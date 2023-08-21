<?php get_header(); ?>

<div class="page-content">
    <?php
    while (have_posts()) : the_post();
        the_title('<h1 class="content-title">', '</h1>');
        the_content();
    endwhile;
    ?>
</div>

<?php get_footer(); ?>