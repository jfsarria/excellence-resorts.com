<?
/*
 * Revised: Aug 09, 2011
 *          May 07, 2014
 *          Jul 22, 2016
 */

$_EDITMOD = array();
$_EDITMOD[0] = array (
    'rooms' => array (
        'name'  => 'm.rooms.name.php',
        'stus'  => 'm.rooms.stus.php',
        'beds'  => 'm.rooms.beds.php',
        'occu'  => 'm.rooms.occu.php',
        'invet' => 'm.rooms.invet.php',
        'descr' => 'm.rooms.descr.php',
        'imgs'  => 'm.rooms.imgs.php',
        'vids'  => 'm.rooms.vids.php'
    ),
    'seasons' => array (
        'name'  => 'm.season.name.php',
        'date'  => 'm.season.date.php',
        'spls'  => 'm.season.spls.php'
    ),
    'classes' => array (
        'name'  => 'm.class.name.php',
        'stus'  => 'm.class.stus.php',
        'season'=> 'm.class.season.php',
        'room'  => 'm.class.room.php',
        'user'  => 'm.class.user.php',
        'rate'  => 'm.class.rate.php',
        'calc'  => 'm.class.calc.php',
        'geo'   => 'm.class.geo.php',
        'close' => 'm.class.close.php',
        'desc'  => 'm.class.desc.php'
    ),
    'specials' => array (
        'name'  => 'm.specials.name.php',
        'stus'  => 'm.specials.stus.php',
        'type'  => 'm.specials.type.php',
        'code'  => 'm.specials.code.php',
        'class' => 'm.specials.class.php',
        'geo'   => 'm.specials.geo.php',
        'desc'  => 'm.specials.desc.php',
        'bwin'  => 'm.specials.bwin.php',
        'trav'  => 'm.specials.trav.php',
        'carr'  => 'm.specials.carr.php',
        'bout'  => 'm.specials.bout.php'
    ),
    'setup' => array (
        'cnf'   => 'm.setup.settings.php',
        'descr' => 'm.setup.descr.php',
        'info'  => 'm.setup.info.php',
        'urls'  => 'm.setup.urls.php',
        'imgs'  => 'm.setup.imgs.php',
        'vids'  => 'm.setup.vids.php',
        'email'  => 'm.setup.email.php'
    ),
    'markup' => array(
        'months'  => 'm.markup.months.php'
    ),
    'banners' => array (
        'name'  => 'm.banners.name.php',
        'stus'  => 'm.banners.fields.php'
    ),
    'transfer_sett' => array (
        'setup' => 'm.transfer.settings.php'
    ),
    'transfer_cars' => array (
        'cars'  => 'm.transfer.cars.php'
    ),
    'transfer_seasons' => array (
        'seasons' => 'm.transfer.seasons.php'
    ),
    'transfer_car_season' => array (
        'car_season' => 'm.transfer.car.season.php'
    ),
    'availability'  => array (
        'init'      => 'm.availability.init.php',
        'future'    => 'm.availability.future.php',
        'prop'      => 'm.availability.prop.php',
        'block2'    => 'm.availability.block_2.php',
        'dates'     => 'm.availability.dates.php',
        'rooms'     => 'm.availability.rooms.php'
    ),
    'stopsale' => array (
        'edit'  => 'm.stopsale.edit.php'
    ),
    'inventory' => array (
        'filters'   => 'm.inventory.filters.php',
        'email'     => 'm.inventory.email.php'
    ),
    'search_ccagent' => array (
        'filters'   => 'm.search_ccagent.filters.php'
    ),
    'search_ta' => array (
        'filters'   => 'm.search_ta.filters.lam.php'
    ),
    'search_guest' => array (
        'filters'   => 'm.search_guest.filters.lam.php'
    ),
    'search_reserv' => array (
        'filters'   => 'm.search_reserv.filters.php'
    ),
    'search' => array (
        'filters'   => 'm.search.filters.simple.php'
    ),
    'edit_reserv' => array (
        'header'    => 'm.edit_reserv.header.php',
        'reserv'    => 'm.edit_reserv.reserv.php',
        'guest'     => 'm.edit_reserv.guest.php',
        'ta'        => 'm.edit_reserv.ta.php',
        'payment'   => 'm.edit_reserv.payment.php',
        'prefer'    => 'm.edit_reserv.prefer.php',
        'comments'  => 'm.edit_reserv.comments.php',
        'history'   => 'm.edit_reserv.history.php'
    )
);
$_EDITMOD[4] = array (
    'rooms' => array (
        'occu'  => 'm.rooms.gopt.php'
    ),
    'child' => array(
        'rate'  => 'm.child.rate.php'
    ),
    'edit_reserv' => array (
        'reserv'    => 'm.edit_reserv.reserv.lam.php',
    )
);


$_MAINPAGES = array();
$_MAINPAGES[0] = array(
    array('CODE'=>'rooms','LABEL'=>'Rooms'),
    array('CODE'=>'seasons','LABEL'=>'Seasons'),
    array('CODE'=>'classes','LABEL'=>'Rate Classes'),
    array('CODE'=>'specials','LABEL'=>'Specials'),
    array('CODE'=>'stopsale','LABEL'=>'Stop-Sale'),
    array('CODE'=>'inventory','LABEL'=>'Inventory')
);
$_MAINPAGES[4] = array(
    array('CODE'=>'rooms','LABEL'=>'Rooms'),
    array('CODE'=>'seasons','LABEL'=>'Seasons'),
    array('CODE'=>'classes','LABEL'=>'Rate Classes'),
    array('CODE'=>'child','LABEL'=>'Children Rates'),
    array('CODE'=>'specials','LABEL'=>'Specials'),
    array('CODE'=>'stopsale','LABEL'=>'Stop-Sale'),
    array('CODE'=>'inventory','LABEL'=>'Inventory')
);

$_RESERVMOD = array();
$_RESERVMOD[0] = array (
    'forwhom' => array (
        'newguest'  => 'm.reserv.forwhom.guest.new.php',
        'forguest'  => 'm.reserv.forwhom.guest.php',
        'forta'     => 'm.reserv.forwhom.ta.php'
    ),
    'rooms' => array (
        'rebooking' => 'm.reserv.room.rebooking.php',
        'guest'     => 'm.reserv.room.guest.php',
        'payment'   => 'm.reserv.room.payment.php',
        'optionals' => 'm.reserv.room.optionals.php',
        'comments'  => 'm.reserv.room.comments.php',
        'next'      => 'm.reserv.room.next.php'
    ),
    'make' => array (
        'number'    => 'm.reserv.number.php',
        'server'    => 'm.reserv.payment.er.server.php',
        'payment'   => 'm.reserv.payment.er.verify.php',
        'save'      => 'm.reserv.make.save.php',
        'confirm'   => 'm.reserv.make.confirm.php'
    )
);

?>