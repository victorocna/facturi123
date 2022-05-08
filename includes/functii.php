<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
include ('config.php');
$buffer = '';

function verifica($id,$nr){
	global $config;
	$dbx = new db($config['host'],$config['user'],$config['pass'],$config['baza']);
	$res = $dbx->query('select * from serii_accesate where id_furnizor = "'.$id.'" and numar = "'.$nr.'"');
	return mysql_num_rows($res);
}

function verifica_serie($id,$serie){
	global $config;
	$dbx = new db($config['host'],$config['user'],$config['pass'],$config['baza']);
	$sql = $dbx->query('select * from firme where id_user="'.$id.'" and substring(serie,1,3)="'.$serie.'" and tip_firma="0"');
	return mysql_num_rows($sql);
}

if ($_GET['op'] == 'login'){
	$sql = $db->query('select * from useri where subdomeniu="'.$_GET['subdomeniu'].'" and user="'.$_GET['user'].'" and parola="'.md5($_GET['parola']).'" and stare="1"');
	if (mysql_num_rows($sql) == 1){
		$row = mysql_fetch_array($sql);
		$_SESSION['user'] = $row['user'];
		$_SESSION['id_user'] = $row['id_user'];
		$_SESSION['subdomeniu'] = $row['subdomeniu'];
		$buffer = '/'.$row['subdomeniu'].'/facturi/'.$row['id_user'].'/';
	}	
	echo $buffer;
}

if ($_GET['op'] == 'logout'){
	unset($_SESSION['id_user']);
	unset($_SESSION['user']);
	unset($_SESSION['subdomeniu']);
	echo "document.location.href = '/".$_GET['subdomeniu']."/';";
}

if ($_GET['op'] == 'verifica_subdomeniu'){
	if (!is_dir('../'.$_GET['subdomeniu']) && $_GET['subdomeniu'] != 'freelancer' && $_GET['subdomeniu'] != 'basic' && $_GET['subdomeniu'] != 'best' && $_GET['subdomeniu'] != 'premium'){
		$sql = $db->query('select * from useri where subdomeniu="'.$_GET['subdomeniu'].'"');
		if (mysql_num_rows($sql) == 0) $buffer = 1;
		else $buffer = 0;
	}
	else $buffer = 0;
	echo $buffer;
}

if ($_GET['op'] == 'verifica_denumire'){
	$buffer = '{';
	if (isset($_GET['id_firma'])) $id_firma = ' and id_firma!="'.$_GET['id_firma'].'"';
	$sql = $db->query('select * from firme where id_user="'.$_SESSION['id_user'].'" and tip_firma="'.$_GET['tip_firma'].'" and denumire="'.htmlentities($_GET['denumire'],ENT_QUOTES).'"'.$id_firma);
	if (mysql_num_rows($sql) == 0) $buffer .= '"fault":"0"';
	if (mysql_num_rows($sql) >= 1){
		$row = mysql_fetch_array($sql);
		$buffer .= '"fault":"1", "id":"'.$row['id_firma'].'", "data_add": "'.convert_data(date('d-m-Y',strtotime($row['data_add']))).'"';
	}
	$buffer .= '}';
	echo $buffer;
}

if ($_GET['op'] == 'verifica_cif'){
	$cif = array_reverse(str_split($_GET['cif']));
	$cheie = array('2', '3', '5', '7', '1', '2', '3', '5', '7');
	$exclus = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','x','y','z');
	$verify = array_diff($cif,$exclus);
	if (count($cif) == count($verify) && strlen($_GET['cif']) >=2 && strlen($_GET['cif']) <= 10){
		for ($i=0; $i<count($cif); $i++){
			$matrice[$i] = $cif[$i+1] * $cheie[$i];
			$suma = $suma + $matrice[$i];
		}
		$verificare = ($suma*10)%11;
		if ($verificare == 10) $verificare = 0;
		if ($verificare == $cif[0]) $buffer = 1;
		else $buffer = 0;
	}
	else $buffer = 0;
	echo $buffer;
}

if ($_GET['op'] == 'verifica_iban'){
	$iban = str_replace(' ','',strtoupper($_GET['iban']));
	$cod = substr($iban,4,strlen($iban)).substr($iban,0,4);
	if (strlen($cod) == 24) {
		$matrice = array('A'=>'10', 'B'=>'11', 'C'=>'12', 'D'=>'13', 'E'=>'14', 'F'=>'15', 'G'=>'16', 'H'=>'17', 'I'=>'18', 'J'=>'19', 'K'=>'20', 'L'=>'21', 'M'=>'22', 'N'=>'23', 'O'=>'24', 'P'=>'25', 'Q'=>'26', 'R'=>'27', 'S'=>'28', 'T'=>'29', 'U'=>'30', 'V'=>'31', 'W'=>'32', 'X'=>'33', 'Y'=>'34', 'Z'=>'35', '0'=>'0', '1'=>'1', '2'=>'2', '3'=>'3', '4'=>'4', '5'=>'5', '6'=>'6', '7'=>'7', '8'=>'8', '9'=>'9');
		for ($i=0; $i<strlen($cod); $i++){
			$suma .= $matrice[$cod[$i]];
		}
		if (bcmod($suma,97) == 1) $buffer = 1;
		else $buffer = 0;
	}	
	else $buffer = 0;
	echo $buffer;
}

if ($_GET['op'] == 'verifica_serie'){
	$sql = $db->query('select * from firme where id_user="'.$_GET['id_user'].'" and serie="'.$_GET['serie'].'" and tip_firma="0"');
	if (mysql_num_rows($sql) == 0) $buffer = 1;
	else $buffer = 0;
	echo $buffer;
}

if ($_GET['op'] == 'verifica_chitanta'){
	$sql = $db->query('select * from firme where id_user="'.$_GET['id_user'].'" and serie_ch="'.$_GET['serie_ch'].'" and tip_firma="0"');
	if (mysql_num_rows($sql) == 0) $buffer = 1;
	else $buffer = 0;
	echo $buffer;
}

if ($_GET['op'] == 'verifica_produs'){
	$buffer = '{';
	if (isset($_GET['id_produs'])) $id_produs = ' and id_produs!="'.$_GET['id_produs'].'"';
	$sql = $db->query('select * from produse where id_user="'.$_SESSION['id_user'].'" and denumire="'.htmlentities($_GET['denumire'],ENT_QUOTES).'"'.$id_produs);
	if (mysql_num_rows($sql) == 0) $buffer .= '"fault":"0"';
	if (mysql_num_rows($sql) >= 1){
		$row = mysql_fetch_array($sql);
		$buffer .= '"fault":"1", "id":"'.$row['id_produs'].'", "data_add": "'.convert_data(date('d-m-Y',strtotime($row['data_add']))).'"';
	}
	$buffer .= '}';
	echo $buffer;
}

if ($_GET['op'] == 'verifica_email'){
	$buffer = '{';
	$sql = $db->query('select * from useri where email="'.$_GET['email'].'" and id_user!="'.$_GET['id_user'].'"');
	if (mysql_num_rows($sql) == 0) $buffer .= '"fault":"0"';
	if (mysql_num_rows($sql) >= 1){
		$row = mysql_fetch_array($sql);
		$buffer .= '"fault":"1"';
	}
	$buffer .= '}';
	echo $buffer;
}

if ($_GET['op'] == 'verifica_cod'){
	//vars
	$cod0 = substr(strrev(strstr(strrev($_GET['cod']),'-')),0,-1);
	$id_factura0 = substr(strrchr($_GET['cod'],'-'),1);
	//end vars
	
	//decrypt cod
	$crypt = new crypt;
	$cod = $crypt->decrypt('123',$cod0);
	$serie = str_replace('-',' ',substr(strrev(strstr(strrev($cod),'-')),0,-1));
	$id_factura = substr(strrchr($cod,'-'),1);
	//end decrypt

	if ($id_factura0 == $id_factura){
		$sql = $db->query('select * from facturi where id_factura="'.$id_factura.'" and serie="'.$serie.'" and stare_email="1" and id_draft="0"');
		if (mysql_num_rows($sql) == 1){
			$se = $db->query('select * from email where id_factura="'.$id_factura.'" order by data_add desc limit 1');
			if (mysql_num_rows($se) == 1) $re = mysql_fetch_array($se);
			$sf = $db->query('select * from firme,facturi where facturi.id_factura="'.$id_factura.'" and facturi.id_furnizor=firme.id_firma and firme.serie="'.$serie.'" and firme.tip_firma="0"');
			if (mysql_num_rows($sf) == 1) $rf = mysql_fetch_array($sf);
			$ss = $db->query('select * from facturi where id_factura="'.$id_factura.'"');
			if (mysql_num_rows($ss) == 1) $rs = mysql_fetch_array($ss);
			$su = $db->query('select * from useri,firme where firme.id_firma="'.$rf['id_firma'].'" and firme.tip_firma="0" and useri.id_user=firme.id_user');
			if (mysql_num_rows($su) == 1) $ru = mysql_fetch_array($su);
			
			$query = "<?php \$_GET['subdomeniu'] = '".$ru['subdomeniu']."'; \$_GET['idf'] = '".$rf['id_user']."'; \$_GET['id_furnizor'] = '".$rf['id_firma']."'; \$_GET['factura'] = '".str_replace(' ','-',$rs['serie'].'-'.$rs['numar'])."'; @include ('../modules/popup_factura_".$re['tip_factura']."_".$rf['tva'].".php'); ?>";
			$fp = fopen('../facturi/'.$_GET['cod'].'.php','w');
			fwrite($fp,$query);
			fclose($fp);
			$buffer = '/facturi/'.$_GET['cod'].'/';
		}
	}
	echo $buffer;
}

