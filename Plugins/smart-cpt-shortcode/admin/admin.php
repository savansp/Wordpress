<?php

class Cpl_admin {

    function __construct() {

        require_once CPLPATH . 'admin/cpl_post_type.php';
        require_once CPLPATH . 'admin/settings.php';

        if (class_exists('acf')) {
            require_once CPLPATH . 'admin/acf_support.php';
        }

        add_action('add_meta_boxes', array($this, 'add_cpl_metaboxes'));
        add_action('save_post', array($this, 'cpl_save_meta'), 1, 2);

        add_action('wp_ajax_cpl_tax_dropdown', array($this, 'cpl_tax_dropdown'));
        add_action('wp_ajax_nopriv_cpl_tax_dropdown', array($this, 'cpl_tax_dropdown'));

        add_action('wp_ajax_cpl_tax_shortcode', array($this, 'cpl_tax_shortcode'));
        add_action('wp_ajax_nopriv_cpl_tax_shortcode', array($this, 'cpl_tax_shortcode'));
    }

    function add_cpl_metaboxes() {
        add_meta_box('cpl_listing_filter', 'Filter Listing', array($this, 'cpl_listing_filter'), 'cpl', 'normal', 'high');
        add_meta_box('cpl_view_contex', 'HTML View Contex', array($this, 'cpl_view_contex'), 'cpl', 'normal', 'default');
        add_meta_box('cpl_style', 'Inline CSS', array($this, 'cpl_style'), 'cpl', 'normal', 'low');
        add_meta_box('cpl_output_shortcode', 'Output List Shortcode', array($this, 'cpl_output_shortcode'), 'cpl', 'side', 'high');
        add_meta_box('cpl_helper', 'Available Contex Shortcode', array($this, 'cpl_helper'), 'cpl', 'side', 'low');
    }

