var data;
var roomleft, sidebar_height, cord, monitor; 

$(document).ready(function(){
	//TMP
	
	
	$(window).scroll( function () {	
		/*
		$('#sidebar').css('position','static');
		$('body').append('<Div class="control"></div>');
		$('.control').css('position','fixed');
		$('.control').css('top','0px');
		$('.control').css('left','0px');
		$('.control').html($('#sidebar').height() +' '+($('#rooms').height()-1+230)); 
		*/
		if($('#sidebar').height() < ($('#rooms').height()-1+230)) {
			//SMALL sidebar 
				$('#sidebar').css('position','static');			
				$('#sidebar').css('left','0px');
				$('#sidebar').css('top','0px');
			cord = $('#sidebar').offset();
			sidebar_height = $('#sidebar').height();
			monitor = $(window).height();
			scroll_top = $(window).scrollTop(); 
			if ( (scroll_top > cord.top) && (monitor > sidebar_height) ) {
				$('#sidebar').css('position','fixed');			
				$('#sidebar').css('left',cord.left+'px');
				$('#sidebar').css('top','0px');
			} 
			if ( (scroll_top < cord.top) && (monitor > sidebar_height) ) {
				$('#sidebar').css('position','static');
				$('#sidebar').css('margin-left','0px');		
			}
			//BIG SIDEBAR
			z_point = cord.top + (sidebar_height - monitor);
			if ( (scroll_top > z_point) && (monitor < sidebar_height) ) {
				$('#sidebar').css('position','fixed');	
				$('#sidebar').css('left',cord.left+'px');
				var px = sidebar_height - monitor + 20;
				$('#sidebar').css('top','-'+px+'px');		 	
			} 
			if ( (scroll_top < z_point) && (monitor < sidebar_height) ){
				$('#sidebar').css('position','static');
				$('#sidebar').css('margin-left','0px');		
			}		
		}
	});


	$('#rooms').html('Loading...');
	 
	$.urlParam = function(name) {
		var results = new RegExp('[\\?&]' + name + '=([^&#]*)').exec(window.location.href);
		return results[1] || 0; 
	}
	
	var room_1_ppl, room_2_ppl, room_3_ppl;
	if($.urlParam('RES_ROOM_1_ADULTS_QTY')) { room_1_ppl = $.urlParam('RES_ROOM_1_ADULTS_QTY'); } else {room_1_ppl = '';}
	if($.urlParam('RES_ROOM_2_ADULTS_QTY')) { room_2_ppl = $.urlParam('RES_ROOM_2_ADULTS_QTY'); } else {room_2_ppl = '';}
	if($.urlParam('RES_ROOM_3_ADULTS_QTY')) { room_3_ppl = $.urlParam('RES_ROOM_3_ADULTS_QTY'); } else {room_3_ppl = '';}
	
	//ROOM DATA
	$('body').append('<input type="hidden" id="SET_room_all" value="'+$.urlParam('RES_ROOMS_QTY')+'">');
	$('body').append('<input type="hidden" id="SET_room_now" value="1">');
	$('body').append('<input type="hidden" id="SET_room_price_1" value="0">');
	$('body').append('<input type="hidden" id="SET_room_price_2" value="0">');
	$('body').append('<input type="hidden" id="SET_room_price_3" value="0">');
	$('body').append('<input type="hidden" id="SET_room_price_full_1" value="0">');
	$('body').append('<input type="hidden" id="SET_room_price_full_2" value="0">');
	$('body').append('<input type="hidden" id="SET_room_price_full_3" value="0">');
	
	//JSON
	var maindata = $.getJSON('/ibe/index.php', {
		PAGE_CODE:        'ws.availability',
		ACTION:           'SUBMIT',
		RES_LANGUAGE :    $.urlParam('RES_LANGUAGE'),
		RES_IN_THE_FUTURE : '0',
		RES_DATE 		  : '',
		RES_PROP_ID :     $.urlParam('RES_PROP_ID'),
		'RES_USERTYPE[]': '1',  
		RES_COUNTRY_CODE: $.urlParam('RES_COUNTRY_CODE'),
		RES_STATE_CODE :  $.urlParam('RES_STATE_CODE'),
		RES_SPECIAL_CODE :$.urlParam('RES_SPECIAL_CODE'),
		RES_CHECK_IN :    $.urlParam('RES_CHECK_IN'),
		RES_CHECK_OUT :   '',
		RES_NIGHTS :      $.urlParam('RES_NIGHTS'),
		RES_ROOMS_QTY :   $.urlParam('RES_ROOMS_QTY'),
		RES_ROOM_1_ADULTS_QTY : room_1_ppl,
		RES_ROOM_2_ADULTS_QTY : room_2_ppl,
		RES_ROOM_3_ADULTS_QTY : room_3_ppl,
		GET_GEO : '1'
		}, function(datat) {
			data = datat;	
			
			//HOTEL INFO
			$('#hotel-info p').html(data.RES_ITEMS.PROPERTY.DESCR_EN);
			var img = '';
			jQuery.each(data.RES_ITEMS.PROPERTY.IMAGES, function (k2, el2) {
				img = el2;
			});
			$('#hotel-info img').attr('src','/'+img);
			
			if(data.RES_PROP_ID == '1') {
				$('#hotel-info').addClass('rc');
				$('#hotel-info span').html('Riviera Cancun');
			}
			if(data.RES_PROP_ID == '2') {				
				$('#hotel-info').addClass('pm');
				$('#hotel-info span').html('Playa Mujeres');
			}
			if(data.RES_PROP_ID == '3') {
				$('#hotel-info').addClass('pc');
				$('#hotel-info span').html('Punta Cana');
			}		
			
			//MAIN SCREEN
			screen();		
	});

	maindata.error(function() { alert("Error in JS-AJAX"); })

	
	//room info 1lvl
	$('.detail a').live('click', function () {		
		var room_id = $(this).parent().parent().parent().attr('id').substr(4);
		var MAIN_DATA = data['RES_ROOM_'+$('#SET_room_now').val()+'_ROOMS'];
		var COUNT_PER_ROOM = data['RES_ROOM_'+$('#SET_room_now').val()+'_GUESTS_QTY'];
		
		$('#room'+room_id).addClass('opened');
		$('#room'+room_id+' .inner').css('display','block');
		$('#room'+room_id+' .text').html (data.RES_ITEMS[room_id].DESCR_EN);
		$('#room'+room_id+' .info').html (data.RES_ITEMS[room_id].INCLU_EN);
		//IMGs
		var arr = [];
		jQuery.each(data.RES_ITEMS[room_id].IMAGES, function (k,el) {
			arr[arr.length] = el;
		});
		$('#room'+room_id+' .mainimg img').attr('src','/'+arr[0]);
		$('#room'+room_id+' .tmbs').html('');
		for(i=1;i<arr.length;i++) {
			$('#room'+room_id+' .tmbs').append('<img src="/'+arr[i]+'">');
		}
		printCalendar2(room_id, $('#SET_room_now').val());
		
		//ROOM LEFT & PER DAY
		roomleft = 99;
		jQuery.each(MAIN_DATA[room_id].NIGTHS, function (k2, el2) {
			if(el2.INVENTORY) {  
				if ( el2.INVENTORY.LEFT < 10 ) { 
					$('#room'+room_id+' .roomleft_inner').html('<div>'+el2.INVENTORY.LEFT +' rooms left!</div>');
				}
			}
			if(el2.RATE) {
				$('.perday'+room_id).append('<span><s>$'+number_format(el2.RATE.GROSS*data.RES_ROOMS_ADULTS_QTY)+'</s> <b>$'+number_format(el2.RATE.FINAL*COUNT_PER_ROOM)+'</b></span> ');
			} else {
				$('.perday'+room_id).append('<span><b>XXX</b></span> ');
			}
		});
		//CHECK RADIO
		if($('#room'+room_id+' .hide .radio').hasClass('radio_on')) {
			$('#room'+room_id+' .right .radio').addClass('radio_on');
		}
		
		//CLOSE
		$('#room'+room_id+' .detail').addClass('hide');
		$('#room'+room_id+' .detail a').html('Hide Details');//View Details
		
		//RE-SELECT ROOM
		setroom($('#room_now').val());
		
		return false;
	});
	//room close
	$('#rooms .hide a').live('click', function () {
		var room_id = $(this).parent().parent().parent().attr('id').substr(4);
		$('#room'+room_id+' .inner').css('display','none');
		$('#room'+room_id).removeClass('opened');
		//button
		$('#room'+room_id+' .detail').removeClass('hide');
		$('#room'+room_id+' .detail a').html('View Details');//View Details
		return false;
	});
	
	//select room
	$('.selector').live('click', function () { 
		var room_id = $(this).parent().attr('id').substr(4);		
		$('.radio').removeClass('radio_on');
		$(this).children('.radio').addClass('radio_on');
		setroom(room_id); 
	});
	//select open room
	$('.select .radio').live('click', function () { 
		var room_id = $(this).attr('rel');
		setroom(room_id); 
	});
	//imitation radio
	$('.radio').live('click',function() {
		$('.radio').removeClass('radio_on');
		$(this).addClass('radio_on');
	});
	
	//CONTINUE
	$('#continue').live('click', function () {
		$('#SET_room_now').val(($('#SET_room_now').val()-1+2));
		screen();
	});
	
	//GO TO 
	$('#go_to1').live('click',function() {
		$('#SET_room_now').val('1');
		screen();
	});
	$('#go_to2').live('click',function() {
		$('#SET_room_now').val('2');
		screen();
	}); 
	$('#go_to3').live('click',function() {
		$('#SET_room_now').val('3');
		screen();
	});
	
	//Room gallery 
	$('.tmbs img').live ('click', function () {
		var room_id = $(this).parent().parent().parent().parent().attr('id').substr(4);
		var src = $('#room'+room_id+' .mainimg img').attr('src');
		$('#room'+room_id+' .mainimg img').attr('src',$(this).attr('src'));
		$(this).attr('src',src);
	});
});

