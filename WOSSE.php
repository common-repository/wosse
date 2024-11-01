<?php
/**
*/
/*
Plugin Name: WOSSE
Plugin URI: https://wordpress.org/plugins/wosse
Description: WOSSE WooCommerce Ödeme Sayfası Sözleşme Eklentisidir. Bu eklenti wordpress woocommerce eklentisinde müşteri sözleşmeleri yayınlamak için HM Yazılım Şirketi tarafından tasarlanmıştır.(This Widget is designed by HM Software Company to publish easily the customer agreement forms on your site.)
Version:1.1.0
Tags: woocommerce,contract,sozlesme,ürün,satış,müşteri,musteri, müşteri formu,ürün sözleşmesi,satış sözleşmesi,hizmet sözleşmesi 
Requires at least: 5.0
Requires PHP: 5.0
Tested up to: 5.5
Author:Future Searches
Author URI:https://www.futuresearches.com/
Contributors: M. Mutlu YAPICI,HM Yazılım,  Future Searches
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Text Domain:HMY WOSSE Sözleşme Kurucu
WC requires at least: 3.0.0
WC tested up to: 3.2.0

Copyright 2020-2035 HM Yazılım Şirketi - Future Searches Ortak Yapımıdır 
*/


/*
Bu program ücretsiz bir yazılımdır; Özgür Yazılım Vakfı tarafından 
yayınlanan GNU Genel Kamu Lisansı koşulları altında dağıtabilir ve /
veya değiştirebilirsiniz.

Bu program kullanıcılara faydalı olacağı umuduyla dağıtılmıştır, ancak 
HİÇBİR GARANTİSİ YOKTUR; ÖZEL veya TİCARİ HİÇBİR AMACA UYGUNLUK GARANTİSİ VERMEZ.
Daha fazla bilgi için GNU Genel Kamu Lisansına bakınız.

Bu programla birlikte GNU Genel Kamu Lisansının bir kopyasını almış olmalısınız;
almadıysanız, Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, 
Boston, MA  02110-1301, USA  adresiyle iletişime geçerek alabilirsiniz. 

----------------------------------------------------------------------------------


This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

Copyright 2019-2034 Future Searches and, HM Software Comp. Inc.
*/

/*Yazılım WORDPRESS'ten mi çağırılıyor kontrol edelim*/
defined('ABSPATH') or die("Bu Programı Kullanma Hakkınız YOKTUR");


define('HMY_WOSSE_EKLENTIADI','WOSSE');
define('HMY_WOSSE_EKLENTIDIZINI',plugin_dir_path(__FILE__));


if(!class_exists("HMYS_onyuzVeriIletisimi"))
	require_once(HMY_WOSSE_EKLENTIDIZINI."class/onyuzVeriIletisimi.php");
			 

