<?php
include '../db/connection.php';
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

error_log(json_encode($_GET)); // Log parameter yang diterima

$noproc1 = trim($_GET['noproc1'] ?? '');
$noproc2 = trim($_GET['noproc2'] ?? '');
$noproc3 = trim($_GET['noproc3'] ?? '');

// **Simpan urutan input sesuai dengan yang dimasukkan oleh user**
$noprocParams = array_filter([$noproc1, $noproc2, $noproc3]);

if (empty($noprocParams)) {
    echo json_encode([
        'success' => false,
        'message' => 'Setidaknya salah satu parameter noproc harus diisi.'
    ]);  
    exit;
}

try {
    // Buat query dengan placeholders
    $placeholders = implode(',', array_fill(0, count($noprocParams), '?'));
    
    // ORDER BY FIELD untuk menjaga urutan
    $query = "SELECT noproc, ctrl_no, c_l, kind, size, col, term_b, strip_b, half_strip_b, man_b, term_a, strip_a, half_strip_a, man_a, qty
              FROM data_kanban 
              WHERE noproc IN ($placeholders)
              ORDER BY FIELD(noproc, " . implode(',', array_map(fn($x) => '?', $noprocParams)) . ")";

    error_log('Query: ' . $query);

    $stmt = $conn->prepare($query);
    
        // Gabungkan parameter untuk WHERE dan ORDER BY
    $types = str_repeat('s', count($noprocParams) * 2);
    $mergedParams = array_merge($noprocParams, $noprocParams); // Simpan dalam variabel
    $stmt->bind_param($types, ...$mergedParams);


    $stmt->execute();
    $result = $stmt->get_result();

    $data = $result->fetch_all(MYSQLI_ASSOC);
    
    // Debug: Log hasil query
    error_log('Fetched data: ' . json_encode($data));

    echo json_encode(['success' => true, 'data' => $data]);

} catch (Exception $e) {
    error_log('Error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
}

$stmt->close();
$conn->close();
?>