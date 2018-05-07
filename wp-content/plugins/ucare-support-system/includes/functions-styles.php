<?php
/**
 * Functions for managing styles on the application's front-end.
 *
 * @since 1.4.2
 * @package ucare
 */
namespace ucare;


// Load default styles
add_action( 'ucare_enqueue_scripts', 'ucare\enqueue_default_styles' );


function enqueue_default_styles() {

    ucare_enqueue_style( 'dropzone',       resolve_url( 'assets/lib/dropzone/css/dropzone.min.css'         ), null, PLUGIN_VERSION );
    ucare_enqueue_style( 'bootstrap',      resolve_url( 'assets/lib/bootstrap/css/bootstrap.min.css'       ), null, PLUGIN_VERSION );
    ucare_enqueue_style( 'scrolling-tabs', resolve_url( 'assets/lib/scrollingTabs/scrollingTabs.min.css'   ), null, PLUGIN_VERSION );
    ucare_enqueue_style( 'light-gallery',  resolve_url( 'assets/lib/lightGallery/css/lightgallery.min.css' ), null, PLUGIN_VERSION );

    ucare_enqueue_style( 'ucare-style',    resolve_url( 'assets/css/style.css' ), null, PLUGIN_VERSION );

    enqueue_fonts();

}


function print_styles() {

    $styles = styles();

    if ( $styles ) {

        $styles->do_items();
        $styles->reset();

        return $styles->done;

    }

    return false;
}


function styles() {
    return ucare()->get( 'styles' );
}
