<?php

class sgFragment_old{
	public $html = "";
	public $target = "";
	public $type_append ="";
}// end class

class sgFragment1{

	public $target = "";
	public $html = "";
	public $script ="";	
	public $css ="";	
	public $type_append ="";	
	
	public function __construct($target, $html, $script = false, $css = false, $typeAppend = "1"){
		
		$this->target = $target;
		$this->html = $html;
		$this->script = $script;
		$this->css = $css;
		$this->typeAppend = $typeAppend;
			
		
	}// end function 
	
	public function xml(){
		$xml = "";
		$xml .= "<fragment>\n";
		$xml .= "	<target><![CDATA[$f->target]]></target>\n";
		$xml .= "	<html><![CDATA[$f->html]]></html>\n";	
		$xml .= "	<script><![CDATA[$f->script]]></script>\n";	
		$xml .= "	<css><![CDATA[$f->css]]></css>\n";	
		$xml .= "	<type_append><![CDATA[$f->type_append]]></type_append>\n";
		$xml .= "</fragment>\n";		
		
	}// end function
	

}// end class


class sg_panel{



	static $_element = "";

	public $element = "";
	public $name = "";
	public $method = "";
	public $elem_params = "";
	
	public $render_method = "";
	public $eparams = array();

	public $html = "";
	public $script ="";
	public $css = "";

	protected $onDesing = true;
	protected $onDebug = true;	
	
	

	public $fragments = array();


	public $title = "";


	public $design_mode = true;

	public $panel="";
	public $subElement = false;

	public $panelType = "1";// 1=Fixed, 2=Aux, 3=Win, 4=Win AND Aux
	public $dinamic = true;
	public $aux	= false;
	public $ajax = false;
	public $updated	= false;
	public $mode_render = "";



	public $esigns = array();


	public $classPanel = "";
	public $class = "";
	
	
	
	public $record="";
	
	public $vparams = array();
	
	
	
	public $ititle = "";
	public $ihtml = "";
	public $itext = "";
	public $iscript ="";
	
	public $thread = "";
	public $debug = "";
	public $message = "";
	public $sign = array();
	public $listen = array();
	public $panel_width = "400px";
	public $win_prop = "";
	public $_win_prop = false;
	
	
	public $user="";
	public $ele_script="";
	public $type=1;

	public $fixed=false;

	
	public $render = 1;

	public $sw = 1;
	public $ins = 0;
	
	public $ref = "";

	public $objScriptName = "sv";//"sgPanel.p";
	public $objFormName = "sgpanel_";
	public $objPanelName = "sgForm";//"sgGPanel";

	public $formEnctype = "multipart/form-data";
	public $formMethod = SS_REQUEST_METHOD;
	
	public $icnn = SS_CNN;
	public $icns = SS_CNS;

	public $p = array();

	protected $_message = array();

	
	protected $_signs = array();
	
	protected $_log = false;
	



	protected $_ref = "";
	protected $_formName = "";
	
	protected $refPanel = "";
	protected $refElement = "";
	protected $refName = "";
	protected $refMethod = "";
	protected $refEparams = "";
	
	static function setElementType($value){
		self::$_element = $value;
	}
	
	static function listActions(){
		
		
	}
	
	
	public function render(){


		
		$this->evalMethod($this->method);


		if($this->subElement or $this->render == 0){
			return $this->html;
		}// end if

		$this->html = $this->getDinamicPanel($this->html);
		
		return $this->html;
		
	}// end function

	public function getScript(){
		
		return $this->script;
		
	}// end function
	
	public function getCss(){
		
		return $this->css;
		
	}// end function
	
	public function getSigns(){
		return $this->_signs;
	}// end function

	public function evalMethod($method){
		//$this->getRender();
	}// end function
	
	public function extraMethod($method){
		return false;
	}// end function

	public function setHTML($html){
		
		
	}// end function

	public function setScript($html){
		
		
	}// end function
	
