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
  $page = new Page("Approve Weekends");

  //if($username != )

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
  $company=$_POST["company"];

  if(($company < 1 || $company > 30) ) {
    $page->content .= '
    <div class="col-sm-12 text-left"><h2>Company must be 1-30 </h2>
    </div>
  </div>';
  }
  else {

    $query = "SELECT midshipmen.alpha,midshipmen.firstname,midshipmen.lastname,midweekend.buddyname,midweekend.buddyphone,weekendplans.wID,weekendplans.address,weekendplans.description
              FROM midweekend join midshipmen on midshipmen.alpha=midweekend.alpha JOIN weekendplans on weekendplans.wid=midweekend.wid Join Company on midweekend.alpha=Company.alpha
              WHERE Company.Company = ?;";

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

  <form id ='form2' method='post' action='completeApprove.php'>
      <div class=\"row content\">
       <div class=\"col-sm-8 text-left\">

        <table class=\"table table-striped table-bordered table-hover\">
        <thead><tr><th> Alpha </th><th> Name </th><th> Buddy </th><th> Buddy Phone </th><th> WID </th><th> Address </th><th> Description </th><th> Approved </th></tr></thead>
        <tbody>";

      $stmt->bind_result($alpha, $firstname, $lastname, $buddyname, $buddyphone, $wID, $address, $description);
      while ($row = $stmt->fetch()) {
        $page->content .= "
        <tr>
          <td>$alpha</td>
          <td>$firstname $lastname</td>
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
      </div></div></form>";


      }

  #$stmt->close();


  // Show the page
  #$_SESSION['date'] = date(DATE_RSS);
  #update($db, $user, session_encode());
  $page->display();
  ?>
