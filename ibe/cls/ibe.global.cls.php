<?
/*
 * Revised: Jan 14, 2013
 *          May 29, 2018
 */

require_once $_SERVER["DOCUMENT_ROOT"] . "/ibe/api/campaignmonitor/campaignmonitor-createsend-php/csrest_transactional_classicemail.php";

class globalfn {
    var $showQry = false;
    var $PROPERTIES = array();
    
    function buildWebserviceParameters($PAGE_CODE, $arr) {
        $return = "<div style='display:none'>\n\n/ibe/index.php?PAGE_CODE={$PAGE_CODE}"; 
        foreach ($arr as $KEY => $VALUE) {
            if (is_array($VALUE)) {
                foreach ($VALUE as $ind => $SVALUE) $return .= "&{$KEY}[]={$SVALUE}";
            } else {
                $return .= "&{$KEY}={$VALUE}";
            }
        }
        $return .= "\n\n</div>";
        return $return;
    }

    function getUserTypes($db, $arg=array()) {
        extract($arg);
     
        $query = "SELECT * FROM USER_TYPES ORDER BY `ORDER`";
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        return $result;
    }

    function getUserByRole($db, $arg=array()) {
        extract($arg);
     
        $query = "SELECT * FROM USERS WHERE ROLE='{$ROLE}' ORDER BY LASTNAME, FIRSTNAME";
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        return $result;
    }

    function getCountries($db, $arg=array()) {
        extract($arg);

        $WHERE = (isset($GROUP)) ? " WHERE `GROUP` = '$GROUP'" : "";
     
        $query = "SELECT * FROM COUNTRIES $WHERE ORDER BY ID";
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        return $result;
    }

    function getCountryGroupByCode($db, $arg=array()) {
        extract($arg);

        $query = "SELECT * FROM COUNTRIES WHERE CODE = '$CODE'";
        $arg = array('query' => $query);
        //print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        $GROUP = "--";
        while ($row = $db->fetch_array($result['rSet'])) {
            $GROUP = $row['GROUP'];
        }
        return $GROUP;
    }

    function getCountriesDropDown($db, $arg, $ln="EN") {
        extract($arg);
        $ELE_ID = isset($ELE_ID) ? $ELE_ID : "COUNTRY_CODE";
        $RSET = $this->getCountries($db, $arg);
        $result = "<select name='$ELE_ID' id='$ELE_ID'>";
        if (isset($firstEmpty)&&(int)$firstEmpty==1) $result .= "<option value='' style='color:#c0c0c0'>".($ln=="EN" ? "Select Country" : "Seleccione País")."</option>";
        while ($row = $db->fetch_array($RSET['rSet'])) {
            $selected = (isset($COUNTRY_CODE)&&$COUNTRY_CODE==$row['CODE']) ? "selected" : "";
            $result .= "<option value='{$row['CODE']}' $selected>{$row['NAME']}</option>";
        }
        $result .= "</select>";

        return $result;
    }

    function getStates($db, $arg=array()) {
        extract($arg);
        $CODE = isset($CODE) ? $CODE : "US";
        $query = "SELECT * FROM V_STATES_{$CODE}";
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        return $result;
    }

    function getStatesDropDown($db, $arg, $ln="EN") {
        extract($arg);
        $ELE_ID = isset($ELE_ID) ? $ELE_ID : "STATE_CODE";
        $result = "<select name='$ELE_ID' id='$ELE_ID'>";
        $result .= "<option value='' style='color:#c0c0c0'>".($ln=="EN" ? "Select State" : "Seleccione Estado")."</option>";
        $arg['CODE'] = isset($arg['CODE']) ? $arg['CODE'] : "US";
        $RSET = $this->getStates($db, $arg);
        while ($row = $db->fetch_array($RSET['rSet'])) {
            $CODE = trim($row['CODE'])!="" ? $row['CODE'] : $row['NAME'];
            $selected = (isset($STATE_CODE)&&$STATE_CODE==$CODE) ? "selected" : "";
            $result .= "<option value='{$CODE}' $selected>{$row['NAME']}</option>";
        }
        $result .= "</select>";

        return $result;
    }

