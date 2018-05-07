<?php
$addons = new AddOns( array (
    'faqs',
    'events',
    'news',
    'testimonials',
    'gallery'
        ) );

new Event_Meta_Box;
new Jobs_Meta_Box;

class AddOns {

    private $args = null;

    public function __construct( $args ) {

        $this->args = $args;
        $this->add_hooks();
    }

    public function add_hooks() {

        // Create shortcodes
//        add_shortcode( 'relia-faqs', array ( $this, 'relia_faqs' ) );
        add_shortcode( 'relia-faqs', array ( $this, 'relia_faqs' ) );
        add_shortcode( 'relia-news', array ( $this, 'relia_news' ) );
        add_shortcode( 'relia-gallery', array ( $this, 'relia_gallery' ) );
        add_shortcode( 'relia-current-events', array( $this, 'relia_current_events' ) );
        add_shortcode( 'relia-past-events', array( $this, 'relia_past_events' ) );
        add_shortcode( 'relia-testimonials', array( $this, 'relia_testimonials' ) );

        add_action( 'wp_head', array ( $this, 'relia_homepage_script' ) );
        add_action( 'wp_head', array ( $this, 'relia_custom_css' ) );
        add_filter('widget_text', 'do_shortcode');
        add_filter( 'the_content', array( $this, 'relia_add_social' ) );
//        add_filter('wp_nav_menu_items', array( $this, 'relia_customize_nav' ) );
        add_action('admin_menu', array( $this, 'relia_add_menu_pages' ) );
        add_action( 'after_setup_theme', array( $this, 'relia_theme_updater' ) );
        
        if ( in_array( 'faqs', $this->args ) ) :
            add_action( 'init', array ( $this, 'create_faqs' ) );
        endif;

        if ( in_array( 'jobs', $this->args ) ) :
            add_action( 'init', array ( $this, 'create_jobs' ) );
        endif;

        if ( in_array( 'events', $this->args ) ) :
            add_action( 'init', array ( $this, 'create_events' ) );
        endif;

        if ( in_array( 'news', $this->args ) ) :
            add_action( 'init', array ( $this, 'create_news' ) );
        endif;

        if ( in_array( 'testimonials', $this->args ) ) :
            add_action( 'init', array ( $this, 'create_testimonials' ) );
        endif;

        if ( in_array( 'gallery', $this->args ) ) :
            add_action( 'init', array ( $this, 'create_gallery' ) );
        endif;
        
        if( ! relia_strap_pl() ) :
            add_action( 'admin_notices', array( $this, 'relia_admin_notice__success' ) );
        endif;

        add_action('switch_theme', array( $this, 'relia_deactivation_function' ) );
        add_action('after_switch_theme', array( $this, 'relia_activation_function' ) );
        add_action( 'widgets_init', array( $this, 'relia_register_widgets' ) );
    }
    
    function relia_deactivation_function(){
        update_option( 'relia_active', false );
    }

    function relia_activation_function(){
        update_option('relia_active', true );
        wp_redirect( admin_url( 'admin.php?page=relia_menu' ) );
    }
    
    function relia_add_menu_pages(){
        add_menu_page('Relia', 'Relia Pro', 'manage_options', 'relia_menu', array( $this, 'relia_menu' ), get_template_directory_uri() . '/inc/images/cat_logo_mini.png' );
    }
    
    function relia_menu() {
        include get_template_directory() . '/inc/relia/upgrade.php';
    }
    
    function relia_theme_updater() {
            require( get_template_directory() . '/inc/relia/theme-updater.php' );
    }
    
    function relia_register_widgets() {
            register_widget( 'Relia_Faq_Widget' );
            register_widget( 'Relia_News_Widget' );
            register_widget( 'Relia_Events_Widget' );
            register_widget( 'Relia_Testimonials_Widget' );
            register_widget( 'Relia_Gallery_Widget' );
            register_widget( 'Relia_Contact_Form' );
            register_widget( 'Relia_Contact_Info' );
            register_widget( 'Relia_Pricing_Table' );
            register_widget( 'Relia_Service' );
            register_widget( 'Relia_CTA' );
        
    }
    
    public function relia_customize_nav ( $items ) {
        
        if( get_theme_mod( 'relia_search_bool', 'on' ) == 'on' ) :
            $items .= '<li class="menu-item"><a class="relia-search" href="#search" role="button" data-toggle="modal"><span class="fa fa-search"></span></a></li>';
        endif;
        
        return $items;
        
    }
    
    function create_testimonials() {

        $labels = array(
                'name'                => _x( 'Testimonials', 'Post Type General Name', 'relia' ),
                'singular_name'       => _x( 'Testimonial', 'Post Type Singular Name', 'relia' ),
                'menu_name'           => __( 'Testimonials', 'relia' ),
                'name_admin_bar'      => __( 'Testimonials', 'relia' ),
                'parent_item_colon'   => __( '', 'relia' ),
                'all_items'           => __( 'All Testimonials', 'relia' ),
                'add_new_item'        => __( 'Add New Testimonial', 'relia' ),
                'add_new'             => __( 'Add New', 'relia' ),
                'new_item'            => __( 'New Testimonial', 'relia' ),
                'edit_item'           => __( 'Edit Testimonial', 'relia' ),
                'update_item'         => __( 'Update Testimonial', 'relia' ),
                'view_item'           => __( 'View Testimonial', 'relia' ),
                'search_items'        => __( 'Search Testimonials', 'relia' ),
                'not_found'           => __( 'No testimonials found', 'relia' ),
                'not_found_in_trash'  => __( 'No testimonials found in trash', 'relia' ),
        );
        $args = array(
                'label'               => __( 'testimonial', 'relia' ),
                'description'         => __( 'Create and display your testimonials', 'relia' ),
                'labels'              => $labels,
                'supports'            => array( 'title', 'editor', ),
                'hierarchical'        => false,
                'public'              => true,
                'show_ui'             => true,
                'show_in_menu'        => true,
                'menu_position'       => 5,
                'menu_icon'           => 'dashicons-format-quote',
                'show_in_admin_bar'   => true,
                'show_in_nav_menus'   => true,
                'can_export'          => true,
                'has_archive'         => false,
                'exclude_from_search' => false,
                'publicly_queryable'  => true,
                'rewrite'             => false,
                'capability_type'     => 'page',
        );
        register_post_type( 'testimonial', $args );

    }

    function create_gallery() {

            $labels = array(
                    'name'                  => _x( 'Gallery', 'Post Type General Name', 'relia' ),
                    'singular_name'         => _x( 'Gallery', 'Post Type Singular Name', 'relia' ),
                    'menu_name'             => __( 'Gallery', 'relia' ),
                    'name_admin_bar'        => __( 'Gallery', 'relia' ),
                    'archives'              => __( '', 'relia' ),
                    'parent_item_colon'     => __( '', 'relia' ),
                    'all_items'             => __( 'All Gallery Items', 'relia' ),
                    'add_new_item'          => __( 'Gallery', 'relia' ),
                    'add_new'               => __( 'Add Gallery Item', 'relia' ),
                    'new_item'              => __( 'New Gallery Item', 'relia' ),
                    'edit_item'             => __( 'Edit Gallery Item', 'relia' ),
                    'update_item'           => __( 'Update Gallery Item', 'relia' ),
                    'view_item'             => __( 'View Gallery Item', 'relia' ),
                    'search_items'          => __( 'Search Gallery Items', 'relia' ),
                    'not_found'             => __( 'Not found', 'relia' ),
                    'not_found_in_trash'    => __( 'Not found in Trash', 'relia' ),
                    'featured_image'        => __( 'Featured Image', 'relia' ),
                    'set_featured_image'    => __( 'Set featured image', 'relia' ),
                    'remove_featured_image' => __( 'Remove featured image', 'relia' ),
                    'use_featured_image'    => __( 'Use as featured image', 'relia' ),
                    'insert_into_item'      => __( 'Insert into item', 'relia' ),
                    'uploaded_to_this_item' => __( 'Uploaded to this item', 'relia' ),
                    'items_list'            => __( 'Items list', 'relia' ),
                    'items_list_navigation' => __( 'Items list navigation', 'relia' ),
                    'filter_items_list'     => __( 'Filter items list', 'relia' ),
            );
            $args = array(
                    'label'                 => __( 'Gallery', 'relia' ),
                    'description'           => __( 'The Gallery is a great way to create an image portfolio', 'relia' ),
                    'labels'                => $labels,
                    'supports'              => array( 'title', 'thumbnail' ),
                    'hierarchical'          => false,
                    'public'                => true,
                    'show_ui'               => true,
                    'show_in_menu'          => true,
                    'menu_position'         => 5,
                    'menu_icon'             => 'dashicons-format-gallery',
                    'show_in_admin_bar'     => true,
                    'show_in_nav_menus'     => true,
                    'can_export'            => true,
                    'has_archive'           => false,
                    'exclude_from_search'   => false,
                    'publicly_queryable'    => true,
                    'rewrite'               => false,
                    'capability_type'       => 'page',
            );
            register_post_type( 'gallery', $args );

    }
    
