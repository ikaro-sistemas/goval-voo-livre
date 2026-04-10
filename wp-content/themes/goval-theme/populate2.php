<?php
require_once __DIR__ . '/../../../wp-load.php';

// Limpar posts antigos
$existing_posts = get_posts(array('post_type' => array('post', 'campeao'), 'numberposts' => -1));
foreach ($existing_posts as $post) {
    wp_delete_post($post->ID, true);
}

// 1. Criar 10 Notícias reais com Imagens (Mock URLs que funcionam)
$noticias = [
    ['title' => 'Erico Oliveira fatura o ouro na 1ª Etapa do Brasileiro em Valadares', 'img' => 'https://images.unsplash.com/photo-1518331165337-25e227568541?w=600&q=80', 'cat' => 'Competição'],
    ['title' => 'Paragliding World Cup: Valadares Confirmada no Circuito 2026', 'img' => 'https://images.unsplash.com/photo-1549646549-3837ad06a090?w=600&q=80', 'cat' => 'PWC'],
    ['title' => 'Marcella Pomarico domina a categoria feminina e leva o troféu!', 'img' => 'https://images.unsplash.com/photo-1473220464492-452bf0bbdfeb?w=600&q=80', 'cat' => 'Destaque'],
    ['title' => 'Condições Térmicas Perfeitas: Pilotos quebram recordes no Ibituruna', 'img' => 'https://images.unsplash.com/photo-1551698618-1dfe5d97d256?w=600&q=80', 'cat' => 'Condições'],
    ['title' => 'Rafael Saladini e Frank Brown relembram disputas históricas', 'img' => 'https://images.unsplash.com/photo-1516089726217-061033230a10?w=600&q=80', 'cat' => 'História'],
    ['title' => 'Segurança de Voo: Novas regras da CBVL implementadas na etapa', 'img' => 'https://images.unsplash.com/photo-1502444330042-d1a1ddf9bb5b?w=600&q=80', 'cat' => 'Regras'],
    ['title' => 'A invasão estrangeira: Europeus lotam hotéis na temporada de voo', 'img' => 'https://images.unsplash.com/photo-1522204523234-8729aa6e3d5f?w=600&q=80', 'cat' => 'Turismo'],
    ['title' => 'Prefeitura anuncia novos investimentos na Rampa do Pico', 'img' => 'https://upload.wikimedia.org/wikipedia/commons/e/e0/Pico_da_Ibituruna.jpg', 'cat' => 'Infraestrutura'],
    ['title' => 'Transmissão ao vivo Bate 100 mil espectadores no Youtube', 'img' => 'https://images.unsplash.com/photo-1560272564-c83b66b1ad12?w=600&q=80', 'cat' => 'Mídia'],
    ['title' => 'Resultados Completos da Categoria Serial e Sport divulgados', 'img' => 'https://images.unsplash.com/photo-1506461883276-594a12b11cf3?w=600&q=80', 'cat' => 'Ranking']
];

foreach ($noticias as $n) {
    $pid = wp_insert_post([
        'post_title' => $n['title'],
        'post_content' => 'Conteúdo exclusivo detalhando o ocorrido durante as competições. Pilotos experientes enfrentaram correntes de ar na formação única do Ibituruna, gerando lances espetaculares ao longo de várias horas de voo em transição.',
        'post_status' => 'publish',
        'post_type' => 'post'
    ]);
    if ($pid) {
        add_post_meta($pid, 'foto_capa', $n['img']);
        add_post_meta($pid, 'categoria_fake', $n['cat']);
    }
}

