<?
/*
 * Revised: Jun 23, 2011
 *          Jul 07, 2014
 */

class inventory {

    var $showQry = false;

    function getInventory($db, $arg) {
        global $clsReserv;
        extract($arg);
        //print "arg: <pre>";print_r($arg);print "</pre>";
        $IDs = array();
        foreach($ROOM_IDs as $ind=>$ROOM_ID) { array_push($IDs," ROOM_ID='{$ROOM_ID}' "); }
        $ROOMS = implode(" OR ",$IDs);
        $YEARS = (!isset($YEARS)) ? array($YEAR) : array_unique($YEARS);
        $qry = array();
        foreach($YEARS as $IND=>$YEAR) {
            $result = $clsReserv->createReservationRoomInventoryTable($db, array("TABLENAME"=>"RESERVATIONS_{$CODE}_{$YEAR}_ROOM_INVENTORY"));
            if ((int)$result == 1) {
                array_push($qry, "SELECT * FROM V_{$CODE}_{$YEAR}_ROOMS_SOLD WHERE ({$ROOMS}) AND (RES_DATE >= '{$FROM} 00:00:00' AND RES_DATE <= '{$TO} 23:59:59') ");
            }
        }
        $query = implode(" UNION ",$qry);
        $arg = array('query' => $query);
        //print "<p class='s_notice top_msg'>$query</p>";
        $result = !empty($query) ? dbQuery($db, $arg) : "";
        return $result;
    }    

    function getRooms($db, $arg) {
        extract($arg);
        $IDs = array();
        foreach($ROOM_IDs as $ind=>$ROOM_ID) { array_push($IDs," ID='{$ROOM_ID}' "); }
        $ROOMS = implode(" OR ",$IDs);
        
        $query = "SELECT ID,NAME_EN,NAME_SP,MAX_ROOMS FROM ROOMS WHERE ({$ROOMS}) ORDER BY `ORDER` ";
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        return $result;
    }    

    function getAddAllocation($db, $arg) {
        extract($arg);
        $IDs = array();
        foreach($ROOM_IDs as $ind=>$ROOM_ID) { array_push($IDs," ROOM_ID='{$ROOM_ID}' "); }
        $ROOMS = implode(" OR ",$IDs);
        
        $query = "SELECT * FROM ROOM_ALLOCATION WHERE ({$ROOMS}) ";
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        return $result;
    }  

    function getBlackOut($db, $arg) {
        extract($arg);
        $IDs = array();
        foreach($ROOM_IDs as $ind=>$ROOM_ID) { array_push($IDs," ROOM_ID='{$ROOM_ID}' "); }
        $ROOMS = implode(" OR ",$IDs);
        
        $query = "SELECT * FROM ROOM_BLACKOUT WHERE ({$ROOMS}) ";
        //ob_start();print_r($arg);$output = ob_get_clean();
        //mail("jaunsarria@gmail.com","query","$query $output");

        $arg = array('query' => $query);
        
        $result = dbQuery($db, $arg);
        return $result;
    } 


    function getStopSale($db, $arg, $_DATA) {
        extract($arg);
        $IDs = array();
        foreach($ROOM_IDs as $ind=>$ROOM_ID) { array_push($IDs," ROOM_ID='{$ROOM_ID}' "); }
        $ROOMS = implode(" OR ",$IDs);

        $query = "
            SELECT * FROM V_STOPSALE
            WHERE IS_ACTIVE = 1 AND ({$ROOMS})
            AND (GEO_GROUP = '{$_DATA['RES_COUNTRY_CODE']}' OR GEO_GROUP = '{$_DATA['RES_COUNTRY_GROUP']}')
            AND ('{$_DATA['RES_CHECK_IN']} 00:00:00' <= `TO` AND '{$_DATA['RES_CHECK_OUT']} 00:00:00' >= `FROM`)
        ";

        //ob_start();print_r($_DATA);$output = ob_get_clean();
        //mail("jaunsarria@gmail.com","getStopSale","$query $output");

        $arg = array('query' => $query);
        
        $result = dbQuery($db, $arg);
        return $result;
    }
    
