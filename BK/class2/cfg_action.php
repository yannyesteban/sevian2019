<?php
/*****************************************************************
creado: 09/02/2016
modificado: 09/02/2016
por: Yanny Nuñez
*****************************************************************/
class cfg_action extends sg_panel{
	public $icnn = SS_CNN;
	public $icns = SS_CNS;
	public $t_actions = false;


	public $action = "";
	public $title = "";
	public $type = "";
	public $function = "";
	public $panel = "";
	public $params = "";
	public $eparams = "";
	public $iparams = "";
	public $vars = "";
	public $proc_before = "";
	public $proc_after = "";
	public $seq_before = "";
	public $seq_after = "";
	public $signs = "";
	public $eval_signs = "";
	public $validate = "";
	public $valid = "";
	public $events = "";
	public $confirm = "";
	public $class = "";
	public $style = "";
	public $status = "";
	
	public $element = "action";
	public $name = "";
	public $method = "";

	
	static function listActions(){

		
		$_act["login"] = "set_panel:'panel:4;element:form;name:establecimientos;method:request;';";
		//$_act["load_structure"] = "set_method:'panel:-2;element:structure;method:init;name:#structure;';";
		$_act["action"] = "set_method:'element:module;method:init;name:#module;';";


		
		return $_act;
		
	}
		



	public function __construct(){
		
		
		$this->t_actions = TABLE_PREFIX."actions";
		
		
		$this->cnn = conection($this->icnn);
		if($this->icns!=""){
			$this->cns = conection($this->icns);
		}else{
			$this->cns = &$this->cnn;
		}// end if
	}// end function	


	
	//===========================================================
	function execute($action_x){
		
		global $seq;
		
		if($action_x!=""){
			$this->action = $action_x;
		}// end if
		//===========================================================

		$cn = &$this->cns;
		
		$cn->query = "	SELECT a.*
						FROM $this->t_actions as a
						WHERE a.action = '$this->action'
						";

		$result = $cn->execute($cn->query);
		
		
		if($rs = $cn->getDataAssoc($result)){			
			
			$seq->cmd->v->par = &$rs;
			
			foreach($rs as $k => $v){
				$this->$k = $v;
			}// next
			
			
			if($prop = $seq->cmd->get_param($this->params)){
				$seq->cmd->v->par = array_merge($seq->cmd->v->par, $prop);
				foreach($prop as $k => $v){
					$this->$k = $v;
				}// next
			}// end if
			return true;
		}// end if
		return false;
	}// end fucntion
	
	
	
	public function evalMethod($method){
		global $seq;
		
		
		
		
		$seq->setVarRec(array());
		
		//$this->evalParam();	
		$this->render = 0;
		switch($method){
			case "load":
				
				//$seq->se->procedure->execute("dos");
				
				$this->execute($this->name);
				$seq->getCmd($this->sequence);	
				
				break;
			
		}// end switch
		
		if($this->type!=0 and $this->render != 0){

			$this->html = $this->getDinamicPanel($this->html);
		}// end if		
	}// end fucntion

	static function send($opt){
		$json = json_encode($opt);
		$ref = "sv";
		return "return $ref.send($json, this);";
	}

	static function send2($opt){
		$ref = "sv";
		$json = "";
		foreach($opt as $k => $v){
			$json .= (($json != "")?",":"")."\"$k\":$v";
		}
		return "return $ref.send({{$json}}, this);";
	}	


	static function sendParams($panel, $params, $valid = false){
		$opt=array();
		$opt["panel"] = $panel;
		$opt["params"] = $params;
		if($valid !== false){
			$opt["params"] = $valid;
		}
		
		$json = json_encode($opt);
		$ref = "sv";
		return "$ref.send($json)";
		
	}




