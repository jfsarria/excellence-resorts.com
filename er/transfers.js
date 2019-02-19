/*
 * Revised: Nov 13, 2014
 *          Feb 07, 2015
 *          Nov 01, 2015
 *          Jan 07, 2019 Fontanar
 */

var transferTripLbl = "",
	urlParam =  typeof(urlParam)=="undefined" ? (typeof(parseGetParams)!="undefined"?parseGetParams():null) : null,
	HAD_TRANSFER = false,
	RES_LANGUAGE = "EN";//urlParam!=null ? urlParam.RES_LANGUAGE : (typeof(RES_LANGUAGE)!="undefined"?RES_LANGUAGE:"EN");

function setUpTransfer(isModify, transferSettings) {
	if (urlParam!=null && typeof(urlParam.RES_PROP_ID)=="undefined") urlParam.RES_PROP_ID = $("#RES_PROP_ID").val();

	var isModify = isModify || false,
		transferSettings = transferSettings || false,
        RES_LANGUAGE = data.RES_LANGUAGE,
		OVERVIEW_TXT = isModify ? "" : transferSettings[urlParam.RES_PROP_ID]['OVERVIEW_'+RES_LANGUAGE],
		IS_TRANSFER_ACTIVE = isModify ? data.IS_TRANSFER_ACTIVE : (transferSettings[urlParam.RES_PROP_ID].IS_ACTIVE==1 ? true : false ),
		HAS_ARRIVAL_DATA = isModify && typeof(data)!="undefined" && (data.RESERVATION.AIRLINE!="" || data.RESERVATION.FLIGHT!=""),
		HAS_TRANSFER_FIELDS = isModify && typeof(data)!="undefined" && typeof(data.RESERVATION.TRANSFER_CAR)!="undefined" && typeof(data.RESERVATION.TRANSFER_FEE)!="undefined",
		HAS_TRANSFER_DATA = HAS_TRANSFER_FIELDS && (data.RESERVATION.TRANSFER_CAR!="" || data.RESERVATION.TRANSFER_FEE!=""),
		PICKUP_DAYS = 2,
		//TRANSFER_DAYS = 4 + (isModify?1:0),
        TRANSFER_DAYS = isModify ? 31 : 5;
		DAYS_LEFT = isModify ? data.DAYS_LEFT : dateDiff(data.RES_CHECK_IN);
    /*
	try {
		console.log("isModify: " + (isModify?"YES":"NO"));
		console.log("IS_TRANSFER_ACTIVE: " + IS_TRANSFER_ACTIVE);
		console.log("HAS_ARRIVAL_DATA: " + (HAS_ARRIVAL_DATA?"YES":"NO"));
		console.log("HAS_TRANSFER_DATA: " + (HAS_TRANSFER_DATA?"YES":"NO"));
		console.log("HAS_TRANSFER_FIELDS: " + (HAS_TRANSFER_FIELDS?"YES":"NO"));
        console.log("RES_CHECK_IN: " + data.RES_CHECK_IN);
		console.log("DAYS_LEFT: " + DAYS_LEFT);
        console.log("PICKUP_DAYS: " + PICKUP_DAYS);
		console.log("typeof(data): " + typeof(data));
		console.log("typeof(data.RESERVATION.TRANSFER_CAR): " + typeof(data.RESERVATION.TRANSFER_CAR));
		console.log("typeof(data.RESERVATION.TRANSFER_FEE): " + typeof(data.RESERVATION.TRANSFER_FEE));
		console.log("typeof(data.RESERVATION.ARRIVAL): " + typeof(data.RESERVATION.ARRIVAL));
	} catch (err){}
    */
	HAD_TRANSFER = isModify && HAS_TRANSFER_DATA;

	if (isModify) {
		clearAllTransferFrm();
		
		try {
			$("input[name=RES_GUEST_ARRIVAL_TIME]").val(data.RESERVATION.ARRIVAL_TIME);
			$("input[name=RES_GUEST_ARRIVAL_AMPM]:eq("+(data.RESERVATION.ARRIVAL_AMPM=="PM"?"1":"0")+")").attr("checked","true");

			$("input[name=RES_GUEST_AIRLINE]").val(data.RESERVATION.AIRLINE);
			$("input[name=RES_GUEST_FLIGHT]").val(data.RESERVATION.FLIGHT);
			$("input[name=RES_GUEST_ARRIVAL]").val(data.RESERVATION.ARRIVAL);
			if (data.RESERVATION.ARRIVAL_AP=="PM") $("#RES_A_PM").attr("checked","true");

			$("input[name=RES_GUEST_DEPARTURE_AIRLINE]").val(data.RESERVATION.DEPARTURE_AIRLINE);
			$("input[name=RES_GUEST_DEPARTURE_FLIGHT]").val(data.RESERVATION.DEPARTURE_FLIGHT);
			$("input[name=RES_GUEST_DEPARTURE]").val(data.RESERVATION.DEPARTURE);
			if (data.RESERVATION.DEPARTURE_AP=="PM") $("#RES_D_PM").attr("checked","true");

			$("#HAD_TRANSFER_DATA").val(HAS_TRANSFER_DATA?"1":"0");
		} catch (err){}
	} else {
		// New Reservation
	}

	if (DAYS_LEFT >= PICKUP_DAYS) {
		if (DAYS_LEFT >= TRANSFER_DAYS && IS_TRANSFER_ACTIVE) {
			//console.log(" A ")
			$('#transfer_box').show();
			$('.add_pickup').hide();
			$('.add_transfer').show();
			if (isModify) {
				if (HAS_TRANSFER_DATA) {
					//console.log("#addtransfer ON ")
					$("#addtransfer").attr("checked","checked");
					$('.transfer_field').css('display','block');
					$(".add_transfer.btn").html(RES_LANGUAGE=="EN"?"CANCEL PRIVATE TRANSFER":"CANCELAR TRANSPORTE");
					$('#'+( typeof(data.RESERVATION.TRANSFER_TYPE)!="undefined"&&data.RESERVATION.TRANSFER_TYPE!=""?data.RESERVATION.TRANSFER_TYPE:"ONWAY" )).click();
				} else {
					//console.log("#addtransfer OFF ")
					$("#addtransfer").removeAttr("checked");
					$(".add_transfer.btn").html(RES_LANGUAGE=="EN"?"ADD PRIVATE TRANSFER":"ADICIONAR TRANSPORTE");
				}
			} else {
				getTransferOverviewCars(OVERVIEW_TXT);
			}
		} else {
			//console.log(" B ")
			$('#transfer_box').hide();
			$('.add_pickup').show();
			$('.add_transfer').hide();
			if (isModify) {
				if (HAS_ARRIVAL_DATA) {
					//console.log("#airportPickup ON ")
					$("#airportPickup").attr("checked","checked");
					$("#airportpickup_open").show();
				} else {
					//console.log("#airportPickup OFF ")
					$("#airportPickup").removeAttr("checked");
				}
				if (HAS_TRANSFER_DATA) {
					$(".add_pickup").hide();
					if (data.RESERVATION.TRANSFER_TYPE=="ROUNDT") $("#DEPARTURE_INFO_TBL").show();
				}
			}
		}
        $('#addtransfer').change(function(){
			addTransferClicked($(this).attr('checked'), isModify);
			var btn = $(".add_transfer.btn");
			if ($(this)[0].checked) {
				btn.html("CANCEL PRIVATE TRANSFER");
			} else {
				btn.html("ADD PRIVATE TRANSFER");
			}
		});
		$(".add_transfer.btn").unbind("click").click(function(){
			$('#addtransfer').click();
		})
	} else {
		//console.log(" C ")
		clearAllTransferFrm();
	}
}

