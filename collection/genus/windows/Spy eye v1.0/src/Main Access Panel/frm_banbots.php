<?php
	
	require_once 'mod_dbase.php';
	require_once 'mod_bots.php';

	$dbase = db_open();
	if (!$dbase) exit;
	
	if ((@$_POST) && $_POST['isBanbots']) {
		$sql = "DELETE FROM ip_ban_t";
		mysqli_query($dbase, $sql);
		if (!mysqli_affected_rows($dbase)) {
			writelog("error.log", "Wrong query : $sql");
			db_close($dbase);
			return 0;
		}
		$match = preg_split("/\n/", $_POST['bans']);
		if (count($match)) {
			for ($i = 0; $i < count($match); $i++) {
				$data = $match[$i];
				if (!strlen($data))
					continue;
			
				$smatch = preg_split("/;/", $data);
				$ip_ban = $smatch[0];
				$note = $smatch[1];
				
				$sql = "INSERT INTO ip_ban_t (ip_ban, note) VALUES ('$ip_ban', '$note')";
				mysqli_query($dbase, $sql);
				if (!mysqli_affected_rows($dbase)) {
					writelog("error.log", "Wrong query : $sql");
					db_close($dbase);
					return 0;
				}
			}
		}
	}
	
	$sql = "SELECT * FROM ip_ban_t";
	$res = mysqli_query($dbase, $sql);
	if (!(@($res))) {
		writelog("error.log", "Wrong query : $sql");
		db_close($dbase);
		return 0;
	}
	while ($mres = mysqli_fetch_array($res)) {
		$ip_ban = $mres['ip_ban'];
		$note = $mres['note'];
		$content .= "$ip_ban;$note\n";
	}
	
	echo "<form method='post' id='frm_banbots'>\n";
	echo "<input type=\"hidden\" name=\"isBanbots\" value=\"1\">";
	echo "<textarea id='bans' name='bans' style='border-width: 1px; width: 200px; height: 400px;'>$content</textarea><br>\n";
	echo "<input type='submit' value='Save' onclick=\"var pdata = ajax_getInputs('frm_banbots'); ajax_pload('frm_banbots.php', pdata, 'div_ajax'); return false;\">";
	echo "</form>\n";
	
?>
