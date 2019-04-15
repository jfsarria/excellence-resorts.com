<?
/*
 * Revised: Mar 23, 2012
 *          Jul 07, 2017
 */

//$clsGlobal->sendEmail(array("SUBJECT"=>"1","MESSAGE"=>"","FROM"=>"","TO"=>"JUAN.SARRIA@EVERLIVESOLUTIONS.COM"));

//print "<h1>m.reserv.confirmation.php</h1>";

if (!isset($_SESSION['AVAILABILITY']['RESERVATION'])) {
    
    print "<DIV>RESERVATION NOT FOUND</DIV>";

} else {
    /*
    ob_start();print "<pre>";print_r($_SESSION['AVAILABILITY']);print "</pre>";$DEBUG = ob_get_clean();
    $clsGlobal->sendEmail(array("SUBJECT"=>"DEBUG 1","MESSAGE"=>$DEBUG,"FROM"=>"","TO"=>"JUAN.SARRIA@EVERLIVESOLUTIONS.COM"));
    */
    extract($_SESSION['AVAILABILITY']);

    $cleanUpTransfers = false;
    $isResending = (isset($RESENDING)&&(int)$RESENDING==1) ? true : false;
    $isOnlyPrefer = isset($isOnlyPrefer) ? $isOnlyPrefer : false;
    $isPreStay = isset($isPreStay) ? $isPreStay : false;
    $isPostStay = isset($isPostStay) ? $isPostStay : false;
    $isRebooking = isset($RES_REBOOKING) && is_array($RES_REBOOKING) && isset($RES_REBOOKING['RES_NUM']) && $RES_REBOOKING['RES_NUM']!="";
    $isCancelled = isset($RESERVATION['STATUS_STR']) && $RESERVATION['STATUS_STR']=="cancelled";
    $isUpdate = $_PAGE_CODE=="ws.updateGuest" || $_PAGE_CODE=="ws.updatePayment" || $_PAGE_CODE=="ws.updateComments" || $_PAGE_CODE=="ws.updateOptionals" || ($_PAGE_CODE=="edit_reserv" && !$isCancelled);

    $isTA = $_SESSION['AVAILABILITY']['RESERVATION']['FORWHOM']['RES_TO_WHOM']=="TA" && (int)$_SESSION['AVAILABILITY']['RESERVATION']['FORWHOM']["RES_GUEST_ID"] != (int)$_SESSION['AVAILABILITY']['RESERVATION']['FORWHOM']["RES_TA_ID"];
    $TA = ($isTA) ? $clsTA->get($db, array("ID"=>$_SESSION['AVAILABILITY']['RESERVATION']['FORWHOM']['RES_TA_ID'])) : array();

    $isFirstTime = !$isResending && !$isCancelled && !$isPreStay && !$isPostStay && !$isUpdate && !$isOnlyPrefer;
	/*
    $HAS_TRANSFER = isset($RESERVATION['TRANSFER_FEE']) && (int)$RESERVATION['TRANSFER_FEE']!=0 && isset($RESERVATION['TRANSFER_CAR']) && (int)$RESERVATION['TRANSFER_CAR']!=0;
    $CANCEL_TRANSFER = (!$HAS_TRANSFER && !$isFirstTime && isset($RESERVATION['TRANSFER_FEE']) && isset($RESERVATION['TRANSFER_CAR']) && isset($RESERVATION['TRANSFER_TYPE']) && !empty($RESERVATION['TRANSFER_TYPE'])) || (isset($RESERVATION['TRANSFER_TYPE']) && $RESERVATION['TRANSFER_TYPE']=="CANCELLED");
    $HAD_TRANSFER = isset($RES_REBOOKING['TRANSFER_FEE']) && (int)$RES_REBOOKING['TRANSFER_FEE']!=0;
	*/
    $HAS_TRANSFER = isset($RESERVATION['TRANSFER_CAR']) && (int)$RESERVATION['TRANSFER_CAR']!=0;
    $CANCEL_TRANSFER = (!$HAS_TRANSFER && !$isFirstTime && isset($RESERVATION['TRANSFER_FEE']) && isset($RESERVATION['TRANSFER_CAR']) && isset($RESERVATION['TRANSFER_TYPE']) && !empty($RESERVATION['TRANSFER_TYPE'])) || (isset($RESERVATION['TRANSFER_TYPE']) && $RESERVATION['TRANSFER_TYPE']=="CANCELLED");
    $HAD_TRANSFER = isset($RES_REBOOKING['TRANSFER_CAR']) && (int)$RES_REBOOKING['TRANSFER_CAR']!=0;
    
    //$clsGlobal->sendEmail(array("SUBJECT"=>$CANCEL_TRANSFER?"CANCEL_TRANSFER 1: YES":"CANCEL_TRANSFER 1: NO","MESSAGE"=>"","FROM"=>"","TO"=>"JUAN.SARRIA@EVERLIVESOLUTIONS.COM"));
    //$clsGlobal->sendEmail(array("SUBJECT"=>$HAS_TRANSFER?"HAS_TRANSFER 1: YES":"HAS_TRANSFER 1: NO","MESSAGE"=>"","FROM"=>"","TO"=>"JUAN.SARRIA@EVERLIVESOLUTIONS.COM"));

    if ($isCancelled) {
        $CANCEL_TRANSFER = $HAS_TRANSFER ? true : $CANCEL_TRANSFER;
        $HAS_TRANSFER = false;
    }
    $EMAIL_TRANSFER = $HAS_TRANSFER || $CANCEL_TRANSFER;
    /*
    $clsGlobal->sendEmail(array("SUBJECT"=>"TRANSFER_TYPE: ".$RESERVATION['TRANSFER_TYPE'],"MESSAGE"=>"","FROM"=>"","TO"=>"JUAN.SARRIA@EVERLIVESOLUTIONS.COM"));
    $clsGlobal->sendEmail(array("SUBJECT"=>"TRANSFER_FEE: ".$RESERVATION['TRANSFER_FEE'],"MESSAGE"=>"","FROM"=>"","TO"=>"JUAN.SARRIA@EVERLIVESOLUTIONS.COM"));    
    $clsGlobal->sendEmail(array("SUBJECT"=>"TRANSFER_CAR: ".$RESERVATION['TRANSFER_CAR'],"MESSAGE"=>"","FROM"=>"","TO"=>"JUAN.SARRIA@EVERLIVESOLUTIONS.COM"));    
    $clsGlobal->sendEmail(array("SUBJECT"=>$CANCEL_TRANSFER?"CANCEL_TRANSFER: YES":"CANCEL_TRANSFER: NO","MESSAGE"=>"","FROM"=>"","TO"=>"JUAN.SARRIA@EVERLIVESOLUTIONS.COM"));
    $clsGlobal->sendEmail(array("SUBJECT"=>$HAS_TRANSFER?"HAS_TRANSFER: YES":"HAS_TRANSFER: NO","MESSAGE"=>"","FROM"=>"","TO"=>"JUAN.SARRIA@EVERLIVESOLUTIONS.COM"));
    $clsGlobal->sendEmail(array("SUBJECT"=>$HAD_TRANSFER?"HAD_TRANSFER: YES":"HAD_TRANSFER: NO","MESSAGE"=>"","FROM"=>"","TO"=>"JUAN.SARRIA@EVERLIVESOLUTIONS.COM"));
    $clsGlobal->sendEmail(array("SUBJECT"=>$isFirstTime?"isFirstTime: YES":"isFirstTime: NO","MESSAGE"=>"","FROM"=>"","TO"=>"JUAN.SARRIA@EVERLIVESOLUTIONS.COM"));
    */
    /*
    ob_start();print "<pre>";print_r($_SESSION['AVAILABILITY']);print "</pre>";$SESSION_ARR = ob_get_clean();
    $clsGlobal->sendEmail(array("SUBJECT"=>"SESSION_ARR","MESSAGE"=>$SESSION_ARR,"FROM"=>"","TO"=>"JUAN.SARRIA@EVERLIVESOLUTIONS.COM"));
    $clsGlobal->sendEmail(array("SUBJECT"=>"TRANSFER_TYPE: ".$RESERVATION['TRANSFER_TYPE'],"MESSAGE"=>"","FROM"=>"","TO"=>"JUAN.SARRIA@EVERLIVESOLUTIONS.COM"));
    $clsGlobal->sendEmail(array("SUBJECT"=>$isPreStay?"isPreStay: YES":"isPreStay: NO","MESSAGE"=>"","FROM"=>"","TO"=>"JUAN.SARRIA@EVERLIVESOLUTIONS.COM"));
    $clsGlobal->sendEmail(array("SUBJECT"=>$isPostStay?"isPostStay: YES":"isPostStay: NO","MESSAGE"=>"","FROM"=>"","TO"=>"JUAN.SARRIA@EVERLIVESOLUTIONS.COM"));
    $clsGlobal->sendEmail(array("SUBJECT"=>$HAS_TRANSFER?"HAS_TRANSFER: YES":"HAS_TRANSFER: NO","MESSAGE"=>"","FROM"=>"","TO"=>"JUAN.SARRIA@EVERLIVESOLUTIONS.COM"));
    $clsGlobal->sendEmail(array("SUBJECT"=>$HAD_TRANSFER?"HAD_TRANSFER: YES":"HAD_TRANSFER: NO","MESSAGE"=>"","FROM"=>"","TO"=>"JUAN.SARRIA@EVERLIVESOLUTIONS.COM"));
    $clsGlobal->sendEmail(array("SUBJECT"=>$isFirstTime?"isFirstTime: YES":"isFirstTime: NO","MESSAGE"=>"","FROM"=>"","TO"=>"JUAN.SARRIA@EVERLIVESOLUTIONS.COM"));
    $clsGlobal->sendEmail(array("SUBJECT"=>$CANCEL_TRANSFER?"CANCEL_TRANSFER: YES":"CANCEL_TRANSFER: NO","MESSAGE"=>"","FROM"=>"","TO"=>"JUAN.SARRIA@EVERLIVESOLUTIONS.COM"));
    $clsGlobal->sendEmail(array("SUBJECT"=>$EMAIL_TRANSFER?"EMAIL_TRANSFER: YES":"EMAIL_TRANSFER: NO","MESSAGE"=>"","FROM"=>"","TO"=>"JUAN.SARRIA@EVERLIVESOLUTIONS.COM"));
    $clsGlobal->sendEmail(array("SUBJECT"=>$isResending?"isResending: YES":"isResending: NO","MESSAGE"=>"","FROM"=>"","TO"=>"JUAN.SARRIA@EVERLIVESOLUTIONS.COM"));
    $clsGlobal->sendEmail(array("SUBJECT"=>$isUpdate?"isUpdate: YES":"isUpdate: NO","MESSAGE"=>$_PAGE_CODE,"FROM"=>"","TO"=>"JUAN.SARRIA@EVERLIVESOLUTIONS.COM"));
    */
    if ($HAS_TRANSFER && isset($RESERVATION['TRANSFER_CAR']) && (int)$RESERVATION['TRANSFER_CAR']!=0) {
      $ROUNDT = $RES_LANGUAGE=='EN' ? "Round Trip" : "Viaje Redondo";
      $ONEWAY = $RES_LANGUAGE=='EN' ? "One Way" : "De ida al hotel";
      $TRANSFER_CAR = $clsTransfer->getCarById($db, array("CAR_ID"=>$RESERVATION['TRANSFER_CAR'],"RES_LANGUAGE"=>$RES_LANGUAGE,"getName"=>TRUE));
      $TRANSFER_TYPE = $RESERVATION['TRANSFER_TYPE']=="ONEWAY" ? $ONEWAY : $ROUNDT;
      //$TRANSFER_TYPE .= " *** ".$RES_LANGUAGE;
      $TRANSFER_FEE = "USD $".number_format($RESERVATION['TRANSFER_FEE']);
    } else {
      $TRANSFER_CAR = "";
      $TRANSFER_TYPE = "";
      $TRANSFER_FEE = "";
    }

    if (!function_exists('stylePropertyName'))  {
        function stylePropertyName($NAME, $RES_PROP_ID) {
            $retVal = $NAME;
            /*
            if ($RES_PROP_ID!=4) {
                $retVal = str_replace("Excellence ","<span style='color:#9f7c32;'>Excellence</span> <span style='color:colorName'>", $retVal)."</span>";
                if ($RES_PROP_ID==1) $retVal = str_replace("colorName","#f78214",$retVal);
                if ($RES_PROP_ID==2) $retVal = str_replace("colorName","#eb087e",$retVal);
                if ($RES_PROP_ID==3) $retVal = str_replace("colorName","#8acd24",$retVal);
                if ($RES_PROP_ID==6) $retVal = str_replace("colorName","#754d9f",$retVal);
                if ($RES_PROP_ID==7) $retVal = str_replace("colorName","#23cdb8",$retVal);
            }
            */
            return $retVal;
        }
    }

    if (!function_exists('propertyColor'))  {
        function propertyColor($STR, $RES_PROP_ID) {
            /*
            $COLOR = "";
            if ($RES_PROP_ID==1) $COLOR = "#f78214";
            if ($RES_PROP_ID==2) $COLOR = "#eb087e";
            if ($RES_PROP_ID==3) $COLOR = "#8acd24";
            if ($RES_PROP_ID==6) $COLOR = "#754d9f";
            if ($RES_PROP_ID==7) $COLOR = "#23cdb8";
            if (!empty($COLOR)) {
              $STR = "<span style='color:$COLOR'>{$STR}</span>";
            }
            */
            return $STR;
        }
    }

    if (!function_exists('adjustPropertyName'))  {
        function adjustPropertyName($NAME, $RES_PROP_ID) {
            if ($RES_PROP_ID != 5) {
              $NAME .= " (Adults Only Resort +18 years)";
            }
            return $NAME;
        }
    }

    $STYLED_PROP_NAME = "<span style='font-size:14px'><b>".stylePropertyName($RES_ITEMS['PROPERTY']['NAME'],(int)$RES_PROP_ID)."</b></span>";

    $_NOTE_PROP_NAME = ($isRebooking && !$isCancelled) ? $RES_ITEMS['PROPERTIES'][$RES_REBOOKING['PROP_ID']]['NAME'] : "";

    $RES_NUMBER = $_SESSION['AVAILABILITY']['RESERVATION']['RES_NUMBER'];
    $GUEST_ID = $RESERVATION['FORWHOM']['RES_GUEST_ID'];
    $GUEST = $clsGuest->get($db, array("ID"=>$GUEST_ID));
    $SETUP = $clsSetup->getById($db, array("PROP_ID"=>$RES_PROP_ID,"asArray"=>true));
    $TRASFER = $clsTransfer->getSetUpById($db, array("PROP_ID"=>$RES_PROP_ID,"asArray"=>true));

    $HOME_URL = $RES_LANGUAGE=="EN" ? $SETUP['HOME_URL'] : $SETUP['HOME_URL_SP'];
    $RES_URL = $RES_LANGUAGE=="EN" ? $SETUP['RES_URL'] : $SETUP['RES_URL_SP'];
    $SPA_RES = $SETUP['SPA_RES_'.$RES_LANGUAGE];
    $SPA_URL = $SETUP['SPA_URL_'.$RES_LANGUAGE];
    $MLIST_URL = $SETUP['MLIST_URL_'.$RES_LANGUAGE];
    $DEAR = _l("Dear","Estimado(a)",$RES_LANGUAGE)." "._title($GUEST['TITLE'], $RES_LANGUAGE)." {$GUEST['FIRSTNAME']} {$GUEST['LASTNAME']}";

    ob_start();
        include (isset($isPostStay) && $isPostStay) ? "e.IBE-GP-09.php" : "e.IBE-GP-01.php";
    $_RES_SUMMARY = ob_get_clean();

    /** KEY replacements **/
    $_RES_SUMMARY = str_replace(
        array(
            '[PROPERTY]',
            '[STYLED PROPERTY]',
            '[RESERVATION]',
            '[RESERVATIONS URL]',
            '[ORIGINAL]',
            '[HOME]',
            '[POLICY]',
            '[FEE]',
            '[CANCELLATION DATE]',
            '[SPA RESERVATION]',
            '[SPA URL]',
            '[MAILING LIST]',
            '[ARRIVAL]',
            '[AIRLINE]'
        ),
        array(
            $RES_ITEMS['PROPERTY']['NAME'],
            $STYLED_PROP_NAME,
            $RES_NUMBER,
            "<a href='{$RES_URL}'>{$RES_URL}</a>",
            ((isset($_NOTE_PROP_NAME)&&$_NOTE_PROP_NAME!="") ? " at $_NOTE_PROP_NAME ":"").$RES_REBOOKING['RES_NUM'],
            "<a href='{$HOME_URL}'>{$HOME_URL}</a>",
            $clsReserv->getCancellationModificationPolicy($RES_CHECK_IN, $RES_LANGUAGE),
            ((isset($RESERVATION['FEES'])) ? number_format((int)$RESERVATION['FEES']):""),
            ((isset($RESERVATION['CANCELLED'])) ? date("l, F j, Y", strtotime($RESERVATION['CANCELLED'])):""),
            "<a href='{$SPA_RES}'>{$SPA_RES}</a>",
            "<a href='{$SPA_URL}'>{$SPA_URL}</a>",
            "<a href='{$MLIST_URL}'>{$MLIST_URL}</a>",
            (($RESERVATION['ARRIVAL_TIME']!="")?$RESERVATION['ARRIVAL_TIME']." ".$RESERVATION['ARRIVAL_AMPM']:"--"),
            "{$RESERVATION['AIRLINE']}, Flight Number: {$RESERVATION['FLIGHT']}"
        ),
        $_RES_SUMMARY
    );
    //**

    if (!$isWEBSERVICE) {
        print "<style>.reserv_left_col {width: 780px;}</style>".$_RES_SUMMARY;
    }

    $_EMAIL = array();
    if (!$isCancelled && $isPreStay) {
        $_SUBJECT = _l("Reconfirming Your Reservation","Confirmando su reserva ",$RES_LANGUAGE)." {$RES_NUMBER} "._l("at","yyy",$RES_LANGUAGE)." {$RES_ITEMS['PROPERTY']['NAME']}.";
        // REMIND PRIVATE TRANSFER IF DON'T HAVE IT AND IT'S ACTIVE
        if (!$HAS_TRANSFER) {
          if ((int)$TRASFER['IS_ACTIVE']==1) {
            $TO = trim($_SESSION['AVAILABILITY']['RESERVATION']['GUEST']['EMAIL']);
            $FROM = $RES_ITEMS['PROPERTY']['RES_EMAIL'];
            $TRANSFERS_URL = $TRASFER['TRANSFERS_URL_'.$RES_LANGUAGE];
            $REMIDER = $TRASFER['REMINDER_'.$RES_LANGUAGE];
            $REMIDER = str_replace(
                array(
                    '[DEAR]',
                    '[PROPERTY]',
                    '[PRIVATE TRANSFER URL]'
                ),
                array(
                    $DEAR,
                    $RES_ITEMS['PROPERTY']['NAME'],
                    "<a href='{$TRANSFERS_URL}'>{$TRANSFERS_URL}</a>",
                ),
                $REMIDER
            );
            $REMIDER = htmlspecialchars_decode($REMIDER);
            $REMIDER = str_replace(array("\n","\r\n"),array("<br>","<br>"),$REMIDER);
            $SUBJECT = _l("Airport Transfer for your upcoming stay at ","Transportación privada para su próxima estadia en ",$RES_LANGUAGE).$RES_ITEMS['PROPERTY']['NAME'];
            $clsGlobal->sendEmail(array("SUBJECT"=>$SUBJECT,"MESSAGE"=>$REMIDER,"FROM"=>$FROM,"TO"=>$TO,"PROP_ID"=>$RES_PROP_ID));
          }
        }
    } else if (!$isCancelled && $isPostStay) {
        $_SUBJECT = _l("Thank you for staying with us at","Gracias por hospedarse con nosotros en el",$RES_LANGUAGE)." {$RES_ITEMS['PROPERTY']['NAME']}.";
    } else {
        $_SUBJECT = ($isCancelled?_l("Cancellation Confirmation - ","Confirmación de cancelación - ",$RES_LANGUAGE):_l("Your ","Su ",$RES_LANGUAGE))._l("Reservation","Reservación",$RES_LANGUAGE)." ".$RES_NUMBER." "._l("at","en el",$RES_LANGUAGE)." ".$RES_ITEMS['PROPERTY']['NAME'];
    }

    $_EMAIL['PROP_ID'] = $RES_ITEMS['PROPERTY']['ID'];
    $_EMAIL['FORM'] = $RES_ITEMS['PROPERTY']['RES_EMAIL'];
    $_EMAIL['MESSAGE'] = ($RESERVATION['RES_GUESTMETHOD']=="CC") ? str_replace("[CC_NUMBER]",last4($RESERVATION['PAYMENT']['CC_NUMBER']),$_RES_SUMMARY) : $_RES_SUMMARY;

    if (!$isPreStay && !$isPostStay && !$isOnlyPrefer) {
        /*
         * Send Copy of Reservation Confirmation to Call Center
         */
        $_EMAIL['TO'] = $SETUP['ADMIN_EMAIL'];
        $_EMAIL['SUBJECT'] = ($isResending?_l("Resending","Re enviando",$RES_LANGUAGE)." ":"").($isUpdate ? _l("Copy of Changes to","Copia de cambios a",$RES_LANGUAGE)." " : _l("Copy of","Copia de",$RES_LANGUAGE)." ").(($isRebooking&&!$isPreStay&&!$isPostStay) ? _l("Re-booking","Modificación",$RES_LANGUAGE)." " : "").$_SUBJECT;
        $clsGlobal->sendEmail($_EMAIL);

        /*
         * If rebooking send a note to the original hotel
         */    
        $_NOTE = $_EMAIL;
        if ($isRebooking && !$isCancelled && isset($RES_ITEMS['PROPERTIES'][$RES_REBOOKING['PROP_ID']]['ADMIN_EMAIL'])) {
            if ((int)$RES_REBOOKING['PROP_ID'] != (int)$RES_PROP_ID) {
                $_NOTE['TO'] .= ",".$RES_ITEMS['PROPERTIES'][$RES_REBOOKING['PROP_ID']]['ADMIN_EMAIL'];
                $_NOTE['SUBJECT'] = _l("Note of Rebooking","Nota acerca de los cambios",$RES_LANGUAGE)." {$RES_NUMBER} "._l("from","desde",$RES_LANGUAGE)." {$_NOTE_PROP_NAME} "._l("to","hasta",$RES_LANGUAGE)." {$RES_ITEMS['PROPERTY']['NAME']}.";
                $clsGlobal->sendEmail($_NOTE);
            }
        }
    }

    /*
     * Transfer Emails
     */ 
    $_NOTE = $_EMAIL;
    if (!$isPreStay && !$isPostStay) {

      if (!$EMAIL_TRANSFER) {
          /*
           * If Air send a note
           */    
          if (
              ((trim($RESERVATION['AIRLINE'])!="" || trim($RESERVATION['FLIGHT'])!="") && $isFirstTime)
              || 
              (
                  isset($ORIGINAL_RES)
                  &&  
                      (
                          trim($RESERVATION['AIRLINE'])!=trim($ORIGINAL_RES['RESERVATION']['AIRLINE']) 
                          ||
                          trim($RESERVATION['FLIGHT'])!=trim($ORIGINAL_RES['RESERVATION']['FLIGHT'])
                      )
              )
          ) {
              $_NOTE['TO'] = $SETUP['AIR_EMAIL'];
              $_NOTE['SUBJECT'] = _l("Air transfer reservation","Servicio de transportación para la reservación",$RES_LANGUAGE)." {$RES_NUMBER} "._l("at","en",$RES_LANGUAGE)." {$RES_ITEMS['PROPERTY']['NAME']}.";
              $clsGlobal->sendEmail($_NOTE);
          }
      }

      if ($isTA && count($TA)>0) {
        $_NOTE['TO'] = trim($TA['EMAIL']);
      } else {
        $_NOTE['TO'] = trim($_SESSION['AVAILABILITY']['RESERVATION']['GUEST']['EMAIL']);
      }
      $_NOTE['CC'] = $TRASFER['COMPANY_EMAIL'];//"juan.sarria@everlivesolutions.com";

      if ($HAS_TRANSFER && !$CANCEL_TRANSFER) {
          if ($isFirstTime) {
              $_NOTE['SUBJECT'] = _l("Private transfer for reservation","Servicio Privado de transportación para la reservación",$RES_LANGUAGE)." {$RES_NUMBER} "._l("at","en",$RES_LANGUAGE)." {$RES_ITEMS['PROPERTY']['NAME']}.";
              $_NOTE['MESSAGE'] = $TRASFER['CONFIRM_'.$RES_LANGUAGE];
          } else {
              $_NOTE['SUBJECT'] = ($isResending?_l("Confirming","Confirmación",$RES_LANGUAGE):_l("Changes to","Cambios",$RES_LANGUAGE))._l(" private transfer for reservation"," del servicio Privado de transportación para la reservación",$RES_LANGUAGE)." {$RES_NUMBER} "._l("at","en",$RES_LANGUAGE)." {$RES_ITEMS['PROPERTY']['NAME']}.";
              $_NOTE['MESSAGE'] = $TRASFER['CHANGE_'.$RES_LANGUAGE];
          }
      }

      if ($CANCEL_TRANSFER && !$isResending) {
          $_NOTE['SUBJECT'] = _l("Cancellation of private transfer for reservation","Cancelacion del servicio privado de transportación para la reservación",$RES_LANGUAGE)." {$RES_NUMBER} "._l("at","en",$RES_LANGUAGE)." {$RES_ITEMS['PROPERTY']['NAME']}.";
          $_NOTE['MESSAGE'] = $TRASFER['CANCEL_'.$RES_LANGUAGE];
          $cleanUpTransfers = true;
          //$clsGlobal->sendEmail(array("SUBJECT"=>"CLEAN UP 1","MESSAGE"=>"","FROM"=>"","TO"=>"JUAN.SARRIA@EVERLIVESOLUTIONS.COM"));
      }

      if ($EMAIL_TRANSFER) {

        $TRANSFER_DETAILS = "<b>".($RES_LANGUAGE=="EN"?"Private Airport Transfer Details":"Detalles del Servicio Privado de Transporte")."</b>
          <b>[PROPERTY]</b>

          [TRANSFER TYPE]
          ".($RES_LANGUAGE=="EN"?"Selected Car":"Carro seleccionado").": [TRANSFER CAR]
          ".($RES_LANGUAGE=="EN"?"Transfer Fee":"Costo del transporte").": [TRANSFER FEE]

          ".($RES_LANGUAGE=="EN"?"Arrival airline":"Aerolinea de llegada").": [AIRLINE]
          ".($RES_LANGUAGE=="EN"?"Arrival flight":"Vuelo de llegada").": [FLIGHT]
          ".($RES_LANGUAGE=="EN"?"Arrival date & time":"Día y hora de llegada").": [ARRIVAL]
        ";
        if ($RESERVATION['TRANSFER_TYPE']=="ROUNDT") {
          $TRANSFER_DETAILS .= "
            ".($RES_LANGUAGE=="EN"?"Departure airline":"Aerolinea de salida").": [DEPARTURE_AIRLINE]
            ".($RES_LANGUAGE=="EN"?"Departure flight":"Vuelo de salida").": [DEPARTURE_FLIGHT]
            ".($RES_LANGUAGE=="EN"?"Departure date & time":"Día y hora de salida").": [DEPARTURE]
          ";         
        }
        $_NOTE['MESSAGE'] = str_replace("[TRANSFER_DETAILS]",$TRANSFER_DETAILS,$_NOTE['MESSAGE']);
        $_NOTE['MESSAGE'] = str_replace(
            array(
                '[DEAR]',
                '[PROPERTY]',
                '[RESERVATION]',
                '[RESERVATIONS URL]',
                '[TRANSFER TYPE]',
                '[TRANSFER CAR]',
                '[TRANSFER FEE]',
                '[FLIGHT]',
                '[ARRIVAL]',
                '[AIRLINE]',
                '[DEPARTURE_FLIGHT]',
                '[DEPARTURE]',
                '[DEPARTURE_AIRLINE]'
            ),
            array(
                $DEAR,
                adjustPropertyName($RES_ITEMS['PROPERTY']['NAME'],$RES_ITEMS['PROPERTY']['ID']),
                $RES_NUMBER,
                "<a href='{$RES_URL}'>{$RES_URL}</a>",
                $TRANSFER_TYPE,
                $TRANSFER_CAR,
                $TRANSFER_FEE,
                $RESERVATION['FLIGHT'],
                date("l, F j, Y", strtotime($RES_CHECK_IN)) . " @ ". $RESERVATION['ARRIVAL']." ".$RESERVATION['ARRIVAL_AP'],
                $RESERVATION['AIRLINE'],
                $RESERVATION['DEPARTURE_FLIGHT'],
                date("l, F j, Y", strtotime($RES_CHECK_OUT)) . " @ ". $RESERVATION['DEPARTURE']." ".$RESERVATION['DEPARTURE_AP'],
                $RESERVATION['DEPARTURE_AIRLINE']
            ),
            $_NOTE['MESSAGE']
        );

        $_NOTE['MESSAGE'] = htmlspecialchars_decode($_NOTE['MESSAGE']);
        $_NOTE['MESSAGE'] = str_replace(array("\n","\r\n"),array("<br>","<br>"),$_NOTE['MESSAGE']);

        $clsGlobal->sendEmail($_NOTE);

        //ob_start();print "<pre>";print_r($_NOTE);print "</pre>";$DEBUG = ob_get_clean();
        //$clsGlobal->sendEmail(array("SUBJECT"=>"DEBUG","MESSAGE"=>"$DEBUG","FROM"=>"","TO"=>"JUAN.SARRIA@EVERLIVESOLUTIONS.COM"));

      }

      if ($HAD_TRANSFER) {
        $_NOTE['SUBJECT'] = _l("Cancellation of previous private transfer for reservation","Cancelacion del anterior servicio privado de transportación para la reservación",$RES_LANGUAGE)." {$RES_NUMBER} "._l("at","en",$RES_LANGUAGE)." {$RES_ITEMS['PROPERTY']['NAME']}.";
        $_NOTE['MESSAGE'] = $TRASFER['CANCEL_'.$RES_LANGUAGE];

        $_NOTE['MESSAGE'] = htmlspecialchars_decode($_NOTE['MESSAGE']);
        $_NOTE['MESSAGE'] = str_replace(array("[DEAR]","\n","\r\n"),array($DEAR,"<br>","<br>"),$_NOTE['MESSAGE']);

        //ob_start();print "<pre>";print_r($_NOTE);print "</pre>";$NOTE_ARR = ob_get_clean();
        //$clsGlobal->sendEmail(array("SUBJECT"=>"NOTE","MESSAGE"=>$NOTE_ARR,"FROM"=>"","TO"=>"JUAN.SARRIA@EVERLIVESOLUTIONS.COM"));

        $clsGlobal->sendEmail($_NOTE);

        if (!($isRebooking&&$HAS_TRANSFER)) {
          $cleanUpTransfers = true;
          //$clsGlobal->sendEmail(array("SUBJECT"=>"CLEAN UP 2","MESSAGE"=>"","FROM"=>"","TO"=>"JUAN.SARRIA@EVERLIVESOLUTIONS.COM"));
        }
      }


    }

    /*
     * Send Reservation Confirmation to Guest / TA
     */
    if ($isTA && count($TA)>0) {
        $_EMAIL['TO'] = trim($TA['EMAIL']);
    } else {
        $_EMAIL['TO'] = trim($_SESSION['AVAILABILITY']['RESERVATION']['GUEST']['EMAIL']);
    }

    $_EMAIL['SUBJECT'] = ($isResending?_l("Resending","Re enviando",$RES_LANGUAGE)." ":"").($isUpdate ? _l("Changes to","Cambios a",$RES_LANGUAGE)." " : "").(($isRebooking&&!$isPreStay&&!$isPostStay) ? _l("Re-booking","Modificación",$RES_LANGUAGE)." " : "").$_SUBJECT;

    if ($RESERVATION['RES_GUESTMETHOD']=="CC") $RESERVATION['PAYMENT']['CC_NUMBER'] = last4($RESERVATION['PAYMENT']['CC_NUMBER']);
    $_EMAIL['MESSAGE'] = ($RESERVATION['RES_GUESTMETHOD']=="CC") ? str_replace("[CC_NUMBER]",$RESERVATION['PAYMENT']['CC_NUMBER'],$_RES_SUMMARY) : $_RES_SUMMARY;

    /*
    ob_start();
        print_r($_SESSION['AVAILABILITY']['RESERVATION']);
    $TMP = ob_get_clean();
    $_EMAIL['MESSAGE'] .= "<hr>=><pre>{$TMP}</pre>";
    */

    ////$_EMAIL['SUBJECT'] = $RESERVATION['TRANSFER_TYPE']." - ".$_EMAIL['SUBJECT'];

    if (!$isOnlyPrefer) $clsGlobal->sendEmail($_EMAIL);

    if ($isFirstTime) {
        foreach ($RESERVATION['RES_ROOMS_SELECTED'] as $IND => $ROOM_ID) {
            // SEND EMAIL IN CASE OF LOW INVENTORY
            $PROPERTY = $RES_ITEMS['PROPERTY'];
            $ROOM = $_SESSION['AVAILABILITY']["RES_ROOM_".($IND+1)."_ROOMS"][$ROOM_ID];
            $ADMIN_EMAIL = explode(",", $SETUP['ADMIN_EMAIL']);
            foreach ($ROOM["NIGTHS"] as $DATE => $DATA) {
                $LEFT = (int)$DATA["INVENTORY"]["LEFT"]-1;
                if ($LEFT <= (int)$PROPERTY['INVENTORY_MIN'] ) {
                    $clsGlobal->sendEmail(array(
                        'FORM' => trim($ADMIN_EMAIL[0]),
                        'TO' => $SETUP['INVENTORY_EMAIL'],
                        'SUBJECT' => "{$PROPERTY['NAME']} Low Inventory {$DATE}",
                        'MESSAGE' => "The allotment for the room '{$ROOM['NAME']}' at {$PROPERTY['NAME']} on {$DATE} is now {$LEFT} and has reached its minimum of {$PROPERTY['INVENTORY_MIN']}.",
                        'IS_INTERNAL' => 1
                    ));
                }
                if ($LEFT<=0) {
                    $PROP = array(
                        "CODE" => array($PROPERTY['CODE']),
                        "ID" => array($RES_PROP_ID)
                    );
                    //$clsGlobal->updateMetaIO($RES_CHECK_IN, $PROP);
                }
            }
        }

    }

    $RES_ID = $_SESSION['AVAILABILITY']['RESERVATION']['RES_ID'];
    $RES_TABLE = $_SESSION['AVAILABILITY']['RESERVATION']['RES_TABLE'];

    if ($cleanUpTransfers || $isCancelled) {
      $arg = array(
        "RES_TABLE"=>$RES_TABLE,
        "FIELDS"=>"ID, NUMBER, ARRAY, NAVISION_RESULT",
        "ID"=>$RES_ID
      );

      $RSET = $clsReserv->getReservationById($db, $arg);
      $row = $db->fetch_array($RSET['rSet']);
    }

    if ($cleanUpTransfers) {
      // CLEANUP TRANSFER DATA AFTER CANCELLED
      $ARRAY = $row['ARRAY'];
      $JSON = $clsGlobal->jsonDecode($ARRAY);

      unset($JSON['RESERVATION']['TRANSFER_TYPE']);
      unset($JSON['RESERVATION']['TRANSFER_CAR']);
      unset($JSON['RESERVATION']['TRANSFER_FEE']);

      if ($HAD_TRANSFER) {
        $JSON['RES_REBOOKING']['TRANSFER_FEE'] = "";
        $JSON['RES_REBOOKING']['TRANSFER_CAR'] = "";
      }

      $JSON = $clsGlobal->jsonEncode($JSON);

      //$clsGlobal->sendEmail(array("SUBJECT"=>"CLEAN UP ".$_SESSION['AVAILABILITY']['RESERVATION']['RES_ID']." ".$_SESSION['AVAILABILITY']['RESERVATION']['RES_TABLE']." C:".$RSET['iCount'],"MESSAGE"=>$JSON,"FROM"=>"","TO"=>"JUAN.SARRIA@EVERLIVESOLUTIONS.COM"));

      $result = $clsReserv->modifyReservation($db, array (
          "RES_TABLE"=>$RES_TABLE,
          "ID"=>$RES_ID,
          "ARRAY"=>$JSON
      ));
      
    }

    if ($isFirstTime || $isCancelled || $isUpdate) {
        // CALL NAVISION API
        include "m.navision.php";
    }

}

//print "<pre>";print_r($_SESSION['SENT']);print "</pre>";
