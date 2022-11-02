var tpj=jQuery;
		tpj.noConflict();
		var $ = jQuery.noConflict();


		tpj(document).ready(function() {
			var apiRevoSlider = tpj('#rev_slider_k_fullwidth').show().revolution(
			{
				sliderType:"standard",
				sliderLayout:"fullwidth",
				delay:9000,
				navigation: {
					arrows:{enable:true}
				},
				responsiveLevels:[1240,1024,778,480],
				visibilityLevels:[1240,1024,778,480],
				gridwidth:[1240,1024,778,480],
				gridheight:[600,768,960,720],
			});

			apiRevoSlider.on("revolution.slide.onloaded",function (e) {
				setTimeout( function(){ SEMICOLON.slider.sliderDimensions(); }, 400 );
			});

			apiRevoSlider.on("revolution.slide.onchange",function (e,data) {
				SEMICOLON.slider.revolutionSliderMenu();
			});
		});