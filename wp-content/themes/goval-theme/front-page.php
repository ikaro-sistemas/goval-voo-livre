<?php
get_header();
?>
<main id="goval-front-page-content" style="min-height: 80vh;">
    <?php
    // Se a página já foi construída com Elementor ou Gutenberg (Possui conteúdo)
    if (have_posts() && trim(get_the_content()) !== '') {
        while (have_posts()) {
            the_post();
            the_content();
        }
    } else {
        // Se a página está virgem, injetamos magicamente o código de Notícias (Nosso Shortcode Base)
        echo do_shortcode('[goval_news_grid]');
    }
    ?>
</main>
<?php get_footer(); ?>