	public function setFragment($target, $html, $script = false, $css = false, $typeAppend = "1"){
		global $seq;
		
		$seq->setFragment($target, $html, $script, $css, $typeAppend);	
		
	}// end function

	public function setFragmentP($opt){
		global $seq;
		
		return $seq->setFragmentP($opt);	
		
	}// end function

	
	public function setFragmentN($html, $target, $type_append = "1"){
		
		
		
		
		$fragment = new sgFragment;
		$fragment->html = $html;
		$fragment->target = $target;
		$fragment->type_append = $type_append;
		$this->fragments[] = $fragment;
	}// end function

	public function getRef($id = 0){
		
		//return $this->objScriptName;
		
		
		if($this->_ref != ""){
			return $this->_ref;
		}else{
			return $this->objScriptName.".p[$this->panel].e[$id]";	
		}// end if
		
	}// end function

	public function getRender(){
		
		if(/*$this->type!=0 and */$this->render != 0){
			
			$this->html = $this->getDinamicPanel($this->html);
		}// end if		
		
	}// end function
	
	public function setParams($params){
		
		$this->p = $params;
		
	}// end function
	
	public function configPanel(){

		return "panel:$this->panel;element:$this->element;name:$this->name;method:$this->method;";
		
		
		
	}
	
	public function getDinamicPanel($html){
		
		$this->script = $this->newPanel().$this->script;

		$f = new sgHTML("form");
		$f->name = $this->getNameForm();
		$f->enctype = $this->formEnctype;
		$f->method = $this->formMethod;
		
		if($_SERVER['PHP_SELF']){
			$f->action = $_SERVER['PHP_SELF'];
		}else{
			$f->action = "index.php";
		}// end if

		$f->appendChild($html.$this->get_var_cfg());
		
		return $f->render();

	}// end function
	
	public function setRef($ref){
		$this->_ref = $ref;
		
	}// end function

	public function getRefX(){
		
		
		//return $this->objScriptName;
		
		
		if($this->_ref != ""){
			return $this->_ref;
		}else{
			return $this->objScriptName.".p[$this->panel]";
		}// end if
	}// end function

	public function newPanel(){
		
		return "\n\t".$this->objScriptName.".setPanel($this->panel);";
		
		
		return "\n\t".$this->getRef()." = new ".$this->objPanelName."('sgpanel_$this->panel');1+1;";		
	}// end function

	public function getJObjectPanel(){
		
		return "\n\t".$this->getRef()." = new ".$this->objPanelName."('sgpanel_$this->panel');1+1;";		
	}// end function

	public function setNameForm($ref){
		$this->_formName = $ref;
		
	}// end function

	public function getNameForm(){
		if($this->_formName!=""){
			return $this->_formName;
		}else{
			return $this->objFormName.$this->panel;
		}// end if
	}// end function

	public function getInitScript(){
		
	
		return "\n\n\tsgPanel._new_element($this->panel);";	
		
	}
	
