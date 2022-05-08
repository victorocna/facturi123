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
$valuta = $factura['facturi']['adv']['valuta'];

/* echo '<pre>';
print_r($factura);
echo '</pre>'; */

class MYPDF extends TCPDF {
	public function Header() {
		//vars
		$xml = '../useri/'.$_GET['subdomeniu'].'/'.$_GET['id_furnizor'].'/xml/'.$_GET['factura'].'.xml';
		$factura = xml2array($xml);
		$valuta = $factura['facturi']['adv']['valuta'];
		$sf = mysql_query('select * from firme where id_firma="'.$factura['facturi']['furnizor_attr']['id'].'" and tip_firma="0"');
		$rf = mysql_fetch_array($sf);
		$sigla_rel = '../useri/'.$_GET['subdomeniu'].'/'.$_GET['id_furnizor'].'/sigla/'.$rf['sigla'];
		//end vars
		if (strlen($factura['facturi']['numar']) > 7) $width = 48 + (strlen($factura['facturi']['numar'])-7);
		else $width = 48;

		if ($rf['sigla']){
			$resize = construct($sigla_rel);
			if ($resize == 1) $this->Image($sigla_rel,10,10,0,13.45,'');
			if ($resize == 2) $this->Image($sigla_rel,10,7,0,26.45,'');
		}
		
		$this->SetDrawColor(56,141,205);
		$this->SetFillColor(255,255,255);
		$this->Rect(70,14,$width,22,'DF');
		$headerx = 75;
		$headery = 3;
		$this->SetXY($headerx,$headery);
		
		$this->SetFont('helvetica','B',15);
		$this->SetTextColor(46,110,158);
		$this->Cell(30,12,'Factura fiscala',0,2,'L');
		
		$this->SetFont('helvetica','',11);
		$this->SetTextColor(46,110,158);
		$this->Cell(12,6,'Serie',0,0,'L');
		$this->SetTextColor(0,0,0);
		$this->Cell(30,6.2,strtoupper($factura['facturi']['serie']).' '.$factura['facturi']['numar'],0,1,'L',0,'',1);
		$this->SetX($headerx);
		$this->SetTextColor(46,110,158);
		$this->Cell(12,6,'Data',0,0,'L');
		$this->SetTextColor(0,0,0);
		$this->Cell(30,6.2,convert_data($factura['facturi']['data_factura']),0,1,'L');
		$this->SetX($headerx);
		$this->SetTextColor(46,110,158);
		$this->Cell(13,6,'Valuta',0,0,'L');
		$this->SetTextColor(0,0,0);
		$this->Cell(30,6,$valuta,0,1,'L');
	}
	public function Footer() {
		//vars
		$xml = '../useri/'.$_GET['subdomeniu'].'/'.$_GET['id_furnizor'].'/xml/'.$_GET['factura'].'.xml';
		$factura = xml2array($xml);
		$sf = mysql_query('select * from facturi where id_factura="'.$factura['facturi_attr']['id'].'"');
		$rf = mysql_fetch_array($sf);
		$height = -20;
		$data_factura = date('dmyHis',strtotime($rf['data_add']));
		$cod = $factura['facturi_attr']['id'].$factura['facturi']['client_attr']['id'].$factura['facturi']['furnizor_attr']['id'].$data_factura;
		$x = 125;
		//end vars
		
		$this->SetDrawColor(36,121,205);
		$this->Rect(5,270,200,0,'DF');
		$this->SetXY(5,$height);
		$this->SetFont('helvetica','',7);
		$this->Cell(85,3,'Document fiscal generat online de www.facturi123.ro',0,0,'L');
		$this->Cell(60,3,'Pagina '.$this->getAliasNumPage().' / '.$this->getAliasNbPages(),0,0,'L');

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
		$this->Cell(140,3,'Documentul fiscal este valabil fara semnatura si stampila',0,0,'L');
		$this->Cell(60,3,$cod,0,1,'L');
		$this->SetX(5);
		$this->Cell(140,3,'si este semnat cu marca digitala aflata in codul de bare.',0,0,'L');
	}
}

