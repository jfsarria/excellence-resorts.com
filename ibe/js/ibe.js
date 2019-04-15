/*
 * Revised: May 09, 2012
 *          Jan 31, 2017
 */

if (typeof(ibe)=="undefined") ibe = {};
if (typeof(ibe.page)=="undefined") ibe.page = {};
if (typeof(ibe.select)=="undefined") ibe.select = {};
if (typeof(ibe.ups)=="undefined") ibe.ups = {};
if (typeof(ibe.callcenter)=="undefined") ibe.callcenter = {};
if (typeof(ibe.callcenter.reserv)=="undefined") ibe.callcenter.reserv = {};
if (typeof(ibe.availability)=="undefined") ibe.availability = {};
if (typeof(ibe.reserv)=="undefined") ibe.reserv = {};
if (typeof(ibe.reserv.forWhom)=="undefined") ibe.reserv.forWhom = {};
if (typeof(ibe.reserv.rooms)=="undefined") ibe.reserv.rooms = {};
if (typeof(ibe.inventory)=="undefined") ibe.inventory = {};
if (typeof(ibe.flashsale)=="undefined") ibe.flashsale = {};

var ng_config = {
    assests_dir: 'cal/assets/'	// the path to the assets directory for the calendar
}

$(document).ready(function() {
    ibe.select.property();
    ibe.page.selected();
    ibe.page.tooltip();
    ibe.page.summary();
});

ibe.init = function() {
    window.onresize = function(event) {ibe.page.frame();}
    ibe.page.frame();
    ibe.page.frms();
    ibe.ups.sortable();
    setTimeout("ibe.page.height();", 1500); 
    ibe.ctrls();
}
ibe.calendarClick = function(o) {
    var browser = $("body").attr("class").split(" ")[0];
    if (typeof(o)!="UNDEFINED") {

        if (browser=="Safari" || browser=="IE" || browser=="Chrome") if (typeof(o)!="undefined") o[0].click();;
        if (browser=="FF") if (typeof(o[0])!="undefined") {
            if (navigator.appVersion == "5.0 (Windows)") {
                o[0].click();
            } else {
                o.click();
            }
        }
    }
}
ibe.toString = function(str, pos) {
    return (typeof(str)=="string") ? str + (typeof(pos)!="undefined"?pos:"") : "";
}
ibe.page.frame = function() {
    ibe.page.width();
    ibe.page.height();
}
ibe.page.width = function() {
    var w = 1028, //$(window).width(),
        leftcol = $("#leftcol").width(),
        newwidth = w-leftcol-8;
    $("#wrapper").css({"width":w+"px"});
    $("#maincol").css({"width":newwidth+"px"});
}
ibe.page.height = function() {
    var hd = 86,
        wh = $(window).height() - hd,
        fh = ($("#maincol .editarea").length) ? $("#maincol .editarea").height() : 0,
        nh = (fh < wh) ? wh : fh + hd;
        $("#leftcol, #maincol, #loginWrap").css({"height":nh+"px"});
}
ibe.page.frms = function(ele) {
    ele = (typeof(ele)=="undefined") ? "" : ele+" ";
    $("#editfrm "+ele+"input[type='checkbox'], #editfrm "+ele+"input[type='radio']").click(function() {
        var ele = $(this);
        if (ele.attr("type")=="radio") $("#editfrm "+ele+"input[name='"+ele.attr("name")+"']").parent().removeClass("selectedItem");
        if (ele[0].checked) {
            ele.parent().addClass("selectedItem");
        } else {
            ele.parent().removeClass("selectedItem");
        }
    });
    $("#editfrm "+ele+"input:checked").parent().addClass("selectedItem");
}
ibe.page.selected = function() {
    $(".topnav span.button, .leftnav span.button").removeClass("selected");
    if (_PROP_ID == 0) $("span.button.callcenter").addClass("selected")
    if (_PAGE_CODE != "") $("a."+_PAGE_CODE+" span.button").addClass("selected")
}

ibe.page.summary_minY = 0;

ibe.page.summary = function() {
    var reserv_summary = $("#reserv_summary");
    if (reserv_summary.length != 0) {
        reserv_summary.css({"position":"absolute","width":"260px"});
        ibe.page.summary_minY = parseInt(reserv_summary.offset().top,10);
        $(window).scroll(function () { 
            if ($(document).scrollTop() > ibe.page.summary_minY) {
                offset = 0 + $(document).scrollTop();
            } else offset = ibe.page.summary_minY;
            var diff = $(window).height() - reserv_summary.height();
            if (diff < 0) offset += diff-20;
            reserv_summary.animate({top:offset+"px"},{duration:500,queue:false});
        });
    }
}
ibe.page.calculateGrossRackRate = function() {
    var rate = parseFloat($("#RATE_PER_RP").val(),10),
        override = parseFloat($("#MARKUP").val(),10),
        year = parseFloat($("#MARKUP_YEAR").val(),10);

    if (isNaN(rate)) rate = 0;
    if (isNaN(override)) override = 0;
    if (isNaN(year)) year = 0;
    //alert(rate+"\n"+override+"\n"+year)

    var markup = (override!=0) ? override : year,
        percentage = rate * (markup / 100),
        gross = Math.round(rate + percentage);
    //alert(markup+"\n"+percentage+"\n"+gross)

    $("#GrossRackRate").html("$" + gross);
}

ibe.select.property = function() {
    $(".topnav select[name='PROP_ID']").change(function() {
        var o = $(this),
            a = new Array();
        a.push("PROP_ID="+o.val());
        //if ((parseInt(o.val(),10)!=0) && typeof(_PAGE_CODE)!="undefined") a.push("PAGE_CODE="+_PAGE_CODE);
        document.location.href = "index.php?"+a.join("&");
    }).find("option").each(function() {
        
    });
}

ibe.addZeroToDate = function(num) {
    return (num<10) ? "0"+num : ""+num;
}

ibe.select.addDaysToDate = function(strDate, days) {
    strDate = (typeof(strDate) == "undefined") ? new Date() : new Date(strDate);
    var myDATE = new Date(strDate.getFullYear(), strDate.getMonth(), strDate.getDate()+days+1);
    return myDATE.getFullYear() + "-" + ibe.addZeroToDate(myDATE.getMonth()+1) + "-" + ibe.addZeroToDate(myDATE.getDate());
}

ibe.select.addMonthsToDate = function(strDate, months) {
    strDate = (typeof(strDate) == "undefined") ? new Date() : new Date(strDate);
    var myDATE = new Date(strDate.getFullYear(), strDate.getMonth()+months, strDate.getDate());
    return myDATE.getFullYear() + "-" + ibe.addZeroToDate(myDATE.getMonth()+1) + "-" + ibe.addZeroToDate(myDATE.getDate());
}

