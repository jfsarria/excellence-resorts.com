<?error_reporting(E_ALL ^ E_NOTICE);
ini_set('memory_limit', '512M');
ob_start();

$EXCEL_NAME = "report.xls";

$DATA_START = "1317988800"; //Wed, 01 Jun 2011 00:00:00 GMT

$USER_ID = $_SESSION['AUTHENTICATION']['ID'];
$USR_PROP = $clsUsers->getProperties($db, array('USER_ID'=>$USER_ID));
while ($prow = $db->fetch_array($USR_PROP['rSet'])) { $USR_PROPS[$prow['PROP_ID']] = $prow; }

//PRINT "<PRE>";print_r($USR_PROPS);PRINT "</PRE>";

?>
<script type="text/javascript" src="/ibe/js/jquery-ui.min.js"></script>	
<link  rel="stylesheet" type="text/css" href="/ibe/css/style_calendar-ibe.css" />
<div class="ListBtns">
    <table>
    <tbody><tr>
        <td><h2>Transfers Report</h2></td>
    </tr>
    </tbody></table>
</div>

<form action="/ibe#result" enctype="multipart/form-data" method="get" id="editfrm">
    <input type="hidden" name="SRCH" id="SRCH" VALUE="0">
    <input type="hidden" name="EXPORT" id="EXPORT" VALUE="0">
    <input type="hidden" name="RClist" id="RClist" VALUE="0">
    <input type="hidden" name="PAGE_CODE" value="reports_transfers">
    <div class="aclear"></div>
<table width="100%" cellspacing="2">
<tbody><tr>
    <td width="20%" valign="top" nowrap="" style="padding-right:20px">
        <fieldset>
            <legend>Include</legend>
            <div class="fieldset">
                <div class="label">
                <?
                if(count($_GET['PROP_IDs'])==0) {
                  foreach ($USR_PROPS as $PROP_ID => $PROP) {
                    $_GET['PROP_IDs'][] = $PROP_ID;
                  }
                }

                foreach ($USR_PROPS as $PROP_ID => $PROP) {
                  ?> <div><span <?if(in_array($PROP_ID,$_GET['PROP_IDs'])){?>class="selectedItem"<?}?>><input type="checkbox" <?if(in_array($PROP_ID,$_GET['PROP_IDs'])){?>checked=""<?}?> id="PROP_IDs_<?=$PROP_ID?>" value="<?=$PROP_ID?>" name="PROP_IDs[]"></span>&nbsp;<?=$PROP['PROP_NAME']?></div> <?
                }
                ?>
              </div>
            </div>
        </fieldset>

        <fieldset>
            <legend>Include</legend>
            <div class="fieldset">
                <div class="label"> 
					<? if(count($_GET['RES_INC'])==0) {
						$_GET['RES_INC'][]='TA';
						$_GET['RES_INC'][]='Admin';
						$_GET['RES_INC'][]='Public';
					}
					?>
                    <div><span class="inc_src <?if(in_array('TA',$_GET['RES_INC'])){?>selectedItem<?}?>"><input     type="checkbox" <?if(in_array('TA',$_GET['RES_INC'])){?>checked<?}?> value="TA" name="RES_INC[]"></span>&nbsp;Travel Agents</div>
					<div><span class="inc_src <?if(in_array('Admin',$_GET['RES_INC'])){?> selectedItem<?}?>"><input  type="checkbox" <?if(in_array('Admin',$_GET['RES_INC'])){?>checked<?}?> value="Admin" name="RES_INC[]"></span>&nbsp;Admin</div>
					<div><span class="inc_src <?if(in_array('Public',$_GET['RES_INC'])){?> selectedItem<?}?>"><input type="checkbox" <?if(in_array('Public',$_GET['RES_INC'])){?>checked<?}?> value="Public" name="RES_INC[]"></span>&nbsp;Public</div>           
				</div>
            </div>
        </fieldset>


    </td>
    <td width="80%" valign="top">
        <fieldset>
            <legend>Generate report</legend>
            <div class="fieldset"> 
				<?if($_GET['report']=='') $_GET['report'] = 'year';?>
                <span <?if($_GET['report']=='day')  {?>class="selectedItem"<?}?>><input type="radio" name="report"  value="day"  <?if($_GET['report']=='day')  {?>checked<?}?>></span>&nbsp;Day&nbsp;&nbsp;&nbsp;&nbsp;
                <!--<span <?if($_GET['report']=='week') {?>class="selectedItem"<?}?>><input type="radio" name="report" value="week" <?if($_GET['report']=='week') {?>checked<?}?>></span>&nbsp;Week&nbsp;&nbsp;&nbsp;&nbsp;-->
                <span <?if($_GET['report']=='month'){?>class="selectedItem"<?}?>><input type="radio" name="report" value="month"<?if($_GET['report']=='month'){?>checked<?}?>></span>&nbsp;Month&nbsp;&nbsp;&nbsp;&nbsp;
                <span <?if($_GET['report']=='year') {?>class="selectedItem"<?}?>><input type="radio" name="report" id="repy" value="year" <?if($_GET['report']=='year') {?>checked<?}?>></span>&nbsp;Year
            </div> 
        </fieldset>
        <fieldset>
            <legend>Select report type</legend>
			
            <div class="fieldset" id="booking_data_by">  
                <span <?if($_GET['booking_data_by']!='arrival')  {?>class="selectedItem"<?}?>><input type="radio" <?if($_GET['booking_data_by']!='arival')  {?>checked=""<?}?> name="booking_data_by" value="sale"></span>&nbsp; by date of sale
                &nbsp;&nbsp;&nbsp;&nbsp;
                <span <?if($_GET['booking_data_by']=='arrival')  {?>class="selectedItem"<?}?>><input type="radio"  <?if($_GET['booking_data_by']=='arrival')  {?>checked=""<?}?> name="booking_data_by" value="arrival"></span>&nbsp;by arrival Date
            </div>
			
        </fieldset>	


		<script>
		$(document).ready(function() {
            /*
			$("#booking_data_by_par input").click( function (){
				if($(this).val()=='booking') {
					$('#booking_data_by').css('display','block');
				} else {
					$('#booking_data_by').css('display','none');
				}
			});			
			*/

			var dates = $( "#from, #to" ).datepicker({
				defaultDate: "+1w",
				changeMonth: true,
				changeYear: true,
				minDate: new Date(<?=date('Y',mktime(0,0,0,1,1,(date('Y')-1)));?>,0,1),
				numberOfMonths: 1,
				prevText: "«",
				nextText: "»",
				onSelect: function( selectedDate ) {
					var option = this.id == "from" ? "minDate" : "maxDate",
						instance = $( this ).data( "datepicker" ),
						date = $.datepicker.parseDate(
							instance.settings.dateFormat ||
							$.datepicker._defaults.dateFormat,
							selectedDate, instance.settings );
					dates.not( this ).datepicker( "option", option, date );
				}
			});
			$('#compare').change (function() {
				if($(this).attr('checked')) {
					$('#repy').attr('checked','checked');
					$('#repy').parent().parent().children('span').removeClass('selectedItem');
					$('#repy').parent().addClass('selectedItem');
					
					$('.inc_src').addClass('selectedItem');
					$('.inc_src input').attr('checked','checked');
				} else {
					$('#compare_dates').css('display','none');
					$('#normal_dates').css('display','block');
				}
			
			});
			$('#compare').trigger('change');
		});
		</script>

		<?
			if($_GET['from']=='') $_GET['from'] = date('m/d/Y',mktime(0,0,0,1,1,date('Y')));
			if($_GET['to']=='')   $_GET['to']   = date('m/d/Y');
		?>
        <fieldset>
            <legend>Dates</legend>
            <div class="fieldset">
				<label for="from">From</label>
				<input type="text" id="from" name="from" value="<?=$_GET['from'];?>"/>
				<label for="to">to</label>
				<input type="text" id="to" name="to" value="<?=$_GET['to'];?>"/>
		
				<br/><span <?if($_GET['compare']=='on'){?>class="selectedItem"<?}?>><input type="checkbox" <?if($_GET['compare']=='on'){?>checked=""<?}?> value="on" id="compare" name="compare"></span>&nbsp; Compare to last year
				<br/><br/>
				 <a onclick="$('#SRCH').val('1');$('#EXPORT').val('0');$('#RClist').val('0');$('#editfrm').submit()"><span class="button key">Get Report</span></a>
				 <a onclick="$('#SRCH').val('1');$('#EXPORT').val('1');$('#RClist').val('0');$('#editfrm').submit()"><span class="button key">Get Excel</span></a>
				 <a onclick="$('#SRCH').val('1');$('#EXPORT').val('1');$('#RClist').val('1');$('#editfrm').submit()"><span class="button key">Get Transfers/Customer list</span></a>
            </div> 
        </fieldset>

    </td>
