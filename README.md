# 📦 Panduan Setup Project dengan Laragon

## 📥 1. Install Laragon
1. Install Laragon yang sudah ada.  
2. Jalankan installer Laragon dan ikuti langkah-langkah instalasi.  
3. Setelah instalasi selesai, buka Laragon.

---

## 🔄 2. Mengubah Versi PHP/MySQL di Laragon (Jika diperlukan)
1. Unduh file versi Laragon atau PHP/MySQL yang disediakan (jika ada).  
   👉 [Download File Versi Laragon yang Disediakan](https://windows.php.net/downloads/releases/php-8.2.28-Win32-vs16-x64.zip)
2. Ekstrak file tersebut ke folder:  C:\laragon\bin\php (untuk PHP) C:\laragon\bin\mysql (untuk MySQL)
3. Restart Laragon.
4. Klik kanan pada icon Laragon > `PHP` > pilih versi PHP yang sudah ditambahkan.
5. Klik kanan pada icon Laragon > `MySQL` > pilih versi MySQL yang sudah ditambahkan (jika diperlukan).

---

## ⚠️ 3. Aktifkan Ekstensi `zip` di `php.ini`
1. Buka Laragon.
2. Klik menu `Menu > PHP > php.ini` atau buka manual di:
3. Cari baris berikut: ;extension=zip
4. Hapus tanda titik koma `;` sehingga menjadi: extension=zip
5. Simpan file dan restart Laragon.

---

## 🗄 4. Membuat Database di Laragon (MySQL)
1. Buka **Laragon**.
2. Klik **Menu** > **MySQL** > **Start All**.
3. Klik **Database** atau buka `http://localhost/phpmyadmin`.
4. Login dengan:  
- Username: `root`  
- Password: *(kosong)*  
5. Buat database baru sesuai dengan nama yang akan digunakan, contoh:  project

