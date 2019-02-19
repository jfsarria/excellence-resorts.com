<?
/*
 * Revised: Apr 25, 2011
 */
?>
<fieldset>
    <legend>Guest Options</legend>
    <div class="fieldset">
        <div class="label">
            <table width="100%">
            <tr>
                <td width="80%" nowrap>
                    Max Occupancy&nbsp;
                    <select name="MAX_OCUP" id="MAX_OCUP"><? for ($t=2; $t<= 20; ++$t) print "<option value='{$t}' ".((isset($_DATA['MAX_OCUP']) && (int)$_DATA['MAX_OCUP']==$t) ? "selected" : "").">{$t}</option>"; ?></select>
                </td>
            </tr>
            </table>
        </div>
    </div>
</fieldset>
