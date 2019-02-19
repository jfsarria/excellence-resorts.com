var _book = {
    'room':[]
};
var GID = 0;
var errTEXT = new Array();
var BOOK = {};
var TOTAL_COST = 0;
var TA_CLIENTS = {};

$(document).ready(function() {
  $(".vip_0:last, .vip_1:last, .vip_2:last").addClass("last-in-list");
    $('.tip').tipr({
        'mode': 'top'
    });
	$('.rate-detail').click(function(){
		var ROOM_NUM = $(this).attr('room_num'),
            RATES = $("#list-room-num-"+ROOM_NUM+" .selected .dailyDetailsTbl").parent().html();

		//printCalendar(room_id, room_num);
        $("#popover_rate .inner").html(RATES);

		//EFFECTS & LOCATION
        $('#popover_rate').show();
		var cord = $(this).offset();
		var top = cord.top;
		top = top - 54;
		
		$('#popover_rate').css('left',(cord.left-550)+'px');
		$('#popover_rate').css('top',top+'px');
		$('#popover_rate').css('display','block!important');
		return false;
	});
	$('.rate-detail').mouseleave(function(){
		$('#popover_rate').hide();
	});

	$('#GUEST_EMAIL').blur(function() {
        if ($('#GUEST_EMAIL').val().length > 0) {
            $.get( "/ibe/index.php", { 
                PAGE_CODE: "ws.checkGuestEmail",
                email: $(this).val(),
                id: $('#GUEST_ID').val()
            }, 
            function (data) {
                if(data == 'found') {
                    alert("This Email Address is already in the system, please login to continue or use a different email")
                } else {
                }
            });
        }
	});

    //TRANSFERS
    var transferdata = $.getJSON('/ibe/index.php', {
        PAGE_CODE: 'ws.getTransferSettings',
        FIELDS: "IS_ACTIVE,OVERVIEW_EN,OVERVIEW_SP"
        }, function(retVal) {
            setUpTransfer(false, retVal);
    });

});


function see_more(ITEM_ID) {
    $("#"+ITEM_ID).addClass("see-more");
	startSlide(ITEM_ID);
}

function see_less(ITEM_ID) {
    $("#"+ITEM_ID).removeClass("see-more");
}

function startSlide(ITEM_ID) {
	var slider = $('#slider-'+ITEM_ID);

	if (!slider.hasClass("rendered") && slider.find("img").length > 1)	{
		slider.addClass("rendered");
		slider.leanSlider({
			directionNav: '#slider-direction-nav-'+ITEM_ID,
			controlNav: '#slider-control-nav-'+ITEM_ID
		});
	}
}

function set_lbl_vip(list_rooms) {
    list_rooms.find('.vip_2:first').before('<div class="section-title">Excellence Club Rooms</div>');
    list_rooms.find('.vip_1:first').before('<div class="section-title">Excellence Club Suites</div>');
}

function select_room(ITEM_ID, track) {
	if (typeof(ITEM_ID)=='undefined') return;

    var track = track || false;
        room = ITEM_ID.split("_"),
        room_num = room[0],
        room_id = room[1],
        list_rooms = $("#list-room-num-"+room[0]),
        selected = list_rooms.find("#"+ITEM_ID),
        room_name = selected.find(".room-name").html(),
        bed_types = selected.attr("data-bed_types").split(","),
        vip = parseInt(selected.attr("data-vip"),10),
        price_was = parseInt(selected.find(".price-was").attr("rel"),10),
        price_is = parseInt(selected.find(".price-is").attr("rel"),10);
        total_price_was = parseInt(selected.find(".total-price-was").attr("rel"),10),
        total_price_is = parseInt(selected.find(".total-price-is").attr("rel"),10);

    search_modify(0);

    list_rooms.find(".room,.sep-line").removeClass("selected prev next");
    selected.addClass("selected");
    selected.prev(".sep-line").addClass("prev");
    selected.prevAll(".room:first").addClass("prev");
    selected.nextAll(".room:first").addClass("next");

    list_rooms.find(".price-descr").html(lan=="EN"?"Per Night":"Por Noche");
    list_rooms.find(".price-was").html("");

    selected.find(".price-descr").html(lan=="EN"?"Per Night<br>All Inclusive":"Por Noche<br>Todo Incluido");
    selected.find(".price-was").html(price_was!=0?formatCurrency(price_was):"");
    selected.find(".price-is").html(formatCurrency(price_is));
        
    pointer = selected.prevAll(".room:first");
    while (pointer && pointer.length==1) {
        pointer_price_is = parseInt(pointer.find(".price-is").attr("rel"),10);
        pointer.find(".price-is").html(formatCurrency(pointer_price_is - price_is, null, "+ "));
        pointer = pointer.prevAll(".room:first");
    }

    pointer = selected.nextAll(".room:first");
    while (pointer && pointer.length==1) {
        pointer_price_is = parseInt(pointer.find(".price-is").attr("rel"),10);
        pointer.find(".price-is").html(formatCurrency(pointer_price_is - price_is, null, "+ "));
        pointer = pointer.nextAll(".room:first");
    }

    $("#summary_room_"+room_num+" .room_name").html(room_name+": $"+number_format(total_price_is));
    $("#pref_room_"+room_num+" .room_name").html(room_name);

    var GUEST_BEDTYPE = $("#ROOM_"+room_num+"_GUEST_BEDTYPE");
    GUEST_BEDTYPE.html('<option value="">'+(lan=="EN"?"No preferences":"Ninguna")+'</option>');
    for (i in bed_types) {
        GUEST_BEDTYPE.append("<option value='"+bed_types[i]+"'>"+PROPERTY_BED_TYPES[bed_types[i]]+"</option>");
    }

    track_selection(room_num, room_id, total_price_was, total_price_is, vip);
    update_totals(room_num);

}