//*** SCREEN ***
function screen() {
	var tmp_k, tmp_first_room=0, tmp_class;
	var MAIN_DATA = data['RES_ROOM_'+$('#SET_room_now').val()+'_ROOMS'];
	
	$('#rooms').html('');
	jQuery.each(MAIN_DATA, function (k, el) {
		//AVALABILITY
		if(data.RES_NIGHTS != el.AVAILABLE_NIGHTS) { tmp_class = 'not-avalable'; } 
			else { 
				tmp_class='';  
				if(tmp_first_room==0) { tmp_first_room = k };
			}
			
		//EXCELLENCE CLUB DETECT
		var tname = el.NAME;
		if(data.RES_ITEMS[k]['IS_VIP']=='1' ) {
			tname = '<span>Excellence Club</span> '+tname.substr(15); 
			tmp_class += ' ec ';
		}
		
		//SPECIAL
		var special_name, special_id;
		jQuery.each(el.SPECIAL_NAMES, function (k2, el2) {
			special_name = el2;
			special_id = k2;
		});
		//PROMOTIONAL
		var promo_name, promo_id;
		jQuery.each(el.CLASS_NAMES, function (k2, el2) {
			promo_name = el2;
			promo_id = k2;
		});
		
		$('#rooms').append(''+
			'<div class="line border '+tmp_class+'" id="room'+k+'">'+
				'<div class="name">'+
					'<div class="nm">'+tname+'</div>'+
				'</div>'+
				'<div class="section2">'+
					'<div class="roomleft"></div>'+	 
				'</div>'+
				'<div class="badprice">$...</div>'+	
				'<div class="section4">'+
					'<div class="na">Not Available</div>'+
					'<div class="goodprice">$...</span></div>'+
					'<div class="pernight">Per Night<span><br/>All Inclusive</span></div>'+
				'</div>'+
				'<div class="selector"><div class="radio" value="'+k+'"></div></div>'+
				'<div style="clear:both"></div>'+
				
				'<div class="inner">'+
					'<div class="gallery">'+
						'<div class="mainimg"><img src="img/tmp_1.jpg"></div>'+
						'<div class="tmbs">'+
						'</div>'+
					'</div>'+
					'<div class="text">'+
						'<p>All our spacious Junior Suites 668 sq. ft. in size and offers private furnished balcony and magnificent view of the Spa and Pool. '+
						'<p>KING OR 2 DOUBLE BEDS'+
					'</div>'+
					'<div class="info">'+
						'<h3>Room Features</h3>'+
						'<p>• Full marble bathrooms, a jetted whirlpool bathtub, separate shower, separate water closet and double vanities equipped with hair dryer, scales, vanity mirror, VIP amenities and phone • Bathrobes & slippers • 27” satellite television • Service Box for Room Service • Air conditioning with in-room climate control • CD/DVD player • Direct dial telephone • Free high-speed internet data port • Radio/alarm clock with I-pod connectivity • Electronic in-room safe (lap-top size) • Coffee/tea maker • Stocked minibar with beer, juices, soft drinks, water and snacks • Iron & ironing board • Pillow menu • Turndown service and Excellence stationary</p>'+
					'</div>'+
					'<div class="calendar-inner">'+
						'<div id="days"><span>SUN</span> <span>MON</span> <span>TUE</span> <span>WED</span> <span>THU</span> <span>FRI</span> <span>SAT</span></div>'+
						'<div class="cline">'+
							'<div class="lcal">'+
								'<dt class="date">Jun 30-Jul 2</dt>	'+
							'</div>'+
							'<div class="cal_content">'+
								'<div><s>$428</s>$278</div>'+
								'<div><s>$428</s>$278</div>'+
								'<div><s>$428</s>$278</div>'+
								'<div><em></em>x</div>'+
							'</div>'+
						'</div>'+
					'</div>'+
					'<p class="pops"><a href="#" class="popover_promo" rel="'+promo_id+'">Promotional Rate Rules</a><br/>'+
						'<a href="#" class="popover_special" rel="'+special_id+'">'+special_name+'</a>'+
					'</p>'+
				'</div>'+
				'<div class="detail"><div><a href="#">View Details</a></div></div>'+
			'</div>');
		
		
		//ROOM LEFT
		roomleft = 99;
		jQuery.each(el.NIGTHS, function (k2, el2) { 
			if(el2.INVENTORY) {  
				if ( el2.INVENTORY.LEFT < 10 ) { 
					$('#room'+k+' .roomleft').html('<div>'+el2.INVENTORY.LEFT +' rooms left!</div>');
				}
			}
		});
		
		tmp_k = k; //for last border
	});
	
	$('#room'+tmp_k).css('border-color','#ae893b');
	
	sidebar();
	
	var room_now = $('#SET_room_now').val(); 
	if($('#room_id_'+room_now).val()>0) {
		tmp_first_room = $('#room_id_'+room_now).val();	
	}
	
	
	setroom (tmp_first_room);
	//EXCELLENCE CLUB LOGO
	$('#rooms .ec:first').before('<div id="ec_logo"></div>');
}


