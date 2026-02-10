# Restaurant POS API (Backend)

<img width="1895" height="882" alt="Screenshot 2026-02-10 203458" src="https://github.com/user-attachments/assets/1b192e8d-9bdc-4d69-b3d8-3e492bba6dbb" />


The robust, decoupled backend for the Restaurant POS System. Built with Laravel 11 and MySQL, this API utilizes a Service Layer Architecture to handle complex business logic, atomic transactions, and strict Role-Based Access Control (RBAC).

## Tech Stack

Framework: Laravel 11

Language: PHP 8.2+

Database: MySQL

Authentication: Laravel Sanctum (SPA Auth)

Permissions: Spatie Laravel Permission

PDF Generation: Barryvdh DomPDF

Architecture: Service Repository/Layer Pattern

## Key Features

1. Robust Architecture

Service Layer: Business logic (calculations, inventory checks) is isolated in OrderService and TableService, keeping Controllers lean.
Database Transactions: Critical actions (Opening/Closing orders) are wrapped in atomic transactions to ensure data integrity.
Optimized Queries: Uses Eager Loading to prevent N+1 performance issues on large datasets.

2. Role-Based Access Control (RBAC)

Middleware Protection: Routes are strictly protected using Spatie Middleware (role:waiter, role:cashier).
Granular Permissions:

Waiters: Can Create/Update/Delete order items and Manage Food Menu.
Cashiers: Restricted to finalizing payments and generating receipts.



3. API Capabilities

PDF Receipt Generation: Server-side rendering of thermal-printer-friendly receipts using DomPDF.
Case-Insensitive Search: Advanced filtering logic for tables and orders.
Validation: Strict FormRequests for all inputs (e.g., negative quantity prevention).

## Prerequisites
Ensure you have the following installed:

PHP: v8.2 or higher

Composer: Latest version

MySQL: Running and accessible

## Installation Guide

Follow these steps to get your local API server up and running.

1. Clone the Repository
First, clone the project to your local machine :
```
git clone https://github.com/krisnadwinugraha/restaurant-api.git
```
2. Navigate into the directory:
```
cd restaurant-api
```

3. Install Dependencies
Use Composer to install the PHP packages:
```
composer install
```
4. Configure Environment
Create a .env file in the root directory. You can quickly copy the example file provided:
```
cp .env.example .env
```
Open the .env file and configure your database credentials:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=restaurant_db
DB_USERNAME=root
DB_PASSWORD=
```
5. Migrations & Seeders
Crucial Step: This command creates the database structure and populates it with the required Roles (Waiter, Cashier) and Demo Users.
```
php artisan migrate:fresh --seed
```
6. Start the Server
```
php artisan serve
```

The API will be available at `http://127.0.0.1:8000`.

## 📂 Project Structure
```
app/
├── Http/
│   ├── Controllers/    # Request handling (Calls Services)
│   ├── Middleware/     # Role verification
│   ├── Requests/       # Validation logic
│   └── Resources/      # API JSON transformers
├── Models/             # Eloquent models (Order, Food, Table)
├── Services/           # Business Logic (OrderService, TableService)
routes/
└── api.php             # API Route definitions
```

## Demo Credentials

Use these accounts to test the frontend application:
### Role
Waiter
Email
```
Waiterwaiter@restaurant.com
```
Password
```
password
```

Manage Orders & Menu

### Cashier
Email
```
cashier@restaurant.com
```
Password
```
password
```

## Screenshots

PDF Receipt Output
<img width="457" height="800" alt="Screenshot 2026-02-10 203939" src="https://github.com/user-attachments/assets/6090ea13-852f-448c-b0ed-f05e6a69dca1" />


## Testing
To run the backend feature tests:
```
php artisan test
```

##Related Repositories

Frontend Client: [Restaurant POS Client](https://github.com/krisnadwinugraha/restaurant-client)

## 🤝 Contributing
Contributions, issues, and feature requests are welcome! Feel free to check the issues page.
## 📝 License
This project is MIT licensed.
## 👤 Author
Krisna

<p align="center">Made with ❤️ using Laravel & PHP</p>
