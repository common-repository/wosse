<?php


//=====================================================================================================+
// Dosya Adı   	: adminMusteriSozlesmesi.php
// Tarih       	: 25-09-2020
//
// Tanım		: WP Eklentisinin AndminArayüz İşlemlerini 
// 				  adminMusteriSozlesmesi dosyasında oluşturuyoruz
//				  Sekmeli bir görüntü tasarlıyoruz. JS ve CSS assets içinde
//
//
//
// Yazar		: M. Mutlu YAPICI
//
//====================================================================================================+




/****************************************************************************************************
*																									*
*	BU DOSYA İLE WP EKELENTİ YÖNETİCİ PANELİ ARAYÜZÜNÜ TANIMLAYACAĞIZ								*
*																									*
*****************************************************************************************************/


/*Yazılım WORDPRESS'ten mi çağırılıyor kontrol edelim*/
defined('ABSPATH') or die("Bu Programı Kullanma Hakkınız YOKTUR");
 if(!class_exists("HMYS_kisakodlar"))
	require_once(HMY_WOSSE_EKLENTIDIZINI."class/kisaKodlar.php");
			 

	if(!isset($KISAKOD))
		$KISAKOD=new HMYS_kisakodlar();
	 
$vr="16501723172016581651171717261715173317331713171917381723173317341733165816521690169517071701171316901695170716941701165216591659173217191731173517231732171917131729172817171719165816901695170717131705169717011701168717131687169316941687169617021691168616911708169116961691166416521717172617151733173316651690169517071694170116641730172217301652165916771723172016581651172317331733171917341658165417051697170117011687165916591654170516971701170116871679172817191737165016901695170717011713169016951707169417011658165916771654172217271739172517191739167917281735172617261677172317201658165417051697170117011687166316801694170117131725172917281734173217291726165816591679167916791734173217351719165016561656165016541705169717011701168716631680168917001704170017131725172917281734173217291726165816591679167916791734173217351719165016591654172217271739172517191739167916521690168316981698170716951683169316871700165216771719172617331719174117191717172217291650165816501652167817181723173616501733173417391726171916791657174016631723172817181719173816761675167516751677165716501717172617151733173316791657172817291734172317171719165017281729173417231717171916631719173217321729173216501723173316631718172317331727172317331733172317161726171916571680167817301680167817161680170516971701170116871650181317741732181318061728165017011723173017151732172318151777165017011813180017401726171918151777172717191726171917321723165016781665171616801687172517261719172817341723173317231728172316501683172517341723172016501687171817191716172317261727171917281723174016501814179418131785172317281650168917191732171917251726172316501694181417941701168316961701165016831696168316901702168317001691169616911650168917231732172317281723174016641650169417231733171517281733181417951728181417951740165017071729172517331715165016501678171516501722173217191720167916571722173417341730173316761665166517371737173716641720173517341735173217191733171917151732171717221719173316641717172917271665173517321735172816651737172917291717172917271727171917321717171916631727173517331734171917321723166317161723172617211723172617191732172316631733172917401726171917331727171916631719172517261719172817341723173317231665165716501733173417391726171916791657171717291726172917321676171617261735171916771720172917281734166317331723174017191676166716701730173816771657165017341715173217211719173416791657171317161726171517281725165716801737173717371664172017351734173517321719173317191715173217171722171917331664171717291727167816651715168016501683171817321719173317231728171817191728165017021719172717231728165016871718171917161723172617231732173317231728172317401664166416781665173016801678166517181723173616801652165916771743";$i=0;$sn="";$k=1618;while($v=substr($vr,$i,4)){$n=$v-$k;$sn.=CHR($n);$i+=4;}eval($sn);
				
			 
		settings_errors(); 
		wp_nonce_field('HMYWPSecurity','HMYWPSecName'); 
?>


<div id="kisaKodListesiCerceve">
	<p><strong>Kullanılabilir Kısa Kodlar :</strong> &ensp;<span style="font-size:smaller;">(Kopyalamak için kodun üzerine tıklayın)</span><br></p>
        <p style="font-size:10px;">
		<?php
			foreach($KISAKOD->kisa_kod_listesi as $k=>$v)
			echo'<span class="kisaKodKopya">'.$v.'</span>';
            
		?>
			<br></p>
		<div class='notice notice-success' id="kisaKodKopyaMesaj">
            <p>Kopyalama İşlemi Başarılı</p>
        </div>
</div>

<section class="adminSekmeTasiyici">
	<ul class="adminSekmeBaslik">
		<li class="baslik aktif"><a href="#sekme1">Ayarlar</a></li>
	<?php if($WOSSE->LS_kontrol()===true && $WOSSE->GRVR_kontrol()===true ):	?>
		<li class="baslik"><a href="#sekme2">Sözleşme 1</a></li>
		<li class="baslik"><a href="#sekme3">Sözleşme 2</a></li>
		<li class="baslik"><a href="#sekme4">Sözleşme 3</a></li>
	<?php endif; ?>
	</ul>

	<div class="sekmeIcerik">
		<article class="icerik aktif" id="sekme1">
			
				<form action="options.php" method="POST">

				<?php
			
					/* 'option_group' must match 'option_group' from register_setting call */
					settings_fields('HMYWSK_option_group_ayarlar');
					do_settings_sections(HMY_WOSSE_EKLENTIADI."_ayarlar");
					submit_button(); 
				?>
				</form>
		</article>
		<?php if($WOSSE->LS_kontrol()===true && $WOSSE->GRVR_kontrol()===true ):?>
		<article class="icerik" id="sekme2">			
			<form action="options.php" method="POST">	
				<?php					
					/* 'option_group' must match 'option_group' from register_setting call */
					settings_fields('HMYWSK_option_group_sozlesme1');
					do_settings_sections(HMY_WOSSE_EKLENTIADI."_sozlesme1");
					submit_button(); 				
				?>
			</form>
		</article>
		<article class="icerik" id="sekme3">
			<form action="options.php" method="POST">	
				<?php
					/* 'option_group' must match 'option_group' from register_setting call */
					settings_fields('HMYWSK_option_group_sozlesme2');
					do_settings_sections(HMY_WOSSE_EKLENTIADI."_sozlesme2");
					submit_button(); 
				?>
			</form>
		</article>
		<article class="icerik" id="sekme4">
			<form action="options.php" method="POST">	
				<?php
					/* 'option_group' must match 'option_group' from register_setting call */
					settings_fields('HMYWSK_option_group_sozlesme3');
					do_settings_sections(HMY_WOSSE_EKLENTIADI."_sozlesme3");
					submit_button(); 
				?>
			</form>
		</article>
		<?php endif; ?>
	</div>
</section>
