<?php
session_start();
include ('../includes/config.php');
$xml = '../useri/'.$_GET['subdomeniu'].'/'.$_GET['id_furnizor'].'/xml/'.$_GET['factura'].'.xml';
require_once('../includes/tcpdf/config/lang/eng.php');
require_once('../includes/tcpdf/tcpdf.php');
$factura = xml2array($xml);
$sc = $db->query('select * from firme where id_firma="'.$factura['facturi']['client_attr']['id'].'" and tip_firma="1"');
$rc = mysql_fetch_array($sc);
$sf = $db->query('select * from firme where id_firma="'.$factura['facturi']['furnizor_attr']['id'].'" and tip_firma="0"');
$rf = mysql_fetch_array($sf);
$cota_tva = $factura['facturi']['adv']['cota_tva'].'%';
$valuta = $factura['facturi']['adv']['valuta'];
$sigla_rel = '/useri/'.$_GET['subdomeniu'].'/'.$_GET['id_furnizor'].'/sigla/'.$rf['sigla'];

/* echo '<pre>';
print_r($factura);
echo '</pre>'; */

class MYPDF extends TCPDF {
	public function Footer() {
		//vars
		$xml = '../useri/'.$_GET['subdomeniu'].'/'.$_GET['id_furnizor'].'/xml/'.$_GET['factura'].'.xml';
		$factura = xml2array($xml);
		$height = -53;
		$width_adv = 80;
		$width_client = 140;
		$sc = mysql_query('select * from firme where id_firma="'.$factura['facturi']['client_attr']['id'].'" and tip_firma="1"');
		$rc = mysql_fetch_array($sc);
		$sf = mysql_query('select * from firme where id_firma="'.$factura['facturi']['furnizor_attr']['id'].'" and tip_firma="0"');
		$rf = mysql_fetch_array($sf);
		$cota_tva = $factura['facturi']['adv']['cota_tva'].'%';
		$valuta = $factura['facturi']['adv']['valuta'];
		$sq = mysql_query('select * from facturi where id_factura="'.$factura['facturi_attr']['id'].'"');
		$rq = mysql_fetch_array($sq);
		$data_factura = date('dmyHis',strtotime($rq['data_add']));
		$cod = $factura['facturi_attr']['id'].$factura['facturi']['client_attr']['id'].$factura['facturi']['furnizor_attr']['id'].$data_factura;
		$x = 130;
		//end vars
		
		if (isset($factura['facturi']['adv']['observatii'])){
			$this->SetXY(5,$height-13);
			$this->SetTextColor(46,110,158);
			$this->SetFont('helvetica','B',8);
			$this->Cell(17,4,'Observatii',0,0,'L');
			$this->SetTextColor(0,0,0);
			$this->SetFont('helvetica','',8);
			$this->Cell(153,4,$factura['facturi']['adv']['observatii'],0,1,'L',0,'',1);
		}
		$this->SetDrawColor(241,112,9);
		$this->Rect(5,243,200,0,'DF');
		$this->SetXY(5,$height);
		$this->SetFont('helvetica','B',7);
		$this->Cell(70,4,strtoupper($rf['denumire']),0,2,'L',0,'',1);
		$this->SetFont('helvetica','',7);
		$this->Cell(70,4,strtoupper($rf['cif']),0,2,'L');
		if ($rf['reg_com']) $this->Cell(70,4,strtoupper($rf['reg_com']),0,2,'L');
		if ($rf['adresa']) $this->MultiCell(70,4,ucwords($rf['adresa']),0,1,'L');
		$this->SetX(5);
		if ($rf['banca']) $this->MultiCell(70,4,ucwords($rf['banca']),0,1,'L');
		$this->SetX(5);
		if ($rf['iban']) $this->Cell(70,4,strtoupper($rf['iban']),0,0,'L');
		
		$this->SetXY($width_adv,$height);
		$this->SetTextColor(46,110,158);
		$this->SetFont('helvetica','B',7);
		$this->Cell(12,4,'Cota TVA',0,0,'L');
		$this->SetTextColor(0,0,0);
		$this->SetFont('helvetica','',7);
		$this->Cell(48,4, $cota_tva,0,2,'L');
		$this->SetX($width_adv);
		$this->SetTextColor(46,110,158);
		$this->SetFont('helvetica','B',7);
		$this->Cell(9,4,'Valuta',0,0,'L');
		$this->SetTextColor(0,0,0);
		$this->SetFont('helvetica','',7);
		$this->Cell(51,4,$valuta,0,1,'L');
		if ($factura['facturi']['adv']['reprez_attr']['id']){
			$sr = mysql_query('select * from reprezentanti where id_reprez="'.$factura['facturi']['adv']['reprez_attr']['id'].'"');
			$rr = mysql_fetch_array($sr);
			$this->SetX($width_adv);
			$this->SetTextColor(46,110,158);
			$this->SetFont('helvetica','B',7);
			$this->Cell(15,4,'Intocmit de',0,0,'L');
			$this->SetTextColor(0,0,0);
			$this->SetFont('helvetica','',7);
			$this->Cell(40,4, ucwords($rr['nume_reprez']),0,1,'L',0,'',1);
			if ($rr['act_reprez']){
				$this->SetX($width_adv);
				$this->SetTextColor(46,110,158);
				$this->SetFont('helvetica','B',7);
				$this->Cell(17,4,'Act identitate',0,0,'L');
				$this->SetTextColor(0,0,0);
				$this->SetFont('helvetica','',7);
				$this->Cell(38,4,strtoupper($rr['act_reprez']),0,2,'L');
			}
		}
		if ($factura['facturi']['adv']['delegat_attr']['id']){
			$sd = mysql_query('select * from delegati where id_delegat="'.$factura['facturi']['adv']['delegat_attr']['id'].'"');
			$rd = mysql_fetch_array($sd);
			$this->SetX($width_adv);
			$this->SetTextColor(46,110,158);
			$this->SetFont('helvetica','B',7);
			$this->Cell(10,4,'Delegat',0,0,'L');
			$this->SetTextColor(0,0,0);
			$this->SetFont('helvetica','',7);
			$this->Cell(45,4,ucwords($rd['nume_delegat']),0,1,'L',0,'',1);
			if ($rd['act_identitate']){
				$this->SetX($width_adv);
				$this->SetTextColor(46,110,158);
				$this->SetFont('helvetica','B',7);
				$this->Cell(17,4,'Act identitate',0,0,'L');
				$this->SetTextColor(0,0,0);
				$this->SetFont('helvetica','',7);
				$this->Cell(38,4,strtoupper($rd['act_identitate']),0,1,'L',0,'',1);
			}
		}
		
		$this->SetXY($width_client,$height);
		$this->SetFont('helvetica', 'B',7);
		$this->Cell(65,4,strtoupper($rc['denumire']),0,2,'L',0,'',1);
		$this->SetFont('helvetica','',7);
		$this->Cell(65,4,strtoupper($rc['cif']),0,2,'L');
		if ($rc['reg_com']) $this->Cell(65,4,strtoupper($rc['reg_com']),0,2,'L');
		if ($rc['adresa']) $this->MultiCell(65,4,ucwords($rc['adresa']),0,1,'L');
		$this->SetX($width_client);
		if ($rc['banca']) $this->MultiCell(65,4,ucwords($rc['banca']),0,1,'L');
		$this->SetX($width_client);
		if ($rc['iban']) $this->Cell(65,4,strtoupper($rc['iban']),0,0,'L');
		
		$this->SetXY(5,-20);
		$this->SetFont('helvetica','',7);
		$this->Cell(75,3,'Document fiscal creat online de www.facturi123.ro',0,0,'L');
		$this->Cell(70,3,'Pagina '.$this->getAliasNumPage().' / '.$this->getAliasNbPages(),0,0,'L');

		$hash = hash('md4',$cod);
		for ($i=0;$i<strlen($hash);$i++){
			$string .= ord($hash[$i]);
		}
		$verify = bcmod($string,90);
		$cod .= $verify;
		$bar_cod = bar_code128($cod);
		for ($i=0;$i<strlen($bar_cod);$i++){
			$this->Image('../imagini/'.$bar_cod[$i].'.jpg',($x),277,0,0,'jpeg'); 
			$x+=0.265;
		}
		$this->Ln();
		$this->SetX(5);
		$this->Cell(100,3,'conform Articolului 155 din Codul Fiscal 2007',0,1,'L');
		$this->Ln(1.5);
		$this->SetX(5);
		$this->Cell(145,3,'Documentul este valabil fara semnatura si stampila',0,0,'L');
		$this->Cell(60,3,$cod,0,1,'L');
		$this->SetX(5);
		$this->Cell(145,3,'si este semnat cu marca digitala din codul de bare.',0,0,'L');
	}
}

