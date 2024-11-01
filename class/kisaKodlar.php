<?php
/****************************************************************************************************
*																									*
*	BU SINIFTA WP KULLANILACAK KISA KOD İŞLEMLERİ TANIMLAYACAĞIZ									*
*	YÖNETİCİ ARAYÜZÜNDE GÖRÜNECEK KISAKODLARI FİLTRELEYEREK HAZIRLAYIP GÖNDERECEĞİMİZ FONKSİYONLAR 	*
*																									*
*****************************************************************************************************/


/*Yazılım WORDPRESS'ten mi çağırılıyor kontrol edelim*/
defined('ABSPATH') or die("Bu Programı Kullanma Hakkınız YOKTUR");


if(!class_exists("HMYS_kisakodlar"))
 {
	
	 class HMYS_kisakodlar{ 
	 
		 public $kisa_kod_listesi=Null;
		

		 
		 function __construct(){
			 $this->kisaKodListesiOlustur();
					 

			 
		 }
		 
		 
		 

		 
		 
		 
		 private function kisaKodListesiOlustur(){
			 
			 $this->kisa_kod_listesi=array(
				"billing_first_name"=>"[musteri-adi]",
				"billing_last_name"=>"[musteri-soyadi]",
				"payment_method_label"=>"[odeme-yontemi]",
				"billing_company"=>"[sirket-adi]",
				"billing_country"=>"[ulke-adi]",
				"billing_address_1"=>"[adres1]",
				"billing_address_2"=>"[adres2]",
				"billing_city"=>"[semt-adi]",
				"billing_state"=>"[sehir-adi]",
				"billing_postcode"=>"[postakodu]",
				"billing_phone"=>"[telefon]",
				"billing_email"=>"[eposta]",
				"tc_kimlik"=>"[tc-kimlik-no]",
				"cart_table"=>"[urun-sepeti]",			 
				"cart_total"=>"[sepet-tutari]",			 
				"total_payment"=>"[toplam-tutar]",			 
				"hmy_yil"=>"[yil]",			 
				"hmy_ay"=>"[ay]",			 
				"hmy_gun"=>"[gun]" ,			 
				"hmy_gunadi"=>"[gun_adi]",			 
				"hmy_ayadi"=>"[ay_adi]",			 
				"hmy_saat"=>"[saat]",			 
				"hmy_tarih"=>"[tarih]"			 
			 );
			 
		 }
		 
		 /*gelen metindeki kısakoda göre verilerle değiştirilecek*/
		 public function kisaKodFiltresi($veriler,$metin){
			 
			 $degisecekVeriler=[];
			 $aranacakVeriler=[];
				
					
			 foreach($this->kisa_kod_listesi as $k=>$v)//kısakod listesinin tamamını kontrol edelim
			 {
				 if(isset($veriler[$k]))
					$degisecekVeriler[]=$veriler[$k];
				else
					$degisecekVeriler[]="{Bu Bölüm Doldurduğunuz Bilgilere Göre Otomatik Oluşturulacak}";

				
				 $aranacakVeriler[]=$v;
			 }			
				/*
				$a=print_r($degisecekVeriler,true);
						wc_add_notice( __( 'degisecekVeriler a <strong>"oooooo '.$a.'"</strong>.', 'woocommerce' ), 'error' );


				$a=print_r($aranacakVeriler,true);
						wc_add_notice( __( 'aranacakVeriler a <strong>"oooooo '.$a.'"</strong>.', 'woocommerce' ), 'error' );
		*/

			 $yeniMetin=nl2br(str_replace($aranacakVeriler, $degisecekVeriler, $metin));
			 return $yeniMetin;

		 }
		 
		 
		 
		 
		 function __destruct(){}
		 
	 }
 }

 $KISAKOD=new HMYS_kisakodlar();