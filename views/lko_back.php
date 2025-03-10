<?php
include '../db/connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $no_stroke = $_POST['no_stroke'] ?? '';
    $new_stroke = $_POST['current_stroke'] ?? 0;

    // Update current stroke di database
    $stmt = $conn->prepare("UPDATE data_stroke SET current_stroke = ? WHERE no = ?");
    $stmt->bind_param("ii", $new_stroke, $no_stroke);
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Stroke updated!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to update stroke"]);
    }
}
?>