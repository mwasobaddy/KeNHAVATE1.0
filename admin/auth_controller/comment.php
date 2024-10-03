<?php
    session_start();

    include("../requirement.php");

    if (isset($_POST['submit_comment'])) {
        // Get the submitted data from the POST request
        $ideaId = $_POST['idea_id'];
        $commentText = $_POST['comment'];

        // Validate and sanitize the data (You can add your validation logic here)

        // Check if the record with the specified upload_id exists
        $stmt_check = $con->prepare("SELECT * FROM submitted_ideas WHERE upload_id = ? LIMIT 1");
        $stmt_check->bind_param("s", $ideaId);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if (mysqli_num_rows($result_check) == 0) {
            // The record does not exist
            $response = array('status' => 'error', 'message' => 'Idea not found.');
            echo json_encode($response);
        } else {
            // The record exists, update the comment
            $stmt_update = $con->prepare("UPDATE submitted_ideas SET expert_comment = ? WHERE upload_id = ?");
            $stmt_update->bind_param("ss", $commentText, $ideaId);

            if ($stmt_update->execute()) {
                $response = array('status' => 'success', 'message' => 'Comment added successfully.');
                echo json_encode($response);
            } else {
                // Error occurred while updating the comment
                $response = array('status' => 'error', 'message' => 'Failed to add comment.');
                echo json_encode($response);
            }

            // Close the prepared statement for updating
            $stmt_update->close();
        }

        // Close the prepared statement for checking
        $stmt_check->close();
    } else {
        // Handle cases where 'submit_comment' is not set
        $response = array('status' => 'error', 'message' => 'Invalid request.');
        echo json_encode($response);
        header("Location: /KeNHAVATE/error_file");
    }
?>
