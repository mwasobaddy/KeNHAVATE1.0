<?php
    if (isset($_GET['file'])) {
        $fileName = $_GET['file'];
        
        $fileDirectory1 = './uploaded_ideas/';
        $fileDirectory2 = './uploaded_challenges/';

        $filepath1 = $fileDirectory1 . $fileName;
        $filepath2 = $fileDirectory2 . $fileName;

        if (file_exists($filepath1)) {
            $filepath = $filepath1;
        } elseif (file_exists($filepath2)) {
            $filepath = $filepath2;
        } else {
            header("Location: /KeNHAVATE/error_404");
            exit;
        }

        // For debugging, show the full file path
        echo "Full File Path: " . $filepath;

        // Set the correct content type header for PDF
        header('Content-type: application/pdf');

        // Set other headers for correct file handling
        header('Content-Transfer-Encoding: binary');
        header('Accept-Ranges: bytes');
        header('Content-Disposition: inline; filename="' . $fileName . '"');

        // Read and output the file
        readfile($filepath);
    } else {
        header("Location: /KeNHAVATE/error_404");
    }
?>
