<?php
// seeder_historical.php
echo "Deletando antigos...\n";
$antigos = get_posts(['post_type' => 'campeao', 'numberposts' => -1]);
foreach($antigos as $p) { wp_delete_post($p->ID, true); }

$data = [];

// Histórico Estruturado MUNDIAIS
$hist_mundial = [
    'Chrigel Maurer' => ['nacao' => 'Suíça 🇨🇭', 'anos' => [2022, 2018, 2015, 2013], 'equip' => 'Advance Omega'],
    'Pepe Lopez' => ['nacao' => 'Espanha 🇪🇸', 'anos' => [2026, 2025, 2017], 'equip' => 'Niviuk Icepeak'],
    'Stefan Gruber' => ['nacao' => 'Áustria 🇦🇹', 'anos' => [2024, 2021, 2014], 'equip' => 'Nova'],
    'Richard Pethigal' => ['nacao' => 'Brasil 🇧🇷', 'anos' => [2012, 1999], 'equip' => 'Advance'],
    'Charles Cazaux' => ['nacao' => 'França 🇫🇷', 'anos' => [2009, 2008, 2001], 'equip' => 'Ozone'],
    'Honorato' => ['nacao' => 'Brasil 🇧🇷', 'anos' => [2023, 2007], 'equip' => 'Gin Gliders'],
    'Carlos Niemeyer' => ['nacao' => 'Brasil 🇧🇷', 'anos' => [2005, 1995, 1993], 'equip' => 'Woody Valley'],
    'Peter Neuenschwander' => ['nacao' => 'Suíça 🇨🇭', 'anos' => [2011, 2004], 'equip' => 'Advance'],
    'Luc Armant' => ['nacao' => 'França 🇫🇷', 'anos' => [2016, 2010], 'equip' => 'Ozone Enzo'],
    'Russell Ogden' => ['nacao' => 'Inglaterra 🇬🇧', 'anos' => [2019, 2006], 'equip' => 'Ozone']
];
foreach($hist_mundial as $nome => $d) {
    foreach($d['anos'] as $ano) {
        $data[] = ['nome' => $nome, 'nacao' => $d['nacao'], 'equip' => $d['equip'], 'ano' => $ano, 'tipo' => 'Mundial', 'pos' => 1, 'bio' => 'Domínio europeu impecável. Coroado Ouro Mundial Absoluto na prova de '.$ano.'.'];
    }
}

// Histórico Estruturado GV (Ibituruna)
$hist_gv = [
    'Frank Brown' => ['nacao' => 'Brasil 🇧🇷', 'anos' => [2025, 2024, 2012, 2010, 2006, 2004, 2002], 'equip' => 'Sol Tracer'],
    'Rafael Saladini' => ['nacao' => 'Brasil 🇧🇷', 'anos' => [2023, 2020, 2018, 2015, 2011], 'equip' => 'Ozone Enzo'],
    'Erico Oliveira' => ['nacao' => 'Brasil 🇧🇷', 'anos' => [2026, 2019, 2016], 'equip' => 'Gin Boomerang'],
    'Pedro Garcia' => ['nacao' => 'Brasil 🇧🇷', 'anos' => [2005, 2001, 1997], 'equip' => 'Skywalk'],
    'Luciano Horn' => ['nacao' => 'Brasil 🇧🇷', 'anos' => [2017, 2013, 2008], 'equip' => 'Ozone']
];
foreach($hist_gv as $nome => $d) {
    foreach($d['anos'] as $ano) {
        $data[] = ['nome' => $nome, 'nacao' => $d['nacao'], 'equip' => $d['equip'], 'ano' => $ano, 'tipo' => 'GV', 'pos' => 1, 'bio' => 'Rei do Ibituruna! Levantou a taça de Valadares voando na rota clássica em '.$ano.'.'];
    }
}

