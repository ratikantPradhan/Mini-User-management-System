<?php
session_start();
include 'db.php'; // Ensure database connection

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer
require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);

    // Check if the email exists in the database
    $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Generate a random OTP
        $otp = rand(100000, 999999);

        // Check if OTP was sent recently (2-minute limit)
        if (!isset($_SESSION['otp_time']) || time() - $_SESSION['otp_time'] > 90) {
            $_SESSION['otp'] = $otp;
            $_SESSION['otp_time'] = time();
            $_SESSION['email'] = $email;

            // Send OTP via PHPMailer
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;

                $mail->Username = 'bikipp6@gmail.com'; // Use App Password
                $mail->Password = 'ocdz pyet vkaa ttsv'; // Use App Password, NOT real password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('bikipp6@gmail.com', 'Auth assessment Team');

                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = "Password Reset OTP";
                $mail->Body = "
                    <p style='font-size:16px;'>Hello,</p>

                    <p style='font-size:16px;'>Your OTP for password reset is:</p>

                    <h2 style='color:blue;'>$otp</h2>
                    <p style='font-size:14px;'>This OTP is valid for 90 seconds.</p>
                    <p>Thank you,<br>devX.js Team</p>

                    <p style='font-size:12px;'>This is an auto-generated email. Please do not reply.</p>

                ";

                if ($mail->send()) {
                    echo "<script>alert('OTP sent successfully! Check your email.'); window.location.href='otp.php';</script>";
                    exit;
                } else {
                    echo "<script>alert('Failed to send OTP. Try again.');</script>";
                    
                }
            } catch (Exception $e) {
                echo "<script>alert('Mail error: {$mail->ErrorInfo}');</script>";
            }
        } else {
            $remaining_time = 90 - (time() - $_SESSION['otp_time']);
            echo "<script>alert('Wait $remaining_time seconds before requesting a new OTP.');</script>";
        }
    } else {
        echo "<script>alert('Email not found!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <h1 class="text-2xl font-bold mb-4">Forgot Password?</h1>
        <p class="mb-6">Enter your email, and we'll send you an OTP to reset your password.</p>
        <form action="forget_password.php" method="POST">
            <div class="mb-4">
                <input type="email" name="email" placeholder="Enter your email" required
                    class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600">
            </div>
            <button type="submit"
                class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition duration-300">
                Send OTP
            </button>
        </form>
    </div>
</body>

</html>