<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}

require_once("../handler/database.php");

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit_rating"])) {
    $recipe_id = $_POST["recipe_id"];
    $rating = $_POST["rating"];
    $review = $_POST["review"];

    // Insert the rating and review into the recipe_ratings table
    $sql = "INSERT INTO recipe_ratings (user_id, recipe_id, rating, review) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiis", $_SESSION['user_id'], $recipe_id, $rating, $review);

    if ($stmt->execute()) {
        $success_message = "Rating submitted successfully!";
    } else {
        $error_message = "Error: " . $stmt->error;
    }
}

// Fetch recipes from the database
$sql = "SELECT * FROM recipes";
$result = $conn->query($sql);

$recipes = array();
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
    <title>Recipe Rating</title>
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

        .container {
            max-width: 800px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }

        form {
            margin-bottom: 30px;
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input[type="text"], input[type="number"], textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            padding: 10px 20px;
            border: none;
            background-color: #007bff;
            color: #fff;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .actions a {
            display: inline-block;
            margin-right: 10px;
            text-decoration: none;
            color: #007bff;
        }

        .actions a:hover {
            text-decoration: underline;
        }

        .message {
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 5px;
        }

        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Recipe Rating</h2>

        <?php if (isset($success_message)): ?>
            <div class="message success">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="message error">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="recipe_id">Select Recipe:</label>
                <select id="recipe_id" name="recipe_id" required>
                    <option value="" disabled selected>Select a recipe</option>
                    <?php foreach ($recipes as $recipe): ?>
                        <option value="<?php echo $recipe['recipe_id']; ?>">
                            <?php echo htmlspecialchars($recipe['recipe_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="rating">Rating:</label>
                <select id="rating" name="rating" required>
                    <option value="" disabled selected>Select a rating</option>
                    <option value="5">5 stars - Excellent</option>
                    <option value="4">4 stars - Very Good</option>
                    <option value="3">3 stars - Good</option>
                    <option value="2">2 stars - Fair</option>
                    <option value="1">1 star - Poor</option>
                </select>
            </div>

            <div class="form-group">
                <label for="review">Review:</label>
                <textarea id="review" name="review" required></textarea>
            </div>

            <div class="form-group">
                <button type="submit" name="submit_rating">Submit Rating</button>
            </div>
        </form>

        <a href="more.php">Back to More Options</a>
    </div>
</body>
</html>
