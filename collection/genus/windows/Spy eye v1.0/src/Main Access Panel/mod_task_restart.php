<?php

$DTimeRun = $_GET['id_dtr'];
if (!@$DTimeRun) exit;
$GlobalTaskForRestart = $_GET['id_gt'];
if (!@$GlobalTaskForRestart) exit;

?>

<?php

require_once 'mod_dbase.php';
require_once 'mod_file.php';
require_once 'mod_time.php';

$dbase = db_open();
if (!$dbase) exit;

// ��������� - �������� �� ��� ���������� ��� �������. ���� ��, �� ��������� ����
$sql = 'SELECT dtimes_run_manual.id'
	 . '	FROM dtimes_run_manual '
	 . ' WHERE dtimes_run_manual.fk_dtimes = ' . $DTimeRun
	 . ' LIMIT 1';
$res = mysqli_query($dbase, $sql);
if ((@$res) && mysqli_num_rows($res))
	list($DTimeRunAlrRest) = mysqli_fetch_row($res);

// ���������� - ����� �� ����� ������
//	 - ��, ������� �������� ��������� ��� ������-���� ����������� �������
//	 - ��� ��, ������� �������� ��������� � ������� �� �������
$sql = 'SELECT MAX(finish_dtime_global_task), MAX(start_dtime_global_task), COUNT(id_dtime_run), NOW()'
	 . '	FROM dtimes_run_t, global_tasks_t'
	 . ' WHERE fk_global_task_dtimes_run = id_global_task'
	 . '	 AND id_global_task = ' . $GlobalTaskForRestart
	 . ' LIMIT 1';
			 
$res = mysqli_query($dbase, $sql);
$res_ = mysqli_fetch_array($res);

$fdtime = $res_[0];
$sdtime = $res_[1];
$cb = $res_[2];
$now_time = $res_[3];

$interval = ( GetTimeStamp($fdtime) - GetTimeStamp($sdtime) ) / $cb;

// max1
$sql = "SELECT MAX(finish_dtime_global_task) "
	 . "	FROM global_tasks_t "
	 . " WHERE id_global_task = $GlobalTaskForRestart "
	 . " LIMIT 1";
$res = mysqli_query($dbase, $sql);
if ((!(@($res))) || !mysqli_num_rows($res)) {
	writelog("error.log", $sql);
	mysqli_close($dbase);
	exit();
	}
list($max1) = mysqli_fetch_row($res);
// max2
$sql = "SELECT MAX(new_dtime_manual) "
	 . "	FROM dtimes_run_manual "
	 . " WHERE fk_dtimes IN "
	 . "		 ( SELECT id_dtime_run "
	 . "			 FROM dtimes_run_t "
	 . "			WHERE fk_global_task_dtimes_run = $GlobalTaskForRestart "
	 . "		 ) "
	 . " LIMIT 1";
$res = mysqli_query($dbase, $sql);
if ((@($res)) && mysqli_num_rows($res)) {
	list($max2) = mysqli_fetch_row($res);
}
// max
if ($max2)
	(GetTimeStamp($max1) > GetTimeStamp($max2)) ? $max_dtime = $max1 : $max_dtime = $max2;
else
	 $max_dtime = $max1;
		
list($year, $month, $day, $h, $m, $s) = split('[ :\/.-]', $max_dtime);

list($year2, $month2, $day2, $h2, $m2, $s2) = split('[ :\/.-]', $now_time);

//echo '<br> MAX = ' . $max_dtime;
//echo '<br> NOW = ' . $now_time;
//echo '<br> INT = ' . $interval. ' second(s)';

// ������������ ������������ ����� �� ���� � �������

// ������ ����
$rndk = rand(0, 33) / 100;
if (!rand(0, 1))
		$rndk *= (-1);

if ($now_time < $max_dtime)
	$new_dtime = gmdate("Y.m.d H:i:s", mktime($h, $m, $s, $month, $day, $year) + $interval + $interval * $rndk);
else 
	$new_dtime = gmdate("Y.m.d H:i:s", mktime($h2, $m2, $s2, $month2, $day2, $year2) + $interval + $interval * $rndk);
//echo '<br><strong> NEW = ' . $new_dtime . '</strong>';
//echo '<br><strong> CHAOS K = ' . $rndk . '</strong>';

// ����. ���������� ����� ������ ������ �������. ��� ������ ...

$sql = 'SELECT id_task '
	 . '	FROM tasks_t, dtimes_run_t '
	 . ' WHERE id_task = fk_task_dtimes_run '
	 . '	 AND id_dtime_run = ' . $DTimeRun;
