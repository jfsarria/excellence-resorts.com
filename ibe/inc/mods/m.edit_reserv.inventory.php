 <?
/*
 * Revised: Jan 03, 2013
 */

ob_start();

if ($MODIFY!="") {
    $showEdit = true;


    foreach ($RESERVATION['RES_ROOMS_SELECTED'] AS $ROOM_KEY => $ROOM_ID) {
        print $ROOM_ID."<br>";
    }

    /*
     * SAVE ROOM INVENTORY
     */
    $TABLE = substr($RESERVATION['RES_TABLE'],0,17);
    $arg = array (
        "ID"=>$RESERVATION['RES_ID'],
        "RES_ID"=>$RESERVATION['RES_ID'],
        "RES_NUM"=>$RESERVATION['RES_NUMBER']
    );

    for ($YEAR=2011;$YEAR<=(int)substr($RES_CHECK_OUT,0,4)+1;++$YEAR) {
        $arg["RES_TABLE"] = $TABLE.$YEAR."_ROOM_INVENTORY";
        //print "<pre>";print_r($arg);print "<pre>";
        $clsReserv->deleteReservationRoomInventory($db, $arg);
    }

    for ($t=0; $t < (int)$RES_NIGHTS; ++$t) {
        $arg["RES_DATE"] = addDaysToDate($RES_CHECK_IN, $t);
        foreach ($RESERVATION['ROOMS'] as $ROOM_KEY => $ROOM_DATA) {
            $arg["ID"] = dbNextId($db);
            $arg["ROOM_ID"]  = $RESERVATION['RES_ROOMS_SELECTED'][$ROOM_KEY];
            $arg["RES_TABLE"] = $TABLE.((int)substr($arg["RES_DATE"],0,4))."_ROOM_INVENTORY";
            print "<pre>";print_r($arg);print "<pre>";
            $saveInventory = $clsReserv->saveReservationRoomInventory($db, $arg);
            if ((int)$saveInventory != 1) {
                print "Error saving room inventory.<br>";
            }
        }
    }

    //print "<script>document.location.href=\"{$THIS_PAGE}\";</script>";

}

$RESULT = ob_get_clean();

if (!$isWEBSERVICE) print $RESULT;        

?>
