# Portfolio

Welcome to **Portfolio**! 🌟  

This project is a basic example of creating a personal portfolio using **PHP** and **MySQL** to render data from a database into a web page. The code is fully open-source and **reusable**, so you can easily adapt it for your own projects.  

## Features

- Dynamic rendering of data from a MySQL database.  
- Simple and structured PHP code, ideal for learning or reusing.  
- Included CSS styles for a clean and functional design.  
- Fully open-source and reusable; you only need to set up your own database.  

## Requirements

- PHP >= 7.4  
- MySQL or MariaDB  
- Local server (XAMPP, WAMP, MAMP, LAMP, etc.)  
- Modern web browser  

## Installation

1. Clone the repository:

```bash
git clone https://github.com/RafaelElebiyo/portfolio
```
Copy the files to your local server directory (e.g., htdocs in XAMPP).

Create a MySQL database, for example portfolio_db.

Import the database structure from database.sql (if you include a SQL example file).

Configure the database connection in config.php:

```
<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "portfolio_db";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
```
Open your browser and go to http://localhost/portfolio.

###Usage
Add, edit, or remove projects, skills, or other information directly in the database.

The front-end will automatically display the changes.

You can reuse the file structure and styles to create your own custom portfolio.

###Contributing
Contributions are welcome! You can:

Improve the code structure.

Add new features.

Update styles and design.

