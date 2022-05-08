<?php
session_start();
include ('../includes/config.php');
require_once('../includes/tcpdf/config/lang/eng.php');
require_once('../includes/tcpdf/tcpdf.php');

//alias
$id_user = $_GET['idf'];
$id_platitor = $_GET['id_furnizor'];
$id_plata = $_GET['factura'];
//end alias

$sql = $db->query('select * from plati,useri where plati.id_plata="'.$id_plata.'" and useri.id_user="'.$id_user.'" and plati.id_user=useri.id_user');
$row = mysql_fetch_array($sql);
$sc = $db->query('select * from firme where id_firma="'.$id_platitor.'" and tip_firma="9"');
$rc = mysql_fetch_array($sc);
$valuta = 'Lei';

//discount
if ($row['perioada'] == '6 Luni') $discount = 'Discount 10%';
if ($row['perioada'] =='12 Luni') $discount = 'Discount 20%';
//end discount
$st = $db->query('select * from tip_cont where id_tip="'.$row['id_tip'].'"');
$rt = mysql_fetch_array($st);
$cont = ucwords($rt['denumire']);

class MYPDF extends TCPDF {
	public function Header() {
		//alias
		$id_user = $_GET['idf'];
		$id_platitor = $_GET['id_furnizor'];
		$id_plata = $_GET['factura'];
		//end alias
		//vars
		$valuta = 'Lei';
		$sql = mysql_query('select * from plati where id_plata="'.$id_plata.'"');
		$row = mysql_fetch_array($sql);
		//end vars

		$this->SetDrawColor(56,141,205);
		$this->SetFillColor(255,255,255);
		$this->Rect(70,14,48,22,'DF');
		$headerx = 75;
		$headery = 3;
		$this->SetXY($headerx,$headery);
		
		$this->SetFont('helvetica','B',12.5);
		$this->SetTextColor(46,110,158);
		$this->Cell(30,12,'Instiintare de plata',0,2,'L');
		
		$this->SetFont('helvetica','',10);
		$this->SetTextColor(46,110,158);
		$this->Cell(13,6,'Serie',0,0,'L');
		$this->SetFont('helvetica','B',10);
		$this->SetTextColor(0,0,0);
		$this->Cell(30,6.2,strtoupper($row['serie']).' '.$row['numar'],0,1,'L',0,'',1);
		$this->SetX($headerx);
		$this->SetFont('helvetica','',10);
		$this->SetTextColor(46,110,158);
		$this->Cell(13,6,'Data',0,0,'L');
		$this->SetFont('helvetica','B',10);
		$this->SetTextColor(0,0,0);
		$this->Cell(30,6.2,convert_data(date('d-m-Y')),0,1,'L');
		$this->SetX($headerx);
		$this->SetFont('helvetica','',10);
		$this->SetTextColor(46,110,158);
		$this->Cell(13,6,'Valuta',0,0,'L');
		$this->SetFont('helvetica','B',10);
		$this->SetTextColor(0,0,0);
		$this->Cell(30,6,$valuta,0,1,'L');
	}
	public function Footer() {
		//alias
		$id_user = $_GET['idf'];
		$id_platitor = $_GET['id_furnizor'];
		$id_plata = $_GET['factura'];
		//end alias
		//vars
		$sql = mysql_query('select * from plati where id_plata="'.$id_plata.'"');
		$row = mysql_fetch_array($sql);
		$height = -20;
		$ttl_general = $row['pret_fin'];
		$cod = $id_user.$id_platitor.$id_plata.$ttl_general;
		$x = 140;
		//end vars
		
		$this->SetDrawColor(36,121,205);
		$this->Rect(5,270,200,0,'DF');
		$this->SetY($height);
		$this->SetFont('helvetica','',7);
		$this->Cell(85,5,'Document electronic generat online de www.facturi123.ro',0,0,'L');
		$this->Cell(60,5,'Pagina '.$this->getAliasNumPage().' / '.$this->getAliasNbPages(),0,0,'L');

		$hash = hash('md4',$cod);
		for ($i=0;$i<strlen($hash);$i++){
			$string .= ord($hash[$i]);
		}
		$verify = bcmod($string,90);
		$cod .= 'v'.$verify;
		$bar_cod = bar_code128($cod);
		for ($i=0;$i<strlen($bar_cod);$i++){
			$this->Image('../imagini/'.$bar_cod[$i].'.jpg',($x),278,0,0,'jpeg'); 
			$x+=0.265;
		}
		$this->Ln(3);
		$this->Cell(80,5,'Documentul este valabil fara semnatura si stampila.',0,0,'L');
	}
}

