<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

require_once("../handler/database.php");

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Invalid request!";
    exit;
}

$user_id = $_GET['id'];

$sql = "DELETE FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    header("Location: admin.php");
    exit;
} else {
    echo "Error deleting account!";
}
?>
