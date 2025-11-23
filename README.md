# 📝 Task Manager System

A lightweight, secure task management system built with PHP, MySQL, and Bootstrap 5. This application demonstrates modern web development practices including secure authentication, CRUD operations, and responsive design.

![PHP](https://img.shields.io/badge/PHP-8.0+-777BB4?style=flat&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-5.7+-4479A1?style=flat&logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=flat&logo=bootstrap&logoColor=white)

## ✨ Features

### 🔐 Security
- **User Authentication**: Secure registration and login system
- **Password Hashing**: Uses PHP's `password_hash()` with bcrypt algorithm
- **SQL Injection Prevention**: PDO prepared statements throughout
- **Session Management**: Secure session handling
- **Input Validation**: Server-side validation for all user inputs

### 📋 Task Management
- **Create Tasks**: Add new tasks with title and description
- **Edit Tasks**: Update existing task information
- **Delete Tasks**: Remove tasks with confirmation
- **Mark as Complete**: Toggle task completion status
- **Task Statistics**: View total, completed, and incomplete task counts

### 🔍 Advanced Features
- **Search Functionality**: Search tasks by title or description
- **Filter Tasks**: Filter by all, completed, or incomplete tasks
- **Responsive Design**: Mobile-first Bootstrap 5 interface
- **Real-time Updates**: AJAX-powered task operations
- **User-Specific Data**: Each user sees only their own tasks

## 🚀 Getting Started

### Prerequisites

- **XAMPP** (or any LAMP/WAMP stack)
  - PHP 7.4 or higher
  - MySQL 5.7 or higher
  - Apache Web Server

### Installation

1. **Clone or Download the Project**
   ```bash
   cd c:\xampp\htdocs
   git clone <your-repo-url> task-manager-system
   ```
   Or simply copy the `task-manager-system` folder to `c:\xampp\htdocs\`

2. **Start XAMPP**
   - Open XAMPP Control Panel
   - Start **Apache** and **MySQL** services

3. **Create Database**
   - Open phpMyAdmin: `http://localhost/phpmyadmin`
   - Click "New" to create a new database
   - Name it `task_manager`
   - Go to the "Import" tab
   - Choose the `database.sql` file from the project root
   - Click "Go" to import the tables

   **OR** run the SQL manually:
   ```sql
   CREATE DATABASE IF NOT EXISTS task_manager;
   USE task_manager;
   -- Then copy and paste the contents of database.sql
   ```

4. **Configure Database Connection**
   
   Open `config/database.php` and update the credentials if needed:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'task_manager');
   define('DB_USER', 'root');        // Your MySQL username
   define('DB_PASS', '');            // Your MySQL password
   ```

5. **Access the Application**
   
   Open your browser and navigate to:
   ```
   http://localhost/task-manager-system
   ```

## 📱 Usage

### Registration
1. Navigate to `http://localhost/task-manager-system/register.php`
2. Fill in username (min 3 characters), email, and password (min 6 characters)
3. Click "Register"

### Login
1. Navigate to `http://localhost/task-manager-system/login.php`
2. Enter your username and password
3. Click "Login"

### Managing Tasks
- **Add Task**: Click "Add New Task" button on dashboard
- **Edit Task**: Click "Edit" button on any task card
- **Complete Task**: Click "Complete" button to mark as done
- **Delete Task**: Click "Delete" button (with confirmation)
- **Search**: Use the search bar to find specific tasks
- **Filter**: Use the tabs to filter by all/incomplete/completed

## 📁 Project Structure

```
task-manager-system/
├── config/
│   ├── database.php          # Database connection configuration
│   └── session.php           # Session management functions
├── includes/
│   ├── User.php              # User authentication class
│   └── Task.php              # Task CRUD operations class
├── index.php                 # Landing page (redirects to login/dashboard)
├── login.php                 # Login page
├── register.php              # Registration page
├── logout.php                # Logout handler
├── dashboard.php             # Main dashboard with task list
├── add_task.php              # Add new task page
├── edit_task.php             # Edit task page
├── database.sql              # Database schema
└── README.md                 # This file
```

## 🛠️ Technologies Used

### Backend
- **PHP**: Server-side scripting
- **MySQL**: Database management
- **PDO**: Database abstraction layer with prepared statements

### Frontend
- **Bootstrap 5**: Responsive CSS framework
- **Bootstrap Icons**: Icon library
- **JavaScript (Vanilla)**: AJAX functionality for dynamic updates

### Security Features
- Password hashing with `password_hash()` and `password_verify()`
- PDO prepared statements to prevent SQL injection
- Session-based authentication
- Input validation and sanitization
- XSS prevention with `htmlspecialchars()`

## 🎯 Key Learning Points

This project demonstrates:

✅ **Secure Coding Practices**
- Password hashing and verification
- SQL injection prevention with prepared statements
- XSS prevention
- Session management

✅ **Object-Oriented PHP**
- Classes for User and Task management
- Separation of concerns
- Reusable code structure

✅ **Database Design**
- Normalized database schema
- Foreign key relationships
- Proper indexing

✅ **Modern UI/UX**
- Mobile-responsive design
- Intuitive interface
- Real-time feedback

## 🔒 Security Features

1. **Password Security**: Bcrypt hashing with salt
2. **SQL Injection Prevention**: PDO prepared statements
3. **XSS Prevention**: Output escaping with `htmlspecialchars()`
4. **Session Security**: Proper session management
5. **Access Control**: User-specific data isolation

## 🌐 Browser Compatibility

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

## 📝 Database Schema

### Users Table
```sql
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- username (VARCHAR 50, UNIQUE)
- email (VARCHAR 100, UNIQUE)
- password (VARCHAR 255, hashed)
- created_at (TIMESTAMP)
```

### Tasks Table
```sql
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- user_id (INT, FOREIGN KEY -> users.id)
- title (VARCHAR 255)
- description (TEXT)
- is_completed (TINYINT 0/1)
- created_at (TIMESTAMP)
- updated_at (TIMESTAMP)
```

## 🚧 Future Enhancements

- Task categories/tags
- Task priority levels
- Due dates and reminders
- Task sharing between users
- Email notifications
- Dark mode
- Export tasks (PDF/CSV)
- Task attachments

## 🤝 Contributing

This is a learning project, but suggestions and improvements are welcome! Feel free to fork and submit pull requests.

## 📄 License

This project is open source and available for educational purposes.

## 👨‍💻 Author

Created as a portfolio project to demonstrate full-stack web development skills.

---

### 📸 Screenshots

**Login Page**
- Clean, modern authentication interface
- Secure login with password hashing

**Dashboard**
- Task statistics at a glance
- Filter and search functionality
- Responsive card layout

**Task Management**
- Easy-to-use CRUD operations
- Real-time status updates
- Mobile-friendly interface

---

⭐ **Star this repository** if you found it helpful!

💡 **Perfect for**: Portfolio projects, learning PHP/MySQL, understanding secure web development practices
