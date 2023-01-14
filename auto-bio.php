<?php
/*
Plugin Name:	Bio Table Generator 
Plugin URI:		http://wordpress.org/extend/plugins/auto_bio_adder
Description:	This plugin just adds a bio table of an artist from a shortcode
Version:		0.1.0
Author:			Peter Ray
Author URI:		www.peterayuzowihe.com

*/


function auto_bio_adder_from_shortcode($attr, $content=null) {
    global $bio_table_data_defaults;
    $img = (isset($attr["image"]) ? $attr["image"] : $bio_table_data_defaults['img']);
    $networth = (isset($attr["networth"]) ? $attr["networth"] : $bio_table_data_defaults['networth']) ;
    $name = (isset($attr["name"]) ? $attr["name"] : $bio_table_data_defaults['name']);
    $color = $bio_table_data_defaults['bg-color'];
    
    $tc = explode('-', $color)[1];
    $bc = explode('-', $color)[0];

    $ministyle = '
        .r-app{
            background-color:';
            $ministyle .=($color)?$bc.';':'white;';
            $ministyle .=($bc == "black" || $bc == "blue" || $bc == "red")?"color:#fff;":"color:#678686;";
            $ministyle .= '
        }
        .bio-def-hr-row{
            display: flex;align-items: center;
        }
        .bio-space-ml-2{
            margin-left: 10px;
        }
        .bio-attrs-o-def{
            padding:10px; 
            display:flex;
            justify-content:space-between; 
            border-bottom:1px solid black;
        }
        .bio-attrs-o-def:hover{
            cursor: pointer;
            background-color: #cecece;
        }
        .bio-networth-active{
            padding: 10px;
            display: flex;
            justify-content: space-between;
            background-color:';
            $ministyle .=($color)?$tc.';':'blue;';
            $ministyle .=($tc == "black" || $tc == "blue")?"color:#fff;":"color:#234334;";
            $ministyle .= '
        }
        .sm-bold-text{
            font-size:large;
            font-weight:bold;
        }
        .lg-bold-text{
            font-size:larger;
            font-weight:bold;
        }
        @media screen and (max-width:500px) {
            .r-app{
                width: 100%;
            }
        }
    ';

    $view = '
        <style>'.$ministyle.'</style>
        <div class="r-app">
            <div class="bio-def-hr-row">
                <img src="'.$img.'" alt="" style="width:100px;"/>
                <div class="bio-space-ml-2">
                    <p class="lg-bold-text">'.ucfirst($name).'</p>
                    <p class="sm-bold-text">'.$networth.'</p>
                </div>
            </div>
            <div>
                <div class="bio-networth-active">
                    <div>Net Worth</div>
                    <div>'.$networth.'</div>
                </div>
                <div class="bio-attrs-o-def">
                    <div>Name</div>
                    <div>'.ucfirst($name).'</div>
                </div>';

                foreach($attr as $key => $value ) {
                    if(!(strtolower($key) == 'name' || strtolower($key) == 'age' || strtolower($key) == 'networth' || strtolower($key) == '' || strtolower($key) == 'image')) {
                        $view .='
                        <div class="bio-attrs-o-def">
                            <div>'.ucfirst($key).'</div>
                            <div>'.ucfirst($value).'</div>
                        </div>
                    ';
                    }
                    if(strtolower($key) == 'age') {
                        $view .='
                        <div class="bio-attrs-o-def">
                            <div>'.ucfirst($key).'</div>
                            <div>'.auto_bio_adder_AgeCalculator($value).'</div>
                        </div>
                    ';
                    }
                }
            $view .= '    
            </div>
        </div>
    ';
    return $view;
}

