<?

print_r($_REQUEST);
print_r($_SERVER);
/*
$email = $_GET['EMAIL'];
$old = $_GET['old'];
if(strtolower($old) == strtolower($email))
  die('true');
//$contents = file_get_contents("http://www.locateandshare.com/ibe/index.php?PAGE_CODE=ws.checkTAEmail&email=".$email);
$ta = json_decode(file_get_contents("http://www.locateandshare.com/ibe/index.php?PAGE_CODE=ws.searchTA&field=EMAIL&value=".$email."&ContentType=json"));

if(isset($ta->agents->agent->ID)){
  print 'false';
} else {
  print 'true';
}*/