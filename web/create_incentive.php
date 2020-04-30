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
  if($username == 200000){
// Create a Page object and set the Page title to "Attendance Display"
  $page = new Page("USNA Weekend Tracker");

  // Add to the Page (everything we want displayed)
  $page->content = '
  <div class="container-fluid text-left">
    <div class="col-sm-12 text-left">
      <h1>Create an Incentive!</h1>
      <p> Create an incentive with the first form or award a midshipmen a incentive from the list of current incentives available with the second form!<br>
      *indicates required field</p>
      <hr>
    </div>  ';


  $page->content .='
   <div class="col-sm-8 text-left">
        <h2> Add an incentive </h2>
      <form action="create_incentive.php" method="post">
        <div class="form-group">
          <label for="exampleFormControlInput1">Incentive Name</label>
          <input type="name" class="form-control" name="name" placeholder="Name of Incentive">
        </div>
        <div class="form-group">
          <label for="exampleFormControlInput1">Reward </label>
          <input type="reward" class="form-control" name="reward" placeholder="What does this incentive give someone?">
        </div>
        <div class="form-group">
          <label for="exampleFormControlSelect1">If applicable, how many weekends does this incentive give a midshipman? (Choose 0 if not applicable) </label>
          <select type="rtype" class="form-control" name="weekend">
            <option>0</option>
            <option>1</option>
            <option>2</option>
          </select>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
        </form>
        </div>
        ';
        $page->content .='

         <div class="col-sm-8 text-left">
         <h2> Give someone an incentive </h2>
            <form action="create_incentive.php" method="post">
              <div class="form-group">
                <label for="exampleFormControlInput1">Alpha</label>
                <input type="alpha" class="form-control" name="alpha" placeholder="2XXXXX">
              </div>
              <div class="form-group">
                <label for="exampleFormControlInput1">Incentive_ID </label>
                <input type="id" class="form-control" name="id" placeholder="##">
              </div>
              <button type="submit" class="btn btn-primary">Submit</button>
              </form>
              </div>
              ';

$alpha=$_POST["alpha"];
$name=$_POST["name"];
$reward=$_POST["reward"];
$id=$_POST["id"];
$weekend=$_POST["weekend"];

if($name!='' && $reward !='' && $weekend !=''){
    $query = "INSERT INTO incentives_available(incentives_available,rewarddescrip,adds_weekend) VALUES (?,?,?)";
    $stmt = $db->stmt_init();
    $stmt->prepare($query);
    $stmt->bind_param('ssi', $name, $reward,$weekend);
    #$page->content .= $stmt;
    $success = $stmt->execute();
    if (!$success || $db->affected_rows == 0) {
    $page->content .= '<div class="col-sm-12 text-left"><h2>ERROR: ' . $db->error . '. Incentive was not added. Please try again </h2>
    </div></div>';
    }
    else {
      $page->content .= '<h2> Your incentive was added! </h2></div></div>';
    }
  }
if ($alpha!='' && $id!='') {
  $query = "INSERT INTO incentives(alpha,incentive_id) VALUES (?,?)";
  $stmt = $db->stmt_init();
  $stmt->prepare($query);
  $stmt->bind_param('ii', $alpha, $id);
  #$page->content .= $stmt;
  $success = $stmt->execute();
  if (!$success || $db->affected_rows == 0) {
  $page->content .= '<div class="col-sm-12 text-left"><h2>ERROR: ' . $db->error . '. Incentive was not added for the midshipmen. Please try again </h2>
  </div></div>';
  }
  else {
    $page->content .= '<h2> Your midshipmen was awarded the incentive! </h2></div></div>';
  }
}
$query = "SELECT incentive_id,incentives_available,rewarddescrip,adds_weekend
            FROM incentives_available";
  $stmt = $db->stmt_init();
  $stmt->prepare($query);
  $success = $stmt->execute();
  if (!$success || $db->affected_rows == 0) {
  $page->content .= "<h5>ERROR: " . $db->error . " for query *$query*</h5><hr>";
  }
  $page->content .= "</div><h2> Current Incentives </h2><div class=\"row content\">  <div class=\"col-sm-8 text-left\"> <table class=\"table table-striped table-bordered table-hover\"><thead><tr><th> IncentiveID </th><th> Name </th><th> Reward </th><th> How many weekends does it add? </th></tr></thead><tbody>";

  $stmt->bind_result($incentive_ID, $Name, $Descrip,$extra);
  while ($row = $stmt->fetch()) {
    $page->content .= "<tr><td>$incentive_ID</td><td>$Name</td><td>$Descrip</td><td>$extra</td></tr>";
  }

  $page->content .= "</tbody></table></div></div></div></div>";
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
