<?

require_once 'mod_dbase.php';

$dbase = db_open();
if (!$dbase) exit;

$id = $_POST['dtrid'];
if (!@$id) exit();
  
$time = $_POST['time'];
  
$sql = "UPDATE dtimes_run_manual "
	 . "  SET new_dtime_manual = '$time'"
	 . " WHERE id = $id"
	 . " LIMIT 1";
$res = mysqli_query($dbase,  $sql);

if (mysqli_affected_rows($dbase) == 1) {
	echo "UPDATE IS <font class='ok'>OK</font>";
}
else {
	echo "<font class='error'>ERROR</font> ON UPDATE<br><small>$sql</small>";
}

db_close($dbase);

?>
