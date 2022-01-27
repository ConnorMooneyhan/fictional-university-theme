<?php 
    get_header();

    while(have_posts()) {
        the_post();
        page_banner();
        ?>

        <div class="container container--narrow page-section">
            <div class="metabox metabox--position-up metabox--with-home-link">
                <p>
                <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('program') ?>"><i class="fa fa-home" aria-hidden="true"></i> Programs Home</a> <span class="metabox__main"><?php the_title() ?></span>
                </p>
            </div>

            <div class="generic-content"><?php the_content() ?></div>

            <?php

            // Output related professors
            $related_professors = new WP_Query(array(
              'posts_per_page' => -1,
              'post_type' => 'professor',
              'orderby' => 'title',
              'order' => 'ASC',
              'meta_query' => array(
                array(
                  'key' => 'related_programs',
                  'compare' => 'LIKE',
                  'value' => '"' . get_the_ID() . '"',
                ),
              ),
            ));

            if ($related_professors->have_posts()) {
              echo '<hr class="section-break">';
              echo '<h2 class="headline headline--medium">' . get_the_title() . ' Professors</h2>';
              
              echo '<ul class=professor-cards">';
              while ($related_professors->have_posts()) {
                $related_professors->the_post(); ?>
                <li class="professor-card__list-item">
                  <a class="professor-card" href="<?php the_permalink() ?>">
                    <img src="<?php the_post_thumbnail_url('professor_landscape') ?>" class="professor-card__image">
                    <span class="professor-card__name"><?php the_title() ?></span>
                  </a>
                </li>

                <?php
              }
              echo '</ul>';
            }

            // Resets global post object that determines what, for instance, 'get_the_ID' outputs
            wp_reset_postdata();

            // Output related events
            $today = date('Ymd');
            $homepage_events = new WP_Query(array(
              'posts_per_page' => 2,
              'post_type' => 'event',
              'meta_key' => 'event_date',
              'orderby' => 'meta_value_num',
              'order' => 'ASC',
              'meta_query' => array(
                array(
                  'key' => 'event_date',
                  'compare' => '>=',
                  'value' => $today,
                ),
                array(
                  'key' => 'related_programs',
                  'compare' => 'LIKE',
                  'value' => '"' . get_the_ID() . '"',
                ),
              ),
            ));

            if ($homepage_events->have_posts()) {
              echo '<hr class="section-break">';
              echo '<h2 class="headline headline--medium">Upcoming ' . get_the_title() . ' Events</h2>';
              
              while ($homepage_events->have_posts()) {
                $homepage_events->the_post();
                get_template_part('template-parts/content', 'event');
              }
            }
          ?>
            
        </div>
    <?php }

    get_footer();
?>