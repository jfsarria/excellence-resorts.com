<?
/*
 * Revised: Sep 18, 2011
 *          May 26, 2016
 */

ob_start();

if ($MODIFY!="") {
    $showEdit = true;

    if ($SUBMIT=="SUBMIT") {
        $isOk = false;

        $GUEST = array("ID"=>$GUEST['ID']);

        if (isset($_DATA['RES_GUEST_TITLE'])) $GUEST["TITLE"] = $_DATA['RES_GUEST_TITLE'];
        if (isset($_DATA['RES_GUEST_FIRSTNAME'])) $GUEST["FIRSTNAME"] = $_DATA['RES_GUEST_FIRSTNAME'];
        if (isset($_DATA['RES_GUEST_LASTNAME'])) $GUEST["LASTNAME"] = $_DATA['RES_GUEST_LASTNAME'];
        if (isset($_DATA['RES_GUEST_EMAIL'])) $GUEST["EMAIL"] = $_DATA['RES_GUEST_EMAIL'];
        if (isset($_DATA['RES_GUEST_PASSWORD'])) $GUEST["PASSWORD"] = $_DATA['RES_GUEST_PASSWORD'];
        if (isset($_DATA['RES_GUEST_LANGUAGE'])) $GUEST["LANGUAGE"] = $_DATA['RES_GUEST_LANGUAGE'];
        if (isset($_DATA['RES_GUEST_ADDRESS'])) $GUEST["ADDRESS"] = $_DATA['RES_GUEST_ADDRESS'];
        if (isset($_DATA['RES_GUEST_CITY'])) $GUEST["CITY"] = $_DATA['RES_GUEST_CITY'];
        if (isset($_DATA['RES_GUEST_STATE'])) $GUEST["STATE"] = $_DATA['RES_GUEST_STATE'];
        if (isset($_DATA['RES_GUEST_COUNTRY'])) $GUEST["COUNTRY"] = $_DATA['RES_GUEST_COUNTRY'];
        if (isset($_DATA['RES_GUEST_ZIPCODE'])) $GUEST["ZIPCODE"] = $_DATA['RES_GUEST_ZIPCODE'];
        if (isset($_DATA['RES_GUEST_MAILING_LIST'])) $GUEST["MAILING_LIST"] = $_DATA['RES_GUEST_MAILING_LIST'];
        if (isset($_DATA['RES_GUEST_PHONE'])) $GUEST["PHONE"] = $_DATA['RES_GUEST_PHONE'];

        //print "<pre>";print_r($GUEST);print "</pre>";

        if (isset($_DATA['RES_GUEST_EMAIL']) && $_DATA['RES_GUEST_EMAIL'] == "") $error['RES_GUEST_EMAIL'] = 'RES_GUEST_EMAIL';

        $TRSET = $clsGuest->getByKey($db, array("WHERE"=>"EMAIL = '{$_DATA['RES_GUEST_EMAIL']}'"));
        if ($TRSET['iCount']==1) {
            $TROW = $db->fetch_array($TRSET['rSet']);
            if ($TROW['ID']==$GUEST['ID']) $isOk = true;
        } else if ($TRSET['iCount']==0) $isOk = true;

        if ((isset($error) && sizeof($error) != 0) || !$isOk) {
            include_once $isOk ? "inc/ibe.frm.err.php" : "inc/ibe.frm.dupl.php";
            $SUBMIT="";
        } else {
            $result = $clsGuest->modify($db, $GUEST); 

            if ((int)$result == 1) {
                include_once "inc/ibe.frm.ok.php";
                $showEdit = false;
            } else {
                print "<div id='s_notice' class='top_msg'>$result</div><br><br>";
            }
        }

        $RES_ASSIGN_TA_ID = isset($_DATA['RES_ASSIGN_TA_ID']) ? (int)$_DATA['RES_ASSIGN_TA_ID'] : 0;
        //print "RES_ASSIGN_TA_ID: $RES_ASSIGN_TA_ID";
        if ($RES_ASSIGN_TA_ID!=0) {
            $URL = "http://".$_SERVER['HTTP_HOST']."/ibe/index.php?PAGE_CODE=ws.getJSON&ID={$_DATA['ID']}&CODE={$_DATA['CODE']}&YEAR={$_DATA['YEAR']}";
            $JSON = file_get_contents($URL);
            $ARRAY = json_decode($JSON,true);
            //PRINT "<PRE>";print_r($ARRAY['RESERVATION']['FORWHOM']);PRINT "</PRE>";
            $ARRAY['RESERVATION']['FORWHOM']['RES_TO_WHOM'] = "TA";
            $ARRAY['RESERVATION']['FORWHOM']['RES_TA_ID'] = $RES_ASSIGN_TA_ID;
            $RSET = $clsTA->getById($db, array("ID"=>$RES_ASSIGN_TA_ID));
            $TAOBJ = ($RSET['iCount']>0) ? $db->fetch_array($RSET['rSet']) : array();
            $TA = array();
            if (count($TAOBJ)>0) {
                $TA = array(
                    "AGENCY_NAME"=>$TAOBJ["AGENCY_NAME"],
                    "TITLE"=>$TAOBJ["TITLE"],
                    "FIRSTNAME"=>$TAOBJ["FIRSTNAME"],
                    "LASTNAME"=>$TAOBJ["LASTNAME"],
                    "EMAIL"=>$TAOBJ["EMAIL"],
                    "AGENCY_PHONE"=>$TAOBJ["AGENCY_PHONE"],
                    "AGENCY_ADDRESS"=>$TAOBJ["AGENCY_ADDRESS"],
                    "AGENCY_CITY"=>$TAOBJ["AGENCY_CITY"],
                    "AGENCY_STATE"=>$TAOBJ["AGENCY_STATE"],
                    "AGENCY_ZIPCODE"=>$TAOBJ["AGENCY_ZIPCODE"],
                    "AGENCY_COUNTRY"=>$TAOBJ["AGENCY_COUNTRY"]
                );
            }
            $ARRAY['RESERVATION']['FORWHOM']['TA'] = $TA;
            //PRINT "<PRE>";print_r($ARRAY['RESERVATION']['FORWHOM']);PRINT "</PRE>";
            $RESERVATION = array(
                "ID"=>$_DATA['ID'],
                "RES_TABLE"=>"RESERVATIONS_{$_DATA['CODE']}_{$_DATA['YEAR']}",
                "OWNER_ID"=>$RES_ASSIGN_TA_ID,
                "ARRAY" => $clsGlobal->jsonEncode($ARRAY),
            );
            $result = $clsReserv->modifyReservation($db, $RESERVATION);
        }
    }

    if (!$isWEBSERVICE) {
        if ($showEdit) {
            include_once "inc/mods/".$_RESERVMOD[0]['rooms']['guest'];
        } else {
            print "
                <script>
                    document.location.href=\"{$THIS_PAGE}\"
                </script>
            ";
        }
    }
} else {
    ?>
    <fieldset>
        <legend>Guest Info</legend>
        <div class="fieldset">
            <div>Name: <? print $GUEST['TITLE']." ".$GUEST['FIRSTNAME']." ".$GUEST['LASTNAME'] ?></div>
            <div>Address: <? print $GUEST['ADDRESS']."<br>".appendToString($GUEST['CITY'],", ").appendToString($GUEST['STATE']," ").appendToString($GUEST['ZIPCODE'],", ").$GUEST['COUNTRY'] ?></div>
            <div>Phone: <? print $GUEST['PHONE'] ?></div>
            <div>Email: <? print $GUEST['EMAIL'] ?></div>
            <div>Language: <? print $GUEST['LANGUAGE'] ?></div>
            <div>Mailing List: <? print (int)$GUEST['MAILING_LIST']==1?"Yes":"No" ?></div>
            <? //if ($RESVIEW['STATUS_STR']=="booked") { ?>
            <div style='text-align:center;margin-top:10px'>
                <a href="<? print $THIS_PAGE."&MODIFY=GUEST"; ?>"><span class="button key">Modify</span></a>
                <? if ($GUEST['EMAIL']!="") { ?>
                    &nbsp;&nbsp;
                    <a href="javascript:void(0)" onClick="ibe.callcenter.sendGuestPwd('<? print $GUEST['EMAIL'] ?>')"><span class="button key">Send Password</span></a>
                <? } ?>
            </div>
            <? //} ?>
        </div>
    </fieldset>
<? } 
    
$RESULT = ob_get_clean();

if (!$isWEBSERVICE) print $RESULT;
    
?>