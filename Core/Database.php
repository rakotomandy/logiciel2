<?php
/**
 * ============================================================================
 * DATABASE INITIALIZATION - Database.php
 * ============================================================================
 * 
 * The Database class is responsible for:
 * 1. Creating the database if it doesn't exist
 * 2. Creating tables for the application
 * 3. Modifying table structure (adding/dropping columns)
 * 4. Dropping tables
 * 
 * USAGE:
 * This class is used for initial database setup and schema management.
 * It's NOT used for regular SELECT/INSERT/UPDATE/DELETE operations.
 * Those operations use the Query class instead.
 * 
 * NOTE: This file contains database setup code at the bottom.
 * Commented out lines can be uncommented to create/modify tables.
 * 
 * DATABASE CONNECTION: PDO (PHP Data Objects)
 * ============================================================================
 */

class Database
{
    /**
     * Database connection object
     * Uses PDO for database abstraction and security
     */
    private $conn;

    /**
     * CONSTRUCTOR - Database Creation
     * 
     * Creates a new database if it doesn't exist.
     * Establishes connection to the newly created or existing database.
     * 
     * @param string $servername - Database server address (e.g., "localhost")
     * @param string $username - Database user (e.g., "root")
     * @param string $password - Database password (e.g., "")
     * @param string $db - Database name to create (e.g., "logiciel")
     * 
     * PROCESS:
     * 1. Connect to MySQL server (without specifying a database)
     * 2. Execute CREATE DATABASE IF NOT EXISTS query
     * 3. Switch to the newly created database using USE statement
     * 4. Display success message
     * 
     * EXAMPLE:
     * $db = new Database("localhost", "root", "", "logiciel");
     */
    public function __construct($servername, $username, $password,$db)
    {
        // Create PDO connection to MySQL server
        // Note: No database is selected initially ("mysql:host=...")
        $this->conn = new PDO("mysql:host=$servername", $username, $password);
        
        // SQL query to create database if it doesn't already exist
        $sql="CREATE DATABASE IF NOT EXISTS $db";
        
        // Execute the CREATE DATABASE query
        $this->conn->exec($sql);
        
        // Switch to the newly created/existing database
        // This is necessary before creating tables
        $this->conn->exec("USE $db");
        
        // Display success message
        echo "db successfully created";
    }

    /**
     * CREATE TABLE METHOD
     * 
     * Creates a new table in the database with specified columns and data types.
     * 
     * @param string $tableName - Name of the table to create
     * @param array $columns - Array of column names
     *                          Example: ["ID", "NAME", "EMAIL", "PASSWORD"]
     * @param array $chars - Array of column definitions (SQL data types)
     *                        Example: ["INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY",
     *                                   "VARCHAR(250) NOT NULL",
     *                                   "VARCHAR(50) NOT NULL",
     *                                   "VARCHAR(50) NOT NULL"]
     * 
     * PROCESS:
     * 1. Validate that both columns and characters are arrays
     * 2. Combine column names with their data types
     * 3. Build CREATE TABLE SQL statement
     * 4. Execute the statement
     * 5. Display success message and the generated SQL
     * 
     * DATA TYPES:
     * - INT(10): Integer, up to 10 digits
     * - VARCHAR(250): Variable-length text, up to 250 characters
     * - UNSIGNED: Only positive numbers (0+)
     * - NOT NULL: Column cannot be empty
     * - AUTO_INCREMENT: Automatically increment value for each row
     * - PRIMARY KEY: Unique identifier for each row
     * 
     * EXAMPLE:
     * $db->createTable(
     *     "user",
     *     ["ID", "NAME", "EMAIL", "PASSWORD"],
     *     ["INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY",
     *      "VARCHAR(250) NOT NULL",
     *      "VARCHAR(50) NOT NULL",
     *      "VARCHAR(50) NOT NULL"]
     * );
     * 
     * GENERATED SQL:
     * CREATE TABLE user(ID INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
     *                   NAME VARCHAR(250) NOT NULL,
     *                   EMAIL VARCHAR(50) NOT NULL,
     *                   PASSWORD VARCHAR(50) NOT NULL)
     */
    public function createTable($tableName,$columns,$chars){
        // Validate that columns and characters are arrays and not empty
        if(is_array($columns) && is_array($chars)){
            // Start building the CREATE TABLE statement
            $create=" CREATE TABLE $tableName";
            
            // Initialize the table definition string
            $table="";
            
            // Combine column names with their data types
            // array_combine(['ID', 'NAME'], ['INT...', 'VARCHAR...'])
            // Results in: ['ID' => 'INT...', 'NAME' => 'VARCHAR...']
            $tab=array_combine($columns,$chars);
            
            // Build the column definitions by iterating through the combined array
            foreach($tab as $key=>$value){
                // Append each column and its data type with a comma
                // Example: " ID INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,"
                $table.=" $key $value,";
            }
            
            // Remove the trailing comma from the last column
            $table=(trim($table,","));
            
            // Complete the CREATE TABLE statement with column definitions
            $create.="($table)";
            
            // Execute the CREATE TABLE query
            $this->conn->exec($create);
            
            // Display success message
            echo "table created successfully";
            
            // Display the generated SQL (useful for debugging)
            echo $create;
        }
    }