// Init
$pdf = new MYPDF(PDF_PAGE_ORIENTATION,PDF_UNIT,PDF_PAGE_FORMAT,true,'UTF-8',false);
$pdf->setPrintHeader(false);
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN,'',PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA,'',PDF_FONT_SIZE_DATA));
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetFooterMargin(100);
//$pdf->SetAutoPageBreak(TRUE,70);
//$pdf->SetTopMargin(50);
$pdf->SetAutoPageBreak(false);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 
$pdf->setLanguageArray($l); 
$pdf->SetDisplayMode(95);
$pdf->SetFont('helvetica','B', 11);
$pdf->AddPage();
// End Init

if ($rf['sigla']){
	$resize = construct($sigla_rel);
	if ($resize == 1) $pdf->Image($sigla_rel,10,10,0,13.45,'');
	if ($resize == 2) $pdf->Image($sigla_rel,10,4,0,26.45,'');
	$pdf->SetY(35);
	$height_header = $pdf->GetY();
}
else{
	$pdf->SetY(10);
	$height_header = $pdf->GetY();
}

$pdf->Cell(90,6,strtoupper($rf['denumire']),0,1,'L',0,'',1);
$pdf->SetFont('helvetica','',9);
$pdf->Cell(90,6,strtoupper($rf['cif']),0,1,'L');
if ($rf['adresa']) $pdf->MultiCell(90,5,ucwords($rf['adresa']),0,1,'L');
if ($rf['reg_com']) $pdf->Cell(90,6,strtoupper($rf['reg_com']),0,1,'L');
if ($rf['banca']) $pdf->MultiCell(90,5,ucwords($rf['banca']),0,1,'L');
if ($rf['iban']) $pdf->Cell(90,6,strtoupper($rf['iban']),0,1,'L');
$height_f = $pdf->GetY();

