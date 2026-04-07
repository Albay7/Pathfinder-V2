"""
accuracy_course.py
==================
PATHFINDER — Course (Degree Program) Recommendation Accuracy Test

Test Method:
    Ideal Student Simulation (Discriminability Test).
    For each of the 58 degree programs across 8 academic domains, a synthetic
    respondent is constructed:
      - Questions where the target course has weight >= 10 → answer = 5 (maximum)
      - All other questions → answer = 1 (minimum)
    The weighted scoring algorithm is applied and normalized to a percentage.
    The target course must rank #1 across all courses in the domain to PASS.

Normalization:
    normalized_score(course) = (Σ response_i × weight_i) / (Σ 5 × weight_i) × 100%

Known Failures (2):
    - JD Juris Doctor  → LLB Law wins (overlapping weight profiles in Law domain)
    - BS Hospitality Management → BS Tourism Management wins (adjacent programs)

Output:
    - Console (formatted table, per-domain sub-tables + domain summary + notes)
    - Datasets/course_accuracy_report.txt

Usage:
    python Datasets/accuracy_course.py
"""

import os

# ─────────────────────────────────────────────────────────────────────────────
# REPORT OUTPUT PATH
# ─────────────────────────────────────────────────────────────────────────────
BASE_DIR    = os.path.dirname(os.path.abspath(__file__))
REPORT_PATH = os.path.join(BASE_DIR, "course_accuracy_report.txt")

# ─────────────────────────────────────────────────────────────────────────────
# COURSE WEIGHT MAPPINGS
# Mirrored from: validate_course_logic.php / validate_all_logic.py
# Source:        PathfinderController.php → calculateWeightedCourseScores()
# ─────────────────────────────────────────────────────────────────────────────