// *** SET ROOM ***
function setroom (room_id) {
	var MAIN_DATA = data['RES_ROOM_'+$('#SET_room_now').val()+'_ROOMS'];
	var room_all = $('#SET_room_all').val();
	var room_now = $('#SET_room_now').val(); 
	 
	//SET ROOM ID 
	$('#room_id_'+room_now).val(room_id);
 
	$('#room_now').val(room_id); 
	//INPUT
	$('.radio').removeClass('radio_on');
	$('#room'+room_id+' .radio').addClass('radio_on');
	
	//TOTAL 
	$('#room_'+room_now+'_data').css('display','block');
	$('#room_'+room_now+'_data .price1').html(number_format(MAIN_DATA[room_id].TOTAL.FINAL));	
	$('#room_'+room_now+'_data .rname').html(MAIN_DATA[room_id].NAME); 
	$('#room_'+room_now+'_data .rate-detail').attr('room_id',room_id); 
	
	$('#SET_room_price_'+room_now).val(MAIN_DATA[room_id].TOTAL.FINAL);
	$('#SET_room_price_full_'+room_now).val(MAIN_DATA[room_id].TOTAL.GROSS);
	
	//PRICE
	var avg_sample = MAIN_DATA[room_id].TOTAL.AVG_FINAL_PN;
	
	
	//$('#rooms .line').not('.not-avalable').each ( function () {
	$('#rooms .line').each ( function () {
		var id = $(this).attr('id').substr(4);
		var diff = 0;
		diff = Math.floor(MAIN_DATA[id].TOTAL.AVG_FINAL_PN - avg_sample);
		if (diff>0) {
			$('#room'+id+' .goodprice').html('+ $'+number_format(diff));
			//$('#room'+id+' .price').html('+ $'+number_format(diff));
			diff += Math.floor(MAIN_DATA[id].TOTAL.AVG_GROSS_PN - MAIN_DATA[id].TOTAL.AVG_FINAL_PN);
			$('#room'+id+' .badprice').html('');
		} else { 
			$('#room'+id+' .goodprice').html('- $'+number_format(diff*(-1)));
			//$('#room'+id+' .price').html('- $'+number_format(diff*(-1)));			
			$('#room'+id+' .badprice').html('');			
		}
	});
	
	//if room open and selected
	 
	if(MAIN_DATA[room_id].TOTAL.AVG_GROSS_PN != MAIN_DATA[room_id].TOTAL.AVG_FINAL_PN) {
		$('#room'+room_id+' .badprice').html('$'+number_format(MAIN_DATA[room_id].TOTAL.AVG_GROSS_PN));
	}
	$('#room'+room_id+' .goodprice').html('$'+number_format(MAIN_DATA[room_id].TOTAL.AVG_FINAL_PN));
		
	total();
	
	//ACTIVE CLASS
	$('#rooms .line').removeClass('active');
	$('#room'+room_id).addClass('active');
} 

