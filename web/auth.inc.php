<?php
  /* auth.inc.php
   * Bodine, Drake - 200540
   *
   * Validate user. If not currently logged on or PHP session is no longer
   * valid, redirect user to 'login.php'.
  */

  // NOTE: this is mostly example code from the course website
  require_once('login_functions.inc.php'); # Login-relatated functions
  require_once('mysql.inc.php');           # MySQL Connection Library
  $db = new myConnectDB();                 # Connect to MySQL

  session_start();                  # Start the Session
  $sessionid = session_id();        # Retrieve the session id

  // LOGON THE USER (if requested)  # Check to see if user/password were sent
  if (isset($_POST['username']) && isset($_POST['password'])) {
                                    # Validate the user/password combination
    if (!logon($db, $_POST['username'], $_POST['password'], $sessionid)) {
      header('Location: login.php');# Redirect the user to the login page
      die;                          # End the script (just in case)
    }
  }

  // VERIFY THE USER IS LOGGED ON
  $username = verify($db, $sessionid);  # Verify the user, return username or ''
  if ($username == '') {                # User was not successfully verified!
    header('Location: login.php');      # Redirect the user to the login page
    die;                                # End the script (just in case)
  }

  // LOGOFF THE USER
  /*
  if (isset($_REQUEST['logoff'])) { # Did the user request to logoff?
    logoff($db, $sessionid);        # Remove the row with this sessionid
    header('Location: login.php');  # Redirect the user to the login page
    die;                            # End the script (just in case)
  }
   */

?>
