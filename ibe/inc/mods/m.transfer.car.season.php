<fieldset>
    <legend>Select Car</legend>
    <div class="fieldset">
        <div class="field">
            <?
            $RSET = $clsTransfer->getCarByProp($db, array(
              "PROP_ID"=>$PROP_ID,
              "PEOPLE"=>"0"
            ));
            ?>
            <select id="CAR_ID" name="CAR_ID">
            <?
            while ($row = $db->fetch_array($RSET['rSet'])) {
                $CAR = $clsGlobal->cleanUp_rSet_Array($row);
                $SELECTED = isset($_DATA['CAR_ID'])&&(int)$_DATA['CAR_ID']==(int)$CAR['ID'] ? "selected" : "";
                print "<option {$SELECTED} value='{$CAR['ID']}'>{$CAR['NAME_EN']}</option>";
            }
            ?>
            </select>
        </div>
    </div>
</fieldset>
<fieldset>
    <legend>Select Season</legend>
    <div class="fieldset">
        <div class="field">
            <?
            $YEAR = isset($_REQUEST['YEAR']) ? (int)$_REQUEST['YEAR'] : date("Y");
            $RSET = $clsTransferSeasons->getByProperty($db, array(
                "PROP_ID"=>$PROP_ID,
                "YEAR"=>$YEAR
            ));
            ?>
            <select id="SEASON_ID" name="SEASON_ID">
            <?
            while ($row = $db->fetch_array($RSET['rSet'])) {
                $SEASON = $clsGlobal->cleanUp_rSet_Array($row);
                $SELECTED = isset($_DATA['SEASON_ID'])&&(int)$_DATA['SEASON_ID']==(int)$SEASON['ID'] ? "selected" : "";
                print "<option {$SELECTED} value='{$SEASON['ID']}'>{$SEASON['NAME']}</option>";
            }
            ?>
            </select>
        </div>
    </div>
</fieldset>
<fieldset>
    <legend>Price</legend>
    <div class="fieldset">
        <div class="field">
            One way: <input type="text" id="PRICE_ONEWAY" name="PRICE_ONEWAY" value="<? print isset($_DATA['PRICE_ONEWAY']) ? $_DATA['PRICE_ONEWAY'] : "" ?>" class="med<? if (isset($error['PRICE_ONEWAY'])) print " s_required" ?>">
        </div>
        <br>
        <div class="field">
            Round trip: <input type="text" id="PRICE_ROUNDT" name="PRICE_ROUNDT" value="<? print isset($_DATA['PRICE_ROUNDT']) ? $_DATA['PRICE_ROUNDT'] : "" ?>" class="med<? if (isset($error['PRICE_ROUNDT'])) print " s_required" ?>">
        </div>
    </div>
</fieldset>

