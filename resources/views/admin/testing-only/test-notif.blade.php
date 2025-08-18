<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>RAN</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>

<body>

    <h1>Test</h1>



    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.js"></script>
    <!-- Bootstrap JS (Dropdowns need this) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Firebase SDK -->
    <script src="https://www.gstatic.com/firebasejs/9.22.0/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.22.0/firebase-database.js"></script>
    <script src="{{ asset('js/admin.js') }}"></script>

    <script type="module">
        // Import Firebase SDKs
        import { initializeApp } from "https://www.gstatic.com/firebasejs/12.1.0/firebase-app.js";
        import { getDatabase, ref, onValue } from "https://www.gstatic.com/firebasejs/12.1.0/firebase-database.js";

        // Your Firebase config
        const firebaseConfig = {
            apiKey: "AIzaSyDI5V6np4Xstxl01DbS9j2PCV3tFmttbHw",
            authDomain: "ran-realtime.firebaseapp.com",
            databaseURL: "https://ran-realtime-default-rtdb.firebaseio.com",
            projectId: "ran-realtime",
            storageBucket: "ran-realtime.firebasestorage.app",
            messagingSenderId: "678506273903",
            appId: "1:678506273903:web:f7979289e002145776c19a",
            measurementId: "G-THBZK5DGGR"
        };

        // Initialize Firebase
        const app = initializeApp(firebaseConfig);
        const database = getDatabase(app);

        // === LISTENER SETUP ===
        const userId = "testing"; // same ID na gamit mo sa sendNotification
        const notifRef = ref(database, "notifications/" + userId);

        // Listen for changes
        onValue(notifRef, (snapshot) => {
            if (snapshot.exists()) {
                const data = snapshot.val();
                console.log("🔥 New notification:", data.user_ids, "at", data.timestamp);

                // Example: append to body
                const p = document.createElement("p");
                p.innerText = `${data.user_ids} (${data.timestamp})`;
                document.body.appendChild(p);
            } else {
                console.log("No data available");
            }
        });
    </script>

</body>

</html>