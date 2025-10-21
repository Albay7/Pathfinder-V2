# Business Administration & Related Disciplines Questionnaire with Weighted Mapping

# Define the courses in this category
courses = [
    "BS Business Administration",
    "BS Accountancy", 
    "BS Marketing Management",
    "BS Financial Management",
    "BS Human Resource Management", 
    "BS Entrepreneurship",
    "BS Management Accounting",
    "BS Operations Management"
]

# Create the questionnaire with 16 questions covering skills, knowledge, and interests
questionnaire = [
    {
        "id": 1,
        "question": "How comfortable are you with mathematical calculations and numerical analysis?",
        "category": "Skill",
        "focus": "Mathematical/Analytical Skills"
    },
    {
        "id": 2, 
        "question": "How interested are you in analyzing financial statements and business performance metrics?",
        "category": "Interest",
        "focus": "Financial Analysis"
    },
    {
        "id": 3,
        "question": "How well can you communicate complex ideas clearly to different audiences?",
        "category": "Skill", 
        "focus": "Communication Skills"
    },
    {
        "id": 4,
        "question": "How interested are you in leading teams and managing people?",
        "category": "Interest",
        "focus": "Leadership/Management"
    },
    {
        "id": 5,
        "question": "How comfortable are you with using technology and business software applications?",
        "category": "Skill",
        "focus": "Technology Skills"
    },
    {
        "id": 6,
        "question": "How interested are you in creating and developing new business ideas or products?",
        "category": "Interest", 
        "focus": "Innovation/Entrepreneurship"
    },
    {
        "id": 7,
        "question": "How well do you understand basic accounting principles and bookkeeping?",
        "category": "Knowledge",
        "focus": "Accounting Knowledge"
    },
    {
        "id": 8,
        "question": "How interested are you in understanding consumer behavior and market trends?",
        "category": "Interest",
        "focus": "Marketing/Consumer Behavior"
    },
    {
        "id": 9,
        "question": "How comfortable are you with making decisions under pressure and uncertainty?",
        "category": "Skill",
        "focus": "Decision Making"
    },
    {
        "id": 10,
        "question": "How well do you understand economic principles and their business applications?",
        "category": "Knowledge", 
        "focus": "Economics/Business Environment"
    },
    {
        "id": 11,
        "question": "How interested are you in managing budgets and financial planning?",
        "category": "Interest",
        "focus": "Financial Planning"
    },
    {
        "id": 12,
        "question": "How well can you organize and manage multiple tasks simultaneously?",
        "category": "Skill",
        "focus": "Organization/Time Management"
    },
    {
        "id": 13,
        "question": "How interested are you in understanding workplace policies and employee relations?",
        "category": "Interest",
        "focus": "Human Resources"
    },
    {
        "id": 14,
        "question": "How comfortable are you with analyzing data and identifying business patterns?",
        "category": "Skill",
        "focus": "Data Analysis"
    },
    {
        "id": 15,
        "question": "How well do you understand legal and regulatory aspects of business operations?",
        "category": "Knowledge",
        "focus": "Business Law/Compliance"
    },
    {
        "id": 16,
        "question": "How interested are you in optimizing business processes and operational efficiency?",
        "category": "Interest",
        "focus": "Operations Management"
    }
]

# Create weight mapping for each question to each course (scale 1-10)
# 10 = Highly relevant, 5 = Moderately relevant, 1 = Low relevance

