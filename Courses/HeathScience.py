import json

# ============================
# Healthcare Program Assessor
# ============================

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
    "Healthcare & Allied Health": [
        "BS Nursing",
        "BS Medical Technology",
        "BS Pharmacy",
        "BS Physical Therapy",
        "BS Radiologic Technology",
        "BS Respiratory Therapy",
        "BS Occupational Therapy",
        "BS Public Health",
    ],
}

# Active category to assess now
ACTIVE_CATEGORY = "Healthcare & Allied Health"
ACTIVE_COURSES = categories[ACTIVE_CATEGORY]

# 16-question instrument for Healthcare category
# Rating scale: 1 (low) to 10 (high). Each response multiplies the per-course weight.

questions = [
    {
        "text": "How comfortable is with direct patient care, bedside procedures, and vital signs monitoring?",
        "focus": "Patient Care & Bedside Skills",
        "weights": {
            "BS Nursing": 10,
            "BS Respiratory Therapy": 8,
            "BS Physical Therapy": 7,
            "BS Occupational Therapy": 7,
            "BS Public Health": 6,
            "BS Medical Technology": 5,
            "BS Radiologic Technology": 5,
            "BS Pharmacy": 4,
        },
    },
    {
        "text": "How strong is interest in laboratory diagnostics, specimen handling, and clinical analysis?",
        "focus": "Laboratory Diagnostics",
        "weights": {
            "BS Medical Technology": 10,
            "BS Public Health": 7,
            "BS Pharmacy": 6,
            "BS Radiologic Technology": 5,
            "BS Nursing": 5,
            "BS Respiratory Therapy": 4,
            "BS Physical Therapy": 3,
            "BS Occupational Therapy": 3,
        },
    },
    {
        "text": "How interested is in pharmacology, medications, dosing, and medication safety?",
        "focus": "Pharmacology & Medication Management",
        "weights": {
            "BS Pharmacy": 10,
            "BS Nursing": 7,
            "BS Public Health": 6,
            "BS Medical Technology": 6,
            "BS Respiratory Therapy": 5,
            "BS Radiologic Technology": 4,
            "BS Physical Therapy": 4,
            "BS Occupational Therapy": 4,
        },
    },
    {
        "text": "How comfortable is with anatomy, kinesiology, and rehabilitation exercise planning?",
        "focus": "Anatomy & Rehabilitation",
        "weights": {
            "BS Physical Therapy": 10,
            "BS Occupational Therapy": 9,
            "BS Nursing": 6,
            "BS Respiratory Therapy": 5,
            "BS Medical Technology": 4,
            "BS Radiologic Technology": 4,
            "BS Pharmacy": 4,
            "BS Public Health": 4,
        },
    },
    {
        "text": "How interested is in medical imaging, radiation physics, and imaging procedures?",
        "focus": "Imaging & Radiologic Science",
        "weights": {
            "BS Radiologic Technology": 10,
            "BS Nursing": 6,
            "BS Respiratory Therapy": 6,
            "BS Medical Technology": 5,
            "BS Public Health": 4,
            "BS Pharmacy": 4,
            "BS Physical Therapy": 3,
            "BS Occupational Therapy": 3,
        },
    },
    {
        "text": "How comfortable is with respiratory assessment, oxygen therapy, and ventilator management?",
        "focus": "Respiratory Care",
        "weights": {
            "BS Respiratory Therapy": 10,
            "BS Nursing": 8,
            "BS Public Health": 5,
            "BS Medical Technology": 5,
            "BS Radiologic Technology": 5,
            "BS Physical Therapy": 4,
            "BS Occupational Therapy": 4,
            "BS Pharmacy": 4,
        },
    },
    {
        "text": "How strong is interest in epidemiology, community programs, and population health?",
        "focus": "Public Health & Epidemiology",
        "weights": {
            "BS Public Health": 10,
            "BS Nursing": 8,
            "BS Medical Technology": 7,
            "BS Pharmacy": 6,
            "BS Respiratory Therapy": 5,
            "BS Radiologic Technology": 4,
            "BS Physical Therapy": 4,
            "BS Occupational Therapy": 4,
        },
    },
    {
        "text": "How comfortable is with chemistry/biochemistry, compounding, and laboratory calculations?",
        "focus": "Chemistry & Compounding",
        "weights": {
            "BS Pharmacy": 10,
            "BS Medical Technology": 8,
            "BS Public Health": 6,
            "BS Nursing": 5,
            "BS Respiratory Therapy": 4,
            "BS Radiologic Technology": 4,
            "BS Physical Therapy": 3,
            "BS Occupational Therapy": 3,
        },
    },
    {
        "text": "How comfortable is with infection control, biosafety, and quality assurance procedures?",
        "focus": "Infection Control & QA",
        "weights": {
            "BS Medical Technology": 9,
            "BS Public Health": 8,
            "BS Nursing": 7,
            "BS Pharmacy": 6,
            "BS Respiratory Therapy": 5,
            "BS Radiologic Technology": 5,
            "BS Physical Therapy": 3,
            "BS Occupational Therapy": 3,
        },
    },
    {
        "text": "How interested is in therapeutic exercise, manual therapy, and rehabilitation goals?",
        "focus": "Therapeutic Exercise",
        "weights": {
            "BS Physical Therapy": 10,
            "BS Occupational Therapy": 9,
            "BS Nursing": 6,
            "BS Respiratory Therapy": 4,
            "BS Medical Technology": 3,
            "BS Radiologic Technology": 3,
            "BS Pharmacy": 3,
            "BS Public Health": 4,
        },
    },
    {
        "text": "How comfortable is with assistive technology, ADL retraining, and psychosocial support?",
        "focus": "Occupational Therapy & ADLs",
        "weights": {
            "BS Occupational Therapy": 10,
            "BS Physical Therapy": 8,
            "BS Nursing": 7,
            "BS Public Health": 5,
            "BS Pharmacy": 4,
            "BS Respiratory Therapy": 4,
            "BS Radiologic Technology": 4,
            "BS Medical Technology": 4,
        },
    },
    {
        "text": "How comfortable is with emergency response, triage, and critical care protocols (BLS/ACLS)?",
        "focus": "Emergency & Critical Care",
        "weights": {
            "BS Nursing": 10,
            "BS Respiratory Therapy": 8,
            "BS Radiologic Technology": 6,
            "BS Medical Technology": 6,
            "BS Pharmacy": 5,
            "BS Physical Therapy": 5,
            "BS Occupational Therapy": 5,
            "BS Public Health": 5,
        },
    },
    {
        "text": "How strong is communication, empathy, and patient education across diverse populations?",
        "focus": "Communication & Patient Education",
        "weights": {
            "BS Nursing": 9,
            "BS Occupational Therapy": 9,
            "BS Physical Therapy": 8,
            "BS Public Health": 8,
            "BS Pharmacy": 6,
            "BS Respiratory Therapy": 6,
            "BS Radiologic Technology": 6,
            "BS Medical Technology": 6,
        },
    },
    {
        "text": "How comfortable is with health informatics, electronic records, and clinical documentation?",
        "focus": "Health Informatics & Documentation",
        "weights": {
            "BS Nursing": 8,
            "BS Public Health": 8,
            "BS Medical Technology": 7,
            "BS Pharmacy": 7,
            "BS Respiratory Therapy": 6,
            "BS Radiologic Technology": 6,
            "BS Physical Therapy": 5,
            "BS Occupational Therapy": 5,
        },
    },
    {
        "text": "How comfortable is with statistics, research methods, and evidence-based practice?",
        "focus": "Research & Evidence-Based Practice",
        "weights": {
            "BS Public Health": 9,
            "BS Medical Technology": 8,
            "BS Nursing": 7,
            "BS Physical Therapy": 7,
            "BS Occupational Therapy": 7,
            "BS Pharmacy": 7,
            "BS Respiratory Therapy": 6,
            "BS Radiologic Technology": 6,
        },
    },
    {
        "text": "How strong is understanding of ethics, regulations, and safety protocols in clinical settings?",
        "focus": "Ethics, Regulation & Safety",
        "weights": {
            "BS Pharmacy": 8,
            "BS Radiologic Technology": 8,
            "BS Medical Technology": 8,
            "BS Nursing": 8,
            "BS Respiratory Therapy": 7,
            "BS Public Health": 7,
            "BS Physical Therapy": 6,
            "BS Occupational Therapy": 6,
        },
    },
]

