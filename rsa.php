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



// 1.5 b - Test de Miller-Rabin

function millerRabin($n, $k) {

    // Cas particuliers
    if ($n < 2) {
        return false;
    }

    if ($n == 2 || $n == 3) {
        return true;
    }

    if ($n % 2 == 0) {
        return false;
    }

    // On decompose n-1 sous la forme 2^s * d
    $d = $n - 1;
    $s = 0;

    while ($d % 2 == 0) {
        $d = intdiv($d, 2);
        $s++;
    }

    // On teste plusieurs bases au hasard
    for ($i = 0; $i < $k; $i++) {

        $a = random_int(2, $n - 2);

        // Si on trouve un temoin, le nombre est compose
        if (estTemoin($a, $d, $s, $n)) {
            return false;
        }
    }

    return true;
}



// 1.5 c - Tests de Miller-Rabin

echo "Tests de Miller-Rabin :<br>";

echo "561 : ";
echo millerRabin(561, 20) ? "premier<br>" : "compose<br>";

echo "221 : ";
echo millerRabin(221, 20) ? "premier<br>" : "compose<br>";

echo "997 : ";
echo millerRabin(997, 20) ? "premier<br>" : "compose<br>";

echo "7919 : ";
echo millerRabin(7919, 20) ? "premier<br>" : "compose<br>";



// 1.5 d - Recherche d'un nombre premier aleatoire

function premierAleatoire($min, $max) {

    // On commence et on termine par un nombre impair
    $minImpair = ($min % 2 == 0) ? $min + 1 : $min;
    $maxImpair = ($max % 2 == 0) ? $max - 1 : $max;

    if ($minImpair > $maxImpair || $maxImpair < 3) {
        throw new Exception("Intervalle invalide");
    }

    // On tire des nombres impairs jusqu'a trouver un premier
    do {
        $n = $minImpair + 2 * random_int(
            0,
            intdiv($maxImpair - $minImpair, 2)
        );

    } while (!millerRabin($n, 20));

    return $n;
}


// Generation des cles RSA

function genererClesRSA($min, $max) {

    $p = premierAleatoire($min, $max);

    do {
        $q = premierAleatoire($min, $max);
    } while ($p == $q);

    $n = $p * $q;
    $phi = ($p - 1) * ($q - 1);

    do {
        $e = random_int(2, $phi - 1);
    } while (gcd($e, $phi) != 1);

    $d = inversemod($e, $phi);

    return [
        "p" => $p,
        "q" => $q,
        "n" => $n,
        "phi" => $phi,
        "e" => $e,
        "d" => $d
    ];
}


// Test de generation des cles RSA

echo "<br>Generation des cles RSA :<br>";

$cles = genererClesRSA(100, 999);

echo "p = " . $cles["p"] . "<br>";
echo "q = " . $cles["q"] . "<br>";
echo "n = " . $cles["n"] . "<br>";
echo "phi(n) = " . $cles["phi"] . "<br>";
echo "e = " . $cles["e"] . "<br>";
echo "d = " . $cles["d"] . "<br>";
?>