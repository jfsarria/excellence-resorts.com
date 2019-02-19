var $=jQuery;

$(document).ready(function(){
	if (navigator.appVersion.indexOf("Mac")!=-1 && $("html").hasClass("desktop")) {
        $('html').addClass('macos');
    };
    //$.nonbounce();
	scrollhead();
    bullet();
	order_popup();
	calendar();
	order_selector();
	reset_form();
	dropdown();
	subscribe_placeholder();
    subscribe_form();
	checkbox_logic();
	//order_width();
    burger();
    mobilenav();
    
	$('.cn-links a span').each(function () {
		//this.innerHTML = this.innerHTML.replace( /^(.+?\s)/, '<span>$1</span>' );
	});
	
	$(window).resize(function(){
		//order_width();
        //console.log($(window).width())
	});
	
});

var order_width = function(){
	if($(window).width()<1200 && $(window).width()>1024 && $("body").hasClass("scrollhead")) {
		var ww = $(window).width();
		$("#top").css({'width':ww+'px'});
	} else if ($(window).width()<=1024 && $("body").hasClass("scrollhead")) {
		$("#top").css({'width':'1024px'});
	} else {
		$("#top").removeAttr("style");
	};
}

var dropdown = function(){
	/*$("#nav li").hover(function(){
		$(this).find("ul").stop().slideToggle(300);
	}, function(){
		$(this).find("ul").stop().slideToggle(300);
	});*/
    	$("#nav li.expanded").hoverIntent({
    		over: makeTall,
    		out: makeShort
    	});
        hide_dropdown();    
}


var mobilenav = function(){
        $("#mobile-nav li.expanded span.bullet").click(function(){
            if($(this).hasClass("bullet-cur")){
                $(this).parent().find("ul").slideUp(200);
                $(this).html("+").removeClass("bullet-cur");
            } else {
                $(this).parent().find("ul").slideDown(200);
                $(this).html("-").addClass("bullet-cur");
            };
        });
}

function makeTall(){$(this).find("ul").fadeIn(300);}
function makeShort(){$(this).find("ul").fadeOut(100);}

var hide_dropdown = function(){
    $(document).click( function(event){
      if( $(event.target).closest("#nav ul li.expanded").length ) 
        return;
      $("#nav ul li.expanded ul").hide();
      event.stopPropagation();
    });
}




var scrollhead = function(){
    $(window).scroll(function () {
        if ($(this).scrollTop() > 0) {
			$('body').addClass("scrollhead");
			//$("#order").slideUp(200, function(){$("body").removeClass("popup");});
        } else {
            $('body').removeClass("scrollhead");
            if($('html').hasClass('tablet') && $('html').hasClass('portrait')){
                close_all_menu();
            }
			//$("#top").css({'top':'0px', 'position':'fixed'});
        };
    });
}


var lift = function() {
	var doubleUp = 0;
	var doubleDown = 0;
	var tempScrollTop = 0;
	var currentScrollTop = 0;
	//console.log(doubleUp+'up');
	//console.log(doubleDown+'down');
    if($(window).width()>=767) {
    $(window).scroll(function () {
        if ($('body').hasClass("popup")) {
			currentScrollTop = $(window).scrollTop();
			if (tempScrollTop > currentScrollTop ) {
			   doubleDown = 0;
				if(doubleUp >= 4){
					doubleUp = 0;
					//console.log(doubleUp+'up');
					close_popup();
                    hide_dropdown();
					return false;
				} else {
					doubleUp ++;
					//console.log(doubleUp+'up');
				};
			} else if (tempScrollTop < currentScrollTop){
				doubleUp = 0;
				if(doubleDown >= 4){
					doubleDown = 0;
					//console.log(doubleDown+'down');
					close_popup();
                    hide_dropdown();
					return false;
				} else {
					doubleDown ++;
					//console.log(doubleDown+'down');
				}
			};
			tempScrollTop = currentScrollTop;
        } else {
			doubleUp = 0;
			doubleDown = 0;
            return false;
        };
    });
    }
}