if ($_GET['op'] == 'save_firma'){
//furnizor
	if (isset($_GET['furnizor'])) {
		foreach ($_GET as $key => $value){
			if ($key != 'op' && $key != 'furnizor' && $key != 'subdomeniu'){
				$campuri .= $key.',';
				$valori .= '"'.htmlentities($value,ENT_QUOTES).'",';
			}
		}
		$campuri .= 'data_add,tip_firma';
		$valori .= '"'.date('Y-m-d H:i:s').'","0"';
		$query = 'insert into firme('.$campuri.') values('.$valori.')';
		$verify = $db->query($query);
		if ($verify){
			$buffer = mysql_insert_id();
			echo $buffer;
			
			if ($_GET['subdomeniu'] == $_SESSION['subdomeniu']){
				if (!file_exists('../useri/'.$_GET['subdomeniu'].'/'.$buffer)){
					mkdir('../useri/'.$_GET['subdomeniu'].'/'.$buffer);
					mkdir('../useri/'.$_GET['subdomeniu'].'/'.$buffer.'/sigla');
					mkdir('../useri/'.$_GET['subdomeniu'].'/'.$buffer.'/xml');
					mkdir('../useri/'.$_GET['subdomeniu'].'/'.$buffer.'/pdf');
				}
				
				if (isset($_GET['sigla'])) {
					$sigla_old = '../useri/'.$_GET['subdomeniu'].'/temp/'.$_GET['sigla'];
					$sigla_new = '../useri/'.$_GET['subdomeniu'].'/'.$buffer.'/sigla/'.$_GET['sigla'];
					copy($sigla_old,$sigla_new);
					recursiveDelete('../useri/'.$_GET['subdomeniu'].'/temp/');
				}
			}
		}
	}
// client
	if (isset($_GET['client'])) {
		foreach ($_GET as $key => $value){
			if ($key != 'op' && $key != 'client'){
				$campuri .= $key.',';
				$valori .= '"'.htmlentities($value,ENT_QUOTES).'",';
			}
		}
		$campuri .= 'data_add,tip_firma';
		$valori .= '"'.date('Y-m-d H:i:s').'","1"';
		$query = 'insert into firme('.$campuri.') values('.$valori.')';
		$verify = $db->query($query);
		if ($verify) $buffer = mysql_insert_id();
		echo $buffer;
	}
// platitor
	if (isset($_GET['platitor'])) {
		foreach ($_GET as $key => $value){
			if ($key != 'op' && $key != 'platitor'){
				$campuri .= $key.',';
				$valori .= '"'.htmlentities($value,ENT_QUOTES).'",';
			}
		}
		$campuri .= 'data_add,tip_firma';
		$valori .= '"'.date('Y-m-d H:i:s').'","9"';
		$query = 'insert into firme('.$campuri.') values('.$valori.')';
		$verify = $db->query($query);
		if ($verify) $buffer = mysql_insert_id();
		echo $buffer;
	}
}

if ($_GET['op'] == 'save_produs'){
	foreach ($_GET as $key => $value){
		if ($key != 'op'){
			$campuri .= $key.',';
			$valori .= '"'.htmlentities($value,ENT_QUOTES).'",';
		}
	}
	$campuri .= 'data_add';
	$valori .= '"'.date('Y-m-d H:i:s').'"';
	$query = 'insert into produse('.$campuri.') values('.$valori.')';
	$verify = $db->query($query);
	if ($verify) $buffer = mysql_insert_id();
	echo $buffer;
}

if ($_GET['op'] == 'update_cont'){
	foreach ($_GET as $key => $value){
		if ($key != 'op' && $key != 'id_user' && $key != 'parola') $valori .= $key.'="'.htmlentities($value,ENT_QUOTES).'",';
		if ($key == 'parola') $valori .= $key.'="'.md5($value).'",';
	}
	$valori .= 'data_mod="'.date('Y-m-d').'"';
	$query = 'update useri set '.$valori.' where id_user="'.$_GET['id_user'].'"';
	$verify = $db->query($query);
	if ($verify) $buffer = $_GET['id_user'];
	echo $buffer;
}

if ($_GET['op'] == 'update_firma'){
// furnizor
	if (isset($_GET['furnizor'])) {
		foreach ($_GET as $key => $value){
			if ($key != 'op' && $key != 'furnizor' && $key != 'id_firma' && $key != 'id_user')
			$valori .= $key.'="'.htmlentities($value,ENT_QUOTES).'",';
		}
		$valori .= 'data_mod="'.date('Y-m-d H:i:s').'"';
		$query = 'update firme set '.$valori.' where id_firma="'.$_GET['id_firma'].'" and id_user="'.$_GET['id_user'].'" and tip_firma="0"';
		$verify = $db->query($query);
		if ($verify) $buffer = $_GET['id_firma'];
		echo $buffer;
	}
//client
	if (isset($_GET['client'])) {
		foreach ($_GET as $key => $value){
			if ($key != 'op' && $key != 'client' && $key != 'id_firma' && $key != 'id_user')
			$valori .= $key.'="'.htmlentities($value,ENT_QUOTES).'",';
		}
		$valori .= 'data_mod="'.date('Y-m-d H:i:s').'"';
		$query = 'update firme set '.$valori.' where id_firma="'.$_GET['id_firma'].'" and id_user="'.$_GET['id_user'].'" and tip_firma="1"';
		$verify = $db->query($query);
		if ($verify) $buffer = $_GET['id_firma'];
		echo $buffer;
	}
//platitor
	if (isset($_GET['platitor'])) {
		foreach ($_GET as $key => $value){
			if ($key != 'op' && $key != 'platitor' && $key != 'id_firma' && $key != 'id_user')
			$valori .= $key.'="'.htmlentities($value,ENT_QUOTES).'",';
		}
		$valori .= 'data_mod="'.date('Y-m-d H:i:s').'"';
		$query = 'update firme set '.$valori.' where id_firma="'.$_GET['id_firma'].'" and id_user="'.$_GET['id_user'].'" and tip_firma="9"';
		$verify = $db->query($query);
		if ($verify) $buffer = $_GET['id_firma'];
		echo $buffer;
	}
}

if ($_GET['op'] == 'update_produs'){
	foreach ($_GET as $key => $value){
		if ($key != 'op' && $key != 'id_produs' && $key != 'id_user')
		$valori .= $key.'="'.htmlentities($value,ENT_QUOTES).'",';
	}
	$valori .= 'data_mod="'.date('Y-m-d H:i:s').'"';
	$query = 'update produse set '.$valori.' where id_produs="'.$_GET['id_produs'].'" and id_user="'.$_GET['id_user'].'"';
	$verify = $db->query($query);
	if ($verify) $buffer = $_GET['id_produs'];
	echo $buffer;
}

if ($_GET['op'] == 'autocomplete_produs'){
	$sql = $db->query('select * from produse where id_user="'.$_SESSION['id_user'].'" and denumire like "'.$_GET['q'].'%" order by denumire asc limit 10');
	while ($row = mysql_fetch_array($sql)){
		$buffer .= "".$row['id_produs'].";".$row['denumire'].";".$row['unitate']."\n";
	}
	echo $buffer;
}

if ($_GET['op'] == 'autocomplete_unitate'){
	$sql = $db->query('select * from produse where id_user="'.$_SESSION['id_user'].'" and unitate like "'.$_GET['q'].'%" group by unitate limit 10');
	while ($row = mysql_fetch_array($sql)){
		$buffer .= "".$row['id_produs'].";".$row['unitate']."\n";
	}
	echo $buffer;
}

if ($_GET['op'] == 'autocomplete_delegat'){
	$sql = $db->query('select * from delegati where id_client="'.$_GET['id_client'].'" and nume_delegat like "'.$_GET['q'].'%" order by nume_delegat asc limit 10');
	while ($row = mysql_fetch_array($sql)){
		$buffer .= "".$row['id_delegat'].";".$row['nume_delegat'].";".$row['act_identitate']."\n";
	}
	echo $buffer;
}

if ($_GET['op'] == 'autocomplete_reprez'){
	$sql = $db->query('select * from reprezentanti where id_furnizor="'.$_GET['id_furnizor'].'" and nume_reprez like "'.$_GET['q'].'%" order by nume_reprez asc limit 10');
	while ($row = mysql_fetch_array($sql)){
		$buffer .= "".$row['id_reprez'].";".$row['nume_reprez'].";".$row['act_reprez']."\n";
	}
	echo $buffer;
}

if ($_GET['op'] == 'autocomplete_client'){
	$sql = $db->query('select * from firme where id_user="'.$_SESSION['id_user'].'" and denumire like "'.$_GET['q'].'%" and tip_firma="1" order by denumire asc limit 10');
	if (mysql_num_rows($sql) > 0){
		while ($row = mysql_fetch_array($sql)){
			$buffer .= "".$row['id_firma'].";".$row['denumire'].";".$row['cif']."\n";
		}
	}
	else{
		$sql = $db->query('select * from firme having locate(" '.$_GET['q'].'",denumire) > 0 and id_user="'.$_SESSION['id_user'].'" and tip_firma="1" order by denumire asc limit 10');
		while ($row = mysql_fetch_array($sql)){
			$buffer .= "".$row['id_firma'].";".$row['denumire'].";".$row['cif']."\n";
		}
	}
	echo $buffer;
}

if ($_GET['op'] == 'autocomplete_furnizor'){
	$sql = $db->query('select * from firme where denumire like "'.$_GET['q'].'%" and id_user="'.$_SESSION['id_user'].'" and tip_firma="0" order by denumire asc limit 10');
	if (mysql_num_rows($sql) > 0){
		while ($row = mysql_fetch_array($sql)){
			$buffer .= "".$row['id_firma'].";".$row['denumire'].";".$row['cif']."\n";
		}
	}
	else{
		$sql = $db->query('select * from firme having locate(" '.$_GET['q'].'",denumire) > 0 and id_user="'.$_SESSION['id_user'].'" and tip_firma="0" order by denumire asc limit 10');
		while ($row = mysql_fetch_array($sql)){
			$buffer .= "".$row['id_firma'].";".$row['denumire'].";".$row['cif']."\n";
		}
	}
	echo $buffer;
}

