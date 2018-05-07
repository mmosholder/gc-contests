<?php

// ---------------------------------------------
// Jumbotron Panel
// ---------------------------------------------
$wp_customize->add_panel( 'relia_jumbotron_panel', array (
    'title'                 => __( 'Jumbotron', 'relia' ),
    'description'           => __( 'Customize the appearance of the site Jumbotron', 'relia' ),
    'priority'              => 10
) );

// ---------------------------------------------
// Jumbotron Sections
// ---------------------------------------------

$wp_customize->add_section( 'relia_slider_images_section', array (
    'title'                 => __( 'Slider Images', 'relia' ),
    'description'           => __( 'Use the settings below to upload your images for the slider', 'relia' ),
    'panel'                 => 'relia_jumbotron_panel',
) );

$wp_customize->add_section( 'relia_static_bg_section', array(
    'title'                 => __( 'Static Background', 'relia'),
    'description'           => __( 'Customize the large banner on your homepage', 'relia' ),
    'panel'                 => 'relia_jumbotron_panel'
) );
    
$wp_customize->add_section( 'relia_slide_settings_section', array (
    'title'                 => __( 'Jumbotron Settings', 'relia' ),
    'description'           => __( 'Adjust the slider speed & animation', 'relia' ),
    'panel'                 => 'relia_jumbotron_panel',
) );

// ---------------------------------------------
// relia_slider_images_section
// ---------------------------------------------

    // 1st slide
    $wp_customize->add_setting( 'relia_slider_image[slide1]', array (
        'default'               => get_template_directory_uri() . '/inc/images/bw-gear.jpg',
        'sanitize_callback'     => 'esc_url_raw'
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'relia_slider_image[slide1]', array (
        'label' =>              __( 'Slide 1', 'relia' ),
        'section'               => 'relia_slider_images_section',
        'mime_type'             => 'image',
        'settings'              => 'relia_slider_image[slide1]',
        'description'           => __( 'Select the image file for Slide 1', 'relia' ),
    ) ) );
    
    // 2nd slide
    $wp_customize->add_setting( 'relia_slider_image[slide2]', array (
        'default'               => get_template_directory_uri() . '/inc/images/bw-gear.jpg',
        'sanitize_callback'     => 'esc_url_raw'
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'relia_slider_image[slide2]', array (
        'label' =>              __( 'Slide 2', 'relia' ),
        'section'               => 'relia_slider_images_section',
        'mime_type'             => 'image',
        'settings'              => 'relia_slider_image[slide2]',
        'description'           => __( 'Select the image file for Slide 2', 'relia' ),
    ) ) );
    
    // 3rd slide
    $wp_customize->add_setting( 'relia_slider_image[slide3]', array (
        'default'               => get_template_directory_uri() . '/inc/images/bw-gear.jpg',
        'sanitize_callback'     => 'esc_url_raw'
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'relia_slider_image[slide3]', array (
        'label' =>              __( 'Slide 3', 'relia' ),
        'section'               => 'relia_slider_images_section',
        'mime_type'             => 'image',
        'settings'              => 'relia_slider_image[slide3]',
        'description'           => __( 'Select the image file for Slide 3', 'relia' ),
    ) ) );
    
    // 4th slide
    $wp_customize->add_setting( 'relia_slider_image[slide4]', array (
        'default'               => get_template_directory_uri() . '/inc/images/bw-gear.jpg',
        'sanitize_callback'     => 'esc_url_raw'
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'relia_slider_image[slide4]', array (
        'label' =>              __( 'Slide 4', 'relia' ),
        'section'               => 'relia_slider_images_section',
        'mime_type'             => 'image',
        'settings'              => 'relia_slider_image[slide4]',
        'description'           => __( 'Select the image file for Slide 4', 'relia' ),
    ) ) );
    
    // 5th slide
    $wp_customize->add_setting( 'relia_slider_image[slide5]', array (
        'default'               => get_template_directory_uri() . '/inc/images/bw-gear.jpg',
        'sanitize_callback'     => 'esc_url_raw'
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'relia_slider_image[slide5]', array (
        'label' =>              __( 'Slide 5', 'relia' ),
        'section'               => 'relia_slider_images_section',
        'mime_type'             => 'image',
        'settings'              => 'relia_slider_image[slide5]',
        'description'           => __( 'Select the image file for Slide 5', 'relia' ),
    ) ) );