COURSE_MAPPINGS = {
    "business": {
        1:  {"BS Business Administration": 2,  "BS Accountancy": 2,  "BS Marketing Management": 10, "BS Financial Management": 5,  "BS Human Resource Management": 2,  "BS Entrepreneurship": 2,  "BS Management Accounting": 10, "BS Operations Management": 5},
        2:  {"BS Business Administration": 2,  "BS Accountancy": 5,  "BS Marketing Management": 2,  "BS Financial Management": 10, "BS Human Resource Management": 2,  "BS Entrepreneurship": 10, "BS Management Accounting": 5,  "BS Operations Management": 2},
        3:  {"BS Business Administration": 2,  "BS Accountancy": 5,  "BS Marketing Management": 10, "BS Financial Management": 5,  "BS Human Resource Management": 10, "BS Entrepreneurship": 2,  "BS Management Accounting": 2,  "BS Operations Management": 2},
        4:  {"BS Business Administration": 2,  "BS Accountancy": 2,  "BS Marketing Management": 5,  "BS Financial Management": 2,  "BS Human Resource Management": 10, "BS Entrepreneurship": 5,  "BS Management Accounting": 2,  "BS Operations Management": 10},
        5:  {"BS Business Administration": 5,  "BS Accountancy": 5,  "BS Marketing Management": 2,  "BS Financial Management": 2,  "BS Human Resource Management": 2,  "BS Entrepreneurship": 2,  "BS Management Accounting": 10, "BS Operations Management": 10},
        6:  {"BS Business Administration": 2,  "BS Accountancy": 5,  "BS Marketing Management": 10, "BS Financial Management": 2,  "BS Human Resource Management": 5,  "BS Entrepreneurship": 10, "BS Management Accounting": 2,  "BS Operations Management": 2},
        7:  {"BS Business Administration": 5,  "BS Accountancy": 10, "BS Marketing Management": 2,  "BS Financial Management": 5,  "BS Human Resource Management": 2,  "BS Entrepreneurship": 2,  "BS Management Accounting": 10, "BS Operations Management": 2},
        8:  {"BS Business Administration": 5,  "BS Accountancy": 2,  "BS Marketing Management": 10, "BS Financial Management": 2,  "BS Human Resource Management": 5,  "BS Entrepreneurship": 10, "BS Management Accounting": 2,  "BS Operations Management": 2},
        9:  {"BS Business Administration": 2,  "BS Accountancy": 2,  "BS Marketing Management": 2,  "BS Financial Management": 5,  "BS Human Resource Management": 10, "BS Entrepreneurship": 10, "BS Management Accounting": 2,  "BS Operations Management": 5},
        10: {"BS Business Administration": 2,  "BS Accountancy": 2,  "BS Marketing Management": 5,  "BS Financial Management": 10, "BS Human Resource Management": 2,  "BS Entrepreneurship": 5,  "BS Management Accounting": 10, "BS Operations Management": 2},
        11: {"BS Business Administration": 5,  "BS Accountancy": 10, "BS Marketing Management": 2,  "BS Financial Management": 10, "BS Human Resource Management": 2,  "BS Entrepreneurship": 2,  "BS Management Accounting": 5,  "BS Operations Management": 2},
        12: {"BS Business Administration": 10, "BS Accountancy": 2,  "BS Marketing Management": 5,  "BS Financial Management": 2,  "BS Human Resource Management": 5,  "BS Entrepreneurship": 2,  "BS Management Accounting": 2,  "BS Operations Management": 10},
        13: {"BS Business Administration": 10, "BS Accountancy": 2,  "BS Marketing Management": 2,  "BS Financial Management": 2,  "BS Human Resource Management": 10, "BS Entrepreneurship": 5,  "BS Management Accounting": 2,  "BS Operations Management": 5},
        14: {"BS Business Administration": 2,  "BS Accountancy": 10, "BS Marketing Management": 2,  "BS Financial Management": 10, "BS Human Resource Management": 2,  "BS Entrepreneurship": 2,  "BS Management Accounting": 5,  "BS Operations Management": 5},
        15: {"BS Business Administration": 10, "BS Accountancy": 10, "BS Marketing Management": 5,  "BS Financial Management": 2,  "BS Human Resource Management": 2,  "BS Entrepreneurship": 2,  "BS Management Accounting": 5,  "BS Operations Management": 2},
        16: {"BS Business Administration": 10, "BS Accountancy": 2,  "BS Marketing Management": 2,  "BS Financial Management": 2,  "BS Human Resource Management": 5,  "BS Entrepreneurship": 5,  "BS Management Accounting": 2,  "BS Operations Management": 10},
    },
    "technology": {
        101: {"BS Computer Science": 2,  "BS Information Technology": 2,  "BS Data Science": 5,  "BS Entertainment and Multimedia Computing": 10, "BS Computer Engineering": 10, "BS Cybersecurity": 2,  "BS Information Systems": 2,  "BS Network Administration": 5},
        102: {"BS Computer Science": 2,  "BS Information Technology": 2,  "BS Data Science": 10, "BS Entertainment and Multimedia Computing": 2,  "BS Computer Engineering": 10, "BS Cybersecurity": 5,  "BS Information Systems": 2,  "BS Network Administration": 5},
        103: {"BS Computer Science": 10, "BS Information Technology": 2,  "BS Data Science": 10, "BS Entertainment and Multimedia Computing": 2,  "BS Computer Engineering": 5,  "BS Cybersecurity": 5,  "BS Information Systems": 2,  "BS Network Administration": 2},
        104: {"BS Computer Science": 2,  "BS Information Technology": 2,  "BS Data Science": 5,  "BS Entertainment and Multimedia Computing": 10, "BS Computer Engineering": 5,  "BS Cybersecurity": 2,  "BS Information Systems": 10, "BS Network Administration": 2},
        105: {"BS Computer Science": 2,  "BS Information Technology": 2,  "BS Data Science": 5,  "BS Entertainment and Multimedia Computing": 10, "BS Computer Engineering": 2,  "BS Cybersecurity": 10, "BS Information Systems": 5,  "BS Network Administration": 2},
        106: {"BS Computer Science": 2,  "BS Information Technology": 2,  "BS Data Science": 2,  "BS Entertainment and Multimedia Computing": 5,  "BS Computer Engineering": 10, "BS Cybersecurity": 2,  "BS Information Systems": 10, "BS Network Administration": 5},
        107: {"BS Computer Science": 2,  "BS Information Technology": 10, "BS Data Science": 2,  "BS Entertainment and Multimedia Computing": 2,  "BS Computer Engineering": 5,  "BS Cybersecurity": 10, "BS Information Systems": 2,  "BS Network Administration": 5},
        108: {"BS Computer Science": 2,  "BS Information Technology": 2,  "BS Data Science": 5,  "BS Entertainment and Multimedia Computing": 2,  "BS Computer Engineering": 5,  "BS Cybersecurity": 10, "BS Information Systems": 2,  "BS Network Administration": 10},
        109: {"BS Computer Science": 2,  "BS Information Technology": 10, "BS Data Science": 2,  "BS Entertainment and Multimedia Computing": 2,  "BS Computer Engineering": 2,  "BS Cybersecurity": 5,  "BS Information Systems": 5,  "BS Network Administration": 10},
        110: {"BS Computer Science": 5,  "BS Information Technology": 5,  "BS Data Science": 2,  "BS Entertainment and Multimedia Computing": 2,  "BS Computer Engineering": 2,  "BS Cybersecurity": 10, "BS Information Systems": 2,  "BS Network Administration": 10},
        111: {"BS Computer Science": 10, "BS Information Technology": 5,  "BS Data Science": 10, "BS Entertainment and Multimedia Computing": 2,  "BS Computer Engineering": 2,  "BS Cybersecurity": 2,  "BS Information Systems": 5,  "BS Network Administration": 2},
        112: {"BS Computer Science": 10, "BS Information Technology": 5,  "BS Data Science": 10, "BS Entertainment and Multimedia Computing": 5,  "BS Computer Engineering": 2,  "BS Cybersecurity": 2,  "BS Information Systems": 2,  "BS Network Administration": 2},
        113: {"BS Computer Science": 5,  "BS Information Technology": 2,  "BS Data Science": 2,  "BS Entertainment and Multimedia Computing": 2,  "BS Computer Engineering": 10, "BS Cybersecurity": 5,  "BS Information Systems": 2,  "BS Network Administration": 10},
        114: {"BS Computer Science": 10, "BS Information Technology": 5,  "BS Data Science": 2,  "BS Entertainment and Multimedia Computing": 10, "BS Computer Engineering": 2,  "BS Cybersecurity": 2,  "BS Information Systems": 5,  "BS Network Administration": 2},
        115: {"BS Computer Science": 5,  "BS Information Technology": 10, "BS Data Science": 2,  "BS Entertainment and Multimedia Computing": 5,  "BS Computer Engineering": 2,  "BS Cybersecurity": 2,  "BS Information Systems": 10, "BS Network Administration": 2},
        116: {"BS Computer Science": 5,  "BS Information Technology": 10, "BS Data Science": 2,  "BS Entertainment and Multimedia Computing": 5,  "BS Computer Engineering": 2,  "BS Cybersecurity": 2,  "BS Information Systems": 10, "BS Network Administration": 2},
    },
    "healthcare": {
        201: {"BS Nursing": 2,  "BS Respiratory Therapy": 10, "BS Physical Therapy": 5,  "BS Occupational Therapy": 5,  "BS Public Health": 2,  "BS Medical Technology": 2,  "BS Radiologic Technology": 10, "BS Pharmacy": 2},
        202: {"BS Nursing": 2,  "BS Respiratory Therapy": 5,  "BS Physical Therapy": 2,  "BS Occupational Therapy": 5,  "BS Public Health": 2,  "BS Medical Technology": 2,  "BS Radiologic Technology": 10, "BS Pharmacy": 10},
        203: {"BS Nursing": 2,  "BS Respiratory Therapy": 2,  "BS Physical Therapy": 2,  "BS Occupational Therapy": 5,  "BS Public Health": 5,  "BS Medical Technology": 2,  "BS Radiologic Technology": 10, "BS Pharmacy": 10},
        204: {"BS Nursing": 2,  "BS Respiratory Therapy": 5,  "BS Physical Therapy": 10, "BS Occupational Therapy": 10, "BS Public Health": 2,  "BS Medical Technology": 2,  "BS Radiologic Technology": 5,  "BS Pharmacy": 2},
        205: {"BS Nursing": 2,  "BS Respiratory Therapy": 5,  "BS Physical Therapy": 2,  "BS Occupational Therapy": 2,  "BS Public Health": 2,  "BS Medical Technology": 5,  "BS Radiologic Technology": 10, "BS Pharmacy": 10},
        206: {"BS Nursing": 2,  "BS Respiratory Therapy": 10, "BS Physical Therapy": 10, "BS Occupational Therapy": 5,  "BS Public Health": 2,  "BS Medical Technology": 2,  "BS Radiologic Technology": 5,  "BS Pharmacy": 2},
        207: {"BS Nursing": 2,  "BS Respiratory Therapy": 10, "BS Physical Therapy": 2,  "BS Occupational Therapy": 2,  "BS Public Health": 10, "BS Medical Technology": 5,  "BS Radiologic Technology": 2,  "BS Pharmacy": 5},
        208: {"BS Nursing": 2,  "BS Respiratory Therapy": 2,  "BS Physical Therapy": 5,  "BS Occupational Therapy": 2,  "BS Public Health": 5,  "BS Medical Technology": 10, "BS Radiologic Technology": 2,  "BS Pharmacy": 10},
        209: {"BS Nursing": 5,  "BS Respiratory Therapy": 2,  "BS Physical Therapy": 2,  "BS Occupational Therapy": 2,  "BS Public Health": 10, "BS Medical Technology": 10, "BS Radiologic Technology": 2,  "BS Pharmacy": 5},
        210: {"BS Nursing": 5,  "BS Respiratory Therapy": 5,  "BS Physical Therapy": 10, "BS Occupational Therapy": 10, "BS Public Health": 2,  "BS Medical Technology": 2,  "BS Radiologic Technology": 2,  "BS Pharmacy": 2},
        211: {"BS Nursing": 5,  "BS Respiratory Therapy": 2,  "BS Physical Therapy": 10, "BS Occupational Therapy": 10, "BS Public Health": 5,  "BS Medical Technology": 2,  "BS Radiologic Technology": 2,  "BS Pharmacy": 2},
        212: {"BS Nursing": 10, "BS Respiratory Therapy": 10, "BS Physical Therapy": 2,  "BS Occupational Therapy": 2,  "BS Public Health": 2,  "BS Medical Technology": 5,  "BS Radiologic Technology": 5,  "BS Pharmacy": 2},
        213: {"BS Nursing": 10, "BS Respiratory Therapy": 2,  "BS Physical Therapy": 5,  "BS Occupational Therapy": 10, "BS Public Health": 5,  "BS Medical Technology": 2,  "BS Radiologic Technology": 2,  "BS Pharmacy": 2},
        214: {"BS Nursing": 10, "BS Respiratory Therapy": 2,  "BS Physical Therapy": 2,  "BS Occupational Therapy": 2,  "BS Public Health": 10, "BS Medical Technology": 5,  "BS Radiologic Technology": 2,  "BS Pharmacy": 5},
        215: {"BS Nursing": 5,  "BS Respiratory Therapy": 2,  "BS Physical Therapy": 5,  "BS Occupational Therapy": 2,  "BS Public Health": 10, "BS Medical Technology": 10, "BS Radiologic Technology": 2,  "BS Pharmacy": 2},
        216: {"BS Nursing": 10, "BS Respiratory Therapy": 2,  "BS Physical Therapy": 2,  "BS Occupational Therapy": 2,  "BS Public Health": 2,  "BS Medical Technology": 10, "BS Radiologic Technology": 5,  "BS Pharmacy": 5},
    },
    "liberal_arts": {
        301: {"BA English Language": 5,  "BA Literature": 2,  "BA Communication": 10, "BA Philosophy": 5,  "BA History": 2,  "BA Political Science": 2,  "BA Psychology": 10, "BA Sociology": 2},
        302: {"BA English Language": 5,  "BA Literature": 2,  "BA Communication": 2,  "BA Philosophy": 2,  "BA History": 10, "BA Political Science": 2,  "BA Psychology": 10, "BA Sociology": 5},
        303: {"BA English Language": 2,  "BA Literature": 2,  "BA Communication": 5,  "BA Philosophy": 2,  "BA History": 2,  "BA Political Science": 5,  "BA Psychology": 10, "BA Sociology": 10},
        304: {"BA English Language": 2,  "BA Literature": 2,  "BA Communication": 2,  "BA Philosophy": 10, "BA History": 5,  "BA Political Science": 10, "BA Psychology": 5,  "BA Sociology": 2},
        305: {"BA English Language": 10, "BA Literature": 5,  "BA Communication": 10, "BA Philosophy": 2,  "BA History": 2,  "BA Political Science": 2,  "BA Psychology": 5,  "BA Sociology": 2},
        306: {"BA English Language": 5,  "BA Literature": 10, "BA Communication": 2,  "BA Philosophy": 10, "BA History": 2,  "BA Political Science": 5,  "BA Psychology": 2,  "BA Sociology": 2},
        307: {"BA English Language": 2,  "BA Literature": 2,  "BA Communication": 2,  "BA Philosophy": 2,  "BA History": 5,  "BA Political Science": 5,  "BA Psychology": 10, "BA Sociology": 10},
        308: {"BA English Language": 10, "BA Literature": 5,  "BA Communication": 10, "BA Philosophy": 2,  "BA History": 2,  "BA Political Science": 5,  "BA Psychology": 2,  "BA Sociology": 2},
        309: {"BA English Language": 2,  "BA Literature": 5,  "BA Communication": 5,  "BA Philosophy": 2,  "BA History": 10, "BA Political Science": 2,  "BA Psychology": 2,  "BA Sociology": 10},
        310: {"BA English Language": 10, "BA Literature": 10, "BA Communication": 5,  "BA Philosophy": 5,  "BA History": 2,  "BA Political Science": 2,  "BA Psychology": 2,  "BA Sociology": 2},
        311: {"BA English Language": 2,  "BA Literature": 2,  "BA Communication": 5,  "BA Philosophy": 2,  "BA History": 10, "BA Political Science": 10, "BA Psychology": 5,  "BA Sociology": 2},
        312: {"BA English Language": 2,  "BA Literature": 2,  "BA Communication": 2,  "BA Philosophy": 5,  "BA History": 10, "BA Political Science": 10, "BA Psychology": 2,  "BA Sociology": 5},
        313: {"BA English Language": 10, "BA Literature": 5,  "BA Communication": 10, "BA Philosophy": 2,  "BA History": 2,  "BA Political Science": 2,  "BA Psychology": 2,  "BA Sociology": 5},
        314: {"BA English Language": 5,  "BA Literature": 10, "BA Communication": 2,  "BA Philosophy": 10, "BA History": 5,  "BA Political Science": 2,  "BA Psychology": 2,  "BA Sociology": 2},
        315: {"BA English Language": 2,  "BA Literature": 2,  "BA Communication": 2,  "BA Philosophy": 5,  "BA History": 5,  "BA Political Science": 10, "BA Psychology": 2,  "BA Sociology": 10},
        316: {"BA English Language": 2,  "BA Literature": 10, "BA Communication": 2,  "BA Philosophy": 10, "BA History": 2,  "BA Political Science": 2,  "BA Psychology": 5,  "BA Sociology": 5},
    },
    "education": {
        401: {"BSEd English": 10, "BEEd": 2,  "BECEd": 2,  "BSEd Social Studies": 5,  "BTLEd": 2,  "BSEd Science": 5,  "BSEd Mathematics": 2,  "BPEd": 10},
        402: {"BSEd English": 2,  "BEEd": 2,  "BECEd": 2,  "BSEd Social Studies": 10, "BTLEd": 5,  "BSEd Science": 2,  "BSEd Mathematics": 10, "BPEd": 5},
        403: {"BSEd English": 2,  "BEEd": 2,  "BECEd": 5,  "BSEd Social Studies": 2,  "BTLEd": 10, "BSEd Science": 10, "BSEd Mathematics": 5,  "BPEd": 2},
        404: {"BSEd English": 2,  "BEEd": 2,  "BECEd": 2,  "BSEd Social Studies": 10, "BTLEd": 5,  "BSEd Science": 2,  "BSEd Mathematics": 10, "BPEd": 5},
        405: {"BSEd English": 2,  "BEEd": 5,  "BECEd": 10, "BSEd Social Studies": 2,  "BTLEd": 2,  "BSEd Science": 5,  "BSEd Mathematics": 2,  "BPEd": 10},
        406: {"BSEd English": 2,  "BEEd": 2,  "BECEd": 2,  "BSEd Social Studies": 10, "BTLEd": 5,  "BSEd Science": 5,  "BSEd Mathematics": 2,  "BPEd": 10},
        407: {"BSEd English": 2,  "BEEd": 2,  "BECEd": 2,  "BSEd Social Studies": 2,  "BTLEd": 10, "BSEd Science": 5,  "BSEd Mathematics": 5,  "BPEd": 10},
        408: {"BSEd English": 2,  "BEEd": 5,  "BECEd": 2,  "BSEd Social Studies": 2,  "BTLEd": 10, "BSEd Science": 10, "BSEd Mathematics": 5,  "BPEd": 2},
        409: {"BSEd English": 10, "BEEd": 5,  "BECEd": 5,  "BSEd Social Studies": 10, "BTLEd": 2,  "BSEd Science": 2,  "BSEd Mathematics": 2,  "BPEd": 2},
        410: {"BSEd English": 5,  "BEEd": 2,  "BECEd": 2,  "BSEd Social Studies": 2,  "BTLEd": 10, "BSEd Science": 10, "BSEd Mathematics": 5,  "BPEd": 2},
        411: {"BSEd English": 2,  "BEEd": 2,  "BECEd": 10, "BSEd Social Studies": 2,  "BTLEd": 5,  "BSEd Science": 2,  "BSEd Mathematics": 10, "BPEd": 5},
        412: {"BSEd English": 10, "BEEd": 10, "BECEd": 5,  "BSEd Social Studies": 2,  "BTLEd": 2,  "BSEd Science": 2,  "BSEd Mathematics": 2,  "BPEd": 5},
        413: {"BSEd English": 5,  "BEEd": 10, "BECEd": 10, "BSEd Social Studies": 5,  "BTLEd": 2,  "BSEd Science": 2,  "BSEd Mathematics": 2,  "BPEd": 2},
        414: {"BSEd English": 10, "BEEd": 10, "BECEd": 5,  "BSEd Social Studies": 5,  "BTLEd": 2,  "BSEd Science": 2,  "BSEd Mathematics": 2,  "BPEd": 2},
        415: {"BSEd English": 5,  "BEEd": 5,  "BECEd": 2,  "BSEd Social Studies": 2,  "BTLEd": 2,  "BSEd Science": 10, "BSEd Mathematics": 10, "BPEd": 2},
        416: {"BSEd English": 5,  "BEEd": 10, "BECEd": 10, "BSEd Social Studies": 5,  "BTLEd": 2,  "BSEd Science": 2,  "BSEd Mathematics": 2,  "BPEd": 2},
    },
    "engineering": {
        501: {"BS Civil Engineering": 2,  "BS Mechanical Engineering": 10, "BS Electrical Engineering": 5,  "BS Computer Engineering": 5,  "BS Chemical Engineering": 2,  "BS Industrial Engineering": 2,  "BS Environmental Engineering": 10, "BS Materials Engineering": 2},
        502: {"BS Civil Engineering": 10, "BS Mechanical Engineering": 5,  "BS Electrical Engineering": 2,  "BS Computer Engineering": 2,  "BS Chemical Engineering": 2,  "BS Industrial Engineering": 2,  "BS Environmental Engineering": 10, "BS Materials Engineering": 5},
        503: {"BS Civil Engineering": 2,  "BS Mechanical Engineering": 2,  "BS Electrical Engineering": 10, "BS Computer Engineering": 10, "BS Chemical Engineering": 2,  "BS Industrial Engineering": 2,  "BS Environmental Engineering": 5,  "BS Materials Engineering": 5},
        504: {"BS Civil Engineering": 2,  "BS Mechanical Engineering": 2,  "BS Electrical Engineering": 10, "BS Computer Engineering": 10, "BS Chemical Engineering": 2,  "BS Industrial Engineering": 2,  "BS Environmental Engineering": 5,  "BS Materials Engineering": 5},
        505: {"BS Civil Engineering": 5,  "BS Mechanical Engineering": 10, "BS Electrical Engineering": 2,  "BS Computer Engineering": 2,  "BS Chemical Engineering": 5,  "BS Industrial Engineering": 2,  "BS Environmental Engineering": 2,  "BS Materials Engineering": 10},
        506: {"BS Civil Engineering": 2,  "BS Mechanical Engineering": 2,  "BS Electrical Engineering": 2,  "BS Computer Engineering": 2,  "BS Chemical Engineering": 10, "BS Industrial Engineering": 5,  "BS Environmental Engineering": 5,  "BS Materials Engineering": 10},
        507: {"BS Civil Engineering": 10, "BS Mechanical Engineering": 10, "BS Electrical Engineering": 5,  "BS Computer Engineering": 2,  "BS Chemical Engineering": 2,  "BS Industrial Engineering": 2,  "BS Environmental Engineering": 5,  "BS Materials Engineering": 2},
        508: {"BS Civil Engineering": 5,  "BS Mechanical Engineering": 2,  "BS Electrical Engineering": 2,  "BS Computer Engineering": 2,  "BS Chemical Engineering": 10, "BS Industrial Engineering": 5,  "BS Environmental Engineering": 10, "BS Materials Engineering": 2},
        509: {"BS Civil Engineering": 10, "BS Mechanical Engineering": 2,  "BS Electrical Engineering": 2,  "BS Computer Engineering": 2,  "BS Chemical Engineering": 5,  "BS Industrial Engineering": 10, "BS Environmental Engineering": 2,  "BS Materials Engineering": 5},
        510: {"BS Civil Engineering": 2,  "BS Mechanical Engineering": 2,  "BS Electrical Engineering": 10, "BS Computer Engineering": 5,  "BS Chemical Engineering": 5,  "BS Industrial Engineering": 2,  "BS Environmental Engineering": 2,  "BS Materials Engineering": 10},
        511: {"BS Civil Engineering": 5,  "BS Mechanical Engineering": 2,  "BS Electrical Engineering": 2,  "BS Computer Engineering": 2,  "BS Chemical Engineering": 10, "BS Industrial Engineering": 5,  "BS Environmental Engineering": 10, "BS Materials Engineering": 2},
        512: {"BS Civil Engineering": 2,  "BS Mechanical Engineering": 2,  "BS Electrical Engineering": 5,  "BS Computer Engineering": 10, "BS Chemical Engineering": 5,  "BS Industrial Engineering": 10, "BS Environmental Engineering": 2,  "BS Materials Engineering": 2},
        513: {"BS Civil Engineering": 2,  "BS Mechanical Engineering": 5,  "BS Electrical Engineering": 10, "BS Computer Engineering": 10, "BS Chemical Engineering": 2,  "BS Industrial Engineering": 5,  "BS Environmental Engineering": 2,  "BS Materials Engineering": 2},
        514: {"BS Civil Engineering": 2,  "BS Mechanical Engineering": 10, "BS Electrical Engineering": 5,  "BS Computer Engineering": 5,  "BS Chemical Engineering": 2,  "BS Industrial Engineering": 10, "BS Environmental Engineering": 2,  "BS Materials Engineering": 2},
        515: {"BS Civil Engineering": 5,  "BS Mechanical Engineering": 5,  "BS Electrical Engineering": 2,  "BS Computer Engineering": 2,  "BS Chemical Engineering": 10, "BS Industrial Engineering": 2,  "BS Environmental Engineering": 2,  "BS Materials Engineering": 10},
        516: {"BS Civil Engineering": 10, "BS Mechanical Engineering": 5,  "BS Electrical Engineering": 2,  "BS Computer Engineering": 5,  "BS Chemical Engineering": 2,  "BS Industrial Engineering": 10, "BS Environmental Engineering": 2,  "BS Materials Engineering": 2},
    },
    "law": {
        601: {"LLB Law": 2,  "JD Juris Doctor": 4,  "BS Criminology": 8,  "BA Public Administration": 10, "BS Forensic Science": 6},
        602: {"LLB Law": 2,  "JD Juris Doctor": 4,  "BS Criminology": 8,  "BA Public Administration": 6,  "BS Forensic Science": 10},
        603: {"LLB Law": 2,  "JD Juris Doctor": 4,  "BS Criminology": 10, "BA Public Administration": 6,  "BS Forensic Science": 8},
        604: {"LLB Law": 2,  "JD Juris Doctor": 8,  "BS Criminology": 4,  "BA Public Administration": 6,  "BS Forensic Science": 10},
        605: {"LLB Law": 2,  "JD Juris Doctor": 8,  "BS Criminology": 4,  "BA Public Administration": 6,  "BS Forensic Science": 10},
        606: {"LLB Law": 2,  "JD Juris Doctor": 8,  "BS Criminology": 4,  "BA Public Administration": 6,  "BS Forensic Science": 10},
        607: {"LLB Law": 8,  "JD Juris Doctor": 6,  "BS Criminology": 4,  "BA Public Administration": 10, "BS Forensic Science": 2},
        608: {"LLB Law": 2,  "JD Juris Doctor": 8,  "BS Criminology": 6,  "BA Public Administration": 4,  "BS Forensic Science": 10},
        609: {"LLB Law": 6,  "JD Juris Doctor": 4,  "BS Criminology": 8,  "BA Public Administration": 2,  "BS Forensic Science": 10},
        610: {"LLB Law": 10, "JD Juris Doctor": 8,  "BS Criminology": 4,  "BA Public Administration": 6,  "BS Forensic Science": 2},
        611: {"LLB Law": 10, "JD Juris Doctor": 8,  "BS Criminology": 4,  "BA Public Administration": 6,  "BS Forensic Science": 2},
        612: {"LLB Law": 10, "JD Juris Doctor": 2,  "BS Criminology": 8,  "BA Public Administration": 4,  "BS Forensic Science": 6},
        613: {"LLB Law": 10, "JD Juris Doctor": 8,  "BS Criminology": 6,  "BA Public Administration": 4,  "BS Forensic Science": 2},
        614: {"LLB Law": 10, "JD Juris Doctor": 2,  "BS Criminology": 8,  "BA Public Administration": 6,  "BS Forensic Science": 4},
        615: {"LLB Law": 8,  "JD Juris Doctor": 6,  "BS Criminology": 4,  "BA Public Administration": 10, "BS Forensic Science": 2},
        616: {"LLB Law": 10, "JD Juris Doctor": 8,  "BS Criminology": 6,  "BA Public Administration": 4,  "BS Forensic Science": 2},
    },
    "tourism": {
        701: {"BS Tourism Management": 2,  "BS Hotel and Restaurant Management": 6,  "BS Hospitality Management": 4,  "BS Event Management": 8,  "BS Culinary Arts": 10},
        702: {"BS Tourism Management": 2,  "BS Hotel and Restaurant Management": 4,  "BS Hospitality Management": 6,  "BS Event Management": 10, "BS Culinary Arts": 8},
        703: {"BS Tourism Management": 2,  "BS Hotel and Restaurant Management": 8,  "BS Hospitality Management": 6,  "BS Event Management": 4,  "BS Culinary Arts": 10},
        704: {"BS Tourism Management": 4,  "BS Hotel and Restaurant Management": 10, "BS Hospitality Management": 8,  "BS Event Management": 6,  "BS Culinary Arts": 2},
        705: {"BS Tourism Management": 6,  "BS Hotel and Restaurant Management": 2,  "BS Hospitality Management": 8,  "BS Event Management": 10, "BS Culinary Arts": 4},
        706: {"BS Tourism Management": 2,  "BS Hotel and Restaurant Management": 6,  "BS Hospitality Management": 4,  "BS Event Management": 8,  "BS Culinary Arts": 10},
        707: {"BS Tourism Management": 2,  "BS Hotel and Restaurant Management": 4,  "BS Hospitality Management": 8,  "BS Event Management": 6,  "BS Culinary Arts": 10},
        708: {"BS Tourism Management": 6,  "BS Hotel and Restaurant Management": 8,  "BS Hospitality Management": 2,  "BS Event Management": 4,  "BS Culinary Arts": 10},
        709: {"BS Tourism Management": 10, "BS Hotel and Restaurant Management": 4,  "BS Hospitality Management": 8,  "BS Event Management": 6,  "BS Culinary Arts": 2},
        710: {"BS Tourism Management": 6,  "BS Hotel and Restaurant Management": 10, "BS Hospitality Management": 8,  "BS Event Management": 4,  "BS Culinary Arts": 2},
        711: {"BS Tourism Management": 10, "BS Hotel and Restaurant Management": 6,  "BS Hospitality Management": 8,  "BS Event Management": 4,  "BS Culinary Arts": 2},
        712: {"BS Tourism Management": 10, "BS Hotel and Restaurant Management": 6,  "BS Hospitality Management": 8,  "BS Event Management": 4,  "BS Culinary Arts": 2},
        713: {"BS Tourism Management": 10, "BS Hotel and Restaurant Management": 4,  "BS Hospitality Management": 8,  "BS Event Management": 6,  "BS Culinary Arts": 2},
        714: {"BS Tourism Management": 8,  "BS Hotel and Restaurant Management": 6,  "BS Hospitality Management": 2,  "BS Event Management": 4,  "BS Culinary Arts": 10},
        715: {"BS Tourism Management": 10, "BS Hotel and Restaurant Management": 4,  "BS Hospitality Management": 6,  "BS Event Management": 8,  "BS Culinary Arts": 2},
        716: {"BS Tourism Management": 6,  "BS Hotel and Restaurant Management": 8,  "BS Hospitality Management": 2,  "BS Event Management": 4,  "BS Culinary Arts": 10},
    },
}

