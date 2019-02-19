<?
/*
 * Revised: Apr 27, 2011
 */

$ACTION = ($ACTION=="LIST"&&isset($_GET['ROOM_ID'])) ? "EDIT" : $ACTION;

if ($ACTION=="EDIT") {
    $RSET = $clsRooms->getById($db, array("ROOM_ID"=>$ROOM_ID));
    if ( $RSET['iCount'] != 0 ) {
        $_DATA = $db->fetch_array($RSET['rSet']);
    }
}

if ($ACTION=="SAVE") {
    if (isset($_DATA['NAME_'.$_IBE_LANG]) && $_DATA['NAME_'.$_IBE_LANG] == "") $error['NAME'] = 'NAME_'.$_IBE_LANG;

    if (isset($error) && sizeof($error) != 0) {
        include_once "inc/ibe.frm.err.php";
        if (isset($wasNEW) && $wasNEW) $isNEW = true;
    } else {
        if (!isset($_DATA['ROOM_ID']) || (int)$_DATA['ROOM_ID'] == 0) {
            $ROOM_ID = dbNextId($db);
            $_DATA['ROOM_ID'] = $ROOM_ID;
        }
        $_DATA['UPS_FOLDER'] = "/ibe/ups/rooms/";

        //print "<pre>";print_r($_DATA);print "</pre>";
        $result = $clsRooms->save($db, $_DATA); 

        if ((int)$result == 1) {
            /* Upload Images & Videos */
            foreach ($_FILES as $_KEY => $_FILE) $clsImage->upload($_KEY, $_DATA['UPS_FOLDER'], $ROOM_ID);
            $isMetaIO = true;
            include_once "inc/ibe.frm.ok.php";
        } else {
            print "<div id='s_notice' class='top_msg'>$result</div><br><br>";
        }
    }
}

if ($ACTION=="LIST") { 
    include_once "page.rooms.list.php";
} else { ?>
    <form id="editfrm" method="post" enctype="multipart/form-data">
        <input type="hidden" name="ACTION" id="ACTION" VALUE="SAVE">
        <input type="hidden" name="PROP_ID" id="PROP_ID" VALUE="<? print (isset($PROP_ID)) ? $PROP_ID : "1" ?>">
        <input type="hidden" name="ROOM_ID" id="ROOM_ID" VALUE="<? print (isset($ROOM_ID)) ? $ROOM_ID : "0" ?>">
        <div class='frmBtns' style="padding-bottom:0;">
            <a onclick="$('#editfrm').submit()"><span class="button key">Submit</span></a>
            <a href="?PAGE_CODE=rooms&PROP_ID=<? print (isset($PROP_ID)) ? $PROP_ID : "1" ?>"><span class="button plain">Go Back</span></a>
        </div>
        <div class="aclear"></div>
        <? include_once "inc/tpl.modules.php"; ?>
        <div class='frmBtns'>
            <a onclick="$('#editfrm').submit()"><span class="button key">Submit</span></a>
        </div>
    </form>
<? } ?>