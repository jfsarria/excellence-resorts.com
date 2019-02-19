<?
/*
 * Revised: Jun 14, 2011
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
            If Triple - Deduction from per person/per night rate is 
            <input type="text" id="SUPL_TRIPLE" name="SUPL_TRIPLE" value="<? print isset($_DATA['SUPL_TRIPLE']) ? $_DATA['SUPL_TRIPLE'] : "" ?>" class="small">
            <select id="SUPL_TYPE" name="SUPL_TYPE">
                <option value="$" <? if (isset($_DATA['SUPL_TYPE'])&&$_DATA['SUPL_TYPE']=="$") print "selected" ?>>$</option>
                <option value="%" <? if (isset($_DATA['SUPL_TYPE'])&&$_DATA['SUPL_TYPE']=="%") print "selected" ?>>%</option>
            </select>
        </div>
    </div>
</fieldset>
