<?php
include '../db/connection.php';
include '../process/auth.php';


// var_dump($nik);
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
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Montserrat:wght@400;600&display=swap"
        rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../public/js/script.js"></script>
    <link rel="stylesheet" href="../public/css/system.css">
    <title>Search Data by Keyword</title>
    <!-- <link rel="stylesheet" href="../public/css/system.css"> -->
</head>


<body>
    <div class="sidebar">
        <h4><?= "Production"; ?></h4>

        <hr>
        <br>
        <?php date_default_timezone_set("Asia/Jakarta");

                $formatter = new IntlDateFormatter(
                    'id_ID', 
                    IntlDateFormatter::FULL, 
                    IntlDateFormatter::SHORT,
                    'Asia/Jakarta',
                    IntlDateFormatter::GREGORIAN
                );

                $loginTime = new DateTime($user['loginTime']);
                $formattedDate = $formatter->format($loginTime);
                ?>

        <h4><?= "Login Time: " . $formattedDate; ?></h4>

        <br>
        <a href="./operator.php?nik=<?= $nik ?>" class="operator">Operator Page</a>

        <form action="../process/logout.php" method="POST" class="sidebar-form">
            <input type="hidden" name="nik" value="<?php echo htmlspecialchars($nik); ?>">
            <button type="submit" class="logout">🚪 Logout</button>
        </form>
    </div>
    <h1>Search Data</h1>

    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalMesin">
        Pilih Mesin
    </button>
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

                        <!-- mesin -->
                        <div class="mb-3">
                            <label for="mesin" class="form-label">Mesin:</label>
                            <input type="text" name="mesin" id="mesin" class="form-control" placeholder="Enter Mesin">
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
    <div class="container mt-5">
    <div class="row">
        <div class="col-md-4">
            <label for="jumlahForm">Jumlah Form (maks 10):</label>
            <input type="number" id="jumlahForm" class="form-control" min="1" max="10" value="1">
            
            <form id="form-noproc" method="GET">
                <input type="hidden" name="nik" value="<?= htmlspecialchars($nik) ?>">
                <input type="hidden" name="mesin" id="hidden-mesin">
                
                <div id="form-container"></div>
                
                <button type="submit" class="btn btn-primary mt-3">Kirim</button>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('jumlahForm').addEventListener('input', function() {
        let jumlah = parseInt(this.value);
        let container = document.getElementById('form-container');
        container.innerHTML = '';
        
        for (let i = 1; i <= jumlah; i++) {
            let label = document.createElement('label');
            label.textContent = 'Nomor Proses ' + i + ':';
            label.setAttribute('for', 'noproc' + i);
            
            let input = document.createElement('input');
            input.type = 'text';
            input.name = 'noproc' + i;
            input.id = 'noproc' + i;
            input.className = 'form-control';
            input.required = true;
            
            container.appendChild(label);
            container.appendChild(input);
        }
    });
    
    document.getElementById('jumlahForm').dispatchEvent(new Event('input'));
</script>
</body>

</html>
