<?php

    session_start();

    include("../../auth_controller/requirement.php");
    include("./requirement.php");

    $key = 'my-KeNHAsecret-passkey';

    $db_en_ses_uuid = $_SESSION['uuid'];
    $day_expert_committed = "not committed";
    $encryptedday_expert_committed = $day_expert_committed;
        
    $numResults_null = '0';
    $idea_uuid_null = 'not assigned';
    $title_null = 'no title';
    $innovation_area_null = 'no area innovation available';
    $brief_description_null = 'no description available';
    $problem_statement_null = 'no problem statement available';
    $proposed_solution_null = 'no proposed avilable';
    $cost_benefit_analysis_null = 'no cost benefit available';
    $original_file_name_null = 'unavailable origin file name';
    $day_user_uploaded_null = 'no user found';
    $expert_uuid_null = 'not assigned';
    $day_expert_appointed_null = 'not assigned';
    $day_expert_committed_null = 'not assigned';
    $expert_comment_null = 'no comment';
    $encryptedday_expert_committed_null = 'not assigned';


    if ($_GET['action'] === 'totalIdeas') {
        
        $stmt = $con->prepare("SELECT * FROM submitted_ideas WHERE expert_uuid = ? ORDER BY timestamp DESC");
        $stmt->bind_param("s", $db_en_ses_uuid);
        $stmt->execute();
        $result = $stmt->get_result();
    
        $numResults = $result->num_rows;
    
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Decrypt the encrypted fields
                $idea_uuid = decryptData($row['idea_uuid'], $key);
                $title = $row['title'];
                $innovation_area = $row['innovation_area'];
                $brief_description = $row['brief_description'];
                $problem_statement = $row['problem_statement'];
                $proposed_solution = $row['proposed_solution'];
                $cost_benefit_analysis = $row['cost_benefit_analysis'];
                $original_file_name = decryptData($row['original_file_name'], $key);
                $upload_id = decrypt($row['upload_id'], $key);
                $day_user_uploaded = decryptData($row['day_user_uploaded'], $key);
                $expert_uuid = decrypt($row['expert_uuid'], $key);
                $day_expert_appointed = decryptData($row['day_expert_appointed'], $key);
                $day_expert_committed = decrypt($row['day_expert_committed'], $key);
                $expert_comment = decryptData($row['expert_comment'], $key);
            }
    
            echo $numResults;
            echo $idea_uuid;
            echo $title;
            echo $innovation_area;
            echo $brief_description;
            echo $problem_statement;
            echo $proposed_solution;
            echo $cost_benefit_analysis;
            echo $original_file_name;
            echo $day_user_uploaded;
            echo $expert_uuid;
            echo $day_expert_appointed;
            echo $day_expert_committed;
            echo $expert_comment;
        }
    
        else{
            
            echo $numResults_null;
            echo $idea_uuid_null;
            echo $title_null;
            echo $innovation_area_null;
            echo $brief_description_null;
            echo $problem_statement_null;
            echo $proposed_solution_null;
            echo $cost_benefit_analysis_null;
            echo $original_file_name_null;
            echo $day_user_uploaded_null;
            echo $expert_uuid_null;
            echo $day_expert_appointed_null;
            echo $day_expert_committed_null;
            echo $expert_comment_null;
            echo $encryptedday_expert_committed_null;
        } 
        $stmt->close();
    }

    //will be used for fetching allocated ideas coz i changed the pending ideas
    if ($_GET['action'] === 'pendingIdeas') {
    
        $stmt1 = $con->prepare("SELECT * FROM submitted_ideas WHERE expert_uuid = ? AND day_expert_committed = ? ORDER BY timestamp DESC");
        $stmt1->bind_param("ss", $db_en_ses_uuid, $encryptedday_expert_committed);
        $stmt1->execute();
        $result1 = $stmt1->get_result();
    
        $numResults1 = $result1->num_rows;
    
        if ($result1->num_rows > 0) {
            while ($row1 = $result1->fetch_assoc()) {
                // Decrypt the encrypted fields
                $idea_uuid1 = decryptData($row1['idea_uuid'], $key);
                $title1 = $row1['title'];
                $innovation_area1 = $row1['innovation_area'];
                $brief_description1 = $row1['brief_description'];
                $problem_statement1 = $row1['problem_statement'];
                $proposed_solution1 = $row1['proposed_solution'];
                $cost_benefit_analysis1 = $row1['cost_benefit_analysis'];
                $original_file_name1 = decryptData($row1['original_file_name'], $key);
                $upload_id1 = decrypt($row1['upload_id'], $key);
                $day_user_uploaded1 = decryptData($row1['day_user_uploaded'], $key);
                $expert_uuid1 = decrypt($row1['expert_uuid'], $key);
                $day_expert_appointed1 = decryptData($row1['day_expert_appointed'], $key);
                $day_expert_committed1 = decrypt($row1['day_expert_committed'], $key);
                $expert_comment1 = decryptData($row1['expert_comment'], $key);
            }
    
            echo $numResults1;
            echo $idea_uuid1;
            echo $title1;
            echo $innovation_area1;
            echo $brief_description1;
            echo $problem_statement1;
            echo $proposed_solution1;
            echo $cost_benefit_analysis1;
            echo $original_file_name1;
            echo $day_user_uploaded1;
            echo $expert_uuid1;
            echo $day_expert_appointed1;
            echo $day_expert_committed1;
            echo $expert_comment1;
        }
    
        else{
            
            echo $numResults_null;
            echo $idea_uuid_null;
            echo $title_null;
            echo $innovation_area_null;
            echo $brief_description_null;
            echo $problem_statement_null;
            echo $proposed_solution_null;
            echo $cost_benefit_analysis_null;
            echo $original_file_name_null;
            echo $day_user_uploaded_null;
            echo $expert_uuid_null;
            echo $day_expert_appointed_null;
            echo $day_expert_committed_null;
            echo $expert_comment_null;
            echo $encryptedday_expert_committed_null;
        }
        $stmt1->close();
    }

    
    if ($_GET['action'] === 'committedIdeas') {

        $stmt2 = $con->prepare("SELECT * FROM submitted_ideas WHERE expert_uuid = ? AND day_expert_committed != ? ORDER BY timestamp DESC");
        $stmt2->bind_param("ss", $db_en_ses_uuid, $encryptedday_expert_committed);
        $stmt2->execute();
        $result2 = $stmt2->get_result();
    
        $numResults2 = $result2->num_rows;
    
        if ($result2->num_rows > 0) {
            while ($row2 = $result2->fetch_assoc()) {
                // Decrypt the encrypted fields
                $idea_uuid2 = decryptData($row2['idea_uuid'], $key);
                $title2 = $row2['title'];
                $innovation_area2 = $row2['innovation_area'];
                $brief_description2 = $row2['brief_description'];
                $problem_statement2 = $row2['problem_statement'];
                $proposed_solution2 = $row2['proposed_solution'];
                $cost_benefit_analysis2 = $row2['cost_benefit_analysis'];
                $original_file_name2 = decryptData($row2['original_file_name'], $key);
                $upload_id2 = decrypt($row2['upload_id'], $key);
                $day_user_uploaded2 = decryptData($row2['day_user_uploaded'], $key);
                $expert_uuid2 = decrypt($row2['expert_uuid'], $key);
                $day_expert_appointed2 = decryptData($row2['day_expert_appointed'], $key);
                $day_expert_committed2 = decrypt($row2['day_expert_committed'], $key);
                $expert_comment2 = decryptData($row2['expert_comment'], $key);
            }
    
            echo $numResults2;
            echo $idea_uuid2;
            echo $title2;
            echo $innovation_area2;
            echo $brief_description2;
            echo $problem_statement2;
            echo $proposed_solution2;
            echo $cost_benefit_analysis2;
            echo $original_file_name2;
            echo $day_user_uploaded2;
            echo $expert_uuid2;
            echo $day_expert_appointed2;
            echo $day_expert_committed2;
            echo $expert_comment2;
            echo $encryptedday_expert_committed;
        }
    
        else{
            
            echo $numResults_null;
            echo $idea_uuid_null;
            echo $title_null;
            echo $innovation_area_null;
            echo $brief_description_null;
            echo $problem_statement_null;
            echo $proposed_solution_null;
            echo $cost_benefit_analysis_null;
            echo $original_file_name_null;
            echo $day_user_uploaded_null;
            echo $expert_uuid_null;
            echo $day_expert_appointed_null;
            echo $day_expert_committed_null;
            echo $expert_comment_null;
            echo $encryptedday_expert_committed_null;
        }
        $stmt2->close();
    }

    //fetches pending ideas
    if ($_GET['action'] === 'updateTablePendingIdeas') {
        $stmt3 = $con->prepare("SELECT * FROM submitted_ideas WHERE expert_uuid = ? AND day_expert_committed = ? ORDER BY STR_TO_DATE(day_expert_appointed, '%a, %M %d, %Y - %h:%i %p') DESC");
        //$stmt3 = $con->prepare("SELECT * FROM submitted_ideas WHERE expert_uuid = ? AND day_expert_committed = ? ORDER BY CAST(day_expert_appointed AS DATETIME) ASC");
        $stmt3->bind_param("ss", $db_en_ses_uuid, $encryptedday_expert_committed);
        $stmt3->execute();
        $result3 = $stmt3->get_result();
    
        $ideas = array();
    
        if ($result3->num_rows > 0) {
            while ($row3 = $result3->fetch_assoc()) {
                $idea_uuid = ($row3['idea_uuid']);
                $title = $row3['title'];
                $innovation_area = $row3['innovation_area'];
                $day_expert_appointed = $row3['day_expert_appointed'];
                $upload_id = $row3['upload_id'];
                $fileName = $upload_id . '.pdf';
    
                $ideas[] = array(
                    'idea_uuid' => $idea_uuid,
                    'title' => $title,
                    'innovation_area' => $innovation_area,
                    'day_expert_appointed' => $day_expert_appointed,
                    'upload_id' => $upload_id,
                    'fileName' => $fileName
                );
            }
    
            header('Content-Type: application/json');
            echo json_encode($ideas);
        }
    }

    //fetches allocated ideas
    if ($_GET['action'] === 'updateTableAllocatedIdeas') {
        $stmt3 = $con->prepare("SELECT * FROM submitted_ideas WHERE expert_uuid = ? ORDER BY STR_TO_DATE(day_expert_appointed, '%a, %M %d, %Y - %h:%i %p') DESC");
        $stmt3->bind_param("s", $db_en_ses_uuid);
        $stmt3->execute();
        $result3 = $stmt3->get_result();
    
        $ideas = array();
    
        if ($result3->num_rows > 0) {
            while ($row3 = $result3->fetch_assoc()) {
                $idea_uuid = ($row3['idea_uuid']);
                $title = $row3['title'];
                $innovation_area = $row3['innovation_area'];
                $day_expert_appointed = $row3['day_expert_appointed'];
                $day_expert_committed = $row3['day_expert_committed'];
                $upload_id = $row3['upload_id'];
                $status = decrypt($row3['status'], $key);
                $fileName = $upload_id . '.pdf';

                $pending = "pending";

                if ($status != $pending) {
                    $status = "reviewed";
                } else {
                    $status = $pending;
                }
                
    
                $ideas[] = array(
                    'idea_uuid' => $idea_uuid,
                    'title' => $title,
                    'innovation_area' => $innovation_area,
                    'day_expert_appointed' => $day_expert_appointed,
                    'day_expert_committed' => $day_expert_committed,
                    'upload_id' => $upload_id,
                    'status' => $status,
                    'fileName' => $fileName
                );
            }
    
            header('Content-Type: application/json');
            echo json_encode($ideas);
        }
    }

    //fetches allocated ideas
    if ($_GET['action'] === 'updateTableCommittedIdeas') {

        $day_expert_committed = "not committed";
        $enc_day_expert_committed = $day_expert_committed;

        $stmt3 = $con->prepare("SELECT * FROM submitted_ideas WHERE expert_uuid = ? AND day_expert_committed != ? ORDER BY STR_TO_DATE(day_expert_committed, '%a, %M %d, %Y - %h:%i %p') DESC");
        $stmt3->bind_param("ss", $db_en_ses_uuid, $enc_day_expert_committed);
        $stmt3->execute();
        $result3 = $stmt3->get_result();
    
        $ideas = array();
    
        if ($result3->num_rows > 0) {
            while ($row3 = $result3->fetch_assoc()) {
                $idea_uuid = ($row3['idea_uuid']);
                $title = $row3['title'];
                $innovation_area = $row3['innovation_area'];
                $day_expert_appointed = $row3['day_expert_appointed'];
                $day_expert_committed = $row3['day_expert_committed'];
                $upload_id = $row3['upload_id'];
                $fileName = $upload_id . '.pdf';

                $expert_comment = decryptData($row3['expert_comment'], $key);

                list($comment_type, $comment_text) = explode(':;', $expert_comment, 2);
    
                $ideas[] = array(
                    'idea_uuid' => $idea_uuid,
                    'title' => $title,
                    'innovation_area' => $innovation_area,
                    'day_expert_appointed' => $day_expert_appointed,
                    'day_expert_committed' => $day_expert_committed,
                    'upload_id' => $upload_id,
                    'fileName' => $fileName,
                    'comment_type' => $comment_type,
                    'comment_text' => $comment_text
                );
            }
    
            header('Content-Type: application/json');
            echo json_encode($ideas);
        }
    }

    //fetches history of all ideas
    //change the order of display
    if ($_GET['action'] === 'updateTableHistoryIdeas') {

        $day_expert_committed = "not committed";
        $enc_day_expert_committed = encrypt($day_expert_committed, $key);
        $stmt3 = $con->prepare("SELECT * FROM submitted_ideas WHERE expert_uuid = ? ORDER BY STR_TO_DATE(day_expert_committed, '%a, %M %d, %Y - %h:%i %p') DESC, STR_TO_DATE(day_expert_appointed, '%a, %M %d, %Y - %h:%i %p') DESC");

        $stmt3->bind_param("s", $db_en_ses_uuid);
        $stmt3->execute();
        $result3 = $stmt3->get_result();
    
        $ideas = array();
    
        if ($result3->num_rows > 0) {
            while ($row3 = $result3->fetch_assoc()) {
                $idea_uuid = ($row3['idea_uuid']);
                $title = $row3['title'];
                $innovation_area = $row3['innovation_area'];
                $day_expert_appointed = $row3['day_expert_appointed'];
                $day_expert_committed = $row3['day_expert_committed'];
                $upload_id = $row3['upload_id'];
                $fileName = $upload_id . '.pdf';
                
                if (isset($row3['expert_comment'])) {
                    $expert_comment = decryptData($row3['expert_comment'], $key);
                    
                    // Check if the string contains the delimiter
                    if (strpos($expert_comment, ':;') !== false) {
                        list($comment_type, $comment_text) = explode(':;', $expert_comment, 2);
                    } else {
                        // Handle the case where the delimiter is not found
                        $comment_type = 'default_comment_type';
                        $comment_text = 'default_comment_text';
                    }
                } else {
                    // Handle the case where 'expert_comment' key doesn't exist
                    $comment_type = 'default_comment_type';
                    $comment_text = 'default_comment_text';
                }
                
    
                $ideas[] = array(
                    'idea_uuid' => $idea_uuid,
                    'title' => $title,
                    'innovation_area' => $innovation_area,
                    'day_expert_appointed' => $day_expert_appointed,
                    'day_expert_committed' => $day_expert_committed,
                    'upload_id' => $upload_id,
                    'fileName' => $fileName,
                    'comment_type' => $comment_type,
                    'comment_text' => $comment_text
                );
            }
    
            header('Content-Type: application/json');
            echo json_encode($ideas);
        }
    }

    //update comment of the selected ideas
    if ($_GET['action'] === 'post_comment') {

        $commentType = $_POST['comment_type'];
        $commentText = $_POST['comment_text'];
        $ideaUUID = $_POST['idea_uuid'];

        $day_expert_committed = date('D, F j, Y - h:i A');
        $combined_comment = $commentType . ':;' . $commentText;
        $stage = "review done";
        $status = "awaiting report";

        $enc_combined_comment = encryptData($combined_comment, $key);
        $enc_day_expert_committed = $day_expert_committed;
        $enc_stage = encrypt($stage, $key);
        $enc_status = encrypt($status, $key);
        
        
        $stmt3 = $con->prepare("UPDATE submitted_ideas SET expert_comment = ?, day_expert_committed = ?, stage = ?, status = ? WHERE idea_uuid = ?");
        $stmt3->bind_param("sssss", $enc_combined_comment, $enc_day_expert_committed, $enc_stage, $enc_status, $ideaUUID);

        if ($stmt3->execute()) {
            echo "Comment submitted successfully.";
        } else {
            echo "Error submitting the comment.";
        }
        $stmt3->close();
        $con->close();
    }
    


    //fecthes data for the comment action in the comment form
    if (isset($_GET['action']) && $_GET['action'] === 'getIdeaDetails' && isset($_GET['ideaUUID'])) {
        $ideaUUID = $_GET['ideaUUID'];
        
        $stmt3 = $con->prepare("SELECT * FROM submitted_ideas WHERE upload_id = ? LIMIT 1");
        $stmt3->bind_param("s", $ideaUUID);
        $stmt3->execute();
        $result3 = $stmt3->get_result();
        
        // Check if the idea with the provided upload_id exists
        if ($result3->num_rows > 0) {
            $ideaDetails = $result3->fetch_assoc();  // Fetch idea details from the database
            
            // Generate and display the dynamic form with idea details
            echo '<form class="mb-3 display_form_information">';
        
                // Populate the form fields with idea details
                echo '<h3 id="title" name="title" style="text-align: center;">Title:' . htmlspecialchars($ideaDetails['title']) . '</h3>';
                
                echo '<div class="alert alert-danger close_ideaDetailsContainer" style="position: absolute; top: 10px; right: 10px;">';
                    echo '<button type="button" class="btn-close" aria-label="Close"></button>';
                echo '</div>';

                echo '<div class="row">';
                    echo '<div class="col-lg-4 col-md-6 col-xl-3 display_vertical">';
                        echo '<label name="area" style="text-align: center; font-weight: bolder; font-size: 20px;">Innovation Area:</label>';
                        echo '<label name="area" style="text-align: center; font-size: 20px; color: limegreen;">' . htmlspecialchars($ideaDetails['innovation_area']) . '</label>';
                    echo '</div>';
                
                    echo '<div class="col-lg-4 col-md-6 col-xl-3 display_vertical">';
                        echo '<label name="description" style="text-align: center; font-weight: bolder; font-size: 20px;">Description:</label>';
                        echo '<label name="description" style="text-align: center; font-size: 20px; color: limegreen;">' . htmlspecialchars($ideaDetails['brief_description']) . '</label>';
                    echo '</div>';
                
                    echo '<div class="col-lg-4 col-md-6 col-xl-3 display_vertical">';
                        echo '<label name="problem" style="text-align: center; font-weight: bolder; font-size: 20px;">Problem Statement:</label>';
                        echo '<label name="problem" style="text-align: center; font-size: 20px; color: limegreen;">' . htmlspecialchars($ideaDetails['problem_statement']) . '</label>';
                    echo '</div>';
                
                    echo '<div class="col-lg-4 col-md-6 col-xl-3 display_vertical">';
                        echo '<label name="solution" style="text-align: center; font-weight: bolder; font-size: 20px;">Proposed Solution:</label>';
                        echo '<label name="solution" style="text-align: center; font-size: 20px; color: limegreen;">' . htmlspecialchars($ideaDetails['proposed_solution']) . '</label>';
                    echo '</div>';
                
                    echo '<div class="col-lg-4 col-md-6 col-xl-3 display_vertical">';
                        echo '<label name="cost" style="text-align: center; font-weight: bolder; font-size: 20px;">Cost Benefit Analysis:</label>';
                        echo '<label name="cost" style="text-align: center; font-size: 20px; color: limegreen;">' . htmlspecialchars($ideaDetails['cost_benefit_analysis']) . '</label>';
                    echo '</div>';
                
                    echo '<div class="col-lg-4 col-md-6 col-xl-3 display_vertical">';
                        echo '<label name="status" style="text-align: center; font-weight: bolder; font-size: 20px;">Status:</label>';
                        echo '<label name="status" style="text-align: center; font-size: 20px; color: tomato;">' . htmlspecialchars(decrypt(($ideaDetails['status']), $key)) . '</label>';
                    echo '</div>';
                
                    echo '<div class="col-lg-4 col-md-6 col-xl-3 display_vertical">';
                        echo '<label name="expert_assigned" style="text-align: center; font-weight: bolder; font-size: 20px;">Assigned to you on:</label>';
                        echo '<label name="expert_assigned" style="text-align: center; font-size: 20px; color: limegreen;">' . htmlspecialchars(($ideaDetails['day_expert_appointed'])) . '</label>';
                    echo '</div>';
                echo '</div>';

                echo '<h2>Comment on the idea</h2>';
                echo '<div class="comment_holder">';
                    echo '<div class="comment_sub_holder_1">';
                        echo '<label for="recipient" class="form-label">Comment Type</label>';
                        echo '<select class="form-select" id="Comment_type" name="Comment_type" style="background-color: gainsboro;" required>';
                            echo '<option value=""> * Select Comment Type * </option>';
                            echo '<option value="critical">critical</option>';
                            echo '<option value="general">general</option>';
                            echo '<option value="suggestion">suggestion</option>';
                        echo '</select>';
                        echo '<p id="comment_info" class="comment_info"><i><b>';
                        echo '</i></b></p>';
                    echo '</div>';
                    echo '<div class="comment_sub_holder_2">';
                        echo '<label for="recipient" class="form-label">Comment Here</label>';
                        echo '<textarea class="form-control"rows="5" placeholder="Type your comment here" style="background-color: gainsboro;" required id="comment_textarea"></textarea>';
                            
                        echo '</p>';
                    echo '</div>';
                echo '</div>';
                echo '<div class="comment_btn" style="display: flex; width: 100%; justify-content: center;">';
                    echo '<button type="submit" class="btn btn-primary comment_btn" name="submit_comment" data-idea-uuid="' . htmlspecialchars($ideaDetails['idea_uuid']) . '">submit&nbsp;comment</button>';
                echo '</div>';

                // Add more form fields for other idea details
    
            echo '</form>';
        }
    }

?>
