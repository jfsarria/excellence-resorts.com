<?
/*
 * Revised: Nov 29, 2011
 */
?>

<fieldset>
    <legend>Emails Text</legend>
    <div>
        <b>Key codes</b>: 
        [PROPERTY],
        [STYLED PROPERTY],
        [RESERVATION],
        [ORIGINAL],
        [CANCELED],
        [HOME],
        [POLICY],
        [FEE],
        [CANCELLATION DATE],
        [SPA RESERVATION],
        [SPA URL],
        [MAILING LIST],
        [ARRIVAL],
        [AIRLINE]
    </div>
</fieldset>

<fieldset>
    <legend>Reservation Email Header</legend>
    <div class="fieldset">
        <div class="label">English</div>
        <div class="field"><textarea id="EMAIL_HDR_EN" name="EMAIL_HDR_EN" class="full"><? print isset($_DATA['EMAIL_HDR_EN']) ? $_DATA['EMAIL_HDR_EN'] : "" ?></textarea></div>
    </div>
    <div class="fieldset">
        <div class="label">Spanish</div>
        <div class="field"><textarea id="EMAIL_HDR_SP" name="EMAIL_HDR_SP" class="full"><? print isset($_DATA['EMAIL_HDR_SP']) ? $_DATA['EMAIL_HDR_SP'] : "" ?></textarea></div>
    </div>
</fieldset>

<fieldset>
    <legend>Reservation Email Body</legend>
    <div class="fieldset">
        <div class="label">English</div>
        <div class="field"><textarea id="EMAIL_RES_EN" name="EMAIL_RES_EN" class="full" style="height:200px"><? print isset($_DATA['EMAIL_RES_EN']) ? $_DATA['EMAIL_RES_EN'] : "" ?></textarea></div>
    </div>
    <div class="fieldset">
        <div class="label">Spanish</div>
        <div class="field"><textarea id="EMAIL_RES_SP" name="EMAIL_RES_SP" class="full" style="height:200px"><? print isset($_DATA['EMAIL_RES_SP']) ? $_DATA['EMAIL_RES_SP'] : "" ?></textarea></div>
    </div>
</fieldset>

<fieldset>
    <legend>Arrival Text</legend>
    <div class="fieldset">
        <div class="label">English</div>
        <div class="field"><textarea id="EMAIL_ARR_EN" name="EMAIL_ARR_EN" class="full"><? print isset($_DATA['EMAIL_ARR_EN']) ? $_DATA['EMAIL_ARR_EN'] : "" ?></textarea></div>
    </div>
    <div class="fieldset">
        <div class="label">Spanish</div>
        <div class="field"><textarea id="EMAIL_ARR_SP" name="EMAIL_ARR_SP" class="full"><? print isset($_DATA['EMAIL_ARR_SP']) ? $_DATA['EMAIL_ARR_SP'] : "" ?></textarea></div>
    </div>
</fieldset>

<fieldset>
    <legend>Airline Text</legend>
    <div class="fieldset">
        <div class="label">English</div>
        <div class="field"><textarea id="EMAIL_AIR_EN" name="EMAIL_AIR_EN" class="full"><? print isset($_DATA['EMAIL_AIR_EN']) ? $_DATA['EMAIL_AIR_EN'] : "" ?></textarea></div>
    </div>
    <div class="fieldset">
        <div class="label">Spanish</div>
        <div class="field"><textarea id="EMAIL_AIR_SP" name="EMAIL_AIR_SP" class="full"><? print isset($_DATA['EMAIL_AIR_SP']) ? $_DATA['EMAIL_AIR_SP'] : "" ?></textarea></div>
    </div>
</fieldset>

<fieldset>
    <legend>Rebooked Header</legend>
    <div class="fieldset">
        <div class="label">English</div>
        <div class="field"><textarea id="EMAIL_REB_EN" name="EMAIL_REB_EN" class="full"><? print isset($_DATA['EMAIL_REB_EN']) ? $_DATA['EMAIL_REB_EN'] : "" ?></textarea></div>
    </div>
    <div class="fieldset">
        <div class="label">Spanish</div>
        <div class="field"><textarea id="EMAIL_REB_SP" name="EMAIL_REB_SP" class="full"><? print isset($_DATA['EMAIL_REB_SP']) ? $_DATA['EMAIL_REB_SP'] : "" ?></textarea></div>
    </div>
</fieldset>

<fieldset>
    <legend>Cancelation Email Header</legend>
    <div class="fieldset">
        <div class="label">English</div>
        <div class="field"><textarea id="EMAIL_CAN_EN" name="EMAIL_CAN_EN" class="full" style="height:200px"><? print isset($_DATA['EMAIL_CAN_EN']) ? $_DATA['EMAIL_CAN_EN'] : "" ?></textarea></div>
    </div>
    <div class="fieldset">
        <div class="label">Spanish</div>
        <div class="field"><textarea id="EMAIL_CAN_SP" name="EMAIL_CAN_SP" class="full" style="height:200px"><? print isset($_DATA['EMAIL_CAN_SP']) ? $_DATA['EMAIL_CAN_SP'] : "" ?></textarea></div>
    </div>
</fieldset>

<fieldset>
    <legend>Pre-Stay Email</legend>
    <div class="fieldset">
        <div class="label">English</div>
        <div class="field"><textarea id="EMAIL_PRESTAY_EN" name="EMAIL_PRESTAY_EN" class="full"><? print isset($_DATA['EMAIL_PRESTAY_EN']) ? $_DATA['EMAIL_PRESTAY_EN'] : "" ?></textarea></div>
    </div>
    <div class="fieldset">
        <div class="label">Spanish</div>
        <div class="field"><textarea id="EMAIL_PRESTAY_SP" name="EMAIL_PRESTAY_SP" class="full"><? print isset($_DATA['EMAIL_PRESTAY_SP']) ? $_DATA['EMAIL_PRESTAY_SP'] : "" ?></textarea></div>
    </div>
</fieldset>

<fieldset>
    <legend>Post-Stay Email</legend>
    <div class="fieldset">
        <div class="label">English</div>
        <div class="field"><textarea id="EMAIL_POSTSTAY_EN" name="EMAIL_POSTSTAY_EN" class="full"><? print isset($_DATA['EMAIL_POSTSTAY_EN']) ? $_DATA['EMAIL_POSTSTAY_EN'] : "" ?></textarea></div>
    </div>
    <div class="fieldset">
        <div class="label">Spanish</div>
        <div class="field"><textarea id="EMAIL_POSTSTAY_SP" name="EMAIL_POSTSTAY_SP" class="full"><? print isset($_DATA['EMAIL_POSTSTAY_SP']) ? $_DATA['EMAIL_POSTSTAY_SP'] : "" ?></textarea></div>
    </div>
</fieldset>

<fieldset>
    <legend>Credit Card Details</legend>
    <div class="fieldset">
        <div class="label">English</div>
        <div class="field"><textarea id="EMAIL_CCDETAILS_EN" name="EMAIL_CCDETAILS_EN" class="full"><? print isset($_DATA['EMAIL_CCDETAILS_EN']) ? $_DATA['EMAIL_CCDETAILS_EN'] : "" ?></textarea></div>
    </div>
    <div class="fieldset">
        <div class="label">Spanish</div>
        <div class="field"><textarea id="EMAIL_CCDETAILS_SP" name="EMAIL_CCDETAILS_SP" class="full"><? print isset($_DATA['EMAIL_CCDETAILS_SP']) ? $_DATA['EMAIL_CCDETAILS_SP'] : "" ?></textarea></div>
    </div>
</fieldset>
