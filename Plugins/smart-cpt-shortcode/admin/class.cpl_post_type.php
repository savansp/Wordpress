<?php

class Cpl_custom_posts {

    function __construct($p_type, $tax) {

        $this->p_type = $p_type;
        $this->tax = $tax;

        $this->add_custom_post_type();
        $this->add_custom_post_taxonomy();
    }

    function add_custom_post_type() {

        register_post_type($this->p_type['slug'], array(
            'labels' => array(
                'name' => __($this->p_type['name']),
                'singular_name' => __($this->p_type['singular_name'])
            ),
            'public' => $this->p_type['is_public'],
            'has_archive' => $this->p_type['has_archive'],
            'menu_icon' => $this->p_type['menu_icon'],
            'supports' => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'page-attributes')
                )
        );
    }

    function add_custom_post_taxonomy() {

        if (is_array($this->tax)) {

            foreach ($this->tax as $key => $value) {

                register_taxonomy(
                        $value, $this->p_type['slug'], array(
                    'label' => __($key),
                    'public' => true,
                    'hierarchical' => true,
                        )
                );
            }
        }
    }

}

?>