$pdf->SetXY(110,$height_header);
$pdf->SetFont('helvetica','B',11);
$pdf->Cell(80,6,strtoupper($rc['denumire']),0,2,'L');
$pdf->SetFont('helvetica','', 9);
$pdf->Cell(80,6,strtoupper($rc['cif']),0,2,'L');
$pdf->Cell(80,6,strtoupper($rc['reg_com']),0,1,'L');

if ($factura['facturi']['text_client']){
	$height_text = $pdf->GetY();
	$pdf->SetXY(110,$height_text+2);
	$text_client = explode('<br>',$factura['facturi']['text_client']);
	for ($i=0; $i<count($text_client); $i++){
		$pdf->Cell(95,5,$text_client[$i],0,1,'L',0,'',1);
		$pdf->SetX(110);
	}
}
$height_c = $pdf->GetY();
if ($height_f > $height_c) $height_top = ($height_f+10);
else $height_top = ($height_c+10);

$y_box = $height_top;
$pdf->Rect(5,$y_box,200,0);
$pdf->SetY($y_box+5);

$pdf->SetFont('helvetica','',10);
$pdf->SetXY(5,$y_box+3);
$pdf->Cell(35,5,'Serie si numar',0,0,'C');
$pdf->SetFont('helvetica','B',12);
$pdf->SetXY(5,$y_box+10);
$pdf->Cell(35,5,strtoupper($factura['facturi']['serie']).' '.$factura['facturi']['numar'],0,0,'C');

