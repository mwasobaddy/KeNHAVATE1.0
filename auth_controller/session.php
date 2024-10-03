<?php
    //just submitted
    //incubation //on queue

    //sme
    //review //pending

    //committee
    //committee //pending

    //board
    //board //pending
    
    $stage = "incubation";
    $status = "on queue";
    $day_user_uploaded = date('D, F j, Y - h:i A');
    $expert_uuid = "unassigned";
    $day_expert_appointed = "not applicable";
    $day_expert_committed = "not committed";
    $expert_comment = "no comment";
    $committee_approved = "not applicable";
    $day_committee_approved = "not applicable";
    $yes_vote = "0";
    $no_vote = "0";
    $email_sent_dg = "not sent";
    $day_board_approved = "not applicable";
    $comment_board = "no comment";


    $stage_sme = "review";
    $status_sme_1 = "on queue";
    $status_sme_2 = "reviewing";
    $status_sme_3 = "reviewed";


            
    $_SESSION['stage_sme'] = $stage_sme;
    $_SESSION['status_sme_1'] = $status_sme_1;
    $_SESSION['status_sme_2'] = $status_sme_2;
    $_SESSION['status_sme_3'] = $status_sme_3;


    $stage_comm = "committee_level";
    $status_comm_1 = "pending";
    $status_comm_2 = "rejected";
    $status_comm_3 = "approved";


            
    $_SESSION['stage_comm'] = $stage_comm;
    $_SESSION['status_comm_1'] = $status_comm_1;
    $_SESSION['status_comm_2'] = $status_comm_2;
    $_SESSION['status_comm_3'] = $status_comm_3;


    $stage_board = "board_level";
    $status_board_1 = "pending";
    $status_board_2 = "rejected";
    $status_board_3 = "approved";


            
    $_SESSION['stage_board'] = $stage_board;
    $_SESSION['status_board_1'] = $status_board_1;
    $_SESSION['status_board_2'] = $status_board_2;
    $_SESSION['status_board_3'] = $status_board_3;


            
    $_SESSION['stage'] = $stage;
    $_SESSION['status'] = $status;
    $_SESSION['day_user_uploaded'] = $day_user_uploaded;
    $_SESSION['expert_uuid'] = $expert_uuid;
    $_SESSION['day_expert_appointed'] = $day_expert_appointed;
    $_SESSION['day_expert_committed'] = $day_expert_committed;
    $_SESSION['expert_comment'] = $expert_comment;
    $_SESSION['committee_approved'] = $committee_approved;
    $_SESSION['day_committee_approved'] = $day_committee_approved;
    $_SESSION['yes_vote'] = $yes_vote;
    $_SESSION['no_vote'] = $no_vote;
    $_SESSION['email_sent_dg'] = $email_sent_dg;
    $_SESSION['day_board_approved'] = $day_board_approved;
    $_SESSION['comment_board'] = $comment_board;
?>