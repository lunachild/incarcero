<script type="text/javascript">
function show_bstat (billing) {
	if (billing == 'loader')
		ajax_load('frm_stat_b-loader.php?b=' + billing, 'div_stat_b');
	else if (billing == 'knocker')
		ajax_load('frm_stat_b-knocker.php?b=' + billing, 'div_stat_b')
	else
		ajax_load('frm_stat_b.php?b=' + billing, 'div_stat_b');
}
</script>

<?php

require_once 'mod_dbase.php';

$dbase = db_open();
if (!$dbase) exit;

$sql = 'SELECT DISTINCT text_url_urls'
	 . '  FROM global_tasks_t, urls_t'
	 . ' WHERE global_tasks_t.fk_url = urls_t.id_url';
$res = mysqli_query($dbase, $sql);
if ((!@$res)/* || !mysqli_num_rows($res)*/) exit;

while (list($url) = mysqli_fetch_array($res)) {
	if (strpos($url, 'setsystems') !== false) $ss = true;
	else if (strpos($url, 'esellerate') !== false) $es = true;
	else if (strpos($url, 'fastspring') !== false) $fs = true;
	else if (strpos($url, 'clickbank') !== false) $cb = true;
	else if (strpos($url, 'shareit') !== false) $si = true;
	else if (strpos($url, 'alertpay') !== false) $ap = true;
	else if (strpos($url, 'securebillingsoftware') !== false) $syss = true;
	else if (strpos($url, 'kinovip') !== false) $kvip = true;
}

$res = mysqli_query($dbase, 'SELECT *'
						  . '  FROM gtask_loader_t');
if ((!@$res)) {
	echo "<font class='error'>error</font> : table <b>gtask_loader_t</b> is not found!";
	exit;
}
if (mysqli_num_rows($res) > 0)
	$ldr = true;

$res = mysqli_query($dbase, 'SELECT *'
						  . '  FROM gtask_knock_t');
if ((!@$res)) {
	echo "<font class='error'>error</font> : table <b>gtask_knock_t</b> is not found!";
	exit;
}
if (mysqli_num_rows($res) > 0)
	$knk = true;

echo "<!-- ajax container -->\n";
echo "<center>\n";
echo "<div id='div_stat' align='center'>\n";
echo "<td valign='middle'>";
echo "<table cellspacing='0' cellpadding='0'><tr>\n";
echo "<a href='#' onclick='show_bstat(\"all\"); return false;'>";
echo "<img src='img/icos/b-all.png' title='All billings' border='0'>";
echo "</a>";
echo "</td>\n";
$cnt = 1;
if ($ss) {
	echo "<td valign='middle'>";
	echo "<a href='#' onclick='show_bstat(\"setsystems\"); return false;'>";
	echo "<img src='img/icos/b-setsystems-prev.png' title='setsystems.com' border='0'>";
	echo "</a>";
	echo "</td>\n";
	$cnt++;
}
if ($es) {
	echo "<td valign='middle'>";
	echo "<a href='#' onclick='show_bstat(\"esellerate\"); return false;'>";
	echo "<img src='img/icos/b-esellerate-prev.png' title='esellerate.com' border='0'>\n";
	echo "</a>";
	echo "</td>\n";
	$cnt++;
}
if ($fs) {
	echo "<td valign='middle'>";
	echo "<a href='#' onclick='show_bstat(\"fastspring\"); return false;'>";
	echo "<img src='img/icos/b-fastspring-prev.png' title='fastspring.com' border='0'>\n";
	echo "</a>";
	echo "</td>\n";
	$cnt++;
}
if ($cb) {
	echo "<td valign='middle'>";
	echo "<a href='#' onclick='show_bstat(\"clickbank\"); return false;'>";
	echo "<img src='img/icos/b-clickbank-prev.png' title='clickbank.com' border='0'>\n";
	echo "</a>";
	echo "</td>\n";
	$cnt++;
}
if ($sh) {
	echo "<td valign='middle'>";
	echo "<a href='#' onclick='show_bstat(\"shareit\"); return false;'>";
	echo "<img src='img/icos/b-shareit-prev.png' title='shareit.com' border='0'>\n";
	echo "</a>";
	echo "</td>\n";
	$cnt++;
}
if ($ap) {
	echo "<td valign='middle'>";
	echo "<a href='#' onclick='show_bstat(\"alertpay\"); return false;'>";
	echo "<img src='img/icos/b-alertpay-prev.png' title='alertpay.com' border='0'>\n";
	echo "</a>";
	echo "</td>\n";
	$cnt++;
}
if ($syss) {
	echo "<td valign='middle'>";
	echo "<a href='#' onclick='show_bstat(\"securebillingsoftware\"); return false;'>";
	echo "<img src='img/icos/b-securebillingsoftware-prev.png' title='securebillingsoftware.com' border='0'>\n";
	echo "</a>";
	echo "</td>\n";
	$cnt++;
}
if ($kvip) {
	echo "<td valign='middle'>";
	echo "<a href='#' onclick='show_bstat(\"kinovip\"); return false;'>";
	echo "<img src='img/icos/b-kinovip-prev.png' title='kinovip.com' border='0'>\n";
	echo "</a>";
	echo "</td>\n";
	$cnt++;
}

if ($ldr) {
	echo "</tr><tr>";
	echo "<td align='center' valign='middle' colspan='$cnt'>";
	echo "<a href='#' onclick='show_bstat(\"loader\"); return false;'>";
	echo "<img src='img/icos/b-loader.png' title='loader' border='0'>\n";
	echo "</a>";
	echo "</td>\n";
}

if ($knk) {
	echo "</tr><tr>";
	echo "<td align='center' valign='middle' colspan='$cnt'>";
	echo "<a href='#' onclick='show_bstat(\"knocker\"); return false;'>";
	echo "<img src='img/icos/b-knocker.png' title='knocker' border='0'>\n";
	echo "</a>";
	echo "</td>\n";
}

echo "</tr></table>\n";
echo "</div>\n";
echo "</center>\n";

db_close($dbase);

?>

<hr size='1' color='#CCC'>

<div id='div_stat_b' align='center'>
</div>