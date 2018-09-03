<?php
$themes = array();
$clsInput=array();
$clsElement=array();
$jsFiles = array();

$themes["sevian"] = array(
	"path_css" => "http://localhost/_sevian/themes/sevian/css/", 
	"css"  => array(/*"sigefor1.css",*/
					"main.css",
					"popup.css",
					"cls_debug.css",
					"tab.css",
					"form.css",
					"sg_menu.css",
					"menu_design.css",
					"form_design.css",
					"paginator.css",
					"calendar.css"
					),
	"path_images" => "../../",
	"path_html" => "../../",
	"path_js" => "../../"
);


$themes["calido2"] = array(
	"path_css" => "http://localhost/_sevian/themes/calido2/css/", 
	"css"  => array(/*"sigefor1.css",*/
					"main.css",
					"popup.css",
					"cls_debug.css",
					"tab.css",
					"form.css",
					"sg_menu.css",
					"menu_design.css",
					"form_design.css",
					"paginator.css",
					"calendar.css"
					),
	"path_images" => "../../",
	"path_html" => "../../",
	"path_js" => "../../"
);

$themes["calido"] = array(
	"path_css" => "http://localhost/_sevian/themes/calido/css/", 
	"css"  => array(/*"sigefor1.css",*/
					"main.css",
					"popup.css",
					"cls_debug.css",
					"tab.css",
					"form.css",
					"sg_menu.css",
					"menu_design.css",
					"form_design.css",
					"paginator.css",
					"calendar.css"
					),
	"path_images" => "http://localhost/_sevian/themes/_common/images/",
	"path_html" => "../../",
	"path_js" => "../../"
);




//$jsFiles[] = array("file" => SS_PATH."js/_tab.js", "begin" => true);
$jsFiles[] = array("file" => SS_PATH."js/sgTools.js", "begin" => true);
$jsFiles[] = array("file" => SS_PATH."js/dragDrop.js", "begin" => true);

$jsFiles[] = array("file" => SS_PATH."js/sgPopup.js", "begin" => true);

$jsFiles[] = array("file" => SS_PATH."js/popup2.js", "begin" => true);
$jsFiles[] = array("file" => SS_PATH."js/sevian.js", "begin" => true);
//$jsFiles[] = array("file" => SS_PATH."js/form.js", "begin" => true);
//$jsFiles[] = array("file" => SS_PATH."js/sgElement.js", "begin" => true);

$jsFiles[] = array("file" => SS_PATH."js/sgForm.js", "begin" => true);
$jsFiles[] = array("file" => SS_PATH."js/debug.js", "begin" => true);
$jsFiles[] = array("file" => SS_PATH."js/selectText.js", "begin" => true);
$jsFiles[] = array("file" => SS_PATH."js/inputParams.js", "begin" => true);

$jsFiles[] = array("file" => SS_PATH."js/validation.js", "begin" => true);

$jsFiles[] = array("file" => SS_PATH."js/inputValid.js", "begin" => true);
$jsFiles[] = array("file" => SS_PATH."js/sg_menu3.js", "begin" => true);
$jsFiles[] = array("file" => SS_PATH."js/menuDesign.js", "begin" => true);

$jsFiles[] = array("file" => SS_PATH."js/clsCalendar.js", "begin" => true);
$jsFiles[] = array("file" => SS_PATH."js/datepicker.js", "begin" => true);
$jsFiles[] = array("file" => SS_PATH."js/dateElement.js", "begin" => true);
//$jsFiles[] = array("file" => SS_PATH."js/sgInput.js", "begin" => true);
$jsFiles[] = array("file" => SS_PATH."js/query.js", "begin" => true);


$clsInput["submit"] = array(
	"file" => "sgInput.php",	
	"css" => "",	
	"js" => "",	
	"class" => "stdInput",
	"type"  =>  "submit");
$clsInput["button"] = array(
	"file" => "sgInput.php",	
	"css" => "",	
	"js" => "",	
	"class" => "stdInput",
	"type"  =>  "button");
$clsInput["text"] = array(
	"file" => "sgInput.php",	
	"css" => "",	
	"js" => "",	
	"class" => "stdInput",
	"type"  =>  "text");
$clsInput["hidden"] = array(
	"file" => "sgInput.php",	
	"css" => "",	
	"js" => "",	
	"class" => "stdInput",
	"type"  =>  "hidden");
$clsInput["password"] = array(
	"file" => "sgInput.php",	
	"css" => "",	
	"js" => "",	
	"class" => "stdInput",
	"type"  =>  "password");
$clsInput["checkbox"] = array(
	"file" => "sgInput.php",	
	"css" => "",	
	"js" => "",	
	"class" => "stdInput",
	"type"  =>  "checkbox");