    function getPropertyById($db, $arg) {
        extract($arg);
     
        $query = "
            SELECT 
                PROPERTIES.*, 
                COMPANIES.NAME AS COMPANY_NAME 
            FROM 
                PROPERTIES 
            JOIN 
                COMPANIES ON COMPANIES.ID = PROPERTIES.COMP_ID 
            WHERE 
                PROPERTIES.ID = {$PROPERTY_ID}

        ";
        $arg = array('query' => $query);
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $result = dbQuery($db, $arg);
        return $result;
    }

    function getPropertiesByIDs($db, $arg) {
        extract($arg);
        global $clsTransfer;

        $asArray = (isset($asArray)) ? $asArray : false;
        $WHERE = (isset($WHERE)) ? $WHERE : "";

        $query = "SELECT * FROM PROPERTIES {$WHERE}";
        if ($this->showQry) print "<p class='s_notice top_msg'>$query</p>";
        $arg = array('query' => $query);
        $result = dbQuery($db, $arg);

        if ($asArray) {
            $arr = array();
            while ($row = $db->fetch_array($result['rSet'])) {
                $arr[$row['ID']] = array();
                $arr[$row['ID']]['NAME'] = $row['NAME'];
                $arr[$row['ID']]['CODE'] = $row['CODE'];
                $arr[$row['ID']]['ADMIN_EMAIL'] = $row['ADMIN_EMAIL'];
                $arr[$row['ID']]['IS_TRANSFER_ACTIVE'] = $clsTransfer->isActive($db, array("PROP_ID"=>$row['ID']));
            }
            return $arr;
        } else {
            return $result;
        }
    }

    function getPropertiesNamesFromArray($IDs, $PROPERTIES) {
        $result = array();
        foreach ($IDs as $ind=>$ID) if (isset($PROPERTIES[$ID])) array_push($result, $PROPERTIES[$ID]['NAME']);
        return implode(", ",$result);
    }

    function reservTypesCheckBoxes($db, $arg = array()) {
        extract($arg);
        $out = "";
        $ELE_ID = isset($ELE_ID) ? $ELE_ID : "RESTYPE_IDs";
        $RESTYPE_IDs = isset($RESTYPE_IDs) ? $RESTYPE_IDs : array();
        $DEFAULT_ALL = isset($DEFAULT_ALL) ? $DEFAULT_ALL : false;
        foreach (array('booked','arrived','cancelled','no show') as $IND => $TYPE) {
            $checked = (in_array($TYPE,$RESTYPE_IDs) || (count($RESTYPE_IDs)==0 && $DEFAULT_ALL)) ? "checked" : "";
            $out .= "<div><span><input type='checkbox' name='{$ELE_ID}[]' value='{$TYPE}' $checked></span>&nbsp;".ucwords($TYPE)."</div>";
        }
        return $out;
    }

    function getUserTypeCheckBoxes($db, $arg = array()) {
        extract($arg);
        $RSET = $this->getUserTypes($db);
        $out = "";
        $ELE_ID = isset($ELE_ID) ? $ELE_ID : "USERTYPE_IDs";
        $MADEBY_IDs = isset($MADEBY_IDs) ? $MADEBY_IDs : array();
        $DEFAULT_ALL = isset($DEFAULT_ALL) ? $DEFAULT_ALL : false;
        while ($row = $db->fetch_array($RSET['rSet'])) {
            //$checked = (in_array($row['ID'],$MADEBY_IDs) || (count($MADEBY_IDs)==0 && $DEFAULT_ALL)) ? "checked" : "";
            $checked = in_array($row['ID'],$MADEBY_IDs) ? "checked" : "";
            $out .= "<div><span><input type='checkbox' name='{$ELE_ID}[]' value='{$row['ID']}' $checked></span>&nbsp;".ucwords($row['TYPE_NAME'])."</div>";
        }
        return $out;
    }

