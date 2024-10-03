<?php
if (isset($_GET['file'])) {
    $fileName = $_GET['file'];
    $filePath = "../uploaded_ideas/" . $fileName; // Path to the selected PDF file

    if (file_exists($filePath)) {
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . $fileName . '"');
        readfile($filePath); // Output the contents of the PDF file
    } else {
        echo 'File not found.';
    }
} else {
    echo 'Invalid request.';
}
?>
