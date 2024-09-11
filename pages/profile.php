<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}

require_once("../handler/database.php");

$user_id = $_SESSION['user_id'];
$user_data = null;
$user_preferences = null;

$sql = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user_data = $result->fetch_assoc();
}

$sql = "SELECT * FROM userpreferences WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user_preferences = $result->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $preferred_cuisine = $_POST["preferred_cuisine"];
    $excluded_ingredients = $_POST["excluded_ingredients"];
    $allergies = $_POST["allergies"];

    if ($user_preferences) {
        
        $sql = "UPDATE userpreferences SET preferred_cuisine = ?, excluded_ingredients = ?, allergies = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $preferred_cuisine, $excluded_ingredients, $allergies, $user_id);
    } else {
        
        $sql = "INSERT INTO userpreferences (user_id, preferred_cuisine, excluded_ingredients, allergies) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isss", $user_id, $preferred_cuisine, $excluded_ingredients, $allergies);
    }

    if ($stmt->execute()) {
        echo '<script>alert("Preferences updated successfully!"); window.location.href = "profile.php";</script>';
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
    <title>Profile</title>
    <link rel="stylesheet" href="../style/pro.css">
    <style>
        p{
            font-size: 15px;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 5px;
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

        .content{
            background-color: rgba(255, 255, 255, 0.9);
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
        <div class="column">
            <h2>User Information</h2>
            <?php if ($user_data): ?>
            <p><strong>Username:</strong> <?php echo $user_data['username']; ?></p>
            <p><strong>Full Name:</strong> <?php echo $user_data['fullname']; ?></p>
            <p><strong>Email:</strong> <?php echo $user_data['email']; ?></p>
            <?php endif; ?>
        </div>
        <div class="column">
            <h2>Preferences</h2>
            <form action="profile.php" method="post">
                <div class="input-box">
                    <label for="preferred_cuisine">Preferred Cuisine</label>
                    <input type="text" id="preferred_cuisine" name="preferred_cuisine" value="<?php echo $user_preferences['preferred_cuisine'] ?? ''; ?>">
                </div>
                <div class="input-box">
                    <label for="excluded_ingredients">Excluded Ingredients</label>
                    <input type="text" id="excluded_ingredients" name="excluded_ingredients" value="<?php echo $user_preferences['excluded_ingredients'] ?? ''; ?>">
                </div>
                <div class="input-box">
                    <label for="allergies">Allergies</label>
                    <input type="text" id="allergies" name="allergies" value="<?php echo $user_preferences['allergies'] ?? ''; ?>">
                </div>
                <button type="submit" class="btn">Update Preferences</button>
            </form>
        </div>
    </div>

</body>
</html>