var close_popup = function() {
	$("body").removeClass("popup");
	$("#order").slideUp(100, function(){
		reset_form();
	});
}

var reset_form = function(){
	$("#reset-form").trigger("click");
	$('#order select').each(function(){
		var cont = $(this).find('option:selected').val();
		$(this).closest(".select-wrapper").find(".customSelectInner").text(cont);
	});
	$("#order form .room, .group-children").removeAttr("style");
	//document.getElementById("frmAvailability").reset();
}

var order_popup = function() {
	$(".book-now").unbind("click").click(function(){
		if($("body").hasClass("popup")) {
			$("#order").slideUp(200, function(){$("body").removeClass("popup"); reset_form();});
		} else {
			$("#order").slideDown(300, function(){
			     var screenHeight = $(window).height();
                 $("#order").css({'max-height':screenHeight+'px'});
			     $("body").addClass("popup");
                 lift();
                 space_click();
            });	
		};
        close_mobilemenu();
	});
}

var space_click = function(){
	$("#container").unbind("click").click( function(){
		if($('body').hasClass('popup')) {
			$("#order").slideUp(200, function(){$("body").removeClass("popup");});
		};
	});
}


var close_all_menu = function(){
    close_mobilemenu();
}

var close_tablet_menu = function(){
	$(".tablet-menu-mask").unbind("click").click( function(){
		if($('body').hasClass('burger-open')) {
            close_all_menu();
		};
	});
    
    $(window).scroll(function(){close_all_menu();});
}

var dropdown_hide_tablet = function(){
	$("#container").unbind("click").click( function(){
		if($('body').hasClass('burger-open')) {
            close_all_menu();
		};
	});
}


