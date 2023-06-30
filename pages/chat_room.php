<main>
    <?php
        if (!isset($_GET['r']) || !isset($_GET['s']) || !isset($_SESSION['user']) || $_GET['s'] != $_SESSION['user']['id']) {
            Redirect::to('/index.php?page=home');
        }
    ?>
    <style>
        #chatbox {
            display: flex;
            flex-direction: column;
            width: 50vw;
            height: 40vh;
            overflow-x: scroll;
            overflow-x: hidden;
        }

        .message {
            width: 60%;
            padding: 2.5px 10px;
            border: 1px solid black;
            margin-bottom: 5px;
            background: #555566;
            color: #ffffff;
            border-radius: 10px;
            word-wrap: break-word !important;
        }
        .message #name {
            color: #00aa00;
        }
        .message #body {
            font-weight: 500;
            font-size: 1.125rem;
        }
        .message #sent {
            text-align: right;
            color: #bbbbbf;
            font-size: .75rem;
        }

        .message.sender {
            margin-left: auto;
            text-align: right;
        }
        .message.sender #name {
            color: #9999ff;
        }
    </style>
    <h1><?= User::getUser($_GET['r'])['name'] ?></h1>
    <div id="chatbox"></div>
    <div>
        <textarea maxlength='255' id="messageInput" placeholder="Type your message..."></textarea>
        <button id="sendButton">Send</button>
    </div>
    <script>
        function scrollToBottom() {
            const chatbox = document.getElementById('chatbox');
            chatbox.scrollTop = chatbox.scrollHeight;
        }

        var url = new URL(window.location.href);
        const chatbox = document.getElementById('chatbox');
        const sender = url.searchParams.get("s");

        let prevFetch = null;
        let msgs = [];

        function fetchData() {
            console.log("===== FETCH =====");

            const params = new URLSearchParams({
                s: sender,
                r: url.searchParams.get("r")
            });

            fetch('/src/chat/fetch.chat.php?' + params)
                .then(response => response.json())
                .then(data => {
                    if (JSON.stringify(data) !== JSON.stringify(prevFetch)) {
                        data.forEach(msg => {
                            if (!isMessageInChatbox(msg)) {
                                msgs.push(msg.id);

                                var div = document.createElement("div");
                                div.classList.add("message");
                                if (msg.sender_id == sender) {
                                    div.classList.add("sender");
                                }

                                var _name = document.createElement("p");
                                _name.classList.add("message-item");
                                _name.id = "name";
                                _name.innerHTML = msg.name;

                                var _body = document.createElement("p");
                                _body.classList.add("message-item");
                                _body.id = "body";
                                _body.innerHTML = `<pre>${msg.body}</pre>`;

                                var _hr = document.createElement("hr");

                                var _sent = document.createElement("p");
                                _sent.classList.add("message-item");
                                _sent.id = "sent";
                                _sent.innerHTML = msg.sent;

                                var _viewed = document.createElement("p");
                                _viewed.classList.add("message-item");
                                _viewed.id = "viewed";
                                _viewed.innerHTML = msg.viewed;

                                div.appendChild(_name);
                                div.appendChild(_body);
                                div.appendChild(_sent);
                                div.appendChild(_viewed);
                                chatbox.appendChild(div);
                                scrollToBottom();
                            }
                        });

                        prevFetch = data;
                    }
                })
                .catch(error => {
                    console.error(error);
                });
        }

        function isMessageInChatbox(msg) {
            for (var i = 0; i < msgs.length; i++) {
                if (msgs[i] === msg.id) {
                    return true;
                }
            }
            return false;
        }

        fetchData();

        // let fetchInterval;

        // function startFetchInterval() {
        //     fetchData();
        //     fetchInterval = setInterval(fetchData, 2500);
        // }

        // function stopFetchInterval() {
        //     clearInterval(fetchInterval);
        // }

        // window.addEventListener("focus", startFetchInterval);
        // window.addEventListener("blur", stopFetchInterval);

        // if (document.visibilityState === 'visible') {
        //     startFetchInterval();
        // }
    </script>
    <script>
        // Sending
        const sendButton = document.getElementById('sendButton');
        sendButton.addEventListener('click', sendMessage);

        function sendMessage() {
            const messageInput = document.getElementById('messageInput');
            const message = messageInput.value;

            if (message !== '') {
                const params = new URLSearchParams({
                    s: sender,
                    r: url.searchParams.get("r"),
                    b: message
                });

                fetch('/src/chat/send.chat.php?' + params)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        messageInput.value = ''; // Clear the input field
                    } else {
                        console.error(data.error);
                    }
                })
                .catch(error => {
                    console.error(error);
                });
            }

            messageInput.value = '';
        }

        messageInput.addEventListener('keydown', function(event) {
            if (event.key === 'Enter' && !event.shiftKey) {
                event.preventDefault();
                sendMessage();
            }
        });
    </script>
</main>