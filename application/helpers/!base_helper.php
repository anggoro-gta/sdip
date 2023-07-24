<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function do_upload($folder, $var="files", $new_file_name=null, $preffix="_") {
	if(isset($_FILES[$var])){
		if(is_array($_FILES[$var]['name'])){
			$result = array();
			foreach ($_FILES[$var]['name'] as $key => $value) {
				if($value == "") continue;
				$file_name = $_FILES[$var]['name'][$key];
				$ext = strtolower(array_pop(explode(".", $file_name)));
				
				if($new_file_name==null){
					$new_file_name = $preffix.$file_name.".".$ext;
				}else{
					$new_file_name = $new_file_name.".".$ext;
				}
				$file_directory_saved = "assets/img/".$folder;
	
				if(!file_exists($file_directory_saved)) {
					mkdir($file_directory_saved, 0777, true);
				}
	
				move_uploaded_file($_FILES[$var]['tmp_name'][$key],$file_directory_saved."/".$new_file_name);
				array_push($result, array (
						"file_loc"=>$file_directory_saved."/".$new_file_name,
						"file_size"=>$_FILES[$var]['size'][$key],
						"file_name"=>$_FILES[$var]['name'][$key],
						"file_type"=>$_FILES[$var]['type'][$key],
					)
				);
			}
			return $result;
		}else{
			if($_FILES[$var]['name'] == "") return false;
			$file_name = $_FILES[$var]['name'];
			$ext = strtolower(array_pop(explode(".", $file_name)));
			
			if($new_file_name==null){
				$new_file_name = $preffix.$file_name.".".$ext;
			}else{
				$new_file_name = $new_file_name.".".$ext;
			}
			$file_directory_saved = "assets/img/".$folder;

			if(!file_exists($file_directory_saved)) {
				mkdir($file_directory_saved, 0777, true);
			}

			move_uploaded_file($_FILES[$var]['tmp_name'],$file_directory_saved."/".$new_file_name);
			return array (
				"file_loc"=>$folder."/".$new_file_name,
				"file_size"=>$_FILES[$var]['size'],
				"file_name"=>$_FILES[$var]['name'],
				"file_type"=>$_FILES[$var]['type'],
			);
		}
		
	}
	return false;
}

function numberToColor($value, $min, $max, $gradientColors = null)
{
    // Ensure value is in range
    if ($value < $min) {
        $value = $min;
    }
    if ($value > $max) {
        $value = $max;
    }

    // Normalize min-max range to [0, positive_value]
    $max -= $min;
    $value -= $min;
    $min = 0;

    // Calculate distance from min to max in [0,1]
    $distFromMin = $value / $max;

    // Define start and end color
    if (count($gradientColors) == 0) {
        return numberToColor($value, $min, $max, ['#CC0000', '#EEEE00', '#00FF00']);
    } else if (count($gradientColors) == 2) {
        $startColor = $gradientColors[0];
        $endColor = $gradientColors[1];
    } else if (count($gradientColors) > 2) {
        $startColor = $gradientColors[floor($distFromMin * (count($gradientColors) - 1))];
        $endColor = $gradientColors[ceil($distFromMin * (count($gradientColors) - 1))];

        $distFromMin *= count($gradientColors) - 1;
        while ($distFromMin > 1) {
            $distFromMin--;
        }
    } else {
        die("Please pass more than one color or null to use default red-green colors.");
    }

    // Remove hex from string
    if ($startColor[0] === '#') {
        $startColor = substr($startColor, 1);
    }
    if ($endColor[0] === '#') {
        $endColor = substr($endColor, 1);
    }

    // Parse hex
    list($ra, $ga, $ba) = sscanf("#$startColor", "#%02x%02x%02x");
    list($rz, $gz, $bz) = sscanf("#$endColor", "#%02x%02x%02x");

    // Get rgb based on
    $distFromMin = $distFromMin;
    $distDiff = 1 - $distFromMin;
    $r = intval(($rz * $distFromMin) + ($ra * $distDiff));
    $r = min(max(0, $r), 255);
    $g = intval(($gz * $distFromMin) + ($ga * $distDiff));
    $g = min(max(0, $g), 255);
    $b = intval(($bz * $distFromMin) + ($ba * $distDiff));
    $b = min(max(0, $b), 255);

    // Convert rgb back to hex
    $rgbColorAsHex = '#' .
        str_pad(dechex($r), 2, "0", STR_PAD_LEFT) .
        str_pad(dechex($g), 2, "0", STR_PAD_LEFT) .
        str_pad(dechex($b), 2, "0", STR_PAD_LEFT);

    return $rgbColorAsHex;
}

function luminance($hexcolor, $percent)
{
  if ( strlen( $hexcolor ) < 6 ) {
    $hexcolor = $hexcolor[0] . $hexcolor[0] . $hexcolor[1] . $hexcolor[1] . $hexcolor[2] . $hexcolor[2];
  }
  $hexcolor = array_map('hexdec', str_split( str_pad( str_replace('#', '', $hexcolor), 6, '0' ), 2 ) );

  foreach ($hexcolor as $i => $color) {
    $from = $percent < 0 ? 0 : $color;
    $to = $percent < 0 ? $color : 255;
    $pvalue = ceil( ($to - $from) * $percent );
    $hexcolor[$i] = str_pad( dechex($color + $pvalue), 2, '0', STR_PAD_LEFT);
  }

  return '#' . implode($hexcolor);
}

?>