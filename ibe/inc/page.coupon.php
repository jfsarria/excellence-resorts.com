<?
/*
 * Revised: Apr 27, 2011
 */

$ACTION = ($ACTION == "LIST" && isset($_GET['ID_CAB'])) ? "EDIT" : $ACTION;

if ($ACTION == "EDIT") {  //aqui manda por defecto, modificar este data para que tambien contenga el valor de las sub reglas,para poder mandar el new y el old.
    //print_r("aqui estoy Editando vacio");
    $RSET = $clsCoupon->getById($db, array("ID_CAB" => $ID_CAB));
    if ( $RSET['iCount'] != 0 ) {
        $_DATA[] = $db->fetch_array($RSET['rSet']);
    }

    $RSET = $clsCoupon->getByIdLin($db, array("ID_CAB"=>$ID_CAB),"");
    //print "<pre> SalidLin";print_r($RSET);print "</pre>";
    
    if ( $RSET['iCount'] != 0 ) {
        $_Lineas = array();
        while ($row = $db->fetch_array($RSET['rSet'])) {
            $_Lineas[] = $row;
        }
        //$_DATA[] = $db->fetch_array($RSET['rSet']);  //while ($row = $db->fetch_array($RSET['rSet'])) {
        $_DATA['LINMOD'] = $_Lineas;
    }
    //print "<pre> <H1>RECORD PARA MODIFICAR: </H1>";print_r($_DATA);print "</pre>";
}


