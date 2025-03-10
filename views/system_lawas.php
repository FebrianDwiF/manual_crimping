<?php
include '../db/connection.php';
include '../process/auth.php';
var_dump(value: $nik);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Search Data by Keyword</title>
    <!-- <link rel="stylesheet" href="../public/css/system.css"> -->
</head>
<style>
/* Global Styles */
body {
    font-family: 'Arial', sans-serif;
    background-color: #f8f8f8;
    margin: 0;
    padding: 0;
    font-size: 14px;
    line-height: 1.6;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    color: #333;
}

/* Title Styling */
h1 {
    font-size: 2em;
    font-weight: 600;
    margin-bottom: 15px;
    text-align: center;
}

/* Container */
.container {
    width: 80%;
    max-width: 1000px;
    padding: 25px;
    background-color: #fff;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
    border-radius: 12px;
    margin-top: 20px;
}

h2.title-section {
    font-size: 1.3em;
    font-weight: 600;
    margin-bottom: 12px;
    text-align: left;
}

/* Form Styling */
form {
    background: #fff;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    width: 100%;
    max-width: 600px;
    margin-bottom: 20px;
}

form label {
    display: block;
    font-weight: 500;
    margin-bottom: 6px;
}

form input,
form select,
form textarea {
    width: 100%;
    padding: 12px;
    margin-bottom: 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 1em;
    transition: border 0.3s ease;
}

form input:focus,
form select:focus,
form textarea:focus {
    border-color: #555;
    outline: none;
}

form button {
    width: 100%;
    padding: 12px;
    border: none;
    border-radius: 8px;
    font-size: 1em;
    font-weight: 600;
    background-color: #444;
    color: white;
    cursor: pointer;
    transition: background 0.3s ease;
}

form button:hover {
    background-color: #666;
}

form button:disabled {
    background-color: #aaa;
    cursor: not-allowed;
}

/* Table Styling */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
    overflow: hidden;
}

table th,
table td {
    padding: 14px;
    text-align: center;
    font-size: 1em;
}

table th {
    background-color: #444;
    color: white;
    font-weight: 600;
}

table tbody tr:hover {
    background-color: #f2f2f2;
}

table td {
    border-top: 1px solid #ddd;
}

/* Button Container */
.button-container {
    display: flex;
    justify-content: center;
    gap: 15px;
    margin: 20px 0;
}

.button-container button {
    background-color: #444;
    color: white;
    border: none;
    border-radius: 8px;
    padding: 12px 18px;
    font-size: 1em;
    cursor: pointer;
    transition: background 0.3s ease, transform 0.2s ease;
}

.button-container button:hover {
    background-color: #666;
    transform: scale(1.05);
}

.button-container button:focus {
    outline: none;
    box-shadow: 0 0 4px rgba(51, 51, 51, 0.6);
}

/* Sidebar (Tetap Dipertahankan) */
.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: 250px;
    height: 100vh;
    background-color: #2c3e50;
    color: #ecf0f1;
    padding: 20px;
    box-shadow: 2px 0 5px rgba(0, 0, 0, 0.2);
}

.sidebar h3 {
    font-size: 18px;
    margin-bottom: 20px;
}

.sidebar a {
    display: block;
    color: #ecf0f1;
    text-decoration: none;
    padding: 12px 15px;
    margin-bottom: 8px;
    border-radius: 6px;
    transition: background 0.3s ease, transform 0.2s ease;
}

.sidebar a:hover {
    background-color: #34495e;
    transform: scale(1.05);
}

