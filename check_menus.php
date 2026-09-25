<?php
// Quick check of menu data
$mysqli = new mysqli('localhost', 'root', 'password', 'newtechv_quizmaster');

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Get all distinct main menus with YES availability
$sql = "SELECT DISTINCT MAIN_MENU, COUNT(*) as menu_count FROM menu_details_admin WHERE AVAILABLE = 'YES' GROUP BY MAIN_MENU ORDER BY MAIN_MENU ASC";
$result = $mysqli->query($sql);

if ($result && $result->num_rows > 0) {
    echo "Main Menus:\n";
    while ($row = $result->fetch_assoc()) {
        echo "  - " . $row['MAIN_MENU'] . " (" . $row['menu_count'] . " items)\n";
    }
} else {
    echo "No menus found\n";
}

// Now check for role 1 specifically with old query  
echo "\nRole 1 menus (OLD query - with ROLE_ID = 1):\n";
$sql_old = "SELECT DISTINCT MAIN_MENU FROM menu_details_admin WHERE AVAILABLE = 'YES' AND ROLE_ID = 1 ORDER BY MAIN_MENU ASC";
$result_old = $mysqli->query($sql_old);
if ($result_old && $result_old->num_rows > 0) {
    while ($row = $result_old->fetch_assoc()) {
        echo "  - " . $row['MAIN_MENU'] . "\n";
    }
} else {
    echo "No menus for role 1 with old query\n";
}

// Check what roles have menus
echo "\nMenus by Role:\n";
$sql_roles = "SELECT DISTINCT ROLE_ID, MAIN_MENU FROM menu_details_admin WHERE AVAILABLE = 'YES' ORDER BY ROLE_ID, MAIN_MENU";
$result_roles = $mysqli->query($sql_roles);
if ($result_roles && $result_roles->num_rows > 0) {
    $current_role = null;
    while ($row = $result_roles->fetch_assoc()) {
        if ($current_role !== $row['ROLE_ID']) {
            $current_role = $row['ROLE_ID'];
            echo "\n  Role " . $current_role . ":\n";
        }
        echo "    - " . $row['MAIN_MENU'] . "\n";
    }
}

$mysqli->close();
?>
