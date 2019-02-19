<?
/*
 * Revised: Jun 15, 2011
 */
?>
<fieldset>
    <legend>Suplements</legend>
    <div class="fieldset">
        <div class="label">
            If Single - Suplement to per person/per night rate is $
            <input type="text" id="SUPL_SINGLE" name="SUPL_SINGLE" value="<? print isset($_DATA['SUPL_SINGLE']) ? $_DATA['SUPL_SINGLE'] : "" ?>" class="small">
        </div>
        <div class="label">
            If Triple - Deduction from per person/per night of 3rd adult rate is
            <input type="text" id="SUPL_TRIPLE" name="SUPL_TRIPLE" value="<? print isset($_DATA['SUPL_TRIPLE']) ? $_DATA['SUPL_TRIPLE'] : "" ?>" class="small">
            <input type="hidden" id="SUPL_TYPE" name="SUPL_TYPE" value="%">%
        </div>
    </div>
</fieldset>
