import json

# ==================================
# Tourism & Hospitality Assessor
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
    "Tourism & Hospitality Management": [
        "BS Tourism Management",
        "BS Hotel and Restaurant Management",
        "BS International Hospitality Management",
        "BS Travel Management",
        "BS Event Management",
        "BS Cruise Ship Management",
        "BS Food Service Management",
        "BS Culinary Arts Management",
    ],
}

# Active category to assess now
ACTIVE_CATEGORY = "Tourism & Hospitality Management"
ACTIVE_COURSES = categories[ACTIVE_CATEGORY]

# 16-question instrument for Tourism & Hospitality
# Rating scale: 1 (low) to 10 (high). Each response multiplies the per-course weight.

questions = [
    {
        "text": "How strong is customer service, guest relations, and front-office communication?",
        "focus": "Guest Service & Front Office",
        "weights": {
            "BS Hotel and Restaurant Management": 10,
            "BS International Hospitality Management": 9,
            "BS Tourism Management": 8,
            "BS Travel Management": 8,
            "BS Cruise Ship Management": 8,
            "BS Event Management": 7,
            "BS Food Service Management": 6,
            "BS Culinary Arts Management": 5,
        },
    },
    {
        "text": "How comfortable is with planning itineraries, ticketing, and travel operations (GDS basics)?",
        "focus": "Travel Ops & Itineraries",
        "weights": {
            "BS Travel Management": 10,
            "BS Tourism Management": 9,
            "BS International Hospitality Management": 7,
            "BS Cruise Ship Management": 7,
            "BS Event Management": 6,
            "BS Hotel and Restaurant Management": 5,
            "BS Food Service Management": 4,
            "BS Culinary Arts Management": 3,
        },
    },
    {
        "text": "How comfortable is with food & beverage service workflows and restaurant operations?",
        "focus": "F&B Service Operations",
        "weights": {
            "BS Food Service Management": 10,
            "BS Hotel and Restaurant Management": 9,
            "BS International Hospitality Management": 8,
            "BS Culinary Arts Management": 7,
            "BS Tourism Management": 6,
            "BS Cruise Ship Management": 6,
            "BS Event Management": 5,
            "BS Travel Management": 4,
        },
    },
    {
        "text": "How interested is in culinary techniques, kitchen management, and menu development?",
        "focus": "Culinary Arts & Kitchen Ops",
        "weights": {
            "BS Culinary Arts Management": 10,
            "BS Food Service Management": 9,
            "BS Hotel and Restaurant Management": 8,
            "BS International Hospitality Management": 7,
            "BS Cruise Ship Management": 6,
            "BS Tourism Management": 5,
            "BS Event Management": 4,
            "BS Travel Management": 3,
        },
    },
    {
        "text": "How comfortable is with housekeeping standards, facilities management, and room division?",
        "focus": "Housekeeping & Facilities",
        "weights": {
            "BS Hotel and Restaurant Management": 10,
            "BS International Hospitality Management": 9,
            "BS Tourism Management": 7,
            "BS Cruise Ship Management": 7,
            "BS Food Service Management": 5,
            "BS Event Management": 5,
            "BS Travel Management": 4,
            "BS Culinary Arts Management": 3,
        },
    },
    {
        "text": "How skilled is in event planning, budgeting, logistics, and vendor coordination?",
        "focus": "Events & MICE",
        "weights": {
            "BS Event Management": 10,
            "BS Tourism Management": 9,
            "BS International Hospitality Management": 8,
            "BS Hotel and Restaurant Management": 7,
            "BS Travel Management": 7,
            "BS Cruise Ship Management": 6,
            "BS Food Service Management": 5,
            "BS Culinary Arts Management": 4,
        },
    },
    {
        "text": "How interested is in cruise operations, onboard services, and maritime hospitality?",
        "focus": "Cruise Ops & Onboard Service",
        "weights": {
            "BS Cruise Ship Management": 10,
            "BS International Hospitality Management": 8,
            "BS Hotel and Restaurant Management": 7,
            "BS Tourism Management": 6,
            "BS Event Management": 5,
            "BS Food Service Management": 5,
            "BS Travel Management": 5,
            "BS Culinary Arts Management": 5,
        },
    },
    {
        "text": "How comfortable is with hospitality marketing, destination promotion, and branding?",
        "focus": "Marketing & Destination Branding",
        "weights": {
            "BS Tourism Management": 10,
            "BS International Hospitality Management": 8,
            "BS Event Management": 8,
            "BS Travel Management": 8,
            "BS Hotel and Restaurant Management": 7,
            "BS Cruise Ship Management": 6,
            "BS Food Service Management": 5,
            "BS Culinary Arts Management": 4,
        },
    },
    {
        "text": "How strong is knowledge of sanitation, food safety, and risk management standards?",
        "focus": "Sanitation & Risk Management",
        "weights": {
            "BS Food Service Management": 10,
            "BS Culinary Arts Management": 9,
            "BS Hotel and Restaurant Management": 8,
            "BS International Hospitality Management": 8,
            "BS Cruise Ship Management": 7,
            "BS Tourism Management": 6,
            "BS Travel Management": 5,
            "BS Event Management": 5,
        },
    },
    {
        "text": "How comfortable is with revenue management, basic accounting, and cost control?",
        "focus": "Revenue & Cost Control",
        "weights": {
            "BS International Hospitality Management": 9,
            "BS Hotel and Restaurant Management": 9,
            "BS Tourism Management": 8,
            "BS Travel Management": 7,
            "BS Event Management": 7,
            "BS Food Service Management": 7,
            "BS Cruise Ship Management": 6,
            "BS Culinary Arts Management": 5,
        },
    },
    {
        "text": "How interested is in tour guiding, cultural interpretation, and heritage tourism?",
        "focus": "Tour Guiding & Interpretation",
        "weights": {
            "BS Tourism Management": 10,
            "BS Travel Management": 9,
            "BS International Hospitality Management": 7,
            "BS Event Management": 6,
            "BS Hotel and Restaurant Management": 5,
            "BS Cruise Ship Management": 5,
            "BS Culinary Arts Management": 4,
            "BS Food Service Management": 4,
        },
    },
    {
        "text": "How comfortable is with cross-cultural communication and international service standards?",
        "focus": "Cross-Culture & Standards",
        "weights": {
            "BS International Hospitality Management": 10,
            "BS Cruise Ship Management": 9,
            "BS Tourism Management": 8,
            "BS Travel Management": 8,
            "BS Hotel and Restaurant Management": 7,
            "BS Event Management": 6,
            "BS Food Service Management": 5,
            "BS Culinary Arts Management": 4,
        },
    },
    {
        "text": "How strong is interest in airline/airport operations, ground services, and travel regulations?",
        "focus": "Airline & Airport Basics",
        "weights": {
            "BS Travel Management": 10,
            "BS Tourism Management": 9,
            "BS International Hospitality Management": 7,
            "BS Cruise Ship Management": 6,
            "BS Event Management": 5,
            "BS Hotel and Restaurant Management": 5,
            "BS Food Service Management": 4,
            "BS Culinary Arts Management": 3,
        },
    },
    {
        "text": "How comfortable is with sustainability, ecotourism, and responsible tourism practices?",
        "focus": "Sustainability & Ecotourism",
        "weights": {
            "BS Tourism Management": 10,
            "BS Travel Management": 9,
            "BS International Hospitality Management": 8,
            "BS Event Management": 7,
            "BS Hotel and Restaurant Management": 6,
            "BS Cruise Ship Management": 6,
            "BS Food Service Management": 5,
            "BS Culinary Arts Management": 4,
        },
    },
    {
        "text": "How comfortable is with digital tools for reservations, PMS, CRM, and event software?",
        "focus": "Digital Tools & Systems",
        "weights": {
            "BS International Hospitality Management": 9,
            "BS Hotel and Restaurant Management": 9,
            "BS Tourism Management": 8,
            "BS Travel Management": 8,
            "BS Event Management": 8,
            "BS Cruise Ship Management": 7,
            "BS Food Service Management": 6,
            "BS Culinary Arts Management": 5,
        },
    },
    {
        "text": "How strong is interest in leadership, team supervision, and service quality management?",
        "focus": "Leadership & Service Quality",
        "weights": {
            "BS International Hospitality Management": 10,
            "BS Hotel and Restaurant Management": 9,
            "BS Tourism Management": 8,
            "BS Event Management": 8,
            "BS Travel Management": 7,
            "BS Cruise Ship Management": 7,
            "BS Food Service Management": 6,
            "BS Culinary Arts Management": 5,
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
print("TOP RECOMMENDED TOURISM & HOSPITALITY PROGRAMS:")
print("=" * 60)
for i, (course, score) in enumerate(ranked_programs, 1):
    print(f"{i}. {course}: {score} points")

# Save results
output_file = 'tourism_hospitality_assessment_results.json'
results_data = {
    "category": ACTIVE_CATEGORY,
    "ranked_programs": ranked_programs,
    "individual_scores": scores,
}

with open(output_file, 'w', encoding='utf-8') as f:
    json.dump(results_data, f, indent=2, ensure_ascii=False)

print(f"\nDetailed results saved to {output_file}")
print("Assessment complete!")