function track_selection(room_num, room_id, total_price_was, total_price_is, vip) {
    _book.room[room_num] = {
        'id':room_id,
        'vip':vip,
        'total_price_was':total_price_was,
        'total_price_is':total_price_is
    };
    room_options(room_num, vip);

	//console.log('ibe_price_'+room_num + " : " + total_price_is)
	if (room_num==1) {
		dataLayer.push({"ibe_price_1" : total_price_is});
	} else if (room_num==2) {
		dataLayer.push({"ibe_price_2" : total_price_is});
	} else if (room_num==3) {
		dataLayer.push({"ibe_price_3" : total_price_is});
	}
	
}

function room_options(room_num, vip) {
    var room = $("#pref_room_"+room_num);
    room.find(".field_vip").hide();
    room.find(".field_vip_"+vip).show();
}

function formatCurrency(total, decimals, sign) {
    var symbol = currency_symbol($("#QUOTE").val())+" ",
		decimals = decimals || 0,
        sign = sign || "",
        neg = false;
    if(total < 0) {
        neg = true;
        total = Math.abs(total);
    }
    return (neg ? "- "+symbol : sign+symbol) + parseFloat(total, 10).toFixed(decimals).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString();
}

function click_room_tab(ROOM_NUM, is_modify) {
    var is_modify = is_modify || false;
    $(".list-rooms").addClass("hidden");
    $("#list-room-num-"+ROOM_NUM+" .room").removeClass("see-more");
    $("#list-room-num-"+ROOM_NUM).removeClass("hidden");
    $("#room-tabs li").removeClass("selected");
    $("#tab-room-"+ROOM_NUM).addClass("selected");

    $("#select-rooms").removeClass("hidden");
    $("#guest-info").addClass("hidden");
    select_nav_step(1);

    if (is_modify && !$("#btn-book-now").hasClass("hidden")) {
        $("#wrap-buttons img").addClass("hidden");
        $("#btn-continue-"+$("#select-rooms").attr("data-rooms-qty")).removeClass("hidden");
        $("#btn-book-now").addClass("hidden");
    }

    return true;
}