//TOTAL
function total() {
	if( (($('#SET_room_all').val()-1+1)==3) &&
		(($('#SET_room_price_1').val()-1)>0) &&
		(($('#SET_room_price_2').val()-1)>0) &&
		(($('#SET_room_price_3').val()-1)>0)) {
		var summary = ($('#SET_room_price_1').val()-1) + ($('#SET_room_price_2').val()-1) + ($('#SET_room_price_3').val()-1)+3;
		var summary_full = ($('#SET_room_price_full_1').val()-1) + ($('#SET_room_price_full_2').val()-1) + ($('#SET_room_price_full_3').val()-1)+3;
		$('#dt_total').html('$'+number_format(summary));
		$('#dt_total_full').html('$'+number_format(summary_full));
	}
	if( (($('#SET_room_all').val()-1+1)==2) &&
		(($('#SET_room_price_1').val()-1)>0) &&
		(($('#SET_room_price_2').val()-1)>0)) {
		var summary = ($('#SET_room_price_1').val()-1) + ($('#SET_room_price_2').val()-1) + 2;
		var summary_full = ($('#SET_room_price_full_1').val()-1) + ($('#SET_room_price_full_2').val()-1) + 2;
		$('#dt_total').html('$'+number_format(summary));
		$('#dt_total_full').html('$'+number_format(summary_full));
	} 

	if (($('#SET_room_all').val()-1+1)==1) {	
		var summary = ($('#SET_room_price_1').val()-1) +1;
		var summary_full = ($('#SET_room_price_full_1').val()-1) + 1;
		$('#dt_total').html('$'+number_format(summary));
		$('#dt_total_full').html('$'+number_format(summary_full));
	}
}


