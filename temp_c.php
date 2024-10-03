

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Idea Rejection Notification</title>
    <style>
        /* Your CSS styles here */
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffd966;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background-color: #333;
            color: #ffd966;
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid #fff;
        }
        h1 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .content {
            padding: 30px;
        }
        p {
            margin-bottom: 20px;
            line-height: 1.6;
            color: #555;
        }
        strong {
            color: #000;
        }
        .signature {
            margin-top: 30px;
            font-style: italic;
            text-align: center;
            color: #555;
        }
        .footer {
            background-color: #333;
            color: #ffd966;
            text-align: center;
            padding: 15px;
            border-top: 1px solid #fff;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>KeNHAVATE Portal</h1>
        </div>
        <div class="content">
            <p>We regret to inform you that your idea submission titled: <strong>' . $title . '</strong>, has been reviewed by the committee and unfortunately, it has not been selected to proceed to the implementation stage since it did not meet our criteria for implementation.</p>
            <p><strong>Idea Description:</strong> ' . $brief_description . '</p>
            <p><strong>Problem Statement:</strong> ' . $problem_statement . '</p>
            <p>We appreciate your participation and the effort you put into submitting your idea. Please do not be discouraged, as we encourage you to continue exploring new ideas and opportunities for innovation.</p>
            <p>Thank you for your valuable contribution.</p>
            <div class="signature">Best regards,<br>KeNHAVATE Management Team</div>
        </div>
        <div class="footer">
            This is an automated message. Please do not reply.
        </div>
    </div>
</body>
</html>
