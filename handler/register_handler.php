<?php

require_once("database.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = $_POST["reg_username"];
    $fullname = $_POST["reg_name"];
    $email = $_POST["reg_email"];
    $password = password_hash($_POST["reg_password"], PASSWORD_DEFAULT);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO Users (username, fullname, email, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $fullname, $email, $password);

    if ($stmt->execute()) {
        echo '<script>alert("Registration successful! Please log in."); window.location.href = "../pages/index.php";</script>';
        exit;
    } else {
        echo "Error: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}

?>
