<?

$id = $_GET['id'];
if (!@$id) {
	echo "Input params error";
	exit();
}

require_once 'mod_dbase.php';

$dbase = db_open();
if (!$dbase) exit;

$sql = "DELETE"
	 . "	FROM global_tasks_t "
	 . " WHERE global_tasks_t.id_global_task = $id"
	 . " LIMIT 1";
mysqli_query($dbase,	$sql);
if (!mysqli_affected_rows($dbase)) {
	echo "Error in query: \"$sql\"";
}
else {
	echo $id;
}
	
db_close($dbase);

?>