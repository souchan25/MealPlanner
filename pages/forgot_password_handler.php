<?php
require_once("../handler/database.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {

        $token = bin2hex(random_bytes(50));
        $sql = "UPDATE users SET reset_token = ?, reset_expires = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $token, $email);
        $stmt->execute();

        $reset_link = "http://yourdomain.com/pages/reset_password.php?token=" . $token;
        $subject = "Password Reset Request";
        $message = "Click the link to reset your password: " . $reset_link;
        $headers = "From: no-reply@yourdomain.com";

        mail($email, $subject, $message, $headers);

        echo "A password reset link has been sent to your email.";
    } else {
        echo "No user found with that email.";
    }
}
?>
