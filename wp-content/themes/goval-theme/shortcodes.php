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
                'type' => get_post_meta(get_the_ID(), 'tipo_torneio', true) ?: 'Mundial'
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
                    <p style="font-size: 1.1em; color: #555;"><strong>Nação:</strong> <?php echo esc_html($atual_mundial['nacionalidade']); ?> <br/><strong>Vela (Glider):</strong> <?php echo esc_html($atual_mundial['equipamento']); ?></p>
                    <p style="color: #444; font-style: italic; line-height: 1.5;"><?php echo wp_kses_post($atual_mundial['bio']); ?></p>
                </div>
                <?php endif; ?>
                <!-- Circuito GV 2026 -->
                <?php if ($atual_gv): ?>
                <div style="background: white; border-top: 5px solid #FFB81C; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                    <span style="background: #333; color: white; padding: 5px 10px; font-weight: bold; border-radius: 4px;">REI DA IBITURUNA (CIRCUITO GV)</span>
                    <h1 style="margin: 15px 0; color: #222; font-size: 2.5em;"><?php echo esc_html($atual_gv['title']); ?></h1>
                    <p style="font-size: 1.1em; color: #555;"><strong>Nação:</strong> <?php echo esc_html($atual_gv['nacionalidade']); ?> <br/><strong>Vela (Glider):</strong> <?php echo esc_html($atual_gv['equipamento']); ?></p>
                    <p style="color: #444; font-style: italic; line-height: 1.5;"><?php echo wp_kses_post($atual_gv['bio']); ?></p>
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

