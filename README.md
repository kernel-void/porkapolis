# Porkapolis - Laravel

## Cara Instalasi

```bash
# 1. Clone project
git clone https://github.com/kernel-void/porkapolis.git

# 2. Masuk ke folder project
cd porkapolis

# 3. Install dependencies Laravel
composer install

# 4. Salin file .env dan sesuaikan konfigurasi database
cp .env.example .env

# 5. Generate app key
php artisan key:generate

# 6. Jalankan Database
php artisan migrate

# 7. Jalankan server lokal
php artisan serve

## 🛠️ Mengambil Update dari Repository

# 1. Ambil update terbaru dari GitHub
git pull origin main
```