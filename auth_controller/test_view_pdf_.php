<?php
$folderPath = "../uploaded_ideas"; // Path to the folder containing PDF files

// Get an array of PDF files in the folder
$pdfFiles = glob($folderPath . '/*.pdf');

// Create the table
echo '<table border="1">';
echo '<thead>';
echo '<tr><th>File Name</th><th>Action</th></tr>';
echo '</thead>';
echo '<tbody>';

foreach ($pdfFiles as $pdfFile) {
    $fileName = basename($pdfFile);
    echo '<tr>';
    echo '<td>' . $fileName . '</td>';
    echo '<td><a href="view_pdf.php?file=' . urlencode($fileName) . '">View</a></td>';
    echo '</tr>';
}

echo '</tbody>';
echo '</table>';
?>
