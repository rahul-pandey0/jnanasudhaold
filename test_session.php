<?php
/**
 * Test session persistence and menu loading
 */

// Start session
session_start();

// Check if we're testing login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    $_SESSION['logged_in'] = true;
    $_SESSION['user_email'] = 'admin@test.com';
    $_SESSION['user_id'] = 1;
    $_SESSION['user_name'] = 'Admin';
    $_SESSION['user_role'] = 1;
    echo "LOGIN SESSION SET\n";
    echo "user_role = " . $_SESSION['user_role'] . "\n";
    exit;
}

// Check if we're testing menu loading
if ($_GET['action'] === 'check') {
    echo "SESSION CHECK:\n";
    echo "logged_in = " . (isset($_SESSION['logged_in']) ? $_SESSION['logged_in'] : 'NOT SET') . "\n";
    echo "user_role = " . (isset($_SESSION['user_role']) ? $_SESSION['user_role'] : 'NOT SET') . "\n";
    echo "user_name = " . (isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'NOT SET') . "\n";
    
    if ($_SESSION['user_role']) {
        // Simulate menu loading
        require_once 'application/models/Menu_model.php';
        $menu_model = new Menu_model();
        $top_menus = $menu_model->get_top_menus($_SESSION['user_role']);
        echo "top_menus count = " . count($top_menus) . "\n";
        if (count($top_menus) > 0) {
            foreach ($top_menus as $menu) {
                echo "  - " . $menu . "\n";
            }
        }
    }
    exit;
}

// Test variable passing
if (isset($_GET['test'])) {
    $test_data = array(
        'user_role' => 1,
        'user_name' => 'Admin',
        'top_menus' => array('Menu1', 'Menu2')
    );
    
    // Simulate what Dashboard controller does
    $data = $test_data;
    
    // Now check if variables are available
    echo "Test: Variables in \$data array:\n";
    echo "user_role = " . (isset($data['user_role']) ? $data['user_role'] : 'NOT SET') . "\n";
    echo "user_name = " . (isset($data['user_name']) ? $data['user_name'] : 'NOT SET') . "\n";
    echo "top_menus count = " . (isset($data['top_menus']) ? count($data['top_menus']) : 'NOT SET') . "\n";
    
    // Now check if they're available as variables (CI Loader uses extract())
    extract($data);
    echo "\nAfter extract(\$data):\n";
    echo "user_role = " . (isset($user_role) ? $user_role : 'NOT SET') . "\n";
    echo "user_name = " . (isset($user_name) ? $user_name : 'NOT SET') . "\n";
    echo "top_menus count = " . (isset($top_menus) ? count($top_menus) : 'NOT SET') . "\n";
    exit;
}

echo "Test page loaded";
?>
