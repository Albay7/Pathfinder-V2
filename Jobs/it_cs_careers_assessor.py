import json

# ===============================================
# IT & CS Careers Assessor (16 Questions)
# ===============================================

def get_user_input(prompt: str) -> int:
    while True:
        try:
            val = int(input(prompt))
            if 1 <= val <= 5:
                return val
        except ValueError:
            pass
        print("Please enter a number between 1 and 5.")

roles = [
    "Software Developer",
    "IT Support Specialist",
    "Data Analyst",
    "Web Developer",
    "Systems Administrator",
    "Cybersecurity Analyst",
    "Database Administrator",
    "Network Administrator",
]
scores = {r:0 for r in roles}

questions = [
    ("I enjoy writing code and building software applications.", "Software Developer"),
    ("I like troubleshooting hardware/software issues for users.", "IT Support Specialist"),
    ("I am skilled at analyzing data sets and creating reports.", "Data Analyst"),
    ("I enjoy designing and coding websites.", "Web Developer"),
    ("I like managing servers, networks, and system configurations.", "Systems Administrator"),
    ("I find satisfaction in securing systems and monitoring threats.", "Cybersecurity Analyst"),
    ("I am comfortable designing and maintaining databases.", "Database Administrator"),
    ("I enjoy configuring and maintaining network infrastructure.", "Network Administrator"),
    ("I like optimizing database queries and performance.", "Database Administrator"),
    ("I enjoy developing APIs and backend services.", "Software Developer"),
    ("I prefer roles involving cloud infrastructure and virtualization.", "Systems Administrator"),
    ("I am interested in ethical hacking and vulnerability assessment.", "Cybersecurity Analyst"),
    ("I like creating interactive user interfaces.", "Web Developer"),
    ("I enjoy monitoring network traffic and ensuring uptime.", "Network Administrator"),
    ("I am adept at building dashboards and visualizing data trends.", "Data Analyst"),
    ("I like documenting IT processes and creating knowledge base articles.", "IT Support Specialist"),
]

print("\nIT & CS Careers Assessment (1-5):\n")
for idx, (q, r) in enumerate(questions, 1):
    print(f"Q{idx}. {q}")
    rating = get_user_input("Your rating: ")
    scores[r] += rating
    print()

results = sorted(scores.items(), key=lambda x: x[1], reverse=True)
print("Top Recommended IT & CS Role(s):")
for role, score in results:
    print(f"- {role}: {score} pts")

with open('it_cs_careers_assessment.json', 'w') as f:
    json.dump(results, f, indent=2)
print("Results saved to it_cs_careers_assessment.json")