var order_selector = function(){
	$('#order .form-item-select-group select').customSelect();
	$('#order .form-item-select-group select').change(function(){
		value = $( this ).val();
		 res = value.replace(/D/g, '');
		if(res>=10) {
			$(this).closest(".select-wrapper").addClass("smaller");
		} else {
			$(this).closest(".select-wrapper").removeClass("smaller");
		};
	});
	
	/*Room count
	---------------------------------------------------------*/
		$('#room-1-count').change(function(){
			value = $( this ).val();
			if(value==3) {
				$('#room-2').slideDown(200).find('#room-2-children-count').attr('disabled',false);
				$('#room-3').slideDown(200).find('#room-3-children-count').attr('disabled',false);
                $('#room-1 .room-lbl').removeClass('hide');
			} else if (value==2) {
				$('#room-2').slideDown(200).find('#room-2-children-count').attr('disabled',false);
				$('#room-3').slideUp(200).find('#room-2-children-count').attr('disabled',true);
                $('#room-1 .room-lbl').removeClass('hide');
			} else {
				$('#room-2, #room-3').slideUp(200).find('#room-3-children-count').attr('disabled',true);
                $('#room-1 .room-lbl').removeClass('hide').addClass('hide');
			};
	});
	/*Room 1 children count
	---------------------------------------------------------*/
	$('#room-1-children-count').change(function(){
	   $('#order .form-item-select-group select').trigger("render.customSelect");
		value = $( this ).val();
		room = $("#room-1");
		if(value==3) {
			room.find("#room-1-child-1").show().find('select').attr('disabled',false);
			room.find("#room-1-child-2").show().find('select').attr('disabled',false);
			room.find("#room-1-child-3").show().find('select').attr('disabled',false);
		} else if (value==2) {
			room.find("#room-1-child-1").show().find('select').attr('disabled',false);
			room.find("#room-1-child-2").show().find('select').attr('disabled',false);
			room.find("#room-1-child-3").hide().find('select').attr('disabled',true);
		} else if (value==1) {
			room.find("#room-1-child-1").show().find('select').attr('disabled',false);
			room.find("#room-1-child-2").hide().find('select').attr('disabled',true);
			room.find("#room-1-child-3").hide().find('select').attr('disabled',true);
		} else {
			room.find("#room-1-child-1").hide().find('select').attr('disabled',true);
			room.find("#room-1-child-2").hide().find('select').attr('disabled',true);
			room.find("#room-1-child-3").hide().find('select').attr('disabled',true);
		};
	});
	/*Room 2 children count
	---------------------------------------------------------*/
	$('#room-2-children-count').change(function(){
	   $('#order .form-item-select-group select').trigger("render.customSelect");
		value = $( this ).val();
		room = $("#room-2");
		if(value==3) {
			room.find("#room-2-child-1").show().find('select').attr('disabled',false);
			room.find("#room-2-child-2").show().find('select').attr('disabled',false);
			room.find("#room-2-child-3").show().find('select').attr('disabled',false);
		} else if (value==2) {
			room.find("#room-2-child-1").show().find('select').attr('disabled',false);
			room.find("#room-2-child-2").show().find('select').attr('disabled',false);
			room.find("#room-2-child-3").hide().find('select').attr('disabled',true);
		} else if (value==1) {
			room.find("#room-2-child-1").show().find('select').attr('disabled',false);
			room.find("#room-2-child-2").hide().find('select').attr('disabled',true);
			room.find("#room-2-child-3").hide().find('select').attr('disabled',true);
		} else {
			room.find("#room-2-child-1").hide().find('select').attr('disabled',true);
			room.find("#room-2-child-2").hide().find('select').attr('disabled',true);
			room.find("#room-2-child-3").hide().find('select').attr('disabled',true);
		};
	});
	/*Room 3 children count
	---------------------------------------------------------*/
	$('#room-3-children-count').change(function(){
	   $('#order .form-item-select-group select').trigger("render.customSelect");
		value = $( this ).val();
		room = $("#room-3");
		if(value==3) {
			room.find("#room-3-child-1").show().find('select').attr('disabled',false);
			room.find("#room-3-child-2").show().find('select').attr('disabled',false);
			room.find("#room-3-child-3").show().find('select').attr('disabled',false);
		} else if (value==2) {
			room.find("#room-3-child-1").show().find('select').attr('disabled',false);
			room.find("#room-3-child-2").show().find('select').attr('disabled',false);
			room.find("#room-3-child-3").hide().find('select').attr('disabled',true);
		} else if (value==1) {
			room.find("#room-3-child-1").show().find('select').attr('disabled',false);
			room.find("#room-3-child-2").hide().find('select').attr('disabled',true);
			room.find("#room-3-child-3").hide().find('select').attr('disabled',true);
		} else {
			room.find("#room-3-child-1").hide().find('select').attr('disabled',true);
			room.find("#room-3-child-2").hide().find('select').attr('disabled',true);
			room.find("#room-3-child-3").hide().find('select').attr('disabled',true);
		};
	});

}

