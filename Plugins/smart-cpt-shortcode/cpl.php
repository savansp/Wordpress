<?php

/*
  Plugin Name: Custom Post type Listing
  Plugin URI: http://celestialdevs.com
  Description: Listing of Custom post type
  Version: 1.0
  Author: Code Addict
*/

define('CPLPATH', plugin_dir_path(__FILE__));

class Cpl {
    
    public $get_object;
    
    function __construct() {

        add_action('admin_print_styles', array($this, 'register_admin_styles'));
        add_action('admin_enqueue_scripts', array($this, 'register_admin_scripts'));

        add_action('wp_enqueue_scripts', array($this, 'register_plugin_styles'));
        add_action('wp_enqueue_scripts', array($this, 'register_plugin_scripts'));

        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
        register_uninstall_hook(__FILE__, 'uninstall');

        require_once CPLPATH . 'includes/cpl_function.php';
        require_once CPLPATH . 'includes/load.php';

        if (is_admin()) {
            require_once CPLPATH . 'admin/admin.php';
        } else {
            require_once CPLPATH . 'public/shortcode.php';
            
            $this->get_object = new Cpl_shortcode();
            
        }
    }

    public function register_admin_styles() {

        wp_enqueue_style('cpl-admin', plugins_url('cpl/assets/css/admin.css'));
        wp_enqueue_style('cpl-select2', plugins_url('cpl/assets/css/select2.min.css'));
        wp_enqueue_style('cpl-cdmrr', plugins_url('cpl/assets/css/codemrr.css'));
        wp_enqueue_style('cpl-codemrrtheme', plugins_url('cpl/assets/css/codemrrtheme.css'));
    }

    public function register_admin_scripts() {

        wp_enqueue_script('cpl-admin', plugins_url('cpl/assets/js/admin.js'));
        wp_enqueue_script('cpl-select2', plugins_url('cpl/assets/js/select2.min.js'));
        wp_enqueue_script('cpl-codemrr', plugins_url('cpl/assets/js/codemrr.js'));
        wp_enqueue_script('cpl-codemrr2', plugins_url('cpl/assets/js/codemrr2.js'));
        wp_enqueue_script('cpl-codemrr3', plugins_url('cpl/assets/js/codemrr3.js'));
    }

    public function register_plugin_styles() {

        wp_enqueue_style('cpl-plugin-styles', plugins_url('cpl/assets/css/display.css'));
    }

    public function register_plugin_scripts() {


        wp_enqueue_script('cpl-plugin-script', plugins_url('cpl/assets/js/display.js'));
    }

