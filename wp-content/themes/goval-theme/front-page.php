<?php
get_header();
?>
<main id="goval-front-page-content" style="min-height: 80vh; background: #fdfdfd;">
    <?php
    echo do_shortcode('[goval_news_grid]');
    ?>
</main>
<?php get_footer(); ?>