var calendar = function(){
	if($('body').data('lang')=="es") {
		$.datepicker.regional['es'] = {
                closeText: 'Cerrar',
				prevText: "«",
				nextText: "»",
                currentText: 'Hoy',
                monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
                'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
                monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun',
                'Jul','Ago','Sep','Oct','Nov','Dic'],
                dayNames: ['Domingo','Lunes','Martes','Mi&eacute;rcoles','Jueves','Viernes','S&aacute;bado'],
                dayNamesShort: ['Dom','Lun','Mar','Mi&eacute;','Juv','Vie','S&aacute;b'],
                dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','S&aacute;'],
                /*weekHeader: 'Sm',
                dateFormat: 'dd/mm/yy',
                firstDay: 1,
                isRTL: false,
                showMonthAfterYear: false,*/
                yearSuffix: ''};
        $.datepicker.setDefaults($.datepicker.regional['es']);
	};
	
		$("#datepicker_from").focus(function() {
			$(this).parent().addClass("active");
		});
		
		$("#datepicker_from").blur(function() {
			$(this).parent().removeClass("active");
		});
		
		$("#datepicker_to").focus(function() {
			$(this).parent().addClass("active");
		});
		
		$("#datepicker_to").blur(function() {
			$(this).parent().removeClass("active");
		});

        var from = jQuery("#datepicker_from").val().split(","),
            to = jQuery("#datepicker_to").val().split(",");


       $("#datepicker_from").datepicker({
            //minDate: "+1d",
            minDate: new Date(from[2],parseInt(from[0],10)-1,from[1]),
            altField: "#date_from",
            altFormat: 'yy-mm-dd',
            dateFormat: 'mm, d, yy',
            onSelect: toSetFinal,
            defaultDate: new Date(2015,01,12),
            hideIfNoPrevNext:true,
            changeYear: false,
            changeMonth: false,
            showOtherMonths: true,
            selectOtherMonths: true,
            prevText: "«",
            nextText: "»",
			dayNamesShort: ['S','M','T','W','T','F','S'],
			dayNamesMin: ['S','M','T','W','T','F','S']
        });

        $("#datepicker_to").datepicker({
            //minDate: "+2d",
            minDate: new Date(to[2],parseInt(to[0],10)-1,to[1]),
            altField: "#date_to",
            altFormat: 'yy-mm-dd',
            dateFormat: 'mm, d, yy',
            //defaultDate: '+2d',
            defaultDate: new Date(2015,01,12),
            onSelect: toSetFinal,
            changeYear: false,
            changeMonth: false,
            showOtherMonths: true, 
            selectOtherMonths: true,
            prevText: "«",
            nextText: "»",
			dayNamesShort: ['S','M','T','W','T','F','S'],
			dayNamesMin: ['S','M','T','W','T','F','S']
        });
		
		$("#spa-reserve-date").datepicker({
			//minDate:"+1d",
            minDate: new Date(2015,01,12),
			dateFormat: 'mm/dd/y',
			altFormat: 'yyyy/MM/dd',
			changeYear: false,
			changeMonth: false,
			showOtherMonths: true, 
			selectOtherMonths: true,
			prevText: "«",
			nextText: "»",
			dayNamesShort: ['S','M','T','W','T','F','S'],
			dayNamesMin: ['S','M','T','W','T','F','S']		
		});
		
		calIcon();

}

var calIcon = function(){
	$(".calendar-icon").click(function(){
		$("#spa-reserve-date").trigger("focus");
	});
}


function setNigths() { 
    var date_from = $('#date_from').val().replace(/-/g, '/');
    var date_from = new Date(date_from+' 4:00:00');
    var date_to = $('#date_to').val().replace(/-/g, '/');   
    var date_to = new Date(date_to+' 12:00:00');
    var diff = Math.floor((date_to.getTime() - date_from.getTime()) / 24 / 60 / 60 / 1000);
    $('#nights').val(diff);
}

function toSetFinal() {
    var date_from = $('#date_from').val().replace(/-/g, '/');   
    var date_from = new Date(date_from+' 4:00:00');
    var date_to = $('#date_to').val().replace(/-/g, '/');   
    var date_to = new Date(date_to+' 12:00:00');
    
    
    $("#datepicker_to").datepicker('destroy');
    $("#datepicker_to").datepicker({
        minDate: new Date(date_from.getFullYear()+'/'+(date_from.getMonth()+1)+'/'+(date_from.getDate()+1)+' 00:00:00'),
        altField: "#date_to",
        altFormat: 'yy-mm-dd',
        dateFormat: 'mm, d, yy', 
        defaultDate: '+8d',
        onSelect: toSetFinal,
        changeYear: false,
        changeMonth: false,
        showOtherMonths: true, 
        selectOtherMonths: true,
        prevText: "«",
        nextText: "»",
		dayNamesShort: ['S','M','T','W','T','F','S'],
		dayNamesMin: ['S','M','T','W','T','F','S']
    });
    //$("#datepicker_to").datepicker('option', 'minDate', );
    //CHECK DATE TO  > DATE FROM
    var diff = Math.floor((date_to.getTime() - date_from.getTime()) / 24 / 60 / 60 / 1000);

    if(diff<=0) {
        date_from.setDate(date_from.getDate() + 1);
        $('#date_to').val(date_from.getFullYear()+'/'+(date_from.getMonth()+1)+'/'+date_from.getDate());
        $("#datepicker_to").datepicker('setDate', date_from);
    }
    
    
    setNigths();
};

