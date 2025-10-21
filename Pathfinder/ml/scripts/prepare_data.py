import pandas as pd
from pathlib import Path

RAW_DATA = [
    {"user_id": 1, "skill_tag": "python", "interest": 0.9},
    {"user_id": 1, "skill_tag": "data", "interest": 0.8},
    {"user_id": 2, "skill_tag": "law", "interest": 0.7},
    {"user_id": 3, "skill_tag": "design", "interest": 0.6},
]

def main():
    df = pd.DataFrame(RAW_DATA)
    out = Path(__file__).parent / 'prepared.csv'
    df.to_csv(out, index=False)
    print(f"Prepared dataset written to {out}")

if __name__ == '__main__':
    main()
