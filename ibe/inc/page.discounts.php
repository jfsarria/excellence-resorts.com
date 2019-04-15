<?
/*
 * Revised: Apr 27, 2011
 */
//print "<pre>";print_r($ACTION);print "</pre>";
$ACTION = ($ACTION=="LIST"&&isset($_GET['ID_CAB'])) ? "EDIT" : $ACTION;

if ($ACTION=="EDIT") {  //aqui manda por defecto, modificar este data para que tambien contenga el valor de las sub reglas,para poder mandar el new y el old.
    //print_r("aqui estoy Editando vacio");
    $RSET = $clsDiscounts->getById($db, array("ID_CAB"=>$ID_CAB));
    if ( $RSET['iCount'] != 0 ) {
        $_DATA[] = $db->fetch_array($RSET['rSet']);
    }
     $RSET = $clsDiscounts->getByIdLin($db, array("ID_CAB"=>$ID_CAB),"");
     //print "<pre> SalidLin";print_r($RSET);print "</pre>";
    if ( $RSET['iCount'] != 0 ) {
        $_Lineas=array();
        while ($row = $db->fetch_array($RSET['rSet'])) {
            $_Lineas[]=$row;
        }
        //$_DATA[] = $db->fetch_array($RSET['rSet']);  //while ($row = $db->fetch_array($RSET['rSet'])) {
        $_DATA['LINMOD']=$_Lineas;
    }
    //print "<pre> <H1>RECORD PARA MODIFICAR: </H1>";print_r($_DATA);print "</pre>";
}


if ($ACTION=="SAVE") {
    //print "<pre><h1>save array </h1>";print_r($_DATA);print "</pre>";  //ver que es lo que envia data despues de pasar por la session de los campos en linea, ver introducir el tabular
//exit;
    if (isset($_DATA['NAME_'.$_IBE_LANG]) && $_DATA['NAME_'.$_IBE_LANG] == "") $error['NAME'] = 'NAME_'.$_IBE_LANG;

    if (isset($error) && sizeof($error) != 0) {
        include_once "inc/ibe.frm.err.php";
        if (isset($wasNEW) && $wasNEW) $isNEW = true;
    } else {
        if (!isset($_DATA['ID_CAB']) || (int)$_DATA['ID_CAB'] == 0) {
            $ID_CAB = dbNextIdMod($db);
            $_DATA['ID_CAB'] = $ID_CAB;
        }
        //$_DATA['UPS_FOLDER'] = "/ibe/ups/rooms/";
        if (strtotime($_DATA['WIN_FROM']) <= strtotime($_DATA['WIN_TO'])) {
            if (strtotime($_DATA['CAB_FROM']) <= strtotime($_DATA['CAB_TO'])) {
                $result = "";

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

                /*
                    16/10/18
                    validacion minimo y maximo de noches
                */
                if (intval($_DATA['MIN_NIGHT']) != 0 || intval($_DATA['MAX_NIGHT']) != 0) {
                    if (intval($_DATA['MIN_NIGHT']) <= intval($_DATA['MAX_NIGHT'])) {
                        $result = (empty($result)) ? "VALID" : $result;//para no sobreescribir
                    } else {
                        $result = "Invalid MIN / MAX Nights";
                    }
                }

                if (intval($_DATA['MIN_ROOM']) != 0 || intval($_DATA['MAX_ROOM']) != 0) {
                    if (intval($_DATA['MIN_ROOM']) <= intval($_DATA['MAX_ROOM'])) {
                        $result = (empty($result)) ? "VALID" : $result;//para no sobreescribir
                    } else{
                        $result = "Invalid MIN / MAX Rooms";
                    }
                }

                if ($result == "VALID") {
                    #$result = true;
                    #echo "Corecto.";
                    $result = $clsDiscounts->save($db, $_DATA);
                }
            } else {
                $result = "Travel dates range is invalid";
            }
        } else {
            $result = "Booking dates range is invalid";
        }

        if ((int)$result == 1) {
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
            print "<div id='s_notice' class='top_msg s_notice'>$result</div><br><br>";
        }
        
        $RSET = $clsDiscounts->getById($db, array("ID_CAB"=>$ID_CAB));
        if ( $RSET['iCount'] != 0 ) {
            $_DATA[] = $db->fetch_array($RSET['rSet']);
        }
         $RSET = $clsDiscounts->getByIdLin($db, array("ID_CAB"=>$ID_CAB),"");
         //print "<pre> SalidLin";print_r($RSET);print "</pre>";
        if ( $RSET['iCount'] != 0 ) {
            $_Lineas=array();
            while ($row = $db->fetch_array($RSET['rSet'])) {
                $_Lineas[]=$row;
            }
            //$_DATA[] = $db->fetch_array($RSET['rSet']);  //while ($row = $db->fetch_array($RSET['rSet'])) {
            $_DATA['LINMOD']=$_Lineas;
        }
    }
}

if ($ACTION=="LIST") { 
    include_once "page.discounts.list.php";
} else { ?>
    <form id="editfrm" method="post" enctype="multipart/form-data">
        <input type="hidden" name="ACTION" id="ACTION" VALUE="SAVE">
        <input type="hidden" name="PROP_ID" id="PROP_ID" VALUE="<? print (isset($PROP_ID)) ? $PROP_ID : "1" ?>">
        <input type="hidden" name="ID_CAB" id="ID_CAB" VALUE="<? print (isset($ID_CAB)) ? $ID_CAB : "0" ?>">
        <div class='frmBtns' style="padding-bottom:0;">
            <a onclick="$('#editfrm').submit()"><span class="button key">Submit</span></a>
            <a href="?PAGE_CODE=discounts&PROP_ID=<? print (isset($PROP_ID)) ? $PROP_ID : "1" ?>"><span class="button plain">Go Back</span></a>
        </div>
        <div class="aclear"></div>
        <? include_once "inc/tpl.modules.php"; ?>
        <div class='frmBtns'>
            <a onclick="$('#editfrm').submit()"><span class="button key">Submit</span></a>
        </div>
    </form>
<? } ?>