function addTransferClicked(checked, isModify) {
	if (checked) {
		setCancelation(false);
		$('.transfer_field').css('display','block');
		$('#ROUNDT').click();
	} else {
		setCancelation(true);
		doNotAddTransfer(isModify);
	}
}

function doNotAddTransfer(isModify) {
	$(".overview_radio").removeClass("selected");
	$('.transfer_field').css('display','none');
	var TRANSFER_FIELDS = new Array('RES_DEPARTURE_AIRLINE','RES_DEPARTURE_FLIGHT','RES_DEPARTURE','TRANSFER_CAR','TRANSFER_FEE','RES_AIRLINE','RES_FNUMBER','RES_ARRIVAL');
	for (i=0;i < TRANSFER_FIELDS.length;++i) {
		var FIELD = $("#"+TRANSFER_FIELDS[i]);
		//console.log(TRANSFER_FIELDS[i] + " = " + FIELD.length)
		FIELD.attr("value","");
	}
	$('#summary_transfer').hide().html("");
	$('#ROUNDT')[0].checked=false;
	$('#ONEWAY')[0].checked=false;
	$("#TRANSFER_CARS_LIST").html((!isModify || !HAD_TRANSFER)?"":"<input style='display:none' name='RES_GUEST_TRANSFER_FEE'><input style='display:none' name='RES_GUEST_TRANSFER_CAR'>");
	if (!isModify)	{
		$("#transfer_make").hide();
		$("#transfer_overview").show();
		$('#total2').html($('#dt_total').html()); 
        transferChange(0,0);
	}
}

