<?php
/**
 * ============================================================================
 * VIEW & ASSET LOADER - Load.php
 * ============================================================================
 * 
 * The Load class is responsible for:
 * 1. Loading view files (HTML content)
 * 2. Loading template files (header, footer, sidebar)
 * 3. Injecting CSS stylesheets into HTML
 * 4. Injecting JavaScript files into HTML
 * 5. Passing data from controller to view files
 * 
 * DESIGN PATTERN: Template Pattern
 * - Separates data (Model) from presentation (View)
 * - Passes data to views via variable extraction
 * 
 * USAGE EXAMPLES:
 * Load::view('home', ['title' => 'Welcome']);
 * Load::template('header', ['css' => ['all', 'home']]);
 * Load::css(['all', 'home']);
 * Load::js(['jquery.min', 'all']);
 * ============================================================================
 */

class Load
{
    /**
     * LOAD VIEW METHOD
     * 
     * Includes a view file from the View/ directory.
     * View files contain the HTML/presentation logic.
     * Data array is converted to local variables accessible in the view.
     * 
     * @param string $view - Name of view file (without .php extension)
     *                        Example: "home" loads View/home.php
     * @param array $data - Associative array of data to pass to view
     *                       Example: ['title' => 'Home', 'users' => $users]
     *                       In view file: $title and $users become available variables
     * 
     * PROCESS:
     * 1. Check if $data is an array and not empty
     * 2. Extract array keys as variable names, values as variable values
     * 3. Include the view file (variables are now accessible in view scope)
     * 
     * EXAMPLE:
     * $data = ['title' => 'Home Page', 'count' => 5];
     * Load::view('home', $data);
     * 
     * Inside View/home.php:
     * echo $title;  // Outputs: "Home Page"
     * echo $count;  // Outputs: 5
     */
    public static function view($view,$data=[]){
        // Check if data is provided as an array and is not empty
        if(is_array($data) && !empty($data)){
            // Convert array keys to variables
            // extract(['title' => 'Home']) becomes $title = 'Home'
            // This makes data accessible as $title in the included view file
            foreach($data as $key=>$value){
                $$key=$value;  // Variable variables: $$key creates variable with dynamic name
            }
        }
        // Include the view file from View/ directory
        // This file now has access to all extracted variables
        require_once "View/$view.php";
    }

    /**
     * LOAD TEMPLATE METHOD
     * 
     * Loads template files from View/template/ directory.
     * Templates are typically reusable layout components (header, footer, sidebar).
     * Works identically to view() but loads from different directory.
     * 
     * @param string $view - Name of template file (without .php extension)
     *                        Example: "header" loads View/template/header.php
     * @param array $data - Data to inject into template variables
     * 
     * TEMPLATE FILES:
     * - header.php: Common page header with title, CSS links
     * - footer.php: Common page footer with JS links
     * - sideAdmin.php: Admin dashboard sidebar navigation
     * 
     * EXAMPLE:
     * Load::template('header', [
     *     'title' => 'Home',
     *     'css' => ['all', 'home']
     * ]);
     */
    public static function template($view,$data=[]){
        // Extract data array to variables (same as view method)
        if(is_array($data) && !empty($data)){
            foreach($data as $key=>$value){
                $$key=$value;
            }
        }
        // Include template file from View/template/ directory
        require_once "View/template/$view.php";
    }

    /**
     * LOAD CSS METHOD
     * 
     * Dynamically generates HTML <link> tags for CSS files.
     * This method should be called in templates to inject stylesheets.
     * 
     * @param array $css - Array of CSS file names (without .css extension)
     *                      Example: ['all', 'home', 'admin']
     *                      Generates links to: Public/css/all.css, Public/css/home.css, etc.
     * 
     * URL CONSTRUCTION:
     * - Prefix: URL constant defined in autoload.php (e.g., "http://localhost/logiciel/")
     * - Path: Public/css/
     * - File: CSS file name from array
     * - Extension: .css
     * 
     * EXAMPLE:
     * Load::css(['all', 'home', 'login']);
     * 
     * OUTPUT (in HTML):
     * <link rel='stylesheet' href='http://localhost/logiciel/Public/css/all.css'>
     * <link rel='stylesheet' href='http://localhost/logiciel/Public/css/home.css'>
     * <link rel='stylesheet' href='http://localhost/logiciel/Public/css/login.css'>
     * 
     * NOTE: This should be called in View/template/header.php
     */
    public static function css($css){
        // Check if CSS array is provided and not empty
        if(is_array($css) && !empty($css)){
            // Loop through each CSS file name
            for($i=0;$i<count($css);$i++){
                // Generate and echo the <link> tag for each CSS file
                // URL constant is defined in autoload.php
                echo "<link rel='stylesheet' href='".URL."Public/css/$css[$i].css'>";
            }
        }
    }

    /**
     * LOAD JAVASCRIPT METHOD
     * 
     * Dynamically generates HTML <script> tags for JavaScript files.
     * This method should be called in templates to inject scripts.
     * 
     * @param array $js - Array of JavaScript file names (without .js extension)
     *                     Example: ['jquery.min', 'all', 'home']
     *                     Generates links to: Public/js/jquery.min.js, Public/js/all.js, etc.
     * 
     * SPECIAL CASES:
     * - Reusable libraries in Public/js/reusable/ (jquery, bootstrap, sweetalert, etc.)
     * - Pass as: 'reusable/jquery.min' to load Public/js/reusable/jquery.min.js
     * 
     * EXAMPLE:
     * Load::js(['reusable/jquery.min', 'all', 'home']);
     * 
     * OUTPUT (in HTML):
     * <script src='http://localhost/logiciel/Public/js/reusable/jquery.min.js'></script>
     * <script src='http://localhost/logiciel/Public/js/all.js'></script>
     * <script src='http://localhost/logiciel/Public/js/home.js'></script>
     * 
     * NOTE: This should be called in View/template/footer.php
     */
    public static function js($js){
        // Check if JavaScript array is provided and not empty
        if(is_array($js) && !empty($js)){
            // Loop through each JavaScript file name
            for($i=0;$i<count($js);$i++){
                // Generate and echo the <script> tag for each JS file
                // Supports subdirectories like "reusable/jquery.min"
                echo "<script src='".URL."Public/js/$js[$i].js'></script>";
            }
        }
    }
}