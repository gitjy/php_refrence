<?php
Class animal
{
  static function eat() {
    var_dump(__METHOD__, __FUNCTION__);
  }

  static function food($eat) {
    if (0 < $eat) {
      //call_user_func(__METHOD__, $eat-1);
      __METHOD__($eat-1); //静态方法不可以这样调用
      //$fn = __METHOD__;
      //$fn($eat-1);  
       //$fn = __FUNCTION__;
       //self::$fn($eat-1);
    } else {
      self::eat();
    }

  }
}

//animal::eat();

animal::food(2);