<?php

/**
 * メッセージ関連を制御します。
 *
 * @author Manato0x2cc
 * @license MIT
 * @copyright Copyright (C) 2016 Manato0x2cc All Rights Reserved.
 **/

 use Mnt2cc\BuildBank\exceptions\BuildBankException;

 namespace Mnt2cc\BuildBank\lang;

 //  頭文字をsprintfに合わせて変換。
const FIRST = ["i"=>"d", "s"=>"s"];

class BuildBankMessages{

   const LANGUAGES = ["ja"];

   protected static $instance;

   protected static $lang;

   protected static $file;

   protected static $l;

   protected function __construct() { }

   public static function getInstance() {

        if (static::$instance === NULL) {

            static::$instance = new static;

        }

        return static::$instance;
    }

   public static function getLang(){
     return static::$lang;
   }

   public static function setLang($lang){
     static::$lang = $lang;
     static::readLang();
   }

   public static function readLang(){

     try{

       static::$file = @file_get_contents(dirname(__FILE__)."/".static::$lang."/".static::$lang.".json");

       if(static::$file === false){

         throw new BuildBankException(BuildBankException::createMessage("Couldn't find that lang code."));

       }

     }catch(\Exception $e){

       throw new BuildBankException(BuildBankException::createMessage("Couldn't find that lang code."));

     }

     static::$l = json_decode(static::$file, true);

   }

   public static function translate($code, ...$args){

    //  return isset(static::$l[$code]) ? static::addTag(static::$l[$code]) : null;

    if (isset(static::$l[$code])) {

      $re_str = static::$l[$code];

      $i = 1;
      foreach ($args as $arg) {

        $re_str = str_replace('%'.$i, $args[$i-1], $re_str);

        ++$i;

      }

      return self::addTag($re_str);

    }

   }

   private static function addTag($str){
     return "[BuildBank] ".$str;
   }
 }
