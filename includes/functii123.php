<?php
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
include ('config.php');
$buffer = '';

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

if ($_GET['op'] == 'save_user'){
	if ($_GET['id_tip'] == 1){
		foreach ($_GET as $key => $value){
			if ($key != 'op' && $key != 'perioada' && $key != 'pret'){
				if ($key == 'parola'){
					$campuri .= $key.',';
					$valori .= 'md5("'.$value.'"),';
				}
				else{
					$campuri .= $key.',';
					$valori .= '"'.$value.'",';
				}
			}
		}
		$campuri .= 'data_add';
		$valori .= '"'.date('Y-m-d').'"';
		$query = 'insert into useri('.$campuri.') values('.$valori.')';
		$db->query($query);
		$id_user = mysql_insert_id();
		
		$cont = str_replace(' ','-',strtolower($_GET['subdomeniu']));
		if (!file_exists('../useri/'.$cont)){
			mkdir('../useri/'.$cont);
			mkdir('../useri/'.$cont.'/temp');
		}
		$buffer = '/inscriere/'.$cont.'/'.$id_user.'/';
	}
	if ($_GET['id_tip'] != 1){
		$x = stripcslashes($_GET['user']);
		$user = json_decode($x,true);
		foreach ($user as $key => $value){
			if ($key == 'parola'){
				$campuri .= $key.',';
				$valori .= 'md5("'.$value.'"),';
			}
			else{
				$campuri .= $key.',';
				$valori .= '"'.$value.'",';
			}
		}
		$campuri .= 'id_tip,stare,data_add';
		$valori .= '"'.$_GET['id_tip'].'","'.$_GET['stare'].'","'.date('Y-m-d').'"';
		$query = 'insert into useri('.$campuri.') values('.$valori.')';
		$db->query($query);
		$id_user = mysql_insert_id();
		
		$x = ''; $campuri = ''; $valori = '';
		$x = stripcslashes($_GET['platitor']);
		$platitor = json_decode($x,true);
		foreach ($platitor as $key => $value){
			$campuri .= $key.',';
			$valori .= '"'.$value.'",';
		}
		$campuri .= 'id_user,tip_firma,data_add';
		$valori .= '"'.$id_user.'","9","'.date('Y-m-d H:i:s').'"';
		$query = 'insert into firme('.$campuri.') values('.$valori.')';
		$db->query($query);
		$id_platitor = mysql_insert_id();
		
		$x = ''; $campuri = ''; $valori = '';
		$x = stripcslashes($_GET['plata']);
		$plata = json_decode($x,true);
		foreach ($plata as $key => $value){
			if ($key == 'data_ini' || $key == 'data_scadenta'){
				$campuri .= $key.',';
				$valori .= '"'.date('Y-m-d',strtotime($value)).'",';
			}
			else{
				$campuri .= $key.',';
				$valori .= '"'.$value.'",';
			}
		}
		$perioada = trim(substr($plata['perioada'],0,2));
		if ($_GET['id_tip'] == 2) $pret_ini = (15 * $perioada);
		if ($_GET['id_tip'] == 3) $pret_ini = (30 * $perioada);
		if ($_GET['id_tip'] == 4) $pret_ini = (70 * $perioada);
		$data_ini = date('Y-m-d',strtotime($plata['data_ini']));
		$data_fin = date('Y-m-d',strtotime($data_ini . $perioada . ' months'));
		
		$serie = 'cms';
		$sp = $db->query('select * from plati order by id_plata desc limit 1');
		if (mysql_num_rows($sp) == 1){
			$rp = mysql_fetch_array($sp);
			$nr = ($rp['numar']+1);
			$numar = str_pad($nr,strlen($rp['numar']),'0',STR_PAD_LEFT);
		}
		else $numar = '010001';
		
		//cod
		$string1 = $id_user.$id_platitor.$_GET['id_tip'].$row['perioada'];
		$string2 = 'facturi123';
		$cod = hash_hmac('crc32',$string1,$string2);
		//end cod
		
		$campuri .= 'id_user,id_platitor,id_tip,data_fin,pret_ini,serie,numar,stare,cod,data_add';
		$valori .= '"'.$id_user.'","'.$id_platitor.'","'.$_GET['id_tip'].'","'.$data_fin.'","'.$pret_ini.'","'.$serie.'","'.$numar.'","1","'.$cod.'","'.date('Y-m-d H:i:s').'"';
		$query = 'insert into plati('.$campuri.') values('.$valori.')';
		$db->query($query);
		$id_plata = mysql_insert_id();
		
		$cont = str_replace(' ','-',strtolower($_GET['subdomeniu']));
		if (!file_exists('../useri/'.$cont)){
			mkdir('../useri/'.$cont);
			mkdir('../useri/'.$cont.'/'.$id_platitor);
			mkdir('../useri/'.$cont.'/temp');
		}
		$buffer = '/inscriere/'.$cont.'/'.$id_user.'/';
	}

	echo $buffer;
	
	$sql = $db->query('select * from tip_cont where id_tip="'.$_GET['id_tip'].'"');
	$row = mysql_fetch_array($sql);
	
	require_once('./mailer/class.phpmailer.php');
	$mail = new PHPMailer(true);
	$mail->IsSMTP();
	$host = 'localhost';
	$email = 'office@facturi123.ro';
	$subject = 'Facturi123 - Cont nou';
	$body = '
		<div style="font-size: 15px;">
			<div style="margin-top: 5px;">Cont nou : '.$_GET['subdomeniu'].'</div>
			<div style="margin-top: 5px;">Email : '.$_GET['email'].'</div>
			<div style="margin-top: 5px;">Tip cont : '.$row['denumire'].'</div>
			<div style="margin-top: 5px;">Data : '.date('d-m-Y H:i:s').'</div>
		</div>
	';	
	$mail->Host = $host; // SMTP server
	$mail->SMTPDebug = 0;
	$mail->SetFrom($email,'Facturi123');
	$mail->AddAddress('robert@facturi123.ro','Robert Muster');
	$mail->AddCC('victor@facturi123.ro','Victor Ocnarescu');
	$mail->Subject = $subject;
	$mail->MsgHTML($body);
	$mail->WordWrap = 50;
	$mail->Send();
}

