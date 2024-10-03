<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admit Admin Form</title>

    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1>Admit Admin</h1>
        <form id="adminAdmissionForm">
            <div class="mb-3">
                <label for="firstName" class="form-label">First Name:</label>
                <input type="text" class="form-control" id="firstName" name="firstName">
            </div>
            <div class="mb-3">
                <label for="otherNames" class="form-label">Other Names:</label>
                <input type="text" class="form-control" id="otherNames" name="otherNames">
            </div>
            <div class="mb-3">
                <label for="idNumber" class="form-label">ID Number:</label>
                <input type="text" class="form-control" id="idNumber" name="idNumber">
            </div>
            <div class="mb-3">
                <label for="mobileNumber" class="form-label">Mobile Number:</label>
                <input type="text" class="form-control" id="mobileNumber" name="mobileNumber">
            </div>
            <div class="mb-3">
                <label for="staffNumber" class="form-label">Staff Number:</label>
                <input type="text" class="form-control" id="staffNumber" name="staffNumber">
            </div>
            <div class="mb-3">
                <label for="personalEmail" class="form-label">Personal Email:</label>
                <input type="email" class="form-control" id="personalEmail" name="personalEmail">
            </div>
            <div class="mb-3">
                <label for="kenhaEmail" class="form-label">Kenha Email:</label>
                <input type="email" class="form-control" id="kenhaEmail" name="kenhaEmail" required>
            </div>
            <div class="mb-3">
                <label for="directorate" class="form-label">Directorate:</label>
                <select class="form-select" id="directorate" name="directorate" required>
                    <option value="Corporate Services (Administrative Services)">Corporate Services (Administrative Services)</option>
                    <option value="Corporate Services (Finance & Accounting)">Corporate Services (Finance & Accounting)</option>
                    <option value="Corporate Services (Human Resource Management)">Corporate Services (Human Resource Management)</option>
                    <option value="Corporate Services (ICT)">Corporate Services (ICT)</option>
                    <option value="Development (Construction & In-house Supervision)">Development (Construction & In-house Supervision)</option>
                    <option value="Development (Development)">Development (Development)</option>
                    <option value="Development (Special Projects)">Development (Special Projects)</option>
                    <option value="Highway Design & Safety (Environmental & Social Safeguards)">Highway Design & Safety (Environmental & Social Safeguards)</option>
                    <option value="Highway Design & Safety (Highway Design & Engineering Training)">Highway Design & Safety (Highway Design & Engineering Training)</option>
                    <option value="Highway Design & Safety (Highway Safety)">Highway Design & Safety (Highway Safety)</option>
                    <option value="Highway Design & Safety (Structure Design)">Highway Design & Safety (Structure Design)</option>
                    <option value="Highway Design & Safety (Survey)">Highway Design & Safety (Survey)</option>
                    <option value="Maintenance (Axle Load Control)">Maintenance (Axle Load Control)</option>
                    <option value="Maintenance (Maintenance)">Maintenance (Maintenance)</option>
                    <option value="Maintenance (Trunk & Regional Network Co-ordination)">Maintenance (Trunk & Regional Network Co-ordination)</option>
                    <option value="Planning, Research & Compliance (Planning & Road Management)">Planning, Research & Compliance (Planning & Road Management)</option>
                    <option value="Planning, Research & Compliance (Quality Assurance)">Planning, Research & Compliance (Quality Assurance)</option>
                    <option value="Planning, Research & Compliance (Research, Corporate Communication)">Planning, Research & Compliance (Corporate Communication)</option>
                    <option value="Planning, Research & Compliance (Research, Innovation & Knowledge Management)">Planning, Research & Compliance (Research, Innovation & Knowledge Management)</option>
                    <option value="Planning, Research & Compliance (Risk Management & Business Process Reengineering)">Planning, Research & Compliance (Risk Management & Business Process Reengineering)</option>
                    <option value="Planning, Research & Compliance (Strategic, Budget & Economic Planning)">Planning, Research & Compliance (Strategic, Budget & Economic Planning)</option>
                    <option value="Public Private Partnership (Public Private Construction)">Public Private Partnership (Public Private Construction)</option>
                    <option value="Public Private Partnership (Public Private Operations & Maintenance)">Public Private Partnership (Public Private Operations & Maintenance)</option>
                    <option value="Public Private Partnership (Public Private Preparatory)">Public Private Partnership (Public Private Preparatory)</option>
                    <option value="Supply Chain Management Department">Supply Chain Management Department</option>
                    <option value="corporation Secretary & Legal Services Department">corporation Secretary & Legal Services Department</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="gender" class="form-label">Gender:</label>
                <select class="form-select" id="gender" name="gender" required>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="accountType" class="form-label">Account Type:</label>
                <select class="form-select" id="accountType" name="accountType" required>
                    <option value="user">User</option>
                    <option value="deputy">Deputy</option>
                    <option value="expert">Expert</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Admit Staff</button>
        </form>
        <!-- Table to display data -->
        <h2>Admin Data</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Other Names</th>
                    <th>ID Number</th>
                    <th>Mobile Number</th>
                    <th>Personal Email</th>
                    <th>Kenha Email</th>
                    <th>Staff Number</th>
                    <th>Directorate</th>
                    <th>Gender</th>
                    <th>Account Type</th>
                </tr>
            </thead>
            <tbody id="adminData">
                <!-- Data will be inserted here -->
            </tbody>
        </table>
    </div>

    <!-- Include Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Include jQuery for AJAX -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // Function to fetch and display admin data
        function fetchAdminData() {
            $.ajax({
                type: 'GET', // Use GET to fetch data
                url: '/KeNHAVATE/get-all-admin', // Replace with the actual URL of your PHP script
                success: function (data) {
                    // Clear existing table data
                    $('#adminData').empty();

                    // Append new rows to the table
                    $.each(data, function (index, item) {
                        $('#adminData').append(
                            '<tr>' +
                            '<td>' + item.id + '</td>' +
                            '<td>' + item.first_name + '</td>' +
                            '<td>' + item.other_names + '</td>' +
                            '<td>' + item.id_number + '</td>' +
                            '<td>' + item.mobile_number + '</td>' +
                            '<td>' + item.personal_email + '</td>' +
                            '<td>' + item.kenha_email + '</td>' +
                            '<td>' + item.staff_number + '</td>' +
                            '<td>' + item.directorate + '</td>' +
                            '<td>' + item.gender + '</td>' +
                            '<td>' + item.account_type + '</td>' +
                            '</tr>'
                        );
                    });
                },
                
            error: function (xhr, status, error) {
                // Handle errors and display the error message
                var errorMessage = "Error: " + error + "\nStatus: " + status + "\nResponse Text: " + xhr.responseText;
                console.error(errorMessage);

                // You can also display the error message on the page, e.g., in an alert
                alert(errorMessage);
            }
            });
        }

        // Execute the function when the page loads
        $(document).ready(function () {
            // Fetch and display admin data on page load
            fetchAdminData();

            $('#adminAdmissionForm').submit(function (e) {
                e.preventDefault();

                // Serialize the form data
                var formData = $(this).serialize();

                $.ajax({
                    type: 'POST',
                    url: '/KeNHAVATE/admit-admin', // Replace with the actual URL of your PHP script
                    data: formData,
                    success: function (response) {
                        // Handle the response from the server, e.g., show a success message
                        console.log(response);

                        // Fetch and display admin data after successful submission
                        fetchAdminData();
                    },
                    error: function () {
                        // Handle errors, e.g., show an error message
                        console.error(response);
                    }
                });
            });
        });
    </script>
</body>
</html>