ibe.ups.addField = function(clsName) {
    var o = null,
        cnt = 0;
    $("."+clsName).each(function() {
        o = $(this);
        o.find("input").attr("name",clsName+"_"+(++cnt));
    });
    if (o) {
        var clone = o.clone();
            clone.find("input").attr("name",clsName+"_"+(++cnt));
        o.parent().append("<div class=\"imgUploaded\">"+clone.html()+"</div>");
    }
}
ibe.ups.sortable = function() {
    $("a[rel^='prettyPhoto']").prettyPhoto();
    $(".sortable").sortable({
        update: function(event, ui) {
            var o = $(this),
                id = o.attr("id"),
                order = $("#"+id+"_ORDER"),
                arr = new Array();
            o.find("input.checkbox").each(function() { arr.push($(this).attr("value")); });
            order.val(arr.join(","));
        }
    });
    $(".cbdelete").click(function() {
        var rel = $(this).attr("rel"),
            cb = $("#cb_"+rel),
            dds = $("#dds_"+rel);
        if (cb[0].checked) {
            cb[0].checked = false;
            dds.removeClass("opaque");
        } else {
            cb[0].checked = true;
            dds.addClass("opaque");
        }
    });
}

ibe.ng_date = function(str) {
    var ret = str;
    try {
        var arr = str.split(" ")[0].split("-");
        if (arr.length==3) ret = parseInt(arr[1],10)+"_"+parseInt(arr[2],10)+"_"+parseInt(arr[0],10);
    } catch (e) { }
    return ret;
}

ibe.ng_cal_selected_date = function(arr, ele) {
    var arr = arr.split(",");
    for (var t=0; t < arr.length; ++t) {
        var d = ibe.ng_date(arr[t]);
        $(ele+" .ng_cal_date_"+d).addClass("ng_cal_selected_date_custom").removeAttr('rel');
    }
}

ibe.callcenter.reserv.resetForm = function() {
    $(".reserv_fieldset,#getAvailabilityBtn").remove();
}

ibe.callcenter.reserv.startNew = function(LAN) {
    ibe.callcenter.reserv.resetForm();
    $("#RES_LANGUAGE").val(LAN);
    $("#RES_IN_THE_FUTURE").val("0");
    $("#RES_DATE").val(_TODAY);
    $('#editfrm').submit();
}

ibe.callcenter.reserv.checkFuture = function(LAN) {
    ibe.callcenter.reserv.resetForm();
    $("#RES_LANGUAGE").val("EN");
    $("#RES_IN_THE_FUTURE").val("1");
    $('#editfrm').submit();
}

ibe.callcenter.reserv.showdescr = function(id) {
    $(".descr_prop").hide();
    $("#descr_prop_"+id).show();
}

ibe.calendarJump = function(which, strDate) {
    var oTbl = $("#objRES_CHECK_"+which+" table.ng_cal_cal_frame_table");
    if (oTbl.length) {
        var oID = oTbl.attr("id").replace("cal_frame_tableng_calendar_",""),
            oYear = $("#yr_sel_menung_calendar_"+oID),
            oMonth = $("#mn_sel_menung_calendar_"+oID),
            oDate =  new Date(strDate),
            sYear = oDate.getFullYear(),
            sMonth = oDate.getMonth();
        //console.log("oDate:"+oDate+"\nsYear:"+sYear+"\nsMonth:"+sMonth);
        oYear.val(sYear).change();
        oMonth.val(sMonth).change();
    }
}

ibe.callcenter.addDaysToCalendar = function(IN, OUT, NIGHTS) {
    var inDate = $("#"+IN).val(),
        outDate = $("#"+OUT).val(),
        nights = $("#"+NIGHTS).val(),
        newDate = ibe.select.addDaysToDate(inDate, parseInt(nights,10)),
        d = ibe.ng_date(newDate);
    //console.log("inDate:"+inDate+"\noutDate:"+outDate+"\nnights:"+nights+"\nnewDate:"+newDate);
    ibe.calendarJump("OUT", newDate);
    ibe.calendarClick($("#obj"+OUT+" .ng_cal_date_"+d));
}

ibe.callcenter.reviewNightsInput = function(IN, OUT, NIGHTS) {
    var nights = $("#"+NIGHTS);
    if (parseInt(nights.val(),10) < 1) {
        alert("Check out day has to be at least one day after check in");
        nights.val("1");
    }
}

ibe.callcenter.adjustNights = function(IN, OUT, NIGHTS) {
    var inDate = $("#"+IN),
        outDate = $("#"+OUT),
        nights = ibe.dateDiff(inDate.val(),outDate.val(),parseInt($("#"+NIGHTS).val(),10));
    if (nights < 1) {
        alert("Check out day has to be at least one day after check in");
        $("#"+NIGHTS).val("2");
        ibe.callcenter.addDaysToCalendar(IN, OUT, NIGHTS);
    } else {
        $("#"+NIGHTS).val(nights);
    }
}

ibe.dateDiff = function(start_date,end_date,d) {
    a = new Date(start_date);
    b = new Date(end_date);
    if (!isNaN(a) && !isNaN(b)) {
        c = b - a;
        d = c / (1000*60*60*24);
    }
    return d;
}    

ibe.callcenter.showRoomBoxes = function(QTY) {
    var ROOM_BOX_TPL_DEFAULT = $("#ROOM_BOX_TPL_0"),
        ROOM_BOX_TPL = $("#ROOM_BOX_TPL_"+oRoom.RES_PROP_ID),
        ROOM_BOXES = $("#ROOM_BOXES");

    if (ROOM_BOX_TPL.length==0) ROOM_BOX_TPL = ROOM_BOX_TPL_DEFAULT;

    ROOM_BOXES.html("");
    if (typeof(QTY) != "undefined") {
        oRoom.RES_ROOMS_QTY = QTY;
    }

    for (ind=1; ind <= oRoom.RES_ROOMS_QTY; ++ind) {
        var BOX = $(ibe.callcenter.roomBoxVars(ROOM_BOX_TPL.html(), ind));
        BOX.css("display","block");
        ROOM_BOXES.append(BOX);
    }
    ROOM_BOXES.append("<div style='clear:both'></div>");

    if (typeof(QTY) == "undefined") {
        ibe.callcenter.roomBoxesInit();
    }

    $("select.children_in_room").each(function() { ibe.callcenter.roomChildrenChange($(this),false) });
    $("select.children_in_room").change(function() { ibe.callcenter.roomChildrenChange($(this),true) });

    ibe.page.height();
}

ibe.callcenter.roomChildrenChange = function(obj, reset) {
    var room_num = obj.attr("room_num"),
        qty = obj.val();

    $(".RES_ROOM_"+room_num+"_CHILDREN_AGES").hide();

    if (reset) {
        $("#RES_ROOM_"+room_num+"_CHILDREN_AGES select").val("0");
    }
    for (c=1;c<=qty;++c) {
       $("#RES_ROOM_"+room_num+"_CHILDREN_AGES_"+c).show();
    }
}

