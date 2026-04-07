"""
check_accuracy.py  (V4.1 — Enhanced Reporting)
==============================================
Validates the Pathfinder hybrid model accuracy with detailed reporting.

Features:
  - Tabular F1 report
  - Top 5 Confusion analysis (who gets mixed up with whom)
  - Thematic performance breakdown (Technical, Business, etc.)
  - Skill detection quality sample

Usage:
    python check_accuracy.py
"""

import json
import os
import re
import numpy as np
import pandas as pd
from sklearn.metrics import classification_report, confusion_matrix
from collections import Counter

# ── Paths ──────────────────────────────────────────────────────────────────
BASE_DIR     = os.path.dirname(os.path.abspath(__file__))
MODEL_PATH   = os.path.join(BASE_DIR, '..', 'Pathfinder', 'storage', 'app', 'data', 'tfidf_model.json')
DATASET_PATH = os.path.join(BASE_DIR, 'Cloude-Resume', 'Kaggle-Resume', 'Resume.csv')

# Cluster mapping for thematic analysis
THEMES = {
    'Technical Skills':           ['INFORMATION-TECHNOLOGY', 'ENGINEERING', 'CONSTRUCTION', 'AUTOMOBILE'],
    'Creative & Design':          ['DESIGNER', 'DIGITAL-MEDIA', 'ARTS', 'APPAREL'],
    'Healthcare & Sciences':      ['HEALTHCARE', 'FITNESS'],
    'Business & Management':      ['FINANCE', 'BANKING', 'ACCOUNTANT', 'BUSINESS-DEVELOPMENT', 'SALES', 'CONSULTANT'],
    'Communication & Interpersonal': ['HR', 'PUBLIC-RELATIONS', 'BPO'],
    'Legal & Compliance':         ['ADVOCATE'],
    'Education & Training':       ['TEACHER'],
    'Trades & Applied':           ['AGRICULTURE', 'AVIATION', 'CHEF'],
}
CAT_TO_THEME = {cat: theme for theme, cats in THEMES.items() for cat in cats}

def clean_text(text: str) -> str:
    text = str(text).lower()
    text = re.sub(r'[^a-z0-9\s\+\#\.]', ' ', text)
    text = re.sub(r'\s+', ' ', text)
    return text.strip()


def get_tfidf_vector(text: str, vocab_index: dict, idf_values: list) -> np.ndarray:
    words  = clean_text(text).split()
    counts = {}
    for w in words:
        if w in vocab_index:
            counts[w] = counts.get(w, 0) + 1

    vector = np.zeros(len(idf_values))
    for term, cnt in counts.items():
        idx        = vocab_index[term]
        tf         = 1 + np.log(cnt)
        vector[idx] = tf * idf_values[idx]
    return vector


def minmax(arr: np.ndarray) -> np.ndarray:
    mn, mx = arr.min(), arr.max()
    if mx - mn < 1e-9:
        return np.zeros_like(arr)
    return (arr - mn) / (mx - mn)