function auto_bio_adder_AgeCalculator($birthdate) {
	global $bio_table_data_defaults;

	$bio_table_data_defaults['birthdate'] = $birthdate;
	$bio_table_data_defaults['birthdate'] = preg_replace(array('/\./', '/\//'), '-', $bio_table_data_defaults['birthdate']);
	
	if (!jc_age_f_is_date($bio_table_data_defaults['birthdate'])) {
		return '<!-- Age Calculator: Invalid Date -->';
	}
	
	$auto_bio_adder_text_month	= $bio_table_data_defaults['month'];
	$auto_bio_adder_text_months	= $bio_table_data_defaults['months'];
	$auto_bio_adder_text_year		= $bio_table_data_defaults['year'];
	$auto_bio_adder_text_years	= $bio_table_data_defaults['years'];
	
	$auto_bio_adder_multiplierM	= $bio_table_data_defaults['multiplierM'];
	$auto_bio_adder_multiplierM	= (is_numeric($auto_bio_adder_multiplierM))	? $auto_bio_adder_multiplierM : 1;
	
	$auto_bio_adder_multiplierY	= $bio_table_data_defaults['multiplierY'];
	$auto_bio_adder_multiplierY	= (is_numeric($auto_bio_adder_multiplierY))	? $auto_bio_adder_multiplierY : 1;
	
	list($bY,$bM,$bD) = explode("-", $bio_table_data_defaults['birthdate']);
	list($cY,$cM,$cD) = explode("-", date("Y-n-d"));
	
	//Calculates Months
	if ($bY == $cY) {
		$months = $cM - $bM;
		
		if ($months == 0 || $months > 1) {
			return ($months * $auto_bio_adder_multiplierM) . '&nbsp;' . $auto_bio_adder_text_months;
		}
		else {
			return ($months * $auto_bio_adder_multiplierM) . '&nbsp;' . $auto_bio_adder_text_month;
		}
	}
	
	//Calculates Months if "over" a year change
	if ($cY - $bY == 1 && $cM - $bM < 12) {
		if ($cD - $bM > 0) {
			$xm = 0;
		}
		else {
			$xm = 1;
		}
		
		$months = 12 - $bM + $cM - $xm;
		
		if ($months == 0 || $months > 1) {
			return ($months * $auto_bio_adder_multiplierM) . '&nbsp;' . $auto_bio_adder_text_months;
		}
		else {
			return ($months * $auto_bio_adder_multiplierM) . '&nbsp;' . $auto_bio_adder_text_month;
		}
	}
	
	//Calculates Years
	$years = (date("md") < $bM.$bD ? date("Y")-$bY-1 : date("Y")-$bY );
	
	if ($years == 0 || $years > 1) {
		return ($years * $auto_bio_adder_multiplierY) . '&nbsp;' . $auto_bio_adder_text_years;
	}
	else {
		return ($years * $auto_bio_adder_multiplierY) . '&nbsp;' . $auto_bio_adder_text_year;
	}
}

function auto_bio_adder_admin_dashboard() {
    global $myobj;
    global $bio_table_data_defaults;

    //check_admin_referer( 'bio_gen_options_hidden_field');
    if(isset($_POST['submit'])) {
        $data = [
            'img' => $_POST['img'],
            'name' => $_POST['name'],
            'networth' => $_POST['networth'],
            'bg-color' => $_POST['bg-color']
        ];
        update_option('bio_adder_defaults__', $data);
    }
 ?>
        <div class="wrap">
            <h2>Bio Table Generator </h2>
            <p>Configure the generated table </p>
            <form action="./options-general.php?page=auto-bio.php" method="post">
                <?php wp_nonce_field( "bio_gen_options_hidden_field") ; ?>
                <table class="form-table" role="presentation">
                    <tbody>
                        <tr>
                            <th scope="row"><label for="name">Name</label></th>
                            <td><input name="name" id="name" type="text" value="<?php echo ($bio_table_data_defaults['name']); ?>" class="regular-text" />
                            <p class="description">Set the default value for the name in case it was not specified</p>
                        </tr>
                        <tr>
                            <th scope="row"><label for="bg-color">Background Color</label></th>
                            <td><input name="bg-color" id="bg-color" type="text" value="<?php echo ($bio_table_data_defaults['bg-color']); ?>" class="regular-text" />
                            <p class="description" id="tagline-description">When inputing color the first color before the hyphen matches the background color and after the hyphen matches the net worth background color</p>
                        </tr>
                        <tr>
                            <th scope="row"><label for="img">Image</label></th>
                            <td><input name="img" id="img" type="text" value="<?php echo ($bio_table_data_defaults['img']); ?>" class="regular-text" />
                            <p class="description">Set the default image in case it was not specified (image takes only file link as text ) </p>
                        </tr>
                        <tr>
                            <th scope="row"><label for="networth">Net Worth</label></th>
                            <td><input name="networth" id="networth" type="text" value="<?php echo ($bio_table_data_defaults['networth']); ?>" class="regular-text" />
                            <p class="description">Set the default value for the net worth in case it was not specified</p>
                        </tr>
                    </tbody>
                </table>
                <p>
                    <input name="submit" value="Save Changes" type="submit" class="button button-primary" />
                </p>
            </form>
        </div>
 <?php
}

function auto_bio_adder_admin_dashboardsettings() {
	add_options_page('Bio Table Generator', 'Bio Table Generator', 8, basename(__FILE__), 'auto_bio_adder_admin_dashboard');
}

function my_bio_table_defaults() {
    $my_defaults__ = [
        'img' => 'https://www.images/uploads/image.png',
        'name' => 'Peter Ray',
        'networth' => '$1,000 dollars',
        'months' => 'months',
        'month' => 'months',
        'years' => 'years',
        'year' => 'years',
        'multiplierY' => 'multiplierY',
        'multiplierM' => 'multiplierM',
        'bg-color' => 'white-blue'
    ];
		
    $bio_def_Options = get_option('bio_adder_defaults__');
    
    if (!empty($bio_def_Options)) {
        foreach ($bio_def_Options as $key => $val) {
            $my_defaults__[$key] = $val;
        }
        update_option('bio_adder_defaults__', $my_defaults__);
    }
    return $my_defaults__;
}

$bio_table_data_defaults = my_bio_table_defaults();

add_action('admin_menu', 'auto_bio_adder_admin_dashboardsettings');
add_shortcode( 'auto_bio_adder', 'auto_bio_adder_from_shortcode' );