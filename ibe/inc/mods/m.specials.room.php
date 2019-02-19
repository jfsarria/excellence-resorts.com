<?
/*
 * Revised: May 01, 2011
 */
?>
<fieldset>
    <legend>Applicable to Room Type</legend>
    <div class="fieldset">
        <div class="label">
            <div>
                Applicable to All&nbsp;<span><input type="checkbox" id="ALL_ROOMS" name="ALL_ROOMS" value="1" <? print (isset($_DATA['ALL_ROOMS'])&&(int)$_DATA['ALL_ROOMS']==1) ? "checked" : "" ?>></span>
            </div>
            <br>
            <table class="pickList" width='100%'>
            <tr>
            <?
            $SEASONS = $clsSpecials->getRooms($db, array("SPECIAL_ID"=>$SPECIAL_ID,"AS_ARRAY"=>true)); 
            $RSET = $clsRooms->getByProperty($db, array("PROP_ID"=>$PROP_ID));
            if ( $RSET['iCount'] != 0 ) {
                $cnt=0;
                while ($row = $db->fetch_array($RSET['rSet'])) {
                    $NAME = _d($row['NAME_'.$_IBE_LANG],$row['NAME_EN']);
                    $CHECKED = (array_key_exists($row['ID'],$SEASONS)) ? "checked" : "";
                    print "<td width='50%' class='pickListItem i{$cnt}'><span><input type='checkbox' name='ROOM_ID[]' value='{$row['ID']}' {$CHECKED}></span>&nbsp;{$NAME}</td>";
                    if (fmod(++$cnt,2)==0) print "</tr><tr>";
                }
            }
            ?>
            </tr>
            </table>
        </div>
    </div>
</fieldset>