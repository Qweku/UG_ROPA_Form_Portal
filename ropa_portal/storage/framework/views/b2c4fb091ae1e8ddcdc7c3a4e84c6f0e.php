<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Verify OTP - RoPA Portal</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link href="<?php echo e(asset('assets/css/ropa.css')); ?>" rel="stylesheet">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo e(asset('assets/apple-touch-icon.png')); ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo e(asset('assets/favicon-32x32.png')); ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo e(asset('assets/favicon-16x16.png')); ?>">
    <link rel="manifest" href="<?php echo e(asset('assets/site.webmanifest')); ?>">

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

        /* Animated Background */
        .shape {
            position: absolute;
            border-radius: 50%;
            background: rgba(182, 153, 100, 0.1);
            animation: float 20s infinite ease-in-out;
        }

        .shape-1 { width: 300px; height: 300px; top: -100px; right: -100px; }
        .shape-2 { width: 200px; height: 200px; bottom: 100px; left: -80px; animation-delay: 5s; }
        .shape-3 { width: 150px; height: 150px; bottom: 200px; right: 50px; animation-delay: 10s; }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }

        .verification-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            z-index: 1;
            padding: 2rem;
        }

        .verification-card {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 32px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            width: 100%;
            max-width: 480px;
            animation: slideUp 0.5s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .verification-header {
            background: linear-gradient(135deg, #153d6f 0%, #1a4a82 100%);
            padding: 2rem;
            text-align: center;
        }

        .otp-icon {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .otp-icon i {
            font-size: 2.5rem;
            color: #b69964;
        }

        .verification-header h2 {
            color: white;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .verification-header p {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.875rem;
        }

        .verification-body {
            padding: 2rem;
        }

        /* OTP Input Styles */
        .otp-container {
            display: flex;
            gap: 12px;
            justify-content: center;
            margin: 2rem 0;
        }

        .otp-input {
            width: 60px;
            height: 70px;
            text-align: center;
            font-size: 1.8rem;
            font-weight: 600;
            border: 2px solid #e9ecef;
            border-radius: 16px;
            transition: all 0.3s ease;
            background: white;
        }

        .otp-input:focus {
            border-color: #b69964;
            outline: none;
            box-shadow: 0 0 0 4px rgba(182, 153, 100, 0.1);
            transform: translateY(-2px);
        }

        .otp-input.error {
            border-color: #dc3545;
            animation: shake 0.3s ease;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        /* Timer Styles */
        .timer {
            text-align: center;
            margin: 1rem 0;
            font-size: 0.9rem;
        }

        .timer-value {
            font-weight: 700;
            color: #b69964;
            font-size: 1.1rem;
        }

        .resend-btn {
            background: none;
            border: none;
            color: #b69964;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .resend-btn:hover:not(:disabled) {
            color: #9e7d4a;
            text-decoration: underline;
        }

        .resend-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .btn-verify {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #153d6f 0%, #1a4a82 100%);
            color: white;
            border: none;
            border-radius: 16px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }

        .btn-verify:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(21, 61, 111, 0.3);
        }

        .btn-verify:active {
            transform: translateY(0);
        }

        .alert-custom {
            border-radius: 16px;
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

        .info-text {
            text-align: center;
            color: #6c757d;
            font-size: 0.85rem;
            margin-top: 1rem;
        }

        .info-text i {
            color: #b69964;
            margin-right: 5px;
        }

        @media (max-width: 576px) {
            .otp-input {
                width: 45px;
                height: 55px;
                font-size: 1.3rem;
            }

            .verification-body {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>
    <div class="shape shape-3"></div>

    <div class="verification-container">
        <div class="verification-card">
            <div class="verification-header">
                <div class="otp-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <h2>Verify Your Email</h2>
                <p>Please enter the 6-digit code sent to <strong><?php echo e(Auth::user()->email); ?></strong></p>
            </div>

            <div class="verification-body">
                <div id="alertContainer"></div>

                <form id="otpForm" method="POST" action="<?php echo e(route('verify.otp')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="otp-container">
                        <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric" autofocus>
                        <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric">
                        <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric">
                        <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric">
                        <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric">
                        <input type="text" class="otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric">
                    </div>
                    <input type="hidden" name="otp" id="otpValue">

                    <div class="timer" id="timerContainer">
                        <i class="fas fa-clock me-1"></i>
                        Code expires in: <span class="timer-value" id="timer">10:00</span>
                    </div>

                    <div class="text-center mb-3">
                        <button type="button" class="resend-btn" id="resendBtn" onclick="resendOtp()">
                            <i class="fas fa-redo-alt me-1"></i> Resend Code
                        </button>
                    </div>

                    <button type="submit" class="btn-verify" id="verifyBtn">
                        <i class="fas fa-check-circle me-2"></i> Verify & Continue
                    </button>
                </form>

                <div class="info-text">
                    <i class="fas fa-info-circle"></i> Didn't receive the code? Check your spam folder or click resend.
                </div>
            </div>
        </div>
    </div>

    <script>
        const otpInputs = document.querySelectorAll('.otp-input');
        const form = document.getElementById('otpForm');
        const verifyBtn = document.getElementById('verifyBtn');
        const resendBtn = document.getElementById('resendBtn');
        let timerInterval;
        let timeLeft = 600; // 10 minutes in seconds

        // Auto-focus and handle OTP input
        otpInputs.forEach((input, index) => {
            input.addEventListener('input', (e) => {
                if (e.target.value.length === 1 && index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }
                updateOtpValue();
            });

            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && index > 0 && !e.target.value) {
                    otpInputs[index - 1].focus();
                }
            });

            // Allow only numbers
            input.addEventListener('keypress', (e) => {
                if (!/^\d$/.test(e.key)) {
                    e.preventDefault();
                }
            });
        });

        function updateOtpValue() {
            let otp = '';
            otpInputs.forEach(input => {
                otp += input.value;
            });
            document.getElementById('otpValue').value = otp;
        }

        function startTimer() {
            timerInterval = setInterval(() => {
                if (timeLeft <= 0) {
                    clearInterval(timerInterval);
                    document.getElementById('timer').innerText = 'Expired';
                    resendBtn.disabled = false;
                    showAlert('Your OTP has expired. Please request a new one.', 'error');
                } else {
                    timeLeft--;
                    const minutes = Math.floor(timeLeft / 60);
                    const seconds = timeLeft % 60;
                    document.getElementById('timer').innerText = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                }
            }, 1000);
        }

        function showAlert(message, type) {
            const alertContainer = document.getElementById('alertContainer');
            const alertClass = type === 'error' ? 'alert-error' : 'alert-success';
            alertContainer.innerHTML = `
                <div class="alert-custom ${alertClass}">
                    <i class="fas fa-${type === 'error' ? 'exclamation-circle' : 'check-circle'} me-2"></i>
                    ${message}
                </div>
            `;

            setTimeout(() => {
                const alert = alertContainer.querySelector('.alert-custom');
                if (alert) {
                    alert.style.transition = 'opacity 0.5s';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                }
            }, 5000);
        }

        function resendOtp() {
            if (resendBtn.disabled) return;

            resendBtn.disabled = true;
            resendBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Sending...';

            fetch('<?php echo e(route("resend.otp")); ?>', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert(data.message, 'success');
                    // Reset timer
                    clearInterval(timerInterval);
                    timeLeft = 600;
                    startTimer();
                    // Clear OTP inputs
                    otpInputs.forEach(input => input.value = '');
                    otpInputs[0].focus();
                    updateOtpValue();
                } else {
                    showAlert(data.error, 'error');
                    resendBtn.disabled = false;
                }
            })
            .catch(error => {
                showAlert('Failed to resend OTP. Please try again.', 'error');
                resendBtn.disabled = false;
            })
            .finally(() => {
                setTimeout(() => {
                    if (resendBtn.innerHTML !== '<i class="fas fa-redo-alt me-1"></i> Resend Code') {
                        resendBtn.innerHTML = '<i class="fas fa-redo-alt me-1"></i> Resend Code';
                    }
                }, 2000);
            });
        }

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const otp = document.getElementById('otpValue').value;
            if (otp.length !== 6) {
                showAlert('Please enter the complete 6-digit OTP', 'error');
                otpInputs.forEach(input => {
                    if (!input.value) input.classList.add('error');
                });
                return;
            }

            verifyBtn.disabled = true;
            verifyBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Verifying...';

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ otp: otp })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert(data.message, 'success');
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 1500);
                } else {
                    showAlert(data.error, 'error');
                    verifyBtn.disabled = false;
                    verifyBtn.innerHTML = '<i class="fas fa-check-circle me-2"></i> Verify & Continue';
                    otpInputs.forEach(input => input.classList.add('error'));
                    setTimeout(() => {
                        otpInputs.forEach(input => input.classList.remove('error'));
                    }, 1000);
                }
            })
            .catch(error => {
                showAlert('Verification failed. Please try again.', 'error');
                verifyBtn.disabled = false;
                verifyBtn.innerHTML = '<i class="fas fa-check-circle me-2"></i> Verify & Continue';
            });
        });

        // Start timer on page load
        startTimer();

        // Pre-fill OTP for development (remove in production)
        const isLocalEnv = "<?php echo e(config('app.env') === 'local' ? 'true' : 'false'); ?>" === 'true';
        if (isLocalEnv) {
            console.log('Development mode: You can use OTP from logs');
        }
    </script>
</body>
</html>
<?php /**PATH C:\Users\Clement Aryee\Documents\UG Projects\UG_ROPA_Form_Portal\ropa_portal\resources\views/auth/verify-otp.blade.php ENDPATH**/ ?>