    public function activate($network_wide) {

        $cpl_default_tpl = '<div class="cpl_wrapper">
	<div class="cpl_thumb">
		<a href="%%page_link%%" class="cpl_link">
		<img src="%%feature_thumb_url%%"/></a>
	</div>
	<div class="cpl_cntent">
		<a href="%%page_link%%" class="cpl_link">
			<h2 class="cpl_title">%%title%%</h2>
		</a>
      <div class="cpl_meta"> %%date%%</div>
      <div class="cpl_tax"> 
       <!-- Taxonomy Shortcode -->
      </div>
     
		<div class="cpl_excerpt">%%excerpt%%</div>
	</div>
</div>';
        $cpl_inline_style = '.cpl_wrapper {
    border: 1px solid #c1c1c1;
    box-shadow: 4px 5px 0 0 #ddd;
    float: left;
    font-size: 13px;
    margin-bottom: 20px;
    padding: 10px;
  	width: 100%;
}
.cpl_thumb {
    width: 30%;
    float: left;
}
.cpl_thumb img {
    width: 100%;
   	max-height: 200px;
  	transition: all 0.2s ease 0s;
}
.cpl_wrapper:hover .cpl_thumb img {
    filter: grayscale(80%) hue-rotate(45deg);
}
.cpl_cntent {
    float: left;
    padding-left: 15px;
    width: calc(70% - 15px);
}
h2.cpl_title {
    margin-bottom: 10px;
    text-transform: capitalize;
}
.cpl_meta {
    color: #7d7d7d;
    font-style: italic;
    margin-bottom: 7px;
}
.cpl_tax li a {
    background: #ddd none repeat scroll 0 0;
    display: inline-block;
    list-style: outside none none;
    margin: 0 5px 5px 5px;
    padding: 2px 10px;
    text-decoration: none;
    text-transform: capitalize;
}
.cpl_tax li {
    display: inline-block;
    list-style: outside none none;
}
.cpl_tax > ul {
    margin-bottom: 10px;
  	margin-left: 0;
}
.cpl_tax > ul > h5 {
    display: inline-block;
    float: left;
    font-size: 15px;
    line-height: 1.6;
    margin: 0 !important;
}
@media only screen and (max-width:800px){
    .cpl_thumb {
    	width: 100%;
	}
  	.cpl_thumb img {
    	max-height: initial;
	}
  	.cpl_cntent {
    	float: left;
    	padding: 5px 0 0;
	   	width: 98%;
	}
}';
        $deshicons = array("dashicons-menu", "dashicons-admin-site", "dashicons-dashboard", "dashicons-admin-post", "dashicons-admin-media", "dashicons-admin-links", "dashicons-admin-page", "dashicons-admin-comments", "dashicons-admin-appearance", "dashicons-admin-plugins", "dashicons-admin-users", "dashicons-admin-tools", "dashicons-admin-settings", "dashicons-admin-network", "dashicons-admin-home", "dashicons-admin-generic", "dashicons-admin-collapse", "dashicons-filter", "dashicons-admin-customizer", "dashicons-admin-multisite", "dashicons-welcome-write-blog", "dashicons-welcome-add-page", "dashicons-welcome-view-site", "dashicons-welcome-widgets-menus", "dashicons-welcome-comments", "dashicons-welcome-learn-more", "dashicons-format-aside", "dashicons-format-image", "dashicons-format-gallery", "dashicons-format-video", "dashicons-format-status", "dashicons-format-quote", "dashicons-format-chat", "dashicons-format-audio", "dashicons-camera", "dashicons-images-alt", "dashicons-images-alt2", "dashicons-video-alt", "dashicons-video-alt2", "dashicons-video-alt3", "dashicons-media-archive", "dashicons-media-audio", "dashicons-media-code", "dashicons-media-default", "dashicons-media-document", "dashicons-media-interactive", "dashicons-media-spreadsheet", "dashicons-media-text", "dashicons-media-video", "dashicons-playlist-audio", "dashicons-playlist-video", "dashicons-controls-play", "dashicons-controls-pause", "dashicons-controls-forward", "dashicons-controls-skipforward", "dashicons-controls-back", "dashicons-controls-skipback", "dashicons-controls-repeat", "dashicons-controls-volumeon", "dashicons-controls-volumeoff", "dashicons-image-crop", "dashicons-image-rotate", "dashicons-image-rotate-left", "dashicons-image-rotate-right", "dashicons-image-flip-vertical", "dashicons-image-flip-horizontal", "dashicons-image-filter", "dashicons-undo", "dashicons-redo", "dashicons-editor-bold", "dashicons-editor-italic", "dashicons-editor-ul", "dashicons-editor-ol", "dashicons-editor-quote", "dashicons-editor-alignleft", "dashicons-editor-aligncenter", "dashicons-editor-alignright", "dashicons-editor-insertmore", "dashicons-editor-spellcheck", "dashicons-editor-expand", "dashicons-editor-contract", "dashicons-editor-kitchensink", "dashicons-editor-underline", "dashicons-editor-justify", "dashicons-editor-textcolor", "dashicons-editor-paste-word", "dashicons-editor-paste-text", "dashicons-editor-removeformatting", "dashicons-editor-video", "dashicons-editor-customchar", "dashicons-editor-outdent", "dashicons-editor-indent", "dashicons-editor-help", "dashicons-editor-strikethrough", "dashicons-editor-unlink", "dashicons-editor-rtl", "dashicons-editor-break", "dashicons-editor-code", "dashicons-editor-paragraph", "dashicons-editor-table", "dashicons-align-left", "dashicons-align-right", "dashicons-align-center", "dashicons-align-none", "dashicons-lock", "dashicons-unlock", "dashicons-calendar", "dashicons-calendar-alt", "dashicons-visibility", "dashicons-hidden", "dashicons-post-status", "dashicons-edit", "dashicons-trash", "dashicons-sticky", "dashicons-external", "dashicons-arrow-up", "dashicons-arrow-down", "dashicons-arrow-right", "dashicons-arrow-left", "dashicons-arrow-up-alt", "dashicons-arrow-down-alt", "dashicons-arrow-right-alt", "dashicons-arrow-left-alt", "dashicons-arrow-up-alt2", "dashicons-arrow-down-alt2", "dashicons-arrow-right-alt2", "dashicons-arrow-left-alt2", "dashicons-sort", "dashicons-leftright", "dashicons-randomize", "dashicons-list-view", "dashicons-exerpt-view", "dashicons-grid-view", "dashicons-move", "dashicons-share", "dashicons-share-alt", "dashicons-share-alt2", "dashicons-twitter", "dashicons-rss", "dashicons-email", "dashicons-email-alt", "dashicons-facebook", "dashicons-facebook-alt", "dashicons-googleplus", "dashicons-networking", "dashicons-hammer", "dashicons-art", "dashicons-migrate", "dashicons-performance", "dashicons-universal-access", "dashicons-universal-access-alt", "dashicons-tickets", "dashicons-nametag", "dashicons-clipboard", "dashicons-heart", "dashicons-megaphone", "dashicons-schedule", "dashicons-wordpress", "dashicons-wordpress-alt", "dashicons-pressthis", "dashicons-update", "dashicons-screenoptions", "dashicons-info", "dashicons-cart", "dashicons-feedback", "dashicons-cloud", "dashicons-translation", "dashicons-tag", "dashicons-category", "dashicons-archive", "dashicons-tagcloud", "dashicons-text", "dashicons-yes", "dashicons-no", "dashicons-no-alt", "dashicons-plus", "dashicons-plus-alt", "dashicons-minus", "dashicons-dismiss", "dashicons-marker", "dashicons-star-filled", "dashicons-star-half", "dashicons-star-empty", "dashicons-flag", "dashicons-warning", "dashicons-location", "dashicons-location-alt", "dashicons-vault", "dashicons-shield", "dashicons-shield-alt", "dashicons-sos", "dashicons-search", "dashicons-slides", "dashicons-analytics", "dashicons-chart-pie", "dashicons-chart-bar", "dashicons-chart-line", "dashicons-chart-area", "dashicons-groups", "dashicons-businessman", "dashicons-id", "dashicons-id-alt", "dashicons-products", "dashicons-awards", "dashicons-forms", "dashicons-testimonial", "dashicons-portfolio", "dashicons-book", "dashicons-book-alt", "dashicons-download", "dashicons-upload", "dashicons-backup", "dashicons-clock", "dashicons-lightbulb", "dashicons-microphone", "dashicons-desktop", "dashicons-laptop", "dashicons-tablet", "dashicons-smartphone", "dashicons-phone", "dashicons-index-card", "dashicons-carrot", "dashicons-building", "dashicons-store", "dashicons-album", "dashicons-palmtree", "dashicons-tickets-alt", "dashicons-money", "dashicons-smiley", "dashicons-thumbs-up", "dashicons-thumbs-down", "dashicons-layout", "dashicons-paperclip");


        add_option('cpl_activate', "true");
        add_option('cpl_default_tpl', $cpl_default_tpl);
        add_option('cpl_inline_style', $cpl_inline_style);
        add_option('cpl_deshicons', $deshicons);
    }

    public function deactivate($network_wide) {
        delete_option('cpl_activate');
    }

    public function uninstall() {
        delete_option('cpl_default_tpl');
        delete_option('cpl_inline_style');
        delete_option('cpl_deshicons');
    }

}

$cpl = new Cpl();

