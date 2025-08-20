<style>
    .juslab-root {
        --juslab-primary: #0056b3;
        --juslab-secondary: #003366;
        --juslab-accent: #ff6b35;
        --juslab-light: #f8f9fa;
        --juslab-dark: #212529;
    }

    .juslab-bg-gradient {
        background: linear-gradient(135deg, var(--juslab-primary), var(--juslab-secondary));
    }

    .juslab-text-accent {
        color: var(--juslab-accent);
    }

    .juslab-btn {
        background-color: var(--juslab-accent);
        color: white;
        border: none;
        padding: 10px 25px;
        border-radius: 30px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .juslab-btn:hover {
        background-color: #e05a2b;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255, 107, 53, 0.3);
        color: white;
    }

    .juslab-feature-icon {
        font-size: 2.5rem;
        color: var(--juslab-primary);
        margin-bottom: 1rem;
    }

    .juslab-card-hover {
        transition: all 0.3s;
        border: none;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    .juslab-chatbot-container {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 1000;
        width: 380px;
        max-width: 90%;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 15px;
    }

    .juslab-chatbot-toggle {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--juslab-primary), var(--juslab-secondary));
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 10px 25px rgba(0, 86, 179, 0.3);
        border: none;
        z-index: 1001;
        transition: all 0.3s;
    }

    .juslab-chatbot-toggle:hover {
        transform: scale(1.1) rotate(10deg);
    }

    .juslab-chatbot-toggle i {
        font-size: 1.8rem;
        transition: all 0.3s;
    }

    .juslab-chatbot-toggle.active i {
        transform: rotate(360deg);
    }

    .juslab-chatbot-window {
        background-color: white;
        border-radius: 20px;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        overflow: hidden;
        transform: translateY(100%);
        opacity: 0;
        height: 0;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        display: flex;
        flex-direction: column;
    }

    .juslab-chatbot-window.active {
        transform: translateY(0);
        opacity: 1;
        height: 550px;
    }

    .juslab-chatbot-header {
        background: linear-gradient(135deg, var(--juslab-primary), var(--juslab-secondary));
        color: white;
        padding: 1.2rem;
        display: flex;
        align-items: center;
        position: relative;
    }

    .juslab-chatbot-header h5 {
        margin: 0 0 0 15px;
        font-weight: 600;
    }

    .juslab-chatbot-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--juslab-primary);
        font-size: 1.2rem;
    }

    .juslab-chatbot-body {
        flex: 1;
        overflow-y: auto;
        padding: 1.5rem;
        background-color: #f8fafc;
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .juslab-chatbot-footer {
        padding: 1.2rem;
        background-color: white;
        border-top: 1px solid #e9ecef;
        position: relative;
    }

    .juslab-chatbot-footer::before {
        content: '';
        position: absolute;
        top: -10px;
        left: 0;
        right: 0;
        height: 10px;
        background: linear-gradient(to bottom, rgba(248, 250, 252, 0), rgba(248, 250, 252, 1));
    }

    .juslab-chatbot-input {
        width: 100%;
        padding: 0.8rem 1.2rem;
        border: 1px solid #dee2e6;
        border-radius: 30px;
        outline: none;
        transition: all 0.3s;
    }

    .juslab-chatbot-input:focus {
        border-color: var(--juslab-primary);
        box-shadow: 0 0 0 0.25rem rgba(0, 86, 179, 0.25);
    }

    .juslab-message {
        max-width: 80%;
        padding: 0.8rem 1.2rem;
        border-radius: 18px;
        line-height: 1.5;
        position: relative;
        animation: juslab-fadeIn 0.3s ease-out;
    }

    @keyframes juslab-fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .juslab-user-message {
        background-color: var(--juslab-primary);
        color: white;
        margin-left: auto;
        border-bottom-right-radius: 5px;
    }

    .juslab-bot-message {
        background-color: white;
        border: 1px solid #e9ecef;
        margin-right: auto;
        border-bottom-left-radius: 5px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .juslab-quick-questions {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 10px;
    }

    .juslab-quick-question {
        background-color: #e9ecef;
        border: none;
        border-radius: 20px;
        padding: 6px 12px;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.2s;
        white-space: nowrap;
    }

    .juslab-quick-question:hover {
        background-color: #dee2e6;
    }

    .juslab-typing-indicator {
        display: flex;
        padding: 0.8rem 1.2rem;
        background-color: white;
        border-radius: 18px;
        border: 1px solid #e9ecef;
        width: fit-content;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        margin-bottom: 15px;
    }

    .juslab-typing-dot {
        width: 8px;
        height: 8px;
        background-color: #adb5bd;
        border-radius: 50%;
        margin: 0 3px;
        animation: juslab-typing 1.4s infinite ease-in-out;
    }

    .juslab-typing-dot:nth-child(1) {
        animation-delay: 0s;
    }

    .juslab-typing-dot:nth-child(2) {
        animation-delay: 0.2s;
    }

    .juslab-typing-dot:nth-child(3) {
        animation-delay: 0.4s;
    }

    @keyframes juslab-typing {
        0%, 60%, 100% {
            transform: translateY(0);
        }
        30% {
            transform: translateY(-5px);
        }
    }

    .juslab-message-time {
        font-size: 0.7rem;
        opacity: 0.7;
        margin-top: 4px;
        text-align: right;
    }

    .juslab-user-message .juslab-message-time {
        color: rgba(255, 255, 255, 0.7);
    }

    .juslab-bot-message .juslab-message-time {
        color: rgba(0, 0, 0, 0.6);
    }

    .juslab-compact-suggestions {
        margin-top: 8px;
        font-size: 0.85rem;
    }

    .juslab-compact-suggestions-title {
        color: #6c757d;
        margin-bottom: 4px;
    }

    .juslab-compact-suggestion {
        display: inline-block;
        background-color: #f1f3f5;
        border-left: 3px solid var(--juslab-accent);
        padding: 4px 8px;
        margin: 2px 4px 2px 0;
        cursor: pointer;
        transition: all 0.2s;
        border-radius: 2px;
    }

    .juslab-compact-suggestion:hover {
        background-color: #e9ecef;
        transform: translateX(2px);
    }

    .juslab-recording {
        animation: juslab-pulse 1.5s infinite;
        background-color: #dc3545 !important;
        color: white !important;
    }

    @keyframes juslab-pulse {
        0% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.1);
        }
        100% {
            transform: scale(1);
        }
    }

    @media (max-width: 768px) {
        .juslab-chatbot-container {
            right: 20px;
            bottom: 20px;
            width: 90%;
        }

        .juslab-chatbot-window.active {
            height: 450px;
        }

        .juslab-chatbot-body {
            height: 320px;
            padding: 1rem;
        }

        .juslab-chatbot-toggle {
            width: 60px;
            height: 60px;
        }

        .juslab-chatbot-footer {
            padding: 1rem;
        }

        .juslab-chatbot-input {
            padding: 0.7rem 1rem;
        }

        .juslab-quick-question {
            padding: 5px 10px;
            font-size: 0.8rem;
        }
    }

    @media (max-width: 360px) {
        .juslab-chatbot-header h5 {
            margin: 0 0 0 10px;
        }
    }
