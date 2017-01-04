<?php

/**
 * JsonTaskはJsonファイルに書き込むタスクです。
 *
 * @author Manato0x2cc
 * @license MIT
 * @copyright Copyright (C) 2016 Manato0x2cc All Rights Reserved.
 **/

namespace Mnt2cc\BuildBank\tasks;

use Mnt2cc\BuildBank\utils\BuildBankUtils;

class JsonTask extends \pocketmine\scheduler\PluginTask{

  public function __construct(\pocketmine\plugin\PluginBase $base, $name, $levelId){

    parent::__construct($base);

    $this->BB = $base;

    $this->levelId = $levelId;

    $this->name = $name;

    $this->initPos();

  }

  private function initPos(){

    $pos = $this->BB->getPos($this->name);

    $minX = min($pos[0]->x, $pos[1]->x);

    $minY = min($pos[0]->y, $pos[1]->y);

    $minZ = min($pos[0]->z, $pos[1]->z);

    $maxX = max($pos[0]->x, $pos[1]->x);

    $maxY = max($pos[0]->y, $pos[1]->y);

    $maxZ = max($pos[0]->z, $pos[1]->z);

    $this->pos[] = $pos[0]->setComponents($minX,$minY,$minZ);

    $this->pos[] = $pos[1]->setComponents($maxX,$maxY,$maxZ);

  }

  public function onRun($t){

    $level = $this->BB->getServer()->getLevel($this->levelId);

    $pos1 = clone $this->pos[0];
    $pos2 = clone $this->pos[1];

    $pos = clone $this->pos[0]; //値を変えていく。

    $obj = [];

    $distY = $pos2->y - $pos1->y;

    $distX = $pos2->x - $pos1->x;

    $distZ = $pos2->z - $pos1->z;

    for ($y=0; $y <= $distY; $y++) {

      for ($x=0; $x <= $distX; $x++) {

        for ($z=0; $z <= $distZ; $z++) {

          $block = $level->getBlock($pos->setComponents($pos1->x + $x, $pos1->y + $y, $pos1->z + $z));

          $obj["object"][$y][$x][$z][] = $block->getId();
          $obj["object"][$y][$x][$z][] = $block->getDamage();

        }

      }

    }

    $obj["direction"] = $this->BB->getDirection($this->name);

    $obj["object"] = BuildBankUtils::turnArray($obj["object"], $obj["direction"]*90);

    $key = $this->BB->getKey($this->name);

    // json_encode($obj, JSON_PRETTY_PRINT);//整形しようと思ったけど、8万行以上行くからやめた

    file_put_contents($this->BB->getBuildPath().$key.".json", json_encode($obj));

    $this->BB->deleteAll($this->name);

  }

}