.sidebar a.operator,
.sidebar a.logout {
    font-weight: bold;
    text-align: center;
    padding: 14px;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.sidebar a.operator {
    background-color: #3b82f6;
}

.sidebar a.operator:hover {
    background-color: #2563eb;
}

.sidebar a.logout {
    background-color: #ef4444;
    margin-top: 20px;
}

.sidebar a.logout:hover {
    background-color: #dc2626;
}

/* Responsive Adjustments */
@media (max-width: 768px) {

    table th,
    table td {
        font-size: 14px;
        padding: 10px;
    }
}

@media (max-width: 768px) {
    .sidebar {
        width: 100%;
        height: auto;
        position: relative;
    }

    .content {
        margin-left: 0;
    }

    .card {
        padding: 15px;
    }
}
</style>

<body>

    <div class="sidebar">

        <?php
        date_default_timezone_set("Asia/Jakarta");?>
        <h3><?= "Login Time: " . htmlspecialchars($user['loginTime']);?></h3>
        <br>
        <h3><?= "Welcome Operator, " . htmlspecialchars($user['name']);?></h3>
        <h3><?="Nik : ".htmlspecialchars($user['nik']);?></h3>


        <br>
        <a href="./operator.php" class="operator">Operator Page</a>

        <form action="../process/logout.php" method="POST">
            <input type="hidden" name="nik" value="<?php echo htmlspecialchars($nik); ?>">
            <button type="submit" class="logout"> 🚪 Logout</button>
        </form>
    </div>

    <h1>Search Data</h1>

    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalMesin">
        Pilih Mesin
    </button>
    <div class="d-flex">
        <div class="modal fade" id="modalMesin" tabindex="-1" aria-labelledby="modalMesinLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalMesinLabel">Masukkan Mesin</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="form-mesin" method="POST">
                            <!-- Shift -->
                            <div class="mb-3">
                                <label class="form-label">Shift:</label>
                                <select name="shift" id="shift" class="form-select">
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                </select>
                            </div>

                            <!-- Carline -->
                            <div class="mb-3">
                                <label for="carline" class="form-label">Carline:</label>
                                <input type="text" name="carline" id="carline" class="form-control"
                                    placeholder="Enter Carline">
                                <div class="invalid-feedback" id="carlineError">Carline harus diisi!</div>
                            </div>

                            <!-- Mesin -->
                            <div class="mb-3">
                                <label for="mesin" class="form-label">Mesin:</label>
                                <input type="text" name="mesin" id="mesin" class="form-control"
                                    placeholder="Enter Mesin">
                                <div class="invalid-feedback" id="mesinError">Nomor Mesin harus diisi!</div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary" id="submit-mesin" disabled>Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const form = document.getElementById("form-mesin");
        const submitButton = document.getElementById("submit-mesin");
        const mesinInput = document.getElementById("mesin");
        const carlineInput = document.getElementById("carline");
        const formNoproc = document.getElementById("form-noproc");
        const formApplicatorTerm = document.getElementById("form-applicator-term");
        const formId = document.getElementById("form-id"); // Form tambahan
        const hiddenMesin = document.getElementById("hidden-mesin");
        const hiddenMesinApplicator = document.getElementById("hidden-mesin-applicator");

        async function validateInput(input, type) {
            try {
                let response = await $.ajax({
                    url: "../validate/val_system.php",
                    method: "POST",
                    data: {
                        [type]: input.value.trim()
                    },
                    dataType: "json",
                });

                if (!response.valid) {
                    input.classList.add("is-invalid");
                    return false;
                } else {
                    input.classList.remove("is-invalid");
                    return true;
                }
            } catch {
                alert("Terjadi kesalahan validasi. Coba lagi.");
                return false;
            }
        }

        async function validateForm() {
            let mesinValid = await validateInput(mesinInput, "mesin");
            let carlineValid = await validateInput(carlineInput, "carline");
            submitButton.disabled = !(mesinValid && carlineValid);
        }

        mesinInput.addEventListener("input", validateForm);
        carlineInput.addEventListener("input", validateForm);

        form.addEventListener("submit", async function(event) {
            event.preventDefault(); // ✅ Cegah halaman refresh

            await validateForm();
            if (!submitButton.disabled) {
                let formData = {
                    shift: document.getElementById("shift").value,
                    mesin: mesinInput.value,
                    carline: carlineInput.value
                };
                $.ajax({
                    url: "../validate/save_session.php",
                    method: "POST",
                    data: formData,
                    success: function(response) {
                        console.log("Data berhasil disimpan:", response);
                    },
                    error: function() {
                        alert("Gagal menyimpan data.");
                    }
                });
                // ✅ Simpan data ke input hidden
                hiddenMesin.value = mesinInput.value;
                hiddenMesinApplicator.value = mesinInput.value;

                // ✅ Tampilkan form tambahan
                formNoproc.style.display = "block";
                formApplicatorTerm.style.display = "block";

                // ✅ Tutup modal setelah sukses
                let modalElement = document.getElementById("modalMesin");
                let modalInstance = bootstrap.Modal.getInstance(modalElement);
                modalInstance.hide();
            }
        });

        // ✅ Tambahkan listener untuk form-applicator-term
        formApplicatorTerm.addEventListener("submit", function(event) {
            event.preventDefault(); // Cegah submit default
            // Lakukan validasi atau aksi lain jika diperlukan
            let valid = true; // Gantilah ini dengan logika validasi sesungguhnya

            if (valid) {
                formId.style.display = "block"; // Tampilkan form-id setelah validasi
            } else {
                alert("Isi data pada form applicator dengan benar.");
            }
        });
    });
    </script>
    <br>







    <!-- Form Nomor Proses (Disembunyikan Awalnya) -->
    <form id="form-noproc" method="GET" style="display: none;">
        <input type="hidden" name="mesin" id="hidden-mesin">

        <label for="noproc1">Nomor Proses 1:</label>
        <input type="text" name="noproc1" id="noproc1" class="form-control" placeholder="Enter Nomor Proses 1">

        <label for="noproc2">Nomor Proses 2:</label>
        <input type="text" name="noproc2" id="noproc2" class="form-control" placeholder="Enter Nomor Proses 2">

        <label for="noproc3">Nomor Proses 3:</label>
        <input type="text" name="noproc3" id="noproc3" class="form-control" placeholder="Enter Nomor Proses 3">

        <button type="submit">Search</button>
    </form>

    <!-- Form Applicator & Terminal (Disembunyikan Awalnya) -->
    <form id="form-applicator-term" method="GET" style="display: none;">
        <input type="hidden" name="mesin" id="hidden-mesin-applicator">
        <input type="hidden" name="noproc1">
        <input type="hidden" name="noproc2">
        <input type="hidden" name="noproc3">

        <label for="applicator">Applicator:</label>
        <input type="text" name="applicator" id="applicator" class="form-control" placeholder="Enter Applicator">

        <label for="term">Terminal:</label>
        <input type="text" name="term" id="term" class="form-control" placeholder="Enter Terminal">

        <button type="submit">Search</button>
    </form>

    <!-- Tempat untuk menampilkan hasil pencarian -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <div id="error-message" style="color: red; display: none;"></div>
    <div id="result"></div>

    <div id="result-container"></div>
    <div id="search-results"></div>
    <script>
    $(document).ready(function() {
        $("#form-noproc").submit(function(event) {
            event.preventDefault(); // Mencegah reload halaman

            let originalValues = {
                noproc1: $("#noproc1").val(),
                noproc2: $("#noproc2").val(),
                noproc3: $("#noproc3").val(),
            };

            let processedValues = {
                noproc1: originalValues.noproc1.substring(1, 5),
                noproc2: originalValues.noproc2.substring(1, 5),
                noproc3: originalValues.noproc3.substring(1, 5),
            };

            $.ajax({
                url: "process2.php",
                type: "POST",
                data: {
                    original: originalValues, // Kirim nilai asli
                    processed: processedValues, // Kirim nilai yang dipotong
                },
                success: function(response) {
                    $("#result").html(response); // Tampilkan hasil di div
                },
                error: function() {
                    $("#result").html(
                        "<p style='color:red;'>Terjadi kesalahan saat mencari data.</p>"
                    );
                },
            });
        });

        // Tangani pemilihan Side A/B tanpa reload
        $(document).on("submit", "#side-selection-form", function(event) {
            event.preventDefault();

            $.ajax({
                url: "save_selection.php",
                type: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    $("#result").html(response);
                },
                error: function() {
                    $("#result").html("<p style='color:red;'>Gagal menyimpan pilihan.</p>");
                }
            });
        });
    });



    $(document).ready(function() {
        $("#side-selection-form").submit(function(e) {
            e.preventDefault(); // Mencegah form melakukan submit normal

            $.ajax({
                type: "POST",
                url: "save_selection.php?t=" + new Date().getTime(), // Hindari cache
                data: $(this).serialize(),
                beforeSend: function() {
                    $("#result-container").html(
                        "<p class='text-warning'>Processing...</p>");
                },

                success: function(response) {
                    console.log(response); // Debugging: cek response dari server

                    if (response.trim() === "success") {
                        location.reload(); // Refresh untuk memastikan session diperbarui
                    } else {
                        $("#result-container").html(
                            response); // Tampilkan respons jika ada error
                    }
                },
                error: function() {
                    $("#result-container").html(
                        "<p class='text-danger'>Error processing request.</p>");
                }
            });
        });


        // Definisikan variabel di luar fungsi

        $('#form-applicator-term').on('submit', function(e) {
            e.preventDefault(); // Mencegah refresh halaman

            const applicator = $('#applicator').val();
            const term = $('#term').val();

            if (!applicator && !term) {
                $('#error-message').text('Applicator dan Terminal harus diisi!').show();
                return;
            }

            $.ajax({
                url: '../process/get_term.php',
                method: 'GET',
                data: {
                    applicator: applicator,
                    term: term,
                },
                success: function(response) {
                    $('#error-message')
                        .hide(); // Sembunyikan pesan error jika sukses
                    let tablesHtml = '';

                    for (const [tableName, rows] of Object.entries(response)) {
                        tablesHtml +=
                            `<h3 style="margin-top: 20px;">${tableName}</h3>`;
                        tablesHtml += `
                                <table border="1" style="
                                    width: 100%; 
                                    border-collapse: collapse; 
                                    margin-bottom: 20px;
                                    font-family: Arial, sans-serif;
                                    font-size: 14px;">
                                <thead><tr>
                                 
                                `;

                        if (Array.isArray(rows) && rows.length > 0) {
                            const columns = Object.keys(rows[0]);
                            columns.forEach(column => {
                                tablesHtml += `
                                        <th style="
                                            padding: 10px; 
                                            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08); 
                                            text-align: left;   
                                            border: 1px solid #ddd;">
                                            ${column}
                                        </th>`;
                            });

                            tablesHtml += '<tbody>';
                            rows.forEach((row, index) => {
                                tablesHtml += `<tr>`;
                                if (tableName === 'data_stroke') {}
                                columns.forEach(column => {
                                    tablesHtml += `
                                            <td class="${column === 'current_stroke' ? 'current-stroke' : ''}" 
                                                data-column="${column}">
                                                ${row[column] || '-'}
                                            </td>`;
                                });
                                tablesHtml += `</tr>`;
                            });
                            tablesHtml += '</tbody>';
                        } else {
                            tablesHtml += `
                                    <tbody>
                                        <tr>
                                            <td colspan="100%" style="
                                                padding: 10px; 
                                                border: 1px solid #ddd; 
                                                text-align: center; 
                                                font-style: italic;">
                                                Data tidak ditemukan
                                            </td>
                                        </tr>
                                    </tbody>`;
                        }
                        tablesHtml += '</table>';
                    }

                    $('#search-results').html(tablesHtml);

                    saveSearchResults(response, 'applicator-term');
                    console.log(response);
                },
                error: function(xhr, status, error) {
                    $('#error-message').text(`Error: ${xhr.status} - ${error}`)
                        .show();
                }
            });
        });

        function saveSearchResults(data, type) {
            if (!data || data.length === 0 || !type) {
                console.error("Data atau type tidak valid.");
                return;
            }

            // Simpan data ke sessionStorage sebelum mengirim ke server
            sessionStorage.setItem(`savedSearchResults_${type}`, JSON.stringify(data));
            console.log(`Data ${type} disimpan ke sessionStorage.`, data);

            // Logika khusus berdasarkan type
            if (type === 'noproc') {
                console.log("Proses khusus untuk 'noproc' dijalankan.");
            } else if (type === 'applicator-term') {
                console.log("Proses khusus untuk 'applicator-term' dijalankan.");
            }

            // Kirim data ke server
            $.ajax({
                url: '../process/save_search_results.php',
                method: 'POST',
                data: {
                    results: JSON.stringify(data),
                    type: type,
                },
                success: function(response) {
                    console.log(`Data ${type} berhasil disimpan ke server:`, response);
                },
                error: function(xhr, status, error) {
                    console.error(`Gagal menyimpan data ${type} ke server:`, error);
                },
            });
        }


        //     $(document).on('click', '#submit', function() {
        //         const selectedRow = $('input[name="select-row"]:checked');
        //         if (selectedRow.length === 0) {
        //             alert('Pilih salah satu baris terlebih dahulu!');
        //             return;
        //         }

        //         // Ambil data dari baris yang dipilih
        //         const no = selectedRow.data('no');
        //         const currentStroke = selectedRow.data('current-stroke');
        //         const carline = selectedRow.data('carline');
        //         const mesin = selectedRow.data('mesin');



        //         // Redirect ke 4.php setelah AJAX sukses
        //         window.location.href =
        //             `4.php?no=${encodeURIComponent(no)}&current_stroke=${encodeURIComponent(currentStroke)}&carline=${encodeURIComponent(carline)}&mesin=${encodeURIComponent(mesin)}`;

        //     });

        //     function addCountingFunctionality() {
        //         // Hapus listener yang sudah ada sebelumnya
        //         $(document).off('keydown', handleKeydown);

        //         // Tambahkan listener baru
        //         $(document).on('keydown', handleKeydown);
        //     }

        //     function handleKeydown(event) {
        //         if (event.key === '+') {
        //             const selectedRow = $('input[name="select-row"]:checked');
        //             if (selectedRow.length === 0) {
        //                 alert("Select tombol!");
        //                 return;
        //             }

        //             const no = selectedRow.data('no');
        //             if (!no) {
        //                 console.error("Atribut data-no tidak ditemukan pada elemen input.");
        //                 return;
        //             }

        //             const currentStrokeCell = selectedRow.closest('tr').find('.current-stroke');
        //             let currentValue = parseInt(currentStrokeCell.text().trim()) || 0;
        //             currentValue += 1;
        //             currentStrokeCell.text(currentValue);

        //             updateCurrentStrokeInDatabase(no, currentValue);
        //         }
        //     }


        //     function updateCurrentStrokeInDatabase(no, currentValue) {
        //         console.log("Mengirim data ke backend:", {
        //             no,
        //             current_stroke: currentValue
        //         });
        //         $.ajax({
        //             url: '../process/stroke_update_system.php',
        //             method: 'POST',
        //             data: {
        //                 no: no,
        //                 current_stroke: currentValue,
        //             },
        //             success: function(response) {
        //                 console.log('Update sukses:', response);
        //             },
        //             error: function(xhr, status, error) {
        //                 console.error(`Error: ${xhr.status} - ${error}`);
        //             }
        //         });
        //     }
        // });
        // $(document).on('click', '#submit', function() {
        //     const selectedRow = $('input[name="select-row"]:checked');
        //     if (selectedRow.length === 0) {
        //         alert('Pilih salah satu baris terlebih dahulu!');
        //         return;
        //     }

        //     // Ambil data dari baris yang dipilih
        //     const no = selectedRow.data('no');
        //     const currentStroke = selectedRow.data('current-stroke');

        //     // Redirect ke halaman lain dengan query string
        //     const targetUrl =
        //         `lko.php?no=${encodeURIComponent(no)}&current_stroke=${encodeURIComponent(currentStroke)}`;
        //     window.location.href = targetUrl;
    });
    </script>


    <!-- <div id="sessionData"></div>
    <script>
    const savedData = sessionStorage.getItem("displayedContent");
    if (savedData) {
        document.getElementById("sessionData").innerHTML = savedData;
    } else {
        document.getElementById("sessionData").innerText = "Tidak ada data yang disimpan.";
    }
    </script> -->


    <form id="form-id" style="display: none;">
        <label for="nilai_f_c_h">Nilai F C/H:</label>
        <input type="text" id="nilai_f_c_h" name="nilai_f_c_h" required>
        <span id="range_f_c_h" class="range-info" style="margin-left: 10px; color: blue;"></span>
        <small id="error_f_c_h" class="error-message" style="color: red;"></small>

        <label for="nilai_f_c_w">Nilai F C/W:</label>
        <input type="text" id="nilai_f_c_w" name="nilai_f_c_w" required>
        <span id="range_f_c_w" class="range-info" style="margin-left: 10px; color: blue;"></span>
        <small id="error_f_c_w" class="error-message" style="color: red;"></small>

        <label for="nilai_r_c_w">Nilai R C/W:</label>
        <input type="text" id="nilai_r_c_w" name="nilai_r_c_w" required>
        <span id="range_r_c_w" class="range-info" style="margin-left: 10px; color: blue;"></span>
        <small id="error_r_c_w" class="error-message" style="color: red;"></small>

        <label for="nilai_r_c_h">Nilai R C/H:</label>
        <input type="text" id="nilai_r_c_h" name="nilai_r_c_h" required>
        <span id="range_r_c_h" class="range-info" style="margin-left: 10px; color: blue;"></span>
        <small id="error_r_c_h" class="error-message" style="color: red;"></small>

        <button type="submit">Submit</button>
    </form>
    <div id="error-message" style="color: red; display: none;"></div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        let ranges = {};

        function fetchRangeData() {
            const termValue = $('#term').val();


            if (!termValue) {
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
                        $('#range_f_c_h').text(
                            `(${parseFloat(response.ranges.f_c_h.min).toFixed(2)} - ${parseFloat(response.ranges.f_c_h.max).toFixed(2)})`
                        );
                        $('#range_f_c_w').text(
                            `(${parseFloat(response.ranges.f_c_w.min).toFixed(2)} - ${parseFloat(response.ranges.f_c_w.max).toFixed(2)})`
                        );
                        $('#range_r_c_w').text(
                            `(${parseFloat(response.ranges.r_c_w.min).toFixed(2)} - ${parseFloat(response.ranges.r_c_w.max).toFixed(2)})`
                        );
                        $('#range_r_c_h').text(
                            `(${parseFloat(response.ranges.r_c_h.min).toFixed(2)} - ${parseFloat(response.ranges.r_c_h.max).toFixed(2)})`
                        );
                    } else {
                        $('#error-message').text("Rentang data tidak valid.").show();
                    }
                },
                error: function() {
                    $('#error-message').text("Terjadi kesalahan saat memuat rentang data.").show();
                }

            });
            console.log(termValue);
        }

        function validateInput(field, value) {
            if (!ranges[field]) return `Rentang untuk ${field} tidak ditemukan.`;
            if (value < ranges[field].min || value > ranges[field].max) {
                return `Nilai harus antara ${ranges[field].min} dan ${ranges[field].max}.`;
            }
            return null;
        }

        // Hanya validasi input dalam form-id
        $('#form-id input[type="text"]').on('input', function() {
            const field = this.id.replace('nilai_', '');
            const value = this.value.trim();
            const numericValue = parseFloat(value);
            const errorElement = $(`#error_${this.id}`);

            if (value === '' || isNaN(numericValue)) {
                $(this).css('border', '2px solid red');
                errorElement.text("Nilai harus angka dan tidak boleh kosong.");
            } else {
                const errorMessage = validateInput(field, numericValue);
                if (errorMessage) {
                    $(this).css('border', '2px solid red');
                    errorElement.text(errorMessage);
                } else {
                    $(this).css('border', '2px solid lightgreen');
                    errorElement.text("");
                }
            }
        });

        $('#form-id').on('submit', function(event) {
            event.preventDefault();

            let isValid = true;
            let validationResults = [];
            let inputData = {}; // Menyimpan data input yang valid

            $('#form-id input[type="text"]').each(function() {
                const field = this.id.replace('nilai_', '');
                const value = this.value.trim();
                const numericValue = parseFloat(value);
                const errorElement = $(`#error_${this.id}`);

                if (value === '' || isNaN(numericValue)) {
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
                        inputData[field] = numericValue; // Simpan nilai yang valid
                    }
                }
            });

            if (isValid) {
                // Panggil fungsi untuk menyimpan data
                saveSearchResults(inputData, 'form-input');

                if (confirm("Validation passed! Do you want to proceed?")) {
                    alert("Form submitted successfully!");
                    if (confirm("Do you want to go to the lko.php page?")) {
                        window.location.href = "data_lko.php";
                    }
                }
            } else {
                alert("Validation failed:\n\n" + validationResults.join("\n"));
            }
        });

        // ✅ Fungsi untuk menyimpan semua data input ke PHP
        function saveSearchResults(data, type) {
            if (!data || Object.keys(data).length === 0 || !type) {
                console.error("Data kosong atau tipe tidak valid sebelum dikirim.");
                return;
            }

            $.ajax({
                url: '../process/save_search_results.php',
                method: 'POST',
                data: {
                    results: JSON.stringify(data), // Kirim sebagai JSON
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


        $('#form-applicator-term').on('submit', function(event) {
            event.preventDefault();
            fetchRangeData();
        });
    });
    </script>


    <div id="loginTime" style="display: none;">
        <?php echo isset($_SESSION['loginTime']) ? htmlspecialchars($_SESSION['loginTime']) : 'Unknown'; ?>
    </div>

    <div id="loginDetails" style="display: none;">
        <span id="loginName"><?php echo isset($user['name']) ? htmlspecialchars($user['name']) : 'Unknown'; ?></span>
        <span id="loginNIK"><?php echo isset($user['nik']) ? htmlspecialchars($user['nik']) : 'Unknown'; ?></span>
    </div>

    <div style="margin-top: 20px;">
        <button id="download" onclick="downloadCSV()">Download CSV</button>
        <button id="print" onclick="printTable()">Print</button>
    </div>

    <script>
    function downloadCSV() {
        const tables = document.querySelectorAll("table");
        let csvContent = "";

        // Ambil data form
        const form = document.getElementById("form-id");
        if (form) {
            const formData = new FormData(form);
            csvContent += "Form Data\n"; // Header untuk bagian form
            formData.forEach((value, key) => {
                csvContent += `${key},${value}\n`;
            });
            csvContent += "\n"; // Pisahkan dengan tabel
        }

        // Ambil data tabel
        tables.forEach(table => {
            const headers = Array.from(table.querySelectorAll("th")).map(th => th.textContent.trim());
            csvContent += headers.join(",") + "\n";

            table.querySelectorAll("tbody tr").forEach(row => {
                const rowData = Array.from(row.querySelectorAll("td")).map(td => td.textContent
                    .trim());
                csvContent += rowData.join(",") + "\n";
            });

            csvContent += "\n";
        });

        const blob = new Blob([csvContent], {
            type: "text/csv;charset=utf-8;"
        });
        const url = URL.createObjectURL(blob);
        const link = document.createElement("a");
        link.setAttribute("href", url);
        link.setAttribute("download", "search_results.csv");
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }


    function printTable() {
        const tables = Array.from(document.querySelectorAll("table")).map(table => table.outerHTML).join("<br>");
        const loginTime = document.getElementById("loginTime").textContent.trim();

        const loginName = document.getElementById("loginName").textContent.trim();
        const loginNIK = document.getElementById("loginNIK").textContent.trim();
        // Ambil data form
        const form = document.getElementById("form-id");
        let formContent = "<h2>Form Data</h2><table border='1' style='border-collapse: collapse; width: 100%;'>";
        formContent += "<tr><th>Field</th><th>Value</th></tr>";

        if (form) {
            const formData = new FormData(form);
            formData.forEach((value, key) => {
                formContent += `<tr><td>${key}</td><td>${value}</td></tr>`;
            });
        }
        formContent += "</table><br>";

        // Gabungkan form dan tabel untuk dicetak
        const contentToPrint = `
            <html>
                <head>
                    <title>Print Table</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
                        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                        th { background-color: #007BFF; color: white; }
                    </style>
                </head>
                <body>
                    <h1>Search Results</h1>
                    <div><strong>Login Time:</strong> ${loginTime}</div>
                    <div><strong>Nama:</strong> ${loginName}</div>
                    <div><strong>NIK:</strong> ${loginNIK}</div>
                    ${tables}
                    ${formContent}
                </body>
            </html>
        `;

        const newWindow = window.open("", "_blank");
        newWindow.document.write(contentToPrint);
        newWindow.document.close();
        newWindow.print();
    }
    </script>
    <br>

</body>

</html>