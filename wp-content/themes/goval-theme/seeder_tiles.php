<?php
/**
 * seeder_tiles.php — Pré-baixa tiles do mapa da região GV para cache local offline.
 * Baixa tiles da área ao redor do Pico da Ibituruna para zooms 9 a 14.
 * Execute: wp eval-file wp-content/themes/goval-theme/seeder_tiles.php --allow-root
 */
require_once('wp-load.php');

// Converte lat/lng para coords de tile XY
function latLngToTile($lat, $lng, $zoom) {
    $n = 2 ** $zoom;
    $x = intval(floor(($lng + 180) / 360 * $n));
    $y = intval(floor((1 - log(tan(deg2rad($lat)) + 1/cos(deg2rad($lat))) / M_PI) / 2 * $n));
    return [$x, $y];
}

// Bounding box da região (Governador Valadares + margem de 80km)
$lat_max = -17.8;  // Norte
$lat_min = -19.6;  // Sul
$lng_min = -42.8;  // Oeste
$lng_max = -41.0;  // Leste

$cache_base = get_template_directory() . '/tile-cache/';
$tile_proxy  = get_template_directory() . '/tile-proxy.php';

// URL ESRI (satélite) e OSM (fallback)
function downloadTile($z, $x, $y, $cache_base) {
    $cache_dir  = $cache_base . $z . '/' . $x . '/';
    $cache_file = $cache_dir . $y . '.png';

    if (file_exists($cache_file) && filesize($cache_file) > 100) {
        return 'cached';
    }

    // ESRI Satellite
    $url = "https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{$z}/{$y}/{$x}";
    $ctx = stream_context_create(['http' => ['timeout' => 12, 'user_agent' => 'GOVAL/1.0', 'header' => "Referer: http://localhost\r\n"]]);
    $data = @file_get_contents($url, false, $ctx);

    if (!$data || strlen($data) < 100) {
        // Fallback OSM
        $s = ['a','b','c'][$x % 3];
        $url_osm = "https://{$s}.tile.openstreetmap.org/{$z}/{$x}/{$y}.png";
        $data = @file_get_contents($url_osm, false, $ctx);
    }

    if ($data && strlen($data) > 100) {
        if (!is_dir($cache_dir)) mkdir($cache_dir, 0755, true);
        file_put_contents($cache_file, $data);
        return 'downloaded';
    }
    return 'failed';
}

$total = 0; $cached = 0; $downloaded = 0; $failed = 0;

echo "=== Seeder de Tiles Offline - Região GV ===\n";
echo "Bounding Box: lat [{$lat_min}, {$lat_max}] · lng [{$lng_min}, {$lng_max}]\n\n";

// Zooms 9-14 (visão geral até detalhe da Ibituruna)
for ($z = 9; $z <= 14; $z++) {
    [$x1, $y1] = latLngToTile($lat_max, $lng_min, $z);
    [$x2, $y2] = latLngToTile($lat_min, $lng_max, $z);

    $count_z = ($x2 - $x1 + 1) * ($y2 - $y1 + 1);
    echo "Zoom {$z}: {$count_z} tiles {$x1}-{$x2} x {$y1}-{$y2}\n";

    for ($x = $x1; $x <= $x2; $x++) {
        for ($y = $y1; $y <= $y2; $y++) {
            $result = downloadTile($z, $x, $y, $cache_base);
            $total++;
            if ($result === 'cached') $cached++;
            elseif ($result === 'downloaded') { $downloaded++; usleep(100000); } // 100ms entre downloads
            else $failed++;

            if ($total % 20 === 0) {
                echo "  [{$total}] baixados:{$downloaded} | cache:{$cached} | erro:{$failed}\n";
                flush();
            }
        }
    }
}

echo "\n=== CONCLUÍDO ===\n";
echo "Total: {$total} | Baixados: {$downloaded} | Cache: {$cached} | Erros: {$failed}\n";
echo "Cache armazenado em: {$cache_base}\n";