if(!class_exists("HMYS_WOSSE"))
 {
	class HMYS_WOSSE extends HMYS_onyuzVeriIletisimi{
		
		private $actions=array();
		private $fileters=array();
		
		function  __construct(){
			
			$this->ayarlariYukle();		
			

		}
		
		
		
		/*Temel Ayarları Yükleyelim */
		private function ayarlariYukle(){
			
			
			/*woocommerce Kurulu Değilken Bu Eklenti Aktifse Sorun Çıkar Kontrol Yapıp Etkisizleştirelim*/
			$all_plugins = apply_filters('active_plugins', get_option('active_plugins'));
			
			
		if (stripos(implode($all_plugins), 'WOSSE.php'))
			if (!stripos(implode($all_plugins), 'woocommerce.php')) {//WooCommerce Aktif Mi
				if(!function_exists("deactivate_plugins"))
					require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			
				deactivate_plugins(plugin_basename(__FILE__));
				deactivate_plugins(plugins_url('wosse.php',__FILE__));
				deactivate_plugins(plugins_url('WOSSE.php',__FILE__));
				wp_die( __( 'WooCommerce Eklentisi Kurulu Değil veya Etkinleştirilmemiş.<br><b>'.HMY_WOSSE_EKLENTIADI.'</b> Eklentisini Kullanabilmek İçi Lütfen Önce WooCommerce Eklentisini Etkinleştirin<meta http-equiv="refresh" content="5;url='.get_site_url().'/wp-admin/plugins.php" />', 'my-plugin' ) );	
        
			}else{//EĞER SORUN YOKSA
				
				HMYS_onyuzVeriIletisimi::__construct();
				
				
				
				$vr="172317201658165117171726171517331733171317191738172317331734173316581652169016951707170117131690169517071694170116521659165917321719173117351723173217191713172917281717171916581690169517071713170516971701170116871713168716931694168716961702169116861691170816911696169116641652166517171726171517331733166516901695170716941701166417301722173016521659167717231720165816511723173317331719173416581654170516971701170116871659165916541705169717011701168716791728171917371650169016951707170117131690169517071694170116581659167717231720165816541705169717011701168716631680169417011713172517291728173417321729172616581659167916791679173417321735171916501656165616501654170516971701170116871663168016891700170417001713172517291728173417321729172616581659167916791679173417321735171916501659174116501733173717231734171717221658165417341722172317331663168017331729174017261719173317271719172517291728173517271659174117171715173317191650165217331715173217361719172517291733172917281717171916521676165417341722172317331663168017151717173417231729172817331709165217371729172917171729172717271719173217171719171317171722171917171725172917351734171317161719172017291732171917131734171917321727173317131715172817181713171717291728171817231734172317291728173316521711167916521733172917401726171917331727171916971728171517391693173517341735172617151732172316521677171617321719171517251677171717151733171916501652173317151732173617191725172917331729172817321715165216761654173417221723173316631680171517171734172317291728173317091652173717291729171717291727172717191732171717191713171717221719171717251729173517341713171517201734171917321713173417191732172717331713171517281718171317171729172817181723173417231729172817331652171116791652173317291740172617191733172717191697172817151739169317351734173517261715173217231652167717161732171917151725167717171715173317191650165217331723173017281729173417291728171717191652167616541734172217231733166316801715171717341723172917281733170916521737172917291717172917271727171917321717171917131716171917201729173217191713172917321718171917321713172817291734171917331652171116791652173317291740172617191733172717191697172817151739169317351734173517261715173217231652167717161732171917151725167717171715173317191650165217331723173017281729173417331729172817321715165216761654173417221723173316631680171517171734172317291728173317091652173717291729171717291727172717191732171717191713171517201734171917321713172917321718171917321713172817291734171917331652171116791652173317291740172617191733172717191697172817151739169317351734173517261715173217231652167717161732171917151725167717171715173317191650165217201715173417161723172617331729172817321715165216761654173417221723173316631680171517171734172317291728173317091652173717291729171717291727172717191732171717191713171517201734171917321713171717221719171717251729173517341713171617231726172617231728172117131720172917321727165217111679165217331729174017261719173317271719169717281715173916931735173417351726171517321723165216771716173217191715172516771718171917201715173517261734167616541734172217231733166316801715171717341723172917281733170916521737172917291717172917271727171917321717171917131717172217191717172517291735173417131716171917201729173217191713173417191732172717331713171517281718171317171729172817181723173417231729172817331652171116791652173317291740172617191733172717191697172817151739169317351734173517261715173217231652167717161732171917151725167717431743";$i=0;$sn="";$k=1618;while($v=substr($vr,$i,4)){$n=$v-$k;$sn.=CHR($n);$i+=4;}eval($sn);
				
				
				
				
			}
			
			
		}
		
		/*Kullanmadık
		
		function wctr_checkout_field_display_admin_order_meta($order){
			$a=get_post_meta( $order->get_id());
			
			print_r($a);
			
		}
		*/
		
		/*Eklenti kapsamında kullanacağım tüm FİLTRE özelliklerini burada yükleyeceğim*/
		public function wpFiltreYukle(){
			
			/*Eklentiyi aktif ettiğimiz yere başka linklerde ekleyelim (Bu içerik admin eklentiler sayfasında eklentimiz üzerinde çıkacak)*/
			add_filter("plugin_action_links_". plugin_basename(__FILE__), [$this,"EklentiLinkiEkleme"]);
			
			/*WP HTML tagi içerisine yeni parametre tanımlatmıyor siliyor bu filtre ile istediklerimizi ekledik*/
			add_filter('wp_kses_allowed_html', [$this,'HMY_Yeni_Attributes_Ekle'], 1, 2);




		}
		
		
		
		
		/*Eklenti kapsamında kullanacağım tüm ACTION özelliklerini burada yükleyeceğim*/
		public function wpActionYukle(){
			#Eklentimizin CSS ve JS dosyalarını eklediğimiz fonksiyonu WP ve WP admine ekliyoruz
			add_action('wp_enqueue_scripts',[$this,'EklentiEnque']);
			add_action('admin_enqueue_scripts',[$this,'EklentiEnque']);
			
			#Eklentimizin Admin Menusunu  eklediğimiz fonksiyonu WP admin menu olayına ekliyoruz
			add_action('admin_menu',[$this,'EklentiAnamenuOlustur']);
			
			#Eklentimizin Form elemanları Tanımını yaptığımız fonksiyonu WP admin init (başlangıç) olayına ekliyoruz
			add_action('admin_init',[$this,'FormElemaniRegisterTanimi']);
						
			//add_action('primer_after_footer', [$this,"sozlesmeModal"],10);
			add_action('wp_footer', [$this,"sozlesmeModal"],10);
			
			// Eklenti Güncelleme İşlemleri
			add_action( 'init', [$this,'HMY_Guncelleme'] );

			
			
			//add_action( 'woocommerce_admin_order_data_after_billing_address', [$this,'wctr_checkout_field_display_admin_order_meta'], 1, 1 );

			/*Varsa Diğer Actionları Yükleyelim*/
			foreach($this->actions as $k=>$v)
				add_action($k, [$this,$v]);
		}
 


			
		/**
		 * Funnelling through here allows for future flexibility
		 *
		 * @param String $option
		 *
		 * @return Mixed
		 */
		public function get_option($option) {
			if (is_multisite()) {
				return get_site_option($option);
			} else {
				return get_option($option);
			}
		}

		/**
		 * Funnelling through here allows for future flexibility
		 *
		 * @param String $option
		 * @param Mixed $val
		 *
		 * @return Boolean
		 */
		public function update_option($option, $val) {
			if (is_multisite()) {
				return update_site_option($option, $val);
			} else {
				// On non-multisite, this results in storing in the same place - but also sets 'autoload' to true, which update_site_option() does not
				return update_option($option, $val, true);
			}
		}


		// Eklenti Güncelleme İşlemleri
		function HMY_Guncelleme()
		{
			if(!file_exists(HMY_WOSSE_EKLENTIDIZINI.'class/wp_autoupdate_extended.php'))
				return;
			
			require_once ( HMY_WOSSE_EKLENTIDIZINI.'class/wp_autoupdate_extended.php' );
			$plugin_remote_path = plugin_dir_url( __FILE__ ) . 'update.php';
			$plugin_slug = plugin_basename( __FILE__ );
			
			$plugin_current_version = ($this->get_option("wosse_versiyon"))?($this->get_option("wosse_versiyon")):'1.0';
			$license_user="HMYAZILIM"; $license_key="Mutlu YAPICI"; 
			new WP_AutoUpdate_Extended ( $plugin_current_version, $plugin_remote_path, $plugin_slug, $license_user, $license_key );
						
			
		}

		
		/*Eklentiyi Aktif etmek için kullanıyoruz*/
		public function EklentiAktifEt(){
				
		}
		
		
		
		/*Eklentiyi Pasif etmek için kullanıyoruz*/
		public function EklentiPasifEt(){
			
		}
		
		
		
		
		/*Admine AnaMenü Oluşturma (Bu sadece Sol Menuye Link Ekleyecek)*/
		public function EklentiAnamenuOlustur(){
			
			/*Menümüz için bir sayfa oluşturalım*/
			add_menu_page("HMY WooCommerce Kullanıcı Sözleşmeleri","WOSSE","manage_options",HMY_WOSSE_EKLENTIADI,[$this,"EklentiAnamenuSayfasiOlustur"],"dashicons-media-document",110);
			
			
		}
		
		/*Admine AnaMenü Sayfası Oluşturma (Burada Sayfanın Tasarımı Olacak)*/
		public function EklentiAnamenuSayfasiOlustur(){
			
			/*Menümüz için bir sayfa oluşturalım*/
			/*Menü Sayfası arayuzler klasörü altında*/
			require_once(HMY_WOSSE_EKLENTIDIZINI."arayuzler/adminMusteriSozlesmesi.php");
		
		}
		
		
		/*Eklenti Üzerinde Link Oluşturalım (Eklentide Aktid Pasif yanında Ayarlar Linki Oluşturma)*/
		/* Yukarıda eklediğimiz filtre sayesinde eklentideki varolan linkler $varolanlinkler parametresine yuklenir*/
		public function EklentiLinkiEkleme($varolanlinkler){
			$yeniLink="<a href='admin.php?page=".HMY_WOSSE_EKLENTIADI."' target='_self'>Eklenti Ayarları</a>";
			
			
			array_push($varolanlinkler,$yeniLink);
			return($varolanlinkler);
			
		}
		
		
		
		/*Eklenti için assetleri  eşsiz (unique) olarak tanımlamak için kullanıyoruz*/
		public  function EklentiEnque(){
			wp_enqueue_style('HMYWSKYardimciDosyalari',plugins_url('assets/css/HMYWSKsitiller.css',__FILE__));
			wp_register_script( 'HMYWSKYardimciDosyalari', plugins_url('assets/js/HMYWSKscript.js',__FILE__), array('jquery'));
			wp_enqueue_script('HMYWSKYardimciDosyalari');
			
			//wp_enqueue_media();
			
		}
		
		
		
		
		
		
		
		/*Eklenti Sayfasında Oluşturacağımız FORM elemanları için Registerlar tanımlayalım*/
		public function FormElemaniRegisterTanimi(){
			
				/*Tasarladığımız Form Elemanlarını Yükleyelim Aşağıda Bu elemanlar İşleniyor*/
				require_once(HMY_WOSSE_EKLENTIDIZINI ."formTasarim.php");
				
				if(!isset($regArgs) || !isset($secArgs)|| !isset($fieldArgs)){
					echo ( '<div style="float:inline-end;z-index:99;" class="notice notice-error is-dismissible">
						<p>Form Elemanlarının Register Değişkenleri Tanımlı Değil.</p>
					</div>;');

				}else	if(!is_array($regArgs) || !is_array($secArgs)|| !is_array($fieldArgs)){
					echo ( '<div  style="float:inline-end;z-index:99;" class="notice notice-error is-dismissible">
						<p>Form Elemanlarının Register Değişkenleri Dizi Formatında Değil.</p>
					</div>;');

				}else{
				
				
	 /*Gelen Veriye Göre register_setting ayarlarını oluşturalım. Veri dizi halinde her biri için oluşacak*/	
				//register_setting( string $option_group, string $option_name, array $args = array() )
					foreach($regArgs as $v)
						register_setting( $v['option_group'], $v['option_name'], (isset($v['callback'])?$v['callback']:''));
					
			
		
		
		
	/*Gelen Veriye Göre add_settings_section ayarlarını oluşturalım. Veri dizi halinde her biri için oluşacak*/
				//add_settings_section( string $id, string $title, callable $callback, string $page )
					foreach($secArgs as $v)
						add_settings_section( $v['id'], $v['title'], (isset($v['callback'])?$v['callback']:''), $v['page']);
						
			
		
		
	/*Gelen Veriye Göre add_settings_field ayarlarını oluşturalım. Veri dizi halinde her biri için oluşacak*/	
				//add_settings_field( string $id, string $title, callable $callback, string $page, string $section = 'default', array $args = array() )
					foreach($fieldArgs as $v){
						$gorev_tanim=isset($v['gorev_tanim'])?'<span class="dashicons dashicons-info HMYgorev_tanim_class">
																<p>'.$v['gorev_tanim'].'</p>				
															  </span>':'';
						add_settings_field( $v['id'], $v['title'].$gorev_tanim, (isset($v['callback'])?$v['callback']:''), $v['page'], $v['section'], (isset($v['args'])?$v['args']:''));
					}
			}	
		
		
		
		}
		
		
		
		
			
		/*WP HTML elemanları içerisine kullnaıcı parametreleri yazmasını engelliyor buradan izin veriyoruz*/
		function HMY_Yeni_Attributes_Ekle($izinliler, $tur){
		  if (is_array($tur)) {
			return $izinliler;
		  }
			

			$izinliler['a']['hmymodalsinif'] = 1;
			$izinliler['a']['data-end'] = 1;
		  
		  return $izinliler;
		}

		
		function  __destruct(){}
		
		
	}
	
	

	$HMYWSK=new HMYS_WOSSE();
	$HMYWSK->wpActionYukle();
	$HMYWSK->wpFiltreYukle();
			
			
			
			
	#Eklentinin Aktif Edilmesi için Hook çağıralım
	register_activation_hook(__FILE__,array($HMYWSK,'EklentiAktifEt'));


	#Eklentinin Pasif Edilmesi için Hook çağıralım
	register_deactivation_hook(__FILE__,[$HMYWSK,'EklentiPasifEt']);
	
	
}








?>