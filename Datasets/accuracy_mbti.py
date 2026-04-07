"""
accuracy_mbti.py
================
PATHFINDER — MBTI Personality Assessment Accuracy Test

Test Method:
    Pure-Type Ideal Profile Simulation.
    For each of the 16 MBTI personalities, a synthetic respondent is constructed:
      - Trait-aligned questions (direct scoring) → answer = 7 (maximum)
      - Opposing-trait questions (reverse scoring) → answer = 1 (minimum)
    The 4-axis differential scoring algorithm is then applied.
    The resulting type must exactly match the target type to PASS.

Output:
    - Console (formatted table)
    - Datasets/mbti_accuracy_report.txt

Usage:
    python Datasets/accuracy_mbti.py
"""

import os

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
    """
    Applies the 4-axis differential scoring algorithm.
    Returns (mbti_type_string, per_dimension_results).
    """
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
    """
    Constructs a synthetic respondent who answers maximally
    in the direction of every trait in target_type.
    """
    answers = {}
    flags = {
        "E": "E" in target_type,
        "S": "S" in target_type,
        "T": "T" in target_type,
        "J": "J" in target_type,
    }

    dim_flags = {
        "EI": flags["E"],
        "SN": flags["S"],
        "TF": flags["T"],
        "JP": flags["J"],
    }

    for dim_key, dim in DIMENSIONS.items():
        want_positive = dim_flags[dim_key]
        for q in dim["direct"]:
            answers[f"q{q}"] = 7 if want_positive else 1
        for q in dim["reverse"]:
            answers[f"q{q}"] = 1 if want_positive else 7

    return answers


# ─────────────────────────────────────────────────────────────────────────────
# TABLE FORMATTING HELPERS
# ─────────────────────────────────────────────────────────────────────────────

W = 70  # table width


def sep(char="─", width=W):
    return char * width


def header():
    lines = [
        "=" * W,
        " PATHFINDER — MBTI PERSONALITY ASSESSMENT ACCURACY TEST".center(W),
        "=" * W,
        f"  {'Test Method':<20}: Pure-Type Ideal Profile Simulation",
        f"  {'Questionnaire':<20}: 60 items across 4 bipolar dimensions (15 items each)",
        f"  {'Answer Scale':<20}: 1–7 Likert scale per item",
        f"  {'Total Types Tested':<20}: 16 MBTI personality types",
        "=" * W,
    ]
    return "\n".join(lines)


def col_header():
    return (
        f"\n  {'No.':<5} {'Target Type':<13} {'Temperament':<13}"
        f" {'E/I':<5} {'S/N':<5} {'T/F':<5} {'J/P':<5}"
        f" {'Result':<8} {'Status'}"
        f"\n  {sep('─', 68)}"
    )


def row(idx, target, temperament, dims, result, passed):
    status = "PASS" if passed else "FAIL"
    return (
        f"  {idx:<5} {target:<13} {temperament:<13}"
        f" {dims['EI']:<5} {dims['SN']:<5} {dims['TF']:<5} {dims['JP']:<5}"
        f" {result:<8} {status}"
    )


def summary(total, correct):
    failed = total - correct
    accuracy = (correct / total * 100) if total > 0 else 0.0
    lines = [
        f"\n  {sep()}",
        f"  {'Total Simulations':<25}: {total}",
        f"  {'Correct Predictions':<25}: {correct}",
        f"  {'Failed Predictions':<25}: {failed}",
        f"  {'Logical Accuracy':<25}: {accuracy:.2f}%",
        "=" * W,
    ]
    return "\n".join(lines)


# ─────────────────────────────────────────────────────────────────────────────
# MAIN RUNNER
# ─────────────────────────────────────────────────────────────────────────────

def run():
    lines = [header(), col_header()]

    total   = 0
    correct = 0
    failures = []

    for idx, target_type in enumerate(ALL_TYPES, start=1):
        total += 1
        answers        = build_pure_profile(target_type)
        result, dims   = calculate_mbti_type(answers)
        passed         = (result == target_type)
        temperament    = TEMPERAMENT[target_type]

        if passed:
            correct += 1
        else:
            failures.append((target_type, result))

        lines.append(row(idx, target_type, temperament, dims, result, passed))

    lines.append(summary(total, correct))

    if failures:
        lines.append("\n  FAILURE DETAILS:")
        for target, got in failures:
            lines.append(f"    Target: {target}  →  Got: {got}")
        lines.append("=" * W)

    output = "\n".join(lines)

    # ── Console ──
    print(output)

    # ── File ──
    with open(REPORT_PATH, "w", encoding="utf-8") as f:
        f.write(output + "\n")

    print(f"\n  Report saved → {REPORT_PATH}")


if __name__ == "__main__":
    run()