function setCancelation(isModify) {
	$("#ALREADY_CHARGED").attr("name",isModify?"ALREADY_CHARGED":"");
	if (isModify) {
		$("#TRANSFER_CANCELLED")[0].checked = parseInt($("#HAD_TRANSFER_DATA").val(),10)==1 ? true : false;
	}
}

function clearAllTransferFrm() {
	$("#summary_transfer").html("").hide();
	$("#TRANSFER_CARS_LIST").html("");
	$('#transfer_box,.transfer_field,.add_pickup,.add_transfer,#add_transfer_h2_lbl').hide();
	$("#transfer_make").parent().hide();
}

function getTransferOverviewCars(OVERVIEW_TXT) {
	OVERVIEW_TXT = OVERVIEW_TXT.replace(/\n/g,"<br>").replace(/\r\n/g,"<br>");
	$("#transfer_make").hide();
	$(".transfer_overview_txt").html(OVERVIEW_TXT);
	getTransferCars("ROUNDT", true);
	$("#transfer_overview").show();
}

function getTransferCars(tripType, isOverview) {
	//alert(tripType + ", " + data.RES_PROP_ID + ", " + data.RES_CHECK_IN + ", " + data.RES_ROOMS_ADULTS_QTY + ", " + data.RES_ROOMS_CHILDREN_QTY)
	//console.log("Getting cars")
	var isOverview = isOverview || false;
	var people = data.RES_ROOMS_ADULTS_QTY + data.RES_ROOMS_CHILDREN_QTY;
	if (!isOverview) {
		if (tripType=="ROUNDT")	{
			$("#DEPARTURE_INFO_TBL").show();
		} else {
			$("#DEPARTURE_INFO_TBL").hide();
			$("#RES_DEPARTURE_AIRLINE,#RES_DEPARTURE_FLIGHT,#RES_DEPARTURE").attr("value","")
		}
	}
	$.ajax({
		url: "/ibe/index.php?PAGE_CODE=ws.getTransferCars&CHECK_IN="+data.RES_CHECK_IN+"&PROP_ID="+data.RES_PROP_ID+"&YEAR="+data.RES_CHECK_IN.substr(0,4)+"&PEOPLE="+people+"&TRIP="+tripType,
		success: function(cars) {
			if (isOverview) {
				displayOverviewTransferCars(cars);
			} else {
				displayTransferCars(tripType, cars);
			}
		}
	});
}

