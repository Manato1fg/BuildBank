<?php
var_dump(_sin(deg2rad(0)));
var_dump(_sin(deg2rad(180)));
var_dump(_sin(deg2rad(270)));

var_dump(_cos(deg2rad(90)));
var_dump(_cos(deg2rad(180)));
var_dump(_cos(deg2rad(270)));

function _sin($rad){
  $sin = sin($rad);
  $sin = (int) ($sin * 10);
  return $sin / 10;
}

function _cos($rad){
  $sin = cos($rad);
  $sin = (int) ($sin * 10000);
  return $sin / 10000;
}
?>
