<?
/*
 * Revised: May 04, 2011
 */

$MARKUP_YEAR = array("MARKUP"=>"");
if (isset($_DATA['YEAR']) && (int)$_DATA['YEAR']!=0) {
    $MARKUP_YEAR = $clsSetup->getMarkupByYear($db, array("PROP_ID"=>$PROP_ID,"YEAR"=>$_DATA['YEAR'],"asArray"=>true));
?>
<fieldset>
    <legend>Contract Rate</legend>
    <div class="fieldset">
        <div class="label">
            <table align="center">
            <tr>
                <td>
                    Per Person/Per Night $&nbsp;&nbsp;
                    <input type="text" id="RATE_PER_RP" name="RATE_PER_RP" value="<? print isset($_DATA['RATE_PER_RP']) ? $_DATA['RATE_PER_RP'] : "" ?>" class="small" onBlur="ibe.page.calculateGrossRackRate()">&nbsp;&nbsp;&nbsp;&nbsp;
                </td>
                <td>
                    Markup Override&nbsp;&nbsp;
                    <input type="text" id="MARKUP" name="MARKUP" value="<? print (isset($_DATA['MARKUP'])&&(double)$_DATA['MARKUP']!=0) ? (double)$_DATA['MARKUP'] : "" ?>" class="small" onBlur="ibe.page.calculateGrossRackRate()">%
                    <br><b>Global Property Markup is <? print isset($MARKUP_YEAR['MARKUP']) ? (double)$MARKUP_YEAR['MARKUP'] : "" ?>%</b>
                    <input type="hidden" id="MARKUP_YEAR" name="MARKUP_YEAR" value="<? print isset($MARKUP_YEAR['MARKUP']) ? (double)$MARKUP_YEAR['MARKUP'] : "" ?>">
                </td>
            </tr>
            </table>                    
        </div>
    </div>
</fieldset>
<? } ?>