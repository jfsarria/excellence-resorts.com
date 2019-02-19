<?
/*
 * Revised: Aug 11, 2011
 */

$_DATA['YEARS'] = (isset($_DATA['YEARS'])&&$_DATA['YEARS']!="") ? $_DATA['YEARS'] : (int)date("Y");
if (!is_array($_DATA['YEARS'])) $_DATA['YEARS'] = explode(",",$_DATA['YEARS']);

$_DATA['GEOS'] =  (isset($_DATA['GEOS'])&&$_DATA['GEOS']!="") ? $_DATA['GEOS'] : "US";
if (!is_array($_DATA['GEOS'])) $_DATA['GEOS'] = explode(",",$_DATA['GEOS']);

$_DATA['SEASON'] = isset($_DATA['SEASON']) ? $_DATA['SEASON'] : "";
?>

<div class="fieldset">
    <div class="label">
        <b>Filter By:</b><br>
        <table id="YearsPickList" class="pickList">
        <tr>
        <td nowrap>Year:&nbsp;</td>
        <?
        $cnt=0;
        for ($YEAR=2011; $YEAR <= date("Y")+5; ++$YEAR) {
            $CHECKED = (in_array($YEAR,$_DATA['YEARS'])) ? "checked" : "";
            print "<td nowrap class='pickListItem i{$cnt}'><span><input type='checkbox' name='YEARS[]' value='{$YEAR}' id='cb_{$YEAR}' {$CHECKED}>&nbsp;{$YEAR}&nbsp;&nbsp;&nbsp;&nbsp;<span></td>";
            if (fmod(++$cnt,5)==0) print "</tr><tr>";
        }
        ?>
        </tr>
        </table>

        <table id="GeosPickList" class="pickList">
        <tr>
            <td nowrap>Geo:&nbsp;</td>
            <td width="20%" nowrap class="pickListItem i0"><input type="checkbox" value="US" id="cb_US" name="GEOS[]">&nbsp;United States</td>
            <td width="20%" nowrap class="pickListItem i1"><input type="checkbox" value="CA" id="cb_CA" name="GEOS[]">&nbsp;Canada</td>
            <td width="20%" nowrap class="pickListItem i2"><input type="checkbox" value="DO" id="cb_DO" name="GEOS[]">&nbsp;Dominican Republic</td>
            <td width="20%" nowrap class="pickListItem i3"><input type="checkbox" value="JM" id="cb_JM" name="GEOS[]">&nbsp;Jamaica</td>
            <td width="20%" nowrap class="pickListItem i4"><input type="checkbox" value="MX" id="cb_MX" name="GEOS[]">&nbsp;Mexico</td>
        </tr>
        <tr>
            <td nowrap>&nbsp;</td>
            <td width="20%" nowrap class="pickListItem i5"><input type="checkbox" value="GB" id="cb_GB" name="GEOS[]">&nbsp;United Kingdom</td>
            <td width="20%" nowrap class="pickListItem i0"><input type="checkbox" value="LA" id="cb_LA" name="GEOS[]">&nbsp;Latin America</td>
            <td width="20%" nowrap class="pickListItem i1"><input type="checkbox" value="EU" id="cb_EU" name="GEOS[]">&nbsp;Europe</td>
            <td width="20%" nowrap class="pickListItem i2"><input type="checkbox" value="--" id="cb_--" name="GEOS[]">&nbsp;Rest of the world</td>
        </tr>
        </table>

        <table id="GeosPickList" class="pickList">
        <tr>
            <td nowrap>Season:&nbsp;</td>
            <td nowrap>
                <select id="SeasonPickList">
                    <option value=''></option>
                    <?
                    $SSET = $clsSeasons->getByProperty($db, array("PROP_ID"=>$PROP_ID));
                    while ($srow = $db->fetch_array($SSET['rSet'])) {
                        print "<option value='{$srow['ID']}'>{$srow['NAME']}</option>";
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td nowrap>Room Type:&nbsp;</td>
            <td nowrap>
                <select id="RoomPickList">
                    <option value=''></option>
                    <?
                    $RSET = $clsRooms->getByProperty($db, array("PROP_ID"=>$PROP_ID));
                    while ($rrow = $db->fetch_array($RSET['rSet'])) {
                        print "<option value='{$rrow['ID']}'>{$rrow['NAME_EN']}</option>";
                    }
                    ?>
                </select>
            </td>
        </tr>
        </table>
    </div>
</div>

<script>
    var YEARS = '<? print implode(",",$_DATA['YEARS']) ?>'.split(","),
        GEOS  = '<? print implode(",",$_DATA['GEOS']) ?>'.split(",");
    for (t=0;t<GEOS.length;++t) {
        $("#cb_"+GEOS[t])[0].checked = true;
    }
</script>