<?php

//============================================================+
// Dosya Adı   	: formElemanlari.php
// Tarih       	: 06-09-2020
//
// Tanım		: WP İçin Form Elemanları Tanımlama İşlemlerini 
// 				  Bu Sınıfıta oluşturuyoruz
//
// Yazar		: M. Mutlu YAPICI
//
//============================================================+




/****************************************************************************************************
*																									*
*	BU SINIFTA WP EKELENTİLERİNDE KULLANACAĞIMIZ FORM ELEMANLARINI TANIMLAYACAĞIZ					*
*	YÖNETİCİ ARAYÜZÜNDE GÖRÜNECEK FORM ELEMANLARINI HAZIRLAYIP GÖNDERECEĞİMİZ FONKSİYONLAR BULUNACAK*
*																									*
*****************************************************************************************************/


class HMYS_FormElemanlari{
	
	
	/*Burada Tüm Sayfalarda Kullanabileceğim Form Elemanlarını Tasarlayacağım*/
	/*$args dizisinde gelen $args['type'] parametresine göre Eleman türü belirlenecek*/
	public function ElemanTasarimlari($args)
	{
		
		/* Her Elemanın  değişken isimi (name) dizi şeklinde olacak (SERIALIZED DATA)
		    Ana dizi adı $args["DegiskenAdi"] indisinde tanımlı olan ve register_setting
			fonksiyonunun ikinci parametresiyle tanımlı isim olacak. Elemanların tüm değerleri
			o ana dizinin içine yazılacak ulaşırken get_option($args['DegiskenAdi'])[$E_name]) kullanılacak
		*/
		
		if(!is_array($args))
			return false;
		
		if(!isset($args['type']))
			return false;
		
		$veri="";
		
		/*parametreleri alalım*/
		$E_type=isset($args['type'])?$args['type']:'';//Elemanın Tipi
		$E_name=isset($args['name'])?$args['name']:'';//Elemanın Adı
		$E_class=isset($args['class'])?$args['class']:'';//Elemanın CSS Sınıfı
		$E_degisken=isset($args['DegiskenAdi'])?$args['DegiskenAdi']:'';//Elemanın Adının bulunduğu dizi adı
		$E_id=isset($args['id'])?$args['id']:'';//Elemanın Idsi
		$E_value=isset($args['value'])?$args['value']:"";//Elemanın Gönderilen Değeri
		$E_kayitliDeger=isset(get_option($E_degisken)[$E_name])?get_option($E_degisken)[$E_name]:array();//Elemanın Daha önceden Kayıtlı değeri varsa alalım
		$E_placeholder=isset($args['placeholder'])?$args['placeholder']:'';//Elemanın placeholder değeri
		$E_ekozellik=isset($args['E_ekozellik'])?$args['E_ekozellik']:'';//Elemanın içine yazılacak ek tanımlar
		$E_max=isset($args['max'])?$args['max']:'';//Elemanın max attribute
		$E_min=isset($args['min'])?$args['min']:'';//Elemanın min attribute
		$E_step=isset($args['step'])?$args['step']:'';//Elemanın step attribute
		$E_Etiket=isset($args['etiket'])?$args['etiket']:'';//Radio ve Checkbox Elemanın etiket adı
		$E_options=isset($args['options'])?$args['options']:'';//Select Elemanının seçenekleri dizi halinde keyler value değerler etiket
		$E_baslik=isset($args['baslik'])?$args['baslik']:'';//Modal gibi Elemanların Başlığı
		$E_richmi=isset($args['richtext'])?$args['richtext']:false;//Textare RichText mi Basit mi olacak
		
		
		$E_sonDeger=($E_kayitliDeger!=Null)?$E_kayitliDeger:$E_value;
		
		
		switch($args['type']){
			
			case 'password': case 'text': case 'file':case 'email':	case 'url':	case 'hidden':	case 'color': case 'button': case 'submit': case 'reset': case 'image':				
				$veri="<input type='$E_type' name='".$E_degisken."[".$E_name."]"."' value='$E_sonDeger' id='$E_id' placeholder='$E_placeholder'  class='$E_class' ".$E_ekozellik."  />";
			break;
			
			case'number':	case'time':	case'month':	case'week':	case'date':	case'date-time':	case'datetime-local':				
				$veri="<input type='$E_type' name='".$E_degisken."[".$E_name."]"."' value='$E_sonDeger' id='$E_id' placeholder='$E_placeholder' max='$E_max' min='$E_min' step='$E_step'  class='$E_class' ".$E_ekozellik."  />";
			break;
			
			case'range':			
				$veri="$E_min<input type='$E_type' name='".$E_degisken."[".$E_name."]"."' value='$E_sonDeger' id='$E_id' placeholder='$E_placeholder' max='$E_max' min='$E_min' step='$E_step'  class='$E_class' ".$E_ekozellik." oninput='cccik.value=this.value' />$E_max <output name='cccik'>$E_sonDeger</output>";
			break;
			
			case'radio':	
				$c=checked(($E_value==$E_sonDeger),true,false);
				
				$veri="<div class='HMY_checkbox_div'>
				<input type='$E_type' name='".$E_degisken."[".$E_name."]"."' value='$E_value' id='$E_id'  class='$E_class' ".$E_ekozellik."  $c  />
				<label for='$E_id'>$E_Etiket</label>
				</div>";
			break;
			
			case'checkbox':	
				$veri="<div class='HMY_checkbox_div'>
					<input type='$E_type' name='".$E_degisken."[".$E_name."][]"."' value='$E_value' id='$E_id'  class='$E_class' ".$E_ekozellik." ".checked( in_array($E_value, $E_kayitliDeger),true,false)."  />
				<label for='$E_id'>$E_Etiket</label>
				</div>";
			break;
			
			case 'WP_Media_Secici': 		

				$veri="<div class='HMY_WP_Media_Secici_div'>
						<label for='upload-btn'>
						<img src='$E_sonDeger' id='img_$E_id' title='Resim Seçmek için Tıklayın' alt='Bir Resim Seçin' width='120px'/></label>
					<input type='hidden' name='".$E_degisken."[".$E_name."]"."' value='$E_sonDeger' id='$E_id' placeholder='$E_placeholder' class='$E_class' ".$E_ekozellik."  />
					 <input type='button' hmyelemanverisi='$E_id' name='upload-btn' id='upload-btn' class='button-secondary' value='Resim Seç'>

				</div>";			
				
			break;
			
			case'textarea':	
				if(!$E_richmi){
				$veri="<textarea style='resize:both;' name='".$E_degisken."[".$E_name."]"."' id='$E_id'  class='$E_class' ".$E_ekozellik.">$E_sonDeger</textarea>";
				}else{
					$settings = array('tinymce' => true, 'textarea_name' => $E_degisken."[".$E_name."]");
					wp_editor(wpautop($E_sonDeger), $E_id, $settings);
				}
			break;
			
			case 'select': 			
				$veri="<select  name='".$E_degisken."[".$E_name."]"."' id='$E_id'  class='$E_class' ".$E_ekozellik.">";
					if(is_array($E_options))
						foreach($E_options as $k=>$v){
							$s=($E_sonDeger==$k|| $E_value==$k)?"selected":"";
							$veri.="<option value='$k' $s>$v</option>";
						}
				$veri.="</select>";
					
			break;
			
			
			case'modalSayfa':
				$E_value=wpautop($E_value);
			$veri=<<<MODAL
				<div class="HMYWSK_modal_div $E_class" >
					<div class="HMYWSK_modal_section">
						<h2 id='modalBaslik'>$E_baslik
						<span id='HMYWP_Modal_Butonu'  class="HMYWP_Modal_Butonu" hmymodalsinif="HMYWSK_modal_div_kapat">X</span>
						</h2> 
						<article id='modalIcerik'>$E_value</article>
					</div>
				</div>
MODAL;
			break;
			
		}

		echo $veri;
		
	}
	
	
	
	
}


if(!isset($FRMELMN))
 {
	 
	 $FRMELMN=new HMYS_FormElemanlari();
	 
 }


?>