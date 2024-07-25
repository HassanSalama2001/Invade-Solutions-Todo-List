# Invade Solutions Todo List

A powerful task management application built with Laravel 11 and Vue.js 3. This application allows users to register, login, manage tasks, and categorize them efficiently. 

## Features

- **User Authentication**: Register and login to access the application.
- **Task Management**:
  - **Create**: Add new tasks with details and due dates.
  - **Edit**: Update task details, including status (Pending, Completed, Overdue).
  - **Soft Delete**: Mark tasks as deleted and retrieve them if needed.
  - **Filter Tasks**: 
    - By deletion status (Deleted, Not Deleted, All)
    - By category
    - By status
  - **Search Tasks**: Find tasks by title or description.
- **Category Management**: Create and assign categories to tasks.
- **Access Control**: Secure access to the tasks page requiring authentication.

## Getting Started

To set up and run the project locally, follow these steps:

### 1. Clone the Repository

Clone the repository to your local machine:

```bash
git clone https://github.com/HassanSalama2001/Invade-Solutions-Todo-List.git
cd Invade-Solutions-Todo-List
```

### 2. Set Up Your Environment

Start your local server (e.g., XAMPP).

### 3. Install Dependencies

Run the following commands in your project folder:

```bash
composer install
npm install
```

### 4. Set Up Environment Variables

Create a copy of the environment file:

```bash
cp .env.example .env
```

Generate the application key:

```bash
php artisan key:generate
```

### 5. Set Up Database and Authentication

Run migrations and seed the database:

```bash
php artisan migrate:fresh --seed
```

Install and configure Laravel Passport:

```bash
php artisan passport:install
php artisan passport:keys --force
```

### 6. Compile Assets

Run the asset watcher:

```bash
npm run watch
```

### 7. Start the Application

Start the Laravel development server:

```bash
php artisan serve
```

The application should now be accessible at http://localhost:8000.

## RESTful API Endpoints

The application provides the following API endpoints for task and category management:

### Tasks

- **Get All Tasks**: `GET /api/tasks`
- **Store New Task**: `POST /api/task/store`
- **Update Task**: `PUT /api/task/{task_id}`
- **Delete Task**: `DELETE /api/task/{task_id}`
- **Retrieve Task**: `POST /api/task/retrieve/{task_id}`

### Categories

- **Get All Categories**: `GET /api/categories`
- **Store New Category**: `POST /api/category/store`

## Contributing

If you wish to contribute to this project, please fork the repository and submit a pull request with your changes.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
