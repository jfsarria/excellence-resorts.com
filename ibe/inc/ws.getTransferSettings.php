<?
/*
 * Revised: Mar 09, 2014
 */

$FIELDS = isset($_REQUEST['FIELDS']) ? $_REQUEST['FIELDS'] : "*";

print $clsTransfer->getAllSetUp($db, array(
  "FIELDS"=>$FIELDS
));

die();

?>
