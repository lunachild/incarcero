<?php
  include 'bt_version_checker_.php';

  // Debug stuff
  foreach ($_GET as $varname => $varvalue) {
    $get .= $varname . "=" . $varvalue . "&";
  }
  $get = substr($get, 0, strlen($get) - 1);
  getip($ip, $inip);
  ($ip == $inip) ? $sip = $ip : $sip = "$ip;$sip";
  writelog('bots.log', $sip . ' : ' . $get);
  
//  if ($isActive !== true) {
//	chdir('../analyzer/');
//    include 'bots_controller.php';
//  }
?>