let isPageLoaded = false;
let loaderTimeout;

// Function to hide the loader
function hideLoader() {
    const loadingContainer = document.querySelector('.holder_loader');
    loadingContainer.classList.add('hidden');
}

// Function to show the loader
function showLoader() {
    const loadingContainer = document.querySelector('.holder_loader');
    loadingContainer.classList.remove('hidden');
}

// Function to handle loader timeout
function loaderTimeoutHandler() {
    hideLoader();
}

// Calculate the loading time and adjust the loader timeout accordingly
const startTime = Date.now();

// Add an event listener to mark the page as fully loaded
window.addEventListener('load', function() {
    const endTime = Date.now();
    const loadingTime = endTime - startTime;
    console.log(`Page loaded in ${loadingTime} milliseconds.`);
    
    if (!isPageLoaded) {
        const remainingTime = 8000 - loadingTime; // Calculate the remaining time to reach 5 seconds
        if (remainingTime > 0) {
            loaderTimeout = setTimeout(loaderTimeoutHandler, remainingTime);
        } else {
            hideLoader();
        }
        isPageLoaded = true;
    }
});

// Add an event listener to show the loader when the page starts loading
window.addEventListener('beforeunload', function() {
    showLoader();
});

// Set a 5-second timeout initially
loaderTimeout = setTimeout(loaderTimeoutHandler, 8000);
