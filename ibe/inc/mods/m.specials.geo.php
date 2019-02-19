<?
/*
 * Revised: May 05, 2011
 */

 // http://www.locateandshare.com/ibe/GeoLiteCity/sample_city.php?IP=92.131.12.146

$isGEOState = (isset($_DATA['IS_GEO'])&&(int)$_DATA['IS_GEO']==1) ? true : false;
?>
<fieldset>
    <legend>Geo Targeting</legend>
    <div class="fieldset">
        <div class="label">
            <div>
                <div>Is US Geo-State Targeted&nbsp;<span><input type="checkbox" id="IS_GEO" name="IS_GEO" value="1" <? print $isGEOState ? "checked" : "" ?> onclick="ibe.select.isGEO(this, 'isGEOState')"></span></div>
                <div>If marked as Geo-state targeted, special will override any regular special but not private special</div>
            </div>
            <br>
            <div id="isGEOState" style='display:<? print $isGEOState ? "":"none" ?>'>
                <table class="pickList" width='100%'>
                <tr>
                <?
                $STATES = $clsSpecials->getStates($db, array("SPECIAL_ID"=>$SPECIAL_ID,"AS_ARRAY"=>true)); 
                $RSET = $clsGlobal->getStates($db);
                if ( $RSET['iCount'] != 0 ) {
                    $cnt=0;
                    while ($row = $db->fetch_array($RSET['rSet'])) {
                        $CHECKED = (array_key_exists($row['CODE'],$STATES)) ? "checked" : "";
                        print "<td width='25%' class='pickListItem i{$cnt}' nowrap><span><input type='checkbox' name='STATE_CODE[]' value='{$row['CODE']}' {$CHECKED}></span>&nbsp;{$row['NAME']}</td>";
                        if (fmod(++$cnt,4)==0) print "</tr><tr>";
                    }
                }
                ?>
                </tr>
                </table>
            </div>
        </div>
    </div>
</fieldset>