$pdf->SetFont('helvetica','',10);
if ($factura['facturi']['adv']['data_scadenta']) $width_rect = 100;
else $width_rect = 135;
$pdf->Rect($width_rect,$y_box,35,20);
$pdf->SetDrawColor(211,102,9);
$pdf->SetFillColor(241,112,9);
$pdf->Rect(($width_rect+35),$y_box,35,20,'DF');
$pdf->SetDrawColor(0,0,0);
if ($factura['facturi']['adv']['data_scadenta']) $pdf->Rect(170,$y_box,35,20);
$pdf->SetXY($width_rect,$y_box+3);
$pdf->Cell(35,5,'Data emiterii',0,0,'C');
$pdf->SetTextColor(255,255,255);
$pdf->Cell(35,5,'Total de plata',0,0,'C');
$pdf->SetTextColor(0,0,0);
if ($factura['facturi']['adv']['data_scadenta']) $pdf->Cell(35,5,'Data scadentei',0,0,'C');

$pdf->SetFont('helvetica','B',12);
$pdf->SetXY($width_rect,$y_box+10);
$pdf->Cell(35,5,convert_data($factura['facturi']['data_factura']),0,0,'C');
$pdf->SetTextColor(255,255,255);
$pdf->Cell(35,5,$factura['facturi']['total_general'].' '.$valuta,0,0,'C');
$pdf->SetTextColor(0,0,0);
if ($factura['facturi']['adv']['data_scadenta']) $pdf->Cell(35,5,convert_data($factura['facturi']['adv']['data_scadenta']),0,0,'C');

$y_tabel = $pdf->GetY();
$pdf->SetXY(5,$y_tabel+25);
$pdf->SetFont('helvetica','B',10);
$pdf->SetTextColor(255,255,255);
$pdf->SetFillColor(66,151,215);
$pdf->Cell(80.5,7.8,'Denumire produs',0,0,'C',1);
$pdf->Cell(.5,8,'',0,0);
$pdf->Cell(20.5,8,'UM',0,0,'C',1);
$pdf->Cell(.5,8,'',0,0);
$pdf->Cell(20.5,8,'Cantitate',0,0,'C',1);
$pdf->Cell(.5,8,'',0,0);
$pdf->Cell(25.5,8,'Pret unitar',0,0,'C',1);
$pdf->Cell(.5,8,'',0,0);
$pdf->Cell(25.5,8,'Valoare',0,0,'C',1);
$pdf->Cell(.5,8,'',0,0);
$pdf->Cell(25.5,8,'TVA '.$cota_tva,0,1,'C',1);
$pdf->SetTextColor(0,0,0);
$pdf->SetDrawColor(0,0,0);
$pdf->SetFont('helvetica','',10);

