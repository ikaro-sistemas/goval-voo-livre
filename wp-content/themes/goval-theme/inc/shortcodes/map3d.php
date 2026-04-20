<?php
/**
 * map3d-shortcode.php — Mapa 3D Offline de Alta Performance (MapLibre GL v3)
 * Uso: [goval_3d_map]
 */

add_shortcode('goval_3d_map', 'goval_3d_map_shortcode');
function goval_3d_map_shortcode($atts) {
    require_once get_template_directory() . '/inc/map3d-data.php';
    $db_voos = goval_get_flight_database();
    $brz     = goval_get_brasileirao_2026();
    $json_db = json_encode($db_voos, JSON_UNESCAPED_UNICODE);
    $anos    = array_keys($db_voos);
    rsort($anos);

    // Assets locais para funcionamento 100% offline
    $theme_url = get_template_directory_uri();
    $tile_url  = $theme_url . '/inc/tile-proxy.php?z={z}&x={x}&y={y}';
    $js_url    = $theme_url . '/assets/js/maplibre-gl.js';
    $css_url   = $theme_url . '/assets/css/maplibre-gl.css';

    ob_start(); ?>

    <!-- MapLibre GL - Assets Locais -->
    <link rel="stylesheet" href="<?= $css_url ?>">
    <script src="<?= $js_url ?>"></script>

    ?>

    <div class="gv-wrapper">
        <div class="gv-tabs-nav">
            <button class="gv-tab-btn active" data-tab="mapa">🗺️ Comparativo 3D</button>
            <button class="gv-tab-btn" data-tab="brz">🏆 Brasileirão 2026</button>
        </div>

        <!-- ABA MAPA -->
        <div id="tab-mapa" class="gv-tab-pane active">
            <div id="gv-map-layout">
                <div class="gv-dashboard">
                    <div class="p-box">
                        <h3>🏔 Seleção de Voo</h3>
                        
                        <div class="sel-group">
                            <label>🟢 Piloto 1 + Temporada</label>
                            <select id="ano1" onchange="loadPilots(1)">
                                <?php foreach($anos as $a): ?><option value="<?= $a ?>"><?= $a ?></option><?php endforeach; ?>
                            </select>
                            <select id="piloto1" style="margin-top:5px"></select>
                        </div>

                        <div class="sel-group">
                            <label>🟡 Piloto 2 + Temporada</label>
                            <select id="ano2" onchange="loadPilots(2)">
                                <?php foreach($anos as $a): ?><option value="<?= $a ?>" <?= $a==($anos[1]??$anos[0])?'selected':'' ?>><?= $a ?></option><?php endforeach; ?>
                            </select>
                            <select id="piloto2" style="margin-top:5px"></select>
                        </div>

                        <button class="btn-render" id="run-render">▶ Renderizar Comparativo</button>
                    </div>

                    <!-- Side Stats -->
                    <div id="pilot-stats-p1" class="p-box">
                        <h3 class="p1-color">🟢 <span id="st-nome1"></span></h3>
                        <div id="st-content1"></div>
                    </div>
                    <div id="pilot-stats-p2" class="p-box">
                        <h3 class="p2-color">🟡 <span id="st-nome2"></span></h3>
                        <div id="st-content2"></div>
                    </div>

                    <div class="p-box">
                        <h3>📍 Telemetria Instantânea</h3>
                        <div id="tele-info" style="font-size:.72em; color:#999; line-height:1.5;">Clique nos marcadores da rota para ver detalhes técnicos.</div>
                    </div>

                    <div class="p-box">
                        <h3>🎮 Câmera 3D</h3>
                        <div class="controls-grid">
                            <button class="c-btn" onclick="setCam('ibituruna')">Pico Ibituruna</button>
                            <button class="c-btn" onclick="setCam('decolagem')">Visão Decolagem</button>
                            <button class="c-btn" onclick="setCam('ground')">Nível do Solo</button>
                            <button class="c-btn" onclick="setCam('wide')">Visão Panorâmica</button>
                        </div>
                    </div>
                </div>

                <div id="map-canvas">
                    <div id="map-overlay">
                        <div class="spinner"></div>
                        <div style="font-size:.8em; color:#aaa;">Processando Trajetórias 3D...</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ABA BRASILEIRÃO -->
        <div id="tab-brz" class="gv-tab-pane">
            <div class="table-container">
                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <h2 style="color:#00cc60; margin:0 0 20px;">Ranking Brasileiro 2026 — 120 Competidores</h2>
                    <input type="text" class="search-bar" id="brzSearch" placeholder="🔍 Filtrar por nome, estado ou equipamento..." onkeyup="filterBrz()">
                </div>
                <table class="brz-table" id="brzTable">
                    <thead>
                        <tr>
                            <th>Pos</th><th>Piloto</th><th>UF</th><th>Vela</th><th>Km</th><th>Pts</th><th>Tempo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($brz as $r): 
                            $pos_cls = $r[0]<=3 ? 'pos-'.$r[0] : ''; ?>
                        <tr>
                            <td class="<?= $pos_cls ?>">#<?= $r[0] ?></td>
                            <td style="font-weight:700;"><?= esc_html($r[1]) ?></td>
                            <td><?= esc_html($r[2]) ?></td>
                            <td><?= esc_html($r[4]) ?></td>
                            <td><?= number_format($r[5], 1) ?></td>
                            <td><?= $r[6] ?></td>
                            <td><?= esc_html($r[7]) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
    const DB = <?= $json_db ?>;
    let map = null;
    let activeLayers = [];

    // ── Inicialização ──
    document.addEventListener('DOMContentLoaded', () => {
        initTabs();
        loadPilots(1);
        loadPilots(2);
        initMap();

        document.getElementById('run-render').addEventListener('click', () => {
            renderComparison();
        });
    });

    function initTabs() {
        document.querySelectorAll('.gv-tab-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.gv-tab-btn').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.gv-tab-pane').forEach(p => p.classList.remove('active'));
                btn.classList.add('active');
                const pane = document.getElementById('tab-' + btn.dataset.tab);
                pane.classList.add('active');
                if(btn.dataset.tab === 'mapa' && map) map.resize();
            });
        });
    }

    function loadPilots(num) {
        const ano = document.getElementById('ano' + num).value;
        const sel = document.getElementById('piloto' + num);
        sel.innerHTML = '';
        (DB[ano] || []).forEach((p, idx) => {
            const opt = document.createElement('option');
            opt.value = idx;
            opt.textContent = `${p.piloto} (${p.posicao})`;
            sel.appendChild(opt);
        });
        if(num === 2 && sel.options.length > 1) sel.selectedIndex = 1;
    }

    // ══════════════════════════════════════════════════════════
    // MOTOR 3D: MAPLIBRE GL (OFFLINE + ELEVAÇÃO REAL)
    // ══════════════════════════════════════════════════════════
    function initMap() {
        const style = {
            version: 8,
            sources: {
                'tiles-google': {
                    type: 'raster',
                    tiles: ['https://mt1.google.com/vt/lyrs=s&y={y}&x={x}&z={z}'],
                    tileSize: 256,
                    attribution: '© Google Maps Satellite'
                }
            },
            layers: [
                { id: 'background', type: 'background', paint: { 'background-color': '#05100a' } },
                { id: 'sat-layer', type: 'raster', source: 'tiles-google', paint: { 'raster-opacity': 0.95 } }
            ]
        };

        map = new maplibregl.Map({
            container: 'map-canvas',
            style: style,
            center: [-41.9152, -18.8865],
            zoom: 14,
            pitch: 50,
            bearing: -20,
            antialias: true
        });

        map.addControl(new maplibregl.NavigationControl({ visualizePitch: true }));
        map.addControl(new maplibregl.FullscreenControl());

        map.on('load', () => {
            console.log("MapLibre GL com Google Maps Satellite Inicializado.");
        });
    }

    // ══════════════════════════════════════════════════════════
    // RENDERIZAÇÃO DAS TRAJETÓRIAS
    // ══════════════════════════════════════════════════════════
    function renderComparison() {
        if(!map) return;
        
        const overlay = document.getElementById('map-overlay');
        overlay.classList.add('visible');

        const a1 = document.getElementById('ano1').value, idx1 = document.getElementById('piloto1').value;
        const a2 = document.getElementById('ano2').value, idx2 = document.getElementById('piloto2').value;
        
        const p1 = DB[a1][idx1], p2 = DB[a2][idx2];

        // Limpar Camadas Anteriores
        activeLayers.forEach(id => {
            if(map.getLayer(id)) map.removeLayer(id);
            if(map.getSource(id)) map.removeSource(id);
        });
        activeLayers = [];

        setTimeout(() => {
            drawPath(p1, 'p1', '#00ff88');
            drawPath(p2, 'p2', '#FFB81C');
            updateStats(1, p1);
            updateStats(2, p2);

            // Enquadrar trajetórias
            const allCoords = [...p1.path, ...p2.path].map(pt => [pt[0], pt[1]]);
            const bounds = allCoords.reduce((acc, coord) => acc.extend(coord), new maplibregl.LngLatBounds(allCoords[0], allCoords[0]));
            map.fitBounds(bounds, { padding: 60, speed: 0.8 });
            
            overlay.classList.remove('visible');
        }, 600);
    }

    function drawPath(pilot, id, color) {
        const sourceId = 'route-' + id;
        const lineId = 'line-' + id;
        const pointSourceId = 'points-' + id;
        const pointLayerId = 'point-layer-' + id;
        
        // Criação da "Fita 3D" Suspensa no Ar (Extrusion) em vez de Linha Plana
        const ribbonFeatures = [];
        for(let i=0; i<pilot.path.length - 1; i++) {
            let p1 = pilot.path[i];
            let p2 = pilot.path[i+1];
            
            let dx = p2[0] - p1[0];
            let dy = p2[1] - p1[1];
            let ang = Math.atan2(dy, dx);
            let width = 0.00015; // Largura da Fita em 3D
            
            let nx = Math.sin(ang) * width;
            let ny = -Math.cos(ang) * width;
            
            let poly = [
                [p1[0] + nx, p1[1] + ny],
                [p2[0] + nx, p2[1] + ny],
                [p2[0] - nx, p2[1] - ny],
                [p1[0] - nx, p1[1] - ny],
                [p1[0] + nx, p1[1] + ny]
            ];
            
            let avgAlt = (p1[2] + p2[2]) / 2; // Altitude média do segmento
            let RibbonThickness = 12; // A fita terá 12 metros de altura vertical flutuando

            ribbonFeatures.push({
                type: 'Feature',
                properties: { 
                    base: Math.max(0, avgAlt - RibbonThickness), 
                    height: avgAlt + RibbonThickness 
                },
                geometry: { type: 'Polygon', coordinates: [poly] }
            });
        }

        const ribbonSourceId = 'ribbon-' + id;
        const ribbonLayerId = 'ribbon-layer-' + id;

        map.addSource(ribbonSourceId, {
            type: 'geojson',
            data: { type: 'FeatureCollection', features: ribbonFeatures }
        });

        // Removemos a linha plana antiga e a substituímos por uma Malha 3D voadora (fill-extrusion)
        map.addLayer({
            id: ribbonLayerId,
            type: 'fill-extrusion',
            source: ribbonSourceId,
            paint: {
                'fill-extrusion-color': color,
                'fill-extrusion-height': ['get', 'height'],
                'fill-extrusion-base': ['get', 'base'],
                'fill-extrusion-opacity': 0.85
            }
        });

        // 500 Pontos GeoJSON Invisíveis (Hitboxes de Interação)
        const features = pilot.path.map((pt, idx) => {
            return {
                type: 'Feature',
                properties: { piloto: pilot.piloto, hora: pt[3], alt: pt[2], vel: pt[4], msg: pt[5], idx: idx },
                geometry: { type: 'Point', coordinates: [pt[0], pt[1]] }
            };
        });

        map.addSource(pointSourceId, {
            type: 'geojson',
            data: { type: 'FeatureCollection', features: features }
        });

        // As bolinhas (pontos de toque invisíveis) viram caixas de interação secretas grandes
        map.addLayer({
            id: pointLayerId,
            type: 'circle',
            source: pointSourceId,
            paint: {
                'circle-color': 'rgba(0,0,0,0)', // Invisíveis aos olhos
                'circle-opacity': 0,
                'circle-radius': 12, // Hitbox generoso para o dedo no celular achar a informação
                'circle-stroke-width': 0
            }
        });

        activeLayers.push(ribbonSourceId, ribbonLayerId, pointSourceId, pointLayerId);

        let popup = new maplibregl.Popup({ closeButton: false, closeOnClick: true, offset: 10 });

        const showPointData = (e) => {
            map.getCanvas().style.cursor = 'crosshair';
            const props = e.features[0].properties;
            const coord = e.features[0].geometry.coordinates.slice();

            while (Math.abs(e.lngLat.lng - coord[0]) > 180) { coord[0] += e.lngLat.lng > coord[0] ? 360 : -360; }

            const html = `
                <div style="background:#0d1a12; color:#fff; padding:8px; font-size:.85em; border-radius:6px; border:1px solid ${color}">
                    <b style="color:${color}">${props.piloto}</b> <span style="color:#666">Pto#${props.idx}</span><br>
                    🕐 <b style="color:#fff">${props.hora}</b> | 🌡 <b style="color:#ffb81c">${props.alt}m</b><br>
                    ⚡ <b style="color:#00ff88">${props.vel} km/h</b><br>
                    <i style="color:#aaa">"${props.msg}"</i>
                </div>
            `;
            popup.setLngLat(coord).setHTML(html).addTo(map);

            document.getElementById('tele-info').innerHTML = `
                <b style="color:${color}">${props.piloto}</b> — Rastreio Ponto #${props.idx}<br>
                <span style="color:#fff">Hora:</span> ${props.hora} | <span style="color:#fff">Altitude:</span> <span style="color:#ffb81c">${props.alt}m</span><br>
                <span style="color:#fff">Velocidade Atual:</span> ⚡ ${props.vel} km/h<br>
                <span style="color:#4ecb80; font-style:italic;">"${props.msg}"</span>
            `;
        };

        map.on('click', pointLayerId, showPointData);
        map.on('mouseenter', pointLayerId, showPointData);
        map.on('mouseleave', pointLayerId, () => {
            map.getCanvas().style.cursor = '';
            popup.remove();
        });
    }

    function updateStats(num, pilot) {
        document.getElementById('pilot-stats-p' + num).style.display = 'block';
        document.getElementById('st-nome' + num).textContent = pilot.piloto;
        const html = `
            <div class="stat-row"><span>Data (Prova):</span> <b style="color:#80e5ff">${pilot.data_prova}</b></div>
            <div class="stat-row"><span>Clima:</span> <b style="color:#80e5ff">${pilot.clima}</b></div>
            <div class="stat-row" style="margin-top:6px; padding-top:6px; border-top:1px dashed #2a4030"><span>Campeonato:</span> <b>${pilot.posicao}</b></div>
            <div class="stat-row"><span>País:</span> <b>${pilot.pais}</b></div>
            <div class="stat-row"><span>Distância:</span> <b>${pilot.distancia}</b></div>
            <div class="stat-row"><span>Alt. Máxima:</span> <b>${pilot.alt_max}</b></div>
            <div class="stat-row"><span>Vela:</span> <b>${pilot.glider}</b></div>
            <div class="stat-row"><span>Selete:</span> <b>${pilot.selete}</b></div>
            <div class="stat-row"><span>Reserva:</span> <b>${pilot.reserva}</b></div>
        `;
        document.getElementById('st-content' + num).innerHTML = html;
    }

    // ── Câmera ──
    function setCam(type) {
        if(!map) return;
        const config = {
            ibituruna: { center: [-41.9152, -18.8865], zoom: 15, pitch: 55, bearing: -40 },
            decolagem: { center: [-41.9145, -18.8870], zoom: 17, pitch: 65, bearing: 10 },
            ground:    { center: [-41.9300, -18.8700], zoom: 14.5, pitch: 70, bearing: -20 },
            wide:      { center: [-41.8000, -18.7800], zoom: 11, pitch: 30, bearing: 0 }
        };
        map.easeTo({ ...config[type], duration: 2500 });
    }

    // ── Brasileirão Filter ──
    function filterBrz() {
        const q = document.getElementById('brzSearch').value.toLowerCase();
        const rows = document.querySelectorAll('#brzTable tbody tr');
        rows.forEach(r => {
            r.style.display = r.textContent.toLowerCase().includes(q) ? '' : 'none';
        });
    }
    </script>
    <?php
    return ob_get_clean();
}
