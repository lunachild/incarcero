<?

function encode($String, $Password) {
    $Salt='BGuxLWQtKweKEMV4';
    $StrLen = strlen($String);
    $Seq = $Password;
    $Gamma = '';
    while (strlen($Gamma) < $StrLen)
    {
        $Seq = pack("H*", sha1($Gamma.$Seq.$Salt));
        $Gamma .= substr($Seq, 0, 8);
    }
   
    return $String^$Gamma;
}

?>