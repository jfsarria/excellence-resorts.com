<?
/*
 * Revised: May 08, 2011
 */

$ACTION = ($ACTION=="LIST"&&isset($_GET['CHILDRATE_ID'])) ? "EDIT" : $ACTION;

if ($ACTION=="EDIT") {
    $RSET = $clsChildrate->getById($db, array("CHILDRATE_ID"=>$CHILDRATE_ID));
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
        if (!isset($_DATA['CHILDRATE_ID']) || (int)$_DATA['CHILDRATE_ID'] == 0) {
            $CHILDRATE_ID = dbNextId($db);
            $_DATA['CHILDRATE_ID'] = $CHILDRATE_ID;
        }

        //print "<pre>";print_r($_DATA);print "</pre>";
        $result = $clsChildrate->save($db, $_DATA); 

        if ((int)$result == 1) {
            include_once "inc/ibe.frm.ok.php";
        } else {
            print "<div id='s_notice' class='top_msg'>$result</div><br><br>";
        }
    }
}

if ($ACTION=="LIST") { 
    include_once "page.child.list.php";
} else { ?>
    <form id="editfrm" method="post" enctype="multipart/form-data">
        <input type="hidden" name="ACTION" id="ACTION" VALUE="SAVE">
        <input type="hidden" name="PROP_ID" id="PROP_ID" VALUE="<? print (isset($PROP_ID)) ? $PROP_ID : "1" ?>">
        <input type="hidden" name="CHILDRATE_ID" id="CHILDRATE_ID" VALUE="<? print (isset($CHILDRATE_ID)) ? $CHILDRATE_ID : "0" ?>">
        <div class='frmBtns' style="padding-bottom:0;">
            <a onclick="$('#editfrm').submit()"><span class="button key">Submit</span></a>
            <a href="?PAGE_CODE=child&PROP_ID=<? print (isset($PROP_ID)) ? $PROP_ID : "1" ?>"><span class="button plain">Go Back</span></a>
        </div>
        <div class="aclear"></div>
        <? include_once "inc/tpl.modules.php"; ?>
        <div class='frmBtns'>
            <a onclick="$('#editfrm').submit()"><span class="button key">Submit</span></a>
        </div>
    </form>
<? } ?>