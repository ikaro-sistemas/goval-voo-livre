<?php
/* Template Name: Mapa 3D */
get_header();

// Gerador dinâmico de 20 percursos de voo realistas em formato [Lon, Lat, Alt, Info]
$atletas = [];
$nomes = [
    "Erico Oliveira", "Rafael Saladini", "Caio Buzzarello", "Luciano Horn", "Stephan Schmoker",
    "Frank Brown", "Honorato", "Pepe Lopez", "Marcella Pomarico", "Julien Wirtz",
    "Gilberto Teles", "Marcelo Pietro", "Cristiano Ricci", "Thiago Candiogon", "Túlio Subirá",
    "Stephan Gruber", "Pedro Garcia", "Carlos Niemeyer", "André Fleury", "Richard Pethigal"
];

for ($i = 0; $i < 20; $i++) {
    // Fator de aleatoriedade para alterar a rota sem perder a lógica
    $seed = ($i * 0.005) - 0.05; 
    $seed_alt = $i * 35; // Diferenças de altitude
    
    // Percurso Geográfico (Governador Valadares -> Região Norte/Leste)
    $path = [
        [-41.9442, -18.8872, 1123, "Decolagem - Pico Ibituruna (Vel: 0 km/h)"],
        [-41.9390 + ($seed/2), -18.8800 + $seed, 1500 + $seed_alt, "Térmica Inicial - Vento: 15km/h NE"],
        [-41.9200 + $seed, -18.8600 - $seed, 2100 + $seed_alt, "Base da Nuvem - 2.100m - Max Climb"],
        [-41.8800 + $seed, -18.8200 + $seed, 1800 + ($seed_alt/2), "Transição sobre a cidade - Vel: 45 km/h"],
        [-41.8300 + $seed, -18.7800 - $seed, 1200 + $seed_alt, "Térmica Secundária - Ganhando elevação"],
        [-41.7700 + $seed, -18.7500 + $seed, 250, "Aproximação Final - Glide Ratio: 9.5"],
        [-41.7500 + $seed, -18.7300 + $seed, 160, "Pouso - Área de Resgate Alcançada"]
    ];
    
    $atletas[] = [
        $nomes[$i],      // [0] Nome
        "Brasil",        // [1] País (genérico)
        "3h 15m",        // [2] Duração
        (2100 + $seed_alt) . "m", // [3] Altitude Máx
        "Equipamento Pro",// [4] Glider
        $path            // [5] Array de coordenadas e Infos [Lon, Lat, Alt, Texto]
    ];
}

$json_atletas = json_encode($atletas);
$icon_pino = esc_url(get_template_directory_uri() . '/assets/img/pin.png'); // fallbacks visuais
?>

