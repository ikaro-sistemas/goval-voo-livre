<?php
// ============================================================================
// SHORTCODE: MAPA COMPARATIVO DE VOOS - Leaflet.js + Tile Proxy Local
// Uso: [goval_3d_map]
// Mapa offline via PHP Tile Proxy Cache (tile-proxy.php)
// ============================================================================
add_shortcode('goval_3d_map', 'goval_3d_map_shortcode');
function goval_3d_map_shortcode($atts) {
    require_once get_template_directory() . '/map3d-data.php';
    $db_voos = goval_get_flight_database();
    $brz     = goval_get_brasileirao_2026();
    $json_db = json_encode($db_voos, JSON_UNESCAPED_UNICODE);
    $anos    = array_keys($db_voos);
    rsort($anos);

    // URL do proxy de tiles local (relativo ao WP)
    $tile_url = get_template_directory_uri() . '/tile-proxy.php?z={z}&x={x}&y={y}';

    ob_start(); ?>

    <!-- Leaflet CSS + JS (CDN confiável, funciona offline se carregado uma vez) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
    .gv-tabs-nav { display:flex; gap:4px; background:#0d1a12; padding:10px 10px 0; }
    .gv-tab-btn  { padding:9px 20px; background:#1a3020; color:#aaa; border:none; border-radius:6px 6px 0 0; cursor:pointer; font-size:.85em; font-family:'Inter',sans-serif; transition:background .2s; }
    .gv-tab-btn.active { background:#007A53; color:#fff; font-weight:700; }
    .gv-tab-pane { display:none; }
    .gv-tab-pane.active { display:flex; }

    /* ── Mapa ─────────────────────────────── */
    #gv-map-container { display:flex; height:82vh; min-height:580px; font-family:'Inter',sans-serif; }
    .gv-side  { width:340px; min-width:280px; background:#0d1a12; color:#ddd; padding:15px; border-right:2px solid #1a3020; overflow-y:auto; display:flex; flex-direction:column; gap:9px; }
    .gv-side h3 { color:#4ecb80; margin:0; font-size:.88em; text-transform:uppercase; letter-spacing:.5px; }
    #leafletMap { flex-grow:1; min-height:400px; background:#1a2a1a; }
    .pb   { background:rgba(255,255,255,.05); border-radius:8px; padding:11px; border-left:4px solid #00cc60; }
    .pb.p2{ border-left-color:#FFB81C; }
    .pb label{ font-size:.7em; color:#999; text-transform:uppercase; letter-spacing:1px; display:block; margin-bottom:3px; }
    .pb select{ width:100%; padding:7px; border-radius:6px; border:1px solid #233022; background:#142014; color:#fff; font-size:.85em; margin-top:2px; }
    .sbtn { padding:11px; background:linear-gradient(135deg,#007A53,#00cc60); color:#fff; border:none; font-weight:700; cursor:pointer; border-radius:8px; font-size:.88em; width:100%; }
    .sbtn:hover { opacity:.85; }
    #tele-box { background:rgba(255,255,255,.04); border-radius:8px; padding:10px; min-height:70px; font-size:.78em; line-height:1.7; color:#bbb; border:1px solid #1a3020; }
    .p-stats { font-size:.74em; background:rgba(255,255,255,.04); border-radius:8px; padding:9px; display:none; }
    .p-stats h4 { margin:0 0 4px; color:#4ecb80; }
    .p-stats.p2 h4 { color:#FFB81C; }
    .sr { display:flex; justify-content:space-between; border-bottom:1px solid #1a3020; padding:3px 0; }
    .sr span:last-child { color:#fff; font-weight:500; }
    .cam-wrap { display:flex; flex-wrap:wrap; gap:5px; }
    .cbtn { flex:1; min-width:70px; padding:6px 3px; background:#142014; color:#aaa; border:1px solid #243824; border-radius:6px; cursor:pointer; font-size:.7em; text-align:center; }
    .cbtn:hover { background:#007A53; color:#fff; }
    .clbl { font-size:.68em; color:#5a9a70; text-transform:uppercase; letter-spacing:1px; margin:2px 0 1px; }

    /* ── Tabela Brasileirao ───────────────── */
    #gv-brz-container { padding:15px; background:#0d1a12; overflow-x:auto; }
    #gv-brz-container h2 { color:#FFB81C; margin:0 0 15px; font-size:1.1em; }
    .brz-search { width:100%; padding:9px 12px; background:#142014; border:1px solid #2a4030; color:#fff; border-radius:6px; margin-bottom:12px; font-size:.9em; }
    .brz-table  { width:100%; border-collapse:collapse; font-size:.8em; min-width:800px; }
    .brz-table th { background:#007A53; color:#fff; padding:9px 8px; text-align:left; position:sticky; top:0; }
    .brz-table td { padding:7px 8px; border-bottom:1px solid #1a3020; color:#ccc; }
    .brz-table tr:hover td { background:rgba(0,200,100,.07); }
    .brz-table tr:nth-child(even) td { background:rgba(255,255,255,.03); }
    .pos-gold { color:#FFD700; font-weight:800; }
    .pos-silver { color:#C0C0C0; font-weight:700; }
    .pos-bronze { color:#CD7F32; font-weight:700; }
    .estrangeiro { color:#FFD700; font-size:.7em; }

    @media(max-width:820px){
        #gv-map-container{flex-direction:column;height:auto}
        .gv-side{width:100%;max-height:40vh}
        #leafletMap{height:50vh}
    }
    </style>

    <!-- Tabs: Mapa / Tabela Brasileirao -->
    <div class="gv-tabs-nav">
        <button class="gv-tab-btn active" onclick="switchTab('mapa',this)">🗺️ Comparativo de Voos</button>
        <button class="gv-tab-btn" onclick="switchTab('brz',this)">🏆 Brasileirão 2026 (120 Pilotos)</button>
    </div>

    <!-- Tab Mapa -->
    <div id="tab-mapa" class="gv-tab-pane active">
        <div id="gv-map-container">
            <div class="gv-side">
                <h3>🏔 Telemetria · Ibituruna</h3>

                <div class="pb p1">
                    <label>🟢 Piloto 1 + Ano</label>
                    <select id="sel-ano1" onchange="updPilotos('1')">
                        <?php foreach($anos as $a): ?><option value="<?= esc_attr($a)?>"><?= esc_html($a)?></option><?php endforeach; ?>
                    </select>
                    <select id="sel-piloto1" style="margin-top:6px"></select>
                </div>

                <div class="pb p2">
                    <label>🟡 Piloto 2 + Ano</label>
                    <select id="sel-ano2" onchange="updPilotos('2')">
                        <?php foreach($anos as $a): ?><option value="<?= esc_attr($a)?>" <?php if($a==$anos[1]) echo 'selected'; ?>><?= esc_html($a)?></option><?php endforeach; ?>
                    </select>
                    <select id="sel-piloto2" style="margin-top:6px"></select>
                </div>

                <button class="sbtn" onclick="renderVoos()">▶ RENDERIZAR COMPARATIVO</button>

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
                <div id="tele-box">Clique em um marcador para ver dados de telemetria.</div>

                <div class="clbl">🎮 Câmera / Zoom</div>
                <div class="cam-wrap">
                    <button class="cbtn" onclick="mapView(-18.8819,-41.9437,14)">🏔 Ibituruna</button>
                    <button class="cbtn" onclick="mapView(-18.78,-41.76,11)">🗺 Rota Completa</button>
                    <button class="cbtn" onclick="mapView(-18.88,-41.94,13)">🔍 Decolagem</button>
                    <button class="cbtn" onclick="userLocation()">📡 Minha Loc.</button>
                </div>
            </div>
            <div id="leafletMap"></div>
        </div>
    </div>

    <!-- Tab Brasileirão -->
    <div id="tab-brz" class="gv-tab-pane">
        <div id="gv-brz-container">
            <h2>🏆 Campeonato Brasileiro de Voo Livre 2026 — 120 Competidores</h2>
            <input class="brz-search" type="text" placeholder="🔍 Buscar piloto, estado, vela..." id="brz-filter" oninput="filtrarBrz()">
            <div style="overflow-x:auto;max-height:78vh">
            <table class="brz-table" id="brz-tabela">
                <thead>
                    <tr>
                        <th>#</th><th>Piloto</th><th>UF</th><th>País</th>
                        <th>Vela</th><th>Distância</th><th>Pontos</th><th>Tempo</th><th>Obs.</th>
                    </tr>
                </thead>
                <tbody id="brz-body">
                <?php foreach($brz as $r):
                    $cls = $r[0]==1?'pos-gold':($r[0]==2?'pos-silver':($r[0]==3?'pos-bronze':''));
                    $ext = $r[2]==='EX' ? '<span class="estrangeiro">(EXT)</span>' : '';
                ?>
                <tr>
                    <td class="<?=$cls?>"><?=$r[0]?></td>
                    <td><?=esc_html($r[1])?> <?=$ext?></td>
                    <td><?=esc_html($r[2])?></td>
                    <td><?=esc_html($r[3])?></td>
                    <td><?=esc_html($r[4])?></td>
                    <td><?=esc_html($r[5])?> km</td>
                    <td><?=esc_html($r[6])?></td>
                    <td><?=esc_html($r[7])?></td>
                    <td style="font-size:.75em;color:#888"><?=esc_html($r[8])?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            </div>
        </div>
    </div>

    <script>
    const DB = <?php echo $json_db; ?>;
    let leafMap = null;
    let layers  = { p1: null, p2: null, markers: [] };

    // ── Tabs ─────────────────────────────────────────────────
    function switchTab(id, btn) {
        document.querySelectorAll('.gv-tab-pane').forEach(p => p.classList.remove('active'));
        document.querySelectorAll('.gv-tab-btn').forEach(b => b.classList.remove('active'));
        document.getElementById('tab-'+id).classList.add('active');
        btn.classList.add('active');
        if(id==='mapa' && leafMap) setTimeout(()=>leafMap.invalidateSize(), 100);
    }

    // ── Filtro da Tabela ──────────────────────────────────────
    function filtrarBrz() {
        const q = document.getElementById('brz-filter').value.toLowerCase();
        document.querySelectorAll('#brz-body tr').forEach(tr => {
            tr.style.display = tr.textContent.toLowerCase().includes(q) ? '' : 'none';
        });
    }

    // ── Selects de Pilotos ────────────────────────────────────
    function updPilotos(num) {
        const ano = document.getElementById('sel-ano'+num).value;
        const sel = document.getElementById('sel-piloto'+num);
        sel.innerHTML = '';
        (DB[ano]||[]).forEach((p,i) => {
            const o = document.createElement('option');
            o.value = i;
            o.textContent = p.piloto + ' — ' + p.posicao + ' · ' + p.pais;
            sel.appendChild(o);
        });
        if(num==='2' && sel.options.length>1) sel.selectedIndex=1;
    }

    document.addEventListener('DOMContentLoaded', () => {
        updPilotos('1');
        const a2 = document.getElementById('sel-ano2');
        if(a2.options.length>1) a2.selectedIndex=1;
        updPilotos('2');
        initLeaflet();
    });

    // ══════════════════════════════════════════════════════════
    // INICIALIZAÇÃO DO LEAFLET (mapa local, sem token)
    // ══════════════════════════════════════════════════════════
    function initLeaflet() {
        // Centro no Pico da Ibituruna (coordenadas exatas)
        leafMap = L.map('leafletMap', {
            center: [-18.8819, -41.9437],
            zoom: 13,
            zoomControl: true
        });

        // Layer 1: Satélite ESRI via proxy local (cache offline)
        const sat = L.tileLayer('<?php echo $tile_url; ?>', {
            attribution: '© ESRI Satellite | © OpenStreetMap',
            maxZoom: 18,
            tileSize: 256
        });
        sat.addTo(leafMap);

        // Layer 2: Ruas OpenStreetMap via proxy (alternativa)
        const osm = L.tileLayer('<?php echo $tile_url; ?>', {
            attribution: '© OpenStreetMap',
            maxZoom: 18
        });

        L.control.layers({ 'Satélite': sat, 'Mapa': osm }).addTo(leafMap);

        // Marcador do Pico da Ibituruna
        const ibIcon = L.divIcon({
            html: '<div style="background:#007A53;color:#fff;padding:4px 8px;border-radius:20px;font-size:11px;font-weight:bold;white-space:nowrap;box-shadow:0 2px 8px rgba(0,0,0,.6)">🏔 Pico Ibituruna</div>',
            className: '', iconAnchor: [60, 15]
        });
        L.marker([-18.8819, -41.9437], { icon: ibIcon })
          .addTo(leafMap)
          .bindPopup('<strong>🏔 Pico da Ibituruna</strong><br>Altitude: 1.123m<br>Coord: -18.8819, -41.9437<br><em>Ponto de Decolagem - GV, MG</em>');
    }

    // ══════════════════════════════════════════════════════════
    // RENDERIZAR VOOS NO MAPA
    // ══════════════════════════════════════════════════════════
    function renderVoos() {
        if(!leafMap) return;

        const ano1=document.getElementById('sel-ano1').value, idx1=parseInt(document.getElementById('sel-piloto1').value);
        const ano2=document.getElementById('sel-ano2').value, idx2=parseInt(document.getElementById('sel-piloto2').value);
        const p1=DB[ano1]&&DB[ano1][idx1], p2=DB[ano2]&&DB[ano2][idx2];
        if(!p1||!p2) return;

        preencherStats('1',p1); preencherStats('2',p2);

        // Remover camadas anteriores
        if(layers.p1) leafMap.removeLayer(layers.p1);
        if(layers.p2) leafMap.removeLayer(layers.p2);
        layers.markers.forEach(m => leafMap.removeLayer(m));
        layers.markers = [];

        // Construir polylines
        const coords1 = p1.path.map(pt => [pt[1], pt[0]]);
        const coords2 = p2.path.map(pt => [pt[1], pt[0]]);

        layers.p1 = L.polyline(coords1, {
            color:'#00e676', weight:5, opacity:0.9, dashArray:null,
            smoothFactor:1.5
        }).addTo(leafMap);

        layers.p2 = L.polyline(coords2, {
            color:'#FFB81C', weight:5, opacity:0.9,
            smoothFactor:1.5
        }).addTo(leafMap);

        // Marcadores com popup de telemetria
        addMarkers(p1, '#00e676');
        addMarkers(p2, '#FFB81C');

        // Ajustar bounds para mostrar ambos os trajetos
        const allCoords = [...coords1, ...coords2];
        leafMap.fitBounds(L.latLngBounds(allCoords), { padding: [30,30] });
    }

    function addMarkers(p, cor) {
        p.path.forEach((pt, i) => {
            const [lng, lat, alt, hora, vel, desc] = pt;
            const isStart = i === 0;
            const isEnd   = i === p.path.length - 1;
            const emoji   = isStart ? '🚀' : (isEnd ? '🏁' : '📍');

            const icon = L.divIcon({
                html: `<div style="
                    width:22px;height:22px;border-radius:50%;
                    background:${cor};border:3px solid #fff;
                    display:flex;align-items:center;justify-content:center;
                    font-size:10px;cursor:pointer;
                    box-shadow:0 2px 8px rgba(0,0,0,.5);
                    transition:transform .2s;
                ">${emoji}</div>`,
                className:'',
                iconAnchor:[11,11]
            });

            const popup = `
                <div style="font-family:'Inter',sans-serif;min-width:200px">
                    <strong style="color:${cor}">${p.piloto}</strong>
                    <span style="color:#888;font-size:.85em"> ${p.pais}</span><br>
                    <span style="color:#007A53">━━━━━━━━━━━━━━━━━</span><br>
                    🕐 <strong>${hora}</strong> &nbsp;|&nbsp;
                    🌡 <strong>${alt}m altitude</strong><br>
                    ⚡ Velocidade: <strong>${vel} km/h</strong><br>
                    📢 <em>${desc}</em><br>
                    🛸 Vela: <small>${p.glider}</small><br>
                    🪢 Selete: <small>${p.selete}</small>
                </div>
            `;

            const m = L.marker([lat, lng], { icon })
                .addTo(leafMap)
                .bindPopup(popup, { maxWidth: 260 })
                .on('click', () => {
                    document.getElementById('tele-box').innerHTML = `
                        <strong style="color:${cor}">${p.piloto}</strong> ${p.pais}<br>
                        🕐 ${hora} | 🌡 Alt: <strong>${alt}m</strong> | ⚡ <strong>${vel} km/h</strong><br>
                        📢 <em>${desc}</em><br>
                        🛸 ${p.glider} · ${p.selete}
                    `;
                });

            layers.markers.push(m);
        });
    }

    function preencherStats(n, p) {
        document.getElementById('stats-p'+n).style.display='block';
        const f={nome:p.piloto+' '+p.pais,pos:p.posicao,dist:p.distancia,alt:p.alt_max,dur:p.duracao,vel:p.vel_media,glider:p.glider,selete:p.selete,reserva:p.reserva,inst:p.instrumento};
        Object.entries(f).forEach(([k,v])=>{ const e=document.getElementById('sp'+n+'-'+k); if(e) e.textContent=v; });
    }

    // ── Controles de Mapa ──────────────────────────────────────
    function mapView(lat, lng, zoom) {
        if(leafMap) leafMap.setView([lat,lng], zoom, { animate:true, duration:1.2 });
    }

    function userLocation() {
        if(!navigator.geolocation){ alert('Geolocalização não disponível.'); return; }
        navigator.geolocation.getCurrentPosition(pos => {
            const {latitude:lat, longitude:lng} = pos.coords;
            L.marker([lat,lng], {
                icon: L.divIcon({
                    html:'<div style="background:#2196F3;border:3px solid #fff;width:20px;height:20px;border-radius:50%;box-shadow:0 2px 8px rgba(0,0,0,.5)"></div>',
                    className:'',iconAnchor:[10,10]
                })
            }).addTo(leafMap).bindPopup('📡 Você está aqui!').openPopup();
            leafMap.setView([lat,lng], 14, {animate:true});
        }, ()=>{ alert('Não foi possível obter sua localização.'); });
    }
    </script>
    <?php
    return ob_get_clean();
}
