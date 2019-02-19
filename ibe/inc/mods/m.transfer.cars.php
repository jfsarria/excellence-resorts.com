<fieldset>
    <legend>Transfers Car Name</legend>
    <div class="fieldset">
        <div class="label">English</div>
        <div class="field"><input type="text" id="NAME_EN" name="NAME_EN" value="<? print isset($_DATA['NAME_EN']) ? $_DATA['NAME_EN'] : "" ?>" class="full<? if (isset($error['NAME']) && $_IBE_LANG=="EN") print " s_required" ?>"></div>
        <div class="label">Spanish</div>
        <div class="field"><input type="text" id="NAME_SP" name="NAME_SP" value="<? print isset($_DATA['NAME_SP']) ? $_DATA['NAME_SP'] : "" ?>" class="full<? if (isset($error['NAME']) && $_IBE_LANG=="SP") print " s_required" ?>"></div>
    </div>
</fieldset>

<fieldset>
    <div class="fieldset">
        <div class="label">
            <table>
            <tr>
                <td width="10%" nowrap><span><input type="checkbox" id="IS_ACTIVE" name="IS_ACTIVE" value="1" <? print (isset($_DATA['IS_ACTIVE'])&&(int)$_DATA['IS_ACTIVE']==1) ? "checked" : "" ?>></span>&nbsp;Active&nbsp;&nbsp;</td>
                <td width="10%" nowrap>Max PAX:&nbsp;<span><input type="text" id="MAX_PAX" name="MAX_PAX" value="<? print isset($_DATA['MAX_PAX']) ? $_DATA['MAX_PAX'] : "" ?>" style="width:50px"></span></td>
                <td width="10%" nowrap><span><input type="radio" name="TYPE" value="SUV" <? print (isset($_DATA['TYPE'])&&($_DATA['TYPE']=="SUV"||empty($_DATA['TYPE']))) ? "checked" : "" ?>></span> SUV</td>
                <td width="10%" nowrap><span><input type="radio" name="TYPE" value="VAN" <? print (isset($_DATA['TYPE'])&&$_DATA['TYPE']=="VAN") ? "checked" : "" ?>></span> VAN</td>
                <td width="10%" nowrap>Archive&nbsp;<span><input type="checkbox" id="IS_ARCHIVE" name="IS_ARCHIVE" value="1" <? print (isset($_DATA['IS_ARCHIVE'])&&(int)$_DATA['IS_ARCHIVE']==1) ? "checked" : "" ?>></span></td>
            </tr>
            </table>                    
        </div>
    </div>
</fieldset>

<style>
#tbl_transfers_price td {
  padding-right:50px;
}
</style>

<fieldset>
    <legend>Price</legend>
    <div class="fieldset">
        <div class="field">
          <table id="tbl_transfers_price">
          <tr>
            <td width="20%">Year</td>
            <td width="40%">One Way in USD</td>
            <td width="40%">Round Trip in USD</td>
          </tr>
          <tr>
            <td><input type="text" id="PRICE_1_YEAR" name="PRICE_1_YEAR" value="<? print isset($_DATA['PRICE_1_YEAR']) ? $_DATA['PRICE_1_YEAR'] : "" ?>" style="width:100%"></td>
            <td><input type="text" id="PRICE_1_ONEWAY" name="PRICE_1_ONEWAY" value="<? print isset($_DATA['PRICE_1_ONEWAY']) ? $_DATA['PRICE_1_ONEWAY'] : "" ?>" style="width:100%"></td>
            <td><input type="text" id="PRICE_1_ROUNDT" name="PRICE_1_ROUNDT" value="<? print isset($_DATA['PRICE_1_ROUNDT']) ? $_DATA['PRICE_1_ROUNDT'] : "" ?>" style="width:100%"></td>
          </tr>
          <tr>
            <td><input type="text" id="PRICE_2_YEAR" name="PRICE_2_YEAR" value="<? print isset($_DATA['PRICE_2_YEAR']) ? $_DATA['PRICE_2_YEAR'] : "" ?>" style="width:100%"></td>
            <td><input type="text" id="PRICE_2_ONEWAY" name="PRICE_2_ONEWAY" value="<? print isset($_DATA['PRICE_2_ONEWAY']) ? $_DATA['PRICE_2_ONEWAY'] : "" ?>" style="width:100%"></td>
            <td><input type="text" id="PRICE_2_ROUNDT" name="PRICE_2_ROUNDT" value="<? print isset($_DATA['PRICE_2_ROUNDT']) ? $_DATA['PRICE_2_ROUNDT'] : "" ?>" style="width:100%"></td>
          </tr>
          <tr>
            <td><input type="text" id="PRICE_3_YEAR" name="PRICE_3_YEAR" value="<? print isset($_DATA['PRICE_3_YEAR']) ? $_DATA['PRICE_3_YEAR'] : "" ?>" style="width:100%"></td>
            <td><input type="text" id="PRICE_3_ONEWAY" name="PRICE_3_ONEWAY" value="<? print isset($_DATA['PRICE_3_ONEWAY']) ? $_DATA['PRICE_3_ONEWAY'] : "" ?>" style="width:100%"></td>
            <td><input type="text" id="PRICE_3_ROUNDT" name="PRICE_3_ROUNDT" value="<? print isset($_DATA['PRICE_3_ROUNDT']) ? $_DATA['PRICE_3_ROUNDT'] : "" ?>" style="width:100%"></td>
          </tr>
          </table>
        </div>
    </div>
</fieldset>

<fieldset>
    <legend>Car Images</legend>
    <div class="fieldset">
        <div class="field">
            <div id="CAR_IMAGES" class="sortable">
            <?
            $ORDER = array();
            if (isset($CAR_ID)) {
                $IRSET = $clsUploads->getByParent($db, array("PARENT_ID"=>$CAR_ID,"TYPE"=>"image"));
                if ($IRSET['iCount']!=0) {
                    while ($irow = $db->fetch_array($IRSET['rSet'])) { 
                        array_push($ORDER,$irow['ID']);
                        print "
                        <div class='ddsitem' id='dds_{$irow['ID']}'>
                            <div class='ddsimg'><A HREF='ups/transfers/{$irow['NAME']}' rel='prettyPhoto' class='img' title=''><IMG class='thumbnail' SRC='ups/transfers/T_{$irow['NAME']}' WIDTH='150' BORDER='0'></a></div>
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
                    print "<h6>There are no Images for this Car</h6>";
                }
            }
            ?>
            </div>
        </div>
        <div class="aclear">
            <hr>
            <input type="hidden" name="CAR_IMAGES_ORDER_CURRENT" id="CAR_IMAGES_ORDER_CURRENT" value="<? print implode(",",$ORDER) ?>">
            <input type="hidden" name="CAR_IMAGES_ORDER" id="CAR_IMAGES_ORDER" value="<? print implode(",",$ORDER) ?>">
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
    <legend>Description</legend>
    <div class="fieldset">
        <div class="label">English</div>
        <div class="field"><textarea id="DESCR_EN" name="DESCR_EN" class="full"><? print isset($_DATA['DESCR_EN']) ? $_DATA['DESCR_EN'] : "" ?></textarea></div>
        <div class="label">Spanish</div>
        <div class="field"><textarea id="DESCR_SP" name="DESCR_SP" class="full"><? print isset($_DATA['DESCR_SP']) ? $_DATA['DESCR_SP'] : "" ?></textarea></div>
    </div>
</fieldset>
