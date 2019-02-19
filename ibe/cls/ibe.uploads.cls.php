<?
/*
 * Revised: Aug 01, 2011
 */

class uploads {

    var $showQry = false;

    function save($db, $arg) {
        extract($arg);

        if ($NAME!="") {
            $result = $this->getByName($db, $arg);
        } else $result['iCount'] = 0;

        if ( $result['iCount'] == 0 ) {
            $result = $this->addNew($db, $arg);
        } else {
            //$result = $this->modify($db, $arg);
        }

        return $result;
    }

    function getById($db, $arg) {
        extract($arg);
     
        $query = "SELECT * FROM UPLOADS WHERE ID='{$ID}'";
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        return $result;
    }

    function getByName($db, $arg) {
        extract($arg);
     
        $query = "SELECT * FROM UPLOADS WHERE NAME='{$NAME}'";
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        return $result;
    }

    function addNew($db, $arg) {
        extract($arg);

        $query = "INSERT INTO UPLOADS ( ID ) VALUES ( '{$ID}' )";
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";

        $arr = array('query' => $query);
        $result = dbExecute($db, $arr);

        return $this->modify($db, $arg);
    }


    function modify($db, $arg) {
        extract($arg);
        $arr = array();

        //print "<pre>";print_r($arg);print "</pre>";

        if (isset($PARENT_ID)) array_push($arr," PARENT_ID = '$PARENT_ID'");
        if (isset($NAME)) array_push($arr," NAME = '$NAME'");
        if (isset($TYPE)) array_push($arr," TYPE = '$TYPE'");
        if (isset($ORDER)) array_push($arr," `ORDER` = '$ORDER'");

        $query = "UPDATE UPLOADS SET ".join(", ",$arr)." WHERE ID='$ID'";
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";

        $arr = array('query' => $query);
        $result = dbExecute($db, $arr);

        if ((int)$result != 1) print "<p class='s_missing top_msg'><b>Debug Data:</b><br><br>\n\n$query\n\n</p>";

        return $result;
    }

    function getByParent($db, $arg) {
        extract($arg);
     
        $query = "SELECT * FROM UPLOADS WHERE PARENT_ID='{$PARENT_ID}'";
        if (isset($TYPE)) $query .= " AND TYPE='{$TYPE}'";
        $query .= " ORDER BY `ORDER`,`NAME`";
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        return $result;
    }

    function deleteById($db, $arg) {
        extract($arg);
//                    print "[2<pre>";print_r($arg);print "</pre>]";

        $UPS_FOLDER = isset($UPS_FOLDER) ? $UPS_FOLDER : "/ups/";
        $RSET = $this->getById($db, $arg);
        if ($RSET['iCount'] != 0 ) {
            $row = $db->fetch_array($RSET['rSet']);
            $LOCATION = $_SERVER["DOCUMENT_ROOT"].$UPS_FOLDER;
            if ($this->showQry) print "<p class='s_notice top_msg'>unlink $LOCATION{$row['NAME']}</p>";

            if (file_exists($LOCATION.$row['NAME'])) unlink($LOCATION.$row['NAME']);
            if (file_exists($LOCATION."T_".$row['NAME'])) unlink($LOCATION."T_".$row['NAME']);

            $query = "DELETE FROM UPLOADS WHERE ID='{$ID}'";
            $arr = array('query' => $query);
            if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
            $result = dbExecute($db, $arr);
        }
    }

    function deleteByIds($db, $arg) {
        extract($arg);
        $arr = (isset($DELETE_UPS)) ? $DELETE_UPS : array();
        foreach ($arr as $ID) {
            $this->deleteById($db, array("ID"=>$ID,"UPS_FOLDER"=>$UPS_FOLDER));
        }
    }

    function saveOrder($db, $arg) {
        extract($arg);
        $arr = explode(",", $IMAGES_ORDER);
        if (!isset($DELETE_UPS)) $DELETE_UPS = array();
        $ORDER = 0;
        foreach ($arr as $ID) {
            if (!in_array($ID,$DELETE_UPS)) $this->modify($db, array("ID"=>$ID,"ORDER"=>++$ORDER));
        }
    }
}
global $clsUploads;
$clsUploads = new uploads;
?>