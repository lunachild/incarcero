<?php

$idc = $_GET['idc'];
if (!@$idc)
  exit();

require_once 'mod_dbase.php';
require_once 'mod_bots.php';
require_once 'mod_file.php';
require_once 'bt_update_stuff.php';

$dbase = db_open();
if (!$dbase) exit;

	      $res = mysqli_query($dbase, "SELECT distinct city_t.state FROM city_t WHERE fk_country_city = $idc ORDER BY city_t.state");
		  $cnt = mysqli_num_rows($res);
          if ($cnt>0)
		  {
		  
		  $rslt .= "<table width='100%' border='1' cellspacing='0' cellpadding='0' style='border: 1px solid gray; font-size: 9px; border-collapse: collapse; background-color: #F4F4F4;'>";
		  $rslt .= "<th width='200px' style='background-color: #DDD;'>State</th>";
		  $rslt .= "<th style='background-color: #DDD;'>Online Bots/<font class='disabled'>Disabled Bots</font> / All Bots</td></th>";
              for ($i = 0; $mres = mysqli_fetch_array($res); $i++)
              {
			  
	  $res2 = mysqli_query($dbase, "SELECT count(id_bot) FROM bots_t WHERE (fk_city_bot IN (SELECT id_city FROM city_t, country_t WHERE fk_country_city = id_country AND id_country = $idc AND state = '".$mres['state']."')) AND (status_bot <> 'offline')");
	  $actb_cnt = -1;
	  if ((@($res2)) && mysqli_num_rows($res2) > 0)
	  {
		$mres2 = mysqli_fetch_array($res2);
		$actb_cnt = $mres2[0];
	  }

	  $res2 = mysqli_query($dbase, "SELECT count(id_bot) FROM bots_t WHERE fk_city_bot IN (SELECT id_city FROM city_t, country_t WHERE fk_country_city = id_country AND id_country = $idc AND state = '".$mres['state']."')");
	  $allb_cnt = -1;
	  if ((@($res2)) && mysqli_num_rows($res2) > 0)
	  {
		$mres2 = mysqli_fetch_array($res2);
		$allb_cnt = $mres2[0];
	  }
	  

		$icfg = parse_ini_file('config.ini');
		$wait_before_start = $icfg['WAIT_BEFORE_START'];
		if (strlen($wait_before_start) != 19) {
			$wait_before_start = '1970-04-01 00:00:00';
			writelog('error.log', 'Cannot read WAIT_BEFORE_START from config');
		}
	  
	  				$sql = "SELECT count(distinct bots_t.id_bot)"
				    . " FROM bots_t, bots_rep_t, city_t"
					. " WHERE bots_t.id_bot = bots_rep_t.fk_bot_rep"
					. "   AND bots_t.fk_city_bot = city_t.id_city"
					. "   AND city_t.fk_country_city = " . $idc
					. "   AND city_t.state = '" . $mres['state'] . "'"
					. "   AND status_bot <> 'offline'"
					. "   AND ( bots_rep_t.data_rep like '%sloppy%'"
					. "    OR UNIX_TIMESTAMP( date_last_run_bot ) >= ( UNIX_TIMESTAMP( now( ) ) - UNIX_TIMESTAMP('" . $wait_before_start . "') )"
					. "    OR bots_t.blocked = 1"
					. "    OR bots_t.ver_bot <> " . LAST_VERSION_BOT . " )";
				   $res2 = mysqli_query($dbase, $sql);
				   $disb_cnt = 0;
				   if ((@($res2)) && mysqli_num_rows($res2) > 0)
				   {
						$mres2 = mysqli_fetch_array($res2);
						$disb_cnt = $mres2[0];
				   }

			  $ccode = 'null';
              for ($j = 1; $j <= count($gi->GEOIP_COUNTRY_NAMES); $j++)
              {
			    if (!strcmp($gi->GEOIP_COUNTRY_NAMES[$j], $mres['name_country']))
				{
				  $ccode = $gi->GEOIP_COUNTRY_CODES[$j];
				  break;
				}
			  }
			  
			  $rslt .= "<tr align='center'><td> " . $mres['state'] . " </td><td>($actb_cnt/<font class='disabled'>$disb_cnt</font> / $allb_cnt)</td></tr>";

			  
			  }
		  } else {$rslt .= "<p>message>> ERROR IN SELECTION OF 'COUNTRY_T'"; return; }

		  $rslt .= "</table>";
		  
      db_close ($dbase);

	  echo $rslt;

?>