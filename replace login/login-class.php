<?php
/**
 * Login class with activate methods
 */

class C_login{
	public $cu_page=0;
        public $arg =array();
        
        /**
         * Activation Hook To Add New Login Page
         */
        function activate(){
            $arg = array(
                    'post_title' => 'rpl-admin',
                    'post_type'=>'page', 'comments',
                    'comment_status' => 'closed',
                    'ping_status' => 'closed',
                    'post_status' => 'publish'
                );
            
            /*** catch the redundant output ***/
            ob_start();
            $this->cu_page = wp_insert_post($arg);
            update_post_meta($this->cu_page,'from_rpl_admin_replace',1);
            ob_flush();

        }
        
        /**
         * Remove Added page when Plugin is deactivated
         */
        function deactivate(){
            $data = get_posts(array(
                'post_type'=>'page',
                'posts_per_page'=>-1
            ));
            foreach ($data as $key){
                if(get_post_meta($key->ID,'from_rpl_admin_replace',true) > 0)
                {
                    wp_delete_post($key->ID,true);
                }
            }
        }
        
        /**
         * Add setting page to show settings for plugin
         */
        function rpl_option_page() {
            add_options_page( 
                    'Replace wp-admin',
                    'Change wp-admin',
                    'manage_options',
                    'replace-wp-admin',
                    array($this, 'rpl_admin_settings')
            );
        }
        
        /**
         * Set the login URL
         */
        function rpl_login_page( $login_url, $redirect ) {
            return home_url( '/my-login-page/?redirect_to=' . $redirect );
        }
        
        /**
         * Set HTML setting page
         */
        function rpl_admin_settings(){
           if ( isset( $_POST['submit_image_selector'] )) :
		//update_option( 'media_selector_attachment_id', absint( $_POST['image_attachment_id'] ) );
           
           /*** set ID for plugin reference***/
               
                   update_option( 'rpl_logo_id', absint( $_POST['image_attachment_id'] ) );
              
                
                update_option('rpl_input_field_css', strip_tags($_POST['input_field_css']));
                update_option('rpl_login_field_css', strip_tags($_POST['login_button_css']));
            
	endif;

	wp_enqueue_media();

	?>
        <h4>Set logo</h4>
        <form method='post'>
		<div class='image-preview-wrapper'>
			<img id='image-preview' src='<?php echo wp_get_attachment_url( get_option( 'rpl_logo_id' ) ); ?>' height='100'>
		</div>
		<input id="upload_image_button" type="button" class="button" value="<?php _e( 'Upload image' ); ?>" />
		<input type='hidden' name='image_attachment_id' id='image_attachment_id' value='<?php echo (get_option( 'rpl_logo_id' )) ? get_option( 'rpl_logo_id' ) : 0 ; ?>'>
		<input type="submit" name="submit_image_selector" value="Save" class="button-primary">
                <div>
                    <div>
                        <span>Apply custom css for input field</span>
                        
                        <p>
                            <textarea  name="input_field_css" class="input_field_css" id="input_field" placeholder="just write the property">
                                <?php echo (get_option('rpl_input_field_css')) ? get_option('rpl_input_field_css') : ""; ?>
                            </textarea>
                        </p>
                        <span>Apply custom css for Button</span>
                        
                        <p>
                            <textarea  name="login_button_css" class="login_button_css" id="input_login" placeholder="just write the property">
                                <?php echo (get_option('rpl_login_field_css')) ? get_option('rpl_login_field_css') : ""; ?>
                            </textarea>
                        </p>
                    </div>
                </div> 
	</form>
           
            <?php

        }
}