function select_continue(args) {
	//AVAILABLE, ROOM_NUM, ROOMS_QTY
	var AVAILABLE = args.AVAILABLE,
		ROOM_NUM = args.ROOM_NUM,
		ROOMS_QTY = args.ROOMS_QTY;

	//alert(AVAILABLE + "," + ROOM_NUM + "," + ROOMS_QTY + " = " + args.toSource())

	if (AVAILABLE==1) {
		var NEXT_ROOM_NUM = ROOM_NUM+1;

		search_modify(0);

		$("#wrap-buttons .btn-continue").addClass("hidden");
		if (NEXT_ROOM_NUM <= ROOMS_QTY) {
			$("#summary_room_"+NEXT_ROOM_NUM).removeClass("hidden");
			$("#btn-continue-"+NEXT_ROOM_NUM).removeClass("hidden");
			click_room_tab(NEXT_ROOM_NUM);
		} else {
			quote_Change(null, "USDUSD");
			$("#room_conversion").addClass("hidden");
			if ($("#QUOTE").val()!="USDUSD") {
				$("#total_conversion").removeClass("hidden");
				$("#money_code").text("USD");
				$("#total_was,#total_is").each(function(){
					var str = $(this).text(),
						out = str.replace(/^.*\s/i, "$ "); 		
					$(this).text(out);
				})
			}

			$("#btn-book-now").removeClass("hidden");
			$("#select-rooms").addClass("hidden");
			$("#guest-info").removeClass("hidden");

			dataLayer.push({"ibe_step": "step-2"});

			select_nav_step(2);
		}
		if (ROOM_NUM==ROOMS_QTY-1) {
			$("#totals").removeClass("hidden");
		}

		ga('send', {
			hitType : 'pageview',
			page : '/booking-client-info',
			title : 'Booking - Client Info'
		});

		// JUST WAITING TO SEE IF THEY WANTED HERE TOO
		/*
		taq('track', 'START_BOOKING', {
			'travel_start_date' : data.RES_CHECK_IN,
			'travel_end_date' : data.RES_CHECK_OUT
		}); 
		*/

		//console.log(data.RES_CHECK_IN, data.RES_CHECK_OUT)

	} else {
		if (args.LN=="EN") {
			alert("Unfortunately there is no availability for the given search criteria");
		} else {
			alert("Desafortunadamente, no hay disponibilidad para los criterios de búsqueda indicados");
		}
	}
}

function quote_Change(ele, val) {
	var val = val || $(ele).val();
	//console.log(val);console.log(CURRENCY[val]);
	
	$("#conversion_code").text(val.replace("USD",""));

	$(".room_currency").each(function(){
		var usd = $(this).attr("data-usd"),
			rel = Math.round(usd * (typeof(CURRENCY[val])!="undefined" ? CURRENCY[val] : 1));
		//console.log(rel);
		$(this).attr("rel",rel);
	});

	$("div.room.selected").each(function(){
		select_room($(this).attr("id"), true);	
	});

}

function currency_symbol(val) {
	return (typeof(CURRENCY_SYMBOL[val])!="undefined" ? CURRENCY_SYMBOL[val] : "$");
}

function update_totals() {
    var currval = $("#QUOTE").val(),
		symbol = currency_symbol(currval) + "&nbsp;",
		total_conv_hidden = $("#total_conversion").hasClass("hidden"),
		total_was = 0,
        total_is = 0;

    for (room_num in _book.room) {
        total_was += _book.room[room_num].total_price_was;
        total_is += _book.room[room_num].total_price_is;
    }

	$("#money_code").text( total_conv_hidden ? currval.replace("USD","") : "USD" );
    $("#total_was").html(total_was!=0?(total_conv_hidden?symbol:"$")+number_format(total_was):"");
    $("#total_is").html((total_conv_hidden?symbol:"$")+number_format(total_is));
    $("#total_cc_charge_is").html(total_is!=0?"$"+number_format(total_is):"");
    TOTAL_COST = total_is;

	update_conversion(currval, symbol);

	return TOTAL_COST;
}

function update_conversion(currval, symbol) {
    var transfer_charge = $("#total_transfer_charge").text(),
		money = typeof(CURRENCY[currval])!="undefined" ? CURRENCY[currval] : 1,
		total_was = 0,
        total_is = 0;

    for (room_num in _book.room) {
        total_was += _book.room[room_num].total_price_was;
        total_is += _book.room[room_num].total_price_is;
    }

	transfer_charge = typeof(transfer_charge)!="undefined" ? parseInt(transfer_charge,10) : 0;
	transfer_charge = !isNaN(transfer_charge) ? transfer_charge : 0;

	//console.log("transfer_charge: "+transfer_charge)
	//console.log("currval: " + currval + " money: " + money)

	total_was += transfer_charge;
	total_is += transfer_charge;

	total_was *= money;
	total_is *= money;

	$("#conversion_code").text(currval.replace("USD",""));
    $("#conv_total_was").html(total_was!=0?symbol+number_format(Math.round(total_was)):"");
    $("#conv_total_is").html(symbol+number_format(Math.round(total_is)));



}

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

function search_modify(side) {
    if (side==1) {
        $("#wrap-summary").hide();
        $("#wrap-modify").show();
    } else {
        $("#wrap-summary").show();
        $("#wrap-modify").hide();
    }
}

