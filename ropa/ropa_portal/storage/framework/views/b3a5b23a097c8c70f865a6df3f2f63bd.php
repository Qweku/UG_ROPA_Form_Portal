<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'RoPA - University of Ghana'); ?></title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <!-- Custom CSS -->
    <link href="<?php echo e(asset('assets/css/ropa.css')); ?>" rel="stylesheet">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo e(asset('assets/apple-touch-icon.png')); ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo e(asset('assets/favicon-32x32.png')); ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo e(asset('assets/favicon-16x16.png')); ?>">
    <link rel="manifest" href="<?php echo e(asset('assets/site.webmanifest')); ?>">



    <?php echo $__env->yieldPushContent('styles'); ?>
</head>

<body>
    <div class="loading-overlay" id="loadingOverlay">
        <div class="spinner-border spinner-border-custom" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg sticky-top" style="background: linear-gradient(135deg, #153d6f 0%, #0e2d52 100%); box-shadow: 0 4px 20px rgba(0,0,0,0.15);">
        <div class="container">
            <!-- Brand Logo with Animation -->
            <a class="navbar-brand" href="<?php echo e(route('ropa.index')); ?>" style="transition: transform 0.3s ease;">
                <div class="d-flex align-items-center">
                    <div class="brand-icon me-2" style="background: rgba(255,255,255,0.15); border-radius: 12px; padding: 4px 6px; transition: all 0.3s ease;">
                        <img src="<?php echo e(asset('assets/ug_logo.png')); ?>" alt="UG Logo" style="width: 60px; height: 60px; object-fit: contain;">

                    <!-- <i class="fas fa-shield-alt" style="color: #b69964; font-size: 1.3rem;"></i> -->
                    </div>
                    <div>
                        <span style="font-weight: 700; font-size: 1.3rem; letter-spacing: -0.5px; background: linear-gradient(135deg, #ffffff 0%, #e8eef5 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                            RoPA Portal
                        </span>
                        <small class="d-block" style="font-size: 0.65rem; color: rgba(255,255,255,0.7); letter-spacing: 0.5px;">
                            UNIVERSITY OF GHANA
                        </small>
                    </div>
                </div>
            </a>

            <!-- Mobile Toggle Button -->
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                style="background: rgba(255,255,255,0.1); padding: 10px 12px; border-radius: 12px; transition: all 0.3s ease;">
                <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
            </button>

            <!-- Navigation Menu -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">


                    <?php if(auth()->guard()->check()): ?>
                    <!-- User Dropdown with Avatar -->
                    <li class="nav-item dropdown ms-2">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false"
                            style="color: white; border-radius: 40px; padding: 5px 15px 5px 8px; transition: all 0.3s ease; background: rgba(255,255,255,0.08);">
                            <div class="user-avatar me-2"
                                style="width: 32px; height: 32px; background: linear-gradient(135deg, #b69964 0%, #9e7d4a 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-user" style="font-size: 0.9rem; color: white;"></i>
                            </div>
                            <span style="font-weight: 500;"><?php echo e(Auth::user()->full_name ?? Auth::user()->firstname . ' ' . Auth::user()->surname); ?></span>
                            <i class="fas fa-chevron-down ms-2" style="font-size: 0.8rem; transition: transform 0.3s ease;"></i>
                        </a>

                        <!-- Enhanced Dropdown Menu -->
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-2"
                            style="border-radius: 16px; overflow: hidden; animation: slideDown 0.3s ease;">
                            <li class="px-3 pt-3 pb-2" style="background: linear-gradient(135deg, #153d6f 0%, #0e2d52 100%);">
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar-large me-3"
                                        style="width: 48px; height: 48px; background: linear-gradient(135deg, #b69964 0%, #9e7d4a 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-user fa-lg" style="color: white;"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 text-white"><?php echo e(Auth::user()->name); ?></h6>
                                        <small class="text-white-50"><?php echo e(Auth::user()->email ?? 'user@ug.edu.gh'); ?></small>
                                    </div>
                                </div>
                            </li>

                            <li class="mt-2">
                                <a class="dropdown-item py-2" href="#" style="transition: all 0.3s ease;">
                                    <i class="fas fa-user-circle me-3" style="color: #b69964; width: 20px;"></i>
                                    <span>My Profile</span>
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item py-2" href="#" style="transition: all 0.3s ease;">
                                    <i class="fas fa-bell me-3" style="color: #b69964; width: 20px;"></i>
                                    <span>Notifications</span>
                                    <span class="badge bg-danger ms-2" style="border-radius: 20px;">3</span>
                                </a>
                            </li>

                            <li>
                                <hr class="dropdown-divider my-1">
                            </li>
                            <li>
                                <form method="POST" action="<?php echo e(url('/logout')); ?>">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="dropdown-item py-2 text-danger" style="transition: all 0.3s ease;">
                                        <i class="fas fa-sign-out-alt me-3" style="color: #dc3545; width: 20px;"></i>
                                        <span>Logout</span>
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                    <?php endif; ?>

                    <?php if(auth()->guard()->guest()): ?>
                    <!-- Login Button for Guests -->
                    <li class="nav-item ms-2">
                        <a class="nav-link" href="<?php echo e(route('login')); ?>"
                            style="background: rgba(255,255,255,0.1); color: white; border-radius: 12px; padding: 10px 20px; transition: all 0.3s ease; font-weight: 500;">
                            <i class="fas fa-sign-in-alt me-2"></i>
                            <span>Login</span>
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>



    <main class="py-4">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <footer class="footer">
        <div class="container text-center">
            <p class="mb-2">
                <i class="fas fa-shield-alt me-2"></i>
                <strong>University of Ghana - Data Protection and Privacy Framework</strong>
            </p>
            <p class="mb-0 small">
                &copy; <?php echo e(date('Y')); ?> Record of Processing Activities (RoPA) |
                <i class="fas fa-lock me-1"></i> Compliant with Data Protection Act 2018
            </p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        AOS.init({
            duration: 800,
            once: true
        });

        // Show loading overlay on form submit
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function() {
                // Skip for final RoPA submission (handled specially)
                const focusedSubmit = form.querySelector('button[type="submit"][name="action"]:focus');
                if (form.id === 'ropaForm' && focusedSubmit && focusedSubmit.value === 'submit') {
                    return;
                }

                // Skip for delete forms - the index page handles showing the overlay
                // at the correct time (after dismissing the confirmation modal)
                if (form.classList.contains('delete-form')) {
                    return;
                }

                document.getElementById('loadingOverlay').style.display = 'flex';
            });
        });

        // Initialize Select2
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });
        });

        // Toast notification helper
        function showToast(message, type = 'success') {
            const toastHtml = `
                <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999">
                    <div class="toast align-items-center text-white bg-${type} border-0" role="alert">
                        <div class="d-flex">
                            <div class="toast-body">${message}</div>
                            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                        </div>
                    </div>
                </div>
            `;
            $('body').append(toastHtml);
            const toast = new bootstrap.Toast($('.toast').last()[0]);
            toast.show();
            setTimeout(() => $('.toast').last().remove(), 3000);
        }
    </script>
    <script>
        // Add active class to current nav item based on URL
        document.addEventListener('DOMContentLoaded', function() {
            const currentUrl = window.location.href;
            const navLinks = document.querySelectorAll('.navbar .nav-link');

            navLinks.forEach(link => {
                if (currentUrl.includes(link.getAttribute('href'))) {
                    link.classList.add('active');
                }
            });

            // Rotate dropdown chevron on toggle
            const dropdownToggle = document.querySelector('#userDropdown');
            if (dropdownToggle) {
                dropdownToggle.addEventListener('click', function() {
                    const chevron = this.querySelector('.fa-chevron-down');
                    if (chevron) {
                        chevron.style.transform = chevron.style.transform === 'rotate(180deg)' ? 'rotate(0deg)' : 'rotate(180deg)';
                    }
                });
            }
        });
    </script>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>

</html>
<?php /**PATH C:\Users\Clement Aryee\Documents\UG Projects\UG_ROPA_Form_Portal\ropa_portal\resources\views/layouts/app.blade.php ENDPATH**/ ?>