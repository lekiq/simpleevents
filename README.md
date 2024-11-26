# **SimpleEvents**

A simple PHP application for managing and viewing sports events, built with an MVC structure.

---

## **Getting Started**

### **Minimum Requirements**

- **PHP Version**: 8.3 or higher
- **Composer**: Ensure Composer is installed to manage dependencies.

---

## **Application Structure**

### **Routes**

- All application routes are defined in the `routes.php` file located in the project root.

### **Environment Variables**

- Rename the `.env.test` file to `.env`.
- Configure environment variables in the `.env` file.
- The `Config` class handles environment variables:
    - Load variables using:
      ```php
      Config::load();
      ```
    - Access variables using:
      ```php
      Config::get('ENV_VARIABLE');
      ```
- Example `.env` file:
  ```env
  DB_PATH=/path/to/your/database.sqlite
  ```

---

## **Scripts**

### **Code Fixer**

Automatically fix code style issues:

```bash
vendor/bin/phpcbf
```

### **Code Sniffer**

Check for code style violations:

```bash
vendor/bin/phpcs
```

---

## **Running the Application**

To start the application locally:

```bash
php -S localhost:8000 -t public
```

---

## **Documentation: Query Method**

### **Purpose**

The `Event::query()` method dynamically fetches event data from the database with optional filters.

### **Usage**

```php
$args = [
    '_sport_id' => 1,       // Optional: Filter by sport ID
    'date' => '2024-12-01'  // Optional: Filter by event date
];

$events = Event::query($args);
```

### **Supported Filters**

- `_sport_id`: Matches events belonging to the specified sport ID.
- `date`: Matches events occurring on the specified date.

### **SQL Injection Protection**

The method uses prepared statements and bound parameters to ensure security.

---

## **Notes**

- Before starting the application, update the `.env` file with the necessary configurations, such as `DB_PATH`.
- Ensure all dependencies are installed by running:
  ```bash
  composer install
  ```