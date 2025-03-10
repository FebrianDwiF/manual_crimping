<?php
include '../db/connection.php';
session_start();
$searchResults = $_SESSION['search_results'];
$filteredStroke = array_slice($searchResults['applicator-term']['data_stroke'] ?? [], 0, 3);
$filteredNoproc = array_slice($_SESSION['filtered_data'], 0, 3);
var_dump($filteredStroke);
// Ambil data dari database

$noproc1 = $filteredNoproc[0]['noproc'] ?? 'N/A';

$noproc2 = $filteredNoproc[1]['noproc'] ?? 'N/A';

$noproc3 = $filteredNoproc[2]['noproc'] ?? 'N/A';
$output1 = 0;
$output2 = 0;

$output3 = 0;
$qty1 = $filteredNoproc[0]['qty'] ?? 'N/A';

$qty2 = $filteredNoproc[1]['qty'] ?? 'N/A';

$qty3 = $filteredNoproc[2]['qty'] ?? 'N/A';
$no_stroke = $filteredStroke[0]['no'] ?? 'N/A';
$applicator = $filteredStroke[0]['applicator'] ?? 'N/A';

$sql = "SELECT current_stroke, max_stroke FROM data_stroke WHERE applicator = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $applicator);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $currentStroke = $row['current_stroke'];
    $maxStroke = $row['max_stroke'];
} else {
    $currentStroke = 'N/A';
    $maxStroke = 'N/A';
}

$current = $currentStroke;
$max =  $maxStroke;
var_dump( 'iki sg tak gae',$currentStroke);





?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Counting Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
    .box-container {
        display: flex;
        justify-content: space-between;
        gap: 20px;
    }

    .box {
        flex: 1;
        padding: 20px;
        border: 2px solid black;
        background-color: #f8f9fa;
        text-align: center;
        border-radius: 10px;
        position: relative;
    }

    .mini-box {
        position: absolute;
        font-size: 20px;
        top: 10px;
        right: 10px;
        padding: 5px 10px;
        background: white;
        border: 1px solid black;
        font-weight: bold;
        border-radius: 5px;
    }

    .box h3 {
        margin-top: 40px;
        font-size: 30px;
    }

    .label {
        font-size: 14px;
        font-weight: bold;
        margin-top: 10px;
        display: block;
    }

    .danger {
        background-color: red !important;
        color: white !important;
    }
    </style>
</head>

<body>

