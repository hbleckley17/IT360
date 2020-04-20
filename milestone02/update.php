<?php
  //Lani Davis m201368
  // Load the Page Class (insnamee of page.inc.php)
  #require_once("auth.inc.php");
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
      <h1>Update your information!</h1>
      <p> Update an of the information you need to int this form!<br>
      *indicates required field</p>
      <hr>
    </div>
    <div class="col-sm-8 text-left">
      <form action="update.php" method="post">
        <div class="form-group">
          <label for="exampleFormControlInput1">Alpha*</label>
          <input type="alpha" class="form-control" name="alpha" placeholder="2XXXXX">
        </div>
        <div class="form-group">
          <label for="exampleFormControlInput1">Company </label>
          <input type="company" class="form-control" name="company" placeholder="##">
        </div>
        <div class="form-group">
          <label for="exampleFormControlInput1"> Cell Phone Number </label>
          <input type="cell" class="form-control" name="cell" placeholder="##########">
        </div>
        <div class="form-group">
          <label for="exampleFormControlInput1"> Sponsors </label>
          <input type="sponsor" class="form-control" name="sponsor" placeholder="#### Madeup Dr. Annapolis, MD 21412">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
        </form>
        </div>
        ';

$alpha=$_POST["alpha"];
$company=$_POST["company"];
$cell=$_POST["cell"];
$sponsor=$_POST["sponsor"];
if($alpha==''||($company==''&&$cell==''&&$sponsor=='')){
  $page->content .= '<div class="col-sm-12 text-left"><h2>Please submit your alpha and the information you would like to update. </h2>
  </div></div>';
}
if($alpha!=''){
  if($company!=''){
    $query = "UPDATE Company
              SET Company=?
              WHERE alpha=?";
    $stmt = $db->stmt_init();
    $stmt->prepare($query);
    $stmt->bind_param('ii', $company,$alpha);
    #$page->content .= $stmt;
    $success = $stmt->execute();
    if (!$success || $db->affected_rows == 0) {
    $page->content .= '<div class="col-sm-12 text-left"><h2>ERROR: ' . $db->error . '. Please try again </h2>
    </div></div>';
    }
    else {
      $page->content .= '<h2> Your company was successfully updated! </h2></div></div>';
    }
  }
  if($cell!=''){
    $query = "UPDATE Cell
              SET phone_number=?
              WHERE alpha=?";
    $stmt = $db->stmt_init();
    $stmt->prepare($query);
    $stmt->bind_param('ii', $cell,$alpha);
    #$page->content .= $stmt;
    $success = $stmt->execute();
    if (!$success || $db->affected_rows == 0) {
    $page->content .= '<div class="col-sm-12 text-left"><h2>ERROR: ' . $db->error . '. Please try again </h2>
    </div></div>';
    }
    else {
      $page->content .= '<h2> Your cell phone number was successfully updated! </h2></div></div>';
    }
  }
  if($sponsor!=''){
    $query = "UPDATE sponsors
              SET sponsoraddress=?
              WHERE alpha=?";
    $stmt = $db->stmt_init();
    $stmt->prepare($query);
    $stmt->bind_param('si', $sponsor,$alpha);
    #$page->content .= $stmt;
    $success = $stmt->execute();
    if (!$success || $db->affected_rows == 0) {
    $page->content .= '<div class="col-sm-12 text-left"><h2>ERROR: ' . $db->error . '. Please try again </h2>
    </div></div>';
    }
    else {
      $page->content .= '<h2> Your sponsor address was updated! </h2></div></div>';
    }
  }
}
else{
  $page->content .= '</div>
    </div>';
}

// Show the page
#$_SESSION['date'] = date(DATE_RSS);
#update($db, $user, session_encode());
$page->display();
?>
