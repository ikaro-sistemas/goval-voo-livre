<?php
// ============================================================================
// SHORTCODE: MAPA 3D AVANÇADO - Dual Pilot/Year Selector + Telemetria (2020-2026)
// Uso no Elementor: [goval_3d_map]
// ============================================================================
add_shortcode('goval_3d_map', 'goval_3d_map_shortcode');
function goval_3d_map_shortcode($atts) {
    require_once get_template_directory() . '/map3d-data.php';
    $db_voos = goval_get_flight_database();
    $json_db = json_encode($db_voos, JSON_UNESCAPED_UNICODE);
    $anos    = array_keys($db_voos);
    rsort($anos);
    ob_start(); ?>
    <style>
        .mapa-3d-wrapper{display:flex;height:88vh;min-height:640px;font-family:'Inter',sans-serif;border-radius:12px;overflow:hidden;box-shadow:0 8px 30px rgba(0,0,0,.25)}
        .mapa-sidebar{width:370px;min-width:300px;background:#0f1c14;color:#eee;padding:18px;border-right:2px solid #1a3325;overflow-y:auto;display:flex;flex-direction:column;gap:10px}
        .mapa-sidebar h2{color:#55c98a;margin:0;font-size:.95em;text-transform:uppercase;letter-spacing:.5px}
        .mapa-container-gl{flex-grow:1;position:relative;background:#000}
        .pilot-block{background:rgba(255,255,255,.05);border-radius:8px;padding:12px;border-left:4px solid #00cc66}
        .pilot-block.p2{border-left-color:#FFB81C}
        .pilot-block label{font-size:.72em;color:#aaa;text-transform:uppercase;letter-spacing:1px;display:block;margin-bottom:3px}
        .pilot-block select{width:100%;padding:8px;border-radius:6px;border:1px solid #2a4030;background:#162a1e;color:white;font-size:.88em;margin-top:2px}
        .sim-btn{padding:12px;background:linear-gradient(135deg,#007A53,#00cc66);color:white;border:none;font-weight:bold;cursor:pointer;border-radius:8px;font-size:.92em;width:100%;transition:opacity .2s}
        .sim-btn:hover{opacity:.85}
        #sim-detalhes{background:rgba(255,255,255,.04);border-radius:8px;padding:12px;min-height:80px;font-size:.78em;line-height:1.7;color:#ccc;border:1px solid #1a3325}
        #sim-detalhes .tl{color:#55c98a;font-weight:bold}
        .p-stats{font-size:.76em;background:rgba(255,255,255,.04);border-radius:8px;padding:10px;display:none}
        .p-stats h4{margin:0 0 5px;color:#55c98a}
        .p-stats.p2 h4{color:#FFB81C}
        .sr{display:flex;justify-content:space-between;border-bottom:1px solid #1a3325;padding:3px 0}
        .sr span:last-child{color:#fff;font-weight:500;text-align:right;max-width:58%}
        .cam-controls{display:flex;flex-wrap:wrap;gap:5px}
        .cam-btn{flex:1;min-width:75px;padding:7px 4px;background:#1a3325;color:#ccc;border:1px solid #2a4a30;border-radius:6px;cursor:pointer;font-size:.72em;text-align:center;transition:background .2s}
        .cam-btn:hover{background:#007A53;color:white}
        .clabel{font-size:.7em;color:#66aa88;text-transform:uppercase;letter-spacing:1px;margin:2px 0 1px}
        @media(max-width:850px){.mapa-3d-wrapper{flex-direction:column;height:auto}.mapa-sidebar{width:100%;max-height:48vh}.mapa-container-gl{height:52vh}}
    </style>

    <div class="mapa-3d-wrapper">
        <!-- ══════════ SIDEBAR ══════════ -->
        <div class="mapa-sidebar">
            <h2>🏔 Telemetria 3D · Ibituruna</h2>

            <div class="pilot-block p1">
                <label>🟢 Piloto 1 + Ano</label>
                <select id="sel-ano1" onchange="atualizarPilotos('1')">
                    <?php foreach($anos as $a): ?><option value="<?= esc_attr($a) ?>"><?= esc_html($a) ?></option><?php endforeach; ?>
                </select>
                <select id="sel-piloto1" style="margin-top:6px"></select>
            </div>

            <div class="pilot-block p2">
                <label>🟡 Piloto 2 + Ano</label>
                <select id="sel-ano2" onchange="atualizarPilotos('2')">
                    <?php foreach($anos as $a): ?><option value="<?= esc_attr($a) ?>" <?php if($a==$anos[1]) echo 'selected'; ?>><?= esc_html($a) ?></option><?php endforeach; ?>
                </select>
                <select id="sel-piloto2" style="margin-top:6px"></select>
            </div>

            <button class="sim-btn" onclick="renderizarVoos()">▶ RENDERIZAR COMPARATIVO</button>

            <div id="stats-p1" class="p-stats">
                <h4>🟢 <span id="sp1-nome"></span></h4>
                <?php foreach(['pos'=>'Posição','dist'=>'Distância','alt'=>'Alt. Máxima','dur'=>'Duração','vel'=>'Vel. Média','glider'=>'Vela','selete'=>'Selete','reserva'=>'Reserva','inst'=>'Instrumento'] as $k=>$lbl): ?>
                <div class="sr"><span><?= $lbl ?></span><span id="sp1-<?= $k ?>"></span></div>
                <?php endforeach; ?>
            </div>
            <div id="stats-p2" class="p-stats p2">
                <h4>🟡 <span id="sp2-nome"></span></h4>
                <?php foreach(['pos'=>'Posição','dist'=>'Distância','alt'=>'Alt. Máxima','dur'=>'Duração','vel'=>'Vel. Média','glider'=>'Vela','selete'=>'Selete','reserva'=>'Reserva','inst'=>'Instrumento'] as $k=>$lbl): ?>
                <div class="sr"><span><?= $lbl ?></span><span id="sp2-<?= $k ?>"></span></div>
                <?php endforeach; ?>
            </div>

            <div class="clabel">📍 Telemetria do Ponto</div>
            <div id="sim-detalhes">Passe o mouse sobre um ponto do voo para ver dados em tempo real.</div>

            <div class="clabel">🎮 Controles de Câmera 3D</div>
            <div class="cam-controls">
                <button class="cam-btn" onclick="setCam(65,45,12.5)">🛰 Aérea</button>
                <button class="cam-btn" onclick="setCam(80,-20,11.5)">🏔 Lateral 3D</button>
                <button class="cam-btn" onclick="setCam(85,0,11.0)">📍 Frontal</button>
                <button class="cam-btn" onclick="setCam(0,0,12.0)">🗺 Top 2D</button>
                <button class="cam-btn" onclick="setCam(89,90,10.0)">🌿 Nível Chão</button>
                <button class="cam-btn" onclick="modoCinematico()">🎬 Cinemático</button>
            </div>
        </div>

        <!-- ══════════ MAP CANVAS ══════════ -->
        <div id="mapContainerGL" class="mapa-container-gl"></div>
    </div>

    <link href="https://unpkg.com/maplibre-gl@3.6.2/dist/maplibre-gl.css" rel="stylesheet"/>
    <script src="https://unpkg.com/maplibre-gl@3.6.2/dist/maplibre-gl.js"></script>
    <script src="https://unpkg.com/deck.gl@8.9.0/dist.min.js"></script>

    <script>
    const DB=<?php echo $json_db; ?>;
    let gmapObj=null,deckOverlay=null,cinematicoInterval=null;

    /* ── Preenche select de pilotos conforme o ano escolhido ── */
    function atualizarPilotos(num){
        const ano=document.getElementById('sel-ano'+num).value;
        const sel=document.getElementById('sel-piloto'+num);
        sel.innerHTML='';
        (DB[ano]||[]).forEach((p,i)=>{
            const o=document.createElement('option');
            o.value=i;
            o.textContent=p.piloto+' — '+p.posicao+' · '+p.pais;
            sel.appendChild(o);
        });
        if(num==='2'&&sel.options.length>1) sel.selectedIndex=1;
    }

    document.addEventListener('DOMContentLoaded',()=>{
        atualizarPilotos('1');
        const a2=document.getElementById('sel-ano2');
        if(a2.options.length>1) a2.selectedIndex=1;
        atualizarPilotos('2');
    });

    /* ── Inicia o mapa centrado no Pico da Ibituruna exato ── */
    setTimeout(()=>{
        gmapObj=new maplibregl.Map({
            container:'mapContainerGL',
            style:{
                version:8,
                sources:{
                    'gsat':{type:'raster',tiles:['https://mt1.google.com/vt/lyrs=y&x={x}&y={y}&z={z}'],tileSize:256},
                    'aterr':{type:'raster-dem',tiles:['https://s3.amazonaws.com/elevation-tiles-prod/terrarium/{z}/{x}/{y}.png'],encoding:'terrarium',tileSize:256}
                },
                layers:[{id:'sat',type:'raster',source:'gsat'}]
            },
            // Coordenadas GPS reais do Pico da Ibituruna, GV - MG, Brasil
            center:[-41.9437,-18.8819],
            zoom:12.5,pitch:65,bearing:45,maxPitch:89
        });
        gmapObj.addControl(new maplibregl.NavigationControl({visualizePitch:true}),'top-right');
        gmapObj.addControl(new maplibregl.ScaleControl(),'bottom-right');
        gmapObj.on('load',()=>gmapObj.setTerrain({source:'aterr',exaggeration:1.6}));
        deckOverlay=new deck.MapboxOverlay({interleaved:true,layers:[]});
        gmapObj.addControl(deckOverlay);
    },600);

    /* ── Renderiza as linhas e pontos de ambos os pilotos ── */
    function renderizarVoos(){
        if(!gmapObj){alert('Mapa carregando, aguarde 2s.');return;}
        const ano1=document.getElementById('sel-ano1').value,idx1=parseInt(document.getElementById('sel-piloto1').value);
        const ano2=document.getElementById('sel-ano2').value,idx2=parseInt(document.getElementById('sel-piloto2').value);
        const p1=DB[ano1]&&DB[ano1][idx1],p2=DB[ano2]&&DB[ano2][idx2];
        if(!p1||!p2)return;
        preencherStats('1',p1);preencherStats('2',p2);

        const mkPath=p=>p.path.map(pt=>[pt[0],pt[1],pt[2]]);
        const pontos=[];
        const mkPts=(p,cor)=>p.path.forEach(pt=>pontos.push({
            pos:[pt[0],pt[1],pt[2]],color:cor,
            piloto:p.piloto,pais:p.pais,glider:p.glider,
            hora:pt[3],vel:pt[4],desc:pt[5]
        }));
        mkPts(p1,[0,210,100,230]);
        mkPts(p2,[255,184,28,230]);

        /* PathLayer: linhas com geometria real (curvas por pontos GPS distintos) */
        const linesLayer=new deck.PathLayer({
            id:'linhas-voo',
            data:[{path:mkPath(p1),color:[0,200,90]},{path:mkPath(p2),color:[255,184,28]}],
            getPath:d=>d.path,getColor:d=>d.color,
            getWidth:25,widthUnits:'meters',widthMinPixels:3
        });

        /* ScatterplotLayer: pontos 3D com telemetria ao hover */
        const dotsLayer=new deck.ScatterplotLayer({
            id:'pontos-voo',data:pontos,
            getPosition:d=>d.pos,getFillColor:d=>d.color,
            getRadius:55,radiusUnits:'meters',pickable:true,
            onHover:({object})=>{
                const el=document.getElementById('sim-detalhes');
                if(object){
                    el.innerHTML=`<span class="tl">${object.piloto}</span> ${object.pais}<br>
                        🕐 <strong>${object.hora}</strong> &nbsp;|&nbsp;
                        🌡 Alt: <strong>${object.pos[2]}m</strong><br>
                        ⚡ Vel: <strong>${object.vel} km/h</strong><br>
                        📢 <em>${object.desc}</em><br>
                        🛸 <small>${object.glider}</small>`;
                }else{el.textContent='Passe o mouse sobre um ponto de voo.';}
            }
        });

        deckOverlay.setProps({layers:[linesLayer,dotsLayer]});
        /* Câmera desliza panoramicamente pela rota completa */
        gmapObj.flyTo({center:[-41.7800,-18.7600],zoom:10.5,pitch:72,bearing:-30,duration:4500});
    }

    /* ── Preenchimento do painel de estatísticas ── */
    function preencherStats(n,p){
        document.getElementById('stats-p'+n).style.display='block';
        document.getElementById('sp'+n+'-nome').textContent=p.piloto+' '+p.pais;
        document.getElementById('sp'+n+'-pos').textContent=p.posicao;
        document.getElementById('sp'+n+'-dist').textContent=p.distancia;
        document.getElementById('sp'+n+'-alt').textContent=p.alt_max;
        document.getElementById('sp'+n+'-dur').textContent=p.duracao;
        document.getElementById('sp'+n+'-vel').textContent=p.vel_media;
        document.getElementById('sp'+n+'-glider').textContent=p.glider;
        document.getElementById('sp'+n+'-selete').textContent=p.selete;
        document.getElementById('sp'+n+'-reserva').textContent=p.reserva;
        document.getElementById('sp'+n+'-inst').textContent=p.instrumento;
    }

    /* ── Controles de câmera 3D (pitch/bearing/zoom animados) ── */
    function setCam(pitch,bearing,zoom){
        if(!gmapObj)return;
        gmapObj.easeTo({pitch,bearing,zoom,duration:1500});
    }

    /* ── Modo cinemático: rotação 360° contínua ao redor das rotas ── */
    function modoCinematico(){
        if(!gmapObj)return;
        clearInterval(cinematicoInterval);
        let b=gmapObj.getBearing();
        gmapObj.easeTo({pitch:75,zoom:11.0,duration:1000});
        cinematicoInterval=setInterval(()=>{b=(b+0.4)%360;gmapObj.setBearing(b);},50);
    }
    </script>
    <?php
    return ob_get_clean();
}
