<?php

$RES_ID = isset($_POST['RES_ID']) ? $_POST['RES_ID'] : $RES_ID;
$RES_TABLE = isset($_POST['RES_TABLE']) ? $_POST['RES_TABLE'] : $RES_TABLE;

if ($isCancelled) {
	$NAVISION_STATUS = "ELIMINAR";
} else if ($isUpdate) {
	$NAVISION_STATUS = "UPDATE";
} else {
	$NAVISION_STATUS = "RESERVAR";    
}
$args = array (
	"ID"=>$RES_ID,
	"RES_TABLE"=>$RES_TABLE,
	"NAVISION_STATUS"=>$NAVISION_STATUS,
);
$result = $clsReserv->modifyReservation($db, $args);

$args["FIELDS"] = "ID, NUMBER, ARRAY, NAVISION_STATUS, NAVISION_SENT, NAVISION_RESULT, NAVISION_CANCEL";

$RSET = $clsReserv->getReservationById($db, $args);
$RECORD = $db->fetch_array($RSET['rSet']);

include_once $_SERVER['DOCUMENT_ROOT']."/ibe/api/navision/make.php";