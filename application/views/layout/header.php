<?php
/**
 * Common Header/Navbar and Sidebar for all pages
 */
$user_email = isset($user_email) ? $user_email : (isset($_SESSION['user_email']) ? $_SESSION['user_email'] : 'Admin');
$active_menu = isset($active_menu) ? $active_menu : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'Home'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --bs-body-font-size: 0.75rem;
        }
        * {
            margin: 0;
            padding: 0;
        }
        body {
            font-size: 0.75rem;
            background-color: #f8f9fa;
        }
        .navbar {
            padding: 0.25rem 0.5rem;
            min-height: auto;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        }
        .navbar-brand {
            font-size: 0.9rem;
            font-weight: 600;
            color: white !important;
        }
        .nav-link {
            padding: 0.15rem 0.3rem !important;
            font-size: 0.7rem;
            color: rgba(255, 255, 255, 0.8) !important;
        }
        .nav-link:hover {
            color: white !important;
        }
        .card {
            box-shadow: 0 0.05rem 0.1rem rgba(0, 0, 0, 0.075);
            border: 0.5px solid rgba(0, 0, 0, 0.06);
            margin-bottom: 0.5rem;
        }
        .card-header {
            padding: 0.35rem 0.5rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-bottom: 0.5px solid rgba(0, 0, 0, 0.06);
        }
        .card-header h5, .card-header h6 {
            font-size: 0.75rem;
            margin-bottom: 0;
        }
        .card-body {
            padding: 0.5rem;
        }
        .table {
            font-size: 0.7rem;
            margin-bottom: 0;
        }
        .table th, .table td {
            padding: 0.25rem 0.3rem;
        }
        .table thead th {
            background-color: #e9ecef;
            font-weight: 600;
            border-bottom: 0.5px solid #dee2e6;
        }
        .btn {
            padding: 0.2rem 0.4rem;
            font-size: 0.65rem;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #5568d3 0%, #6a3d8f 100%);
            border: none;
        }
        .btn-sm {
            padding: 0.15rem 0.3rem;
            font-size: 0.6rem;
        }
        .btn-success {
            background-color: #28a745;
            border: none;
        }
        .btn-danger {
            background-color: #dc3545;
            border: none;
        }
        .btn-warning {
            background-color: #ffc107;
            border: none;
            color: #333;
        }
        .form-control, .form-select {
            padding: 0.2rem 0.3rem;
            font-size: 0.75rem;
            height: auto;
        }
        .container-fluid {
            padding: 0.5rem;
        }
        .sidebar {
            background-color: #fff;
            border-right: 0.5px solid #dee2e6;
            padding: 0.3rem;
            min-height: calc(100vh - 35px);
        }
        .sidebar .list-group-item {
            padding: 0.3rem 0.5rem;
            font-size: 0.7rem;
            border: none;
            border-left: 3px solid transparent;
        }
        .sidebar .list-group-item:hover {
            background-color: #f8f9fa;
            border-left-color: #667eea;
        }
        .sidebar .list-group-item.active {
            background-color: #f0f2f7;
            color: #667eea;
            border-left-color: #667eea;
        }
        .main-content {
            padding: 0.5rem;
        }
        .badge {
            padding: 0.2rem 0.3rem;
            font-size: 0.6rem;
        }
        .breadcrumb {
            padding: 0.25rem 0;
            margin-bottom: 0.3rem;
            font-size: 0.7rem;
        }
        .row {
            margin: 0;
        }
        .row > * {
            padding: 0.2rem;
        }
        .alert {
            padding: 0.5rem;
            margin-bottom: 0.5rem;
            font-size: 0.7rem;
        }
        footer {
            padding: 0.3rem 0 !important;
            font-size: 0.65rem;
            margin-top: 0.5rem;
        }
        .user-info {
            font-size: 0.7rem;
            color: rgba(255, 255, 255, 0.9);
        }
        .pagination { margin: 0; }
        .page-link { padding: 0.2rem 0.4rem; font-size: 0.65rem; color: #667eea; border: 1px solid #dee2e6; }
        .page-item.active .page-link { background-color: #667eea; border-color: #667eea; color: white; }
        .page-link:hover { color: #764ba2; border-color: #667eea; }
        .page-item.disabled .page-link { color: #999; cursor: not-allowed; }
        .datatable-info { color: #666; }
        .badge-active { background-color: #28a745; color: white; padding: 0.2rem 0.3rem; border-radius: 3px; font-size: 0.6rem; }
        .badge-inactive { background-color: #dc3545; color: white; padding: 0.2rem 0.3rem; border-radius: 3px; font-size: 0.6rem; }
        .stat-card { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 0.5rem; border-radius: 4px; text-align: center; margin-bottom: 0.3rem; }
        .stat-card h6 { font-size: 0.65rem; margin: 0; }
        .stat-card h3 { font-size: 1.2rem; margin: 0; }
        .search-box { margin-bottom: 0.5rem; }
        .btn-sm {
            padding: 0.15rem 0.3rem;
            font-size: 0.6rem;
        }
        .btn-success {
            background-color: #28a745;
            border: none;
        }
        .btn-danger {
            background-color: #dc3545;
            border: none;
        }
        .btn-warning {
            background-color: #ffc107;
            border: none;
            color: #333;
        }
        .code-block {
            background-color: #f8f9fa;
            padding: 0.5rem;
            border-radius: 4px;
            font-family: monospace;
            font-size: 0.65rem;
            overflow-x: auto;
            border: 0.5px solid #dee2e6;
        }
        .status-success {
            color: #28a745;
            font-weight: 600;
        }
        .status-error {
            color: #dc3545;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <!-- Dynamic Navbar based on User Role -->
    <?php
        if (!isset($_SESSION)) { session_start(); }
        $theme_primary = isset($_SESSION['theme_primary']) ? $_SESSION['theme_primary'] : null;
        $theme_secondary = isset($_SESSION['theme_secondary']) ? $_SESSION['theme_secondary'] : null;
        if ($theme_primary === null || $theme_secondary === null) {
            // Fallback to DB settings
            require_once APPPATH . 'helpers/database_helper.php';
            $db = get_db();
            $res = $db->query("SELECT setting_key, setting_value FROM app_settings WHERE setting_key IN ('theme_primary','theme_secondary')");
            $theme_primary = '#667eea';
            $theme_secondary = '#764ba2';
            if ($res && $res->num_rows > 0) {
                while ($row = $res->fetch_assoc()) {
                    if ($row['setting_key'] === 'theme_primary') { $theme_primary = $row['setting_value']; }
                    if ($row['setting_key'] === 'theme_secondary') { $theme_secondary = $row['setting_value']; }
                }
            }
            $_SESSION['theme_primary'] = $theme_primary;
            $_SESSION['theme_secondary'] = $theme_secondary;
        }
        $navbar_bg = 'linear-gradient(135deg, ' . htmlspecialchars($theme_primary) . ' 0%, ' . htmlspecialchars($theme_secondary) . ' 100%)';
    ?>
    <nav class="navbar navbar-expand-lg navbar-dark" style="background: <?php echo $navbar_bg; ?>; padding: 0.5rem 1rem;">
        <div class="container-fluid" style="padding: 0;">
            <?php
            // Attempt to load branding logo if available
            $logo_path = '';
            if (function_exists('base_url')) {
                // Check for PNG/JPG under assets
                $root = defined('FCPATH') ? rtrim(FCPATH, '/\\') : dirname(dirname(dirname(__FILE__)));
                $png = $root . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'logo.png';
                $jpg = $root . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'logo.jpg';
                if (file_exists($png)) {
                    $logo_path = base_url('assets/logo.png');
                } elseif (file_exists($jpg)) {
                    $logo_path = base_url('assets/logo.jpg');
                }
            }
            ?>
            <a class="navbar-brand d-flex align-items-center" href="<?php echo base_url('home'); ?>" style="font-size: 0.85rem; margin-right: 2rem; gap:0.4rem;">
                <?php if($logo_path): ?>
                    <img src="<?php echo htmlspecialchars($logo_path); ?>" alt="Logo" style="height:24px; width:auto; display:block; border-radius:4px;" />
                <?php else: ?>
                    <i class="bi bi-house"></i>
                <?php endif; ?>
                <span class="fw-semibold">Home</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#dynamicNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="dynamicNavbar">
                <!-- User Menu on Right -->
                <ul class="navbar-nav ms-auto">
                    <?php 
                    $display_name = isset($_SESSION['user_name']) ? trim($_SESSION['user_name']) : (isset($user_name) ? $user_name : 'Guest');
                    $display_email = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : (isset($user_email) ? $user_email : '');
                    $display_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : (isset($user_role) ? $user_role : '');
                    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : '';
                    ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="font-size: 0.8rem; color: #fff;">
                            <span class="me-1"><i class="bi bi-person-circle" style="font-size: 1rem;"></i></span>
                            <span class="fw-semibold" style="max-width:140px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;"><?php echo htmlspecialchars($display_name); ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="userMenu" style="font-size: 0.75rem; min-width: 220px;">
                            <li class="dropdown-header">
                                <div class="fw-semibold" style="font-size:0.75rem;"><?php echo htmlspecialchars($display_name); ?></div>
                                <?php if($display_email): ?><div style="font-size:0.65rem; color:#666;"><?php echo htmlspecialchars($display_email); ?></div><?php endif; ?>
                                <?php if($display_role !== ''): ?><div class="badge bg-light text-dark border" style="font-size:0.55rem;">Role: <?php echo htmlspecialchars($display_role); ?></div><?php endif; ?>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?php echo base_url('home'); ?>"><i class="bi bi-house"></i> Home</a></li>
                            <li><a class="dropdown-item" href="<?php echo base_url('users/view/' . $user_id); ?>"><i class="bi bi-person-lines-fill"></i> Profile</a></li>
                            <li><a class="dropdown-item" href="<?php echo base_url('users/change_password/' . $user_id); ?>"><i class="bi bi-key"></i> Change Password</a></li>
                            <li><a class="dropdown-item" href="<?php echo base_url('users'); ?>"><i class="bi bi-people"></i> User Management</a></li>
                            <li><a class="dropdown-item" href="<?php echo base_url('questions'); ?>"><i class="bi bi-question-circle"></i> Quiz Questions</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="<?php echo base_url('auth/logout'); ?>"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar with Menus -->
            <div class="col-md-2">
                <div class="sidebar">
                    <?php 
                    // Load menu data for sidebar
                    $top_menus = isset($top_menus) ? $top_menus : array();
                    $user_role = isset($user_role) ? $user_role : null;
                    $user_name = isset($user_name) ? $user_name : 'Guest';
                    
                    error_log("Sidebar DEBUG - top_menus count: " . count($top_menus) . ", user_role: " . $user_role);
                    
                    if (!empty($top_menus) && $user_role) {
                        ?>
                        <div class="list-group list-group-flush">
                        <?php
                        foreach ($top_menus as $menu) {
                            $main_menu_name = $menu['MAIN_MENU'];
                            $menu_id = preg_replace('/[^a-z0-9]/i', '_', $main_menu_name);
                        ?>
                            <div class="list-group-item">
                                <a href="#menu_<?php echo $menu_id; ?>" data-bs-toggle="collapse" 
                                   style="text-decoration: none; color: inherit; display: block; cursor: pointer;">
                                    <i class="bi bi-chevron-right"></i> 
                                    <strong><?php echo htmlspecialchars($main_menu_name); ?></strong>
                                </a>
                                <div class="collapse" id="menu_<?php echo $menu_id; ?>">
                                    <div style="padding-left: 1rem; margin-top: 0.3rem;">
                                        <?php
                                        // Load submenus for this main menu
                                        require_once APPPATH . 'helpers/database_helper.php';
                                        
                                        $db = get_db();
                                        $main_menu_escaped = $db->escape($main_menu_name);
                                        $user_role_int = (int)$user_role;
                                        
                                        // Get submenus for this role and main menu
                                        if ($user_role_int == 1) {
                                            // Admin sees all submenus for this main menu
                                            $submenu_sql = "SELECT * FROM menu_details_admin 
                                                           WHERE MAIN_MENU = '" . $main_menu_escaped . "' 
                                                           AND AVAILABLE = 'YES'
                                                           ORDER BY ORDER_NO ASC";
                                        } else {
                                            // Other roles see only their assigned submenus
                                            $submenu_sql = "SELECT * FROM menu_details_admin 
                                                           WHERE MAIN_MENU = '" . $main_menu_escaped . "' 
                                                           AND AVAILABLE = 'YES' 
                                                           AND ROLE_ID = " . $user_role_int . "
                                                           ORDER BY ORDER_NO ASC";
                                        }
                                       
                                        $submenu_result = $db->query($submenu_sql);
                                                                            
                                        if ($submenu_result && $submenu_result->num_rows > 0) {
                                            while ($submenu = $submenu_result->fetch_assoc()) {
                                                $menu_text = htmlspecialchars($submenu['MENUTEXT']);
                                                $menu_url = htmlspecialchars($submenu['NAVIGATION']);
                                        ?>
                                            <div style="padding: 0.3rem 0;">
                                                <a href="<?php echo base_url($menu_url); ?>" style="font-size: 0.65rem; text-decoration: none; color: #667eea;">
                                                    → <?php echo $menu_text; ?>
                                                </a>
                                            </div>
                                        <?php
                                            }
                                        } else {
                                        ?>
                                            <div style="padding: 0.3rem 0; font-size: 0.65rem; color: #999;">
                                                No submenus available
                                            </div>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                        </div>
                        <?php
                    } else {
                        ?>
                        <div class="alert alert-warning" style="font-size: 0.7rem;">
                            No menus available for your role
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-10">
                <div class="main-content">
