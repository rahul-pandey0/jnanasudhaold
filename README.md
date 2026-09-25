# CodeIgniter Project

A CodeIgniter 3 project setup with a basic application structure.

## Project Structure

```
jnanasudha/
├── application/
│   ├── config/          # Configuration files
│   ├── controllers/     # Application controllers
│   ├── models/          # Database models
│   ├── views/           # View templates
│   ├── libraries/       # Custom libraries
│   └── helpers/         # Helper functions
├── public/
│   ├── css/             # Stylesheets
│   ├── js/              # JavaScript files
│   └── images/          # Image assets
├── system/              # CodeIgniter framework core
└── index.php            # Application entry point
```

## Setup Instructions

### Prerequisites
- PHP 5.6 or higher
- MySQL or compatible database
- A web server (Apache, Nginx, etc.)

### Installation Steps

1. **Configure the Base URL**
   - Edit `application/config/config.php`
   - Update the `base_url` to match your server setup

2. **Configure Database**
   - Edit `application/config/database.php`
   - Set your database credentials:
     - hostname
     - username
     - password
     - database name

3. **Create Database**
   ```sql
   CREATE DATABASE codeigniter_db;
   ```

4. **Access the Application**
   - Navigate to `http://localhost/jnanasudha/` in your browser
   - You should see the welcome page

## Running the Application

### Using PHP Built-in Server
```bash
cd c:\PROJECTS\jnanasudha
php -S localhost:8000
```

Then access: `http://localhost:8000`

### Using Apache
1. Place the project in your Apache `htdocs` folder
2. Ensure `mod_rewrite` is enabled
3. Access via `http://localhost/jnanasudha/`

## Routing

Routes are configured in `application/config/routes.php`. Example:

```php
$route['blog/(:any)'] = 'blog/view/$1';
$route['posts'] = 'blog/posts';
```

## Creating Controllers

Create a new controller in `application/controllers/`:

```php
<?php
class Blog extends CI_Controller {
    public function index()
    {
        echo "Welcome to Blog!";
    }
    
    public function view($id)
    {
        echo "Blog post ID: " . $id;
    }
}
```

Access via: `http://localhost/jnanasudha/blog` or `http://localhost/jnanasudha/blog/view/1`

## Creating Models

Create a model in `application/models/`:

```php
<?php
class User_model extends CI_Model {
    public function get_users()
    {
        $query = $this->db->get('users');
        return $query->result();
    }
}
```

## Creating Views

Create a view in `application/views/`:

```html
<h1><?php echo $title; ?></h1>
<p><?php echo $content; ?></p>
```

## Useful Resources

- [CodeIgniter Documentation](https://codeigniter.com/user_guide/)
- [CodeIgniter Forum](https://forum.codeigniter.com/)
- [PHP Documentation](https://www.php.net/docs.php)

## License

This project is open source and available under the MIT License.
