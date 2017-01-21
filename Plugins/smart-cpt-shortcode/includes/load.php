<?php

class Cpl_load {

    function __construct() {

        add_action('init', array($this, 'cpl_post_reg'));
        add_action('init', array($this, 'cpl_post_type_reg'));

        add_filter('manage_cpl_posts_columns', array($this, 'set_custom_edit_cpl_columns'));
        add_action('manage_cpl_posts_custom_column', array($this, 'custom_cpl_column'), 10, 2);

        require_once CPLPATH . 'admin/class.cpl_post_type.php';
        add_action('init', array($this, 'populate_post_type'));
    }

    function cpl_post_reg() {

        $labels = array(
            'name' => _x('CPL', 'post type general name', 'your-plugin-textdomain'),
            'singular_name' => _x('CPL List', 'post type singular name', 'your-plugin-textdomain'),
            'menu_name' => _x('CPL', 'admin menu', 'your-plugin-textdomain'),
            'name_admin_bar' => _x('CPL', 'add new on admin bar', 'your-plugin-textdomain'),
            'add_new' => _x('Add New Shortcode', 'book', 'your-plugin-textdomain'),
            'add_new_item' => __('Add New Shortcode', 'your-plugin-textdomain'),
            'new_item' => __('New CPL', 'your-plugin-textdomain'),
            'edit_item' => __('Edit CPL', 'your-plugin-textdomain'),
            'view_item' => __('View CPL', 'your-plugin-textdomain'),
            'all_items' => __('All Shortcode', 'your-plugin-textdomain'),
            'search_items' => __('Search CPL', 'your-plugin-textdomain'),
            'parent_item_colon' => __('Parent CPL:', 'your-plugin-textdomain'),
            'not_found' => __('No CPL found.', 'your-plugin-textdomain'),
            'not_found_in_trash' => __('No CPL found in Trash.', 'your-plugin-textdomain')
        );

        $args = array(
            'labels' => $labels,
            'description' => __('Custom Post type Listing', 'your-plugin-textdomain'),
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'cpl'),
            'capability_type' => 'post',
            'has_archive' => false,
            'hierarchical' => false,
            'menu_position' => null,
            'menu_icon' => 'dashicons-editor-kitchensink',
            'supports' => array('title')
        );

        register_post_type('cpl', $args);
    }

    function set_custom_edit_cpl_columns($columns) {
        $columns = array(
            'cb' => '<input type="checkbox" />',
            'title' => __('List Title'),
            'shortcode' => __('Shortcode'),
            'date' => __('Date')
        );

        return $columns;
    }

    function custom_cpl_column($column, $post_id) {
        switch ($column) {

            case 'shortcode' :

                global $post;
                echo '<input type="text" name="main_shortcode" value="[cpl list=' . $post->ID . ']" readonly="readonly" class="main_shortcode list">';

                break;
        }
    }

    function cpl_post_type_reg() {
        register_post_type('cpl_post_type', array(
            'labels' => array(
                'name' => __('Post Type'),
                'singular_name' => __('Post Type'),
                'menu_name' => _x('Post Types', 'admin menu', 'your-plugin-textdomain'),
                'name_admin_bar' => _x('Post Types', 'add new on admin bar', 'your-plugin-textdomain'),
                'add_new' => _x('Add New Post Type', 'Post Type', 'your-plugin-textdomain'),
                'add_new_item' => __('Add New Post Type', 'your-plugin-textdomain'),
                'new_item' => __('New Post Type', 'your-plugin-textdomain'),
                'edit_item' => __('Edit Post Type', 'your-plugin-textdomain'),
                'view_item' => __('View Post Type', 'your-plugin-textdomain'),
                'all_items' => __('Post Types', 'your-plugin-textdomain')
            ),
            'public' => true,
            'has_archive' => true,
            'show_in_menu' => 'edit.php?post_type=cpl',
            'supports' => array('title')
                )
        );
    }

    function populate_post_type() {

        $args = array(
            'post_type' => 'cpl_post_type',
            'posts_per_page' => 15,
        );
        $query = new WP_Query($args);

        if ($query->have_posts()) :
            while ($query->have_posts()) :
                $query->the_post();

                global $post;

                $cpl_post_type_icon = get_post_meta($post->ID, 'cpl_post_type_icon', true);
                $cpl_post_type_taxonomy_slug = get_post_meta($post->ID, 'cpl_post_type_taxonomy_slug', true);

                $slug = $post->post_name;
                $title = get_the_title($post->ID);
                
                if($slug!='' && $title!=''){


                $p_type = array(
                    "slug" => $slug,
                    "name" => $title,
                    "singular_name" => $title,
                    "is_public" => true,
                    "has_archive" => true,
                    'menu_icon' => $cpl_post_type_icon,
                );
                $tax = $cpl_post_type_taxonomy_slug;
                

                $var = new Cpl_custom_posts($p_type, $tax);

                }
            endwhile;
        endif;
    }

}

$cpl_load = new Cpl_load();
