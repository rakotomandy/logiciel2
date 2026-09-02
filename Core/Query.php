<?php
/**
 * ============================================================================
 * DATABASE QUERY BUILDER - Query.php
 * ============================================================================
 * 
 * The Query class handles all database operations using PDO (PHP Data Objects).
 * It provides a simple interface for executing custom SQL queries with prepared statements.
 * 
 * FEATURES:
 * - Prepared statements (prevents SQL injection attacks)
 * - Static connection configuration (singleton pattern)
 * - Fluent interface for method chaining
 * - Support for SELECT, INSERT, UPDATE, DELETE queries
 * - Automatic result formatting (returns objects)
 * 
 * SECURITY:
 * - All SQL queries use placeholders (?) to prevent SQL injection
 * - Parameters are bound separately from the SQL query
 * - Never concatenate user input directly into queries
 * 
 * DATABASE CONFIGURATION:
 * Host: localhost
 * Database: logiciel
 * User: root
 * Password: (empty)
 * ============================================================================
 */

class Query
{
    // ===== STATIC CONNECTION PROPERTIES =====
    // These are shared across all Query instances (singleton-like pattern)
    // Set once via Query::connect() and used by all instances
    
    private static $hostname;   // Database server address
    private static $dbname;     // Database name
    private static $user;       // Database username
    private static $password;   // Database password

    // ===== INSTANCE PROPERTIES =====
    // These are unique to each Query instance
    
    private $query;    // The SQL query string
    private $reqType;  // Type of query: "select", "insert", "update", "delete"
    private $db;       // PDO database connection object

    /**
     * STATIC CONNECTION CONFIGURATION
     * 
     * Call this method once at application startup to configure database credentials.
     * All Query instances will use these settings.
     * 
     * @param string $hostname - Database server address (usually "localhost")
     * @param string $dbname - Database name (e.g., "logiciel")
     * @param string $user - Database username (e.g., "root")
     * @param string $password - Database password (e.g., "" for no password)
     * 
     * CALLED IN: Core/Query.php at the bottom of file
     * EXAMPLE:
     * Query::connect("localhost", "logiciel", "root", "");
     * 
     * After this, all new Query() instances can use these connection details.
     */
    public static function connect($hostname, $dbname, $user, $password)
    {
        // Store connection credentials in static properties
        self::$hostname = $hostname;
        self::$dbname = $dbname;
        self::$user = $user;
        self::$password = $password;
    }

    /**
     * CONSTRUCTOR
     * 
     * Establishes a PDO connection to the database when a Query object is created.
     * Uses the static connection credentials configured via Query::connect()
     * 
     * @throws PDOException if connection fails
     * 
     * PROCESS:
     * 1. Create a new PDO connection using stored credentials
     * 2. PDO handles database driver abstraction (MySQL, PostgreSQL, etc.)
     * 
     * CALLED: Each time new Query() is instantiated
     * EXAMPLE:
     * $query = new Query();  // Automatically connects to database
     */
    public  function __construct()
    {
        // Create PDO connection using the static connection credentials
        // DSN format: mysql:host=localhost;dbname=logiciel
        $this->db = new PDO("mysql:host=" . self::$hostname . ";dbname=" . self::$dbname, self::$user, self::$password);
    }

    /**
     * CUSTOM QUERY METHOD
     * 
     * Sets up a custom SQL query and its type.
     * Allows for chainable method calls (fluent interface).
     * 
     * @param string $req - The SQL query string with placeholders (?)
     *                       Example: "SELECT * FROM user WHERE EMAIL=?"
     *                       Example: "INSERT INTO user (NAME, EMAIL) VALUES (?, ?)"
     * @param string $type - Query type: "select", "insert", "update", or "delete"
     *                       Affects how results are returned
     * 
     * RETURNS: $this - Returns the object itself to allow method chaining
     * 
     * METHOD CHAINING EXAMPLE:
     * $query->custom("SELECT * FROM user WHERE ID=?", "select")
     *       ->execute([5]);
     * 
     * EXAMPLE - SELECT QUERY:
     * $query->custom("SELECT * FROM user WHERE EMAIL=?", "select")
     *       ->execute([$email]);
     * 
     * EXAMPLE - INSERT QUERY:
     * $query->custom("INSERT INTO user (NAME, EMAIL) VALUES (?, ?)", "insert")
     *       ->execute([$name, $email]);
     */
    public function custom($req, $type)
    {
        // Store the SQL query string
        $this->query = $req;
        
        // Store the query type (determines how results are handled)
        $this->reqType = $type;
        
        // Return $this to allow method chaining
        return $this;
    }

