<?php

$test_ary = [];

ini_set('memory_limit', '2097152M');

$t = time();

for ($y=0; $y < 100; $y++) {
  for ($x=0; $x < 100; $x++) {
    for ($z=0; $z < 100; $z++) {
      $test_ary[$y][$x][$z] = mt_rand(0, $y*$x*$z+100);
    }
  }
}

turnArray($test_ary, 90);

function turnArray($ary, $k){

  $halfX = 0; $halfZ = 0;

  if($k === 90 || $k === 180) $halfX = count($ary[0]) - 1;

  if($k === 180 || $k === 270) $halfZ = count($ary[0][0]) - 1;

  $k = deg2rad(correct90($k));

  $copy_ary = $ary;

  //いちいち計算しても計算結果は一緒だからね。
  $cos = cos($k);
  $sin = sin($k);

  for ($y=0; $y < count($ary); $y++) {

    for ($x=0; $x < count($ary[0]); $x++) {

      for ($z=0; $z < count($ary[0][0]); $z++) {

        $xx = $x * $cos - $z * $sin + $halfX;

        $zz = $x * $sin + $z * $cos + $halfZ;

        echo sprintf("(x,y) = (%d,%d) (x',y') = (%d,%d) ID:%d", $x,$z,$xx,$zz,$copy_ary[$y][$x][$z])."\n";

        $ary[$y][$xx][$zz] = $copy_ary[$y][$x][$z];

      }

    }

  }

  return $ary;

}

//90の倍数に直す。
function correct90($deg){

  $div = (int) ($deg / 90);

  return $div * 90;

} ?>
