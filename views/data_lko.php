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


$jumlahInput = $_SESSION['jumlahInput'] ?? 0;
var_dump($jumlahInput);
// var_dump($_SESSION['original_noproc1']);

// var_dump($_SESSION['filtered_data']);

// Ambil data dari sesi setelah diperbarui
$searchResults = $_SESSION['search_results'];
$formData = $_SESSION['form_input_data'] ?? [];

$carline = isset($_SESSION['carline']) ? $_SESSION['carline'] : '';
$mesin = isset($_SESSION['mesin']) ? $_SESSION['mesin'] : '';
$shift = isset($_SESSION['shift']) ? $_SESSION['shift'] : '';


//variabel app_term.php3


$selectedData = $_SESSION['filtered_data'];
$terminal1 = isset($selectedData[0]['Terminal']) ? $selectedData[0]['Terminal'] : 'gaono data';
$terminal2 = isset($selectedData[1]['Terminal']) ? $selectedData[1]['Terminal'] : 'gaono data';
$terminal3 = isset($selectedData[2]['Terminal']) ? $selectedData[2]['Terminal'] : 'gaono data';
$terminal4 = isset($selectedData[3]['Terminal']) ? $selectedData[3]['Terminal'] : 'gaono data';
// var_dump($terminal1, $terminal2, $terminal3); 



// Ambil data yang difilter
$filteredApplicator = array_slice($searchResults['applicator-term']['data_cfm'] ?? [], 0, 3);
$filteredTerm = array_slice($searchResults['applicator-term']['data_crimping'] ?? [], 0, 3);
$filteredStroke = array_slice($searchResults['applicator-term']['data_stroke'] ?? [],  0, 5);
$filteredNoproc = array_slice($_SESSION['filtered_data'], 0, 5);
// var_dump($filteredNoproc);
$kanban1 = $_SESSION['original_noproc1'];
$kanban2 = $_SESSION['original_noproc2'];
$kanban3 = $_SESSION['original_noproc3'];


$noproc1 = $filteredNoproc[0]['noproc'] ?? 'N/A';
$noproc2 = $filteredNoproc[1]['noproc'] ?? 'N/A';
$noproc3 = $filteredNoproc[2]['noproc'] ?? 'N/A';

