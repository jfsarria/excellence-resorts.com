<?
/*
 * Revised: May 11, 2011
 */

if (isset($_DATA['YEAR']) && (int)$_DATA['YEAR']!=0) {
?>
<fieldset>
    <legend>Applicable to Season(s)</legend>
    <div class="fieldset">
        <div class="label">
            <table class="pickList" width='100%'>
            <tr>
            <?
            $SEASONS = $clsClasses->getSeasons($db, array("CLASS_ID"=>$CLASS_ID,"AS_ARRAY"=>true)); 
            $RSET = $clsSeasons->getByProperty($db, array("PROP_ID"=>$PROP_ID,"YEAR"=>$_DATA['YEAR']));
            if ( $RSET['iCount'] != 0 ) {
                $cnt=0;
                while ($row = $db->fetch_array($RSET['rSet'])) {
                    $CHECKED = (array_key_exists($row['ID'],$SEASONS)) ? "checked" : "";
                    print "<td width='50%' class='pickListItem i{$cnt}'><span><input type='checkbox' name='SEASON_ID[]' value='{$row['ID']}' {$CHECKED}></span>&nbsp;{$row['NAME']}</td>";
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