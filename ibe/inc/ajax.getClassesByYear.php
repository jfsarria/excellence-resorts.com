<?
/*
 * Revised: Sep 18, 2011
 */

$YEARS = isset($_GET['YEAR']) ? $_GET['YEAR'] : 0;
$GEOS = isset($_GET['GEOS']) ? $_GET['GEOS'] : "";
$SEASON = isset($_GET['SEASON']) ? $_GET['SEASON'] : "";
$ROOM = isset($_GET['ROOM']) ? $_GET['ROOM'] : "";

?>

<div id="wrapper" year="<? print isset($_GET['YEAR']) ? $_GET['YEAR'] : $PROP_ID ?>">
    <table class="pickList" width='100%'>
    <tr>
    <?
        $CLASSES = $clsSpecials->getClasses($db, array("SPECIAL_ID"=>$SPECIAL_ID,"AS_ARRAY"=>true,"WHERE"=>" AND CLASSES.IS_ACTIVE='1' ")); 
        $RSET = $clsClasses->getByFilters($db, array("PROP_ID"=>$PROP_ID,"YEAR"=>$YEARS,"GEOS"=>$GEOS,"SEASON"=>$SEASON,"ROOM"=>$ROOM));
        if ( $RSET['iCount'] != 0 ) {
            $cnt=0;
            while ($row = $db->fetch_array($RSET['rSet'])) {
                $NAME = _d($row['NAME_'.$_IBE_LANG],$row['NAME_EN']);
                $REFERENCE = trim($row['REFERENCE'])!=""?$row['REFERENCE']:$NAME;
                $CHECKED = (array_key_exists($row['ID'],$CLASSES)) ? "checked" : "";
                print "
                <td width='50%' class='pickListItem i{$cnt}' valign='top'>
                    <table cellpadding='1' cellspacing='1' border='0'>
                    <tr>
                        <td valign='top'><span><input rel='{$row['YEAR']}' type='checkbox' name='CLASS_ID[]' value='{$row['ID']}' {$CHECKED}></span></td>
                        <td valign='top' style='padding-left:3px'>{$REFERENCE}</td>
                    </tr>
                    </table>
                </td>";
                if (fmod(++$cnt,2)==0) print "</tr><tr>";
            }
        }
    ?>
    </tr>
    </table>
</div>