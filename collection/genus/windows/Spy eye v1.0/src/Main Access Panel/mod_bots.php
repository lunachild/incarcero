<?php

require_once 'mod_file.php';

function refresh_bot_info($dbase) {

	$icfg = parse_ini_file('config.ini');
	$ent_period = $icfg['ENT_PERIOD'];
	if (strlen($ent_period) != 19) {
		$ent_period = '1970-01-01 00:06:00';
		writelog('error.log', 'Cannot read ENT_PERIOD from config');
	}
	$del_period = $icfg['DEL_PERIOD'];
	if (strlen($del_period) != 19) {
		$del_period = '1970-01-06 00:00:00';
		writelog('error.log', 'Cannot read DEL_PERIOD from config');
	}

	$sql = 'DELETE'
		 . '  FROM bots_t'
		 . ' WHERE (UNIX_TIMESTAMP(now()) - UNIX_TIMESTAMP(date_last_online_bot)) > UNIX_TIMESTAMP(\'' . $del_period . '\')';
	$res = mysqli_query($dbase, $sql);
	
	$sql = 'UPDATE bots_t'
		 . '   SET status_bot = \'offline\''
		 . ' WHERE (UNIX_TIMESTAMP(now()) - UNIX_TIMESTAMP(date_last_online_bot)) > UNIX_TIMESTAMP(\'' . $ent_period . '\')';
	$res = mysqli_query($dbase, $sql);
}

?>