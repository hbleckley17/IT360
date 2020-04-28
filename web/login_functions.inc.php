<?php
  /* login_functions.inc.php
   * Bodine, Drake - 200540
   *
   * LOGIN-Related Function Definitions
   * Note: Code uses and derives from a lot of the example code provided on the
   *       course website
   */

  // Verify username/password combo and add sessionid to database
  //   Ex: <bool> = logon($db, $username, $password, $sessionid);
  function logon($db, $username, $password, $sessionid, $test=FALSE) {
    /// VERIFY USERNAME/PASSWORD IN DB
    // Create MySQL query to get hash for given username
    $query = "SELECT password
                FROM midshipmen
                WHERE alpha = '$username'";
    
    // Execute and check results
    $stmt = $db -> stmt_init();
    $stmt -> prepare($query);
    $success = $stmt -> execute();

    // Error check only when requested via $test:
    if ($test && !$success){
      echo "<h5>ERROR: " . $db -> error . " validating user credentials with database!</h5> in logon()<hr> Please Try Again!";
    }
    
    // Check hash of entered password against db's
    $stmt -> bind_result($hashDB); // store hash from db in $hashDB
    $stmt->fetch();
    if(!password_verify($password, $hashDB)) { // password incorrect! Fail!
      if($test) {
        $sTmp = "Password hashes don't match for $username!<br>";
        $sTmp .= "DB Hash: $hashDB";
        echo $sTmp;
        die;
      }
      return False;
    }
    
    /// UPDATE LAST LOGIN IN AUTH_USER TABLES
    // Create MySQL update statement for midshipmen
    $query = "UPDATE midshipmen
                SET lastlogin = NOW()
                WHERE alpha = '$username'";

    // Execute and check results
    $stmt = $db -> stmt_init();
    $stmt -> prepare($query);
    $success = $stmt -> execute();

    // Error check only when requested via $test:
    if ($test && !$success){
      echo "<h5>ERROR: " . $db -> error . " for user login update *$query*</h5> in logon()<hr> Please Try Again!";
    } else if ( $test ) {
      echo "<h4> Row: $username updated! </h4>";
    }
   
    // ADD SESSIONID LINKED TO USER TO AUTH_SESSION TABLE
    // Create MySQL insert statement
    $query = "INSERT INTO auth_session (alpha, id)
                VALUES('$username', '$sessionid')
              ON DUPLICATE KEY UPDATE alpha=VALUES(alpha), lastvisit=NOW()";

    // Execute and check results
    $stmt = $db -> stmt_init();
    $stmt -> prepare($query);
    $success = $stmt -> execute();

    // Error check only when requested via $test:
    if ($test && !$success){
      echo "<h5>ERROR: " . $db -> error . " for insertion *$query*</h5> in logon()<hr> Please Try Again!";
      die;
    } else if ( $test ) {
      echo "<h4> Row: Session ID: '$sessionStr' added/updated in Database! </h4>";
    }

    return $success;
  }

  // Verify login status, returning session and relating info IF LOGGED ON
  //   Ex: <string username> = verify($db, $sessionid);
  function verify($db, $sessionid, $LAST_VISIT_LIMIT="INTERVAL 1 HOUR", 
                  $LAST_LOGIN_LIMIT="INTERVAL 1 DAY", $test=FALSE) {
    /// QUERY AUTH_SESSION BY SESSIONID & AUTH_USER(SESSION)
    // Create MySQL query for user, session, and lastvisit
    $query = "SELECT midshipmen.alpha, session
                FROM auth_session JOIN midshipmen 
                  ON auth_session.alpha = midshipmen.alpha
                WHERE id = '$sessionid'
                  AND (NOW() < (DATE_ADD(lastvisit, $LAST_VISIT_LIMIT)))
                  AND (NOW() < (DATE_ADD(lastlogin, $LAST_LOGIN_LIMIT)))";
    
    // Execute and check results
    $stmt = $db -> stmt_init();
    $stmt -> prepare($query);
    $success = $stmt -> execute();

    // Error check only when requested via $test:
    if ($test && !$success) {
      echo "<h5>ERROR: " . $db -> error . " validating user credentials with database!</h5> in verify()<hr> Please Try Again!";
      die;
    }
    
    // Get query results
    $stmt -> bind_result($user, $sessionEnc);
    if(!$stmt->fetch()) { // no valid instance, return false!
      return '';
    }

    /// GET SESSION DATA AND UPDATE $_SESSION
    session_decode($sessionEnc);

    /// UPDATE LAST VISIT
    // Create MySQL update statement for auth_session
    $query = "UPDATE auth_session
                SET lastvisit = NOW()
                WHERE id = '$sessionid'";

    // Execute and check results
    $stmt = $db -> stmt_init();
    $stmt -> prepare($query);
    $success = $stmt -> execute();

    // Error check only when requested via $test:
    if ($test && !$success){
      echo "<h5>ERROR: " . $db -> error . " for user lastvisit update *$query*</h5> in verify()<hr> Please Try Again!";
    } else if ( $test ) {
      echo "<h4> Row: $username updated! </h4>";
    }

    /// RETURN USERNAME
    if($user == 0) return True; // for "admin" alpha of 0
    return $user;
  }


  // Log user off, removing sessionid row from db
  //   Ex: logoff($db, $sessionid);
  function logoff($db, $sessionid, $test=FALSE) {
    /// DELETE SESSIONID FROM AUTH_SESSION
    // Create MySQL delete statement for auth_session
    $query = "DELETE FROM auth_session
                WHERE id = '$sessionid'";

    // Execute and check results
    $stmt = $db -> stmt_init();
    $stmt -> prepare($query);
    $success = $stmt -> execute();

    // Error check only when requested via $test:
    if ($test && !$success){
      echo "<h5>ERROR: " . $db -> error . " for updating SESSION string *$query*</h5> in update()<hr> Please Try Again!";
    } else if ( $test ) {
      echo "<h4> Row: $username updated! </h4>";
    }
  }


  // Save session info, updating session string for specific user
  //   Ex: update($db, $username, $session_string);
  function update($db, $username, $session_string) {
    /// UPDATE SESSION FIELD IN AUTH_USER
    // Create MySQL update statement for midshipmen
    $query = "UPDATE midshipmen
                SET session = '$session_string'
                WHERE alpha = '$username'";

    // Execute and check results
    $stmt = $db -> stmt_init();
    $stmt -> prepare($query);
    $success = $stmt -> execute();

    // Error check only when requested via $test:
    if ($test && !$success){
      echo "<h5>ERROR: " . $db -> error . " for user lastvisit update *$query*</h5> in verify()<hr> Please Try Again!";
    } else if ( $test ) {
      echo "<h4> Row: $username updated! </h4>";
    }
  }
?>
