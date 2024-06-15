
<?php

$servername = "sql212.infinityfree.com";
$username = "if0_36660578";
$password = "smplsp2024";
$database = "if0_36660578_smplsp";

$conn = new mysqli($servername, $username, $password, $database);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$table = "";


if(isset($_POST['submit'])) {
    
    $section = $_POST['sections']; 
    $item = $_POST['item'];
    $product_num = $_POST['product_num'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $date = $_POST['date'];
    
    $subject = $_POST['subject'];

    switch($section) {
        case 'ss':
            $table = "supply_section";
            break;
        case 'um':
            $table = "u_material";
            break;
        case 'pps':
            $table = "printer_section";
            break;
        case 'ls':
            $table = "library_section";
            break;
        default:
            break;
    }

    $fieldsToUpdate = [];
    if (!empty($item)) {
        $fieldsToUpdate[] = "ItemName='$item'";
    }
    if (!empty($quantity)) {
        $fieldsToUpdate[] = "Quantity='$quantity'";
    }
    if (!empty($price)) {
        $fieldsToUpdate[] = "Price='$price'";
    }
    if (!empty($date)) {
        $fieldsToUpdate[] = "DateArrival='$date'";
    }
    if (!empty($subject)) {
        $fieldsToUpdate[] = "Subject='$subject'";
    }

    if (!empty($fieldsToUpdate)) {
        
        $sql = "UPDATE $table SET " . implode(", ", $fieldsToUpdate) . " WHERE ProductNumber='$product_num'";
        if ($conn->query($sql) === TRUE) {
            if ($conn->affected_rows > 0) {
                echo "Record updated successfully in $table table.";
            } else {
                echo "The product number you input is not yet recorded. Please use a valid product number.";
            }
        } else {
            echo "Error updating record in $table table: " . $conn->error;
        }
        
        
        $sql = "UPDATE supplies SET " . implode(", ", $fieldsToUpdate) . " WHERE ProductNumber='$product_num'";
        if ($conn->query($sql) === TRUE) {
            if ($conn->affected_rows > 0) {
                echo "Record updated successfully in supplies table.";
            } else {
                echo "The product number you input is not yet recorded in supplies table. Please use a valid product number.";
            }
        } else {
            echo "Error updating record in supplies table: " . $conn->error;
        }
        
        
        header("Location: und.html");
        exit();
    } else {
        echo "No fields to update.";
    }
}
$conn->close();
?>