// ============================================================================
// 3. SHORTCODE: MAPA 3D AVANÇADO (Maplibre + Deck.gl)
// Uso no Elementor: [goval_3d_map]
// ============================================================================
add_shortcode('goval_3d_map', 'goval_3d_map_shortcode');
function goval_3d_map_shortcode($atts) {
    ob_start();
    
    // Geração da Base de Dados PHP (Pilotos Georreferenciados)
    // Esta mesma lógica foi criada anteriormente e acoplada no Shortcode para isolamento global
    $atletas = [];
    $nomes = ["Erico Oliveira", "Rafael Saladini", "Caio Buzzarello", "Luciano Horn", "Stephan Schmoker", "Frank Brown", "Honorato"];
    for ($i = 0; $i < 7; $i++) {
        $seed = ($i * 0.005) - 0.05; 
        $seed_alt = $i * 35; 
        $path = [
            [-41.9442, -18.8872, 1123, "Decolagem - Pico Ibituruna"],
            [-41.9390 + ($seed/2), -18.8800 + $seed, 1500 + $seed_alt, "Térmica Inicial"],
            [-41.9200 + $seed, -18.8600 - $seed, 2100 + $seed_alt, "Max Climb"],
            [-41.8300 + $seed, -18.7800 - $seed, 1200 + $seed_alt, "Transição"],
            [-41.7700 + $seed, -18.7500 + $seed, 250, "Glide Ratio"],
            [-41.7500 + $seed, -18.7300 + $seed, 160, "Pouso (Resgate)"]
        ];
        $atletas[] = [$nomes[$i], "Brasil", "3h 15m", (2100 + $seed_alt)."m", "Equipamento Pro", $path];
    }
    $json_atletas = json_encode($atletas);
    ?>
    <style>
        .mapa-3d-wrapper { display: flex; height: 80vh; min-height: 600px; font-family: 'Inter', sans-serif; border: 1px solid #ddd; border-radius: 8px; overflow: hidden; }
        .mapa-sidebar { width: 340px; background: white; padding: 25px; border-right: 2px solid #ccc; overflow-y: auto; z-index: 10; }
        .mapa-container-gl { flex-grow: 1; position: relative; background: #000; }
        @media (max-width: 800px) { .mapa-3d-wrapper { flex-direction: column; height: 120vh; } .mapa-sidebar { width: 100%; height: auto; } }
    </style>

    <div class="mapa-3d-wrapper">
        <!-- Sidebar de Controles Embutida no Shortcode -->
        <div class="mapa-sidebar">
            <h2 style="color: #007A53; margin: 0 0 15px 0;">Menu de Telemetria 3D</h2>
            
            <label style="font-size:0.9em; color:#007A53;"><b>Piloto 1 (Verde):</b></label>
            <select id="sim-piloto1" style="width:100%; padding: 10px; margin-bottom: 10px; border-radius:4px; border: 1px solid #ccc; background:#e8f4ef;">
                <?php foreach($atletas as $idx => $a): ?> <option value="<?php echo $idx; ?>"><?php echo esc_html($a[0]); ?></option> <?php endforeach; ?>
            </select>

            <label style="font-size:0.9em; color:#d99500;"><b>Piloto 2 (Ouro):</b></label>
            <select id="sim-piloto2" style="width:100%; padding: 10px; margin-bottom: 20px; border-radius:4px; border: 1px solid #ccc; background:#fff8e5;">
                <?php foreach($atletas as $idx => $a): ?> <option value="<?php echo $idx; ?>" <?php if($idx==1) echo 'selected'; ?>><?php echo esc_html($a[0]); ?></option> <?php endforeach; ?>
            </select>

            <button onclick="iniciarReplayCurto()" style="width: 100%; padding: 15px; background: #007A53; color: white; border: none; font-weight: bold; cursor: pointer; border-radius: 6px; font-size: 1.1em;">RENDERIZAR VOO</button>
            
            <div id="sim-painel" style="margin-top: 20px; font-size: 0.85em; color: #555; background: #fdfdfd; padding: 15px; border-radius: 4px; border: 1px solid #eee;">
                <strong>🎮 Instruções:</strong><br>
                - Clique no mapa e arraste.<br>
                - Segure <b>CTRL</b> + Clique e arraste para inclinar a visualização (3D).<br>
                - Passe o mouse sobre as bolinhas que flutuam para detalhes.
            </div>
            <div id="sim-detalhes" style="margin-top:15px; min-height: 80px; color:#222; font-weight:bold;"></div>
        </div>
        <div id="mapContainerGL" class="mapa-container-gl"></div>
    </div>

    <!-- Bibliotecas Externas isoladas -->
    <link href="https://unpkg.com/maplibre-gl@3.6.2/dist/maplibre-gl.css" rel="stylesheet" />
    <script src="https://unpkg.com/maplibre-gl@3.6.2/dist/maplibre-gl.js"></script>
    <script src="https://unpkg.com/deck.gl@8.9.0/dist.min.js"></script>

    <script>
        const dbAtl = <?php echo $json_atletas; ?>;
        
        // Proteção para garantir que o mapa só carrega quando a div existe (útil no Elementor)
        setTimeout(() => {
            const mapObj = new maplibregl.Map({
                container: 'mapContainerGL',
                style: {
                    version: 8,
                    sources: {
                        'google-sat': { type: 'raster', tiles: ['https://mt1.google.com/vt/lyrs=y&x={x}&y={y}&z={z}'], tileSize: 256 },
                        'aws-terr': { type: 'raster-dem', tiles: ['https://s3.amazonaws.com/elevation-tiles-prod/terrarium/{z}/{x}/{y}.png'], encoding: 'terrarium', tileSize: 256 }
                    },
                    layers: [{ id: 'sat-layer', type: 'raster', source: 'google-sat' }]
                },
                center: [-41.9300, -18.8800], zoom: 12.5, pitch: 65, bearing: 45
            });

            mapObj.addControl(new maplibregl.NavigationControl({ visualizePitch: true }), 'top-right');
            mapObj.on('load', () => mapObj.setTerrain({ source: 'aws-terr', exaggeration: 1.5 }));

            // Instância Global do Deck
            window.deckOverlayInstance = new deck.MapboxOverlay({ interleaved: true, layers: [] });
            mapObj.addControl(window.deckOverlayInstance);
            window.gmapObj = mapObj; // Expõe para a função
        }, 500);

        function iniciarReplayCurto() {
            const map = window.gmapObj;
            if(!map) return;
            const p1 = dbAtl[document.getElementById('sim-piloto1').value];
            const p2 = dbAtl[document.getElementById('sim-piloto2').value];

            const path1 = p1[5].map(pt => [pt[0], pt[1], pt[2]]);
            const path2 = p2[5].map(pt => [pt[0], pt[1], pt[2]]);

            let checkPoints = [];
            p1[5].forEach((pt, i) => checkPoints.push({ position: [pt[0],pt[1],pt[2]], color: [0, 160, 100], info: pt[3], piloto: p1[0] }));
            p2[5].forEach((pt, i) => checkPoints.push({ position: [pt[0],pt[1],pt[2]], color: [255, 200, 0], info: pt[3], piloto: p2[0] }));

            const layerLinhas = new deck.PathLayer({
                id: 'linhas-voo',
                data: [ { path: path1, color: [0, 122, 83] }, { path: path2, color: [255, 184, 28] } ],
                getWidth: 20, widthUnits: 'meters',
                getColor: d => d.color, getZ: d => d[2]
            });

            const layerPontos = new deck.ScatterplotLayer({
                id: 'pontos-voo', data: checkPoints,
                getPosition: d => d.position, getFillColor: d => d.color,
                getRadius: 80, pickable: true,
                onHover: ({object}) => {
                    const el = document.getElementById('sim-detalhes');
                    if (object) {
                        el.innerHTML = `<span style="background:#222;color:#fff;padding:4px;">${object.piloto}</span><br>Altitude: ${object.position[2]}m<br>${object.info}`;
                    } else el.innerHTML = '';
                }
            });

            window.deckOverlayInstance.setProps({ layers: [layerLinhas, layerPontos] });
            map.flyTo({ center: [-41.8800, -18.8250], zoom: 11.5, pitch: 75, bearing: -25, duration: 5000 });
        }
    </script>
    <?php
    return ob_get_clean();
}
