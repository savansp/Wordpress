<?php

class Cpl_post_type {

    function __construct() {


        add_action('add_meta_boxes', array($this, 'add_cpl_post_type_metaboxes'));
        add_action('save_post', array($this, 'cpl_post_type_save_meta'), 1, 2);

        add_action('admin_print_scripts-post-new.php', array($this, 'cpl_post_type_admin_script'), 11);
        add_action('admin_print_scripts-post.php', array($this, 'cpl_post_type_admin_script'), 11);
    }

    function cpl_post_type_admin_script() {
        global $post_type;
        if ('cpl_post_type' == $post_type)
            wp_enqueue_script('cpl-post_type_validation', plugins_url('cpl/assets/js/post_type_validation.js'));
    }

    function add_cpl_post_type_metaboxes() {
        add_meta_box('cpl_post_type_box', 'Post Type Detail', array($this, 'cpl_post_type_box'), 'cpl_post_type', 'normal', 'high');
    }

    function cpl_post_type_box() {
        global $post;


        $cpl_post_type_icon = get_post_meta($post->ID, 'cpl_post_type_icon', true);
        $cpl_post_type_taxonomy = get_post_meta($post->ID, 'cpl_post_type_taxonomy', true);
        
        ?>      

        <input type="hidden" name="cpl_p_noncename" id="cpl_p_noncename" value="<?php echo wp_create_nonce(plugin_basename(__FILE__)); ?>" />

        <div class="row cpptype">



            <div class="row">
                <span id="here">
                    <?php
                    $cpl_post_type_taxonomy = get_post_meta($post->ID, 'cpl_post_type_taxonomy', true);

                    $c = 0;
                    if (isset($cpl_post_type_taxonomy) && !empty($cpl_post_type_taxonomy) && count($cpl_post_type_taxonomy) > 0) {
                        foreach ($cpl_post_type_taxonomy as $tax) {
                            if (isset($tax['cpl_post_type_taxonomy'])) {
                                printf('<div class="col-1"><label> Taxonomy : </label><input type="text" name="cpl_post_type_taxonomy[%1$s]" value="%2$s" /> <span class="remove dashicons dashicons-no">%3$s</span></div>', $c, $tax, __(''));
                                $c = $c + 1;
                            }
                        }
                    }
                    ?>
                </span>
                <div class="col-1 action"><span class="add page-title-action"><?php _e('Add Taxonomy'); ?></span></div>

            </div>
            <hr>
            <div class="col-1 ri8">
                <h3>Icon </h3>
                <div class="icns">

                    <?php
                    if (!isset($cpl_post_type_icon) || $cpl_post_type_icon == '') {
                        $cpl_post_type_icon = 'dashicons-admin-post';
                    }
                    $arr = get_option('cpl_deshicons');
                    foreach ($arr as $value) {
                        $selected = ($value == $cpl_post_type_icon) ? 'checked' : ''
                        ?>
                        <div class="block"><input type="radio" name="cpl_post_type_icon" value="<?php echo $value; ?>" id="<?php echo $value; ?>" <?php echo $selected; ?>><label for="<?php echo $value; ?>"><span class="dashicons <?php echo $value; ?>"></span></label></div>
                                <?php
                            }
                            ?>

                </div>
            </div>

        </div>

        <?php
    }

    function cpl_post_type_save_meta($post_id, $post) {

        if (isset($_POST['cpl_p_noncename'])) {
            if (!wp_verify_nonce($_POST['cpl_p_noncename'], plugin_basename(__FILE__))) {
                return $post->ID;
            }

            if (!current_user_can('edit_post', $post->ID))
                return $post->ID;

            $cpl_meta['cpl_post_type_taxonomy_slug'] = array();
            
            foreach ($_POST['cpl_post_type_taxonomy'] as $value) {
                
                $tmp = sanitize_title_with_dashes($value);
                
                $cpl_meta['cpl_post_type_taxonomy_slug'][$value] = $tmp;
                
            }


            $cpl_meta['cpl_post_type_taxonomy'] = $_POST['cpl_post_type_taxonomy'];
            $cpl_meta['cpl_post_type_icon'] = $_POST['cpl_post_type_icon'];

            foreach ($cpl_meta as $key => $value) {
                if ($post->post_type == 'revision')
                    return;

                if (get_post_meta($post->ID, $key, FALSE)) {
                    update_post_meta($post->ID, $key, $value);
                } else {
                    add_post_meta($post->ID, $key, $value);
                }
                if (!$value)
                    delete_post_meta($post->ID, $key);
            }
        }
    }

}

$cpl_post_type = new Cpl_post_type();