<?
/*
 * Revised: Oct 14, 2011
 */

if (!is_array($_REQUEST)) $_REQUEST = array();

$ID = isset($_REQUEST['ID']) ? $_REQUEST['ID'] : "0";

$SUBMIT = isset($_REQUEST['SUBMIT']) ? $_REQUEST['SUBMIT'] : "";

$THIS_PAGE = "?PAGE_CODE=migrate_TA&ID={$ID}";
$showPWD = true;
?>

<div class='ListBtns'>
    <table>
    <tr>
        <td><h2>Migrate Travel Agent</h2></td>
    </tr>
    </table>
</div>
<div class="aclear"></div>
<?

if ($SUBMIT=="SUBMIT") {
    $_OLD_ID = $ID;
    include "import.ta.php";
    $TA = $_AGENT;
} else {
    include "ws.migration.server.php";
    $ARGS = array (
        "CODE"=>"getAgentByUsrPwd",
        "OLD_ID"=>$ID,
        "asJSON"=>1
    );
    //print "<pre>";print_r($ARGS);print "</pre>";
    ob_start();
        include "ws.migration.submit.php";
    $_JSON = ob_get_clean();
    //print "=> ".$_JSON;
    $TA = json_decode($_JSON, true);
    //print "<pre>";print_r($TA);print "</pre>";
}
if (is_array($TA) && count($TA)!=0) { 
    if ($SUBMIT=="") { ?>
        <form id="editfrm" method="post" enctype="multipart/form-data" action="<? print $THIS_PAGE ?>">
            <input type="hidden" name="SUBMIT" value="SUBMIT">
            <? include "mods/m.migrate.ta.php"; ?>
            <div style='text-align:center;margin-top:10px'>
                <a onclick="$('#editfrm').submit()"><span class="button key">Migrate</span></a>
            </div>
        </form>
    <? } else { ?>
        <div class="s_success top_msg">Data have been migratted successfully.</div>
        <script>
            document.location.href = "?PAGE_CODE=edit_ta&ID=<? print $TA['ID'] ?>";
        </script>
    <? }
} 

?>
