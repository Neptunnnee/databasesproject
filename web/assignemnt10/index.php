<?php
function h($s) {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

function getClientIp(): string {
    $candidates = [
        'HTTP_CLIENT_IP',
        'HTTP_X_FORWARDED_FOR',
        'HTTP_X_FORWARDED',
        'HTTP_X_REAL_IP',
        'REMOTE_ADDR',
    ];

    foreach ($candidates as $key) {
        if (!empty($_SERVER[$key])) {
            $parts = explode(',', $_SERVER[$key]);
            foreach ($parts as $ip) {
                $ip = trim($ip);
                if (filter_var(
                    $ip,
                    FILTER_VALIDATE_IP,
                    FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
                )) {
                    return $ip;
                }
            }
        }
    }
    return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
}

$ip = getClientIp();

$lat = 53.1700;
$lon = 8.6510;
$city = '';
$region = '';
$country = '';
$geo_ok = false;
$error_message = '';

$apiUrl = 'https://ipinfo.io/' . urlencode($ip) . '/json';

$opts = [
    'http' => [
        'timeout' => 2,
        'ignore_errors' => true,
    ],
];
$context = stream_context_create($opts);

$response = @file_get_contents($apiUrl, false, $context);
if ($response !== false) {
    $data = json_decode($response, true);
    if (isset($data['loc'])) {
        $locParts = explode(',', $data['loc']);
        if (count($locParts) === 2) {
            $lat = (float)$locParts[0];
            $lon = (float)$locParts[1];
            $city = $data['city'] ?? '';
            $region = $data['region'] ?? '';
            $country = $data['country'] ?? '';
            $geo_ok = true;
        } else {
            $error_message = 'Unexpected coordinate format from geolocation service.';
        }
    } else {
        $error_message = 'Geolocation service did not return coordinates for this IP.';
    }
} else {
    $error_message = 'Could not contact ipinfo.io geolocation service.';
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>A10 – Client Location via Linked Services</title>
    <link
        rel="stylesheet"
        href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-sA+4J08z8JbG2kJrTN3h7x1LXYhOg2iWwKw0pQtA1r0="
        crossorigin=""
    />
    <style>
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            margin: 1.5rem;
        }
        #map {
            height: 480px;
            max-width: 900px;
            border: 1px solid #ccc;
            margin-top: 1rem;
        }
        .note {
            max-width: 900px;
        }
    </style>
</head>
<body>
<h2>A10 – Linked Services: Client Region</h2>

<p class="note">
    This page determines your IP address, sends it to a geolocation service
    (<code>ipinfo.io</code>), and displays the resulting coordinates on an
    OpenStreetMap map rendered with Leaflet.
</p>

<p><strong>Detected IP address:</strong> <?= h($ip) ?></p>

<?php if ($geo_ok): ?>
    <p>
        <strong>Location from IP geolocation:</strong>
        <?= h($lat) ?>, <?= h($lon) ?>
        <?php if ($city || $region || $country): ?>
            (<?= h(trim("$city $region $country")) ?>)
        <?php endif; ?>
    </p>
<?php else: ?>
    <p>
        <strong>Note:</strong>
        Could not determine your coordinates from the IP address.
        Showing a default location (Constructor University campus) instead.
    </p>
    <?php if ($error_message): ?>
        <p><em><?= h($error_message) ?></em></p>
    <?php endif; ?>
<?php endif; ?>

<div id="map"></div>

<script
    src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-o9N1j7kGStb6kKpQbF6gR01CPTjHzu3eDqp3f3xX4o8="
    crossorigin=""
></script>

<script>
const clientIp = <?= json_encode($ip) ?>;
const lat      = <?= json_encode($lat) ?>;
const lon      = <?= json_encode($lon) ?>;

const map = L.map('map').setView([lat, lon], 11);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

const marker = L.marker([lat, lon]).addTo(map);
marker.bindPopup(
    'Client IP: ' + clientIp +
    '<br>Coordinates: ' + lat.toFixed(4) + ', ' + lon.toFixed(4)
).openPopup();
</script>
</body>
</html>
