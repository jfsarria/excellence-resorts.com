<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Excellence Resorts - My Account</title>
	<meta name="title" content="" />
	<meta name="keywords" content="" />
	<meta name="description" content="" />
	<link rel="stylesheet" href="/er-es-core/css/style.css" type="text/css" media="screen, projection" />
  <link rel="stylesheet" href="/er-es-core/css/style_calendar.css" type="text/css" media="screen, projection" />
  <!--[if lte IE 7]><link rel="stylesheet" href="/er-es-core/css/style_ie.css" type="text/css" media="screen, projection" /><![endif]-->
  <script type="text/javascript" src="/er-es-core/js/jquery.js"></script>
  <script type="text/javascript" src="/er-es-core/js/jquery-ui.min.js"></script>
  <script type="text/javascript" src="/er-es-core/js/jquery.validate.min.js"></script>
  <script type="text/javascript" src="/er-es-core/js/jquery.form.js"></script>  
  <script type="text/javascript" src="/er-es-core/js/jquery.cookie.js"></script>
  <script type="text/javascript" src="/er-es-core/js/core.js"></script>
  <script type="text/javascript">
  $(document).ready(function(){
    core.TA = true;
    control.initialize();
  });
  </script>
</head>

<body>
<div id="mask"></div><!--#mask-->
<div id="popup">
	<a href="#" class="close_popup">Cerca X</a>
    <p>Introduzca la dirección de correo electrónico asociado con su cuenta en Excellence Resorts y a continuación haga clic en Enviar.</p>
    <form action="" id="passwordForm">
    <div class="form-item">
    	<input id="email" type="text" />
        <label>Correo electrónico</label>
    </div><!--.form-item-->
    <div class="form-submit">
    	<input type="submit" value="Enviar" />
    </div><!--.form-submit-->
    </form>
</div><!--.popup-->
<div id="wrapper" class="spanish">

	<div id="header">
    	<a href="/es" id="logo"></a>
       	<div class="top_menu">
			<a href="http://www.excellence-resorts.com/ofertas-especiales">OFERTAS ESPECIALES</a> | <a href="http://www.excellence-resorts.com/galeria-de-imagenesy">IMÁGENES</a> | <a href="http://www.excellence-resorts.com/">INICIO</a>
		</div><!--.top_menu-->
        <div id="nav">
          <ul>
            <li class="nav_l_1"><a href="http://www.excellence-resorts.com/descubre-excellence">descubre excellence</a></li>
            <li class="nav_l_2"><a href="http://www.excellence-resorts.com/destinos">destinos</a></li>
            <li class="nav_l_3"><a href="http://www.excellence-resorts.com/suites">suites</a></li>
            <li class="nav_l_4"><a href="http://www.excellence-resorts.com/restaurantes">restaurantes</a></li>
            <li class="nav_l_5"><a href="http://www.excellence-resorts.com/bodas-and-romance">bodas & romance</a></li>
            <li class="nav_l_6"><a href="http://www.excellence-resorts.com/miile-spa-es">miile spa</a></li>
            <li class="nav_l_7"><a href="http://www.excellence-resorts.com/convenciones">convenciones</a></li>
          </ul>
        </div><!--.nav-->
	</div><!-- #header-->

