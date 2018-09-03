<?php

define ("SS_PATH", "../");
define ("SS_CHARSET", "utf-8");
define ("SS_MAIN_PANEL", 4);



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
	"dbase"		=> "cfg_lab",
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

$seq = array();
$seq[]["vses"] = array("xx"=>"ann");
$seq[]["vexp"] = array("vexp"=>array("aux"=>"pepe"));
$seq[]["vreq"] = array("vreq"=>array("w"=>"Yanny","abc"=>"A1B2c5"));



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


$listen_[] = array(
	"sign"=>"save_personas",
	"set_panel"=>array(
		"panel"=>9,
		"element"=>"form",
		"name"=>"personas",
		"method"=>"request"
	)
);

$listen_["save_personas"][] = array(
	"setPanel" => array(
		"panel"=>4,
		"element"=>"fragment",
		"name"=>"fragment",
		"method"=>"load",
		"eparams"=>array("id"=>1)
	
	)

);

$commands["procedure"] = array(
	
	"element"	=>	"procedure",
	"property"	=>	"name",
	"method"	=>	"init",
	"name"		=>	false,
	"eparams"	=>	false
);



$themes["sevian"] = array(
	"path_css" 	=> "http://localhost/sevian/themes/sevian/css/", 
	"css" 		=> array(/*"sigefor1.css",
					
					"paginator.css",
					"calendar.css"*/
					),

	
	"templates"=>array(
		"main"=>"../themes/sevian/html/main.html",
		"main2"=>"../themes/sevian/html/main2.html",
	)
	
);

$init = array(
	"template"	=>	"...<div>--4--<hr>--5--</div>",
	"templateName"=> "main",
	"elements"	=>	$elements,
	"actions"	=>	$actions,
	"listen"	=>	$listen,
	"themes" 	=>	$themes,
	"cssSheets" =>	array(
			"css/main.css"
		)

);

$clsInput["submit"] = array(
	"file" 	=> "sgInput.php",	
	"css" 	=> "",	
	"js" 	=> "",	
	"class" => "stdInput",
	"type"  =>  "submit");

$clsElement["form"] = array(
	"file" 	=> "SgForm.php",
	"class" => "SgForm");
$clsElement["menu"] = array(
	"file" 	=> "SgMenu.php",
	"class" => "SgMenu");
$clsElement["fragment"] = array(
	"file" 	=> "ssFragment.php",
	"class" => "ssFragment");
$clsElement["procedure"] = array(
	"file" 	=> "SsProcedure.php",
	"class"	=> "SsProcedure");


$clsElement["sgForm"] = array(
	"file" 	=> "Sigefor/Form.php",
	"class" => "Sevian\Sigefor\Form");


$PATH = SS_PATH;

$cssSheets = array(
	"{$PATH}css/sgMenu.css",
	"{$PATH}css/sgWindow.css",
	"{$PATH}css/sgCalendar.css",
	"{$PATH}css/selectText.css",
	"{$PATH}css/sgTab.css",
	"{$PATH}css/sgAjax.css",
	"{$PATH}css/grid.css"

);


$jsFiles[] = array("file" => "{$PATH}_js/_sgQuery.js", "begin" => true);
$jsFiles[] = array("file" => "{$PATH}js/sgAjax.js", "begin" => true);
$jsFiles[] = array("file" => "{$PATH}js/drag.js", "begin" => true);
$jsFiles[] = array("file" => "{$PATH}js/sgWindow.js", "begin" => true);
$jsFiles[] = array("file" => "{$PATH}js/sgDB.js", "begin" => true);
$jsFiles[] = array("file" => "{$PATH}js/sgInit.js", "begin" => true);
$jsFiles[] = array("file" => "{$PATH}js/sgSevian.js", "begin" => true);

$jsFiles[] = array("file" => "{$PATH}js/sgTab.js", "begin" => true);

$init["cssSheetsDefault"] = $cssSheets;
$init["jsFilesDefault"] = $jsFiles;


include("../class/Debug/Log.php");
include("../class/Connection.php");
include("../class/HTML.php");
include("../class/Document.php");
include("../class/Form.php");
include("../class/Panel.php");
include("../class2/sg_html.php");
include("../class2/sgHTMLDoc.php");
include("../class/sgTool.php");
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