<?php

$id_dtr = $_GET['id_dtr'];
if (!@$id_dtr) exit;

?>

<?php

require_once 'mod_dbase.php';
require_once 'mod_file.php';

$dbase = db_open();
if (!$dbase) exit;

// �������� ������� ����� ��������
$sql = ' SELECT id, new_dtime_manual'
	 . '   FROM dtimes_run_manual'
	 . "  WHERE fk_dtimes = $id_dtr"
	 . '  LIMIT 0, 1';
$res = mysqli_query ($dbase, $sql);
if ((!(@($res))) || !mysqli_num_rows($res)) {
	writelog("error.log", $sql);
	db_close($dbase);
	exit();
}				
list ($id, $time) = mysqli_fetch_row($res);

db_close($dbase);

?>

<?php

$content = "<form id='frm_edtdtrtime' onsubmit='var pdata = ajax_getInputs(\"frm_edtdtrtime\"); ajax_pload(\"mod_tast_restart_time_update.php\", pdata, \"div_ajax_trest\"); return false;'>\n";
$content .= "<input type='hidden' id='dtrid' name='dtrid' value='$id'>\n";
$content .= "<input size='25' id='time' name='time' value='$time'><br><br>\n";
$content .= "<input type='submit' value='APPLY'>\n";
$content .= "<input type='button' value='CANCEL' onclick='parent.parent.GB_hide();'>\n";
$content .= "</form>\n";
$content .= "<hr size='1' color='#CCC'>\n";
$content .= "<div id='div_ajax_trest'>\n";
$content .= "</div>\n";

?>

<?php
require_once 'frm_skelet.php';
echo get_smskelet('Edit restart time of task', $content);
?>