function login(search_all) {
    var email = $('#EMAIL'),
        pwd = $('#PWD'),
        hello_guest = $("#hello-guest"),
        login_guest = $("#login-guest");
    var logindata = $.get('/ibe/index.php', {
        PAGE_CODE: 'ws.getGuest',
        EMAIL:  email.val(),
        PWD:    pwd.val()
        }, function(guest) {
            if(guest.IS_ACTIVE == 1) { 
                document.cookie="RES_GUEST="+JSON.stringify(guest)+"; path=/";
                document.cookie="RES_TA={}; path=/";
                guest_fillout(guest);
                //
                email.removeClass('required');
                pwd.removeClass('required');
                login_guest.hide();
                hello_guest.show();
                $('#login_name').html(guest.FIRSTNAME+' '+guest.LASTNAME);
            } else {
                document.cookie="RES_GUEST={}; path=/";
                document.cookie="RES_TA={}; path=/";

                if (search_all) {
                    ta_login();
                } else {
                    email.addClass('required');
                    pwd.addClass('required');
                    alert("Please check your email and password and enter them again. If problems persist please call us USA 1 866 540 25 85, Canada 1 866 451 15 92, Mexico 01 800 966 36 70, UK 0 800 051 6244");
                }
            }
        }); 
        logindata.error(function() { alert("Error getting user information"); })
}

function ta_login() {
    var email = $('#EMAIL'),
        pwd = $('#PWD'),
        hello_ta = $("#hello-ta"),
        login_guest = $("#login-guest");
    var logindata = $.get('/ibe/index.php', {
        PAGE_CODE: 'ws.getTA',
        EMAIL:  email.val(),
        PWD:    pwd.val()
        }, function(ta) {
            if(ta.IS_ACTIVE == 1 && ta.IS_CONFIRMED == 1) { 
                document.cookie="RES_TA="+JSON.stringify(ta)+"; path=/";
                document.cookie="RES_GUEST={}; path=/";
                $('#TA_ID').val(ta.ID);
                $(".ta_field").show();
                //
                email.removeClass('required');
                pwd.removeClass('required');
                login_guest.hide();
                hello_ta.show();
                $('#ta_login_name').html(ta.FIRSTNAME+' '+ta.LASTNAME);
            } else {
                document.cookie="RES_GUEST={}; path=/";
                document.cookie="RES_TA={}; path=/";

                email.addClass('required');
                pwd.addClass('required');
                $(".ta_field").hide();
                alert("Please check your email and password and enter them again. If problems persist please call us USA 1 866 540 25 85, Canada 1 866 451 15 92, Mexico 01 800 966 36 70, UK 0 800 051 6244");
            }
        }); 
        logindata.error(function() { alert("Error getting information"); })
}

function get_ta_clients() {
    $("#clients_data").html("").show();

    var logindata = $.getJSON('/ibe/index.php', {
        PAGE_CODE: 'ws.getTAguests',
        ID: $('#TA_ID').val()
        }, function(result) {
            if (typeof(result.guests)!="undefined") {
				var guests = result.guests;
				if (typeof(result.guests.length)=="undefined") {
					guests = [result.guests];
				}
				$.each(guests, function(key, guest) {
                    $("#clients_data").append('<div><a href="javascript:void(0)" onclick="select_ta_guest('+guest.ID+')">'+guest.FIRSTNAME+" "+guest.LASTNAME+'</a></div>');
                    TA_CLIENTS[guest.ID] = guest;
				});
                $("#clear_data").show();
            } else {
                TA_CLIENTS = {};
                $("#clients_data").html("You do not have any clients in the system yet. Start a new profile below.").show();
                $("#clear_data").hide();
            }
        });
}

function select_ta_guest(ID) {
    guest_fillout(TA_CLIENTS[ID])
}

function guest_fillout(guest) {
    $('#GUEST_ID').val(guest.ID);
    $('#GUEST_TITLE').val(guest.TITLE);
    $('#GUEST_FIRSTNAME').val(typeof(guest.FIRSTNAME)=="string"?guest.FIRSTNAME:"");
    $('#GUEST_LASTNAME').val(typeof(guest.LASTNAME)=="string"?guest.LASTNAME:"");
    $('#GUEST_EMAIL').val(typeof(guest.EMAIL)=="string"?guest.EMAIL:"");
    $('#GUEST_PHONE').val(typeof(guest.PHONE)=="string"?guest.PHONE:"");
    $('#GUEST_ADDRESS').val(typeof(guest.ADDRESS)=="string"?guest.ADDRESS:"");
    $('#GUEST_CITY').val(typeof(guest.CITY)=="string"?guest.CITY:"");
    $('#GUEST_ZIPCODE').val(typeof(guest.ZIPCODE)=="string"?guest.ZIPCODE:"");					
    $('#GUEST_STATE').val(typeof(guest.STATE)=="string"?guest.STATE:"");                
    $('#GUEST_COUNTRY').val(typeof(guest.COUNTRY)=="string"?guest.COUNTRY:"");
    $('#GUEST_COUNTRY').change();
    $("#GUEST_STATE").val(typeof(guest.STATE)=="string"?guest.STATE:"");
    $(".states-list").val(typeof(guest.STATE)=="string"?guest.STATE:"");
}

