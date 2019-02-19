<?
/*
 * Revised: May 04, 2011
 */

if (isset($_DATA['YEAR']) && (int)$_DATA['YEAR']!=0) {
?>
<fieldset>
    <legend>Gross Rack Rate</legend>
    <div class="fieldset">
        <div class="label">
            <br>
            <span class="button" onClick="ibe.page.calculateGrossRackRate()">Calculate</span> Gross Rack Rate (without promotional discount) For 1 person based on 2 person in room ocupancy&nbsp;&nbsp;<span id="GrossRackRate">$000</span>
            <br><br>
        </div>
    </div>
</fieldset>
<script>
    ibe.page.calculateGrossRackRate();
</script>
<? } ?>