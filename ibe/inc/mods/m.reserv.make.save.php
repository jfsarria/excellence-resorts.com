<?
/*
 * Revised: Jan 06, 2013
 *          Feb 24, 2016
 *          Dec 13, 2017
 */

ob_start();

if ($isOk) {

    // VERIFY INVENTORY 

    $ROOM_IDs = array();
    foreach ($_SESSION['AVAILABILITY']["RESERVATION"]["RES_ROOMS_SELECTED"] AS $ROOM_ID) {
        array_push($ROOM_IDs, $ROOM_ID);
    }

    $FROM = $_SESSION['AVAILABILITY']['RES_CHECK_IN'];
    $TO = $_SESSION['AVAILABILITY']['RES_CHECK_OUT'];
    $CODE = $_SESSION['AVAILABILITY']['RES_ITEMS']['PROPERTIES'][$_SESSION['AVAILABILITY']['RES_PROP_ID']]['CODE'];

    $YEAR_START = date("Y", strtotime($FROM));
    if ($YEAR_START<date("Y")) $YEAR_START = date("Y");

    $YEAR_END = date("Y", strtotime($TO));
    if ($YEAR_END>date("Y")+1) $YEAR_END = date("Y")+1;

    $YEARS = array_unique(array($YEAR_START,$YEAR_END));

    $_ARG =  array (
        "ROOM_IDs"=>$ROOM_IDs,
        "FROM"=>$FROM,
        "TO"=>$TO,
        "CODE"=>$CODE,
        "YEAR"=>(isset($YEAR))?$YEAR:date("Y"),
        "YEARS"=>(isset($YEARS))?$YEARS:array(date("Y"))
    );

    //ob_start();print_r($_ARG);$output = ob_get_clean();mail("juan.sarria@everlivesolutions.com","Inventory","$output");

    $I_RSET = $clsInventory->getInventory($db, $_ARG);
    $R_RSET = $clsInventory->getRooms($db, $_ARG);
    $O_RSET = $clsInventory->getAddAllocation($db, $_ARG);
    $C_RSET = $clsInventory->getBlackOut($db, $_ARG);

    $INVENTORY = array();
    $OVERRIDE = array();
    $BLACKOUT = array();
    $MAX_ROOMS = array();
    $STOPSALE = array();

    if (!is_string($I_RSET))  while ($row = $db->fetch_array($I_RSET['rSet'])) $INVENTORY[$row['ROOM_ID']][substr($row['RES_DATE'],0,10)] = $row['SOLD'];
    while ($row = $db->fetch_array($O_RSET['rSet'])) $OVERRIDE[$row['ROOM_ID']][substr($row['RES_DATE'],0,10)] = $row['QTY'];
    while ($row = $db->fetch_array($C_RSET['rSet'])) $BLACKOUT[$row['ROOM_ID']][substr($row['DATE_CLOSED'],0,10)] = 1;
    if (isset($R_RSET)) while ($row = $db->fetch_array($R_RSET['rSet'])) $MAX_ROOMS[$row['ID']] = (int)$row['MAX_ROOMS'];

    $_DATA = array(
      'RES_CHECK_IN' => $FROM,
      'RES_CHECK_OUT' => $TO,
      'RES_COUNTRY_CODE' => $_SESSION['AVAILABILITY']['RES_COUNTRY_CODE'],
      'RES_COUNTRY_GROUP' => $clsGlobal->getCountryGroupByCode($db, array("CODE"=>$_SESSION['AVAILABILITY']['RES_COUNTRY_CODE']))
    );
    $STOPSALE = $clsInventory->makeStopSale($db, $_ARG, $_DATA);

    //ob_start();print "<pre>_ARG: ";print_r($_ARG);print "INVENTORY: ";print_r($INVENTORY);print "OVERRIDE: ";print_r($OVERRIDE);print "STOPSALE: ";print_r($STOPSALE);print "MAX_ROOMS: ";print_r($MAX_ROOMS);print "</pre>";$output = ob_get_clean();mail("juan.sarria@everlivesolutions.com","Inventory ".rand(),"$output".rand());
    //ob_start();print_r($_SESSION['AVAILABILITY']);$output .= ob_get_clean();mail("jaunsarria@gmail.com","AVAILABILITY","$output");

    $ROOMS_ERROR = array();

    foreach ($ROOM_IDs AS $ROOM_ID) {

      for ($t=0; $t < (int)$_SESSION['AVAILABILITY']["RES_NIGHTS"]; ++$t) {
            $DATE = addDaysToDate($FROM, $t);

            //print $ROOM_ID . " :: " . $DATE."<br>";

            $isSTOPSALE = isset($STOPSALE[$ROOM_ID][$DATE]) ? true : false;
            $isCLOSED = isset($BLACKOUT[$ROOM_ID][$DATE]) ? true : false;
            $SOLD = isset($INVENTORY[$ROOM_ID][$DATE]) ? $INVENTORY[$ROOM_ID][$DATE] : 0;
            $PLUSQTY = isset($OVERRIDE[$ROOM_ID][$DATE]) ? $OVERRIDE[$ROOM_ID][$DATE] : 0;
            $LEFT = isset($MAX_ROOMS[$ROOM_ID]) ? ($MAX_ROOMS[$ROOM_ID] + $PLUSQTY) - $SOLD : 0;
            $isSOLD = ($LEFT<=0) ? true : false;
            
            if ($isCLOSED || $isSOLD || $isSTOPSALE || $LEFT <= 0) {
                $isOk = false;
                $ROOMS_ERROR[] = $_SESSION['AVAILABILITY']['RES_ITEMS'][$ROOM_ID]['NAME_EN'];
            }
      }

    }
    /*
    $output .= "
      ".rand()."

      isCLOSED : $isCLOSED
      isSOLD : $isSOLD
      isSTOPSALE : $isSTOPSALE
      LEFT : $LEFT
      
    ";
    file_put_contents($_SERVER["DOCUMENT_ROOT"]."/ibe/inventory.txt",$output);
    */
    if (count($ROOMS_ERROR) > 0) {
        $err_msg = implode(", ",array_unique($ROOMS_ERROR)). "  is no longer available for the dates in your search, please try searching for a different date or booking another room.";
        if ($isWEBSERVICE) {
          array_push($err,$err_msg);
        } else {
          print '<p class="s_notice top_msg"><b>'.$err_msg.'</b></p>';
          //print $output;
        }
    }

}

