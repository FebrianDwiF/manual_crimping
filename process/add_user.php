<?php
include '../db/connection.php';
session_start();





if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    var_dump($aut);
    $name = $conn->real_escape_string($_POST['name']);
    $nik = $conn->real_escape_string($_POST['nik']);
    $role = $conn->real_escape_string($_POST['role']);

    $query = "INSERT INTO users (name, nik, role) VALUES ('$name', '$nik', '$role')";
    if ($conn->query($query) === TRUE) {
           
        header("Location: ../views/teknisi.php?nik=" . urlencode($aut));
        exit();


} else {
echo "Error: " . $conn->error;
}

$conn->close();
}
?>