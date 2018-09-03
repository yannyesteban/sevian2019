<?php
define ("SS_PATH", "../");
define ("SS_CHARSET", "utf-8");
define ("SS_MAIN_PANEL", 4);

include "Config.php";


$conections["_default"] = array(
	"driver"	=> "mysql",
	"host"		=> "127.0.0.1",
	"port"		=> "3306",
	"user"		=> "root",
	"pass"		=> "123456",
	"dbase"		=> "sevian",
	"charset"	=> "utf-8"
);

$conections["_config"] = array(
	"driver"	=> "mysql",
	"host"		=> "127.0.0.1",
	"port"		=> "3306",
	"user"		=> "root",
	"pass"		=> "123456",
	"dbase"		=> "sevian_2017",
	"charset"	=> "utf-8"
);


$clsElement["Menu1"] = [
	"file" 	=> "../admin/Menu1.php",
	"class" => "Menu1"];
$clsElement["Design"] = [
	"file" 	=> "../admin/DesignMenu.php",
	"class" => "DesignMenu"];

$clsElement["FormC"] = [
	"file" 	=> "formB.php",
	"class" => "FormB"];

$elements[] = array(
	"panel"		=>4,
	"element"	=>"FormC",
	"name"		=>"uno",
	"method"	=>"toolbar",
	"designMode"=>false,
	"fixed"		=>true,
);


$init = array(
	"template"	=>	"...<div>--4--<hr>--5--</div>",
	"templateName"=> "main3",
	"elements"	=>	$elements,
	"actions"	=>	$actions,
	"listen"	=>	$listen,
	"themes" 	=>	$themes,
	"cssSheets" =>	array(
			//"css/main.css"
		)

);

$init["cssSheetsDefault"] = $cssSheets;
$init["jsFilesDefault"] = $jsFiles;


include("../class/Debug/Log.php");
include("../class/Connection.php");
include("../class/HTML.php");
include("../class/Document.php");

include("../class/Menu.php");

include("../class/Input.php");

include("../class/Page.php");

include("../class/Tab.php");
include("../class/Form.php");
include("../class/Panel.php");
include("../class2/sg_html.php");
//include("../class2/sgHTMLDoc.php");
include("../class/sgTool.php");

include("../class/Action.php");

include("../class/sgPanel.php");
include("../class/Sevian.php");
include("../class/Request.php");
include("../class/Valid.php");
include("../class/Record.php");


Sevian\Connection::load($conections);

$sevian = new Sevian(array(
	"clsElement"	=> $clsElement,
	"clsInput"		=> $clsInput,
	"commands"		=> $commands,
	"actions"		=> $actions,
	"elements"		=> $elements,
	"sequenceInit" 	=> $sequence_init,
	"sequence" 		=> $sequence,
	"signs"			=> $listen,
	//"sequence2" => $seq2,
));
$request = new Sevian\Request($init);


echo $request->render();
?>