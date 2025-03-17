<?php
include '../db/connection.php';

$response = ["valid" => true, "error" => ""];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $originalValues = $_POST["original"] ?? [];
    $processedValues = $_POST['processed'];


    foreach ($processedValues as $key => $value) {
        $query = "SELECT COUNT(*) FROM data_kanban WHERE noproc = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $value);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count == 0) {
            $response["valid"] = false;
            $response["error"] .= "Nomor Proses tidak ditemukan di database.<br>";
            exit(0);
        }
    }
}

echo json_encode($response);
?>