if ($_GET['op'] == 'query_id_platitor'){
	$sql = $db->query('select * from firme where denumire="'.$_GET['denumire'].'" and tip_firma="9"');
	if (mysql_num_rows($sql) == 1){
		$row = mysql_fetch_array($sql);
		$buffer = '{"id_firma": "'.$row['id_firma'].'"}';
	}
	else $buffer = '{"id_firma": "0"}';
	echo $buffer;
}

if ($_GET['op'] == 'query_platitor'){
	$sql = $db->query('select * from firme where id_firma="'.$_GET['id_firma'].'" and tip_firma="9"');
	$row = mysql_fetch_array($sql);
		$buffer = '{"id_firma": "'.$row['id_firma'].'", "denumire": "'.$row['denumire'].'", "cif": "'.$row['cif'].'", "reg_com": "'.$row['reg_com'].'", "adresa": "'.$row['adresa'].'", "reg_com": "'.$row['reg_com'].'", "banca": "'.$row['banca'].'", "iban": "'.$row['iban'].'"}';
	echo $buffer;
}

if ($_GET['op'] == 'query_id_client'){
	$sql = $db->query('select * from firme where denumire="'.$_GET['denumire'].'" and tip_firma="1"');
	if (mysql_num_rows($sql) == 1){
		$row = mysql_fetch_array($sql);
		$buffer = '{"id_firma": "'.$row['id_firma'].'"}';
	}
	else $buffer = '{"id_firma": "0"}';
	echo $buffer;
}

if ($_GET['op'] == 'query_client'){
	$sql = $db->query('select * from firme where id_firma="'.$_GET['id_firma'].'" and tip_firma="1"');
	$row = mysql_fetch_array($sql);
		$buffer = '{"id_firma": "'.$row['id_firma'].'", "denumire": "'.$row['denumire'].'", "cif": "'.$row['cif'].'", "reg_com": "'.$row['reg_com'].'", "adresa": "'.$row['adresa'].'", "reg_com": "'.$row['reg_com'].'", "banca": "'.$row['banca'].'", "iban": "'.$row['iban'].'"}';
	echo $buffer;
}

if ($_GET['op'] == 'query_id_furnizor'){
	$db->query('delete from serii_accesate where id_user="'.$_GET['id_user'].'" and serie="'.$_GET['serie'].'" and numar="'.$_GET['numar'].'"');
	$sql = $db->query('select * from firme where denumire="'.$_GET['denumire'].'" and tip_firma="0"');
	if (mysql_num_rows($sql) == 1){
		$row = mysql_fetch_array($sql);
		$buffer = '{"id_firma": "'.$row['id_firma'].'"}';
	}
	else $buffer = '{"id_firma": "0"}';
	echo $buffer;
}

if ($_GET['op'] == 'query_furnizor'){
	$sql = $db->query('select * from firme where id_firma="'.$_GET['id_firma'].'" and tip_firma="0"');
	$row = mysql_fetch_array($sql);
		$buffer = '{"id_firma": "'.$row['id_firma'].'", "denumire": "'.$row['denumire'].'", "cif": "'.$row['cif'].'", "reg_com": "'.$row['reg_com'].'", "adresa": "'.$row['adresa'].'", "reg_com": "'.$row['reg_com'].'", "banca": "'.$row['banca'].'", "iban": "'.$row['iban'].'", "tva": "'.$row['tva'].'"}';
	echo $buffer;
}

if ($_GET['op'] == 'query_produs'){
	$sql = $db->query('select * from produse where id_produs="'.$_GET['id_produs'].'" and id_user="'.$_SESSION['id_user'].'"');
	$row = mysql_fetch_array($sql);
		$buffer .= '{"id": "'.$row['id_produs'].'", "denumire": "'.$row['denumire'].'", "unitate": "'.$row['unitate'].'"}';
	echo $buffer;
}

if ($_GET['op'] == 'query_furnizor_editare'){
	$sql = $db->query('select * from firme where id_user="'.$_GET['id_user'].'" and id_firma="'.$_GET['id_firma'].'" and tip_firma="0"');
	$row = mysql_fetch_array($sql);
	if (isset($_GET['acces'])) $db->query('delete from serii_accesate where id_user="'.$_GET['id_user'].'" and serie="'.$_GET['serie'].'" and numar="'.$_GET['numar'].'"');

	if ($_GET['id_draft'] == 0) $sqls = $db->query('select * from facturi where id_furnizor="'.$_GET['id_firma'].'" and id_factura="'.$_GET['id_factura'].'"');
	else $sqls = $db->query('select * from facturi where id_furnizor="'.$_GET['id_firma'].'" and id_draft="'.$_GET['id_factura'].'"');
	if (mysql_num_rows($sqls) > 0){
		$rows = mysql_fetch_array($sqls);
		$numar = $rows['numar'];
	}
else{
	$ss = $db->query('select lpad(a.numar+1,length(a.numar),"0") as serie_lipsa from facturi as a, facturi as b where a.numar < b.numar and a.id_furnizor="'.$_GET['id_firma'].'" group by a.numar having serie_lipsa < min(b.numar) order by serie_lipsa asc');
	if (mysql_num_rows($ss) > 0){
		while($rs = mysql_fetch_array($ss)){
			if (verifica($_GET['id_firma'],$rs['serie_lipsa']) > 0){
				$sf = $db->query('select lpad(numar+1,length(numar),"0") as serie_noua from facturi where id_furnizor="'.$_GET['id_firma'].'" order by numar desc limit 1');
				$rf = mysql_fetch_array($sf);
				$nr = $rf['serie_noua'];
				$b = verifica($_GET['id_firma'],$nr);
				while ($b>0) {
					$nr++;
					$b = verifica($_GET['id_firma'],str_pad($nr,strlen($rf['serie_noua']),'0',STR_PAD_LEFT));
				}
				$numar = str_pad($nr,strlen($rf['serie_noua']),'0',STR_PAD_LEFT);
				break;
			}
			else{
				$numar = $rs['serie_lipsa'];
				break;
			}
		}
	}
	else{
		$sf = $db->query('select lpad(numar+1,length(numar),"0") as serie_noua from facturi where id_furnizor="'.$_GET['id_firma'].'" order by numar desc limit 1');
		$rf = mysql_fetch_array($sf);
		$b = verifica($_GET['id_firma'],$rf['serie_noua']);
		$nr = $rf['serie_noua'];
		if ($nr != '') {
			while ($b!=0) {
				$nr++;
				$b = verifica($_GET['id_firma'],str_pad($nr,strlen($rf['serie_noua']),'0',STR_PAD_LEFT));
			}
			$numar = str_pad($nr,strlen($rf['serie_noua']),'0',STR_PAD_LEFT);
		}
		else{
			$si = $db->query('select numar from firme where id_user="'.$_SESSION['id_user'].'" and id_firma="'.$_GET['id_firma'].'" and tip_firma="0"');
			$ri = mysql_fetch_array($si);
			$b = verifica($_GET['id_firma'],$ri['numar']);
			$nr = $ri['numar'];
			while ($b!=0) {
				$nr++;
				$b = verifica($_GET['id_firma'],str_pad($nr,strlen($ri['numar']),'0',STR_PAD_LEFT));
			}
			$numar = str_pad($nr,strlen($ri['numar']),'0',STR_PAD_LEFT);
		}
	}
	$sq = $db->query('select id_serie from serii_accesate order by id_serie desc limit 1');
	$rq = mysql_fetch_array($sq);
	$id_serie = ($rq['id_serie']+1);
	$campuri = 'id_serie,id_user,id_furnizor,serie,numar,tip_serie,data_add';
	$valori = '"'.$id_serie.'","'.$_GET['id_user'].'","'.$_GET['id_firma'].'","'.$row['serie'].'","'.$numar.'","1","'.date('Y-m-d H:i:s').'"';
	$query = 'insert into serii_accesate('.$campuri.') values('.$valori.')';
	$db->query($query);
	
	$sf = $db->query('select * from facturi where id_furnizor="'.$_GET['id_firma'].'" order by numar desc limit 1');
	if (mysql_num_rows($sf) > 0){
		$rf = mysql_fetch_array($sf);
		if ($rf['id_draft'] != '0' && $rf['numar'] < $numar){
			$draft = $rf['numar'];
			$id_draft = $rf['id_draft'];
		}
	}
}
	
	$buffer = '{"id_firma": "'.$row['id_firma'].'", "denumire": "'.$row['denumire'].'", "cif": "'.$row['cif'].'", "reg_com": "'.$row['reg_com'].'", "adresa": "'.$row['adresa'].'", "banca": "'.$row['banca'].'", "iban": "'.$row['iban'].'", "tva": "'.$row['tva'].'", "serie": "'.$row['serie'].'", "numar": "'.$numar.'", "draft": "'.$draft.'", "id_draft": "'.$id_draft.'"}';
	echo $buffer;
}

