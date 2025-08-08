PHP Final Project

Overview

This is a responsive PHP CRUD application built for a college Computer Programming course. It demonstrates best practices in PHP development, including:

User authentication with password hashing and avatar uploads

Role-based admin management

Content creation and management with full CRUD operations

Modern, semantic HTML5 and CSS3 styling with Bootstrap integration

Secure database interactions using PDO with prepared statements

Features

User Authentication: Register, Login, Logout with hashed passwords and optional avatar upload.

Administrator Management: View, edit, and delete admin users (done via a secure CRUD interface).

Content Management: Create, read, update, and delete website content (articles) with semantic structure and date formatting.

Responsive Design: Mobile-friendly navigation with collapsible Bootstrap navbar and custom CSS cards.

Security: Input validation, output escaping (htmlspecialchars()), strict file-type validation on image uploads, and use of prepared statements.



Prerequisites

PHP 8.0 or higher

MySQL 5.7 or higher

Web server (Apache via XAMPP, WAMP, or MAMP)

Git (for version control)

Installation

Clone the repository

git clone https://github.com/<your-username>/php-final-project.git
cd php-final-project

Install XAMPP/WAMP/MAMP and start Apache + MySQL.

Place the project folder inside your web server root (htdocs or www).

Open your terminal in the project folder.

Database Setup

Open phpMyAdmin at http://localhost/phpmyadmin.

Create a database named php_final_project.

Import the schema:

Go to the Import tab.

Choose the file sql/schema.sql and click Go.

Configuration

Database credentials are located in includes/db.php. By default, it uses:

$host = '127.0.0.1';
$dbname = 'php_final_project';
$user = 'root';
$pass = '';

If you have a custom MySQL user/password, update those variables accordingly.

Usage

Navigate to http://localhost/php-final-project/index.php.

Register a new admin account via Register link.

Login using your email and password.

Access Manage Users to edit or delete admins.

Browse Content, create new posts, edit existing ones, or delete as needed.
