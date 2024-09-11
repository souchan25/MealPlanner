<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

require_once("../handler/database.php");

$sql = "SELECT * FROM users";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        .admin-wrapper {
            max-width: 1000px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 28px;
            margin-bottom: 20px;
            color: #333;
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

        td a {
            display: inline-block;
            margin-right: 10px;
            text-decoration: none;
            color: #007bff;
        }

        td a:hover {
            text-decoration: underline;
        }

        button {
            padding: 10px 20px;
            border: none;
            background-color: #007bff;
            color: #fff;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 10px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="admin-wrapper">
        <h1>Admin Panel</h1>
        <form action="../handler/logout_handler.php" method="post">
            <button type="submit">Logout</button>
        </form>
        <table>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo $row['user_id']; ?></td>
                <td><?php echo htmlspecialchars($row['username']); ?></td>
                <td><?php echo htmlspecialchars($row['fullname']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td>
                <a href="edit.php?id=<?php echo $row['user_id']; ?>" style="display: inline-block; padding: 8px 12px; background-color: #007bff; color: #fff; border: 1px solid #007bff; border-radius: 5px; text-decoration: none; transition: background-color 0.3s, color 0.3s;">
                    Edit
                </a>
                <a href="delete.php?id=<?php echo $row['user_id']; ?>" onclick="confirmDelete(event)" style="display: inline-block; padding: 8px 12px; background-color: #dc3545; color: #fff; border: 1px solid #dc3545; border-radius: 5px; text-decoration: none; transition: background-color 0.3s, color 0.3s;">
                    Delete
                </a>

                <script>
                function confirmDelete(event) {
                    event.preventDefault();

                    if (confirm('Are you sure you want to delete this account?')) {
                        window.location.href = event.target.href;
                    }
                }
                </script>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</body>
</html>
