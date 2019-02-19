<?
    $_COOKIE['GUEST_LOGGED'] = false;
    if (isset($_COOKIE['RES_GUEST'])) {
        $RES_GUEST = json_decode($_COOKIE['RES_GUEST'],true);
        if (isset($RES_GUEST['ID'])&&(int)$RES_GUEST['ID']!=0) {
            $_COOKIE['GUEST_LOGGED'] = true;
            $_COOKIE['TA_LOGGED'] = false;
        }
    } 

    $_COOKIE['TA_LOGGED'] = false;
    if (isset($_COOKIE['RES_TA'])) {
        $RES_TA = json_decode($_COOKIE['RES_TA'],true);
        if (isset($RES_TA['ID'])&&(int)$RES_TA['ID']!=0) {
            $_COOKIE['TA_LOGGED'] = true;
            $_COOKIE['GUEST_LOGGED'] = false;
        }
    } 

?>