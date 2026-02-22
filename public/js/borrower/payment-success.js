(function () {
    let seconds = 15;
    const countdownEl = document.getElementById('countdown');
    const redirectUrl = '/repayment-schedule';

    const interval = setInterval(function () {
        seconds--;
        if (countdownEl) {
            countdownEl.textContent = seconds;
        }

        if (seconds <= 0) {
            clearInterval(interval);
            window.location.href = redirectUrl;
        }
    }, 1000);
})();
