<?
/*
 * Revised: Dec 01, 2011
 */

class childrenrate {

    var $showQry = false;

    function save($db, $arg) {
        extract($arg);

        if ((int)$CHILDRATE_ID!=0) {
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
     
        $query = "SELECT * FROM CHILDREN_RATES WHERE ID='{$CHILDRATE_ID}'";
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        return $result;
    }

    function addNew($db, $arg) {
        extract($arg);

        $query = "INSERT INTO CHILDREN_RATES ( ID, UPDATED_BY ) VALUES ( '{$CHILDRATE_ID}', '{$_SESSION['AUTHENTICATION']['ID']}' )";
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
        if (isset($NAME)) array_push($arr," NAME = '$NAME'");
        if (isset($FROM)) array_push($arr," `FROM` = '$FROM'");
        if (isset($TO)) array_push($arr," `TO` = '$TO'");
        if (isset($PERCENTAGE)) array_push($arr," PERCENTAGE = '$PERCENTAGE'");

        /* CHECKBOXES */
        array_push($arr," COUNTED = '".(isset($COUNTED)?$COUNTED:"0")."'");

        $query = "UPDATE CHILDREN_RATES SET ".join(", ",$arr)." WHERE ID='$CHILDRATE_ID'";
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";

        $arr = array('query' => $query);
        $result = dbExecute($db, $arr);

        if ((int)$result != 1) { 
            print "<p class='s_missing top_msg'><b>Debug Data:</b><br><br>\n\n$query\n\n</p>";
        } else {
            //
        }

        return $result;
    }

    function getByProperty($db, $arg) {
        extract($arg);
     
        $query = "SELECT * FROM CHILDREN_RATES WHERE PROP_ID='{$PROP_ID}' ORDER BY `FROM`,`TO` ";
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        return $result;
    }

    function getPercentage($db, $arg) {
        extract($arg);
        $percentage = 100;
        $query = "SELECT * FROM CHILDREN_RATES WHERE PROP_ID='{$PROP_ID}' AND $AGE >= `FROM` AND $AGE <= `TO`";
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $RSET = dbQuery($db, array('query' => $query));
        if ( $RSET['iCount'] != 0 ) {
            $row = $db->fetch_array($RSET['rSet']);
            $percentage = ((int)$row['COUNTED']==1) ? (int)$row['PERCENTAGE'] : 0;
        }
        return $percentage;
    }

    function getMaxAge($db, $arg=array()) {
        extract($arg);
        $MAX_AGE = 17;
        $query = "SELECT MAX(`TO`) AS MAX_AGE FROM CHILDREN_RATES";
        if (isset($noAdults) && $noAdults) $query .= " WHERE PERCENTAGE <> 100";
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $RSET = dbQuery($db, array('query' => $query));
        if ( $RSET['iCount'] != 0 ) {
            $row = $db->fetch_array($RSET['rSet']);
            $MAX_AGE = (int)$row['MAX_AGE'];
        }
        return $MAX_AGE;
    }

}
global $clsChildrate;
$clsChildrate = new childrenrate;
?>