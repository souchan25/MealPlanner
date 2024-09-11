<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}

require_once("../handler/database.php");

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add_recipe"])) {
    $recipe_name = $_POST["recipe_name"];
    $ingredients = $_POST["ingredients"];
    $instructions = $_POST["instructions"];
    $meal_type = $_POST["meal_type"];

    $upload_dir = "../uploads/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $recipe_image = null;
    if (isset($_FILES['recipe_image']) && $_FILES['recipe_image']['error'] === UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['recipe_image']['tmp_name'];
        $file_name = $_FILES['recipe_image']['name'];
        $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
        $file_path = $upload_dir . uniqid() . '.' . $file_ext;

        if (move_uploaded_file($tmp_name, $file_path)) {
            $recipe_image = $file_path;
        } else {
            echo "Failed to move uploaded file.";
            exit;
        }
    }

    $sql = "INSERT INTO recipes (user_id, recipe_name, ingredients, instructions, meal_type, image_url) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssss", $user_id, $recipe_name, $ingredients, $instructions, $meal_type, $recipe_image); // Here, we bind $recipe_image to image_url

    if ($stmt->execute()) {
        echo '<script>alert("Recipe added successfully!"); window.location.href = "view_recipe.php";</script>';
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Recipe</title>
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
            background: rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
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
            margin: 0 auto;
            padding: 20px;
            max-width: 1000px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
        }

        #recipe-form {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin: 0 auto;
            padding: 40px;
        }

        #recipe-form label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        #recipe-form input[type="text"],
        #recipe-form textarea,
        #recipe-form select,
        #recipe-form input[type="file"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 8px;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-sizing: border-box;
            font-size: 16px;
        }

        #recipe-form button[type="submit"] {
            width: 100%;
            padding: 8px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        #recipe-form button[type="submit"]:hover {
            background-color: #0056b3;
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
            <li><a href="view_recipe.php">View Your Recipes</a></li>
            <li><a href="add_recipe.php">Add Recipe</a></li>
            <li><a href="aboutUs.php">About Us</a></li>
            <li><a href="../handler/logout_handler.php">Logout</a></li>
        </ul>
    </nav>

    <div class="content">
        <h1>Add a New Recipe</h1>
        <form id="recipe-form" action="add_recipe.php" method="post" enctype="multipart/form-data" style="background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;">
            <div class="input-box">
                <label for="recipe_name">Recipe Name</label>
                <input type="text" id="recipe_name" name="recipe_name" required>
            </div>
            <div class="input-box">
                <label for="ingredients">Ingredients</label>
                <textarea id="ingredients" name="ingredients" rows="4" required></textarea>
            </div>
            <div class="input-box">
                <label for="instructions">Instructions</label>
                <textarea id="instructions" name="instructions" rows="4" required></textarea>
            </div>
            <div class="input-box">
                <label for="meal_type">Meal Type</label>
                <select id="meal_type" name="meal_type" required>
                    <option value="breakfast">Breakfast</option>
                    <option value="lunch">Lunch</option>
                    <option value="dinner">Dinner</option>
                </select>
            </div>
            <div class="input-box">
                <label for="recipe_image">Recipe Image</label>
                <input type="file" id="recipe_image" name="recipe_image" accept="image/*" required>
            </div>
            <button type="submit" name="add_recipe" class="btn">Add Recipe</button>
        </form>
    </div>
</body>
</html>
