<?php
/**
 * Shortcode: Grid de Notícias
 * Exibe as notícias em um layout de portal premium.
 */

add_shortcode('goval_news_grid', 'goval_news_grid_shortcode');

function goval_news_grid_shortcode($atts) {
    ob_start();
    ?>
    <div class="goval-news-wrapper">
        <!-- Coluna Esquerda: Notícias em Destaque -->
        <div class="main-news">
            <?php
            // Busca a notícia mais recente para o Destaque Principal
            $destaque = new WP_Query(['post_type' => 'post', 'posts_per_page' => 1]);
            if ($destaque->have_posts()) : while ($destaque->have_posts()) : $destaque->the_post();
                $capa = get_post_meta(get_the_ID(), 'foto_capa', true) ?: 'https://upload.wikimedia.org/wikipedia/commons/e/e0/Pico_da_Ibituruna.jpg';
            ?>
            <div class="goval-main-card" onclick="window.location.href='<?php the_permalink(); ?>'">
                <div class="goval-main-bg" style="background-image: url('<?php echo esc_url($capa); ?>');"></div>
                <div class="goval-overlay">
                    <span class="goval-badge">Destaque Oficial</span>
                    <h1 style="margin: 20px 0 10px 0; font-size: 3.2em; line-height: 1.1; text-shadow: 2px 2px 5px rgba(0,0,0,0.9);">
                        <?php the_title(); ?>
                    </h1>
                    <p style="font-size: 1.25em; opacity: 0.95; margin: 0; text-shadow: 1px 1px 3px rgba(0,0,0,0.8);"><?php echo wp_trim_words(get_the_excerpt(), 25); ?></p>
                </div>
            </div>
            <?php endwhile; endif; wp_reset_postdata(); ?>

            <!-- Sub Destaques -->
            <div class="goval-subgrid">
                <?php
                // Busca as 4 notícias seguintes
                $sub = new WP_Query(['post_type' => 'post', 'posts_per_page' => 4, 'offset' => 1]);
                if ($sub->have_posts()) : while ($sub->have_posts()) : $sub->the_post();
                    $capa = get_post_meta(get_the_ID(), 'foto_capa', true) ?: 'https://images.unsplash.com/photo-1549646549-3837ad06a090?w=600&q=80';
                ?>
                <div class="goval-card-anim" onclick="window.location.href='<?php the_permalink(); ?>'">
                    <div style="overflow:hidden;"><div class="bg" style="background: url('<?php echo esc_url($capa); ?>') center/cover; height: 180px; width: 100%;"></div></div>
                    <div style="padding: 20px;">
                        <h3 style="margin: 0; font-size: 1.2em; line-height: 1.35; color: #222;"><?php the_title(); ?></h3>
                        <p style="color:#666; font-size:0.95em; margin-top:10px;"><?php echo wp_trim_words(get_the_excerpt(), 14); ?></p>
                    </div>
                </div>
                <?php endwhile; endif; wp_reset_postdata(); ?>
            </div>
        </div>

        <!-- Coluna Direita: Feed Recentes -->
        <div class="side-news" style="display: flex; flex-direction: column; gap: 25px;">
            <h2 style="margin-top: 0; border-bottom: 3px solid #FFB81C; padding-bottom: 10px; color: #007A53;">Últimas Atualizações</h2>
            <?php
            // Busca o restante das notícias para o feed lateral
            $feed = new WP_Query(['post_type' => 'post', 'posts_per_page' => 5, 'offset' => 5]);
            if ($feed->have_posts()) : while ($feed->have_posts()) : $feed->the_post();
                $capa = get_post_meta(get_the_ID(), 'foto_capa', true) ?: 'https://images.unsplash.com/photo-1518331165337-25e227568541?w=400&q=80';
                $cat = get_post_meta(get_the_ID(), 'categoria_fake', true) ?: 'Aeronáutica';
            ?>
            <div class="goval-side-item" onclick="window.location.href='<?php the_permalink(); ?>'">
                <div style="overflow:hidden; border-radius:6px; min-width: 120px; height: 90px;"><div style="background: url('<?php echo esc_url($capa); ?>') center/cover; width: 100%; height: 100%; transition: transform 0.4s ease;" onmouseover="this.style.transform='scale(1.15)'" onmouseout="this.style.transform='scale(1)'"></div></div>
                <div>
                    <span style="color: #007A53; font-weight: 800; font-size: 0.8em; text-transform: uppercase; letter-spacing: 0.5px;"><?php echo esc_html($cat); ?></span>
                    <h3 style="margin: 8px 0; font-size: 1.05em; line-height: 1.35; color: #222;"><?php the_title(); ?></h3>
                </div>
            </div>
            <?php endwhile; endif; wp_reset_postdata(); ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
