<?php
/**
 * ============================================================================
 * LOGIN MODEL - LoginModel.php
 * ============================================================================
 * 
 * The LoginModel class handles all business logic related to user authentication.
 * It serves as the bridge between Controllers and the Database.
 * 
 * RESPONSIBILITIES:
 * 1. User login verification (email and password validation)
 * 2. User registration (account creation)
 * 3. Database interaction via Query class
 * 4. Password security (hashing and verification)
 * 
 * DESIGN PATTERN: Model Layer in MVC
 * - Separates business logic from controllers
 * - Handles database operations
 * - Manages user authentication state
 * 
 * SECURITY FEATURES:
 * - Password hashing using PASSWORD_DEFAULT algorithm
 * - Prepared statements to prevent SQL injection
 * - Email uniqueness validation
 * - Password verification using password_verify()
 * 
 * DATABASE INTERACTION:
 * Uses Query class for database operations:
 * - SELECT queries to fetch user data
 * - INSERT queries to create new users
 * - Prepared statements with parameter binding
 * ============================================================================
 */

class LoginModel
{
    /**
     * Private property to store Query instance
     * Used for all database operations
     */
    private $loginModel;

    /**
     * CONSTRUCTOR
     * 
     * Initializes the LoginModel by creating a new Query instance.
     * This Query instance is used for all database operations.
     * 
     * PROCESS:
     * 1. Create a new Query object
     * 2. Store it in $loginModel property
     * 3. The Query object will establish database connection
     * 
     * CALLED: When LoginModel is instantiated
     * EXAMPLE:
     * $loginModel = new LoginModel();  // Constructor is called automatically
     */
    public function __construct()
    {
        // Initialize Query instance for database operations
        // This Query object provides methods for SELECT, INSERT, etc.
        $this->loginModel = new Query();
    }

    /**
     * LOGIN METHOD - User Authentication
     * 
     * Verifies user credentials by:
     * 1. Searching for user by email in database
     * 2. Comparing provided password with stored hash
     * 
     * @param string $email - User's email address
     * @param string $pwd - User's plain text password (from login form)
     * 
     * @return string - Either "match" (successful login) or "mismatch" (failed login)
     * 
     * SECURITY:
     * - Uses password_verify() to compare password with hash
     * - Never compares plain passwords directly
     * - Prevents timing attacks with constant-time comparison
     * 
     * PROCESS:
     * 1. Execute SELECT query to find user by email
     * 2. Check if user exists in database
     * 3. Use password_verify() to compare provided password with stored hash
     * 4. Return "match" if both email found and password correct
     * 5. Return "mismatch" if email not found or password incorrect
     * 
     * DATABASE QUERY:
     * SELECT * FROM user WHERE EMAIL=?
     * Parameters: [$email]
     * 
     * RETURN VALUES:
     * - "match": Email exists AND password is correct
     * - "mismatch": Email doesn't exist OR password is incorrect
     * 
     * EXAMPLE:
     * $loginModel = new LoginModel();
     * $result = $loginModel->login("john@email.com", "password123");
     * if($result === "match") {
     *     // Login successful - set session variables
     *     $_SESSION['user_id'] = $user->ID;
     * } else {
     *     // Login failed - show error message
     *     echo "Invalid email or password";
     * }
     * 
     * NOTES:
     * - Password must have been hashed with PASSWORD_DEFAULT when user registered
     * - Uses PDO prepared statements to prevent SQL injection
     * - $data[0] accesses first result (assuming email is unique)
     */
    public function login($email, $pwd)
    {
        // Execute SELECT query to find user by email
        // This uses prepared statements with parameter binding
        // The ? is replaced safely with the $email value
        $data = $this->loginModel->custom("SELECT * FROM user WHERE EMAIL=?", "select")->execute([$email]);
        
        // Check if user was found and password is correct
        // $data contains array of objects (rows returned from query)
        // password_verify($plain_text, $hash) returns true if they match
        if ($data && password_verify($pwd, $data[0]['PASSWORD'])) {
            // Both email exists and password is correct
            return "match";
        } else {
            // Either email not found or password is incorrect
            return "mismatch";
        }
    }

