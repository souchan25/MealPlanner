<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../style/reg.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'><link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>

    <div class="wrapper">
        <form action="../handler/register_handler.php" method="post">
            <h1>Register</h1>
            <div class="input-box">
                <input type="text" placeholder="Username" name="reg_username" required >
                <i class='bx bx-user' ></i>
            </div>
            <div class="input-box">
                <input type="text" placeholder="Full Name" name="reg_name" required>
                <i class='bx bxs-user-detail' ></i>
            </div>
            <div class="input-box">
                <input type="text" placeholder="Email" name="reg_email" required>
                <i class='bx bx-envelope'></i>
            </div>
            <div class="input-box">
                <input type="password" placeholder="Password" name="reg_password" required>
                <i class='bx bx-lock-alt'></i>
            </div>


            <button type="submit" class="btn">Register</button>

            <div class="register-link">
                <p>Already have an account? <a href="index.php">Login</a></p>
            </div>
        </form>
    </div>

</body>
</html>