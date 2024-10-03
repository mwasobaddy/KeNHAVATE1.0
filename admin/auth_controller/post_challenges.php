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

    if ($_POST['action'] === 'post_challenge') {
        $challenge_uuid = generate_challenge_uuid($con);
        $db_en_staff_uuid = $_SESSION['uuid'];
        

        // Retrieve and sanitize input values
        $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
        $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
        $date = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING);
        $upload_challenge = $_FILES['upload_challenge']['name'];
        $upload_challenge_type = $_FILES['upload_challenge']['type'];

        $allowedFileTypes = ['image/jpeg', 'image/png', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];

        if (!in_array($upload_challenge_type, $allowedFileTypes)) {
            echo 'Invalid file type. Please upload an image, PDF, or Word document.';
            exit;
        }
        else{
            $encryptedchallenge_uuid = encrypt($challenge_uuid, $key);
            $encryptedstaff_uuid = $db_en_staff_uuid;
            $encryptedtitle = $title;
            $encrypteddescription = $description;
            $encrypteddate = date('D, F j, Y - h:i A', strtotime($date));
            $uploaddate = date('D, F j, Y - h:i A');

            
            //allow staff to upload challenge to the database
            $stmt = $con->prepare("INSERT INTO posted_challenges (author_uuid, challenge_uuid, title, description, deadline, upload_name, day_uploaded) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssss", $encryptedstaff_uuid, $encryptedchallenge_uuid, $encryptedtitle, $encrypteddescription, $encrypteddate, $upload_challenge, $uploaddate);

            if ($stmt->execute()) {
                $targetDir = "../../uploaded_challenges/";
                $uploadSuccess = move_uploaded_file($_FILES['upload_challenge']['tmp_name'], $targetDir . $upload_challenge);

                if ($uploadSuccess) {
                    echo 'Your challenge has successfully been posted!';
                } else {
                    echo 'An error occurred during file upload.';
                }
                exit;
            }
            else {
                echo 'An error occurred during upload.';
                exit;
            }

            $stmt->close();
            exit;
        }

        
    }
    else {
        echo 'No Challenges posted';
    }
?>