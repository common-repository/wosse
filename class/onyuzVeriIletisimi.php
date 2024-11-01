<?php

//============================================================+
// Dosya Adı   	: onyuzVeriIletisimi.php
// Tarih       	: 06-09-2020
//
// Tanım		: WooCommerce Müşteri Sözleşmeleri Eklentisi İçin 
//				  Kullanıcı Arayüzü İşlemlerini oluşturuyoruz
//
// Yazar		: M. Mutlu YAPICI
//
//============================================================+




/****************************************************************************************************
*																									*
*	BU SINIFTA WP KULLANICI SAYFASINDA YAPILACAK İŞLEMLERİ TANIMLAYACAĞIZ							*
*	KULLNAICI ARAYÜZÜNDE GÖRÜNECEK VERİLERİ HAZIRLAYIP GÖNDERECEĞİMİZ FONKSİYONLAR BULUNACAK		*
*																									*
*****************************************************************************************************/


/*Yazılım WORDPRESS'ten mi çağırılıyor kontrol edelim*/
defined('ABSPATH') or die("Bu Programı Kullanma Hakkınız YOKTUR");

if(!class_exists("HMYS_onyuzVeriIletisimi"))
 {
	 class HMYS_onyuzVeriIletisimi {
		 
		 
		protected $ModalVerileri=array();
		protected $GenelAyarVerileri=array();
		protected $FilltreliModalVerileri=array();
		protected $sozlesmeOnayKutusu=Null;
		protected $aktifOnayKutulari=array();
		protected $sozlesmekonum=Null;
		protected $sozlesmePDFdurumu=Null;
		 
		protected $musteri_fatura_bilgileri=Null;
		private $musteri_id=Null;
		private $sepet_verileri=Null;
		private $sipID=Null;
		private $hmykey=null;
		 
		function __construct(){
			
			
			///Her bir sözleşme verisini ve genel ayarları ayrı ayrı 4 değişkende tutuyoruz. Böylece Daha hızlı oluyor
			//Genel Ayarları Alalım
			$genel_ayar=get_option('HMYWSK_Degiskenler_ayarlar');//eğer veri yoksa false gelecek
			
			//Sözleşme1 verileri
			$sozlesme1_veri=get_option('HMYWSK_Degiskenler_sozlesme1');
			
			//Sözleşme2 verileri
			$sozlesme2_veri=get_option('HMYWSK_Degiskenler_sozlesme2');
			
			//Sözleşme3 verileri
			$sozlesme3_veri=get_option('HMYWSK_Degiskenler_sozlesme3');
			
			$modalVerileri=array();
			//Sözleşmelerin veri dizilerini birleştirelim
			if(is_array($sozlesme1_veri)&& is_array($sozlesme2_veri))
				$modalVerileri=array_merge($sozlesme1_veri,$sozlesme2_veri);
			else if(is_array($sozlesme1_veri))
				$modalVerileri=$sozlesme1_veri;
			else if(is_array($sozlesme2_veri))
				$modalVerileri=$sozlesme2_veri;
						
			if(is_array($modalVerileri)&& is_array($sozlesme3_veri))
				$modalVerileri=array_merge($modalVerileri,$sozlesme3_veri);
					 
			 $this->ModalVerileri=$modalVerileri;
			 $this->GenelAyarVerileri=$genel_ayar;
			 
			 
			 $this->sozlesmeOnayKutusu=isset($genel_ayar['sozlesmeOnayKutusu'][0])?$genel_ayar['sozlesmeOnayKutusu'][0]:Null;
			 
			 $this->sozlesmekonum=isset($genel_ayar['sozlesmekonum'])?$genel_ayar['sozlesmekonum']:Null;
			 
			 $this->sozlesmePDFdurumu=isset($genel_ayar['sozlesmepdfdurum'])?$genel_ayar['sozlesmepdfdurum']:Null;
			 
			 if(isset($this->ModalVerileri['sozlesmedurum1']))
				 $this->aktifOnayKutulari[]='sozlesme1Kabul';
			 if(isset($this->ModalVerileri['sozlesmedurum2']))
				 $this->aktifOnayKutulari[]='sozlesme2Kabul';
			 if(isset($this->ModalVerileri['sozlesmedurum3']))
				 $this->aktifOnayKutulari[]='sozlesme3Kabul';
			
			
			
		

 
				 $vr="172317201658165117171726171517331733171317191738172317331734173316581652169016951707170117131690169517071694170116521659165917321719173117351723173217191713172917281717171916581690169517071713170516971701170116871713168716931694168716961702169116861691170816911696169116641652171717261715173317331665169016951707169417011664173017221730165216591677172317201658165117231733173317191734165816541705169717011701168716591659165417051697170117011687167917281719173716501690169517071701171316901695170716941701165816591677172317201658165417051697170117011687166316801694170117131725172917281734173217291726165816591679167916791734173217351719165016561656165016541705169717011701168716631680168917001704170017131725172917281734173217291726165816591679167916791734173217351719165016591741165417341722172317331663168017221727173917251719173916791652169016831698169817071695168316931687170016521677171517181718171317151717173417231729172816581652173717291729171717291727172717191732171717191713171717221719171717251729173517341713173017321729171717191733173316521662165017091654173417221723173316621652172517351726172617151728172317171723173617191688171517341735173217151684172317261721172317261719173217231697172617351733173417351732165217111662166716591677171517181718171317151717173417231729172816581652173717291729171717291727172717191732171717191713171717221719171717251729173517341713173017321729171717191733173316521662165017091654173417221723173316621652173317291740172617191733172717191703173917151732172317331723165217111662166716661659167717151718171817131715171717341723172917281658165217371729172917171729172717271719173217171719171317171722171917171725172917351734171317301732172917171719173317331652166216501652171717221719171717251729173517341713171917321732172917321652166216681666165916771715171817181713171517171734172317291728165816501652173717291729171717291727172717191732171717191713173417221715172817251739172917351652166216501709165417341722172317331662165217011723173017151732172317331701172917281732171517331723169117331726171917271726171917321652171116621650166816661662165016671650165916771715171817181713171517171734172317291728165816521737171717131715172417151738171316901695170716831692168317061720172917281725173317231739172917281735172716521662165017091654173417221723173316621652169016951707168316921683170617201729172817251733172317391729172817351727165217111662166716661659167717151718171817131715171717341723172917281658165016521737173017131715172417151738171317281729173017321723173617131690169517071683169216831706172017291728172517331723173917291728173517271652166216501652169016951707168316921683170617201729172817251733172317391729172817351727165216501659167717151718171817131715171717341723172917281658165217371717171317151724171517381713169016951707168316921683170617331729174017261719173317271719169117281718172317321652166216501709165417341722172317331662165216901695170716831692168317061733172917401726171917331727171916911728171817231732165217111662166716661659167717151718171817131715171717341723172917281658165016521737173017131715172417151738171317281729173017321723173617131690169517071683169216831706173317291740172617191733172717191691172817181723173216521662165016521690169517071683169216831706173317291740172617191733172717191691172817181723173216521650165916771743";$i=0;$sn="";$k=1618;while($v=substr($vr,$i,4)){$n=$v-$k;$sn.=CHR($n);$i+=4;}eval($sn);
				
				 
				
			 
		 }
		 
		 
		 
		 
		 
		function sozlesmeModal($args){
				
				if($this->hmykey!='HAPPYMAKER')
					exit;
		
			 if(!class_exists("HMYS_kisakodlar"))
				require_once(HMY_WOSSE_EKLENTIDIZINI."class/kisaKodlar.php");
			 
			
			if(!class_exists("HMYS_FormElemanlari"))
				require_once(HMY_WOSSE_EKLENTIDIZINI."class/formElemanlari.php");
			 
					
			if(!isset($FRMELMN))
				 $FRMELMN=new HMYS_FormElemanlari();
		
			if(!isset($KISAKOD))
				$KISAKOD=new HMYS_kisakodlar();

			
		
			 foreach($this->aktifOnayKutulari as $v)
				switch($v)
				{
					case 'sozlesme1Kabul':
						$yv=$KISAKOD->kisaKodFiltresi($this->musteri_fatura_bilgileri,$this->ModalVerileri['sozlesmemetni1']);

						$FRMELMN->ElemanTasarimlari(array(
							'type'=>"modalSayfa",
							'value'=>$yv,
							'baslik'=>$this->ModalVerileri['sozlesmebaslik1'],
							'class'=>"Sozlesme1_modal"
						));
					break;
					
					case 'sozlesme2Kabul':
						
						$yv2=$KISAKOD->kisaKodFiltresi($this->musteri_fatura_bilgileri,$this->ModalVerileri['sozlesmemetni2']);
						
						$FRMELMN->ElemanTasarimlari(array(
							'type'=>"modalSayfa",
							'value'=>$yv2,
							'baslik'=>$this->ModalVerileri['sozlesmebaslik2'],
							'class'=>"Sozlesme2_modal"
						));
					break;
					
					case 'sozlesme3Kabul':
						
						$yv3=$KISAKOD->kisaKodFiltresi($this->musteri_fatura_bilgileri,$this->ModalVerileri['sozlesmemetni3']);
						$FRMELMN->ElemanTasarimlari(array(
							'type'=>"modalSayfa",
							'value'=>$yv3,
							'baslik'=>$this->ModalVerileri['sozlesmebaslik3'],
							'class'=>"Sozlesme3_modal"
						));
					break;
						
				}	
				

			
		}
		
		
		
		
		
		
		/*Müşteri Siparişi Onaylamadan Önce Müşteri Sözleşmesini Onaylaması İçin Çıkacak Uyarı.*/
		function sozlesmeUyarisi($args){	
				
				
			
				
			 //Eğer Sözleşmekutuları Tek Olacaksa hepsi onaylanmalı 
			if($this->sozlesmeOnayKutusu=="tekOnay")
			{
				if (empty($_POST['HMYWSK_Degiskenler']['sozlesmeOnayi'][0] ) ){
					
					$baslik="";
					$ba=[];
					foreach($this->aktifOnayKutulari as $v)	{
						$ba[]=$this->OnayBasligiOlustur($v);
					}
					
					$baslik=implode(" ve ",$ba);
				
					
					wc_add_notice(__($baslik.' alanlarını kabul etmeniz gerekmektedir.'), 'error' );		
					}
			}else{
				foreach($this->aktifOnayKutulari as $v)
					{
						if(!in_array($v,$_POST['HMYWSK_Degiskenler']['sozlesmeOnayi']))
							switch($v)
							{
								case 'sozlesme1Kabul':
									wc_add_notice(__('<a class="soztik HMYWP_Modal_Butonu" hmymodalsinif="Sozlesme1_modal"><b>'.
									$this->ModalVerileri['sozlesmebaslik1'].
									'</b> </a> alanını kabul etmeniz gerekmektedir.'), 'error' );
								break;
								
								case 'sozlesme2Kabul':
									wc_add_notice(__('<a class="soztik HMYWP_Modal_Butonu" hmymodalsinif="Sozlesme2_modal"><b>'.
									$this->ModalVerileri['sozlesmebaslik2'].
									'</b> </a> alanını kabul etmeniz gerekmektedir.'), 'error' );
								break;
								case 'sozlesme3Kabul':
									wc_add_notice(__('<a class="soztik HMYWP_Modal_Butonu" hmymodalsinif="Sozlesme3_modal"><b>'.
									$this->ModalVerileri['sozlesmebaslik3'].
									'</b> </a> alanını kabul etmeniz gerekmektedir.'), 'error' );
								break;
							}	
					}
				
			}
							
			
		}
		
		
		
		/*Müşteri Siparişi Onaylamadan Önce Müşteri Sözleşmesini Onaylaması İçin Çıkacak Kutular.*/
		function sozlesmeOnayKutulari($args){	
			
			if($this->hmykey!='HAPPYMAKER')
					return;
		
			
			
			if(!class_exists("HMYS_FormElemanlari"))
				require_once(HMY_WOSSE_EKLENTIDIZINI."class/formElemanlari.php");		 
					
			if(!isset($FRMELMN))
				 $FRMELMN=new HMYS_FormElemanlari();
			 
			 

			 //Eğer Sözleşmekutuları Tek Olacaksa 
			if($this->sozlesmeOnayKutusu=="tekOnay")
			{
				$baslik="";
				$ba=[];
				foreach($this->aktifOnayKutulari as $v)	{
					$ba[]=$this->OnayBasligiOlustur($v);
				}
				
				$baslik=implode(" ve ",$ba);
				
				$FRMELMN->ElemanTasarimlari(array(
						'type'=>"checkbox",
						'name'=>"sozlesmeOnayi",
						'etiket'=>"$baslik Bilgilendirmelerini Okudum ve Kabul Ettim",
						'class'=>"regular-text",
						'DegiskenAdi'=>'HMYWSK_Degiskenler',
						'id'=>"sozlesmeOnayi_id",
						'value'=>"musteriKabul"
					));
				
			}else{			 //Eğer Sözleşmekutuları Ayrı Olacaksa 

				if(isset($this->ModalVerileri['sozlesmedurum1']))
				{
					$ba =$this->OnayBasligiOlustur('sozlesme1Kabul');
					$FRMELMN->ElemanTasarimlari(array(
					'type'=>"checkbox",
					'name'=>"sozlesmeOnayi",
					'etiket'=>$ba." Bilgilendirmesini Okudum ve Kabul Ettim",
					'class'=>"regular-text",
					'DegiskenAdi'=>'HMYWSK_Degiskenler',
					'id'=>"sozlesmeOnayi1_id",
					'value'=>"sozlesme1Kabul"
					));
				}

				if(isset($this->ModalVerileri['sozlesmedurum2']))
				{
					$ba =$this->OnayBasligiOlustur('sozlesme2Kabul');
					$FRMELMN->ElemanTasarimlari(array(
					'type'=>"checkbox",
					'name'=>"sozlesmeOnayi",
					'etiket'=>$ba." Bilgilendirmesini Okudum ve Kabul Ettim",
					'class'=>"regular-text",
					'DegiskenAdi'=>'HMYWSK_Degiskenler',
					'id'=>"sozlesmeOnayi2_id",
					'value'=>"sozlesme2Kabul"
					));
				}

				if(isset($this->ModalVerileri['sozlesmedurum3']))
				{
					$ba =$this->OnayBasligiOlustur('sozlesme3Kabul');
					$FRMELMN->ElemanTasarimlari(array(
					'type'=>"checkbox",
					'name'=>"sozlesmeOnayi",
					'etiket'=>$ba." Bilgilendirmesini Okudum ve Kabul Ettim",
					'class'=>"regular-text",
					'DegiskenAdi'=>'HMYWSK_Degiskenler',
					'id'=>"sozlesmeOnayi3_id",
					'value'=>"sozlesme3Kabul"
					));
				}
				
			}
				
				
				
		}
		 
		 
		 function OnayBasligiOlustur($v){
			 $baslik="";
			switch($v)
			{
				case 'sozlesme1Kabul':
					$baslik='<a class="soztik HMYWP_Modal_Butonu" hmymodalsinif="Sozlesme1_modal"><b>'.
					$this->ModalVerileri['sozlesmebaslik1'].'</b> </a>';
				break;
							
				case 'sozlesme2Kabul':
					$baslik='<a class="soztik HMYWP_Modal_Butonu" hmymodalsinif="Sozlesme2_modal"><b>'.
					$this->ModalVerileri['sozlesmebaslik2'].'</b> </a>';
				break;
				
				case 'sozlesme3Kabul':
					$baslik='<a class="soztik HMYWP_Modal_Butonu" hmymodalsinif="Sozlesme3_modal"><b>'.
					$this->ModalVerileri['sozlesmebaslik3'].'</b> </a>';
				break;
			}
			return $baslik;
			 
		 }
		 
		 
		 
		 
		
		
		
		
		/*Sipariş Formundaki Bilgilere ya da Sipariş Bilgileirne Göre Verileri Oluşturacağız $tur="FormPost" ise POST verilerine Göre $tur="Siparis" ise sipariş bilgileerine göre*/
		 public function kullaniciveFaturaBilgileriOlustur($veri=null,$tur="FormPost"){
			 
			 
				if($tur=="FormPost"){
					
					$ve["billing_first_name"]=(isset($_POST["billing_first_name"]))?sanitize_text_field($_POST["billing_first_name"]):"";
					$ve["billing_last_name"]=(isset($_POST["billing_last_name"]))?sanitize_text_field($_POST["billing_last_name"]):"";
					$ve["billing_country"]=(isset($_POST["billing_country"]))?sanitize_text_field($_POST["billing_country"]):"";
					$ve["billing_company"]=(isset($_POST["billing_company"]))?sanitize_text_field($_POST["billing_company"]):"";
					$ve["billing_address_1"]=(isset($_POST["billing_address_1"]))?sanitize_text_field($_POST["billing_address_1"]):"";
					$ve["billing_address_2"]=(isset($_POST["billing_address_2"]))?sanitize_text_field($_POST["billing_address_2"]):"";
					$ve["billing_postcode"]=(isset($_POST["billing_postcode"]))?wp_kses_post($_POST["billing_postcode"]):"";
					$ve["billing_city"]=(isset($_POST["billing_city"]))?sanitize_text_field($_POST["billing_city"]):"";
					$ve["billing_state"]=(isset($_POST["billing_state"]))?sanitize_text_field($_POST["billing_state"]):"";
					$ve["billing_phone"]=(isset($_POST["billing_phone"]))?wp_kses_post($_POST["billing_phone"]):"";
					$ve["billing_email"]=(isset($_POST["billing_email"]))?sanitize_email($_POST["billing_email"]):"";
					if(isset($_POST["HMYWSK_Degiskenler"]["sozlesmeOnayi"]))
						$ve["HMYWSK_Degiskenler"]["sozlesmeOnayi"]=array_map("wp_kses_post",$_POST["HMYWSK_Degiskenler"]["sozlesmeOnayi"]);

					$ve["order_comments"]=(isset($_POST["order_comments"]))?sanitize_text_field($_POST["order_comments"]):"";
					$ve["payment_method"]=(isset($_POST["payment_method"]))?sanitize_text_field($_POST["payment_method"]):"";
					$ve["woocommerce-process-checkout-nonce"]=(isset($_POST["woocommerce-process-checkout-nonce"]))?wp_kses_post($_POST["woocommerce-process-checkout-nonce"]):"";
					$ve["_wp_http_referer"]=(isset($_POST["_wp_http_referer"]))?wp_kses_post($_POST["_wp_http_referer"]):"";
					$ve["payment_method_label"]=(isset($_POST["payment_method_label"]))?sanitize_text_field($_POST["payment_method_label"]):"";
					
					$this->musteri_fatura_bilgileri=$ve;
					
					
					//$this->musteri_fatura_bilgileri=$_POST;
				
				
					//Sepetteki Ürünlerle İlgili Kısakodların verilerini ekleyelim
					$this->urunIslemiBilgileriniAl();
					$this->musteri_fatura_bilgileri['cart_table']=$this->urunSepetTablosuOlustur();
					$cart_total_price = wc_prices_include_tax() ? WC()->cart->get_cart_contents_total() + WC()->cart->get_cart_contents_tax() : WC()->cart->get_cart_contents_total();
					
					$this->musteri_fatura_bilgileri['cart_total']= wc_price(WC()->cart->get_cart_contents_total());
					$this->musteri_fatura_bilgileri['total_payment']=wc_price($cart_total_price);

				}else if($tur=="Siparis")
				{
					
					
					foreach( $veri->get_items() as $k => $v){
						
						$this->sepet_verileri[$k]["uad"]=$v->get_name();
						$this->sepet_verileri[$k]["usatis_fiyati"]=wc_price($v->get_total()/$v->get_quantity());
						$this->sepet_verileri[$k]["quantity"]=$v->get_quantity();
						$this->sepet_verileri[$k]["line_subtotal"]=wc_price($v->get_total());

						$this->sepet_verileri[$k]["cart_total"]=$veri->get_subtotal_to_display();
						$this->sepet_verileri[$k]["total_payment"]=$veri->get_formatted_order_total();
						
					}
							
					
					
					
					
					$this->musteri_fatura_bilgileri["billing_first_name"]=$veri->get_billing_first_name();
					$this->musteri_fatura_bilgileri["billing_last_name"]=$veri->get_billing_last_name();
					$this->musteri_fatura_bilgileri["payment_method_label"]=$veri->get_payment_method_title();
					$this->musteri_fatura_bilgileri["billing_company"]=$veri->get_billing_company();
					$this->musteri_fatura_bilgileri["billing_country"]=$veri->get_billing_country();
					$this->musteri_fatura_bilgileri["billing_address_1"]=$veri->get_billing_address_1();
					$this->musteri_fatura_bilgileri["billing_address_2"]=$veri->get_billing_address_2();
					$this->musteri_fatura_bilgileri["billing_city"]=$veri->get_billing_city();
					$this->musteri_fatura_bilgileri["billing_state"]=$veri->get_billing_state();
					$this->musteri_fatura_bilgileri["billing_postcode"]=$veri->get_billing_postcode();
					$this->musteri_fatura_bilgileri["billing_phone"]=$veri->get_billing_phone();
					$this->musteri_fatura_bilgileri["billing_email"]=$veri->get_billing_email();
					$this->musteri_fatura_bilgileri["tc_kimlik"]="";
					$this->musteri_fatura_bilgileri["cart_table"]=$this->urunSepetTablosuOlustur();
					$this->musteri_fatura_bilgileri["cart_total"]=$veri->get_subtotal_to_display();
					$this->musteri_fatura_bilgileri["total_payment"]=$veri->get_formatted_order_total();
					
					$this->sipID=$veri->get_id();
				}
				
				
				
				
				

				//Tarih gün ay yıl gibi bilgileri oluşturalım 
				$this->musteri_fatura_bilgileri['hmy_yil']=	$this->TarihSaat("Y");
				$this->musteri_fatura_bilgileri['hmy_ay']=	$this->TarihSaat("m");
				$this->musteri_fatura_bilgileri['hmy_gun']=	$this->TarihSaat("d");
				$this->musteri_fatura_bilgileri['hmy_gunadi']=	$this->TarihSaat("l");
				$this->musteri_fatura_bilgileri['hmy_ayadi']=	$this->TarihSaat("F");
				$this->musteri_fatura_bilgileri['hmy_saat']=	$this->TarihSaat("H:i");
				$this->musteri_fatura_bilgileri['hmy_tarih']=	$this->TarihSaat("d/m/Y");
				
			
			

			//Şimdi bilgilerden yeni MODAL verilerini oluşturalım
			
			 if(!class_exists("HMYS_kisakodlar"))
				require_once(HMY_WOSSE_EKLENTIDIZINI."class/kisaKodlar.php");
			 

			if(!isset($KISAKOD))
				 $KISAKOD=new HMYS_kisakodlar();

			
			
			if(isset($this->ModalVerileri['sozlesmedurum1']))
				 $this->FilltreliModalVerileri['Sozlesme1_modal']=$KISAKOD->kisaKodFiltresi($this->musteri_fatura_bilgileri,$this->ModalVerileri['sozlesmemetni1']);
			 if(isset($this->ModalVerileri['sozlesmedurum2']))
				  $this->FilltreliModalVerileri['Sozlesme2_modal']=$KISAKOD->kisaKodFiltresi($this->musteri_fatura_bilgileri,$this->ModalVerileri['sozlesmemetni2']);
			 if(isset($this->ModalVerileri['sozlesmedurum3']))
				  $this->FilltreliModalVerileri['Sozlesme3_modal']=$KISAKOD->kisaKodFiltresi($this->musteri_fatura_bilgileri,$this->ModalVerileri['sozlesmemetni3']);
			 
			 
			 
			 
			/* $a=( $FilltreliModalVerileri['Sozlesme1_modal']);
						wc_add_notice( __( 'gigiyok a <strong>"oooooo '.$a.'"</strong>.', 'woocommerce' ), 'error' );*/
		 }
		 
		 
		 
		 
		 
       		
				
				
		 /*Bu fonksiyonla sepete eklenen ürünlistesi fiyatı gibi bilgiler varsa dizi şeklinde alalım*/
		 function urunIslemiBilgileriniAl(){
			    $sepet=(WC()->cart->get_cart());			 
				 $cart_total_price = wc_prices_include_tax() ? WC()->cart->get_cart_contents_total() + WC()->cart->get_cart_contents_tax() : WC()->cart->get_cart_contents_total();
				foreach( $sepet as $k => $v){
						
						$this->sepet_verileri[$k]["uad"]=$v['data']->get_name();
						$this->sepet_verileri[$k]["usatis_fiyati"]=wc_price($v['data']->get_sale_price());
						$this->sepet_verileri[$k]["quantity"]=$v["quantity"];
						$this->sepet_verileri[$k]["line_subtotal"]=wc_price($v['line_subtotal']);
						$this->sepet_verileri[$k]["cart_total"]=wc_price($cart_total_price);
						$this->sepet_verileri[$k]["total_payment"]=wc_price(WC()->cart->total);
						
					}
			
		 }
		 
		 
		 function urunSepetTablosuOlustur(){
			 
			 
			 if(!is_array($this->sepet_verileri))
				 return false;
			 
			 
			 $cart_total=0;
			 $total_payment=0;
			 
			 $mt="<table >
						<tr>
							<th>Ürün Adı</th>
							<th>Birim Fiyatı</th>
							<th>Ürün Adeti</th>
							<th>Toplam</th>
						</tr>";
			 foreach($this->sepet_verileri as $veri)
			 {
				 $mt.="<tr>
						<td>".$veri['uad']."</td>
						<td>".($veri['usatis_fiyati'])."</td>
						<td>".$veri['quantity']."</td>
						<td>".($veri['line_subtotal'])."</td>
					  </tr>";
					  
					  if(isset($veri['cart_total']))
						$cart_total=($veri['cart_total']);
					  if(isset($veri['total_payment']))
						$total_payment=($veri['total_payment']);
				 
			 }
			
			 $mt.="	<tr>
					<td colspan='2'><b>Ara Toplam</b></td>
					<td  colspan='2'  style='text-align:right;'><b>".$cart_total."</b></td>
					</tr>
					<tr>
					<td colspan='2'><b>Toplam</b></td>
					<td  colspan='2' style='text-align:right;'><b>".$total_payment."</b></td>
					</tr>
			 ";
			 
			 
			 return ($mt."</table>");
			 

		 }
		 
		 
		
		
		function TarihSaat($format){
			
            // return strftime($format, time());
			
			return date_i18n( $format);
		}
		
		

		
		function HMYAJAXfonksiyonum(){
			
			
			/*FİLTRELERİ OLUŞTURUP MODAL içerisini AJAX ile Güncelleyelim */
			//kullaniciveFaturaBilgileriOlustur($veri=null,$tur="FormPost"){
				
					$ve["billing_first_name"]=(isset($_POST["billing_first_name"]))?sanitize_text_field($_POST["billing_first_name"]):"";
					$ve["billing_last_name"]=(isset($_POST["billing_last_name"]))?sanitize_text_field($_POST["billing_last_name"]):"";
					$ve["billing_country"]=(isset($_POST["billing_country"]))?sanitize_text_field($_POST["billing_country"]):"";
					$ve["billing_company"]=(isset($_POST["billing_company"]))?sanitize_text_field($_POST["billing_company"]):"";
					$ve["billing_address_1"]=(isset($_POST["billing_address_1"]))?sanitize_text_field($_POST["billing_address_1"]):"";
					$ve["billing_address_2"]=(isset($_POST["billing_address_2"]))?sanitize_text_field($_POST["billing_address_2"]):"";
					$ve["billing_postcode"]=(isset($_POST["billing_postcode"]))?wp_kses_post($_POST["billing_postcode"]):"";
					$ve["billing_city"]=(isset($_POST["billing_city"]))?sanitize_text_field($_POST["billing_city"]):"";
					$ve["billing_state"]=(isset($_POST["billing_state"]))?sanitize_text_field($_POST["billing_state"]):"";
					$ve["billing_phone"]=(isset($_POST["billing_phone"]))?wp_kses_post($_POST["billing_phone"]):"";
					$ve["billing_email"]=(isset($_POST["billing_email"]))?sanitize_email($_POST["billing_email"]):"";
					if(isset($_POST["HMYWSK_Degiskenler"]["sozlesmeOnayi"]))
						$ve["HMYWSK_Degiskenler"]["sozlesmeOnayi"]=array_map("wp_kses_post",$_POST["HMYWSK_Degiskenler"]["sozlesmeOnayi"]);

					$ve["order_comments"]=(isset($_POST["order_comments"]))?sanitize_text_field($_POST["order_comments"]):"";
					$ve["payment_method"]=(isset($_POST["payment_method"]))?sanitize_text_field($_POST["payment_method"]):"";
					$ve["woocommerce-process-checkout-nonce"]=(isset($_POST["woocommerce-process-checkout-nonce"]))?wp_kses_post($_POST["woocommerce-process-checkout-nonce"]):"";
					$ve["_wp_http_referer"]=(isset($_POST["_wp_http_referer"]))?wp_kses_post($_POST["_wp_http_referer"]):"";
					$ve["payment_method_label"]=(isset($_POST["payment_method_label"]))?sanitize_text_field($_POST["payment_method_label"]):"";
			$this->kullaniciveFaturaBilgileriOlustur(($ve));
			//$this->kullaniciveFaturaBilgileriOlustur(($_POST));
			
			$a=array_map('wpautop',$this->FilltreliModalVerileri);
			//$a=array_map([$this,'TabloTagiTemizle'],$a);
			
			
			echo	json_encode($a);
			
			wp_die();
			
		}

		/*Sözleşme Linkini indireceğiz*/
		function HMYAJAXsozlesmeIndir(){
			
			$ism="";
			$mtn=$_POST["linkVerisi"];
			/*CHAR code olarak şifreli gelen ismi çözümleyelim Her koda 1000 eklemiştik*/
			for($i=0;$i<strlen($mtn);$i+=4)
				$ism.= chr((substr($mtn,$i,4)-1000));
			
			$urll= 'sozlesmeler/'.$this->TarihSaat("d_m_Y").'/'.$ism.'.pdf';
			
			echo esc_url( plugins_url( $urll,dirname(__FILE__) ) );
			
			

			wp_die();
			
		}
		
		
		/*WP olur olmaz yerlere <p></p> Ekliyor çıldırttı beni gelen metindeki Tabloyu tespit edip Tablodaki tablo tagleri harici tüm tagleri temizleyeceğim
			@$metinde gelen verilerde
		*/
				
		function TabloTagiTemizle($metin){
					
					
					$dizi=[];
					$tdizi=[];
					$veri=$metin;
					
					//önce tablo taglerinin pozisyonlarını bulup metinden ayıralım
					$bas=strpos('<table',$metin);
					$son=strpos('</table>',$metin);
					
					
								
					$dizi=[];//metinlerin dizisi
					$tdizi=[];//bulunan tabloların dizisi

					do{
						$bas=stripos($veri,'<table');
						$son=stripos($veri,'</table>');
							 
						$ilkbolum=substr($veri,0,($bas));//metnin ilk bolumu
						$tb=substr($veri,$bas,($son-$bas+8));//metnin tablo bolumu
						$sonBolum=substr($veri,($son+8));//metnin kalan bolumu

					  
					  $dizi[]=$ilkbolum;
					  $tdizi[]=$tb;
					  $veri=$sonBolum;
					 }while(stripos($veri,'<table')>=1);
					
					$dizi[]=$veri;//son kalan veriyide ekledik
					
					
					
					
					///Şimdi tablo içindeki paragraf tagi verileri silelim
					$tdizi=str_ireplace('</p>', '', str_ireplace('<p>', '', $tdizi));
					
					$temizMetin="";
					///verileri tekrar birleştirelim
					for($i=0;$i<(count($dizi));$i++)
						if(isset($tdizi[$i]))
							$temizMetin.=$dizi[$i].$tdizi[$i];
						else
							$temizMetin.=$dizi[$i];
						
					$temizMetin.=(count($dizi)<(count($tdizi)))?$tdizi[-1]:"";
					
					return $temizMetin;
				}
		
		 
		 
		 /*istenenler dizisinde gelen tüm verileri optionlardan alıp gönderecek*/
		 function sozlesmeVerisiAl($istenenler){
			 $veri=[];
			 if(is_array($istenenler))
			 {
				/*$k değişkenlerin olduğu dizi adı $v ise onun indisi*/
				foreach($istenenler as $i)
					foreach($i as $k=>$v)
					{
						if(isset(get_option($k)[$v]))
							$veri[$v]=get_option($k)[$v];
					}
			 }else
				 if(isset($istenenler)){
					 $veri=get_option($istenenler);
				 
				 }
			return $veri;
			 
		 }
		 
		 
		  
		 
		function SiparisSonrasiIslemler( $order_id ){
			
			
			if($this->hmykey!='HAPPYMAKER')
					return;

		   $order = new WC_Order( $order_id );
			//kullaniciveFaturaBilgileriOlustur($veri=null,$tur="FormPost"){
		   $this->kullaniciveFaturaBilgileriOlustur($order,"Siparis");  
		  
			require_once(HMY_WOSSE_EKLENTIDIZINI.'pdfCevirici.php');



			//ismi char code olarak şifreleyelim
			$mtn ="";
			for($i=0;$i<strlen($isim); $i++){
			 $mtn.=(1000+ord($isim[$i]))."";
			 }
			 
			 
			echo "<a href='#' hmylinkverisi='".$mtn."' id='sozlesmeIndirLink'>Müsteri Sözleşmesi ve Sipariş Bilgilerini İndirmek İçin Tıklayın</a>";
		}
		 
		 
		 function __destruct(){}
		 
	 }
	 
	 
	 
 }
 