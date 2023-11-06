<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.10.2/css/bulma.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            height: 100vh;
        }

        .container {
            display: flex;
            flex-direction: column;
            height: 100%;
            justify-content: center;
            align-items: center;
        }

        .chat-btn {
            margin-bottom: 20px;
        }

        .chat-box {
            display: none;
            width: 100%;
            max-width: 300px;
            position: fixed;
            bottom: 0;
            right: 0;
            border: 1px solid #ccc;
            background-color: #fff;
        }

        .chat-header {
            background-color: #007bff;
            color: #fff;
            padding: 10px;
            text-align: center;
            font-weight: bold;
        }

        .chat-messages {
            max-height: 200px;
            overflow-y: auto;
            padding: 10px;
        }

        .chat-input {
            display: flex;
            justify-content: space-between;
            padding: 10px;
            border-top: 1px solid #ccc;
        }

        .close-chat-btn {
            cursor: pointer;
        }
    </style>
    <title>Live Chat</title>
</head>

<body>
    <div class="container">
        <div class="chat-btn">
            <button class="button is-primary" id="open-chat-btn">Chat</button>
        </div>
        <div class="chat-box">
            <div class="chat-header">
                <span class="close-chat-btn" id="close-chat-btn">&times;</span>
                Chat
            </div>
            <div class="chat-messages" id="chat-messages"></div>
            <div class="chat-input">
                <input class="input" type="text" id="message-input" placeholder="Type your message...">
                <button class="button is-info" id="send-btn">Send</button>
            </div>
        </div>
    </div>
    <script>
        const chatBtn = document.getElementById('open-chat-btn');
        const chatBox = document.querySelector('.chat-box');
        const closeChatBtn = document.getElementById('close-chat-btn');
        const messageInput = document.getElementById('message-input');
        const sendBtn = document.getElementById('send-btn');
        const chatMessages = document.getElementById('chat-messages');

        chatBtn.addEventListener('click', () => {
            chatBox.style.display = 'block';
        });

        closeChatBtn.addEventListener('click', () => {
            chatBox.style.display = 'none';
        });

        sendBtn.addEventListener('click', sendMessage);

        function sendMessage() {
            const message = messageInput.value.trim();
            if (message !== '') {
                displayMessage('You: ' + message);
                messageInput.value = '';
            }
        }

        function displayMessage(message) {
            const div = document.createElement('div');
            div.textContent = message;
            chatMessages.appendChild(div);
            // Scroll to the bottom of the chat messages
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
    </script>
</body>

</html>
