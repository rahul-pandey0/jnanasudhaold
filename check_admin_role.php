<?php
define('ENVIRONMENT', 'development');

$db_config = array(
    'hostname' => 'localhost',
    'username' => 'root',
    'password' => 'password',
    'database' => 'newtechv_quizmaster'
);

$conn = new mysqli($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);

if ($conn->connect_error) {
    die('Connection Error: ' . $conn->connect_error);
}

// Check admin user role
echo "Admin User Info:\n";
$result = $conn->query('SELECT user_id, user_name, email, role_id FROM user_details WHERE user_name = "admin"');
$row = $result->fetch_assoc();
echo "  User ID: " . $row['user_id'] . "\n";
echo "  Username: " . $row['user_name'] . "\n";
echo "  Email: " . $row['email'] . "\n";
echo "  Role ID: " . $row['role_id'] . "\n\n";

$admin_role = $row['role_id'];

// Check menus for admin role
echo "Menus for Role ID " . $admin_role . ":\n";
$result = $conn->query('SELECT DISTINCT MAIN_MENU FROM menu_details_admin WHERE ROLE_ID = ' . $admin_role . ' AND AVAILABLE = "YES"');
echo "Total main menus: " . $result->num_rows . "\n";

while($r = $result->fetch_assoc()) {
    echo "  - " . $r['MAIN_MENU'] . "\n";
}

echo "\nAll menus in DB for role " . $admin_role . ":\n";
$result = $conn->query('SELECT MAIN_MENU, MENUTEXT FROM menu_details_admin WHERE ROLE_ID = ' . $admin_role . ' LIMIT 10');
while($r = $result->fetch_assoc()) {
    echo "  - Main: " . $r['MAIN_MENU'] . " | Text: " . $r['MENUTEXT'] . "\n";
}

$conn->close();
?>
