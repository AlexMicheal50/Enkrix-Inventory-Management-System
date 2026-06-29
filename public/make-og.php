<?php
/**
 * Generates a proper branded OG image for Enkrix IMS.
 * Run: php public/make-og.php
 */
$w = 1200; $h = 630;
$img = imagecreatetruecolor($w, $h);

// ── Colors ──────────────────────────────────────────────────
$cBlack   = imagecolorallocate($img,   6,   6,   6);
$cCard    = imagecolorallocate($img,  17,  17,  17);
$cGold    = imagecolorallocate($img, 212, 168,  83);
$cGoldL   = imagecolorallocate($img, 240, 216, 128);
$cGoldD   = imagecolorallocate($img, 184, 146,  42);
$cWhite   = imagecolorallocate($img, 245, 245, 245);
$cGray    = imagecolorallocate($img, 160, 160, 160);
$cDimGray = imagecolorallocate($img, 100, 100, 100);
$cBorder  = imagecolorallocate($img,  40,  40,  40);

// ── Background ───────────────────────────────────────────────
imagefill($img, 0, 0, $cBlack);

// ── Gold outer border ────────────────────────────────────────
imagerectangle($img, 12, 12, $w-13, $h-13, $cGoldD);
imagerectangle($img, 20, 20, $w-21, $h-21, $cGold);

// ── Dark card panel (right side content area) ─────────────────
imagefilledrectangle($img, 480, 60, $w-60, $h-60, $cCard);
imagerectangle($img, 480, 60, $w-60, $h-60, $cBorder);

// ── Gold vertical divider ────────────────────────────────────
imagefilledrectangle($img, 476, 60, 480, $h-60, $cGoldD);

// ── EMBLEM (left panel, centred around x=240, y=280) ─────────
$cx = 240; $cy = 268;

// Crown shape
$crownPts = [
    $cx-95, $cy+55,   // bottom-left
    $cx-92, $cy-10,   // left peak base
    $cx-60, $cy+25,   // inner-left
    $cx-28, $cy-52,   // left peak top
    $cx,    $cy+15,   // centre dip
    $cx+28, $cy-52,   // right peak top
    $cx+60, $cy+25,   // inner-right
    $cx+92, $cy-10,   // right peak base
    $cx+95, $cy+55,   // bottom-right
];
imagefilledpolygon($img, $crownPts, $cGold);

// Crown base band
imagefilledrectangle($img, $cx-97, $cy+55, $cx+97, $cy+78, $cGoldL);

// Gems on band
foreach ([-35, 0, 35] as $ox) {
    imagefilledellipse($img, $cx+$ox, $cy+66, 14, 14, $cGoldD);
    imagefilledellipse($img, $cx+$ox, $cy+66, 7,  7,  $cCard);
}

// Crown orb top
imagefilledellipse($img, $cx, $cy-50, 18, 18, $cGoldL);

// 3 stars above crown (simplified as filled pentagons)
function drawStar($img, $cx, $cy, $r, $col) {
    $pts = [];
    for ($i = 0; $i < 5; $i++) {
        $a = deg2rad($i * 72 - 90);
        $pts[] = (int)($cx + $r * cos($a));
        $pts[] = (int)($cy + $r * sin($a));
        $a2 = deg2rad($i * 72 - 90 + 36);
        $pts[] = (int)($cx + ($r*0.42) * cos($a2));
        $pts[] = (int)($cy + ($r*0.42) * sin($a2));
    }
    imagefilledpolygon($img, $pts, $col);
}
drawStar($img, $cx - 60, $cy - 90, 14, $cGoldD);
drawStar($img, $cx,      $cy - 102, 20, $cGold);
drawStar($img, $cx + 60, $cy - 90, 14, $cGoldD);

// Laurel wreath — simple leaf rows
function drawLeaf($img, $cx, $cy, $angle, $col) {
    $rad = deg2rad($angle);
    $pts = [];
    $lx = (int)($cx + 24 * cos($rad));
    $ly = (int)($cy + 24 * sin($rad));
    $perp = $rad + M_PI/2;
    $px = (int)(8 * cos($perp));
    $py = (int)(8 * sin($perp));
    $pts = [$cx, $cy, $lx+$px, $ly+$py, $lx, $ly, $lx-$px, $ly-$py];
    imagefilledpolygon($img, $pts, $col);
}

// Left branch
$leftLeaves = [[-30,285,-155],[-10,270,-135],[10,262,-110],[30,262,-88],[50,270,-65],[65,284,-45]];
foreach ($leftLeaves as [$ox, $oy, $ang]) {
    drawLeaf($img, $cx+$ox-60, $oy, $ang, $cGold);
}
// Right branch
$rightLeaves = [[-30,285,-25],[-10,270,-45],[10,262,-70],[30,262,-92],[50,270,-115],[65,284,-135]];
foreach ($rightLeaves as [$ox, $oy, $ang]) {
    drawLeaf($img, $cx-$ox+60, $oy, $ang, $cGold);
}

