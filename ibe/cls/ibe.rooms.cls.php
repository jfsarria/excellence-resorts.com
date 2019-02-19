<?
/*
 * Revised: Apr 23, 2016
 *          Jun 09, 2016
 *          Oct 04, 2016
 */

class rooms {

    var $showQry = false;

    function save($db, $arg) {
        extract($arg);

        if ((int)$ROOM_ID!=0) {
            $result = $this->getById($db, $arg);
        } else $result['iCount'] = 0;

        if ( $result['iCount'] == 0 ) {
            $result = $this->addNew($db, $arg);
        } else {
            $result = $this->modify($db, $arg);
        }

        return $result;
    }

    function getById($db, $arg) {
        extract($arg);
     
        $query = "SELECT * FROM ROOMS WHERE ID='{$ROOM_ID}'";
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        return $result;
    }

    function addNew($db, $arg) {
        extract($arg);

        $query = "INSERT INTO ROOMS ( ID, UPDATED_BY ) VALUES ( '{$ROOM_ID}', '{$_SESSION['AUTHENTICATION']['ID']}' )";
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";

        $arr = array('query' => $query);
        $result = dbExecute($db, $arr);

        return $this->modify($db, $arg);
    }


    function modify($db, $arg) {
        extract($arg);
        global $clsUploads;
        $arr = array();

        //print "<pre>";print_r($arg);print "</pre>";

        if (isset($PROP_ID)) array_push($arr," PROP_ID = '$PROP_ID'");
        if (isset($NAME_EN)) array_push($arr," NAME_EN = '$NAME_EN'");
        if (isset($NAME_SP)) array_push($arr," NAME_SP = '$NAME_SP'");
        if (isset($DESCR_EN)) array_push($arr," DESCR_EN = '$DESCR_EN'");
        if (isset($DESCR_SP)) array_push($arr," DESCR_SP = '$DESCR_SP'");
        if (isset($INCLU_EN)) array_push($arr," INCLU_EN = '$INCLU_EN'");
        if (isset($INCLU_SP)) array_push($arr," INCLU_SP = '$INCLU_SP'");
        if (isset($TA_AMENITIES_EN)) array_push($arr," TA_AMENITIES_EN = '$TA_AMENITIES_EN'");
        if (isset($TA_VIEWTYPE_EN)) array_push($arr," TA_VIEWTYPE_EN = '$TA_VIEWTYPE_EN'");
        if (isset($TA_ACCESSIBILITY_EN)) array_push($arr," TA_ACCESSIBILITY_EN = '$TA_ACCESSIBILITY_EN'");
        if (isset($CLAVE)) array_push($arr," CLAVE = '$CLAVE'");
        if (isset($ORDER)) array_push($arr," `ORDER` = '$ORDER'");
        if (isset($MAX_OCUP)) array_push($arr," MAX_OCUP = '$MAX_OCUP'");
        if (isset($MAX_ADUL)) array_push($arr," MAX_ADUL = '$MAX_ADUL'");
        if (isset($MAX_CHIL)) array_push($arr," MAX_CHIL = '$MAX_CHIL'");
        if (isset($MAX_ROOMS)) array_push($arr," MAX_ROOMS = '$MAX_ROOMS'");

        /* CHECKBOXES */
        array_push($arr," IS_VIP = '".(isset($IS_VIP)?$IS_VIP:"0")."'");
        array_push($arr," IS_ACTIVE = '".(isset($IS_ACTIVE)?$IS_ACTIVE:"0")."'");
        array_push($arr," IS_ARCHIVE = '".(isset($IS_ARCHIVE)?$IS_ARCHIVE:"0")."'");
        array_push($arr," BEDS = '".(isset($BEDS)?implode(",", $BEDS):"0")."'");

        $query = "UPDATE ROOMS SET ".join(", ",$arr)." WHERE ID='$ROOM_ID'";
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";

        $arr = array('query' => $query);
        $result = dbExecute($db, $arr);

        if ((int)$result != 1) { 
            print "<p class='s_missing top_msg'><b>Debug Data:</b><br><br>\n\n$query\n\n</p>";
        } else {
            if (isset($DELETE_UPS)) {
                $clsUploads->deleteByIds($db, $arg);
            }
            if (isset($ROOM_IMAGES_ORDER_CURRENT)&&isset($ROOM_IMAGES_ORDER)&&$ROOM_IMAGES_ORDER_CURRENT!=$ROOM_IMAGES_ORDER) {
                $arg['IMAGES_ORDER'] = $ROOM_IMAGES_ORDER;
                $clsUploads->saveOrder($db, $arg);
            }
        }

        return $result;
    }

