<script type="text/javascript">
    var GB_ROOT_DIR = "js/greybox/";
</script>

<script type="text/javascript" src="js/greybox/AJS.js"></script>
<script type="text/javascript" src="js/greybox/AJS_fx.js"></script>
<script type="text/javascript" src="js/greybox/gb_scripts.js"></script>
<link href="js/greybox/gb_styles.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="js/scroll.js"></script>

<script type="text/javascript">
function ajaxScrollToIt(fromel) {
	scrollIt(fromel, 'ajax_stat_b_sub');
}
function ajaxTaskDelete(res) {
	if (res.indexOf('Error') == -1 && res.length != 0) {
		var el = document.getElementById('t' + res);
		if (el) {
			el.innerHTML = '';
			return true;
		}
	}
	alert('Cannot delete this task\n\n' + res);
}
function taskDelete(id) {
	ajax_load('mod_gtask_del.php?id=' + id, ':restofunc:', ajaxTaskDelete);
}

function ajaxTaskPause(res) {
	if (res.indexOf('Error') == -1 && res.length != 0) {
		var el = document.getElementById('psb' + res);
		if (el) {
			if (el.innerHTML.indexOf('pause') != -1)
				el.innerHTML = "<img border='0' src='img/icos/play.png'>";
			else
				el.innerHTML = "<img border='0' src='img/icos/pause.png'>";
			return true;
		}
	}
	alert('Cannot pause this task\n\n' + res);
}
function taskPause(id) {
	ajax_load('mod_gtask_pause.php?id=' + id, ':restofunc:', ajaxTaskPause);
}
</script>

<?php
$bill = $_GET['b'];
if (!@$bill) exit;

?>

<h3>Global tasks for billing <b> <?php echo $bill; ?></b></h3>

<table align='center' width='740px' border='1' cellspacing='0' cellpadding='0' style='border: 1px solid gray; font-size: 9px; border-collapse: collapse; background-color: white;'>
	<th>ID</th>
	<th>Note</th>
	<th>Start Time</th>
	<th>Finish Time</th>
	<th>Bots Count</th>
	<th>Tasks Processing (%)</th>
	<th>[Detail info]</th>
	<th>[Controls]</th>

<?php

require_once 'mod_dbase.php';
require_once 'mod_time.php';

$dbase = db_open();
if (!$dbase) exit;

$sql = "SELECT *"
	 . "  FROM global_tasks_t"
	 . " ORDER BY id_global_task";
