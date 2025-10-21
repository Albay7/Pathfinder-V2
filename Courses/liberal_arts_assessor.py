import json

# ==================================
# Liberal Arts & Social Sciences Assessor
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
    "Liberal Arts & Social Sciences": [
        "BA Psychology",
        "BS Psychology",
        "BA Political Science",
        "BA Communication",
        "BA Sociology",
        "BA History",
        "BA International Studies",
        "BA Development Studies",
    ],
}

# Active category to assess now
ACTIVE_CATEGORY = "Liberal Arts & Social Sciences"
ACTIVE_COURSES = categories[ACTIVE_CATEGORY]

# 16-question instrument for Liberal Arts & Social Sciences
# Rating scale: 1 (low) to 10 (high). Each response multiplies the per-course weight.

questions = [
    {
        "text": "How comfortable is with research methods (qualitative/quantitative) and academic writing?",
        "focus": "Research Methods & Writing",
        "weights": {
            "BS Psychology": 10,
            "BA Sociology": 9,
            "BA Development Studies": 9,
            "BA Political Science": 8,
            "BA History": 8,
            "BA Psychology": 8,
            "BA International Studies": 7,
            "BA Communication": 6,
        },
    },
    {
        "text": "How strong is skill in critical reading of theory and constructing evidence-based arguments?",
        "focus": "Critical Theory & Argument",
        "weights": {
            "BA Political Science": 10,
            "BA Sociology": 9,
            "BA History": 9,
            "BS Psychology": 8,
            "BA Development Studies": 8,
            "BA Psychology": 7,
            "BA International Studies": 7,
            "BA Communication": 6,
        },
    },
    {
        "text": "How comfortable is with statistics (SPSS/R), survey design, and data interpretation?",
        "focus": "Statistics & Surveys",
        "weights": {
            "BS Psychology": 10,
            "BA Sociology": 9,
            "BA Development Studies": 9,
            "BA Political Science": 8,
            "BA International Studies": 7,
            "BA Psychology": 7,
            "BA Communication": 6,
            "BA History": 6,
        },
    },
    {
        "text": "How confident is in public speaking, presentations, and media communication?",
        "focus": "Public Speaking & Media",
        "weights": {
            "BA Communication": 10,
            "BA Political Science": 9,
            "BA International Studies": 8,
            "BA Development Studies": 7,
            "BA Psychology": 7,
            "BS Psychology": 6,
            "BA Sociology": 6,
            "BA History": 5,
        },
    },
    {
        "text": "How interested is in global affairs, diplomacy, and foreign policy analysis?",
        "focus": "Global Affairs & Diplomacy",
        "weights": {
            "BA International Studies": 10,
            "BA Political Science": 9,
            "BA Development Studies": 8,
            "BA Communication": 7,
            "BA Sociology": 6,
            "BA History": 6,
            "BS Psychology": 5,
            "BA Psychology": 5,
        },
    },
    {
        "text": "How comfortable is with fieldwork, community immersion, and stakeholder engagement?",
        "focus": "Fieldwork & Community",
        "weights": {
            "BA Development Studies": 10,
            "BA Sociology": 9,
            "BA International Studies": 8,
            "BA Political Science": 7,
            "BA Communication": 7,
            "BA Psychology": 6,
            "BS Psychology": 6,
            "BA History": 5,
        },
    },
    {
        "text": "How strong is interest in historical research, archives, and heritage studies?",
        "focus": "Historical Research",
        "weights": {
            "BA History": 10,
            "BA Political Science": 7,
            "BA Sociology": 7,
            "BA Development Studies": 6,
            "BA International Studies": 6,
            "BA Psychology": 5,
            "BS Psychology": 5,
            "BA Communication": 4,
        },
    },
    {
        "text": "How comfortable is with qualitative methods (interviews, focus groups, ethnography)?",
        "focus": "Qualitative Methods",
        "weights": {
            "BA Sociology": 10,
            "BA Development Studies": 9,
            "BA Psychology": 8,
            "BS Psychology": 8,
            "BA International Studies": 7,
            "BA Political Science": 7,
            "BA History": 6,
            "BA Communication": 6,
        },
    },
    {
        "text": "How strong is interest in policy analysis, governance, and public institutions?",
        "focus": "Policy & Governance",
        "weights": {
            "BA Political Science": 10,
            "BA Development Studies": 9,
            "BA International Studies": 8,
            "BA Sociology": 7,
            "BA History": 6,
            "BA Communication": 6,
            "BS Psychology": 5,
            "BA Psychology": 5,
        },
    },
    {
        "text": "How interested is in counseling, mental health, and human behavior?",
        "focus": "Counseling & Behavior",
        "weights": {
            "BA Psychology": 10,
            "BS Psychology": 10,
            "BA Sociology": 7,
            "BA Communication": 6,
            "BA Development Studies": 5,
            "BA Political Science": 5,
            "BA International Studies": 5,
            "BA History": 4,
        },
    },
    {
        "text": "How comfortable is with investigative journalism, storytelling, and content production?",
        "focus": "Journalism & Storytelling",
        "weights": {
            "BA Communication": 10,
            "BA Political Science": 7,
            "BA Development Studies": 7,
            "BA Sociology": 6,
            "BA History": 6,
            "BA International Studies": 6,
            "BA Psychology": 5,
            "BS Psychology": 5,
        },
    },
    {
        "text": "How strong is foreign language aptitude and cross-cultural communication?",
        "focus": "Languages & Cross-Culture",
        "weights": {
            "BA International Studies": 10,
            "BA Development Studies": 8,
            "BA Political Science": 8,
            "BA Communication": 7,
            "BA Sociology": 6,
            "BA History": 6,
            "BA Psychology": 5,
            "BS Psychology": 5,
        },
    },
    {
        "text": "How interested is in social research software and data visualization (e.g., SPSS, Tableau)?",
        "focus": "Research Tools & Visualization",
        "weights": {
            "BS Psychology": 9,
            "BA Sociology": 9,
            "BA Development Studies": 8,
            "BA Political Science": 8,
            "BA International Studies": 7,
            "BA Psychology": 7,
            "BA Communication": 6,
            "BA History": 6,
        },
    },
    {
        "text": "How comfortable is with ethics in research, data privacy, and responsible scholarship?",
        "focus": "Research Ethics",
        "weights": {
            "BS Psychology": 9,
            "BA Psychology": 9,
            "BA Sociology": 8,
            "BA Development Studies": 8,
            "BA Political Science": 7,
            "BA International Studies": 7,
            "BA History": 6,
            "BA Communication": 6,
        },
    },
    {
        "text": "How interested is in development economics, project monitoring, and impact evaluation?",
        "focus": "Development Practice",
        "weights": {
            "BA Development Studies": 10,
            "BA Political Science": 8,
            "BA International Studies": 8,
            "BA Sociology": 8,
            "BS Psychology": 6,
            "BA Psychology": 6,
            "BA Communication": 5,
            "BA History": 5,
        },
    },
    {
        "text": "How comfortable is with organizing events, advocacy campaigns, and stakeholder communications?",
        "focus": "Advocacy & Stakeholder Comms",
        "weights": {
            "BA Communication": 10,
            "BA Development Studies": 9,
            "BA Political Science": 8,
            "BA International Studies": 8,
            "BA Sociology": 7,
            "BA Psychology": 6,
            "BS Psychology": 6,
            "BA History": 5,
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
print("TOP RECOMMENDED LIBERAL ARTS PROGRAMS:")
print("=" * 60)
for i, (course, score) in enumerate(ranked_programs, 1):
    print(f"{i}. {course}: {score} points")

# Save results
output_file = 'liberal_arts_assessment_results.json'
results_data = {
    "category": ACTIVE_CATEGORY,
    "ranked_programs": ranked_programs,
    "individual_scores": scores,
}

with open(output_file, 'w', encoding='utf-8') as f:
    json.dump(results_data, f, indent=2, ensure_ascii=False)

print(f"\nDetailed results saved to {output_file}")
print("Assessment complete!")