	static function event($type="set_panel", $panel, $eparams, $action="", $valid="", $confirm=""){
		if(!$panel){
			return false;
		}// end if


		$n = "sgPanel";
		$n = "sv";

		switch($type){
		case "set_panel":
			return "$n.send($panel, '$eparams', '$action', '$valid', '$confirm')";
			break;	
		case "set_panel_x":
			return "$n.sendX($panel, '$eparams', '$action', '$valid', '$confirm')";
			break;	
		case "open_window":
			return "$n.openWindow($v->panel, $v->w_panel, '$v->eparams', '$v->action', '$v->valid', '$v->confirm')";
			break;	
		case "open_menu":
			break;	
		case "return_value":
			return "$n.returnValue($v->panel, $v->request_panel, '$v->request_element', 100)";
			break;	
		case "return_form":
			return "$n.returnForm($v->panel, $v->request_panel, '$v->request_element')";
			break;	
			
		}// end switch


		switch($type){
		case "set_panel":
			return "$n.setPanel($panel, '$eparams', '$action', '$valid', '$confirm')";
			break;	
		case "set_panel_x":
			return "$n.setPanelX($panel, '$eparams', '$action', '$valid', '$confirm')";
			break;	
		case "open_window":
			return "$n.openWindow($v->panel, $v->w_panel, '$v->eparams', '$v->action', '$v->valid', '$v->confirm')";
			break;	
		case "open_menu":
			break;	
		case "return_value":
			return "$n.returnValue($v->panel, $v->request_panel, '$v->request_element', 100)";
			break;	
		case "return_form":
			return "$n.returnForm($v->panel, $v->request_panel, '$v->request_element')";
			break;	
			
		}// end switch
		
		
	
		
		
	}// end fucntion


	static function setEvent2($type="set_panel", $v){
		if(!$v->panel){
			return false;
		}// end if


		switch($type){
		case "set_panel":
			return "sv.send($v->panel, '$v->eparams', '$v->action', '$v->valid', '$v->confirm')";
			break;	
		case "set_panel_x":
			return "sv.sendX($v->panel, '$v->eparams', '$v->action', '$v->valid', '$v->confirm')";
			break;	
		case "open_window":
			return "sv.openWindow($v->panel, $v->w_panel, '$v->eparams', '$v->action', '$v->valid', '$v->confirm')";
			break;	
		case "open_menu":
			break;	
		case "return_value":
			return "sv.returnValue($v->panel, $v->request_panel, '$v->request_element', 100)";
			break;	
		case "return_form":
			return "sv.returnForm($v->panel, $v->request_panel, '$v->request_element')";
			break;	
			
		}// end switch



		switch($type){
		case "set_panel":
			return "sgPanel.setPanel($v->panel, '$v->eparams', '$v->action', '$v->valid', '$v->confirm')";
			break;	
		case "set_panel_x":
			return "sgPanel.setPanelX($v->panel, '$v->eparams', '$v->action', '$v->valid', '$v->confirm')";
			break;	
		case "open_window":
			return "sgPanel.openWindow($v->panel, $v->w_panel, '$v->eparams', '$v->action', '$v->valid', '$v->confirm')";
			break;	
		case "open_menu":
			break;	
		case "return_value":
			return "sgPanel.returnValue($v->panel, $v->request_panel, '$v->request_element', 100)";
			break;	
		case "return_form":
			return "sgPanel.returnForm($v->panel, $v->request_panel, '$v->request_element')";
			break;	
			
		}// end switch
		
		
		switch($type){
		case "set_panel":
			return "sgAction.setPanel($v->panel, '$v->eparams', '$v->action', '$v->valid', '$v->confirm')";
			break;	
		case "set_panel_x":
			return "sgAction.setPanelX($v->panel, '$v->eparams', '$v->action', '$v->valid', '$v->confirm')";
			break;	
		case "open_window":
			return "sgAction.openWindow($v->panel, $v->w_panel, '$v->eparams', '$v->action', '$v->valid', '$v->confirm')";
			break;	
		case "open_menu":
			break;	
		case "return_value":
			return "sgAction.returnValue($v->panel, $v->request_panel, '$v->request_element', 100)";
			break;	
		case "return_form":
			return "sgAction.returnForm($v->panel, $v->request_panel, '$v->request_element')";
			break;	
			
		}// end switch
		
		
	}// end fucntion
	