// 2. Campeões (20 Atletas tops, 5 ultimos anos + maiores)
$campeoes_data = [
    // Atual (2026) Brasileiro
    ['name' => 'Erico Oliveira', 'year' => 2026, 'pos' => 1, 'type' => 'Mundial/Brasileiro', 'country' => 'Brasil', 'glider' => 'Ozone Enzo 3', 'bio' => 'Atual campeão e líder absoluto do ranking, demonstrando voo espetacular no Ibituruna.'],
    ['name' => 'Caio Buzzarello', 'year' => 2026, 'pos' => 2, 'type' => 'Mundial/Brasileiro', 'country' => 'Brasil', 'glider' => 'Niviuk Icepeak', 'bio' => 'Vice-campeão disputadíssimo.'],
    ['name' => 'Luciano Horn', 'year' => 2026, 'pos' => 3, 'type' => 'Mundial/Brasileiro', 'country' => 'Brasil', 'glider' => 'Gin Boomerang', 'bio' => 'Mestre das térmicas fracas na transição final.'],
    ['name' => 'Thiago Candiogon', 'year' => 2026, 'pos' => 4, 'type' => 'Mundial/Brasileiro', 'country' => 'Brasil', 'glider' => 'Ozone Enzo', 'bio' => 'Ótimo traçado.'],
    ['name' => 'Túlio Subirá', 'year' => 2026, 'pos' => 5, 'type' => 'Mundial/Brasileiro', 'country' => 'Brasil', 'glider' => 'Skywalk', 'bio' => 'Performance sólida no PWC.'],
    // 2025
    ['name' => 'Rafael Saladini', 'year' => 2025, 'pos' => 1, 'type' => 'PWC/Mundial', 'country' => 'Brasil', 'glider' => 'Ozone Enzo 3', 'bio' => 'Recordista lendário.'],
    ['name' => 'Stephan Gruber', 'year' => 2025, 'pos' => 2, 'type' => 'PWC/Mundial', 'country' => 'Áustria', 'glider' => 'Niviuk', 'bio' => 'Craque estrangeiro nas terras mineiras.'],
    ['name' => 'Cristiano Ricci', 'year' => 2025, 'pos' => 3, 'type' => 'PWC/Mundial', 'country' => 'Brasil', 'glider' => 'Ozone', 'bio' => 'Forte na chegada.'],
    // 2024
    ['name' => 'Frank Brown', 'year' => 2024, 'pos' => 1, 'type' => 'Brasileiro GV', 'country' => 'Brasil', 'glider' => 'Sol Tracer', 'bio' => 'Múltiplo campeão brasileiro e herói do esporte.'],
    ['name' => 'Marcella Pomarico', 'year' => 2024, 'pos' => 1, 'type' => 'Feminino GV', 'country' => 'Brasil', 'glider' => 'Ozone Zeno 2', 'bio' => 'Destruiu todos os recordes femininos em 2024.'],
    ['name' => 'Marcelo Pietro', 'year' => 2024, 'pos' => 2, 'type' => 'Brasileiro GV', 'country' => 'Brasil', 'glider' => 'Niviuk', 'bio' => 'Atleta consistente e tático.'],
    // 2023
    ['name' => 'Honorato', 'year' => 2023, 'pos' => 1, 'type' => 'Mundial', 'country' => 'Brasil', 'glider' => 'Gin Boomerang 12', 'bio' => 'Piloto de acrobacias que dominou no Cross.'],
    ['name' => 'Julien Wirtz', 'year' => 2023, 'pos' => 2, 'type' => 'Mundial', 'country' => 'França', 'glider' => 'Enzo 3', 'bio' => 'Especialista francês nos grandes voos de Valadares.'],
    ['name' => 'Gilberto Teles', 'year' => 2023, 'pos' => 3, 'type' => 'Mundial', 'country' => 'Brasil', 'glider' => 'Skywalk', 'bio' => 'Focado na rota da feira da paz.'],
    // 2022
    ['name' => 'Stefan Schmoker', 'year' => 2022, 'pos' => 1, 'type' => 'PWC', 'country' => 'Suíça', 'glider' => 'Ozone', 'bio' => 'Trouxe sua especialidade suíça para vencer o forte calor do Ibituruna.'],
    ['name' => 'Pepe Lopez', 'year' => 2022, 'pos' => 2, 'type' => 'PWC', 'country' => 'Espanha', 'glider' => 'Niviuk', 'bio' => 'Disputou o caneco na reta final.'],
    // Top Históricos (Anos variados)
    ['name' => 'Richard Pethigal', 'year' => 2012, 'pos' => 1, 'type' => 'Historico Top', 'country' => 'Brasil', 'glider' => 'Advance', 'bio' => 'Considerado um dos pais da rota clássica.'],
    ['name' => 'André Fleury', 'year' => 2008, 'pos' => 1, 'type' => 'Historico Top', 'country' => 'Brasil', 'glider' => 'Sol', 'bio' => 'Maior pontuador da década 00s.'],
    ['name' => 'Carlos Niemeyer', 'year' => 2005, 'pos' => 1, 'type' => 'Historico Top', 'country' => 'Brasil', 'glider' => 'Niviuk', 'bio' => 'Campeonato Mundial histórico.'],
    ['name' => 'Pedro Garcia', 'year' => 2000, 'pos' => 1, 'type' => 'Historico Top', 'country' => 'Brasil', 'glider' => 'Nova', 'bio' => 'Pioneiro das provas avançadas do Ibituruna.']
];

foreach ($campeoes_data as $c) {
    $cpid = wp_insert_post([
        'post_title' => $c['name'],
        'post_content' => $c['bio'],
        'post_status' => 'publish',
        'post_type' => 'campeao'
    ]);
    if ($cpid) {
        add_post_meta($cpid, 'nacionalidade', $c['country']);
        add_post_meta($cpid, 'equipamento', $c['glider']);
        add_post_meta($cpid, 'ano_campeonato', (string)$c['year']);
        add_post_meta($cpid, 'posicao', (string)$c['pos']);
        add_post_meta($cpid, 'tipo_torneio', $c['type']);
    }
}
echo "Notícias e top 20 atletas criados com sucesso!\n";
