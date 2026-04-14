import os
import random

# ─────────────────────────────────────────────────────────────────────────────
# REPORT OUTPUT PATH
# ─────────────────────────────────────────────────────────────────────────────
BASE_DIR    = os.path.dirname(os.path.abspath(__file__))
REPORT_PATH = os.path.join(BASE_DIR, "mbti_accuracy_report.txt")

# ─────────────────────────────────────────────────────────────────────────────
# MBTI SCORING LOGIC
# Mirrored from: MbtiController.php → processQuestionnaire()
#
# Dimension layout (60 questions total, 15 per axis):
#   E/I  →  Q1–Q15   | Direct E:  1,3,5,7,9,11,13,15  | Reverse I: 2,4,6,8,10,12,14
#   S/N  →  Q16–Q30  | Direct S: 16,18,20,22,24,26,28,30 | Reverse N: 17,19,21,23,25,27,29
#   T/F  →  Q31–Q45  | Direct T: 31,33,35,37,39,41,43,45 | Reverse F: 32,34,36,38,40,42,44
#   J/P  →  Q46–Q60  | Direct J: 46,48,50,52,54,56,58,60 | Reverse P: 47,49,51,53,55,57,59
# ─────────────────────────────────────────────────────────────────────────────

DIMENSIONS = {
    "EI": {
        "direct":  [1, 3, 5, 7, 9, 11, 13, 15],
        "reverse": [2, 4, 6, 8, 10, 12, 14],
        "pos_letter": "E",
        "neg_letter": "I",
    },
    "SN": {
        "direct":  [16, 18, 20, 22, 24, 26, 28, 30],
        "reverse": [17, 19, 21, 23, 25, 27, 29],
        "pos_letter": "S",
        "neg_letter": "N",
    },
    "TF": {
        "direct":  [31, 33, 35, 37, 39, 41, 43, 45],
        "reverse": [32, 34, 36, 38, 40, 42, 44],
        "pos_letter": "T",
        "neg_letter": "F",
    },
    "JP": {
        "direct":  [46, 48, 50, 52, 54, 56, 58, 60],
        "reverse": [47, 49, 51, 53, 55, 57, 59],
        "pos_letter": "J",
        "neg_letter": "P",
    },
}

TEMPERAMENT = {
    "INTJ": "Analyst",  "INTP": "Analyst",  "ENTJ": "Analyst",  "ENTP": "Analyst",
    "INFJ": "Diplomat", "INFP": "Diplomat", "ENFJ": "Diplomat", "ENFP": "Diplomat",
    "ISTJ": "Sentinel", "ISFJ": "Sentinel", "ESTJ": "Sentinel", "ESFJ": "Sentinel",
    "ISTP": "Explorer", "ISFP": "Explorer", "ESTP": "Explorer", "ESFP": "Explorer",
}

ALL_TYPES = [
    "INTJ", "INTP", "ENTJ", "ENTP",
    "INFJ", "INFP", "ENFJ", "ENFP",
    "ISTJ", "ISFJ", "ESTJ", "ESFJ",
    "ISTP", "ISFP", "ESTP", "ESFP",
]


def calculate_mbti_type(answers: dict) -> tuple[str, dict]:
    result_letters = []
    dim_results = {}

    for dim_key, dim in DIMENSIONS.items():
        pos_score = 0
        neg_score = 0

        for q in dim["direct"]:
            val = answers.get(f"q{q}", 4)
            pos_score += val
            neg_score += (8 - val)

        for q in dim["reverse"]:
            val = answers.get(f"q{q}", 4)
            neg_score += val
            pos_score += (8 - val)

        winner = dim["pos_letter"] if pos_score > neg_score else dim["neg_letter"]
        result_letters.append(winner)
        dim_results[dim_key] = winner

    return "".join(result_letters), dim_results


def build_pure_profile(target_type: str) -> dict:
    answers = {}
    flags = {
        "E": "E" in target_type, "S": "S" in target_type,
        "T": "T" in target_type, "J": "J" in target_type,
    }

    dim_flags = {"EI": flags["E"], "SN": flags["S"], "TF": flags["T"], "JP": flags["J"]}

    for dim_key, dim in DIMENSIONS.items():
        want_positive = dim_flags[dim_key]
        for q in dim["direct"]: answers[f"q{q}"] = 7 if want_positive else 1
        for q in dim["reverse"]: answers[f"q{q}"] = 1 if want_positive else 7

    return answers


def build_noisy_profile(target_type: str, noise_level: str) -> dict:
    answers = {}
    flags = {
        "E": "E" in target_type, "S": "S" in target_type,
        "T": "T" in target_type, "J": "J" in target_type,
    }
    dim_flags = {"EI": flags["E"], "SN": flags["S"], "TF": flags["T"], "JP": flags["J"]}
    
    for dim_key, dim in DIMENSIONS.items():
        want_positive = dim_flags[dim_key]
        
        for q in dim["direct"]:
            if want_positive:
                if noise_level == "Strong": val = random.randint(5, 7)
                elif noise_level == "Moderate": val = random.randint(4, 6)
                else: val = random.randint(4, 5) # Weak
            else:
                if noise_level == "Strong": val = random.randint(1, 3)
                elif noise_level == "Moderate": val = random.randint(2, 4)
                else: val = random.randint(3, 4) # Weak
            answers[f"q{q}"] = val
            
        for q in dim["reverse"]:
            if want_positive:
                if noise_level == "Strong": val = random.randint(1, 3)
                elif noise_level == "Moderate": val = random.randint(2, 4)
                else: val = random.randint(3, 4) # Weak
            else:
                if noise_level == "Strong": val = random.randint(5, 7)
                elif noise_level == "Moderate": val = random.randint(4, 6)
                else: val = random.randint(4, 5) # Weak
            answers[f"q{q}"] = val

    return answers


