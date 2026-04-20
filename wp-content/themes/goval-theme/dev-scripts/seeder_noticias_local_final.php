<?php
/**
 * seeder_noticias_local_final.php
 * Reportagens longas com imagens LOCAIS geradas por IA.
 */
require_once('wp-load.php');

$antigos = get_posts(['post_type' => 'post', 'numberposts' => -1]);
foreach($antigos as $p) { wp_delete_post($p->ID, true); }

$theme_uri = get_template_directory_uri();
$local_img_path = $theme_uri . '/assets/images/';

$noticias = [
    [
        'title' => 'Governador Valadares: O Epocentro Geológico que Desafia a Gravidade',
        'cat' => 'Reportagem Especial',
        'resumo' => 'Uma investigação profunda sobre como as térmicas do Rio Doce criam o cenário perfeito para os maiores recordes do mundo.',
        'img' => $local_img_path . 'news1.png',
        'content' => '
            <p><strong>A Origem do Fenômeno:</strong> Situada em um vale cercado por montanhas de granito negro, Governador Valadares possui uma configuração geológica que funciona como um radiador gigante. Durante o dia, o solo mineiro absorve a radiação solar intensamente. Ao atingir um ponto crítico, o ar aquecido se desprende do chão em forma de "bolhas térmicas" que sobem em colunas cilíndricas. É nestas colunas que os pilotos entram, espiralando para ganhar altitude, muitas vezes subindo a velocidades superiores a 5 metros por segundo.</p>
            
            <p><strong>A Ciência do Voo Livre:</strong> Diferente de um avião a motor, o paraglider vive da energia da atmosfera. O Pico da Ibituruna, com sua face voltada para o Sol da manhã, é o "gatilho" perfeito. Pilotos de elite vindos da Suíça, Áustria e Japão descrevem Valadares como uma universidade a céu aberto. Eles vêm buscar não apenas troféus, mas a compreensão de como navegar em massas de ar complexas que permitem voos de mais de 100km de distância cruzando o interior do estado rumo ao Espírito Santo.</p>
            
            <p><strong>O Orgulho de uma Nação:</strong> Para o povo valadarense, a Ibituruna é mais que um marco turístico; é um símbolo de força. O resgate da autoestima da cidade passou necessariamente pela valorização do seu céu. Hoje, a infraestrutura de decolagem é uma das melhores do planeta, e o site oficial com telemetria 3D é o selo final de modernidade. Valadares não apenas voa; Valadares lidera o mundo no esporte que mais aproxima o ser humano da liberdade absoluta. Esta é a nossa pátria, este é o nosso céu.</p>
        '
    ],
    [
        'title' => 'Anatomia da Vitória: Por dentro do kit de 20 mil Euros dos Campeões Mundiais',
        'cat' => 'Tecnologia',
        'resumo' => 'Da microeletrônica dos GPS à química dos tecidos ripstop, entenda por que o voo livre é a Fórmula 1 dos céus.',
        'img' => $local_img_path . 'news2.png',
        'content' => '
            <p><strong>Materiais Aeroespaciais:</strong> O que à distância parece apenas um tecido colorido é, na verdade, uma trama de nylon siliconado de alta resistência, pesando menos de 30 gramas por metro quadrado. As linhas que sustentam o piloto têm a espessura de um fio de pesca, mas são feitas de Kevlar e Dyneema, capazes de suportar toneladas de carga. Em Valadares, onde as térmicas são fortes e turbulentas, a estabilidade desses materiais é o que separa um voo tranquilo de uma emergência.</p>
            
            <p><strong>O Cockpit Inteligente:</strong> Esqueça as bússolas antigas. O piloto moderno carrega tablets dedicados e variômetros ultrassensíveis. Equipamentos como o Naviter Oudie utilizam dados de satélite para prever, com precisão de metros, se o piloto conseguirá ou não chegar no próximo ponto de planeio. A integração Bluetooth envia dados em tempo real para as equipes de terra, garantindo que o resgate saiba a localização exata no momento do pouso, mesmo em áreas remotas do interior mineiro.</p>
            
            <p><strong>O Futuro da Segurança:</strong> A tecnologia também salvaguarda a vida. Seletes de alta performance possuem proteção contra impactos espinhais e dois paraquedas de reserva de acionamento rápido. Valadares tem sido o laboratório para testar essas inovações sob o intenso sol tropical, garantindo que a evolução do esporte seja acompanhada por um índice de segurança cada vez maior. O Mundial Ibituruna celebra esta união entre coragem e engenharia de precisão.</p>
        '
    ],
    [
        'title' => 'Dossiê Ibituruna: O Legado de Frank Brown e o Sonho das Novas Gerações',
        'cat' => 'Ícones e Lendas',
        'resumo' => 'Entrevistamos as figuras que transformaram a rampa local no palco mais respeitado das Américas.',
        'img' => $local_img_path . 'news3.png',
        'content' => '
            <p><strong>O Mestre do Quintal:</strong> Frank Brown não apenas voa em Valadares; ele entende o Pico da Ibituruna como ninguém. Com mais de 30 anos de voo, Frank é o recordista que ensinou o mundo a respeitar o vento mineiro. Sua trajetória é marcada pela resiliência e por uma leitura tática de prova que desafia os algoritmos de computador. Ele é a prova de que, no voo livre, o talento humano e a sensibilidade ainda superam a máquina.</p>
            
            <p><strong>A Passagem do Bastão:</strong> Novos nomes como Rafael Saladini e Erico Oliveira já escrevem seus nomes nos livros de recordes, mas sempre citam a base deixada pelos pioneiros. Governador Valadares tornou-se um celeiro de talentos porque soube preservar a história enquanto abraçava a inovação. As escolinhas de voo na base da montanha hoje recebem jovens que sonham em ser os próximos "Reis da Ibituruna", mantendo acesa a chama do esporte que define a cidade.</p>
            
            <p><strong>Um Portal para a Eternidade:</strong> Nosso objetivo ao criar este portal de alta performance é imortalizar esses feitos. Ao registrar cada título e cada telemetria épica, garantimos que as futuras gerações saibam que em 2026, Valadares era o centro do universo aeronáutico esportivo. O site Mundial Ibituruna é o museu digital de um povo que escolheu não ter pés no chão, mas asas no coração.</p>
        '
    ],
    [
        'title' => 'Turismo e Economia: O Voo que Alimenta o Vale do Rio Doce',
        'cat' => 'Cidadania e Desenvolvimento',
        'resumo' => 'Como cada evento mundial injeta milhões na economia local e promove a imagem de Valadares para o exterior.',
        'img' => $local_img_path . 'news4.png',
        'content' => '
            <p><strong>Início: A Virada Econômica:</strong> Governador Valadares descobriu no céu sua principal mina de ouro. A cada temporada, hotéis e restaurantes registram recordes de ocupação, movimentando uma cadeia que vai do comércio de luxo ao pequeno artesão local. O turismo de voo livre é hoje o maior promotor da marca Valadares no exterior, gerando divisas e empregos para milhares de famílias que se especializaram em receber o mundo com a hospitalidade mineira.</p>
            
            <p><strong>Meio: Infraestrutura e Sustentabilidade:</strong> O crescimento do esporte trouxe consigo a responsabilidade ambiental. A preservação da Ibituruna e a manutenção das rampas são prioridades que unem o poder público e os esportistas. O investimento em infraestrutura digital, como o próprio site oficial que agora conta com imagens locais de alta qualidade, é parte de uma estratégia de longo prazo para tornar Valadares o destino #1 de esportes de aventura da América Latina.</p>
            
            <p><strong>Conclusão: Um Futuro de Ouro:</strong> Olhar para o Pico da Ibituruna pontilhado de velas coloridas é ter a certeza de que a cidade encontrou seu caminho. O voo livre não é apenas um hobby; é um motor de desenvolvimento social e econômico que resgatou a dignidade e a autoestima do povo valadarense. Celebramos hoje essa trajetória de sucesso, com a certeza de que os melhores voos ainda estão por vir. Valadares voa alto, e o mundo voa conosco.</p>
        '
    ],
    [
        'title' => 'A Mágica das Máquinas Voadoras: O Desafio de Flutuar sobre as Montanhas',
        'cat' => 'Espetáculo Visual',
        'resumo' => 'Uma galeria de fotos e relatos sobre a beleza plástica da competição mais colorida do Brasil.',
        'img' => $local_img_path . 'news5.png',
        'content' => "
            <p><strong>O Palco Flutuante:</strong> Não há nada na Terra que se compare ao visual de 150 paragliders térmicos juntos, subindo em uma espiral perfeita sobre o Rio Doce. O contraste das cores contra o azul profundo do céu mineiro cria uma ópera visual que emociona até os observadores mais experientes. Este espetáculo atrai fotógrafos e cinegrafistas de todos os continentes, buscando capturar a essência da liberdade.</p>
            
            <p><strong>A Técnica por trás da Poesia:</strong> Mas não se engane pela beleza calma; cada piloto ali dentro está em uma batalha tática. Manter-se no 'core' da térmica exige braço firme e concentração total. Qualquer erro de julgamento pode significar o fim da prova e um pouso prematuro longe da meta. É essa mistura de perigo, técnica e estética que faz do voo livre o esporte mais bonito do mundo.</p>
            <p><strong>Finalizando o Voo:</strong> Ao final do dia, as histórias se misturam nos bares da cidade, onde cada piloto relata seu 'golaço' ou seu 'pouso no mato'. É essa camaradagem que torna Valadares especial. Nosso portal capta essa alma, trazendo imagens locais e relatos reais de quem vive o sonho de Ícaro todos os dias.</p>
        "
    ],
];

