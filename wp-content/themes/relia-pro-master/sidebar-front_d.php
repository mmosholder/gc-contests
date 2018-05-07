<?php
/**
 * The front page widget area "C".
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package relia
 */
if ( ! is_active_sidebar( 'sidebar-front-d' ) ) { return 0; } ?>

    <section class="main-page-content front-page-widget area-d">

        <div class="container">

            <div class="row">
                    
                    <?php dynamic_sidebar( 'sidebar-front-d' ); ?>
                    
            </div>

        </div>

    </section>