ibe.callcenter.roomBoxesInit = function() {
    $("select.room_box_select").each(function() {
        var id = $(this).attr("id"),
            rel = parseInt($(this).attr("rel"),10);
        if (rel==0 && id.indexOf("%")==-1 && typeof(eval("oRoom."+id)) != "undefined") {
            val = eval("oRoom."+id);
            $(this).val(val);
            $(this).attr("rel","1");
        }
    });
}

ibe.callcenter.roomBoxVars = function(str, ind) {
    str = str.replace(/\%room_num\%/g,ind);
    return str;
}

ibe.select.getClassesByYear = function(YEAR, GEOS, SEASON, ROOM) {
    GEOS = GEOS || "";
    SEASON = SEASON || "";
    ROOM = ROOM || "";
    $.ajax({
        url: "index.php?PAGE_CODE=ajax.getClassesByYear&PROP_ID="+_PROP_ID+"&SPECIAL_ID="+_SPECIAL_ID+"&YEAR="+YEAR+"&GEOS="+GEOS+"&SEASON="+SEASON+"&ROOM="+ROOM,
        success: function(result) {
            var o = $("<div>"+result+"</div>"),
                YEAR = o.find("#wrapper").attr('year');
            $("#classes_"+YEAR).html(o);
            ibe.page.frms("#classes_"+YEAR);
            $("#classes_"+YEAR+" input[type='checkbox']").click(function() {
                ibe.select.checkClassesByYear($(this), YEAR)
            });
        }
    });
}
ibe.select.getClassesByYearDiscounts = function(YEAR, GEOS, SEASON, ROOM,linea) {

    GEOS = GEOS || "";
    SEASON = SEASON || "";
    ROOM = ROOM || "";
    $.ajax({
        url: "index.php?PAGE_CODE=ajax.getClassesByYear.discounts&ID_CAB="+linea+"&PROP_ID="+_PROP_ID+"&SPECIAL_ID="+_SPECIAL_ID+"&YEAR="+YEAR+"&GEOS="+GEOS+"&SEASON="+SEASON+"&ROOM="+ROOM,
        success: function(result) {
            var o = $("<div>"+result+"</div>"),
                YEAR = o.find("#wrapper").attr('year');
                
            $("#classes_"+YEAR).html(o);
            ibe.page.frms("#classes_"+YEAR);
            $("#classes_"+YEAR+" input[type='checkbox']").click(function() {
                ibe.select.checkClassesByYear($(this), YEAR)
            });
        }
    });
}

ibe.select.showClasses = function($this) {
    var isChecked = $this[0].checked,
        geosPickList = function() { 
            var array = new Array();
            $("#GeosPickList input[type='checkbox']").each(function() { if ($(this)[0].checked) array.push($(this).val()); });
            /*
            if (array.length==0) {
                array.push("US");
                $("#cb_US")[0].checked=true;
            }
            */
            return array.join(",");
        }
        YEAR = $this.val(),
        GEOS = geosPickList(),
        SEASON = $("#SeasonPickList").val(),
        ROOM = $("#RoomPickList").val(),
        list = $("#classList_"+YEAR),
        cls = $("#classes_"+YEAR);
    if (isChecked) {
        ibe.select.getClassesByYear(YEAR, GEOS, SEASON, ROOM);
        list.show();
    } else {
        $("#classes_"+YEAR+" input[type='checkbox']").each(function() {
            if ($this[0].checked) {
                $this[0].checked = false;
                $this.parent().removeClass("selectedItem");
            }
        });
        list.hide();
    }
}
ibe.select.showClassesDiscounts = function($this,$id_cab) {
  
    var isChecked = $this[0].checked,
        geosPickList = function() { 
            var array = new Array();
            $("#GeosPickList input[type='checkbox']").each(function() { if ($(this)[0].checked) array.push($(this).val()); });
          
            return array.join(",");
        }
      
        YEAR = $this.val(),
        GEOS = geosPickList(),
        SEASON = $("#SeasonPickList").val(),
        ROOM = $("#RoomPickList").val(),
        list = $("#classList_"+YEAR),
        cls = $("#classes_"+YEAR);

    if (isChecked) {

        ibe.select.getClassesByYearDiscounts(YEAR, GEOS, SEASON, ROOM,$id_cab);
        list.show();
    } else {
        $("#classes_"+YEAR+" input[type='checkbox']").each(function() {
            if ($this[0].checked) {
                $this[0].checked = false;
                $this.parent().removeClass("selectedItem");
            }
        });
        list.hide();
    }
}
ibe.select.controlSpecialFiltersDiscounts = function(idcab) {
    $("#YearsPickList input[type='checkbox']").click(function() {
        ibe.select.showClassesDiscounts($(this),idcab);
    });
    $("#GeosPickList input[type='checkbox']").click(function() {
        $("#YearsPickList input[type='checkbox']").each(function() {
            ibe.select.showClassesDiscounts($(this),idcab);
        });
    });
    $("#SeasonPickList,#RoomPickList").change(function() {
        $("#YearsPickList input[type='checkbox']").each(function() {
            ibe.select.showClassesDiscounts($(this),idcab);
        });
    });
}
ibe.select.controlSpecialFilters = function() {
    $("#YearsPickList input[type='checkbox']").click(function() {
        ibe.select.showClasses($(this));
    });
    $("#GeosPickList input[type='checkbox']").click(function() {
        $("#YearsPickList input[type='checkbox']").each(function() {
            ibe.select.showClasses($(this));
        });
    });
    $("#SeasonPickList,#RoomPickList").change(function() {
        $("#YearsPickList input[type='checkbox']").each(function() {
            ibe.select.showClasses($(this));
        });
    });
}

ibe.select.checkClassesByYear = function(o, YEAR) {
    var isChecked = o[0].checked,
        cb_Year = $("#cb_"+YEAR),
        isYearOn = $(cb_Year)[0].checked;
    if (isChecked) {
        if (!isYearOn) {
            $(cb_Year)[0].checked = true;
            $(cb_Year).parent().addClass("selectedItem");
        }
    } else {
        var cnt=0;
        $("#classes_"+YEAR+" input[type='checkbox']").each(function() {
            if ($(this)[0].checked) ++cnt;
        });
        if (cnt==0) {
            $(cb_Year)[0].checked = false;
            $(cb_Year).parent().removeClass("selectedItem");
        }
    }
}

ibe.select.setAllClassesByYear = function(YEAR, status) {
    $("#classes_"+YEAR+" input[type='checkbox']").each(function() {
        ibe.select.setCheckboxStatus($(this), status);
    });
    ibe.select.setCheckboxStatus($("#cb_"+YEAR), status);
}

ibe.select.setCheckboxStatus = function(o, status) {
    o[0].checked = status;
    if (status) {
        o.parent().addClass("selectedItem");
    } else {
        o.parent().removeClass("selectedItem");
    }
}


