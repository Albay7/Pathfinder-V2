import json

# ==============================================
# Healthcare Careers Assessor (16 Questions)
# ==============================================

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
    "Staff Nurse",
    "Medical Technologist",
    "Pharmacist",
    "Physical Therapist",
    "Radiologic Technologist",
    "Respiratory Therapist",
    "Occupational Therapist",
    "Public Health Officer",
]
scores = {r:0 for r in roles}

questions = [
    ("I am comfortable providing direct patient care and monitoring vital signs.","Staff Nurse"),
    ("I enjoy performing laboratory tests and analyzing specimens.","Medical Technologist"),
    ("I am interested in dispensing medications and advising patients.","Pharmacist"),
    ("I like designing exercise programs for rehabilitation.","Physical Therapist"),
    ("I enjoy operating imaging equipment and conducting scans.","Radiologic Technologist"),
    ("I am skilled at managing ventilators and respiratory therapies.","Respiratory Therapist"),
    ("I like assisting patients with daily living activities.","Occupational Therapist"),
    ("I enjoy planning and implementing community health programs.","Public Health Officer"),
    ("I find satisfaction in wound care and IV therapy.","Staff Nurse"),
    ("I am comfortable ensuring lab quality control and safety.","Medical Technologist"),
    ("I enjoy compounding medications and patient counseling.","Pharmacist"),
    ("I like using manual therapy techniques and modalities.","Physical Therapist"),
    ("I am interested in radiation safety and imaging protocols.","Radiologic Technologist"),
    ("I prefer roles involving pulmonary function tests.","Respiratory Therapist"),
    ("I enjoy assessing home environments for patient safety.","Occupational Therapist"),
    ("I am committed to epidemiological research and disease prevention.","Public Health Officer"),
]

print("\nHealthcare Careers Assessment (1-5):\n")
for i,(q,r) in enumerate(questions,1):
    print(f"Q{i}. {q}")
    rating = get_user_input("Your rating: ")
    scores[r]+=rating
    print()

results = sorted(scores.items(), key=lambda x:x[1], reverse=True)
print("Top Recommended Healthcare Role(s):")
for role,score in results:
    print(f"- {role}: {score} pts")

with open('healthcare_careers_assessment.json','w') as f:
    json.dump(results,f,indent=2)
print("Results saved to healthcare_careers_assessment.json")