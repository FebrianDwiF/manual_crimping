<?php 
include '../db/connection.php';
include '../process/auth.php';


//var_dump($nik);


// Misalnya, $user['loginTime'] memiliki format "Friday, 21 February 2025, 14:21:42"
$rawTime = $user['loginTime'];

// Ubah format string menjadi objek DateTime
$dateTime = DateTime::createFromFormat('l, d F Y, H:i:s', $rawTime);

// Jika parsing berhasil, format waktu sesuai kebutuhan, jika tidak gunakan nilai default
$timeOnly = $dateTime ? $dateTime->format('H:i:s') : '00:00:00';
$formattedDateTime = $dateTime ? $dateTime->format('Y-m-d\TH:i') : '';
$formattedFull = $dateTime ? $dateTime->format('l, d F Y - H:i:s') : 'Invalid Date';



// Pastikan session 'search_results' tidak undefined
$_SESSION['search_results'] = $_SESSION['search_results'] ?? [] ;
$_SESSION['filtered_data'] = $_SESSION['filtered_data'] ?? [];
$_SESSION['original_noproc1'] = $_SESSION['original_noproc1'] ?? '';
$_SESSION['original_noproc2'] = $_SESSION['original_noproc2'] ?? '';
$_SESSION['original_noproc3'] = $_SESSION['original_noproc3'] ?? '';

// var_dump($_SESSION['filtered_data']);

// Ambil data dari sesi setelah diperbarui
$searchResults = $_SESSION['search_results'];
$formData = $_SESSION['form_input_data'] ?? [];

$carline = isset($_SESSION['carline']) ? $_SESSION['carline'] : '';
$mesin = isset($_SESSION['mesin']) ? $_SESSION['mesin'] : '';
$shift = isset($_SESSION['shift']) ? $_SESSION['shift'] : '';



// Ambil data yang difilter
$filteredApplicator = array_slice($searchResults['applicator-term']['data_cfm'] ?? [], 0, 3);
$filteredTerm = array_slice($searchResults['applicator-term']['data_crimping'] ?? [], 0, 3);
$filteredStroke = array_slice($searchResults['applicator-term']['data_stroke'] ?? [],  0, 3);

$filteredNoproc = array_slice($_SESSION['filtered_data'], 0, 3);
$kanban1 = $_SESSION['original_noproc1'];
$kanban2 = $_SESSION['original_noproc2'];
$kanban3 = $_SESSION['original_noproc3'];

$noproc1 = $filteredNoproc[0]['noproc'] ?? 'N/A';
$noproc2 = $filteredNoproc[1]['noproc'] ?? 'N/A';
$noproc3 = $filteredNoproc[2]['noproc'] ?? 'N/A';

$c_l1 = $filteredNoproc[0]['c_l'] ?? 'N/A';
$c_l2 = $filteredNoproc[1]['c_l'] ?? 'N/A';
$c_l3 = $filteredNoproc[2]['c_l'] ?? 'N/A';

$col1 = $filteredNoproc[0]['col'] ?? 'N/A';
$col2 = $filteredNoproc[1]['col'] ?? 'N/A';
$col3 = $filteredNoproc[2]['col'] ?? 'N/A';

$qty1 = $filteredNoproc[0]['qty'] ?? 'N/A';
$qty2 = $filteredNoproc[1]['qty'] ?? 'N/A';
$qty3 = $filteredNoproc[2]['qty'] ?? 'N/A';

$mesin = $filteredTerm[0]['mesin'] ?? 'N/A';

$kind1 = $filteredNoproc[0]['kind'] ?? 'N/A';
$kind2 = $filteredNoproc[1]['kind'] ?? 'N/A';
$kind3 = $filteredNoproc[2]['kind'] ?? 'N/A';

$size1 = $filteredNoproc[0]['size'] ?? 'N/A';
$size2 = $filteredNoproc[1]['size'] ?? 'N/A';
$size3 = $filteredNoproc[2]['size'] ?? 'N/A';

$terminal1 = $filteredNoproc[0]['Terminal'] ?? 'N/A';
$terminal2 = $filteredNoproc[1]['Terminal'] ?? 'N/A';
$terminal3 = $filteredNoproc[2]['Terminal'] ?? 'N/A';

// variabel global untuk counting
$applicator = $filteredStroke[0]['applicator'] ?? 'N/A';
$output = 0;

$sql = "SELECT current_stroke, max_stroke FROM data_stroke WHERE applicator = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $applicator);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $currentStroke = $row['current_stroke'];
    $maxStroke = $row['max_stroke'];
} else {
    $currentStroke = 0; // Gunakan angka 0 sebagai default jika data tidak ditemukan
    $maxStroke = 10; // Misalnya, gunakan batas stroke default
}

// Perbarui session strokeData setiap kali halaman dimuat agar selalu sinkron dengan database
$_SESSION['strokeData'] = [
    'form1' => $currentStroke,
    'form2' => $currentStroke,
    'form3' => $currentStroke
];

$_SESSION['maxStroke'] = $maxStroke;



$term = $filteredTerm[0]['term'] ?? 'N/A';
$size_crimping = $filteredTerm[0]['size'] ?? 'N/A';
$stroke = $filteredStroke[0]['current_stroke'] ?? 'N/A';
$max =  $filteredStroke[0]['max_stroke'] ?? 'N/A';
$no_stroke = $filteredStroke[0]['no'] ?? 'N/A';
$output = $no_stroke; 
$output1 = 0;
$output2 = 0;

$output3 = 0;
$noprocValues = [];
for ($i = 0; $i < count($filteredNoproc); $i++) {
    $noprocValues[] = $filteredNoproc[$i]['noproc'] ?? 'N/A';
}


// Gabungkan tiga nilai menjadi satu string
$noprocString = implode(', ', $noprocValues);
// var_dump($noprocString);

$f_c_h = $formData['f_c_h'] ?? 'N/A';
$f_c_w = $formData['f_c_w'] ?? 'N/A';
$r_c_h = $formData['r_c_h'] ?? 'N/A';
$r_c_w = $formData['r_c_w'] ?? 'N/A';

