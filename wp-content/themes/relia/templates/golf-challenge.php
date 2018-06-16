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
                                <!-- <p>If you have entered this contest but cannot see the proper contest info, please perform a few hard refreshes in your browser (usually command/control + shift + r or can be controlled in the browser options or history), or open this page in a private browser tab. Sorry for the inconvenience as we work out these kinks!</p> -->

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
