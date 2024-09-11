<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="../style/style.css">
</head>
<body>

    <div class="wrapper">
        <form action="../handler/forgot_password_handler.php" method="post">
            <h1>Forgot Password</h1>
            <div class="input-box">
                <input type="email" placeholder="Enter your email" name="email" required>
                <i class='bx bx-envelope'></i>
            </div>
            <button type="submit" class="btn">Submit</button>
            <br>
            <button class="btn" style="margin-top: 5px;"><a href="index.php">Cancel</a></button>
        </form>
    </div>

</body>
</html>
