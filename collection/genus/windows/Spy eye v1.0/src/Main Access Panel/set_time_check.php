<?

// mysql time

require_once 'mod_dbase.php';

$db = db_open();

$sql = "SELECT NOW() as time";
$res = mysqli_query($db, $sql);
if (@$res && mysqli_num_rows($res)) {
	$mres = mysqli_fetch_array($res);
	$mysqltime = $mres['time'];
}
else {
	$mysqltime = "<font class='error'>Cannot get MySQL time</font>";
}

db_close($db);

// php time

$phptime = gmdate('Y.m.d H:i:s');

// show it

require_once 'frm_skelet.php';
echo get_smskelet('Time Check', "<table cellspacing='0' cellpadding='5' style='border: 1px solid gray; background: white;'><tr><td>MySQL time</td><td>$mysqltime</td></tr><tr><td>PHP time</td><td>$phptime</td></tr></table>");

?>