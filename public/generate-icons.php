<?php
/**
 * Generates bright, crisp Enkrix PWA icons.
 * Run: php public/generate-icons.php
 */

function star5(GdImage $img, int $cx, int $cy, int $r, int $col): void
{
    $pts = [];
    for ($i = 0; $i < 5; $i++) {
        $a  = deg2rad($i * 72 - 90);
        $a2 = deg2rad($i * 72 - 54);
        $pts[] = (int)($cx + $r       * cos($a));
        $pts[] = (int)($cy + $r       * sin($a));
        $pts[] = (int)($cx + $r * 0.42 * cos($a2));
        $pts[] = (int)($cy + $r * 0.42 * sin($a2));
    }
    imagefilledpolygon($img, $pts, $col);
}

function makeIcon(int $size): void
{
    $img   = imagecreatetruecolor($size, $size);
    $s     = $size / 192.0;

    // ── Colours ──────────────────────────────────────────────
    $bg    = imagecolorallocate($img,  13,  13,  13); // near-black
    $gold  = imagecolorallocate($img, 212, 168,  83); // #D4A853
    $goldL = imagecolorallocate($img, 248, 225, 130); // bright highlight
    $goldD = imagecolorallocate($img, 155, 110,  25); // deep gold
    $blk   = imagecolorallocate($img,   8,   8,   8);

    imagefill($img, 0, 0, $bg);

    // ── Gold border ──────────────────────────────────────────
    $p = max(2, (int)(9 * $s));
    imagerectangle($img, $p,   $p,   $size-$p-1,   $size-$p-1,   $gold);
    imagerectangle($img, $p+3, $p+3, $size-$p-4,   $size-$p-4,   $goldL);

    // ── 3 stars ──────────────────────────────────────────────
    $cy = (int)(40 * $s);
    $cx = (int)($size / 2);
    star5($img, $cx - (int)(28*$s), $cy + (int)(5*$s), (int)(8*$s),  $goldD);
    star5($img, $cx,                $cy,                (int)(13*$s), $goldL);
    star5($img, $cx + (int)(28*$s), $cy + (int)(5*$s), (int)(8*$s),  $goldD);

    // ── Crown body ───────────────────────────────────────────
    $ccx = $cx;
    $ccy = (int)(108 * $s);
    $cw  = (int)(62  * $s);
    $ch  = (int)(44  * $s);

    $crownPts = [
        $ccx - $cw,            $ccy + $ch,
        $ccx - $cw + (int)(5*$s), $ccy - (int)(8*$s),
        $ccx - (int)(30*$s),   $ccy + (int)(16*$s),
        $ccx - (int)(14*$s),   $ccy - $ch + (int)(4*$s),
        $ccx,                  $ccy + (int)(10*$s),
        $ccx + (int)(14*$s),   $ccy - $ch + (int)(4*$s),
        $ccx + (int)(30*$s),   $ccy + (int)(16*$s),
        $ccx + $cw - (int)(5*$s), $ccy - (int)(8*$s),
        $ccx + $cw,            $ccy + $ch,
    ];
    imagefilledpolygon($img, $crownPts, $gold);

    // Crown top orb
    $orb = max(3, (int)(6*$s));
    imagefilledellipse($img, $ccx, $ccy - $ch + (int)(4*$s), $orb*2, $orb*2, $goldL);

    // Crown band
    $bT = $ccy + $ch;
    $bB = $bT + (int)(17*$s);
    imagefilledrectangle($img, $ccx - $cw - (int)(2*$s), $bT,
                                $ccx + $cw + (int)(2*$s), $bB, $goldL);

    // Gems on band
    if ($size >= 48) {
        $gr = max(2, (int)(4*$s));
        $gy = (int)(($bT + $bB) / 2);
        foreach ([-1, 0, 1] as $gi) {
            $gx = $ccx + $gi * (int)(22*$s);
            imagefilledellipse($img, $gx, $gy, $gr*2+2, $gr*2+2, $blk);
            imagefilledellipse($img, $gx, $gy, $gr, $gr, $gold);
        }
    }

    // ── Laurel leaves ────────────────────────────────────────
    if ($size >= 48) {
        $lw = (int)(22 * $s);
        $lh = (int)(9  * $s);
        $base = $ccx - $cw - (int)(4*$s);
        $leaves = [
            [$base,                 $ccy + (int)(34*$s)],
            [$base - (int)(14*$s),  $ccy + (int)(12*$s)],
            [$base - (int)(18*$s),  $ccy - (int)(12*$s)],
            [$base - (int)(12*$s),  $ccy - (int)(32*$s)],
        ];
        foreach ($leaves as [$lx, $ly]) {
            imagefilledellipse($img, $lx, $ly, $lw, $lh, $gold);
            imagefilledellipse($img, $size - $lx, $ly, $lw, $lh, $gold);
        }
        // Bottom knot
        imagefilledellipse($img, $ccx, $bB + (int)(8*$s), (int)(22*$s), (int)(9*$s), $gold);
    }

    // ── "E" lettermark (single large letter for clarity) ─────
    // For small icons, just show the crown. For larger ones, add text.
    if ($size >= 192) {
        $label = 'ENKRIX';
        $fw    = imagefontwidth(5);
        $fh    = imagefontheight(5);
        $lw    = $fw * strlen($label);

        $sc  = (int)(($size * 0.48) / max(1, $lw));
        $sc  = max(1, min($sc, 6));

        if ($sc >= 2) {
            $tmp   = imagecreatetruecolor($lw + 4, $fh + 4);
            $tbg   = imagecolorallocate($tmp, 0, 0, 1);
            imagefill($tmp, 0, 0, $tbg);
            $tg    = imagecolorallocate($tmp, 248, 225, 130);
            imagestring($tmp, 5, 2, 2, $label, $tg);
            $scW   = ($lw + 4) * $sc;
            $scH   = ($fh + 4) * $sc;
            $big   = imagescale($tmp, $scW, $scH, IMG_NEAREST_NEIGHBOUR);
            $tx    = (int)(($size - $scW) / 2);
            $ty    = $bB + (int)(20 * $s);
            if ($ty + $scH < $size - (int)(20*$s)) {
                imagecopy($img, $big, $tx, $ty, 0, 0, $scW, $scH);
            }
            imagedestroy($tmp);
            imagedestroy($big);
        }
    }

    $path = __DIR__ . "/icon-{$size}.png";
    imagepng($img, $path, 2);
    imagedestroy($img);
    echo "icon-{$size}.png  " . round(filesize($path)/1024, 1) . " KB\n";
}

foreach ([16, 32, 48, 192, 512] as $sz) {
    makeIcon($sz);
}
echo "All icons generated.\n";