$sql = "SELECT no, item_defect FROM defect";
$result1 = mysqli_query($conn, $sql);
$result2 = mysqli_query($conn, $sql);
$result3 = mysqli_query($conn, $sql);
// Cek apakah ada data yang bisa ditampilkan
// $hasData = !empty($filteredNoproc) || !empty($filteredApplicator) || !empty($filteredTerm) || !empty($filteredStroke);

// Contoh: Cek apakah ada data dari session atau database
// $newData = isset($_SESSION['data_produksi']) && !empty($_SESSION['data_produksi']);

// if ($newData) {
//     echo "<pre>";
//     print_r($_SESSION['data_produksi']); // Debug untuk melihat data yang tersimpan
//     echo "</pre>";
// }


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Downtime Control</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" href="../public/css/lko.css">

</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand" href="#">Downtime Control System</a>
            <div class="navbar-text">
                Operator: <?= htmlspecialchars($user['name'] ?? '') ?> (NIK: <?= htmlspecialchars($nik ?? '') ?>)
                <?php date_default_timezone_set("Asia/Jakarta");?>
                <h3><?= "Login Time: " . htmlspecialchars($user['loginTime']);?></h3>

            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="cobalko.php" class="btn btn-success">Pergi</a>

                <form action="../process/logout.php" method="POST">
                    <input type="hidden" name="nik" value="<?php echo htmlspecialchars($user['nik']); ?>">
                    <button type="submit" class="btn btn-danger">🚪 Logout</button>
                </form>
            </div>


        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Kolom Kiri -->

            <div class="col-lg-6 mx-auto">
                <div class="col-lg-12 form1">
                    <div class="card">
                        <div
                            class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Form LKO 1</h5>


                        </div>

                        <div class="card-body">
                            <button type="button" class="btn btn-success scan-button w-100 mb-3"
                                onclick="startScanning()">
                                <i class="bi bi-upc-scan"></i> Mulai Scan
                            </button>

                            <form id="form1" method="post">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="carline" class="form-label">Carline</label>
                                        <input type="text" class="form-control" id="carline" name="carline"
                                            value="<?= htmlspecialchars($carline ?? '') ?>" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="mesin" class="form-label">Mesin</label>
                                        <input type="text" class="form-control" id="mesin" name="mesin"
                                            value="<?= htmlspecialchars($mesin ?? '') ?>" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="shift" class="form-label">Shift</label>
                                        <input type="text" class="form-control" id="shift" name="shift"
                                            value="<?= htmlspecialchars($shift ?? '') ?>" readonly>
                                    </div>
                                    <div class="col-12">
                                        <label for="time" class="form-label">Time</label>
                                        <input type="time   " class="form-control" id="time" name="time" required
                                            value="<?= htmlspecialchars($timeOnly); ?>" readonly>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="noIssue" class="form-label">No Issue</label>
                                        <input type="text" class="form-control" id="noIssue" name="noIssue"
                                            value="<?= htmlspecialchars($noproc1 ?? '') ?>" readonly>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="qty" class="form-label">Quantity</label>
                                        <input type="number" class="form-control" id="qty" name="qty"
                                            value="<?= htmlspecialchars($qty1 ?? '') ?>" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="scanKanban" class="form-label">Scan Kanban</label>
                                        <input type="text" class="form-control" id="scanKanban" name="scanKanban"
                                            value="<?= htmlspecialchars($kanban1 ?? '') ?>">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="kind" class="form-label">Kind</label>
                                        <input type="text" class="form-control" id="kind" name="kind"
                                            value="<?= htmlspecialchars($kind1 ?? '') ?>" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="size" class="form-label">Size</label>
                                        <input type="text" class="form-control" id="size" name="size"
                                            value="<?= htmlspecialchars($size1 ?? '') ?>" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="col" class="form-label">COL</label>
                                        <input type="text" class="form-control" id="col" name="col"
                                            value="<?= htmlspecialchars($col1 ?? '') ?>" readonly>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="r_c_h" class="form-label">R C/H</label>
                                        <input type="text" class="form-control" id="r_c_h" name="r_c_h"
                                            value="<?= htmlspecialchars($r_c_h ?? '') ?>" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="f_c_h" class="form-label">F C/H</label>
                                        <input type="text" class="form-control" id="f_c_h" name="f_c_h"
                                            value="<?= htmlspecialchars($f_c_h ?? '') ?>" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="terminal" class="form-label">No. Terminal</label>
                                        <input type="text" class="form-control" id="terminal" name="terminal"
                                            value="<?= htmlspecialchars($terminal1 ?? '') ?>" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="f_c_w" class="form-label">F C/W</label>
                                        <input type="text" class="form-control" id="f_c_w" name="f_c_w"
                                            value="<?= htmlspecialchars($f_c_w ?? '') ?>" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="r_c_w" class="form-label">R C/W</label>
                                        <input type="text" class="form-control" id="r_c_w" name="r_c_w"
                                            value="<?= htmlspecialchars($r_c_w ?? '') ?>" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="c_l" class="form-label">C/L</label>
                                        <input type="text" class="form-control" id="c_l" name="c_l"
                                            value="<?= htmlspecialchars($c_l1 ?? '') ?>" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="kodeDefect" class="form-label">Kode Defect</label>
                                        <select class="form-control" id="kodeDefect" name="kodeDefect">
                                            <option value="">Pilih Kode Defect</option>
                                            <?php while ($row = mysqli_fetch_assoc($result1)): ?>
                                            <option value="<?= htmlspecialchars($row['no']) ?>">
                                                <?= htmlspecialchars($row['no'] . " - " . $row['item_defect']) ?>
                                            </option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="qtym" class="form-label">Quantity defect</label>
                                        <input type="number" class="form-control" id="qtym" name="qtym">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="lotTerminal" class="form-label">No. Lot Terminal</label>
                                        <input type="text" class="form-control" id="lotTerminal" name="lotTerminal"
                                            required>
                                    </div>

                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary w-100 mt-3">
                                            <i class="bi bi-save"></i> Simpan Data
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 counting1" data-form="1">
                <div class="card p-4 shadow-lg position-relative">
                    <div class="position-absolute top-0 start-0 p-3 text-dark rounded"
                        style="background-color:rgb(8, 139, 239);"><?= $noproc1; ?></div>
                    <button id="reset-output-0" class="btn btn-danger position-absolute top-0 end-0 m-3">Reset
                        Output</button>
                    <div class="box-container mt-5">
                        <div class="box" id="output-box-0">
                            <div class="mini-box">Quantity = <?= $qty1; ?></div>
                            <h3 id="output-0"><?= $output1; ?></h3>
                            <span class="label">Output</span>
                            <button class="btn btn-success mt-2" id="increment-output-0">+</button>
                        </div>

                        <div class="box" id="stroke-box-0">
                            <div class="mini-box">Max Stroke = <?= $max; ?></div>
                            <h3 id="current-0"><?= $currentStroke; ?></h3>
                            <span class="label">Current Stroke</span>
                            <button class="btn btn-success mt-2" id="increment-stroke-0">+</button>
                        </div>
                    </div>
                </div>


                <div class="container">
                    <div class="col-lg-12 mx-auto downtime1">
                        <div class="card">
                            <div class="card-header bg-danger text-white">
                                <h5 class="mb-0">Downtime Control</h5>
                            </div>
                            <div class="card-body">
                                <form class="downtimeForm" data-id="form1">
                                    <div class="mb-3">
                                        <label class="form-label">Kode Error</label>
                                        <select class="form-select codeError" required>
                                            <option value="">Pilih kode error</option>
                                            <option value="a">A</option>
                                            <option value="b">B</option>
                                            <option value="c">C</option>
                                            <option value="c1">C1</option>
                                            <option value="c2">C2</option>
                                            <option value="c3">C3</option>
                                            <option value="d">D</option>
                                            <option value="d1">D1</option>
                                            <option value="d2">D2</option>
                                            <option value="d3">D3</option>
                                            <option value="d4">D4</option>
                                            <option value="d5">D5</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Alasan (Opsional)</label>
                                        <textarea class="form-control reasonError" rows="3"></textarea>
                                    </div>
                                    <table class="table">
                                        <thead>
                                            <tr class="text-center">
                                                <th>No</th>
                                                <th>Kode Error</th>
                                                <th>Alasan</th>
                                                <th>Durasi</th>
                                                <th>Total</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="dataBody"></tbody>
                                    </table>
                                    <div class="timer-container">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <button type="button" class="btn btn-success startDowntime">
                                                <i class="bi bi-play-circle"></i> Mulai
                                            </button>
                                            <button type="button" class="btn btn-danger endDowntime" disabled>
                                                <i class="bi bi-stop-circle"></i> Akhiri
                                            </button>
                                        </div>
                                        <div class="timer text-center fs-4 fw-bold">00:00:00</div>
                                    </div>
                                    <!-- <button type="submit" class="btn btn-primary w-100 mt-3">
                                        <i class="bi bi-check-circle"></i> Submit
                                    </button> -->
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="container-field">
        <div class="row">
            <!-- Kolom Kiri -->

            <div class="col-lg-6">
                <div class="col-lg-12 form2" style="display: none;">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Form LKO 2</h5>
                            <!-- <button type="button" class="btn btn-light text-primary" onclick="showCounting(2)">
                                <i class="bi bi-gear"></i> Tombol Baru
                            </button> -->
                        </div>
                        <div class="card-body">
                            <button type="button" class="btn btn-success scan-button w-100 mb-3"
                                onclick="startScanning()">
                                <i class="bi bi-upc-scan"></i> Mulai Scan
                            </button>

                            <form id="form2" method="post">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="carline2" class="form-label">Carline</label>
                                        <input type="text" class="form-control" id="carline2" name="carline"
                                            value="<?= htmlspecialchars($carline ?? '') ?>" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="mesin2" class="form-label">mesin</label>
                                        <input type="text" class="form-control" id="mesin2" name="mesin"
                                            value="<?= htmlspecialchars($mesin ?? '') ?>" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="shift2" class="form-label">Shift</label>
                                        <input type="text" class="form-control" id="shift2" name="shift"
                                            value="<?= htmlspecialchars($shift ?? '') ?>" readonly>
                                    </div>
                                    <div class="col-12">
                                        <label for="time" class="form-label">Time</label>
                                        <input type="time   " class="form-control" id="time" name="time" required
                                            value="<?= htmlspecialchars($timeOnly); ?>" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="noIssue2" class="form-label">No Issue</label>
                                        <input type="text" class="form-control" id="noIssue2" name="noIssue"
                                            value="<?= htmlspecialchars($noproc2 ?? '') ?>" readonly>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="qty2" class="form-label">Quantity</label>
                                        <input type="number" class="form-control" id="qty2" name="qty"
                                            value="<?= htmlspecialchars($qty2 ?? '') ?>" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="scanKanban2" class="form-label">Scan Kanban</label>
                                        <input type="text" class="form-control" id="scanKanban2" name="scanKanban"
                                            value="<?= htmlspecialchars($kanban2 ?? '') ?>">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="kind2" class="form-label">Kind</label>
                                        <input type="text" class="form-control" id="kind2" name="kind"
                                            value="<?= htmlspecialchars($kind2 ?? '') ?>" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="size2" class="form-label">Size</label>
                                        <input type="text" class="form-control" id="size2" name="size"
                                            value="<?= htmlspecialchars($size2 ?? '') ?>" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="col2" class="form-label">COL</label>
                                        <input type="text" class="form-control" id="col2" name="col"
                                            value="<?= htmlspecialchars($col2 ?? '') ?>" readonly>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="r_c_h2" class="form-label">R C/H</label>
                                        <input type="text" class="form-control" id="r_c_h2" name="r_c_h"
                                            value="<?= htmlspecialchars($r_c_h ?? '') ?>" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="f_c_h2" class="form-label">F C/H</label>
                                        <input type="text" class="form-control" id="f_c_h2" name="f_c_h"
                                            value="<?= htmlspecialchars($f_c_h ?? '') ?>" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="terminal2" class="form-label">No. Terminal</label>
                                        <input type="text" class="form-control" id="terminal2" name="terminal"
                                            value="<?= htmlspecialchars($terminal2 ?? '') ?>" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="f_c_w2" class="form-label">F C/W</label>
                                        <input type="text" class="form-control" id="f_c_w2" name="f_c_w"
                                            value="<?= htmlspecialchars($f_c_w ?? '') ?>" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="r_c_w2" class="form-label">R C/W</label>
                                        <input type="text" class="form-control" id="r_c_w2" name="r_c_w"
                                            value="<?= htmlspecialchars($r_c_w ?? '') ?>" readonly>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="c_l" class="form-label">C/L</label>
                                        <input type="text" class="form-control" id="c_l" name="c_l"
                                            value="<?= htmlspecialchars($c_l2 ?? '') ?>" readonly>
                                    </div>

                                    <div class="col-md-4">
                                        <label for="kodeDefect" class="form-label">Kode Defect</label>
                                        <select class="form-control" id="kodeDefect" name="kodeDefect">
                                            <option value="">Pilih Kode Defect</option>
                                            <?php while ($row = mysqli_fetch_assoc($result2)): ?>
                                            <option value="<?= htmlspecialchars($row['no']) ?>">
                                                <?= htmlspecialchars($row['no'] . " - " . $row['item_defect']) ?>
                                            </option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="qtym2" class="form-label">Quantity defect</label>
                                        <input type="number" class="form-control" id="qtym2" name="qtym">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="lotTerminal2" class="form-label">No. Lot Terminal</label>
                                        <input type="text" class="form-control" id="lotTerminal2" name="lotTerminal"
                                            required>
                                    </div>

                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary w-100 mt-3">
                                            <i class="bi bi-save"></i> Simpan Data
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 counting2" data-form="2">
                <div class="card p-4 shadow-lg position-relative">
                    <div class="position-absolute top-0 start-0 p-3 text-dark rounded"
                        style="background-color:rgb(3, 124, 165);"><?= $noproc2; ?></div>
                    <button id="reset-output-1" class="btn btn-danger position-absolute top-0 end-0 m-3">Reset
                        Output</button>
                    <div class="box-container mt-5">
                        <div class="box" id="output-box-1">
                            <div class="mini-box">Quantity = <?= $qty2; ?></div>
                            <h3 id="output-1"><?= $output2; ?></h3>
                            <span class="label">Output</span>
                            <button class="btn btn-success mt-2" id="increment-output-1">+</button>
                        </div>

                        <div class="box" id="stroke-box-1">
                            <div class="mini-box">Max Stroke = <?= $max; ?></div>
                            <h3 id="current-1"><?= $currentStroke; ?></h3>
                            <span class="label">Current Stroke</span>
                            <button class="btn btn-success mt-2" id="increment-stroke-1">+</button>
                        </div>
                    </div>
                </div>
                <div class="container">
                    <div class="col-lg-12 mx-auto downtime1">
                        <div class="card">
                            <div class="card-header bg-danger text-white">
                                <h5 class="mb-0">Downtime Control</h5>
                            </div>
                            <div class="card-body">
                                <form class="downtimeForm" data-id="form2">
                                    <div class="mb-3">
                                        <label class="form-label">Kode Error</label>
                                        <select class="form-select codeError" required>
                                            <option value="">Pilih kode error</option>
                                            <option value="a">A</option>
                                            <option value="b">B</option>
                                            <option value="c">C</option>
                                            <option value="c1">C1</option>
                                            <option value="c2">C2</option>
                                            <option value="c3">C3</option>
                                            <option value="d">D</option>
                                            <option value="d1">D1</option>
                                            <option value="d2">D2</option>
                                            <option value="d3">D3</option>
                                            <option value="d4">D4</option>
                                            <option value="d5">D5</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Alasan (Opsional)</label>
                                        <textarea class="form-control reasonError" rows="3"></textarea>
                                    </div>
                                    <table class="table">
                                        <thead>
                                            <tr class="text-center">
                                                <th>No</th>
                                                <th>Kode Error</th>
                                                <th>Alasan</th>
                                                <th>Durasi</th>
                                                <th>Total</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="dataBody"></tbody>
                                    </table>
                                    <div class="timer-container">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <button type="button" class="btn btn-success startDowntime">
                                                <i class="bi bi-play-circle"></i> Mulai
                                            </button>
                                            <button type="button" class="btn btn-danger endDowntime" disabled>
                                                <i class="bi bi-stop-circle"></i> Akhiri
                                            </button>
                                        </div>
                                        <div class="timer text-center fs-4 fw-bold">00:00:00</div>
                                    </div>
                                    <!-- <button type="submit" class="btn btn-primary w-100 mt-3">
                                        <i class="bi bi-check-circle"></i> Submit
                                    </button> -->
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-field">
            <div class="row">
                <!-- Kolom Kiri -->

                <div class="col-lg-6">
                    <div class="col-lg-12 form3" style="display: none;">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Form LKO 3</h5>
                                <!-- <button type="button" class="btn btn-light text-primary" onclick="showCounting(3)">
                                <i class="bi bi-gear"></i> Tombol Baru
                            </button> -->

                            </div>
                            <div class="card-body">
                                <button type="button" class="btn btn-success scan-button w-100 mb-3"
                                    onclick="startScanning()">
                                    <i class="bi bi-upc-scan"></i> Mulai Scan
                                </button>

                                <form id="form3" method="post">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label for="carline3" class="form-label">Carline</label>
                                            <input type="text" class="form-control" id="carline3" name="carline"
                                                value="<?= htmlspecialchars($carline ?? '') ?>" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="mesin3" class="form-label">mesin</label>
                                            <input type="text" class="form-control" id="mesin3" name="mesin"
                                                value="<?= htmlspecialchars($mesin ?? '') ?>" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="shift3" class="form-label">Shift</label>
                                            <input type="text" class="form-control" id="shift3" name="shift"
                                                value="<?= htmlspecialchars($shift ?? '') ?>" readonly>
                                        </div>
                                        <div class="col-12">
                                            <label for="time" class="form-label">Time</label>
                                            <input type="time" class="form-control" id="time" name="time" required
                                                required value="<?= htmlspecialchars($timeOnly); ?>" readonly>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="noIssue3" class="form-label">No Issue</label>
                                            <input type="text" class="form-control" id="noIssue3" name="noIssue"
                                                value="<?= htmlspecialchars($noproc3 ?? '') ?>" readonly>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="qty3" class="form-label">Quantity</label>
                                            <input type="number" class="form-control" id="qty3" name="qty"
                                                value="<?= htmlspecialchars($qty3 ?? '') ?>" readonly>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="scanKanban3" class="form-label">Scan Kanban</label>
                                            <input type="text" class="form-control" id="scanKanban3" name="scanKanban"
                                                value="<?= htmlspecialchars($kanban3 ?? '') ?>">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="kind3" class="form-label">Kind</label>
                                            <input type="text" class="form-control" id="kind3" name="kind"
                                                value="<?= htmlspecialchars($kind3 ?? '') ?>" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="size3" class="form-label">Size</label>
                                            <input type="text" class="form-control" id="size3" name="size"
                                                value="<?= htmlspecialchars($size3 ?? '') ?>" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="col3" class="form-label">COL</label>
                                            <input type="text" class="form-control" id="col3" name="col"
                                                value="<?= htmlspecialchars($col3 ?? '') ?>" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="terminal3" class="form-label">No. Terminal</label>
                                            <input type="text" class="form-control" id="terminal3" name="terminal"
                                                value="<?= htmlspecialchars($terminal3 ?? '') ?>" readonly>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="f_c_h3" class="form-label">F C/H</label>
                                            <input type="text" class="form-control" id="f_c_h3" name="f_c_h"
                                                value="<?= htmlspecialchars($f_c_h ?? '') ?>" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="r_c_h3" class="form-label">R C/H</label>
                                            <input type="text" class="form-control" id="r_c_h3" name="r_c_h"
                                                value="<?= htmlspecialchars($r_c_h ?? '') ?>" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="f_c_w3" class="form-label">F C/W</label>
                                            <input type="text" class="form-control" id="f_c_w3" name="f_c_w"
                                                value="<?= htmlspecialchars($f_c_w ?? '') ?>" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="r_c_w3" class="form-label">R C/W</label>
                                            <input type="text" class="form-control" id="r_c_w3" name="r_c_w"
                                                value="<?= htmlspecialchars($r_c_w ?? '') ?>" readonly>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="c_l" class="form-label">C/L</label>
                                            <input type="text" class="form-control" id="c_l" name="c_l"
                                                value="<?= htmlspecialchars($c_l3 ?? '') ?>" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="kodeDefect" class="form-label">Kode Defect</label>
                                            <select class="form-control" id="kodeDefect" name="kodeDefect">
                                                <option value="">Pilih Kode Defect</option>
                                                <?php while ($row = mysqli_fetch_assoc($result3)): ?>
                                                <option value="<?= htmlspecialchars($row['no']) ?>">
                                                    <?= htmlspecialchars($row['no'] . " - " . $row['item_defect']) ?>
                                                </option>
                                                <?php endwhile; ?>
                                            </select>
                                        </div>
                                        <div class="col -md-4">
                                            <label for="qtym3" class="form-label">Quantity defect</label>
                                            <input type="number" class="form-control" id="qtym3" name="qtym">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="lotTerminal3" class="form-label">No. Lot Terminal</label>
                                            <input type="text" class="form-control" id="lotTerminal3" name="lotTerminal"
                                                required>
                                        </div>
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary w-100 mt-3">
                                                <i class="bi bi-save"></i> Simpan Data
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 counting3" data-form="3">
                    <div class="card p-4 shadow-lg position-relative">
                        <div class="position-absolute top-0 start-0 p-3 text-dark rounded"
                            style="background-color:rgb(4, 91, 120);"><?= $noproc3; ?></div>
                        <button id="reset-output-2" class="btn btn-danger position-absolute top-0 end-0 m-3">Reset
                            Output</button>

                        <div class="box-container mt-5">
                            <div class="box" id="output-box-2">
                                <div class="mini-box">Quantity = <?= $qty3; ?></div>
                                <h3 id="output-2"><?= $output3; ?></h3>
                                <span class="label">Output</span>
                                <button class="btn btn-success mt-2" id="increment-output-2">+</button>
                            </div>

                            <div class="box" id="stroke-box-2">
                                <div class="mini-box">Max Stroke = <?= $max; ?></div>
                                <h3 id="current-2"><?= $currentStroke; ?></h3>
                                <span class="label">Current Stroke</span>
                                <button class="btn btn-success mt-2" id="increment-stroke-2">+</button>
                            </div>
                        </div>
                    </div>
                    <div class="container">
                        <div class="col-lg-12 mx-auto downtime1">
                            <div class="card">
                                <div class="card-header bg-danger text-white">
                                    <h5 class="mb-0">Downtime Control</h5>
                                </div>
                                <div class="card-body">
                                    <form class="downtimeForm" data-id="form3">
                                        <div class="mb-3">
                                            <label class="form-label">Kode Error</label>
                                            <select class="form-select codeError" required>
                                                <option value="">Pilih kode error</option>
                                                <option value="a">A</option>
                                                <option value="b">B</option>
                                                <option value="c">C</option>
                                                <option value="c1">C1</option>
                                                <option value="c2">C2</option>
                                                <option value="c3">C3</option>
                                                <option value="d">D</option>
                                                <option value="d1">D1</option>
                                                <option value="d2">D2</option>
                                                <option value="d3">D3</option>
                                                <option value="d4">D4</option>
                                                <option value="d5">D5</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Alasan (Opsional)</label>
                                            <textarea class="form-control reasonError" rows="3"></textarea>
                                        </div>
                                        <table class="table">
                                            <thead>
                                                <tr class="text-center">
                                                    <th>No</th>
                                                    <th>Kode Error</th>
                                                    <th>Alasan</th>
                                                    <th>Durasi</th>
                                                    <th>Total</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody class="dataBody"></tbody>
                                        </table>
                                        <div class="timer-container">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <button type="button" class="btn btn-success startDowntime">
                                                    <i class="bi bi-play-circle"></i> Mulai
                                                </button>
                                                <button type="button" class="btn btn-danger endDowntime" disabled>
                                                    <i class="bi bi-stop-circle"></i> Akhiri
                                                </button>
                                            </div>
                                            <div class="timer text-center fs-4 fw-bold">00:00:00</div>
                                        </div>
                                        <!-- <button type="submit" class="btn btn-primary w-100 mt-3">
                                            <i class="bi bi-check-circle"></i> Submit
                                        </button> -->
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Tabel Data -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-dark text-white">
                            <h5 class="mb-0">Data Produksi</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="lkoData">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>No</th>
                                            <th>Carline</th>
                                            <th>Mesin</th>
                                            <th>Time</th>
                                            <th>Shift</th>
                                            <th>No. Proses</th>
                                            <th>Scan Kanban</th>
                                            <th>Quantity</th>
                                            <th>Kind</th>
                                            <th>Size</th>
                                            <th>COL</th>
                                            <th>Terminal</th>
                                            <th>Lot Terminal</th>
                                            <th>F C/H</th>
                                            <th>R C/H</th>
                                            <th>F C/W</th>
                                            <th>R C/W</th>
                                            <th>C/L</th>
                                            <th>Kode Defect</th>
                                            <th>Quantity defect</th>
                                            <th>Code Error</th>
                                            <th>Downtime</th>

                                        </tr>
                                    </thead>
                                    <tbody id="dataContainer">
                                        <?php
        if (isset($_SESSION['saved_data']) && !empty($_SESSION['saved_data'])) {
            foreach ($_SESSION['saved_data'] as $index => $data) {
                echo "<tr>
                    <td>" . ($index + 1) . "</td>
                    <td>{$data['carline']}</td>
                    <td>{$data['mesin']}</td>
                    <td>{$data['time']}</td>
                    <td>{$data['shift']}</td>
                    <td>{$data['noIssue']}</td>
                    <td>{$data['scanKanban']}</td>
                    <td>{$data['qty']}</td>
                    <td>{$data['kind']}</td>
                    <td>{$data['size']}</td>
                    <td>{$data['col']}</td>
                    <td>{$data['terminal']}</td>
                    <td>{$data['lotTerminal']}</td>
                    <td>{$data['f_c_h']}</td>
                    <td>{$data['r_c_h']}</td>
                    <td>{$data['f_c_w']}</td>
                    <td>{$data['r_c_w']}</td>
                    <td>{$data['c_l']}</td>
                    <td>{$data['kodeDefect']}</td>
                    <td>{$data['qtyM']}</td>
                    <td>{$data['codeError']}</td>
                    <td>{$data['downtime']}</td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='22' class='text-center'>Belum ada data</td></tr>";
        }
        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
            function startScanning() {
                let inputs = document.querySelectorAll('input[type="text"]');
                if (inputs.length > 0) {
                    inputs[0].focus(); // Fokus ke input pertama
                }
            }

            // Fungsi untuk memindahkan kursor ke input berikutnya saat Enter ditekan
            document.addEventListener('keydown', function(event) {
                if (event.key === "Enter") {
                    event.preventDefault(); // Mencegah form submit otomatis

                    let inputs = document.querySelectorAll(
                        'input[type="text"]'); // Ambil semua input teks
                    let index = Array.from(inputs).indexOf(document
                        .activeElement); // Temukan input yang aktif

                    if (index !== -1 && index < inputs.length - 1) {
                        inputs[index + 1].focus(); // Pindah ke input berikutnya
                    } else {
                        inputs[0].focus(); // Jika di input terakhir, kembali ke input pertama
                    }
                }
            });
            // couting stroke
            document.addEventListener('DOMContentLoaded', function() {
                $(document).ready(function() {
                    // Muat nilai output dari localStorage
                    for (let i = 0; i < 3; i++) {
                        let savedOutput = localStorage.getItem('output-' + i);
                        if (savedOutput !== null) {
                            $('#output-' + i).text(savedOutput); // Tampilkan nilai dari localStorage
                        }
                    }
                });
                // let qty = [<?= $qty1; ?>, <?= $qty2; ?>, <?= $qty3; ?>];

                // Fungsi untuk mengupdate output dan stroke
                function updateOutput(index) {
                    let output = localStorage.getItem('output-' + index) ? parseInt(localStorage.getItem(
                        'output-' +
                        index)) : 0;
                    let qtyList = [<?= $qty1; ?>, <?= $qty2; ?>, <?= $qty3; ?>];
                    let qty = qtyList[index] ?? 0;
                    if (output < qty) {
                        output++;
                        localStorage.setItem('output-' + index, output);
                        $('#output-' + index).text(output);
                        checkWarning(index);
                    }
                }

                function updateStroke(index) {
                    let currentStroke = parseInt($('#current-' + index).text());
                    let maxStroke = <?= $max; ?>;

                    if (currentStroke < maxStroke) {
                        currentStroke++;
                        $('#current-' + index).text(currentStroke); // Update langsung di UI

                        // Kirim data ke server di background tanpa mengganggu UI
                        setTimeout(() => {
                            $.post("lko_back.php", {
                                current_stroke: currentStroke,
                                no_stroke: <?= $no_stroke; ?>
                            }, function(data) {
                                let response = JSON.parse(data);
                                if (!response.success) {
                                    console.error("Gagal update stroke:", response.message);
                                }
                            });
                        }, 100); // Delay kecil agar tidak menghambat tampilan
                    }
                }


                function checkWarning(index) {
                    let output = localStorage.getItem('output-' + index) ? parseInt(localStorage.getItem(
                        'output-' +
                        index)) : 0;

                    let qtyList = [<?= $qty1; ?>, <?= $qty2; ?>, <?= $qty3; ?>];
                    let qty = qtyList[index] ?? 0; // Ambil qty sesuai index

                    let currentStroke = parseInt($('#current-' + index).text());
                    let maxStroke = <?= $max; ?>;

                    // Cek jika output sudah mencapai qty yang ditentukan
                    if (output >= qty) {
                        $('#output-box-' + index).addClass('danger');
                        alert("⚠️ Output sudah mencapai Quantity!");

                    } else {
                        $('#output-box-' + index).removeClass('danger');
                    }

                    // Cek jika currentStroke sudah mencapai maxStroke
                    if (currentStroke >= maxStroke) {
                        $('#stroke-box-' + index).addClass('danger');
                        alert("⚠️ Current Stroke sudah mencapai Max Stroke!");

                    } else {
                        $('#stroke-box-' + index).removeClass('danger');
                    }
                }
                // Event listener untuk tombol + dari keyboard
                document.addEventListener('keydown', function(event) {
                    console.log("Key pressed:", event.key, " | Code:", event.code);

                    // Deteksi tombol + dari keyboard utama atau Numpad
                    if ((event.code === 'Equal' && event.shiftKey) || event.code === 'NumpadAdd') {
                        event.preventDefault();
                        console.log("✅ Tombol + terdeteksi!");

                        // Ambil index form yang sedang aktif dari sessionStorage
                        let activeIndex = sessionStorage.getItem('currentFormIndex') ? parseInt(
                            sessionStorage
                            .getItem('currentFormIndex')) : 0;
                        console.log("🟢 Form aktif index:", activeIndex);

                        updateOutput(activeIndex);
                        updateStroke(activeIndex);
                    }
                });



                // Event listener untuk tombol reset dan increment
                for (let i = 0; i < 3; i++) {
                    $('#reset-output-' + i).click(function() {
                        localStorage.setItem('output-' + i, 0);
                        $('#output-' + i).text(0);
                        location.reload();
                        alert("✅ Output berhasil direset!");

                    });

                    $('#increment-output-' + i).click(function() {
                        updateOutput(i);
                        updateStroke(i);
                    });
                }
            });

            // form 

            document.addEventListener("DOMContentLoaded", function() {
                const counting = document.querySelectorAll('.counting1, .counting2, .counting3');
                const forms = document.querySelectorAll('.form1, .form2, .form3');
                const dataBody = document.getElementById('dataContainer');

                let currentFormIndex = sessionStorage.getItem('currentFormIndex') ?
                    parseInt(sessionStorage.getItem('currentFormIndex')) : 0;

                function showForm(index) {
                    forms.forEach((form, i) => {
                        form.style.display = (i === index) ? 'block' : 'none';
                    });

                    counting.forEach((el, j) => {
                        el.style.display = (j === index) ? 'block' : 'none';
                    });

                    sessionStorage.setItem('currentFormIndex', index);
                }

                // Debugging: Cek apakah form ditemukan
                console.log("Jumlah form ditemukan:", forms.length);

                forms.forEach((form) => {
                    form.querySelector("form").addEventListener("submit", function(event) {
                        event.preventDefault();

                        const formData = new FormData(this);
                        let formId = form.getAttribute("data-form"); // Ambil ID Form

                        let latestDowntime = "00:00:00";
                        let latestCodeError = "-";
                        let totalDowntime = "00:00:00";

                        let downtimeRecords = JSON.parse(sessionStorage.getItem(
                            "downtimeRecords")) || {};
                        let totalDowntimeKey = `totalDowntime_${formId}`;
                        let totalDowntimeMs = JSON.parse(sessionStorage.getItem(
                            totalDowntimeKey)) || 0;

                        if (downtimeRecords[formId] && downtimeRecords[formId].length > 0) {
                            const lastEntry = downtimeRecords[formId].slice(-1)[0];
                            if (lastEntry) {
                                latestDowntime = lastEntry.duration;
                                latestCodeError = lastEntry.codeError;
                            }
                        }

                        if (totalDowntimeMs > 0) {
                            totalDowntime = formatDuration(totalDowntimeMs);
                        }

                        const data = {
                            no: document.getElementById("dataContainer").children.length +
                                1,
                            carline: formData.get("carline"),
                            mesin: formData.get("mesin"),
                            time: formData.get("time"),
                            shift: formData.get("shift"),
                            noIssue: formData.get("noIssue"),
                            scanKanban: formData.get("scanKanban"),
                            qty: formData.get("qty"),
                            kind: formData.get("kind"),
                            size: formData.get("size"),
                            col: formData.get("col"),
                            terminal: formData.get("terminal"),
                            lotTerminal: formData.get("lotTerminal"),
                            f_c_h: formData.get("f_c_h"),
                            r_c_h: formData.get("r_c_h"),
                            f_c_w: formData.get("f_c_w"),
                            r_c_w: formData.get("r_c_w"),
                            c_l: formData.get("c_l"),
                            kodeDefect: formData.get("kodeDefect"),
                            qtyM: formData.get("qtym"),
                            codeError: latestCodeError,
                            downtime: totalDowntime // Total downtime hanya untuk form ini
                        };

                        console.log(`Data untuk Form ${formId}:`, data);
                        addRowToTable(data);
                        saveDataToSessionStorage(data);
                    });

                    showForm(currentFormIndex);
                });

                function addRowToTable(data) {
                    const row = document.createElement("tr");

                    row.innerHTML = `
            <td>${data.no}</td>
            <td>${data.carline}</td>
            <td>${data.mesin}</td>
            <td>${data.time}</td>
            <td>${data.shift}</td>
            <td>${data.noIssue}</td>
            <td>${data.scanKanban}</td>
            <td>${data.qty}</td>
            <td>${data.kind}</td>
            <td>${data.size}</td>
            <td>${data.col}</td>
            <td>${data.terminal}</td>
            <td>${data.lotTerminal}</td>
            <td>${data.f_c_h}</td>
            <td>${data.r_c_h}</td>
            <td>${data.f_c_w}</td>
            <td>${data.r_c_w}</td>
            <td>${data.c_l}</td>
            <td>${data.kodeDefect}</td>
            <td>${data.qtyM}</td>
            <td>${data.codeError}</td>
            <td>${data.downtime}</td>
        `;

                    document.getElementById("dataContainer").appendChild(row);

                    fetch("../validate/save_data.php", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json"
                            },
                            body: JSON.stringify(data)
                        })
                        .then(response => response.json())
                        .then(result => {
                            console.log(result.message);
                            alert(result.message);
                            redirectAfterSubmit();
                        })
                        .catch(error => console.error("Error:", error));
                }


                function removeRow(button) {
                    button.closest('tr').remove();
                }

                function redirectAfterSubmit() {
                    if (currentFormIndex === 0) {
                        sessionStorage.setItem('currentFormIndex', 1);
                        window.location.href = "app_term.php";
                    } else if (currentFormIndex === 1) {
                        sessionStorage.setItem('currentFormIndex', 2);
                        window.location.href = "app_term.php";
                    } else {
                        alert('Semua form telah dikirim!');
                        sessionStorage.clear(); // Reset index setelah semua form dikirim

                    }
                }

                function saveDataToSessionStorage(data) {
                    let storedData = JSON.parse(sessionStorage.getItem('lkoData')) || [];
                    storedData.push(data);
                    sessionStorage.setItem('lkoData', JSON.stringify(storedData));
                    console.log(storedData);
                    alert('Data telah disimpan!'); // Menampilkan pesan konfirmasi
                }

                window.removeRow = function(button) {
                    const row = button.closest('tr');
                    row.remove();
                }
            });




            function formatDuration(ms) {
                let elapsed = new Date(ms);
                let h = String(elapsed.getUTCHours()).padStart(2, '0');
                let m = String(elapsed.getUTCMinutes()).padStart(2, '0');
                let s = String(elapsed.getUTCSeconds()).padStart(2, '0');
                return `${h}:${m}:${s}`;
            }

            // Fungsi global untuk menghapus entry
            function deleteEntry(index, formId) {
                let downtimeRecords = JSON.parse(sessionStorage.getItem("downtimeRecords")) || {};
                if (downtimeRecords[formId]) {
                    downtimeRecords[formId].splice(index, 1);
                    sessionStorage.setItem("downtimeRecords", JSON.stringify(downtimeRecords));
                }
                location.reload();
            }

            // Fungsi global untuk mengambil data downtime
            function getDowntimeRecordsByForm(formId) {
                const downtimeRecords = JSON.parse(sessionStorage.getItem('downtimeRecords')) || {};
                return downtimeRecords[formId] || [];
                console.log(downtimeRecords);
            }

            document.addEventListener("DOMContentLoaded", function() {
                let timers = {};
                let startTimes = {};
                let durations = JSON.parse(sessionStorage.getItem('durations')) || {};
                let downtimeRecords = JSON.parse(sessionStorage.getItem('downtimeRecords')) || {};

                // Bagian untuk timer dan downtime
                document.querySelectorAll(".downtimeForm").forEach(formContainer => {
                    const formId = formContainer.dataset.id;
                    const timerElement = formContainer.querySelector(".timer");
                    const startButton = formContainer.querySelector(".startDowntime");
                    const endButton = formContainer.querySelector(".endDowntime");
                    const dataBody = formContainer.querySelector(".dataBody");
                    const codeErrorInput = formContainer.querySelector(".codeError");
                    const reasonErrorInput = formContainer.querySelector(".reasonError");

                    if (!durations[formId]) durations[formId] = 0;

                    const updateTable = () => {
                        dataBody.innerHTML = downtimeRecords[formId]?.map((entry, index) => `
                <tr>
                    <td>${index + 1}</td>
                    <td>${entry.codeError}</td>
                    <td>${entry.reasonError}</td>
                    <td>${entry.duration}</td>
                    <td>${entry.totalDowntime}</td>
                    <td><button onclick="deleteEntry(${index}, '${formId}')">Hapus</button></td>
                </tr>
            `).join('');
                    };

                    startButton.addEventListener("click", () => {
                        if (!timers[formId]) {
                            startTimes[formId] = Date.now(); // Simpan waktu mulai saat ini
                            timers[formId] = setInterval(() => {
                                const elapsedMs = Date.now() - startTimes[formId] +
                                    durations[formId];
                                timerElement.textContent = formatDuration(elapsedMs);
                            }, 1000);
                            startButton.disabled = true;
                            endButton.disabled = false;
                        }
                    });

                    endButton.addEventListener("click", () => {
                        if (timers[formId] && codeErrorInput.value) {
                            clearInterval(timers[formId]);
                            timers[formId] = null;

                            const elapsedMs = Date.now() - startTimes[formId];

                            // Simpan total downtime hanya untuk form terkait
                            let totalDowntimeKey = `totalDowntime_${formId}`;
                            let totalDowntime = JSON.parse(sessionStorage.getItem(
                                totalDowntimeKey)) || 0;
                            totalDowntime += elapsedMs;
                            sessionStorage.setItem(totalDowntimeKey, JSON.stringify(
                                totalDowntime));

                            // Buat entry downtime baru
                            const entry = {
                                codeError: codeErrorInput.value,
                                reasonError: reasonErrorInput.value || "-",
                                duration: formatDuration(elapsedMs),
                                totalDowntime: formatDuration(
                                    totalDowntime
                                ) // Total downtime hanya untuk form ini
                            };

                            // Simpan record downtime hanya untuk form terkait
                            downtimeRecords[formId] = [...(downtimeRecords[formId] || []),
                                entry
                            ];
                            sessionStorage.setItem("downtimeRecords", JSON.stringify(
                                downtimeRecords));

                            console.log(`Total Downtime (${formId}): `, formatDuration(
                                totalDowntime));

                            updateTable();
                            timerElement.textContent = formatDuration(durations[formId]);
                            startButton.disabled = false;
                            endButton.disabled = true;
                        } else {
                            alert("Masukkan kode error sebelum mengakhiri downtime.");
                        }
                    });

                    updateTable();
                });

                // Bagian untuk form submit

            });

            function deleteEntry(index, formId) {
                let downtimeRecords = JSON.parse(sessionStorage.getItem("downtimeRecords")) || {};
                if (downtimeRecords[formId]) {
                    downtimeRecords[formId].splice(index, 1);
                    sessionStorage.setItem("downtimeRecords", JSON.stringify(downtimeRecords));
                }
                location.reload();
            }

            function logout() {
                sessionStorage.clear();
                window.location.href = '../process/logout.php';
            }
            </script>


            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
            <!-- Script JavaScript tetap sama -->
            <?php
        mysqli_free_result($result1); // Bersihkan hasil query
        mysqli_free_result($result2);
        mysqli_free_result($result3);
        mysqli_close($conn); // Tutup koneksi database
        ?>
</body>

</html>