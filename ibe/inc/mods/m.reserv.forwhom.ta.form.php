<?
/*
* Revised: Aug 05, 2011
*/

ob_start();

$isOk = false;
$result = array();

if (isset($MODIFY) && $MODIFY!="") {
    $showEdit = true;

    if ($SUBMIT=="SUBMIT") {

        $TA['ID'] = ((int)$ID!=0) ? $ID : dbNextId($db);

        /* AGENCY INFORMATION */
        if (isset($_REQUEST['IATA'])) $TA['IATA'] = $_REQUEST['IATA'];
        if (isset($_REQUEST['AGENCY_NAME'])) $TA['AGENCY_NAME'] = $_REQUEST['AGENCY_NAME'];
        if (isset($_REQUEST['AGENCY_PHONE'])) $TA['AGENCY_PHONE'] = $_REQUEST['AGENCY_PHONE'];
        if (isset($_REQUEST['AGENCY_ADDRESS'])) $TA['AGENCY_ADDRESS'] = $_REQUEST['AGENCY_ADDRESS'];
        if (isset($_REQUEST['AGENCY_CITY'])) $TA['AGENCY_CITY'] = $_REQUEST['AGENCY_CITY'];
        if (isset($_REQUEST['AGENCY_STATE'])) $TA['AGENCY_STATE'] = $_REQUEST['AGENCY_STATE'];
        if (isset($_REQUEST['AGENCY_COUNTRY'])) $TA['AGENCY_COUNTRY'] = $_REQUEST['AGENCY_COUNTRY'];
        if (isset($_REQUEST['AGENCY_ZIPCODE'])) $TA['AGENCY_ZIPCODE'] = $_REQUEST['AGENCY_ZIPCODE'];
        if (isset($_REQUEST['COMMISSION_RATE'])) $TA['COMMISSION_RATE'] = $_REQUEST['COMMISSION_RATE'];
        if (isset($_REQUEST['IN_MEXICO'])) $TA['IN_MEXICO'] = $_REQUEST['IN_MEXICO'];
        /* CONTACT INFORMATION */
        if (isset($_REQUEST['FIRSTNAME'])) $TA['FIRSTNAME'] = $_REQUEST['FIRSTNAME'];
        if (isset($_REQUEST['LASTNAME'])) $TA['LASTNAME'] = $_REQUEST['LASTNAME'];
        if (isset($_REQUEST['EMAIL'])) $TA['EMAIL'] = $_REQUEST['EMAIL'];
        if (isset($_REQUEST['PASSWORD'])) $TA['PASSWORD'] = $_REQUEST['PASSWORD'];
        if (isset($_REQUEST['COMMENTS'])) $TA['COMMENTS'] = $_REQUEST['COMMENTS'];
        if (isset($_REQUEST['HEAR_ABOUT_US'])) $TA['HEAR_ABOUT_US'] = $_REQUEST['HEAR_ABOUT_US'];
        /* OTHER INFORMATION */
        if (isset($_REQUEST['IS_CONFIRMED'])) $TA['IS_CONFIRMED'] = $_REQUEST['IS_CONFIRMED'];

        //print "<pre>";print_r($TA);print "</pre>";

        //if (isset($_DATA['EMAIL']) && $_DATA['EMAIL'] == "") $error['EMAIL'] = 'EMAIL';

        if (isset($error) && sizeof($error) != 0) {
            include_once "inc/ibe.frm.err.php";
        } else {

            if (isset($TA['EMAIL']) && trim($TA["EMAIL"])!="") {
                $TRSET = $clsTA->getByKey($db, array("WHERE"=>"EMAIL = '{$TA['EMAIL']}'"));
                if ($TRSET['iCount']==1) {
                    $TROW = $db->fetch_array($TRSET['rSet']);
                    if ($TROW['ID']==$TA['ID']) {
                        $isOk = true;
                    } else $result['error'] = "Email Already Taken";
                } else if ($TRSET['iCount']==0) $isOk = true;
            } else $isOk = true;

            if ((isset($error) && sizeof($error) != 0) || !$isOk) {
                include_once $isOk ? "inc/ibe.frm.err.php" : "inc/ibe.frm.dupl.php";
            } else {
                if (isset($TA['IS_CONFIRMED'])&&(int)$TA['IS_CONFIRMED'] == -1) {
                    $retval = $clsTA->deleteById($db, $TA); 
                } else {
                    $retval = $clsTA->save($db, $TA); 

                    if ((int)$retval == 1) {
                        include_once "inc/ibe.frm.ok.php";
                        $result = $TA;
                        $showEdit = false;
                        if (isset($TA['IS_CONFIRMED'])&&(int)$TA['IS_CONFIRMED']==1) {
                            $clsTA->sendApproval($db, array("ID"=>$TA['ID']));
                        }
                    } else {
                        $result['error'] = $retval;
                        print "<div id='s_notice' class='top_msg'>$retval</div><br><br>";
                    }
                }
            }
        }
    }

    if (!$isWEBSERVICE && $isOk) {
        if (isset($TA['IS_CONFIRMED'])&&(int)$TA['IS_CONFIRMED'] == -1) {
            print "
                <script>
                    document.location.href=\"?PAGE_CODE=search_ta\"
                </script>
            ";
        } else {
            print "
                <script>
                    document.location.href=\"?PAGE_CODE=edit_ta&ID={$TA['ID']}\"
                </script>
            ";
        }
    }
} 

