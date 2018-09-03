<?php

function get_include_contents($filename) {
    if (is_file($filename)) {
        ob_start();
        include $filename;
        return ob_get_clean();
    }
    return false;
}


function hr($msg_x, $color_x="black",$back_x=""){
	
	//echo "**(".$GLOBALS["debug"].")**__";
	
	if(is_array($msg_x) or is_object($msg_x)){
		
		$msg_x = print_r($msg_x, true);
	}
	
	
	if(isset($_GET["ajax"]) or isset($_POST["ajax"])){
		$GLOBALS["debugN"]++;
		$GLOBALS["debug"] .= $GLOBALS["debugN"].": ".$msg_x."\n";
		
		//echo $GLOBALS["debug"];
		return;	
		
	}
	
	if ($color_x==""){
		echo "<hr>$msg_x<hr>";
	}else{
		echo "<hr><span style=\"background-color:$back_x;color:$color_x;font-family:tahoma;font-size:9pt;font-weight:bold;\">$msg_x</span><hr>";
	}// end if
	
}// end function

class InfoWindow{
	public $caption = false;
	public $mode = "custom";
	public $width = "300px";
	public $height = "300px";
	public $visible = true;
	public $className = false;
	public $classImage = false;
	public $icon = false;
	
	public function __construct($opt = array()){
		foreach($opt as $k => $v){
			if(property_exists($this, $k)){
				$this->$k = $v;
			}
		}
	}
}
class InfoRequest{

	public $panel = false;
	public $targetId = false;
	public $html = false;
	public $script = false;
	public $css = false;
	public $title = false;
	public $typeAppend = 1;
	public $hidden = false;
	public $window = false;
	
	public function __construct($opt = array()){
		foreach($opt as $k => $v){
			$this->$k = $v;
		}
	}
}
class InfoParam{
	public $panel = false;
	public $element = "";
	public $name = "";
	public $method = "";
	public $eparams = array();
	public $async = false;
	public $update = false;
	public $debugMode = false;
	public $designMode = false;
	public $fixed = false;
	
	public function __construct($opt = array()){
		foreach($opt as $k => $v){
			if(property_exists($this, $k)){
				$this->$k = $v;
			}
			
		}
	}
}

class Structure extends sgHTML{
	public $template = "";
	
	private $_ele = array();
	private $_panels = array();
	public function setTemplate($template){
		$this->template = $template;
	}
	
	public function addPanel($panel, $e){
		$this->_ele[$panel] = $e;
		$this->add($e);
	} 
	
	public function getStrPanels($template = ""){
		
		if($template == ""){
			$template = $this->template;
		}
		
		$exp = "|--([0-9]+)--|";
		$this->_panels = array();
		if(preg_match_all($exp, $template, $c)){
			foreach($c[1] as $a => $b){
				$this->_panels[trim($b)] = trim($b);
			}// next
		}// next
		return $this->_panels;

	}
	
	public function render(){
		$template = $this->template;
		
		foreach($this->_ele as $panel => $e){
			$template = str_replace("--$panel--", $e->render(), $template);
		}
		return $this->html = $template;
	}
}





class Sevian{
	
	
	
	
	public $template = false;
	public $title = "";
	public $mainPanel = SS_MAIN_PANEL;
	
	public $ses = array();
	public $req = array();
	public $exp = array();
	public $cfg = array();
	
	public $templateChanged = false;
	
	public $onAjax = false;
	public $onDesign = false;
	public $onDebug = false;
	
	
	private $_info = array();
	
	
	private $lastAction = false;
	//private $clsElement = array();
	private $strPanels = array();
	
	private $_fragments = array();
	
	private $_pSigns = array();
	private $_signs = array();
	
	private $_commands = array();
	private $_actions = array();
	private $_clsElements = array();
	private $_clsInputs = array();
	private $_infoClasses = array();
	//public $isAnonim
		//is_authenticated
		//s_anonymous
		