    function getByProperty($db, $arg) {
        extract($arg);
        global $_IBE_LANG;
            
        $WEHRE = isset($WEHRE) ? $WEHRE : "";
     
        $query = "SELECT * FROM ROOMS WHERE PROP_ID='{$PROP_ID}' {$WEHRE} ORDER BY `ORDER`, NAME_{$_IBE_LANG} ";
        $arg = array('query' => $query);
        //print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        return $result;
    }

    function getBedOptions($db, $arg) {
        extract($arg);
     
        $query = "SELECT * FROM BEDS WHERE PROP_ID='{$PROP_ID}'";
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        return $result;
    }

    function getBedTypesDropDown($db, $arg) {
        extract($arg);
        $ELE_ID = isset($ELE_ID) ? $ELE_ID : "BED_TYPE";
        $RSET = $this->getBedOptions($db, array("PROP_ID"=>$PROP_ID));
        $result = "<select name='$ELE_ID' id='$ELE_ID'><option value=''>No preferences</option>";
        while ($brow = $db->fetch_array($RSET['rSet'])) {
            $result .= "<option value='".$brow['ID']."'>".$brow['NAME']."</option>";
        }
        $result .= "</select>";
        return $result;
    }

    function remove($db, $arg) {
        extract($arg);
        global $clsUploads;
        global $clsClasses;

        // Search the room in Classes. If not in use then allow removing
        $result = $clsClasses->getByKey($db, array("WHERE"=>"ROOM_ID = '{$DELETE_ID}'"));
        if ( $result['iCount'] != 0 ) {
            $result = -1;
        } else {
            $DELETE_UPS = array();
            $RSET = $clsUploads->getByParent($db, array("PARENT_ID"=>$DELETE_ID));
            while ($row = $db->fetch_array($RSET['rSet'])) array_push($DELETE_UPS,$row['ID']);
            if (count($DELETE_UPS)!=0) $clsUploads->deleteByIds($db, array("DELETE_UPS"=>$DELETE_UPS,"UPS_FOLDER"=>"/ibe/ups/rooms/"));
            $result = dbExecute($db, array('query' => "DELETE FROM ROOMS WHERE ID='{$DELETE_ID}'"));
        }

        return $result;
    }

    /* ************************** */

    function saveStopSale($db, $arg) {
        extract($arg);
        //print "<pre>";print_r($arg);print "</pre>";

        if ((int)$ID!=0) {
            $result = $this->getStopSaleById($db, $arg);
        } else $result['iCount'] = 0;

        if ( $result['iCount'] == 0 ) {
            $result = $this->addNewStopSale($db, $arg);
        } else {
            $result = $this->modifyStopSale($db, $arg);
        }

        return $result;
    }

    function getStopSaleById($db, $arg) {
        extract($arg);
     
        $query = "SELECT * FROM STOPSALE WHERE ID='{$ID}'";
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        return $result;
    }

    function addNewStopSale($db, $arg) {
        extract($arg);

        $query = "INSERT INTO STOPSALE ( ID ) VALUES ( '{$ID}' )";
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";

        $arr = array('query' => $query);
        $result = dbExecute($db, $arr);

        return $this->modifyStopSale($db, $arg);
    }


    function modifyStopSale($db, $arg) {
        extract($arg);
        global $clsUploads;
        $arr = array();

        //print "<pre>";print_r($arg);print "</pre>";

        if (isset($PROP_ID)) array_push($arr," PROP_ID = '$PROP_ID'");
        if (isset($NAME)) array_push($arr," NAME = '$NAME'");
        if (isset($YEAR)) array_push($arr," YEAR = '$YEAR'");
        if (isset($FROM)) array_push($arr," `FROM` = '$FROM'");
        if (isset($TO)) array_push($arr," `TO` = '$TO'");

        /* CHECKBOXES */
        array_push($arr," IS_ACTIVE = '".(isset($IS_ACTIVE)?$IS_ACTIVE:"0")."'");
        array_push($arr," IS_ARCHIVE = '".(isset($IS_ARCHIVE)?$IS_ARCHIVE:"0")."'");

        $query = "UPDATE STOPSALE SET ".join(", ",$arr)." WHERE ID='$ID'";
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";

        $arr = array('query' => $query);
        $result = dbExecute($db, $arr);

        if ((int)$result != 1) { 
            print "<p class='s_missing top_msg'><b>Debug Data:</b><br><br>\n\n$query\n\n</p>";
        } else {
            // Save RelationShips
            $this->saveStopSaleGeos($db, $arg);
            $this->saveStopSaleRooms($db, $arg);
        }

        return $result;
    }