// Init
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetFooterMargin(50);
//$pdf->SetAutoPageBreak(TRUE, 40);
$pdf->SetTopMargin(50);
$pdf->SetAutoPageBreak(false);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->setLanguageArray($l); 
$pdf->SetDisplayMode(95);
$pdf->SetFont('helvetica','',11);
$pdf->AddPage();
$x_client = 110;
// End Init

$pdf->SetY(50);
$h = $pdf->GetY();
$pdf->SetTextColor(46,110,158);
$pdf->Cell(17,6,'Furnizor',0,0,'L');
$pdf->SetTextColor(0,0,0);
$pdf->SetFont('helvetica','B',11);
$pdf->Cell(73,6.2,strtoupper($rf['denumire']),0,1,'L',0,'',1);
$pdf->SetFont('helvetica','',9);
$pdf->SetTextColor(46,110,158);
$pdf->Cell(17,6,'CIF / CUI',0,0,'L');
$pdf->SetTextColor(0,0,0);
$pdf->Cell(73,6,strtoupper($rf['cif']),0,1,'L');
$pdf->SetTextColor(46,110,158);
$pdf->Cell(17,6,'Adresa',0,0,'L');
$pdf->SetTextColor(0,0,0);
if ($rf['adresa']) $pdf->MultiCell(73,5,ucwords($rf['adresa']),0,1,'L');
else $pdf->MultiCell(73,5,'-',0,1,'L');
$pdf->SetTextColor(46,110,158);
$pdf->Cell(17,6,'Reg Com',0,0,'L');
$pdf->SetTextColor(0,0,0);
if ($rf['reg_com']) $pdf->Cell(73,6,strtoupper($rf['reg_com']),0,1,'L');
else $pdf->Cell(73,6,'-',0,1,'L');
$pdf->SetTextColor(46,110,158);
$pdf->Cell(17,6,'Banca',0,0,'L');
$pdf->SetTextColor(0,0,0);
if ($rf['banca']) $pdf->MultiCell(73,5,ucwords($rf['banca']),0,1,'L');
else $pdf->MultiCell(73,5,'-',0,1,'L');
$pdf->SetTextColor(46,110,158);
$pdf->Cell(17,6,'IBAN',0,0,'L');
$pdf->SetTextColor(0,0,0);
if ($rf['iban']) $pdf->Cell(73,6,strtoupper($rf['iban']),0,1,'L');
else $pdf->Cell(73,6,'-',0,1,'L');
$y_furnizor = $pdf->GetY();

$pdf->SetXY($x_client,$h);
$pdf->SetFont('helvetica','',11);
$pdf->SetTextColor(46,110,158);
$pdf->Cell(17,6,'Client',0,0,'L');
$pdf->SetTextColor(0,0,0);
$pdf->SetFont('helvetica','B',11);
$pdf->Cell(73,6.2,strtoupper($rc['denumire']),0,1,'L',0,'',1);
$pdf->SetX($x_client);
$pdf->SetFont('helvetica','',9);
$pdf->SetTextColor(46,110,158);
$pdf->Cell(17,6,'CIF / CUI',0,0,'L');
$pdf->SetTextColor(0,0,0);
$pdf->Cell(73,6,strtoupper($rc['cif']),0,1,'L');
$pdf->SetX($x_client);
$pdf->SetTextColor(46,110,158);
$pdf->Cell(17,6,'Adresa',0,0,'L');
$pdf->SetTextColor(0,0,0);
if ($rc['adresa']) $pdf->MultiCell(73,5,ucwords($rc['adresa']),0,1,'L');
else $pdf->MultiCell(73,5,'-',0,1,'L');
$pdf->SetX($x_client);
$pdf->SetTextColor(46,110,158);
$pdf->Cell(17,6,'Reg Com',0,0,'L');
$pdf->SetTextColor(0,0,0);
if ($rc['reg_com']) $pdf->Cell(73,6,strtoupper($rc['reg_com']),0,1,'L');
else $pdf->Cell(73,6,'-',0,1,'L');
$pdf->SetX($x_client);
$pdf->SetTextColor(46,110,158);
$pdf->Cell(17,6,'Banca',0,0,'L');
$pdf->SetTextColor(0,0,0);
if ($rc['banca']) $pdf->MultiCell(73,5,ucwords($rc['banca']),0,1,'L');
else $pdf->MultiCell(73,5,'-',0,1,'L');
$pdf->SetX($x_client);
$pdf->SetTextColor(46,110,158);
$pdf->Cell(17,6,'IBAN',0,0,'L');
$pdf->SetTextColor(0,0,0);
if ($rc['iban']) $pdf->Cell(73,6,strtoupper($rc['iban']),0,1,'L');
else $pdf->Cell(73,6,'-',0,1,'L');
$y_client = $pdf->GetY();

