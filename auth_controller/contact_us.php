<?php
    session_start();
    
    $_SESSION['success_message'] = "Your response has been submitted. Thank You!";
    header("Location: ../kenhavate");
    exit;
?>