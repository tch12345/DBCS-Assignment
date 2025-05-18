# User Management System – Database Project

## Course
**UGRD-FCI-2510**  
**CCS6344 – Database and Cloud Security**

## Overview
This project is a **User Management System** designed to demonstrate secure handling of user data in a database environment. It supports features such as user creation, editing, role management, and secure access control with role-based restrictions (e.g., Admin, User).

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


## Setup Instructions
1. **Import Database:**
   - Use SQL Server Management Studio (SSMS)

2. **Configure Connection:**
   - Update the `Config/connect.php` file with your SQL Server credentials

3. **Run Application:**
   - Host PHP files on a server (e.g., XAMPP, IIS, or WAMP)
   - run localhost in browser and start

## Security Notes
- This system uses **parameterized queries** to prevent SQL Injection.
- Admin role assignment is restricted and validated using `md5(1)` to simulate secure session handling.
- Passwords are stored in plain text for demo purposes – replace with proper hashing (e.g., `password_hash()`) in production.


## License
This project is for **academic use only** under CCS6344 course requirements.