function logout() {
    document.cookie="RES_GUEST={}; path=/";
    document.cookie="RES_TA={}; path=/";

    clear_data();

    $('#TA_ID').val('0');
    $("#hello-ta").hide();
    $("#hello-guest").hide();
    $("#login-guest").show();
    $("#clear_data").hide();
    $("#clients_data").html("").show();

    $(".ta_field").hide();
}

function clear_data() {
    $('#GUEST_ID').val('0');
    $('#GUEST_TITLE').val("");
    $('#GUEST_FIRSTNAME').val("");
    $('#GUEST_LASTNAME').val("");
    $('#GUEST_EMAIL').val("");
    $('#GUEST_EMAIL_CONFIRM').val("");
    $('#GUEST_PHONE').val("");
    $('#GUEST_ADDRESS').val("");
    $('#GUEST_CITY').val("");
    $('#GUEST_ZIPCODE').val("");					
    $('#GUEST_STATE').val("");                
    $('#GUEST_COUNTRY').val("");
    $('#GUEST_COUNTRY').change(); 	
}

function popover_open(ele, popup) {
    var $popup = $("#"+popup),
        cord = ele.offset(),
        top = cord.top - $popup.height() - 1,
        left = (cord.left-$popup.width())+ele.width();
    //left = left+ele.width>$(window).width()
    $popup.css({'left':left+'px','top':top+'px'}).show();
    return true;
}

function sendGuestPwd() {
    var email = $('#popover_pwd input').val();
    var fogotdata = $.get('/ibe/index.php', {
        PAGE_CODE: 'ws.sendGuestPwd',
        EMAIL: email
        }, function(guest) {
            if(guest.error) {
                alert("Error sending guest password");
            } else {
                $('#popover_pwd').hide();
                alert("Password has been sent")
            }
        }); 
        fogotdata.error(function() { alert("Error getting guest password"); })	    
}

function billing_box() {
    if ($("#billing_box")[0].checked) {
        $("#billing_tbl").hide();
    } else {
        $("#billing_tbl").show();
    }
}

function hear_txt(ele) {
    var txt = $("#"+ele.val()+"_txt");
    $(".hear_txt").hide();
    if (ele[0].checked) {
        txt.show();
    }
}

function get_states_list(ele) {
    var PREFIX = ele.attr("id").indexOf("BILL-")>=0 ? "BILL-" : "",
        CODE = ele.val(),
        states = $("#"+PREFIX+CODE+"-states");
    return (states.length==0) ? $("#"+PREFIX+"GUEST_STATE") : states;
}


function country_changed(ele) {
    var PREFIX = ele.attr("id").indexOf("BILL-")>=0 ? "BILL-" : "",
        states = get_states_list(ele);
    $("."+PREFIX+"states-list").addClass('hide');
    states.removeClass('hide');
}

function getCardType(type) {
	var cards = new Array();
	cards[0] = { name: "MasterCard", clength: [16], exp: /^5[1-5]/ };
	cards[1] = { name: "Visa", clength: [13, 16], exp: /^4/ };
	cards[2] = { name: "AmEx", clength: [15], exp: /^3(4|7)/ };
	var card = null; ;
	for (i = 0; i < cards.length; i++) {
		if (cards[i].name.toLowerCase() == type.toLowerCase()) {
			card = cards[i];
			break;
		}
	}
	return card;
}

function validateCardNumber(cn){ 
	var cn1 = cn;
	if(cn.length < 10 || cn.length > 16) return false;
	if(cn.length < 16) cn = '0'+cn;
	var odd = new Array();
	var even = new Array();
	for(i=0;i<cn.length;i++) {
		if(i%2==0){
			odd[odd.length]= cn.substr(i,1);
		} else {
			even[even.length]= cn.substr(i,1);
		} 
	}
	
	
	for(i=0;i<odd.length;i++) {
		var tmp = odd[i] * 2;
		if(tmp <= 9){
			odd[i] = tmp;
		} else {
			var s = String(tmp);
			odd[i] = (s[0] - 0) + (s[1] - 0);
		}
	}
	
	
	var summa = 0;
	for(i=0;i<odd.length;i++) {
		summa += odd[i];
	}
	for(i=0;i<even.length;i++) {
		summa += even[i] - 0;
	}
	
	
	if(summa%10 == 0){ 
		var lengthIsValid = false;
		var prefixIsValid = false;
		var prefixRegExp; 
		var cardNumberLength = cn1.length; 
		var ctype = $('#CC_TYPE').val();
		var card = getCardType(ctype);
		if (card != null) {
			for (i = 0; i < card.clength.length; i++) {
				if (!lengthIsValid) {
					if (cardNumberLength == card.clength[i]) lengthIsValid = true;
				}
			}
			prefixIsValid = card.exp.test(cn1);
		}
		isValid = prefixIsValid && lengthIsValid;
		
		if(isValid) return true;
	} else {
		return false;
	}
}

