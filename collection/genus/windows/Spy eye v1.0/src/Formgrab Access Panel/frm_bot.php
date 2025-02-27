<?

$guid = $_GET['guid'];
if ( strlen($guid) === false ) {
	$content .= '<b>ERROR</b> : Invalid bot GUID';
}

require_once 'mod_file.php';
require_once 'mod_time.php';
require_once 'mod_dbase.php';
require_once 'mod_strenc.php';

$dbase = db_open();

$sql = 'SELECT *'
	 . '  FROM rep1'
	 . " WHERE bot_guid = '$guid'"
	 . ' LIMIT 1';
	 
$res = mysqli_query($dbase, $sql);
if (!(@($res))) {
	writelog("error.log", "Wrong query : \" $sql \"");
	db_close($dbase);
	return 0;
}
list($id, $ip, $bot_guid, $bot_version, $local_time, $timezone, $tick_time, $os_version, $language_id, $date_rep) = mysqli_fetch_row($res);

$content .= "<table width='430' border='1' cellspacing='0' cellpadding='3' style='border: 1px solid lightgray; font-size: 9px; border-collapse: collapse; background-color: rgb(255, 255, 255);'>";

$content .= "<tr><td width='100px'><b>id</b></td><td>$id</td></tr>";
$content .= "<tr><td width='100px'><b>ip</b></td><td>$ip</td></tr>";
$content .= "<tr><td width='100px'><b>bot_guid</b></td><td>$bot_guid</td></tr>";
$content .= "<tr><td width='100px'><b>bot_version</b></td><td>$bot_version</td></tr>";
$content .= "<tr><td width='100px'><b>local_time</b></td><td>$local_time</td></tr>";

$timezone = ucs2html($timezone);
$content .= "<tr><td width='100px'><b>timezone</b></td><td>$timezone</td></tr>";

list($year, $month, $day, $hour, $minute, $second) = split('[ :\/.-]', $tick_time);
$day = intval($day);
$hour = intval($hour);
$min = intval($minute);
$sec = intval($second);
$hour = ($day - 1) * 24 + $hour;

$content .= "<tr><td width='100px'><b>tick_time</b></td><td>$hour:$min:$sec</td></tr>";

$content .= "<tr><td width='100px'><b>os_version</b></td><td>$os_version</td></tr>";
$content .= "<tr><td width='100px'><b>language_id</b></td><td>$language_id</td></tr>";
$content .= "<tr><td width='100px'><b>date_rep</b></td><td>$date_rep</td></tr>";

$content .= "</table>";
	
db_close($dbase);
	
?>

<?php
require_once 'frm_skelet.php';
echo get_smskelet('Detail info for selected bot', $content);
?>