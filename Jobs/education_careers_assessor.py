import json

# =============================================
# Education Careers Assessor (16 Questions)
# =============================================

def get_user_input(prompt: str) -> int:
    while True:
        try:
            value = int(input(prompt))
            if 1 <= value <= 5:
                return value
        except ValueError:
            pass
        print("Please enter a number between 1 and 5.")

# Education roles and initial scores
roles = [
    "Elementary School Teacher",
    "High School English Teacher",
    "High School Math Teacher",
    "High School Science Teacher",
    "Preschool Teacher",
    "Special Education Teacher",
    "Educational Coordinator",
    "Curriculum Developer",
]
scores = {role: 0 for role in roles}

# 16 statements mapping
questions = [
    ("I enjoy teaching basic literacy and numeracy to young children.", "Elementary School Teacher"),
    ("I am passionate about teaching English language and literature.", "High School English Teacher"),
    ("I excel at explaining mathematical concepts and solving equations.", "High School Math Teacher"),
    ("I enjoy conducting experiments and teaching scientific principles.", "High School Science Teacher"),
    ("I find joy in nurturing early childhood development through play.", "Preschool Teacher"),
    ("I am committed to supporting learners with special needs.", "Special Education Teacher"),
    ("I like coordinating school activities, schedules, and programs.", "Educational Coordinator"),
    ("I enjoy designing lesson plans and educational materials.", "Curriculum Developer"),
    ("I am comfortable managing classroom behavior and engagement.", "Elementary School Teacher"),
    ("I like analyzing language skills assessment data.", "High School English Teacher"),
    ("I enjoy using educational technology in lesson delivery.", "Curriculum Developer"),
    ("I prefer roles involving teacher training and professional development.", "Educational Coordinator"),
    ("I am skilled at adapting lessons for diverse learning needs.", "Special Education Teacher"),
    ("I like creating interactive math activities and assessments.", "High School Math Teacher"),
    ("I find satisfaction in organizing science fairs and labs.", "High School Science Teacher"),
    ("I thrive on developing story-based learning for preschoolers.", "Preschool Teacher"),
]

print("\nEducation Careers Assessment: Rate 1 (Strongly Disagree) to 5 (Strongly Agree)\n")
for idx, (statement, role) in enumerate(questions, 1):
    print(f"Q{idx}. {statement}")
    rating = get_user_input("Your rating (1-5): ")
    scores[role] += rating
    print()

# Results
top_roles = sorted(scores.items(), key=lambda x: x[1], reverse=True)
print("Top Recommended Education Role(s):")
for role, score in top_roles:
    print(f"- {role}: {score} points")

# Save results
with open('education_careers_assessment.json', 'w') as f:
    json.dump(top_roles, f, indent=2)

print("\nAssessment complete! Results saved to education_careers_assessment.json")