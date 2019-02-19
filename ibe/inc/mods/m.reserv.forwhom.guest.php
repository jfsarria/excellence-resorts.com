<?
/*
 * Revised: Nov 08, 2011
 */

if (!$is_Rebooking) {
?>
<fieldset>
    <legend><input type="radio" id="RES_TO_WHOM_GUEST" name="RES_TO_WHOM" value="GUEST" onclick="ibe.reserv.forWhom.open(this.id)">&nbsp;Reservation for Existing Guest</legend>
    <div class="fieldset RES_TO_WHOM" id='callcenter_RES_TO_WHOM_GUEST'>
        <table>
        <tr>
            <td><input type="text" id="RES_SEARCH_GUEST_BY_LASTNAME" class="med"></td>
            <td nowrap><a href="javascript:void(0)" onclick="ibe.reserv.searchContact('#RES_SEARCH_GUEST_BY_LASTNAME','LASTNAME','searchGuest')">Search by Last Name</a></td>
        </tr>
        <tr>
            <td><input type="text" id="RES_SEARCH_GUEST_BY_PHONE" class="med"></td>
            <td nowrap><a href="javascript:void(0)" onclick="ibe.reserv.searchContact('#RES_SEARCH_GUEST_BY_PHONE','PHONE','searchGuest')">Search by Phone Number</a></td>
        </tr>
        <tr>
            <td><input type="text" id="RES_SEARCH_GUEST_BY_EMAIL" class="med"></td>
            <td nowrap><a href="javascript:void(0)" onclick="ibe.reserv.searchContact('#RES_SEARCH_GUEST_BY_EMAIL','EMAIL','searchGuest')">Search by Email</a></td>
        </tr>
        </table>
        <div id='searchGuestResult' class="RES_TO_WHOM"></div>
        <div id="m_reserv_forwhom_guest_next" class="RES_TO_WHOM" style='display:none'>
            <span class="button" onclick="ibe.reserv.forWhom.Next_ExistingGuest()">Next Step &#187;</span>
        </div>
    </div>
</fieldset>
<? } ?>