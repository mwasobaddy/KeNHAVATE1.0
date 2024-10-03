<?php
    
    function check_login_admin($con){

        if (isset($_SESSION['session_id'])) {

            //validate the session_id
            $session_id = $_SESSION['session_id'];
            $key = 'my-KeNHAsecret-passkey';
            $encrypt_session_id = encrypt($session_id, $key);

            $stmt = $con->prepare("SELECT s.*, ss.* FROM staff_table s INNER JOIN staff_sub_table ss ON s.staff_uuid = ss.staff_uuid WHERE s.session_id = ? LIMIT 1");
            $stmt->bind_param("s", $encrypt_session_id);
            $stmt->execute();
            $result = $stmt->get_result();
    
            if ($result->num_rows >= 1) {

                //fetch the associative array of data in the db if reslts are found
                $row = $result->fetch_assoc();

                $db_encrtypted_KeNHA_email = $row['KeNHA_email'];
                $db_encrtypted_personal_email = $row['personal_email'];

                //retrieve the stored email in session
                $session_dec_email = $_SESSION['input_email'];
                $session_en_email = encrypt($session_dec_email, $key);
    
                if (($db_encrtypted_KeNHA_email == $session_en_email) || ($db_encrtypted_personal_email == $session_en_email)) {
                    return 0;
                }
                else {
                
                    $_SESSION['session_id'] = "";
                    header("Location: /KeNHAVATE/kenhavate");
                }
            }
            else{
                $_SESSION['session_id'] = "";
                header("Location: /KeNHAVATE/kenhavate");
            }
        }
        else {
            $_SESSION['session_id'] = "";
            header("Location: /KeNHAVATE/kenhavate");
        } 
    }
    
    //random string generating function
    function generateRandomUUID_staff($length = 10, $con) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()_+{}|:"<>?~';
        $charLength = strlen($characters);
        $randomString = '';
        $unique = false;
        while (!$unique) {
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charLength - 1)];
            }

            // Example usage:
            $encryptionKey = 'my-KeNHAsecret-passkey';
            $encrypteduuid = encrypt($randomString, $encryptionKey);

            $stmt = $con->prepare("SELECT * FROM users_table WHERE uuid = ? LIMIT 1");
            $stmt->bind_param("s", $encrypteduuid);
            $stmt->execute();
            $result = $stmt->get_result();

            // Perform the database query to check if the random string exists
            // If the query returns no rows, then the string is unique and the loop will exit
            // If the query returns at least one row, then the string is not unique and the loop will continue

            //search starts from the user table
            if($result->num_rows == 0){
                $unique = true;
            }
            else {
                $randomString = '';
            }
        }
    
        return $randomString;
    }

    //random string generating function
    function generateOTP_staff($length = 6, $con) {
        $characters = '0123456789';
        $charLength = strlen($characters);
        $randomString = '';
        $unique = false;
        while (!$unique) {
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charLength - 1)];
            }
            
            $encryptionKey = 'my-KeNHAsecret-passkey';
            $encryptedOTP = encryptData($randomString, $encryptionKey);

            $stmt = $con->prepare("SELECT * FROM staff_table WHERE OTP_code = ? LIMIT 1");
            $stmt->bind_param("s", $encryptedOTP);
            $stmt->execute();
            $result = $stmt->get_result();

            // Perform the database query to check if the random string exists
            // If the query returns no rows, then the string is unique and the loop will exit
            // If the query returns at least one row, then the string is not unique and the loop will continue

            //search starts from the user table
            if($result->num_rows == 0){
                $unique = true;
            }
            else {
                $randomString = '';
            }
        }
    
        return $randomString;
    }

    //function to check if data details are empty in the database, if they are empty redirect to a certain form
    function check_details_admin($con){

        if (isset($_SESSION['uuid'])) {
            

            //validate the session_id
            $session_uuid = $_SESSION['uuid'];
            $key = 'my-KeNHAsecret-passkey';

            $stmt = $con->prepare("SELECT s.*, ss.* FROM staff_table s INNER JOIN staff_sub_table ss ON s.staff_uuid = ss.staff_uuid WHERE ss.staff_uuid = ? LIMIT 1");
            $stmt->bind_param("s", $session_uuid);
            $stmt->execute();
            $result = $stmt->get_result();
    
            if ($result->num_rows >= 1) {

                //fetch the associative array of data in the db if reslts are found
                $row = $result->fetch_assoc();

                $missingFields = [];

                //staff table
                $db_decrypted_session_id = decrypt($row['session_id'], $key);
                $db_decrypted_personal_email = decrypt($row['personal_email'], $key);
                $db_decrypted_first_name = decrypt($row['first_name'], $key);
                $db_decrypted_other_names = decrypt($row['other_names'], $key);
                $db_decrypted_id_number = decrypt($row['id_number'], $key);
                $db_decrypted_mobile_number = decrypt($row['mobile_number'], $key);
                $db_decrypted_gender = decrypt($row['gender'], $key);

                ////staff sub table

                $db_decrypted_KeNHA_email = decrypt($row['KeNHA_email'], $key);
                $db_decrypted_staff_number = decryptData($row['staff_number'], $key);

                

            // Check if any data is missing and add the corresponding field name to the $missingFields array
                if ($db_decrypted_first_name === 'not filled') {
                    $missingFields[] = 'firstName';
                }
                if ($db_decrypted_other_names === 'not filled') {
                    $missingFields[] = 'otherNames';
                }
                if ($db_decrypted_id_number === 'not filled') {
                    $missingFields[] = 'idNumber';
                }
                if ($db_decrypted_personal_email === 'not filled') {
                    $missingFields[] = 'personalEmail';
                }
                if ($db_decrypted_mobile_number === 'not filled') {
                    $missingFields[] = 'mobileNumber';
                }
                if ($db_decrypted_gender === 'not filled') {
                    $missingFields[] = 'gender';
                }
                if ($db_decrypted_KeNHA_email === 'not filled') {
                    $missingFields[] = 'kenhaEmail';
                }
                if ($db_decrypted_staff_number === 'not filled') {
                    $missingFields[] = 'staffNumber';
                }
                

                if (!empty($missingFields)) {
                    $_SESSION['missingFields'] = $missingFields;
                    header("Location: /KeNHAVATE/staff-add-details");
                    exit;
                }
                else {
                    return 0;
                }
                
            }
        }
        else{
            //redirect to kenhavate
            echo 'Kindly ask for help';
        }
    }
    
?>