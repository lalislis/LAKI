
# PROYEK LAKI : Layanan Absen Kantor Indonesia

## About LAKI

LAKI (Layanan Absensi Kantor Indonesia) merupakan sebuah aplikasi layanan presensi karyawan untuk kantor-kantor yang menggunakan layanan aplikasi ini. Dalam aplikasi ini terdapat 3 jenis pengguna, yaitu user (karyawan kantor), Super User (HR kantor), dan Admin. Jika sebuah perusahaan ingin menggunakan layanan aplikasi LAKI, maka HR harus mengontak admin untuk registrasi akun Super User. Setelah mendapatkan akun, maka HR bisa membuat akun setiap karyawannya yang akan digunakan untuk mengisi presensi nantinya. Setelah mendapatkan akun dari HR, karyawan bisa mengganti password dan menggunakan akunnya untuk melakukan presensi.

## Anggota Kelompok

- M. Luthfi Taufiqurrahman  - 140810190036 (Project Manager)
- Gregorius Evangelist W.   - 140810190040 (Designer, Tester)
- Ihsanuddin Dwi P.         - 140810190048 (Engineer)
- Elshandi Septiawan        - 140810190050 (Engineer)

### Teknis Clone Repo (PASTIKAN PHP versi 8+ untuk laravel 9)

1. Clone repository
2. Masuk directory project
3. run `cp .env.example .env`
4. run `php artisan key:generate`
5. Sesuaikan settingan database (nama database, password dll.)
6. run `composer install`

### Making Resources (CRUD)

- run : `php artisan make:model Models/NamaModel -mrc`
  (it will make 3 file: migration, model, controller)
- adjust field requirements in migration file
- run -> `php artisan migrate`
- Add routes in file web.php : Route::resource(Url,NamaController);