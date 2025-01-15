# GDP Donasi - Plugin WordPress untuk Penggalangan Dana

Plugin WordPress untuk mengelola program donasi dan zakat dengan integrasi payment gateway.

## Fitur

- Program donasi dengan target dan batas waktu
- Form donasi dengan berbagai tipe (Card, Typing, Packaged)
- Perhitungan zakat otomatis (Penghasilan, Maal, Fitrah)
- Integrasi payment gateway (Midtrans, Xendit, Tripay)
- Notifikasi email dan WhatsApp
- Riwayat donasi dengan filter dan ekspor
- Donasi anonim
- Halaman sukses dan gagal donasi

## Persyaratan

- WordPress 5.0 atau lebih tinggi
- PHP 7.4 atau lebih tinggi
- MySQL 5.6 atau lebih tinggi
- Plugin Redux Framework

## Instalasi

1. Upload folder `gdp-donasi` ke direktori `/wp-content/themes/`
2. Aktifkan tema melalui menu 'Tampilan > Tema' di WordPress
3. Install dan aktifkan plugin Redux Framework
4. Konfigurasi pengaturan tema di menu 'GDP Donasi'

## Pengaturan

### Pengaturan Umum

- Halaman donasi
- Mata uang
- Minimal donasi
- Jumlah donasi yang disarankan

### Payment Gateway

- Pilih payment gateway (Midtrans/Xendit/Tripay)
- Konfigurasi API key dan mode (sandbox/production)

### Notifikasi

- Email admin
- Logo untuk email
- Nomor WhatsApp admin
- URL dan API key WhatsApp

## Penggunaan

### Membuat Program Donasi

1. Buka menu 'Program > Tambah Baru'
2. Isi informasi program:
   - Judul dan deskripsi
   - Target donasi
   - Batas waktu
   - Tipe form (Card/Typing/Packaged)
   - Pengaturan zakat (opsional)
3. Publish program

### Form Donasi

Tersedia 3 tipe form:
1. **Card**: Pilihan nominal donasi dalam bentuk card
2. **Typing**: Input nominal donasi manual
3. **Packaged**: Paket donasi dengan nominal dan deskripsi

### Perhitungan Zakat

Tersedia 3 jenis zakat:
1. **Penghasilan**: 2.5% dari penghasilan
2. **Maal**: 2.5% dari harta
3. **Fitrah**: Sesuai ketentuan

### Riwayat Donasi

1. Buka menu 'Program > Riwayat Donasi'
2. Filter berdasarkan:
   - Program
   - Status
   - Tanggal
3. Ekspor data ke CSV

## Hooks dan Filter

### Actions
```php
// Ketika status donasi berubah
do_action('gdp_donation_status_changed', $donation_id, $new_status, $old_status);

// Setelah donasi dibuat
do_action('gdp_after_donation_created', $donation_id);
```

### Filters
```php
// Modifikasi minimal donasi
add_filter('gdp_minimum_donation_amount', function($amount) {
    return 50000; // Rp 50.000
});

// Modifikasi jumlah donasi yang disarankan
add_filter('gdp_suggested_donation_amounts', function($amounts) {
    return [100000, 200000, 500000, 1000000];
});
```

## Troubleshooting

### Payment Gateway

1. **Midtrans**
   - Pastikan Server Key sudah benar
   - Cek mode sandbox/production
   - Verifikasi callback URL

2. **Xendit**
   - Pastikan API Key sudah benar
   - Cek mode sandbox/production
   - Verifikasi callback URL

3. **Tripay**
   - Pastikan Merchant Code dan API Key sudah benar
   - Cek mode sandbox/production
   - Verifikasi callback URL

### Notifikasi

1. **Email**
   - Cek pengaturan SMTP WordPress
   - Pastikan email admin sudah benar
   - Cek folder spam

2. **WhatsApp**
   - Pastikan URL dan API Key WhatsApp sudah benar
   - Cek format nomor telepon (awalan 62)
   - Verifikasi status API WhatsApp

## Lisensi

GPL v2 atau yang lebih tinggi

## Kredit

Dikembangkan oleh Gusvira Digital