$clsInput["textarea"] = array(
	"file" => "sgInput.php",	
	"css" => "",	
	"js" => "",	
	"class" => "stdInput",
	"type"  =>  "textarea");
$clsInput["select"] = array(
	"file" => "sgInput.php",	
	"css" => "",	
	"js" => "",	
	"class" => "stdInput",
	"type"  =>  "select");
$clsInput["params"] = array(
	"file" => "sgInput.php",	
	"css" => "",	
	"js" => "",	
	"class" => "inputParams", 
	"type"  => "inputParams");
$clsInput["valid"] = array(
	"file" => "sgInput.php",	
	"css" => "",	
	"js" => "",	
	"class" => "inputValid", 
	"type"  => "inputValid");
$clsInput["calendar"] = array(
	"file" => "inputDate.php",	
	"css" => "",	
	"js" => "",	
	"class" => "inputDate",
	"type"  =>  "calendar");

$clsInput["data_check"] = array(
	"file" => "sgInput.php",	
	"css" => "",	
	"js" => "",	
	"class" => "inputDataCheck", 
	"type"  => "inputDataCheck");
	
	
$clsInput["check_list"] = array(
	"file" => "svCheckList.php",	
	"css" => "",	
	"js" => "",	
	"class" => "svCheckList", 
	"type"  => "svCheckList");	


$clsInput["data_list1"] = array(
	"file" => "sgDataList.php",	
	"css" => "",	
	"js" => "",	
	"class" => "sgDataList", 
	"type"  => "checkbox");	


$clsInput["sf_check_list"] = array(
	"file" => "formInput.php",	
	"css" => "",	
	"js" => "",	
	"class" => "formInput", 
	"type"  => "checkbox");	


$clsInput["sf_check"] = array(
	"file" => "formInput.php",	
	"css" => "",	
	"js" => "",	
	"class" => "formInput", 
	"type"  => "check");
	
$clsInput["check"] = array(
	"file" => "formInput.php",	
	"css" => "",	
	"js" => "",	
	"class" => "setCheck", 
	"type"  => "check");
	
$clsInput["check2"] = array(
	"file" => "formInput.php",	
	"css" => "",	
	"js" => "",	
	"class" => "setCheck2", 
	"type"  => "check");			


$clsInput["data_list"] = array(
	"file" => "formInput.php",	
	"css" => "",	
	"js" => "",	
	"class" => "setCheck2", 
	"type"  => "check");
	
	
$clsInput["select_text"] = array(
	"file" => "sgInput.php",	
	"css" => "",	
	"js" => "",	
	"class" => "inputSelectText", 
	"type"  => "text");				



$clsElement["debug"] 	= 		array("file" => "cls_debug.php", 		"class" => "cls_debug");
$clsElement["module"] 	= 		array("file" => "sg_module.php", 		"class" => "sg_module");
$clsElement["structure"]= 		array("file" => "sg_structure.php",	"class" => "sg_structure");
$clsElement["sigefor"]= 		array("file" => "sigefor.php",	"class" => "sigefor");
$clsElement["menu"] 	= 		array("file" => "cls_menu_design.php", 		"class" => "cls_menu_design");
$clsElement["form"] 	= 		array("file" => "cls_form_design.php", 		"class" => "cls_form_design");

//$clsElement["form"] 	= 		array("file" => "cls_form.php", 		"class" => "cls_form");


$clsElement["fragment"] 	= 		array("file" => "sg_fragment.php", 		"class" => "sg_fragment");
$clsElement["procedure"] 	= 		array("file" => "cfg_procedure.php", 		"class" => "cfg_procedure");
$clsElement["sequence"] 	= 		array("file" => "cfg_sequence.php", 		"class" => "cfg_sequence");
$clsElement["action"] 	= 		array("file" => "cfg_action.php", 		"class" => "cfg_action");
$clsElement["query"] 	= 		array("file" => "query.php", 		"class" => "query");





$cmd = array();

$cmd["procedure"] = array(
	"class"=>"procedure",
	"element"=>"procedure",
	"property"=>"name",
	"method"=>"load",
	"name"=>"",
	"eparam"=>""
);

$cmd["sequence"] = array(
	"class"=>"sequence",
	"element"=>"sequence",
	"property"=>"name",
	"method"=>"load",
	"name"=>"",
	"eparam"=>""
);

$cmd["module"] = array(
	"class"=>"module",
	"element"=>"module",
	"property"=>"name",
	"method"=>"init",
	"name"=>"",
	"eparam"=>""
);

$cmd["structure"] = array(
	"class"=>"structure",
	"element"=>"structure",
	"property"=>"name",
	"method"=>"init",
	"name"=>"",
	"eparam"=>""
);


?>