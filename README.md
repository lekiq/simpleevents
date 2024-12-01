
# SimpleEvents

A simple PHP application for managing and viewing sports events, built with an MVC structure.

## Overview

SimpleEvents is a sports event calendar application developed as a coding exercise. It allows users to:
- View sports events with details like teams, venue, date, and description
- Filter events by sport and date
- Add new events through a modal form
- View events in a responsive grid layout

### Features

- **Event Listing**: Display events in a card-based layout
- **Filtering System**: Filter events by sport and date
- **Dynamic Form**: Add new events with automatic team loading based on selected sport
- **Responsive Design**: Works on mobile, tablet, and desktop views
- **SQL Injection Protection**: Uses prepared statements for database queries
- **Input Validation**: Both frontend and backend validation for data integrity

## Database Structure

### ERD Diagram
![Database ERD](erd.png)

### Tables
- **sports**: Stores different types of sports
- **teams**: Stores teams with their associated sport
- **venues**: Stores event venues
- **events**: Main table linking teams, venues, and containing event details

## Getting Started

### Minimum Requirements

To run this application, ensure the following requirements are met:

1. **PHP**: Version 8.3 or higher must be installed on your system.
    - Check your PHP version by running: `php -v`
    - [Download PHP](https://www.php.net/downloads)
2. **Composer**: Install Composer to manage dependencies.
    - [Download Composer](https://getcomposer.org/download/)
3. **SQLite**: Ensure the SQLite extension for PHP is enabled.
    - Confirm by running: `php -m | grep sqlite3`
4. **Development Environment**:
    - Any IDE supporting PHP, such as **PHPStorm** or **VSCode**, is recommended.

### Installation

1. Clone the repository:

```bash
git clone https://github.com/lekiq/simpleevents.git
cd simpleevents
```

2. Install dependencies:

```bash
composer install
```

3. Configure environment:
- Rename `.env.test` to `.env`
- Update the `DB_PATH` in `.env` to point to your SQLite database

4. Start the application:

```bash
php -S localhost:8000 -t public
```

## Application Structure

### Core Components

- **MVC Architecture**: Follows Model-View-Controller pattern
- **Router**: Custom routing system for handling requests
- **Database**: PDO-based database connection management
- **Config**: Environment variable management using phpdotenv

### Key Files

- `routes.php`: Defines all application routes
- `app/controllers/EventController.php`: Handles event-related actions
- `app/models/Event.php`: Event model with database operations
- `app/views/pages/index.php`: Main view template

## Development

### Code Style

The project follows PSR-12 coding standards. Use the following commands:

```bash
# Check code style
vendor/bin/phpcs

# Fix code style
vendor/bin/phpcbf
```

### API Endpoints

- `GET /api/sports`: Retrieve all sports
- `GET /api/teams`: Get teams for a specific sport
- `GET /api/venues`: Get all venues
- `POST /events`: Create a new event

## Technical Decisions

1. **SQLite**: Chosen for simplicity and portability
2. **No Frontend Framework**: Pure JavaScript for lighter footprint
3. **CSS Nesting**: Modern CSS approach for maintainable styles
4. **Modal Form**: Enhanced UX for event creation
5. **Prepared Statements**: Security against SQL injection

## Future Improvements

- Add authentication system
- Implement event editing and deletion
- Add pagination for event listing
- Include image upload for teams/venues
- Add unit tests
- Implement caching system

## License

MIT License