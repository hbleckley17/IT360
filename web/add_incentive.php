<?php
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
      <h1>Add an Incentive to your Weekend</h1>
      <p> Lucky mid you have an incentive to use. Add it to your weekend plan to redeem it!<br>
      * indicates a required field</p>
      <hr>
    </div>
    <div class="col-sm-8 text-left">
      <form action="add_incentive.php" method="post">
        <div class="form-group">
          <label for="exampleFormControlInput1">WID* </label>
          <input type="wid" class="form-control" name="wid" placeholder="###">
        </div>
        <div class="form-group">
          <label for="exampleFormControlInput1"> Incentive ID* </label>
          <input type="incentiveID" class="form-control" name="incentiveID" placeholder="##">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
        </form>
        </div>
        ';

$alpha=$username;
$wid=$_POST["wid"];
$incentive=$_POST["incentiveID"];

if($incentive!='' && $alpha!='' && $wid!=''){
  $query = "INSERT INTO weekendextra (wID,incentive_id,alpha)
            VALUES(?,?,?)";
  $stmt = $db->stmt_init();
  $stmt->prepare($query);
  $stmt->bind_param('iii', $wid, $incentive, $alpha);
  #$page->content .= $stmt;
  $success = $stmt->execute();
  if (!$success || $db->affected_rows == 0) {
  $page->content .= '<div class="col-sm-12 text-left"><h2>ERROR: ' . $db->error . '. Please try again </h2>
  </div></div>';
  }
  else {
    $page->content .= '<h2> Your incentive was successfully added. Thank you and have fun! </h2></div></div>';
  }
}
else{
  $page->content .= '<div class="col-sm-12 text-left"><h2>Please fill out the required fields in the form above to add an incentive. </h2>
  </div></div>';
}

// Show the page
#$_SESSION['date'] = date(DATE_RSS);
#update($db, $user, session_encode());
$page->display();
?>
