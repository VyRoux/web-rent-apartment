<?php
// File: register.php
session_start();

// Ambil error dari session jika ada
 $register_errors = $_SESSION["register_errors"] ?? [];
 $register_data = $_SESSION["register_data"] ?? [];

// Hapus error dan data dari session
unset($_SESSION["register_errors"], $_SESSION["register_data"]);

// Set default value dari data yang sudah diisi sebelumnya
 $username = $register_data["username"] ?? "";
 $email = $register_data["email"] ?? "";
 $full_name = $register_data["full_name"] ?? "";

// Inisialisasi variable error kosong
 $username_err = $register_errors["username"] ?? "";
 $email_err = $register_errors["email"] ?? "";
 $full_name_err = $register_errors["full_name"] ?? "";
 $password_err = $register_errors["password"] ?? "";
 $confirm_password_err = $register_errors["password_confirm"] ?? "";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi | Sewa Apartmen.id</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --bs-primary: #6366f1;
            --bs-primary-hover: #4f46e5;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .register-container {
            max-width: 480px;
            width: 100%;
            animation: fadeIn 0.5s ease-in-out;
        }

        .card {
            border: none;
            border-radius: 16px;
            background: rgba(0, 0, 0, 0.65);
            backdrop-filter: blur(15px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }

        .card-body {
            padding: 2.5rem;
        }

        .form-label, .card-body h4, .card-body p {
            color: #f8f9fa !important;
        }

        .form-control, .form-control:focus {
            border-radius: 10px;
            border: 1px solid #ced4da;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            background-color: rgba(255, 255, 255, 0.9);
            color: #212529;
        }
        
        .form-control:focus {
            border-color: var(--bs-primary);
            box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.25);
            background-color: #fff;
        }

        .btn-primary {
            background-color: var(--bs-primary);
            border-color: var(--bs-primary);
            border-radius: 10px;
            padding: 0.75rem;
            font-weight: 600;
            transition: all 0.3s ease-in-out;
        }

        .btn-primary:hover {
            background-color: var(--bs-primary-hover);
            border-color: var(--bs-primary-hover);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(99, 102, 241, 0.4);
        }
        
        .error-message {
            color: #f8d7da;
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
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="register-container p-3">
        <div class="card">
            <div class="card-body">
                <!-- Logo & Title -->
                <div class="text-center mb-4">
                    <h4 class="fw-bold text-white">Buat Akun Baru</h4>
                    <p class="text-white-50">Daftar untuk mulai menyewa apartemen impian Anda</p>
                </div>

                <!-- Menampilkan Pesan Error Umum -->
                <?php if (!empty($register_errors) && isset($register_errors["general"])): ?>
                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                        <i class="fas fa-exclamation-triangle-fill me-2"></i>
                        <div><?php echo $register_errors["general"]; ?></div>
                    </div>
                <?php endif; ?>
                
                <form id="registerForm" method="POST" action="auth/register-proses.php">
                    <!-- Username Input -->
                    <div class="mb-3">
                        <label for="username" class="form-label fw-medium">
                            <i class="fas fa-user me-2"></i>Username
                        </label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input 
                                type="text" 
                                class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" 
                                id="username" 
                                name="username"
                                value="<?php echo htmlspecialchars($username); ?>"
                                placeholder="Masukkan username Anda"
                                required
                            >
                        </div>
                        <?php if (!empty($username_err)): ?>
                            <div class="error-message"><?php echo $username_err; ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Email Input -->
                    <div class="mb-3">
                        <label for="email" class="form-label fw-medium">
                            <i class="fas fa-envelope me-2"></i>Email
                        </label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input 
                                type="email" 
                                class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" 
                                id="email" 
                                name="email"
                                value="<?php echo htmlspecialchars($email); ?>"
                                placeholder="Masukkan email Anda"
                                required
                            >
                        </div>
                        <?php if (!empty($email_err)): ?>
                            <div class="error-message"><?php echo $email_err; ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Full Name Input -->
                    <div class="mb-3">
                        <label for="full_name" class="form-label fw-medium">
                            <i class="fas fa-id-card me-2"></i>Nama Lengkap
                        </label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                            <input 
                                type="text" 
                                class="form-control <?php echo (!empty($full_name_err)) ? 'is-invalid' : ''; ?>" 
                                id="full_name" 
                                name="full_name"
                                value="<?php echo htmlspecialchars($full_name); ?>"
                                placeholder="Masukkan nama lengkap Anda"
                                required
                            >
                        </div>
                        <?php if (!empty($full_name_err)): ?>
                            <div class="error-message"><?php echo $full_name_err; ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Password Input -->
                    <div class="mb-3">
                        <label for="password" class="form-label fw-medium">
                            <i class="fas fa-lock me-2"></i>Password
                        </label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input 
                                type="password"
                                class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" 
                                id="password" 
                                name="password"
                                placeholder="Masukkan password Anda"
                                required
                            >
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
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
                        <label for="password_confirm" class="form-label fw-medium">
                            <i class="fas fa-lock me-2"></i>Konfirmasi Password
                        </label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input 
                                type="password"
                                class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" 
                                id="password_confirm" 
                                name="password_confirm"
                                placeholder="Masukkan ulang password Anda"
                                required
                            >
                            <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                                <i class="fas fa-eye" id="eyeIconConfirm"></i>
                            </button>
                        </div>
                        <?php if (!empty($confirm_password_err)): ?>
                            <div class="error-message"><?php echo $confirm_password_err; ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Register Button -->
                    <button 
                        type="submit"
                        class="btn btn-primary w-100 btn-lg fw-semibold"
                    >
                        <i class="fas fa-user-plus me-2"></i>Daftar Sekarang
                    </button>
                </form>

                <!-- Login Link -->
                <p class="mt-4 text-center text-white-50 mb-0">
                    Sudah punya akun? 
                    <a href="index.php" class="text-decoration-none fw-medium">Masuk di sini</a>
                </p>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
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
        setupPasswordToggle('toggleConfirmPassword', 'password_confirm', 'eyeIconConfirm');

        // Password Strength Indicator
        const passwordInput = document.getElementById('password');
        const passwordStrength = document.getElementById('passwordStrength');

        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;

            if (password.length >= 8) strength++;
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
        const confirmPasswordInput = document.getElementById('password_confirm');
        
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