if ($isOk) {
    /*
     * IF REBOOKING
     */
    if (!isset($_SESSION['AVAILABILITY']['RESERVATION']['DONE']['REBOKKING'])) {
        $_SESSION['AVAILABILITY']['RESERVATION']['DONE']['REBOKKING'] = 1;

        if (isset($_SESSION['AVAILABILITY']['RES_REBOOKING']) && trim($_SESSION['AVAILABILITY']['RES_REBOOKING']['RES_NUM'])!="") {
            $REBOOKING_RES_TABLE = "RESERVATIONS_{$_SESSION['AVAILABILITY']['RES_REBOOKING']['RES_CODE']}_{$_SESSION['AVAILABILITY']['RES_REBOOKING']['RES_YEAR']}";

            /*
             * UPDATE REBOOKED JSON
             */ 
            $getargs = array (
                "ID"=>$_SESSION['AVAILABILITY']['RES_REBOOKING']['RES_ID'],
                "FIELDS"=>"ROOMS, ARRAY, NAVISION_RESULT",
                "RES_TABLE"=>$REBOOKING_RES_TABLE
            );
            $RSET = $clsReserv->getReservationById($db, $getargs);
            $row = $db->fetch_array($RSET['rSet']);
            $ARRAY = $row['ARRAY'];
            $NAVISION_RESULT = $row['NAVISION_RESULT'];
            $JSON = $clsGlobal->jsonDecode($ARRAY);
            $SAME_ROOMS_QTY = (int)$row['ROOMS'] == (int)$_SESSION['AVAILABILITY']['RES_ROOMS_QTY'];
            $SAME_PROPERTY = (int)$_SESSION['AVAILABILITY']['RES_REBOOKING']['PROP_ID'] == (int)$_SESSION['AVAILABILITY']['RES_PROP_ID'];

            if (isset($_SESSION['AVAILABILITY']['RESERVATION']['FEES'])) {
                $FEES = $_SESSION['AVAILABILITY']['RESERVATION']['FEES'];
            } else {
                $arg = array (
                    "CHECK_IN"=>$_SESSION['AVAILABILITY']['RES_REBOOKING']['CHECK_IN'],
                    "TOTAL_CHARGE"=>$_SESSION['AVAILABILITY']['RES_REBOOKING']['TOTAL_CHARGE'],
                    "NIGHTS"=>$_SESSION['AVAILABILITY']['RES_REBOOKING']['NIGHTS']
                );
                $FEES = $clsReserv->calculateFees($db, $arg);
            }
            $JSON['RES_REBOOKING']['RES_NUM'] = $_SESSION['AVAILABILITY']['RES_REBOOKING']['RES_NUM'];
            $JSON['RESERVATION']['NOTES'] = isset($_SESSION['AVAILABILITY']['RESERVATION']['NOTES']) ? $_SESSION['AVAILABILITY']['RESERVATION']['NOTES'] : "Rebooking";
            $JSON['RESERVATION']['FEES'] = $FEES;

            $MOD_ARGS = array (
                "RES_NUM"=>$JSON['RES_REBOOKING']['RES_NUM'],
                "NOTES"=>$JSON['RESERVATION']['NOTES'],
                "FEES"=>$JSON['RESERVATION']['FEES'],
                "ARRAY"=>$clsGlobal->jsonEncode($JSON),
                "RES_TABLE"=>$REBOOKING_RES_TABLE,
                "STATUS"=>-1
            );

            /*
             * NAVISION
             */
            $ELIMIBAR = false;
            if (!$SAME_ROOMS_QTY || !$SAME_PROPERTY) {
              $ELIMIBAR = true;
              $MOD_ARGS["NAVISION_STATUS"] = 'ELIMINAR'; // In case we want to cancel the rebooked res in order to reserve again
            } else {
              $_SESSION['AVAILABILITY']['NAVISION_RESULT'] = $NAVISION_RESULT; // In case we want to use modifying instead of cancelling
              $MOD_ARGS["NAVISION_RESULT"] = $NAVISION_RESULT; // In case we want to modifying instead of cancelling
            } 

            /*
             * MARK AS REBOOKED & SAVE FEES AND NOTES
             */
            $result = $clsReserv->modifyReservation($db, $MOD_ARGS);

            /*
             * NAVISION
             */
            if ($ELIMIBAR) {
              include_once $_SERVER["DOCUMENT_ROOT"] . "/ibe/api/navision/classes.php";
              $navision = new navision_cls();
              $navision->cancel_reservation($db, $getargs);
            }

            /*
             * RESTORE ROOM INVENTORY
             */
            $TABLE = substr($REBOOKING_RES_TABLE,0,17);
            $START = substr($_SESSION['AVAILABILITY']['RES_REBOOKING']['CHECK_IN'],0,4);
            for ($YEAR=(int)$START;$YEAR<=(int)$START+2;++$YEAR) {
                $clsReserv->deleteReservationRoomInventory($db, array (
                    "ID"=>$_SESSION['AVAILABILITY']['RES_REBOOKING']['RES_ID'],
                    "RES_TABLE"=>$TABLE.$YEAR."_ROOM_INVENTORY"
                ));
            }
        }
    }
}