    function getCallCenterCheckBoxes($db, $arg = array()) {
        global $clsUsers;
        extract($arg);
        //$RSET = $this->getUserByRole($db, array("ROLE"=>"3"));
        $RSET = $clsUsers->getAll($db);
        $out = "";
        $ELE_ID = isset($ELE_ID) ? $ELE_ID : "USER_IDs";
        $AGENT_IDs = isset($AGENT_IDs) ? $AGENT_IDs : array();
        $DEFAULT_ALL = isset($DEFAULT_ALL) ? $DEFAULT_ALL : false;
        while ($row = $db->fetch_array($RSET['rSet'])) {
            $checked = (in_array($row['ID'],$AGENT_IDs) || (count($AGENT_IDs)==0 && $DEFAULT_ALL)) ? "checked" : "";
            $out .= "<div><span><input type='checkbox' name='{$ELE_ID}[]' value='{$row['ID']}' $checked></span>&nbsp;".ucwords($row['LASTNAME']).",".ucwords($row['FIRSTNAME'])."</div>";
        }
        return $out;
    }

    function getCallCenterAgents($db, $arg = array()) {
        global $clsUsers;
        extract($arg);
        $RSET = $clsUsers->getAll($db);
        $out = array();
        while ($row = $db->fetch_array($RSET['rSet'])) $out[$row['ID']] = $row;
        return $out;
    }

    function cleanUp_rSet_Array($ARR) {
        foreach ($ARR AS $KEY => $VAL) if ((int)$KEY!=0||$KEY=="0") unset($ARR[$KEY]);
        return $ARR;
    }

    function daily_rate_details($_AVAILABILITY, $arg) {
        extract($arg);
        $str = $this->daily_rate_openWeekBox(array(
            "DATE_START"=>$_AVAILABILITY['RES_CHECK_IN'],
            "DATE_END"=>$_AVAILABILITY['RES_CHECK_OUT']
        ));
        $str .= $this->daily_rate_row($_AVAILABILITY, $arg);
        $str .= $this->daily_rate_closeWeekBox();
        return $str;
    }

    function daily_rate_openWeekBox($arg) {
        extract($arg);
        $str = "
            <TABLE class='dailyDetailsTbl' width='100%' border='0' cellpadding='2' cellspacing='2'>
            <TR>
                <TD width='16%' class='dowTbl'>&nbsp;</TD>
                <TD width='12%' class='dowTbl'>Sun</TD>
                <TD width='12%' class='dowTbl'>Mon</TD>
                <TD width='12%' class='dowTbl'>Tue</TD>
                <TD width='12%' class='dowTbl'>Wed</TD>
                <TD width='12%' class='dowTbl'>Thr</TD>
                <TD width='12%' class='dowTbl'>Fri</TD>
                <TD width='12%' class='dowTbl'>Sat</TD>
            </TR>
            <TR class='dailyRow'>
        ";

        $DOW = date("w", strtotime($DATE_START))+1;
        $str .= $this->daily_rate_BoxDates($DATE_START, $DATE_END, $DOW);
        for ($t=1; $t < $DOW; ++$t) $str .= "<TD>&nbsp;</TD>";

        return $str;
    }

    function daily_rate_closeWeekBox() {
        $str = "</TR></TABLE>";
        return $str;
    }

    function daily_rate_BoxDates($FROM, $TO, $DOW) {
        $LIMIT = addDaysToDate($FROM, 7-$DOW);
        $TO = (strtotime($LIMIT) < strtotime($TO)) ? $LIMIT : addDaysToDate($TO,-1);

        $return = "<TD nowrap valign='top' style='padding:0 15px 0 5px'>";
        $return .= date("M j", strtotime($FROM));
        if ( $FROM != $TO ) $return .= "&nbsp;-&nbsp;".date("M j", strtotime($TO));
        $return .= "</TD>";

        return $return;
    }

