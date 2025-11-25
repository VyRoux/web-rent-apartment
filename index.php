<?php
session_start();
$login_error   = $_SESSION['login_error'] ?? '';
$login_success = $_SESSION['login_success'] ?? '';
unset($_SESSION['login_error'], $_SESSION['login_success']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Sewa Apartmen.id</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #bebebe 0%, #909090 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 1rem;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
        }

        .input-focus {
            transition: .3s;
        }

        .input-focus:focus {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(102,126,234,0.2);
        }

        .btn-hover {
            transition: .3s;
        }

        .btn-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(102,126,234,0.4);
        }

        @keyframes float {
            0%   { transform: translateY(0); }
            50%  { transform: translateY(-20px); }
            100% { transform: translateY(0); }
        }

        .float-animation {
            animation: float 6s ease-in-out infinite;
        }

        .bg-decoration {
            position: absolute;
            inset: 0;
            overflow: hidden;
            z-index: 0;
        }

        .bg-decoration .bubble {
            position: absolute;
            border-radius: 50%;
            opacity: 0.7;
        }

        .form-control:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 .25rem rgba(99,102,241,0.25);
        }

        .btn-primary {
            background-color: #6366f1;
            border-color: #6366f1;
        }

        .btn-primary:hover {
            background-color: #4f46e5;
            border-color: #4f46e5;
        }
    </style>
</head>

<body>

    <!-- Background Decoration -->
    <div class="bg-decoration">
        <div class="bubble float-animation" style="width:20rem;height:20rem;background:#a62020;top:-10rem;right:-10rem;"></div>
        <div class="bubble float-animation" style="width:20rem;height:20rem;background:#339f25;bottom:-10rem;left:-10rem;animation-delay:2s;"></div>
        <div class="bubble float-animation" style="width:20rem;height:20rem;background:#2aa0a0;top:50%;left:50%;transform:translate(-50%,-50%);animation-delay:4s;"></div>
    </div>

    <!-- Login Container -->
    <div class="position-relative z-10 w-100" style="max-width:28rem;">
        
        <!-- Logo & Title -->
        <div class="text-center mb-4">
            <h1 class="display-6 fw-bold text-white mb-2">Sewa Apartmen.id</h1>
            <p class="text-white-50">Login ke akun Anda</p>
        </div>

        <!-- Login Form -->
        <div class="glass-effect p-4">

            <?php if ($login_success): ?>
                <div class="alert alert-success"><?= $login_success ?></div>
            <?php endif; ?>

            <?php if ($login_error): ?>
                <div class="alert alert-danger"><?= $login_error ?></div>
            <?php endif; ?>

            <form id="loginForm" method="POST" action="login-proses.php">

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label fw-medium">
                        <i class="fas fa-envelope me-2"></i>Email
                    </label>
                    <input 
                        type="email"
                        class="form-control form-control-lg input-focus"
                        id="email"
                        name="email"
                        placeholder="Masukkan email Anda"
                        required
                    >
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label fw-medium">
                        <i class="fas fa-lock me-2"></i>Password
                    </label>
                    <div class="position-relative">
                        <input 
                            type="password"
                            class="form-control form-control-lg input-focus"
                            id="password"
                            name="password"
                            placeholder="Masukkan password Anda"
                            required
                        >
                        <button 
                            type="button"
                            class="position-absolute top-50 end-0 translate-middle-y me-3 btn btn-link text-muted p-0"
                            id="togglePassword"
                        >
                            <i class="fas fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember Me + Forgot -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="rememberMe">
                        <label class="form-check-label" for="rememberMe">Ingat saya</label>
                    </div>
                    <a href="#" class="text-decoration-none fw-medium text-primary">Lupa password?</a>
                </div>

                <!-- Button -->
                <button type="submit" class="btn btn-primary btn-hover w-100 btn-lg fw-semibold">
                    <i class="fas fa-sign-in-alt me-2"></i>Masuk
                </button>

            </form>

            <!-- Register Link -->
            <p class="mt-4 text-center text-muted small">
                Belum punya akun?
                <a href="register.php" class="text-decoration-none fw-medium text-primary">
                    Daftar sekarang
                </a>
            </p>

        </div>

        <!-- Success Alert -->
        <div id="successMessage" class="alert alert-success mt-3 d-none">
            <i class="fas fa-check-circle me-2"></i>Login berhasil! Mengalihkan...
        </div>

        <!-- Error Alert -->
        <div id="errorMessage" class="alert alert-danger mt-3 d-none">
            <i class="fas fa-exclamation-circle me-2"></i>
            <span id="errorText">Email atau password salah!</span>
        </div>

    </div>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Toggle Password
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput  = document.getElementById('password');
        const eyeIcon        = document.getElementById('eyeIcon');

        togglePassword.addEventListener('click', () => {
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;

            eyeIcon.classList.toggle('fa-eye');
            eyeIcon.classList.toggle('fa-eye-slash');
        });

        // Forgot Password Notice
        document.querySelectorAll('a[href="#"]').forEach(a => {
            a.addEventListener('click', e => {
                if (a.textContent.trim() === 'Lupa password?') {
                    e.preventDefault();
                    alert('Fitur lupa password akan mengirimkan email reset ke alamat Anda.');
                }
            });
        });
    </script>

</body>
</html>
