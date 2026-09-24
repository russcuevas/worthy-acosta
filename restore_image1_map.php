<?php

$ring = json_decode(file_get_contents(__DIR__ . '/mariveles_true_land_ring.json'), true);

// Downsample ring to ~250 clean smooth points
$step = max(1, (int)(count($ring) / 250));
$downsampled = [];
for ($i = 0; $i < count($ring); $i += $step) {
    $downsampled[] = $ring[$i];
}
$downsampled[] = $downsampled[0];

$minLon = 120.350; $maxLon = 120.655;
$minLat = 14.345;  $maxLat = 14.515;
$svgWidth = 1000;
$svgHeight = 850;
$margin = 60;

$outerPts = [];
foreach ($downsampled as $p) {
    $x = $margin + (($p[0] - $minLon) / ($maxLon - $minLon)) * ($svgWidth - 2 * $margin);
    $y = $margin + (($maxLat - $p[1]) / ($maxLat - $minLat)) * ($svgHeight - 2 * $margin);
    $outerPts[] = [round($x, 1), round($y, 1)];
}

$barangays = [
    [
        "id" => 1,
        "name" => "ALION",
        "title" => "Alion",
        "center" => [710, 145],
        "color" => "#FEF08A",
        "voters" => 5840,
        "precincts" => 8,
        "leading" => "Maria Santos",
        "turnout" => "84.2%"
    ],
    [
        "id" => 2,
        "name" => "BATANGAS II",
        "title" => "Batangas II",
        "center" => [820, 240],
        "color" => "#FED7AA",
        "voters" => 6920,
        "precincts" => 10,
        "leading" => "Juan Dela Cruz",
        "turnout" => "88.1%"
    ],
    [
        "id" => 3,
        "name" => "CABCABEN",
        "title" => "Cabcaben",
        "center" => [730, 260],
        "color" => "#DDD6FE",
        "voters" => 12450,
        "precincts" => 16,
        "leading" => "Pedro Reyes",
        "turnout" => "86.5%"
    ],
    [
        "id" => 4,
        "name" => "LUCANIN",
        "title" => "Lucanin",
        "center" => [805, 360],
        "color" => "#BAE6FD",
        "voters" => 4610,
        "precincts" => 6,
        "leading" => "Elena Gomez",
        "turnout" => "82.9%"
    ],
    [
        "id" => 5,
        "name" => "BALON-ANITO",
        "title" => "Balon-Anito",
        "center" => [350, 190],
        "color" => "#DCE8BE",
        "voters" => 8100,
        "precincts" => 11,
        "leading" => "Carlos Mendoza",
        "turnout" => "85.7%"
    ],
    [
        "id" => 6,
        "name" => "MALIGAYA",
        "title" => "Maligaya",
        "center" => [560, 200],
        "color" => "#FADBD8",
        "voters" => 7430,
        "precincts" => 9,
        "leading" => "Maria Santos",
        "turnout" => "87.0%"
    ],
    [
        "id" => 7,
        "name" => "BIAAN",
        "title" => "Biaan",
        "center" => [190, 260],
        "color" => "#C8E6C9",
        "voters" => 3200,
        "precincts" => 5,
        "leading" => "Juan Dela Cruz",
        "turnout" => "80.4%"
    ],
    [
        "id" => 8,
        "name" => "MALAYA",
        "title" => "Malaya",
        "center" => [450, 200],
        "color" => "#E1D5E7",
        "voters" => 5350,
        "precincts" => 7,
        "leading" => "Elena Gomez",
        "turnout" => "83.5%"
    ],
    [
        "id" => 9,
        "name" => "TOWNSITE",
        "title" => "Townsite",
        "center" => [735, 420],
        "color" => "#FDE68A",
        "voters" => 6780,
        "precincts" => 9,
        "leading" => "Carlos Mendoza",
        "turnout" => "89.2%"
    ],
    [
        "id" => 10,
        "name" => "SAN ISIDRO",
        "title" => "San Isidro",
        "center" => [380, 350],
        "color" => "#A7F3D0",
        "voters" => 4910,
        "precincts" => 6,
        "leading" => "Pedro Reyes",
        "turnout" => "81.6%"
    ],
    [
        "id" => 11,
        "name" => "MT. VIEW",
        "title" => "Mt. View",
        "center" => [710, 520],
        "color" => "#FBCFE8",
        "voters" => 7890,
        "precincts" => 10,
        "leading" => "Maria Santos",
        "turnout" => "86.8%"
    ],
    [
        "id" => 12,
        "name" => "ALAS-ASIN",
        "title" => "Alas-Asin",
        "center" => [615, 430],
        "color" => "#FED7AA",
        "voters" => 14200,
        "precincts" => 18,
        "leading" => "Juan Dela Cruz",
        "turnout" => "91.3%"
    ],
    [
        "id" => 13,
        "name" => "CAMAYA",
        "title" => "Camaya",
        "center" => [435, 445],
        "color" => "#BAE6FD",
        "voters" => 6120,
        "precincts" => 8,
        "leading" => "Maria Santos",
        "turnout" => "84.9%"
    ],
    [
        "id" => 14,
        "name" => "BASECO COUNTRY",
        "title" => "Baseco Country",
        "center" => [555, 520],
        "color" => "#FEF08A",
        "voters" => 5410,
        "precincts" => 7,
        "leading" => "Carlos Mendoza",
        "turnout" => "85.2%"
    ],
    [
        "id" => 15,
        "name" => "SAN CARLOS",
        "title" => "San Carlos",
        "center" => [485, 465],
        "color" => "#DDD6FE",
        "voters" => 4820,
        "precincts" => 6,
        "leading" => "Elena Gomez",
        "turnout" => "83.1%"
    ],
    [
        "id" => 16,
        "name" => "POBLACION",
        "title" => "Poblacion",
        "center" => [385, 520],
        "color" => "#FADBD8",
        "voters" => 9850,
        "precincts" => 13,
        "leading" => "Juan Dela Cruz",
        "turnout" => "89.8%"
    ],
    [
        "id" => 17,
        "name" => "SISIMAN",
        "title" => "Sisiman",
        "center" => [525, 660],
        "color" => "#A7F3D0",
        "voters" => 5100,
        "precincts" => 7,
        "leading" => "Pedro Reyes",
        "turnout" => "87.4%"
    ],
    [
        "id" => 18,
        "name" => "IPAG",
        "title" => "Ipag",
        "center" => [375, 650],
        "color" => "#E1D5E7",
        "voters" => 6730,
        "precincts" => 9,
        "leading" => "Maria Santos",
        "turnout" => "86.1%"
    ]
];