ibe.select.isGEO = function(o, ele) {
    if (o.checked) {
        $("#isGEOState").show();
    } else {
        $("#isGEOState").hide();
    }
}

ibe.select.checkCountries = function(GROUP, status) {
    $("#country_group_"+GROUP+" input[type='checkbox']").each(function() {
        ibe.select.setCheckboxStatus($(this), status);
    });
}

ibe.quote_Change = function(ele, val) {
	var val = val || $(ele).val(),
		conversion = typeof(CURRENCY[val])!="undefined" ? CURRENCY[val] : 1
	
	console.log(val);console.log(conversion);
	
	$("#conversion_code").text(val.replace("USD",""));
	$("#CURRENCY_CODE").val(val);
	$("#CURRENCY_QUOTE").val(conversion);

	$(".room_currency").each(function(){
		var usd = $(this).attr("data-usd"),
			rel = Math.round(usd * conversion);
		//console.log(rel);
		$(this).attr("rel",rel);
		$(this).html("$"+ibe.page.formatCurrency(rel));
	});

	$("tr.AVAILABLE_ROOM.selected").each(function(){
		//select_room($(this).attr("id"), true);	
		var id = $(this).attr("id").replace("room_id_",""),
			room = $(this).parents("table.avaResultsTbl").attr("id").replace("availableRooms_","");
		ibe.availability.selectRoom(room,id);
	});

}

ibe.availability.selectRoom = function(ROOM_NUM, ROOM_ID) {
    $("#availableRooms_"+ROOM_NUM+" tr").removeClass("selected");
    $("#availableRooms_"+ROOM_NUM+" #room_id_"+ROOM_ID).addClass("selected");

    var avg_final_selected = parseInt($("#availableRooms_"+ROOM_NUM+" #room_id_"+ROOM_ID+" div.AVG_FINAL").attr("rel"),10);
    $("#availableRooms_"+ROOM_NUM+" tr.AVAILABLE_ROOM").each(function () {
        var avg_final = parseInt($(this).find("div.AVG_FINAL").attr("rel"),10),
            diff = avg_final - avg_final_selected;
        $(this).find("div.AVG_FINAL_DIFF").html("$"+ibe.page.formatCurrency(diff));
    });

    ibe.availability.totalReservation();
}

ibe.availability.totalReservation = function() {
    var result = 0,
        ROOM_IDs = new Array();

    $("table.avaResultsTbl tr.AVAILABLE_ROOM.selected").each(function() {
        var ROOM_ID = $(this).attr("id").replace("room_id_",""),
            FINAL = $(this).find(".FINAL"),
            rel = parseInt(FINAL.attr("rel"),10);
        result += rel;
        ROOM_IDs.push(ROOM_ID);
    });

    if (result==0) {
        $(".TOTAL_RESERVATION_CELL").hide();
    } else {
        $(".TOTAL_RESERVATION_CELL").show();
    }

    resultCurrency = ibe.page.formatCurrency(result);
    $(".TOTAL_RESERVATION").each(function() {
        $(this).html("$"+resultCurrency);
    });

    $("#RES_ROOMS_SELECTED").val(ROOM_IDs.toString());
}

ibe.page.tooltip = function() {
    $('a[rel=tooltip]').click(function(e) {
        var tip = $("#"+$(this).attr('tootip')).html();
        $("div#tooltip").html(tip);
        ibe.page.tooltipMove(e);
    }).mousemove(function(e) {
        ibe.page.tooltipMove(e);
    }).mouseout(function() {
        $("div#tooltip").html("");
    });
}

ibe.page.tooltipMove = function(e) {
    var y = e.pageY+10,
        x = e.pageX,
        o = $('div#tooltip'),
        w = o.width(),
        x = (x+w > 1024) ? 1024-w : x;
    $('div#tooltip').css({'top':y,'left':x});
}

ibe.page.formatCurrency = function(num) {
    num = num.toString().replace(/\$|\,/g,'');
    if(isNaN(num))
    num = "0";
    sign = (num == (num = Math.abs(num)));
    num = Math.floor(num*100+0.50000000001);
    cents = num%100;
    num = Math.floor(num/100).toString();
    if(cents<10)
    cents = "0" + cents;
    for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
    num = num.substring(0,num.length-(4*i+3))+','+
    num.substring(num.length-(4*i+3));
    //return (((sign)?'':'-') + '$' + num + '.' + cents);
    return (((sign)?'':'-') + num);
}

ibe.page.setCardExp = function(ID) {
    /* MM/YY */
    var MM = $("#card-exp-MM"),
        YY = $("#card-exp-YY"),
        Exp = $("#"+ID);
    Exp.val(MM.val()+"/"+YY.val());
}

ibe.page.creditCardFix = function(ID) {
    var obj = $(ID),
        ccnum = obj.val().replace(/[^\d]+/g,"");
    obj.val(ccnum);
    return ccnum;
}

