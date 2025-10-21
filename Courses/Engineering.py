import json

# ============================
# Engineering Program Assessor
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
    "Engineering & Technology": [
        "BS Civil Engineering",
        "BS Mechanical Engineering",
        "BS Electrical Engineering",
        "BS Electronics Engineering",
        "BS Computer Engineering",
        "BS Chemical Engineering",
        "BS Industrial Engineering",
        "BS Geodetic Engineering",
    ],
}

# Active category to assess now
ACTIVE_CATEGORY = "Engineering & Technology"
ACTIVE_COURSES = categories[ACTIVE_CATEGORY]

# 16-question instrument for Engineering category
# Rating scale: 1 (low) to 10 (high). Each response multiplies the per-course weight.

questions = [
    {
        "text": "How comfortable is with advanced mathematics (calculus, differential equations)?",
        "focus": "Advanced Mathematics",
        "weights": {
            "BS Mechanical Engineering": 9,
            "BS Civil Engineering": 9,
            "BS Electrical Engineering": 9,
            "BS Electronics Engineering": 8,
            "BS Computer Engineering": 8,
            "BS Chemical Engineering": 8,
            "BS Industrial Engineering": 7,
            "BS Geodetic Engineering": 7,
        },
    },
    {
        "text": "How strong is understanding of mechanics, statics, and dynamics in solving problems?",
        "focus": "Mechanics & Dynamics",
        "weights": {
            "BS Mechanical Engineering": 10,
            "BS Civil Engineering": 9,
            "BS Industrial Engineering": 7,
            "BS Geodetic Engineering": 7,
            "BS Chemical Engineering": 6,
            "BS Electrical Engineering": 6,
            "BS Electronics Engineering": 6,
            "BS Computer Engineering": 5,
        },
    },
    {
        "text": "How comfortable is with electricity, circuits, and power systems concepts?",
        "focus": "Circuits & Power",
        "weights": {
            "BS Electrical Engineering": 10,
            "BS Electronics Engineering": 9,
            "BS Computer Engineering": 8,
            "BS Industrial Engineering": 5,
            "BS Chemical Engineering": 5,
            "BS Mechanical Engineering": 5,
            "BS Civil Engineering": 4,
            "BS Geodetic Engineering": 3,
        },
    },
    {
        "text": "How proficient is in programming, embedded systems, and basic algorithms?",
        "focus": "Programming & Embedded",
        "weights": {
            "BS Computer Engineering": 10,
            "BS Electronics Engineering": 8,
            "BS Electrical Engineering": 7,
            "BS Industrial Engineering": 6,
            "BS Mechanical Engineering": 5,
            "BS Civil Engineering": 4,
            "BS Chemical Engineering": 4,
            "BS Geodetic Engineering": 4,
        },
    },
    {
        "text": "How strong is background in chemistry and interest in process engineering?",
        "focus": "Chemistry & Processes",
        "weights": {
            "BS Chemical Engineering": 10,
            "BS Industrial Engineering": 7,
            "BS Mechanical Engineering": 6,
            "BS Civil Engineering": 5,
            "BS Electrical Engineering": 4,
            "BS Electronics Engineering": 4,
            "BS Geodetic Engineering": 4,
            "BS Computer Engineering": 3,
        },
    },
    {
        "text": "How comfortable is with materials science and strength of materials concepts?",
        "focus": "Materials & Strength",
        "weights": {
            "BS Civil Engineering": 9,
            "BS Mechanical Engineering": 9,
            "BS Chemical Engineering": 7,
            "BS Geodetic Engineering": 6,
            "BS Industrial Engineering": 6,
            "BS Electrical Engineering": 5,
            "BS Electronics Engineering": 5,
            "BS Computer Engineering": 4,
        },
    },
    {
        "text": "How interested is in structural analysis, construction methods, and infrastructure?",
        "focus": "Structures & Construction",
        "weights": {
            "BS Civil Engineering": 10,
            "BS Geodetic Engineering": 7,
            "BS Mechanical Engineering": 6,
            "BS Chemical Engineering": 5,
            "BS Industrial Engineering": 5,
            "BS Electrical Engineering": 4,
            "BS Electronics Engineering": 4,
            "BS Computer Engineering": 3,
        },
    },
    {
        "text": "How comfortable is with surveying, mapping, and GIS/geospatial concepts?",
        "focus": "Surveying & GIS",
        "weights": {
            "BS Geodetic Engineering": 10,
            "BS Civil Engineering": 8,
            "BS Computer Engineering": 4,
            "BS Mechanical Engineering": 4,
            "BS Industrial Engineering": 4,
            "BS Electrical Engineering": 3,
            "BS Electronics Engineering": 3,
            "BS Chemical Engineering": 3,
        },
    },
    {
        "text": "How strong is understanding of thermodynamics, fluid mechanics, and heat transfer?",
        "focus": "Thermo & Fluids",
        "weights": {
            "BS Mechanical Engineering": 10,
            "BS Chemical Engineering": 8,
            "BS Civil Engineering": 6,
            "BS Industrial Engineering": 6,
            "BS Electrical Engineering": 5,
            "BS Electronics Engineering": 5,
            "BS Geodetic Engineering": 4,
            "BS Computer Engineering": 4,
        },
    },
    {
        "text": "How interested is in control systems, instrumentation, and automation?",
        "focus": "Controls & Instrumentation",
        "weights": {
            "BS Electronics Engineering": 9,
            "BS Electrical Engineering": 9,
            "BS Computer Engineering": 8,
            "BS Mechanical Engineering": 6,
            "BS Industrial Engineering": 6,
            "BS Chemical Engineering": 5,
            "BS Civil Engineering": 4,
            "BS Geodetic Engineering": 3,
        },
    },
    {
        "text": "How interested is in manufacturing systems, operations research, and optimization?",
        "focus": "Manufacturing & OR",
        "weights": {
            "BS Industrial Engineering": 10,
            "BS Mechanical Engineering": 7,
            "BS Chemical Engineering": 7,
            "BS Computer Engineering": 5,
            "BS Electrical Engineering": 5,
            "BS Electronics Engineering": 5,
            "BS Civil Engineering": 5,
            "BS Geodetic Engineering": 3,
        },
    },
    {
        "text": "How comfortable is with CAD, technical drawing, and engineering design projects?",
        "focus": "CAD & Design",
        "weights": {
            "BS Mechanical Engineering": 9,
            "BS Civil Engineering": 8,
            "BS Chemical Engineering": 6,
            "BS Geodetic Engineering": 6,
            "BS Industrial Engineering": 6,
            "BS Electrical Engineering": 5,
            "BS Electronics Engineering": 5,
            "BS Computer Engineering": 5,
        },
    },
    {
        "text": "How comfortable is with project management, safety, standards, and quality assurance (QA/QC)?",
        "focus": "PM, Safety & QA",
        "weights": {
            "BS Industrial Engineering": 9,
            "BS Civil Engineering": 8,
            "BS Mechanical Engineering": 8,
            "BS Chemical Engineering": 7,
            "BS Geodetic Engineering": 7,
            "BS Electrical Engineering": 6,
            "BS Electronics Engineering": 6,
            "BS Computer Engineering": 5,
        },
    },
    {
        "text": "How willing is to do fieldwork/outdoor site work and site inspections?",
        "focus": "Fieldwork Orientation",
        "weights": {
            "BS Geodetic Engineering": 9,
            "BS Civil Engineering": 9,
            "BS Mechanical Engineering": 6,
            "BS Chemical Engineering": 5,
            "BS Industrial Engineering": 5,
            "BS Electrical Engineering": 4,
            "BS Electronics Engineering": 4,
            "BS Computer Engineering": 3,
        },
    },
    {
        "text": "How much is enjoyed building prototypes, tinkering with hardware, and rapid experimentation?",
        "focus": "Prototyping & Hardware",
        "weights": {
            "BS Electronics Engineering": 9,
            "BS Computer Engineering": 9,
            "BS Electrical Engineering": 8,
            "BS Mechanical Engineering": 7,
            "BS Industrial Engineering": 6,
            "BS Chemical Engineering": 4,
            "BS Civil Engineering": 4,
            "BS Geodetic Engineering": 4,
        },
    },
    {
        "text": "How comfortable is with data analysis, statistics, and technical documentation?",
        "focus": "Data & Documentation",
        "weights": {
            "BS Industrial Engineering": 9,
            "BS Computer Engineering": 8,
            "BS Chemical Engineering": 7,
            "BS Civil Engineering": 6,
            "BS Mechanical Engineering": 6,
            "BS Electrical Engineering": 6,
            "BS Electronics Engineering": 6,
            "BS Geodetic Engineering": 6,
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
print("TOP RECOMMENDED ENGINEERING PROGRAMS:")
print("=" * 60)
for i, (course, score) in enumerate(ranked_programs, 1):
    print(f"{i}. {course}: {score} points")

# Save results
output_file = 'engineering_assessment_results.json'
results_data = {
    "category": ACTIVE_CATEGORY,
    "ranked_programs": ranked_programs,
    "individual_scores": scores,
}

with open(output_file, 'w', encoding='utf-8') as f:
    json.dump(results_data, f, indent=2, ensure_ascii=False)

print(f"\nDetailed results saved to {output_file}")
print("Assessment complete!")
