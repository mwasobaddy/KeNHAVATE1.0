
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
    const menu_idea = document.querySelector('.menu_idea');
    const menu_message = document.querySelector('.menu_message');
    const menu_committee = document.querySelector('.menu_committee');
    const menu_board = document.querySelector('.menu_board');

    
    const home_cards = document.querySelector('.home_cards');
    const all_idea_submitted = document.querySelector('.all_idea_submitted');
    const new_idea_tab_cards = document.querySelector('.new_idea_tab_cards');
    const new_idea_tab = document.querySelector('.new_idea_tab');
    const tasks_done = document.querySelector('.tasks_done');
    const task_undone = document.querySelector('.task_undone');
    const approved_committee = document.querySelector('.approved_committee');
    const approved_board = document.querySelector('.approved_board');
    const message_cards = document.querySelector('.message_cards');
    const message_dg = document.querySelector('.message_dg');
    const message_committee = document.querySelector('.message_committee');
    const message_expert = document.querySelector('.message_expert');
    const committee_cards = document.querySelector('.committee_cards');
    const committee_members = document.querySelector('.committee_members');
    const board_cards = document.querySelector('.board_cards');

    menu_dash.addEventListener('click', ()=>{
        home_cards.classList.add('active');
        all_idea_submitted.classList.add('active');
        new_idea_tab_cards.classList.remove('active');
        new_idea_tab.classList.remove('active');
        tasks_done.classList.remove('active');
        task_undone.classList.remove('active');
        approved_committee.classList.remove('active');
        approved_board.classList.remove('active');
        message_cards.classList.remove('active');
        message_dg.classList.remove('active');
        message_committee.classList.remove('active');
        message_expert.classList.remove('active');
        committee_cards.classList.remove('active');
        committee_members.classList.remove('active');
        board_cards.classList.remove('active');
    });

    menu_idea.addEventListener('click', ()=>{
        home_cards.classList.remove('active');
        all_idea_submitted.classList.remove('active');
        new_idea_tab_cards.classList.add('active');
        new_idea_tab.classList.remove('active');
        tasks_done.classList.remove('active');
        task_undone.classList.remove('active');
        approved_committee.classList.remove('active');
        approved_board.classList.remove('active');
        message_cards.classList.remove('active');
        message_dg.classList.remove('active');
        message_committee.classList.remove('active');
        message_expert.classList.remove('active');
        committee_cards.classList.remove('active');
        committee_members.classList.remove('active');
        board_cards.classList.remove('active');
    });

    menu_message.addEventListener('click', ()=>{
        home_cards.classList.remove('active');
        all_idea_submitted.classList.remove('active');
        new_idea_tab_cards.classList.remove('active');
        new_idea_tab.classList.remove('active');
        tasks_done.classList.remove('active');
        task_undone.classList.remove('active');
        approved_committee.classList.remove('active');
        approved_board.classList.remove('active');
        message_cards.classList.add('active');
        message_dg.classList.remove('active');
        message_committee.classList.remove('active');
        message_expert.classList.remove('active');
        committee_cards.classList.remove('active');
        committee_members.classList.remove('active');
        board_cards.classList.remove('active');
    });

    menu_committee.addEventListener('click', ()=>{
        home_cards.classList.remove('active');
        all_idea_submitted.classList.remove('active');
        new_idea_tab_cards.classList.remove('active');
        new_idea_tab.classList.remove('active');
        tasks_done.classList.remove('active');
        task_undone.classList.remove('active');
        approved_committee.classList.remove('active');
        approved_board.classList.remove('active');
        message_cards.classList.remove('active');
        message_dg.classList.remove('active');
        message_committee.classList.remove('active');
        message_expert.classList.remove('active');
        committee_cards.classList.add('active');
        committee_members.classList.remove('active');
        board_cards.classList.remove('active');
    });

    menu_board.addEventListener('click', ()=>{
        home_cards.classList.remove('active');
        all_idea_submitted.classList.remove('active');
        new_idea_tab_cards.classList.remove('active');
        new_idea_tab.classList.remove('active');
        tasks_done.classList.remove('active');
        task_undone.classList.remove('active');
        approved_committee.classList.remove('active');
        approved_board.classList.remove('active');
        message_cards.classList.remove('active');
        message_dg.classList.remove('active');
        message_committee.classList.remove('active');
        message_expert.classList.remove('active');
        committee_cards.classList.remove('active');
        committee_members.classList.remove('active');
        board_cards.classList.add('active');
    });
    

    
    const card_new_idea = document.querySelector('.card_new_idea');
    const card_done_task = document.querySelector('.card_done_task');
    const card_undone_task = document.querySelector('.card_undone_task');
    const card_approved_committee = document.querySelector('.card_approved_committee');
    const card_approved_board = document.querySelector('.card_approved_board');

    card_new_idea.addEventListener('click', ()=>{
        home_cards.classList.remove('active');
        all_idea_submitted.classList.remove('active');
        new_idea_tab_cards.classList.remove('active');
        new_idea_tab.classList.add('active');
        tasks_done.classList.remove('active');
        task_undone.classList.remove('active');
        approved_committee.classList.remove('active');
        approved_board.classList.remove('active');
        message_cards.classList.remove('active');
        message_dg.classList.remove('active');
        message_committee.classList.remove('active');
        message_expert.classList.remove('active');
        committee_cards.classList.remove('active');
        committee_members.classList.remove('active');
        board_cards.classList.remove('active');
    });

    card_done_task.addEventListener('click', ()=>{
        home_cards.classList.remove('active');
        all_idea_submitted.classList.remove('active');
        new_idea_tab_cards.classList.remove('active');
        new_idea_tab.classList.remove('active');
        tasks_done.classList.add('active');
        task_undone.classList.remove('active');
        approved_committee.classList.remove('active');
        approved_board.classList.remove('active');
        message_cards.classList.remove('active');
        message_dg.classList.remove('active');
        message_committee.classList.remove('active');
        message_expert.classList.remove('active');
        committee_cards.classList.remove('active');
        committee_members.classList.remove('active');
        board_cards.classList.remove('active');
    });

    card_undone_task.addEventListener('click', ()=>{
        home_cards.classList.remove('active');
        all_idea_submitted.classList.remove('active');
        new_idea_tab_cards.classList.remove('active');
        new_idea_tab.classList.remove('active');
        tasks_done.classList.remove('active');
        task_undone.classList.add('active');
        approved_committee.classList.remove('active');
        approved_board.classList.remove('active');
        message_cards.classList.remove('active');
        message_dg.classList.remove('active');
        message_committee.classList.remove('active');
        message_expert.classList.remove('active');
        committee_cards.classList.remove('active');
        committee_members.classList.remove('active');
        board_cards.classList.remove('active');
    });

    card_approved_committee.addEventListener('click', ()=>{
        home_cards.classList.remove('active');
        all_idea_submitted.classList.remove('active');
        new_idea_tab_cards.classList.remove('active');
        new_idea_tab.classList.remove('active');
        tasks_done.classList.remove('active');
        task_undone.classList.remove('active');
        approved_committee.classList.add('active');
        approved_board.classList.remove('active');
        message_cards.classList.remove('active');
        message_dg.classList.remove('active');
        message_committee.classList.remove('active');
        message_expert.classList.remove('active');
        committee_cards.classList.remove('active');
        committee_members.classList.remove('active');
        board_cards.classList.remove('active');
    });

    card_approved_board.addEventListener('click', ()=>{
        home_cards.classList.remove('active');
        all_idea_submitted.classList.remove('active');
        new_idea_tab_cards.classList.remove('active');
        new_idea_tab.classList.remove('active');
        tasks_done.classList.remove('active');
        task_undone.classList.remove('active');
        approved_committee.classList.remove('active');
        approved_board.classList.add('active');
        message_cards.classList.remove('active');
        message_dg.classList.remove('active');
        message_committee.classList.remove('active');
        message_expert.classList.remove('active');
        committee_cards.classList.remove('active');
        committee_members.classList.remove('active');
        board_cards.classList.remove('active');
    });
    

    
    const card_message_dg = document.querySelector('.card_message_dg');
    const card_message_committee = document.querySelector('.card_message_committee');
    const card_message_experts = document.querySelector('.card_message_experts');

    card_message_dg.addEventListener('click', ()=>{
        home_cards.classList.remove('active');
        all_idea_submitted.classList.remove('active');
        new_idea_tab_cards.classList.remove('active');
        new_idea_tab.classList.remove('active');
        tasks_done.classList.remove('active');
        task_undone.classList.remove('active');
        approved_committee.classList.remove('active');
        approved_board.classList.remove('active');
        message_cards.classList.remove('active');
        message_dg.classList.add('active');
        message_committee.classList.remove('active');
        message_expert.classList.remove('active');
        committee_cards.classList.remove('active');
        committee_members.classList.remove('active');
        board_cards.classList.remove('active');
    });

    card_message_committee.addEventListener('click', ()=>{
        home_cards.classList.remove('active');
        all_idea_submitted.classList.remove('active');
        new_idea_tab_cards.classList.remove('active');
        new_idea_tab.classList.remove('active');
        tasks_done.classList.remove('active');
        task_undone.classList.remove('active');
        approved_committee.classList.remove('active');
        approved_board.classList.remove('active');
        message_cards.classList.remove('active');
        message_dg.classList.remove('active');
        message_committee.classList.add('active');
        message_expert.classList.remove('active');
        committee_cards.classList.remove('active');
        committee_members.classList.remove('active');
        board_cards.classList.remove('active');
    });

    card_message_experts.addEventListener('click', ()=>{
        home_cards.classList.remove('active');
        all_idea_submitted.classList.remove('active');
        new_idea_tab_cards.classList.remove('active');
        new_idea_tab.classList.remove('active');
        tasks_done.classList.remove('active');
        task_undone.classList.remove('active');
        approved_committee.classList.remove('active');
        approved_board.classList.remove('active');
        message_cards.classList.remove('active');
        message_dg.classList.remove('active');
        message_committee.classList.remove('active');
        message_expert.classList.add('active');
        committee_cards.classList.remove('active');
        committee_members.classList.remove('active');
        board_cards.classList.remove('active');
    });
    

    const card_view_committee_members = document.querySelector('.card_view_committee_members');

    card_view_committee_members.addEventListener('click', ()=>{
        home_cards.classList.remove('active');
        all_idea_submitted.classList.remove('active');
        new_idea_tab_cards.classList.remove('active');
        new_idea_tab.classList.remove('active');
        tasks_done.classList.remove('active');
        task_undone.classList.remove('active');
        approved_committee.classList.remove('active');
        approved_board.classList.remove('active');
        message_cards.classList.remove('active');
        message_dg.classList.remove('active');
        message_committee.classList.remove('active');
        message_expert.classList.remove('active');
        committee_cards.classList.remove('active');
        committee_members.classList.add('active');
        board_cards.classList.remove('active');
    });


    const close_new_idea_tab = document.querySelector('.close_new_idea_tab');
    const close_tasks_done = document.querySelector('.close_tasks_done');
    const close_task_undone = document.querySelector('.close_task_undone');
    const close_approved_committee = document.querySelector('.close_approved_committee');
    const close_approved_board = document.querySelector('.close_approved_board');
    const close_message_dg = document.querySelector('.close_message_dg');
    const close_message_committee = document.querySelector('.close_message_committee');
    const close_message_expert = document.querySelector('.close_message_expert');
    const close_committee_members = document.querySelector('.close_committee_members');

    close_new_idea_tab.addEventListener('click', ()=>{
        new_idea_tab.classList.remove('active');
        new_idea_tab_cards.classList.add('active');
        
    });

    close_tasks_done.addEventListener('click', ()=>{
        tasks_done.classList.remove('active');
        new_idea_tab_cards.classList.add('active');
    });

    close_task_undone.addEventListener('click', ()=>{
        task_undone.classList.remove('active');
        new_idea_tab_cards.classList.add('active');
    });

    close_approved_committee.addEventListener('click', ()=>{
        approved_committee.classList.remove('active');
        new_idea_tab_cards.classList.add('active');
    });

    close_approved_board.addEventListener('click', ()=>{
        approved_board.classList.remove('active');
        new_idea_tab_cards.classList.add('active');
    });

    close_message_dg.addEventListener('click', ()=>{
        message_cards.classList.add('active');
        message_dg.classList.remove('active');
    });

    close_message_committee.addEventListener('click', ()=>{
        message_cards.classList.add('active');
        message_committee.classList.remove('active');
    });

    close_message_expert.addEventListener('click', ()=>{
        message_cards.classList.add('active');
        message_expert.classList.remove('active');
    });

    close_committee_members.addEventListener('click', ()=>{
        committee_cards.classList.add('active');
        committee_members.classList.remove('active');
    });

    const post_challenge = document.querySelector('.post_challenge');
    const view_challenge = document.querySelector('.view_challenge');
    const row_challenges = document.querySelector('.row_challenges');
    
    const form_post_challenge = document.querySelector('.form_post_challenge');
    const challenges_uploaded = document.querySelector('.challenges_uploaded');
    
    const close_challenges = document.querySelectorAll('.close_challenges');
    const close_challenges_responses1 = document.querySelectorAll('.close_challenges_responses1');
    const challenges_responses1 = document.querySelector('.challenges_responses1');
    //const challenges_uploaded = document.querySelector('.challenges_uploaded');

    post_challenge.addEventListener('click', ()=>{
        form_post_challenge.classList.add('active');
        challenges_uploaded.classList.remove('active');
        row_challenges.classList.remove('active');
    });

    view_challenge.addEventListener('click', ()=>{
        form_post_challenge.classList.remove('active');
        challenges_uploaded.classList.add('active');
        row_challenges.classList.remove('active');
    });

    close_challenges.forEach(function(element) {
        element.addEventListener('click', () => {
            form_post_challenge.classList.remove('active');
            challenges_uploaded.classList.remove('active');
            row_challenges.classList.add('active');
        });
    });
    
    close_challenges_responses1.forEach(function (element) {
        element.addEventListener('click', () => {
            form_post_challenge.classList.remove('active');
            challenges_uploaded.classList.add('active');
            row_challenges.classList.remove('active');
            
            // Set only the challenges_responses1 element to display 'none'
            if (challenges_responses1) {
                challenges_responses1.style.display = 'none';
                challenges_uploaded.style.display = 'flex';
            }
        });
    });

    const close_details_form_container = document.querySelector('.close_details_form_container');
    const details_form_container = document.querySelector('.details_form_container');

    close_details_form_container.addEventListener('click', ()=>{
        details_form_container.classList.remove('active');
        task_undone.classList.add('active');
    });

});