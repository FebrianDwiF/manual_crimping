<?php
session_start();
include '../db/connection.php';

$man = $_SESSION['mesin']; // Mesin yang digunakan untuk filter

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['original_noproc'] = $_POST['original'];

    // Ambil hanya nomor proses yang valid (tidak kosong)
    $noprocList = array_values($_POST['processed']);
    $noprocList = array_filter($noprocList); // Hapus nilai kosong
}

if (empty($noprocList)) {
    echo "<p style='color:red;'>No process number provided.</p>";
    exit();
}

// Query database sesuai jumlah input yang diterima
$placeholders = implode(',', array_fill(0, count($noprocList), '?'));
$query = "SELECT machine, noproc, ctrl_no, kind, size, col, c_l,
                 term_b, strip_b, half_strip_b, man_b, acc_b1,
                 term_a, strip_a, half_strip_a, man_a, acc_a1, qty
          FROM data_kanban
          WHERE noproc IN ($placeholders)
          ORDER BY FIELD(noproc, " . implode(',', array_map(fn($v) => "'$v'", $noprocList)) . ")";
$stmt = $conn->prepare($query);
$types = str_repeat('s', count($noprocList));
$stmt->bind_param($types, ...$noprocList);
$stmt->execute();
$result = $stmt->get_result();

// Simpan hasil ke session dengan filter man_a/man_b sesuai $man
$_SESSION['data_kanban'] = [];
while ($row = $result->fetch_assoc()) {
    // Filter berdasarkan $man
    if ($row['man_a'] === $man || $row['man_b'] === $man) {
        $_SESSION['data_kanban'][] = $row;
    }
}

if (empty($_SESSION['data_kanban'])) {
    echo "<p style='color:red;'>Tidak ada data yang ditemukan dalam database dengan mesin yang sesuai.</p>";
    exit();
}

// Tampilkan tabel hanya jika ada data
$pilihanDibutuhkan = false;
$setidaknyaSatuDipilih = false;
$output = "<form id='side-selection-form' method='POST' action='save_selection.php'>
            <table border='1'>
                <tr>
                    <th>No Process</th>
                    <th>Side A</th>
                    <th>Side B</th>
                </tr>";

foreach ($_SESSION['data_kanban'] as $row) {
    $adaManA = (!empty($row['man_a']) && $row['man_a'] === $man);
    $adaManB = (!empty($row['man_b']) && $row['man_b'] === $man);

    $output .= "<tr><td>{$row['noproc']}</td>";

    if ($adaManA || $adaManB) {
        if ($adaManA && $adaManB) {
            // Jika dua sisi tersedia, user harus memilih
            $output .= "<td><input type='radio' name='side[{$row['noproc']}]' value='A' required> Pilih A</td>
                        <td><input type='radio' name='side[{$row['noproc']}]' value='B' required> Pilih B</td>";
            $pilihanDibutuhkan = true;
        } else {
            // Pilih otomatis jika hanya satu opsi tersedia
            if ($adaManA) {
                $output .= "<td>✅ <input type='hidden' name='side[{$row['noproc']}]' value='A'></td><td>-</td>";
            } elseif ($adaManB) {
                $output .= "<td>-</td><td>✅ <input type='hidden' name='side[{$row['noproc']}]' value='B'></td>";
            }
            $setidaknyaSatuDipilih = true;
        }
    } else {
        $output .= "<td>-</td><td>-</td>";
    }

    $output .= "</tr>";
}

$output .= "</table>";

// Tombol submit hanya muncul jika ada pilihan yang perlu disimpan
if ($pilihanDibutuhkan || $setidaknyaSatuDipilih) {
    $output .= "<button type='submit'>Simpan Pilihan</button>";
}

$output .= "</form>";

// Jika semua opsi otomatis dipilih, langsung simpan ke session
if (!$pilihanDibutuhkan && $setidaknyaSatuDipilih) {
    $_SESSION['filtered_data'] = [];

    foreach ($_SESSION['data_kanban'] as $row) {
        $side = "-";

        if (!empty($row['man_a']) && $row['man_a'] === $man && empty($row['man_b'])) {
            $side = "A";
        } elseif (!empty($row['man_b']) && $row['man_b'] === $man && empty($row['man_a'])) {
            $side = "B";
        }

        $_SESSION['filtered_data'][] = [
            'machine' => $row['machine'],
            'noproc' => $row['noproc'],
            'ctrl_no' => $row['ctrl_no'],
            'kind' => $row['kind'],
            'size' => $row['size'],
            'col' => $row['col'],
            'c_l' => $row['c_l'],
            'terminal' => $side == "A" ? $row['term_a'] : ($side == "B" ? $row['term_b'] : "-"),
            'strip' => $side == "A" ? $row['strip_a'] : ($side == "B" ? $row['strip_b'] : "-"),
            'half_strip' => $side == "A" ? $row['half_strip_a'] : ($side == "B" ? $row['half_strip_b'] : "-"),
            'man' => $side == "A" ? $row['man_a'] : ($side == "B" ? $row['man_b'] : "-"),
            'acc' => $side == "A" ? $row['acc_a1'] : ($side == "B" ? $row['acc_b1'] : "-"),
            'qty' => $row['qty']
        ];
    }
}

echo $output;
?>