	static function setEvent($type="set_panel", $panel="false", $eparam, $action="", $valid="", $confirm="", $extras=""){
		if(!$panel){
			return false;
		}// end if
		
		switch($type){
		case "set_panel":
			return "sgAction.setPanel($panel, '$eparam', '$action', '$valid', '$confirm')";
			break;	
		case "set_panel_x":
			return "sgAction.setPanelX($panel, '$eparam', '$action', '$valid', '$confirm')";
			break;	
		case "open_window":
			return "sgAction.openWindow($panel, '$eparam', '$action', '$valid', '$confirm')";
			break;	
		case "open_menu":
			break;	
		case "return_value":
			return "sgAction.returnValue($panel, element, value)";
			break;	
		case "return_form":
			return "sgAction.returnForm($panel, element, value)";
			break;	
			
		}// end switch
		
		
	}// end fucntion
	

	

	static function setEventNO($function="set_panel", $panel="false", $eparam, $action="", $valid="", $confirm=""){
		
		return "sgPanel.setPanel('$function', $panel, '$eparam', '$action', '$valid', '$confirm')";
		
	}// end fucntion


	
	public function getFunction($panel, $param, $name, $valid){
		return "S.setPanel($panel, '$param', '$name', false, false)";
		
	}	

	public function getAction($action){
		if(!$this->execute($action)){
		
			return "";
		}// end if
		
		return $this->getFunction($this->panel, $this->getParam(), $this->action, false);
		
		
	}	

	
	public function button($action){
		if(!$this->execute($action)){
			return false;
			
			
		}// end if
		
		
		$ele = new sgHTML("button");
		$ele->event["onclick"] = $this->getAction($this->panel, $this->getParam(), $this->action, false);		
		return $ele;
	}
	
	public function getParam($param=false){
		
		$str = "";
		
		if($this->panel!==""){
			$str .= "panel:".$this->panel.";";
		}// enf if
		if($this->element!=""){
			$str .= "element:".$this->element.";";
		}// enf if
		if($this->name!=""){
			$str .= "name:".$this->name.";";
		}// enf if
		if($this->method!=""){
			$str .= "method:".$this->method.";";
		}// enf if
		if($this->proc_before){
			$str .= "proc_before:".$this->proc_before.";";
		}// enf if
		if($this->proc_after){
			$str .= "proc_after:".$this->proc_after.";";
		}// enf if
		if($this->seq_before){
			$str .= "seq_before:".$this->seq_before.";";
		}// enf if
		if($this->seq_after){
			$str .= "seq_after:".$this->seq_after.";";
		}// enf if
		if($this->structure){
			$str .= "structure:".$this->structure.";";
		}// enf if
		if($this->interaction){
			$str .= "imethod:".$this->interaction.";";
		}// enf if
		if($this->vars){
			$str .= "vars:\'".$this->vars."\';";
		}// enf if


		if($this->elem_params!=""){
			$str .= $this->elem_params;
		}// enf if


		return $str;
	}// end function


	static function getParamA($param, $func=true){
		
		$str = "";
		
		if($param["panel"]!==""){
			$str .= "panel:".$param["panel"].";";
		}// enf if
		if($param["element"]!=""){
			$str .= "element:".$param["element"].";";
		}// enf if
		if($param["name"]!=""){
			$str .= "name:".$param["name"].";";
		}// enf if
		if($param["method"]!=""){
			$str .= "method:".$param["method"].";";
		}// enf if
		if($param["proc_before"]){
			$str .= "proc_before:".$param["proc_before"].";";
		}// enf if
		if($param["proc_after"]){
			$str .= "proc_after:".$param["proc_after"].";";
		}// enf if
		if($param["seq_before"]){
			$str .= "seq_before:".$param["seq_before"].";";
		}// enf if
		if($param["seq_after"]){
			$str .= "seq_after:".$param["seq_after"].";";
		}// enf if
		if($param["structure"]){
			$str .= "structure:".$param["structure"].";";
		}// enf if
		if($param["interaction"]){
			$str .= "imethod:".$param["interaction"].";";
		}// enf if
		if($param["vars"]){
			$str .= "vars:\'".$param["vars"]."\';";
		}// enf if
		if(isset($param["login"])){
			$str .= "login:".$param["login"].";";
		}// enf if

		if($param["elem_params"]!=""){
			$str .= $param["elem_params"];
		}// enf if

		return $str;
	}// end function
	
	
}// end class
?>