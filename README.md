# Meal Planner System

Welcome to the Meal Planner System! This project allows users to add and view recipes, and get meal recommendations based on their preferences and dietary restrictions.

## Features

- **User Authentication**: Secure login and session management.
- **Add Recipes**: Users can add their own recipes to the system.
- **View Recipes**: View all recipes added by users.
- **Personalized Meal Recommendations**: Get meal suggestions for breakfast, lunch, and dinner based on user preferences and dietary restrictions.

## Technologies Used

- **PHP**: Server-side scripting language.
- **MySQL**: Database management system.
- **HTML/CSS**: Frontend design and layout.
- **JavaScript**: For dynamic content (in future enhancements).

## Installation

### Prerequisites

- PHP >= 7.4
- MySQL >= 5.7
- A web server like Apache or Nginx

### Setup

1. **Clone the Repository**

   ```bash
   git clone https://github.com/souchan25/MealPlanner.git
   cd meal-planner

2. Configure Database

Update the database.php file with your database credentials. Ensure your MySQL database is set up with the required tables. Use the provided SQL scripts or manually create the tables as needed.
```
<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "userDB";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

?>
```
3. Set Up Database

Create the necessary database tables using the following SQL schema:

```CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE recipes (
    recipe_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    recipe_name VARCHAR(255) NOT NULL,
    description TEXT,
    ingredients TEXT,
    instructions TEXT,
    meal_type ENUM('breakfast', 'lunch', 'dinner'),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

CREATE TABLE userpreferences (
    user_id INT PRIMARY KEY,
    preferred_cuisine VARCHAR(255),
    excluded_ingredients TEXT,
    allergies TEXT,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);
```

4. Start Your Server

Ensure your web server is running and navigate to http://localhost/meal-planner/ in your web browser to access the application.

Usage

1. Register/Login: Navigate to the login page to access your account. New users can register from the login page.
2. Add Recipes: Use the "Add Recipe" page to submit new recipes including name, ingredients, and instructions.
3. View Recipes: Access the "All Recipes" page to view all recipes added by users.
   
Get Recommendations: View meal recommendations for breakfast, lunch, and dinner based on your preferences and dietary restrictions.

Contributing

If you'd like to contribute to the project, please fork the repository and create a pull request with your proposed changes. For significant changes, please open an issue to discuss the changes first.

Contact
For any inquiries, please contact eugenepausa@gmail.com
