<?php
  /* check_weekend.php
   * Bodine, Drake - 200540
   *
   * This page shows allows the user to check his/her weekend status
   *
  */

  /// Function Definitions ///
  
  // Delete the weekend entry with weekend ID wID for the alpha at username
  function deleteWeekendEntry($db, $username, $wID, $test=FALSE) {
    // Create MySQL delete statement for approved table
    $query = "DELETE FROM approved WHERE alpha = $username AND wID = $wID;";

    // Execute and check results
    $stmt = $db -> stmt_init();
    $stmt -> prepare($query);
    $success = $stmt -> execute();

    // Error check only when requested via $test:
    if ($test && !$success) {
      echo "<h5>ERROR: " . $db -> error . " for deleting from approved";
    } else if ( $test ) {
      echo "<h4> Row: $username deleted! </h4>";
    }

    // Create MySQL delete statement for weekendextra table
    $query = "DELETE FROM weekendextra WHERE alpha = $username AND wID = $wID;";

    // Execute and check results
    $stmt = $db -> stmt_init();
    $stmt -> prepare($query);
    $success = $stmt -> execute();

    // Error check only when requested via $test:
    if ($test && !$success) {
      echo "<h5>ERROR: " . $db -> error . " for deleting from weekendextra";
    } else if ( $test ) {
      echo "<h4> Row: $username deleted! </h4>";
    }

    // Create MySQL delete statement for midweekend table
    $query = "DELETE FROM midweekend WHERE alpha = $username AND wID = $wID;";

    // Execute and check results
    $stmt = $db -> stmt_init();
    $stmt -> prepare($query);
    $success = $stmt -> execute();

    // Error check only when requested via $test:
    if ($test && !$success) {
      echo "<h5>ERROR: " . $db -> error . " for deleting from midweekend";
    } else if ( $test ) {
      echo "<h4> Row: $username deleted! </h4>";
    }
  }

  // Create table filled with all the current weekend list entries for the
  // the alpha username
  function getWeekendStatusTable($db, $username) {
    // Setup info above table
    $table = '<div class="container-fluid text-left">
                <div class="col-sm-12 text-left">
                  <h1>Weekend Status ('.$username.'):</h1>
                  <hr>';

    // Get weekend data from DB
    $query = "SELECT t2.wID, buddyname, buddyphone, address, description,
                     incentive_id, approved
                FROM ((SELECT t.wID, t.alpha, buddyname, buddyphone, address,
                              description, incentive_id
                         FROM ((SELECT midweekend.wID, alpha, buddyname,
                                       buddyphone, address, description
                                  FROM midweekend JOIN weekendplans
                                       ON midweekend.wID = weekendplans.wID
                                  WHERE alpha = ?)
                              AS t)
                         LEFT JOIN weekendextra ON t.alpha = weekendextra.alpha)
                     AS t2)
                LEFT JOIN approved ON t2.alpha = approved.alpha;";
    $stmt = $db->stmt_init();
    $stmt->prepare($query);
    $stmt->bind_param('i', $username);
    $success = $stmt->execute();
    if (!$success || $db->affected_rows == 0) {
      $page->content .= "<h5>ERROR: " . $db->error . " for query *$query*</h5><hr>";
    }

    // Populate table w/ DB info returned
    $table .= "<div class=\"row content\">
                 <div class=\"col-sm-12 text-left\">
                   <table class=\"table table-striped table-bordered table-hover\">
                      <thead>
                        <tr>
                          <th> wID  </th>
                          <th> Buddy Name </th>
                          <th> Buddy Phone </th>
                          <th> Address </th>
                          <th> Description </th>
                          <th> Incentive ID </th>
                          <th> Approved? </th>
                          <th> Delete? </th>
                        </tr>
                      </thead>
                    <tbody>";

    $ct = 0;
    $stmt->bind_result($wID, $bName, $bPhone, $addr, $desc, $iID, $approved);
    while ($row = $stmt->fetch()) {
      $ct += 1;
      if($approved == 1) {
        $approved = "<span style=\"color: green;\">Yes</span>";
      } elseif ($approved == 0) {
        $approved = "<span style=\"color: red;\">NO</span>";
      } else {
        $approved = "<i>Pending</i>";
      }
      $button = getDeleteButton($wID);
      $table .= "<tr><td>$wID</td>
                     <td>$bName</td>
                     <td>$bPhone</td>
                     <td>$addr</td>
                     <td>$desc</td>
                     <td>$iID</td>
                     <td>$approved</td>
                     <td>$button</td></tr>";
    }
    $table .= "</tbody></table>";

    if($ct > 1) {
      $table .= "<p><h3><span style=\"color: red;\">*ATTENTION:</span> You 
        must only have one pending weekend!  Please delete the plan(s) you do
        not wish to submit.</h3></p>";
    }
    
    $table .= "</div></div></div></div>";

    return $table;
  }

  // Get html string for delete button for the weekend ID wID
  function getDeleteButton($wID) {
    $button .= "<form action=\"\" method=\"post\" 
                     onsubmit=\"return confirm('Are you sure?')\">";
    $button .= "<input type=\"hidden\" name=\"wID\" value=\"$wID\" />";
    $button .= "<input name='delete' type='submit' value='X' class='btn btn-danger'>";
    $button .= "</form>";

    return $button;
  }

  /// Included files ///
  require_once("auth.inc.php"); // ensure user is logged in
  require_once("page.inc.php"); // get Page class
  require_once('mysql.inc.php'); // MySQL Connection Library

  /// Populate page with weekend status if any ///
  // But first check for deletion
  if (isset($_POST['delete'])) {
    $wID = $_POST['wID'];

    // Delete weekend entry data from DB
    deleteWeekendEntry($db, $username, $wID);
  }

  $db = new myConnectDB();
  if (mysqli_connect_errno()) {
    echo "<h5>ERROR: " . mysqli_connect_errno() . ": "
      . mysqli_connect_error() . " </h5><br>";
  }

  // Create a Page object
  $page = new Page("USNA Weekend Tracker");

  // Add to the Page (everything we want displayed)
  $page->content = getWeekendStatusTable($db,$username);

  // Show the page
  $page->display();
?>
