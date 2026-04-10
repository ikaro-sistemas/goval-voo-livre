<?php
/* Template Name: Os Campeões */
get_header();
?>
<main id="goval-campeoes-content" style="min-height: 80vh;">
    <?php
    if (have_posts()) {
        while (have_posts()) {
            the_post();
            // A Função the_content garante que qualquer bloco jogado pelo Elementor seja renderizado!
            the_content();
        }
    }
    ?>
</main>
<?php get_footer(); ?>
