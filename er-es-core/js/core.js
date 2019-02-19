/* Main JSON sender object */
var core = Object({
  TA:true,
  redirect: true,
	userID: 0,
  user: null,
	state:"start", /* login, account, reservations */
  reservation:null,
  saveSession:function(){
    if(core.TA){    
      $.ajax({
  			url:"/er-ta/session.php",
  			method: "get",
        data:{
          action:"set",
          ID:core.user.ID,
          NAME:core.user.FIRSTNAME,
          LASTNAME:core.user.LASTNAME        
        }
  		});
    }      
  },
  clearSession:function(){
    if(core.TA){    
      $.ajax({
  			url:"/er-ta/session.php",
  			method: "get",
        data:{
          action:"delete"
        }
  		});
    }    
  },
  initialize:function(){
    var ID = parseInt($.cookie("ID"));
    var hash = location.hash.split("/");
    var action = "default";
    if(hash.length>1)
      action = hash[1];
    
    switch(action){
      case "reservations":
      if(hash[2]){
        core.getReservation(hash[4],hash[2],hash[3]);
        core.redirect = false;
      }
      break; 
    }
     
    /* IF TA */
    if(core.TA){
      if(ID){
        core.userID = ID;      
        control.showTATabs();        
        core.user = core.getTAById(ID);        
      } else {
        control.showAnonimTabs(); 
        control.gotoLogin();
      }   
    } else {
    /* IF GUEST */
      if(ID){
        core.userID = ID;      
        control.showGuestTabs();        
        core.user = core.getGuestById(ID);
      } else {
        control.showAnonimTabs(); 
        control.gotoLogin();
      }
    }
    
  },
	/* Error handling */
	showError:function(msg){
		alert(msg);
	},
	/* Success messages */
	showMessage:function(msg){
		alert(msg);
	},
	
  /** LOGIN BLOCK **/
	tryLogin:function(_email, _password){		
		$.ajax({
			url:core.TA?"/ibe/index.php?PAGE_CODE=ws.getTA&EMAIL="+_email+"&PWD="+_password:"/ibe/index.php?PAGE_CODE=ws.getGuest&EMAIL="+_email+"&PWD="+_password,
			dataType: "json",
			success:core.loginSuccess,
			error:core.loginFail
		});
	},
    loginSuccess:function(data){
      if(!data.ID){
  		  core.showError("Por favor verifique su correo electrónico y contraseña e intente de nuevo. Si el problema persiste por favor contáctenos.\nUSA 866-540-2585\nCanada 866-451-1592");					
  		  core.userID = 0;
  		} else {
  		  if(core.TA&&data.IS_CONFIRMED!="1"){
  		    core.showError("You're not confirmed yet");
  		    return false;
        }
  		  $.cookie("ID",data.ID);
  		  core.userID = parseInt(data.ID);
        core.user = data;
        core.saveSession();
        /* FILL ACCOUNT */
        if(core.TA){
          core.fillTAAccount();
          control.showTATabs();
          control.gotoTAAccount();
        } else {
          core.fillAccount();
          control.showGuestTabs();
          control.gotoAccount();
        }
        
  		}
    },
    loginFail:function(data){
      core.showError("Bad request");
  		core.user = null;
    },
    logOut:function(){
      control.gotoLogin();
      $.cookie("ID", 0);
      core.userID = 0;
      control.showAnonimTabs();
      core.clearSession();
      core.user = null;
    },
  fillAccount:function(){
    $.each(core.user, function(i, val){
      if($("#accountForm").find("#"+i).size()>0){            
        $("#accountForm").find("#"+i).val(val);
        if(i=="EMAIL") $("#CONFIRM_EMAIL").val(val);
        if(i=="COUNTRY") $("#COUNTRY").change();
      }
    });
  },
  fillTAAccount:function(){
    $.each(core.user, function(i, val){
      $("#TAAccountForm").find("[name="+i+"]").val(val);
      if(i=="EMAIL") $("input[name=EMAIL_CONFIRM]").val(val);
      if(i=="AGENCY_COUNTRY") $("#AGENCY_COUNTRY").change();
      /*
        
      }*/
    });
    $("#TAAccountForm").find("#STATE").val(core.user.AGENCY_STATE);
    $("span#COMMISSION_RATE").text(core.user.COMMISSION_RATE+"%");
  },
  getGuestById:function(_id){
    $.ajax({
			url:"/ibe/index.php?PAGE_CODE=ws.searchGuest&field=ID&value="+_id+"&ContentType=json",
			dataType: "json",
			success:core.getGuestSuccess,
			error:core.getGuestFail
		});
  },
    getGuestSuccess:function(data){
  		core.user = data.guests.guest;
      core.fillAccount();
      if(core.redirect)
        control.gotoAccount();
    },
    getGuestFail:function(){
      core.showError("Bad request");
  		core.user = null;
    },
  getTAById:function(_id){
    $.ajax({
			url:"/ibe/index.php?PAGE_CODE=ws.searchTA&field=ID&value="+_id+"&ContentType=json",
			dataType: "json",
			success:core.getTASuccess,
			error:core.getTAFail
		});
  },
    getTASuccess:function(data){
  		core.user = data.agents.agent;
      core.fillTAAccount();
      if(core.redirect)
        control.gotoTAAccount();
    },
    getTAFail:function(){
      core.showError("Bad request");
  		core.user = null;
    },
  
    
	/* ACCOUNT UPDATE */
	saveGuest:function(_id, _options){
		$.ajax({
			url:"/ibe/index.php?PAGE_CODE=ws.updateGuest&ID="+_id+"&"+_options.join('&'),
			dataType: "json",
			success:core.saveGuestSuccess,
			error:core.saveGuestFail
		});
	},
    saveGuestSuccess:function(data){
      //core.showMessage('Saved');
      window.location.reload();
    },
    saveGuestFail:function(data){
      core.showError('Fracasado');      
    },
  saveTA:function(_id, _options){
		$.ajax({
			url:"/ibe/index.php?PAGE_CODE=ws.saveTA&ID="+_id+"&"+_options.join('&'),
			dataType: "json",
			success:core.saveTASuccess,
			error:core.saveTAFail
		});
	},
    saveTASuccess:function(data){
      //core.showMessage('Saved');
      $.cookie("ID",core.userID);
      window.location.reload();
    },
    saveTAFail:function(data){
      core.showError('Fracasado');      
    },
	/* password recovery */
	sendPasswordGuest:function(_email){
		$.ajax({
			url:"/ibe/index.php?PAGE_CODE=ws.sendGuestPwd&EMAIL="+_email,
			dataType: "json",
			success:function(data){
				if(data.error)
					core.showError("GuestError: "+_email+" "+data.error);
				else
					core.showMessage("E-mail "+_email+" sent");		
			},
			error:function(data){core.showError("Bad request");}
		});
	},
	/* password ta */
	sendPasswordTA:function(_email){
		$.ajax({
			url:"/ibe/index.php?PAGE_CODE=ws.sendTAPwd&EMAIL="+_email,
			dataType: "json",
			success:function(data){
				if(data.error)
					core.showError("TAError: "+_email+" "+data.error);
				else
					core.showMessage("E-mai "+_email+" sent");		
			},error:function(data){core.showError("Bad request");}
		});
	},
  getGuestReservations:function(_id){    
    $.ajax({
			url:"/ibe/index.php?PAGE_CODE=ws.getGuestReservations&ID="+_id+"&GROUPED=0",
			dataType: "json",
			success:core.getGuestReservationsSuccess,
      error:core.getGuestReservationsFail
		});
  },
    getGuestReservationsSuccess:function(data){
      $(".gotoReservation").unbind('click');
      $("form#reservationForm tbody tr").remove();
      var i = 0;
      var tmp = '';      
      $.each(data,function(id, val){        
        var transdate = val.MODIFIED;
        transdate = transdate.substr(0,10);
        var year = val.CREATED;
        year = year.substr(0,4);
        tmp += '<tr><td><span><a class="gotoReservation" href="#/reservations/'+year+'/'+val.HOTEL+'/'+val.ID+'">'+val.NUMBER+'</a></span></td><td>'+val.STATUS_STR+'</td><td>'+val.CHECK_IN+'</td><td>'+val.CHECK_OUT+'</td><td>'+transdate+'</td><td><a class="gotoReservation" href="#/reservations/'+year+'/'+val.HOTEL+'/'+val.ID+'">Ver</a></td></tr>';        
        i++;
      });
      $("form#reservationForm tbody").append(tmp);            
      $(".gotoReservation").click(function(){
        var tmp = $(this).attr("href").split("#");        
        var hash = tmp[1].split("/");
        window.location.href = $(this).attr("href");                
        core.getReservation(hash[4],hash[2],hash[3]);
      });
    },
    getGuestReservationsFail:function(data){
      core.showError("Solicitud incorrecta");
    },
  getTAReservations:function(_id){
    $.ajax({
			url:"/ibe/index.php?PAGE_CODE=ws.getTAReservations&ID="+_id+"&GROUPED=0",
			dataType: "json",
			success:core.getTAReservationsSuccess,
      error:core.getTAReservationsFail
		});
  },
    getTAReservationsSuccess:function(data){
     $("form#TAreservationForm tbody tr").remove();
      var i = 0;
      var tmp = '';      
      $.each(data,function(id, val){
        var transdate = val.MODIFIED;
        transdate = transdate.substr(0,10);
        var year = val.CREATED;
                
        year = year.substr(0,4);
        tmp += '<tr><td><span><a class="gotoReservation" href="#/reservations/'+year+'/'+val.HOTEL+'/'+val.ID+'">'+val.NUMBER+'</a></span></td><td>'+val.STATUS_STR+'</td><td>'+val.CHECK_IN+'</td><td>'+val.CHECK_OUT+'</td></tr>';
        i++;
      });
      $("form#TAreservationForm tbody").append(tmp);      
      $(".gotoReservation").click(function(){
        var tmp = $(this).attr("href").split("#");
        var hash = tmp[1].split("/");
        window.location.href = $(this).attr("href");        
        core.getReservation(hash[4],hash[2],hash[3]);
      });
    },
    getTAReservationsFail:function(data){
      core.showError("Solicitud incorrecta");
    },
  getReservation:function(_id, _year, _code){
 	  $.ajax({
			url:"/ibe/index.php?PAGE_CODE=ws.getJSON&RES_ID="+_id+"&CODE="+_code+"&YEAR="+_year,
			dataType: "json",
			success:core.reservationSuccess,
			error:core.reservationFail
		});
  },
    reservationSuccess:function(data){
      
      core.reservation = data;
      var hotels = new Array("ninguno","Excellence Riviera Cancun","Playa Mujeres","Punta Canna","La Amada Hotel");
      $(".autofill").text('loading...');$("#optionalPrefs").find("input[name=CODE]").val();
      
      var GUESTS = 0;
      switch(data.RES_ROOMS_QTY){
        case 1:
          GUESTS = parseInt(data.RES_ROOM_1_ADULTS_QTY,10);
          break;
        case 2:
          GUESTS = parseInt(data.RES_ROOM_1_ADULTS_QTY,10) + parseInt(data.RES_ROOM_2_ADULTS_QTY,10);
          break;
        case 3:
          GUESTS = parseInt(data.RES_ROOM_1_ADULTS_QTY,10) + parseInt(data.RES_ROOM_2_ADULTS_QTY,10) + parseInt(data.RES_ROOM_3_ADULTS_QTY,10);
          break;
      }
      
      $(".pickup_cb").change(function(){
        if($(this).is(":checked")){
          $("#airportpickup_open").show();
        } else {
          $("#airportpickup_open").hide();  
        }
      });
      
      $("#GUEST_ROOMS").text(data.RES_ROOMS_QTY);
      $("#ROOM_COUNT").text(GUESTS);
      
      /* PAYMENT */
      switch(data.RESERVATION.RES_GUESTMETHOD){
        case "CC":
          $(".method_wire").hide();
          $(".method_cc").show();
          $("span#PAYMENTCCTYPE").text(data.RESERVATION.PAYMENT.CC_TYPE);
          $("span#PAYMENTCCNUMBER").text("********"+data.RESERVATION.PAYMENT.CC_NUMBER);
          break;
        case "WIRE":
          $(".method_wire").show();
          $(".method_cc").hide();
          break;
      }
      
      /* GUEST INFO */
      $("span#GUESTNAME").text(data.RESERVATION.GUEST.TITLE+" "+data.RESERVATION.GUEST.FIRSTNAME+" "+data.RESERVATION.GUEST.LASTNAME);
      $("span#GUESTADDRESS").text(data.RESERVATION.GUEST.ADDRESS);
      $("span#GUESTPHONE").text(data.RESERVATION.GUEST.PHONE);
      $("span#GUESTEMAIL").text(data.RESERVATION.GUEST.EMAIL);
      
      /* DATE */
      $("span#DATETRANS").text(dateToStr(data.RES_DATE));
      $("span#DATECHECKIN").text(dateToStr(data.RES_CHECK_IN));
      $("span#DATECHECKOUT").text(dateToStr(data.RES_CHECK_OUT));
      
      /* HOTEL */
      $("span#HOTEL").text(hotels[data.RES_PROP_ID]);
      
      $("#cancelView, #CANCEL, #contactMakeChanges, #preferencesMakeChanges, #commentsMakeChanges").hide();
      if(data.RESERVATION.STATUS_STR == "cancelled"){
        $("span#STATUS").html('<span class="cancelled"><span class="red">Cancelled</span></span>');
        $("#cancelView").slideDown();
        $("span#NOTES").text(data.RESERVATION.NOTES);        
        $("span#FEE").text("$"+main.setPrice(data.RESERVATION.FEES));
        if(parseInt(data.RESERVATION.FEES)!=data.RESERVATION.FEES){
          $("#cancelView").hide();
        } else {
          $("#cancelView").show();
        }
      } else if(data.RESERVATION.STATUS_STR == "booked") {
        var hash = location.hash.split("/");
        $("#optionalPrefs").find("input[name=RES_ID]").val(hash[4]);
        $("#optionalPrefs").find("input[name=YEAR]").val(hash[2]);
        $("#optionalPrefs").find("input[name=CODE]").val(hash[3]);
        $("#optionalPrefs").find("input[name=RES_NUM]").val(core.reservation.RESERVATION.RES_NUMBER);
        
        
        $("span#STATUS").text(data.RESERVATION.STATUS_STR);
        $("#CANCEL, #contactMakeChanges, #preferencesMakeChanges, #commentsMakeChanges").show();
        $("#CANCEL").attr('href',location.hash).unbind('click').click(function(){
          $("#cancelEdit").slideDown();        
          
          $("#buttonCancellation").attr('href',location.hash).click(function(){
            var hash = location.hash.split("/");
            core.cancelReservation(core.reservation.RESERVATION.RES_NUMBER, hash[4],hash[2],hash[3],$("#cancellationNote").val());
          })                  
        });        
      } else {
        $("span#STATUS").text(data.RESERVATION.STATUS_STR);        
      }
      
      if(data.RES_ITEMS.hasOwnProperty("CANCELLATION_POLICY")){
        $(".cancelation-info").show();
        $("#CANCELLATION").html('').html(data.RES_ITEMS.CANCELLATION_POLICY);
      } else {
        $(".cancelation-info").hide();
        $("#CANCELLATION").html('');
      }
      $("#singleReservationForm").find("div#COMMENTS").html(decodeURIComponent(data.RESERVATION.COMMENTS));
      
      /* ROOMS */
      
      $("span#TOTALCHARGE").text("$"+main.setPrice(data.RESERVATION.RES_TOTAL_CHARGE));
      if(core.TA)
        $("span#YOURCOMMISSION").text("$"+main.setPrice(Math.round(data.RESERVATION.RES_TOTAL_CHARGE * core.user.COMMISSION_RATE/100)));
      if(data.RESERVATION.ARRIVAL_TIME){
        $("span#ARRIVAL").text(data.RESERVATION.ARRIVAL_TIME + " " + data.RESERVATION.ARRIVAL_AMPM);
        $("span#AIRLINE").text(data.RESERVATION.AIRLINE);
        $("span#FLIGHT").text(data.RESERVATION.FLIGHT);
        $(".arrival-block").show();
      } else {
        $("span#ARRIVAL").hide();
        $(".arrival-block").hide();
      }
      
      $(".preferences").html('');
      $(".rooms-edit").html('');
        
      var BED_TYPES = "";
      
      $.each(data.RES_ITEMS.PROPERTY.BED_TYPES,function(i,val){
        BED_TYPES += '<option value="'+i+'">'+val+'</option>';
      });
      
      $.each(data.RESERVATION.ROOMS, function(i, val){
        $(".preferences").append(preparePreference(i+1,val.GUEST_TITLE + (val.GUEST_FIRSTNAME!=null?(" " + val.GUEST_FIRSTNAME):"") + " " + (val.GUEST_LASTNAME!=null?(" " + val.GUEST_LASTNAME):""), val.GUEST_REPEATED, data.RES_ITEMS.PROPERTY.BED_TYPES[val.GUEST_BEDTYPE], val.GUEST_SMOKING, val.GUEST_OCCASION));        
        var room = data.RES_ROOM_1_ROOMS[data.RESERVATION.RES_ROOMS_SELECTED[i]];
        $(".rooms-edit").append(prepareRoomEdit(i,room.NAME,BED_TYPES));
        
        /* ЗАКИДЫВАЕМ ДАННЫЕ */
        $("input[name=RES_GUEST_ROOM_"+i+"_TITLE]").val(val.GUEST_TITLE);
        $("input[name=RES_GUEST_ROOM_"+i+"_FIRSTNAME]").val(val.GUEST_FIRSTNAME);
        $("input[name=RES_GUEST_ROOM_"+i+"_LASTNAME]").val(val.GUEST_LASTNAME);
        if(val['GUEST_REPEATED']){
          $.each(val.GUEST_REPEATED, function(i,val){
           $("input[name='RES_GUEST_ROOM_0_REPEATED[]']:eq("+(parseInt(val,10)-1)+")").attr('checked', true);
          });
        }
        
        $("input[name=RES_GUEST_ROOM_"+i+"_TITLE]").val(val.GUEST_TITLE);
        $("select[name=RES_GUEST_ROOM_"+i+"_BEDTYPE]").val(val.GUEST_BEDTYPE);
        $("select[name=RES_GUEST_ROOM_"+i+"_SMOKING]").val(val.GUEST_SMOKING);
        $("select[name=RES_GUEST_ROOM_"+i+"_OCCASION]").val(val.GUEST_OCCASION);
      });
      /* ЕЩЕ ДАННЫЕ */
      $("input[name=RES_GUEST_ARRIVAL_TIME]").val(data.RESERVATION.ARRIVAL_TIME);
      $("input[name=RES_GUEST_ARRIVAL_AMPM]:eq("+(data.RESERVATION.ARRIVAL_AMPM=="PM"?"1":"0")+")").attr("checked","true");
      $("input[name=RES_GUEST_AIRLINE]").val(data.RESERVATION.AIRLINE);
      $("input[name=RES_GUEST_FLIGHT]").val(data.RESERVATION.FLIGHT);
      
      $("#optionalPrefs").find("textarea#COMMENTS").val($("div#COMMENTS").text());
      $(".form-item-how textarea").remove();
      var tmp = data.RESERVATION.HEAR_ABOUT_US.split('|');
          if(!tmp[1])
            tmp[1] = "";
			tmp[1] = decodeURIComponent(tmp[1]);
      switch(data.RESERVATION.HEAR_ABOUT_US.substr(0,3)){
        case "Rep":
          $(".hear:eq(0)").attr("checked","true");
          break;
        case "Rec":
          $(".hear:eq(1)").attr("checked","true");
          break;
        case "Tri":
          $(".hear:eq(2)").attr("checked","true");
          break;
        case "Int":          
          $(".hear:eq(3)").attr("checked","true").parent().append('<textarea name="HEAR_ABOUT_US" id="HEAR_ABOUT_US">'+tmp[1]+'</textarea>');
          break;
        case "New":
          $(".hear:eq(4)").attr("checked","true").parent().append('<textarea name="HEAR_ABOUT_US" id="HEAR_ABOUT_US">'+tmp[1]+'</textarea>');
          break;
        default:
          $(".hear:eq(5)").attr("checked","true").parent().append('<textarea name="HEAR_ABOUT_US" id="HEAR_ABOUT_US">'+tmp[1]+'</textarea>');
          break;        
      }
      $(".hear").change(function(){
        $(".form-item-how textarea").remove();
      });
      $(".hear_about_us").unbind("change").change(function(){
        $(".form-item-how textarea").remove();
        if($(this).attr("checked")=="checked"){
          $(this).parent().append('<textarea name="HEAR_ABOUT_US" id="HEAR_ABOUT_US"></textarea>');
        }
      });
      
      $(".reservation-rooms").html('');
      $.each(data.RESERVATION.RES_ROOMS_SELECTED, function(i, val){        
        var room = data.RES_ROOM_1_ROOMS[val];
        var QTY = 0;
        switch(i){
          case 0:
            QTY = data.RES_ROOM_1_ADULTS_QTY;
            break;
          case 1:
            QTY = data.RES_ROOM_2_ADULTS_QTY;
            break;
          case 2:
            QTY = data.RES_ROOM_3_ADULTS_QTY;
            break;
        }
        $(".reservation-rooms").append(prepareRoom(i+1,room.NAME, data.RESERVATION.RES_ROOM_CHARGE[i], QTY, main.setPrice(data.RESERVATION.RES_ROOM_CHARGE[i])));
      });
      
      $("#guest_single_reservation_block").find("#RES_NUMBER").text(data.RESERVATION.RES_NUMBER);
      
      $("input[name=RES_GUEST_AIRPORT_PICKUP]").unbind('change').change(function(){
        if(!$(this).prop("checked")){
          $("input[name=RES_GUEST_AIRLINE], input[name=RES_GUEST_FLIGHT]").val('');
          $("#airportpickup_open").hide();
        } else {
          $("#airportpickup_open").show();
        }
      });
      
      if(data.RESERVATION.FLIGHT || data.RESERVATION.AIRLINE){
        $("input[name=RES_GUEST_AIRPORT_PICKUP]").click();
        $("input[name=RES_GUEST_AIRPORT_PICKUP]").change();
      }
      
      control.gotoReservation();
    },
    reservationFail:function(data){
      core.showError('Bad request');
    },
  cancelReservation:function(_num, _id, _year, _code, _comment){
		if(_id==null||_year==null||_num==null||_code==null){
			alert("An error ocurred, please try again");
		} else {
			$.ajax({      
				url:"/ibe/index.php?PAGE_CODE=ws.cancelReservation&RES_NUM="+_num+"&ID="+_id+"&CODE="+_code+"&YEAR="+_year+"&NOTES="+_comment+"",
				dataType: "json",
				success:core.cancelSuccess,
				error:core.cancelFail
			});
		}
  },
    cancelSuccess:function(){
      //core.showMessage('cancelled');			
			alert('Cancelled');
      window.location.reload();
    },
    cancelFail:function(){
      core.showError('Failed!');
      //window.location.reload();      
    },
  saveReservation:function(_num, _id, _year, _code, _options){
		$.ajax({
			url:"/ibe/index.php?PAGE_CODE=ws.updateOptionals&RES_ID="+_id+"&RES_NUM="+_num+"&CODE="+_code+"&YEAR="+_year+"&"+_options.join('&'),
			dataType: "json",
			success:core.saveGuestSuccess,
			error:core.saveGuestFail
		});
	},
    saveReservationSuccess:function(data){
      core.showMessage('Guardado');      
    },
    saveReservationFail:function(data){
      core.showError('No se guarda');      
    }
});

