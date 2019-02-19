<?
/*
 * Revised: Oct 24, 2011
 */

$ACTION = ($ACTION=="LIST"&&isset($_GET['PROP_ID'])) ? "EDIT" : $ACTION;
if ($ACTION=="LIST") $ACTION = "EDIT";

if ($ACTION=="EDIT") {

    $RSET = $clsSetup->getById($db, array("PROP_ID"=>$PROP_ID));
    if ( $RSET['iCount'] != 0 ) {
        $_DATA = $db->fetch_array($RSET['rSet']);
        //print "<pre>";print_r($_DATA);print "</pre>";
    }
}

if ($ACTION=="SAVE") {
    //if (isset($_DATA['NAME']) && $_DATA['NAME'] == "") $error['NAME'] = 'NAME';

    if (isset($error) && sizeof($error) != 0) {
        include_once "inc/ibe.frm.err.php";
        if (isset($wasNEW) && $wasNEW) $isNEW = true;
    } else {
        if (!isset($_DATA['PROP_ID']) || (int)$_DATA['PROP_ID'] == 0) {
            $PROP_ID = dbNextId($db);
            $_DATA['PROP_ID'] = $PROP_ID;
        }
        $_DATA['UPS_FOLDER'] = "/ibe/ups/props/";

        //print "<pre>";print_r($_DATA);print "</pre>";
        $result = $clsSetup->save($db, $_DATA); 

        if ((int)$result == 1) {
            /* Upload Images & Videos */
            foreach ($_FILES as $_KEY => $_FILE) $clsImage->upload($_KEY, $_DATA['UPS_FOLDER'], $PROP_ID);
            $isMetaIO = true;
            include_once "inc/ibe.frm.ok.php";
        } else {
            print "<div id='s_notice' class='top_msg'>$result</div><br><br>";
        }
    }
}

if ($ACTION=="LIST") { 
    //include_once "page.setup.list.php";
} else { ?>
    <div class='ListBtns'>
        <table>
        <tr>
            <td><h2>Property Set Up</h2></td>
        </tr>
        </table>
    </div>
    <div class="aclear"></div>

    <form id="editfrm" method="post" enctype="multipart/form-data">
        <input type="hidden" name="ACTION" id="ACTION" VALUE="SAVE">
        <input type="hidden" name="PROP_ID" id="PROP_ID" VALUE="<? print (isset($PROP_ID)) ? $PROP_ID : "1" ?>">
        <div class='frmBtns' style="padding-bottom:0;">
            <a onclick="$('#editfrm').submit()"><span class="button key">Submit</span></a>
        </div>
        <div class="aclear"></div>
        <? include_once "inc/tpl.modules.php"; ?>
        <div class='frmBtns'>
            <a onclick="$('#editfrm').submit()"><span class="button key">Submit</span></a>
        </div>
    </form>
<? } ?>