<?
/*
 * Revised: Jun 08, 2015
 */
?>
<fieldset>
    <div class="fieldset m_specials">
        <div class="label">
            <table align="center">
            <tr>
                <td nowrap>Active&nbsp;<span><input type="checkbox" id="IS_ACTIVE" name="IS_ACTIVE" value="1" <? print (isset($_DATA['IS_ACTIVE'])&&(int)$_DATA['IS_ACTIVE']==1) ? "checked" : "" ?>></span>&nbsp;&nbsp;&nbsp;</td>
            </tr>
            </table>                    
        </div>
    </div>
</fieldset>

<fieldset>
    <legend>Banner Images (First Image is english, second is Spanish)</legend>
    <div class="fieldset">
        <div class="field">
            <?
            $ORDER = array();
            if (isset($BANNER_ID)) {
                $IRSET = $clsUploads->getByParent($db, array("PARENT_ID"=>$BANNER_ID,"TYPE"=>"image"));
                if ($IRSET['iCount']!=0) {
                    ?><div id="BANNER_IMAGES" class="sortable"><?
                    while ($irow = $db->fetch_array($IRSET['rSet'])) { 
                        //print "<pre>";print_r($irow);print "</pre>";
                        array_push($ORDER,$irow['ID']);
                        print "
                        <div class='ddsitem' id='dds_{$irow['ID']}'>
                            <div class='ddsimg' style='height:20px'><A HREF='ups/banners/{$irow['NAME']}' rel='prettyPhoto' class='img' title=''><IMG class='thumbnail' SRC='ups/banners/T_{$irow['NAME']}' WIDTH='150' BORDER='0'></a></div>
                            <div class='ddsfile'>
                                <div>
                                    <div class='aleft cbdelete' rel='{$irow['ID']}'><img src='css/img/cross.png' width='16' height='16' border='0' alt='Delete'></div>
                                    <div class='aleft'>&nbsp;{$irow['NAME']}</div>
                                    <div class='aclear'></div>
                                </div>
                                <div _style='display:none'><span><input id='cb_{$irow['ID']}' class='checkbox' type='checkbox' name='DELETE_UPS[]' value='{$irow['ID']}' style='width:auto'></span></div>
                            </div>
                        </div>                    
                        ";
                    }?>
                    </div><?
                } else {
                    print "<h6>There are no Images for this Banner Type</h6>";
                }
            }
            ?>
        </div>
        <div class="aclear">
            <hr>
            <input type="hidden" name="BANNER_IMAGES_ORDER_CURRENT" id="BANNER_IMAGES_ORDER_CURRENT" value="<? print implode(",",$ORDER) ?>">
            <input type="hidden" name="BANNER_IMAGES_ORDER" id="BANNER_IMAGES_ORDER" value="<? print implode(",",$ORDER) ?>">
        </div>
        <div class="label">
            <div class="imgUploaded"><input type="file" name="imgUploaded_1" /></div>
        </div>
        <div class="field">
            <a href="javascript:void(0)" onclick="ibe.ups.addField('imgUploaded')">Add another image</a>
        </div>
    </div>
</fieldset>

<fieldset>
    <legend>Banner Right Text</legend>
    <div class="fieldset">
        <div class="label">English</div>
        <div class="field"><input type="text" id="RTEXT_EN" name="RTEXT_EN" value="<? print isset($_DATA['RTEXT_EN']) ? $_DATA['RTEXT_EN'] : "" ?>" class="full<? if (isset($error['NAME']) && $_IBE_LANG=="EN") print " s_required" ?>"></div>
        <div class="label">Spanish</div>
        <div class="field"><input type="text" id="RTEXT_SP" name="RTEXT_SP" value="<? print isset($_DATA['RTEXT_SP']) ? $_DATA['RTEXT_SP'] : "" ?>" class="full<? if (isset($error['NAME']) && $_IBE_LANG=="SP") print " s_required" ?>"></div>
    </div>
</fieldset>

<fieldset>
    <legend>Banner Link Label</legend>
    <div class="fieldset">
        <div class="label">English</div>
        <div class="field"><input type="text" id="RLABEL_EN" name="RLABEL_EN" value="<? print isset($_DATA['RLABEL_EN']) ? $_DATA['RLABEL_EN'] : "" ?>" class="full<? if (isset($error['NAME']) && $_IBE_LANG=="EN") print " s_required" ?>"></div>
        <div class="label">Spanish</div>
        <div class="field"><input type="text" id="RLABEL_SP" name="RLABEL_SP" value="<? print isset($_DATA['RLABEL_SP']) ? $_DATA['RLABEL_SP'] : "" ?>" class="full<? if (isset($error['NAME']) && $_IBE_LANG=="SP") print " s_required" ?>"></div>
    </div>
