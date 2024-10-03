<?php
    session_start();

    include("../auth_controller/requirement.php");

    require './mailer/PHPMailer.php';
    require './mailer/SMTP.php';
    require './mailer/Exception.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    if (isset($_POST['upload_idea'])) {

        $key = 'my-KeNHAsecret-passkey';

        if (isset($_SESSION['uuid'])) {

            $db_enc_staff_uuid = $_SESSION['uuid'];

            $stmt2 = $con->prepare("SELECT s.*, ss.* FROM staff_table s INNER JOIN staff_sub_table ss ON s.staff_uuid = ss.staff_uuid WHERE s.staff_uuid = ? LIMIT 1");
            $stmt2->bind_param("s", $db_enc_staff_uuid);
            $stmt2->execute();
            $result2 = $stmt2->get_result();

            if ($result2->num_rows == 1) {

                $row = $result2->fetch_assoc();
                                
                                
                // Access email from the result set
                $personal_email = decrypt($row['personal_email'], $key);
                $KeNHA_email =  decrypt($row['KeNHA_email'], $key);
    
                $ideaTitle = filter_input(INPUT_POST, 'ideaTitle', FILTER_SANITIZE_STRING);
                $innovationAreas = filter_input(INPUT_POST, 'innovationAreas', FILTER_SANITIZE_STRING);
                $briefDescription = filter_input(INPUT_POST, 'briefDescription', FILTER_SANITIZE_STRING);
                $problemStatement = filter_input(INPUT_POST, 'problemStatement', FILTER_SANITIZE_STRING);
                $proposedSolution = filter_input(INPUT_POST, 'proposedSolution', FILTER_SANITIZE_STRING);
                $costBenefitAnalysis = filter_input(INPUT_POST, 'costBenefitAnalysis', FILTER_SANITIZE_STRING);
                $authorsInterests = filter_input(INPUT_POST, 'authorsInterests', FILTER_SANITIZE_STRING);

                $empty_fields = [];

                //check if fields are empty
                if (empty($ideaTitle)) {
                    $empty_fields[] = 'Idea Title field is empty';
                }

                if (empty($innovationAreas)) {
                    $empty_fields[] = 'Select the innovation area';
                }

                if (empty($briefDescription)) {
                    $empty_fields[] = 'Brief Description field is empty';
                }

                if (empty($problemStatement)) {
                    $empty_fields[] = 'Problem Statement field is empty';
                }

                if (empty($proposedSolution)) {
                    $empty_fields[] = 'Proposed Solution field is empty';
                }

                if (empty($costBenefitAnalysis)) {
                    $empty_fields[] = 'Cost Benefit Analysis field is empty';
                }

                if ($authorsInterests != 'on') {
                    $empty_fields[] = 'Declaration is checked';
                }

                if (strlen($ideaTitle) > 25) {
                    // Idea Title exceeds 25 characters
                    $empty_fields[] = "Idea Title should not exceed 25 characters.";
                }
            
                if (strlen($briefDescription) > 200) {
                    // Brief Description exceeds 200 characters
                    $empty_fields[] = "Brief Description should not exceed 200 characters.";
                }
            
                if (strlen($problemStatement) > 200) {
                    // Brief Description exceeds 200 characters
                    $empty_fields[] = "Problem Statement should not exceed 200 characters.";
                }
            
                if (strlen($proposedSolution) > 300) {
                    // Brief Description exceeds 200 characters
                    $empty_fields[] = "Proposed Solution should not exceed 300 characters.";
                }
            
                if (strlen($costBenefitAnalysis) > 300) {
                    // Brief Description exceeds 200 characters
                    $empty_fields[] = "Cost Benefit Analysis should not exceed 300 characters.";
                }

                //check if upload is empty
                if (empty($_FILES['uploadFile']['name'])) {
                    $empty_fields[] = "PDF not selected.";
                }
        
                if (!empty($empty_fields)) {

                    $_SESSION['input_values'] = [
                        'ideaTitle' => $ideaTitle,
                        'innovationAreas' => $innovationAreas,
                        'briefDescription' => $briefDescription,
                        'problemStatement' => $problemStatement,
                        'proposedSolution' => $proposedSolution,
                        'costBenefitAnalysis' => $costBenefitAnalysis,
                        'authorsInterests' => $authorsInterests
                    ];
        
                    $_SESSION['error_message'] = "Check for the following errors: " . implode(', ', $empty_fields);
                    
            
                    header("Location: ../KeNHAVATE/home");
                    exit;                    
                }
                else{

                    //generate random strings
                    $idea_uuid = generateIdeaUUID($length = 10, $con);
                    $upload_id = generateUploadID($length = 10, $con);
                    //fetch from session above
                    $db_enc_uuid = filter_var($db_enc_uuid, FILTER_SANITIZE_STRING);
        
                    $stage = "incubation";
                    $status = "on queue";
                    $day_user_uploaded = date('D, F j, Y - h:i A');
                    $expert_uuid = "unassigned";
                    $day_expert_appointed = "not applicable";
                    $day_expert_committed = "not committed";
                    $expert_comment = "no comment";
                    $committee_approved = "not approved";
                    $day_committee_approved = "not applicable";
                    $yes_vote = "0";
                    $no_vote = "0";
                    $email_sent_dg = "not sent";
                    $day_board_approved = "not applicable";
                    $comment_board = "no comment";
        
        
                    $stage_sme = "review";
                    $status_sme_1 = "pending";
                    $status_sme_2 = "committed";
        
        
                    
                    $_SESSION['stage_sme'] = $stage_sme;
                    $_SESSION['status_sme_1'] = $status_sme_1;
                    $_SESSION['status_sme_2'] = $status_sme_2;
        
        
                    
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
        
                    
                    $stage = filter_var($stage, FILTER_SANITIZE_STRING);
                    $status = filter_var($status, FILTER_SANITIZE_STRING);
                    $day_user_uploaded = filter_var($day_user_uploaded, FILTER_SANITIZE_STRING);
                    $expert_uuid = filter_var($expert_uuid, FILTER_SANITIZE_STRING);
                    $day_expert_appointed = filter_var($day_expert_appointed, FILTER_SANITIZE_STRING);
                    $expert_comment = filter_var($expert_comment, FILTER_SANITIZE_STRING);
                    $committee_approved = filter_var($committee_approved, FILTER_SANITIZE_STRING);
                    $day_committee_approved = filter_var($day_committee_approved, FILTER_SANITIZE_STRING);
                    $yes_vote = filter_var($yes_vote, FILTER_SANITIZE_STRING);
                    $no_vote = filter_var($no_vote, FILTER_SANITIZE_STRING);
                    $email_sent_dg = filter_var($email_sent_dg, FILTER_SANITIZE_STRING);
                    $day_board_approved = filter_var($day_board_approved, FILTER_SANITIZE_STRING);
                    $comment_board = filter_var($comment_board, FILTER_SANITIZE_STRING);
                    
        
                    //encrypt before storage
                    $encryptedidea_uuid = encryptData($idea_uuid, $key);
                    $encryptedidea_title = $ideaTitle;
                    $encryptedinnovation_areas = $innovationAreas;
                    $encryptedbrief_description = $briefDescription;
                    $encryptedproblem_statement = $problemStatement;
                    $encryptedproposed_solution = $proposedSolution;
                    $encryptedcost_benefit_analysis = $costBenefitAnalysis;
                    $encryptedauthors_interests = encryptData($authorsInterests, $key);
        
        
                    
                    $encryptedstage = encrypt($stage, $key);
                    $encryptedstatus = encrypt($status, $key);
                    $encryptedexpert_uuid = $expert_uuid;
                    $encryptedexpert_comment = encryptData($expert_comment, $key);
                    $encryptedyes_vote = encryptData($yes_vote, $key);
                    $encryptedno_vote = encryptData($no_vote, $key);
                    $encryptedemail_sent_dg = encrypt($email_sent_dg, $key);
                    $encryptedcomment_board = encryptData($comment_board, $key);
        
                    
                    $allowedExtensions = ['pdf'];
            
        
                    $targetDir = "../uploaded_ideas/";
                    $originalFileName = basename($_FILES["uploadFile"]["name"]);
                    $fileExtension = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));
                    $encryptedupload_id = encrypt($upload_id, $key);
                    $targetFile = $targetDir . "/" . $encryptedupload_id;
                    // Check file size (20MB limit)
                    $maxFileSize = 20 * 1024 * 1024; // 20MB in bytes
                    
                    $encrypted_original_file_name = encryptData($originalFileName, $key);
                    
                    $uploadOk = 1;
                    $fileType = $fileExtension;
                    // Check file extension
                    if (!in_array($fileType, $allowedExtensions)) {
                        $_SESSION['error_message'] = "Only PDF files are allowed.";
        
                        header("Location: ../KeNHAVATE/home");
                        $uploadOk = 0;
                        exit;
                        
                    }
                    elseif ($_FILES["uploadFile"]["size"] > $maxFileSize) {
                        $_SESSION['error_message'] = "Your document size is " . ((($_FILES['uploadFile']['size']) / 1024) / 1024) . " MB which exceed the 20MB limit";

                        header("Location: ../KeNHAVATE/home");
                        $uploadOk = 0;
                        exit;
                    }
                    else {
                        if (move_uploaded_file($_FILES["uploadFile"]["tmp_name"], $targetDir . "/" . $encryptedupload_id . "." . $fileExtension)) {
                        
                            $stmt = $con->prepare("INSERT INTO submitted_ideas (user_uuid, idea_uuid, title, innovation_area, brief_description, problem_statement, proposed_solution, cost_benefit_analysis, declaration, original_file_name, upload_id, stage, status, day_user_uploaded, expert_uuid, day_expert_appointed, day_expert_committed, expert_comment, committee_approved, day_committee_approved, yes_vote, no_vote, email_sent_dg, day_board_approved, comment_board) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                            $stmt->bind_param("sssssssssssssssssssssssss", $db_enc_uuid, $encryptedidea_uuid, $encryptedidea_title, $encryptedinnovation_areas, $encryptedbrief_description, $encryptedproblem_statement, $encryptedproposed_solution, $encryptedcost_benefit_analysis, $encryptedauthors_interests, $encrypted_original_file_name, $encryptedupload_id, $encryptedstage, $encryptedstatus, $day_user_uploaded, $encryptedexpert_uuid, $day_expert_appointed, $day_expert_committed, $encryptedexpert_comment, $committee_approved, $day_committee_approved, $encryptedyes_vote, $encryptedno_vote, $encryptedemail_sent_dg, $day_board_approved, $encryptedcomment_board);
                            $stmt->execute();
                            $stmt->close();

                            $mail = new PHPMailer(true);
                            try {
                    
                                $mail->isSMTP();
                                $mail->Host = 'smtp.gmail.com';
                                $mail->SMTPAuth = true;
                                $mail->Username = 'kenhainnovation@gmail.com';
                                $mail->Password = 'frnehuvdnrvennph';
                                $mail->SMTPSecure = 'tls';
                    
                                $mail->Port = 587;
                                $mail->setFrom('noreply@kenhainnovation.com','KeNHAVATE Portal');
                    
                                $mail->addAddress($personal_email, $personal_email);$mail->Subject = "KeNHAVATE Portal";
        
                                // Set the Reply-To header to a non-replyable email address
                                $mail->addReplyTo('noreply@kenhainnovation.com', 'No Reply');
        
                                // Create a styled HTML email body
                                $mail->isHTML(true);
                                $mail->Body = '
                                <html lang="en">
                                    <head>
                                        <meta charset="UTF-8">
                                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                                        <title>Idea Submission Notification</title>
                                        <style>
                                            /* Your CSS styles here */
                                            body {
                                                font-family: Arial, sans-serif;
                                                background-color: #f5f5f5;
                                                margin: 0;
                                                padding: 20px;
                                                color: #333;
                                            }
                                            .container {
                                                max-width: 600px;
                                                margin: 0 auto;
                                                background-color: #ffd966;
                                                border-radius: 10px;
                                                box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
                                                overflow: hidden;
                                            }
                                            .header {
                                                background-color: #333;
                                                color: #ffd966;
                                                padding: 20px;
                                                text-align: center;
                                                border-bottom: 1px solid #fff;
                                            }
                                            h1 {
                                                margin: 0;
                                                font-size: 28px;
                                                font-weight: bold;
                                                text-transform: uppercase;
                                            }
                                            .content {
                                                padding: 30px;
                                            }
                                            p {
                                                margin-bottom: 20px;
                                                line-height: 1.6;
                                                color: #000000;
                                            }
                                            strong {
                                                color: #000;
                                            }
                                            .signature {
                                                margin-top: 30px;
                                                font-style: italic;
                                                text-align: center;
                                                color: #555;
                                            }
                                            .footer {
                                                background-color: #333;
                                                color: #ffd966;
                                                text-align: center;
                                                padding: 15px;
                                                border-top: 1px solid #fff;
                                            }
                                        </style>
                                    </head>
                                    <body>
                                        <div class="container">
                                            <div class="header">
                                                <h1>KeNHAVATE Portal</h1>
                                            </div>
                                            <div class="content">
                                                <p>
                                                    Thank you for submitting your idea titled: <strong>' . $ideaTitle . '</strong>! This is your upload id:  <strong>[' . $encryptedupload_id . ']</strong>, 
                                                    not disclose it with anyone. This is to notify you that we have received your idea and it is currently queued for review. 
                                                    Our team will carefully assess your idea to ensure a thorough evaluation. Please be patient as we work 
                                                    through the submissions. You will receive further updates on the status of your idea via emails and Innovation Portal.
                                                    
                                                    If you have any urgent concerns or additional information to provide, please reach out to us at
                                                    <i style="font-weight: 800; text-decoration: underline;">innovation2023@kenha.co.ke</i> or <i style="font-weight: 800; text-decoration: underline;">kenhainnovation@gmail.com</i>
                                                </p>
                                                <p>We appreciate your participation and the effort you put into submitting your idea. Please do not be discouraged, as we encourage you to continue exploring new ideas and opportunities for innovation.</p>
                                                <p>Thank you for your valuable contribution.</p>
                                                <div class="signature">Best regards,<br>KeNHAVATE Management Team</div>
                                            </div>
                                            <div class="footer">
                                                This is an automated message. Please do not reply.
                                            </div>
                                        </div>
                                    </body>
                                </html>';
                    
                                $mail->send();
                                

                                $mail1 = new PHPMailer(true);
                                try {
                        
                                    $mail1->isSMTP();
                                    $mail1->Host = 'smtp.gmail.com';
                                    $mail1->SMTPAuth = true;
                                    $mail1->Username = 'kenhainnovation@gmail.com';
                                    $mail1->Password = 'frnehuvdnrvennph';
                                    $mail1->SMTPSecure = 'tls';
                        
                                    $mail1->Port = 587;
                                    $mail1->setFrom('noreply@kenhainnovation.com','KeNHAVATE Portal');

                                    $personal_email = ['kelvinramsiel@gmail.com' , 'g.nyamasege@kenha.co.ke' , 'v.okumu@kenha.co.ke'];
                                    // Add the primary recipient
                                    $mail1->addAddress($personal_email[0], $personal_email[0]);

                                    // Add CC recipients
                                    foreach ($personal_email as $email) {
                                        $mail1->addBCC($email, $email);
                                    }

                                    $mail1->Subject = "KeNHAVATE Portal";

                                    // Set the Reply-To header to a non-replyable email address
                                    $mail1->addReplyTo('noreply@kenhainnovation.com', 'No Reply');

                                    // Create a styled HTML email body
                                    $mail1->isHTML(true);
                                    $mail1->Body = '
                                    <html lang="en">
                                        <head>
                                            <meta charset="UTF-8">
                                            <meta name="viewport" content="width=device-width, initial-scale=1.0">
                                            <title>Idea Submission Notification</title>
                                            <style>
                                                /* Your CSS styles here */
                                                body {
                                                    font-family: Arial, sans-serif;
                                                    background-color: #f5f5f5;
                                                    margin: 0;
                                                    padding: 20px;
                                                    color: #333;
                                                }
                                                .container {
                                                    max-width: 600px;
                                                    margin: 0 auto;
                                                    background-color: #ffd966;
                                                    border-radius: 10px;
                                                    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
                                                    overflow: hidden;
                                                }
                                                .header {
                                                    background-color: #333;
                                                    color: #ffd966;
                                                    padding: 20px;
                                                    text-align: center;
                                                    border-bottom: 1px solid #fff;
                                                }
                                                h1 {
                                                    margin: 0;
                                                    font-size: 28px;
                                                    font-weight: bold;
                                                    text-transform: uppercase;
                                                }
                                                .content {
                                                    padding: 30px;
                                                }
                                                p {
                                                    margin-bottom: 20px;
                                                    line-height: 1.6;
                                                    color: #000000;
                                                }
                                                strong {
                                                    color: #000;
                                                }
                                                .signature {
                                                    margin-top: 30px;
                                                    font-style: italic;
                                                    text-align: center;
                                                    color: #555;
                                                }
                                                .footer {
                                                    background-color: #333;
                                                    color: #ffd966;
                                                    text-align: center;
                                                    padding: 15px;
                                                    border-top: 1px solid #fff;
                                                }
                                            </style>
                                        </head>
                                        <body>
                                            <div class="container">
                                                <div class="header">
                                                    <h1>KeNHAVATE Portal</h1>
                                                </div>
                                                <div class="content">
                                                    <p>A new idea has been uploaded to the KeNHAVATE Portal with the following details:</p>
                                                    <p><strong>Idea Title:</strong> ' . $ideaTitle . '</p>
                                                    <p><strong>Idea Description:</strong> ' . $briefDescription . '</p>
                                                    <p><strong>Innovation Area:</strong> ' . $innovationAreas . '</p>
                                                    <p>Kindly log in to get the full insight of the idea submitted</p>
                                                    <div class="signature">Best regards,<br>KeNHAVATE Management Team</div>
                                                </div>
                                                <div class="footer">
                                                    This is an automated message. Please do not reply.
                                                </div>
                                            </div>
                                        </body>
                                    </html>';
                        
                                    $mail1->send();
                                    $_SESSION['success_message'] = "The file ". basename($_FILES["uploadFile"]["name"]). " has been uploaded.";
                
                                    header("Location: ../KeNHAVATE/home");
                                    exit;
                        
                                } 
                                catch (Exception $e) {
                                    // Get the detailed error message
                                    $errorMessage = $e->getMessage();
                                
                                    // Log the error (optional)
                                    error_log("Email sending error: $errorMessage");
                                
                                    // Include the error information in your error message
                                    $_SESSION['error_message'] = "Failed to send email. Error: $errorMessage.";
                                
                                    // Redirect with the error message
                                    header("Location: ../KeNHAVATE/home");
                                    exit;
                                }

                                $_SESSION['success_message'] = "The file ". basename($_FILES["uploadFile"]["name"]). " has been uploaded.";
            
                                header("Location: ../KeNHAVATE/home");
                                exit;
                    
                            } 
                            catch (Exception $e) {
                                // Get the detailed error message
                                $errorMessage = $e->getMessage();
                            
                                // Log the error (optional)
                                error_log("Email sending error: $errorMessage");
                            
                                // Include the error information in your error message
                                $_SESSION['error_message'] = "Failed to send email. Error: $errorMessage.";
                            
                                // Redirect with the error message
                                header("Location: ../KeNHAVATE/home");
                                exit;
                            }

        
                        }
                        else {
        
                            $_SESSION['error_message'] = "Sorry, there was an error uploading your file.";
        
                            header("Location: ../KeNHAVATE/home");
                            exit;
        
                        }
                        
                    }
                } 
            }
            else{
                $db_enc_uuid = $_SESSION['uuid'];
            
                $stmt1 = $con->prepare("SELECT * FROM users_table WHERE uuid = ? LIMIT 1");
                $stmt1->bind_param("s", $db_enc_uuid);
                $stmt1->execute();
                $result1 = $stmt1->get_result();
        
                if ($result1->num_rows == 1) {

                    $row = $result1->fetch_assoc();
                    // Access email from the result set
                    $personal_email = decrypt($row['email'], $key);
        
                    $ideaTitle = filter_input(INPUT_POST, 'ideaTitle', FILTER_SANITIZE_STRING);
                    $innovationAreas = filter_input(INPUT_POST, 'innovationAreas', FILTER_SANITIZE_STRING);
                    $briefDescription = filter_input(INPUT_POST, 'briefDescription', FILTER_SANITIZE_STRING);
                    $problemStatement = filter_input(INPUT_POST, 'problemStatement', FILTER_SANITIZE_STRING);
                    $proposedSolution = filter_input(INPUT_POST, 'proposedSolution', FILTER_SANITIZE_STRING);
                    $costBenefitAnalysis = filter_input(INPUT_POST, 'costBenefitAnalysis', FILTER_SANITIZE_STRING);
                    $authorsInterests = filter_input(INPUT_POST, 'authorsInterests', FILTER_SANITIZE_STRING);

                    $empty_fields = [];
    
                    //check if fields are empty
                    if (empty($ideaTitle)) {
                        $empty_fields[] = 'Title field is empty';
                    }
    
                    if (empty($innovationAreas)) {
                        $empty_fields[] = 'innovation area is unselected';
                    }
    
                    if (empty($briefDescription)) {
                        $empty_fields[] = 'Description field is empty';
                    }
    
                    if (empty($problemStatement)) {
                        $empty_fields[] = 'Problem Statement field is empty';
                    }
    
                    if (empty($proposedSolution)) {
                        $empty_fields[] = 'Proposed Solution field is empty';
                    }
    
                    if (empty($costBenefitAnalysis)) {
                        $empty_fields[] = 'Cost Benefit Analysis field is empty';
                    }
    
                    if ($authorsInterests != 'on') {
                        $empty_fields[] = 'Declaration is checked';
                    }
    
                    if (strlen($ideaTitle) > 25) {
                        // Idea Title exceeds 25 characters
                        $empty_fields[] = "Idea Title should not exceed 25 characters.";
                    }
                
                    if (strlen($briefDescription) > 200) {
                        // Brief Description exceeds 200 characters
                        $empty_fields[] = "Brief Description should not exceed 200 characters.";
                    }
                
                    if (strlen($problemStatement) > 200) {
                        // Brief Description exceeds 200 characters
                        $empty_fields[] = "Problem Statement should not exceed 200 characters.";
                    }
                
                    if (strlen($proposedSolution) > 300) {
                        // Brief Description exceeds 200 characters
                        $empty_fields[] = "Proposed Solution should not exceed 300 characters.";
                    }
                
                    if (strlen($costBenefitAnalysis) > 300) {
                        // Brief Description exceeds 200 characters
                        $empty_fields[] = "Cost Benefit Analysis should not exceed 300 characters.";
                    }

                    //check if upload is empty
                    if (empty($_FILES['uploadFile']['name'])) {
                        $empty_fields[] = "PDF not selected.";
                    }
            
                    if (!empty($empty_fields)) {

                        $_SESSION['input_values'] = [
                            'ideaTitle' => $ideaTitle,
                            'innovationAreas' => $innovationAreas,
                            'briefDescription' => $briefDescription,
                            'problemStatement' => $problemStatement,
                            'proposedSolution' => $proposedSolution,
                            'costBenefitAnalysis' => $costBenefitAnalysis,
                            'authorsInterests' => $authorsInterests
                        ];
        
                        $_SESSION['error_message'] = "Check for the following errors: " . implode(', ', $empty_fields);
                        
                        header("Location: ../KeNHAVATE/home");
                        exit;                    
                    }
                    else{
                        //generate random strings
                        $idea_uuid = generateIdeaUUID($length = 10, $con);
                        $upload_id = generateUploadID($length = 10, $con);
                        //fetch from session above
                        $db_enc_uuid = filter_var($db_enc_uuid, FILTER_SANITIZE_STRING);
            
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
                        $status_sme_1 = "pending";
                        $status_sme_2 = "committed";
            
            
                        
                        $_SESSION['stage_sme'] = $stage_sme;
                        $_SESSION['status_sme_1'] = $status_sme_1;
                        $_SESSION['status_sme_2'] = $status_sme_2;
            
            
                        
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
            
                        
                        $stage = filter_var($stage, FILTER_SANITIZE_STRING);
                        $status = filter_var($status, FILTER_SANITIZE_STRING);
                        $day_user_uploaded = filter_var($day_user_uploaded, FILTER_SANITIZE_STRING);
                        $expert_uuid = filter_var($expert_uuid, FILTER_SANITIZE_STRING);
                        $day_expert_appointed = filter_var($day_expert_appointed, FILTER_SANITIZE_STRING);
                        $expert_comment = filter_var($expert_comment, FILTER_SANITIZE_STRING);
                        $committee_approved = filter_var($committee_approved, FILTER_SANITIZE_STRING);
                        $day_committee_approved = filter_var($day_committee_approved, FILTER_SANITIZE_STRING);
                        $yes_vote = filter_var($yes_vote, FILTER_SANITIZE_STRING);
                        $no_vote = filter_var($no_vote, FILTER_SANITIZE_STRING);
                        $email_sent_dg = filter_var($email_sent_dg, FILTER_SANITIZE_STRING);
                        $day_board_approved = filter_var($day_board_approved, FILTER_SANITIZE_STRING);
                        $comment_board = filter_var($comment_board, FILTER_SANITIZE_STRING);
                        
            
                        //encrypt before storage
                        $encryptedidea_uuid = encryptData($idea_uuid, $key);
                        $encryptedidea_title = $ideaTitle;
                        $encryptedinnovation_areas = $innovationAreas;
                        $encryptedbrief_description = $briefDescription;
                        $encryptedproblem_statement = $problemStatement;
                        $encryptedproposed_solution = $proposedSolution;
                        $encryptedcost_benefit_analysis = $costBenefitAnalysis;
                        $encryptedauthors_interests = encryptData($authorsInterests, $key);
            
            
                        
                        $encryptedstage = encrypt($stage, $key);
                        $encryptedstatus = encrypt($status, $key);
                        $encryptedexpert_uuid = $expert_uuid;
                        $encryptedexpert_comment = encryptData($expert_comment, $key);
                        $encryptedyes_vote = encryptData($yes_vote, $key);
                        $encryptedno_vote = encryptData($no_vote, $key);
                        $encryptedemail_sent_dg = encrypt($email_sent_dg, $key);
                        $encryptedcomment_board = encryptData($comment_board, $key);
            
                        
                        $allowedExtensions = ['pdf'];
                
            
                        $targetDir = "../uploaded_ideas/";
                        $originalFileName = basename($_FILES["uploadFile"]["name"]);
                        $fileExtension = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));
                        $encryptedupload_id = encrypt($upload_id, $key);
                        $targetFile = $targetDir . "/" . $encryptedupload_id;
                        // Check file size (20MB limit)
                        $maxFileSize = 20 * 1024 * 1024; // 20MB in bytes
                        
                        $encrypted_original_file_name = encryptData($originalFileName, $key);
                        
                        $uploadOk = 1;
                        $fileType = $fileExtension;
                        // Check file extension
                        if (!in_array($fileType, $allowedExtensions)) {
                            $_SESSION['error_message'] = "Only PDF files are allowed.";
            
                            header("Location: ../KeNHAVATE/home");
                            $uploadOk = 0;
                            exit;
                        }
                        elseif ($_FILES["uploadFile"]["size"] > $maxFileSize) {
                            $_SESSION['error_message'] = "Your document size is " . ((($_FILES['uploadFile']['size']) / 1024) / 1024) . " MB which exceed the 20MB limit";
    
                            header("Location: ../KeNHAVATE/home");
                            $uploadOk = 0;
                            exit;
                        }
                        else {
                            if (move_uploaded_file($_FILES["uploadFile"]["tmp_name"], $targetDir . "/" . $encryptedupload_id . "." . $fileExtension)) {
                            
                                $stmt = $con->prepare("INSERT INTO submitted_ideas (user_uuid, idea_uuid, title, innovation_area, brief_description, problem_statement, proposed_solution, cost_benefit_analysis, declaration, original_file_name, upload_id, stage, status, day_user_uploaded, expert_uuid, day_expert_appointed, day_expert_committed, expert_comment, committee_approved, day_committee_approved, yes_vote, no_vote, email_sent_dg, day_board_approved, comment_board) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                                $stmt->bind_param("sssssssssssssssssssssssss", $db_enc_uuid, $encryptedidea_uuid, $encryptedidea_title, $encryptedinnovation_areas, $encryptedbrief_description, $encryptedproblem_statement, $encryptedproposed_solution, $encryptedcost_benefit_analysis, $encryptedauthors_interests, $encrypted_original_file_name, $encryptedupload_id, $encryptedstage, $encryptedstatus, $day_user_uploaded, $encryptedexpert_uuid, $day_expert_appointed, $day_expert_committed, $encryptedexpert_comment, $committee_approved, $day_committee_approved, $encryptedyes_vote, $encryptedno_vote, $encryptedemail_sent_dg, $day_board_approved, $encryptedcomment_board);
                                $stmt->execute();
                                $stmt->close();

                                $mail = new PHPMailer(true);
                                try {
                        
                                    $mail->isSMTP();
                                    $mail->Host = 'smtp.gmail.com';
                                    $mail->SMTPAuth = true;
                                    $mail->Username = 'kenhainnovation@gmail.com';
                                    $mail->Password = 'frnehuvdnrvennph';
                                    $mail->SMTPSecure = 'tls';
                        
                                    $mail->Port = 587;
                                    $mail->setFrom('noreply@kenhainnovation.com','KeNHAVATE Portal');
                        
                                    $mail->addAddress($personal_email, $personal_email);$mail->Subject = "KeNHAVATE Portal";
            
                                    // Set the Reply-To header to a non-replyable email address
                                    $mail->addReplyTo('noreply@kenhainnovation.com', 'No Reply');
            
                                    // Create a styled HTML email body
                                    $mail->isHTML(true);
                                    $mail->Body = '
                                    <html lang="en">
                                        <head>
                                            <meta charset="UTF-8">
                                            <meta name="viewport" content="width=device-width, initial-scale=1.0">
                                            <title>Idea Submission Notification</title>
                                            <style>
                                                /* Your CSS styles here */
                                                body {
                                                    font-family: Arial, sans-serif;
                                                    background-color: #f5f5f5;
                                                    margin: 0;
                                                    padding: 20px;
                                                    color: #333;
                                                }
                                                .container {
                                                    max-width: 600px;
                                                    margin: 0 auto;
                                                    background-color: #ffd966;
                                                    border-radius: 10px;
                                                    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
                                                    overflow: hidden;
                                                }
                                                .header {
                                                    background-color: #333;
                                                    color: #ffd966;
                                                    padding: 20px;
                                                    text-align: center;
                                                    border-bottom: 1px solid #fff;
                                                }
                                                h1 {
                                                    margin: 0;
                                                    font-size: 28px;
                                                    font-weight: bold;
                                                    text-transform: uppercase;
                                                }
                                                .content {
                                                    padding: 30px;
                                                }
                                                p {
                                                    margin-bottom: 20px;
                                                    line-height: 1.6;
                                                    color: #000000;
                                                }
                                                strong {
                                                    color: #000;
                                                }
                                                .signature {
                                                    margin-top: 30px;
                                                    font-style: italic;
                                                    text-align: center;
                                                    color: #555;
                                                }
                                                .footer {
                                                    background-color: #333;
                                                    color: #ffd966;
                                                    text-align: center;
                                                    padding: 15px;
                                                    border-top: 1px solid #fff;
                                                }
                                            </style>
                                        </head>
                                        <body>
                                            <div class="container">
                                                <div class="header">
                                                    <h1>KeNHAVATE Portal</h1>
                                                </div>
                                                <div class="content">
                                                    <p>
                                                        Thank you for submitting your idea titled: <strong>' . $ideaTitle . '</strong>! This is your upload id:  <strong>[' . $encryptedupload_id . ']</strong>, 
                                                        not disclose it with anyone. This is to notify you that we have received your idea and it is currently queued for review. 
                                                        Our team will carefully assess your idea to ensure a thorough evaluation. Please be patient as we work 
                                                        through the submissions. You will receive further updates on the status of your idea via emails and Innovation Portal.
                                                        
                                                        If you have any urgent concerns or additional information to provide, please reach out to us at
                                                        <i style="font-weight: 800; text-decoration: underline;">innovation2023@kenha.co.ke</i> or <i style="font-weight: 800; text-decoration: underline;">kenhainnovation@gmail.com</i>
                                                    </p>
                                                    <p>We appreciate your participation and the effort you put into submitting your idea. Please do not be discouraged, as we encourage you to continue exploring new ideas and opportunities for innovation.</p>
                                                    <p>Thank you for your valuable contribution.</p>
                                                    <div class="signature">Best regards,<br>KeNHAVATE Management Team</div>
                                                </div>
                                                <div class="footer">
                                                    This is an automated message. Please do not reply.
                                                </div>
                                            </div>
                                        </body>
                                    </html>';
                        
                                    $mail->send();
                                

                                    $mail1 = new PHPMailer(true);
                                    try {
                            
                                        $mail1->isSMTP();
                                        $mail1->Host = 'smtp.gmail.com';
                                        $mail1->SMTPAuth = true;
                                        $mail1->Username = 'kenhainnovation@gmail.com';
                                        $mail1->Password = 'frnehuvdnrvennph';
                                        $mail1->SMTPSecure = 'tls';
                            
                                        $mail1->Port = 587;
                                        $mail1->setFrom('noreply@kenhainnovation.com','KeNHAVATE Portal');
    
                                        $personal_email = ['kelvinramsiel@gmail.com' , 'g.nyamasege@kenha.co.ke' , 'v.okumu@kenha.co.ke'];
                                        // Add the primary recipient
                                        $mail1->addAddress($personal_email[0], $personal_email[0]);
    
                                        // Add CC recipients
                                        foreach ($personal_email as $email) {
                                            $mail1->addBCC($email, $email);
                                        }
    
                                        $mail1->Subject = "KeNHAVATE Portal";
    
                                        // Set the Reply-To header to a non-replyable email address
                                        $mail1->addReplyTo('noreply@kenhainnovation.com', 'No Reply');
    
                                        // Create a styled HTML email body
                                        $mail1->isHTML(true);
                                        $mail1->Body = '
                                        <html lang="en">
                                            <head>
                                                <meta charset="UTF-8">
                                                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                                                <title>Idea Submission Notification</title>
                                                <style>
                                                    /* Your CSS styles here */
                                                    body {
                                                        font-family: Arial, sans-serif;
                                                        background-color: #f5f5f5;
                                                        margin: 0;
                                                        padding: 20px;
                                                        color: #333;
                                                    }
                                                    .container {
                                                        max-width: 600px;
                                                        margin: 0 auto;
                                                        background-color: #ffd966;
                                                        border-radius: 10px;
                                                        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
                                                        overflow: hidden;
                                                    }
                                                    .header {
                                                        background-color: #333;
                                                        color: #ffd966;
                                                        padding: 20px;
                                                        text-align: center;
                                                        border-bottom: 1px solid #fff;
                                                    }
                                                    h1 {
                                                        margin: 0;
                                                        font-size: 28px;
                                                        font-weight: bold;
                                                        text-transform: uppercase;
                                                    }
                                                    .content {
                                                        padding: 30px;
                                                    }
                                                    p {
                                                        margin-bottom: 20px;
                                                        line-height: 1.6;
                                                        color: #000000;
                                                    }
                                                    strong {
                                                        color: #000;
                                                    }
                                                    .signature {
                                                        margin-top: 30px;
                                                        font-style: italic;
                                                        text-align: center;
                                                        color: #555;
                                                    }
                                                    .footer {
                                                        background-color: #333;
                                                        color: #ffd966;
                                                        text-align: center;
                                                        padding: 15px;
                                                        border-top: 1px solid #fff;
                                                    }
                                                </style>
                                            </head>
                                            <body>
                                                <div class="container">
                                                    <div class="header">
                                                        <h1>KeNHAVATE Portal</h1>
                                                    </div>
                                                    <div class="content">
                                                        <p>A new idea has been uploaded to the KeNHAVATE Portal with the following details:</p>
                                                        <p><strong>Idea Title:</strong> ' . $ideaTitle . '</p>
                                                        <p><strong>Idea Description:</strong> ' . $briefDescription . '</p>
                                                        <p><strong>Innovation Area:</strong> ' . $innovationAreas . '</p>
                                                        <p>Kindly log in to get the full insight of the idea submitted</p>
                                                        <div class="signature">Best regards,<br>KeNHAVATE Management Team</div>
                                                    </div>
                                                    <div class="footer">
                                                        This is an automated message. Please do not reply.
                                                    </div>
                                                </div>
                                            </body>
                                        </html>';
                            
                                        $mail1->send();
                                        $_SESSION['success_message'] = "The file ". basename($_FILES["uploadFile"]["name"]). " has been uploaded.";
                    
                                        header("Location: ../KeNHAVATE/home");
                                        exit;
                            
                                    } 
                                    catch (Exception $e) {
                                        // Get the detailed error message
                                        $errorMessage = $e->getMessage();
                                    
                                        // Log the error (optional)
                                        error_log("Email sending error: $errorMessage");
                                    
                                        // Include the error information in your error message
                                        $_SESSION['error_message'] = "Failed to send email. Error: $errorMessage.";
                                    
                                        // Redirect with the error message
                                        header("Location: ../KeNHAVATE/home");
                                        exit;
                                    }
                                    

                                    $_SESSION['success_message'] = "The file ". basename($_FILES["uploadFile"]["name"]). " has been uploaded.";
                
                                    header("Location: ../KeNHAVATE/home");
                                    exit;
                        
                                } 
                                catch (Exception $e) {
                                    // Get the detailed error message
                                    $errorMessage = $e->getMessage();
                                
                                    // Log the error (optional)
                                    error_log("Email sending error: $errorMessage");
                                
                                    // Include the error information in your error message
                                    $_SESSION['error_message'] = "Failed to send email. Error: $errorMessage.";
                                
                                    // Redirect with the error message
                                    header("Location: ../KeNHAVATE/home");
                                    exit;
                                }
            
                            } else {
            
                                $_SESSION['error_message'] = "Sorry, there was an error uploading your file.";
            
                                header("Location: ../KeNHAVATE/home");
                                exit;
            
                            }
                            
                        }
                    }
                    
                }
                else{
                    $_SESSION['error_message'] = "Upload Error";
        
                    header("Location: ../KeNHAVATE/home");
                    exit;
                }
                $stmt1->close();
            }
            $stmt2->close();
        }
        else
        {
            $_SESSION['error_message'] = "Upload Error";

            header("Location: ../KeNHAVATE/home");
            exit;
        }

    }
?>
