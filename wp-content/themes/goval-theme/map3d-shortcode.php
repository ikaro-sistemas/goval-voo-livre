<?php
// ============================================================================
// SHORTCODE: MAPA 3D AVANÇADO (MaplibreGL Nativo - Sem deck.gl)
// Uso: [goval_3d_map]
// Motor: MaplibreGL v3 + GeoJSON Layers + Satellite Tiles + AWS Terrain 3D
// ============================================================================
add_shortcode('goval_3d_map', 'goval_3d_map_shortcode');
function goval_3d_map_shortcode($atts) {
    require_once get_template_directory() . '/map3d-data.php';
    $db_voos = goval_get_flight_database();
    $json_db = json_encode($db_voos, JSON_UNESCAPED_UNICODE);
    $anos    = array_keys($db_voos);
    rsort($anos);
    ob_start(); ?>

    <link href="https://unpkg.com/maplibre-gl@3.6.2/dist/maplibre-gl.css" rel="stylesheet"/>
    <script src="https://unpkg.com/maplibre-gl@3.6.2/dist/maplibre-gl.js"></script>

    <style>
    .gv3d-wrap   { display:flex; height:88vh; min-height:640px; font-family:'Inter',sans-serif; border-radius:12px; overflow:hidden; box-shadow:0 8px 32px rgba(0,0,0,.3); }
    .gv3d-side   { width:350px; min-width:290px; background:#0d1a12; color:#ddd; padding:16px; border-right:2px solid #1a3020; overflow-y:auto; display:flex; flex-direction:column; gap:9px; }
    .gv3d-map    { flex-grow:1; position:relative; }
    .gv3d-side h2{ color:#4ecb80; margin:0; font-size:.9em; text-transform:uppercase; letter-spacing:.5px; }
    .pb          { background:rgba(255,255,255,.05); border-radius:8px; padding:11px; border-left:4px solid #00cc60; }
    .pb.p2       { border-left-color:#FFB81C; }
    .pb label    { font-size:.7em; color:#999; text-transform:uppercase; letter-spacing:1px; display:block; margin-bottom:3px; }
    .pb select   { width:100%; padding:7px; border-radius:6px; border:1px solid #233022; background:#142014; color:#fff; font-size:.85em; margin-top:2px; }
    .sbtn        { padding:12px; background:linear-gradient(135deg,#007A53,#00cc60); color:#fff; border:none; font-weight:700; cursor:pointer; border-radius:8px; font-size:.9em; width:100%; transition:opacity .2s; }
    .sbtn:hover  { opacity:.85; }
    #tele-box    { background:rgba(255,255,255,.04); border-radius:8px; padding:11px; min-height:75px; font-size:.78em; line-height:1.7; color:#bbb; border:1px solid #1a3020; }
    .tl          { color:#4ecb80; font-weight:700; }
    .p-stats     { font-size:.74em; background:rgba(255,255,255,.04); border-radius:8px; padding:9px; display:none; }
    .p-stats h4  { margin:0 0 5px; color:#4ecb80; }
    .p-stats.p2 h4 { color:#FFB81C; }
    .sr          { display:flex; justify-content:space-between; border-bottom:1px solid #1a3020; padding:3px 0; }
    .sr span:last-child { color:#fff; font-weight:500; text-align:right; max-width:60%; }
    .cam-wrap    { display:flex; flex-wrap:wrap; gap:5px; }
    .cbtn        { flex:1; min-width:72px; padding:6px 3px; background:#142014; color:#aaa; border:1px solid #243824; border-radius:6px; cursor:pointer; font-size:.7em; text-align:center; transition:background .2s; }
    .cbtn:hover  { background:#007A53; color:#fff; }
    .clbl        { font-size:.68em; color:#5a9a70; text-transform:uppercase; letter-spacing:1px; margin:2px 0 1px; }
    /* popup MaplibreGL */
    .maplibregl-popup-content { background:#0d1a12; color:#ddd; font-size:.8em; padding:12px 14px; border-radius:8px; border:1px solid #00cc60; max-width:240px; }
    .maplibregl-popup-tip { border-top-color:#0d1a12!important; border-bottom-color:#0d1a12!important; }
    @media(max-width:820px){.gv3d-wrap{flex-direction:column;height:auto}.gv3d-side{width:100%;max-height:46vh}.gv3d-map{height:54vh}}
    </style>

    <div class="gv3d-wrap">
        <!-- ════ SIDEBAR ════ -->
        <div class="gv3d-side">
            <h2>🏔 Telemetria 3D · Ibituruna</h2>

            <div class="pb p1">
                <label>🟢 Piloto 1 + Ano</label>
                <select id="sel-ano1" onchange="atualizarPilotos('1')">
                    <?php foreach($anos as $a): ?><option value="<?= esc_attr($a)?>"><?= esc_html($a)?></option><?php endforeach; ?>
                </select>
                <select id="sel-piloto1" style="margin-top:6px"></select>
            </div>

            <div class="pb p2">
                <label>🟡 Piloto 2 + Ano</label>
                <select id="sel-ano2" onchange="atualizarPilotos('2')">
                    <?php foreach($anos as $a): ?><option value="<?= esc_attr($a)?>" <?php if($a==$anos[1]) echo 'selected'; ?>><?= esc_html($a)?></option><?php endforeach; ?>
                </select>
                <select id="sel-piloto2" style="margin-top:6px"></select>
            </div>

            <button class="sbtn" onclick="renderizarVoos()">▶ RENDERIZAR COMPARATIVO</button>

            <div id="stats-p1" class="p-stats">
                <h4>🟢 <span id="sp1-nome"></span></h4>
                <?php foreach(['pos'=>'Posição','dist'=>'Distância','alt'=>'Alt. Máxima','dur'=>'Duração','vel'=>'Vel. Média','glider'=>'Vela','selete'=>'Selete','reserva'=>'Reserva','inst'=>'Instrumento'] as $k=>$lbl): ?>
                <div class="sr"><span><?=$lbl?></span><span id="sp1-<?=$k?>"></span></div>
                <?php endforeach; ?>
            </div>
            <div id="stats-p2" class="p-stats p2">
                <h4>🟡 <span id="sp2-nome"></span></h4>
                <?php foreach(['pos'=>'Posição','dist'=>'Distância','alt'=>'Alt. Máxima','dur'=>'Duração','vel'=>'Vel. Média','glider'=>'Vela','selete'=>'Selete','reserva'=>'Reserva','inst'=>'Instrumento'] as $k=>$lbl): ?>
                <div class="sr"><span><?=$lbl?></span><span id="sp2-<?=$k?>"></span></div>
                <?php endforeach; ?>
            </div>

            <div class="clbl">📍 Telemetria do Ponto</div>
            <div id="tele-box">Clique em qualquer marcador do percurso para ver detalhes de telemetria.</div>

            <div class="clbl">🎮 Câmera 3D</div>
            <div class="cam-wrap">
                <button class="cbtn" onclick="setCam(65,45,12.5)">🛰 Aérea</button>
                <button class="cbtn" onclick="setCam(80,-20,11.5)">🏔 Lateral</button>
                <button class="cbtn" onclick="setCam(85,0,11.0)">📍 Frontal</button>
                <button class="cbtn" onclick="setCam(0,0,12.0)">🗺 Top 2D</button>
                <button class="cbtn" onclick="setCam(89,90,10.5)">🌿 Chão</button>
                <button class="cbtn" onclick="modoCinematico()">🎬 Cinemático</button>
            </div>
        </div>

        <!-- ════ MAPA ════ -->
        <div id="gv3d-mapbox" class="gv3d-map"></div>
    </div>

    <script>
    /* ── Base de Dados ── */
    const DB = <?php echo $json_db; ?>;
    let map = null, cinematicoIv = null;
    let markers = []; // marcadores de ponto de telemetria

    /* ── Popula selects de pilotos ── */
    function atualizarPilotos(num) {
        const ano = document.getElementById('sel-ano'+num).value;
        const sel = document.getElementById('sel-piloto'+num);
        sel.innerHTML = '';
        (DB[ano]||[]).forEach((p,i)=>{
            const o = document.createElement('option');
            o.value = i;
            o.textContent = p.piloto+' — '+p.posicao+' · '+p.pais;
            sel.appendChild(o);
        });
        if(num==='2' && sel.options.length>1) sel.selectedIndex=1;
    }

    document.addEventListener('DOMContentLoaded', ()=>{
        atualizarPilotos('1');
        const a2 = document.getElementById('sel-ano2');
        if(a2.options.length>1) a2.selectedIndex=1;
        atualizarPilotos('2');
        initMap();
    });

    /* ═══════════════════════════════════════════════
       INICIALIZAÇÃO DO MAPA — MaplibreGL Nativo
    ═══════════════════════════════════════════════ */
    function initMap() {
        map = new maplibregl.Map({
            container: 'gv3d-mapbox',
            style: {
                version: 8,
                glyphs: 'https://demotiles.maplibre.org/font/{fontstack}/{range}.pbf',
                sources: {
                    // Satélite Google (sem token)
                    'sat': {
                        type: 'raster',
                        tiles: ['https://mt0.google.com/vt/lyrs=y&hl=pt-BR&x={x}&y={y}&z={z}'],
                        tileSize: 256,
                        attribution: '© Google Maps'
                    },
                    // Terreno 3D AWS (sem token)
                    'terrain': {
                        type: 'raster-dem',
                        tiles: ['https://s3.amazonaws.com/elevation-tiles-prod/terrarium/{z}/{x}/{y}.png'],
                        encoding: 'terrarium',
                        tileSize: 256
                    }
                },
                layers: [{
                    id: 'background',
                    type: 'background',
                    paint: { 'background-color': '#1a2a1a' }
                }, {
                    id: 'satellite',
                    type: 'raster',
                    source: 'sat'
                }]
            },
            // Pico da Ibituruna — coordenadas GPS exatas
            center: [-41.9437, -18.8819],
            zoom: 12.5,
            pitch: 55,
            bearing: 30,
            maxPitch: 89,
            attributionControl: false
        });

        // Controles de navegação (zoom, rotação, inclinação)
        map.addControl(new maplibregl.NavigationControl({ visualizePitch: true }), 'top-right');
        map.addControl(new maplibregl.ScaleControl({ maxWidth: 120, unit: 'metric' }), 'bottom-right');
        map.addControl(new maplibregl.GeolocateControl({
            positionOptions: { enableHighAccuracy: true },
            trackUserLocation: true
        }), 'top-right');

        map.on('load', ()=>{
            // Ativa terreno 3D
            map.setTerrain({ source: 'terrain', exaggeration: 1.5 });
            // Fontes e layers reservados para os voos (inicialmente vazios)
            map.addSource('voo1', { type:'geojson', data: emptyGeoJSON() });
            map.addSource('voo2', { type:'geojson', data: emptyGeoJSON() });

            // Layer: linha de voo Piloto 1 (verde)
            map.addLayer({
                id: 'linha-p1',
                type: 'line',
                source: 'voo1',
                layout: { 'line-cap':'round', 'line-join':'round' },
                paint: {
                    'line-color': '#00e676',
                    'line-width': 4,
                    'line-opacity': 0.92
                }
            });

            // Layer: linha de voo Piloto 2 (ouro)
            map.addLayer({
                id: 'linha-p2',
                type: 'line',
                source: 'voo2',
                layout: { 'line-cap':'round', 'line-join':'round' },
                paint: {
                    'line-color': '#FFB81C',
                    'line-width': 4,
                    'line-opacity': 0.92
                }
            });
        });
    }

    function emptyGeoJSON() {
        return { type:'FeatureCollection', features:[] };
    }

    /* ═══════════════════════════════════════════════
       RENDERIZAR VOOS COMPARATIVOS
    ═══════════════════════════════════════════════ */
    function renderizarVoos() {
        if(!map || !map.isStyleLoaded()) {
            alert('Mapa ainda inicializando — aguarde 3 segundos e tente novamente.');
            return;
        }

        const ano1 = document.getElementById('sel-ano1').value;
        const idx1 = parseInt(document.getElementById('sel-piloto1').value);
        const ano2 = document.getElementById('sel-ano2').value;
        const idx2 = parseInt(document.getElementById('sel-piloto2').value);

        const p1 = DB[ano1] && DB[ano1][idx1];
        const p2 = DB[ano2] && DB[ano2][idx2];
        if(!p1 || !p2) return;

        preencherStats('1', p1);
        preencherStats('2', p2);

        // Limpar marcadores anteriores
        markers.forEach(m => m.remove());
        markers = [];

        // Construir GeoJSON a partir dos pontos de cada piloto
        const geo1 = pilotoParaGeoJSON(p1);
        const geo2 = pilotoParaGeoJSON(p2);

        map.getSource('voo1').setData(geo1);
        map.getSource('voo2').setData(geo2);

        // Adicionar marcadores clicáveis com telemetria
        adicionarMarcadores(p1, '#00e676');
        adicionarMarcadores(p2, '#FFB81C');

        // Câmera: voa pela rota completa
        map.flyTo({
            center: [-41.8500, -18.8000],
            zoom: 10.5,
            pitch: 70,
            bearing: -25,
            duration: 4500,
            essential: true
        });
    }

    function pilotoParaGeoJSON(p) {
        const coords = p.path.map(pt => [pt[0], pt[1]]);
        return {
            type: 'FeatureCollection',
            features: [{
                type: 'Feature',
                geometry: { type: 'LineString', coordinates: coords },
                properties: { piloto: p.piloto }
            }]
        };
    }

    function adicionarMarcadores(p, cor) {
        p.path.forEach((pt, i) => {
            const [lng, lat, alt, hora, vel, desc] = pt;

            // Emoji de marcador por tipo de ponto
            const emoji = i === 0 ? '🚀' : (i === p.path.length-1 ? '🏁' : '📍');

            // Elemento HTML do marcador
            const el = document.createElement('div');
            el.style.cssText = `
                width:24px; height:24px; border-radius:50%;
                background:${cor}; border:3px solid #fff;
                cursor:pointer; display:flex; align-items:center;
                justify-content:center; font-size:12px;
                box-shadow:0 2px 8px rgba(0,0,0,.5);
                transition:transform .2s;
            `;
            el.title = p.piloto + ' — ' + desc;

            // Popup de telemetria completa
            const popup = new maplibregl.Popup({ offset: 18, closeButton: false, maxWidth:'260px' })
                .setHTML(`
                    <div style="font-family:'Inter',sans-serif">
                        <strong style="color:${cor};font-size:1.05em">${p.piloto}</strong>
                        <span style="color:#aaa;font-size:.85em"> ${p.pais}</span><br>
                        <hr style="border-color:#2a4030;margin:5px 0">
                        🕐 <strong>${hora}</strong> &nbsp;|&nbsp;
                        🌡 <strong>${alt}m</strong> &nbsp;|&nbsp;
                        ⚡ <strong>${vel} km/h</strong><br>
                        <em style="color:#9ecfb5">${desc}</em><br>
                        <small style="color:#777">🛸 ${p.glider}</small>
                    </div>
                `);

            const marker = new maplibregl.Marker({ element: el, anchor:'center' })
                .setLngLat([lng, lat])
                .setPopup(popup)
                .addTo(map);

            el.addEventListener('mouseenter', ()=>{ el.style.transform='scale(1.4)'; });
            el.addEventListener('mouseleave', ()=>{ el.style.transform='scale(1)'; });
            el.addEventListener('click', ()=>{
                document.getElementById('tele-box').innerHTML = `
                    <span class="tl">${p.piloto}</span> ${p.pais}<br>
                    🕐 <strong>${hora}</strong> &nbsp;|&nbsp; 🌡 Alt: <strong>${alt}m</strong><br>
                    ⚡ Vel: <strong>${vel} km/h</strong><br>
                    📢 <em>${desc}</em><br>
                    🛸 <small>${p.glider} · ${p.selete}</small>
                `;
            });

            markers.push(marker);
        });
    }

    /* ── Painel de Estatísticas ── */
    function preencherStats(n, p) {
        document.getElementById('stats-p'+n).style.display = 'block';
        const map_fields = {
            'nome':p.piloto+' '+p.pais,'pos':p.posicao,'dist':p.distancia,
            'alt':p.alt_max,'dur':p.duracao,'vel':p.vel_media,
            'glider':p.glider,'selete':p.selete,'reserva':p.reserva,'inst':p.instrumento
        };
        Object.entries(map_fields).forEach(([k,v])=>{
            const el = document.getElementById('sp'+n+'-'+k);
            if(el) el.textContent = v;
        });
    }

    /* ── Controles de Câmera ── */
    function setCam(pitch, bearing, zoom) {
        if(!map) return;
        map.easeTo({ pitch, bearing, zoom, duration: 1500 });
    }

    function modoCinematico() {
        if(!map) return;
        clearInterval(cinematicoIv);
        let b = map.getBearing();
        map.easeTo({ pitch: 72, zoom: 11.0, duration: 1200 });
        cinematicoIv = setInterval(()=>{ b=(b+0.35)%360; map.setBearing(b); }, 50);
    }
    </script>
    <?php
    return ob_get_clean();
}
