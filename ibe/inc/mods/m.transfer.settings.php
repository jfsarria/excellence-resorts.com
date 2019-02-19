<fieldset>
    <div class="fieldset">
      <div class="label"><input type="checkbox" id="IS_ACTIVE" name="IS_ACTIVE" value="1" <? print (isset($_DATA['IS_ACTIVE'])&&(int)$_DATA['IS_ACTIVE']==1) ? "checked" : "" ?>> Is Active</div>
    </div>
</fieldset>

<fieldset>
    <legend>Transfers Company</legend>
    <div>
      <div class="fieldset" style="width:40%;float:left">
          <div class="label">Phone Number</div>
          <div class="field"><input type="text" id="COMPANY_PHONE" name="COMPANY_PHONE" value="<? print isset($_DATA['COMPANY_PHONE']) ? $_DATA['COMPANY_PHONE'] : "" ?>" class="full" style="width:100%"></div>
      </div>
      <div class="fieldset" style="width:50%;float:right;padding-right: 20px;">
          <div class="label">Company Name</div>
          <div class="field"><input type="text" id="COMPANY_NAME" name="COMPANY_NAME" value="<? print isset($_DATA['COMPANY_NAME']) ? $_DATA['COMPANY_NAME'] : "" ?>" class="full" style="width:100%"></div>
      </div>
      <div style="clear:both"></div>
    </div>
</fieldset>

<fieldset>
    <legend>Transfers Email Addresses</legend>
    <div class="fieldset">
        <div class="label">Emails separated by comma</div>
        <div class="field"><input type="text" id="COMPANY_EMAIL" name="COMPANY_EMAIL" value="<? print isset($_DATA['COMPANY_EMAIL']) ? $_DATA['COMPANY_EMAIL'] : "" ?>" class="full"></div>
    </div>
</fieldset>

<fieldset>
    <legend>Private Transfer URL</legend>
    <div class="fieldset">
        <div class="label">English</div>
        <div class="field"><input type="text" id="TRANSFERS_URL_EN" name="TRANSFERS_URL_EN" value="<? print isset($_DATA['TRANSFERS_URL_EN']) ? $_DATA['TRANSFERS_URL_EN'] : "" ?>" class="full"></div>
        <div class="label">Spanish</div>
        <div class="field"><input type="text" id="TRANSFERS_URL_SP" name="TRANSFERS_URL_SP" value="<? print isset($_DATA['TRANSFERS_URL_SP']) ? $_DATA['TRANSFERS_URL_SP'] : "" ?>" class="full"></div>
    </div>
</fieldset>

<fieldset>
    <legend>Overview Editable Text</legend>
    <div class="fieldset">
        <div class="label">English</div>
        <div class="field"><textarea id="OVERVIEW_EN" name="OVERVIEW_EN" class="full"><? print isset($_DATA['OVERVIEW_EN']) ? $_DATA['OVERVIEW_EN'] : "" ?></textarea></div>
    </div>
    <div class="fieldset">
        <div class="label">Spanish</div>
        <div class="field"><textarea id="OVERVIEW_SP" name="OVERVIEW_SP" class="full"><? print isset($_DATA['OVERVIEW_SP']) ? $_DATA['OVERVIEW_SP'] : "" ?></textarea></div>
    </div>
</fieldset>

<fieldset>
    <legend>Confirmation Email Editable Text</legend>
    <div class="fieldset">
        <div class="label">English</div>
        <div class="field"><textarea id="CONFIRM_EN" name="CONFIRM_EN" class="full"><? print isset($_DATA['CONFIRM_EN']) ? $_DATA['CONFIRM_EN'] : "" ?></textarea></div>
    </div>
    <div class="fieldset">
        <div class="label">Spanish</div>
        <div class="field"><textarea id="CONFIRM_SP" name="CONFIRM_SP" class="full"><? print isset($_DATA['CONFIRM_SP']) ? $_DATA['CONFIRM_SP'] : "" ?></textarea></div>
    </div>
</fieldset>

<fieldset>
    <legend>Transfer Change Email Editable Text</legend>
    <div class="fieldset">
        <div class="label">English</div>
        <div class="field"><textarea id="CHANGE_EN" name="CHANGE_EN" class="full"><? print isset($_DATA['CHANGE_EN']) ? $_DATA['CHANGE_EN'] : "" ?></textarea></div>
    </div>
    <div class="fieldset">
        <div class="label">Spanish</div>
        <div class="field"><textarea id="CHANGE_SP" name="CHANGE_SP" class="full"><? print isset($_DATA['CHANGE_SP']) ? $_DATA['CHANGE_SP'] : "" ?></textarea></div>
    </div>
</fieldset>

<fieldset>
    <legend>Cancellation Email Editable Text</legend>
    <div class="fieldset">
        <div class="label">English</div>
        <div class="field"><textarea id="CANCEL_EN" name="CANCEL_EN" class="full"><? print isset($_DATA['CANCEL_EN']) ? $_DATA['CANCEL_EN'] : "" ?></textarea></div>
    </div>
    <div class="fieldset">
        <div class="label">Spanish</div>
        <div class="field"><textarea id="CANCEL_SP" name="CANCEL_SP" class="full"><? print isset($_DATA['CANCEL_SP']) ? $_DATA['CANCEL_SP'] : "" ?></textarea></div>
    </div>
</fieldset>

<fieldset>
    <legend>No Transfer Reminder Email Editable Text</legend>
    <div class="fieldset">
        <div class="label">English</div>
        <div class="field"><textarea id="REMINDER_EN" name="REMINDER_EN" class="full"><? print isset($_DATA['REMINDER_EN']) ? $_DATA['REMINDER_EN'] : "" ?></textarea></div>
    </div>
    <div class="fieldset">
        <div class="label">Spanish</div>
        <div class="field"><textarea id="REMINDER_SP" name="REMINDER_SP" class="full"><? print isset($_DATA['REMINDER_SP']) ? $_DATA['REMINDER_SP'] : "" ?></textarea></div>
    </div>
</fieldset>