if ($isOk && $RESERVATION['FORWHOM']['RES_TO_WHOM'] == "TA" && !isset($_SESSION['AVAILABILITY']['RESERVATION']['DONE']['TA'])) {
    /*
     * TA RESERVATION
     */
    if ((int)$_SESSION['AVAILABILITY']['RESERVATION']['FORWHOM']['RES_NEW_TA'] == 1) {
        /*
         * SAVE NEW TRAVEL AGENT
         */
        if ((int)$_SESSION['AVAILABILITY']['RESERVATION']['FORWHOM']['RES_TA_ID']==0) {
            $_SESSION['AVAILABILITY']['RESERVATION']['FORWHOM']['RES_TA_ID'] = dbNextId($db);
        }
        $RESERVATION['FORWHOM']['TA']['ID'] = $_SESSION['AVAILABILITY']['RESERVATION']['FORWHOM']['RES_TA_ID'];
        $RESERVATION['FORWHOM']['TA']['IS_ACTIVE'] = "1";
        $RESERVATION['FORWHOM']['TA']['IS_CONFIRMED'] = "1";
        $retTA = $clsTA->create($db, $RESERVATION['FORWHOM']['TA']);
        if (is_array($retTA)) {
            $isOk = false;
            if ($isWEBSERVICE) array_push($err,"Travel Agent Email already in use");
            include_once "m.reserv.make.ta.askemail.php";
        } else if ((int)$retTA!=1) {
            $isOk = false;
            array_push($err, "Error creating new Travel Agent.");
            array_push($errMsg, $retTA);
        } else {
            $_SESSION['AVAILABILITY']['RESERVATION']['DONE']['TA'] = 1;
        }
    }
}

