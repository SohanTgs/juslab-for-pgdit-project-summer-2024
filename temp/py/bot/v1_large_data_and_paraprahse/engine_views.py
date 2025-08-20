from django.http import JsonResponse
from rest_framework.decorators import api_view
from .engine import get_best_answer

@api_view(['POST'])
def chat(request):
    message = request.data.get('message', '')
    if not message:
        return JsonResponse({"error": "No message provided."}, status=400)

    reply = get_best_answer(message)
    return JsonResponse({"reply": reply})
