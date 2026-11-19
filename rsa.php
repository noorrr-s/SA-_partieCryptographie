<?php


// 1.1 Exponentiation rapide (Personne 1)
function expmod(int $x, int $e, int $n): int
{
    if ($n <= 0) {
        throw new InvalidArgumentException("Le module n doit être strictement positif.");
    }
    if ($e < 0) {
        throw new InvalidArgumentException("L'exposant e doit être positif ou nul.");
    }

    $result = 1 % $n;
    $base = $x % $n;
    if ($base < 0) {
        $base += $n;
    }
    $exp = $e;

    while ($exp > 0) {
        if (($exp & 1) === 1) {
            $result = ($result * $base) % $n;
        }
        $base = ($base * $base) % $n;
        $exp >>= 1;
    }

    return $result;
}

// Test
echo expmod(2, 30, 9) . "\n"; // Affiche 1



// 1.2 PGCD (algorithme d'Euclide)
function gcd(int $a, int $b) : int
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
echo gcd(12, 26) . "\n";



// 1.3 Inverse modulaire (algorithme d'Euclide étendu)

function inversemod( $e, $f)
{
    $r0 = $e;
    $u0 = 1;
    $r1 = $f;
    $u1 = 0;

    while ($r1 > 0) {
        $q = intdiv($r0, $r1); // Quotient de la division euclidienne

        // Mise à jourdes restes et des coefficients u
        $nouvR = $r0 - $q * $r1;
        $nouvU = $u0 - $q * $u1;

        $r0 = $r1;
        $u0 = $u1;
        $r1 = $nouvR;
        $u1 = $nouvU;
    }

    // Si le dernier reste n'est pas 1 pas d'inverse
    if ($r0 !== 1) {
        throw new Exception("Erreur : $e n'est pas inversible modulo $f");
    }

    $resultat = $u0 % $f;
    if ($resultat < 0) {
        $resultat += $f;
    }
    return $resultat;
}

// Test
echo inversemod(5, 26) . "\n";

try {
    echo inversemod(2, 26) . "\n";
} catch (Exception $err) {
    echo $err->getMessage() . "\n"; // Affiche une erreur car PGCD(2, 26) = 2 et non pas 1
}



// 1.4 Chiffrement et déchiffrement RSA (Personne 1)

function RSAencrypt(int $x, int $e, int $n): int
{
    return expmod($x, $e, $n);
}

function RSAdecrypt(int $y, int $d, int $p, int $q): int
{
    $n = $p * $q;
    return expmod($y, $d, $n);
}

// Tests
echo RSAencrypt(3, 5, 85) . "\n"; // Affiche 73
echo RSAdecrypt(2, 5, 5, 17) . "\n"; // Affiche 32
