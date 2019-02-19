<?
/*
 * Revised: Jun 09, 2011
 *          Sep 13, 2016
 */

$_FIELD = isset($_GET['field']) ? $_GET['field'] : "";
$_VALUE = isset($_GET['value']) ? $_GET['value'] : "";
$_FORMAT = isset($_GET['ContentType']) ? $_GET['ContentType'] : "xml";

ob_start();
$RSET = $clsTA->search($db, array("FIELD"=>$_FIELD,"VALUE"=>$_VALUE));

if (!empty($_FORMAT)) {
  print "<data><agents>";
  if ( $RSET['iCount'] != 0 ) {
      $cnt=0;
      while ($row = $db->fetch_array($RSET['rSet'])) {
          $AGENT = $clsGlobal->cleanUp_rSet_Array($row);
          print "<agent>";
          foreach ($AGENT AS $KEY => $VAL) {
              print "<{$KEY}>{$VAL}</{$KEY}>";
          }
          print "</agent>";
      }
  }
  print "</agents></data>";
  $OUT = ob_get_clean();
  $OUT = preg_replace(array("/\&(\w)+;/"),array(""),$OUT);

  header('Cache-Control: no-cache, must-revalidate');
  header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

  if ($_FORMAT == "xml") {
      header ("Content-type: text/xml");
      print '<?xml version="1.0" encoding="UTF-8"?>';
  }

  if ($_FORMAT == "json") {
      header ("Content-Type:application/json");
      $OUT = json_encode(simplexml_load_string($OUT));
  }

} else {

    print "<pre>";
    while ($row = $db->fetch_array($RSET['rSet'])) {
      $AGENT = $clsGlobal->cleanUp_rSet_Array($row);
      print_r($AGENT);
    }
    print "</pre>";
    $OUT = ob_get_clean();

}



print $OUT;

?>