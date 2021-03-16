<?php
/**
 * Generate child theme functions and definitions
 *
 * @package Generate
 */
require_once( dirname( __FILE__ ) . '/class-givewp-donations.php');

// Able to use font awesome in navigation (menu)
function wmpudev_enqueue_icon_stylesheet() {
    wp_register_style( 'fontawesome', 'https://use.fontawesome.com/releases/v5.5.0/css/all.css' );
    wp_enqueue_style( 'fontawesome');
}
add_action( 'wp_enqueue_scripts', 'wmpudev_enqueue_icon_stylesheet' );

// Shortcode-Enable My Widget Title https://gingercoolidge.com/add-font-awesome-to-widget-title-wordpress/
add_filter('widget_title', 'do_shortcode');

// (1) Creates a shortcode that displays a Font Awesome heart icon
add_shortcode('fa-heart', 'gsc_shortcode_faheart');

function gsc_shortcode_faheart( $attr ){
  return '<i class="fa fa-heart"></i>';
}

// (2) Creates a shortcode that displays a Font Awesome envelope icon
add_shortcode('fa-envelope', 'gsc_shortcode_faenvelope');

function gsc_shortcode_faenvelope( $attr ){
  return '<i class="fa fa-envelope"></i>';
}


// Gutenbergのブロックエディタの色設定をカスタマイズ https://delaymania.com/202004/web/gutenberg-palette-custom/
function my_color_set() {
  add_theme_support('editor-color-palette', array(
    array(
      'name' => __('red','Red') ,
      'slug' => 'red',
      'color' => '#f13c33',
    ) ,
    array(
      'name' => __('cream-yellow','Cream Yellow') ,
      'slug' => 'cream-yellow',
      'color' => '#fdeab6',
    ) ,
    array(
      'name' => __('blue','Blue') ,
      'slug' => 'blue',
      'color' => '#156ec7',
    ) ,
    array(
      'name' => __('green','Green') ,
      'slug' => 'green',
      'color' => '#78b466',
    ) ,
    array(
      'name' => __('dark-green','Dark Green') ,
      'slug' => 'dark-green',
      'color' => '#4c8a31',
    ) ,
    array(
      'name' => __('light-grey','Light Grey') ,
      'slug' => 'light-grey',
      'color' => '#eeeeee',
    ) ,
    array(
      'name' => __('pink','Pink') ,
      'slug' => 'pink',
      'color' => '#e4579f',
    ) ,
    array(
      'name' => __('baby-blue','Baby Blue') ,
      'slug' => 'baby-blue',
      'color' => '#b8e4f2',
    ) ,
    array(
      'name' => __('bright-yellow','Bright Yellow') ,
      'slug' => 'bright-yellow',
      'color' => '#fbe763',
    )

  ));
}
add_action('after_setup_theme', 'my_color_set');

// フルスクリーンモードをやめる
add_action( 'enqueue_block_editor_assets', 'wpdd_disable_editor_fullscreen_by_default' );
// Disable Fullscreen Gutenberg.
function wpdd_disable_editor_fullscreen_by_default() {
	$script = "window.onload = function() { const isFullscreenMode = wp.data.select( 'core/edit-post' ).isFeatureActive( 'fullscreenMode' ); if ( isFullscreenMode ) { wp.data.dispatch( 'core/edit-post' ).toggleFeature( 'fullscreenMode' ); } }";
		
	wp_add_inline_script( 'wp-blocks', $script );
}
 
 