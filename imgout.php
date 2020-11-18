<?php
$im = imagecreatefrompng("../source/gd.png");
$file = '../source/gd1.png';
imagepng($im, $file, -1);