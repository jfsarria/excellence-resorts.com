<?
/*
 * Revised: Jun 09, 2011
 *          May 22, 2016
 */

if (!$is_Rebooking) {
?>
<fieldset>
    <legend><input type="radio" checked id="RES_TO_WHOM_NEWGUEST" name="RES_TO_WHOM" value="GUEST" onclick="ibe.reserv.forWhom.open(this.id)">&nbsp;Reservation for New Guest</legend>
    <div class="fieldset RES_TO_WHOM" id='callcenter_RES_TO_WHOM_NEWGUEST'>
        <table>
        <tr>
            <td><input type="text" id="RES_VERIFY_GUEST_EMAIL" class="med"></td>
            <td nowrap><a href="javascript:void(0)" onclick="ibe.reserv.searchContact('#RES_VERIFY_GUEST_EMAIL','EMAIL','verifyEmail')">Guest Emal (very or add new)</a></td>
        </tr>
        </table>
        <div id='verifyEmailResult' class="RES_TO_WHOM"></div>

        <div id="continue_new_guest_btn" class="RES_TO_WHOM" style='display:none'>
            <span class="button" onclick="ibe.reserv.forWhom.Next_NewGuest()">Continue with new guest &#187;</span>
        </div>

        <div id="continue_existing_guest_btn" class="RES_TO_WHOM" style='display:none'>
            <span class="button" onclick="ibe.reserv.forWhom.Next_ExistingGuest()">Next Step &#187;</span>
        </div>
    </div>
</fieldset>
<? } ?>