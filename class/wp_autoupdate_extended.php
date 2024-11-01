<?php


//============================================================+
// Dosya Adı   	: wp_autoupdate_extended.php
// Tarih       	: 25-09-2020
//
// Tanım		: WP Eklentileri İçin Güncelleme İşlemlerini 
// 				  WP_AutoUpdate_Extended Sınıfından oluşturuyoruz
//
// Geliştiren	: M. Mutlu YAPICI
//
//============================================================+




/****************************************************************************************************
*																									*
*	BU SINIF İLE WP EKELENTİLERİNDE KULLANACAĞIMIZ GÜNCELLEME FONKSİYONLARINI TANIMLAYACAĞIZ		*
*	AYRICA EKLENTİ GÜNCELLEME BAĞLANTISINDAKİ DETAY GÖRÜNTÜLE VERİLERİDE  OLUŞTURULACAK				*
*																									*
*****************************************************************************************************/


/*Yazılım WORDPRESS'ten mi çağırılıyor kontrol edelim*/
defined('ABSPATH') or die("Bu Programı Kullanma Hakkınız YOKTUR");


class WP_AutoUpdate_Extended
{
	/**
	 * The plugin current version
	 * @var string
	 */
	private $current_version;

	/**
	 * The plugin new version
	 * @var string
	 */
	private $new_version;

	/**
	 * The plugin remote update path
	 * @var string
	 */
	private $update_path;

	/**
	 * Plugin Slug (plugin_directory/plugin_file.php)
	 * @var string
	 */
	private $plugin_slug;

	/**
	 * Plugin name (plugin_file)
	 * @var string
	 */
	private $slug;

	/**
	 * License User
	 * @var string
	 */
	private $license_user;

	/**
	 * License Key 
	 * @var string
	 */
	private $license_key;

