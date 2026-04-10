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
        /* Estilos limpos para o Grid de Notícias */
        .goval-news-wrapper { max-width: 1300px; margin: 40px auto; padding: 0 20px; display: grid; grid-template-columns: 2fr 1fr; gap: 40px; font-family: 'Inter', sans-serif; }
        .goval-main-card { position: relative; overflow: hidden; border-radius: 8px; box-shadow: 0 6px 20px rgba(0,0,0,0.15); }
        .goval-main-bg { height: 550px; width: 100%; transition: transform 0.5s ease; background-position: center; background-size: cover; }
        .goval-main-card:hover .goval-main-bg { transform: scale(1.03); }
        .goval-overlay { position: absolute; bottom: 0; left: 0; width: 100%; padding: 40px; background: linear-gradient(transparent, rgba(0,0,0,0.95)); color: white; }
        .goval-badge { background: #e90000; color: white; padding: 6px 12px; font-weight: bold; border-radius: 4px; font-size: 0.9em; text-transform: uppercase; }
        .goval-subgrid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 30px; }
        
        /* Responsividade Básica */
        @media (max-width: 900px) {
            .goval-news-wrapper { grid-template-columns: 1fr; }
            .goval-main-bg { height: 350px; }
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
            <div class="goval-main-card">
                <div class="goval-main-bg" style="background-image: url('<?php echo esc_url($capa); ?>');"></div>
                <div class="goval-overlay">
                    <span class="goval-badge">Destaque Oficial</span>
                    <h1 style="margin: 20px 0 10px 0; font-size: 3.2em; line-height: 1.1; text-shadow: 2px 2px 5px rgba(0,0,0,0.9);">
                        <a href="<?php the_permalink(); ?>" style="color: white; text-decoration: none;"><?php the_title(); ?></a>
                    </h1>
                    <p style="font-size: 1.25em; opacity: 0.95; margin: 0; text-shadow: 1px 1px 3px rgba(0,0,0,0.8);"><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>
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
                <div style="border-radius: 8px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.1); background: white;">
                    <div style="background: url('<?php echo esc_url($capa); ?>') center/cover; height: 200px; width: 100%;"></div>
                    <div style="padding: 15px;">
                        <h3 style="margin: 0; font-size: 1.2em; line-height: 1.3;"><a href="<?php the_permalink(); ?>" style="color: #222; text-decoration: none;"><?php the_title(); ?></a></h3>
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
            <div style="display: flex; gap: 20px; border-bottom: 1px solid #ddd; padding-bottom: 20px;">
                <div style="background: url('<?php echo esc_url($capa); ?>') center/cover; min-width: 120px; height: 90px; border-radius: 6px;"></div>
                <div>
                    <span style="color: #e90000; font-weight: bold; font-size: 0.85em; text-transform: uppercase;"><?php echo esc_html($cat); ?></span>
                    <h3 style="margin: 8px 0; font-size: 1.1em; line-height: 1.3;"><a href="<?php the_permalink(); ?>" style="color: #333; text-decoration: none;"><?php the_title(); ?></a></h3>
                </div>
            </div>
            <?php endwhile; endif; wp_reset_postdata(); ?>
        </div>
    </div>
    <?php
    return ob_get_clean(); // Retorna o componente renderizado para o Elementor
}

