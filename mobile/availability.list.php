<div data-role="header" data-theme="x">
    <h1><? print _l("Select Room","Seleccione Habitación",$RES_LANGUAGE) ?><? if ((int)$RES_ROOMS_QTY>1) print " #".$ROOM_NUM ?></h1>
    <a href="#" data-rel="back" data-direction="reverse" data-role="button" data-icon="back" data-iconpos="notext"></a>
</div>


<div data-role="content">	

    <h2 id="prop_name">
        EXCELLENCE <span class="prop_color_<? print $RES_ITEMS['PROPERTY']['CODE'] ?>"><? print str_replace("EXCELLENCE","",strtoupper($RES_ITEMS['PROPERTY']['NAME'])) ?></span>
    </h2>

    <? 
        $data_collapsed = "true";
        include "reservation.summary.top.php" 
    ?>

    <div id="room-list">
    <?
    $IS_VIP = 0;
    $IS_AVAILABLE = count($_SESSION['AVAILABILITY']["RES_ROOM_{$ROOM_NUM}_ROOMS"])!=0 ? true : false;
    $ADULTS_QTY = (int)$_SESSION['AVAILABILITY']["RES_ROOM_{$ROOM_NUM}_ADULTS_QTY"];
    $CHILDREN_QTY = (isset($_SESSION['AVAILABILITY']["RES_ROOM_{$ROOM_NUM}_CHILDREN_QTY"])) ? (int)$_SESSION['AVAILABILITY']["RES_ROOM_{$ROOM_NUM}_CHILDREN_QTY"] : 0;
    $cnt = 0;
    foreach ($_SESSION['AVAILABILITY']["RES_ROOM_{$ROOM_NUM}_ROOMS"] AS $ROOM_ID => $ROOM) { 
        $ITEM_ID = $ROOM_NUM."_".$ROOM_ID;
        $IMAGES = $RES_ITEMS[$ROOM_ID]['IMAGES'];
        $AVAILABLE_NIGHTS = (int)$ROOM['AVAILABLE_NIGHTS'];
        $IS_AVAILABLE = ((int)$AVAILABLE_NIGHTS == (int)$RES_NIGHTS) ? true : false;
        $HAS_PRICE = true;
        $LEFT = 999999;
        if (isset($ROOM['NIGTHS'])&&is_array($ROOM['NIGTHS'])) {
            foreach ($ROOM['NIGTHS'] as $DATE => $DATA) 
                if (is_array($DATA)&&(int)$DATA['INVENTORY']['LEFT'] < $LEFT) 
                    $LEFT = (int)$DATA['INVENTORY']['LEFT'];
        }

        $CLASS_SPECIAL = (isset($ROOM['SPECIAL_NAMES'])&&is_array($ROOM['SPECIAL_NAMES'])) ? $ROOM['SPECIAL_NAMES'] : ((isset($ROOM['CLASS_NAMES'])&&is_array($ROOM['CLASS_NAMES'])) ? $ROOM['CLASS_NAMES'] : array());
        $CLASSES = (isset($ROOM['CLASS_NAMES'])&&is_array($ROOM['CLASS_NAMES'])) ? $ROOM['CLASS_NAMES'] : array();
        if (isset($ROOM['TOTAL'])) {
            $AVG_GROSS = (int)$ROOM['TOTAL']['AVG_GROSS_PN'];
            $AVG_FINAL = (int)$ROOM['TOTAL']['AVG_FINAL_PN'];
            $GROSS = (int)$ROOM['TOTAL']['GROSS'];
            $FINAL = (int)$ROOM['TOTAL']['FINAL'];
        } else {
            $HAS_PRICE = false;
        }
        if ((int)$RES_ITEMS[$ROOM_ID]["IS_VIP"]==1 && $IS_VIP==0) $IS_VIP = 1;
        ?>
        <div class="ui-collapsible-noround" data-role="collapsible" data-theme="c" data-content-theme="c" onclick="startSlide('<?=$ITEM_ID?>')">
            <h3 style="font-size:14px">
            <? 
                if ($IS_VIP==1) print "<span class='IS_VIP_LABEL'>Excellence Club</span><br>";
                print $RES_ITEMS[$ROOM_ID]['NAME_'.$RES_LANGUAGE] ;
            ?>
            </h3>
            <div class="ui-collapsible-box">

                <div class="room-image slider-wrapper">
                    <div id="slider-<?=$ITEM_ID?>">
                    <?
                    if (isset($RES_ITEMS[$ROOM_ID]['IMAGES'])) {
                        $icnt = 0;
                        foreach ($IMAGES as $i => $IMAGE) { ?>
                            <div class="slide<?=(++$icnt)?>">
                                <img src="/<?=$IMAGE?>" alt="" />
                            </div><?
                        }
                    }
                    ?>
                    </div>
                    <div class="slider-direction-nav" id="slider-direction-nav-<?=$ITEM_ID?>"></div>
                    <div class="slider-control-nav" id="slider-control-nav-<?=$ITEM_ID?>"></div>
                </div>

                <div class="room-descr">
                    <? print $RES_ITEMS[$ROOM_ID]['DESCR_'.$RES_LANGUAGE] ?>
                </div>
                <div><b><? print _l("Room Features","Características",$RES_LANGUAGE) ?></b></div>
                <div class="room-features">
                    <? print $RES_ITEMS[$ROOM_ID]['INCLU_'.$RES_LANGUAGE] ?>
                </div>
                <div class="room-class">
                    <a href="#" id="classlink_<? print $ROOM_ID ?>" class="simpleDialog" rel="classlink_<? print $ROOM_ID ?>_html"><? print _l("Promotional Rate Rules","Condiciones de Promoción",$RES_LANGUAGE) ?></a>  
                    <div style="display:none;" id="classlink_<? print $ROOM_ID ?>_html">
                        <div style="text-align:right"><a href="#" class="close" data-role="button" data-icon="delete" data-inline="true" data-mini="true" data-iconpos="notext"></a></div>
                        <div>
                        <? 
                            foreach ($CLASSES as $CLASS_ID => $REFERENCE) {
                                $TXT = $RES_ITEMS[$CLASS_ID]['DESCR_'.$RES_LANGUAGE];
                                print ($RES_LANGUAGE=="EN") ? htmlentities($TXT) : $TXT;
                            }
                        ?>
                        </div>
                    </div>
                </div>
                <div class="room-special">
                    <? 
                    if (isset($ROOM['SPECIAL_NAMES'])&&is_array($ROOM['SPECIAL_NAMES'])) {
                        foreach ($ROOM['SPECIAL_NAMES'] as $SPECIAL_ID => $REFERENCE) { ?>
                            <a href="#" id="speciallink_<? print $ROOM_ID ?>" class="simpleDialog" rel="speciallink_<? print $ROOM_ID ?>_html"><? print $RES_ITEMS[$SPECIAL_ID]['NAME_'.$RES_LANGUAGE] ?></a>  
                            <div style="display:none;" id="speciallink_<? print $ROOM_ID ?>_html">
                                <div style="text-align:right"><a href="#" class="close" data-role="button" data-icon="delete" data-inline="true" data-mini="true" data-iconpos="notext"></a></div>
                                <div>
                                    <? 
                                        $TXT = $RES_ITEMS[$SPECIAL_ID]['DESCR_'.$RES_LANGUAGE];
                                        print ($RES_LANGUAGE=="EN") ? htmlentities($TXT) : $TXT;
                                    ?>
                                </div>
                            </div>
                            <?
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
        <div class="room-summary-bottom ui-collapsible-content ui-body-c ui-corner-bottom ui-collapsible-box" aria-hidden="false">
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td width="100%" nowrap valign="bottom">
                    <?
                        if ($HAS_PRICE && $IS_AVAILABLE) print _l("Per night, All inclusive","Por noche, Todo Incluido",$RES_LANGUAGE);
                    ?>
                </td>

                <td nowrap>
                    <? 
                        if ($IS_AVAILABLE) print ($LEFT<=9) ? "<div class='room-left'>{$LEFT} "._l("rooms left","Habitaciones Disponibles",$RES_LANGUAGE)."!</div>" : "&nbsp;";
                    ?>
                </td>
            </tr>
            <tr>
                <td width="100%">
                    <? if ($HAS_PRICE && $IS_AVAILABLE) { ?>
                        <b>
                        <? if ($AVG_GROSS!=$AVG_FINAL) print "<span class='crossed'>$".number_format($AVG_GROSS)."</span>"; ?>
                        <span class='AVG_FINAL' rel='<? print $AVG_FINAL ?>'>$<? print number_format($AVG_FINAL) ?></span>
                        </b>
                    <? } else print "&nbsp;"; ?>
                </td>
                <td nowrap>
                    <? if ($IS_AVAILABLE) { ?>
                        <a href="/mobile/availability.php?ROOM_NUM_SELECTED=<? print $ROOM_NUM ?>&ROOM_ID_SELECTED=<? print $ROOM_ID ?>" data-role="button" data-theme="x" data-mini="true" data-ajax="false"><? print _l("Continue","Continuar",$RES_LANGUAGE) ?></a>
                    <? } else { ?>
                        NOT AVAILABLE
                    <? } ?>
                </td>
            </tr>
            </table>
        </div>
        <?
    }

    ?>
    </div>

    <script>
        $('.simpleDialog').simpleDialog({
                            showCloseLabel: false,
                            width:"250px",
                            opacity:"0.8"
                        });
    </script>

</div>