</fieldset>

<fieldset>
    <legend>Color</legend>
    <div class="fieldset">
        <div class="label">Font (e.g. #000000)</div>
        <div class="field"><input type="text" id="FONT_COLOR" name="FONT_COLOR" value="<? print isset($_DATA['FONT_COLOR']) ? $_DATA['FONT_COLOR'] : "" ?>" class="full<? if (isset($error['NAME']) && $_IBE_LANG=="EN") print " s_required" ?>"></div>
        <div class="label">Background (e.g. #FF0000)</div>
        <div class="field"><input type="text" id="BG_COLOR" name="BG_COLOR" value="<? print isset($_DATA['BG_COLOR']) ? $_DATA['BG_COLOR'] : "" ?>" class="full<? if (isset($error['NAME']) && $_IBE_LANG=="EN") print " s_required" ?>"></div>
    </div>
</fieldset>

<fieldset>
    <legend>Conditions</legend>
    <div class="fieldset">
        <div class="label">English</div>
        <div class="field"><textarea id="CONDITIONS_EN" name="CONDITIONS_EN" class="full"><? print isset($_DATA['CONDITIONS_EN']) ? $_DATA['CONDITIONS_EN'] : "" ?></textarea></div>
        <div class="label">Spanish</div>
        <div class="field"><textarea id="CONDITIONS_SP" name="CONDITIONS_SP" class="full"><? print isset($_DATA['CONDITIONS_SP']) ? $_DATA['CONDITIONS_SP'] : "" ?></textarea></div>
    </div>
</fieldset>

<fieldset>
    <legend>Publish Banner in the following pages</legend>
    <div class="fieldset">
        <div class="label">All URLS starting with the ones below will include the banner. One URL per line. Leave empty to publish in all page<br>Example:<br>/suites/<br>/booking/</div>
        <div class="field"><textarea id="PUBLISH_URLS" name="PUBLISH_URLS" class="full" style="height:300px"><? print isset($_DATA['PUBLISH_URLS']) ? $_DATA['PUBLISH_URLS'] : "" ?></textarea></div>
    </div>
</fieldset>


<style>
.country_group {
    margin-bottom:20px;
}
.country_group .gname {
    padding-bottom:10px;
}
</style>
<fieldset>
    <legend>Geo Targeting</legend>
    <div class="fieldset">
        <div class="label">
        <?
        $GROPUS = array(
            "AA"=>"Prime Countries",
            "LA"=>"Latin America",
            "EU"=>"Europe",
            "--"=>"Rest of the world"
        );  
        foreach ($GROPUS as $GROUP => $GROUP_NAME) { ?>
            <table id='country_group_<? print $GROUP ?>' class="country_group pickList" width='100%'>
            <tr><td colspan="10" class='gname'><b><? print $GROUP_NAME ?></b>&nbsp;&nbsp;&nbsp;<a href='javascript:void(0)' onclick="ibe.select.checkCountries('<? print $GROUP ?>', true)">Check all</a>&nbsp;-&nbsp;<a href='javascript:void(0)' onclick="ibe.select.checkCountries('<? print $GROUP ?>', false)">Uncheck all</a></td></tr>
            <tr>
            <?
            $COUNTRIES = $clsBanners->getCountries($db, array("BANNER_ID"=>$BANNER_ID,"AS_ARRAY"=>true)); 
            if (count($COUNTRIES)==0) $COUNTRIES['US'] = 1;
            //print "<pre>";print_r($COUNTRIES);print "<pre>";
            $RSET = $clsGlobal->getCountries($db, array("GROUP"=>$GROUP));
            if ( $RSET['iCount'] != 0 ) {
                $cnt=0;
                while ($row = $db->fetch_array($RSET['rSet'])) {
                    $CHECKED = (array_key_exists($row['CODE'],$COUNTRIES)) ? "checked" : "";
                    print "<td width='25%' class='pickListItem i{$cnt}' nowrap><span><input type='checkbox' name='COUNTRY_CODE[]' value='{$row['CODE']}' {$CHECKED}></span>&nbsp;{$row['NAME']}</td>";
                    if (fmod(++$cnt,4)==0) print "</tr><tr>";
                }
            }
            ?>
            </tr>
            </table>
        <? } ?>
        </div>
    </div>
</fieldset>


<fieldset>
    <legend>Output</legend>
    <div class="fieldset">
        <div class="label">HTML</div>
        <div class="field"><textarea id="HTML" name="HTML" class="full" style="height:300px"><? print isset($_DATA['HTML']) ? $HTML = html_entity_decode($_DATA['HTML']) : "" ?></textarea></div>
    </div>
</fieldset>
