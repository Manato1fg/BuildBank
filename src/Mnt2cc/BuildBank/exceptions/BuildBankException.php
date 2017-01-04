<?php

/**
 * BuildBankExceptionはBuildBankの実行途中に起きた例外エラーです。
 *
 *
 * @author Manato0x2cc
 * @license MIT
 * @copyright Copyright (C) 2016 Manato0x2cc All Rights Reserved.
 **/

 namespace Mnt2cc\BuildBank\exceptions;

 class BuildBankException extends \Exception{

   public static function createMessage($msg){
     return $msg . "\n不明、誤作動の場合はhttps://github.com/Manato0x2cc/BuildBank/issues で報告してください。"
   }

 }