$res = mysqli_query($dbase, $sql);
if ((@$res) && mysqli_num_rows($res)) {
	list($TaskForRestart) = mysqli_fetch_row($res);
		 
	// ���� task �������� ���������, �� ���������� �������� ���� ���� � ��������� ����������
	// ... ���� ID ����
	$sql = 'SELECT fk_bot_task'
		 . '	FROM tasks_t'
		 . ' WHERE tasks_t.id_task = ' . $TaskForRestart
		 . ' LIMIT 1';
	$res = mysqli_query($dbase, $sql);
	if ((!(@($res))) || !mysqli_num_rows($res)) {
		writelog("error.log", $sql);
		mysqli_close($dbase);
		exit();
	}
	list($id_bot) = mysqli_fetch_row($res);
	// ... ���� ID ���������� ������� ��� �������� ����
	$sql = 'SELECT id_task'
		 . '  FROM tasks_t'
		 . ' WHERE tasks_t.fk_bot_task = ' . $id_bot
		 . ' ORDER BY id_task DESC';
	$res = mysqli_query($dbase, $sql);
	$cnt = mysqli_num_rows($res);
	if ((!(@($res))) || !$cnt) {
		writelog("error.log", $sql);
		mysqli_close($dbase);
		exit();
	}
	list($max_id_task) = mysqli_fetch_row($res);
	// ... ���� ������� ������� �������� ���������, �� ��� ���� � ����. ���������� ����
	if ($TaskForRestart == $max_id_task) {
		// ���� ��� �� ����� �������� �����-���� �������, �� ����� ����� ���� �������������� �������
		$dlr = 'NULL';
		if ($cnt > 1) {
			$sql = "SELECT date_rep"
				 . "  FROM bots_rep_t"
				 . " WHERE bots_rep_t.fk_bot_rep = $id_bot"
				 . " ORDER BY id_rep DESC";
			$res = mysqli_query($dbase, $sql);
			if ((@($res)) && mysqli_num_rows($res) > 1) {
				mysqli_fetch_row($res);
				list($time) = mysqli_fetch_row($res);
				$dlr = "'$time'";
			}
		}
	
		$sql = "UPDATE bots_t SET date_last_run_bot = $dlr WHERE id_bot = $id_bot";
		$res = mysqli_query($dbase, $sql);
	}

	// ������� �������
	$sql = "DELETE FROM tasks_t WHERE tasks_t.id_task = $TaskForRestart";
	$res = mysqli_query($dbase, $sql);
	if (mysqli_affected_rows($dbase) != 1) {
		writelog('error.log', $sql);
		mysqli_close($dbase);
		exit();
	}

	// �������� ID ������ �� ������� ��������������� �������,	������� ������������� ���������� �������
	$sql = 'SELECT id_dtime_run FROM dtimes_run_t WHERE fk_task_dtimes_run = ' . $TaskForRestart . ' LIMIT 0, 1';
	$res = mysqli_query($dbase, $sql);
	if ((!(@($res))) || !mysqli_num_rows($res)) {
		writelog("error.log", $sql);
		mysqli_close($dbase);
		exit();
	}
	list($id_dtime_run) = mysqli_fetch_row($res);

	// ��� ����� ����� ������� � �������� dtimes_run_t
	$sql = "UPDATE dtimes_run_t SET fk_task_dtimes_run = NULL WHERE fk_task_dtimes_run = $TaskForRestart";
	$res = mysqli_query($dbase, $sql);
	if (mysqli_affected_rows($dbase) != 1) {
		writelog('error.log', $sql);
		mysqli_close($dbase);
		exit();
	}

}

// ��������� ID'���� ���������������� (�� dtimes_run_t) ������� 
// ��� ��������� ����� ��������
//echo $new_dtime;
if (@$DTimeRunAlrRest) {
	$sql = "UPDATE dtimes_run_manual SET new_dtime_manual = '" . $new_dtime . "' WHERE dtimes_run_manual.id = $DTimeRunAlrRest LIMIT 1";
	//echo "<br>TASK IS ALREADY RESTARTED ... OK ... SET NEW DATE OF RESTART<br>";
}
else {
	$sql = "INSERT INTO dtimes_run_manual VALUES(NULL, $DTimeRun, '" . $new_dtime . "')";
}
$res = mysqli_query($dbase, $sql);
if (mysqli_affected_rows($dbase) != 1) {
	writelog('error.log', $sql);
	mysqli_close($dbase);
	exit();
}

db_close($dbase);

//echo "<br>TASK WAS SUCCESSFULLY RESTARTED";

if ($_GET['print']) {
	echo "<center>" . "<a href=\"#null\" onclick=\"GB_show('Edit restart time for task', '../../frm_task_restart_time.php?id_dtr=$DTimeRun', 200, 500);\"><img border='0' src='img/icos/clock.png' title='Edit time for this task'></a> $new_dtime</center>";
}

?>