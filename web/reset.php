<?php
  //Lani Davis m201368
  //Allows admin to reset the database and account for used incentives after each weekend.
  require_once("auth.inc.php");
  require_once("page.inc.php");
  require_once('mysql.inc.php');    # MySQL Connection Library
  $db = new myConnectDB();
  if (mysqli_connect_errno()) {
  echo "<h5>ERROR: " . mysqli_connect_errno() . ": " . mysqli_connect_error() . " </h5><br>";
  }
  if($username == 200000){
  // Create a Page object and set the Page title to "Approve Weekends"
  $page = new Page("Reset");
  // Create a Page object and set the Page title to "Attendance Display"


  // Add to the Page (everything we want displayed)
  $page->content = '
  <div class="container-fluid text-left">
    <div class="col-sm-12 text-left">
      <h1>Reset </h1>
      <p> Reset the weekend plans and approvals for a new weekend. Please be careful before hitting the reset button below. This will reset the system for the entire brigade.</p>
      <hr>
    </div>
    <div class="col-sm-8 text-left">
      <form action="reset.php" method="post">
      <div class="form-group">
        <label for="exampleFormControlSelect1">Are you sure you want to reset the system? </label>
        <select type="rtype" class="form-control" name="reset">
          <option>Yes</option>
          <option>No</option>
        </select>
      </div>
        <button type="submit" class="btn btn-primary">Reset</button>
        </form>
        </div>
        ';


$reset=$_POST["reset"];

if($reset == 'Yes'){
  $query = "CALL reset()";
  $stmt = $db->stmt_init();
  $stmt->prepare($query);
  #$page->content .= $stmt;
  $success = $stmt->execute();
  if (!$success) {
  $page->content .= '<div class="col-sm-12 text-left"><h2>ERROR: ' . $db->error . '. Please try again </h2>
  </div></div>';
  }
  else {
    $page->content .= '<h2> Your have successfully reset the system. </h2></div></div>';
  }
}
else{
  $page->content .= '<div class="col-sm-12 text-left"><h2>Please submit Yes if you want to reset the system. </h2>
  </div></div>';
}
}
else {
  $page = new Page("Reset");
  $page->content = "<h1>You are not an administrator and are not allowed to use this page. </h1>";
}
// Show the page
#$_SESSION['date'] = date(DATE_RSS);
#update($db, $user, session_encode());
$page->display();
?>
