<?php
  /* logout.php
   * Bodine, Drake - 200540
   * 
   * This page serves as the logout page for storefront
   * 
   */

  /// Function Definitions ///

  // Get successful logout message and button to go back to select_product.php
  function getLogoutMessage($homepage) {
    $msg = "<div class='container'>";
    $msg .= "<div class='jumbotron mt-3'>";
    $msg .= "<h3>Logout successful! Thank you for stopping by!</h3>";
    $msg .= "<p>";
    $msg .= "<form action='$homepage'>";
    $msg .= "<input type='submit' value='Go Back' class='btn btn-success'>";
    $msg .= "</form>";
    $msg .= "</p>";
    $msg .= "</div>";
    $msg .= "</div>";

    return $msg;
  }

  /// Page setup ///

  $homePage = 'add_weekend.php';
  require_once("auth.inc.php"); // get HTML page class
  require_once("page.inc.php"); // get HTML page class
  require_once("login_functions.inc.php"); // get logout()
  require_once("mysql.inc.php"); // connect to db

  $db = new myConnectDB();                 # Connect to MySQL

  session_start();                  # Start the Session
  $sessionid = session_id();        # Retrieve the session id

  // Log user out
  logoff($db, $sessionid);

  // Create Page object and title accordingly
  $page = new Page("Log-Out Page");

  // Populate page with login form to enter credentials
  $page->content = getLogoutMessage($homePage);

  // Display page
  $page->display(True,True,False);
?>
