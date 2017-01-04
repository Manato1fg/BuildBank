<?php

/**
 * BuildTaskは組み立てるタスクです。
 *
 * @author Manato0x2cc
 * @license MIT
 * @copyright Copyright (C) 2016 Manato0x2cc All Rights Reserved.
 **/

 namespace Mnt2cc\BuildBank\tasks;


 use Mnt2cc\BuildBank\exceptions\BuildBankException;
 use Mnt2cc\BuildBank\lang\BuildBankMessages as BM;
 use Mnt2cc\BuildBank\utils\BuildBankUtils;
 use pocketmine\math\Vector3;
 use pocketmine\block\Block;


 class BuildTask extends \pocketmine\scheduler\PluginTask{

   public function __construct(\pocketmine\plugin\PluginBase $base, $name, $levelId, Vector3 $pos, $key, $direction){

     parent::__construct($base);

     $this->BB = $base;

     $this->levelId = $levelId;

     $this->name = $name;

     $this->bm = BM::getInstance();

     $json = @file_get_contents($this->BB->getBuildPath().$key.".json");

     if($json === false){

       $this->BB->getServer()->getPlayer($name)->sendMessage($this->bm->translate("not_found_key", $key));

     }

     $this->key = $key;

     $this->obj = json_decode($json, true);

     $this->pos = $pos;

     $this->d = $direction;

   }

   public function onRun($t){

     $level = $this->BB->getServer()->getLevel($this->levelId);

     $pos = clone $this->pos;

     $obj = BuildBankUtils::turnArray($this->obj["object"], -$this->d * 90);

     $distY = count($obj);

     $distX = count($obj[0]);

     $distZ = count($obj[0][0]);

     $halfX = 0; $halfZ = 0;

     $cos = (int) cos(deg2rad($this->d*90));
     $sin = (int) sin(deg2rad($this->d*90));

     if($sin === BuildBankUtils::SIN90 || ($sin === BuildBankUtils::SIN180 && $cos === BuildBankUtils::COS180)) $halfX = count($obj[0]) - 1;

     if(($sin === BuildBankUtils::SIN180 && $cos === BuildBankUtils::COS180) || $sin === BuildBankUtils::SIN270) $halfZ = count($obj[0][0]) - 1;

     for ($y=0; $y < $distY; $y++) {

       for ($x=0; $x < $distX; $x++) {

         for ($z=0; $z < $distZ; $z++) {

           $this->pos = $this->pos->setComponents($pos->x + $x - $halfX, $pos->y + $y, $pos->z + $z - $halfZ);

          //  echo sprintf("X: %d, Y: %d, Z: %d\n\n", $pos->x + $x, $pos->y + $y, $pos->z + $z);

           $id = $obj[$y][$x][$z][0];
           $meta = $obj[$y][$x][$z][1];

           $level->setBlock($this->pos, Block::get($id, $meta));

         }

       }

     }

     $this->BB->getServer()->getPlayer($this->name)->sendMessage($this->bm->translate("built_object", $this->key));

     $this->BB->deleteAll($this->name);

     unset($this);

  }
}
