<?php
$base = imagecreatefrompng("images/$type.png");
// Salvare alpha
imagesavealpha($base, true);
// Generare colore testo
$black = imagecolorallocate($base, 0, 0, 0);
// Applicare background
imagefill($base, 0, 0, $black);
// Scrivere una frase con un determinato font ($arial): $ist, $dimensione, $angolo=0, $x, $y, $colore, $font, $testo
imagettftext($base, 12, 0, 15, 37, $color, $arial, $text);
// Scrivere un testo senza font personalizzato: $ist, $dim, $x, $y, $testo, $colore
imagestring($im, 3, 0, 0, $text, $color);
header ('Content-type: image/png');
imagepng($base);
?>