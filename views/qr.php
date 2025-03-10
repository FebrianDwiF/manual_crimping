<?php
include '../db/connection.php';
require '../vendor/autoload.php'; // Pastikan path benar

use Picqer\Barcode\BarcodeGeneratorPNG;

function generateBarcode($data) {
    $generator = new BarcodeGeneratorPNG();
    $barcode = $generator->getBarcode($data, $generator::TYPE_CODE_128);

    // Simpan barcode sebagai file PNG
    file_put_contents('barcode_' . $data . '.png', $barcode);

    // Atau, jika ingin menampilkan sebagai gambar HTML
    echo '<img src="data:image/png;base64,' . base64_encode($barcode) . '"><br>';
    echo '<p>Data: ' . htmlspecialchars($data) . '</p>';
}

// Ambil data dari database
$sql = "SELECT noproc FROM data_kanban WHERE id = '60'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data = $row['noproc'];
        // Generate barcode untuk setiap data
        generateBarcode($data);
    }
} else {
    echo "Tidak ada data.";
}


$conn->close();

?>
<div class="print-button">
    <button onclick="window.print()">Print</button>
</div>