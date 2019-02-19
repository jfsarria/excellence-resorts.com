<?
/*
 * Revised: Jun 12, 2015
 *			
 */

$COUNTRY_CODE = isset($_GET['COUNTRY_CODE'])&&!empty($_GET['COUNTRY_CODE']) ? strtoupper($_GET['COUNTRY_CODE']) : "US";
$THIS_PAGE = isset($_GET['THIS_PAGE']) ? trim($_GET['THIS_PAGE']) : "";
$LANG = isset($_GET['LANG']) ? strtoupper($_GET['LANG']) : "EN";
$FORMAT = isset($_GET['FORMAT']) ? $_GET['FORMAT'] : "JSON";

$BANNER = array();
$RESULTS = $clsBanners->getWebBanner($db, $_GET);

if (count($RESULTS)!=0) {
  foreach ($RESULTS AS $IND => $RESULT) {
    //print "<pre>";print_r($RESULT);print "</pre>";
    $isOk = false;
    $PUBLISH_URLS = $RESULT['PUBLISH_URLS'];
    if (empty($PUBLISH_URLS)) {
      $isOk = true;
    } else if (!empty($THIS_PAGE)) {
      $PAGES = explode("\r\n",$PUBLISH_URLS);
      foreach ($PAGES as $PAGE) {
        if (!empty($PAGE) && stristr($THIS_PAGE,$PAGE)!==FALSE) {
          $isOk = true;
        }
      }
    }
    if ($isOk) {
      $BANNER = $RESULT;
      break;
    }
  }
}

//print "<pre>";print_r($BANNER);print "</pre>";

if ($FORMAT=="JSON") {

  $OUTPUT = array();
  foreach ($BANNER as $KEY => $VAL) {
    if (is_string($KEY) && $KEY!="PUBLISH_URLS") { $OUTPUT[$KEY] = $VAL; }
  }

  header('Cache-Control: no-cache, must-revalidate');
  header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
  header("Content-Type:application/json");
  print json_encode($OUTPUT);

} else {
  $HTML = "";

  if (count($BANNER)!=0) {
    $IMAGES = $clsUploads->getByParent($db, array("PARENT_ID"=>$BANNER['BANNER_ID']));
    $BANNER["IMAGE_EN"] = "";
    $BANNER["IMAGE_SP"] = "";
    $cnt = 0;
    while ($row = $db->fetch_array($IMAGES['rSet'])) {
      //print_r($row);
      $BANNER["IMAGE_".(++$cnt==1?"EN":"SP")] = $row['NAME'];
      if ($cnt==2) { break; }
    }
    $IMAGE = isset($BANNER["IMAGE_$LANG"]) ? $BANNER["IMAGE_$LANG"] : "";
    $RTEXT = isset($BANNER["RTEXT_$LANG"]) ? $BANNER["RTEXT_$LANG"] : "";
    $RLABEL = isset($BANNER["RLABEL_$LANG"]) ? $BANNER["RLABEL_$LANG"] : "";
    $BG_COLOR = isset($BANNER["BG_COLOR"]) ? $BANNER["BG_COLOR"] : "";
    $FONT_COLOR = isset($BANNER["FONT_COLOR"]) ? $BANNER["FONT_COLOR"] : "";
    $CONDITIONS = isset($BANNER["CONDITIONS_$LANG"]) ? nl2br($BANNER["CONDITIONS_$LANG"]) : "";

    $HTML = isset($BANNER["HTML"]) ? "<div>".$BANNER["HTML"]."</div>" : "";
    //$HTML = html_entity_decode($HTML);
    $HTML = html_entity_decode($HTML, ENT_QUOTES);
    $HTML = str_replace("[IMAGE]",$IMAGE,$HTML);
    $HTML = str_replace("[RTEXT]",$RTEXT,$HTML);
    $HTML = str_replace("[RLABEL]",$RLABEL,$HTML);
    $HTML = str_replace("[BG_COLOR]",$BG_COLOR,$HTML);
    $HTML = str_replace("[FONT_COLOR]",$FONT_COLOR,$HTML);
    $HTML = str_replace("[CONDITIONS]",$CONDITIONS,$HTML);
  }

  print preg_replace( "/\r|\n/", "", $HTML );
}

?>