    /**
     * REGISTER/INSERT USER METHOD
     * 
     * Creates a new user account with provided information.
     * Performs validation and security checks before insertion.
     * 
     * @param string $name - User's last name
     * @param string $firstname - User's first name
     * @param string $gender - User's gender
     * @param string $email - User's email address (must be unique)
     * @param string $password - User's plain text password
     * @param string $function - User's job title/position
     * @param string $cin - Identification number (CIN/ID number)
     * @param string $photo - User's profile photo (file path or name)
     * 
     * @return string - Status of registration:
     *                  "inserted" = Successfully created user
     *                  "alreadyRegistered" = Email already exists in database
     * 
     * SECURITY:
     * - Password is hashed using PASSWORD_DEFAULT before storage
     * - Duplicate email prevention
     * - Uses prepared statements to prevent SQL injection
     * 
     * PROCESS:
     * 1. Query database to check if email already exists
     * 2. If email exists: return "alreadyRegistered"
     * 3. If email is new:
     *    a. Hash the password using PASSWORD_DEFAULT
     *    b. INSERT new user into database with all provided data
     *    c. Return "inserted"
     * 
     * DATABASE QUERIES:
     * 
     * QUERY 1 - Check existing email:
     * SELECT * FROM user WHERE EMAIL=?
     * 
     * QUERY 2 - Insert new user:
     * INSERT INTO user (NAME, FIRSTNAME, GENDER, EMAIL, PASSWORD, FUNCTION, CIN, PHOTO)
     * VALUES (?, ?, ?, ?, ?, ?, ?, ?)
     * 
     * RETURN VALUES:
     * - "alreadyRegistered": Email already exists in database
     * - "inserted": User successfully created
     * 
     * EXAMPLE:
     * $loginModel = new LoginModel();
     * $result = $loginModel->insertUser(
     *     "Smith",
     *     "John",
     *     "Male",
     *     "john.smith@email.com",
     *     "password123",
     *     "Manager",
     *     "12345678",
     *     "photo_john.jpg"
     * );
     * 
     * if($result === "inserted") {
     *     echo "Registration successful!";
     * } elseif($result === "alreadyRegistered") {
     *     echo "Email already registered!";
     * }
     * 
     * PASSWORD HASHING:
     * - PASSWORD_DEFAULT uses bcrypt (current best practice)
     * - Each hash is unique (includes salt)
     * - Cannot be reversed to get original password
     * - password_verify() is used to check passwords at login
     * 
     * DATABASE TABLE STRUCTURE:
     * user(
     *   ID INT PRIMARY KEY AUTO_INCREMENT,
     *   NAME VARCHAR(250),
     *   FIRSTNAME VARCHAR(250),
     *   GENDER VARCHAR(100),
     *   EMAIL VARCHAR(50) UNIQUE,  // Should be UNIQUE in actual database
     *   PASSWORD VARCHAR(250),      // Stores hashed password
     *   FUNCTION VARCHAR(100),
     *   CIN INT,
     *   PHOTO VARCHAR(100)
     * )
     */
    public function insertUser($name, $firstname, $gender, $email, $password, $function, $cin, $photo)
    {
        // Check if email already exists in database
        // This prevents duplicate user accounts with same email
        $data = $this->loginModel->custom("SELECT * FROM user WHERE EMAIL=?", "select")->execute([$email]);
        
        // If email already exists in database
        if ($data) {
            // Email is already registered - return error status
            return "alreadyRegistered";
        } else {
            // Email is new - proceed with user registration
            
            // Hash the password using PASSWORD_DEFAULT algorithm (bcrypt)
            // This converts plain text password into secure hash
            // Example: "password123" becomes "$2y$10$aBcDeF..."
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            // Execute INSERT query to create new user
            $this->loginModel->custom(
                // SQL INSERT query with placeholders
                "INSERT INTO user (NAME,FIRSTNAME,GENDER,EMAIL,PASSWORD,FUNCTION,CIN,PHOTO) VALUES (?,?,?,?,?,?,?,?)",
                "insert"  // Query type
            )->execute([
                // Parameters in same order as ? placeholders
                $name,              // NAME column
                $firstname,         // FIRSTNAME column
                $gender,            // GENDER column
                $email,             // EMAIL column
                $hashedPassword,    // PASSWORD column (hashed)
                $function,          // FUNCTION column
                $cin,               // CIN column
                $photo              // PHOTO column
            ]);
            
            // Return success status
            return "inserted";
        }
    }
}