function submitIBEform () {
	
	$('#frmAvailability').submit();
}

var subscribe_form = function() {
  $("#subscribe-form").submit(function(){
  var email = $("input#fieldEmail").val();
    if(email != "") {
      $(this).find("input, span").hide();
      $(this).find("label").text("Thank you for subscribing!");
      $.ajax({
        data: {email:email},
        dataType: "json",
        type: "GET",
        url: "/subscribe.php",
        cache: false,
      })
    }
    return false;
  });
}

var subscribe_placeholder = function(){
	var pl = $('.subscribe-block input[type="text"]');
	var placeholder = pl.val();
	pl.focus(function(){
		if(this.value==placeholder){
			this.value='';
		}
	});
	
	pl.blur(function(){
		if(this.value==''){
			this.value=placeholder;
		}
	});   
}

var checkbox_logic = function(){
	$(".toggle-title a").parent().append('<span class="toggle-plus">+</span><span class="toggle-minus">-</span>');
	$(".toggle-title a").click(function(){
		var toggleLink = $(this);
		if(toggleLink.hasClass("open")) {
			toggleLink.closest(".form-reserve-group").find(".toggle").slideUp(200, function(){toggleLink.removeClass("open");});
			toggleLink.parent().find(".toggle-minus").fadeOut(200, function(){toggleLink.parent().find(".toggle-plus").fadeIn(200);});
		} else {
			toggleLink.closest(".form-reserve-group").find(".toggle").slideDown(200, function(){toggleLink.addClass("open");});
			toggleLink.parent().find(".toggle-plus").fadeOut(200, function(){toggleLink.parent().find(".toggle-minus").fadeIn(200);});
		};
	});
	
	$(".checkbox-item input").click(function(){
		if($(this).parent().hasClass("checked")) {
			$(this).parent().removeClass("checked").next(".checkbox-descr").slideUp(300).animate({'opacity':'0'},200);
		} else {
			$(this).parent().addClass("checked").next(".checkbox-descr").slideDown(300).animate({'opacity':'1'},200);
		};
	});
	
}

var bullet = function(){
    $("#mobile-nav ul li.expanded").append('<span class="bullet">+</span>'); 
}

var burger = function(){
    $(".burger").click(function(){
        if($('body').hasClass("burger-open")){
            close_mobilemenu();
            $("#mobile-nav").css({'max-height':'100%','overflow':'auto'});
        } else {
            if($('html').hasClass('mobile')) {
                if($('body').hasClass('scrollhead')) {
                    $('html,body').scrollTop(1);
                } else {
                    $(window).scrollTop();
                }
                //$('body').bind('touchmove', false);
                //$('#site').bind('touchmove', true);
            };
            if($('html').hasClass('tablet')) {
                var menuHeight = $(window).height()-63;
                $("#mobile-nav").css({'max-height':menuHeight+'px','overflow':'scroll'});
                close_tablet_menu();
            };
            $("#mobile-nav").show();
            $('body').addClass("burger-open");
        };
        close_popup();
    });
    
    $('.close-tablet-menu').click(function(){
        close_mobilemenu();
        close_popup();
    });
    
}


var close_mobilemenu = function(){
    $("#mobile-nav").hide();
    $('body').removeClass("burger-open");
    $("#mobile-nav li.expanded").find('ul').hide();
    $("#mobile-nav li.expanded span.bullet").html("+").removeClass("bullet-cur");
}

var air_hotel_click = function() {
    ga('send', 'event', 'button', 'click', 'gbs-external', 1);
    return true;
}

function l(en, es) {
 
}