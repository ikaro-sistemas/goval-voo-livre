<?php
/* Template Name: Os Campeões */
get_header();

// Consultar lista completa de campeões registrados no mock
$campeoes_query = new WP_Query(['post_type' => 'campeao', 'posts_per_page' => -1]);
$campeoes = [];
if ($campeoes_query->have_posts()) {
    while ($campeoes_query->have_posts()) {
        $campeoes_query->the_post();
        $campeoes[] = [
            'id' => get_the_ID(),
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

// Organização dos Dados
$atual = null;
$maiores = [];
$por_ano = [];

foreach ($campeoes as $c) {
    if ($c['ano'] == '2026' && $c['pos'] == 1) {
        $atual = $c;
    }
    if ($c['type'] == 'Historico Top' || $c['pos'] == 1) {
        if (!in_array($c, $maiores)) $maiores[] = $c;
    }
    $por_ano[$c['ano']][] = $c;
}
krsort($por_ano); // Ordena do mais recente para o mais antigo
?>

<div style="background: var(--vale-light); min-height: 100vh;">
<div style="max-width: 1200px; margin: 0 auto; padding: 30px 20px;">
    
    <!-- Sub-menus (Tabs) just below header -->
    <div style="display: flex; gap: 10px; border-bottom: 2px solid var(--vale-green); margin-bottom: 30px; overflow-x: auto; padding-bottom: 10px;">
        <button onclick="openTab('atual')" class="tab-btn active" style="padding: 10px 20px; border:none; background: var(--vale-green); color: white; cursor: pointer; font-weight: bold; border-radius: 4px;">Campeão Atual</button>
        <button onclick="openTab('maiores')" class="tab-btn" style="padding: 10px 20px; border:none; background: #ddd; color: #333; cursor: pointer; font-weight: bold; border-radius: 4px;">Maiores Campeões</button>
        
        <?php foreach (array_keys($por_ano) as $anoKey): ?>
        <button onclick="openTab('ano-<?php echo esc_attr($anoKey); ?>')" class="tab-btn" style="padding: 10px 20px; border:none; background: #eee; color: #333; cursor: pointer; border-radius: 4px;">Ano <?php echo esc_html($anoKey); ?></button>
        <?php endforeach; ?>
    </div>

    <!-- TAB 1: Campeão Atual -->
    <div id="atual" class="tab-content" style="display: block;">
        <?php if ($atual): ?>
        <div style="background: white; border-top: 5px solid var(--vale-green); padding: 40px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); display: flex; gap: 30px; align-items: center;">
            <div style="background: url('https://images.unsplash.com/photo-1518331165337-25e227568541?w=400&q=80') center/cover; min-width: 200px; height: 200px; border-radius: 50%; box-shadow: 0 4px 10px rgba(0,0,0,0.2);"></div>
            <div>
                <span class="badge" style="background: var(--vale-gold); padding: 5px 10px; font-size: 0.8em; text-transform: uppercase; font-weight: bold; border-radius: 4px;">Campeão 2026</span>
                <h1 style="margin: 10px 0; color: var(--vale-dark); font-size: 3em;"><?php echo esc_html($atual['title']); ?></h1>
                <p style="font-size: 1.2em; color: #555;"><strong>Continente/Nação:</strong> <?php echo esc_html($atual['nacionalidade']); ?> | <strong>Glider:</strong> <?php echo esc_html($atual['equipamento']); ?></p>
                <div style="font-size: 1.1em; line-height: 1.6; color: #444; margin-top: 15px;">
                    <?php echo wp_kses_post($atual['bio']); ?>
                </div>
            </div>
        </div>
        <?php else: ?>
        <p>Campeão mais recente não definido ainda.</p>
        <?php endif; ?>
    </div>

    <!-- TAB 2: Tabela de Maiores Campeões -->
    <div id="maiores" class="tab-content" style="display: none;">
        <div style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
            <h2 style="color: var(--vale-green); margin-top: 0; border-bottom: 2px solid #ddd; padding-bottom: 10px;">🏆 Tabela Geral Histórica - Os Imortais do Ibituruna</h2>
            <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
                <thead>
                    <tr style="background: var(--vale-dark); color: white; text-align: left;">
                        <th style="padding: 15px;">Gênio (Competidor)</th>
                        <th style="padding: 15px;">Ano de Glória</th>
                        <th style="padding: 15px;">Nacionalidade</th>
                        <th style="padding: 15px;">Resumo (Bio)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($maiores as $m): ?>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 15px; font-weight: bold; color: var(--vale-green);"><?php echo esc_html($m['title']); ?></td>
                        <td style="padding: 15px;"><strong><?php echo esc_html($m['ano']); ?></strong></td>
                        <td style="padding: 15px;"><?php echo esc_html($m['nacionalidade']); ?></td>
                        <td style="padding: 15px; font-size: 0.9em; color: #666;"><?php echo esc_html($m['bio']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- TABS DE ANOS ESPECÍFICOS -->
    <?php foreach ($por_ano as $anoKey => $pilotosAno): ?>
    <div id="ano-<?php echo esc_attr($anoKey); ?>" class="tab-content" style="display: none;">
        <div style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
            <h2 style="color: var(--vale-green); margin-top: 0;">Resumo da Etapa - Ano <?php echo esc_html($anoKey); ?></h2>
            <p style="color: #666; margin-bottom: 30px;">Detalhes e escalação dos top atletas que subiram no pódio neste ano na Ibituruna.</p>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px;">
                <?php 
                usort($pilotosAno, function($a, $b) { return $a['pos'] <=> $b['pos']; });
                foreach ($pilotosAno as $p): 
                ?>
                <div style="border: 1px solid #ddd; border-top: 4px solid <?php echo ($p['pos']==1)?'var(--vale-gold)':'#999'; ?>; padding: 20px; border-radius: 6px;">
                    <h3 style="margin: 0; color: var(--vale-dark);"><?php echo esc_html($p['pos']); ?>º Lugar</h3>
                    <h4 style="margin: 5px 0 15px 0; font-size: 1.5em; color: var(--vale-green);"><?php echo esc_html($p['title']); ?></h4>
                    <p style="margin: 3px 0;"><strong>Nação:</strong> <?php echo esc_html($p['nacionalidade']); ?></p>
                    <p style="margin: 3px 0;"><strong>Equipamento:</strong> <?php echo esc_html($p['equipamento']); ?></p>
                    <hr style="border: 0; border-top: 1px dashed #ccc; margin: 15px 0;">
                    <p style="font-size: 0.9em; color: #555;"><?php echo esc_html($p['bio']); ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

</div>
</div>

<script>
function openTab(tabId) {
    let contents = document.getElementsByClassName('tab-content');
    for(let i=0; i<contents.length; i++) {
        contents[i].style.display = 'none';
    }
    let btns = document.getElementsByClassName('tab-btn');
    for(let i=0; i<btns.length; i++) {
        btns[i].style.background = '#ddd';
        btns[i].style.color = '#333';
    }
    document.getElementById(tabId).style.display = 'block';
    let btnActive = event.currentTarget;
    btnActive.style.background = 'var(--vale-green)';
    btnActive.style.color = 'white';
}
</script>

<?php get_footer(); ?>
