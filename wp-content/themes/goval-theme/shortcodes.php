<?php
/**
 * ============================================================================
 * MÓDULO DE SHORTCODES - PORTAL MUNDIAL DE VOO LIVRE (GOVAL)
 * ============================================================================
 * Este arquivo refatora as interfaces estáticas complexas desenvolvidas no MVP
 * e as empacota como "Shortcodes" do WordPress.
 * 
 * OBJETIVO: Permitir que administradores utilizem o Elementor (ou Gutenberg)
 * para arrastar, soltar e envelopar as funcionalidades de 3D, Abas e Notícias
 * sem perder a liberdade de editar textos ao redor.
 * 
 * CÓDIGO LIMPO: As variáveis estão documentadas e responsabilidades separadas.
 */

// ============================================================================
// 1. SHORTCODE: GRID DE NOTÍCIAS (Estilo Portal)
// Uso no Elementor: [goval_news_grid]
// ============================================================================
add_shortcode('goval_news_grid', 'goval_news_grid_shortcode');
function goval_news_grid_shortcode($atts) {
    ob_start(); // Inicia o buffer de saída para evitar quebra de layout
    ?>
    <style>
        /* Estilos Premium para o Portal de Notícias */
        .goval-news-wrapper { max-width: 1300px; margin: 40px auto; padding: 0 20px; display: grid; grid-template-columns: 2.2fr 1fr; gap: 50px; font-family: 'Inter', sans-serif; }
        .goval-main-card { position: relative; overflow: hidden; border-radius: 12px; box-shadow: 0 8px 25px rgba(0,0,0,0.2); transition: box-shadow 0.4s ease; cursor: pointer; }
        .goval-main-card:hover { box-shadow: 0 15px 40px rgba(0,0,0,0.35); }
        .goval-main-bg { height: 580px; width: 100%; transition: transform 0.7s cubic-bezier(0.25, 0.46, 0.45, 0.94); background-position: center; background-size: cover; }
        .goval-main-card:hover .goval-main-bg { transform: scale(1.06); }
        .goval-overlay { position: absolute; bottom: 0; left: 0; width: 100%; padding: 40px 40px 30px 40px; background: linear-gradient(transparent, rgba(0,0,0,0.95)); color: white; transition: padding-bottom 0.4s ease; }
        .goval-main-card:hover .goval-overlay { padding-bottom: 50px; }
        .goval-badge { background: #FFB81C; color: #222; padding: 8px 16px; font-weight: 800; border-radius: 4px; font-size: 0.85em; text-transform: uppercase; box-shadow: 0 4px 10px rgba(0,0,0,0.2); display: inline-block; }
        .goval-subgrid { display: grid; grid-template-columns: 1fr 1fr; gap: 25px; margin-top: 30px; }
        .goval-card-anim { border-radius: 10px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.06); background: white; transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275), box-shadow 0.3s ease; cursor: pointer; border: 1px solid #f1f1f1; }
        .goval-card-anim:hover { transform: translateY(-8px); box-shadow: 0 15px 35px rgba(0,0,0,0.12); }
        .goval-card-anim img, .goval-card-anim div.bg { transition: transform 0.6s ease; }
        .goval-card-anim:hover div.bg { transform: scale(1.08); }
        .goval-side-item { display: flex; gap: 20px; border-bottom: 1px solid #eee; padding: 15px 10px; transition: all 0.3s ease; border-radius: 8px; cursor: pointer; }
        .goval-side-item:hover { background: #fdfdfd; transform: translateX(8px); box-shadow: -4px 4px 15px rgba(0,0,0,0.05); border-left: 4px solid #007A53; padding-left: 15px; }

        @media (max-width: 900px) {
            .goval-news-wrapper { grid-template-columns: 1fr; }
            .goval-main-bg { height: 400px; }
            .goval-subgrid { grid-template-columns: 1fr; }
        }
    </style>

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
    return ob_get_clean(); // Retorna o componente renderizado para o Elementor
}

// ============================================================================
// 2. SHORTCODE: ABAS E TABELAS DE CAMPEÕES (Multi-Ranking Edition)
// Uso no Elementor: [goval_champions_tabs]
// ============================================================================
add_shortcode('goval_champions_tabs', 'goval_champions_tabs_shortcode');
function goval_champions_tabs_shortcode($atts) {
    ob_start();

    // Query robusta para resgatar todos os campeões cadastrados!
    $campeoes_query = new WP_Query(['post_type' => 'campeao', 'posts_per_page' => -1]);
    $campeoes = [];
    if ($campeoes_query->have_posts()) {
        while ($campeoes_query->have_posts()) {
            $campeoes_query->the_post();
            $campeoes[] = [
                'title' => get_the_title(),
                'bio' => get_the_excerpt(),
                'nacionalidade' => get_post_meta(get_the_ID(), 'nacionalidade', true) ?: 'Desconhecido',
                'equipamento' => get_post_meta(get_the_ID(), 'equipamento', true) ?: 'N/A',
                'ano' => get_post_meta(get_the_ID(), 'ano_campeonato', true) ?: date('Y'),
                'pos' => (int) (get_post_meta(get_the_ID(), 'posicao', true) ?: 1),
                'type' => get_post_meta(get_the_ID(), 'tipo_torneio', true) ?: 'Mundial',
                'etapas' => get_post_meta(get_the_ID(), 'detalhes_etapas', true),
                'conquistas' => get_post_meta(get_the_ID(), 'detalhes_conquistas', true),
                'voo_log' => get_post_meta(get_the_ID(), 'detalhes_voo_log', true),
                'equip_completo' => get_post_meta(get_the_ID(), 'equipamento_completo', true)
            ];
        }
    }
    wp_reset_postdata();

    // --- LÓGICAS DE AGREGAÇÃO HISTÓRICA ---
    $atual_mundial = null;
    $atual_gv = null;
    $por_ano = [];
    $tit_mundiais = [];
    $tit_gv = [];
    $tit_br = [];

    foreach ($campeoes as $c) {
        $ano = $c['ano']; $nome = $c['title']; $pos = $c['pos']; $tipo = $c['type'];
        
        // Separa Destaques Atuais 2026 (Para a aba principal)
        if ($ano == '2026' && $pos == 1) {
            if ($tipo == 'Mundial' && !$atual_mundial) $atual_mundial = $c;
            if ($tipo == 'GV' && !$atual_gv) $atual_gv = $c;
        }

        // Soma os Troféus/Títulos e Arquiva os Anos (Somente Ouros / 1º Lugar)
        if ($pos == 1) {
            if ($tipo == 'Mundial') {
                if (!isset($tit_mundiais[$nome])) $tit_mundiais[$nome] = ['nacao' => $c['nacionalidade'], 'qtd' => 0, 'anos' => []];
                $tit_mundiais[$nome]['qtd']++;
                $tit_mundiais[$nome]['anos'][] = $ano;
            } elseif ($tipo == 'GV') {
                if (!isset($tit_gv[$nome])) $tit_gv[$nome] = ['nacao' => $c['nacionalidade'], 'qtd' => 0, 'anos' => []];
                $tit_gv[$nome]['qtd']++;
                $tit_gv[$nome]['anos'][] = $ano;
            } elseif ($tipo == 'Brasileiro') {
                if (!isset($tit_br[$nome])) $tit_br[$nome] = ['nacao' => $c['nacionalidade'], 'qtd' => 0, 'anos' => []];
                $tit_br[$nome]['qtd']++;
                $tit_br[$nome]['anos'][] = $ano;
            }
        }

        // Agrupamento histórico universal (Ano > Tipo da Competição)
        $por_ano[$ano][$tipo][] = $c;
    }
    
    // Ordena do maior pro menor os Campeões Históricos
    uasort($tit_mundiais, function($a,$b) { return $b['qtd'] <=> $a['qtd']; });
    uasort($tit_gv, function($a,$b) { return $b['qtd'] <=> $a['qtd']; });
    uasort($tit_br, function($a,$b) { return $b['qtd'] <=> $a['qtd']; });
    krsort($por_ano); // Ordena os anos do mais novo para o mais velho
    ?>
    <style>
        /* Estilos Limpos da Ferramenta de Campeões */
        .goval-champions-wrapper { max-width: 1300px; margin: 0 auto; padding: 20px; font-family: 'Inter', sans-serif;}
        .goval-tabs-nav { display: flex; gap: 10px; border-bottom: 2px solid #007A53; margin-bottom: 30px; overflow-x: auto; padding-bottom: 10px; }
        .goval-tab-btn { padding: 12px 24px; border:none; background: #eee; color: #333; cursor: pointer; font-weight: bold; border-radius: 4px; white-space: nowrap; transition: 0.2s;}
        .goval-tab-btn.active { background: #007A53; color: white; }
        .goval-tab-btn:hover:not(.active) { background: #ddd; }
        .goval-tab-content { display: none; animation: fadeIn 0.5s; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

        .goval-ranking-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; margin-top: 20px; }
        .goval-board { background: white; border-radius: 8px; border: 1px solid #ddd; padding: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .goval-board h3 { margin: 0 0 15px 0; color: #007A53; border-bottom: 2px solid #007A53; padding-bottom: 10px; }
        .goval-card { border-left: 5px solid #FFB81C; background: #fafafa; padding: 15px; margin-bottom: 15px; border-radius: 4px; }
        .gold-border { border-left-color: #FFD700 !important; }
        .silver-border { border-left-color: #C0C0C0 !important; }
        .bronze-border { border-left-color: #CD7F32 !important; }
    </style>

    <div class="goval-champions-wrapper">
        <!-- NAVEGAÇÃO -->
        <div class="goval-tabs-nav">
            <button onclick="openGovalTab(event, 'tab-atual')" class="goval-tab-btn active">👑 Campeões da Temporada</button>
            <button onclick="openGovalTab(event, 'tab-maiores')" class="goval-tab-btn">🏆 Resumo de Títulos Históricos</button>
            <?php foreach (array_keys($por_ano) as $anoKey): ?>
                <button onclick="openGovalTab(event, 'tab-ano-<?php echo esc_attr($anoKey); ?>')" class="goval-tab-btn">🏁 Ano <?php echo esc_html($anoKey); ?></button>
            <?php endforeach; ?>
        </div>

        <!-- ABA 1: CAMPEÕES ATUAIS (Destaque Biográfico) -->
        <div id="tab-atual" class="goval-tab-content" style="display: block;">
            <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 30px;">
                <!-- Mundial 2026 -->
                <?php if ($atual_mundial): ?>
                <div style="background: white; border-top: 5px solid #007A53; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                    <span style="background: #e90000; color: white; padding: 5px 10px; font-weight: bold; border-radius: 4px;">CAMPEÃO MUNDIAL</span>
                    <h1 style="margin: 15px 0; color: #222; font-size: 2.5em;"><?php echo esc_html($atual_mundial['title']); ?></h1>
                    <p style="font-size: 1.1em; color: #555;"><strong>Nação:</strong> <?php echo esc_html($atual_mundial['nacionalidade']); ?></p>
                    
                    <?php if ($atual_mundial['etapas']): ?>
                    <div style="background: #f9f9f9; padding: 15px; border-radius: 6px; margin: 15px 0; border-left: 4px solid #007A53;">
                        <h4 style="margin-top: 0; color: #007A53;">📊 Resultados por Etapa</h4>
                        <div style="font-size: 0.9em; line-height: 1.4;"><?php echo nl2br(esc_html($atual_mundial['etapas'])); ?></div>
                    </div>
                    <?php endif; ?>

                    <div style="margin: 15px 0;">
                        <h4 style="margin-bottom: 5px; color: #333;">🛠️ Equipamento Completo</h4>
                        <p style="font-size: 0.9em; color: #666; margin: 0;"><?php echo esc_html($atual_mundial['equip_completo'] ?: $atual_mundial['equipamento']); ?></p>
                    </div>

                    <p style="color: #444; font-style: italic; line-height: 1.5; border-top: 1px solid #eee; padding-top: 15px;"><?php echo wp_kses_post($atual_mundial['bio']); ?></p>
                </div>
                <?php endif; ?>

                <!-- Circuito GV 2026 -->
                <?php if ($atual_gv): ?>
                <div style="background: white; border-top: 5px solid #FFB81C; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                    <span style="background: #333; color: white; padding: 5px 10px; font-weight: bold; border-radius: 4px;">REI DA IBITURUNA (CIRCUITO GV)</span>
                    <h1 style="margin: 15px 0; color: #222; font-size: 2.5em;"><?php echo esc_html($atual_gv['title']); ?></h1>
                    
                    <?php if ($atual_gv['voo_log']): ?>
                    <div style="background: #fff8e5; padding: 15px; border-radius: 6px; margin: 15px 0; border-left: 4px solid #FFB81C;">
                        <h4 style="margin-top: 0; color: #856404;">⏱️ Telemetria do Voo Final</h4>
                        <div style="font-size: 0.9em; line-height: 1.4;"><?php echo nl2br(esc_html($atual_gv['voo_log'])); ?></div>
                    </div>
                    <?php endif; ?>

                    <div style="margin: 15px 0;">
                        <h4 style="margin-bottom: 5px; color: #333;">🛠️ Kit Profissional</h4>
                        <p style="font-size: 0.9em; color: #666; margin: 0;"><?php echo esc_html($atual_gv['equip_completo'] ?: $atual_gv['equipamento']); ?></p>
                    </div>

                    <p style="color: #444; font-style: italic; line-height: 1.5; border-top: 1px solid #eee; padding-top: 15px;"><?php echo wp_kses_post($atual_gv['bio']); ?></p>
                </div>
                <?php endif; ?>

                <!-- Brasileiro (Só se houver espaço ou em nova linha) -->
                <?php
                // Busca o atual brasileiro separadamente para destaque
                $atual_br = null;
                foreach($campeoes as $c) if($c['ano'] == '2026' && $c['pos'] == 1 && $c['type'] == 'Brasileiro') $atual_br = $c;
                if ($atual_br):
                ?>
                <div style="background: white; border-top: 5px solid #222; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); grid-column: span 2;">
                    <span style="background: #007A53; color: white; padding: 5px 10px; font-weight: bold; border-radius: 4px;">CAMPEÃO BRASILEIRO</span>
                    <div style="display: flex; gap: 30px; flex-wrap: wrap; align-items: flex-start; margin-top: 15px;">
                        <div style="flex: 1; min-width: 300px;">
                            <h2 style="margin: 0; color: #222; font-size: 2.2em;"><?php echo esc_html($atual_br['title']); ?></h2>
                            <p style="color: #666; margin-top: 10px;"><?php echo wp_kses_post($atual_br['bio']); ?></p>
                        </div>
                        <?php if ($atual_br['conquistas']): ?>
                        <div style="flex: 1; min-width: 300px; background: #f0f0f0; padding: 20px; border-radius: 8px;">
                            <h4 style="margin-top: 0; color: #222;">🏅 Recordes e Pontuação</h4>
                            <div style="font-size: 0.95em; color: #333; line-height: 1.5;"><?php echo nl2br(esc_html($atual_br['conquistas'])); ?></div>
                            <div style="margin-top: 15px; font-size: 0.85em; color: #666;"><strong>Gear:</strong> <?php echo esc_html($atual_br['equip_completo']); ?></div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- ABA 2: MAIORES CAMPEÕES (Rankings Agregados por Números de Títulos) -->
        <div id="tab-maiores" class="goval-tab-content">
            <div class="goval-ranking-grid">
                <!-- Quadro Mundiais -->
                <div class="goval-board">
                    <h3>🌍 Ranking de Mundiais</h3>
                    <table style="width: 100%; border-collapse: collapse; text-align: left;">
                        <tr style="background:#f1f1f1;"><th style="padding:10px;">Atleta</th><th style="padding:10px; width:50%;">Ouros e Anos</th></tr>
                        <?php foreach($tit_mundiais as $nome => $d): rsort($d['anos']); ?>
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding:10px;"><strong><?php echo esc_html($nome); ?></strong><br><small><?php echo esc_html($d['nacao']); ?></small></td>
                            <td style="padding:10px; color:#007A53; font-weight:bold; font-size:1.1em;">
                                <?php echo $d['qtd']; ?> Troféus<br>
                                <span style="font-size:0.7em; color:#666; font-weight:normal; letter-spacing:-0.5px;">[<?php echo implode(', ', $d['anos']); ?>]</span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <!-- Quadro GV -->
                <div class="goval-board">
                    <h3>⛰️ Titulares de Governador Valadares</h3>
                    <table style="width: 100%; border-collapse: collapse; text-align: left;">
                        <tr style="background:#f1f1f1;"><th style="padding:10px;">Atleta</th><th style="padding:10px; width:50%;">Ouros e Anos</th></tr>
                        <?php foreach($tit_gv as $nome => $d): rsort($d['anos']); ?>
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding:10px;"><strong><?php echo esc_html($nome); ?></strong><br><small><?php echo esc_html($d['nacao']); ?></small></td>
                            <td style="padding:10px; color:#FFB81C; font-weight:bold; font-size:1.1em;">
                                <?php echo $d['qtd']; ?> Troféus<br>
                                <span style="font-size:0.7em; color:#666; font-weight:normal; letter-spacing:-0.5px;">[<?php echo implode(', ', $d['anos']); ?>]</span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <!-- Quadro Brasileiros -->
                <div class="goval-board">
                    <h3>🇧🇷 Ranking Brasileiro Absoluto</h3>
                    <table style="width: 100%; border-collapse: collapse; text-align: left;">
                        <tr style="background:#f1f1f1;"><th style="padding:10px;">Atleta</th><th style="padding:10px; width:50%;">Ouros e Anos</th></tr>
                        <?php foreach($tit_br as $nome => $d): rsort($d['anos']); ?>
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding:10px;"><strong><?php echo esc_html($nome); ?></strong><br><small><?php echo esc_html($d['nacao']); ?></small></td>
                            <td style="padding:10px; color:#333; font-weight:bold; font-size:1.1em;">
                                <?php echo $d['qtd']; ?> Troféus<br>
                                <span style="font-size:0.7em; color:#666; font-weight:normal; letter-spacing:-0.5px;">[<?php echo implode(', ', $d['anos']); ?>]</span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        </div>

        <!-- ABAS: DETALHES POR ANO -->
        <?php foreach ($por_ano as $anoKey => $tiposNoAno): ?>
        <div id="tab-ano-<?php echo esc_attr($anoKey); ?>" class="goval-tab-content">
            <h2 style="color: #007A53; font-size: 2em; margin-bottom: 0;">Temporada <?php echo esc_html($anoKey); ?></h2>
            <p style="color: #666; margin-top: 0;">Resultados oficiais unificados nas categorias Mundiais, Brasileiras e Locais.</p>

            <div class="goval-ranking-grid">
                
                <!-- Coluna 1: Mundiais (Até Top 5) -->
                <?php if (isset($tiposNoAno['Mundial'])): 
                    usort($tiposNoAno['Mundial'], function($a,$b){ return $a['pos'] <=> $b['pos']; });
                ?>
                <div class="goval-board">
                    <h3>🌍 Etapa Mundial (Top 5)</h3>
                    <?php foreach (array_slice($tiposNoAno['Mundial'], 0, 5) as $p): 
                        $border = 'goval-card';
                        if($p['pos']==1) $border = 'goval-card gold-border';
                        if($p['pos']==2) $border = 'goval-card silver-border';
                        if($p['pos']==3) $border = 'goval-card bronze-border';
                    ?>
                    <div class="<?php echo $border; ?>">
                        <span style="font-weight:bold; color:#007A53; display:inline-block; margin-bottom:5px;">#<?php echo $p['pos']; ?> LUGAR</span>
                        <h4 style="margin: 0 0 5px 0; font-size: 1.3em;"><?php echo esc_html($p['title']); ?></h4>
                        <div style="font-size: 0.9em; color: #555;">
                            <strong><?php echo esc_html($p['nacionalidade']); ?></strong><br>
                            Vela: <em><?php echo esc_html($p['equipamento']); ?></em>
                        </div>
                        <p style="font-size: 0.9em; margin-top: 8px; color: #444; border-top: 1px dotted #ccc; padding-top: 5px;">
                            <?php echo wp_trim_words($p['bio'], 15); ?>
                        </p>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <!-- Coluna 2: Circuito GV (Até Top 5) -->
                <?php if (isset($tiposNoAno['GV'])): 
                    usort($tiposNoAno['GV'], function($a,$b){ return $a['pos'] <=> $b['pos']; });
                ?>
                <div class="goval-board">
                    <h3>⛰️ Circuito Ibituruna (Top 5)</h3>
                    <?php foreach (array_slice($tiposNoAno['GV'], 0, 5) as $p): ?>
                    <div class="goval-card">
                        <span style="font-weight:bold; color:#555; display:inline-block; margin-bottom:5px;">#<?php echo $p['pos']; ?> LUGAR</span>
                        <h4 style="margin: 0 0 5px 0; font-size: 1.3em;"><?php echo esc_html($p['title']); ?></h4>
                        <div style="font-size: 0.9em; color: #555;">
                            <strong><?php echo esc_html($p['nacionalidade']); ?></strong><br>
                            Vela: <em><?php echo esc_html($p['equipamento']); ?></em>
                        </div>
                        <p style="font-size: 0.9em; margin-top: 8px; color: #444; border-top: 1px dotted #ccc; padding-top: 5px;"><?php echo wp_trim_words($p['bio'], 15); ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <!-- Coluna 3: Campeões Brasileiros -->
                <?php if (isset($tiposNoAno['Brasileiro'])): 
                    usort($tiposNoAno['Brasileiro'], function($a,$b){ return $a['pos'] <=> $b['pos']; });
                ?>
                <div class="goval-board">
                    <h3>🇧🇷 Campeonato Brasileiro</h3>
                    <?php foreach (array_slice($tiposNoAno['Brasileiro'], 0, 5) as $p): ?>
                    <div class="goval-card">
                        <span style="font-weight:bold; color:#222; display:inline-block; margin-bottom:5px;">#<?php echo $p['pos']; ?> NACIONAL</span>
                        <h4 style="margin: 0 0 5px 0; font-size: 1.3em;"><?php echo esc_html($p['title']); ?></h4>
                        <div style="font-size: 0.9em; color: #555;"><strong><?php echo esc_html($p['nacionalidade']); ?></strong><br>Vela: <em><?php echo esc_html($p['equipamento']); ?></em></div>
                        <p style="font-size: 0.9em; margin-top: 8px; color: #444; border-top: 1px dotted #ccc; padding-top: 5px;"><?php echo wp_trim_words($p['bio'], 15); ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Script Isolado para as Abas do Elementor -->
    <script>
    function openGovalTab(evt, tabId) {
        let i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("goval-tab-content");
        for (i = 0; i < tabcontent.length; i++) { tabcontent[i].style.display = "none"; }
        tablinks = document.getElementsByClassName("goval-tab-btn");
        for (i = 0; i < tablinks.length; i++) { tablinks[i].className = tablinks[i].className.replace(" active", ""); }
        document.getElementById(tabId).style.display = "block";
        evt.currentTarget.className += " active";
    }
    </script>
    <?php
    return ob_get_clean();
}

