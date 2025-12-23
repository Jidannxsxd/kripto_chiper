# Aplikasi Caesar Cipher - Komputasi Paralel

Aplikasi web sederhana untuk mendemonstrasikan konsep **Komputasi Paralel dan Terdistribusi** menggunakan teknik kriptografi **Caesar Cipher**.

## Struktur File

```
Komputasi Pararel/
├── index.php       # Halaman utama dengan UI dan form input
├── process.php     # Logic Caesar Cipher dan simulasi paralel
└── style.css       # Styling modern dan responsif
```

## Cara Menggunakan

### 1. Persiapan
- Pastikan server PHP sudah terinstall (XAMPP, WAMP, atau built-in PHP server)
- Letakkan folder aplikasi di direktori yang sesuai

### 2. Menjalankan Aplikasi

**Menggunakan XAMPP/WAMP:**
- Letakkan folder di `htdocs/` atau `www/`
- Buka browser dan akses: `http://localhost/Semester%205/Komputasi%20Pararel/index.php`

**Menggunakan PHP Built-in Server:**
```bash
cd "c:\Users\zidane\Documents\Semester 5\Komputasi Pararel"
php -S localhost:8000
```
- Buka browser: `http://localhost:8000/index.php`

### 3. Menggunakan Aplikasi
1. Masukkan teks yang ingin dienkripsi/dekripsi
2. Atur shift key (1-25)
3. Pilih mode: Enkripsi atau Dekripsi
4. Klik tombol "Proses"
5. Lihat hasil processing dari setiap node dan hasil akhir

## Konsep yang Diimplementasikan

### 1. Kriptografi - Caesar Cipher
- **Enkripsi**: Menggeser setiap huruf sebanyak shift key dalam alfabet
- **Dekripsi**: Mengembalikan huruf yang sudah digeser ke posisi semula
- **Contoh**: 
  - Input: "HELLO" 
  - Shift: 3
  - Output: "KHOOR"

#### Apa itu Shift Key?

**Shift Key** adalah kunci atau angka yang menentukan **berapa posisi** setiap huruf akan digeser dalam alfabet. Ini adalah komponen penting dalam Caesar Cipher.

**Cara Kerja Shift Key:**

- **Rentang nilai**: 1 sampai 25
- **Untuk Enkripsi**: Huruf digeser maju (ke kanan) dalam alfabet
- **Untuk Dekripsi**: Huruf digeser mundur (ke kiri) dalam alfabet

**Contoh Detail dengan Shift Key = 3:**

| Huruf Asli | A | B | C | D | E | F | G | H | I | J | K |
|------------|---|---|---|---|---|---|---|---|---|---|---|
| Terenkripsi| D | E | F | G | H | I | J | K | L | M | N |

Jadi:
- `A` + shift 3 = `D`
- `H` + shift 3 = `K`
- `E` + shift 3 = `H`
- `L` + shift 3 = `O`

Maka **"HELLO"** menjadi **"KHOOR"**

**Dimana Unsur Kriptografi Cipher diterapkan?**