// ---------------------------------------------
// relia_static_bg_section
// ---------------------------------------------

    // Use Image or Colour for Static Background? 
    $wp_customize->add_setting( 'relia_static_jumbotron_type', array (
        'default'               => 'image',
        'transport'             => 'refresh',
        'sanitize_callback'     => 'relia_sanitize_static_background_toggle',
    ) );
    $wp_customize->add_control( 'relia_static_jumbotron_type', array(
        'type'                  => 'radio',
        'section'               => 'relia_static_bg_section',
        'label'                 => __( 'Image or Color', 'relia' ),
        'description'           => __( 'Specify whether you would like the background of the static jumbotron to be solid colour or a single image', 'relia' ),
        'choices'               => array(
            'image'             => __( 'Use a background image', 'relia' ),
            'color'              => __( 'Use a solid color background', 'relia' ),
    ) ) );

    // Static Jumbotron Image
    $wp_customize->add_setting( 'relia_jumbotron_static_image', array (
            'default'               => get_template_directory_uri() . '/inc/images/bw-gear.jpg',
            'transport'             => 'refresh',
            'sanitize_callback'     => 'esc_url_raw',
        ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'relia_jumbotron_static_image', array (
        'mime_type'             => 'image',
        'settings'              => 'relia_jumbotron_static_image',
        'section'               => 'relia_static_bg_section',
        'label'                 => __( 'Static Background Image', 'relia' ),   
        'description'           => __( 'Select the image file that you would like to use as the homepage banner background', 'relia' ),
    ) ) );
    
    // Static Jumbotron Color
    $wp_customize->add_setting( 'relia_jumbotron_static_color', array (
        'default'               => '#1c1c1c',
        'transport'             => 'refresh',
        'sanitize_callback'     => 'relia_sanitize_text',
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'relia_jumbotron_static_color', array(
            'label'      => __( 'Static Background Color', 'relia' ),
            'section'    => 'relia_static_bg_section',
            'settings'   => 'relia_jumbotron_static_color',
    ) ) );
    
