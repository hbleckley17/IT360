<?php
  /* login.php
   * Bodine, Drake - 200540
   * 
   * This page serves as the login page for whole website
   * 
  */

  /// Function Definitions ///

  // Get login form HTML (referenced bootsnipp.com/snippets/z8aQr) for design
  function getLoginFormHtml($homepage, $attempt=False) {
    $form = "<div class='container'>";
    $form .= "<div class='row'>";
    $form .= "<div class='col-md-6 jumbotron'>";
    $title = "Login";
    if($attempt) {
      $title .= " FAILED!";
    }
    $form .= "<h3>$title</h3>";
    $form .= "<form action='$homepage' method='post'>";
    $form .= "<div class='form-group'>";
    $form .= "<input name='username' type='text' placeholder='Alpha' value='' 
      class='form-control'>";
    $form .= "</div>";
    $form .= "<div class='form-group'>";
    $form .= "<input name='password' type='password' placeholder='Password' 
      value='' class='form-control'>";
    $form .= "</div>";
    $form .= "<div class='form-group'>";
    $form .= "<input type='submit' value='Log-in' class='btn btn-primary'>";
    $form .= "</div>";
    $form .= "</form>";
    $form .= "</div>";
    $form .= "</div>";
    $form .= "</div>";

    return $form;
  }

  /// Page setup ///

  $homePage = 'add_weekend.php';
  require_once("page.inc.php"); // get HTML page class

  // Create Page object and title accordingly
  $page = new Page("Log-In");

  // Populate page with login form to enter credentials
  $previousAttempt = False;
  if (isset($_POST['username'])) {
    $previousAttempt = True;
  }
  $page->content = getLoginFormHtml($homePage, $previousAttempt);

  // Display page
  $page->display(True,True,False);
?>