if ($_GET['op'] == 'query_furnizor_serie'){
	$sql = $db->query('select * from firme where id_user="'.$_GET['id_user'].'" and id_firma="'.$_GET['id_firma'].'" and tip_firma="0"');
	$row = mysql_fetch_array($sql);
	if (isset($_GET['acces'])) $db->query('delete from serii_accesate where id_user="'.$_GET['id_user'].'" and serie="'.$_GET['serie'].'" and numar="'.$_GET['numar'].'"');
	
	$ss = $db->query('select lpad(a.numar+1,length(a.numar),"0") as serie_lipsa from facturi as a, facturi as b where a.numar < b.numar and a.id_furnizor="'.$_GET['id_firma'].'" group by a.numar having serie_lipsa < min(b.numar) order by serie_lipsa asc');
	if (mysql_num_rows($ss) > 0){
		while($rs = mysql_fetch_array($ss)){
			if (verifica($_GET['id_firma'],$rs['serie_lipsa']) > 0){
				$sf = $db->query('select lpad(numar+1,length(numar),"0") as serie_noua from facturi where id_furnizor="'.$_GET['id_firma'].'" order by numar desc limit 1');
				$rf = mysql_fetch_array($sf);
				$nr = $rf['serie_noua'];
				$b = verifica($_GET['id_firma'],$nr);
				while ($b>0) {
					$nr++;
					$b = verifica($_GET['id_firma'],str_pad($nr,strlen($rf['serie_noua']),'0',STR_PAD_LEFT));
				}
				$numar = str_pad($nr,strlen($rf['serie_noua']),'0',STR_PAD_LEFT);
				break;
			}
			else{
				$numar = $rs['serie_lipsa'];
				break;
			}
		}
	}
	else{
		$sf = $db->query('select lpad(numar+1,length(numar),"0") as serie_noua from facturi where id_furnizor="'.$_GET['id_firma'].'" order by numar desc limit 1');
		$rf = mysql_fetch_array($sf);
		$b = verifica($_GET['id_firma'],$rf['serie_noua']);
		$nr = $rf['serie_noua'];
		if ($nr != '') {
			while ($b!=0) {
				$nr++;
				$b = verifica($_GET['id_firma'],str_pad($nr,strlen($rf['serie_noua']),'0',STR_PAD_LEFT));
			}
			$numar = str_pad($nr,strlen($rf['serie_noua']),'0',STR_PAD_LEFT);
		}
		else{
			$si = $db->query('select numar from firme where id_user="'.$_SESSION['id_user'].'" and id_firma="'.$_GET['id_firma'].'" and tip_firma="0"');
			$ri = mysql_fetch_array($si);
			$b = verifica($_GET['id_firma'],$ri['numar']);
			$nr = $ri['numar'];
			while ($b!=0) {
				$nr++;
				$b = verifica($_GET['id_firma'],str_pad($nr,strlen($ri['numar']),'0',STR_PAD_LEFT));
			}
			$numar = str_pad($nr,strlen($ri['numar']),'0',STR_PAD_LEFT);
		}
	}
	
	$sq = $db->query('select id_serie from serii_accesate order by id_serie desc limit 1');
	$rq = mysql_fetch_array($sq);
	$id_serie = ($rq['id_serie']+1);
	$campuri = 'id_serie,id_user,id_furnizor,serie,numar,tip_serie,data_add';
	$valori = '"'.$id_serie.'","'.$_GET['id_user'].'","'.$_GET['id_firma'].'","'.$row['serie'].'","'.$numar.'","1","'.date('Y-m-d H:i:s').'"';
	$query = 'insert into serii_accesate('.$campuri.') values('.$valori.')';
	$db->query($query);
	
	$sf = $db->query('select * from facturi where id_furnizor="'.$_GET['id_firma'].'" order by numar desc limit 1');
	if (mysql_num_rows($sf) > 0){
		$rf = mysql_fetch_array($sf);
		if ($rf['id_draft'] != '0' && $rf['numar'] < $numar){
			$draft = $rf['numar'];
			$id_draft = $rf['id_draft'];
		}
	}
	
	$buffer = '{"id_firma": "'.$row['id_firma'].'", "denumire": "'.$row['denumire'].'", "cif": "'.$row['cif'].'", "reg_com": "'.$row['reg_com'].'", "adresa": "'.$row['adresa'].'", "banca": "'.$row['banca'].'", "iban": "'.$row['iban'].'", "tva": "'.$row['tva'].'", "serie": "'.$row['serie'].'", "numar": "'.$numar.'", "draft": "'.$draft.'", "id_draft": "'.$id_draft.'"}';
	echo $buffer;
}

if ($_GET['op'] == 'init_factura'){
	$denumire = str_replace('.','',$_GET['denumire']);
	if (substr($denumire,0,3) == 'sc ') $denumire = substr($denumire,3,strlen($denumire));
	if (substr($denumire,-4) == ' srl') $denumire = substr($denumire,0,strlen($denumire)-4);
	if (substr($denumire,-3) == ' sa') $denumire = substr($denumire,0,strlen($denumire)-3);
	
	if (str_word_count($denumire) <= 1){
		$x = substr($denumire,0,3);
		if (strlen($x) < 3){
			$x .= "zz";
			$x = substr($x,0,3);
		}
	}
	if (str_word_count($denumire) == 2){
		$lit = explode(" ",$denumire);
		if (strlen($lit[0]) == 1){
			$x = substr($lit[0],0,1).substr($lit[1],0,2);
		}
		else $x = substr($lit[0],0,2).substr($lit[1],0,1);
	}
	if (str_word_count($denumire) >= 3){
		$lit = explode(" ",$denumire);
		$x = substr($lit[0],0,1).substr($lit[1],0,1).substr($lit[2],0,1);
	}
	
	if (ord(substr($x,2,1)) <= 110){
		$sr = verifica_serie($_GET['id_user'],$x);
		while ($sr>0) {
			$y = ord(substr($x,2,1));
			$x = str_replace(substr($x,2,1),chr(($y+1)),$x);
			$sr = verifica_serie($_GET['id_user'],$x);
		}
	}
	else{
		$sr = verifica_serie($_GET['id_user'],$x);
		while ($sr>0) {
			$y = ord(substr($x,2,1));
			$x = str_replace(substr($x,2,1),chr(($y-1)),$x);
			$sr = verifica_serie($_GET['id_user'],$x);
		}
	}
	
	//$serie_ch = substr($serie,0,-1).'ch';
	$serie = $x.' f';
	$serie_ch = $x.' ch';
	$buffer = '{"serie": "'.$serie.'", "serie_ch": "'.$serie_ch.'"}';
	echo $buffer;
}

if ($_GET['op'] == 'init_chitanta'){
	$sql = $db->query('select * from chitante where id_furnizor="'.$_GET['id_furnizor'].'" order by numar_ch desc limit 1');
	if (mysql_num_rows($sql) == 1){
		$row = mysql_fetch_array($sql);
		$serie_ch = strtoupper($row['serie_ch']);
		$nr = ($row['numar_ch']+1);
		$numar_ch = str_pad($nr,strlen($row['numar_ch']),'0',STR_PAD_LEFT);
	}
	else{
		$sqls = $db->query('select * from firme where id_firma="'.$_GET['id_furnizor'].'"');
		$rows = mysql_fetch_array($sqls);
		$serie_ch = strtoupper($rows['serie_ch']);
		$numar_ch = $rows['numar_ch'];
	}
	$buffer = '{"serie_ch": "'.$serie_ch.'", "numar_ch": "'.$numar_ch.'"}';
	echo $buffer;
}

if ($_POST['op'] == 'autosave'){
	$sf = $db->query('select id_factura from facturi order by id_factura desc limit 1');
	$rf = mysql_fetch_array($sf);
	$id_draft = ($rf['id_factura']+1);
	
	if (isset($_POST['delegat'])){
		if (isset($_POST['id_delegat'])){
			$campuri .= 'id_delegat,';
			$valori .= '"'.$_POST['id_delegat'].'",';
		}
		else{
			$db->query('insert into delegati(id_client,nume_delegat,act_identitate,id_draft) values("'.$_POST['id_client'].'","'.$_POST['delegat'].'","'.$_POST['act_identitate'].'","'.$id_draft.'")');
			$id_delegat = mysql_insert_id();
			$campuri .= 'id_delegat,';
			$valori .= '"'.$id_delegat.'",';
		}
	}
	if (isset($_POST['reprez'])){
		if (isset($_POST['id_reprez'])){
			$campuri .= 'id_reprez,';
			$valori .= '"'.$_POST['id_reprez'].'",';
		}
		else{
			$db->query('insert into reprezentanti(id_furnizor,nume_reprez,act_reprez,id_draft) values("'.$_POST['id_furnizor'].'","'.$_POST['reprez'].'","'.$_POST['act_reprez'].'","'.$id_draft.'")');
			$id_reprez = mysql_insert_id();
			$campuri .= 'id_reprez,';
			$valori .= '"'.$id_reprez.'",';
		}
	}
	
	foreach ($_POST as $key => $value){
		if ($key != 'op' && $key != 'idf' && $key != 'id_delegat' && $key != 'delegat' && $key != 'act_identitate' && $key != 'id_reprez' && $key != 'reprez' && $key != 'act_reprez' && $key != 'jsn'){
			if ($key == 'data_factura' || $key == 'data_scadenta'){
				$campuri .= $key.',';
				$valori .= '"'.date('Y-m-d',strtotime($value)).'",';
			}
			else{
				$campuri .= $key.',';
				$valori .= '"'.$value.'",';
			}	
		}	
	}
	$campuri .= 'data_add,id_draft';
	$valori .= '"'.date('Y-m-d H:i:s').'","'.$id_draft.'"';
	$query = 'insert into facturi('.$campuri.') values('.$valori.')';
	$db->query($query);
	$id_factura = mysql_insert_id();
	
	$x = stripcslashes($_POST['jsn']);
	$json = json_decode($x,true);
	foreach ($json['linii'] as $key => $value){
		$sl = $db->query('select id_linie from linii order by id_linie desc limit 1');
		$rl = mysql_fetch_array($sl);
		$id_linie = ($rl['id_linie']+1);
		
		$sp = $db->query('select id_produs from produse order by id_produs desc limit 1');
		$rp = mysql_fetch_array($sp);
		$id_produs = ($rp['id_produs']+1);
		
		$q = 'insert into linii(id_linie,id_factura,id_produs,q,pret,val,tva,id_draft) values("'.$id_linie.'","'.$id_factura.'"';
		$linii = '';
		foreach ($value as $k => $v){
			if ($k == 'id_produs'){
				$linii .= ',"'.$v.'"';
			}
			if ($k == 'produs' && !isset($json['linii'][$key]['id_produs'])){
				$db->query('insert into produse(id_produs,id_user,denumire,unitate,data_add,id_draft) values("'.$id_produs.'","'.$_POST['idf'].'","'.$json['linii'][$key]['produs'].'","'.$json['linii'][$key]['um'].'","'.date('Y-m-d H:i:s').'","'.$id_draft.'")');
				$linii .= ',"'.$id_produs.'"';
			}
			if ($k != 'id_produs' && $k != 'produs' && $k != 'um'){
				$linii .= ',"'.$v.'"';
			}
		}
		$q .= $linii.',"'.$id_draft.'")';
		$db->query($q);
	}

	echo $id_factura;
}

