$.mobile.page.prototype.options.domCache = false;

$("body").live('pageinit', function(event) {
    //ibemobile.initGallery();
});

ibemobile = new Object();;
ibemobile.availability = new Object();
ibemobile.availability.reservation = new Object();
ibemobile.reservation = new Object();
ibemobile.reservation.login = new Object();
ibemobile.validate = new Object();

ibemobile.initGallery = function(TARGET) {
    $(TARGET+" a").photoSwipe({ enableMouseWheel: false , enableKeyboard: false });
}

ibemobile.dialog = function(TARGET, fullHTML) {
    $(document).delegate(TARGET, 'click', function() {
        $(this).simpledialog({
            'mode' : 'blank',
            'prompt': false,
            'forceInput': false,
            'useModal':true,
            'fullHTML' : fullHTML
        })
    });
}

ibemobile.availability.roomSelector = function() {
    var RES_ROOMS_QTY = $("#RES_ROOMS_QTY").val();
    $("#room_qtys div.room_box, #room_qtys div.room_box_children_age").hide();
    for (var RNUM=1; RNUM<=RES_ROOMS_QTY; ++RNUM) {
        if (RNUM <= RES_ROOMS_QTY) {
            //console.log("RNUM: "+RNUM);
            $("#room_box_"+RNUM).show();
            var cQTY = $("#RES_ROOM_"+RNUM+"_CHILDREN_QTY").val();
            //console.log(cQTY)
            for (var CNUM=1; CNUM<=cQTY; ++CNUM) {
                //console.log("#room_box_"+RNUM+"_children_"+CNUM+"_age")
                $("#room_box_"+RNUM+"_children_"+CNUM+"_age").show();
            }
        } else {
            $("#room_box_"+RNUM).hide();
        }
    }
}


ibemobile.autoSelect = function(OBJ, VAL) {
    OBJ.val(VAL);
    OBJ.parent().find("span.ui-btn-text").html(OBJ.find("option[value='"+VAL+"']").text());
}

ibemobile.availability.roomAdultsSelector = function(RNUM) {
    var ADULTS = $("#RES_ROOM_"+RNUM+"_ADULTS_QTY"),
        ADULTS_QTY = parseInt(ADULTS.val(),10),
        CHILDREN = $("#RES_ROOM_"+RNUM+"_CHILDREN_QTY"),
        CHILDREN_QTY = parseInt(CHILDREN.val(),10),
        GUESTS = ADULTS_QTY + CHILDREN_QTY
    //console.log(RNUM + ", " + ADULTS_QTY + ", " + CHILDREN_QTY)
    //console.log(GUESTS);
    if (GUESTS > 6) {
        ibemobile.autoSelect(CHILDREN, 6-ADULTS_QTY);
        ibemobile.availability.roomChildrenSelector(RNUM);
    }
}

ibemobile.availability.roomChildrenSelector = function(RNUM) {
    var ROOM_CHILDREN_QTY = $("#RES_ROOM_"+RNUM+"_CHILDREN_QTY").val();
    //console.log("RES_ROOM_"+RNUM+"_CHILDREN_QTY" + "\n"+ROOM_CHILDREN_QTY)
    for (var CNUM=1; CNUM<=5; ++CNUM) {
        //console.log("#room_box_"+RNUM+"_children_"+CNUM+"_age")
        for (var CNUM=1; CNUM<=5; ++CNUM) {
            //console.log("#room_box_"+RNUM+"_children_"+CNUM+"_age")
            if (CNUM<=ROOM_CHILDREN_QTY) {
                $("#room_box_"+RNUM+"_children_"+CNUM+"_age").show();
            } else {
                $("#room_box_"+RNUM+"_children_"+CNUM+"_age").hide();
            }
        }
    }
    ibemobile.availability.roomAdultsSelector(RNUM);
}

ibemobile.availability.submit = function() {
    $("#RES_CHECK_IN").val( $("#check-in-year").val() + "-" + $("#check-in-month").val() + "-" + $("#check-in-day").val() );
    $("#RES_CHECK_OUT").val( $("#check-out-year").val() + "-" + $("#check-out-month").val() + "-" + $("#check-out-day").val() );
    /*
    var RES_CHECK_IN =  $("#check-in-year").val() + "-" + $("#check-in-month").val() + "-" + $("#check-in-day").val(),
        RES_CHECK_OUT = $("#check-out-year").val() + "-" + $("#check-out-month").val() + "-" + $("#check-out-day").val();
    */
    var getDate = function(str) {
            var yearfield=str.split("-")[0];
            var monthfield=str.split("-")[1];
            var dayfield=str.split("-")[2];
            var dayobj = new Date(yearfield, monthfield-1, dayfield);
            return ((dayobj.getMonth()+1!=monthfield)||(dayobj.getDate()!=dayfield)||(dayobj.getFullYear()!=yearfield)) ? false : dayobj;
        },
        today = new Date(new Date().getFullYear(), new Date().getMonth(), new Date().getDate()),
        error = "", checkInDate=false, checkOutDate=false;

    checkInDate = getDate($("#RES_CHECK_IN").val()),
    checkOutDate = getDate($("#RES_CHECK_OUT").val());

    if (!checkInDate) {
        error = "Invalid Check In date";
    } else if (!checkOutDate) {
        error = "Invalid Check Out date";
    } else if (checkInDate < today) {
        error = "Check in date already past.";
    } else if (checkOutDate <= checkInDate) {
        error = "Please make sure Check out date is after Check in date"
    }

    if (error!="") {
        alert(error);
        return false;
    } else {
        $("#RES_NIGHTS").val((checkOutDate-checkInDate) / 86400000);
        return true;
    }
}

