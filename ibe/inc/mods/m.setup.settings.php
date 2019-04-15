<?
/*
 * Revised: Dec 19, 2012
 *          Jul 22, 2016
 */
?>
<fieldset>
    <legend>Markup</legend>
    <div class="fieldset">
        <div class="label">
            <br>
            <table class="pickList" width='100%'>
            <tr>
            <?
            $MRSET = $clsSetup->getMarkups($db, array("PROP_ID"=>$PROP_ID,"asArray"=>true));
            for ($YY = date("Y")-1; $YY <= date("Y")+2; ++$YY) {
                if (!isset($MRSET[$YY])) $MRSET[$YY] = array("MARKUP"=>0);
            }
            //if (!isset($MRSET[date("Y")])) $MRSET[date("Y")] = array("MARKUP"=>0);
            //if (!isset($MRSET[date("Y")+1])) $MRSET[date("Y")+1] = array("MARKUP"=>0);
            $cnt=0;
            foreach ($MRSET as $YEAR => $ARR) {
                $MARKUP = $ARR['MARKUP'];
                print "<td width='20%' class='pickListItem' nowrap><b>$YEAR</b>&nbsp;<input type='text' id='MARKUP' name='MARKUP_{$YEAR}' value='{$MARKUP}' style='width:50px'>%</td>";
                if (fmod(++$cnt,5)==0) print "</tr><tr>";
            }            
            ?>  
            </tr>
            </table>
        </div>
    </div>
</fieldset>

<fieldset>
    <legend>CAP</legend>
    <div class="fieldset">
        
        <div class="field"><input type="text" id="LIMIT_MPRICE" name="LIMIT_MPRICE" value="<? print isset($_DATA['LIMIT_MPRICE']) ? $_DATA['LIMIT_MPRICE'] : "" ?>" class="small">% &nbsp; (0 without discounts)</div>
    </div>
</fieldset>
<fieldset>
    <legend>Global configuration of price modifiers</legend>
    <div class="fieldset">
        <?$RSET=$clsDiscounts->get_all_priority($db);
        //print_r($RSET);
        
        if ( $RSET['iCount'] != 0 ) { ?>
            <div class="listTbl">
            <table>
            <tr class="listRowHdr">
                <td>System</td>                
                <td>Priority</td>                
                
                <!-- <td>&nbsp;</td> -->
            </tr>
            <?
            $cnt=1;
            while ($row = $db->fetch_array($RSET['rSet'])) {
               
                ?>
                <tr class='listRow<? print $cnt ?>'>                    
                    <td align='right'>
                        <label><? print $row['SYSTEM'] ?></label>
                        <input type="hidden" id="C_SYSTEM[]" name="C_SYSTEM[]" value="<? print $row['SYSTEM'] ?>">
                        </td>
                    <td align='right'>
                        <select name="C_PRIORITY[]" id="C_PRIORITY[]"><? for ($t=1; $t<= 20; ++$t) print "<option value='{$t}' ".((isset($row['PRIORITY']) && (int)$row['PRIORITY']==$t) ? "selected" : "").">{$t}</option>"; ?></select>
                        <? //print $row['PRIORITY'] ?>
                        
                    </td>                     
                </tr>
                <?
                $cnt*=-1;
            }
            ?>
            </table>
            </div>
        <? }?>
    </div>
</fieldset>

<fieldset>
    <legend>Blocked IPs</legend>
    <div class="fieldset">
        <div class="label">IPs (Separate by comma or space)</div>
        <div class="field"><input type="text" id="BLOCK_IP" name="BLOCK_IP" value="<? print isset($_DATA['BLOCK_IP']) ? $_DATA['BLOCK_IP'] : "" ?>" class="full"></div>
    </div>
</fieldset>
