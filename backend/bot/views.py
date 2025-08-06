import json
import numpy as np
from rest_framework.decorators import api_view
from rest_framework.response import Response
from sentence_transformers import SentenceTransformer
from transformers import T5ForConditionalGeneration, T5Tokenizer
import os
import random 

# === Load models ===
# embedding_model = SentenceTransformer('all-MiniLM-L6-v2')
embedding_model = SentenceTransformer('all-mpnet-base-v2')

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

    # Function to generate suggestions based on user input
    def generate_suggestions(input_text):
        # Clean the input
        clean_input = input_text.rstrip('?').lower()
        
        # Find related questions based on word overlap
        related = []
        for q, a in faq_entries:
            q_words = set(q.split())
            input_words = set(clean_input.split())
            overlap = q_words.intersection(input_words)
            if len(overlap) >= 1 and q != clean_input:  # At least one common word and not the same question
                related.append(q)
        
        # Also include random questions if we don't have enough
        if len(related) < 3:
            random_questions = random.sample(questions, min(3, len(questions)))
            related.extend([q for q in random_questions if q not in related])
        
        # Deduplicate and limit to 3 suggestions
        suggestions = list(set(related))[:3]
        return suggestions

    # First try exact match (including case-insensitive)
    for q, a in faq_entries:
        if user_input == q:
            return Response({
                "reply": random.choice(a),
                "source": "exact match",
                "suggestions": generate_suggestions(user_input)
            })

    # Then try cleaned punctuation match
    clean_input = user_input.rstrip('?')
    for q, a in faq_entries:
        if clean_input == q.rstrip('?'):
            return Response({
                "reply": random.choice(a),
                "source": "cleaned match",
                "suggestions": generate_suggestions(user_input)
            })

    # Then try partial match (only if the input is at least 3 words)
    if len(user_input.split()) >= 3:
        for q, a in faq_entries:
            if clean_input in q or q in clean_input:
                return Response({
                    "reply": random.choice(a),
                    "source": "partial match",
                    "suggestions": generate_suggestions(user_input)
                })

    # Finally try semantic match with higher threshold for short questions
    input_embedding = embedding_model.encode([user_input])
    scores = np.dot(question_embeddings, input_embedding.T).flatten()
    best_idx = np.argmax(scores)
    best_score = scores[best_idx]
    
    # Adjust threshold based on question length
    threshold = 0.6 if len(user_input.split()) <= 3 else 0.4
    
    print(f"Best match: {questions[best_idx]} (score: {best_score})")

    if best_score > threshold:
        matched_answers = answers[best_idx]
        return Response({
            "reply": random.choice(matched_answers),
            "source": "semantic match",
            "suggestions": generate_suggestions(user_input)
        })

    # Fallback response
    fallbacks = [
        "I'm the JU PGDIT assistant. Could you clarify your question?",
        "Ask me about PGDIT admission, fees, or curriculum!",
        "Try asking: 'How to apply for PGDIT?' or 'What are the requirements?'"
    ]
    
    return Response({
        "reply": random.choice(fallbacks),
        "source": "fallback",
        "suggestions": random.sample(questions, min(4, len(questions)))
    })