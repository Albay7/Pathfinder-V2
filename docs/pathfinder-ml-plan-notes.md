# Pathfinder — ML Plan (Saved Conversation Notes)
Date: 2025-11-03

## Summary
- Intelligent system: yes. Use learned text representations + similarity + rules/weights to personalize recommendations.
- Core: sentence embeddings + cosine similarity; TF‑IDF as baseline; blend with MBTI fit and skills overlap.
- Optional upgrade: learn weights with a small supervised meta-model (logistic regression or XGBoost) from user feedback.

## Main Model (current)
- Representation: sentence-transformers/all-MiniLM-L6-v2 (CPU-friendly).
- Scoring: cosine similarity between user answers and job/skill vectors.
- Ensemble (heuristic to start): score = 0.7*cosine_embed + 0.2*mbti_fit + 0.1*skills_overlap.
- Baseline: TF‑IDF + cosine for exact/keyword overlap.

## Meta-Model (later, becomes the “main model”)
- Inputs (features): cosine_tfidf, cosine_embed, mbti_fit, skills_overlap (and optional token/idf overlap).
- Labels: user feedback (thumbs up/down, clicks, bookmarks).
- Models: start with LogisticRegression (pointwise); upgrade to XGBoost rank:pairwise for better reranking.
- Flow: retrieve top-N by embeddings → compute features → meta-model reranks → explain component scores.

## Architecture
- Laravel (web/API, UI).
- ML microservice (FastAPI, Python):
  - /match: embed user answers, query vectors, blend scores, return top K with explanations.
  - Preloads embedding model at startup.
- Vector DB: Qdrant Cloud (cosine). Store job/skill embeddings; query top-N.
- Deployment: Railway for Laravel + ML service (simple now). Azure later if desired.

## Data & Storage
- Job/skill corpus → embeddings (batch/offline) → Qdrant collection (cosine).
- Model assets (if any): object storage (S3/Backblaze/Azure Blob). Minimal for embeddings-only flow.
- Do not bake large models into Docker images; download on startup if needed.

## Pipeline
1) Prepare corpus (jobs/skills texts).
2) Compute embeddings (all-MiniLM-L6-v2) and upsert to Qdrant (cosine).
3) At runtime:
   - Embed user answers.
   - Retrieve top-N from Qdrant.
   - Compute features (embed cosine, TF‑IDF cosine, mbti_fit, skills_overlap).
   - Blend with weights (or meta-model if trained).
   - Return ranked results + component breakdown.

## Why this fits the thesis
- Content-based filtering via TF‑IDF and semantic embeddings.
- Hybrid with MBTI and skills overlap (transparent, explainable).
- Optional supervised layer to learn weights from Pathfinder’s own data.

## Next Steps
- Create ML service (FastAPI) with /match and /health.
- Add Laravel client (config ML_SERVICE_URL; HTTP call to /match).
- Set up Qdrant Cloud; load corpus embeddings.
- Start collecting feedback labels for meta-model training.

## Open Choices
- Qdrant Cloud vs local FAISS (recommend Qdrant to start).
- Heuristic weights now vs meta-model later.
- Exact MBTI-to-role mapping and skills overlap calculation details.