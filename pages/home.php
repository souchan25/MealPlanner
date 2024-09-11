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

    $sql = "INSERT INTO recipes (user_id, recipe_name, description, ingredients, instructions, meal_type) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssss", $user_id, $recipe_name, $description, $ingredients, $instructions, $meal_type);

    if ($stmt->execute()) {
        echo '<script>alert("Recipe added successfully!"); window.location.href = "view_recipe.php";</script>';
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}

function fetchMeals($meal_type, $user_id, $conn, $limit = 2) {
    $sql = "SELECT preferred_cuisine, excluded_ingredients, allergies FROM userpreferences WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $preferences = $result->fetch_assoc();
        $excluded_ingredients = $preferences['excluded_ingredients'] ?: '';
        $allergies = $preferences['allergies'] ?: '';
    } else {
        return null;
    }

    $excluded_ingredients_pattern = '%' . $excluded_ingredients . '%';
    $allergies_pattern = '%' . $allergies . '%';

    if (empty($excluded_ingredients) && empty($allergies)) {
        $sql = "SELECT * FROM recipes WHERE meal_type = ? LIMIT ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $meal_type, $limit);
    } else {
        $sql = "SELECT * FROM recipes WHERE meal_type = ? AND (ingredients NOT LIKE ? AND ingredients NOT LIKE ?) LIMIT ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $meal_type, $excluded_ingredients_pattern, $allergies_pattern, $limit);
    }

    $stmt->execute();
    return $stmt->get_result();
}

$breakfast_meals = fetchMeals('breakfast', $user_id, $conn, 2);
$lunch_meals = fetchMeals('lunch', $user_id, $conn, 2);
$dinner_meals = fetchMeals('dinner', $user_id, $conn, 2);
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
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            text-align: center;
        }

        header h1 {
            font-size: 2em;
            color: #555;
        }

        nav {
            background-color: rgba(248, 248, 248, 0.8);
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
            margin: 10px auto;
            padding: 20px;
            max-width: 1000px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
        }

        .meal-section {
            margin-bottom: 30px;
        }

        .meal-section h2 {
            background-color: rgba(240, 240, 240, 0.9);
            color: #4CAF50;
            padding: 10px;
            border-left: 5px solid #4CAF50;
            font-size: 24px;
        }

        .meals .meal {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
            background-color: rgba(249, 249, 249, 0.9);
        }

        .meals .meal h3 {
            margin-top: 0;
        }

        .meals .meal p {
            margin: 5px 0;
        }

        .meals .meal p strong {
            color: #4CAF50;
        }

        .meals .meal:hover {
            background-color: rgba(241, 241, 241, 0.9);
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
        <h1>Recommended Meals for Today</h1>

        <div class="meal-section">
            <h2>Breakfast</h2>
            <div class="meals">
                <?php if ($breakfast_meals && $breakfast_meals->num_rows > 0): ?>
                    <?php while ($row = $breakfast_meals->fetch_assoc()): ?>
                        <div class="meal">
                            <h3><?php echo $row['recipe_name']; ?></h3>
                            <div class="meal" data-recipe-id="<?php echo $row['recipe_id']; ?>">
                                <p><strong>Ingredients:</strong> <?php echo $row['ingredients']; ?></p>
                                <p><strong>Instructions:</strong> <?php echo $row['instructions']; ?></p>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No recommended breakfast meals available for today.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="meal-section">
            <h2>Lunch</h2>
            <div class="meals">
                <?php if ($lunch_meals && $lunch_meals->num_rows > 0): ?>
                    <?php while ($row = $lunch_meals->fetch_assoc()): ?>
                        <div class="meal">
                            <h3><?php echo $row['recipe_name']; ?></h3>
                            <div class="meal" data-recipe-id="<?php echo $row['recipe_id']; ?>">
                                <p><strong>Ingredients:</strong> <?php echo $row['ingredients']; ?></p>
                                <p><strong>Instructions:</strong> <?php echo $row['instructions']; ?></p>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No recommended lunch meals available for today.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="meal-section">
            <h2>Dinner</h2>
            <div class="meals">
                <?php if ($dinner_meals && $dinner_meals->num_rows > 0): ?>
                    <?php while ($row = $dinner_meals->fetch_assoc()): ?>
                        <div class="meal">
                            <h3><?php echo $row['recipe_name']; ?></h3>
                            <div class="meal" data-recipe-id="<?php echo $row['recipe_id']; ?>">
                                <p><strong>Ingredients:</strong> <?php echo $row['ingredients']; ?></p>
                                <p><strong>Instructions:</strong> <?php echo $row['instructions']; ?></p>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No recommended dinner meals available for today.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>

