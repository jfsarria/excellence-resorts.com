<?
/*
 * Revised: May 15, 2011
 */

 // http://www.locateandshare.com/ibe/GeoLiteCity/sample_city.php?IP=92.131.12.146

if (isset($_DATA['YEAR']) && (int)$_DATA['YEAR']!=0) {
?>
<style>
.country_group {
    margin-bottom:20px;
}
.country_group .gname {
    padding-bottom:10px;
}
</style>
<fieldset>
    <legend>Geo Targeting</legend>
    <div class="fieldset">
        <div class="label">
        <?
        $GROPUS = array(
            "AA"=>"Prime Countries",
            "LA"=>"Latin America",
            "EU"=>"Europe",
            "--"=>"Rest of the world"
        );  
        foreach ($GROPUS as $GROUP => $GROUP_NAME) { ?>
            <table id='country_group_<? print $GROUP ?>' class="country_group pickList" width='100%'>
            <tr><td colspan="10" class='gname'><b><? print $GROUP_NAME ?></b>&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' onclick="ibe.select.checkCountries('<? print $GROUP ?>', true)">Check all</a>&nbsp;-&nbsp;<a href='javascript:void(0)' onclick="ibe.select.checkCountries('<? print $GROUP ?>', false)">Uncheck all</a></td></tr>
            <tr>
            <?
            $COUNTRIES = $clsClasses->getCountries($db, array("CLASS_ID"=>$CLASS_ID,"AS_ARRAY"=>true)); 
            if (count($COUNTRIES)==0) $COUNTRIES['US'] = 1;
            //print "<pre>";print_r($COUNTRIES);print "<pre>";
            $RSET = $clsGlobal->getCountries($db, array("GROUP"=>$GROUP));
            if ( $RSET['iCount'] != 0 ) {
                $cnt=0;
                while ($row = $db->fetch_array($RSET['rSet'])) {
                    $CHECKED = (array_key_exists($row['CODE'],$COUNTRIES)) ? "checked" : "";
                    print "<td width='25%' class='pickListItem i{$cnt}' nowrap><span><input type='checkbox' name='COUNTRY_CODE[]' value='{$row['CODE']}' {$CHECKED}></span>&nbsp;{$row['NAME']}</td>";
                    if (fmod(++$cnt,4)==0) print "</tr><tr>";
                }
            }
            ?>
            </tr>
            </table>
        <? } ?>
        </div>
    </div>
</fieldset>
<? } ?>