    public function create_faqs() {

        $labels = array (
            'name' => _x( 'FAQs', 'Post Type General Name', 'relia' ),
            'singular_name' => _x( 'FAQ', 'Post Type Singular Name', 'relia' ),
            'menu_name' => __( 'FAQs', 'relia' ),
            'name_admin_bar' => __( 'FAQ', 'relia' ),
            'archives' => __( 'FAQ Archives', 'relia' ),
            'parent_item_colon' => __( 'Parent Item:', 'relia' ),
            'all_items' => __( 'All FAQs', 'relia' ),
            'add_new_item' => __( 'Add New FAQ', 'relia' ),
            'add_new' => __( 'Add FAQ', 'relia' ),
            'new_item' => __( 'New FAQ', 'relia' ),
            'edit_item' => __( 'Edit FAQ', 'relia' ),
            'update_item' => __( 'Update FAQ', 'relia' ),
            'view_item' => __( 'View FAQ', 'relia' ),
            'search_items' => __( 'Search FAQs', 'relia' ),
            'not_found' => __( 'Not found', 'relia' ),
            'not_found_in_trash' => __( 'Not found in Trash', 'relia' ),
            'featured_image' => __( 'Featured Image', 'relia' ),
            'set_featured_image' => __( 'Set featured image', 'relia' ),
            'remove_featured_image' => __( 'Remove featured image', 'relia' ),
            'use_featured_image' => __( 'Use as featured image', 'relia' ),
            'insert_into_item' => __( 'Insert into FAQ', 'relia' ),
            'uploaded_to_this_item' => __( 'Uploaded to this FAQ', 'relia' ),
            'items_list' => __( 'FAQs list', 'relia' ),
            'items_list_navigation' => __( 'Items list navigation', 'relia' ),
            'filter_items_list' => __( 'Filter items list', 'relia' ),
        );
        $args = array (
            'label' => __( 'FAQ', 'relia' ),
            'description' => __( 'Frequently asked questions for your site', 'relia' ),
            'labels' => $labels,
            'supports' => array ( 'title', 'editor', 'revisions', ),
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_position' => 5,
            'menu_icon' => 'dashicons-editor-help',
            'show_in_admin_bar' => true,
            'show_in_nav_menus' => true,
            'can_export' => true,
            'has_archive' => false,
            'exclude_from_search' => true,
            'publicly_queryable' => true,
            'rewrite' => false,
            'capability_type' => 'page',
        );

        register_post_type( 'faq', $args );
    }

    function create_jobs() {

        $labels = array (
            'name' => _x( 'Jobs', 'Post Type General Name', 'relia' ),
            'singular_name' => _x( 'Job', 'Post Type Singular Name', 'relia' ),
            'menu_name' => __( 'Jobs', 'relia' ),
            'name_admin_bar' => __( 'Jobs', 'relia' ),
            'archives' => __( 'Archives', 'relia' ),
            'parent_item_colon' => __( 'Parent Item:', 'relia' ),
            'all_items' => __( 'All Jobs', 'relia' ),
            'add_new_item' => __( 'Add New Job', 'relia' ),
            'add_new' => __( 'Add New', 'relia' ),
            'new_item' => __( 'New Job', 'relia' ),
            'edit_item' => __( 'Edit Job', 'relia' ),
            'update_item' => __( 'Update Job', 'relia' ),
            'view_item' => __( 'View Job', 'relia' ),
            'search_items' => __( 'Search Jobs', 'relia' ),
            'not_found' => __( 'Not found', 'relia' ),
            'not_found_in_trash' => __( 'Not found in Trash', 'relia' ),
            'featured_image' => __( 'Featured Image', 'relia' ),
            'set_featured_image' => __( 'Set featured image', 'relia' ),
            'remove_featured_image' => __( 'Remove featured image', 'relia' ),
            'use_featured_image' => __( 'Use as featured image', 'relia' ),
            'insert_into_item' => __( 'Insert into job', 'relia' ),
            'uploaded_to_this_item' => __( 'Uploaded to this job', 'relia' ),
            'items_list' => __( 'Jobs list', 'relia' ),
            'items_list_navigation' => __( 'Jobs list navigation', 'relia' ),
            'filter_items_list' => __( 'Filter jobs', 'relia' ),
        );
        $args = array (
            'label' => __( 'Job', 'relia' ),
            'description' => __( 'Jobs', 'relia' ),
            'labels' => $labels,
            'supports' => array ( 'title', 'editor', 'author', 'thumbnail', ),
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_position' => 5,
            'menu_icon' => 'dashicons-businessman',
            'show_in_admin_bar' => true,
            'show_in_nav_menus' => true,
            'can_export' => true,
            'has_archive' => true,
            'exclude_from_search' => false,
            'publicly_queryable' => true,
            'capability_type' => 'page',
        );

        register_post_type( 'job', $args );
    }

    function create_events() {

        $labels = array (
            'name' => _x( 'Events', 'Post Type General Name', 'relia' ),
            'singular_name' => _x( 'Event', 'Post Type Singular Name', 'relia' ),
            'menu_name' => __( 'Events', 'relia' ),
            'name_admin_bar' => __( 'Events', 'relia' ),
            'archives' => __( 'Archives', 'relia' ),
            'parent_item_colon' => __( 'Parent Item:', 'relia' ),
            'all_items' => __( 'All Events', 'relia' ),
            'add_new_item' => __( 'Add New Event', 'relia' ),
            'add_new' => __( 'Add New', 'relia' ),
            'new_item' => __( 'New Event', 'relia' ),
            'edit_item' => __( 'Edit Event', 'relia' ),
            'update_item' => __( 'Update Event', 'relia' ),
            'view_item' => __( 'View Event', 'relia' ),
            'search_items' => __( 'Search Events', 'relia' ),
            'not_found' => __( 'Not found', 'relia' ),
            'not_found_in_trash' => __( 'Not found in Trash', 'relia' ),
            'featured_image' => __( 'Featured Image', 'relia' ),
            'set_featured_image' => __( 'Set featured image', 'relia' ),
            'remove_featured_image' => __( 'Remove featured image', 'relia' ),
            'use_featured_image' => __( 'Use as featured image', 'relia' ),
            'insert_into_item' => __( 'Insert into event', 'relia' ),
            'uploaded_to_this_item' => __( 'Uploaded to this event', 'relia' ),
            'items_list' => __( 'Events list', 'relia' ),
            'items_list_navigation' => __( 'Jobs list navigation', 'relia' ),
            'filter_items_list' => __( 'Filter events', 'relia' ),
        );
        $args = array (
            'label' => __( 'Event', 'relia' ),
            'description' => __( 'Events', 'relia' ),
            'labels' => $labels,
            'supports' => array ( 'title', 'editor', 'author', 'thumbnail', ),
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_position' => 5,
            'menu_icon' => 'dashicons-calendar-alt',
            'show_in_admin_bar' => true,
            'show_in_nav_menus' => true,
            'can_export' => true,
            'has_archive' => true,
            'exclude_from_search' => false,
            'publicly_queryable' => true,
            'capability_type' => 'post',
        );
        register_post_type( 'event', $args );
    }

    function create_news() {

        $labels = array (
            'name' => _x( 'News', 'Post Type General Name', 'relia' ),
            'singular_name' => _x( 'News Entry', 'Post Type Singular Name', 'relia' ),
            'menu_name' => __( 'News', 'relia' ),
            'name_admin_bar' => __( 'News', 'relia' ),
            'archives' => __( 'Item Archives', 'relia' ),
            'parent_item_colon' => __( 'Parent Item:', 'relia' ),
            'all_items' => __( 'All Items', 'relia' ),
            'add_new_item' => __( 'Add New Item', 'relia' ),
            'add_new' => __( 'Add News Item', 'relia' ),
            'new_item' => __( 'New News Item', 'relia' ),
            'edit_item' => __( 'Edit News Item', 'relia' ),
            'update_item' => __( 'Update News Item', 'relia' ),
            'view_item' => __( 'View News Item', 'relia' ),
            'search_items' => __( 'Search News Item', 'relia' ),
            'not_found' => __( 'Not found', 'relia' ),
            'not_found_in_trash' => __( 'Not found in Trash', 'relia' ),
            'featured_image' => __( 'Featured Image', 'relia' ),
            'set_featured_image' => __( 'Set featured image', 'relia' ),
            'remove_featured_image' => __( 'Remove featured image', 'relia' ),
            'use_featured_image' => __( 'Use as featured image', 'relia' ),
            'insert_into_item' => __( 'Insert into item', 'relia' ),
            'uploaded_to_this_item' => __( 'Uploaded to this item', 'relia' ),
            'items_list' => __( 'Items list', 'relia' ),
            'items_list_navigation' => __( 'Items list navigation', 'relia' ),
            'filter_items_list' => __( 'Filter items list', 'relia' ),
        );
        $args = array (
            'label' => __( 'News Entry', 'relia' ),
            'description' => __( 'In The News posts', 'relia' ),
            'labels' => $labels,
            'supports' => array ( 'title', 'thumbnail', 'editor' ),
            'hierarchical' => false,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_position' => 5,
            'menu_icon' => 'dashicons-megaphone',
            'show_in_admin_bar' => true,
            'show_in_nav_menus' => true,
            'can_export' => true,
            'has_archive' => false,
            'exclude_from_search' => false,
            'publicly_queryable' => true,
            'rewrite' => false,
            'capability_type' => 'page',
        );
        register_post_type( 'news', $args );
    }
    
    function relia_admin_notice__success() {
        ?>
        <div class="notice notice-error is-dismissible">
            <h3>Your Relia license is not active. </h3>
            <p>
                <a href="<?php echo admin_url('themes.php?page=relia-pro-license'); ?>" class="button button-primary"> Begin activating Relia Pro</a>
            </p>
        </div>
        <?php
    }
    
    function relia_add_social( $content ){ 
        
            if( is_single() && 'post' == get_post_type() ) :

                if( get_theme_mod('relia_social_bool', 'on' ) == 'off' ) : 
                    return $content;
                endif;

                $home_url = home_url('/');            $content .= '<ul class="share-buttons">'; 
                $content .= '<li><a target="_BLANK" href="https://www.facebook.com/sharer/sharer.php?u=' . get_the_permalink() . '&t=' . get_the_title() . '"><img src="' . get_template_directory_uri() . '/inc/images/Facebook.png"></a></li>';
                $content .= '<li><a target="_BLANK" href="https://twitter.com/intent/tweet?source=' . get_the_permalink() . '&text=:%20' . get_the_permalink() . '"><img src="' . get_template_directory_uri() . '/inc/images/Twitter.png"></a></li>';
                $content .= '<li><a target="_BLANK" href="https://plus.google.com/share?url=' . get_the_permalink() . '"><img src="' . get_template_directory_uri() . '/inc/images/Google+.png"></a></li>';
                $content .= '<li><a target="_BLANK" href="http://www.linkedin.com/shareArticle?mini=true&url=' . get_the_permalink() . '&title=' . get_the_title() . '"><img src="' . get_template_directory_uri() . '/inc/images/LinkedIn.png"></a></li>';
                $content .= '<li><a target="_BLANK" href="http://pinterest.com/pin/create/button/?url=' . get_the_permalink() . '&description=' . get_the_title() . '"><img src="' . get_template_directory_uri() . '/inc/images/Pinterest.png"></a></li>';

                $content .= '</ul>';?>

            <?php endif;

            return $content;
    }
    
