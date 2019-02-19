<?
/*
 * Revised: Oct 24, 2011
 *          May 12, 2018
 */

class TA {

    var $showQry = false;

    function getById($db, $arg) {
        $query = "SELECT * FROM TRAVEL_AGENTS WHERE ID=".$arg['ID'];
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        return $result;
    }

    function getByEmailPwd($db, $arg) {
        extract($arg);
        $FIELDS = isset($FIELDS) ? $FIELDS : "*";

        $query = "SELECT * FROM TRAVEL_AGENTS WHERE EMAIL='{$EMAIL}' AND PASSWORD='{$PASSWORD}'";
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        return $result;
    }

    function get($db, $arg) {
        global $clsGlobal;
        extract($arg);
        $return = array();
        $GSET = $this->getById($db, array("ID"=>$ID));
        if ($GSET['iCount']>0) {
            $return = $clsGlobal->cleanUp_rSet_Array($db->fetch_array($GSET['rSet']));
        }
        return $return;
    }

    function getByKey($db, $arg) {
        extract($arg);

        $query = "SELECT * FROM TRAVEL_AGENTS WHERE {$WHERE}";
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        return $result;
    }

    function deleteById($db, $arg) {
        $query = "DELETE FROM TRAVEL_AGENTS WHERE ID=".$arg['ID'];
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbExecute($db, $arg);
        return $result;
    }

    function getAll($db, $arg) {
        extract($arg);

        if (!isset($SORTBY) || $SORTBY == "") { $SORTBY = "LASTNAME, FIRSTNAME"; }

        $query = "SELECT * FROM TRAVEL_AGENTS ORDER BY ".$SORTBY;
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";

        $arg = array('query' => $query);
        $result = dbQuery($db, $arg);
        return $result;
    }

    function save($db, &$arg) {
        extract($arg);

        $result = $this->getById($db, $arg);

        if ( $result['iCount'] == 0 ) {
            $result = $this->addNew($db, $arg);
        } else {
            $result = $this->modify($db, $arg);
        }

        return $result;
    }

    function create($db, &$arg) {
        extract($arg);

        $result = $this->getByKey($db, array("WHERE"=>"EMAIL = '$EMAIL'"));

        if ( $result['iCount'] == 0 ) {
            return $this->addNew($db, $arg);
        } else {
            return $db->fetch_array($result['rSet']);
        }
    }

    function addNew($db, &$arg) {
        global $clsGlobal;
        $arg['PASSWORD'] = (isset($arg['PASSWORD'])&&$arg['PASSWORD']!="") ? $arg['PASSWORD'] : $clsGlobal->createPwd($arg['ID']);

        extract($arg);

        if (ctype_digit($ID)) {
            $UPDATED_BY = (isset($_SESSION['AUTHENTICATION']['ID'])) ? $_SESSION['AUTHENTICATION']['ID'] : 0;

            $query = "INSERT INTO TRAVEL_AGENTS ( ID, UPDATED_BY ) VALUES ( '{$ID}', '{$UPDATED_BY}' )";
            if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";

            $arr = array('query' => $query);
            $result = dbExecute($db, $arr);

            $arg["IS_NEW"] = "1";
            return $this->modify($db, $arg);
        }
    }

    function is_empty($params, $fields) {
        $vacio = false;
        foreach (explode(",", $fields) as $i => $field) {
            if (empty($params[$field])) {
              $vacio = true;
            }
        }
        return $vacio;
    }

