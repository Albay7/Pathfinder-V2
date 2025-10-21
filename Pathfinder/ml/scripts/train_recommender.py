import argparse
import pandas as pd
from pathlib import Path
from sklearn.preprocessing import OneHotEncoder
from sklearn.linear_model import LogisticRegression
from sklearn.pipeline import Pipeline
import joblib


def train(model_out: str, epochs: int):
    data_path = Path(__file__).parent / 'prepared.csv'
    if not data_path.exists():
        raise SystemExit("prepared.csv not found. Run prepare_data.py first")
    df = pd.read_csv(data_path)
    # Simple placeholder model: predict interest > 0.75
    df['label'] = (df['interest'] > 0.75).astype(int)
    X = df[['skill_tag']]
    y = df['label']
    pipe = Pipeline([
        ('enc', OneHotEncoder(handle_unknown='ignore')),
        ('clf', LogisticRegression(max_iter=100))
    ])
    pipe.fit(X, y)
    joblib.dump(pipe, model_out)
    print(f"Model trained and saved to {model_out}")


def parse_args():
    p = argparse.ArgumentParser()
    p.add_argument('--model-out', default='model.pkl')
    p.add_argument('--epochs', type=int, default=3)
    return p.parse_args()


if __name__ == '__main__':
    args = parse_args()
    train(args.model_out, args.epochs)