<div class="mapa-container" style="display: flex; height: 85vh;">
    <!-- Sidebar de Controles -->
    <div class="sidebar" style="width: 360px; background: white; padding: 25px; border-right: 2px solid #ccc; overflow-y: auto; box-shadow: 2px 0 10px rgba(0,0,0,0.05); z-index: 10;">
        <h2 style="color: var(--vale-green); font-size: 1.6em; margin: 0 0 5px 0;">Google Earth 3D Air</h2>
        <p style="font-size: 0.95em; color: #555; margin-bottom: 25px;">Simulador de Voo Nativo</p>
        
        <label style="font-weight:bold; color:var(--vale-dark);">Comparar Competidores Reais:</label>
        
        <div style="margin-top: 10px;">
            <label style="font-size:0.9em; color:#007A53;"><b>Piloto 1 (Traçado Verde):</b></label>
            <select id="piloto1" style="width:100%; padding: 10px; margin-bottom: 10px; border-radius:4px; border: 1px solid #ccc; background:#e8f4ef;">
                <?php foreach($atletas as $idx => $a): ?>
                    <option value="<?php echo $idx; ?>"><?php echo esc_html($a[0]); ?> - Max: <?php echo esc_html($a[3]); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div style="margin-top: 10px;">
            <label style="font-size:0.9em; color:#d99500;"><b>Piloto 2 (Traçado Ouro):</b></label>
            <select id="piloto2" style="width:100%; padding: 10px; margin-bottom: 20px; border-radius:4px; border: 1px solid #ccc; background:#fff8e5;">
                <?php foreach($atletas as $idx => $a): ?>
                    <option value="<?php echo $idx; ?>" <?php if($idx==1) echo 'selected'; ?>><?php echo esc_html($a[0]); ?> - Max: <?php echo esc_html($a[3]); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <button onclick="iniciarReplay()" style="width: 100%; padding: 15px; background: var(--vale-green); color: white; border: none; font-weight: bold; cursor: pointer; border-radius: 6px; box-shadow: 0 4px 10px rgba(0,122,83,0.3); transition: background 0.3s; font-size: 1.1em;">RENDERIZAR NUVENS</button>
        
        <div id="painel_info" style="display: none; margin-top: 25px; padding: 20px; background: #fff; border-radius: 8px; border-top: 4px solid var(--vale-gold); box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <h3 style="margin-top: 0; color: var(--vale-dark); font-size: 1.1em; border-bottom: 2px solid #eee; padding-bottom: 5px;">📡 Dados do Trilho do Voo</h3>
            <div id="detalhe_ponto" style="font-size: 1.1em; color: #444; line-height: 1.6; min-height: 50px;">
                <em>Passe o mouse por cima das bolinhas na linha para ler a telemetria do ponto.</em>
            </div>
            
            <div style="margin-top: 15px; font-size: 0.85em; color: #666; background: #fdfdfd; padding: 15px; border-radius: 4px; border: 1px solid #eee;">
                <strong>🎮 Controles da Câmera:</strong><br>
                - Para girar e ver o voo de lado: <strong>Segure CTRL + Clique do Mouse e arraste</strong>.<br>
                - Para aproximar use a Roda do Mouse.
            </div>
        </div>
    </div>

    <!-- Container do Mapa -->
    <div id="mapContainer" style="flex-grow: 1; position: relative; background:#000;"></div>
</div>

<!-- Maplibre GL JS -->
<link href="https://unpkg.com/maplibre-gl@3.6.2/dist/maplibre-gl.css" rel="stylesheet" />
<script src="https://unpkg.com/maplibre-gl@3.6.2/dist/maplibre-gl.js"></script>

<!-- DECK.GL - Biblioteca responsável por desenhar malhas e linhas "suspensas no ar" com 3D real -->
<script src="https://unpkg.com/deck.gl@8.9.0/dist.min.js"></script>

