<?php
require_once("database.php");

function escapeString($data) {
    global $conn;
    return mysqli_real_escape_string($conn, $data);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = escapeString($_POST['username']);
    $password = escapeString($_POST['password']);
    $remember_me = isset($_POST['remember_me']);

    login($username, $password, $remember_me);
} else {
    // Check if the user has "Remember Me" cookies set
    if (isset($_COOKIE['username']) && isset($_COOKIE['password'])) {
        $username = $_COOKIE['username'];
        $password = $_COOKIE['password']; // This should be a hashed password

        // Log the user in automatically
        login($username, $password, true, true);
    }
}

function login($username, $password, $remember_me, $is_cookie = false) {
    global $conn;

    // Predefined admin credentials
    $admin_username = "admin";
    $admin_password = "adminpassword"; // You should change this to the actual password

    // Check if the provided username matches the admin username and if the provided password matches the admin password
    if ($username === $admin_username && ($is_cookie ? $password === hash('sha256', $admin_password) : $password === $admin_password)) {
        session_start();
        $_SESSION['username'] = $username;
        $_SESSION['role'] = 'admin';

        if ($remember_me) {
            setcookie('username', $username, time() + (86400 * 30), "/");
            setcookie('password', hash('sha256', $admin_password), time() + (86400 * 30), "/");
        }

        header("Location: ../pages/admin.php");
        exit();
    } else {
        // Check if the provided username exists in the database for regular users
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $hashed_password = $row['password'];

            if ($is_cookie ? $password === $hashed_password : password_verify($password, $hashed_password)) {
                session_start();
                $_SESSION['username'] = $username;
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['role'] = 'user'; // Assuming regular users have the role 'user'

                if ($remember_me) {
                    setcookie('username', $username, time() + (86400 * 30), "/");
                    setcookie('password', $hashed_password, time() + (86400 * 30), "/");
                }

                header("Location: ../pages/home.php");
                exit();
            } else {
                echo "Incorrect password!";
            }
        } else {
            echo "User not found!";
        }
    }
}
?>
