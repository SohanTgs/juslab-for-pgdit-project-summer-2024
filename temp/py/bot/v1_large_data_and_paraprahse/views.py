import json
import numpy as np
from rest_framework.decorators import api_view
from rest_framework.response import Response
from sentence_transformers import SentenceTransformer
from transformers import T5ForConditionalGeneration, T5Tokenizer
import os

# === Load models ===
embedding_model = SentenceTransformer('all-MiniLM-L6-v2')
# paraphraser_tokenizer = T5Tokenizer.from_pretrained('ramsrigouthamg/t5_paraphraser')
# paraphraser_model = T5ForConditionalGeneration.from_pretrained('ramsrigouthamg/t5_paraphraser')

# === Paraphrasing function ===
def paraphrase(text, max_length=64):
    return text
    # input_text = f"paraphrase: {text} </s>"
    # input_ids = paraphraser_tokenizer.encode(input_text, return_tensors="pt", max_length=256, truncation=True)
    # output_ids = paraphraser_model.generate(input_ids, max_length=max_length, num_return_sequences=1, do_sample=True)
    # output_text = paraphraser_tokenizer.decode(output_ids[0], skip_special_tokens=True)
    # print(f"Paraphrased: {output_text}")   # <--- Add this debug print
    # return output_text

# === Load FAQ data ===
faq_path = os.path.join(os.path.dirname(__file__), "faqs.json")
with open(faq_path, encoding="utf-8", errors="replace") as f:
    faq_data = json.load(f)

faq_entries = [(item["question"].lower().strip(), item["answer"]) for item in faq_data]
questions = [q for q, a in faq_entries]
answers = [a for q, a in faq_entries]
question_embeddings = embedding_model.encode(questions)

@api_view(['POST', 'GET'])
def chat(request):
    print("request.content_type:", request.content_type)
    print("request.body:", request.body)
    print("request.data:", request.data)
    
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
        "reply": "I'm an XCash support assistant. Please try rephrasing your question.",
        "source": "fallback"
    })
