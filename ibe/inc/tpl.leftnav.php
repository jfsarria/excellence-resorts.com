<?
/*
 * Revised: Aug 10, 2011
 *          Jun 07, 2016
 *          Mar 08, 2018
 */

//print $isSUPER ? " ** IS SUPER ** " : "** NO SUPER **";
//print $isSPECIAL ? " ** IS SPECIAL ** " : "** NO SPECIAL **";

if ((!$isSUPER && !$isUSER_TRANSFERS) || $PROP_ID==0) {

  if ($isSPECIAL) { ?>

    <div class="leftnav byall regular">
      <a class="search" href="?PAGE_CODE=search"><span class="button navbtn">Quick Search</span></a>

  <? } else { ?>

    <div class="leftnav byall">
        <? if (!$isUSER_TRANSFERS) { ?>
            <a class="reserv" href="?PAGE_CODE=availability"><span class="button navbtn">Make Reservations</span></a>
            <a class="reports" href="?PAGE_CODE=reports"><span class="button navbtn">Rooms Reports</span></a>
        <? } ?>

        <a class="reports_transfers" href="?PAGE_CODE=reports_transfers"><span class="button navbtn">Transfers Reports</span></a>

        <? if ($isSUPER) { ?>
            <a class="ccagent" href="?PAGE_CODE=search_ccagent"><span class="button navbtn">CC Agents</span></a>
        <? } ?>

        <? if (!$isUSER_TRANSFERS) { ?>
            <a class="tagent" href="?PAGE_CODE=search_ta"><span class="button navbtn">Travel Agents</span></a>
            <a class="search_guest" href="?PAGE_CODE=search_guest"><span class="button navbtn">Guests</span></a>
            <a class="search_reserv" href="?PAGE_CODE=search_reserv"><span class="button navbtn">Reservations</span></a>
            <a class="search" href="?PAGE_CODE=search"><span class="button navbtn">Quick Search</span></a>
        <? } ?>

  <? } ?>

<? } else if ($isSUPER || $isUSER_TRANSFERS) { ?>

<div class='leftnav byprop'>
    <?
    if (!$isUSER_TRANSFERS) {
        $_PAGES = isset($_MAINPAGES[$PROP_ID]) ? $_MAINPAGES[$PROP_ID] : $_MAINPAGES[0];
        foreach($_PAGES as $KEY => $_PAGE) {
            print "<a class='{$_PAGE['CODE']}' href='?PAGE_CODE={$_PAGE['CODE']}&PROP_ID={$PROP_ID}'><span class='button navbtn'>{$_PAGE['LABEL']}</span></a>";
        }
        print "
          <a class='setup' href='?PAGE_CODE=setup&PROP_ID={$PROP_ID}'><span class='button navbtn'>Set Up</span></a>
          <a class='markup' href='?PAGE_CODE=markup&PROP_ID={$PROP_ID}'><span class='button navbtn'>Monthly Markup</span></a>
          <a class='banners' href='?PAGE_CODE=banners&PROP_ID={$PROP_ID}'><span class='button navbtn'>Web Banner</span></a>
        ";
    }
    print "
      <br>
      <a href='?PAGE_CODE=transfer_sett&PROP_ID={$PROP_ID}' class='transfer_settigns'><span class='button navbtn'>Transfer Settings</span></a>
      <a href='?PAGE_CODE=transfer_cars&PROP_ID={$PROP_ID}' class='transfer_cars'><span class='button navbtn'>Transfer Cars</span></a>
      <a href='?PAGE_CODE=transfer_seasons&PROP_ID={$PROP_ID}' class='transfer_seasons'><span class='button navbtn'>Transfer Seasons</span></a>
      <a href='?PAGE_CODE=transfer_car_season&PROP_ID={$PROP_ID}' class='transfer_car_season'><span class='button navbtn'>Transfer Prices</span></a>
    ";
}

?>
<div class="aclear"></div>
</div>
