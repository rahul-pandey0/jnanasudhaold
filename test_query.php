<?php
// Simple debug script
echo "Testing get_top_menus for admin (role_id=1)\n";
echo "Query: SELECT DISTINCT MAIN_MENU FROM menu_details_admin WHERE AVAILABLE = 'YES' ORDER BY MAIN_MENU ASC\n\n";

// The query should return menu names like:
// - Feedback
// - Subject Information
// etc.

// This will show what menus should be available
?>