</style>

<!-- Chatbot Container -->
<div class="juslab-chatbot-container">
    <button class="juslab-chatbot-toggle" id="juslabChatbotToggle">
        <i class="fas fa-comment-dots"></i>
    </button>

    <div class="juslab-chatbot-window" id="juslabChatbotWindow">
        <div class="juslab-chatbot-header">
            <div class="juslab-chatbot-avatar">
                <i class="fas fa-robot"></i>
            </div>
            <h5 style="color:white;">JusLab (PGDIT assistant)</h5>
        </div>

        <div class="juslab-chatbot-body" id="juslabChatbotBody">
            <div class="juslab-message juslab-bot-message">
                Hello! I'm the JusLab (PGDIT assistant). How can I help you today?
                <div class="juslab-message-time"></div>
            </div>
            <div class="juslab-message juslab-bot-message">
                You can ask me about:
                <div class="juslab-quick-questions">
                    <button class="juslab-quick-question" onclick="juslabSendQuickQuestion('What is PGDIT?')">What is PGDIT?</button>
                    <button class="juslab-quick-question" onclick="juslabSendQuickQuestion('Who can apply?')">Eligibility</button>
                    <button class="juslab-quick-question" onclick="juslabSendQuickQuestion('How to apply?')">How to apply?</button>
                    <button class="juslab-quick-question" onclick="juslabSendQuickQuestion('Class schedule?')">Class Schedule</button>
                </div>
                <div class="juslab-message-time"></div>
            </div>
        </div>
        <div class="juslab-chatbot-footer">
            <form id="juslabChatbotForm" style="display: flex; align-items: center; gap: 8px;">
                <input type="text" class="juslab-chatbot-input" id="juslabUserInput" placeholder="Ask about PGDIT program..." autocomplete="off" style="flex-grow: 1;">
                <button type="button" id="juslabVoiceButton" style="
                    width: 50px; 
                    height: 50px;
                    border-radius: 50%;
                    padding: 0;
                    flex-shrink: 0;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    background: transparent;
                    border: 1px solid #dee2e6;
                    cursor: pointer;
                ">
                    <i class="fas fa-microphone" style="font-size: 1.1rem;"></i>
                </button>
                <button type="submit" class="juslab-btn" style="
                    width: 50px; 
                    height: 50px;
                    border-radius: 50%;
                    padding: 0;
                    flex-shrink: 0;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                ">
                    <i class="fas fa-paper-plane" style="font-size: 1.1rem;"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    // Chatbot functionality
    (function() {
        const chatbotToggle = document.getElementById('juslabChatbotToggle');
        const chatbotWindow = document.getElementById('juslabChatbotWindow');
        const chatbotBody = document.getElementById('juslabChatbotBody');
        const userInput = document.getElementById('juslabUserInput');
        const voiceButton = document.getElementById('juslabVoiceButton');

        let isChatbotOpen = false;
        let isVoiceInput = false;
        let recognition;
        let isRecording = false;
        let currentSpeechUtterance = null;

        // Initialize speech recognition
        function initSpeechRecognition() {
            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

            if (SpeechRecognition) {
                recognition = new SpeechRecognition();
                recognition.continuous = false;
                recognition.interimResults = false;
                recognition.lang = 'en-US';

                recognition.onstart = () => {
                    isRecording = true;
                    voiceButton.classList.add('juslab-recording');
                    voiceButton.innerHTML = '<i class="fas fa-stop" style="font-size: 1.1rem;"></i>';
                    userInput.placeholder = "Listening...";
                };

                recognition.onresult = (event) => {
                    const transcript = event.results[0][0].transcript;
                    userInput.value = transcript;
                };

                recognition.onerror = (event) => {
                    console.error('Speech recognition error', event.error);
                    juslabAddMessage("Sorry, I couldn't understand your voice. Please try again.", 'bot');
                };

                recognition.onend = () => {
                    stopRecording();
                    if (userInput.value.trim() !== '') {
                        juslabSendMessage(userInput.value.trim());
                        userInput.value = '';
                    }
                };
            } else {
                voiceButton.style.display = 'none';
                console.warn('Speech recognition not supported in this browser');
            }
        }

        function startRecording() {
            if (!recognition) {
                initSpeechRecognition();
            }
            isVoiceInput = true;
            try {
                recognition.start();
            } catch (error) {
                console.error('Speech recognition error:', error);
                stopRecording();
            }
        }

        function stopRecording() {
            if (recognition && isRecording) {
                try {
                    recognition.stop();
                } catch (error) {
                    console.error('Error stopping recognition:', error);
                }
            }
            isRecording = false;
            voiceButton.classList.remove('juslab-recording');
            voiceButton.innerHTML = '<i class="fas fa-microphone" style="font-size: 1.1rem;"></i>';
            userInput.placeholder = "Ask about PGDIT program...";
        }

        // Toggle voice recording
        voiceButton.addEventListener('click', () => {
            if (isRecording) {
                stopRecording();
            } else {
                startRecording();
            }
        });

        // Toggle chatbot window with animation
        chatbotToggle.addEventListener('click', () => {
            isChatbotOpen = !isChatbotOpen;
            chatbotWindow.classList.toggle('active', isChatbotOpen);
            chatbotToggle.classList.toggle('active', isChatbotOpen);

            if (isChatbotOpen) {
                chatbotToggle.innerHTML = '<i class="fas fa-times"></i>';
            } else {
                chatbotToggle.innerHTML = '<i class="fas fa-comment-dots"></i>';
            }
        });

        // Handle form submission
        document.getElementById('juslabChatbotForm').addEventListener('submit', function (e) {
            e.preventDefault();
            if (userInput.value.trim() !== '') {
                isVoiceInput = false;
                juslabSendMessage(userInput.value.trim());
                userInput.value = '';
            }
        });

        // Send message when Enter is pressed
        userInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter' && userInput.value.trim() !== '') {
                isVoiceInput = false;
                juslabSendMessage(userInput.value.trim());
                userInput.value = '';
            }
        });

        // Global function for quick questions
        window.juslabSendQuickQuestion = function(question) {
            isVoiceInput = false;
            juslabSendMessage(question);
        };

        function juslabSendMessage(message) {
            // Stop any ongoing speech
            stopSpeech();

            // Add user message to chat
            juslabAddMessage(message, 'user');

            // Show typing indicator
            juslabShowTypingIndicator();

            // Call API
            fetch(`https://sohan.thesoftking.com/chatbot/api/chat/?message=${encodeURIComponent(message)}`)
                .then(response => response.json())
                .then(data => {
                    juslabRemoveTypingIndicator();
                    juslabAddMessage(data.reply, 'bot');
                    if (isVoiceInput) {
                        speak(data.reply);
                    }
                    if (data.suggestions) juslabAddSuggestions(data.suggestions);
                    isVoiceInput = false;
                })
                .catch(error => {
                    juslabRemoveTypingIndicator();
                    juslabAddMessage("Sorry, I'm having trouble connecting. Please try again later.", 'bot');
                    console.error('Error:', error);
                    isVoiceInput = false;
                });
        }

        function speak(message) {
            if ('speechSynthesis' in window) {
                window.speechSynthesis.cancel();
                currentSpeechUtterance = null;

                const msg = new SpeechSynthesisUtterance();
                msg.text = message;
                msg.volume = 1;
                msg.rate = 1;
                msg.pitch = 1;

                currentSpeechUtterance = msg;

                msg.onend = function () {
                    currentSpeechUtterance = null;
                };

                msg.onerror = function (event) {
                    console.error('SpeechSynthesis error:', event);
                    currentSpeechUtterance = null;
                };

                const voices = window.speechSynthesis.getVoices();
                if (voices.length > 0) {
                    const englishVoice = voices.find(voice =>
                        voice.lang.includes('en') || voice.lang.includes('EN')
                    );
                    if (englishVoice) msg.voice = englishVoice;
                    window.speechSynthesis.speak(msg);
                } else {
                    window.speechSynthesis.onvoiceschanged = function () {
                        const availableVoices = window.speechSynthesis.getVoices();
                        const englishVoice = availableVoices.find(voice =>
                            voice.lang.includes('en') || voice.lang.includes('EN')
                        );
                        if (englishVoice) msg.voice = englishVoice;
                        window.speechSynthesis.speak(msg);
                    };
                }
            }
        }

        function stopSpeech() {
            if ('speechSynthesis' in window && currentSpeechUtterance) {
                window.speechSynthesis.cancel();
                currentSpeechUtterance = null;
            }
        }

        function juslabAddSuggestions(suggestions) {
            const suggestionsDiv = document.createElement('div');
            suggestionsDiv.classList.add('juslab-compact-suggestions');

            let html = '<div class="juslab-compact-suggestions-title">Related queries:</div>';

            suggestions.forEach(suggestion => {
                html += `<div class="juslab-compact-suggestion" onclick="juslabSendQuickQuestion('${suggestion}')">${suggestion}</div>`;
            });

            suggestionsDiv.innerHTML = html;

            const botMessages = document.querySelectorAll('.juslab-bot-message');
            if (botMessages.length > 0) {
                const lastBotMessage = botMessages[botMessages.length - 1];
                lastBotMessage.appendChild(suggestionsDiv);
            }

            chatbotBody.scrollTop = chatbotBody.scrollHeight;
        }

        function juslabAddMessage(text, sender) {
            const messageDiv = document.createElement('div');
            messageDiv.classList.add('juslab-message', `juslab-${sender}-message`);

            const messageContent = document.createElement('div');
            messageContent.textContent = text;

            const timeDiv = document.createElement('div');
            timeDiv.classList.add('juslab-message-time');
            timeDiv.textContent = getCurrentTime();

            messageDiv.appendChild(messageContent);
            messageDiv.appendChild(timeDiv);

            chatbotBody.appendChild(messageDiv);
            chatbotBody.scrollTop = chatbotBody.scrollHeight;
        }

        function getCurrentTime() {
            const now = new Date();
            let hours = now.getHours();
            let minutes = now.getMinutes();
            const ampm = hours >= 12 ? 'PM' : 'AM';

            hours = hours % 12;
            hours = hours ? hours : 12;
            minutes = minutes < 10 ? '0' + minutes : minutes;

            return `${hours}:${minutes} ${ampm}`;
        }

        function juslabShowTypingIndicator() {
            const typingDiv = document.createElement('div');
            typingDiv.classList.add('juslab-typing-indicator');
            typingDiv.id = 'juslabTypingIndicator';

            for (let i = 0; i < 3; i++) {
                const dot = document.createElement('div');
                dot.classList.add('juslab-typing-dot');
                typingDiv.appendChild(dot);
            }

            chatbotBody.appendChild(typingDiv);
            chatbotBody.scrollTop = chatbotBody.scrollHeight;
        }

        function juslabRemoveTypingIndicator() {
            const typingIndicator = document.getElementById('juslabTypingIndicator');
            if (typingIndicator) {
                typingIndicator.remove();
            }
        }

        // Stop speech when page unloads
        window.addEventListener('beforeunload', stopSpeech);
    })();
</script>