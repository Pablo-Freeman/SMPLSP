<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEW RECORD</title>
    <link rel="stylesheet" type="text/css" href="styless.css"> 
</head>

<body>

<div class = "background"> <p> </p> </div>
<div class="parent-container">
    <div class="container">
<?php

$servername = "sql212.infinityfree.com";
$username = "if0_36660578";
$password = "smplsp2024";
$database = "if0_36660578_smplsp";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $itemName = $_POST["item"];
    $productNumber = $_POST["product_num"];
    $quantity = $_POST["quantity"];
    $price = $_POST["price"];
    $dateArrival = $_POST["date"];
    $department = $_POST["department"];
    $section = $_POST["section"];
    $subject = $_POST["subject"];

    $conn->begin_transaction();

    try {
        // Insert data into the parent table
        $insertParentSQL = "INSERT INTO supplies (ItemName, ProductNumber, Quantity, Price, DateArrival, Department, Subject, Section) 
                            VALUES ('$itemName', '$productNumber', '$quantity', '$price', '$dateArrival', '$department', '$subject', '$section')";
        $conn->query($insertParentSQL);

        if ($section == "LIBRARY SECTION") {
            $subject = $department;
            $department = null;
        } else {
            $subject = null; 
        }

        $tableName = '';
        switch ($section) {
            case "UNIVERSITY MATERIALS SECTION":
                $tableName = "u_material";
                break;
            case "PRINTING PRESS SECTION":
                $tableName = "printer_section";
                break;
            case "LIBRARY SECTION":
                $tableName = "library_section";
                break;
            case "SUPPLY SECTION":
                $tableName = "supply_section";
                break;
            default:
                echo "Invalid section selected.";
                exit;
        }

        
      $insertChildSQL = "INSERT INTO $tableName (ItemName, ProductNumber, Quantity, Price, DateArrival";


if ($section == "LIBRARY SECTION") {
    $insertChildSQL .= ", Subject) VALUES ('$itemName', '$productNumber', '$quantity', '$price', '$dateArrival', '$subject')";
} else {
    // For other sections, include "Department"
    $insertChildSQL .= ", Department) VALUES ('$itemName', '$productNumber', '$quantity', '$price', '$dateArrival', '$department')";
}

$conn->query($insertChildSQL);

        $conn->commit();
        
        echo "Data inserted successfully.";

    } catch (Exception $e) {
        
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }
}


$conn->close();

?>

    </div>
    <div class="button-container">
        <button onclick="goToIndex()">Go back to Supply Section</button>
        <form method="post" action="data.php">
    <button type="submit" name="showDataBtn">Show Data</button> <!-- Change type to "submit" -->
</form>
    </div>
</div>

<script>
function goToIndex() {
    window.location.href = 'Supply Section.html';
}
</script>

</body>
</html>