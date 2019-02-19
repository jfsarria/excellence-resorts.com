<?
/*
 * Revised: Dec 15, 2011
 */
?>
<fieldset>
    <legend>Season Name</legend>
    <div class="fieldset">
        <div class="field"><input type="text" id="NAME" name="NAME" value="<? if (isset($isCopy)&&$isCopy) print "Copy of " ?><? print isset($_DATA['NAME']) ? $_DATA['NAME'] : "" ?>" class="full<? if (isset($error['NAME'])) print " s_required" ?>"></div>
    </div>
</fieldset>
