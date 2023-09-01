<?php
    // Get POST data
    $id = $_POST['id'];
    $newPlate = $_POST['plate'];

    // Connect to your database
    $conn = new mysqli("localhost", "root", "", "uviscan");
    
    if($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Update the plate value
    $sql = "UPDATE entrances SET plate = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $newPlate, $id);
    
    $response = [];

    if($stmt->execute()) {
        $response["success"] = true;
    } else {
        $response["success"] = false;
        $response["error"] = "Failed to update plate.";
    }

    echo json_encode($response);
?>
