jQuery(document).ready(function(){
	
	jQuery('body').on("click",'.HMYWP_Modal_Butonu',function(){
		var modalSinif=jQuery(this).attr("hmymodalsinif");

		var yeniSinif="";
		var eskiSinif="";
		
		if(modalSinif=="HMYWSK_modal_div_kapat"){
			yeniSinif="HMYWP_Modal_Kapa";
			eskiSinif="HMYWP_Modal_Ac";
			modalSinif="HMYWSK_modal_div";
		}else{
			//Açmadan önce formdaki verileri çekip güncelleme işlemini yapsın
			form_verilerini_guncelle();

			n = jQuery("."+modalSinif).attr("class").indexOf("HMYWP_Modal_Ac");
			yeniSinif=(n==-1)?"HMYWP_Modal_Ac":"HMYWP_Modal_Kapa";
			eskiSinif=(n==-1)?"HMYWP_Modal_Kapa":"HMYWP_Modal_Ac";
		}
			
		jQuery("."+modalSinif).removeClass(eskiSinif);
		jQuery("."+modalSinif).addClass(yeniSinif);
	
	});
	
	
	 jQuery( document.body ).on( 'checkout_error', function() {
		
		 form_verilerini_guncelle();
    });
	
	
	function form_verilerini_guncelle(){
		
		 
		var FomrData=jQuery("form").serializeArray();
		
		 var a=jQuery("form input[name='payment_method']");
		 var yonetem=null;
		 jQuery.each(a, function( index, value ) {
				if(value.checked)
					if(typeof(value.labels)!== 'undefined')
					 yonetem=(value.labels[0].innerText);
					else
						yonetem="";
			});
			
		FomrData.push({name: 'payment_method_label', value: yonetem});
		
		jQuery.post('/?wc-ajax=HMYAJAXfonksiyonum',FomrData)
		.done(function(cevap){
			var dizi=JSON.parse(cevap);
			jQuery.each(dizi, function( index, value ) {
				jQuery("."+index+" .HMYWSK_modal_section #modalIcerik").html(value);
			});
						
		})
		.fail(function(){
			console.log('ajax request failed. check network log.');
		});
		
	}
	
	
	
	/*Admin paneli AKTİF SEKME Ayarı*/
	jQuery( document.body ).on('click', '.adminSekmeTasiyici .adminSekmeBaslik li', function(e) {
		e.preventDefault();
		
		 var hedef=jQuery(this).children().attr("href");

		 ///SekmeBasligindaki tüm elemanların sınıfını pasif yapalım
		jQuery( ".adminSekmeTasiyici .adminSekmeBaslik li" ).each(function() {
			 jQuery(this).removeClass( "aktif" );
		});
		
		//tıklanan sekmeyi aktif yapalım
		 jQuery(this).addClass( "aktif" );
		 
		  ///Sekmeİçeriklerinin hepsinin sınıfını pasif yapalım
		jQuery( ".adminSekmeTasiyici .sekmeIcerik article" ).each(function() {
			 jQuery(this).removeClass( "aktif" );
		});
		
		///Sadece Tıklanan Başlıktaki Hedef idye sahip olanı aktif edelim
		jQuery( ".adminSekmeTasiyici .sekmeIcerik "+hedef).addClass( "aktif" );
		
	 });
	
	
	
	/*Admin paneli LOGO Upload*/
	jQuery('#upload-btn').click(function(e) {
        e.preventDefault();
		var secimID=jQuery(this).attr("hmyelemanverisi");
        var image = wp.media({ 
            title: 'Upload Image',
            // mutiple: true if you want to upload multiple files at once
            multiple: false
        }).open()
        .on('select', function(e){
            // This will return the selected image from the Media Uploader, the result is an object
            var uploaded_image = image.state().get('selection').first();
            // We convert uploaded_image to a JSON object to make accessing it easier
            // Output to the console uploaded_image
            console.log(uploaded_image);
            var image_url = uploaded_image.toJSON().url;
            // Let's assign the url value to the input field
			
            jQuery('#img_'+secimID).attr("src",image_url);
            jQuery('#'+secimID).val(image_url);
        });
    });
	
	
	
	
	
	
	/*İndirme Bağlantısı AJAX tetikleyicisi*/	
	 jQuery( document.body ).on('click', '#sozlesmeIndirLink', function() {
		
		 
		 var lnk=jQuery(this).attr("hmylinkverisi");
		
		
		 var veri = {
				linkVerisi: lnk,
			};
		
		jQuery.post('/?wc-ajax=HMYAJAXsozlesmeIndir',veri)
		.done(function(cevap){
			 var link=document.createElement('a');
			 document.body.appendChild(link);
			 link.href=cevap;
			 link.target="_blank";
			 link.click();
									
		})
		.fail(function(){
			console.log('ajax request failed. check network log.');
		});
    });
	
	
	
    jQuery( document.body ).on('click', '.kisaKodKopya', function (node) {

        // Skip IE < 9 which uses TextRange instead of Range

        if (!window.getSelection) {

            return false;

        }

				
		jQuery('#kisaKodKopyaMesaj').removeClass("gorunKaybol");

		var copyText = jQuery(this);
			var textArea = document.createElement("textarea");
			textArea.value = copyText[0].innerHTML;
			document.body.appendChild(textArea);
			textArea.select();
			document.execCommand("Copy");
			textArea.remove();
			jQuery('#kisaKodKopyaMesaj p').html("<b>"+copyText[0].innerHTML+"</b> Kopyalama İşlemi Başarılı");


        try {

            result = document.execCommand('copy');

        } catch (err) {}



        // Restore previous selection if any

		jQuery('#kisaKodKopyaMesaj').toggleClass("gorunKaybol");
        

        return false;

    });
	
	
});
