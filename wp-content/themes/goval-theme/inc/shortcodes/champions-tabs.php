<?php
/**
 * Shortcode: Abas e Tabelas de Campeões
 * Exibe o histórico de campeões em abas interativas.
 */

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
    if(!empty($tit_mundiais)) uasort($tit_mundiais, function($a,$b) { return $b['qtd'] <=> $a['qtd']; });
    if(!empty($tit_gv)) uasort($tit_gv, function($a,$b) { return $b['qtd'] <=> $a['qtd']; });
    if(!empty($tit_br)) uasort($tit_br, function($a,$b) { return $b['qtd'] <=> $a['qtd']; });
    krsort($por_ano); // Ordena os anos do mais novo para o mais velho
    ?>

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

                <!-- Brasileiro -->
                <?php
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

        <!-- ABA 2: MAIORES CAMPEÕES -->
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
                
                <!-- Coluna 1: Mundiais -->
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
                        <div style="font-size: 0.9em; color: #555;"><strong><?php echo esc_html($p['nacionalidade']); ?></strong><br>Vela: <em><?php echo esc_html($p['equipamento']); ?></em></div>
                        <p style="font-size: 0.9em; margin-top: 8px; color: #444; border-top: 1px dotted #ccc; padding-top: 5px;"><?php echo wp_trim_words($p['bio'], 15); ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <!-- Coluna 2: Circuito GV -->
                <?php if (isset($tiposNoAno['GV'])): 
                    usort($tiposNoAno['GV'], function($a,$b){ return $a['pos'] <=> $b['pos']; });
                ?>
                <div class="goval-board">
                    <h3>⛰️ Circuito Ibituruna (Top 5)</h3>
                    <?php foreach (array_slice($tiposNoAno['GV'], 0, 5) as $p): ?>
                    <div class="goval-card">
                        <span style="font-weight:bold; color:#555; display:inline-block; margin-bottom:5px;">#<?php echo $p['pos']; ?> LUGAR</span>
                        <h4 style="margin: 0 0 5px 0; font-size: 1.3em;"><?php echo esc_html($p['title']); ?></h4>
                        <div style="font-size: 0.9em; color: #555;"><strong><?php echo esc_html($p['nacionalidade']); ?></strong><br>Vela: <em><?php echo esc_html($p['equipamento']); ?></em></div>
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
        if (evt) evt.currentTarget.className += " active";
    }
    </script>
    <?php
    return ob_get_clean();
}
