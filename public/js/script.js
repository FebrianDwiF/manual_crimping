document.addEventListener("keydown", function (event) {
  if (event.key === "Enter") {
    event.preventDefault(); // Mencegah form submit otomatis

    let inputs = document.querySelectorAll('input[type="text"]'); // Ambil semua input teks
    let index = Array.from(inputs).indexOf(document.activeElement); // Temukan input yang aktif

    if (index !== -1 && index < inputs.length - 1) {
      inputs[index + 1].focus(); // Pindah ke input berikutnya
    } else {
      inputs[0].focus(); // Jika di input terakhir, kembali ke input pertama
    }
  }
});
document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("form-mesin");
  const submitButton = document.getElementById("submit-mesin");
  const mesinInput = document.getElementById("mesin");
  const carlineInput = document.getElementById("carline");
  const formNoproc = document.getElementById("form-noproc");
  const hiddenMesin = document.getElementById("hidden-mesin");

  // ✅ Cek apakah mesin sudah dipilih sebelumnya
  let savedMesin = sessionStorage.getItem("mesin");
  let savedCarline = sessionStorage.getItem("carline");

  if (savedMesin && savedCarline) {
    console.log("✅ Mesin sudah dipilih sebelumnya:", savedMesin);

    // ✅ Set nilai input tersembunyi
    hiddenMesin.value = savedMesin;
    
    // ✅ Isi input dan nonaktifkan agar tidak bisa diedit ulang
    mesinInput.value = savedMesin;
    carlineInput.value = savedCarline;
    mesinInput.disabled = true;
    carlineInput.disabled = true;
    
    // ✅ Sembunyikan form-mesin dan langsung tampilkan form-noproc
    form.style.display = "none";
    formNoproc.style.display = "block";

    return; // Stop eksekusi lebih lanjut
  } 

  // ✅ Jika mesin belum dipilih, jalankan validasi input
  mesinInput.addEventListener("input", validateForm);
  carlineInput.addEventListener("input", validateForm);

  async function validateInput(input, type) {
    try {
      let response = await $.ajax({
        url: "../validate/val_system.php",
        method: "POST",
        data: {
          [type]: input.value.trim(),
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

  form.addEventListener("submit", async function (event) {
    event.preventDefault(); // ✅ Cegah halaman refresh

    await validateForm();
    if (!submitButton.disabled) {
      let mesinValue = mesinInput.value;
      let carlineValue = carlineInput.value;

      // ✅ Simpan ke sessionStorage
      sessionStorage.setItem("mesin", mesinValue);
      sessionStorage.setItem("carline", carlineValue);
      console.log("Mesin:", sessionStorage.getItem("mesin"));
console.log("Carline:", sessionStorage.getItem("carline"));


      let formData = {
        shift: document.getElementById("shift").value,
        mesin: mesinValue,
        carline: carlineValue,
      };

      $.ajax({
        url: "../validate/save_session.php",
        method: "POST",
        data: formData,
        success: function (response) {
          console.log("Data berhasil disimpan:", response);
        },
        error: function () {
          alert("Gagal menyimpan data.");
        },
      });

      // ✅ Simpan ke input hidden
      hiddenMesin.value = mesinValue;

      // ✅ Sembunyikan form-mesin dan tampilkan form-noproc
      form.style.display = "none";
      formNoproc.style.display = "block";

      // ✅ Tutup modal setelah sukses
      let modalElement = document.getElementById("modalMesin");
      let modalInstance = bootstrap.Modal.getInstance(modalElement);
      modalInstance.hide();
    }
  });
});

$(document).ready(function () {
  // Tangani perubahan jumlah input
  $("#jumlahInput").change(function () {
      let jumlah = parseInt($(this).val());
      $(".process-input").hide(); // Sembunyikan semua input
      for (let i = 1; i <= jumlah; i++) {
          $("#input-" + i).show(); // Tampilkan sesuai pilihan
      }
  });
  $("#jumlahInput").change(function () {
    let jumlah = $(this).val();
    let urlParams = new URLSearchParams(window.location.search);
    urlParams.set('jumlah', jumlah);
    window.location.search = urlParams.toString();
});

  // Tangani submit form tanpa reload
  $("#form-noproc").submit(function (event) {
      event.preventDefault(); // Mencegah reload halaman

      let jumlah = parseInt($("#jumlahInput").val());
      let originalValues = {};
      let processedValues = {};

      for (let i = 1; i <= jumlah; i++) {
          let value = $("#noproc" + i).val();
          originalValues["noproc" + i] = value;
          processedValues["noproc" + i] = value.substring(1, 5); // Potong karakter
      }

      $.ajax({
          url: "process2.php",
          type: "POST",
          data: {
              original: originalValues,
              processed: processedValues
          },
          success: function (response) {
              $("#result").html(response); // Tampilkan hasil
          },
          error: function () {
              $("#result").html("<p style='color:red;'>Terjadi kesalahan saat mencari data.</p>");
          },
      });
  });

  // Tangani pemilihan Side A/B tanpa reload
  $(document).on("submit", "#side-selection-form", function (event) {
      event.preventDefault();

      $.ajax({
          url: "save_selection.php",
          type: "POST",
          data: $(this).serialize(),
          success: function (response) {
              window.location.href = "app_term.php";
          },
          error: function () {
              $("#result").html("<p style='color:red;'>Gagal menyimpan pilihan.</p>");
          },
      });
  });
});

// $(document).ready(function () {
//   //   $("#side-selection-form").submit(function (e) {
//   //     e.preventDefault(); // Mencegah form melakukan submit normal

//   //     $.ajax({
//   //       type: "POST",
//   //       url: "save_selection.php?t=" + new Date().getTime(), // Hindari cache
//   //       data: $(this).serialize(),
//   //       beforeSend: function () {
//   //         $("#result-container").html(
//   //           "<p class='text-warning'>Processing...</p>"
//   //         );
//   //       },

//   //       success: function (response) {
//   //         console.log(response); // Debugging: cek response dari server

//   //         if (response.trim() === "success") {
//   //           location.reload(); // Refresh untuk memastikan session diperbarui
//   //         } else {
//   //           $("#result-container").html(response); // Tampilkan respons jika ada error
//   //         }
//   //       },
//   //       error: function () {
//   //         $("#result-container").html(
//   //           "<p class='text-danger'>Error processing request.</p>"
//   //         );
//   //       },
//   //     });
//   //   });

//   // Definisikan variabel di luar fungsi

//   $("#form-applicator-term").on("submit", function (e) {
//     e.preventDefault(); // Mencegah refresh halaman

//     const applicator = $("#applicator").val();
//     const term = $("#term").val();

//     if (!applicator && !term) {
//       $("#error-message").text("Applicator dan Terminal harus diisi!").show();
//       return;
//     }

//     $.ajax({
//       url: "../process/get_term.php",
//       method: "GET",
//       data: {
//         applicator: applicator,
//         term: term,
//       },
//       success: function (response) {
//         $("#error-message").hide(); // Sembunyikan pesan error jika sukses
//         let tablesHtml = "";

//         for (const [tableName, rows] of Object.entries(response)) {
//           tablesHtml += `<h3 style="margin-top: 20px;">${tableName}</h3>`;
//           tablesHtml += `
//                             <table border="1" style="
//                                 width: 100%; 
//                                 border-collapse: collapse; 
//                                 margin-bottom: 20px;
//                                 font-family: Arial, sans-serif;
//                                 font-size: 14px;">
//                             <thead><tr>
                             
//                             `;

//           if (Array.isArray(rows) && rows.length > 0) {
//             const columns = Object.keys(rows[0]);
//             columns.forEach((column) => {
//               tablesHtml += `
//                                     <th style="
//                                         padding: 10px; 
//                                         box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08); 
//                                         text-align: left;   
//                                         border: 1px solid #ddd;">
//                                         ${column}
//                                     </th>`;
//             });

//             tablesHtml += "<tbody>";
//             rows.forEach((row, index) => {
//               tablesHtml += `<tr>`;
//               if (tableName === "data_stroke") {
//               }
//               columns.forEach((column) => {
//                 tablesHtml += `
//                                         <td class="${
//                                           column === "current_stroke"
//                                             ? "current-stroke"
//                                             : ""
//                                         }" 
//                                             data-column="${column}">
//                                             ${row[column] || "-"}
//                                         </td>`;
//               });
//               tablesHtml += `</tr>`;
//             });
//             tablesHtml += "</tbody>";
//           } else {
//             tablesHtml += `
//                                 <tbody>
//                                     <tr>
//                                         <td colspan="100%" style="
//                                             padding: 10px; 
//                                             border: 1px solid #ddd; 
//                                             text-align: center; 
//                                             font-style: italic;">
//                                             Data tidak ditemukan
//                                         </td>
//                                     </tr>
//                                 </tbody>`;
//           }
//           tablesHtml += "</table>";
//         }

//         $("#search-results").html(tablesHtml);

//         saveSearchResults(response, "applicator-term");
//         console.log(response);
//       },
//       error: function (xhr, status, error) {
//         $("#error-message").text(`Error: ${xhr.status} - ${error}`).show();
//       },
//     });
//   });

  function saveSearchResults(data, type) {
    if (!data || data.length === 0 || !type) {
      console.error("Data atau type tidak valid.");
      return;
    }

    // Simpan data ke sessionStorage sebelum mengirim ke server
    sessionStorage.setItem(`savedSearchResults_${type}`, JSON.stringify(data));
    console.log(`Data ${type} disimpan ke sessionStorage.`, data);

    // Logika khusus berdasarkan type
    if (type === "noproc") {
      console.log("Proses khusus untuk 'noproc' dijalankan.");
    } else if (type === "applicator-term") {
      console.log("Proses khusus untuk 'applicator-term' dijalankan.");
    }

    // Kirim data ke server
    $.ajax({
      url: "../process/save_search_results.php",
      method: "POST",
      data: {
        results: JSON.stringify(data),
        type: type,
      },
      success: function (response) {
        console.log(`Data ${type} berhasil disimpan ke server:`, response);
      },
      error: function (xhr, status, error) {
        console.error(`Gagal menyimpan data ${type} ke server:`, error);
      },
    });
  }
