<?php
/**
 * The front page widget area "B".
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package relia
 */
if ( ! is_active_sidebar( 'sidebar-front-b' ) ) { return 0; } ?>

    <section class="main-page-content front-page-widget area-b">

        <div class="container">

            <div class="row">
                    
                    <?php dynamic_sidebar( 'sidebar-front-b' ); ?>
                    
            </div>

        </div>

    </section>
    


