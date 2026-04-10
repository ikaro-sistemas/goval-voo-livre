<?php
/* Template Name: Front Page Customizada */
get_header(); ?>

<div class="news-section globo-style" style="max-width: 1300px; margin: 40px auto; padding: 0 20px; display: grid; grid-template-columns: 2fr 1fr; gap: 40px;">
    
    <!-- Destaque Principal -->
    <div class="main-news">
        <?php
        $destaque = new WP_Query(array('post_type' => 'post', 'posts_per_page' => 1));
        if ($destaque->have_posts()) : while ($destaque->have_posts()) : $destaque->the_post();
            $capa = get_post_meta(get_the_ID(), 'foto_capa', true) ?: 'https://upload.wikimedia.org/wikipedia/commons/e/e0/Pico_da_Ibituruna.jpg';
        ?>
        <div style="position: relative; overflow: hidden; border-radius: 8px; box-shadow: 0 6px 20px rgba(0,0,0,0.15);">
            <div style="background: url('<?php echo esc_attr($capa); ?>') center/cover; height: 550px; width: 100%; transition: transform 0.5s ease;"></div>
            <div style="position: absolute; bottom: 0; left: 0; width: 100%; padding: 40px; background: linear-gradient(transparent, rgba(0,0,0,0.95)); color: white;">
                <span style="background: #e90000; color: white; padding: 6px 12px; font-weight: bold; border-radius: 4px; font-size: 0.9em; text-transform: uppercase;">Destaque Globo</span>
                <h1 style="margin: 20px 0 10px 0; font-size: 3.2em; line-height: 1.1; text-shadow: 2px 2px 5px rgba(0,0,0,0.9);"><a href="<?php the_permalink(); ?>" style="color: white; text-decoration: none;"><?php the_title(); ?></a></h1>
                <p style="font-size: 1.25em; opacity: 0.95; margin: 0; text-shadow: 1px 1px 3px rgba(0,0,0,0.8);"><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>
            </div>
        </div>
        <?php endwhile; endif; wp_reset_postdata(); ?>

        <!-- Sub Destaques -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 30px;">
            <?php
            $sub = new WP_Query(array('post_type' => 'post', 'posts_per_page' => 4, 'offset' => 1));
            if ($sub->have_posts()) : while ($sub->have_posts()) : $sub->the_post();
                $capa = get_post_meta(get_the_ID(), 'foto_capa', true) ?: 'https://images.unsplash.com/photo-1549646549-3837ad06a090?w=600&q=80';
            ?>
            <div style="border-radius: 8px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                <div style="background: url('<?php echo esc_attr($capa); ?>') center/cover; height: 200px; width: 100%;"></div>
                <div style="padding: 15px; background: white;">
                    <h3 style="margin: 0; font-size: 1.2em; line-height: 1.3;"><a href="<?php the_permalink(); ?>" style="color: var(--vale-dark); text-decoration: none;"><?php the_title(); ?></a></h3>
                </div>
            </div>
            <?php endwhile; endif; wp_reset_postdata(); ?>
        </div>
    </div>

    <!-- Feed Lateral -->
    <div class="side-news" style="display: flex; flex-direction: column; gap: 25px;">
        <h2 style="margin-top: 0; border-bottom: 3px solid var(--vale-gold); padding-bottom: 10px; color: var(--vale-green);">Voo Livre em Tempo Real</h2>
        <?php
        $feed = new WP_Query(array('post_type' => 'post', 'posts_per_page' => 5, 'offset' => 5));
        if ($feed->have_posts()) : while ($feed->have_posts()) : $feed->the_post();
            $capa = get_post_meta(get_the_ID(), 'foto_capa', true) ?: 'https://images.unsplash.com/photo-1518331165337-25e227568541?w=400&q=80';
            $cat = get_post_meta(get_the_ID(), 'categoria_fake', true) ?: 'Esporte';
        ?>
        <div style="display: flex; gap: 20px; border-bottom: 1px solid #ddd; padding-bottom: 20px;">
            <div style="background: url('<?php echo esc_attr($capa); ?>') center/cover; min-width: 140px; height: 100px; border-radius: 6px;"></div>
            <div>
                <span style="color: #e90000; font-weight: bold; font-size: 0.85em; text-transform: uppercase;"><?php echo esc_html($cat); ?></span>
                <h3 style="margin: 8px 0; font-size: 1.2em; line-height: 1.3;"><a href="<?php the_permalink(); ?>" style="color: var(--vale-dark); text-decoration: none; transition: color 0.2s;"><?php the_title(); ?></a></h3>
            </div>
        </div>
        <?php endwhile; endif; wp_reset_postdata(); ?>
        
        <div style="background: url('https://upload.wikimedia.org/wikipedia/commons/e/e0/Pico_da_Ibituruna.jpg') center/cover; position: relative; border-radius: 8px; padding: 40px 25px; text-align: center; color: white; margin-top: 20px; box-shadow: 0 4px 10px rgba(0,0,0,0.2);">
            <div style="position: absolute; top:0;left:0;right:0;bottom:0; background: rgba(0,122,83, 0.8); border-radius: 8px; z-index: 0;"></div>
            <div style="position: relative; z-index: 1;">
                <h3 style="margin: 0 0 15px 0; color: var(--vale-gold); font-size: 1.8em;">Gmaps 3D</h3>
                <p style="font-size: 1em; margin-bottom: 20px;">Compare as rotas usando a API do Google</p>
                <a href="<?php echo site_url('/comparativo-3d'); ?>" style="display: block; padding: 15px; background: white; color: var(--vale-green); text-decoration: none; font-weight: bold; font-size: 1.1em; border-radius: 4px; border: 2px solid var(--vale-gold);">Entrar no Mapa 3D</a>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>