function displayOverviewTransferCars(cars) {
	urlParam = parseGetParams();
	RES_LANGUAGE = data.RES_LANGUAGE;

	var list = $("#OVERVIEW_CARS_LIST"),
		cnt = 0,
		firstPrice = 0;
	list.html("");
	for (;cnt <cars.length; ++cnt) {
		var car = cars[cnt],
			descr = car['DESCR_'+RES_LANGUAGE].replace(/\n/g,"<br>").replace(/\r\n/g,"<br>"),
			html = "";
		html += "<div class='car_item line ec cnt_"+(cnt % 2)+"'>";
		html += "	<div class='car_hdr name'><div class='nm'>"+car['NAME_'+RES_LANGUAGE]+"</div></div>";
		if (typeof(car.IMAGES[0])!="undefined") {
			html += "<div class='car_img'><a href='javascript:void(0)'><img src='//"+car.IMAGES[0]+"'></a></div>";
		}
		html += "	<div>";
        html += "		<div class='overview_car' style='float:left'><table border='0' cellpadding='0' cellspacing='0'><tr><td><input type='radio' name='overview_car' style='margin:3px 10px 0 0'></td><td class=''>SELECT</td></tr></table></div>";
		html += "		<div class='overview_radio' data-carid='"+car.ID+"'></div>";
		html += "		<div class='overview_lbl' style='float:right'>&nbsp;"+(RES_LANGUAGE=="EN"?"Round Trip transfer":"Ida y vuelta al aeropuerto")+"</div>";
		html += "		<div class='overview_price' style='float:right'>$"+car.PRICE+"</div>";
		html += "	</div>";
		html += "</div>";
		list.append(html);
	}
	list.append("<div style='clear:both'></div>");

    list.find(".car_img a").click(function(){
        $(this).parents('.car_item').first().find(".overview_radio").click();
	})

	list.find(".overview_radio").click(function(){
		$(this).addClass("selected");
		$("#transfer_overview").hide();
		$("#transfer_make").show();
		$("#addtransfer").attr("checked","checked");
		addTransferClicked(true, false);
	})
}

function select_car(ele) {
    // USED TO USE IMAGE FOR THIS. THAT'S WHY ALL THIS CRAPPY CODE JUST TO DON'T RECODE THE WHOLE THING
    var isCar = ele.parents(".car_item");
    $('.radio').removeClass('radio_on');
    ele.children('.radio').addClass('radio_on');
    if (isCar.length == 1) {
        isCar.find(".car_checkbox").click();
        settransfer(isCar);
    }
    return false;
}

function displayTransferCars(tripType, cars) {
	var list = $("#TRANSFER_CARS_LIST"),
		cnt = 0,
		firstPrice = 0;
	transferTripLbl = "<div class='softner'>"+(RES_LANGUAGE=="EN"?"Transfer Fee":"Costo de transportación")+"/"+(tripType=="ROUNDT"?(RES_LANGUAGE=="EN"?"Round Trip":"viaje redondo"):(RES_LANGUAGE=="EN"?"One Way":"de ida"))+"</div>";
	list.html("");
	for (;cnt <cars.length; ++cnt) {
		var car = cars[cnt],
			descr = car['DESCR_'+RES_LANGUAGE].replace(/\n/g,"<br>").replace(/\r\n/g,"<br>");
			html = "";
		if (cnt==0){
			html += "<input type='text' style='display:none' id='TRANSFER_FEE' name='RES_GUEST_TRANSFER_FEE' value='"+car.PRICE+"'>"
			html += "<input type='text' style='display:none' id='TRANSFER_CAR' name='RES_GUEST_TRANSFER_CAR' value='"+car.ID+"'>"
		}
		html += "<div class='car_item line ec' id='carId_"+car.ID+"' data-price='"+car.PRICE+"'>";
		html += "	<div class='car_hdr name'><div class='nm'>"+car['NAME_'+RES_LANGUAGE]+"</div></div>";
		html += "	<div class='car_trip section4 goodprice'></div>";
		//html += "	<div class='selector' style='float:right;'><div value='20' class='radio'></div></div>";
        html += "	<div class='car_select nowrap'><span><input type='radio' class='radio' onclick='select_car($(this))' name='car_select'></span><span class='car_price goodprice'></span></div>";
		//html += "	<div class='car_price section4 goodprice' style='clear:both'></div>";
		html += "	<div style='clear:both'><div style='display:none'><input name='TRANSFER_CAR_CHKBX' class='car_checkbox' "+(cnt==0?"checked":"")+" type='radio' value='"+car.ID+"' onclick=\"transferChange(this.value,'"+car.PRICE+"')\"></div></div>";
		html += "	<div style='margin-top:20px'>";
		if (typeof(car.IMAGES[0])!="undefined") {
			html += "		<div class='car_img'><img src='//"+car.IMAGES[0]+"'></div>";
		}
		html += "		<div class='car_descr'>"+descr+"</div>";
		html += "		<div style='clear:both'>";
		html += "	</div>";
		html += "</div>";
		list.append(html);
	}
    /*
	$('#TRANSFER_CARS_LIST .car_item .selector').unbind('click').click(function () { 
		var isCar = $(this).parents(".car_item");
		$('.radio').removeClass('radio_on');
		$(this).children('.radio').addClass('radio_on');
		if (isCar.length == 1) {
			isCar.find(".car_checkbox").click();
			settransfer(isCar);
		}
		return false;
	});
    */
	if (cnt==0) {
		list.append("<div style='font-size:16px'>No cars available for "+people+" people.</div>");
	} else {
		if (typeof(data)!="undefined"&&typeof(data.RESERVATION)!="undefined"&&typeof(data.RESERVATION.TRANSFER_CAR)!="undefined"&&data.RESERVATION.TRANSFER_CAR!="") {
			TRANSFER_CAR = data.RESERVATION.TRANSFER_CAR;
		} 
		$(".overview_radio.selected").each(function(){
			TRANSFER_CAR = parseInt($(this).attr("data-carid"),10);
		})

		if (typeof(TRANSFER_CAR)!="undefined" && TRANSFER_CAR!=0 && !isNaN(TRANSFER_CAR)) {
			var car_item = $("#carId_"+TRANSFER_CAR);
			car_item.find(".car_checkbox").click();
			settransfer(car_item);
			TRANSFER_CAR = 0;
		} else {
			$(".car_checkbox").first().click();
			settransfer($("#TRANSFER_CARS_LIST .car_item").first());
		}
	}
}

