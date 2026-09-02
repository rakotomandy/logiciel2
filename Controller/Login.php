<?php
/**
 * ============================================================================
 * LOGIN CONTROLLER - Login.php
 * ============================================================================
 * 
 * The Login controller handles requests for the login page.
 * It displays the login form and is typically paired with a LoginModel
 * for handling authentication logic.
 * 
 * ROUTE ACCESS:
 * - URL: http://localhost/logiciel/?action=Login
 * - Method Called: Login::index()
 * 
 * RESPONSIBILITIES:
 * 1. Display the login form
 * 2. Load CSS stylesheets specific to login page
 * 3. Load JavaScript for form validation and submission
 * 
 * FLOW:
 * 1. Root router calls Login::index()
 * 2. Load::template() includes View/template/header.php with login title
 * 3. Load::view() includes View/login.php (login form)
 * 4. Load::template() includes View/template/footer.php with login scripts
 * 5. HTML login page is rendered to browser
 * 
 * ASSOCIATED FILES:
 * - View/login.php: Contains the login form HTML
 * - Public/css/login.css: Login page styles
 * - Public/js/login.js: Login form JavaScript (validation, AJAX submission)
 * - Model/LoginModel.php: Handles authentication logic
 * ============================================================================
 */

class Login
{
    /**
     * INDEX METHOD - Display login page
     * 
     * This method is called when user accesses:
     * http://localhost/logiciel/?action=Login
     * 
     * PROCESS:
     * 1. Load header template with "login" title
     * 2. Include login form view
     * 3. Load footer with login-specific JavaScript
     * 
     * DATA PASSED TO TEMPLATES:
     * - title: Page title ("login")
     * - css: Stylesheets for login page
     * - js: JavaScript files for login functionality
     */
    public function index()
    {
        // Load page header with login-specific configuration
        Load::template("header", [
            // Page title displayed in browser tab
            "title" => "login",
            
            // CSS files to load for login page
            "css" => [
                "all",       // Common styles shared across all pages
                "login"      // Login page specific styles (form styling, etc.)
            ]
        ]);
        
        // Load the login form view
        // This includes View/login.php which contains:
        // - HTML form with email and password fields
        // - Form validation UI
        Load::view("login");
        
        // Load footer with login-specific JavaScript files
        Load::template("footer", [
            // JavaScript files for login page functionality
            "js" => [
                "reusable/jquery.min",              // jQuery for DOM manipulation
                "reusable/sweetalert2.all.min",     // SweetAlert for alerts (success/error messages)
                "reusable/all",                     // Common JavaScript
                "login"                             // Login page specific JavaScript
                                                     // Handles form validation and AJAX submission
            ]
        ]);
    }
}
