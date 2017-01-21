<?php

class Cpl_acf_support {

    function __construct() {

        add_action('wp_ajax_cpl_acf_keys', array($this, 'cpl_acf_keys'));
        add_action('wp_ajax_nopriv_cpl_acf_keys', array($this, 'cpl_acf_keys'));
    }

    function cpl_acf_keys() {

        $ptype = $_POST['ptype'];

        $cpl_key = array();
        $args = array(
            'post_type' => 'acf',
            'posts_per_page' => -1,
        );
        $query = new WP_Query($args);

        if ($query->have_posts()) :
            while ($query->have_posts()) :
                $query->the_post();

                global $post;


                $groupID = $post->ID;

                $type = get_post_meta($groupID, 'rule', true);

                if ($type['operator'] == '==' && $type['param'] == 'post_type' && $type['value'] == $ptype) {

                    $custom_field_keys = get_post_custom_keys($groupID);
                    $items = count($custom_field_keys);

                    if (!empty($custom_field_keys)) {
                        foreach ($custom_field_keys as $key => $fieldkey) {
                            if (stristr($fieldkey, 'field_')) {
                                $field = get_field_object($fieldkey, $groupID);

                                $field_key = $field['name'];

                                $cpl_key[] = '%%acf_' . $field_key . '%%';
                            }
                        }
                    }
                }

            endwhile;
        endif;
        if (!empty($cpl_key)) {
            $output = '<h4>Available Acf Shortcode</h4>';
            foreach ($cpl_key as $value) {
                $output.= '<input type="text" readonly="readonly" value="' . $value . '" />';
            }
            echo $output;
        }
        die();
    }

}

$cpl_acf_support = new Cpl_acf_support();
