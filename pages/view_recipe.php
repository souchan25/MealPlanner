<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}

require_once("../handler/database.php");

$user_id = $_SESSION['user_id'];
$recipes = [];

$sql = "SELECT * FROM recipes WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $recipes[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Recipes</title>
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
            margin: 0 auto;
            padding: 20px;
            max-width: 1200px;
        }

        #recipe-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            list-style-type: none;
            padding: 0;
        }

        .recipe-item {
            border: 1px solid #ddd;
            border-radius: 5px;
            overflow: hidden;
            background-color: #f9f9f9;
            transition: background-color 0.3s ease;
        }

        .recipe-item:hover {
            background-color: #f1f1f1;
        }

        .recipe-item img {
            width: 100%;
            height: auto;
            display: block;
        }

        .recipe-content {
            padding: 15px;
        }

        .recipe-item h3 {
            margin-top: 0;
        }

        .recipe-item p {
            margin: 5px 0;
        }

        .recipe-item p strong {
            color: #4CAF50;
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
            <li><a href="#">About Us</a></li>
            <li><a href="../handler/logout_handler.php">Logout</a></li>
        </ul>
    </nav>

    <div class="content">
        <h1>Your Recipes</h1>

        <?php if (!empty($recipes)): ?>
            <ul id="recipe-list">
                <?php foreach ($recipes as $recipe): ?>
                    <li class="recipe-item">
                        <img src="<?php echo htmlspecialchars($recipe['image_url']); ?>" alt="<?php echo htmlspecialchars($recipe['recipe_name']); ?>">
                        <div class="recipe-content">
                            <h3><?php echo htmlspecialchars($recipe['recipe_name']); ?></h3>
                            <p><strong>Ingredients: <br></strong> <?php echo nl2br(htmlspecialchars($recipe['ingredients'])); ?></p>
                            <p><strong>Instructions: <br></strong> <?php echo nl2br(htmlspecialchars($recipe['instructions'])); ?></p>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p id="no-recipes">You have not added any recipes yet.</p>
        <?php endif; ?>
    </div>
</body>
</html>
