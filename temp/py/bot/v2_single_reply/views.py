import json
import numpy as np
from rest_framework.decorators import api_view
from rest_framework.response import Response
from sentence_transformers import SentenceTransformer
from transformers import T5ForConditionalGeneration, T5Tokenizer
import os

# === Load models ===
embedding_model = SentenceTransformer('all-MiniLM-L6-v2')

# === Paraphrasing function ===
def paraphrase(text, max_length=64):
    return text

faq_folder = os.path.join(os.path.dirname(__file__), "datasets")
faq_data = []

for filename in os.listdir(faq_folder):
    if filename.endswith(".json"):
        file_path = os.path.join(faq_folder, filename)
        with open(file_path, encoding="utf-8", errors="replace") as f:
            try:
                data = json.load(f)
                if isinstance(data, list):
                    faq_data.extend(data)
                else:
                    print(f"Skipped {filename}: not a list of FAQs.")
            except Exception as e:
                print(f"Error loading {filename}: {e}")

faq_entries = [(item["question"].lower().strip(), item["answer"]) for item in faq_data]
questions = [q for q, a in faq_entries]
answers = [a for q, a in faq_entries]
question_embeddings = embedding_model.encode(questions)

@api_view(['GET'])
def chat(request):
    user_input = request.GET.get("message", "").strip().lower()
    print(f"User asked: {user_input}")

    # Exact match
    for q, a in faq_entries:
        if user_input == q:
            return Response({
                "reply": paraphrase(a),
                "source": "exact match"
            })

    # Cleaned punctuation match
    clean_input = user_input.rstrip('?')
    for q, a in faq_entries:
        if clean_input == q.rstrip('?'):
            return Response({
                "reply": paraphrase(a),
                "source": "cleaned match"
            })

    # Partial match
    for q, a in faq_entries:
        if clean_input in q or q in clean_input:
            return Response({
                "reply": paraphrase(a),
                "source": "partial match"
            })

    # Semantic match
    input_embedding = embedding_model.encode([user_input])
    scores = np.dot(question_embeddings, input_embedding.T).flatten()
    best_idx = np.argmax(scores)
    best_score = scores[best_idx]

    print(f"Best match: {questions[best_idx]} (score: {best_score})")

    if best_score > 0.4:
        matched_answer = answers[best_idx]
        paraphrased = paraphrase(matched_answer)
        return Response({
            "reply": paraphrased,
            "source": "semantic match"
        })

    return Response({
        "reply": "I'm the JU PGDIT assistant. Please clarify or rephrase your question.",
        "source": "fallback"
    })

    # return Response({
    #     "reply": "I'm the JU PGDIT assistant. Could you clarify or rephrase your question? For example, you can ask about: " 
    #             "\n- PGDIT admission requirements" 
    #             "\n- Course duration or fees" 
    #             "\n- Curriculum details",
    #     "source": "fallback"
    # })