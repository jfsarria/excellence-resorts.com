<?
/*
 * Revised: Jul 22, 2016
 */

$ACTION = ($ACTION=="LIST"&&isset($_GET['MARKUP_ID'])) ? "EDIT" : $ACTION;

if ($ACTION=="EDIT") {
    $RSET = $clsMarkups->getById($db, array("MARKUP_ID"=>$MARKUP_ID));
}

if ($ACTION=="SAVE") {
    if (isset($_DATA['YEAR']) && $_DATA['YEAR'] == "") $error['YEAR'] = 'YEAR';

    if (isset($error) && sizeof($error) != 0) {
        include_once "inc/ibe.frm.err.php";
        if (isset($wasNEW) && $wasNEW) $isNEW = true;
    } else {
        if (!isset($_DATA['MARKUP_ID']) || (int)$_DATA['MARKUP_ID'] == 0) {
            $MARKUP_ID = dbNextId($db);
            $_DATA['MARKUP_ID'] = $MARKUP_ID;
        }

        //print "<pre>";print_r($_DATA);print "</pre>";
        $result = $clsMarkups->save($db, $_DATA); 

        if ((int)$result == 1) {
            include_once "inc/ibe.frm.ok.php";
        } else {
            print "<div id='s_notice' class='top_msg'>$result</div><br><br>";
        }

        $ACTION="LIST";
    }
}

if ($ACTION=="LIST") { 
    include_once "page.markup.list.php";
} else { ?>
    <form id="editfrm" method="post" enctype="multipart/form-data">
        <input type="hidden" name="ACTION" id="ACTION" VALUE="SAVE">
        <input type="hidden" name="PROP_ID" id="PROP_ID" VALUE="<? print (isset($PROP_ID)) ? $PROP_ID : "1" ?>">
        <input type="hidden" name="MARKUP_ID" id="MARKUP_ID" VALUE="<? print (isset($MARKUP_ID)) ? $MARKUP_ID : "0" ?>">
        <div class='frmBtns' style="padding-bottom:0;">
            <a onclick="$('#editfrm').submit()"><span class="button key">Submit</span></a>
            <a href="?PAGE_CODE=markup&PROP_ID=<? print (isset($PROP_ID)) ? $PROP_ID : "1" ?>"><span class="button plain">Go Back</span></a>
        </div>
        <div class="aclear"></div>
        <? include_once "inc/tpl.modules.php"; ?>
        <div class='frmBtns'>
            <a onclick="$('#editfrm').submit()"><span class="button key">Submit</span></a>
        </div>
    </form>
<? } ?>