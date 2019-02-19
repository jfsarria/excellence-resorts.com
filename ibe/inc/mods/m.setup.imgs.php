<?
/*
 * Revised: May 09, 2011
 */
?>
<fieldset>
    <legend>Property Images</legend>
    <div class="fieldset">
        <div class="field">
            <div id="PROP_IMAGES" class="sortable">
            <?
            $ORDER = array();
            if (isset($PROP_ID)) {
                $IRSET = $clsUploads->getByParent($db, array("PARENT_ID"=>$PROP_ID,"TYPE"=>"image"));
                if ($IRSET['iCount']!=0) {
                    while ($irow = $db->fetch_array($IRSET['rSet'])) { 
                        array_push($ORDER,$irow['ID']);
                        print "
                        <div class='ddsitem' id='dds_{$irow['ID']}'>
                            <div class='ddsimg'><A HREF='ups/props/{$irow['NAME']}' rel='prettyPhoto' class='img' title=''><IMG class='thumbnail' SRC='ups/props/T_{$irow['NAME']}' WIDTH='150' BORDER='0'></a></div>
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
                    }
                } else {
                    print "<h6>There are no Images for this Property</h6>";
                }
            }
            ?>
            </div>
        </div>
        <div class="aclear">
            <hr>
            <input type="hidden" name="PROP_IMAGES_ORDER_CURRENT" id="PROP_IMAGES_ORDER_CURRENT" value="<? print implode(",",$ORDER) ?>">
            <input type="hidden" name="PROP_IMAGES_ORDER" id="PROP_IMAGES_ORDER" value="<? print implode(",",$ORDER) ?>">
        </div>
        <div class="label">
            <div class="imgUploaded"><input type="file" name="imgUploaded_1" /></div>
        </div>
        <div class="field">
            <!-- <a href="javascript:void(0)" onclick="ibe.ups.addField('imgUploaded')">Add another image</a> -->
        </div>
    </div>
</fieldset>

