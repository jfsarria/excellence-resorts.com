
<?
    if(isset($_DATA['LINMOD'])){
?>
    <fieldset>            
    <legend>Inventory</legend>
        
           <table class="table">
              
                <tr class="listRowHdr">
                  <th scope="col">Off</th>
                  <th scope="col"></th>
                  <th scope="col">Country</th>
                  <th scope="col">Active</th>
                  
                </tr>
             
              <tbody>
                <?
                $CountAcive=0;
                $CountInactive=0;                 
                for($i=0;$i<count($_DATA['LINMOD']);$i++){
                if($_DATA['LINMOD'][$i]['IS_ACTIVE']==1){
                  $CountAcive++;  
                }else{
                  $CountInactive++;  
                }
                ?>      <tr >
                    <th scope="row"><input type="text" id=<?print '"Lin-'.$i.'-VALOR"';?> name=<?print '"Lin-'.$i.'-VALOR"';?> value="<? print isset($_DATA['LINMOD'][$i]['VALUE']) ? $_DATA['LINMOD'][$i]['VALUE'] : "" ?>"  >
                    </th>
                    <td><input type="text" id=<?print '"Lin-'.$i.'-SIMBOLO"';?> name=<?print '"Lin-'.$i.'-SIMBOLO"';?> value="<? print isset($_DATA['LINMOD'][$i]['SYMBOL']) ? $_DATA['LINMOD'][$i]['SYMBOL'] : "" ?>"  >
                    </td>
                    <td><input type="text" id=<?print '"Lin-'.$i.'-DIVISA"';?> name=<?print '"Lin-'.$i.'-DIVISA"';?> value="<? print isset($_DATA['LINMOD'][$i]['GEOCOUNTRY']) ? $_DATA['LINMOD'][$i]['GEOCOUNTRY'] : "" ?>"  >
                    </td>
                    <td><input type="checkbox" id=<?print '"Lin-'.$i.'-ACTIVO"';?> name=<?print '"Lin-'.$i.'-ACTIVO"';?> value="1" <? print (isset($_DATA['LINMOD'][$i]['IS_ACTIVE'])&&(int)$_DATA['LINMOD'][$i]['IS_ACTIVE']==1) ? "checked" : "" ?> >
                    </td>

                </tr>
                    
                <?}?>
                
                
              </tbody>
            </table>
      <div>
        <b>Pending:&nbsp;<label><?print $CountAcive;?></label>
        &nbsp;
        Used:&nbsp;<label><?print $CountInactive;?></label></b>
      </div>
    
</fieldset>
<?}?>


