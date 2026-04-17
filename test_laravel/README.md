### Enterprise Approval System

### Prasyarat Instalasi
- PHP >= 8.2
- Composer
- SQLite (yang lain)

### Langkah-langkah
1. Clone Repository
   bash
   git clone (tempelkan link repository url)
   cd test_laravel

2. Instal Dependensi
   composer install

3. Konfigurasi Environment
   cp .env.example .env

4. Generate App Key
   php artisan key:generate

5. Persiapan Database**
   New-Item -Path database/database.sqlite -ItemType File

6. Migration & Seeder
   php artisan migrate --seed

7. Jalankan Aplikasi
   php artisan serve
   Aplikasi akan berjalan di http://127.0.0.1:8000

#####################################################

### Migration Database
php artisan migrate:fresh --seed


#####################################################
### Endpoint API yang Tersedia
### 1. Autentikasi
POST /auth/register == Pendaftaran user baru == Publik 
POST /auth/login == Login untuk mendapatkan token == Publik 
POST /auth/logout == Revoke token saat ini == Token required 
GET /auth/me == Ambil detail profil user == Token required 

### 2. Submissions (Pengajuan)
GET /submissions ==List pengajuan (filter otomatis per role) == Token required 
POST /submissions == Membuat pengajuan baru ==  Divisi 
GET /submissions/{id} ==Detail pengajuan == Token required
PUT /submissions/{id} == Update pengajuan (sebelum di-approve) == Owner 
PUT /submissions/{id}/approve == Setujui atau Tolak pengajuan == Manager

### Akun Demo (Password DEmo: `password123`)
Manager ==  `manager@perusahaan.com` == Manager
Finance == `finance@perusahaan.com` == Keuangan
Divisi 1 ==  `divisi1@perusahaan.com` == Teknologi Informasi 
Divisi 2 == `divisi2@perusahaan.com` == Sumber Daya Manusia 
Divisi 3 == `divisi3@perusahaan.com` == Pemasaran 


### File .env.example
konfigurasi database di file .env == SQLite
env
DB_CONNECTION=sqlite
