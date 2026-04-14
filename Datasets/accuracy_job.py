import os
import random

# ─────────────────────────────────────────────────────────────────────────────
# REPORT OUTPUT PATH
# ─────────────────────────────────────────────────────────────────────────────
BASE_DIR    = os.path.dirname(os.path.abspath(__file__))
REPORT_PATH = os.path.join(BASE_DIR, "job_accuracy_report.txt")

# ─────────────────────────────────────────────────────────────────────────────
# JOB ROLES & WEIGHT MAPPINGS
# Mirrored from: validate_all_logic.py (JOB_ROLES / JOB_MAPPINGS)
# ─────────────────────────────────────────────────────────────────────────────

JOB_ROLES = {
    "business":    ["Business Analyst", "Financial Analyst", "Marketing Manager", "Operations Manager",
                    "Human Resources Manager", "Project Manager", "Sales Manager", "Management Consultant"],
    "healthcare":  ["Registered Nurse", "Medical Laboratory Technologist", "Physical Therapist",
                    "Pharmacist", "Radiologic Technologist", "Respiratory Therapist",
                    "Public Health Specialist", "Occupational Therapist"],
    "technology":  ["Software Developer", "Data Scientist", "Cybersecurity Analyst", "Network Administrator",
                    "UX/UI Designer", "AI/ML Engineer", "Database Administrator", "IT Project Manager"],
    "creative":    ["Graphic Designer", "Content Writer", "Digital Marketing Specialist", "Art Director",
                    "Photographer", "Social Media Manager", "Creative Director", "Brand Manager"],
    "education":   ["Elementary School Teacher", "High School English Teacher", "High School Math Teacher",
                    "High School Science Teacher", "Preschool Teacher", "Special Education Teacher",
                    "Educational Coordinator", "Curriculum Developer"],
    "engineering": ["Civil Engineer", "Mechanical Engineer", "Electrical Engineer", "Software Engineer",
                    "Chemical Engineer", "Environmental Engineer", "Industrial Engineer", "Biomedical Engineer"],
    "law":         ["Corporate Lawyer", "Criminal Defense Attorney", "Public Defender", "Legal Researcher",
                    "Paralegal", "Compliance Officer", "Judge", "Legal Consultant"],
    "tourism":     ["Travel Agent", "Hotel Manager", "Tour Guide", "Event Planner",
                    "Restaurant Manager", "Tourism Marketing Specialist", "Resort Operations Manager",
                    "Travel Consultant"],
}