    public static function relia_contact_form( $instance ){ ?>

        <div class="contact-form-section">

            <div class="col-sm-12">
            
                <div class="contact-form-wrapper">
                
                    <h3 class="contact-form-heading"><?php echo __('Contact Form', 'relia' ); ?></h3>
                    
                    <form action="<?php echo admin_url('admin-ajax.php' ); ?>" id="relia-contact-form">

                        <input type="hidden" class="recipient" name="recipient" value="<?php echo !empty( $instance['relia_contactemail'] ) ? $instance['relia_contactemail'] : ''; ?>" />
                        
                        <div class="group">
                            <label><?php echo !empty( $instance['relia_contactfrom_label'] ) ? $instance['relia_contactfrom_label'] : __('Name', 'relia' ); ?></label>
                            <input type="text" name="name" class="control name"/>
                        </div>

                        <div class="group">
                            <label><?php echo !empty( $instance['relia_contactemail_label'] ) ? $instance['relia_contactemail_label'] : __('Email Address', 'relia' ); ?></label>
                            <input type="text" name="email" class="control email"/>
                        </div>

                        <div class="group">
                            <label class="message"><?php echo !empty( $instance['relia_contactmessage_label'] ) ? $instance['relia_contactmessage_label'] : __('Message', 'relia' ); ?></label>
                            <textarea name="message" class="control message"></textarea>
                        </div>

                        <input type="submit" class="relia-button" value="<?php echo !empty( $instance['relia_contactsubmit_label'] ) ? $instance['relia_contactsubmit_label'] : __('Submit', 'relia' ); ?>"/>

                        <div class="mail-sent"><span class="fa fa-check-circle"></span> <?php _e( 'Email sent!', 'relia' ); ?></div>
                        <div class="mail-not-sent"><span class="fa fa-exclamation-circle"></span> <?php _e( 'There has been an error, please check the information you entered and try again.', 'relia' ); ?></div>

                    </form>

                </div>
                    
            </div>
                
        </div>

    <?php }
    

    public function relia_faqs() {
        
        ob_start();
        $this->relia_output_faqs();
        $output = ob_get_clean();
        return $output;
    }
    
    public static function relia_output_faqs() {
        
        // WP_Query arguments
        $args = array (
            'post_type' => array ( 'faq' ),
            'post_status' => array ( 'publish' ),
            'order' => 'DESC',
            'orderby' => 'date',
            'posts_per_page' => '200',
        );

        // The Query
        $faqs = new WP_Query( $args );

        // The Loop
        if ( $faqs->have_posts() ) : ?>

            <div class="faq-section">

                <?php while ( $faqs->have_posts() ) :

                    $faqs->the_post();
                    ?>

                    <div class="single-faq col-sm-12">
                        <h3 class="faq-title"><?php the_title(); ?></h3>
                        <div class="faq-content">
                            <p><?php the_content(); ?></p>
                        </div>
                    </div>            

                    <?php
                endwhile; ?>
                
            </div>

        <?php else :
        // no posts found
        endif;



        // Restore original Post Data
        wp_reset_postdata();
        
    }

    function relia_news() {

        ob_start();
        $this->relia_output_news();
        $output = ob_get_clean();
        return $output;
    }
    
    public static function relia_output_news () {
        
        echo '<div id="news-posts">';
        
        $paged = ( get_query_var( 'paged', 1 ) );


        // WP_Query arguments
        $args = array (
            'post_type' => array ( 'news' ),
            'post_status' => array ( 'publish' ),
            'paged' => $paged,
            'posts_per_page' => '6',
        );

        // The Query
        $news = new WP_Query( $args );

        // The Loop
        if ( $news->have_posts() ) :

            $ctr = 0;

            while ( $news->have_posts() ) :

                $ctr++;

                $news->the_post();
                ?>

                <div class="col-sm-6">
                    <div class="news-item">

                        <div class="image" style="background-image: url(<?php echo has_post_thumbnail() ? get_the_post_thumbnail_url() : get_template_directory_uri() . '/inc/images/blog-post-default-bg.jpg'; ?>);">
                            
                            <h3 class="title">
                                <a href="<?php echo esc_url( get_the_permalink() ); ?>"><?php the_title(); ?></a>
                            </h3>
                            
                            <div class="diviver"><span></span></div>

                            <div class="date"><?php echo get_the_date( 'd M, Y'); ?></div>
                            
                        </div>
                        
                    </div>            
                </div>

                <?php if ( $ctr % 2 == 0 ): ?>
                    <div class="clear"></div>
                <?php endif; ?>

                <?php
            endwhile;

            if ( $news->max_num_pages > 1 ) { // check if the max number of pages is greater than 1  
                ?>
                <div class="clear"></div>
                <nav class="prev-next-posts">
                    <div class="prev-posts-link">
                        <?php echo get_previous_posts_link( '<span class="fa fa-chevron-left"></span>' ); // display newer posts link  ?>
                    </div>
                    <div class="next-posts-link">
                        <?php
                        echo get_query_var( 'paged', 1 ) ? get_query_var( 'paged', 1 ) : '1';
                        echo ' of ' . $news->max_num_pages;
                        ?>
                <?php echo get_next_posts_link( '<span class="fa fa-chevron-right"></span>', $news->max_num_pages ); // display older posts link   ?>
                    </div>
                </nav>
                <?php
            }

        // no posts found
        endif;

        // Restore original Post Data
        wp_reset_postdata();

        echo '</div>';
        
    }

    function relia_current_events() {

        ob_start();
        $this->relia_output_current_events();
        $output = ob_get_clean();
        return $output;
    }

    public static function relia_output_current_events() {
        

        $args = array (
            'posts_per_page' => '200',
            'post_type' => array ( 'event' ),
            'post_status' => array ( 'publish' ),
            'order' => 'DESC',
            'orderby' => 'date',
            'meta_query' => array (
                array (
                    'key' => 'event_metadate',
                    'value' => date( 'Y-m-d' ),
                    'compare' => '>=',
                    'type' => 'DATE',
                ),
            ),
        );

        // The Query
        $events = new WP_Query( $args );

        // The Loop
        if ( $events->have_posts() ) {
            ?>

            <div class="event-blog">

                    <?php
                    $ctr = 0;

                    while ( $events->have_posts() ) {

                        $ctr++;

                        $events->the_post();
                        ?>

                        <div class="col-sm-6 event-post-wrapper">
                            <div class="blogroll-post event-post row <?php echo $ctr > 3 ? ' ' : ''; ?>">

                    <?php if ( has_post_thumbnail() ) : ?>
                                    <div class="background col-sm-12" 
                                         style="background-image: url(<?php echo wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) ); ?>)"></div>
                    <?php endif; ?>

                                <div class="col-sm-12 event-details">
                                    <h2 class="title">

                                        <a target="_BLANK" href="<?php echo esc_url( get_the_permalink( get_the_ID() ) ); ?>">
                                            <?php echo the_title(); ?>
                                        </a>

                                        <div class="location">
                                            <?php echo get_post_meta( get_the_ID(), 'event_metalocation', true ); ?>
                                        </div>

                                        <div class="date">
                                            <?php echo date( 'M jS, Y', strtotime( get_post_meta( get_the_ID(), 'event_metadate', true ) ) ); ?>

                                            <?php echo date( 'g:i', strtotime( get_post_meta( get_the_ID(), 'event_metatime_start', true ) ) ); ?>
                                            to <?php echo date( 'g:i a', strtotime( get_post_meta( get_the_ID(), 'event_metatime_end', true ) ) ); ?>
                                        </div>

                                        <div class="clear"></div>

                                    </h2>

                                    <div class="">
                                        <a class="apply secondary-button" href="<?php echo esc_url( get_the_permalink( get_the_ID() ) ); ?>"><?php _e( 'Learn More', 'relia') ?></a>
                                    </div>

                                </div>

                            </div>
                            
                        </div>
                  
                    <?php }
                    ?>
                    
            </div>
                
            <?php
        } else {
            echo '<h4>There are currently no upcoming events, please check again at a later time.</h4>';
        }

        // Restore original Post Data
        wp_reset_postdata();
        ?>

        <div class="clear"></div>

        <?php
        
    }
    
