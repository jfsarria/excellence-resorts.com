<?
$json = '[[{"type":"standard","code":3,"count":1},{"type":"custom","name":"Loft","count":1}]]';

$arr = josn_decode($json, true);
print_r($arr);