/* CONTROL OBJECT */
var control = Object({
  initialize:function(){
    // JSON object initialization
    core.initialize();
    main.dateInitialize();
    main.formInitialize();
    
    /* AVAIBILITY FORM */
    $("#aviailability_form").validate({
      rules: {RES_PROP_ID: "required"	},
  		messages: {RES_PROP_ID: "Por favor seleccione un destino!"}
    });
    
    $("#guestLoginForm").validate({
      rules: {email:"required",password:"required"},
      submitHandler:function(){
        core.tryLogin($("#guestLoginForm").find("#email").val(),$("#guestLoginForm").find("#password").val());
      },
      errorPlacement: function(error, element) {
			}
    });
    
    /* MAKE CHANGES */
    $("#contactMakeChanges").click(control.gotoAccount);
    $("#preferencesMakeChanges").click(control.showPreferences);
    $("#commentsMakeChanges").click(control.showPreferences);
    
    /* COUNTRY */
    $("#COUNTRY, #AGENCY_COUNTRY").change(function(){
      $(".USA-STATE, .MEXICA-STATE, .CANADA-STATE, .TEXT-STATE").hide().attr("id","HIDDEN").addClass('hidden');
      switch($(this).val()){
        case "US":          
          $(".USA-STATE").show().removeClass('hidden').attr("id","STATE");
          break;
        case "MX":
          $(".MEXICA-STATE").show().removeClass('hidden').attr("id","STATE");
          break;
        case "CA":
          $(".CANADA-STATE").show().removeClass('hidden').attr("id","STATE");
          break;
        default:
          $(".TEXT-STATE").show().removeClass('hidden').attr("id","STATE");
      }
    });
    
    $("#forgotPassword").click(control.showPopupPassword);
    
    /* TAB NAVIGATION */
    $(".tab_menu a").click(function(){$(this).parent().find('a').removeClass("active");$(this).addClass("active");});
    $("a#tabGuestAccount").click(control.gotoAccount);
    $("a#gotoRegister, a#tabTAAccount").click(control.gotoTAAccount);
    $(".ta-tabs a:eq(1), a#tabReservations").click(control.gotoReservations);
    $("a#tabVacation").click(control.gotoVacation);    
    $(".ta-tabs a:eq(4), a#tabLogOut").click(core.logOut);
    
    /* VACATION FORM */
    $("form#TAVacationForm").submit(function(){
      $.ajax({
        url: "/er-ta/send.php",
        type: "POST",
        data:{
          IATA:core.user.IATA,
          EMAIL:core.user.EMAIL,
          NAME:core.user.FIRSTNAME + " " + core.user.LASTNAME,
          VACATION:$("#TAVacationForm").find("[name=VACATION]").val()
        },
        success:function(){
          core.showMessage("E-mail enviado");
        }                
      });
      return false;
    });
  },
  showPreferences:function(){
    $("#content form").slideUp();
    $("form#optionalPrefs").slideDown();
    $("form#optionalPrefs").ajaxForm({
      success:function(){
        core.showMessage("Guardado");
        window.location.reload();
      },
      beforeSubmit:function(arr, $form, options){
        
        $(arr).each(function(i){
          if(this.name=="HEAR_ABOUT_US"){            
            arr[i].value = $("input[name=HEAR]:checked").val()+"|"+encodeURIComponent(arr[i].value);
          }
          if(this.name=="COMMENTS"){            
            arr[i].value = encodeURIComponent(arr[i].value);
          }
        });
          
        return true;
      },      
      error:function(){
        core.showMessage("Error");
      }
    });
    return false;
  },
  gotoLogin:function(){
    if(core.state=="login")
      return false;
    core.state = "login";
    $("#content form").slideUp();
    $("form#guestLoginForm").slideDown();
  },
  gotoAccount:function(){
    if(core.userID > 0){
      if(core.state=="account")
        return false;
      core.state = "account";
      $("form#accountForm").validate({
        rules: {
          ADDRESS:"required",
          PHONE:"required",
          EMAIL:{
    				required: true,
    				email: true,
    				remote: "/er-guest/email.php?old="+core.user.EMAIL
          },
          CONFIRM_EMAIL:{equalTo: "#EMAIL"},
          LASTNAME:"required",
          FIRSTNAME:"required",
          CITY:"required"
        },
        messages: {
          EMAIL: {
            remote: "This Email Address is already in the system"
          }
        },
        submitHandler:function(){
          var options = Array();
          $("form#accountForm").find("select, input").not(".submit").each(function(){
            if($(this).attr("id")!="HIDDEN")
              options.push($(this).attr("id")+"="+encodeURIComponent($(this).val()));
          });
          core.saveGuest(core.userID, options);
        }
      });
      $("#content form").slideUp();
      $("form#accountForm").slideDown();
      
    } else control.gotoLogin();
  },
  gotoReservation:function(){
    if(core.state=="reservation")
      return false;
    core.state = "reservation";
    
    $("#content form").slideUp();
    $("form#singleReservationForm").slideDown();    
  },
  gotoVacation:function(){
    if(core.state=="vacation")
      return false;
    core.state = "vacation";
    
    $("#content form").slideUp();
    $("form#TAVacationForm").slideDown();    
  },
  gotoTAAccount:function(){
    if(core.state=="taaccount")
      return false;
    core.state = "taaccount";
    
    $("#content form").slideUp();
    $("form#TAAccountForm").slideDown();
    if(core.user == null){
      $("form#TAAccountForm").find("input").not("input[type=submit]").val("");
      $("form#TAAccountForm").find("input[name=ID]").val(0);
      $("form#TAAccountForm").find(".info").show();
      $("form#TAAccountForm").find(".commision").hide();
    } else {
      $("form#TAAccountForm").find(".info").hide();
      $("form#TAAccountForm").find(".commision").show();
    }
    $("form#TAAccountForm").validate({
        rules: {
          IATA:"required",
          AGENCY_NAME:"required",
          AGENCY_PHONE:"required",
          AGENCY_ADDRESS:"required",
          AGENCY_CITY:"required",
          ADDRESS:"required",
          PHONE:"required",
          EMAIL:{
    				required: true,
    				email: true,
    				remote: "/er-ta/email.php"+(core.user == null?"":("?old="+core.user.EMAIL))
          },
          CONFIRM_EMAIL:{equalTo: "#EMAIL"},
          LASTNAME:"required",
          FIRSTNAME:"required",
          CITY:"required"
        },
        messages: {
          EMAIL: {
            remote: "Este correo electrónico ya se encuentra en el sistema"
          }
        },
				errorElement:"div",
        submitHandler:function(){
          var options = Array();
          $("form#TAAccountForm").find("input[name=AGENCY_STATE]").val($("form#TAAccountForm").find("#STATE").val());
          core.userID = $("form#TAAccountForm").find("input[name=ID]").val();
          $("form#TAAccountForm").find("select, input").not(".submit").each(function(){            
            if($(this).attr("name")!=""&&$(this).attr("name")){
              if(!($(this).attr("name")=="PASSWORD" && $(this).val()==""))                            
                options.push($(this).attr("name")+"="+encodeURIComponent($(this).val()));       
            }
          });
          core.saveTA(core.userID, options);
        }
    });
      
  },
  gotoTAReservations:function(){
    if(core.state=="reservations")
      return false;
    core.state = "reservations";
    
    $("#content form").slideUp();
    $("form#reservationForm").slideDown();
    core.getTAReservations(core.userID);
  },
  gotoReservations:function(){
    if(core.state=="reservations")
      return false;
    core.state = "reservations";
    
    $("#content form").slideUp();
    
    if(core.TA){
      $("form#TAreservationForm").slideDown();
      core.getTAReservations(core.userID);
    } else {
      $("form#reservationForm").slideDown();
      core.getGuestReservations(core.userID);
    }
  },
  showAnonimTabs:function(){
    $(".tab_menu div").hide();
    $(".anonim-tabs").fadeIn();
  },
  showGuestTabs:function(){
    $(".tab_menu div").hide();
    $(".logged-tabs").fadeIn();
  },
  showTATabs:function(){
    $(".tab_menu div").hide();
    $(".ta-tabs").fadeIn();
  },
  showPopupPassword:function(){
    $("#mask, #popup").fadeIn();
    $("#mask, .close_popup").unbind('click').click(function(){
      $("#mask, #popup").fadeOut();
    });
    $("form#passwordForm").unbind('submit').submit(function(){
      if(core.TA){
        core.sendPasswordTA($(this).find("#email").val());
      } else {
        core.sendPasswordGuest($(this).find("#email").val());
      }
      return false;
    });
    return false;
  }
});

