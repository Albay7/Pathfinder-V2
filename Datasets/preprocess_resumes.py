"""
preprocess_resumes.py  (V4 — Hybrid Content-Based Filtering)
============================================================
Pathfinder CV Analysis Model Trainer — Hybrid TF-IDF + LinearSVC Pipeline.

Content-Based Filtering approach:
  1. TF-IDF  — extracts weighted keyword features from resume text
  2. LinearSVC — learns decision boundaries from labeled training data
       (far superior to cosine-to-centroid for multi-class classification)
  3. Cosine Centroid — kept as a fallback/tie-breaker signal

Usage:
    # First run the data normalizer:
    python normalize_datasets.py

    # Then train:
    pip install pandas scikit-learn openpyxl
    python preprocess_resumes.py

Output:
    ../Pathfinder/storage/app/data/tfidf_model.json
"""

import os
import re
import json
import numpy as np
import pandas as pd
from sklearn.feature_extraction.text import TfidfVectorizer, ENGLISH_STOP_WORDS
from sklearn.svm import LinearSVC
from sklearn.calibration import CalibratedClassifierCV
from sklearn.preprocessing import LabelEncoder
from sklearn.model_selection import cross_val_score

# ============================================================
# 0. Stopwords  (generic resume noise — NOT skills)
# ============================================================

RESUME_STOPWORDS = [
    # Action verbs
    'responsible', 'managed', 'developed', 'worked', 'assisted', 'maintained',
    'created', 'performed', 'provided', 'implemented', 'coordinated', 'organized',
    'supported', 'delivered', 'achieved', 'ensured', 'utilized', 'prepared',
    'conducted', 'facilitated', 'participated', 'contributed', 'established',
    'improved', 'increased', 'reduced', 'led', 'directed', 'oversaw', 'supervised',
    'handled', 'completed', 'executed', 'initiated', 'launched', 'built',
    'designed', 'trained', 'served', 'collaborated', 'communicated',
    'demonstrated', 'identified', 'resolved', 'monitored', 'evaluated',
    'reviewed', 'analyzed', 'presented', 'reported', 'documented',
    'helped', 'gained', 'received', 'focused', 'applied', 'attended',
    'graduated', 'studied', 'obtained', 'earned', 'awarded', 'selected',
    'promoted', 'recognized', 'published', 'submitted', 'assigned',
    'planned', 'scheduled', 'processed', 'generated', 'updated',
    'operated', 'produced', 'engaged', 'addressed',
    # Generic resume nouns
    'company', 'organization', 'team', 'years', 'experience', 'role',
    'position', 'department', 'office', 'environment', 'ability', 'knowledge',
    'understanding', 'responsibilities', 'duties', 'tasks', 'skills',
    'work', 'working', 'including', 'various', 'multiple', 'ensuring',
    'using', 'based', 'related', 'level', 'well', 'also', 'new',
    'high', 'strong', 'excellent', 'good', 'effective', 'successful',
    'professional', 'extensive', 'significant', 'relevant',
    'specific', 'general', 'overall', 'additional', 'major', 'key',
    'proficient', 'familiar', 'experienced', 'skilled', 'capable',
    'results', 'goals', 'objectives', 'requirements', 'standards',
    'processes', 'procedures', 'operations', 'activities', 'functions',
    'services', 'solutions', 'strategies', 'programs', 'initiatives',
    'projects', 'plans', 'reports', 'records', 'documents', 'materials',
    'resources', 'information', 'issues', 'problems', 'opportunities',
    'areas', 'aspects', 'members', 'staff', 'personnel', 'clients',
    'customers', 'stakeholders', 'partners', 'vendors', 'colleagues',
    'university', 'college', 'school', 'institute', 'degree', 'bachelor',
    'master', 'diploma', 'certificate', 'gpa', 'honors', 'dean',
    'resume', 'curriculum', 'vitae',
    # Dates
    'january', 'february', 'march', 'april', 'may', 'june',
    'july', 'august', 'september', 'october', 'november', 'december',
    'present', 'current', 'currently', 'daily', 'weekly', 'monthly',
    'annual', 'annually', 'semester', 'quarter', 'year',
    # Filler
    'etc', 'per', 'via', 'within', 'across', 'throughout', 'along',
    'regarding', 'according', 'approximately', 'involved', 'required',
    'necessary', 'needed', 'appropriate', 'available', 'able', 'highly',
    'varied', 'different', 'wide', 'variety', 'outstanding', 'proven',
    'track', 'record', 'dynamic', 'motivated', 'proactive', 'detail',
    'oriented', 'background', 'basis', 'large', 'small', 'many',
    'career', 'project', 'management', 'support', 'technical',
]

