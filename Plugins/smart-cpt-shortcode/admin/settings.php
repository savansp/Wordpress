<?php

class Cpl_settings {

    function __construct() {

        add_action('admin_menu', array($this, 'cpl_setting_page'));
        add_action( 'admin_init', array($this, 'register_cpl_settings') );
    }

    function cpl_setting_page() {
        add_submenu_page('edit.php?post_type=cpl', 'Custom Post Type Admin', 'CPL Settings', 'edit_posts', basename(__FILE__), array($this, 'cpl_settings'));
        
    }
    function register_cpl_settings() {
	register_setting( 'cpl-settings-group', 'cpl_default_tpl' );
	register_setting( 'cpl-settings-group', 'cpl_inline_style' );
}
    
    function cpl_settings(){
       
        ?>
<div class="wrap cpl_setting">
<h1>CPL Settings</h1>

<form method="post" action="options.php">
    <?php settings_fields( 'cpl-settings-group' ); ?>
    <?php do_settings_sections( 'cpl-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Default Template</th>
        <td><textarea name="cpl_default_tpl" id="cpl_default_tpl"><?php echo get_option('cpl_default_tpl',true);?></textarea></td>
        </tr>
         
        <tr valign="top">
        <th scope="row">Default Inline Style</th>
        <td><textarea name="cpl_inline_style" id="cpl_inline_style"><?php echo get_option('cpl_inline_style',true);?></textarea></td>
        </tr>
        

    </table>
    
    <?php submit_button(); ?>

</form>
</div>
<?php
    }

}

$cpl_settings = new Cpl_settings();




