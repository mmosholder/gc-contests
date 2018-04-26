<?php
/*
Template Name: Left Sidebar
*/
get_header(); ?>

<div id="primary" class="content-area">
    
        <main id="main" class="site-main" role="main">
            
            <div class="container">
        
                <div class="row">

                    <?php if (is_active_sidebar( 'sidebar-left' ) ) : ?>
                 
                            <?php get_sidebar( 'left' ); ?>
              
                    <?php endif; ?>
                    
                    <div class="col-sm-<?php echo relia_main_width( 'left' ); ?>">
                    
                        <div class="row">
                            
                            <?php if( have_posts() ) : ?>

                                <?php while ( have_posts() ) : the_post(); ?>

                                    <div class="col-sm-12">

                                        <h2 class="page-title">
                                            <?php the_title(); ?>
                                        </h2>

                                        <hr>

                                        <?php the_content(); ?>

                                    </div>

                                <?php endwhile; ?>

                            <?php endif; ?>
                            
                        </div>
                        
                    </div>
                    
                </div>
            
            </div>

        </main><!-- #main -->
        
    </div><!-- #primary -->

<?php get_footer(); ?>