<?php
session_start();

$_SESSION = array();

session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="3;url=../pages/index.php">
    <title>Logout</title>
</head>
<body>
    <script>
        alert("You have been logged out successfully!");
        setTimeout(function() {
            window.location.href = '../pages/index.php';
        }, 2000);
    </script>
</body>
</html>