ALL_STOPWORDS = list(ENGLISH_STOP_WORDS) + RESUME_STOPWORDS

# ============================================================
# 0b. Skill Denylist
# ============================================================

SKILL_DENYLIST = {
    'jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec',
    'january', 'february', 'march', 'april', 'june', 'july', 'august', 'september',
    'october', 'november', 'december', 'city', 'state', 'usa', 'united', 'states',
    'american', 'national', 'local', 'regional', 'global', 'international',
    'area', 'basis', 'level', 'role', 'position', 'month', 'months', 'year', 'years',
    'number', 'member', 'groups', 'item', 'items', 'individual', 'personal',
    'basic', 'advanced', 'senior', 'junior', 'entry', 'major', 'minor',
    'degree', 'university', 'college', 'school', 'studies', 'student', 'students',
    'skill', 'skills', 'work', 'working', 'job', 'experience',
    'knowledge', 'ability', 'capability', 'history', 'summary', 'highlights',
    'accomplishments', 'responsibilities', 'duties', 'tasks', 'activities',
    'information', 'details', 'detailed', 'brief', 'background',
    'academic', 'agencies', 'associate', 'associates', 'associated',
    'efficiency', 'efficient', 'efficiently', 'facilitate', 'proficiency',
    'society', 'pricing', 'specialist', 'coordinator', 'manager', 'director',
    'organizations', 'participation', 'recipient',
}

# ============================================================
# 1. Paths
# ============================================================

BASE_DIR   = os.path.dirname(os.path.abspath(__file__))
DATA_DIR   = os.path.join(BASE_DIR, 'Cloude-Resume', 'Kaggle-Resume')

# Primary: use normalized corpus produced by normalize_datasets.py
NORMALIZED_PATH = os.path.join(BASE_DIR, 'normalized_training_data.csv')
# Fallback: raw Resume.csv
RESUME_PATH     = os.path.join(DATA_DIR, 'Resume.csv')

# ESCO Taxonomy (for skills tagging only — not classification)
ESCO_SKILLS_PATH = os.path.join(DATA_DIR, 'skills_en.csv')
ESCO_OCC_PATH    = os.path.join(DATA_DIR, 'occupations_en.csv')

OUTPUT_DIR  = os.path.join(BASE_DIR, '..', 'Pathfinder', 'storage', 'app', 'data')
OUTPUT_PATH = os.path.join(OUTPUT_DIR, 'tfidf_model.json')

# ============================================================
# 2. Load training data
# ============================================================

def clean_text(text):
    """Lightweight text normalizer for inference-time use (no HTML etc.)."""
    text = str(text)
    text = re.sub(r'\s+', ' ', text)
    text = text.lower()
    text = re.sub(r'[^a-z0-9\s\+\#\.]', ' ', text)
    text = re.sub(r'\s+', ' ', text)
    return text.strip()


print("=" * 60)
print("PATHFINDER HYBRID MODEL TRAINER (V4)")
print("TF-IDF + LinearSVC Content-Based Filtering")
print("=" * 60)

if os.path.exists(NORMALIZED_PATH):
    print(f"\n[1/7] Loading normalized corpus from {os.path.basename(NORMALIZED_PATH)}...")
    df = pd.read_csv(NORMALIZED_PATH)
    # The normalized file already has clean text, run the lightweight pass
    df['clean_text'] = df['text'].apply(clean_text)
else:
    print(f"\n[1/7] normalized_training_data.csv not found. Falling back to Resume.csv...")
    print("      (Run normalize_datasets.py first for best accuracy!)")
    raw = pd.read_csv(RESUME_PATH)
    df = pd.DataFrame({
        'clean_text': raw['Resume_str'].apply(clean_text),
        'category':   raw['Category'].str.upper()
    })

# Drop short/empty
df = df[df['clean_text'].str.len() > 50].reset_index(drop=True)
print(f"    Training corpus: {len(df)} samples across {df['category'].nunique()} categories")

