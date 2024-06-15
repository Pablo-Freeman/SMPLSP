<?php

$servername = "sql212.infinityfree.com";
$username = "if0_36660578";
$password = "smplsp2024";
$database = "if0_36660578_smplsp";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_username = $_POST['current_username'];
    $new_username = $_POST['new_username'];
    $new_password = $_POST['new_password'];

    if (empty($current_username)) {
        $error = "Current username is required.";
    } else {
        // Check if current username exists and is not admin
        $stmt = $conn->prepare("SELECT username FROM users WHERE username = ?");
        $stmt->bind_param("s", $current_username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 0) {
            $error = "The current username does not exist.";
        } elseif ($current_username == "admin") {
            $error = "Cannot update admin account.";
        } else {
    
            $sql = "UPDATE users SET ";
            $params = [];
            $types = '';

            if (!empty($new_username)) {
                $sql .= "username = ?";
                $params[] = $new_username;
                $types .= 's';
            }
            if (!empty($new_password)) {
                if (!empty($new_username)) {
                    $sql .= ", ";
                }
                $sql .= "password = ?";
                $params[] = $new_password;
                $types .= 's';
            }

            $sql .= " WHERE username = ?";
            $params[] = $current_username;
            $types .= 's';

        $stmt = $conn->prepare($sql);
            $stmt->bind_param($types, ...$params);

            if ($stmt->execute()) {
                $_SESSION['message'] = "User updated successfully.";
            } else {
                $error = "Error updating user: " . $conn->error;
            }

            $stmt->close();
        }
    }
}

if (!empty($error)) {
    $_SESSION['error'] = $error;
}

$conn->close();

header("Location: admin.php");
exit();
?>
