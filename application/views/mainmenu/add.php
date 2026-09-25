<?php
$active_menu = 'Main_menu';
$title = 'Add Menu';

require_once APPPATH . 'views/layout/header.php';
?>

<style>
.menu-form .form-group {
    margin-bottom: 15px;
}
.menu-form label {
    margin-bottom: 5px;
    display: block;
    font-weight: 600;
}
</style>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
            <a href="<?php echo base_url('dashboard'); ?>">Home</a>
        </li>
        <li class="breadcrumb-item active">Add Menu</li>
    </ol>
</nav>

<?php if (!empty($_SESSION['success'])): ?>
<div class="alert alert-success alert-dismissible fade show">
    <?php echo $_SESSION['success']; ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php unset($_SESSION['success']); endif; ?>

<?php if (!empty($_SESSION['error'])): ?>
<div class="alert alert-danger alert-dismissible fade show">
    <?php echo $_SESSION['error']; ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php unset($_SESSION['error']); endif; ?>

<?php if (!empty($errors)): ?>
<div class="alert alert-danger">
    <ul>
        <?php foreach ($errors as $err): ?>
            <li><?php echo $err; ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<div class="row mt-4">
  <div class="col-md-6">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Add Menu</h5>
         <!-- <a href="<?php echo base_url('Main_menu/add_new_menu'); ?>" class="btn btn-success btn-sm">+ New</a>-->
      </div>


<div class="card-body">
<form method="post" class="menu-form">
<!-- ROLE -->
   <div class="form-group">
        <label>Role</label>
        <select name="role_id" class="form-control" required>
            <option value="">Select Role</option>

            <?php if (!empty($roles)): ?>
                <?php foreach ($roles as $r): ?>
                    <option value="<?php echo $r['role_id']; ?>"
                        <?php echo (($_POST['role_id'] ?? '') == $r['role_id']) ? 'selected' : ''; ?>>
                        <?php echo $r['role_name']; ?> 
                    </option>
                <?php endforeach; ?>
            <?php endif; ?>

        </select>
    </div>

  <!--  <div class="form-group" style="position:relative;">
    <label>Main Menu</label>
    <input type="text" name="main_menu" id="main_menu_input" class="form-control" placeholder="Select or type main menu" autocomplete="off" required>
    <ul id="main_menu_list" class="dropdown-list" 
        style="
            border:1px solid #ccc; 
            display:none; 
            max-height:200px; 
            overflow-y:auto; 
            position:absolute; 
            background:#fff; 
            z-index:1000; 
            list-style:none; 
            padding:0; 
            margin:0;
            width:100%;" 
        >
        <?php foreach ($main_menu as $n): ?>
            <li class="dropdown-item" 
                style="padding:8px 12px; cursor:pointer; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                <?php echo $n['MAIN_MENU']; ?>
            </li>
        <?php endforeach; ?>
    </ul>
</div>-->
 <label>Main Menu</label>
<select name="main_menu" class="form-control">
    <option value="">Select Main Menu</option>
    <?php foreach ($main_menu as $row): ?>
        <option value="<?php echo htmlspecialchars($row['main_menu']); ?>">
            <?php echo htmlspecialchars($row['main_menu']); ?>
        </option>
    <?php endforeach; ?>
</select>

<!-- MENU TEXT -->
<div class="form-group">
    <label>Menu Text</label>
    <select name="screen_id" class="form-control" required>
        <option value="">Select Menu Text</option>

        <?php if (!empty($screen_name)): ?>
            <?php foreach ($screen_name as $screen): ?>
                <option value="<?php echo $screen['screen_id']; ?>"
                    <?php echo (($_POST['screen_id'] ?? '') == $screen['screen_id']) ? 'selected' : ''; ?>>
                    <?php echo $screen['screen_name']; ?>
                </option>
            <?php endforeach; ?>
        <?php endif; ?>

    </select>
</div>
<!-- NAVIGATION  -->
    <div class="form-group">
        <label>Navigation</label>
        <input type="text" name="navigation" class="form-control" value="<?php echo $_POST['navigation'] ?? ''; ?>"placeholder="Enter navigation URL"required>
    </div>


<!-- AVAILABLE -->
    <div class="form-group">
        <label>Available</label>
        <select name="available" class="form-control">
            <option value="Yes" <?php echo (($_POST['available'] ?? '') == 'Yes') ? 'selected' : ''; ?>>Yes</option>
            <option value="No" <?php echo (($_POST['available'] ?? '') == 'No') ? 'selected' : ''; ?>>No</option>
        </select>
    </div>

    <!-- ORDER -->
    <div class="form-group">
        <label>Order No</label>
        <input type="number"name="order_no"class="form-control" value="<?php echo $_POST['order_no'] ?? ''; ?>" required>
    </div>

    <div class="text-center mt-3">
        <button type="submit" class="btn btn-primary">Save</button>
        <a href="<?php echo base_url('Main_menu/index'); ?>" class="btn btn-danger">Cancel</a>
    </div>

</form>
</div>
</div>
</div>
</div>
<script>
    
    const screenLinks = {
        <?php if (!empty($screen_name)): ?>
            <?php foreach ($screen_name as $screen): ?>
                "<?php echo $screen['screen_id']; ?>": "<?php echo $screen['link']; ?>",
            <?php endforeach; ?>
        <?php endif; ?>
    };

    
    document.addEventListener('DOMContentLoaded', function() {
        const screenDropdown = document.querySelector('select[name="screen_id"]');
        const navigationInput = document.querySelector('input[name="navigation"]');

        screenDropdown.addEventListener('change', function() {
            const selectedId = this.value;
            if (selectedId && screenLinks[selectedId]) {
                navigationInput.value = screenLinks[selectedId];
            } else {
                navigationInput.value = '';
            }
        });
    });
</script>
<!--<script>
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('main_menu_input');
    const list = document.getElementById('main_menu_list');
    const items = list.querySelectorAll('li');

    
    input.addEventListener('focus', () => {
        list.style.display = 'block';
    });

   
    input.addEventListener('input', () => {
        const val = input.value.toLowerCase();
        list.style.display = 'block';
        items.forEach(item => {
            if (item.textContent.toLowerCase().includes(val)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    });

    
    items.forEach(item => {
        item.addEventListener('click', () => {
            input.value = item.textContent;
            list.style.display = 'none';
        });
    });

   
    document.addEventListener('click', (e) => {
        if (!input.contains(e.target) && !list.contains(e.target)) {
            list.style.display = 'none';
        }
    });
});
</script>-->



<?php require_once APPPATH . 'views/layout/footer.php'; ?>
