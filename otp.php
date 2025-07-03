<?php
session_start();
include 'db.php'; // Include database connection

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';
require 'phpmailer/Exception.php';

// Check if user is logged in and has valid session
if (!isset($_SESSION['email'])) {
echo "<script>
    alert('Unauthorized access!');
    window.location.href = 'forget_password.php';
</script>";
exit;
}

// Check if OTP is expired (90 seconds)
$otp_expired = isset($_SESSION['otp_time']) && (time() - $_SESSION['otp_time'] > 90);
if ($otp_expired) {
unset($_SESSION['otp']); // Remove expired OTP
unset($_SESSION['otp_time']);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
if (isset($_POST['verify'])) {
$user_otp = trim($_POST['otp']);

if (!$otp_expired && isset($_SESSION['otp']) && $_SESSION['otp'] == $user_otp) {
unset($_SESSION['otp']); // OTP used, remove it
unset($_SESSION['otp_time']);

echo "<script>
    alert('OTP verified successfully!');
    window.location.href = 'reset_password.php';
</script>";
exit;
} else {
echo "<script>
    alert('Invalid or expired OTP!');
</script>";
}
}

if (isset($_POST['resend']) && $otp_expired) {
// Generate new OTP
$otp = rand(100000, 999999);
$_SESSION['otp'] = $otp;
$_SESSION['otp_time'] = time();

$mail = new PHPMailer(true);
try {
$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'bikipp6@gmail.com';
$mail->Password = 'ocdz pyet vkaa ttsv';
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port = 587;

$mail->setFrom('bikipp6@gmail.com', 'Auth assessment Team');
$mail->addAddress($_SESSION['email']);
$mail->isHTML(true);
$mail->Subject = "New Resended OTP for Password Reset";
$mail->Body = "<p>Your new OTP for password reset is:</p>
<h2 style='color:blue;'>$otp</h2>
<p>This OTP is valid for 90 seconds.</p>";

if ($mail->send()) {
echo "<script>
    alert('New OTP sent successfully!');
    window.location.href = 'otp.php';
</script>";
exit;
} else {
echo "<script>
    alert('Failed to send OTP. Try again later.');
</script>";
}
} catch (Exception $e) {
echo "<script>
    alert('Mail error: {$mail->ErrorInfo}');
</script>";
}
}
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <h1 class="text-2xl font-bold mb-4">Verify OTP</h1>
        <p class="mb-6">Enter the OTP sent to your email: <b><?= $_SESSION['email'] ?></b></p>

        <form action="otp.php" method="POST">
            <div class="mb-4">
                <input type="text" name="otp" placeholder="Enter OTP" required
                    class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600">
            </div>
            <button type="submit" name="verify"
                class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition duration-300">
                Verify OTP
            </button>
        </form>

        <form action="otp_verification.php" method="POST" class="mt-4">
            <button type="submit" name="resend"
                class="w-full bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition duration-300"
                <?= !$otp_expired ? 'disabled class="opacity-50 cursor-not-allowed"' : '' ?>>
                Resend OTP
            </button>
        </form>

        <p class="text-sm text-gray-500 mt-4">
            OTP expires in <span id="countdown">90</span> seconds.
        </p>
    </div>

    <script>
        let countdown = <?= isset($_SESSION['otp_time']) ? max(0, 90 - (time() - $_SESSION['otp_time'])) : 90 ?>;
        let countdownElem = document.getElementById("countdown");

        function updateCountdown() {
            if (countdown > 0) {
                countdown--;
                countdownElem.textContent = countdown;
                setTimeout(updateCountdown, 1000);
            }
        }

        updateCountdown();
    </script>
</body>

</html>