if ($isOk) {
    if (!isset($_SESSION['AVAILABILITY']['RESERVATION']['DONE']['GUEST'])) {
        if ((int)$_SESSION['AVAILABILITY']['RESERVATION']['FORWHOM']['RES_NEW_GUEST'] == 1) {
            /*
             * SAVE NEW GUEST
             */
            if ((int)$_SESSION['AVAILABILITY']['RESERVATION']['FORWHOM']['RES_GUEST_ID']==0) {
                $_SESSION['AVAILABILITY']['RESERVATION']['FORWHOM']['RES_GUEST_ID'] = dbNextId($db);
            }
            $RESERVATION['GUEST']['ID'] = $_SESSION['AVAILABILITY']['RESERVATION']['FORWHOM']['RES_GUEST_ID'];
            $RESERVATION['GUEST']['IS_ACTIVE'] = "1";
            $RESERVATION['GUEST']['IS_CONFIRMED'] = "1";
            $RESERVATION['GUEST']['OWNER_ID'] = ((int)$_SESSION['AVAILABILITY']['RESERVATION']['FORWHOM']['RES_TA_ID']==0) ? $RESERVATION['GUEST']['ID'] : $_SESSION['AVAILABILITY']['RESERVATION']['FORWHOM']['RES_TA_ID'];
            $retGuest = $clsGuest->create($db, $RESERVATION['GUEST']);
            if (is_array($retGuest)) {
                $isOk = false;
                if ($isWEBSERVICE) array_push($err,'Guest Email already in use');
                include_once "m.reserv.make.guest.askemail.php";
            } else if ((int)$retGuest!=1) {
                $isOk = false;
                array_push($err, "Error creating new Guest.");
                array_push($errMsg, $retGuest);
            } else {
                $_SESSION['AVAILABILITY']['RESERVATION']['DONE']['GUEST'] = 1;
                $_SESSION['AVAILABILITY']['RESERVATION']['GUEST']['PASSWORD'] = $RESERVATION['GUEST']['PASSWORD'];
                if ($isWEBSERVICE) $_ws_result['PASSWORD'] = $RESERVATION['GUEST']['PASSWORD'];
            }
        } else {
            /*
             * UPDATE GUEST INFORMATION
             */
            $RESERVATION['GUEST']['ID'] = (int)$_SESSION['AVAILABILITY']['RESERVATION']['FORWHOM']['RES_GUEST_ID'];
            $retGuest = $clsGuest->modify($db, $RESERVATION['GUEST']);
            if (is_array($retGuest)) {
                $isOk = false;
                if ($isWEBSERVICE) array_push($err,'Guest Email already in use');
                include_once "m.reserv.make.guest.askemail.php";
            } else if ((int)$retGuest!=1) {
                $isOk = false;
                array_push($err, "Error creating new Guest.");
                array_push($errMsg, $retGuest);
            } else {
                $_SESSION['AVAILABILITY']['RESERVATION']['DONE']['GUEST'] = 1;
            }
        }
    }
}

