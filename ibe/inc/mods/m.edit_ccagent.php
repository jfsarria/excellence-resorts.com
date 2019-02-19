<?
/*
 * Revised: Aug 10, 2011
 */

ob_start();

$showEdit = true;

if ($SUBMIT=="SUBMIT") {
    $isOk = false;

    $CCAGENT = array(
        "USERNAME"=>$_DATA['CC_AGENT_USERNAME'],
        "PASSWORD"=>$_DATA['CC_AGENT_PASSWORD'],
        "FIRSTNAME"=>$_DATA['CC_AGENT_FIRSTNAME'],
        "LASTNAME"=>$_DATA['CC_AGENT_LASTNAME'],
        "EMAIL"=>$_DATA['CC_AGENT_EMAIL'],
        "ROLE"=>$_DATA['CC_AGENT_ROLE']
    );

    $CCAGENT['ID'] = ((int)$ID!=0) ? $ID : dbNextId($db);
    $THIS_PAGE = "?PAGE_CODE=edit_ccagent&ID={$CCAGENT['ID']}";

    //print "<pre>";print_r($CCAGENT);print "</pre>";

    if (isset($_DATA['CC_AGENT_EMAIL']) && $_DATA['CC_AGENT_EMAIL'] == "") $error['CC_AGENT_EMAIL'] = 'CC_AGENT_EMAIL';

    if (isset($error) && sizeof($error) != 0) {
        include_once "inc/ibe.frm.err.php";
        $SUBMIT="";
    } else {
        $result = $clsUsers->save($db, $CCAGENT); 

        if ((int)$result == 1) {
            include_once "inc/ibe.frm.ok.php";
            $showEdit = false;
        } else {
            print "<div id='s_notice' class='top_msg'>$result</div><br><br>";
        }
    }
}

if (!$isWEBSERVICE) {
    if ($showEdit) {
        include_once "inc/mods/m.ccagent.frm.php";
    } else {
        print "
            <script>
                document.location.href=\"{$THIS_PAGE}\"
            </script>
        ";
    }
}

    
$RESULT = ob_get_clean();

if (!$isWEBSERVICE) print $RESULT;
    
?>