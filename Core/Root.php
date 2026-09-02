<?php
/**
 * ============================================================================
 * URL ROUTER - Root.php
 * ============================================================================
 * 
 * The Root class is the main router for the entire application.
 * It is responsible for:
 * 1. Parsing the URL/action parameter
 * 2. Determining which Controller and Method to call
 * 3. Dynamically invoking the controller method with parameters
 * 
 * URL FORMAT & EXAMPLES:
 * ├─ /Home                  → Home::index()
 * ├─ /Login                 → Login::index()
 * ├─ /User                  → User::index()
 * ├─ /User/show             → User::show()
 * ├─ /User/show/5           → User::show(5)
 * ├─ /User/show/5/true      → User::show(5, true)
 * └─ /User/show/5?id=10     → User::show(5, ['id'=>10])
 * 
 * The method always receives $_GET parameters as the last argument.
 * ============================================================================
 */

class Root
{
    /**
     * MAIN ROUTING METHOD
     * 
     * This static method is the entry point for all application requests.
     * It parses the URL, loads the appropriate controller, and calls the requested method.
     * 
     * @param string $url - The action from GET parameter (e.g., "Home", "User/show/5")
     * 
     * PROCESS:
     * 1. Sanitize the URL to prevent XSS attacks
     * 2. Parse URL into segments (split by "/")
     * 3. Extract controller name (first segment)
     * 4. Check if controller file exists
     * 5. Extract method name (second segment, defaults to "index")
     * 6. Verify method exists in controller
     * 7. Extract additional parameters (third segment onwards)
     * 8. Invoke the method using PHP Reflection API
     */
    public static function connect($url)
    {
        // ===== URL SANITIZATION =====
        // Prevent XSS attacks by escaping special HTML characters
        // Example: "<script>" becomes "&lt;script&gt;"
        $url = htmlspecialchars(trim($url)); // Avoid XSS and remove extra spaces
        
        // Remove leading and trailing slashes from URL
        // Example: "/Home/" becomes "Home"
        $url = trim($url, "/");              // Remove leading and trailing slashes

        // ===== URL PARSING =====
        // Split the URL into segments using "/" as delimiter
        // Example: "User/show/5" becomes ["User", "show", "5"]
        $parts = explode("/", $url);

        // Store GET parameters (e.g., ?id=5&name=john)
        // These will be passed to the controller method as an array
        $queryParams = $_GET;

        // ===== CONTROLLER EXTRACTION =====
        // First segment is always the controller class name
        // Example: in "User/show/5", $class = "User"
        $class = $parts[0];

        // ===== CONTROLLER FILE VALIDATION =====
        // Check if the controller file exists before attempting to load it
        if (!file_exists("Controller/$class.php")) {
            // If controller doesn't exist, display error message and exit
            echo "<h1 style='color:red;text-align:center'>$class does not exist in this site</h1>";
            return;
        }

        // Load the controller class file
        // (Note: autoload might already handle this, but explicit require ensures it's loaded)
        require_once "Controller/$class.php";

        // ===== INDEX METHOD (Single Segment) =====
        // If URL has only one segment (e.g., "/Home"), call the index() method
        if (count($parts) == 1) {
            // Check if the index method exists in the controller
            if (method_exists($class, 'index')) {
                // Use Reflection API to dynamically invoke the method
                // Reflection allows us to call methods without knowing them at compile time
                $reflect = new ReflectionMethod($class, 'index');
                
                // invokeArgs instantiates the class and calls the method with parameters
                // Parameters: (object instance, array of parameters)
                $reflect->invokeArgs(new $class, [$queryParams]);
            } else {
                // If index method doesn't exist, display error
                echo "<h1 style='color:red;text-align:center'>index() does not exist in $class</h1>";
            }
            return;
        }

        // ===== SPECIFIC METHOD (Two or More Segments) =====
        // If URL has multiple segments, second segment is the method name
        // Example: in "User/show/5", $method = "show"
        $method = $parts[1]; // Second segment is the method name

        // Verify the method exists in the controller class
        if (!method_exists($class, $method)) {
            // If method doesn't exist, display error and exit
            echo "<h1 style='color:red;text-align:center'>$method() does not exist in $class</h1>";
            return;
        }

        // ===== PARAMETER EXTRACTION =====
        // Get all segments after the first two (controller and method names)
        // Example: in "User/show/5/true", $params = ["5", "true"]
        $params = array_slice($parts, 2); // Get everything after the second segment

        // Add GET parameters as the last parameter to the method
        // The method will receive: ..., $param1, $param2, $queryParams
        $params[] = $queryParams;

        // ===== METHOD INVOCATION =====
        // Use Reflection API to dynamically call the method with its parameters
        $reflect = new ReflectionMethod($class, $method);
        
        // invokeArgs creates a new instance and calls the method with parameters
        $reflect->invokeArgs(new $class, $params);
    }
}