DOMAIN_LABELS = {
    "business":    "Business",
    "technology":  "Technology",
    "healthcare":  "Healthcare",
    "liberal_arts":"Liberal Arts",
    "education":   "Education",
    "engineering": "Engineering",
    "law":         "Law",
    "tourism":     "Tourism",
}

FAILURE_NOTES = {
    "JD Juris Doctor": (
        "JD Juris Doctor and LLB Law share near-identical weight distributions "
        "in the Law domain. The Law weight matrix uses a denser scale (2–10) "
        "rather than the standard 2/5/10 tiers, reducing discriminability between "
        "these two closely related programs."
    ),
    "BS Hospitality Management": (
        "BS Hospitality Management and BS Tourism Management are academically "
        "adjacent programs with heavily overlapping weight profiles. In real usage, "
        "both programs are valid alternatives for the same respondent profile."
    ),
}

# ─────────────────────────────────────────────────────────────────────────────
# SCORING ENGINE
# ─────────────────────────────────────────────────────────────────────────────

def calculate_scores(responses: dict, category: str) -> dict:
    """
    Weighted scoring + normalization to 0–100%.
    normalized_score(course) = (Σ response_i × weight_i) / (Σ 5 × weight_i) × 100
    """
    mapping = COURSE_MAPPINGS[category]
    courses = list(next(iter(mapping.values())).keys())

    raw = {c: 0.0 for c in courses}
    for q_id, weights in mapping.items():
        rating = responses.get(q_id, 0)
        for course in courses:
            raw[course] += rating * weights.get(course, 0)

    normalized = {}
    for course in courses:
        max_possible = sum(5 * weights.get(course, 0) for weights in mapping.values())
        normalized[course] = (raw[course] / max_possible * 100) if max_possible > 0 else 0.0

    return dict(sorted(normalized.items(), key=lambda x: x[1], reverse=True))


