<div id="wrap-summary">
    <div class="block-title"><?=ln("reservation summary","resumen de la reserva")?></div>
    <div id="summary" class="right-side-box">
        <?
          $PROP_NAME = "";
          if ($results['RES_PROP_ID']=="1") {
            $PROP_NAME = "Excellence <span style='color:#f78214'>Riviera Cancun</span>";
          } else if ($results['RES_PROP_ID']=="2") {
            $PROP_NAME = "Excellence <span style='color:#f00a82'>Playa Mujeres</span>";
          } else if ($results['RES_PROP_ID']=="3") {
            $PROP_NAME = "Excellence <span style='color:#8fd526'>Punta Cana</span>";
          } else if ($results['RES_PROP_ID']=="6") {
            $PROP_NAME = "Excellence <span style='color:#754d9f'>El Carmen</span>";
          } else if ($results['RES_PROP_ID']=="7") {
            $PROP_NAME = "Excellence <span style='color:#23cdb8'>Oyster Bay</span>";
          }
          print "<b>$PROP_NAME</b>";
        ?><br><br>
        <?=ln("Check In","Llegada")?>: <?=shortDate($results['RES_CHECK_IN'])?><br>
        <?=ln("Check Out","Salida")?>: <?=shortDate($results['RES_CHECK_OUT'])?><br>
        <?=$results['RES_NIGHTS']?>&nbsp;<?=ln("Nights","Noches")?><br>
        <?=$results['RES_ROOMS_QTY']?>&nbsp;<?=ln("Rooms","Habs.")?>&nbsp;<?=$results['RES_ROOMS_ADULTS_QTY']?> <?=ln("Guests","Adultos")?>
        <!-- &nbsp;<?=$results['RES_ROOMS_CHILDREN_QTY']?> <?=ln("Children","Niños")?> -->
        <br>
    </div>
    <div id="btn-modify-search" class="right-side-box"><a href="javascript:void(0)" onclick="search_modify(1)"><?=ln("Modify Search","Modificar")?></a></div>
</div>
<div id="wrap-modify">
    <? include "search-box.php"; ?>
</div>
<div id="wrap-selections">
    <div id="selected-rooms" class="right-side-box">
        <? for ($ROOM_NUM=1; $ROOM_NUM <= (int)$results['RES_ROOMS_QTY']; ++$ROOM_NUM) { ?>
        <div id="summary_room_<?=$ROOM_NUM?>" class="summary_room <?=$ROOM_NUM>1?"hidden":"" ?>">
            <? if ((int)$results['RES_ROOMS_QTY']>1) { ?>
            <span class="room_guets"><?=ln("Room","Hab.")?> <?=$ROOM_NUM?>, <?=$results["RES_ROOM_{$ROOM_NUM}_ADULTS_QTY"]?> <?=ln("Guests","Adultos")?>
            <!-- , <?=$results["RES_ROOM_{$ROOM_NUM}_CHILDREN_QTY"]?> <?=ln("Children","Niños")?> -->
            <br>
            <? } ?>
            <span class="room_name"></span>&nbsp;
            <? if ((int)$results['RES_ROOMS_QTY']>1) { ?>
            <a id="btn-modify-room-<?=$ROOM_NUM?>" class="_hidden" href="javascript:void(0)" onclick="click_room_tab('<?=$ROOM_NUM?>', true)"><?=ln("Modify","Modificar")?></a><br>
            <? } ?>
            <div class="room_rate"><a class="rate-detail" href="javascript:void(0)" room_num="<?=$ROOM_NUM?>"><?=ln("View Rate Details","Ver detalles de la tarifa")?></a></div>
        </div>
        <? } ?>
        <div id="room_conversion" style="padding-bottom:10px">
            <label><?=ln("See prices in: ","Ver precios en: ")?></label>
            <span class="select-wrapper">
                <select id="QUOTE" name="QUOTE" rel="USDUSD" onchange="quote_Change(this)">
                    <option value="USDUSD">USD</option>
                    <?
                      $CODES = ARRAY('CAD','AUD','GBP','EUR','MXN','BRL');
                      foreach ($CODES as $CODE) {
                        print "<option value='USD{$CODE}'>{$CODE}</option>";
                      }
                    ?>
                </select>
            </span>
            <!--.select-wrapper-->
        </div>

        <div id="totals" class="<? print (int)$results['RES_ROOMS_QTY']>1?"hidden":""?>" style="clear:both">
            <div id="lbl_total_usd"><?=ln("ROOM FEE","CARGO POR HAB.")?> (<span id="money_code"></span>):</div>
            <div id="total_was"></div>&nbsp;<div id="total_is"></div>
            <div style="clear:both"></div>
        </div>
        <div id="summary_transfer">
        </div>

        <div id="total_conversion" style="padding-bottom:10px" class="hidden">
            <div id="conv_lbl_total_usd"><?=ln("EQUIVALENT COST","COSTO ESTIMADO")?>:</div>
            <div id="conv_total_is"></div>&nbsp;(<span id="conversion_code"></span>)
            <div style="clear:both"></div>
        </div>

    </div>
</div>
<div id="wrap-buttons">
    <? for ($ROOM_NUM=1; $ROOM_NUM <= (int)$results['RES_ROOMS_QTY']; ++$ROOM_NUM) { ?>
    <a id="btn-continue-<?=$ROOM_NUM?>" class="btn-booking hand btn-continue <? print $ROOM_NUM!=1 ? "hidden" : "" ?>" onclick="select_continue({'AVAILABLE':<?=$IS_ROOM_AVAILABLE[$ROOM_NUM]?>,'ROOM_NUM':<?=$ROOM_NUM?>,'ROOMS_QTY':<?=$results['RES_ROOMS_QTY']?>,'LN':'<?=$RES_LANGUAGE?>'})"><?=$ROOM_NUM==(int)$results['RES_ROOMS_QTY']?ln("complete booking","completar reservar"):ln("continue","continuar")?></a>
    <? } ?>

    <? if ($IS_ALL_AVAILABLE) { ?>
        <a id="btn-book-now" class="btn-booking hand hidden" onclick="book_now()"><?=ln("book now","Reservar ahora")?></a>
    <? } ?>
</div>
<div id="loading-making-booking" class="hidden">
    <img src="loading-small.gif">
</div>
<div id='summary_bottom'>
    <div class="hdr">
        <?=ln("RATES NOT VALID FOR GROUPS<br><br>For help with reservations,<br>please call","TARIFAS NO VÁLIDA PARA GRUPOS<br><br>Para ayuda con reservaciones,<br>por favor llamar")?>:
    </div>
    <div class="phones">
        USA 1 866 540 25 85<br>
        Canada 1 866 451 15 92<br>
        Mexico 01 800 966 36 70<br>
        UK 0 800 051 6244
    </div>
    <div class="policy">
        <a onclick="popover_open($(this),'popover_cancellation_policy')" href="javascript:void(0)"><?=ln("Cancellation and<br>Modification Policy","Poliza de Cancelación<br>y Modificaciones")?></a>
    </div>
</div>