# ─────────────────────────────────────────────────────────────────────────────
# TABLE FORMATTING HELPERS
# ─────────────────────────────────────────────────────────────────────────────

W = 85

def sep(char="─", width=W):
    return char * width

def run():
    random.seed(42) # Reproducibility
    
    lines = [
        "=" * W,
        " PATHFINDER — MBTI PERSONALITY ASSESSMENT ACCURACY & PERFORMANCE TEST".center(W),
        "=" * W,
    ]
    
    # ---------------------------------------------------------
    # PART 1: LOGICAL CORRECTNESS (PURE IDEAL)
    # ---------------------------------------------------------
    lines.extend([
        "\n" + "=" * W,
        " PART 1: LOGICAL CORRECTNESS (PURE-TYPE IDEAL SIMULATION)".center(W),
        " Testing the Mathematical Limits of the Recommendation Engine".center(W),
        "=" * W,
        f"\n  {'No.':<4} {'Target Type':<11} {'Temperament':<10}"
        f" {'E/I':<4} {'S/N':<4} {'T/F':<4} {'J/P':<4}"
        f" {'Result':<8} {'Status'}",
        f"  {sep('─', W-4)}"
    ])

    total_pure = 0
    correct_pure = 0
    failures_pure = []

    for idx, target_type in enumerate(ALL_TYPES, start=1):
        total_pure += 1
        answers = build_pure_profile(target_type)
        result, dims = calculate_mbti_type(answers)
        passed = (result == target_type)
        temperament = TEMPERAMENT[target_type]

        if passed: correct_pure += 1
        else: failures_pure.append((target_type, result))

        status = "PASS" if passed else "FAIL"
        lines.append(
            f"  {idx:<4} {target_type:<11} {temperament:<10}"
            f" {dims['EI']:<4} {dims['SN']:<4} {dims['TF']:<4} {dims['JP']:<4}"
            f" {result:<8} {status}"
        )
        
    lines.extend([
        f"\n  {sep('─', W-4)}",
        f"  {'Total Pure Simulations':<25}: {total_pure}",
        f"  {'Logical Accuracy':<25}: {(correct_pure/total_pure*100):.2f}%",
        f"  {sep('─', W-4)}"
    ])

    # ---------------------------------------------------------
    # PART 2: CONCORDANCE TEST (VARIED NOISE SIMULATION)
    # ---------------------------------------------------------
    lines.extend([
        "\n\n" + "=" * W,
        " PART 2: 48-INPUT MATRIX CONCORDANCE TEST (REALISTIC NOISE)".center(W),
        " Testing Recommendation Robustness vs. the Ground Truth Matrix".center(W),
        "=" * W,
        f"\n  {'No.':<4} {'Matrix Expected':<16} {'Noise Level':<12}"
        f" {'E/I':<4} {'S/N':<4} {'T/F':<4} {'J/P':<4}"
        f" {'System Output':<15} {'Status'}",
        f"  {sep('─', W-4)}"
    ])

    total_noisy = 0
    correct_noisy = 0
    per_type_metrics = {t: {'total': 0, 'correct': 0} for t in ALL_TYPES}

    test_idx = 1
    for target_type in ALL_TYPES:
        for noise in ["Strong", "Moderate", "Weak"]:
            total_noisy += 1
            answers = build_noisy_profile(target_type, noise)
            result, dims = calculate_mbti_type(answers)
            passed = (result == target_type)
            
            per_type_metrics[target_type]['total'] += 1
            if passed: 
                correct_noisy += 1
                per_type_metrics[target_type]['correct'] += 1

            status = "PASS" if passed else "FAIL"
            lines.append(
                f"  {test_idx:<4} {target_type:<16} {noise:<12}"
                f" {dims['EI']:<4} {dims['SN']:<4} {dims['TF']:<4} {dims['JP']:<4}"
                f" {result:<15} {status}"
            )
            test_idx += 1

    lines.extend([
        f"\n  {sep('─', W-4)}",
        f"  {'Total Noisy Simulations':<25}: {total_noisy}",
        f"  {'Concordance Rate':<25}: {(correct_noisy/total_noisy*100):.2f}%",
        f"  {sep('─', W-4)}",
        "\n  PER-TYPE BREAKDOWN:",
    ])
    
    for t in ALL_TYPES:
        m = per_type_metrics[t]
        acc = (m['correct'] / m['total'] * 100) if m['total'] > 0 else 0
        lines.append(f"  {t:<6} : {m['correct']}/{m['total']} ({acc:>6.2f}%)")

    lines.append("=" * W)

    output = "\n".join(lines)
    print(output)
    with open(REPORT_PATH, "w", encoding="utf-8") as f:
        f.write(output + "\n")
    print(f"\n  Report saved → {REPORT_PATH}")

if __name__ == "__main__":
    run()