// Init
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetFooterMargin(50);
//$pdf->SetAutoPageBreak(TRUE, 40);
$pdf->SetTopMargin(45);
$pdf->SetAutoPageBreak(false);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->setLanguageArray($l); 
$pdf->SetDisplayMode(100);
$pdf->SetFont('helvetica','',11);
$pdf->AddPage();
$x_client = 110;
// End Init

$pdf->SetY(45);
$h = $pdf->GetY();
$pdf->SetTextColor(46,110,158);
$pdf->Cell(17,6,'Furnizor',0,0,'L');
$pdf->SetTextColor(0,0,0);
$pdf->SetFont('helvetica','B',11);
$pdf->Cell(73,6.2,'SC CREATIVE MINDS SOFTWARE',0,1,'L',0,'',1);
$pdf->SetFont('helvetica','',9);
$pdf->SetTextColor(46,110,158);
$pdf->Cell(17,6,'CIF / CUI',0,0,'L');
$pdf->SetTextColor(0,0,0);
$pdf->Cell(73,6,'24032840',0,1,'L');
$pdf->SetTextColor(46,110,158);
$pdf->Cell(17,6,'Adresa',0,0,'L');
$pdf->SetTextColor(0,0,0);
$pdf->MultiCell(73,5,'Str Recoltei Nr 20, Chitila Jud Ilfov',0,1,'L');
$pdf->SetTextColor(46,110,158);
$pdf->Cell(17,6,'Reg Com',0,0,'L');
$pdf->SetTextColor(0,0,0);
$pdf->Cell(73,6,'J23/1817/2008',0,1,'L');
$pdf->SetTextColor(46,110,158);
$pdf->Cell(17,6,'Banca',0,0,'L');
$pdf->SetTextColor(0,0,0);
$pdf->MultiCell(73,5,'Italo Romena Agentia Unirii',0,1,'L');
$pdf->SetTextColor(46,110,158);
$pdf->Cell(17,6,'IBAN',0,0,'L');
$pdf->SetTextColor(0,0,0);
$pdf->Cell(73,6,'RO57 BITR BU1R ON03 1725 CC01',0,1,'L');
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
if ($row['pret_ini'] == $row['pret_fin']){
	$y0 = $pdf->GetY();
	$pdf->SetX(15);
	$pdf->MultiCell(80,0,'Abonament Facturi123 - Cont '.$cont.' '.$row['perioada'],'LR','L',1,2);
	
	$y1 = $pdf->GetY();
	$y = $y1-$y0;
	$pdf->SetXY(5,$y0);
	$pdf->Cell(10,$y,($i+1),'LR',0,'C',1,'',1);
	$pdf->SetXY(95,$y0);
	$pdf->Cell(25,$y,'buc','LR',0,'L',1,'',1,'',1);
	$pdf->Cell(25,$y,'1','LR',0,'R',1,'',1);
	$pdf->Cell(30,$y,$row['pret_ini'],'LR',0,'R',1,'',1);
	$pdf->Cell(30,$y,$row['pret_ini'],'LR',1,'R',1,'',1);
}
else{
	$y0 = $pdf->GetY();
	$pdf->SetX(15);
	$pdf->MultiCell(80,0,'Abonament Facturi123 - Cont '.$cont.' '.$row['perioada'],'LR','L',1,2);
	
	$y1 = $pdf->GetY();
	$y = $y1-$y0;
	$pdf->SetXY(5,$y0);
	$pdf->Cell(10,$y,'1','LR',0,'C',1,'',1);
	$pdf->SetXY(95,$y0);
	$pdf->Cell(25,$y,'buc','LR',0,'L',1,'',1,'',1);
	$pdf->Cell(25,$y,'1','LR',0,'R',1,'',1);
	$pdf->Cell(30,$y,$row['pret_ini'],'LR',0,'R',1,'',1);
	$pdf->Cell(30,$y,$row['pret_ini'],'LR',1,'R',1,'',1);
	
	$y0 = $pdf->GetY();
	$pdf->SetX(15);
	$pdf->MultiCell(80,0,$discount,'LR','L',1,2);
	
	$y1 = $pdf->GetY();
	$y = $y1-$y0;
	$pdf->SetXY(5,$y0);
	$pdf->Cell(10,$y,'2','LR',0,'C',1,'',1);
	$pdf->SetXY(95,$y0);
	$pdf->Cell(25,$y,'buc','LR',0,'L',1,'',1,'',1);
	$pdf->Cell(25,$y,'1','LR',0,'R',1,'',1);
	$pdf->Cell(30,$y,'-'.($row['pret_ini']-$row['pret_fin']),'LR',0,'R',1,'',1);
	$pdf->Cell(30,$y,'-'.($row['pret_ini']-$row['pret_fin']),'LR',1,'R',1,'',1);
}
$pdf->SetFillColor(255,255,255);
$pdf->SetX(5);
$pdf->Cell(10,6,'','LRB',0,'C',1,'',1);
$pdf->Cell(80,6,'','LRB',0,'L',1,'',1);
$pdf->Cell(25,6,'','LRB',0,'L',1,'',1);
$pdf->Cell(25,6,'','LRB',0,'R',1,'',1);
$pdf->Cell(30,6,'','LRB',0,'R',1,'',1);
$pdf->Cell(30,6,'','LRB',1,'R',1,'',1);