    function daily_rate_row($_AVAILABILITY, $arg) {
        extract($arg);
        $return = "";

        $ROOM = $_AVAILABILITY["RES_ROOM_{$ROOM_NUM}_ROOMS"][$ROOM_ID];
        if (!isset($ROOM["NIGTHS"])) $ROOM["NIGTHS"] = array();

        $AV_STAY = (int)$_AVAILABILITY['RES_NIGHTS'];
        $AV_DAY = $_AVAILABILITY['RES_CHECK_IN'];
        $AV_TO = $_AVAILABILITY['RES_CHECK_OUT'];
        
        $incr = 0;
        do {
            $hasRate = true;
            if (isset($ROOM["NIGTHS"][$AV_DAY])) {
                $NIGHT = $ROOM["NIGTHS"][$AV_DAY];
                if (is_array($NIGHT)) {
                    $GUESTS = ($_AVAILABILITY["RES_ROOM_{$ROOM_NUM}_ROOMS"][$ROOM_ID]['TOTAL']['GROSS_PP']!=0) ? (int)$_AVAILABILITY["RES_ROOM_{$ROOM_NUM}_GUESTS_QTY"] : 1;
                    $GROSS = (int)$NIGHT["RATE"]["GROSS"]*$GUESTS;
                    $FINAL = (int)$NIGHT["RATE"]["FINAL"]*$GUESTS;
                    $return .= "<TD nowrap valign='top'>";
                        if ($GROSS!=$FINAL) $return .= "<div class='GROSS crossed'>$".number_format($GROSS)."</div>";
                        $return .= "<div class='FINAL'>$".number_format($FINAL)."</div>";
                    $return .= "</TD>";
                } else $hasRate = false;
            } else $hasRate = false;
            if (!$hasRate) $return .= "<TD>X</TD>";

            // NEXT DAY
            $DOW = date("w", strtotime($AV_DAY))+1;
            $AV_DAY = addDaysToDate($AV_DAY, 1);
            ++$incr;

            if ( $DOW == 7 && $incr < $AV_STAY ) {
                $return .= "<TR>".$this->daily_rate_BoxDates($AV_DAY, $AV_TO, 1)."</TD>";
            }
        } while ($incr < $AV_STAY);

        return $return;
    }

    function getExpMonthsDropDown($arg) {
        extract($arg);
        $abbrMonthNames = array('01-Jan', '02-Feb', '03-Mar', '04-Apr', '05-May', '06-Jun', '07-Jul', '08-Aug', '09-Sep', '10-Oct', '11-Nov', '12-Dec');
        $ELE_ID = isset($ELE_ID) ? $ELE_ID : "RES_CC_EXP";
        ob_start();
        ?>
        <input type="hidden" name="<? print $ELE_ID ?>" id="<? print $ELE_ID ?>" value="<? if (isset($card_exp)) print $card_exp ?>">
        <select name="card-exp-MM" id="card-exp-MM">
            <?
            $MM=1;
            foreach($abbrMonthNames as $key) {
                print "<option value='".(($MM>9)?$MM:"0".$MM)."'".((isset($expMM)&&(int)$expMM==$MM)?"selected":"").">{$key}</option>";
                ++$MM;
            }
            ?>
        </select>
        /
        <select name="card-exp-YY" id="card-exp-YY">
            <?
            for ($YY=date("Y");$YY<=date("Y")+12;++$YY) print "<option value='".($YY - 2000)."'".((isset($expYY)&&(int)$expYY==($YY - 2000))?"selected":"").">{$YY}</option>";
            ?>
        </select>
        <?
        return ob_get_clean();
    }