SURGICAL_FILTERS = {
    'INFORMATION-TECHNOLOGY': ['javascript', 'php', 'python', 'java', 'sql', 'coding', 'developer', 'linux', 'mysql', 'css', 'html', 'react', 'laravel', 'c++', 'c#', 'cloud', 'aws', 'docker', 'typescript', 'programming', 'devops', 'kubernetes', 'git', 'api'],
    'ADVOCATE':    ['paralegal', 'litigation', 'legal', 'affidavit', 'jurisdiction', 'courtroom', 'testimony', 'lawyer', 'attorney', 'notary', 'mediation'],
    'ACCOUNTANT':  ['cpa', 'accounting', 'auditing', 'gaap', 'ledger', 'payroll', 'taxation', 'bookkeeping', 'audit', 'tax'],
    'HEALTHCARE':  ['clinical', 'patient', 'medical', 'diagnosis', 'nurse', 'physician', 'surgical', 'pharmacology', 'hospital', 'nursing', 'therapy', 'dental'],
    'CHEF':        ['culinary', 'kitchen', 'bakery', 'pastry', 'restaurant', 'cooking', 'chef', 'catering', 'food safety', 'menu'],
    'AVIATION':    ['pilot', 'flight', 'aircraft', 'airline', 'cockpit', 'avionics', 'aviation', 'navigation', 'aerospace'],
    'AGRICULTURE': ['farming', 'crop', 'livestock', 'irrigation', 'agronomy', 'horticulture', 'agriculture', 'forestry', 'harvesting'],
    'SALES':       ['prospecting', 'lead generation', 'salesforce', 'crm', 'cold calling', 'sales', 'retail', 'selling', 'merchandising', 'revenue', 'quota'],
    'CONSTRUCTION':['structural', 'contractor', 'blueprints', 'excavation', 'carpentry', 'construction', 'building', 'plumbing', 'welding'],
    'ENGINEERING': ['mechanical', 'electrical', 'civil', 'structural', 'solidworks', 'cad', 'engineering', 'robotics', 'automation'],
    'APPAREL':     ['fashion', 'textile', 'clothing', 'apparel', 'garment', 'merchandising', 'retail', 'tailoring', 'couture'],
    'FITNESS':     ['trainer', 'gym', 'wellness', 'nutrition', 'athlete', 'coaching', 'aerobics', 'kinesiology', 'personal trainer'],
    'DIGITAL-MEDIA':['social media', 'content', 'seo', 'sem', 'digital marketing', 'advertising', 'copywriting', 'analytics', 'content writer'],
    'HR':          ['recruiting', 'hiring', 'compensation', 'benefits', 'payroll', 'employee relations', 'onboarding', 'hris', 'recruitment'],
    'BPO':         ['outsourcing', 'call center', 'customer support', 'inbound', 'outbound', 'service level', 'sla', 'zendesk', 'bpo', 'contact center'],
    'FINANCE':     ['investment', 'securities', 'banking', 'equity', 'capital', 'wealth', 'portfolio', 'trading', 'finance', 'underwriting'],
    'BANKING':     ['banking', 'loan', 'deposit', 'credit', 'teller', 'mortgage', 'bank branch', 'interbank', 'swift', 'remittance'],
    'CONSULTANT':  ['strategy', 'optimization', 'business analyst', 'roadmap', 'transformation', 'management consulting', 'stakeholder'],
    'PUBLIC-RELATIONS':['media relations', 'press release', 'publicity', 'branding', 'crisis management', 'spokesperson'],
    'ARTS':        ['fine arts', 'gallery', 'curator', 'visual arts', 'sculpture', 'painting', 'exhibition', 'arts'],
    'BUSINESS-DEVELOPMENT': ['business development', 'growth strategy', 'partnership', 'market expansion', 'pipeline', 'revenue growth', 'b2b sales', 'lead generation'],
    'AUTOMOBILE':  ['automotive', 'vehicle', 'mechanic', 'car repair', 'powertrain', 'chassis', 'diagnostics', 'automobile'],
    'DESIGNER':    ['ux', 'ui', 'photoshop', 'illustrator', 'figma', 'sketch', 'typography', 'wireframe', 'graphic design'],
    'TEACHER':     ['pedagogy', 'curriculum', 'classroom', 'lesson plan', 'teaching', 'tutoring', 'instructional', 'education'],
}