function sidebar () {
	if(data.RES_PROP_ID == '1') {
		$('.hotel-name').addClass('rc');
		$('.hotel-name span').html('Riviera Cancun');
	}
	if(data.RES_PROP_ID == '2') {
		
		$('.hotel-name').addClass('pm');
		$('.hotel-name span').html('Playa Mujeres');
	}
	if(data.RES_PROP_ID == '3') {
		$('.hotel-name').addClass('pc');
		$('.hotel-name span').html('Punta Cana');
	}

	$('#dt_date_from').html(dateformat2(data.RES_CHECK_IN));
	$('#dt_date_to').html(dateformat2(data.RES_CHECK_IN,'out'));

	$('#dt_nights').html(data.RES_NIGHTS);
	$('#dt_rooms').html(data.RES_ROOMS_QTY);
	$('#dt_guests').html(data.RES_ROOMS_ADULTS_QTY);
	
	if(($('#SET_room_all').val()!='1') && ($('#SET_room_all').val()!=$('#SET_room_now').val())) {
		$('.but_book-now').css('display','none'); 
		$('#continue').css('display','block');
	} else {
		$('#continue').css('display','none');
		$('.but_book-now').css('display','block'); 
	}
	
	if($('#SET_room_all').val()=='1') {
		$('#room_selecter').css('display','none');
		
		$('#room_1_data h4').css('display','none'); 
		$('#room_2_data').css('display','none'); 
		$('#room_3_data').css('display','none'); 		
	}
	if($('#SET_room_all').val()=='2') {
		$('#room_selecter').css('display','block');
		$('#room_2_data').css('display','block'); 		
		var g;
		if(data.RES_ROOM_1_ADULTS_QTY != '1') { g = 'guests'; } else { g = 'guest'; }
		$('#room_1_data h4 span').html(data.RES_ROOM_1_ADULTS_QTY + ' ' + g);  
		if(data.RES_ROOM_2_ADULTS_QTY != '1') { g = 'guests'; } else { g = 'guest'; }
		$('#room_2_data h4 span').html(data.RES_ROOM_2_ADULTS_QTY + ' ' + g); 
	}
	if($('#SET_room_all').val()=='3') {
		$('#room_selecter').css('display','block');
		$('#room_2_data').css('display','block'); 
		$('#room_3_data').css('display','block'); 		
		var g;
		if(data.RES_ROOM_1_ADULTS_QTY != '1') { g = 'guests'; } else { g = 'guest'; }
		$('#room_1_data h4 span').html(data.RES_ROOM_1_ADULTS_QTY + ' ' + g);  
		if(data.RES_ROOM_2_ADULTS_QTY != '1') { g = 'guests'; } else { g = 'guest'; }
		$('#room_2_data h4 span').html(data.RES_ROOM_2_ADULTS_QTY + ' ' + g); 
		if(data.RES_ROOM_3_ADULTS_QTY != '1') { g = 'guests'; } else { g = 'guest'; }
		$('#room_3_data h4 span').html(data.RES_ROOM_3_ADULTS_QTY + ' ' + g); 
	}
	
	
	if($('#SET_room_now').val()=='1') {
		if(data.RES_ROOM_1_ADULTS_QTY != '1') { g = 'guests'; } else { g = 'guest'; }
		$('#room_selecter').html('Select Room 1 ('+data.RES_ROOM_1_ADULTS_QTY + ' ' + g + ')');
		
		$('#room_1_data .modifyb').css('display','none');
		$('#room_2_data .modifyb').css('display','block');
		$('#room_3_data .modifyb').css('display','block');	
		
		$('#room_1_data .roomname').css('display','block');
		$('#room_1_data .pending').css('display','none');
	}
	if($('#SET_room_now').val()=='2') {
		if(data.RES_ROOM_2_ADULTS_QTY != '1') { g = 'guests'; } else { g = 'guest'; }
		$('#room_selecter').html('Select Room 2 ('+data.RES_ROOM_2_ADULTS_QTY + ' ' + g + ')');
		
		$('#room_1_data .modifyb').css('display','block');
		$('#room_2_data .modifyb').css('display','none');	
		$('#room_3_data .modifyb').css('display','block');	
		
		$('#room_2_data .roomname').css('display','block');
		$('#room_2_data .pending').css('display','none');
	}
	if($('#SET_room_now').val()=='3') {
		if(data.RES_ROOM_3_ADULTS_QTY != '1') { g = 'guests'; } else { g = 'guest'; }
		$('#room_selecter').html('Select Room 3 ('+data.RES_ROOM_3_ADULTS_QTY + ' ' + g + ')');
		
		$('#room_1_data .modifyb').css('display','block');
		$('#room_2_data .modifyb').css('display','block');	
		$('#room_3_data .modifyb').css('display','none');
		
		$('#room_3_data .roomname').css('display','block');
		$('#room_3_data .pending').css('display','none');
	}
	$('.cancel').html('<p>Cancellation  and <br> Modification  Policy</p><p>'+data.RES_ITEMS.CANCELLATION_POLICY);
}

