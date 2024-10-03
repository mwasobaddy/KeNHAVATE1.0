<?php
    session_start();

    include("../auth_controller/requirement.php");

    require './mailer/PHPMailer.php';
    require './mailer/SMTP.php';
    require './mailer/Exception.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    if (isset($_POST['sign_up'])) {
        
        $string_acount_status = 'active';
        $string_agree_to_terms_and_conditions = 'disagree';


        $uu_id = generateRandomUUID($length = 10, $con);
        $session_id = generateRandomUUID($length = 10, $con);
        $count_code = filter_input(INPUT_POST, 'Count_Code', FILTER_SANITIZE_STRING);
        $full_name = filter_input(INPUT_POST, 'Names', FILTER_SANITIZE_STRING);
        $id_no = filter_input(INPUT_POST, 'Id_Number', FILTER_SANITIZE_NUMBER_INT);
        $email = filter_input(INPUT_POST, 'Email', FILTER_SANITIZE_STRING);
        $mobile_no = filter_input(INPUT_POST, 'Mobile_Number', FILTER_SANITIZE_NUMBER_INT);
        $staff_no = "not filled";
        $gender = filter_input(INPUT_POST, 'Gender', FILTER_SANITIZE_STRING);
        $OTP_code = generateOTP($length = 6, $con);
        $acount_status = filter_var($string_acount_status, FILTER_SANITIZE_STRING);
        $agree_to_terms_and_conditions = filter_var($string_agree_to_terms_and_conditions, FILTER_SANITIZE_STRING);

        $empty_fields = [];

        $pattern_email = '/^(.+)@(gmail\.com|yahoo\.com)$/i';
        $pattern_number = '/^\d{8}$/';
        
        
        if (!preg_match("/^[A-Za-z ]+$/", $full_name)) {
            $empty_fields[] = 'Invalid Name';
        }
        
        if (!preg_match($pattern_number, $id_no)) {
            $empty_fields[] = 'ID Number is invalid';
        }
        
        if (!preg_match($pattern_email, $email)) {
            $empty_fields[] = 'Email should end with @gmail.com or yahoo.com';
        }

        if (!preg_match($pattern_number, $mobile_no)) {
            $empty_fields[] = 'Mobile Number is invalid';
        }

        if (empty($full_name)) {
            $empty_fields[] = 'Full Name';
        }

        if (empty($id_no)) {
            $empty_fields[] = 'ID Number';
        }

        if (empty($email)) {
            $empty_fields[] = 'Email';
        }

        if (empty($mobile_no)) {
            $empty_fields[] = 'Mobile Number';
        }

        if (empty($gender)) {
            $empty_fields[] = 'Gender';
        }
        
        if (!empty($empty_fields)) {
            
            $_SESSION['input_values'] = [
                'Names' => $full_name,
                'Id_Number' => $id_no,
                'Email' => $email,
                'Mobile_Number' => $mobile_no,
                'Staff_Number' => $staff_no,
                'Gender' => $gender,
                'Count_Code' => $count_code
            ];

            $_SESSION['error_message'] = "Please fill in the following required fields: " . implode(', ', $empty_fields);
            
    
            header("Location: ../kenhavate");

            exit;                    
        }
        else {
            // Encrypt all data to be stored
            $key = 'my-KeNHAsecret-passkey';
            $encrypteduuid = encrypt($uu_id, $key);
            $encryptedsession_id = encrypt($session_id, $key);
            $encryptedfull_name = encrypt($full_name, $key);
            $encryptedid_no = encrypt($id_no, $key);
            $encryptedemail = encrypt($email, $key);
            $encryptedmobile_no = encrypt($mobile_no, $key);
            $encryptedstaff_no = encryptData($staff_no, $key);
            $encryptedOTP_code = encryptData($OTP_code, $key);
            $encryptedacount_status = encryptData($acount_status, $key);
            $encryptedagree_to_terms_and_conditions = encryptData($agree_to_terms_and_conditions, $key);

            // Check if the entered id number, email, mobile number, and staff number exist
            $stmt = $con->prepare("SELECT * FROM users_table WHERE id_no = ? OR email = ? OR mobile_no = ?");
            $stmt->bind_param("sss", $encryptedid_no, $encryptedemail, $encryptedmobile_no);
            $stmt->execute();
            $result = $stmt->get_result();

            if (mysqli_num_rows($result) > 0) {
            
                $_SESSION['input_values'] = [
                    'Names' => $full_name,
                    'Id_Number' => $id_no,
                    'Email' => $email,
                    'Mobile_Number' => $mobile_no,
                    'Gender' => $gender,
                    'Count_Code' => $count_code
                ];
                // Data already exists
                $_SESSION['error_message'] = "Some of the details entered already exist.";
                $_SESSION['dont_have_an_account'] = true;
                header("Location: ../kenhavate");
                exit;
            }

            // Data doesn't exist; insert into the database
            $stmt = $con->prepare("INSERT INTO users_table (uuid, session_id, full_name, id_no, email, mobile_no, staff_no, gender, OTP_code, account_status, t_and_c) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssssssss", $encrypteduuid, $encryptedsession_id, $encryptedfull_name, $encryptedid_no, $encryptedemail, $encryptedmobile_no, $encryptedstaff_no, $gender, $encryptedOTP_code, $encryptedacount_status, $encryptedagree_to_terms_and_conditions);

            if ($stmt->execute()) {
                // Registration successful
                $_SESSION['dont_have_an_account'] = false;
                $_SESSION['signup_form_processed'] = true;
                $_SESSION['success_message'] = "Account successfully created!";
                header("Location: ../kenhavate");
                exit;
            } else {
                // Error occurred during registration
            
                $_SESSION['input_values'] = [
                    'Names' => $full_name,
                    'Id_Number' => $id_no,
                    'Email' => $email,
                    'Mobile_Number' => $mobile_no,
                    'Gender' => $gender,
                    'Count_Code' => $count_code
                ];
                $_SESSION['error_message'] = "An error occurred during registration. Please try again";
                $_SESSION['dont_have_an_account'] = true;
                header("Location: ../kenhavate");
                exit;
            }

            $stmt->close();
        }
    }

    if (isset($_POST['get_code'])) {

        $email = filter_input(INPUT_POST, 'Email', FILTER_SANITIZE_STRING);

        $empty_fields = [];       

        if (empty($email)) {
            $empty_fields[] = 'Email';
        }

        if (!empty($empty_fields)) {
            
            $_SESSION['input_values'] = [
                'Email' => $email
            ];

            $_SESSION['error_message'] = "Please fill in the required field: " . implode(', ', $empty_fields);
            
    
            header("Location: ../kenhavate");
            exit;                    
        }
        else {

            $_SESSION['input_email'] = $email;

            //encrypt email for verification
            $key = 'my-KeNHAsecret-passkey';

            $encryptedemail = encrypt($email, $key);

            $stmt = $con->prepare("SELECT s.*, ss.* FROM staff_table s INNER JOIN staff_sub_table ss ON s.staff_uuid = ss.staff_uuid WHERE s.personal_email = ? OR ss.KeNHA_email = ? LIMIT 1");
            $stmt->bind_param("ss", $encryptedemail, $encryptedemail);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows >= 1) {


                $row = $result->fetch_assoc();

                $db_enc_account_status = $row['account_status'];

                //fetch OTP to send via email
                $db_enc_OTP = $row['OTP_code'];

                //fetch email so that it may be stored in the session
                $db_enc_personal_email = $row['personal_email'];
                $db_enc_KeNHA_email = $row['KeNHA_email'];

                $accountStatusA = accountStatusA();
                $accountStatusB = accountStatusB();
                $accountStatusC = accountStatusC();

                $db_dec_account_status = decryptData($db_enc_account_status, $key);
                $db_dec_db_enc_OTP = decryptData($db_enc_OTP, $key);

                
                if ($db_dec_account_status == $accountStatusA) {

                    $mail = new PHPMailer(true);
                    try {
            
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPAuth = true;
                        $mail->Username = 'kenhainnovation@gmail.com';
                        $mail->Password = 'frnehuvdnrvennph';
                        $mail->SMTPSecure = 'tls';
            
                        $mail->Port = 587;
                        $mail->setFrom('noreply@kenhainnovation.com','KeNHA Innovation Portal');
            
                        $mail->addAddress($email, $email);$mail->Subject = "KeNHA Innovation OTP Code";

                        // Set the Reply-To header to a non-replyable email address
                        $mail->addReplyTo('noreply@kenhainnovation.com', 'No Reply');

                        // Create a styled HTML email body
                        $mail->isHTML(true);
                        $mail->Body = '
                        <html lang="en">
                            <head>
                                <meta charset="UTF-8">
                                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                                <title>OTP Verification</title>
                                <!-- Bootstrap CSS -->
                                <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
                                <style>
                                    body {
                                        background-color: #f5f5f5;
                                        font-family: Arial, sans-serif;
                                        padding: 20px;
                                    }
                                    .container {
                                        border: 0.5px groove black;
                                        border-radius: 5px;
                                        -webkit-box-shadow: 0px 0px 67px rgba(0, 0, 0, 0.1);
                                        -moz-box-shadow: 0px 0px 67px rgba(0, 0, 0, 0.1);
                                        box-shadow: 0px 0px 67px rgba(0, 0, 0, 0.1);
                                        padding: 20px;
                                        margin: 0 auto;
                                        max-width: 600px;
                                    }
                                    .otp-box {
                                        background-color: #fff3cd;
                                        border: 2px solid #ffeeba;
                                        border-radius: 5px;
                                        padding: 20px;
                                        margin-bottom: 20px;
                                    }
                                    .otp-code {
                                        font-size: 24px;
                                        font-weight: bold;
                                        color: #343a40;
                                        text-align: center;
                                        margin-bottom: 20px;
                                    }
                                    .otp-info {
                                        font-size: 16px;
                                        color: #343a40;
                                        text-align: center;
                                        margin-bottom: 20px;
                                    }
                                    .btn {
                                        background-color: #ffc107;
                                        color: #343a40;
                                        border: none;
                                        border-radius: 5px;
                                        padding: 10px 20px;
                                        font-size: 16px;
                                        cursor: pointer;
                                        text-decoration: none;
                                    }
                                    .btn:hover {
                                        background-color: #ffca2c;
                                    }
                                </style>
                            </head>
                            <body>
                                <div class="container" style="border: 0.5px groove black; border-radius: 5px; -webkit-box-shadow: 0px 0px 67px rgba(0, 0, 0, 0.1); -moz-box-shadow: 0px 0px 67px rgba(0, 0, 0, 0.1); box-shadow: 0px 0px 67px rgba(0, 0, 0, 0.1); padding: 20px; margin: 20px auto; max-width: 600px;">
                                <h5 style="text-align: center; color: #343a40; font-size: xx-large;"><strong>KENHAVATE PORTAL</strong></h5>
                                    <div class="otp-box">
                                    <h3 style="text-align: center; color: #343a40;">OTP Verification Code</h3>
                                        <p class="otp-info">As part of our enhanced security measures, 
                                            we have the one-time password (OTP) verification process. 
                                            To proceed, please access the online portal at <u style="color: blue;"><b><i>http://kenhavate.kenha.co.ke/KeNHAVATE</i></b></u>
                                            and enter the below code. This unique code is time-sensitive 
                                            and should be entered promptly to ensure a seamless verification experience. 
                                            Do not share the code with anyone.
                                        </p>
                                        <p class="otp-code">Your OTP: <strong>' . $db_dec_db_enc_OTP . '</strong></p>
                                    </div>
                                    <p style="font-size: 14px; text-align: center; color: #6c757d;">This is an automated message. Do not reply.</p>
                                </div>
                            </body>
                        </html>';
            
                        $mail->send();
        
                        $_SESSION['success_message'] = "Sent!<br>Check your email inbox for the code.";
                    
                        $_SESSION['first_form_processed'] = true;
        
                        header("Location: ../kenhavate");
                        exit;
            
                    } catch (Exception $e) {
                        $_SESSION['signup_form_processed'] = true;
            
                        $_SESSION['input_values'] = [
                            'Email' => $email
                        ];
                        //intitial code
                        //$_SESSION['error_message'] = "Failed to send email. Error: {$mail->ErrorInfo}";

                        $_SESSION['error_message'] = "Failed to send email. Make sure you are connected to the internet";
        
                        header("Location: ../kenhavate");
                        exit;
                    }
                }

                elseif ($db_dec_account_status == $accountStatusB) {

                    $_SESSION['error_message'] = "account your account is under review";
        
                    header("Location: ../kenhavate");
                    exit;
                }

                elseif ($db_dec_account_status == $accountStatusC) {

                    $_SESSION['error_message'] = "account your account was reviewed and you have been banned";
        
                    header("Location: ../kenhavate");
                    exit;
                }

                else{
                    
                    $_SESSION['error_message'] = "You account has a problem please contact us";
        
                    header("Location: ../kenhavate");
                    exit;
                }
            }
            
            else {
                //check for user_table
                $stmt = $con->prepare("SELECT * FROM users_table WHERE email = ? LIMIT 1");
                $stmt->bind_param("s", $encryptedemail);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows == 1) {

                    $row = $result->fetch_assoc();

                    $db_enc_account_status = $row['account_status'];
        
                    //fetch OTP to send via email
                    $db_enc_OTP = $row['OTP_code'];

                    //fetch the email
                    $db_enc_email = $row['email'];
        
                    //method for account status
                    $accountStatusA = accountStatusA();
                    $accountStatusB = accountStatusB();
                    $accountStatusC = accountStatusC();
                    
                    $db_dec_account_status = decryptData($db_enc_account_status, $key);
                    $db_dec_db_enc_OTP = decryptData($db_enc_OTP, $key);
        
                    //check the account status
                
                    if ($db_dec_account_status == $accountStatusA) {

                        $mail = new PHPMailer(true);
                        try {
                
                            $mail->isSMTP();
                            $mail->Host = 'smtp.gmail.com';
                            $mail->SMTPAuth = true;
                            $mail->Username = 'kenhainnovation@gmail.com';
                            $mail->Password = 'frnehuvdnrvennph';
                            $mail->SMTPSecure = 'tls';
                
                            $mail->Port = 587;
                            $mail->setFrom('noreply@kenhainnovation.com','KeNHA Innovation Portal');
                
                            $mail->addAddress($email, $email);$mail->Subject = "KeNHA Innovation OTP Code";

                            // Set the Reply-To header to a non-replyable email address
                            $mail->addReplyTo('noreply@kenhainnovation.com', 'No Reply');

                            // Create a styled HTML email body
                            $mail->isHTML(true);
                            $mail->Body = '
                            <html lang="en">
                                <head>
                                    <meta charset="UTF-8">
                                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                                    <title>OTP Verification</title>
                                    <!-- Bootstrap CSS -->
                                    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
                                    <style>
                                        body {
                                            background-color: #f5f5f5;
                                            font-family: Arial, sans-serif;
                                            padding: 20px;
                                        }
                                        .container {
                                            border: 0.5px groove black;
                                            border-radius: 5px;
                                            -webkit-box-shadow: 0px 0px 67px rgba(0, 0, 0, 0.1);
                                            -moz-box-shadow: 0px 0px 67px rgba(0, 0, 0, 0.1);
                                            box-shadow: 0px 0px 67px rgba(0, 0, 0, 0.1);
                                            padding: 20px;
                                            margin: 0 auto;
                                            max-width: 600px;
                                        }
                                        .otp-box {
                                            background-color: #fff3cd;
                                            border: 2px solid #ffeeba;
                                            border-radius: 5px;
                                            padding: 20px;
                                            margin-bottom: 20px;
                                        }
                                        .otp-code {
                                            font-size: 24px;
                                            font-weight: bold;
                                            color: #343a40;
                                            text-align: center;
                                            margin-bottom: 20px;
                                        }
                                        .otp-info {
                                            font-size: 16px;
                                            color: #343a40;
                                            text-align: center;
                                            margin-bottom: 20px;
                                        }
                                        .btn {
                                            background-color: #ffc107;
                                            color: #343a40;
                                            border: none;
                                            border-radius: 5px;
                                            padding: 10px 20px;
                                            font-size: 16px;
                                            cursor: pointer;
                                            text-decoration: none;
                                        }
                                        .btn:hover {
                                            background-color: #ffca2c;
                                        }
                                    </style>
                                </head>
                                <body>
                                    <div class="container" style="border: 0.5px groove black; border-radius: 5px; -webkit-box-shadow: 0px 0px 67px rgba(0, 0, 0, 0.1); -moz-box-shadow: 0px 0px 67px rgba(0, 0, 0, 0.1); box-shadow: 0px 0px 67px rgba(0, 0, 0, 0.1); padding: 20px; margin: 20px auto; max-width: 600px;">
                                    <h5 style="text-align: center; color: #343a40; font-size: xx-large;"><strong>KENHAVATE PORTAL</strong></h5>
                                        <div class="otp-box">
                                        <h3 style="text-align: center; color: #343a40;">OTP Verification Code</h3>
                                            <p class="otp-info">As part of our enhanced security measures, 
                                                we have the one-time password (OTP) verification process. 
                                                To proceed, please access the online portal at <u style="color: blue;"><b><i>http://kenhavate.kenha.co.ke/KeNHAVATE</i></b></u>
                                                and enter the below code. This unique code is time-sensitive 
                                                and should be entered promptly to ensure a seamless verification experience. 
                                                Do not share the code with anyone.
                                            </p>
                                            <p class="otp-code">Your OTP: <strong>' . $db_dec_db_enc_OTP . '</strong></p>
                                        </div>
                                        <p style="font-size: 14px; text-align: center; color: #6c757d;">This is an automated message. Do not reply.</p>
                                    </div>
                                </body>
                            </html>';
                
                            $mail->send();
            
                            $_SESSION['success_message'] = "Sent!<br>Check your email inbox for the code.";
                        
                            $_SESSION['first_form_processed'] = true;
            
                            header("Location: ../kenhavate");
                            exit;
                
                        } catch (Exception $e) {
                            $_SESSION['signup_form_processed'] = true;
                            //intitial code
                            //$_SESSION['error_message'] = "Failed to send email. Error: {$mail->ErrorInfo}";

                            $_SESSION['input_values'] = [
                                'Email' => $email
                            ];
        
                            $_SESSION['error_message'] = "Failed to send email. Make sure you are connected to the internet";
            
                            header("Location: ../kenhavate");
                            exit;
                        }
                    }

                    elseif ($db_dec_account_status == $accountStatusB) {

                        $_SESSION['error_message'] = "account your account is under review";
            
                        header("Location: ../kenhavate");
                        exit;
                    }

                    elseif ($db_dec_account_status == $accountStatusC) {

                        $_SESSION['error_message'] = "account your account was reviewed and you have been banned";
            
                        header("Location: ../kenhavate");
                        exit;
                    }

                    else{
                        
                        $_SESSION['error_message'] = "You account has a problem please contact us";
            
                        header("Location: ../kenhavate");
                        exit;
                    }

                }
                else {
                    
            
                    $_SESSION['input_values'] = [
                        'Email' => $email
                    ];
                    
                    $_SESSION['signup_form_processed'] = true;
        
                    $_SESSION['error_message'] = "Wrong Email Address";
        
                    header("Location: ../kenhavate");
                    exit;
                }
                $stmt->close();
            }
            $stmt->close();
        }
    }
    elseif (isset($_POST['resend'])) {

        $input_email = $_SESSION['input_email'];

        $email = filter_var($input_email, FILTER_SANITIZE_STRING);
        
        //encrypt email for verification
        $key = 'my-KeNHAsecret-passkey';
        $key = 'my-KeNHAsecret-passkey';

        $encryptedemail = encrypt($email, $key);

        $stmt = $con->prepare("SELECT s.*, ss.* FROM staff_table s INNER JOIN staff_sub_table ss ON s.staff_uuid = ss.staff_uuid WHERE s.personal_email = ? OR ss.KeNHA_email = ? LIMIT 1");
        $stmt->bind_param("ss", $encryptedemail, $encryptedemail);
        $stmt->execute();
        $result = $stmt->get_result();


        if ($result->num_rows >= 1) {


            $row = $result->fetch_assoc();

            $db_enc_account_status = $row['account_status'];

            //fetch OTP to send via email
            $db_enc_OTP = $row['OTP_code'];

            //fetch email so that it may be stored in the session
            $db_enc_personal_email = $row['personal_email'];
            $db_enc_KeNHA_email = $row['KeNHA_email'];

            $accountStatusA = accountStatusA();
            $accountStatusB = accountStatusB();
            $accountStatusC = accountStatusC();

            $db_dec_account_status = decryptData($db_enc_account_status, $key);
            $db_dec_db_enc_OTP = decryptData($db_enc_OTP, $key);

            
            if ($db_dec_account_status == $accountStatusA) {

                $mail = new PHPMailer(true);
                try {
        
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'kenhainnovation@gmail.com';
                    $mail->Password = 'frnehuvdnrvennph';
                    $mail->SMTPSecure = 'tls';
        
                    $mail->Port = 587;
                    $mail->setFrom('noreply@kenhainnovation.com','KeNHA Innovation Portal');
        
                    $mail->addAddress($email, $email);$mail->Subject = "KeNHA Innovation OTP Code";

                    // Set the Reply-To header to a non-replyable email address
                    $mail->addReplyTo('noreply@kenhainnovation.com', 'No Reply');

                    // Create a styled HTML email body
                    $mail->isHTML(true);
                    $mail->Body = '
                    <html lang="en">
                        <head>
                            <meta charset="UTF-8">
                            <meta name="viewport" content="width=device-width, initial-scale=1.0">
                            <title>OTP Verification</title>
                            <!-- Bootstrap CSS -->
                            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
                            <style>
                                body {
                                    background-color: #f5f5f5;
                                    font-family: Arial, sans-serif;
                                    padding: 20px;
                                }
                                .container {
                                    border: 0.5px groove black;
                                    border-radius: 5px;
                                    -webkit-box-shadow: 0px 0px 67px rgba(0, 0, 0, 0.1);
                                    -moz-box-shadow: 0px 0px 67px rgba(0, 0, 0, 0.1);
                                    box-shadow: 0px 0px 67px rgba(0, 0, 0, 0.1);
                                    padding: 20px;
                                    margin: 0 auto;
                                    max-width: 600px;
                                }
                                .otp-box {
                                    background-color: #fff3cd;
                                    border: 2px solid #ffeeba;
                                    border-radius: 5px;
                                    padding: 20px;
                                    margin-bottom: 20px;
                                }
                                .otp-code {
                                    font-size: 24px;
                                    font-weight: bold;
                                    color: #343a40;
                                    text-align: center;
                                    margin-bottom: 20px;
                                }
                                .otp-info {
                                    font-size: 16px;
                                    color: #343a40;
                                    text-align: center;
                                    margin-bottom: 20px;
                                }
                                .btn {
                                    background-color: #ffc107;
                                    color: #343a40;
                                    border: none;
                                    border-radius: 5px;
                                    padding: 10px 20px;
                                    font-size: 16px;
                                    cursor: pointer;
                                    text-decoration: none;
                                }
                                .btn:hover {
                                    background-color: #ffca2c;
                                }
                            </style>
                        </head>
                        <body>
                            <div class="container" style="border: 0.5px groove black; border-radius: 5px; -webkit-box-shadow: 0px 0px 67px rgba(0, 0, 0, 0.1); -moz-box-shadow: 0px 0px 67px rgba(0, 0, 0, 0.1); box-shadow: 0px 0px 67px rgba(0, 0, 0, 0.1); padding: 20px; margin: 20px auto; max-width: 600px;">
                            <h5 style="text-align: center; color: #343a40; font-size: xx-large;"><strong>KENHAVATE PORTAL</strong></h5>
                                <div class="otp-box">
                                <h3 style="text-align: center; color: #343a40;">Resent OTP Verification Code</h3>
                                    <p class="otp-info">As part of our enhanced security measures, 
                                        we have the one-time password (OTP) verification process. 
                                        To proceed, please access the online portal at <u style="color: blue;"><b><i>http://kenhavate.kenha.co.ke/KeNHAVATE</i></b></u>
                                        and enter the below code. This unique code is time-sensitive 
                                        and should be entered promptly to ensure a seamless verification experience. 
                                        Do not share the code with anyone.
                                    </p>
                                    <p class="otp-code">Your OTP: <strong>' . $db_dec_db_enc_OTP . '</strong></p>
                                </div>
                                <p style="font-size: 14px; text-align: center; color: #6c757d;">This is an automated message. Do not reply.</p>
                            </div>
                        </body>
                    </html>';
        
                    $mail->send();
    
                    $_SESSION['success_message'] = "Sent!<br>Check your email inbox for the code.";
                
                    $_SESSION['first_form_processed'] = true;
    
                    header("Location: ../kenhavate");
                    exit;
        
                } catch (Exception $e) {
                    $_SESSION['signup_form_processed'] = true;
                    //intitial code
                    //$_SESSION['error_message'] = "Failed to send email. Error: {$mail->ErrorInfo}";
            
                    $_SESSION['input_values'] = [
                        'Email' => $email
                    ];

                    $_SESSION['error_message'] = "Failed to send email. Make sure you are connected to the internet";
    
                    header("Location: ../kenhavate");
                    exit;
                }
            }

            elseif ($db_dec_account_status == $accountStatusB) {

                $_SESSION['error_message'] = "account your account is under review";
    
                header("Location: ../kenhavate");
                exit;
            }

            elseif ($db_dec_account_status == $accountStatusC) {

                $_SESSION['error_message'] = "account your account was reviewed and you have been banned";
    
                header("Location: ../kenhavate");
                exit;
            }

            else{
                
                $_SESSION['error_message'] = "You account has a problem please contact us";
    
                header("Location: ../kenhavate");
                exit;
            }
        }

        else {
            //not found in the admin side
            //check for user_table
            $stmt = $con->prepare("SELECT * FROM users_table WHERE email = ? LIMIT 1");
            $stmt->bind_param("s", $encryptedemail);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {

                $row = $result->fetch_assoc();

                $db_enc_account_status = $row['account_status'];
    
                //fetch OTP to send via email
                $db_enc_OTP = $row['OTP_code'];

                //fetch the email
                $db_enc_email = $row['email'];
    
                //method for account status
                $accountStatusA = accountStatusA();
                $accountStatusB = accountStatusB();
                $accountStatusC = accountStatusC();
                
                $db_dec_account_status = decryptData($db_enc_account_status, $key);
                $db_dec_db_enc_OTP = decryptData($db_enc_OTP, $key);
    
                //check the account status
            
                if ($db_dec_account_status == $accountStatusA) {

                    $mail = new PHPMailer(true);
                    try {
            
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPAuth = true;
                        $mail->Username = 'kenhainnovation@gmail.com';
                        $mail->Password = 'frnehuvdnrvennph';
                        $mail->SMTPSecure = 'tls';
            
                        $mail->Port = 587;
                        $mail->setFrom('noreply@kenhainnovation.com','KeNHA Innovation Portal');
            
                        $mail->addAddress($email, $email);$mail->Subject = "KeNHA Innovation OTP Code";

                        // Set the Reply-To header to a non-replyable email address
                        $mail->addReplyTo('noreply@kenhainnovation.com', 'No Reply');

                        // Create a styled HTML email body
                        $mail->isHTML(true);
                        $mail->Body = '
                        <html lang="en">
                            <head>
                                <meta charset="UTF-8">
                                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                                <title>OTP Verification</title>
                                <!-- Bootstrap CSS -->
                                <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
                                <style>
                                    body {
                                        background-color: #f5f5f5;
                                        font-family: Arial, sans-serif;
                                        padding: 20px;
                                    }
                                    .container {
                                        border: 0.5px groove black;
                                        border-radius: 5px;
                                        -webkit-box-shadow: 0px 0px 67px rgba(0, 0, 0, 0.1);
                                        -moz-box-shadow: 0px 0px 67px rgba(0, 0, 0, 0.1);
                                        box-shadow: 0px 0px 67px rgba(0, 0, 0, 0.1);
                                        padding: 20px;
                                        margin: 0 auto;
                                        max-width: 600px;
                                    }
                                    .otp-box {
                                        background-color: #fff3cd;
                                        border: 2px solid #ffeeba;
                                        border-radius: 5px;
                                        padding: 20px;
                                        margin-bottom: 20px;
                                    }
                                    .otp-code {
                                        font-size: 24px;
                                        font-weight: bold;
                                        color: #343a40;
                                        text-align: center;
                                        margin-bottom: 20px;
                                    }
                                    .otp-info {
                                        font-size: 16px;
                                        color: #343a40;
                                        text-align: center;
                                        margin-bottom: 20px;
                                    }
                                    .btn {
                                        background-color: #ffc107;
                                        color: #343a40;
                                        border: none;
                                        border-radius: 5px;
                                        padding: 10px 20px;
                                        font-size: 16px;
                                        cursor: pointer;
                                        text-decoration: none;
                                    }
                                    .btn:hover {
                                        background-color: #ffca2c;
                                    }
                                </style>
                            </head>
                            <body>
                                <div class="container" style="border: 0.5px groove black; border-radius: 5px; -webkit-box-shadow: 0px 0px 67px rgba(0, 0, 0, 0.1); -moz-box-shadow: 0px 0px 67px rgba(0, 0, 0, 0.1); box-shadow: 0px 0px 67px rgba(0, 0, 0, 0.1); padding: 20px; margin: 20px auto; max-width: 600px;">
                                <h5 style="text-align: center; color: #343a40; font-size: xx-large;"><strong>KENHAVATE PORTAL</strong></h5>
                                    <div class="otp-box">
                                    <h3 style="text-align: center; color: #343a40;">Resent OTP Verification Code</h3>
                                        <p class="otp-info">As part of our enhanced security measures, 
                                            we have the one-time password (OTP) verification process. 
                                            To proceed, please access the online portal at <u style="color: blue;"><b><i>http://kenhavate.kenha.co.ke/KeNHAVATE</i></b></u>
                                            and enter the below code. This unique code is time-sensitive 
                                            and should be entered promptly to ensure a seamless verification experience. 
                                            Do not share the code with anyone.
                                        </p>
                                        <p class="otp-code">Your OTP: <strong>' . $db_dec_db_enc_OTP . '</strong></p>
                                    </div>
                                    <p style="font-size: 14px; text-align: center; color: #6c757d;">This is an automated message. Do not reply.</p>
                                </div>
                            </body>
                        </html>';
            
                        $mail->send();
        
                        $_SESSION['success_message'] = "Sent!<br>Check your email inbox for the code.";
                    
                        $_SESSION['first_form_processed'] = true;
        
                        header("Location: ../kenhavate");
                        exit;
            
                    } catch (Exception $e) {
                        $_SESSION['signup_form_processed'] = true;
                        //intitial code
                        //$_SESSION['error_message'] = "Failed to send email. Error: {$mail->ErrorInfo}";
                        
                        $_SESSION['input_values'] = [
                            'Email' => $email
                        ];
    
                        $_SESSION['error_message'] = "Failed to send email. Make sure you are connected to the internet";
        
                        header("Location: ../kenhavate");
                        exit;
                    }
                }

                elseif ($db_dec_account_status == $accountStatusB) {

                    $_SESSION['error_message'] = "account your account is under review";
        
                    header("Location: ../kenhavate");
                    exit;
                }

                elseif ($db_dec_account_status == $accountStatusC) {

                    $_SESSION['error_message'] = "account your account was reviewed and you have been banned";
        
                    header("Location: ../kenhavate");
                    exit;
                }

                else{
                    
                    $_SESSION['error_message'] = "You account has a problem please contact us";
        
                    header("Location: ../kenhavate");
                    exit;
                }

            }
            else {
            
                $_SESSION['input_values'] = [
                    'Email' => $email
                ];
                
                $_SESSION['signup_form_processed'] = true;
    
                $_SESSION['error_message'] = "Wrong Email Address";
    
                header("Location: ../kenhavate");
                exit;
            }
            $stmt->close();
        }
        $stmt->close();
    }

    //in the sign in remeber to store session email and session id so as to allow login
    if (isset($_POST['sign_in'])) {
        
        // Retrieve the email value from the session
        $input_email = $_SESSION['input_email'];

        $email = filter_var($input_email, FILTER_SANITIZE_STRING);

        $OTP_code = filter_input(INPUT_POST, 'OTP_code', FILTER_SANITIZE_NUMBER_INT);

        $empty_fields = [];

        if (empty($OTP_code)) {
            $empty_fields[] = 'OTP code';
        }

        if (!empty($empty_fields)) {

            $_SESSION['error_message'] = "Please fill in the required field: " . implode(', ', $empty_fields);
            
    
            header("Location: ../kenhavate");
            exit;                    
        }
        else {

            $key = 'my-KeNHAsecret-passkey';
            $encryptedemail = encrypt($email, $key);


            if (isset($_SESSION['input_email'])) {
                
                //check if the email stored in the session is a valid one
                $stmt = $con->prepare("SELECT s.*, ss.* FROM staff_table s INNER JOIN staff_sub_table ss ON s.staff_uuid = ss.staff_uuid WHERE s.personal_email = ? OR ss.KeNHA_email = ? LIMIT 1");
                $stmt->bind_param("ss", $encryptedemail, $encryptedemail);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows >= 1) {

                    $row = $result->fetch_assoc();
        
                    //OTP_code will be used to authetify the code entered is correct
                    $db_enc_OTP = $row['OTP_code'];
                    //session_id will be used in the check log in fuction
                    $db_enc_session = $row['session_id'];
                    //staff_uuid will be used to track idea submitted to a database
                    $db_enc_staff_uuid = $row['staff_uuid'];
                    //account_type will be used to redirect the user to the appropriate page in the agree page
                    $db_enc_account_type = $row['account_type'];

                    //decrypt session_id to be used in the check login function
                    $db_dec_session = decrypt($db_enc_session, $key);
                    //decrypt OTP from db to verify if the entered match with the one on the database
                    $db_dec_OTP = decryptData($db_enc_OTP, $key);
                    //staff_uuid will be used to track idea submitted to a database
                    $db_dec_staff_uuid = decrypt($db_enc_staff_uuid, $key);

                    if ($OTP_code == $db_dec_OTP) {   
                        
                        //generate a new OTP
                        $new_OTP_code = generateOTP($length = 6, $con);
                        $encryptedNewOTP = encryptData($new_OTP_code, $key);

                        $stmt = $con->prepare("UPDATE staff_table s INNER JOIN staff_sub_table ss ON s.staff_uuid = ss.staff_uuid SET s.OTP_code = ? WHERE s.personal_email = ? OR ss.KeNHA_email = ?");
                        $stmt->bind_param("sss", $encryptedNewOTP, $encryptedemail, $encryptedemail);
                        $stmt->execute();

                        if ($stmt->affected_rows > 0) {

                            $_SESSION['account_type'] = $db_enc_account_type;
                            $_SESSION['session_id'] = $db_dec_session;
                            $_SESSION['db_dec_staff_uuid'] = $db_dec_staff_uuid;
                            
                            header("Location: ../terms_and_conditions");
                            exit;
                        }
                        else{
                            $_SESSION['first_form_processed'] = true;
                            
                            $_SESSION['error_message'] = "An error occured kindly renter your code";
                            
                            header("Location: ../kenhavate");
                            exit;
                        }
                        $stmt->close();
                    }
                    else{
                        $_SESSION['first_form_processed'] = true;
                        
                        $_SESSION['error_message'] = "Wrong code entered";
                        
                        header("Location: ../kenhavate");
                        exit;
                    }
                }
                else {
            
                    $stmt = $con->prepare("SELECT * FROM users_table WHERE email = ? LIMIT 1");
                    $stmt->bind_param("s", $encryptedemail);
                    $stmt->execute();
                    $result = $stmt->get_result();
            
                    if ($result->num_rows == 1) {

                        $row = $result->fetch_assoc();
            
                        //OTP_code will be used to authetify the code entered is correct
                        $db_enc_OTP = $row['OTP_code'];
                        //session_id will be used in the check log in fuction
                        $db_enc_session = $row['session_id'];
                        //uuid will be used to track idea submitted to a database
                        $db_enc_uuid = $row['uuid'];
        
                        //decrypt session_id to be used in the check login function
                        $db_dec_session = decrypt($db_enc_session, $key);
                        //decrypt OTP from db to verify if the entered match with the one on the database
                        $db_dec_OTP = decryptData($db_enc_OTP, $key);
                        //decrypt session_id to be used in the check login function
                        $db_dec_uuid = decrypt($db_enc_uuid, $key);


                        //check if OTPS match
                        if ($OTP_code == $db_dec_OTP) {
            
                            //generate a new OTP
                            $new_OTP_code = generateOTP($length = 6, $con);
                            $encryptedNewOTP = encryptData($new_OTP_code, $key);
            
                            // Perform database update to store the new OTP for future verification
                            $stmt = $con->prepare("UPDATE users_table SET OTP_code = ? WHERE email = ?");
                            $stmt->bind_param("ss", $encryptedNewOTP, $encryptedemail);
                            $stmt->execute();

                            if ($stmt->affected_rows > 0) {

                                $_SESSION['account_type'] = $db_enc_account_type;
                                $_SESSION['session_id'] = $db_dec_session;
                                $_SESSION['db_dec_uuid'] = $db_dec_uuid;
                                $_SESSION['user_OTP_code'] = false;
                                
                                header("Location: ../terms_and_conditions");
                                exit;
                            }
                            else{
                                $_SESSION['first_form_processed'] = true;
                                
                                $_SESSION['error_message'] = "An error occured kindly renter your code";

                                header("Location: ../kenhavate");
                                exit;
                            }
                            $stmt->close();
            
                        }
                        else{
                            $_SESSION['first_form_processed'] = true;
                            
                            $_SESSION['error_message'] = "Wrong code entered";
                            
                            header("Location: ../kenhavate");
                            exit;
                        }
                    }
                    else {
                        $_SESSION['signup_form_processed'] = true;
                        
                        $_SESSION['error_message'] = "Re-enter your email";
                        
                        header("Location: ../kenhavate");
                        exit;
                    }
                    $stmt->close();
                }
                $stmt->close();
            }

            else{
                //code to display sign in form since the session email is not set or session email is not valid
                $_SESSION['signup_form_processed'] = true;
                $_SESSION['error_message'] = "Re-enter your email";
                header("Location: ../kenhavate");
                exit;
            }
        }
    }

    // redirect to different pages if user= user and etc
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if (isset($_POST["agree"])) {
            // Retrieve the email value from the session and check if it exists in the database
            if (isset($_SESSION['input_email'])) {

                $key = 'my-KeNHAsecret-passkey';

                $input_email = $_SESSION['input_email'];

                $email = filter_var($input_email, FILTER_SANITIZE_STRING);
        
                $encryptedemail = encrypt($email, $key);

                if (isset($_SESSION['db_dec_staff_uuid'])) {

                    $db_dec_staff_uuid = $_SESSION['db_dec_staff_uuid'];
    
                    //check if the email exists in the database
                    $stmt = $con->prepare("SELECT s.*, ss.* FROM staff_table s INNER JOIN staff_sub_table ss ON s.staff_uuid = ss.staff_uuid WHERE s.personal_email = ? OR ss.KeNHA_email = ? LIMIT 1");
                    $stmt->bind_param("ss", $encryptedemail, $encryptedemail);
                    $stmt->execute();
                    $result = $stmt->get_result();
    
                    if ($result->num_rows >= 1) {
    
                        $row = $result->fetch_assoc();
                        
                        $db_enc_staff_uuid = $row['staff_uuid'];
                        $db_enc_account_type = $row['account_type'];
                        $db_enc_first_name = $row['first_name'];
    
                        //encrypt the staff_uuid to check if it exist
                        $enc_staff_uuid = encrypt($db_dec_staff_uuid, $key);
    
                        if ($db_enc_staff_uuid == $enc_staff_uuid) {
    
                            
                            $dec_account_type = decrypt($db_enc_account_type, $key);
    
                            $accountTypeA = accountTypeA();
                            $accountTypeB = accountTypeB();
                            $accountTypeC = accountTypeC();
    
                            if ($dec_account_type == $accountTypeA) {
                    
                                $_SESSION['success_message'] = "Login Success!!";
                    
                                header("Location: ../home");
                                exit;
                            }
                            else if ($dec_account_type == $accountTypeB) {
                                //sme landing page
                                //check if email contained kenha.co.ke
                                // if yes redirect to sme landing page and if no redirect to user

                                if (strpos($input_email, 'kenha.co.ke') !== false) {

                                    $_SESSION['success_message'] = "Login Success!!";
                        
                                    header("Location: ../subject_matter_expert");
                                    exit;
                                }
                                else {
                    
                                    $_SESSION['success_message'] = "Login Success!!";
                        
                                    header("Location: ../home");
                                    exit;
                                }
                            }
                            else if ($dec_account_type == $accountTypeC) {
                                //dd r and i landing page
                                //check if email contained kenha.co.ke
                                // if yes redirect to sme landing page and if no redirect to user

                                if (strpos($input_email, 'kenha.co.ke') !== false) {

                                    $_SESSION['success_message'] = "Login Success!!";
                    
                                    header("Location: ../DD_Research_and_Innovation");
                                    exit;
                                }
                                else {
                    
                                    $_SESSION['success_message'] = "Login Success!!";
                        
                                    header("Location: ../home");
                                    exit;
                                }
                            }
                            else {
                                
                                $_SESSION['error_message'] = "Your account has a problem contact us";
                                
                                header("Location: ../kenhavate");
                                exit;
                            }
                        }
                        else {
                
                            $_SESSION['signup_form_processed'] = true;
                
                            $_SESSION['error_message'] = "Re-enter your email";
                
                            header("Location: ../kenhavate");
                            exit;
                        }
                    }
                    else{
                        //check for set user id
                        if (isset($_SESSION['db_dec_uuid'])) {
                            
                            $db_dec_uuid = $_SESSION['db_dec_uuid'];
            
                            $stmt = $con->prepare("SELECT * FROM users_table WHERE email = ? LIMIT 1");
                            $stmt->bind_param("s", $encryptedemail);
                            $stmt->execute();
                            $result = $stmt->get_result();
                    
                            if ($result->num_rows == 1) {
        
                                $row = $result->fetch_assoc();
                                
                                $db_enc_uuid = $row['uuid'];
                                $db_enc_account_type = $row['account_type'];
                                $db_enc_full_name = $row['full_name'];
        
                                //encrypt the staff_uuid to check if it exist
                                $enc_uuid = encrypt($db_dec_uuid, $key);
    
                                if ($db_enc_uuid == $enc_uuid) {
                        
                                    $_SESSION['success_message'] = "Login Success!!";
                        
                                    header("Location: ../home");
                                    exit;
                                }
                                else{
                    
                                    $_SESSION['signup_form_processed'] = true;
                        
                                    $_SESSION['error_message'] = "Re-enter your email";
                        
                                    header("Location: ../kenhavate");
                                    exit;
                                }
                            }
                            else{
                    
                                $_SESSION['signup_form_processed'] = true;
                    
                                $_SESSION['error_message'] = "Re-enter your email";
                    
                                header("Location: ../kenhavate");
                                exit;
                            }
                            $stmt->close();
    
                        }
                        else {
                    
                            $_SESSION['input_email'] = null;
                    
                            $_SESSION['db_dec_uuid'] = null;
                    
                            $_SESSION['signup_form_processed'] = true;
                
                            $_SESSION['error_message'] = "Re-enter your email";
                
                            header("Location: ../kenhavate");
                            exit;
                        }
                    }
                    $stmt->close();
                }
                else {
                    //check for set user id
                    if (isset($_SESSION['db_dec_uuid'])) {
                        
                        $db_dec_uuid = $_SESSION['db_dec_uuid'];
        
                        $stmt = $con->prepare("SELECT * FROM users_table WHERE email = ? LIMIT 1");
                        $stmt->bind_param("s", $encryptedemail);
                        $stmt->execute();
                        $result = $stmt->get_result();
                
                        if ($result->num_rows == 1) {
    
                            $row = $result->fetch_assoc();
                            
                            $db_enc_uuid = $row['uuid'];
                            $db_enc_account_type = $row['account_type'];
                            $db_enc_full_name = $row['full_name'];
    
                            //encrypt the staff_uuid to check if it exist
                            $enc_uuid = encrypt($db_dec_uuid, $key);

                            if ($db_enc_uuid == $enc_uuid) {
                    
                                $_SESSION['success_message'] = "Login Success!!";
                    
                                header("Location: ../home");
                                exit;
                            }
                            else{
                
                                $_SESSION['signup_form_processed'] = true;
                    
                                $_SESSION['error_message'] = "Re-enter your email";
                    
                                header("Location: ../kenhavate");
                                exit;
                            }
                        }
                        else{
                
                            $_SESSION['signup_form_processed'] = true;
                
                            $_SESSION['error_message'] = "Re-enter your email";
                
                            header("Location: ../kenhavate");
                            exit;
                        }
                        $stmt->close();

                    }
                    else {
                
                        $_SESSION['input_email'] = null;
                
                        $_SESSION['db_dec_uuid'] = null;
                
                        $_SESSION['signup_form_processed'] = true;
            
                        $_SESSION['error_message'] = "Re-enter your email";
            
                        header("Location: ../kenhavate");
                        exit;
                    }
                }
            }
            else {
                //return to index page and nullify the email
                
                $_SESSION['input_email'] = null;
                
                $_SESSION['db_dec_uuid'] = null;
                
                $_SESSION['signup_form_processed'] = true;
                
                $_SESSION['error_message'] = "Re-enter your email";
                
                header("Location: ../kenhavate");
                exit;
            }
        } elseif (isset($_POST["disagree"])) {
            
            
            $_SESSION['input_values'] = [
                'Email' => $email
            ];
                
            $_SESSION['error_message'] = "You are not allowed to use our services if you dont agree to our terms";

            header("Location: ../kenhavate");
            exit;
        }
    }

    
    session_unset();

    // Destroy the session
    session_destroy();

    // Redirect to the index.php page
    header("Location: /kenhavate");
    exit;

?>