    function getHowDidYouHearAboutUs($db, $arg=array(), $ln="EN") {
        extract($arg);
        $HEAR_ABOUT_US = isset($HEAR_ABOUT_US) ? $HEAR_ABOUT_US : "";
        $ELE_ID = isset($ELE_ID) ? $ELE_ID : "RES_GUEST_HEAR_ABOUT_US";
        $aHOWS = array(
          "Travel Agent",
          "Wedding",
          "Family",
          "Online research",
          "Repeat guest",
          "TripAdvisor",
          "Other"
        );
        $result = "
          <script>
            function getHowDidYouHearAboutUs(value) {
              $('#Other_txt').css('display',(value.indexOf('Other')==0?'block':'none'));
              $('#{$ELE_ID}').val(value.indexOf('Other')==0?value+': '+$('#Other_txt textarea').val():value);
            }
            function setHowDidYouHearAboutUs(value) {
              if (value.indexOf('Other')==0) {
                $('#Other_txt textarea').val(value.replace('Other: ',''));
              } 
              getHowDidYouHearAboutUs($('#hearOptions').val())
            }
          </script>
        ";
        $result .= "<input name='$ELE_ID' id='$ELE_ID' style='width:100%;display:none'>";
        $result .= "<select id='hearOptions' style='width:100%' onchange='getHowDidYouHearAboutUs(this.value)' rel='$HEAR_ABOUT_US'><option value=''></option>";
        for ($t=0; $t < count($aHOWS); ++$t) {
            $result .= "<option value='".$aHOWS[$t]."' ".( stripos($HEAR_ABOUT_US,$aHOWS[$t])===0?"selected":"").">".$aHOWS[$t]."</option>";
        }
        $result .= "</select>";
        $result .= "<div id='Other_txt' style='display: none;'><textarea style='width:100%' onmouseout=\"getHowDidYouHearAboutUs($('#hearOptions').val())\"></textarea></div>";
        $result .= "<script>setHowDidYouHearAboutUs('$HEAR_ABOUT_US')</script>";
        return $result;    
    }

    function getBedTypesDropDown($db, $arg, $ln="EN") {
        extract($arg);
        $ELE_ID = isset($ELE_ID) ? $ELE_ID : "BED_TYPE";
        $aBEDS = explode(",",$BEDS);
        $result = "<select name='$ELE_ID' id='$ELE_ID'><option value=''>".($ln=="EN" ? "No preferences" : "Ninguna")."</option>";
        for ($t=0; $t < count($aBEDS); ++$t) {
            $result .= "<option value='".$aBEDS[$t]."' ".((isset($SELECTED)&&$SELECTED==$aBEDS[$t])?"selected":"").">".$BED_TYPES[$aBEDS[$t]]."</option>";
        }
        $result .= "</select>";
        return $result;
    }

    function getSmokingPrefeDropDown($db, $arg, $ln="EN") {
        extract($arg);
        $ELE_ID = isset($ELE_ID) ? $ELE_ID : "SMOKING";
        $result = "
        <select name='$ELE_ID' id='$ELE_ID'>
			<option value=''>".($ln=="EN" ? "No preferences" : "Ninguna")."</option>
			<option value='Non-smoking' ".((isset($SELECTED)&&$SELECTED=='Non-smoking')?"selected":"").">".($ln=="EN" ? "Non-smoking" : "No Fumar")."</option>
			<option value='Smoking' ".((isset($SELECTED)&&$SELECTED=='Smoking')?"selected":"").">".($ln=="EN" ? "Smoking" : "Fumar")."</option>
        </select>";
        return $result;
    }


    function getSpecialOccasionDropDown($db, $arg, $ln="EN") {
        extract($arg);
        $ELE_ID = isset($ELE_ID) ? $ELE_ID : "OCCASION";
        $result = "
        <select name='$ELE_ID' id='$ELE_ID'>
			<option value=''>".($ln=="EN" ? "No preferences" : "Ninguna")."</option>
			<option value='Anniversary' ".((isset($SELECTED)&&$SELECTED=='Anniversary')?"selected":"").">".($ln=="EN" ? "Anniversary" : "Aniversario")."</option>
			<option value='Honeymoon' ".((isset($SELECTED)&&$SELECTED=='Honeymoon')?"selected":"").">".($ln=="EN" ? "Honeymoon" : "Luna de miel")."</option>
            <option value='Birthday' ".((isset($SELECTED)&&$SELECTED=='Birthday')?"selected":"").">".($ln=="EN" ? "Birthday" : "Cumpleaños")."</option>
        </select>";
        return $result;
    }