$pdf->SetCellPadding(2);
if (isset($factura['facturi']['linii']['linie_attr'])){
	$sp = $db->query('select * from produse where id_produs="'.$factura['facturi']['linii']['linie']['denumire_attr']['id'].'"');
	$rp = mysql_fetch_array($sp);
	
	$y0 = $pdf->GetY();
	$pdf->SetX(5);
	$pdf->SetFillColor(255,255,255);
	$pdf->MultiCell(80.5,0,($i+1).'.  '.$rp['denumire'],0,'L',1,2);
	
	$y1 = $pdf->GetY();
	$y = $y1-$y0;
	$pdf->SetXY(85.5,$y0);
	$pdf->Cell(.5,$y,'',0,0);
	$pdf->Cell(20.5,$y,$rp['unitate'],0,0,'L',1,'',1);
	$pdf->Cell(.5,$y,'',0,0);
	$pdf->Cell(20.5,$y,$factura['facturi']['linii']['linie']['cantitate'],0,0,'R',1,'',1);
	$pdf->Cell(.5,$y,'',0,0);
	$pdf->Cell(25.5,$y,$factura['facturi']['linii']['linie']['pret'],0,0,'R',1,'',1);
	$pdf->Cell(.5,$y,'',0,0);
	$pdf->Cell(25.5,$y,$factura['facturi']['linii']['linie']['valoare'],0,0,'R',1,'',1);
	$pdf->Cell(.5,$y,'',0,0);
	$pdf->Cell(25.5,$y,$factura['facturi']['linii']['linie']['tva'],0,1,'R',1,'',1);
}
else{
	for ($i=0; $i<(count($factura['facturi']['linii']['linie'])-1); $i++){
		$sp = $db->query('select * from produse where id_produs="'.$factura['facturi']['linii']['linie'][$i]['denumire_attr']['id'].'"');
		$rp = mysql_fetch_array($sp);
		if ($i%2 == 0) $pdf->SetFillColor(255,255,255);
		else $pdf->SetFillColor(235,235,235);
		
		$y0 = $pdf->GetY();
		$pdf->SetX(5);
		$pdf->MultiCell(80.5,0,($i+1).'.  '.$rp['denumire'],0,'L',1,2);
		
		$y1 = $pdf->GetY();
		$y = ($y1-$y0);
		$pdf->SetXY(85.5,$y0);
		$pdf->Cell(.5,$y,'',0,0);
		$pdf->Cell(20.5,$y,$rp['unitate'],0,0,'L',1,'',1);
		$pdf->Cell(.5,$y,'',0,0);
		$pdf->Cell(20.5,$y,$factura['facturi']['linii']['linie'][$i]['cantitate'],0,0,'R',1,'',1);
		$pdf->Cell(.5,$y,'',0,0);
		$pdf->Cell(25.5,$y,$factura['facturi']['linii']['linie'][$i]['pret'],0,0,'R',1,'',1);
		$pdf->Cell(.5,$y,'',0,0);
		$pdf->Cell(25.5,$y,$factura['facturi']['linii']['linie'][$i]['valoare'],0,0,'R',1,'',1);
		$pdf->Cell(.5,$y,'',0,0);
		$pdf->Cell(25.5,$y,$factura['facturi']['linii']['linie'][$i]['tva'],0,1,'R',1,'',1);
		
		if ($y1 > 210 && $y1 < 240){
			$pdf->AddPage(); $pdf->SetTopMargin(15); 
		}
	}
}

$pdf->SetFont('helvetica','B',11);
$pdf->Cell(0,0,'',0,1);
$pdf->Cell(144,8,'Subtotal',0,0,'R');
$pdf->Cell(25.5,8,$factura['facturi']['total_valoare'],0,0,'R',0,'',1);
$pdf->Cell(25.8,8,$factura['facturi']['total_tva'],0,1,'R',0,'',1);
$pdf->Cell(144,8,'Total General '.$valuta,0,0,'R');
$pdf->Cell(51.3,8,$factura['facturi']['total_general'],0,1,'R',0,'',1);
$pdf->Cell(0,10,'',0,1);

if (isset($_GET['op']) && $_GET['op'] == 'save_atasament'){
	$factura = '../useri/'.$_GET['subdomeniu'].'/'.$_GET['id_furnizor'].'/pdf/factura-'.$_GET['factura'].'.pdf';
	if (file_exists($factura)) unlink ($factura);
	$pdf->Output($factura,'F');
}
if (!isset($_GET['op'])) $pdf->Output('factura-'.$_GET['factura'].'.pdf','I');
?>