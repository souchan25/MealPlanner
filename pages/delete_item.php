<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}

require_once("../handler/database.php");

if (isset($_GET['id'])) {
    $item_id = $_GET['id'];
    $sql = "DELETE FROM shopping_list WHERE item_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $item_id);

    if ($stmt->execute()) {
        echo '<script>alert("Item deleted successfully!"); window.location.href = "shopping_list.php";</script>';
    } else {
        echo "Error: " . $stmt->error;
    }
} else {
    header("Location: shopping_list.php");
}
?>