if ($isOk) {
    /*
     * SAVE RESERVATION RECORD
     */
    if (!isset($_SESSION['AVAILABILITY']['RESERVATION']['DONE']['RESERVATION'])) {
        $_SESSION['AVAILABILITY']['NAVISION_RESULT'] = isset($NAVISION_RESULT)?$NAVISION_RESULT:"";
        $_SESSION['AVAILABILITY']['RESERVATION']['RES_TABLE'] = "RESERVATIONS_{$RES_ITEMS['PROPERTY']['CODE']}_".date("Y");
        $saveRes = $clsReserv->setReservation($db, $_SESSION['AVAILABILITY']);
        if ((int)$saveRes != 1) {
            $isOk = false;
            array_push($err, "Error saving reservation.");
            array_push($errMsg, $saveRes);
        } else {
            $_SESSION['AVAILABILITY']['RESERVATION']['DONE']['RESERVATION'] = 1;
            $isDone = true;
        }
        if ($isWEBSERVICE) {
            $_ws_result['RES_YEAR'] = str_replace("RESERVATIONS_","",$_SESSION['AVAILABILITY']['RESERVATION']['RES_TABLE']);
        }
    }
}

if ($isOk) {
    /*
     * SAVE ROOM OPTIONALS
     */
    if (!isset($_SESSION['AVAILABILITY']['RESERVATION']['DONE']['OPTIONALS'])) {
        $ROOM_RES_INFO = array(
            "RES_TABLE" => $_SESSION['AVAILABILITY']['RESERVATION']['RES_TABLE']."_ROOM_OPTS",
            "RES_ID"    => $_SESSION['AVAILABILITY']['RESERVATION']['RES_ID'],
            "RES_NUM"   => $_SESSION['AVAILABILITY']['RESERVATION']['RES_NUMBER']
        );
        foreach ($RESERVATION['ROOMS'] as $ROOM_KEY => $ROOM_DATA) {
            $ROOM_DATA["ROOM_KEY"] = $ROOM_KEY; //dbNextId($db);
            $ROOM_DATA["ROOM_ID"]  = $RESERVATION['RES_ROOMS_SELECTED'][$ROOM_KEY];
            $ROOM_DATA["ROOM_CHARGE"] = $RESERVATION['RES_ROOM_CHARGE'][$ROOM_KEY];
            $saveRoom = $clsReserv->saveReservationRoomOpts($db, array_merge($ROOM_RES_INFO,$ROOM_DATA));
            if ((int)$saveRoom != 1) {
                $isOk = false;
                array_push($err, "Error saving room optionals.");
                array_push($errMsg, $saveRoom);
            }
        }
        $_SESSION['AVAILABILITY']['RESERVATION']['DONE']['OPTIONALS'] = 1;
    }

    /*
     * SAVE ROOM INVENTORY
     */ 
    if (!isset($_SESSION['AVAILABILITY']['RESERVATION']['DONE']['INVENTORY'])) {
        $ROOM_RES_INFO["RES_TABLE"] = $_SESSION['AVAILABILITY']['RESERVATION']['RES_TABLE']."_ROOM_INVENTORY";
        $TABLE = substr($ROOM_RES_INFO["RES_TABLE"],0,17);
        for ($t=0; $t < (int)$RES_NIGHTS; ++$t) {
            $ROOM_RES_INFO["RES_DATE"] = addDaysToDate($RES_CHECK_IN, $t);
            foreach ($RESERVATION['ROOMS'] as $ROOM_KEY => $ROOM_DATA) {

                $ROOM_DATA["ID"] = dbNextId($db);
                $ROOM_DATA["ROOM_ID"]  = $RESERVATION['RES_ROOMS_SELECTED'][$ROOM_KEY];
                $ROOM_RES_INFO["RES_TABLE"] = $TABLE.((int)substr($ROOM_RES_INFO["RES_DATE"],0,4))."_ROOM_INVENTORY";

                $saveInventory = $clsReserv->saveReservationRoomInventory($db, array_merge($ROOM_RES_INFO,$ROOM_DATA));
                if ((int)$saveInventory != 1) {
                    $isOk = false;
                    array_push($err, "Error saving room inventory.");
                    array_push($errMsg, $saveInventory);
                }
            }
        }
        $_SESSION['AVAILABILITY']['RESERVATION']['DONE']['INVENTORY'] = 1;
    }
}

$RESULT = ob_get_clean();

if (!$isWEBSERVICE) print $RESULT;

?>
