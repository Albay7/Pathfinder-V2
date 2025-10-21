import json

# ==================================
# Law & Public Administration Assessor
# ==================================

# Helper for safe numeric input
def get_user_input(prompt: str) -> int:
    while True:
        try:
            value = int(input(prompt))
            if 1 <= value <= 10:
                return value
            else:
                print("Please enter a number between 1 and 10.")
        except ValueError:
            print("Please enter a valid integer between 1 and 10.")

# Categories and courses
categories = {
    "Law & Public Administration": [
        "LLB Law",
        "JD Juris Doctor",
        "BA Legal Management",
        "BA Public Administration",
        "BS Criminology",
        "BS Forensic Science",
        "BS Customs Administration",
        "BS International Relations",
    ],
}

# Active category to assess now
ACTIVE_CATEGORY = "Law & Public Administration"
ACTIVE_COURSES = categories[ACTIVE_CATEGORY]

# 16-question instrument for Law & Public Administration
# Rating scale: 1 (low) to 10 (high). Each response multiplies the per-course weight.
questions = [
    {"text": "How strong is interest in understanding and interpreting legislation and statutes?",
     "focus": "Statutory Interpretation",
     "weights": {"LLB Law":10, "JD Juris Doctor":10, "BA Legal Management":8,
                 "BA Public Administration":7, "BS Criminology":6,
                 "BS Forensic Science":5, "BS Customs Administration":5,
                 "BS International Relations":6}},
    {"text": "How confident is in constructing logical arguments and legal reasoning?",
     "focus": "Legal Reasoning",
     "weights": {"LLB Law":10, "JD Juris Doctor":10, "BA Legal Management":9,
                 "BA Public Administration":6, "BS Criminology":6,
                 "BS Forensic Science":5, "BS Customs Administration":5,
                 "BS International Relations":6}},
    {"text": "How comfortable is with researching case law, precedents, and legal documents?",
     "focus": "Legal Research",
     "weights": {"LLB Law":10, "JD Juris Doctor":10, "BA Legal Management":8,
                 "BA Public Administration":6, "BS Criminology":6,
                 "BS Forensic Science":5, "BS Customs Administration":5,
                 "BS International Relations":6}},
    {"text": "How skilled is in negotiation, mediation, and conflict resolution?",
     "focus": "Negotiation & Mediation",
     "weights": {"LLB Law":9, "JD Juris Doctor":9, "BA Public Administration":8,
                 "BA Legal Management":7, "BS International Relations":7,
                 "BS Criminology":5, "BS Customs Administration":5,
                 "BS Forensic Science":4}},
    {"text": "How interested is in public policy formulation, governance, and public sector ethics?",
     "focus": "Public Policy & Ethics",
     "weights": {"BA Public Administration":10, "LLB Law":8,
                 "JD Juris Doctor":8, "BA Legal Management":7,
                 "BS International Relations":8, "BS Criminology":6,
                 "BS Customs Administration":5, "BS Forensic Science":4}},
    {"text": "How comfortable is with criminal justice processes, investigation, and law enforcement?",
     "focus": "Criminal Justice",
     "weights": {"BS Criminology":10, "LLB Law":8,
                 "JD Juris Doctor":8, "BA Public Administration":6,
                 "BS Forensic Science":8, "BS Customs Administration":5,
                 "BS International Relations":5, "BA Legal Management":5}},
    {"text": "How skilled is in forensic analysis, evidence handling, and crime scene protocols?",
     "focus": "Forensic Science",
     "weights": {"BS Forensic Science":10, "BS Criminology":8,
                 "LLB Law":5, "JD Juris Doctor":5,
                 "BS Customs Administration":6, "BS International Relations":5,
                 "BA Public Administration":4, "BA Legal Management":4}},
    {"text": "How interested is in customs regulations, trade law, and import-export compliance?",
     "focus": "Customs & Trade Law",
     "weights": {"BS Customs Administration":10, "LLB Law":7,
                 "JD Juris Doctor":7, "BA Public Administration":6,
                 "BA Legal Management":5, "BS International Relations":6,
                 "BS Criminology":4, "BS Forensic Science":4}},
    {"text": "How strong is aptitude for public international law, diplomacy, and global negotiations?",
     "focus": "International Law & Diplomacy",
     "weights": {"BS International Relations":10, "LLB Law":8,
                 "JD Juris Doctor":8, "BA Public Administration":7,
                 "BA Legal Management":6, "BS Customs Administration":6,
                 "BS Criminology":5, "BS Forensic Science":4}},
    {"text": "How comfortable is with drafting legal documents, contracts, and agreements?",
     "focus": "Legal Drafting",
     "weights": {"LLB Law":10, "JD Juris Doctor":10,
                 "BA Legal Management":9, "BA Public Administration":6,
                 "BS International Relations":5, "BS Criminology":5,
                 "BS Customs Administration":5, "BS Forensic Science":4}},
    {"text": "How skilled is in public administration leadership, budgeting, and program management?",
     "focus": "Admin Leadership & Budgeting",
     "weights": {"BA Public Administration":10, "BA Legal Management":7,
                 "LLB Law":6, "JD Juris Doctor":6,
                 "BS International Relations":6, "BS Criminology":5,
                 "BS Customs Administration":5, "BS Forensic Science":4}},
    {"text": "How interested is in human rights law, social justice advocacy, and NGO work?",
     "focus": "Human Rights & Advocacy",
     "weights": {"LLB Law":9, "JD Juris Doctor":9,
                 "BA Public Administration":8, "BS International Relations":8,
                 "BA Legal Management":6, "BS Criminology":6,
                 "BS Forensic Science":4, "BS Customs Administration":4}},
    {"text": "How comfortable is with legal ethics, professional responsibility, and bar standards?",
     "focus": "Legal Ethics",
     "weights": {"LLB Law":10, "JD Juris Doctor":10,
                 "BA Legal Management":7, "BA Public Administration":6,
                 "BS International Relations":5, "BS Criminology":5,
                 "BS Customs Administration":4, "BS Forensic Science":4}},
    {"text": "How strong is interest in comparative law, constitutional studies, and jurisprudence?",
     "focus": "Comparative & Constitutional Law",
     "weights": {"LLB Law":10, "JD Juris Doctor":10,
                 "BA Legal Management":7, "BS International Relations":6,
                 "BA Public Administration":6, "BS Criminology":5,
                 "BS Customs Administration":4, "BS Forensic Science":4}},
    {"text": "How comfortable is with public speaking, courtroom advocacy, and moot court competitions?",
     "focus": "Advocacy & Moot Court",
     "weights": {"LLB Law":10, "JD Juris Doctor":10,
                 "BA Legal Management":7, "BA Public Administration":5,
                 "BS International Relations":6, "BS Criminology":4,
                 "BS Customs Administration":4, "BS Forensic Science":3}},
]

# Initialize scores for active courses
scores = {course: 0 for course in ACTIVE_COURSES}

# Conduct assessment
print(f"\n=== {ACTIVE_CATEGORY} Program Assessment ===")
print("Rate each question from 1 (low) to 10 (high).\n")

for idx, q in enumerate(questions, start=1):
    print(f"Q{idx}. {q['text']}")
    print(f"Focus: {q['focus']}")
    rating = get_user_input("Your rating (1-10): ")
    for course, weight in q['weights'].items():
        if course in scores:
            scores[course] += rating * weight
    print()

# Sort and display results
ranked_programs = sorted(scores.items(), key=lambda x: x[1], reverse=True)
print("Top Recommended Programs:")
for course, score in ranked_programs:
    print(f"- {course}: {score} points")

# Save results
output_file = 'law_pa_recommendation_scores.json'
with open(output_file, 'w', encoding='utf-8') as f:
    json.dump({'ranked_programs': ranked_programs}, f, indent=2)
print(f"\nDetailed scores saved to {output_file}")
