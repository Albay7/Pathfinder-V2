import json

# ============================
# IT & CS Program Assessor
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
    "Information Technology & Computer Science": [
        "BS Information Technology",
        "BS Computer Science",
        "BS Information Systems",
        "BS Computer Engineering",
        "BS Entertainment and Multimedia Computing",
        "BS Data Science",
        "BS Cybersecurity",
        "BS Network Administration",
    ],
}

# Active category to assess now
ACTIVE_CATEGORY = "Information Technology & Computer Science"
ACTIVE_COURSES = categories[ACTIVE_CATEGORY]

# 16-question instrument for IT & CS category
# Rating scale: 1 (low) to 10 (high). Each response multiplies the per-course weight.

questions = [
    {
        "text": "How comfortable is with programming fundamentals (e.g., Python/Java) and problem solving?",
        "focus": "Programming Fundamentals",
        "weights": {
            "BS Computer Science": 10,
            "BS Information Technology": 9,
            "BS Data Science": 8,
            "BS Entertainment and Multimedia Computing": 7,
            "BS Computer Engineering": 7,
            "BS Cybersecurity": 6,
            "BS Information Systems": 6,
            "BS Network Administration": 6,
        },
    },
    {
        "text": "How strong is understanding of algorithms, data structures, and computational thinking?",
        "focus": "Algorithms & Data Structures",
        "weights": {
            "BS Computer Science": 10,
            "BS Data Science": 9,
            "BS Information Technology": 7,
            "BS Computer Engineering": 7,
            "BS Information Systems": 6,
            "BS Entertainment and Multimedia Computing": 6,
            "BS Cybersecurity": 6,
            "BS Network Administration": 5,
        },
    },
    {
        "text": "How comfortable is with discrete math, logic, linear algebra, and probability?",
        "focus": "Mathematics for Computing",
        "weights": {
            "BS Computer Science": 10,
            "BS Data Science": 9,
            "BS Computer Engineering": 7,
            "BS Information Technology": 6,
            "BS Information Systems": 6,
            "BS Cybersecurity": 6,
            "BS Entertainment and Multimedia Computing": 5,
            "BS Network Administration": 5,
        },
    },
    {
        "text": "How comfortable is with databases, SQL, and data modeling/normalization?",
        "focus": "Databases & SQL",
        "weights": {
            "BS Information Systems": 10,
            "BS Information Technology": 9,
            "BS Data Science": 8,
            "BS Computer Science": 7,
            "BS Cybersecurity": 6,
            "BS Network Administration": 6,
            "BS Computer Engineering": 5,
            "BS Entertainment and Multimedia Computing": 5,
        },
    },
    {
        "text": "How interested is in web and mobile development (front-end/back-end, APIs, apps)?",
        "focus": "Web & Mobile Development",
        "weights": {
            "BS Information Technology": 10,
            "BS Information Systems": 8,
            "BS Entertainment and Multimedia Computing": 8,
            "BS Computer Science": 7,
            "BS Cybersecurity": 6,
            "BS Network Administration": 6,
            "BS Computer Engineering": 5,
            "BS Data Science": 5,
        },
    },
    {
        "text": "How familiar is with software engineering lifecycle, Git, testing, and CI/CD?",
        "focus": "Software Engineering",
        "weights": {
            "BS Information Technology": 9,
            "BS Information Systems": 9,
            "BS Computer Science": 8,
            "BS Entertainment and Multimedia Computing": 7,
            "BS Cybersecurity": 7,
            "BS Network Administration": 7,
            "BS Computer Engineering": 6,
            "BS Data Science": 6,
        },
    },
    {
        "text": "How comfortable is with operating systems, shell, virtualization, and sysadmin tasks?",
        "focus": "Operating Systems & SysAdmin",
        "weights": {
            "BS Network Administration": 9,
            "BS Information Technology": 8,
            "BS Cybersecurity": 8,
            "BS Computer Engineering": 7,
            "BS Computer Science": 7,
            "BS Information Systems": 6,
            "BS Entertainment and Multimedia Computing": 5,
            "BS Data Science": 5,
        },
    },
    {
        "text": "How comfortable is with computer networks, routing/switching, and protocols?",
        "focus": "Computer Networks",
        "weights": {
            "BS Network Administration": 10,
            "BS Cybersecurity": 9,
            "BS Information Technology": 8,
            "BS Computer Engineering": 7,
            "BS Computer Science": 6,
            "BS Information Systems": 6,
            "BS Entertainment and Multimedia Computing": 5,
            "BS Data Science": 5,
        },
    },
    {
        "text": "How experienced is with cloud platforms (AWS/Azure/GCP), scripting, and DevOps?",
        "focus": "Cloud & DevOps",
        "weights": {
            "BS Network Administration": 10,
            "BS Information Technology": 9,
            "BS Information Systems": 7,
            "BS Cybersecurity": 7,
            "BS Computer Science": 7,
            "BS Data Science": 6,
            "BS Computer Engineering": 6,
            "BS Entertainment and Multimedia Computing": 5,
        },
    },
    {
        "text": "How interested is in cybersecurity (threats, defense, forensics, and policy)?",
        "focus": "Cybersecurity",
        "weights": {
            "BS Cybersecurity": 10,
            "BS Network Administration": 8,
            "BS Information Technology": 7,
            "BS Computer Science": 6,
            "BS Information Systems": 6,
            "BS Computer Engineering": 6,
            "BS Data Science": 5,
            "BS Entertainment and Multimedia Computing": 4,
        },
    },
    {
        "text": "How comfortable is with statistics, data wrangling, visualization, and analytics?",
        "focus": "Data Analysis & Stats",
        "weights": {
            "BS Data Science": 10,
            "BS Information Systems": 8,
            "BS Computer Science": 8,
            "BS Information Technology": 7,
            "BS Entertainment and Multimedia Computing": 6,
            "BS Cybersecurity": 6,
            "BS Network Administration": 5,
            "BS Computer Engineering": 5,
        },
    },
    {
        "text": "How interested is in machine learning and AI (models, training, evaluation)?",
        "focus": "Machine Learning & AI",
        "weights": {
            "BS Data Science": 10,
            "BS Computer Science": 9,
            "BS Information Technology": 7,
            "BS Information Systems": 6,
            "BS Entertainment and Multimedia Computing": 6,
            "BS Cybersecurity": 6,
            "BS Computer Engineering": 5,
            "BS Network Administration": 4,
        },
    },
    {
        "text": "How comfortable is with hardware, digital logic, microcontrollers, and embedded systems?",
        "focus": "Hardware & Embedded",
        "weights": {
            "BS Computer Engineering": 10,
            "BS Network Administration": 7,
            "BS Cybersecurity": 6,
            "BS Computer Science": 6,
            "BS Information Technology": 5,
            "BS Entertainment and Multimedia Computing": 5,
            "BS Information Systems": 4,
            "BS Data Science": 4,
        },
    },
    {
        "text": "How interested is in graphics, multimedia production, and game engines?",
        "focus": "Graphics, Multimedia & Games",
        "weights": {
            "BS Entertainment and Multimedia Computing": 10,
            "BS Computer Science": 7,
            "BS Information Technology": 7,
            "BS Information Systems": 6,
            "BS Cybersecurity": 5,
            "BS Computer Engineering": 5,
            "BS Data Science": 5,
            "BS Network Administration": 4,
        },
    },
    {
        "text": "How strong is interest in UX/HCI, requirements analysis, and business process modeling?",
        "focus": "UX/HCI & Business Analysis",
        "weights": {
            "BS Information Systems": 10,
            "BS Entertainment and Multimedia Computing": 8,
            "BS Information Technology": 8,
            "BS Computer Science": 6,
            "BS Cybersecurity": 6,
            "BS Network Administration": 5,
            "BS Computer Engineering": 5,
            "BS Data Science": 5,
        },
    },
    {
        "text": "How comfortable is with teamwork, communication, and managing projects (Agile/Scrum)?",
        "focus": "Teamwork & Project Management",
        "weights": {
            "BS Information Systems": 9,
            "BS Information Technology": 9,
            "BS Computer Science": 8,
            "BS Entertainment and Multimedia Computing": 8,
            "BS Cybersecurity": 7,
            "BS Network Administration": 7,
            "BS Computer Engineering": 7,
            "BS Data Science": 7,
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
print("TOP RECOMMENDED IT & CS PROGRAMS:")
print("=" * 60)
for i, (course, score) in enumerate(ranked_programs, 1):
    print(f"{i}. {course}: {score} points")

# Save results
output_file = 'it_cs_assessment_results.json'
results_data = {
    "category": ACTIVE_CATEGORY,
    "ranked_programs": ranked_programs,
    "individual_scores": scores,
}

with open(output_file, 'w', encoding='utf-8') as f:
    json.dump(results_data, f, indent=2, ensure_ascii=False)

print(f"\nDetailed results saved to {output_file}")
print("Assessment complete!")
