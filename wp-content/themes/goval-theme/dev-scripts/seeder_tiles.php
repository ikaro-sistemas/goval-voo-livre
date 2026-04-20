<?php
/**
 * seeder_tiles.php — Pre-baixa tiles (Satélite e Terreno 3D) para a região de GV.
 * Abrange o Pico da Ibituruna e arredores para funcionamento offline.
 * Execute via CLI: php wp-content/themes/goval-theme/seeder_tiles.php
 */

require_once('wp-load.php');

function latLngToTile($lat, $lng, $zoom) {
    $n = 2 ** $zoom;
    $x = intval(floor(($lng + 180) / 360 * $n));
    $y = intval(floor((1 - log(tan(deg2rad($lat)) + 1/cos(deg2rad($lat))) / M_PI) / 2 * $n));
    return [$x, $y];
}

// Bounding box: Governador Valadares e arredores
$lat_max = -18.70; $lat_min = -19.00;
$lng_min = -42.10; $lng_max = -41.80;

$theme_path = get_template_directory();

function downloadLocal($type, $z, $x, $y, $theme_path) {
    $ext = ($type === 'terrain' ? 'png' : 'jpg');
    $cache_dir = $theme_path . "/tile-cache/{$type}/{$z}/{$x}/";
    $cache_file = $cache_dir . "{$y}.{$ext}";

    if (file_exists($cache_file)) return 'exists';

    // Chama o proxy local internamente ou via HTTP
    $proxy_url = get_template_directory_uri() . "/tile-proxy.php?type={$type}&z={$z}&x={$x}&y={$y}";
    
    $ctx = stream_context_create(['http' => ['timeout' => 15]]);
    $data = @file_get_contents($proxy_url, false, $ctx);

    if ($data) {
        // O proxy já salvou no disco, mas retornamos o status
        return 'downloaded';
    }
    return 'failed';
}

echo "=== SEEDER OFFLINE: SATÉLITE + TERRENO 3D ===\n";

for ($z = 10; $z <= 14; $z++) {
    [$x1, $y1] = latLngToTile($lat_max, $lng_min, $z);
    [$x2, $y2] = latLngToTile($lat_min, $lng_max, $z);
    
    echo "Zoom {$z}: x[{$x1}-{$x2}], y[{$y1}-{$y2}]\n";

    for ($x = $x1; $x <= $x2; $x++) {
        for ($y = $y1; $y <= $y2; $y++) {
            downloadLocal('sat', $z, $x, $y, $theme_path);
            downloadLocal('terrain', $z, $x, $y, $theme_path);
        }
    }
}

echo "Seeding concluído para a região de Governador Valadares.\n";