function validateCard() {
    var cc_num = $('#CC_NUMBER').val().replace(/\D/g, "");
    if(!validateCardNumber(cc_num)) {
        errTEXT.push("Please confirm your credit card number");
    }
    var zeros = function(n) { return parseInt(n,10)<10 ? "0"+parseInt(n,10) : parseInt(n,10) },
        expiry = new Date("20"+$('#CC_EXP_YEAR').val(), parseInt($('#CC_EXP_MONTH').val(),10), 1),
        today = new Date();
    if (expiry.getTime() <= today.getTime()) {
        errTEXT.push("Please check card expiration date");
    }
}

function validate_form() {
    errTEXT = new Array();

    validateTransferForm()

    var STATE = $(".states-list").not(".hide").first();
    if (STATE.attr("id")!="GUEST_STATE") $("#GUEST_STATE").val(STATE.val());

    var BILL_STATE = $(".BILL-states-list").not(".hide").first();
    if (BILL_STATE.attr("id")!="BILL-GUEST_STATE") $("#BILL-GUEST_STATE").val(BILL_STATE.val());

    if ($("#GUEST_FIRSTNAME").val().trim()=="") errTEXT.push("First name");
    if ($("#GUEST_LASTNAME").val().trim()=="") errTEXT.push("Last name");
    if ($("#GUEST_PHONE").val().trim()=="") errTEXT.push("Phone number");
    if ($("#GUEST_ADDRESS").val().trim()=="") errTEXT.push("Street address");
    if ($("#GUEST_CITY").val().trim()=="") errTEXT.push("City");
    if ($("#GUEST_ZIPCODE").val().trim()=="") errTEXT.push("Zipcode");

    if ($("#WIRE_TRANSFER").length==1 && !$("#WIRE_TRANSFER")[0].checked) {
        if ($("#CC_NUMBER").val().trim()=="") { errTEXT.push("Credit card number") } else { validateCard(); }
        if ($("#CC_SECCODE").val().trim()=="") errTEXT.push("Credit card 3 digits security code");
        if ($("#CC_NAME").val().trim()=="") errTEXT.push("Card holder name");
    }

    if ($("#TA_ID").val()==0) {
        if ($("#GUEST_EMAIL").val().trim()=="") errTEXT.push("Email address");
        if ($("#GUEST_ID").val()==0 && ($("#GUEST_EMAIL").val().trim()!=$("#GUEST_EMAIL_CONFIRM").val().trim())) errTEXT.push("Please confirm your email address");
    }

    var ROOMS_QTY = parseInt($("#select-rooms").attr("data-rooms-qty"),10);
    if (ROOMS_QTY>1) {
        for (var ROOM_NUM=1; ROOM_NUM<=ROOMS_QTY; ++ROOM_NUM) {
            
            var ROOM_GUEST_FIRSTNAME = $("#ROOM_"+ROOM_NUM+"_GUEST_FIRSTNAME");
            if (ROOM_GUEST_FIRSTNAME.val().trim()=="") ROOM_GUEST_FIRSTNAME.val($("#GUEST_FIRSTNAME").val().trim());
            
            var ROOM_GUEST_LASTNAME = $("#ROOM_"+ROOM_NUM+"_GUEST_LASTNAME");
            if (ROOM_GUEST_LASTNAME.val().trim()=="") ROOM_GUEST_LASTNAME.val($("#GUEST_LASTNAME").val().trim());
        }
    }

    if(!$('#AGREE')[0].checked) errTEXT.push("You must agree to the terms and conditions");

}

