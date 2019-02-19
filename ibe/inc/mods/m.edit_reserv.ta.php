<?
$RES_TA_ID = (int)$RESERVATION['FORWHOM']['RES_TA_ID'];
if ($RES_TA_ID!=0) {
    $RSET = $clsTA->getById($db, array("ID"=>$RES_TA_ID));
    $ARRAY = array();
    $TA = ($RSET['iCount']>0) ? $db->fetch_array($RSET['rSet']) : array();

    if (count($TA)>0) {
    ?>
    <fieldset>
        <legend>Travel Agent</legend>
        <div class="fieldset">
            <div>IATA: <? print isset($TA['IATA'])?$TA['IATA']:"" ?></div>
            <div>Agency Name: <? print isset($TA['AGENCY_NAME'])?$TA['AGENCY_NAME']:"" ?></div>
            <div>Agency Phone: <? print isset($TA['AGENCY_PHONE'])?$TA['AGENCY_PHONE']:"" ?></div>
            <div>Agent Name: <? if (isset($TA['TITLE'])&&isset($TA['FIRSTNAME'])&&isset($TA['LASTNAME'])) print $TA['TITLE']." ".$TA['FIRSTNAME']." ".$TA['LASTNAME'] ?></div>
            <div>Agent E-mail: <? print isset($TA['EMAIL'])?$TA['EMAIL']:"" ?></div>
            <div style='text-align:center;margin-top:10px'>
                <a href="?PAGE_CODE=edit_ta&ID=<? print $RES_TA_ID; ?>"><span class="button key">Edit</span></a>
            </div>
        </div>
    </fieldset>
    <? 
    }
} 
?>