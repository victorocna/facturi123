<?php
session_start();
include ('../includes/config.php');
$xml = '../useri/'.$_SESSION['subdomeniu'].'/'.$_GET['id_furnizor'].'/xml/'.$_GET['factura'].'.xml';
require_once('../includes/tcpdf/config/lang/eng.php');
require_once('../includes/tcpdf/tcpdf.php');
$factura = xml2array($xml);
$sc = $db->query('select * from firme where id_firma="'.$factura['facturi']['client_attr']['id'].'" and tip_firma="1"');
$rc = mysql_fetch_array($sc);
$sf = $db->query('select * from firme where id_firma="'.$factura['facturi']['furnizor_attr']['id'].'" and tip_firma="0"');
$rf = mysql_fetch_array($sf);
	$valuta = $factura['facturi']['adv']['valuta'];
	if ($valuta == 'Euro') $valuta_min = 'eurocenti';
	if ($valuta == 'USD') $valuta_min = 'centi';
	if ($valuta == 'Lei') $valuta_min = 'bani';
$ttl_general0 = str_replace('.','',$factura['facturi']['total_general']);
$ttl_general = str_replace(',','.',$ttl_general0);

$sql = $db->query('select * from incasare,chitante where chitante.id_chitanta="'.$_GET['id_chitanta'].'" and incasare.id_chitanta=chitante.id_chitanta');
$row = mysql_fetch_array($sql);
// !! $suma0 = suma fara '.' pt sume > 1000
$suma0 = str_replace('.','',$row['suma']);
$pos = strpos($suma0,',');
if ($pos === false)	$suma = '('.nr2lit($suma0).''.strtolower($valuta).')';
else{
	$a = substr($suma0,0,$pos);
	$b = substr($suma0,($pos+1));
	if ($b != '00') $suma = '('.nr2lit($a).''.strtolower($valuta).'si'.nr2lit($b).''.$valuta_min.')';
	else $suma = '('.nr2lit($a).''.strtolower($valuta).')';
}

class MYPDF extends TCPDF {
	public function Header() {
		//vars
		$xml = '../useri/'.$_SESSION['subdomeniu'].'/'.$_GET['id_furnizor'].'/xml/'.$_GET['factura'].'.xml';
		$factura = xml2array($xml);
		$sql = mysql_query('select * from incasare,chitante where chitante.id_chitanta="'.$_GET['id_chitanta'].'" and incasare.id_chitanta=chitante.id_chitanta');
		$row = mysql_fetch_array($sql);
		$width = 20 + 2.4*(strlen($row['serie_ch'].' '.$row['numar_ch']));
		//end vars
		
		$this->SetXY(135,10);
		$this->SetFont('helvetica','B',12);
		$this->SetTextColor(46,110,158);
		$this->SetFillColor(244,244,244);
		$this->Cell($width,8,'Chitanta '.$row['serie_ch'].' '.$row['numar_ch'],0,1,'C',1);
		$this->SetXY(135,20);
		$this->SetFont('helvetica','B',10);
		$this->Cell($width,8,convert_data(date('d-m-Y',strtotime($row['data_incasare']))),0,1,'C',1);
	}
}

// Init
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
//$pdf->SetFooterMargin(100);
$pdf->SetAutoPageBreak(TRUE, 40);
$pdf->SetTopMargin(10);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 
$pdf->setLanguageArray($l); 
$pdf->SetDisplayMode(95);
$pdf->SetFont('helvetica','',11);
$pdf->AddPage();
// End Init

