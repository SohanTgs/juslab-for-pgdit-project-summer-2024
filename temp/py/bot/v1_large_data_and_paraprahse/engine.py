import os
import re
import json
import torch
from sentence_transformers import SentenceTransformer, util
from transformers import PegasusTokenizer, PegasusForConditionalGeneration

# Load embedding model
embedding_model = SentenceTransformer('all-MiniLM-L6-v2')

# Load Pegasus paraphrasing model
paraphrase_model_name = 'tuner007/pegasus_paraphrase'
tokenizer = PegasusTokenizer.from_pretrained(paraphrase_model_name)
paraphrase_model = PegasusForConditionalGeneration.from_pretrained(paraphrase_model_name)

# Load FAQs
BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
FAQ_PATH = os.path.join(BASE_DIR, 'faqs.json')

# Load FAQs
# Load FAQs
try:
    with open(FAQ_PATH, 'r', encoding='utf-8') as f:
        faq_data = json.load(f)
except Exception as e:
    print("❌ Failed to load faqs.json:", str(e))
    faq_data = []

faq_questions = [item['question'] for item in faq_data]
faq_answers = [item['answer'] for item in faq_data]
faq_embeddings = embedding_model.encode(faq_questions, convert_to_tensor=True)

# Paraphrasing function
def paraphrase_text(text):
    inputs = tokenizer([text], truncation=True, padding='longest', return_tensors="pt")
    with torch.no_grad():
        outputs = paraphrase_model.generate(**inputs, max_length=60, num_return_sequences=1, temperature=1.5)
    return tokenizer.decode(outputs[0], skip_special_tokens=True)

# Core answer function
def clean_text(text):
    """Lowercase and remove punctuation"""
    return re.sub(r'[^\w\s]', '', text.strip().lower())

def get_best_answer(query: str, threshold: float = 0.45) -> str:
    query_clean = clean_text(query)

    # Step 1: Try exact-ish match (ignores punctuation and case)
    for i, q in enumerate(faq_questions):
        if clean_text(q) == query_clean:
            return paraphrase_text(faq_answers[i])  # or return directly

    # Step 2: Similarity matching if no direct match
    query_embedding = embedding_model.encode(query, convert_to_tensor=True)
    similarity_scores = util.dot_score(query_embedding, faq_embeddings)[0]

    best_score = float(torch.max(similarity_scores))
    best_match_idx = int(torch.argmax(similarity_scores))

    if best_score < threshold:
        return "Sorry, I couldn't find a relevant answer. Please try again."

    return paraphrase_text(faq_answers[best_match_idx])