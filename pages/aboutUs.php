<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <link rel="stylesheet" href="../style/about.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('background.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            background-color: #f5f5f5;
            color: #333;
            line-height: 1.6;
            position: relative;
            z-index: 1;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: inherit;
            background-size: inherit;
            background-repeat: inherit;
            background-position: inherit;
            filter: blur(5px);
            z-index: -1;
        }
        .content{
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Meal Planner System</h1>
    </header>
    <nav>
        <ul>
            <li><a href="home.php">Home</a></li>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="recipe.php">Recipe</a></li>
            <li><a href="more.php">More</a></li>
            <li><a href="aboutUs.php">About Us</a></li>
            <li><a href="../handler/logout_handler.php">Logout</a></li>
        </ul>
    </nav>

    <div class="content">
        <h1>About Us</h1>
        <div class="developer-info">
            <h2>Meet the Developer</h2>
            <p>Hello! My name is Eugene, and I am the developer of this recipe application. I am passionate about creating user-friendly and efficient web applications that help people in their daily tasks.</p>
            <p>If you have any questions, feedback, or suggestions, feel free to reach out to me through the following platforms:</p>
            <div class="social-links">
                <a href="#">eugenepausa@gmail.com</a>
                <a href="#" target="_blank">Facebook</a>
                <a href="#" target="_blank">Twitter</a>
            </div>
        </div>
    </div>
</body>
</html>
