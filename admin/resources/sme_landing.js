
document.addEventListener("DOMContentLoaded", function() {

    const menu_icon = document.querySelector('.menu_icon');
    const sidebar = document.querySelector('.sidebar');
    const close_menu = document.querySelector('.close_menu');

    menu_icon.addEventListener('click', ()=>{
        sidebar.classList.add('active');
    });

    close_menu.addEventListener('click', ()=>{
        sidebar.classList.remove('active');
    });


    const menu_dash = document.querySelector('.menu_dash');
    const menu_idea_allocated = document.querySelector('.menu_idea_allocated');
    const menu_idea_pending = document.querySelector('.menu_idea_pending');
    const menu_idea_committed = document.querySelector('.menu_idea_committed');
    const menu_idea_history = document.querySelector('.menu_idea_history');
    const menu_message = document.querySelector('.menu_message');

    
    const home_cards = document.querySelector('.home_cards');
    const all_allocated_ideas = document.querySelector('.all_allocated_ideas');
    const all_pending_ideas = document.querySelector('.all_pending_ideas');
    const all_committed_ideas = document.querySelector('.all_committed_ideas');
    const all_history_ideas = document.querySelector('.all_history_ideas');
    const message_dd = document.querySelector('.message_dd');
     

    // Select a parent element that exists in the DOM at the time of the initial page load
    const ideaDetailsContainer = document.querySelector('.ideaDetailsContainer');
    const close_ideaDetailsContainers = document.querySelectorAll('.close_ideaDetailsContainer');

    // Add a click event listener to the parent container
    ideaDetailsContainer.addEventListener('click', (event) => {
        const close_ideaDetailsContainer = event.target.closest('.close_ideaDetailsContainer');

        if (close_ideaDetailsContainer) {
            const ideaDetailsContainer = close_ideaDetailsContainer.closest('.ideaDetailsContainer');

            if (ideaDetailsContainer) {
                ideaDetailsContainer.style.display = 'none';
                all_pending_ideas.classList.add('active');
            }
        }
    });


    // Event delegation for dynamically created elements
    document.addEventListener('change', function(event) {
        if (event.target && event.target.id === 'Comment_type') {
            const selectedValue = event.target.value;
            let commentInfoText = '';

            if (ideaDetailsContainer) {
                const comment_sub_holder_1 = document.querySelector('.comment_sub_holder_1');
                const comment_sub_holder_2 = document.querySelector('.comment_sub_holder_2');
                const comment_info = document.querySelector('.comment_info');
                const comment_textarea = document.getElementById('comment_textarea');

                if (selectedValue === 'critical') {
                    commentInfoText = '<b style="color: red;">This comment can be used to state that the document might be invalid, some information is missing, the document is not understood, or the document does not belong to your department. <u><span style="color: blue;">Click here for more information.</span></u></b>';

                    // Remove the "active" class
                    comment_sub_holder_1.classList.remove('active');
                    comment_sub_holder_2.classList.remove('active');
                    comment_info.classList.remove('active');
                    comment_textarea.value = '';
                
                    // Use setTimeout to add the "active" class after a delay
                    setTimeout(function() {
                        comment_sub_holder_1.classList.add('active');
                        comment_sub_holder_2.classList.add('active');
                        comment_info.classList.add('active');
                    }, 250);

                } 
                else if (selectedValue === 'general') {
                    commentInfoText = '<b style="color: red;">This comment can be used to make a general comment regarding the idea upon review. <u><span style="color: blue;">Click here for more information.</span></u></b>';

                    // Remove the "active" class
                    comment_sub_holder_1.classList.remove('active');
                    comment_sub_holder_2.classList.remove('active');
                    comment_info.classList.remove('active');
                    comment_textarea.value = '';

                    // Use setTimeout to add the "active" class after a delay
                    setTimeout(function() {
                        comment_sub_holder_1.classList.add('active');
                        comment_sub_holder_2.classList.add('active');
                        comment_info.classList.add('active');
                    }, 250);

                } 
                else if (selectedValue === 'suggestion') {
                    commentInfoText = '<b style="color: red;">This comment can be used to suggest improvements or modifications to the idea. <u><span style="color: blue;">Click here for more information.</span></u></b>';

                    // Remove the "active" class
                    comment_sub_holder_1.classList.remove('active');
                    comment_sub_holder_2.classList.remove('active');
                    comment_info.classList.remove('active');
                    comment_textarea.value = '';
                
                    // Use setTimeout to add the "active" class after a delay
                    setTimeout(function() {
                        comment_sub_holder_1.classList.add('active');
                        comment_sub_holder_2.classList.add('active');
                        comment_info.classList.add('active');
                    }, 250);
                }
                else{
                    comment_sub_holder_1.classList.remove('active');
                    comment_sub_holder_2.classList.remove('active');
                    comment_info.classList.remove('active');
                    comment_textarea.value = '';
                }

                const commentInfoParagraph = ideaDetailsContainer.querySelector('.comment_info');
                if (commentInfoParagraph) {
                    commentInfoParagraph.innerHTML = '<i>' + commentInfoText + '</i>';
                }
            }
        }
    });









    menu_dash.addEventListener('click', ()=>{
        home_cards.classList.add('active');
        all_allocated_ideas.classList.remove('active');
        all_pending_ideas.classList.remove('active');
        all_committed_ideas.classList.remove('active');
        all_history_ideas.classList.remove('active');
        message_dd.classList.remove('active');
        ideaDetailsContainer.style.display = 'none';
    });

    menu_idea_allocated.addEventListener('click', ()=>{
        home_cards.classList.remove('active');
        all_allocated_ideas.classList.add('active');
        all_pending_ideas.classList.remove('active');
        all_committed_ideas.classList.remove('active');
        all_history_ideas.classList.remove('active');
        message_dd.classList.remove('active');
        ideaDetailsContainer.style.display = 'none';
    });

    menu_idea_pending.addEventListener('click', ()=>{
        home_cards.classList.remove('active');
        all_allocated_ideas.classList.remove('active');
        all_pending_ideas.classList.add('active');
        all_committed_ideas.classList.remove('active');
        all_history_ideas.classList.remove('active');
        message_dd.classList.remove('active');
        ideaDetailsContainer.style.display = 'none';
        //document.getElementById("comment-form").style.display = "none";
    });

    menu_idea_committed.addEventListener('click', ()=>{
        home_cards.classList.remove('active');
        all_allocated_ideas.classList.remove('active');
        all_pending_ideas.classList.remove('active');
        all_committed_ideas.classList.add('active');
        all_history_ideas.classList.remove('active');
        message_dd.classList.remove('active');
        ideaDetailsContainer.style.display = 'none';
    });

    menu_idea_history.addEventListener('click', ()=>{
        home_cards.classList.remove('active');
        all_allocated_ideas.classList.remove('active');
        all_pending_ideas.classList.remove('active');
        all_committed_ideas.classList.remove('active');
        all_history_ideas.classList.add('active');
        message_dd.classList.remove('active');
        ideaDetailsContainer.style.display = 'none';
    });

    menu_message.addEventListener('click', ()=>{
        home_cards.classList.remove('active');
        all_allocated_ideas.classList.remove('active');
        all_pending_ideas.classList.remove('active');
        all_committed_ideas.classList.remove('active');
        all_history_ideas.classList.remove('active');
        message_dd.classList.add('active');
        ideaDetailsContainer.style.display = 'none';
    });


});