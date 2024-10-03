<?php
    
    function check_login($con){

        if (isset($_SESSION['session_id'])) {

            //validate the session_id
            $session_id = $_SESSION['session_id'];
            $key = 'my-KeNHAsecret-passkey';
            $encrypt_session_id = encrypt($session_id, $key);

            $stmt = $con->prepare("SELECT * FROM users_table WHERE session_id = ? LIMIT 1");
            $stmt->bind_param("s", $encrypt_session_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if($result->num_rows == 1){

                //fetch the associative array of data in the db if reslts are found
                $row = $result->fetch_assoc();

                $db_encrtypted_email = $row['email'];

                //will be used to fetch uuid for the dispaly in landing page table
                $db_encrtypted_uuid = $row['uuid'];
                $_SESSION['uuid'] = $db_encrtypted_uuid;

                //retrieve the stored email in session
                $session_dec_email = $_SESSION['input_email'];
                $session_en_email = encrypt($session_dec_email, $key);

                if ($db_encrtypted_email != $session_en_email) {
                    
                    $_SESSION['session_id'] = "";
                    header("Location: /kenhavate/");
                }
                else {
                    return 0;
                }
            }
            else{

                $stmt = $con->prepare("SELECT s.*, ss.* FROM staff_table s INNER JOIN staff_sub_table ss ON s.staff_uuid = ss.staff_uuid WHERE s.session_id = ? LIMIT 1");
                $stmt->bind_param("s", $encrypt_session_id);
                $stmt->execute();
                $result = $stmt->get_result();
        
                if ($result->num_rows >= 1) {
    
                    //fetch the associative array of data in the db if reslts are found
                    $row = $result->fetch_assoc();
    
                    $db_encrtypted_KeNHA_email = $row['KeNHA_email'];
                    $db_encrtypted_personal_email = $row['personal_email'];
                
                    //will be used to fetch uuid for the dispaly in landing page table
                    $db_encrtypted_staff_uuid = $row['staff_uuid'];
                    $_SESSION['uuid'] = $db_encrtypted_staff_uuid;
    
                    //retrieve the stored email in session
                    $session_dec_email = $_SESSION['input_email'];
                    $session_en_email = encrypt($session_dec_email, $key);
                    
    
                    if (($db_encrtypted_KeNHA_email == $session_en_email) || ($db_encrtypted_personal_email == $session_en_email)) {
                        return 0;
                    }
                    else {
                    
                        $_SESSION['session_id'] = "";
                        header("Location: /kenhavate/");
                    }
                }
                else{
                    $_SESSION['session_id'] = "";
                    header("Location: /kenhavate/");
                }
                $stmt->close();
            }
            $stmt->close();
        }
        else {
            $_SESSION['session_id'] = "";
            header("Location: /kenhavate/");
        } 
    }
    
    //random string generating function
    function generateRandomUUID($length = 10, $con) {
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
    function generateOTP($length = 6, $con) {
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

            $stmt = $con->prepare("SELECT * FROM users_table WHERE OTP_code = ? LIMIT 1");
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

    function encryptData($data, $key) {
        $cipher = 'AES-128-CTR';
        $key = hex2bin(hash('sha256', $key)); // Convert the key to binary
    
        // Generate a random IV securely
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));
    
        // Encrypt the data with AES and return the encrypted string along with the IV
        $encryptedData = openssl_encrypt($data, $cipher, $key, OPENSSL_RAW_DATA, $iv);
        $result = base64_encode($iv . $encryptedData);
        return $result;
    }
    
    function decryptData($encryptedData, $key) {
        $cipher = 'AES-128-CTR';
        $key = hex2bin(hash('sha256', $key)); // Convert the key to binary
    
        // Decode the base64-encoded encrypted data to retrieve IV and encrypted content
        $decodedData = base64_decode($encryptedData);
    
        // Extract the IV from the data
        $ivlen = openssl_cipher_iv_length($cipher);
        $iv = substr($decodedData, 0, $ivlen);
    
        // Extract the encrypted content
        $encryptedContent = substr($decodedData, $ivlen);
    
        // Decrypt the data with AES and return the original string
        $decryptedData = openssl_decrypt($encryptedContent, $cipher, $key, OPENSSL_RAW_DATA, $iv);
        return $decryptedData;
    }    
      
    function encrypt($message, $key) {
        $encrypted = "";
        $key_length = strlen($key);

        for ($i = 0; $i < strlen($message); $i++) {
            $encrypted_char = ord($message[$i]) + ord($key[$i % $key_length]);
            $encrypted .= chr($encrypted_char);
        }

        return base64_encode($encrypted);
    }

    function decrypt($message, $key) {
        $message = base64_decode($message);
        $decrypted = "";
        $key_length = strlen($key);

        for ($i = 0; $i < strlen($message); $i++) {
            $decrypted_char = ord($message[$i]) - ord($key[$i % $key_length]);
            $decrypted .= chr($decrypted_char);
        }

        return $decrypted;
    }
    
    //random string generating function
    function generateIdeaUUID($length = 10, $con) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
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
            $encrypteduuid = encryptData($randomString, $encryptionKey);

            $stmt = $con->prepare("SELECT * FROM submitted_ideas WHERE idea_uuid = ? LIMIT 1");
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
    function generateUploadID($length = 10, $con) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
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
            $encrypteduuid = encryptData($randomString, $encryptionKey);

            $stmt = $con->prepare("SELECT * FROM submitted_ideas WHERE upload_id = ? LIMIT 1");
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
    function generateChallengeID($length = 10, $con) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
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
            $encrypteduuid = encryptData($randomString, $encryptionKey);

            $stmt = $con->prepare("SELECT * FROM replied_challenges WHERE upload_id = ? LIMIT 1");
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

    
    // Function to generate a unique challenge UUID
    function generate_challenge_uuid($con) {
        $base_uuid = "kenhavate/challenge/";
        $number = 1;

        do {
            $formatted_number = sprintf('%03d', $number); // Zero-padding to 3 digits
            $challenge_uuid = $base_uuid . $formatted_number;

            $encryptionKey = 'my-KeNHAsecret-passkey';
            $encrypted_challenge_uuid = encrypt($challenge_uuid, $encryptionKey);

            try {
                $stmt = $con->prepare("SELECT * FROM posted_challenges WHERE challenge_uuid = ? LIMIT 1");
                $stmt->bind_param("s", $encrypted_challenge_uuid);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows == 0) {
                    return $challenge_uuid; // Unique UUID found
                }
            } catch (Exception $e) {
                // Handle the database query exception here (e.g., log the error)
                // You might also want to throw an exception or return an error message
                return false;
            }

            $number++; // Increment the number and try again
        } while (true);
    }

    function accountTypeA() {
        return 'user';
    }

    function accountTypeB() {
        return 'expert';
    }

    function accountTypeC() {
        return 'deputy';
    }

    function accountStatusA() {
        return 'active';
    }

    function accountStatusB() {
        return 'temp_disabled';
    }

    function accountStatusC() {
        return 'perm_diabled';
    }



?>