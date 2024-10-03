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

    if (isset($_POST['sign_up'])) {
        
        $staff_uuid = generateRandomUUID_staff($length = 10, $con);
        $staff_session_id = generateRandomUUID_staff($length = 10, $con);
        $OTP_code = generateOTP_staff($length = 6, $con);

        //use function to generate string
        $account_status = "active";
        $account_type = "expert";

        // Retrieve and sanitize input values
        $firstName = filter_input(INPUT_POST, 'firstName', FILTER_SANITIZE_STRING);
        $otherNames = filter_input(INPUT_POST, 'otherNames', FILTER_SANITIZE_STRING);
        $idNumber = filter_input(INPUT_POST, 'idNumber', FILTER_SANITIZE_NUMBER_INT);
        $mobileNumber = filter_input(INPUT_POST, 'mobileNumber', FILTER_SANITIZE_NUMBER_INT);
        $personalEmail = filter_input(INPUT_POST, 'personalEmail', FILTER_SANITIZE_STRING);
        $kenhaEmail = filter_input(INPUT_POST, 'kenhaEmail', FILTER_SANITIZE_STRING);
        $staffNumber = filter_input(INPUT_POST, 'staffNumber', FILTER_SANITIZE_NUMBER_INT);
        $department = filter_input(INPUT_POST, 'department', FILTER_SANITIZE_STRING);
        $gender = filter_input(INPUT_POST, 'gender', FILTER_SANITIZE_STRING);
        
        // Check if inputs are empty and set them to "not filled"
        $firstName = empty($firstName) ? "not filled" : $firstName;
        $otherNames = empty($otherNames) ? "not filled" : $otherNames;
        $idNumber = empty($idNumber) ? "not filled" : $idNumber;
        $mobileNumber = empty($mobileNumber) ? "not filled" : $mobileNumber;
        $personalEmail = empty($personalEmail) ? "not filled" : $personalEmail;
        $staffNumber = empty($staffNumber) ? "not filled" : $staffNumber;
        $department = empty($department) ? "not filled" : $department;
        $gender = empty($gender) ? "not filled" : $gender;

        
        $encryptedstaff_uuid = encrypt($staff_uuid, $key);
        $encryptedstaff_session_id = encrypt($staff_session_id, $key);
        $encryptedfirstName = encrypt($firstName, $key);
        $encryptedotherNames = encrypt($otherNames, $key);
        $encryptedidNumber = encrypt($idNumber, $key);
        $encryptedmobileNumber = encrypt($mobileNumber, $key);
        $encryptedpersonalEmail = encrypt($personalEmail, $key);
        $encryptedkenhaEmail = encrypt($kenhaEmail, $key);
        $encryptedstaffNumber = encryptData($staffNumber, $key);
        $encrypteddepartment = encrypt($department, $key);
        $encryptedOTP_code = encryptData($OTP_code, $key);
        $encryptedaccount_status = encryptData($account_status, $key);
        $encryptedaccount_type = encrypt($account_type, $key);

        echo "$staff_uuid";
        echo "$staff_session_id";
        echo "$firstName";
        echo "$otherNames";
        echo "$mobileNumber";
        echo "$personalEmail";
        echo "$kenhaEmail";
        echo "$staffNumber";
        echo "$department";
        echo "$OTP_code";
        echo "$account_status";
        echo "$account_type";

        echo "$encryptedstaff_uuid";
        echo "$encryptedstaff_session_id";
        echo "$encryptedfirstName";
        echo "$encryptedotherNames";
        echo "$encryptedmobileNumber";
        echo "$encryptedpersonalEmail";
        echo "$encryptedkenhaEmail";
        echo "$encryptedstaffNumber";
        echo "$encrypteddepartment";
        echo "$encryptedOTP_code";
        echo "$encryptedaccount_status";
        echo "$encryptedaccount_type";

        $stmt = $con->prepare("SELECT s.*, ss.* FROM staff_table s INNER JOIN staff_sub_table ss ON s.staff_uuid = ss.staff_uuid");
        $stmt->execute();
        $result = $stmt->get_result();

        if (mysqli_num_rows($result) == 0) {
            //allow user to register and Upload to the database
            $stmt3 = $con->prepare("INSERT INTO staff_table (staff_uuid, session_id, first_name, other_names, id_number, mobile_number, personal_email, gender, OTP_code, account_type, account_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt3->bind_param("sssssssssss", $encryptedstaff_uuid, $encryptedstaff_session_id, $encryptedfirstName, $encryptedotherNames, $encryptedidNumber, $encryptedmobileNumber, $encryptedpersonalEmail, $gender, $encryptedOTP_code, $encryptedaccount_type, $encryptedaccount_status);
            $stmt3->execute();
            $stmt3->close();

            
            //allow user to register and Upload to the database
            $stmt4 = $con->prepare("INSERT INTO staff_sub_table (staff_uuid, KeNHA_email , staff_number, department) VALUES (?, ?, ?, ?)");
            $stmt4->bind_param("ssss", $encryptedstaff_uuid, $encryptedkenhaEmail, $encryptedstaffNumber, $encrypteddepartment);
            $stmt4->execute();
            $stmt4->close();

            echo "created";

            exit;
            
        }
        else {
            $row = $result->fetch_assoc();
            
            //dont allow user to signup if there's an existing information
            $db_encrtypted_id_number = $row['id_number'];
            $db_encrtypted_personal_email = $row['personal_email'];
            $db_encrtypted_mobile_number = $row['mobile_number'];

            $db_encrtypted_KeNHA_email = $row['KeNHA_email'];
            $db_encrtypted_staff_number = $row['staff_number'];

            if ($db_encrtypted_KeNHA_email == $encryptedkenhaEmail) {
                
                echo "The kenha email already exists";

                exit;
            }

            else{
                //allow user to register and Upload to the database
                $stmt3 = $con->prepare("INSERT INTO staff_table (staff_uuid, session_id, first_name, other_names, id_number, mobile_number, personal_email, gender, OTP_code, account_type, account_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt3->bind_param("sssssssssss", $encryptedstaff_uuid, $encryptedstaff_session_id, $encryptedfirstName, $encryptedotherNames, $encryptedidNumber, $encryptedmobileNumber, $encryptedpersonalEmail, $gender, $encryptedOTP_code, $encryptedaccount_type, $encryptedaccount_status);
                $stmt3->execute();
                $stmt3->close();
    
                
                //allow user to register and Upload to the database
                $stmt4 = $con->prepare("INSERT INTO staff_sub_table (staff_uuid, KeNHA_email , staff_number, department) VALUES (?, ?, ?, ?)");
                $stmt4->bind_param("ssss", $encryptedstaff_uuid, $encryptedkenhaEmail, $encryptedstaffNumber, $encrypteddepartment);
                $stmt4->execute();
                $stmt4->close();
    
                echo "created";

                exit;
            }

        }
        
    }
    else {
        header("Location: /KeNHAVATE/error_file");
    }
?>