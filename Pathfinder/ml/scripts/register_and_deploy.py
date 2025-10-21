import argparse
from pathlib import Path
import os

# Placeholder: integrate Azure ML SDK when backend infra is finalized.
# For now, simply simulate a registration step.

def register(model_file: str, model_name: str):
    if not Path(model_file).exists():
        raise SystemExit(f"Model file {model_file} not found")
    print(f"Pretending to register model '{model_name}' from {model_file} to Azure ML workspace...")
    # TODO: Add real azure.ai.ml code once workspace auth patterns are decided.


def parse_args():
    p = argparse.ArgumentParser()
    p.add_argument('--model-file', default='model.pkl')
    p.add_argument('--model-name', required=True)
    return p.parse_args()

if __name__ == '__main__':
    args = parse_args()
    register(args.model_file, args.model_name)
