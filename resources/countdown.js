function startCountdown(decryptedDate, countdownId) {
    const countdownElement = document.getElementById(countdownId);

    const countdownInterval = setInterval(function() {
        const now = new Date();
        const timeDifference = new Date(decryptedDate) - now;

        
        const days = Math.floor(timeDifference / (1000 * 60 * 60 * 24));
        const hours = Math.floor((timeDifference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((timeDifference % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((timeDifference % (1000 * 60)) / 1000);

        if (timeDifference <= 0) {
            clearInterval(countdownInterval);
            countdownElement.textContent = `00d 00h 00m 00s`;
        } else {

            countdownElement.textContent = `${days}d ${hours}h ${minutes}m ${seconds}s`;
        }
    }, 1000);
}




    // Initial fetch
    fetchChallenges();

    // Periodically update the content every one minute (60000 milliseconds)
    setInterval(fetchChallenges, 60000);