    function relia_past_events() {

        ob_start();

        $args = array (
            'post_type' => array ( 'event' ),
            'post_status' => array ( 'publish' ),
            'order' => 'DESC',
            'orderby' => 'date',
            'meta_query' => array (
                array (
                    'key' => 'event_metadate',
                    'value' => date( 'Y-m-d' ),
                    'compare' => '<',
                    'type' => 'DATE',
                ),
            ),
        );

        // The Query
        $events = new WP_Query( $args );

        // The Loop
        if ( $events->have_posts() ) {
            ?>

            <div class="event-blog">

                <?php
                $ctr = 0;

                while ( $events->have_posts() ) {

                    $ctr++;

                    $events->the_post();
                    ?>

                    <div class="col-sm-6 event-post-wrapper">
                        <div class="blogroll-post event-post row <?php echo $ctr > 3 ? ' ' : ''; ?>">

                <?php if ( has_post_thumbnail() ) : ?>
                                <div class="background col-sm-12" 
                                     style="background-image: url(<?php echo wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) ); ?>)"></div>
                <?php endif; ?>

                            <div class="col-sm-12 event-details">
                                <h2 class="title">

                                    <a href="<?php echo the_permalink(); ?>">
                <?php echo the_title(); ?>
                                    </a>

                                    <div class="location">
                <?php echo get_post_meta( get_the_ID(), 'event_metalocation', true ); ?>
                                    </div>

                                    <div class="date">
                <?php echo date( 'M jS, Y', strtotime( get_post_meta( get_the_ID(), 'event_metadate', true ) ) ); ?>


                <?php echo date( 'g:i', strtotime( get_post_meta( get_the_ID(), 'event_metatime_start', true ) ) ); ?>
                                        to <?php echo date( 'g:i a', strtotime( get_post_meta( get_the_ID(), 'event_metatime_end', true ) ) ); ?>


                                    </div>


                                    <div class="clear"></div>

                                </h2>

                                <div class="">
                                    <a class="apply secondary-button" target="_BLANK" href="<?php echo esc_url( get_post_meta( get_the_ID(), 'event_more', true ) ); ?>">Learn More</a>
                                </div>                               

                            </div>



                        </div>
                    </div>

                <?php }
                ?>
            </div>
            <?php
        } else {
            echo '<h4>There are currently no past events, please check again at a later time.</h4>';
        }

        // Restore original Post Data
        wp_reset_postdata();
        ?>

        <div class="clear"></div>

        <?php
        $output = ob_get_clean();
        return $output;
    }
    
    
    function relia_testimonials() {
        
        ob_start();
        $this->relia_output_testimonials();
        $output = ob_get_clean();
        return $output;
        
    }
    
    public static function relia_output_testimonials( $type = null ){
        

        
        $args = array (
            'posts_per_page' => '200',
            'post_status'   => 'publish',
            'post_type'     => 'testimonial'
        );

        $testimonials = wp_get_recent_posts($args); ?>

        <div id="testimonial-section">
        
            <div class="col-sm-12">

                <ul id="relia-testimonials" class="relia-testimonials <?php echo !empty( $type ) ? $type : null ?> owl-carousel owl-theme">

                    <?php foreach( $testimonials as $testimonial ) : ?>

                    <li>

                        <div class="testimonial-content">
                            <div class="col-xs-12">
<!--                                        <i class="fa fa-quote-left"></i>-->
                                <?php echo $testimonial['post_content']; ?>
                            </div>
                            <div class="clear"></div>
                            <!--<i class="fa fa-quote-right"></i>-->
                        </div>

                        <div class="testimonial-author center">
                            ~ <?php echo $testimonial['post_title']; ?>
                        </div>

                    </li>

                    <?php endforeach; ?>

                    <?php wp_reset_postdata(); ?>
                </ul>

            </div>
                    
        </div>
            
        <?php
        
        
    }
    
    function relia_gallery() {

        ob_start();
        $this->relia_output_gallery();
        $output = ob_get_clean();
        return $output;
    }
    
    public static function relia_output_gallery(){

        $args = array (
            'numberposts'   => -1,
            'post_status'   => 'publish',
            'post_type'     => 'gallery',
        );

        $gallery = get_posts($args); ?>


        <div id="gallery" class="relia-gallery">
            
            <?php $ctr = 0; ?>
            <?php foreach( $gallery as $item ) : $ctr++; ?>

                <?php $feat_image = wp_get_attachment_url( get_post_thumbnail_id( $item->ID ) ); ?>

                <div class="single-gallery-item blog-roll-item col-sm-6 col-md-3">
                    <div class="inner">
                        <a href="<?php echo $feat_image; ?>" data-lightbox="image-<?php echo $ctr; ?>" data-title="<?php echo $item->post_title; ?>">
                            <img alt="<?php echo $item->post_title; ?>"
                                src="<?php echo $feat_image; ?>"
                                data-image="<?php echo $feat_image; ?>"
                                data-description="<?php echo $item->post_title; ?>">
                        </a>
                    </div>
                </div>
            
            <?php endforeach; ?>
            
            <?php wp_reset_postdata(); ?>
            
        </div>


        <?php
    }

    function relia_custom_css() {
        ?>

        <style type="text/css">
 
            div.col-md-12.hero-banner {
                height: <?php echo esc_attr( get_theme_mod( 'relia_slider_height', 600 ) ) . 'px'; ?>;
            }
            
            .camera_overlayer,
            div.col-md-12.hero-banner .hero-overlay {
                background-color: rgba(0,0,0,<?php echo esc_attr( get_theme_mod( 'relia_slider_dark_tint', .30 ) ); ?>);
            }
            
            div.hero-overlay h2,
            div#slider-content-overlay h2 { font-size: <?php echo esc_attr( get_theme_mod( 'relia_jumbotron_heading_size', 50 ) ) . 'px'; ?>; }
            
            div.big-hero-buttons button { font-size: <?php echo esc_attr( get_theme_mod( 'relia_jumbotron_button_size', 14 ) ) . 'px'; ?>; }
            
        </style>
            
        <?php
    }

    function relia_homepage_script() {
        ?>

        <script type="text/javascript">
            jQuery(document).ready(function ($) {

                if (jQuery('#relia-slider').html()) {
                    relia_slider();
                }

                function relia_slider() {
                    
                    var height = get_height();

                    jQuery('#relia-slider').camera({
                        height: "<?php echo esc_attr( get_theme_mod( 'relia_slider_height', 600 ) ) . 'px'; ?>",
                        loader: "<?php echo esc_attr( get_theme_mod( 'relia_slide_loader', 'bar' ) ); ?>",
                        fx: "<?php echo esc_attr( get_theme_mod( 'relia_slide_transition', 'scrollHorz' ) ); ?>",
                        time: "<?php echo esc_attr( get_theme_mod( 'relia_slide_timer', '4000' ) ); ?>",
                        pagination: false,
                        thumbnails: false,
                        transPeriod: 1500,
                        overlayer: true,
                        playPause: false,
                        hover: false,
                        navigation: <?php echo esc_attr( get_theme_mod( 'relia_slide_pagination', 'false' ) ); ?>
                    });
                }
                
                function get_height() {

                    if (jQuery(window).width() < 601) {
                        return jQuery(window).height();
                    } else {
                        return jQuery(window).height();
                    }


                }                
                
            });

        </script>
        <?php
        if ( get_theme_mod( 'relia_js', false ) ) :

            echo get_theme_mod( 'relia_js', false );

        endif;
        ?>

        <?php
    }

}

class Event_Meta_Box {

    public function __construct() {

        if ( is_admin() ) {
            add_action( 'load-post.php', array ( $this, 'init_metabox' ) );
            add_action( 'load-post-new.php', array ( $this, 'init_metabox' ) );
        }
    }

    public function init_metabox() {

        add_action( 'add_meta_boxes', array ( $this, 'add_metabox' ) );
        add_action( 'save_post', array ( $this, 'save_metabox' ), 10, 2 );
    }

    public function add_metabox() {

        add_meta_box(
                'event_meta', __( 'Event Details', 'relia' ), array ( $this, 'render_event_meta' ), 'event', 'side', 'high'
        );
    }

    public function render_event_meta( $post ) {

        // Add nonce for security and authentication.
        wp_nonce_field( 'event_metanonce_action', 'event_metanonce' );

        // Retrieve an existing value from the database.
        $event_metadate = get_post_meta( $post->ID, 'event_metadate', true );
        $event_metatime_start = get_post_meta( $post->ID, 'event_metatime_start', true );
        $event_metatime_end = get_post_meta( $post->ID, 'event_metatime_end', true );
        $event_metalocation = get_post_meta( $post->ID, 'event_metalocation', true );
        $event_rsvp = get_post_meta( $post->ID, 'event_rsvp', true );
        $event_more = get_post_meta( $post->ID, 'event_more', true );

        // Set default values.
        if ( empty( $event_metadate ) )
            $event_metadate = '';
        if ( empty( $event_metatime_start ) )
            $event_metatime_start = '';
        if ( empty( $event_metatime_end ) )
            $event_metatime_end = '';
        if ( empty( $event_metalocation ) )
            $event_metalocation = '';
        if ( empty( $event_rsvp ) )
            $event_rsvp = '';
        if ( empty( $event_more ) )
            $event_more = '';

        // Form fields.
        echo '<table class="form-table">';

        echo '	<tr>';
        echo '		<th><label for="event_metadate" class="event_metadate_label">' . __( 'Date', 'relia' ) . '</label></th>';
        echo '		<td>';
        echo '			<input type="date" id="event_metadate" name="event_metadate" class="event_metadate_field" placeholder="' . esc_attr__( 'yyyy-mm-dd', 'relia' ) . '" value="' . esc_attr__( $event_metadate ) . '">';
        echo '		</td>';
        echo '	</tr>';

        echo '	<tr>';
        echo '		<th><label for="event_metatime_start" class="event_metatime_start_label">' . __( 'Time Start', 'relia' ) . '</label></th>';
        echo '		<td>';
        echo '			<input type="time" id="event_metatime_start" name="event_metatime_start" class="event_metatime_start_field" placeholder="' . esc_attr__( '--:-- AM', 'relia' ) . '" value="' . esc_attr__( $event_metatime_start ) . '">';
        echo '		</td>';
        echo '	</tr>';

        echo '	<tr>';
        echo '		<th><label for="event_metatime_end" class="event_metatime_end_label">' . __( 'Time End', 'relia' ) . '</label></th>';
        echo '		<td>';
        echo '			<input type="time" id="event_metatime_end" name="event_metatime_end" class="event_metatime_end_field" placeholder="' . esc_attr__( '--:-- PM', 'relia' ) . '" value="' . esc_attr__( $event_metatime_end ) . '">';
        echo '		</td>';
        echo '	</tr>';

        echo '	<tr>';
        echo '		<th><label for="event_metalocation" class="event_metalocation_label">' . __( 'Location', 'relia' ) . '</label></th>';
        echo '		<td>';
        echo '			<input type="text" id="event_metalocation" name="event_metalocation" class="event_metalocation_field" placeholder="' . esc_attr__( 'address, city, state', 'relia' ) . '" value="' . esc_attr__( $event_metalocation ) . '">';
        echo '		</td>';
        echo '	</tr>';



        echo '</table>';
    }