if ($ACTION == "SAVE") {
    //print "<pre>";print_r($_DATA);print "</pre>"; 
    //ver que es lo que envia data despues de pasar por la session de los campos en linea, ver introducir el tabular

    if (isset($_DATA['NAME_'.$_IBE_LANG]) && $_DATA['NAME_'.$_IBE_LANG] == "") $error['NAME'] = 'NAME_'.$_IBE_LANG;

    if (isset($error) && sizeof($error) != 0) {
        include_once "inc/ibe.frm.err.php";
        if (isset($wasNEW) && $wasNEW) $isNEW = true;
    } else {

        include_once "inc/xlsxreader.php";
        if ((!isset($_DATA['ID_CAB']) || (int)$_DATA['ID_CAB'] == 0) &&
            (!isset($_POST['ID_CAB']) || (int)$_POST['ID_CAB'] == 0)) {
            $ID_CAB = dbNextIdMod($db);
            $_DATA['ID_CAB'] = $ID_CAB;
        }
        //$_DATA['UPS_FOLDER'] = "/ibe/ups/rooms/";

        //print "<pre>";print_r($_POST);print "</pre>";
        //print "<pre>";print_r($_DATA);print "</pre>";
        $rel_ = (isset($_DATA['RELACION']) ? $_DATA['RELACION'] : 0);
        $result = "";
        if ($rel_ == 0) {
            if ((int)$_DATA['ID_CAB'] != 0) {
                $result1 = $clsCoupon->getById($db, $_DATA);
            } else $result1['iCount'] = 0;
            
            if ( $result1['iCount'] == 0 ) {
                if ($_DATA['multicode'] == "unicode") {
                    $max_size = 1000000 * 100; //100mb
                    if ($_FILES['excel_file']['type'] == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
                        if (intval($_FILES['excel_file']['size']) <= $max_size) {
                            $ignored = array();
                            $xls = new XLSXReader($_FILES['excel_file']['tmp_name']);
                            foreach ($xls->sharedStrings as $key => &$mail) {
                                if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                                    $ignored[] = $mail;
                                    array_splice($xls->sharedStrings, $key, 1);
                                }
                            }

                            $_DATA['mails'] = $xls->sharedStrings;
                            $_DATA['INVENTORY'] = count($xls->sharedStrings);
                        } else {
                            $result = "Maximum file size is 100Mb and only .XLSX extension is allowed";
                        }
                    } else if (empty($_FILES['excel_file']['type'])) {
                        #$result = "Maximum file size is 100Mb and only .XLSX extension is allowed";
                        if (!empty($_DATA['INVENTORY'])) {
                            $_DATA['mails'] = array();
                        } else {
                            $result = "Provide a quantity";
                        }
                    } else {
                        $result = "Only .XLSX extension is allowed";
                    }

                    /*
                        16/10/18
                        validacion descuentos mayores al CAP
                    */
                    if ($_DATA['SYMBOL'] == "%") {
                        $limits = $clsSetup->getLimitmprice($db);
                        $limitprice = $limits[$_DATA["PROP_ID"]];

                        if (intval($_DATA['VALUE']) <= intval($limitprice)) {
                            $result = (empty($result)) ? "VALID" : $result;//para no sobreescribir
                        } else {
                            $result = "Discount percentage cannot be major that configured CAP";
                        }
                    } else {
                        $result = (empty($result)) ? "VALID" : $result;//para no sobreescribir
                    }

                    if (strtotime($_DATA['WIN_FROM']) <= strtotime($_DATA['WIN_TO'])) {
                        if (strtotime($_DATA['CAB_FROM']) <= strtotime($_DATA['CAB_TO'])) {
                            #$result = $clsCoupon->save($db, $_DATA);
                            $result = (empty($result)) ? "VALID" : $result;//para no sobreescribir
                        } else {
                            $result = "Travel dates range is invalid";
                        }
                    } else {
                        $result = "Booking dates range is invalid";
                    }
                } else {
                    /*
                        16/10/18
                        validacion descuentos mayores al CAP
                    */
                    if ($_DATA['SYMBOL'] == "%") {
                        $limits = $clsSetup->getLimitmprice($db);
                        $limitprice = $limits[$_DATA["PROP_ID"]];

                        if (intval($_DATA['VALUE']) <= intval($limitprice)) {
                            $result = "VALID";
                        } else {
                            $result = "Discount percentage cannot be major that configured CAP";
                        }
                    } else {
                        $result = "VALID";
                    }

                    if (strtotime($_DATA['WIN_FROM']) <= strtotime($_DATA['WIN_TO'])) {
                        if (strtotime($_DATA['CAB_FROM']) <= strtotime($_DATA['CAB_TO'])) {
                            #$result = $clsCoupon->save($db, $_DATA);
                            $result = (empty($result)) ? "VALID" : $result;//para no sobreescribir
                        } else {
                            $result = "Travel dates range is invalid";
                        }
                    } else {
                        $result = "Booking dates range is invalid";
                    }
                }

                if ($result == "VALID") {
                    #$result = true;
                    $result = $clsCoupon->save($db, $_DATA);
                } else {
                    $ID_CAB = 0;
                }
            } else {
                $result = "";
                $result2 = $clsCoupon->getByIdInventory($db, $_DATA, "");

                $countApplied = 0;
                foreach ($result2 as $lin) {
                    if ($lin['IS_APPLIED'] == 1) {
                        $countApplied++;
                    }
                }

                if ($countApplied == 0) {
                    if ($_DATA['multicode'] == "unicode") {
                        $max_size = 1000000 * 100; //100mb
                        if ($_FILES['excel_file']['type'] == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
                            if (intval($_FILES['excel_file']['size']) <= $max_size) {
                                $ignored = array();
                                $xls = new XLSXReader($_FILES['excel_file']['tmp_name']);
                                foreach ($xls->sharedStrings as $key => &$mail) {
                                    if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                                        $ignored[] = $mail;
                                        array_splice($xls->sharedStrings, $key, 1);
                                    }
                                }

                                $_DATA['mails'] = $xls->sharedStrings;
                                $_DATA['INVENTORY'] = count($xls->sharedStrings);
                            } else {
                                $result = "Maximum file size is 100Mb";
                            }
                            
                        } else if (empty($_FILES['excel_file']['type'])) {
                            #$result = "Maximum file size is 100Mb and only .XLSX extension is allowed";
                            if (!empty($_DATA['INVENTORY'])) {
                                $_DATA['mails'] = array();
                            } else {
                                $result = "Provide a quantity";
                            }
                        } else {
                            $result = "Only .XLSX extension is allowed";
                        }

                        /*
                            16/10/18
                            validacion descuentos mayores al CAP
                        */
                        if ($_DATA['SYMBOL'] == "%") {
                            $limits = $clsSetup->getLimitmprice($db);
                            $limitprice = $limits[$_DATA["PROP_ID"]];

                            if (intval($_DATA['VALUE']) <= intval($limitprice)) {
                                $result = (empty($result)) ? "VALID" : $result;//para no sobreescribir
                            } else {
                                $result = "Discount percentage cannot be major that configured CAP";
                            }
                        } else {
                            $result = (empty($result)) ? "VALID" : $result;//para no sobreescribir
                        }

                        if (strtotime($_DATA['WIN_FROM']) <= strtotime($_DATA['WIN_TO'])) {
                            if (strtotime($_DATA['CAB_FROM']) <= strtotime($_DATA['CAB_TO'])) {
                                #$result = $clsCoupon->save($db, $_DATA);
                                $result = (empty($result)) ? "VALID" : $result;//para no sobreescribir
                            } else {
                                $result = "Travel dates range is invalid";
                            }
                        } else {
                            $result = "Booking dates range is invalid";
                        }
                    } else {
                        /*
                            16/10/18
                            validacion descuentos mayores al CAP
                        */
                        if ($_DATA['SYMBOL'] == "%") {
                            $limits = $clsSetup->getLimitmprice($db);
                            $limitprice = $limits[$_DATA["PROP_ID"]];

                            if (intval($_DATA['VALUE']) <= intval($limitprice)) {
                                $result = "VALID";
                            } else {
                                $result = "Discount percentage cannot be major that configured CAP";
                            }
                        } else {
                            $result = "VALID";
                        }

                        if (strtotime($_DATA['WIN_FROM']) <= strtotime($_DATA['WIN_TO'])) {
                            if (strtotime($_DATA['CAB_FROM']) <= strtotime($_DATA['CAB_TO'])) {
                                #$result = $clsCoupon->save($db, $_DATA);
                                $result = (empty($result)) ? "VALID" : $result;//para no sobreescribir
                            } else {
                                $result = "Travel dates range is invalid";
                            }
                        } else {
                            $result = "Booking dates range is invalid";
                        }
                    }
                } else {
                    $result = "Coupons are not modifiable.";
                }

                if ($result == "VALID") {
                    #$result = true;
                    $result = $clsCoupon->save($db, $_DATA);
                }
            }
        } else {
           $result = $clsCoupon->addNewRelation($db, $_DATA) ;
        }
        
        if (!empty($ignored)) {
            #include_once "inc/ibe.frm.ok.mails-exceptions.php";
            echo "<div class='s_notice top_msg'>".
                    "Data have been saved successfully, with these emails ignored:<br />";
            #var_dump($ignored);
            foreach ($ignored as $email) {
                echo "<strong>".$email."</strong><br />";
            }
            echo "</div>";
        } else if ((int)$result == 1) {
            // Upload Images & Videos 
            //foreach ($_FILES as $_KEY => $_FILE) $clsImage->upload($_KEY, $_DATA['UPS_FOLDER'], $ROOM_ID);
            //$isMetaIO = true;
            include_once "inc/ibe.frm.ok.php";
            
            //print "
            //    <script>
            //        document.location.href = '?PAGE_CODE={$_DATA['TYPE']}&PROP_ID={$PROP_ID}&ID_CAB={$_DATA['ID_CAB']}';
            //    </script>
            //";
        } else {
            print "<div class='s_notice top_msg'>$result</div><br><br>";
        }

        $RSET = $clsCoupon->getById($db, array("ID_CAB" => $ID_CAB));
        if ( $RSET['iCount'] != 0 ) {
            $_DATA[] = $db->fetch_array($RSET['rSet']);
        }

        $RSET = $clsCoupon->getByIdLin($db, array("ID_CAB"=>$ID_CAB),"");
        //print "<pre> SalidLin";print_r($RSET);print "</pre>";
        
        if ( $RSET['iCount'] != 0 ) {
            $_Lineas = array();
            while ($row = $db->fetch_array($RSET['rSet'])) {
                $_Lineas[] = $row;
            }
            //$_DATA[] = $db->fetch_array($RSET['rSet']);  //while ($row = $db->fetch_array($RSET['rSet'])) {
            $_DATA['LINMOD'] = $_Lineas;
        }
    }
}


