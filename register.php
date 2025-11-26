<?php
// File: register.php
session_start();

// Ambil error dari session jika ada
$register_errors = $_SESSION["register_errors"] ?? [];
$register_data = $_SESSION["register_data"] ?? [];

// Hapus error dan data dari session
unset($_SESSION["register_errors"], $_SESSION["register_data"]);

// Set default value dari data yang sudah diisi sebelumnya
$name = $register_data["name"] ?? "";
$email = $register_data["email"] ?? "";
$phone = $register_data["phone"] ?? "";

// Inisialisasi variable error kosong
$name_err = $register_errors["name"] ?? "";
$email_err = $register_errors["email"] ?? "";
$phone_err = $register_errors["phone"] ?? "";
$password_err = $register_errors["password"] ?? "";
$confirm_password_err = $register_errors["confirm_password"] ?? "";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi | Sewa Apartmen.id</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-radius: 1rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        
        .input-focus {
            transition: all 0.3s ease;
        }
        
        .input-focus:focus {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(102, 126, 234, 0.2);
        }
        
        .btn-hover {
            transition: all 0.3s ease;
        }
        
        .btn-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        }
        
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
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
            filter: blur(0px);
            opacity: 0.7;
        }
        
        .form-control:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 0.25rem rgba(99, 102, 241, 0.25);
        }
        
        .btn-primary {
            background-color: #6366f1;
            border-color: #6366f1;
        }
        
        .btn-primary:hover {
            background-color: #4f46e5;
            border-color: #4f46e5;
        }
        
        .error-message {
            color: #dc3545;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        
        .password-strength {
            height: 5px;
            margin-top: 0.5rem;
            border-radius: 5px;
            transition: all 0.3s ease;
        }
        
        .strength-weak { background-color: #dc3545; width: 33%; }
        .strength-medium { background-color: #ffc107; width: 66%; }
        .strength-strong { background-color: #28a745; width: 100%; }
    </style>
</head>
<body>
    <!-- Background Decorations -->
    <div class="bg-decoration">
        <div class="bubble float-animation" style="width: 20rem; height: 20rem; background-color: #c084fc; top: -10rem; right: -10rem;"></div>
        <div class="bubble float-animation" style="width: 20rem; height: 20rem; background-color: #a78bfa; bottom: -10rem; left: -10rem; animation-delay: 2s;"></div>
        <div class="bubble float-animation" style="width: 20rem; height: 20rem; background-color: #f9a8d4; top: 50%; left: 50%; transform: translate(-50%, -50%); animation-delay: 4s;"></div>
    </div>

    <!-- Registration Container -->
    <div class="position-relative z-10 w-100" style="max-width: 32rem;">
        <!-- Logo and Title -->
        <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center justify-content-center bg-white rounded-circle shadow-lg mb-3" style="width: 5rem; height: 5rem;">
                <i class="fas fa-building text-indigo fs-2"></i>
            </div>
            <h1 class="display-6 fw-bold text-white mb-2">Sewa Apartmen.id</h1>
            <p class="text-white-50">Buat akun baru</p>
        </div>

        <!-- Registration Form -->
        <div class="glass-effect p-4">
            <?php if (!empty($register_errors) && isset($register_errors["general"])): ?>
                <div class="alert alert-danger mb-4" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i><?php echo $register_errors["general"]; ?>
                </div>
            <?php endif; ?>
            
            <form id="registerForm" method="POST" action="auth/register-proses.php">
                <!-- Name Input -->
                <div class="mb-3">
                    <label for="username" class="form-label fw-medium">
                        <i class="fas fa-user me-2 text-indigo"></i>Username
                    </label>
                    <input 
                        type="text" 
                        class="form-control form-control-lg input-focus <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" 
                        id="username" 
                        name="username"
                        value="<?php echo $name; ?>"
                        placeholder="Masukkan username Anda"
                        required
                    >
                    <?php if (!empty($name_err)): ?>
                        <div class="error-message"><?php echo $name_err; ?></div>
                    <?php endif; ?>
                </div>

                <!-- Email Input -->
                <div class="mb-3">
                    <label for="email" class="form-label fw-medium">
                        <i class="fas fa-envelope me-2 text-indigo"></i>Email
                    </label>
                    <input 
                        type="email" 
                        class="form-control form-control-lg input-focus <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" 
                        id="email" 
                        name="email"
                        value="<?php echo $email; ?>"
                        placeholder="Masukkan email Anda"
                        required
                    >
                    <?php if (!empty($email_err)): ?>
                        <div class="error-message"><?php echo $email_err; ?></div>
                    <?php endif; ?>
                </div>

                <!-- Full Name Input -->
                <div class="mb-3">
                    <label for="full_name" class="form-label fw-medium">
                        <i class="fas fa-user me-2 text-indigo"></i>Nama lengkap
                    </label>
                    <input 
                        type="text" 
                        class="form-control form-control-lg input-focus <?php echo (!empty($phone_err)) ? 'is-invalid' : ''; ?>" 
                        id="full_name" 
                        name="full_name"
                        value="<?php echo $full_name; ?>"
                        placeholder="Masukkan nama lengkap Anda"
                        required
                    >
                    <?php if (!empty($phone_err)): ?>
                        <div class="error-message"><?php echo $phone_err; ?></div>
                    <?php endif; ?>
                </div>

                <!-- Password Input -->
                <div class="mb-3">
                    <label for="password" class="form-label fw-medium">
                        <i class="fas fa-lock me-2 text-indigo"></i>Password
                    </label>
                    <div class="position-relative">
                        <input 
                            type="password" 
                            class="form-control form-control-lg input-focus <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" 
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
                    <div class="password-strength" id="passwordStrength"></div>
                    <?php if (!empty($password_err)): ?>
                        <div class="error-message"><?php echo $password_err; ?></div>
                    <?php endif; ?>
                </div>

                <!-- Confirm Password Input -->
                <div class="mb-4">
                    <label for="confirm_password" class="form-label fw-medium">
                        <i class="fas fa-lock me-2 text-indigo"></i>Konfirmasi Password
                    </label>
                    <div class="position-relative">
                        <input 
                            type="password" 
                            class="form-control form-control-lg input-focus <?php echo (!empty($password_confirm_err)) ? 'is-invalid' : ''; ?>" 
                            id="password_confirm" 
                            name="password_confirm"
                            placeholder="Konfirmasi password Anda"
                            required
                        >
                        <button 
                            type="button" 
                            class="position-absolute top-50 end-0 translate-middle-y me-3 btn btn-link text-muted p-0"
                            id="toggleConfirmPassword"
                        >
                            <i class="fas fa-eye" id="eyeIconConfirm"></i>
                        </button>
                    </div>
                    <?php if (!empty($password_confirm_err)): ?>
                        <div class="error-message"><?php echo $password_confirm_err; ?></div>
                    <?php endif; ?>
                </div>

                <!-- Register Button -->
                <button 
                    type="submit"
                    class="btn btn-primary btn-hover w-100 btn-lg fw-semibold">
                    <i class="fas fa-user-plus me-2"></i>Daftar Sekarang
                </button>
            </form>

            <!-- Login Link -->
            <p class="mt-4 text-center text-muted small">
                Sudah punya akun? 
                <a href="index.php" class="text-decoration-none text-indigo fw-medium">Masuk di sini</a>
            </p>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Toggle Password Visibility
        function setupPasswordToggle(toggleId, inputId, iconId) {
            const toggle = document.getElementById(toggleId);
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);

            toggle.addEventListener('click', function() {
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                
                if (type === 'password') {
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                } else {
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                }
            });
        }

        setupPasswordToggle('togglePassword', 'password', 'eyeIcon');
        setupPasswordToggle('toggleConfirmPassword', 'confirm_password', 'eyeIconConfirm');

        // Password Strength Indicator
        const passwordInput = document.getElementById('password');
        const passwordStrength = document.getElementById('passwordStrength');

        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;

            if (password.length >= 6) strength++;
            if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;
            if (password.match(/[^a-zA-Z0-9]/)) strength++;

            passwordStrength.className = 'password-strength';
            
            if (password.length === 0) {
                passwordStrength.style.width = '0';
            } else if (strength <= 1) {
                passwordStrength.classList.add('strength-weak');
            } else if (strength <= 2) {
                passwordStrength.classList.add('strength-medium');
            } else {
                passwordStrength.classList.add('strength-strong');
            }
        });

        // Real-time password confirmation check
        const confirmPasswordInput = document.getElementById('confirm_password');
        
        confirmPasswordInput.addEventListener('input', function() {
            if (this.value !== passwordInput.value) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });
    </script>
</body>
</html>