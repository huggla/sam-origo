<?php
  function dbh()
  {
	  $dbh = pg_connect("host=config-db port=5432 dbname=origo-config user=postgres password=postgres");
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