    function sendInternalEmail($arg) {
        extract($arg);
        global $EMAIL_GLOBAL_RES, $clsGlobal;

        $FORM = (isset($FORM) && trim($FORM)!="") ? $FORM : $EMAIL_GLOBAL_RES;
        $CC = (isset($CC) && trim($CC)!="") ? $CC : ""; $BCC = "";
        $BCC = (isset($BCC) && trim($BCC)!="") ? $BCC : "nisenbaummirek@gmail.com";
        //$BCC = (isset($BCC) && trim($BCC)!="") ? $BCC : "nisenbaummirek@gmail.com; jaunsarria@gmail.com";
        //$BCC = (isset($BCC) && trim($BCC)!="") ? $BCC : "mirek@artbymobile.com";
        //$BCC = (isset($BCC) && trim($BCC)!="") ? $BCC : "mirek@artbymobile.com; juan.sarria@everlivesolutions.com";

        $HEADER  = "From: {$FORM} \n";
        $HEADER .= "Cc: {$CC} \n";
        $HEADER .= "Bcc: {$BCC} \n";
        $HEADER .= "Content-type: text/html\n";

        $BODY = "
            <HTML>
                <HEAD>
                  <meta charset='utf-8'>
                </HEAD>
                <BODY>
                    {$MESSAGE}
                </BODY>
            </HTML>
        ";

        //$_SESSION['SENT'][] = $SUBJECT;

        mail($TO, $SUBJECT, $BODY, $HEADER);
    }

    function exception_error_handler($severity, $message, $file, $line) {
      return;
    }

    function sendEmail($arg) {
        extract($arg);
        
        if (isset($IS_INTERNAL)&&$IS_INTERNAL==1) {

          $this->sendInternalEmail($arg);

        } else {
          set_error_handler(array($this, "exception_error_handler"));

          global $EMAIL_GLOBAL_RES, $clsGlobal;

          $auth = array("api_key" => "7d024234924c83681099369b885e9c2ba7b6d57c8f62f42c");
          $wrap = new CS_REST_Transactional_ClassicEmail($auth, NULL);

          $PROP_ID = isset($PROP_ID) ? $PROP_ID : 0;
          $FROM = "reservations@excellence-resorts.com";//(isset($FORM) && trim($FORM)!="") ? $FORM : $EMAIL_GLOBAL_RES;
          $CC = (isset($CC) && trim($CC)!="") ? $CC : ""; $BCC = "";
          $BCC = (isset($BCC) && trim($BCC)!="") ? $BCC : "nisenbaummirek@gmail.com";
          $BODY = "
              <HTML>
                  <HEAD>
                    <meta charset='utf-8'>
                  </HEAD>
                  <BODY>
                      {$MESSAGE}
                  </BODY>
              </HTML>
          ";

          $SEND_TO = explode(",",$TO);
          $COPY_TO = explode(",",$CC);
          $BCC_TO = explode(",",$BCC);

          $simple_message = array(
            "From" => $FROM,
            "Subject" => $SUBJECT,
            "To" => count($SEND_TO)==1 ? $TO : $SEND_TO,
            "CC" => count($COPY_TO)==1 ? $CC : $COPY_TO,
            "BCC" => count($BCC_TO)==1 ? $BCC : $BCC_TO,
            "HTML" => $BODY
          );

          $guest_url = "http://".$_SERVER['SERVER_NAME']."/ibe/index.php?PAGE_CODE=ws.getGuest&EMAIL=".$SEND_TO[0];
          $guest_info = file_get_contents($guest_url);
          $guest = json_decode($guest_info, true);

          if (strstr($_SERVER["HTTP_HOST"],"excellence")!==FALSE || strstr($_SERVER["HTTP_HOST"],"locateandshare")!==FALSE || strstr($_SERVER["HTTP_HOST"],"205.186.160.44")!==FALSE) {
              $list_ID = "6449300f406db9b6b0802848b75a3793";
              if ($PROP_ID==1) {
                  $group_name = 'Excellence Riviera Cancun';
              } else if ($PROP_ID==2) {
                  $group_name = 'Excellence Playa Mujeres';
              } else if ($PROP_ID==3) {
                  $group_name = 'Excellence Punta Cana';
              } else if ($PROP_ID==6) {
                  $group_name = 'Excellence El Carmen';
              } else if ($PROP_ID==7) {
                  $group_name = 'Excellence Oyster Bay';
              } else {
                  $group_name = 'Excellence Resorts';
              }
          } else if (strstr($_SERVER["HTTP_HOST"],"belovedhotels")!==FALSE || strstr($_SERVER["HTTP_HOST"],"hoopsydoopsy")!==FALSE || strstr($_SERVER["HTTP_HOST"],"205.186.163.157")!==FALSE) {
              $group_name = 'Beloved Playa Mujeres';
              $list_ID = "8243243472ae3d93864d8bb3ba542efc";
          } else if (strstr($_SERVER["HTTP_HOST"],"finest")!==FALSE) {
              $group_name = 'Finest Playa Mujeres';
              $list_ID = "106e90d3cea4e9c862ec55716a664339";
          }

          $add_recipients_to_subscriber_list_ID = isset($guest["MAILING_LIST"])&&(int)$guest["MAILING_LIST"]==1 ? $list_ID : ""; # optional, make sure you have permission

          $result = $wrap->send($simple_message, $group_name, $add_recipients_to_subscriber_list_ID);

          //ob_start();print_r($result);print_r($guest);print $guest_url."<br>MAILING_LIST:".$guest["MAILING_LIST"];print_r($simple_message);print_r($arg);$DEBUG = ob_get_clean();mail("jaunsarria@gmail.com", "Email sent", $DEBUG);
          //ob_start();print_r($result);$DEBUG = ob_get_clean();mail("jaunsarria@gmail.com", "Email sent", $DEBUG);

          restore_error_handler();

          if (isset($result->http_status_code) && (int)$result->http_status_code=="E" ) {
              ob_start();print_r($result);print_r($simple_message);$DEBUG = ob_get_clean();
              mail("jaunsarria@gmail.com, mirek@basedesign.com", "Sending internal email >>> $SUBJECT", $DEBUG);
              $this->sendInternalEmail($arg);
          }

        }

    }