if ($_POST['op'] == 'update_autosave'){
	$db->query('delete from delegati where id_draft="'.$_POST['id_factura'].'"');
	if (isset($_POST['delegat'])){
		if (isset($_POST['id_delegat'])){
			$update .= 'id_delegat="'.$_POST['id_delegat'].'",';
		}
		else{
			$db->query('insert into delegati(id_client,nume_delegat,act_identitate,id_draft) values("'.$_POST['id_client'].'","'.$_POST['delegat'].'","'.$_POST['act_identitate'].'","'.$_POST['id_factura'].'")');
			$id_delegat = mysql_insert_id();
			$update .= 'id_delegat="'.$id_delegat.'",';
		}
	}
	else $update .= 'id_delegat=null,';
	$db->query('delete from reprezentanti where id_draft="'.$_POST['id_factura'].'"');
	if (isset($_POST['reprez'])){
		if (isset($_POST['id_reprez'])){
			$update .= 'id_reprez="'.$_POST['id_reprez'].'",';
		}
		else{
			$db->query('insert into reprezentanti(id_furnizor,nume_reprez,act_reprez,id_draft) values("'.$_POST['id_furnizor'].'","'.$_POST['reprez'].'","'.$_POST['act_reprez'].'","'.$_POST['id_factura'].'")');
			$id_reprez = mysql_insert_id();
			$update .= 'id_reprez="'.$id_reprez.'",';
		}
	}
	else $update .= 'id_reprez=null,';
	
	foreach ($_POST as $key => $value){
		if ($key != 'op' && $key != 'idf' && $key != 'id_delegat' && $key != 'delegat' && $key != 'act_identitate' && $key != 'id_reprez' && $key != 'reprez' && $key != 'act_reprez' && $key != 'id_factura' && $key != 'jsn'){
			if ($key == 'data_factura' || $key == 'data_scadenta')	$update .= $key.'="'.date('Y-m-d',strtotime($value)).'",';
			else $update .= $key.'="'.$value.'",';
		}	
	}
	$update .= 'data_add="'.date('Y-m-d H:i:s').'"';
	$query = 'update facturi set '.$update.' where id_factura="'.$_POST['id_factura'].'"';
	$db->query($query);
	
	$db->query('delete from produse where id_draft="'.$_POST['id_factura'].'"');
	$db->query('delete from linii where id_draft="'.$_POST['id_factura'].'"');
	$x = stripcslashes($_POST['jsn']);
	$json = json_decode($x,true);
	foreach ($json['linii'] as $key => $value){
		$sl = $db->query('select id_linie from linii order by id_linie desc limit 1');
		$rl = mysql_fetch_array($sl);
		$id_linie = ($rl['id_linie']+1);
		
		$sp = $db->query('select id_produs from produse order by id_produs desc limit 1');
		$rp = mysql_fetch_array($sp);
		$id_produs = ($rp['id_produs']+1);
		
		$q = 'insert into linii(id_linie,id_factura,id_produs,q,pret,val,tva,id_draft) values("'.$id_linie.'","'.$_POST['id_factura'].'"';
		$linii = '';
		foreach ($value as $k => $v){
			if ($k == 'id_produs'){
				$linii .= ',"'.$v.'"';
			}
			if ($k == 'produs' && !isset($json['linii'][$key]['id_produs'])){
				$db->query('insert into produse(id_produs,id_user,denumire,unitate,data_add,id_draft) values("'.$id_produs.'","'.$_POST['idf'].'","'.$json['linii'][$key]['produs'].'","'.$json['linii'][$key]['um'].'","'.date('Y-m-d H:i:s').'","'.$_POST['id_factura'].'")');
				$linii .= ',"'.$id_produs.'"';
			}
			if ($k != 'id_produs' && $k != 'produs' && $k != 'um'){
				$linii .= ',"'.$v.'"';
			}
		}
		$q .= $linii.',"'.$_POST['id_factura'].'")';
		$db->query($q);
	}
	echo $_POST['id_factura'];
}

if ($_POST['op'] == 'save_factura'){
	$xml = '<?xml version="1.0" encoding="UTF-8"?>';
	$xml .= '<facturi>';
	foreach ($_POST as $key => $value){
		if ($key == 'serie' || $key == 'numar' || $key == 'data_factura'){
			$xml .= '<'.$key.'>'.$value.'</'.$key.'>';
		}
	}
	$db->query('delete from serii_accesate where id_furnizor="'.$_POST['id_furnizor'].'" and serie="'.$_POST['serie'].'" and numar="'.$_POST['numar'].'"');
	$xml .= '<adv>';
	if (isset($_POST['delegat'])){
		if (isset($_POST['id_delegat'])){
			$id_delegat = $_POST['id_delegat'];
			$campuri .= 'id_delegat,';
			$valori .= '"'.$_POST['id_delegat'].'",';
		}
		else{
			$db->query('insert into delegati(id_client,nume_delegat,act_identitate,id_draft) values("'.$_POST['id_client'].'","'.$_POST['delegat'].'","'.$_POST['act_identitate'].'","0")');
			$id_delegat = mysql_insert_id();
			$campuri .= 'id_delegat,';
			$valori .= '"'.$id_delegat.'",';
		}
		$xml .= '<delegat id="'.$id_delegat.'"></delegat>';
	}
	
	if (isset($_POST['reprez'])){
		if (isset($_POST['id_reprez'])){
			$id_reprez = $_POST['id_reprez'];
			$campuri .= 'id_reprez,';
			$valori .= '"'.$_POST['id_reprez'].'",';
		}
		else{
			$db->query('insert into reprezentanti(id_furnizor,nume_reprez,act_reprez,id_draft) values("'.$_POST['id_furnizor'].'","'.$_POST['reprez'].'","'.$_POST['act_reprez'].'","0")');
			$id_reprez = mysql_insert_id();
			$campuri .= 'id_reprez,';
			$valori .= '"'.$id_reprez.'",';
		}
		$xml .= '<reprez id="'.$id_reprez.'"></reprez>';
	}
	
	foreach ($_POST as $key => $value){
		if ($key == 'cota_tva' || $key == 'valuta' || $key == 'data_scadenta' || $key == 'observatii'){
			$xml .= '<'.$key.'>'.$value.'</'.$key.'>';
		}
		if ($key != 'op' && $key != 'idf' && $key != 'id_delegat' && $key != 'delegat' && $key != 'act_identitate' && $key != 'id_reprez' && $key != 'reprez' && $key != 'act_reprez' && $key != 'jsn'){
			if ($key == 'data_factura' || $key == 'data_scadenta'){
				$campuri .= $key.',';
				$value = date('Y-m-d',strtotime($value));
				$valori .= '"'.$value.'",';
			}
			else{
				$campuri .= $key.',';
				$valori .= '"'.$value.'",';
			}	
		}
	}
	$xml .= '</adv>';
	
	$campuri .= 'data_add,stare_incasare,id_draft';
	$valori .= '"'.date('Y-m-d H:i:s').'","0","0"';
	$query = 'insert into facturi('.$campuri.') values('.$valori.')';
	$db->query($query);
	$id_factura = mysql_insert_id();

	$xml = str_replace('<facturi>','<facturi id="'.$id_factura.'">',$xml);
	$x = stripcslashes($_POST['jsn']);
	$json = json_decode($x,true);

	foreach ($json['linii'] as $key => $value){
		$sl = $db->query('select id_linie from linii order by id_linie desc limit 1');
		$rl = mysql_fetch_array($sl);
		$id_linie = ($rl['id_linie']+1);
		
		$sp = $db->query('select id_produs from produse order by id_produs desc limit 1');
		$rp = mysql_fetch_array($sp);
		$id_produs = ($rp['id_produs']+1);
		
		$q = 'insert into linii(id_linie,id_factura,id_produs,q,pret,val,tva,id_draft) values("'.$id_linie.'","'.$id_factura.'"';
		$linii = '';
		foreach ($value as $k => $v){
			if ($k == 'id_produs'){
				$linii .= ',"'.$v.'"';
			}
			if ($k == 'produs' && !isset($json['linii'][$key]['id_produs'])){
				$db->query('insert into produse(id_produs,id_user,denumire,unitate,data_add,id_draft) values("'.$id_produs.'","'.$_POST['idf'].'","'.$json['linii'][$key]['produs'].'","'.$json['linii'][$key]['um'].'","'.date('Y-m-d H:i:s').'","0")');
				$linii .= ',"'.$id_produs.'"';
			}
			if ($k != 'id_produs' && $k != 'produs' && $k != 'um'){
				$linii .= ',"'.$v.'"';
			}
		}
		$q .= $linii.',"0")';
		$db->query($q);
	}

	$xml .= '<client id="'.$_POST['id_client'].'"></client>';
	$xml .= '<furnizor id="'.$_POST['id_furnizor'].'"></furnizor>';
	$xml .= '<linii>';
	$linii = $db->query('select * from linii,produse where produse.id_produs = linii.id_produs and id_factura="'.$id_factura.'"');
	$j = 1;
	while ($matrice = mysql_fetch_array($linii)){
		$xml .= '<linie id="'.$j.'"><denumire id="'.$matrice['id_produs'].'"></denumire><cantitate>'.$matrice['q'].'</cantitate><pret>'.$matrice['pret'].'</pret><tva>'.$matrice['tva'].'</tva><valoare>'.$matrice['val'].'</valoare></linie>';
		$j++;
	}
	$xml .= '</linii>';
	
	$xml .= '<total_tva>'.$_POST['total_tva'].'</total_tva><total_valoare>'.$_POST['total_valoare'].'</total_valoare>';
	$xml .= '<total_general>'.$_POST['total_general'].'</total_general>';
	$xml .= '</facturi>';
	
	$fp = fopen('../useri/'.$_SESSION['subdomeniu'].'/'.$_POST['id_furnizor'].'/xml/'.str_replace(' ','-',$_POST['serie']).'-'.$_POST['numar'].'.xml','w');
	fwrite($fp,$xml);
	fclose($fp);
	
	$buffer = '{"idf": "'.$_POST['idf'].'", "id_furnizor": "'.$_POST['id_furnizor'].'", "factura": "'.str_replace(' ','-',$_POST['serie']).'-'.$_POST['numar'].'"}';
	echo $buffer;
}

