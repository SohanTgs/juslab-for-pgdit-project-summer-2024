import json
import os
import torch
from sentence_transformers import SentenceTransformer, util

BASE_DIR = os.path.dirname(os.path.abspath(__file__))

# Load FAQ data
with open(os.path.join(BASE_DIR, 'faqs.json'), 'r', encoding='utf-8') as f:
    faq_data = json.load(f)

faq_questions = [item["question"] for item in faq_data]
faq_answers = [item["answer"] for item in faq_data]

# Load small transformer model (CPU-friendly)
model = SentenceTransformer('all-MiniLM-L6-v2')
faq_embeddings = model.encode(faq_questions, convert_to_tensor=True)

def get_best_answer(query: str, threshold: float = 0.45) -> str:
    query_embedding = model.encode(query, convert_to_tensor=True)
    similarity_scores = util.dot_score(query_embedding, faq_embeddings)[0]

    best_score = float(torch.max(similarity_scores))
    best_match_idx = int(torch.argmax(similarity_scores))

    print("Best score:", best_score)
    print("Best match:", faq_questions[best_match_idx])

    if best_score < threshold:
        return "Sorry, I couldn't find a relevant answer. Please try again."

    return faq_answers[best_match_idx]
