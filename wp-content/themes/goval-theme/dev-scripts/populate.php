<?php
require_once __DIR__ . '/../../../wp-load.php';

echo "Populando campeões...\n";

$real_champs = [
    ['name' => 'Erico Oliveira', 'year' => 2026, 'country' => 'Brasil', 'glider' => 'Ozone Enzo 3'],
    ['name' => 'Caio Buzzarello', 'year' => 2026, 'country' => 'Brasil', 'glider' => 'Niviuk Icepeak'],
    ['name' => 'Luciano Horn', 'year' => 2026, 'country' => 'Brasil', 'glider' => 'Gin Boomerang'],
    ['name' => 'Marcella Pomarico', 'year' => 2026, 'country' => 'Brasil', 'glider' => 'Ozone Zeno 2'],
    ['name' => 'Stefan Schmoker', 'year' => 2022, 'country' => 'Suíça', 'glider' => 'Ozone Enzo 3'],
    ['name' => 'Frank Brown', 'year' => 2005, 'country' => 'Brasil', 'glider' => 'Sol Tracer'],
    ['name' => 'Rafael Saladini', 'year' => 2019, 'country' => 'Brasil', 'glider' => 'Ozone Enzo 3']
];

$first_names = ['Carlos', 'João', 'Pedro', 'Lucas', 'Mateus', 'Gabriel', 'Max', 'Thomas', 'Julien', 'Hans'];
$last_names = ['Silva', 'Santos', 'Oliveira', 'Muller', 'Schmidt', 'Dubois', 'Rossi', 'Kim', 'Tanaka', 'Cardoso'];
$gliders = ['Ozone Zeno 2', 'Niviuk Icepeak X-One', 'Gin Boomerang 12', 'Flow Spectra 2', 'UP Meru', 'Skywalk X-Alps'];
$countries = ['Brasil', 'França', 'Suíça', 'Alemanha', 'EUA', 'Itália', 'Japão', 'Espanha'];

$total = 123;
$created = 0;

for ($i = 0; $i < $total; $i++) {
    if ($i < count($real_champs)) {
        $c = $real_champs[$i];
        $title = $c['name'];
        $nacao = $c['country'];
        $equip = $c['glider'];
        $ano = $c['year'];
        $content = "Biografia de " . $title . ". Vencedor em Governador Valadares defendendo a bandeira de " . $nacao . ".";
    } else {
        $title = $first_names[array_rand($first_names)] . ' ' . $last_names[array_rand($last_names)];
        $nacao = $countries[array_rand($countries)];
        $equip = $gliders[array_rand($gliders)];
        $ano = rand(1995, 2025);
        $content = "Competidor experiente com histórico em voo livre mundial em Valadares. Piloto internacional de " . $nacao . ".";
    }

    $post_id = wp_insert_post([
        'post_title' => $title,
        'post_content' => $content,
        'post_status' => 'publish',
        'post_type' => 'campeao'
    ]);

    if ($post_id) {
        add_post_meta($post_id, 'nacionalidade', $nacao);
        add_post_meta($post_id, 'equipamento', $equip);
        add_post_meta($post_id, 'ano_campeonato', (string)$ano);
        $created++;
    }
}

echo "Criados $created campeões/competidores com sucesso!\n";
