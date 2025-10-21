import json

# ============================
# Education Program Assessor
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
    "Education Science & Teacher Training": [
        "Bachelor of Elementary Education (BEEd)",
        "Bachelor of Secondary Education (BSEd) major in English",
        "Bachelor of Secondary Education (BSEd) major in Mathematics",
        "Bachelor of Secondary Education (BSEd) major in Science",
        "Bachelor of Secondary Education (BSEd) major in Social Studies",
        "Bachelor of Physical Education (BPEd)",
        "Bachelor of Early Childhood Education (BECEd)",
        "Bachelor of Technology and Livelihood Education (BTLEd)",
    ],
}

# Active category to assess now
ACTIVE_CATEGORY = "Education Science & Teacher Training"
ACTIVE_COURSES = categories[ACTIVE_CATEGORY]

# 16-question instrument for Education category
# Rating scale: 1 (low) to 10 (high). Each response multiplies the per-course weight.

questions = [
    {
        "text": "How confident is teaching language, reading comprehension, and writing to learners?",
        "focus": "Language/Communication Pedagogy",
        "weights": {
            "Bachelor of Secondary Education (BSEd) major in English": 10,
            "Bachelor of Elementary Education (BEEd)": 8,
            "Bachelor of Early Childhood Education (BECEd)": 8,
            "Bachelor of Secondary Education (BSEd) major in Social Studies": 6,
            "Bachelor of Technology and Livelihood Education (BTLEd)": 5,
            "Bachelor of Secondary Education (BSEd) major in Science": 4,
            "Bachelor of Secondary Education (BSEd) major in Mathematics": 4,
            "Bachelor of Physical Education (BPEd)": 3,
        },
    },
    {
        "text": "How strong is interest and skill in mathematics problem-solving and explaining math concepts?",
        "focus": "Mathematics Pedagogy",
        "weights": {
            "Bachelor of Secondary Education (BSEd) major in Mathematics": 10,
            "Bachelor of Elementary Education (BEEd)": 8,
            "Bachelor of Technology and Livelihood Education (BTLEd)": 6,
            "Bachelor of Secondary Education (BSEd) major in Science": 5,
            "Bachelor of Secondary Education (BSEd) major in Social Studies": 4,
            "Bachelor of Secondary Education (BSEd) major in English": 4,
            "Bachelor of Physical Education (BPEd)": 3,
            "Bachelor of Early Childhood Education (BECEd)": 7,
        },
    },
    {
        "text": "How interested is in scientific inquiry, experiments, and explaining natural phenomena?",
        "focus": "Science Pedagogy",
        "weights": {
            "Bachelor of Secondary Education (BSEd) major in Science": 10,
            "Bachelor of Elementary Education (BEEd)": 7,
            "Bachelor of Technology and Livelihood Education (BTLEd)": 6,
            "Bachelor of Secondary Education (BSEd) major in Mathematics": 5,
            "Bachelor of Secondary Education (BSEd) major in English": 4,
            "Bachelor of Secondary Education (BSEd) major in Social Studies": 4,
            "Bachelor of Physical Education (BPEd)": 3,
            "Bachelor of Early Childhood Education (BECEd)": 6,
        },
    },
    {
        "text": "How interested is in civics, history, culture, and facilitating discussions on society?",
        "focus": "Social Studies Pedagogy",
        "weights": {
            "Bachelor of Secondary Education (BSEd) major in Social Studies": 10,
            "Bachelor of Elementary Education (BEEd)": 7,
            "Bachelor of Secondary Education (BSEd) major in English": 6,
            "Bachelor of Technology and Livelihood Education (BTLEd)": 5,
            "Bachelor of Secondary Education (BSEd) major in Science": 4,
            "Bachelor of Secondary Education (BSEd) major in Mathematics": 4,
            "Bachelor of Early Childhood Education (BECEd)": 6,
            "Bachelor of Physical Education (BPEd)": 3,
        },
    },
    {
        "text": "How patient and passionate is about nurturing children ages 3–8 through play-based learning?",
        "focus": "Early Childhood Orientation",
        "weights": {
            "Bachelor of Early Childhood Education (BECEd)": 10,
            "Bachelor of Elementary Education (BEEd)": 9,
            "Bachelor of Secondary Education (BSEd) major in English": 5,
            "Bachelor of Secondary Education (BSEd) major in Mathematics": 4,
            "Bachelor of Secondary Education (BSEd) major in Science": 4,
            "Bachelor of Secondary Education (BSEd) major in Social Studies": 4,
            "Bachelor of Physical Education (BPEd)": 5,
            "Bachelor of Technology and Livelihood Education (BTLEd)": 4,
        },
    },
    {
        "text": "How confident is in lesson planning, classroom management, and assessment strategies?",
        "focus": "General Pedagogy & Assessment",
        "weights": {
            "Bachelor of Elementary Education (BEEd)": 9,
            "Bachelor of Secondary Education (BSEd) major in English": 8,
            "Bachelor of Secondary Education (BSEd) major in Mathematics": 8,
            "Bachelor of Secondary Education (BSEd) major in Science": 8,
            "Bachelor of Secondary Education (BSEd) major in Social Studies": 8,
            "Bachelor of Early Childhood Education (BECEd)": 9,
            "Bachelor of Physical Education (BPEd)": 7,
            "Bachelor of Technology and Livelihood Education (BTLEd)": 7,
        },
    },
    {
        "text": "How comfortable is with facilitating physical activities, sports coaching, and fitness instruction?",
        "focus": "Physical Education & Coaching",
        "weights": {
            "Bachelor of Physical Education (BPEd)": 10,
            "Bachelor of Elementary Education (BEEd)": 6,
            "Bachelor of Early Childhood Education (BECEd)": 6,
            "Bachelor of Technology and Livelihood Education (BTLEd)": 5,
            "Bachelor of Secondary Education (BSEd) major in English": 4,
            "Bachelor of Secondary Education (BSEd) major in Mathematics": 4,
            "Bachelor of Secondary Education (BSEd) major in Science": 4,
            "Bachelor of Secondary Education (BSEd) major in Social Studies": 4,
        },
    },
    {
        "text": "How skilled is in practical, hands-on, and vocational skills (e.g., ICT, HELE/TLE, carpentry, cookery)?",
        "focus": "TLE/Vocational Orientation",
        "weights": {
            "Bachelor of Technology and Livelihood Education (BTLEd)": 10,
            "Bachelor of Elementary Education (BEEd)": 6,
            "Bachelor of Early Childhood Education (BECEd)": 5,
            "Bachelor of Physical Education (BPEd)": 5,
            "Bachelor of Secondary Education (BSEd) major in Mathematics": 5,
            "Bachelor of Secondary Education (BSEd) major in Science": 5,
            "Bachelor of Secondary Education (BSEd) major in English": 4,
            "Bachelor of Secondary Education (BSEd) major in Social Studies": 4,
        },
    },
    {
        "text": "How comfortable is speaking in front of groups, facilitating discussions, and leading classes?",
        "focus": "Public Speaking & Facilitation",
        "weights": {
            "Bachelor of Secondary Education (BSEd) major in English": 9,
            "Bachelor of Secondary Education (BSEd) major in Social Studies": 9,
            "Bachelor of Elementary Education (BEEd)": 8,
            "Bachelor of Physical Education (BPEd)": 8,
            "Bachelor of Secondary Education (BSEd) major in Science": 7,
            "Bachelor of Secondary Education (BSEd) major in Mathematics": 7,
            "Bachelor of Early Childhood Education (BECEd)": 8,
            "Bachelor of Technology and Livelihood Education (BTLEd)": 7,
        },
    },
    {
        "text": "How strong is interest in educational technology (LMS, multimedia lessons, basic coding/ICT)?",
        "focus": "EdTech Integration",
        "weights": {
            "Bachelor of Technology and Livelihood Education (BTLEd)": 9,
            "Bachelor of Secondary Education (BSEd) major in Mathematics": 8,
            "Bachelor of Secondary Education (BSEd) major in Science": 8,
            "Bachelor of Secondary Education (BSEd) major in English": 7,
            "Bachelor of Secondary Education (BSEd) major in Social Studies": 7,
            "Bachelor of Elementary Education (BEEd)": 7,
            "Bachelor of Physical Education (BPEd)": 6,
            "Bachelor of Early Childhood Education (BECEd)": 6,
        },
    },
    {
        "text": "How interested is in child/adolescent psychology, differentiation, and inclusive practices?",
        "focus": "Learner Diversity & Inclusion",
        "weights": {
            "Bachelor of Elementary Education (BEEd)": 9,
            "Bachelor of Early Childhood Education (BECEd)": 9,
            "Bachelor of Secondary Education (BSEd) major in English": 7,
            "Bachelor of Secondary Education (BSEd) major in Mathematics": 7,
            "Bachelor of Secondary Education (BSEd) major in Science": 7,
            "Bachelor of Secondary Education (BSEd) major in Social Studies": 7,
            "Bachelor of Physical Education (BPEd)": 6,
            "Bachelor of Technology and Livelihood Education (BTLEd)": 6,
        },
    },
    {
        "text": "How comfortable is with designing performance tasks, rubrics, and interpreting test results?",
        "focus": "Assessment Literacy",
        "weights": {
            "Bachelor of Elementary Education (BEEd)": 9,
            "Bachelor of Secondary Education (BSEd) major in English": 8,
            "Bachelor of Secondary Education (BSEd) major in Mathematics": 8,
            "Bachelor of Secondary Education (BSEd) major in Science": 8,
            "Bachelor of Secondary Education (BSEd) major in Social Studies": 8,
            "Bachelor of Technology and Livelihood Education (BTLEd)": 7,
            "Bachelor of Early Childhood Education (BECEd)": 8,
            "Bachelor of Physical Education (BPEd)": 7,
        },
    },
    {
        "text": "How much is enjoyed designing creative instructional materials (worksheets, visuals, manipulatives)?",
        "focus": "Instructional Design & Creativity",
        "weights": {
            "Bachelor of Early Childhood Education (BECEd)": 9,
            "Bachelor of Elementary Education (BEEd)": 9,
            "Bachelor of Secondary Education (BSEd) major in English": 8,
            "Bachelor of Secondary Education (BSEd) major in Social Studies": 7,
            "Bachelor of Secondary Education (BSEd) major in Science": 7,
            "Bachelor of Secondary Education (BSEd) major in Mathematics": 7,
            "Bachelor of Physical Education (BPEd)": 7,
            "Bachelor of Technology and Livelihood Education (BTLEd)": 7,
        },
    },
    {
        "text": "How interested is in school-community engagement, service learning, and extracurricular advising?",
        "focus": "Advising & Community Engagement",
        "weights": {
            "Bachelor of Elementary Education (BEEd)": 8,
            "Bachelor of Secondary Education (BSEd) major in Social Studies": 8,
            "Bachelor of Secondary Education (BSEd) major in English": 8,
            "Bachelor of Physical Education (BPEd)": 8,
            "Bachelor of Early Childhood Education (BECEd)": 8,
            "Bachelor of Secondary Education (BSEd) major in Science": 7,
            "Bachelor of Secondary Education (BSEd) major in Mathematics": 7,
            "Bachelor of Technology and Livelihood Education (BTLEd)": 7,
        },
    },
    {
        "text": "How comfortable is in basic classroom research (action research, data collection, reflection)?",
        "focus": "Action Research Orientation",
        "weights": {
            "Bachelor of Secondary Education (BSEd) major in Science": 8,
            "Bachelor of Secondary Education (BSEd) major in Mathematics": 8,
            "Bachelor of Secondary Education (BSEd) major in English": 7,
            "Bachelor of Secondary Education (BSEd) major in Social Studies": 7,
            "Bachelor of Elementary Education (BEEd)": 7,
            "Bachelor of Technology and Livelihood Education (BTLEd)": 7,
            "Bachelor of Early Childhood Education (BECEd)": 6,
            "Bachelor of Physical Education (BPEd)": 6,
        },
    },
    {
        "text": "How strong is interest in mentorship, patience, and building positive classroom climate?",
        "focus": "Pastoral Care & Mentorship",
        "weights": {
            "Bachelor of Elementary Education (BEEd)": 9,
            "Bachelor of Early Childhood Education (BECEd)": 9,
            "Bachelor of Physical Education (BPEd)": 8,
            "Bachelor of Secondary Education (BSEd) major in English": 8,
            "Bachelor of Secondary Education (BSEd) major in Social Studies": 8,
            "Bachelor of Secondary Education (BSEd) major in Mathematics": 7,
            "Bachelor of Secondary Education (BSEd) major in Science": 7,
            "Bachelor of Technology and Livelihood Education (BTLEd)": 7,
        },
    },
    {
        "text": "How adept is in integrating real-world applications and projects into lessons?",
        "focus": "Applied Learning & Projects",
        "weights": {
            "Bachelor of Technology and Livelihood Education (BTLEd)": 9,
            "Bachelor of Secondary Education (BSEd) major in Science": 8,
            "Bachelor of Secondary Education (BSEd) major in Mathematics": 8,
            "Bachelor of Secondary Education (BSEd) major in Social Studies": 7,
            "Bachelor of Secondary Education (BSEd) major in English": 7,
            "Bachelor of Elementary Education (BEEd)": 8,
            "Bachelor of Physical Education (BPEd)": 8,
            "Bachelor of Early Childhood Education (BECEd)": 7,
        },
    },
    {
        "text": "How comfortable is collaborating with co-teachers and parents, and coordinating school activities?",
        "focus": "Collaboration & Coordination",
        "weights": {
            "Bachelor of Elementary Education (BEEd)": 9,
            "Bachelor of Early Childhood Education (BECEd)": 9,
            "Bachelor of Secondary Education (BSEd) major in English": 8,
            "Bachelor of Secondary Education (BSEd) major in Social Studies": 8,
            "Bachelor of Secondary Education (BSEd) major in Science": 7,
            "Bachelor of Secondary Education (BSEd) major in Mathematics": 7,
            "Bachelor of Physical Education (BPEd)": 8,
            "Bachelor of Technology and Livelihood Education (BTLEd)": 7,
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
ranked_programs = sorted(scores.items(), key=lambda x: x[1], reverse=True)

print("=" * 60)
print("TOP RECOMMENDED EDUCATION PROGRAMS:")
print("=" * 60)
for i, (course, score) in enumerate(ranked_programs, 1):
    print(f"{i}. {course}: {score} points")

# Save results
output_file = 'education_assessment_results.json'
results_data = {
    "category": ACTIVE_CATEGORY,
    "ranked_programs": ranked_programs,
    "individual_scores": scores
}

with open(output_file, 'w', encoding='utf-8') as f:
    json.dump(results_data, f, indent=2, ensure_ascii=False)

print(f"\nDetailed results saved to {output_file}")
print("Assessment complete!")
