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

    // Sembunyikan semua input terlebih dahulu
    $(".process-input").hide();

    // Tampilkan input sesuai jumlah yang dipilih
    for (let i = 1; i <= jumlah; i++) {
      $("#input-" + i).show();
    }

    // Perbarui parameter URL tanpa double event
    let urlParams = new URLSearchParams(window.location.search);
    urlParams.set("jumlah", jumlah);
    window.history.replaceState(null, "", "?" + urlParams.toString());
  });
  $("#jumlahInput").change(function () {
    let jumlah = $(this).val();
    let urlParams = new URLSearchParams(window.location.search);
    urlParams.set("jumlah", jumlah);
    window.location.search = urlParams.toString();
  });

  // Tangani submit form tanpa reload dan dengan validasi
  $("#form-noproc").submit(function (event) {
    event.preventDefault(); // Mencegah reload halaman

    let jumlah = parseInt($("#jumlahInput").val());
    let originalValues = {};
    let processedValues = {};
    let isValid = true;
    let errorMessages = [];

    for (let i = 1; i <= jumlah; i++) {
      let inputField = $("#noproc" + i);
      let value = inputField.val().trim();

      if (!value) {
        isValid = false;
        errorMessages.push(`Nomor Proses ${i} harus diisi.`);
        inputField.addClass("is-invalid");
      } else {
        inputField.removeClass("is-invalid");
        originalValues["noproc" + i] = value;
        processedValues["noproc" + i] = value.substring(1, 5);
      }
    }

    if (!isValid) {
      $("#error-message").html(errorMessages.join("<br>")).show();
      return;
    } else {
      $("#error-message").hide();
    }

    // **Cek apakah semua input valid di database sebelum mengirimkan form**
    $.ajax({
      url: "../validate/validate_noproc.php", // File PHP untuk validasi database
      type: "POST",
      data: { processed: processedValues },
      dataType: "json",
      success: function (response) {
        if (!response.valid) {
          $("#error-message").html(response.error).show();
          return;
        }

        // Jika valid, kirim data ke process2.php
        $.ajax({
          url: "process2.php",
          type: "POST",
          data: {
            original: originalValues,
            processed: processedValues,
          },
          success: function (response) {
            $("#result").html(response);
          },
          error: function () {
            $("#result").html(
              "<p style='color:red;'>Terjadi kesalahan saat mencari data.</p>"
            );
          },
        });
      },
      error: function () {
        $("#error-message")
          .html("<p style='color:red;'>Gagal memvalidasi data.</p>")
          .show();
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
      success: function () {
        window.location.href = "app_term.php";
      },
      error: function () {
        $("#result").html("<p style='color:red;'>Gagal menyimpan pilihan.</p>");
      },
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
});