function dateformat (date,out) {
	var month = new  Array ('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
	date_arr = date.split("-");
	if(out=='out') {
		var dt = new Date(date_arr[0]+'/'+date_arr[1]+'/'+(date_arr[2]-1+2+(data.RES_NIGHTS-1))+' 00:00:00');
	} else {
		var dt = new Date(date_arr[0]+'/'+date_arr[1]+'/'+date_arr[2]+' 00:00:00');
	}
	return month[dt.getMonth()]+' '+dt.getDate();
}
function dateformat2 (date,out) {
	var month = new  Array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
	date_arr = date.split("-");
	if(out=='out') {
		var dt = new Date(date_arr[0]+'/'+date_arr[1]+'/'+(date_arr[2]-1+2+(data.RES_NIGHTS-1))+' 00:00:00');
	} else {
		var dt = new Date(date_arr[0]+'/'+date_arr[1]+'/'+date_arr[2]+' 00:00:00');
	}
	return month[dt.getMonth()]+' '+dt.getDate()+', '+dt.getFullYear();
}
function strpos( haystack, needle, offset){	
	var i = haystack.indexOf( needle, offset );
	return i >= 0 ? i : false;
}




// http://art-blog.ru/blog/topic/49
function number_format(_number, _cfg){
	function obj_merge(obj_first, obj_second){
		var obj_return = {};
        for (key in obj_first){
            if (typeof obj_second[key] !== 'undefined') obj_return[key] = obj_second[key];
            else obj_return[key] = obj_first[key];
        }
		return obj_return;
    }
    
	function thousands_sep(_num, _sep){
		  if (_num.length <= 3) return _num;
		  var _count = _num.length;
		  var _num_parser = '';
		  var _count_digits = 0;
		  for (var _p = (_count - 1); _p >= 0; _p--){
			var _num_digit = _num.substr(_p, 1);
			if (_count_digits % 3 == 0 && _count_digits != 0 && !isNaN(parseFloat(_num_digit))) _num_parser = _sep + _num_parser;
			_num_parser = _num_digit + _num_parser;
			_count_digits++;
			}
		  return _num_parser;
	}
	if (typeof _number !== 'number'){
	  _number = parseFloat(_number);
	  if (isNaN(_number)) return false;
	}
	var _cfg_default = {before: '', after: '', decimals: 0, dec_point: '', thousands_sep: ','};
	if (_cfg && typeof _cfg === 'object'){
	  _cfg = obj_merge(_cfg_default, _cfg);
	}
	else _cfg = _cfg_default;
	_number = _number.toFixed(_cfg.decimals);
	if(_number.indexOf('.') != -1){
	  var _number_arr = _number.split('.');
	  var _number = thousands_sep(_number_arr[0], _cfg.thousands_sep) + _cfg.dec_point + _number_arr[1];
	}
	else var _number = thousands_sep(_number, _cfg.thousands_sep);
	return _cfg.before + _number + _cfg.after;
}


/*******************************
**********Form******************
*******************************/

$(document).ready(function() {

	$('#go_button').live('click', function() {
		if(($('#prop_id').val()!='1') && ($('#prop_id').val()!='2') && ($('#prop_id').val()!='3')) {
			alert('Please Select Destination!');
		} else {
			$('#res_form').submit(); 
		} 
		return false;
	}); 
	
	$('#select_room').live('change',function() {
		if($(this).val()=='1') {
			$('#res_room_2').css('display','none');
			$('#res_room_3').css('display','none');
		}
		if($(this).val()=='2') {
			$('#res_room_2').css('display','block');
			$('#res_room_3').css('display','none');
		}
		if($(this).val()=='3') {
			$('#res_room_2').css('display','block');
			$('#res_room_3').css('display','block');
		}
	});
	
	$('#goto_form').live('click', function() {
		$('#res_data').css('display','none');
		$('#res_form').css('display','block');
		//ROOMS COUNT
		if($('#select_room').val()=='1') {
			$('#res_room_2').css('display','none');
			$('#res_room_3').css('display','none');
		}
		if($('#select_room').val()=='2') {
			$('#res_room_2').css('display','block');
			$('#res_room_3').css('display','none');
		}
		if($('#select_room').val()=='3') {
			$('#res_room_2').css('display','block');
			$('#res_room_3').css('display','block');
		}
		//START PARAMS		
		var date_from = data.RES_CHECK_IN.replace(/-/g, '/');	
		var start = new Date(date_from+' 00:00:00');
		var finish = new Date( start.getFullYear()+'/'+(start.getMonth()+1)+'/'+(start.getDate()+data.RES_NIGHTS)+' 00:00:00');
		
		$('#date_from').val(start.getFullYear()+'/'+(start.getMonth()+1)+'/'+start.getDate());
		$('#date_to').val(finish.getFullYear()+'/'+(finish.getMonth()+1)+'/'+finish.getDate());
	 
		$('#prop_id').val(data.RES_PROP_ID);		
		$('#select_room').val(data.RES_ROOMS_QTY);
		$('#select_room').change();
		$('#RES_ROOM_1_ADULTS_QTY').val(data.RES_ROOM_1_ADULTS_QTY);
		$('#RES_ROOM_2_ADULTS_QTY').val(data.RES_ROOM_2_ADULTS_QTY);
		$('#RES_ROOM_3_ADULTS_QTY').val(data.RES_ROOM_3_ADULTS_QTY);
	 
		$("#datepicker_from").datepicker({
			minDate: "+1d",
			altField: "#date_from",
			altFormat: 'yy-mm-dd',
			dateFormat: 'mm, d, yy',
			onSelect: toSetFinal,
			defaultDate: "+1d",
			changeYear: false,
			changeMonth: false,
			showOtherMonths: true,
			selectOtherMonths: true,
			prevText: "«",
			nextText: "»"
		});
		$("#datepicker_from").datepicker('setDate',start);

		$("#datepicker_to").datepicker({
			minDate: "+2d",
			altField: "#date_to",
			altFormat: 'yy-mm-dd',
			dateFormat: 'mm, d, yy',
			defaultDate: '+2d',
			onSelect: toSetFinal,
			changeYear: false,
			changeMonth: false,
			showOtherMonths: true, 
			selectOtherMonths: true,
			prevText: "«",
			nextText: "»"
		});
		$("#datepicker_to").datepicker('setDate',finish);
		
		setNigths();
	});
 
	
	function toSetFinal() {
		var date_from = $('#date_from').val().replace(/-/g, '/');	
		var date_from = new Date(date_from+' 00:00:00');
		var date_to = $('#date_to').val().replace(/-/g, '/');	
		var date_to = new Date(date_to+' 00:00:00');
		
		
		$("#datepicker_to").datepicker('destroy');
		$("#datepicker_to").datepicker({
			minDate: new Date(date_from.getFullYear()+'/'+(date_from.getMonth()+1)+'/'+(date_from.getDate()+1)+' 00:00:00'),
			altField: "#date_to",
			altFormat: 'yy/mm/dd',
			dateFormat: 'mm, d, yy', 
			defaultDate: '+8d',
			onSelect: toSetFinal,
			changeYear: false,
			changeMonth: false,
			showOtherMonths: true, 
			selectOtherMonths: true,
			prevText: "«",
			nextText: "»"
		});
		//$("#datepicker_to").datepicker('option', 'minDate', );
		//CHECK DATE TO  > DATE FROM
		var diff = Math.floor((date_to.getTime() - date_from.getTime()) / 24 / 60 / 60 / 1000);
 
		if(diff<=0) {
			var newdate = new Date(date_from.getFullYear()+'/'+(date_from.getMonth()+1)+'/'+(date_from.getDate()+1)+' 00:00:00');
			$('#date_to').val(newdate.getFullYear()+'/'+(newdate.getMonth()+1)+'/'+newdate.getDate());
			$("#datepicker_to").datepicker('setDate',newdate);
		}
		
		
		setNigths();
	};
	
	function setNigths() {
		var date_from = $('#date_from').val().replace(/-/g, '/');
		var date_from = new Date(date_from+' 4:00:00');
		var date_to = $('#date_to').val().replace(/-/g, '/');	
		var date_to = new Date(date_to+' 12:00:00');
		var diff = Math.floor((date_to.getTime() - date_from.getTime()) / 24 / 60 / 60 / 1000);
		$('#nights').val(diff);
	}
	
	//***********************
	//******POPUPERS********* 
	//***********************
	$('#ec_logo').live('mouseenter', function (e) {
		if($.browser.msie) {
			$('#popover_ex').css('display','block');
		} else {	
			$('#popover_ex').stop(true,true).fadeIn(300);
		}
		
		cord = $('#ec_logo').offset();
		top = cord.top;
		top = top - $('#popover_ex').height();
		
		$('#popover_ex').css('left',(cord.left-1-21)+'px');
		$('#popover_ex').css('top',top+'px');
		
	})
	$('#ec_logo').live('mouseleave', function (e) {
		if($.browser.msie) {
			$('#popover_ex').css('display','none');
		} else {	
			$('#popover_ex').fadeOut(300);
		}
	});
	
	$('.name span').live('mouseenter', function (e) {		
		if($.browser.msie) {
			$('#popover_ex').css('display','block');
		} else {	
			$('#popover_ex').stop(true,true).fadeIn(300);
		}
		cord = $(this).offset();
		top = cord.top;
		top = top - $('#popover_ex').height()-20;
		
		$('#popover_ex').css('left',(cord.left-1-21)+'px');
		$('#popover_ex').css('top',top+'px');
		
	})
	$('.name span').live('mouseleave', function (e) {
		if($.browser.msie) {
			$('#popover_ex').css('display','none');
		} else {	
			$('#popover_ex').fadeOut(300);
		}
	});
	
	//SPECIAL	
	$('.popover_special').live('click', function (e) {	
		var prop_id = $(this).attr('rel');
		var prop = data.RES_ITEMS[prop_id];
		$('#popover_special .inner').html(prop.DESCR_EN);
		if($.browser.msie) {
			$('#popover_special').css('display','block');
		} else {	
			$('#popover_special').stop(true,true).fadeIn(300);
		}
		cord = $(this).offset();
		top = cord.top;
		top = top - $('#popover_special').height()-16;
		
		$('#popover_special').css('left',(cord.left-1-41)+'px');
		$('#popover_special').css('top',top+'px');
		return false;
	})
	$('.popover_special').live('mouseleave', function (e) {
		if($.browser.msie) {
			$('#popover_special').css('display','none');
		} else {	
			$('#popover_special').fadeOut(300);
		}
	});
	//PROMO	
	$('.popover_promo').live('click', function (e) {	
		var prop_id = $(this).attr('rel');
		var prop = data.RES_ITEMS[prop_id];
		$('#popover_promo .inner').html(prop.DESCR_EN);
		if($.browser.msie) {
			$('#popover_promo').css('display','block');
		} else {	
			$('#popover_promo').stop(true,true).fadeIn(300);
		}
		cord = $(this).offset();
		top = cord.top;
		top = top - $('#popover_promo').height()-16;
		
		$('#popover_promo').css('left',(cord.left-1-41)+'px');
		$('#popover_promo').css('top',top+'px');
		return false;
	})
	$('.popover_promo').live('mouseleave', function (e) {
		if($.browser.msie) {
			$('#popover_promo').css('display','none');
		} else {	
			$('#popover_promo').fadeOut(300);
		}
	});
	
	//RATE
	$('.rate-detail').live('click', function (e) {
		var room_id = $(this).attr('room_id');
		var room_now = $(this).attr('room_now');
		printCalendar(room_id, room_now);
		//EFFECTS & LOCATION
		if($.browser.msie) {
			$('#popover_rate').css('display','block');
		} else {	
			$('#popover_rate').stop(true,true).fadeIn(300);
		}
		
		var cord = $(this).offset();
		var top = cord.top;
		top = top -54;
		
		$('#popover_rate').css('left',(cord.left-609)+'px');
		$('#popover_rate').css('top',top+'px');
		return false;
	})
	$('.rate-detail').live('mouseleave', function (e) {
		if($.browser.msie) {
			$('#popover_rate').css('display','none');
		} else {	
			$('#popover_rate').fadeOut(300);
		}
	});
});
  
  
function printCalendar(room_id, room_now, br) {
	if(!br) br = '<br/>';

	$('#popover_rate .cal_content').html('');
	//COUNT
	var MAIN_DATA = data['RES_ROOM_'+room_now+'_ROOMS'];
	var PPL_COUNT = data['RES_ROOM_'+room_now+'_GUESTS_QTY'];
	var date_from = data.RES_CHECK_IN.replace(/-/g, '/');	
	var start = new Date(date_from+' 00:00:00');
	var finish = new Date( start.getFullYear()+'/'+(start.getMonth()+1)+'/'+(start.getDate()+data.RES_NIGHTS)+' 00:00:00');
	
	
	//Pustota
	for(i=0; i<start.getDay();i++) {
		$('#popover_rate .cal_content').append('<div>&nbsp;</div>');
	}
	//Prices
	jQuery.each(MAIN_DATA[room_id].NIGTHS, function (k, el) {
		$('#popover_rate .cal_content').append('<div><s>$'+(el.RATE.GROSS * PPL_COUNT)+'</s>$'+(el.RATE.FINAL * PPL_COUNT)+'</div>');
	});
	

	//COUNT WEEKS 
	$('#popover_rate .lcal').html('');
	var month = new  Array ('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
	var weeks = 1;
	var ngth = data.RES_NIGHTS - (7 - start.getDay()) ;
	do {
		ngth = ngth - 7;
		weeks++;
	} while (ngth > 7);
	if(ngth > 0) weeks++;
	 
	//FIRST WEEK
	var ngth = data.RES_NIGHTS - (7 - start.getDay());
	var prom = new Date( start.getFullYear()+'/'+(start.getMonth()+1)+'/'+(start.getDate()+(6-start.getDay()))+' 00:00:00');
	$('#popover_rate .lcal').append('<dt class="date">'+dateformat(data.RES_CHECK_IN)+'-'+br+month[prom.getMonth()]+' '+prom.getDate()+'</dt>');
	
	//BETWEEN WEEKS
	var full_weeks = (data.RES_NIGHTS - (7 - start.getDay()) -  finish.getDay())/7;
	for(i = 0; i < full_weeks; i++) {			
		var proms = new Date( start.getFullYear()+'/'+(start.getMonth()+1)+'/'+(start.getDate()+(6-start.getDay())+(7*i)+1)+' 00:00:00');
		var promf = new Date( start.getFullYear()+'/'+(start.getMonth()+1)+'/'+(start.getDate()+(6-start.getDay())+(7*i+7))+' 00:00:00');			
		$('#popover_rate .lcal').append('<dt class="date">'+month[proms.getMonth()]+' '+proms.getDate()+'-'+br+month[promf.getMonth()]+' '+promf.getDate()+'</dt>');
	}
	
	//FINAL WEEK
	if(( (7 - start.getDay()) <= data.RES_NIGHTS)  && (finish.getDay()>0)) {
		var prom = new Date( start.getFullYear()+'/'+(start.getMonth()+1)+'/'+(start.getDate()+ (data.RES_NIGHTS -finish.getDay()))+' 00:00:00');
		$('#popover_rate .lcal').append('<dt class="date">'+month[prom.getMonth()]+' '+prom.getDate()+'-'+br+month[finish.getMonth()]+' '+finish.getDate()+'</dt>');
	}
}
  
//ROOM OPEN CALENDAR
function printCalendar2(room_id, room_now, br) {
	$('#room'+room_id+' .cal_content').html('');
	//COUNT
	var MAIN_DATA = data['RES_ROOM_'+room_now+'_ROOMS'];
	var PPL_COUNT = data['RES_ROOM_'+room_now+'_GUESTS_QTY'];
	var date_from = data.RES_CHECK_IN.replace(/-/g, '/');	
	var start = new Date(date_from+' 00:00:00');
	var finish = new Date( start.getFullYear()+'/'+(start.getMonth()+1)+'/'+(start.getDate()+data.RES_NIGHTS)+' 00:00:00');
	
	
	//Pustota
	for(i=0; i<start.getDay();i++) {
		$('#room'+room_id+' .cal_content').append('<div>&nbsp;</div>');
	}
	//Prices
	jQuery.each(MAIN_DATA[room_id].NIGTHS, function (k, el) {
		if(el.RATE) {
			$('#room'+room_id+' .cal_content').append('<div><s>$'+(el.RATE.GROSS * PPL_COUNT)+'</s>$'+(el.RATE.FINAL * PPL_COUNT)+'</div>');
		} else {
			$('#room'+room_id+' .cal_content').append('<div><em>&nbsp;</em>x</div>');
		}
	});
	

	//COUNT WEEKS 
	$('#room'+room_id+' .lcal').html('');
	var month = new  Array ('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
	var weeks = 1;
	var ngth = data.RES_NIGHTS - (7 - start.getDay()) ;
	do {
		ngth = ngth - 7;
		weeks++;
	} while (ngth > 7);
	if(ngth > 0) weeks++;
	 
	//FIRST WEEK 
	var ngth = data.RES_NIGHTS - (7 - start.getDay());
	var prom = new Date( start.getFullYear()+'/'+(start.getMonth()+1)+'/'+(start.getDate()+(6-start.getDay()))+' 00:00:00');
	$('#room'+room_id+' .lcal').append('<dt class="date">'+dateformat(data.RES_CHECK_IN)+'-'+month[prom.getMonth()]+' '+prom.getDate()+'</dt>');
	
	//BETWEEN WEEKS
	var full_weeks = (data.RES_NIGHTS - (7 - start.getDay()) -  finish.getDay())/7;
	if(full_weeks>=1) {
		for(i = 0; i < full_weeks; i++) {			
			var proms = new Date( start.getFullYear()+'/'+(start.getMonth()+1)+'/'+(start.getDate()+(6-start.getDay())+(7*i)+1)+' 00:00:00');
			var promf = new Date( start.getFullYear()+'/'+(start.getMonth()+1)+'/'+(start.getDate()+(6-start.getDay())+(7*i+7))+' 00:00:00');			
			$('#room'+room_id+' .lcal').append('<dt class="date">'+month[proms.getMonth()]+' '+proms.getDate()+'-'+month[promf.getMonth()]+' '+promf.getDate()+'</dt>');
		}
	} else full_weeks = 0;
	
	//FINAL WEEK
	if(( (7 - start.getDay()) <= data.RES_NIGHTS) && (finish.getDay()>0)) {
		var prom = new Date( start.getFullYear()+'/'+(start.getMonth()+1)+'/'+(start.getDate()+ (data.RES_NIGHTS -finish.getDay()))+' 00:00:00');
		$('#room'+room_id+' .lcal').append('<dt class="date">'+month[prom.getMonth()]+' '+prom.getDate()+'-'+month[finish.getMonth()]+' '+finish.getDate()+'</dt>');
	}
}
  