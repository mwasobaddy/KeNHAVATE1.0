<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Email</title>
    <style>
        /* Custom styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #333;
        }
        p {
            color: #555;
            margin-bottom: 20px;
        }
        .otp-code {
            font-size: 24px;
            color: #ffcc00;
            margin-bottom: 30px;
        }
        .btn {
            background-color: #ffcc00;
            color: #333;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            display: inline-block;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>OTP Verification</h2>
        <p>Please use the following OTP to verify your email address:</p>
        <div class="otp-code">123456</div>
        <p>If you didn't request this OTP, please ignore this email.</p>
        <a href="#" class="btn">Verify Email</a>
    </div>
</body>
</html>
