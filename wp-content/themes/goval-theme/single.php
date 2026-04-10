<?php
/**
 * single.php - Template para visualização de notícia completa
 */
get_header();
?>

<style>
    .goval-article-header {
        height: 60vh;
        min-height: 400px;
        width: 100%;
        background-size: cover;
        background-position: center;
        position: relative;
        display: flex;
        align-items: flex-end;
        color: white;
    }
    .goval-article-header::after {
        content: '';
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        background: linear-gradient(to bottom, transparent 40%, rgba(0,0,0,0.85));
    }
    .goval-article-title-container {
        position: relative;
        z-index: 2;
        max-width: 900px;
        margin: 0 auto;
        padding: 40px 20px;
        width: 100%;
    }
    .goval-article-content {
        max-width: 800px;
        margin: 50px auto;
        padding: 0 20px;
        line-height: 1.8;
        font-size: 1.2em;
        color: #333;
        font-family: 'Inter', sans-serif;
    }
    .goval-article-content h2, .goval-article-content h3 {
        color: #007A53;
        margin-top: 40px;
    }
    .goval-article-content p {
        margin-bottom: 25px;
        text-align: justify;
    }
    .goval-back-btn {
        display: inline-block;
        margin-bottom: 20px;
        color: #FFB81C;
        text-decoration: none;
        font-weight: bold;
        text-transform: uppercase;
        font-size: 0.9em;
    }
    .goval-back-btn:hover {
        text-decoration: underline;
    }
</style>

<?php if (have_posts()) : while (have_posts()) : the_post(); 
    $capa = get_post_meta(get_the_ID(), 'foto_capa', true) ?: 'https://images.unsplash.com/photo-1518331165337-25e227568541?w=1200&q=80';
    $categoria = get_post_meta(get_the_ID(), 'categoria_fake', true) ?: 'Notícias';
?>

<div class="goval-article-header" style="background-image: url('<?php echo esc_url($capa); ?>');">
    <div class="goval-article-title-container">
        <a href="<?php echo home_url(); ?>" class="goval-back-btn">← Voltar para o Portal</a>
        <span style="display: block; background: #FFB81C; color: #222; padding: 5px 12px; border-radius: 4px; font-weight: bold; font-size: 0.8em; width: fit-content; margin-bottom: 15px; text-transform: uppercase;">
            <?php echo esc_html($categoria); ?>
        </span>
        <h1 style="font-size: 3.5em; line-height: 1.1; margin: 0; text-shadow: 2px 2px 10px rgba(0,0,0,0.5);"><?php the_title(); ?></h1>
        <div style="margin-top: 15px; opacity: 0.9; font-size: 1.1em; font-style: italic;">
            Publicado em <?php echo get_the_date(); ?> • Por Equipe Mundial Ibituruna
        </div>
    </div>
</div>

<article class="goval-article-content">
    <?php the_content(); ?>
</article>

<?php endwhile; endif; ?>

<?php get_footer(); ?>
