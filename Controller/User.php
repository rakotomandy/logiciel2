<?php
/**
 * ============================================================================
 * USER CONTROLLER - User.php
 * ============================================================================
 * 
 * The User controller handles user management features.
 * It provides:
 * 1. User dashboard display (admin interface)
 * 2. User insertion/creation via AJAX
 * 
 * ROUTE ACCESS:
 * - Display dashboard: http://localhost/logiciel/?action=User
 *   Method Called: User::index()
 * 
 * - Insert new user: http://localhost/logiciel/?action=User/insert
 *   Method Called: User::insert()
 *   Expected: AJAX POST request with user data
 * 
 * RESPONSIBILITIES:
 * 1. Display user management dashboard with sidebar
 * 2. Load admin-specific CSS and JavaScript
 * 3. Handle AJAX requests for user insertion
 * 4. Return JSON responses for AJAX operations
 * 
 * FLOW FOR INDEX:
 * 1. Root router calls User::index()
 * 2. Load admin header with admin CSS
 * 3. Load admin sidebar navigation
 * 4. Load user management view
 * 5. Load footer with admin scripts
 * 6. HTML dashboard is rendered
 * 
 * FLOW FOR INSERT:
 * 1. JavaScript sends AJAX POST request
 * 2. Root router calls User::insert()
 * 3. Method returns JSON success response
 * 4. JavaScript processes response and updates UI
 * 
 * ASSOCIATED FILES:
 * - View/user.php: User management dashboard interface
 * - View/template/sideAdmin.php: Admin sidebar navigation
 * - Public/css/admin.css: Admin dashboard styles
 * - Public/js/user.js: User management JavaScript
 * - Public/js/reusable/insert.js: AJAX insertion functionality
 * - Model/LoginModel.php: User data handling (could be extended)
 * ============================================================================
 */

class User
{
    /**
     * INDEX METHOD - Display user management dashboard
     * 
     * This method is called when user accesses:
     * http://localhost/logiciel/?action=User
     * 
     * Displays a full admin interface for user management with:
     * - Admin sidebar navigation
     * - User data table/list
     * - Forms for user creation
     * - Admin-specific styling and functionality
     * 
     * PROCESS:
     * 1. Load header with admin CSS files
     * 2. Load admin sidebar (navigation menu)
     * 3. Load main user management view
     * 4. Load footer with admin JavaScript files
     * 
     * DATA PASSED:
     * - title: Page title ("HOME" for consistency)
     * - css: Admin-specific stylesheets
     * - js: Admin-specific scripts and utilities
     */
    public function index()
    {
        // Load page header with admin configuration
        Load::template("header", [
            // Page title (shown in browser tab)
            "title" => "HOME",
            
            // CSS files for admin dashboard
            "css" => [
                "sweetalert.min",    // SweetAlert styles for alerts
                "all",               // Common styles for all pages
                "admin"              // Admin dashboard specific styles
            ]
        ]);
        
        // Load admin sidebar navigation
        // This includes View/template/sideAdmin.php
        // Displays navigation menu for admin functions
        Load::template("sideAdmin");
        
        // Load main user management view
        // This includes View/user.php
        // Contains user table, creation form, etc.
        Load::view("user");
        
        // Load footer with admin-specific JavaScript files
        Load::template("footer", [
            // JavaScript files for admin functionality
            "js" => [
                "reusable/jquery.min",           // jQuery for DOM manipulation
                "reusable/sweetalert2.all.min",  // SweetAlert2 for alerts
                "reusable/all",                  // Common JavaScript
                "reusable/insert",               // AJAX insertion utility
                "reusable/selectimg",            // Image selection utility
                "user"                           // User management specific scripts
            ]
        ]);
    }

    /**
     * INSERT METHOD - Handle AJAX user insertion requests
     * 
     * This method is called when AJAX sends a request to:
     * http://localhost/logiciel/?action=User/insert
     * 
     * Typically called from JavaScript (Public/js/reusable/insert.js)
     * with user data via AJAX POST request.
     * 
     * PROCESS:
     * 1. Set response content type to JSON
     * 2. Return JSON success response
     * 3. JavaScript processes response on client side
     * 
     * TYPICAL USAGE IN JAVASCRIPT:
     * $.ajax({
     *     url: 'index.php?action=User/insert',
     *     type: 'POST',
     *     data: formData,
     *     success: function(response) {
     *         if(response.status === 'success') {
     *             // Show success message
     *             // Refresh user table
     *         }
     *     }
     * });
     * 
     * RETURNS: JSON object with status
     * Example: {"status": "success"}
     * 
     * NOTE: The actual user insertion logic should be implemented here
     * or delegated to LoginModel::insertUser()
     * 
     * POTENTIAL IMPROVEMENTS:
     * 1. Add error handling (try-catch block)
     * 2. Validate input data
     * 3. Call LoginModel::insertUser() for data persistence
     * 4. Return appropriate success/error status codes
     * 5. Include error messages in JSON response
     */
    public function insert()
    {
        // Set HTTP header to indicate JSON response
        // This tells the browser/AJAX to expect JSON data
        header('Content-Type: application/json');
        
        // Return JSON response to AJAX request
        // This is a basic success response
        // In a real implementation, you would:
        // 1. Extract POST data
        // 2. Validate the data
        // 3. Call LoginModel::insertUser()
        // 4. Return success or error based on result
        echo json_encode([
            "status" => "success"
        ]);
    }
}
