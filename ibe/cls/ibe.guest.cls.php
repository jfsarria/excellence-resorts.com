<?
/*
 * Revised: May 07, 2012
 */

class guest {

    var $showQry = false;
    
    function getById($db, $arg) {
        extract($arg);
        $FIELDS = isset($FIELDS) ? $FIELDS : "*";

        $query = "SELECT {$FIELDS} FROM GUESTS WHERE ID={$ID}";
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        return $result;
    }

    function getByEmail($db, $arg) {
        extract($arg);
        $FIELDS = isset($FIELDS) ? $FIELDS : "*";

        $query = "SELECT ID,FIRSTNAME,LASTNAME,`LANGUAGE`,MAILING_LIST FROM GUESTS WHERE EMAIL='{$EMAIL}'";
        $arg = array('query' => $query);
        //print "<p class='s_notice top_msg'>==> $query</p>";
        $result = dbQuery($db, $arg);
        return $result;
    }

    function getByEmailPwd($db, $arg) {
        extract($arg);
        $FIELDS = isset($FIELDS) ? $FIELDS : "*";

        $query = "SELECT * FROM GUESTS WHERE EMAIL='{$EMAIL}' AND PASSWORD='{$PASSWORD}'";
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        return $result;
    }

    function getByEmailOldId($db, $arg) {
        extract($arg);
        $query = "SELECT * FROM GUESTS WHERE MIGRATED_ID='{$MIGRATED_ID}'";
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

        $query = "SELECT * FROM GUESTS WHERE {$WHERE}";
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        return $result;
    }

    function deleteById($db, $arg) {
        $query = "DELETE FROM GUESTS WHERE ID=".$arg['ID'];
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbExecute($db, $arg);
        return $result;
    }

    function getAll($db, $arg) {
        extract($arg);

        if (!isset($SORTBY) || $SORTBY == "") { $SORTBY = "LASTNAME, FIRSTNAME"; }

        $query = "SELECT * FROM GUESTS ORDER BY ".$SORTBY;
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";

        $arg = array('query' => $query);
        $result = dbQuery($db, $arg);
        return $result;
    }

    function save($db, $arg) {
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

        if ($EMAIL!="") {
            $result = $this->getByKey($db, array("WHERE"=>"EMAIL = '$EMAIL'"));
        } else $result['iCount'] = 0;

        if ( $result['iCount'] == 0 ) {
            return $this->addNew($db, $arg);
        } else {
            return $db->fetch_array($result['rSet']);
        }
    }

    function addNew($db, &$arg) {
        global $clsGlobal;
        $arg['PASSWORD'] = (isset($arg['PASSWORD'])&&trim($arg['PASSWORD'])!="") ? $arg['PASSWORD'] : $clsGlobal->createPwd($arg['ID']);

        extract($arg);
        $UPDATED_BY = (isset($_SESSION['AUTHENTICATION']['ID'])) ? $_SESSION['AUTHENTICATION']['ID'] : 0;

        $query = "INSERT INTO GUESTS ( ID, UPDATED_BY ) VALUES ( '{$ID}', '{$UPDATED_BY}' )";
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";

        $arr = array('query' => $query);
        $result = dbExecute($db, $arr);

        return $this->modify($db, $arg);
    }