// Histórico Estruturado BRASILEIROS
$hist_br = [
    'Frank Brown' => ['nacao' => 'Brasil 🇧🇷', 'anos' => [2022, 2017, 2015, 2012, 2008, 2005, 2003, 1999], 'equip' => 'Sol Tracer'],
    'Rafael Saladini' => ['nacao' => 'Brasil 🇧🇷', 'anos' => [2021, 2019, 2014, 2011, 2009], 'equip' => 'Ozone Enzo'],
    'Erico Oliveira' => ['nacao' => 'Brasil 🇧🇷', 'anos' => [2024, 2020], 'equip' => 'Gin'],
    'Luciano Horn' => ['nacao' => 'Brasil 🇧🇷', 'anos' => [2025, 2018], 'equip' => 'Ozone Zeno'],
    'Honorato' => ['nacao' => 'Brasil 🇧🇷', 'anos' => [2023, 2016, 2013, 2010], 'equip' => 'Gin Gliders'],
    'Marcella Pomarico' => ['nacao' => 'Brasil 🇧🇷', 'anos' => [2026, 2007], 'equip' => 'Ozone Zeno'],
    'Marcelo Pietro' => ['nacao' => 'Brasil 🇧🇷', 'anos' => [2004, 2000, 1998], 'equip' => 'Niviuk / Advance']
];
foreach($hist_br as $nome => $d) {
    foreach($d['anos'] as $ano) {
        $data[] = ['nome' => $nome, 'nacao' => $d['nacao'], 'equip' => $d['equip'], 'ano' => $ano, 'tipo' => 'Brasileiro', 'pos' => 1, 'bio' => 'O imbatível da temporada. Ouro Nacional absoluto com performance sólida cravada em '.$ano.'.'];
    }
}

// Top 2 ao Top 5 de 2026 para rechear a tab visual
$extras = [
    ['nome' => 'Rafael Saladini', 'nacao' => 'Brasil 🇧🇷', 'equip' => 'Ozone', 'ano' => 2026, 'tipo' => 'GV', 'pos' => 2, 'bio' => 'Vice-campeão disputadíssimo em GV.'],
    ['nome' => 'Stefan Schmoker', 'nacao' => 'Suíça 🇨🇭', 'equip' => 'Advance', 'ano' => 2026, 'tipo' => 'Mundial', 'pos' => 2, 'bio' => 'Prata no Mundial'],
    ['nome' => 'Julien Wirtz', 'nacao' => 'França 🇫🇷', 'equip' => 'Ozone', 'ano' => 2026, 'tipo' => 'Mundial', 'pos' => 3, 'bio' => 'Bronze Mundial. Perdeu pontos por punição no GPS.'],
    ['nome' => 'Honorato', 'nacao' => 'Brasil 🇧🇷', 'equip' => 'Gin', 'ano' => 2026, 'tipo' => 'Mundial', 'pos' => 4, 'bio' => 'Pódio mundial para o Brasil no 4º lugar.'],
    ['nome' => 'Frank Brown', 'nacao' => 'Brasil 🇧🇷', 'equip' => 'Sol', 'ano' => 2026, 'tipo' => 'Mundial', 'pos' => 5, 'bio' => 'Top 5 Mundial.'],
];
foreach($extras as $e) $data[] = $e;

echo "Iniciando processamento e semeadura brutal de " . count($data) . " títulos épicos mundiais...\n";
foreach($data as $d) {
    $pid = wp_insert_post([
        'post_title' => $d['nome'], 'post_type' => 'campeao', 'post_status' => 'publish', 'post_excerpt' => $d['bio']
    ]);
    if ($pid) {
        update_post_meta($pid, 'nacionalidade', $d['nacao']);
        update_post_meta($pid, 'equipamento', $d['equip']);
        update_post_meta($pid, 'ano_campeonato', $d['ano']);
        update_post_meta($pid, 'tipo_torneio', $d['tipo']);
        update_post_meta($pid, 'posicao', $d['pos']);
    }
}
echo "Tudo perfeitamente registrado no WP DB!\n";