def main():
    report_path = os.path.join(BASE_DIR, 'accuracy_report.txt')

    with open(report_path, 'w', encoding='utf-8') as report_file:

        def log(msg: str):
            print(msg)
            report_file.write(msg + '\n')

        log("=" * 70)
        log("PATHFINDER MODEL ANALYSIS REPORT (V4.1)")
        log("=" * 70)

        # ── Load model ─────────────────────────────────────────────────
        log("\nLoading model...")
        with open(MODEL_PATH, 'r', encoding='utf-8') as f:
            model = json.load(f)

        vocab       = model['vocabulary']
        vocab_index = {term: i for i, term in enumerate(vocab)}
        idf_values  = model['idf_values']
        centroids   = model['category_centroids']
        skill_flags = model['skill_flags']
        svc_coef      = np.array(model.get('svc_coef',      []))
        svc_intercept = np.array(model.get('svc_intercept', []))
        svc_classes   = model.get('svc_classes', [])
        has_svc       = len(svc_coef) > 0

        log(f"  Model Type : {'Hybrid LinearSVC + Cosine' if has_svc else 'Cosine Only'}")
        log(f"  Vocabulary : {len(vocab)} terms")
        log(f"  Categories : {len(centroids)}")

        # ── Load test dataset ──────────────────────────────────────────
        log("\nLoading test dataset...")
        df = pd.read_csv(DATASET_PATH)
        num_samples = 1500
        test_df     = df.sample(min(num_samples, len(df)), random_state=42)
        log(f"  Testing on {len(test_df)} samples from Resume.csv")

        y_true = []
        y_pred = []
        y_pred_top3 = []

        log("\nEvaluating predictions...")
        total = 0
        for _, row in test_df.iterrows():
            actual = str(row['Category']).upper().strip()
            text   = row['Resume_str']
            words  = set(clean_text(text).split())

            cv_vector = get_tfidf_vector(text, vocab_index, idf_values)

            # SVC score
            if has_svc:
                raw_svc = svc_coef @ cv_vector + svc_intercept
                svc_norm = minmax(raw_svc)
            else:
                svc_norm = None

            # Cosine score
            skill_weights = np.array([10.0 if f else 1.0 for f in skill_flags])
            weighted_cv   = cv_vector * skill_weights
            mag_cv        = np.sqrt(np.sum(weighted_cv ** 2))

            cosine_scores = {}
            for cat, centroid_list in centroids.items():
                centroid   = np.array(centroid_list)
                w_centroid = centroid * skill_weights
                mag_ct     = np.sqrt(np.sum(w_centroid ** 2))
                dot        = np.sum(weighted_cv * w_centroid)
                cos_score  = dot / (mag_cv * mag_ct) if (mag_cv > 0 and mag_ct > 0) else 0.0
                cosine_scores[cat] = cos_score

            # Blend (Raw SVC + Cosine, skipping surgical filters for accurate Kaggle benchmarking)
            final_scores = {}
            cats_ordered = svc_classes if has_svc else list(centroids.keys())
            for i, cat in enumerate(cats_ordered):
                cos_val    = cosine_scores.get(cat, 0.0)
                if has_svc:
                    svc_val  = float(svc_norm[i])
                    # 90% SVC (data-driven), 10% Cosine (content-fallback)
                    combined = 0.90 * svc_val + 0.10 * cos_val
                else:
                    combined = cos_val
                final_scores[cat] = combined

            # Sort scores to get top 3
            sorted_cats = sorted(final_scores.keys(), key=lambda c: final_scores[c], reverse=True)
            best_cat = sorted_cats[0]
            top_3_cats = sorted_cats[:3]
            
            y_true.append(actual)
            y_pred.append(best_cat.upper())
            y_pred_top3.append([c.upper() for c in top_3_cats])
            total += 1

        # ── 1. Pretty Table Report ─────────────────────────────────────
        report_dict = classification_report(y_true, y_pred, output_dict=True, zero_division=0)
        
        log("\n" + "=" * 70)
        log(f"{'CATEGORY':<25} {'PRECISION':>10} {'RECALL':>10} {'F1-SCORE':>10} {'SAMPLES':>10}")
        log("-" * 70)
        
        categories_to_show = sorted([c for c in report_dict.keys() if c not in ['accuracy', 'macro avg', 'weighted avg']])
        
        for cat in categories_to_show:
            metrics = report_dict[cat]
            f1 = metrics['f1-score']
            status = "✓" if f1 >= 0.8 else "!" if f1 >= 0.6 else "✗"
            log(f"{status} {cat[:23]:<23} {metrics['precision']:>10.2f} {metrics['recall']:>10.2f} {metrics['f1-score']:>10.2f} {int(metrics['support']):>10}")
            
        log("-" * 70)
        
        # Calculate Top-1 and Top-3 Accuracy
        correct_top1 = sum(a == p for a, p in zip(y_true, y_pred))
        correct_top3 = sum(a in p3 for a, p3 in zip(y_true, y_pred_top3))
        
        acc_top1 = correct_top1 / len(y_true) * 100
        acc_top3 = correct_top3 / len(y_true) * 100
        
        log(f"TOP-1 EXACT MATCH ACCURACY  : {acc_top1:5.2f}% ({correct_top1}/{len(y_true)})")
        log(f"TOP-3 RECOMMENDATION ACCURACY: {acc_top3:5.2f}% ({correct_top3}/{len(y_true)}) *** PRIMARY APP METRIC ***")
        log(f"MACRO AVG F1 (Top-1)        : {report_dict['macro avg']['f1-score']:5.2f}")
        log("=" * 70)

        # ── 2. Thematic Breakdown ──────────────────────────────────────
        log("\n" + "=" * 70)
        log("THEMATIC PERFORMANCE (Grouping categories into fields)")
        log("-" * 70)
        
        theme_metrics = {theme: [] for theme in THEMES.keys()}
        for cat in categories_to_show:
            theme = CAT_TO_THEME.get(cat, 'Other')
            if theme in theme_metrics:
                theme_metrics[theme].append(report_dict[cat]['f1-score'])
        
        for theme, f1s in theme_metrics.items():
            avg_f1 = np.mean(f1s) if f1s else 0.0
            bar = '█' * int(avg_f1 * 30)
            log(f"{theme:<30} {avg_f1:>6.2f}  {bar}")
        log("-" * 70)

        # ── 3. Confusion Analysis ──────────────────────────────────────
        log("\n" + "=" * 70)
        log("TOP 5 MISCLASSIFICATIONS (Who gets mixed up?)")
        log("-" * 70)
        
        labels = sorted(list(set(y_true) | set(y_pred)))
        cm = confusion_matrix(y_true, y_pred, labels=labels)
        
        mistakes = []
        for i, row in enumerate(cm):
            actual_cat = labels[i]
            for j, val in enumerate(row):
                if i != j and val > 0:
                    predicted_cat = labels[j]
                    mistakes.append((actual_cat, predicted_cat, val))
        
        mistakes.sort(key=lambda x: x[2], reverse=True)
        for actual, predicted, count in mistakes[:8]:
            log(f"Actual: {actual:<20} mispredicted as {predicted:<20} ({count} times)")
        
        log("-" * 70)

        # ── 4. Skill Sample ───────────────────────────────────────────
        log("\n" + "=" * 70)
        log("Skill Detection Health Sample")
        log("-" * 70)
        for i, (_, row) in enumerate(test_df.head(3).iterrows()):
            text  = row['Resume_str']
            words = set(clean_text(text).split())
            detected = [vocab[j] for j in range(len(vocab)) if skill_flags[j] and vocab[j] in words]
            log(f"CV: {str(row['Category'])[:20]:<20} | Found: {', '.join(detected[:12])}")

        log("=" * 70)
        log("\nDetailed Report saved to: Datasets/accuracy_report.txt")

if __name__ == "__main__":
    main()
