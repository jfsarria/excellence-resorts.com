<?
    $url = $B_WEBSERVER.'ws/record.php';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $CCDATA);
    $response = curl_exec($ch);
    curl_close($ch);

    //print file_get_contents("https://secure-excellence-resorts.com/".'ws/record.php');
?>