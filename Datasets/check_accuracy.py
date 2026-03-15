import json
import os
import re
import pandas as pd
import numpy as np
from sklearn.metrics import classification_report

# Paths
BASE_DIR = os.path.dirname(os.path.abspath(__file__))
MODEL_PATH = os.path.join(BASE_DIR, '..', 'Pathfinder', 'storage', 'app', 'data', 'tfidf_model.json')
DATASET_PATH = os.path.join(BASE_DIR, 'Cloude-Resume', 'Kaggle-Resume', 'Resume.csv')

def clean_text(text):
    text = str(text).lower()
    text = re.sub(r'[^a-z0-9\s\+\#\.]', ' ', text)
    text = re.sub(r'\s+', ' ', text)
    return text.strip()

def get_tfidf_vector(text, vocab, idf_values):
    text = clean_text(text)
    words = text.split()
    vector = np.zeros(len(vocab))
    
    # Calculate term frequency
    counts = {}
    for w in words:
        if w in vocab:
            counts[w] = counts.get(w, 0) + 1
            
    # Apply TF-IDF (using sublinear TF like the training script)
    for i, term in enumerate(vocab):
        if term in counts:
            tf = 1 + np.log(counts[term])
            vector[i] = tf * idf_values[i]
            
    return vector

def main():
    report_path = os.path.join(BASE_DIR, 'accuracy_report.txt')
    with open(report_path, 'w', encoding='utf-8') as report_file:
        def log(msg):
            print(msg)
            report_file.write(msg + '\n')

        log("Loading model...")
        with open(MODEL_PATH, 'r', encoding='utf-8') as f:
            model = json.load(f)
        
        vocab = model['vocabulary']
        idf_values = model['idf_values']
        centroids = model['category_centroids']
        skill_flags = model['skill_flags']
        
        # Load dataset
        log("Loading dataset...")
        df = pd.read_csv(DATASET_PATH)
        
        # Increased sample for better statistical significance
        num_samples = 1000
        test_df = df.sample(min(num_samples, len(df)), random_state=42)
        
        y_true = []
        y_pred = []
        
        log(f"Testing accuracy on {len(test_df)} samples...")
        log("-" * 60)
        log(f"{'Actual Category':<25} | {'Predicted Category':<25} | Result")
        log("-" * 60)
        
        total = 0
        for _, row in test_df.iterrows():
            actual = str(row['Category']).upper().strip()
            text = row['Resume_str']
            
            # 1. Extract and Count words
            words = clean_text(text).split()
            matched_counts = {}
            for w in words:
                if w in vocab:
                    matched_counts[w] = matched_counts.get(w, 0) + 1
            
            # 2. Get TF-IDF Vector (Force 1D)
            cv_vector = get_tfidf_vector(text, vocab, idf_values).ravel()
            
            # 3. Apply Skill Weights matching PHP
            weights = np.array([3.0 if f else 1.0 for f in skill_flags]).ravel()
            weighted_cv = cv_vector * weights
            
            best_score = -1
            best_cat = "UNKNOWN"
            
            # 4. Math matching CVAnalysisService.php
            for cat, centroid_list in centroids.items():
                centroid = np.array(centroid_list).ravel()
                
                # Weighted similarity
                weighted_centroid = centroid * weights
                
                dot = np.sum(weighted_cv * weighted_centroid)
                mag_cv = np.sqrt(np.sum(weighted_cv**2))
                mag_ct = np.sqrt(np.sum(weighted_centroid**2))
                
                score = dot / (mag_cv * mag_ct) if (mag_cv > 0 and mag_ct > 0) else 0
                
                # Keyword Hit Boost matching PHP
                keyword_hit_count = 0
                top_keywords = model.get('category_top_keywords', {}).get(cat, [])
                for kw in top_keywords:
                    if kw in matched_counts:
                        keyword_hit_count += 1
                
                score += (keyword_hit_count * 0.005)

                if score > best_score:
                    best_score = score
                    best_cat = cat
            
            y_true.append(actual)
            y_pred.append(best_cat.upper())
            
            is_correct = actual == best_cat.upper()
            total += 1
            
            # Print first 20 for preview
            if total <= 20:
                status = "✓" if is_correct else "✗"
                log(f"{actual[:25]:<25} | {best_cat[:25]:<25} | {status}")
                
        log("-" * 60)
        log("\n--- Classification Performance Report ---")
        report = classification_report(y_true, y_pred, zero_division=0)
        log(report)
        log("-" * 60)
        
        # Check Skill Quality
        log("\n--- Skill Detection Quality Check ---")
        sample_cvs = test_df.head(5)
        for i, (_, row) in enumerate(sample_cvs.iterrows()):
            text = row['Resume_str']
            words = set(clean_text(text).split())
            detected = []
            for j, term in enumerate(vocab):
                if skill_flags[j] and term in words:
                    detected.append(term)
            
            log(f"\nCV {i+1} ({row['Category']}):")
            log(f"Detected Skills: {', '.join(detected[:15])}")

if __name__ == "__main__":
    main()