	public function get_var_cfg(){
		
		global $seq;
		
		$div = new sgHTML("div");
		$hidden = new sgHTML("input");
		$hidden->type = "hidden";
		$hidden->name = "__sg_ins";
		
		$hidden->onmouseover = "this.title=this.name";
		$hidden->value = $seq->ins;
		$div->innerHTML .= "\n\t".$hidden->render();

		$hidden->name = "__sg_tpanel";
		$hidden->value = $this->panelType;
		$div->innerHTML .= "\n\t".$hidden->render();
		
		$hidden->name = "__sg_panel";
		$hidden->value = $this->panel;
		$div->innerHTML .= "\n\t".$hidden->render();
		
		$hidden->name = "__sg_element";
		$hidden->value = $this->element;
		$div->innerHTML .= "\n\t".$hidden->render();
		
		$hidden->name = "__sg_name";
		$hidden->value = $this->name;
		$div->innerHTML .= "\n\t".$hidden->render();
		
		$hidden->name = "__sg_method";
		$hidden->value = $this->method;
		$div->innerHTML .= "\n\t".$hidden->render();
		
		$hidden->name = "__sg_q_search";
		$hidden->value = "buscar";
		$div->innerHTML .= "\n\t".$hidden->render();
		
		$hidden->name = "__sg_params";
		$hidden->placeholder = "__sg_params";
		$hidden->value = "";
		$div->innerHTML .= "\n\t".$hidden->render();
		
		$hidden->name = "__sg_eparams";
		$hidden->placeholder = "__sg_eparams";
		$hidden->value = "";
		$div->innerHTML .= "\n\t".$hidden->render();
		
		$hidden->name = "__sg_iparams";
		$hidden->placeholder = "__sg_iparams";
		$hidden->value = "";
		$div->innerHTML .= "\n\t".$hidden->render();
		
		$hidden->name = "__sg_action";
		$hidden->placeholder = "__sg_action";
		$hidden->value = "";
		$div->innerHTML .= "\n\t".$hidden->render();
		
		$hidden->name = "__sg_thread";
		$hidden->placeholder = "__sg_thread";
		$hidden->value = $this->thread;
		$div->innerHTML .= "\n\t".$hidden->render();

		$hidden->name = "__sg_sw";
		$hidden->placeholder = "__sg_sw";
		$hidden->value = $seq->getSW();
		$div->innerHTML .= "\n\t".$hidden->render();

		if($this->onDebug or $this->onDesing){

			$hidden->name = "__sg_cfg_params";
			$hidden->placeholder = "__sg_cfg_params";
			$hidden->value = $this->configPanel();
			$div->innerHTML .= "\n\t".$hidden->render();
		
		}

		return "\n\t".$div->render();
		
	}// end function
	
	public function setVarDesign(){
		global $seq;
		
		if(isset($this->eparams["ref_panel"])){
			$this->refPanel = $this->eparams["ref_panel"];
		}elseif($aux = $seq->getReq("__sg_ref_panel")){
			$this->refPanel = $aux;
		}// end if

		if(isset($this->eparams["ref_element"])){
			$this->refElement = $this->eparams["ref_element"];
		}elseif($aux = $seq->getReq("__sg_ref_element")){
			$this->refElement = $aux;
		}// end if

		if(isset($this->eparams["ref_name"])){
			$this->refName = $this->eparams["ref_name"];
		}elseif($aux = $seq->getReq("__sg_ref_name")){
			$this->refName = $aux;
		}// end if

		if(isset($this->eparams["ref_method"])){
			$this->refMethod = $this->eparams["ref_method"];
		}elseif($aux = $seq->getReq("__sg_ref_method")){
			$this->refMethod = $aux;
		}// end if

		if(isset($this->eparams["ref_eparams"])){
			$this->refEparams = $this->eparams["ref_eparams"];
		}elseif($aux = $seq->getReq("__sg_ref_eparams")){
			$this->refEparams = $aux;
		}// end if
			
	}// end function