	//is_fully_authenticated
	
	public function __construct($opt = array()){
		
		foreach($opt as $k => $v){
			$this->$k = $v;
		}
		
		$ins = false;
		
		if(isset($_REQUEST["__sg_ins"])){
			$ins = $_REQUEST["__sg_ins"];
		}else{
			$ins = "p".date("Ymdhis").str_pad(rand(1, 9999), 4, "0", STR_PAD_LEFT);
		}
		
		$this->ins = $ins;

		session_name($ins);
		session_start();
		
		$this->cfg = &$_SESSION;
		$this->req = &$_REQUEST;
		
		$this->ses = &$this->cfg["VSES"];
		$this->onAjax = $this->getReq("__sg_async");
		
		//$this->loadClsElement();
		
		if(!isset($this->cfg["INIT"])){
			
			$this->cfg["INIT"] = true;
			$this->cfg["SW"] = 1;
			$this->cfg["INFO"] = array();
			$this->cfg["AUTH"] = false;
			
			$this->cfg["VSES"] = array();
			$this->cfg["TEMPLATE"] = &$this->template;
			$this->cfg["STR_PANELS"] = &$this->strPanels;
			
			if(isset($opt["clsElement"])){
				$this->_infoClasses = $opt["clsElement"];
			}
			
			if(isset($opt["clsInput"])){
				$this->_infoInputs = $opt["clsInput"];
			}
			
			
			if(isset($opt["elements"])){
				foreach($opt["elements"] as $k => $e){
					$this->setPanel(new InfoParam($e));
				}
			}
			
			if(isset($opt["commands"])){
				$this->_commands = $opt["commands"];
			}
			if(isset($opt["actions"])){
				$this->_actions = $opt["actions"];
			}
			
			
			
			if(isset($opt["signs"])){
				$this->_signs = $opt["signs"];
			}
			$this->cfg["INFO_CLASSES"] = &$this->_infoClasses;
			$this->cfg["INFO_INPUTS"] = &$this->_infoInputs;
			$this->cfg["LISTEN_PANEL"] = &$this->_pSigns;
			$this->cfg["LISTEN"] = &$this->_signs;
			$this->cfg["COMMANDS"] = &$this->_commands;
			$this->cfg["ACTIONS"] = &$this->_actions;
		}else{
			$this->cfg["INIT"] = false;
			
			$this->cfg["SW"] = ($this->cfg["SW"] == "1")? "0": "1";
			
			$this->_infoClasses = &$this->cfg["INFO_CLASSES"];
			$this->_infoInputs = &$this->cfg["INFO_INPUTS"];
			
			$this->_info = &$this->cfg["INFO"];
			$this->template = &$this->cfg["TEMPLATE"];
			$this->strPanels = &$this->cfg["STR_PANELS"];
			
			$this->_signs = &$this->cfg["LISTEN"];
			
			$this->_pSigns = &$this->cfg["LISTEN_PANEL"];
			$this->_commands = &$this->cfg["COMMANDS"];
			$this->_actions = &$this->cfg["ACTIONS"];
		}
		
		foreach($this->_info as $info){
			$info->update = false;
		}
		
		foreach($this->_infoClasses as $name => $info){
			$this->setClassElement($name, $info);
		}
		
		foreach($this->_infoInputs as $name => $info){
			$this->setClassInput($name, $info);
		}
		
		if($this->cfg["INIT"] and isset($opt["sequenceInit"])){
			$this->sequence($opt["sequenceInit"]);
		}
		
		if(isset($opt["sequence"])){
			$this->sequence($opt["sequence"]);
		}
		
		$this->evalParams();
		
		
		$n = &$this->getSes("n");
		$m = &$this->getReq("test");
		$n++;
		$m++;

		$this->ses["saludo"] = "hola a todos";
		
		$aa = &$this->getSes("saludo");
		$aa = "Good Bye !!!";
		
		
		
		
	}
	public function setSes($key, $value){
		$this->ses[$key] = $value;
	}
	public function setReq($key, $value){
		$this->req[$key] = $value;
	}
	public function setExp($key, $value){
		$this->exp[$key] = $value;
	}
	public function &getSes($key){
		return $this->ses[$key];
	}
	public function &getReq($key){
		return $this->req[$key];
	}
	public function &getExp($key){
		return $this->exp[$key];
	}
	public function &getVSes(){
		return $this->ses;
	}
	public function &getVReq(){
		return $this->req;
	}
	public function &getVExp(){
		return $this->exp;
	}
	public function setTemplate($template = ""){
		$this->template = $template;
		$this->templateChanged = true;
	}
	public function getTemplate(){
		return $this->template;
	}
	public function setPanel($info, $update = false){
		
		if($info->panel){
			$info->update = $update;
			$this->_info[$info->panel] = $info; 
		}
		
		
	}
	public function getElement($info){
		
		return $this->sgElement($info);
		
	}
	public function evalMethod($info){
		
		$elem = $this->getElement($info); 
		
		$this->addFragment($elem->request());
		
		$signs = $elem->getSings();
	
		if($signs){
			foreach($signs as $sign){
				$this->evalSigns($signs);
			}
			
		}
		//$this->addRequest($elem->evalMethod($info));
		
	
		
	}
	public function addFragment($fragment){
		$this->_fragments[] = $fragment;
		
	}
	public function getFragment(){
		return $this->_fragments;
		
	}
	public function iMethod($panel, $method){
		$elem = $this->getElement($info); 
		$this->addRequest($elem->evalMethod($info));
	}
	public function setSign($sign, $sequence){
		$this->_signs[$sign] = $secuence;
	}
	public function setPanelSign($panel, $sign, $sequence){
		
		if(!isset($this->_pSigns[$panel])){
			$this->_pSigns[$panel] = array();
		}
		$this->_pSigns[$panel][$sign] = $sequence;
	}
	public function resetPanelSigns($panel){
		if(isset($this->_pSigns[$panel])){
			unset($this->_pSigns[$panel]);
		}
	}
	public function evalSigns($signs){
		
		foreach($signs as $sing){
			if(isset($this->_signs[$sing])){
				$this->sequence($this->_signs[$sing]);
			}
			
			foreach($this->_pSigns as $pSigns){
			
				if(isset($pSigns[$sing])){
					$this->sequence($pSigns[$sing]);
				}

			}

		}
		
		
		
		
	}
	public function evalTemplate(){
		$request = false;
		
		$str = new Structure();
		
		$str->setTemplate($this->vars($this->template));
		
		if($this->templateChanged){
			$this->strPanels = $str->getStrPanels();
			foreach($this->strPanels as $panel){
				if(!isset($this->_info[$panel])){
					$this->setPanel(new InfoParam(array("panel" => $panel)));
				}
			}
		}
		
		foreach($this->_info as $panel => $e){
			
			$this->resetPanelSigns($panel);
			
			$elem = $this->getElement($e); 
			
			$aux = $this->configInputs(array(
				"__sg_panel"	=>$panel,
				"__sg_sw"		=>$this->cfg["SW"],
				"__sg_sw2"		=>$this->cfg["SW"],
				"__sg_ins"		=>$this->ins,
				"__sg_params"	=>"",
				"__sg_async"	=>"",
				"__sg_action"	=>$this->lastAction,
				"__sg_thread"	=>""
			
			));
			
			$form = new sgHTML(array("tagName"=>"form", "action"=>"", "name"=>"form_p$panel", "id"=>"form_p$panel", "method"=> "POST", "enctype"=>"multipart/form-data"));
			$form->add($elem);
			$form->add($aux);
			
			if(isset($this->strPanels[$panel])){
				$div = new sgHTML(array("tagName"=>"div", "id"=>"panel_p$panel"));
				$div->add($form);
				$str->addPanel($panel, $div);
			}else{
				
				$win = new InfoWindow(array(
					"caption"=>"hola $panel"	
				));

				$elem->setWinParams($win);

				$request[] = new InfoRequest(array(
					"panel"		=> $panel,
					"targetId"	=> "panel_p$panel",
					"html"		=> $form->render(),
					"script"	=> $form->getScript(),
					"css"		=> $form->getCss(),
					"typeAppend"=> 1,
					"hidden"	=> false,
					"title"		=> $elem->title,
					"window"	=> $elem->getWinParams(),
				));
			}
			
		}
		
		$opt = new stdClass;
		$opt->INS = $this->ins;
		$opt->SW = $this->cfg["SW"];
		$opt->mainPanel = 4;
		
		if($request){
			$opt->request = $request;
		}
		
		$opt->fragments = $this->getFragment();
		
		$json = json_encode($opt);
		
		$this->script = "\nsevian.init($json);";
		
		$this->cfg["INFO"] = $this->_info;
		
		return $str;
	}
	private function getFormPanel($info){
		$aux = $this->configInputs(array(
				"__sg_panel"	=> $info->panel,
				"__sg_sw"		=> $this->cfg["SW"],
				"__sg_sw2"		=> $this->cfg["SW"],
				"__sg_ins"		=> $this->ins,
				"__sg_params"	=> '',
				"__sg_async"	=> '',
				"__sg_action"	=> $this->lastAction,
				"__sg_thread"	=> ''
			
			));
		$elem = $this->getElement($info); 
		$form = new sgHTML(array("tagName"=>"form", "id"=>"form_p$panel"));
		$form->add($elem);
		$form->add($aux);
		
		return $form;
		
	}
	public function requestPanels($info, $onlyUpdate = true){
		
		$request = array();
		
		foreach($info as $panel => $e){
			
			if($onlyUpdate and !$e->update){
				continue;
			}
			
			$this->resetPanelSigns($panel);
			
			$elem = $this->getElement($e); 
			
			$aux = $this->configInputs(array(
				"__sg_panel"	=> $panel,
				"__sg_sw"		=> $this->cfg["SW"],
				"__sg_sw2"		=> $this->cfg["SW"],
				"__sg_ins"		=> $this->ins,
				"__sg_params"	=> '',
				"__sg_async"	=> '',
				"__sg_action"	=> $this->lastAction,
				"__sg_thread"	=> ''
			
			));
			
			$form = new sgHTML(array("tagName"=>"form", "id"=>"form_p$panel"));
			$form->add($elem);
			$form->add($aux);
			
			$win = new InfoWindow(array(
				"caption"=>"hola $panel"	
			));
			
			$elem->setWinParams($win);
			
			$request[] = new InfoRequest(
				array(
					"panel"		=> $panel,
					"targetId"	=> "panel_p$panel",
					"html"		=> $form->render(),
					"script"	=> $form->getScript(),
					"css"		=> $form->getCss(),
					"typeAppend"=> 1,
					"hidden"	=> false,
					"title"		=> $elem->title,
					"window"	=> $elem->getWinParams(),
				));
			
		}
		
		$request = array_merge($request, $this->_fragments);
		
		return $request;
		
		
	}
	public function evalPanels(){
		
		
		$this->cfg["INFO"] = $this->_info;
		return $this->requestPanels($this->_info);
		
	}
	public function getRequest(){
		//$request = $cmd->evalPanels();
		//return json_encode($request);

		if($this->templateChanged){
			$request[0] = new InfoRequest();
			$request[0]->targetId = false;
			$request[0]->html = $this->evalTemplate()->render();
			$request[0]->script = $this->script;
			$request[0]->title = $this->title;

		}else{
			$request = $this->evalPanels();

		}

		return json_encode($request);
	}
	public function evalParams(){
		if(isset($this->req["__sg_params"]) and $this->req["__sg_params"] != ""){
			$this->sequence(json_decode($this->req["__sg_params"]));
			
		}
	}
	public function sequence($seq){
		
		foreach($seq as $line){
			$_line = each($line);
			$this->command($_line["key"], $_line["value"]);
		}
		
	}
	public function command($cmd, $params){
		
		
		
		switch($cmd){
			case "vses":
				$this->_setVars($this->ses, $params);
				break;			
			case "vexp":
				$this->_setVars($this->exp, $params);
				break;	
			case "vreq":
				$this->_setVars($this->req, $params);
				break;
			case "set_params":
				$this->params = array_merge($this->params, $this->cmd->get_param($value));
				break;
			case "setPanel":
				$this->setPanel(new InfoParam($params), true);
				break;
			case "setMethod":
				$this->evalMethod($params);
				break;
			case "iMethod":
				$this->iMethod($params);
				break;
			case "signs":
				$this->evalSigns($params);
				break;
			default:
				if(isset($this->_commands[$cmd])){
					$this->evalAction($cmd, $params);
				}else if(is_string($params) and isset($this->_actions[$cmd][$params])){
					$this->sequence($this->_actions[$cmd][$params]);
				}
				break;
		}
		
	}
	public function setClassElement($name, $info){
		//require_once($info["file"]);
		
		if(isset($info["file"]) and $info["file"] != ""){
			require_once($info["file"]);
		}
		$this->_clsElement[$name] = $info["class"];
	}
	public function setClassInput($name, $info){
		
		if(isset($info["file"]) and $info["file"] != ""){
			require_once($info["file"]);
		}
		
		$this->_clsInput[$name] = array("class"=>$info["class"], "type"=>$info["type"]);
	}
	public function loadClsElement(){
		
		foreach($this->clsElement as $k => $v){
			require_once($v["file"]);
			//$this->_cssFile[] = $v["css"];
			$this->_clsElement[$k] = $v["class"];

			$v["class"]::setElementType($k);

		} 
		
	}
	public function sgElement($info){
		
		if(isset($this->_clsElement[$info->element])){
			$obj = new $this->_clsElement[$info->element]($info);
			
		}else{
			$obj = new SgPanel($info);
			
		}
		return $obj;

	}
	public function sgInput($info){
		
		if(is_array($info)){
			$info = new Sevian\InfoInput($info);
		}
		
		if(isset($this->_clsInput[$info->input])){
			$info->type = $this->_clsInput[$info->input]["type"];
			$obj = new $this->_clsInput[$info->input]["class"]($info);
		}else{
			$obj = new Sevian\Input($info);
		}

		return $obj;

	}
	public function vars($q){
		return sgTool::vars($q, array(
			array(
				"token" 	=> "@",
				"data" 		=> $this->ses,
				"default" 	=> false
			),
			array(
				"token"		=> "\#",
				"data" 		=> $this->req,
				"default" 	=> false
			),
			array(
				"token" 	=> "&EX_",
				"data" 		=> $this->exp,
				"default" 	=> false
			),
		));
	}
	private function configInputs($_vconfig){
		$div = new sgHTML("");
		
		foreach($_vconfig as $k => $v){
			$input = $div->add(array(
				"tagName"	=>	"input",
				"type"		=>	"hidden",
				"name"		=>	$k,
				"value"		=>	$v
			));
		}
	
		return $div;
		
	}
	private function _setVars(&$var, $params){
		
		foreach($params as $k => $v){
			$var[$k] = $v;
		}

	}
	public function evalAction($params, $value){
	
		if(isset($this->_commands[$params])){
			
			$property = $this->_commands[$params]["property"];
			
			$this->_commands[$params][$property] = $value;
			$this->evalMethod(new InfoParam($this->_commands[$params]));
		}
	
	}
}
?>