</tr>
</tbody></table>

<div id="inventoryEditBox"></div></form>


<a name="result"></a>
<?
if($_GET['SRCH']) {

  if(in_array('1',$_GET['PROP_IDs'])) $HOTEL[] = 'XRC';
  if(in_array('2',$_GET['PROP_IDs'])) $HOTEL[] = 'XPM';
  if(in_array('3',$_GET['PROP_IDs'])) $HOTEL[] = 'XPC';
  if(in_array('4',$_GET['PROP_IDs'])) $HOTEL[] = 'LAM';
  if(in_array('5',$_GET['PROP_IDs'])) $HOTEL[] = 'FPM';
  if(in_array('6',$_GET['PROP_IDs'])) $HOTEL[] = 'XEC';
  if(in_array('7',$_GET['PROP_IDs'])) $HOTEL[] = 'XOB';
	
	$YEAR = 2011;

	//Ã”Ã®Ã°Ã¬Ã¨Ã°Ã³Ã¥Ã¬ Ã§Ã Ã¯Ã°Ã®Ã± Ãª Ã�Ã„
	$from = explode('/',$_GET['from']);
	$from_compare = mktime(0,0,1,$from[0],$from[1],($from[2]-1));
	$from = mktime(0,0,1,$from[0],$from[1],$from[2]);
	$to = explode('/',$_GET['to']);
	$to_compare = mktime(0,0,1,$to[0],$to[1],($to[2]-1));
	$to = mktime(0,0,1,$to[0],$to[1],$to[2]);
	$W = ''; $W_compare = '';
	
	
	$data = array();
	$YEAR_S = date('Y',$from);
	$YEAR_F = date('Y',$to);

    if ($_GET['booking_data_by']=='arrival') $YEAR_S -= 1;
    
	//Ã‚Ã»Ã¡Ã®Ã°ÃªÃ  Ã¨Ã§ Ã­Ã®Ã¢Ã®Ã© Ã¡Ã Ã§Ã»
	if($_GET['booking_data_by']=='arrival') $W .= " AND (`restbl`.`CHECK_IN` >= '".date('Y-m-d',$from)."' AND `restbl`.`CHECK_IN` <= '".date('Y-m-d',$to)."') ";
		else $W .= " AND (`restbl`.`CREATED` > '".date('Y-m-d 00:00:00',$from)."' AND `restbl`.`CREATED` < '".date('Y-m-d 23:59:59',$to)."') ";

	if($_GET['report_type']=='cancellation') $W .= ' AND (`restbl`.`STATUS` = 0) ';
		else {
			if($_GET['rebooking']=='on') $W	.= " AND (`restbl`.`STATUS` = '-1' OR `restbl`.`STATUS` = 1) "; 
				else $W .= " AND (`restbl`.`STATUS` = 1) "; 
		}
	$WHO = '';
	if(in_array('Public',$_GET['RES_INC'])) $WHO[] = ' (`restbl`.SOURCE_ID=`restbl`.GUEST_ID 	AND `restbl`.SOURCE_ID=`restbl`.OWNER_ID ) '; 
	if(in_array('Admin',$_GET['RES_INC']))  $WHO[] = ' (`restbl`.SOURCE_ID<>`restbl`.GUEST_ID 	AND `restbl`.SOURCE_ID<>`restbl`.OWNER_ID) '; 
	if(in_array('TA',$_GET['RES_INC'])) 	$WHO[] = ' (`restbl`.SOURCE_ID<>`restbl`.GUEST_ID 	AND `restbl`.SOURCE_ID=`restbl`.OWNER_ID ) '; 
	$W .= ' AND ('.implode(' OR ',$WHO).' ) '; 
	/*
	foreach($HOTEL as $tbl_name) {
		$sql[] = "SELECT `restbl`.`ID`, `restbl`.`NUMBER`, `restbl`.`CHECK_IN`, `restbl`.`CHECK_OUT`, `restbl`.`NIGHTS`, `restbl`.`ROOMS`, `restbl`.`ADULTS`, `restbl`.`CHILDREN`, `restbl`.`TOTAL`, `restbl`.`METHOD`, `restbl`.`CANCELLED`, `restbl`.`CREATED`, `restbl`.`STATUS` , '".$tbl_name."' 			FROM `RESERVATIONS_".$tbl_name."_".$YEAR."` as `restbl` WHERE 1=1  ".$W;	
	}		
	*/
	for($YEAR_NOW = $YEAR_S; $YEAR_NOW<=$YEAR_F; $YEAR_NOW++) {
        if (is_array($HOTEL)) {
            foreach($HOTEL as $tbl_name) {			
                $sql = "
                SELECT `restbl`.`ID`, `restbl`.`NUMBER`, `restbl`.`CHECK_IN`, `restbl`.`CHECK_OUT`, `restbl`.`NIGHTS`, `restbl`.`ROOMS`, `restbl`.`ADULTS`, `restbl`.`CHILDREN`, `restbl`.`TOTAL`, `restbl`.`METHOD`, `restbl`.`CANCELLED`, `restbl`.`CREATED`, `restbl`.`STATUS` , '".$tbl_name."', `GUESTS`.`STATE`, `GUESTS`.`COUNTRY`, TRANSFER_TYPE, TRANSFER_CAR, TRANSFER_FEE
                FROM `RESERVATIONS_".$tbl_name."_".$YEAR_NOW."` as `restbl`, `GUESTS` 
                WHERE STATUS <> 0 AND TRANSFER_CAR <> 0 AND TRANSFER_TYPE <> '' AND `GUESTS`.`ID` = `restbl`.`GUEST_ID` AND `restbl`.`CREATED` > '".date('Y-m-d',$DATA_START)."' 	".$W;		
                //print "a: ".$sql."<hr>";
                $r = mysql_query($sql);// or die(mysql_error().' SQL: '.$sql);
                $datat = array();
                for($datat=array();$row=@mysql_fetch_assoc($r);$datat[]=$row);
                $data = array_merge($data,$datat);				
                echo '<!--'.$sql.'-->';
            }
        }
	} 
	
	//Ã‚Ã»Ã¡Ã®Ã°ÃªÃ  Ã¨Ã§ Ã±Ã²Ã Ã°Ã®Ã© Ã¡Ã Ã§Ã»
	if ($from < $DATA_START) {
		//Ã“Ã‘Ã‹ÃŽÃ‚ÃˆÃŸ
		$W_old = '';
		if($_GET['booking_data_by']=='arrival') $W_old .= " AND (`restbl`.`RES_FROM` >= '".date('Y-m-d',$from)."' AND `restbl`.`RES_FROM` <= '".date('Y-m-d',$to)."') ";			
			else $W_old .= " AND (`restbl`.`RES_CREATED` > '".date('Y-m-d 00:00:00',$from)."' AND `restbl`.`RES_CREATED` < '".date('Y-m-d 23:59:59',$to)."') ";
				
		if($_GET['report_type']=='cancellation') $W_old .= ' AND ( `restbl`.`RES_STATUS` = -1 AND `restbl`.`RES_REBOOKED` = \'\' ) ';	
			else $W_old .= " AND ( `restbl`.`RES_STATUS` = 0 OR `restbl`.`RES_STATUS` = -2 ) "; 
					 
		
		$t = ''; $t[] = ' 1=0 ';
		foreach($HOTEL as $e) $t[] = " `XPC`= '".$e."' ";
		$W_old .= ' AND ('.implode(' OR ', $t).' ) ';	
	
	
		$sql = "
        SELECT  `restbl`.`RES_NUMBER` as`ID`, `restbl`.`RES_NUMBER` as `NUMBER`, `restbl`.`RES_FROM` as `CHECK_IN`, `restbl`.`RES_TO` as `CHECK_OUT`, `restbl`.`RES_DAYS` as `NIGHTS`, `restbl`.`RES_ROOMS` as `ROOMS`, `restbl`.`RES_GUESTS` as `ADULTS`, '0', `restbl`.`RES_TOTAL` as `TOTAL`, 'old', `restbl`.`RES_MODIFIED`, `restbl`.`RES_CREATED` as `CREATED`, `restbl`.`RES_STATUS` , 'old', TRANSFER_TYPE, TRANSFER_CAR, TRANSFER_FEE
		FROM `v_OLD_RES` as `restbl` 
        WHERE STATUS <> 0 AND TRANSFER_CAR <> 0 AND TRANSFER_TYPE <> '' AND 1=1 ".$W_old;	 

        //print "b: ".$sql."<hr>";
		$r = mysql_query($sql);// or die(mysql_error().' SQL: '.$sql);
		$datat = array();
		for($datat=array();$row=@mysql_fetch_assoc($r);$datat[]=$row);
		$data = array_merge($data,$datat);				
		//echo $sql;		
	}
	
	
	
	if($_GET['RClist']==0) {?>
		<?ob_start();?>
		<table id="report_tbl" cellspacing="0">
		<?if($isEXPORT) {?>
			<tr><td colspan="8">
				<?if($_GET['report']=='day') {?>Daily<?}?> 
				<?if($_GET['report']=='month') {?>Monthly<?}?> 
				<?if($_GET['report']=='year') {?>Annually<?}?> 	
				
				
				<?if($_GET['booking_data_by']=='arrival') {?>by arrival date<?}?>
				<?if($_GET['booking_data_by']=='sale')    {?>by date of sale<?}?>
				 from <?=date('F d, Y',$from);?> to <?=date('F d, Y',$to);?></td></tr>
			<tr><td colspan="8">Includes: <?$t = '';foreach($HOTEL as $e) $t[] = $e; echo implode(', ',$t);?> </td></tr>
			<tr><td colspan="8">Includes: <?$t = '';
				if(in_array('Public',$_GET['RES_INC'])) $t[] = 'Public';
				if(in_array('Admin',$_GET['RES_INC']))  $t[] = 'Admin';
				if(in_array('TA',$_GET['RES_INC']))     $t[] = 'Travel Agents';
				echo implode(', ',$t);	
			?></td></tr>
		<?}?>
		
			<tr> 
				<th><?if($_GET['booking_data_by']=='arrival'){?>Check-in Date<?} else {?>Book Date<?}?></th>
				<th><?if($_GET['report_type']=='cancellation') {?>Cancelled<?} else {?>All-Bookings<?}?></th>	
				<th>One-ways</th> 	
				<th>Round-trips</th> 	
				<th>Booking $</th> 	
			</tr>		
		<?	
		//REPORT
		if(!$_GET['compare']) {
			if($_GET['report']=='day') {
				for($i = $from; $i<= $to; $i+=60*60*24) {
					$daymass = array();
					foreach($data as $el) {
						if($_GET['booking_data_by']=='arrival') {$dt_cr = explode('-',$el['CHECK_IN']);}
							else {$dt_cr = explode('-',$el['CREATED']);}
						$dt_cr = mktime(0,0,0,$dt_cr[1],$dt_cr[2],$dt_cr[0])+100;
						//echo 'DATE - '.date('H:i d.m.Y',$dt_cr).' === '.date('H:i d.m.Y',$i).' '.date('H:i d.m.Y',($i+60*60*24)).'---<br>';
						if($dt_cr >= $i && $dt_cr <= ($i+60*60*24)) $daymass[] = $el;
					}
					//echo '<hr>';
					?>
					<tr>
						<td><?=date('m.d.Y',$i);?></td>
                        <?php report_line($daymass) ?>
					</tr><?		
				}	
				//TOTAL 
				?>
				<tr>
                    <?php report_total($data) ?>
				</tr><?		
			/* *************** 
				*** MONTH ***
				**************
			*/
			} elseif ($_GET['report']=='month') {
				//ÃŠÃ®Ã«-Ã¢Ã® Ã¬Ã¥Ã±Ã¿Ã¶Ã¥Ã¢
				$sM = date('m',$from);
				$fM = date('m',$to);
				$sY = date('Y',$from);
				$fY = date('Y',$to);
				//echo $sM. ' - '.$fM.' | '.$sY.' - '.$fY.' = ';
				$diffM = $fM - $sM + 1 + 12*($fY - $sY);
				//echo $diffM; 
			
				for($i = 0; $i< $diffM; $i++) {
					//Month start 
					if($i==0) $sD = $from;
						else {
							$t = mktime(0,0,0,(date('m',$from)+$i),1,date('Y',$from));
							$sD = $t;
						}
					if($i==($diffM-1)) $fD =$to;
						else {
							$t = mktime(23,59,59,(date('m',$from)+$i+1),0,date('Y',$from));
							$fD = $t;
						}
					
					$daymass = array();
					for($j=$sD;$j<=$fD;$j+=60*60*24) {
						//Ã¯Ã¥Ã°Ã¥Ã¡Ã¨Ã°Ã Ã¥Ã¬ Ã¤Ã­Ã¨ Ã¢ ÃªÃ Ã¦Ã¤Ã®Ã¬ Ã¬Ã¥Ã±Ã¿Ã¶Ã¥
						foreach($data as $el) {
							if($_GET['booking_data_by']=='arrival') 
								$dt_cr = explode('-',$el['CHECK_IN']);
							else 
								$dt_cr = explode('-',$el['CREATED']);
								
							$dt_cr = mktime(0,0,0,$dt_cr[1],$dt_cr[2],$dt_cr[0])+100;
							//echo $dt_cr;
							//echo 'DATE - '.date('H:i d.m.Y',$dt_cr).' === '.date('H:i d.m.Y',$j).' '.date('H:i d.m.Y',($j+60*60*24)).'---<br>';
							if($dt_cr >= $j && $dt_cr <= ($j+60*60*24)) { 
								$daymass[] = $el;
							}
						}
					}
					//$daymass - Ã¢Ã®Ã² Ã²Ã³Ã² Ã³ Ã­Ã Ã± Ã¬Ã Ã±Ã±Ã¨Ã¢ Ã± Ã°Ã¥Ã§Ã¥Ã°Ã¢Ã Ã¶Ã¨Ã¿Ã¬Ã¨ Ã§Ã  
					//echo '<pre>';
					//print_r($daymass); echo '<hr>';
					
					
					?>
					<tr>
						<td><?=(date('m.d.Y',$sD).' - '.date('m.d.Y',$fD));?></td>
						<?php report_line($daymass) ?>
					</tr><?		
				}	
				//TOTAL
				?>
				<tr>
					<?php report_total($data) ?>
				</tr><?		
			/* *************** 
				*** YEAR ***
				**************
			*/
			} elseif ($_GET['report']=='year') {
				//ÃŠÃ®Ã«-Ã¢Ã® Ã¬Ã¥Ã±Ã¿Ã¶Ã¥Ã¢
				$sY = date('Y',$from);
				$fY = date('Y',$to);
				//echo $sM. ' - '.$fM.' | '.$sY.' - '.$fY.' = ';
				$diffY = $fY - $sY + 1 ;
				//echo $diffY; 
			
				for($i = 0; $i< $diffY; $i++) {
					//Month start 
					if($i==0) $sD = $from;
						else {
							$t = mktime(0,0,0,(date('m',$from)),1,(date('Y',$from)+$i));
							$sD = $t;
						}
					if($i==($diffY-1)) $fD = $to;
						else {
							$t = mktime(23,59,59,(date('m',$from)),0,(date('Y',$from)+$i+1));
							//echo date('H:i d.m.Y',$t).'<hr>';
							$fD = $t;
						}
					//echo date('H:i d.m.Y',$sD).'--'.date('H:i d.m.Y',$fD).'<br>';
					$daymass = array();
					for($j=$sD;$j<=$fD;$j+=60*60*24) {
						//Ã¯Ã¥Ã°Ã¥Ã¡Ã¨Ã°Ã Ã¥Ã¬ Ã¤Ã­Ã¨ Ã¢ ÃªÃ Ã¦Ã¤Ã®Ã¬ Ã¬Ã¥Ã±Ã¿Ã¶Ã¥
						foreach($data as $el) {
							if(strpos($el['CHECK_IN'],' ')) $el['CHECK_IN'] = substr($el['CHECK_IN'],0,strpos($el['CHECK_IN'],' '));
							if(strpos($el['CREATED'],' ')) 	$el['CREATED']  = substr($el['CREATED'], 0,strpos($el['CREATED'], ' '));
							if($_GET['booking_data_by']=='arrival') 
								$dt_cr = explode('-',$el['CHECK_IN']);
							else 
								$dt_cr = explode('-',$el['CREATED']);
							//echo $el['CREATED'].' -- '.strpos($el['CREATED'],' ').' = <br>';
							//print_r($dt_cr);	
							$dt_cr = mktime(0,0,0,$dt_cr[1],$dt_cr[2],$dt_cr[0])+100;
							//echo $dt_cr;
							//echo 'DATE - '.date('H:i d.m.Y',$dt_cr).' === '.date('H:i d.m.Y',$j).' '.date('H:i d.m.Y',($j+60*60*24)).'---<br>';
							if($dt_cr >= $j && $dt_cr <= ($j+60*60*24)) { 
								$daymass[] = $el;
							}
						}
					}
					//$daymass - Ã¢Ã®Ã² Ã²Ã³Ã² Ã³ Ã­Ã Ã± Ã¬Ã Ã±Ã±Ã¨Ã¢ Ã± Ã°Ã¥Ã§Ã¥Ã°Ã¢Ã Ã¶Ã¨Ã¿Ã¬Ã¨ Ã§Ã  
					//echo '<pre>';
					//print_r($daymass); echo '<hr>';
					//print_R($data);
					
					?>
					<tr>
						<td><?=(date('m.d.Y',$sD).' - '.date('m.d.Y',$fD));?></td>
						<?php report_line($daymass) ?>
					</tr><?		
				}	
				//TOTAL 
				?>
				<tr>
					<?php report_total($data) ?>
				</tr><?		
			}
			
			/* **************
			*** STATISTIC ***
			************** */
			$states = array();
			$countries = array();
			foreach($data as $el) {
				if($el['STATE']) $states[$el['STATE']]++;
					else $states['other']++;
				if($el['COUNTRY']) $countries[$el['COUNTRY']]++;
					else $countries['other']++;
			}
			arsort($states);
			arsort($countries);
			?><tr><td colspan="8" style="text-align: left"><b>Statistic:</b></td></tr>
			<tr><td colspan="8" style="text-align: left">STATES: <?foreach($states as $k=>$el) { if($k!='other') echo $k.' ('.$el.'), '; }?> other(<?=$states['other'];?>)</td></tr>
			<tr><td colspan="8" style="text-align: left">COUNTRIES: <?foreach($countries as $k=>$el) { if($k!='other') echo $k.' ('.$el.'), '; }?> other(<?=$countries['other'];?>)</td></tr>
			
			<?
			
		/* ***************
		   *** COMPARE ***
		   *************** */
		} else {
			
			
			/**** Ã’Ã…ÃŠÃ“Ã™ÃˆÃ‰ ÃƒÃŽÃ„ ****/
			//ÃŠÃ®Ã«-Ã¢Ã® Ã¬Ã¥Ã±Ã¿Ã¶Ã¥Ã¢
			$sY = date('Y',$from);
			$fY = date('Y',$to);
			//echo $sM. ' - '.$fM.' | '.$sY.' - '.$fY.' = ';
			$diffY = $fY - $sY + 1 ;
			//echo $diffY; 
		
			for($i = 0; $i< $diffY; $i++) {
				//Month start 
				if($i==0) $sD = $from;
					else {
						$t = mktime(0,0,0,(date('m',$from)),1,(date('Y',$from)+$i));
						$sD = $t;
					}
				if($i==($diffY-1)) $fD = $to;
					else {
						$t = mktime(23,59,59,(date('m',$from)),0,(date('Y',$from)+$i+1));
						//echo date('H:i d.m.Y',$t).'<hr>';
						$fD = $t;
					}
				//echo date('H:i d.m.Y',$sD).'--'.date('H:i d.m.Y',$fD).'<br>';
				$daymass = array();
				for($j=$sD;$j<=$fD;$j+=60*60*24) {
					//Ã¯Ã¥Ã°Ã¥Ã¡Ã¨Ã°Ã Ã¥Ã¬ Ã¤Ã­Ã¨ Ã¢ ÃªÃ Ã¦Ã¤Ã®Ã¬ Ã¬Ã¥Ã±Ã¿Ã¶Ã¥
					foreach($data as $el) {
						if($_GET['booking_data_by']=='arrival') 
							$dt_cr = explode('-',$el['CHECK_IN']);
						else 
							$dt_cr = explode('-',$el['CREATED']);
							
						$dt_cr = mktime(0,0,0,$dt_cr[1],$dt_cr[2],$dt_cr[0])+100;
						//echo $dt_cr;
						//echo 'DATE - '.date('H:i d.m.Y',$dt_cr).' === '.date('H:i d.m.Y',$j).' '.date('H:i d.m.Y',($j+60*60*24)).'---<br>';
						if($dt_cr >= $j && $dt_cr <= ($j+60*60*24)) { 
							$daymass[] = $el;
						}
					}
				}
				//$daymass - Ã¢Ã®Ã² Ã²Ã³Ã² Ã³ Ã­Ã Ã± Ã¬Ã Ã±Ã±Ã¨Ã¢ Ã± Ã°Ã¥Ã§Ã¥Ã°Ã¢Ã Ã¶Ã¨Ã¿Ã¬Ã¨ Ã§Ã  
				//echo '<pre>';
				//print_r($daymass); echo '<hr>';
				
				
				?>
				<tr>
					<td><?=(date('m.d.Y',$sD).' - '.date('m.d.Y',$fD));?></td>
					<?php report_line($daymass) ?>
				</tr><?		
			}	
			
			/**** COMPARE YEAR ****/
			
			
			//Ã‚Ã»Ã¡Ã®Ã°ÃªÃ 
			$data = array();
			$YEAR_S = date('Y',$from_compare);
			$YEAR_F = date('Y',$to_compare);

            if ($_GET['booking_data_by']=='arrival') $YEAR_S -= 1;

			$W = '';
			//Ã‚Ã»Ã¡Ã®Ã°ÃªÃ  Ã¨Ã§ Ã­Ã®Ã¢Ã®Ã© Ã¡Ã Ã§Ã»
			if($_GET['booking_data_by']=='arrival') $W .= " AND (`restbl`.`CHECK_IN` >= '".date('Y-m-d',$from_compare)."' AND `restbl`.`CHECK_IN` <= '".date('Y-m-d',$to_compare)."') ";
				else $W .= " AND (`restbl`.`CREATED` > '".date('Y-m-d 00:00:00',$from_compare)."' AND `restbl`.`CREATED` < '".date('Y-m-d 23:59:59',$to_compare)."') ";
			if($_GET['report_type']=='cancellation') $W .= ' AND (`restbl`.`STATUS` = 0) ';
				else {
					if($_GET['rebooking']=='on') $W	.= " AND (`restbl`.`STATUS` = '-1' OR `restbl`.`STATUS` = 1) "; 
						else $W .= " AND (`restbl`.`STATUS` = 1) "; 
				}
			$WHO = ''; 
			if(in_array('Public',$_GET['RES_INC'])) $WHO[] = ' (`restbl`.SOURCE_ID=`restbl`.GUEST_ID 	AND `restbl`.SOURCE_ID=`restbl`.OWNER_ID ) '; 
			if(in_array('Admin',$_GET['RES_INC']))  $WHO[] = ' (`restbl`.SOURCE_ID<>`restbl`.GUEST_ID 	AND `restbl`.SOURCE_ID<>`restbl`.OWNER_ID) '; 
			if(in_array('TA',$_GET['RES_INC'])) 	$WHO[] = ' (`restbl`.SOURCE_ID<>`restbl`.GUEST_ID 	AND `restbl`.SOURCE_ID=`restbl`.OWNER_ID ) '; 
			$W .= ' AND ('.implode(' OR ',$WHO).' ) '; 
			
			for($YEAR_NOW = $YEAR_S; $YEAR_NOW<=$YEAR_F; $YEAR_NOW++) {
				foreach($HOTEL as $tbl_name) {
					$sql = "SELECT `restbl`.`ID`, `restbl`.`NUMBER`, `restbl`.`CHECK_IN`, `restbl`.`CHECK_OUT`, `restbl`.`NIGHTS`, `restbl`.`ROOMS`, `restbl`.`ADULTS`, `restbl`.`CHILDREN`, `restbl`.`TOTAL`, `restbl`.`METHOD`, `restbl`.`CANCELLED`, `restbl`.`CREATED`, `restbl`.`STATUS` , '".$tbl_name."', TRANSFER_TYPE, TRANSFER_CAR, TRANSFER_FEE 			FROM `RESERVATIONS_".$tbl_name."_".$YEAR_NOW."` as `restbl` 
					WHERE STATUS <> 0 AND TRANSFER_CAR <> 0 AND TRANSFER_TYPE <> '' AND `restbl`.`CREATED` > '".date('Y-m-d',$DATA_START)."'   ".$W;		
                    //print "c: ".$sql."<hr>";
					$r = mysql_query($sql);// or die(mysql_error().' SQL: '.$sql);
					$datat = array();
					for($datat=array();$row=@mysql_fetch_assoc($r);$datat[]=$row);
					$data = array_merge($data,$datat);				
					//echo $sql;
				}
			}
			
			//Ã‚Ã»Ã¡Ã®Ã°ÃªÃ  Ã¨Ã§ Ã±Ã²Ã Ã°Ã®Ã© Ã¡Ã Ã§Ã»
			//Ã“Ã‘Ã‹ÃŽÃ‚ÃˆÃŸ
			$W_old = '';
			if($_GET['booking_data_by']=='arrival') $W_old .= " AND (`restbl`.`RES_FROM` >= '".date('Y-m-d',$from_compare)."' AND `restbl`.`RES_FROM` <= '".date('Y-m-d',$to_compare)."') ";			
				else $W_old .= " AND (`restbl`.`RES_CREATED` > '".date('Y-m-d 00:00:00',$from_compare)."' AND `restbl`.`RES_CREATED` < '".date('Y-m-d 23:59:59',$to_compare)."') ";
					
			if($_GET['report_type']=='cancellation') $W_old .= ' AND ( `restbl`.`RES_STATUS` = -1 AND `restbl`.`RES_REBOOKED` = \'\' ) ';	
				else $W_old .= " AND ( `restbl`.`RES_STATUS` = 0 OR `restbl`.`RES_STATUS` = -2 ) "; 
						 
			
			$t = ''; $t[] = ' 1=0 ';
			foreach($HOTEL as $e) $t[] = " `XPC`= '".$e."' ";
			$W_old .= ' AND ('.implode(' OR ', $t).' ) ';	
		
		
			$sql = "SELECT  `restbl`.`RES_NUMBER` as`ID`, `restbl`.`RES_NUMBER` as `NUMBER`, `restbl`.`RES_FROM` as `CHECK_IN`, `restbl`.`RES_TO` as `CHECK_OUT`, `restbl`.`RES_DAYS` as `NIGHTS`, `restbl`.`RES_ROOMS` as `ROOMS`, `restbl`.`RES_GUESTS` as `ADULTS`, '0', `restbl`.`RES_TOTAL` as `TOTAL`, 'old', `restbl`.`RES_MODIFIED`, `restbl`.`RES_CREATED` as `CREATED`, `restbl`.`RES_STATUS` , 'old'
			FROM `v_OLD_RES` as `restbl` WHERE 1=1 ".$W_old;	 
            //print "d: ".$sql."<hr>";
			$r = mysql_query($sql);// or die(mysql_error().' SQL: '.$sql);
			$datat = array();
			for($datat=array();$row=@mysql_fetch_assoc($r);$datat[]=$row);
			$data = array_merge($data,$datat);				
			//echo $sql;	
			
			
			$from = mktime(0,0,0,date('m',$from),date('d',$from),(date('Y',$from)-1));
			$sY = date('Y',$from);
			$to = mktime(0,0,0,date('m',$to),date('d',$to),(date('Y',$to)-1));
			$fY = date('Y',$to );
			$diffY = $fY - $sY + 1 ;
			
			//print_r($data_compare);
			
			for($i = 0; $i< $diffY; $i++) {
				//Month start 
				if($i==0) $sD = $from;
					else {
						$t = mktime(0,0,0,(date('m',$from)),1,(date('Y',$from)+$i));
						$sD = $t;
					}
				if($i==($diffY-1)) $fD = $to;
					else {
						$t = mktime(23,59,59,(date('m',$from)),0,(date('Y',$from)+$i+1));
						//echo date('H:i d.m.Y',$t).'<hr>';
						$fD = $t;
					}
				//echo date('H:i d.m.Y',$sD).'--'.date('H:i d.m.Y',$fD).'<br>';
				$daymass = array();
				for($j=$sD;$j<=$fD;$j+=60*60*24) {
					//Ã¯Ã¥Ã°Ã¥Ã¡Ã¨Ã°Ã Ã¥Ã¬ Ã¤Ã­Ã¨ Ã¢ ÃªÃ Ã¦Ã¤Ã®Ã¬ Ã¬Ã¥Ã±Ã¿Ã¶Ã¥
					foreach($data as $el) {
						if($_GET['booking_data_by']=='arrival') 
							$dt_cr = explode('-',$el['CHECK_IN']);
						else 
							$dt_cr = explode('-',$el['CREATED']);
							
						$dt_cr = mktime(0,0,0,$dt_cr[1],$dt_cr[2],$dt_cr[0])+100;
						//echo $dt_cr;
						//echo 'DATE - '.date('H:i d.m.Y',$dt_cr).' === '.date('H:i d.m.Y',$j).' '.date('H:i d.m.Y',($j+60*60*24)).'---<br>';
						if($dt_cr >= $j && $dt_cr <= ($j+60*60*24)) { 
							$daymass[] = $el;
						}
					}
				}
				//$daymass - Ã¢Ã®Ã² Ã²Ã³Ã² Ã³ Ã­Ã Ã± Ã¬Ã Ã±Ã±Ã¨Ã¢ Ã± Ã°Ã¥Ã§Ã¥Ã°Ã¢Ã Ã¶Ã¨Ã¿Ã¬Ã¨ Ã§Ã  
				//echo '<pre>';
				//print_r($daymass); echo '<hr>';
				
				
				?>
				<tr>
					<td><?=(date('m.d.Y',$sD).' - '.date('m.d.Y',$fD));?></td>
					<?php report_line($daymass) ?>
				</tr><?		
			}	
			

			

		}		
		/*
		if($isEXPORT) {
		?>
			<tr><td colspan="8">Room Nights = Total Rooms Booked x Total Nights of Stay</td></tr>
			<tr><td colspan="8">ADR = Average Daily Rate</td></tr>
			<tr><td colspan="8">AP = Average Days Before Arrival</td></tr>
			<tr><td colspan="8">LOS = Average Length of Stay</td></tr>
		<?}
        */
        ?> 
		
		</table><?
	
	/* ***************
	   *** RC LIST ***
	   *************** */	
	}	else {
        // AQUI
		//Ã‘Ã´Ã®Ã°Ã¬Ã¨Ã°Ã³Ã¥Ã¬ Ã±Ã¢Ã®Ã© Ã§Ã Ã¯Ã°Ã®Ã± Ãª Ã�Ã„
		//$isEXPORT = 0;
		$W = '';
		if($_GET['booking_data_by']=='arrival') $W .= " AND (`restbl`.`CHECK_IN` >= '".date('Y-m-d',$from)."' AND `restbl`.`CHECK_IN` <= '".date('Y-m-d',$to)."') ";
			else $W .= " AND (`restbl`.`CREATED` > '".date('Y-m-d 00:00:00',$from)."' AND `restbl`.`CREATED` < '".date('Y-m-d 23:59:59',$to)."') ";
		
		if($_GET['report_type']=='cancellation') $W .= ' AND (`restbl`.`STATUS` = 0) ';
			else {
				if($_GET['rebooking']=='on') $W .= " AND (`restbl`.`STATUS` = '-1' OR `restbl`.`STATUS` = 1) "; 
					else $W .= " AND (`restbl`.`STATUS` = 1) "; 
			}
			
		$WHO = '';
		if(in_array('Public',$_GET['RES_INC'])) $WHO[] = ' (`restbl`.SOURCE_ID=`restbl`.GUEST_ID AND `restbl`.SOURCE_ID=`restbl`.OWNER_ID) '; 
		if(in_array('Admin',$_GET['RES_INC']))  $WHO[] = ' (`restbl`.SOURCE_ID<>`restbl`.GUEST_ID AND `restbl`.SOURCE_ID<>`restbl`.OWNER_ID) '; 
		if(in_array('TA',$_GET['RES_INC'])) $WHO[] = ' (`restbl`.SOURCE_ID<>`restbl`.GUEST_ID AND `restbl`.SOURCE_ID=`restbl`.OWNER_ID) '; 
		$W .= ' AND ('.implode(' OR ',$WHO).' ) '; 
		
		//TA NAME
		$sql = "SELECT `ID`, `AGENCY_NAME` FROM `TRAVEL_AGENTS` ";
        //print "e: ".$sql."<hr>";
		$r = mysql_query($sql);// or die(mysql_error().' SQL: '.$sql);
		for($datat=array();$row=@mysql_fetch_assoc($r);$datat[]=$row);
		foreach($datat as $el) $TAname[$el['ID']] = $el['AGENCY_NAME'];

		//ROOM NAME
		$sql = "SELECT `ID`, `NAME_EN` FROM `ROOMS` ";
        //print "f: ".$sql."<hr>";
		$r = mysql_query($sql);// or die(mysql_error().' SQL: '.$sql);
		for($datat=array();$row=@mysql_fetch_assoc($r);$datat[]=$row);
		foreach($datat as $el) $ROOMname[$el['ID']] = $el['NAME_EN'];

		//TRANSFER CARS NAME
		$sql = "SELECT `ID`, `NAME_EN` FROM TRANSFER_CARS ";
        //print "f: ".$sql."<hr>";
		$r = mysql_query($sql);// or die(mysql_error().' SQL: '.$sql);
		for($datat=array();$row=@mysql_fetch_assoc($r);$datat[]=$row);
		foreach($datat as $el) $CARname[$el['ID']] = $el['NAME_EN'];

		
		
		$data = array();
		$data_rooms = array();
		$YEAR_S = date('Y',$from);
		$YEAR_F = date('Y',$to);

        if ($_GET['booking_data_by']=='arrival') $YEAR_S -= 1;

		for($YEAR_NOW = $YEAR_S; $YEAR_NOW<=$YEAR_F; $YEAR_NOW++) {
			foreach($HOTEL as $tbl_name) {
				$sql = "SELECT `restbl`.`ID`, `restbl`.`NUMBER`, `restbl`.`CHECK_IN`, `restbl`.`CHECK_OUT`, `restbl`.`NIGHTS`, `restbl`.`ROOMS`, `restbl`.`ADULTS`, `restbl`.`CHILDREN`, `restbl`.`TOTAL`, `restbl`.`METHOD`, `restbl`.`CANCELLED`, `restbl`.`CREATED`, `restbl`.`STATUS` , `restbl`.`SOURCE_ID`, `restbl`.`GUEST_ID`, `restbl`.`OWNER_ID`, '".$tbl_name."', `restbl`.`GEO_COUNTRY_CODE`, `restbl`.`CLASS_NAMES`, `restbl`.`SPECIAL_NAMES`, 	
				`GUESTS`.`FIRSTNAME`, `GUESTS`.`LASTNAME`, `GUESTS`.`EMAIL`, `GUESTS`.`CITY`, `GUESTS`.`STATE`, `GUESTS`.`COUNTRY`, `GUESTS`.`TITLE`, TRANSFER_TYPE, TRANSFER_CAR, TRANSFER_FEE
						
						FROM `RESERVATIONS_".$tbl_name."_".$YEAR_NOW."` as `restbl`, `GUESTS`						
						
						WHERE STATUS <> 0 AND TRANSFER_CAR <> 0 AND TRANSFER_TYPE <> '' AND `GUESTS`.`ID` = `restbl`.`GUEST_ID` ".$W;
                print_sql($YEAR_NOW."<br>".$sql);
                //print "g: ".$sql."<hr>";
				$r = mysql_query($sql);// or die(mysql_error().' SQL: '.$sql);
				$datat = array();
				for($datat=array();$row=@mysql_fetch_assoc($r);$datat[]=$row);
				$data = array_merge($data,$datat);				
				//echo $sql;
				
				//ROOMS DATA 
				$sql = "SELECT `restbl`.`ID`, `restbl`.`NUMBER`, `opts`.`ROOM_ID`
						
						FROM `RESERVATIONS_".$tbl_name."_".$YEAR_NOW."` as `restbl`,  `RESERVATIONS_".$tbl_name."_".$YEAR_NOW."_ROOM_OPTS` as `opts`					
						
						WHERE `opts`.`RES_ID` = `restbl`.`ID` ".$W;
                print_sql($YEAR_NOW."<br>".$sql);
                //print "h: ".$sql."<hr>";
				$r = mysql_query($sql);// or die(mysql_error().' SQL: '.$sql);
				$datat = array();
				for($datat=array();$row=@mysql_fetch_assoc($r);$datat[]=$row);
				$data_rooms = array_merge($data_rooms,$datat);				
				
				//die($sql);
			}
		}
		$t = $data_rooms;
		$data_rooms = array();
		foreach($t as $el) {
			$data_rooms[$el['ID']][] = $el['ROOM_ID'];
		}
	
		ob_start();
		?>
		<table>
			<tr>
				<th>ID</th>
				<th>SOURCE</th>
				<th>DATE OF BOOKING</th>
				<th>HOTEL</th>
				<th>CAR NAME</th>
				<th>TRANSFER TYPE</th>
				<th>FEE</th>
				<th>No.PEOPLE</th>
				<th>FIRST NAME</th>
				<th>LAST NAME</th>
				<th>EMAIL</th>
				<th>COUNTRY</th>
			</tr>
			<?foreach($data as $el) {?>
				<tr>
					<td># <?=$el['NUMBER'];?></td>
					<td><?
						if($el['SOURCE_ID'] == $el['GUEST_ID'] AND $el['SOURCE_ID'] == $el['OWNER_ID']) {?>Public<?}
						if($el['SOURCE_ID'] <> $el['GUEST_ID'] AND $el['SOURCE_ID'] <> $el['OWNER_ID']) {?>Call Center<?}
						if($el['SOURCE_ID'] <> $el['GUEST_ID'] AND $el['SOURCE_ID'] == $el['OWNER_ID']) {?>Travel Agency<?}
						?>
          </td>
					<td><?=$el['CREATED'];?></td>
					<td><?
						if($el['XRC']) $tmp = $el['XRC'];
						if($el['XPC']) $tmp = $el['XPC'];
						if($el['XPM']) $tmp = $el['XPM'];
						if($el['LAM']) $tmp = $el['LAM'];
						if($tmp=='XRC') {?>Excellence Riviera Cancun<?}
						if($tmp=='XPC') {?>Excellence Punta-Cana<?}
						if($tmp=='XPM') {?>Excellence Playa Mujeres<?}
						if($tmp=='LAM') {?>La Amada Hotel<?}						
						?>
          </td>
					<td><?=$CARname[$el['TRANSFER_CAR']]?></td>
					<td><?=$el['TRANSFER_TYPE']=="ONEWAY"?"One Way":"Round Trip"?></td>
					<td><?=(int)$el['TRANSFER_FEE']?></td>
					<td><?=($el['ADULTS']+$el['CHILDREN']);?></td>
					<td><?=$el['FIRSTNAME'];?></td>
					<td><?=$el['LASTNAME'];?></td>
					<td><?=$el['EMAIL'];?></td>
					<td><?=$el['COUNTRY'];?></td>
				</tr>
			<?}?>			
		</table>
		<?
	}
	//$isEXPORT = 0;
    $REPORT = ob_get_clean();
	if (!$isEXPORT) print $REPORT;


//

}