JOB_MAPPINGS = {
    "business": {
        1:  {"Business Analyst": 10, "Financial Analyst": 2,  "Marketing Manager": 2,  "Operations Manager": 5,  "Human Resources Manager": 2,  "Project Manager": 5,  "Sales Manager": 2,  "Management Consultant": 10},
        2:  {"Business Analyst": 10, "Financial Analyst": 10, "Marketing Manager": 2,  "Operations Manager": 5,  "Human Resources Manager": 2,  "Project Manager": 2,  "Sales Manager": 2,  "Management Consultant": 5},
        3:  {"Business Analyst": 2,  "Financial Analyst": 2,  "Marketing Manager": 2,  "Operations Manager": 10, "Human Resources Manager": 5,  "Project Manager": 10, "Sales Manager": 2,  "Management Consultant": 5},
        4:  {"Business Analyst": 5,  "Financial Analyst": 2,  "Marketing Manager": 10, "Operations Manager": 2,  "Human Resources Manager": 2,  "Project Manager": 2,  "Sales Manager": 10, "Management Consultant": 5},
        5:  {"Business Analyst": 2,  "Financial Analyst": 10, "Marketing Manager": 2,  "Operations Manager": 5,  "Human Resources Manager": 2,  "Project Manager": 5,  "Sales Manager": 2,  "Management Consultant": 10},
        6:  {"Business Analyst": 2,  "Financial Analyst": 2,  "Marketing Manager": 5,  "Operations Manager": 2,  "Human Resources Manager": 10, "Project Manager": 2,  "Sales Manager": 10, "Management Consultant": 5},
        7:  {"Business Analyst": 2,  "Financial Analyst": 2,  "Marketing Manager": 10, "Operations Manager": 10, "Human Resources Manager": 2,  "Project Manager": 5,  "Sales Manager": 5,  "Management Consultant": 2},
        8:  {"Business Analyst": 5,  "Financial Analyst": 10, "Marketing Manager": 2,  "Operations Manager": 5,  "Human Resources Manager": 2,  "Project Manager": 10, "Sales Manager": 2,  "Management Consultant": 2},
        9:  {"Business Analyst": 10, "Financial Analyst": 2,  "Marketing Manager": 10, "Operations Manager": 2,  "Human Resources Manager": 5,  "Project Manager": 2,  "Sales Manager": 5,  "Management Consultant": 2},
        10: {"Business Analyst": 5,  "Financial Analyst": 5,  "Marketing Manager": 2,  "Operations Manager": 10, "Human Resources Manager": 2,  "Project Manager": 10, "Sales Manager": 2,  "Management Consultant": 2},
        11: {"Business Analyst": 2,  "Financial Analyst": 2,  "Marketing Manager": 5,  "Operations Manager": 2,  "Human Resources Manager": 5,  "Project Manager": 2,  "Sales Manager": 10, "Management Consultant": 10},
        12: {"Business Analyst": 10, "Financial Analyst": 10, "Marketing Manager": 5,  "Operations Manager": 2,  "Human Resources Manager": 2,  "Project Manager": 5,  "Sales Manager": 2,  "Management Consultant": 2},
        13: {"Business Analyst": 5,  "Financial Analyst": 5,  "Marketing Manager": 2,  "Operations Manager": 2,  "Human Resources Manager": 10, "Project Manager": 10, "Sales Manager": 2,  "Management Consultant": 2},
        14: {"Business Analyst": 2,  "Financial Analyst": 5,  "Marketing Manager": 2,  "Operations Manager": 2,  "Human Resources Manager": 5,  "Project Manager": 2,  "Sales Manager": 10, "Management Consultant": 10},
        15: {"Business Analyst": 2,  "Financial Analyst": 5,  "Marketing Manager": 10, "Operations Manager": 2,  "Human Resources Manager": 10, "Project Manager": 2,  "Sales Manager": 5,  "Management Consultant": 2},
        16: {"Business Analyst": 2,  "Financial Analyst": 2,  "Marketing Manager": 5,  "Operations Manager": 10, "Human Resources Manager": 10, "Project Manager": 2,  "Sales Manager": 5,  "Management Consultant": 2},
    },
    "healthcare": {
        1:  {"Registered Nurse": 10, "Medical Laboratory Technologist": 2,  "Physical Therapist": 5,  "Pharmacist": 2,  "Radiologic Technologist": 2,  "Respiratory Therapist": 5,  "Public Health Specialist": 2,  "Occupational Therapist": 10},
        2:  {"Registered Nurse": 2,  "Medical Laboratory Technologist": 10, "Physical Therapist": 2,  "Pharmacist": 10, "Radiologic Technologist": 5,  "Respiratory Therapist": 2,  "Public Health Specialist": 5,  "Occupational Therapist": 2},
        3:  {"Registered Nurse": 5,  "Medical Laboratory Technologist": 2,  "Physical Therapist": 10, "Pharmacist": 2,  "Radiologic Technologist": 2,  "Respiratory Therapist": 2,  "Public Health Specialist": 10, "Occupational Therapist": 5},
        4:  {"Registered Nurse": 10, "Medical Laboratory Technologist": 2,  "Physical Therapist": 2,  "Pharmacist": 5,  "Radiologic Technologist": 5,  "Respiratory Therapist": 10, "Public Health Specialist": 2,  "Occupational Therapist": 2},
        5:  {"Registered Nurse": 2,  "Medical Laboratory Technologist": 2,  "Physical Therapist": 10, "Pharmacist": 5,  "Radiologic Technologist": 2,  "Respiratory Therapist": 5,  "Public Health Specialist": 2,  "Occupational Therapist": 10},
        6:  {"Registered Nurse": 2,  "Medical Laboratory Technologist": 10, "Physical Therapist": 2,  "Pharmacist": 5,  "Radiologic Technologist": 10, "Respiratory Therapist": 5,  "Public Health Specialist": 2,  "Occupational Therapist": 2},
        7:  {"Registered Nurse": 5,  "Medical Laboratory Technologist": 5,  "Physical Therapist": 2,  "Pharmacist": 10, "Radiologic Technologist": 2,  "Respiratory Therapist": 2,  "Public Health Specialist": 10, "Occupational Therapist": 2},
        8:  {"Registered Nurse": 2,  "Medical Laboratory Technologist": 5,  "Physical Therapist": 2,  "Pharmacist": 2,  "Radiologic Technologist": 10, "Respiratory Therapist": 10, "Public Health Specialist": 5,  "Occupational Therapist": 2},
        9:  {"Registered Nurse": 10, "Medical Laboratory Technologist": 2,  "Physical Therapist": 5,  "Pharmacist": 2,  "Radiologic Technologist": 2,  "Respiratory Therapist": 10, "Public Health Specialist": 2,  "Occupational Therapist": 5},
        10: {"Registered Nurse": 10, "Medical Laboratory Technologist": 5,  "Physical Therapist": 2,  "Pharmacist": 10, "Radiologic Technologist": 2,  "Respiratory Therapist": 5,  "Public Health Specialist": 2,  "Occupational Therapist": 2},
        11: {"Registered Nurse": 5,  "Medical Laboratory Technologist": 2,  "Physical Therapist": 5,  "Pharmacist": 2,  "Radiologic Technologist": 2,  "Respiratory Therapist": 2,  "Public Health Specialist": 10, "Occupational Therapist": 10},
        12: {"Registered Nurse": 5,  "Medical Laboratory Technologist": 2,  "Physical Therapist": 10, "Pharmacist": 2,  "Radiologic Technologist": 2,  "Respiratory Therapist": 2,  "Public Health Specialist": 5,  "Occupational Therapist": 10},
        13: {"Registered Nurse": 2,  "Medical Laboratory Technologist": 10, "Physical Therapist": 2,  "Pharmacist": 5,  "Radiologic Technologist": 10, "Respiratory Therapist": 2,  "Public Health Specialist": 2,  "Occupational Therapist": 5},
        14: {"Registered Nurse": 2,  "Medical Laboratory Technologist": 2,  "Physical Therapist": 2,  "Pharmacist": 10, "Radiologic Technologist": 5,  "Respiratory Therapist": 10, "Public Health Specialist": 2,  "Occupational Therapist": 5},
        15: {"Registered Nurse": 2,  "Medical Laboratory Technologist": 10, "Physical Therapist": 5,  "Pharmacist": 2,  "Radiologic Technologist": 5,  "Respiratory Therapist": 2,  "Public Health Specialist": 10, "Occupational Therapist": 2},
        16: {"Registered Nurse": 2,  "Medical Laboratory Technologist": 5,  "Physical Therapist": 10, "Pharmacist": 2,  "Radiologic Technologist": 10, "Respiratory Therapist": 2,  "Public Health Specialist": 5,  "Occupational Therapist": 2},
    },
    "technology": {
        1:  {"Software Developer": 10, "Data Scientist": 2,  "Cybersecurity Analyst": 2,  "Network Administrator": 2,  "UX/UI Designer": 10, "AI/ML Engineer": 5,  "Database Administrator": 5,  "IT Project Manager": 2},
        2:  {"Software Developer": 5,  "Data Scientist": 10, "Cybersecurity Analyst": 2,  "Network Administrator": 2,  "UX/UI Designer": 2,  "AI/ML Engineer": 10, "Database Administrator": 5,  "IT Project Manager": 2},
        3:  {"Software Developer": 10, "Data Scientist": 2,  "Cybersecurity Analyst": 10, "Network Administrator": 5,  "UX/UI Designer": 2,  "AI/ML Engineer": 5,  "Database Administrator": 2,  "IT Project Manager": 2},
        4:  {"Software Developer": 2,  "Data Scientist": 2,  "Cybersecurity Analyst": 10, "Network Administrator": 10, "UX/UI Designer": 2,  "AI/ML Engineer": 2,  "Database Administrator": 5,  "IT Project Manager": 5},
        5:  {"Software Developer": 2,  "Data Scientist": 2,  "Cybersecurity Analyst": 5,  "Network Administrator": 10, "UX/UI Designer": 2,  "AI/ML Engineer": 2,  "Database Administrator": 10, "IT Project Manager": 5},
        6:  {"Software Developer": 5,  "Data Scientist": 2,  "Cybersecurity Analyst": 2,  "Network Administrator": 2,  "UX/UI Designer": 10, "AI/ML Engineer": 10, "Database Administrator": 2,  "IT Project Manager": 5},
        7:  {"Software Developer": 5,  "Data Scientist": 10, "Cybersecurity Analyst": 5,  "Network Administrator": 2,  "UX/UI Designer": 2,  "AI/ML Engineer": 10, "Database Administrator": 2,  "IT Project Manager": 2},
        8:  {"Software Developer": 2,  "Data Scientist": 10, "Cybersecurity Analyst": 2,  "Network Administrator": 5,  "UX/UI Designer": 2,  "AI/ML Engineer": 5,  "Database Administrator": 10, "IT Project Manager": 2},
        9:  {"Software Developer": 10, "Data Scientist": 2,  "Cybersecurity Analyst": 5,  "Network Administrator": 2,  "UX/UI Designer": 5,  "AI/ML Engineer": 2,  "Database Administrator": 2,  "IT Project Manager": 10},
        10: {"Software Developer": 5,  "Data Scientist": 2,  "Cybersecurity Analyst": 2,  "Network Administrator": 10, "UX/UI Designer": 5,  "AI/ML Engineer": 2,  "Database Administrator": 2,  "IT Project Manager": 10},
        11: {"Software Developer": 10, "Data Scientist": 5,  "Cybersecurity Analyst": 10, "Network Administrator": 2,  "UX/UI Designer": 2,  "AI/ML Engineer": 5,  "Database Administrator": 2,  "IT Project Manager": 2},
        12: {"Software Developer": 2,  "Data Scientist": 5,  "Cybersecurity Analyst": 2,  "Network Administrator": 5,  "UX/UI Designer": 2,  "AI/ML Engineer": 10, "Database Administrator": 10, "IT Project Manager": 2},
        13: {"Software Developer": 2,  "Data Scientist": 2,  "Cybersecurity Analyst": 5,  "Network Administrator": 10, "UX/UI Designer": 5,  "AI/ML Engineer": 2,  "Database Administrator": 2,  "IT Project Manager": 10},
        14: {"Software Developer": 2,  "Data Scientist": 10, "Cybersecurity Analyst": 2,  "Network Administrator": 2,  "UX/UI Designer": 10, "AI/ML Engineer": 2,  "Database Administrator": 5,  "IT Project Manager": 5},
        15: {"Software Developer": 2,  "Data Scientist": 5,  "Cybersecurity Analyst": 2,  "Network Administrator": 5,  "UX/UI Designer": 10, "AI/ML Engineer": 2,  "Database Administrator": 2,  "IT Project Manager": 10},
        16: {"Software Developer": 2,  "Data Scientist": 5,  "Cybersecurity Analyst": 10, "Network Administrator": 2,  "UX/UI Designer": 5,  "AI/ML Engineer": 2,  "Database Administrator": 10, "IT Project Manager": 2},
    },
    "creative": {
        1:  {"Graphic Designer": 10, "Content Writer": 2,  "Digital Marketing Specialist": 2,  "Art Director": 10, "Photographer": 5,  "Social Media Manager": 2,  "Creative Director": 5,  "Brand Manager": 2},
        2:  {"Graphic Designer": 2,  "Content Writer": 10, "Digital Marketing Specialist": 5,  "Art Director": 2,  "Photographer": 2,  "Social Media Manager": 10, "Creative Director": 2,  "Brand Manager": 5},
        3:  {"Graphic Designer": 5,  "Content Writer": 2,  "Digital Marketing Specialist": 10, "Art Director": 2,  "Photographer": 10, "Social Media Manager": 5,  "Creative Director": 2,  "Brand Manager": 2},
        4:  {"Graphic Designer": 2,  "Content Writer": 2,  "Digital Marketing Specialist": 10, "Art Director": 2,  "Photographer": 2,  "Social Media Manager": 5,  "Creative Director": 5,  "Brand Manager": 10},
        5:  {"Graphic Designer": 10, "Content Writer": 2,  "Digital Marketing Specialist": 2,  "Art Director": 5,  "Photographer": 10, "Social Media Manager": 2,  "Creative Director": 5,  "Brand Manager": 2},
        6:  {"Graphic Designer": 2,  "Content Writer": 5,  "Digital Marketing Specialist": 2,  "Art Director": 10, "Photographer": 2,  "Social Media Manager": 2,  "Creative Director": 10, "Brand Manager": 5},
        7:  {"Graphic Designer": 5,  "Content Writer": 10, "Digital Marketing Specialist": 2,  "Art Director": 5,  "Photographer": 10, "Social Media Manager": 2,  "Creative Director": 2,  "Brand Manager": 2},
        8:  {"Graphic Designer": 2,  "Content Writer": 2,  "Digital Marketing Specialist": 5,  "Art Director": 5,  "Photographer": 2,  "Social Media Manager": 2,  "Creative Director": 10, "Brand Manager": 10},
        9:  {"Graphic Designer": 5,  "Content Writer": 2,  "Digital Marketing Specialist": 2,  "Art Director": 10, "Photographer": 2,  "Social Media Manager": 5,  "Creative Director": 10, "Brand Manager": 2},
        10: {"Graphic Designer": 10, "Content Writer": 2,  "Digital Marketing Specialist": 10, "Art Director": 2,  "Photographer": 5,  "Social Media Manager": 2,  "Creative Director": 2,  "Brand Manager": 5},
        11: {"Graphic Designer": 2,  "Content Writer": 10, "Digital Marketing Specialist": 2,  "Art Director": 2,  "Photographer": 5,  "Social Media Manager": 10, "Creative Director": 5,  "Brand Manager": 2},
        12: {"Graphic Designer": 5,  "Content Writer": 5,  "Digital Marketing Specialist": 2,  "Art Director": 2,  "Photographer": 2,  "Social Media Manager": 2,  "Creative Director": 10, "Brand Manager": 10},
        13: {"Graphic Designer": 2,  "Content Writer": 5,  "Digital Marketing Specialist": 2,  "Art Director": 10, "Photographer": 2,  "Social Media Manager": 10, "Creative Director": 2,  "Brand Manager": 5},
        14: {"Graphic Designer": 2,  "Content Writer": 2,  "Digital Marketing Specialist": 10, "Art Director": 5,  "Photographer": 5,  "Social Media Manager": 10, "Creative Director": 2,  "Brand Manager": 2},
        15: {"Graphic Designer": 10, "Content Writer": 5,  "Digital Marketing Specialist": 5,  "Art Director": 2,  "Photographer": 10, "Social Media Manager": 2,  "Creative Director": 2,  "Brand Manager": 2},
        16: {"Graphic Designer": 2,  "Content Writer": 10, "Digital Marketing Specialist": 5,  "Art Director": 2,  "Photographer": 2,  "Social Media Manager": 5,  "Creative Director": 2,  "Brand Manager": 10},
    },
    "education": {
        1:  {"Elementary School Teacher": 2,  "High School English Teacher": 2,  "High School Math Teacher": 2,  "High School Science Teacher": 10, "Preschool Teacher": 5,  "Special Education Teacher": 5,  "Educational Coordinator": 10, "Curriculum Developer": 2},
        2:  {"Elementary School Teacher": 10, "High School English Teacher": 5,  "High School Math Teacher": 2,  "High School Science Teacher": 2,  "Preschool Teacher": 10, "Special Education Teacher": 2,  "Educational Coordinator": 2,  "Curriculum Developer": 5},
        3:  {"Elementary School Teacher": 10, "High School English Teacher": 10, "High School Math Teacher": 5,  "High School Science Teacher": 2,  "Preschool Teacher": 2,  "Special Education Teacher": 5,  "Educational Coordinator": 2,  "Curriculum Developer": 2},
        4:  {"Elementary School Teacher": 2,  "High School English Teacher": 2,  "High School Math Teacher": 10, "High School Science Teacher": 10, "Preschool Teacher": 2,  "Special Education Teacher": 2,  "Educational Coordinator": 5,  "Curriculum Developer": 5},
        5:  {"Elementary School Teacher": 2,  "High School English Teacher": 2,  "High School Math Teacher": 2,  "High School Science Teacher": 5,  "Preschool Teacher": 5,  "Special Education Teacher": 2,  "Educational Coordinator": 10, "Curriculum Developer": 10},
        6:  {"Elementary School Teacher": 5,  "High School English Teacher": 2,  "High School Math Teacher": 2,  "High School Science Teacher": 2,  "Preschool Teacher": 10, "Special Education Teacher": 10, "Educational Coordinator": 2,  "Curriculum Developer": 5},
        7:  {"Elementary School Teacher": 2,  "High School English Teacher": 10, "High School Math Teacher": 5,  "High School Science Teacher": 2,  "Preschool Teacher": 5,  "Special Education Teacher": 10, "Educational Coordinator": 2,  "Curriculum Developer": 2},
        8:  {"Elementary School Teacher": 5,  "High School English Teacher": 2,  "High School Math Teacher": 10, "High School Science Teacher": 2,  "Preschool Teacher": 2,  "Special Education Teacher": 2,  "Educational Coordinator": 5,  "Curriculum Developer": 10},
        9:  {"Elementary School Teacher": 2,  "High School English Teacher": 10, "High School Math Teacher": 5,  "High School Science Teacher": 2,  "Preschool Teacher": 5,  "Special Education Teacher": 2,  "Educational Coordinator": 10, "Curriculum Developer": 2},
        10: {"Elementary School Teacher": 2,  "High School English Teacher": 2,  "High School Math Teacher": 2,  "High School Science Teacher": 10, "Preschool Teacher": 2,  "Special Education Teacher": 5,  "Educational Coordinator": 5,  "Curriculum Developer": 10},
        11: {"Elementary School Teacher": 10, "High School English Teacher": 5,  "High School Math Teacher": 2,  "High School Science Teacher": 2,  "Preschool Teacher": 2,  "Special Education Teacher": 10, "Educational Coordinator": 2,  "Curriculum Developer": 5},
        12: {"Elementary School Teacher": 5,  "High School English Teacher": 2,  "High School Math Teacher": 10, "High School Science Teacher": 5,  "Preschool Teacher": 10, "Special Education Teacher": 2,  "Educational Coordinator": 2,  "Curriculum Developer": 2},
        13: {"Elementary School Teacher": 2,  "High School English Teacher": 5,  "High School Math Teacher": 2,  "High School Science Teacher": 5,  "Preschool Teacher": 2,  "Special Education Teacher": 2,  "Educational Coordinator": 10, "Curriculum Developer": 10},
        14: {"Elementary School Teacher": 5,  "High School English Teacher": 10, "High School Math Teacher": 10, "High School Science Teacher": 2,  "Preschool Teacher": 2,  "Special Education Teacher": 5,  "Educational Coordinator": 2,  "Curriculum Developer": 2},
        15: {"Elementary School Teacher": 2,  "High School English Teacher": 2,  "High School Math Teacher": 2,  "High School Science Teacher": 5,  "Preschool Teacher": 10, "Special Education Teacher": 10, "Educational Coordinator": 5,  "Curriculum Developer": 2},
        16: {"Elementary School Teacher": 10, "High School English Teacher": 5,  "High School Math Teacher": 5,  "High School Science Teacher": 10, "Preschool Teacher": 2,  "Special Education Teacher": 2,  "Educational Coordinator": 2,  "Curriculum Developer": 2},
    },
    "engineering": {
        1:  {"Civil Engineer": 2,  "Mechanical Engineer": 10, "Electrical Engineer": 10, "Software Engineer": 5,  "Chemical Engineer": 2,  "Environmental Engineer": 2,  "Industrial Engineer": 5,  "Biomedical Engineer": 2},
        2:  {"Civil Engineer": 2,  "Mechanical Engineer": 5,  "Electrical Engineer": 5,  "Software Engineer": 10, "Chemical Engineer": 10, "Environmental Engineer": 2,  "Industrial Engineer": 2,  "Biomedical Engineer": 2},
        3:  {"Civil Engineer": 10, "Mechanical Engineer": 10, "Electrical Engineer": 2,  "Software Engineer": 2,  "Chemical Engineer": 2,  "Environmental Engineer": 5,  "Industrial Engineer": 5,  "Biomedical Engineer": 2},
        4:  {"Civil Engineer": 2,  "Mechanical Engineer": 2,  "Electrical Engineer": 10, "Software Engineer": 5,  "Chemical Engineer": 2,  "Environmental Engineer": 2,  "Industrial Engineer": 5,  "Biomedical Engineer": 10},
        5:  {"Civil Engineer": 10, "Mechanical Engineer": 5,  "Electrical Engineer": 2,  "Software Engineer": 2,  "Chemical Engineer": 2,  "Environmental Engineer": 2,  "Industrial Engineer": 10, "Biomedical Engineer": 5},
        6:  {"Civil Engineer": 2,  "Mechanical Engineer": 2,  "Electrical Engineer": 2,  "Software Engineer": 2,  "Chemical Engineer": 10, "Environmental Engineer": 10, "Industrial Engineer": 5,  "Biomedical Engineer": 5},
        7:  {"Civil Engineer": 5,  "Mechanical Engineer": 2,  "Electrical Engineer": 2,  "Software Engineer": 10, "Chemical Engineer": 5,  "Environmental Engineer": 2,  "Industrial Engineer": 10, "Biomedical Engineer": 2},
        8:  {"Civil Engineer": 10, "Mechanical Engineer": 2,  "Electrical Engineer": 2,  "Software Engineer": 2,  "Chemical Engineer": 5,  "Environmental Engineer": 10, "Industrial Engineer": 2,  "Biomedical Engineer": 5},
        9:  {"Civil Engineer": 2,  "Mechanical Engineer": 5,  "Electrical Engineer": 5,  "Software Engineer": 2,  "Chemical Engineer": 2,  "Environmental Engineer": 2,  "Industrial Engineer": 10, "Biomedical Engineer": 10},
        10: {"Civil Engineer": 5,  "Mechanical Engineer": 2,  "Electrical Engineer": 10, "Software Engineer": 10, "Chemical Engineer": 2,  "Environmental Engineer": 5,  "Industrial Engineer": 2,  "Biomedical Engineer": 2},
        11: {"Civil Engineer": 5,  "Mechanical Engineer": 10, "Electrical Engineer": 2,  "Software Engineer": 2,  "Chemical Engineer": 10, "Environmental Engineer": 5,  "Industrial Engineer": 2,  "Biomedical Engineer": 2},
        12: {"Civil Engineer": 2,  "Mechanical Engineer": 2,  "Electrical Engineer": 5,  "Software Engineer": 5,  "Chemical Engineer": 2,  "Environmental Engineer": 10, "Industrial Engineer": 2,  "Biomedical Engineer": 10},
        13: {"Civil Engineer": 5,  "Mechanical Engineer": 2,  "Electrical Engineer": 2,  "Software Engineer": 2,  "Chemical Engineer": 5,  "Environmental Engineer": 10, "Industrial Engineer": 2,  "Biomedical Engineer": 10},
        14: {"Civil Engineer": 10, "Mechanical Engineer": 5,  "Electrical Engineer": 5,  "Software Engineer": 10, "Chemical Engineer": 2,  "Environmental Engineer": 2,  "Industrial Engineer": 2,  "Biomedical Engineer": 2},
        15: {"Civil Engineer": 2,  "Mechanical Engineer": 10, "Electrical Engineer": 2,  "Software Engineer": 2,  "Chemical Engineer": 5,  "Environmental Engineer": 2,  "Industrial Engineer": 10, "Biomedical Engineer": 5},
        16: {"Civil Engineer": 2,  "Mechanical Engineer": 2,  "Electrical Engineer": 10, "Software Engineer": 5,  "Chemical Engineer": 10, "Environmental Engineer": 5,  "Industrial Engineer": 2,  "Biomedical Engineer": 2},
    },
    "law": {
        1:  {"Corporate Lawyer": 2,  "Criminal Defense Attorney": 2,  "Public Defender": 2,  "Legal Researcher": 10, "Paralegal": 10, "Compliance Officer": 5,  "Judge": 2,  "Legal Consultant": 5},
        2:  {"Corporate Lawyer": 10, "Criminal Defense Attorney": 2,  "Public Defender": 2,  "Legal Researcher": 5,  "Paralegal": 10, "Compliance Officer": 2,  "Judge": 2,  "Legal Consultant": 5},
        3:  {"Corporate Lawyer": 2,  "Criminal Defense Attorney": 10, "Public Defender": 2,  "Legal Researcher": 2,  "Paralegal": 10, "Compliance Officer": 5,  "Judge": 5,  "Legal Consultant": 2},
        4:  {"Corporate Lawyer": 5,  "Criminal Defense Attorney": 2,  "Public Defender": 2,  "Legal Researcher": 2,  "Paralegal": 2,  "Compliance Officer": 10, "Judge": 10, "Legal Consultant": 5},
        5:  {"Corporate Lawyer": 5,  "Criminal Defense Attorney": 10, "Public Defender": 5,  "Legal Researcher": 2,  "Paralegal": 2,  "Compliance Officer": 2,  "Judge": 10, "Legal Consultant": 2},
        6:  {"Corporate Lawyer": 10, "Criminal Defense Attorney": 2,  "Public Defender": 2,  "Legal Researcher": 5,  "Paralegal": 5,  "Compliance Officer": 2,  "Judge": 2,  "Legal Consultant": 10},
        7:  {"Corporate Lawyer": 2,  "Criminal Defense Attorney": 5,  "Public Defender": 2,  "Legal Researcher": 10, "Paralegal": 2,  "Compliance Officer": 10, "Judge": 2,  "Legal Consultant": 5},
        8:  {"Corporate Lawyer": 10, "Criminal Defense Attorney": 5,  "Public Defender": 2,  "Legal Researcher": 2,  "Paralegal": 10, "Compliance Officer": 2,  "Judge": 5,  "Legal Consultant": 2},
        9:  {"Corporate Lawyer": 2,  "Criminal Defense Attorney": 5,  "Public Defender": 10, "Legal Researcher": 2,  "Paralegal": 2,  "Compliance Officer": 5,  "Judge": 2,  "Legal Consultant": 10},
        10: {"Corporate Lawyer": 2,  "Criminal Defense Attorney": 10, "Public Defender": 10, "Legal Researcher": 5,  "Paralegal": 5,  "Compliance Officer": 2,  "Judge": 2,  "Legal Consultant": 2},
        11: {"Corporate Lawyer": 10, "Criminal Defense Attorney": 2,  "Public Defender": 5,  "Legal Researcher": 2,  "Paralegal": 2,  "Compliance Officer": 5,  "Judge": 2,  "Legal Consultant": 10},
        12: {"Corporate Lawyer": 5,  "Criminal Defense Attorney": 2,  "Public Defender": 2,  "Legal Researcher": 10, "Paralegal": 5,  "Compliance Officer": 10, "Judge": 2,  "Legal Consultant": 2},
        13: {"Corporate Lawyer": 2,  "Criminal Defense Attorney": 2,  "Public Defender": 5,  "Legal Researcher": 5,  "Paralegal": 2,  "Compliance Officer": 2,  "Judge": 10, "Legal Consultant": 10},
        14: {"Corporate Lawyer": 5,  "Criminal Defense Attorney": 10, "Public Defender": 10, "Legal Researcher": 2,  "Paralegal": 2,  "Compliance Officer": 2,  "Judge": 5,  "Legal Consultant": 2},
        15: {"Corporate Lawyer": 2,  "Criminal Defense Attorney": 2,  "Public Defender": 10, "Legal Researcher": 10, "Paralegal": 5,  "Compliance Officer": 2,  "Judge": 5,  "Legal Consultant": 2},
        16: {"Corporate Lawyer": 2,  "Criminal Defense Attorney": 5,  "Public Defender": 5,  "Legal Researcher": 2,  "Paralegal": 2,  "Compliance Officer": 10, "Judge": 10, "Legal Consultant": 2},
    },
    "tourism": {
        1:  {"Travel Agent": 10, "Hotel Manager": 2,  "Tour Guide": 5,  "Event Planner": 2,  "Restaurant Manager": 2,  "Tourism Marketing Specialist": 5,  "Resort Operations Manager": 2,  "Travel Consultant": 10},
        2:  {"Travel Agent": 2,  "Hotel Manager": 10, "Tour Guide": 5,  "Event Planner": 2,  "Restaurant Manager": 10, "Tourism Marketing Specialist": 5,  "Resort Operations Manager": 2,  "Travel Consultant": 2},
        3:  {"Travel Agent": 5,  "Hotel Manager": 2,  "Tour Guide": 10, "Event Planner": 2,  "Restaurant Manager": 2,  "Tourism Marketing Specialist": 2,  "Resort Operations Manager": 5,  "Travel Consultant": 10},
        4:  {"Travel Agent": 4,  "Hotel Manager": 10, "Tour Guide": 2,  "Event Planner": 5,  "Restaurant Manager": 5,  "Tourism Marketing Specialist": 2,  "Resort Operations Manager": 10, "Travel Consultant": 2},
        5:  {"Travel Agent": 2,  "Hotel Manager": 5,  "Tour Guide": 2,  "Event Planner": 10, "Restaurant Manager": 10, "Tourism Marketing Specialist": 2,  "Resort Operations Manager": 5,  "Travel Consultant": 2},
        6:  {"Travel Agent": 2,  "Hotel Manager": 2,  "Tour Guide": 10, "Event Planner": 5,  "Restaurant Manager": 5,  "Tourism Marketing Specialist": 2,  "Resort Operations Manager": 2,  "Travel Consultant": 10},
        7:  {"Travel Agent": 10, "Hotel Manager": 2,  "Tour Guide": 5,  "Event Planner": 2,  "Restaurant Manager": 2,  "Tourism Marketing Specialist": 10, "Resort Operations Manager": 2,  "Travel Consultant": 5},
        8:  {"Travel Agent": 2,  "Hotel Manager": 10, "Tour Guide": 2,  "Event Planner": 5,  "Restaurant Manager": 10, "Tourism Marketing Specialist": 5,  "Resort Operations Manager": 2,  "Travel Consultant": 2},
        9:  {"Travel Agent": 10, "Hotel Manager": 2,  "Tour Guide": 10, "Event Planner": 2,  "Restaurant Manager": 2,  "Tourism Marketing Specialist": 5,  "Resort Operations Manager": 5,  "Travel Consultant": 2},
        10: {"Travel Agent": 5,  "Hotel Manager": 2,  "Tour Guide": 10, "Event Planner": 2,  "Restaurant Manager": 2,  "Tourism Marketing Specialist": 2,  "Resort Operations Manager": 5,  "Travel Consultant": 10},
        11: {"Travel Agent": 10, "Hotel Manager": 5,  "Tour Guide": 2,  "Event Planner": 2,  "Restaurant Manager": 2,  "Tourism Marketing Specialist": 10, "Resort Operations Manager": 2,  "Travel Consultant": 5},
        12: {"Travel Agent": 2,  "Hotel Manager": 5,  "Tour Guide": 2,  "Event Planner": 10, "Restaurant Manager": 5,  "Tourism Marketing Specialist": 2,  "Resort Operations Manager": 10, "Travel Consultant": 2},
        13: {"Travel Agent": 5,  "Hotel Manager": 2,  "Tour Guide": 5,  "Event Planner": 2,  "Restaurant Manager": 2,  "Tourism Marketing Specialist": 10, "Resort Operations Manager": 10, "Travel Consultant": 2},
        14: {"Travel Agent": 2,  "Hotel Manager": 10, "Tour Guide": 2,  "Event Planner": 5,  "Restaurant Manager": 5,  "Tourism Marketing Specialist": 2,  "Resort Operations Manager": 10, "Travel Consultant": 2},
        15: {"Travel Agent": 5,  "Hotel Manager": 2,  "Tour Guide": 2,  "Event Planner": 10, "Restaurant Manager": 2,  "Tourism Marketing Specialist": 10, "Resort Operations Manager": 2,  "Travel Consultant": 5},
        16: {"Travel Agent": 2,  "Hotel Manager": 5,  "Tour Guide": 2,  "Event Planner": 10, "Restaurant Manager": 10, "Tourism Marketing Specialist": 2,  "Resort Operations Manager": 2,  "Travel Consultant": 5},
    },
}

