<?php
session_start();

include("../auth_controller/requirement.php");

// Check if the user is logged in
if (isset($_SESSION['uuid'])) {
    $user_uuid = $_SESSION['uuid'];

    $key = 'my-KeNHAsecret-passkey';

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $date = date('D, F j, Y - h:i A');

        // Sanitize and validate input
        $challengeId = filter_input(INPUT_POST, 'challengeId', FILTER_SANITIZE_STRING);
        $newChallengeId = filter_input(INPUT_POST, 'newChallengeId', FILTER_SANITIZE_STRING);
        $desc_sln = filter_input(INPUT_POST, "solution", FILTER_SANITIZE_STRING);
        $desc_mtvn = filter_input(INPUT_POST, "motivation", FILTER_SANITIZE_STRING);
        $desc_cost = filter_input(INPUT_POST, "cost", FILTER_SANITIZE_STRING);
        $declaration = filter_input(INPUT_POST, "termsCheck", FILTER_SANITIZE_STRING);
        $time_uploaded = $date; // You can directly use the formatted date
        $upload_id = generateChallengeID($length = 10, $con);

        try {
            // Handle file upload
            if (isset($_FILES['supportDocs']) && $_FILES['supportDocs']['error'] === UPLOAD_ERR_OK) {
                $supportDocsFile = $_FILES['supportDocs'];
                $supportDocsName = $supportDocsFile['name'];

                // Extract filename and extension
                $filename = pathinfo($supportDocsName, PATHINFO_FILENAME);
                $extension = pathinfo($supportDocsName, PATHINFO_EXTENSION);

                // Encrypt sensitive data
                $enc_declaration = encryptData($declaration, $key);
                $enc_upload_id = encryptData($upload_id, $key);
                $enc_time_uploaded = encryptData($time_uploaded, $key);

                // Move the uploaded file to a designated folder (e.g., "uploads/")
                $uploadDir = "../uploaded_challenges/";
                $uploadPath = $uploadDir . $enc_upload_id . '.' . $extension;

                if (move_uploaded_file($supportDocsFile["tmp_name"], $uploadPath)) {
                    // Insert form data and file path into the database
                    // (use prepared statements for database queries)
                    // Replace with your database connection code and SQL query

                    $stmt = $con->prepare("INSERT INTO replied_challenges (user_uuid, challenge_uuid, desc_solution, desc_motivation, desc_cost_estimate, declaration, time_uploaded, upload_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("ssssssss", $user_uuid, $newChallengeId, $desc_sln, $desc_mtvn, $desc_cost, $enc_declaration, $enc_time_uploaded, $enc_upload_id);

                    if ($stmt->execute()) {
                        echo "Success: Your response to the challenge was successfully uploaded.";
                    } else {
                        echo "Error: Your response to the challenge was not successfully uploaded.";
                    }

                    $stmt->close();
                } else {
                    throw new Exception("Failed to move the uploaded file.");
                }
            } else {
                echo "Error: No file uploaded or an error occurred during upload.";
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Error: Invalid request.";
    }
} else {
    echo "Error: An error occurred. Please log in again.";
}
?>