ibe.page.validateCC = function() {
    if ($("#RES_CC_NUMBER").length==1 && $("#RES_CC_NUMBER").val().length != 0) {
        ibe.page.setCardExp('RES_CC_EXP');
        var type = $("#RES_CC_TYPE").val(),
            ccnum = ibe.page.creditCardFix("#RES_CC_NUMBER");
            isValid = ibe.isValidCreditCard(type, ccnum);
        if (isValid) {
            return true;
        } else {
            alert("Please verify the Credit Card Number and Type");
            return false;
        }
    } else {
        return true;
    }
}
ibe.page.validateTransferFlightInfo = function() {
	var isOK = true;
		TRANSFER_TYPE = $("#ROUNDT").length==1 && $("#ROUNDT")[0].checked ? "ROUNDT" : "ONEWAY",
		TRANSFER_FEE = $("#TRANSFER_FEE");
	if (TRANSFER_FEE.length==1) {
		var RES_AIRLINE = $("#RES_GUEST_AIRLINE"),
			RES_FNUMBER = $("#RES_GUEST_FLIGHT"),
			RES_ARRIVAL = $("#RES_GUEST_ARRIVAL");
		if (TRANSFER_TYPE=="ONEWAY" || TRANSFER_TYPE=="ROUNDT") {
			if (RES_AIRLINE.val()=="") {
				alert("Please enter Arrival Airline");
				isOK = false;
			} else if (RES_FNUMBER.val()=="") {
				alert("Please enter Arrival Flight");
				isOK = false;
			} else if (RES_ARRIVAL.val()=="") {
				alert("Please enter Arrival Time");
				isOK = false;
			}
		}
		if (TRANSFER_TYPE=="ROUNDT" && isOK) {
			var RES_DEPARTURE_AIRLINE = $("#RES_GUEST_DEPARTURE_AIRLINE"),
				RES_DEPARTURE_FLIGHT = $("#RES_GUEST_DEPARTURE_FLIGHT"),
				RES_DEPARTURE = $("#RES_GUEST_DEPARTURE");
			if (RES_DEPARTURE_AIRLINE.val()=="") {
				alert("Please enter Departure Airline");
				isOK = false;
			} else if (RES_DEPARTURE_FLIGHT.val()=="") {
				alert("Please enter Departure Flight");
				isOK = false;
			} else if (RES_DEPARTURE.val()=="") {
				alert("Please enter Departure Time");
				isOK = false;
			}
		}
	}
	return isOK;
}
/*
ibe.reserv.forWhom.nextStep = function(FLAG) {
    var FLAG = FLAG || "";
    if (FLAG=="NEWGUEST") $("#RES_NEW_GUEST").val("1");
    if (FLAG=="OLDGUEST") $("#RES_NEW_GUEST").val("0");
    $("#reservForm").attr("action","?PAGE_CODE=reserv&PAGE_SECTION=rooms").submit();
}
*/
ibe.reserv.forWhom.nextStep = function() {
    $("#reservForm").attr("action","?PAGE_CODE=reserv&PAGE_SECTION=rooms").submit();
}
ibe.reserv.rooms.nextStep = function() {
    ibe.page.setCardExp('RES_CC_EXP');
    $("#reservForm").attr("action","?PAGE_CODE=reserv&PAGE_SECTION=make").submit();
}
ibe.reserv.rooms.sameBilling = function(isSame) {
    var res_billing_info = $("#res_billing_info"),
        res_fields = new Array('ADDRESS','CITY','COUNTRY','STATE','ZIPCODE');
    if (isSame) {
        var COUNTRY = $("#RES_GUEST_COUNTRY").val();
        $("#US_PAYMENT_STATES,#CA_PAYMENT_STATES,#MX_PAYMENT_STATES").hide();
        $("#RES_CC_BILL_STATE").show();
        for (t=0; t < res_fields.length; ++t) $("#RES_CC_BILL_"+res_fields[t]).val($("#RES_GUEST_"+res_fields[t]).val());
        if (COUNTRY=="US"||COUNTRY=="CA"||COUNTRY=="MX") {
            $("#"+COUNTRY+"_PAYMENT_STATES").val($("#"+COUNTRY+"_GUEST_STATES").val()).show();
            $("#RES_CC_BILL_STATE").hide();
        } 
        res_billing_info.hide();
    } else {
        res_billing_info.show();
    }
}
ibe.reserv.rooms.airportPickup = function(pickup, isEdit) {
	var isEdit = typeof(isEdit)!="undefined" ? isEdit : 1;
    if (pickup) {
        $(".airportPickup").show();
		$("#HOTEL_ARRIVAL_TBL table").hide();
		$("#RES_GUEST_ARRIVAL_TIME").attr("value","");
		if (isEdit==0) {
			$("#ROUNDT").click();
		}
    } else {
        $(".airportPickup").hide();
		$("#TRANSFER_CARS_LIST").html("");
		$("#HOTEL_ARRIVAL_TBL table").show();

		var TRANSFER_FIELDS = new Array('AIRLINE','FLIGHT','ARRIVAL','ARRIVAL_AP','DEPARTURE_AIRLINE','DEPARTURE_FLIGHT','DEPARTURE','DEPARTURE_AP','TRANSFER_TYPE','TRANSFER_CAR','TRANSFER_FEE');
		for (i=0;i < TRANSFER_FIELDS.length;++i) {
			var FIELD_NAME = TRANSFER_FIELDS[i],
				FIELD_SELECTOR = "input[type='text'][name='RES_GUEST_"+FIELD_NAME+"']",
				FIELD = $(FIELD_SELECTOR);
			//console.log(FIELD_NAME + ", " + FIELD_SELECTOR + ", " + FIELD.length);
			if (FIELD.length==1) FIELD.attr("value","");
		}

    }
}
ibe.reserv.rooms.addTranfers = function(tripType, propId, checkIn, people) {
	//alert(opc + ", " + checkIn + ", " + people);
	if (tripType=="ROUNDT")	{
		$("#DEPARTURE_INFO_TBL").show();
	} else {
		$("#DEPARTURE_INFO_TBL").hide();
	}
	if (tripType=="") {
		$("#TRANSFER_CARS_LIST").html("");
	} else {
		$.ajax({
			url: "/ibe/index.php?PAGE_CODE=ws.getTransferCars&CHECK_IN="+checkIn+"&PROP_ID="+propId+"&YEAR="+checkIn.substr(0,4)+"&PEOPLE="+people+"&TRIP="+tripType,
			success: function(cars) {
				var list = $("#TRANSFER_CARS_LIST")
					cnt = 0;
				list.html("<h3>Select a car</h3>");
				for (;cnt <cars.length; ++cnt) {
					var car = cars[cnt],
						html = "";
					if (cnt==0){
						html += "<input type='text' style='display:none' name='RES_GUEST_TRANSFER_FEE' id='TRANSFER_FEE' value='"+car.PRICE+"'>"
					}
					html += "<div class='car_item' id='carId_"+car.ID+"'>";
					html += "	<div class='car_name'><input class='car_checkbox' "+(cnt==0?"checked":"")+" type='radio' name='RES_GUEST_TRANSFER_CAR' value='"+car.ID+"' data-price='"+car.PRICE+"' onclick=\"ibe.reserv.rooms.transferCharge('"+car.ID+"')\">&nbsp;<span class='car_lbl'>"+car.NAME_EN+"</span></div>";
					html += "	<div>";
					html += "		<div class='car_descr'>"+car.DESCR_EN+"</div>";
					html += "		<div class='car_fee'>Transfer Fee<BR>"+(tripType=="ROUNDT"?"Round Trip":"One Way")+" <b>$"+ibe.page.formatCurrency(car.PRICE)+"</b></div>";
					html += "		<div style='clear:both'></div>";
					html += "	</div>";
					html += "</div>";
					list.append(html);
				}
				if (cnt==0) {
					list.append("<div style='font-size:16px'>No cars available for "+people+" people.</div>");
				} else {
					if (typeof(TRANSFER_CAR)!="undefined" && TRANSFER_CAR!=0 && !isNaN(TRANSFER_CAR)) {
						$("#carId_"+TRANSFER_CAR+" .car_checkbox").click();
						TRANSFER_CAR = 0;
					} else {
						$(".car_checkbox").first().click();
					}
				}
			}
		});
	}
}
ibe.reserv.rooms.transferCharge = function(carId) {
	var car = $("#carId_"+carId),
		carPrice = parseInt(car.find('.car_checkbox').attr("data-price"),10),
		carLabel = car.find('.car_lbl').html(),
		roomCharge = parseInt($("#summary_total_room_charge").attr("rel"),10),
		total_transfer_charge = $("#total_transfer_charge"),
		summary_transfer = $('#summary_transfer'),
		formattedCarPrice = ibe.page.formatCurrency(carPrice),
		totalCharge = ibe.page.formatCurrency(roomCharge+carPrice),
		conversion = parseFloat($("#total_conversion").attr("rel")),
		total_conversion = ibe.page.formatCurrency(Math.round((roomCharge+carPrice) * conversion));

	//console.log(roomCharge+carPrice);
	//console.log(conversion);

	$('#TRANSFER_FEE').val(carPrice);
	if (total_transfer_charge.length==1) total_transfer_charge.html(formattedCarPrice);

	if (summary_transfer.length==1) {
		var summary = "<br><b>Transfer</b><br>";
		summary += $("#ROUNDT")[0].checked ? "Round Trip" : ($("#ONEWAY")[0].checked ? "One Way" : "");
		summary += "<br>Selected Car: "+carLabel;
		summary += "<br>Transfer Fee: $"+formattedCarPrice;
		summary += "<br><br>Total Charge: $"+totalCharge
		summary_transfer.html(summary);

		$("#conv_total_is").html("$"+total_conversion);
	}

}
ibe.reserv.rooms.paymentMethod = function(box) {
    if (box.value=="CC") {
        $(".paymentMethod").show();
    } else {
        $(".paymentMethod").hide();
    }
}
ibe.reserv.rooms.sameContact = function(isSame, ROOM_KEY) {
    if (isSame) {
        var res_billing_info = $("#res_billing_info"),
            res_fields = new Array('TITLE','FIRSTNAME','LASTNAME');
        for (t=0; t < res_fields.length; ++t) {
            $("#RES_GUEST_ROOM_"+ROOM_KEY+"_"+res_fields[t]).val($("#RES_GUEST_"+res_fields[t]).val());
        }
    } 
}

