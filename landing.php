<?php

  session_start();
  
  include("auth_controller/requirement.php");
  
  check_login($con);
  
  $_SESSION['first_form_processed'] = false;

  if (isset($_SESSION['error_message'])) {
    $error_message = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
  }
  else {
    $error_message = null;
  }

  if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
  }
  else {
    $success_message = null;
  }

  if (isset($_SESSION['uuid'])) {
    $user_uuid = $_SESSION['uuid'];
  }
  else {
    $user_uuid = null;
  }

  $input_values = isset($_SESSION['input_values']) ? $_SESSION['input_values'] : [];

  unset($_SESSION['input_values']);
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="canonical" href="http://KeNHAVATE/landing.php">
    <title>KeNHAVATE</title>

    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"/>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet"/>
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.1/mdb.min.css" rel="stylesheet"/>
    <base href="/KeNHAVATE/">

    <link rel="stylesheet" href="resources/index.css">
    <link rel="stylesheet" href="resources/landing.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />

    <script src="resources/countdown.min.js"></script>
</head>
<body>
    <div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true" style="align-items: center; justify-content: center;">
        <div class="modal-dialog" style="min-width: 225px; width: 350px;">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #d35353; color: white;">
                    <h5 class="modal-title" id="errorModalLabel" style="align-items: center; width: 100%; justify-content: center; display: flex;">warning!!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background-color: white;border-radius: 55%;"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger">
                        <?php echo $error_message; ?>
                    </div>
                </div>
                <div class="modal-footer" style="justify-content: center;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="background-color: #d35353; color: white;">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true" style="align-items: center; justify-content: center;">
        <div class="modal-dialog" style="min-width: 225px; width: 350px;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #3fdc3f; color: white;">
                <h5 class="modal-title" id="successModalLabel" style="align-items: center; width: 100%; justify-content: center; display: flex;">success!!</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background-color: white;border-radius: 55%;"></button>
            </div>
            <div class="modal-body">
            <div class="alert alert-success">
                <?php echo $success_message; ?>
            </div>
            </div>
            <div class="modal-footer" style="justify-content: center;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="background-color: #3fdc3f; color: white;">Close</button>
            </div>
        </div>
        </div>
    </div>

    <?php include 'resources/user_landing_header.php'; ?>

    <div class="row main_row" style="margin-top: 101px; width: 100%;">
        <div class="col-md-4 menu_holder" style="display: flex; justify-content: center; padding: 0px;">
            <div class="menu_show">
                <i class="fa-regular fa-circle-left hide_menu" style="font-size: 30px; color: black; position: absolute; top: 0px; right: 0px; padding: 15px; top: 106px;"></i>
            </div>

            <div class="nav flex-column nav-pills text-center" id="v-pills-tab" role="tablist" aria-orientation="vertical" style="position: fixed; top: 116px;">
                <a class="nav-link active" id="v-pills-home-tab" data-mdb-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true" style="display: flex; align-items: center; justify-content: space-between; font-size: 15px; gap: 8px;"><i class="fa-sharp fa-solid fa-house fa-flip"></i>Home<i class="fa-sharp fa-solid fa-circle-arrow-right fa-beat"></i></a>
                <a class="nav-link" id="v-pills-idea-tab" data-mdb-toggle="pill" href="#v-pills-idea" role="tab" aria-controls="v-pills-idea" aria-selected="false" style="display: flex; align-items: center; justify-content: space-between; font-size: 15px; gap: 8px;"><i class="fa-sharp fa-solid fa-lightbulb fa-flip"></i>Submit Idea<i class="fa-sharp fa-solid fa-circle-arrow-right fa-beat"></i></a>
                <a class="nav-link" id="v-pills-challenge-tab" data-mdb-toggle="pill" href="#v-pills-challenge" role="tab" aria-controls="v-pills-challenge" aria-selected="false" style="display: flex; align-items: center; justify-content: space-between; font-size: 15px; gap: 8px;"><i class="fa-sharp fa-solid fa-handshake fa-flip"></i>Join a Challenge<i class="fa-sharp fa-solid fa-circle-arrow-right fa-beat"></i></a>
                <a class="nav-link" id="v-pills-submission-tab" data-mdb-toggle="pill" href="#v-pills-submission" role="tab" aria-controls="v-pills-submission" aria-selected="false" style="display: flex; align-items: center; justify-content: space-between; font-size: 15px; gap: 8px;"><i class="fa-sharp fa-solid fa-clipboard-list fa-flip"></i>My Submissions<i class="fa-sharp fa-solid fa-circle-arrow-right fa-beat"></i></a>
                <a class="nav-link" id="v-pills-messages-tab" data-mdb-toggle="pill" href="#v-pills-messages" role="tab" aria-controls="v-pills-messages" aria-selected="false" style="display: flex; align-items: center; justify-content: space-between; font-size: 15px; gap: 8px;"><i class="fa-sharp fa-solid fa-envelope fa-flip"></i>Messages<i class="fa-sharp fa-solid fa-circle-arrow-right fa-beat"></i></a>
                <a href="/KeNHAVATE/auth_controller/logout.php" class="nav-link" role="tab" aria-controls="v-pills-log_out" aria-selected="false" style="display: flex; align-items: center; justify-content: space-between; font-size: 15px; gap: 8px;"><i class="fa-sharp fa-solid fa-right-from-bracket fa-bounce"></i>Log&nbsp;Out<i class="fa-sharp fa-solid fa-right-from-bracket fa-bounce"></i></a>
            </div>
        </div>

        <div class="col-md-8" style="padding: 0px 30px 40px 0px">
            <div class="tab-content" id="v-pills-tabContent">
                <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                    <h2 style="text-align: center;">HOME</h2>
                    <h4 style="text-align: center;">Idea submission process</h4>
                    <div class="flow_img_content"  style="position: relative; flex-direction: row; top: 0px; width: 100%; display: flex;">
                        <div class="flow_image" style="width: 100%;height: 100%; padding-right: 8px;">
                            <img src="img/home_landing.PNG" alt="" style="width: 100%; height: 100%; border-radius: 10px;">
                        </div>
                        <div class="flow_content" style="width: 50%; padding-left: 8px; z-index: 0;">
                            <div class="swiper mySwiper" style="width: 100%; height: 100%; border-radius: 6px;">
                                <div class="swiper-wrapper">
                                    <div class="swiper-slide" style="text-align: center; font-size: 18px; background: #fff; display: flex; justify-content: start; align-items: flex-start; flex-direction: column;">
                                        <div class="idea_title" style="background-color: #d8d801ed; padding: 20px 0px; width: 100%; font-weight: bold; font-size: 20px;">1. Idea Generation:</div>
                                        <div class="idea_content">
                                            This is the initial phase where individuals brainstorm and generate a wide range of creative ideas. The goal is to come up with a variety 
                                            of potential solutions to a problem or opportunities for improvement. Idea generation can be facilitated through brainstorming sessions, idea 
                                            workshops, online collaboration tools, or individual reflection. The aim is to encourage thinking outside the box and gathering diverse perspectives.
                                        </div>
                                    </div>
                                    <div class="swiper-slide" style="text-align: center; font-size: 18px; background: #fff; display: flex; justify-content: start; align-items: flex-start; flex-direction: column;">
                                        <div class="idea_title" style="background-color: #d8d801ed; padding: 20px 0px; width: 100%; font-weight: bold; font-size: 20px;">2. Idea Evaluation:</div>
                                        <div class="idea_content">
                                            In this phase, the generated ideas are assessed and evaluated based on predefined criteria. The evaluation process helps identify ideas that are most 
                                            feasible, effective, and aligned with our goals and resources. Various evaluation methods can be used, such as SWOT analysis (Strengths, 
                                            Weaknesses, Opportunities, Threats), cost-benefit analysis, risk assessment, and alignment with strategic objectives. The goal is to narrow down the 
                                            pool of ideas to those with the highest potential for success.
                                        </div>
                                    </div>
                                    <div class="swiper-slide" style="text-align: center; font-size: 18px; background: #fff; display: flex; justify-content: start; align-items: flex-start; flex-direction: column;">
                                        <div class="idea_title" style="background-color: #d8d801ed; padding: 20px 0px; width: 100%; font-weight: bold; font-size: 20px;">3. Idea Implementation:</div>
                                        <div class="idea_content">
                                            Once the most promising ideas are selected, the implementation phase begins. This involves turning the selected idea into a tangible project or 
                                            initiative. It includes planning, resource allocation, assigning responsibilities, setting timelines, and defining key performance indicators (KPIs) 
                                            to measure progress and success. Implementation may require collaboration across various departments within KeNHA.
                                        </div>
                                    </div>
                                    <div class="swiper-slide" style="text-align: center; font-size: 18px; background: #fff; display: flex; justify-content: start; align-items: flex-start; flex-direction: column;">
                                        <div class="idea_title" style="background-color: #d8d801ed; padding: 20px 0px; width: 100%; font-weight: bold; font-size: 20px;">4. Reward if the Idea is Approved:</div>
                                        <div class="idea_content">
                                            To motivate and incentivize contributors to participate actively in the innovation idea process, KeNHA will provide rewards for 
                                            approved ideas. Rewards can take various forms, such as monetary bonuses, recognition, promotions, or additional benefits. The rewards serve as a 
                                            way to acknowledge and appreciate the efforts of individuals who contributed valuable ideas. It also fosters a culture of innovation and 
                                            encourages ongoing participation.
                                        </div>
                                    </div>
                                </div>
                                <!--<div class="swiper-button-next"></div>-->
                                <!--<div class="swiper-button-prev"></div>-->
                                <div class="swiper-pagination" style="top: 99%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="v-pills-idea" role="tabpanel" aria-labelledby="v-pills-idea-tab" style="position: relative; width: 100%;">

                    <?php include 'resources/idea_form.php'; ?>

                </div>
                <div class="tab-pane fade" id="v-pills-challenge" role="tabpanel" aria-labelledby="v-pills-challenge-tab" style="position: relative; width: 100%;">

                    <?php include 'resources/challenge.php'; ?>

                </div>
                <div class="tab-pane fade" id="v-pills-submission" role="tabpanel" aria-labelledby="v-pills-submission-tab" style="position: relative; width: 100%;">

                    <?php include 'resources/my_submissions.php'; ?>

                </div>
                <div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab" style="position: relative; width: 100%;">

                    <?php include 'resources/my_messages.php'; ?>
                    
                </div>
            </div>
        </div>
    </div>


    <!-- MDB -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.1/mdb.min.js"></script>


    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>

    <script>
        var swiper = new Swiper(".mySwiper", {
            loop: true,
            autoplay: {
                delay: 5000,
            },
            pagination: {
                el: ".swiper-pagination",
                type: "progressbar",
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
        });

        swiper.el.addEventListener("mouseenter", function () {
            swiper.autoplay.stop();
        });

        swiper.el.addEventListener("mouseleave", function () {
            swiper.autoplay.start();
        });
    </script>
  
  
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <?php
        if (isset($error_message)) {
        echo "
            <script>
            $(document).ready(function() {
                $('#errorModal').modal('show');
            });
            </script>
        ";
        }
        
        if (isset($success_message)) {
        echo "
            <script>
            $(document).ready(function() {
                $('#successModal').modal('show');
            });
            </script>
        ";
        }
    ?>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.12/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

    <script src="resources/landing.js"></script>

    
    <script>
        $(document).ready(function () {
            $('.submit_challenge').on('click', function () {
                var challengeId = $.escapeSelector($(this).data('challenge-id'));
                var newChallengeId = $('#' + challengeId + '-challenge_id').val();
                var termsCheckInput = $('#' + challengeId + '-termsCheck');

                
                var formData = new FormData();

                var errorMessages = [];

                // Validate fields
                var fieldsToCheck = [
                    { input: $('#' + challengeId + '-solution'), message: "Solution Field is Empty" },
                    { input: $('#' + challengeId + '-motivation'), message: "Motivation Field is Empty" },
                    { input: $('#' + challengeId + '-cost'), message: "Cost Benefit Analysis Field is Empty" },
                    { input: $('#' + challengeId + '-supportDocs'), message: "PDF Field is Empty" },
                    { input: $('#' + challengeId + '-termsCheck'), message: "Terms of Service Field is Unchecked" }
                ];

                fieldsToCheck.forEach(function (field) {
                    var inputValue = field.input.val().trim();
                    if (field.input.attr('type') === 'checkbox' && !field.input.is(':checked')) {
                        errorMessages.push(field.message);
                        field.input.css('outline', '2px solid red');
                    } else if (inputValue === '') {
                        errorMessages.push(field.message);
                        field.input.css('border', '2px solid red');
                    } else {
                        field.input.css('border', '');
                        field.input.css('outline', '');
                    }
                });

                // Validate file input
                var supportDocsInput = $('#' + challengeId + '-supportDocs');
                var supportDocsFile = supportDocsInput[0].files[0];

                if (supportDocsFile) {
                    var maxFileSizeMB = 20;
                    var maxFileSizeBytes = maxFileSizeMB * 1024 * 1024;

                    formData.append('supportDocs', supportDocsFile);

                    formData.append('supportDocsName', supportDocsFile.name);

                    if (supportDocsFile.size > maxFileSizeBytes) {
                        errorMessages.push("File size should not exceed 2 MB");
                        supportDocsInput.css('border', '2px solid red');
                    } else {
                        supportDocsInput.css('border', '');
                    }
                }

                // Display errors if any
                if (errorMessages.length > 0) {
                    var errorMessage = "Error(s) :" + errorMessages.join(', ');

                    // Set the error message in the modal
                    $('#errorModal .alert-danger').text(errorMessage);

                    // Show the error modal
                    $('#errorModal').modal('show');
                    return;
                }

                // Rest of your code to handle the form submission and file upload
                formData.append('challengeId', challengeId);
                formData.append('newChallengeId', newChallengeId);
                formData.append('solution', fieldsToCheck[0].input.val().trim());
                formData.append('motivation', fieldsToCheck[1].input.val().trim());
                formData.append('cost', fieldsToCheck[2].input.val().trim());
                formData.append('supportDocs', supportDocsFile);
                formData.append('termsCheck', termsCheckInput.is(':checked') ? 'checked' : 'unchecked');

                $.ajax({
                    url: 'auth_controller/reply_upload_challenge.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        // Show the success modal
                        $('#successModal .alert-success').text(response);
                        $('#successModal').modal('show');
                        console.log(response);
                        

                        // Clear input fields on success
                        fieldsToCheck.forEach(function (field) {
                            field.input.val(''); // Clear the input field
                            field.input.css('border', ''); // Remove any error styling
                            field.input.css('outline', ''); // Remove any error styling for checkboxes
                        });
                    },
                    error: function (response) {
                        $('#errorModal .alert-danger').text('An error occurred during the request.');
                        $('#errorModal').modal('show');
                        console.log(response);
                    }
                });
            });
            
            function fetchChallenges() {
                $.ajax({
                    url: '/KeNHAVATE/fetch-uploaded-challenge?action=fetchPostedChallenges',
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        // Clear existing content in the containers
                        var challengesContainer = $('#challenges-container');
                        challengesContainer.html('');

                        if (data.length > 0) {
                            displayChallenges(data);
                        } else {
                            // Display a message if there are no challenges
                            challengesContainer.html('<p>No challenges currently</p>');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                        console.error(status);
                        console.error(xhr);
                    }
                });
            }

            function displayChallenges(challenges) {
                $.each(challenges, function (index, challenge) {
                    // Check if the deadline has passed
                    if (!hasDeadlinePassed(challenge.deadline)) {
                        displayChallenge(challenge);
                    }
                });
            }

            function hasDeadlinePassed(deadlineString) {
                // Parse the deadline string into a Date object
                var deadlineDate = parseDeadlineDate(deadlineString);
                var now = new Date();

                return now > deadlineDate;
            }

            function displayChallenge(challenge) {
                // Create a div for each challenge
                var challengeDiv = $('<div class="mb-3 form-check"></div>');

                // Create a radio button for the challenge
                var radio = $('<input class="form-check-input" type="radio" name="selected_challenge" value="' + challenge.challengeId + '" style="width: 10px!important; height: 24px; display: flex; align-items: center; justify-content: center;"> ');

                // Create a label for the radio button
                var label = $('<label class="form-check-label">' + challenge.title + '</label>');

                // Append radio and label to the challenge div
                challengeDiv.append(radio).append(label);

                // Append the challenge div to the challenges container
                $('#challenges-container').append(challengeDiv);

                // Attach a change event listener to the radio button
                radio.change(function () {
                    console.log(challenge.challengeId);
                    if ($(this).is(':checked')) {
                        var DetailsChallengesContainer = $('#detail-challenges-container');
                        var challengesContainer = $('#challenges-container');
                        DetailsChallengesContainer.show();
                        DetailsChallengesContainer.html('');
                        challengesContainer.hide();

                        $.ajax({
                            url: '/KeNHAVATE/fetch-uploaded-challenge?action=fetchChallengeDetails&challengeId=' + challenge.challengeId,
                            type: 'GET',
                            dataType: 'json',
                            success: function (details) {
                                displayChallengeDetails(details);
                            },
                            error: function (xhr, status, error) {
                                console.error(error);
                                console.error(status);
                                console.error(xhr);
                                alert(details);
                            }
                        });
                    }
                });
            }

            function displayChallengeDetails(details) {
                // Add detailed information to the form
                $.each(details, function (key, value) {
                    var challengeForm = $('<div class="mb-3" style="display: flex; flex-direction: column;"></div>');
                    var challengeTitle = $('<label class="challenge_title" style="text-align: center; font-weight: 700; font-size: 23px;">Title: ' + value.title + '</label>');
                    var challengeDescription = $('<div style="display: flex; justify-content: center; align-items: center; flex-direction: column;">' +
                                                    '<label style="font-weight: 700;">Description</label>' +
                                                    '<div>' + value.description + '</div>' +
                                                    '<div style="font-weight: 600;">' +
                                                        '<a href="view_doc?file='+ value.upload_name +'" target="_blank" style="text-decoration: underline; color: rebeccapurple;"><i class="fa-solid fa-file-arrow-down"></i> click here</a> to view and download the instruction file' +
                                                    '</div>' +
                                                 '</div>'                                                  
                                                );
                    var challengeDeadline_countdownSpan = $('<div class="row" style="width: 100%;">' +
                                                                '<div class="col-md-6 col-lg-6 col-12" style="font-weight: 700;">Deadline: ' + value.deadline + '</div>' +
                                                                '<div class="col-md-6 col-lg-6 col-12"></div>' +
                                                            '</div>'
                                                            );
                    var countdownSpan = $('<div class="countdown" style="color: red; font-weight: 700;"></div>');
                    var challengeUploadDate = $('<label style="text-align: center;">Upload Date: ' + value.day_uploaded + '</label>');

                    
                    var row_one = $('<div class="row" style="width: 100%; row-gap: 22px;">' +
                                        '<div class="col-md-6 col-lg-6 col-12">' +
                                            '<textarea class="form-control" rows="3" placeholder="Enter Solution Description"></textarea>' +
                                        '</div>' +
                                        '<div class="col-md-6 col-lg-6 col-12">' +
                                            '<textarea class="form-control" rows="3" placeholder="Enter Motivation Description"></textarea>' +
                                        '</div>' +
                                    '</div>'
                                    );

                    
                    var row_two = $('<div class="row" style="width: 100%; row-gap: 22px;">' +
                                        '<div class="col-md-6 col-lg-6 col-12">' +
                                            '<textarea class="form-control" rows="3" placeholder="Enter Cost Estimate Description"></textarea>' +
                                        '</div>' +
                                        '<div class="col-md-6 col-lg-6 col-12">' +
                                            '<input type="file" class="form-control-file" accept=".pdf" style="width: 100%;" id="upload_challenge_pdf">' +
                                        '</div>' +
                                    '</div>'
                                    );

                    
                    var row_three = $('<div class="row" style="width: 100%; row-gap: 22px;">' +
                                        '<div class="form-check"><input type="checkbox" class="form-check-input" id="declarationCheck" style="width: fit-content !important; display: flex; align-items: center; justify-content: center;"><label class="form-check-label" for="declarationCheck">I agree to the terms and conditions</label></div>' +
                                    '</div>'
                                    );
                    var buttons = $('<div class="main" style="display: flex; justify-content: space-around;">' +
                                        '<div class="b_one">' +
                                        '</div>' +
                                        '<div class="b_two">' +
                                            '<button type="button" class="btn btn-primary" data-challenge-uuid=' + value.challenge_uuid + '>submit</button>' +
                                        '</div>'
                                    );
                    var backButton = $('<button type="button" class="btn btn-primary">back</button>');


                    challengeDeadline_countdownSpan.find('.col-md-6:last-child').append(countdownSpan);
                    buttons.find('.b_one').append(backButton);
                    challengeForm.append(challengeTitle).append(challengeDeadline_countdownSpan).append(challengeUploadDate).append(challengeDescription).append(row_one).append(row_two).append(row_three).append(buttons);


                    $('#detail-challenges-container').append(challengeForm);

                    // Initialize countdown timer
                    initializeCountdown(countdownSpan, value.deadline);

                    backButton.on('click', function () {
                        closeForm();
                    });
                });
            }

            function initializeCountdown(countdownSpan, deadline) {
                // Parse the deadline string into a Date object
                var deadlineDate = parseDeadlineDate(deadline).getTime();

                // Update countdown in real-time
                updateCountdown();

                function updateCountdown() {
                    var now = new Date().getTime();
                    var timeRemaining = deadlineDate - now;

                    if (timeRemaining > 0) {
                        var days = Math.floor(timeRemaining / (1000 * 60 * 60 * 24));
                        var hours = Math.floor((timeRemaining % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        var minutes = Math.floor((timeRemaining % (1000 * 60 * 60)) / (1000 * 60));
                        var seconds = Math.floor((timeRemaining % (1000 * 60)) / 1000);

                        countdownSpan.html('Time remaining: ' + days + 'd ' + hours + 'h ' + minutes + 'm ' + seconds + 's');
                    } else {
                        countdownSpan.html('Deadline has passed');
                    }

                    // Update every second
                    setTimeout(updateCountdown, 1000);
                }
            }

            function parseDeadlineDate(deadlineString) {
                // Parse the deadline string into a Date object
                var parts = deadlineString.match(/(\w+), (\w+) (\d+), (\d+) - (\d+):(\d+) (\w+)/);
                var months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
                var monthIndex = months.indexOf(parts[2]);
                var year = parseInt(parts[4]);
                var hours = parseInt(parts[5]);
                var minutes = parseInt(parts[6]);
                if (parts[7] === "PM" && hours !== 12) {
                    hours += 12;
                }
                return new Date(year, monthIndex, parseInt(parts[3]), hours, minutes);
            }

            function closeForm() {
                $('#detail-challenges-container').hide();
                $('#challenges-container').show();
            }

            // Update the content initially
            fetchChallenges();

            // Periodically update the content every one minute (60000 milliseconds)
            setInterval(fetchChallenges, 60000);

            // Function to handle submission
            function handleSubmit(challengeUuid, clickedButton) {
                // Collect data from input fields
                var solutionDesc = $('textarea[placeholder="Enter Solution Description"]').val();
                var motivationDesc = $('textarea[placeholder="Enter Motivation Description"]').val();
                var costEstimateDesc = $('textarea[placeholder="Enter Cost Estimate Description"]').val();
                var fileInput = $('#upload_challenge_pdf');
                var selectedFiles = fileInput.prop('files');
                var declarationChecked = $('#declarationCheck').is(':checked');

                var errors = [];

                // Validate solutionDesc
                if (!solutionDesc.trim()) {
                    errors.push("Solution description field is empty");
                }

                // Validate motivationDesc
                if (!motivationDesc.trim()) {
                    errors.push("Motivation description field is empty");
                }

                // Validate costEstimateDesc
                if (!costEstimateDesc.trim()) {
                    errors.push("Cost estimate field is empty");
                }

                // Validate file input
                if (selectedFiles.length === 0) {
                    errors.push("No PDF selected");
                } else {
                    // Check if the selected file has a PDF extension
                    var allowedExtensions = /\.pdf$/i; // Use a regular expression
                    if (!allowedExtensions.test(selectedFiles[0].name)) {
                        errors.push("Only PDF files are accepted");
                    }

                    // Check file size
                    if (selectedFiles[0].size > 20 * 1024 * 1024) {
                        errors.push("The selected PDF exceeds the size limit of 20MB");
                    }
                }

                // Validate if terms and conditions are checked
                if (!declarationChecked) {
                    errors.push("The terms and conditions is unchecked");
                }

                // Displaying combined error message
                if (errors.length > 0) {
                    alert("Your submission has the following errors:\n" + errors.join(", "));
                    return;
                }


                // Prepare FormData object to send to PHP
                var formData = new FormData();
                formData.append('challengeUuid', challengeUuid);
                formData.append('solutionDesc', solutionDesc);
                formData.append('motivationDesc', motivationDesc);
                formData.append('costEstimateDesc', costEstimateDesc);
                formData.append('pdfFile', selectedFiles[0]);
                formData.append('declaration', declarationChecked);
                formData.append('action', 'replyChallenge');

                // Perform AJAX request to send data to PHP
                $.ajax({
                    url: '/KeNHAVATE/fetch-uploaded-challenge?action=replyChallenge', // Replace with the actual path
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        
                        $('textarea[placeholder="Enter Solution Description"]').val('');
                        $('textarea[placeholder="Enter Motivation Description"]').val('');
                        $('textarea[placeholder="Enter Cost Estimate Description"]').val('');
                        $('#upload_challenge_pdf').val('');
                        $('#declarationCheck').prop('checked', false);
                        
                        $('#successModal .alert-success').text("Upload Successful");
                        $('#successModal').modal('show');
                        
                        $('#detail-challenges-container').hide();
                        $('#challenges-container').show();
                    },
                    error: function (response) {
                        $('#errorModal .alert-danger').text("Upload Failed");
                        $('#errorModal').modal('show');
                    }
                });
            }

            // Event listener for the submit button
            $('#detail-challenges-container').on('click', '.b_two button', function () {
                var challengeUuid = $(this).data('challenge-uuid');
                handleSubmit(challengeUuid, this);
            });
            $('#upload_idea_form').on('click', '#upload_idea_time_out', function () {
                var button = $(this);
                
                setTimeout(function() {
                    button.prop('disabled', true); // Disable the button after 2 seconds

                    var count = 20; // Countdown timer in seconds

                    // Create a div for the countdown message
                    var countdownDiv = $('<div id="countdownMessage">You have <span id="countdown">' + count + '</span> seconds remaining.</div>');
                    $('body').append(countdownDiv);

                    // Function to update countdown
                    function updateNewCountdown() {
                        var countdownSpan = $('#countdown');
                        countdownSpan.text(count);
                        if (count > 0) {
                            count--;
                            setTimeout(updateNewCountdown, 1000); // Update countdown every second (1000 milliseconds)
                        } else {
                            $('#countdownMessage').remove(); // Remove the countdown message
                            button.prop('disabled', false); // Enable the button
                        }
                    }

                    updateNewCountdown(); // Start countdown
                }, 2000); // 2000 milliseconds = 2 seconds
            });

            

        });

    </script>


</body>
</html>