weight_mapping = {
    1: {  # Mathematical/Analytical Skills
        "BS Business Administration": 7,
        "BS Accountancy": 10, 
        "BS Marketing Management": 6,
        "BS Financial Management": 9,
        "BS Human Resource Management": 5,
        "BS Entrepreneurship": 6,
        "BS Management Accounting": 10,
        "BS Operations Management": 8
    },
    2: {  # Financial Analysis Interest
        "BS Business Administration": 8,
        "BS Accountancy": 10,
        "BS Marketing Management": 5,
        "BS Financial Management": 10,
        "BS Human Resource Management": 4,
        "BS Entrepreneurship": 7,
        "BS Management Accounting": 9,
        "BS Operations Management": 6
    },
    3: {  # Communication Skills
        "BS Business Administration": 9,
        "BS Accountancy": 7,
        "BS Marketing Management": 10,
        "BS Financial Management": 7,
        "BS Human Resource Management": 10,
        "BS Entrepreneurship": 9,
        "BS Management Accounting": 6,
        "BS Operations Management": 8
    },
    4: {  # Leadership/Management Interest
        "BS Business Administration": 10,
        "BS Accountancy": 6,
        "BS Marketing Management": 8,
        "BS Financial Management": 7,
        "BS Human Resource Management": 10,
        "BS Entrepreneurship": 10,
        "BS Management Accounting": 5,
        "BS Operations Management": 9
    },
    5: {  # Technology Skills
        "BS Business Administration": 8,
        "BS Accountancy": 9,
        "BS Marketing Management": 8,
        "BS Financial Management": 8,
        "BS Human Resource Management": 7,
        "BS Entrepreneurship": 7,
        "BS Management Accounting": 9,
        "BS Operations Management": 9
    },
    6: {  # Innovation/Entrepreneurship Interest
        "BS Business Administration": 7,
        "BS Accountancy": 3,
        "BS Marketing Management": 8,
        "BS Financial Management": 5,
        "BS Human Resource Management": 6,
        "BS Entrepreneurship": 10,
        "BS Management Accounting": 4,
        "BS Operations Management": 6
    },
    7: {  # Accounting Knowledge
        "BS Business Administration": 7,
        "BS Accountancy": 10,
        "BS Marketing Management": 4,
        "BS Financial Management": 8,
        "BS Human Resource Management": 5,
        "BS Entrepreneurship": 6,
        "BS Management Accounting": 10,
        "BS Operations Management": 6
    },
    8: {  # Marketing/Consumer Behavior Interest
        "BS Business Administration": 7,
        "BS Accountancy": 3,
        "BS Marketing Management": 10,
        "BS Financial Management": 4,
        "BS Human Resource Management": 6,
        "BS Entrepreneurship": 8,
        "BS Management Accounting": 3,
        "BS Operations Management": 5
    },
    9: {  # Decision Making Skills
        "BS Business Administration": 9,
        "BS Accountancy": 7,
        "BS Marketing Management": 8,
        "BS Financial Management": 9,
        "BS Human Resource Management": 8,
        "BS Entrepreneurship": 10,
        "BS Management Accounting": 7,
        "BS Operations Management": 9
    },
    10: {  # Economics/Business Environment Knowledge
        "BS Business Administration": 9,
        "BS Accountancy": 6,
        "BS Marketing Management": 7,
        "BS Financial Management": 8,
        "BS Human Resource Management": 6,
        "BS Entrepreneurship": 8,
        "BS Management Accounting": 6,
        "BS Operations Management": 7
    },
    11: {  # Financial Planning Interest
        "BS Business Administration": 8,
        "BS Accountancy": 9,
        "BS Marketing Management": 4,
        "BS Financial Management": 10,
        "BS Human Resource Management": 5,
        "BS Entrepreneurship": 8,
        "BS Management Accounting": 9,
        "BS Operations Management": 6
    },
    12: {  # Organization/Time Management Skills
        "BS Business Administration": 9,
        "BS Accountancy": 8,
        "BS Marketing Management": 9,
        "BS Financial Management": 8,
        "BS Human Resource Management": 9,
        "BS Entrepreneurship": 9,
        "BS Management Accounting": 8,
        "BS Operations Management": 10
    },
    13: {  # Human Resources Interest
        "BS Business Administration": 7,
        "BS Accountancy": 4,
        "BS Marketing Management": 6,
        "BS Financial Management": 5,
        "BS Human Resource Management": 10,
        "BS Entrepreneurship": 7,
        "BS Management Accounting": 4,
        "BS Operations Management": 7
    },
    14: {  # Data Analysis Skills
        "BS Business Administration": 8,
        "BS Accountancy": 9,
        "BS Marketing Management": 8,
        "BS Financial Management": 9,
        "BS Human Resource Management": 6,
        "BS Entrepreneurship": 7,
        "BS Management Accounting": 9,
        "BS Operations Management": 9
    },
    15: {  # Business Law/Compliance Knowledge
        "BS Business Administration": 8,
        "BS Accountancy": 9,
        "BS Marketing Management": 6,
        "BS Financial Management": 7,
        "BS Human Resource Management": 8,
        "BS Entrepreneurship": 7,
        "BS Management Accounting": 8,
        "BS Operations Management": 7
    },
    16: {  # Operations Management Interest
        "BS Business Administration": 8,
        "BS Accountancy": 5,
        "BS Marketing Management": 6,
        "BS Financial Management": 6,
        "BS Human Resource Management": 7,
        "BS Entrepreneurship": 7,
        "BS Management Accounting": 6,
        "BS Operations Management": 10
    }
}

# Display the questionnaire
print("BUSINESS ADMINISTRATION & RELATED DISCIPLINES")
print("=" * 60)
print("CAREER ASSESSMENT QUESTIONNAIRE")
print("=" * 60)
print()
print("Instructions: Rate each question on a scale of 1-10")
print("1 = Strongly Disagree/Not Interested, 10 = Strongly Agree/Very Interested")
print()

for i, q in enumerate(questionnaire, 1):
    print(f"Question {i}: {q['question']}")
    print(f"Category: {q['category']} | Focus: {q['focus']}")
    print("Rating: ___/10")
    print()

print("=" * 60)
print("SCORING GUIDE")
print("=" * 60)
print()
print("Each question has different weight values for each course:")
print()

# Create a summary table showing weights
import pandas as pd

# Create DataFrame for weight mapping
weight_df = pd.DataFrame(weight_mapping).T
weight_df.index.name = 'Question'

print("WEIGHT MAPPING TABLE")
print("(Higher numbers indicate stronger relevance to that course)")
print()
print(weight_df.to_string())

print()
print("=" * 60)
print("HOW TO CALCULATE YOUR COURSE MATCH:")
print("=" * 60)
print()
print("1. For each question, multiply your rating (1-10) by the weight for each course")
print("2. Add up all weighted scores for each course")
print("3. The course with the highest total score is your best match")
print()
print("Example:")
print("If you rated Question 1 as 8/10:")
print("- BS Accountancy score = 8 × 10 = 80 points")
print("- BS Marketing Management score = 8 × 6 = 48 points")
print("- Continue for all 16 questions and sum the totals")

# Save to CSV
questionnaire_df = pd.DataFrame(questionnaire)
questionnaire_df.to_csv('business_admin_questionnaire.csv', index=False)
weight_df.to_csv('business_admin_weights.csv')

print(f"\nFiles saved:")
print("- business_admin_questionnaire.csv")
print("- business_admin_weights.csv")