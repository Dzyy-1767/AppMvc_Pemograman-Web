# Sistem Informasi Nilai Mahasiswa

Kerangka aplikasi PHP MVC **tanpa framework** untuk mata kuliah Pemrograman Web.

---

## Prasyarat

| Kebutuhan | Versi minimum |
|-----------|--------------|
| PHP | 8.1 |
| MySQL / MariaDB | 5.7 / 10.4 |
| Composer | 2.x |

---

## Instalasi

### 1. Install dependensi Composer

```bash
composer install
```

Perintah ini membuat folder `vendor/` dan mengaktifkan autoload PSR-4.

### 2. Siapkan database

Buka MySQL (phpMyAdmin, MySQL Workbench, atau CLI) lalu jalankan:

```sql
SOURCE database/schema.sql;
```

Atau via terminal:

```bash
mysql -u root -p < database/schema.sql
```

> **Default kredensial** (bisa diubah di `src/config/koneksi.php`):
> - Host: `localhost`
> - Database: `pemweb`
> - Username: `root`
> - Password: *(kosong)*

### 3. Jalankan server

#### Opsi A – PHP built-in server (paling mudah)

```bash
php -S localhost:8000 -t public public/index.php
```

Buka browser → <http://localhost:8000/mahasiswa>

> **Catatan:** `.htaccess` tidak dibaca oleh PHP built-in server.
> Flag `-t public public/index.php` menggantikan fungsi `.htaccess`:
> semua request diarahkan ke `public/index.php`.

#### Opsi B – XAMPP

1. Salin folder `app-mvc/` ke `C:\xampp\htdocs\`.
2. Jadikan `app-mvc/public/` sebagai document root via **Virtual Host**:

   Edit `C:\xampp\apache\conf\extra\httpd-vhosts.conf`:

   ```apache
   <VirtualHost *:80>
       ServerName sinilai.test
       DocumentRoot "C:/xampp/htdocs/app-mvc/public"
       <Directory "C:/xampp/htdocs/app-mvc/public">
           AllowOverride All
           Require all granted
       </Directory>
   </VirtualHost>
   ```

3. Tambahkan `127.0.0.1 sinilai.test` ke `C:\Windows\System32\drivers\etc\hosts`.
4. Restart Apache → buka <http://sinilai.test/mahasiswa>

---

## Struktur Folder

```
app-mvc/
├─ composer.json
├─ README.md
├─ database/
│  └─ schema.sql
├─ public/
│  ├─ index.php        ← front controller (satu-satunya pintu masuk)
│  └─ .htaccess
├─ src/
│  ├─ Core/
│  │  └─ Router.php
│  ├─ Controllers/
│  │  ├─ MahasiswaController.php
│  │  └─ NilaiController.php
│  ├─ Model/
│  │  ├─ Mahasiswa.php
│  │  └─ Nilai.php
│  ├─ Repository/
│  │  ├─ MahasiswaRepository.php
│  │  ├─ NilaiRepository.php
│  │  ├─ PdoMahasiswaRepository.php
│  │  └─ PdoNilaiRepository.php
│  ├─ Service/
│  │  └─ GradeCalculator.php
│  └─ config/
│     └─ koneksi.php
└─ views/
   ├─ layout/
   │  ├─ header.php
   │  └─ footer.php
   └─ mahasiswa/
      └─ index.php
```

---

## Alur Satu Request

```
Browser (GET /mahasiswa)
   │
   ▼
public/index.php          ← front controller: require autoload, buat Router
   │  parse_url → '/mahasiswa'
   ▼
Core/Router::dispatch()   ← cocokkan GET + '/mahasiswa'
   │
   ▼
MahasiswaController::index()
   │  buat PdoMahasiswaRepository
   ├─ Repository::all()   ← SELECT via prepared statement
   │  kembalikan []Mahasiswa
   │  ubah ke array asosiatif
   ▼
views/mahasiswa/index.php ← require header, foreach, require footer
   │
   ▼