$y_adv = $pdf->GetY();
$pdf->Rect(5,$y_adv,115,26);

$pdf->SetXY(6,($y_adv+2));
$pdf->SetFont('helvetica','B',9);
$pdf->SetTextColor(46,110,158);
$pdf->Cell(25,8,'Data scadentei',0,0,'L');
$pdf->SetFont('helvetica','',9);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(70,8,convert_data(date('d-m-Y',strtotime($row['data_scadenta']))),0,1,'L',0,'',1);

$pdf->SetXY(6,($y_adv+8));
$pdf->SetFont('helvetica','B',9);
$pdf->SetTextColor(46,110,158);
$pdf->Cell(25,8,'Observatii',0,0,'L');
$pdf->SetFont('helvetica','',9);
$pdf->SetTextColor(0,0,0);
$pdf->Cell(70,8,'La plata, va rugam sa specificati codul '.$row['cod'],0,1,'L',0,'',1);

$pdf->SetXY(120,$y_adv);
$pdf->SetFont('helvetica','B',11);
$pdf->Cell(55,13,'Subtotal',1,0,'R');
$pdf->Cell(30,13,$row['pret_fin'],1,1,'R',1,'',1);
$pdf->SetX(120);
$pdf->Cell(55,13,'Total General '.$valuta,1,0,'R');
$pdf->Cell(30,13,$row['pret_fin'],1,1,'R',1,'',1);

if (isset($_GET['op']) && $_GET['op'] == 'save_instiintare'){
	$instiintare = '../useri/'.$row['subdomeniu'].'/'.$id_platitor.'/instiintare-plata-'.$row['serie'].'-'.$row['numar'].'.pdf';
	if (file_exists($instiintare)) unlink ($instiintare);
	$pdf->Output($instiintare,'F');
}
if (!isset($_GET['op'])) $pdf->Output('instiintare-plata-'.$row['serie'].'-'.$row['numar'].'.pdf','I');
?>