ibemobile.reservation.login.submit = function() {
    var LOGIN_EMAIL = $("#LOGIN_EMAIL").val(),
        LOGIN_PWD = $("#LOGIN_PWD").val();
    document.location.href = "/mobile/availability.php?GET_INFO=1&LOGIN_EMAIL="+LOGIN_EMAIL+"&LOGIN_PWD="+LOGIN_PWD;
}

ibemobile.reservation.logout = function() {
    document.location.href = "/mobile/availability.php?GET_INFO=1&LOGOUT=1";
}

ibemobile.validate.isEmail = function (email) { 
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

ibemobile.validate.creditCardFix = function(ID) {
    var obj = $(ID),
        ccnum = obj.val().replace(/[^\d]+/g,"");
    obj.val(ccnum);
    return ccnum;
}

ibemobile.validate.isCreditCardNumber = function() {
    var type = $("#CC_TYPE").val(),
        ccnum = ibemobile.validate.creditCardFix("#CC_NUMBER");
        isValid = isValidCreditCard(type, ccnum);
    return isValid;
}

ibemobile.validate.reservation = function() {
    var err = "";
    if (err=="" && ($.trim($("#EMAIL").val())=="" || !ibemobile.validate.isEmail($("#EMAIL").val()))) {
        err += "Please enter a valid email address"+"\n";
        $("#EMAIL")[0].focus();
    }
    if (err=="" && $.trim($("#FIRSTNAME").val())=="") {
        err += "Please enter guest first name"+"\n";
        $("#FIRSTNAME")[0].focus();
    }
    if (err=="" && $.trim($("#LASTNAME").val())=="") {
        err += "Please enter guest last name"+"\n";
        $("#LASTNAME")[0].focus();
    }
    if (err=="" && $.trim($("#PHONE").val())=="") {
        err += "Please enter guest phone number"+"\n";
        $("#PHONE")[0].focus();
    }
    if (err=="" && $.trim($("#ADDRESS").val())=="") {
        err += "Please enter guest address"+"\n";
        $("#ADDRESS")[0].focus();
    }
    if (err=="" && $.trim($("#RES_GUEST_STATE").val())=="") {
        err += "Please enter guest state"+"\n";
        $("#RES_GUEST_STATE")[0].focus();
    }
    if (err=="" && $.trim($("#CITY").val())=="") {
        err += "Please enter guest city"+"\n";
        $("#CITY")[0].focus();
    }
    if (err=="" && $.trim($("#ZIPCODE").val())=="") {
        err += "Please enter guest zipcode"+"\n";
        $("#ZIPCODE")[0].focus();
    }

    if (err=="" && ($.trim($("#CC_NUMBER").val())=="" || !ibemobile.validate.isCreditCardNumber())) {
        err += "Please enter a valid credit card number"+"\n";
        $("#CC_NUMBER")[0].focus();
    }
    if (err=="" && $.trim($("#CC_NAME").val())=="") {
        err += "Please enter name on credit card"+"\n";
        $("#CC_NAME")[0].focus();
    }
    if (err=="" && $.trim($("#CC_CODE").val())=="") {
        err += "Please enter credit card security code"+"\n";
        $("#CC_CODE")[0].focus();
    }
    if (err=="" && $.trim($("#CC_CODE").val())=="") {
        err += "Please enter credit card security code"+"\n";
        $("#CC_CODE")[0].focus();
    }
    $("#CC_EXP").val($("#CC_EXP-month").val() + "/" + $("#CC_EXP-year").val().substr(2,2));

    if ($.trim($("#CC_BILL_ADDRESS").val())=="") $("#CC_BILL_ADDRESS").val($("#ADDRESS").val());
    if ($.trim($("#RES_CC_BILL_COUNTRY").val())=="") $("#RES_CC_BILL_COUNTRY").val($("#RES_GUEST_COUNTRY").val());
    if ($.trim($("#RES_CC_BILL_STATE").val())=="") $("#RES_CC_BILL_STATE").val($("#RES_GUEST_STATE").val());
    if ($.trim($("#CC_BILL_CITY").val())=="") $("#CC_BILL_CITY").val($("#CITY").val());
    if ($.trim($("#CC_BILL_ZIPCODE").val())=="") $("#CC_BILL_ZIPCODE").val($("#ZIPCODE").val());

    if (err=="" && !$("#checkbox-agree")[0].checked) {
        err += "Please read and agree to the Excellence Resorts Terms and Conditions"+"\n";
        $("#checkbox-agree")[0].focus();
    }


    if (err!="") {
        alert(err)
        return false;
    } else {
        return true;
    }

    //return false;
    //return true;
}

isValidCreditCard = function(type, ccnum) {
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
   //alert(re.test(ccnum))
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


startSlide = function(ITEM_ID) {
	var slider = $('#slider-'+ITEM_ID);

	if (!slider.hasClass("rendered") && slider.find("img").length > 1)	{
		slider.addClass("rendered");
		slider.leanSlider({
			directionNav: '#slider-direction-nav-'+ITEM_ID,
			controlNav: '#slider-control-nav-'+ITEM_ID
		});
	}
}
