<table cellspacing="0" cellpadding="0" border="0" width="100px" height="50px">
<tr>
	<td width="43px" style='background: url("img/p-botsqview-p1.png"); background-repeat: no-repeat;' title="Online bots and All bots"></td>
	<td width="57px" style='background: url("img/p-botsqview-p2.png"); background-repeat: no-repeat;' id='bstat' title="Online bots and All bots"></td>
</tr>
</table>

<script type="text/javascript" defer>
function setStat (stat) {
	el = document.getElementById('bstat');
	val = '<center><b>' + stat.split('/').join('<br>') + '</b></center>';
	el.innerHTML = val;
}
function setStatAjax () {
	ajax_load('mod_bots-qview.php', ':restofunc:', setStat);
	setTimeout(setStatAjax, 5000);
}
setStatAjax();
</script>