<?php
// seeder_noticias.php
$antigos = get_posts(['post_type' => 'post', 'numberposts' => -1]);
foreach($antigos as $p) { wp_delete_post($p->ID, true); }

$noticias = [
    [
        'title' => 'Governador Valadares: O Resgate do Orgulho e o Retorno à Elite do Voo Livre Mundial',
        'cat' => 'Destino Gv',
        'resumo' => 'Mais de 30 anos após sediar sua primeira grande competição, o Pico da Ibituruna é reafirmado como o maior palco esportivo das Américas para o parapente e asa delta.',
        'img' => 'https://images.unsplash.com/photo-1518331165337-25e227568541?w=1200&q=80',
        'content' => '<h2>Valadares Nas Alturas</h2><p>Governador Valadares, com suas térmicas perfeitas e a imponente figura do Pico da Ibituruna, sempre foi considerada o "Havaí do Voo Livre". Hoje, o foco não é apenas na competição, mas no forte resgate cívico de nossa autoestima. O valadarense sabe que dividir os ares da nossa terra com a elite da Europa, Japão e Américas é prova viva de que a nossa geografia é abençoada.</p>'
    ],
    [
        'title' => 'O Poder do Equipamento: Como os Gliders Modernos Cortam o Céu Mineiro a Mais de 70km/h',
        'cat' => 'Tecnologia',
        'resumo' => 'Os modelos de Parapente "classe aberta" evoluíram assustadoramente. Analisamos o Ozone Enzo 3 e a precisão do Niviuk Icepeak nos campeonatos Mundiais.',
        'img' => 'https://plus.unsplash.com/premium_photo-1661957449557-4bada4f08e8b?w=1200&q=80',
        'content' => '<h2>Tecnologia na Aerodinâmica</h2><p>Antigamente, as "velas" sofriam com longos planeios e turbulências instáveis. Hoje, os equipamentos trazem reforço interno estrutural e painéis mais estreitos que aceleram muito. No campeonato de GV, pilotos têm ultrapassado marcas de voos cruzando centenas de quilômetros ao pousarem até no estado vizinho, Espírito Santo.</p>'
    ],
    [
        'title' => 'Do Pico da Ibituruna ao Topo do Pódio: Especial Frank Brown e a Dinastia do Voo Local',
        'cat' => 'Lendas Brasileiras',
        'resumo' => 'Frank Brown se consolidou como uma das maiores lendas dos céus, conquistando 7 ouros marcantes nos campeonatos que saem de Governador Valadares.',
        'img' => 'https://images.unsplash.com/photo-1601243161405-b072d621b0dc?w=1200&q=80',
        'content' => '<h2>Frank Brown e as Conquistas</h2><p>Voar no seu "quintal" sempre traz vantagens, mas o que pilotos como Frank e Rafael Saladini mostram em Valadares transcende o mapeamento aéreo: eles possuem instintos afiados onde a maioria dos europeus não conseguem acompanhar. A identificação fluida das correntes térmicas debaixo do forte sol mineiro se transformou em uma verdadeira arte de navegação e vitória nacional.</p>'
    ],
    [
        'title' => 'Turismo em Governador Valadares Bate Recordes Graças ao Circuito Mundial',
        'cat' => 'Economia',
        'resumo' => 'O esporte impulsiona a economia local! Hotéis e pousadas registram índices globais de lotação no auge da temporada de campeonatos na Ibituruna.',
        'img' => 'https://images.unsplash.com/photo-1549646549-3837ad06a090?w=1200&q=80',
        'content' => '<h2>Economia Fortalecida</h2><p>Quando começa a etapa, o centro da cidade e as rampas superlotam de famílias, comerciantes locais e esportistas. Tudo isso fortalece não só nossa auto-estima, mas movimenta milhares e dezenas de milhões de reais com serviços locais, aluguel, guias e infraestrura gastronômica.</p>'
    ],
    [
        'title' => 'O Guia Completo para Iniciantes no Parapente: Como a Ibituruna é o Sonho de Todo Aprendiz',
        'cat' => 'Educação',
        'resumo' => 'Quer sentir os ares mas não sabe por onde começar? Valadares possui centros de excelência no treinamento esportivo seguro.',
        'img' => 'https://images.unsplash.com/photo-1463124578508-301ad1d1d86d?w=1200&q=80',
        'content' => '<h2>Começando a Voar</h2><p>Diferente do que parece, voar não exige um preparo de fisiculturista. Exige mente sã, interpretação do vento e muito respeito pela natureza. Inúmeras escolinhas homologadas na nossa pátria amada preparam você para subir de carona com os experientes e dar o seu primeiro lifting (puxada) numa decolagem da rampa de Valadares!</p>'
    ],
    [
        'title' => 'O Desafio do Pouso Preciso: Um Minuto que Decide um Título Internacional',
        'cat' => 'Tática e Competição',
        'resumo' => 'Saiba como os europeus e brasileiros lidam não apenas com o cross-country, mas com a temível linha de chegada em alvos mínimos e terrenos secos.',
        'img' => 'https://images.unsplash.com/photo-1524240751-bbbbce8fa3fe?w=1200&q=80',
        'content' => '<h2>Pouso Tático</h2><p>Muitas vezes, a corrida de longo alcance é acompanhada por pousos em "cylinder ranges" no GPS, e quem toca a linha final virtual no sistema comemora no ar. Hoje, uma fração de minutos dita quem é Ouro e quem perde tudo.</p>'
    ],
    [
        'title' => 'A Fúria das Térmicas: Como os Pilotos Encontram Elevadores Invisíveis de Vento',
        'cat' => 'Natureza e Clima',
        'resumo' => 'Desvendamos o que os competidores enxergam no chão de Valadares que permite a eles subirem três quilômetros no céu em espiral, movidos só pelo calor.',
        'img' => 'https://images.unsplash.com/photo-1527581561633-1469e8b3e839?w=1200&q=80',
        'content' => '<h2>O Segredo Visível</h2><p>Eles não usam motores! Com urubus pelo céu ou formatos de nuvem lenticulares se formando no horizonte, o piloto de voo livre decifra o que acontece embaixo: Se existe asfalto ou plantações escuras torrando de calor em uma fazenda a beira do rio, aquilo ali é um pilar maciço de vento quente. E quem entra primeiro, sobe como um foguete!</p>'
    ],
    [
        'title' => 'Superação e Autoestima: GV se Firmeza Contra Preconceitos na Excelência do Voo',
        'cat' => 'Cidadania',
        'resumo' => 'Uma cidade conhecida também pelos desafios de imigração e seca se volta para sua própria joia verde e amarela e consolida os laços cívicos!',
        'img' => 'https://images.unsplash.com/photo-1574786438084-257de9aab7ca?w=1200&q=80',
        'content' => '<h2>Joia de Minas Gerais</h2><p>Cada novo campeão do circuito é um selo provando que a Ibituruna é inigualável. Essa é uma reflexão coletiva: O amor por si mesmo (autoestima) passa também em reconhecer quem somos e para que o solo e o céu de Governador Valadares foram feitos!</p>'
    ],
    [
        'title' => 'O Campeão Mundial em Casa: Saladini e o Bicampeonato',
        'cat' => 'Atletas de Elite',
        'resumo' => 'A trajetória recente do brasileiro Rafael Saladini dominando pódios no exterior e também na sede principal amada.',
        'img' => 'https://images.unsplash.com/photo-1516246479703-a4e98f02ae03?w=1200&q=80',
        'content' => '<h2>Bicampeão Heroico</h2><p>O foco em voos épicos de longa distância é visível. Saladini reescreveu muitas vezes as rotas longas quebrando recordes espetaculares saindo de Governador Valadares na esteira dos bons ventos que cruzam as fronteiras do nordeste e centro-oeste do Brasil.</p>'
    ],
    [
        'title' => 'Telemetria e Inovação 3D Chegam ao Plataforma Oficial do Campeonato de Governador Valadares',
        'cat' => 'Esportes Eletrônicos',
        'resumo' => 'Agora você pode ver as exatas manobras dos seus ídolos diretamente aqui no site. Nosso mapa tridimensional permite explorar cada decisão tomada na prova.',
        'img' => 'https://images.unsplash.com/photo-1698223631745-f0980aa8dbfb?w=1200&q=80',
        'content' => '<h2>Modernização do Voo</h2><p>Com tecnologias unindo bancos de dados geoespaciais e WebGL avançado, a diretoria agora dispõe de um site interativo que simula as rodadas e engloba absolutamente tudo: O portal mundial é uma imersão virtual inovadora para qualquer fã presenciar de camarote.</p>'
    ]
];

echo "Injetando 10 Artigos Editoriais Profissionais...\n";
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
echo "Tudo PRONTO!\n";
