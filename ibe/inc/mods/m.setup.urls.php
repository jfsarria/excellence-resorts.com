<?
/*
 * Revised: Dec 07, 2011
 */
?>
<fieldset>
    <legend>Property Emails</legend>
    <div class="fieldset">
        <div class="label">Admin Email</div>
        <div class="field"><input type="text" id="ADMIN_EMAIL" name="ADMIN_EMAIL" value="<? print isset($_DATA['ADMIN_EMAIL']) ? $_DATA['ADMIN_EMAIL'] : "" ?>" class="full"></div>
        <div class="label">Reservation Email</div>
        <div class="field"><input type="text" id="RES_EMAIL" name="RES_EMAIL" value="<? print isset($_DATA['RES_EMAIL']) ? $_DATA['RES_EMAIL'] : "" ?>" class="full"></div>
        <div class="label">Air Email</div>
        <div class="field"><input type="text" id="AIR_EMAIL" name="AIR_EMAIL" value="<? print isset($_DATA['AIR_EMAIL']) ? $_DATA['AIR_EMAIL'] : "" ?>" class="full"></div>
    </div>
</fieldset>

<fieldset>
    <legend>Property URLs</legend>
    <div class="fieldset">
        <div class="label">Home English</div>
        <div class="field"><input type="text" id="HOME_URL" name="HOME_URL" value="<? print isset($_DATA['HOME_URL']) ? $_DATA['HOME_URL'] : "" ?>" class="full"></div>
        <div class="label">Home Spanish</div>
        <div class="field"><input type="text" id="HOME_URL_SP" name="HOME_URL_SP" value="<? print isset($_DATA['HOME_URL_SP']) ? $_DATA['HOME_URL_SP'] : "" ?>" class="full"></div>

        <div class="label">Reservations English</div>
        <div class="field"><input type="text" id="RES_URL" name="RES_URL" value="<? print isset($_DATA['RES_URL']) ? $_DATA['RES_URL'] : "" ?>" class="full"></div>
        <div class="label">Reservations Spanish</div>
        <div class="field"><input type="text" id="RES_URL_SP" name="RES_URL_SP" value="<? print isset($_DATA['RES_URL_SP']) ? $_DATA['RES_URL_SP'] : "" ?>" class="full"></div>

        <div><br><br></div>
        <div class="label">Spa English</div>
        <div class="field"><input type="text" id="SPA_URL_EN" name="SPA_URL_EN" value="<? print isset($_DATA['SPA_URL_EN']) ? $_DATA['SPA_URL_EN'] : "" ?>" class="full"></div>
        <div class="label">Spa Spanish</div>
        <div class="field"><input type="text" id="SPA_URL_SP" name="SPA_URL_SP" value="<? print isset($_DATA['SPA_URL_SP']) ? $_DATA['SPA_URL_SP'] : "" ?>" class="full"></div>
        <div class="label">Spa Reservations English</div>
        <div class="field"><input type="text" id="SPA_RES_EN" name="SPA_RES_EN" value="<? print isset($_DATA['SPA_RES_EN']) ? $_DATA['SPA_RES_EN'] : "" ?>" class="full"></div>
        <div class="label">Spa Reservations Spanish</div>
        <div class="field"><input type="text" id="SPA_RES_SP" name="SPA_RES_SP" value="<? print isset($_DATA['SPA_RES_SP']) ? $_DATA['SPA_RES_SP'] : "" ?>" class="full"></div>
        <div><br><br></div>
        <div class="label">Mailing List English</div>
        <div class="field"><input type="text" id="MLIST_URL_EN" name="MLIST_URL_EN" value="<? print isset($_DATA['MLIST_URL_EN']) ? $_DATA['MLIST_URL_EN'] : "" ?>" class="full"></div>
        <div class="label">Mailing List Spanish</div>
        <div class="field"><input type="text" id="MLIST_URL_SP" name="MLIST_URL_SP" value="<? print isset($_DATA['MLIST_URL_SP']) ? $_DATA['MLIST_URL_SP'] : "" ?>" class="full"></div>
    </div>
</fieldset>