    /**
     * EXECUTE METHOD
     * 
     * Executes the SQL query with provided parameters.
     * Handles prepared statement binding and result fetching.
     * 
     * @param array $value - Array of parameters to bind to placeholders (?)
     *                        Optional - pass empty array [] or omit for queries without parameters
     * 
     * RETURNS:
     * - For SELECT queries: Array of objects containing result rows
     * - For INSERT/UPDATE/DELETE: true/null (no return value for non-select)
     * 
     * SECURITY:
     * - Uses prepared statements with parameter binding
     * - Placeholders (?) are replaced with parameters safely
     * - Example: custom("SELECT * FROM user WHERE ID=?", "select")->execute([5])
     *   The "?" is replaced with 5, but not through string concatenation
     * 
     * RETURN FORMATS:
     * - SELECT: Array of PDOStatement objects (PDO::FETCH_OBJ)
     * - INSERT/UPDATE/DELETE: No return (method completes execution)
     * 
     * EXAMPLES:
     * 
     * 1. SELECT with parameters:
     *    $users = $query->custom("SELECT * FROM user WHERE ID=?", "select")
     *                   ->execute([5]);
     *    echo $users[0]->NAME;  // Access object properties
     * 
     * 2. SELECT without parameters:
     *    $users = $query->custom("SELECT * FROM user", "select")
     *                   ->execute();
     * 
     * 3. INSERT with parameters:
     *    $query->custom("INSERT INTO user (NAME, EMAIL) VALUES (?, ?)", "insert")
     *          ->execute(["John", "john@email.com"]);
     */
    public function execute($value = [])
    {
        // Check if parameters are provided (array and not empty)
        if (is_array($value) && !empty($value)) {
            // CASE 1: Non-SELECT queries (INSERT, UPDATE, DELETE) with parameters
            if ($this->reqType != "select") {
                // Prepare the SQL statement for execution
                $req = $this->db->prepare($this->query);
                
                // Execute the query with parameter binding
                // PDO replaces placeholders (?) with values from $value array
                $req->execute($value);
                
                // Non-select queries don't return results
                // Return value is implicit (execution success)
            }
            // CASE 2: SELECT queries with parameters
            else {
                // Prepare the SQL statement
                $req = $this->db->prepare($this->query);
                
                // Execute the query with parameter binding
                $req->execute($value);
                
                // Fetch all results as objects (PDO::FETCH_OBJ)
                // Each row becomes an object with properties matching column names
                // Example: $row->NAME, $row->EMAIL, $row->ID
                return $req->fetchAll(PDO::FETCH_OBJ);
            }
        }
        // CASE 3: No parameters provided
        else {
            // Only valid for SELECT queries without WHERE clauses
            if ($this->reqType == "select") {
                // Prepare and execute query
                $req = $this->db->prepare($this->query);
                $req->execute();
                
                // Fetch and return all results as objects
                return $req->fetchAll(PDO::FETCH_OBJ);
            }
        }
    }

    /**
     * GET QUERY METHOD
     * 
     * Returns the current SQL query string.
     * Useful for debugging to see the actual query being executed.
     * 
     * @return string - The SQL query string (with placeholders, not actual values)
     * 
     * DEBUGGING EXAMPLE:
     * $query = new Query();
     * $query->custom("SELECT * FROM user WHERE ID=?", "select");
     * echo $query->getQuery();  // Outputs: SELECT * FROM user WHERE ID=?
     */
    public function getQuery()
    {
        return $this->query;
    }
}

// ===== DATABASE CONNECTION INITIALIZATION =====
// Configure database credentials at application startup
// These credentials are used by all Query instances
Query::connect("localhost", "logiciel", "root", "");

// ===== COMMENTED OUT EXAMPLES =====
// The following are examples of how to use the Query class.
// Uncommented these during development for testing.

/*
$db = new Query();

// Example 1: INSERT query
$db->custom(
    "INSERT INTO user (PSEUDO, EMAIL, PASSWORD, PHOTO) VALUES (?, ?, ?, ?)",
    "insert"
)->execute(["ERIC", "eric@gmail.com", "1234", "photo_path"]);

// Example 2: SELECT query with WHERE clause
$db->custom("SELECT * FROM user WHERE EMAIL=?", "select")
   ->execute(["eric@gmail.com"]);

// Example 3: UPDATE query
$db->custom(
    "UPDATE user SET PASSWORD=? WHERE EMAIL=?",
    "update"
)->execute(["new_password", "eric@gmail.com"]);

// Example 4: DELETE query
$db->custom("DELETE FROM user WHERE PSEUDO=?", "delete")
   ->execute(["ERIC"]);

// Example 5: SELECT all records
$data = $db->custom("SELECT * FROM user", "select")->execute();
var_dump($data);

// Display the query for debugging
echo $db->getQuery();
*/
