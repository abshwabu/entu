<?php

$logoPath = __DIR__ . '/../public/brand/logo.png';
if (!file_exists($logoPath)) {
    die("Error: {$logoPath} not found\n");
}

$im = imagecreatefrompng($logoPath);
$width = imagesx($im);
$height = imagesy($im);

function getColorAt($im, $x, $y) {
    $rgb = imagecolorat($im, (int)$x, (int)$y);
    $r = ($rgb >> 16) & 0xFF;
    $g = ($rgb >> 8) & 0xFF;
    $b = ($rgb >> 0) & 0xFF;
    return [
        'hex' => sprintf("#%02X%02X%02X", $r, $g, $b),
        'rgb' => [$r, $g, $b],
    ];
}

$bg = getColorAt($im, 10, 10);

$cx = $width / 2.0;
$cy = $height / 2.0;
$radius = 380.0;
$u_par = 1.0 / sqrt(2.0); // along 135° (dx=+, dy=+)
$u_perp_x = -1.0 / sqrt(2.0); // perpendicular to 135°
$u_perp_y = 1.0 / sqrt(2.0);

// Sample points along 135° axis with a perpendicular offset where needed to avoid the white wordmark
// Point 1: Violet-deep (gradient start, t = 0.05)
$proj1 = -$radius + 2.0 * $radius * 0.05;
$c1 = getColorAt($im, $cx + $proj1 * $u_par, $cy + $proj1 * $u_par);

// Point 2: Violet (upper-mid, t = 0.33)
$proj2 = -$radius + 2.0 * $radius * 0.33;
// Offset perp by 120px to miss any letter accents
$c2 = getColorAt($im, $cx + $proj2 * $u_par + 120 * $u_perp_x, $cy + $proj2 * $u_par + 120 * $u_perp_y);

// Point 3: Purple (mid, t = 0.66)
$proj3 = -$radius + 2.0 * $radius * 0.66;
// Offset perp by 140px to miss the "CREATIVE" wordmark
$c3 = getColorAt($im, $cx + $proj3 * $u_par + 140 * $u_perp_x, $cy + $proj3 * $u_par + 140 * $u_perp_y);

// Point 4: Magenta (gradient end, t = 0.95)
$proj4 = -$radius + 2.0 * $radius * 0.95;
$c4 = getColorAt($im, $cx + $proj4 * $u_par, $cy + $proj4 * $u_par);

echo "=== Entu Creative Violet-Magenta Gradient Sampling ===\n";
echo "1. Violet-deep (gradient start) : {$c1['hex']} (RGB: {$c1['rgb'][0]}, {$c1['rgb'][1]}, {$c1['rgb'][2]})\n";
echo "2. Violet (upper-mid)           : {$c2['hex']} (RGB: {$c2['rgb'][0]}, {$c2['rgb'][1]}, {$c2['rgb'][2]})\n";
echo "3. Purple (mid)                 : {$c3['hex']} (RGB: {$c3['rgb'][0]}, {$c3['rgb'][1]}, {$c3['rgb'][2]})\n";
echo "4. Magenta (gradient end)       : {$c4['hex']} (RGB: {$c4['rgb'][0]}, {$c4['rgb'][1]}, {$c4['rgb'][2]})\n";
echo "5. Black (logo background)      : {$bg['hex']} (RGB: {$bg['rgb'][0]}, {$bg['rgb'][1]}, {$bg['rgb'][2]})\n";
echo "Gradient angle: 135°\n";

imagedestroy($im);
