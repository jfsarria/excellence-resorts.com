<?

//$get = file_get_contents("http://locateandshare.com/ibe/index.php?PAGE_CODE=ws.getGuestReservations&ID=103690&GROUPED=0");

$get = file_get_contents("http://excellence-resorts.com/ibe/index.php?PAGE_CODE=ws.getJSON&RES_ID=156188&CODE=XRC&YEAR=2012");
print_r(json_decode($get));

?>
