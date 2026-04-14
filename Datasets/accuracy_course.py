import os
import random

# ─────────────────────────────────────────────────────────────────────────────
# REPORT OUTPUT PATH
# ─────────────────────────────────────────────────────────────────────────────
BASE_DIR    = os.path.dirname(os.path.abspath(__file__))
REPORT_PATH = os.path.join(BASE_DIR, "course_accuracy_report.txt")

# ─────────────────────────────────────────────────────────────────────────────
# COURSE WEIGHT MAPPINGS
# Source: PathfinderController.php → calculateWeightedCourseScores()
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
        "in the Law domain. The Law weight matrix uses a denser scale (2-10) "
        "rather than standard tiers, reducing discriminability."
    ),
    "BS Hospitality Management": (
        "BS Hospitality Management and BS Tourism Management are academically "
        "adjacent programs with heavily overlapping weight profiles."
    ),
}

# ─────────────────────────────────────────────────────────────────────────────
# SCORING ENGINE
# ─────────────────────────────────────────────────────────────────────────────

def calculate_scores(responses: dict, category: str) -> dict:
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
    mapping = COURSE_MAPPINGS[category]
    # Note: Using >= 8 because some matrices like Law/Tourism use 8-10 for high impact
    return {
        q_id: (5 if weights.get(target_course, 0) >= 8 else 1)
        for q_id, weights in mapping.items()
    }

def build_noisy_profile(target_course: str, category: str, noise_level: str) -> dict:
    mapping = COURSE_MAPPINGS[category]
    profile = {}
    
    for q_id, weights in mapping.items():
        ww = weights.get(target_course, 0)
        
        if ww >= 8: # High Impact
            if noise_level == "Strong": val = random.randint(4, 5)
            elif noise_level == "Moderate": val = random.randint(3, 5)
            else: val = random.randint(3, 4)
        elif ww >= 5: # Moderate Impact
            if noise_level == "Strong": val = random.randint(3, 4)
            elif noise_level == "Moderate": val = random.randint(2, 4)
            else: val = random.randint(2, 3)
        else: # Low Impact
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
        " PATHFINDER — COURSE RECOMMENDATION ACCURACY & CONCORDANCE TEST".center(W),
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

    total_ideal = 0
    total_ideal_passed = 0
    
    for category in COURSE_MAPPINGS:
        courses = list(next(iter(COURSE_MAPPINGS[category].values())).keys())
        total_ideal += len(courses)
        for target_course in courses:
            profile = build_ideal_profile(target_course, category)
            scores  = calculate_scores(profile, category)
            top     = list(scores.keys())[0]
            if top == target_course:
                total_ideal_passed += 1

    lines.extend([
        f"  Total Courses Tested : {total_ideal}",
        f"  Passed Ideal Tests   : {total_ideal_passed}",
        f"  Logical Accuracy     : {(total_ideal_passed/total_ideal*100):.2f}%",
    ])

    # ---------------------------------------------------------
    # PART 2: NOISE SIMULATION vs MATRIX RULES
    # ---------------------------------------------------------
    lines.extend([
        "\n\n" + "=" * W,
        " PART 2: 58-INPUT MATRIX CONCORDANCE TEST (REALISTIC NOISE)".center(W),
        " Testing 1 Noisy Variant For Every Single Course Against Ground Truth".center(W),
        "=" * W,
        f"\n  {'No.':<4} {'Matrix Expected (Target)':<35} {'Noise Level':<12} {'System Output (Top-1)':<35} {'Status'}",
        f"  {'─' * (W - 4)}"
    ])

    total_noisy = 0
    correct_top1 = 0
    test_idx = 1
    
    domain_stats = {d: {'total': 0, 'correct': 0} for d in COURSE_MAPPINGS.keys()}
    all_failures = []

    for category, mapping in COURSE_MAPPINGS.items():
        courses = list(next(iter(mapping.values())).keys())
        
        for i, target_course in enumerate(courses):
            noise = ["Strong", "Moderate", "Weak"][i % 3]
            
            profile = build_noisy_profile(target_course, category, noise)
            scores = calculate_scores(profile, category)
            top_course = list(scores.keys())[0]
            
            passed = (top_course == target_course)
            status = "PASS" if passed else "FAIL"
            
            domain_stats[category]['total'] += 1
            total_noisy += 1
            if passed: 
                correct_top1 += 1
                domain_stats[category]['correct'] += 1
            else:
                all_failures.append((category, target_course, noise, top_course, scores[top_course]))
            
            # Truncate strings to fit layout
            t_course = target_course[:33] + ".." if len(target_course) > 35 else target_course
            r_course = top_course[:33] + ".." if len(top_course) > 35 else top_course
            
            lines.append(f"  {test_idx:<4} {t_course:<35} {noise:<12} {r_course:<35} {status}")
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
    
    for category in COURSE_MAPPINGS.keys():
        s = domain_stats[category]
        acc = (s['correct'] / s['total'] * 100) if s['total'] > 0 else 0
        lines.append(f"    {DOMAIN_LABELS[category]:<15} : {s['correct']:>2}/{s['total']:>2} ({acc:>6.2f}%)")
        
    if all_failures:
        lines.extend([
            f"\n  {'─' * (W - 4)}",
            f"  NOTES ON FAILURES",
            f"  {'─' * (W - 4)}"
        ])
        seen = set()
        for cat, target, noise, got, score in all_failures:
            lines.append(f"  [{target}] ({noise} noise) → Got: {got} ({score:.1f}%)")
            if target in FAILURE_NOTES and target not in seen:
                seen.add(target)
                lines.append(f"    Note: {FAILURE_NOTES[target]}")
                
    lines.append("=" * W)

    output = "\n".join(lines)
    print(output)
    
    with open(REPORT_PATH, "w", encoding="utf-8") as f:
        f.write(output + "\n")
    print(f"\n  Report saved → {REPORT_PATH}")

if __name__ == "__main__":
    run()
