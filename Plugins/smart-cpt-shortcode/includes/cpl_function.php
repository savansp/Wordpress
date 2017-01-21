<?php

class Cpl_function {

    function cpl_contex_replacement($html, $replace_arr, $postid) {

        foreach ($replace_arr as $key => $value) {

            $html = str_replace($key, $value, $html);
        }
        if (strpos($html, '%%tax_') !== false) {
            $html = $this->cpl_tax_shortcode_replacement($html, $postid);
        }
        if (class_exists('acf') && strpos($html, '%%acf_') !== false) {
            $html = $this->cpl_acf_shortcode_replacement($html, $postid);
        }

        return $html;
    }

    function cpl_tax_shortcode_replacement($output_row, $postid) {

        $m = array();
       

        preg_match_all('@%%tax_(.+?)%%@', $output_row, $m);

        $tax_row_arr = array_unique($m[0]);
        $tax_cnt_arr = array_unique($m[1]);

        $tax_merge = array_combine($tax_row_arr, $tax_cnt_arr);


        foreach ($tax_merge as $key => $value) {

            $terms = wp_get_post_terms($postid, $value);
           
            $tax_name = get_taxonomy( $value )->labels->name;
            
             $field_value = '<ul><h5 class="tax_title">'.$tax_name.' </h5>';

            foreach ($terms as $term_obj) {
                
                $term_name = $term_obj->name;
                $term_link = get_term_link($term_obj->term_id);

                $field_value .= '<li><a href="' . esc_url($term_link) . '">' . $term_name . '</a></li>';

                
            }
            $field_value .='</ul>';
            $output_row = str_replace($key, $field_value, $output_row);
        }
        return $output_row;
    }

    function cpl_acf_shortcode_replacement($output_row, $postid) {

        $m = array();
        $output = '';
        preg_match_all('@%%acf_(.+?)%%@', $output_row, $m);

        $acf_row_arr = array_unique($m[0]);
        $acf_cnt_arr = array_unique($m[1]);

        $acf_merge = array_combine($acf_row_arr, $acf_cnt_arr);


        foreach ($acf_merge as $key => $value) {
            $obj = get_field_object($value, $postid);

            $field_value = $this->cpl_acf_type_identify($obj);
            $output_row = str_replace($key, $field_value, $output_row);
        }

        return $output_row;
    }

    function cpl_acf_type_identify($obj) {

        if ($obj['type'] == 'textarea' || $obj['type'] == 'number' || $obj['type'] == 'email' || $obj['type'] == 'password' || $obj['type'] == 'wysiwyg' || $obj['type'] == 'select' || $obj['type'] == 'radio' || $obj['type'] == 'page_link' || $obj['type'] == 'color_picker' || $obj['type'] == 'true_false') {

            return $obj['value'];
        } elseif ($obj['type'] == 'image' || $obj['type'] == 'file') {

            return $obj['value']['url'];
        } elseif ($obj['type'] == 'checkbox') {

            $output = '';
            if (!empty($obj['value'])) {
                foreach ($obj['value'] as $single_item) {
                    $output.= '<li>' . $single_item . '</li>';
                }
            }
            return $output;
        } elseif ($obj['type'] == 'user') {

            $output = '';

            if (!empty($obj['value'])) {
                if (isset($obj['value'][0])) {
                    foreach ($obj['value'] as $single_user) {

                        $output.= '<li>' . '<span>' . $single_user['user_avatar'] . '</span>' . $single_user['display_name'] . '</li>';
                    }
                } else {
                    $output = '<li>' . '<span>' . $obj['value']['user_avatar'] . '</span>' . $obj['value']['display_name'] . '</li>';
                }
            }
            return $output;
        } elseif ($obj['type'] == 'taxonomy') {

            $tax = $obj['taxonomy'];
            $output = '';
            if (!empty($obj['value'])) {

                foreach ($obj['value'] as $trm_single) {

                    $term_obj = get_term_by('id', $trm_single, $tax);
                    $term_name = $term_obj->name;
                    $term_link = get_term_link($trm_single);

                    $output.= '<li><a href="' . esc_url($term_link) . '">' . $term_name . '</a></li>';
                }
            }

            return $output;
        } elseif ($obj['type'] == 'date_picker') {

            $date_format = $obj['date_format'];
            $display_format = $obj['display_format'];
            $value = $obj['value'];



            if (strpos($display_format, 'dd') !== false) {
                $display_format = str_replace('dd', 'd', $display_format);
            }

            if (strpos($display_format, 'mm') !== false) {
                $display_format = str_replace('mm', 'm', $display_format);
            }
            if (strpos($display_format, 'yy') !== false) {
                $display_format = str_replace('yy', 'Y', $display_format);
            }
            if (strpos($display_format, 'YY') !== false) {
                $display_format = str_replace('YY', 'Y', $display_format);
            }
            if (strpos($display_format, 'DD') !== false) {
                $display_format = str_replace('DD', 'D', $display_format);
            }
            if (strpos($display_format, 'MM') !== false) {
                $display_format = str_replace('MM', 'M', $display_format);
            }


            $date = strtotime($value);

            $output = date('M-d-Y', $date);

            return $output;
        } else {

            return '';
        }
    }

}

/* $cpl_function = new Cpl_function(); */
?>