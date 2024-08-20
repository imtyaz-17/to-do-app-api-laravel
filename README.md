## ITC To-Do Web App API

This is a RESTful API for a To-Do web application built using Laravel 11. The API allows users to manage their tasks and
task lists efficiently. This project focuses on the backend, providing all necessary endpoints for creating, reading,
updating, and deleting tasks and task lists.


[//]: # (- [Simple, fast routing engine]&#40;https://laravel.com/docs/routing&#41;.)

[//]: # (- [Powerful dependency injection container]&#40;https://laravel.com/docs/container&#41;.)

[//]: # (- Multiple back-ends for [session]&#40;https://laravel.com/docs/session&#41; and [cache]&#40;https://laravel.com/docs/cache&#41; storage.)

[//]: # (- Expressive, intuitive [database ORM]&#40;https://laravel.com/docs/eloquent&#41;.)

[//]: # (- Database agnostic [schema migrations]&#40;https://laravel.com/docs/migrations&#41;.)

[//]: # (- [Robust background job processing]&#40;https://laravel.com/docs/queues&#41;.)

[//]: # (- [Real-time event broadcasting]&#40;https://laravel.com/docs/broadcasting&#41;.)

## Table of Contents

- [Features](#features)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [API Endpoints](#api-endpoints)
- [Authentication](#authentication)
- [Database Migrations](#database-migrations)
- [Testing](#testing)
- [Contributing](#contributing)
- [License](#license)

## Features

- **User Authentication**: Secure user authentication using Laravel Sanctum.
- **Token-based Authentication**: Implemented token-based authentication for API access.
- **Task Management**: Create, update, delete, and retrieve tasks.
- **Task List Management**: Organize tasks into lists with titles.
- **Due Dates**: Set due dates for tasks.

## Installation

### Prerequisites

- PHP 8.2 or higher
- Composer
- Laravel 11
- MySQL

### Steps

1. **Clone the repository**:
   `bash git clone`

   https://git.sandbox3000.com/imtyaz/itc-todo-app-api  
   `cd todo-web-app-api`

2. **Install dependencies**:
   `bash composer install`

3. **Create a `.env` file**:
   Copy the `.env.example` file and update your database and other configurations.
   `bash cp .env.example .env`

4. **Generate an application key**:
   `bash php artisan key:generate`

5. **Run migrations**:
   `bash php artisan migrate`

6. **Run the development server**:
   `bash php artisan serve`

## Configuration

Update the `.env` file with your database credentials and other settings:

`.env`

` DB_CONNECTION=mysql`

`DB_HOST=127.0.0.1` `DB_PORT=3306 `

`DB_DATABASE=your_database_name `

`DB_USERNAME=your_username ` `DB_PASSWORD=your_password`

`
SANCTUM_STATEFUL_DOMAINS=yourdomain.com `

`SESSION_DOMAIN=yourdomain.com`

## Usage

After setting up the project, you can start using the API by making requests to the provided endpoints. Ensure you
include the authentication token in your requests where required.

[//]: # (### API Documentation)

[//]: # ()

[//]: # (Detailed API documentation can be accessed at [API Documentation]&#40;link_to_documentation&#41; &#40;if applicable, otherwise consider using Postman collection or similar tools&#41;.)

[//]: # ()

[//]: # (## API Endpoints)

## Database Migrations

To run the migrations, use:

`bash php artisan migrate`

This will create the necessary tables in your database, including \`users\`, \`task_lists\`, and \`tasks\`.

## Authentication

Authentication is handled via Laravel Sanctum. Users must register and log in to receive a token, which should be
included in the \`Authorization\` header of all requests requiring authentication.

- `POST /api/register`: Register a new user.
- `POST /api/login`: Log in a user and receive an authentication token.
- `POST /api/logout`: Log out the authenticated user and invalidate the token.

### TaskLists

- `GET /api/task-lists`: Retrieve all task lists.
- `POST /api/task-lists`: Create a new task list.
- `GET /api/task-lists/{taskList}`: Retrieve a specific task list.
- `PUT /api/task-lists/{taskList}`: Update a specific task list.
- `DELETE /api/task-lists/{taskList}`: Delete a specific task list.

### Tasks

- `GET /api/tasks`: Retrieve all tasks for the authenticated user.
- `POST /api/tasks`: Create a new task.
- `GET /api/tasks/{task}`: Retrieve a specific task.
- `PUT /api/tasks/{task}`: Update a specific task.
- `DELETE /api/tasks/{task}`: Delete a specific task.

## Admin Panel

The admin panel API provides endpoints for managing administrative tasks, including user management and application
oversight.

### User Management

- `GET /api/admin/users`: Retrieve a list of all users (admin access required).
- `POST /api/admin/users`: Create a new user (admin access required).
- `GET /api/admin/users/{user}`: Retrieve details of a specific user (admin access required).
- `PUT /api/admin/users/{user}`: Update a specific user’s details (admin access required).
- `DELETE /api/admin/users/{user}`: Delete a specific user (admin access required).

### TaskLists

- `GET /api/admin/task-lists`: Retrieve all task lists.(admin access required).
- `POST /api/admin/task-lists`: Create a new task list.(admin access required).
- `GET /api/admin/task-lists/{taskList}`: Retrieve a specific task list.(admin access required).
- `PUT /api/admin/task-lists/{taskList}`: Update a specific task list.(admin access required).
- `DELETE /api/admin/task-lists/{taskList}`: Delete a specific task list.(admin access required).

### Tasks

- `GET /api/admin/tasks`: Retrieve all tasks for the authenticated user(admin access required).
- `POST /api/admin/tasks`: Create a new task(admin access required).
- `GET /api/admin/tasks/{task}`: Retrieve a specific task(admin access required).
- `PUT /api/admin/tasks/{task}`: Update a specific task(admin access required).
- `DELETE /api/admin/tasks/{task}`: Delete a specific task(admin access required).

## Testing

To run the tests, use:
`bash php artisan test`

Ensure all tests pass before making any pull requests.

[//]: # (## Contributing)

[//]: # ()

[//]: # (Contributions are welcome! Please follow these steps:)

[//]: # ()

[//]: # (1. Fork the repository.)

[//]: # (2. Create a new branch &#40;\`git checkout -b feature/your-feature-name\`&#41;.)

[//]: # (3. Commit your changes &#40;\`git commit -am 'Add some feature'\`&#41;.)

[//]: # (4. Push to the branch &#40;\`git push origin feature/your-feature-name\`&#41;.)

[//]: # (5. Create a new Pull Request.)

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for more details.