if ($_POST['op'] == 'update_factura'){
	$xml = '<?xml version="1.0" encoding="UTF-8"?>';
	$xml .= '<facturi>';
	foreach ($_POST as $key => $value){
		if ($key == 'serie' || $key == 'numar' || $key == 'data_factura'){
			$xml .= '<'.$key.'>'.$value.'</'.$key.'>';
		}
	}
	$xml .= '<adv>';
	$db->query('delete from delegati where id_draft="'.$_POST['id_factura'].'"');
	if (isset($_POST['delegat'])){
		if (isset($_POST['id_delegat'])){
			$id_delegat = $_POST['id_delegat'];
			$update .= 'id_delegat="'.$_POST['id_delegat'].'",';
		}
		else{
			$db->query('insert into delegati(id_client,nume_delegat,act_identitate,id_draft) values("'.$_POST['id_client'].'","'.$_POST['delegat'].'","'.$_POST['act_identitate'].'","0")');
			$id_delegat = mysql_insert_id();
			$update .= 'id_delegat="'.$id_delegat.'",';
		}
		$xml .= '<delegat id="'.$id_delegat.'"></delegat>';
	}
	else $update .= 'id_delegat=null,';
	
	$db->query('delete from reprezentanti where id_draft="'.$_POST['id_factura'].'"');
	if (isset($_POST['reprez'])){
		if (isset($_POST['id_reprez'])){
			$id_reprez = $_POST['id_reprez'];
			$update .= 'id_reprez="'.$_POST['id_reprez'].'",';
		}
		else{
			$db->query('insert into reprezentanti(id_furnizor,nume_reprez,act_reprez,id_draft) values("'.$_POST['id_furnizor'].'","'.$_POST['reprez'].'","'.$_POST['act_reprez'].'","0")');
			$id_reprez = mysql_insert_id();
			$update .= 'id_reprez="'.$id_reprez.'",';
		}
		$xml .= '<reprez id="'.$id_reprez.'"></reprez>';
	}
	else $update .= 'id_reprez=null,';
	
	foreach ($_POST as $key => $value){
		if ($key == 'cota_tva' || $key == 'valuta' || $key == 'data_scadenta' || $key == 'observatii'){
			$xml .= '<'.$key.'>'.$value.'</'.$key.'>';
		}
		if ($key != 'op' && $key != 'idf' && $key != 'id_delegat' && $key != 'delegat' && $key != 'act_identitate' && $key != 'id_reprez' && $key != 'reprez' && $key != 'act_reprez' && $key != 'id_factura' && $key != 'jsn' && $key != 'editare'){
			if ($key == 'data_factura' || $key == 'data_scadenta')	$update .= $key.'="'.date('Y-m-d',strtotime($value)).'",';
			else $update .= $key.'="'.$value.'",';
		}
	}
	if (!isset($_POST['data_scadenta'])) $update.='data_scadenta=null,';
	if (!isset($_POST['observatii'])) $update.='observatii=null,';
	$xml .= '</adv>';
	
	if (isset($_POST['editare']) && $_POST['editare'] == '0'){
		$db->query('delete from incasare,chitante using incasare inner join chitante where incasare.id_factura="'.$_POST['id_factura'].'" and incasare.id_chitanta=chitante.id_chitanta');
		$db->query('delete from incasare where id_factura="'.$_POST['id_factura'].'"');
		$update .= 'stare_incasare="0",';
	}
	$update .= 'data_mod="'.date('Y-m-d H:i:s').'",id_draft="0"';
	$query = 'update facturi set '.$update.' where id_factura="'.$_POST['id_factura'].'"';
	$db->query($query);
	$id_factura = $_POST['id_factura'];

	$db->query('delete from produse where id_draft="'.$_POST['id_factura'].'"');
	if (isset($_POST['editare'])) $db->query('delete from linii where id_factura="'.$_POST['id_factura'].'"');
	else $db->query('delete from linii where id_draft="'.$_POST['id_factura'].'"');

	$xml = str_replace('<facturi>','<facturi id="'.$_POST['id_factura'].'">',$xml);
	$x = stripcslashes($_POST['jsn']);
	$json = json_decode($x,true);

	foreach ($json['linii'] as $key => $value){
		$sl = $db->query('select id_linie from linii order by id_linie desc limit 1');
		$rl = mysql_fetch_array($sl);
		$id_linie = ($rl['id_linie']+1);
		
		$sp = $db->query('select id_produs from produse order by id_produs desc limit 1');
		$rp = mysql_fetch_array($sp);
		$id_produs = ($rp['id_produs']+1);
		
		$q = 'insert into linii(id_linie,id_factura,id_produs,q,pret,val,tva,id_draft) values("'.$id_linie.'","'.$id_factura.'"';
		$linii = '';
		foreach ($value as $k => $v){
			if ($k == 'id_produs'){
				$linii .= ',"'.$v.'"';
			}
			if ($k == 'produs' && !isset($json['linii'][$key]['id_produs'])){
				$db->query('insert into produse(id_produs,id_user,denumire,unitate,data_add,id_draft) values("'.$id_produs.'","'.$_POST['idf'].'","'.$json['linii'][$key]['produs'].'","'.$json['linii'][$key]['um'].'","'.date('Y-m-d H:i:s').'","0")');
				$linii .= ',"'.$id_produs.'"';
			}
			if ($k != 'id_produs' && $k != 'produs' && $k != 'um'){
				$linii .= ',"'.$v.'"';
			}
		}
		$q .= $linii.',"0")';
		$db->query($q);
	}

	$xml .= '<client id="'.$_POST['id_client'].'"></client>';
	$xml .= '<furnizor id="'.$_POST['id_furnizor'].'"></furnizor>';
	$xml .= '<linii>';
	$linii = $db->query('select * from linii,produse where produse.id_produs = linii.id_produs and id_factura="'.$id_factura.'"');
	$j = 1;
	while ($matrice = mysql_fetch_array($linii)){
		$xml .= '<linie id="'.$j.'"><denumire id="'.$matrice['id_produs'].'"></denumire><cantitate>'.$matrice['q'].'</cantitate><pret>'.$matrice['pret'].'</pret><tva>'.$matrice['tva'].'</tva><valoare>'.$matrice['val'].'</valoare></linie>';
		$j++;
	}
	$xml .= '</linii>';
	
	$xml .= '<total_tva>'.$_POST['total_tva'].'</total_tva><total_valoare>'.$_POST['total_valoare'].'</total_valoare>';
	$xml .= '<total_general>'.$_POST['total_general'].'</total_general>';
	if (isset($_POST['editare']) && $_POST['editare'] != '0') $xml .= '<rest_plata>'.$_POST['editare'].'</rest_plata>';
	$xml .= '</facturi>';
	
	$fp = fopen('../useri/'.$_SESSION['subdomeniu'].'/'.$_POST['id_furnizor'].'/xml/'.str_replace(' ','-',$_POST['serie']).'-'.$_POST['numar'].'.xml','w');
	fwrite($fp,$xml);
	fclose($fp);

	$buffer = '{"idf": "'.$_POST['idf'].'", "id_furnizor": "'.$_POST['id_furnizor'].'", "factura": "'.str_replace(' ','-',$_POST['serie']).'-'.$_POST['numar'].'"}';
	echo $buffer;
}

if ($_GET['op'] == 'update_text_client'){
	$sql = $db->query('update facturi set text_client="'.htmlentities($_GET['text_client']).'" where id_factura="'.$_GET['id_factura'].'"');
	$filename = '../useri/'.$_SESSION['subdomeniu'].'/'.$_GET['id_furnizor'].'/xml/'.$_GET['factura'].'.xml';
	$fp = fopen($filename,'r');
	$text = fread($fp,filesize($filename));
	fclose($fp);
	$fp = fopen($filename,'w');
	$pos = strpos($text,'<text_client>');
	if ($pos === false) $buffer = str_replace('</facturi>','<text_client>'.htmlentities($_GET['text_client']).'</text_client></facturi>',$text);
	else{
		$start = (strpos($text,'<text_client>')+13);
		$stop = (strpos($text,'</text_client>')-$start);
		$replace = substr($text,$start,$stop);
		$buffer = str_replace('<text_client>'.$replace.'</text_client>','<text_client>'.htmlentities($_GET['text_client']).'</text_client>',$text);
	}
	fwrite($fp,$buffer);
	fclose($fp);
	echo 'Mesajul a fost salvat';
}

if ($_GET['op'] == 'delete_text_client'){
	$sql = $db->query('update facturi set text_client="" where id_factura="'.$_GET['id_factura'].'"');
	$filename = '../useri/'.$_SESSION['subdomeniu'].'/'.$_GET['id_furnizor'].'/xml/'.$_GET['factura'].'.xml';
	$fp = fopen($filename,'r');
	$text = fread($fp,filesize($filename));
	fclose($fp);
	$fp = fopen($filename,'w');
	$pos = strpos($text,'<text_client>');
	$start = (strpos($text,'<text_client>')+13);
	$stop = (strpos($text,'</text_client>')-$start);
	$replace = substr($text,$start,$stop);
	$buffer = str_replace('<text_client>'.$replace.'</text_client>','',$text);
	fwrite($fp,$buffer);
	fclose($fp);
	echo 'Mesajul a fost sters';
}

if ($_GET['op'] == 'save_chitanta'){
	foreach ($_GET as $key => $value){
		if ($key != 'op'){
			$campuri .= $key.',';
			$valori .= '"'.$value.'",';
		}
	}
	$campuri = substr($campuri,0,-1);
	$valori = substr($valori,0,-1);
	$query = 'insert into chitante('.$campuri.') values('.$valori.')';
 	$db->query($query);
	$buffer = mysql_insert_id();
	echo $buffer;
}