    function modify($db, $arg) {
        global $clsGlobal;
        //extract($arg);
        $arr = array();

        // Escape variables for security
        $params = array();
        foreach ($arg as $key => $val) {
          if (is_string($val)) {
            $val = mysql_real_escape_string($val);
          }
          $params[$key] = $val;
        }

        extract($params);
        $arr = array();
        //$BASIC_FIELDS = "IATA,AGENCY_NAME,AGENCY_PHONE,AGENCY_ADDRESS,AGENCY_CITY,AGENCY_STATE,AGENCY_COUNTRY,AGENCY_ZIPCODE,FIRSTNAME,LASTNAME";
        $BASIC_FIELDS = "IATA,AGENCY_NAME,FIRSTNAME";

        if (ctype_digit($ID) && !$this->is_empty($params, $BASIC_FIELDS) && filter_var($EMAIL, FILTER_VALIDATE_EMAIL)) {

        if (isset($IATA)) array_push($arr," IATA = '$IATA'");
        if (isset($AGENCY_NAME)) array_push($arr," AGENCY_NAME = '$AGENCY_NAME'");
        if (isset($AGENCY_PHONE)) array_push($arr," AGENCY_PHONE = '$AGENCY_PHONE'");
        if (isset($AGENCY_ADDRESS)) array_push($arr," AGENCY_ADDRESS = '$AGENCY_ADDRESS'");
        if (isset($AGENCY_CITY)) array_push($arr," AGENCY_CITY = '$AGENCY_CITY'");
        if (isset($AGENCY_STATE)) array_push($arr," AGENCY_STATE = '$AGENCY_STATE'");
        if (isset($AGENCY_COUNTRY)) array_push($arr," AGENCY_COUNTRY = '$AGENCY_COUNTRY'");
        if (isset($AGENCY_ZIPCODE)) array_push($arr," AGENCY_ZIPCODE = '$AGENCY_ZIPCODE'");
        if (isset($COMMISSION_RATE)) array_push($arr," COMMISSION_RATE = '$COMMISSION_RATE'");
        if (isset($TITLE)) array_push($arr," TITLE = '$TITLE'");
        if (isset($FIRSTNAME)) array_push($arr," FIRSTNAME = '$FIRSTNAME'");
        if (isset($LASTNAME)) array_push($arr," LASTNAME = '$LASTNAME'");
        if (isset($EMAIL)) array_push($arr," EMAIL = '$EMAIL'");
        if (isset($COMMENTS)) array_push($arr," COMMENTS = '$COMMENTS'");
        if (isset($HEAR_ABOUT_US)) array_push($arr," HEAR_ABOUT_US = '$HEAR_ABOUT_US'");

            if (isset($IS_NEW) && ((isset($IS_CONFIRMED) && (int)$IS_CONFIRMED==0) || !isset($IS_CONFIRMED))) {
                $MESSAGE = implode("<br>",$arr);
                $MESSAGE = str_replace(array("'"," = ","_"),array("",": "," "),$MESSAGE);
                $clsGlobal->sendEmail(array(
                    'FORM' => $EMAIL,
                    'TO' => constant("EMAIL_ADMIN"),
                    'SUBJECT' => "Travel agent account request",
                    'MESSAGE' => $MESSAGE
                ));
            }

            if (isset($PASSWORD)) array_push($arr," PASSWORD = '$PASSWORD'");
            if (isset($CREATED)) array_push($arr," CREATED = '{$CREATED}'");
            if (isset($MIGRATED_ID)) array_push($arr," MIGRATED_ID = '{$MIGRATED_ID}'");

            /* CHECKBOXES */
            if (isset($IN_MEXICO)) array_push($arr," IN_MEXICO = '{$IN_MEXICO}'");
            if (isset($IS_ACTIVE)) array_push($arr," IS_ACTIVE = '{$IS_ACTIVE}'");
            if (isset($IS_ARCHIVE)) array_push($arr," IS_ARCHIVE = '{$IS_ARCHIVE}'");
            if (isset($IS_CONFIRMED)) array_push($arr," IS_CONFIRMED = '{$IS_CONFIRMED}'");

            $query = "UPDATE TRAVEL_AGENTS SET ".join(", ",$arr)." WHERE ID='$ID'";
            if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";

            $arr = array('query' => $query);
            $result = dbExecute($db, $arr);

            if ((int)$result != 1) { 
                print "<p class='s_missing top_msg'><b>Debug Data:</b><br><br>\n\n$query\n\n</p>";
            } else {

            }
        } else {
            $result = 0;
            print "<p class='s_missing top_msg'>Invalid data</p>";
        }

        if ($result != 1) {
            $this->deleteById($db, $arg);
        }

        return $result;
    }

