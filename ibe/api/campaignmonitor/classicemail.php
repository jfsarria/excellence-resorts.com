<?
$POSTFIELDS = '{
  "Subject": "Password reset request for ABC Widgets",
  "From": "reservations@excellence-resorts.com",
  "ReplyTo": "Jane Smith <jane@abcwidgets.com>",
  "To": [
    Juan Sarria Gmail <jaunsarria@gmail.com>"
  ],
  "CC": null,
  "BCC": null,
  "HTML": "html content goes here",
  "Text": "plain text content goes here",
  "Attachments": [],
  "BasicGroup": "IBE test group"
}';

$APIKey = "7d024234924c83681099369b885e9c2ba7b6d57c8f62f42c";
$ClientID = "db8bd893bb8cea1420ea55d643201bc4";
$password = "";

$headers = array(
    'Content-Type:application/json',
    'Authorization: Basic '. base64_encode("$APIKey:$password")
);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://api.createsend.com/api/v3.1/transactional/classicEmail/send?clientID=" . $ClientID);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_HEADER, 1);
//curl_setopt($ch, CURLOPT_USERPWD, $APIKey . ":" . $password);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $POSTFIELDS);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
$result = curl_exec($ch);
curl_close($ch);

print $result;