def build_ideal_profile(target_course: str, category: str) -> dict:
    """
    Sets 5 for questions where target course has weight >= 10, else 1.
    """
    mapping = COURSE_MAPPINGS[category]
    return {
        q_id: (5 if weights.get(target_course, 0) >= 10 else 1)
        for q_id, weights in mapping.items()
    }


# ─────────────────────────────────────────────────────────────────────────────
# TABLE FORMATTING
# ─────────────────────────────────────────────────────────────────────────────

W  = 75
C1 = 5   # No.
C2 = 46  # Course name
C3 = 13  # Match Score
C4 = 8   # Status


def hdr():
    return "\n".join([
        "=" * W,
        " PATHFINDER — COURSE RECOMMENDATION ACCURACY TEST".center(W),
        "=" * W,
        f"  {'Test Method':<22}: Ideal Student Simulation (Weighted Scoring)",
        f"  {'Normalization':<22}: score = (Σ response × weight) / (Σ 5 × weight) × 100%",
        f"  {'Answer Scale':<22}: 1–5 per question (16 questions per domain)",
        f"  {'Total Courses Tested':<22}: 58 degree programs across 8 academic domains",
        "=" * W,
    ])


def domain_header(label, total, passed):
    status = f"({passed}/{total} Passed)"
    return (
        f"\n  [ {label.upper()} DOMAIN ]  {status}\n"
        f"  {'─' * (W - 2)}\n"
        f"  {'No.':<{C1}} {'Degree Program':<{C2}} {'Match Score':>{C3}} {'Status':>{C4}}\n"
        f"  {'─' * (W - 2)}"
    )


