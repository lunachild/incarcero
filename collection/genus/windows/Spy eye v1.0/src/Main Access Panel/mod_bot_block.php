<?php

$id = $_GET['id'];
if (!@$id )
  exit;

require_once 'mod_dbase.php';

$dbase = db_open();
if (!$dbase) exit;
  	
$sql = "SELECT blocked"
	 . "  FROM bots_t"
	 . " WHERE id_bot = $id"
	 . " LIMIT 1";
$res = mysqli_query ($dbase, $sql);
if (@$res && mysqli_num_rows($res)) {
	list($blocked) = mysqli_fetch_row($res);

	$blocked = !$blocked;

	$sql = "UPDATE bots_t"
		 . "   SET blocked = '$blocked'"
		 . " WHERE id_bot = $id"
		 . " LIMIT 1";
	
	$res = mysqli_query ($dbase, $sql);
	if (@$res && mysqli_affected_rows($dbase))
		echo $id;
	else
		echo "Error! 0x00";
}
else
	echo "Error! 0x01";

db_close($dbase);

?>