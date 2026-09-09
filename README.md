# SRS (Software Requirements Specification)

## Module 1: Authentication & User Management
Modul ini mengelola pendaftaran, autentikasi, serta hak akses dalam sistem.

### 1.1 System Admin Management (Fungsional Admin)

- **FR-ADM-01**: Admin dapat menambahkan akun pengguna baru (Create User) secara manual dengan menginput nama, email, dan password sementara.
- **FR-ADM-02**: Admin dapat menghapus akun pengguna (Delete User) yang akan berdampak pada pencabutan akses pengguna tersebut dari sistem.
- **FR-ADM-03**: Admin dapat melihat daftar seluruh akun pengguna yang terdaftar di dalam sistem (Read/List Users).
- **FR-ADM-04**: Admin dapat mereset password akun pengguna jika terjadi kendala akses.

### 1.2 Authentication & Authorization (Fungsional Pengguna)

- **FR-ATH-01**: Pengguna dapat melakukan Login menggunakan email dan password.
- **FR-ATH-02**: Pengguna dapat melakukan Logout dari sistem.
- **FR-ATH-03**: Sistem harus memvalidasi hak akses (Role-based Access Control / RBAC) antara Admin dan User Biasa.

## Module 2: Project & List Management
Modul ini mengelola wadah pengelompokan tugas, baik personal maupun tim.

### 2.1 Manajemen Daftar/Proyek

- **FR-LST-01**: Pengguna dapat membuat daftar (list/project) baru untuk mengelompokkan tugas.
- **FR-LST-02**: Pengguna yang membuat daftar secara otomatis menjadi Pemilik Daftar (List Owner).
- **FR-LST-03**: Pemilik Daftar dapat mengubah nama, deskripsi, atau menghapus daftar yang dimilikinya.
- **FR-LST-04**: Pengguna dapat melihat semua daftar pribadi dan daftar kolaborasi tempat ia terdaftar.

### 2.2 Kolaborasi & Keanggotaan Daftar

- **FR-COL-01**: Pemilik Daftar dapat mengundang/menambahkan pengguna lain (Anggota/Member) ke dalam daftarnya menggunakan email atau nama pengguna.
- **FR-COL-02**: Pemilik Daftar dapat mengeluarkan anggota dari daftarnya.
- **FR-COL-03**: Anggota yang ditambahkan dapat melihat seluruh tugas dan berkontribusi di dalam daftar tersebut.

## Module 3: Task Management
Modul inti untuk pengelolaan tugas individu maupun bersama.

### 3.1 Operasi Dasar Tugas (CRUD)

- **FR-TSK-01**: Pengguna dapat membuat tugas baru di dalam daftar tertentu.
- **FR-TSK-02**: Pengguna dapat memperbarui rincian tugas (judul, deskripsi).
- **FR-TSK-03**: Pengguna dapat menghapus tugas dari daftar.

### 3.2 Atribut & Penugasan

- **FR-TSK-04**: Pengguna dapat menetapkan tingkat prioritas pada tugas (misal: Low, Medium, High, Urgent).
- **FR-TSK-05**: Pengguna dapat menentukan tenggat waktu (due date) dan jam penyelesaian tugas.
- **FR-TSK-06**: Pemilik Daftar/Anggota dapat menunjuk (assign) diri sendiri atau anggota lain dalam daftar untuk mengerjakan tugas tertentu.
- **FR-TSK-07**: Pengguna dapat mengubah status tugas menjadi Selesai (Completed) atau membukanya kembali (Re-open).

## Module 4: Progress Tracking & Monitoring
Modul khusus pemantauan perkembangan tugas bagi Pemilik Daftar dan Anggota.

- **FR-PRG-01**: Pemilik Daftar dapat melihat progress bar atau persentase penyelesaian tugas secara keseluruhan dalam satu daftar (misal: 5 dari 10 tugas selesai = 50%).
- **FR-PRG-02**: Pemilik Daftar dapat memfilter tugas berdasarkan status (Belum Selesai, Selesai), prioritas, atau anggota yang ditunjuk.
- **FR-PRG-03**: Sistem menampilkan indikator visual (misal: warna merah) untuk tugas-tugas yang telah melewati tenggat waktu (overdue).

## Module 5: Non-Functional Requirements (NFR)
Spesifikasi kualitas dan performa aplikasi web Jara.

- **NFR-SEC-01 (Kemanan)**: Password pengguna wajib dienkripsi menggunakan algoritma hashing yang aman (misal: bcrypt).
- **NFR-PER-01 (Performa)**: Halaman web harus dapat dimuat (load time) kurang dari 2 detik pada kondisi koneksi internet normal.
- **NFR-USE-01 (Usability)**: Antarmuka web (UI/UX) harus responsif (dapat diakses dengan baik melalui layar desktop maupun mobile/tablet).

