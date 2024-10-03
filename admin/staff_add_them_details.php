<?php
session_start();

include("../auth_controller/requirement.php");
include("../admin/auth_controller/requirement.php");
include("../auth_controller/session.php");

$user_data = check_login_admin($con);

$missingFields = $_SESSION['missingFields'] ?? [];

if (!empty($missingFields)) {
    echo '<!DOCTYPE html>';
    echo '<html lang="en">';
    echo '<head>';
        echo '<meta charset="UTF-8">';
        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
        echo '<title>Fill in Form</title>';

        echo '<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">';
        echo '<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">';
        echo '<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">';
        echo '<link rel="manifest" href="/site.webmanifest">';
        
        echo '<!-- Include Bootstrap CSS -->';
        echo '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">';
        echo '<base href="/KeNHAVATE/">';
    echo '</head>';
    echo '<body>';
        echo '<div style="background-color: yellow; text-align: center; padding: 5px; position: sticky; top: 0px;">';
            echo '<h1 style="font-size: 20px;">KeNHAVATE ADMIN</h1>';
        echo '</div>';
        echo '<div class="container" style="justify-content: center; display: flex; flex-direction: column; padding: 15px 15px 50px">';
            echo '<h3 style="text-align: center; font-size: 20px;">Add your missing details</h3>';
            echo '<form id="missingDetailsForm" style="display: flex; flex-direction: column;">';

                foreach ($missingFields as $fieldName) {
                    echo '<div class="mb-3">';
                    echo '<label for="' . $fieldName . '" class="form-label">' . ucfirst(str_replace("_", " ", $fieldName)) . ':</label>';
                    echo '<input type="text" class="form-control" id="' . $fieldName . '" name="' . $fieldName . '">';
                    echo '</div>';
                }

                echo '<button id="submitForm" type="submit" class="btn btn-primary" style="align-self: center;">submit</button>';
            echo '</form>';
        echo '</div>';
        echo '<div style="background-color: lightgray; position: fixed; bottom: 0px; text-align: center; padding: 5px; display: flex; align-items: center; justify-content: center; width: 100%;">';
            echo '<h4 style="font-size: 14px;">Need Assistance?</h4>';
            echo '<h4 style="font-size: 14px;">Call/Text +254740252837</h4>';
        echo '</div>';
        echo '<!-- Include Bootstrap JavaScript -->';
        echo '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>';
        echo '<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>';
        echo '<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>';
        echo '<script src="admin/resources/update_admin_details.js"></script>';
    echo '</body>';
    echo '</html>';
}
?>
