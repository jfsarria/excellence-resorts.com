<?
/*
 * Revised: Oct 20, 2011
 */

$agents = $clsGlobal->getCallCenterAgents($db);
//print "<pre>";print_r($agents);print "</pre>";
?>

<fieldset>
    <legend>Reservation History</legend>
    <div class="fieldset">

    <table width="100%" id='searchReserTbl' cellpadding="0" cellspacing="0">
    <tr class="listRowHdr">
        <th>&nbsp;</th>
        <th>&nbsp;&nbsp;Action</th>
        <th>Date</th>
        <th>By</th>
    </tr>

    <?
        //$PROPERTIES = $clsReserv->getProperties($db);
        //while ($prow = $db->fetch_array($PROPERTIES['rSet'])) { $PROPERTIES[$prow['CODE']] = $prow['ID']; }
        //print "<h3>searchReservation</h3>";
        $YEARS = array((int)$YEAR-1,$YEAR,(int)$YEAR+1);
        $RSET = $clsReserv->searchReservation($db, array(
            //"PROP_IDs"=>array($RES_PROP_ID),
            "YEARS"=>$YEARS,
            "RESNUM"=>$RESVIEW['NUMBER'],
            "GROUPED"=>0
        ));
        $IDs = array();
        $HISTORY = array();
        $CODES = array();
        while ($row = $db->fetch_array($RSET['rSet'])) {
            //print "<pre>";print_r($row);print "</pre>";
            array_push($IDs, $row['ID']);
            $HISTORY[$row['ID']]['HOTEL'] = $row['HOTEL'];
            $HISTORY[$row['ID']]['STATUS_STR'] = $row['STATUS_STR'];
            $HISTORY[$row['ID']]['CREATED'] = $row['CREATED'];
            $HISTORY[$row['ID']]['MODIFIED'] = $row['MODIFIED'];
            array_push($CODES, $row['HOTEL']);
        }
        //print "<h3>getSingleReservation</h3>";
        foreach ($CODES as $HOTEL) {
            $arg = array(
                "IDs"=>$IDs,
                "FIELDS"=>"ID, NOTES, CREATED_BY, MODIFIED_BY",
                "CODE"=>$HOTEL,
                "YEARS"=>$YEARS
            );
            //print "<pre>";print_r($arg);print "</pre>";
            $ISET = $clsReserv->getSingleReservation($db, $arg);
            $cnt=1;
            $qty=1;
            while ($row = $db->fetch_array($ISET['rSet'])) {
                $CREATED = shortDateTime($HISTORY[$row['ID']]['CREATED']);
                $MODIFIED = shortDateTime($HISTORY[$row['ID']]['MODIFIED']);

                if ($qty==1) {
                    $AGENT_NAME = isset($agents[$row['CREATED_BY']]) ? $agents[$row['CREATED_BY']]['FIRSTNAME'] : "GP";
                    ?>
                    <tr class="row<? print $cnt ?>">
                        <td><? print $HISTORY[$row['ID']]['HOTEL'] ?></td>
                        <td>Created</td>
                        <td nowrap><? print $CREATED ?></td>
                        <td><? print $AGENT_NAME ?></td>
                    </tr>
                    <?
                    $cnt *= -1;
                    ++$qty;
                }

                $STATUS_STR = $HISTORY[$row['ID']]['STATUS_STR'];
                if ($STATUS_STR=="arrived") $STATUS_STR = "Booked";
                //if ($qty==1 && $CREATED!=$MODIFIED) $STATUS_STR = "Modified";
                $AGENT_NAME = isset($agents[$row['MODIFIED_BY']]) ? $agents[$row['MODIFIED_BY']]['FIRSTNAME'] : "GP";
                ?>
                <tr class="row<? print $cnt ?>">
                    <td><? print $HISTORY[$row['ID']]['HOTEL'] ?></td>
                    <td><? print ucwords($STATUS_STR) ?></td>
                    <td nowrap><? print $MODIFIED ?></td>
                    <td><? print $AGENT_NAME ?></td>
                </tr>
                <? if (trim($row['NOTES'])!="") { ?>
                <tr><td colspan='10'><i>Notes: <? print $row['NOTES'] ?></i></td></tr>
                <? } ?>
                <tr><td colspan='10' style='background-color:#DFE1E3;height:1px'></td></tr>
                <?
                $cnt *= -1;
                ++$qty;
            }
        }
    ?>
    </table>
    </div>
</fieldset>