if ($_GET['op'] == 'email_instiintare'){
	$sql = $db->query('select * from plati,useri where useri.subdomeniu="'.$_GET['subdomeniu'].'" and plati.id_plata="'.$_GET['id_plata'].'" and plati.id_user=useri.id_user');
	$row = mysql_fetch_array($sql);
	$sql2 = $db->query('select * from tip_cont where id_tip="'.$row['id_tip'].'"');
	$row2 = mysql_fetch_array($sql2);

	require_once('./mailer/class.phpmailer.php');
	$mail = new PHPMailer(true);
	$mail->IsSMTP();
	//vars
	$host = 'localhost';
	$email = 'office@facturi123.ro';
	$address = $row['email'];
	$subject = 'Facturi123 - Instiintare de plata';
	$body = '
		<div style="font-size: 15px;">
			<div style="margin-top: 5px;">Contul tau in programul de facturare online Facturi123 a fost creat. Multumim!</div>
			<div style="margin-top: 5px;">Pentru activarea contului te rugam sa achiti suma prezenta in instiintarea de plata.</div>
			<div style="margin-top: 10px; font-weight: bold;">Detalii cont:</div>
			<div style="margin-top: 4px;">Contul tau: <span style="color: #1d5987">'.$row['subdomeniu'].'</span></div>
			<div style="margin-top: 4px;">Utilizator: <span style="color: #1d5987">'.$row['user'].'</span></div>
			<div style="margin-top: 4px;">Tip cont: <span style="text-transform: capitalize; color: #1d5987;"> '.$row2['denumire'].'</span></div>
		</div>
		';
	//end vars
	
	if ($_GET['atasament'] == 1){
		$factura = '../useri/'.$row['subdomeniu'].'/'.$row['id_platitor'].'/instiintare-plata-'.$row['serie'].'-'.$row['numar'].'.pdf';
		$mail->AddAttachment($factura);
	}
	
	$mail->Host = $host; // SMTP server
	$mail->SMTPDebug = 0;
	$mail->SetFrom($email,'Facturi123');
	$mail->AddAddress($address);
	$mail->Subject = $subject;
	$mail->MsgHTML($body);
	$mail->WordWrap = 50;
	if(!$mail->Send()) $buffer = -1;
	else $buffer = 1;
	echo $buffer;
}

