<?php
    session_start();

    include("../auth_controller/requirement.php");
    
    header('Content-Type: application/json');

    $key = 'my-KeNHAsecret-passkey';

    if ($_GET['action'] === 'fetchPostedChallenges') {
        $challenges = array();
        $stmt = $con->prepare("SELECT * FROM posted_challenges ORDER BY STR_TO_DATE(day_uploaded, '%a, %M %d, %Y - %h:%i %p') DESC");
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $challenges[] = array(
                    'challengeId' => $row['challenge_uuid'],
                    'title' => htmlspecialchars($row['title']),
                    'deadline' => htmlspecialchars($row['deadline']),
                );
            }
            echo json_encode($challenges);
        } else {
            echo json_encode(array('message' => 'No challenge currently'));
        }
        $stmt->close();
    }

    else if ($_GET['action'] === 'fetchChallengeDetails') {
        $challengeId = $_GET['challengeId'];
        $path = '../KeNHAVATE/uploaded_challenges/';

        $challengeDetails = array();
        $stmt = $con->prepare("SELECT * FROM posted_challenges WHERE challenge_uuid = ?");
        $stmt->bind_param("s", $challengeId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $challengeDetails[] = array(
                    'challengeId' => $row['challenge_uuid'],
                    'challenge_uuid' => htmlspecialchars($row['challenge_uuid']),
                    'title' => htmlspecialchars($row['title']),
                    'description' => htmlspecialchars($row['description']),
                    'deadline' => htmlspecialchars($row['deadline']),
                    'day_uploaded' => htmlspecialchars($row['day_uploaded']),
                    'upload_name' => htmlspecialchars($row['upload_name']),
                    'download_path' => htmlspecialchars($path . $row['upload_name'])
                );
            }
            echo json_encode($challengeDetails);
        } else {
            echo json_encode(array('message' => 'No challenge currently'));
        }
        $stmt->close();
    }

    else if (isset($_POST['action']) && $_POST['action'] === 'replyChallenge') {
        try {
            // Retrieve data from the FormData object
            $challengeUuid = $_POST['challengeUuid'];
            $solutionDesc = $_POST['solutionDesc'];
            $motivationDesc = $_POST['motivationDesc'];
            $costEstimateDesc = $_POST['costEstimateDesc'];
            $declaration = $_POST['declaration'];
            $day_challenge_replied = date('D, F j, Y - h:i A');
            $upload_id = generateChallengeID($length = 10, $con);

            $encryptedupload_id = encrypt($upload_id, $key);
            $encrypteddeclaration = encryptData($declaration, $key);
        
            // Process the file upload
            $file = $_FILES['pdfFile'];
            $uploadDirectory = "../uploaded_challenges/";
            
            
            $stmt = $con->prepare("INSERT INTO replied_challenges (user_uuid, challenge_uuid, desc_solution, desc_motivation, desc_cost_estimate, declaration, time_uploaded, upload_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssss", $_SESSION['uuid'], $challengeUuid, $solutionDesc, $motivationDesc, $costEstimateDesc, $encrypteddeclaration, $day_challenge_replied, $encryptedupload_id);

            if ($stmt->execute()) {
        
                // Check if the file was uploaded successfully
                if ($file['error'] === UPLOAD_ERR_OK) {
                    $uploadedFilePath = $uploadDirectory . $encryptedupload_id . ".pdf";
                    move_uploaded_file($file['tmp_name'], $uploadedFilePath);
                    
                    echo json_encode(['success' => true, 'message' => 'Upload Successful']);
                } else {
                    // Respond with an error message
                    echo json_encode(['success' => false, 'message' => 'Upload Failed']);
                }

            } else {
                // Respond with an error message
                echo json_encode(['success' => false, 'message' => 'Data Upload Failed']);
            }
        } catch (Exception $e) {
            // Respond with an error message in case of an exception
            echo json_encode(['success' => false, 'message' => 'An error occurred']);
        }
    } 
    
    else if ($_GET['action'] === 'UpdateChallenges') {

        $stmt3 = $con->prepare("SELECT * FROM posted_challenges ORDER BY STR_TO_DATE(day_uploaded, '%a, %M %d, %Y - %h:%i %p') DESC LIMIT 1");
        $stmt3->execute();
        $result3 = $stmt3->get_result();
    
        $challenge_array = array();
    
        if ($result3->num_rows > 0) {
            while ($row3 = $result3->fetch_assoc()) {
                $title = $row3['title'];
                $description = $row3['description'];
                $deadline = $row3['deadline'];                
    
                $challenge_array[] = array(
                    'title' => $title,
                    'description' => $description,
                    'deadline' => $deadline
                );
            }
    
            header('Content-Type: application/json');
            echo json_encode($challenge_array);
        }
        else {
            echo json_encode(['message' => 'No table returned', 'data' => []]);
        }
    }
    
    else if ($_GET['action'] === 'UpdatePast5Challenges') {

        $stmt3 = $con->prepare("SELECT * FROM posted_challenges ORDER BY STR_TO_DATE(day_uploaded, '%a, %M %d, %Y - %h:%i %p') DESC LIMIT 5");
        $stmt3->execute();
        $result3 = $stmt3->get_result();
    
        $challenge_array = array();
    
        if ($result3->num_rows > 0) {
            while ($row3 = $result3->fetch_assoc()) {
                $title = $row3['title'];
                $description = $row3['description'];
                $deadline = $row3['deadline'];                
    
                $challenge_array[] = array(
                    'title' => $title
                );
            }
    
            header('Content-Type: application/json');
            echo json_encode($challenge_array);
        }
        else {
            echo json_encode(['message' => 'No table returned', 'data' => []]);
        }
    }

    else {
        // Respond with an error message for non-POST requests
        echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    }
    
    

?>