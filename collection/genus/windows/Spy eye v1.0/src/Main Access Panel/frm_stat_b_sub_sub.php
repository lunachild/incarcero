<?php

$gtid = $_GET['gtid'];
if (!@$gtid) exit;
$tid = $_GET['tid'];
if (!@$tid) exit;
$bid = $_GET['id_bot_task'];
if (!@$bid) exit;

?>

<h3>Report : Bot <b>#<?echo $bid;?></b> of Task <b>#<?echo $tid;?></b> for Global Task <b>#<?echo $gtid;?></b></h3>
  
<table id='trep' align='center' width='600px' border='1' cellspacing='0' cellpadding='0' style='border: 1px solid gray; font-size: 9px; border-collapse: collapse; background-color: white;'>
<th>Id Report</th>
<th>Data Report</th>
<th>Date Report</th>

<?php

require_once 'mod_dbase.php';

$dbase = db_open();
if (!$dbase) exit;

$sql = "SELECT id_rep, data_rep, date_rep"
	 . "  FROM bots_rep_t"
	 . " WHERE fk_bot_rep = $bid"
	 . "   AND fk_global_task = $gtid"
	 . " ORDER BY id_rep DESC";

$res = mysqli_query ($dbase, $sql);
if (@($res)) {
	while ($mres = mysqli_fetch_array($res))  {
		$CTIME = $mres['date_rep'];
	    echo "<tr align='center'>\n" . 
			 "<td>" . $mres['id_rep'] . "</td>\n" .
			 "<td width='80%'>" . htmlspecialchars($mres['data_rep'], ENT_QUOTES) . "</td>\n" .
			 "<td>" . $mres['date_rep'] . "</td>\n" .
			 "</tr>";
	}
	echo "</table>";
}
else {
	echo "<font class='error'>SQL-Error</font> : <small>$sql</small>";
}

db_close($dbase);
?>