    function search($db, $arg) {
        extract($arg);

        //$query = "SELECT * FROM TRAVEL_AGENTS WHERE `{$FIELD}` LIKE '%{$VALUE}%' AND IS_ACTIVE='1' AND IS_CONFIRMED='1' ORDER BY LASTNAME, FIRSTNAME";
        $query = "SELECT * FROM TRAVEL_AGENTS WHERE `{$FIELD}` LIKE '%{$VALUE}%' AND IS_ACTIVE='1' ORDER BY LASTNAME, FIRSTNAME";
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        return $result;
    }

    function searchTA($db, $arg) {
        extract($arg);

        $SELECT_FIELDS = (isset($COUNT) && (int)$COUNT==1) ? " ID " : " * ";

        $arr = array();
        if (isset($IATA)&&$IATA!="") array_push($arr," IATA LIKE '%{$IATA}%'");
        if (isset($AGENCY)&&$AGENCY!="") array_push($arr," AGENCY_NAME LIKE '%{$AGENCY}%'");
        if (isset($PHONE)&&$PHONE!="") array_push($arr," AGENCY_PHONE LIKE '%{$PHONE}%'");
        if (isset($LASTNAME)&&$LASTNAME!="") array_push($arr," LASTNAME LIKE '%{$LASTNAME}%'");
        if (isset($EMAIL)&&$EMAIL!="") array_push($arr," EMAIL LIKE '%{$EMAIL}%'");
        if (isset($IS_CONFIRMED)&&$IS_CONFIRMED!="") array_push($arr," IS_CONFIRMED='{$IS_CONFIRMED}'");
        $WHERE = count($arr)>0 ? "WHERE ".join(" AND ",$arr) : "";

        $query = "SELECT {$SELECT_FIELDS} FROM TRAVEL_AGENTS $WHERE ";

        if (isset($sortBy) && $sortBy != "") $query .= " ORDER BY $sortBy";
        if (isset($startItem) && isset($itemsPerPage)) $query .= " LIMIT $startItem, $itemsPerPage";

        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        return $result;
    }

    function sendPwd($db, $arg) {
        global $clsGlobal, $clsReserv, $GENERIC_SITE_NAME;
        extract($arg);

        ob_start();
            $PROPERTY = $clsReserv->getOwnerProperty($db, array("OWNER_ID"=>$ID));
            //print "<pre>";print_r($PROPERTY);print "</pre>";
        $DELETE = ob_get_clean();

        $FIXED_HOME = isset($PROPERTY['HOME_URL']) ? str_replace("http://www.","",$PROPERTY['HOME_URL']) : $GENERIC_SITE_NAME;
        $LOGIN_TA_URL = isset($PROPERTY['LOGIN_TA_URL']) ? $PROPERTY['LOGIN_TA_URL'] : $FIXED_HOME;
        $RES_URL = isset($PROPERTY['RES_URL']) ? $PROPERTY['RES_URL'] : $FIXED_HOME;
        $RES_EMAIL = isset($PROPERTY['RES_EMAIL']) ? $PROPERTY['RES_EMAIL'] : "";

        $RSET = $this->getById($db, $arg);
        if ( $RSET['iCount'] != 0 ) {
            $TA = $db->fetch_array($RSET['rSet']);
            $MESSAGE = "
                Dear {$TA['FIRSTNAME']} {$TA['LASTNAME']},
                
                This is the login information that you can use to access your $GENERIC_SITE_NAME Travel Agent account:
                
                <b>{$TA['PASSWORD']}</b>
                
                Please remember that you will need this information in order to make changes to your clients reservations. To login go to the professional tool section on the website.
                
                Kind regards,
                
                <b>The $GENERIC_SITE_NAME team.</b>
                
            ";
            $MESSAGE = str_replace(array("\n","\r\n"),array("<br>","<br>"),$MESSAGE);
            $_EMAIL['FORM'] = "";
            $_EMAIL['TO'] = $TA['EMAIL'];
            $_EMAIL['SUBJECT'] = "Password for your account on $GENERIC_SITE_NAME";
            $_EMAIL['MESSAGE'] = $MESSAGE;

            $clsGlobal->sendEmail($_EMAIL);
        }
    }

