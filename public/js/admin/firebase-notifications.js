import { initializeApp } from "https://www.gstatic.com/firebasejs/12.1.0/firebase-app.js";
import { getDatabase, ref, query, orderByChild, startAt, onChildAdded } from "https://www.gstatic.com/firebasejs/12.1.0/firebase-database.js";

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

const app = initializeApp(firebaseConfig);
const database = getDatabase(app);
const pageLoadTimestamp = Math.floor(Date.now() / 1000);

const notifRef = query(
  ref(database, "notifications"),
  orderByChild("timestamp"),
  startAt(pageLoadTimestamp)
);

onChildAdded(notifRef, (snapshot) => {
  const authId = parseInt($('#getAuthID').val(), 10);
  const notif = snapshot.val();

  if (!authId || !notif || !Array.isArray(notif.user_ids)) {
    return;
  }

  if (!notif.user_ids.includes(authId)) {
    return;
  }

  const sound = document.getElementById("notifSound");
  if (sound) {
    sound.currentTime = 0;
    sound.play().catch(() => {});
  }

  if (typeof window.general_notification_count === 'function') {
    window.general_notification_count();
  }
  if (typeof window.general_notification_data === 'function') {
    window.general_notification_data(10, 0, false);
  }
});

$(function () {
  if (typeof window.general_notification_count === 'function') {
    window.general_notification_count();
  }
});
