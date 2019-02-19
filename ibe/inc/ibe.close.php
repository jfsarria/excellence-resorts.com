<?
/*
 * Revised: Jun 24, 2011
 *          May 21, 2017
 */

$tmpl->body = ob_get_clean();

$tmpl->body = str_replace(array("Á","á","É","é","Í","í","Ñ","ñ","Ó","ó","Ú","ú","Ü","ü"),array("&#193;","&#225;","&#201;","&#233;","&#205;","&#237;","&#209;","&#241;","&#211;","&#243;","&#218;","&#250;","&#220;","&#252;"),$tmpl->body);

if ($isWEBSERVICE || $isEXPORT) {
    $STR = $tmpl->body; 
    $STR = str_replace("\r\n","",$STR);
    if ($isEXPORT) {
        header('Content-type: application/vnd.ms-excel');
        header("Content-Disposition: attachment; filename=".(isset($EXCEL_NAME)?$EXCEL_NAME:"report.xls"));
        header("Pragma: no-cache");
        header("Expires: 0");
        $STR = strip_tags($STR, '<table><th><tr><td>');
    }
    print $STR;
} else {
    if ($_PAGE_PREFIX=="") {
        $TMPL_NAME = "tpl.empty.php";
    }
    $TMPL = (isset($TMPL_NAME)) ? (($TMPL_NAME!="")?"inc/$TMPL_NAME":"") : "inc/tpl.main.php";
    if ($TMPL!="") include_once $TMPL;
}

if (isset($_GET['start'])) {
    print "
        <script>
            ibe.callcenter.emailPrePostStay();
        </script>
    ";
}

include_once "inc/db.close.php";
?>