<?php
/*
Plugin Name: Cux Estate
Description: Estate Task
*/
include( 'post_type/post_type.php' );
include( 'acf/fields.php' );




add_action( 'template_include', 'uploadr_redirect' );
    function uploadr_redirect( $template ) {

        $plugindir = dirname( __FILE__ );

        if ( 'real_estate' == get_post_type() )  {

            $template = $plugindir . '/single-real_estate.php';
        }

        return $template;

    }
    add_action('wp_enqueue_scripts', 'my_scripts_method');

    function my_scripts_method() {
    wp_enqueue_script( 'jquery' );
    }
    add_action('wp_ajax_contact_form', 'contact_form');
    add_action('wp_ajax_nopriv_contact_form', 'contact_form');

    function contact_form()
    {
    echo $_POST['name'];
    }
