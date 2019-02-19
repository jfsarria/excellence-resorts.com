<?
/*
 * Revised: Aug 11, 2011
 */

?>
<fieldset>
    <legend>Applicable to Class</legend>
    <? include "m.specials.class.filters.php"; ?>
    <div class="fieldset">
        <div class="label">
            <div style='display:none'>
                Applicable to All&nbsp;<span><input type="checkbox" id="ALL_CLASSES" name="ALL_CLASSES" value="1" <? print (isset($_DATA['ALL_CLASSES'])&&(int)$_DATA['ALL_CLASSES']==1) ? "checked" : "" ?>></span>
            </div>
            <br>
            <div id='classList'>
                <?
                for ($YEAR=2011; $YEAR <= date("Y")+5; ++$YEAR) {
                    $DISPLAY = (in_array($YEAR,$_DATA['YEARS'])) ? "" : "none";
                    print "<div id='classList_{$YEAR}' style='display:{$DISPLAY};margin-bottom:20px;'>
                                <div class='classYear' style='padding-bottom:5px'><b>{$YEAR} Classes</b>&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' onclick=\"ibe.select.setAllClassesByYear('{$YEAR}', true)\">Check all</a>&nbsp;-&nbsp;<a href='javascript:void(0)' onclick=\"ibe.select.setAllClassesByYear('{$YEAR}', false)\">Uncheck all</a></div>
                                <div id='classes_$YEAR'></div>";
                    print "</div>";
                }?>
            </div>
        </div>
    </div>
</fieldset>

<script>
    $("#YearsPickList input[type='checkbox']").each(function() {
        ibe.select.showClasses($(this));
    });

    ibe.select.controlSpecialFilters();
</script>