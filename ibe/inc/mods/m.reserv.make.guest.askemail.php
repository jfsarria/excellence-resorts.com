<?
/*
 * Revised: Jun 12, 2011
 */
?>

<p class='s_notice top_msg'>
    <b>Contact Email Address already in used by:</b><br><br>
    <? print "{$retGuest['FIRSTNAME']} {$retGuest['LASTNAME']}<br>{$retGuest['ADDRESS']}<br>{$retGuest['CITY']}, {$retGuest['STATE']} " ?>
    <br><br>
    Please provide a different email or check here <input type="checkbox" name="RES_USE_THIS_GUEST" value="<? print $retGuest['ID'] ?>"> to select this guest.
</p>

<fieldset>
    <legend>Guest Contact Email</legend>
    <div class="fieldset">
        <div class="field"><input type="text" id="RES_GUEST_EMAIL" name="RES_GUEST_EMAIL" value="<? print isset($RESERVATION['GUEST']['EMAIL']) ? $RESERVATION['GUEST']['EMAIL'] : "" ?>" style="width:480px"></div>
        <div style='padding-top:30px;text-align:right'>
            <span class="button" onclick='$("#reservForm").submit()'>Continue &#187;</span>
        </div>
    </div>
</fieldset>

