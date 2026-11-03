<?php

return [
    // Franja diaria de mantenimiento: no se programan citas dentro de ella.
    // Formato 24 horas HH:MM. Si fin es menor que inicio, la franja cruza la medianoche.
    'inicio' => env('MANTENIMIENTO_INICIO', '23:00'),
    'fin'    => env('MANTENIMIENTO_FIN', '05:00'),
];