dist = df['category'].value_counts()
print("    Category distribution:")
for cat, cnt in dist.items():
    bar = '█' * min(cnt // 5, 35)
    print(f"      {cat:<25} {cnt:>4}  {bar}")

# ============================================================
# 3. TF-IDF Vectorizer — Content Feature Extraction
# ============================================================

print("\n[2/7] Fitting TF-IDF vectorizer...")
print("      (TF-IDF: converts resume text into weighted keyword feature vectors)")

vectorizer = TfidfVectorizer(
    max_features=10000,         # Larger vocabulary for better coverage
    stop_words=ALL_STOPWORDS,
    min_df=3,                   # Must appear in ≥3 documents (reduce noise)
    max_df=0.45,                # Must NOT appear in >45% of docs (too generic)
    sublinear_tf=True,          # log(1+tf) — dampens high-frequency terms
    ngram_range=(1, 2),         # Unigrams + bigrams
    token_pattern=r'(?u)\b[a-z][a-z\+\#\.]{1,25}\b'
)

X = vectorizer.fit_transform(df['clean_text'])
vocabulary  = vectorizer.get_feature_names_out().tolist()
idf_values  = vectorizer.idf_.tolist()
categories  = sorted(df['category'].unique().tolist())

print(f"    Vocabulary size : {len(vocabulary)} terms")
print(f"    Feature matrix  : {X.shape[0]} × {X.shape[1]}")

# ============================================================
# 4. LinearSVC — Supervised Classification
# ============================================================
# LinearSVC learns a hyperplane that maximally separates each
# category from all others (one-vs-rest). This is the "real"
# classifier replacing the hand-tuned centroid cosine approach.

print("\n[3/7] Training LinearSVC classifier (content-based filtering)...")
print("      class_weight='balanced' corrects for unequal class sizes")

le = LabelEncoder()
y  = le.fit_transform(df['category'])
svc_classes = le.classes_.tolist()   # ordered list of category names

# Calibrated: wraps LinearSVC to produce probability scores
svc = LinearSVC(
    C=1.0,                  # Regularization 
    max_iter=3000,
    class_weight='balanced', # Auto-compensates for thin classes
    dual=True,
)
svc.fit(X, y)

# Cross-validation estimate (5-fold)
print("    Running 5-fold cross-validation...")
cv_scores = cross_val_score(svc, X, y, cv=5, scoring='accuracy')
print(f"    CV Accuracy: {cv_scores.mean()*100:.1f}% ± {cv_scores.std()*100:.1f}%")

# Extract SVC coefficients for JSON storage
# coef_ shape: (n_classes, n_features)
svc_coef      = svc.coef_.tolist()        # list of lists [n_classes][n_features]
svc_intercept = svc.intercept_.tolist()   # list [n_classes]

print(f"    SVC classes   : {len(svc_classes)}")
print(f"    SVC coef shape: {len(svc_coef)} × {len(svc_coef[0])}")

# ============================================================
# 5. Compute enhanced centroids  (cosine fallback)
# ============================================================
# Even with SVC as primary scorer, we keep centroids as a
# tie-breaker and for the PHP-side cosine scoring fallback.

print("\n[4/7] Computing data-driven category centroids...")

# Load ESCO for ICF weighting
esco_skills = set()
try:
    s_df = pd.read_csv(ESCO_SKILLS_PATH)
    esco_skills = set(s_df['preferredLabel'].str.lower().dropna().unique())
    print(f"    Loaded {len(esco_skills)} ESCO skills for ICF weighting")
except Exception as e:
    print(f"    WARNING: ESCO skills unavailable — {e}")

# Compute Inverse Category Frequency (ICF)
term_category_presence = np.zeros(len(vocabulary))
for cat in categories:
    mask = df['category'] == cat
    if mask.any():
        cat_matrix = X[mask.values]
        presence   = (cat_matrix.sum(axis=0).A1 > 0).astype(float)
        term_category_presence += presence

icf_weights = np.log((len(categories) + 1) / (term_category_presence + 1))
icf_weights = (icf_weights - icf_weights.min()) / (icf_weights.max() - icf_weights.min() + 1e-6)

category_centroids = {}
for cat in categories:
    mask = df['category'] == cat
    if mask.any():
        cat_matrix   = X[mask.values]
        raw_centroid = cat_matrix.mean(axis=0).A1
        # Apply ICF sharpening — penalizes terms common across categories
        sharpened = raw_centroid * icf_weights
    else:
        sharpened = np.zeros(len(vocabulary))

    # ── Minimal anchor boost (15% only — data drives 85%) ───────────────
    # This is intentionally much lower than the old 60% anchor weight.
    CATEGORY_ANCHORS = {
        "INFORMATION-TECHNOLOGY": ["software", "developer", "coding", "algorithm", "database",
                                    "backend", "frontend", "devops", "cloud", "javascript",
                                    "python", "java", "cybersecurity", "sysadmin", "programming"],
        "FINANCE":     ["investment", "securities", "banking", "equity", "capital", "wealth",
                        "portfolio", "trading", "underwriting", "derivatives", "forecasting", "hedge fund"],
        "ENGINEERING": ["mechanical", "electrical", "civil", "structural", "cad", "solidworks",
                        "thermodynamics", "circuitry", "infrastructure", "robotics"],
        "ACCOUNTANT":  ["auditing", "taxation", "bookkeeping", "cpa", "gaap", "ledger",
                        "payroll", "reconciliation", "audit", "tax"],
        "HEALTHCARE":  ["clinical", "patient", "medical", "diagnosis", "nurse", "physician",
                        "surgical", "pharmacology", "therapy", "hospital", "nursing"],
        "SALES":       ["prospecting", "salesforce", "crm", "cold calling", "revenue",
                        "b2b", "quota", "closing", "merchandising"],
        "CONSULTANT":  ["strategy", "optimization", "roadmap", "transformation",
                        "management consulting", "stakeholder", "feasibility"],
        "DESIGNER":    ["graphics", "ux", "ui", "photoshop", "illustrator", "figma",
                        "sketch", "typography", "branding", "layout"],
        "TEACHER":     ["pedagogy", "curriculum", "classroom", "lesson plan",
                        "teaching", "tutoring", "instructional", "education", "syllabus", "educator"],
        "ADVOCATE":    ["paralegal", "litigation", "legal", "affidavit", "jurisdiction",
                        "courtroom", "testimony", "attorney", "mediation", "lawyer",
                        "jurisprudence", "counsel", "tribunal"],
        "CHEF":        ["culinary", "kitchen", "bakery", "pastry", "menu",
                        "hospitality", "food safety", "catering", "restaurant"],
        "AVIATION":    ["pilot", "flight", "aircraft", "airline", "navigation",
                        "aerospace", "cockpit", "avionics"],
        "FITNESS":     ["trainer", "gym", "wellness", "nutrition", "athlete",
                        "coaching", "aerobics", "kinesiology"],
        "APPAREL":     ["fashion", "textile", "clothing", "garment", "merchandising",
                        "tailoring", "fibers", "couture"],
        "CONSTRUCTION":["site", "structural", "contractor", "blueprints", "excavation",
                        "concrete", "carpentry", "plumbing"],
        "PUBLIC-RELATIONS":["media relations", "press release", "publicity", "branding",
                             "crisis management", "spokesperson", "journalism"],
        "HR":          ["recruiting", "hiring", "compensation", "benefits",
                        "employee relations", "onboarding", "hris", "succession"],
        "DIGITAL-MEDIA":["social media", "content", "seo", "sem", "digital marketing",
                          "advertising", "copywriting", "engagement", "analytics"],
        "AGRICULTURE": ["farming", "crop", "livestock", "irrigation", "soil",
                        "harvesting", "pest control", "agronomy", "horticulture"],
        "AUTOMOBILE":  ["automotive", "vehicle", "mechanic", "transportation", "chassis",
                        "powertrain", "diagnostics", "car repair"],
        "BPO":         ["call center", "outsourcing", "customer support", "inbound",
                        "outbound", "sla", "zendesk", "contact center"],
        "ARTS":        ["fine arts", "gallery", "curator", "sculpture", "painting",
                        "exhibition", "aesthetic", "museum", "visual arts"],
        "BANKING":     ["banking", "loan", "branch", "deposit", "credit", "debit",
                        "teller", "mortgage", "interbank", "swift"],
        "BUSINESS-DEVELOPMENT": ["business development", "growth strategy", "partnership",
                                  "market expansion", "lead generation", "b2b sales",
                                  "pipeline", "strategic alliances", "revenue growth", "acquisitions"],
    }

    anchors = CATEGORY_ANCHORS.get(cat, [])
    anchors.append(cat.lower().replace('-', ' '))

    anchor_vector = np.zeros(len(vocabulary))
    for i, term in enumerate(vocabulary):
        if any(anchor in term for anchor in anchors):
            anchor_vector[i] = 1.0

    # 85% data, 15% anchor (was 40% / 60% — far better now)
    combined = (0.85 * sharpened) + (0.15 * anchor_vector)

    # Top-300 sparsity (only keep most discriminative terms per category)
    top_300 = combined.argsort()[-300:]
    final   = np.zeros_like(combined)
    final[top_300] = combined[top_300]

    total = np.sum(final)
    if total > 0:
        final = final / total

    category_centroids[cat] = final.tolist()

print(f"    Centroids computed for {len(category_centroids)} categories")

# ============================================================
# 6. Extract top keywords + build skill flags
# ============================================================

print("\n[5/7] Extracting top keywords per category...")

category_top_keywords = {}
for cat in categories:
    centroid  = np.array(category_centroids[cat])
    top_idx   = centroid.argsort()[-30:][::-1]
    keywords  = [vocabulary[i] for i in top_idx if centroid[i] > 0.001]
    category_top_keywords[cat] = keywords

# Skill detection: ESCO-backed whitelist + extra technical terms
EXTRA_SKILL_TERMS = {
    'programming', 'coding', 'software', 'hardware', 'database', 'networking',
    'security', 'cloud', 'devops', 'frontend', 'backend', 'fullstack',
    'javascript', 'python', 'java', 'html', 'css', 'sql', 'react', 'angular',
    'node', 'docker', 'kubernetes', 'aws', 'azure', 'linux', 'windows',
    'api', 'testing', 'debugging', 'agile', 'scrum', 'git',
    'machine learning', 'data analysis', 'data science', 'artificial intelligence',
    'deep learning', 'natural language', 'computer vision', 'statistics',
    'algorithms', 'data structures', 'web development', 'mobile development',
    'cybersecurity', 'blockchain', 'iot', 'embedded', 'firmware',
    # Healthcare
    'nursing', 'clinical', 'patient care', 'diagnosis', 'treatment',
    'pharmacy', 'surgical', 'radiology', 'cardiology', 'pediatrics',
    'emergency', 'rehabilitation', 'therapy', 'pathology', 'anatomy',
    'physiology', 'epidemiology', 'biomedical', 'dental',
    # Business/Finance
    'accounting', 'auditing', 'taxation', 'bookkeeping', 'budgeting',
    'forecasting', 'investment', 'banking', 'insurance', 'underwriting',
    'financial analysis', 'risk management', 'portfolio', 'equity',
    'marketing', 'sales', 'advertising', 'branding', 'merchandising',
    'procurement', 'supply chain', 'logistics', 'inventory',
    'negotiation', 'arbitration', 'mediation', 'litigation',
    # Design/Creative
    'graphic design', 'illustration', 'typography', 'animation',
    'photography', 'videography', 'editing', 'photoshop', 'illustrator',
    'figma', 'sketch', 'prototyping', 'wireframing', 'user interface',
    'user experience', 'layout', 'color theory',
    # Engineering
    'mechanical', 'electrical', 'civil', 'structural', 'chemical',
    'aerospace', 'automotive', 'manufacturing', 'welding', 'machining',
    'cad', 'autocad', 'solidworks', 'matlab', 'simulation',
    'thermodynamics', 'hydraulics', 'robotics',
    # Education
    'teaching', 'tutoring', 'curriculum', 'pedagogy', 'assessment',
    'classroom', 'instruction', 'mentoring', 'counseling',
    # Culinary
    'cooking', 'baking', 'catering', 'food safety', 'menu planning',
    'hospitality', 'bartending', 'pastry',
    # Agriculture
    'farming', 'irrigation', 'horticulture', 'agronomy', 'livestock',
    'crop', 'soil', 'fertilizer', 'pesticide', 'harvesting',
    # Legal
    'legal research', 'contract', 'compliance', 'regulatory',
    'intellectual property', 'patent', 'trademark', 'copyright',
    # Soft skills
    'leadership', 'teamwork', 'communication', 'problem solving',
    'critical thinking', 'time management', 'adaptability',
    'creativity', 'collaboration', 'presentation', 'interpersonal',
    'conflict resolution', 'decision making', 'analytical',
    'project management', 'strategic planning', 'public speaking',
}

skill_whitelist = esco_skills | EXTRA_SKILL_TERMS

skill_flags = []
for term in vocabulary:
    term_lower = term.lower()
    if term_lower in SKILL_DENYLIST:
        skill_flags.append(False)
    elif term_lower in skill_whitelist:
        skill_flags.append(True)
    elif any(s in term_lower for s in EXTRA_SKILL_TERMS):
        skill_flags.append(True)
    else:
        skill_flags.append(False)

print(f"    Skills tagged: {sum(skill_flags)}/{len(vocabulary)} terms")

# ============================================================
# 6b. Cluster profiles
# ============================================================

CATEGORY_TO_CLUSTER = {
    'INFORMATION-TECHNOLOGY': 'Technical Skills',
    'ENGINEERING':            'Technical Skills',
    'CONSTRUCTION':           'Technical Skills',
    'AUTOMOBILE':             'Technical Skills',
    'DESIGNER':               'Creative & Design',
    'DIGITAL-MEDIA':          'Creative & Design',
    'ARTS':                   'Creative & Design',
    'APPAREL':                'Creative & Design',
    'HEALTHCARE':             'Healthcare & Sciences',
    'FITNESS':                'Healthcare & Sciences',
    'FINANCE':                'Business & Management',
    'BANKING':                'Business & Management',
    'ACCOUNTANT':             'Business & Management',
    'BUSINESS-DEVELOPMENT':   'Business & Management',
    'SALES':                  'Business & Management',
    'CONSULTANT':             'Business & Management',
    'HR':                     'Communication & Interpersonal',
    'PUBLIC-RELATIONS':       'Communication & Interpersonal',
    'BPO':                    'Communication & Interpersonal',
    'ADVOCATE':               'Legal & Compliance',
    'TEACHER':                'Education & Training',
    'AGRICULTURE':            'Trades & Applied',
    'AVIATION':               'Trades & Applied',
    'CHEF':                   'Trades & Applied',
}

CLUSTER_NAMES = sorted(set(CATEGORY_TO_CLUSTER.values()))

term_clusters = {cluster: [] for cluster in CLUSTER_NAMES}
for i, term in enumerate(vocabulary):
    best_cat = max(categories, key=lambda c: category_centroids[c][i])
    cluster  = CATEGORY_TO_CLUSTER.get(best_cat, 'Business & Management')
    term_clusters[cluster].append(i)

category_cluster_profiles = {}
for cat in categories:
    centroid = np.array(category_centroids[cat])
    profile  = {}
    for cluster_name in CLUSTER_NAMES:
        idxs = term_clusters[cluster_name]
        profile[cluster_name] = float(np.mean([centroid[i] for i in idxs])) if idxs else 0.0
    max_val = max(profile.values()) if profile else 1.0
    if max_val > 0:
        profile = {k: round(v / max_val, 4) for k, v in profile.items()}
    category_cluster_profiles[cat] = profile

# ============================================================
# 7. Category → Job Roles mapping
# ============================================================

category_roles = {
    "INFORMATION-TECHNOLOGY": [
        {"title": "Software Developer",   "description": "Designs, codes, and maintains software applications using various programming languages and frameworks."},
        {"title": "Web Developer",        "description": "Builds and maintains websites and web applications with frontend and backend technologies."},
        {"title": "IT Support Specialist","description": "Provides technical support, troubleshoots hardware/software issues, and maintains IT infrastructure."}
    ],
    "BUSINESS-DEVELOPMENT": [
        {"title": "Business Development Manager","description": "Identifies growth opportunities, builds strategic partnerships, and drives revenue expansion."},
        {"title": "Sales Strategist",            "description": "Develops and implements sales strategies to achieve business objectives and market penetration."},
        {"title": "Marketing Coordinator",       "description": "Plans and executes marketing campaigns, manages brand communications, and analyzes market trends."}
    ],
    "FINANCE": [
        {"title": "Financial Analyst", "description": "Analyzes financial data, prepares investment reports, and advises on financial planning decisions."},
        {"title": "Investment Banker", "description": "Facilitates capital markets transactions, advises on mergers and acquisitions, and manages financial deals."},
        {"title": "Risk Manager",      "description": "Identifies, assesses, and mitigates financial and operational risks for organizations."}
    ],
    "ENGINEERING": [
        {"title": "Civil Engineer",      "description": "Designs and oversees construction of infrastructure projects including roads, bridges, and buildings."},
        {"title": "Mechanical Engineer", "description": "Designs, develops, and tests mechanical systems, devices, and thermal equipment."},
        {"title": "Electrical Engineer", "description": "Designs and develops electrical systems, circuits, and electronic components."}
    ],
    "ADVOCATE": [
        {"title": "Attorney",        "description": "Represents clients in legal proceedings, provides legal advice, and drafts legal documents."},
        {"title": "Legal Consultant","description": "Advises organizations on legal matters, regulatory compliance, and risk management."},
        {"title": "Paralegal",       "description": "Assists attorneys with case preparation, legal research, document drafting, and client communication."}
    ],
    "CHEF": [
        {"title": "Executive Chef",     "description": "Leads kitchen operations, creates menus, manages food quality, and oversees culinary staff."},
        {"title": "Sous Chef",          "description": "Assists the head chef in kitchen management, food preparation, and staff supervision."},
        {"title": "Restaurant Manager", "description": "Manages daily restaurant operations, staff scheduling, customer service, and financial performance."}
    ],
    "ACCOUNTANT": [
        {"title": "Accountant",     "description": "Manages financial records, prepares tax returns, and ensures compliance with financial regulations."},
        {"title": "Auditor",        "description": "Examines financial statements, evaluates internal controls, and ensures regulatory compliance."},
        {"title": "Tax Consultant", "description": "Advises clients on tax planning strategies, compliance, and optimization of tax obligations."}
    ],
    "FITNESS": [
        {"title": "Personal Trainer",  "description": "Designs customized exercise programs, guides clients through workouts, and monitors fitness progress."},
        {"title": "Fitness Director",  "description": "Oversees fitness facility operations, develops wellness programs, and manages training staff."},
        {"title": "Sports Coach",      "description": "Trains athletes, develops game strategies, and builds team performance through coaching."}
    ],
    "AVIATION": [
        {"title": "Airline Pilot",             "description": "Operates aircraft, ensures flight safety, navigates routes, and communicates with air traffic control."},
        {"title": "Aviation Technician",       "description": "Inspects, repairs, and maintains aircraft systems to ensure airworthiness and safety compliance."},
        {"title": "Flight Operations Manager", "description": "Coordinates flight schedules, manages crew assignments, and ensures operational compliance."}
    ],
    "SALES": [
        {"title": "Sales Representative","description": "Promotes and sells products or services, builds client relationships, and meets sales targets."},
        {"title": "Account Manager",     "description": "Manages key client accounts, ensures customer satisfaction, and identifies upselling opportunities."},
        {"title": "Sales Director",      "description": "Leads sales teams, develops revenue strategies, and drives business growth initiatives."}
    ],
    "BANKING": [
        {"title": "Banking Officer", "description": "Manages banking operations, processes transactions, and provides financial services to customers."},
        {"title": "Loan Officer",    "description": "Evaluates loan applications, assesses creditworthiness, and manages lending portfolios."},
        {"title": "Branch Manager",  "description": "Oversees bank branch operations, manages staff, and drives customer acquisition and retention."}
    ],
    "CONSULTANT": [
        {"title": "Management Consultant","description": "Advises organizations on strategy, operations, and organizational improvement initiatives."},
        {"title": "Strategy Consultant",  "description": "Analyzes market dynamics, develops competitive strategies, and guides business transformation."},
        {"title": "Business Analyst",     "description": "Evaluates business processes, gathers requirements, and recommends technology-driven solutions."}
    ],
    "HEALTHCARE": [
        {"title": "Registered Nurse",       "description": "Provides patient care, administers medications, monitors health conditions, and supports recovery."},
        {"title": "Healthcare Administrator","description": "Manages healthcare facility operations, budgets, staff, and regulatory compliance."},
        {"title": "Medical Technologist",   "description": "Performs laboratory tests, analyzes biological samples, and aids in medical diagnosis."}
    ],
    "CONSTRUCTION": [
        {"title": "Construction Manager","description": "Plans and oversees construction projects, manages timelines, budgets, and on-site operations."},
        {"title": "Site Engineer",       "description": "Supervises construction activities, ensures structural integrity, and manages site safety."},
        {"title": "Project Estimator",   "description": "Calculates project costs, prepares bid proposals, and analyzes material and labor requirements."}
    ],
    "PUBLIC-RELATIONS": [
        {"title": "PR Specialist",       "description": "Manages public image, writes press releases, coordinates media relations, and handles crisis communication."},
        {"title": "Communications Director","description": "Oversees organizational communications strategy, media relations, and brand messaging."},
        {"title": "Media Relations Manager","description": "Builds relationships with journalists, coordinates press coverage, and manages media inquiries."}
    ],
    "HR": [
        {"title": "HR Specialist","description": "Manages recruitment, employee relations, benefits administration, and workplace compliance."},
        {"title": "Recruiter",   "description": "Sources and screens candidates, conducts interviews, and manages the hiring pipeline."},
        {"title": "HR Manager",  "description": "Oversees human resources operations, develops policies, and manages employee development programs."}
    ],
    "DESIGNER": [
        {"title": "Graphic Designer", "description": "Creates visual designs for marketing materials, branding, and digital media using design tools."},
        {"title": "UX/UI Designer",   "description": "Designs user interfaces and experiences through research, wireframing, and usability testing."},
        {"title": "Creative Director","description": "Leads creative vision, directs design teams, and ensures brand consistency across projects."}
    ],
    "ARTS": [
        {"title": "Art Director",  "description": "Oversees visual style and creative direction for productions, publications, or campaigns."},
        {"title": "Gallery Curator","description": "Selects and organizes art exhibitions, manages collections, and coordinates with artists."},
        {"title": "Visual Artist", "description": "Creates original artwork using various media, develops artistic concepts, and exhibits works."}
    ],
    "TEACHER": [
        {"title": "Elementary Teacher", "description": "Educates young students across subjects, develops lesson plans, and assesses student progress."},
        {"title": "University Professor","description": "Teaches higher education courses, conducts research, and mentors graduate students."},
        {"title": "Curriculum Developer","description": "Designs educational curricula, creates learning materials, and evaluates teaching effectiveness."}
    ],
    "APPAREL": [
        {"title": "Fashion Designer",   "description": "Designs clothing and accessories, creates patterns, and oversees production of fashion lines."},
        {"title": "Merchandiser",       "description": "Plans product assortments, manages inventory, and optimizes retail sales strategies."},
        {"title": "Textile Specialist", "description": "Researches fabrics, evaluates material quality, and advises on textile selection for production."}
    ],
    "DIGITAL-MEDIA": [
        {"title": "Social Media Manager",       "description": "Manages social media presence, creates content strategies, and engages with online audiences."},
        {"title": "Content Strategist",         "description": "Develops content plans, manages editorial calendars, and drives audience engagement."},
        {"title": "Digital Marketing Specialist","description": "Executes digital marketing campaigns, analyzes performance metrics, and optimizes ROI."}
    ],
    "AGRICULTURE": [
        {"title": "Agricultural Scientist","description": "Conducts research on crop production, soil health, and sustainable farming practices."},
        {"title": "Farm Manager",         "description": "Oversees farm operations, manages workers, and ensures efficient crop and livestock production."},
        {"title": "Agronomist",           "description": "Advises on soil management, crop rotation, and agricultural technology to improve yields."}
    ],
    "AUTOMOBILE": [
        {"title": "Automotive Engineer","description": "Designs and develops vehicle systems, conducts testing, and improves automotive technology."},
        {"title": "Service Manager",    "description": "Manages automotive service operations, oversees technicians, and ensures customer satisfaction."},
        {"title": "Vehicle Inspector",  "description": "Inspects vehicles for safety compliance, performs diagnostics, and certifies roadworthiness."}
    ],
    "BPO": [
        {"title": "Operations Manager",   "description": "Manages business process outsourcing operations, optimizes workflows, and ensures service quality."},
        {"title": "Process Analyst",      "description": "Analyzes business processes, identifies efficiency improvements, and implements automation solutions."},
        {"title": "Customer Service Lead","description": "Leads customer service teams, handles escalations, and develops service improvement strategies."}
    ],
}

# ============================================================
# 8. Build and write JSON artifact
# ============================================================

print("\n[6/7] Building model JSON artifact...")

rounded_centroids = {
    cat: [round(x, 6) for x in centroid]
    for cat, centroid in category_centroids.items()
}

output = {
    # — TF-IDF features
    "vocabulary":   vocabulary,
    "idf_values":   [round(v, 6) for v in idf_values],
    "skill_flags":  skill_flags,

    # — Centroid-based scoring (cosine similarity — PHP fallback)
    "category_centroids":       rounded_centroids,
    "category_top_keywords":    category_top_keywords,
    "category_cluster_profiles": category_cluster_profiles,
    "term_clusters":            term_clusters,

    # — LinearSVC scorer (primary hybrid classifier)
    # PHP can compute: score_i = dot(tfidf_vector, svc_coef[i]) + svc_intercept[i]
    # Category = svc_classes[argmax(scores)]
    "svc_coef":       [[round(w, 6) for w in row] for row in svc_coef],
    "svc_intercept":  [round(b, 6) for b in svc_intercept],
    "svc_classes":    svc_classes,

    # — Role mappings
    "category_roles": category_roles,

    # — Metadata
    "metadata": {
        "corpus_size":        len(df),
        "vocabulary_size":    len(vocabulary),
        "categories":         len(categories),
        "category_list":      categories,
        "classifier":         "hybrid_tfidf_linearsvc_v4",
        "cv_accuracy_mean":   round(float(cv_scores.mean()), 4),
        "cv_accuracy_std":    round(float(cv_scores.std()),  4),
        "generated_at":       pd.Timestamp.now().isoformat(),
    }
}

os.makedirs(OUTPUT_DIR, exist_ok=True)
with open(OUTPUT_PATH, 'w', encoding='utf-8') as f:
    json.dump(output, f, indent=None, separators=(',', ':'))

file_size = os.path.getsize(OUTPUT_PATH) / 1024 / 1024
print(f"\n[7/7] Model saved!")
print(f"      Path         : {OUTPUT_PATH}")
print(f"      File size    : {file_size:.1f} MB")
print(f"      Vocabulary   : {len(vocabulary)} terms")
print(f"      Categories   : {len(categories)}")
print(f"      CV Accuracy  : {cv_scores.mean()*100:.1f}% ± {cv_scores.std()*100:.1f}%")

print("\n    Top keywords per selected category:")
for cat in ['HEALTHCARE', 'INFORMATION-TECHNOLOGY', 'FINANCE', 'TEACHER', 'CHEF', 'BUSINESS-DEVELOPMENT']:
    if cat in category_top_keywords:
        kw = ', '.join(category_top_keywords[cat][:8])
        print(f"      {cat}: {kw}")

print("\n" + "=" * 60)
print("Done! Run check_accuracy.py to validate on the full test set.")
print("=" * 60)
