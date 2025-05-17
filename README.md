# User Management System – Database Project

## Course
**UGRD-FCI-2510**  
**CCS6344 – Database and Cloud Security**

## Overview
This project is a **User Management System** designed to demonstrate secure handling of user data in a database environment. It supports features such as user creation, editing, role management, and secure access control with role-based restrictions (e.g., Admin, Finance, User).

## Features
- User Registration and Role Assignment
- Admin Controls for Adding/Editing Users
- Role-based Permissions (Admin, User, Finance)
- SweetAlert for user-friendly confirmations and alerts
- Integration with Microsoft SQL Server (SSMS)
- Session-based login verification
- Basic input validation and email uniqueness enforcement

## Technologies Used
- **Frontend:** HTML, Bootstrap 5, JavaScript, SweetAlert2
- **Backend:** PHP 8+
- **Database:** Microsoft SQL Server (SSMS)
- **Session Handling:** PHP Sessions
- **Security Measures:**
  - Input validation
  - Role-based access checks
  - SQL parameterization to prevent SQL Injection
  - Admin privilege control via session hash comparison

## Table: `users`

| Column Name | Data Type         | Description                        |
|-------------|------------------|------------------------------------|
| user_id     | INT (PK, IDENTITY) | Unique auto-increment user ID     |
| name        | VARCHAR(100)     | Full name of the user              |
| email       | VARCHAR(255)     | Email address (must be unique)     |
| password    | VARCHAR(255)     | Encrypted password (plaintext in demo) |
| phone       | VARCHAR(20)      | User's contact number              |
| created_at  | DATETIME         | Account creation timestamp         |
| deleted_at  | DATETIME (NULL)  | Deletion timestamp (if soft-deleted) |
| status      | INT              | Account status (0 = active, 1 = inactive) |
| role        | VARCHAR(20)      | User role (admin, user, finance)   |
| username    | VARCHAR(50)      | Optional username field            |

## Setup Instructions
1. **Import Database:**
   - Use SQL Server Management Studio (SSMS)
   - Execute the provided SQL schema or manually create the `users` table based on the structure above

2. **Configure Connection:**
   - Update the `Config/connect.php` file with your SQL Server credentials

3. **Run Application:**
   - Host PHP files on a server (e.g., XAMPP, IIS, or WAMP)
   - Open `login2.0.php` in your browser to start

## Security Notes
- This system uses **parameterized queries** to prevent SQL Injection.
- Admin role assignment is restricted and validated using `md5(1)` to simulate secure session handling.
- Passwords are stored in plain text for demo purposes – replace with proper hashing (e.g., `password_hash()`) in production.

## Author
- **tan**
- Student ID: *-*
- University: *-*

## License
This project is for **academic use only** under CCS6344 course requirements.