if ($y_furnizor >= $y_client) $y_tabel = ($y_furnizor+5);
else $y_tabel = ($y_client+5);
$pdf->SetXY(5,$y_tabel+10);
$pdf->SetFont('helvetica','B',10);
$pdf->SetTextColor(255,255,255);
$pdf->SetDrawColor(36,121,195);
$pdf->SetFillColor(66,151,215);
$pdf->Cell(10,8,'Nr',1,0,'C',1);
$pdf->Cell(80,8,'Denumire produs',1,0,'C',1);
$pdf->Cell(25,8,'UM',1,0,'C',1);
$pdf->Cell(25,8,'Cantitate',1,0,'C',1);
$pdf->Cell(30,8,'Pret unitar',1,0,'C',1);
$pdf->Cell(30,8,'Valoare',1,1,'C',1);

$pdf->SetX(5);
$pdf->SetTextColor(102,102,102);
$pdf->SetDrawColor(102,102,102);
$pdf->SetFillColor(255,255,255);
$pdf->SetFont('helvetica','',8);
$pdf->Cell(10,5,'0',1,0,'C',1);
$pdf->Cell(80,5,'1',1,0,'C',1);
$pdf->Cell(25,5,'2',1,0,'C',1);
$pdf->Cell(25,5,'3',1,0,'C',1);
$pdf->Cell(30,5,'4',1,0,'C',1);
$pdf->Cell(30,5,'5',1,1,'C',1);
$y_linii = $pdf->GetY();

