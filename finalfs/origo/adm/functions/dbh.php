<?php
  function dbh()
  {
	  $dbh = pg_connect("host=localhost port=5432 dbname=origo user=postgres password=postgres");
	  if (!$dbh)
	  {
		  echo '{"save_status":"Error in connection"}';
		  die();
	  }
    else
    {
      return $dbh;
    }
  }
?>
