<?php
/**
 * ============================================================================
 * HOME CONTROLLER - Home.php
 * ============================================================================
 * 
 * The Home controller handles requests for the home page.
 * It is responsible for loading and rendering the home page view.
 * 
 * ROUTE ACCESS:
 * - URL: http://localhost/logiciel/?action=Home
 * - Method Called: Home::index()
 * 
 * RESPONSIBILITIES:
 * 1. Load the page header with title and CSS files
 * 2. Load the main home page content
 * 3. Load the page footer with JavaScript files
 * 
 * FLOW:
 * 1. Root router calls Home::index()
 * 2. Load::template() includes View/template/header.php
 * 3. Load::view() includes View/home.php
 * 4. Load::template() includes View/template/footer.php
 * 5. HTML is rendered to browser
 * ============================================================================
 */

class Home
{
    /**
     * INDEX METHOD - Default action for Home controller
     * 
     * This method is called when user accesses:
     * http://localhost/logiciel/?action=Home
     * 
     * PROCESS:
     * 1. Load header template with title and CSS stylesheets
     * 2. Load main content view (home.php)
     * 3. Load footer template with JavaScript files
     * 
     * DATA PASSED TO VIEWS:
     * - title: Page title (displayed in browser tab and header)
     * - css: Array of CSS files to load (stylesheets for home page)
     * - js: Array of JavaScript files to load (scripts for functionality)
     */
    public function index()
    {
        // Load header template with data
        // This includes the common page header (navbar, title, etc.)
        Load::template("header", [
            // Page title (used in <title> tag and header display)
            "title" => "HOME",
            
            // Array of CSS files to include
            // These are relative to Public/css/ directory
            // Example: "all" loads Public/css/all.css
            "css" => [
                "all",       // Common styles for all pages
                "home"       // Home page specific styles
            ]
        ]);
        
        // Load main content view
        // This includes View/home.php
        // Displays the actual home page content
        Load::view("home");
        
        // Load footer template with JavaScript files
        // This includes the common page footer
        Load::template("footer", [
            // Array of JavaScript files to include
            // These are relative to Public/js/ directory
            // Note: "reusable/" prefix loads from Public/js/reusable/
            "js" => [
                "reusable/jquery.min",   // jQuery library for DOM manipulation
                "reusable/all",          // Common JavaScript for all pages
                "reusable/home"          // Home page specific JavaScript
            ]
        ]);
    }
}
