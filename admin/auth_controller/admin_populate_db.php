<?php
    session_start();

    include("../../auth_controller/requirement.php");
    include("./requirement.php");

    $key = 'my-KeNHAsecret-passkey';

    $session_uuid = $_SESSION['uuid'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Retrieve and sanitize the form data
        $firstName = isset($_POST['firstName']) ? mysqli_real_escape_string($con, $_POST['firstName']) : 'not filled';
        $otherNames = isset($_POST['otherNames']) ? mysqli_real_escape_string($con, $_POST['otherNames']) : 'not filled';
        $idNumber = isset($_POST['idNumber']) ? mysqli_real_escape_string($con, $_POST['idNumber']) : 'not filled';
        $mobileNumber = isset($_POST['mobileNumber']) ? mysqli_real_escape_string($con, $_POST['mobileNumber']) : 'not filled';
        $staffNumber = isset($_POST['staffNumber']) ? mysqli_real_escape_string($con, $_POST['staffNumber']) : 'not filled';
        $personalEmail = isset($_POST['personalEmail']) ? mysqli_real_escape_string($con, $_POST['personalEmail']) : 'not filled';

        // Encrypt the form data
        $encryptedfirstName = encrypt($firstName, $key);
        $encryptedotherNames = encrypt($otherNames, $key);
        $encryptedidNumber = encrypt($idNumber, $key);
        $encryptedmobileNumber = encrypt($mobileNumber, $key);
        $encryptedpersonalEmail = encrypt($personalEmail, $key);
        $encryptedstaffNumber = encryptData($staffNumber, $key);
                
        //check if the email stored in the session is a valid one
        $stmt = $con->prepare("SELECT s.*, ss.* FROM staff_table s INNER JOIN staff_sub_table ss ON s.staff_uuid = ss.staff_uuid WHERE s.staff_uuid = ? LIMIT 1");
        $stmt->bind_param("s", $session_uuid);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows >= 1) {

            $row = $result->fetch_assoc();
            $db_dec_first_name = decrypt($row['first_name'], $key );
            $db_dec_other_names = decrypt($row['other_names'], $key );
            $db_dec_id_number = decrypt($row['id_number'], $key );
            $db_dec_mobile_number = decrypt($row['mobile_number'], $key );
            $db_dec_personal_email = decrypt($row['personal_email'], $key );
            $db_dec_staff_number = decryptData($row['staff_number'], $key );

            
            $encryptedfirstName = $db_dec_first_name !== "not filled" ? $row['first_name'] : $encryptedfirstName;
            $encryptedotherNames = $db_dec_other_names !== "not filled" ? $row['other_names'] : $encryptedotherNames;
            $encryptedidNumber = $db_dec_id_number !== "not filled" ? $row['id_number'] : $encryptedidNumber;
            $encryptedmobileNumber = $db_dec_mobile_number !== "not filled" ? $row['mobile_number'] : $encryptedmobileNumber;
            $encryptedpersonalEmail = $db_dec_personal_email !== "not filled" ? $row['personal_email'] : $encryptedpersonalEmail;
            $db_dec_staff_number = $db_dec_staff_number !== "not filled" ? $row['staff_number'] : $db_dec_staff_number;


            $stmt2 = $con->prepare("DELETE FROM users_table WHERE email = ?");
            $stmt2->bind_param("s", $encryptedpersonalEmail);

            // Perform data update into the admin table
            $stmt3 = $con->prepare("UPDATE staff_table SET first_name = ?, other_names = ?, id_number = ?, mobile_number = ?, personal_email = ? WHERE staff_uuid = ?");
            $stmt3->bind_param("ssssss", $encryptedfirstName, $encryptedotherNames, $encryptedidNumber, $encryptedmobileNumber, $encryptedpersonalEmail, $session_uuid);

            $stmt4 = $con->prepare("UPDATE staff_sub_table SET staff_number = ? WHERE staff_uuid = ?");
            $stmt4->bind_param("ss", $encryptedstaffNumber, $session_uuid);

            $stmt2_result = $stmt2->execute();
            $stmt3_result = $stmt3->execute();
            $stmt4_result = $stmt4->execute();

            if ($stmt3_result && $stmt4_result && $stmt2_result) {
                // Data insertion was successful
                echo "Data updated successfully!";
            } else {
                // Data insertion failed
                echo "Error: " . $con->error;
            }
        }
        else {
            // Data insertion failed
            echo "Error: " . $con->error;
        }
    } else {
        header("Location: /KeNHAVATE/error_file");
    }
?>