$pdf->SetXY(5,$y_linii+0.2);
$pdf->SetTextColor(0,0,0);
$pdf->SetFont('helvetica','',10);
$pdf->SetCellPadding(2);
if (isset($factura['facturi']['linii']['linie_attr'])){
	$sp = $db->query('select * from produse where id_produs="'.$factura['facturi']['linii']['linie']['denumire_attr']['id'].'"');
	$rp = mysql_fetch_array($sp);
	
	$y0 = $pdf->GetY();
	$pdf->SetX(15);
	$pdf->MultiCell(80,0,$rp['denumire'],'LR','L',1,2);
	
	$y1 = $pdf->GetY();
	$y = $y1-$y0;
	$pdf->SetXY(5,$y0);
	$pdf->Cell(10,$y,($i+1),'LR',0,'C',1,'',1);
	$pdf->SetXY(95,$y0);
	$pdf->Cell(25,$y,$rp['unitate'],'LR',0,'L',1,'',1,'',1);
	$pdf->Cell(25,$y,$factura['facturi']['linii']['linie']['cantitate'],'LR',0,'R',1,'',1);
	$pdf->Cell(30,$y,$factura['facturi']['linii']['linie']['pret'],'LR',0,'R',1,'',1);
	$pdf->Cell(30,$y,$factura['facturi']['linii']['linie']['valoare'],'LR',1,'R',1,'',1);
	
	$pdf->SetX(5);
	$pdf->Cell(10,10,'','LRB',0,'C',1,'',1);
	$pdf->Cell(80,10,'','LRB',0,'L',1,'',1);
	$pdf->Cell(25,10,'','LRB',0,'L',1,'',1);
	$pdf->Cell(25,10,'','LRB',0,'R',1,'',1);
	$pdf->Cell(30,10,'','LRB',0,'R',1,'',1);
	$pdf->Cell(30,10,'','LRB',1,'R',1,'',1);
}
else{
	for ($i=0; $i<(count($factura['facturi']['linii']['linie'])-1); $i++){
		$sp = $db->query('select * from produse where id_produs="'.$factura['facturi']['linii']['linie'][$i]['denumire_attr']['id'].'"');
		$rp = mysql_fetch_array($sp);
		if ($i%2 == 0) $pdf->SetFillColor(255,255,255);
		else $pdf->SetFillColor(235,235,235);
		
		$y0 = $pdf->GetY();
		$pdf->SetX(15);
		$pdf->MultiCell(80,0,$rp['denumire'],'LR','L',1,2);
		
		$y1 = $pdf->GetY();
		$y = $y1-$y0;
		$pdf->SetXY(5,$y0);
		$pdf->Cell(10,$y,($i+1),'LR',0,'C',1,'',1);
		$pdf->SetXY(95,$y0);
		$pdf->Cell(25,$y,$rp['unitate'],'LR',0,'L',1,'',1,'',1);
		$pdf->Cell(25,$y,$factura['facturi']['linii']['linie'][$i]['cantitate'],'LR',0,'R',1,'',1);
		$pdf->Cell(30,$y,$factura['facturi']['linii']['linie'][$i]['pret'],'LR',0,'R',1,'',1);
		$pdf->Cell(30,$y,$factura['facturi']['linii']['linie'][$i]['valoare'],'LR',1,'R',1,'',1);
		
		if ($y1 > 230 && $y1 < 260){
			$pdf->AddPage(); $pdf->SetTopMargin(50);
		}
	}
	$pdf->SetFillColor(255,255,255);
	$pdf->SetX(5);
	$pdf->Cell(10,12,'','LRB',0,'C',1,'',1);
	$pdf->Cell(80,12,'','LRB',0,'L',1,'',1);
	$pdf->Cell(25,12,'','LRB',0,'L',1,'',1);
	$pdf->Cell(25,12,'','LRB',0,'R',1,'',1);
	$pdf->Cell(30,12,'','LRB',0,'R',1,'',1);
	$pdf->Cell(30,12,'','LRB',1,'R',1,'',1);
}

$y_adv = $pdf->GetY();
$pdf->Rect(5,$y_adv,115,26);
if ($factura['facturi']['adv']['reprez_attr']['id']){
	$sr = $db->query('select * from reprezentanti where id_reprez="'.$factura['facturi']['adv']['reprez_attr']['id'].'"');
	$rr = mysql_fetch_array($sr);
	$pdf->SetFont('helvetica','B',8);
	$pdf->SetTextColor(46,110,158);
	$pdf->Cell(20,8,'Intocmit de',0,0,'L');
	$pdf->SetFont('helvetica','',8);
	$pdf->SetTextColor(0,0,0);
	$pdf->Cell(70,8,ucwords($rr['nume_reprez']),0,1,'L',0,'',1);
	if ($rr['act_reprez']){
		$pdf->SetFont('helvetica','B',8);
		$pdf->SetTextColor(46,110,158);
		$pdf->Cell(20,2,'Act identitate',0,0,'L');
		$pdf->SetFont('helvetica','',8);
		$pdf->SetTextColor(0,0,0);
		$pdf->Cell(70,2,strtoupper($rr['act_reprez']),0,1,'L',0,'',1);
	}
}
if ($factura['facturi']['adv']['delegat_attr']['id']){
	$sd = $db->query('select * from delegati where id_delegat="'.$factura['facturi']['adv']['delegat_attr']['id'].'"');
	$rd = mysql_fetch_array($sd);
	$pdf->SetFont('helvetica','B',8);
	$pdf->SetTextColor(46,110,158);
	$pdf->Cell(20,8,'Delegat',0,0,'L');
	$pdf->SetFont('helvetica','',8);
	$pdf->SetTextColor(0,0,0);
	$pdf->Cell(70,8,ucwords($rd['nume_delegat']),0,1,'L',0,'',1);
	if ($rd['act_identitate']){
		$pdf->SetFont('helvetica','B',8);
		$pdf->SetTextColor(46,110,158);
		$pdf->Cell(20,2,'Act identitate',0,0,'L');
		$pdf->SetFont('helvetica','',8);
		$pdf->SetTextColor(0,0,0);
		$pdf->Cell(70,2,strtoupper($rd['act_identitate']),0,1,'L',0,'',1);
	}
}

