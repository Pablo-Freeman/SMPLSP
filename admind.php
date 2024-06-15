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
    $username = $_POST['usernamedelete'];

    if (empty($username)) {
        $error = "Username is required.";
    } else {
        // Check if username exists and is not admin
        $stmt = $conn->prepare("SELECT username FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 0) {
            $error = "The username does not exist.";
        } elseif ($username == "admin") {
            $error = "Cannot delete admin account.";
        } else {
        
            $stmt = $conn->prepare("DELETE FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);

            if ($stmt->execute()) {
                $_SESSION['message'] = "User deleted successfully.";
            } else {
                $error = "Error deleting user: " . $conn->error;
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
