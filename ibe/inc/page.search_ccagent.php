<?
/*
 * Revised: Aug 09, 2011
 */

ob_start();

if (!is_array($_DATA)) $_DATA = array();

//$EXCEL_NAME = "ccagents.xls";
$isEXPORT = false;
$LASTNAME = isset($_DATA['LASTNAME']) ? $_DATA['LASTNAME'] : "";
$EMAIL = isset($_DATA['EMAIL']) ? $_DATA['EMAIL'] : "";

?>
<div class='ListBtns'>
    <table>
    <tr>
        <td><h2>Call Center Agents</h2></td>
    </tr>
    </table>
</div>
<div class="aclear"></div>

<form id="editfrm" method="post" enctype="multipart/form-data" action="">
    <input type="hidden" name="PAGE_CODE" id="PAGE_CODE" VALUE="search_ccagent">
    <input type="hidden" name="ACTION" id="ACTION" VALUE="">
    <div class="aclear"></div>
    <? include_once "inc/tpl.modules.php"; ?>
</form>

<a name="results"></a>

<fieldset>
    <div class="fieldset">
        <?
        if ($ACTION=="SUBMIT") {
            $RSET = $clsUsers->search($db, array(
                "LASTNAME"=>$LASTNAME,
                "EMAIL"=>$EMAIL
            ));
            ob_start();
            ?>
            <table width="100%" id='searchReserTbl' cellpadding="2" cellspacing="0">
            <tr class="listRowHdr">
                <th>&nbsp;</th>
                <th>Name</th>
                <th>Email</th>
            </tr>
            <?
            $cnt=1;
            $num=1;
            while ($row = $db->fetch_array($RSET['rSet'])) {
                ?>
                <tr class="row<? print $cnt ?>">
                    <td><? print $num ?></td>
                    <td><a href="?PAGE_CODE=edit_ccagent&ID=<? print $row['ID'] ?>"><? print $row['FIRSTNAME']." ".$row['LASTNAME'] ?></a></td>
                    <td><? print $row['EMAIL'] ?></td>
                </tr>
                <?
                $cnt *= -1;
                ++$num;
            }
            ?>
            </table>
            <?
            $REPORT = ob_get_clean();

            if (!$isEXPORT) print $REPORT;

        } else {
        }
        ?>
    </div>
</fieldset>
<?
$OUT = ob_get_clean();

print ($isEXPORT) ? $REPORT : $OUT;

?>

