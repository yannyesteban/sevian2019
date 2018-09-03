<?php
class one
{
     protected static $foo = "xxx ";
     public function change_foo($value)
     {
         self::$foo = $value;
     }
 }

 class two extends one
{
     public function tell_me()
     {
         echo self::$foo;
     }
 }
$first = new one;
$second = new two;

$second->tell_me(); // bar
$first->change_foo(" restaurant");
$second->tell_me(); // restaurant
?> 