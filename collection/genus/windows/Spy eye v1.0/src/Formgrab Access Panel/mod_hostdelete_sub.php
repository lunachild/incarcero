<?

$host = $_POST['host'];
if ( (!isset($host)) || !strlen($host) ) {
	exit;
}

$dt = $_GET['dt'];
if ( (!isset($dt)) || !strlen($dt) ) {
	exit;
}

require_once 'mod_dbase.php';

$dbase = db_open($dbase);

$sql = "DELETE FROM rep2_$dt"
	 . " WHERE LEFT( func_data, LOCATE( '\n', func_data ) ) LIKE '%$host%'";
$res = mysqli_query($dbase, $sql);
if (!@$res)
	$content .= "<font class='error'>ERROR SQL</font> : ' $sql '";
else {
	$cnt = mysqli_affected_rows($dbase);
	$content .= "<font class='ok'>OK</font> : $cnt items of '$host' was successfully deleted";
}

db_close($dbase);

//require_once 'frm_skelet.php';
//echo get_smskelet('Host delete', $content);

echo $content;

?>