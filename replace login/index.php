<?php
/*
 * Plugin name: Custom LA login asdasd
 * Author: LAnet Team
 * Description: Replace wp-admin with custom login screen
 * Tags: wp-admin relace, wp admin replace, change login screen, customize wp-admin
 */
 require_once 'login-class.php';
  $obj = new C_login();
 register_activation_hook(__FILE__,array($obj,'activate'));
 register_deactivation_hook(__FILE__,array($obj,'deactivate'));

 /*** Add Setting page ***/
 add_action( 'admin_menu', array($obj,'rpl_option_page') );
 
 /*** Redirect when wp-admin HIT ***/
 //add_filter( 'login_url', array($obj,'rpl_login_page'), 10, 2 );
add_action( 'admin_footer', 'media_selector_print_scripts' );
add_action('admin_init','add_cu_role');
function add_cu_role(){
    
}
function media_selector_print_scripts() {

	$my_saved_attachment_post_id = get_option( 'rpl_logo_id', 0 );

	?><script type='text/javascript'>

		jQuery( document ).ready( function( $ ) {

			// Uploading files
			var file_frame;
			var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id
			var set_to_post_id = <?php echo $my_saved_attachment_post_id; ?>; // Set this
                           console.log(set_to_post_id);
			jQuery('#upload_image_button').on('click', function( event ){

				event.preventDefault();

				// If the media frame already exists, reopen it.
				if ( file_frame ) {
					// Set the post ID to what we want
					file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
					// Open frame
					file_frame.open();
					return;
				} else {
					// Set the wp.media post id so the uploader grabs the ID we want when initialised
					wp.media.model.settings.post.id = set_to_post_id;
				}

				// Create the media frame.
				file_frame = wp.media.frames.file_frame = wp.media({
					title: 'Select a image to upload',
					button: {
						text: 'Use this image',
					},
					multiple: false	// Set to true to allow multiple files to be selected
				});

				// When an image is selected, run a callback.
				file_frame.on( 'select', function() {
					// We set multiple to false so only get one image from the uploader
					attachment = file_frame.state().get('selection').first().toJSON();

					// Do something with attachment.id and/or attachment.url here
					$( '#image-preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
                                        alert(attachment.id);
					$( '#image_attachment_id' ).val( attachment.id );

					// Restore the main post ID
					wp.media.model.settings.post.id = wp_media_post_id;
				});

					// Finally, open the modal
					file_frame.open();
			});

			// Restore the main ID when the add media button is pressed
			jQuery( 'a.add_media' ).on( 'click', function() {
				wp.media.model.settings.post.id = wp_media_post_id;
			});
		});

	</script><?php

}