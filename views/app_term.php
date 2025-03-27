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
// var_dump($filteredApplicator);
$filteredTerm = array_slice($searchResults['applicator-term']['data_crimping'] ?? [], 0, 3);
$filteredStroke = array_slice($searchResults['applicator-term']['data_stroke'] ?? [],  0, 3);
$_SESSION['original_noproc'] = $_SESSION['original_noproc'] ?? '';
// var_dump($_SESSION['original_noproc']);  

// var_dump($kanban1, $kanban2, $kanban3);
$selectedData = $_SESSION['filtered_data'] ?? [];
// var_dump($selectedData);
$jumlahInput = $_SESSION['jumlahInput'] ?? 0;






// var_dump($jumlahInput);
$terminals = [];
$applicators = [];

for ($i = 0; $i < $jumlahInput; $i++) {
    $terminals[] = $selectedData[$i]['Terminal'] ?? 'tidak ada data';
    $applicators[] = $selectedData[$i]['Terminal'] ?? 'tidak ada data'; // Ubah jika 'applicator' ada di kolom lain
}
// var_dump($terminals);
// var_dump($terminal1, $terminal2, $terminal3);


// var_dump( $_SESSION['filtered_data']);
// var_dump($app1,$app2,$app3);
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
    <!-- Formulir 1 -->
    <div class="container mt-5">
        <?php for ($i = 0; $i < $jumlahInput; $i++) : ?>
        <div class="col-md-4" id="form-container-<?= $i ?>" style="display: <?= $i === 0 ? 'block' : 'none' ?>;">

            <form id="form-applicator-term-<?= $i ?>" method="GET">
                <input type="hidden" name="mesin" id="hidden-mesin-applicator-<?= $i ?>">
                <input type="hidden" name="noproc1">
                <input type="hidden" name="noproc2">
                <input type="hidden" name="noproc3">
                <div style="position: absolute; top: 10px; right: 10px;">
                    <label class="d-flex align-items-center mb-0">
                        Lakukan Pengukuran di Akhir
                        <input type="checkbox" id="cekPengukuranAkhir" class="ms-2">
                    </label>
                </div>


                <label for="applicator-<?= $i ?>">Applicator <?= $i + 1 ?>:
                    <?= htmlspecialchars($applicators[$i]) ?></label>
                <input type="text" name="applicator" id="applicator-<?= $i ?>" class="form-control"
                    placeholder="Masukkan Applicator" required>
                <label for="term-<?= $i ?>">Terminal <?= $i + 1 ?>: <?= htmlspecialchars($terminals[$i]) ?></label>
                <input type="text" name="term" id="term-<?= $i ?>" class="form-control" placeholder="Masukkan Terminal"
                    required>




                <button type="submit" class="btn btn-primary mt-2">Submit</button>
            </form>
        </div>
        <?php endfor; ?>
    </div>
    <script>
    document.getElementById("cekPengukuranAkhir").addEventListener("change", function() {
        sessionStorage.setItem("pengukuranAkhir", this.checked);
        console.log("Pengukuran di Akhir:", this.checked);
    });

    document.addEventListener("DOMContentLoaded", function() {
        let terminals = <?php echo json_encode($terminals); ?>;
        let applicators = <?php echo json_encode($applicators); ?>;
        let jumlahInput = <?php echo $jumlahInput; ?>;

        function addHyphens(str) {
            if (str.length !== 10) return str;
            return str.replace(/(\d{4})(\d{4})(\d{2})/, '$1-$2-$3');
        }

        const urlParams = new URLSearchParams(window.location.search);
        let formIndex = parseInt(urlParams.get('formIndex')) || 0;

        for (let i = 0; i < jumlahInput; i++) {
            document.getElementById(`form-container-${i}`).style.display = (i === formIndex) ? 'block' : 'none';
        }

        function handleFormSubmit(formIndex) {
            $(`#form-applicator-term-${formIndex}`).on("submit", function(e) {
                e.preventDefault();

                const applicator = $(`#applicator-${formIndex}`).val();
                const term = $(`#term-${formIndex}`).val();
                const appRef = applicators[formIndex];
                const termRef = terminals[formIndex].replace(/-/g, "");

                if (!term || term.length < 36) {
                    alert("Input tidak valid! Pastikan panjang karakter minimal 36.");
                    return;
                }

                const termExtract = term.substring(26, 36);
                const termExtractFormatted = addHyphens(termExtract);

                if (applicator !== appRef) {
                    alert(`Applicator tidak sesuai! Seharusnya: ${appRef}`);
                    return;
                }

                if (termExtract !== termRef) {
                    alert(`Terminal harus sesuai dengan: ${addHyphens(termRef)}`);
                    return;
                }

                $.ajax({
                    url: "../process/get_term.php",
                    method: "GET",
                    data: {
                        term: termExtractFormatted,
                        applicator: appRef
                    },
                    dataType: "json",
                    success: function(response) {
                        $("#error-message").hide();

                        const allTables = ["data_kanban", "data_cfm", "data_crimping",
                            "data_stroke"
                        ];
                        const allTablesHaveData = allTables.every(
                            (table) => Array.isArray(response[table]) && response[table]
                            .length > 0
                        );

                        if (!allTablesHaveData) {
                            alert(
                                "Data Tidak Valid! Periksa kembali Terminal yang dimasukkan."
                            );
                            return;
                        }

                        sessionStorage.setItem('currentFormData', JSON.stringify({
                            applicator: appRef,
                            term: termExtractFormatted,
                            formIndex: formIndex,
                            tables: response
                        }));

                        saveSearchResults(response, "applicator-term");
                        window.location.href =
                            `pengukuran.php?formIndex=${formIndex + 1}&applicator=${appRef}&term=${termExtractFormatted}`;

                    },
                    error: function(xhr, status, error) {
                        $("#error-message").text(`Error: ${xhr.status} - ${error}`).show();
                    }
                });
            });
        }

        for (let i = 0; i < jumlahInput; i++) {
            handleFormSubmit(i);
        }

        function saveSearchResults(data, type) {
            if (!data || Object.keys(data).length === 0 || !type) {
                console.error("Data atau type tidak valid.");
                return;
            }

            try {
                sessionStorage.setItem(`savedSearchResults_${type}`, JSON.stringify(data));
                console.log(`Data ${type} disimpan ke sessionStorage.`, data);

                $.ajax({
                    url: "../process/save_search_results.php",
                    method: "POST",
                    data: {
                        results: JSON.stringify(data),
                        type
                    },
                    success: function(response) {
                        console.log(`Data ${type} berhasil disimpan ke server:`, response);
                    },
                    error: function(xhr, status, error) {
                        console.error(`Gagal menyimpan data ${type} ke server:`, error);
                    }
                });
            } catch (error) {
                console.error("Gagal menyimpan ke sessionStorage:", error);
            }
        }
    });
    </script>

</body>