<script>
    const dbAtletas = <?php echo $json_atletas; ?>;
    
    // Inicia o Maplibre com Google Satellite e AWS Terrain
    const map = new maplibregl.Map({
        container: 'mapContainer',
        style: {
            version: 8,
            sources: {
                'google-satellite': {
                    type: 'raster',
                    tiles: ['https://mt1.google.com/vt/lyrs=y&x={x}&y={y}&z={z}'], // Google Earth
                    tileSize: 256,
                    maxzoom: 20
                },
                'aws-terrain': {
                    type: 'raster-dem',
                    tiles: ['https://s3.amazonaws.com/elevation-tiles-prod/terrarium/{z}/{x}/{y}.png'], // Terreno AWS 3D
                    encoding: 'terrarium',
                    tileSize: 256,
                    maxzoom: 14
                }
            },
            layers: [
                {
                    id: 'satellite-layer',
                    type: 'raster',
                    source: 'google-satellite'
                }
            ]
        },
        center: [-41.9300, -18.8800], 
        zoom: 12.5,
        pitch: 65,     // Visão inclinada para ver montanhas de lado
        bearing: 45    // Girado para focar no relevo
    });

    // Controles visuais no mapa
    map.addControl(new maplibregl.NavigationControl({ visualizePitch: true }), 'top-right');

    map.on('load', () => {
        // Habilita as Montanhas!
        map.setTerrain({ source: 'aws-terrain', exaggeration: 1.5 });
    });

    // Inicia o Overlay do Deck.GL que vai cuidar de desenhar coisas "Flutuando no Céu"
    const deckbox = new deck.MapboxOverlay({
        interleaved: true,
        layers: []
    });
    map.addControl(deckbox);

    function iniciarReplay() {
        document.getElementById('painel_info').style.display = 'block';

        const p1_idx = document.getElementById('piloto1').value;
        const p2_idx = document.getElementById('piloto2').value;
        const piloto1 = dbAtletas[p1_idx];
        const piloto2 = dbAtletas[p2_idx];

        // Montar arrays de coordenadas brutas para a linha (Lon, Lat, Alt)
        const path1 = piloto1[5].map(pt => [pt[0], pt[1], pt[2]]);
        const path2 = piloto2[5].map(pt => [pt[0], pt[1], pt[2]]);

        // Montar arranjo de pontos (esferas) e atrelá-las aos textos informativos de cada ponto
        let checkPoints = [];
        piloto1[5].forEach((pt, index) => {
            checkPoints.push({ position: [pt[0], pt[1], pt[2]], color: [0, 160, 100], info: pt[3], piloto: piloto1[0], step: index+1 });
        });
        piloto2[5].forEach((pt, index) => {
            checkPoints.push({ position: [pt[0], pt[1], pt[2]], color: [255, 200, 0], info: pt[3], piloto: piloto2[0], step: index+1 });
        });

        // Camada 1: A Linha do Voo flutuando
        const layerLinhas = new deck.PathLayer({
            id: 'linhas-voo',
            data: [
                { path: path1, color: [0, 122, 83] },     // Verde Vale
                { path: path2, color: [255, 184, 28] }    // Amarelo/Dourado Vale
            ],
            getWidth: 20,
            widthUnits: 'meters', // Usa metros para ficar realista no mundo
            getColor: d => d.color,
            getZ: d => d[2], // <== ISTO ELEVA A LINHA PARA O CÉU (ALÉM DO TERRENO)
            billboard: true // Garante visualização suave
        });

        // Camada 2: Bolinhas iterativas (Waypoints) flutuando no espaço do piloto
        const layerPontos = new deck.ScatterplotLayer({
            id: 'pontos-voo',
            data: checkPoints,
            getPosition: d => d.position,
            getFillColor: d => d.color,
            getRadius: 80, // Raio em metros
            pickable: true, // Habilita o clique/Hover
            onHover: ({object, x, y}) => {
                const el = document.getElementById('detalhe_ponto');
                if (object) {
                    el.innerHTML = `<span style="background:#e90000;color:white;padding:3px 6px;border-radius:3px;font-size:0.8em;font-weight:bold;">${object.piloto}</span><br><strong>Passo ${object.step}:</strong> ${object.info}<br><span style="color:#007A53;">Altitude MSL Atual: ${object.position[2]} metros</span>`;
                    map.getCanvas().style.cursor = 'crosshair';
                } else {
                    el.innerHTML = `<em>Passe o mouse por cima das bolinhas na linha aérea para ler a telemetria do ponto.</em>`;
                    map.getCanvas().style.cursor = '';
                }
            }
        });

        // Joga as camadas flutuantes pro mapa
        deckbox.setProps({
            layers: [layerLinhas, layerPontos]
        });

        // Movimenta a câmera com uma visão panorâmica lindíssima para englobar as montanhas
        map.flyTo({
            center: [-41.8800, -18.8250],
            zoom: 11.5,
            pitch: 75, // Inclinação fortíssima pra ver os voos debaixo
            bearing: -25,
            duration: 5000
        });
    }
</script>
<?php get_footer(); ?>
