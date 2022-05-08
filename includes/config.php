<?php
include ('clase.php');
$host='localhost';
$user='root';
$pass='root';
$baza='facturi';
$SERVER_PATH='/';
//$db=new db($host,$user,$pass,$baza);

//session
$timeout = 4800;
ini_set('session.gc_maxlifetime',$timeout);
//end session

ini_set('display_errors','on');

// variabila globala pentru conectare la mysql in functii externe
$config = array();
$config['host']='localhost';
$config['user']='root';
$config['pass']='root';
$config['baza']='facturi';

// variabila globala pentru subdomeniu
$x = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
$y = parse_url($x);
$dirs = explode("/", $y['path']);
$subdomeniu = $dirs[1];

function convert_month($dat){
$luni = array("Ianuarie",
		"Februarie",
		"Martie",
		"Aprilie",
		"Mai",
		"Iunie",
		"Iulie",
		"August",
		"Septembrie",
		"Octombrie",
		"Noiembrie",
		"Decembrie"
	);
	$luna = substr($dat,5,2);
	$m = $luni[($luna-1)];
	return $m;
}

function convert_data($dat){
// dd-mm-yyyy => dd luni-min yyyy
$luni_min = array("Ian",
		"Feb",
		"Mar",
		"Apr",
		"Mai",
		"Iun",
		"Iul",
		"Aug",
		"Sep",
		"Oct",
		"Nov",
		"Dec"
	);
	$zi = substr($dat,0,2);
	$luna = substr($dat,3,2);
	$an = substr($dat,6,4);
	$dat = $zi.' '.$luni_min[($luna-1)].' '.$an;
	return $dat;
}
function convert_sql_date($dat){
// yyyy-mm-dd hh:ii:ss => dd luni-min yyyy hh:ii
$luni_min = array("Ian",
		"Feb",
		"Mar",
		"Apr",
		"Mai",
		"Iun",
		"Iul",
		"Aug",
		"Sep",
		"Oct",
		"Nov",
		"Dec"
	);
	$zi = substr($dat,8,2);
	$luna = substr($dat,5,2);
	$an = substr($dat,0,4);
	$hour = substr($dat,11,2);
	$min = substr($dat,14,2);
	$dat = $zi.' '.$luni_min[($luna-1)].' '.$an.' '.$hour.':'.$min;
	return $dat;
}

function ro($nr){
	if (strlen($nr) == 1) return '<strong>'.$nr.'</strong>';
	else{
		if ((substr($nr,-2,1) == 0 && substr($nr,-1) != 0) || substr($nr,-2,1) == 1) return '<strong>'.$nr.'</strong>';
		else return '<strong>'.$nr.'</strong> de';
	}
}

function recursiveDelete($str){
	if(is_file($str)){
		return @unlink($str);
	}
	elseif(is_dir($str)){
		$scan = glob(rtrim($str,'/').'/*');
		foreach($scan as $index=>$path){
			recursiveDelete($path);
		}
		//return @rmdir($str);
	}
}

//Images
function construct($sigla){
	$image = @openImage($sigla);
	$width = @imagesx($image);
	$height = @imagesy($image);
	if ($width > $height) return '1';
	else return '2';
}
function openImage($file){
	$extension = strtolower(strrchr($file, '.'));
	switch($extension){
		case '.jpg':
		case '.jpeg':
			$img = @imagecreatefromjpeg($file);
			break;
		case '.gif':
			$img = @imagecreatefromgif($file);
			break;
		case '.png':
			$img = @imagecreatefrompng($file);
			break;
		default:
			$img = false;
			break;
	}
	return $img;
}
//end Images

//Parola
function random(){
	$characters = array(
	"A","B","C","D","E","F","G","H","J","K","L","M",
	"N","P","Q","R","S","T","U","V","W","X","Y","Z",
	"1","2","3","4","5","6","7","8","9");
	$keys = array();
	while(count($keys) < 7) {
		$x = mt_rand(0, count($characters)-1);
		if(!in_array($x, $keys)) {
		   $keys[] = $x;
		}
	}
	foreach($keys as $key){
	   $random .= $characters[$key];
	}
	return $random;
}
//end Parola

