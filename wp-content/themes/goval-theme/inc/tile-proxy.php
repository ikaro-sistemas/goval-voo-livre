<?php
/**
 * tile-proxy.php — Geoportal Offline Tile Proxy
 * Serve e faz cache local de tiles de Satélite e Elevação (Terrain-RGB).
 * URL: /tile-proxy.php?type=sat|terrain&z={z}&x={x}&y={y}
 */

header('Access-Control-Allow-Origin: *');
header('Cache-Control: public, max-age=604800'); // Cache de 1 semana

$type = isset($_GET['type']) ? $_GET['type'] : 'sat'; // 'sat' ou 'terrain'
$z = isset($_GET['z']) ? intval($_GET['z']) : null;
$x = isset($_GET['x']) ? intval($_GET['x']) : null;
$y = isset($_GET['y']) ? intval($_GET['y']) : null;

if ($z === null || $x === null || $y === null) {
    http_response_code(400); die('Params missing');
}

// Diretório de cache organizado por tipo (fora do tema para performance)
$cache_dir = dirname(__DIR__, 3) . "/uploads/tile-cache/{$type}/{$z}/{$x}/";
$cache_file = $cache_dir . $y . ($type === 'terrain' ? '.png' : '.jpg');

if (file_exists($cache_file)) {
    header('Content-Type: ' . ($type === 'terrain' ? 'image/png' : 'image/jpeg'));
    header('X-Proxy-Cache: HIT');
    readfile($cache_file);
    exit;
}

// URLs dos provedores remotos
$url = "";
if ($type === 'terrain') {
    // Terrarium Elevation Tiles (AWS S3 - Gratuito e Aberto)
    $url = "https://s3.amazonaws.com/elevation-tiles-prod/terrarium/{$z}/{$x}/{$y}.png";
} else {
    // ESRI World Imagery (Satélite)
    $url = "https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{$z}/{$y}/{$x}";
}

$ctx = stream_context_create([
    'http' => [
        'timeout' => 10,
        'user_agent' => 'GOVAL-Portal/1.1 (Offline Seeding)',
        'header' => "Referer: http://localhost\r\n"
    ]
]);

$data = @file_get_contents($url, false, $ctx);

// Fallback Satélite se ESRI falhar (OSM/Google)
if ($type === 'sat' && ($data === false || strlen($data) < 100)) {
    $s = ['a','b','c'][$x % 3];
    $url = "https://{$s}.tile.openstreetmap.org/{$z}/{$x}/{$y}.png";
    $data = @file_get_contents($url, false, $ctx);
}

if ($data === false || strlen($data) < 100) {
    http_response_code(503);
    die('Offline: Tile non-cached');
}

// Salva em cache para uso offline futuro
if (!is_dir($cache_dir)) mkdir($cache_dir, 0755, true);
file_put_contents($cache_file, $data);

header('Content-Type: ' . ($type === 'terrain' ? 'image/png' : 'image/jpeg'));
header('X-Proxy-Cache: MISS');
echo $data;
