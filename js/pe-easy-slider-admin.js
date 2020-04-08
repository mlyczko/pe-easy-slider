jQuery(document).ready(
	function($)
	{
	    function on_form_update() {
		    jQuery(".widget-liquid-right .widget .pe-easy-slider-widget-options p.pe-easy-slider-source-selector").each(function(){
				if ($(this).children('.pe-easy-slider-source-select').val() == 'folder'){
				      $(this).parent().children('p.source-posts').hide();
				      $(this).parent().children('p.source-folder-images').show();
				}
				if ($(this).children('.pe-easy-slider-source-select').val() == 'posts'){
				      $(this).parent().children('p.source-posts').show();
				      $(this).parent().children('p.source-folder-images').hide();
				}
			})
	    }
	    
	    $( document ).on( 'widget-updated', on_form_update );
	    $( document ).on( 'widget-added', on_form_update );
	     
	    jQuery(".widget-liquid-right .widget .pe-easy-slider-widget-options p.pe-easy-slider-source-selector").each(function(){
			if ($(this).children('.pe-easy-slider-source-select').val() == 'folder'){
			      $(this).parent().children('p.source-posts').hide();
			      $(this).parent().children('p.source-folder-images').show();
			}
			if ($(this).children('.pe-easy-slider-source-select').val() == 'posts'){
			      $(this).parent().children('p.source-posts').show();
			      $(this).parent().children('p.source-folder-images').hide();
			}
		})

		$(".widget-liquid-right .pe-easy-slider-source-select").live("change", function() {
		    if($(this).val() == 'folder'){
		      $(this).parent().parent().children('p.source-posts').hide();
		      $(this).parent().parent().children('p.source-folder-images').show();
		    } else if($(this).val() == 'posts'){
		      $(this).parent().parent().children('p.source-posts').show();
		      $(this).parent().parent().children('p.source-folder-images').hide();
		    }
		});
	}
);