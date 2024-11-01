<?php

//============================================================+
// Dosya Adı   	: form.php
// Tarih       	: 06-09-2020
//
// Tanım		: WP İçi Form Arayüzü İşlemlerini 
// 				  FormElemanlari Sınıfından oluşturuyoruz
//
// Yazar		: M. Mutlu YAPICI
//
//============================================================+




/****************************************************************************************************
*																									*
*	BU SINIFTA WP EKELENTİLERİNDE KULLANACAĞIMIZ FORM ELEMANLARININ TASARIMLARINI YAPACAĞIZ			*
*	YÖNETİCİ ARAYÜZÜNDE GÖRÜNECEK FORM TASARIMINI HAZIRLIYORUZ										*
*																									*
*****************************************************************************************************/


/*Yazılım WORDPRESS'ten mi çağırılıyor kontrol edelim*/
defined('ABSPATH') or die("Bu Programı Kullanma Hakkınız YOKTUR");
require_once(HMY_WOSSE_EKLENTIDIZINI."class/formElemanlari.php");



	/*Form Elemanları için register_setting , add_settings_section, add_settings_field argumanlarını tanımlayalım*/
	/*Tüm sayfalarda görünecek form tasarımını burada yapacağız.*/
	
	/*
	*	'option_group'= Değişkenleri Yaratacağımız Pluginin Unique adı 
		'option_name'= Form Elemanlarının değilkenlerinin bulunduğu anadizi adı ("Değişkenadı")
		'callback'= Varsa Çağırılacak ek fonksiyon adı
	*
	*/
	$regArgs=array(
		array(
			'option_group'=>"HMYWSK_option_group_ayarlar",//Değişkenleri Yaratacağımız Pluginin Unique adı 
			'option_name'=>"HMYWSK_Degiskenler_ayarlar", //Form Elemanlarının değilkenlerinin bulunduğu anadizi adı ("Değişkenadı")
			'callback'=>""//varsa Çağırılacak ek fonksiyon adı
		),
		array(
			'option_group'=>"HMYWSK_option_group_sozlesme1",//Değişkenleri Yaratacağımız Pluginin Unique adı 
			'option_name'=>"HMYWSK_Degiskenler_sozlesme1", //Form Elemanlarının değilkenlerinin bulunduğu anadizi adı ("Değişkenadı")
			'callback'=>""//varsa Çağırılacak ek fonksiyon adı
		),
		array(
			'option_group'=>"HMYWSK_option_group_sozlesme2",//Değişkenleri Yaratacağımız Pluginin Unique adı 
			'option_name'=>"HMYWSK_Degiskenler_sozlesme2", //Form Elemanlarının değilkenlerinin bulunduğu anadizi adı ("Değişkenadı")
			'callback'=>""//varsa Çağırılacak ek fonksiyon adı
		),
		array(
			'option_group'=>"HMYWSK_option_group_sozlesme3",//Değişkenleri Yaratacağımız Pluginin Unique adı 
			'option_name'=>"HMYWSK_Degiskenler_sozlesme3", //Form Elemanlarının değilkenlerinin bulunduğu anadizi adı ("Değişkenadı")
			'callback'=>""//varsa Çağırılacak ek fonksiyon adı
		)
	
	);
	
	
	$secArgs=array(
		array(
			'id'=>"HMYWSK_Sec_Id_ayar",//Değişkenlerin Bölümünün idsi 
			'title'=>"Müşteri Sözleşmesi Genel Ayarları", //Değişkenlerin Bölümünün adı (Bölüm adı olarak fromda görünür)
			'callback'=>"",//varsa Çağırılacak ek fonksiyon adı
			'page'=>HMY_WOSSE_EKLENTIADI."_ayarlar"//Bu pluginin sayfasının adı
		),
		array(
			'id'=>"HMYWSK_Sec_Id_sozlesme1",//Değişkenlerin Bölümünün idsi 
			'title'=>"Sözleşme 1 Ayarları", //Değişkenlerin Bölümünün adı (Bölüm adı olarak fromda görünür)
			'callback'=>"",//varsa Çağırılacak ek fonksiyon adı
			'page'=>HMY_WOSSE_EKLENTIADI."_sozlesme1"//Bu sectiona ait form elemanlarının görüneceğ sayfasının unique adı (Bu ada göre görünecek)
		),
		array(
			'id'=>"HMYWSK_Sec_Id_sozlesme2",//Değişkenlerin Bölümünün idsi 
			'title'=>"Sözleşme 2 Ayarları", //Değişkenlerin Bölümünün adı (Bölüm adı olarak fromda görünür)
			'callback'=>"",//varsa Çağırılacak ek fonksiyon adı
			'page'=>HMY_WOSSE_EKLENTIADI."_sozlesme2"//Bu sectiona ait form elemanlarının görüneceğ sayfasının unique adı (Bu ada göre görünecek)
		),
		array(
			'id'=>"HMYWSK_Sec_Id_sozlesme3",//Değişkenlerin Bölümünün idsi 
			'title'=>"Sözleşme 3 Ayarları", //Değişkenlerin Bölümünün adı (Bölüm adı olarak fromda görünür)
			'callback'=>"",//varsa Çağırılacak ek fonksiyon adı
			'page'=>HMY_WOSSE_EKLENTIADI."_sozlesme3"//Bu sectiona ait form elemanlarının görüneceğ sayfasının unique adı (Bu ada göre görünecek)
		)
	
	);
	
	
	
	/*
	*	'id'= Form Elemanının Adı, option_name içindeki indis oluyor
		'title'= Form Elemanının Etiket Adı (Etiket adı olarak formda görünür)
		'gorev_tanim'= Form Elemanının Etiket Adının yanında belirecek bilgi ikonu üzerine gelince çıkacak GÖREV TNAIMI (Normalde bu yoktu ben ekledim Eğer boşsa ya da tanımlanmadıysa çıkmaz)
		'callback'= Form Elemanını yaratacağımız fonksiyonu çağıracağız
		'page'= Plugin sayfasının adı 
		'section'= Section add_settings_section da bulunan id ile aynı olmak zorunda
		'args'= Burda tanımlanacak argümanlar  ElemanTasarimlari fonksiyonuna gönderilecek parametreler ve form tasarımı bu parametrelere göre gelişiyor
	*
	*	
	*/
	$fieldArgs=array(
/* AYARLAR SEKMESİNİN TASARIMI */
	
		array(
			'id'=>"HMYWSK_field_Id128",
			'title'=>"Eklenti Lisans Anahtarı",
			'gorev_tanim'=>"Eklentiyi Aktif Edebilmeniz İçin Gerekli LİSANS ANAHTARINI Giriniz. Lisansınız Yoksa  <a href='https://www.futuresearches.com/urun/woocommerce-musteri-bilgileri-sozlesme-eklentisi/' style='color:blue;font-size:14px;' target='_blank'>www.futuresearches.com</a> Adresinden Temin Edebilirsiniz.",
			'callback'=>array($FRMELMN,"ElemanTasarimlari"),
			'page'=>$secArgs[0]['page'],///Bu eleman yukarıda tanımlı hangi sectiona aitse onun sayfa adı olacak
			'section'=>$secArgs[0]['id'],///Bu eleman yukarıda tanımlı hangi sectiona aitse onun idsi olacak
			'args'=>array(
				'type'=>"text",
				'name'=>"sozlesmeLisans",
				'class'=>"regular-text",
				'DegiskenAdi'=>$regArgs[0]['option_name'],
				'id'=>"sozlesmeLisans_id",
				'value'=>"",
				'placeholder'=>"Lisans Anahtarını Girin",
				'E_ekozellik'=>""
			)
		),	
		
		array(
			'id'=>"HMYWSK_field_Id1",
			'title'=>"Sözleşmelerin Konumu",
			'gorev_tanim'=>"Sözleşme Uyarılarının Sipariş Formunun Neresinde Görüneceğini Belirler",
			'callback'=>array($FRMELMN,"ElemanTasarimlari"),
			'page'=>$secArgs[0]['page'],///Bu eleman yukarıda tanımlı hangi sectiona aitse onun sayfa adı olacak
			'section'=>$secArgs[0]['id'],///Bu eleman yukarıda tanımlı hangi sectiona aitse onun idsi olacak
			'args'=>array(
				'type'=>"select",
				'name'=>"sozlesmekonum",
				'class'=>"regular-text",
				'DegiskenAdi'=>$regArgs[0]['option_name'],
				'id'=>"sozlesmekonum_id",
				'value'=>"",
				'options'=>array(	"sarvekosonce"=>"Şartlar ve Koşullardan Önce",
									"sarvekossonra"=>"Şartlar ve Koşullardan Sonra",
									"sipnotonce"=>"Sipariş Notlarından Önce",
									"sipnotsonra"=>"Sipariş Notlarından Sonra",
									"fatbilsonra"=>"Fatura Bilgilerinden Sonra"
									),
				'E_ekozellik'=>""
			)
		),
		
		
		array(
			'id'=>"HMYWSK_field_Id123",
			'title'=>"Sözleşmeler İçin Onay Kutusu",
			'gorev_tanim'=>"Sözleşme Uyarılarının Herbir Sözleşme İçin Ayrı Ayrı Mı? Yoksa Hepsi İçin Bir Tane Mi? Olacağını Belirler. (İşaretlerseniz Sipariş Formunda Tüm Sözleşmeler İçin Bir Tane Onay Kutusu Çıkar)",
			'callback'=>array($FRMELMN,"ElemanTasarimlari"),
			'page'=>$secArgs[0]['page'],///Bu eleman yukarıda tanımlı hangi sectiona aitse onun sayfa adı olacak
			'section'=>$secArgs[0]['id'],///Bu eleman yukarıda tanımlı hangi sectiona aitse onun idsi olacak
			'args'=>array(
				'type'=>"checkbox",
				'name'=>"sozlesmeOnayKutusu",
				'etiket'=>"Tüm Sözleşmeler İçin Tek Onay Kutusu Çıkar",
				'class'=>"regular-text",
				'DegiskenAdi'=>$regArgs[0]['option_name'],
				'id'=>"sozlesmeOnayKutusu_id",
				'value'=>"tekOnay"
			)
		),
		
		
		array(
			'id'=>"HMYWSK_field_Id124",
			'title'=>"Sözleşme PDF Kayıt Durumları",
			'gorev_tanim'=>"Herbir Sipariş İşlemi Sonunda İşaretli Sözleşmelerin PDF Dosyası Halinde Kaydı Tutulacaktır.",
			'callback'=>array($FRMELMN,"ElemanTasarimlari"),
			'page'=>$secArgs[0]['page'],///Bu eleman yukarıda tanımlı hangi sectiona aitse onun sayfa adı olacak
			'section'=>$secArgs[0]['id'],///Bu eleman yukarıda tanımlı hangi sectiona aitse onun idsi olacak
			'args'=>array(
				'type'=>"checkbox",
				'name'=>"sozlesmepdfdurum",
				'etiket'=>"Sözleşme1'i PDF Dosyasına Kaydet ",
				'class'=>"regular-text",
				'DegiskenAdi'=>$regArgs[0]['option_name'],
				'id'=>"sozlesmepdfdurum1_id",
				'value'=>"sozlesme1"
			)
		),
	
		
		
		array(
			'id'=>"HMYWSK_field_Id125",
			'title'=>"",
			'callback'=>array($FRMELMN,"ElemanTasarimlari"),
			'page'=>$secArgs[0]['page'],///Bu eleman yukarıda tanımlı hangi sectiona aitse onun sayfa adı olacak
			'section'=>$secArgs[0]['id'],///Bu eleman yukarıda tanımlı hangi sectiona aitse onun idsi olacak
			'args'=>array(
				'type'=>"checkbox",
				'name'=>"sozlesmepdfdurum",
				'etiket'=>"Sözleşme2'yi PDF Dosyasına Kaydet ",
				'class'=>"regular-text",
				'DegiskenAdi'=>$regArgs[0]['option_name'],
				'id'=>"sozlesmepdfdurum2_id",
				'value'=>"sozlesme2"
			)
		),
	
	
		
		
		array(
			'id'=>"HMYWSK_field_Id126",
			'title'=>"",
			'callback'=>array($FRMELMN,"ElemanTasarimlari"),
			'page'=>$secArgs[0]['page'],///Bu eleman yukarıda tanımlı hangi sectiona aitse onun sayfa adı olacak
			'section'=>$secArgs[0]['id'],///Bu eleman yukarıda tanımlı hangi sectiona aitse onun idsi olacak
			'args'=>array(
				'type'=>"checkbox",
				'name'=>"sozlesmepdfdurum",
				'etiket'=>"Sözleşme3'ü PDF Dosyasına Kaydet ",
				'class'=>"regular-text",
				'DegiskenAdi'=>$regArgs[0]['option_name'],
				'id'=>"sozlesmepdfdurum3_id",
				'value'=>"sozlesme3"
			)
		),
	
	
	
			array(
			'id'=>"HMYWSK_field_Id12",
			'title'=>"Şirket Adı",
			'gorev_tanim'=>"Sözleşmede [sirket-adi] İle Etiketli Yerlerde ve PDF Dosyasının Üst Bilgi Bölümünde Görünecek Şirket Adı Bilgisi.",
			'callback'=>array($FRMELMN,"ElemanTasarimlari"),
			'page'=>$secArgs[0]['page'],///Bu eleman yukarıda tanımlı hangi sectiona aitse onun sayfa adı olacak
			'section'=>$secArgs[0]['id'],///Bu eleman yukarıda tanımlı hangi sectiona aitse onun idsi olacak
			'args'=>array(
				'type'=>"text",
				'name'=>"sozlesmeSirketAdi",
				'class'=>"regular-text",
				'DegiskenAdi'=>$regArgs[0]['option_name'],
				'id'=>"sozlesmeSirketAdi_id",
				'value'=>"",
				'placeholder'=>"Şirketinizin Adını Girin",
				'E_ekozellik'=>""
			)
		),
		
		array(
			'id'=>"HMYWSK_field_Id13",
			'title'=>"Şirket Açıklaması",
			'gorev_tanim'=>"PDF Dosyasının Üst Bilgi Bölümünde Şirket Adı Altında Görünecek Açıklama Bilgisi.",
			'callback'=>array($FRMELMN,"ElemanTasarimlari"),
			'page'=>$secArgs[0]['page'],///Bu eleman yukarıda tanımlı hangi sectiona aitse onun sayfa adı olacak
			'section'=>$secArgs[0]['id'],///Bu eleman yukarıda tanımlı hangi sectiona aitse onun idsi olacak
			'args'=>array(
				'type'=>"textarea",
				'name'=>"sozlesmeSirketAciklama",
				'class'=>"regular-text",
				'DegiskenAdi'=>$regArgs[0]['option_name'],
				'id'=>"sozlesmeSirketAciklama_id",
				'value'=>"",
				'E_ekozellik'=>"rows='3'",
				'richtext'=>false
			)
		),
		
		
		array(
			'id'=>"HMYWSK_field_Id14",
			'title'=>"Şirket Logosu",
			'gorev_tanim'=>"PDF Dosyasının Üst Bilgi Bölümünde Görünecek Şirket Logosu (Boyutlarının 100x70 pixel olmasına dikkat edin).",
			'callback'=>array($FRMELMN,"ElemanTasarimlari"),
			'page'=>$secArgs[0]['page'],///Bu eleman yukarıda tanımlı hangi sectiona aitse onun sayfa adı olacak
			'section'=>$secArgs[0]['id'],///Bu eleman yukarıda tanımlı hangi sectiona aitse onun idsi olacak
			'args'=>array(
				'type'=>"WP_Media_Secici",
				'name'=>"sozlesmeSirketLogo",
				'etiket'=>"Tüm Sözleşmeler İçin Tek Onay Kutusu Çıkar",
				'class'=>"regular-text",
				'DegiskenAdi'=>$regArgs[0]['option_name'],
				'id'=>"sozlesmeSirketLogo_id",
				'value'=>"",
				'E_ekozellik'=>""
			)
		),
	
		

/* SÖZLEŞME 1 SEKMESİNİN TASARIMI */		
		array(
			'id'=>"HMYWSK_field_Id2",
			'title'=>"Sözleşme 1 Başlık",
			'gorev_tanim'=>"Birinci Sözleşmenin Başlığı.",
			'callback'=>array($FRMELMN,"ElemanTasarimlari"),
			'page'=>$secArgs[1]['page'],///Bu eleman yukarıda tanımlı hangi sectiona aitse onun sayfa adı olacak
			'section'=>$secArgs[1]['id'],///Bu eleman yukarıda tanımlı hangi sectiona aitse onun idsi olacak
			'args'=>array(
				'type'=>"text",
				'name'=>"sozlesmebaslik1",
				'class'=>"regular-text",
				'DegiskenAdi'=>$regArgs[1]['option_name'],
				'id'=>"sozlesmebaslik1_id",
				'value'=>"",
				'placeholder'=>"Sözleşme Başlığını Girin",
				'E_ekozellik'=>""
			)
		),
	
		array(
			'id'=>"HMYWSK_field_Id3",
			'title'=>"Sözleşme 1 Metni",
			'gorev_tanim'=>"Birinci Sözleşmenin Metni.",
			'callback'=>array($FRMELMN,"ElemanTasarimlari"),
			'page'=>$secArgs[1]['page'],///Bu eleman yukarıda tanımlı hangi sectiona aitse onun sayfa adı olacak
			'section'=>$secArgs[1]['id'],///Bu eleman yukarıda tanımlı hangi sectiona aitse onun idsi olacak
			'args'=>array(
				'type'=>"textarea",
				'name'=>"sozlesmemetni1",
				'class'=>"regular-text",
				'DegiskenAdi'=>$regArgs[1]['option_name'],
				'id'=>"sozlesmemetni1_id",
				'value'=>"",
				'E_ekozellik'=>"rows='17'",
				'richtext'=>true
			)
		),
	
		array(
			'id'=>"HMYWSK_field_Id10",
			'title'=>"Sözleşme 1 Durumu",
			'gorev_tanim'=>"Birinci Sözleşmenin Durumu. Eğer Durum AKTİF ise Sözleşme Görünür.",
			'callback'=>array($FRMELMN,"ElemanTasarimlari"),
			'page'=>$secArgs[1]['page'],///Bu eleman yukarıda tanımlı hangi sectiona aitse onun sayfa adı olacak
			'section'=>$secArgs[1]['id'],///Bu eleman yukarıda tanımlı hangi sectiona aitse onun idsi olacak
			'args'=>array(
				'type'=>"checkbox",
				'name'=>"sozlesmedurum1",
				'etiket'=>"Sözleşmeyi Aktif Edin",
				'class'=>"regular-text",
				'DegiskenAdi'=>$regArgs[1]['option_name'],
				'id'=>"sozlesmedurum1_id",
				'value'=>"sozlesme_aktif"
			)
		),
	
	

/* SÖZLEŞME 2 SEKMESİNİN TASARIMI */	
		array(
			'id'=>"HMYWSK_field_Id4",
			'title'=>"Sözleşme 2 Başlık",
			'gorev_tanim'=>"İkinci Sözleşmenin Başlığı.",
			'callback'=>array($FRMELMN,"ElemanTasarimlari"),
			'page'=>$secArgs[2]['page'],///Bu eleman yukarıda tanımlı hangi sectiona aitse onun sayfa adı olacak
			'section'=>$secArgs[2]['id'],///Bu eleman yukarıda tanımlı hangi sectiona aitse onun idsi olacak
			'args'=>array(
				'type'=>"text",
				'name'=>"sozlesmebaslik2",
				'class'=>"regular-text",
				'DegiskenAdi'=>$regArgs[2]['option_name'],
				'id'=>"sozlesmebaslik2_id",
				'value'=>"",
				'placeholder'=>"Sözleşme Başlığını Girin",
				'E_ekozellik'=>""
			)
		),
	
		array(
			'id'=>"HMYWSK_field_Id5",
			'title'=>"Sözleşme 2 Metni",
			'gorev_tanim'=>"İkinci Sözleşmenin Metni.",
			'callback'=>array($FRMELMN,"ElemanTasarimlari"),
			'page'=>$secArgs[2]['page'],///Bu eleman yukarıda tanımlı hangi sectiona aitse onun sayfa adı olacak
			'section'=>$secArgs[2]['id'],///Bu eleman yukarıda tanımlı hangi sectiona aitse onun idsi olacak
			'args'=>array(
				'type'=>"textarea",
				'name'=>"sozlesmemetni2",
				'class'=>"regular-text",
				'DegiskenAdi'=>$regArgs[2]['option_name'],
				'id'=>"sozlesmemetni2_id",
				'value'=>"",
				'E_ekozellik'=>"rows='7'",
				'richtext'=>true
			)
		),
	
		array(
			'id'=>"HMYWSK_field_Id6",
			'title'=>"Sözleşme 2 Durumu",
			'gorev_tanim'=>"İkinci Sözleşmenin Durumu. Eğer Durum AKTİF ise Sözleşme Görünür.",
			'callback'=>array($FRMELMN,"ElemanTasarimlari"),
			'page'=>$secArgs[2]['page'],///Bu eleman yukarıda tanımlı hangi sectiona aitse onun sayfa adı olacak
			'section'=>$secArgs[2]['id'],///Bu eleman yukarıda tanımlı hangi sectiona aitse onun idsi olacak
			'args'=>array(
				'type'=>"checkbox",
				'name'=>"sozlesmedurum2",
				'etiket'=>"Sözleşmeyi Aktif Edin",
				'class'=>"regular-text",
				'DegiskenAdi'=>$regArgs[2]['option_name'],
				'id'=>"sozlesmedurum2_id",
				'value'=>"sozlesme_aktif"
			)
		),
	
	

/* SÖZLEŞME 3 SEKMESİNİN TASARIMI */
		array(
			'id'=>"HMYWSK_field_Id7",
			'title'=>"Sözleşme 3 Başlık",
			'gorev_tanim'=>"Üçüncü Sözleşmenin Başlığı.",
			'callback'=>array($FRMELMN,"ElemanTasarimlari"),
			'page'=>$secArgs[3]['page'],///Bu eleman yukarıda tanımlı hangi sectiona aitse onun sayfa adı olacak
			'section'=>$secArgs[3]['id'],///Bu eleman yukarıda tanımlı hangi sectiona aitse onun idsi olacak
			'args'=>array(
				'type'=>"text",
				'name'=>"sozlesmebaslik3",
				'class'=>"regular-text",
				'DegiskenAdi'=>$regArgs[3]['option_name'],
				'id'=>"sozlesmebaslik3_id",
				'value'=>"",
				'placeholder'=>"Sözleşme Başlığını Girin",
				'E_ekozellik'=>""
			)
		),
	
		array(
			'id'=>"HMYWSK_field_Id8",
			'title'=>"Sözleşme 3 Metni",
			'gorev_tanim'=>"Üçüncü Sözleşmenin Metni.",
			'callback'=>array($FRMELMN,"ElemanTasarimlari"),
			'page'=>$secArgs[3]['page'],///Bu eleman yukarıda tanımlı hangi sectiona aitse onun sayfa adı olacak
			'section'=>$secArgs[3]['id'],///Bu eleman yukarıda tanımlı hangi sectiona aitse onun idsi olacak
			'args'=>array(
				'type'=>"textarea",
				'name'=>"sozlesmemetni3",
				'class'=>"regular-text",
				'DegiskenAdi'=>$regArgs[3]['option_name'],
				'id'=>"sozlesmemetni3_id",
				'value'=>"",
				'E_ekozellik'=>"rows='7'",
				'richtext'=>true
			)
		),
	
		array(
			'id'=>"HMYWSK_field_Id9",
			'title'=>"Sözleşme 3 Durumu",
			'gorev_tanim'=>"Üçüncü Sözleşmenin Durumu. Eğer Durum AKTİF ise Sözleşme Görünür.",
			'callback'=>array($FRMELMN,"ElemanTasarimlari"),
			'page'=>$secArgs[3]['page'],///Bu eleman yukarıda tanımlı hangi sectiona aitse onun sayfa adı olacak
			'section'=>$secArgs[3]['id'],///Bu eleman yukarıda tanımlı hangi sectiona aitse onun idsi olacak
			'args'=>array(
				'type'=>"checkbox",
				'name'=>"sozlesmedurum3",
				'etiket'=>"Sözleşmeyi Aktif Edin",
				'class'=>"regular-text",
				'DegiskenAdi'=>$regArgs[3]['option_name'],
				'id'=>"sozlesmedurum3_id",
				'value'=>"sozlesme_aktif"
			)
		)
	
	);


?>