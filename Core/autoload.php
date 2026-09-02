<?php
/**
 * ============================================================================
 * AUTOLOADER & CONFIGURATION - autoload.php
 * ============================================================================
 * 
 * This file is responsible for:
 * 1. Starting PHP sessions for user state management
 * 2. Defining the base URL for asset loading (CSS, JS, images)
 * 3. Auto-loading classes when they are first used (PSR-0 style)
 * 
 * FEATURES:
 * - Automatic class instantiation (no need for manual require_once)
 * - Supports classes from Controller/, Model/, and Core/ directories
 * - Improves code organization and reduces manual includes
 * ============================================================================
 */

// Start PHP session to manage user authentication state and session variables
// This enables $_SESSION usage throughout the application
session_start();

/**
 * Define the base URL for the application
 * Used for:
 * - Loading CSS files via Load::css()
 * - Loading JavaScript files via Load::js()
 * - Building links in templates
 * 
 * Must be updated if application is moved to different server/path
 */
define("URL","http://localhost/logiciel/");

/**
 * AUTOLOAD FUNCTION
 * 
 * This function is called automatically when a class is used but not yet defined.
 * It attempts to find and load the class file from specific directories.
 * 
 * @param string $file - The name of the class to load
 * 
 * SEARCH ORDER:
 * 1. Check Controller/ directory (e.g., Controller/Home.php)
 * 2. Check Model/ directory (e.g., Model/LoginModel.php)
 * 3. Check Core/ directory (e.g., Core/Query.php)
 * 
 * EXAMPLE:
 * When you write: $home = new Home();
 * PHP will automatically call autoload("Home") if Home class is not already defined
 * The function will find and include Controller/Home.php
 */
function autoload($file){
    // Look for controller class files first
    if(file_exists("Controller/$file.php")){
        require_once "Controller/$file.php";
    }
    // Look for model class files second
    elseif(file_exists("Model/$file.php")){
        require_once "Model/$file.php";
    }
    // Look for core class files last
    elseif(file_exists("Core/$file.php")){
        require_once "Core/$file.php";
    }
}

/**
 * Register the autoload function with PHP's autoload system
 * 
 * This tells PHP to call our autoload() function whenever an undefined class is used.
 * This is called "autoloading" and is a PSR-0 standard practice.
 */
spl_autoload_register("autoload");