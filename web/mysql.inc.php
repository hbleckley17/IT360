<?php
  /* mysql.inc.php
   * Bodine, Drake - 200540
   * 
   * This file will be used to establish the database connection.
   * 
  */
  class myConnectDB extends mysqli{
    public function __construct($hostname="localhost",
        $db_user="m200540",
        $db_password="lampshade404",
        $db_name="it360_weekend_tracker"){
      parent::__construct($hostname, $db_user, $db_password, $db_name);
    }
  }
?>