if (!$isOk && !$isWEBSERVICE) {
    ?>
    <table width="100%" cellspacing="4" cellpadding="1">
    <tr>
        <td colspan="10">
            <h3 class="h3_hdr">AGENCY INFORMATION</h3>
        </td>
    </tr>
    <tr>
        <td valign="top" nowrap>
            <span class="astrik">*</span>IATA
        </td>
        <td width="100%" valign="top">
            <input type="text" id="IATA" name="IATA" class="med" title="Agency IATA" value="<? print isset($TA['IATA'])?$TA['IATA']:"" ?>">
        </td>
    </tr>
    <tr>
        <td valign="top" nowrap>
            <span class="astrik">*</span>Agency name
        </td>
        <td width="100%" valign="top">
            <input type="text" id="AGENCY_NAME" class="large" title="Agency Name" name="AGENCY_NAME" value="<? print isset($TA['AGENCY_NAME'])?$TA['AGENCY_NAME']:"" ?>">
        </td>
    </tr>
    <tr>
        <td valign="top" nowrap>
            <span class="astrik">*</span>Agency Phone
        </td>
        <td width="100%" valign="top">
            <input type="text" id="AGENCY_PHONE" class="large" title="Agency Phone" name="AGENCY_PHONE" value="<? print isset($TA['AGENCY_PHONE'])?$TA['AGENCY_PHONE']:"" ?>">
        </td>
    </tr>
    <tr>
        <td valign="top" nowrap>
            <span class="astrik">*</span>Address
        </td>
        <td width="100%" valign="top">
            <input type="text" id="AGENCY_ADDRESS" class="large" title="Address" name="AGENCY_ADDRESS" value="<? print isset($TA['AGENCY_ADDRESS'])?$TA['AGENCY_ADDRESS']:"" ?>">
        </td>
    </tr>
    <tr>
        <td valign="top" nowrap>
            <span class="astrik">*</span>Country
        </td>
        <td width="100%" valign="top">
            <? print $clsGlobal->getCountriesDropDown($db, array("ELE_ID"=>"AGENCY_COUNTRY")); ?>
        </td>
    </tr>
    <tr>
        <td valign="top" nowrap>
            <span class="astrik">*</span>State/Province
        </td>
        <td width="100%" valign="top">
            <input type="text" id="AGENCY_STATE" class="med" title="State/Province" name="AGENCY_STATE" value="<? print isset($TA['AGENCY_STATE'])?$TA['AGENCY_STATE']:"" ?>">
            <? 
                print $clsGlobal->getStatesDropDown($db, array("ELE_ID"=>"US_STATES","CODE"=>"US")); 
                print $clsGlobal->getStatesDropDown($db, array("ELE_ID"=>"CA_STATES","CODE"=>"CA")); 
                print $clsGlobal->getStatesDropDown($db, array("ELE_ID"=>"MX_STATES","CODE"=>"MX")); 
            ?>
        </td>
    </tr>
    <tr>
        <td valign="top" nowrap>
            <span class="astrik">*</span>City
        </td>
        <td width="100%" valign="top">
            <input type="text" id="AGENCY_CITY" class="med" title="City" name="AGENCY_CITY" value="<? print isset($TA['AGENCY_CITY'])?$TA['AGENCY_CITY']:"" ?>">
        </td>
    </tr>
    <tr>
        <td valign="top" nowrap>
            <span class="astrik">*</span>Zip/Postal code
        </td>
        <td width="100%" valign="top">
            <input type="text" id="AGENCY_ZIPCODE" class="med" title="Zip/Postal code" name="AGENCY_ZIPCODE" value="<? print isset($TA['AGENCY_ZIPCODE'])?$TA['AGENCY_ZIPCODE']:"" ?>">
        </td>
    </tr>
    <tr>
        <td valign="top" nowrap>
            Is agent/agency<br>located in Mexico
        </td>
        <td width="100%" valign="top">
            <span><input type="radio" value="1" name="IN_MEXICO" <? if (isset($TA['IN_MEXICO'])&&(int)$TA['IN_MEXICO']==1) print "checked" ?>></span>&nbsp;Yes
            &nbsp;&nbsp;&nbsp;
            <span><input type="radio" value="0" name="IN_MEXICO" <? if (isset($TA['IN_MEXICO'])&&(int)$TA['IN_MEXICO']==0) print "checked" ?>></span>&nbsp;No
        </td>
    </tr>
    <tr>
        <td valign="top" nowrap>
            <span class="astrik">*</span>Commission Rate
        </td>
        <td width="100%" valign="top">
            <input type="text" id="COMMISSION_RATE" class="small" title="Commission Rate" name="COMMISSION_RATE" value="<? print isset($TA['COMMISSION_RATE'])?$TA['COMMISSION_RATE']:"10" ?>">%
        </td>
    </tr>
    <tr>
        <td colspan="10">
            <h3 class="h3_hdr">CONTACT INFORMATION</h3>
        </td>
    </tr>
    <tr>
        <td valign="top" nowrap>
            <span class="astrik">*</span>First Name
        </td>
        <td width="100%" valign="top">
            <input type="text" id="FIRSTNAME" class="large" title="First Name" name="FIRSTNAME" value="<? print isset($TA['FIRSTNAME'])?$TA['FIRSTNAME']:"" ?>">
        </td>
    </tr>
    <tr>
        <td valign="top" nowrap>
            <span class="astrik">*</span>Last Name
        </td>
        <td width="100%" valign="top">
            <input type="text" id="LASTNAME" class="large" title="Last Name" name="LASTNAME" value="<? print isset($TA['LASTNAME'])?$TA['LASTNAME']:"" ?>">
        </td>
    </tr>
    <tr>
        <td valign="top" nowrap>
            <span class="astrik">*</span>E-mail
        </td>
        <td width="100%" valign="top">
            <input type="text" id="EMAIL" class="large" title="E-mail" name="EMAIL" value="<? print isset($TA['EMAIL'])?$TA['EMAIL']:"" ?>">
        </td>
    </tr>
    <tr>
        <td valign="top" nowrap>
            <span class="astrik">*</span>Password
        </td>
        <td width="100%" valign="top">
            <input type="text" id="PASSWORD" class="large" title="Password" name="PASSWORD" value="<? print isset($TA['PASSWORD'])?$TA['PASSWORD']:"" ?>">
            <? if (isset($TA['PASSWORD'])&&$TA['PASSWORD']!=""&&isset($TA['EMAIL'])&&$TA['EMAIL']!=""&&isset($TA['IS_CONFIRMED'])&&(int)$TA['IS_CONFIRMED']==1) { ?>
            &nbsp;<a href="javascript:void(0)" onClick="ibe.callcenter.sendTAPwd('<? print isset($TA['EMAIL'])?$TA['EMAIL']:"" ?>')">Send Password in E-Mail</a>
            <? } ?>
        </td>
    </tr>
    <tr>
        <td colspan="10">
            <h3 class="h3_hdr">COMMENTS</h3>
        </td>
    </tr>
    <tr>
        <td colspan="10">
            <textarea style="width:100%;height:75px;" id="COMMENTS" name="COMMENTS"><? print isset($TA['COMMENTS'])?$TA['COMMENTS']:"" ?></textarea>
        </td>
    </tr>
    <tr>
        <td colspan="10">
            <h3 class="cufon">HOW DID YOU HEAR ABOUT US?</h3>
        </td>
    </tr>
    <tr>
        <td colspan="10">
            <textarea style="width:100%;height:75px;" id="HEAR_ABOUT_US" name="HEAR_ABOUT_US"><? print isset($TA['HEAR_ABOUT_US'])?$TA['HEAR_ABOUT_US']:"" ?></textarea>
        </td>
    </tr>
    </table>
    <? if ($_PAGE_CODE!="edit_ta") { ?>
        <div id="m_reserv_forwhom_newta_next">
            <span class="button" onclick='ibe.reserv.forWhom.Next_NewTA()'>Next Step &#187;</span>
        </div>
    <? } else { ?>
        <input type="hidden" name="ID" value="<? print $ID ?>">
    <? } ?>
    <script>
        <? if (isset($TA['AGENCY_COUNTRY'])) print "$('#AGENCY_COUNTRY').val('{$TA['AGENCY_COUNTRY']}') \n"; ?>

        function setStateDropDown() {
            var AGENCY_COUNTRY = $('#AGENCY_COUNTRY').val();
            $('#US_STATES,#CA_STATES,#MX_STATES,#AGENCY_STATE').hide();
            $('#'+AGENCY_COUNTRY+'_STATES').show();
            if (AGENCY_COUNTRY!="US"&&AGENCY_COUNTRY!="MX"&&AGENCY_COUNTRY!="CA") $('#AGENCY_STATE').show();
        }
        $('#AGENCY_COUNTRY').change(function(){
            setStateDropDown();
        });
        $('#US_STATES,#CA_STATES,#MX_STATES').change(function(){
            $('#AGENCY_STATE').val($(this).val());
        });
        setStateDropDown();
        <? if (isset($TA['AGENCY_STATE'])) print "$('#US_STATES,#CA_STATES,#MX_STATES,#AGENCY_STATE').val('{$TA['AGENCY_STATE']}'); \n"; ?>
        $('#STATES').show();
    </script>
    <? 
} 
    
$RESULT = ob_get_clean();

if (!$isWEBSERVICE) print $RESULT;
    
?>
