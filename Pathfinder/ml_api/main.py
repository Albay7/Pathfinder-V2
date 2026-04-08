from fastapi import FastAPI, HTTPException
from pydantic import BaseModel
import json
import os
import re
import math
from typing import List, Dict, Any

app = FastAPI(title="Pathfinder ML API")

# --- Globals for Model ---
MODEL_PATH = os.path.join(os.path.dirname(os.path.dirname(__file__)), 'storage', 'app', 'data', 'tfidf_model.json')

model_data = {}
vocab_index = {}
idf_values = []
centroids = {}
skill_flags = []
svc_coef = []
svc_intercept = []
svc_classes = []
has_svc = False

def load_model():
    global model_data, vocab_index, idf_values, centroids, skill_flags, svc_coef, svc_intercept, svc_classes, has_svc
    if not os.path.exists(MODEL_PATH):
        print(f"Error: Model not found at {MODEL_PATH}")
        return False
        
    try:
        with open(MODEL_PATH, 'r', encoding='utf-8') as f:
            model_data = json.load(f)
            
        vocab = model_data.get('vocabulary', [])
        vocab_index = {term: i for i, term in enumerate(vocab)}
        idf_values = model_data.get('idf_values', [])
        centroids = model_data.get('category_centroids', {})
        skill_flags = model_data.get('skill_flags', [])
        
        svc_coef = model_data.get('svc_coef', [])
        svc_intercept = model_data.get('svc_intercept', [])
        svc_classes = model_data.get('svc_classes', [])
        has_svc = len(svc_coef) > 0
        
        return True
    except Exception as e:
        print(f"Error loading model: {e}")
        return False

# Load model at startup
load_model()

# --- Helpers ---
def clean_text(text: str) -> str:
    text = str(text).lower()
    text = re.sub(r'[^a-z0-9\s\+\#\.]', ' ', text)
    text = re.sub(r'\s+', ' ', text)
    return text.strip()

class ResumeRequest(BaseModel):
    text: str

class MLResponse(BaseModel):
    top_category: str
    categories: Dict[str, float]
    skills: List[str]
    skill_vector: Dict[str, float]

def get_tfidf_vector(text: str) -> list:
    words = clean_text(text).split()
    counts = {}
    for w in words:
        if w in vocab_index:
            counts[w] = counts.get(w, 0) + 1

    vector = [0.0] * len(idf_values)
    for term, cnt in counts.items():
        idx = vocab_index[term]
        tf = 1 + math.log(cnt)
        vector[idx] = tf * idf_values[idx]
    return vector

def map_category_to_vector(category: str) -> Dict[str, float]:
    """Map ML Category to Laravel 12-dimensional vector"""
    # Initialize with zeros
    vector = {
        'programming': 0.0, 'web_development': 0.0, 'database': 0.0, 
        'cloud_devops': 0.0, 'mobile_development': 0.0, 'data_science': 0.0,
        'ui_ux': 0.0, 'project_management': 0.0, 'communication': 0.0,
        'leadership': 0.0, 'analytical_thinking': 0.0, 'problem_solving': 0.0
    }
    
    cat = category.upper()
    if cat == 'INFORMATION-TECHNOLOGY':
        vector['programming'] = 0.9
        vector['web_development'] = 0.8
        vector['database'] = 0.7
        vector['problem_solving'] = 0.8
    elif cat == 'ENGINEERING':
        vector['analytical_thinking'] = 0.9
        vector['problem_solving'] = 0.8
        vector['project_management'] = 0.5
    elif cat in ['FINANCE', 'ACCOUNTANT', 'BANKING']:
        vector['analytical_thinking'] = 0.9
        vector['database'] = 0.4
    elif cat == 'SALES' or cat == 'BUSINESS-DEVELOPMENT':
        vector['communication'] = 0.9
        vector['leadership'] = 0.6
    elif cat == 'HR':
        vector['communication'] = 0.9
        vector['project_management'] = 0.6
        vector['leadership'] = 0.5
    elif cat == 'DESIGNER' or cat == 'ARTS':
        vector['ui_ux'] = 0.9
        vector['web_development'] = 0.4
    elif cat in ['HEALTHCARE', 'FITNESS']:
        vector['communication'] = 0.8
        vector['analytical_thinking'] = 0.6
    elif cat == 'DIGITAL-MEDIA' or cat == 'PUBLIC-RELATIONS':
        vector['communication'] = 0.9
        vector['web_development'] = 0.3
    else:
        # Default fallback
        vector['communication'] = 0.5
        vector['problem_solving'] = 0.5
        
    return vector

