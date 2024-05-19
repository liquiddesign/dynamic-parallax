<?php

$a = 4.5; // velka poloosa, uhlove vteriny
$b = 3.4; // mala poloosa, uhlove vteriny
$mg1 = 3.9; // relativni magnituda primar
$mg2 = 5.3; // relativni magnituda sekundar
$re = 10; // obezna doba casti elipsy

//a2 = b2 + h2
$h = pow(pow($a, 2) - pow($b, 2), 1 / 2);

// alternativni vypocet
// $h = $a * pow((1 - pow($b / $a, 2)), 1/2);

// plocha elipsy
$s = pi() * $a * $b;

// plocha vysece
$se = $a * $b * (acos($h / $a) - ($h / pow($a, 2) * pow(pow($a, 2) - pow($h, 2), 1/2)));

// obezna doba dle 2. keplerova zakona
$t = 10 * ($s / $se);

echo $t;

// p3 = a3/(t2(mh1+mh2))
$p =


die();


$d = getAngleByMass(4.0, 5, 1, 1 );

echo getAbsoluteMagnitude(1, $d);
echo getAbsoluteMagnitude(1, $d);

die("\nDynamic parallax ended.");



function getAbsoluteMagnitude(float $m, float $d): float
{
	// M = m + 5 – 5 log d.
	return $m + 5 - (5 * log10($d));
}

function getRelativeMagnitude(float $M, float $d): float
{
	//  m = M – 5 + 5*log d
	return $M - 5 + (5 * log10($d));
}

function getDistanceByAngle(float $a): float
{
	// units: a (radian), return (pc)
	return 1 / tan($a);
}

function getAngleByMass(float $a, float $t, float $m1, float $m2): float
{
	$exp = pow($a, 3) / (pow($t, 2) * ($m1 + $m2));

	return pow($exp, 1 / 3);
	//
	//pi()
}