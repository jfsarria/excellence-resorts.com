<?
/*
 * Revised: Jun 08, 2015
 */

$ACTION = ($ACTION=="LIST"&&isset($_GET['BANNER_ID'])) ? "EDIT" : $ACTION;

if ($ACTION=="EDIT") {
    $RSET = $clsBanners->getById($db, array("BANNER_ID"=>$BANNER_ID));
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
        if (!isset($_DATA['BANNER_ID']) || (int)$_DATA['BANNER_ID'] == 0) {
            $BANNER_ID = dbNextId($db);
            $_DATA['BANNER_ID'] = $BANNER_ID;
        }
        $_DATA['UPS_FOLDER'] = "/ibe/ups/banners/";

        //print "<pre>";print_r($_DATA);print "</pre>";
        $result = $clsBanners->save($db, $_DATA); 

        if ((int)$result == 1) {
            /* Upload Images & Videos */
            foreach ($_FILES as $_KEY => $_FILE) $clsImage->upload($_KEY, $_DATA['UPS_FOLDER'], $BANNER_ID);
            include_once "inc/ibe.frm.ok.php";
        } else {
            print "<div id='s_notice' class='top_msg'>$result</div><br><br>";
        }
    }
}

if ($ACTION=="LIST") { 
    include_once "page.banners.list.php";
} else { ?>
    <form id="editfrm" method="post" enctype="multipart/form-data">
        <input type="hidden" name="ACTION" id="ACTION" VALUE="SAVE">
        <input type="hidden" name="PROP_ID" id="PROP_ID" VALUE="<? print (isset($PROP_ID)) ? $PROP_ID : "1" ?>">
        <input type="hidden" name="BANNER_ID" id="BANNER_ID" VALUE="<? print (isset($BANNER_ID)) ? $BANNER_ID : "0" ?>">
        <div class='frmBtns' style="padding-bottom:0;">
            <a onclick="$('#editfrm').submit()"><span class="button key">Submit</span></a>
            <a href="?PAGE_CODE=banners&PROP_ID=<? print (isset($PROP_ID)) ? $PROP_ID : "1" ?>"><span class="button plain">Go Back</span></a>
        </div>
        <div class="aclear"></div>
        <? include_once "inc/tpl.modules.php"; ?>
        <div class='frmBtns'>
            <a onclick="$('#editfrm').submit()"><span class="button key">Submit</span></a>
        </div>
    </form>
<? } ?>