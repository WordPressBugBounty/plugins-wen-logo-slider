var wls_file_frame;

(function( $ ) {
	'use strict';

	 $(function() {

	 	$( "#main-slides-list-wrap" ).sortable();

	 	// Create Bulk Slider 
	 	jQuery(document).on('click', 'input.wls-select-img', function( event ){	 		
	 	  var $this = $(this);
	 	  event.preventDefault();

	 	  // Create the media frame.
	 	  wls_file_frame = wp.media.frames.wls_file_frame = wp.media({
	 	    title: jQuery( this ).data( 'uploader_title' ),
	 	    button: {
	 	      text: jQuery( this ).data( 'uploader_button_text' ),
	 	    },
	 	    multiple: true  // Set to true to allow multiple files to be selected
	 	  });

	 	  // When an image is selected, run a callback.
	 	  wls_file_frame.on( 'select', function() {

	 	    // We set multiple to false so only get one image from the uploader
	 	    //var attachment = wls_file_frame.state().get('selection').first().toJSON();
	 	    var attachment = wls_file_frame.state().get('selection').toJSON();	
	 	    
	 	   	var slide_count;
	 	    if($('.ws-logo-slider-pro').length > 0)
	 	    	slide_count = $('.ws-logo-slider-pro').length;
 	    	else
	 	    	slide_count = 0; 
	 	    
	 	    for(var i =0; i<attachment.length; i++){
		 	    var mytemplate = $("#template-wls-slider-item").html();

		 		mytemplate = '<div class="ws-logo-slider-pro" id="ws-logo-slider-pro' + slide_count + '">' + mytemplate + '</div>';

		 		$('#main-slides-list-wrap').append(mytemplate);

		 	    var parent_id = "ws-logo-slider-pro"+slide_count;		 	    
		 	    var image_field = $('#'+parent_id).children().children().children().children().siblings('.wls-slide-image-id');
		 	    image_field.val(attachment[i].id);

		 	    var imgurl = attachment[i].url;
		 	    if( 'undefined' != typeof attachment[i].sizes.thumbnail ){
	  	 	    imgurl = attachment[i].sizes.thumbnail.url;
		 	    }
		 	    var image_preview_wrap = $('#'+parent_id).children().children().children().children().siblings('.image-preview-wrap');
		 	    image_preview_wrap.show();
		 	    image_preview_wrap.find('.img-preview').attr('src',imgurl);

		 	     // Hide upload button
	 	    	//$this.hide();
	 	    	 $('#'+parent_id).children().children().children().children().siblings('.wls-select-single-img').hide();

		 	    //e.preventDefault();
		 	    slide_count++;	 	 		
	 	    }		 
	 	    $(".wls-choosen").chosen({
				disable_search_threshold: 10 
			});	   
	 	    return;
	 	  });

	 	  // Finally, open the modal
	 	  wls_file_frame.open();
	 	});


		wp.media.view.Attachment.prototype.toggleSelectionHandler = function( event ) {
			// Don't do anything inside inputs and on the attachment check and remove buttons.
			if ( 'INPUT' === event.target.nodeName || 'BUTTON' === event.target.nodeName ) {
				return;
			}

			// Catch arrow events
			if ( 37 === event.keyCode || 38 === event.keyCode || 39 === event.keyCode || 40 === event.keyCode ) {
				this.controller.trigger( 'attachment:keydown:arrow', event );
				return;
			}

			// Catch enter and space events
			if ( 'keydown' === event.type && 13 !== event.keyCode && 32 !== event.keyCode ) {
				return;
			}

			event.preventDefault();
			var method = 'between';
		    if ( event.shiftKey ) {
		        method = 'between';
		    } else {
		        method = 'toggle';
		    }

		    this.toggleSelection({
		        method: method
		    });
		};
	

		// single image Upload/change only
	 	jQuery(document).on('click', 'input.wls-select-single-img', function( event ){
			var $this = $(this);
			event.preventDefault();

			// Create the media frame.
			wls_file_frame = wp.media.frames.wls_file_frame = wp.media({
				title: jQuery( this ).data( 'uploader_title' ),
				button: {
				  text: jQuery( this ).data( 'uploader_button_text' ),
				},
				multiple: false  // Set to true to allow multiple files to be selected
			});

			// When an image is selected, run a callback.
			wls_file_frame.on( 'select', function() {
	 	    // We set multiple to false so only get one image from the uploader
		 	    var attachment = wls_file_frame.state().get('selection').first().toJSON();	 
		 	    
		 	    var image_field = $this.siblings('.wls-slide-image-id');
		 	    image_field.val(attachment.id);

		 	    var imgurl = attachment.url;
		 	    if( 'undefined' != typeof attachment.sizes.thumbnail ){
	  	 	    imgurl = attachment.sizes.thumbnail.url;
		 	    }
		 	    var image_preview_wrap = $this.siblings('.image-preview-wrap');
		 	    image_preview_wrap.show();
		 	    image_preview_wrap.find('.img-preview').attr('src',imgurl);

		 	     // Hide upload button
	 	    	$this.hide();
		 	    return;
			});

			// Finally, open the modal
			wls_file_frame.open();
	 	});

		// Image remove button handler
		$(document).on('click', 'a.btn-wls-remove-image-upload', function(evt){
		  evt.preventDefault();
		  var $this = $(this);

		  var image_field_temp = $this.parent().parent().parent().find('input.wls-slide-image-id');
		  var upload_button = $this.parent().parent().parent().find('input.wls-select-single-img');
		  var image_preview_wrap = $this.parent().parent().parent().find('.image-preview-wrap');
		  var cur_image_value = image_field_temp.val();

		  image_field_temp.val('');
		  image_preview_wrap.fadeOut('slow',function(){
			  image_preview_wrap.hide();
			  image_preview_wrap.find('.img-preview').attr('src','');
			  upload_button.fadeIn();
		  });
		  return;
		});


	 	// Remove Handler
	 	$(document).on('click','input.btn-remove-slide-item', function(e){

	 		e.preventDefault();
	 		// Confirmation
	 		var confirmation = confirm(WLS_OBJ.lang.are_you_sure);
	 		if( ! confirmation ){
	 			return false;
	 		}

	 		var $this = $(this);
	 		var $wrap = $this.parent().parent();
	 		$wrap.fadeOut('slow',function(){
				$wrap.remove();
			});

	 	});


	 	// Slides for Difn Resolution - Enable / Disable
        $("#wls_enable_mobile_resolution").click(function () {
            if ($(this).is(":checked")) {
                $("#mobile-resolution-options").fadeIn('slow');
                $('.wls-resolutions').attr('required','required');
            } else {
                $("#mobile-resolution-options").fadeOut('slow');
                $('.wls-resolutions').removeAttr('required');
            }
        });
		
		// Pagination Enable / Disable
        $("#wls_pagination").click(function () {
            if ($(this).is(":checked")) {;
                $("#pagination_types").fadeIn('slow');
            } else {
                $("#pagination_types").fadeOut('slow');
            }
        });


		// Navigation arrow Enable / Disable (Prev / Next)
		$("#wls_enable_navigation_arrow").click(function () {
            if ($(this).is(":checked")) {                        
                $("#navigation_types").fadeIn('slow');
                $("#navigation_arrow_mob").fadeIn('slow');
            } else {                       
                $("#navigation_types").fadeOut('slow');
                $("#navigation_arrow_mob").fadeOut('slow');
            }
        });
		
		// heading size options
		$("#wls-show_title").click(function () {
            if ($(this).is(":checked")) {                        
                $("#slide-headings").fadeIn('slow');               
            } else {                       
                $("#slide-headings").fadeOut('slow');               
            }
        });

		// Caption option check for caption effect
		$("#wls_caption").on('change',function(){
			var caption_val = $(this).val();
			if(caption_val != "No caption")
				$('#caption-effect').fadeIn('slow');					
			else
				$('#caption-effect').fadeOut('slow');
		});

        // for choosen dropdown
        $(".wls-choosen").chosen({
		  disable_search_threshold: 10 
		});
		$(".wls-choosen-nav-type").chosenImage({
		  disable_search_threshold: 10,
		  
		});


		$(document).on('keyup select', ".txt-slide-url",function(e) {				
			var url = $(this).val();
			var pattern = /^(http(s)?:\/\/)?(www\.)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/;				
			 if(!pattern.test(url) && url != "")
			 	$(this).addClass('error');
		 	else
		 		$(this).removeClass('error');

		 	var Error = "";
		 	$('.txt-slide-url').each(function(i){
		 		if($(this).hasClass('error')){
		 			Error ="has error";
		 		}			 			
		 	});

		 	if(!Error)
		 		$('#publish').removeAttr('disabled');
		 	else{
		 		$('#publish').attr('disabled','disabled');			 					 		  
		 	}
			
		});
	 	// $(document).on('click keyup', "#images_per_slide",function(e){
	 	// 	e.preventDefault();	
	 	// 	// var slides = $('.slide-item-wrap').length;
	 	// 	// $(this).attr('max',slides);
	 	// 	// $('#res786').attr('max',slides);
	 	// 	// $('#res360').attr('max',slides);
	 	// 	// console.log(slides);
	 	// });

	 	$(".tabs-menu a").click(function(event) {
	        event.preventDefault();
	        $(this).parent().addClass("current");
	        $(this).parent().siblings().removeClass("current");
	        var tab = $(this).attr("href");
	        $(".tab-content").not(tab).css("display", "none");
	        $(tab).fadeIn();
	    });

	    $("input:text").focus(function() { $(this).select(); } );

	    // setting option acc
	    $(document).on('click','.option-title a',function(e){
			e.preventDefault();
			$(this).parent().next("div.setting-options").slideToggle();
			
			if($(this).hasClass('showing')){
				$(this).removeClass('showing');
				$(this).children('i').removeClass('dashicons-arrow-up').addClass('dashicons-arrow-down');				
			}
			else{
				$(this).addClass('showing');
				$(this).children('i').removeClass('dashicons-arrow-down').addClass('dashicons-arrow-up');				
			}
		});

		$( document ).on( 'click', '.ws-add-new-breakpoint-popup', function() {
			$( '.ws-add-breakpoint-template' ).slideToggle();
		} );

		$( document ).on( 'click', '.ws-add-new-breakpoint', function() {


			var breakpoint = $('.ws-break-point-temp').val();
			var slides = $('.ws-number-of-slides').val();

			$('.ws-break-point-temp').removeClass( 'error' );
			$('.ws-number-of-slides').removeClass( 'error' )

			if ( '' == breakpoint || '' == slides ) {
				
				if ( '' == breakpoint )
					$('.ws-break-point-temp').addClass('error');
				if ( '' == slides )
					$('.ws-number-of-slides').addClass( 'error' );

				return
			}
			var template = '<div class="ws-breakpoint">';
				template += '<a href="javascript:void(0)" class="wls-breakpoint-remove"><i class="dashicons dashicons-dismiss"></i></a>';
				template += '<input type="number" min="1" max="9" class="wls-resolutions" id="res_' + breakpoint + '" name="wen_logo_slider_settings[res][_' + breakpoint + ']" value="' + slides + '"  required /><br>';							
				template += '<span>Breakpoint < ' + breakpoint + '</span>';
				template += '</div>';
			$('.ws-breakpoints').append( template );

			$( '.ws-add-breakpoint-template' ).slideToggle();

			$('.ws-break-point-temp').val('');
			$('.ws-number-of-slides').val('');
		} );

		$( document ).on( 'click', '.wls-breakpoint-remove', function() {
			var total_breakpoint = $('.ws-breakpoint').length;
			var breakpoint_name = $(this).siblings('span').text();
			if ( total_breakpoint > 2 ) {

				var c = confirm( "Are you sure to delete " +  breakpoint_name + ' ?' )
				if ( c ) {
					$(this).parent('.ws-breakpoint').remove();
				}			
			} else {
				alert( 'Sorry ! atleast 2 breakpoints are required.' )
			}
		} );

 	 });

})( jQuery );

