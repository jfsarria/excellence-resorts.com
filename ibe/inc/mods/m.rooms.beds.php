<?
/*
 * Revised: Apr 25, 2011
 */
?>
<fieldset>
    <legend>Bed Options</legend>
    <div class="fieldset">
        <div class="label">
        <?
        $BEDS = (isset($_DATA['BEDS'])&&is_array($_DATA['BEDS'])) ? $_DATA['BEDS'] : (isset($_DATA['BEDS']) ? explode(",", $_DATA['BEDS']) : array());
        $RSET = $clsRooms->getBedOptions($db, array("PROP_ID"=>$PROP_ID));
        while ($brow = $db->fetch_array($RSET['rSet'])) {
            ?>
            <table>
            <tr>
                <td><span><input type="checkbox" name="BEDS[]" value="<? print $brow['ID'] ?>" <? if (in_array($brow['ID'],$BEDS)) print "checked" ?>></span>&nbsp;</td>
                <td><? print $brow['NAME'] ?></td>
            </tr>
            </table>
            <?
        }
        ?>
        </div>
    </div>
</fieldset>