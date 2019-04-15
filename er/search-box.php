<?
    $ARR_IN = explode("-", $results['RES_CHECK_IN']);
    $ARR_OUT = explode("-", $results['RES_CHECK_OUT']);
?>
<div id="order">
    <div class="block-title"><?=ln("modify search","modificar")?></div>
    <div class="order-form-wrapper">
        <form id="frmAvailability" action="" method="GET">
            <div class="select-group-item" style="width:164px">
                <label style="float: none; width: 100%; text-align: center;"><?=ln("Select Destination","Seleccione Destino")?></label>
                <span class="select-wrapper" style="float: none; margin-bottom: 10px; margin-left: 0px; width: 163px; margin-top: 6px;">
                    <select id="RES_PROP_ID" name="RES_PROP_ID" rel="<?=$results['RES_PROP_ID']?>">
                        <option value="1" <? print (int)$results['RES_PROP_ID']==1?"selected":""?>>Riviera Cancun, MX</option>
                        <option value="2" <? print (int)$results['RES_PROP_ID']==2?"selected":""?>>Playa Mujeres, MX</option>
                        <option value="3" <? print (int)$results['RES_PROP_ID']==3?"selected":""?>>Punta Cana, DR</option>
                        <option value="6" <? print (int)$results['RES_PROP_ID']==6?"selected":""?>>El Carmen, DR</option>
                        <option value="7" <? print (int)$results['RES_PROP_ID']==7?"selected":""?>>Oyster Bay, JM</option>
                    </select>
                </span>
                <!--.select-wrapper-->
            </div>

            <div class="form-item-text first-form-el">
                <label><?=ln("Arrival","Llegada")?></label>
                <span class="input-wrapper">
                    <input value="<?=$results['RES_CHECK_IN']?>" type="text" name="RES_CHECK_IN" id="datepicker_from" placeholder="" readonly>
                </span><!--.input-wrapper-->
            </div>
            <!--.form-item-text-->
            <div class="form-item-text">
                <label><?=ln("Departure","Salida")?></label>
                <span class="input-wrapper">
                    <input value="<?=$results['RES_CHECK_OUT']?>" type="text" name="RES_CHECK_OUT" id="datepicker_to" placeholder="" readonly>
                </span><!--.input-wrapper-->
            </div>
            <!--.form-item-text-->
            <input name="RES_COUNTRY_CODE" value="<?=$_GEO['RES_COUNTRY_CODE']?>" type="hidden" readonly>
            <!--
            <input name="RES_CHECK_IN" value="<?=$results['RES_CHECK_IN']?>" type="hidden" id="date_from" readonly>        
            <input name="RES_CHECK_OUT" value="<?=$results['RES_CHECK_OUT']?>" type="hidden" id="date_to" readonly>
            -->
            <input type="hidden" name="RES_NIGHTS" id="nights" value="<?=$results['RES_NIGHTS']?>">
            <div class="form-item-select-group">
                <div class="select-group-item">
                    <label><?=ln("Rooms","Hab.")?></label>
                    <span class="select-wrapper">
                        <select id="room-1-count" name="RES_ROOMS_QTY" rel="<?=$results['RES_ROOMS_QTY']?>">
                            <option value="1" <? print (int)$results['RES_ROOMS_QTY']==1?"selected":""?>>1</option>
                            <option value="2" <? print (int)$results['RES_ROOMS_QTY']==2?"selected":""?>>2</option>
                            <option value="3" <? print (int)$results['RES_ROOMS_QTY']==3?"selected":""?>>3</option>
                        </select>
                    </span>
                    <!--.select-wrapper-->
                </div>
                <? for ($ROOM_NUM=1; $ROOM_NUM <= 3; ++$ROOM_NUM) { ?>

                    <!--ROOM <?=$ROOM_NUM?>------------------>
                    <div id="room-<?=$ROOM_NUM?>" class="room <? print $ROOM_NUM<=(int)$results['RES_ROOMS_QTY']?"":"hide"?>">
                        <div class="search-box-line room-line"></div>
                        <div class="room-lbl"><label><?=ln("ROOOM","HAB.")?> <?=$ROOM_NUM?></label></div>
                        <!--.select-group-item-->
                        <div class="select-group-item">
                            <label><?=ln("Guests","Adultos")?></label>
                            <span class="select-wrapper">
                                <select id="room-<?=$ROOM_NUM?>-adults-count" name="RES_ROOM_<?=$ROOM_NUM?>_ADULTS_QTY">
                                    <option value="1" <? print (int)$results['RES_ROOM_'.$ROOM_NUM.'_ADULTS_QTY']==1?"selected":""?>>1</option>
                                    <option value="2" <? print (int)$results['RES_ROOM_'.$ROOM_NUM.'_ADULTS_QTY']==2?"selected":""?>>2</option>
                                    <option value="3" <? print (int)$results['RES_ROOM_'.$ROOM_NUM.'_ADULTS_QTY']==3?"selected":""?>>3</option>
                                </select>
                            </span>
                            <!--.select-wrapper-->
                        </div>
                        <!--.select-group-item-->
                        <div class="select-group-item hide">
                            <label><?=ln("Infants","Infantes")?> 0-3</label>
                            <span class="select-wrapper">
                                <select id="room-<?=$ROOM_NUM?>-infants-count" name="RES_ROOM_<?=$ROOM_NUM?>_CHILD_AGE_5">
                                    <option value="0" <? print (int)$results['RES_ROOM_'.$ROOM_NUM.'_CHILD_AGE_5']==0?"selected":""?>>0</option>
                                    <option value="1" <? print (int)$results['RES_ROOM_'.$ROOM_NUM.'_CHILD_AGE_5']==1?"selected":""?>>1</option>
                                </select>
                            </span>
                            <!--.select-wrapper-->
                        </div>
                        <!--.select-group-item-->
                        <div class="select-group-item hide">
                            <label><?=ln("Children","Niños")?></label>
                            <span class="select-wrapper">
                                <select id="room-<?=$ROOM_NUM?>-children-count" class="room-children-count" name="RES_ROOM_<?=$ROOM_NUM?>_CHILDREN_QTY">
                                    <option value="0">0</option>
                                    <option value="1" <? print (int)$results['RES_ROOM_'.$ROOM_NUM.'_CHILDREN_QTY']==1?"selected":""?>>1</option>
                                    <option value="2" <? print (int)$results['RES_ROOM_'.$ROOM_NUM.'_CHILDREN_QTY']==2?"selected":""?>>2</option>
                                    <option value="3" <? print (int)$results['RES_ROOM_'.$ROOM_NUM.'_CHILDREN_QTY']==3?"selected":""?>>3</option>
                                </select>
                            </span>
                            <!--.select-wrapper-->
                        </div>
                        <!--.select-group-item-->
                        <!--CHILDREN------------------>
                        <div class="room-children-box">
                            <? for ($CHILD_NUM=3; $CHILD_NUM>=1; --$CHILD_NUM) { ?>
                                <div id="room-<?=$ROOM_NUM?>-child-<?=$CHILD_NUM?>" class="group-children <? print $CHILD_NUM<=(int)$results['RES_ROOM_'.$ROOM_NUM.'_CHILDREN_QTY']?"":"hide"?>">
                                    <div class="select-group-label" style="font-size: 10px;text-align:center">
                                        <?=ln("Child","Niño")?> <?=$CHILD_NUM?>
                                    </div>
                                    <!--.select-group-label-->
                                    <div class="select-group-item">
                                        <span class="select-wrapper">
                                            <select id="room-<?=$ROOM_NUM?>-child-<?=$CHILD_NUM?>-year" class="child-age" name="RES_ROOM_<?=$ROOM_NUM?>_CHILD_AGE_<?=$CHILD_NUM?>" disabled>
                                                <option value="4" <? print (int)$results['RES_ROOM_'.$ROOM_NUM.'_CHILD_AGE_'.$CHILD_NUM]==4?"selected":""?>>4 <?=ln("Y","A")?>.</option>
                                                <option value="5" <? print (int)$results['RES_ROOM_'.$ROOM_NUM.'_CHILD_AGE_'.$CHILD_NUM]==5?"selected":""?>>5 <?=ln("Y","A")?>.</option>
                                                <option value="6" <? print (int)$results['RES_ROOM_'.$ROOM_NUM.'_CHILD_AGE_'.$CHILD_NUM]==6?"selected":""?>>6 <?=ln("Y","A")?>.</option>
                                                <option value="7" <? print (int)$results['RES_ROOM_'.$ROOM_NUM.'_CHILD_AGE_'.$CHILD_NUM]==7?"selected":""?>>7 <?=ln("Y","A")?>.</option>
                                                <option value="8" <? print (int)$results['RES_ROOM_'.$ROOM_NUM.'_CHILD_AGE_'.$CHILD_NUM]==8?"selected":""?>>8 <?=ln("Y","A")?>.</option>
                                                <option value="9" <? print (int)$results['RES_ROOM_'.$ROOM_NUM.'_CHILD_AGE_'.$CHILD_NUM]==9?"selected":""?>>9 <?=ln("Y","A")?>.</option>
                                                <option value="10" <? print (int)$results['RES_ROOM_'.$ROOM_NUM.'_CHILD_AGE_'.$CHILD_NUM]==10?"selected":""?>>10 <?=ln("Y","A")?>.</option>
                                                <option value="11" <? print (int)$results['RES_ROOM_'.$ROOM_NUM.'_CHILD_AGE_'.$CHILD_NUM]==11?"selected":""?>>11 <?=ln("Y","A")?>.</option>
                                                <option value="12" <? print (int)$results['RES_ROOM_'.$ROOM_NUM.'_CHILD_AGE_'.$CHILD_NUM]==12?"selected":""?>>12 <?=ln("Y","A")?>.</option>
                                            </select>
                                        </span>
                                        <!--.select-wrapper-->
                                    </div>
                                    <!--.select-group-item-->
                                </div>
                                <!--.group-children-->
                            <? } ?>
                        <div style="clear:both"></div></div>
                    </div>
                    <!--#room-->
                <? } ?>
                <script>
                    var rn = $("#room-1-count").val();
                    $('#room-1 .room-lbl').removeClass('hide');
                    if (rn==1) {
                        $('#room-1 .room-lbl').addClass('hide');
                    }

                </script>
                <div class="search-box-line" style="margin-top:20px"></div>
                <div class="form-item-text third-form-el promo-code">
                    <label>Promo</label>
                    <span class="input-wrapper">
                        <input type="text" name="RES_SPECIAL_CODE" value="<? print $results['RES_SPECIAL_CODE']?>">
                        <input type="hidden" name="T_ACCESO" value="<? print isset($results['T_ACCESO'])?$results['T_ACCESO']:''?>">
                         <input type="hidden" name="ENTORNO" value="<? print isset($results['ENTORNO'])?$results['ENTORNO']:''?>">
                    </span><!--.input-wrapper-->
                </div>
                <!-- 
                <div class="form-item-text third-form-el promo-code">
                    <label>Coupon</label>
                    <span class="input-wrapper">
                       <input type="text" name="RES_COUPON_CODE" id="RES_COUPON_CODE" value="<? print $results['RES_COUPON_CODE']?>">   
                   
                </div>
                -->
            </div>
            <!--.form-select-group-->
            <input type="hidden" name="RES_LANGUAGE" value="<? print $RES_LANGUAGE ?>">
            <input type="hidden" name="RES_STATE_CODE" id="state_code" value="485199146=1503364634">
            <div class="form-item-submit">
                <!--
                <label>&nbsp;</label>
                <span class="submit-wrapper"><input type="submit" value="<?=ln("SEARCH AGAIN","BUSCAR")?>" onclick="submitIBEform()"></span>
                <input type="reset" id="reset-form" value="Reset">
                -->
                <div style="clear:both"><p>&nbsp;</p></div>
            </div>
            <!--.form-item-submit-->
        </form>

        <script>

            function setSearchFrom(selected_date) {
                var from = selected_date.split("-");
                var search_from = new Date(from[0],from[1]-1,from[2]);
                var search_to = new Date(search_from.getFullYear(),search_from.getMonth(),search_from.getDate()+1);

                //alert(search_from + "\n" + search_to);

                $("#datepicker_to").datepicker("setDate", search_to);
                $("#datepicker_to").datepicker("option", "minDate", search_to);

                setResNigths()
            }

            function setSearchTo(selected_date) {
                setResNigths()
            }

            function setResNigths() { 
                var date_from = $('#datepicker_from').val().replace(/-/g, '/');
                var date_from = new Date(date_from+' 4:00:00');
                var date_to = $('#datepicker_to').val().replace(/-/g, '/');   
                var date_to = new Date(date_to+' 12:00:00');
                var diff = Math.floor((date_to.getTime() - date_from.getTime()) / 24 / 60 / 60 / 1000);
                $('#nights').val(diff);
            }


        </script>

        <script>
            jQuery(".group-children select").attr('disabled',false);

            $("#datepicker_from").datepicker('destroy');
            $("#datepicker_from").datepicker({
                minDate: '<?=Date("Y")?>-<?=Date("m")?>-<?=Date("d")?>',
                defaultDate : new Date(<?=$ARR_IN[0]?>,<?=(int)$ARR_IN[1]-1?>,<?=$ARR_IN[2]?>),
                dateFormat: 'yy-mm-dd',
                onSelect: setSearchFrom,
                prevText: "«",
                nextText: "»",
                dayNamesShort: ['S','M','T','W','T','F','S'],
                dayNamesMin: ['S','M','T','W','T','F','S']
            })

            $("#datepicker_to").datepicker('destroy');
            $("#datepicker_to").datepicker({
                minDate: '<?=Date("Y")?>-<?=Date("m")?>-<?=Date("d")?>',
                defaultDate : new Date(<?=$ARR_OUT[0]?>,<?=(int)$ARR_OUT[1]-1?>,<?=$ARR_OUT[2]?>),
                dateFormat: 'yy-mm-dd',
                onSelect: setSearchTo,
                prevText: "«",
                nextText: "»",
                dayNamesShort: ['S','M','T','W','T','F','S'],
                dayNamesMin: ['S','M','T','W','T','F','S']
            })


        </script>

        <style>
          .ui-datepicker-today a {
              background: #edeaeb !important;
              color: #000 !important;
          }
        </style>

        <!--.order-options-->
    </div>

    <div id="wrap-buttons">
        <a onclick="submitIBEform()" class="btn-booking hand" id="btn-search"><?=ln("SEARCH AGAIN","BUSCAR")?></a>
    </div>

    <!--.order-form-wrapper-->
</div>
<!--#order-->