@app.post("/api/analyze", response_model=MLResponse)
def analyze_resume(req: ResumeRequest):
    if not vocab_index:
        if not load_model():
            raise HTTPException(status_code=500, detail="ML Model not available.")
            
    text = req.text
    cv_vector = get_tfidf_vector(text)
    
    # 1. Cosine Scores
    import numpy as np
    np_cv_vector = np.array(cv_vector)
    np_skill_weights = np.array([10.0 if f else 1.0 for f in skill_flags])
    weighted_cv = np_cv_vector * np_skill_weights
    mag_cv = np.sqrt(np.sum(weighted_cv ** 2))
    
    cosine_scores = {}
    for cat, centroid_list in centroids.items():
        w_centroid = np.array(centroid_list) * np_skill_weights
        mag_ct = np.sqrt(np.sum(w_centroid ** 2))
        dot = np.sum(weighted_cv * w_centroid)
        cos_score = float(dot / (mag_cv * mag_ct)) if (mag_cv > 0 and mag_ct > 0) else 0.0
        cosine_scores[cat] = cos_score
        
    # 2. SVC Scores
    final_scores = {}
    if has_svc:
        np_svc_coef = np.array(svc_coef)
        np_svc_intercept = np.array(svc_intercept)
        raw_svc = np_svc_coef @ np_cv_vector + np_svc_intercept
        
        # Minmax normalization
        mn, mx = raw_svc.min(), raw_svc.max()
        if mx - mn < 1e-9:
            svc_norm = np.zeros_like(raw_svc)
        else:
            svc_norm = (raw_svc - mn) / (mx - mn)
            
        for i, cat in enumerate(svc_classes):
            cos_val = cosine_scores.get(cat, 0.0)
            svc_val = float(svc_norm[i])
            combined = 0.90 * svc_val + 0.10 * cos_val
            final_scores[cat] = combined
    else:
        final_scores = cosine_scores
        
    # Sort categories
    sorted_cats = sorted(final_scores.items(), key=lambda x: x[1], reverse=True)
    top_cat = sorted_cats[0][0] if sorted_cats else "OTHER"
    
    cat_dict = {cat: score for cat, score in sorted_cats[:5]}
    
    # 3. Detect Skills
    words = set(clean_text(text).split())
    # Identify skills present in document mapped by original vocabulary
    vocab = model_data.get('vocabulary', [])
    detected_skills = []
    for i, flag in enumerate(skill_flags):
        if flag and i < len(vocab):
            word = vocab[i]
            if word in words:
                detected_skills.append(word)
                
    # 4. Map to Laravel 12-dimensional vector based on Top Category and Skills
    skill_vector = map_category_to_vector(top_cat)
    
    # Optional dynamic boost based on detected skills
    if 'python' in detected_skills or 'javascript' in detected_skills or 'php' in detected_skills:
        skill_vector['programming'] = min(skill_vector['programming'] + 0.3, 1.0)
    if 'react' in detected_skills or 'html' in detected_skills or 'css' in detected_skills:
        skill_vector['web_development'] = min(skill_vector['web_development'] + 0.3, 1.0)
    if 'sql' in detected_skills or 'mysql' in detected_skills or 'database' in detected_skills:
        skill_vector['database'] = min(skill_vector['database'] + 0.3, 1.0)
    if 'aws' in detected_skills or 'docker' in detected_skills:
        skill_vector['cloud_devops'] = min(skill_vector['cloud_devops'] + 0.3, 1.0)

    return MLResponse(
        top_category=top_cat,
        categories=cat_dict,
        skills=detected_skills,
        skill_vector=skill_vector
    )