function xml2array($url, $get_attributes = 1, $priority = 'tag') {
    $contents = "";
    if (!function_exists('xml_parser_create'))
    {
        return array ();
    }
    $parser = xml_parser_create('');
    if (!($fp = @ fopen($url, 'rb')))
    {
        return array ();
    }
    while (!feof($fp))
    {
        $contents .= fread($fp, 8192);
    }
    fclose($fp);
    xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8");
    xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
    xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
    xml_parse_into_struct($parser, trim($contents), $xml_values);
    xml_parser_free($parser);
    if (!$xml_values)
        return; //Hmm...
    $xml_array = array ();
    $parents = array ();
    $opened_tags = array ();
    $arr = array ();
    $current = & $xml_array;
    $repeated_tag_index = array (); 
    foreach ($xml_values as $data)
    {
        unset ($attributes, $value);
        extract($data);
        $result = array ();
        $attributes_data = array ();
        if (isset ($value))
        {
            if ($priority == 'tag')
                $result = $value;
            else
                $result['value'] = $value;
        }
        if (isset ($attributes) and $get_attributes)
        {
            foreach ($attributes as $attr => $val)
            {
                if ($priority == 'tag')
                    $attributes_data[$attr] = $val;
                else
                    $result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
            }
        }
        if ($type == "open")
        { 
            $parent[$level -1] = & $current;
            if (!is_array($current) or (!in_array($tag, array_keys($current))))
            {
                $current[$tag] = $result;
                if ($attributes_data)
                    $current[$tag . '_attr'] = $attributes_data;
                $repeated_tag_index[$tag . '_' . $level] = 1;
                $current = & $current[$tag];
            }
            else
            {
                if (isset ($current[$tag][0]))
                {
                    $current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;
                    $repeated_tag_index[$tag . '_' . $level]++;
                }
                else
                { 
                    $current[$tag] = array (
                        $current[$tag],
                        $result
                    ); 
                    $repeated_tag_index[$tag . '_' . $level] = 2;
                    if (isset ($current[$tag . '_attr']))
                    {
                        $current[$tag]['0_attr'] = $current[$tag . '_attr'];
                        unset ($current[$tag . '_attr']);
                    }
                }
                $last_item_index = $repeated_tag_index[$tag . '_' . $level] - 1;
                $current = & $current[$tag][$last_item_index];
            }
        }
        elseif ($type == "complete")
        {
            if (!isset ($current[$tag]))
            {
                $current[$tag] = $result;
                $repeated_tag_index[$tag . '_' . $level] = 1;
                if ($priority == 'tag' and $attributes_data)
                    $current[$tag . '_attr'] = $attributes_data;
            }
            else
            {
                if (isset ($current[$tag][0]) and is_array($current[$tag]))
                {
                    $current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;
                    if ($priority == 'tag' and $get_attributes and $attributes_data)
                    {
                        $current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
                    }
                    $repeated_tag_index[$tag . '_' . $level]++;
                }
                else
                {
                    $current[$tag] = array (
                        $current[$tag],
                        $result
                    ); 
                    $repeated_tag_index[$tag . '_' . $level] = 1;
                    if ($priority == 'tag' and $get_attributes)
                    {
                        if (isset ($current[$tag . '_attr']))
                        { 
                            $current[$tag]['0_attr'] = $current[$tag . '_attr'];
                            unset ($current[$tag . '_attr']);
                        }
                        if ($attributes_data)
                        {
                            $current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
                        }
                    }
                    $repeated_tag_index[$tag . '_' . $level]++; //0 and 1 index is already taken
                }
            }
        }
        elseif ($type == 'close')
        {
            $current = & $parent[$level -1];
        }
    }
    return ($xml_array);
}

