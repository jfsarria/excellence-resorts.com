<?
/*
 * Revised: Jun 07, 2016
 */

$check_all_rooms = isset($_DATA['check_all_rooms']) ? $_DATA['check_all_rooms'] : "";
$ACTION = ($ACTION=="LIST"&&isset($_GET['ID'])) ? "EDIT" : $ACTION;
$isCopy = (isset($_GET['IS_COPY'])&&$_GET['IS_COPY']=="yes") ? true : false;
$ID = isset($_GET['ID']) ? $_GET['ID'] : 0;
$_IS_ARCHIVE = isset($_REQUEST['_IS_ARCHIVE']) ? (int)$_REQUEST['_IS_ARCHIVE'] : 0;

//print "<pre>";print_r($_DATA);print "</pre>";
//print "<pre>";print_r($_GET);print "</pre>";
//print "<pre>";print_r($_POST);print "</pre>";

if ($ACTION=="EDIT") {
    $RSET = $clsRooms->getStopSaleById($db, array("ID"=>$ID));
    if ( $RSET['iCount'] != 0 ) {
        $_DATA = $db->fetch_array($RSET['rSet']);
        $RSET = $clsRooms->getStopSaleGeos($db, array("ID"=>$ID));
        
        $GEOS = array();
        if ( $RSET['iCount'] != 0 ) {
            while ($row = $db->fetch_array($RSET['rSet'])) {
                $GEOS[] = $row['GEO_GROUP'];
            }
        }
        $_DATA['GEOS'] = $GEOS;

        $ROOM_IDs = array();
        $RSET = $clsRooms->getStopSaleRooms($db, array("ID"=>$ID));
        if ( $RSET['iCount'] != 0 ) {
            while ($row = $db->fetch_array($RSET['rSet'])) {
                $ROOM_IDs[] = $row['ROOM_ID'];
            }
        }
        $_DATA['ROOM_IDs'] = $ROOM_IDs;

        //print "<pre>";print_r($_DATA);print "</pre>";
    }
}

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

        //print "<pre>";print_r($_DATA);print "</pre>";
        $result = $clsRooms->saveStopSale($db, $_DATA); 

        if ((int)$result == 1) {
            include_once "inc/ibe.frm.ok.php";
            print "
                <script>
                    document.location.href = '?PAGE_CODE=stopsale&PROP_ID={$PROP_ID}&_IS_ARCHIVE={$_IS_ARCHIVE}';
                </script>
            ";
        } else {
            print "<div id='s_notice' class='top_msg'>$result</div><br><br>";
        }
    }
}

if ($ACTION=="LIST") { 
    include_once "page.stopsale.list.php";
} else { 
    ?>
    <form id="editfrm" method="post" enctype="multipart/form-data">
        <input type="hidden" name="ACTION" id="ACTION" VALUE="SAVE">
	
        <input type="hidden" name="ID" id="ID" VALUE="<? print (isset($ID)) ? $ID : "0" ?>">
        <input type="hidden" name="PROP_ID" id="PROP_ID" VALUE="<? print (isset($PROP_ID)) ? $PROP_ID : "1" ?>">
        <input type="hidden" name="IS_COPY" id="IS_COPY" VALUE="<? print (isset($isCopy)&&$isCopy) ? "yes" : "no" ?>">
        <div class='frmBtns' style="padding-bottom:0;">
            <a onclick="$('#editfrm').submit()"><span class="button key">Submit</span></a>
            <a href="?PAGE_CODE=stopsale&PROP_ID=<? print (isset($PROP_ID)) ? $PROP_ID : "1" ?>&_IS_ARCHIVE=<? print $_IS_ARCHIVE ?>"><span class="button plain">Go Back</span></a>
        </div>
        <div class="aclear"></div>
        <? include_once "inc/tpl.modules.php"; ?>
        <div class='frmBtns'>
            <a onclick="$('#editfrm').submit()"><span class="button key">Submit</span></a>
        </div>
    </form>
<? } ?>