<?php
// seeder_details_2026.php
require_once('wp-load.php');

$champs_2026 = [
    'Mundial' => [
        'nome' => 'Pepe Lopez',
        'etapas' => "1. Colômbia (Roldanillo): 1º lugar - 980 pts\n2. França (Annecy): 3º lugar - 920 pts\n3. Turquia (Kayseri): 1º lugar - 1000 pts\n4. Brasil (G. Valadares): 2º lugar - 950 pts\nTotal do Circuito: 3850 pts (Campeão Absoluto)",
        'equip_completo' => "Vela: Niviuk Icepeak Evox 3 | Selete: Drifter 2 | Reserva: Octopus 16 | Eletrônicos: Naviter Oudie N + Vario Bluetooth"
    ],
    'Brasileiro' => [
        'nome' => 'Marcella Pomarico',
        'conquistas' => "🏆 Ranking Nacional 2026: 4.250 pts\n✅ 1º Lugar em Andradas (MG)\n✅ 2º Lugar em Baixo Guandu (ES)\n✨ Recorde Feminino: 285km de voo cruzado (XC)\n🎯 Média de Golaço: 96% de presença em metas",
        'equip_completo' => "Vela: Ozone Zeno 2 MS | Selete: Woody Valley XR7 | Reserva: Companion SQR | GPS: Flytec Connect 1"
    ],
    'GV' => [
        'nome' => 'Erico Oliveira',
        'voo_log' => "🏁 Feito Histórico - Ibituruna Open 2026\n🛫 Decolagem: 11:45 (Vento S 12km/h)\n☁️ Térmica de Recorde: +5.2 m/s sobre o Rio Doce\n📍 Percurso: Pico -> Alpercata -> Gov. Valadares\n🏁 Meta (Pouso): 16:15 (Meta Oficial)\n⏱️ Tempo Total: 4h 30m de pura técnica",
        'equip_completo' => "Vela: Gin Boomerang 12 | Selete: Gin Genie Race 4 | Reserva: Gin Yeti | Inst: Skytraxx 5.0"
    ]
];

foreach ($champs_2026 as $tipo => $d) {
    $posts = get_posts([
        'post_type' => 'campeao',
        'title' => $d['nome'],
        'meta_query' => [
            ['key' => 'ano_campeonato', 'value' => '2026'],
            ['key' => 'tipo_torneio', 'value' => $tipo]
        ]
    ]);

    if (!empty($posts)) {
        $pid = $posts[0]->ID;
        if (isset($d['etapas'])) update_post_meta($pid, 'detalhes_etapas', $d['etapas']);
        if (isset($d['conquistas'])) update_post_meta($pid, 'detalhes_conquistas', $d['conquistas']);
        if (isset($d['voo_log'])) update_post_meta($pid, 'detalhes_voo_log', $d['voo_log']);
        if (isset($d['equip_completo'])) update_post_meta($pid, 'equipamento_completo', $d['equip_completo']);
        echo "Atualizado: {$d['nome']} ({$tipo})\n";
    } else {
        echo "Aviso: Post para {$d['nome']} ({$tipo}) não encontrado em 2026.\n";
    }
}
