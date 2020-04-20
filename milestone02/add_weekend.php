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
      <h1>Add a Weekend Plan</h1>
      <p> What are you doing this weekend?<br>
      * indicates a required field<br>
      You must provide an address and description or a wid to make a weekend plan.</p>
      <hr>
    </div>
    <div class="col-sm-8 text-left">
      <form action="add_weekend.php" method="post">
        <div class="form-group">
          <label for="exampleFormControlInput1">Alpha*</label>
          <input type="alpha" class="form-control" name="alpha" placeholder="2XXXXX">
        </div>
        <div class="form-group">
          <label for="exampleFormControlInput1">WID (if you are hanging out with other mids copy their weekend plan and avoid having to type the address and description below by using their WID otherwise leave blank) </label>
          <input type="wid" class="form-control" name="wid" placeholder="###">
        </div>
        <div class="form-group">
          <label for="exampleFormControlInput1"> Address (Bancroft, Sponsors, or write the real address leave blank if you have a WID) </label>
          <input type="address" class="form-control" name="address" placeholder="#### Madeup Dr. Annapolis, MD 21412">
        </div>
        <div class="form-group">
          <label for="exampleFormControlInput1"> Description (minimum 2 sentences leave blank if you have a WID) </label>
          <input type="description" class="form-control" name="description" placeholder="I will be....">
        </div>
        <div class="form-group">
          <label for="exampleFormControlInput1"> Buddy Name* </label>
          <input type="name" class="form-control" name="name" placeholder="Joe Bob">
        </div>
        <div class="form-group">
          <label for="exampleFormControlInput1"> Buddy Phone* </label>
          <input type="phone" class="form-control" name="phone" placeholder="##########">
        </div>
      <button type="submit" class="btn btn-primary">Submit</button>
      </form>
      </div>
      ';
  $buddyname=$_POST["name"];
  $alpha=$_POST["alpha"];
  $wid=$_POST["wid"];
  $addr=$_POST["address"];
  $des=$_POST["description"];
  $buddyphone=$_POST["phone"];
  if($buddyname==''||$alpha==''||$buddyphone==''){
    $page->content .= '<div class="col-sm-12 text-left"><h2>Please fill out the required fields in the form to submit a weekend plan. </h2>
    </div></div>';
  }
  if($wid!='' && ($addr!='' || $des!='')){
    $page->content .= '<div class="col-sm-12 text-left"><h2>ERROR: You can not use a Weekend ID and have an address or description. This is $wid: '.$wid.' Please try again </h2>
    </div></div>';
  }
  else{
  if(($addr != '' && $des == '') || ($addr == '' && $des != '')) {
    $page->content .= '<div class="col-sm-12 text-left"><h2>ERROR: You need both an address and a description in a weekend plan. Please try again </h2>
    </div></div>';
  }
  else{
  if($addr!='' && $des!=''){
    $query = "INSERT INTO weekendplans(address,description)
              VALUES(?,?)";
    $stmt = $db->stmt_init();
    $stmt->prepare($query);
    $stmt->bind_param('ss', $addr, $des);
    #$page->content .= $stmt;
    $success = $stmt->execute();
    if (!$success || $db->affected_rows == 0) {
    $page->content .= '<div class="col-sm-12 text-left"><h2>' . $db->error . '</h2>
    </div></div>';
    }
    $query = "SELECT wID
              FROM weekendplans
              WHERE address = ?
              AND description = ?";
    $stmt = $db->stmt_init();
    $stmt->prepare($query);
    $stmt->bind_param('ss', $addr, $des);
    $success = $stmt->execute();
    if (!$success || $db->affected_rows == 0) {
      $page->content .= "<h5>ERROR: " . $db->error . " for query *$query*</h5><hr>";
    }
    $stmt->bind_result($wid);
    $stmt->fetch();
    $page->content .= '<h2> Your WID is '.$wid.'</h2></div></div>';

  }
  if($buddyname!='' && $alpha!='' && $buddyphone!=''){
    #$page->content .= "<h5>ERROR: " . $wid . " is the wid2 from the query</h5><hr>";
    $query = "INSERT INTO  midweekend(wID,alpha,buddyname,buddyphone)
              VALUES(?,?,?,?)";
    $stmt = $db->stmt_init();
    $stmt->prepare($query);
    $stmt->bind_param('iisi', $wid, $alpha, $buddyname, $buddyphone);
    #$page->content .= $stmt;
    $success = $stmt->execute();
    if (!$success || $db->affected_rows == 0) {
    $page->content .= '<div class="col-sm-12 text-left"><h2>ERROR: ' . $db->error . '. Please try again </h2>
    </div></div>';
    }
    else {
      $page->content .= '<h2> Your weekend was successfully added. Thank you and have fun! </h2></div></div>';
    }
  }
  }
}
  #$stmt->close();


  // Show the page
  #$_SESSION['date'] = date(DATE_RSS);
  #update($db, $user, session_encode());
  $page->display();
?>
