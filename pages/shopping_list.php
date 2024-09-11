<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}

require_once("../handler/database.php");

$user_id = $_SESSION['user_id'];

// Handle form submission to add items
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add_item"])) {
    $item_name = $_POST["item_name"];
    $quantity = $_POST["quantity"];
    $price = $_POST["price"];

    $sql = "INSERT INTO shopping_list (user_id, item_name, quantity, price) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isss", $user_id, $item_name, $quantity, $price);

    if ($stmt->execute()) {
        echo '<script>alert("Item added successfully!"); window.location.href = "shopping_list.php";</script>';
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}

// Fetch shopping list items for the logged-in user
$sql = "SELECT * FROM shopping_list WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping List</title>
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
        input[type="text"], input[type="number"] {
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
    </style>
</head>
<body>
    <div class="container">
        <h1>Shopping List</h1>
        <form action="shopping_list.php" method="post">
            <label for="item_name">Item Name:</label>
            <input type="text" id="item_name" name="item_name" required>
            <label for="quantity">Quantity:</label>
            <input type="text" id="quantity" name="quantity" required>
            <label for="price">Price</label>
            <input type="text" id="price" name="price" required>
            <button type="submit" name="add_item">Add Item</button>
        </form>
        <table>
            <tr>
                <th>Item Name</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                <td><?php echo htmlspecialchars($row['price']); ?></td>
                <td class="actions">
                    <a href="delete_item.php?id=<?php echo $row['item_id']; ?>" onclick="return confirm('Are you sure you want to delete this item?');" style="border: solid 1px red; background-color: red; padding: 5px; border-radius: 5px">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
        <a href="more.php">Back to More Options</a>
    </div>
</body>
</html>