Unsur kriptografi cipher diimplementasikan di file **[process.php](file:///c:/Users/zidane/Documents/Semester%205/Komputasi%20Pararel/process.php)** dalam class `CipherProcessor`:

1. **Fungsi Enkripsi** (`encrypt()` - baris ~46-62)
   - Mengiterasi setiap karakter dalam teks
   - Untuk huruf UPPERCASE: menggeser dengan rumus `((ord($char) - 65 + $shift) % 26) + 65`
   - Untuk huruf lowercase: menggeser dengan rumus `((ord($char) - 97 + $shift) % 26) + 97`
   - Karakter non-huruf tetap tidak berubah (spasi, tanda baca, angka)
   
2. **Fungsi Dekripsi** (`decrypt()` - baris ~64-80)
   - Proses kebalikan dari enkripsi
   - Menggeser mundur dengan rumus `((ord($char) - 65 - $shift + 26) % 26) + 65`
   - Menggunakan `+26` untuk menghindari nilai negatif saat modulo
   
3. **Algoritma Caesar Cipher**
   - Menggunakan operasi modulo (`%26`) untuk wrapping alfabet (Z kembali ke A)
   - Konversi ASCII: `ord()` untuk mengambil kode ASCII, `chr()` untuk konversi balik
   - Offset 65 untuk uppercase (A=65) dan 97 untuk lowercase (a=97)

**Contoh Proses Kriptografi:**
```
Input: 'H' (ASCII 72)
Shift: 3

Enkripsi:
(72 - 65 + 3) % 26 + 65 = 10 % 26 + 65 = 75 → 'K'

Dekripsi: 
(75 - 65 - 3 + 26) % 26 + 65 = 33 % 26 + 65 = 7 + 65 = 72 → 'H'
```

**Mengapa Shift Key Penting?**
- Shift key berfungsi sebagai **kunci rahasia** dalam enkripsi
- Tanpa mengetahui shift key yang tepat, pesan terenkripsi sulit didekripsi
- Pengirim dan penerima harus sepakat menggunakan shift key yang sama
- Semakin bervariasi shift key, semakin aman (dalam konteks sederhana)

### 2. Komputasi Paralel
- Teks input dibagi menjadi **3 chunks** (bagian)
- Setiap chunk diproses secara terpisah
- Simulasi pemrosesan paralel menggunakan loop

**Dimana Unsur Komputasi Paralel diterapkan?**

Implementasi ada di **[process.php](file:///c:/Users/zidane/Documents/Semester%205/Komputasi%20Pararel/process.php)** dalam class `CipherProcessor`:

1. **Pembagian Teks** (`splitText()` - baris ~33-44)
   ```php
   // Menghitung ukuran setiap chunk
   $chunkSize = ceil($length / $parts);
   
   // Membagi teks menjadi beberapa bagian
   for ($i = 0; $i < $parts; $i++) {
       $start = $i * $chunkSize;
       $chunk = substr($text, $start, $chunkSize);
       $chunks[] = $chunk;
   }
   ```
   - Teks dibagi secara merata menjadi 3 bagian
   - Menggunakan `ceil()` untuk pembulatan ke atas
   - Setiap chunk berukuran hampir sama

2. **Pemrosesan Paralel** (`processText()` - baris ~8-31)
   ```php
   // Loop untuk memproses setiap chunk
   for ($i = 0; $i < count($chunks); $i++) {
       $chunk = $chunks[$i];
       
       // Proses enkripsi/dekripsi per chunk
       if ($mode === 'encrypt') {
           $processed = $this->encrypt($chunk, $shift);
       } else {
           $processed = $this->decrypt($chunk, $shift);
       }
       
       // Simpan hasil per node
       $nodeResults[] = [
           'name' => 'Node ' . ($i + 1),
           'input' => $chunk,
           'output' => $processed
       ];
   }
   ```
   - Setiap chunk diproses **independen** satu sama lain
   - Simulasi pemrosesan paralel (dalam implementasi nyata, ini berjalan bersamaan)
   - Hasil disimpan terpisah untuk setiap chunk

**Konsep Komputasi Paralel:**
- **Task Decomposition**: Pekerjaan besar dipecah menjadi tugas kecil
- **Independent Processing**: Setiap chunk tidak bergantung pada chunk lain
- **Speedup**: Untuk teks panjang, proses paralel lebih cepat dibanding sequential
- **Scalability**: Jumlah node bisa ditambah sesuai kebutuhan (saat ini 3 node)


### 3. Sistem Terdistribusi
- Setiap chunk diproses oleh "node" yang berbeda
- Node 1, Node 2, Node 3 bekerja secara independen
- Hasil dari semua node digabungkan menjadi output final

**Dimana Unsur Sistem Terdistribusi diterapkan?**

Implementasi di **[process.php](file:///c:/Users/zidane/Documents/Semester%205/Komputasi%20Pararel/process.php)** dan **[index.php](file:///c:/Users/zidane/Documents/Semester%205/Komputasi%20Pararel/index.php)**:

1. **Arsitektur Node** (process.php - baris ~8-31)
   ```php
   private $numNodes = 3;  // Jumlah node virtual
   
   // Setiap chunk diproses oleh node berbeda
   for ($i = 0; $i < count($chunks); $i++) {
       $nodeResults[] = [
           'name' => 'Node ' . ($i + 1),  // Node identifier
           'input' => $chunk,              // Data input
           'output' => $processed          // Hasil processing
       ];
   }
   ```
   - Sistem memiliki **3 node virtual** yang independen
   - Setiap node memiliki identifier unik (Node 1, Node 2, Node 3)
   - Node menyimpan input dan output secara terpisah

2. **Agregasi Hasil** (process.php - baris ~23-27)
   ```php
   // Menggabungkan hasil dari semua node
   $finalResult = '';
   foreach ($nodeResults as $node) {
       $finalResult .= $node['output'];
   }
   ```
   - Hasil dari setiap node dikumpulkan
   - Dilakukan agregasi/penggabungan hasil
   - Output final adalah gabungan semua node

3. **Visualisasi Distributed System** (index.php - baris ~54-68)
   ```php
   // Menampilkan hasil per node
   foreach ($result['nodes'] as $node) {
       echo '<div class="node-card">';
       echo '<span class="node-name">' . $node['name'] . '</span>';
       echo '<span class="node-status">✓ Selesai</span>';
       echo '<p>Input Chunk: ' . $node['input'] . '</p>';
       echo '<p>Output: ' . $node['output'] . '</p>';
       echo '</div>';
   }
   ```
   - Setiap node ditampilkan dalam card terpisah
   - Menunjukkan status processing setiap node
   - Transparansi proses distributed computing

**Konsep Sistem Terdistribusi:**
- **Node Independence**: Setiap node bekerja independen tanpa tergantung node lain
- **Data Distribution**: Data didistribusikan ke node berbeda untuk diproses
- **Result Aggregation**: Hasil dari semua node dikumpulkan dan digabungkan
- **Fault Tolerance**: Jika satu node gagal, node lain tetap bisa bekerja
- **Scalability**: Mudah menambah jumlah node (ubah `$numNodes`)

**Perbedaan dengan Sistem Sequential:**

| Aspek | Sequential | Distributed (Aplikasi ini) |
|-------|-----------|---------------------------|
| Pemrosesan | Satu per satu | Bersamaan di node berbeda |
| Waktu | Lebih lama | Lebih cepat (teoritis) |
| Resource | 1 processor | Multiple nodes |
| Visualisasi | Hasil akhir saja | Proses setiap node terlihat |


## Fitur UI/UX

✨ **Design Modern**
- Gradient background yang menarik
- Card design untuk setiap node
- Warna lembut dan konsisten

🎯 **Interaktif**
- Animasi hover pada tombol
- Transisi smooth
- Visual feedback untuk setiap proses

📱 **Responsive**
- Tampilan optimal untuk desktop
- Grid layout yang fleksibel

## Contoh Penggunaan

### Enkripsi
- **Input**: "Komputasi Paralel"
- **Shift**: 5
- **Mode**: Enkripsi
- **Output**: "Ptruzyfxn Ufwfqjq"

### Dekripsi
- **Input**: "Ptruzyfxn Ufwfqjq"
- **Shift**: 5
- **Mode**: Dekripsi
- **Output**: "Komputasi Paralel"

## Teknologi

- **Backend**: PHP 7.x/8.x
- **Frontend**: HTML5, CSS3
- **Konsep**: Object-Oriented Programming (Class-based)
