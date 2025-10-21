import json

# ============================================
# Engineering Careers Assessor (16 Questions)
# ============================================

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
    "Civil Engineer",
    "Mechanical Engineer",
    "Electrical Engineer",
    "Electronics Engineer",
    "Industrial Engineer",
    "Computer Engineer",
    "Chemical Engineer",
    "Project Engineer",
]
scores = {r:0 for r in roles}

questions = [
    ("I enjoy designing and analyzing structures, roads, and bridges.","Civil Engineer"),
    ("I like applying principles of mechanics and materials to machines.","Mechanical Engineer"),
    ("I am comfortable working with power systems and circuit design.","Electrical Engineer"),
    ("I enjoy developing and testing electronic devices and systems.","Electronics Engineer"),
    ("I like optimizing production processes and improving efficiency.","Industrial Engineer"),
    ("I enjoy programming hardware and embedded systems.","Computer Engineer"),
    ("I am interested in chemical processes and material transformation.","Chemical Engineer"),
    ("I like coordinating engineering projects from planning to completion.","Project Engineer"),
    ("I am skilled at site surveying and construction supervision.","Civil Engineer"),
    ("I enjoy using CAD and CAE software for mechanical designs.","Mechanical Engineer"),
    ("I am adept at control systems and automation tasks.","Electrical Engineer"),
    ("I like developing PCB layouts and microcontroller projects.","Electronics Engineer"),
    ("I find satisfaction in quality control and lean manufacturing.","Industrial Engineer"),
    ("I like integrating software and hardware in devices.","Computer Engineer"),
    ("I enjoy process simulation and chemical plant design.","Chemical Engineer"),
    ("I am comfortable managing budgets, timelines, and teams.","Project Engineer"),
]

print("\nEngineering Careers Assessment (1-5):\n")
for i,(q,r) in enumerate(questions,1):
    print(f"Q{i}. {q}")
    score = get_user_input("Your rating: ")
    scores[r]+=score
    print()

results = sorted(scores.items(), key=lambda x:x[1], reverse=True)
print("Top Recommended Engineering Role(s):")
for r,s in results:
    print(f"- {r}: {s} pts")
with open('engineering_careers_assessment.json','w') as f:
    json.dump(results,f,indent=2)
print("Results saved to engineering_careers_assessment.json")