<?php
class Page
{
  // class Page's attributes
  public $content;
  private $title = 'IT360 Applied Database Systems Project Spring 2020 - Weekend Trackers';
  private $keywords = 'IT360 Applied Database Systems, Project, 2020, Weekend, Trackers, USNA';
  private $xmlheader = "<!DOCTYPE html><html lang = \"en\">";

  //constructor
  public function __construct($title) {
    $this->__set("title", $title);
  }

  //set private attributes
    public function __set($varName, $varValue) {
      $varValue = trim($varValue);
      $varValue = strip_tags($varValue);
      if (!get_magic_quotes_gpc()){
        $varValue = addslashes($varValue);
      }
      $this->$varName = $varValue;
    }

  //get function - nothing special for now
 public function __get($varName) {
   return $this->$varName;
 }

 //output the page
  public function display($hd=True,$ft=True,$nav=True)
  {
    echo $this->xmlheader;
    echo "<head>\n";
    $this -> displayTitle();
    $this -> displayKeywords();
    $this -> displayStyles();
    $this -> displaybootstrap();
    echo "</head>\n<body>\n";
    if($hd) $this -> displayContentHeader($nav);
    echo $this->content;
    if($ft) $this -> displayContentFooter();
    echo "</body>\n</html>\n";
  }

  //output the title
  public function displayTitle() {
    echo '<title> '.$this->title.' </title>';
  }

  public function displayKeywords() {
    echo "<meta name=\"keywords\" content=\"$this->keywords\" />";
  }

  //display the embedded stylesheet
  public function displayStyles() {
    ?>
      <style>
        h1.header {color:white; background-color: #5A0001; font-size:30pt;
                   text-align:center; font-family:arial,sans-serif}
        p         {font-size:12pt; text-align:justify;
                   font-family:arial,sans-serif}

        /* Remove the navbar's default margin-bottom and rounded borders */
        .navbar {
                  margin-bottom: 0;
                  border-radius: 0;
                }

        /* Set height of the grid so .sidenav can be 100% (adjust as needed) */
        .row.content {height: 450px}

        /* Set gray background color and 100% height */
        .sidenav {
             padding-top: 20px;
             background-color: #ACAD94 ;
             height: 100%;
             }

        /* Set black background color, white text and some padding */
        footer {
             background-color: #6E7271;
             color: white;
             padding: 15px;
             }

        /* On small screens, set height to 'auto' for sidenav and grid */
        @media screen and (max-width: 767px) {
               .sidenav {
               height: auto;
               padding: 15px;
               }
                 .row.content {height:auto;}
               }
      </style>
    <?php
  }

  //do bootstrap
  public function displaybootstrap() {
    ?>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <?php
  }

  //display the header part of the visible page
  public function displayContentHeader($nav=True) {
    ?>
      <h1 class="header"><br>~ Weekend Trackers ~<br><br></h1>
    <?php
    if($nav) {
    ?>
      <nav class="navbar navbar-inverse">
        <div class="container-fluid">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="home.php">Weekend Trackers</a>
          </div>
          <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav navbar-nav">
              <li class="active"><a href="home.php">Home</a></li>
              <li><a href="add_weekend.php">Add Weekend Plan</a></li>
              <li><a href="check_weekend.php">Check Weekend Status</a></li>
              <li><a href="check_incentives.php">Check incentives</a></li>
              <li><a href="add_incentives.php">Add incentives</a></li>
              <li><a href="update_profile.php">Update Profile</a></li>
              <li><a href="approve.php">Approve Weekends</a></li>
              <li><a href="add_users.php">Add Users</a></li>
              <li><a href="create_incentives.php">Create incentives</a></li>
            </ul>
            <form  action="logout.php" method="post" class="form-inline my-2 my-lg-0">
            <button class="btn btn-outline-success my-2 my-sm-0" name="logoff" type="logoff">Log Off</button>
          </form>
          </div>
        </div>
      </nav>
    <?php
    }
  }

  //display the footer part of the visible page
  public function displayContentFooter() {
    ?>
    <footer class="container-fluid text-center">
      <p>IT360 Applied Database Systems Project By Harrison Bleckley, Drake Bodine, and Lani Davis</p>
    </footer>
    <?php
  }
}
?>