function book_now() {
    BOOK = {};

    validate_form();

    if (errTEXT.length==0) {
        $("#btn-book-now").addClass("hidden");
        $("#loading-making-booking").show();

        var ROOMS_QTY = parseInt($("#select-rooms").attr("data-rooms-qty"),10);

        BOOK.RES_ROOMS_SELECTED = [];
        BOOK.ROOMS = [];

        for (var ROOM_NUM=1; ROOM_NUM<=ROOMS_QTY; ++ROOM_NUM) {
            var ROOM_LIST = $("#list-room-num-"+ROOM_NUM),
                SELECTED = ROOM_LIST.find(".selected"),
                SELECTED_ID = SELECTED.attr("id").substr(2);

            BOOK.RES_ROOMS_SELECTED.push(SELECTED_ID);
            BOOK.ROOMS.push({
              "GUEST_TITLE": $("#ROOM_"+ROOM_NUM+"_GUEST_TITLE").val(),
              "GUEST_FIRSTNAME": $("#ROOM_"+ROOM_NUM+"_GUEST_FIRSTNAME").val(),
              "GUEST_LASTNAME": $("#ROOM_"+ROOM_NUM+"_GUEST_LASTNAME").val(),
              "GUEST_BEDTYPE": $("#ROOM_"+ROOM_NUM+"_GUEST_BEDTYPE").val(),
              "GUEST_SMOKING": $("#ROOM_"+ROOM_NUM+"_GUEST_SMOKING").val(),
              "GUEST_OCCASION": $("#ROOM_"+ROOM_NUM+"_GUEST_OCCASION").val(),
              "GUEST_BABYCRIB": $("#ROOM_"+ROOM_NUM+"_GUEST_BABYCRIB")[0].checked?"1":"0"
              //"GUEST_REPEATED": [$("#ROOM_"+ROOM_NUM+"_GUEST_REPEATED")[0].checked?"5":"0"]
            });
        }

        BOOK.GUEST = {
            "TITLE": $("#GUEST_TITLE").val(),
            "FIRSTNAME": $("#GUEST_FIRSTNAME").val(),
            "LASTNAME": $("#GUEST_LASTNAME").val(),
            "LANGUAGE": $("#RES_LANGUAGE").val(),
            "ADDRESS": $("#GUEST_ADDRESS").val(),
            "CITY": $("#GUEST_CITY").val(),
            "STATE": $("#GUEST_STATE").val(),
            "COUNTRY": $("#GUEST_COUNTRY").val(),
            "ZIPCODE": $("#GUEST_ZIPCODE").val(),
            "PHONE": $("#GUEST_PHONE").val(),
            "EMAIL": $("#GUEST_EMAIL").val(),
            "MAILING_LIST": $("#MAILING_LIST")[0].checked?"1":"0"
        }

        var IS_TA = $("#TA_ID").val()!=0,
            USE_AS_BILLING = $("#billing_box")[0].checked,
            IS_WIRE = $("#WIRE_TRANSFER").length==1 && $("#WIRE_TRANSFER")[0].checked;

        BOOK.RES_GUESTMETHOD = IS_WIRE?"WIRE":"CC";
        BOOK.PAYMENT = IS_WIRE ? {"CC_TYPE":""} : {
            "CC_TYPE": $("#CC_TYPE").val(),
            "CC_NUMBER": $("#CC_NUMBER").val(),
            "CC_NAME": $("#CC_NAME").val(),
            "CC_CODE": $("#CC_SECCODE").val(),
            "CC_EXP": $("#CC_EXP_MONTH").val()+"/"+$("#CC_EXP_YEAR").val(),
            "CC_BILL_EMAIL": BOOK.GUEST.EMAIL, 
            "CC_BILL_ADDRESS": USE_AS_BILLING ? BOOK.GUEST.ADDRESS : $("#BILL-GUEST_ADDRESS").val(),
            "CC_BILL_CITY": USE_AS_BILLING ? BOOK.GUEST.CITY : $("#BILL-GUEST_CITY").val(),
            "CC_BILL_STATE": USE_AS_BILLING ? BOOK.GUEST.STATE : $("#BILL-GUEST_STATE").val(),
            "CC_BILL_COUNTRY": USE_AS_BILLING ? BOOK.GUEST.COUNTRY : $("#BILL-GUEST_COUNTRY").val(),
            "CC_BILL_ZIPCODE": USE_AS_BILLING ? BOOK.GUEST.ZIPCODE : $("#BILL-GUEST_ZIPCODE").val()
        }

        BOOK.FORWHOM = {
            "RES_TO_WHOM": IS_TA?"TA":"GUEST",
            "RES_GUEST_ID": $("#GUEST_ID").val(),
            "RES_NEW_GUEST": $("#GUEST_ID").val()==0 ? "1" : "0",
            "RES_TA_ID": $("#TA_ID").val(),
            "RES_NEW_TA": 0
        }

        var HEAR_ABOUT_US = function() {
            var ret = $('input[name=HEAR_ABOUT_US]:checked').val(),
                ret_txt = $("#"+ret+"_txt textarea");
            return (ret_txt.length==1 && ret_txt.val().trim()!="")?ret+": "+ret_txt.val():ret;
        }

		BOOK.COMMENTS = nl2space($("#COMMENTS").val().replace(/\"/g, "").replace(/\'/g, ""));
        BOOK.HEAR_ABOUT_US = nl2space(HEAR_ABOUT_US().replace(/\"/g, "").replace(/\'/g, ""));

        BOOK.ARRIVAL_TIME = $("#RES_ARRIVAL_TIME").val();
        BOOK.ARRIVAL_AMPM = $("input[name='arrival_time']:checked").val();

        BOOK.AIRLINE = $("#RES_AIRLINE").val();
        BOOK.FLIGHT = $("#RES_FNUMBER").val();
        BOOK.ARRIVAL = $("#RES_ARRIVAL").val();
        BOOK.ARRIVAL_AP = $("input[name='RES_ARRIVAL_AP']:checked").val();

        BOOK.DEPARTURE_AIRLINE = $("#RES_DEPARTURE_AIRLINE").val();
        BOOK.DEPARTURE_FLIGHT = $("#RES_DEPARTURE_FLIGHT").val();
        BOOK.DEPARTURE = $("#RES_DEPARTURE").val();
        BOOK.DEPARTURE_AP = $("input[name='RES_DEPARTURE_AP']:checked").val();

        var RES_TRANSFER_TYPE = $("#ROUNDT")[0].checked?"ROUNDT":($("#ONEWAY")[0].checked?"ONEWAY":"");
        if (RES_TRANSFER_TYPE!="") {
            BOOK.TRANSFER_TYPE = RES_TRANSFER_TYPE;
            BOOK.TRANSFER_CAR = _book.transfers.carId;
            BOOK.TRANSFER_FEE = _book.transfers.carPrice;
        }

		BOOK.CURRENCY_CODE = $("#QUOTE").val();
		BOOK.CURRENCY_QUOTE = CURRENCY[BOOK.CURRENCY_CODE];

		//console.log(search_qry)

        var make =  $.post('make-booking.php', {
			QRYSTR: search_qry,
			BOOK: BOOK
		}, function(result) {

			if (typeof result.RES_NUMBER != "undefined") {
                //document.location.href = "confirmation.php?"+JSON.stringify(result);
                document.location.href = "confirmation.php";
			} else {
				alert(typeof result.error == "undefined" ? "Room is not longer available." : result.error.join(", "));
				$("#btn-book-now").removeClass("hidden");
				$("#loading-making-booking").hide();
			}

		}).fail(function(result) {
			alert("The transaction could not be completed. Please call +1 866 540 2585")
			//document.location.href = "confirmation.php";
		})

        //make.error(function() { alert("Error submitting the reservation"); })

    } else {
        alert("The following fields are missing or require attention:\r\n\r\n"+errTEXT.join("\r\n"));
    }
}

function parseGetParams() {
	var $_GET = {};
	var __GET = $('#thedata').length==1 ? $('#thedata').val().split("&") : window.location.search.substring(1).split("&");
	for(var i=0; i<__GET.length; i++) {
		var getVar = __GET[i].split("=");
		$_GET[getVar[0]] = typeof(getVar[1])=="undefined" ? "" : getVar[1];
	}
	return $_GET;
}

function str_replace(search, replace, subject) {
	return subject ? subject.split(search).join(replace) : "";
}

function select_nav_step(stepNo) {
    $("#top-nav ul li").removeClass("selected");
    $("#top-nav ul li.step-"+stepNo).addClass("selected");
}

function pay_with_wire(ele) {
    if (ele[0].checked) {
        $(".CC_DETAILS").hide()
    } else {
        $(".CC_DETAILS").show()
    }
}

function nl2space(a) {
    for (var b = a.replace(/\n/g, " ").replace(/\r/g, " "), e = [], c = !1, d = 0, f = b.length; d < f; d++) {
        var i = b.charAt(d);
        if (c && i === c) b.charAt(d - 1) !== "\\" && (c = !1);
        else if (!c && (i === '"' || i === "'")) c = i;
        else if (!c && (i === " " || i === "\t")) i = " ";
        e.push(i)
    }
    return e.join("");
}


