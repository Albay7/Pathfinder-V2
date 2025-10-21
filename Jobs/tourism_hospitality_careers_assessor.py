import json

# ================================================
# Tourism & Hospitality Careers Assessor (16 Questions)
# ================================================

def get_user_input(prompt: str) -> int:
    while True:
        try:
            v = int(input(prompt))
            if 1 <= v <= 5:
                return v
        except:
            pass
        print("Enter a number 1-5.")

roles = [
    "Hotel Front Desk Agent",
    "Restaurant Server",
    "Tour Guide",
    "Flight Attendant",
    "Chef",
    "Event Coordinator",
    "Travel Agent",
    "Housekeeping Supervisor",
]
scores = {r:0 for r in roles}

questions = [
    ("I excel at greeting and assisting hotel guests upon arrival.","Hotel Front Desk Agent"),
    ("I enjoy serving food and ensuring customer satisfaction in restaurants.","Restaurant Server"),
    ("I love guiding tourists and sharing local knowledge.","Tour Guide"),
    ("I am comfortable ensuring passenger comfort and safety on flights.","Flight Attendant"),
    ("I enjoy cooking and developing new recipes.","Chef"),
    ("I am skilled at planning and managing events.","Event Coordinator"),
    ("I like booking travel arrangements and advising clients.","Travel Agent"),
    ("I find satisfaction in supervising cleaning and maintenance in hotels.","Housekeeping Supervisor"),
    ("I excel at handling guest inquiries and problem-solving.","Hotel Front Desk Agent"),
    ("I am adept at upselling menu items and specials.","Restaurant Server"),
    ("I enjoy creating engaging tour itineraries.","Tour Guide"),
    ("I like demonstrating safety protocols during flights.","Flight Attendant"),
    ("I prefer roles in kitchen management and food safety.","Chef"),
    ("I find satisfaction in coordinating vendors and logistics.","Event Coordinator"),
    ("I am comfortable using travel booking systems.","Travel Agent"),
    ("I enjoy training staff and enforcing cleaning standards.","Housekeeping Supervisor"),
]

print("\nTourism & Hospitality Careers Assessment (1-5):\n")
for i,(q,r) in enumerate(questions,1):
    print(f"Q{i}. {q}")
    rating=get_user_input("Your rating: ")
    scores[r]+=rating
    print()

results = sorted(scores.items(), key=lambda x:x[1], reverse=True)
print("Top Recommended Tourism & Hospitality Role(s):")
for role,score in results:
    print(f"- {role}: {score} pts")

with open('tourism_hospitality_careers_assessment.json','w') as f:
    json.dump(results,f,indent=2)
print("Results saved to tourism_hospitality_careers_assessment.json")