    function modify($db, $arg) {
        extract($arg);
        $arr = array();

        if ($EMAIL!="") {
            $result = $this->getByKey($db, array("WHERE"=>"EMAIL = '$EMAIL' AND ID <> '$ID'"));
        } else $result['iCount'] = 0;

        if ( $result['iCount'] == 0 ) {
            if (isset($TITLE)) array_push($arr," TITLE = '$TITLE'");
            if (isset($FIRSTNAME)) array_push($arr," FIRSTNAME = '$FIRSTNAME'");
            if (isset($LASTNAME)) array_push($arr," LASTNAME = '$LASTNAME'");
            if (isset($EMAIL)) array_push($arr," EMAIL = '$EMAIL'");
            if (isset($PASSWORD)) array_push($arr," PASSWORD = '$PASSWORD'");
            if (isset($ADDRESS)) array_push($arr," ADDRESS = '$ADDRESS'");
            if (isset($CITY)) array_push($arr," CITY = '$CITY'");
            if (isset($STATE)) array_push($arr," STATE = '$STATE'");
            if (isset($COUNTRY)) array_push($arr," COUNTRY = '$COUNTRY'");
            if (isset($ZIPCODE)) array_push($arr," ZIPCODE = '$ZIPCODE'");
            if (isset($PHONE)) array_push($arr," PHONE = '$PHONE'");
            if (isset($LANGUAGE)) array_push($arr," LANGUAGE = '$LANGUAGE'");
            if (isset($OWNER_ID)) array_push($arr," OWNER_ID = '$OWNER_ID'");
            if (isset($MAILING_LIST)) array_push($arr," MAILING_LIST = '{$MAILING_LIST}'");
            if (isset($CREATED)) array_push($arr," CREATED = '{$CREATED}'");
            if (isset($MIGRATED_ID)) array_push($arr," MIGRATED_ID = '{$MIGRATED_ID}'");

            /* CHECKBOXES */
            if (isset($IS_ACTIVE)) array_push($arr," IS_ACTIVE = '{$IS_ACTIVE}'");
            if (isset($IS_ARCHIVE)) array_push($arr," IS_ARCHIVE = '{$IS_ARCHIVE}'");
            if (isset($IS_CONFIRMED)) array_push($arr," IS_CONFIRMED = '{$IS_CONFIRMED}'");

            $query = "UPDATE GUESTS SET ".join(", ",$arr)." WHERE ID='$ID'";
            if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";

            $arr = array('query' => $query);
            $result = dbExecute($db, $arr);

            if ((int)$result != 1) { 
                print "<p class='s_missing top_msg'><b>Debug Data:</b><br><br>\n\n$query\n\n</p>";
            } else {
                //
            }
        } else {
            $result = $db->fetch_array($result['rSet']);
        }

        return $result;
    }

    function search($db, $arg) {
        extract($arg);

        $query = "SELECT * FROM GUESTS WHERE `{$FIELD}` LIKE '%{$VALUE}%' AND IS_ACTIVE='1' AND IS_CONFIRMED='1' ORDER BY LASTNAME, FIRSTNAME";
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        return $result;
    }

