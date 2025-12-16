# Worktopia

A job listings portal built with vanilla PHP following an MVC architecture.

## Features

- Browse, search, create, edit, and delete job listings
- User authentication (register, login, logout)
- Route-level middleware for authorization
- Flash messages and session management

## Requirements

- PHP 7.4+
- MySQL 5.7+
- Composer

## Quick Start

```bash
# Clone and install
git clone https://github.com/ThureinS/JobPortal-PHP.git
cd JobPortal-PHP
composer install

# Set up database
mysql -u root -p < listings.sql

# Configure database credentials
# Create config/db.php (see DOCS.md for format)

# Run development server
php -S localhost:8000 -t public
```

Visit `http://localhost:8000`

## Documentation

See [DOCS.md](DOCS.md) for architecture details, routes, and database schema.

## License

MIT