    function createPwd($ID) {
        $PASSWORD = $ID;
        for ($p=0; $p < 5; ++$p) $PASSWORD .= chr(mt_rand(65,90));
        return $PASSWORD;
    }

    function jsonDecode(&$STR, $cleanUp=true) {
        //$STR = str_replace(array("\r\n",'"null"','null',"\t"),array("",'""','""',""),$STR);
		$STR = str_replace(array("\r\n","\t","\r","\n"),array("<br>","<br>","<br>","<br>"),$STR);
		$STR = preg_replace(array("/\"null\"/","/\:\s*null/"),array('""',':""'),$STR);
        $STR = trim($STR);
        $json = json_decode($STR, true);
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                break;
            case JSON_ERROR_DEPTH:
                $json = array("error"=>"Maximum stack depth exceeded");
                break;
            case JSON_ERROR_STATE_MISMATCH:
                $json = array("error"=>"Underflow or the modes mismatch");
                break;
            case JSON_ERROR_CTRL_CHAR:
                $json = array("error"=>"Unexpected control character found");
                break;
            case JSON_ERROR_SYNTAX:
                $json = array("error"=>"Syntax error, malformed JSON");
                break;
            case JSON_ERROR_UTF8:
                $json = array("error"=>"Malformed UTF-8 characters, possibly incorrectly encoded");
                break;
            default:
                $json = array("error"=>"Unknown error");
                break;
        }

