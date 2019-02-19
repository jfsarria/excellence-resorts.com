<?
/*
 * Revised: Aug 01, 2011
 */
?>

<? if ($_DATA['RES_LANGUAGE']!="") { ?>
<div class='reserv_fieldset'>
    <div style='float:left;margin-right:30px;width: 200px;display:none'>
        <fieldset>
        <legend>Show Rates for</legend>
        <div class="fieldset">
            <div class="label">
            <?
            $RSET = $clsGlobal->getUserTypes($db, array());
            if ( $RSET['iCount'] != 0 ) {
                $cnt=0;
                while ($row = $db->fetch_array($RSET['rSet'])) {
                    $CHECKED = "checked"; //(in_array($row['ID'],$_DATA['RES_USERTYPE'])) ? "checked" : "";
                    print "<div><input type='checkbox' name='RES_USERTYPE[]' value='{$row['ID']}' {$CHECKED}>&nbsp;{$row['TYPE_NAME']}</div>";
                    if (fmod(++$cnt,3)==0) print "</tr><tr>";
                }
            }
            ?>
            </div>
        </div>
        </fieldset>
    </div>
    <div style='float:left;margin-right:30px'>
        <fieldset>
        <legend>Geo</legend>
        <div class="fieldset">
            <div class="label">
                <? print $clsGlobal->getCountriesDropDown($db, array('ELE_ID'=>'RES_COUNTRY_CODE','COUNTRY_CODE'=>$_DATA['RES_COUNTRY_CODE'])) ?>
            </div>
            <div class="label">
                <? print $clsGlobal->getStatesDropDown($db, array('ELE_ID'=>'RES_STATE_CODE','STATE_CODE'=>$_DATA['RES_STATE_CODE'])) ?>
            </div>
        </div>
        </fieldset>
    </div>
    <div style='float:left'>
        <fieldset>
        <legend>Special Code</legend>
        <div class="fieldset">
            <div class="label">
                <input type="text" id="RES_SPECIAL_CODE" name="RES_SPECIAL_CODE" value="<? print $_DATA['RES_SPECIAL_CODE'] ?>" style="width:100px;">
            </div>
        </div>
        </fieldset>
    </div>
    <div style="clear:both"></div>
</div>
<? } ?>