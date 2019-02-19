<?
/*
 * Revised: Dec 15, 2011
 */

$ACTION = ($ACTION=="LIST"&&isset($_GET['SEASON_ID'])) ? "EDIT" : $ACTION;
$isCopy = (isset($_GET['IS_COPY'])&&$_GET['IS_COPY']=="yes") ? true : false;

if ($ACTION=="EDIT") {
    $RSET = $clsSeasons->getById($db, array("SEASON_ID"=>$SEASON_ID));
    if ( $RSET['iCount'] != 0 ) {
        $_DATA = $db->fetch_array($RSET['rSet']);
        //print "<pre>";print_r($_DATA);print "</pre>";
    }
}

if ($ACTION=="SAVE") {
    if (isset($_DATA['NAME']) && $_DATA['NAME'] == "") $error['NAME'] = 'NAME';

    if (isset($error) && sizeof($error) != 0) {
        include_once "inc/ibe.frm.err.php";
        if (isset($wasNEW) && $wasNEW) $isNEW = true;
    } else {
        if (!isset($_DATA['SEASON_ID']) || (int)$_DATA['SEASON_ID'] == 0 || ( isset($_DATA['IS_COPY'])&&$_DATA['IS_COPY']=="yes" )) {
            $SEASON_ID = dbNextId($db);
            $_DATA['SEASON_ID'] = $SEASON_ID;
        }

        //print "<pre>";print_r($_DATA);print "</pre>";
        $result = $clsSeasons->save($db, $_DATA); 

        if ((int)$result == 1) {
            include_once "inc/ibe.frm.ok.php";
            print "
                <script>
                    document.location.href = '?PAGE_CODE=seasons&PROP_ID={$PROP_ID}&SEASON_ID={$SEASON_ID}';
                </script>
            ";
        } else {
            print "<div id='s_notice' class='top_msg'>$result</div><br><br>";
        }
    }
}

if ($ACTION=="LIST") { 
    include_once "page.seasons.list.php";
} else { 
    ?>
    <form id="editfrm" method="post" enctype="multipart/form-data">
        <input type="hidden" name="ACTION" id="ACTION" VALUE="SAVE">
        <input type="hidden" name="PROP_ID" id="PROP_ID" VALUE="<? print (isset($PROP_ID)) ? $PROP_ID : "1" ?>">
        <input type="hidden" name="SEASON_ID" id="SEASON_ID" VALUE="<? print (isset($SEASON_ID)) ? $SEASON_ID : "0" ?>">
        <input type="hidden" name="IS_COPY" id="IS_COPY" VALUE="<? print (isset($isCopy)&&$isCopy) ? "yes" : "no" ?>">
        <div class='frmBtns' style="padding-bottom:0;">
            <a onclick="$('#editfrm').submit()"><span class="button key">Submit</span></a>
            <a href="?PAGE_CODE=seasons&PROP_ID=<? print (isset($PROP_ID)) ? $PROP_ID : "1" ?>"><span class="button plain">Go Back</span></a>
        </div>
        <div class="aclear"></div>
        <? include_once "inc/tpl.modules.php"; ?>
        <div class='frmBtns'>
            <a onclick="$('#editfrm').submit()"><span class="button key">Submit</span></a>
        </div>
    </form>
<? } ?>