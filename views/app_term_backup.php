<?php
include '../db/connection.php';
include '../process/auth.php';


// var_dump($nik);

if (isset($_GET['term'])) {
    $_SESSION['term'] = trim($_GET['term']);
}


$_SESSION['search_results'] = $_SESSION['search_results'] ?? [] ;
$searchResults = $_SESSION['search_results'];


$filteredApplicator = array_slice($searchResults['applicator-term']['data_cfm'] ?? [], 0, 3);
$filteredTerm = array_slice($searchResults['applicator-term']['data_crimping'] ?? [], 0, 3);
$filteredStroke = array_slice($searchResults['applicator-term']['data_stroke'] ?? [],  0, 3);
$_SESSION['original_noproc1'] = $_SESSION['original_noproc1'] ?? '';
$_SESSION['original_noproc2'] = $_SESSION['original_noproc2'] ?? '';
$_SESSION['original_noproc3'] = $_SESSION['original_noproc3'] ?? '';

$kanban1 = $_SESSION['original_noproc1'];
$kanban2 = $_SESSION['original_noproc2'];
$kanban3 = $_SESSION['original_noproc3'];
// var_dump($kanban1, $kanban2, $kanban3);
$selectedData = $_SESSION['filtered_data'];
// var_dump($selectedData);
$terminal1 = isset($selectedData[0]['Terminal']) ? $selectedData[0]['Terminal'] : 'gaono data';
$terminal2 = isset($selectedData[1]['Terminal']) ? $selectedData[1]['Terminal'] : 'gaono data';
$terminal3 = isset($selectedData[2]['Terminal']) ? $selectedData[2]['Terminal'] : 'gaono data';

$app1 = $terminal1;
$app2 = $terminal2;
$app3 = $terminal3;

var_dump($terminal1, $terminal2, $terminal3);



var_dump($app1,$app2,$app3);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- <script src="../public/js/term.js"></script> -->
    <link rel="stylesheet" href="../public/css/app_term.css">
    <title>Search Data by Keyword</title>
    <!-- <link rel="stylesheet" href="../public/css/system.css"> -->
</head>

