<?php
    session_start();

    include("../../auth_controller/requirement.php");
    include("./requirement.php");

    require './mailer/PHPMailer.php';
    require './mailer/SMTP.php';
    require './mailer/Exception.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    $key = 'my-KeNHAsecret-passkey';
    $expert_uuid = 'unassigned';
    $day_expert_appointed = 'not applicable';
    $day_expert_committed = 'not committed';
    $committee_stage = "committee";
    $committee_approved ='not approved';
    $day_committee_approved = 'not applicable';
    $board_stage = "board";
    $day_board_approved = 'not applicable';
    $board_status = "rejected";
    
    if ($_GET['action'] === 'UpdateTableChallenges') {

        $stmt3 = $con->prepare("SELECT * FROM posted_challenges ORDER BY STR_TO_DATE(day_uploaded, '%a, %M %d, %Y - %h:%i %p') DESC");
        $stmt3->execute();
        $result3 = $stmt3->get_result();
    
        $posted_challenges_array = array();
    
        if ($result3->num_rows > 0) {
            while ($row3 = $result3->fetch_assoc()) {
                $challenge_id = $row3['challenge_id'];
                $author_uuid = $row3['author_uuid'];
                $challenge_uuid = decrypt($row3['challenge_uuid'], $key);
                $title = $row3['title'];
                $description = $row3['description'];
                $deadline = $row3['deadline'];
                $day_uploaded = $row3['day_uploaded'];
                
                $stmt = $con->prepare("SELECT * FROM staff_table WHERE staff_uuid = ?");
                $stmt->bind_param("s", $author_uuid);
                $stmt->execute();
                $result = $stmt->get_result();

                while ($row = $result->fetch_assoc()) {
                    $first_name =  decrypt($row['first_name'], $key);
                    $other_names =  decrypt($row['other_names'], $key);
                

                    $deadlineDateTime = DateTime::createFromFormat('D, F j, Y - h:i A', $deadline);
                    $currentDateTime = new DateTime();

                    if ($deadlineDateTime < $currentDateTime) {
                        $status = 'expired';
                    } else {
                        $status = 'in&nbsp;progress';
                    }
                    
        
                    $posted_challenges_array[] = array(
                        'challenge_id' => $challenge_id,
                        'first_name' => $first_name,
                        'other_names' => $other_names,
                        'challenge_uuid' => $challenge_uuid,
                        'title' => $title,
                        'description' => $description,
                        'status' => $status,
                        'deadline' => $deadline,
                        'day_uploaded' => $day_uploaded
                    );
                }
            }
    
            header('Content-Type: application/json');
            echo json_encode($posted_challenges_array);
        }
        else {
            echo json_encode(['message' => 'No table returned', 'data' => []]);
        }
    }
    else if ($_GET['action'] === 'UpdateTableChallengesViewMore') {
        // Retrieve the challengeId from the URL
        $challengeId = $_GET['challengeId'];
        $enc_challenge_uuid = encrypt($challengeId, $key);

        $stmt = $con->prepare("SELECT * FROM replied_challenges WHERE challenge_uuid = ? ORDER BY STR_TO_DATE(time_uploaded, '%a, %M %d, %Y - %h:%i %p') DESC");
        $stmt->bind_param("s", $enc_challenge_uuid);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $replied_challenges_array = array();
        $numRows = $result->num_rows;
    
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $challenge_uuid = decrypt($row['challenge_uuid'], $key);
                $desc_solution = $row['desc_solution'];
                $desc_motivation = $row['desc_motivation'];
                $desc_cost_estimate = $row['desc_cost_estimate'];
                $time_uploaded = $row['time_uploaded'];
                $upload_id = $row['upload_id'];
                $user_uuid = $row['user_uuid'];

                $stmt_1 = $con->prepare("SELECT * FROM users_table WHERE uuid = ? LIMIT 1");
                $stmt_1->bind_param("s", $user_uuid);
                $stmt_1->execute();
                $result_1 = $stmt_1->get_result();

                while ($row_1 = $result_1->fetch_assoc()) {
                    $email = decrypt($row_1['email'], $key);
                }
                
    
                $replied_challenges_array[] = array(
                    'challenge_uuid' => $challenge_uuid,
                    'desc_solution' => $desc_solution,
                    'desc_motivation' => $desc_motivation,
                    'desc_cost_estimate' => $desc_cost_estimate,
                    'time_uploaded' => $time_uploaded,
                    'upload_id' => $upload_id,
                    'email' => $email,
                    'numRows' => $numRows
                );
            }
    
            header('Content-Type: application/json');
            echo json_encode($replied_challenges_array);
        }
        else {
            echo json_encode(['message' => 'No table returned', 'data' => []]);
        }
    }
    else if ($_GET['action'] === 'GetRows') {

        $stmt = $con->prepare("SELECT challenge_uuid, COUNT(*) as count FROM replied_challenges GROUP BY challenge_uuid");
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $data = array();
            // Fetch and store the results in an array
            while ($row = $result->fetch_assoc()) {
                $data[] = array(
                    'challenge_uuid' => decrypt($row["challenge_uuid"], $key),
                    'count' => $row["count"]
                );
            }
    
            header('Content-Type: application/json');
            echo json_encode(['message' => 'Success', 'data' => $data]);
        }
        else {
            echo json_encode(['message' => 'No table returned', 'data' => []]);
        }

    }
    else if ($_GET['action'] === 'UpdateFormChallengesViewMore') {
        // Retrieve the upload_id from the URL
        $upload_id = $_GET['upload_id'];

        $stmt = $con->prepare("SELECT * FROM replied_challenges WHERE upload_id = ? LIMIT 1");
        $stmt->bind_param("s", $upload_id);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $found_response_array = array();
    
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $enc_challenge_uuid = $row['challenge_uuid'];
                $challenge_uuid = decrypt($row['challenge_uuid'], $key);
                $desc_solution = $row['desc_solution'];
                $desc_motivation = $row['desc_motivation'];
                $desc_cost_estimate = $row['desc_cost_estimate'];
                $time_uploaded = $row['time_uploaded'];
                $upload_id = $row['upload_id'];

                

                $stmt1 = $con->prepare("SELECT * FROM posted_challenges WHERE challenge_uuid = ? LIMIT 1");
                $stmt1->bind_param("s", $enc_challenge_uuid);
                $stmt1->execute();
                $result1 = $stmt1->get_result();
    
                if ($result1->num_rows > 0) {
                    while ($row1 = $result1->fetch_assoc()) {
                        $title = $row1['title'];
                        $description = $row1['description'];
                    
        
                        $found_response_array[] = array(
                            'challenge_uuid' => $challenge_uuid,
                            'desc_solution' => $desc_solution,
                            'desc_motivation' => $desc_motivation,
                            'desc_cost_estimate' => $desc_cost_estimate,
                            'time_uploaded' => $time_uploaded,
                            'upload_id' => $upload_id,
                            'challenge_title' => $title,
                            'challenge_desc' => $description
                        );
                    }
                }
            }
    
            header('Content-Type: application/json');
            echo json_encode($found_response_array);
        }
        else {
            echo json_encode(['message' => 'No table returned', 'data' => []]);
        }
    }
    else if ($_GET['action'] === 'UpdateFirstIdeaTable') {
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $itemsPerPage = isset($_GET['itemsPerPage']) ? $_GET['itemsPerPage'] : 10;
    
        // Calculate the offset based on the page and items per page
        $offset = ($page - 1) * $itemsPerPage;
    
        $stmt = $con->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM submitted_ideas ORDER BY STR_TO_DATE(day_user_uploaded, '%a, %M %d, %Y - %h:%i %p') DESC LIMIT ?, ?");
        $stmt->bind_param("ii", $offset, $itemsPerPage);
        $stmt->execute();
    
        $result = $stmt->get_result();
    
        // Fetch and store the results in an array
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data['items'][] = array(
                'idea_uuid' => $row['idea_uuid'],
                'title' => $row['title'],
                'innovation_area' => $row['innovation_area'],
                'problem_statement' => $row['problem_statement'],
                'day_user_uploaded' => $row['day_user_uploaded'],
                'stage' => decrypt($row['stage'], $key),
                'status' => decrypt($row['status'], $key)
            );
        }
    
        // Get total number of rows without LIMIT
        $totalRows = $con->query("SELECT FOUND_ROWS()")->fetch_row()[0];
    
        // Calculate total pages
        $totalPages = ceil($totalRows / $itemsPerPage);
    
        // Add total pages to the data
        $data['totalPages'] = $totalPages;
        $data['totalRows'] = $totalRows;
    
        // Return the data as JSON
        header('Content-Type: application/json');
        echo json_encode($data);
    }
    else if ($_GET['action'] === 'UpdateFormIdeasViewMore') {
        // Retrieve the upload_id from the URL
        $idea_uuid = $_GET['idea_uuid'];

        $stmt = $con->prepare("SELECT * FROM submitted_ideas WHERE idea_uuid = ? LIMIT 1");
        $stmt->bind_param("s", $idea_uuid);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $found_response_array = array();
    
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                
                $found_response_array[] = array(
                    'title' => $row['title'],
                    'innovation_area' => $row['innovation_area'],
                    'brief_description' => $row['brief_description'],
                    'problem_statement' => $row['problem_statement'],
                    'proposed_solution' => $row['proposed_solution'],
                    'cost_benefit_analysis' => $row['cost_benefit_analysis'],
                    'upload_id' => $row['upload_id'],
                    'stage' => decrypt($row['stage'], $key),
                    'status' => decrypt($row['status'], $key),
                    'day_user_uploaded' => $row['day_user_uploaded'],
                    'expert_uuid' => $row['expert_uuid'],
                    'day_expert_appointed' => $row['day_expert_appointed'],
                    'day_expert_committed' => $row['day_expert_committed'],
                    'committee_approved' => $row['committee_approved'],
                    'day_committee_approved' => $row['day_committee_approved']
                );
            }
    
            header('Content-Type: application/json');
            echo json_encode($found_response_array);
        }
        else {
            echo json_encode(['message' => 'No table returned', 'data' => []]);
        }
    } 
    else if ($_GET['action'] === 'UpdateSecondIdeaTable') {
        $page2 = isset($_GET['page2']) ? $_GET['page2'] : 1;
        $itemsPerPage2 = isset($_GET['itemsPerPageTable2']) ? $_GET['itemsPerPageTable2'] : 10;

        $expert_uuid = 'unassigned';
    
        $offset = ($page2 - 1) * $itemsPerPage2;
    
        $stmt = $con->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM submitted_ideas WHERE expert_uuid = ? ORDER BY STR_TO_DATE(day_user_uploaded, '%a, %M %d, %Y - %h:%i %p') DESC LIMIT ?, ?");
        $stmt->bind_param("sii", $expert_uuid, $offset, $itemsPerPage2);
        $stmt->execute();
    
        $result = $stmt->get_result();
    
        // Fetch and store the results in an array
        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data['items'][] = array(
                'idea_uuid' => $row['idea_uuid'],
                'title' => $row['title'],
                'innovation_area' => $row['innovation_area'],
                'problem_statement' => $row['problem_statement'],
                'day_user_uploaded' => $row['day_user_uploaded'],
                'stage' => decrypt($row['stage'], $key),
                'status' => decrypt($row['status'], $key)
            );
        }
    
        // Get total number of rows without LIMIT
        $totalRows = $con->query("SELECT FOUND_ROWS()")->fetch_row()[0];
    
        // Calculate total pages
        $totalPages = ceil($totalRows / $itemsPerPage2);
    
        // Add total pages to the data
        $data['totalPages'] = $totalPages;
        $data['totalRows'] = $totalRows;
    
        // Return the data as JSON
        header('Content-Type: application/json');
        echo json_encode($data);
    }
    else if ($_GET['action'] === 'GetAllAdminDetails') {

        $stmt = $con->prepare("SELECT s.*, ss.* FROM staff_table s INNER JOIN staff_sub_table ss ON s.staff_uuid = ss.staff_uuid");
        $stmt->execute();
        $result = $stmt->get_result();
    
        $found_response_array = array();
    
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                
                $found_response_array[] = array(
                    'personal_email' => $row['personal_email'],
                    'KeNHA_email' => decrypt($row['KeNHA_email'], $key),
                    'directorate' => decrypt($row['directorate'], $key),
                    'staff_uuid' => $row['staff_uuid'],
                    'first_name' => decrypt($row['first_name'], $key),
                    'other_names' => decrypt($row['other_names'], $key)
                );
            }
    
            header('Content-Type: application/json');
            echo json_encode($found_response_array);
        }
        else {
            echo json_encode(['message' => 'No table returned', 'data' => []]);
        }
    }
    else if ($_GET['action'] === 'AppointExpert') {
        // Retrieve the upload_id from the URL
        $staff_uuid = $_GET['staff_uuid'];
        $idea_uuid = $_GET['idea_uuid'];

        $stmt = $con->prepare("SELECT s.*, ss.* FROM staff_table s INNER JOIN staff_sub_table ss ON s.staff_uuid = ss.staff_uuid WHERE s.staff_uuid = ? LIMIT 1");
        $stmt->bind_param("s", $staff_uuid);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $response = array();
        
        if ($result->num_rows > 0) {
            // Fetch the data from the result set
            $row = $result->fetch_assoc();

            $expert_email = decrypt($row['KeNHA_email'], $key);
            $expert_uuid = $row['staff_uuid'];

            $stmt_1 = $con->prepare("SELECT * FROM submitted_ideas WHERE idea_uuid = ? LIMIT 1");
            $stmt_1->bind_param("s", $idea_uuid);
            $stmt_1->execute();
            $result_1 = $stmt_1->get_result();
        
            if ($result_1->num_rows > 0) {
                // Fetch the data from the result set
                $row_1 = $result_1->fetch_assoc();

                $authors_uuid = $row_1['user_uuid'];
                $title = $row_1['title'];
                $innovation_area = $row_1['innovation_area'];
                $brief_description = $row_1['brief_description'];
                $day_user_uploaded = $row_1['day_user_uploaded'];

                $stage = encrypt('review', $key);
                $status = encrypt('pending', $key);
                $day_expert_appointed = date('D, F j, Y - h:i A');

                $mail = new PHPMailer(true);
                try {
        
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'kenhainnovation@gmail.com';
                    $mail->Password = 'frnehuvdnrvennph';
                    $mail->SMTPSecure = 'tls';
        
                    $mail->Port = 587;
                    $mail->setFrom('noreply@kenhainnovation.com','KeNHAVate Portal');
        
                    $mail->addAddress($expert_email);$mail->Subject = "Appointment Alert";

                    // Set the Reply-To header to a non-replyable email address
                    $mail->addReplyTo('noreply@kenhainnovation.com', 'No Reply');

                    // Create a styled HTML email body
                    $mail->isHTML(true);
                    $mail->Body = '<html>
                    <head>
                        <style>
                            /* Your CSS styles here */
                            body {
                                font-family: Arial, sans-serif;
                                background-color: #f5f5f5;
                                margin: 0;
                                padding: 20px;
                            }
                            .im {
                                color: black !important;
                            }
                            .container {
                                max-width: 600px;
                                margin: 0 auto;
                                padding: 20px;
                                background: linear-gradient(to right, yellow, white 20%, white 80%, yellow);
                                border-radius: 10px;
                                box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
                            }
                            .header {
                                background-color: #d8d801ed;
                                color: #3b3b3bs;
                                padding: 10px;
                                text-align: center;
                            }
                            .content {    
                                padding: 20px;
                                flex-direction: column;
                                align-items: center;
                                border: 1px solid grey;
                                background-color: #dddddd;
                                font-size: 13px;
                            }
                        </style>
                    </head>
                    <body>
                        <div class="container">
                            <div class="header">
                                <h1>KeNHAVATE Portal</h1>
                            </div>
                            <div class="content" style="align-items: center; flex-direction: column !important; color: black;">
                                <h3 style="text-align: center;">Appointment Alert</h3>
                                <p style="margin-bottom: 0px; font-size: 16px; color: black;">
                                    This is to notify you that you have been appointed as the Idea Expert to the following Idea:
                                        <br>
                                        <span style="color: black; width: 100%; display: flex; flex-direction: column; align-items: center; flex-flow: column;">
                                            <ul>
                                                <li style="list-style: none;"><b>Idea Title</b></li>
                                                <li style="list-style: none;">' . $title . '</li>
                                            </ul>
                                            <ul>
                                                <li style="list-style: none;"><b style="text-align: center;">Innovation Area</b></li>
                                                <li style="list-style: none;">' . $innovation_area . '</li>
                                            </ul>
                                            <ul>
                                                <li style="list-style: none;"><b style="text-align: center;">Idea Description</b></li>
                                                <li style="list-style: none;">' . $brief_description . '</li>
                                            </ul>
                                        </span>
                                    Login to the KeNHAVATE Portal and check the task assigned on the dashboard
                                </p>
                                <p style="margin-bottom: 0px; bottom: 0px; position: relative;">This is an automated message do not reply.</p>
                            </div>
                            <p style="margin: 0px; text-align: center; background: #414141; color: white; border-radius: 0px 0px 5px 5px; padding: 8px;">Thank you for using the KeNHA Innovation Portal.</p>
                        </div>
                    </body>
                    </html>';
        
                    $mail->send();

                    $stmt_2 = $con->prepare("UPDATE submitted_ideas SET stage = ?, status = ?, expert_uuid = ?, day_expert_appointed = ? WHERE idea_uuid = ? LIMIT 1");
                    $stmt_2->bind_param("sssss", $stage, $status, $expert_uuid, $day_expert_appointed, $idea_uuid);
                    $stmt_2->execute();
            
                    if ($stmt_2->affected_rows > 0) {

                        //new email will be
                        //dd r and i
                        $dd_email = 'v.okumu@kenha.co.ke';

                        $mail1 = new PHPMailer(true);
                        try {
                
                            $mail1->isSMTP();
                            $mail1->Host = 'smtp.gmail.com';
                            $mail1->SMTPAuth = true;
                            $mail1->Username = 'kenhainnovation@gmail.com';
                            $mail1->Password = 'frnehuvdnrvennph';
                            $mail1->SMTPSecure = 'tls';
                
                            $mail1->Port = 587;
                            $mail1->setFrom('noreply@kenhainnovation.com','KeNHAVate Portal');
                
                            $mail1->addAddress($dd_email);$mail1->Subject = "Appointment Alert";

                            // Set the Reply-To header to a non-replyable email address
                            $mail1->addReplyTo('noreply@kenhainnovation.com', 'No Reply');

                            // Create a styled HTML email body
                            $mail1->isHTML(true);
                            $mail1->Body = '<html>
                            <head>
                                <style>
                                    /* Your CSS styles here */
                                    body {
                                        font-family: Arial, sans-serif;
                                        background-color: #f5f5f5;
                                        margin: 0;
                                        padding: 20px;
                                    }
                                    .im {
                                        color: black !important;
                                    }
                                    .container {
                                        max-width: 600px;
                                        margin: 0 auto;
                                        padding: 20px;
                                        background: linear-gradient(to right, yellow, white 20%, white 80%, yellow);
                                        border-radius: 10px;
                                        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
                                    }
                                    .header {
                                        background-color: #d8d801ed;
                                        color: #3b3b3bs;
                                        padding: 10px;
                                        text-align: center;
                                    }
                                    .content {    
                                        padding: 20px;
                                        flex-direction: column;
                                        align-items: center;
                                        border: 1px solid grey;
                                        background-color: #dddddd;
                                        font-size: 13px;
                                    }
                                </style>
                            </head>
                            <body>
                                <div class="container">
                                    <div class="header">
                                        <h1>KeNHAVATE Portal</h1>
                                    </div>
                                    <div class="content" style="align-items: center; flex-direction: column !important; color: black;">
                                        <h3 style="text-align: center;">Appointment Alert</h3>
                                        <p style="margin-bottom: 0px; font-size: 16px; color: black;">
                                            This is to notify you that you have appointed ' . $expert_email . ' as the Idea Expert to the following Idea:
                                                <br>
                                                <span style="color: black; width: 100%; display: flex; flex-direction: column; align-items: center; flex-flow: column;">
                                                    <ul>
                                                        <li style="list-style: none;"><b>Idea Title</b></li>
                                                        <li style="list-style: none;">' . $title . '</li>
                                                    </ul>
                                                    <ul>
                                                        <li style="list-style: none;"><b style="text-align: center;">Innovation Area</b></li>
                                                        <li style="list-style: none;">' . $innovation_area . '</li>
                                                    </ul>
                                                    <ul>
                                                        <li style="list-style: none;"><b style="text-align: center;">Idea Description</b></li>
                                                        <li style="list-style: none;">' . $brief_description . '</li>
                                                    </ul>
                                                    <ul>
                                                        <li style="list-style: none;"><b style="text-align: center;">Appointment Date</b></li>
                                                        <li style="list-style: none;">' . $day_expert_appointed . '</li>
                                                    </ul>
                                                </span>
                                        </p>
                                        <p style="margin-bottom: 0px; bottom: 0px; position: relative;">This is an automated message do not reply.</p>
                                    </div>
                                    <p style="margin: 0px; text-align: center; background: #414141; color: white; border-radius: 0px 0px 5px 5px; padding: 8px;">Thank you for using the KeNHA Innovation Portal.</p>
                                </div>
                            </body>
                            </html>';
                
                            $mail1->send();
                
                            $response[] = array(
                                'first_name' => decrypt($row['first_name'], $key),
                                'other_names' => decrypt($row['other_names'], $key),
                                'personal_email' => decrypt($row['personal_email'], $key),
                                'KeNHA_email' => decrypt($row['KeNHA_email'], $key),
                                'directorate' => decrypt($row['directorate'], $key),
                                'stage' => 'review',
                                'status' => 'pending'
                            );
    
                            header('Content-Type: application/json');
                            echo json_encode($response);

                            

                            $stmt_3 = $con->prepare("SELECT * FROM users_table WHERE uuid = ? LIMIT 1");
                            $stmt_3->bind_param("s", $authors_uuid);
                            $stmt_3->execute();
                            $result_3 = $stmt_3->get_result();
                        
                            if ($result_3->num_rows > 0) {
                                $row_3 = $result_3->fetch_assoc();
                
                                $authors_email = decrypt($row_3['email'], $key);
                            
                                $mail2 = new PHPMailer(true);
                                try {
                        
                                    $mail2->isSMTP();
                                    $mail2->Host = 'smtp.gmail.com';
                                    $mail2->SMTPAuth = true;
                                    $mail2->Username = 'kenhainnovation@gmail.com';
                                    $mail2->Password = 'frnehuvdnrvennph';
                                    $mail2->SMTPSecure = 'tls';
                        
                                    $mail2->Port = 587;
                                    $mail2->setFrom('noreply@kenhainnovation.com','KeNHAVate Portal');
                        
                                    $mail2->addAddress($authors_email);$mail2->Subject = "Appointment Alert";
    
                                    // Set the Reply-To header to a non-replyable email address
                                    $mail2->addReplyTo('noreply@kenhainnovation.com', 'No Reply');
    
                                    // Create a styled HTML email body
                                    $mail2->isHTML(true);
                                    $mail2->Body = '<html>
                                    <head>
                                        <style>
                                            /* Your CSS styles here */
                                            body {
                                                font-family: Arial, sans-serif;
                                                background-color: #f5f5f5;
                                                margin: 0;
                                                padding: 20px;
                                            }
                                            .im {
                                                color: black !important;
                                            }
                                            .container {
                                                max-width: 600px;
                                                margin: 0 auto;
                                                padding: 20px;
                                                background: linear-gradient(to right, yellow, white 20%, white 80%, yellow);
                                                border-radius: 10px;
                                                box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
                                            }
                                            .header {
                                                background-color: #d8d801ed;
                                                color: #3b3b3bs;
                                                padding: 10px;
                                                text-align: center;
                                            }
                                            .content {    
                                                padding: 20px;
                                                flex-direction: column;
                                                align-items: center;
                                                border: 1px solid grey;
                                                background-color: #dddddd;
                                                font-size: 13px;
                                            }
                                        </style>
                                    </head>
                                    <body>
                                        <div class="container">
                                            <div class="header">
                                                <h1>KeNHAVATE Portal</h1>
                                            </div>
                                            <div class="content" style="align-items: center; flex-direction: column !important; color: black;">
                                                <h3 style="text-align: center;">Idea Update Alert</h3>
                                                <p style="margin-bottom: 0px; font-size: 16px; color: black;">
                                                    This is to notify you that your idea status has changed and is now being reviewed by our expert. Here is a brief on your idea;
                                                        <br>
                                                        <span style="color: black; width: 100%; display: flex; flex-direction: column; align-items: center; flex-flow: column;">
                                                            <ul>
                                                                <li style="list-style: none;"><b>Idea Title</b></li>
                                                                <li style="list-style: none;">' . $title . '</li>
                                                            </ul>
                                                            <ul>
                                                                <li style="list-style: none;"><b style="text-align: center;">Innovation Area</b></li>
                                                                <li style="list-style: none;">' . $innovation_area . '</li>
                                                            </ul>
                                                            <ul>
                                                                <li style="list-style: none;"><b style="text-align: center;">Idea Description</b></li>
                                                                <li style="list-style: none;">' . $brief_description . '</li>
                                                            </ul>
                                                        </span>
                                                </p>
                                                <p style="margin-bottom: 0px; bottom: 0px; position: relative;">This is an automated message do not reply.</p>
                                            </div>
                                            <p style="margin: 0px; text-align: center; background: #414141; color: white; border-radius: 0px 0px 5px 5px; padding: 8px;">Thank you for using the KeNHA Innovation Portal.</p>
                                        </div>
                                    </body>
                                    </html>';
                        
                                    $mail2->send();
    
                                    exit;
                        
                                } catch (Exception $e) {
    
                                    // Update was successful
                                    $response = array(
                                        'success' => false,
                                        'message' => 'Error in sending Email 1',
                                    );
    
                                    exit;
                                }
                            }
                
                        } catch (Exception $e) {

                            // Update was successful
                            $response = array(
                                'success' => false,
                                'message' => 'Error in sending Email 1',
                            );

                            exit;
                        }
                    }
                    exit;
        
                } catch (Exception $e) {

                    // Update was successful
                    $response = array(
                        'success' => false,
                        'message' => 'Error in sending Email 2',
                    );

                    exit;
                }
            }
    
            // Send the JSON response
            header('Content-Type: application/json');
            echo json_encode($response);
        }

        else {
            echo json_encode('Button table returned', 'data');
        }

    }
    else if ($_GET['action'] === 'UpdateCardUnallocatedIdeas') {    
        $stmt = $con->prepare("SELECT * FROM submitted_ideas WHERE expert_uuid = ? AND day_expert_appointed = ?");
        $stmt->bind_param("ss", $expert_uuid, $day_expert_appointed);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $numRows = $result->num_rows;
    
        $unallocatedIdeasCard[] = array(
            'numRows' => $numRows
        );
    
        header('Content-Type: application/json');
        echo json_encode($unallocatedIdeasCard);
    }
    else if ($_GET['action'] === 'UpdateCardTotalIdeas') {    
        $stmt = $con->prepare("SELECT * FROM submitted_ideas");
        $stmt->execute();
        $result = $stmt->get_result();
        
        $numRows = $result->num_rows;
    
        $TotalIdeasCard[] = array(
            'numRows' => $numRows
        );
    
        header('Content-Type: application/json');
        echo json_encode($TotalIdeasCard);
    }
    else if ($_GET['action'] === 'UpdateCardAllocatedIdeas') {    
        $stmt = $con->prepare("SELECT * FROM submitted_ideas WHERE expert_uuid != ? AND day_expert_appointed != ?");
        $stmt->bind_param("ss", $expert_uuid, $day_expert_appointed);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $numRows = $result->num_rows;
    
        $allocatedIdeasCard[] = array(
            'numRows' => $numRows
        );
    
        header('Content-Type: application/json');
        echo json_encode($allocatedIdeasCard);
    }
    else if ($_GET['action'] === 'UpdateCardReviewedIdeas') {    
        $stmt = $con->prepare("SELECT * FROM submitted_ideas WHERE expert_uuid != ? AND day_expert_committed != ?");
        $stmt->bind_param("ss", $expert_uuid, $day_expert_committed);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $numRows = $result->num_rows;
    
        $reviewedIdeasCard[] = array(
            'numRows' => $numRows
        );
    
        header('Content-Type: application/json');
        echo json_encode($reviewedIdeasCard);
    }
    else if ($_GET['action'] === 'UpdateCardCommitteeIdeas') {
        $encryptedcommittee_stage = encrypt($committee_stage, $key);
        
        $stmt = $con->prepare("SELECT * FROM submitted_ideas WHERE stage = ? AND committee_approved = ?");
        $stmt->bind_param("ss", $encryptedstage, $committee_approved);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $numRows = $result->num_rows;
    
        $committeeIdeasCard[] = array(
            'numRows' => $numRows
        );
    
        header('Content-Type: application/json');
        echo json_encode($committeeIdeasCard);
    }
    else if ($_GET['action'] === 'UpdateCardBoardAllIdeas') {
        $encryptedboard_stage = encrypt($board_stage, $key);
        
        $stmt = $con->prepare("SELECT * FROM submitted_ideas WHERE stage = ? AND day_board_approved = ?");
        $stmt->bind_param("ss", $encryptedboard_stage, $day_board_approved);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $numRows = $result->num_rows;
    
        $boardAllIdeasCard[] = array(
            'numRows' => $numRows
        );
    
        header('Content-Type: application/json');
        echo json_encode($boardAllIdeasCard);
    }
    else if ($_GET['action'] === 'UpdateCardBoardRejectedIdeas') {
        $encryptedboard_stage = encrypt($board_stage, $key);
        $encryptedboard_status = encrypt($board_status, $key);

        $stmt = $con->prepare("SELECT * FROM submitted_ideas WHERE stage = ? AND status = ?");
        $stmt->bind_param("ss", $encryptedboard_stage, $encryptedboard_status);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $numRows = $result->num_rows;
    
        $boardRejectedIdeasCard[] = array(
            'numRows' => $numRows
        );
    
        header('Content-Type: application/json');
        echo json_encode($boardRejectedIdeasCard);
    }
    else if ($_GET['action'] === 'UpdateReviewedIdeaTable') {
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $itemsPerPage = isset($_GET['itemsPerPage']) ? $_GET['itemsPerPage'] : 10;
    
        // Calculate the offset based on the page and items per page
        $offset = ($page - 1) * $itemsPerPage;
    
        $stmt = $con->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM submitted_ideas WHERE expert_uuid != ? AND day_expert_committed != ? ORDER BY STR_TO_DATE(day_expert_committed, '%a, %M %d, %Y - %h:%i %p') DESC LIMIT ?, ?");
        $stmt->bind_param("ssii", $expert_uuid, $day_expert_committed, $offset, $itemsPerPage);
        $stmt->execute();
    
        $result = $stmt->get_result();
    
        // Fetch and store the results in an array
        $data = array();
        while ($row = $result->fetch_assoc()) {

            if (isset($row['expert_comment'])) {
                $expert_comment = decryptData($row['expert_comment'], $key);
                list($comment_type, $comment_text) = explode(':;', $expert_comment, 2);
            } else {
                // Handle the case where 'expert_comment' key doesn't exist
                $comment_type = 'default_comment_type';
                $comment_text = 'default_comment_text';
            }


            $data['items'][] = array(
                'idea_uuid' => $row['idea_uuid'],
                'title' => $row['title'],
                'innovation_area' => $row['innovation_area'],
                'problem_statement' => $row['problem_statement'],
                'day_user_uploaded' => $row['day_user_uploaded'],
                'day_expert_appointed' => $row['day_expert_appointed'],
                'day_expert_committed' => $row['day_expert_committed'],
                'comment_type' => $comment_type,
                'comment_text' => $comment_text,
                'stage' => decrypt($row['stage'], $key),
                'status' => decrypt($row['status'], $key)
            );
        }
    
        // Get total number of rows without LIMIT
        $totalRows = $con->query("SELECT FOUND_ROWS()")->fetch_row()[0];
    
        // Calculate total pages
        $totalPages = ceil($totalRows / $itemsPerPage);
    
        // Add total pages to the data
        $data['totalPages'] = $totalPages;
        $data['totalRows'] = $totalRows;
    
        // Return the data as JSON
        header('Content-Type: application/json');
        echo json_encode($data);
    }
    else if ($_GET['action'] === 'UpdateFormReviewedIdeas') {
        // Retrieve the upload_id from the URL
        $idea_uuid = $_GET['upload_id'];

        $stmt = $con->prepare("SELECT * FROM submitted_ideas WHERE idea_uuid = ? LIMIT 1");
        $stmt->bind_param("s", $idea_uuid);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $found_response_array = array();
    
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $db_expert_uuid = $row['expert_uuid'];

                $stmt3 = $con->prepare("SELECT * FROM staff_table WHERE staff_uuid = ? LIMIT 1");
                $stmt3->bind_param("s", $db_expert_uuid);
                $stmt3->execute();
                $result3 = $stmt3->get_result();
            
                if ($result3->num_rows > 0) {
                    while ($row3 = $result3->fetch_assoc()) {
    
                        $first_name = decrypt($row3['first_name'], $key);
                        $other_names = decrypt($row3['other_names'], $key);
    
                    }
                }
                
                $found_response_array[] = array(
                    'title' => $row['title'],
                    'innovation_area' => $row['innovation_area'],
                    'brief_description' => $row['brief_description'],
                    'problem_statement' => $row['problem_statement'],
                    'proposed_solution' => $row['proposed_solution'],
                    'cost_benefit_analysis' => $row['cost_benefit_analysis'],
                    'upload_id' => $row['upload_id'],
                    'stage' => decrypt($row['stage'], $key),
                    'status' => decrypt($row['status'], $key),
                    'day_user_uploaded' => $row['day_user_uploaded'],
                    'first_name' => $first_name,
                    'other_names' => $other_names,
                    'day_expert_appointed' => $row['day_expert_appointed'],
                    'day_expert_committed' => $row['day_expert_committed'],
                    'committee_approved' => $row['committee_approved'],
                    'day_committee_approved' => $row['day_committee_approved']
                );
            }
    
            header('Content-Type: application/json');
            echo json_encode($found_response_array);
        }
        else {
            echo json_encode(['message' => 'No table returned', 'data' => []]);
        }
    }





    else if ($_GET['action'] === 'UpdateUnreviewedIdeaTable') {
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $itemsPerPage = isset($_GET['itemsPerPage']) ? $_GET['itemsPerPage'] : 10;
    
        // Calculate the offset based on the page and items per page
        $offset = ($page - 1) * $itemsPerPage;
    
        $stmt = $con->prepare("SELECT * FROM submitted_ideas WHERE expert_uuid != ? AND day_expert_committed = ? ORDER BY STR_TO_DATE(day_expert_committed, '%a, %M %d, %Y - %h:%i %p') DESC LIMIT ?, ?");
        $stmt->bind_param("ssii", $expert_uuid, $day_expert_committed, $offset, $itemsPerPage);
        $stmt->execute();
        $result = $stmt->get_result();
    
        // Fetch total rows count
        $totalRowsQuery = $con->prepare("SELECT COUNT(*) AS totalRows FROM submitted_ideas WHERE expert_uuid != ? AND day_expert_committed = ?");
        $totalRowsQuery->bind_param("ss", $expert_uuid, $day_expert_committed);
        $totalRowsQuery->execute();
        $totalRowsResult = $totalRowsQuery->get_result();
        $totalRowsData = $totalRowsResult->fetch_assoc();
        $totalRows = $totalRowsData['totalRows'];
    
        // Close total rows query
        $totalRowsQuery->close();
    
        // Fetch and store the results in an array
        $data = array();
        while ($row = $result->fetch_assoc()) {
    
            $db_expert_uuid = $row['expert_uuid'];
    
            $stmt_1 = $con->prepare("SELECT first_name, other_names FROM staff_table WHERE staff_uuid = ? LIMIT 1");
            $stmt_1->bind_param("s", $db_expert_uuid);
            $stmt_1->execute();
            $result_1 = $stmt_1->get_result();
    
            $row_1 = $result_1->fetch_assoc();
    
            $first_name = decrypt($row_1['first_name'], $key);
            $other_names = decrypt($row_1['other_names'], $key);
    
            $data['items'][] = array(
                'idea_uuid' => $row['idea_uuid'],
                'title' => $row['title'],
                'innovation_area' => $row['innovation_area'],
                'problem_statement' => $row['problem_statement'],
                'day_user_uploaded' => $row['day_user_uploaded'],
                'day_expert_appointed' => $row['day_expert_appointed'],
                'day_expert_committed' => $row['day_expert_committed'],
                'first_name' => $first_name,
                'other_names' => $other_names,
                'stage' => decrypt($row['stage'], $key),
                'status' => decrypt($row['status'], $key)
            );
    
            $stmt_1->close();
        }
    
        // Calculate total pages
        $totalPages = ceil($totalRows / $itemsPerPage);
    
        // Add total pages and total rows to the data
        $data['totalPages'] = $totalPages;
        $data['totalRows'] = $totalRows;
    
        // Return the data as JSON
        header('Content-Type: application/json');
        echo json_encode($data);
    
        // Close the main query
        $stmt->close();
    }
    else if ($_GET['action'] === 'UpdateFormReviewedIdeas') {
        // Retrieve the upload_id from the URL
        $idea_uuid = $_GET['upload_id'];

        $stmt = $con->prepare("SELECT * FROM submitted_ideas WHERE idea_uuid = ? LIMIT 1");
        $stmt->bind_param("s", $idea_uuid);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $found_response_array = array();
    
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $db_expert_uuid = $row['expert_uuid'];

                $stmt3 = $con->prepare("SELECT * FROM staff_table WHERE staff_uuid = ? LIMIT 1");
                $stmt3->bind_param("s", $db_expert_uuid);
                $stmt3->execute();
                $result3 = $stmt3->get_result();
            
                if ($result3->num_rows > 0) {
                    while ($row3 = $result3->fetch_assoc()) {
    
                        $first_name = decrypt($row3['first_name'], $key);
                        $other_names = decrypt($row3['other_names'], $key);
    
                    }
                }
                
                $found_response_array[] = array(
                    'title' => $row['title'],
                    'innovation_area' => $row['innovation_area'],
                    'brief_description' => $row['brief_description'],
                    'problem_statement' => $row['problem_statement'],
                    'proposed_solution' => $row['proposed_solution'],
                    'cost_benefit_analysis' => $row['cost_benefit_analysis'],
                    'upload_id' => $row['upload_id'],
                    'stage' => decrypt($row['stage'], $key),
                    'status' => decrypt($row['status'], $key),
                    'day_user_uploaded' => $row['day_user_uploaded'],
                    'first_name' => $first_name,
                    'other_names' => $other_names,
                    'day_expert_appointed' => $row['day_expert_appointed'],
                    'day_expert_committed' => $row['day_expert_committed'],
                    'committee_approved' => $row['committee_approved'],
                    'day_committee_approved' => $row['day_committee_approved']
                );
            }
    
            header('Content-Type: application/json');
            echo json_encode($found_response_array);
        }
        else {
            echo json_encode(['message' => 'No table returned', 'data' => []]);
        }
    }





    else if ($_GET['action'] === 'UpdateCommitteeApprovedTable') {
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $itemsPerPage = isset($_GET['itemsPerPage']) ? $_GET['itemsPerPage'] : 10;
    
        // Calculate the offset based on the page and items per page
        $offset = ($page - 1) * $itemsPerPage;
    
        $stmt = $con->prepare("SELECT * FROM submitted_ideas WHERE committee_approved != ? AND day_committee_approved != ? ORDER BY STR_TO_DATE(day_committee_approved, '%a, %M %d, %Y - %h:%i %p') DESC LIMIT ?, ?");
        $stmt->bind_param("ssii", $committee_approved, $day_committee_approved, $offset, $itemsPerPage);
        $stmt->execute();
        $result = $stmt->get_result();
    
        // Fetch total rows count
        $totalRowsQuery = $con->prepare("SELECT COUNT(*) AS totalRows FROM submitted_ideas WHERE committee_approved != ? AND day_committee_approved != ?");
        $totalRowsQuery->bind_param("ss", $committee_approved, $day_committee_approved);
        $totalRowsQuery->execute();
        $totalRowsResult = $totalRowsQuery->get_result();
        $totalRowsData = $totalRowsResult->fetch_assoc();
        $totalRows = $totalRowsData['totalRows'];
    
        // Close total rows query
        $totalRowsQuery->close();
    
        // Fetch and store the results in an array
        $data = array();
        while ($row = $result->fetch_assoc()) {
    
            $db_expert_uuid = $row['expert_uuid'];
    
            $data['items'][] = array(
                'idea_uuid' => $row['idea_uuid'],
                'title' => $row['title'],
                'innovation_area' => $row['innovation_area'],
                'problem_statement' => $row['problem_statement'],
                'day_user_uploaded' => $row['day_user_uploaded'],
                'stage' => 'committee',
                'status' => 'approved'
            );
    
            $stmt_1->close();
        }
    
        // Calculate total pages
        $totalPages = ceil($totalRows / $itemsPerPage);
    
        // Add total pages and total rows to the data
        $data['totalPages'] = $totalPages;
        $data['totalRows'] = $totalRows;
    
        // Return the data as JSON
        header('Content-Type: application/json');
        echo json_encode($data);
    
        // Close the main query
        $stmt->close();
    }
    else if ($_GET['action'] === 'UpdateFormReviewedIdeas') {
        // Retrieve the upload_id from the URL
        $idea_uuid = $_GET['upload_id'];

        $stmt = $con->prepare("SELECT * FROM submitted_ideas WHERE idea_uuid = ? LIMIT 1");
        $stmt->bind_param("s", $idea_uuid);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $found_response_array = array();
    
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $db_expert_uuid = $row['expert_uuid'];

                $stmt3 = $con->prepare("SELECT * FROM staff_table WHERE staff_uuid = ? LIMIT 1");
                $stmt3->bind_param("s", $db_expert_uuid);
                $stmt3->execute();
                $result3 = $stmt3->get_result();
            
                if ($result3->num_rows > 0) {
                    while ($row3 = $result3->fetch_assoc()) {
    
                        $first_name = decrypt($row3['first_name'], $key);
                        $other_names = decrypt($row3['other_names'], $key);
    
                    }
                }
                
                $found_response_array[] = array(
                    'title' => $row['title'],
                    'innovation_area' => $row['innovation_area'],
                    'brief_description' => $row['brief_description'],
                    'problem_statement' => $row['problem_statement'],
                    'proposed_solution' => $row['proposed_solution'],
                    'cost_benefit_analysis' => $row['cost_benefit_analysis'],
                    'upload_id' => $row['upload_id'],
                    'stage' => decrypt($row['stage'], $key),
                    'status' => decrypt($row['status'], $key),
                    'day_user_uploaded' => $row['day_user_uploaded'],
                    'first_name' => $first_name,
                    'other_names' => $other_names,
                    'day_expert_appointed' => $row['day_expert_appointed'],
                    'day_expert_committed' => $row['day_expert_committed'],
                    'committee_approved' => $row['committee_approved'],
                    'day_committee_approved' => $row['day_committee_approved']
                );
            }
    
            header('Content-Type: application/json');
            echo json_encode($found_response_array);
        }
        else {
            echo json_encode(['message' => 'No table returned', 'data' => []]);
        }
    }




    //this section is complete
    else if ($_GET['action'] === 'UpdateEscalateIdeaTable') {
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $itemsPerPage = isset($_GET['itemsPerPage']) ? $_GET['itemsPerPage'] : 10;
    
        // Calculate the offset based on the page and items per page
        $offset = ($page - 1) * $itemsPerPage;
        $stage = encrypt("review done", $key);
        $status = encrypt("awaiting report", $key);
    
        $stmt = $con->prepare("SELECT * FROM submitted_ideas WHERE stage = ? AND status = ? ORDER BY STR_TO_DATE(day_expert_committed, '%a, %M %d, %Y - %h:%i %p') DESC LIMIT ?, ?");
        $stmt->bind_param("ssii", $stage, $status, $offset, $itemsPerPage);
        $stmt->execute();
        $result = $stmt->get_result();
    
        // Fetch total rows count
        $totalRowsQuery = $con->prepare("SELECT COUNT(*) AS totalRows FROM submitted_ideas WHERE stage = ? AND status = ?");
        $totalRowsQuery->bind_param("ss", $stage, $status);
        $totalRowsQuery->execute();
        $totalRowsResult = $totalRowsQuery->get_result();
        $totalRowsData = $totalRowsResult->fetch_assoc();
        $totalRows = $totalRowsData['totalRows'];
    
        // Close total rows query
        $totalRowsQuery->close();
    
        // Fetch and store the results in an array
        $data = array();
        while ($row = $result->fetch_assoc()) {
    
            $db_expert_uuid = $row['expert_uuid'];
    
            $data['items'][] = array(
                'idea_uuid' => $row['idea_uuid'],
                'title' => $row['title'],
                'innovation_area' => $row['innovation_area'],
                'problem_statement' => $row['problem_statement'],
                'day_user_uploaded' => $row['day_user_uploaded'],
                'day_expert_committed' => $row['day_expert_committed'],
                'stage' => decrypt($row['stage'], $key),
                'status' => decrypt($row['status'], $key)
            );
        }
    
        // Calculate total pages
        $totalPages = ceil($totalRows / $itemsPerPage);
    
        // Add total pages and total rows to the data
        $data['totalPages'] = $totalPages;
        $data['totalRows'] = $totalRows;
    
        // Return the data as JSON
        header('Content-Type: application/json');
        echo json_encode($data);
    
        // Close the main query
        $stmt->close();
    }
    //all updates form in the committe will use the same fetch method
    else if ($_GET['action'] === 'UpdateFormEscalateIdeas' ||$_GET['action'] === 'UpdateFormApproveIdeas' || $_GET['action'] === 'UpdateFormRejectIdeas' || $_GET['action'] === 'UpdateFormPendingIdeas' || $_GET['action'] === 'UpdateFormApprovedIdeas' || $_GET['action'] === 'UpdateFormRejectedIdeas') {
        // Retrieve the upload_id from the URL
        $idea_uuid = $_GET['upload_id'];

        $stmt = $con->prepare("SELECT * FROM submitted_ideas WHERE idea_uuid = ? LIMIT 1");
        $stmt->bind_param("s", $idea_uuid);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $found_response_array = array();
    
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $db_expert_uuid = $row['expert_uuid'];

                $stmt3 = $con->prepare("SELECT * FROM staff_table WHERE staff_uuid = ? LIMIT 1");
                $stmt3->bind_param("s", $db_expert_uuid);
                $stmt3->execute();
                $result3 = $stmt3->get_result();
            
                if ($result3->num_rows > 0) {
                    while ($row3 = $result3->fetch_assoc()) {
    
                        $first_name = decrypt($row3['first_name'], $key);
                        $other_names = decrypt($row3['other_names'], $key);
    
                    }
                }
                
                $found_response_array[] = array(
                    'idea_uuid' => $row['idea_uuid'],
                    'title' => $row['title'],
                    'innovation_area' => $row['innovation_area'],
                    'brief_description' => $row['brief_description'],
                    'problem_statement' => $row['problem_statement'],
                    'proposed_solution' => $row['proposed_solution'],
                    'cost_benefit_analysis' => $row['cost_benefit_analysis'],
                    'upload_id' => $row['upload_id'],
                    'stage' => decrypt($row['stage'], $key),
                    'status' => decrypt($row['status'], $key),
                    'day_user_uploaded' => $row['day_user_uploaded'],
                    'first_name' => $first_name,
                    'other_names' => $other_names,
                    'day_expert_appointed' => $row['day_expert_appointed'],
                    'day_expert_committed' => $row['day_expert_committed'],
                    'committee_approved' => $row['committee_approved'],
                    'day_committee_approved' => $row['day_committee_approved']
                );
            }
    
            header('Content-Type: application/json');
            echo json_encode($found_response_array);
        }
        else {
            echo json_encode(['message' => 'No table returned', 'data' => []]);
        }
    }
    else if ($_GET['action'] === 'UpdateDB_EscalateIdeas') {
        // Retrieve the upload_id from the URL
        $idea_uuid = $_GET['upload_id'];
        $stage = encrypt('committee', $key);
        $status = encrypt('pending', $key);

        $stmt = $con->prepare("UPDATE submitted_ideas SET status = ?, stage = ? WHERE idea_uuid = ? LIMIT 1");
        $stmt->bind_param("sss", $status, $stage, $idea_uuid);
        $stmt->execute();
        
        // Retrieve the user_uuid from the updated row
        $stmt_1 = $con->prepare("SELECT * FROM submitted_ideas WHERE idea_uuid = ? LIMIT 1");
        $stmt_1->bind_param("s", $idea_uuid);
        $stmt_1->execute();
        $result_1 = $stmt_1->get_result();

        if ($result_1->num_rows > 0 && $stmt->affected_rows > 0) {
            $row_1 = $result_1->fetch_assoc();
            $user_uuid = $row_1['user_uuid'];
            $title = $row_1['title'];
            $brief_description = $row_1['brief_description'];
            $problem_statement = $row_1['problem_statement'];
            
            // Retrieve the user_uuid from the updated row
            $stmt_2 = $con->prepare("SELECT email FROM users_table WHERE uuid = ? LIMIT 1");
            $stmt_2->bind_param("s", $user_uuid);
            $stmt_2->execute();
            $result_2 = $stmt_2->get_result();

            if ($result_2->num_rows > 0) {
                $row_2 = $result_2->fetch_assoc();
                $email = decrypt($row_2['email'], $key);

                //send email to user

                $mail = new PHPMailer(true);
                try {
        
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'kenhainnovation@gmail.com';
                    $mail->Password = 'frnehuvdnrvennph';
                    $mail->SMTPSecure = 'tls';
        
                    $mail->Port = 587;
                    $mail->setFrom('noreply@kenhainnovation.com','KeNHAVate Portal');
        
                    $mail->addAddress($email);$mail->Subject = "Idea Update Alert";

                    // Set the Reply-To header to a non-replyable email address
                    $mail->addReplyTo('noreply@kenhainnovation.com', 'No Reply');

                    // Create a styled HTML email body
                    $mail->isHTML(true);
                    $mail->Body = '<html>
                    <head>
                        <style>
                            /* Your CSS styles here */
                            body {
                                font-family: Arial, sans-serif;
                                background-color: #f5f5f5;
                                margin: 0;
                                padding: 20px;
                            }
                            .im {
                                color: black !important;
                            }
                            .container {
                                max-width: 600px;
                                margin: 0 auto;
                                padding: 20px;
                                background: linear-gradient(to right, yellow, white 20%, white 80%, yellow);
                                border-radius: 10px;
                                box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
                            }
                            .header {
                                background-color: #d8d801ed;
                                color: #3b3b3bs;
                                padding: 10px;
                                text-align: center;
                            }
                            .content {    
                                padding: 20px;
                                flex-direction: column;
                                align-items: center;
                                border: 1px solid grey;
                                background-color: #dddddd;
                                font-size: 13px;
                            }
                        </style>
                    </head>
                    <body>
                        <div class="container">
                            <div class="header">
                                <h1>KeNHAVATE Portal</h1>
                            </div>
                            <div class="content" style="align-items: center; flex-direction: column !important; color: black;">
                                <h3 style="text-align: center;">Idea Update Alert</h3>
                                <p style="margin-bottom: 0px; font-size: 16px; color: black;">We are pleased to inform you that your idea submission titled: <strong>' . $title . '</strong>, has successfully passed the review stage and is now at the committee stage.</p>
                                <p style="margin-bottom: 0px; font-size: 16px; color: black;"><strong>Idea Description:</strong> ' . $brief_description . '</p>
                                <p style="margin-bottom: 0px; font-size: 16px; color: black;"><strong>Problem Statement:</strong> ' . $problem_statement . '</p>
                                <p style="margin-bottom: 0px; font-size: 16px; color: black;">Our committee will carefully evaluate your idea, and you will be notified once the process is completed.</p>
                                <p style="margin-bottom: 0px; font-size: 16px; color: black;">Thank you for your valuable contribution and patience throughout this process.</p>
                                <br>
                                <br>
                                Best regards,
                                <br>
                                KeNHAVATE Management Team
                                </p>
                                <p style="margin-bottom: 0px; bottom: 0px; position: relative;">This is an automated message do not reply.</p>
                            </div>
                            <p style="margin: 0px; text-align: center; background: #414141; color: white; border-radius: 0px 0px 5px 5px; padding: 8px;">Thank you for using the KeNHA Innovation Portal.</p>
                        </div>
                    </body>
                    </html>';
        
                    $mail->send();

                    echo json_encode(['message' => 'Successful']);header('Content-Type: application/json');
                    
        
                }
                catch (Exception $e) {
                    
                    echo json_encode(['message' => 'Error']);header('Content-Type: application/json');

                    exit;
                }
            }
            else {
                echo json_encode(['message' => 'Error']);header('Content-Type: application/json');
            }
        }
        else {
            echo json_encode(['message' => 'Error']);header('Content-Type: application/json');
        }
    }

    //to reject or approve or pending ideas at the committee will require the same method of fetching
    else if ($_GET['action'] === 'UpdateRejectIdeaTable' || $_GET['action'] === 'UpdateApproveIdeaTable' || $_GET['action'] === 'UpdatePendingIdeaTable') {
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $itemsPerPage = isset($_GET['itemsPerPage']) ? $_GET['itemsPerPage'] : 10;
    
        // Calculate the offset based on the page and items per page
        $offset = ($page - 1) * $itemsPerPage;
        $stage = encrypt("committee", $key);
        $status = encrypt("pending", $key);
    
        $stmt = $con->prepare("SELECT * FROM submitted_ideas WHERE stage = ? AND status = ? ORDER BY STR_TO_DATE(day_expert_committed, '%a, %M %d, %Y - %h:%i %p') DESC LIMIT ?, ?");
        $stmt->bind_param("ssii", $stage, $status, $offset, $itemsPerPage);
        $stmt->execute();
        $result = $stmt->get_result();
    
        // Fetch total rows count
        $totalRowsQuery = $con->prepare("SELECT COUNT(*) AS totalRows FROM submitted_ideas WHERE stage = ? AND status = ?");
        $totalRowsQuery->bind_param("ss", $stage, $status);
        $totalRowsQuery->execute();
        $totalRowsResult = $totalRowsQuery->get_result();
        $totalRowsData = $totalRowsResult->fetch_assoc();
        $totalRows = $totalRowsData['totalRows'];
    
        // Close total rows query
        $totalRowsQuery->close();
    
        // Fetch and store the results in an array
        $data = array();
        while ($row = $result->fetch_assoc()) {
    
            $db_expert_uuid = $row['expert_uuid'];
    
            $data['items'][] = array(
                'idea_uuid' => $row['idea_uuid'],
                'title' => $row['title'],
                'innovation_area' => $row['innovation_area'],
                'problem_statement' => $row['problem_statement'],
                'day_user_uploaded' => $row['day_user_uploaded'],
                'day_expert_committed' => $row['day_expert_committed'],
                'stage' => decrypt($row['stage'], $key),
                'status' => decrypt($row['status'], $key)
            );
        }
    
        // Calculate total pages
        $totalPages = ceil($totalRows / $itemsPerPage);
    
        // Add total pages and total rows to the data
        $data['totalPages'] = $totalPages;
        $data['totalRows'] = $totalRows;
    
        // Return the data as JSON
        header('Content-Type: application/json');
        echo json_encode($data);
    
        // Close the main query
        $stmt->close();
    }
    else if ($_GET['action'] === 'UpdateDB_RejectIdeas') {
        // Retrieve the upload_id from the URL
        $idea_uuid = $_GET['upload_id'];
        $stage = encrypt('committee', $key);
        $status = encrypt('rejected', $key);
        $date = date('D, F j, Y - h:i A');

        $stmt = $con->prepare("UPDATE submitted_ideas SET status = ?, stage = ?, day_committee_approved = ? WHERE idea_uuid = ? LIMIT 1");
        $stmt->bind_param("ssss", $status, $stage, $date, $idea_uuid);
        $stmt->execute();
        
        // Retrieve the user_uuid from the updated row
        $stmt_1 = $con->prepare("SELECT * FROM submitted_ideas WHERE idea_uuid = ? LIMIT 1");
        $stmt_1->bind_param("s", $idea_uuid);
        $stmt_1->execute();
        $result_1 = $stmt_1->get_result();

        if ($result_1->num_rows > 0 && $stmt->affected_rows > 0) {
            $row_1 = $result_1->fetch_assoc();
            $user_uuid = $row_1['user_uuid'];
            $title = $row_1['title'];
            $brief_description = $row_1['brief_description'];
            $problem_statement = $row_1['problem_statement'];
            
            // Retrieve the user_uuid from the updated row
            $stmt_2 = $con->prepare("SELECT email FROM users_table WHERE uuid = ? LIMIT 1");
            $stmt_2->bind_param("s", $user_uuid);
            $stmt_2->execute();
            $result_2 = $stmt_2->get_result();

            if ($result_2->num_rows > 0) {
                $row_2 = $result_2->fetch_assoc();
                $email = decrypt($row_2['email'], $key);

                //send email to user

                $mail = new PHPMailer(true);
                try {
        
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'kenhainnovation@gmail.com';
                    $mail->Password = 'frnehuvdnrvennph';
                    $mail->SMTPSecure = 'tls';
        
                    $mail->Port = 587;
                    $mail->setFrom('noreply@kenhainnovation.com','KeNHAVate Portal');
        
                    $mail->addAddress($email);$mail->Subject = "Idea Update Alert";

                    // Set the Reply-To header to a non-replyable email address
                    $mail->addReplyTo('noreply@kenhainnovation.com', 'No Reply');

                    // Create a styled HTML email body
                    $mail->isHTML(true);
                    $mail->Body = '<html>
                    <head>
                        <style>
                            /* Your CSS styles here */
                            body {
                                font-family: Arial, sans-serif;
                                background-color: #f5f5f5;
                                margin: 0;
                                padding: 20px;
                            }
                            .im {
                                color: black !important;
                            }
                            .container {
                                max-width: 600px;
                                margin: 0 auto;
                                padding: 20px;
                                background: linear-gradient(to right, yellow, white 20%, white 80%, yellow);
                                border-radius: 10px;
                                box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
                            }
                            .header {
                                background-color: #d8d801ed;
                                color: #3b3b3bs;
                                padding: 10px;
                                text-align: center;
                            }
                            .content {    
                                padding: 20px;
                                flex-direction: column;
                                align-items: center;
                                border: 1px solid grey;
                                background-color: #dddddd;
                                font-size: 13px;
                            }
                        </style>
                    </head>
                    <body>
                        <div class="container">
                            <div class="header">
                                <h1>KeNHAVATE Portal</h1>
                            </div>
                            <div class="content" style="align-items: center; flex-direction: column !important; color: black;">
                                <h3 style="text-align: center;">Idea Update Alert</h3>
                                <p style="margin-bottom: 0px; font-size: 16px; color: black;">We regret to inform you that your idea submission titled: <strong>' . $title . '</strong>, has been reviewed by the committee and unfortunately, it has not been selected to proceed to the implementation stage since it did not meet our criteria for implementation.</p>
                                <p style="margin-bottom: 0px; font-size: 16px; color: black;"><strong>Idea Description:</strong> ' . $brief_description . '</p>
                                <p style="margin-bottom: 0px; font-size: 16px; color: black;"><strong>Problem Statement:</strong> ' . $problem_statement . '</p>
                                <p style="margin-bottom: 0px; font-size: 16px; color: black;">We appreciate your participation and the effort you put into submitting your idea. Please do not be discouraged, as we encourage you to continue exploring new ideas and opportunities for innovation.</p>
                                <p style="margin-bottom: 0px; font-size: 16px; color: black;">Thank you for your valuable contribution.</p>
                                <br>
                                <br>
                                Best regards,
                                <br>
                                KeNHAVATE Management Team
                                </p>
                                <p style="margin-bottom: 0px; bottom: 0px; position: relative;">This is an automated message do not reply.</p>
                            </div>
                            <p style="margin: 0px; text-align: center; background: #414141; color: white; border-radius: 0px 0px 5px 5px; padding: 8px;">Thank you for using the KeNHA Innovation Portal.</p>
                        </div>
                    </body>
                    </html>';
        
                    $mail->send();

                    echo json_encode(['message' => 'Successful']);header('Content-Type: application/json');
                    
        
                }
                catch (Exception $e) {
                    
                    echo json_encode(['message' => 'Error']);header('Content-Type: application/json');

                    exit;
                }
            }
            else {
                echo json_encode(['message' => 'Error']);header('Content-Type: application/json');
            }
        }
        else {
            echo json_encode(['message' => 'Error']);header('Content-Type: application/json');
        }
    }

    else if ($_GET['action'] === 'UpdateDB_ApproveIdeas') {
        // Retrieve the upload_id from the URL
        $idea_uuid = $_GET['upload_id'];
        $stage = encrypt('committee', $key);
        $status = encrypt('approved', $key);
        $date = date('D, F j, Y - h:i A');

        $stmt = $con->prepare("UPDATE submitted_ideas SET status = ?, stage = ?, day_committee_approved = ? WHERE idea_uuid = ? LIMIT 1");
        $stmt->bind_param("ssss", $status, $stage, $date, $idea_uuid);
        $stmt->execute();
        
        // Retrieve the user_uuid from the updated row
        $stmt_1 = $con->prepare("SELECT * FROM submitted_ideas WHERE idea_uuid = ? LIMIT 1");
        $stmt_1->bind_param("s", $idea_uuid);
        $stmt_1->execute();
        $result_1 = $stmt_1->get_result();

        if ($result_1->num_rows > 0 && $stmt->affected_rows > 0) {
            $row_1 = $result_1->fetch_assoc();
            $user_uuid = $row_1['user_uuid'];
            $title = $row_1['title'];
            $brief_description = $row_1['brief_description'];
            $problem_statement = $row_1['problem_statement'];
            
            // Retrieve the user_uuid from the updated row
            $stmt_2 = $con->prepare("SELECT email FROM users_table WHERE uuid = ? LIMIT 1");
            $stmt_2->bind_param("s", $user_uuid);
            $stmt_2->execute();
            $result_2 = $stmt_2->get_result();

            if ($result_2->num_rows > 0) {
                $row_2 = $result_2->fetch_assoc();
                $email = decrypt($row_2['email'], $key);

                //send email to user

                $mail = new PHPMailer(true);
                try {
        
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'kenhainnovation@gmail.com';
                    $mail->Password = 'frnehuvdnrvennph';
                    $mail->SMTPSecure = 'tls';
        
                    $mail->Port = 587;
                    $mail->setFrom('noreply@kenhainnovation.com','KeNHAVate Portal');
        
                    $mail->addAddress($email);$mail->Subject = "Idea Update Alert";

                    // Set the Reply-To header to a non-replyable email address
                    $mail->addReplyTo('noreply@kenhainnovation.com', 'No Reply');

                    // Create a styled HTML email body
                    $mail->isHTML(true);
                    $mail->Body = '<html>
                    <head>
                        <style>
                            /* Your CSS styles here */
                            body {
                                font-family: Arial, sans-serif;
                                background-color: #f5f5f5;
                                margin: 0;
                                padding: 20px;
                            }
                            .im {
                                color: black !important;
                            }
                            .container {
                                max-width: 600px;
                                margin: 0 auto;
                                padding: 20px;
                                background: linear-gradient(to right, yellow, white 20%, white 80%, yellow);
                                border-radius: 10px;
                                box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
                            }
                            .header {
                                background-color: #d8d801ed;
                                color: #3b3b3bs;
                                padding: 10px;
                                text-align: center;
                            }
                            .content {    
                                padding: 20px;
                                flex-direction: column;
                                align-items: center;
                                border: 1px solid grey;
                                background-color: #dddddd;
                                font-size: 13px;
                            }
                        </style>
                    </head>
                    <body>
                        <div class="container">
                            <div class="header">
                                <h1>KeNHAVATE Portal</h1>
                            </div>
                            <div class="content" style="align-items: center; flex-direction: column !important; color: black;">
                                <h3 style="text-align: center;">Idea Update Alert</h3>
                                <p style="margin-bottom: 0px; font-size: 16px; color: black;">We are pleased to inform you that your idea submission titled: <strong>' . $title . '</strong>, has successfully passed the committee review stage and is now advancing to the board stage for final review.</p>
                                <p style="margin-bottom: 0px; font-size: 16px; color: black;"><strong>Idea Description:</strong> ' . $brief_description . '</p>
                                <p style="margin-bottom: 0px; font-size: 16px; color: black;"><strong>Problem Statement:</strong> ' . $problem_statement . '</p>
                                <p style="margin-bottom: 0px; font-size: 16px; color: black;">Our board members will carefully evaluate your idea in the next stage of the review process. Your continued participation and contribution are greatly appreciated.</p>
                                <p style="margin-bottom: 0px; font-size: 16px; color: black;">Thank you for your valuable input and dedication to innovation.</p>
                                <br>
                                <br>
                                Best regards,
                                <br>
                                KeNHAVATE Management Team
                                </p>
                                <p style="margin-bottom: 0px; bottom: 0px; position: relative;">This is an automated message do not reply.</p>
                            </div>
                            <p style="margin: 0px; text-align: center; background: #414141; color: white; border-radius: 0px 0px 5px 5px; padding: 8px;">Thank you for using the KeNHA Innovation Portal.</p>
                        </div>
                    </body>
                    </html>';
        
                    $mail->send();

                    echo json_encode(['message' => 'Successful']);header('Content-Type: application/json');
                    
        
                }
                catch (Exception $e) {
                    
                    echo json_encode(['message' => 'Error']);header('Content-Type: application/json');

                    exit;
                }
            }
            else {
                echo json_encode(['message' => 'Error']);header('Content-Type: application/json');
            }
        }
        else {
            echo json_encode(['message' => 'Error']);header('Content-Type: application/json');
        }
    }
    else if ($_GET['action'] === 'UpdateApprovedIdeaTable') {
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $itemsPerPage = isset($_GET['itemsPerPage']) ? $_GET['itemsPerPage'] : 10;
    
        // Calculate the offset based on the page and items per page
        $offset = ($page - 1) * $itemsPerPage;
        $stage = encrypt("committee", $key);
        $status = encrypt("approved", $key);
    
        $stmt = $con->prepare("SELECT * FROM submitted_ideas WHERE stage = ? AND status = ? ORDER BY STR_TO_DATE(day_expert_committed, '%a, %M %d, %Y - %h:%i %p') DESC LIMIT ?, ?");
        $stmt->bind_param("ssii", $stage, $status, $offset, $itemsPerPage);
        $stmt->execute();
        $result = $stmt->get_result();
    
        // Fetch total rows count
        $totalRowsQuery = $con->prepare("SELECT COUNT(*) AS totalRows FROM submitted_ideas WHERE stage = ? AND status = ?");
        $totalRowsQuery->bind_param("ss", $stage, $status);
        $totalRowsQuery->execute();
        $totalRowsResult = $totalRowsQuery->get_result();
        $totalRowsData = $totalRowsResult->fetch_assoc();
        $totalRows = $totalRowsData['totalRows'];
    
        // Close total rows query
        $totalRowsQuery->close();
    
        // Fetch and store the results in an array
        $data = array();
        while ($row = $result->fetch_assoc()) {
    
            $db_expert_uuid = $row['expert_uuid'];
    
            $data['items'][] = array(
                'idea_uuid' => $row['idea_uuid'],
                'title' => $row['title'],
                'innovation_area' => $row['innovation_area'],
                'problem_statement' => $row['problem_statement'],
                'day_user_uploaded' => $row['day_user_uploaded'],
                'day_expert_committed' => $row['day_expert_committed'],
                'stage' => decrypt($row['stage'], $key),
                'status' => decrypt($row['status'], $key)
            );
        }
    
        // Calculate total pages
        $totalPages = ceil($totalRows / $itemsPerPage);
    
        // Add total pages and total rows to the data
        $data['totalPages'] = $totalPages;
        $data['totalRows'] = $totalRows;
    
        // Return the data as JSON
        header('Content-Type: application/json');
        echo json_encode($data);
    
        // Close the main query
        $stmt->close();
    }
    else if ($_GET['action'] === 'UpdateRejectedIdeaTable') {
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $itemsPerPage = isset($_GET['itemsPerPage']) ? $_GET['itemsPerPage'] : 10;
    
        // Calculate the offset based on the page and items per page
        $offset = ($page - 1) * $itemsPerPage;
        $stage = encrypt("committee", $key);
        $status = encrypt("rejected", $key);
    
        $stmt = $con->prepare("SELECT * FROM submitted_ideas WHERE stage = ? AND status = ? ORDER BY STR_TO_DATE(day_expert_committed, '%a, %M %d, %Y - %h:%i %p') DESC LIMIT ?, ?");
        $stmt->bind_param("ssii", $stage, $status, $offset, $itemsPerPage);
        $stmt->execute();
        $result = $stmt->get_result();
    
        // Fetch total rows count
        $totalRowsQuery = $con->prepare("SELECT COUNT(*) AS totalRows FROM submitted_ideas WHERE stage = ? AND status = ?");
        $totalRowsQuery->bind_param("ss", $stage, $status);
        $totalRowsQuery->execute();
        $totalRowsResult = $totalRowsQuery->get_result();
        $totalRowsData = $totalRowsResult->fetch_assoc();
        $totalRows = $totalRowsData['totalRows'];
    
        // Close total rows query
        $totalRowsQuery->close();
    
        // Fetch and store the results in an array
        $data = array();
        while ($row = $result->fetch_assoc()) {
    
            $db_expert_uuid = $row['expert_uuid'];
    
            $data['items'][] = array(
                'idea_uuid' => $row['idea_uuid'],
                'title' => $row['title'],
                'innovation_area' => $row['innovation_area'],
                'problem_statement' => $row['problem_statement'],
                'day_user_uploaded' => $row['day_user_uploaded'],
                'day_expert_committed' => $row['day_expert_committed'],
                'stage' => decrypt($row['stage'], $key),
                'status' => decrypt($row['status'], $key)
            );
        }
    
        // Calculate total pages
        $totalPages = ceil($totalRows / $itemsPerPage);
    
        // Add total pages and total rows to the data
        $data['totalPages'] = $totalPages;
        $data['totalRows'] = $totalRows;
    
        // Return the data as JSON
        header('Content-Type: application/json');
        echo json_encode($data);
    
        // Close the main query
        $stmt->close();
    }







    
    else if ($_GET['action'] === 'UpdateEscalateBoardIdeaTable') {
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $itemsPerPage = isset($_GET['itemsPerPage']) ? $_GET['itemsPerPage'] : 10;
    
        // Calculate the offset based on the page and items per page
        $offset = ($page - 1) * $itemsPerPage;
        $stage = encrypt("committee", $key);
        $status = encrypt("approved", $key);
    
        $stmt = $con->prepare("SELECT * FROM submitted_ideas WHERE stage = ? AND status = ? ORDER BY STR_TO_DATE(day_expert_committed, '%a, %M %d, %Y - %h:%i %p') DESC LIMIT ?, ?");
        $stmt->bind_param("ssii", $stage, $status, $offset, $itemsPerPage);
        $stmt->execute();
        $result = $stmt->get_result();
    
        // Fetch total rows count
        $totalRowsQuery = $con->prepare("SELECT COUNT(*) AS totalRows FROM submitted_ideas WHERE stage = ? AND status = ?");
        $totalRowsQuery->bind_param("ss", $stage, $status);
        $totalRowsQuery->execute();
        $totalRowsResult = $totalRowsQuery->get_result();
        $totalRowsData = $totalRowsResult->fetch_assoc();
        $totalRows = $totalRowsData['totalRows'];
    
        // Close total rows query
        $totalRowsQuery->close();
    
        // Fetch and store the results in an array
        $data = array();
        while ($row = $result->fetch_assoc()) {
    
            $db_expert_uuid = $row['expert_uuid'];
    
            $data['items'][] = array(
                'idea_uuid' => $row['idea_uuid'],
                'title' => $row['title'],
                'innovation_area' => $row['innovation_area'],
                'problem_statement' => $row['problem_statement'],
                'day_user_uploaded' => $row['day_user_uploaded'],
                'day_expert_committed' => $row['day_expert_committed'],
                'stage' => decrypt($row['stage'], $key),
                'status' => decrypt($row['status'], $key)
            );
        }
    
        // Calculate total pages
        $totalPages = ceil($totalRows / $itemsPerPage);
    
        // Add total pages and total rows to the data
        $data['totalPages'] = $totalPages;
        $data['totalRows'] = $totalRows;
    
        // Return the data as JSON
        header('Content-Type: application/json');
        echo json_encode($data);
    
        // Close the main query
        $stmt->close();
    }
    
    else if ($_GET['action'] === 'UpdateFormEscalateBoardIdeas' ||$_GET['action'] === 'UpdateFormApproveBoardIdeas' || $_GET['action'] === 'UpdateFormRejectBoardIdeas' || $_GET['action'] === 'UpdateFormPendingBoardIdeas' || $_GET['action'] === 'UpdateFormApprovedBoardIdeas' || $_GET['action'] === 'UpdateFormRejectedBoardIdeas') {
        // Retrieve the upload_id from the URL
        $idea_uuid = $_GET['upload_id'];

        $stmt = $con->prepare("SELECT * FROM submitted_ideas WHERE idea_uuid = ? LIMIT 1");
        $stmt->bind_param("s", $idea_uuid);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $found_response_array = array();
    
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $db_expert_uuid = $row['expert_uuid'];

                $stmt3 = $con->prepare("SELECT * FROM staff_table WHERE staff_uuid = ? LIMIT 1");
                $stmt3->bind_param("s", $db_expert_uuid);
                $stmt3->execute();
                $result3 = $stmt3->get_result();
            
                if ($result3->num_rows > 0) {
                    while ($row3 = $result3->fetch_assoc()) {
    
                        $first_name = decrypt($row3['first_name'], $key);
                        $other_names = decrypt($row3['other_names'], $key);
    
                    }
                }
                
                $found_response_array[] = array(
                    'idea_uuid' => $row['idea_uuid'],
                    'title' => $row['title'],
                    'innovation_area' => $row['innovation_area'],
                    'brief_description' => $row['brief_description'],
                    'problem_statement' => $row['problem_statement'],
                    'proposed_solution' => $row['proposed_solution'],
                    'cost_benefit_analysis' => $row['cost_benefit_analysis'],
                    'upload_id' => $row['upload_id'],
                    'stage' => decrypt($row['stage'], $key),
                    'status' => decrypt($row['status'], $key),
                    'day_user_uploaded' => $row['day_user_uploaded'],
                    'first_name' => $first_name,
                    'other_names' => $other_names,
                    'day_expert_appointed' => $row['day_expert_appointed'],
                    'day_expert_committed' => $row['day_expert_committed'],
                    'committee_approved' => $row['committee_approved'],
                    'day_committee_approved' => $row['day_committee_approved']
                );
            }
    
            header('Content-Type: application/json');
            echo json_encode($found_response_array);
        }
        else {
            echo json_encode(['message' => 'No table returned', 'data' => []]);
        }
    }
    else if ($_GET['action'] === 'UpdateDB_EscalateBoardIdeas') {
        // Retrieve the upload_id from the URL
        $idea_uuid = $_GET['upload_id'];
        $stage = encrypt('board', $key);
        $status = encrypt('pending', $key);

        $stmt = $con->prepare("UPDATE submitted_ideas SET status = ?, stage = ? WHERE idea_uuid = ? LIMIT 1");
        $stmt->bind_param("sss", $status, $stage, $idea_uuid);
        $stmt->execute();
        
        // Retrieve the user_uuid from the updated row
        $stmt_1 = $con->prepare("SELECT * FROM submitted_ideas WHERE idea_uuid = ? LIMIT 1");
        $stmt_1->bind_param("s", $idea_uuid);
        $stmt_1->execute();
        $result_1 = $stmt_1->get_result();

        if ($result_1->num_rows > 0 && $stmt->affected_rows > 0) {
            $row_1 = $result_1->fetch_assoc();
            $user_uuid = $row_1['user_uuid'];
            $title = $row_1['title'];
            $brief_description = $row_1['brief_description'];
            $problem_statement = $row_1['problem_statement'];
            
            // Retrieve the user_uuid from the updated row
            $stmt_2 = $con->prepare("SELECT email FROM users_table WHERE uuid = ? LIMIT 1");
            $stmt_2->bind_param("s", $user_uuid);
            $stmt_2->execute();
            $result_2 = $stmt_2->get_result();

            if ($result_2->num_rows > 0) {
                $row_2 = $result_2->fetch_assoc();
                $email = decrypt($row_2['email'], $key);

                //send email to user

                $mail = new PHPMailer(true);
                try {
        
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'kenhainnovation@gmail.com';
                    $mail->Password = 'frnehuvdnrvennph';
                    $mail->SMTPSecure = 'tls';
        
                    $mail->Port = 587;
                    $mail->setFrom('noreply@kenhainnovation.com','KeNHAVate Portal');
        
                    $mail->addAddress($email);$mail->Subject = "Idea Update Alert";

                    // Set the Reply-To header to a non-replyable email address
                    $mail->addReplyTo('noreply@kenhainnovation.com', 'No Reply');

                    // Create a styled HTML email body
                    $mail->isHTML(true);
                    $mail->Body = '<html>
                    <head>
                        <style>
                            /* Your CSS styles here */
                            body {
                                font-family: Arial, sans-serif;
                                background-color: #f5f5f5;
                                margin: 0;
                                padding: 20px;
                            }
                            .im {
                                color: black !important;
                            }
                            .container {
                                max-width: 600px;
                                margin: 0 auto;
                                padding: 20px;
                                background: linear-gradient(to right, yellow, white 20%, white 80%, yellow);
                                border-radius: 10px;
                                box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
                            }
                            .header {
                                background-color: #d8d801ed;
                                color: #3b3b3bs;
                                padding: 10px;
                                text-align: center;
                            }
                            .content {    
                                padding: 20px;
                                flex-direction: column;
                                align-items: center;
                                border: 1px solid grey;
                                background-color: #dddddd;
                                font-size: 13px;
                            }
                        </style>
                    </head>
                    <body>
                        <div class="container">
                            <div class="header">
                                <h1>KeNHAVATE Portal</h1>
                            </div>
                            <div class="content" style="align-items: center; flex-direction: column !important; color: black;">
                                <h3 style="text-align: center;">Idea Update Alert</h3>
                                <p style="margin-bottom: 0px; font-size: 16px; color: black;">We are excited to inform you that your idea submission titled: <strong>' . $title . '</strong>, has successfully passed the committee review stage. The committee has recognized its viability, and it is now advancing to the final review stage by the board.</p>
                                <p style="margin-bottom: 0px; font-size: 16px; color: black;"><strong>Idea Description:</strong> ' . $brief_description . '</p>
                                <p style="margin-bottom: 0px; font-size: 16px; color: black;"><strong>Problem Statement:</strong> ' . $problem_statement . '</p>
                                <p style="margin-bottom: 0px; font-size: 16px; color: black;">Our board members will carefully evaluate your idea in this final stage of the review process to determine its feasibility and potential impact.</p>
                                <p style="margin-bottom: 0px; font-size: 16px; color: black;">Thank you for your innovative contribution and patience throughout this process. We look forward to the board&apos;s decision.</p>

                                <br>
                                <br>
                                Best regards,
                                <br>
                                KeNHAVATE Management Team
                                </p>
                                <p style="margin-bottom: 0px; bottom: 0px; position: relative;">This is an automated message do not reply.</p>
                            </div>
                            <p style="margin: 0px; text-align: center; background: #414141; color: white; border-radius: 0px 0px 5px 5px; padding: 8px;">Thank you for using the KeNHA Innovation Portal.</p>
                        </div>
                    </body>
                    </html>';
        
                    $mail->send();

                    echo json_encode(['message' => 'Successful']);header('Content-Type: application/json');
                    
        
                }
                catch (Exception $e) {
                    
                    echo json_encode(['message' => 'Error']);header('Content-Type: application/json');

                    exit;
                }
            }
            else {
                echo json_encode(['message' => 'Error']);header('Content-Type: application/json');
            }
        }
        else {
            echo json_encode(['message' => 'Error']);header('Content-Type: application/json');
        }
    }

    
    else if ($_GET['action'] === 'UpdateRejectBoardIdeaTable' || $_GET['action'] === 'UpdateApproveBoardIdeaTable' || $_GET['action'] === 'UpdatePendingBoardIdeaTable') {
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $itemsPerPage = isset($_GET['itemsPerPage']) ? $_GET['itemsPerPage'] : 10;
    
        // Calculate the offset based on the page and items per page
        $offset = ($page - 1) * $itemsPerPage;
        $stage = encrypt("board", $key);
        $status = encrypt("pending", $key);
    
        $stmt = $con->prepare("SELECT * FROM submitted_ideas WHERE stage = ? AND status = ? ORDER BY STR_TO_DATE(day_expert_committed, '%a, %M %d, %Y - %h:%i %p') DESC LIMIT ?, ?");
        $stmt->bind_param("ssii", $stage, $status, $offset, $itemsPerPage);
        $stmt->execute();
        $result = $stmt->get_result();
    
        // Fetch total rows count
        $totalRowsQuery = $con->prepare("SELECT COUNT(*) AS totalRows FROM submitted_ideas WHERE stage = ? AND status = ?");
        $totalRowsQuery->bind_param("ss", $stage, $status);
        $totalRowsQuery->execute();
        $totalRowsResult = $totalRowsQuery->get_result();
        $totalRowsData = $totalRowsResult->fetch_assoc();
        $totalRows = $totalRowsData['totalRows'];
    
        // Close total rows query
        $totalRowsQuery->close();
    
        // Fetch and store the results in an array
        $data = array();
        while ($row = $result->fetch_assoc()) {
    
            $db_expert_uuid = $row['expert_uuid'];
    
            $data['items'][] = array(
                'idea_uuid' => $row['idea_uuid'],
                'title' => $row['title'],
                'innovation_area' => $row['innovation_area'],
                'problem_statement' => $row['problem_statement'],
                'day_user_uploaded' => $row['day_user_uploaded'],
                'day_expert_committed' => $row['day_expert_committed'],
                'stage' => decrypt($row['stage'], $key),
                'status' => decrypt($row['status'], $key)
            );
        }
    
        // Calculate total pages
        $totalPages = ceil($totalRows / $itemsPerPage);
    
        // Add total pages and total rows to the data
        $data['totalPages'] = $totalPages;
        $data['totalRows'] = $totalRows;
    
        // Return the data as JSON
        header('Content-Type: application/json');
        echo json_encode($data);
    
        // Close the main query
        $stmt->close();
    }
    else if ($_GET['action'] === 'UpdateDB_RejectBoardIdeas') {
        // Retrieve the upload_id from the URL
        $idea_uuid = $_GET['upload_id'];
        $stage = encrypt('board', $key);
        $status = encrypt('rejected', $key);
        $date = date('D, F j, Y - h:i A');

        $stmt = $con->prepare("UPDATE submitted_ideas SET status = ?, stage = ?, day_board_approved = ? WHERE idea_uuid = ? LIMIT 1");
        $stmt->bind_param("ssss", $status, $stage, $date, $idea_uuid);
        $stmt->execute();
        
        // Retrieve the user_uuid from the updated row
        $stmt_1 = $con->prepare("SELECT * FROM submitted_ideas WHERE idea_uuid = ? LIMIT 1");
        $stmt_1->bind_param("s", $idea_uuid);
        $stmt_1->execute();
        $result_1 = $stmt_1->get_result();

        if ($result_1->num_rows > 0 && $stmt->affected_rows > 0) {
            $row_1 = $result_1->fetch_assoc();
            $user_uuid = $row_1['user_uuid'];
            $title = $row_1['title'];
            $brief_description = $row_1['brief_description'];
            $problem_statement = $row_1['problem_statement'];
            
            // Retrieve the user_uuid from the updated row
            $stmt_2 = $con->prepare("SELECT email FROM users_table WHERE uuid = ? LIMIT 1");
            $stmt_2->bind_param("s", $user_uuid);
            $stmt_2->execute();
            $result_2 = $stmt_2->get_result();

            if ($result_2->num_rows > 0) {
                $row_2 = $result_2->fetch_assoc();
                $email = decrypt($row_2['email'], $key);

                //send email to user

                $mail = new PHPMailer(true);
                try {
        
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'kenhainnovation@gmail.com';
                    $mail->Password = 'frnehuvdnrvennph';
                    $mail->SMTPSecure = 'tls';
        
                    $mail->Port = 587;
                    $mail->setFrom('noreply@kenhainnovation.com','KeNHAVate Portal');
        
                    $mail->addAddress($email);$mail->Subject = "Idea Update Alert";

                    // Set the Reply-To header to a non-replyable email address
                    $mail->addReplyTo('noreply@kenhainnovation.com', 'No Reply');

                    // Create a styled HTML email body
                    $mail->isHTML(true);
                    $mail->Body = '<html>
                    <head>
                        <style>
                            /* Your CSS styles here */
                            body {
                                font-family: Arial, sans-serif;
                                background-color: #f5f5f5;
                                margin: 0;
                                padding: 20px;
                            }
                            .im {
                                color: black !important;
                            }
                            .container {
                                max-width: 600px;
                                margin: 0 auto;
                                padding: 20px;
                                background: linear-gradient(to right, yellow, white 20%, white 80%, yellow);
                                border-radius: 10px;
                                box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
                            }
                            .header {
                                background-color: #d8d801ed;
                                color: #3b3b3bs;
                                padding: 10px;
                                text-align: center;
                            }
                            .content {    
                                padding: 20px;
                                flex-direction: column;
                                align-items: center;
                                border: 1px solid grey;
                                background-color: #dddddd;
                                font-size: 13px;
                            }
                        </style>
                    </head>
                    <body>
                        <div class="container">
                            <div class="header">
                                <h1>KeNHAVATE Portal</h1>
                            </div>
                            <div class="content" style="align-items: center; flex-direction: column !important; color: black;">
                                <h3 style="text-align: center;">Idea Update Alert</h3>
                                <p style="margin-bottom: 0px; font-size: 16px; color: black;">We regret to inform you that your idea submission titled: <strong>' . $title . '</strong>, has been reviewed by the board, and unfortunately, it has not been selected to proceed further since it did not meet our criteria for implementation.</p>
                                <p style="margin-bottom: 0px; font-size: 16px; color: black;"><strong>Idea Description:</strong> ' . $brief_description . '</p>
                                <p style="margin-bottom: 0px; font-size: 16px; color: black;"><strong>Problem Statement:</strong> ' . $problem_statement . '</p>
                                <p style="margin-bottom: 0px; font-size: 16px; color: black;">We appreciate your participation and the effort you put into submitting your idea. Although it wasn&apos;t selected this time, we encourage you to continue exploring new ideas and opportunities for innovation.</p>
                                <p style="margin-bottom: 0px; font-size: 16px; color: black;">Thank you for your valuable contribution.</p>

                                <br>
                                <br>
                                Best regards,
                                <br>
                                KeNHAVATE Management Team
                                </p>
                                <p style="margin-bottom: 0px; bottom: 0px; position: relative;">This is an automated message do not reply.</p>
                            </div>
                            <p style="margin: 0px; text-align: center; background: #414141; color: white; border-radius: 0px 0px 5px 5px; padding: 8px;">Thank you for using the KeNHA Innovation Portal.</p>
                        </div>
                    </body>
                    </html>';
        
                    $mail->send();

                    echo json_encode(['message' => 'Successful']);header('Content-Type: application/json');
                    
        
                }
                catch (Exception $e) {
                    
                    echo json_encode(['message' => 'Error']);header('Content-Type: application/json');

                    exit;
                }
            }
            else {
                echo json_encode(['message' => 'Error']);header('Content-Type: application/json');
            }
        }
        else {
            echo json_encode(['message' => 'Error']);header('Content-Type: application/json');
        }
    }

    else if ($_GET['action'] === 'UpdateDB_ApproveBoardIdeas') {
        // Retrieve the upload_id from the URL
        $idea_uuid = $_GET['upload_id'];
        $stage = encrypt('board', $key);
        $status = encrypt('approved', $key);
        $date = date('D, F j, Y - h:i A');

        $stmt = $con->prepare("UPDATE submitted_ideas SET status = ?, stage = ?, day_board_approved = ? WHERE idea_uuid = ? LIMIT 1");
        $stmt->bind_param("ssss", $status, $stage, $date, $idea_uuid);
        $stmt->execute();
        
        // Retrieve the user_uuid from the updated row
        $stmt_1 = $con->prepare("SELECT * FROM submitted_ideas WHERE idea_uuid = ? LIMIT 1");
        $stmt_1->bind_param("s", $idea_uuid);
        $stmt_1->execute();
        $result_1 = $stmt_1->get_result();

        if ($result_1->num_rows > 0 && $stmt->affected_rows > 0) {
            $row_1 = $result_1->fetch_assoc();
            $user_uuid = $row_1['user_uuid'];
            $title = $row_1['title'];
            $brief_description = $row_1['brief_description'];
            $problem_statement = $row_1['problem_statement'];
            
            // Retrieve the user_uuid from the updated row
            $stmt_2 = $con->prepare("SELECT email FROM users_table WHERE uuid = ? LIMIT 1");
            $stmt_2->bind_param("s", $user_uuid);
            $stmt_2->execute();
            $result_2 = $stmt_2->get_result();

            if ($result_2->num_rows > 0) {
                $row_2 = $result_2->fetch_assoc();
                $email = decrypt($row_2['email'], $key);

                //send email to user

                $mail = new PHPMailer(true);
                try {
        
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'kenhainnovation@gmail.com';
                    $mail->Password = 'frnehuvdnrvennph';
                    $mail->SMTPSecure = 'tls';
        
                    $mail->Port = 587;
                    $mail->setFrom('noreply@kenhainnovation.com','KeNHAVate Portal');
        
                    $mail->addAddress($email);$mail->Subject = "Idea Update Alert";

                    // Set the Reply-To header to a non-replyable email address
                    $mail->addReplyTo('noreply@kenhainnovation.com', 'No Reply');

                    // Create a styled HTML email body
                    $mail->isHTML(true);
                    $mail->Body = '<html>
                    <head>
                        <style>
                            /* Your CSS styles here */
                            body {
                                font-family: Arial, sans-serif;
                                background-color: #f5f5f5;
                                margin: 0;
                                padding: 20px;
                            }
                            .im {
                                color: black !important;
                            }
                            .container {
                                max-width: 600px;
                                margin: 0 auto;
                                padding: 20px;
                                background: linear-gradient(to right, yellow, white 20%, white 80%, yellow);
                                border-radius: 10px;
                                box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
                            }
                            .header {
                                background-color: #d8d801ed;
                                color: #3b3b3bs;
                                padding: 10px;
                                text-align: center;
                            }
                            .content {    
                                padding: 20px;
                                flex-direction: column;
                                align-items: center;
                                border: 1px solid grey;
                                background-color: #dddddd;
                                font-size: 13px;
                            }
                        </style>
                    </head>
                    <body>
                        <div class="container">
                            <div class="header">
                                <h1>KeNHAVATE Portal</h1>
                            </div>
                            <div class="content" style="align-items: center; flex-direction: column !important; color: black;">
                                <h3 style="text-align: center;">Idea Update Alert</h3>
                                <p style="margin-bottom: 0px; font-size: 16px; color: black;">Congratulations! We are delighted to inform you that your idea submission titled: <strong>' . $title . '</strong>, has been reviewed and approved by the board.</p>
                                <p style="margin-bottom: 0px; font-size: 16px; color: black;"><strong>Idea Description:</strong> ' . $brief_description . '</p>
                                <p style="margin-bottom: 0px; font-size: 16px; color: black;"><strong>Problem Statement:</strong> ' . $problem_statement . '</p>
                                <p style="margin-bottom: 0px; font-size: 16px; color: black;">Thank you for your valuable input and dedication to innovation. You can expect to be contacted soon via email or phone to discuss the next steps for implementing your idea.</p>
                                <br>
                                <br>
                                Best regards,
                                <br>
                                KeNHAVATE Management Team
                                </p>
                                <p style="margin-bottom: 0px; bottom: 0px; position: relative;">This is an automated message do not reply.</p>
                            </div>
                            <p style="margin: 0px; text-align: center; background: #414141; color: white; border-radius: 0px 0px 5px 5px; padding: 8px;">Thank you for using the KeNHA Innovation Portal.</p>
                        </div>
                    </body>
                    </html>';
        
                    $mail->send();

                    echo json_encode(['message' => 'Successful']);header('Content-Type: application/json');
                    
        
                }
                catch (Exception $e) {
                    
                    echo json_encode(['message' => 'Error']);header('Content-Type: application/json');

                    exit;
                }
            }
            else {
                echo json_encode(['message' => 'Error']);header('Content-Type: application/json');
            }
        }
        else {
            echo json_encode(['message' => 'Error']);header('Content-Type: application/json');
        }
    }
    else if ($_GET['action'] === 'UpdateApprovedBoardIdeaTable') {
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $itemsPerPage = isset($_GET['itemsPerPage']) ? $_GET['itemsPerPage'] : 10;
    
        // Calculate the offset based on the page and items per page
        $offset = ($page - 1) * $itemsPerPage;
        $stage = encrypt("board", $key);
        $status = encrypt("approved", $key);
    
        $stmt = $con->prepare("SELECT * FROM submitted_ideas WHERE stage = ? AND status = ? ORDER BY STR_TO_DATE(day_board_approved, '%a, %M %d, %Y - %h:%i %p') DESC LIMIT ?, ?");
        $stmt->bind_param("ssii", $stage, $status, $offset, $itemsPerPage);
        $stmt->execute();
        $result = $stmt->get_result();
    
        // Fetch total rows count
        $totalRowsQuery = $con->prepare("SELECT COUNT(*) AS totalRows FROM submitted_ideas WHERE stage = ? AND status = ?");
        $totalRowsQuery->bind_param("ss", $stage, $status);
        $totalRowsQuery->execute();
        $totalRowsResult = $totalRowsQuery->get_result();
        $totalRowsData = $totalRowsResult->fetch_assoc();
        $totalRows = $totalRowsData['totalRows'];
    
        // Close total rows query
        $totalRowsQuery->close();
    
        // Fetch and store the results in an array
        $data = array();
        while ($row = $result->fetch_assoc()) {
    
            $db_expert_uuid = $row['expert_uuid'];
    
            $data['items'][] = array(
                'idea_uuid' => $row['idea_uuid'],
                'title' => $row['title'],
                'innovation_area' => $row['innovation_area'],
                'problem_statement' => $row['problem_statement'],
                'day_user_uploaded' => $row['day_user_uploaded'],
                'day_expert_committed' => $row['day_expert_committed'],
                'stage' => decrypt($row['stage'], $key),
                'status' => decrypt($row['status'], $key)
            );
        }
    
        // Calculate total pages
        $totalPages = ceil($totalRows / $itemsPerPage);
    
        // Add total pages and total rows to the data
        $data['totalPages'] = $totalPages;
        $data['totalRows'] = $totalRows;
    
        // Return the data as JSON
        header('Content-Type: application/json');
        echo json_encode($data);
    
        // Close the main query
        $stmt->close();
    }
    else if ($_GET['action'] === 'UpdateRejectedBoardIdeaTable') {
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $itemsPerPage = isset($_GET['itemsPerPage']) ? $_GET['itemsPerPage'] : 10;
    
        // Calculate the offset based on the page and items per page
        $offset = ($page - 1) * $itemsPerPage;
        $stage = encrypt("board", $key);
        $status = encrypt("rejected", $key);
    
        $stmt = $con->prepare("SELECT * FROM submitted_ideas WHERE stage = ? AND status = ? ORDER BY STR_TO_DATE(day_board_approved, '%a, %M %d, %Y - %h:%i %p') DESC LIMIT ?, ?");
        $stmt->bind_param("ssii", $stage, $status, $offset, $itemsPerPage);
        $stmt->execute();
        $result = $stmt->get_result();
    
        // Fetch total rows count
        $totalRowsQuery = $con->prepare("SELECT COUNT(*) AS totalRows FROM submitted_ideas WHERE stage = ? AND status = ?");
        $totalRowsQuery->bind_param("ss", $stage, $status);
        $totalRowsQuery->execute();
        $totalRowsResult = $totalRowsQuery->get_result();
        $totalRowsData = $totalRowsResult->fetch_assoc();
        $totalRows = $totalRowsData['totalRows'];
    
        // Close total rows query
        $totalRowsQuery->close();
    
        // Fetch and store the results in an array
        $data = array();
        while ($row = $result->fetch_assoc()) {
    
            $db_expert_uuid = $row['expert_uuid'];
    
            $data['items'][] = array(
                'idea_uuid' => $row['idea_uuid'],
                'title' => $row['title'],
                'innovation_area' => $row['innovation_area'],
                'problem_statement' => $row['problem_statement'],
                'day_user_uploaded' => $row['day_user_uploaded'],
                'day_expert_committed' => $row['day_expert_committed'],
                'stage' => decrypt($row['stage'], $key),
                'status' => decrypt($row['status'], $key)
            );
        }
    
        // Calculate total pages
        $totalPages = ceil($totalRows / $itemsPerPage);
    
        // Add total pages and total rows to the data
        $data['totalPages'] = $totalPages;
        $data['totalRows'] = $totalRows;
    
        // Return the data as JSON
        header('Content-Type: application/json');
        echo json_encode($data);
    
        // Close the main query
        $stmt->close();
    }



    else if ($_GET['action'] === 'createNewCommittee') {
        // Retrieve form data
        $committeName = $_POST['committeName'] . '_committee';
        $tableName = $committeName;
        $creationDate = date('Y-m-d');
        $expiryDate = $_POST['expiryDate'];
        $chairperson = $_POST['chairperson'];
        $secretary = $_POST['secretary'];

        $enc_chairperson = encrypt($chairperson, $key);
        $enc_secretary = encrypt($secretary, $key);

        if ($creationDate >= $expiryDate) {
            echo "Error: Your expiry date is not valid";
        }
        else {
            $status = 'active';
            try {
                $stmt = $con->prepare("SELECT * FROM committee_table_name WHERE table_name = ?");
                $stmt->bind_param("s", $tableName);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result->num_rows > 0) {
                    echo "Error: A committee with a similar name already exists!";
                }
                else {
                    try {
                        $stmt_1 = $con->prepare("INSERT INTO committee_table_name (table_name, creation_date, expiry_date, status, chairperson, secretary) VALUES (?, ?, ?, ?, ?, ?)");
                        $stmt_1->bind_param("ssssss", $tableName, $creationDate, $expiryDate, $status, $enc_chairperson, $enc_secretary);
                        $stmt_1->execute();
            
                        try {
                            $stmt_2 = $con->prepare("CREATE TABLE {$committeName} (
                                id INT AUTO_INCREMENT PRIMARY KEY,
                                uuid VARCHAR(255) UNIQUE,
                                name VARCHAR(255),
                                position VARCHAR(255),
                                tel VARCHAR(255) UNIQUE,
                                email VARCHAR(255) UNIQUE,
                                rank VARCHAR(255) ,
                                gender VARCHAR(255),
                                timestamp TIMESTAMP
                            )");
                            $stmt_2->execute();
                            $stmt_2->close();
                            echo "Success: $committeName committee has been activated, please add your members";
            
                        } catch (PDOException $e) {
                            echo "Error: Inserting error";
                        }
                        $stmt_1->close();
            
                    } catch (PDOException $e) {
                        echo "Error: creation error";
                    }
                }
                $stmt->close();

            } catch (PDOException $e) {
                echo "Error: An error occured";
            }
        }
        


    }

    else if ($_GET['action'] === 'FetchNewCommitteeTable') {
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $itemsPerPage = isset($_GET['itemsPerPage']) ? $_GET['itemsPerPage'] : 10;
    
        // Calculate the offset based on the page and items per page
        $offset = ($page - 1) * $itemsPerPage;

        $stmt = $con->prepare("SELECT * FROM committee_table_name ORDER BY id DESC LIMIT 1");
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            
            $id = $row['id'];
            $table_name = $row['table_name'];
            $expiry_date = $row['expiry_date'];
            $status = $row['status'];


            $stmt_1 = $con->prepare("SELECT * FROM $table_name");
            $stmt_1->execute();
            $result_1 = $stmt_1->get_result();
        
            // Fetch total rows count
            $totalRowsQuery = $con->prepare("SELECT COUNT(*) AS totalRows FROM $table_name");
            $totalRowsQuery->execute();
            $totalRowsResult = $totalRowsQuery->get_result();
            $totalRowsData = $totalRowsResult->fetch_assoc();
            $totalRows = $totalRowsData['totalRows'];
        
            // Close total rows query
            $totalRowsQuery->close();
        
            // Fetch and store the results in an array
            $data = array();
            while ($row_1 = $result_1->fetch_assoc()) {
        
                $data['items'][] = array(
                    'uuid' => $row_1['uuid'],
                    'name' => $row_1['name'],
                    'position' => $row_1['position'],
                    'tel' => $row_1['tel'],
                    'email' => $row_1['email'],
                    'rank' => $row_1['rank'],
                    'gender' => $row_1['gender']
                );
            }
        
            // Calculate total pages
            $totalPages = ceil($totalRows / $itemsPerPage);
        
            // Add total pages and total rows to the data
            $data['totalPages'] = $totalPages;
            $data['totalRows'] = $totalRows;
            $data['expiry_date'] = $expiry_date;
            $data['status'] = $status;
            $data['table_name'] = $table_name;
        
            // Return the data as JSON
            header('Content-Type: application/json');
            echo json_encode($data);
        
            // Close the main query
            $stmt->close();
        }
        else {
            // Handle the case when there is no data to return
            header('Content-Type: application/json');
            echo json_encode(['message' => 'No table returned', 'data' => []]); // Encode your message array as JSON and echo it
        }
    }

    else if ($_GET['action'] === 'addNewMember_1') {
        // Retrieve the table_name from the URL
        $table_name = $_GET['table_name'];
    
        // Initialize the UUID variable
        $uuid = null;
    
        // Loop until a unique UUID is generated
        do {
            $uuid = generateRandomUUID($length = 10, $con);
            $enc_uuid = encrypt($uuid, $key);
    
            // Check if the UUID already exists in the database
            $stmt = $con->prepare("SELECT * FROM $table_name WHERE uuid = ? LIMIT 1");
            $stmt->bind_param("s", $enc_uuid);
            $stmt->execute();
            $result = $stmt->get_result();
    
            // Close the result set
            $stmt->close();
    
            // If the UUID exists, generate a new one
        } while ($result->num_rows > 0);
    
        // Retrieve form data
        $name = $_POST['name'];
        $position = $_POST['position'];
        $mobile = $_POST['mobile'];
        $rank = $_POST['rank'];
        $email = $_POST['email'];
        $gender = $_POST['gender'];
    
        try {
            // Insert data into the database
            $stmt = $con->prepare("INSERT INTO $table_name (uuid, name, position, tel, email, rank, gender) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssss", $uuid, $name, $position, $mobile, $email, $rank, $gender);
            $stmt->execute();
    
            // Check if the insertion was successful
            if ($stmt->affected_rows > 0) {
                echo "Success: $name has been added to the $table_name and has been appointed $position position.";
                echo "Add another entry!";
            } else {
                echo "Error: Failed to add $name into the database.";
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    
    else if ($_GET['action'] === 'ListExistingCommittee') {
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $itemsPerPage = isset($_GET['itemsPerPage']) ? $_GET['itemsPerPage'] : 12;
    
        // Calculate the offset based on the page and items per page
        $offset = ($page - 1) * $itemsPerPage;
    
        $stmt = $con->prepare("SELECT * FROM committee_table_name ORDER BY creation_date LIMIT ?, ?");
        $stmt->bind_param("ii", $offset, $itemsPerPage);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $data = array();
    
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data['items'][] = array(            
                    'id' => $row['id'],
                    'table_name' => $row['table_name'],
                    'creation_date' => $row['creation_date'],
                    'expiry_date' => $row['expiry_date'],
                    'status' => $row['status'],
                    'offset' => $offset,
                    'itemsPerPage' => $itemsPerPage,
                );
            }
    
            // Fetch total rows count
            $totalRowsQuery = $con->query("SELECT COUNT(*) AS totalRows FROM committee_table_name");
            $totalRowsData = $totalRowsQuery->fetch_assoc();
            $totalRows = $totalRowsData['totalRows'];
    
            // Close total rows query
            $totalRowsQuery->close();
    
            // Calculate total pages
            $totalPages = ceil($totalRows / $itemsPerPage);
    
            // Add total pages and total rows to the data
            $data['totalPages'] = $totalPages;
            $data['totalRows'] = $totalRows;
    
            // Return the data as JSON
            header('Content-Type: application/json');
            echo json_encode($data);
    
            // Close the main query
            $stmt->close();
        } else {
            // Handle the case when there is no data to return
            header('Content-Type: application/json');
            echo json_encode(['message' => 'No table returned', 'data' => []]); // Encode your message array as JSON and echo it
        }
    }
    else if ($_GET['action'] === 'FetchClickedCommitteeTable') {
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $itemsPerPage = isset($_GET['itemsPerPage']) ? $_GET['itemsPerPage'] : 10;
        $committee_name = $_GET['upload_id'];
    
        // Calculate the offset based on the page and items per page
        $offset = ($page - 1) * $itemsPerPage;
    
        $stmt = $con->prepare("SELECT * FROM $committee_name LIMIT ?, ?");
        $stmt->bind_param("ii", $offset, $itemsPerPage);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $stmt_1 = $con->prepare("SELECT * FROM committee_table_name WHERE table_name = ? LIMIT 1");
        $stmt_1->bind_param("s", $committee_name);
        $stmt_1->execute();
        $result_1 = $stmt_1->get_result();
    
        // Fetch and store the results in an array
        $data = array();
        $data['items'] = array();
        
        if ($result_1->num_rows > 0) {
            $row_1 = $result_1->fetch_assoc();
            $status = $row_1['status'];
            while ($row = $result->fetch_assoc()) {
                $data['items'][] = array(
                    'uuid' => $row['uuid'],
                    'name' => $row['name'],
                    'position' => $row['position'],
                    'tel' => $row['tel'],
                    'email' => $row['email'],
                    'rank' => $row['rank'],
                    'gender' => $row['gender'],
                    'table_name' => $committee_name
                );
            }
        }
        else{
            echo json_encode(['message' => 'No table returned', 'data' => []]);
        }
    
        // Fetch total rows count
        $totalRowsQuery = $con->prepare("SELECT COUNT(*) AS totalRows FROM $committee_name");
        $totalRowsQuery->execute();
        $totalRowsResult = $totalRowsQuery->get_result();
        $totalRowsData = $totalRowsResult->fetch_assoc();
        $totalRows = $totalRowsData['totalRows'];
    
        // Close total rows query
        $totalRowsQuery->close();
    
        // Calculate total pages
        $totalPages = ceil($totalRows / $itemsPerPage);
    
        // Add total pages and total rows to the data
        $data['totalPages'] = $totalPages;
        $data['totalRows'] = $totalRows;
        $data['status'] = $status;
    
        // Return the data as JSON
        header('Content-Type: application/json');
        echo json_encode($data);
    
        // Close the main query
        $stmt->close();
    }
    else if ($_GET['action'] === 'RemoveCommitteeMember') {
        // Check if table_name and member_uuid are set and retrieve their values
        $table_name = isset($_GET['table_name']) ? $_GET['table_name'] : null;
        $member_uuid = isset($_GET['member_uuid']) ? $_GET['member_uuid'] : null;
    
        if ($table_name && $member_uuid) {
            // Prepare the SQL statement
            $stmt = $con->prepare("DELETE FROM $table_name WHERE uuid = ? LIMIT 1");
    
            // Check if the statement was prepared successfully
            if ($stmt) {
                $stmt->bind_param("s", $member_uuid);
                $stmt->execute();
    
                // Check if any rows were affected (i.e., a row was deleted)
                if ($stmt->affected_rows > 0) {
                    // Success response
                    header('Content-Type: application/json');
                    echo json_encode(['success' => true, 'message' => 'Member removed successfully']);
                } else {
                    // No rows were affected
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => 'No member found with the given UUID']);
                }
    
                // Close the statement
                $stmt->close();
            } else {
                // Error in preparing the statement
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Failed to prepare the statement']);
            }
        } else {
            // Missing table_name or member_uuid
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Invalid request parameters']);
        }
    }
    else if ($_GET['action'] === 'UpdateCommitteeMember') {
        $member_uuid = $_POST['member_uuid'];
        $member_name = $_POST['name'];
        $member_position = $_POST['position'];
        $member_tel = $_POST['mobile'];
        $member_email = $_POST['email'];
        $member_rank = $_POST['rank'];
        $tableName = $_POST['table_name'];

        // Assuming $con is your database connection
        $stmt = $con->prepare("UPDATE $tableName SET name=?, position=?, tel=?, email=?, rank=? WHERE uuid=?");
        $stmt->bind_param("ssssss", $member_name, $member_position, $member_tel, $member_email, $member_rank, $member_uuid);

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Member details updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update member details']);
        }

        $stmt->close();
    }


    

    else if ($_GET['action'] === 'changeTableStatus') {
        // Retrieve the table_name from the URL
        $table_name = $_GET['table_name'];
        $status = $_GET['status'];
    
        try {
            // Insert data into the database
            $stmt = $con->prepare("UPDATE committee_table_name SET status = ? WHERE table_name = ? LIMIT 1");
            $stmt->bind_param("ss", $status, $table_name);
            $stmt->execute();
    
            // Check if the insertion was successful
            if ($stmt->affected_rows > 0) {
                echo "Success: $table_name  is now $status.";
            } else {
                echo "Error: Failed to update status of $table_name.";
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    
    











    
    
    
    else {
        echo json_encode(['message' => 'No table returns']);
    }
?>