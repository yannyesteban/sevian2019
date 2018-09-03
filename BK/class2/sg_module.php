<?php 
/*****************************************************************
creado: 23/04/2007
modificado: 29/10/2013
por: Yanny Nuñez
*****************************************************************/
//===========================================================
class sg_module extends sg_panel{
	public $element = "module";
	public $module = "";
	public $title = "";
	public $structure = "";
	public $login = "";
	public $password = "";
	public $configuration = "";
	
	public $procedure = "";
	public $secuence = "";
	
	public $files = "";
	public $theme = "";
	public $on_design = "";
	public $status = "";
	
	
	public $update = false;
	
	private $t_modules = "";				

	//===========================================================
	var $vses = array();
	var $vreq = array();
	var $vpara = array();
	var $vreg = array();
	var $vexp = array();
	var $vst = array();
	
	var $icnn = SS_CNN;
	var $icns = SS_CNS;
	//===========================================================
	public function __construct() {
		$this->cnn = conection($this->icnn);
		if($this->icns!=""){
			$this->cns = conection($this->icns);
		}else{
			$this->cns = &$this->cnn;
		}// end if
		
		$this->t_modules = TABLE_PREFIX."modules";
	}// end function
	//===========================================================
	public function execute($module_x, $method){
		
		global $seq;
		
		
		if($module_x!=""){
			$this->module = $module_x;
		}// end if

		

		$log = $seq->log->set(array(
			"type"=>"I",
			"panel"=>$this->panel,
			"title"=>$this->title,
			"element"=>$this->element,
			"name"=>$this->name,
			"method"=>$method,
			"title"=>$this->title
			));


		$cn = &$this->cns;
		
		$cn->query = "
			SELECT * 
			FROM $this->t_modules 
			WHERE module = '$this->module'
			";

		$result = $cn->execute();
		
		
		
		$error = false;
		if($rs=$cn->getData($result)){       



			
			foreach($rs as $k => $v){
				$this->$k = $v;
			}// next

			if($prop = $seq->cmd->get_param($this->configuration)){
				//$this->vpara = array_merge($this->vpara,$prop);
				foreach($prop as $k => $v){
					$this->$k = $v;
				}// next
			}// end if
			
			if($this->procedure!=""){
				$seq->procedure($this->procedure);
			}
			if($this->secuence!=""){
				$seq->secuence($this->secuence);
			}
			
			if($this->on_design){
				$seq->onDesign = $this->on_design;
			}
			$this->update = true;
		}else{
			//$seq->db->regQuery("0", $cn->query." (ERROR).");
			$this->update = false;
			$error = true;
		}// end if


		$log->add(array(
			"type"=>"i",
			"title"=>"Query",
			"info"=>array(
				"conexion"=>"",
				"panel"=>$this->panel,
				"query"=>$cn->query,
				"query end"=>$cn->query,
				"result"=>array("error"=>$error)
		)));

			
	}// end fucntion
	public function evalMethod($method){
		
		global $seq;
		
		if(!$this->name){
			if(isset($this->eparams["name"]) and $this->eparams["name"] != ""){
				$this->name = $this->eparams["name"];
			}else if($v_name = $seq->getReq("module")){
				$this->name = $v_name;
			}//end if			
		}//end if
		
		
	
		
		switch($method){
		case "load":

			$this->execute($this->name, $method);
			$seq->setVar("MODULE_NAME", $this->name);
			$this->evalStructure($this->structure, "init");
			
			break;	
		case "init":
		
			if($seq->getVar("MODULE_NAME") != $this->name){
				
				$this->execute($this->name, $method);
				$this->evalStructure($this->structure, "init");
				$seq->setVar("MODULE_NAME", $this->name);
			}// end if
			break;	
		case "new_element":
		
			$this->newElement();
		
			break;	
	
	
		}// end switch

		return $this->update;
	}// end function
	

	public function evalStructure($structure, $method, $param = array()){
	
		$s = new sg_structure;
		
		$s->name = $structure;
		$s->evalMethod($method);
		
	}// end function


	public function newElement($name=""){
		global $seq;
		$cn = &$this->cnn;
		
		if($name == ""){
			
			$name = $this->element."_".$cn->serialName($this->t_modules, $this->element."_", "module");

		}
		
		$title = ucwords(implode(" ",explode("_", $name)));
		
		
		$cn->query = "INSERT INTO $this->t_modules (module, title) VALUES ('$name', '$title');";

		$this->debug = $cn->query;
		$result = $cn->execute();

		$this->vparams["new_menu"] = $name;
		$seq->setReq("new_menu", $name);
		
		$this->name = $name;
	}// end funtion 
	
	
}// end class
?>