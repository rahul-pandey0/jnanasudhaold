<?php
// Quick menu data check
define('ENVIRONMENT', 'development');

$db_config = array(
    'default' => array(
        'hostname' => 'localhost',
        'username' => 'root',
        'password' => 'password',
        'database' => 'newtechv_quizmaster'
    )
);

$conn = new mysqli($db_config['default']['hostname'], $db_config['default']['username'], $db_config['default']['password'], $db_config['default']['database']);

if ($conn->connect_error) {
    die('Connection Error: ' . $conn->connect_error);
}

// Check menu records
$result = $conn->query('SELECT COUNT(*) as count FROM menu_details_admin');
$row = $result->fetch_assoc();
echo "Total menu records: " . $row['count'] . "\n\n";

// Check roles in menu
echo "Roles in menu_details_admin:\n";
$result = $conn->query('SELECT DISTINCT ROLE_ID FROM menu_details_admin ORDER BY ROLE_ID');
while($r = $result->fetch_assoc()) {
    echo "  - Role ID: " . $r['ROLE_ID'] . "\n";
}

// Check main menus
echo "\nMain Menus:\n";
$result = $conn->query('SELECT DISTINCT MAIN_MENU FROM menu_details_admin ORDER BY MAIN_MENU');
while($r = $result->fetch_assoc()) {
    echo "  - " . $r['MAIN_MENU'] . "\n";
}

// Sample menus for role 1
echo "\nSample menus for Role ID 1:\n";
$result = $conn->query('SELECT MAIN_MENU, MENUTEXT, NAVIGATION FROM menu_details_admin WHERE ROLE_ID = 1 LIMIT 5');
while($r = $result->fetch_assoc()) {
    echo "  - Main: " . $r['MAIN_MENU'] . " | Text: " . $r['MENUTEXT'] . " | Nav: " . $r['NAVIGATION'] . "\n";
}

$conn->close();
?>
