<?php
/* Template Name: Mapa 3D */
get_header();
?>
<main id="goval-mapa-content" style="min-height: 80vh;">
    <?php
    if (have_posts()) {
        while (have_posts()) {
            the_post();
            // Renderiza os conteúdos puros criados no WP/Elementor (Que agora armazenam os shortcodes)
            the_content();
        }
    }
    ?>
</main>
<?php get_footer(); ?>