// Bottom stem knot
imagefilledellipse($img, $cx, $cy+100, 30, 12, $cGold);

// ── TEXT (right panel) ────────────────────────────────────────

// Load a font — use GD's built-in fonts (no TTF needed)
// We'll draw chunky text using imagestring with largest font
$rightX = 520;

// "ENKRIX" — large, using GD font 5 repeated for bold effect
$font5W = imagefontwidth(5);
$font5H = imagefontheight(5);

$label = 'ENKRIX';
$lw    = $font5W * strlen($label);
$scale = 5; // We'll draw it multiple times at offsets for bold

// Draw "ENKRIX" very large using scaled rendering
// Since GD doesn't support TTF fallback, let's draw it as big as possible
// by drawing the text multiple times at pixel offsets
$textY = 190;
$textX = $rightX + 20;

// Shadow/depth
for ($dx = 0; $dx <= 3; $dx++) {
    for ($dy = 0; $dy <= 3; $dy++) {
        imagestring($img, 5, $textX + $dx, $textY + $dy, $label, $cGoldD);
    }
}
// Main text
imagestring($img, 5, $textX, $textY, $label, $cGoldL);

// Draw "ENKRIX" bigger by using a loop (GD doesn't scale built-in fonts easily)
// Let's use imagescale to make a larger version
$chunk = imagecreatetruecolor($font5W * strlen($label) + 4, $font5H + 4);
$chunkBg = imagecolorallocate($chunk, 1, 1, 1);
imagecolortransparent($chunk, $chunkBg);
imagefill($chunk, 0, 0, $chunkBg);
$chunkGold = imagecolorallocate($chunk, 240, 216, 128);
imagestring($chunk, 5, 2, 2, $label, $chunkGold);
$bigLabel = imagescale($chunk, ($font5W * strlen($label) + 4) * 6, ($font5H + 4) * 6, IMG_NEAREST_NEIGHBOUR);
imagecopy($img, $bigLabel, $textX, 140, 0, 0, imagesx($bigLabel), imagesy($bigLabel));
imagedestroy($chunk);
imagedestroy($bigLabel);

// Thin gold divider line under ENKRIX
$lineY = 270;
imagefilledrectangle($img, $rightX+20, $lineY, $w-80, $lineY+2, $cGoldD);

// "INVENTORY MANAGEMENT SYSTEM"
$sub1 = 'INVENTORY MANAGEMENT SYSTEM';
$s1w  = imagefontwidth(4) * strlen($sub1);
// Scaled up x3
$c2 = imagecreatetruecolor($s1w + 4, imagefontheight(4) + 4);
$c2bg = imagecolorallocate($c2, 1, 1, 1);
imagefill($c2, 0, 0, $c2bg);
$c2g = imagecolorallocate($c2, 212, 168, 83);
imagestring($c2, 4, 2, 2, $sub1, $c2g);
$bigSub1 = imagescale($c2, ($s1w + 4) * 2, (imagefontheight(4) + 4) * 2, IMG_NEAREST_NEIGHBOUR);
imagecopy($img, $bigSub1, $textX, 288, 0, 0, imagesx($bigSub1), imagesy($bigSub1));
imagedestroy($c2);
imagedestroy($bigSub1);

// "DRIPPING IN ROYALTY"
$sub2 = 'DRIPPING IN ROYALTY';
$s2w  = imagefontwidth(3) * strlen($sub2);
$c3 = imagecreatetruecolor($s2w + 4, imagefontheight(3) + 4);
$c3bg = imagecolorallocate($c3, 1, 1, 1);
imagefill($c3, 0, 0, $c3bg);
$c3g = imagecolorallocate($c3, 160, 130, 60);
imagestring($c3, 3, 2, 2, $sub2, $c3g);
$bigSub2 = imagescale($c3, ($s2w + 4) * 2, (imagefontheight(3) + 4) * 2, IMG_NEAREST_NEIGHBOUR);
imagecopy($img, $bigSub2, $textX, 360, 0, 0, imagesx($bigSub2), imagesy($bigSub2));
imagedestroy($c3);
imagedestroy($bigSub2);

// Second divider
imagefilledrectangle($img, $rightX+20, 418, $w-80, 419, $cBorder);

// "Powered by Avolution AI LTD"
imagestring($img, 2, $textX, 438, 'Powered by Avolution AI LTD', $cDimGray);

// URL
imagestring($img, 2, $textX, 460, 'enkrix.avolutionai.com', $cGoldD);

// ── Save ─────────────────────────────────────────────────────
$outPath = __DIR__ . '/og.png';
imagepng($img, $outPath, 6);
imagedestroy($img);
echo "Generated: $outPath (" . round(filesize($outPath)/1024, 1) . " KB)\n";
