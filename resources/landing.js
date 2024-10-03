document.addEventListener("DOMContentLoaded", function() {
    const challengeRadios = document.querySelectorAll('input[name="selected_challenge"]');
    const challengeDetailsArray = document.querySelectorAll('.challenge-details');
    const challengeList = document.querySelector('.display_none'); // Change this selector
    
    challengeRadios.forEach(radio => {
        radio.addEventListener('change', function () {
            if (this.checked) {
                // Hide all challenge list options
                challengeRadios.forEach(r => {
                    r.closest('.mb-3').style.display = 'none';
                });
                
                // Hide all challenge details containers
                challengeDetailsArray.forEach(details => {
                    details.style.display = 'none';
                });
                
                const selectedChallengeId = this.value;
                
                // Show the selected challenge details container
                const selectedChallengeDetails = document.getElementById('challengeDetails-' + selectedChallengeId);
                if (selectedChallengeDetails) {
                    selectedChallengeDetails.style.display = 'block';
                }
            }
        });
    });
    
    // Update code for handling the "Back" button and resetting visibility
    const closeChallengeDetailsArray = document.querySelectorAll('.close_challenge_details');
    
    closeChallengeDetailsArray.forEach(closeButton => {
        closeButton.addEventListener('click', function () {
            // Show all challenge list options
            challengeRadios.forEach(r => {
                r.closest('.mb-3').style.display = 'flex';
            });
            
            const selectedChallengeId = this.getAttribute('data-challenge-id');
            const selectedChallengeDetails = document.getElementById('challengeDetails-' + selectedChallengeId);
            if (selectedChallengeDetails) {
                selectedChallengeDetails.style.display = 'none';
            }
        });
    });

    // Function to update character count for a textarea
    function updateCharacterCount(textareaId, countId, maxCharacters) {
        const textarea = document.getElementById(textareaId);
        const countElement = document.getElementById(countId);
        const currentText = textarea.value;
        const remaining = Math.max(0, maxCharacters - currentText.length); // Ensure remaining characters are non-negative

        // Display the remaining characters
        countElement.textContent = remaining;

        // If the user exceeds the character limit, prevent further input
        if (remaining === 0) {
        textarea.value = currentText.substr(0, maxCharacters); // Trim the text to the character limit
        }
    }

    // Add event listeners to the textareas
    const briefDescriptionTextarea = document.getElementById("briefDescription");
    briefDescriptionTextarea.addEventListener("input", function () {
        updateCharacterCount("briefDescription", "briefDescriptionCount", 200);
    });

    const problemStatementTextarea = document.getElementById("problemStatement");
    problemStatementTextarea.addEventListener("input", function () {
        updateCharacterCount("problemStatement", "problemStatementCount", 200);
    });

    const proposedSolutionTextarea = document.getElementById("proposedSolution");
    proposedSolutionTextarea.addEventListener("input", function () {
        updateCharacterCount("proposedSolution", "proposedSolutionCount", 300);
    });

    const costBenefitAnalysisTextarea = document.getElementById("costBenefitAnalysis");
    costBenefitAnalysisTextarea.addEventListener("input", function () {
        updateCharacterCount("costBenefitAnalysis", "costBenefitAnalysisCount", 300);
    });

    // Initial character count update
    updateCharacterCount("briefDescription", "briefDescriptionCount", 200);
    updateCharacterCount("problemStatement", "problemStatementCount", 200);
    updateCharacterCount("proposedSolution", "proposedSolutionCount", 300);
    updateCharacterCount("costBenefitAnalysis", "costBenefitAnalysisCount", 300);

    
    const inputElement = document.getElementById("ideaTitle");
    const charCountElement = document.getElementById("charCount");

    inputElement.addEventListener("input", function () {
        const inputValue = this.value;
        const remainingCharacters = 25 - inputValue.length;
        const remainingWords = inputValue.trim().split(/\s+/).filter(Boolean).length;

        if (remainingCharacters < 0 || remainingWords > 25) {
            // If input exceeds 25 characters or 25 words, truncate it
            const truncatedText = inputValue.substr(0, 25);
            this.value = truncatedText;
        }

        charCountElement.textContent = `${Math.max(0, remainingCharacters)} characters remaining`;
    });

    const show_menu = document.querySelector(".show_menu");
    const hide_menu = document.querySelector(".hide_menu");
    const menu_holder = document.querySelector(".menu_holder");
    
    const vPillsHomeTab = document.getElementById("v-pills-home-tab");
    const vPillsIdeaTab = document.getElementById("v-pills-idea-tab");
    const vPillsChallengeTab = document.getElementById("v-pills-challenge-tab");
    const vPillsSubmissionTab = document.getElementById("v-pills-submission-tab");
    const vPillsMessagesTab = document.getElementById("v-pills-messages-tab");



    show_menu.addEventListener('click', ()=>{
        show_menu.classList.remove('active');
        hide_menu.classList.add('active');
        menu_holder.classList.add('active');
    });

    hide_menu.addEventListener('click', ()=>{
        show_menu.classList.add('active');
        hide_menu.classList.remove('active');
        menu_holder.classList.remove('active');
    });

    vPillsHomeTab.addEventListener('click', ()=>{
        show_menu.classList.add('active');
        hide_menu.classList.remove('active');
        menu_holder.classList.remove('active');
    });

    vPillsIdeaTab.addEventListener('click', ()=>{
        show_menu.classList.add('active');
        hide_menu.classList.remove('active');
        menu_holder.classList.remove('active');
    });

    vPillsChallengeTab.addEventListener('click', ()=>{
        show_menu.classList.add('active');
        hide_menu.classList.remove('active');
        menu_holder.classList.remove('active');
    });

    vPillsSubmissionTab.addEventListener('click', ()=>{
        show_menu.classList.add('active');
        hide_menu.classList.remove('active');
        menu_holder.classList.remove('active');
    });

    vPillsMessagesTab.addEventListener('click', ()=>{
        show_menu.classList.add('active');
        hide_menu.classList.remove('active');
        menu_holder.classList.remove('active');
    });

});
