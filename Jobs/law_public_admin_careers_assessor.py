import json

# =========================================================
# Law & Public Administration Careers Assessor (16 Questions)
# =========================================================

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
    "Government Administrative Officer",
    "Legal Assistant/Paralegal",
    "Police Officer",
    "Compliance Officer",
    "Court Personnel",
    "Immigration Officer",
    "Customs Officer",
    "Public Relations Officer",
]
scores = {r:0 for r in roles}

questions = [
    ("I am comfortable managing government permits, records, and public services.","Government Administrative Officer"),
    ("I enjoy preparing legal documents, briefs, and conducting legal research.","Legal Assistant/Paralegal"),
    ("I am interested in maintaining public order and enforcing laws.","Police Officer"),
    ("I like ensuring company practices comply with regulations and standards.","Compliance Officer"),
    ("I am skilled at supporting court operations and case management.","Court Personnel"),
    ("I find satisfaction in verifying immigration applications and border control.","Immigration Officer"),
    ("I enjoy inspecting imports/exports and enforcing customs regulations.","Customs Officer"),
    ("I like managing communications between public institutions and citizens.","Public Relations Officer"),
    ("I excel at drafting official correspondence and memos.","Government Administrative Officer"),
    ("I prefer roles involving trial preparation and case file organization.","Legal Assistant/Paralegal"),
    ("I enjoy conducting field patrols and community policing.","Police Officer"),
    ("I am interested in risk assessments and auditing.","Compliance Officer"),
    ("I like organizing court schedules and dockets.","Court Personnel"),
    ("I find satisfaction in visa interviews and document inspections.","Immigration Officer"),
    ("I enjoy customs valuation and tariff classification.","Customs Officer"),
    ("I am adept at drafting press releases and media statements.","Public Relations Officer"),
]

print("\nLaw & Public Admin Careers Assessment (1-5):\n")
for i,(q,r) in enumerate(questions,1):
    print(f"Q{i}. {q}")
    score = get_user_input("Your rating: ")
    scores[r]+=score
    print()

results = sorted(scores.items(), key=lambda x:x[1], reverse=True)
print("Top Recommended Law & Public Admin Role(s):")
for role,score in results:
    print(f"- {role}: {score} pts")

with open('law_public_admin_careers_assessment.json','w') as f:
    json.dump(results,f,indent=2)
print("Results saved to law_public_admin_careers_assessment.json")