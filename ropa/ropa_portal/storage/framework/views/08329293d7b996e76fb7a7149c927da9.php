<?php $__env->startSection('title', 'Page Not Found'); ?>

<?php $__env->startSection('content'); ?>
<div class="error-page-wrap">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-xl-6 text-center">

                <div class="error-card">
                    <?php echo $__env->make('errors.partials.error-illustration', ['code' => '404', 'stampColor' => 'var(--accent)', 'stampDark' => 'var(--accent-dark)'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                    <p class="error-eyebrow">Record not found</p>
                    <h1 class="error-heading">This page isn't on file.</h1>
                    <p class="error-body">
                        The page you're looking for may have been moved, renamed, or never existed.
                        Check the link, or head back to your dashboard to find what you need.
                    </p>

                    <div class="error-actions">
                        <a href="<?php echo e(url('/')); ?>" class="btn btn-accent btn-lg">
                            <i class="fas fa-home me-2"></i> Back to Dashboard
                        </a>
                        <button onclick="history.back()" class="btn btn-outline-primary btn-lg">
                            <i class="fas fa-arrow-left me-2"></i> Go Back
                        </button>
                    </div>

                    <p class="error-code-tag">Error 404 &middot; Not Found</p>
                </div>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<?php echo $__env->make('errors.partials.error-page-styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Clement Aryee\Documents\UG Projects\UG_ROPA_Form_Portal\ropa_portal\resources\views/errors/404.blade.php ENDPATH**/ ?>