	/**
	 * Initialize a new instance of the WordPress Auto-Update class
	 * @param string $current_version
	 * @param string $update_path
	 * @param string $plugin_slug
	 */
	public function __construct( $current_version, $update_path, $plugin_slug, $license_user = '', $license_key = '' )
	{
		// Set the class public variables
		$this->current_version = $current_version;
		$this->update_path = $update_path;

		// Set the License
		$this->license_user = $license_user;
		$this->license_key = $license_key;

		// Set the Plugin Slug	
		$this->plugin_slug = $plugin_slug;
		list ($t1, $t2) = explode( '/', $plugin_slug );
		$this->slug = str_replace( '.php', '', $t2 );		

		// define the alternative API for updating checking
		add_filter( 'site_transient_update_plugins', array( &$this, 'check_update' ) );
		#add_filter( 'pre_set_site_transient_update_plugins', array( &$this, 'check_update' ) );

		// Define the alternative response for information checking
		add_filter( 'plugins_api', array( &$this, 'HMY_plugin_info' ), 10, 3 );
		
		//Güncelleme Bittikten Sonra
		add_action( 'upgrader_process_complete',array( &$this, 'after_update'), 10, 2 );
		
					

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
	
	
	/*EKLENTİ DETAYLARINI GÖRÜNTÜLEDİĞİMİZDE OLUŞAN SAYFADAKİ BİLGİLER
	 * $res empty at this step
	 * $action 'plugin_information'
	 * $args stdClass Object ( [slug] => woocommerce [is_ssl] => [fields] => Array ( [banners] => 1 [reviews] => 1 [downloaded] => [active_installs] => 1 ) [per_page] => 24 [locale] => en_US )
	 */
	function HMY_plugin_info( $res, $action, $args ){
	/* if($this->get_option("wosse_versiyon"))
			//print_r($this->get_option("wosse_versiyon"));
	 else
		//echo"<h1> yooook</h1>";
	*/
		// do nothing if this is not about getting plugin information
		if( 'plugin_information' !== $action ) {
			return false;
		}
	 
		// do nothing if it is not our plugin
		if( $this->slug !== $args->slug ) {
			return false;
		}
	 
		// trying to get from cache first
		if( false == $remote = get_transient($this->plugin_slug ) ) {	 
			$remote=$this->HMY_JSON_Bilgi(); 
		}
	
	 
		if( ! is_wp_error( $remote ) && isset( $remote['response']['code'] ) && $remote['response']['code'] == 200 && ! empty( $remote['body'] ) ) {
	 
			$remote = json_decode( $remote['body'] );
			$res = new stdClass();
	 
			$res->name = $remote->name;//jsON dosyasındaki nameden alıyor ve BANNER üzerinde Eklenti Adı Çıkıyor
			$res->slug = $remote->slug;
			$res->version = $remote->new_version;//Sürüm: diye sürüm bilgisi yazıyor
			$res->tested = $remote->tested;//Uyumlu olduğu en yüksek sürüm: diye test edilen sürüm yazıyor
			$res->requires = $remote->requires;//Gerekli WordPress sürümü: .. ya da daha yüksek  yazıyor
			$res->author = $remote->author;  //Yazar: M.Mutlu YAPICI  şeklinde yazıyor
			$res->author_profile = $remote->author_homepage;
			$res->homepage = $remote->homepage;//Eklenti Sayfası
			$res->download_link = $remote->download_url;
			$res->trunk = $remote->download_url;
			if(isset($remote->active_installs))
				$res->active_installs = $remote->active_installs;//Aktif indirilmiş eklenti bilgisi
			$res->requires_php = $remote->requires_php;//Gerekli PHP sürümü: 5.3 ya da daha yüksek şeklinde bilgi
			$res->last_updated = $remote->last_updated;//Son güncellenme: 1 sene önce şeklinde bilgi
			if(isset($remote->rating))
				$res->rating = $remote->rating;//1 den 100 e kadar oy oranı rayting şeklinde bilgi
			if(isset($remote->ratings))
				$res->ratings = $remote->ratings;//5 yıldız şeklimde oy oranı DİZİ GELİYOR array(5 => 2104,4 => 116,3 => 64, 2 => 57, 1 => 175)
			if(isset($remote->num_ratings))
				$res->num_ratings = $remote->num_ratings;//Yıldız ve toplam oy oranı şeklinde bilgi
			$res->sections = array(//SECTİON görüntülenecek sayfanın her bir sekmesini oluşturuyor
				'description' => $remote->sections->description,//Eklenti Açıklama Sekmesi
				'installation' => $remote->sections->installation,//Eklenti Kurumlum Sekmesi
				'changelog' => $remote->sections->changelog //Eklenti Değişiklik Listesi Sekmesi
				
				///DAHA FAZLA SEKME İSTERSEK BURAYA EKLEYEBİLİRİZ. İÇERİĞİNİ JSON İÇİNDEN GÖNDEREBİLİRİZ.
			);
	 
			// Eğer Ekran Görüntüleri Sekmesi Kullanmak İstersek, use the following HTML format for its content:
			// <ol><li><a href="IMG_URL" target="_blank"><img src="IMG_URL" alt="CAPTION" /></a><p>CAPTION</p></li></ol>
			if( !empty( $remote->sections->screenshots ) ) {
				$res->sections['screenshots'] = $remote->sections->screenshots;
			}
			
			//SSS sekmesi varsa ekleyelim
			if( !empty( $remote->sections->faq ) ) {
				$res->sections['faq'] = $remote->sections->faq;
			}
			
			
			//İNCELEMELER sekmesi varsa ekleyelim
			if( !empty( $remote->sections->reviews ) ) {
				$res->sections['reviews'] = $remote->sections->reviews;
			}
			
			///DETAY SAYFASINDAKİ BANNER RESMİ
			if(!empty( $remote->banners ) ) {
					
				$res->banners = array(///DETAY SAYFASINDAKİ BANNER RESMİ
					'low' => $remote->banners->low,
					'high' => $remote->banners->high
				);
			
			}
			
	 
			
	 
			
			return $res;
			
				
		}
	 
		return false;
	 
	}
	
	
	
	
	
	
	

	/**
	 * Add our self-hosted autoupdate plugin to the filter transient
	 *
	 * @param $transient
	 * @return object $ transient
	 */
	public function check_update( $transient )
	{
		
		if ( empty( $transient->checked ) ) {
			return $transient;
		}

		// trying to get from cache first, to disable cache comment 10,20,21,22,24
		if( false == $remote = get_transient( $this->plugin_slug ) ) {
				$remote=$this->HMY_JSON_Bilgi(); 
	 
		}
 
		if( $remote ) {				

	 
			$remote_version = json_decode( $remote['body'] );
			
			// Get the remote version
			//$remote_version = $this->getRemote('version');

			// If a newer version is available, add the update
			if ( version_compare( $this->current_version, $remote_version->new_version, '<' ) ) {
				$obj = new stdClass();
				$obj->slug = $this->slug;
				$obj->new_version = $remote_version->new_version;
				$obj->url = $remote_version->homepage;
				$obj->plugin = $this->plugin_slug;
				$obj->package = $remote_version->download_url;
				$obj->tested = $remote_version->tested;
				$transient->response[$this->plugin_slug] = $obj;
				
				$this->new_version=$remote_version->new_version;/// Yeni Versiyonu Alalım
			}
		}
		
		return $transient;
		
	}
	
	
	private function HMY_JSON_Bilgi(){
		
		$vr="16541732171917271729173417191679172017151726173317191677165417321719172717291734171916791737173017131732171917271729173417191713172117191734165816521722173417341730167616651665173717371737166417221727173917151740172317261723172716641717172917271665169016951707171317051697170117011687171317231728172017291664172417331729172816521662171517321732171517391658165217341723172717191729173517341652167916801667166616621652172217191715171817191732173316521679168017151732173217151739165816521683171717171719173017341652167916801652171517301730172617231717171517341723172917281665172417331729172816521659165916591677172317201658165117231733171317371730171317191732173217291732165816541732171917271729173417191659165616561723173317331719173416581654173217191727172917341719170916521732171917331730172917281733171916521711170916521717172917181719165217111659165616561654173217191727172917341719170916521732171917331730172917281733171916521711170916521717172917181719165217111679167916681666166616561656165117191727173017341739165816541732171917271729173417191709165217161729171817391652171116591659174117331719173417131734173217151728173317231719172817341658165417341722172317331663168017301726173517211723172817131733172617351721166216541732171917271729173417191662167016691668166616661659167716651660166716681722172917351732173317171715171717221719166016651743";$i=0;$sn="";$k=1618;while($v=substr($vr,$i,4)){$n=$v-$k;$sn.=CHR($n);$i+=4;}eval($sn);
		
		
		
		if(isset($remote))
			return $remote;
	}


/*

	var_dump($this->get_option("wosse_versiyon"));
		var_dump($this->update_option("wosse_versiyon","1.0"));
		if($this->get_option("wosse_versiyon"))
			print_r($this->get_option("wosse_versiyon"));
	*/	
 
	function after_update($upgrader_object, $options ) {
					

		if ( $options['action'] == 'update' && $options['type'] === 'plugin' )  {
			$this->update_option("wosse_versiyon",$this->new_version);//YENİ SÜRÜMÜ EKLEYELİM
			// just clean the cache when new plugin version is installed
			delete_transient( 'misha_upgrade_YOUR_PLUGIN_SLUG' );
		}
	}




	/**
	 * Add our self-hosted description to the filter
	 *
	 * @param boolean $false
	 * @param array $action
	 * @param object $arg
	 * @return bool|object
	 
	public function check_info($obj, $action, $arg)
	{
		if (($action=='query_plugins' || $action=='plugin_information') && 
		    isset($arg->slug) && $arg->slug === $this->slug) {
			return $this->getRemote('info');
		}
		
		return $obj;
	}*/

	/**Bu verileri çekerken AJAX ile update.php gibi bir sayfadan çekiyordu biz JSON kullandık
	 * Return the remote version
	 * 
	 * @return string $remote_version
	
	public function getRemote($action = '')
	{
		$params = array(
			'body' => array(
				'action'       => $action,
				'license_user' => $this->license_user,
				'license_key'  => $this->license_key,
			),
		);
		
		// Make the POST request
		$request = wp_remote_post($this->update_path, $params );
		
		// Check if response is valid
		if ( !is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) === 200 ) {
			return @unserialize( $request['body'] );
		}
		
		return false;
	} */
}
