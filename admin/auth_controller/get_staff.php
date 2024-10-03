<?php
    // Include database connection code or configuration
    include("../../auth_controller/requirement.php");
    include("./requirement.php");

    $key = 'my-KeNHAsecret-passkey';

    // Create an array to store the fetched data
    $staffData = array();

    // Fetch data from the database
    $stmt = $con->prepare("SELECT s.count_id, 
                                s.first_name, 
                                s.other_names, 
                                s.id_number, 
                                s.mobile_number, 
                                s.personal_email, 
                                ss.kenha_email,
                                ss.staff_number,
                                ss.directorate, 
                                s.gender, 
                                s.account_type
                            FROM staff_table s
                            INNER JOIN staff_sub_table ss ON s.staff_uuid = ss.staff_uuid");

    if ($stmt) {
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Decrypt the encrypted fields
                $decryptedFirstName = decrypt($row['first_name'], $key);
                $decryptedOtherNames = decrypt($row['other_names'], $key);

                $staffData[] = array(
                    'id' => $row['count_id'],
                    'first_name' => $decryptedFirstName,
                    'other_names' => $decryptedOtherNames,
                    'id_number' => decrypt($row['id_number'], $key),
                    'mobile_number' => decrypt($row['mobile_number'], $key),
                    'personal_email' => decrypt($row['personal_email'], $key),
                    'kenha_email' => decrypt($row['kenha_email'], $key),
                    'staff_number' => decryptData($row['staff_number'], $key), // Decrypt kenha_email
                    'directorate' => decrypt($row['directorate'], $key),   // Decrypt directorate
                    'gender' => $row['gender'],
                    'account_type' => decrypt($row['account_type'], $key),
                );
            }
        }
        
        // Close the prepared statement
        $stmt->close();
    }

    // Close the database connection
    $con->close();

    // Return the data as a JSON response
    header('Content-Type: application/json');
    echo json_encode($staffData);
?>
