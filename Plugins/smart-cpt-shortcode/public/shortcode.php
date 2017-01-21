<?php

class Cpl_shortcode {

    function __construct() {

        global $cpl_function;
        $cpl_function = new Cpl_function();

        add_shortcode('cpl', array($this, 'result'));
    }

    function result($atts) {

        global $cpl_function;

        if (is_array($atts) && array_key_exists('list', $atts)) {
            $cpl_id = $atts['list'];
            $object_return = false;
        } else {
            $cpl_id = $atts;
            $object_return = true;
        }

        $output = '';
        $output_final = '';

        $cpl_data = get_metadata('post', $cpl_id);
        
        if(!empty($cpl_data)){

        $view_contex = $cpl_data['view_contex'][0];
        $ptype = $cpl_data['ptype'][0];
        $plimit = $cpl_data['plimit'][0];
        $cpl_style = $cpl_data['cpl_style'][0];
        $tax = json_decode($cpl_data['tax'][0], true);

        $output.= '<style>' . $cpl_style . '</style>';

        $full = array();
        $i = 0;

        $full['relation'] = 'AND';
        if (isset($tax) && !empty($tax)) {
            foreach ($tax as $key => $value) {

                $taxTerm['taxonomy'] = $key;
                $taxTerm['field'] = 'slug';
                $taxTerm['terms'] = $value;

                $full[$i] = $taxTerm;

                if (in_array($key . '-all', $value)) { /*                 * *********  ALL TAXONOMY  ***************** */

                    unset($full[$i]);

                    $i--;
                }
                $i++;
            }
        }

        $args = array(
            'post_type' => $ptype,
            'posts_per_page' => $plimit,
        );
        $args['tax_query'] = $full;


        $query = new WP_Query($args);

        if ($object_return === true) {
            return $query->posts;
        }

        if ($query->have_posts()) :
            while ($query->have_posts()) :
                $query->the_post();

                global $post;

                $feature_thumb_tmp = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'thumbnail');
                $feature_full_tmp = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');

                $title = get_the_title($post->ID);
                $link = get_permalink($post->ID);
                $content = get_the_content($post->ID);
                $excerpt = get_the_excerpt($post->ID);
                $date = get_the_date('', $post->ID) . ' @ ' . get_the_time('', $post->ID);

                $feature_thumb = (isset($feature_thumb_tmp[0]) && $feature_thumb_tmp[0] != '') ? $feature_thumb_tmp[0] : plugins_url('../assets/images/default.png', __FILE__);
                $feature_full = (isset($feature_full_tmp[0]) && $feature_full_tmp[0] != '') ? $feature_full_tmp[0] : plugins_url('../assets/images/default.png', __FILE__);

                $replace_arr = array(
                    '%%title%%' => $title,
                    '%%content%%' => $content,
                    '%%excerpt%%' => $excerpt,
                    '%%page_link%%' => $link,
                    '%%date%%' => $date,
                    '%%feature_thumb_url%%' => $feature_thumb,
                    '%%feature_original_url%%' => $feature_full
                );

                $output .= $cpl_function->cpl_contex_replacement($view_contex, $replace_arr, $post->ID);

            endwhile;
        endif;

        preg_match_all('@%%(.+?)%%@', $output, $m);


        foreach ($m[0] as $dummy) {
            $output = str_replace($dummy, '', $output);
        }

        return $output;
    }
    }

}
