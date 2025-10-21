import json

# =====================================================
# Liberal Arts & Social Sciences Careers Assessor (16 Questions)
# =====================================================

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
    "Customer Service Representative",
    "Social Worker",
    "Content Writer",
    "Market Research Analyst",
    "Public Relations Officer",
    "HR Assistant",
    "Government Administrative Officer",
    "Communications Specialist",
]
scores = {r:0 for r in roles}

questions = [
    ("I excel at resolving client inquiries via phone and email.","Customer Service Representative"),
    ("I feel fulfilled helping vulnerable individuals and families.","Social Worker"),
    ("I enjoy crafting written content for blogs and social media.","Content Writer"),
    ("I am skilled at designing surveys and analyzing consumer data.","Market Research Analyst"),
    ("I like managing media relations and writing press releases.","Public Relations Officer"),
    ("I enjoy assisting in recruitment and maintaining employee records.","HR Assistant"),
    ("I am comfortable handling permits, licenses, and official documents.","Government Administrative Officer"),
    ("I like developing internal newsletters and corporate communications.","Communications Specialist"),
    ("I am adept at de-escalating conflicts and empathic listening.","Customer Service Representative"),
    ("I prefer roles involving community outreach and support programs.","Social Worker"),
    ("I like editing and proofreading written materials.","Content Writer"),
    ("I enjoy interpreting market trends and reporting insights.","Market Research Analyst"),
    ("I find satisfaction in organizing press conferences and events.","Public Relations Officer"),
    ("I like coordinating onboarding and training sessions.","HR Assistant"),
    ("I am interested in policy research and public sector administration.","Government Administrative Officer"),
    ("I enjoy managing social media channels and corporate blogs.","Communications Specialist"),
]

print("\nLiberal Arts & Social Sciences Careers Assessment (1-5):\n")
for i,(q,r) in enumerate(questions,1):
    print(f"Q{i}. {q}")
    score = get_user_input("Your rating: ")
    scores[r]+=score
    print()

results = sorted(scores.items(), key=lambda x:x[1], reverse=True)
print("Top Recommended Liberal Arts Role(s):")
for role,score in results:
    print(f"- {role}: {score} pts")

with open('liberal_arts_careers_assessment.json','w') as f:
    json.dump(results,f,indent=2)
print("Results saved to liberal_arts_careers_assessment.json")