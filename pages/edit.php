<?php
session_start();

// Check if user is logged in as admin, otherwise redirect to index.php
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

require_once("../handler/database.php");

// Check if ID parameter is provided in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Invalid request!";
    exit;
}

// Retrieve account details based on ID from the database
$id = $_GET['id'];
$sql = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Account not found!";
    exit;
}

$row = $result->fetch_assoc();
$username = $row['username'];
$fullname = $row['fullname'];
$email = $row['email'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $newUsername = $_POST['username'];
    $newFullname = $_POST['fullname'];
    $newEmail = $_POST['email'];

    $sql = "UPDATE users SET username = ?, fullname = ?, email = ? WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $newUsername, $newFullname, $newEmail, $id);

    if ($stmt->execute()) {
        header("Location: admin.php");
        exit;
    } else {
        echo "Error updating account!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Account</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .edit-form-wrapper {
            max-width: 500px;
            margin: 50px auto;
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 10px;
        }

        input {
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        button[type="button"] {
            background-color: #ff0000;
            margin-right: 10px;

        }
    </style>
</head>
<body>
    <div class="edit-form-wrapper">
        <h1>Edit Account</h1>
        <form action="" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo $username; ?>" required><br>
            <label for="fullname">Full Name:</label>
            <input type="text" id="fullname" name="fullname" value="<?php echo $fullname; ?>" required><br>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $email; ?>" required><br>
            <button type="submit">Save Changes</button>
            <a href="admin.php"><button type="button" style="width: 500px;">Cancel</button></a>
        </form>
    </div>
</body>
</html>
