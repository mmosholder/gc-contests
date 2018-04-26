<?php
/*
Template Name: Golf Challege
*/
get_header(); ?>

<div id="primary" class="content-area">
    
        <main id="main" class="site-main" role="main">
            
            <div class="container">
        
                <div class="row">

                    <div class="col-sm-12">
                    
                        <div class="row">

                            <div class="col-sm-12">

                                <h2 class="page-title">
                                    <?php the_title(); ?>
                                </h2>

                                <hr>

                                <?php
                                    // are there any rows within within our flexible content?                                                                          
                                    get_template_part('template-parts/golf-test'); 
                                    
                                  ?>

                            </div>
                            
                        </div>
                        
                    </div>
                    
                </div>
            
            </div>

        </main><!-- #main -->
        
    </div><!-- #primary -->

<?php get_footer(); ?>