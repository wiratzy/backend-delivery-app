<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page</title>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 600px;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            background-color: #ffffff;
            margin-top: 50px;
        }
        .btn-primary {
            background-color: #ff5722; /* Warna Deep Orange */
            border-color: #ff5722;
        }
        .btn-primary:hover {
            background-color: #e64a19;
            border-color: #e64a19;
        }
        .btn-success {
            background-color: #4CAF50; /* Warna Hijau */
            border-color: #4CAF50;
        }
        .btn-success:hover {
            background-color: #45a049;
            border-color: #45a049;
        }
        .modal-header {
            background-color: #ff5722;
            color: white;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }
        .modal-content {
            border-radius: 15px;
        }
        .form-control:focus {
            border-color: #ff5722;
            box-shadow: 0 0 0 0.25rem rgba(255, 87, 34, 0.25);
        }
        .is-invalid {
            border-color: #dc3545;
        }
        .invalid-feedback {
            display: block; /* Agar pesan error langsung terlihat */
            width: 100%;
            margin-top: 0.25rem;
            font-size: .875em;
            color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="container text-center mt-5">
        <img src="{{ asset('storage/photos/app_logo.png') }}" alt="Logo Aplikasi" class="mb-4" style="height: 120px;">
        <h2 class="mb-4" style="color: #333;">Selamat Datang di Aplikasi KarPel Food Delivery</h2>

        <div class="d-grid gap-3 col-8 mx-auto">
            <a href="{{ asset('storage/apk/app-release.apk') }}" class="btn btn-primary btn-lg mb-3">
                <i class="fas fa-download me-2"></i> Download Aplikasi Pengguna
            </a>
            <button class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#restaurantModal">
                <i class="fas fa-store me-2"></i> Daftar Sebagai Restoran
            </button>
        </div>
    </div>

    <div class="modal fade" id="restaurantModal" tabindex="-1" aria-labelledby="restaurantModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <form id="restaurantApplicationForm" action="{{ route('restaurant-application.store') }}" enctype="multipart/form-data" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="restaurantModalLabel">Form Pendaftaran Restoran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Restoran</label>
                    <input type="text" name="name" id="name" class="form-control" required>
                    <div class="text-danger mt-1" id="name_error"></div>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email Restoran</label>
                    <input type="email" name="email" id="email" class="form-control" required>
                    <div class="text-danger mt-1" id="email_error"></div>
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">No. Telepon (Format Indonesia: 08xx atau +628xx)</label>
                    <input type="text" name="phone" id="phone" class="form-control" placeholder="Contoh: 081234567890">
                    <div class="text-danger mt-1 invalid-feedback" id="phone_error"></div>
                </div>
                <div class="mb-3">
                    <label for="location" class="form-label">Lokasi Restoran (Alamat Lengkap)</label>
                    <textarea name="location" id="location" class="form-control" rows="3" required></textarea>
                    <div class="text-danger mt-1" id="location_error"></div>
                </div>
                <div class="mb-3">
                    <label for="restaurant_type" class="form-label">Tipe Restoran (contoh: Cafe, Restoran Keluarga)</label>
                    <input type="text" name="restaurant_type" id="restaurant_type" class="form-control" required>
                    <div class="text-danger mt-1" id="restaurant_type_error"></div>
                </div>
                <div class="mb-3">
                    <label for="food_type" class="form-label">Jenis Makanan (contoh: Masakan Indonesia, Fast Food)</label>
                    <input type="text" name="food_type" id="food_type" class="form-control" required>
                    <div class="text-danger mt-1" id="food_type_error"></div>
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Logo/Gambar Restoran</label>
                    <input type="file" name="image" id="image" class="form-control" accept="image/png, image/jpeg, image/jpg">
                    <div class="text-danger mt-1" id="image_error"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary" id="submitBtn">Kirim Pengajuan</button>
            </div>
        </form>
      </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('restaurantApplicationForm');
            const submitBtn = document.getElementById('submitBtn');
            const phoneInput = document.getElementById('phone');
            const phoneError = document.getElementById('phone_error');

            // Regex untuk validasi nomor telepon Indonesia di frontend
            const indoPhoneRegex = /^(08\d{8,11}|\+628\d{8,11})$/;

            function validatePhone() {
                const phoneValue = phoneInput.value.trim();
                if (phoneValue === '') {
                    phoneInput.classList.remove('is-invalid');
                    phoneError.textContent = '';
                    return false; // Biarkan required dihandle oleh browser atau validasi submit
                } else if (!indoPhoneRegex.test(phoneValue)) {
                    phoneInput.classList.add('is-invalid');
                    phoneError.textContent = 'Format nomor telepon tidak valid. Gunakan 08xx atau +628xx.';
                    return false;
                } else {
                    phoneInput.classList.remove('is-invalid');
                    phoneError.textContent = '';
                    return true;
                }
            }

            // Tambahkan event listener untuk validasi on-the-fly
            phoneInput.addEventListener('input', validatePhone);


            form.addEventListener('submit', async function(event) {
                event.preventDefault(); // Mencegah submit form default

                // Validasi ulang nomor telepon saat submit
                if (!validatePhone()) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validasi Gagal!',
                        text: 'Mohon perbaiki kesalahan pada formulir, terutama nomor telepon.',
                        confirmButtonColor: '#d33',
                    });
                    return; // Hentikan proses submit jika validasi frontend gagal
                }

                const formData = new FormData(form);

                // Reset error messages from previous attempts
                document.querySelectorAll('.text-danger').forEach(el => el.textContent = '');
                document.querySelectorAll('.form-control').forEach(el => el.classList.remove('is-invalid'));


                submitBtn.disabled = true; // Nonaktifkan tombol saat submit
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mengirim...'; // Tambahkan spinner

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'Accept': 'application/json'
                        }
                    });

                    const result = await response.json();

                    if (response.ok) { // Status 2xx
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: result.message,
                            confirmButtonColor: '#3085d6',
                        }).then(() => {
                            form.reset(); // Reset form setelah sukses
                            var modal = bootstrap.Modal.getInstance(document.getElementById('restaurantModal'));
                            modal.hide(); // Tutup modal
                        });
                    } else { // Status non-2xx (termasuk 422 untuk validasi)
                        let errorMessage = result.message || 'Terjadi kesalahan saat mengirim pengajuan.';
                        if (result.errors) {
                            for (const field in result.errors) {
                                const errorElement = document.getElementById(`${field}_error`);
                                if (errorElement) {
                                    errorElement.textContent = result.errors[field][0];
                                    document.getElementById(field).classList.add('is-invalid'); // Tambahkan kelas invalid
                                }
                            }
                            errorMessage += '\nSilakan periksa kembali input Anda.';
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: errorMessage,
                            confirmButtonColor: '#d33',
                        });
                    }
                } catch (error) {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Kesalahan Jaringan!',
                        text: 'Tidak dapat terhubung ke server. Mohon coba lagi nanti.',
                        confirmButtonColor: '#d33',
                    });
                } finally {
                    submitBtn.disabled = false; // Aktifkan kembali tombol
                    submitBtn.innerHTML = 'Kirim Pengajuan'; // Kembalikan teks tombol
                }
            });
        });
    </script>
</body>
</html>
