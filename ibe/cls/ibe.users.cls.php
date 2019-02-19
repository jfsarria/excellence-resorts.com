<?
/*
 * Revised: Feb 03, 2013
 */

class users {

    var $showQry = false;

    function getById($db, $arg) {
        extract($arg);
        $FIELDS = isset($FIELDS) ? $FIELDS : "*";

        $query = "SELECT {$FIELDS} FROM USERS WHERE ID={$ID}";
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        return $result;
    }

    function getAgentName($db, $arg) {
        extract($arg);
        $AGENT_NAME = "";
        $USET = $this->getById($db, array("ID"=>$ID,"FIELDS"=>"FIRSTNAME,LASTNAME,EMAIL"));
        if ($USET['iCount']>0) {
            $arow = $db->fetch_array($USET['rSet']);
            $AGENT_NAME = $arow['FIRSTNAME']." ".$arow['LASTNAME'];
        }
        return $AGENT_NAME;
    }

    function getByUserName($db, $arg) {
        $query = "SELECT * FROM USERS WHERE USERNAME='".$arg['USERNAME']."'";
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        return $result;
    }

    function getByRole($db, $arg) {
        $query = "SELECT * FROM USERS WHERE ROLE='".$arg['ROLE']."'";
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        return $result;
    }

    function deleteById($db, $arg) {
        $query = "DELETE FROM USERS WHERE ID=".$arg['ID'];
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbExecute($db, $arg);
        return $result;
    }

    function getAll($db, $arg=array()) {
        extract($arg);

        if (!isset($SORTBY) || $SORTBY == "") { $SORTBY = "LASTNAME, FIRSTNAME"; }

        $query = "SELECT * FROM USERS ORDER BY ".$SORTBY;
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";

        $arg = array('query' => $query);
        $result = dbQuery($db, $arg);
        return $result;
    }

    function save($db, $arg) {
        $result = $this->getById($db, $arg);

        if ( $result['iCount'] == 0 ) {
            $result = $this->addNew($db, $arg);
        } else {
            $result = $this->modify($db, $arg);
        }

        return $result;
    }

    function addNew($db, $arg) {
        extract($arg);

        $query = "
            INSERT INTO USERS (
                ID,
                USERNAME,
                PASSWORD,
                FIRSTNAME,
                LASTNAME,
                EMAIL,
                ROLE
            ) 
            VALUES 
            (
                '".$ID."',
                '".$USERNAME."',
                '".$PASSWORD."',
                '".$FIRSTNAME."',
                '".$LASTNAME."',
                '".$EMAIL."',
                '".$ROLE."'
            )
        ";

        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";

        $arr = array('query' => $query);
        $result = dbExecute($db, $arr);

        dbExecute($db, array('query' => "DELETE FROM USER_PROP WHERE USER_ID={$ID}"));
        /* DELETE THIS WHEN PROPERLY IMPLEMENTED */
        for ($t=1;$t<=10;++$t) dbExecute($db, array('query' => "INSERT INTO USER_PROP (USER_ID,PROP_ID) VALUES ('{$ID}','{$t}')"));

        return $result;
    }

    function modify($db, &$arg) {
        extract($arg);

        $query = "UPDATE USERS SET ";

        $query .= " 
                USERNAME = '".$USERNAME."',
                PASSWORD = '".$PASSWORD."',
                FIRSTNAME = '".$FIRSTNAME."',
                LASTNAME = '".$LASTNAME."',
                EMAIL = '".$EMAIL."',
                ROLE = '".$ROLE."'
            WHERE ID=".$ID."
        ";

        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $arr = array('query' => $query);
        $result = dbExecute($db, $arr);
        return $result;
    }

    function getProperties($db, $arg) {
        extract($arg);

        $query = "SELECT * FROM V_USER_PROP WHERE USER_ID='{$USER_ID}'";
        //print "<p class='s_notice top_msg'>$query</p>";
        $arg = array('query' => $query);
        $result = dbQuery($db, $arg);

        if ($asArray) {
            $arr = array();
            while ($row = $db->fetch_array($result['rSet'])) {
                $arr[$row['PROP_ID']] = array();
                $arr[$row['PROP_ID']]['NAME'] = $row['PROP_NAME'];
                $arr[$row['PROP_ID']]['CODE'] = $row['PROP_CODE'];
                $arr[$row['PROP_ID']]['DESCR_EN'] = $row['PROP_DESCR_EN'];
                $arr[$row['PROP_ID']]['DESCR_SP'] = $row['PROP_DESCR_SP'];
            }
            return $arr;
        } else {
            return $result;
        }
    }

