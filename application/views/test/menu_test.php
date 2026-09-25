<?php
// Set active menu for sidebar highlight
$active_menu = 'dashboard';
// Include header with navbar and sidebar
require_once APPPATH . 'views/layout/header.php';
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item"><a href="<?php echo base_url('dashboard'); ?>">Home</a></li>
        <li class="breadcrumb-item active">Menu Test</li>
    </ol>
</nav>

<div class="row">
    <!-- Dynamic Menu Example -->
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-list"></i> Dynamic Menu for Role ID: <?php echo (int)$role_id; ?></h5>
            </div>
            <div class="card-body">
                <p><strong>User:</strong> <?php echo htmlspecialchars($username); ?></p>
                <p><strong>Role ID:</strong> <?php echo (int)$role_id; ?></p>
                
                <?php if (!empty($menu_hierarchy)): ?>
                    <h6 class="mt-3 mb-2">Available Menus:</h6>
                    <div style="background-color: #f8f9fa; padding: 1rem; border-radius: 4px;">
                        <?php foreach ($menu_hierarchy as $main_menu => $submenus): ?>
                            <div style="margin-bottom: 1rem;">
                                <strong style="color: #667eea; font-size: 1.05rem;">📁 <?php echo htmlspecialchars($main_menu); ?></strong>
                                <ul style="margin-top: 0.5rem; padding-left: 2rem;">
                                    <?php foreach ($submenus as $submenu): ?>
                                        <li style="margin-bottom: 0.25rem;">
                                            <a href="<?php echo htmlspecialchars($submenu['NAVIGATION']); ?>" 
                                               style="color: #764ba2; text-decoration: none;">
                                                📄 <?php echo htmlspecialchars($submenu['MENUTEXT']); ?>
                                            </a>
                                            <br>
                                            <small style="color: #999;">Role: <?php echo htmlspecialchars($submenu['ROLE_ID']); ?> | 
                                            Order: <?php echo (int)$submenu['ORDER_NO']; ?> | 
                                            Link: <code><?php echo htmlspecialchars($submenu['NAVIGATION']); ?></code></small>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i> No menus available for Role ID <?php echo (int)$role_id; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Top Menus List -->
<div class="row mt-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-menu-button-wide"></i> Top-Level Menus</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($top_menus)): ?>
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Main Menu</th>
                                <th>Available</th>
                                <th>Submenus Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($top_menus as $menu): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($menu['MAIN_MENU']); ?></strong></td>
                                    <td><span class="badge bg-success">YES</span></td>
                                    <td><?php echo isset($menu_hierarchy[$menu['MAIN_MENU']]) ? count($menu_hierarchy[$menu['MAIN_MENU']]) : 0; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-warning">No top-level menus available for this role.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Test the Dynamic Menu Navbar -->
<div class="row mt-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-gear"></i> Dynamic Menu Navbar</h5>
            </div>
            <div class="card-body">
                <p style="margin-bottom: 1rem;">Below is how the dynamic menu will appear in your application:</p>
                <?php $this->load->view('layout/dynamic_menu', array('role_id' => $role_id, 'username' => $username)); ?>
            </div>
        </div>
    </div>
</div>

<!-- Testing Info -->
<div class="row mt-3">
    <div class="col-md-12">
        <div class="card bg-light">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-info-circle"></i> How to Use Dynamic Menu</h5>
            </div>
            <div class="card-body">
                <h6>1. In Your Controller:</h6>
                <pre class="code-block">$this->load->model('Menu_model', 'menu_model');
$menus = $this->menu_model->get_top_menus($_SESSION['user_role']);
$data['menus'] = $menus;</pre>

                <h6 class="mt-3">2. In Your View:</h6>
                <pre class="code-block">&lt;?php $this->load->view('layout/dynamic_menu', array(
    'role_id' => $_SESSION['user_role'],
    'username' => $_SESSION['user_name']
)); ?&gt;</pre>

                <h6 class="mt-3">3. Via Helper Function:</h6>
                <pre class="code-block">$this->load->helper('menu_helper');
load_user_menu(); // Loads menu automatically</pre>

                <h6 class="mt-3">4. Get Menu Data as Array:</h6>
                <pre class="code-block">$menus = get_user_menus($_SESSION['user_role']);</pre>

                <h6 class="mt-3">5. Check Access to Menu Item:</h6>
                <pre class="code-block">if (check_menu_access($_SESSION['user_role'], $menu_id)) {
    // User has access
}</pre>

                <h6 class="mt-3">6. AJAX Endpoints:</h6>
                <ul>
                    <li><code>/test_menu/get_menus_json</code> - Get all menus as JSON</li>
                    <li><code>/test_menu/get_submenus/Dashboard</code> - Get submenus for a main menu</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php require_once APPPATH . 'views/layout/footer.php'; ?>
