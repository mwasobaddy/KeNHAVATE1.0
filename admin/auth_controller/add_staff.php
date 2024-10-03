<?php
    session_start();

    include("../../auth_controller/requirement.php");
    include("./requirement.php");

    $key = 'my-KeNHAsecret-passkey';
        
    $staff_uuid = generateRandomUUID_staff($length = 10, $con);
    $staff_session_id = generateRandomUUID_staff($length = 10, $con);
    $OTP_code = generateOTP_staff($length = 6, $con);
    $account_status = "active";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {        
        $firstName = filter_input(INPUT_POST, 'firstName', FILTER_SANITIZE_STRING);
        $otherNames = filter_input(INPUT_POST, 'otherNames', FILTER_SANITIZE_STRING);
        $idNumber = filter_input(INPUT_POST, 'idNumber', FILTER_SANITIZE_NUMBER_INT);
        $mobileNumber = filter_input(INPUT_POST, 'mobileNumber', FILTER_SANITIZE_NUMBER_INT);
        $personalEmail = filter_input(INPUT_POST, 'personalEmail', FILTER_SANITIZE_STRING);
        $kenhaEmail = filter_input(INPUT_POST, 'kenhaEmail', FILTER_SANITIZE_STRING);
        $staffNumber = filter_input(INPUT_POST, 'staffNumber', FILTER_SANITIZE_NUMBER_INT);
        $directorate = filter_input(INPUT_POST, 'directorate', FILTER_SANITIZE_STRING);
        $gender = filter_input(INPUT_POST, 'gender', FILTER_SANITIZE_STRING);
        $accountType = filter_input(INPUT_POST, 'accountType', FILTER_SANITIZE_STRING);
        
        // Check if inputs are empty and set them to "not filled"
        $firstName = empty($firstName) ? "not filled" : $firstName;
        $otherNames = empty($otherNames) ? "not filled" : $otherNames;
        $idNumber = empty($idNumber) ? "not filled" : $idNumber;
        $mobileNumber = empty($mobileNumber) ? "not filled" : $mobileNumber;
        $personalEmail = empty($personalEmail) ? "not filled" : $personalEmail;
        $staffNumber = empty($staffNumber) ? "not filled" : $staffNumber;


        
        $encryptedstaff_uuid = encrypt($staff_uuid, $key);
        $encryptedstaff_session_id = encrypt($staff_session_id, $key);
        $encryptedfirstName = encrypt($firstName, $key);
        $encryptedotherNames = encrypt($otherNames, $key);
        $encryptedidNumber = encrypt($idNumber, $key);
        $encryptedmobileNumber = encrypt($mobileNumber, $key);
        $encryptedpersonalEmail = encrypt($personalEmail, $key);
        $encryptedkenhaEmail = encrypt($kenhaEmail, $key);
        $encryptedstaffNumber = encryptData($staffNumber, $key);
        $encrypteddirectorate = encrypt($directorate, $key);
        $encryptedOTP_code = encryptData($OTP_code, $key);
        $encryptedaccount_status = encryptData($account_status, $key);
        $encryptedaccountType = encrypt($accountType, $key);


        // Perform data insertion into the admin table
        $stmt3 = $con->prepare("INSERT INTO staff_table (staff_uuid, session_id, first_name, other_names, id_number, mobile_number, personal_email, gender, OTP_code, account_type, account_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt3->bind_param("sssssssssss", $encryptedstaff_uuid, $encryptedstaff_session_id, $encryptedfirstName, $encryptedotherNames, $encryptedidNumber, $encryptedmobileNumber, $encryptedpersonalEmail, $gender, $encryptedOTP_code, $encryptedaccountType, $encryptedaccount_status);
        if (!$stmt3) {
            die('Error in SQL query: ' . $con->error);
        }

        
        //allow user to register and Upload to the database
        $stmt4 = $con->prepare("INSERT INTO staff_sub_table (staff_uuid, KeNHA_email , staff_number, directorate) VALUES (?, ?, ?, ?)");
        $stmt4->bind_param("ssss", $encryptedstaff_uuid, $encryptedkenhaEmail, $encryptedstaffNumber, $encrypteddirectorate);
        if (!$stmt4) {
            die('Error in SQL query: ' . $con->error);
        }

        $stmt3_result = $stmt3->execute();
        $stmt4_result = $stmt4->execute();
    
        if ($stmt3_result && $stmt4_result) {
            echo json_encode(['message' => 'Admin admitted successfully']);
        } else {
            echo json_encode(['error' => 'Admin admission failed']);
        }

        // Close the database connection
        $stmt3->close();
        $stmt4->close();

        $con->close();
    }
    else {
        header("Location: /KeNHAVATE/error_file");
        echo json_encode(['error' => 'Invalid request']);
    }