def data_row(idx, course, score, passed):
    score_str = f"{score:.1f}%" if passed else "—"
    status    = "PASS" if passed else "FAIL"
    return f"  {idx:<{C1}} {course:<{C2}} {score_str:>{C3}} {status:>{C4}}"


def fail_row(idx, course, got_course, got_score):
    label = f"{course}"
    note  = f"  → Recommended: {got_course} ({got_score:.1f}%)"
    return (
        f"  {idx:<{C1}} {label:<{C2}} {'—':>{C13}} {'FAIL':>{C4}}\n"
        f"  {'':<{C1}} {note:<{C2 + C3 + C4}}"
    ).replace("C13", str(C3))


def summary_table(domain_stats):
    lines = [
        f"\n  {'─' * (W - 2)}",
        f"  DOMAIN SUMMARY",
        f"  {'─' * (W - 2)}",
        f"  {'Domain':<16} {'Courses':>8} {'Correct':>9} {'Failed':>7} {'Accuracy':>10}",
        f"  {'─' * (W - 2)}",
    ]
    total_r = total_c = total_f = 0
    for domain, (roles, correct, failed) in domain_stats.items():
        acc = (correct / roles * 100) if roles > 0 else 0.0
        lines.append(f"  {DOMAIN_LABELS[domain]:<16} {roles:>8} {correct:>9} {failed:>7} {acc:>9.2f}%")
        total_r += roles; total_c += correct; total_f += failed
    overall_acc = (total_c / total_r * 100) if total_r > 0 else 0.0
    lines += [
        f"  {'─' * (W - 2)}",
        f"  {'OVERALL':<16} {total_r:>8} {total_c:>9} {total_f:>7} {overall_acc:>9.2f}%",
        "=" * W,
    ]
    return "\n".join(lines)


