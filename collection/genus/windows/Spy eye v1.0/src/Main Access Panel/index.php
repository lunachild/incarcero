<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/tr/html4/loose.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<title>CN 1</title>
		<link href="css/style.css" type=text/css rel=stylesheet>
		<script type="text/javascript" src="js/ajax.js"></script>
	</head>
	<body>

	<center>
	<div id="div_main" class="div_main">
		
		<noscript>
		<font class='error'>Your JavaScript is turned off. Please, enable your JS.</font>
		</noscript>
		
		<?php require_once 'mod_auth.php'; if (!auth()) exit; ?>
	
		<!-- ajax main panel -->
		<div id='div_mainp'>
		<table cellspacing="0" cellpadding="0" border="0" width='100%' height="50px">
			<tr>
			<td align='left'><?php readfile('frm_clock.php'); ?></td>
			<td width='540px' align='center'>
				<a href="#" onclick="ajax_load('frm_gtask.php', 'div_ajax'); return false;" title='Create Task For Billing'><img src='img/b-createtask.png' alt='createtask' border='0'></a>
				<a href="#" onclick="ajax_load('frm_cards.php', 'div_ajax'); return false;" title='Modify Cards'><img src='img/b-modcards.png' alt='modcards' border='0'></a>
				<a href="#" onclick="ajax_load('frm_stat.php', 'div_ajax'); return false;" title='Tasks Statistic'><img src='img/b-statistics.png' alt='statistics' border='0'></a>
				<a href="#" onclick="ajax_load('frm_botsmon_country.php', 'div_ajax'); return false;" title='Bots Monitoring'><img src='img/b-bots.png' alt='bots' border='0'></a>
				<a href="#" onclick="ajax_load('frm_settings.php', 'div_ajax'); return false;" title='Settings'><img src='img/b-settings.png' alt='settings' border='0'></a>
				<a href="#" onclick="ajax_load('frm_banbots.php', 'div_ajax'); return false;" title='Ban Bots'><img src='img/b-banbots.png' alt='banbots' border='0'></a>
				<a href="#" onclick="ajax_load('frm_gtaskloader.php', 'div_ajax'); return false;" title='Create Task For Loader'><img src='img/b-createloadertask.png' alt='createloadertask' border='0'></a>
				<a href="#" onclick="ajax_load('frm_gtaskknock.php', 'div_ajax'); return false;" title='Create Task For Knock'><img src='img/b-createknocktask.png' alt='createknocktask' border='0'></a>
			</td>
			<td align='right'><?php readfile('frm_bots-qview.php'); ?></td>
			</tr>
		</table>
		</div>
		
		<hr size='1' color='#CCC'>
	
		<!-- ajax container -->
		<div id='div_ajax' align='center'>
		<img src='img/logomain.png'>
		</div>
	
	</div>
	</center>
	
	
		
		<script>
		if (navigator.userAgent.indexOf('Mozilla/4.0') != -1) {
			alert('Your browser is not support yet. Please, use another (FireFox, Opera, Safari)');
			document.getElementById('div_main').innerHTML = '<font class="error">ChAnGE YOuR BRoWsEr! Dont use BUGGED Microsoft products!</font>';
			}
		</script>
	
	</body>
</html>