/* old object */
var main = Object({
  dateInitialize:function(){
    
    $("#datepicker_from").datepicker({
			minDate: "+1d",
			altField: "#date_from",
			altFormat: 'yy-mm-dd',
			dateFormat: 'mm, d, yy',
			defaultDate: "+1d",
      monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
  		'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
  		monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun',
  		'Jul','Ago','Sep','Oct','Nov','Dic'],
  		dayNames: ['Domingo','Lunes','Martes','Mi&eacute;rcoles','Jueves','Viernes','S&aacute;bado'],
  		dayNamesShort: ['Dom','Lun','Mar','Mi&eacute;','Juv','Vie','S&aacute;b'],
  		dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','S&aacute;'],
			changeYear: false,
      onSelect: main.toSetFinal,
			changeMonth: false,
			showOtherMonths: true,
			selectOtherMonths: true,
			prevText: "«",
			nextText: "»"
		});
    
		$("#datepicker_to").datepicker({
			minDate: "+2d",
			altField: "#date_to",
			altFormat: 'yy-mm-dd',
			dateFormat: 'mm, d, yy',
			defaultDate: '+2d',
      monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
  		'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
  		monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun',
  		'Jul','Ago','Sep','Oct','Nov','Dic'],
  		dayNames: ['Domingo','Lunes','Martes','Mi&eacute;rcoles','Jueves','Viernes','S&aacute;bado'],
  		dayNamesShort: ['Dom','Lun','Mar','Mi&eacute;','Juv','Vie','S&aacute;b'],
  		dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','S&aacute;'],
      onSelect: main.toSetFinal,
			changeYear: false,
			changeMonth: false,
			showOtherMonths: true, 
			selectOtherMonths: true,
			prevText: "«",
			nextText: "»"
		});
    
  },
  formInitialize:function(){
    $("#select_room").change(function(){
        switch($(this).val()){
          case "1":
            $(".room2, .room3").slideUp();
            break;
          case "2":
            $(".room3").slideUp();
            $(".room2").slideDown();
            break;
          case "3":
            $(".room2, .room3").slideDown();
            break;
        }
    });    
  },
  setNigths:function () {
		var date_from = $('#date_from').val().replace(/-/g, '/');
		var date_from = new Date(date_from+' 4:00:00');
		var date_to = $('#date_to').val().replace(/-/g, '/');	
		var date_to = new Date(date_to+' 12:00:00');
		var diff = Math.floor((date_to.getTime() - date_from.getTime()) / 24 / 60 / 60 / 1000);
		$('#nights').val(diff);
	},
  toSetFinal:function() {
		var date_from = $('#date_from').val().replace(/-/g, '/');	
		var date_from = new Date(date_from+' 00:00:00');
		var date_to = $('#date_to').val().replace(/-/g, '/');	
		var date_to = new Date(date_to+' 00:00:00');
		
		
		$("#datepicker_to").datepicker('destroy');
		$("#datepicker_to").datepicker({
			minDate: new Date(date_from.getFullYear()+'/'+(date_from.getMonth()+1)+'/'+(date_from.getDate()+1)+' 00:00:00'),
			altField: "#date_to",
			altFormat: 'yy-mm-dd',
			dateFormat: 'mm, d, yy', 
			defaultDate: '+8d',
      monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
  		'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
  		monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun',
  		'Jul','Ago','Sep','Oct','Nov','Dic'],
  		dayNames: ['Domingo','Lunes','Martes','Mi&eacute;rcoles','Jueves','Viernes','S&aacute;bado'],
  		dayNamesShort: ['Dom','Lun','Mar','Mi&eacute;','Juv','Vie','S&aacute;b'],
  		dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','S&aacute;'],
			onSelect: main.toSetFinal,
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
		
		
		main.setNigths();
	},
  setPrice:function(_price) {
    var tmp = ""+_price;
    if(_price < 1000)
      return _price;
    return tmp.substr(0, tmp.length-3)+ "," + tmp.substr(tmp.length-3,3);
    return (Math.floor(_price/1000)?(Math.floor(_price/1000)+","):'')+_price%1000;
  }
});