---

# Pembagian Tugas 3 Programmer (Laravel Monolith)

Dalam arsitektur monolitik Laravel, pembagian tugas dibagi menjadi Fitur Admin & Fondasi, Fitur Kolaborasi Proyek, dan Fitur Inti Tugas & Dashboard.

```
                           +-----------------------------------+
                           |        PROGRAMMER 1 (LEAD)        |
                           | Setup Master, Auth & Modul Admin  |
                           +-----------------+-----------------+
                                             |
            +--------------------------------+--------------------------------+
            |                                                                 |
+-----------v-----------------------+                             +-----------v-----------------------+
| PROGRAMMER 2                      |                             | PROGRAMMER 3                      |
| Modul List/Project & Kolaborasi   |                             | Modul Task Engine & Progress UI   |
+-----------------------------------+                             +-----------------------------------+
```

## Programmer 1: Foundation, Auth & Admin Management (Lead)

**Fokus**: Menyiapkan struktur dasar aplikasi Laravel dan menangani seluruh manajemen akun.

### Tanggung Jawab

#### Setup Proyek
Inisialisasi framework Laravel, pengaturan database (.env), konfigurasi CSS (Tailwind/Bootstrap), dan struktur folder views/Blade layout utama.

#### Authentication & Access Control
- Membuat fitur Login & Logout (bisa memanfaatkan Laravel Breeze / skema auth standar).
- Membuat Middleware Laravel untuk memisahkan hak akses antara Admin dan User.

#### Modul Admin (SRS Module 1)
- Membuat AdminController & Migration tambahan untuk role user.
- Membuat tampilan Blade Dashboard Admin.
- Mengimplementasikan fungsi Tambah User Baru dan Hapus User oleh Admin.

#### Migration Database
Membuat tabel dasar users dan roles.

## Programmer 2: List/Project & Team Collaboration Specialist

**Fokus**: Menangani wadah proyek/daftar dan sistem pengundangan anggota tim.

### Tanggung Jawab

#### Database Migration & Model
Membuat tabel lists / projects dan tabel pivot list_user (untuk relasi Many-to-Many antara Daftar dan Anggota).

#### Modul List / Project (SRS Module 2.1)
- Membuat ListController (CRUD Daftar/Proyek).
- Membuat tampilan Blade untuk halaman daftar proyek (Halaman Utama User).
- Memastikan aturan bahwa pembuat daftar otomatis tercatat sebagai List Owner.

#### Modul Kolaborasi Tim (SRS Module 2.2)
- Membuat fitur pencarian dan penambahan akun user ke dalam daftar (Add Member).
- Membuat fitur hapus anggota dari daftar (Remove Member).
- Membuat Laravel Policy (ListPolicy) untuk memastikan hanya Owner yang bisa menambah/menghapus anggota atau menghapus daftar.

## Programmer 3: Task Engine & Progress Tracking Specialist

**Fokus**: Menangani pengoperasian tugas individu/tim dan visualisasi pemantauan.

### Tanggung Jawab

#### Database Migration & Model
Membuat tabel tasks (dengan kolom list_id, assigned_to, title, description, priority, due_date, is_completed).

#### Modul Pengelolaan Tugas (SRS Module 3)
- Membuat TaskController (CRUD Tugas di dalam daftar).
- Membuat form modal/halaman Blade untuk tambah & edit tugas.
- Mengimplementasikan fitur penetapan Priority, Due Date, Assignee (pilih anggota daftar), dan toggle Check/Uncheck (Selesai).

#### Modul Progress & Monitoring (SRS Module 4)
- Membuat kalkulasi persentase penyelesaian tugas di Controller/Model.
- Membuat komponen Progress Bar di tampilan Blade.
- Membuat logika kondisional warna/badge untuk tugas yang Overdue (melewati tenggat waktu).
- Membuat fitur filtering tugas (berdasarkan status selesai/belum, prioritas, atau penanggung jawab).

## Alur Integrasi Antar Programmer

### Sprint Awal (Hari 1-2)
Programmer 1 melakukan setup repositori Git, instansiasi Laravel, dan membagikan basis kode awal yang sudah memiliki sistem Auth.

### Sprint Pengembangan (Hari 3-7)
- Programmer 1 menyelesaikan fitur Admin.
- Programmer 2 membuat fitur List dan pengundangan anggota tim.
- Programmer 3 membuat fitur Task di dalam List yang dibuat Programmer 2.

### Sprint Final (Hari 8-10)
Penggabungan seluruh file Blade layout, pengujian Laravel Policy/Middleware, dan perbaikan tampilan (UI).
