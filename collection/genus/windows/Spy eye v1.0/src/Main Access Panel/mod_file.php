<?php

function writelog ($url, $data)
{
     $url = trim(htmlspecialchars($url));
     $f = fopen($url, "a");
     fwrite($f, gmdate("r") . "  -  " . $data . "\n");
     fclose($f);
}

?>