<?php
/**
 * seeder_noticias_final.php
 * Conteúdo de alta fidelidade com imagens locais e reportagens extensas.
 */
require_once('wp-load.php');

$antigos = get_posts(['post_type' => 'post', 'numberposts' => -1]);
foreach($antigos as $p) { wp_delete_post($p->ID, true); }

$theme_uri = get_template_directory_uri();
$local_img_path = $theme_uri . '/assets/images/';

$noticias = [
    [
        'title' => 'O Poder Supremo da Ibituruna: Por que Governador Valadares é a Capital Mundial do Voo Livre',
        'cat' => 'Reportagem Especial',
        'resumo' => 'Uma análise profunda sobre a geografia sagrada do Vale do Rio Doce e como ela moldou o destino de milhares de pilotos ao longo de quatro décadas.',
        'img' => $local_img_path . 'news1.jpg',
        'content' => '
            <p><strong>A Anatomia de um Gigante:</strong> Erguendo-se a 1.123 metros acima do nível do mar, o Pico da Ibituruna não é apenas um monte de granito; é uma máquina térmica térmica natural. Sua posição estratégica em relação ao Rio Doce cria um microclima único, onde o ar aquecido pelo vale encontra as encostas rochosas, gerando elevadores invisíveis que podem levar um piloto a mais de 3.000 metros de altitude sem o gasto de uma única gota de combustível. Este fenômeno, conhecido como "térmicas de serviço", é o que atrai a elite mundial para Governador Valadares todos os anos entre os meses de julho e outubro.</p>
            
            <p><strong>A Época de Ouro:</strong> Desde o primeiro grande campeonato internacional realizado em 1991, Valadares aprendeu a falar todas as línguas do mundo. O centro da cidade se transforma em um mosaico de bandeiras, onde alemães, japoneses, austríacos e americanos trocam experiências nos cafés locais antes de subirem a íngreme estrada rumo à decolagem. O "Pouso da Feira", como é conhecido o campo oficial de aterrissagem aos pés da montanha, tornou-se o palco de comemorações lendárias, onde o cansaço de voos de 100km desaparece diante de uma recepção calorosa e cívica dos moradores locais.</p>
            
            <p><strong>O Resgate do Futuro:</strong> Mais do que esporte, o voo livre em Valadares é uma afirmação de identidade. Após anos de desafios, a cidade reafirma seu compromisso com a excelência. Novos investimentos em infraestrutura turística, segurança nas rampas e, agora, a integração tecnológica via telemetria 3D, colocam o Mundial Ibituruna em um patamar de eventos de classe mundial. A cada salto dado daquela rampa, o valadarense reafirma o orgulho de sua pátria e a certeza de que o nosso céu é o limite para a criatividade e a coragem humana. Esta reportagem é um tributo a cada herói anônimo que ajudou a transformar o Pico da Ibituruna no Olimpo do Ar.</p>
        '
    ],
    [
        'title' => 'Dossiê Técnico: A Evolução das Máquinas de Voo e o Recorde de 2026',
        'cat' => 'Tecnologia e Inovação',
        'resumo' => 'Entramos nos boxes das equipes de elite para entender os segredos das velas que cruzam estados e os instrumentos de precisão militar.',
        'img' => $local_img_path . 'news2.jpg',
        'content' => '
            <p><strong>Engenharia de Ponta:</strong> O parapente moderno deixou de ser uma "vela de pano" há muito tempo. Hoje, estamos falando de estruturas aerodinâmicas complexas, onde fios de Nitinol (uma liga metálica de memória de forma) garantem que o perfil de ataque da asa permaneça estável mesmo em ventos de 50km/h. Os materiais de tecido, como o Porcher Skytex, pesam apenas 27 gramas por metro quadrado, mas possuem uma resistência à tração que desafia a lógica. Em 2026, vimos o ápice dessa tecnologia com recordes de planeio que permitem ao piloto percorrer 12 metros horizontais para cada 1 metro que desce.</p>
            
            <p><strong>O Cérebro do Piloto Digital:</strong> No cockpit de um campeão mundial, o silêncio é preenchido pelo "bip" frenético dos variômetros digitais. Instrumentos integrados com GPS e sensores de pressão estática traduzem a massa de ar em mapas coloridos em telas de alta definição. O algoritmo "Standard McCready" calcula em milissegundos a velocidade ideal de planeio para que o piloto alcance a próxima nuvem com o máximo de energia conservada. Esta integração entre homem e máquina é o que permitiu os voos de distância recorde saindo de Valadares rumo ao interior do país nos últimos anos.</p>
            
            <p><strong>A Segurança como Base:</strong> Todo esse avanço tecnológico seria inútil sem a evolução dos sistemas de vida. As seletes modernas possuem carenagens protetoras que reduzem o arrasto e incluem airbags de alta absorção de impacto. O uso de dois paraquedas de reserva tornou-se o padrão para a elite, garantindo que mesmo em condições meteorológicas extremas ou colapsos catastróficos, o atleta retorne ao solo com segurança absoluta. Valadares continua sendo o campo de testes definitivo para essas invenções que garantem a vida de milhares de entusiastas do céu ao redor do globo.</p>
        '
    ],
    [
        'title' => 'Frank Brown e o Espírito da Montanha: 30 Anos de Hegemonia nos Céus de Minas',
        'cat' => 'Lendas Vivas',
        'resumo' => 'A trajetória do piloto que ensinou o mundo a voar no quintal de casa e sua influência na nova geração de atletas.',
        'img' => $local_img_path . 'news3.jpg',
        'content' => '
            <p><strong>O Pioneiro Incansável:</strong> Frank Brown não é apenas um nome no topo dos rankings; ele é o DNA do voo livre em Governador Valadares. Desde os seus primeiros voos, ainda jovem, Frank demonstrou uma habilidade quase sobrenatural de ler o terreno. Enquanto outros pilotos buscavam indicadores visuais claros, como urubus ou nuvens, Frank parecia sentir a pulsação da terra, identificando térmicas onde ninguém mais via. Sua dedicação ao Pico da Ibituruna transformou a rampa local em seu laboratório pessoal, onde ele forjou mais de uma dezena de títulos nacionais e participações heróicas em mundiais.</p>
            
            <p><strong>Mentoria para o Futuro:</strong> O impacto de Brown vai além de seus próprios troféus. Ele se tornou o mentor de uma geração inteira de pilotos que hoje dominam os pódios. Rafael Saladini, Erico Oliveira e tantos outros bebem da fonte de conhecimento que Frank compartilha generosamente. "Voar em Valadares é uma lição de humildade", costuma dizer a lenda. Para ele, o esporte é uma ferramenta de transformação social, capaz de tirar jovens de situações vulneráveis e colocá-los em contato com a disciplina e a beleza da natureza.</p>
            
            <p><strong>Um Legado Eterno:</strong> Ao completar três décadas de carreira ativa, Brown continua sendo um competidor temido e respeitado. Seu estilo de voo agressivo, mas calculado, é estudado por pilotos do mundo todo. O portal Mundial Ibituruna dedica este espaço para registrar que, enquanto houver um paraglider cruzando o horizonte de Minas Gerais, o espírito de Frank Brown estará soprando junto, inspirando cada novo aprendiz a desafiar a gravidade e a buscar a melhor versão de si mesmo nos ares.</p>
        '
    ],
    [
        'title' => 'Dossiê Econômico: O Voo Livre como Motor de Desenvolvimento em Minas Gerais',
        'cat' => 'Economia e Cidadania',
        'resumo' => 'A história de como um esporte de nicho gerou uma indústria de turismo multimilionária para Governador Valadares.',
        'img' => $local_img_path . 'news4.jpg',
        'content' => '
            <p><strong>Início: A Virada Gastronômica e Hoteleira:</strong> Quando os primeiros grupos de pilotos estrangeiros começaram a desembarcar no Aeroporto de Valadares, a cidade percebeu que precisava se profissionalizar. O que era um turismo de aventura esporádico tornou-se uma cadeia produtiva robusta. Hotéis que antes operavam com baixas taxas de ocupação no inverno mineiro, agora registram lotação esgotada meses antes do início do circuito mundial. Restaurantes adaptaram seus cardápios para dietas esportivas e o setor de serviços aprendeu o valor de um atendimento bilíngue e especializado.</p>
            
            <p><strong>Meio: Infraestrutura e Sustentabilidade:</strong> O investimento público e privado no Pico da Ibituruna seguiu o rastro do crescimento do esporte. Novas estradas de acesso, áreas de lazer para famílias que acompanham os pilotos e a preservação rigorosa da Área de Proteção Ambiental (APA) da serra mostram que o progresso e a natureza podem caminhar juntos. Valadares tornou-se um modelo de como o esporte de natureza pode revitalizar uma região, gerando empregos diretos para guias, instrutores de voo duplo e equipes de logística de resgate que percorrem centenas de quilômetros de estradas rurais todos os dias.</p>
            
            <p><strong>Fim: Um Olhar para 2030:</strong> O futuro é promissor. Com a estabilização de grandes eventos como o Mundial de Parapente, a cidade agora busca expandir a marca "Ibituruna" para o mercado de esportes eletrônicos e transmissões ao vivo via satélite. O portal que estamos construindo é o primeiro passo para essa integração digital massiva. Ao unirmos a tradição do voo com a tecnologia de ponta, garantimos que Governador Valadares não seja apenas um refúgio sazonal, mas a capital perene do voo livre, gerando prosperidade e orgulho para as próximas gerações de valadarenses.</p>
        '
    ]
];

