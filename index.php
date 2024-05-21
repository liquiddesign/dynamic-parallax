<?php

// KONSTANTY ----------------------
const GRAVITY_CONSTANT = 6.67430e-11;
const SUN_WEIGHT = 1.989e30; // kg
const SUN_MAGNITUDE = 4.83; // mag (absolutni)
const M_IN_PARSEC = 3.0857e16; // m
const PARSEC_IN_LY = 3.262; // pc

// PROMENNE ----------------------
$a = 4.5; // velka poloosa, uhlove vteriny
$b = 3.4; // mala poloosa, uhlove vteriny
$mg1 = 3.9; // relativni magnituda primarni hvezda
$mg2 = 5.3; // relativni magnituda sekundarni hvezda
$re = 10; // obezna doba casti elipsy
$desiredPrecisionPct = 1; // chci presnost pod 1%

// obezna doba dle 2. keplerova zakona, v rocich
$t = $re * (getEllipsisArea($a, $b) / getSectionOfEllipsisArea($a, $b, getFocalDistance($a, $b)));

$precisionPct = null;
$i = 0;
$weight1 = SUN_WEIGHT;
$weight2 = SUN_WEIGHT;

while($precisionPct === null || $precisionPct >= $desiredPrecisionPct) {
	$axis = estimateAxis(yearsToSeconds($t), $weight1, $weight2);
	$d = getDistanceByAngle($axis, $a);

	$mga1 = getAbsoluteMagnitude($mg1, metersToPc($d));
	$mga2 = getAbsoluteMagnitude($mg2, metersToPc($d));

	$prevWeight1 = $weight1;
	$prevWeight2 = $weight2;
	$weight1 = getWeightByMagnitude($mga1);
	$weight2 = getWeightByMagnitude($mga2);

	$precisionPct = max(getDifferencePct($weight1, $prevWeight1), getDifferencePct($weight2, $prevWeight2));
	$i++;
}

echo 'Obezna doba: ' . $t . ' roky' . PHP_EOL;
echo 'Iterace / presnost: ' . $i . '/' . $precisionPct . '%' . PHP_EOL;
echo 'Vzdalenost: ' . pcToLy(metersToPc($d)) . ' ly ' . PHP_EOL;
echo 'Absolutni magnituda primarni: ' . $mga1 . ' mag' . PHP_EOL;
echo 'Absolutni magnituda sekundarni: ' . $mga2 . ' mag' . PHP_EOL;
echo 'Hmotnost primarni: ' . kgToSun($weight1) . ' slunci (' . $weight1 . 'kg)' . PHP_EOL;
echo 'Hmotnost sekundarni: ' . kgToSun($weight2) . ' slunci (' . $weight2 . 'kg)' . PHP_EOL;
die();

// vzdalenost ohniska dle Pythagorovy vety
function getFocalDistance(float $a, float $b): float
{
	return pow(pow($a, 2) - pow($b, 2), 1 / 2);
}

// obsah elipsy
function getEllipsisArea(float $a, float $b): float
{
	return pi() * $a * $b;
}

// obsah vysece
function getSectionOfEllipsisArea(float $a, float $b, float $h): float
{
	return $a * $b * (acos($h / $a) - ($h / pow($a, 2) * pow(pow($a, 2) - pow($h, 2), 1/2)));
}

// vypocet absolutni magnitudy z relativni a ze vzdalenosti
function getAbsoluteMagnitude(float $m, float $d): float
{
	return $m + 5 - (5 * log10($d));
}

// trigonometricka funkce zjistujici vzdalenost na zaklade uhlu a protilehle strany
function getDistanceByAngle(float $a, float $angle): float
{
	$angleInRadian = $angle * pi() / 180 / 3600;

	return $a / (2 * tan($angleInRadian / 2));
}

// 3. kepleruv zakon v si
function estimateAxis(float $t, float $m1, float $m2): float
{
	return pow(GRAVITY_CONSTANT * ($m1 + $m2) / 4 * pow(pi(), 2) * pow($t, 2), 1 / 3);
}

function getWeightByMagnitude(float $mga): float
{
	return pow(pow(10, ($mga - SUN_MAGNITUDE) / -2.5), 1 / 3.5) * SUN_WEIGHT;
}

// rozdil v procentech
function getDifferencePct(float $newValue, float $oldValue): float
{
	return abs(round(($newValue - $oldValue) / $oldValue * 100, 2));
}

// konverze
function yearsToSeconds(int $years): int
{
	return $years * 365 * 24 * 60 * 60;
}

// konverze
function metersToPc(float $d): float
{
	return $d / M_IN_PARSEC;
}

// konverze
function pcToLy(float $pc): float
{
	return $pc / PARSEC_IN_LY;
}

// konverze
function kgToSun($m): float
{
	return round($m / SUN_WEIGHT, 6);
}