# Initialize scores
scores = {course: 0 for course in ACTIVE_COURSES}

# Conduct assessment
print(f"\n=== {ACTIVE_CATEGORY} Program Assessment ===")
print("Rate each question from 1 (low) to 10 (high).\n")

for idx, q in enumerate(questions, start=1):
    print(f"Q{idx}. {q['text']}")
    print(f"Focus: {q['focus']}")
    rating = get_user_input("Your rating (1-10): ")

    for course, weight in q["weights"].items():
        if course in scores:
            scores[course] += rating * weight
    print()

# Sort and display results
ranked_programs = sorted(scores.items(), key=lambda item: item[1], reverse=True)

print("=" * 60)
print("TOP RECOMMENDED HEALTHCARE PROGRAMS:")
print("=" * 60)
for i, (course, score) in enumerate(ranked_programs, 1):
    print(f"{i}. {course}: {score} points")

# Save results
output_file = 'healthcare_assessment_results.json'
results_data = {
    "category": ACTIVE_CATEGORY,
    "ranked_programs": ranked_programs,
    "individual_scores": scores,
}

with open(output_file, 'w', encoding='utf-8') as f:
    json.dump(results_data, f, indent=2, ensure_ascii=False)

print(f"\nDetailed results saved to {output_file}")
print("Assessment complete!")
