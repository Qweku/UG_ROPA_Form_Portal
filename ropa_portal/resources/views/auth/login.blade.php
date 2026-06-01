<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Welcome to RoPA Portal - University of Ghana</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #153d6f 0%, #0e2d52 100%);
            position: relative;
            overflow-x: hidden;
        }

        /* Animated Background Shapes */
        .shape {
            position: absolute;
            border-radius: 50%;
            background: rgba(182, 153, 100, 0.1);
            animation: float 20s infinite ease-in-out;
            z-index: 0;
        }

        .shape-1 {
            width: 300px;
            height: 300px;
            top: -100px;
            right: -100px;
            animation-delay: 0s;
        }

        .shape-2 {
            width: 200px;
            height: 200px;
            bottom: 100px;
            left: -80px;
            animation-delay: 5s;
        }

        .shape-3 {
            width: 150px;
            height: 150px;
            bottom: 200px;
            right: 50px;
            animation-delay: 10s;
        }

        .shape-4 {
            width: 400px;
            height: 400px;
            top: 50%;
            left: -200px;
            animation-delay: 2s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(5deg);
            }
        }

        /* Main Container */
        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 1;
            padding: 2rem;
        }

        /* Card Container */
        .auth-card {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 32px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            width: 100%;
            max-width: 480px;
            backdrop-filter: blur(10px);
            transition: transform 0.3s ease;
        }

        .auth-card:hover {
            transform: translateY(-5px);
        }

        /* Header Section */
        .auth-header {
            background: linear-gradient(135deg, #153d6f 0%, #1a4a82 100%);
            padding: 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .auth-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(182, 153, 100, 0.1) 0%, transparent 70%);
            animation: pulse 8s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
                opacity: 0.5;
            }

            50% {
                transform: scale(1.1);
                opacity: 0.8;
            }
        }

        .logo-icon {
            width: 70px;
            height: 70px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            animation: bounce 2s infinite;
        }

        @keyframes bounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        .logo-icon i {
            font-size: 2.5rem;
            color: #b69964;
        }

        .auth-header h2 {
            color: white;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .auth-header p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.875rem;
        }

        /* Form Container */
        .form-container {
            padding: 2rem;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .form-group input {
            width: 100%;
            padding: 12px 16px 12px 48px;
            border: 2px solid #e9ecef;
            border-radius: 16px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: white;
        }

        .form-group input:focus {
            border-color: #b69964;
            outline: none;
            box-shadow: 0 0 0 4px rgba(182, 153, 100, 0.1);
        }

        .form-group input.error {
            border-color: #dc3545;
        }

        .form-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #b69964;
            font-size: 1.1rem;
            pointer-events: none;
        }

        /* Password Toggle */
        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #adb5bd;
            transition: color 0.3s ease;
            z-index: 10;
        }

        .password-toggle:hover {
            color: #b69964;
        }

        /* Submit Button */
        .btn-submit {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #153d6f 0%, #1a4a82 100%);
            color: white;
            border: none;
            border-radius: 16px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-submit::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-submit:hover::before {
            left: 100%;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(21, 61, 111, 0.3);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        /* Switch Link */
        .switch-link {
            text-align: center;
            margin-top: 1.5rem;
            color: #6c757d;
            font-size: 0.9rem;
        }

        .switch-link a {
            color: #b69964;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .switch-link a:hover {
            color: #9e7d4a;
            text-decoration: underline;
        }

        /* Form Transition */
        .form-pane {
            transition: all 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            opacity: 1;
            transform: translateX(0);
        }

        .form-pane.hide {
            display: none;
            opacity: 0;
            transform: translateX(50px);
        }

        /* Alert Messages */
        .alert-custom {
            border-radius: 16px;
            border: none;
            padding: 1rem;
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }

        /* Loading State */
        .btn-submit.loading {
            pointer-events: none;
            opacity: 0.7;
        }

        .btn-submit.loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            top: 50%;
            right: 20px;
            transform: translateY(-50%);
            border: 2px solid white;
            border-top-color: transparent;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }

        @keyframes spin {
            to {
                transform: translateY(-50%) rotate(360deg);
            }
        }

        /* Responsive */
        @media (max-width: 576px) {
            .auth-container {
                padding: 1rem;
            }

            .form-container {
                padding: 1.5rem;
            }

            .auth-header {
                padding: 1.5rem;
            }

            .logo-icon {
                width: 50px;
                height: 50px;
            }

            .logo-icon i {
                font-size: 1.8rem;
            }
        }

        /* Floating Labels Animation */
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

        .shake {
            animation: shake 0.3s ease-in-out;
        }

        .checkbox-compact {
            width: 1rem;
            height: 1rem;
            min-width: 1rem;
            max-width: 1rem;
            border-radius: 0.25rem;
            flex-shrink: 0;
            accent-color: #153d6f;
            cursor: pointer;
            border: 1px solid #adb5bd;
        }
    </style>
</head>

<body>
    <!-- Animated Background Shapes -->
    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>
    <div class="shape shape-3"></div>
    <div class="shape shape-4"></div>

    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <div class="logo-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h2>Welcome to RoPA Portal</h2>
                <p>University of Ghana - Data Protection Framework</p>
            </div>

            <div class="form-container">
                <!-- Error Messages -->
                @if($errors->any())
                <div class="alert-custom alert-error" id="errorMessage">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                    @endforeach
                </div>
                @endif

                <!-- Login Form -->
                <div id="loginForm" class="form-pane">
                    <form method="POST" action="{{ route('login') }}" id="loginFormElement">
                        @csrf
                        <div class="form-group">
                            <i class="fas fa-envelope form-icon"></i>
                            <input type="email" name="email" id="loginEmail" placeholder="Email Address" required autocomplete="email" autofocus>
                        </div>

                        <div class="form-group">
                            <i class="fas fa-lock form-icon"></i>
                            <input type="password" name="password" id="loginPassword" placeholder="Password" required>
                            <i class="fas fa-eye password-toggle" onclick="togglePassword('loginPassword', this)"></i>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="remember" id="remember">
                                <label class="form-check-label" for="remember" style="font-size: 0.875rem;">
                                    Remember me
                                </label>
                            </div>
                            <a href="#" style="color: #b69964; text-decoration: none; font-size: 0.875rem;">
                                Forgot Password?
                            </a>
                        </div>

                        <button type="submit" class="btn-submit" id="loginBtn">
                            <i class="fas fa-sign-in-alt me-2"></i> Sign In
                        </button>
                    </form>

                    <div class="switch-link">
                        Don't have an account?
                        <a onclick="switchToSignup()">Create Account <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>

                <!-- Signup Form - Updated with firstname and surname -->
                <div id="signupForm" class="form-pane" style="display: none;">
                    <form method="POST" action="{{ route('register') }}" id="signupFormElement">
                        @csrf

                        <div class="form-group">
                            <i class="fas fa-user form-icon"></i>
                            <input type="text" name="firstname" id="signupFirstname" placeholder="First Name" required>
                        </div>

                        <div class="form-group">
                            <i class="fas fa-user form-icon"></i>
                            <input type="text" name="surname" id="signupSurname" placeholder="Surname" required>
                        </div>

                        <div class="form-group">
                            <i class="fas fa-envelope form-icon"></i>
                            <input type="email" name="email" id="signupEmail" placeholder="Email Address" required>
                        </div>

                        <div class="form-group">
                            <i class="fas fa-id-card form-icon"></i>
                            <input type="text" name="personnel_id" id="signupPersonnelId" placeholder="Personnel ID (Optional)">
                        </div>

                        <div class="form-group">
                            <i class="fas fa-lock form-icon"></i>
                            <input type="password" name="password" id="signupPassword" placeholder="Password" required>
                            <i class="fas fa-eye password-toggle" onclick="togglePassword('signupPassword', this)"></i>
                        </div>

                        <div class="form-group">
                            <i class="fas fa-lock form-icon"></i>
                            <input type="password" name="password_confirmation" id="signupPasswordConfirm" placeholder="Confirm Password" required>
                            <i class="fas fa-eye password-toggle" onclick="togglePassword('signupPasswordConfirm', this)"></i>
                        </div>

                        <div class="mb-4">
                            <div class="form-check d-flex align-items-center">
                                <input type="checkbox" class="form-check-input checkbox-compact me-3" name="terms" id="terms" required>
                                <label class="form-check-label" for="terms" style="font-size: 0.875rem;">
                                    I agree to the <a href="#" style="color: #b69964;">Terms of Service</a> and <a href="#" style="color: #b69964;">Privacy Policy</a>
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn-submit" id="signupBtn">
                            <i class="fas fa-user-plus me-2"></i> Create Account
                        </button>
                    </form>

                    <div class="switch-link">
                        Already have an account?
                        <a onclick="switchToLogin()">Sign In <i class="fas fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle between Login and Signup forms with smooth transition
        function switchToSignup() {
            const loginPane = document.getElementById('loginForm');
            const signupPane = document.getElementById('signupForm');

            loginPane.style.animation = 'slideOutLeft 0.3s ease forwards';
            setTimeout(() => {
                loginPane.style.display = 'none';
                signupPane.style.display = 'block';
                signupPane.style.animation = 'slideInRight 0.5s ease forwards';
            }, 150);
        }

        function switchToLogin() {
            const loginPane = document.getElementById('loginForm');
            const signupPane = document.getElementById('signupForm');

            signupPane.style.animation = 'slideOutRight 0.3s ease forwards';
            setTimeout(() => {
                signupPane.style.display = 'none';
                loginPane.style.display = 'block';
                loginPane.style.animation = 'slideInLeft 0.5s ease forwards';
            }, 150);
        }

        // Add animation keyframes
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideOutLeft {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(-50px);
                    opacity: 0;
                }
            }

            @keyframes slideInRight {
                from {
                    transform: translateX(50px);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }

            @keyframes slideOutRight {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(50px);
                    opacity: 0;
                }
            }

            @keyframes slideInLeft {
                from {
                    transform: translateX(-50px);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
        `;
        document.head.appendChild(style);

        // Toggle password visibility
        function togglePassword(fieldId, element) {
            const field = document.getElementById(fieldId);
            if (field.type === 'password') {
                field.type = 'text';
                element.classList.remove('fa-eye');
                element.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                element.classList.remove('fa-eye-slash');
                element.classList.add('fa-eye');
            }
        }

        // Form validation with shake animation
        document.getElementById('loginFormElement')?.addEventListener('submit', function(e) {
            const email = document.getElementById('loginEmail');
            const password = document.getElementById('loginPassword');

            if (!email.value.trim() || !password.value.trim()) {
                e.preventDefault();
                showError('Please fill in all fields');
                if (!email.value.trim()) shakeElement(email);
                if (!password.value.trim()) shakeElement(password);
            } else {
                const btn = document.getElementById('loginBtn');
                btn.classList.add('loading');
                btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Signing in...';
            }
        });

        // Updated validation for signup form
        document.getElementById('signupFormElement')?.addEventListener('submit', function(e) {
            const firstname = document.getElementById('signupFirstname');
            const surname = document.getElementById('signupSurname');
            const email = document.getElementById('signupEmail');
            const password = document.getElementById('signupPassword');
            const confirm = document.getElementById('signupPasswordConfirm');
            const terms = document.getElementById('terms');

            let hasError = false;

            if (!firstname.value.trim()) {
                shakeElement(firstname);
                hasError = true;
            }
            if (!surname.value.trim()) {
                shakeElement(surname);
                hasError = true;
            }
            if (!email.value.trim()) {
                shakeElement(email);
                hasError = true;
            }
            if (!password.value.trim()) {
                shakeElement(password);
                hasError = true;
            }
            if (!confirm.value.trim()) {
                shakeElement(confirm);
                hasError = true;
            }
            if (password.value !== confirm.value) {
                showError('Passwords do not match');
                shakeElement(password);
                shakeElement(confirm);
                e.preventDefault();
                return;
            }
            if (!terms.checked) {
                showError('Please accept the Terms of Service');
                e.preventDefault();
                return;
            }

            if (hasError) {
                e.preventDefault();
                showError('Please fill in all required fields');
            } else {
                const btn = document.getElementById('signupBtn');
                btn.classList.add('loading');
                btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Creating account...';
            }
        });

        function shakeElement(element) {
            element.classList.add('shake');
            setTimeout(() => {
                element.classList.remove('shake');
            }, 300);
            element.style.borderColor = '#dc3545';
            element.addEventListener('input', function() {
                this.style.borderColor = '';
            }, {
                once: true
            });
        }

        function showError(message) {
            let errorDiv = document.querySelector('.alert-custom');
            if (!errorDiv) {
                errorDiv = document.createElement('div');
                errorDiv.className = 'alert-custom alert-error';
                const formContainer = document.querySelector('.form-container');
                formContainer.insertBefore(errorDiv, formContainer.firstChild);
            }
            errorDiv.innerHTML = `<i class="fas fa-exclamation-circle me-2"></i> ${message}`;
            setTimeout(() => {
                errorDiv.style.opacity = '0';
                setTimeout(() => {
                    if (errorDiv) errorDiv.remove();
                }, 300);
            }, 3000);
        }

        // Auto-hide error messages after 5 seconds
        setTimeout(() => {
            const errorMsg = document.getElementById('errorMessage');
            if (errorMsg) {
                errorMsg.style.transition = 'opacity 0.5s ease';
                errorMsg.style.opacity = '0';
                setTimeout(() => errorMsg.remove(), 500);
            }
        }, 5000);

        // Smooth input animations
        document.querySelectorAll('.form-group input').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'translateX(5px)';
            });
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'translateX(0)';
            });
        });

        // Demo credentials hint (remove in production)
        console.log('Demo credentials: admin@ug.edu.gh / password');
    </script>
</body>

</html>
