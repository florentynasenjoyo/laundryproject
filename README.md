Untuk menjalankan file proyek Laundry-In di github ini :
- Download dulu file zip nya di github lalu di unzip
- Pastikan telah menginstal PHP dan Composer di komputer kalian
- Lalu buka terminal
- Lakukan command 'cd' (change directory) untuk masuk ke direktori tempat file tersebut disimpan
- Instal dependensi proyek dengan menjalankan command 'composer install'
- Lalu kita harus menyalin file .env.example ke .env dengan melakukan command 'cp .env.example .env'
- Generate key aplikasi Laravel dengan command 'php artisan key:generate'
- Setelah itu kita masuk dulu ke file .env nya lalu ganti nama db_databasenya dengan database yang kalian buat di phpmyadmin
- Migrasi dan seeding database dengan menjalankan command 'php artisan migrate --seed'
- Terakhir, jalankan server pengembangan lokal dengan command 'php artisan serve'
- Disitu akan muncul URL server seperti 'http://127.0.0.1:8000'
- Buka URL tersebut di browser, dan proyek Laravel tersebut akan dijalankan