$ctrl1 = $filteredNoproc[0]['ctrl_no'] ?? 'N/A';
$ctrl2 = $filteredNoproc[1]['ctrl_no'] ?? 'N/A';
$ctrl3 = $filteredNoproc[2]['ctrl_no'] ?? 'N/A';

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
            <!-- <div class="d-flex align-items-center gap-2">
                <a href="cobalko.php" class="btn btn-success">Pergi</a>

                <form action="../process/logout.php" method="POST">
                    <input type="hidden" name="nik" value="<?php echo htmlspecialchars($user['nik']); ?>">
                    <button type="submit" class="btn btn-danger">🚪 Logout</button>
                </form>
            </div> -->


        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <?php for ($i = 0; $i < $jumlahInput; $i++): ?>
            <?php
            // Ambil data untuk form ke-$i
            $noproc = $filteredNoproc[$i]['noproc'] ?? 'N/A';
            $ctrl = $filteredNoproc[$i]['ctrl_no'] ?? 'N/A';
            $c_l = $filteredNoproc[$i]['c_l'] ?? 'N/A';
            $col = $filteredNoproc[$i]['col'] ?? 'N/A';
            $qty = $filteredNoproc[$i]['qty'] ?? 'N/A';
            $kind = $filteredNoproc[$i]['kind'] ?? 'N/A';
            $size = $filteredNoproc[$i]['size'] ?? 'N/A';
            $terminal = $filteredNoproc[$i]['Terminal'] ?? 'N/A';
            $kanban = ($i == 0) ? $kanban1 : (($i == 1) ? $kanban2 : $kanban3);
            ?>
            <!-- Kolom Kiri -->
            <div class="col-lg-6 mx-auto">
                <div class="col-lg-12 form<?= $i + 1 ?>" style="display: none;">
                    <div class="card">
                        <div
                            class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Form LKO <?= $i + 1 ?></h5>
                        </div>
                        <div class="card-body">
                            <form id="form<?= $i + 1 ?>" method="post">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="carline<?= $i ?>" class="form-label">Carline</label>
                                        <input type="text" class="form-control" id="carline<?= $i ?>" name="carline"
                                            value="<?= htmlspecialchars($carline) ?>" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="mesin<?= $i ?>" class="form-label">Mesin</label>
                                        <input type="text" class="form-control" id="mesin<?= $i ?>" name="mesin"
                                            value="<?= htmlspecialchars($mesin) ?>" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="shift<?= $i ?>" class="form-label">Shift</label>
                                        <input type="text" class="form-control" id="shift<?= $i ?>" name="shift"
                                            value="<?= htmlspecialchars($shift) ?>" readonly>
                                    </div>
                                    <div class="col-6">
                                        <label for="time<?= $i ?>" class="form-label">Time</label>
                                        <input type="time" class="form-control" id="time<?= $i ?>" name="time" required
                                            value="<?= htmlspecialchars($timeOnly ?? '') ?>" readonly>
                                    </div>
                                    <div class="col-6">
                                        <label for="ctrl_no<?= $i ?>" class="form-label">No Control</label>
                                        <input type="text" class="form-control" id="ctrl_no<?= $i ?>" name="ctrl_no"
                                            readonly value="<?= htmlspecialchars($ctrl) ?>">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="noIssue<?= $i ?>" class="form-label">No Issue</label>
                                        <input type="text" class="form-control" id="noIssue<?= $i ?>" name="noIssue"
                                            value="<?= htmlspecialchars($noproc) ?>" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="qty<?= $i ?>" class="form-label">Quantity</label>
                                        <input type="number" class="form-control" id="qty<?= $i ?>" name="qty"
                                            value="<?= htmlspecialchars($qty) ?>" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="scanKanban<?= $i ?>" class="form-label">Scan Kanban</label>
                                        <input type="text" class="form-control" id="scanKanban<?= $i ?>"
                                            name="scanKanban" value="<?= htmlspecialchars($kanban) ?>" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="kind<?= $i ?>" class="form-label">Kind</label>
                                        <input type="text" class="form-control" id="kind<?= $i ?>" name="kind"
                                            value="<?= htmlspecialchars($kind) ?>" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="size<?= $i ?>" class="form-label">Size</label>
                                        <input type="text" class="form-control" id="size<?= $i ?>" name="size"
                                            value="<?= htmlspecialchars($size) ?>" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="col<?= $i ?>" class="form-label">COL</label>
                                        <input type="text" class="form-control" id="col<?= $i ?>" name="col"
                                            value="<?= htmlspecialchars($col) ?>" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="terminal<?= $i ?>" class="form-label">No. Terminal</label>
                                        <input type="text" class="form-control" id="terminal<?= $i ?>" name="terminal"
                                            value="<?= htmlspecialchars($terminal) ?>" readonly>
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
                                        <label for="kodeDefect<?= $i ?>" class="form-label">Kode Defect</label>
                                        <select class="form-control" id="kodeDefect<?= $i ?>" name="kodeDefect">
                                            <option value="">Pilih Kode Defect</option>
                                            <?php while ($row = mysqli_fetch_assoc($result3)): ?>
                                            <option value="<?= htmlspecialchars($row['no']) ?>">
                                                <?= htmlspecialchars($row['no'] . " - " . $row['item_defect']) ?>
                                            </option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="qtym<?= $i ?>" class="form-label">Quantity defect</label>
                                        <input type="number" class="form-control" id="qtym<?= $i ?>" name="qtym"
                                            value="0" min="0" onblur="if(this.value===''){this.value='0';}">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="lotTerminal<?= $i ?>" class="form-label">No. Lot Terminal</label>
                                        <input type="text" class="form-control" id="lotTerminal<?= $i ?>"
                                            name="lotTerminal" required>
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
            <?php endfor; ?>
        </div>
        <div class="col-lg-6 counting2" data-form="2">
            <div class="card p-4 shadow-lg position-relative">
                <div class="position-absolute top-0 start-0 p-3 text-light rounded"
                    style="background-color:rgb(3, 124, 165);"><?= $noproc2; ?></div>
                <!-- <button id="reset-output-1" class="btn btn-danger position-absolute top-0 end-0 m-3">Reset
                        Output</button> -->
                <div class="box-container mt-5">
                    <div class="box" id="output-box-1">
                        <div class="mini-box">Quantity = <?= $qty2; ?></div>
                        <h3 id="output-1"><?= $output2; ?></h3>
                        <span class="label">Output</span>

                    </div>

                    <div class="box" id="stroke-box-1">
                        <div class="mini-box">Max Stroke = <?= $max; ?></div>
                        <h3 id="current-1"><?= $currentStroke; ?></h3>
                        <span class="label">Current Stroke</span>

                    </div>
                </div>
            </div>
            <div class="container">
                <div class="col-lg-12 mx-auto downtime2">
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
                                                    <td>" . htmlspecialchars($data['carline'] ?? '-') . "</td>
                                                    <td>" . htmlspecialchars($data['mesin'] ?? '-') . "</td>
                                                    <td>" . htmlspecialchars($data['time'] ?? '-') . "</td>
                                                    <td>" . htmlspecialchars($data['shift'] ?? '-') . "</td>
                                                    <td>" . htmlspecialchars($data['noIssue'] ?? '-') . "</td>
                                                    <td>" . htmlspecialchars($data['scanKanban'] ?? '-') . "</td>
                                                    <td>" . htmlspecialchars($data['qty'] ?? '0') . "</td>
                                                    <td>" . htmlspecialchars($data['kind'] ?? '-') . "</td>
                                                    <td>" . htmlspecialchars($data['size'] ?? '-') . "</td>
                                                    <td>" . htmlspecialchars($data['col'] ?? '-') . "</td>
                                                    <td>" . htmlspecialchars($data['terminal'] ?? '-') . "</td>
                                                    <td>" . htmlspecialchars($data['lotTerminal'] ?? '-') . "</td>
                                                    <td>" . htmlspecialchars($data['f_c_h'] ?? '-') . "</td>
                                                    <td>" . htmlspecialchars($data['r_c_h'] ?? '-') . "</td>
                                                    <td>" . htmlspecialchars($data['f_c_w'] ?? '-') . "</td>
                                                    <td>" . htmlspecialchars($data['r_c_w'] ?? '-') . "</td>
                                                    <td>" . htmlspecialchars($data['c_l'] ?? '-') . "</td>
                                                    <td>" . htmlspecialchars($data['kodeDefect'] ?? '-') . "</td>
                                                    <td>" . htmlspecialchars($data['qtyM'] ?? '0') . "</td>
                                                    <td>" . htmlspecialchars($data['codeError'] ?? '-') . "</td>
                                                    <td>" . htmlspecialchars($data['downtime'] ?? '00:00:00') . "</td>
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
    // function startScanning() {
    //     let inputs = document.querySelectorAll('input[type="text"]');
    //     if (inputs.length > 0) {
    //         inputs[0].focus(); // Fokus ke input pertama
    //     }
    // }

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



    // COUNTING STROKE//
    document.addEventListener('DOMContentLoaded', function() {
        $(document).ready(function() {
            // Muat nilai output dari localStorage
            for (let i = 0; i < 3; i++) {
                let savedOutput = localStorage.getItem('output-' + i);
                if (savedOutput !== null) {
                    $('#output-' + i).text(savedOutput);
                }
            }
        });

        function updateOutput(index) {
            let output = localStorage.getItem('output-' + index) ? parseInt(localStorage.getItem(
                'output-' + index)) : 0;

            let qtyList = <?= json_encode([$qty1 ?? 0, $qty2 ?? 0, $qty3 ?? 0]); ?>;

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
                $('#current-' + index).text(currentStroke);

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
                }, 100);
            }
        }

        function checkWarning(index) {
            let output = localStorage.getItem('output-' + index) ? parseInt(localStorage.getItem(
                'output-' + index)) : 0;

            let qtyList = <?= json_encode([$qty1 ?? 0, $qty2 ?? 0, $qty3 ?? 0]); ?>;

            let qty = qtyList[index] ?? 0;
            let currentStroke = parseInt($('#current-' + index).text());
            let maxStroke = <?= $max; ?>;

            if (output >= qty) {
                $('#output-box-' + index).addClass('danger');
                alert("⚠️ Output sudah mencapai Quantity!");
            } else {
                $('#output-box-' + index).removeClass('danger');
            }

            if (currentStroke >= maxStroke) {
                $('#stroke-box-' + index).addClass('danger');
                alert("⚠️ Current Stroke sudah mencapai Max Stroke!");
            } else {
                $('#stroke-box-' + index).removeClass('danger');
            }
        }

        document.addEventListener('keydown', function(event) {
            if ((event.code === 'Equal' && event.shiftKey) || event.code === 'NumpadAdd') {
                event.preventDefault();
                let activeIndex = sessionStorage.getItem('currentFormIndex') ? parseInt(
                    sessionStorage.getItem('currentFormIndex')) : 0;
                updateOutput(activeIndex);
                updateStroke(activeIndex);
            }
        });
    });
    // batas form stroke 






    // === Inisialisasi dan Event Listener Utama ===
    document.addEventListener("DOMContentLoaded", function() {
        // Inisialisasi elemen DOM
        const counting = document.querySelectorAll('.counting1, .counting2, .counting3');
        const forms = document.querySelectorAll('.form1, .form2, .form3');
        const dataBody = document.getElementById('dataContainer');

        // Ambil indeks form saat ini dari sessionStorage, jika tidak ada set ke 0
        let currentFormIndex = sessionStorage.getItem('currentFormIndex') ?
            parseInt(sessionStorage.getItem('currentFormIndex')) : 0;

        // Pemetaan antara currentFormIndex dan formId untuk downtime
        const formIdMap = {
            0: 'form1',
            1: 'form2',
            2: 'form3'
        };

        // Fungsi untuk menampilkan form berdasarkan indeks
        function showForm(index) {
            forms.forEach((form, i) => {
                form.style.display = (i === index) ? 'block' : 'none';
            });

            counting.forEach((el, j) => {
                el.style.display = (j === index) ? 'block' : 'none';
            });

            sessionStorage.setItem('currentFormIndex', index);
        }

        // Muat ulang data dari sessionStorage saat halaman dimuat
        function loadDataToTable() {
            const storedData = JSON.parse(sessionStorage.getItem('lkoData')) || [];
            dataBody.innerHTML = ''; // Kosongkan tabel sebelum memuat ulang
            storedData.forEach(data => {
                addRowToTable(data, false); // Tambahkan tanpa mengirim ke server
            });
        }

        // Panggil fungsi untuk memuat data saat halaman dimuat
        loadDataToTable();

        // Debugging: Cek jumlah form yang ditemukan
        console.log("Jumlah form ditemukan:", forms.length);

        // === Penanganan Submit Form ===
        forms.forEach((form) => {
            form.querySelector("form").addEventListener("submit", function(event) {
                event.preventDefault();

                const formData = new FormData(this);
                const formId = formIdMap[currentFormIndex];
                let isDowntimeRunning = false;

                // Ambil activeIndex dari sessionStorage
                const activeIndex = sessionStorage.getItem('currentFormIndex') ?
                    parseInt(sessionStorage.getItem('currentFormIndex')) : 0;

                // Ambil nilai output dan qty
                let output = localStorage.getItem('output-' + activeIndex) ? parseInt(
                    localStorage.getItem('output-' + activeIndex)) : 0;

                let qtyList = <?= json_encode([$qty1 ?? 0, $qty2 ?? 0, $qty3 ?? 0]); ?>;

                let qty = qtyList[activeIndex] ?? 0;

                // Validasi: Jika output < qty, batalkan submit
                if (output < qty) {
                    alert("⚠️ Output belum mencapai Quantity! Submit dibatalkan.");
                    return; // Hentikan proses submit
                }

                // Periksa status timer downtime dari localStorage (sebagai fallback)
                try {
                    isDowntimeRunning = localStorage.getItem(
                        `isDowntimeRunning_${formId}`) === "true";
                } catch (error) {
                    console.error("Error saat memeriksa status timer downtime:", error);
                    isDowntimeRunning = false;
                }

                // Jika downtime masih berjalan, batalkan submit
                if (isDowntimeRunning) {
                    alert(
                        "Harap selesaikan downtime terlebih dahulu dengan menekan tombol 'Akhiri' sebelum submit form!"
                    );
                    return;
                }

                // Inisialisasi data default untuk downtime dan kode error
                let latestDowntime = "00:00:00";
                let latestCodeError = "-";
                let totalDowntime = "00:00:00";

                let downtimeRecords = [];
                let totalDowntimeMs = 0;

                // Penanganan error saat mengambil data downtime dari localStorage
                try {
                    downtimeRecords = JSON.parse(localStorage.getItem(
                        `downtimeRecords_${formId}`)) || [];
                    totalDowntimeMs = JSON.parse(localStorage.getItem(
                        `totalDowntime_${formId}`)) || 0;
                } catch (error) {
                    console.error(
                        "Error saat mengambil data downtime dari localStorage:",
                        error);
                    downtimeRecords = [];
                    totalDowntimeMs = 0;
                }

                // Jika ada data downtime, ambil data terakhir
                if (downtimeRecords.length > 0) {
                    const lastEntry = downtimeRecords[downtimeRecords.length - 1];
                    latestDowntime = lastEntry.duration || "00:00:00";
                    latestCodeError = lastEntry.codeError || "-";
                }

                // Jika ada total downtime, format ke HH:MM:SS
                if (totalDowntimeMs > 0) {
                    totalDowntime = formatDuration(totalDowntimeMs);
                }

                // Siapkan data untuk ditampilkan di tabel
                const data = {
                    no: (JSON.parse(sessionStorage.getItem('lkoData')) || [])
                        .length + 1,
                    carline: formData.get("carline") || "-",
                    mesin: formData.get("mesin") || "-",
                    time: formData.get("time") || "-",
                    shift: formData.get("shift") || "-",
                    noIssue: formData.get("noIssue") || "-",
                    scanKanban: formData.get("scanKanban") || "-",
                    qty: formData.get("qty") || "0",
                    kind: formData.get("kind") || "-",
                    size: formData.get("size") || "-",
                    col: formData.get("col") || "-",
                    terminal: formData.get("terminal") || "-",
                    lotTerminal: formData.get("lotTerminal") || "-",
                    f_c_h: formData.get("f_c_h") || "-",
                    r_c_h: formData.get("r_c_h") || "-",
                    f_c_w: formData.get("f_c_w") || "-",
                    r_c_w: formData.get("r_c_w") || "-",
                    c_l: formData.get("c_l") || "-",
                    kodeDefect: formData.get("kodeDefect") || "-",
                    qtyM: formData.get("qtym") || "0",
                    codeError: latestCodeError || "-",
                    downtime: totalDowntime || "00:00:00"
                };

                console.log("Data yang akan ditampilkan di tabel:", data);
                addRowToTable(data, true); // Tambahkan dan kirim ke server
                saveDataToSessionStorage(data);
            });

            showForm(currentFormIndex);
        });
    });

    // === Penanganan Downtime ===
    document.addEventListener("DOMContentLoaded", () => {
        const forms = document.querySelectorAll(".downtimeForm");

        forms.forEach((form) => {
            // Inisialisasi elemen DOM untuk downtime
            const formId = form.dataset.id;
            const startButton = form.querySelector(".startDowntime");
            const endButton = form.querySelector(".endDowntime");
            const timerElement = form.querySelector(".timer");
            const codeErrorInput = form.querySelector(".codeError");
            const reasonErrorInput = form.querySelector(".reasonError");
            const dataBody = form.querySelector(".dataBody");

            let interval;

            // Ambil data dari localStorage saat halaman dimuat
            let downtimeRecords = [];
            let totalDowntime = 0;
            let startTime = null;

            try {
                downtimeRecords = JSON.parse(localStorage.getItem(
                    `downtimeRecords_${formId}`)) || [];
                totalDowntime = JSON.parse(localStorage.getItem(`totalDowntime_${formId}`)) ||
                    0;
                startTime = JSON.parse(localStorage.getItem(`startTime_${formId}`));
            } catch (error) {
                console.error("Error saat mengambil data downtime dari localStorage:", error);
                downtimeRecords = [];
                totalDowntime = 0;
                startTime = null;
            }

            // Status timer downtime (apakah sedang berjalan)
            let isDowntimeRunning = localStorage.getItem(`isDowntimeRunning_${formId}`) ===
                "true";

            // Fungsi untuk memformat durasi (milidetik) ke format HH:MM:SS
            const formatDuration = (milliseconds) => {
                const totalSeconds = Math.floor(milliseconds / 1000);
                const hours = Math.floor(totalSeconds / 3600);
                const minutes = Math.floor((totalSeconds % 3600) / 60);
                const seconds = totalSeconds % 60;
                return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
            };

            // Fungsi untuk memperbarui status tombol submit form utama
            function updateSubmitButtonStatus(formIndex) {
                const form = document.querySelector(`.form${formIndex + 1}`);
                if (form) {
                    const submitButton = form.querySelector("button[type='submit']");
                    if (submitButton) {
                        submitButton.disabled = isDowntimeRunning;
                    }
                }
            }

            // Fungsi untuk memperbarui tabel dengan data dari localStorage
            const updateTable = () => {
                dataBody.innerHTML = downtimeRecords.map((record, index) => `
                <tr>
                    <td>${index + 1}</td>
                    <td>${record.codeError}</td>
                    <td>${record.reasonError}</td>
                    <td>${record.duration}</td>
                    <td>${record.totalDowntime}</td>
                    
                </tr> 
            `).join("");
            };

            // Perbarui tabel saat halaman dimuat
            updateTable();

            // Jika ada waktu mulai yang disimpan, lanjutkan timer dan nonaktifkan tombol submit
            if (startTime) {
                startTime = new Date(startTime);
                startButton.disabled = true;
                endButton.disabled = false;
                isDowntimeRunning = true;
                localStorage.setItem(`isDowntimeRunning_${formId}`, "true");

                interval = setInterval(() => {
                    const elapsedMs = Date.now() - startTime;
                    timerElement.textContent = formatDuration(elapsedMs);
                }, 1000);

                // Nonaktifkan tombol submit form utama terkait
                updateSubmitButtonStatus(parseInt(formId.replace('form', '')) - 1);
            } else {
                isDowntimeRunning = false;
                localStorage.setItem(`isDowntimeRunning_${formId}`, "false");
                updateSubmitButtonStatus(parseInt(formId.replace('form', '')) - 1);
            }




            // Tombol "Mulai" ditekan
            startButton.addEventListener("click", () => {
                startTime = Date.now();
                localStorage.setItem(`startTime_${formId}`, JSON.stringify(startTime));
                startButton.disabled = true;
                endButton.disabled = false;
                isDowntimeRunning = true;
                localStorage.setItem(`isDowntimeRunning_${formId}`, "true");


                interval = setInterval(() => {
                    const elapsedMs = Date.now() - startTime;
                    timerElement.textContent = formatDuration(elapsedMs);
                }, 1000);

                // Nonaktifkan tombol submit form utama terkait
                updateSubmitButtonStatus(parseInt(formId.replace('form', '')) - 1);
            });

            // Fungsi untuk mengirim data ke server
            const sendDataToServer = () => {
                fetch("save_downtime.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            formId,
                            downtimeRecords
                        }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log("Data downtime berhasil dikirim:", data);
                    })
                    .catch(error => console.error("Error mengirim data downtime:", error));
            };

            // Tombol "Akhiri" ditekan
            endButton.addEventListener("click", () => {
                if (!codeErrorInput.value) {
                    alert("Pilih kode error terlebih dahulu!");
                    return;
                }

                clearInterval(interval);
                const elapsedMs = Date.now() - startTime;
                let totalDowntime = 0;

                try {
                    totalDowntime = JSON.parse(localStorage.getItem(
                        `totalDowntime_${formId}`)) || 0;
                } catch (error) {
                    console.error("Error saat mengambil totalDowntime:", error);
                    totalDowntime = 0;
                }

                totalDowntime += elapsedMs;

                const newRecord = {
                    codeError: codeErrorInput.value,
                    reasonError: reasonErrorInput.value || "-",
                    duration: formatDuration(elapsedMs),
                    totalDowntime: formatDuration(totalDowntime),
                };
                downtimeRecords.push(newRecord);
                localStorage.setItem(`downtimeRecords_${formId}`, JSON.stringify(
                    downtimeRecords));
                localStorage.setItem(`totalDowntime_${formId}`, JSON.stringify(
                    totalDowntime));

                localStorage.removeItem(`startTime_${formId}`);

                sendDataToServer();
                updateTable();

                // Set status timer downtime selesai
                isDowntimeRunning = false;
                localStorage.setItem(`isDowntimeRunning_${formId}`, "false");

                // Aktifkan kembali tombol submit form utama terkait
                updateSubmitButtonStatus(parseInt(formId.replace('form', '')) - 1);

                startButton.disabled = false;
                endButton.disabled = true;
                timerElement.textContent = "00:00:00";
                codeErrorInput.value = "";
                reasonErrorInput.value = "";
            });

            // Penanganan penghapusan entri dari tabel
            dataBody.addEventListener("click", (e) => {
                if (e.target.classList.contains("deleteEntry")) {
                    const index = e.target.dataset.index;
                    const deletedRecord = downtimeRecords[index];

                    const durationMs = parseDuration(deletedRecord.duration);
                    totalDowntime -= durationMs;

                    // Pastikan totalDowntime tidak kurang dari 0
                    if (totalDowntime < 0) {
                        totalDowntime = 0;
                    }

                    downtimeRecords.splice(index, 1);

                    localStorage.setItem(`downtimeRecords_${formId}`, JSON.stringify(
                        downtimeRecords));
                    localStorage.setItem(`totalDowntime_${formId}`, JSON.stringify(
                        totalDowntime));

                    updateTable();
                }
            });

            // Fungsi untuk mengonversi format HH:MM:SS ke milidetik
            const parseDuration = (duration) => {
                const [hours, minutes, seconds] = duration.split(":").map(Number);
                return (hours * 3600 + minutes * 60 + seconds) * 1000;
            };
        });
    });


    // === Fungsi untuk Menangani Tabel dan Penyimpanan Data ===
    /**
     * Fungsi untuk menambahkan baris ke tabel
     * @param {Object} data - Data yang akan ditambahkan ke tabel
     * @param {boolean} sendToServer - Apakah data perlu dikirim ke server
     */
    function addRowToTable(data, sendToServer = true) {
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

        if (sendToServer) {
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
                .catch(error => console.error("Error mengirim data ke server:", error));
        }
    }

    /**
     * Fungsi untuk menyimpan data ke sessionStorage
     * @param {Object} data - Data yang akan disimpan
     */
    function saveDataToSessionStorage(data) {
        let storedData = JSON.parse(sessionStorage.getItem('lkoData')) || [];
        storedData.push(data);
        sessionStorage.setItem('lkoData', JSON.stringify(storedData));
        console.log("Data disimpan di sessionStorage:", storedData);
    }

    /**
     * Fungsi global untuk menghapus baris dari tabel
     * @param {HTMLElement} button - Tombol yang memicu penghapusan
     */
    window.removeRow = function(button) {
        const row = button.closest('tr');
        const rowIndex = Array.from(row.parentElement.children).indexOf(row);
        let storedData = JSON.parse(sessionStorage.getItem('lkoData')) || [];
        storedData.splice(rowIndex, 1);
        sessionStorage.setItem('lkoData', JSON.stringify(storedData));
        row.remove();
        // Perbarui nomor urut di tabel
        const rows = document.getElementById("dataContainer").children;
        Array.from(rows).forEach((row, index) => {
            row.cells[0].textContent = index + 1;
        });
    };
    /**
     * Fungsi untuk menghapus semua data downtime dari localStorage dan reset UI
     */
    function clearAllDowntimeData() {
        try {
            // Simpan semua kunci yang akan dihapus terlebih dahulu
            const keysToRemove = [];
            for (let i = 0; i < localStorage.length; i++) {
                const key = localStorage.key(i);
                if (key.startsWith('downtimeRecords_') || key.startsWith('totalDowntime_') || key.startsWith(
                        'startTime_') || key.startsWith('isDowntimeRunning_')) {
                    keysToRemove.push(key);
                }
            }

            // Hapus semua kunci yang telah dikumpulkan
            keysToRemove.forEach(key => {
                localStorage.removeItem(key);
                console.log(`Kunci ${key} berhasil dihapus dari localStorage`);
            });

            // Reset UI dan total downtime untuk semua form downtime
            document.querySelectorAll(".downtimeForm").forEach(form => {
                const formId = form.dataset.id;
                const timerElement = form.querySelector(".timer");
                const startButton = form.querySelector(".startDowntime");
                const endButton = form.querySelector(".endDowntime");
                const codeErrorInput = form.querySelector(".codeError");
                const reasonErrorInput = form.querySelector(".reasonError");
                const dataBody = form.querySelector(".dataBody");

                // Reset total downtime di localStorage untuk form ini
                localStorage.setItem(`totalDowntime_${formId}`, "0");
                localStorage.setItem(`isDowntimeRunning_${formId}`, "false");

                // Reset UI
                if (timerElement) timerElement.textContent = "00:00:00";
                if (startButton) startButton.disabled = false;
                if (endButton) endButton.disabled = true;
                if (codeErrorInput) codeErrorInput.value = "";
                if (reasonErrorInput) reasonErrorInput.value = "";
                if (dataBody) dataBody.innerHTML = "";
            });

            // Pastikan tabel downtime diperbarui setelah semua data dihapus
            if (typeof updateTable === "function") {
                updateTable(); // Ini akan memastikan tabel kosong setelah reset
            }

            console.log("Semua data downtime berhasil dihapus dari localStorage dan UI direset");
        } catch (error) {
            console.error("Error saat menghapus semua data downtime:", error);
            alert('Gagal menghapus semua data downtime: ' + error.message);
        }
    }


    // === Fungsi untuk Redirect dan Logout ===
    /**
     * 
     * 
     * Fungsi untuk redirect setelah submit berhasil
     */

    /**


     * Fungsi untuk redirect setelah submit berhasil
     */
    let terminal1 = "<?php echo $terminal1; ?>";
    let terminal2 = "<?php echo $terminal2; ?>";
    let terminal3 = "<?php echo $terminal3; ?>";

    function redirectAfterSubmit() {
        try {
            let currentFormIndex = parseInt(sessionStorage.getItem('currentFormIndex')) || 0;
            if (currentFormIndex === 0) {
                sessionStorage.setItem('currentFormIndex', 1);
                if (terminal1 == terminal2) {
                    window.location.href = "data_lko2.php?formIndex=1";
                } else {
                    window.location.href = "app_term.php?formIndex=1";
                }
            } else if (currentFormIndex === 1) {
                sessionStorage.setItem('currentFormIndex', 2);
                if (terminal2 == terminal3) {
                    window.location.href = "data_lko2.php?formIndex=2";
                } else {
                    window.location.href = "app_term.php?formIndex=2";
                }
            } else {
                // Hapus indeks form dari sessionStorage
                sessionStorage.removeItem('currentFormIndex');

                // Hapus data output dari localStorage untuk semua indeks (0-3)
                for (let i = 0; i <= 3; i++) {
                    localStorage.removeItem('output-' + i);
                }

                // Hapus semua data downtime dari localStorage dan reset UI
                clearAllDowntimeData();

                window.location.href = "system.php";
            }
        } catch (error) {
            console.error("Error saat redirect setelah submit:", error);
            alert('Gagal melakukan redirect: ' + error.message);
        }
    }

    /**
     * Fungsi untuk logout dan membersihkan sessionStorage
     */
    function logout() {
        sessionStorage.clear();
        window.location.href = '../process/logout.php';
    }

    // === Fungsi Utilitas ===
    /**
     * Fungsi untuk memformat durasi ke dalam format HH:MM:SS
     * @param {number} ms - Durasi dalam milidetik
     * @returns {string} Durasi dalam format HH:MM:SS
     */
    function formatDuration(ms) {
        // Jika ms negatif, kembalikan "00:00:00" atau tangani sesuai kebutuhan
        if (ms < 0) {
            return "00:00:00";
        }

        const totalSeconds = Math.floor(ms / 1000);
        const hours = Math.floor(totalSeconds / 3600);
        const minutes = Math.floor((totalSeconds % 3600) / 60);
        const seconds = totalSeconds % 60;
        return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
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