<div id="container">
	<div id="content">
		<div class="tab_menu">
        		<div class="logged-tabs" style="display:none"><a id="tabGuestAccount" class="active" href="#/account">Cuenta del huésped</a> | <a href="#/reservations" id="tabReservations" >Reservaciones</a> | <a id="tabLogOut" href="#">Salir</a></div>
            <div class="anonim-tabs" style="display:none"><a id="tabGuestAccount" class="active" href="#/account">Cuenta del huésped</a></div>
            <div class="ta-tabs" style="display:none"><a id="tabTAAccount" class="active" href="#/account">travel agent account</a> | <a href="#/reservations" id="tabReservations" >Reservaciones</a> | <a target="_blank" href="http://www.excellence-resorts.com/excellence-resorts-image-gallery/high-resolution-images-excellence-resorts" id="tabImages" >imágenes & Logos</a> | <a href="#/images" id="tabVacation" >Mis vacaciones</a> | <a id="tabLogOut" href="#">Salir</a></div>
        </div><!--.tab_menu-->
        <form style="display: none;" id="TAreservationForm">
          <div id="reservation_block">
        	<table width="100%">
            	<thead>
                	<th>Reservación #</th>
                                   
                    <th>Estado</th>
                    <th>Llegada</th>
                    <th>Salida</th>
                </thead>
                <tbody>
                    
				        </tbody>
            </table>
            </div><!--.TAreservation_block-->
            </form>
        <form id="optionalPrefs" style="display:none;" method="get" action="/ibe/index.php">
          <input type="hidden" name="PAGE_CODE" value="ws.updateOptionals" />                
          <input type="hidden" name="CODE" />
          <input type="hidden" name="YEAR" />
          <input type="hidden" name="RES_ID" />
          <input type="hidden" name="RES_NUM" />
          <h1>Preferencias</h1>
          <div id="opotional_form">        	
        	<div class="rooms-edit">          
          </div>
            <table class="form_builder">
            <tr>
            <td class="td_180">
            	<label>Hora de llegada</label>
            </td>
            <td class="td_input">
                <input type="text" class="w145 mr10" name="RES_GUEST_ARRIVAL_TIME" />
                <input type="radio" value="AM" class="radio" name="RES_GUEST_ARRIVAL_AMPM"/><label class="am mr10">AM</label>
                <input type="radio" value="PM" class="radio" name="RES_GUEST_ARRIVAL_AMPM"/><label class="pm">PM</label>
            </td>
            </tr>
            <tr>
            <td class="td_180">
            	<label>Servicio de transporte<input name="RES_GUEST_AIRPORT_PICKUP" type="checkbox" value="1" class="ml10 pickup_cb" /></label>
            </td>
            <td class="td_input">
                	<div class="info">Costo adicional. Servicio de transporte debe ser solicitado por lo menos 48 horas antes de la fecha de llegada.</div>
            </td>
            </tr>
            </table>
            
            <div style="display: none;" id="airportpickup_open">
                <table class="form_builder">
                <tr>
                <td class="td_220">
		          <label class="mr none lh">Aerolínea</label><input type="text" name="RES_GUEST_AIRLINE" class="w145" />
                </td>
                <td>
  					<label class="mr lh">Número de Vuelo</label><input type="text" name="RES_GUEST_FLIGHT" class="w145" />
                </td>
                </tr>
                </table>
            </div>
            <div class="form-textarea-item">
                <table class="form_builder">
                <tr>
                <td class="td_180">
            	   <label>Comentarios y solicitudes especiales</label>
                </td>
                <td class="td_textarea">
                    <textarea name="COMMENTS" id="COMMENTS" rows="5"></textarea>
                </td>
                </tr>
                </table>
            </div><!--.form-textarea-item-->
            <h4>Como se entero de nosotros?</h4>
            <table class="form_builder">
            <tr>
            <td class="td_320">
                <div class="form-item-how">
               	<div><input type="radio" name="HEAR" class="hear radio" value="Repeat guest"/><label class="none">Cliente repetitivo</label><br/></div>
               	<div><input type="radio" name="HEAR" class="hear radio" value="Recommendation" /><label class="none">Recomendaciones</label><br/></div>
               	<div><input type="radio" name="HEAR" class="hear radio" value="Tripadvisor" /><label class="none">Tripadvisor</label></div>
                </div><!--.form-item-how-->
            </td>
            <td>
                <div class="form-item-how">
               	<div><input type="radio" class="hear hear_about_us radio" name="HEAR" value="Internet"/><label>Internet...</label><br /></div>
               	<div><input type="radio" class="hear hear_about_us radio" name="HEAR" value="Newspaper/Magazine/Radio"/><label>Periódico/Revistas/TV/Radio...</label><br /></div>
               	<div><input type="radio" class="hear hear_about_us radio" name="HEAR" value="Other"/><label>Otros...</label><br /></div>
                </div><!--.form-item-how-->
             </td>
            </tr>
            </table>
            <div class="form-submit">
            	<input type="submit" class="submit" value="Save Changes" />
            </div><!--.form-submit-->
        </div><!--#opotional_form-->
        </form>
        <form style="display: none;" id="TAAccountForm">
        <div class="info">
          <p>Como Agente de Viajes registrado  usted puede realizar reservaciones para clientes y recibir comisión</p>

          <p>Registrarse es fácil, solo complete el formato indicado abajo.<br />Nosotros nos comunicaremos tan pronto su información haya sido verificada.</p>
        
        </div>
        <input name="ID" type="hidden" />
        <input name="IS_ACTIVE" type="hidden" value="1" />
        <h1>Información de la Agencia</h1>
        <div id="agency_info_block">
            <table class="form_builder">
            <tr>
            <td class="td_150">
                <label>IATA*</label>
            </td>
            <td class="td_input">
                <input name="IATA" type="text" class="w145" />
            </td>
            </tr>
            <tr>
            <td class="td_150">
            	<label>Nombre de la agencia*</label>
            </td>
            <td class="td_input">
                <input name="AGENCY_NAME" type="text" class="w145" />
            </td>
            </tr>
            <tr>
            <td class="td_150">
            	<label>Teléfono de la agencia*</label>
            </td>
            <td class="td_input">
                <input name="AGENCY_PHONE" type="text" class="w145" />
            </td>
            </tr>
            <tr>
            <td class="td_150">
            	<label>Dirección*</label>
            </td>
            <td class="td_input">
                <input name="AGENCY_ADDRESS" type="text" class="w145" />
            </td>
            </tr>
            <tr>
            <td class="td_150">
            	<label>Ciudad*</label>
            </td>
            <td class="td_input">
                <input name="AGENCY_CITY" type="text" class="w145"/>
            </td>
            </tr>
            <tr>
            <td class="td_150">
            	<label>País*</label>
            </td>
            <td class="td_input">
                <select id="AGENCY_COUNTRY" name="AGENCY_COUNTRY" class="w145">
                   	<option selected="" value="US">United States</option><option value="MX">Mexico</option><option value="CA">Canada</option><option value="AF">Afghanistan</option><option value="AL">Albania</option><option value="DZ">Algeria</option><option value="AD">Andorra</option><option value="AO">Angola</option><option value="AG">Antigua &amp; Barbuda</option><option value="AR">Argentina</option><option value="AM">Armenia</option><option value="AW">Aruba</option><option value="AU">Australia</option><option value="AT">Austria</option><option value="AZ">Azerbaijan</option><option value="BS">Bahama</option><option value="BH">Bahrain</option><option value="BD">Bangladesh</option><option value="BB">Barbados</option><option value="BY">Belarus</option><option value="BE">Belgium</option><option value="BZ">Belize</option><option value="BM">Bermuda</option><option value="BJ">Benin</option><option value="BT">Bhutan</option><option value="BO">Bolivia</option><option value="BA">Bosnia and Herzegovina</option><option value="BW">Botswana</option><option value="BR">Brazil</option><option value="BN">Brunei Darussalam</option><option value="BG">Bulgaria</option><option value="BF">Burkina Faso</option><option value="BI">Burundi</option><option value="KH">Cambodia</option><option value="CM">Cameroon</option><option value="CV">Cape Verde</option><option value="CF">Central African Republic</option><option value="TD">Chad</option><option value="CL">Chile</option><option value="CN">China</option><option value="CO">Colombia</option><option value="KM">Comoros</option><option value="CG">Congo</option><option value="CR">Costa Rica</option><option value="HR">Croatia</option><option value="CU">Cuba</option><option value="CY">Cyprus</option><option value="CZ">Czech Republic</option><option value="DK">Denmark</option><option value="DJ">Djibouti</option><option value="DM">Dominica</option><option value="DO">Dominican Republic</option><option value="TP">East Timor</option><option value="EC">Ecuador</option><option value="EG">Egypt</option><option value="SV">El Salvador</option><option value="GQ">Equatorial Guinea</option><option value="ER">Eritrea</option><option value="EE">Estonia</option><option value="ET">Ethiopia</option><option value="FJ">Fiji</option><option value="FI">Finland</option><option value="FR">France</option><option value="GA">Gabon</option><option value="GM">Gambia</option><option value="GE">Georgia</option><option value="DE">Germany</option><option value="GH">Ghana</option><option value="GR">Greece</option><option value="GD">Grenada</option><option value="GT">Guatemala</option><option value="GN">Guinea</option><option value="GW">Guinea-Bissau</option><option value="GY">Guyana</option><option value="HT">Haiti</option><option value="HN">Honduras</option><option value="HU">Hungary</option><option value="IS">Iceland</option><option value="IN">India</option><option value="ID">Indonesia</option><option value="IR">Islamic Republic of Iran</option><option value="IQ">Iraq</option><option value="IE">Ireland</option><option value="IL">Israel</option><option value="IT">Italy</option><option value="JM">Jamaica</option><option value="JP">Japan</option><option value="JO">Jordan</option><option value="KZ">Kazakhstan</option><option value="KE">Kenya</option><option value="KI">Kiribati</option><option value="KP">Korea, North</option><option value="KR">Korea, South</option><option value="KV">Kosovo</option><option value="KW">Kuwait</option><option value="KG">Kyrgyzstan</option><option value="LA">Lao People's Democratic Republic</option><option value="LV">Latvia</option><option value="LB">Lebanon</option><option value="LS">Lesotho</option><option value="LR">Liberia</option><option value="LY">Libyan Arab Jamahiriya</option><option value="LI">Liechtenstein</option><option value="LT">Lithuania</option><option value="LU">Luxembourg</option><option value="MK">Macedonia</option><option value="MG">Madagascar</option><option value="MW">Malawi</option><option value="MY">Malaysia</option><option value="MV">Maldives</option><option value="ML">Mali</option><option value="MT">Malta</option><option value="MH">Marshall Islands</option><option value="MR">Mauritania</option><option value="MU">Mauritius</option><option value="FM">Micronesia</option><option value="MD">Moldova, Republic of</option><option value="MC">Monaco</option><option value="MN">Mongolia</option><option value="ME">Montenegro</option><option value="MA">Morocco</option><option value="MZ">Mozambique</option><option value="MM">Myanmar</option><option value="NA">Namibia</option><option value="NR">Nauru</option><option value="NP">Nepal</option><option value="NL">Netherlands</option><option value="NZ">New Zealand</option><option value="NI">Nicaragua</option><option value="NE">Niger</option><option value="NG">Nigeria</option><option value="NO">Norway</option><option value="OM">Oman</option><option value="PK">Pakistan</option><option value="PW">Palau</option><option value="PA">Panama</option><option value="PG">Papua New Guinea</option><option value="PY">Paraguay</option><option value="PE">Peru</option><option value="PH">Philippines</option><option value="PL">Poland</option><option value="PT">Portugal</option><option value="QA">Qatar</option><option value="RO">Romania</option><option value="RU">Russia</option><option value="RW">Rwanda</option><option value="KN">St. Kitts and Nevis</option><option value="LC">Saint Lucia</option><option value="VC">St. Vincent &amp; the Grenadines</option><option value="WS">Samoa</option><option value="SM">San Marino</option><option value="ST">Sao Tome &amp; Principe</option><option value="SA">Saudi Arabia</option><option value="SN">Senegal</option><option value="RS">Serbia</option><option value="SC">Seychelles</option><option value="SL">Sierra Leone</option><option value="SG">Singapore</option><option value="SK">Slovakia</option><option value="SI">Slovenia</option><option value="SB">Solomon Islands</option><option value="SO">Somalia</option><option value="ZA">South Africa</option><option value="ES">Spain</option><option value="LK">Sri Lanka</option><option value="SD">Sudan</option><option value="SR">Suriname</option><option value="SZ">Swaziland</option><option value="SE">Sweden</option><option value="CH">Switzerland</option><option value="SY">Syrian Arab Republic</option><option value="TW">Taiwan, Province of China</option><option value="TJ">Tajikistan</option><option value="TZ">Tanzania, United Republic of</option><option value="TH">Thailand</option><option value="TG">Togo</option><option value="TO">Tonga</option><option value="TT">Trinidad &amp; Tobago</option><option value="TN">Tunisia</option><option value="TR">Turkey</option><option value="TM">Turkmenistan</option><option value="TV">Tuvalu</option><option value="UG">Uganda</option><option value="UA">Ukraine</option><option value="AE">United Arab Emirates</option><option value="GB">United Kingdom</option><option value="UY">Uruguay</option><option value="UZ">Uzbekistan</option><option value="VU">Vanuatu</option><option value="VA">Vatican City State (Holy See)</option><option value="VE">Venezuela</option><option value="VN">Viet Nam</option><option value="YE">Yemen</option><option value="ZM">Zambia</option><option value="ZW">Zimbabwe</option>
                </select>
            </td>
            </tr>
            <tr>
            <td class="td_150">
            	<label>Estado/provincia*</label>
            </td>
            <td class="td_input">
              <input name="AGENCY_STATE" type="hidden" class="w145" />
              <select class="USA-STATE hidden w145" id="AGENCY_STATE">
                  <option value="AL" id="USA-AL">Alabama (AL) </option>
                  <option value="AK" id="USA-AK">Alaska (AK) </option>
                  <option value="AZ" id="USA-AZ">Arizona (AZ) </option>
                  <option value="AR" id="USA-AR">Arkansas (AR) </option>
                  <option value="CA" id="USA-CA">California (CA) </option>
                  <option value="CO" id="USA-CO">Colorado (CO) </option>
                  <option value="CT" id="USA-CT">Connecticut (CT) </option>
                  <option value="DE" id="USA-DE">Delaware (DE) </option>
                  <option value="DC" id="USA-DC">District of Columbia (DC) </option>
                  <option value="FL" id="USA-FL">Florida (FL) </option>
                  <option value="GA" id="USA-GA">Georgia (GA) </option>
                  <option value="GU" id="USA-GU">Guam (GU) </option>
                  <option value="HI" id="USA-HI">Hawaii (HI) </option>
                  <option value="ID" id="USA-ID">Idaho (ID) </option>
                  <option value="IL" id="USA-IL">Illinois (IL) </option>
                  <option value="IN" id="USA-IN">Indiana (IN) </option>
                  <option value="IA" id="USA-IA">Iowa (IA) </option>
                  <option value="KS" id="USA-KS">Kansas (KS) </option>
                  <option value="KY" id="USA-KY">Kentucky (KY) </option>
                  <option value="LA" id="USA-LA">Louisiana (LA) </option>
                  <option value="ME" id="USA-ME">Maine (ME) </option>
                  <option value="MD" id="USA-MD">Maryland (MD) </option>
                  <option value="MA" id="USA-MA">Massachusetts (MA) </option>
                  <option value="MI" id="USA-MI">Michigan (MI) </option>
                  <option value="MN" id="USA-MN">Minnesota (MN) </option>
                  <option value="MS" id="USA-MS">Mississippi (MS) </option>
                  <option value="MO" id="USA-MO">Missouri (MO) </option>
                  <option value="MT" id="USA-MT">Montana (MT) </option>
                  <option value="NE" id="USA-NE">Nebraska (NE) </option>
                  <option value="NV" id="USA-NV">Nevada (NV) </option>
                  <option value="NH" id="USA-NH">New Hampshire (NH) </option>
                  <option value="NJ" id="USA-NJ">New Jersey (NJ) </option>
                  <option value="NM" id="USA-NM">New Mexico (NM) </option>
                  <option value="NY" id="USA-NY">New York (NY) </option>
                  <option value="NC" id="USA-NC">North Carolina (NC) </option>
                  <option value="ND" id="USA-ND">North Dakota (ND) </option>
                  <option value="OH" id="USA-OH">Ohio (OH) </option>
                  <option value="OK" id="USA-OK">Oklahoma (OK) </option>
                  <option value="OR" id="USA-OR">Oregon (OR) </option>
                  <option value="PA" id="USA-PA">Pennyslvania (PA) </option>
                  <option value="PR" id="USA-PR">Puerto Rico (PR) </option>
                  <option value="RI" id="USA-RI">Rhode Island (RI) </option>
                  <option value="SC" id="USA-SC">South Carolina (SC) </option>
                  <option value="SD" id="USA-SD">South Dakota (SD) </option>
                  <option value="TN" id="USA-TN">Tennessee (TN) </option>
                  <option value="TX" id="USA-TX">Texas (TX) </option>
                  <option value="UT" id="USA-UT">Utah (UT) </option>
                  <option value="VT" id="USA-VT">Vermont (VT) </option>
                  <option value="VA" id="USA-VA">Virginia (VA) </option>
                  <option value="VI" id="USA-VI">Virgin Islands (VI) </option>
                  <option value="WA" id="USA-WA">Washington (WA) </option>
                  <option value="WV" id="USA-WV">West Virginia (WV) </option>
                  <option value="WI" id="USA-WI">Wisconsin (WI) </option>
                  <option value="WY" id="USA-WY">Wyoming (WY)
                </select>
                <select class="CANADA-STATE hidden w145" id="AGENCY_STATE" style="display:none;">
                  <option value="AB" id="CAN-AB">Alberta (AB) </option>
                  <option value="BC" id="CAN-BC">British Columbia (BC) </option>
                  <option value="MB" id="CAN-MB">Manitoba (MB) </option>
                  <option value="NB" id="CAN-NB">New Brunswick (NB) </option>
                  <option value="NL" id="CAN-NL">Newfoundland and Labrador (NL) </option>
                  <option value="NT" id="CAN-NT">Northwest Territories (NT) </option>
                  <option value="NS" id="CAN-NS">Nova Scotia (NS) </option>
                  <option value="NU" id="CAN-NU">Nunavut (NU) </option>
                  <option value="PE" id="CAN-PE">Prince Edward Island (PE) </option>
                  <option value="SK" id="CAN-SK">Saskatchewan (SK) </option>
                  <option value="ON" id="CAN-ON">Ontario (ON) </option>
                  <option value="QC" id="CAN-QC">Quebec (QC) </option>
                  <option value="YT" id="CAN-YT">Yukon (YT)
                </select>
                <select class="MEXICA-STATE hidden w145" id="AGENCY_STATE" style="display:none;">
                  <option>Distrito Federal
                  <option>Chihuahua
                  <option>Sonora
                  <option>Coahuila
                  <option>Durango
                  <option>Oaxaca
                  <option>Tamaulipas
                  <option>Jalisco
                  <option>Zacatecas
                  <option>Baja California Sur
                  <option>Chiapas
                  <option>Veracruz
                  <option>Baja California
                  <option>Nuevo Leon
                  <option>Guerrero
                  <option>San Luis Potosi
                  <option>Michoacan
                  <option>Campeche
                  <option>Sinaloa
                  <option>Quintana Roo
                  <option>Yucatan
                  <option>Puebla
                  <option>Guanajuato
                  <option>Nayarit
                  <option>Tabasco
                  <option>Mexico
                  <option>Hidalgo
                  <option>Queretaro
                  <option>Colima
                  <option>Aguascalientes
                  <option>Morelos
                  <option>Tlaxcala
                </select>
                <input type="text" id="AGENCY_STATE" class="TEXT-STATE hidden w145" style="display:none;" />
            </td>
            </tr>
            <tr>
            <td class="td_150">
            	<label>Código postal*</label>
            </td>
            <td class="td_input">
                <input name="AGENCY_ZIPCODE" type="text" class="w145" />
            </td>
            </tr>
            </table>
            <table class="form_builder">
            <tr>
            <td class="td_250">
            	<label class="is_agency_label">Es agente /Agencia ubicada en México?</label>
            </td>
            <td>
                <label class="cb_mexico"><input name="IN_MEXICO" value="1" type="checkbox" class="checkbox" />Sí</label>
            </td>
            </tr>
            </table>
            <div class="form_required">
            	*Necesario
            </div><!--.form_required-->
        </div><!--#agency_info_block-->
        
        <h1>Información de contacto</h1>
        <div id="contact_form">
            <table class="form_builder">
            <tr>
            <td class="td_150">
                <label>Nombre*</label>
            </td>
            <td class="td_input">
                <input name="FIRSTNAME" type="text" class="w145" />
            </td>
            </tr>
            <tr>
            <td class="td_150">
                    <label>Apellido*</label>
            </td>
            <td class="td_input">
                    <input name="LASTNAME" type="text" class="w145" />
            </td>
            </tr>
            <tr>
            <td class="td_150">
                    <label>Correo electrónico*</label>
            </td>
            <td class="td_input">
                    <input name="EMAIL" type="text" class="w145"/>
            </td>
            </tr>
            <tr>
            <td class="td_150">
                    <label>Confirme su correo electrónico*</label>
            </td>
            <td class="td_input">
                    <input name="EMAIL_CONFIRM"  type="text" class="w145"/>
            </td>
            </tr>
            <tr>
            <td class="td_150">
                    <label>Contraseña*</label>
            </td>
            <td class="td_input">
                    <input name="PASSWORD"  type="password" class="w145"/>
            </td>
            </tr>
            </table>
          	<div class="commision">
               	Tarifa de comisión <span id="COMMISSION_RATE"></span>
            </div><!--.commision-->
            <div class="form_required">
            	*Necesario
            </div><!--.form_required-->
            <div class="form-submit">
            	<input type="submit" value="Guardar cambios" />
            </div><!--.form-submit-->
        </div><!--#contact_form-->
        </form>
        <form style="display: none;" id="TAVacationForm">
       	<div id="vacation_form">
        	Por favor contactenos si usted esta interesado en saber<br/>más sobre descuentos especiales para Agentes de viajes.
              	<textarea name="VACATION" rows="5" class="textarea"></textarea>
  			<div class="form-submit">
              	<input type="submit" value="Enviar" />
              </div><!--.form-submit-->
  		  </div><!--#vacation_form-->
        </form>
        <form style="display: none;" id="singleReservationForm">
       	<div id="guest_single_reservation_block">
            	<h3>
                    <span id="HOTEL">Excellence Riviera Cancun</span><br />
                    Reservación # <span id="RES_NUMBER">210030716885</span><br />
                    Estado: <span id="STATUS">Booked</span> <span class="gold"><a id="CANCEL" href="#">Cancel</a><!--  / <a href="#">Rebook </a>--></span><br />
                    <!--.Статус ОТМЕНЕНО-->
                    <!--Status: <span class="cancelled"><span class="red">Cancelled</span> on 07,25,2011</span>-->
                </h3>
                
            <table><tr><td>        
        	<div class="column no-float">
                    <!--.КОММЕНТАРИИ ПРИ ОТМЕНЕ БРОНИРОВАНИЯ. ПРИ ЭТОМ АКТИВНОМ БЛОКЕ В СТРОЧКЕ ВЫШЕ "/ <a href="#">Rebook </a>" НЕ ОТОБРАЖАЕТСЯ -->
                    <div id="cancelEdit" style="display:none">
                    <div class="cancel_comment_block">
                    	<textarea rows="3" id="cancellationNote"></textarea>
                        <div class="form-submit">
                            <div class="cancel_label">
                            	Cancellation note
                            </div><!--.cancel_label-->
                        	<input id="buttonCancellation" type="button" value="Cancel" />
                        </div><!--.form-submit-->
                    </div><!--.cancel_comment_block-->
                    </div>
                
                <!--.ОТОБРАЖАЕТСЯ ПРИ СТАТУСЕ ОТМЕНЕНО-->
                <div id="cancelView" style="display:none">
                  <h3>Cancellation note</h3>
                  <div class="item-group">
                  	<span id="NOTES"></span>
  				        </div><!--.item-group-->
                  <h3 id="FEEWRAPPER">Cancellation Fee: <span id="FEE">$1,234</span></h3>
                </div>
                <!--.КОНЕЦ ОТОБРАЖЕНИЯ -->

                
                <div class="item-group">
                    <div class="item">
                        <label>No. de Habitación:</label><span class="autofill" id="GUEST_ROOMS">0</span>
                    </div><!--.item-->
                    <div class="item">
                    <label>Adultos en Habitación:</label><span class="autofill" id="ROOM_COUNT">0</span>
                    </div><!--.item-->
                    <div class="item">
                    <label>Trans. Date:</label><span class="autofill" id="DATETRANS">July 16, 2011</span>
                    </div><!--.item-->
				        </div><!--.item-group-->
				
                <div class="item-group">
                    <div class="item">
                    <label>Llegada:</label><span class="autofill" id="DATECHECKIN">July 16, 2011</span>
                    </div><!--.item-->
                    <div class="item">
                    <label>Salida:</label><span class="autofill" id="DATECHECKOUT">July 16, 2011</span>
                    </div><!--.item-->
				</div><!--.item-group-->
                <div class="reservation-rooms autofill">
                <!-- ТУТ БУДУТ КУОМНАТЫ -->
                </div>
                <div class="item-group">
                    <div class="item">
                    <label>Cargo total:</label><span id="TOTALCHARGE">$1,336</span>
                    </div><!--.item-->
                    <div class="item">
                    <label>Su Comisión:</label><span id="YOURCOMMISSION">$0</span>
                    </div><!--.item-->
				</div><!--.item-group-->
                
                <h3>Información de contacto <a id="contactMakeChanges" href="#">&gt; Hacer cambios</a></h3>
                
                <div class="item-group">
                    <div class="item">
            	        Nombre: <span class="autofill" id="GUESTNAME">Mr. Juan Fernando Sarria</span>
                    </div><!--.item-->
                    <div class="item">
        	            Dirección: <span class="autofill" id="GUESTADDRESS">Bogota 12354,US</span>
                    </div><!--.item-->
                    <div class="item">
    	                Telefónico: <span class="autofill" id="GUESTPHONE">6565 5656</span>
                    </div><!--.item-->
                    <div class="item">
	                    Email: <span class="autofill" id="GUESTEMAIL">gfblj@email.com</span>
                    </div><!--.item-->
				</div><!--.item-group-->

				<h3>Información de Pago</h3>

                <div class="item-group">
                    <div class="item method_wire">
                	    Payment Method: <span id="PAYMENTMETHOD">Wire</span>
                    </div><!--.item-->
                    <div class="item method_cc">
                	    Card Type: <span id="PAYMENTCCTYPE">Visa</span>
                    </div><!--.item-->
                    <div class="item method_cc">
                	    Card Number: <span id="PAYMENTCCNUMBER">*******1111</span>
                    </div><!--.item-->
				</div><!--.item-group-->
			</div><!--.column-->
            </td>
            <td>                        
            <div class="column no-float">
            
                <h3>Preferencias <a id="preferencesMakeChanges" href="#">&gt; Hacer cambios</a></h3>
                <div class="preferences">
                <div class="item-group">
                	Room 1
                    <div class="item">
            	        Name: Mr. Bibi Villegas
                    </div><!--.item-->
                    <div class="item">
        	            Repaet Guest: Excellence Riviera Cancun, <br />
                        Excellence Punta Cana
                    </div><!--.item-->
                    <div class="item">
    	                Bed Type: 1 King
                    </div><!--.item-->
                    <div class="item">
	                    Baby Crib: No
                    </div><!--.item-->
                    <div class="item">
	                    Smoking Preference: Non-smoking
                    </div><!--.item-->
                    <div class="item">
	                    Special Occasion: Honeymoon
                    </div><!--.item-->
				</div><!--.item-group-->
                
                <div class="item-group">
                	Room 2
                    <div class="item">
            	        Name: Mr. Bibi Villegas
                    </div><!--.item-->
                    <div class="item">
        	            Repaet Guest: Excellence Riviera Cancun, <br />
                        Excellence Punta Cana
                    </div><!--.item-->
                    <div class="item">
    	                Bed Type: 1 King
                    </div><!--.item-->
                    <div class="item">
	                    Baby Crib: No
                    </div><!--.item-->
                    <div class="item">
	                    Smoking Preference: Non-smoking
                    </div><!--.item-->
                    <div class="item">
	                    Special Occasion: Honeymoon
                    </div><!--.item-->
				</div><!--.item-group-->
                </div>
                <div class="item-group arrival-block">
                    <div class="item">
						Arrival Time: <span id="ARRIVAL" class="autofill"></span>
                    </div><!--.item-->
                    <div class="item">
						Airline: <span id="AIRLINE" class="autofill"></span>
                    </div><!--.item-->
                    <div class="item">
						Flight: <span id="FLIGHT" class="autofill"></span>
                    </div><!--.item-->
				</div><!--.item-group-->
                
                <h3>Comentarios <a id="commentsMakeChanges" href="#">&gt; Hacer cambios</a></h3>
                
                <div id="COMMENTS" class="item-group autofill">
                	text, text, text
				</div><!--.item-group-->
                <div class="cancelation-info">
                <h3>Cancelation Information</h3>
                
                <div id="CANCELLATION" class="item-group">
                	text, text, text
          				</div><!--.item-group-->
                  </div>
             </div><!--.column-->
             </td></tr></table>             
            <div class="clearfix"></div>
        </div><!--#guest_single_reservation_block-->
        </form>
        <form style="display: none;" id="guestLoginForm">
       	<div id="login_form">
	      <table class="form_builder">
                <tr>
                <td class="w220 pr20">
                	<input id="email" name="email" type="text" value="" class="w205" /><br />
                    <label>Correo electrónico*</label>
                </td>
                <td width="w220 pr20">
                	<input id="password" name="password" value="" type="password" class="w205" />
                    <label>Contraseña&nbsp;|&nbsp;<a href="#" id="forgotPassword">Olvidó contraseña?</a></label>
                </td>
                <td>
                <div class="form-submit">
                	<input type="submit" value="Acceso" />
                </div><!--.form-submit-->
            </td>
            </th>
            </table>
            <ul class="reg_list">
            	<li>Aún no se ha registrado? <a href="#register" id="gotoRegister">Registrese aquí</a></li>
              <li><a target="_blank" href="http://www.excellence-resorts.com/excellence-resorts-image-gallery/high-resolution-images-excellence-resorts">Imágenes y Logos</a></li>
            </ul>
            </div><!--#login_form-->
        </form>
        <form style="display: none;" id="accountForm">
        <div id="account_form">
		  	<table class="form_builder">
                <tr>
                <td class="td_100 mt">
            	<select id="TITLE" class="w85">
                	<option value="Mr.">Sr.</option>
                  <option value="Mrs.">Sra.</option>
                  <option value="Dr.">Dr.</option>
                </select><br />
                <label>Trato</label>
                </td>
                <td class="pr20">
            	<input id="FIRSTNAME" type="text" value="Name" class="required w205" /><br />
                <label>Nombre*</label>
                </td>
                <td class="pr20">
            	<input id="LASTNAME" type="text" value="Lastname" class="required w205" /><br />
                <label>Apellido*</label>
                </td>
                </tr>
                <tr>
                <td class="td_100">
                </td>
                <td class="pr20">
            	<input id="EMAIL" type="text" name="EMAIL" class="required w205" /><br />
                <label>Correo electrónico*</label>
                </td>
                <td class="pr20">
            	<input id="CONFIRM_EMAIL" type="text" name="CONFIRM_EMAIL" class="required w205" /><br />
                <label>Confirme correo electrónico*</label>
                </td>
                </tr>
                <tr>
                <td class="td_100">
                </td>
                <td class="pr20" colspan="2">
            	<input id="PHONE" type="text" name="PHONE" class="required w205" /><br />
                <label>Número telefónico*</label>
                </td>
                </tr>
                <tr>
                <td class="td_100">
                </td>
                <td class="pr20" colspan="2">
            	<input id="ADDRESS" type="text" name="ADDRESS" class="required w_wide" /><br />
                <label>Dirección*</label>
                </td>
                </tr>
                <tr>
                <tr>
                <td class="td_100">
                </td>
                <td class="pr20">
            	<input id="CITY" type="text" name="CITY" class="required w205" /><br />
                <label>Ciudad*</label>
                </td>
                <td class="pr20">
            	<select id="COUNTRY" class="w205">
                	<option selected="" value="US">United States</option><option value="MX">Mexico</option><option value="CA">Canada</option><option value="AF">Afghanistan</option><option value="AL">Albania</option><option value="DZ">Algeria</option><option value="AD">Andorra</option><option value="AO">Angola</option><option value="AG">Antigua &amp; Barbuda</option><option value="AR">Argentina</option><option value="AM">Armenia</option><option value="AW">Aruba</option><option value="AU">Australia</option><option value="AT">Austria</option><option value="AZ">Azerbaijan</option><option value="BS">Bahama</option><option value="BH">Bahrain</option><option value="BD">Bangladesh</option><option value="BB">Barbados</option><option value="BY">Belarus</option><option value="BE">Belgium</option><option value="BZ">Belize</option><option value="BM">Bermuda</option><option value="BJ">Benin</option><option value="BT">Bhutan</option><option value="BO">Bolivia</option><option value="BA">Bosnia and Herzegovina</option><option value="BW">Botswana</option><option value="BR">Brazil</option><option value="BN">Brunei Darussalam</option><option value="BG">Bulgaria</option><option value="BF">Burkina Faso</option><option value="BI">Burundi</option><option value="KH">Cambodia</option><option value="CM">Cameroon</option><option value="CV">Cape Verde</option><option value="CF">Central African Republic</option><option value="TD">Chad</option><option value="CL">Chile</option><option value="CN">China</option><option value="CO">Colombia</option><option value="KM">Comoros</option><option value="CG">Congo</option><option value="CR">Costa Rica</option><option value="HR">Croatia</option><option value="CU">Cuba</option><option value="CY">Cyprus</option><option value="CZ">Czech Republic</option><option value="DK">Denmark</option><option value="DJ">Djibouti</option><option value="DM">Dominica</option><option value="DO">Dominican Republic</option><option value="TP">East Timor</option><option value="EC">Ecuador</option><option value="EG">Egypt</option><option value="SV">El Salvador</option><option value="GQ">Equatorial Guinea</option><option value="ER">Eritrea</option><option value="EE">Estonia</option><option value="ET">Ethiopia</option><option value="FJ">Fiji</option><option value="FI">Finland</option><option value="FR">France</option><option value="GA">Gabon</option><option value="GM">Gambia</option><option value="GE">Georgia</option><option value="DE">Germany</option><option value="GH">Ghana</option><option value="GR">Greece</option><option value="GD">Grenada</option><option value="GT">Guatemala</option><option value="GN">Guinea</option><option value="GW">Guinea-Bissau</option><option value="GY">Guyana</option><option value="HT">Haiti</option><option value="HN">Honduras</option><option value="HU">Hungary</option><option value="IS">Iceland</option><option value="IN">India</option><option value="ID">Indonesia</option><option value="IR">Islamic Republic of Iran</option><option value="IQ">Iraq</option><option value="IE">Ireland</option><option value="IL">Israel</option><option value="IT">Italy</option><option value="JM">Jamaica</option><option value="JP">Japan</option><option value="JO">Jordan</option><option value="KZ">Kazakhstan</option><option value="KE">Kenya</option><option value="KI">Kiribati</option><option value="KP">Korea, North</option><option value="KR">Korea, South</option><option value="KV">Kosovo</option><option value="KW">Kuwait</option><option value="KG">Kyrgyzstan</option><option value="LA">Lao People's Democratic Republic</option><option value="LV">Latvia</option><option value="LB">Lebanon</option><option value="LS">Lesotho</option><option value="LR">Liberia</option><option value="LY">Libyan Arab Jamahiriya</option><option value="LI">Liechtenstein</option><option value="LT">Lithuania</option><option value="LU">Luxembourg</option><option value="MK">Macedonia</option><option value="MG">Madagascar</option><option value="MW">Malawi</option><option value="MY">Malaysia</option><option value="MV">Maldives</option><option value="ML">Mali</option><option value="MT">Malta</option><option value="MH">Marshall Islands</option><option value="MR">Mauritania</option><option value="MU">Mauritius</option><option value="FM">Micronesia</option><option value="MD">Moldova, Republic of</option><option value="MC">Monaco</option><option value="MN">Mongolia</option><option value="ME">Montenegro</option><option value="MA">Morocco</option><option value="MZ">Mozambique</option><option value="MM">Myanmar</option><option value="NA">Namibia</option><option value="NR">Nauru</option><option value="NP">Nepal</option><option value="NL">Netherlands</option><option value="NZ">New Zealand</option><option value="NI">Nicaragua</option><option value="NE">Niger</option><option value="NG">Nigeria</option><option value="NO">Norway</option><option value="OM">Oman</option><option value="PK">Pakistan</option><option value="PW">Palau</option><option value="PA">Panama</option><option value="PG">Papua New Guinea</option><option value="PY">Paraguay</option><option value="PE">Peru</option><option value="PH">Philippines</option><option value="PL">Poland</option><option value="PT">Portugal</option><option value="QA">Qatar</option><option value="RO">Romania</option><option value="RU">Russia</option><option value="RW">Rwanda</option><option value="KN">St. Kitts and Nevis</option><option value="LC">Saint Lucia</option><option value="VC">St. Vincent &amp; the Grenadines</option><option value="WS">Samoa</option><option value="SM">San Marino</option><option value="ST">Sao Tome &amp; Principe</option><option value="SA">Saudi Arabia</option><option value="SN">Senegal</option><option value="RS">Serbia</option><option value="SC">Seychelles</option><option value="SL">Sierra Leone</option><option value="SG">Singapore</option><option value="SK">Slovakia</option><option value="SI">Slovenia</option><option value="SB">Solomon Islands</option><option value="SO">Somalia</option><option value="ZA">South Africa</option><option value="ES">Spain</option><option value="LK">Sri Lanka</option><option value="SD">Sudan</option><option value="SR">Suriname</option><option value="SZ">Swaziland</option><option value="SE">Sweden</option><option value="CH">Switzerland</option><option value="SY">Syrian Arab Republic</option><option value="TW">Taiwan, Province of China</option><option value="TJ">Tajikistan</option><option value="TZ">Tanzania, United Republic of</option><option value="TH">Thailand</option><option value="TG">Togo</option><option value="TO">Tonga</option><option value="TT">Trinidad &amp; Tobago</option><option value="TN">Tunisia</option><option value="TR">Turkey</option><option value="TM">Turkmenistan</option><option value="TV">Tuvalu</option><option value="UG">Uganda</option><option value="UA">Ukraine</option><option value="AE">United Arab Emirates</option><option value="GB">United Kingdom</option><option value="UY">Uruguay</option><option value="UZ">Uzbekistan</option><option value="VU">Vanuatu</option><option value="VA">Vatican City State (Holy See)</option><option value="VE">Venezuela</option><option value="VN">Viet Nam</option><option value="YE">Yemen</option><option value="ZM">Zambia</option><option value="ZW">Zimbabwe</option>
                </select><br />
                <label>País*</label>
                </td>
                </tr>
                <tr>
                <td class="td_100">
                </td>
                <td class="pr20">
            	<select class="USA-STATE w205" id="STATE">
                  <option value="AL" id="USA-AL">Alabama (AL) </option>
                  <option value="AK" id="USA-AK">Alaska (AK) </option>
                  <option value="AZ" id="USA-AZ">Arizona (AZ) </option>
                  <option value="AR" id="USA-AR">Arkansas (AR) </option>
                  <option value="CA" id="USA-CA">California (CA) </option>
                  <option value="CO" id="USA-CO">Colorado (CO) </option>
                  <option value="CT" id="USA-CT">Connecticut (CT) </option>
                  <option value="DE" id="USA-DE">Delaware (DE) </option>
                  <option value="DC" id="USA-DC">District of Columbia (DC) </option>
                  <option value="FL" id="USA-FL">Florida (FL) </option>
                  <option value="GA" id="USA-GA">Georgia (GA) </option>
                  <option value="GU" id="USA-GU">Guam (GU) </option>
                  <option value="HI" id="USA-HI">Hawaii (HI) </option>
                  <option value="ID" id="USA-ID">Idaho (ID) </option>
                  <option value="IL" id="USA-IL">Illinois (IL) </option>
                  <option value="IN" id="USA-IN">Indiana (IN) </option>
                  <option value="IA" id="USA-IA">Iowa (IA) </option>
                  <option value="KS" id="USA-KS">Kansas (KS) </option>
                  <option value="KY" id="USA-KY">Kentucky (KY) </option>
                  <option value="LA" id="USA-LA">Louisiana (LA) </option>
                  <option value="ME" id="USA-ME">Maine (ME) </option>
                  <option value="MD" id="USA-MD">Maryland (MD) </option>
                  <option value="MA" id="USA-MA">Massachusetts (MA) </option>
                  <option value="MI" id="USA-MI">Michigan (MI) </option>
                  <option value="MN" id="USA-MN">Minnesota (MN) </option>
                  <option value="MS" id="USA-MS">Mississippi (MS) </option>
                  <option value="MO" id="USA-MO">Missouri (MO) </option>
                  <option value="MT" id="USA-MT">Montana (MT) </option>
                  <option value="NE" id="USA-NE">Nebraska (NE) </option>
                  <option value="NV" id="USA-NV">Nevada (NV) </option>
                  <option value="NH" id="USA-NH">New Hampshire (NH) </option>
                  <option value="NJ" id="USA-NJ">New Jersey (NJ) </option>
                  <option value="NM" id="USA-NM">New Mexico (NM) </option>
                  <option value="NY" id="USA-NY">New York (NY) </option>
                  <option value="NC" id="USA-NC">North Carolina (NC) </option>
                  <option value="ND" id="USA-ND">North Dakota (ND) </option>
                  <option value="OH" id="USA-OH">Ohio (OH) </option>
                  <option value="OK" id="USA-OK">Oklahoma (OK) </option>
                  <option value="OR" id="USA-OR">Oregon (OR) </option>
                  <option value="PA" id="USA-PA">Pennyslvania (PA) </option>
                  <option value="PR" id="USA-PR">Puerto Rico (PR) </option>
                  <option value="RI" id="USA-RI">Rhode Island (RI) </option>
                  <option value="SC" id="USA-SC">South Carolina (SC) </option>
                  <option value="SD" id="USA-SD">South Dakota (SD) </option>
                  <option value="TN" id="USA-TN">Tennessee (TN) </option>
                  <option value="TX" id="USA-TX">Texas (TX) </option>
                  <option value="UT" id="USA-UT">Utah (UT) </option>
                  <option value="VT" id="USA-VT">Vermont (VT) </option>
                  <option value="VA" id="USA-VA">Virginia (VA) </option>
                  <option value="VI" id="USA-VI">Virgin Islands (VI) </option>
                  <option value="WA" id="USA-WA">Washington (WA) </option>
                  <option value="WV" id="USA-WV">West Virginia (WV) </option>
                  <option value="WI" id="USA-WI">Wisconsin (WI) </option>
                  <option value="WY" id="USA-WY">Wyoming (WY)
                </select>
                <select class="CANADA-STATE w205" id="STATE" style="display:none;">
                  <option value="AB" id="CAN-AB">Alberta (AB) </option>
                  <option value="BC" id="CAN-BC">British Columbia (BC) </option>
                  <option value="MB" id="CAN-MB">Manitoba (MB) </option>
                  <option value="NB" id="CAN-NB">New Brunswick (NB) </option>
                  <option value="NL" id="CAN-NL">Newfoundland and Labrador (NL) </option>
                  <option value="NT" id="CAN-NT">Northwest Territories (NT) </option>
                  <option value="NS" id="CAN-NS">Nova Scotia (NS) </option>
                  <option value="NU" id="CAN-NU">Nunavut (NU) </option>
                  <option value="PE" id="CAN-PE">Prince Edward Island (PE) </option>
                  <option value="SK" id="CAN-SK">Saskatchewan (SK) </option>
                  <option value="ON" id="CAN-ON">Ontario (ON) </option>
                  <option value="QC" id="CAN-QC">Quebec (QC) </option>
                  <option value="YT" id="CAN-YT">Yukon (YT)
                </select>
                <select class="MEXICA-STATE w205" id="STATE" style="display:none;">
                  <option>Distrito Federal
                  <option>Chihuahua
                  <option>Sonora
                  <option>Coahuila
                  <option>Durango
                  <option>Oaxaca
                  <option>Tamaulipas
                  <option>Jalisco
                  <option>Zacatecas
                  <option>Baja California Sur
                  <option>Chiapas
                  <option>Veracruz
                  <option>Baja California
                  <option>Nuevo Leon
                  <option>Guerrero
                  <option>San Luis Potosi
                  <option>Michoacan
                  <option>Campeche
                  <option>Sinaloa
                  <option>Quintana Roo
                  <option>Yucatan
                  <option>Puebla
                  <option>Guanajuato
                  <option>Nayarit
                  <option>Tabasco
                  <option>Mexico
                  <option>Hidalgo
                  <option>Queretaro
                  <option>Colima
                  <option>Aguascalientes
                  <option>Morelos
                  <option>Tlaxcala
                </select>
                <input type="text" id="STATE" class="TEXT-STATE w205" style="display:none;" /><br />
                <label>Estado / Provincia*</label>
                </td>
                <td class="pr20">
            	<input id="ZIPCODE" type="text" class="required w205" /><br />
                <label>Código postal*</label>
                </td>
                </tr>
                </table>
            <div class="form-submit">
            	<input type="submit" class="submit" value="Guardar cambios" />
            </div><!--.form-submit-->
		  </div><!--#account_form-->
        </form>
        <form style="display: none;" id="reservationForm">
        <div id="reservation_block">
        	<table width="100%">
            	<thead>
                	<th>Reservación #</th>
                    <th>Estado</th>
                    <th>Llegada</th>
                    <th>Salida</th>
                    <th>Trans. Date</th>
                    <th></th>
                </thead>
                <tbody>
                    
				        </tbody>
            </table>
            
                        
            
        </div><!--.reservation_block-->
        </form>
        
        
                   
	     </div><!-- #content-->
    <div id="sidebar">
    	<div class="sb_block">
            <form action="/er" id="aviailability_form">
                <ul class="sb_menu">
                    <li class="first"><a href="#" class="active">Hotel</a></li>
                    <li class="last"><a href="http://res.excellence.globalbookingsolutions.com/Search/Default.aspx?plcode=gbexcel" target="_blank">Vuelo</a></li>
                </ul><!--.sb_menu-->
                <div class="form-item">
               	    <select class="sel_dest" name="RES_PROP_ID" id="prop_id">
        				<option value="">Destino
        				<option value="1">Riviera Cancun, MX
        				<option value="2">Playa Mujeres, MX
        				<option value="3">Punta Cana, DR
    			    </select>
                </div><!--.form-item-->
                <div class="form-item">
                	<label>Llegada</label>
                    <input id="datepicker_from" type="text" class="calendar" />
                    <div class="clearfix"></div>
                </div><!--.form-item-->
                <div class="form-item">
                	<label>Salida</label>
                    <input id="datepicker_to" type="text" class="calendar" />
                    <div class="clearfix"></div>
                </div><!--.form-item-->
                <input type="hidden" name="RES_CHECK_IN" id="date_from" />                
          			<input type="hidden" name="RES_CHECK_OUT" id="date_to" /> 
          			<input type="hidden" name="RES_NIGHTS"   id="nights" value="1" />
                <div class="form-item">
                    <select class="sb_half" id="select_room" name="RES_ROOMS_QTY" id="RES_ROOMS_QTY">
						<option value="1">1 Habitacion</option>
						<option value="2">2 Habitaciones</option>
						<option value="3">3 Habitaciones</option>
                    </select>
                    <select class="sb_half last" name="RES_ROOM_1_ADULTS_QTY" id="RES_ROOM_1_ADULTS_QTY">
						<option value="1">1 Huespedes</option>
						<option value="2" selected="selected">2 Huespedes</option>
						<option value="3">3 Huespedes</option>
					</select>
                    <div class="clearfix"></div>
                </div><!--.form-item-->
                <div class="form-item room2" style="display:none">                
                    <select class="sb_half last alone" name="RES_ROOM_2_ADULTS_QTY" id="RES_ROOM_2_ADULTS_QTY">
          						<option value="1">1 Huespedes</option>
          						<option value="2" selected="selected">2 Huespedes</option>
          						<option value="3">3 Huespedes</option>
          					</select> 
                    <div class="clearfix"></div>           
                </div>
                <div class="form-item room3" style="display:none">
                    <select class="sb_half last alone" name="RES_ROOM_3_ADULTS_QTY" id="RES_ROOM_3_ADULTS_QTY">
          						<option value="1">1 Huespedes</option>
          						<option value="2" selected="selected">2 Huespedes</option>
          						<option value="3">3 Huespedes</option>
          					</select> 
                    <div class="clearfix"></div>           
                </div>
                
                <div class="form-item">
                	<label class="l_min">Codigo Promocional</label>
                    <input type="text" name="RES_SPECIAL_CODE" />
                    <div class="clearfix"></div>
                </div><!--.form-item-->
                <input type="hidden" name="RES_LANGUAGE" value="SP">
          			<input type="hidden" name="RES_COUNTRY_CODE" value="US">
          			<input type="hidden" name="RES_STATE_CODE" value="">
          			<input type="hidden" name="<?=rand();?>
" value="<?=rand();?>">
                <div class="form-submit">
                	<input type="submit" value="VER DISPONIBILIDAD" />
                </div><!--.form-submit-->
            </form>
        </div><!--.sb_block-->
        <div class="sb_block">
        	<div class="contacts">
                Para asistencia con reservaciones,<br />
                favor llamar:<br />
                USA 1 866 540 25 85<br/>
                Canada 1 866 451 15 92<br/>
                Mexico 01 800 966 36 70<br/>
                UK 0 800 051 6244
            </div><!--.contacts-->
        </div><!--.sb_block-->
    </div><!--#sidebar-->
    <div class="clearfix"></div>
</div><!--#container-->
</div><!-- #wrapper -->

<div id="footer">
	&copy; 2012 Excellence Resorts
</div><!-- #footer -->

</body>
</html>