function transferChange(carId, carPrice) {
	var total_transfer_charge = $("#total_transfer_charge");
	$('#TRANSFER_FEE').val(carPrice);
	$('#TRANSFER_CAR').val(carId);
	if (total_transfer_charge.length==1) total_transfer_charge.html(number_format(carPrice));

	var summary_transfer = $('#summary_transfer');
		
	if (summary_transfer.length==1) {
		var car = $("#carId_"+carId),
			carLabel = car.find('.car_hdr .nm').html(),
			roomCharge = TOTAL_COST;//$('#dt_total').length==1 ? $('#dt_total').html().replace(/[A-Za-z\$\-\,\.]/g, "") : data.RESERVATION.RES_TOTAL_CHARGE,
			formattedCarPrice = number_format(carPrice),
			totalCharge = parseInt(roomCharge,10)+parseInt(carPrice,10);
			formattedTotalCharge = number_format(totalCharge),
			summary = "<p><b>"+(RES_LANGUAGE=="EN"?"Transfer":"Transportación")+"</b><br>";
		summary += $("#ROUNDT")[0].checked ? (RES_LANGUAGE=="EN"?"Round Trip":"Viaje Redondo") : ($("#ONEWAY")[0].checked ? (RES_LANGUAGE=="EN"?"One Way":"de ida") : "");
		summary += "<br>"+(RES_LANGUAGE=="EN"?"Selected Car":"Carro seleccionado")+": "+carLabel;
		summary += "<br>"+(RES_LANGUAGE=="EN"?"Transfer Fee":"Costo de transportación")+": $"+formattedCarPrice + "<span class='hidden' id='total_transfer_charge'>"+carPrice+"</span>";
		summary += "<br><br><div class='rules_conditions'><div class='modify'><a href='javascript:void(0)' onclick=\"popover_open($(this),'popover_transfer_rules')\">"+(RES_LANGUAGE=="EN"?"Cancellation and Modification Policy":"Política de Cancelación y Modificación")+"</a></div></div>";
		summary += "<br><span class='total'>"+(RES_LANGUAGE=="EN"?"TOTAL CHARGE":"CARGO TOTAL")+" (USD): <span class='total_charge_usd' rel='"+totalCharge+"'>$"+formattedTotalCharge+"</span></span></p><br>";

        if (parseInt(carPrice,10)==0) {
            summary_transfer.hide();
        } else {
    		summary_transfer.show().html("").append(summary);
        }

		if ($('#total2').length==1) $('#total2').html("$"+formattedTotalCharge); 

        _book.transfers = {
            'carId':carId,
            'carPrice':parseInt(carPrice,10),
            'carType':$("#ROUNDT")[0].checked?"ROUNDT":($("#ONEWAY")[0].checked?"ONEWAY":"")
        }
        
        $("#total_cc_charge_is").html("$"+formattedTotalCharge);

		transferPolicyPopUp(data);
	}

	if (typeof(update_conversion)!="undefined")	{
		update_conversion($("#QUOTE").val(),currency_symbol($("#QUOTE").val()) + "&nbsp;");
	}
}