if ($_GET['op'] == 'email_contact'){
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
		$email = $_GET['email'];
		$address = 'contact@facturi123.ro';
		$subject = $_GET['subiect'];
		$body = str_replace('<br>',' ',$_GET['mesaj']);
		//end vars
		
		$mail->Host = $host; // SMTP server
		$mail->SMTPDebug = 0;
		$mail->SetFrom($email,substr($email,0,(strpos($email,'@'))));
		$mail->AddAddress($address);
		$mail->Subject = $subject;
		$mail->MsgHTML($body);
		$mail->WordWrap = 50;
		if(!$mail->Send()) $buffer = -1;
		else $buffer = 1;
	}
	else $buffer = 0;
	echo $buffer;
}

if ($_GET['op'] == 'email_imbunatatiri'){
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
		$email = $_GET['email'];
		$address = 'contact@facturi123.ro';
		$subject = $_GET['subiect'];
		$body = str_replace('<br>',' ',$_GET['mesaj']);
		//end vars
		
		$mail->Host = $host; // SMTP server
		$mail->SMTPDebug = 0;
		$mail->SetFrom($email,substr($email,0,(strpos($email,'@'))));
		$mail->AddAddress($address);
		$mail->Subject = $subject;
		$mail->MsgHTML($body);
		$mail->WordWrap = 50;
		if(!$mail->Send()) $buffer = -1;
		else $buffer = 1;
	}
	else $buffer = 0;
	echo $buffer;
}

if ($_GET['op'] == 'email_stiri'){
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
	$email = $_GET['email'];
	$address = 'contact@facturi123.ro';
	$subject = 'Facturi123 - Stiri';
	$body = '
		<div style="font-size: 15px;">
			<div style="margin-top: 5px;"><strong>Stiri Facturi123</strong></div>
			<div style="margin-top: 5px;">Email : '.$email.'</div>
			<div style="margin-top: 5px;">Data solicitarii : '.date('d-m-Y H:i:s').'</div>
		</div>
	';
	//end vars
		
		$mail->Host = $host; // SMTP server
		$mail->SMTPDebug = 0;
		$mail->SetFrom($email,substr($email,0,(strpos($email,'@'))));
		$mail->AddAddress($address);
		$mail->Subject = $subject;
		$mail->MsgHTML($body);
		$mail->WordWrap = 50;
		if(!$mail->Send()) $buffer = 0;
		else $buffer = 1;
	}
	else $buffer = 0;
	echo $buffer;
}

if ($_GET['op'] == 'email_cont'){
	$sql = $db->query('select * from useri where email="'.$_GET['email'].'" and user="'.$_GET['user'].'"');
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
			$subject = 'Facturi123 - Informatii contul tau';
			$body = '
				<div style="font-size: 15px;">
					<div style="margin-top: 5px;">Contul tau : '.$row['subdomeniu'].'</div>
					<div style="margin-top: 5px;">Utilizator : '.$row['user'].'</div>
					<div style="margin-top: 5px;">Email : '.$row['email'].'</div>
				</div>
			';
			//end vars
			
			$mail->Host = $host; // SMTP server
			$mail->SMTPDebug = 0;
			$mail->SetFrom($email,'Facturi123');
			$mail->AddAddress($address);
			$mail->Subject = $subject;
			$mail->MsgHTML($body);
			$mail->WordWrap = 50;
			if(!$mail->Send()) $buffer = -1;
			else $buffer = 1;
		}
		else $buffer = 0;
	}
	else $buffer = 0;
	echo $buffer;
}

if ($_GET['op'] == 'email_parola'){
	$sql = $db->query('select * from useri where email="'.$_GET['email'].'" and user="'.$_GET['user'].'"');
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
				<p>Dupa autentificare, poti modifica noua parola accesand meniul Contul tau.</p>
			';
			//end vars
			
			$mail->Host = $host; // SMTP server
			$mail->SMTPDebug = 0;
			$mail->SetFrom($email,'Facturi123');
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