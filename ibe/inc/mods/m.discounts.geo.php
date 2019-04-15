

<?$check_all_rooms="";
//print_r($_DATA);


//print_r($radio);

?>
<div id="geo1" <?print $style_ocultar2; ?>>
<fieldset >
    <legend>Geo Targeting</legend>
    <div class="fieldset">
        <!--
        <table>
            <tr>
                <td>
                 <input type='checkbox' name='Generalgeo' id="Generalgeo" value='generalgeo' checked><label>General</label>
                 &nbsp;
            </td>
            <td>
                <input type='text' name='Graltxtgeo' id="Graltxtgeo" value='' maxlength='3' size='3'> <div id='porgeo' name='porgeo' style='display:inline;'>%</div>
                &nbsp;
            </td>
        </tr>
        </table>
       -->
        <div class="label">
            <?php
            $TARGETS = array(
                "US" => "United States",
                "CA" => "Canada",
                "DO" => "Dominican Republic",
                "JM" => "Jamaica",
                "MX" => "Mexico",
                "GB" => "United Kingdom",
                "LA" => "Latin America",
                "EU" => "Europe",
                "--" => "Rest of the world"

            );
            ?>

            <table id="GeosPickList" class="pickList">
            <tr>
                <!--<input type='checkbox' value='' id='cb_' name='GEOS[]' >&nbsp;All &nbsp;-->
            <?php
            //print_r($_DATA);
            
            $GEOS=array();
            $GEOSpor=array();
            $GEOSid=array();
            if(isset($_DATA['LINMOD'])){
                 for($i=0;$i<count($_DATA['LINMOD']);$i++){
                $GEOS[]=$_DATA['LINMOD'][$i]['GEOCOUNTRY'];
                $GEOSpor[]=$_DATA['LINMOD'][$i]['VALUE'];
                $GEOSid[]=$_DATA['LINMOD'][$i]['ID_LIN'];

            }
            }
           
            //$GEOS = isset($_DATA['LINMOD'][0] ) ? $_DATA['LINMOD'][0][]: array();
            //print_r($GEOS);
            //print_r($GEOSpor);
            $cnt=0;
            foreach ($TARGETS as $CODE => $NAME) {
                //print_r($CODE); 
                if (in_array($CODE,$GEOS)===false && $check_all_rooms=="on") array_push($GEOS,$CODE);
                $pos=-1;
                $valortext="";
                $idgeo="";
                if(in_array($CODE,$GEOS)===true){
                    $pos= array_search($CODE, $GEOS);
                    if($pos>=0){
                        $valortext=$GEOSpor[$pos];
                        $idgeo=$GEOSid[$pos];
                    }  
                }
                //print_r($idgeo);

                
                //print $pos;
                print "<td width='20%' nowrap class='pickListItem i{$cnt}'>
                <input type='hidden' id='geoid[]' name='geoid[]' value='{$idgeo}'>
                <input type='hidden' id='geocheck[]' name='geocheck[]' value='{$CODE}'>
                <input type='checkbox' value='{$CODE}' id='cb_{$CODE}' name='GEOS[]' ".((in_array($CODE,$GEOS)===true)?"checked":"").">&nbsp;{$NAME} &nbsp; 
               <!-- <input type='text' id='GEOStext[]' name='GEOStext[]' ".((in_array($CODE,$GEOS)===true)?"style='display:inline;'":"style='display:none;'")." maxlength='3' size='3' value='{$valortext}'>--> <div id='porgeo{$cnt}' name='porgeo{$cnt}' ".((in_array($CODE,$GEOS)===true)?"style='display:inline;'":"style='display:none;'")."></div></td>";
                if (fmod(++$cnt,3)==0) print "</tr><tr>";
            }
            
            ?>
            </tr>
            </table>
        </div>
    </div>
</fieldset>
</div>


