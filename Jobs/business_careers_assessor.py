import json

# ============================================
# Business Careers Assessor (16 Questions)
# ============================================

def get_user_input(prompt: str) -> int:
    while True:
        try:
            value = int(input(prompt))
            if 1 <= value <= 5:
                return value
        except ValueError:
            pass
        print("Please enter a number between 1 and 5.")

# Business roles and initial scoresoles = [
    "Accountant",
    "Sales Representative",
    "Administrative Officer",
    "Marketing Coordinator",
    "Financial Analyst",
    "Human Resources Specialist",
    "Business Development Manager",
    "Operations Manager",
]
scores = {role: 0 for role in roles}

# 16 statements mapping
questions = [
    ("I enjoy analyzing financial statements and preparing budgets.", "Accountant"),
    ("I am motivated by setting and meeting sales targets and building client relationships.", "Sales Representative"),
    ("I am comfortable organizing schedules, managing office systems, and handling administrative tasks.", "Administrative Officer"),
    ("I like developing marketing plans, crafting promotional campaigns, and tracking their performance.", "Marketing Coordinator"),
    ("I excel at interpreting market data and making investment recommendations.", "Financial Analyst"),
    ("I find satisfaction in recruiting team members, resolving workplace issues, and designing benefit programs.", "Human Resources Specialist"),
    ("I thrive on identifying new business opportunities, pitching proposals, and negotiating partnerships.", "Business Development Manager"),
    ("I enjoy overseeing processes, improving operational efficiency, and coordinating cross-functional teams.", "Operations Manager"),
    ("I like conducting competitor analysis and adjusting strategies accordingly.", "Marketing Coordinator"),
    ("I prefer roles where I manage budgets and control costs.", "Accountant"),
    ("I enjoy training staff and leading employee engagement initiatives.", "Human Resources Specialist"),
    ("I am driven by closing deals and negotiating contracts.", "Business Development Manager"),
    ("I regularly optimize workflows to enhance productivity.", "Operations Manager"),
    ("I am adept at resolving customer complaints and ensuring satisfaction.", "Administrative Officer"),
    ("I often evaluate investment opportunities and market trends.", "Financial Analyst"),
    ("I am skilled at creating and managing promotional events.", "Marketing Coordinator"),
]

print("\nBusiness Careers Assessment: Rate 1 (Strongly Disagree) to 5 (Strongly Agree)\n")
for idx, (statement, role) in enumerate(questions, 1):
    print(f"Q{idx}. {statement}")
    rating = get_user_input("Your rating (1-5): ")
    scores[role] += rating
    print()

# Results
top_roles = sorted(scores.items(), key=lambda x: x[1], reverse=True)
print("Top Recommended Business Role(s):")
for role, score in top_roles:
    print(f"- {role}: {score} points")

# Save results
with open('business_careers_assessment.json', 'w') as f:
    json.dump(top_roles, f, indent=2)

print("\nAssessment complete! Results saved to business_careers_assessment.json")