// ---------------------------------------------
// relia_slide_settings_section
// ---------------------------------------------
    
    // Display Jumbotron?
    $wp_customize->add_setting( 'relia_slider_bool', array (
        'default'               => 'show',
        'transport'             => 'refresh',
        'sanitize_callback'     => 'relia_sanitize_show_hide'
    ) );
    $wp_customize->add_control( 'relia_slider_bool', array(
        'label'   => __( 'Display Jumbotron on Frontpage', 'relia' ),
        'section' => 'relia_slide_settings_section',
        'type'    => 'radio',
        'choices'    => array(
            'show'              => __( 'Show', 'relia' ),
            'hide'              => __( 'Hide', 'relia' ),
        )
    ));
    
    // Slider or Static BG
    $wp_customize->add_setting( 'relia_jumbotron_type', array (
        'default'               => 'static',
        'transport'             => 'refresh',
        'sanitize_callback'     => 'relia_sanitize_jumbotron_type'
    ) );
    $wp_customize->add_control( 'relia_jumbotron_type', array(
        'label'   => __( 'Jumbotron Style', 'relia' ),
        'section' => 'relia_slide_settings_section',
        'type'    => 'radio',
        'choices'    => array(
            'slider'            => __( 'Slider', 'relia' ),
            'static'            => __( 'Static Image', 'relia' ),
        )
    ));
    
    // Jumbotron Height
    $wp_customize->add_setting( 'relia_slider_height', array (
        'default'               => 600,
        'transport'             => 'refresh',
        'sanitize_callback'     => 'relia_sanitize_integer',
    ) );
    $wp_customize->add_control( 'relia_slider_height', array(
        'type'                  => 'number',
        'section'               => 'relia_slide_settings_section',
        'label'                 => __( 'Jumbotron height', 'relia' ),
        'input_attrs'           => array(
            'min' => 1000,
            'max' => 300,
            'step' => 25,
    ) ) );
    
    // Slider Darkness Tint
    $wp_customize->add_setting( 'relia_slider_dark_tint', array (
        'default'               => .30,
        'transport'             => 'refresh',
        'sanitize_callback'     => 'relia_sanitize_decimal',
    ) );
    $wp_customize->add_control( 'relia_slider_dark_tint', array(
        'type'                  => 'number',
        'section'               => 'relia_slide_settings_section',
        'label'                 => __( 'Darkness Tint', 'relia' ),
        'description'           => __( 'Adjust the amount of dark tint to apply to slider images, from 0.0 for no tint to 1.0 for solid black, or anything in between', 'relia' ),
        'input_attrs'           => array(
            'min' => .0,
            'max' => 1.0,
            'step' => .05,
    ) ) );
    
    // Slide Delay
    $wp_customize->add_setting( 'relia_slide_timer', array (
        'default'               => '4000',
        'transport'             => 'refresh',
        'sanitize_callback'     => 'relia_sanitize_text'
    ) );
    $wp_customize->add_control( 'relia_slide_timer', array(
        'label'   => __( 'Slide Delay', 'relia' ),
        'section' => 'relia_slide_settings_section',
        'type'    => 'select',
        'choices'    => array(
            '2000'              => __( '2 Seconds', 'relia' ),
            '3000'              => __( '3 Seconds', 'relia' ),
            '4000'              => __( '4 Seconds', 'relia' ),
            '5000'              => __( '5 Seconds', 'relia' ),
            '6000'              => __( '6 Seconds', 'relia' ),
        )
    ));
    
    // Slide Transition
    $wp_customize->add_setting( 'relia_slide_transition', array (
        'default'               => 'scrollHorz',
        'transport'             => 'refresh',
        'sanitize_callback'     => 'relia_sanitize_text'
    ) );
    $wp_customize->add_control( 'relia_slide_transition', array(
        'label'   => __( 'Slide Transition Effect', 'relia' ),
        'section' => 'relia_slide_settings_section',
        'type'    => 'select',
        'choices'    => array(
            'simpleFade'        => __( 'Fade', 'relia' ),
            'scrollTop'         => __( 'Scroll Top', 'relia' ),
            'scrollHorz'        => __( 'Horizontal Scroll', 'relia' ),
            'mosaicSpiral'      => __( 'Mosaic Spiral', 'relia' ),
            'mosaicRandom'      => __( 'Mosaic Random', 'relia' ),
            'mosaicReverse'     => __( 'Mosaic Reverse', 'relia' ),
            'stampede'          => __( 'Stampede', 'relia' ),
            'random'            => __( 'Random', 'relia' ),
        )
    ));
    
    // Slide Loader
    $wp_customize->add_setting( 'relia_slide_loader', array (
        'default'               => 'bar',
        'transport'             => 'refresh',
        'sanitize_callback'     => 'relia_sanitize_text'
    ) );
    $wp_customize->add_control( 'relia_slide_loader', array(
        'label'   => __( 'Slide Loader Style', 'relia' ),
        'section' => 'relia_slide_settings_section',
        'type'    => 'select',
        'choices'    => array(
            'pie'               => __( 'Pie', 'relia' ),
            'bar'               => __( 'Bar', 'relia' ),
            'none'              => __( 'None', 'relia' ),
        )
    ));

    // Slider Next / Previous
    $wp_customize->add_setting( 'relia_slide_pagination', array (
        'default'               => 'false',
        'transport'             => 'refresh',
        'sanitize_callback'     => 'relia_sanitize_text'
    ) );
    $wp_customize->add_control( 'relia_slide_pagination', array(
        'label'   => __( 'Slider Next/Previous Buttons', 'relia' ),
        'section' => 'relia_slide_settings_section',
        'type'    => 'radio',
        'choices'    => array(
            'true'              => __( 'Show', 'relia' ),
            'false'             => __( 'Hide', 'relia' ),
        )
    ));
    
    // Jumbotron Heading Text
    $wp_customize->add_setting( 'relia_jumbotron_heading', array (
        'default'               => __( 'Featured Product', 'relia' ),
        'transport'             => 'refresh',
        'sanitize_callback'     => 'relia_sanitize_text',
    ) );
    $wp_customize->add_control( 'relia_jumbotron_heading', array(
        'type'                  => 'text',
        'section'               => 'relia_slide_settings_section',
        'label'                 => __( 'Main Jumbotron Heading', 'relia' ),
    ) );
    
    // Jumbotron Heading Font Size
    $wp_customize->add_setting( 'relia_jumbotron_heading_size', array (
        'default'               => 50,
        'transport'             => 'refresh',
        'sanitize_callback'     => 'relia_sanitize_text',
    ) );
    $wp_customize->add_control( 'relia_jumbotron_heading_size', array(
        'type'                  => 'number',
        'section'               => 'relia_slide_settings_section',
        'label'                 => __( 'Jumbotron Heading Font Size', 'relia' ),
        'description'           => __( 'Adjust the font size of the jumbotron heading in pixels', 'relia' ),
        'input_attrs'           => array(
            'min' => 18,
            'max' => 72,
            'step' => 2,
    ) ) );
    
    // Jumbotron Button 1 - Text 
    $wp_customize->add_setting( 'relia_jumbotron_button_1_text', array (
        'default'               => __( 'View Collection', 'relia' ),
        'transport'             => 'refresh',
        'sanitize_callback'     => 'relia_sanitize_text',
    ) );
    $wp_customize->add_control( 'relia_jumbotron_button_1_text', array(
        'type'                  => 'text',
        'section'               => 'relia_slide_settings_section',
        'label'                 => __( 'Button 1 - Text', 'relia' ),
    ) );

    // Jumbotron Button 1 - Internal Link
    $wp_customize->add_setting( 'relia_jumbotron_button_1_internal', array (
        'default'               => null,
        'transport'             => 'refresh',
        'sanitize_callback'     => 'relia_sanitize_post',
    ) );
    $wp_customize->add_control( 'relia_jumbotron_button_1_internal', array(
        'type'                  => 'select',
        'section'               => 'relia_slide_settings_section',
        'label'                 => __( 'Button 1 - Link to Post / Page', 'relia' ),
        'choices'               => relia_all_posts_array(),
    ) );
    
    // Jumbotron Button 1 - External URL
    $wp_customize->add_setting( 'relia_jumbotron_button_1_url', array (
        'default'               => null,
        'transport'             => 'refresh',
        'sanitize_callback'     => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'relia_jumbotron_button_1_url', array(
        'type'                  => 'url',
        'section'               => 'relia_slide_settings_section',
        'label'                 => __( 'Button 1 - External URL', 'relia' ),
        'description'           => __( 'When not blank, forces Button 1 to link to an external URL instead of a specified post/page', 'relia' ),
    ) );

    // Jumbotron Button 2 - Text 
    $wp_customize->add_setting( 'relia_jumbotron_button_2_text', array (
        'default'               => __( 'Back Us On Kickstarter', 'relia' ),
        'transport'             => 'refresh',
        'sanitize_callback'     => 'relia_sanitize_text',
    ) );
    $wp_customize->add_control( 'relia_jumbotron_button_2_text', array(
        'type'                  => 'text',
        'section'               => 'relia_slide_settings_section',
        'label'                 => __( 'Button 2 - Text', 'relia' ),
    ) );

    // Jumbotron Button 2 - Internal Link
    $wp_customize->add_setting( 'relia_jumbotron_button_2_internal', array (
        'default'               => null,
        'transport'             => 'refresh',
        'sanitize_callback'     => 'relia_sanitize_post',
    ) );
    $wp_customize->add_control( 'relia_jumbotron_button_2_internal', array(
        'type'                  => 'select',
        'section'               => 'relia_slide_settings_section',
        'label'                 => __( 'Button 2 - Link to Post / Page', 'relia' ),
        'choices'               => relia_all_posts_array(),
    ) );
    
    // Jumbotron Button 2 - External URL
    $wp_customize->add_setting( 'relia_jumbotron_button_2_url', array (
        'default'               => null,
        'transport'             => 'refresh',
        'sanitize_callback'     => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'relia_jumbotron_button_2_url', array(
        'type'                  => 'url',
        'section'               => 'relia_slide_settings_section',
        'label'                 => __( 'Button 2 - External URL', 'relia' ),
        'description'           => __( 'When not blank, forces Button 1 to link to an external URL instead of a specified post/page', 'relia' ),
    ) );
    
    // Jumbotron Button Font Size
    $wp_customize->add_setting( 'relia_jumbotron_button_size', array (
        'default'               => 14,
        'transport'             => 'refresh',
        'sanitize_callback'     => 'relia_sanitize_integer',
    ) );
    $wp_customize->add_control( 'relia_jumbotron_button_size', array(
        'type'                  => 'number',
        'section'               => 'relia_slide_settings_section',
        'label'                 => __( 'Button Font Size', 'relia' ),
        'input_attrs'           => array(
            'min' => 8,
            'max' => 40,
            'step' => 2,
    ) ) );