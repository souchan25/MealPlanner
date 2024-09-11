<?php
require_once("../handler/database.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_GET['token'])) {
    $token = $_GET['token'];
    $new_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE reset_token = ? AND reset_expires > NOW()";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $new_password, $token);
    $stmt->execute();

    if ($stmt->affected_rows == 1) {
        echo "Your password has been reset successfully.";
    } else {
        echo "The reset link is invalid or expired.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="../style/style.css">
</head>
<body>

    <div class="wrapper">
        <form action="" method="post">
            <h1>Reset Password</h1>
            <div class="input-box">
                <input type="password" placeholder="Enter new password" name="password" required>
                <i class='bx bx-lock-alt'></i>
            </div>
            <button type="submit" class="btn">Submit</button>
        </form>
    </div>

</body>
</html>