function pointInPoly($x, $y, &$poly, $n) {
    $inside = false;
    for ($i = 0, $j = $n - 1; $i < $n; $j = $i++) {
        $xi = $poly[$i][0]; $yi = $poly[$i][1];
        $xj = $poly[$j][0]; $yj = $poly[$j][1];
        $intersect = (($yi > $y) != ($yj > $y)) && ($x < ($xj - $xi) * ($y - $yi) / ($yj - $yi) + $xi);
        if ($intersect) $inside = !$inside;
    }
    return $inside;
}

$W = 160;
$H = 150;
$minX = 0; $maxX = 1000;
$minY = 0; $maxY = 850;
$dx = ($maxX - $minX) / ($W - 1);
$dy = ($maxY - $minY) / ($H - 1);

$grid = array_fill(0, $H, array_fill(0, $W, -1));
$polyLen = count($outerPts);

for ($r = 0; $r < $H; $r++) {
    $y = $minY + $r * $dy;
    for ($c = 0; $c < $W; $c++) {
        $x = $minX + $c * $dx;
        if (pointInPoly($x, $y, $outerPts, $polyLen)) {
            $minDist = 999999999;
            $bestId = 0;
            foreach ($barangays as $bgy) {
                $bx = $bgy['center'][0];
                $by = $bgy['center'][1];
                $dist = ($x - $bx) * ($x - $bx) + ($y - $by) * ($y - $by);
                if ($dist < $minDist) {
                    $minDist = $dist;
                    $bestId = $bgy['id'];
                }
            }
            $grid[$r][$c] = $bestId;
        }
    }
}

