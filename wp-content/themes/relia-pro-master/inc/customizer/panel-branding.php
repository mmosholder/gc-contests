<?php

// ---------------------------------------------
// Theme Branding Panel
// ---------------------------------------------

$wp_customize->add_panel( 'relia_branding_panel', array (
    'title'                 => __( 'Theme Branding', 'relia' ),
    'description'           => __( 'Customize the theme branding', 'relia' ),
    'priority'              => 10
) );

// ---------------------------------------------
// Smartcat Branding Section
// ---------------------------------------------

$wp_customize->add_section( 'relia_smartcat_branding_section', array (
    'title'                 => __( 'Smartcat Branding', 'relia' ),
    'description'           => __( 'Use the settings below to set the visibility of the Smartcat branding', 'relia' ),
    'panel'                 => 'relia_branding_panel',
) );

// ---------------------------------------------
// relia_smartcat_branding_section
// ---------------------------------------------

    $wp_customize->add_setting( 'relia_smarcat_branding', array (
        'default'               => 'show',
        'transport'             => 'refresh',
        'sanitize_callback'     => 'relia_sanitize_show_hide'
    ) );
    $wp_customize->add_control( 'relia_smarcat_branding', array(
        'label'   => __( '"Designed by Smartcat"', 'relia' ),
        'section' => 'relia_smartcat_branding_section',
        'type'    => 'radio',
        'choices'    => array(
            'show'              => __( 'Show', 'relia' ),
            'hide'              => __( 'Hide', 'relia' ),
        )
    ));