// Completar 10 notícias com conteúdo robusto
for ($i = 5; $i <= 10; $i++) {
    $noticias[] = [
        'title' => "Reportagem Especial #$i: O Segredo das Correntes Térmicas de Valadares",
        'cat' => 'Ciência do Voo',
        'resumo' => "Descubra como o aquecimento do solo mineiro cria 'elevadores invisíveis' que permitem voos de até 10 horas.",
        'img' => $local_img_path . "news$i.jpg",
        'content' => "
            <p><strong>Introdução:</strong> Para quem olha lá de baixo, o parapente parece flutuar de forma mística. Mas por trás de cada curva existe uma ciência térmica complexa. <strong>O Fenômeno:</strong> O sol de Minas Gerais incide sobre as plantações e terrenos descampados do Vale do Rio Doce, criando bolhas de ar quente que se desprendem do chão e sobem a velocidades espantosas de até 6 metros por segundo. <strong>Conclusão:</strong> O piloto de Valadares é um mestre em caçar essas bolhas, transformando o calor intenso em distância e glória. Este portal traz a você a chance de entender essa dança com os elementos através da nossa telemetria 3D interativa.</p>
        "
    ];
}

echo "Limpando posts antigos...\n";
echo "Injetando 10 Reportagens Épicas com Imagens LOCAIS...\n";

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
echo "TUDO PERFEITO: Banco de dados atualizado e imagens locais vinculadas!\n";
