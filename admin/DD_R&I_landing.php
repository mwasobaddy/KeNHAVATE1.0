<?php

    session_start();
  
    include("../auth_controller/requirement.php");
    include("../admin/auth_controller/requirement.php");

    //$uuid = $_SESSION['uuid'];

    //check_login_admin($con);
    //check_details_admin($con);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DD RI&KM Dashboard</title>

    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"/>
    <base href="/KeNHAVATE/">

    <link rel="stylesheet" href="admin/resources/admin_style.css">
</head>
    <style>

        .hidden {
            display: none !important;
        }

        .loader {
            text-align: center;
            position: relative;
        }

        .cog-container {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cog {
            font-size: 48px;
            animation-duration: 2s;
            animation-iteration-count: infinite;
            transform-origin: center;
        }

        .cog-white {
            color: white; /* White color for the first cog */
            animation: rotate-clockwise 2s linear infinite;
        }

        .cog-yellow {
            color: #FFD100; /* Yellow color for the second cog */
            animation: rotate-anticlockwise 2s linear infinite;
        }

        .cog-grey {
            color: grey; /* Grey color for the third cog */
            animation: rotate-clockwise 2s linear infinite;
        }

        .loader p {
            font-size: 24px;
            color: #333; /* Black color */
            margin-top: 20px;
        }

        @keyframes rotate-clockwise {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @keyframes rotate-anticlockwise {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(-360deg); }
        }

        /* Style for the creative design */
        .creative-design {
            position: absolute;
            width: 100px;
            height: 100px;
            background-color: #333; /* Black color */
            border-radius: 50%;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(0.8); }
            50% { transform: scale(1); }
        }

        /* Style for the text animation */
        .text-animation {
            font-size: 32px;
            color: white;
            white-space: nowrap;
            overflow: hidden;
            width: 0;
            animation: text-slide 5s linear forwards, text-fade-in 1s 4s linear forwards;
            font-family: 'Kanit', sans-serif; /* Use your preferred font family */
            background: linear-gradient(to right, yellow 0%, yellow 46%, white 47%, white 100%);
            height: 55px;
            align-items: center;
            display: flex;
            gap: 35px;
            padding-right: 7px;
        }

        @keyframes text-slide {
            0% { width: 0; }
            100% { width: 100%; }
        }

        @keyframes text-fade-in {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }
    </style>
<body style="display: flex;">
    
    <?php include '../admin/resources/custom_loader.php'; ?>
    
    <?php include '../admin/resources/landing_sidebar.php'; ?>
    
    <?php include '../admin/resources/landing_maincontent.php'; ?>

    
    <script src="admin/resources/loader.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/7a67a52733.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="admin/resources/DD_R&I_script.js"></script>
    <script src="admin/resources/fetch_resources.js"></script>
    
</body>
</html>