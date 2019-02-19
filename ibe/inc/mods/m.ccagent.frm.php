<?
/*
 * Revised: Aug 10, 2011
 *          Mar 08, 2018
 */

?>
<fieldset>
    <legend>Agent Information</legend>
    <input type="hidden" name="ID" id="ID" class="large" value="<? print isset($CCAGENT['ID'])?$CCAGENT['ID']:"" ?>">

    <div class="fieldset">
        <table width="100%" cellspacing="4" cellpadding="1">
        <tr>
            <td valign="top" nowrap>First Name</td>
            <td width="100%" valign="top"><input type="text" name="CC_AGENT_FIRSTNAME" id="CC_AGENT_FIRSTNAME" class="large" value="<? print isset($CCAGENT['FIRSTNAME'])?$CCAGENT['FIRSTNAME']:"" ?>"></td>
        </tr>
        <tr>
            <td valign="top" nowrap>Last Name</td>
            <td width="100%" valign="top"><input type="text" name="CC_AGENT_LASTNAME" id="CC_AGENT_LASTNAME" class="large" value="<? print isset($CCAGENT['LASTNAME'])?$CCAGENT['LASTNAME']:"" ?>"></td>
        </tr>
        <tr>
            <td valign="top" nowrap>Email</td>
            <td width="100%" valign="top"><input type="text" name="CC_AGENT_EMAIL" id="CC_AGENT_EMAIL" class="large" value="<? print isset($CCAGENT['EMAIL'])?$CCAGENT['EMAIL']:"" ?>"></td>
        </tr>
        <tr>
            <td valign="top" nowrap>User Name</td>
            <td width="100%" valign="top"><input type="text" name="CC_AGENT_USERNAME" id="CC_AGENT_USERNAME" class="large" value="<? print isset($CCAGENT['USERNAME'])?$CCAGENT['USERNAME']:"" ?>"></td>
        </tr>
        <tr>
            <td valign="top" nowrap>Password</td>
            <td width="100%" valign="top"><input type="text" name="CC_AGENT_PASSWORD" id="CC_AGENT_PASSWORD" class="large" value="<? print isset($CCAGENT['PASSWORD'])?$CCAGENT['PASSWORD']:"" ?>"></td>
        </tr>
        <tr>
            <td valign="top" nowrap>Role</td>
            <td width="100%" valign="top">
                <select name="CC_AGENT_ROLE" id="CC_AGENT_ROLE">
                    <option value="3" <? if (isset($CCAGENT['ROLE'])&&(int)$CCAGENT['ROLE']==3) print "selected" ?>>Regular Agent</option>
                    <option value="1" <? if (isset($CCAGENT['ROLE'])&&(int)$CCAGENT['ROLE']==1) print "selected" ?>>Super Admin</option>
                    <option value="5" <? if (isset($CCAGENT['ROLE'])&&(int)$CCAGENT['ROLE']==5) print "selected" ?>>Special Agent</option>
                    <option value="10" <? if (isset($CCAGENT['ROLE'])&&(int)$CCAGENT['ROLE']==10) print "selected" ?>>Transfers Admin</option>
                </select>
            </td>
        </tr>
        </table>
    </div>
</fieldset>