<div class="container-fluid">
    <div class="row flex-nowrap overflow-auto p-3">
        <!-- Downtime Control Form 1 -->
        <div class="col-md-4 col-sm-6 col-12 mb-3">
            <div class="card h-100">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">Downtime Control - Form 1</h5>
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
                    </form>
                </div>
            </div>
        </div>

        <!-- Downtime Control Form 2 -->
        <div class="col-md-4 col-sm-6 col-12 mb-3">
            <div class="card h-100">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">Downtime Control - Form 2</h5>
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
                    </form>
                </div>
            </div>
        </div>

        <!-- Downtime Control Form 3 -->
        <div class="col-md-4 col-sm-6 col-12 mb-3">
            <div class="card h-100">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">Downtime Control - Form 3</h5>
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
                    </form>
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
                                                <th>Code Error</th>
                                                <th>Downtime</th>

                                            </tr>
                                     </thead>
                                            <tbody id="dataContainer">
                                            <?php
                                            if (isset($_SESSION['saved_data']) && !empty($_SESSION['saved_data'])) {
                                                foreach ($_SESSION['saved_data'] as $index => $data) {
                                                    echo "<tr>
                                                        <td>{$data['codeError']}</td>
                                                        <td>{$data['duration']}</td>
                                                    </tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='2' class='text-center'>Belum ada data</td></tr>";
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
    document.addEventListener("DOMContentLoaded", () => {
    const forms = document.querySelectorAll(".downtimeForm");

    forms.forEach((form) => {
        const formId = form.dataset.id;
        const startButton = form.querySelector(".startDowntime");
        const endButton = form.querySelector(".endDowntime");
        const timerElement = form.querySelector(".timer");
        const codeErrorInput = form.querySelector(".codeError");
        const reasonErrorInput = form.querySelector(".reasonError");
        const dataBody = form.querySelector(".dataBody");

        let interval;

        // Ambil data dari localStorage saat halaman dimuat
        let downtimeRecords = JSON.parse(localStorage.getItem(`downtimeRecords_${formId}`)) || [];
        let totalDowntime = JSON.parse(localStorage.getItem(`totalDowntime_${formId}`)) || 0;
        let startTime = JSON.parse(localStorage.getItem(`startTime_${formId}`)); // Waktu mulai yang disimpan

        // Fungsi untuk memformat durasi (ms) ke format HH:MM:SS
        const formatDuration = (milliseconds) => {
            const totalSeconds = Math.floor(milliseconds / 1000);
            const hours = Math.floor(totalSeconds / 3600);
            const minutes = Math.floor((totalSeconds % 3600) / 60);
            const seconds = totalSeconds % 60;
            return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
        };

        // Fungsi untuk memperbarui tabel dengan data dari localStorage
        const updateTable = () => {
            dataBody.innerHTML = downtimeRecords.map((record, index) => `
                <tr>
                    <td>${index + 1}</td>
                    <td>${record.codeError}</td>
                    <td>${record.reasonError}</td>
                    <td>${record.duration}</td>
                    <td>${record.totalDowntime}</td>
                    <td><button class="btn btn-sm btn-danger deleteEntry" data-index="${index}">Hapus</button></td>
                </tr>
            `).join("");
        };

        // Perbarui tabel saat halaman dimuat
        updateTable();

        // Jika ada waktu mulai yang disimpan, lanjutkan timer
        if (startTime) {
            startTime = new Date(startTime); // Konversi ke objek Date
            startButton.disabled = true;
            endButton.disabled = false;

            // Mulai timer berdasarkan waktu yang tersimpan
            interval = setInterval(() => {
                const elapsedMs = Date.now() - startTime;
                timerElement.textContent = formatDuration(elapsedMs);
            }, 1000);
        }

        // Tombol "Mulai" ditekan
        startButton.addEventListener("click", () => {
           
            startTime = Date.now(); // Catat waktu mulai
            localStorage.setItem(`startTime_${formId}`, JSON.stringify(startTime)); // Simpan waktu mulai ke localStorage
            startButton.disabled = true;
            endButton.disabled = false;

            // Mulai timer
            interval = setInterval(() => {
                const elapsedMs = Date.now() - startTime;
                timerElement.textContent = formatDuration(elapsedMs);
            }, 1000);
        });

        // Fungsi untuk mengirim data ke server (save_downtime.php)
                    const sendDataToServer = () => {
                fetch("save_downtime.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ downtimeRecords }),
                })
                .then(response => response.json())
                .then(data => {
                    console.log("Data berhasil dikirim:", data);
                })
                .catch(error => console.error("Error mengirim data:", error));
            };

        // Tombol "Akhiri" ditekan
        endButton.addEventListener("click", () => {
            if (!codeErrorInput.value) {
                alert("Pilih kode error terlebih dahulu!");
                return;
            }

            clearInterval(interval); // Hentikan timer
            const elapsedMs = Date.now() - startTime; // Hitung durasi downtime ini
            totalDowntime += elapsedMs; // Tambahkan ke total downtime

            // Simpan data ke localStorage
            const newRecord = {
                codeError: codeErrorInput.value,
                reasonError: reasonErrorInput.value || "-",
                duration: formatDuration(elapsedMs),
                totalDowntime: formatDuration(totalDowntime),
            };
            downtimeRecords.push(newRecord);
            localStorage.setItem(`downtimeRecords_${formId}`, JSON.stringify(downtimeRecords));
            localStorage.setItem(`totalDowntime_${formId}`, JSON.stringify(totalDowntime));

            // Hapus waktu mulai dari localStorage karena downtime sudah selesai
            localStorage.removeItem(`startTime_${formId}`);

            // Perbarui tabel
            sendDataToServer();
            updateTable();

            // Reset form
            startButton.disabled = false;
            endButton.disabled = true;
            timerElement.textContent = "00:00:00";
            codeErrorInput.value = "";
            reasonErrorInput.value = "";
        });

        // Hapus entri dari tabel
        dataBody.addEventListener("click", (e) => {
            if (e.target.classList.contains("deleteEntry")) {
                const index = e.target.dataset.index;
                const deletedRecord = downtimeRecords[index];

                // Kurangi total downtime
                const durationMs = parseDuration(deletedRecord.duration); // Konversi HH:MM:SS ke milidetik
                totalDowntime -= durationMs;

                // Hapus entri dari array
                downtimeRecords.splice(index, 1);

                // Simpan data yang diperbarui ke localStorage
                localStorage.setItem(`downtimeRecords_${formId}`, JSON.stringify(downtimeRecords));
                localStorage.setItem(`totalDowntime_${formId}`, JSON.stringify(totalDowntime));

                // Perbarui tabel
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
    </script>




</body>

</html>