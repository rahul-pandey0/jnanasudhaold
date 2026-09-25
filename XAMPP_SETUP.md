# XAMPP Setup Guide for jnanasudha

## Overview
This application works with both PHP development server (`localhost:8000`) and XAMPP (`localhost:8080` or `localhost:8081`).

## Quick Start for XAMPP

### 1. **Install XAMPP**
   - Download from: https://www.apachefriends.org/
   - Install to default location (usually `C:\xampp` on Windows)

### 2. **Copy Project to XAMPP**
   ```
   Copy entire jnanasudha folder to: C:\xampp\htdocs\
   
   Result: C:\xampp\htdocs\jnanasudha\
   ```

### 3. **Start XAMPP**
   - Open XAMPP Control Panel
   - Click **Start** for Apache
   - Click **Start** for MySQL
   - Wait for green status indicators

### 4. **Access the Application**
   ```
   http://localhost:8080/jnanasudha/
   or
   http://localhost/jnanasudha/  (if port 80 is available)
   ```

### 5. **Login**
   - Username: `admin`
   - Password: `admin123`

## Configuration Details

### Base URL (Automatic Detection)
The application automatically detects your setup:

- **For XAMPP (port 8080/8081)**:
  ```
  http://localhost:8080/jnanasudha/
  or
  http://localhost:8081/jnanasudha/
  ```

- **For PHP Dev Server**:
  ```
  http://localhost:8000/
  ```

- **For Custom Domain**:
  ```
  http://yourdomain.local/jnanasudha/
  ```

**Location**: `application/config/config.php`

### htaccess Configuration
The `.htaccess` file rewrites all requests to `index.php`:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /jnanasudha/
    
    # Remove index.php from URL
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php/$1 [L]
</IfModule>
```

**Note**: This requires `mod_rewrite` to be enabled in Apache.

## Enable Apache mod_rewrite (if needed)

### On Windows (XAMPP):
1. Open `C:\xampp\apache\conf\httpd.conf`
2. Find the line: `#LoadModule rewrite_module modules/mod_rewrite.so`
3. Remove the `#` to uncomment it
4. Save the file
5. Restart Apache from XAMPP Control Panel

### Verify mod_rewrite is enabled:
- Create a test file `C:\xampp\htdocs\phpinfo.php`:
  ```php
  <?php phpinfo(); ?>
  ```
- Visit `http://localhost/phpinfo.php`
- Search for "mod_rewrite" - should show "enabled"

## Database Configuration

### Database Settings
- **Host**: `localhost`
- **Username**: `root`
- **Password**: (leave empty for default XAMPP)
- **Database**: `newtechv_quizmaster`
- **Port**: `3306`

**Location**: `application/config/database.php`

### Create Database (if needed)
1. Open phpMyAdmin: `http://localhost/phpmyadmin`
2. Create new database: `newtechv_quizmaster`
3. Import your SQL file

## Session Configuration

The application uses file-based sessions:
- **Driver**: `files`
- **Path**: System temp directory (auto-detected)
- **Expiration**: 24 hours

**Location**: `application/config/config.php`

## Troubleshooting

### Issue: "Page Not Found" (404)
**Solution**: 
- Make sure htaccess mod_rewrite is enabled
- Check that project is in `C:\xampp\htdocs\jnanasudha\`
- Verify base_url is correct in config.php

### Issue: "Can't connect to database"
**Solution**:
- Start MySQL from XAMPP Control Panel
- Check database credentials in `application/config/database.php`
- Verify database exists in phpMyAdmin

### Issue: "Session not saving"
**Solution**:
- Check temp directory permissions
- Verify `sess_driver` is set to `'files'`
- Check `sess_save_path` in config.php

### Issue: "CSS/Images not loading"
**Solution**:
- Make sure base_url includes trailing slash
- Check that assets are in public directory
- Verify all paths use `base_url()` function

## Project Structure for XAMPP

```
C:\xampp\htdocs\jnanasudha\
├── .htaccess              (URL rewriting)
├── index.php              (Entry point)
├── application/
│   ├── config/            (Configuration files)
│   ├── controllers/       (Page controllers)
│   ├── models/            (Database models)
│   ├── views/             (HTML templates)
│   └── helpers/           (Helper functions)
├── system/                (Framework core)
└── public/                (CSS, JS, images)
```

## Testing the Setup

### Test 1: Access Login Page
```
http://localhost:8080/jnanasudha/auth/login
```

### Test 2: Login with Admin
- Username: `admin`
- Password: `admin123`

### Test 3: Check Dashboard
Should display sidebar with menus and submenus.

### Test 4: Check Error Logs
```
C:\xampp\apache\logs\error.log
C:\xampp\htdocs\jnanasudha\logs\  (if available)
```

## Performance Tips

1. **Enable Query Caching** in `config.php`
2. **Use Opcache** in PHP settings
3. **Minify CSS/JS** in public directory
4. **Enable GZIP** in Apache

## Additional Resources

- **Apache Documentation**: https://httpd.apache.org/
- **PHP Documentation**: https://www.php.net/manual/
- **CodeIgniter Documentation**: https://codeigniter.com/userguide/

## Support

For issues or questions:
1. Check error logs
2. Enable debug mode in `config.php`
3. Check application logs in `application/logs/`
4. Verify all file permissions are readable

---
**Version**: 1.0
**Last Updated**: November 26, 2025
