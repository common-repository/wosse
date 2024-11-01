<?php

//============================================================+
// Dosya Adı   	: pdfCevirici.php
// Tarih       	: 06-09-2020
//
// Tanım		: WooCommerce Eklentisi İçin Sözleşmelerin PDF Halini 
//				  TCPDF sınıfını kullanarak oluşturuyoruz
//
// Yazar		: M. Mutlu YAPICI
//
//============================================================+
$PDFsirketadi=isset($this->GenelAyarVerileri['sozlesmeSirketAdi'])?$this->GenelAyarVerileri['sozlesmeSirketAdi']:"";
$PDFsirketAciklama=isset($this->GenelAyarVerileri['sozlesmeSirketAciklama'])?$this->GenelAyarVerileri['sozlesmeSirketAciklama']:"";
$PDFsirketLogo=isset($this->GenelAyarVerileri['sozlesmeSirketLogo'])?$this->GenelAyarVerileri['sozlesmeSirketLogo']:"";
 define('HMYS_PDF_FONT_NAME_MAIN','dejavusans');
 define ('HMYS_PDF_HEADER_TITLE', $PDFsirketadi);
 define ('HMYS_PDF_HEADER_STRING', $PDFsirketAciklama);
 define ('K_PATH_IMAGES', '');
 
 
 if(!file_exists($PDFsirketLogo)){
	 $urr=(parse_url($PDFsirketLogo));

	 if(!function_exists("get_home_path"))
		require_once( ABSPATH . 'wp-admin/includes/file.php' );
	$ghp=get_home_path();
	 $yeniurl=$ghp.substr($urr['path'],1);
	 
	if(file_exists($yeniurl))
		$PDFsirketLogo=$yeniurl;
	else{
		if(!function_exists("wp_upload_dir"))
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
		$upload_dir = wp_upload_dir();
		$yeniurl=$upload_dir['basedir'].substr($urr['path'],(strpos($urr['path'],'uploads')+7));
		
		$PDFsirketLogo=$yeniurl;
		
	}
 }
 
 
 define ('HMYS_PDF_HEADER_LOGO', $PDFsirketLogo);
 define ('HMYS_PDF_HEADER_LOGO_WIDTH', 30);
 
$tcpdf_include_dirs = array(
	realpath(HMY_WOSSE_EKLENTIDIZINI.'TCPDF/tcpdf.php')
);
foreach ($tcpdf_include_dirs as $tcpdf_include_path) {
	if (@file_exists($tcpdf_include_path)) {
		require_once($tcpdf_include_path);
		break;
	}
}


// TCPDF sınıfından nesne oluşturuyoruz
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// PDF dokümanı için doküman bilgilerini tanımlıyoruz
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('HM Yazılım Şirketi');
$pdf->SetTitle($PDFsirketadi);
$pdf->SetSubject('WooCommerce WOSSE Sözleşmeler');
//$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// PDF üst bilgi verilerini oluşturuyoruz
$pdf->SetHeaderData(HMYS_PDF_HEADER_LOGO, HMYS_PDF_HEADER_LOGO_WIDTH, HMYS_PDF_HEADER_TITLE, HMYS_PDF_HEADER_STRING);

// header ve footer yazı fontları
$pdf->setHeaderFont(Array(HMYS_PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// Dokümanın  boşlukları
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// Döküman Sonu Ayarı
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// Görüntü oranı
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

/*
// Dil ayarları yapılabilir
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

*/

// set default font subsetting mode
$pdf->setFontSubsetting(true);

// İçerik için font tanımlayalım
$pdf->SetFont('dejavusans', '', 11);

// sayfayı oluşturduk
$pdf->AddPage();

$html="";

for($i=0;$i<count($this->sozlesmePDFdurumu); $i++)
{
	
	switch($this->sozlesmePDFdurumu[$i])
	{
		case 'sozlesme1':
			if(isset($this->FilltreliModalVerileri['Sozlesme1_modal']))
				$html=$this->TabloTagiTemizle(wpautop($this->FilltreliModalVerileri['Sozlesme1_modal']));
			else
				$html="";			
		break;
		case 'sozlesme2':
			if(isset($this->FilltreliModalVerileri['Sozlesme2_modal']))
				$html=$this->TabloTagiTemizle(wpautop($this->FilltreliModalVerileri['Sozlesme2_modal']));
			else
				$html="";		
		break;
		case 'sozlesme3':
			if(isset($this->FilltreliModalVerileri['Sozlesme3_modal']))
				$html=$this->TabloTagiTemizle(wpautop($this->FilltreliModalVerileri['Sozlesme3_modal']));
			else
				$html="";
		break;
	}
	
	if($html!="" && !empty($html)){
		if($i>0)
			$pdf->AddPage();
		
		$pdf->writeHTML($html, true, false, true, false, '');
	}
	
}



//İçeriği temizleyip oluşturduk
//$html=$this->TabloTagiTemizle(wpautop($this->FilltreliModalVerileri['Sozlesme1_modal']));

// İçeriği HTML formatından çevirip PDF e yazdık


// Sayfa sonunu işaretledil
$pdf->lastPage();

$isim="Sozlesme_".$this->sipID."_".$this->TarihSaat("d_m_Y");


 if(!file_exists(HMY_WOSSE_EKLENTIDIZINI.'sozlesmeler/'.$this->TarihSaat("d_m_Y")))
	mkdir(HMY_WOSSE_EKLENTIDIZINI.'sozlesmeler/'.$this->TarihSaat("d_m_Y"), 0755, true);


//Dokumanı oluşturarak kapattık sonunda D ekleyince indiriyor I ekleyince otomatik açıyor F fdeyince kaydediyor
$pdf->Output(HMY_WOSSE_EKLENTIDIZINI.'sozlesmeler/'.$this->TarihSaat("d_m_Y").'/'.$isim.'.pdf', 'F');

//============================================================+
// BİTTİ
//============================================================+


?>