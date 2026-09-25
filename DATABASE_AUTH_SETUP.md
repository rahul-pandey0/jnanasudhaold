# Database Authentication Setup

## Authentication Requirements

Login is now validated against `user_details` table with the following criteria:

1. **Email field** - Used for login (case-sensitive comparison)
2. **Password field** - MD5 encrypted (use md5_generator.php to generate hashes)
3. **role_id** - Must NOT be 2 (role 2 users cannot login)
4. **user_status** - Must be 1 (active)

## SQL Examples for Test Users

### Create Admin User (role_id = 1)
```sql
INSERT INTO user_details (
    user_name, 
    password, 
    first_name, 
    last_name, 
    email, 
    phone, 
    role_id, 
    user_status
) VALUES (
    'admin',
    MD5('admin123'),
    'Admin',
    'User',
    'admin@test.com',
    '9876543210',
    1,
    1
);
```

### Create Regular User (role_id = 3)
```sql
INSERT INTO user_details (
    user_name, 
    password, 
    first_name, 
    last_name, 
    email, 
    phone, 
    role_id, 
    user_status
) VALUES (
    'user1',
    MD5('password123'),
    'John',
    'Doe',
    'john@example.com',
    '9876543211',
    3,
    1
);
```

### Create Inactive User (Cannot Login)
```sql
INSERT INTO user_details (
    user_name, 
    password, 
    first_name, 
    last_name, 
    email, 
    phone, 
    role_id, 
    user_status
) VALUES (
    'inactive_user',
    MD5('password123'),
    'Jane',
    'Smith',
    'jane@example.com',
    '9876543212',
    1,
    0
);
```

### Create Role 2 User (Cannot Login - even if active)
```sql
INSERT INTO user_details (
    user_name, 
    password, 
    first_name, 
    last_name, 
    email, 
    phone, 
    role_id, 
    user_status
) VALUES (
    'restricted_user',
    MD5('password123'),
    'Bob',
    'Johnson',
    'bob@example.com',
    '9876543213',
    2,
    1
);
```

## How to Generate MD5 Hashes

1. Visit `http://localhost:8000/md5_generator.php`
2. Enter your password
3. Copy the generated MD5 hash
4. Use in SQL INSERT statements

## Testing Login

- **Valid login:** Any user with role_id != 2 and user_status = 1
- **Invalid login:** 
  - Wrong email or password
  - User has role_id = 2
  - User has user_status = 0 (inactive)

## Session Data After Login

After successful login, the following session variables are set:
- `$_SESSION['logged_in']` = true
- `$_SESSION['user_email']` = user's email
- `$_SESSION['user_id']` = user's ID
- `$_SESSION['user_name']` = user's full name
- `$_SESSION['role_id']` = user's role ID
