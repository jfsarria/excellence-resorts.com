<?
/*
 * Revised: Jun 09, 2011
 *          May 26, 2016
 */

$assign_ta = isset($assign_ta) ? $assign_ta : false;

if (!$is_Rebooking) {
?>
<fieldset>
    <? if ($assign_ta) { ?>
    <legend>Assign Travel Agent</legend>
    <input type="hidden" name="RES_ASSIGN_TA_ID" id="RES_TA_ID" value="0">
    <? } else { ?>
    <legend><input type="radio" id="RES_TO_WHOM_TA" name="RES_TO_WHOM" value="TA" onclick="ibe.reserv.forWhom.open(this.id)">&nbsp;Reservation for Travel Agent</legend>
    <? } ?>
    <div class="fieldset RES_TO_WHOM" id='callcenter_RES_TO_WHOM_TA' <?=$assign_ta?'style="display:block"':""?>>
        <table>
        <tr>
            <td><input type="text" id="RES_SEARCH_TA_BY_IATA" class="med"></td>
            <td nowrap><a href="javascript:void(0)" onclick="ibe.reserv.searchContact('#RES_SEARCH_TA_BY_IATA','IATA','searchTA')">Search by IATA</a></td>
        </tr>
        <tr>
            <td><input type="text" id="RES_SEARCH_TA_BY_AGENCY_PHONE" class="med"></td>
            <td nowrap><a href="javascript:void(0)" onclick="ibe.reserv.searchContact('#RES_SEARCH_TA_BY_AGENCY_PHONE','AGENCY_PHONE','searchTA')">Search by Phone Number</a></td>
        </tr>
        <tr>
            <td><input type="text" id="RES_SEARCH_TA_BY_AGENCY_NAME" class="med"></td>
            <td nowrap><a href="javascript:void(0)" onclick="ibe.reserv.searchContact('#RES_SEARCH_TA_BY_AGENCY_NAME','AGENCY_NAME','searchTA')">Search by Agency Name</a></td>
        </tr>
        <tr>
            <td><input type="text" id="RES_SEARCH_TA_BY_LASTNAME" class="med"></td>
            <td nowrap><a href="javascript:void(0)" onclick="ibe.reserv.searchContact('#RES_SEARCH_TA_BY_LASTNAME','LASTNAME','searchTA')">Search by Contact Last Name</a></td>
        </tr>
        <tr>
            <td><input type="text" id="RES_SEARCH_TA_BY_EMAIL" class="med"></td>
            <td nowrap><a href="javascript:void(0)" onclick="ibe.reserv.searchContact('#RES_SEARCH_TA_BY_EMAIL','EMAIL','searchTA')">Search by Email</a></td>
        </tr>
        <? if ($assign_ta) { } else { ?>
        <tr>
            <td></td>
            <td nowrap>
                <div style='margin-top:20px;text-align:center'>
                    <span class="button key" onclick="ibe.reserv.forWhom.newTA()">Register new Travel Agent</span>
                </div>
            </td>
        </tr>
        <? } ?>
        </table>
        <div id='searchTAResult' class="RES_TO_WHOM"></div>
        
        <div id="m_reserv_forwhom_ta_next" class="RES_TO_WHOM" style='display:none'>
        <? if ($assign_ta) { } else { ?>
            <span class="button" onclick="ibe.reserv.forWhom.Next_ExistingTA()">Next Step &#187;</span>
        <? } ?>
        </div>

        <div id='callcenter_NEW_RES_TO_WHOM_TA' class='RES_TO_WHOM'>
            <? include "m.reserv.forwhom.ta.form.php" ?>
        </div>

    </div>
</fieldset>
<? } ?>