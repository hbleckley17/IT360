<?php
  //Harrison Bleckley m210498
  // Load the Page Class, Init DB
  require_once("auth.inc.php");
  require_once("page.inc.php");
  require_once('mysql.inc.php');    # MySQL Connection Library
  $db = new myConnectDB();
  if (mysqli_connect_errno()) {
  echo "<h5>ERROR: " . mysqli_connect_errno() . ": " . mysqli_connect_error() . " </h5><br>";
  }
  // Create a Page object and set the Page title to "Approve Weekends"

    #if($username == 200000){
      $page = new Page("Approve Weekends");

  // Add company submission to the page
  $page->content = '
  <div class="container-fluid text-left">
    <div class="col-sm-12 text-left">
      <h1>Select Company</h1>
    </div>

    <div class="col-sm-8 text-left">
      <form id="form1" action="approve.php" method="post">
      <div class="form-group">
        <label for="exampleFormControlInput1">Enter Company (1-30)</label>
        <input type="int" class="form-control" name="company" placeholder="1">
      </div>

      <button type="submit" class="btn btn-primary">Submit</button>
      </form>
    </div>
  ';

$company=$_POST['company'];


  if(($company < 1 || $company > 30) && !isset($_POST['approvedAlphas'])) {
    $page->content .= '
    <div class="col-sm-12 text-left"><h2>Company must be 1-30 </h2>
    </div>
  </div>';
  }
  else {

    $query = "SELECT midshipmen.alpha,midshipmen.firstname,midshipmen.lastname,midweekend.buddyname,midweekend.buddyphone,weekendplans.wID,weekendplans.address,weekendplans.description
              FROM midweekend join midshipmen on midshipmen.alpha=midweekend.alpha JOIN weekendplans on weekendplans.wid=midweekend.wid Join Company on midweekend.alpha=Company.alpha
              WHERE Company.Company = ? AND midweekend.alpha NOT in (SELECT alpha FROM approved);";

      $stmt = $db->stmt_init();
      $stmt->prepare($query);
      $stmt->bind_param('i', $company);
      $success = $stmt->execute();
      if (!$success || $db->affected_rows == 0) {
        $page->content .= "<h5>ERROR: " . $db->error . " for query *$query*</h5><hr>";
      }
      $page->content .= "
      </div>

      <h2>Company Members</h2>

  <form id ='form2' method='post' action='approve.php'>
      <div class=\"row content\">
       <div class=\"col-sm-8 text-left\">

        <table class=\"table table-striped table-bordered table-hover\">
        <thead><tr><th> Name </th><th> Alpha </th><th> Buddy </th><th> Buddy Phone </th><th> WID </th><th> Address </th><th> Description </th><th> Approved </th></tr></thead>
        <tbody>";

      $stmt->bind_result($alpha, $firstname, $lastname, $buddyname, $buddyphone, $wID, $address, $description);
      while ($row = $stmt->fetch()) {
        $page->content .= "
        <tr>
          <td>$lastname, $firstname</td>
          <td>$alpha</td>
          <td>$buddyname</td>
          <td>$buddyphone</td>
          <td>$wID</td>
          <td>$address</td>
          <td>$description</td>
          <td><input type='checkbox' name='approvedAlphas[]' id='approvedAlphas' value='$alpha'></td>
        </tr>";
      }

      $page->content .= "</tbody></table>
      <button type='submit' class='btn btn-primary'>Approve</button>
      </div></form>";


      }


      if (isset($_POST['approvedAlphas'])) {
        $approvedAlphas = $_POST['approvedAlphas'];
        $page->content .= '

        <div class="container-fluid text-left">
        <div class="col-sm-8 text-left">
        ';

        $allwID = getWID($db, $approvedAlphas);
        $allWeekendsLeft = getWeekendsLeft($db, $approvedAlphas);

        $approvedWeekendsFinal = getApprovedFinal($approvedAlphas, $allWeekendsLeft, $allwID);
        $notApprovedWeekendsFinal = getNotApproved($approvedAlphas, $allWeekendsLeft, $allwID);

        submitApproved($db, $approvedWeekendsFinal);
        submitNotApproved($db, $notApprovedWeekendsFinal);

        if (sizeof($approvedWeekendsFinal) != 0){
          $page->content .= '
          <br>
              <h1>Success! The following alphas have been approved</h1>
              <hr>
            ';
        foreach ($approvedWeekendsFinal as $alpha => $out){
          $page->content .= '
          <h3>m'.$alpha.' </h3>
          ';
        }
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

        $page->content .= '
        </div></div>';
      }

      $page->content .= '
      </div></div></div>';

      if (!isset($_POST['company'])) {
        $company = getCompany($db, $approvedAlphas[0]);
      }

      if (isset($_POST['company']) || $company != null) {

      $page->content .= "
      <div class=\"container-fluid text-left\">
      <div class=\"col-sm-8 text-left\">
      <h1>Already Reviewed</h1>
      <hr>


      <div class=\"row content\">
       <div class=\"col-sm-8 text-left\">

        <table class=\"table table-striped table-bordered table-hover\">
        <thead><tr><th> Name </th><th> Alpha </th><th> WID </th><th> Approved </th></tr></thead>
        <tbody>";

          $tempApp = "Yes";

          $query = "SELECT midshipmen.alpha,midshipmen.firstname,midshipmen.lastname,weekendplans.wID, approved.approved
                    FROM midweekend join midshipmen on midshipmen.alpha=midweekend.alpha JOIN weekendplans on weekendplans.wid=midweekend.wid
                    Join Company on midweekend.alpha=Company.alpha JOIN approved on midweekend.alpha = approved.alpha
                    WHERE Company.Company = ?;";

          $stmt = $db->stmt_init();
          $stmt->prepare($query);
          $stmt->bind_param('i', $company);
          $success = $stmt->execute();


          $stmt->bind_result($alpha, $firstname, $lastname, $wID, $approved);


          while ($row = $stmt->fetch()) {
            if($approved == 1) {
              $tempApp = "Yes";
            }
            else if ($approved == 2) {
              $tempApp = "No";
            }
            $page->content .= "
            <tr>
              <td>$lastname, $firstname</td>
              <td>$alpha</td>
              <td>$wID</td>
              <td>$tempApp</td>
            </tr>";
          }

          $page->content .= "</tbody></table>
          </div></div>";

          $page->content .= '
          </div></div>';
        }


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
          $num = 2;

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

          function getCompany($db, $same) {
            $query = "SELECT Company
                      FROM Company
                      WHERE alpha = ?;";

              $stmt = $db->stmt_init();
              $stmt->prepare($query);
              $stmt->bind_param('i', $same);
              $success = $stmt->execute();


              $stmt->bind_result($co);


              while ($row = $stmt->fetch()) {
                $thisCompany = $co;
              }
              return $thisCompany;
          }
        #}

        #  else{
        #      $page = new Page("Approve Weekends");
        #      $page->content = "<h1>You are not an administrator and are not allowed to use this page. </h1>";
        #  }

  #$stmt->close();


  // Show the page
  #$_SESSION['date'] = date(DATE_RSS);
  #update($db, $user, session_encode());
  $page->display();
  ?>
