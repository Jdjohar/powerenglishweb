<?php
// save_email.php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email address.");
    }

    $host = "localhost"; // Change if needed
    $dbname = "u663612385_powerenglish"; // Your database name
    $username = "u663612385_powerenglish"; // Your MySQL username
    $password = "Zsxedc@123"; // Your MySQL password

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $pdo->prepare("INSERT INTO waitlist (email) VALUES (:email)");
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        // Send email notification to user
        $to = $email;
        $subject = "Thank You for Joining Our Waitlist!";
        $message = "<h2>Welcome to Our Waitlist!</h2><p>We're excited to have you onboard. You'll be the first to know when we launch!</p>";
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: no-reply@powerenglish.com" . "\r\n";

        mail($to, $subject, $message, $headers);

        echo "<script>alert('Thank you for joining our waitlist!'); window.location.href = '/';</script>";
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            die("Email is already on the waitlist.");
        } else {
            die("Database error: " . $e->getMessage());
        }
    }
}
?>
