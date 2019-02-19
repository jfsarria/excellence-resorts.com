<?
/*
 * Revised: Dec 15, 2011
 */

$STYLED_PROP_NAME = "<span style='font-size:14px'><b>".stylePropertyName($RES_ITEMS['PROPERTY']['NAME'],(int)$RES_PROP_ID)."</b></span>";

$OUTOUT = ((int)$RES_PROP_ID==4) ? "<div style='font:normal 12px Arial;color:#333333;'>" : "<div style='font:normal 12px Georgia;color:#000000;'>";

$EMAIL_POSTSTAY = html_entity_decode($SETUP['EMAIL_POSTSTAY_'.$RES_LANGUAGE]);
$OUTOUT .= "\n".$EMAIL_POSTSTAY."\n";

$OUTOUT = str_replace(array("\n","\r\n"),array("<br>","<br>"),$OUTOUT);

print $OUTOUT;

?>