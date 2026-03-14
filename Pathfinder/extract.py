import sys
import glob
import re

courses = set()
files = glob.glob('C:/Users/Hendrix/OneDrive/Desktop/Projects/PathfinderApp/Courses/*.py')

for filepath in files:
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()
        # Find all quoted strings that look like typical PH degree names
        matches = re.findall(r'"(B[A|S|P|E|T][a-zA-Z\s]+.*?)"', content)
        for m in matches:
            if 'BS ' in m or 'BA ' in m or 'Bachelor ' in m:
                courses.add(m)

print("--- DEFINITIVE COURSES LIST ---")
for c in sorted(list(courses)):
    print(c)