DOMAIN_LABELS = {
    "business":    "Business",
    "healthcare":  "Healthcare",
    "technology":  "Technology",
    "creative":    "Creative",
    "education":   "Education",
    "engineering": "Engineering",
    "law":         "Law",
    "tourism":     "Tourism",
}

# ─────────────────────────────────────────────────────────────────────────────
# SCORING ENGINE
# ─────────────────────────────────────────────────────────────────────────────

def calculate_scores(responses: dict, category: str) -> dict:
    mapping = JOB_MAPPINGS[category]
    roles   = JOB_ROLES[category]

    raw = {role: 0.0 for role in roles}
    for q_id, weights in mapping.items():
        rating = responses.get(q_id, 0)
        for role in roles:
            raw[role] += rating * weights.get(role, 0)

    normalized = {}
    for role in roles:
        max_possible = sum(5 * weights.get(role, 0) for weights in mapping.values())
        normalized[role] = (raw[role] / max_possible * 100) if max_possible > 0 else 0.0

    return dict(sorted(normalized.items(), key=lambda x: x[1], reverse=True))

def build_ideal_profile(target_job: str, category: str) -> dict:
    mapping = JOB_MAPPINGS[category]
    return {
        q_id: (5 if weights.get(target_job, 0) == 10 else 1)
        for q_id, weights in mapping.items()
    }