$res = mysqli_query($dbase, $sql);
if (@($res)) {
	$i = 0;
	while ($mres = mysqli_fetch_array($res)) {
		$sql = "SELECT text_url_urls"
			 . "  FROM urls_t, global_tasks_t"
			 . " WHERE id_url = " . $mres['fk_url']
			 . "   AND global_tasks_t.fk_url = urls_t.id_url"
			 . " LIMIT 1";
		$res2 = mysqli_query ($dbase, $sql);
		$mres_r = mysqli_fetch_array($res2);		  
		$url = $mres_r['text_url_urls'];
		if (strpos($url, 'http') === false)
			$url = 'http://' . $url;
			
		$sql = "SELECT text_url_urls"
			 . "  FROM urls_t, global_tasks_t"
			 . " WHERE id_url = " . $mres['fk_ref_url']
			 . "   AND global_tasks_t.fk_ref_url = urls_t.id_url"
			 . " LIMIT 1";
		$res2 = mysqli_query ($dbase, $sql);
		$mres_r = mysqli_fetch_array($res2);		  
		$refurl = $mres_r['text_url_urls'];
		if (strpos($refurl, 'http') === false)
			$refurl = 'http://' . $refurl;
		
		if ((strpos($url, $bill) === false) && ($bill != 'all')) continue;
		
		$id = $mres['id_global_task'];
		$sql =   'SELECT COUNT(id_dtime_run)'
			   . '  FROM dtimes_run_t'
			   . " WHERE fk_global_task_dtimes_run = $id";
		$res2 = mysqli_query($dbase, $sql);
		list($all_task) = mysqli_fetch_array($res2);
		
		$sql =   'SELECT COUNT(status_task)'
			   . '  FROM global_tasks_t, dtimes_run_t, tasks_t'
			   . " WHERE id_task = fk_task_dtimes_run"
			   . "   AND id_global_task = fk_global_task_dtimes_run"
			   . "   AND fk_global_task_dtimes_run = $id";
		$res2 = mysqli_query($dbase, $sql);
		list($comp_task) = mysqli_fetch_array($res2);
	   
		$sql =   'SELECT COUNT(status_task)'
			   . '  FROM global_tasks_t, dtimes_run_t, tasks_t'
			   . " WHERE id_task = fk_task_dtimes_run"
			   . "   AND id_global_task = fk_global_task_dtimes_run"
			   . "   AND fk_global_task_dtimes_run = $id"
			   . "   AND status_task = 1";
		$res2 = mysqli_query($dbase, $sql);
		list($ok_task) = mysqli_fetch_array($res2);
		
		$sql =   'SELECT COUNT(status_task)'
			   . '  FROM global_tasks_t, dtimes_run_t, tasks_t'
			   . " WHERE id_task = fk_task_dtimes_run"
			   . "   AND id_global_task = fk_global_task_dtimes_run"
			   . "   AND fk_global_task_dtimes_run = $id"
			   . "   AND status_task = -1";
		$res2 = mysqli_query($dbase, $sql);
		list($err_task) = mysqli_fetch_array($res2);
		
		$sql =   'SELECT COUNT(status_task)'
			   . '  FROM global_tasks_t, dtimes_run_t, tasks_t'
			   . " WHERE id_task = fk_task_dtimes_run"
			   . "   AND id_global_task = fk_global_task_dtimes_run"
			   . "   AND fk_global_task_dtimes_run = $id"
			   . "   AND status_task = 0";
		$res2 = mysqli_query($dbase, $sql);
		list($sloppy_task) = mysqli_fetch_array($res2);

		echo "<tr align='center' id='t$id'";
		$starttime = $mres['start_dtime_global_task'];
		$fintime = $mres['finish_dtime_global_task'];
		$curtime = gmdate("r");
		if (GetTimeStamp($fintime) < strtotime($curtime))
			echo " style=\"background-color: #BDBDBD;\"";
		else 
			if ( (GetTimeStamp($starttime) <= strtotime($curtime)) && (GetTimeStamp($fintime) >= strtotime($curtime)) )
				echo " style=\"background-color: #FFBDBD;\"";
		echo ">";
		$note = $mres['note'];
		if ($bill == 'all') {
			if (strpos($url, 'setsystems') !== false)
				$cbill = 'setsystems';
			else if (strpos($url, 'esellerate') !== false)
				$cbill = 'esellerate';
			else if (strpos($url, 'fastspring') !== false)
				$cbill = 'fastspring';
			else if (strpos($url, 'clickbank') !== false)
				$cbill = 'clickbank';
			else if (strpos($url, 'shareit') !== false)
				$cbill = 'shareit';
			else if (strpos($url, 'alertpay') !== false)
				$cbill = 'alertpay';
			else if (strpos($url, 'securebillingsoftware') !== false)
				$cbill = 'securebillingsoftware';
			else if (strpos($url, 'kinovip') !== false)
				$cbill = 'kinovip';
			$ico = "<img title=\"'$refurl' -> '$url'\" src=\"img/icos/b-" . $cbill . "_24.png\">";
		}
		echo "<td>$id</td>".
			 "<td><table><tr><tr valign='middle'>$ico</td><td><span title=\"'$refurl' -> '$url'\">$note</span></td></tr></table></td>".
			 "<td>$starttime</td>".
		     "<td>$fintime</td>".
		     "<td>" . $mres['count_bots_global_task'] . "</td>";
		echo "<td><center>";

		if (!$all_task) $all_task = 0.00001;
		$perc = round( ( ($comp_task / $all_task) * 100 ) );
		echo "<img alt=\"$perc% ($comp_task / $all_task)\" title=\"Taked tasks: $perc% ($comp_task / $all_task)\" width=\"210\" src=\"php/progress-bar/progress.php?img=winxpblue/210/$perc/100\"/>";
		echo "<br>";
		$perc = round( ( ($ok_task / $all_task) * 100 ) );
		echo "<img alt=\"$perc% ($ok_task / $all_task)\" title=\"Complered tasks: $perc% ($ok_task / $all_task)\" width=\"70\" src=\"php/progress-bar/progress.php?img=winxp/70/$perc/100\"/>";
		$perc = round( ( ($sloppy_task / $all_task) * 100 ) );
		echo "<img alt=\"$perc% ($sloppy_task / $all_task)\" title=\"Sloppy tasks: $perc% ($sloppy_task / $all_task)\" width=\"70\" src=\"php/progress-bar/progress.php?img=winxpyellow/70/$perc/100\"/>";
		$perc = round( ( ($err_task / $all_task) * 100 ) );
		echo "<img alt=\"$perc% ($err_task / $all_task)\" title=\"Error tasks: $perc% ($err_task / $all_task)\" width=\"70\" src=\"php/progress-bar/progress.php?img=winxpred/70/$perc/100\"/>";
		
        echo "</center></td>";
		echo "<td>";
		echo "<a href='#null' id='stat_sub_link$i' onclick='ajax_load(\"frm_stat_b_sub.php?gtid=$id\", \"ajax_stat_b_sub\", ajaxScrollToIt, \"stat_sub_link$i\"); return false;'><img border='0' src='img/icos/info.png' title=\"Get detail info about global task ($id)\"></a>";
		echo "<a href='#null' title='Show cards' onclick=\"GB_show('Cards for this global task', '../../frm_cards_gtaskshow.php?gtask=$mres[0]', 500, 700); return false;\"><img border='0' src='img/icos/cards.png'></a>";
		echo "</td>";
			
		$pause = $mres['paused'];
		if ($pause == 0) {
		  $play_pause_pic = 'img/icos/pause.png';
		  $npause = 1; }
		else {
		  $play_pause_pic = 'img/icos/play.png';
		  $npause = 0; }
			
		echo "<td>"
		. "<a id='psb$id' href=\"#null\" onclick=\"taskPause('$id'); return false;\"><img border='0' src='$play_pause_pic'></a>"
		. "<a href=\"#null\" onclick=\"if (!confirm('Do you really want to delete global task?')) return false; taskDelete('$id'); return false;\"><img border='0' src='img/icos/delete_24.png'></a>"
			
		. "</td>";
			
		echo "</tr>";
		
		$i++;
	}
	echo "</table>";
}
else {
	echo "<font class='error'>SQL-Error</font> : <small>$sql</small>";
}

db_close($dbase);

?>

<hr size='1' color='#CCC'>

<div id='ajax_stat_b_sub'>
</div>