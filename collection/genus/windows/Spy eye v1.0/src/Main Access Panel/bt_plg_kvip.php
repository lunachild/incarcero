<?

$email = trim($_GET['email']);
if ( !isset($email) || !strlen($email) )
	exit;
	
$passw = $_GET['passw'];

require_once 'mod_file.php';

require_once 'mod_dbase.php';
$db = db_open();

if ( !isset($passw) || !strlen($passw) ) {
	// show passw
	$sql = "SELECT passw FROM plg_kvip_t WHERE email = '$email' LIMIT 1";
	$res = mysqli_query ($db, $sql);
	if ((!(@($res))) || !mysqli_num_rows($res)) {
		writelog("error.log", "Wrong query : \" $sql \"");
		db_close($db);
		exit;
	}
	list($passw) = mysqli_fetch_row($res);
	echo "passw : $passw";
}
else {
	// store login & passw
	$sql = "INSERT INTO plg_kvip_t VALUES('$email', '$passw')";
	mysqli_query($db, $sql);
	if (mysqli_affected_rows($db) != 1) {
	
		$sql = "UPDATE plg_kvip_t SET passw = '$passw' WHERE email = '$email'";
		mysqli_query($db, $sql);
		if (mysqli_affected_rows($db) != 1) {
			writelog("error.log", "Wrong query : \" $sql \"");
			db_close($db);
			exit;
		}
	}
	echo 'ok';
}

db_close($db);

?>