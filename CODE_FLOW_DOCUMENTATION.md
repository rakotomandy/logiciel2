# Logiciel2 Application - Code Flow Documentation

## Table of Contents
1. [Application Overview](#application-overview)
2. [Architecture](#architecture)
3. [Request Flow](#request-flow)
4. [File Structure](#file-structure)
5. [Key Components](#key-components)
6. [Data Flow](#data-flow)

---

## Application Overview

**Logiciel2** is a PHP-based web application built on an MVC (Model-View-Controller) architecture. The application manages user authentication, user management, and provides a dashboard interface.

**Technology Stack:**
- **Backend:** PHP 7+
- **Database:** MySQL
- **Frontend:** HTML, CSS, JavaScript (jQuery)
- **Additional Libraries:** DataTables, DOMPDF, Bootstrap

---

## Architecture

This application follows the **MVC Pattern**:

```
┌─────────────────────────────────────────────────┐
│           User Request (Browser)                │
└────────────────────┬────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────┐
│   index.php (Entry Point)                       │
│   - Checks for ?action parameter                │
│   - Routes request to Root::connect()           │
└────────────────────┬────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────┐
│   Core/Root.php (Router)                        │
│   - Parses URL                                  │
│   - Determines Controller & Method              │
│   - Loads appropriate Controller                │
└────────────────────┬────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────┐
│   Controller (Home/Login/User)                  │
│   - Handles request logic                       │
│   - Calls Model if needed                       │
│   - Prepares data for View                      │
└────────────────────┬────────────────────────────┘
                     │
                ┌────┴────┐
                ▼         ▼
          ┌──────────┐  ┌──────────────┐
          │ Model    │  │ Load::view() │
          │(Business)│  │ (Template)   │
          │ Logic    │  │              │
          └────┬─────┘  └────┬─────────┘
               │             │
               ▼             ▼
          ┌──────────────────────────┐
          │ Query (Database Access)  │
          │ - PDO Queries            │
          │ - Prepared Statements    │
          └────────┬─────────────────┘
                   │
                   ▼
          ┌─────────────────┐
          │  MySQL Database │
          └─────────────────┘
```

---

## Request Flow

### Step 1: Initial Request
- User accesses: `http://localhost/logiciel/?action=Home`
- Request reaches **index.php**

### Step 2: Route Processing
- **index.php** checks for `$_GET["action"]` parameter
- Calls `Root::connect("Home")`
- **Core/Root.php** parses the URL:
  - First segment (`Home`) = Controller class name
  - Second segment (if exists) = Method name
  - Additional segments = Parameters

### Step 3: Controller Execution
- Loads the appropriate Controller class (e.g., `Home.php`)
- Calls the requested method (e.g., `index()`)
- Controller processes business logic

### Step 4: View Rendering
- Controller calls `Load::template()` for header
- Controller calls `Load::view()` for main content
- Controller calls `Load::template()` for footer
- View files are included with passed data

### Step 5: Response
- HTML is rendered and sent to browser
- JavaScript and CSS files are linked in templates

---

## File Structure

### Core Files (`/Core/`)

| File | Purpose |
|------|---------|
| **autoload.php** | Auto-loads classes from Controller, Model, and Core directories |
| **Root.php** | Main router - parses URLs and dispatches to controllers |
| **Load.php** | Template and asset loader - manages views, CSS, and JS |
| **Query.php** | Database query builder with PDO prepared statements |
| **Database.php** | Database and table creation (one-time setup) |

### Controller Files (`/Controller/`)

| File | Purpose |
|------|---------|
| **Home.php** | Handles home page display |
| **Login.php** | Handles login page display |
| **User.php** | Handles user management and dashboard |

### Model Files (`/Model/`)

| File | Purpose |
|------|---------|
| **LoginModel.php** | Business logic for user authentication and registration |

### View Files (`/View/`)

| File | Purpose |
|------|---------|
| **home.php** | Home page template |
| **login.php** | Login page template |
| **user.php** | User management page template |
| **template/header.php** | Common header section |
| **template/footer.php** | Common footer section |
| **template/sideAdmin.php** | Admin sidebar navigation |

### Public Assets (`/Public/`)

| Folder | Contents |
|--------|----------|
| **css/** | Stylesheets for different pages |
| **js/** | JavaScript files for functionality |
| **js/reusable/** | Reusable libraries (jQuery, Bootstrap, SweetAlert) |

---

## Key Components

### 1. Entry Point: index.php
```php
<?php
require_once "Core/autoload.php";

if(isset($_GET["action"])){
    Root::connect($_GET["action"]);  // Route the request
}else{
    echo "<h1> NOT FOUND</h1>";
}
```
- Starts the application
- Requires autoload for automatic class loading
- Checks for `action` parameter in URL
- Routes to Root::connect() if action exists

---

### 2. Router: Core/Root.php
**Purpose:** Parse URLs and dispatch to appropriate controller

**URL Format:**
- `/Home` → Calls `Home::index()`
- `/Home/show` → Calls `Home::show()`
- `/User/show/5` → Calls `User::show(5)`
- `/User/show/5?id=10` → Calls `User::show(5, ['id'=>10])`

**Process:**
1. Sanitizes URL (XSS protection)
2. Splits URL by `/` into segments
3. First segment = Controller class name
4. Second segment (optional) = Method name
5. Remaining segments = Method parameters
6. Uses Reflection API to dynamically invoke methods

---

### 3. Template Loader: Core/Load.php
**Purpose:** Load views and inject assets

**Methods:**
- `Load::view($view, $data)` - Load a view file from /View/
- `Load::template($template, $data)` - Load a template from /View/template/
- `Load::css($cssArray)` - Link CSS files
- `Load::js($jsArray)` - Link JavaScript files

**Data Injection:**
- Data array is converted to variables in the view scope
- Example: `['title' => 'Home']` becomes `$title = 'Home'` in view

---

### 4. Database Query: Core/Query.php
**Purpose:** Handle database operations with prepared statements

**Methods:**
- `Query::connect()` - Set database connection details
- `custom($query, $type)` - Set custom SQL query
- `execute($params)` - Execute query with parameters

**Security:**
- Uses PDO prepared statements (SQL injection protection)
- Parameters are passed separately from SQL query

**Example:**
```php
$data = $query->custom("SELECT * FROM user WHERE EMAIL=?", "select")
              ->execute([$email]);
```

---

### 5. Model: LoginModel.php
**Purpose:** Business logic for authentication

**Methods:**
- `login($email, $pwd)` - Verify user credentials
- `insertUser(...)` - Register new user

**Features:**
- Password hashing with PASSWORD_DEFAULT
- Password verification using password_verify()
- Duplicate email checking

---

### 6. Controllers
**Home Controller:**
- Displays homepage
- Loads header, home view, and footer

**Login Controller:**
- Displays login page
- Loads login-specific CSS and JavaScript

**User Controller:**
- Displays user management dashboard
- Includes admin sidebar
- Provides insert() method for AJAX requests

---

## Data Flow Examples

### Example 1: Accessing Home Page
```
URL: http://localhost/logiciel/?action=Home
│
├─ index.php checks $_GET["action"]
│
├─ Root::connect("Home")
│  ├─ Sanitizes URL
│  ├─ Splits: parts[0] = "Home"
│  ├─ Checks if Controller/Home.php exists
│  ├─ Requires Controller/Home.php
│  ├─ Calls Home::index()
│
└─ Home::index()
   ├─ Load::template("header", [...])
   │  └─ Includes View/template/header.php
   │  └─ Sets $title, loads CSS files
   │
   ├─ Load::view("home")
   │  └─ Includes View/home.php
   │
   └─ Load::template("footer", [...])
      └─ Includes View/template/footer.php
      └─ Loads JavaScript files
```

### Example 2: User Login
```
URL: http://localhost/logiciel/?action=Login
│
├─ Root::connect("Login")
│  └─ Calls Login::index()
│
└─ Login::index()
   ├─ Loads login template with CSS
   └─ Loads login view
      └─ HTML form with JavaScript for submission
      
(Form submitted via AJAX)
│
├─ LoginModel::login($email, $password)
│  ├─ Query: SELECT * FROM user WHERE EMAIL=?
│  ├─ Verify password_hash
│  └─ Return "match" or "mismatch"
│
└─ JavaScript processes result
   ├─ Show success/error message
   └─ Redirect if successful
```

### Example 3: User Management
```
URL: http://localhost/logiciel/?action=User
│
├─ Root::connect("User")
│  └─ Calls User::index()
│
└─ User::index()
   ├─ Load header with admin CSS
   ├─ Load sidebar navigation
   ├─ Load user management view
   └─ Load footer with JavaScript
      └─ Includes insert.js for AJAX operations
      
(Insert user via AJAX)
│
├─ User::insert()
│  └─ Returns JSON: {"status": "success"}
│
└─ JavaScript processes JSON response
```

---

## Configuration & Setup

### Database Connection (Core/autoload.php)
```php
define("URL","http://localhost/logiciel/");
```
- Base URL for asset loading (CSS, JS)

### Database Credentials (Core/Query.php)
```php
Query::connect("localhost", "logiciel", "root", "");
```
- Host: localhost
- Database: logiciel
- User: root
- Password: (empty)

---

## Security Features

1. **XSS Protection:** URLs are sanitized with `htmlspecialchars()`
2. **SQL Injection Protection:** Prepared statements with parameter binding
3. **Password Security:** PASSWORD_DEFAULT hashing and verification
4. **Session Management:** Session started in autoload.php

---

## Potential Improvements

1. **Environment Configuration:** Move database credentials to .env file
2. **Error Handling:** Implement try-catch blocks and error logging
3. **Validation:** Add input validation before database operations
4. **Security:** Implement CSRF tokens for forms
5. **API Response:** Standardize JSON responses with status codes
6. **Middleware:** Add middleware for authentication/authorization
7. **ORM:** Consider using an ORM for database operations
8. **Testing:** Add unit and integration tests

---

## Summary

This application follows a straightforward MVC pattern:
1. User requests → URL routing (Root.php)
2. Route → Controller selection
3. Controller → Business logic (Model) + View rendering (Load.php)
4. Model → Database queries (Query.php)
5. View → HTML output with assets

The architecture is clean, maintainable, and provides a solid foundation for a multi-page web application.
