<?php
/**
 * ============================================================================
 * APPLICATION ENTRY POINT - index.php
 * ============================================================================
 * 
 * This is the main entry point for the entire web application.
 * All requests are routed through this file.
 * 
 * URL Format: index.php?action=ControllerName/MethodName/Param1/Param2
 * Example: index.php?action=Home
 *          index.php?action=User/show/5
 * 
 * FLOW:
 * 1. Load autoloader to enable automatic class loading
 * 2. Check if 'action' parameter exists in GET request
 * 3. If exists: Route to Root::connect() for processing
 * 4. If not exists: Display 404 error
 * ============================================================================
 */

// Include the autoloader which registers custom autoload function
// This allows automatic loading of classes from Controller/, Model/, and Core/ directories
require_once "Core/autoload.php";

// Check if the 'action' GET parameter is set (required for routing)
if(isset($_GET["action"])){
    // Pass the action to the Root router for processing
    // Example: ?action=Home will call Home controller's index() method
    // Example: ?action=User/show/5 will call User controller's show() method with parameter 5
    Root::connect($_GET["action"]);
}else{
    // If no action parameter is provided, display error message
    echo "<h1> NOT FOUND</h1>";
}