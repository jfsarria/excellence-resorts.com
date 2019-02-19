<?
/*
 * Revised: Feb 03, 2013
 */

?>
<table width="100%" cellspacing="2">
<tr>
    <td valign='top' width="20%" nowrap style="padding-right:20px">
        <fieldset>
            <legend>View By</legend>
            <div class="fieldset">
                <span><input type="radio" name="VIEWBY" value="activity" <? if ($VIEWBY=="activity") print "checked" ?>></span>&nbsp;IBE Activity Date
                <BR>
                <span><input type="radio" name="VIEWBY" value="arrival" <? if ($VIEWBY=="arrival") print "checked" ?>></span>&nbsp;Arrival Date
            </div>
        </fieldset>

        <fieldset>
            <legend>Include</legend>
            <div class="fieldset">
                <div class="label">
                    <? print $clsGlobal->reservTypesCheckBoxes($db, array("ELE_ID"=>"RESTYPE_IDs","RESTYPE_IDs"=>$RESTYPE_IDs,"DEFAULT_ALL"=>true)); ?>
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend>Made by</legend>
            <div class="fieldset">
                <div class="label">
                    <? print $clsGlobal->getUserTypeCheckBoxes($db, array("ELE_ID"=>"MADEBY_IDs","MADEBY_IDs"=>$MADEBY_IDs,"DEFAULT_ALL"=>true)); ?>
                    <div id='callCenterCheckBoxes'>
                        <? print $clsGlobal->getCallCenterCheckBoxes($db, array("ELE_ID"=>"AGENT_IDs","AGENT_IDs"=>$AGENT_IDs,"DEFAULT_ALL"=>false)); ?>
                    </div>
                </div>
            </div>
        </fieldset>    
    </td>
    <td valign='top' width="80%">
      <? include "m.search.filters.simple.php" ?>
    </td>
</tr>
</table>



<div id='inventoryEditBox'></div>