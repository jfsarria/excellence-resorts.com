<?
    $url = (isset($WSURL)&&$WSURL!="") ? $WSURL : $B_WEBSERVER.'migration.php?';
    //print $url;
    $ch = curl_init($url);
    //curl_setopt($ch, CURLOPT_URL,$B_WEBSERVER); 
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $ARGS);
    $response = curl_exec($ch);
    curl_close($ch);
?>
