<?
/*
 * Revised: Jun 12, 2011
 */
?>

<p class='s_notice top_msg'>
    <b>Travel Agent Email Address already in used by:</b><br><br>
    <? print "{$retTA['FIRSTNAME']} {$retTA['LASTNAME']}<br>{$retTA['AGENCY_NAME']}<br>{$retTA['AGENCY_CITY']}, {$retTA['AGENCY_STATE']} " ?>
    <br><br>
    Please provide a different email or check here <input type="checkbox" name="RES_USE_THIS_TA" value="<? print $retTA['ID'] ?>"> to select this guest.
</p>

<fieldset>
    <legend>Travel Agent Contact Email</legend>
    <div class="fieldset">
        <div class="field"><input type="text" id="EMAIL" name="EMAIL" value="<? print isset($RESERVATION['FORWHOM']['TA']['EMAIL']) ? $RESERVATION['FORWHOM']['TA']['EMAIL'] : "" ?>" style="width:480px"></div>
        <div style='padding-top:30px;text-align:right'>
            <span class="button" onclick='$("#reservForm").submit()'>Continue &#187;</span>
        </div>
    </div>
</fieldset>

