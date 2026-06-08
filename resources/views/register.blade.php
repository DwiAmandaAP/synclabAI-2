<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Register | Portal Akademik</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow-x: hidden;
        }

        .decoration-circle {
            position: absolute;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(129, 140, 248, 0.15), rgba(192, 132, 252, 0.1));
            pointer-events: none;
            z-index: 0;
        }

        .circle-1 {
            width: 350px;
            height: 350px;
            top: -120px;
            right: -120px;
        }

        .circle-2 {
            width: 250px;
            height: 250px;
            bottom: -80px;
            left: -80px;
        }

        .circle-3 {
            width: 180px;
            height: 180px;
            top: 50%;
            left: 15%;
        }

        .register-container {
            width: 100%;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            position: relative;
            z-index: 1;
        }

        .register-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 32px;
            padding: 40px;
            width: 100%;
            max-width: 500px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            animation: fadeInUp 0.5s ease;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .register-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .logo {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #818cf8, #c084fc);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 10px 25px -5px rgba(129, 140, 248, 0.3);
        }

        .logo i {
            font-size: 32px;
            color: white;
        }

        .register-header h1 {
            font-size: 1.8rem;
            font-weight: 700;
            background: linear-gradient(135deg, #1e293b, #2d3a4b);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
            margin-bottom: 8px;
        }

        .register-header p {
            color: #64748b;
            font-size: 0.9rem;
        }

        .register-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .input-group {
            display: flex;
            align-items: center;
            gap: 12px;
            background: #f8fafc;
            border-radius: 60px;
            padding: 4px 20px 4px 16px;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .input-group:focus-within {
            border-color: #818cf8;
            box-shadow: 0 0 0 3px rgba(129, 140, 248, 0.2);
            background: white;
        }

        .input-icon {
            color: #94a3b8;
            font-size: 1.1rem;
            transition: color 0.3s ease;
        }

        .input-group:focus-within .input-icon {
            color: #818cf8;
        }

        .input-field {
            flex: 1;
            position: relative;
        }

        .input-field input {
            width: 100%;
            padding: 14px 0;
            border: none;
            background: transparent;
            font-size: 0.95rem;
            font-family: 'Inter', sans-serif;
            outline: none;
            color: #1e293b;
        }

        .input-field label {
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 0.95rem;
            pointer-events: none;
            transition: all 0.3s ease;
        }

        .input-field input:focus~label,
        .input-field input:not(:placeholder-shown)~label {
            top: -8px;
            font-size: 0.7rem;
            color: #818cf8;
            background: white;
            padding: 0 4px;
        }

        .toggle-password {
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            font-size: 1rem;
            padding: 4px;
            transition: color 0.3s ease;
        }

        .toggle-password:hover {
            color: #818cf8;
        }

        .register-btn {
            background: linear-gradient(135deg, #818cf8, #c084fc);
            border: none;
            padding: 14px;
            border-radius: 60px;
            color: white;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.3s ease;
            margin-top: 8px;
        }

        .register-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(129, 140, 248, 0.4);
        }

        .register-btn:active {
            transform: translateY(0);
        }

        .login-link {
            text-align: center;
            padding-top: 16px;
            border-top: 1px solid #e2e8f0;
        }

        .login-link p {
            color: #64748b;
            font-size: 0.9rem;
        }

        .login-link a {
            color: #818cf8;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .login-link a:hover {
            color: #c084fc;
            text-decoration: underline;
        }

        .input-group.error {
            border-color: #ef4444;
            animation: shake 0.3s ease;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-5px);
            }

            75% {
                transform: translateX(5px);
            }
        }

        .error-message {
            color: #ef4444;
            font-size: 0.7rem;
            margin-top: 4px;
            padding-left: 48px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .success-message {
            background: #dcfce7;
            color: #16a34a;
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            animation: slideDown 0.3s ease;
        }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #fca5a5;
            color: #dc2626;
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 0.85rem;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            animation: slideDown 0.3s ease;
        }

        .alert-error i.fa-exclamation-circle {
            margin-top: 2px;
            flex-shrink: 0;
        }

        .alert-error div {
            flex: 1;
        }

        .alert-error p {
            margin: 0;
            line-height: 1.5;
        }

        .alert-error button {
            background: none;
            border: none;
            color: #dc2626;
            cursor: pointer;
            padding: 0;
            flex-shrink: 0;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 560px) {
            .register-card {
                padding: 32px 24px;
            }

            .register-header h1 {
                font-size: 1.5rem;
            }

            .logo {
                width: 60px;
                height: 60px;
            }

            .logo i {
                font-size: 28px;
            }

            .input-group {
                padding: 4px 16px 4px 12px;
            }
        }

        @media (max-width: 420px) {
            .register-card {
                padding: 24px 20px;
            }

            .register-btn {
                padding: 12px;
                font-size: 0.9rem;
            }

            .error-message {
                padding-left: 40px;
                font-size: 0.65rem;
            }
        }

        .input-field input:focus {
            animation: pulse 0.3s ease;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.01);
            }

            100% {
                transform: scale(1);
            }
        }
    </style>
    <div class="register-container">
        <div class="register-card">
            <div class="register-header">
                <div class="logo">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h1>Daftar Akun</h1>
                <p>Buat akun baru untuk mengakses portal</p>
            </div>

            <form id="registerForm" class="register-form" action="{{ route('register.store') }}" method="POST">
            @csrf
            @if ($errors->any())
                <div class="alert-error" id="serverErrorAlert">
                    <i class="fas fa-exclamation-circle"></i>
                    <div>
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                    <button type="button" onclick="document.getElementById('serverErrorAlert').remove()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif
                <div class="input-group">
                    <div class="input-icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="input-field">
                        <input type="text" id="nama" name="nama" placeholder=" " required>
                        <label for="nama">Nama Lengkap</label>
                    </div>
                </div>

                <div class="input-group">
                    <div class="input-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="input-field">
                        <input type="email" id="email" name="email" placeholder=" " required>
                        <label for="email">Email</label>
                    </div>
                </div>

                <div class="input-group">
                    <div class="input-icon">
                        <i class="fas fa-id-card"></i>
                    </div>
                    <div class="input-field">
                        <input type="text" id="nimNip" name="nomor_induk" placeholder=" " required>
                        <label for="nimNip">NIM / NIP</label>
                    </div>
                </div>

                <div class="input-group">
                    <div class="input-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                    <div class="input-field">
                        <input type="password" id="password" name="password" placeholder=" " required>
                        <label for="password">Password</label>
                        <button type="button" class="toggle-password" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="input-group">
                    <div class="input-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                    <div class="input-field">
                        <input type="password" id="confirmPassword" name="password_confirmation" placeholder=" " required>
                        <label for="confirmPassword">Konfirmasi Password</label>
                        <button type="button" class="toggle-password" id="toggleConfirmPassword">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="register-btn">
                    <i class="fas fa-user-plus"></i> Register
                </button>

                <div class="login-link">
                    <p>Sudah punya akun? <a href="{{ route('login') }}">Sign In</a></p>
                </div>
            </form>
        </div>

        <div class="decoration-circle circle-1"></div>
        <div class="decoration-circle circle-2"></div>
        <div class="decoration-circle circle-3"></div>
    </div>

    <script>
        (function() {
            // Toggle Password Visibility for Password field
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');

            if (togglePassword && passwordInput) {
                togglePassword.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    const icon = this.querySelector('i');
                    icon.classList.toggle('fa-eye');
                    icon.classList.toggle('fa-eye-slash');
                });
            }

            const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
            const confirmPasswordInput = document.getElementById('confirmPassword');

            if (toggleConfirmPassword && confirmPasswordInput) {
                toggleConfirmPassword.addEventListener('click', function() {
                    const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    confirmPasswordInput.setAttribute('type', type);
                    const icon = this.querySelector('i');
                    icon.classList.toggle('fa-eye');
                    icon.classList.toggle('fa-eye-slash');
                });
            }

            const registerForm = document.getElementById('registerForm');
            const namaInput = document.getElementById('nama');
            const emailInput = document.getElementById('email');
            const nimNipInput = document.getElementById('nimNip');

            function showError(inputGroup, message) {
                const existingError = inputGroup.parentElement.querySelector('.error-message');
                if (existingError) existingError.remove();

                inputGroup.classList.add('error');

                const errorDiv = document.createElement('div');
                errorDiv.className = 'error-message';
                errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
                inputGroup.parentElement.insertBefore(errorDiv, inputGroup.nextSibling);

                setTimeout(() => {
                    inputGroup.classList.remove('error');
                    const err = inputGroup.parentElement.querySelector('.error-message');
                    if (err) err.remove();
                }, 3000);
            }

            function removeError(inputGroup) {
                inputGroup.classList.remove('error');
                const err = inputGroup.parentElement.querySelector('.error-message');
                if (err) err.remove();
            }

            function validateNama(nama) {
                return nama.trim().length >= 3;
            }

            function validateEmail(email) {
                const re = /^[^\s@]+@([^\s@.,]+\.)+[^\s@.,]{2,}$/;
                return re.test(email);
            }

            function validateNimNip(nimNip) {
                return /^[0-9]{8,15}$/.test(nimNip);
            }

            function validatePassword(password) {
                return password.length >= 8;
            }

            function validateConfirmPassword(password, confirmPassword) {
                return password === confirmPassword;
            }

            function showSuccessMessage(message) {
                const existingSuccess = document.querySelector('.success-message');
                if (existingSuccess) existingSuccess.remove();

                const successDiv = document.createElement('div');
                successDiv.className = 'success-message';
                successDiv.innerHTML = `<i class="fas fa-check-circle"></i> ${message}`;

                const form = document.querySelector('.register-form');
                form.insertBefore(successDiv, form.firstChild);

                setTimeout(() => {
                    successDiv.remove();
                }, 4000);
            }

            function handleRegister(e) {
                e.preventDefault();
                const nama = namaInput.value.trim();
                const email = emailInput.value.trim();
                const nimNip = nimNipInput.value.trim();
                const password = passwordInput.value;
                const confirmPassword = confirmPasswordInput.value;

                const allInputGroups = document.querySelectorAll('.input-group');
                allInputGroups.forEach(group => {
                    group.classList.remove('error');
                });
                document.querySelectorAll('.error-message').forEach(err => err.remove());

                let hasError = false;

                if (!nama) {
                    showError(namaInput.closest('.input-group'), 'Nama lengkap harus diisi');
                    hasError = true;
                } else if (!validateNama(nama)) {
                    showError(namaInput.closest('.input-group'), 'Nama minimal 3 karakter');
                    hasError = true;
                }

                if (!email) {
                    showError(emailInput.closest('.input-group'), 'Email harus diisi');
                    hasError = true;
                } else if (!validateEmail(email)) {
                    showError(emailInput.closest('.input-group'), 'Email tidak valid');
                    hasError = true;
                }

                if (!nimNip) {
                    showError(nimNipInput.closest('.input-group'), 'NIM/NIP harus diisi');
                    hasError = true;
                } else if (!validateNimNip(nimNip)) {
                    showError(nimNipInput.closest('.input-group'), 'NIM/NIP harus berupa angka 8-15 digit');
                    hasError = true;
                }

                if (!password) {
                    showError(passwordInput.closest('.input-group'), 'Password harus diisi');
                    hasError = true;
                } else if (!validatePassword(password)) {
                    showError(passwordInput.closest('.input-group'), 'Password minimal 8 karakter');
                    hasError = true;
                }

                if (!confirmPassword) {
                    showError(confirmPasswordInput.closest('.input-group'), 'Konfirmasi password harus diisi');
                    hasError = true;
                } else if (!validateConfirmPassword(password, confirmPassword)) {
                    showError(confirmPasswordInput.closest('.input-group'), 'Password tidak cocok');
                    hasError = true;
                }

                if (hasError) return;
                registerForm.submit();
            }

            if (registerForm) {
                registerForm.addEventListener('submit', handleRegister);
            }

            function addRealTimeValidation(input, validationFn, errorMessage) {
                input.addEventListener('blur', function() {
                    const value = this.value.trim();
                    const inputGroup = this.closest('.input-group');

                    if (value && !validationFn(value)) {
                        showError(inputGroup, errorMessage);
                    } else if (value && validationFn(value)) {
                        removeError(inputGroup);
                    }
                });

                input.addEventListener('input', function() {
                    const inputGroup = this.closest('.input-group');
                    removeError(inputGroup);
                });
            }

            if (namaInput) {
                addRealTimeValidation(namaInput, validateNama, 'Nama minimal 3 karakter');
            }

            if (emailInput) {
                addRealTimeValidation(emailInput, validateEmail, 'Email tidak valid');
            }

            if (nimNipInput) {
                addRealTimeValidation(nimNipInput, validateNimNip, 'NIM/NIP harus berupa angka 8-15 digit');
            }

            if (passwordInput && confirmPasswordInput) {
                function checkPasswordMatch() {
                    const password = passwordInput.value;
                    const confirmPassword = confirmPasswordInput.value;
                    const confirmGroup = confirmPasswordInput.closest('.input-group');

                    if (confirmPassword && password !== confirmPassword) {
                        showError(confirmGroup, 'Password tidak cocok');
                    } else if (confirmPassword && password === confirmPassword) {
                        removeError(confirmGroup);
                    }
                }

                passwordInput.addEventListener('input', checkPasswordMatch);
                confirmPasswordInput.addEventListener('input', checkPasswordMatch);
            }
        })();
    </script>
</body>

</html>
