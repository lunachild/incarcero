<?php
Error_Reporting (E_ALL &~E_NOTICE);

include "config.php";

// SETTING TIMEZONE FOR MYSQL

$dbase = mysqli_connect (DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);
if ($dbase) {
  $res = mysqli_query($dbase, "SET GLOBAL time_zone = '+00:00'");
}
mysqli_close($dbase);

?>