    public function save_metabox( $post_id, $post ) {

        // Add nonce for security and authentication.
        $nonce_name = isset( $_POST[ 'event_metanonce' ] ) ? $_POST[ 'event_metanonce' ] : '';
        $nonce_action = 'event_metanonce_action';

        // Check if a nonce is set.
        if ( !isset( $nonce_name ) )
            return;

        // Check if a nonce is valid.
        if ( !wp_verify_nonce( $nonce_name, $nonce_action ) )
            return;

        // Sanitize user input.
        $event_metanew_date = isset( $_POST[ 'event_metadate' ] ) ? sanitize_text_field( $_POST[ 'event_metadate' ] ) : '';
        $event_metanew_time_start = isset( $_POST[ 'event_metatime_start' ] ) ? sanitize_text_field( $_POST[ 'event_metatime_start' ] ) : '';
        $event_metanew_time_end = isset( $_POST[ 'event_metatime_end' ] ) ? sanitize_text_field( $_POST[ 'event_metatime_end' ] ) : '';
        $event_metanew_location = isset( $_POST[ 'event_metalocation' ] ) ? sanitize_text_field( $_POST[ 'event_metalocation' ] ) : '';
        $event_metanew_rsvp = isset( $_POST[ 'event_rsvp' ] ) ? sanitize_text_field( $_POST[ 'event_rsvp' ] ) : '';
        $event_metanew_more = isset( $_POST[ 'event_more' ] ) ? sanitize_text_field( $_POST[ 'event_more' ] ) : '';

        // Update the meta field in the database.
        update_post_meta( $post_id, 'event_metadate', $event_metanew_date );
        update_post_meta( $post_id, 'event_metatime_start', $event_metanew_time_start );
        update_post_meta( $post_id, 'event_metatime_end', $event_metanew_time_end );
        update_post_meta( $post_id, 'event_metalocation', $event_metanew_location );
        update_post_meta( $post_id, 'event_rsvp', $event_metanew_rsvp );
        update_post_meta( $post_id, 'event_more', $event_metanew_more );
    }

}

class Jobs_Meta_Box {

    public function __construct() {

        if ( is_admin() ) {
            add_action( 'load-post.php', array ( $this, 'init_metabox' ) );
            add_action( 'load-post-new.php', array ( $this, 'init_metabox' ) );
        }
    }

    public function init_metabox() {

        add_action( 'add_meta_boxes', array ( $this, 'add_metabox' ) );
        add_action( 'save_post', array ( $this, 'save_metabox' ), 10, 2 );
    }

    public function add_metabox() {

        add_meta_box(
                'job_meta', __( 'Job Details', 'text_domain' ), array ( $this, 'render_job_metabox' ), 'job', 'side', 'high'
        );
    }

    public function render_job_metabox( $post ) {

        // Retrieve an existing value from the database.
        $job_metatitle = get_post_meta( $post->ID, 'job_metatitle', true );
        $job_metadepartment = get_post_meta( $post->ID, 'job_metadepartment', true );

        // Set default values.
        if ( empty( $job_metatitle ) )
            $job_metatitle = '';
        if ( empty( $job_metadepartment ) )
            $job_metadepartment = '';

        // Form fields.
        echo '<table class="form-table">';

        echo '	<tr>';
        echo '		<th><label for="job_metatitle" class="job_metatitle_label">' . __( 'Location', 'text_domain' ) . '</label></th>';
        echo '		<td>';
        echo '			<input type="text" id="job_metatitle" name="job_metatitle" class="job_metatitle_field" placeholder="' . esc_attr__( '', 'text_domain' ) . '" value="' . esc_attr__( $job_metatitle ) . '">';
        echo '		</td>';
        echo '	</tr>';

        echo '	<tr>';
        echo '		<th><label for="job_metadepartment" class="job_metadepartment_label">' . __( 'Department', 'text_domain' ) . '</label></th>';
        echo '		<td>';
        echo '			<input type="text" id="job_metadepartment" name="job_metadepartment" class="job_metadepartment_field" placeholder="' . esc_attr__( '', 'text_domain' ) . '" value="' . esc_attr__( $job_metadepartment ) . '">';
        echo '		</td>';
        echo '	</tr>';

        echo '</table>';
    }

    public function save_metabox( $post_id, $post ) {

        // Sanitize user input.
        $job_metanew_title = isset( $_POST[ 'job_metatitle' ] ) ? sanitize_text_field( $_POST[ 'job_metatitle' ] ) : '';
        $job_metanew_department = isset( $_POST[ 'job_metadepartment' ] ) ? sanitize_text_field( $_POST[ 'job_metadepartment' ] ) : '';

        // Update the meta field in the database.
        update_post_meta( $post_id, 'job_metatitle', $job_metanew_title );
        update_post_meta( $post_id, 'job_metadepartment', $job_metanew_department );
    }

}


class Relia_Faq_Widget extends WP_Widget {

	public function __construct() {

            parent::__construct(
                    'relia-faqs-widget',
                    __( 'Relia FAQs', 'relia' ),
                    array(
                            'description' => __( 'Display the FAQs you have created', 'relia' ),
                    )
            );

	}

	public function widget( $args, $instance ) {

            AddOns::relia_output_faqs();

	}

	public function form( $instance ) {

	}

	public function update( $new_instance, $old_instance ) {

	}

}

function relia_faq_register_widgets() {
	register_widget( 'Relia_Faq_Widget' );
}
add_action( 'widgets_init', 'relia_faq_register_widgets' );

class Relia_News_Widget extends WP_Widget {

	public function __construct() {

            parent::__construct(
                    'relia-news-widget',
                    __( 'Relia News', 'relia' ),
                    array(
                            'description' => __( 'Display the News you have created', 'relia' ),
                    )
            );

	}

	public function widget( $args, $instance ) {
            
            AddOns::relia_output_news();

	}

	public function form( $instance ) {

	}

	public function update( $new_instance, $old_instance ) {

	}

}

class Relia_Events_Widget extends WP_Widget {

	public function __construct() {

            parent::__construct(
                    'relia-events-widget',
                    __( 'Relia Current Events', 'relia' ),
                    array(
                            'description' => __( 'Display the Events you have created', 'relia' ),
                    )
            );

	}

	public function widget( $args, $instance ) {
            
            AddOns::relia_output_current_events();

	}

	public function form( $instance ) {

	}

	public function update( $new_instance, $old_instance ) {

	}

}


class Relia_Contact_Info extends WP_Widget {

	public function __construct() {

		parent::__construct(
			'relia-contact-info',
			__( 'Relia Contact Info', 'relia' ),
			array(
				'classname'   => 'relia-contact-info',
			)
		);

	}

	public function widget( $args, $instance ) { ?>
            
        <div class="relia-contact-info">
            
                <div class="col-sm-12">

                    <h2 class="widget-title"><?php echo !empty( $instance['relia_contact_title'] ) ? $instance['relia_contact_title'] : '' ?></h2>
                    <div class="diviver"><span></span></div>
                    <div class="textwidget"><?php echo !empty( $instance['relia_contact_subtitle'] ) ? $instance['relia_contact_subtitle'] : '' ?></div>

                    <div class="row">
                                
                            <?php if( !empty( $instance['relia_contact_phone'] ) ) : ?>
                            <div class="col-sm-4">
                                <div>
                                    
                                    <span class="fa fa-phone"></span>
                                
                                    <div><?php echo !empty( $instance['relia_contact_phone'] ) ? $instance['relia_contact_phone'] : '' ?></div>
                                    
                                </div>
                            </div>
                            <?php endif; ?>

                            <?php if( !empty( $instance['relia_contact_email'] ) ) : ?>
                            <div class="col-sm-4">
                                <div>
                                    
                                    <span class="fa fa-envelope"></span>
                                
                                    <div><?php echo !empty( $instance['relia_contact_email'] ) ? $instance['relia_contact_email'] : '' ?></div>
                                    
                                </div>
                                
                            </div>
                            <?php endif; ?>

                            <?php if( !empty( $instance['relia_contact_address'] ) ) : ?>
                            <div class="col-sm-4">
                                <div>
                                    
                                    <span class="fa fa-map"></span>
                                
                                    <div><?php echo !empty( $instance['relia_contact_address'] ) ? $instance['relia_contact_address'] : '' ?></div>
                                
                                </div>
                            </div>
                            <?php endif; ?>
                   
                    </div>

                </div>
                    
        </div>

        
        
	<?php }