function dateToStr(date){
  var month = new  Array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
  tmp = date.replace("%2F","-").split("-");
  return month[parseInt(tmp[1],10)-1] + " " + parseInt(tmp[2],10) + ", " + tmp[0];
}

function prepareRoom(_num, _type, _rate, _guests, _charge) {
  _rate = "La mejor tarifa disponible";
  return '<div class="item-group">Habitación '+_num+
  '<div class="item"><label>Tipo habitación:</label>'+_type+'</div>'+
  '<div class="item"><label>Tarifa:</label>'+_rate+'</div>'+
  '<div class="item"><label>No. de huéspedes:</label>'+_guests+'</div>'+
  '<div class="item"><label>Habitación cargo:</label>$'+_charge+'</div>'+
  '</div>';
}

function preparePreference(_room, _name, _repeat, _bed, _smoking, _occassion) {
  var tmp = '<div class="item-group">Habitación '+_room;
  
  if(_name!="Mr. null null")
    tmp += '<div class="item">Nombre: '+_name+'</div>';
  
  if(_repeat && _repeat.length>0){
    var hotels = new Array("ninguno","Excellence Riviera Cancun","Playa Mujeres","Punta Canna","La Amada Hotel");
    var _tmp = new Array();
    $.each(_repeat, function(i,val){
      _tmp.push(hotels[val]);      
    });
    tmp += '<div class="item">Cliente repetitivo: '+_tmp.join(", ")+'</div>';
  }
  
  var pref = 0;
  if(_bed){
    pref++;
    tmp += '<div class="item">Tipo de cama: '+_bed+'</div>';
  }
  if(_smoking){
    pref++;
    tmp += '<div class="item">Preferencia de Habitación: '+_smoking+'</div>';
  }
  if(_occassion){
    pref++;
    tmp += '<div class="item">Ocasión Especial : '+_occassion+'</div>';
  }
  if(pref==0)
    tmp += '<div class="item">Ninguna</div>'
  tmp += '</div>';
  return tmp;
}

