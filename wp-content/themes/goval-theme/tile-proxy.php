<?php
/**
 * tile-proxy.php — Cache local de tiles OSM para o mapa offline
 * Serve tiles do OpenStreetMap e os salva em disco para uso offline.
 * URL: /wp-content/themes/goval-theme/tile-proxy.php?z={z}&x={x}&y={y}
 */

// Cabeçalhos CORS e tipo de imagem
header('Access-Control-Allow-Origin: *');
header('Cache-Control: public, max-age=86400');

$z = isset($_GET['z']) ? intval($_GET['z']) : null;
$x = isset($_GET['x']) ? intval($_GET['x']) : null;
$y = isset($_GET['y']) ? intval($_GET['y']) : null;

if ($z === null || $x === null || $y === null) {
    http_response_code(400); die('Params missing');
}

// Limitar zoom para economizar espaço (somente 9–15)
if ($z < 0 || $z > 18 || $x < 0 || $y < 0) {
    http_response_code(400); die('Invalid tile coords');
}

// Diretório de cache local
$cache_dir = __DIR__ . '/tile-cache/' . $z . '/' . $x . '/';
$cache_file = $cache_dir . $y . '.png';

// Se o tile já está em cache, serve direto
if (file_exists($cache_file)) {
    header('Content-Type: image/png');
    header('X-Tile-Cache: HIT');
    readfile($cache_file);
    exit;
}

// Caso contrário, baixa do OSM (ou servidores alternativos)
$servers = ['a', 'b', 'c'];
$s = $servers[$x % 3];

// Tenta satelite (ESRI)
$url = "https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{$z}/{$y}/{$x}";

$ctx = stream_context_create([
    'http' => [
        'timeout' => 8,
        'user_agent' => 'GOVAL-Portal-Offline-Map/1.0',
        'header' => "Referer: http://localhost\r\n"
    ]
]);

$data = @file_get_contents($url, false, $ctx);

if ($data === false || strlen($data) < 100) {
    // Fallback para OpenStreetMap
    $url = "https://{$s}.tile.openstreetmap.org/{$z}/{$x}/{$y}.png";
    $data = @file_get_contents($url, false, $ctx);
}

if ($data === false || strlen($data) < 100) {
    http_response_code(503);
    die('Tile unavailable offline');
}

// Salva em cache
if (!is_dir($cache_dir)) {
    mkdir($cache_dir, 0755, true);
}
file_put_contents($cache_file, $data);

header('Content-Type: image/png');
header('X-Tile-Cache: MISS');
echo $data;