if ($ACTION == "LIST") { 
    include_once "page.coupon.list.php";
} else { ?>
    <form id="editfrm" method="post" enctype="multipart/form-data">
        <input type="hidden" name="ACTION" id="ACTION" VALUE="SAVE">
        <input type="hidden" name="PROP_ID" id="PROP_ID" VALUE="<? print (isset($PROP_ID)) ? $PROP_ID : "1" ?>">
        <input type="hidden" name="ID_CAB" id="ID_DOC" VALUE="<? print (isset($ID_CAB)) ? $ID_CAB : "0" ?>">
        <input type="hidden" name="export_excel" id="export_excel" VALUE="<? print (isset($export_excel)) ? $export_excel : "0" ?>">
        <div class='frmBtns' style="padding-bottom:0;">
            <a onclick="$('#editfrm').submit()"><span class="button key">Submit</span></a>
            <a href="?PAGE_CODE=coupon&PROP_ID=<? print (isset($PROP_ID)) ? $PROP_ID : "1" ?>">
                <span class="button plain">Go Back</span>
            </a>
        </div>
        <div class="aclear"></div>
        <? include_once "inc/tpl.modules.php"; ?>
        <div class='frmBtns'>
            <a onclick="$('#export_excel').val('0');$('#ACTION').val('SAVE');$('#editfrm').submit()">
                <span class="button key">
                    Submit
                </span>
            </a>
        </div>
    </form>
<? } ?>