function transferPolicyPopUp(data) {
	// TRANSFER POLICY
	$('#popover_trans_terms .text').html("<p>"+data.RES_ITEMS.TRANSFER_RULES+"</p>");

	$('.rules_conditions .modify').click(function (e) {	
		var $popover_trans_terms = $('#popover_trans_terms');

		var cord = $(this).offset(),
			top = cord.top - ($popover_trans_terms.height()-1),
			left = cord.left-128;
		
		$popover_trans_terms.css({'left':left+'px','top':top+'px','display':'block'});
		return false;
	})
}

function dateDiff(strDate) {
	var date = new Date(),
		YYYY = date.getFullYear(),
		MM = date.getMonth()+1,
		DD = date.getDate(),
		today = YYYY + "-" + (MM<10?"0"+MM:MM) + "-" + (DD<10?"0"+DD:DD),
		start = new Date(today),
		end = new Date(strDate),
		diff = new Date(end - start),
		days = diff/1000/60/60/24;
	//alert(today + ", " + strDate + ", " + days)
	return days;
}
function settransfer(car_item) {
	//$('#TRANSFER_CARS_LIST .selector .radio').removeClass('radio_on');
	//car_item.find('.selector .radio').addClass('radio_on');
	//car_item.find('.car_checkbox').click();
    car_item.find(".car_select .radio")[0].checked=true;

	$('#TRANSFER_CARS_LIST .car_item').removeClass('selected');
	car_item.addClass('selected');

	var PRICE = parseInt(car_item.attr("data-price"),10)
	car_item.find(".car_trip").html(transferTripLbl);
	car_item.find(".car_price").html("$"+number_format(PRICE));
	$('#TRANSFER_CARS_LIST .car_item').each(function(){
		if (!$(this).hasClass("selected")) {
			var car_price = parseInt($(this).attr("data-price"),10);
				diff = car_price - PRICE;
			/* THIS MAKES THE +/-
			$(this).find(".car_trip").html((diff<0?"-":"+")+"&nbsp;&nbsp;");
			$(this).find(".car_price").html("$"+number_format(Math.abs(diff)));
			*/
			$(this).find(".car_trip").html("&nbsp;&nbsp;");
			$(this).find(".car_price").html("$"+number_format(car_price));
		}
	})
}
function validateTransferForm() {
	var TRANSFER_TYPE = $("input[name=RES_TRANSFER_TYPE]:checked").val(),
		TRANSFER_FEE = $("#TRANSFER_FEE"),
		TRANSFER_CAR = $("#TRANSFER_CAR");

	if (TRANSFER_FEE.length==1 && TRANSFER_CAR.length==1) {
		var RES_AIRLINE = $("#RES_AIRLINE"),
			RES_FNUMBER = $("#RES_FNUMBER"),
			RES_ARRIVAL = $("#RES_ARRIVAL");
		if (TRANSFER_TYPE=="ONEWAY" || TRANSFER_TYPE=="ROUNDT") {
			if (RES_AIRLINE.val()=="") errTEXT.push("Please enter Arrival Airline");
            if (RES_FNUMBER.val()=="") errTEXT.push("Please enter Arrival Flight");
            if (RES_ARRIVAL.val()=="") errTEXT.push("Please enter Arrival Time");
		}
		if (TRANSFER_TYPE=="ROUNDT") {
			var RES_DEPARTURE_AIRLINE = $("#RES_DEPARTURE_AIRLINE"),
				RES_DEPARTURE_FLIGHT = $("#RES_DEPARTURE_FLIGHT"),
				RES_DEPARTURE = $("#RES_DEPARTURE");
			if (RES_DEPARTURE_AIRLINE.val()=="") errTEXT.push("Please enter Departure Airline");
            if (RES_DEPARTURE_FLIGHT.val()=="") errTEXT.push("Please enter Departure Flight");
            if (RES_DEPARTURE.val()=="") errTEXT.push("Please enter Departure Time");
		}
	} 

	return errTEXT.length==0?true:false;
}