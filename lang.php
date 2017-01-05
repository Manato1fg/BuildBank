<?php

/**
 * You run this file to make lang file easily.
 *
 * @author Manato0x2cc
 * @license MIT
 * @copyright Copyright (C) 2016 Manato0x2cc All Rights Reserved.
 **/

$langs = [];

$keys = [
  "touch_start_point",
  "register_start_point",
  "touch_end_point",
  "register_end_point",
  "enter_key_name",
  "register_build",
  "built_object",
  "usage",
  "cancel",
  "not_found_key",
  "use_from_console",
  "touch_to_build"
];

$msgs = [
  "The message tells player to touch start point to register building",
  "The message is sent when player who wants to register building touches start point ".blue("[x: %1, y: %2, z: %3]"),
  "The message tells player to touch end point to register building",
  "The message is sent when player who wants to register building touches end point ".blue("[x: %1, y: %2, z: %3, count-of-blocks: %4]"),
  "The message tells player to enter building name.",
  "The message is sent when registering building finished successfully. ".blue("[building-name: %1]"),
  "The message is sent when building object finished successfully. ".blue("[building-name: %1]"),
  "The Usage of command and subcommand.",
  "The message is sent when player canceled command",
  "The message is sent when couldn't find the building ".blue("[building-name: %1]"),
  "The message is sent when the owner uses command from console.",
  "The message is sent when player who wants to copy building executes /bb build command."
];

echo "Enter the lang code. : ";
$lang_code = fgets(STDIN);

$lang_code = str_replace("\n", "", $lang_code);

echo "\n";
echo "
All right. Ask some questions to register " .$lang_code. ".json file.
If you'll see variable like ".blue("[var: %1]").", message must include the parcent-signs.

For example
>>> The message is sent when registering building finished successfully. ".blue("[building-name: %1]").
"\nmessage: Registered the building as %1 successfully.

If you couldn't ask questions, press enter-key.\n\n\n\n";

for ($i=0; $i < count($keys); $i++) {

  echo $msgs[$i]."\n";
  echo "message: ";
  $lang = fgets(STDIN);
  echo "All right.\n\n";

  $langs[$keys[$i]] = $lang;
}

echo "Great!! You finished register {$lang_code}. thanks for registering.
Please send http://buildbank.mnt2cc.com/lang/. Or you can send me by using gmail.
Gmail address is manato0x2cc@gmail.com
I really thank you for all of your help.\n\n\n\n";

$json = json_encode($langs, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);//JSON_UNESCAPED_UNICODEはユニコード変換防止

file_put_contents(dirname(__FILE__).'/'.$lang_code.".json", $json);

echo "Generating ".$lang_code.".json successfully\n\n";

function blue($str){
  return "\033[0;34m$str\033[0m";
}
