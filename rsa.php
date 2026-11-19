<?php


// 1.2 PGCD (algorithme d'Euclide)
function gcd($a, $b)
{
    $a = abs($a);
    $b = abs($b);

    while ($b !== 0) {
        $reste = $a % $b;
        $a = $b;
        $b = $reste;
    }

    return $a;
}

// Test
echo gcd(12, 26) . "\n"; // Affiche 2
