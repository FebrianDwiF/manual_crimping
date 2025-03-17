<?php
include '../db/connection.php';
include '../process/auth.php';


//var_dump($nik);
// include '../views/system_backup2.php';



    // // Ambil data dari URL
    // $applicator = $_GET['applicator'] ?? 'Tidak ada data';
    // $term = $_GET['term'] ?? 'Tidak ada data';
    // echo "<p>Applicator: " . htmlspecialchars($applicator) . "</p>";
    // echo "<p>Terminal: " . htmlspecialchars($term) . "</p>";



$_SESSION['search_results'] = $_SESSION['search_results'] ?? [] ;
$searchResults = $_SESSION['search_results'];
// var_dump($searchResults);


$filteredApplicator = array_slice($searchResults['applicator-term']['data_cfm'] ?? [], 0, 3);

$filteredTerm = array_slice($searchResults['applicator-term']['data_crimping'] ?? [], 0, 3);
$filteredStroke = array_slice($searchResults['applicator-term']['data_stroke'] ?? [],  0, 3);
// var_dump($filteredStroke);

$filteredNoproc = array_slice($_SESSION['filtered_data'], 0, 10);
// var_dump($filteredNoproc);

// var_dump($filteredStroke);
// var_dump($filteredTerm);
// var_dump($filteredStroke);
$term = $filteredTerm[0]['term'] ?? 'N/A';
// var_dump($term);
// echo $term;

// print_r($filteredApplicator);
// print_r($filteredTerm);
// print_r($filteredStroke);
// print_r($filteredNoproc);

// $_SESSION['original_noproc1'] = $_SESSION['original_noproc1'] ?? '';
// $_SESSION['original_noproc2'] = $_SESSION['original_noproc2'] ?? '';
// $_SESSION['original_noproc3'] = $_SESSION['original_noproc3'] ?? '';

// $kanban1 = $_SESSION['original_noproc1'];
// $kanban2 = $_SESSION['original_noproc2'];
// $kanban3 = $_SESSION['original_noproc3'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pengukuran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/pengukuran.css">

</head>

