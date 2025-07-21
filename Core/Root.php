<?php

class Root
{
    public static function connect($url)
    {
        // Sanitize and clean the URL
        $url = htmlspecialchars(trim($url)); // Avoid XSS and remove extra spaces
        $url = trim($url, "/");              // Remove leading and trailing slashes

        // Split the URL into parts by "/"
        $parts = explode("/", $url);

        // Store GET parameters (e.g., ?id=5)
        $queryParams = $_GET;

        // Get the class name (first segment)
        $class = $parts[0];

        // Check if the Controller class file exists
        if (!file_exists("Controller/$class.php")) {
            echo "<h1 style='color:red;text-align:center'>$class does not exist in this site</h1>";
            return;
        }

        // Load the controller class if not already loaded (optional if autoloading is in place)
        require_once "Controller/$class.php";

        // If only one segment (e.g., /Home), call the 'index' method
        if (count($parts) == 1) {
            if (method_exists($class, 'index')) {
                $reflect = new ReflectionMethod($class, 'index');
                $reflect->invokeArgs(new $class, [$queryParams]);
            } else {
                echo "<h1 style='color:red;text-align:center'>index() does not exist in $class</h1>";
            }
            return;
        }

        // If at least two segments (e.g., /User/show)
        $method = $parts[1]; // Second segment is the method name

        if (!method_exists($class, $method)) {
            echo "<h1 style='color:red;text-align:center'>$method() does not exist in $class</h1>";
            return;
        }

        // Gather any additional parameters from URL (e.g., /User/show/5/true)
        $params = array_slice($parts, 2); // Get everything after the second segment

        // Add GET parameters as the last argument
        $params[] = $queryParams;

        // Dynamically invoke the method with parameters
        $reflect = new ReflectionMethod($class, $method);
        $reflect->invokeArgs(new $class, $params);
    }
}