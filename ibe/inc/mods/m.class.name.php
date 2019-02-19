<?
/*
 * Revised: Dec 15, 2011
 */

?>
<fieldset>
    <legend>Class Name</legend>
    <div class="fieldset">
        <div class="label">English</div>
        <div class="field"><input type="text" id="NAME_EN" name="NAME_EN" value="<? if (isset($isCopy)&&$isCopy) print "Copy of " ?><? print (isset($_DATA['NAME_EN']) && $_DATA['NAME_EN']!="") ? $_DATA['NAME_EN'] : "All Inclusive seasonal discounted rate per room. All taxes included. All prices in US Dollars." ?>" class="full<? if (isset($error['NAME']) && $_IBE_LANG=="EN") print " s_required" ?>"></div>
        <div class="label">Spanish</div>
        <div class="field"><input type="text" id="NAME_SP" name="NAME_SP" value="<? if (isset($isCopy)&&$isCopy) print "Copia de " ?><? print (isset($_DATA['NAME_SP']) && $_DATA['NAME_SP']!="") ? $_DATA['NAME_SP'] : "Todo Incluido promoción de temporada. Impuestos incluidos. Precios en dólares américanos." ?>" class="full<? if (isset($error['NAME']) && $_IBE_LANG=="SP") print " s_required" ?>"></div>
    </div>
    <div class="fieldset">
        <div class="label">Reference</div>
        <div class="field"><input type="text" id="REFERENCE" name="REFERENCE" value="<? if (isset($isCopy)&&$isCopy) print "Copy of " ?><? print isset($_DATA['REFERENCE']) ? $_DATA['REFERENCE'] : "" ?>" class="full"></div>
    </div>
</fieldset>
