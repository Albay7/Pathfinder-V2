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
    'necessary', 'needed', 'appropriate', 'available', 'able',
]

ALL_STOPWORDS = list(ENGLISH_STOP_WORDS) + RESUME_STOPWORDS

# ============================================================
# 1. Load and clean dataset
# ============================================================

BASE_DIR = os.path.dirname(__file__)
DATASET_PATH = os.path.join(BASE_DIR, 'Cloude-Resume', 'Kaggle-Resume', 'Resume.csv')
SKILLS_TXT_PATH = os.path.join(BASE_DIR, 'Cloude-Resume', 'Kaggle-Resume', 'Skills.txt')
SKILLS_OCC_PATH = os.path.join(BASE_DIR, 'Cloude-Resume', 'Kaggle-Resume', 'Skills-occupation.xlsx')
OUTPUT_DIR = os.path.join(BASE_DIR, '..', 'Pathfinder', 'storage', 'app', 'data')
OUTPUT_PATH = os.path.join(OUTPUT_DIR, 'tfidf_model.json')

print(f"Loading dataset from: {DATASET_PATH}")
df = pd.read_csv(DATASET_PATH)
print(f"Loaded {len(df)} resumes across {df['Category'].nunique()} categories")


def clean_text(text):
    """Normalize resume text: lowercase, remove special chars, collapse whitespace."""
    text = str(text)
    text = re.sub(r'\s+', ' ', text)
    text = text.lower()
    # Keep alphanumeric, spaces, +, #, . (for abbreviations like c++, c#, node.js)
    text = re.sub(r'[^a-z0-9\s\+\#\.]', ' ', text)
    text = re.sub(r'\s+', ' ', text)
    return text.strip()


df['clean_text'] = df['Resume_str'].apply(clean_text)

# Drop any rows with empty text
df = df[df['clean_text'].str.len() > 50].reset_index(drop=True)
print(f"After cleaning: {len(df)} resumes")

# ============================================================
# 2. Fit TF-IDF vectorizer
# ============================================================

print("Fitting TF-IDF vectorizer (max_features=500, bigrams enabled)...")

vectorizer = TfidfVectorizer(
    max_features=500,
    stop_words=ALL_STOPWORDS,
    min_df=5,            # term must appear in at least 5 resumes
    max_df=0.8,          # ignore terms in >80% of resumes (too common)
    sublinear_tf=True,   # use 1 + log(tf) instead of raw tf
    ngram_range=(1, 2),  # unigrams and bigrams
    token_pattern=r'(?u)\b[a-z][a-z\+\#\.]{1,25}\b'  # 2+ char tokens
)

tfidf_matrix = vectorizer.fit_transform(df['clean_text'])
vocabulary = vectorizer.get_feature_names_out().tolist()
idf_values = vectorizer.idf_.tolist()

print(f"Vocabulary size: {len(vocabulary)} terms")
print(f"TF-IDF matrix shape: {tfidf_matrix.shape}")

# ============================================================
# 3. Compute category centroids
# ============================================================

print("Computing category centroids...")

categories = sorted(df['Category'].unique().tolist())
category_centroids = {}

for cat in categories:
    mask = df['Category'] == cat
    cat_matrix = tfidf_matrix[mask.values]
    centroid = cat_matrix.mean(axis=0).A1  # sparse to dense array
    category_centroids[cat] = centroid.tolist()

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
# 8. Load O*NET skills and build skill whitelist (Layer 2)
# ============================================================

print("Loading O*NET skill taxonomy...")

# 8a: Load 36 O*NET skill names from Skills.txt (TSV)
skill_whitelist = set()
try:
    skills_df = pd.read_csv(SKILLS_TXT_PATH, sep='\t')
    onet_skills = set(skills_df['Element Name'].str.lower().str.strip().unique())
    skill_whitelist |= onet_skills
    print(f"  Loaded {len(onet_skills)} O*NET skill categories from Skills.txt")
except Exception as e:
    print(f"  Warning: Could not load Skills.txt: {e}")
    onet_skills = set()

# 8b: Load occupation words from Skills-occupation.xlsx
try:
    occ_df = pd.read_excel(SKILLS_OCC_PATH, header=3)
    occupation_words = set()
    for title in occ_df.iloc[:, 1].dropna():  # Column B = occupation title
        words = re.findall(r'[a-z]+', str(title).lower())
        for w in words:
            if len(w) > 3:
                occupation_words.add(w)
                # Also add singular forms (strip common plural suffixes)
                if w.endswith('ists'):
                    occupation_words.add(w[:-1])   # therapists -> therapist
                elif w.endswith('ors'):
                    occupation_words.add(w[:-1])   # directors -> director
                elif w.endswith('ers'):
                    occupation_words.add(w[:-1])   # engineers -> engineer
                elif w.endswith('ants'):
                    occupation_words.add(w[:-1])   # accountants -> accountant
                elif w.endswith('ents'):
                    occupation_words.add(w[:-1])   # agents -> agent
                elif w.endswith('sts'):
                    occupation_words.add(w[:-1])   # analysts -> analyst
                elif w.endswith('ies'):
                    occupation_words.add(w[:-3] + 'y')  # therapies -> therapy
                elif w.endswith('es') and len(w) > 5:
                    occupation_words.add(w[:-2])   # nurses -> nurs (ok, still helps)
                    occupation_words.add(w[:-1])   # nurses -> nurse
                elif w.endswith('s') and not w.endswith('ss'):
                    occupation_words.add(w[:-1])   # pilots -> pilot
    # Also add full multi-word occupation terms (lowercased)
    for title in occ_df.iloc[:, 1].dropna():
        clean_title = re.sub(r'[^a-z\s]', '', str(title).lower()).strip()
        if clean_title:
            skill_whitelist.add(clean_title)
    skill_whitelist |= occupation_words
    print(f"  Loaded {len(occupation_words)} occupation words from Skills-occupation.xlsx")
except Exception as e:
    print(f"  Warning: Could not load Skills-occupation.xlsx: {e}")
    onet_skills = onet_skills if 'onet_skills' in dir() else set()

# 8c: Add common technical/domain skill terms to whitelist
EXTRA_SKILL_TERMS = {
    # Technical
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

    # Direct match: term is in whitelist
    if term_lower in skill_whitelist:
        is_skill = True
    # Partial match: any word in term matches whitelist
    elif term_words & skill_whitelist:
        is_skill = True
    else:
        # Check if term is a substring of an O*NET skill name or vice versa
        for onet_skill in onet_skills:
            if onet_skill in term_lower or term_lower in onet_skill:
                is_skill = True
                break

    skill_flags.append(is_skill)

skill_count = sum(skill_flags)
print(f"  Tagged {skill_count}/{len(vocabulary)} vocabulary terms as skills")

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