	public function form( $instance ) {

		// Set default values
		$instance = wp_parse_args( (array) $instance, array( 
			'relia_contact_title' => '',
			'relia_contact_subtitle' => '',
			'relia_contact_phone' => '',
			'relia_contact_email' => '',
			'relia_contact_address' => '',
		) );

		// Retrieve an existing value from the database
		$relia_contact_title = !empty( $instance['relia_contact_title'] ) ? $instance['relia_contact_title'] : '';
		$relia_contact_subtitle = !empty( $instance['relia_contact_subtitle'] ) ? $instance['relia_contact_subtitle'] : '';
		$relia_contact_phone = !empty( $instance['relia_contact_phone'] ) ? $instance['relia_contact_phone'] : '';
		$relia_contact_email = !empty( $instance['relia_contact_email'] ) ? $instance['relia_contact_email'] : '';
		$relia_contact_address = !empty( $instance['relia_contact_address'] ) ? $instance['relia_contact_address'] : '';

		// Form fields
		echo '<p>';
		echo '	<label for="' . $this->get_field_id( 'relia_contact_title' ) . '" class="relia_contact_title_label">' . __( 'Title', 'relia' ) . '</label>';
		echo '	<input type="text" id="' . $this->get_field_id( 'relia_contact_title' ) . '" name="' . $this->get_field_name( 'relia_contact_title' ) . '" class="widefat" placeholder="' . esc_attr__( '', 'relia' ) . '" value="' . esc_attr( $relia_contact_title ) . '">';
		echo '</p>';

		echo '<p>';
		echo '	<label for="' . $this->get_field_id( 'relia_contact_subtitle' ) . '" class="relia_contact_subtitle_label">' . __( 'Subtitle', 'relia' ) . '</label>';
		echo '	<input type="text" id="' . $this->get_field_id( 'relia_contact_subtitle' ) . '" name="' . $this->get_field_name( 'relia_contact_subtitle' ) . '" class="widefat" placeholder="' . esc_attr__( '', 'relia' ) . '" value="' . esc_attr( $relia_contact_subtitle ) . '">';
		echo '</p>';

		echo '<p>';
		echo '	<label for="' . $this->get_field_id( 'relia_contact_phone' ) . '" class="relia_contact_phone_label">' . __( 'Phone', 'relia' ) . '</label>';
		echo '	<input type="text" id="' . $this->get_field_id( 'relia_contact_phone' ) . '" name="' . $this->get_field_name( 'relia_contact_phone' ) . '" class="widefat" placeholder="' . esc_attr__( '', 'relia' ) . '" value="' . esc_attr( $relia_contact_phone ) . '">';
		echo '</p>';

		echo '<p>';
		echo '	<label for="' . $this->get_field_id( 'relia_contact_email' ) . '" class="relia_contact_email_label">' . __( 'Email', 'relia' ) . '</label>';
		echo '	<input type="text" id="' . $this->get_field_id( 'relia_contact_email' ) . '" name="' . $this->get_field_name( 'relia_contact_email' ) . '" class="widefat" placeholder="' . esc_attr__( '', 'relia' ) . '" value="' . esc_attr( $relia_contact_email ) . '">';
		echo '</p>';

		echo '<p>';
		echo '	<label for="' . $this->get_field_id( 'relia_contact_address' ) . '" class="relia_contact_address_label">' . __( 'Address', 'relia' ) . '</label>';
		echo '	<input type="text" id="' . $this->get_field_id( 'relia_contact_address' ) . '" name="' . $this->get_field_name( 'relia_contact_address' ) . '" class="widefat" placeholder="' . esc_attr__( '', 'relia' ) . '" value="' . esc_attr( $relia_contact_address ) . '">';
		echo '</p>';

	}

	public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['relia_contact_title'] = !empty( $new_instance['relia_contact_title'] ) ? strip_tags( $new_instance['relia_contact_title'] ) : '';
		$instance['relia_contact_subtitle'] = !empty( $new_instance['relia_contact_subtitle'] ) ? strip_tags( $new_instance['relia_contact_subtitle'] ) : '';
		$instance['relia_contact_phone'] = !empty( $new_instance['relia_contact_phone'] ) ? strip_tags( $new_instance['relia_contact_phone'] ) : '';
		$instance['relia_contact_email'] = !empty( $new_instance['relia_contact_email'] ) ? strip_tags( $new_instance['relia_contact_email'] ) : '';
		$instance['relia_contact_address'] = !empty( $new_instance['relia_contact_address'] ) ? strip_tags( $new_instance['relia_contact_address'] ) : '';

		return $instance;

	}

}


class Relia_Testimonials_Widget extends WP_Widget {

	public function __construct() {

            parent::__construct(
                    'relia-testimonials-widget',
                    __( 'Relia Testimonials Carousel', 'relia' ),
                    array(
                            'description' => __( 'Display the Testimonials you have created', 'relia' )
                    )
            );

	}

	public function widget( $args, $instance ) {
            
            AddOns::relia_output_testimonials( 'owl-carousel owl-theme' );

	}

	public function form( $instance ) {

	}

	public function update( $new_instance, $old_instance ) {

	}

}

class Relia_Gallery_Widget extends WP_Widget {

	public function __construct() {

            parent::__construct(
                    'relia-gallery-widget',
                    __( 'Relia Gallery', 'relia' ),
                    array(
                            'description' => __( 'Display the Gallery you have created', 'relia' )
                    )
            );

	}

	public function widget( $args, $instance ) {
            
            AddOns::relia_output_gallery();

	}

	public function form( $instance ) {

	}

	public function update( $new_instance, $old_instance ) {

	}

}

class Relia_Contact_Form extends WP_Widget {

	public function __construct() {

		parent::__construct(
			'relia-contact-form',
			__( 'Relia Contact Form', 'relia' )
		);

	}

	public function widget( $args, $instance ) { 
            AddOns::relia_contact_form( $instance );
	}

	public function form( $instance ) {

		// Set default values
		$instance = wp_parse_args( (array) $instance, array( 
			'relia_contactfrom_label' => __( 'Name', 'relia' ),
			'relia_contactemail_label' => __( 'Email Address', 'relia' ),
			'relia_contactmessage_label' => __( 'Message', 'relia' ),
			'relia_contactemail' => '',
                        'relia_contactsubmit_label' => __( 'Submit', 'relia' )
		) );

		// Retrieve an existing value from the database
		$relia_contactfrom_label = !empty( $instance['relia_contactfrom_label'] ) ? $instance['relia_contactfrom_label'] : '';
		$relia_contactemail_label = !empty( $instance['relia_contactemail_label'] ) ? $instance['relia_contactemail_label'] : '';
		$relia_contactmessage_label = !empty( $instance['relia_contactmessage_label'] ) ? $instance['relia_contactmessage_label'] : '';
		$relia_contactemail = !empty( $instance['relia_contactemail'] ) ? $instance['relia_contactemail'] : '';
		$relia_contactsubmit_label = !empty( $instance['relia_contactsubmit_label'] ) ? $instance['relia_contactsubmit_label'] : '';

		// Form fields
		echo '<p>';
		echo '	<label for="' . $this->get_field_id( 'relia_contactfrom_label' ) . '" class="relia_contactfrom_label_label">' . __( 'From Name Label', 'relia' ) . '</label>';
		echo '	<input type="text" id="' . $this->get_field_id( 'relia_contactfrom_label' ) . '" name="' . $this->get_field_name( 'relia_contactfrom_label' ) . '" class="widefat" placeholder="' . esc_attr__( '', 'relia' ) . '" value="' . esc_attr( $relia_contactfrom_label ) . '">';
		echo '</p>';

		echo '<p>';
		echo '	<label for="' . $this->get_field_id( 'relia_contactemail_label' ) . '" class="relia_contactemail_label_label">' . __( 'Email Address Label', 'relia' ) . '</label>';
		echo '	<input type="text" id="' . $this->get_field_id( 'relia_contactemail_label' ) . '" name="' . $this->get_field_name( 'relia_contactemail_label' ) . '" class="widefat" placeholder="' . esc_attr__( '', 'relia' ) . '" value="' . esc_attr( $relia_contactemail_label ) . '">';
		echo '</p>';

		echo '<p>';
		echo '	<label for="' . $this->get_field_id( 'relia_contactmessage_label' ) . '" class="relia_contactmessage_label_label">' . __( 'Message Label', 'relia' ) . '</label>';
		echo '	<input type="text" id="' . $this->get_field_id( 'relia_contactmessage_label' ) . '" name="' . $this->get_field_name( 'relia_contactmessage_label' ) . '" class="widefat" placeholder="' . esc_attr__( '', 'relia' ) . '" value="' . esc_attr( $relia_contactmessage_label ) . '">';
		echo '</p>';

		echo '<p>';
		echo '	<label for="' . $this->get_field_id( 'relia_contactemail' ) . '" class="relia_contactemail_label">' . __( 'Recipient Email', 'relia' ) . '</label>';
		echo '	<input type="email" id="' . $this->get_field_id( 'relia_contactemail' ) . '" name="' . $this->get_field_name( 'relia_contactemail' ) . '" class="widefat" placeholder="' . esc_attr__( '', 'relia' ) . '" value="' . esc_attr( $relia_contactemail ) . '">';
		echo '</p>';

		echo '<p>';
		echo '	<label for="' . $this->get_field_id( 'relia_contactsubmit_label' ) . '" class="relia_contactemail_label">' . __( 'Recipient Email', 'relia' ) . '</label>';
		echo '	<input type="email" id="' . $this->get_field_id( 'relia_contactsubmit_label' ) . '" name="' . $this->get_field_name( 'relia_contactsubmit_label' ) . '" class="widefat" placeholder="' . esc_attr__( '', 'relia' ) . '" value="' . esc_attr( $relia_contactsubmit_label ) . '">';
		echo '</p>';

	}

	public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['relia_contactfrom_label'] = !empty( $new_instance['relia_contactfrom_label'] ) ? strip_tags( $new_instance['relia_contactfrom_label'] ) : '';
		$instance['relia_contactemail_label'] = !empty( $new_instance['relia_contactemail_label'] ) ? strip_tags( $new_instance['relia_contactemail_label'] ) : '';
		$instance['relia_contactmessage_label'] = !empty( $new_instance['relia_contactmessage_label'] ) ? strip_tags( $new_instance['relia_contactmessage_label'] ) : '';
		$instance['relia_contactemail'] = !empty( $new_instance['relia_contactemail'] ) ? strip_tags( $new_instance['relia_contactemail'] ) : '';
		$instance['relia_contactsubmit_label'] = !empty( $new_instance['relia_contactsubmit_label'] ) ? strip_tags( $new_instance['relia_contactsubmit_label'] ) : '';

		return $instance;

	}

}

class Relia_Pricing_Table extends WP_Widget {

	public function __construct() {

		parent::__construct(
			'relia-pricing-table',
			__( 'Relia Pricing Table', 'relia' ),
			array(
				'classname'   => 'relia-pricing-table',
			)
		);

	}

