# Laravel AI Task Management System

A production-ready, senior-level task management application built with Laravel 12+, strictly following clean architecture principles, Repository Pattern, Service Layer, and featuring AI integration for task summaries and priority inference.

## Features

- **Clean Architecture:** Strict separation of concerns (Repositories, Services, Form Requests, Policies, Resources).
- **Role-Based Access Control (RBAC):** Admin (full control, dashboard access) and User (only manage assigned tasks).
- **AI Integration (OpenAI/Gemini):** Automatically generates task summaries and suggests priorities based on task parameters. Gracefully falls back to a mock system if API keys are missing.
- **REST APIs:** Full API support using API Resources with proper status codes.
- **Modern UI:** Responsive, custom Tailwind CSS implementation tailored for a sleek admin dashboard feel.
- **Analytics:** Chart.js integration for dashboard metrics.

---

## Tech Stack

- **Framework:** Laravel 12.x / PHP 8.2+
- **Database:** MySQL
- **Templating:** Blade + Tailwind CSS v3
- **Authentication:** Laravel Breeze
- **AI Integration:** OpenAI PHP Client (`openai-php/client`)
- **Charts:** Chart.js

---

## Architecture Overview

This application structure avoids "Fat Controllers" by enforcing strict boundaries:

1.  **Controllers:** Thin coordinators. They receive the Request, authorize via `TaskPolicy`, validate via Form Requests (`StoreTaskRequest`, etc.), pass data to the Service Layer, and return a view or API response. No direct Eloquent calls live here.
2.  **Service Layer (`TaskService` / `AIService`):** Encapsulates business logic. `TaskService` wraps operations in database transactions and coordinates with the Repository and `AIService`.
3.  **Repository Pattern (`TaskRepositoryInterface` -> `TaskRepository`):** Handles all database access and complex Eloquent queries (using local scopes mapped from the `Task` model). Paginations and eager-loading live here.
4.  **Enums (`TaskStatusEnum`, `TaskPriorityEnum`, `UserRoleEnum`):** Type-safe value handling with helper methods for UI badges.
5.  **Policies (`TaskPolicy`, `AdminMiddleware`):** Enforce strict role validation.

---

## System Requirements

- PHP = 8.2
- Composer
- Node.js & npm
- MySQL

## Setup & Installation

1.  **Clone the repository / Navigate to folder**
    ```bash
    cd task-manager
    ```

2.  **Install PHP Dependencies**
    ```bash
    composer install
    ```

3.  **Install Node Dependencies**
    ```bash
    npm install && npm run build
    ```

4.  **Environment Setup**
    Copy `.env.example` to `.env` (it has been pre-configured for this project):
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

    Ensure your `.env` contains:
    ```env
    DB_CONNECTION=mysql
    DB_DATABASE=aromal
    DB_USERNAME=root
    DB_PASSWORD=

    # AI Configuration Setup (Optional)
    AI_PROVIDER=openai
    OPENAI_API_KEY=your_openai_api_key
    ```

5.  **Run Migrations & Seeders**
    This will create the database schema, 1 Admin User, 5 Normal Users, and sample Tasks.
    ```bash
    php artisan migrate:fresh --seed
    ```

6.  **Serve the Application**
    ```bash
    php artisan serve
    ```

---

## Authentication & Roles

The system is pre-seeded with:

**Admin User:**
- **Email:** `admin@example.com`
- **Password:** `password`
- *Access:* Can view Dashboard, view all tasks, create, edit, delete, and reassign tasks.

**Normal Users:**
- Created dynamically by the seeder (e.g., `user1@example.com`).
- *Access:* Cannot access the dashboard. Can only view and update tasks specifically assigned to them. Cannot create or delete tasks.

---

## AI Integration Details

### How it works
When a new task is created through `TaskService::createTask()`, the service dispatches a call to `AIService::generateSummary()`.

### The Prompt
The AI service builds the following structured prompt:
```text
Please analyze the following task and provide a concise, professional summary and suggest a priority level based on the wording and urgency.

Task Title: {title}
Task Description: {description}
Current Priority: {priority}
Due Date: {due_date}

Return EXACTLY a JSON object in this format:
{
  "ai_summary": "A concise 1-2 sentence summary of the task.",
  "ai_priority": "low|medium|high"
}
```

### Mock Fallback
If `OPENAI_API_KEY` is not present, or if an API exception occurs (e.g., rate limit), the system catches the error and gracefully falls back to a deterministic mock generator. This ensures the UI never breaks.

---

## API Endpoints

All API endpoints are prefixed with `/api` and require Sanctum authentication (cookies or bearer token).

| Method | Endpoint | Description | Payloads/Responses |
| :--- | :--- | :--- | :--- |
| **GET** | `/api/tasks` | Get paginated tasks (filtered) | Filter by `status`, `priority`, `search` |
| **POST** | `/api/tasks` | Create task (Admin only) | Requires `title`, `priority`, `status`, `due_date`, `assigned_to` |
| **PATCH** | `/api/tasks/{id}/status` | Update task status | `{ "status": "completed" }` |
| **GET** | `/api/tasks/{id}/ai-summary` | Get generated AI Insights | Returns `ai_summary` and `ai_priority` |

Responses leverage `TaskResource` for structured JSON output.

---

## Author
Developed as a Machine Test demonstration of Senior Laravel Architecture.
