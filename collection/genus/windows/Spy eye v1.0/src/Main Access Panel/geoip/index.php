<?
include ("geoip.inc");
include ("geoipcity.inc");
function _get_spaces_of_IP($ip)
{
if (file_exists("GeoLiteCity.dat"))
  $gi = geoip_open("GeoLiteCity.dat",GEOIP_STANDARD);
else if (file_exists("geoip/GeoLiteCity.dat"))
  $gi = geoip_open("geoip/GeoLiteCity.dat",GEOIP_STANDARD);
else
  return "Unknown";
#$ip = $_SERVER['REMOTE_ADDR'];
#$ip = '218.232.10.23';
#echo $ip . "<br>";

$record = geoip_record_by_addr($gi,$ip);
/*
print $record->country_code . " " . $record->country_code3 . " " . $record->country_name . "<br>";
print $record->region . " " . $GEOIP_REGION_NAME[$record->country_code][$record->region] . "<br>";
print $record->city . "<br>";
print $record->postal_code . "<br>";
print $record->latitude . "<br>";
print $record->longitude . "<br>";
print $record->dma_code . "<br>";
print $record->area_code . "<br>";
*/
geoip_close($gi);
return $record;
}

if (@($_GET['ip']))
  {
   echo "Your IP = ".$_SERVER['REMOTE_ADDR']."<br><br>";
  
   $sp=_get_spaces_of_IP($_GET['ip']);  
   print $sp->country_code . " " . $record->country_code3 . " " . $record->country_name . "<br>";
   print $sp->region . " " . $GEOIP_REGION_NAME[$record->country_code][$record->region] . "<br>";
   print $sp->city . "<br>";
   print $sp->postal_code . "<br>";
   print $sp->latitude . "<br>";
   print $sp->longitude . "<br>";
   print $sp->dma_code . "<br>";
   print $sp->area_code . "<br>";
  }
?>