// ============================================================================
// 2. SHORTCODE: ABAS E TABELAS DE CAMPEÕES
// Uso no Elementor: [goval_champions_tabs]
// ============================================================================
add_shortcode('goval_champions_tabs', 'goval_champions_tabs_shortcode');
function goval_champions_tabs_shortcode($atts) {
    ob_start();

    // Query robusta para resgatar todos os campeões do CPT
    $campeoes_query = new WP_Query(['post_type' => 'campeao', 'posts_per_page' => -1]);
    $campeoes = [];
    if ($campeoes_query->have_posts()) {
        while ($campeoes_query->have_posts()) {
            $campeoes_query->the_post();
            $campeoes[] = [
                'title' => get_the_title(),
                'bio' => get_the_excerpt(),
                'nacionalidade' => get_post_meta(get_the_ID(), 'nacionalidade', true),
                'equipamento' => get_post_meta(get_the_ID(), 'equipamento', true),
                'ano' => get_post_meta(get_the_ID(), 'ano_campeonato', true),
                'pos' => get_post_meta(get_the_ID(), 'posicao', true) ?: 1,
                'type' => get_post_meta(get_the_ID(), 'tipo_torneio', true)
            ];
        }
    }
    wp_reset_postdata();

    // Separação de Lógica de Negócio (Organiza em arrays por Ano e Top Rankings)
    $atual = null;
    $maiores = [];
    $por_ano = [];

    foreach ($campeoes as $c) {
        if ($c['ano'] == '2026' && $c['pos'] == 1) $atual = $c;
        if ($c['type'] == 'Historico Top' || $c['pos'] == 1) {
            if (!in_array($c, $maiores)) $maiores[] = $c;
        }
        $por_ano[$c['ano']][] = $c;
    }
    krsort($por_ano); // Z-A (Anos mais recentes primeiro)
    ?>
    <style>
        /* Estilos Isolados para não conflitar com Elementor */
        .goval-tabs-nav { display: flex; gap: 10px; border-bottom: 2px solid #007A53; margin-bottom: 30px; overflow-x: auto; padding-bottom: 10px; font-family: 'Inter', sans-serif;}
        .goval-tab-btn { padding: 12px 24px; border:none; background: #eee; color: #333; cursor: pointer; font-weight: bold; border-radius: 4px; white-space: nowrap; transition: 0.2s;}
        .goval-tab-btn.active { background: #007A53; color: white; }
        .goval-tab-btn:hover:not(.active) { background: #ddd; }
        .goval-tab-content { display: none; animation: fadeIn 0.5s; font-family: 'Inter', sans-serif;}
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    </style>

    <div class="goval-champions-wrapper" style="max-width: 1200px; margin: 0 auto; padding: 20px;">
        
        <!-- Navegação das Abas -->
        <div class="goval-tabs-nav">
            <button onclick="openGovalTab(event, 'tab-atual')" class="goval-tab-btn active">👑 Campeão Atual</button>
            <button onclick="openGovalTab(event, 'tab-maiores')" class="goval-tab-btn">🌍 Maiores Campeões</button>
            <?php foreach (array_keys($por_ano) as $anoKey): ?>
                <button onclick="openGovalTab(event, 'tab-ano-<?php echo esc_attr($anoKey); ?>')" class="goval-tab-btn">Ano <?php echo esc_html($anoKey); ?></button>
            <?php endforeach; ?>
        </div>

        <!-- CONTEÚDO: Campeão Atual -->
        <div id="tab-atual" class="goval-tab-content" style="display: block;">
            <?php if ($atual): ?>
            <div style="background: white; border-top: 5px solid #007A53; padding: 40px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); display: flex; gap: 30px; align-items: center; flex-wrap: wrap;">
                <div style="background: url('https://images.unsplash.com/photo-1518331165337-25e227568541?w=400&q=80') center/cover; min-width: 200px; height: 200px; border-radius: 50%; box-shadow: 0 4px 10px rgba(0,0,0,0.2);"></div>
                <div>
                    <span style="background: #FFB81C; padding: 5px 10px; font-size: 0.8em; text-transform: uppercase; font-weight: bold; border-radius: 4px;">Top 1 - <?php echo esc_html($atual['ano']); ?></span>
                    <h1 style="margin: 10px 0; color: #222; font-size: 3em;"><?php echo esc_html($atual['title']); ?></h1>
                    <p style="font-size: 1.2em; color: #555;"><strong>Nação:</strong> <?php echo esc_html($atual['nacionalidade']); ?> | <strong>Glider:</strong> <?php echo esc_html($atual['equipamento']); ?></p>
                    <div style="font-size: 1.1em; line-height: 1.6; color: #444; margin-top: 15px;"><?php echo wp_kses_post($atual['bio']); ?></div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- CONTEÚDO: Maiores Campeões (Tabela) -->
        <div id="tab-maiores" class="goval-tab-content">
            <div style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
                <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
                    <thead>
                        <tr style="background: #222; color: white; text-align: left;">
                            <th style="padding: 15px;">Nome do Atleta</th>
                            <th style="padding: 15px;">Ano de Glória</th>
                            <th style="padding: 15px;">Nação</th>
                            <th style="padding: 15px;">Resumo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($maiores as $m): ?>
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 15px; font-weight: bold; color: #007A53;"><?php echo esc_html($m['title']); ?></td>
                            <td style="padding: 15px;"><strong><?php echo esc_html($m['ano']); ?></strong></td>
                            <td style="padding: 15px;"><?php echo esc_html($m['nacionalidade']); ?></td>
                            <td style="padding: 15px; font-size: 0.9em; color: #666;"><?php echo esc_html($m['bio']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- CONTEÚDO: Categorias por Ano -->
        <?php foreach ($por_ano as $anoKey => $pilotosAno): ?>
        <div id="tab-ano-<?php echo esc_attr($anoKey); ?>" class="goval-tab-content">
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px;">
                <?php 
                usort($pilotosAno, function($a, $b) { return $a['pos'] <=> $b['pos']; });
                foreach ($pilotosAno as $p): 
                ?>
                <div style="background: white; border: 1px solid #ddd; border-top: 4px solid <?php echo ($p['pos']==1)?'#FFB81C':'#999'; ?>; padding: 25px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                    <h3 style="margin: 0; color: #555;"><?php echo esc_html($p['pos']); ?>º Lugar</h3>
                    <h4 style="margin: 5px 0 15px 0; font-size: 1.6em; color: #007A53;"><?php echo esc_html($p['title']); ?></h4>
                    <p style="margin: 3px 0;"><strong>Nação:</strong> <?php echo esc_html($p['nacionalidade']); ?></p>
                    <p style="margin: 3px 0;"><strong>Parapente:</strong> <?php echo esc_html($p['equipamento']); ?></p>
                    <p style="font-size: 0.95em; color: #666; margin-top: 15px; font-style: italic;"><?php echo esc_html($p['bio']); ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>

    </div>

    <!-- Script Isolado para as Abas -->
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
