<?php

namespace ThirstyAffiliates\Models;

use ThirstyAffiliates\Abstracts\Abstract_Main_Plugin_Class;

use ThirstyAffiliates\Interfaces\Model_Interface;
use ThirstyAffiliates\Interfaces\Initiable_Interface;

use ThirstyAffiliates\Helpers\Plugin_Constants;
use ThirstyAffiliates\Helpers\Helper_Functions;

use ThirstyAffiliates\Models\Affiliate_Link;

/**
 * Model that houses the link fixer logic.
 *
 * @since 3.0.0
 */
class Link_Fixer implements Model_Interface , Initiable_Interface {

    /*
    |--------------------------------------------------------------------------
    | Class Properties
    |--------------------------------------------------------------------------
    */

    /**
     * Property that holds the single main instance of Bootstrap.
     *
     * @since 3.0.0
     * @access private
     * @var Link_Fixer
     */
    private static $_instance;

    /**
     * Model that houses the main plugin object.
     *
     * @since 3.0.0
     * @access private
     * @var Abstract_Main_Plugin_Class
     */
    private $_main_plugin;

    /**
     * Model that houses all the plugin constants.
     *
     * @since 3.0.0
     * @access private
     * @var Plugin_Constants
     */
    private $_constants;

    /**
     * Property that houses all the helper functions of the plugin.
     *
     * @since 3.0.0
     * @access private
     * @var Helper_Functions
     */
    private $_helper_functions;




    /*
    |--------------------------------------------------------------------------
    | Class Methods
    |--------------------------------------------------------------------------
    */

    /**
     * Class constructor.
     *
     * @since 3.0.0
     * @access public
     *
     * @param Abstract_Main_Plugin_Class $main_plugin      Main plugin object.
     * @param Plugin_Constants           $constants        Plugin constants object.
     * @param Helper_Functions           $helper_functions Helper functions object.
     */
    public function __construct( Abstract_Main_Plugin_Class $main_plugin , Plugin_Constants $constants , Helper_Functions $helper_functions ) {

        $this->_constants        = $constants;
        $this->_helper_functions = $helper_functions;

        $main_plugin->add_to_all_plugin_models( $this );
        $main_plugin->add_to_public_models( $this );

    }

    /**
     * Ensure that only one instance of this class is loaded or can be loaded ( Singleton Pattern ).
     *
     * @since 3.0.0
     * @access public
     *
     * @param Abstract_Main_Plugin_Class $main_plugin      Main plugin object.
     * @param Plugin_Constants           $constants        Plugin constants object.
     * @param Helper_Functions           $helper_functions Helper functions object.
     * @return Link_Fixer
     */
    public static function get_instance( Abstract_Main_Plugin_Class $main_plugin , Plugin_Constants $constants , Helper_Functions $helper_functions ) {

        if ( !self::$_instance instanceof self )
            self::$_instance = new self( $main_plugin , $constants , $helper_functions );

        return self::$_instance;

    }

    /**
     * Get data of links to be fixed.
     *
     * @since 3.0.0
     * @since 3.2.4 Make sure that link fixer runs using the default language set on WPML when it is active.
     * @access public
     *
     * @global SitePress $sitepress WPML main plugin object.
     *
     * @param array $links   List of affiliate links to fix.
     * @param int   $post_id ID of the post currently being viewed.
     * @param array $data    Affiliate Links data.
     * @return array Affiliate Links data.
     */
    public function get_link_fixer_data( $links , $post_id = 0 , $data = array() ) {

        global $sitepress;

        if ( empty( $links ) )
            return $data;

        if ( is_object( $sitepress ) )
            $sitepress->switch_lang( $sitepress->get_default_language() );

        foreach( $links as $link ) {

            $href    = esc_url_raw( $link[ 'href' ] );
            $class   = isset( $link[ 'class' ] ) ? sanitize_text_field( $link[ 'class' ] ) : '';
            $key     = (int) sanitize_text_field( $link[ 'key' ] );
            $link_id = url_to_postid( $href );

            $thirstylink = new Affiliate_Link( $link_id );

            if ( ! $thirstylink->get_id() )
                continue;

            $class      = str_replace( 'thirstylinkimg' , 'thirstylink' , $class );
            $class     .= ( get_option( 'ta_disable_thirsty_link_class' ) !== "yes" && strpos( $class , 'thirstylink' ) === false ) ? ' thirstylink' : '';
            $href       = ( $thirstylink->is( 'uncloak_link' ) ) ? apply_filters( 'ta_uncloak_link_url' , $thirstylink->get_prop( 'destination_url' ) , $thirstylink ) : $thirstylink->get_prop( 'permalink' );
            $rel        = $thirstylink->is( 'no_follow' ) ? 'nofollow' : '';
            $rel       .= ' ' . $thirstylink->get_prop( 'rel_tags' );
            $target     = $thirstylink->is( 'new_window' ) ? '_blank' : '';
            $title      = get_option( 'ta_disable_title_attribute' ) != 'yes' ? esc_attr( str_replace( '"' , '' , $thirstylink->get_prop( 'name' ) ) ) : '';
            $title      = str_replace( '&#039;' , '\'' , $title );

            if ( $link[ 'is_image' ] )
                $class = str_replace( 'thirstylink' , 'thirstylinkimg' , $class );

            $data[] = array(
                'key'     => $key,
                'link_id' => $link_id,
                'class'   => esc_attr( trim( $class ) ),
                'href'    => esc_url_raw( $href ),
                'rel'     => esc_attr( trim( $rel ) ),
                'target'  => esc_attr( $target ),
                'title'   => $title
            );
        }

        return $data;
    }

    /**
     * Ajax link fixer.
     *
     * @since 3.0.0
     * @access public
     */
    public function ajax_link_fixer() {

        if ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX )
            $response = array( 'status' => 'fail' , 'error_msg' => __( 'Invalid AJAX call' , 'thirstyaffiliates' ) );
        elseif ( ! isset( $_POST[ 'hrefs' ] ) || empty( $_POST[ 'hrefs' ] ) )
            $response = array( 'status' => 'fail' , 'error_msg' => __( 'Invalid AJAX call' , 'thirstyaffiliates' ) );
        else {

            $links    = $_POST[ 'hrefs' ];
            $post_id  = isset( $_POST[ 'post_id' ] ) ? intval( $_POST[ 'post_id' ] ) : 0;
            $response = array(
                'status' => 'success',
                'data' => $this->get_link_fixer_data( $links , $post_id )
            );
        }

        @header( 'Content-Type: application/json; charset=' . get_option( 'blog_charset' ) );
        echo wp_json_encode( $response );
        wp_die();
    }




    /*
    |--------------------------------------------------------------------------
    | Fulfill implemented interface contracts
    |--------------------------------------------------------------------------
    */

    /**
     * Execute codes that needs to run on plugin initialization.
     *
     * @since 3.0.0
     * @access public
     * @implements ThirstyAffiliates\Interfaces\Initiable_Interface
     */
    public function initialize() {

        add_action( 'wp_ajax_ta_link_fixer' , array( $this , 'ajax_link_fixer' ) );
        add_action( 'wp_ajax_nopriv_ta_link_fixer' , array( $this , 'ajax_link_fixer' ) );
    }

    /**
     * Execute link picker.
     *
     * @since 3.0.0
     * @access public
     */
    public function run() {
    }
}