    function cpl_save_meta($post_id, $post) {

        if (isset($_POST['cpl_noncename'])) {
            if (!wp_verify_nonce($_POST['cpl_noncename'], plugin_basename(__FILE__))) {
                return $post->ID;
            }

            if (!current_user_can('edit_post', $post->ID))
                return $post->ID;

            $cpl_meta['ptype'] = $_POST['ptype'];
            $cpl_meta['plimit'] = $_POST['plimit'];
            $cpl_meta['view_contex'] = $_POST['view_contex'];
            $cpl_meta['cpl_style'] = $_POST['cpl_style'];
            $cpl_meta['tax'] = json_encode($_POST['tax']);

            foreach ($cpl_meta as $key => $value) {
                if ($post->post_type == 'revision')
                    return;
                $value = implode(',', (array) $value);
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

    function cpl_listing_filter() {
        global $post;
        global $wp_post_types;

        $ptype = get_post_meta($post->ID, 'ptype', true);

        if (get_post_meta($post->ID, 'plimit', FALSE)) {
            $plimit = get_post_meta($post->ID, 'plimit', true);
        } else {
            $plimit = get_option('posts_per_page');
        }

        $tax = json_decode(get_post_meta($post->ID, 'tax', true), true);

        $output = '<input type="hidden" name="cpl_noncename" id="cpl_noncename" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />';
        $output.= '<input type="hidden" name="cpl_post_id" id="cpl_post_id" value="' . $post->ID . '">';

        $output.= '<div class="row"><div class="col-2"><label for="ptype">Post Type : </label>';
        $output.= '<select name="ptype" id="ptype" class="form-cntrl">';
        $output.= '<option value="0">--Select Post Type--</option>';

        foreach ($wp_post_types as $key => $value) {
            if ($key != 'attachment' && $key != 'revision' && $key != 'nav_menu_item' && $key != 'cpl' && $key != 'cpl_post_type') {
                $selected = ($key == $ptype) ? 'selected' : '';
                $output.= '<option value="' . $key . '" ' . $selected . '>' . $value->label . '</option>';
            }
        }
        $output.= '</select></div>';

        $output.= '<div class="col-2"><label for="plimit">Post Limit : </label>';
        $output.= '<input type="text" name="plimit" value="' . $plimit . '" id="plimit" class="form-cntrl"/>';
        $output.= '</div></div>';

        $opt_tmp = '';

        $taxonomy_objects = get_object_taxonomies($ptype, 'names');

        foreach ($taxonomy_objects as $value) {

            $terms = get_terms(array(
                'taxonomy' => $value,
                'hide_empty' => false,
            ));

            $opt_tmp .= '<div class="col-1"><label>' . $value . ' : </label><select name="tax[' . $value . '][]" class="form-cntrl select2" multiple="multiple">';

            $select_all = (!empty($tax) && array_key_exists($value, $tax) && in_array($value . '-all', $tax[$value])) ? 'selected' : '';

            $opt_tmp .= '<option value="' . $value . '-all" ' . $select_all . ' >All</option>';

            foreach ($terms as $term) {

                if (!empty($tax) && array_key_exists($value, $tax) && in_array($term->slug, $tax[$value])) {
                    $selected = 'selected';
                } else {
                    $selected = '';
                }

                $opt_tmp .= '<option value=' . $term->slug . ' ' . $selected . ' >' . $term->name . '</option>';
            }

            $opt_tmp .= '</select></div>';
        }

        $output.= '<h4>Texonomies : </h4><div class="row" id="cpl_tex">' . $opt_tmp . '</div>';

        echo $output;
    }

    function cpl_tax_dropdown() {

        $ptype = $_POST['ptype'];
        $pid = $_POST['pid'];

        $tax = json_decode(get_post_meta($pid, 'tax', true), true);

        $taxonomy_objects = get_object_taxonomies($ptype, 'names');

        foreach ($taxonomy_objects as $value) {

            $terms = get_terms(array(
                'taxonomy' => $value,
                'hide_empty' => false,
            ));

            $output .= '<div class="col-1"><label>' . $value . ' : </label><select name="tax[' . $value . '][]" class="form-cntrl select2" multiple="multiple">';


            $select_all = (!empty($tax) && array_key_exists($value, $tax) && in_array($value . '-all', $tax[$value])) ? 'selected' : '';

            $output .= '<option value="' . $value . '-all" ' . $select_all . ' >All</option>';

            foreach ($terms as $term) {


                if (array_key_exists($value, $tax) && in_array($term->slug, $tax[$value])) {
                    $selected = 'selected';
                } else {
                    $selected = '';
                }

                $output .= '<option value=' . $term->slug . ' ' . $selected . ' >' . $term->name . '</option>';
            }

            $output .= '</select></div>';
        }

        echo $output;
        die();
    }

    function cpl_view_contex() {
        global $post;

        if (get_post_meta($post->ID, 'view_contex', true) && get_post_meta($post->ID, 'view_contex', true) != '') {
            $view_contex = get_post_meta($post->ID, 'view_contex', true);
        } else {
            $view_contex = get_option('cpl_default_tpl', true);
        }


        echo '<textarea name="view_contex" id="view_contex" class="form-cntrl">' . $view_contex . '</textarea>';
    }

    function cpl_style() {
        global $post;

        if (get_post_meta($post->ID, 'cpl_style', true) && get_post_meta($post->ID, 'cpl_style', true) != '') {
            $cpl_style = get_post_meta($post->ID, 'cpl_style', true);
        } else {
            $cpl_style = get_option('cpl_inline_style', true);
        }


        echo '<textarea name="cpl_style" id="cpl_style_inline" class="form-cntrl">' . $cpl_style . '</textarea>';
    }

    function cpl_helper() {


        echo '<div class="shrt-cd"><input type="text" readonly="readonly" value="%%title%%" />' .
        '<input type="text" readonly="readonly" value="%%content%%" />' .
        '<input type="text" readonly="readonly" value="%%excerpt%%" />' .
        '<input type="text" readonly="readonly" value="%%page_link%%" />' .
        '<input type="text" readonly="readonly" value="%%date%%" />' .
        '<input type="text" readonly="readonly" value="%%feature_thumb_url%%" />' .
        '<input type="text" readonly="readonly" value="%%feature_original_url%%" /><div id="cpl_tax_shortcode"></div><div id="acf_shortcode"></div></div>';
    }

    function cpl_output_shortcode() {
        global $my_admin_page;
        $screen = get_current_screen();

        if (is_admin() && ($screen->id == 'cpl')) {
            global $post;
            $id = $post->ID;

            $output = '<input type="text" name="main_shortcode" value="[cpl list=' . $id . ']" readonly="readonly" class="main_shortcode">';
            $output .= '<h4>PHP object (Array of Posts)</h4>';
            $output .= '<textarea name="main_shortcode_obj" readonly="readonly" class="main_shortcode" >if(class_exists("Cpl")){ &#013;&#010;$cpl = new Cpl(); &#013;&#010;$obj = $cpl->get_object->result(' . $id . '); &#013;&#010;}</textarea>';

            echo $output;
        }
    }

    function cpl_tax_shortcode() {
        $ptype = $_POST['ptype'];

        $cpl_tax_terms = array();
        $tax_name = get_object_taxonomies($ptype);

        foreach ($tax_name as $value) {

            $cpl_tax_terms[] = '%%tax_' . $value . '%%';
        }

        if (!empty($cpl_tax_terms)) {
            $output = '<h4>' . $ptype . ' Texonomy Shortcode</h4>';
            foreach ($cpl_tax_terms as $value) {
                $output.= '<input type="text" readonly="readonly" value="' . $value . '" />';
            }
            echo $output;
        }
        die();
    }

}

$cpl_admin = new Cpl_admin();




