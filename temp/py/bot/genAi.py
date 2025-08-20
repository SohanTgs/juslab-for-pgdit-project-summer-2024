import json
import numpy as np
from rest_framework.decorators import api_view
from rest_framework.response import Response
from sentence_transformers import SentenceTransformer
from transformers import T5ForConditionalGeneration, T5Tokenizer, pipeline
import os
import random
import warnings

# Suppress the specific warning
warnings.filterwarnings("ignore", category=FutureWarning, message="`resume_download` is deprecated")

# === Load Optimized Models ===
# For semantic understanding (using slightly smaller but still powerful model)
embedding_model = SentenceTransformer('BAAI/bge-base-en-v1.5', 
                                   device='cpu',
                                   cache_folder='./model_cache')

# For answer paraphrasing/generation (using base version for better CPanel compatibility)
paraphrase_model = T5ForConditionalGeneration.from_pretrained('google/flan-t5-base',
                                                            cache_dir='./model_cache')
paraphrase_tokenizer = T5Tokenizer.from_pretrained('google/flan-t5-base',
                                                 cache_dir='./model_cache')
paraphraser = pipeline("text2text-generation", 
                      model=paraphrase_model,
                      tokenizer=paraphrase_tokenizer,
                      device='cpu')

# === Data Loading ===
faq_folder = os.path.join(os.path.dirname(__file__), "datasets")
faq_data = []

for filename in os.listdir(faq_folder):
    if filename.endswith(".json"):
        file_path = os.path.join(faq_folder, filename)
        with open(file_path, encoding="utf-8", errors="replace") as f:
            try:
                data = json.load(f)
                faq_data.extend(data) if isinstance(data, list) else print(f"Skipped {filename}: not a list")
            except Exception as e:
                print(f"Error loading {filename}: {str(e)[:200]}...")  # Truncate long errors

faq_entries = [(item["question"].lower().strip(), item["answer"]) for item in faq_data if isinstance(item, dict)]
questions = [q for q, a in faq_entries]
answers = [a if isinstance(a, list) else [a] for q, a in faq_entries]  # Ensure answers are always lists

# Encode questions with prefix (for better semantic matching)
question_texts = ["query: " + q for q in questions]
question_embeddings = embedding_model.encode(question_texts, normalize_embeddings=True)

# === Enhanced Paraphrasing ===
def paraphrase_answer(answer, num_variations=1):
    """Generate paraphrased versions of an answer with error handling"""
    if not answer.strip():
        return answer
        
    try:
        prompt = f"Paraphrase this professionally: {answer}"
        return paraphraser(
            prompt,
            max_length=256,
            num_return_sequences=num_variations,
            temperature=0.7,
            do_sample=True
        )[0]['generated_text']
    except Exception as e:
        print(f"Paraphrasing failed: {str(e)[:200]}")
        return answer  # Fallback to original

# === Chat Endpoint ===
@api_view(['GET'])
def chat(request):
    user_input = request.GET.get("message", "").strip().lower()
    if not user_input:
        return Response({"reply": "Please enter your question", "suggestions": []})

    try:
        # 1. First try exact match
        for q, a in faq_entries:
            if user_input == q:
                return Response({
                    "reply": paraphrase_answer(random.choice(a)),
                    "source": "exact match",
                    "suggestions": generate_suggestions(user_input)
                })

        # 2. Semantic search
        input_embedding = embedding_model.encode(f"query: {user_input}", normalize_embeddings=True)
        scores = np.dot(question_embeddings, input_embedding.T).flatten()
        best_idx = np.argmax(scores)
        best_score = scores[best_idx]
        
        if best_score > 0.7:  # Good match threshold
            return Response({
                "reply": paraphrase_answer(random.choice(answers[best_idx])),
                "source": "semantic match",
                "confidence": float(best_score),
                "suggestions": generate_suggestions(user_input)
            })

        # 3. Fallback
        return Response({
            "reply": "I'm the PGDIT assistant. Try asking about admissions, curriculum, or fees.",
            "suggestions": generate_suggestions(user_input)
        })

    except Exception as e:
        print(f"Error processing query: {str(e)[:200]}")
        return Response({
            "reply": "I'm having trouble answering right now. Please try again later.",
            "suggestions": []
        })

def generate_suggestions(user_input, top_n=3):
    """Generate relevant suggestions with error handling"""
    try:
        if not questions:
            return []
            
        input_embedding = embedding_model.encode(f"query: {user_input}", normalize_embeddings=True)
        scores = np.dot(question_embeddings, input_embedding.T).flatten()
        return [questions[i] for i in np.argsort(scores)[-top_n:][::-1] if scores[i] > 0.5]
    except:
        return random.sample(questions, min(top_n, len(questions)))