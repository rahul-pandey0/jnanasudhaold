<?php
/**
 * Dynamic Menu Component - Loads based on User Role
 * Usage: $this->load->view('layout/dynamic_menu', array('role_id' => $role_id));
 */

$role_id = isset($role_id) ? $role_id : (isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null);
$username = isset($username) ? $username : (isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest');
$active_menu = isset($active_menu) ? $active_menu : '';

if (!$role_id) {
    echo '<div class="alert alert-warning">User role not set. Cannot load menu.</div>';
    return;
}

$this->load->model('Menu_model', 'menu_model');

// Get top-level menus for this role
$top_menus = $this->menu_model->get_top_menus($role_id);
?>

<!-- Dynamic Navigation Menu based on Role -->
<nav class="navbar navbar-expand-lg navbar-dark" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?php echo base_url(); ?>">
            <i class="bi bi-house"></i> Home
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#dynamicNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="dynamicNavbar">
            <ul class="navbar-nav me-auto">
                <?php if (!empty($top_menus)): ?>
                    <?php foreach ($top_menus as $menu): ?>
                        <?php 
                        $main_menu_name = $menu['MAIN_MENU'];
                        $submenus = $this->menu_model->get_submenus($main_menu_name, $role_id, $username);
                        $has_submenus = !empty($submenus);
                        ?>
                        
                        <?php if ($has_submenus): ?>
                            <!-- Menu with Dropdown -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="menu<?php echo md5($main_menu_name); ?>" 
                                   role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-list"></i> <?php echo htmlspecialchars($main_menu_name); ?>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="menu<?php echo md5($main_menu_name); ?>">
                                    <?php foreach ($submenus as $submenu): ?>
                                        <li>
                                            <a class="dropdown-item" href="<?php echo htmlspecialchars($submenu['NAVIGATION']); ?>" 
                                               title="<?php echo htmlspecialchars($submenu['MENUTEXT']); ?>">
                                                <i class="bi bi-arrow-right"></i> <?php echo htmlspecialchars($submenu['MENUTEXT']); ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li class="nav-item">
                        <span class="nav-link text-warning">No menus available for your role</span>
                    </li>
                <?php endif; ?>
            </ul>
            
            <!-- User Menu on Right -->
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userMenu" role="button" 
                       data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($username); ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                        <li><a class="dropdown-item" href="<?php echo base_url('home'); ?>">
                            <i class="bi bi-house"></i> Home
                        </a></li>
                        <li><a class="dropdown-item" href="<?php echo base_url('users'); ?>">
                            <i class="bi bi-people"></i> Users
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?php echo base_url('auth/logout'); ?>">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<style>
.navbar {
    padding: 0.5rem 1rem;
    min-height: auto;
}
.navbar-brand {
    font-size: 1rem;
    font-weight: 600;
    margin-right: 2rem;
}
.nav-link {
    font-size: 0.9rem;
    padding: 0.5rem 0.75rem !important;
    color: rgba(255, 255, 255, 0.9) !important;
}
.nav-link:hover {
    color: white !important;
}
.dropdown-menu {
    font-size: 0.85rem;
    background-color: #f8f9fa;
}
.dropdown-item {
    padding: 0.5rem 1rem;
    color: #333;
}
.dropdown-item:hover {
    background-color: #e9ecef;
    color: #667eea;
}
.dropdown-item i {
    margin-right: 0.5rem;
    color: #667eea;
}
</style>