function prepareRoomEdit(_num, _room_title, _bed_type) {
  return '<div class="underline"><h3>Habitación '+(_num+1)+': '+_room_title+'</h3><div class="user-info"><table class="form_builder"><tr>'+
  '<td class="td_80 mt"><select name="RES_GUEST_ROOM_'+_num+'_TITLE" class="w80"><option value="Mr.">Sr.</option><option value="Mrs.">Sra.</option><option value="Dr.">Dr.</option></select><br />'+
  '<label class="none">Trato</label></td>'+  
  '<td class="td_220"><input name="RES_GUEST_ROOM_'+_num+'_FIRSTNAME" type="text" class="w205"/><br /><label class="none">Nombre</label></td>'+
  '<td class="td_220"><input name="RES_GUEST_ROOM_'+_num+'_LASTNAME" type="text" class="w205"/><br /><label class="none">Apellido</label></td>'+
  '</tr></table>'+
  '<table class="form_builder"><tr><td class="td_180"><label class="none">Se ha hospedado en Excellence anteriormente</label></td>'+
  '<td class="td_slayed"><input name="RES_GUEST_ROOM_'+_num+'_REPEATED[]" value="1" type="checkbox" class="checkbox" />'+
  '<label class="ch_first mr10">Rivera Cancun</label>'+
  '<input name="RES_GUEST_ROOM_'+_num+'_REPEATED[]" value="2" type="checkbox" class="checkbox" />'+
  '<label class="ch_second mr10">Playa Mujeres</label>'+
  '<input name="RES_GUEST_ROOM_'+_num+'_REPEATED[]" value="3" type="checkbox" class="checkbox" />'+
  '<label class="ch_last mr10">Punta Cana</label>'+
  '</td></tr></table>'+
  '<table class="form_builder"><tr><td class="td_180">'+
  '<label>Preferencia en tipo de cama</label></td>'+
  '<td class="td_select"><select name="RES_GUEST_ROOM_'+_num+'_BEDTYPE" class="w205">'+
  '<option value="">Ninguna</option>'+
  _bed_type+
  '</select>'+
  '</td></tr>'+
  '<tr><td class="td_180">'+
  '<label>Preferencia de Habitación</label></td>'+
  '<td class="td_select"><select name="RES_GUEST_ROOM_'+_num+'_SMOKING" class="w205">'+
  '<option value="">Ninguna</option>'+
  '<option value="Non-smoking">No Fumar</option>'+
  '<option value="Smoking">Fumar</option>'+
  '</select>'+
  '</td></tr>'+
  '<tr><td class="td_180">'+
  '<label>Ocasión Especial </label></td>'+
  '<td class="td_select"><select name="RES_GUEST_ROOM_'+_num+'_OCCASION" class="w205">'+
  '<option value="">Ninguna</option>'+
  '<option value="Anniversary">Aniversario</option>'+
  '<option value="Honeymoon">Luna de Miel</option>'+
  '<option value="Birthday">Cumpleaños</option>'+
  '</select>'+
   '</td></tr></table></div>';
}