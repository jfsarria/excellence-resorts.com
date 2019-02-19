<?
/*
 * Revised: Aug 12, 2011
 */
?>
<div class="topnav">
    <? if ($isSUPER || $isUSER_TRANSFERS) print $clsUsers->propertiesDropDown($db) ?>
    <? if (!$isUSER_TRANSFERS) { ?>
    <div class="aright"><a href='index.php'><span class="button topbtn callcenter">Call Center</span></a></div>
    <? } ?>
    <div class="aclear"></div>
</div>
 