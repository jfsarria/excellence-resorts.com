
<?
    if(isset($_DATA['LINMOD'])){

?>
    <fieldset>            
    
        <div id='inventoryEditBox'></div>
        <div style='text-align:center'></div>
           <table class="table">
              
                <tr class="listRowHdr">
                  
                    <?
                        print (isset($_DATA['LINMOD'][0]['PROMOCODE']) && $_DATA['LINMOD'][0]['PROMOCODE'] != "")
                        ? "<td width='30%'>PROMOCODE</td>"
                        : ""
                    ?>
                        <td width="30%">Off</td>
                        <td >Country</td>
                        <td >Applied</td>
                        <td >Active</td>
                    <?
                        print (isset($_DATA['LINMOD'][0]['PROMOCODE']) && $_DATA['LINMOD'][0]['PROMOCODE'] != "")
                        ? "<td width='30%'>Email</td>"
                        : ""
                    ?>
                  
                </tr>
             
              <tbody>
                <?
                $CountAcive = 0;
                $CountInactive = 0;
                $cnt = 1;
                for ($i = 0; $i<count($_DATA['LINMOD']); $i++) {
                    $is_coupon = (isset($_DATA['LINMOD'][$i]['PROMOCODE']) && $_DATA['LINMOD'][$i]['PROMOCODE'] != ""); 
                    
                    if ($_DATA['LINMOD'][$i]['IS_APPLIED'] == 0) {
                        $CountAcive++;  
                    } else {
                        $CountInactive++;  
                    }
                ?>      <tr class='listRow<? print $cnt ?>'>
                   <? print  $is_coupon? "<th>{$_DATA['LINMOD'][$i]['PROMOCODE']} <input type='hidden' name='inv[]' id='inv[]' value='{$_DATA['LINMOD'][$i]['PROMOCODE']}-{$_DATA['LINMOD'][$i]['ID_LIN']}'></th>" : "" ?>
                    <td>
                        <? print isset($_DATA['LINMOD'][$i]['VALUE']) ? $_DATA['LINMOD'][$i]['VALUE'] : "" ?>
                        <? print isset($_DATA['LINMOD'][$i]['SYMBOL']) ? $_DATA['LINMOD'][$i]['SYMBOL'] : "" ?>
                    </td>
                    <td><? print isset($_DATA['LINMOD'][$i]['GEOCOUNTRY']) ? $_DATA['LINMOD'][$i]['GEOCOUNTRY'] : "" ?>
                    </td>
                     <td>
                        <input
                            type="checkbox"
                            id=<?print '"Lin-'.$i.'-APPLIED"';?>
                            name=<?print '"Lin-'.$i.'-APPLIED"';?>
                            value="<?print (isset($_DATA['LINMOD'][$i]['IS_APPLIED'])) ? $_DATA['LINMOD'][$i]['IS_APPLIED'] : "0";?>"
                            <? print (isset($_DATA['LINMOD'][$i]['IS_APPLIED']) && (int)$_DATA['LINMOD'][$i]['IS_APPLIED'] == 1)
                                ? "checked"
                                : "" ?>
                        enabled />
                    </td>

                    <td>
                        <input
                            type="checkbox"
                            id=<?print '"Lin-'.$i.'-ACTIVO"';?> name=<?print '"Lin-'.$i.'-ACTIVO"';?>
                            value="<?print (isset($_DATA['LINMOD'][$i]['IS_ACTIVE'])) ? $_DATA['LINMOD'][$i]['IS_ACTIVE'] : "0";?>"
                            <? print (isset($_DATA['LINMOD'][$i]['IS_ACTIVE']) && (int)$_DATA['LINMOD'][$i]['IS_ACTIVE'] == 1)
                                ? "checked"
                                : "" ?>
                        />
                    </td>
                    <? 
                        //print  $is_coupon? "<td><input type='checkbox' name='email[]' id='email[]' value='{$_DATA['LINMOD'][$i]['EMAIL']}' ".($_DATA['LINMOD'][$i]['EMAIL']!=""?"checked":"")." class='email'>&nbsp;{$_DATA['LINMOD'][$i]['EMAIL']}&nbsp;</td>" : "" 
                        /*print $is_coupon
                        ? "<td><input type='text' name='".$_DATA['PROP_ID']."-{$_DATA['LINMOD'][$i]['ID_LIN']}-{$_DATA['ID_CAB']}' id='email-{$_DATA['LINMOD'][$i]['ID_LIN']}' value='{$_DATA['LINMOD'][$i]['EMAIL']}' class='email'".($_DATA['LINMOD'][$i]['IS_APPLIED']==0?"Enabled":"Disabled")."></td>" : ""*/
                        if ($is_coupon) {
                            print "<td>".$_DATA['LINMOD'][$i]['EMAIL']."</td>";
                        }
                    ?>
                </tr>
                    
                <?
                $cnt *= -1;

              }?>
              </tbody>
            </table>
      <div>
        <br>
        <b>
            Pending:&nbsp;
            <label><?print $CountAcive;?></label>
            &nbsp;
            Used:&nbsp;
            <label><?print $CountInactive;?></label>
        </b>
        <div class="frmBtns">
            <a id="exportButton"><span class="button key">Export to Excel</span></a>
        </div>
      </div>
</fieldset>
<?}?>


<script>

ibe.flashsale.init();
</script>


