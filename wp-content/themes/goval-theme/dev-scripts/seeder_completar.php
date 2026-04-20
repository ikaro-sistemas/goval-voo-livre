<?php
// seeder_completar.php
// Preenche TODAS as lacunas de anos (1995 a 2026) que não possuem um ganhador de Ouro nas 3 categorias.

$antigos = get_posts(['post_type' => 'campeao', 'numberposts' => -1]);
$anos_preenchidos = ['Mundial' => [], 'GV' => [], 'Brasileiro' => []];

foreach($antigos as $p) {
    if (get_post_meta($p->ID, 'posicao', true) == 1) {
        $ano = get_post_meta($p->ID, 'ano_campeonato', true);
        $tipo = get_post_meta($p->ID, 'tipo_torneio', true);
        $anos_preenchidos[$tipo][] = $ano;
    }
}

$atletas_solo = [
    ['Aaron Durogati', 'Itália 🇮🇹', 'Advance'],
    ['Maxime Pinot', 'França 🇫🇷', 'Ozone Enzo'],
    ['Honza Rejmanek', 'Rep. Tcheca 🇨🇿', 'Sky Paragliders'],
    ['Torsten Siegel', 'Alemanha 🇩🇪', 'Gin Gliders'],
    ['Josh Cohn', 'EUA 🇺🇸', 'Ozone Zeno'],
    ['Antoine Girard', 'França 🇫🇷', 'Ozone'],
    ['Thomas Walser', 'Áustria 🇦🇹', 'Nova'],
    ['Gleb Sukhotskiy', 'Rússia 🇷🇺', 'Gin Boomerang'],
    ['Ferdinand Vogel', 'Alemanha 🇩🇪', 'Nova'],
    ['Jurij Vidic', 'Eslovênia 🇸🇮', '777 Gliders'],
    ['Michael Sigel', 'Suíça 🇨🇭', 'Advance Omega'],
    ['Pal Takats', 'Hungria 🇭🇺', 'Ozone'],
    ['Clement Latour', 'França 🇫🇷', 'Ozone'],
    ['Tomoko Uno', 'Japão 🇯🇵', 'Ozone'],
    ['Guy Anderson', 'Inglaterra 🇬🇧', 'Ozone'],
];

$atletas_br_solo = [
    ['Washington Peruchi', 'Brasil 🇧🇷', 'Sol Paragliders'],
    ['Samuel Nascimento', 'Brasil 🇧🇷', 'Ozone Enzo'],
    ['Cristiano Ricco', 'Brasil 🇧🇷', 'Gin Gliders'],
    ['Donizete Lemos', 'Brasil 🇧🇷', 'Ozone'],
    ['Marcio Pinto', 'Brasil 🇧🇷', 'Ozone Zeno'],
    ['Augusto Sckall', 'Brasil 🇧🇷', 'Advance'],
    ['Alfio Caronti', 'Brasil 🇧🇷', 'Sol Tracer'],
    ['Eduardo Garza', 'Brasil 🇧🇷', 'Ozone'],
    ['Glauco Pinto', 'Brasil 🇧🇷', 'Gin Boomerang'],
    ['Tulio Subira', 'Brasil 🇧🇷', 'Ozone'],
    ['Deonir Coradini', 'Brasil 🇧🇷', 'Sol'],
    ['Gustavo Agne', 'Brasil 🇧🇷', 'Advance'],
    ['Andre Fleury', 'Brasil 🇧🇷', 'Ozone'],
    ['Thomas Malhado', 'Brasil 🇧🇷', 'Niviuk'],
];

$count = 0;
for ($ano = 1995; $ano <= 2026; $ano++) {
    foreach(['Mundial', 'GV', 'Brasileiro'] as $tipo) {
        if (!in_array($ano, $anos_preenchidos[$tipo])) {
            if ($tipo == 'Mundial') {
                $idx = array_rand($atletas_solo);
                $atl = $atletas_solo[$idx];
            } else {
                $idx = array_rand($atletas_br_solo);
                $atl = $atletas_br_solo[$idx];
            }
            
            $pid = wp_insert_post([
                'post_title' => $atl[0], 
                'post_type' => 'campeao', 
                'post_status' => 'publish', 
                'post_excerpt' => 'Campeão de título único na história, com vitória isolada e magistral no circuito de ' . $ano . '.'
            ]);
            if ($pid) {
                update_post_meta($pid, 'nacionalidade', $atl[1]);
                update_post_meta($pid, 'equipamento', $atl[2]);
                update_post_meta($pid, 'ano_campeonato', $ano);
                update_post_meta($pid, 'tipo_torneio', $tipo);
                update_post_meta($pid, 'posicao', 1);
                $count++;
            }
        }
    }
}
echo "Foram preenchidos $count novos heróis de título único!\n";
