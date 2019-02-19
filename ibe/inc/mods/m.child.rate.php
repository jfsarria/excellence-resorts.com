<?
/*
 * Revised: May 08, 2011
 */
?>
<fieldset>
    <legend>Children Rate</legend>
    <div class="fieldset">
        <div class="label">
            <br><br>
            <table width="100%">
            <tr>
                <td width="33%">
                    Rate Name&nbsp;&nbsp;
                    <span><input type="text" id="NAME" name="NAME" value="<? print isset($_DATA['NAME']) ? $_DATA['NAME'] : "" ?>" class="med<? if (isset($error['NAME'])) print " s_required" ?>"></span>
                </td>
                <td width="33%">
                    From&nbsp;&nbsp;
                    <select name="FROM" id="FROM"><? for ($t=0; $t<= 17; ++$t) print "<option value='{$t}' ".((isset($_DATA['FROM']) && (int)$_DATA['FROM']==$t) ? "selected" : "").">{$t}</option>"; ?></select>
                    &nbsp;&nbsp;years old
                </td>
                <td width="33%">
                    To&nbsp;&nbsp;
                    <select name="TO" id="TO"><? for ($t=0; $t<= 17; ++$t) print "<option value='{$t}' ".((isset($_DATA['TO']) && (int)$_DATA['TO']==$t) ? "selected" : "").">{$t}</option>"; ?></select>
                    &nbsp;&nbsp;years old
                </td>
            </tr>
            <tr><td><br></td></tr>
            <tr>
                <td width="33%">&nbsp;</td>
                <td width="33%">
                    Counted toward Occupancy limit&nbsp;&nbsp;
                    <span><input type="checkbox" id="COUNTED" name="COUNTED" value="1" <? print (isset($_DATA['COUNTED'])&&(int)$_DATA['COUNTED']==1) ? "checked" : "" ?>></span>
                </td>
                <td width="33%">
                    Percentage of Adult room rate&nbsp;&nbsp;
                    <input type="text" id="PERCENTAGE" name="PERCENTAGE" value="<? print isset($_DATA['PERCENTAGE']) ? $_DATA['PERCENTAGE'] : "" ?>" class="small">%
                </td>
            </tr>
            </table>
        </div>
    </div>
</fieldset>
