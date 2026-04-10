<?php
// seeder.php - Run via wp eval-file
echo "Deletando antigos... \n";
$antigos = get_posts(['post_type' => 'campeao', 'numberposts' => -1]);
foreach($antigos as $p) { wp_delete_post($p->ID, true); }

$data = [
    // --- 2026 ---
    // Mundiais
    ['nome' => 'Rafael Saladini', 'nacao' => 'Brasil 🇧🇷', 'equip' => 'Ozone Enzo 3', 'ano' => 2026, 'tipo' => 'Mundial', 'pos' => 1, 'bio' => 'Lendário voo batendo o recorde de distância e tática da temporada.'],
    ['nome' => 'Pepe Lopez', 'nacao' => 'Espanha 🇪🇸', 'equip' => 'Niviuk Icepeak', 'ano' => 2026, 'tipo' => 'Mundial', 'pos' => 2, 'bio' => 'Vice-campeão colado, dominando as térmicas centrais.'],
    ['nome' => 'Stefan Schmoker', 'nacao' => 'Suíça 🇨🇭', 'equip' => 'Advance Omega', 'ano' => 2026, 'tipo' => 'Mundial', 'pos' => 3, 'bio' => 'Veterano europeu, impecável no planeio.'],
    ['nome' => 'Julien Wirtz', 'nacao' => 'França 🇫🇷', 'equip' => 'Ozone Zeno', 'ano' => 2026, 'tipo' => 'Mundial', 'pos' => 4, 'bio' => 'Ameaçou o pódio nos últimos dois dias de glides longos.'],
    ['nome' => 'Luciano Horn', 'nacao' => 'Brasil 🇧🇷', 'equip' => 'Ozone Enzo', 'ano' => 2026, 'tipo' => 'Mundial', 'pos' => 5, 'bio' => 'Pouso cravado na meta lhe rendeu o 5º lugar global.'],
    // GV / Brasileiro
    ['nome' => 'Erico Oliveira', 'nacao' => 'Brasil 🇧🇷', 'equip' => 'Gin Boomerang', 'ano' => 2026, 'tipo' => 'GV', 'pos' => 1, 'bio' => 'Atleta local dominou os ares da terra natal com perfeição.'],
    ['nome' => 'Rafael Saladini', 'nacao' => 'Brasil 🇧🇷', 'equip' => 'Ozone Enzo 3', 'ano' => 2026, 'tipo' => 'GV', 'pos' => 2, 'bio' => 'Prata fortíssima no circuito local.'],
    ['nome' => 'Marcella Pomarico', 'nacao' => 'Brasil 🇧🇷', 'equip' => 'Ozone Zeno 2', 'ano' => 2026, 'tipo' => 'Brasileiro', 'pos' => 1, 'bio' => 'Dominou o ranking feminino e absoluto nacional neste ano.'],

    // --- 2025 ---
    ['nome' => 'Pepe Lopez', 'nacao' => 'Espanha 🇪🇸', 'equip' => 'Niviuk Icepeak', 'ano' => 2025, 'tipo' => 'Mundial', 'pos' => 1, 'bio' => 'Estratégia espanhola letal. Aproveitou as térmicas de fim de tarde.'],
    ['nome' => 'Rafael Saladini', 'nacao' => 'Brasil 🇧🇷', 'equip' => 'Ozone Enzo 3', 'ano' => 2025, 'tipo' => 'Mundial', 'pos' => 2, 'bio' => 'Vice-campeonato muito disputado ponto a ponto.'],
    ['nome' => 'Luciano Horn', 'nacao' => 'Brasil 🇧🇷', 'equip' => 'Gin Boomerang', 'ano' => 2025, 'tipo' => 'Brasileiro', 'pos' => 1, 'bio' => 'Consistência o levou ao ouro nacional absoluto.'],
    ['nome' => 'Frank Brown', 'nacao' => 'Brasil 🇧🇷', 'equip' => 'Sol Tracer', 'ano' => 2025, 'tipo' => 'GV', 'pos' => 1, 'bio' => 'Conseguiu raspar os picos e cruzar o rio mais rápido que todos.'],

    // --- 2024 ---
    ['nome' => 'Stefan Gruber', 'nacao' => 'Áustria 🇦🇹', 'equip' => 'Nova', 'ano' => 2024, 'tipo' => 'Mundial', 'pos' => 1, 'bio' => 'O austríaco voou magistralmente, isolando-se no pelotão da frente.'],
    ['nome' => 'Rafael Saladini', 'nacao' => 'Brasil 🇧🇷', 'equip' => 'Ozone Enzo 2', 'ano' => 2024, 'tipo' => 'Mundial', 'pos' => 2, 'bio' => 'Sempre consistente, ficou no pódio mais uma vez.'],
    ['nome' => 'Frank Brown', 'nacao' => 'Brasil 🇧🇷', 'equip' => 'Sol Tracer', 'ano' => 2024, 'tipo' => 'GV', 'pos' => 1, 'bio' => 'Bicampeão consecutivo local, o rei de Valadares.'],
    ['nome' => 'Erico Oliveira', 'nacao' => 'Brasil 🇧🇷', 'equip' => 'Gin', 'ano' => 2024, 'tipo' => 'Brasileiro', 'pos' => 1, 'bio' => 'Ouro brasileiro no último segundo de prova.'],

    // --- 2023 ---
    ['nome' => 'Honorato', 'nacao' => 'Brasil 🇧🇷', 'equip' => 'Gin Gliders', 'ano' => 2023, 'tipo' => 'Mundial', 'pos' => 1, 'bio' => 'Surpreendeu a elite europeia vencendo as provas do Mundial!'],
    ['nome' => 'Honorato', 'nacao' => 'Brasil 🇧🇷', 'equip' => 'Gin Gliders', 'ano' => 2023, 'tipo' => 'Brasileiro', 'pos' => 1, 'bio' => 'Duplo Ouro. Venceu o Brasileiro na mesma competição.'],
    ['nome' => 'Rafael Saladini', 'nacao' => 'Brasil 🇧🇷', 'equip' => 'Ozone Enzo', 'ano' => 2023, 'tipo' => 'GV', 'pos' => 1, 'bio' => 'Ouro na etapa GV, salvando pontos cruciais.'],

    // --- 2012 (Histórico) ---
    ['nome' => 'Richard Pethigal', 'nacao' => 'Brasil 🇧🇷', 'equip' => 'Advance', 'ano' => 2012, 'tipo' => 'Mundial', 'pos' => 1, 'bio' => 'Considerado o pai das rotas modernas de Cross Country.'],
    ['nome' => 'Richard Pethigal', 'nacao' => 'Brasil 🇧🇷', 'equip' => 'Advance', 'ano' => 2012, 'tipo' => 'GV', 'pos' => 1, 'bio' => 'Mestre absoluto das rotas antigas, ganhando em casa.'],
    ['nome' => 'Frank Brown', 'nacao' => 'Brasil 🇧🇷', 'equip' => 'Sol Tracer', 'ano' => 2012, 'tipo' => 'Brasileiro', 'pos' => 1, 'bio' => 'Sua juventude e técnica renderam o ouro.'],

    // --- 2005 (Histórico) ---
    ['nome' => 'Carlos Niemeyer', 'nacao' => 'Brasil 🇧🇷', 'equip' => 'Woody Valley', 'ano' => 2005, 'tipo' => 'Mundial', 'pos' => 1, 'bio' => 'Campeão mundial histórico.'],
    ['nome' => 'Pedro Garcia', 'nacao' => 'Brasil 🇧🇷', 'equip' => 'Skywalk', 'ano' => 2005, 'tipo' => 'GV', 'pos' => 1, 'bio' => 'Pioneiro das térmicas difíceis do sudoeste.']
];

echo "Semeando " . count($data) . " atletas ricos...\n";
foreach($data as $d) {
    // Generate post in db
    $pid = wp_insert_post([
        'post_title' => $d['nome'],
        'post_type' => 'campeao',
        'post_status' => 'publish',
        'post_excerpt' => $d['bio']
    ]);
    if ($pid) {
        update_post_meta($pid, 'nacionalidade', $d['nacao']);
        update_post_meta($pid, 'equipamento', $d['equip']);
        update_post_meta($pid, 'ano_campeonato', $d['ano']);
        update_post_meta($pid, 'tipo_torneio', $d['tipo']);
        update_post_meta($pid, 'posicao', $d['pos']);
    }
}
echo "Completo!\n";
