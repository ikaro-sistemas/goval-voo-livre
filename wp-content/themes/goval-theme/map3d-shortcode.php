<?php
/**
 * map3d-shortcode.php — Mapa 3D Offline de Alta Performance (MapLibre GL v3)
 * Uso: [goval_3d_map]
 */

add_shortcode('goval_3d_map', 'goval_3d_map_shortcode');
function goval_3d_map_shortcode($atts) {
    require_once get_template_directory() . '/map3d-data.php';
    $db_voos = goval_get_flight_database();
    $brz     = goval_get_brasileirao_2026();
    $json_db = json_encode($db_voos, JSON_UNESCAPED_UNICODE);
    $anos    = array_keys($db_voos);
    rsort($anos);

    // Assets locais para funcionamento 100% offline
    $theme_url = get_template_directory_uri();
    $tile_url  = $theme_url . '/tile-proxy.php?z={z}&x={x}&y={y}';
    $js_url    = $theme_url . '/assets/maplibre-gl.js';
    $css_url   = $theme_url . '/assets/maplibre-gl.css';

    ob_start(); ?>

    <!-- MapLibre GL - Assets Locais -->
    <link rel="stylesheet" href="<?= $css_url ?>">
    <script src="<?= $js_url ?>"></script>

    <style>
    .gv-wrapper { background:#0a140f; color:#fff; font-family:'Inter',sans-serif; border-radius:12px; overflow:hidden; box-shadow:0 10px 40px rgba(0,0,0,.5); border:1px solid #1a3020; }
    .gv-tabs-nav { display:flex; gap:4px; background:#0d1a12; padding:10px 10px 0; border-bottom:1px solid #1a3020; }
    .gv-tab-btn  { padding:11px 24px; background:#1a3020; color:#aaa; border:none; border-radius:8px 8px 0 0; cursor:pointer; font-size:.82em; font-weight:600; text-transform:uppercase; letter-spacing:1px; transition:all .2s; }
    .gv-tab-btn.active { background:#007A53; color:#fff; }
    .gv-tab-pane { display:none; flex-direction:column; }
    .gv-tab-pane.active { display:flex; }

    /* ── Layout Mapa ── */
    #gv-map-layout { display:flex; height:85vh; min-height:650px; }
    .gv-dashboard { width:360px; min-width:320px; background:#0d1a12; padding:20px; border-right:2px solid #1a3020; overflow-y:auto; display:flex; flex-direction:column; gap:12px; }
    #map-canvas { flex-grow:1; background:#000; position:relative; }
    
    /* ── Painéis ── */
    .p-box { background:rgba(255,255,255,.03); border-radius:10px; padding:12px; border:1px solid #1a3020; }
    .p-box h3 { margin:0 0 10px; font-size:.78em; color:#4ecb80; text-transform:uppercase; letter-spacing:1.5px; border-bottom:1px solid #1a3020; padding-bottom:6px; }
    .sel-group { display:flex; flex-direction:column; gap:6px; margin-bottom:12px; }
    .sel-group label { font-size:.65em; color:#888; text-transform:uppercase; }
    select { width:100%; padding:10px; background:#142014; color:#fff; border:1px solid #233823; border-radius:8px; font-size:.85em; cursor:pointer; }
    
    /* ── Botão Render ── */
    .btn-render { padding:14px; background:linear-gradient(135deg,#007A53,#00cc60); color:#fff; border:none; border-radius:10px; font-weight:800; font-size:.9em; cursor:pointer; transition:transform .1s, box-shadow .2s; text-transform:uppercase; margin-bottom:10px; }
    .btn-render:hover { transform:translateY(-1px); box-shadow:0 5px 15px rgba(0,200,100,.3); }
    .btn-render:active { transform:translateY(1px); }

    /* ── Stats ── */
    #pilot-stats-p1, #pilot-stats-p2 { display:none; animation:fadeIn 0.3s forwards; }
    .stat-row { display:flex; justify-content:space-between; font-size:.75em; padding:4px 0; border-bottom:1px solid #1a3020; color:#ccc; }
    .stat-row b { color:#fff; }
    .p1-color { color:#00e676 !important; }
    .p2-color { color:#FFB81C !important; }

    /* ── Controles de Visualização ── */
    .controls-grid { display:grid; grid-template-columns: 1fr 1fr; gap:6px; margin-top:10px; }
    .c-btn { background:#1a3020; border:1px solid #2a4030; color:#aaa; font-size:.7em; padding:8px; border-radius:6px; cursor:pointer; transition:.2s; }
    .c-btn:hover { background:#007A53; color:#fff; }

    /* ── Tabela Brasileirão ── */
    .table-container { padding:25px; background:#0d1a12; height:85vh; overflow-y:auto; }
    .brz-table { width:100%; border-collapse:collapse; background:rgba(0,0,0,.2); border-radius:12px; font-size:.85em; }
    .brz-table th { background:#007A53; color:#fff; padding:12px; text-align:left; font-weight:700; position:sticky; top:0; z-index:10; }
    .brz-table td { padding:10px 12px; border-bottom:1px solid #1a3020; color:#eee; }
    .brz-table tr:hover td { background:rgba(255,255,255,.05); }
    .pos-1 { color:#FFD700; font-weight:900; }
    .pos-2 { color:#C0C0C0; font-weight:700; }
    .pos-3 { color:#CD7F32; font-weight:700; }
    .search-bar { width: 100%; max-width: 400px; padding: 12px 15px; background: #142014; border: 1px solid #233823; border-radius: 10px; color: #fff; margin-bottom: 20px; outline: none; transition: border-color .2s; }
    .search-bar:focus { border-color: #007A53; }

    /* ── Overlay "Aguarde" ── */
    #map-overlay { position:absolute; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,.85); z-index:100; display:flex; flex-direction:column; align-items:center; justify-content:center; transition:opacity .5s; pointer-events:none; opacity:0; }
    #map-overlay.visible { opacity:1; pointer-events:auto; }
    .spinner { border: 4px solid rgba(255, 255, 255, 0.1); border-left-color: #00cc60; border-radius: 50%; width: 40px; height: 40px; animation: spin 1s linear infinite; margin-bottom:15px; }
    @keyframes spin { to { transform: rotate(360deg); } }
    @keyframes fadeIn { from { opacity:0; transform:translateY(5px); } to { opacity:1; transform:translateY(0); } }

    @media(max-width:960px){
        #gv-map-layout { flex-direction:column; height:auto; }
        .gv-dashboard { width:100%; height:auto; max-height:none; }
        #map-canvas { height:50vh; min-height:350px; }
    }
    </style>

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
    let p1Markers = [], p2Markers = [];

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
    // MOTOR 3D: MAPLIBRE GL (OFFLINE)
    // ══════════════════════════════════════════════════════════
    function initMap() {
        const style = {
            version: 8,
            sources: {
                'tiles-local': {
                    type: 'raster',
                    tiles: ['<?= $tile_url ?>'],
                    tileSize: 256,
                    attribution: '© Google Satellite (Offline Cache)'
                }
            },
            layers: [
                { id: 'background', type: 'background', paint: { 'background-color': '#05100a' } },
                { id: 'sat-layer', type: 'raster', source: 'tiles-local' }
            ]
        };

        map = new maplibregl.Map({
            container: 'map-canvas',
            style: style,
            center: [-41.9437, -18.8819],
            zoom: 13,
            pitch: 45,
            bearing: -20,
            antialias: true
        });

        // Controles de Navegação (Zoom/Rotation)
        map.addControl(new maplibregl.NavigationControl({ visualizePitch: true }));
        map.addControl(new maplibregl.FullscreenControl());

        map.on('load', () => {
            console.log("MapLibre 3D Inicializado com sucesso.");
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
        p1Markers.forEach(m => m.remove()); 
        p2Markers.forEach(m => m.remove());
        p1Markers = []; p2Markers = [];

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
        
        const coords = pilot.path.map(pt => [pt[0], pt[1]]);
        
        map.addSource(sourceId, {
            type: 'geojson',
            data: {
                type: 'Feature',
                properties: {},
                geometry: { type: 'LineString', coordinates: coords }
            }
        });

        map.addLayer({
            id: lineId,
            type: 'line',
            source: sourceId,
            layout: { 'line-join': 'round', 'line-cap': 'round' },
            paint: {
                'line-color': color,
                'line-width': 6,
                'line-opacity': 0.85,
                'line-blur': 1
            }
        });

        activeLayers.push(sourceId, lineId);

        // Adicionar Marcadores Interativos
        pilot.path.forEach((pt, idx) => {
            const isCritical = idx === 0 || idx === pilot.path.length - 1 || pt[4] > 40;
            if(!isCritical && idx % 2 !== 0) return; // Otimização visual

            const el = document.createElement('div');
            el.style.width = '16px'; el.style.height = '16px';
            el.style.backgroundColor = color;
            el.style.border = '2px solid #fff';
            el.style.borderRadius = '50%';
            el.style.cursor = 'pointer';
            el.style.boxShadow = '0 0 10px ' + color;

            const popup = new maplibregl.Popup({ offset: 10, closeButton: false })
                .setHTML(`
                    <div style="background:#0d1a12; color:#fff; padding:6px; font-size:.8em; border-radius:6px; border:1px solid ${color}">
                        <b style="color:${color}">${pilot.piloto}</b><br>
                        🕐 ${pt[3]} | 🌡 ${pt[2]}m<br>
                        ⚡ ${pt[4]} km/h<br>
                        <em>"${pt[5]}"</em>
                    </div>
                `);

            const marker = new maplibregl.Marker(el)
                .setLngLat([pt[0], pt[1]])
                .setPopup(popup)
                .addTo(map);

            el.addEventListener('click', () => {
                document.getElementById('tele-info').innerHTML = `
                    <b style="color:${color}">${pilot.piloto}</b> — Ponto #${idx}<br>
                    <span style="color:#fff">Hora:</span> ${pt[3]} | <span style="color:#fff">Altitude:</span> ${pt[2]}m<br>
                    <span style="color:#fff">Velocidade:</span> ${pt[4]} km/h<br>
                    <span style="color:#fff; font-style:italic;">"${pt[5]}"</span>
                `;
            });

            if(id === 'p1') p1Markers.push(marker); else p2Markers.push(marker);
        });
    }

    function updateStats(num, pilot) {
        document.getElementById('pilot-stats-p' + num).style.display = 'block';
        document.getElementById('st-nome' + num).textContent = pilot.piloto;
        const html = `
            <div class="stat-row"><span>Campeonato:</span> <b>${pilot.posicao}</b></div>
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
            ibituruna: { center: [-41.9437, -18.8819], zoom: 15.5, pitch: 65, bearing: -40 },
            decolagem: { center: [-41.9430, -18.8825], zoom: 17, pitch: 75, bearing: 10 },
            ground:    { center: [-41.9300, -18.8700], zoom: 14.5, pitch: 85, bearing: -20 },
            wide:      { center: [-41.7800, -18.7800], zoom: 11, pitch: 30, bearing: 0 }
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
