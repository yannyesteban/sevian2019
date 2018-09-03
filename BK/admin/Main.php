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

$conections["_sigefor"] = array(
	"driver"	=> "mysql",
	"host"		=> "127.0.0.1",
	"port"		=> "3306",
	"user"		=> "root",
	"pass"		=> "123456",
	"dbase"		=> "sg_config",
	"charset"	=> "utf-8"
);

$conections["contraloria"] = array(
	"driver"	=> "postgres",
	"host"		=> "127.0.0.1",
	"port"		=> "5432",
	"user"		=> "postgres",
	"pass"		=> "admin",
	"dbase"		=> "contraloria",
	"charset"	=> "utf-8"
);


$conections["sevian"] = array(
	"driver"	=> "postgres",
	"host"		=> "127.0.0.1",
	"port"		=> "5432",
	"user"		=> "postgres",
	"pass"		=> "postgres",
	"dbase"		=> "sevian",
	"charset"	=> "utf-8"
);
$conections["sevian_2017"] = array(
	"driver"	=> "mysql",
	"host"		=> "127.0.0.1",
	"port"		=> "3306",
	"user"		=> "root",
	"pass"		=> "123456",
	"dbase"		=> "sevian_2017",
	"charset"	=> "utf-8"
);

$conections["sevian_2017_pg"] = array(
	"driver"	=> "postgres",
	"host"		=> "127.0.0.1",
	"port"		=> "5432",
	"user"		=> "postgres",
	"pass"		=> "postgres",
	"dbase"		=> "sevian_2017",
	"charset"	=> "utf-8"
);
$clsElement["Menu1"] = [
	"file" 	=> "../admin/Menu1.php",
	"class" => "Menu1"];
$clsElement["Design"] = [
	"file" 	=> "../admin/DesignMenu.php",
	"class" => "DesignMenu"];
$elements[] = array(
	"panel"		=>1,
	"element"	=>"Menu1",
	"name"		=>"uno",
	"method"	=>"toolbar",
	"designMode"=>false,
	"fixed"		=>true,
);
$elements[] = array(
	"panel"		=>4,
	"element"	=>"Design",
	"name"		=>"uno",
	"method"	=>"toolbar",
	"designMode"=>false,
	"fixed"		=>true,
);
$elements[] = array(
	"panel"		=>5,
	"element"	=>"ImagesDir",
	"name"		=>"uno",
	"method"	=>"toolbar",
	"designMode"=>false,
	"fixed"		=>true,
);
$elements[] = array(
	"panel"		=>6,
	"element"	=>"form4",
	"name"		=>"uno",
	"method"	=>"toolbar",
	"designMode"=>false,
	"fixed"		=>true,
);

/*
$elements[] = array(
	"panel"		=>6,
	"element"	=>"sgForm",
	"name"		=>"",
	"method"	=>"toolbar",
	"designMode"=>false,
	"fixed"		=>true,
);


$elements[] = array(
	"panel"		=> 3,
	"element"	=> "fragment",
	"name"		=> "",
	"method"	=> "toolbar",
	"designMode"=> false,
	"fixed"		=> true,
);

$elements[] = array(
	"panel"		=> 4,
	"element"	=> "menu",
	"name"		=> "",
	"method"	=> "toolbar",
	"designMode"=> false,
	"fixed"		=> true,
);

$elements[] = array(
	"panel"		=>5,
	"element"	=>"form",
	"name"		=>"",
	"method"	=>"toolbar",
	"designMode"=>false,
	"fixed"		=>true,
);
*/
$actions["do"] = array(
	"save_k" => array(
		"imethod" => "save",
		"params" => "a=2,b=3;",
		"set_panel"=> "panel:4;element:form;name:personas;method:load;record:cedula=#cedula;")
	
);
$actions["_form"]["save_person"] = array(
	array(
		"vses"	=>
			array(
				"tipo"	=> "Tarjeta")),
	array(
		"vses"	=> 
			array(
				"forma"	=> "electronico")),
	array(
		"vses"	=> 
			array(
				"q1"	=> " Ciudad",
				"q2"	=> " de Caracas")),

	array(
		"_iMethod"	=> 
			array(
				"panel"		=> -1,
				"element"	=> "form",
				"name"		=> "person",
				"method"	=> "save",
			)),
	array(
		"setPanel"	=>
			array(
				"panel"		=> 6,
				"element"	=> "form",
				"name"		=> "person",
				"method"	=> "load",
				"eparams"	=>
					array(
						"record"=>"id={&idPersona}"
				
				),

			)),
	



);

$sequence_init = array(
	array("vses"=>array("xx"=>"ann")),
	array("vexp"=>array("aux"=>"pepe")),
	array("vreq"=>array("w"=>"Yannyt","abc"=>"A1B2c5")),

	array("setPanel"=> 
		array(
			"panel"		=> 2,
			"element"	=> "form",
			"name"		=> "uno"
		)
	),



);
$listen["save"] = "sequence";



$sequence = array(
	array("vses"=>array("we"=>"Peter")),
	array("vexp"=>array("aux"=>"pepe")),
	array("vreq"=>array("w"=>"Yannyt","abc"=>"pedro"))

);

//$cssSheets[] = "{$PATH}css/sgMenu.css";
//$jsFiles[] = array("file" => "{$PATH}_js/_sgQuery.js", "begin" => true);


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