def build_noisy_profile(target_job: str, category: str, noise_level: str) -> dict:
    mapping = JOB_MAPPINGS[category]
    profile = {}
    
    for q_id, weights in mapping.items():
        ww = weights.get(target_job, 0)
        
        if ww == 10:
            if noise_level == "Strong": val = random.randint(4, 5)
            elif noise_level == "Moderate": val = random.randint(3, 5)
            else: val = random.randint(3, 4)
        elif ww == 5:
            if noise_level == "Strong": val = random.randint(3, 4)
            elif noise_level == "Moderate": val = random.randint(2, 4)
            else: val = random.randint(2, 3)
        else:
            if noise_level == "Strong": val = random.randint(1, 2)
            elif noise_level == "Moderate": val = random.randint(1, 3)
            else: val = random.randint(2, 3)
            
        profile[q_id] = val
        
    return profile

# ─────────────────────────────────────────────────────────────────────────────
# MAIN RUNNER
# ─────────────────────────────────────────────────────────────────────────────

W  = 95

def run():
    random.seed(42)
    
    lines = [
        "=" * W,
        " PATHFINDER — JOB RECOMMENDATION ACCURACY & CONCORDANCE TEST".center(W),
        "=" * W,
    ]
    
    # ---------------------------------------------------------
    # PART 1: IDEAL PROFILE SIMULATION
    # ---------------------------------------------------------
    lines.extend([
        "\n" + "=" * W,
        " PART 1: LOGICAL CORRECTNESS (IDEAL PROFILE)".center(W),
        " Testing the Mathematical Limits of Content-Based Filtering".center(W),
        "=" * W,
    ])

    total_ideal_passed = 0
    total_ideal = sum(len(roles) for roles in JOB_ROLES.values())
    
    for category, roles in JOB_ROLES.items():
        for target_job in roles:
            profile = build_ideal_profile(target_job, category)
            scores  = calculate_scores(profile, category)
            top_job = list(scores.keys())[0]
            if top_job == target_job:
                total_ideal_passed += 1

    lines.extend([
        f"  Total Roles Tested  : {total_ideal}",
        f"  Passed Ideal Tests  : {total_ideal_passed}",
        f"  Logical Accuracy    : {(total_ideal_passed/total_ideal*100):.2f}%",
    ])

    # ---------------------------------------------------------
    # PART 2: NOISE SIMULATION vs MATRIX RULES
    # ---------------------------------------------------------
    lines.extend([
        "\n\n" + "=" * W,
        " PART 2: MATRIX CONCORDANCE TEST (REALISTIC NOISE)".center(W),
        " Testing System Recommendations vs. Matrix Ground Truth".center(W),
        "=" * W,
        f"\n  {'No.':<4} {'Matrix Expected (Target Role)':<30} {'Noise Level':<12} {'System Output (Top-1)':<30} {'Status'}",
        f"  {'─' * (W - 4)}"
    ])

    total_noisy = 0
    correct_top1 = 0
    test_idx = 1
    
    domain_stats = {d: {'total': 0, 'correct': 0} for d in JOB_ROLES.keys()}

    for category, roles in JOB_ROLES.items():
        # Generate 6 noisy tests per domain (to hit 48 total tests like MBTI)
        test_roles = roles[:6]
        
        for i, target_job in enumerate(test_roles):
            noise = ["Strong", "Moderate", "Weak"][i % 3]
            
            profile = build_noisy_profile(target_job, category, noise)
            scores = calculate_scores(profile, category)
            top_job = list(scores.keys())[0]
            
            passed = (top_job == target_job)
            status = "PASS" if passed else "FAIL"
            
            domain_stats[category]['total'] += 1
            total_noisy += 1
            if passed: 
                correct_top1 += 1
                domain_stats[category]['correct'] += 1
            
            lines.append(f"  {test_idx:<4} {target_job:<30} {noise:<12} {top_job:<30} {status}")
            test_idx += 1

    lines.extend([
        f"\n  {'─' * (W - 4)}",
        f"  OVERALL PERFORMANCE MEASURES",
        f"  {'─' * (W - 4)}",
        f"  Total Simulated Inputs : {total_noisy}",
        f"  Matches Matrix Rules   : {correct_top1}",
        f"  System Mismatches      : {total_noisy - correct_top1}",
        f"  Concordance Rate       : {(correct_top1/total_noisy*100):.2f}%",
        f"\n  PER-DOMAIN BREAKDOWN:"
    ])
    
    for category in JOB_ROLES.keys():
        s = domain_stats[category]
        acc = (s['correct'] / s['total'] * 100) if s['total'] > 0 else 0
        lines.append(f"    {DOMAIN_LABELS[category]:<15} : {s['correct']}/{s['total']} ({acc:.2f}%)")
        
    lines.append("=" * W)

    output = "\n".join(lines)
    print(output)
    with open(REPORT_PATH, "w", encoding="utf-8") as f:
        f.write(output + "\n")
    print(f"\n  Report saved → {REPORT_PATH}")

if __name__ == "__main__":
    run()
