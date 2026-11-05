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
│  │  └─ MahasiswaController.php
│  ├─ Model/
│  │  └─ Mahasiswa.php
│  ├─ Repository/
│  │  ├─ MahasiswaRepository.php        ← interface
│  │  └─ PdoMahasiswaRepository.php     ← implementasi PDO
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

---

## Konvensi Kode

- Namespace root: `UMM\Pemweb\`
- Semua file PHP menggunakan `declare(strict_types=1)`
- Query SQL: **selalu prepared statement** (tidak ada string SQL langsung dengan input user)
- View: hanya HTML + `htmlspecialchars()`, tanpa SQL dan logika bisnis
- Controller: tipis, hanya orkestrasi antara Repository dan View