if ($_GET['op'] == 'save_incasare'){
	foreach ($_GET as $key => $value){
		if ($key != 'op' && $key != 'factura' && $key != 'subdomeniu' && $key != 'id_furnizor'){
			if ($key == 'data_incasare'){
				$campuri .= $key.',';
				$value = date('Y-m-d',strtotime($value));
				$valori .= '"'.$value.'",';
			}
			else{
				$campuri .= $key.',';
				$valori .= '"'.$value.'",';
			}
		}
	}
	$campuri .= 'data_add';
	$valori .= '"'.date('Y-m-d H:i:s').'"';
	$query = 'insert into incasare('.$campuri.') values('.$valori.')';
 	$db->query($query);
	$id = mysql_insert_id();
	if (isset($_GET['id_chitanta'])) $buffer = '{"id": "'.$id.'", "id_chitanta": "'.$_GET['id_chitanta'].'"}';
	else $buffer = '{"id": "'.$id.'"}';
	echo $buffer;
	
	if ($_GET['rest_plata'] == 0) $stare_incasare = '2';
	else $stare_incasare = '1';
	$db->query('update facturi set stare_incasare="'.$stare_incasare.'" where id_factura="'.$_GET['id_factura'].'"');
	
	$filename = '../useri/'.$_GET['subdomeniu'].'/'.$_GET['id_furnizor'].'/xml/'.$_GET['factura'].'.xml';
	$fp = fopen($filename,'r');
	$text = fread($fp,filesize($filename));
	fclose($fp);
	$fp = fopen($filename,'w');
	$pos = strpos($text,'<rest_plata>');
	if ($pos === false) $buffer = str_replace('</facturi>','<rest_plata>'.$_GET['rest_plata'].'</rest_plata></facturi>',$text);
	else{
		$start = (strpos($text,'<rest_plata>')+12);
		$stop = (strpos($text,'</rest_plata>')-$start);
		$replace = substr($text,$start,$stop);
		$buffer = str_replace('<rest_plata>'.$replace.'</rest_plata>','<rest_plata>'.$_GET['rest_plata'].'</rest_plata>',$text);
	}
	fwrite($fp,$buffer);
	fclose($fp);
}

if ($_GET['op'] == 'update_draft'){
	$buffer = '{';
	$sql = $db->query('select count(distinct(facturi.numar)) as nr from facturi,firme where firme.id_user="'.$_GET['id_user'].'" and facturi.id_furnizor="'.$_GET['id_furnizor'].'" and facturi.id_draft!="0"');
while ($row = mysql_fetch_array($sql)){
	if ($row['nr'] > 0){
		$buffer .= '"nr": "'.$row['nr'].'",';
		$sql2 = $db->query('select * from facturi where id_furnizor="'.$_GET['id_furnizor'].'" and id_draft!="0" order by numar desc limit 5');
		if (mysql_num_rows($sql2) > 0){
			$buffer .= '"facturi":[';
			while ($row2 = mysql_fetch_array($sql2)){
				$buffer .= '{"id_user": "'.$_GET['id_user'].'", "id_draft": "'.$row2['id_draft'].'","serie": "'.$row2['serie'].'", "numar": "'.$row2['numar'].'", "data_factura": "'.convert_data(date('d-m-Y',strtotime($row2['data_factura']))).'"},';
			}
			$buffer = substr($buffer,0,-1);
			$buffer .= ']';
		}
	}
}
	$buffer .= '}';
	echo $buffer;
}

if ($_GET['op'] == 'query_clienti'){
	$buffer = '{';
	$sqls = $db->query('select * from firme where id_user="'.$_SESSION['id_user'].'" and tip_firma="1"');
	$buffer .= '"ttl":"'.mysql_num_rows($sqls).'",';
	$sql = $db->query('select * from firme where id_user="'.$_SESSION['id_user'].'" and tip_firma="1" order by '.$_GET['order'].' limit '.$_GET['limit'].',8');
	$buffer .= '"clienti":[';
	while ($row = mysql_fetch_array($sql)){
		$buffer .= '{"id": "'.$row['id_firma'].'", "denumire": "'.$row['denumire'].'", "cif": "'.$row['cif'].'", "adresa": "'.$row['adresa'].'", "banca": "'.$row['banca'].'", "iban": "'.$row['iban'].'", "reg_com": "'.$row['reg_com'].'", "data_add": "'.convert_data(date('d-m-Y',strtotime($row['data_add']))).'"},';
	}
	$buffer = substr($buffer,0,-1);
	$buffer .= ']}';
	echo $buffer;
}

if ($_GET['op'] == 'query_furnizori'){
	$buffer = '{';
	$sqls = $db->query('select * from firme where id_user="'.$_SESSION['id_user'].'" and tip_firma="0"');
	$buffer .= '"ttl":"'.mysql_num_rows($sqls).'",';
	$sql = $db->query('select * from firme where id_user="'.$_SESSION['id_user'].'" and tip_firma="0" order by '.$_GET['order'].' limit '.$_GET['limit'].',8');
	$buffer .= '"furnizori":[';
	while ($row = mysql_fetch_array($sql)){
		$buffer .= '{"id": "'.$row['id_firma'].'", "denumire": "'.$row['denumire'].'", "cif": "'.$row['cif'].'", "adresa": "'.$row['adresa'].'", "banca": "'.$row['banca'].'", "iban": "'.$row['iban'].'", "reg_com": "'.$row['reg_com'].'", "data_add": "'.convert_data(date('d-m-Y',strtotime($row['data_add']))).'", "tva": "'.$row['tva'].'"},';
	}
	$buffer = substr($buffer,0,-1);
	$buffer .= ']}';
	echo $buffer;
}

if ($_GET['op'] == 'query_produse'){
	$buffer = '{';
	$sqls = $db->query('select * from produse where id_user="'.$_SESSION['id_user'].'"');
	$buffer .= '"ttl":"'.mysql_num_rows($sqls).'",';
	$sql = $db->query('select * from produse where id_user="'.$_SESSION['id_user'].'" order by '.$_GET['order'].' limit '.$_GET['limit'].',8');
	$buffer .= '"produse":[';
	while ($row = mysql_fetch_array($sql)){
		$buffer .= '{"id": "'.$row['id_produs'].'", "denumire": "'.$row['denumire'].'", "unitate": "'.$row['unitate'].'", "data_add": "'.convert_data(date('d-m-Y',strtotime($row['data_add']))).'"},';
	}
	$buffer = substr($buffer,0,-1);
	$buffer .= ']}';
	echo $buffer;
}

if ($_GET['op'] == 'query_ttl'){
	$sqls = $db->query('select * from facturi where id_furnizor="'.$_GET['id_furnizor'].'"');
	$buffer .= '{"ttl":"'.mysql_num_rows($sqls).'"}';
	echo $buffer;
}

if ($_GET['op'] == 'query_facturi'){
	$buffer = '{';
	$sqls = $db->query('select * from facturi where id_furnizor="'.$_GET['id_furnizor'].'"');
	$buffer .= '"ttl":"'.mysql_num_rows($sqls).'",';
	$sql = $db->query('select facturi.*,firme.denumire from facturi,firme where facturi.id_furnizor="'.$_GET['id_furnizor'].'" and facturi.id_client=firme.id_firma order by '.$_GET['order'].' limit '.$_GET['limit'].',8');
	$buffer .= '"facturi":[';
	while ($row = mysql_fetch_array($sql)){
		if (isset($row['valuta'])) $valuta = $row['valuta'];
		else $valuta = 'Lei';
		$buffer .= '{"id": "'.$row['id_factura'].'", "serie": "'.$row['serie'].'", "numar": "'.$row['numar'].'", "denumire": "'.$row['denumire'].'", "data_factura": "'.convert_data(date('d-m-Y',strtotime($row['data_factura']))).'", "total_general": "'.$row['total_general'].'", "valuta": "'.$valuta.'", "stare_incasare": "'.$row['stare_incasare'].'", "data_scadenta": "'.$row['data_scadenta'].'", "id_draft": "'.$row['id_draft'].'", "id_user": "'.$_SESSION['id_user'].'", "id_furnizor": "'.$_GET['id_furnizor'].'"},';
	}
	if (mysql_num_rows($sql) != 0) $buffer = substr($buffer,0,-1);
	$buffer .= ']}';
	echo $buffer;
}

if ($_GET['op'] == 'search_clienti'){
	$buffer = '[';
	$sql = $db->query('select * from firme where denumire like "'.$_GET['denumire'].'%" and id_user="'.$_SESSION['id_user'].'" and tip_firma="1" order by '.$_GET['order'].' limit 8');
	if (mysql_num_rows($sql) > 0){
		while ($row = mysql_fetch_array($sql)){
			$buffer .= '{"id": "'.$row['id_firma'].'", "denumire": "'.$row['denumire'].'", "cif": "'.$row['cif'].'", "adresa": "'.$row['adresa'].'", "banca": "'.$row['banca'].'", "iban": "'.$row['iban'].'", "reg_com": "'.$row['reg_com'].'", "data_add": "'.convert_data(date('d-m-Y',strtotime($row['data_add']))).'"},';
		}
	}
	else{
		$sql = $db->query('select * from firme having locate(" '.$_GET['denumire'].'",denumire) > 0 and id_user="'.$_SESSION['id_user'].'" and tip_firma="1" order by '.$_GET['order'].' limit 8');
		if (mysql_num_rows($sql) == 0) $buffer .= '{},';
		while ($row = mysql_fetch_array($sql)){
			$buffer .= '{"id": "'.$row['id_firma'].'", "denumire": "'.$row['denumire'].'", "cif": "'.$row['cif'].'", "adresa": "'.$row['adresa'].'", "banca": "'.$row['banca'].'", "iban": "'.$row['iban'].'", "reg_com": "'.$row['reg_com'].'", "data_add": "'.convert_data(date('d-m-Y',strtotime($row['data_add']))).'"},';
		}
	}
	$buffer = substr($buffer,0,-1);
	$buffer .= ']';
	echo $buffer;
}