//echo '<hr><hr><hr><pre>';
//print_R($data);
//print_r($_GET); 

?>
<style>
#report_tbl {
	font-size: 12px;
	width : 100%;
}
#report_tbl td {
	text-align: right;
	padding: 2px 5px;
	border-top: 1px solid #333;
}
#report_tbl th {
	text-align: right;
	padding: 2px 5px;
	font-size 14px;
}
#report_tbl tr:hover {
	background: #e6e6e6;
}
</style>
 
<?
$OUT = ob_get_clean();

print ($isEXPORT) ? $REPORT : $OUT;

function numberFormat($number) {
	return number_format($number,2);
	$smallN = $number%1000;	
	$bigN = ($number-$smallN)/1000;
	if($number>1000) return $bigN.','.sprintf("%06.2f",$smallN);
		else return sprintf("%01.2f",$smallN);
	
}

function print_sql($sql) {
    //print "<div style='padding:10px;background:#FFFFFF;color:#000000;border:solid 2px #000000'>".$sql."</div>";
}

function report_line($daymass) {
    if(count($daymass)==0) {
        ?><td>-</td><td>-</td><td>-</td><td>-</td><?
    } else {?>
        <td><?
            $all_bookings = 0;
            foreach($daymass as $el) $all_bookings += 1;
            echo $all_bookings;
            ?>
        </td>
        <td><?
            $oneway = 0;
            foreach($daymass as $el) $oneway += $el['TRANSFER_TYPE']=="ONEWAY" ? 1 : 0;
            echo $oneway;
            ?>
        </td>
        <td><?
            $roundt = 0;
            foreach($daymass as $el) $roundt += $el['TRANSFER_TYPE']=="ROUNDT" ? 1 : 0;
            echo $roundt;
            ?>
        </td>
        <td><?
            $booking = 0;
            foreach($daymass as $el) $booking += $el['TRANSFER_FEE'];
            echo numberFormat($booking);
            ?>
        </td>
    <?}
}

function report_total($data) {
    ?>
    <td>TOTAL</td>
    <td><?
        $all_bookings = 0;
        foreach($data as $el) $all_bookings += 1;
        echo $all_bookings;
        ?>
    </td>
    <td><?
        $oneway = 0;
        foreach($data as $el) $oneway += $el['TRANSFER_TYPE']=="ONEWAY" ? 1 : 0;
        echo $oneway;
        ?>
    </td>
    <td><?
        $roundt = 0;
        foreach($data as $el) $roundt += $el['TRANSFER_TYPE']=="ROUNDT" ? 1 : 0;
        echo $roundt;
        ?>
    </td>
    <td><?
        $booking = 0;
        foreach($data as $el) $booking += $el['TRANSFER_FEE'];
        echo numberFormat($booking);
        ?>
    </td>
    <?php
}