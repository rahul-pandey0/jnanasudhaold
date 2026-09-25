<?php
// Test script to check admin menus
require_once 'application/config/database.php';
require_once 'application/helpers/database_helper.php';

// Initialize database
$GLOBALS['db_instance'] = new CI_DB(array(
    'hostname' => $db['default']['hostname'],
    'username' => $db['default']['username'],
    'password' => $db['default']['password'],
    'database' => $db['default']['database']
));

// Test query
$role_id = 1; // Admin
$sql = "SELECT DISTINCT MAIN_MENU FROM menu_details_admin WHERE AVAILABLE = 'YES' ORDER BY MAIN_MENU ASC";

$result = get_db()->query($sql);
if ($result) {
    $rows = $result->fetch_all(MYSQLI_ASSOC);
    echo "Admin menus found: " . count($rows) . "\n";
    foreach ($rows as $menu) {
        echo "- " . $menu['MAIN_MENU'] . "\n";
    }
} else {
    echo "Query failed\n";
}
?>
