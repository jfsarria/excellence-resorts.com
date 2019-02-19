<?
/*
 * Revised: Jul 22, 2016
 */

$MONTHSTR = array("","January","February","March","April","May","June","July","August","September","October","November","December");

while ($row = $db->fetch_array($RSET['rSet'])) {
    $MONTH = $row['MONTH'];
    $_DATA[$MONTH] = $row["MONTHLY"];
}
//print "<pre>";print_r($_DATA);print "</pre>";


?>
<fieldset>
    <legend><?=$_GET['YEAR']?> Monthly Markup</legend>
    <br>
    <div class="fieldset">
        <div class="label">
            <table width="100%" border="0" cellpadding="3"><tr>
            <?
            $cnt=0;
            for ($MONTH=1; $MONTH<=12; ++$MONTH) {
                $MARKUP = isset($_DATA[$MONTH]) ? $_DATA[$MONTH] : "";
                print "
                  <td width='20%' align='right'>{$MONTHSTR[$MONTH]}</td>
                  <td width='10%' align='left'><input type='text' name='MARKUP_{$MONTH}' value='$MARKUP' class='small'>%</td>
                ";
                if (++$cnt%3==0) {
                  $cnt=0;
                  print "</tr><tr>";
                } else {
                  print "<td width='3%'>&nbsp;</td>";
                }
            }
            ?>
            </tr></table>
        </div>
    </div>
</fieldset>