	public function widget( $args, $instance ) { ?>

        <div class="col-sm-4 relia-pricing-table">
            <div class="inner">
                        
                <div class="header">
                    <?php echo empty( $instance['relia_pricing_table_special'] ) ? null : '<span class="special"><span class="fa fa-star"></span></span>'; ?>
                    <div class="price"><?php echo !empty( $instance['relia_pricing_table_price'] ) ? $instance['relia_pricing_table_price'] : null ?></div>
                </div>
                
                <?php if ( !empty( $instance['relia_pricing_table_link'] ) ) : ?>
                    <a href="<?php echo esc_url( $instance['relia_pricing_table_link'] ); ?>">
                <?php endif; ?>
                
                <h2 class="title"><?php echo !empty( $instance['relia_pricing_table_title'] ) ? $instance['relia_pricing_table_title'] : null ?></h2>
                
                <?php if ( !empty( $instance['relia_pricing_table_link'] ) ) : ?>
                    </a>
                <?php endif; ?>
                
                <div class="diviver"><span></span></div>
                <div class="subtitle"><?php echo !empty( $instance['relia_pricing_table_subtitle'] ) ? $instance['relia_pricing_table_subtitle'] : null ?></div>
                <div class="description">
                    <?php echo !empty( $instance['relia_pricing_table_description'] ) ? $instance['relia_pricing_table_description'] : null ?>
                </div>
                
            </div>
        </div>
            
            
	<?php }

	public function form( $instance ) {

		// Set default values
		$instance = wp_parse_args( (array) $instance, array( 
			'relia_pricing_table_special' => '',
			'relia_pricing_table_title' => '',
			'relia_pricing_table_price' => '',
			'relia_pricing_table_subtitle' => '',
			'relia_pricing_table_description' => '',
			'relia_pricing_table_link' => '',
		) );

		// Retrieve an existing value from the database
		$relia_pricing_table_special = !empty( $instance['relia_pricing_table_special'] ) ? $instance['relia_pricing_table_special'] : '';
		$relia_pricing_table_title = !empty( $instance['relia_pricing_table_title'] ) ? $instance['relia_pricing_table_title'] : '';
		$relia_pricing_table_price = !empty( $instance['relia_pricing_table_price'] ) ? $instance['relia_pricing_table_price'] : '';
		$relia_pricing_table_subtitle = !empty( $instance['relia_pricing_table_subtitle'] ) ? $instance['relia_pricing_table_subtitle'] : '';
		$relia_pricing_table_description = !empty( $instance['relia_pricing_table_description'] ) ? $instance['relia_pricing_table_description'] : '';
		$relia_pricing_table_link = !empty( $instance['relia_pricing_table_link'] ) ? $instance['relia_pricing_table_link'] : '';

		// Form fields
		echo '<p>';
		echo '	<label>';
		echo '		<input type="checkbox" id="' . $this->get_field_id( 'relia_pricing_table_special' ) . '" name="' . $this->get_field_name( 'relia_pricing_table_special' ) . '" class="widefat" placeholder="' . esc_attr__( '', 'relia' ) . '" value="1" ' . checked( $relia_pricing_table_special, true, false ) . '>' . __( 'Special', 'relia' );
		echo '	</label><br>';
		echo '</p>';

		echo '<p>';
		echo '	<label for="' . $this->get_field_id( 'relia_pricing_table_title' ) . '" class="relia_pricing_table_title_label">' . __( 'Title', 'relia' ) . '</label>';
		echo '	<input type="text" id="' . $this->get_field_id( 'relia_pricing_table_title' ) . '" name="' . $this->get_field_name( 'relia_pricing_table_title' ) . '" class="widefat" placeholder="' . esc_attr__( '', 'relia' ) . '" value="' . esc_attr( $relia_pricing_table_title ) . '">';
		echo '</p>';

		echo '<p>';
		echo '	<label for="' . $this->get_field_id( 'relia_pricing_table_price' ) . '" class="relia_pricing_table_price_label">' . __( 'Price', 'relia' ) . '</label>';
		echo '	<input type="text" id="' . $this->get_field_id( 'relia_pricing_table_price' ) . '" name="' . $this->get_field_name( 'relia_pricing_table_price' ) . '" class="widefat" placeholder="' . esc_attr__( '', 'relia' ) . '" value="' . esc_attr( $relia_pricing_table_price ) . '">';
		echo '</p>';

		echo '<p>';
		echo '	<label for="' . $this->get_field_id( 'relia_pricing_table_subtitle' ) . '" class="relia_pricing_table_subtitle_label">' . __( 'Subtitle', 'relia' ) . '</label>';
		echo '	<input type="text" id="' . $this->get_field_id( 'relia_pricing_table_subtitle' ) . '" name="' . $this->get_field_name( 'relia_pricing_table_subtitle' ) . '" class="widefat" placeholder="' . esc_attr__( '', 'relia' ) . '" value="' . esc_attr( $relia_pricing_table_subtitle ) . '">';
		echo '</p>';

		echo '<p>';
		echo '	<label for="' . $this->get_field_id( 'relia_pricing_table_description' ) . '" class="relia_pricing_table_description_label">' . __( 'Description', 'relia' ) . '</label>';
		echo '	<textarea id="' . $this->get_field_id( 'relia_pricing_table_description' ) . '" name="' . $this->get_field_name( 'relia_pricing_table_description' ) . '" class="widefat" placeholder="' . esc_attr__( '', 'relia' ) . '">' . $relia_pricing_table_description . '</textarea>';
		echo '</p>';

		echo '<p>';
		echo '	<label for="' . $this->get_field_id( 'relia_pricing_table_link' ) . '" class="relia_pricing_table_link_label">' . __( 'Title Link', 'relia' ) . '</label>';
		echo '	<textarea id="' . $this->get_field_id( 'relia_pricing_table_link' ) . '" name="' . $this->get_field_name( 'relia_pricing_table_link' ) . '" class="widefat" placeholder="' . esc_attr__( '', 'relia' ) . '">' . $relia_pricing_table_link . '</textarea>';
		echo '</p>';

	}

	public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['relia_pricing_table_special'] = !empty( $new_instance['relia_pricing_table_special'] ) ? true : false;
		$instance['relia_pricing_table_title'] = !empty( $new_instance['relia_pricing_table_title'] ) ? strip_tags( $new_instance['relia_pricing_table_title'] ) : '';
		$instance['relia_pricing_table_price'] = !empty( $new_instance['relia_pricing_table_price'] ) ? strip_tags( $new_instance['relia_pricing_table_price'] ) : '';
		$instance['relia_pricing_table_subtitle'] = !empty( $new_instance['relia_pricing_table_subtitle'] ) ? strip_tags( $new_instance['relia_pricing_table_subtitle'] ) : '';
		$instance['relia_pricing_table_description'] = !empty( $new_instance['relia_pricing_table_description'] ) ? ( $new_instance['relia_pricing_table_description'] ) : '';
		$instance['relia_pricing_table_link'] = !empty( $new_instance['relia_pricing_table_link'] ) ? ( $new_instance['relia_pricing_table_link'] ) : '';

		return $instance;

	}

}

class Relia_Service extends WP_Widget {

	public function __construct() {

		parent::__construct(
			'relia-service',
			__( 'Relia Service', 'relia' ),
			array(
				'classname'   => 'relia-service',
			)                        
		);

	}

	public function widget( $args, $instance ) { ?>

        <div class="relia-service col-sm-4">
            <div class="service-space-wrapper">
                
                <?php if ( !empty( $instance['relia_service_link'] ) ) : ?>
                    <a href="<?php echo esc_url( $instance['relia_service_link'] ); ?>">
                <?php endif; ?>
                
                    <div class="icon-container">
                        <span class="<?php echo isset( $instance['relia_service_icon'] ) ? $instance['relia_service_icon'] : ''; ?>"></span>
                    </div>
                        
                <?php if ( !empty( $instance['relia_service_link'] ) ) : ?>
                    </a>
                <?php endif; ?>
                        
            </div>
            <div class="service-space-wrapper">
                <h3><?php echo isset( $instance['relia_service_title'] ) ? $instance['relia_service_title'] : ''; ?></h3>
            </div>
            <div class="service-space-wrapper">
            <div class="diviver"><span></span></div>
                <p><?php echo isset( $instance['relia_service_description'] ) ? $instance['relia_service_description'] : ''; ?></p>
            </div>
        </div>
        
        
	<?php }

	public function form( $instance ) {

		// Set default values
		$instance = wp_parse_args( (array) $instance, array( 
			'relia_service_title' => '',
			'relia_service_icon' => '',
			'relia_service_description' => '', 
			'relia_service_link' => '',
		) );

		// Retrieve an existing value from the database
		$relia_service_title = !empty( $instance['relia_service_title'] ) ? $instance['relia_service_title'] : '';
		$relia_service_icon = !empty( $instance['relia_service_icon'] ) ? $instance['relia_service_icon'] : '';
		$relia_service_description = !empty( $instance['relia_service_description'] ) ? $instance['relia_service_description'] : '';
		$relia_service_link = !empty( $instance['relia_service_link'] ) ? $instance['relia_service_link'] : '';

		// Form fields
		echo '<p>';
		echo '	<label for="' . $this->get_field_id( 'relia_service_title' ) . '" class="relia_service_title_label">' . __( 'Title', 'relia' ) . '</label>';
		echo '	<input type="text" id="' . $this->get_field_id( 'relia_service_title' ) . '" name="' . $this->get_field_name( 'relia_service_title' ) . '" class="widefat" placeholder="' . esc_attr__( '', 'relia' ) . '" value="' . esc_attr( $relia_service_title ) . '">';
		echo '</p>';
		
		echo '<p>';
		echo '	<label for="' . $this->get_field_id( 'relia_service_icon' ) . '" class="relia_service_icon_label">' . __( 'Icon', 'relia' ) . '</label>';
		echo '	<select id="' . $this->get_field_id( 'relia_service_icon' ) . '" name="' . $this->get_field_name( 'relia_service_icon' ) . '" class="widefat">';
                
                foreach( relia_icons() as $key=>$value ) :
		echo '		<option value="' . $key . '" ' . selected( $relia_service_icon, $key, false ) . '> ' . $value . '</option>';
		endforeach;
                
                echo '	</select>';
		echo '</p>';

		echo '<p>';
		echo '	<label for="' . $this->get_field_id( 'relia_service_description' ) . '" class="relia_service_description_label">' . __( 'Description', 'relia' ) . '</label>';
		echo '	<textarea id="' . $this->get_field_id( 'relia_service_description' ) . '" name="' . $this->get_field_name( 'relia_service_description' ) . '" class="widefat" placeholder="' . esc_attr__( '', 'relia' ) . '">' . $relia_service_description . '</textarea>';
		echo '</p>';
                
                echo '<p>';
		echo '	<label for="' . $this->get_field_id( 'relia_service_link' ) . '" class="relia_service_link_label">' . __( 'Icon Link', 'relia' ) . '</label>';
		echo '	<input type="text" id="' . $this->get_field_id( 'relia_service_link' ) . '" name="' . $this->get_field_name( 'relia_service_link' ) . '" class="widefat" placeholder="' . esc_attr__( '', 'relia' ) . '" value="' . esc_attr( $relia_service_link ) . '">';
		echo '</p>';

	}