$svgFeatures = [];

foreach ($barangays as $bgy) {
    $id = $bgy['id'];
    $edges = [];

    for ($r = 0; $r < $H; $r++) {
        for ($c = 0; $c < $W; $c++) {
            if ($grid[$r][$c] === $id) {
                $x0 = round($minX + ($c - 0.5) * $dx, 1);
                $x1 = round($minX + ($c + 0.5) * $dx, 1);
                $y0 = round($minY + ($r - 0.5) * $dy, 1);
                $y1 = round($minY + ($r + 0.5) * $dy, 1);

                if ($r == $H - 1 || $grid[$r + 1][$c] !== $id) {
                    $edges[] = [[$x0, $y1], [$x1, $y1]];
                }
                if ($r == 0 || $grid[$r - 1][$c] !== $id) {
                    $edges[] = [[$x1, $y0], [$x0, $y0]];
                }
                if ($c == $W - 1 || $grid[$r][$c + 1] !== $id) {
                    $edges[] = [[$x1, $y1], [$x1, $y0]];
                }
                if ($c == 0 || $grid[$r][$c - 1] !== $id) {
                    $edges[] = [[$x0, $y0], [$x0, $y1]];
                }
            }
        }
    }

    if (empty($edges)) continue;

    $chains = [];
    while (!empty($edges)) {
        $first = array_shift($edges);
        $loop = [$first[0], $first[1]];

        $stuck = false;
        while (!$stuck && !empty($edges)) {
            $last = end($loop);
            $found = false;
            foreach ($edges as $i => $e) {
                if (abs($last[0] - $e[0][0]) < 0.1 && abs($last[1] - $e[0][1]) < 0.1) {
                    $loop[] = $e[1];
                    unset($edges[$i]);
                    $edges = array_values($edges);
                    $found = true;
                    break;
                }
            }
            if (!$found) $stuck = true;
        }
        $chains[] = $loop;
    }

    usort($chains, function($a, $b) { return count($b) - count($a); });
    $mainRing = $chains[0];

    $step = max(1, (int)(count($mainRing) / 45));
    $pts = [];
    for ($i = 0; $i < count($mainRing); $i += $step) {
        $pts[] = $mainRing[$i];
    }
    $pts[] = $pts[0];

    $d = "M " . $pts[0][0] . " " . $pts[0][1];
    for ($i = 1; $i < count($pts); $i++) {
        $d .= " L " . $pts[$i][0] . " " . $pts[$i][1];
    }
    $d .= " Z";

    $cx = 0; $cy = 0;
    for ($i = 0; $i < count($pts) - 1; $i++) {
        $cx += $pts[$i][0];
        $cy += $pts[$i][1];
    }
    $cx = round($cx / (count($pts) - 1), 1);
    $cy = round($cy / (count($pts) - 1), 1);

    if ($bgy['name'] === 'SISIMAN') {
        $cy += 8;
    } elseif ($bgy['name'] === 'MT. VIEW') {
        $cy += 4;
    } elseif ($bgy['name'] === 'BASECO COUNTRY') {
        $cy -= 4;
    }

    $svgFeatures[] = [
        "id" => $bgy['id'],
        "name" => $bgy['name'],
        "title" => $bgy['title'],
        "color" => $bgy['color'],
        "voters" => $bgy['voters'],
        "precincts" => $bgy['precincts'],
        "leading" => $bgy['leading'],
        "turnout" => $bgy['turnout'],
        "path" => $d,
        "labelPos" => [$cx, $cy]
    ];
}

file_put_contents(__DIR__ . '/public/geojson/mariveles_svg_data.json', json_encode($svgFeatures, JSON_PRETTY_PRINT));
echo "DONE! Exported all " . count($svgFeatures) . " contiguous connected barangays successfully!\n";
