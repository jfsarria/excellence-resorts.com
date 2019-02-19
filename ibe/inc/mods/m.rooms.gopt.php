<?
/*
 * Revised: May 16, 2011
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
                    <select name="MAX_OCUP" id="MAX_OCUP"><? for ($t=2; $t<= 10; ++$t) print "<option value='{$t}' ".((isset($_DATA['MAX_OCUP']) && (int)$_DATA['MAX_OCUP']==$t) ? "selected" : "").">{$t}</option>"; ?></select>
                </td>
                <td width="80%" nowrap>
                    Max Adults&nbsp;
                    <select name="MAX_ADUL" id="MAX_ADUL"><? for ($t=2; $t<= 10; ++$t) print "<option value='{$t}' ".((isset($_DATA['MAX_ADUL']) && (int)$_DATA['MAX_ADUL']==$t) ? "selected" : "").">{$t}</option>"; ?></select>
                </td>
                <td width="80%" nowrap>
                    Max Children&nbsp;
                    <select name="MAX_CHIL" id="MAX_CHIL"><? for ($t=2; $t<= 10; ++$t) print "<option value='{$t}' ".((isset($_DATA['MAX_CHIL']) && (int)$_DATA['MAX_CHIL']==$t) ? "selected" : "").">{$t}</option>"; ?></select>
                </td>
            </tr>
            </table>
        </div>
    </div>
</fieldset>
