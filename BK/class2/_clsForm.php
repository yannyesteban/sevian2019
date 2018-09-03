<?php



class _clsForm extends _sgPanel{
	
	
	//private $_ref = false;
	
	
	public function evalMethod(){
		
		$f = new _sgForm(array(
			"caption"=>"Prueba Form", 
			"id"=>"p4_personas"
		
		)
		
		);

$f->setRef($this->getRef());

$f->addPage(array("caption"=>"Main"));

$f->addTab(array("id"=>"personas_p4"."_tab_1"));

$f->addTabPage("Tab Principal");
$f->addField(array(
	
	"target"=>"",
	"input"=>"z",
	"type_"=>"text",
	"name"=>"nombre",
	
	"id"=>"p4_personas_e_nombre",
	"title"=>"Nombre:",
	"placeholder"=>"- nombre",
	"value"=>"",
	"class"=>"roto",
	"data"=>array(),
	"childs"=>false,
	"parent"=>false,
	"rules"=>array(),
	"propertys"=>array("multiple"=>true, "size"=>8, "title"=>"hola"),
	"style"=>array("color"=>"red", "border"=>"4px solid green"),
	"events"=>array("change"=>"alert(this.getValue())"),
	"mode"=>"normal",
	"status"=>"normal"	
		
	)
);
$f->addTabPage("Opciones Multiples");


$f->addPage(array("caption"=>"Página 01"));

$f->addField(array(
	
	"target"=>"",
	"input"=>"z",
	"type_"=>"text",
	"name"=>"cedula",
	"id"=>"p4_personas_e_cedula",
	"title"=>"Cédula",
	"placeholder"=>"- seleccione",
	"value"=>"12474737",
	"class"=>"roto",
	"data"=>array(),
	"childs"=>false,
	"parent"=>false,
	"rules"=>array(),
	"propertys"=>array("multiple"=>true, "size"=>8, "title"=>"hola"),
	"style"=>array("color"=>"red", "border"=>"4px solid green"),
	"events"=>array("change"=>"alert(this.getValue())"),
	"mode"=>"normal",
	"status"=>"normal"	
		
	)
);
		
		
		$this->html = $f->render();
		$this->script = $f->getScript();
	}// end fucntion
	
	public function _setRef($name){
		$this->_ref = $name;	
	}// end function

	public function _getRef(){
		return $this->_ref;	
	}// end function	

	public function getConfig(){
		
		
	}
	
}// enc class


?>