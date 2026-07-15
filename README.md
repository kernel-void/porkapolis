# Porkapolis - Laravel

## Cara Instalasi

### 1. Clone project
```bash
git clone https://github.com/kernel-void/porkapolis.git
```

### 2. Masuk ke folder project
```bash
cd porkapolis
```
### 3. Install dependencies Laravel
```bash
composer install
```
### 4. Salin file .env dan sesuaikan konfigurasi database
```bash
cp .env.example .env
```
### 5. Generate app key
```bash
php artisan key:generate
```
### 6. Jalankan Database
```bash
php artisan migrate
```
### 7. Jalankan server lokal
```bash
php artisan serve
```
## 🛠️ Mengambil Update dari Repository

### 1. Ambil update terbaru dari GitHub
```bash
git pull origin main
```