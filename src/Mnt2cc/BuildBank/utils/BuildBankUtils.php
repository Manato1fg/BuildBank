<?php

/**
 * BuildBankUtilsはBuildBankの計算などを行います。
 *
 * @author Manato0x2cc
 * @license MIT
 * @copyright Copyright (C) 2016 Manato0x2cc All Rights Reserved.
 **/


namespace Mnt2cc\BuildBank\utils;


class BuildBankUtils{
  
  const SIN90  = 1;

  const SIN180 = 0;

  const COS180 = -1;

  const SIN270 = -1;

  /**
   * Args:
   * $ary : 回転させる四次元配列。
   * $k   : 横に回転させる度数 0 90 180 270
   *
   * Important!!
   * $kはcorrect90関数によって一応９０の倍数に変換されますが、
   * 予期せぬ挙動を起こす場合があるので必ず、0, 90, 180, 270....90x
   * にしてください。
   *
   * 計算式:
   * x' = z sinθ + x cosθ + halfX
   * z' = z cosθ - x sinθ + halfZ
   **/

  public static function turnArray($ary, $k){

    $halfX = 0; $halfZ = 0;

    $k = deg2rad(self::correct90($k));

    //いちいち計算しなくても計算結果は一緒だからね。
    $cos = (int) cos($k);
    $sin = (int) sin($k);

    if($sin === self::SIN270 || ($sin === self::SIN180 && $cos === self::COS180)) $halfX = count($ary[0]) - 1;

    if(($sin === self::SIN180 && $cos === self::COS180) || $sin === self::SIN90) $halfZ = count($ary[0][0]) - 1;

    $copy_ary = [];

    for ($y=0; $y < count($ary); $y++) {

      for ($x=0; $x < count($ary[0]); $x++) {

        for ($z=0; $z < count($ary[0][0]); $z++) {

          $zz = $z * $cos - $x * $sin + $halfZ;

          $xx = $z * $sin + $x * $cos + $halfX;

          // echo sprintf("(x,y) = (%d,%d)  (x',y') = (%d,%d)\n\n",$x,$z,$xx,$zz);

          $copy_ary[$y][$xx][$zz] = $ary[$y][$x][$z];

        }

      }

    }

    return $copy_ary;

  }

  //90の倍数に直す。
  private static function correct90($deg){

    $div = (int) ($deg / 90);

    return $div * 90;

  }

}
