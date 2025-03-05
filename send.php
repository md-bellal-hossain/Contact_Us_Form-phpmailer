<?php
//Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Validate required fields
    $required_fields = ['fullName', 'email', 'subject', 'message'];
    $errors = [];
    
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $errors[] = ucfirst($field) . ' is required';
        }
    }
    
    // Validate email format
    if (!empty($_POST['email']) && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format';
    }
    
    if (empty($errors)) {
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->isSMTP();
            $mail->SMTPAuth   = true;
            $mail->Host       = 'smtp.gmail.com';
            $mail->Username   = 'rabelisback@gmail.com';
            $mail->Password   = 'eijkafjpbxjxbiue';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            //Recipients
            $mail->setFrom('rabelisback@gmail.com', 'Code with Rabel');
            $mail->addAddress('rabelisback@gmail.com', 'Code with Rabel');

            // Sanitize input data
            $fullName = htmlspecialchars($_POST['fullName']);
            $email = htmlspecialchars($_POST['email']);
            $subject = htmlspecialchars($_POST['subject']);
            $message = htmlspecialchars($_POST['message']);

            //Content
            $mail->isHTML(true);
            $mail->Subject = 'New Contact Form Submission: ' . $subject;
            $mail->Body    = "
                <h1>Contact Form Submission</h1>
                <p><strong>Full Name:</strong> {$fullName}</p>
                <p><strong>Email:</strong> {$email}</p>
                <p><strong>Subject:</strong> {$subject}</p>
                <p><strong>Message:</strong> {$message}</p>
            ";
            
            // Plain text version
            $mail->AltBody = "Contact Form Submission\n\n" .
                            "Full Name: {$fullName}\n" .
                            "Email: {$email}\n" .
                            "Subject: {$subject}\n" .
                            "Message: {$message}";

            $mail->send();
            header('Content-Type: application/json');
            echo json_encode(['status' => 'success', 'message' => 'Message has been sent successfully']);
            
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"]);
        }
    } else {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => implode(', ', $errors)]);
    }
} else {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}