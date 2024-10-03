<?php
    session_start();

    include("../../auth_controller/requirement.php");
    include("./requirement.php");

    $key = 'my-KeNHAsecret-passkey';

    // Receive the row identifier from the AJAX request
    $upload_id = $_POST['upload_id'];

    $stmt = $con->prepare("SELECT * FROM submitted_ideas WHERE upload_id = ?");
    $stmt->bind_param("s", $upload_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $row = $result->fetch_assoc();

    $title = $row['title'];
    $innovation_area = $row['innovation_area'];
    $brief_description = $row['brief_description'];
    $problem_statement = $row['problem_statement'];
    $proposed_solution = $row['proposed_solution'];
    $cost_benefit_analysis = $row['cost_benefit_analysis'];
    $stage = $row['stage'];
    $status = $row['status'];
    $expert_uuid = $row['expert_uuid'];// used to fetch staff name
    $day_expert_committed = $row['day_expert_committed'];

    // Fetch details for the selected row from the database

    // Generate a dynamic form with the fetched details
    
    // Generate a dynamic form with Bootstrap 5 styling
    $form = '<form class="container mt-5">';
        $form .= '<h4 class="mb-3" style="text-align: center;">Idea Details</h4>';
        $form .= '<h4 class="mb-3" style="text-align: center;">Title: ' . $title . '</h4>';

        // Innovation Area
        $form .= '<div class="row">';
            $form .= '<div class="mb-3 col-lg-6 col-md-6 col-sm-12 col-12">';
                $form .= '<label for="innovation_area" class="form-label">Innovation Area</label>';
                $form .= '<label id="innovation_area" class="form-control">' . $innovation_area . '</label>';
            $form .= '</div>';

            // Brief Description
            $form .= '<div class="mb-3 col-lg-6 col-md-6 col-sm-12 col-12">';
                $form .= '<label for="brief_description" class="form-label">Brief Description</label>';
                $form .= '<label id="brief_description" class="form-control">' . $brief_description . '</label>';
            $form .= '</div>';
        $form .= '</div>';

        // Problem Statement
        $form .= '<div class="row">';
        $form .= '<div class="mb-3 col-lg-6 col-md-6 col-sm-12 col-12">';
        $form .= '<label for="problem_statement" class="form-label">Problem Statement</label>';
        $form .= '<label id="problem_statement" class="form-control">' . $problem_statement . '</label>';
        $form .= '</div>';

        // Proposed Solution
        $form .= '<div class="mb-3 col-lg-6 col-md-6 col-sm-12 col-12">';
        $form .= '<label for="proposed_solution" class="form-label">Proposed Solution</label>';
        $form .= '<label id="proposed_solution" class="form-control">' . $proposed_solution . '</label>';
        $form .= '</div>';

        // Cost Benefit Analysis
        $form .= '<div class="row">';
            $form .= '<div class="mb-3 col-lg-6 col-md-6 col-sm-12 col-12">';
                $form .= '<label for="cost_benefit_analysis" class="form-label">Cost Benefit Analysis</label>';
                $form .= '<label id="cost_benefit_analysis" class="form-control">' . $cost_benefit_analysis . '</label>';
            $form .= '</div>';

            // Original File Name
            $form .= '<div class="mb-3 col-lg-6 col-md-6 col-sm-12 col-12">';
                $form .= '<label for="original_file_name" class="form-label">Original File Name</label>';
                $form .= '<label id="original_file_name" class="form-control">' . decryptData($row['original_file_name'], $key) . '</label>';
            $form .= '</div>';
        $form .= '</div>';

        // Stage
        $form .= '<div class="row">';
            $form .= '<div class="mb-3 col-lg-6 col-md-6 col-sm-12 col-12">';
                $form .= '<label for="stage" class="form-label">Stage</label>';
                $form .= '<label id="stage" class="form-control">' .  decrypt($stage, $key) . '</label>';
            $form .= '</div>';

            // Status
            $form .= '<div class="mb-3 col-lg-6 col-md-6 col-sm-12 col-12">';
                $form .= '<label for "status" class="form-label">Status</label>';
                $form .= '<label id="status" class="form-control">' .  decrypt($status, $key) . '</label>';
            $form .= '</div>';
        $form .= '</div>';

        // Upload Date
        $form .= '<div class="row">';
            $form .= '<div class="mb-3 col-lg-6 col-md-6 col-sm-12 col-12">';
                $form .= '<label for="upload_date" class="form-label">Upload Date</label>';
                $form .= '<label id="upload_date" class="form-control">' . decryptData($row['day_user_uploaded'], $key) . '</label>';
            $form .= '</div>';

            // Expert Name
            $form .= '<div class="mb-3 col-lg-6 col-md-6 col-sm-12 col-12">';
                $form .= '<label for="expert_uuid" class="form-label">Expert Name</label>';
                $form .= '<label id="expert_uuid" class="form-control">' . $expert_uuid . '</label>';
            $form .= '</div>';
        $form .= '</div>';

        // Day Expert Appointed
        $form .= '<div class="row">';
            $form .= '<div class="mb-3 col-lg-6 col-md-6 col-sm-12 col-12">';
                $form .= '<label for="day_expert_appointed" class="form-label">Day Expert Appointed</label>';
                $form .= '<label id="day_expert_appointed" class="form-control">' . decryptData($row['day_expert_appointed'], $key) . '</label>';
            $form .= '</div>';

            // Day Expert Committed
            $form .= '<div class="mb-3 col-lg-6 col-md-6 col-sm-12 col-12">';
                $form .= '<label for="day_expert_committed" class="form-label">Day Expert Committed</label>';
                $form .= '<label id="day_expert_committed" class="form-control">' . decrypt($day_expert_committed, $key) . '</label>';
            $form .= '</div>';
        $form .= '</div>';

        // Expert Comment
        $form .= '<div class="mb-3 col-lg-6 col-md-6 col-sm-12 col-12">';
            $form .= '<label for="expert_comment" class="form-label">Expert Comment</label>';
            $form .= '<label id="expert_comment" class="form-control">' . decryptData($row['expert_comment'], $key) . '</label>';
        $form .= '</div>';

    $form .= '</form>';

echo $form;
?>
