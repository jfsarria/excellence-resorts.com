<?
/*
 * Revised: May 01, 2011
 */

if (isset($_DATA['YEAR']) && (int)$_DATA['YEAR']!=0) {
?>
<fieldset>
    <legend>Applicable to Room Type</legend>
    <div class="fieldset">
        <div class="label">
            <table class="pickList" width='100%'>
            <tr>
            <?
            $RSET = $clsRooms->getByProperty($db, array("PROP_ID"=>$PROP_ID));
            if ( $RSET['iCount'] != 0 ) {
                $cnt=0;
                while ($row = $db->fetch_array($RSET['rSet'])) {
                    $NAME = _d($row['NAME_'.$_IBE_LANG],$row['NAME_EN']);
                    print "<td width='50%' class='pickListItem i{$cnt}'><span><input type='radio' name='ROOM_ID' value='{$row['ID']}' ".(  (isset($_DATA['ROOM_ID']) && (int)$row['ID']==(int)$_DATA['ROOM_ID']  )?"checked":"")."></span>&nbsp;{$NAME}</td>";
                    if (fmod(++$cnt,2)==0) print "</tr><tr>";
                }
            }
            ?>
            </tr>
            </table>
        </div>
    </div>
</fieldset>
<? } ?>