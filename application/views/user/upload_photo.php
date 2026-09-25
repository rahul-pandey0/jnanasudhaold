<?php
$active_menu = 'users';
require_once APPPATH . 'views/layout/header.php';
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-3">
        <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Home</a></li>
        <li class="breadcrumb-item"><a href="<?php echo base_url('users'); ?>">Users</a></li>
        <li class="breadcrumb-item active"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></li>
    </ol>
</nav>

<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="bi bi-camera me-2"></i>Upload Profile Photo</h5>
    </div>
    <div class="card-body">
        <?php if (!empty($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form action="" method="post" enctype="multipart/form-data">
            <div class="row align-items-center mb-4">
                <?php if (!empty($user['profile_photo'])): ?>
                    <div class="col-md-3 text-center">
                        <label class="form-label fw-bold">Current Photo:</label>
                        <div class="border p-2 rounded">
                            <img src="<?php echo base_url('uploads/profile_photos/' . $user['profile_photo']); ?>" 
                                 alt="Profile Photo" 
                                 class="img-fluid rounded" 
                                 style="max-height: 150px; object-fit: cover;">
                        </div>
                    </div>
                <?php endif; ?>

                <div class="col-md-6">
                    <label for="profile_photo" class="form-label fw-bold">Choose Photo:</label>
                    <?php if (empty($user['profile_photo'])): ?>
                        <input type="file" name="profile_photo" id="profile_photo" class="form-control" required>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            Profile photo already exists.  
                            Please delete the photo to upload a new one.
                        </div>
                    <?php endif; ?>

                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success"><i class="bi bi-check-circle me-1"></i> Save</button>
                 <a href="<?php echo base_url('users/delete_profile_photo/' . $user['user_id']); ?>" class="btn btn-danger"><i class="bi bi-trash me-1"></i> Delete Image</a>
                 <a href="<?php echo base_url('users/view/' . $user['user_id']); ?>" class="btn btn-secondary"><i class="bi bi-x-circle me-1"></i> Cancel</a>
               
            </div>
        </form>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('profile_photo');
    const hasPhoto = <?php echo !empty($user['profile_photo']) ? 'true' : 'false'; ?>;

    fileInput.addEventListener('click', function(e) {
        if (hasPhoto) {
            e.preventDefault(); 
            alert('Profile photo already exists for this user!');
        }
    });
});
</script>


<?php require_once APPPATH . 'views/layout/footer.php'; ?>