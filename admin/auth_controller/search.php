<?php
session_start();

include("./requirement.php");

// Retrieve the search query from the GET request
if (isset($_GET['query'])) {

    $query = $mysqli->real_escape_string($_GET['query']); // Ensure safe input

    

    // Perform a database query to fetch data from replied_challenges
    
    $stmt = $con->prepare("SELECT * FROM replied_challenges WHERE challenge_uuid = ?");
    
    $stmt = $con->prepare("
        SELECT column_name FROM table1 WHERE column1 LIKE ?
        UNION
        SELECT column_name FROM table2 WHERE column2 LIKE ?
        UNION
        SELECT column_name FROM table3 WHERE column3 LIKE ?
    ");
    $stmt->bind_param("s", $query);
    $stmt->execute();
    $result = $stmt->get_result();

    // Generate HTML for the search results
    if ($result) {
        if ($result->num_rows > 0) {
            echo '<ul>';
            while ($row = $result->fetch_assoc()) {
                echo '<li>' . htmlspecialchars($row['column_name']) . '</li>';
            }
            echo '</ul>';
        } else {
            echo 'No results found.';
        }
        $result->free();
    } else {
        echo 'Query error: ' . $mysqli->error;
    }

    // Close the prepared statement
    $stmt->close();
} else {
    echo 'Invalid request.';
}

// Close the database connection
$mysqli->close();
?>