ibe.reserv.forWhom.open = function(ID) {
    ibe.reserv.forWhom.resetAll();
    $("#callcenter_"+ID).show();
    $(document).scrollTop(0);
}
ibe.flashsale.open = function(ID) {
    ibe.reserv.forWhom.resetAll();
    $("#callcenter_"+ID).show();

}

ibe.reserv.forWhom.resetAll = function() {
    $(".RES_TO_WHOM").hide();
    $("#searchGuestResult","#searchTAResult").html("");
	$("#RES_GUEST_EMAIL").val("");
    $("#RES_GUEST_ID,#RES_NEW_GUEST,#RES_TA_ID,#RES_NEW_TA").val("0");
}

ibe.reserv.forWhom.Next_NewGuest = function() {
	$("#RES_GUEST_EMAIL").val($('#RES_VERIFY_GUEST_EMAIL').val());
    $("#RES_GUEST_ID,#RES_TA_ID,#RES_NEW_TA").val("0");
    $("#RES_NEW_GUEST").val("1");
    ibe.reserv.forWhom.nextStep();
}
ibe.reserv.forWhom.Next_ExistingGuest = function() {
    $("#RES_NEW_GUEST,#RES_TA_ID,#RES_NEW_TA").val("0");
    ibe.reserv.forWhom.nextStep();
}

ibe.reserv.forWhom.Next_ExistingTA = function() {
	$("#RES_GUEST_EMAIL").val("");
    $("#RES_GUEST_ID,#RES_NEW_TA").val("0");
    $("#RES_NEW_GUEST").val("1");
    ibe.reserv.forWhom.nextStep();
}
ibe.reserv.forWhom.Next_NewTA = function() {
	$("#RES_GUEST_EMAIL").val("");
    $("#RES_GUEST_ID,#RES_TA_ID").val("0");
    $("#RES_NEW_GUEST,#RES_NEW_TA").val("1");
    ibe.reserv.forWhom.nextStep();
}

ibe.reserv.forWhom.newTA = function(ID) {
    $("#searchTAResult").html("").hide();
    $("#searchTAResult,#m_reserv_forwhom_ta_next").hide();
    $('#callcenter_NEW_RES_TO_WHOM_TA').show();
    $("#RES_GUEST_ID,#RES_TA_ID").val("0");
    $("#RES_NEW_TA,#RES_NEW_GUEST").val("1");
}

ibe.is_valid_email = function(email) { return /^.+@.+\..+$/.test(email); }

ibe.reserv.searchContact = function(ele, field, who) {
    if (who=="searchTA") ibe.reserv.searchTA(ele, field, who);
    if (who=="searchGuest") ibe.reserv.searchGuest(ele, field, who);
	if (who=="verifyEmail") {
		if (ibe.is_valid_email($(ele).val())) {
			ibe.reserv.searchGuest(ele, field, who);
		} else {
			alert("Invalid email address");
		}
	}
}

ibe.reserv.searchGuest = function(ele, field, who) {
    var oEle = $(ele),
        value = oEle.val();
    $("#RES_GUEST_ID").val("0");
	$("#RES_GUEST_EMAIL").val("");
    $("#m_reserv_forwhom_guest_next,#continue_new_guest_btn,#continue_existing_guest_btn").hide();
    $("#"+who+"Result").html("<br><center>Searching...</center>").show();;
    $.ajax({
        url: "index.php?PAGE_CODE=ws.searchGuest&field="+field+"&value="+value+"&ContentType=json",
        success: function(result) {
            var holder = $("#"+who+"Result"),
                btnNext = who=="searchGuest"?$("#m_reserv_forwhom_guest_next"):$("#continue_existing_guest_btn"),
                guests = result.guests.guest,
                cnt = 0,
                table = who=="searchGuest"?"<br><div style='padding:5px'>Resutls by: <b>"+value+"</b></div>":"";
				table += "<table width='100%' id='searchGuestResultTbl' cellspacing='2' cellpadding='2'><tr>";
            holder.hide();;
            if (typeof(guests)!="undefined") {
                if (typeof(guests.length)=="undefined") guests = Array(guests);
                for (t=0; t < guests.length; ++t) {
                    var guest = guests[t];
                    if (typeof(guest)!="undefined") {
                        table += "<td valign='top' width='50%' class='td'><table><tr><td valign='top'><input id='select_guest_"+t+"' type='radio' name='select_guest' onclick=\"ibe.reserv.selectGuest('"+guest.ID+"')\"></td><td>"+ibe.toString(guest.LASTNAME,", ")+ibe.toString(guest.FIRSTNAME,"<br>")+ibe.toString(guest.ADDRESS,"<br>")+ibe.toString(guest.CITY,", ")+ibe.toString(guest.STATE," ")+ibe.toString(guest.ZIPCODE,"<br>")+ibe.toString(guest.PHONE,"<br>")+ibe.toString(guest.EMAIL,"<br>")+" <input type='hidden' id='couponrel' name='couponrel' value="+guest.ID+"></td></tr></table></td>";
                        ++cnt; if ((cnt % 2)==0) table += "</tr><tr>";
                    }
                }
            } else {
                table += who=="searchGuest"?"<td style='text-align:center'>Not found</td>":"<br>";
				$("#continue_new_guest_btn").show();
            }
            table += "</tr></table>";
            holder.html(table).show();
			if (guests.length==1) {
				holder.find("#select_guest_0").click();
			}

        }
    });
}
ibe.reserv.selectGuest = function(GUESS_ID) {
    $('#RES_GUEST_ID').val(GUESS_ID);
    $("#m_reserv_forwhom_guest_next,#continue_existing_guest_btn").show();
}