    function getStopSaleByProperty($db, $arg) {
        extract($arg);

        $WHERE = (isset($YEAR)) ? " AND YEAR='{$YEAR}'" : "";
        $WHERE .= (isset($_IS_ARCHIVE)) ? " AND IS_ARCHIVE='{$_IS_ARCHIVE}'" : "";

        $query = "SELECT * FROM STOPSALE WHERE PROP_ID='{$PROP_ID}' $WHERE ORDER BY `YEAR` DESC, `FROM` DESC,`TO` DESC";
        $arg = array('query' => $query);
        //print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        return $result;
    }

    function deleteStopSaleById($db, $arg) {
        extract($arg);
        $query = "DELETE FROM STOPSALE WHERE ID = '$ID'";
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbExecute($db, $arg);
        return $result;
    }

    function removeStopSale($db, $arg) {
        extract($arg);
        global $clsUploads;
        global $clsClasses;

        $result = $this->deleteStopSaleById($db, array("ID"=>$DELETE_ID));
        // REMOVE RELATIONSHIP AS WELL
        $this->deleteStopSaleGeos($db, array("ID"=>$DELETE_ID));
        $this->deleteStopSaleRooms($db, array("ID"=>$DELETE_ID));
        return $result;
    }

    function saveStopSaleGeos($db, $vars) {
        extract($vars);

        $query = "DELETE FROM STOPSALE_GEO WHERE STOPSALE_ID = '$ID'";
        //print "<p class='s_notice top_msg'>$query</p>";
        $arg = array('query' => $query);
        $result = dbExecute($db, $arg);

        if (isset($GEOS) && is_array($GEOS)) {
            foreach ($GEOS as $GEO) {
                $query = "INSERT INTO STOPSALE_GEO ( STOPSALE_ID, GEO_GROUP ) VALUES ( '{$ID}', '{$GEO}' );";
                //print "<p class='s_notice top_msg'>$query</p>";
                $arg = array('query' => $query);
                $result = dbExecute($db, $arg);
            }
        }
        return 1;
    }

    function saveStopSaleRooms($db, $vars) {
        extract($vars);

        $query = "DELETE FROM ROOM_STOPSALE WHERE STOPSALE_ID = '$ID'";
        //print "<p class='s_notice top_msg'>$query</p>";
        $arg = array('query' => $query);
        $result = dbExecute($db, $arg);

        if (isset($ROOM_IDs) && is_array($ROOM_IDs)) {
            foreach ($ROOM_IDs as $ROOM_ID) {
                $query = "INSERT INTO ROOM_STOPSALE ( STOPSALE_ID, ROOM_ID ) VALUES ( '{$ID}', '{$ROOM_ID}' );";
                //print "<p class='s_notice top_msg'>$query</p>";
                $arg = array('query' => $query);
                $result = dbExecute($db, $arg);
            }
        }

        return 1;
    }

    function getStopSaleGeos($db, $arg) {
        extract($arg);
        
        $query = "SELECT * FROM STOPSALE_GEO WHERE STOPSALE_ID='{$ID}'";
        $arg = array('query' => $query);
        //print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        return $result;
    }

    function getStopSaleRooms($db, $arg) {
        extract($arg);
        
        $query = "SELECT * FROM ROOM_STOPSALE WHERE STOPSALE_ID='{$ID}'";
        $arg = array('query' => $query);
        //print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        return $result;
    }

    function deleteStopSaleGeos($db, $arg) {
        extract($arg);
        $query = "DELETE FROM STOPSALE_GEO WHERE STOPSALE_ID = '$ID'";
        $arg = array('query' => $query);
        //print "<p class='s_notice top_msg'>$query</p>";
        $result = dbExecute($db, $arg);
        return $result;
    }

    function deleteStopSaleRooms($db, $arg) {
        extract($arg);
        $query = "DELETE FROM ROOM_STOPSALE WHERE STOPSALE_ID = '$ID'";
        $arg = array('query' => $query);
        //print "<p class='s_notice top_msg'>$query</p>";
        $result = dbExecute($db, $arg);
        return $result;
    }

}
global $clsRooms;
$clsRooms = new rooms;
?>