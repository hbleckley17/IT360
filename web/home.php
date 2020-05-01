<?php
  //Lani Davis m201368
  // Load the Page Class (inside of page.inc.php)
  #require_once("auth.inc.php");
  require_once("page.inc.php");

  // Create a Page object and set the Page title to "Attendance Display"
  $page = new Page("USNA Weekend Tracker");

  // Add to the Page (everything we want displayed)
  $page->content = '<div class="container-fluid text-center">
    <div class="row content">
      <div class="col-sm-2 sidenav">
        <p><a href="#">Fill out your weekend plan accurately and honestly.</a></p>
        <p><a href="#">No weekend of fun is worth an honor offense.</a></p>
        <p><a href="#">Enjoy your weekend and return with integrity.</a></p>
      </div>
      <div class="col-sm-8 text-left">
        <h1>Welcome Weekend Warriors!</h1>
        <p>This is site is for you to easily enter your weekend plans and to check if they are approved by your CoC before you go out! Always make sure to have a plan before going out! Have fun and enjoy your weekend!<p>
        <hr>
        <h3>How to use this site:</h3>
        <p> Add weekend - Create a new weekend plan or add yourself to someone elses weekend plan<br>
            Check Weekend- Check your weekend approval Status <br>
            Check Incentives- Check if you have any incentives to use on your weekend!<br>
            Add Incentives- Use a incentive with your weekend <br>
            Update Profile - Got a new phone number or change companies? Change your information here! <br>
            <br>
            For Admin only:<br>
            Approve Weekend- Company Officers can approve weekends here <br>
            Add Users- Administrators can add new users to their company <br>
            Create Incentives- Administrators can create a new incentive or award an incentive to midshipmen <br>
            Reset- Administrators can reset the weekend plans and approvals after every weekend <br>
        </p>

      </div>
      <div class="col-sm-2 sidenav">
        <div class="well">
          <p>Be a Guardian Angel to other MIDS if you can.</p>
        </div>
        <div class="well">
          <p>Call Shipmate at (410)320-5961 if you need help.</p>
        </div>
      </div>
    </div>
  </div>';

  // Show the page

  #update($db, $user, session_encode());
  $page->display();
?>