	public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['relia_service_title'] = !empty( $new_instance['relia_service_title'] ) ? strip_tags( $new_instance['relia_service_title'] ) : '';
		$instance['relia_service_icon'] = !empty( $new_instance['relia_service_icon'] ) ? strip_tags( $new_instance['relia_service_icon'] ) : '';
		$instance['relia_service_description'] = !empty( $new_instance['relia_service_description'] ) ? strip_tags( $new_instance['relia_service_description'] ) : '';
		$instance['relia_service_link'] = !empty( $new_instance['relia_service_link'] ) ? strip_tags( $new_instance['relia_service_link'] ) : '';

		return $instance;

	}

}

class Relia_CTA extends WP_Widget {

	public function __construct() {

		parent::__construct(
			'relia-cta',
			__( 'Relia Call to Action', 'relia' ),
			array(
				'classname'   => 'relia-cta',
			)
		);

	}

	public function widget( $args, $instance ) { ?>

            <div class="relia-callout col-sm-12">
                
                <h3 class="widget-title"><?php echo isset( $instance['relia_cta_title'] ) ? $instance['relia_cta_title'] : ''; ?></h3>
                <div class="diviver"><span></span></div>
                <div class="textwidget"><?php echo isset( $instance['relia_cta_detail'] ) ? $instance['relia_cta_detail'] : ''; ?></div>
                <div>
                    <?php if( isset( $instance['relia_cta_button1_url'] ) ) : ?>
                        <a class="relia-button" href="<?php echo esc_url( $instance['relia_cta_button1_url'] ); ?>"><?php echo isset( $instance['relia_cta_button1_text'] ) ? $instance['relia_cta_button1_text'] : ''; ?></a>
                    <?php endif; ?>
                    <?php if( isset( $instance['relia_cta_button2_url'] ) ) : ?>
                        <a class="relia-button" href="<?php echo esc_url( $instance['relia_cta_button2_url'] ); ?>"><?php echo isset( $instance['relia_cta_button2_text'] ) ? $instance['relia_cta_button2_text'] : ''; ?></a>
                    <?php endif; ?>
                </div>
                
            </div>
        
        
	<?php }

	public function form( $instance ) {

		// Set default values
		$instance = wp_parse_args( (array) $instance, array( 
			'relia_cta_title' => '',
			'relia_cta_detail' => '',
			'relia_cta_button1_text' => '',
			'relia_cta_button1_url' => '',
			'relia_cta_button2_text' => '',
			'relia_cta_button2_url' => '',
		) );

		// Retrieve an existing value from the database
		$relia_cta_title = !empty( $instance['relia_cta_title'] ) ? $instance['relia_cta_title'] : '';
		$relia_cta_detail = !empty( $instance['relia_cta_detail'] ) ? $instance['relia_cta_detail'] : '';
		$relia_cta_button1_text = !empty( $instance['relia_cta_button1_text'] ) ? $instance['relia_cta_button1_text'] : '';
		$relia_cta_button1_url = !empty( $instance['relia_cta_button1_url'] ) ? $instance['relia_cta_button1_url'] : '';
		$relia_cta_button2_text = !empty( $instance['relia_cta_button2_text'] ) ? $instance['relia_cta_button2_text'] : '';
		$relia_cta_button2_url = !empty( $instance['relia_cta_button2_url'] ) ? $instance['relia_cta_button2_url'] : '';

		// Form fields
		echo '<p>';
		echo '	<label for="' . $this->get_field_id( 'relia_cta_title' ) . '" class="relia_cta_title_label">' . __( 'Title', 'relia' ) . '</label>';
		echo '	<input type="text" id="' . $this->get_field_id( 'relia_cta_title' ) . '" name="' . $this->get_field_name( 'relia_cta_title' ) . '" class="widefat" placeholder="' . esc_attr__( '', 'relia' ) . '" value="' . esc_attr( $relia_cta_title ) . '">';
		echo '</p>';

		echo '<p>';
		echo '	<label for="' . $this->get_field_id( 'relia_cta_detail' ) . '" class="relia_cta_detail_label">' . __( 'Details', 'relia' ) . '</label>';
		echo '	<textarea id="' . $this->get_field_id( 'relia_cta_detail' ) . '" name="' . $this->get_field_name( 'relia_cta_detail' ) . '" class="widefat" placeholder="' . esc_attr__( '', 'relia' ) . '">' . $relia_cta_detail . '</textarea>';
		echo '</p>';

		echo '<p>';
		echo '	<label for="' . $this->get_field_id( 'relia_cta_button1_text' ) . '" class="relia_cta_button1_text_label">' . __( 'Button 1 Text', 'relia' ) . '</label>';
		echo '	<input type="text" id="' . $this->get_field_id( 'relia_cta_button1_text' ) . '" name="' . $this->get_field_name( 'relia_cta_button1_text' ) . '" class="widefat" placeholder="' . esc_attr__( '', 'relia' ) . '" value="' . esc_attr( $relia_cta_button1_text ) . '">';
		echo '</p>';

		echo '<p>';
		echo '	<label for="' . $this->get_field_id( 'relia_cta_button1_url' ) . '" class="relia_cta_button1_url_label">' . __( 'Button 1 URL', 'relia' ) . '</label>';
		echo '	<input type="url" id="' . $this->get_field_id( 'relia_cta_button1_url' ) . '" name="' . $this->get_field_name( 'relia_cta_button1_url' ) . '" class="widefat" placeholder="' . esc_attr__( '', 'relia' ) . '" value="' . esc_attr( $relia_cta_button1_url ) . '">';
		echo '</p>';

		echo '<p>';
		echo '	<label for="' . $this->get_field_id( 'relia_cta_button2_text' ) . '" class="relia_cta_button2_text_label">' . __( 'Button 2 Text', 'relia' ) . '</label>';
		echo '	<input type="text" id="' . $this->get_field_id( 'relia_cta_button2_text' ) . '" name="' . $this->get_field_name( 'relia_cta_button2_text' ) . '" class="widefat" placeholder="' . esc_attr__( '', 'relia' ) . '" value="' . esc_attr( $relia_cta_button2_text ) . '">';
		echo '</p>';
                
		echo '<p>';
		echo '	<label for="' . $this->get_field_id( 'relia_cta_button2_url' ) . '" class="relia_cta_button2_url_label">' . __( 'Button 2 URL', 'relia' ) . '</label>';
		echo '	<input type="url" id="' . $this->get_field_id( 'relia_cta_button2_url' ) . '" name="' . $this->get_field_name( 'relia_cta_button2_url' ) . '" class="widefat" placeholder="' . esc_attr__( '', 'relia' ) . '" value="' . esc_attr( $relia_cta_button2_url ) . '">';
		echo '</p>';

	}

	public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['relia_cta_title'] = !empty( $new_instance['relia_cta_title'] ) ? strip_tags( $new_instance['relia_cta_title'] ) : '';
		$instance['relia_cta_detail'] = !empty( $new_instance['relia_cta_detail'] ) ? strip_tags( $new_instance['relia_cta_detail'] ) : '';
		$instance['relia_cta_button1_text'] = !empty( $new_instance['relia_cta_button1_text'] ) ? strip_tags( $new_instance['relia_cta_button1_text'] ) : '';
		$instance['relia_cta_button1_url'] = !empty( $new_instance['relia_cta_button1_url'] ) ? strip_tags( $new_instance['relia_cta_button1_url'] ) : '';
		$instance['relia_cta_button2_text'] = !empty( $new_instance['relia_cta_button2_text'] ) ? strip_tags( $new_instance['relia_cta_button2_text'] ) : '';
		$instance['relia_cta_button2_url'] = !empty( $new_instance['relia_cta_button2_url'] ) ? strip_tags( $new_instance['relia_cta_button2_url'] ) : '';

		return $instance;

	}

}

function relia_send_message(){

    $name = sanitize_text_field( $_POST['name'] );
    $email = sanitize_text_field( $_POST['email'] );
    $message_entered = sanitize_text_field( $_POST['message'] );
    $recipient_email = sanitize_text_field( $_POST['recipient'] );
    
    $message = 'From: ' . $name . ' || Sender Email: ' . $email . ' || Message: ' . $message_entered;

    $widget = new Relia_Contact_Form();
    $settings = $widget->get_settings();
    $settings = reset( $settings );
    
    wp_mail( $recipient_email, __( 'New message from ' . get_option('blog_name'), 'relia' ), $message );
    
    echo 1;
    exit();

}
add_action('wp_ajax_relia_send_message', 'relia_send_message' );
add_action('wp_ajax_nopriv_relia_send_message', 'relia_send_message' );
