<?php

$src = imagecreatefromjpeg("original.jpg");
[$sx, $sy] = [imagesx($src), imagesy($src)];
$img = imagecreatetruecolor($sx, $sy);

$size = $_GET['cell_size'] ?? 50;
[$cx, $cy] = [ceil($sx / $size), ceil($sy / $size)];

for ($i = 0; $i < $cx; $i++) {
    for ($j = 0; $j < $cy; $j++) {
        $color = [0, 0, 0];
        $count = 0;

        for ($x = $i * $size; $x < min($sx, $i * $size + $size); $x++) {
            for ($y = $j * $size; $y < min($sy, $j * $size + $size); $y++) {
                $index = imagecolorat($src, $x, $y);
                $c = imagecolorsforindex($src, $index);

                $color[0] += $c['red'];
                $color[1] += $c['blue'];
                $color[2] += $c['green'];

                $count++;
            }
        }

        $avg = [$color[0] / $count, $color[1] / $count, $color[2] / $count];

        $col = imagecolorallocate($img, $avg[0], $avg[2], $avg[1]);
        imagefilledrectangle($img, $i * $size, $j * $size, min($i * $size + $size, $sx), min($j * $size + $size, $sy), $col);
    }
}

header("Content-Type: image/png");
imagepng($img);
imagedestroy($img);
imagedestroy($src);