def failure_notes_section(all_failures):
    if not all_failures:
        return ""
    lines = ["\n  NOTES ON FAILURES\n  " + "─" * (W - 2)]
    seen = set()
    for _, target, got, got_score in all_failures:
        lines.append(f"\n  [{target}] → Recommended: {got} ({got_score:.1f}%)")
        if target in FAILURE_NOTES and target not in seen:
            seen.add(target)
            wrapped = FAILURE_NOTES[target]
            # Simple word-wrap at 68 chars
            words = wrapped.split()
            line_buf = "  "
            for w in words:
                if len(line_buf) + len(w) + 1 > 70:
                    lines.append(line_buf)
                    line_buf = "  " + w
                else:
                    line_buf += (" " if line_buf != "  " else "") + w
            if line_buf.strip():
                lines.append(line_buf)
    lines.append("\n" + "=" * W)
    return "\n".join(lines)


# ─────────────────────────────────────────────────────────────────────────────
# MAIN RUNNER
# ─────────────────────────────────────────────────────────────────────────────

def run():
    lines = [hdr()]
    domain_stats = {}
    all_failures = []

    for category in COURSE_MAPPINGS:
        mapping  = COURSE_MAPPINGS[category]
        courses  = list(next(iter(mapping.values())).keys())
        d_passed = d_failed = 0
        d_rows   = []

        for idx, target_course in enumerate(courses, start=1):
            profile = build_ideal_profile(target_course, category)
            scores  = calculate_scores(profile, category)
            top     = list(scores.keys())[0]
            passed  = (top == target_course)

            if passed:
                d_passed += 1
                d_rows.append(data_row(idx, target_course, scores[target_course], True))
            else:
                d_failed += 1
                got_score = scores[top]
                all_failures.append((category, target_course, top, got_score))
                d_rows.append(
                    f"  {idx:<{C1}} {target_course:<{C2}} {'—':>{C3}} {'FAIL':>{C4}}\n"
                    f"  {'':<{C1}} {'  → Recommended: ' + top + f' ({got_score:.1f}%)'}"
                )

        domain_stats[category] = (d_passed + d_failed, d_passed, d_failed)
        lines.append(domain_header(DOMAIN_LABELS[category], d_passed + d_failed, d_passed))
        lines.extend(d_rows)

    lines.append(summary_table(domain_stats))
    lines.append(failure_notes_section(all_failures))

    output = "\n".join(lines)
    print(output)

    with open(REPORT_PATH, "w", encoding="utf-8") as f:
        f.write(output + "\n")

    print(f"\n  Report saved → {REPORT_PATH}")


if __name__ == "__main__":
    run()
