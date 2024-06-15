<?php

$servername = "sql212.infinityfree.com";
$username = "if0_36660578";
$password = "smplsp2024";
$database = "if0_36660578_smplsp";

$conn = new mysqli($servername, $username, $password, $database);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if(isset($_POST['submit'])) {
    
    if (isset($_POST['delete_all_supplies'])) {
        
        $sql = "DELETE FROM supplies";
        if ($conn->query($sql) === TRUE) {
            echo "All data deleted successfully from supplies table.";
        } else {
            echo "Error deleting all data from supplies table: " . $conn->error;
        }
    } else {
        
        $section = $_POST['sections'];
        $product_num = $_POST['product_num'];

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

        if (!empty($product_num)) {
            $sql = "DELETE FROM $table WHERE ProductNumber='$product_num'";
            if ($conn->query($sql) === TRUE) {
                if ($conn->affected_rows > 0) {
                    echo "Record deleted successfully from $table table.";
                } else {
                    echo "The product number you input is not yet recorded. Please use a valid product number.";
                }
            } else {
                echo "Error deleting record from $table table: " . $conn->error;
            }

            $sql = "DELETE FROM supplies WHERE ProductNumber='$product_num'";
            if ($conn->query($sql) === TRUE) {
                if ($conn->affected_rows > 0) {
                    echo "Record deleted successfully from supplies table.";
                } else {
                    echo "The product number you input is not yet recorded in supplies table. Please use a valid product number.";
                }
            } else {
                echo "Error deleting record from supplies table: " . $conn->error;
            }
        } else {
            echo "Please provide a product number to delete.";
        }
    }

    header("Location: und.html");
    exit();
}

$conn->close();
?>
