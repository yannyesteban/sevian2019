<?php

$conections = [];
$elements = [];
$themes = [];
$clsInput = [];
$clsElement = [];
$commands = [];
$sequence_init = [];
$sequence = [];
$listen = [];
$actions = [];

$themes["sevian"] = [
	"css"		=> [
		"../themes/sevian/css/Sevian.css",
		//"Main.css",
		//"Window.css",
		//"Menu.css",
		//"Calendar.css",
		//"Tab.css",
		//"Ajax.css",
		//"SelectText.css",
		//"Form.css"
	],
	"js"		=> [
		"uno.js"
	],
	"templates"	=> [
		"main"	=> "../themes/sevian/html/main.html",
		"main2"	=> "../themes/sevian/html/main2.html",
		"main3"	=> "../themes/sevian/html/main3.php",
		"main4"	=> "../themes/sevian/html/main4.php",
	]
];

$clsInput["submit"] = [
	"file" 	=> "Input.php",	
	"css" 	=> "",	
	"js" 	=> "",	
	"class" => "Sevian\Input",
	"type"  =>  "submit"];
$clsInput["text"] = ["class" => "Sevian\Input",	"type"  =>  "text"];
$clsInput["hidden"] = ["class" => "Sevian\Input",	"type"  =>  "hidden"];
$clsInput["password"] = ["class" => "Sevian\Input",	"type"  =>  "password"];
//$clsInput["select"] = ["class" => "Sevian\Input",	"type"  =>  "select"];

$clsInput["button"] = ["class" => "Sevian\Input",	"type"  =>  "button"];
$clsInput["range"] = ["class" => "Sevian\Input",	"type"  =>  "range"];
$clsInput["image"] = ["class" => "Sevian\Input",	"type"  =>  "image"];

$clsInput["date"] = ["class" => "Sevian\DateInput",	"type"  =>  "calendar"];




$clsInput["select"] = [
	//"file" 	=> "",	
	"css" 	=> "",	
	"js" 	=> "",	
	"class" => "Sevian\Input",
	"type"  =>  "select"];
$clsInput["multiple"] = [
	//"file" 	=> "",	
	"css" 	=> "",	
	"js" 	=> "",	
	"class" => "Sevian\Input",
	"type"  =>  "select"];
$clsInput["color"] = [
	//"file" 	=> "Input.php",	
	"css" 	=> "",	
	"js" 	=> "",	
	"class" => "Sevian\Input",
	"type"  =>  "color"];



$clsElement["form"] = [
	"file" 	=> "SgForm.php",
	"class" => "SgForm"];
$clsElement["menu"] = [
	"file" 	=> "SgMenu.php",
	"class" => "SgMenu"];
$clsElement["fragment"] = [
	"file" 	=> "ssFragment.php",
	"class" => "ssFragment"];
$clsElement["procedure"] = [
	"file" 	=> "SsProcedure.php",
	"class"	=> "SsProcedure"];
$clsElement["sgForm"] = [
	"file" 	=> "Sigefor/Form.php",
	"class" => "Sevian\Sigefor\Form"];

$clsElement["Fragment"] = [
	"file" 	=> "Sigefor/Fragment.php",
	"class" => "Sigefor\Fragment"];
$clsElement["ImagesDir"] = [
	"file" 	=> "ImagesDir.php",
	"class" => "Sevian\ImagesDir"];

$commands["procedure"] = [
	"element"	=>	"procedure",
	"property"	=>	"name",
	"method"	=>	"init",
	"name"		=>	false,
	"eparams"	=>	false
];

$clsElement["form4"] = [
	"file" 	=> "form4.php",
	"class" => "form4"];


$PATH = SS_PATH;

$cssSheets = array(
	"{$PATH}css/Menu.css",
	
	//"{$PATH}css/sgMenu.css",
	"{$PATH}css/sgWindow.css",
	"{$PATH}css/sgCalendar.css",
	"{$PATH}css/selectText.css",
	"{$PATH}css/sgTab.css",
	"{$PATH}css/sgAjax.css",
	"{$PATH}css/grid.css",
	"{$PATH}css/DesignMenu.css",
	"{$PATH}css/Form.css"

);

$jsFiles[] = array("file" => "{$PATH}_js/_sgQuery.js", "begin" => false);
$jsFiles[] = array("file" => "{$PATH}js/sgAjax.js", "begin" => false);
$jsFiles[] = array("file" => "{$PATH}js/drag.js", "begin" => false);
$jsFiles[] = array("file" => "{$PATH}js/sgWindow.js", "begin" => false);
$jsFiles[] = array("file" => "{$PATH}js/sgDB.js", "begin" => true);
$jsFiles[] = array("file" => "{$PATH}js/sgInit.js", "begin" => false);
$jsFiles[] = array("file" => "{$PATH}js/sgSevian.js", "begin" => false);
$jsFiles[] = array("file" => "{$PATH}js/Sevian/Tab.js", "begin" => false);

$jsFiles[] = array("file" => "{$PATH}js/Sevian/Menu.js", "begin" => false);
$jsFiles[] = array("file" => "{$PATH}js/Sevian/DesignMenu.js", "begin" => false);
$jsFiles[] = array("file" => "{$PATH}js/Sevian/Upload.js", "begin" => false);

$jsFiles[] = array("file" => "{$PATH}js/sgCalendar.js", "begin" => false);
$jsFiles[] = array("file" => "{$PATH}js/Sevian/Input.js", "begin" => false);
$jsFiles[] = array("file" => "{$PATH}js/Sevian/Form.js", "begin" => false);


?>