// Completar 10 com as imagens restantes gradualmente
for ($i = 6; $i <= 10; $i++) {
    $img_idx = ($i > 6) ? ($i % 6 + 1) : $i;
    $noticias[] = [
        'title' => "Reportagem Especial #$i: Os Bastidores da Logística de Resgate no Campeonato",
        'cat' => 'Logística de Elite',
        'resumo' => 'Saiba como os pilotos são resgatados a centenas de quilômetros de distância em tempo recorde.',
        'img' => $local_img_path . "news$img_idx.png",
        'content' => "
            <p><strong>O Desafio:</strong> Voar 150km é a parte fácil; o difícil é voltar para casa. <strong>A Estratégia:</strong> Equipes de resgate monitoram o sinal de satélite de cada atleta. Assim que o pouso é confirmado em uma fazenda remota, as caminhonetes de apoio entram em ação, percorrendo estradas de terra para buscar o campeão. <strong>Conclusão:</strong> Sem essa infraestrutura de apoio, que envolve rádios, GPS e motoristas experientes, os grandes recordes de Valadares não seriam possíveis. O Mundial Ibituruna é um esforço coletivo de terra e ar.</p>
        "
    ];
}

echo "Iniciando Semeadura Local e Journalística...\n";
foreach($noticias as $n) {
    $pid = wp_insert_post([
        'post_title' => $n['title'],
        'post_type' => 'post',
        'post_status' => 'publish',
        'post_excerpt' => $n['resumo'],
        'post_content' => $n['content']
    ]);
    if ($pid) {
        update_post_meta($pid, 'categoria_fake', $n['cat']);
        update_post_meta($pid, 'foto_capa', $n['img']);
    }
}
echo "TUDO PRONTO E LOCALIZADO!\n";
