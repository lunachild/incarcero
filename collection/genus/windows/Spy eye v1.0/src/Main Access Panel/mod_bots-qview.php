<?php

require_once 'mod_dbase.php';
require_once 'mod_bots.php';

$dbase = db_open();
if (!$dbase) exit;

refresh_bot_info($dbase);

$sql = 'SELECT COUNT(id_bot)'
	 . '  FROM bots_t'
	 . ' WHERE status_bot <> \'offline\'';
$res = mysqli_query($dbase, $sql);
$cnt = -1;
if ((@($res)) && mysqli_num_rows($res) > 0) {
	list($cnt) = mysqli_fetch_array($res);
}

$sql = 'SELECT COUNT(id_bot)'
	 . '  FROM bots_t';
$res = mysqli_query($dbase, $sql);
$cnt2 = -1;
if ((@($res)) && mysqli_num_rows($res) > 0) {
	list($cnt2) = mysqli_fetch_array($res);
}

echo $cnt . '/' . $cnt2;

db_close($dbase);

?>