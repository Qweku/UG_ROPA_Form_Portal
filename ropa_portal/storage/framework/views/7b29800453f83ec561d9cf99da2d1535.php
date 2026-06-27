<?php $__env->startSection('title', 'Something Went Wrong'); ?>

<?php $__env->startSection('content'); ?>
<div class="error-page-wrap">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-xl-6 text-center">

                <div class="error-card">
                    <?php echo $__env->make('errors.partials.error-illustration', ['code' => '500', 'stampColor' => 'var(--primary)', 'stampDark' => 'var(--primary-dark)'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                    <p class="error-eyebrow">System error</p>
                    <h1 class="error-heading">Something broke on our end.</h1>
                    <p class="error-body">
                        We hit an unexpected error processing your request. Nothing on your end caused this &mdash;
                        try again in a moment, and if it keeps happening, let your administrator know.
                    </p>

                    <div class="error-actions">
                        <button onclick="location.reload()" class="btn btn-accent btn-lg">
                            <i class="fas fa-redo me-2"></i> Try Again
                        </button>
                        <a href="<?php echo e(url('/')); ?>" class="btn btn-outline-primary btn-lg">
                            <i class="fas fa-home me-2"></i> Back to Dashboard
                        </a>
                    </div>

                    <p class="error-code-tag">Error 500 &middot; Internal Server Error</p>
                </div>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<?php echo $__env->make('errors.partials.error-page-styles', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Clement Aryee\Documents\UG Projects\UG_ROPA_Form_Portal\ropa_portal\resources\views/errors/500.blade.php ENDPATH**/ ?>