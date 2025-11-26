<?php
// /SisCap/includes/imagenes.php
function obtenerImagenCurso(string $titulo): string
{
    $t = mb_strtolower($titulo, 'UTF-8');

    $map = [
        'python'        => 'https://images.pexels.com/photos/1181675/pexels-photo-1181675.jpeg',
        'php'           => 'https://images.pexels.com/photos/2706379/pexels-photo-2706379.jpeg',
        'javascript'    => 'https://images.pexels.com/photos/1181671/pexels-photo-1181671.jpeg',
        'java'          => 'https://images.pexels.com/photos/574073/pexels-photo-574073.jpeg',
        'react'         => 'https://images.pexels.com/photos/1181467/pexels-photo-1181467.jpeg',
        'web'           => 'https://images.pexels.com/photos/1181670/pexels-photo-1181670.jpeg',
        'database'      => 'https://images.pexels.com/photos/1148820/pexels-photo-1148820.jpeg',
        'sql'           => 'https://images.pexels.com/photos/577585/pexels-photo-577585.jpeg',
        'cloud'         => 'https://images.pexels.com/photos/2706378/pexels-photo-2706378.jpeg',
        'seguridad'     => 'https://images.pexels.com/photos/5380642/pexels-photo-5380642.jpeg',
        'ciber'         => 'https://images.pexels.com/photos/5380642/pexels-photo-5380642.jpeg',
        'ai'            => 'https://images.pexels.com/photos/8386440/pexels-photo-8386440.jpeg',
        'inteligencia'  => 'https://images.pexels.com/photos/8386440/pexels-photo-8386440.jpeg',
    ];

    foreach ($map as $key => $url) {
        if (strpos($t, $key) !== false) {
            return $url;
        }
    }

    // Imagen genérica
    return 'https://images.pexels.com/photos/3861964/pexels-photo-3861964.jpeg';
}