function bar_code128($cod) {
	$bar = '';
	$start=1;
	$start_code = array (0=>'11010000100',1=>'11010010000',2=>'11010011100');
	$stop_code = '1100011101011';
	$start_chk = array (0=>'105',1=>'104',2=>'103');

	for ($i=0;$i<strlen($cod);$i++) {
	$code_comp[] = ord($cod[$i]);
	}

	$checksum = $start_chk[$start];
	foreach ($code_comp as $key => $value) {
		$checksum += ($key + 1) * ($value - 32);
	}

$chk_dig = ($checksum % 103);
$code_caracter = array (
	0=>'11011001100',
	1=>'11001101100',
	2=>'11001100110',
	3=>'10010011000',
	4=>'10010001100',
	5=>'10001001100',
	6=>'10011001000',
	7=>'10011000100',
	8=>'10001100100',
	9=>'11001001000',
	10=>'11001000100',
	11=>'11000100100',
	12=>'10110011100',
	13=>'10011011100',
	14=>'10011001110',
	15=>'10111001100',
	16=>'10011101100',
	17=>'10011100110',
	18=>'11001110010',
	19=>'11001011100',
	20=>'11001001110',
	21=>'11011100100',
	22=>'11001110100',
	23=>'11101101110',
	24=>'11101001100',
	25=>'11100101100',
	26=>'11100100110',
	27=>'11101100100',
	28=>'11100110100',
	29=>'11100110010',
	30=>'11011011000',
	31=>'11011000110',
	32=>'11000110110',
	33=>'10100011000',
	34=>'10001011000',
	35=>'10001000110',
	36=>'10110001000',
	37=>'10001101000',
	38=>'10001100010',
	39=>'11010001000',
	40=>'11000101000',
	41=>'11000100010',
	42=>'10110111000',
	43=>'10110001110',
	44=>'10001101110',
	45=>'10111011000',
	46=>'10111000110',
	47=>'10001110110',
	48=>'11101110110',
	49=>'11010001110',
	50=>'11000101110',
	51=>'11011101000',
	52=>'11011100010',
	53=>'11011101110',
	54=>'11101011000',
	55=>'11101000110',
	56=>'11100010110',
	57=>'11101101000',
	58=>'11101100010',
	59=>'11100011010',
	60=>'11101111010',
	61=>'11001000010',
	62=>'11110001010',
	63=>'10100110000',
	64=>'10100001100',
	65=>'10010110000',
	66=>'10010000110',
	67=>'10000101100',
	68=>'10000100110',
	69=>'10110010000',
	70=>'10110000100',
	71=>'10011010000',
	72=>'10011000010',
	73=>'10000110100',
	74=>'10000110010',
	75=>'11000010010',
	76=>'11001010000',
	77=>'11110111010',
	78=>'11000010100',
	79=>'10001111010',
	80=>'10100111100',
	81=>'10010111100',
	82=>'10010011110',
	83=>'10111100100',
	84=>'10011110100',
	85=>'10011110010',
	86=>'11110100100',
	87=>'11110010100',
	88=>'11110010010',
	89=>'11011011110',
	90=>'11011110110',
	91=>'11110110110',
	92=>'10101111000',
	93=>'10100011110',
	94=>'10001011110',
	95=>'10111101000',
	96=>'10111100010',
	97=>'11110101000',
	98=>'11110100010',
	99=>'10111011110',
	100=>'10111101110',
	101=>'11101011110',
	102=>'11110101110');

	for ($j=0;$j<strlen($cod);$j++) {
		$bar .= $code_caracter[(ord($cod[$j])-32)];
	}

	$barcode = 'q'.$start_code[$start].$bar.$code_caracter[$chk_dig].$stop_code.'q';
	return $barcode;
}

//Chitante
function nr2lit($num) {
	$u = array('','unu','doi','trei','patru','cinci','sase','sapte','opt','noua');
	$m = strlen($num);
	$t = 0;
	$d = 'si';
	while ($t<$m) {
		$nr[$m-$t] = $num[$t];
		$t++;	
	}
	$sg = '';
	foreach($nr as $k=>$v) {
	//print_r($nr);
	switch ($k) {
	case '4':
				switch($v) {
				case '1': $sg .= 'omie';break;
				case '2': $sg .= 'douamii';break;
				default: $sg .= $u[$v]."mii";break;
				} break;
	case '3': 
				switch($v) {
				case '1': $sg .= 'unasuta';break;
				case '2': $sg .= 'douasute';break;
				case '0': $sg .= '';break;
				default: $sg .= $u[$v]."sute";break;
				} break;
	case '2': 
				switch($v) {
				case '1': 
					switch($nr[$v]) {
					case '0': $sg .= 'zece';break;
					case '1': $sg .= 'unsprezece';break;
					case '4': $sg .= 'paisprezece';break;
					case '6': $sg .= 'saisprezece';break;
					default : $sg .= $u[$nr[$v]].'sprezece';break;
					}
				break;
				case '2': $sg .= 'douazeci';if (strlen($num) >= 2 && $nr[1] != 0) $sg .= $d;break;
				case '6': $sg .= 'saizeci';if (strlen($num) >= 2 && $nr[1] != 0) $sg .= $d;break;
				case '0': $sg .= '';break;
				default: $sg .= $u[$v]."zeci";if (strlen($num) >= 2 && $nr[1] != 0) $sg .= $d;break;
				} break;
	case '1': if (strlen($num)<2) { $sg .= $u[$v];} else if ($nr[2] != 1) $sg .= $u[$v];break;

				}
	}
	return $sg;
}
//end Chitante

?>