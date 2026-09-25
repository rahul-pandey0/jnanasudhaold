# Dynamic Menu System Documentation

## Overview
This modern, secure menu system replaces the legacy `Login_model` menu loading approach with role-based, dynamic menu generation.

## Architecture

### Components

1. **Menu_model** (`application/models/Menu_model.php`)
   - Core model for menu operations
   - Secure SQL queries (no SQL injection)
   - Session-based role detection
   - Methods:
     - `get_top_menus($role_id)` - Get main menu items
     - `get_submenus($main_menu, $role_id, $username)` - Get submenu items
     - `get_menu_hierarchy($role_id, $username)` - Get complete menu tree
     - `has_menu_access($role_id, $menu_id)` - Check access permission
     - `get_public_menus()` - Get guest-accessible menus

2. **Menu Controller** (`application/controllers/Menu.php`)
   - AJAX endpoints for menu operations
   - Methods return JSON responses
   - Endpoints:
     - `/menu/get_top_menus` - Get main menus
     - `/menu/get_submenus/[main_menu]` - Get submenus
     - `/menu/get_hierarchy` - Get full hierarchy
     - `/menu/check_access/[menu_id]` - Check access

3. **Dynamic Menu View** (`application/views/layout/dynamic_menu.php`)
   - Bootstrap 5 navbar with dropdowns
   - Responsive design
   - User profile menu
   - Auto-generates based on role

4. **Menu Helper** (`application/helpers/menu_helper.php`)
   - Utility functions for menu operations
   - Functions:
     - `load_user_menu()` - Load menu view
     - `get_user_menus()` - Get menu array
     - `check_menu_access()` - Check access
     - `render_menu_html()` - Generate HTML

## Database Schema

Expected `menu_details` table structure:

```sql
CREATE TABLE menu_details (
    id INT PRIMARY KEY AUTO_INCREMENT,
    MAIN_MENU VARCHAR(100) NOT NULL,
    MENUTEXT VARCHAR(200) NOT NULL,
    MENU_LINK VARCHAR(255),
    ROLE_ID VARCHAR(50),
    AVAILABLE VARCHAR(3) DEFAULT 'YES',
    ORDER_NO INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

## Usage Examples

### 1. Load Menu in View
```php
<?php
// In your controller
$this->load->view('layout/dynamic_menu', array(
    'role_id' => $_SESSION['user_role'],
    'username' => $_SESSION['user_name']
));
?>
```

### 2. Using Helper Function
```php
<?php
// Load menu helper
$this->load->helper('menu_helper');

// Load menu
load_user_menu();

// Or get menu data
$menus = get_user_menus();
echo render_menu_html($menus);
?>
```

### 3. Check Menu Access
```php
<?php
if (check_menu_access($_SESSION['user_role'], 5)) {
    // User has access to menu ID 5
    // Show restricted content
}
?>
```

### 4. AJAX Menu Loading
```javascript
// Get top menus
fetch('/menu/get_top_menus')
    .then(r => r.json())
    .then(data => console.log(data.menus));

// Get submenus for a main menu
fetch('/menu/get_submenus/Dashboard', { method: 'POST' })
    .then(r => r.json())
    .then(data => console.log(data.submenus));

// Get complete hierarchy
fetch('/menu/get_hierarchy')
    .then(r => r.json())
    .then(data => console.log(data.hierarchy));
```

## Role-Based Access Control

### Admin (Role 1)
- Access to all menus
- No restrictions

### Role 2 (Student/Limited)
- Access to menus with ROLE_ID IN (2, 4, 5)
- Excluded from admin-only items

### Role 3
- Access to menus with ROLE_ID = 3

### Role 4
- Access to menus with ROLE_ID = 6

### Role 5 (Public)
- Access to menus with ROLE_ID not in (1, 15, 16, 17)

## Security Features

✅ **SQL Injection Prevention**
- Using parameterized queries via `db->escape()`
- No direct string concatenation in user input

✅ **Session-Based Access Control**
- Menu visibility based on `$_SESSION['user_role']`
- Server-side role validation

✅ **XSS Protection**
- Using `htmlspecialchars()` for output
- Safe rendering of menu text and links

✅ **Access Control**
- `check_menu_access()` validates user permissions
- Protected controller inheritance for admin functions

## Implementation Steps

### Step 1: Create Menu Model
✅ Already created: `application/models/Menu_model.php`

### Step 2: Create Menu Controller
✅ Already created: `application/controllers/Menu.php`

### Step 3: Create Dynamic Menu View
✅ Already created: `application/views/layout/dynamic_menu.php`

### Step 4: Create Menu Helper
✅ Already created: `application/helpers/menu_helper.php`

### Step 5: Use in Your Pages
```php
<?php
class Dashboard extends CI_ProtectedController {
    public function index() {
        // Menu will be loaded automatically
        // Or load manually:
        $this->load->view('layout/dynamic_menu');
        $this->load->view('dashboard/index');
    }
}
?>
```

## Migration from Legacy System

### Old (Insecure) Way
```php
// Old Login_model with SQL injection vulnerabilities
$sql = "SELECT * FROM menu_details WHERE MAIN_MENU='$mainmenu' AND ROLE_ID='$role'";
$query = $this->db->query($sql);  // UNSAFE!
```

### New (Secure) Way
```php
// New Menu_model with safe parameterized queries
$main_menu = $db->escape($main_menu);  // Escape input
$sql = "SELECT * FROM menu_details WHERE MAIN_MENU = " . $main_menu;
$result = $db->query($sql);
$rows = $db->get_rows($result);
```

## Configuration

### Session Variables Required
```php
$_SESSION['user_role']  // Required: User's role ID
$_SESSION['user_name']  // Required: Username for display
$_SESSION['org_id']     // Optional: Organization ID
```

### Available Methods

#### Menu_model Methods
- `get_top_menus($role_id)` - Get distinct main menus
- `get_submenus($main_menu, $role_id, $username)` - Get submenu items
- `get_menu_hierarchy($role_id, $username)` - Get complete tree
- `has_menu_access($role_id, $menu_id)` - Boolean access check
- `get_public_menus()` - Non-admin menus

#### Helper Functions
- `load_user_menu($role_id, $username)` - Render menu view
- `get_user_menus($role_id)` - Get menu array
- `check_menu_access($role_id, $menu_id)` - Check permission
- `render_menu_html($hierarchy)` - Generate menu HTML

## Troubleshooting

### Menu Not Showing
1. Verify `$_SESSION['user_role']` is set
2. Check menu_details table has records
3. Verify AVAILABLE = 'YES' for menu items
4. Check ROLE_ID matches user's role

### SQL Errors
1. Verify menu_details table exists
2. Check column names match: MAIN_MENU, MENUTEXT, MENU_LINK, ROLE_ID, AVAILABLE, ORDER_NO
3. Run database schema check

### Access Denied
1. Verify role_id in user_details table
2. Check menu ROLE_ID settings
3. Verify session variables are set correctly

## Performance Tips

1. **Cache Menu Hierarchy** - Cache `get_menu_hierarchy()` results
2. **Limit Submenus** - Use pagination for large menu lists
3. **Lazy Load** - Load submenus on demand via AJAX
4. **Database Index** - Add index on MAIN_MENU and ROLE_ID columns

## Future Enhancements

- [ ] Menu caching for performance
- [ ] Dynamic menu management UI
- [ ] Menu breadcrumb component
- [ ] Active state tracking
- [ ] Permission-based hiding
- [ ] Multi-language support
- [ ] Menu icons/badges
- [ ] Submenu history tracking