$pdf->SetXY(120,$y_adv);
$pdf->SetFont('helvetica','B',10);
$pdf->Cell(55,13,'Subtotal',1,0,'R');
$pdf->Cell(30,13,$factura['facturi']['total_valoare'],1,1,'R',1,'',1);
$pdf->SetX(120);
$pdf->Cell(55,13,'Total General '.$valuta,1,0,'R');
$pdf->Cell(30,13,$factura['facturi']['total_general'],1,1,'R',1,'',1);

if (isset($factura['facturi']['adv']['data_scadenta']) || isset($factura['facturi']['adv']['observatii'])){
	$y_text = $pdf->GetY();
	$pdf->Rect(5,$y_text,200,14);
	if (isset($factura['facturi']['adv']['data_scadenta'])){
		$pdf->SetFont('helvetica','B',8);
		$pdf->SetTextColor(46,110,158);
		$pdf->Cell(22,8,'Data scadentei',0,0,'L');
		$pdf->SetFont('helvetica','',8);
		$pdf->SetTextColor(0,0,0);
		$pdf->Cell(70,8,convert_data($factura['facturi']['adv']['data_scadenta']),0,1,'L');
	}
	if (isset($factura['facturi']['adv']['observatii'])){
		$pdf->SetFont('helvetica','B',8);
		$pdf->SetTextColor(46,110,158);
		$pdf->Cell(22,2,'Observatii',0,0,'L');
		$pdf->SetFont('helvetica','',8);
		$pdf->SetTextColor(0,0,0);
		$pdf->Cell(170,2,$factura['facturi']['adv']['observatii'],0,1,'L',0,'',1);
	}
	$pdf->SetY($y_text+17);
}
if (!isset($factura['facturi']['adv']['data_scadenta']) && !isset($factura['facturi']['adv']['observatii'])){
	$y_text = $pdf->GetY();
	$pdf->SetY($y_text+6);
}
if (isset($factura['facturi']['text_client'])){
	$pdf->SetFont('helvetica','',8);
	$pdf->SetTextColor(0,0,0);
	$text_client = explode('<br>',$factura['facturi']['text_client']);
	for ($i=0; $i<count($text_client); $i++){
		$pdf->Cell(170,5,$text_client[$i],0,1,'L',0,'',1);
	}
}

if (isset($_GET['op']) && $_GET['op'] == 'save_atasament'){
	$factura = '../useri/'.$_GET['subdomeniu'].'/'.$_GET['id_furnizor'].'/pdf/factura-'.$_GET['factura'].'.pdf';
	if (file_exists($factura)) unlink ($factura);
	$pdf->Output($factura,'F');
}
if (!isset($_GET['op'])) $pdf->Output('factura-'.$_GET['factura'].'.pdf','I');
?>