<body>
    <div id="notification" style="position: fixed; top: 10px; left: 50%; transform: translateX(-50%);
            background: rgba(6, 181, 229, 0.9); color: white; padding: 15px 20px;
            border-radius: 8px; display: none; font-size: 16px; text-align: center;">
    </div>
    <div class="main-container">
        <h2 class="text-center mb-4">Data Pengukuran</h2>

        <!-- First Table -->
        <div class="table-section">
            <table class="table compact-table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 10%;">Carline</th>
                        <th style="width: 10%;">Mesin</th>
                        <th style="width: 10%;">Applicator</th>
                        <th style="width: 8%;">Man No</th>
                        <th style="width: 8%;">Kind</th>
                        <th style="width: 8%;">Size</th>
                        <th style="width: 10%;">Knop Spacer</th>
                        <th style="width: 8%;">Dial</th>
                        <th style="width: 8%;">No Prog</th>
                        <th style="width: 10%;">Max Stroke</th>
                        <th style="width: 10%;">Current Stroke</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($filteredApplicator as $applicator) {
                        $stroke = array_filter($filteredStroke, function ($s) use ($applicator) {
                            return $s['applicator'] == $applicator['applicator']; 
                        });
                        $stroke = reset($stroke) ?: null; // Ambil data pertama yang cocok

                        echo "<tr>";
                        echo "<td>{$applicator['no']}</td>";
                        echo "<td>{$applicator['carline']}</td>";
                        echo "<td>{$applicator['mesin']}</td>";
                        echo "<td>{$applicator['applicator']}</td>";
                        echo "<td>{$applicator['man_no']}</td>";
                        echo "<td>{$applicator['kind']}</td>";
                        echo "<td>{$applicator['size']}</td>";
                        echo "<td>{$applicator['knop_spacer']}</td>";
                        echo "<td>{$applicator['dial']}</td>";
                        echo "<td>{$applicator['no_prog']}</td>";

                        if ($stroke) {
                            echo "<td>{$stroke['max_stroke']}</td>";
                            echo "<td>{$stroke['current_stroke']}</td>";
                        } else {
                            echo "<td>-</td><td>-</td>";
                        }

                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <?php
if (!isset($_SESSION['filtered_data']) || empty($_SESSION['filtered_data'])) {
    echo "<p class='text-danger text-center'>Tidak ada data yang dipilih.</p>";
    exit();
}

$selectedData = array_filter($_SESSION['filtered_data']);
// var_dump($selectedData);
// echo "<pre>";
// print_r($_SESSION['filtered_data']);
// echo "</pre>";
?>
        <!-- Second Table -->
        <div class="table-section">
            <table class="table compact-table table-bordered table-striped">
                <thead class="table-primary">
                    <tr>
                        <th style="width: 8%;">Machine</th>
                        <th style="width: 8%;">No Process</th>
                        <th style="width: 8%;">No Control</th>
                        <th style="width: 8%;">Kind</th>
                        <th style="width: 5%;">Size</th>
                        <th style="width: 5%;">Col</th>
                        <th style="width: 10%;">Terminal</th>
                        <th style="width: 6%;">Strip</th>
                        <th style="width: 7%;">Half Strip</th>
                        <th style="width: 8%;">Man</th>
                        <th style="width: 5%;">Acc</th>
                        <th style="width: 10%;">Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                  if (!empty($selectedData)) {
                    foreach ($selectedData as $row) {
                        // Cek apakah elemen adalah array dan memiliki kunci 'machine' (data utama)
                        if (!is_array($row) || !isset($row['machine'])) {
                            continue; // Lewati data yang tidak sesuai
                        }
                    
                        echo "<tr>
                                <td>" . (!empty($row['machine']) ? $row['machine'] : '-') . "</td>
                                <td>" . (!empty($row['noproc']) ? $row['noproc'] : '-') . "</td>
                                <td>" . (!empty($row['ctrl_no']) ? $row['ctrl_no'] : '-') . "</td>
                                <td>" . (!empty($row['kind']) ? $row['kind'] : '-') . "</td>
                                <td>" . (!empty($row['size']) ? $row['size'] : '-') . "</td>
                                <td>" . (!empty($row['col']) ? $row['col'] : '-') . "</td>
                                <td>" . (!empty($row['Terminal']) ? $row['Terminal'] : '-') . "</td>
                                <td>" . (!empty($row['strip']) ? $row['strip'] : '-') . "</td>
                                <td>" . (!empty($row['half_strip']) ? $row['half_strip'] : '-') . "</td>
                                <td>" . (!empty($row['man']) ? $row['man'] : '-') . "</td>
                                <td>" . (!empty($row['acc']) ? $row['acc'] : '-') . "</td>
                                <td>" . (!empty($row['qty']) ? $row['qty'] : '-') . "</td>
                            </tr>";
                    }
                }                    
                             
                
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Form -->
        <div class="form-container">
            <form id="form-id">
                <h4 class="form-title">Form Pengukuran</h4>
                <div class="form-group">
                    <label for="nilai_f_c_h">Nilai F C/H: <span id="range_f_c_h" class="range-info"></span></label>
                    <input type="text" id="nilai_f_c_h" name="nilai_f_c_h" required>
                    <small id="error_f_c_h" class="error-message"></small>
                </div>
                <div class="form-group">
                    <label for="nilai_f_c_w">Nilai F C/W: <span id="range_f_c_w" class="range-info"></span></label>
                    <input type="text" id="nilai_f_c_w" name="nilai_f_c_w" required>
                    <small id="error_f_c_w" class="error-message"></small>
                </div>
                <div class="form-group">
                    <label for="nilai_r_c_w">Nilai R C/W: <span id="range_r_c_w" class="range-info"></span></label>
                    <input type="text" id="nilai_r_c_w" name="nilai_r_c_w" required>
                    <small id="error_r_c_w" class="error-message"></small>
                </div>
                <div class="form-group">
                    <label for="nilai_r_c_h">Nilai R C/H: <span id="range_r_c_h" class="range-info"></span></label>
                    <input type="text" id="nilai_r_c_h" name="nilai_r_c_h" required>
                    <small id="error_r_c_h" class="error-message"></small>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    </div>

    <script>
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
    $(document).ready(function() {
        let ranges = {};

        function fetchRangeData() {
            const termValue = <?php echo json_encode($term = $filteredTerm[0]['term'] ?? 'N/A'); ?>;
            console.log("Term Value:", termValue);

            if (!termValue || termValue === 'N/A') {
                $('#error-message').text("Parameter term harus diisi").show();
                return;
            }

            $.ajax({
                url: '../process/get_range.php',
                method: 'GET',
                data: {
                    term: termValue
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success && response.ranges) {
                        ranges = response.ranges;
                        $('#error-message').hide();

                        Object.keys(ranges).forEach(key => {
                            $(`#range_${key}`).text(
                                `(${parseFloat(ranges[key].min).toFixed(2)} - ${parseFloat(ranges[key].max).toFixed(2)})`
                            );
                        });
                    } else {
                        $('#error-message').text("Rentang data tidak valid.").show();
                    }
                },
                error: function() {
                    $('#error-message').text("Terjadi kesalahan saat memuat rentang data.")
                        .show();
                }
            });
        }

        function validateInput(field, value) {
            if (!ranges[field]) return `Rentang untuk ${field} tidak ditemukan.`;
            if (value < ranges[field].min || value > ranges[field].max) {
                return `Nilai harus antara ${ranges[field].min} dan ${ranges[field].max}.`;
            }
            return null;
        }

        $('#form-id input[type="text"]').on('input', function() {
            const field = this.id.replace('nilai_', '');
            const numericValue = parseFloat(this.value.trim());
            const errorElement = $(`#error_${this.id}`);

            if (isNaN(numericValue)) {
                $(this).css('border', '2px solid red');
                errorElement.text("Nilai harus angka dan tidak boleh kosong.");
            } else {
                const errorMessage = validateInput(field, numericValue);
                $(this).css('border', errorMessage ? '2px solid red' : '2px solid lightgreen');
                errorElement.text(errorMessage || "");
            }
        });

        $('#form-id').on('submit', function(event) {
            event.preventDefault();

            let isValid = true;
            let validationResults = [];
            let inputData = {};

            $('#form-id input[type="text"]').each(function() {
                const field = this.id.replace('nilai_', '');
                const numericValue = parseFloat(this.value.trim());
                const errorElement = $(`#error_${this.id}`);

                if (isNaN(numericValue)) {
                    isValid = false;
                    validationResults.push(
                        `${field}: Nilai harus angka dan tidak boleh kosong.`);
                    $(this).css('border', '2px solid red');
                    errorElement.text("Nilai harus angka dan tidak boleh kosong.");
                } else {
                    const errorMessage = validateInput(field, numericValue);
                    if (errorMessage) {
                        isValid = false;
                        validationResults.push(`${field}: ${errorMessage}`);
                        $(this).css('border', '2px solid red');
                        errorElement.text(errorMessage);
                    } else {
                        $(this).css('border', '2px solid lightgreen');
                        errorElement.text("");
                        inputData[field] = numericValue;
                    }
                }
            });

            function showNotification(message) {
                let notification = document.getElementById("notification");
                notification.innerHTML = message;
                notification.style.display = "block";

                setTimeout(() => {
                    notification.style.display = "none";
                }, 500); // Hilang setelah 2 detik
            }

            if (isValid) {
                saveSearchResults(inputData, 'form-input');

                showNotification("✅ Form submitted successfully!");
                setTimeout(() => {
                    window.location.href = "data_lko2.php";
                }, 500);
            } else {
                showNotification("❌ Validation failed!");
            }



        });

        function saveSearchResults(data, type) {
            if (!data || Object.keys(data).length === 0 || !type) {
                console.error("Data kosong atau tipe tidak valid sebelum dikirim.");
                return;
            }

            $.ajax({
                url: '../process/save_search_results.php',
                method: 'POST',
                data: {
                    results: JSON.stringify(data),
                    type: type,
                },
                success: function(response) {
                    console.log("Response dari server:", response);
                },
                error: function(xhr, status, error) {
                    console.error("Gagal menyimpan data:", error);
                }
            });
        }

        fetchRangeData();
    });

    function adjustTableHeights() {
        const availableHeight = window.innerHeight - 300;
        document.querySelectorAll('.table-responsive').forEach(el => {
            el.style.maxHeight = `${availableHeight / 2}px`;
        });
    }

    window.addEventListener('resize', adjustTableHeights);
    adjustTableHeights();
    </script>

</body>

</html>