    function searchGuests($db, $arg) {
        extract($arg);

        $SELECT_FIELDS = (isset($COUNT) && (int)$COUNT==1) ? " ID " : " * ";

        $arr = array();
        if (isset($LASTNAME)&&$LASTNAME!="") array_push($arr," LASTNAME LIKE '%{$LASTNAME}%'");
        if (isset($EMAIL)&&$EMAIL!="") array_push($arr," EMAIL LIKE '%{$EMAIL}%'");
        if (isset($PHONE)&&$PHONE!="") array_push($arr," PHONE LIKE '%{$PHONE}%'");
        if (isset($MAILING_LIST)&&$MAILING_LIST!="") array_push($arr," MAILING_LIST='{$MAILING_LIST}'");
        $WHERE = count($arr)>0 ? "WHERE ".join(" AND ",$arr) : "";

        $query = "SELECT {$SELECT_FIELDS} FROM V_GUEST_SEARCH $WHERE ";

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

        $LAN = (isset($LAN)&&$LAN=="SP") ? "_SP" : "";

        $FIXED_HOME = isset($PROPERTY['HOME_URL'.$LAN]) ? str_replace("http://www.","",$PROPERTY['HOME_URL'.$LAN]) : $GENERIC_SITE_NAME;
        $LOGIN_GUEST_URL = isset($PROPERTY['LOGIN_GUEST_URL'.$LAN]) ? $PROPERTY['LOGIN_GUEST_URL'.$LAN] : $FIXED_HOME;
        $RES_URL = isset($PROPERTY['RES_URL'.$LAN]) ? $PROPERTY['RES_URL'.$LAN] : $FIXED_HOME;
        $RES_EMAIL = isset($PROPERTY['RES_EMAIL']) ? $PROPERTY['RES_EMAIL'] : "";

        $RSET = $this->getById($db, $arg);
        if ( $RSET['iCount'] != 0 ) {
            $GUEST = $db->fetch_array($RSET['rSet']);
            if ((int)$GUEST['OWNER_ID']==(int)$GUEST['ID']) {
                $MESSAGE = _l(
                "
                    Dear {$GUEST['FIRSTNAME']} {$GUEST['LASTNAME']},
                    
                    This is the password that you will need in order to access your personal {$FIXED_HOME} account: 
                    
                    <b>{$GUEST['PASSWORD']}</b>
                    
                    Please remember that you will need this password in order to change or cancel your reservations.

                    To access your personal account, go to: <a href='{$RES_URL}'>View my Reservations</a> 

                    Please do not hesitate to contact us via e-mail at the address <a href='mailto:{$RES_EMAIL}'>{$RES_EMAIL}</a> should you require any further information. 

                    Kind regards,
                    
                    <b>The {$FIXED_HOME} team.</b>
                    
                ",
                "
                    Estimado (a) {$GUEST['FIRSTNAME']} {$GUEST['LASTNAME']},
                     
                    Con esta contraseña podrá acceder a la información personal de su cuenta en {$FIXED_HOME}:
                     
                    <b>{$GUEST['PASSWORD']}</b>
                     
                    Por favor, recuerde que usted necesitará esta contraseña para cambiar o cancelar sus reservas.
                     
                    Para acceder a su cuenta personal, ir a: <a href='{$RES_URL}'>Ver mis reservas</a> 
                     
                    Por favor no dude en contactar con nosotros vía e-mail a la dirección <a href='mailto:{$RES_EMAIL}'>{$RES_EMAIL}</a> en caso de necesitar asistencia o más información.
                     
                    Saludos cordiales,
                     
                    <b>El equipo de {$FIXED_HOME}.</b>

                ",
                $LAN);
            } else {
                $MESSAGE = _l(
                "
                    Dear {$GUEST['FIRSTNAME']},
                    
                    Please contact your Travel Agent in order to have access to your reservations.
                    
                    Kind regards,
                    
                    <b>The {$FIXED_HOME} team.</b>
                    
                ",
                "
                    Estimado (a) {$GUEST['FIRSTNAME']} {$GUEST['LASTNAME']},

                    Por favor contacte su Agente de Viajes para tener accesso a sus reservaciones.

                    Saludos cordiales,
                     
                    <b>El equipo de {$FIXED_HOME}.</b>

                ",
                $LAN);
            }
            $MESSAGE = str_replace(array("\n","\r\n"),array("<br>","<br>"),$MESSAGE);
            $_EMAIL['FORM'] = "";
            $_EMAIL['TO'] = $GUEST['EMAIL'];
            $_EMAIL['SUBJECT'] = _l("Password for your account on ","Contraseña para su cuenta en ",$LAN).$FIXED_HOME;
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
            if (dbTableExists($db, $VIEWNAME)) {
                $clsReserv->searchReservationQuery($db, array(
                    "GROUPED"=>$GROUPED,
                    "VIEWNAME"=>$VIEWNAME,
                    "TABLENAME"=>str_replace("V_SEARCH_","RESERVATIONS_",$VIEWNAME),
                    "WHERE"=>"GUEST_ID={$GUEST_ID}"
                ), $qry);
            }
        }

        $query = implode(" UNION ",$qry)." ORDER BY ID DESC, NUMBER";
        $arg = array('query' => $query);
        //if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        //file_put_contents($_SERVER['DOCUMENT_ROOT']."/ibe/cls/DEBUG.TXT",$query,FILE_APPEND);
        $result = dbQuery($db, $arg);
        return $result;
    }

}

global $clsGuest;
$clsGuest = new guest;
?>