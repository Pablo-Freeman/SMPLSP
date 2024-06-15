<?php

$servername = "sql212.infinityfree.com";
$dbUsername = "if0_36660578";
$dbPassword = "smplsp2024";
$database = "if0_36660578_smplsp";


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['username']) && isset($_POST['password'])) {
    
    $form_username = $_POST['username'];
    $form_password = $_POST['password'];

    try {
        
        $conn = new PDO("mysql:host=$servername;dbname=$database", $dbUsername, $dbPassword);
        
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        
        $stmt = $conn->prepare("SELECT * FROM users WHERE Username = :username");
        $stmt->bindParam(':username', $form_username);
        $stmt->execute();

        
        if ($stmt->rowCount() == 1) {
            
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

           
            if ($form_password === $user['Password']) {
                
                if ($user['Username'] === 'admin') {
                    
                    header("Location: admin.php");
                    exit();
                } else {
                    
                    header("Location: index.html");
                    exit();
                }
            } else {
                
                header("Location: log.html?error=3"); 
                exit();
            }
        } else {
            
            header("Location: log.html?error=2"); 
            exit();
        }
    } catch (PDOException $e) {
        
        error_log("Database Error (Code: " . $e->getCode() . "): " . $e->getMessage() . "\n" . $e->getTraceAsString(), 0);

        
        echo "Database Error: " . $e->getMessage();

        header("Location: login.html?error=4"); 
    }

} else {
   
    header("Location: log.html"); 
    exit();
}
?>
