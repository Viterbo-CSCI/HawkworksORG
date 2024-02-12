<?php
require 'vendor/autoload.php';
require 'core/config.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'db.php'; // Your database connection file

$username = $_POST['username'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

// Generate a unique token for email confirmation
$token = bin2hex(random_bytes(16));

// Insert user into the database with the token
$stmt = $conn->prepare("INSERT INTO users (username, password, email, token) VALUES (?, ?, ?, ?)");
$stmt->execute([$username, $password, $email, $token]);
$userId = $conn->lastInsertId();

// Create a new PHPMailer instance
$mail = new PHPMailer();

try {
    // Server settings
    $mail->isSMTP();
    $mail->SMTPDebug  = 1;  
    $mail->Host = 'smtp.office365.com';
    $mail->SMTPAuth = true;
    $mail->Username = SMTP_USER;
    $mail->Password = SMTP_PASS;
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    // Recipients
    $mail->setFrom('vitcsci@gmail.com', 'Mailer');
    $mail->addAddress($email, $username); // Add a recipient

    // Content
    $mail->IsHTML(true);
    $mail->AddAddress($email, "recipient-name");
    $mail->SetFrom("egweinberg@viterbo.edu", "from-name");
    $mail->AddReplyTo("egweinberg@viterbo.edu", "reply-to-name");
    $mail->isHTML(true);
    $mail->Subject = 'Registration Confirmation';
    $mail->Body    = "Thank you for registering. Please click <a href='http://localhost/confirm_registration.php?token=$token'>this link</a> to confirm your registration.";

    $mail->send();
    echo 'Registration successful, confirmation email sent.';
} catch (Exception $e) {
    echo "Registration successful, but the confirmation email could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>
