(function () {
    if (window.__sessionGuardInitialized) {
        return;
    }
    window.__sessionGuardInitialized = true;

    const PING_URL = '/session/ping';
    const PING_INTERVAL_MS = 8000;
    let stopped = false;

    function isDarkModeActive() {
        const body = document.body;
        if (!body) {
            return false;
        }
        return body.classList.contains('dark-mode') ||
            body.classList.contains('site-dark') ||
            body.classList.contains('landing-dark');
    }

    function showConflictPopup(message) {
        const text = message || 'You were logged out because your account was opened in another browser.';
        const isDark = isDarkModeActive();

        if (window.Swal && typeof window.Swal.fire === 'function') {
            Swal.fire({
                icon: 'warning',
                title: 'Session Ended',
                text: text,
                confirmButtonText: 'Go to Login',
                background: isDark ? '#0f172a' : '#ffffff',
                color: isDark ? '#e2e8f0' : '#111827',
                confirmButtonColor: isDark ? '#3b82f6' : '#2563eb',
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then(() => {
                window.location.href = '/login';
            });
            return;
        }

        alert(text);
        window.location.href = '/login';
    }

    async function pingSession() {
        if (stopped) {
            return;
        }

        try {
            const response = await fetch(PING_URL, {
                method: 'GET',
                credentials: 'same-origin',
                cache: 'no-store',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (response.status === 401) {
                stopped = true;
                let message = '';

                try {
                    const payload = await response.json();
                    message = payload && payload.message ? payload.message : '';
                } catch (e) {}

                showConflictPopup(message);
            }
        } catch (e) {
            // Ignore transient network errors.
        }
    }

    setInterval(pingSession, PING_INTERVAL_MS);
    document.addEventListener('visibilitychange', function () {
        if (!document.hidden) {
            pingSession();
        }
    });
})();
