<?
/*
 * Revised: Aug 11, 2011
 */

class seasons {

    var $showQry = false;

    function save($db, $arg) {
        extract($arg);

        if ((int)$SEASON_ID!=0) {
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
     
        $query = "SELECT * FROM SEASONS WHERE ID='{$SEASON_ID}'";
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        return $result;
    }

    function addNew($db, $arg) {
        extract($arg);

        $query = "INSERT INTO SEASONS ( ID, UPDATED_BY ) VALUES ( '{$SEASON_ID}', '{$_SESSION['AUTHENTICATION']['ID']}' )";
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
        if (isset($NAME)) array_push($arr," YEAR = '$YEAR'");
        if (isset($FROM)) array_push($arr," `FROM` = '{$FROM} 00:00:00'");
        if (isset($TO)) array_push($arr," `TO` = '{$TO} 23:59:59'");
        if (isset($SUPL_SINGLE)) array_push($arr," SUPL_SINGLE = '$SUPL_SINGLE'");
        if (isset($SUPL_TRIPLE)) array_push($arr," SUPL_TRIPLE = '$SUPL_TRIPLE'");
        if (isset($SUPL_TYPE)) array_push($arr," SUPL_TYPE = '$SUPL_TYPE'");

        $query = "UPDATE SEASONS SET ".join(", ",$arr)." WHERE ID='$SEASON_ID'";
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
     
        $WHERE = (isset($YEAR)) ? " AND YEAR='{$YEAR}'" : "";

        $query = "SELECT * FROM SEASONS WHERE PROP_ID='{$PROP_ID}' $WHERE ORDER BY `YEAR` DESC, `FROM` DESC,`TO` DESC";
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        return $result;
    }

    function deleteById($db, $arg) {
        extract($arg);
        $query = "DELETE FROM SEASONS WHERE ID = '$ID'";
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbExecute($db, $arg);
        return $result;
    }

    function remove($db, $arg) {
        extract($arg);
        global $clsUploads;
        global $clsClasses;

        // Search the season in Classes. If not in use then allow removing
        $result = $clsClasses->getClassSeasonBySeasonId($db, array("SEASON_ID"=>$DELETE_ID));
        if ( $result['iCount'] != 0 ) {
            $result = -1;
        } else {
            $result = $this->deleteById($db, array("ID"=>$DELETE_ID));
        }
        return $result;
    }
}
global $clsSeasons;
$clsSeasons = new seasons;
?>