<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - KasirKu (Point of Sales)</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        .login-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35);
            overflow: hidden;
            max-width: 960px;
            width: 100%;
        }

        .login-banner {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: #ffffff;
            padding: 3rem 2.5rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .login-form-wrapper {
            padding: 3rem 2.5rem;
        }

        .brand-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(8px);
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .demo-pill {
            cursor: pointer;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 0.65rem 0.85rem;
            transition: all 0.2s ease;
            background: #f8fafc;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.5rem;
        }

        .demo-pill:hover {
            border-color: #2563eb;
            background: #eff6ff;
            transform: translateY(-2px);
        }

        .demo-pill .icon-box {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
        }

        .form-control:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15);
        }

        .btn-submit {
            background-color: #2563eb;
            border: none;
            color: white;
            font-weight: 700;
            padding: 0.75rem;
            border-radius: 10px;
            transition: all 0.2s;
        }

        .btn-submit:hover {
            background-color: #1d4ed8;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.4);
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="row g-0">
            <!-- Left Info Banner -->
            <div class="col-lg-5 login-banner d-none d-lg-flex flex-column">
                <div>
                    <div class="brand-badge mb-4">
                        <i class="bi bi-cart-check-fill text-warning"></i>
                        <span>Tugas Besar Pemrograman Web</span>
                    </div>
                    <h2 class="fw-bold mb-3">KasirKu (POS)</h2>
                    <p class="text-white-50 leading-relaxed mb-4">
                        Aplikasi Point of Sales modern terintegrasi dengan 3 tingkatan hak akses pengguna, pencatatan transaksi real-time, dan manajemen inventaris.
                    </p>

                    <div class="mt-4">
                        <h6 class="fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.08em; opacity: 0.8;">3 Tingkatan Hak Akses:</h6>
                        <ul class="list-unstyled mt-3 mb-0" style="font-size: 0.85rem;">
                            <li class="d-flex align-items-center gap-2 mb-2">
                                <i class="bi bi-shield-check text-warning"></i>
                                <span><strong>Owner/Manajer:</strong> Dashboard, Laporan & Akun Pegawai</span>
                            </li>
                            <li class="d-flex align-items-center gap-2 mb-2">
                                <i class="bi bi-box-seam text-info"></i>
                                <span><strong>Admin Gudang:</strong> Master Barang, Kategori & Stok Masuk</span>
                            </li>
                            <li class="d-flex align-items-center gap-2">
                                <i class="bi bi-calculator text-success"></i>
                                <span><strong>Kasir:</strong> Transaksi Penjualan, Cart & Cetak Struk</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="pt-4 border-top border-white-10 text-white-50" style="font-size: 0.8rem;">
                    © 2026 KasirKu POS System. Dibuat dengan Laravel 12 & Bootstrap 5.
                </div>
            </div>

            <!-- Right Form Section -->
            <div class="col-lg-7 login-form-wrapper">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div class="bg-primary text-white rounded p-1 d-inline-flex">
                        <i class="bi bi-shop fs-5"></i>
                    </div>
                    <span class="fw-bold fs-5 text-dark">KasirKu</span>
                </div>
                <h4 class="fw-bold text-dark mb-1">Selamat Datang!</h4>
                <p class="text-muted mb-4" style="font-size: 0.9rem;">Masukkan akun Anda untuk melanjutkan ke sistem kasir.</p>

                <!-- Error Messages -->
                @if($errors->any())
                    <div class="alert alert-danger py-2 px-3 mb-3 border-0 rounded-3" style="font-size: 0.85rem;">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i>
                        {{ $errors->first() }}
                    </div>
                @endif

                @if(session('info'))
                    <div class="alert alert-info py-2 px-3 mb-3 border-0 rounded-3" style="font-size: 0.85rem;">
                        <i class="bi bi-info-circle-fill me-1"></i>
                        {{ session('info') }}
                    </div>
                @endif

                <form action="{{ route('login.post') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold" style="font-size: 0.85rem;">Alamat Email</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" id="email" class="form-control border-start-0 ps-0" placeholder="nama@kasirku.com" value="{{ old('email') }}" required autofocus>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label fw-semibold" style="font-size: 0.85rem;">Kata Sandi</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted border-end-0"><i class="bi bi-lock"></i></span>
                            <input type="password" name="password" id="password" class="form-control border-start-0 ps-0" placeholder="••••••••" required>
                            <button class="btn btn-outline-secondary border-start-0 bg-white" type="button" id="togglePassword">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label text-muted" for="remember" style="font-size: 0.85rem;">
                                Ingat saya
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-submit w-100 d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-box-arrow-in-right"></i>
                        <span>Masuk ke Sistem</span>
                    </button>
                </form>

                <!-- Quick Demo Login Buttons for Lecturer / Grading -->
                <div class="mt-4 pt-3 border-top">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="text-uppercase fw-bold text-muted" style="font-size: 0.72rem; letter-spacing: 0.05em;">Klik Cepat Demo Akun (Untuk Dosen / Penilai):</span>
                    </div>
                    <div class="row g-2">
                        <div class="col-md-4">
                            <div class="demo-pill" onclick="quickFill('owner@kasirku.com', 'password')">
                                <div class="icon-box bg-warning-subtle text-warning">
                                    <i class="bi bi-person-badge"></i>
                                </div>
                                <div class="overflow-hidden">
                                    <div class="fw-bold text-dark text-truncate" style="font-size: 0.8rem;">Owner</div>
                                    <div class="text-muted" style="font-size: 0.7rem;">Pemilik Toko</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="demo-pill" onclick="quickFill('gudang@kasirku.com', 'password')">
                                <div class="icon-box bg-primary-subtle text-primary">
                                    <i class="bi bi-boxes"></i>
                                </div>
                                <div class="overflow-hidden">
                                    <div class="fw-bold text-dark text-truncate" style="font-size: 0.8rem;">Gudang</div>
                                    <div class="text-muted" style="font-size: 0.7rem;">Admin Logistik</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="demo-pill" onclick="quickFill('kasir@kasirku.com', 'password')">
                                <div class="icon-box bg-success-subtle text-success">
                                    <i class="bi bi-cash-coin"></i>
                                </div>
                                <div class="overflow-hidden">
                                    <div class="fw-bold text-dark text-truncate" style="font-size: 0.8rem;">Kasir</div>
                                    <div class="text-muted" style="font-size: 0.7rem;">Front-Office</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        function quickFill(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
        }

        document.getElementById('togglePassword').addEventListener('click', function() {
            const passInput = document.getElementById('password');
            const icon = this.querySelector('i');
            if (passInput.type === 'password') {
                passInput.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                passInput.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });
    </script>
</body>
</html>