	public function varAuxDesign(){
		
		global $seq;
		
		$div = new sgHTML("div");
		$hidden = new sgHTML("input");
		$hidden->type = "hidden";

		$hidden->name = "__sg_ref_panel";
		$hidden->value = $this->refPanel;
		$div->innerHTML .= "\n\t".$hidden->render();

		$hidden->name = "__sg_ref_element";
		$hidden->value = $this->refElement;
		$div->innerHTML .= "\n\t".$hidden->render();

		$hidden->name = "__sg_ref_name";
		$hidden->value = $this->refName;
		$div->innerHTML .= "\n\t".$hidden->render();

		$hidden->name = "__sg_ref_method";
		$hidden->value = $this->refMethod;
		$div->innerHTML .= "\n\t".$hidden->render();

		$hidden->name = "__sg_ref_eparams";
		$hidden->value = $this->refEparams;
		$div->innerHTML .= "\n\t".$hidden->render();
		
		
		return "\n\t".$div->render();
	}// end function

	
	public function menuDesign(){

		$this->html = "&nbsp;";
		return "";

		$div = new sgHTML("div");

		$button = new sgHTML("input");
		$button->type = "button";
		$button->id = "button_dbp".$this->panel;
		$button->value  = $this->panel;
		$div->innerHTML .= $button->render();
		return $div->render();
		

		$panel = new sgHTML("span");
		$panel->innerHTML = "P: $this->panel.- ";
		$div->innerHTML = $panel->render();


		$element = new sgHTML("select");
		$element->id = "element_dbp".$this->panel;
		$element->innerHTML  = "<option value=3>Menu</option><option value=1>Form</option>";
		$div->innerHTML .= $element->render();

		$name = new sgHTML("input");
		$name->type = "text";
		$name->id = "name_dbp".$this->panel;
		$name->size = "8";
		$name->value  = "";
		$div->innerHTML .= $name->render();


		$method = new sgHTML("input");
		$method->type = "text";
		$method->id = "method_dbp".$this->panel;
		$method->size = "8";
		$method->value  = "";
		$div->innerHTML .= $method->render();


		$go = new sgHTML("input");
		$go->type = "button";
		$go->id = "show_dbp".$this->panel;
		$go->size = "8";
		$go->value  = "GO";
		$go->onclick = "sgPanel.setPanel('ajax', $this->panel, 'panel:$this->panel;element:'+document.getElementById('element_dbp$this->panel').value+';";
		$go->onclick .= "name:'+document.getElementById('name_dbp$this->panel').value+';method:'+document.getElementById('method_dbp$this->panel').value+';', '', '', '');";	
		$div->innerHTML .= "<br>".$go->render();


		$design = new sgHTML("input");
		$design->type = "button";
		$design->id = "new_dbp".$this->panel;
		$design->size = "8";
		$design->value  = "Design";
		$design->onclick = "sgPanel.setPanel('ajax', $this->panel, 'panel:$this->panel;element:'+document.getElementById('element_dbp$this->panel').value+';";
		$design->onclick .= "name:'+document.getElementById('name_dbp$this->panel').value+';method:design;', '', '', '');";	
		$div->innerHTML .= $design->render();

		$new = new sgHTML("input");
		$new->type = "button";
		$new->id = "new_dbp".$this->panel;
		$new->size = "8";
		$new->value  = "+";
		$new->onclick = "sgPanel.setPanel('ajax', $this->panel, 'panel:$this->panel;element:'+document.getElementById('element_dbp$this->panel').value+';";
		$new->onclick .= "name:;method:design;ielement:'+document.getElementById('element_dbp$this->panel').value+';imethod:new_element;', '', '', '');";	
		
		
		$div->innerHTML .= $new->render();
		return $div->render();	
	}// end function
	
	public function setMessage($message, $options=array()){
		
		$this->_message = array("message"=>$message, "options"=>$options); 	
		
	}// end function
	
	public function getMessage(){
		return $this->_message;	
	}
	
	public function getWinProp(){
		global $seq;
		
		if($this->win_prop != ""){
			
			return $this->_win_prop = $seq->cmd->get_param($this->win_prop);
			
			
		}
		return $this->_win_prop;
		

	}// end function
	
	public function log($info = false){
		
		if(!$this->_log){
			global $seq;
			if($info === false){
				
				$this->_log = $seq->log->set(
					array(
						"type"=>"I",
						"panel"=>$this->panel,
						"title"=>&$this->title,
						"element"=>$this->element,
						"name"=>&$this->name,
						"method"=>"",
						"title"=>$this->title
				));			
				
			}else{
				$this->_log = $seq->log->set($info);
			}// end if
			
		}else{
			
			$this->_log->add($info);
			
		}// end if
		
	}// end function

	public function logScript(){
		global $seq;
		return $seq->log->logScript($this->_log);
		
	}// end function

	
}// end class

?>