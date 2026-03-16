import json
import os
import pandas as pd
import numpy as np
from sklearn.metrics import confusion_matrix

# Paths
BASE_DIR = os.path.dirname(os.path.abspath(__file__))
MODEL_PATH = os.path.join(BASE_DIR, '..', 'Pathfinder', 'storage', 'app', 'data', 'tfidf_model.json')
DATASET_PATH = os.path.join(BASE_DIR, 'Cloude-Resume', 'Kaggle-Resume', 'Resume.csv')

def clean_text(text):
    import re
    text = str(text).lower()
    text = re.sub(r'[^a-z0-9\s\+\#\.]', ' ', text)
    text = re.sub(r'\s+', ' ', text)
    return text.strip()

def get_tfidf_vector(text, vocab, idf_values):
    text = clean_text(text)
    words = text.split()
    vector = np.zeros(len(vocab))
    counts = {}
    for w in words:
        if w in vocab:
            counts[w] = counts.get(w, 0) + 1
    for i, term in enumerate(vocab):
        if term in counts:
            tf = 1 + np.log(counts[term])
            vector[i] = tf * idf_values[i]
    return vector

def main():
    print("Loading model and dataset...")
    with open(MODEL_PATH, 'r', encoding='utf-8') as f:
        model = json.load(f)
    
    vocab = model['vocabulary']
    idf_values = model['idf_values']
    centroids = model['category_centroids']
    skill_flags = model['skill_flags']
    weights = np.array([3.0 if f else 1.0 for f in skill_flags]).ravel()
    
    df = pd.read_csv(DATASET_PATH)
    test_df = df.sample(min(800, len(df)), random_state=42)
    
    true_labels = []
    pred_labels = []
    
    print(f"Analyzing {len(test_df)} samples for confusion matrix...")
    
    categories = sorted(list(centroids.keys()))
    
    for _, row in test_df.iterrows():
        actual = row['Category'].upper()
        text = row['Resume_str']
        
        cv_vector = get_tfidf_vector(text, vocab, idf_values).ravel()
        weighted_cv = cv_vector * weights
        
        best_score = -1
        best_cat = "UNKNOWN"
        
        for cat, centroid_list in centroids.items():
            centroid = np.array(centroid_list).ravel()
            weighted_centroid = centroid * weights
            
            dot = np.sum(weighted_cv * weighted_centroid)
            mag_cv = np.sqrt(np.sum(weighted_cv**2))
            mag_ct = np.sqrt(np.sum(weighted_centroid**2))
            
            score = dot / (mag_cv * mag_ct) if (mag_cv > 0 and mag_ct > 0) else 0
            
            # Boost logic
            keyword_hit_count = 0
            top_keywords = model.get('category_top_keywords', {}).get(cat, [])
            words_in_cv = set(clean_text(text).split())
            for kw in top_keywords:
                if kw in words_in_cv:
                    keyword_hit_count += 1
            score += (keyword_hit_count * 0.005)

            if score > best_score:
                best_score = score
                best_cat = cat
        
        true_labels.append(actual)
        pred_labels.append(best_cat)

    # Compute confusion matrix
    cm = confusion_matrix(true_labels, pred_labels, labels=categories)
    
    # Save confusion matrix to CSV for analysis
    cm_df = pd.DataFrame(cm, index=categories, columns=categories)
    cm_df.to_csv(os.path.join(BASE_DIR, 'confusion_matrix.csv'))
    
    print("\nConfusion Matrix top 5 mismatches:")
    mismatches = []
    for i in range(len(categories)):
        for j in range(len(categories)):
            if i != j and cm[i][j] > 0:
                mismatches.append((categories[i], categories[j], cm[i][j]))
    
    mismatches.sort(key=lambda x: x[2], reverse=True)
    for actual, pred, count in mismatches[:10]:
        print(f"  {actual} misclassified as {pred}: {count} times")

if __name__ == "__main__":
    main()
