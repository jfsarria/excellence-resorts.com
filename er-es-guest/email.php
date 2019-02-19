<?

$url = isset($_SERVER['HTTPS'])?"https://":"http://";
$url .= $_SERVER['HTTP_HOST'];

$email = $_GET['EMAIL'];
$old = $_GET['old'];
if(strtolower($old) == strtolower($email))
  die('true');
$contents = file_get_contents($url."/ibe/index.php?PAGE_CODE=ws.checkGuestEmail&email=".$email);
if($contents=="found"){
  print 'false';
} else {
  print 'true';
}