    function sendApproval($db, $arg) {
        global $clsGlobal, $clsReserv, $GENERIC_SITE_NAME;
        extract($arg);

        ob_start();
            $PROPERTY = $clsReserv->getOwnerProperty($db, array("OWNER_ID"=>$ID));
            //print "<pre>";print_r($PROPERTY);print "</pre>";
        $DELETE = ob_get_clean();

        $FIXED_HOME = isset($PROPERTY['HOME_URL']) ? str_replace("http://www.","",$PROPERTY['HOME_URL']) : $GENERIC_SITE_NAME;
        $LOGIN_TA_URL = isset($PROPERTY['LOGIN_TA_URL']) ? $PROPERTY['LOGIN_TA_URL'] : $FIXED_HOME;
        $SERVICE_EMAIL = isset($PROPERTY['SERVICE_EMAIL']) ? $PROPERTY['SERVICE_EMAIL'] : "";

        $RSET = $this->getById($db, $arg);
        if ( $RSET['iCount'] != 0 ) {
            $TA = $db->fetch_array($RSET['rSet']);
            $MESSAGE = "
                Password for {$FIXED_HOME}
                
                Dear {$TA['FIRSTNAME']},
                
                <b>Your account has been approved for access.</b>
                
                This is the password that you will need in order to access your {$FIXED_HOME} account:
                
                <b>login:</b> {$TA['EMAIL']}
                <b>password:</b> {$TA['PASSWORD']}
                
                You can start using your account immediately to make reservations and earn commissions*. To login go to the professional tool section on the website.

                *Note that commission applies to “Resort Only” reservations booked through {$FIXED_HOME} official website.

                Please do not hesitate to contact us via e-mail at the address <a href='mailto:{$SERVICE_EMAIL}'>{$SERVICE_EMAIL}</a> should you require any further information.

                Kind regards,

                <b>The {$FIXED_HOME} team.</b>
                
            ";
            $MESSAGE = str_replace(array("\n","\r\n"),array("<br>","<br>"),$MESSAGE);

            $_EMAIL['FORM'] = "";
            $_EMAIL['TO'] = $TA['EMAIL'];
            $_EMAIL['SUBJECT'] = "Your Access for {$FIXED_HOME}";
            $_EMAIL['MESSAGE'] = $MESSAGE;

            $clsGlobal->sendEmail($_EMAIL);
        }
    }

    function getReservations($db, $arg) {
        extract($arg);
        global $clsReserv;

        $qry = array();
        $TABLES = array();
        $RSET = mysql_list_tables(constant("APP_DB_NAME"));
        while ($row = mysql_fetch_row($RSET)) {
            $VIEWNAME = $row[0];
            if (strstr($VIEWNAME,"V_SEARCH_")) array_push($TABLES,$VIEWNAME);
        }
        foreach ($TABLES as $i=>$VIEWNAME) {
            $clsReserv->searchReservationQuery($db, array(
                "GROUPED"=>$GROUPED,
                "VIEWNAME"=>$VIEWNAME,
                "TABLENAME"=>str_replace("V_SEARCH_","RESERVATIONS_",$VIEWNAME),
                "WHERE"=>"OWNER_ID={$TA_ID}"
            ), $qry);
        }

        $query = implode(" UNION ",$qry)." ORDER BY ID DESC, NUMBER";
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        return $result;
    }

}

global $clsTA;
$clsTA = new TA;
?>