    function propertiesDropDown($db, $arg = array()) {
        extract($arg);
        global $PROP_ID;

        $out = "";
        $ELE_ID = isset($ELE_ID) ? $ELE_ID : "PROP_ID";
        $qty = count($_SESSION['AUTHENTICATION']['PROPERTIES']);
        if ($qty!=0) {
            $out = "<select name='{$ELE_ID}' id='{$ELE_ID}'>";
            ##if ($qty>1) 
            $out .= "<option value='0'>Choose a Property</option>";
            foreach ($_SESSION['AUTHENTICATION']['PROPERTIES'] as $id => $prop) {
                ##if ($qty==1) $PROP_ID = (int)$id;
                $selected = ($PROP_ID == (int)$id) ? "selected" : "";
                $out .= "<option value='{$id}' $selected>{$prop['NAME']}</option>";
            }
            $out .= "</select>";
        }
        return $out;
    }

    function propertiesRadioBtns($db, $RES_PROP_ID) {
        $out = "";
        $qty = count($_SESSION['AUTHENTICATION']['PROPERTIES']);
        if ($qty!=0) {
            foreach ($_SESSION['AUTHENTICATION']['PROPERTIES'] as $id => $prop) {
                $checked = ($RES_PROP_ID == (int)$id) ? "checked" : "";
                $out .= "<div><input type='radio' name='RES_PROP_ID' value='{$id}' onClick='ibe.callcenter.reserv.showdescr(this.value)' $checked>&nbsp;{$prop['NAME']}</div>" ;
            }
        }
        return $out;
    }

    function propertiesCheckBoxes($db, $arg = array()) {
        extract($arg);
        //print "<pre>";print_r($arg);print "</pre>";
        $out = "";
        $SHORT = isset($SHORT) ? $SHORT : false;
        $ELE_ID = isset($ELE_ID) ? $ELE_ID : "PROP_IDs";
        $PROP_IDs = isset($PROP_IDs) ? $PROP_IDs : array();
        $DEFAULT_ALL = isset($DEFAULT_ALL) ? $DEFAULT_ALL : false;
        $qty = count($_SESSION['AUTHENTICATION']['PROPERTIES']);
        if ($qty!=0) {
            foreach ($_SESSION['AUTHENTICATION']['PROPERTIES'] as $id => $prop) {
                $checked = (in_array($id,$PROP_IDs) || (count($PROP_IDs)==0 && $DEFAULT_ALL)) ? "checked" : "";
                $out .= (($SHORT)?"":"<div>")."<span><input class='PROP_ID' type='checkbox' name='{$ELE_ID}[]' value='{$id}' id='{$ELE_ID}_{$id}' $checked></span>&nbsp;".($SHORT?$prop['CODE']:$prop['NAME']).(($SHORT)?"&nbsp;&nbsp;&nbsp;":"</div>");
            }
        }
        return $out;
    }

    function propertiesDescription($db, $arg) {
        extract($arg);
        $out = "";
        foreach ($_SESSION['AUTHENTICATION']['PROPERTIES'] as $id => $prop) {
            $out .= "<div id='descr_prop_{$id}' class='descr_prop'>".$prop['DESCR_'.$LANGUAGE]."</div>" ;
        }
        return $out;
    }


    function search($db, $arg) {
        extract($arg);

        $arr = array();
        if (isset($LASTNAME)&&$LASTNAME!="") array_push($arr," LASTNAME LIKE '%{$LASTNAME}%'");
        if (isset($EMAIL)&&$EMAIL!="") array_push($arr," EMAIL LIKE '%{$EMAIL}%'");
        $WHERE = count($arr)>0 ? "WHERE ".join(" AND ",$arr) : "";

        $query = "SELECT * FROM USERS $WHERE ";
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        return $result;
    }

}
global $clsUsers;
$clsUsers = new users;
?>