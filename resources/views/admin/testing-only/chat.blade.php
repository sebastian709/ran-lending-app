<!DOCTYPE html>
<html>
<head>
    <title>Soketi Chat Test</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h2>Chat Log</h2>

    <form id="chat-form">
        <input type="text" id="username" placeholder="Your name" required>
        <input type="text" id="message" placeholder="Your message" required>
        <button type="submit">Send</button>
    </form>

    <ul id="chat-log"></ul>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pusher/7.0.3/pusher.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.15.1/echo.iife.js"></script>

    <script>
        window.Pusher = Pusher;

        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: "{{ config('broadcasting.connections.pusher.key') }}",
            wsHost: '127.0.0.1',
            wsPort: 6001,
            forceTLS: false,
            disableStats: true,
            enabledTransports: ['ws'],
        });

        window.Echo.channel('chat')
            .listen('.message.sent', function (e) {
                console.log('💬 Received:', e);
                const el = document.createElement('li');
                el.textContent = `${e.username}: ${e.message}`;
                document.getElementById('chat-log').appendChild(el);
            });

        document.getElementById('chat-form').addEventListener('submit', function (e) {
            e.preventDefault();

            const username = document.getElementById('username').value.trim();
            const message = document.getElementById('message').value.trim();

            axios.post('/test-broadcast', {
                username: username,
                message: message
            }).then(() => {
                document.getElementById('message').value = '';
                console.log(document.getElementById('message').value);
            }).catch(err => {
                console.error('❌ Broadcast failed', err);
            });
        });
    </script>
</body>
</html>