    function makeStopSale($db, $arg, $_DATA) {
        extract($arg);
        $STOPSALE = array();
        $S_RSET = $this->getStopSale($db, $arg, $_DATA);
        while ($row = $db->fetch_array($S_RSET['rSet'])) {
            $FROM = substr($row['FROM'],0,10);$int_FROM = (int)str_replace("-","",$FROM);
            $TO = substr($row['TO'],0,10);$int_TO = (int)str_replace("-","",$TO);
            $DAYS = ($int_TO - $int_FROM) + 1;
            for ($t=0; $t < $DAYS; ++$t) {
                $THIS_DAY = addDaysToDate($FROM, $t);
                $STOPSALE[$row['ROOM_ID']][$THIS_DAY] = $DAYS;
            }
        }
        return $STOPSALE;
    }

    function saveAllocation($db, $arg) {
        extract($arg);

        if ((int)$ROOM_ID!=0) {
            $result = $this->getAllocationByDate($db, $arg);
        } else $result['iCount'] = 0;

        if ( $result['iCount'] == 0 ) {
            $result = $this->addNewAllocation($db, $arg);
        } else {
            $result = $this->modifyAllocation($db, $arg);
        }
        
        $this->removeBlockOut($db, $arg);
        if ($STATUS=="close") $this->setBlockOut($db, $arg);

        return $result;
    }

    function getAllocationByDate($db, $arg) {
        extract($arg);
     
        $query = "SELECT * FROM ROOM_ALLOCATION WHERE ROOM_ID='{$ROOM_ID}' AND RES_DATE='{$RES_DATE}'";
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        return $result;
    }

    function addNewAllocation($db, $arg) {
        extract($arg);

        $query = "INSERT INTO ROOM_ALLOCATION ( ID, RES_DATE, ROOM_ID, QTY ) VALUES ( '".dbNextId($db)."', '{$RES_DATE}', '{$ROOM_ID}', '{$QTY}' )";
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";

        $arr = array('query' => $query);
        $result = dbExecute($db, $arr);

        return $result;
    }

    function modifyAllocation($db, $arg) {
        extract($arg);
        $arr = array();

        $query = "UPDATE ROOM_ALLOCATION SET QTY='$QTY' WHERE ROOM_ID='$ROOM_ID' AND RES_DATE='$RES_DATE' ";
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";

        $arr = array('query' => $query);
        $result = dbExecute($db, $arr);

        if ((int)$result != 1) { 
            print "<p class='s_missing top_msg'><b>Debug Data:</b><br><br>\n\n$query\n\n</p>";
        }

        return $result;
    }

    function removeBlockOut($db, $arg) {
        extract($arg);

        $query = "DELETE FROM ROOM_BLACKOUT WHERE ROOM_ID='$ROOM_ID' AND DATE_CLOSED='$RES_DATE' ";
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";

        $arr = array('query' => $query);
        $result = dbExecute($db, $arr);

        return $result;
    }

    function setBlockOut($db, $arg) {
        extract($arg);

        $query = "INSERT INTO ROOM_BLACKOUT ( ROOM_ID, DATE_CLOSED ) VALUES ( '{$ROOM_ID}', '{$RES_DATE}' )";
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";

        $arr = array('query' => $query);
        $result = dbExecute($db, $arr);

        return $result;
    }

    function saveEmailMin($db, $arg) {
        extract($arg);
        $arr = array();

        $query = "UPDATE PROPERTIES SET INVENTORY_EMAIL='$INVENTORY_EMAIL', INVENTORY_MIN='$INVENTORY_MIN' WHERE ID='{$PROP_ID}' ";
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";

        $arr = array('query' => $query);
        $result = dbExecute($db, $arr);

        if ((int)$result != 1) { 
            print "<p class='s_missing top_msg'><b>Debug Data:</b><br><br>\n\n$query\n\n</p>";
        }

        return $result;
    }
}
global $clsInventory;
$clsInventory = new inventory;
?>