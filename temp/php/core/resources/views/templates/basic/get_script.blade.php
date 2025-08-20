<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

    :root {
        --ju-primary: #0056b3;
        --ju-secondary: #003366;
        --ju-accent: #ff6b35;
        --ju-light: #f8f9fa;
        --ju-dark: #212529;
    }

    /* CHATBOT STYLES */
    .jusLab.chatbot-container {
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

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .jusLab.chatbot-container {
            right: 20px;
            bottom: 20px;
            width: 90%;
        }
    }


    .jusLab {
        * {
            font-family: "Poppins", sans-serif;
        }

        h5 {
            font-size: 1.25rem;
        }


        .bg-ju-gradient {
            background: linear-gradient(135deg, var(--ju-primary), var(--ju-secondary));
        }

        .text-ju-accent {
            color: var(--ju-accent);
        }

        .btn-ju {
            background-color: var(--ju-accent);
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 30px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-ju:hover {
            background-color: #e05a2b;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 107, 53, 0.3);
            color: white;
        }

        .feature-icon {
            font-size: 2.5rem;
            color: var(--ju-primary);
            margin-bottom: 1rem;
        }

        .card-hover {
            transition: all 0.3s;
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .card-hover:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .timeline {
            position: relative;
            padding-left: 3rem;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 1.5rem;
            top: 0;
            bottom: 0;
            width: 2px;
            background: var(--ju-primary);
        }

        .timeline-item {
            position: relative;
            margin-bottom: 2rem;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -3rem;
            top: 0.3rem;
            width: 1.2rem;
            height: 1.2rem;
            border-radius: 50%;
            background: var(--ju-accent);
            border: 3px solid white;
        }

        .chatbot-toggle {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--ju-primary), var(--ju-secondary));
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

        .chatbot-toggle:hover {
            transform: scale(1.1) rotate(10deg);
        }

        .chatbot-toggle i {
            font-size: 1.8rem;
            transition: all 0.3s;
        }

        .chatbot-toggle.active i {
            transform: rotate(360deg);
        }

        .chatbot-window {
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

        .chatbot-window.active {
            transform: translateY(0);
            opacity: 1;
            height: 550px;
        }

        .chatbot-header {
            background: linear-gradient(135deg, var(--ju-primary), var(--ju-secondary));
            color: white;
            padding: 1.2rem;
            display: flex;
            align-items: center;
            position: relative;
        }

        .chatbot-header h5 {
            margin: 0 0 0 15px;
            font-weight: 600;
        }

        .chatbot-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--ju-primary);
            font-size: 1.2rem;
        }

        .chatbot-body {
            flex: 1;
            overflow-y: auto;
            padding: 1.5rem;
            background-color: #f8fafc;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .chatbot-footer {
            padding: 1.2rem;
            background-color: white;
            border-top: 1px solid #e9ecef;
            position: relative;
        }

        .chatbot-footer::before {
            content: '';
            position: absolute;
            top: -10px;
            left: 0;
            right: 0;
            height: 10px;
            background: linear-gradient(to bottom, rgba(248, 250, 252, 0), rgba(248, 250, 252, 1));
        }

        .chatbot-input {
            width: 100%;
            padding: 1rem 1.2rem;
            border: 1px solid #dee2e6;
            border-radius: 30px;
            outline: none;
            transition: all 0.3s;
        }

        .chatbot-input:focus {
            border-color: var(--ju-primary);
            box-shadow: 0 0 0 0.25rem rgba(0, 86, 179, 0.25);
        }

        .message {
            max-width: 80%;
            padding: 0.8rem 1.2rem;
            border-radius: 18px;
            line-height: 1.5;
            position: relative;
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .user-message {
            background-color: var(--ju-primary);
            color: white;
            margin-left: auto;
            border-bottom-right-radius: 5px;
        }

        .bot-message {
            background-color: white;
            border: 1px solid #e9ecef;
            margin-right: auto;
            border-bottom-left-radius: 5px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        /* Improved quick questions section */
        .quick-questions-intro {
            margin-bottom: 8px;
            /* Space between text and buttons */
        }

        .quick-questions-container {
            margin-top: 10px;
            /* Space above quick questions */
        }

        .quick-questions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            /* Space between buttons */
        }

        .quick-question {
            background-color: #e9ecef;
            border: none;
            border-radius: 20px;
            padding: 6px 12px;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.2s;
            white-space: nowrap;
            color: black;
        }

        .quick-question:hover {
            background-color: #dee2e6;
            color: black;
        }

        .typing-indicator {
            display: flex;
            padding: 0.8rem 1.2rem;
            background-color: white;
            border-radius: 18px;
            border: 1px solid #e9ecef;
            width: fit-content;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            margin-bottom: 15px;
        }

        .typing-dot {
            width: 8px;
            height: 8px;
            background-color: #adb5bd;
            border-radius: 50%;
            margin: 0 3px;
            animation: juslab_typing 1.4s infinite ease-in-out;
        }

        .typing-dot:nth-child(1) {
            animation-delay: 0s;
        }

        .typing-dot:nth-child(2) {
            animation-delay: 0.2s;
        }

        .typing-dot:nth-child(3) {
            animation-delay: 0.4s;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .chatbot-window.active {
                height: 450px;
            }

            .chatbot-body {
                height: 320px;
                padding: 1rem;
            }

            .chatbot-toggle {
                width: 60px;
                height: 60px;
            }

            .chatbot-footer {
                padding: 1rem;
            }

            .chatbot-input {
                padding: 0.7rem 1rem;
            }

            .quick-question {
                padding: 5px 10px;
                font-size: 0.8rem;
            }
        }

        .quick-questions-intro {
            font-size: 0.9rem;
            color: #6c757d;
            margin-bottom: 8px;
        }

        .quick-questions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 5px;
        }

        .quick-question {
            background-color: #e9ecef;
            border: none;
            border-radius: 20px;
            padding: 6px 12px;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.2s;
            white-space: normal;
            text-align: left;
        }

        .quick-question:hover {
            background-color: #dee2e6;
            transform: translateY(-1px);
        }

        .message-time {
            font-size: 0.7rem;
            opacity: 0.7;
            margin-top: 4px;
            text-align: right;
        }

        .user-message .message-time {
            color: rgba(255, 255, 255, 0.7);
        }

        .bot-message .message-time {
            color: rgba(0, 0, 0, 0.6);
        }

        /* Compact suggestion style */
        .compact-suggestions {
            margin-top: 8px;
            font-size: 0.85rem;
        }

        .compact-suggestions-title {
            color: #6c757d;
            margin-bottom: 4px;
        }

        .compact-suggestion {
            display: inline-block;
            background-color: #f1f3f5;
            border-left: 3px solid var(--ju-accent);
            padding: 4px 8px;
            margin: 2px 4px 2px 0;
            cursor: pointer;
            transition: all 0.2s;
            border-radius: 2px;
        }

        .compact-suggestion:hover {
            background-color: #e9ecef;
            transform: translateX(2px);
        }

        .recording {
            animation: pulse 1.5s infinite;
            background-color: #dc3545 !important;
            color: white !important;
        }

        @keyframes pulse {
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

        @media(max-width: 360px) {
            .chatbot-header h5 {
                margin: 0 0 0 10px;
            }
        }

        .chatbot-wrapper {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 0;
        }

        button {
            cursor: pointer;
            background-color: transparent;
            outline: none;
            border: 1px solid #6c757d;
            color: #6c757d;
        }

        button:hover {
            color: #fff;
            background-color: #6c757d;
        }
        .chatbot-footer-branding {
            display: flex;
            align-items: center;
            justify-content: space-between;
            /*gap: 5px;*/
            margin-top: 10px;
            font-size: 0.7rem;
            color: #6c757d;
            flex-wrap: wrap;
        }
        
        .chatbot-footer-branding a {
            color: var(--ju-primary);
            text-decoration: underline;
            font-weight: 500;
            transition: color 0.2s;
            font-size: 0.7rem;
        }
        
        .chatbot-footer-branding a:hover {
            color: var(--ju-accent);
        }
        
        .separator {
            margin: 0 5px;
            color: #ced4da;
        }
        
        .program-info {
            font-style: italic;
        }
    }
    
      @keyframes juslab_typing {

            0%,
            60%,
            100% {
                transform: translateY(0);
            }

            30% {
                transform: translateY(-5px);
            }
        }
</style>

<!-- Chatbot Container -->
<div class="chatbot-container jusLab">
    <button class="chatbot-toggle" id="chatbotToggle">
        <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="lucide lucide-message-circle-more-icon lucide-message-circle-more">
            <path
                d="M2.992 16.342a2 2 0 0 1 .094 1.167l-1.065 3.29a1 1 0 0 0 1.236 1.168l3.413-.998a2 2 0 0 1 1.099.092 10 10 0 1 0-4.777-4.719" />
            <path d="M8 12h.01" />
            <path d="M12 12h.01" />
            <path d="M16 12h.01" />
        </svg>
    </button>

    <div class="chatbot-window" id="chatbotWindow">
        <div class="chatbot-header">
            <div class="chatbot-avatar">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="lucide lucide-bot-icon lucide-bot">
                    <path d="M12 8V4H8" />
                    <rect width="16" height="12" x="4" y="8" rx="2" />
                    <path d="M2 14h2" />
                    <path d="M20 14h2" />
                    <path d="M15 13v2" />
                    <path d="M9 13v2" />
                </svg>
            </div>
            <h5 style="color:white;">Ask me a question</h5>
        </div>

        <div class="chatbot-body" id="chatbotBody">
            <div class="message bot-message">
                Hello! I'm the JusLab (PGDIT assistant). How can I help you today?
                <div class="message-time"></div>
            </div>
            <div class="message bot-message">
                You can ask me about:
                <div class="quick-questions">
                    <button class="quick-question" onclick="sendQuickQuestion(this.innerText)">What is PGDIT?</button>
                    <button class="quick-question" onclick="sendQuickQuestion(this.innerText)">Eligibility?</button>
                    <button class="quick-question" onclick="sendQuickQuestion(this.innerText)">Course fees?</button>
                    <button class="quick-question" onclick="sendQuickQuestion(this.innerText)">Class Schedule?</button>
                </div>
                <div class="message-time"></div>
            </div>
        </div>
        <div class="chatbot-footer">
            <form id="chatbotForm" class="chatbot-wrapper">
                <input type="text" class="chatbot-input flex-grow-1" id="userInput"
                    placeholder="Ask about PGDIT program..." autocomplete="off">
                <button type="button" id="voiceButton"
                    class="btn btn-outline-secondary d-flex align-items-center justify-content-center" style="
                        width: 50px; 
                        height: 50px;
                        border-radius: 50%;
                        padding: 0;
                        flex-shrink: 0;
                    ">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-mic-icon lucide-mic">
                        <path d="M12 19v3" />
                        <path d="M19 10v2a7 7 0 0 1-14 0v-2" />
                        <rect x="9" y="2" width="6" height="13" rx="3" />
                    </svg>
                </button>
                <button type="submit" class="btn btn-ju d-flex align-items-center justify-content-center" style="
                        width: 50px; 
                        height: 50px;
                        border-radius: 50%;
                        padding: 0;
                        flex-shrink: 0;
                    ">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-send-icon lucide-send">
                        <path
                            d="M14.536 21.686a.5.5 0 0 0 .937-.024l6.5-19a.496.496 0 0 0-.635-.635l-19 6.5a.5.5 0 0 0-.024.937l7.93 3.18a2 2 0 0 1 1.112 1.11z" />
                        <path d="m21.854 2.147-10.94 10.939" />
                    </svg>
                </button>
            </form>
            <!-- Add this inside your chatbot-footer div, after the form -->
            <div class="chatbot-footer-branding">
                <div>
                    <span class="program-info">
                        Supervised by <a href="https://www.juniv.edu/teachers/shamim" target="_blank">Prof. Shamim Al Mamun, PhD</a>
                    </span>
                </div>
                <div>
                    <span>Developed by</span>
                    <a href="https://www.facebook.com/tgs.sohan/" target="_blank">Sohan</a>
                    <span>and</span>
                    <a href="https://www.facebook.com/thisiswali" target="_blank">Wali</a>
                </div>
                <span class="program-info">PGDIT Summer 2024</span>
            </div>
        </div>
    </div>
</div>

<script>
    // Chatbot functionality
    const jusLabChatbotToggle = document.getElementById('chatbotToggle');
    const jusLabChatbotWindow = document.getElementById('chatbotWindow');
    const jusLabChatbotBody = document.getElementById('chatbotBody');
    const jusLabUserInput = document.getElementById('userInput');

    let jusLabIsChatbotOpen = false;
    let jusLabIsVoiceInput = false;

    // Voice recognition functionality
    const jusLabVoiceButton = document.getElementById('voiceButton');
    let jusLabRecognition;
    let jusLabIsRecording = false;

    // Initialize speech recognition
    function jusLabInitSpeechRecognition() {
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

        if (SpeechRecognition) {
            jusLabRecognition = new SpeechRecognition();
            jusLabRecognition.continuous = false;
            jusLabRecognition.interimResults = false;
            jusLabRecognition.lang = 'en-US';

            jusLabRecognition.onstart = () => {
                jusLabIsRecording = true;
                jusLabVoiceButton.classList.add('recording');
                jusLabVoiceButton.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-square-icon lucide-square"><rect width="18" height="18" x="3" y="3" rx="2"/></svg>
            `;
                jusLabUserInput.placeholder = "Listening...";
            };

            jusLabRecognition.onresult = (event) => {
                const transcript = event.results[0][0].transcript;
                jusLabUserInput.value = transcript;
            };

            jusLabRecognition.onerror = (event) => {
                console.error('Speech recognition error', event.error);
                jusLabAddMessage("Sorry, I couldn't understand your voice. Please try again.", 'bot');
            };

            jusLabRecognition.onend = () => {
                jusLabStopRecording();
                if (jusLabUserInput.value.trim() !== '') {
                    jusLabSendMessage(jusLabUserInput.value.trim());
                    jusLabUserInput.value = '';
                }
            };
        } else {
            jusLabVoiceButton.style.display = 'none';
            console.warn('Speech recognition not supported in this browser');
        }
    }

    function jusLabStartRecording() {
        if (!jusLabRecognition) {
            jusLabInitSpeechRecognition();
        }
        jusLabIsVoiceInput = true;
        try {
            jusLabRecognition.start();
        } catch (error) {
            console.error('Speech recognition error:', error);
            jusLabStopRecording();
        }
    }

    function jusLabStopRecording() {
        if (jusLabRecognition && jusLabIsRecording) {
            try {
                jusLabRecognition.stop();
            } catch (error) {
                console.error('Error stopping recognition:', error);
            }
        }
        jusLabIsRecording = false;
        jusLabVoiceButton.classList.remove('recording');
        jusLabVoiceButton.innerHTML = `
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-mic-icon lucide-mic">
            <path d="M12 19v3"></path>
            <path d="M19 10v2a7 7 0 0 1-14 0v-2"></path>
            <rect x="9" y="2" width="6" height="13" rx="3"></rect>
        </svg>
    `;
        jusLabUserInput.placeholder = "Ask about PGDIT program...";
    }

    // Toggle voice recording
    jusLabVoiceButton.addEventListener('click', () => {
        if (jusLabIsRecording) {
            jusLabStopRecording();
        } else {
            jusLabStartRecording();
        }
    });

    // Initialize speech recognition when chatbot is opened
    jusLabChatbotToggle.addEventListener('click', () => {
        if (jusLabIsChatbotOpen && !jusLabRecognition) {
            jusLabInitSpeechRecognition();
        }
    });

    // Toggle chatbot window with animation
    jusLabChatbotToggle.addEventListener('click', () => {
        jusLabIsChatbotOpen = !jusLabIsChatbotOpen;
        jusLabChatbotWindow.classList.toggle('active', jusLabIsChatbotOpen);
        jusLabChatbotToggle.classList.toggle('active', jusLabIsChatbotOpen);

        if (jusLabIsChatbotOpen) {
            jusLabChatbotToggle.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x-icon lucide-x"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
        `;
        } else {
            jusLabChatbotToggle.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-circle-more-icon lucide-message-circle-more"><path d="M2.992 16.342a2 2 0 0 1 .094 1.167l-1.065 3.29a1 1 0 0 0 1.236 1.168l3.413-.998a2 2 0 0 1 1.099.092 10 10 0 1 0-4.777-4.719"/><path d="M8 12h.01"/><path d="M12 12h.01"/><path d="M16 12h.01"/></svg>
        `;
        }
    });

    // Handle form submission
    document.getElementById('chatbotForm').addEventListener('submit', function (e) {
        e.preventDefault();
        if (jusLabUserInput.value.trim() !== '') {
            jusLabIsVoiceInput = false;
            jusLabSendMessage(jusLabUserInput.value.trim());
            jusLabUserInput.value = '';
        }
    });

    // Send message when Enter is pressed
    jusLabUserInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter' && jusLabUserInput.value.trim() !== '') {
            jusLabIsVoiceInput = false;
            jusLabSendMessage(jusLabUserInput.value.trim());
            jusLabUserInput.value = '';
        }
    });

    // Function to send quick questions
    function sendQuickQuestion(question) {
        jusLabIsVoiceInput = false;
        jusLabSendMessage(question);
    }

    // Global variable to track current speech utterance
    let jusLabCurrentSpeechUtterance = null;

    function jusLabSpeak(message) {
        if ('speechSynthesis' in window) {
            window.speechSynthesis.cancel();
            jusLabCurrentSpeechUtterance = null;

            const msg = new SpeechSynthesisUtterance();
            msg.text = message;
            msg.volume = 1;
            msg.rate = 1;
            msg.pitch = 1;

            jusLabCurrentSpeechUtterance = msg;

            msg.onend = function () {
                jusLabCurrentSpeechUtterance = null;
            };

            msg.onerror = function (event) {
                console.error('SpeechSynthesis error:', event);
                jusLabCurrentSpeechUtterance = null;
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

    function jusLabStopSpeech() {
        if ('speechSynthesis' in window && jusLabCurrentSpeechUtterance) {
            window.speechSynthesis.cancel();
            jusLabCurrentSpeechUtterance = null;
        }
    }

    function jusLabAddSuggestions(suggestions) {
        const suggestionsDiv = document.createElement('div');
        suggestionsDiv.classList.add('compact-suggestions');

        let html = '<div class="compact-suggestions-title">Related queries:</div>';

        suggestions.forEach(suggestion => {
            html += `
                <div class="compact-suggestion" onclick="sendQuickQuestion(\`${suggestion}\`)">
                    ${suggestion}
                </div>`;
        });

        suggestionsDiv.innerHTML = html;

        const botMessages = document.querySelectorAll('.bot-message');
        if (botMessages.length > 0) {
            const lastBotMessage = botMessages[botMessages.length - 1];
            lastBotMessage.appendChild(suggestionsDiv);
        }

        jusLabChatbotBody.scrollTop = jusLabChatbotBody.scrollHeight;
    }

    function jusLabAddMessage(text, sender) {
        const messageDiv = document.createElement('div');
        messageDiv.classList.add('message', `${sender}-message`);

        const messageContent = document.createElement('div');
        messageContent.textContent = text;

        const timeDiv = document.createElement('div');
        timeDiv.classList.add('message-time');
        timeDiv.textContent = jusLabGetCurrentTime();

        messageDiv.appendChild(messageContent);
        messageDiv.appendChild(timeDiv);

        jusLabChatbotBody.appendChild(messageDiv);
        jusLabChatbotBody.scrollTop = jusLabChatbotBody.scrollHeight;
    }

    function jusLabGetCurrentTime() {
        const now = new Date();
        let hours = now.getHours();
        let minutes = now.getMinutes();
        const ampm = hours >= 12 ? 'PM' : 'AM';

        hours = hours % 12;
        hours = hours ? hours : 12;
        minutes = minutes < 10 ? '0' + minutes : minutes;

        return `${hours}:${minutes} ${ampm}`;
    }

    function jusLabShowTypingIndicator() {
        const typingDiv = document.createElement('div');
        typingDiv.classList.add('typing-indicator');
        typingDiv.id = 'typingIndicator';

        for (let i = 0; i < 3; i++) {
            const dot = document.createElement('div');
            dot.classList.add('typing-dot');
            typingDiv.appendChild(dot);
        }

        jusLabChatbotBody.appendChild(typingDiv);
        jusLabChatbotBody.scrollTop = jusLabChatbotBody.scrollHeight;
    }

    function jusLabRemoveTypingIndicator() {
        const typingIndicator = document.getElementById('typingIndicator');
        if (typingIndicator) {
            typingIndicator.remove();
        }
    }

    function jusLabSendMessage(message) {
        jusLabStopSpeech();
        jusLabAddMessage(message, 'user');
        jusLabShowTypingIndicator();

        fetch(`https://sohan.thesoftking.com/chatbot/api/chat/?message=${encodeURIComponent(message)}`)
            .then(response => response.json())
            .then(data => {
                jusLabRemoveTypingIndicator();
                jusLabAddMessage(data.reply, 'bot');
                if (jusLabIsVoiceInput) {
                    jusLabSpeak(data.reply);
                }
                if (data.suggestions) jusLabAddSuggestions(data.suggestions);
                jusLabIsVoiceInput = false;
            })
            .catch(error => {
                jusLabRemoveTypingIndicator();
                jusLabAddMessage("Sorry, I'm having trouble connecting. Please try again later.", 'bot');
                console.error('Error:', error);
                jusLabIsVoiceInput = false;
            });
    }

    // Stop speech when page loads/unloads
    window.addEventListener('load', jusLabStopSpeech);
    window.addEventListener('beforeunload', jusLabStopSpeech);
    window.addEventListener('unload', jusLabStopSpeech);
</script>
