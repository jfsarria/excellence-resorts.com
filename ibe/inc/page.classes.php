<?
/*
 * Revised: Dec 16, 2011
 *          Jan 31, 2017
 */

$ACTION = ($ACTION=="LIST"&&isset($_GET['CLASS_ID'])) ? "EDIT" : $ACTION;
$PROP_ID = isset($PROP_ID) ? $PROP_ID : "1";

if ($ACTION=="EDIT") {
    $RSET = $clsClasses->getById($db, array("CLASS_ID"=>$CLASS_ID));
    if ( $RSET['iCount'] != 0 ) {
        $_DATA = $db->fetch_array($RSET['rSet']);
        //print "<pre>";print_r($_DATA);print "</pre>";
    }
}

if ($ACTION=="SAVE") {
    if (isset($_DATA['NAME_'.$_IBE_LANG]) && $_DATA['NAME_'.$_IBE_LANG] == "") $error['NAME'] = 'NAME';

    if (isset($error) && sizeof($error) != 0) {
        include_once "inc/ibe.frm.err.php";
        if (isset($wasNEW) && $wasNEW) $isNEW = true;
    } else {
        if (!isset($_DATA['CLASS_ID']) || (int)$_DATA['CLASS_ID'] == 0 || ( isset($_DATA['IS_COPY'])&&$_DATA['IS_COPY']=="yes" ) ) {
            $CLASS_ID = dbNextId($db);
            $_DATA['CLASS_ID'] = $CLASS_ID;
        }

        //print "<pre>";print_r($_DATA);print "</pre>";
        $result = $clsClasses->save($db, $_DATA); 

        if ((int)$result == 1) {
            $isMetaIO = true;
            include_once "inc/ibe.frm.ok.php";
        } else {
            print "<div id='s_notice' class='top_msg'>$result</div><br><br>";
        }
    }
}

if ($ACTION=="LIST") { 
    include_once "page.classes.list.php";
} else if ($ACTION!="SAVE") { 
    $isCopy = (isset($_GET['IS_COPY'])&&$_GET['IS_COPY']=="yes") ? true : false;
    ?>
    <form id="editfrm" method="post" enctype="multipart/form-data">
        <input type="hidden" name="ACTION" id="ACTION" VALUE="SAVE">
        <input type="hidden" name="PROP_ID" id="PROP_ID" VALUE="<? print $PROP_ID ?>">
        <input type="hidden" name="CLASS_ID" id="CLASS_ID" VALUE="<? print (isset($CLASS_ID)) ? $CLASS_ID : "0" ?>">
        <input type="hidden" name="IS_COPY" id="IS_COPY" VALUE="<? print (isset($isCopy)&&$isCopy) ? "yes" : "no" ?>">
        <div class='frmBtns' style="padding-bottom:0;">
            <a onclick="$('#editfrm').submit()"><span class="button key">Submit</span></a>
            <a href="?PAGE_CODE=classes&PROP_ID=<? print $PROP_ID ?>"><span class="button plain">Go Back</span></a>
        </div>
        <div class="aclear"></div>
        <? include_once "inc/tpl.modules.php"; ?>
        <div class='frmBtns'>
            <a onclick="$('#editfrm').submit()"><span class="button key">Submit</span></a>
        </div>
    </form>
<? } else { ?>
    <center>
    <table width="500px">
    <tr>
        <td align="center"><a href="?PAGE_CODE=classes&PROP_ID=<? print $PROP_ID ?>&CLASS_ID=<? print (isset($CLASS_ID)) ? $CLASS_ID : "0" ?>"><b>Edit this Class</b></a></td>
        <td align="center"><a href="?PAGE_CODE=classes&PROP_ID=<? print $PROP_ID ?>"><b>List of Classes</b></a></td>
    </tr>
    </table>
    </center>
<? } ?>