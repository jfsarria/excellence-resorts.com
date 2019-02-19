<?
/*
 * Revised: Oct 08, 2015
 */

$ACTION = ($ACTION=="LIST"&&isset($_GET['ID'])) ? "EDIT" : $ACTION;
$isCopy = (isset($_GET['IS_COPY'])&&$_GET['IS_COPY']=="yes") ? true : false;
$ID = isset($_GET['ID']) ? $_GET['ID'] : 0;

if ($ACTION=="EDIT") {
    $RSET = $clsTransferCarSeason->getById($db, array("ID"=>$ID));
    if ( $RSET['iCount'] != 0 ) {
        $_DATA = $db->fetch_array($RSET['rSet']);
        //print "<pre>";print_r($_DATA);print "</pre>";
    }
}

//print "<pre>";print_r($_DATA);print "</pre>";

if ($ACTION=="SAVE") {
    if (isset($_DATA['NAME']) && $_DATA['NAME'] == "") $error['NAME'] = 'NAME';

    if (isset($error) && sizeof($error) != 0) {
        include_once "inc/ibe.frm.err.php";
        if (isset($wasNEW) && $wasNEW) $isNEW = true;
    } else {
        if (!isset($_DATA['ID']) || (int)$_DATA['ID'] == 0 || ( isset($_DATA['IS_COPY'])&&$_DATA['IS_COPY']=="yes" )) {
            $ID = dbNextId($db);
            $_DATA['ID'] = $ID;
        }

        //print "save: <pre>";print_r($_DATA);print "</pre>";
        $result = $clsTransferCarSeason->save($db, $_DATA); 

        if ((int)$result == 1) {
            include_once "inc/ibe.frm.ok.php";
            print "
                <script>
                    document.location.href = '?PAGE_CODE=transfer_car_season&PROP_ID={$PROP_ID}&_YEAR={$_REQUEST['YEAR']}';
                </script>
            ";
        } else {
            print "<div id='s_notice' class='top_msg'>$result</div><br><br>";
        }
    }
}

if ($ACTION=="LIST") { 
    include_once "page.transfer_car_season.list.php";
} else { 
    ?>
    <form id="editfrm" method="post" enctype="multipart/form-data">
        <input type="hidden" name="ACTION" id="ACTION" VALUE="SAVE">
        <input type="hidden" name="_YEAR" id="_YEAR" value="<?php print $_GET['YEAR'] ?>">
        <input type="hidden" name="IS_COPY" id="IS_COPY" VALUE="<? print (isset($isCopy)&&$isCopy) ? "yes" : "no" ?>">
        <div class='frmBtns' style="padding-bottom:0;">
            <a onclick="$('#editfrm').submit()"><span class="button key">Submit</span></a>
            <a href="?PAGE_CODE=transfer_car_season&PROP_ID=<? print (isset($PROP_ID)) ? $PROP_ID : "1" ?>&_YEAR=<?php print $_GET['YEAR'] ?>"><span class="button plain">Go Back</span></a>
        </div>
        <div class="aclear"></div>
        <? include_once "inc/tpl.modules.php"; ?>
        <div class='frmBtns'>
            <a onclick="$('#editfrm').submit()"><span class="button key">Submit</span></a>
        </div>
    </form>
<? } ?>