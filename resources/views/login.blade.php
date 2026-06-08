<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Login | Portal Mahasiswa</title>
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
            overflow: hidden;
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
            width: 300px;
            height: 300px;
            top: -100px;
            right: -100px;
        }

        .circle-2 {
            width: 200px;
            height: 200px;
            bottom: -50px;
            left: -50px;
        }

        .circle-3 {
            width: 150px;
            height: 150px;
            top: 40%;
            left: 20%;
        }

        .login-container {
            width: 100%;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            z-index: 1;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 32px;
            padding: 40px;
            width: 100%;
            max-width: 460px;
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

        .login-header {
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

        .login-header h1 {
            font-size: 1.8rem;
            font-weight: 700;
            background: linear-gradient(135deg, #1e293b, #2d3a4b);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
            margin-bottom: 8px;
        }

        .login-header p {
            color: #64748b;
            font-size: 0.9rem;
        }

        .login-form {
            display: flex;
            flex-direction: column;
            gap: 24px;
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

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            font-size: 0.85rem;
            color: #475569;
            position: relative;
        }

        .checkbox-label input {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .checkmark {
            width: 18px;
            height: 18px;
            background: #e2e8f0;
            border-radius: 4px;
            display: inline-block;
            transition: all 0.2s ease;
        }

        .checkbox-label input:checked~.checkmark {
            background: #818cf8;
            position: relative;
        }

        .checkbox-label input:checked~.checkmark::after {
            content: '\f00c';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 10px;
            color: white;
        }

        .checkbox-label span:last-child {
            user-select: none;
        }

        .forgot-password {
            font-size: 0.85rem;
            color: #818cf8;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .forgot-password:hover {
            color: #c084fc;
            text-decoration: underline;
        }

        .login-btn {
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

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(129, 140, 248, 0.4);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .register-link {
            text-align: center;
            padding-top: 16px;
            border-top: 1px solid #e2e8f0;
        }

        .register-link p {
            color: #64748b;
            font-size: 0.9rem;
        }

        .register-link a {
            color: #818cf8;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .register-link a:hover {
            color: #c084fc;
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 32px 24px;
            }

            .login-header h1 {
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

            .form-options {
                flex-direction: column;
                align-items: flex-start;
            }
        }

        @media (max-width: 380px) {
            .login-card {
                padding: 24px 20px;
            }

            .login-btn {
                padding: 12px;
                font-size: 0.9rem;
            }
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

        .input-group.error {
            border-color: #ef4444;
            animation: shake 0.3s ease;
        }

        .error-message {
            color: #ef4444;
            font-size: 0.75rem;
            margin-top: 4px;
            padding-left: 48px;
        }
    </style>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="logo">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h1>SyncLab</h1>
                <p>Silakan masuk ke akun Anda</p>
            </div>

            <form id="loginForm" class="login-form" method="POST" action="{{ route('login') }}">
                @csrf

                @if ($errors->any())
                    {{-- {{ dd($errors) }} --}}
                    @foreach ($errors->all() as $item)
                        <div class="error-message">
                            {{ $item }} <br>
                        </div>
                    @endforeach
                @endif
                <div class="input-group">
                    <div class="input-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="input-field">
                        <input type="email" id="email" placeholder="Email" name="email"
                            value="{{ old('email') }}" required>
                        <label for="email">Email</label>

                    </div>
                </div>

                <div class="input-group">
                    <div class="input-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                    <div class="input-field">
                        <input type="password" id="password" name="password" placeholder="Password" required>
                        <label for="password">Password</label>
                        <button type="button" class="toggle-password" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="form-options">
                    <label class="checkbox-label">
                        <input type="checkbox" id="remember">
                        <span class="checkmark"></span>
                        Ingat saya
                    </label>
                    <a href="#" class="forgot-password">Lupa password?</a>
                </div>

                <button type="submit" class="login-btn">
                    <i class="fas fa-sign-in-alt"></i> Log In
                </button>

                <div class="register-link">
                    <p>Belum punya akun? <a href="{{ route('register') }}">Sign Up</a></p>
                </div>
            </form>
        </div>

        <div class="decoration-circle circle-1"></div>
        <div class="decoration-circle circle-2"></div>
        <div class="decoration-circle circle-3"></div>
    </div>

    <script>
        (function() {
            // Check for registration success flag
            @if(session('register_success'))
                window.onload = function() {
                    alert('Akun berhasil didaftarkan! Silakan login dengan akun Anda.');
                };
            @endif

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

            const loginForm = document.getElementById('loginForm');
            const emailInput = document.getElementById('email');

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

            function handleLogin(e) {
                e.preventDefault();

                const email = emailInput.value.trim();
                const password = passwordInput.value.trim();
                const remember = document.getElementById('remember').checked;

                document.querySelectorAll('.input-group').forEach(group => {
                    group.classList.remove('error');
                });
                document.querySelectorAll('.error-message').forEach(err => err.remove());

                let hasError = false;

                if (!email) {
                    const emailGroup = emailInput.closest('.input-group');
                    showError(emailGroup, 'Email harus diisi');
                    hasError = true;
                } else if (!validateEmail(email)) {
                    const emailGroup = emailInput.closest('.input-group');
                    showError(emailGroup, 'Email tidak valid');
                    hasError = true;
                }

                if (!password) {
                    const passwordGroup = passwordInput.closest('.input-group');
                    showError(passwordGroup, 'Password harus diisi');
                    hasError = true;
                } else if (password.length < 6) {
                    const passwordGroup = passwordInput.closest('.input-group');
                    showError(passwordGroup, 'Password minimal 6 karakter');
                    hasError = true;
                }

                if (hasError) return;
            }

            document.querySelectorAll('.input-field input').forEach(input => {
                if (input.value) {
                    input.dispatchEvent(new Event('input'));
                }
            });
        })();
    </script>
</body>

</html>
