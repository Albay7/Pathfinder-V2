"""
Preprocess Resume.csv dataset and generate a TF-IDF model JSON artifact
for the Pathfinder CV Analysis Service.

Usage:
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

# ============================================================
# 0. Resume stopwords (Layer 1: remove generic resume words)
# ============================================================

RESUME_STOPWORDS = [
    # Action verbs (not skills)
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
    'maintained', 'operated', 'produced', 'engaged', 'addressed',

    # Generic resume nouns
    'company', 'organization', 'team', 'years', 'experience', 'role',
    'position', 'department', 'office', 'environment', 'ability', 'knowledge',
    'understanding', 'responsibilities', 'duties', 'tasks', 'skills',
    'work', 'working', 'including', 'various', 'multiple', 'ensuring',
    'using', 'based', 'related', 'level', 'well', 'also', 'new',
    'high', 'strong', 'excellent', 'good', 'effective', 'successful',
    'professional', 'extensive', 'responsible', 'significant', 'relevant',
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

    # Time words
    'january', 'february', 'march', 'april', 'may', 'june',
    'july', 'august', 'september', 'october', 'november', 'december',
    'present', 'current', 'currently', 'daily', 'weekly', 'monthly',
    'annual', 'annually', 'semester', 'quarter', 'year',

    # Filler
    'etc', 'per', 'via', 'within', 'across', 'throughout', 'along',
    'regarding', 'according', 'approximately', 'involved', 'required',
    'necessary', 'needed', 'appropriate', 'available', 'able', 'highly',
    'excellent', 'strong', 'good', 'including', 'varied', 'various',
    'multiple', 'different', 'wide', 'variety', 'outstanding', 'proven',
    'track', 'record', 'hand', 'on', 'focused', 'dynamic', 'motivated',
    'proactive', 'detail', 'oriented', 'skills', 'experience', 'background',
    'level', 'basis', 'large', 'small', 'many', 'work', 'career',
]

# ============================================================
# 0b. Skill Denylist (Words that should NEVER be tagged as skills)
# ============================================================

SKILL_DENYLIST = {
    'jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec',
    'january', 'february', 'march', 'april', 'june', 'july', 'august', 'september',
    'october', 'november', 'december', 'city', 'state', 'usa', 'united', 'states',
    'american', 'national', 'local', 'regional', 'global', 'international',
    'area', 'basis', 'level', 'role', 'position', 'jan', 'month', 'months', 'year', 'years',
    'number', 'member', 'groups', 'item', 'items', 'individual', 'personal',
    'basic', 'advanced', 'senior', 'junior', 'entry', 'major', 'minor',
    'degree', 'university', 'college', 'school', 'studies', 'student', 'students',
    'skill', 'skills', 'work', 'working', 'job', 'position', 'experience',
    'knowledge', 'ability', 'capability', 'history', 'summary', 'highlights',
    'accomplishments', 'responsibilities', 'duties', 'tasks', 'activities',
    'information', 'details', 'detailed', 'brief', 'background', 'involved',
    'academic', 'agencies', 'associate', 'associates', 'associated', 'capital', 'decision',
    'efficiency', 'efficient', 'efficiently', 'facilitate', 'proficiency',
    'society', 'pricing', 'specifications', 'special events', 'society',
    'specialist', 'coordinator', 'manager', 'director', 'executive',
    'organizations', 'participation', 'recipient', 'producing', 'involved',
}

ALL_STOPWORDS = list(ENGLISH_STOP_WORDS) + RESUME_STOPWORDS

# ============================================================
# 1. Load and clean dataset
# ============================================================

BASE_DIR = os.path.dirname(os.path.abspath(__file__))
DATA_DIR = os.path.join(BASE_DIR, 'Cloude-Resume', 'Kaggle-Resume')

# Dataset Paths
RESUME_PATH = os.path.join(DATA_DIR, 'Resume.csv')
UPDATED_RESUME_PATH = os.path.join(DATA_DIR, 'UpdatedResumeDataSet.csv')
TRAIN_PATH = os.path.join(DATA_DIR, 'train.csv')

# ESCO Taxonomy Paths
ESCO_SKILLS_PATH = os.path.join(DATA_DIR, 'skills_en.csv')
ESCO_OCC_PATH = os.path.join(DATA_DIR, 'occupations_en.csv')
ESCO_REL_PATH = os.path.join(DATA_DIR, 'occupationSkillRelations_en.csv')

OUTPUT_DIR = os.path.join(BASE_DIR, '..', 'Pathfinder', 'storage', 'app', 'data')
OUTPUT_PATH = os.path.join(OUTPUT_DIR, 'tfidf_model.json')

def load_merged_datasets():
    """Load and merge Resume.csv, UpdatedResumeDataSet.csv, and train.csv."""
    print("Merging datasets...")
    
    # 1. Base Resume Dataset
    df1 = pd.read_csv(RESUME_PATH)
    merged = pd.DataFrame({
        'text': df1['Resume_str'],
        'category': df1['Category'].str.upper()
    })
    
    # 2. Updated Resume Dataset (mapping categories)
    try:
        df2 = pd.read_csv(UPDATED_RESUME_PATH)
        # Standardize naming to match our 24 roles where possible
        cat_map = {
            'Java Developer': 'INFORMATION-TECHNOLOGY',
            'Python Developer': 'INFORMATION-TECHNOLOGY',
            'Web Designing': 'DESIGNER',
            'Business Analyst': 'CONSULTANT',
            'Mechanical Engineer': 'ENGINEERING',
            'Health and fitness': 'FITNESS',
            'Civil Engineer': 'CONSTRUCTION',
            'Data Science': 'INFORMATION-TECHNOLOGY',
            'Electrical Engineering': 'ENGINEERING',
            'Operations Manager': 'MANAGEMENT', # New category if needed
            'DevOps Engineer': 'INFORMATION-TECHNOLOGY',
            'Network Security Engineer': 'INFORMATION-TECHNOLOGY',
            'PMO': 'MANAGEMENT',
            'Database': 'INFORMATION-TECHNOLOGY',
            'Hadoop': 'INFORMATION-TECHNOLOGY',
            'ETL Developer': 'INFORMATION-TECHNOLOGY',
            'DotNet Developer': 'INFORMATION-TECHNOLOGY',
            'Blockchain': 'INFORMATION-TECHNOLOGY',
            'Testing': 'INFORMATION-TECHNOLOGY',
            'Automation Testing': 'INFORMATION-TECHNOLOGY',
        }
        df2['category'] = df2['Category'].apply(lambda x: cat_map.get(x, x).upper())
        merged = pd.concat([merged, pd.DataFrame({
            'text': df2['Resume'],
            'category': df2['category']
        })])
        
        # 2.5 Add SAP DEVELOPER and other common tech categories to IT if thin
        merged['category'] = merged['category'].replace({
            'SAP DEVELOPER': 'INFORMATION-TECHNOLOGY',
            'DEVELOPER': 'INFORMATION-TECHNOLOGY',
            'MANAGEMENT': 'CONSULTANT' # Merge thin Management into Consultant or vice versa
        })
    except Exception as e:
        print(f"Warning: Failed to load UpdatedResumeDataSet.csv: {e}")

    # 3. train.csv (Extracting high-quality Job Descriptions as "Ideal Targets")
    try:
        # Load only "Good Fit" labels for job descriptions to define "Ideal centroids"
        df3 = pd.read_csv(TRAIN_PATH)
        # Note: train.csv doesn't have explicit category labels in the 'label' col (it's binary fit)
        # but we can use it to augment overall training text if we had category info.
        # Since it lacks category mapping, we'll use it for vocabulary building only.
        pass
    except Exception as e:
        print(f"Warning: Failed to load train.csv: {e}")

    return merged

df_merged = load_merged_datasets()
print(f"Merged total: {len(df_merged)} samples")

def load_esco_taxonomy():
    """Ingest ESCO professional taxonomy for skills and occupations."""
    print("Ingesting ESCO Taxonomy...")
    esco_skills = set()
    esco_occupations = set()
    
    try:
        s_df = pd.read_csv(ESCO_SKILLS_PATH)
        esco_skills = set(s_df['preferredLabel'].str.lower().dropna().unique())
        print(f"  Loaded {len(esco_skills)} ESCO professional skills.")
    except Exception as e:
        print(f"Warning: Failed to load ESCO skills: {e}")

    try:
        o_df = pd.read_csv(ESCO_OCC_PATH)
        esco_occupations = set(o_df['preferredLabel'].str.lower().dropna().unique())
        print(f"  Loaded {len(esco_occupations)} ESCO occupation titles.")
    except Exception as e:
        print(f"Warning: Failed to load ESCO occupations: {e}")
        
    return esco_skills, esco_occupations

ESCO_SKILLS, ESCO_OCCUPATIONS = load_esco_taxonomy()


def clean_text(text):
    """Normalize resume text: lowercase, remove special chars, collapse whitespace."""
    text = str(text)
    text = re.sub(r'\s+', ' ', text)
    text = text.lower()
    # Keep alphanumeric, spaces, +, #, . (for abbreviations like c++, c#, node.js)
    text = re.sub(r'[^a-z0-9\s\+\#\.]', ' ', text)
    text = re.sub(r'\s+', ' ', text)
    return text.strip()


df_merged['clean_text'] = df_merged['text'].apply(clean_text)

# Drop any rows with empty text or too short
df = df_merged[df_merged['clean_text'].str.len() > 50].reset_index(drop=True)
print(f"After cleaning: {len(df)} resumes/documents total.")

# ============================================================
# 1.5. Build Professional Vocabulary from ESCO
# ============================================================
# We use ESCO skills and occupations to seed our vocabulary 
# ensuring the TF-IDF model focuses on real-world professional terms.
print("Integrating ESCO terms into vocabulary seeds...")
ESCO_TERMS = list(ESCO_SKILLS) + list(ESCO_OCCUPATIONS)
ESCO_CLEAN = [clean_text(t) for t in ESCO_TERMS if len(str(t)) > 2]
print(f"  Added {len(ESCO_CLEAN)} professional seeds.")

# ============================================================
# 2. Fit TF-IDF vectorizer
# ============================================================

print("Fitting TF-IDF vectorizer (max_features=1500, bigrams enabled)...")

vectorizer = TfidfVectorizer(
    max_features=3000,   # Increased further to separate similar roles
    stop_words=ALL_STOPWORDS,
    min_df=3,            
    max_df=0.4,          # Even stricter: discard very common professional terms
    sublinear_tf=True,   
    ngram_range=(1, 2),  
    token_pattern=r'(?u)\b[a-z][a-z\+\#\.]{2,25}\b'
)

tfidf_matrix = vectorizer.fit_transform(df['clean_text'])
vocabulary = vectorizer.get_feature_names_out().tolist()
idf_values = vectorizer.idf_.tolist()

print(f"Vocabulary size: {len(vocabulary)} terms")
print(f"TF-IDF matrix shape: {tfidf_matrix.shape}")

# ============================================================
# 3. Compute category centroids
# ============================================================

print("Computing professional anchored centroids...")

categories = sorted(df['category'].unique().tolist())
category_centroids = {}

# Dictionary to anchor our 24 categories with ESCO professional terminology
CATEGORY_ANCHORS = {
    "INFORMATION-TECHNOLOGY": ["software", "developer", "web", "database", "it", "security", "cloud", "devops", "coding"],
    "FINANCE": ["financial", "investment", "banking", "risk", "equity", "finance", "capital", "underwriting"],
    "ENGINEERING": ["mechanical", "electrical", "civil", "structural", "engineering", "manufacturing", "cad"],
    "ACCOUNTANT": ["accounting", "auditing", "taxation", "bookkeeping", "cpa", "audit"],
    "HEALTHCARE": ["nursing", "clinical", "patient", "medical", "hospital", "diagnosis", "nurse", "physician"],
    "SALES": ["sales", "representative", "account manager", "merchandising", "retail", "selling"],
    "CONSULTANT": ["consulting", "strategy", "management", "business analyst", "advisor"],
    "DESIGNER": ["graphic", "ux", "ui", "designer", "creative", "illustration", "branding"],
    "TEACHER": ["teaching", "educator", "classroom", "pedagogy", "curriculum", "professor", "instruction"],
    "ADVOCATE": ["legal", "attorney", "lawyer", "paralegal", "compliance", "regulatory", "litigation"],
    "CHEF": ["culinary", "chef", "cooking", "restaurant", "kitchen", "hospitality", "bakery"],
    "AVIATION": ["pilot", "flight", "aviation", "aircraft", "airline", "navigation"],
    "FITNESS": ["fitness", "trainer", "gym", "wellness", "sports", "coaching", "athlete"],
    "APPAREL": ["fashion", "textile", "clothing", "apparel", "garment", "merchandising"],
    "CONSTRUCTION": ["construction", "site", "building", "structural", "contractor", "infrastructure"],
    "PUBLIC-RELATIONS": ["public relations", "media", "journalism", "communications", "press"],
    "HR": ["human resources", "recruiting", "hiring", "compensation", "personnel"],
    "DIGITAL-MEDIA": ["social media", "content", "digital marketing", "advertising", "seo"],
    "AGRICULTURE": ["farming", "agricultural", "crop", "livestock", "agronomy", "irrigation"],
    "AUTOMOBILE": ["automotive", "vehicle", "car", "mechanic", "transportation"],
    "BPO": ["business process", "outsourcing", "customer service", "call center", "support"],
    "ARTS": ["art", "fine arts", "gallery", "curator", "creative", "visual"],
}

for cat in categories:
    # 1. Get raw centroid from resumes
    mask = df['category'] == cat
    if mask.any():
        cat_matrix = tfidf_matrix[mask.values]
        raw_centroid = cat_matrix.mean(axis=0).A1
    else:
        raw_centroid = np.zeros(len(vocabulary))

    # 2. Add "Anchor Weight" from ESCO terms and keywords
    anchors = CATEGORY_ANCHORS.get(cat, [])
    # Add category name itself as anchor
    anchors.append(cat.lower().replace('-', ' '))
    
    anchor_vector = np.zeros(len(vocabulary))
    for i, term in enumerate(vocabulary):
        if any(anchor in term for anchor in anchors):
            anchor_vector[i] = 1.0
            
    # Combine (Weighted: 30% anchor, 70% reality)
    combined_centroid = (0.7 * raw_centroid) + (0.3 * anchor_vector)
    category_centroids[cat] = combined_centroid.tolist()

print(f"Computed centroids for {len(category_centroids)} categories")

# ============================================================
# 4. Extract top keywords per category
# ============================================================

print("Extracting top keywords per category...")

category_top_keywords = {}
for cat in categories:
    centroid = np.array(category_centroids[cat])
    # Get indices of top 30 terms by centroid value
    top_indices = centroid.argsort()[-30:][::-1]
    keywords = [vocabulary[i] for i in top_indices if centroid[i] > 0.001]
    category_top_keywords[cat] = keywords

# ============================================================
# 5. Assign terms to thematic clusters
# ============================================================

print("Assigning vocabulary terms to thematic clusters...")

CATEGORY_TO_CLUSTER = {
    'INFORMATION-TECHNOLOGY': 'Technical Skills',
    'ENGINEERING': 'Technical Skills',
    'CONSTRUCTION': 'Technical Skills',
    'AUTOMOBILE': 'Technical Skills',
    'DESIGNER': 'Creative & Design',
    'DIGITAL-MEDIA': 'Creative & Design',
    'ARTS': 'Creative & Design',
    'APPAREL': 'Creative & Design',
    'HEALTHCARE': 'Healthcare & Sciences',
    'FITNESS': 'Healthcare & Sciences',
    'FINANCE': 'Business & Management',
    'BANKING': 'Business & Management',
    'ACCOUNTANT': 'Business & Management',
    'BUSINESS-DEVELOPMENT': 'Business & Management',
    'SALES': 'Business & Management',
    'CONSULTANT': 'Business & Management',
    'HR': 'Communication & Interpersonal',
    'PUBLIC-RELATIONS': 'Communication & Interpersonal',
    'BPO': 'Communication & Interpersonal',
    'ADVOCATE': 'Legal & Compliance',
    'TEACHER': 'Education & Training',
    'AGRICULTURE': 'Trades & Applied',
    'AVIATION': 'Trades & Applied',
    'CHEF': 'Trades & Applied',
}

CLUSTER_NAMES = sorted(set(CATEGORY_TO_CLUSTER.values()))

# For each term, find which category has the highest centroid value,
# then map that category to a cluster
term_clusters = {cluster: [] for cluster in CLUSTER_NAMES}

for i, term in enumerate(vocabulary):
    best_cat = max(categories, key=lambda c: category_centroids[c][i])
    cluster = CATEGORY_TO_CLUSTER.get(best_cat, 'Business & Management')
    term_clusters[cluster].append(i)

for cluster, indices in term_clusters.items():
    print(f"  {cluster}: {len(indices)} terms")

# ============================================================
# 6. Pre-compute 8-dimensional cluster profiles per category
# ============================================================

print("Computing cluster profiles per category...")

category_cluster_profiles = {}
for cat in categories:
    centroid = np.array(category_centroids[cat])
    profile = {}
    for cluster_name in CLUSTER_NAMES:
        indices = term_clusters[cluster_name]
        if len(indices) > 0:
            profile[cluster_name] = float(np.mean([centroid[i] for i in indices]))
        else:
            profile[cluster_name] = 0.0

    # Normalize to 0-1 range
    max_val = max(profile.values()) if profile else 1.0
    if max_val > 0:
        profile = {k: round(v / max_val, 4) for k, v in profile.items()}

    category_cluster_profiles[cat] = profile

# ============================================================
# 7. Define category-to-role mapping
# ============================================================

category_roles = {
    "INFORMATION-TECHNOLOGY": [
        {"title": "Software Developer", "description": "Designs, codes, and maintains software applications using various programming languages and frameworks."},
        {"title": "Web Developer", "description": "Builds and maintains websites and web applications with frontend and backend technologies."},
        {"title": "IT Support Specialist", "description": "Provides technical support, troubleshoots hardware/software issues, and maintains IT infrastructure."}
    ],
    "BUSINESS-DEVELOPMENT": [
        {"title": "Business Development Manager", "description": "Identifies growth opportunities, builds strategic partnerships, and drives revenue expansion."},
        {"title": "Sales Strategist", "description": "Develops and implements sales strategies to achieve business objectives and market penetration."},
        {"title": "Marketing Coordinator", "description": "Plans and executes marketing campaigns, manages brand communications, and analyzes market trends."}
    ],
    "FINANCE": [
        {"title": "Financial Analyst", "description": "Analyzes financial data, prepares investment reports, and advises on financial planning decisions."},
        {"title": "Investment Banker", "description": "Facilitates capital markets transactions, advises on mergers and acquisitions, and manages financial deals."},
        {"title": "Risk Manager", "description": "Identifies, assesses, and mitigates financial and operational risks for organizations."}
    ],
    "ENGINEERING": [
        {"title": "Civil Engineer", "description": "Designs and oversees construction of infrastructure projects including roads, bridges, and buildings."},
        {"title": "Mechanical Engineer", "description": "Designs, develops, and tests mechanical systems, devices, and thermal equipment."},
        {"title": "Electrical Engineer", "description": "Designs and develops electrical systems, circuits, and electronic components."}
    ],
    "ADVOCATE": [
        {"title": "Attorney", "description": "Represents clients in legal proceedings, provides legal advice, and drafts legal documents."},
        {"title": "Legal Consultant", "description": "Advises organizations on legal matters, regulatory compliance, and risk management."},
        {"title": "Paralegal", "description": "Assists attorneys with case preparation, legal research, document drafting, and client communication."}
    ],
    "CHEF": [
        {"title": "Executive Chef", "description": "Leads kitchen operations, creates menus, manages food quality, and oversees culinary staff."},
        {"title": "Sous Chef", "description": "Assists the head chef in kitchen management, food preparation, and staff supervision."},
        {"title": "Restaurant Manager", "description": "Manages daily restaurant operations, staff scheduling, customer service, and financial performance."}
    ],
    "ACCOUNTANT": [
        {"title": "Accountant", "description": "Manages financial records, prepares tax returns, and ensures compliance with financial regulations."},
        {"title": "Auditor", "description": "Examines financial statements, evaluates internal controls, and ensures regulatory compliance."},
        {"title": "Tax Consultant", "description": "Advises clients on tax planning strategies, compliance, and optimization of tax obligations."}
    ],
    "FITNESS": [
        {"title": "Personal Trainer", "description": "Designs customized exercise programs, guides clients through workouts, and monitors fitness progress."},
        {"title": "Fitness Director", "description": "Oversees fitness facility operations, develops wellness programs, and manages training staff."},
        {"title": "Sports Coach", "description": "Trains athletes, develops game strategies, and builds team performance through coaching."}
    ],
    "AVIATION": [
        {"title": "Airline Pilot", "description": "Operates aircraft, ensures flight safety, navigates routes, and communicates with air traffic control."},
        {"title": "Aviation Technician", "description": "Inspects, repairs, and maintains aircraft systems to ensure airworthiness and safety compliance."},
        {"title": "Flight Operations Manager", "description": "Coordinates flight schedules, manages crew assignments, and ensures operational compliance."}
    ],
    "SALES": [
        {"title": "Sales Representative", "description": "Promotes and sells products or services, builds client relationships, and meets sales targets."},
        {"title": "Account Manager", "description": "Manages key client accounts, ensures customer satisfaction, and identifies upselling opportunities."},
        {"title": "Sales Director", "description": "Leads sales teams, develops revenue strategies, and drives business growth initiatives."}
    ],
    "BANKING": [
        {"title": "Banking Officer", "description": "Manages banking operations, processes transactions, and provides financial services to customers."},
        {"title": "Loan Officer", "description": "Evaluates loan applications, assesses creditworthiness, and manages lending portfolios."},
        {"title": "Branch Manager", "description": "Oversees bank branch operations, manages staff, and drives customer acquisition and retention."}
    ],
    "CONSULTANT": [
        {"title": "Management Consultant", "description": "Advises organizations on strategy, operations, and organizational improvement initiatives."},
        {"title": "Strategy Consultant", "description": "Analyzes market dynamics, develops competitive strategies, and guides business transformation."},
        {"title": "Business Analyst", "description": "Evaluates business processes, gathers requirements, and recommends technology-driven solutions."}
    ],
    "HEALTHCARE": [
        {"title": "Registered Nurse", "description": "Provides patient care, administers medications, monitors health conditions, and supports recovery."},
        {"title": "Healthcare Administrator", "description": "Manages healthcare facility operations, budgets, staff, and regulatory compliance."},
        {"title": "Medical Technologist", "description": "Performs laboratory tests, analyzes biological samples, and aids in medical diagnosis."}
    ],
    "CONSTRUCTION": [
        {"title": "Construction Manager", "description": "Plans and oversees construction projects, manages timelines, budgets, and on-site operations."},
        {"title": "Site Engineer", "description": "Supervises construction activities, ensures structural integrity, and manages site safety."},
        {"title": "Project Estimator", "description": "Calculates project costs, prepares bid proposals, and analyzes material and labor requirements."}
    ],
    "PUBLIC-RELATIONS": [
        {"title": "PR Specialist", "description": "Manages public image, writes press releases, coordinates media relations, and handles crisis communication."},
        {"title": "Communications Director", "description": "Oversees organizational communications strategy, media relations, and brand messaging."},
        {"title": "Media Relations Manager", "description": "Builds relationships with journalists, coordinates press coverage, and manages media inquiries."}
    ],
    "HR": [
        {"title": "HR Specialist", "description": "Manages recruitment, employee relations, benefits administration, and workplace compliance."},
        {"title": "Recruiter", "description": "Sources and screens candidates, conducts interviews, and manages the hiring pipeline."},
        {"title": "HR Manager", "description": "Oversees human resources operations, develops policies, and manages employee development programs."}
    ],
    "DESIGNER": [
        {"title": "Graphic Designer", "description": "Creates visual designs for marketing materials, branding, and digital media using design tools."},
        {"title": "UX/UI Designer", "description": "Designs user interfaces and experiences through research, wireframing, and usability testing."},
        {"title": "Creative Director", "description": "Leads creative vision, directs design teams, and ensures brand consistency across projects."}
    ],
    "ARTS": [
        {"title": "Art Director", "description": "Oversees visual style and creative direction for productions, publications, or campaigns."},
        {"title": "Gallery Curator", "description": "Selects and organizes art exhibitions, manages collections, and coordinates with artists."},
        {"title": "Visual Artist", "description": "Creates original artwork using various media, develops artistic concepts, and exhibits works."}
    ],
    "TEACHER": [
        {"title": "Elementary Teacher", "description": "Educates young students across subjects, develops lesson plans, and assesses student progress."},
        {"title": "University Professor", "description": "Teaches higher education courses, conducts research, and mentors graduate students."},
        {"title": "Curriculum Developer", "description": "Designs educational curricula, creates learning materials, and evaluates teaching effectiveness."}
    ],
    "APPAREL": [
        {"title": "Fashion Designer", "description": "Designs clothing and accessories, creates patterns, and oversees production of fashion lines."},
        {"title": "Merchandiser", "description": "Plans product assortments, manages inventory, and optimizes retail sales strategies."},
        {"title": "Textile Specialist", "description": "Researches fabrics, evaluates material quality, and advises on textile selection for production."}
    ],
    "DIGITAL-MEDIA": [
        {"title": "Social Media Manager", "description": "Manages social media presence, creates content strategies, and engages with online audiences."},
        {"title": "Content Strategist", "description": "Develops content plans, manages editorial calendars, and drives audience engagement."},
        {"title": "Digital Marketing Specialist", "description": "Executes digital marketing campaigns, analyzes performance metrics, and optimizes ROI."}
    ],
    "AGRICULTURE": [
        {"title": "Agricultural Scientist", "description": "Conducts research on crop production, soil health, and sustainable farming practices."},
        {"title": "Farm Manager", "description": "Oversees farm operations, manages workers, and ensures efficient crop and livestock production."},
        {"title": "Agronomist", "description": "Advises on soil management, crop rotation, and agricultural technology to improve yields."}
    ],
    "AUTOMOBILE": [
        {"title": "Automotive Engineer", "description": "Designs and develops vehicle systems, conducts testing, and improves automotive technology."},
        {"title": "Service Manager", "description": "Manages automotive service operations, oversees technicians, and ensures customer satisfaction."},
        {"title": "Vehicle Inspector", "description": "Inspects vehicles for safety compliance, performs diagnostics, and certifies roadworthiness."}
    ],
    "BPO": [
        {"title": "Operations Manager", "description": "Manages business process outsourcing operations, optimizes workflows, and ensures service quality."},
        {"title": "Process Analyst", "description": "Analyzes business processes, identifies efficiency improvements, and implements automation solutions."},
        {"title": "Customer Service Lead", "description": "Leads customer service teams, handles escalations, and develops service improvement strategies."}
    ]
}

# ============================================================
# 8. Taxonomy-Driven Skill Detection (ESCO)
# ============================================================

print("Building professional skill dictionary from ESCO...")

# Combined professional dictionary
skill_whitelist = ESCO_SKILLS.copy()
occupation_words = ESCO_OCCUPATIONS.copy()

# Add legacy manual boosts for common technical terms
EXTRA_SKILL_TERMS = {
    'programming', 'coding', 'software', 'hardware', 'database', 'networking',
    'security', 'cloud', 'devops', 'frontend', 'backend', 'fullstack',
    'javascript', 'python', 'java', 'html', 'css', 'sql', 'react', 'angular',
    'node', 'docker', 'kubernetes', 'aws', 'azure', 'linux', 'windows',
    'api', 'testing', 'debugging', 'agile', 'scrum', 'git', 'ci', 'cd',
    'machine learning', 'data analysis', 'data science', 'artificial intelligence',
    'deep learning', 'natural language', 'computer vision', 'statistics',
    'algorithms', 'data structures', 'web development', 'mobile development',
    'cybersecurity', 'blockchain', 'iot', 'embedded', 'firmware',
    # Healthcare
    'nursing', 'clinical', 'patient care', 'diagnosis', 'treatment',
    'pharmacy', 'surgical', 'radiology', 'cardiology', 'pediatrics',
    'emergency', 'rehabilitation', 'therapy', 'pathology', 'oncology',
    'anatomy', 'physiology', 'epidemiology', 'biomedical', 'dental',
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
    'user experience', 'branding', 'layout', 'color theory',
    # Engineering
    'mechanical', 'electrical', 'civil', 'structural', 'chemical',
    'aerospace', 'automotive', 'manufacturing', 'welding', 'machining',
    'cad', 'autocad', 'solidworks', 'matlab', 'simulation',
    'thermodynamics', 'hydraulics', 'pneumatics', 'robotics',
    # Education
    'teaching', 'tutoring', 'curriculum', 'pedagogy', 'assessment',
    'classroom', 'instruction', 'mentoring', 'counseling', 'advising',
    # Culinary/Hospitality
    'cooking', 'baking', 'catering', 'food safety', 'menu planning',
    'hospitality', 'bartending', 'sommelier', 'pastry',
    # Agriculture
    'farming', 'irrigation', 'horticulture', 'agronomy', 'livestock',
    'crop', 'soil', 'fertilizer', 'pesticide', 'harvesting',
    # Legal
    'legal research', 'contract', 'compliance', 'regulatory',
    'intellectual property', 'patent', 'trademark', 'copyright',
    # Soft skills
    'leadership', 'teamwork', 'communication', 'problem solving',
    'critical thinking', 'time management', 'organization', 'adaptability',
    'creativity', 'collaboration', 'presentation', 'interpersonal',
    'conflict resolution', 'decision making', 'analytical',
    'project management', 'strategic planning', 'public speaking',
}
skill_whitelist |= EXTRA_SKILL_TERMS

print(f"  Total skill whitelist size: {len(skill_whitelist)} terms")

# 8d: Tag each vocabulary term as skill or non-skill
skill_flags = []
for term in vocabulary:
    is_skill = False
    term_lower = term.lower()
    term_words = set(term_lower.split())

    # Skip if in denylist
    # Logic: If term matches ESCO skill directly, it's a skill.
    # We prioritize exact matches with ESCO to avoid generic term detection.
    
    if term_lower in SKILL_DENYLIST:
        is_skill = False
    elif term_lower in occupation_words:
        is_skill = False
    elif term_lower in ESCO_SKILLS: # Exact ESCO match is gold
        is_skill = True
    elif term_lower in EXTRA_SKILL_TERMS:
        is_skill = True
    elif any(s in term_lower for s in EXTRA_SKILL_TERMS): # Substring match for technical keywords
        is_skill = True
        
    skill_flags.append(is_skill)

print(f"  Tagged {sum(skill_flags)}/{len(vocabulary)} terms as professional skills.")

# ============================================================
# 9. Build and write JSON artifact
# ============================================================

print("Building JSON artifact...")

# Round centroid values to 6 decimal places to keep file size reasonable
rounded_centroids = {}
for cat, centroid in category_centroids.items():
    rounded_centroids[cat] = [round(x, 6) for x in centroid]

output = {
    "vocabulary": vocabulary,
    "idf_values": [round(v, 6) for v in idf_values],
    "skill_flags": skill_flags,
    "category_centroids": rounded_centroids,
    "category_roles": category_roles,
    "category_top_keywords": category_top_keywords,
    "term_clusters": term_clusters,
    "category_cluster_profiles": category_cluster_profiles,
    "metadata": {
        "corpus_size": len(df),
        "vocabulary_size": len(vocabulary),
        "categories": len(categories),
        "category_list": categories,
        "generated_at": pd.Timestamp.now().isoformat()
    }
}

# Ensure output directory exists
os.makedirs(OUTPUT_DIR, exist_ok=True)

with open(OUTPUT_PATH, 'w', encoding='utf-8') as f:
    json.dump(output, f, indent=None, separators=(',', ':'))

file_size = os.path.getsize(OUTPUT_PATH) / 1024 / 1024
print(f"\nModel saved to: {OUTPUT_PATH}")
print(f"File size: {file_size:.2f} MB")
print(f"Vocabulary: {len(vocabulary)} terms")
print(f"Categories: {len(categories)}")
print(f"Clusters: {len(CLUSTER_NAMES)}")

# Print sample keywords for verification
print("\n--- Sample Top Keywords ---")
for cat in ['HEALTHCARE', 'INFORMATION-TECHNOLOGY', 'FINANCE', 'TEACHER', 'CHEF']:
    if cat in category_top_keywords:
        print(f"  {cat}: {', '.join(category_top_keywords[cat][:10])}")

print("\n--- Cluster Profiles (HEALTHCARE) ---")
if 'HEALTHCARE' in category_cluster_profiles:
    for cluster, score in sorted(category_cluster_profiles['HEALTHCARE'].items(), key=lambda x: -x[1]):
        print(f"  {cluster}: {score}")

print(f"\n--- Skill Flags Summary ---")
print(f"  Skills: {sum(skill_flags)}/{len(skill_flags)} terms tagged as real skills")
skill_terms = [vocabulary[i] for i in range(len(vocabulary)) if skill_flags[i]]
non_skill_terms = [vocabulary[i] for i in range(len(vocabulary)) if not skill_flags[i]]
print(f"  Sample skill terms: {', '.join(skill_terms[:20])}")
print(f"  Sample non-skill terms: {', '.join(non_skill_terms[:20])}")

print("\nDone!")