    /**
     * ALTER TABLE METHOD
     * 
     * Modifies table structure (rename columns, modify data types, etc.)
     * 
     * @param string $table - Name of table to alter
     * @param string $column - The ALTER command (e.g., "RENAME COLUMN", "MODIFY COLUMN")
     * @param string $sub - Additional parameters for the ALTER command
     * 
     * NOTE: This method requires specific SQL syntax knowledge.
     * Examples of $column parameter:
     * - "RENAME COLUMN old_name TO new_name"
     * - "MODIFY COLUMN email VARCHAR(100)"
     * - "DROP COLUMN password"
     * 
     * EXAMPLE:
     * $db->alterTable("user", "RENAME COLUMN", "old_name TO new_name");
     * $db->alterTable("user", "MODIFY COLUMN email", "VARCHAR(100) NOT NULL");
     */
    public function alterTable($table,$column,$sub){
        // Build ALTER TABLE query
        $alter="ALTER TABLE $table $column $sub";
        
        // Execute the ALTER query
        $this->conn->exec($alter);
        
        // Display success message
        echo "table altered successfully";
    }

    /**
     * ADD/DROP COLUMN METHOD
     * 
     * Adds or drops columns from an existing table after creation.
     * 
     * @param string $table - Name of table to modify
     * @param string $action - "ADD" or "DROP" (specifies action)
     * @param string $column - Column name to add or drop
     * @param string $colDef - Column definition for ADD action (e.g., "VARCHAR(100) NOT NULL")
     *                          Not used for DROP action
     * @param string $position - Position for new column (e.g., "AFTER EMAIL", "FIRST")
     * @param string $existing - Additional constraints (not typically used)
     * 
     * EXAMPLE - ADD COLUMN:
     * $db->addDropColumn("user", "ADD", "PHONE", "VARCHAR(20) NOT NULL", "AFTER", "EMAIL");
     * 
     * EXAMPLE - DROP COLUMN:
     * $db->addDropColumn("user", "DROP", "PHONE", "", "", "");
     */
    public function addDropColumn($table,$action,$column,$colDef="",$position="",$existing=""){
        // Build ALTER TABLE query with ADD or DROP COLUMN
        $add="ALTER TABLE $table $action COLUMN $column $colDef $position $existing";
        
        // Execute the query
        $this->conn->exec($add);
        
        // Display success message with action type
        strtolower($action);
        echo "column {$action}ed successfully";
    }

    /**
     * DROP TABLE METHOD (named "custom" for historical reasons)
     * 
     * Deletes an entire table from the database.
     * WARNING: This removes all data in the table permanently!
     * 
     * @param string $table - Name of table to delete
     * 
     * EXAMPLE:
     * $db->custom("user");  // Deletes the "user" table
     */
    public function custom($table){
        // Build DROP TABLE query
        $req="DROP TABLE $table";
        
        // Execute the DROP TABLE query
        $this->conn->exec($req);
        
        // Display success message
        echo "table deleted successfully";
    }
}

// ===== DATABASE INITIALIZATION CODE =====
// Uncomment lines below to create/modify database and tables

// Create new Database instance
// This automatically creates the database if it doesn't exist
$db=new Database("localhost","root","","logiciel");

/**
 * TABLE CREATION EXAMPLE - user_skype table
 * Commented out - uncomment if you want to create this table
 * 
 * $db->createTable(
 *     "user_skype",
 *     ["ID", "PSEUDO", "EMAIL", "PASSWORD", "PHOTO"],
 *     ["INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY",
 *      "VARCHAR(50) NOT NULL",
 *      "VARCHAR(50) NOT NULL",
 *      "VARCHAR(50) NOT NULL",
 *      "VARCHAR(50) NOT NULL"]
 * );
 */

// TABLE CREATION - user table
// This table stores user account information
// Columns: ID, NAME, FIRSTNAME, GENDER, FUNCTION, CIN, PHOTO
$db->createTable("user",["ID","NAME","FIRSTNAME","GENDER","FUNCTION","CIN","PHOTO"],["INT(10) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY","VARCHAR(250) NOT NULL","VARCHAR(250) NOT NULL","VARCHAR(100) NOT NULL","VARCHAR(100) NOT NULL","INT(100) UNSIGNED NOT NULL","VARCHAR(100) NOT NULL"]);

/**
 * ADDITIONAL MODIFICATION EXAMPLES - Commented Out
 * Uncomment these if you want to modify the table structure
 */

// Drop the message table (if you want to remove it)
// $db->custom("message");

// Add PASSWORD column after EMAIL column
// $db->addDropColumn("user", "ADD", "PASSWORD", "VARCHAR(250) NOT NULL", "AFTER", "EMAIL");