ibe.reserv.searchTA = function(ele, field) {
    var oEle = $(ele),
        value = oEle.val();
    $("#RES_TA_ID").val("0");
    $('#callcenter_NEW_RES_TO_WHOM_TA').hide();
    $("#m_reserv_forwhom_ta_next").hide();
    $("#searchTAResult").html("<br><center>Searching...</center>");
    $.ajax({
        url: "index.php?PAGE_CODE=ws.searchTA&field="+field+"&value="+value+"&ContentType=json",
        success: function(result) {
            var holder = $("#searchTAResult"),
                btnNext = $("#m_reserv_forwhom_ta_next"),
                agents = result.agents.agent,
                cnt = 0,
                table = "<br><div style='padding:5px'>Resutls by: <b>"+value+"</b></div><table width='100%' id='searchTAResultTbl' cellspacing='2' cellpadding='2'><tr>";
            holder.hide();;
            if (typeof(agents)!="undefined") {
                if (typeof(agents.length)=="undefined") agents = Array(agents);
                for (t=0; t < agents.length; ++t) {
                    var agent = agents[t];
                    if (typeof(agent)!="undefined") {
                        table += "<td valign='top' width='50%' class='td'><table><tr><td valign='top'><input type='radio' name='select_agent' onclick=\"ibe.reserv.selectTA('"+agent.ID+"')\"></td><td>"+ibe.toString(agent.LASTNAME,", ")+ibe.toString(agent.FIRSTNAME,"<br>")+ibe.toString(agent.AGENCY_NAME,"<br>")+"IATA: "+agent.IATA+"<br>"+ibe.toString(agent.AGENCY_ADDRESS,"<br>")+ibe.toString(agent.AGENCY_CITY,", ")+ibe.toString(agent.AGENCY_STATE," ")+ibe.toString(agent.AGENCY_ZIPCODE,"<br>")+ibe.toString(agent.AGENCY_PHONE,"<br>")+ibe.toString(agent.EMAIL,"<br>")+"</td></tr></table></td>";
                        ++cnt; if ((cnt % 2)==0) table += "</tr><tr>";
                    }
                }
            } else {
                table += "<td style='text-align:center'>Not found</td>";
            }
            table += "</tr></table>";
            holder.html(table).show();;
        }
    });
}
ibe.reserv.selectTA = function(TA_ID) {
    $('#RES_TA_ID').val(TA_ID);
    $("#m_reserv_forwhom_ta_next").show();
}

ibe.inventory.init = function() {
    $(".tdSold,.tdLeft").hover(
        function () { ibe.inventory.hover($(this),'hover'); }, 
        function () { ibe.inventory.hover($(this),'out'); }
    );
    $(".tdSold,.tdLeft").click(function (e) {
        var ROOM_ID = $(this).attr("rel"),
            RES_DATE = $(this).attr("class").replace(/[^\d]+/g,""),
            CODE = $(this).attr("code"),
            YEAR = $(this).attr("year"),
            TOP = e.pageY+30;
        $.ajax({
            url: "index.php?PAGE_CODE=ajax.roomAllotment&ROOM_ID="+ROOM_ID+"&RES_DATE="+RES_DATE+"&TOP="+TOP+"&CODE="+CODE+"&YEAR="+YEAR,
            success: function(result) {
                var y = $(result).find("#top").text();
                $("#inventoryEditBox").html(result).css({'top':y+'px'}).show();
            }
        });
    });
}
ibe.flashsale.close = function() {
    $("#inventoryEditBox").html("").hide();
}
ibe.flashsale.init = function() {
        
    $(".email").click(function (event) {

        var valor = $(event.target).val();
        var valor2 = event.target.id; //CAB
        var valor3 = event.target.name; //PROP-LINEA-CAB
        
        
        if(valor==""){
            $.ajax({
                url: "index.php?PAGE_CODE=ajax.emailcoupon&PARAM="+valor3+"&",
                success: function(result) {
                    var y = $(result).find("#top").text();
                    $("#inventoryEditBox").html(result).css({'top':y+'px'}).show();
                }
            });
        }
        
    });
    $(".geo").click(function (event) {
       var valor = $(event.target).val();
       var valor2 = $("#Infinito").attr('checked');       

        if(!valor2){
            $("#loading-making-booking").show();

            
            $.ajax({
                url: "index.php?PAGE_CODE=ajax.guestcoupon&GEO="+valor+"&",
                success: function(result) {
                    var y = $(result).find("#top").text();
                    $("#calculo").html(result).css({'top':y+'px'}).show();
                }
            });
            $("#loading-making-booking").hiden();
        }
            

    });
}

ibe.inventory.hover = function(cell, status) {
    var rel = cell.attr("rel"),
        date = cell.attr("class").replace(/[^\d]+/g,""),
        objs = $("#row"+rel+" ."+date);
    if (status=="hover") objs.addClass("cell_hover");
    if (status=="out") objs.removeClass("cell_hover");
}
ibe.inventory.close = function() {
    $("#inventoryEditBox").html("").hide();
}

ibe.callcenter.sendGuestPwd = function(EMAIL) {
    $.ajax({
        url: "index.php?PAGE_CODE=ws.sendGuestPwd&EMAIL="+EMAIL,
        success: function(result) {
            alert("Password has been sent to the Guest");
        }
    });
}

ibe.callcenter.sendTAPwd = function(EMAIL) {
    $.ajax({
        url: "index.php?PAGE_CODE=ws.sendTAPwd&EMAIL="+EMAIL,
        success: function(result) {
            alert("Password has been sent to the Travel Agent");
        }, 
    });
}

ibe.callcenter.sendConfirmation = function(RES_ID, CODE, YEAR) {
    $.ajax({
        url: "index.php?PAGE_CODE=ws.sendConfirmation&RES_ID="+RES_ID+"&CODE="+CODE+"&YEAR="+YEAR+"&RESENDING=1",
        success: function(result) {
            alert("Reservation confirmation has been sent");
        }
    });
}

ibe.ctrls = function() {
    if ($("#IS_ACTIVE").length!=0) {
        $("#IS_ARCHIVE, #IS_ACTIVE").click(function() { ibe.ctrl_active_inactive($(this)); });
        if ($(".m_specials").length==0) if (!$("#IS_ACTIVE")[0].checked && !$("#IS_ARCHIVE")[0].checked) $("#IS_ACTIVE")[0].checked=true;
    }
}

ibe.ctrl_active_inactive = function($this) {
    var ID = $this.attr("id");
    if (ID=="IS_ARCHIVE" && $("#IS_ACTIVE")[0].checked) $("#IS_ACTIVE")[0].checked=false;
    if (ID=="IS_ACTIVE" && $("#IS_ARCHIVE")[0].checked) $("#IS_ARCHIVE")[0].checked=false;
}

