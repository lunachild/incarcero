<?php

$idt = $_GET['idt'];
if (!@$idt) exit;
$iddtr = $_GET['iddtr'];
if (!@$iddtr) exit;

require_once 'mod_dbase.php';

$dbase = db_open();
if (!$dbase) exit;

$sql = "UPDATE tasks_t SET status_task = 1 WHERE id_task = $idt LIMIT 1";
$res = mysqli_query($dbase, $sql);

db_close($dbase);

if ($_GET['print']) {
	if ($res)
		echo $iddtr;
	else
		echo 'err';
}

?>

