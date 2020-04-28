<?php
  //Harrison Bleckley
  // Load the Page Class (insnamee of page.inc.php)
  require_once("auth.inc.php");
  require_once("page.inc.php");
  require_once('mysql.inc.php');    # MySQL Connection Library
  $db = new myConnectDB();
  if (mysqli_connect_errno()) {
  echo "<h5>ERROR: " . mysqli_connect_errno() . ": " . mysqli_connect_error() . " </h5><br>";
  }
  // Create a Page object and set the Page title to "Attendance Display"
  $page = new Page("Approved!");

  // Add to the Page Success msg
  $page->content = '
  <div class="container-fluid text-left">
    <div class="col-sm-12 text-left">
      <h1>Success! The following alphas have been approved</h1>
      <hr>
    ';


    if (isset($_POST['approvedAlphas'])) {
      $approvedAlphas = $_POST['approvedAlphas'];

      $allwID = getWID($db, $approvedAlphas);
      $allWeekendsLeft = getWeekendsLeft($db, $approvedAlphas);

      $approvedWeekendsFinal = getApprovedFinal($approvedAlphas, $allWeekendsLeft, $allwID);
      $notApprovedWeekendsFinal = getNotApproved($approvedAlphas, $allWeekendsLeft, $allwID);

      submitApproved($db, $approvedWeekendsFinal);
      submitNotApproved($db, $notApprovedWeekendsFinal);

      foreach ($approvedWeekendsFinal as $alpha => $out){
        $page->content .= '
        <h3>m'.$alpha.' </h3>
        ';
      }

      if (sizeof($notApprovedWeekendsFinal) != 0){
        $page->content .= '
        <br>
            <h1>Error! The following alphas have no weekends left</h1>
            <hr>
          ';
        foreach ($notApprovedWeekendsFinal as $alpha => $out){
          $page->content .= '
          <h3>m'.$alpha.' </h3>
          ';
        }
      }
    }

    $page->content .= "</div></div>";

    function submitApproved($db, $approvedWeekendsFinal) {
      foreach($approvedWeekendsFinal as $alpha => $wID) {
        $tempID = $wID;
        $num = 1;

        $query = "INSERT INTO approved (wID, alpha, approved)
                  VALUES (?,?,?)";
        $stmt = $db->stmt_init();
        $stmt->prepare($query);
        $stmt->bind_param('iii', $tempID, $alpha, $num);
        #$page->content .= $stmt;
        $success = $stmt->execute();
      }
    }

    function submitNotApproved($db, $notApproved) {
      foreach($notApproved as $alpha => $wID) {
        $tempID = $wID;
        $num = 0;

        $query = "INSERT INTO approved (wID, alpha, approved)
                  VALUES (?,?,?)";
        $stmt = $db->stmt_init();
        $stmt->prepare($query);
        $stmt->bind_param('iii', $tempID, $alpha, $num);
        #$page->content .= $stmt;
        $success = $stmt->execute();
      }
    }

    function getApprovedFinal($approvedAlphas, $weekendsLeft, $wID) {
      $approvedWeekendsFinal = array();
      $size = count($weekendsLeft);

      for ($x = 0; $x < $size; $x+=1) {
        if($weekendsLeft[$x] != 0) {
          $approvedWeekendsFinal[$approvedAlphas[$x]] = $wID[$x];
        }
      }
      return $approvedWeekendsFinal;
    }

    function getNotApproved($approvedAlphas, $weekendsLeft, $wID) {
      $notApproved = array();
      $size = count($weekendsLeft);

      for ($x = 0; $x < $size; $x+=1) {
        if($weekendsLeft[$x] == 0) {
          $notApproved[$approvedAlphas[$x]] = $wID[$x];
        }
      }
      return $notApproved;
    }

    function getWID($db, $approvedAlphas) {
      $allwID = array();
      foreach ($approvedAlphas as $alpha){
        $query = "SELECT wID
                    FROM midweekend
                    WHERE alpha = ?";
          $stmt = $db->stmt_init();
          $stmt->prepare($query);
          $stmt->bind_param('i', $alpha);
          $success = $stmt->execute();
          if (!$success || $db->affected_rows == 0) {
          $page->content .= "<h5>ERROR: " . $db->error . " for query *$query*</h5><hr>";
          }

          $stmt->bind_result($wID);
          while ($row = $stmt->fetch()) {
            array_push($allwID,  $wID);
          }
        }
        return $allwID;
      }

      function getWeekendsLeft($db, $approvedAlphas) {
        $allWeekendsLeft = array();
        foreach ($approvedAlphas as $alpha){
          $query = "SELECT weekends_left
                      FROM weekends_left
                      WHERE alpha = ?";
            $stmt = $db->stmt_init();
            $stmt->prepare($query);
            $stmt->bind_param('i', $alpha);
            $success = $stmt->execute();
            if (!$success || $db->affected_rows == 0) {
              $page->content .= "<h5>ERROR: " . $db->error . " for query *$query*</h5><hr>";
            }

            $stmt->bind_result($weekendsLeft);
            while ($row = $stmt->fetch()) {
              array_push($allWeekendsLeft, $weekendsLeft);
            }
          }
          return $allWeekendsLeft;
        }



// Show the page
#$_SESSION['date'] = date(DATE_RSS);
#update($db, $user, session_encode());
$page->display();
?>