Browser (HTML halaman daftar mahasiswa)
```

---

## Rute yang Tersedia

| Method | URI | Keterangan |
|--------|-----|------------|
| GET | `/mahasiswa` | Daftar semua mahasiswa |
| GET | `/mahasiswa/tambah` | Form tambah mahasiswa |
| POST | `/mahasiswa/simpan` | Validasi dan simpan mahasiswa baru |
| GET | `/mahasiswa/edit?id={id}` | Form edit mahasiswa |
| POST | `/mahasiswa/update` | Validasi dan perbarui mahasiswa |
| POST | `/mahasiswa/hapus` | Hapus mahasiswa tanpa nilai terkait |
| GET | `/nilai` | Daftar nilai dan grade mahasiswa |
| GET | `/nilai/tambah` | Form tambah nilai |
| GET | `/nilai/edit?id={id}` | Form edit nilai |
| POST | `/nilai/simpan` | Validasi dan simpan nilai |
| POST | `/nilai/update` | Validasi dan perbarui nilai |
| POST | `/nilai/hapus` | Hapus nilai |

Grade dihitung dari nilai akhir (0–100): A ≥ 85, B ≥ 75, C ≥ 65, D ≥ 50, dan E untuk nilai di bawah 50.

---

## Pengujian

### Uji GET /mahasiswa (harus tampil 3 data)

1. Jalankan server: `php -S localhost:8000 -t public public/index.php`
2. Buka <http://localhost:8000/mahasiswa>
3. ✅ Halaman menampilkan tabel dengan 3 baris: Andi Pratama, Siti Rahayu, Budi Santoso
4. ✅ Footer menampilkan "Total: **3** mahasiswa terdaftar."

### Uji rute tidak ada (harus tampil 404)

1. Buka <http://localhost:8000/sembarang>
2. ✅ Browser menampilkan: **404 – Halaman Tidak Ditemukan**
3. ✅ HTTP status code yang diterima adalah `404`

Verifikasi HTTP status code via terminal:

```bash
curl -o /dev/null -s -w "%{http_code}" http://localhost:8000/sembarang
# output: 404
```

### Uji tambah mahasiswa

1. Buka <http://localhost:8000/mahasiswa>, lalu pilih **Tambah Mahasiswa**.
2. Isi NIM angka yang belum digunakan (maksimal 20 karakter), nama, dan program studi; simpan. Browser harus kembali ke `/mahasiswa` dan data baru muncul satu kali.
3. Kirim form kosong; respons harus `422` dan tampil pesan wajib isi untuk setiap field.
4. Coba NIM yang sudah terdaftar; form harus menampilkan pesan bahwa NIM sudah digunakan.
5. Coba NIM yang mengandung huruf atau lebih dari 20 karakter; form harus menampilkan pesan validasi NIM.
6. Refresh halaman daftar setelah penyimpanan; data tidak bertambah lagi (pola Post/Redirect/Get).

### Uji edit dan hapus mahasiswa

1. Pilih **Edit** pada daftar, ubah data dengan NIM angka yang belum digunakan, lalu simpan; daftar menampilkan pesan sukses.
2. Edit data dengan NIM milik mahasiswa lain; form harus menolak NIM duplikat.
3. Edit dengan field kosong; respons harus `422` dan pesan kesalahan tampil dengan nilai lama tetap terisi.
4. Simpan edit tanpa mengubah NIM; perubahan harus berhasil.
5. Buka <http://localhost:8000/mahasiswa/edit?id=99999>; respons harus `404` dengan pesan **Data tidak ditemukan**.
6. Hapus mahasiswa tanpa data nilai; data harus hilang dari daftar.
7. Hapus mahasiswa yang memiliki data nilai; penghapusan harus ditolak dan pesan bahwa data nilainya harus dihapus lebih dahulu tampil.
8. Refresh daftar setelah update atau hapus; aksi tidak boleh dijalankan ulang.

---

## Konvensi Kode

- Namespace root: `UMM\Pemweb\`
- Semua file PHP menggunakan `declare(strict_types=1)`
- Query SQL: **selalu prepared statement** (tidak ada string SQL langsung dengan input user)
- View: hanya HTML + `htmlspecialchars()`, tanpa SQL dan logika bisnis
- Controller: tipis, hanya orkestrasi antara Repository dan View
