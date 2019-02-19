<?
/*
 * Revised: Jul 15, 2011
 *          Jan 03, 2017
 */

class authentication {

    function isAuthenticated() {
        return (isset($_SESSION['AUTHENTICATION'])) ? true : false;
    }

    function isLoginPage() {
        return (strpos($_SERVER['SCRIPT_NAME'], "login.php")) ? true : false;
    }

    function logOff() {
        unset($_SESSION['AUTHENTICATION']);
        if (isset($_SESSION['AVAILABILITY'])) unset($_SESSION['AVAILABILITY']);
    }

    function login($db, $arg) {
        global $clsUsers;
        global $clsSetup;
        global $clsGlobal;
        global $PROP_ID;
        extract($arg);

        $this->logOff();

        $query = "SELECT * FROM USERS WHERE STATUS='1' AND USERNAME='".$P_ADMIN_USER."' AND PASSWORD='".$P_ADMIN_PWD."'";
        $arg = array('query' => $query);
        $rs = dbQuery($db, $arg);

        if ( $rs['iCount'] != 0 ) {
            // Check case sensitivity
            $DATA = $db->fetch_array($rs['rSet']);
            if (strcmp($DATA['PASSWORD'],$P_ADMIN_PWD) == 0) {
                $result = true;
                $_SESSION['AUTHENTICATION'] = $clsGlobal->cleanUp_rSet_Array($DATA);
                $_SESSION['AUTHENTICATION']['PROPERTIES'] = $clsUsers->getProperties($db, array("USER_ID"=>$DATA['ID'],"asArray"=>true));
            } else {
                $result = false;
            }
        } else {
            $result = false;
        }

        return $result;
    }
}
global $clsAuthentication;
$clsAuthentication = new authentication;
?>