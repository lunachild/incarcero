<?
$id = $_GET['id'];
if (!@$id) {
	echo "Input params error";
	exit();
}

require_once 'mod_dbase.php';

$dbase = db_open();
if (!$dbase) exit;

$sql = "SELECT global_tasks_t.paused"
	 . "  FROM global_tasks_t"
	.  " WHERE global_tasks_t.id_global_task = $id"
	.  " LIMIT 1";
$res = mysqli_query($dbase, $sql);
list($pause) = mysqli_fetch_array($res);

($pause) ? $pause = 0 : $pause = 1;
	
$sql = "UPDATE global_tasks_t "
	.  "   SET global_tasks_t.paused = $pause "
	.  " WHERE global_tasks_t.id_global_task = $id"
	.  " LIMIT 1";
mysqli_query($dbase, $sql);
if (!mysqli_affected_rows($dbase)) {
	echo "Error in query: \"$sql\"";
}
else {
	echo $id;
}

db_close($dbase);

?>