        if (json_last_error()==0) {
            // CLEAN CC NUMBER
            if ($cleanUp) $this->cleanUnwantedData($json);
        } else {
            print "<!-- BAD JSON [".json_last_error()."]:\n\n".$STR."\n\n -->";
        }
        //print "<!-- JSON:\n\n ".$STR." \n\n -->";
        return $json;
    }

    function jsonEncode($ARRAY) {
        $this->cleanUnwantedData($ARRAY);
        $JSON = json_encode($ARRAY);
        $JSON = $this->cleanJSON($JSON);
        return $JSON;
    }

    function cleanJSON($JSON) {
        $JSON = str_replace(array("\r\n","\t","\r","\n"),array("<br>","<br>","<br>","<br>"),$JSON);
        return $JSON;
    }

    function cleanUnwantedData(&$ARRAY) {
        if (is_array($ARRAY)) {
            if (isset($ARRAY['RESERVATION'])&&isset($ARRAY['RESERVATION']['PAYMENT'])&&isset($ARRAY['RESERVATION']['PAYMENT']['CC_NUMBER'])) {
                $ARRAY['RESERVATION']['PAYMENT']['CC_NUMBER'] = last4($ARRAY['RESERVATION']['PAYMENT']['CC_NUMBER']);
            }
        }
    }

    function setActive($db, $arg) {
        extract($arg);

        if ($TABLE=="" || $ID==0) return -1;

        $query = "UPDATE {$TABLE} SET IS_ACTIVE='{$ACTIVE}' WHERE ID='{$ID}'";

        $arr = array('query' => $query);
        $result = dbExecute($db, $arr);

        if ((int)$result != 1) { 
            return -1;
        } else {
            return $ACTIVE;
        }
    }

    function logMe($str = "", $myFile = "log.txt") {  
        $fh = fopen($_SERVER["DOCUMENT_ROOT"]."/ibe/log/".$myFile, 'a') or die("can't open file");
        $stringData = "{$str}\n";
        fwrite($fh, $stringData);
        fclose($fh);
    }

    function get_Uploads($db, $arg) {
        global $clsUploads;
        extract($arg);
        $result = array();
        $IRSET = $clsUploads->getByParent($db, array("PARENT_ID"=>$PARENT_ID,"TYPE"=>$TYPE));
        if ($IRSET['iCount']!=0) {
            while ($irow = $db->fetch_array($IRSET['rSet'])) { 
                $result[$irow['ID']] = "{$LOCATION}{$irow['NAME']}";
            }
        }
        return $result;
    }

    function nl2br($str) {
        return str_replace("\r\n","<br>",$str);  
    }

    function br2nl($str) {
        return str_replace(array("<br>","&lt;br&gt;"),array("\r\n","\r\n"),$str);  
    }

    function updateMetaIO($CHECK_IN, $PROP=array()) {
        ob_start();

        global $clsGlobal;
        include_once $_SERVER["DOCUMENT_ROOT"]."/ibe/meta_io/fns.php";

        $FILES = array();
        $PROP = count($PROP)==0 ? getMetaIOProp(1) : $PROP;
        //print "PROP: <pre>";print_r($PROP);print "</pre>";

        foreach ($PROP["ID"] as $i => $PROP_ID) {
            $CODE = $PROP["CODE"][$i] == "LAM" ? "TBH" : $PROP["CODE"][$i];
            $FILE_NAME = $_SERVER['DOCUMENT_ROOT']."/ibe/meta_io/data/".$CODE."-".substr($CHECK_IN, 0, 7).".json";
            //print "<br>FILE_NAME: ".$FILE_NAME;

            if (file_exists($FILE_NAME)) {
                $JSON = file_get_contents($FILE_NAME);
                $DATA = json_decode($JSON, true);
                $CHECK_OUT = addUnitsToDate($CHECK_IN, "+1");

                $FILES[$FILE_NAME] = getMetaIORooms($DATA, $PROP_ID, $CHECK_IN, $CHECK_OUT);

                //print "<pre>";print_r($FILES);print "</pre>";
                //print "<br>Saving...";
                saveMetaIO($FILES);
            }
        }

        //print "<pre>";print_r($FILES);print "</pre>";

        $DEBUG = ob_get_clean();
        //mail("jaunsarria@gmail.com","updateMetaIO",$DEBUG);
    }
}

global $clsGlobal;
$clsGlobal = new globalfn;
?>