import pandas as pd
import os

# Paths
BASE_DIR = os.path.dirname(os.path.abspath(__file__))
DATASET_PATH = os.path.join(BASE_DIR, 'Cloude-Resume', 'Kaggle-Resume', 'Resume.csv')

def main():
    if not os.path.exists(DATASET_PATH):
        print(f"Error: Dataset not found at {DATASET_PATH}")
        return

    df = pd.read_csv(DATASET_PATH)
    
    print("--- Category Distribution ---")
    counts = df['Category'].value_counts()
    print(counts)
    
    print("\nTotal Resumes:", len(df))
    print("Number of Categories:", len(counts))
    print("Average Resumes per Category:", round(len(df) / len(counts), 2))
    print("Min Resumes in Category:", counts.min())
    print("Max Resumes in Category:", counts.max())

if __name__ == "__main__":
    main()
