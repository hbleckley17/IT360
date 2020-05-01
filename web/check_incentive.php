<?php
  // check_incentive.php: Check all user's incentives
  //Lani Davis m201368

  // Load the Page Class (insnamee of page.inc.php)
  require_once("auth.inc.php");
  require_once("page.inc.php");
  require_once('mysql.inc.php');    # MySQL Connection Library
  $db = new myConnectDB();
  if (mysqli_connect_errno()) {
  echo "<h5>ERROR: " . mysqli_connect_errno() . ": " . mysqli_connect_error() . " </h5><br>";
  }
  // Create a Page object and set the Page title to "Attendance Display"
  $page = new Page("USNA Weekend Tracker");

  // Add to the Page (everything we want displayed)
  $page->content = '
  <div class="container-fluid text-left">
    <div class="col-sm-12 text-left">
      <h1>Check your incentives!</h1>
      <p> You are currently logged in as alpha: '.$username.'<br>
      Check what incentives you have!</p>
      <hr>
    </div>
    ';

    $query = "SELECT incentive_id,incentives_available,rewarddescrip
                FROM incentives_available JOIN incentives USING (incentive_id)
                WHERE alpha = ?";
      $stmt = $db->stmt_init();
      $stmt->prepare($query);
      $stmt->bind_param('i', $username);
      $success = $stmt->execute();
      if (!$success || $db->affected_rows == 0) {
      $page->content .= "<h5>ERROR: " . $db->error . " for query *$query*</h5><hr>";
      }
      $page->content .= "</div><h2>Your Current Incentives </h2><div class=\"row content\">  <div class=\"col-sm-8 text-left\"> <table class=\"table table-striped table-bordered table-hover\"><thead><tr><th> IncentiveID </th><th> Name of the Incentive </th><th> Reward Description</th></tr></thead><tbody>";

      $stmt->bind_result($incentive_ID, $Name, $Descrip);
      while ($row = $stmt->fetch()) {
        $page->content .= "<tr><td>$incentive_ID</td><td>$Name</td><td>$Descrip</td></tr>";
      }

      $page->content .= "</tbody></table></div></div></div></div>";

// Show the page
#$_SESSION['date'] = date(DATE_RSS);
#update($db, $user, session_encode());
$page->display();
?>
