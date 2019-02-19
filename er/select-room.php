<div id="select-rooms" data-rooms-qty="<?=$results["RES_ROOMS_QTY"]?>">
    <!--
    <? if ((int)$results['RES_ROOMS_QTY']>1) { ?>
        <ul id='room-tabs'>
            <? for ($ROOM_NUM=1; $ROOM_NUM<=(int)$results['RES_ROOMS_QTY']; ++$ROOM_NUM) { ?>
                <li id="tab-room-<?=$ROOM_NUM?>" onclick="click_room_tab('<?=$ROOM_NUM?>')">Select Room <?=$ROOM_NUM?></li>
            <? } ?>
        </ul>
    <? } ?>
    -->
    <script>
        var PROPERTY_BED_TYPES = <?=json_encode($results['RES_ITEMS']['PROPERTY']['BED_TYPES'])?>;
    </script>

    <?
    $RES_NIGHTS = (int)$results['RES_NIGHTS'];
    $IS_ROOM_AVAILABLE = array();
    $IS_ALL_AVAILABLE = true;
    for ($ROOM_NUM=1; $ROOM_NUM <= (int)$results['RES_ROOMS_QTY']; ++$ROOM_NUM) { 
        //print "<br>".$ROOM_NUM." --> ".count($results["RES_ROOM_{$ROOM_NUM}_ROOMS"]);
        $IS_ROOM_AVAILABLE[$ROOM_NUM] = 0;
        $IS_ALL_AVAILABLE = count($results["RES_ROOM_{$ROOM_NUM}_ROOMS"])!=0 ? true : false;
        if (!$IS_ALL_AVAILABLE) {
            break;
        } else {
            foreach ($results["RES_ROOM_{$ROOM_NUM}_ROOMS"] AS $ROOM_ID => $ROOM) { 
                $AVAILABLE_NIGHTS = (int)$ROOM['AVAILABLE_NIGHTS'];
                $IS_ALL_AVAILABLE = ($AVAILABLE_NIGHTS == $RES_NIGHTS) ? true : false;
                //print "<br> * $AVAILABLE_NIGHTS == $RES_NIGHTS";
                if ($IS_ALL_AVAILABLE) {
                    $IS_ROOM_AVAILABLE[$ROOM_NUM] = 1;
                    break;
                }
            }
            if (!$IS_ALL_AVAILABLE) {
                break;
            }
        }
    }


    //print "<br> --> ".($IS_ALL_AVAILABLE?"SI":"NO");

    for ($ROOM_NUM=1; $ROOM_NUM<=(int)$results['RES_ROOMS_QTY']; ++$ROOM_NUM) { 
        $AVAILABLE_ROOM_CNT = 0;
        $IS_ROOM_AVAILABLE[$ROOM_NUM] = isset($IS_ROOM_AVAILABLE[$ROOM_NUM]) ? $IS_ROOM_AVAILABLE[$ROOM_NUM] : 0;
        //print "<br>is room {$ROOM_NUM} available: ".$IS_ROOM_AVAILABLE[$ROOM_NUM];
        ?>
        <div id="list-room-num-<?=$ROOM_NUM?>" class="list-rooms hidden" data-adults="<?=$results["RES_ROOM_{$ROOM_NUM}_ADULTS_QTY"]?>" data-children="<?=$results["RES_ROOM_{$ROOM_NUM}_CHILDREN_QTY"]?>">
            <? if ((int)$results['RES_ROOMS_QTY']>1) { ?>
            <div class="room-selection-header">
                <?=ln("SELECT ROOM","ESCOGER HABITACIÓN")?> <?=$ROOM_NUM?> (<?=$results["RES_ROOM_{$ROOM_NUM}_ADULTS_QTY"]?> <?=ln("ADULTS","ADULTOS")?><!-- , <?=$results["RES_ROOM_{$ROOM_NUM}_CHILDREN_QTY"]?> <?=ln("CHILDREN","NIÑOS")?> -->)
            </div>
            <? } ?>
            <div class="sep-line"></div>
            <? 
            $ORDERED_LIST = $results["RES_ROOM_{$ROOM_NUM}_ROOMS_ORDER"];
            $ROOM_LIST = $results["RES_ROOM_{$ROOM_NUM}_ROOMS"];
            //print "ORDERED_LIST $ROOM_NUM:<pre>";print_r($ORDERED_LIST);print "</pre>";
            foreach ($ORDERED_LIST as $i => $ROOM_ID) { 
                $ROOM = $ROOM_LIST[$ROOM_ID];
                $ROOM_DETAILS = $results["RES_ITEMS"][$ROOM_ID];
                $IMAGES = $ROOM_DETAILS['IMAGES'];
                $ITEM_ID = $ROOM_NUM."_".$ROOM_ID;
                $IS_AVAILABLE = ((int)$ROOM['AVAILABLE_NIGHTS'] == (int)$results['RES_NIGHTS']);
                $IS_VIP = $ROOM_DETAILS['IS_VIP'];
                $BED_TYPES = $ROOM_DETAILS["BEDS"];
                $LEFT = 999999;
                $HAS_PRICE = false;
                if (isset($ROOM['NIGTHS'])&&is_array($ROOM['NIGTHS'])) {
                    foreach ($ROOM['NIGTHS'] as $DATE => $DATA) 
                        if (is_array($DATA)&&(int)$DATA['INVENTORY']['LEFT'] < $LEFT) 
                            $LEFT = (int)$DATA['INVENTORY']['LEFT'];
                }
                if (isset($ROOM['TOTAL'])) {
                    $GROSS = (int)$ROOM['TOTAL']['GROSS'];
                    $FINAL = (int)$ROOM['TOTAL']['FINAL'];
                    $AVG_GROSS = (int)$ROOM['TOTAL']['AVG_GROSS_PN'];
                    $AVG_FINAL = (int)$ROOM['TOTAL']['AVG_FINAL_PN'];
                    $ROOMS_LEFT = (int)$ROOM['TOTAL']['ROOMS_LEFT'];
                    $HAS_PRICE = true;
                }
                $RATE_RULES = array();
                $CLASS_NAMES = (isset($ROOM['CLASS_NAMES'])&&is_array($ROOM['CLASS_NAMES'])) ? $ROOM['CLASS_NAMES'] : array();
                foreach ($CLASS_NAMES as $CLASS_ID => $REFERENCE) {
                    $RATE_RULES[] = $results['RES_ITEMS'][$CLASS_ID]['DESCR_'.$RES_LANGUAGE];
                }
                $RATE_RULES = htmlentities(implode(" - ",$RATE_RULES));
                $SPECIALS = array();
                $SPECIAL_NAMES = (isset($ROOM['SPECIAL_NAMES'])&&is_array($ROOM['SPECIAL_NAMES'])) ? $ROOM['SPECIAL_NAMES'] : array();
                foreach ($SPECIAL_NAMES as $SPECIAL_ID => $REFERENCE) {
                    $SPECIALS[] = array(
                        "NAME" => $results['RES_ITEMS'][$SPECIAL_ID]['NAME_'.$RES_LANGUAGE],
                        "DESCR" => $results['RES_ITEMS'][$SPECIAL_ID]['DESCR_'.$RES_LANGUAGE]
                    );
                }
                ?>
                <div id="<?=$ITEM_ID?>" class="room <?="vip_".$IS_VIP?> <?=($IS_AVAILABLE && $HAS_PRICE)?"":"not-available"?>" rel="<?=$i?>" data-vip="<?=$IS_VIP?>" data-bed_types="<?=$BED_TYPES?>">
                    <div class="wrap-room-picture">
                        <? foreach ($IMAGES as $i => $IMAGE) { ?>
                        <div class="room-thumbnail"><a href="javascript:void(0)" onclick="see_more('<?=$ITEM_ID?>')"><img src="/<?=$IMAGE?>"></a></div>
                        <? break; } ?>
                    </div>
                    <div class="wrap-room-description">
                        <div class="box">
                            <div class="room-name"><a href="javascript:void(0)" onclick="see_more('<?=$ITEM_ID?>')"><?=$ROOM_DETAILS['NAME_'.$RES_LANGUAGE]?></a></div>

                            <div class="room-image slider-wrapper">
                                <div id="slider-<?=$ITEM_ID?>">
                                    <? 
                                    $icnt = 0;
                                    foreach ($IMAGES as $i => $IMAGE) { ?>
                                        <div class="slide<?=(++$icnt)?>">
                                            <img src="/<?=$IMAGE?>" alt="" />
                                        </div><?
                                    } ?>
                                </div>
                                <div class="slider-direction-nav" id="slider-direction-nav-<?=$ITEM_ID?>"></div>
                                <div class="slider-control-nav" id="slider-control-nav-<?=$ITEM_ID?>"></div>
                            </div>

                            <div class="room-description"><?=excerpt($ROOM_DETAILS['DESCR_'.$RES_LANGUAGE])?></div>
                            <div class="room-features"><div><?=ln("ROOM FEATURES","EQUIPAMIENTO DE LA HABITACIÓN")?></div><?=$ROOM_DETAILS['INCLU_'.$RES_LANGUAGE]?></div>
                            <div class="room-more"><a href="javascript:void(0)" onclick="see_more('<?=$ITEM_ID?>')">+ <?=ln("SEE MORE","VER MÁS")?></a></div>
                        </div>
                    </div>
                    <div class="wrap-room-price">
                        <? 
                        if ($IS_AVAILABLE && $HAS_PRICE) { 
                          ++$AVAILABLE_ROOM_CNT;
                          $PRICE_WAS = ($AVG_GROSS!=$AVG_FINAL) ? $AVG_GROSS : 0;
                          $TOTAL_PRICE_WAS = ($GROSS!=$FINAL) ? $GROSS : 0;
                          $ROOM_CNT = $ROOM_NUM."_".$AVAILABLE_ROOM_CNT;
                          ?>
                          <div class="btn-select" onclick="select_room('<?=$ITEM_ID?>', true)"><?=ln("select","escoger")?></div>
                          <div class="room_currency price-was" data-usd='<?=$PRICE_WAS?>' rel='<?=$PRICE_WAS?>'></div>
                          <div class="room_currency price-is" data-usd='<?=$AVG_FINAL?>' rel='<?=$AVG_FINAL?>'></div>
                          <div class="room_currency total-price-was" data-usd='<?=$TOTAL_PRICE_WAS?>' rel='<?=$TOTAL_PRICE_WAS?>'></div>
                          <div class="room_currency total-price-is" data-usd='<?=$FINAL?>' rel='<?=$FINAL?>'></div>
                          <div class="price-descr"></div>
                          <div class="rooms-left"><?=$ROOMS_LEFT<10?$ROOMS_LEFT." ".ln("rooms left","disponible"):""?></div>

                          <script>
                              dataLayer.push({"Room_List_<?=$ROOM_CNT?>": "<?=$ROOM_DETAILS['NAME_EN']?>"});
                          </script>
                        <? } else { ?>
                          <div class="room-not-available"><?=ln("Not Available","No Disponible")?></div>
                        <? }?>
                    </div>
                    <div class="wrap-room-details">
                        <? $RATE_DETAILS = daily_rate_details($results, array("ROOM_ID"=>$ROOM_ID,"ROOM_NUM"=>$ROOM_NUM)); ?>
                        <div class="room-rate-details">
                            <div><?=$RATE_DETAILS?></div>
                            <table class="ratesSpecialsTbl" width="100%" border="0" cellpadding="5" cellspacing="0">
                            <tr>
                                <td width="100%">
                                    <div><a class="tip" data-tip="<?=$RATE_RULES?>" href="javascript:void(0)"><?=ln("Promotional Rate Rules","Reglas de la promoción")?></a></div>
                                </td>
                                <td rowspan="2"><a href="javascript:void(0)" onclick="see_less('<?=$ITEM_ID?>')"><?=ln("CLOSE","CERRAR")?></a></td>
                            </tr>
                            <tr>
                                <td>
                                <? foreach ($SPECIALS AS $i => $SPECIAL) { ?>
                                    <div><a class="tip" data-tip="<?=htmlentities($SPECIAL['DESCR'])?>" href="javascript:void(0)"><?=$SPECIAL['NAME']?></a></div>
                                <? } ?>
                                </td>
                            </tr>
                            </table>
                        </div>
                        <div class="room-less"></div>
                    </div>
                    <div class="room-gap">&nbsp;</div>
                </div>
            <? } ?>
        </div>
        <script>
            set_lbl_vip($("#list-room-num-<?=$ROOM_NUM?>"));
        </script>
    <? } ?>
    <script>
        click_room_tab('1');
    </script>
</div>