if ($_GET['op'] == 'search_furnizori'){
	$buffer = '[';
	$sql = $db->query('select * from firme where denumire like "'.$_GET['denumire'].'%" and id_user="'.$_SESSION['id_user'].'" and tip_firma="0" order by '.$_GET['order'].' limit 8');
	if (mysql_num_rows($sql) > 0){
		while ($row = mysql_fetch_array($sql)){
			$buffer .= '{"id": "'.$row['id_firma'].'", "denumire": "'.$row['denumire'].'", "cif": "'.$row['cif'].'", "adresa": "'.$row['adresa'].'", "banca": "'.$row['banca'].'", "iban": "'.$row['iban'].'", "reg_com": "'.$row['reg_com'].'", "data_add": "'.convert_data(date('d-m-Y',strtotime($row['data_add']))).'", "tva": "'.$row['tva'].'"},';
		}
	}
	else{
		$sql = $db->query('select * from firme having locate(" '.$_GET['denumire'].'",denumire) > 0 and id_user="'.$_SESSION['id_user'].'" and tip_firma="0" order by '.$_GET['order'].' limit 8');
		if (mysql_num_rows($sql) == 0) $buffer .= '{},';
		while ($row = mysql_fetch_array($sql)){
			$buffer .= '{"id": "'.$row['id_firma'].'", "denumire": "'.$row['denumire'].'", "cif": "'.$row['cif'].'", "adresa": "'.$row['adresa'].'", "banca": "'.$row['banca'].'", "iban": "'.$row['iban'].'", "reg_com": "'.$row['reg_com'].'", "data_add": "'.convert_data(date('d-m-Y',strtotime($row['data_add']))).'", "tva": "'.$row['tva'].'"},';
		}
	}
	$buffer = substr($buffer,0,-1);
	$buffer .= ']';
	echo $buffer;
}

if ($_GET['op'] == 'search_produse'){
	$buffer = '[';
	$sql = $db->query('select * from produse where denumire like "'.$_GET['denumire'].'%" and id_user="'.$_SESSION['id_user'].'" order by '.$_GET['order'].' limit 8');
	if (mysql_num_rows($sql) > 0){
		while ($row = mysql_fetch_array($sql)){
			$buffer .= '{"id": "'.$row['id_produs'].'", "denumire": "'.$row['denumire'].'", "unitate": "'.$row['unitate'].'", "data_add": "'.convert_data(date('d-m-Y',strtotime($row['data_add']))).'"},';
		}
	}
	else{
		$sql = $db->query('select * from produse having locate(" '.$_GET['denumire'].'",denumire) > 0 and id_user="'.$_SESSION['id_user'].'" order by '.$_GET['order'].' limit 8');
		if (mysql_num_rows($sql) == 0) $buffer .= '{},';
		while ($row = mysql_fetch_array($sql)){
			$buffer .= '{"id": "'.$row['id_produs'].'", "denumire": "'.$row['denumire'].'", "unitate": "'.$row['unitate'].'", "data_add": "'.convert_data(date('d-m-Y',strtotime($row['data_add']))).'"},';
		}
	}
	$buffer = substr($buffer,0,-1);
	$buffer .= ']';
	echo $buffer;
}

if ($_GET['op'] == 'search_facturi'){
	$buffer = '[';
	$sql = $db->query('select facturi.*,firme.denumire from facturi,firme where facturi.id_furnizor="'.$_GET['id_furnizor'].'" and firme.denumire like "'.$_GET['denumire'].'%" and facturi.id_client=firme.id_firma order by '.$_GET['order'].' limit 8');
	if (mysql_num_rows($sql) > 0){
		while ($row = mysql_fetch_array($sql)){
			if (isset($row['valuta'])) $valuta = $row['valuta'];
			else $valuta = 'Lei';
			$buffer .= '{"id": "'.$row['id_factura'].'", "serie": "'.$row['serie'].'", "numar": "'.$row['numar'].'", "denumire": "'.$row['denumire'].'", "data_factura": "'.convert_data(date('d-m-Y',strtotime($row['data_factura']))).'", "total_general": "'.$row['total_general'].'", "valuta": "'.$valuta.'", "stare_incasare": "'.$row['stare_incasare'].'", "data_scadenta": "'.$row['data_scadenta'].'", "id_draft": "'.$row['id_draft'].'", "id_user": "'.$_SESSION['id_user'].'", "id_furnizor": "'.$_GET['id_furnizor'].'"},';
		}
	}
	else{
		$sql = $db->query('select facturi.*, firme.denumire from facturi,firme where facturi.id_furnizor="'.$_GET['id_furnizor'].'" and facturi.id_client=firme.id_firma having locate("'.$_GET['denumire'].'",firme.denumire) > 0 order by '.$_GET['order'].' limit 8');
		if (mysql_num_rows($sql) == 0) $buffer .= '{},';
		while ($row = mysql_fetch_array($sql)){
			if (isset($row['valuta'])) $valuta = $row['valuta'];
			else $valuta = 'Lei';
			$buffer .= '{"id": "'.$row['id_factura'].'", "serie": "'.$row['serie'].'", "numar": "'.$row['numar'].'", "denumire": "'.$row['denumire'].'", "data_factura": "'.convert_data(date('d-m-Y',strtotime($row['data_factura']))).'", "total_general": "'.$row['total_general'].'", "valuta": "'.$valuta.'", "stare_incasare": "'.$row['stare_incasare'].'", "data_scadenta": "'.$row['data_scadenta'].'", "id_draft": "'.$row['id_draft'].'", "id_user": "'.$_SESSION['id_user'].'", "id_furnizor": "'.$_GET['id_furnizor'].'"},';
		}
	}
	$buffer = substr($buffer,0,-1);
	$buffer .= ']';
	echo $buffer;
}

if ($_GET['op'] == 'save_email'){
	//vars
	$host = 'localhost';
	$email = $_GET['email'];
	$address = $_GET['catre'];
	$x = stripcslashes($_GET['jsn']);
	$json = json_decode($x,true);
	//end vars
	
	foreach ($_GET as $key => $value){
		if ($key != 'op' && $key != 'mesaj' && $key != 'jsn'){
			$campuri .= $key.',';
			$valori .= '"'.$value.'",';
		}
	}
	
	// crypt serie
	for ($i=0; $i<strlen($json['factura']); $i++){
		$x = substr($json['factura'],$i,1);
		if (is_numeric($x)){
			$pos = $i;
			break;
		}
	}
	$serie = trim(substr($json['factura'],0,$pos)).'-'.$_GET['id_factura'];
	$crypt = new crypt;
	$cod = $crypt->encrypt('123',$serie,8).'-'.$_GET['id_factura'];
	
	// change <tag> for mysql
	$campuri .= 'mesaj,cod,data_add';
	$valori .= '"'.htmlentities($_GET['mesaj']).'","'.$cod.'","'.date('Y-m-d H:i:s').'"';
	
	$query = 'insert into email('.$campuri.') values('.$valori.')';
	$db->query($query);
	$db->query('update facturi set stare_email="1" where id_factura="'.$_GET['id_factura'].'"');
	
	require_once('./mailer/class.phpmailer.php');
	$mail = new PHPMailer(true);
	$mail->IsSMTP();
	
	$mail->Host = $host; // SMTP server
	$mail->SMTPDebug = 0;
	$mail->From = $email;
	$mail->AddAddress($address);
	
	if (isset($_GET['subiect'])) $mail->Subject = $_GET['subiect'];
	if (isset($_GET['cc'])) $mail->AddCC($_GET['cc']);
	if (isset($_GET['bcc'])) $mail->AddCC($_GET['bcc']);
	if ($_GET['atasament'] == 1){
		$factura = '../useri/'.$json['subdomeniu'].'/'.$json['id_furnizor'].'/pdf/factura-'.$json['factura'].'.pdf';
		$mail->AddAttachment($factura);
	}
	
	if (isset($_GET['mesaj'])) $body = $_GET['mesaj'].'<br>';
	$body .= '
		<br><p>Puteti vizualiza factura accesand http://facturi123.ro/facturi/
		<br><p>Cod de acces : <strong>'.$cod.'</strong></p>
	';
	
	$mail->Body = "<html><body>".$body."</body></html>";
	$mail->WordWrap = 50;
	$mail->IsHTML(true);
	if(!$mail->Send()) $buffer = 0;
	else $buffer = 1;
	echo $buffer;
}

if ($_GET['op'] == 'email_parola'){
	$sql = $db->query('select * from useri where email="'.$_GET['email'].'"');
	if (mysql_num_rows($sql) == 1){
		$row = mysql_fetch_array($sql);
		require_once('./mailer/class.phpmailer.php');
		$mail = new PHPMailer(true);
		$mail->IsSMTP();

		$valid = 1;
		foreach ($_GET as $key => $value){
			if ($value == '') $valid = 0;
		}
		if ($valid == 1){
		//vars
		$host = 'localhost';
		$email = 'office@facturi123.ro';
		$address = $_GET['email'];
		$subject = 'Facturi123 - Parola noua';
		$parola = random();
		$body = '
			<font size="3">
				<p style="line-height: 15px;">Contul tau : '.$row['subdomeniu'].'</p>
				<p style="line-height: 15px;">Utilizator : '.$row['user'].'</p>
				<p style="line-height: 15px;">Parola noua : <strong>'.$parola.'</strong></p>
			</font>
			<p>Dupa autentificare, poti modifica noua parola accesand meniul Contul tau</p>
		';
		//end vars
			
			$mail->Host = $host; // SMTP server
			$mail->SMTPDebug = 0;
			$mail->From = $email;
			$mail->AddAddress($address);
			$mail->Subject = $subject;
			$mail->MsgHTML($body);
			$mail->WordWrap = 50;
			if(!$mail->Send()) $buffer = -1;
			else{
				$db->query('update useri set parola="'.md5($parola).'" where id_user="'.$row['id_user'].'"');
				$buffer = 1;
			}
		}
		else $buffer = 0;
	}
	else $buffer = 0;
	echo $buffer;
}

?>