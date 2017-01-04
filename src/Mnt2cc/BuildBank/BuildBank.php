<?php

/**
 * BuildBankは建物単位のWorldEditです。
 * 建物をjsonファイルで保管しておくことでサーバー間の建物共有などが可能になります。
 *
 * @author Manato0x2cc
 * @license MIT
 * @copyright Copyright (C) 2016 Manato0x2cc All Rights Reserved.
 **/

namespace Mnt2cc\BuildBank;

use pocketmine\Server;

use pocketmine\Player;

use pocketmine\plugin\PluginBase;

use pocketmine\utils\Config;

use pocketmine\command\Command as Cmd;

use pocketmine\command\CommandSender as CmdS;

use pocketmine\event\player\PlayerInteractEvent as TapEvent;

use pocketmine\event\player\PlayerChatEvent as ChatEvent;


use Mnt2cc\BuildBank\tasks\BuildTask;

use Mnt2cc\BuildBank\tasks\JsonTask;

use Mnt2cc\BuildBank\utils\BuildBankUtils;

use Mnt2cc\BuildBank\lang\BuildBankMessages as BM;

use Mnt2cc\BuildBank\exceptions\BuildBankException;


class BuildBank extends PluginBase implements \pocketmine\event\Listener{

  //サーバーインスタンス
  public $server;

  //InteractEventでregisterコマンドを実行している人がどうかを確認するための配列。
  //keyはプレイヤーの名前。格納できる値は、
  //'start' ... registerコマンドを実行した直後。始点をpos配列に格納しておくために使う。
  //'end' ... 'start'サブコマンドが実行し終わった時。終点を取得する。
  private $touch;

  //Interactイベントで始点を保管しておくだけ。
  //keyはプレイヤーの名前、値はVector3オブジェクト。
  private $pos;

  //InteractEventでおく建物のキーを保管する。
  //keyはプレイヤーの名前、値は建物のキー。
  private $put;

  //ChatEventでキーを登録する時に使う。
  //プレイヤーの名前を保管する。
  private $addkey;

  //向いている向きを保管する。
  //keyはプレイヤーの名前、値は向いている向き(int)
  private $directions;

  //buildsまでのパス
  private $buildpath;

  //BuildBankMessagesのインスタンス
  private $bm;

  public function onEnable(){

    $this->server = Server::getInstance();//インスタンスを取得

		$this->server->getPluginManager()->registerEvents($this,$this);//イベントを登録

    $this->bm = BM::getInstance();

    $this->bm->setLang("ja");//デフォルトは日本語。

    $this->buildpath = $this->getDataFolder()."builds/";

		if(!file_exists($this->buildpath)){

      @mkdir($this->buildpath, 0744, true);//フォルダを作成

    }

  }

  public function onCommand(CmdS $sender, Cmd $command, $label, array $args){

    if(strtolower($command->getName()) === "bb" or strtolower($command->getName()) === "buildbank"){

      //コマンド送信者がプレイヤーだったら
      if($sender instanceof Player){

        //サブコマンドがあるか。
        if($args){

          switch (strtolower($args[0])) {

            case 'register':
            case 'r':

              $sender->sendMessage($this->bm->translate("touch_start_point"));

              $this->touch[$sender->getName()] = 'start';

              break;

            case 'build':
            case 'b':

              if(isset($args[1])){

                $key = $args[1];

                if(@file_get_contents($this->buildpath.$key.".json") !== false){

                  $this->put[$sender->getName()] = $key;

                  $sender->sendMessage($this->bm->translate("touch_to_build"));

                  break;

                }else{

                  $sender->sendMessage($this->bm->translate("not_found_key", $key));
                  return;

                }

              }else{

                $this->help($sender);

                break;

              }

            case 'cancel':
            case 'c':

              $this->deleteAll($sender->getName());

              $sender->sendMessage($this->bm->translate("cancel"));

              break;

            default:

              $this->help($sender);

              break;

          }

        }else{

          $this->help($sender);

          return;

        }

      //コマンドプロントからだったら
      }else{

        $sender->sendMessage($this->bm->translate("use_from_console"));
        return;

      }

    }

  }

  public function onTap(TapEvent $event){

    $player = $event->getPlayer();

    $name = $player->getName();

    if(isset($this->touch[$name])){

      $pos = $event->getBlock();

      $key = $this->touch[$name];

      if($key === 'start'){

        $player->sendMessage($this->bm->translate("register_start_point", $pos->x, $pos->y, $pos->z));

        $this->touch[$name] = 'end';

        $player->sendMessage($this->bm->translate("touch_end_point"));

        $this->pos[$name][] = $pos;

        $this->directions[$name] = $player->getDirection();

        return;

      }else if($key === 'end'){

        $blocks = $this->calculateBlocks($this->pos[$name][0], $pos);

        $player->sendMessage($this->bm->translate("register_end_point",$pos->x, $pos->y, $pos->z, $blocks));

        $this->touch[$name] = null;
        $this->pos[$name][] = $pos;

        $this->addkey[] = $name;

        $player->sendMessage($this->bm->translate("enter_key_name"));

        return;
      }
    }

    if(isset($this->put[$name])){

      $pos = $event->getBlock();

      $key = $this->put[$name];

      $levelId = $player->getLevel()->getId();

      $bTask = new BuildTask($this, $name, $levelId, $pos, $key, $player->getDirection());

      $this->server->getScheduler()->scheduleDelayedTask($bTask, 1);

      return;
    }

  }

  public function chat(ChatEvent $event){

    $player = $event->getPlayer();

    $name = $player->getName();

    if(in_array($name, (array) ($this->addkey))){

      $this->put[$name] = $event->getMessage();

      $levelId = $player->getLevel()->getId();

      $jTask = new JsonTask($this, $name, $levelId);

      $this->server->getScheduler()->scheduleDelayedTask($jTask, 1);

      //$jthread->start();

      $player->sendMessage($this->bm->translate("register_build", $event->getMessage()));

      $event->setCancelled();

    }
  }


  private function calculateBlocks($v1, $v2){

    $x = abs($v1->x - $v2->x) + 1;

    $y = abs($v1->y - $v2->y) + 1;

    $z = abs($v1->z - $v2->z) + 1;

    return $x * $y * $z;

  }


  private function help($player){

    $player->sendMessage($this->bm->translate("usage"));

    return;

  }

  public function deleteAll($name){

    unset($this->touch[$name]);
    unset($this->pos[$name]);
    unset($this->put[$name]);
    unset($this->addkey[$name]);
    unset($this->directions[$name]);

  }

  public function getBuildPath(){

    return $this->buildpath;

  }

  public function getKey($name){

    return $this->put[$name];

  }

  public function getDirection($name){

    return $this->directions[$name];

  }

  public function getPos($name){

    return $this->pos[$name];

  }

}
