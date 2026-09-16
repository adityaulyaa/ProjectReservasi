# SRS (Software Requirements Specification)

## Module 1: Authentication & User Management
Modul ini mengelola hak akses akun, manajemen pengguna oleh Admin, serta validasi keamanan input.

### 1.1 Admin User Creation (SRS-MOD1-01)
- **Deskripsi:** Admin dapat menambahkan akun pengguna baru ke dalam sistem.
- **Input:** Nama lengkap, Alamat Email, dan Password sementara.
- **Validasi:** Email format valid & unik, password minimal panjang, semua input divalidasi. Menggunakan Eloquent ORM untuk mencegah SQL Injection.

### 1.2 Admin User Deletion (SRS-MOD1-02)
- **Deskripsi:** Admin dapat menghapus akun pengguna dari sistem.
- **Proses:** Penghapusan mencabut hak akses dan menyesuaikan keanggotaan di daftar terkait.

### 1.3 Role & Authorization Check (SRS-MOD1-03)
- **Deskripsi:** Sistem menolak akses otomatis bagi pengguna yang tidak berwenang (Unauthorized Request).
- **Aturan:** Hanya role **Admin** yang dapat mengakses fungsi pembuatan dan penghapusan akun.

---

## Module 2: List / Project & Team Collaboration
Modul ini menangani pengelolaan daftar (project/list), keanggotaan tim, dan transaksi atomis.

### 2.1 Creation of List & Auto-Ownership (SRS-MOD2-01)
- **Deskripsi:** Pengguna dapat membuat daftar baru untuk mengelompokkan tugas.
- **Aturan Bisnis:** Pembuat otomatis menjadi **Pemilik Daftar**.

### 2.2 Atomic Deletion of List (SRS-MOD2-02)
- **Deskripsi:** Pemilik dapat menghapus daftar miliknya.
- **Transaksi Atomis:** Penghapusan daftar, semua tugas, dan data keanggotaan (`list_user`) dilakukan dalam `DB::transaction()`. Jika ada kegagalan, seluruh perubahan di‑rollback.

### 2.3 Team Collaboration / Member Management (SRS-MOD2-03)
- **Deskripsi:** Pemilik dapat menambahkan pengguna lain ke daftar untuk kolaborasi bersama.

### 2.4 Authorization & Input Validation for List (SRS-MOD2-04)
- **Keamanan:** Permintaan hapus atau penambahan anggota oleh non‑owner ditolak (Laravel Policy → 403 Forbidden). Semua input nama/deskripsi daftar divalidasi dan diproses dengan parameterized query.

---

## Module 3: Task Management & Operations
Modul ini menangani siklus hidup tugas, penetapan atribut, dan penandaan selesai.

### 3.1 Task Creation & Assignment (SRS-MOD3-01)
- **Deskripsi:** Pemilik maupun anggota dapat membuat tugas baru dalam daftar terkait dan/atau menunjuk penanggung jawab.

### 3.2 Task Attributes - Priority & Due Date (SRS-MOD3-02)
- **Deskripsi:** Pengguna dapat menetapkan prioritas (Low, Medium, High) dan tenggat waktu pada tiap tugas.
- **Validasi:** Tanggal harus format tanggal yang valid; semua parameter diproses aman dari SQL Injection.

### 3.3 Task Completion Marking (SRS-MOD3-03)
- **Deskripsi:** Pengguna dapat menandai tugas selesai (Completed) atau mengubah status kembali.

### 3.4 Task Authorization (SRS-MOD3-04)
- **Aturan:** Pengguna di luar daftar dilarang membuat, mengubah, atau menandai tugas.

---

## Module 4: Progress Tracking & Monitoring
Modul ini menangani visibilitas dan pemantauan penyelesaian tugas dalam suatu daftar.

### 4.1 List Progress Monitoring (SRS-MOD4-01)
- **Deskripsi:** Pemilik dan anggota dapat memantau progress penyelesaian tugas dalam daftar.
- **Fitur:** Menampilkan progres bar (%) berdasarkan rasio tugas selesai vs total tugas.

### 4.2 Task Filtering & Alerting (SRS-MOD4-02)
- **Deskripsi:** Sistem dapat memfilter tugas dan memberikan indikator visual untuk tugas yang melewati tenggat waktu (Overdue).

---

## 2. Pembagian Tugas Tiap Programmer (Development Phase)
Dengan asumsi setup awal repo dan framework telah selesai, pengembangan dibagi **Vertical Slicing / Feature‑Based** untuk 3 programmer:

```
+-----------------------------------------------------------------------------------+
|                            DEVELOPMENT PHASE (3 PROGRAMMERS)                      |
+------------------------------------+----------------------------------------------+
                                     |
    +--------------------------------+--------------------------------+
    |                                |                                |
+---v------------------------+  +----v-----------------------+  +-----v------------------------+
| PROGRAMMER 1               |  | PROGRAMMER 2               |  | PROGRAMMER 3                 |
| User Auth, Admin & Security|  | Project/List & Transaksi   |  | Task Operations & Progress   |
| (Modul 1)                  |  | Atomis (Modul 2)           |  | Monitoring (Modul 3 & 4)    |
+----------------------------+  +----------------------------+  +------------------------------+
```

### Programmer 1: User Auth, Admin & Security Specialist (Modul 1)
- Implement `AdminController` & Blade UI untuk manajemen akun.
- Middleware memastikan hanya role `Admin` yang dapat mengakses.
- Form Request Validation untuk semua input (nama, email, password).

### Programmer 2: Project/List & Atomic Transaction Specialist (Modul 2)
- `ListController` & Blade UI untuk pembuatan daftar, otomatis set owner.
- Hapus daftar menggunakan `DB::transaction()` untuk atomicity.
- Fitur penambahan/penghapusan anggota serta `ListPolicy` untuk otorisasi.

### Programmer 3: Task Operations & Progress Monitoring Specialist (Modul 3 & 4)
- `TaskController` & Blade modal untuk CRUD tugas, prioritas, due date, assignee, dan toggle selesai.
- Kalkulasi persentase penyelesaian tugas dan progress bar.
- Indikator visual (warna merah) untuk tugas overdue serta filter tugas.
