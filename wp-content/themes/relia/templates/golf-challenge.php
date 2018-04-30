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
                                    if (is_user_logged_in()) {
                                        // are there any rows within within our flexible content?                                                                          
                                        get_template_part('template-parts/golf-test'); 
                                    } else {
                                        echo "<p>Please log in to use this page</p>";
                                    }                                                                           
                                  ?>

                            </div>
                            
                        </div>
                        
                    </div>
                    
                </div>
            
            </div>

        </main><!-- #main -->
        
    </div><!-- #primary -->

<?php get_footer(); ?>