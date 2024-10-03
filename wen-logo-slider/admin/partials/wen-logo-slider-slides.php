		<?php global $post; ?>
		<?php wp_nonce_field( 'ws_logo_slider_slides_nonce_action', 'ws_logo_slider_slides_nonce_field' ); ?>

		<div id="main-slides-list-wrap">
			<?php
				$slides = get_post_meta( $post->ID, '_wls_slides', true ) ;
			 ?>
			 <?php if ( ! empty( $slides ) ): ?>

			 	<?php $count = 0; ?>
			 	<?php foreach ($slides as $key => $slide): ?>
			 		<div class="ws-logo-slider-pro" id= "ws-logo-slider-pro<?php echo $count; ?>">
						<div class="slide-item-wrap clearfix">
							<input type="button" value="" class="button btn-remove-slide-item"/>
							<div class="slide-item-left">
							
								<div class="wls-form-row">
									<?php

										$thumbnail_url = '';
										$thumbnail_id = $slide['slide_image_id'];
										if ($thumbnail_id) {
											$thumbnail_url = wp_get_attachment_thumb_url( $thumbnail_id );
										}
										$upload_button_status = ' style="display:none;" ';
										if ( empty( $thumbnail_url ) ) {
											$upload_button_status = '';
										}

									?>

									<input type="hidden" name="slide_image_id[]" value="<?php echo esc_attr( $slide['slide_image_id'] ); ?>" class="wls-slide-image-id" />
									<input type="button" class="wls-select-single-img button button-primary" value="<?php _e( 'Upload', 'wen-logo-slider' ); ?>" data-uploader_button_text="<?php _e( 'Select', 'wen-logo-slider' );?>" data-uploader_title="<?php _e( 'Select Image', 'wen-logo-slider' );?>" <?php echo $upload_button_status; ?>/>

									<?php
										$style_text="display:none;";
										if ( !empty($thumbnail_url)) {
											$style_text = '';
										}
									 ?>

									<div class="image-preview-wrap" style="<?php echo $style_text; ?>" >
										<img class="img-preview" alt="<?php _e( 'Preview', 'wen-logo-slider' ); ?>" src="<?php echo $thumbnail_url; ?>" height="150" width="150" />
										<a href="#" class="btn-wls-remove-image-upload">
											<span class="dashicons dashicons-dismiss"></span>
										</a>
									</div>

								</div>

							</div>
							<div class="slide-item-right">
								<div class="wls-form-row">
									<i class="dashicons dashicons-editor-textcolor"></i>
									<input type="text" name="slide_title[]" value="<?php echo esc_attr( $slide['title'] ); ?>" placeholder="<?php _e( 'Enter Title', 'wen-logo-slider' ); ?>" class="txt-slide-title regular-text code" />
									<span class="description"><?php _e( 'Enter Title', 'wen-logo-slider' ); ?></span>
								</div>
								<div class="wls-form-row">
									<i class="dashicons dashicons-admin-site"></i>

									<input type="text" name="slide_url[]" value="<?php echo esc_url( $slide['url'] ); ?>" placeholder="<?php _e( 'Enter URL', 'wen-logo-slider' ); ?>" class="txt-slide-url regular-text code" />
									<span class="description"><?php _e( 'Enter URL', 'wen-logo-slider' ); ?></span>
								</div>
								<div class="wls-form-row">
									<i class="dashicons dashicons-share-alt2"></i>
									<select name="slide_new_window[]" class="wls-choosen">
										<option value="yes" <?php selected( $slide['slide_new_window'], 'yes' ); ?>><?php _e( 'Yes', 'wen-logo-slider' ); ?></option>
										<option value="no" <?php selected( $slide['slide_new_window'], 'no' ); ?>><?php _e( 'No', 'wen-logo-slider' ); ?></option>
									</select>
									<span class="description"><?php _e( 'Open in new window', 'wen-logo-slider' ); ?></span>

								</div>
							</div>
						</div> <!-- .slide-item-wrap -->
					</div><!-- /.ws-logo-slider-pro -->
			 	<?php $count++; endforeach ?>

			 <?php endif ?>

		</div><!-- #main-slides-list-wrap -->
		<p><input type="button" value="<?php  esc_attr( _e( 'Add New Slide', 'wen-logo-slider' ) ); ?>" class="button button-primary wls-select-img" /></p>