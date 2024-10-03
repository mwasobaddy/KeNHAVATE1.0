<?php

  session_start();
  
  include("auth_controller/requirement.php");
  
  $_SESSION['session_id'] = null;

  $_SESSION['db_dec_staff_uuid'] = null;
  $_SESSION['db_dec_uuid'] = null;

  //session email
  if (isset($_SESSION['input_email'])) {
    $enc_input_email = $_SESSION['input_email'];
  }
  elseif (!isset($_SESSION['input_email'])) {
    $_SESSION['input_email'] = null;
  }

  if (isset($_SESSION['error_message'])) {
    $error_message = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
    unset($_SESSION['success_message']);
  }
  elseif (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
    unset($_SESSION['error_message']);
  }
  else{
    $error_message = null;
    $success_message = null;
  }
  
  if (isset($_GET['reset_signup_form_processed'])) {
    $_SESSION['signup_form_processed'] = false;
  }
  elseif (isset($_GET['set_signup_form_processed_true'])) {
    $_SESSION['signup_form_processed'] = true;
  }
  
  if (isset($_GET['reset_first_form_processed'])) {
    $_SESSION['first_form_processed'] = false;
  }
  
  if (isset($_GET['reset_dont_have_an_account'])) {
    $_SESSION['dont_have_an_account'] = false;
  }
  elseif (isset($_GET['set_dont_have_an_account_true'])) {
    $_SESSION['dont_have_an_account'] = true;
  }

  $first_form_processed = isset($_SESSION['first_form_processed']) && $_SESSION['first_form_processed'];

  $signup_form_processed = isset($_SESSION['signup_form_processed']) && $_SESSION['signup_form_processed'];

  $dont_have_an_account  = isset($_SESSION['dont_have_an_account']) && $_SESSION['dont_have_an_account'];

  $input_values = isset($_SESSION['input_values']) ? $_SESSION['input_values'] : [];

  unset($_SESSION['input_values']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="canonical" href="http://KeNHAVATE/index.php">
  <title>KeNHAVATE</title>

  <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
  <link rel="manifest" href="/site.webmanifest">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
  <base href="/KeNHAVATE/">

  <link rel="stylesheet" href="resources/index.css">


</head>
<body>
  <div class="body_container">
    <!-- Error Modal -->
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

    <!-- Success Modal -->
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

    <?php include 'resources/user_index_header.php'; ?>

    <?php include 'resources/user_index_signup.php'; ?>

    <?php include 'resources/user_index_login.php'; ?>
      
    <div class="hero">
      <video id="myVideo" class="pc" style="width: 100%; border-radius: 10px;" autoplay muted>
        <source src="img/VID-20240311-WA0004.mp4" type="video/mp4">
        Your browser does not support the video tag.
      </video>
      <div class="slidder">

        <!-- Swiper -->
        <div class="swiper mySwiper" style=" border-radius: 10px;">
          <h2 style="text-align: center; color: #ffffff; display: none;">Key Features</h2>
          <div class="animation-container" style="z-index: -10; position: absolute; display: none; width: 100%;">
            <div class="overlay_bg_black" style="position: absolute; background-color: #9b9b9bc4; width: 100%; height: 150%;"></div>
              <img src="img/innovation_bg.png" alt="" style="top: 15px; position: absolute; z-index: -5;">
          </div>
          <div class="swiper-wrapper">
            <div class="swiper-slide">
              <img src="img/Capture.PNG" alt="" style="width: 45%; height: 99%;">
              <p>Together, let's accelerate innovations to the future!&#x1F4A1;</p>
            </div>
            <div class="swiper-slide maint">
              <img src="img/maintainance3.png" alt="" style="width: 45%; height: 75%;">
              <p>Together, let's drive to sustainable roads!&#128679;</p>
            </div>
            <div class="swiper-slide">
              <img src="img/climate.jpg" alt="" style="width: 45%;">
              <p>Together, let's express ways to a green future!&#127795;</p>
            </div>
            <div class="swiper-slide">
              <img src="img/bilboard.jpg" alt="" style="width: 45%;">
              <p>Together, let's be visible to the top!&#128176;</p>
            </div>
          </div>
          <div class="swiper-pagination"></div>
        </div>
        <!--<img src="img/sample.jpg" alt=""> -->
      </div>
    </div>
    
    <?php include 'resources/aboutus.php'; ?>

    <?php include 'resources/contactus.php'; ?>

    <?php include 'resources/innovation_areas.php'; ?>

    <?php include 'resources/customer.php'; ?>

    <?php include 'resources/innovation_challenge.php'; ?>

    <?php include 'resources/terms_and_conditions.php'; ?>

    <?php include 'resources/help.php'; ?>

    <?php include 'resources/quality.php'; ?>

    <?php include 'resources/road.php'; ?>

    <?php include 'resources/tech.php'; ?>

    <?php include 'resources/climate.php'; ?>

    <?php include 'resources/value.php'; ?>

    <?php include 'resources/revenue.php'; ?>

    <?php include 'resources/footer.php'; ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>

  <script>
    var swiper = new Swiper(".mySwiper", {
      loop: true,
      grabCursor: true,
      pagination: {
        el: ".swiper-pagination",
        dynamicBullets: true,
      },
      autoplay: {
        delay: 3000, // Change the delay to 3000 milliseconds (6 seconds)
      },
    });
  </script>
  
  <script>
      var swiper = new Swiper(".helpSwipper", {
          pagination: {
              el: ".help-pagination",
              clickable: true,
              renderBullet: function (index, className) {
                  return '<span class="' + className + '" style="height: 16px; width: 16px; display: flex; align-items: center; justify-content: center; color: white !important;">' + (index + 1) + '</span>';
              },
          },
      });
  </script>
  

  <script>
    function startCountdown() {
        var seconds = 99;
        var countdownElement = document.getElementById("countdown");
        var resendButton = document.querySelector("input[name=resend]");

        resendButton.disabled = true;
        resendButton.style.backgroundColor = "#1c1b1b57";

        var countdownInterval = setInterval(function() {
            countdownElement.textContent = seconds;
            seconds--;

            if (seconds < 0) {
                clearInterval(countdownInterval);
                resendButton.disabled = false;
                resendButton.style.backgroundColor = "#4b4b4b";
            }
        },1000);
    }

    document.addEventListener("DOMContentLoaded", function() {
        var overlayOTP = document.querySelector(".overlay_OTP");
        if (overlayOTP.classList.contains("active")) {
            startCountdown();
        }
    });
  </script>

  <script>
      document.addEventListener("DOMContentLoaded", function() {
          const resendButton = document.getElementById("resendBtn");
          const otpInput = document.querySelector("input[name=OTP_code]");
          const otpForm = document.getElementById("otpForm");

          resendButton.addEventListener("click", function() {
              if (otpInput.value.trim() === "") {
                  // Temporarily remove the 'required' attribute and submit the form
                  otpInput.removeAttribute("required");
                  otpForm.submit();
              }
          });
      });
  </script>
  
  
  <!-- Bootstrap JavaScript and Popper.js -->
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

  <script src="https://kit.fontawesome.com/7a67a52733.js" crossorigin="anonymous"></script>
  <script src="resources/index.js"></script>
  <script>
        $(document).ready(function () {
            
          function UpdateChallenges() {            
            $.ajax({
              url: '/KeNHAVATE/fetch-uploaded-challenge?action=UpdateChallenges',
              type: 'GET',
              success: function (data) {
                  if (data.length === 0) {
                    $('#challenge_details').empty().append('<h4 style="text-align: center;">No Active Challenges</h4>');
                  }
                  else {
                      $('#challenge_details').empty();

                      // Iterate through the data
                      data.forEach(function (idea) {
      
                          // Generate a new row and append it to the table
                          var newChallenge =
                            '<h4 style="text-align: center;">Challenge Title</h4>' +
                            '<p style="text-align: center;">' + idea.title + '</p>' +
                            '<h4 style="text-align: center;">Challenge Description</h4>' +
                            '<p>' + idea.description + '.The deadline for the described challenge is on, ' + idea.deadline + '</p>';
                          $('#challenge_details').append(newChallenge);
                      });
                  }
              },
              error: function (xhr, status, error) {
                  console.error(xhr.responseText); // Log the full server response
                  console.error(error); // Log the error message
              }
            })
          }
          function UpdatePast5Challenges() {            
            $.ajax({
              url: '/KeNHAVATE/fetch-uploaded-challenge?action=UpdatePast5Challenges',
              type: 'GET',
              success: function (data) {
                  if (data.length === 0) {
                    $('#past_challenges').empty().append('<h4 style="text-align: center;">No Past Challenges</h4>');
                  }
                  else {
                      $('#past_challenges').empty();

                      // Iterate through the data
                      data.forEach(function (idea) {
      
                          // Generate a new row and append it to the table
                          var newChallenge =
                            '<p style="text-align: center;">' + idea.title + '</p>';
                          $('#past_challenges').append(newChallenge);
                      });
                  }
              },
              error: function (xhr, status, error) {
                  console.error(xhr.responseText); // Log the full server response
                  console.error(error); // Log the error message
              }
            })
          }

          // Update the content initially
          UpdateChallenges();
          UpdatePast5Challenges();

          // Periodically update the content every one minute (60000 milliseconds)
          setInterval(UpdateChallenges, 60000);
          setInterval(UpdatePast5Challenges, 60000);

        });

    </script>


<script>
        const video = document.getElementById('myVideo');

        let reverse = false;
        let reverseInterval;

        video.addEventListener('ended', () => {
            reverse = true;
            playReverse();
        });

        video.addEventListener('play', () => {
            if (reverse) {
                video.pause();
                playReverse();
            }
        });

        function playReverse() {
            clearInterval(reverseInterval);
            reverseInterval = setInterval(() => {
                if (video.currentTime <= 0) {
                    clearInterval(reverseInterval);
                    reverse = false;
                    video.currentTime = 0;
                    video.play();
                } else {
                    video.currentTime -= 0.1;
                }
            }, 1000 / 60); // 60fps for smoother reverse playback
        }

        video.play();
    </script>

</body>
</html>
