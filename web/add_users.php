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
    if($username == 200000){
  // Create a Page object and set the Page title to "Attendance Display"
  $page = new Page("USNA Weekend Tracker");

  // Add to the Page (everything we want displayed)
  $page->content = '
  <div class="container-fluid text-left">
    <div class="col-sm-12 text-left">
      <h1>Add User</h1>
      <p> Add a user. You will need an alpha, first name, last name, company, phone number, password, sponsors address, and the number of weekends left.<br>
      The company, phone number, password, and sponsors addres will be able to adjusted later by the midshipmen once they are added to the system. <br>
      All fields are required </p>
      <hr>
    </div>
    <div class="col-sm-8 text-left">
      <form action="add_users.php" method="post">
        <div class="form-group">
          <label for="exampleFormControlInput1">Alpha</label>
          <input type="alpha" class="form-control" name="alpha" placeholder="2XXXXX">
        </div>
        <div class="form-group">
          <label for="exampleFormControlInput1">First Name </label>
          <input type="first" class="form-control" name="first" placeholder="Joe">
        </div>
        <div class="form-group">
          <label for="exampleFormControlInput1">Last Name </label>
          <input type="last" class="form-control" name="last" placeholder="Door">
        </div>
        <div class="form-group">
          <label for="exampleFormControlInput1">Password </label>
          <input type="password" class="form-control" name="password" placeholder="password">
        </div>
        <div class="form-group">
          <label for="exampleFormControlInput1"> Company </label>
          <input type="company" class="form-control" name="company" placeholder="##">
        </div>
        <div class="form-group">
          <label for="exampleFormControlInput1"> Cell Number </label>
          <input type="cell" class="form-control" name="cell" placeholder="##########">
        </div>
        <div class="form-group">
          <label for="exampleFormControlInput1"> Weekend Number </label>
          <input type="weekend" class="form-control" name="weekend" placeholder="##">
        </div>
        <div class="form-group">
          <label for="exampleFormControlInput1"> Sponsors Address </label>
          <input type="sponsor" class="form-control" name="sponsor" placeholder="#### Maybe Dr. Annapolis, MD 21412">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
        </form>
        </div>
        ';

$alpha=$_POST["alpha"];
$first=$_POST["first"];
$last=$_POST["last"];
$password=$_POST["password"];
$company=$_POST["company"];
$cell=$_POST["cell"];
$sponsor=$_POST["sponsor"];
$weekend=$_POST["weekend"];

if($first!='' && $alpha!='' && $last!='' && $password!=''  && $company!=''  && $cell!='' && $sponsor!=''  && $weekend!=''){
  $hash=password_hash($password, PASSWORD_DEFAULT);
  $query = "INSERT INTO midshipmen (alpha,firstname,lastname,password)
            VALUES (?,?,?,?)";
  $stmt = $db->stmt_init();
  $stmt->prepare($query);
  $stmt->bind_param('isss', $alpha, $first, $last, $hash);
  #$page->content .= $stmt;
  $success = $stmt->execute();
  if (!$success || $db->affected_rows == 0) {
  $page->content .= '<div class="col-sm-12 text-left"><h2>ERROR: ' . $db->error . '. Midshipmen could not be added. Please try again </h2>
  </div></div>';
  }
  else {
    $query = "INSERT INTO weekends_left(alpha,weekends_left) VALUES (?,?)";
    $stmt = $db->stmt_init();
    $stmt->prepare($query);
    $stmt->bind_param('ii', $alpha, $weekend);
    $success = $stmt->execute();
    if (!$success || $db->affected_rows == 0) {
    $page->content .= '<div class="col-sm-12 text-left"><h2>ERROR: ' . $db->error . '. Midshipmen weekend count could not be added. Please try again </h2>
    </div></div>';
    }
    else{
      $query = "INSERT INTO Company(alpha,Company) VALUES (?,?)";
      $stmt = $db->stmt_init();
      $stmt->prepare($query);
      $stmt->bind_param('ii', $alpha, $company);
      $success = $stmt->execute();
      if (!$success || $db->affected_rows == 0) {
      $page->content .= '<div class="col-sm-12 text-left"><h2>ERROR: ' . $db->error . '. Midshipmen company could not be added. Please try again </h2>
      </div></div>';
      }
      else{
        $query = "INSERT INTO sponsors(alpha,sponsoraddress) VALUES (?,?)";
        $stmt = $db->stmt_init();
        $stmt->prepare($query);
        $stmt->bind_param('is', $alpha, $sponsor);
        $success = $stmt->execute();
        if (!$success || $db->affected_rows == 0) {
        $page->content .= '<div class="col-sm-12 text-left"><h2>ERROR: ' . $db->error . '. Midshipmen sponsor address could not be added. Please try again </h2>
        </div></div>';
        }
        else{
          $query = "INSERT INTO Cell(alpha,phone_number) VALUES (?,?)";
          $stmt = $db->stmt_init();
          $stmt->prepare($query);
          $stmt->bind_param('ii', $alpha, $cell);
          $success = $stmt->execute();
          if (!$success || $db->affected_rows == 0) {
          $page->content .= '<div class="col-sm-12 text-left"><h2>ERROR: ' . $db->error . '. Midshipmen cell could not be added. Please try again </h2>
          </div></div>';
          }
          else{
              $page->content .= '<h2> Your midshipmen was successfully added. </h2></div></div>';
          }
        }
      }
    }
  }
}
else{
  $page->content .= '<div class="col-sm-12 text-left"><h2>Please fill out all the fields in the form above to add a user. </h2>
  </div></div>';
}
}
else{
    $page = new Page("USNA Weekend Tracker");
    $page->content = "<h1>You are not an administrator and are not allowed to use this page. </h1>";
}

// Show the page
#$_SESSION['date'] = date(DATE_RSS);
#update($db, $user, session_encode());
$page->display();
?>