<body>
    <div class="container mt-5">
        <div class="col-md-4">
            <form id="form-applicator-term-0" method="GET">
                <input type="hidden" name="mesin" id="hidden-mesin-applicator">
                <input type="hidden" name="noproc1">
                <input type="hidden" name="noproc2">
                <input type="hidden" name="noproc3">

                <label for="applicator">Applicator:</label>
                <input type="text" name="applicator" id="applicator" class="form-control" placeholder="Masukkan Applicator" required>

                <label for="term">Terminal:</label>
                <input type="text" name="term" id="term" class="form-control" placeholder="Masukkan Terminal" required>

                <button type="submit" class="btn btn-primary mt-2">Submit</button>
            </form>
        </div>
    </div>
    <div class="container mt-5">
        <div class="col-md-4">
            <form id="form-applicator-term-1" method="GET">
                <input type="hidden" name="mesin" id="hidden-mesin-applicator">
                <input type="hidden" name="noproc1">
                <input type="hidden" name="noproc2">
                <input type="hidden" name="noproc3">

                <label for="applicator">Applicator:</label>
                <input type="text" name="applicator" id="applicator" class="form-control" placeholder="Masukkan Applicator" required>

                <label for="term">Terminal:</label>
                <input type="text" name="term" id="term" class="form-control" placeholder="Masukkan Terminal" required>

                <button type="submit" class="btn btn-primary mt-2">Submit</button>
            </form>
        </div>
    </div>
    <div class="container mt-5">
        <div class="col-md-4">
            <form id="form-applicator-term-2" method="GET">
                <input type="hidden" name="mesin" id="hidden-mesin-applicator">
                <input type="hidden" name="noproc1">
                <input type="hidden" name="noproc2">
                <input type="hidden" name="noproc3">

                <label for="applicator">Applicator:</label>
                <input type="text" name="applicator" id="applicator" class="form-control" placeholder="Masukkan Applicator" required>

                <label for="term">Terminal:</label>
                <input type="text" name="term" id="term" class="form-control" placeholder="Masukkan Terminal" required>

                <button type="submit" class="btn btn-primary mt-2">Submit</button>
            </form>
        </div>
    </div>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
    document.addEventListener("keydown", function (event) {
        if (event.key === "Enter") {
            event.preventDefault(); // Mencegah form submit otomatis

            let inputs = document.querySelectorAll('input[type="text"]'); // Ambil semua input teks
            let index = Array.from(inputs).indexOf(document.activeElement); // Temukan input yang aktif

            if (index !== -1 && index < inputs.length - 1) {
                // Jika bukan input terakhir, pindah ke input berikutnya
                inputs[index + 1].focus();
            } else if (index === inputs.length - 1) {
                // Jika input terakhir, submit form
                document.getElementById("form-applicator-term").requestSubmit();
            }
        }
    });

    $(document).ready(function () {
        $("#form-applicator-term").on("submit", function (e) {
            e.preventDefault(); // Cegah halaman refresh setelah submit

            const applicator = $("#applicator").val();
            const term = $("#term").val();

            var app1 = "<?= addslashes(str_replace('-', '', $app1)) ?>";  // Hapus strip dari database
            var terminal1 = "<?= addslashes(str_replace('-', '', $terminal1)) ?>";
            
            if (applicator.length < 36 || term.length < 36) {
                alert("Input tidak valid! Pastikan panjang karakter cukup.");
                return;
            }

            // Ambil karakter ke-27 hingga ke-36 (indeks 26 sampai 35)
            var appExtract = applicator.substring(26, 36);
            var termExtract = term.substring(26, 36);

            function addHyphens(str) {
                if (str.length !== 10) {
                    return str; // Jika panjang tidak sesuai, biarkan string tetap
                }
                return str.replace(/(\d{4})(\d{4})(\d{2})/, '$1-$2-$3');
            }

            var appExtractFormatted = addHyphens(appExtract);
            var termExtractFormatted = addHyphens(termExtract);

            if (appExtract !== app1) {
                alert("Applicator harus sesuai dengan: " + app1);
                return;
            }

            if (termExtract !== terminal1) {
                alert("Terminal harus sesuai dengan: " + terminal1);
                return;
            }

            $.ajax({
                url: "../process/get_term.php",
                method: "GET",
                data: { 
                    applicator: appExtractFormatted,  // Kirim yang sudah diformat
                    term: termExtractFormatted        // Kirim yang sudah diformat
                },
                dataType: "json",
                success: function (response) {
                    $("#error-message").hide();

                    const allTables = ["data_kanban", "data_cfm", "data_crimping", "data_stroke"];
                    const allTablesHaveData = allTables.every(
                        (table) => Array.isArray(response[table]) && response[table].length > 0
                    );

                    if (!allTablesHaveData) {
                        alert("Data Tidak Valid! Periksa kembali Applicator atau Term yang dimasukkan.");
                        return;
                    }

                    let tablesHtml = "";
                    for (const [tableName, rows] of Object.entries(response)) {
                        tablesHtml += `<h3 style="margin-top: 20px;">${tableName}</h3>`;
                        tablesHtml += `<table border="1" style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                                        <thead><tr>`;

                        if (Array.isArray(rows) && rows.length > 0) {
                            const columns = Object.keys(rows[0]);
                            columns.forEach((column) => {
                                tablesHtml += `<th style="padding: 10px; border: 1px solid #ddd;">${column}</th>`;
                            });

                            tablesHtml += "<tbody>";
                            rows.forEach((row) => {
                                tablesHtml += "<tr>";
                                columns.forEach((column) => {
                                    tablesHtml += `<td>${row[column] || "-"}</td>`;
                                });
                                tablesHtml += "</tr>";
                            });
                            tablesHtml += "</tbody>";
                        } else {
                            tablesHtml += `<tbody>
                                            <tr>
                                                <td colspan="100%" style="padding: 10px; border: 1px solid #ddd; text-align: center;">
                                                Data tidak ditemukan
                                                </td>
                                            </tr>
                                            </tbody>`;
                        }
                        tablesHtml += "</table>";
                    }

                    $("#search-results").html(tablesHtml);
                    saveSearchResults(response, "applicator-term");

                    window.location.href = "pengukuran.php";
                },
                error: function (xhr, status, error) {
                    $("#error-message").text(`Error: ${xhr.status} - ${error}`).show();
                },
            });
        });
    });




  function saveSearchResults(data, type) {
    if (!data || Object.keys(data).length === 0 || !type) {
      console.error("Data atau type tidak valid.");
      return;
    }

    try {
      sessionStorage.setItem(
        `savedSearchResults_${type}`,
        JSON.stringify(data)
      );
      console.log(`Data ${type} disimpan ke sessionStorage.`, data);

      $.ajax({
        url: "../process/save_search_results.php",
        method: "POST",
        data: { results: JSON.stringify(data), type },
        success: function (response) {
          console.log(`Data ${type} berhasil disimpan ke server:`, response);
        },
        error: function (xhr, status, error) {
          console.error(`Gagal menyimpan data ${type} ke server:`, error);
        },
      });
    } catch (error) {
      console.error("Gagal menyimpan ke sessionStorage:", error);
    }
  }
});

    </script>
</body>