<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>More Options</title>
    <style>
        * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
        }

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

        header {
        background-color: #fff;
        padding: 20px;
        text-align: center;
        }

        header h1 {
        font-size: 2em;
        color: #555;
        }

        nav {
        background-color: #f8f8f8;
        padding: 10px 0;
        }

        nav ul {
        list-style: none;
        text-align: center;
        }

        nav ul li {
        display: inline;
        margin: 0 10px;
        }

        nav ul li a {
        color: #666;
        text-decoration: none;
        padding: 5px 10px;
        }

        nav ul li a:hover {
        color: #000;
        }

        .content {
            padding: 20px;
        }

        .options {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }

        .option {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: 20px;
            padding: 20px;
            text-align: center;
            width: 200px;
        }

        .option a {
            text-decoration: none;
            color: #007bff;
        }

        .option a:hover {
            text-decoration: underline;
        }

        .option h3 {
            margin-bottom: 15px;
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
            <li><a href="recipe.php">All Recipes</a></li>
            <li><a href="more.php">More</a></li>
            <li><a href="aboutUs.php">About Us</a></li>
            <li><a href="../handler/logout_handler.php">Logout</a></li>
        </ul>
    </nav>

    <div class="content">
        <h2>Select an Option</h2>
        <div class="options">
            <div class="option">
                <h3>Automated Shopping List</h3>
                <p>Create a shopping list based on your meal plans.</p>
                <a href="shopping_list.php">Go to Shopping List</a>
            </div>
            <div class="option">
                <h3>Pantry Inventory</h3>
                <p>Manage your pantry inventory.</p>
                <a href="pantry_inventory.php">Go to Pantry Inventory</a>
            </div>
            <div class="option">
                <h3>Custom Meal Plans</h3>
                <p>Create custom meal plans.</p>
                <a href="custom_meal_plans.php">Go to Custom Meal Plans</a>
            </div>
            <div class="option">
                <h3>Recipe Rating</h3>
                <p>Rate and review recipes.</p>
                <a href="recipe_rating.php">Go to Recipe Rating</a>
            </div>
        </div>
    </div>
</body>
</html>