ibe.callcenter.emailPrePostStay = function() {
    $.ajax({
        url: "index.php?PAGE_CODE=ws.emailPrePostStay",
        success: function(result) {
        }
    });
}


ibe.callcenter.changePage = function(pageNo) {
    ibe.callcenter.setQryStr('pageNo', pageNo);
}

ibe.callcenter.setOrderBy = function(SORTBY) {
    ibe.callcenter.setQryStr('sortby', SORTBY);
}

ibe.callcenter.setQryStr = function(varName, varValue, remove) {
    var oField = $("#"+varName);
    if (oField.length==1 && $('#editfrm').length==1) {
        oField.val(varValue);
        if (varName == "sortby" && oField.val() != "" && oField.val().indexOf(varValue) == 0) {
            oField.val((oField.val() == varValue) ? varValue + " DESC" : varValue);
        }
        $('#ACTION').val('SUBMIT');
        $('#editfrm').submit();
    } else {
        if (typeof(remove) == "undefined") var remove = {};
        var param = new Object();
        var qrystr = location.search.substr(1).split("&");
        for (var t=0; t < qrystr.length; ++t) {
            var arr = qrystr[t].split("=");
            try { param[arr[0]] = arr[1] } catch (e) { }
        }
        //alert("1: "+param[varName]);
        if (varName == "sortby" && typeof(param[varName]) != "undefined" && param[varName].indexOf(varValue) == 0) {
            param[varName] = (param[varName] == varValue) ? varValue + "%20DESC" : varValue;
        } else {
            param[varName] = varValue;
        }
        //alert("2: "+param[varName]);
        var arr = new Array();
        for (var key in param) {
            if (varName != "delete" && key == "delete") key = "";
            if (key != "" && param[key] != "" && typeof(remove[key]) == "undefined") arr.push(key + "=" + param[key]);
        }
        document.location.href = "?" + arr.join("&");
    }
}

ibe.setActive = function(TABLE, ACTIVE, ID) {
    $.ajax({
        url: "index.php?PAGE_CODE=ajax.setActive&TABLE="+TABLE+"&ACTIVE="+ACTIVE+"&ID="+ID,
        success: function(result) {
            var ret = result.split(",")
            //alert(ret[0] + "," + ret[1])
            //alert(document.location.href)
            document.location.href = document.location.href;
        }
    });
}

ibe.isValidCreditCard = function(type, ccnum) {
   if (type == "Visa") {
      // Visa: length 16, prefix 4, dashes optional.
      var re = /^4\d{3}-?\d{4}-?\d{4}-?\d{4}$/;
   } else if (type == "MasterCard") {
      // Mastercard: length 16, prefix 51-55, dashes optional.
      var re = /^5[1-5]\d{2}-?\d{4}-?\d{4}-?\d{4}$/;
   } else if (type == "Disc") {
      // Discover: length 16, prefix 6011, dashes optional.
      var re = /^6011-?\d{4}-?\d{4}-?\d{4}$/;
   } else if (type == "AmEx") {
      // American Express: length 15, prefix 34 or 37.
      var re = /^3[4,7]\d{13}$/;
   } else if (type == "Diners") {
      // Diners: length 14, prefix 30, 36, or 38.
      var re = /^3[0,6,8]\d{12}$/;
   }
   if (!re.test(ccnum)) return false;
   // Remove all dashes for the checksum checks to eliminate negative numbers
   ccnum = ccnum.split("-").join("");
   // Checksum ("Mod 10")
   // Add even digits in even length strings or odd digits in odd length strings.
   var checksum = 0;
   for (var i=(2-(ccnum.length % 2)); i<=ccnum.length; i+=2) {
      checksum += parseInt(ccnum.charAt(i-1));
   }
   // Analyze odd digits in even length strings or even digits in odd length strings.
   for (var i=(ccnum.length % 2) + 1; i<ccnum.length; i+=2) {
      var digit = parseInt(ccnum.charAt(i-1)) * 2;
      if (digit < 10) { checksum += digit; } else { checksum += (digit-9); }
   }
   if ((checksum % 10) == 0) return true; else return false;
}

var qty = 0;

ibe.updateMetaIO = function(PROP_ID, COUNTRY, ADULTS_QTY, MONTH, START) {
    var PROP_ID = PROP_ID || 1,
		MONTH = MONTH || 1,
		COUNTRY = COUNTRY || 1,
		ADULTS_QTY = ADULTS_QTY || 1,
        START = START || _TODAY,
        STOP = ibe.select.addMonthsToDate(START, 1);

	var adults = 0;

    if (MONTH==1 && COUNTRY==1 && ADULTS_QTY==1) {
        jQuery("#metaIO_msg").html("<div>Please wait until the Meta IO seaches and files are all updated (108 Searches) <br><br></div>")
    }
	
	jQuery("#metaIO_msg").append("<div id='meta_"+PROP_ID+"_"+COUNTRY+"_"+ADULTS_QTY+"_"+MONTH+"'>"+(++qty)+" :: Property "+PROP_ID+", Country "+COUNTRY+", Adults "+ADULTS_QTY+", Month "+MONTH+", from "+START+", to "+STOP+" <span>............</span></div>");

	$.ajax({
		url: "/ibe/meta_io/generate.php?PROP_ID="+PROP_ID+"&COUNTRY="+COUNTRY+"&ADULTS_QTY="+ADULTS_QTY+"&MONTH="+MONTH+"&START="+START+"&STOP="+STOP+"&RETURN=1",
		success: function(obj) {
			var COUNTRY = parseInt(obj.COUNTRY,10),
				MONTH = parseInt(obj.MONTH,10),
				ADULTS_QTY = parseInt(obj.ADULTS_QTY, 10);

			jQuery("#meta_"+obj.PROP_ID+"_"+COUNTRY+"_"+ADULTS_QTY+"_"+MONTH+" span").html("Updated");

			if ((COUNTRY==3 && ADULTS_QTY==3 && MONTH==12) || qty==(2)) { //qty==(3*3*12)
				jQuery("#metaIO_msg").append("<div><br><b>All files have been updated.</b></div>");
				qty = 0;
			} else {
				//ADULTS_QTY = ADULTS_QTY==3 ? 1 : ++ADULTS_QTY;
				if (COUNTRY==3 && ADULTS_QTY==3) {
					START = ibe.select.addDaysToDate(STOP, MONTH==11?2:1);
					++MONTH;
					COUNTRY=1;
					ADULTS_QTY=1;
				} else if (COUNTRY!=3 && ADULTS_QTY==3) {
					ADULTS_QTY=1;
					++COUNTRY;
				} else {
					++ADULTS_QTY;
				}

				ibe.updateMetaIO(PROP_ID, COUNTRY, ADULTS_QTY, MONTH, START);
			}
		}
	});

}
