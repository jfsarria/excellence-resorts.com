<?
/*
 * Revised: May 04, 2011
 */
?>
<fieldset>
    <div class="fieldset m_specials">
        <div class="label">
            <table align="center">
            <tr>
                <td nowrap>Active&nbsp;<span><input type="checkbox" id="IS_ACTIVE" name="IS_ACTIVE" value="1" <? print (isset($_DATA['IS_ACTIVE'])&&(int)$_DATA['IS_ACTIVE']==1) ? "checked" : "" ?>></span>&nbsp;&nbsp;&nbsp;</td>
                <td nowrap>Archive&nbsp;<span><input type="checkbox" id="IS_ARCHIVE" name="IS_ARCHIVE" value="1" <? print (isset($_DATA['IS_ARCHIVE'])&&(int)$_DATA['IS_ARCHIVE']==1) ? "checked" : "" ?>></span></td>
            </tr>
            </table>                    
        </div>
    </div>
</fieldset>