$pdf->SetFont('helvetica','B',11);
$pdf->Cell(100,8,strtoupper($rf['denumire']),0,1,'L',0,'',1);
$pdf->SetFont('helvetica','',8);
$pdf->SetTextColor(46,110,158);
$pdf->Cell(15,5,'CIF / CUI',0,0,'L');
$pdf->SetTextColor(0,0,0);
$pdf->Cell(85,5,strtoupper($rf['cif']),0,1,'L');
$pdf->SetTextColor(46,110,158);
$pdf->Cell(15,5.3,'Adresa',0,0,'L');
$pdf->SetTextColor(0,0,0);
if ($rf['adresa']) $pdf->MultiCell(85,2,ucwords($rf['adresa']),0,1,'L');
else $pdf->MultiCell(85,2,'-',0,1,'L');
$pdf->SetTextColor(46,110,158);
$pdf->Cell(15,5,'Reg Com',0,0,'L');
$pdf->SetTextColor(0,0,0);
if ($rf['reg_com']) $pdf->Cell(85,5,strtoupper($rf['reg_com']),0,1,'L');
else $pdf->Cell(85,5,'-',0,1,'L');
$pdf->SetTextColor(46,110,158);
$pdf->Cell(15,5,'Banca',0,0,'L');
$pdf->SetTextColor(0,0,0);
if ($rf['banca']) $pdf->MultiCell(85,2,ucwords($rf['banca']),0,1,'L');
else $pdf->MultiCell(85,2,'-',0,1,'L');
$pdf->SetTextColor(46,110,158);
$pdf->Cell(15,5,'IBAN',0,0,'L');
$pdf->SetTextColor(0,0,0);
if ($rf['iban']) $pdf->Cell(85,5,strtoupper($rf['iban']),0,1,'L');
else $pdf->Cell(85,5,'-',0,1,'L');

$y_header = $pdf->GetY();
$pdf->SetY($y_header+10);
$pdf->SetFillColor(244,244,244);
$pdf->SetFont('helvetica','',10);
$pdf->SetTextColor(46,110,158);
$pdf->Cell(27,6,'Am primit de la',0,0,'L');
$pdf->SetFont('helvetica','B',10);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(153,6.2,strtoupper($rc['denumire']),0,1,'L',1,'',1);
$pdf->SetFont('helvetica','',10);
$pdf->SetTextColor(46,110,158);
$pdf->Ln(2);
$pdf->Cell(27,6,'CIF / CUI',0,0,'L');
$pdf->SetTextColor(0,0,0);
$pdf->Cell(153,6,strtoupper($rc['cif']),0,1,'L',1);
if ($rc['adresa']){
	$pdf->SetTextColor(46,110,158);
	$pdf->Ln(2);
	$pdf->Cell(27,6,'Adresa',0,0,'L');
	$pdf->SetTextColor(0,0,0);
	$pdf->Cell(153,6,ucwords($rc['adresa']),0,1,'L',1,'',1);
}
if ($rc['reg_com']){
	$pdf->SetTextColor(46,110,158);
	$pdf->Ln(2);
	$pdf->Cell(27,6,'Reg Com',0,0,'L');
	$pdf->SetTextColor(0,0,0);
	$pdf->Cell(153,6,strtoupper($rc['reg_com']),0,1,'L',1);
}
$pdf->SetTextColor(46,110,158);
$pdf->Ln(2);
$pdf->Cell(27,6,'Suma de',0,0,'L');
$pdf->SetTextColor(0,0,0);
$pdf->Cell(153,6,$row['suma'].' '.$valuta.' '.$suma,0,1,'L',1);
$pdf->SetTextColor(46,110,158);
$pdf->Ln(2);
$pdf->Cell(27,6,'reprezentand',0,0,'L');
$pdf->SetTextColor(0,0,0);
if ($factura['facturi']['total_general'] == $row['suma']) $pdf->Cell(153,6,'Contravaloarea facturii '.strtoupper(str_replace('-',' ',$_GET['factura'])),0,1,'L',1);
else $pdf->Cell(153,6,'Contravaloarea partiala a facturii '.strtoupper(str_replace('-',' ',$_GET['factura'])),0,1,'L',1);

$x = 10;
$cod = $factura['facturi_attr']['id'].$factura['facturi']['client_attr']['id'].$factura['facturi']['furnizor_attr']['id'].$_GET['id_incasare'];
$y_footer = $pdf->GetY();
$pdf->SetY($y_footer+10);
$pdf->SetFont('helvetica','',8);
$pdf->SetTextColor(102,102,102);
$pdf->Cell(145,10,'Chitanta creata online de www.facturi123.ro',0,1,'L');
$hash = hash('md4',$cod);
for ($i=0;$i<strlen($hash);$i++){
	$string .= ord($hash[$i]);
}
$verify = bcmod($string,90);
$cod .= 'v'.$verify;
$bar_cod = bar_code128($cod);
for ($i=0;$i<strlen($bar_cod);$i++){
	$pdf->Image('../imagini/'.$bar_cod[$i].'.jpg',($x+1),($y_footer+18),0,0,'jpeg'); 
	$x+=0.265;
}

$pdf->Output('chitanta-'.$_GET['chitanta'].'.pdf','I');
?>