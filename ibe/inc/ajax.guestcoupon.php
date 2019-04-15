<?
/*
 * Revised: Jun 23, 2011
 */

$GEO = isset($_GET['GEO']) ? $_GET['GEO'] : "";
$valor="";

?>

   <!-- <legend>Select Email Address&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0)" onclick='ibe.flashsale.close()'><img src="css/img/cross.png" width="16" height="16" border="0" alt=""></a></legend>-->
   <div id="loading-making-booking" style='display:none;'>
         <img src="img/loading-small.gif">
     </div>
    <legend>Se Crearan <? print_r($clsCoupon->get_count_guest($db,"$GEO","count")); ?> Cupones para <?print ($GEO=="--"?"Rest of the world":$GEO)?></legend>
 
<?
$OUT = ob_get_clean();

print $OUT;

?>
