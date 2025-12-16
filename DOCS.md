# Documentation

## Project Structure

```
├── App/
│   ├── controllers/          # Request handlers
│   │   ├── HomeController.php
│   │   ├── ListingController.php
│   │   ├── UserController.php
│   │   └── ErrorController.php
│   └── views/                # PHP templates
│       ├── listings/         # Listing views (index, show, create, edit)
│       ├── users/            # Auth views (login, register)
│       └── partials/         # Reusable components
├── Framework/                # Core framework
│   ├── Router.php            # Route registration & dispatching
│   ├── Database.php          # PDO wrapper
│   ├── Session.php           # Session & flash messages
│   ├── Authorization.php     # Ownership checks
│   ├── Validation.php        # Input validation
│   └── middleware/
│       └── Authorize.php     # Auth/guest middleware
├── config/
│   └── db.php                # Database credentials (gitignored)
├── public/                   # Web root
│   ├── index.php             # Entry point
│   └── css/
├── helpers.php               # Global helper functions
├── route.php                 # Route definitions
└── listings.sql              # Database schema
```

---

## Configuration

Create `config/db.php`:

```php
<?php
return [
    'host' => 'localhost',
    'port' => 3306,
    'dbname' => 'worktopia',
    'username' => 'your_username',
    'password' => 'your_password'
];
```

> ⚠️ This file is gitignored. Never commit credentials.

---

## Database Schema

### users

| Column     | Type         | Notes             |
| ---------- | ------------ | ----------------- |
| id         | INT          | Primary key, auto |
| name       | VARCHAR(255) |                   |
| email      | VARCHAR(255) | Unique            |
| password   | VARCHAR(255) | Hashed            |
| city       | VARCHAR(45)  |                   |
| state      | VARCHAR(45)  |                   |
| created_at | TIMESTAMP    | Default: now      |

### listings

| Column       | Type         | Notes                   |
| ------------ | ------------ | ----------------------- |
| id           | INT          | Primary key, auto       |
| user_id      | INT          | FK → users.id (CASCADE) |
| title        | VARCHAR(255) | Required                |
| description  | LONGTEXT     |                         |
| salary       | VARCHAR(45)  |                         |
| tags         | VARCHAR(255) |                         |
| company      | VARCHAR(45)  |                         |
| address      | VARCHAR(255) |                         |
| city         | VARCHAR(45)  |                         |
| state        | VARCHAR(45)  |                         |
| phone        | VARCHAR(45)  |                         |
| email        | VARCHAR(45)  |                         |
| requirements | LONGTEXT     |                         |
| benefits     | LONGTEXT     |                         |
| created_at   | TIMESTAMP    | Default: now            |

---

## Routes

| Method | URI                   | Controller@Method           | Middleware |
| ------ | --------------------- | --------------------------- | ---------- |
| GET    | `/`                   | HomeController@index        |            |
| GET    | `/listings`           | ListingController@index     |            |
| GET    | `/listings/create`    | ListingController@create    | auth       |
| POST   | `/listings`           | ListingController@store     | auth       |
| GET    | `/listings/search`    | ListingController@search    |            |
| GET    | `/listings/{id}`      | ListingController@show      |            |
| GET    | `/listings/edit/{id}` | ListingController@edit      | auth       |
| PUT    | `/listings/{id}`      | ListingController@update    | auth       |
| DELETE | `/listings/{id}`      | ListingController@destroy   | auth       |
| GET    | `/auth/register`      | UserController@create       | guest      |
| POST   | `/auth/register`      | UserController@store        | guest      |
| GET    | `/auth/login`         | UserController@login        | guest      |
| POST   | `/auth/login`         | UserController@authenticate | guest      |
| POST   | `/auth/logout`        | UserController@logout       | auth       |

---

## Architecture

### Router

Routes are defined in `route.php` using a fluent syntax:

```php
$router->get('/listings', 'ListingController@index');
$router->post('/listings', 'ListingController@store', ['auth']);
```

- Supports `GET`, `POST`, `PUT`, `DELETE`
- Dynamic parameters: `/listings/{id}`
- Middleware: `['auth']` or `['guest']`

PUT/DELETE are handled via `_method` hidden field in forms.

### Middleware

- **auth** – Redirects unauthenticated users to `/auth/login`
- **guest** – Redirects authenticated users to `/`

### Database

PDO wrapper with prepared statements:

```php
$db->query('SELECT * FROM listings WHERE id = :id', ['id' => $id]);
```

### Session

```php
Session::set('user', $userData);
Session::get('user');
Session::setFlashMessage('success_message', 'Done!');
Session::getFlashMessage('success_message');
```

### Authorization

Ownership check for edit/delete operations:

```php
Authorization::isOwner($listing->user_id);
```

---

## Helpers

| Function                    | Description                         |
| --------------------------- | ----------------------------------- |
| `basePath($path)`           | Get absolute path from project root |
| `loadView($name, $data)`    | Render a view with data             |
| `loadPartial($name, $data)` | Render a partial                    |
| `sanitize($value)`          | Sanitize user input                 |
| `formatSalary($salary)`     | Format as currency                  |
| `redirect($url)`            | HTTP redirect                       |

---

## Security Notes

- Passwords hashed with `password_hash()` / `password_verify()`
- SQL injection prevented via PDO prepared statements
- XSS mitigation via `sanitize()` helper
- CSRF protection not implemented (consider adding for production)
