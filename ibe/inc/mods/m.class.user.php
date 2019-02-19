<?
/*
 * Revised: Aug 01, 2011
 */

if (isset($_DATA['YEAR']) && (int)$_DATA['YEAR']!=0) {
?>
<div style='display:none'>

<fieldset>
    <legend>Visible to these user types</legend>
    <div class="fieldset">
        <div class="label">
            <table class="pickList" width='80%'>
            <tr>
            <?
            $USERTYPES = $clsClasses->getUserTypes($db, array("CLASS_ID"=>$CLASS_ID,"AS_ARRAY"=>true)); 
            if (count($USERTYPES)==0) $USERTYPES['1'] = 1;
            $RSET = $clsGlobal->getUserTypes($db, array());
            if ( $RSET['iCount'] != 0 ) {
                $cnt=0;
                while ($row = $db->fetch_array($RSET['rSet'])) {
                    $CHECKED = "checked"; //(array_key_exists($row['ID'],$USERTYPES)) ? "checked" : "";
                    print "<td width='50%' class='pickListItem i{$cnt}' nowrap><span><input type='checkbox' name='USERTYPE_ID[]' value='{$row['ID']}' {$CHECKED}></span>&nbsp;{$row['TYPE_NAME']}</td>";
                    if (fmod(++$cnt,3)==0) print "</tr><tr>";
                }
            }
            ?>
            </tr>
            </table>
        </div>
    </div>
</fieldset>

</div>

<? } ?>