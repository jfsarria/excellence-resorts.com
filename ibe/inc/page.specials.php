<?
/*
 * Revised: Dec 16, 2011
 */

$ACTION = ($ACTION=="LIST"&&isset($_GET['SPECIAL_ID'])) ? "EDIT" : $ACTION;
$_YEAR = isset($_REQUEST['_YEAR']) ? (int)$_REQUEST['_YEAR'] : date("Y");
$_MONTH = isset($_REQUEST['_MONTH']) ? (int)$_REQUEST['_MONTH'] : date("n");
$_IS_ARCHIVE = isset($_REQUEST['_IS_ARCHIVE']) ? (int)$_REQUEST['_IS_ARCHIVE'] : 0;

if ($ACTION=="EDIT") {
    $RSET = $clsSpecials->getById($db, array("SPECIAL_ID"=>$SPECIAL_ID));
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
        if (!isset($_DATA['SPECIAL_ID']) || (int)$_DATA['SPECIAL_ID'] == 0 || ( isset($_DATA['IS_COPY'])&&$_DATA['IS_COPY']=="yes" ) ) {
            $SPECIAL_ID = dbNextId($db);
            $_DATA['SPECIAL_ID'] = $SPECIAL_ID;
        }

        //print "<pre>";print_r($_DATA);print "</pre>";
        $result = $clsSpecials->save($db, $_DATA); 

        if ((int)$result == 1) {
            print "
                <script>
                    document.location.href = '?PAGE_CODE=specials&PROP_ID={$PROP_ID}&SPECIAL_ID={$SPECIAL_ID}&_YEAR={$_YEAR}&_MONTH={$_MONTH}&_IS_ARCHIVE={$_IS_ARCHIVE}&SAVED=1';
                </script>
            ";
        } else {
            print "<div id='s_notice' class='top_msg'>$result</div><br><br>";
            $ACTION="EDIT";
        }
    }
}

if (isset($_REQUEST["SAVED"])) {
    $isMetaIO = true;
    include_once "inc/ibe.frm.ok.php";
}

if ($ACTION=="LIST") { 
    include_once "page.spacials.list.php";
} 
if ($ACTION=="EDIT") {
    $isCopy = (isset($_GET['IS_COPY'])&&$_GET['IS_COPY']=="yes") ? true : false;
    ?>
    <form id="editfrm" method="post" enctype="multipart/form-data">
        <input type="hidden" name="ACTION" id="ACTION" VALUE="SAVE">
        <input type="hidden" name="PROP_ID" id="PROP_ID" VALUE="<? print (isset($PROP_ID)) ? $PROP_ID : "1" ?>">
        <input type="hidden" name="SPECIAL_ID" id="SPECIAL_ID" VALUE="<? print (isset($SPECIAL_ID)) ? $SPECIAL_ID : "0" ?>">
        <input type="hidden" name="IS_COPY" id="IS_COPY" VALUE="<? print (isset($isCopy)&&$isCopy) ? "yes" : "no" ?>">
        <div class='frmBtns' style="padding-bottom:0;">
            <a onclick="$('#editfrm').submit()"><span class="button key">Submit</span></a>
            <a href="?PAGE_CODE=specials&PROP_ID=<? print (isset($PROP_ID)) ? $PROP_ID : "1" ?>&_YEAR=<? print $_YEAR ?>&_MONTH=<? print $_MONTH ?>&_IS_ARCHIVE=<? print $_IS_ARCHIVE ?>"><span class="button plain">Go Back</span></a>
        </div>
        <div class="aclear"></div>
        <? include_once "inc/tpl.modules.php"; ?>
        <div class='frmBtns'>
            <a onclick="$('#editfrm').submit()"><span class="button key">Submit</span></a>
        </div>
    </form>
    <? 
} 
?>