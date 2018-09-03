<?php
session_start();

ini_set('default_charset', SS_CHARSET);
//ini_set('default_charset', 'UTF-8');'ISO-8859-1'
define ("SC_DATE_TIME_ZONE", "America/Caracas");
date_default_timezone_set(SC_DATE_TIME_ZONE);



//include (SS_PATH_CONFIG."config.php");
include ("sgJSON.php");
include ("connection.php");
include ("svAction.php");

include ("sg_html.php");
include ("sgHTMLDoc.php");
//include ("instructions.php");
include ("functions.php");
//include ("funciones_sg.php");

include ("_sgTab.php");
include ("_sgPage.php");
include ("_sgInput.php");
include ("_sgForm.php");

include ("_sgStructure.php");
include ("_sgPanel.php");


include ("sgDiagram.php");
//include ("cls_table.php");

include ("sg_panel.php");
//include ("sg_input.php");
include ("cfg_command.php");
//include ("cls_document.php");
//include ("cfg_sequence.php");

include ("sgLog.php");
//include ("cfg_procedure.php");
//include ("cfg_action.php");

include ("svForm.php");
//include ("sgForm.php");
//include ("sgMenu.php");


class var_sevian{
	
	public $mod=array();
	public $est=array();
	public $req=array();
	public $ses=array();
	public $exp=array();
	public $rec=array();
	
}// end class

class sevian extends sgHTMLDoc{
	
	private $_strConfig = false;
	private $_str = false;
	
	private $elemsInit = array();
	private $dataElems=array();		
	
	private $defaultTitle = SS_TITLE;
	
	//public $module = SS_MODULE;
	
	
	public $clsElement = array();

	
	public $panels = array();

	public $structure = "";
	public $template = SS_DEFAULT_TEMPLATE;
	public $user = "";
	
	public $elems = array();
	
	
	public $onDesing = SS_ON_DESIGN;
	public $onDebug = true;
	public $onAjax = false;
	public $onrefresh = false;
	
	public $strPanels = false;
	
	
	
	private $proc = null;
	
	private $_update = array();
	
	public $theme = SS_THEME;
	
	public $cmd = null;
	
	public $se = false;
	
	
	//public $module = SC_MODULE;
	public $type = "html";
	public $on_config = false;
	
	public $form_method = SS_REQUEST_METHOD;
	public $panel_default = "4";

	public $lastPanel = false;	
	
	public $modo_exec = "html";//xml
	public $path = "";
	public $v = false;
	
	public $est="";	
	
	public $themes = array();
	
	public $listen = array();
	public $esigns = array();
	public $signs = array();
	
	
	public $params = array();
	
	
	public $xml="";
	
	public $script = "";
	
	public $ajax = false;
	
	protected $_actions = array();
	
	protected $auxPanel = 1000;
	
	protected $_messages = array();
	protected $_scriptInit = "";
	
	protected $_clsElement = array();
	protected $_clsInput = array();
	
	protected $_cssFile = array();
	
	protected $_fragments = array();
	
	public function __construct(){
		$this->log = new sgLog;
		
		sgHTMLDoc::__construct();
		//$this->v=new var_sevian;
		
	}// end function
		
	public function addMessage($message = array()){
		$this->_messages[] = $message;
	}// end function

	public function getMessage(){
		return $this->_messages;
	}// end function
	
	public function setScript($script = ""){
		if($script == ""){
			return false;	
		}// end if
		$this->_scriptInit .= $script;
	}// end function

	public function getAuxPanel(){
		return $this->auxPanel++;
	}// end function

	public function getSW(){
		return ($this->vest["SW"] == "1")? "0": "1";
	}// end function

	public function loadClsInput(){
		
		foreach($this->clsInput as $k => $v){
			require_once($v["file"]);
			$this->_clsInput[$k][0] = $v["class"];
			$this->_clsInput[$k][1] = $v["type"];
		}// next 
		
	}// end function

	public function loadClsElement(){
		
		foreach($this->clsElement as $k => $v){
			require_once($v["file"]);
			//$this->_cssFile[] = $v["css"];
			$this->_clsElement[$k] = $v["class"];

			$v["class"]::setElementType($k);
			
			
			$this->_actions[$k] = $v["class"]::listActions();
		}// next 
		
	}// end function
	
	public function setTemplate($template){
		$this->template = $template;	
		
	}
	
	public function init($cn, $vses){
		
		if(isset($_REQUEST["__sg_ins"])){
			$ins = $_REQUEST["__sg_ins"];
		}else{
			do{
				$ins = date("Ymdhis").str_pad(rand(1,9999), 4, "0", STR_PAD_LEFT);
			}while(isset($_SESSION["VSES"][$ins]["SS_INS"]) && $_SESSION["VSES"][$ins]["SS_INS"] > 0);
		}// end if
		
		$this->loadClsInput();
		$this->loadClsElement();
		
		$this->ins = $ins;
		
		$this->session = &$_SESSION;
		$this->server = &$_SERVER;
		$this->vreq = &$_REQUEST;
		
		//hr($_REQUEST);
		//$s->modulo = SC_MODULE;
		
		$this->vses_ini = $vses;
		$this->a_cnn = $cn;		

		set_connections($this->a_cnn);
		$this->setSession();	

		$this->cmd = new cfg_command();
		$this->cmd->v->ses = &$this->vses; 
		$this->cmd->v->req = &$this->vreq;	
		$this->cmd->v->exp = array();	

		$this->onAjax = false;

		if(isset($this->cmd->v->req["ajax"])){
			$this->onAjax = true;
		}

		if($this->onAjax){
			$this->diagram = new sgXMLDiagram;
		}else{
			$this->diagram = new sgHTMLDiagram;
		}
		
		
		if(isset($this->vest["ACTIONS"])){
			
			$this->_actions = $this->vest["ACTIONS"];
		}

		$this->vest["ACTIONS"] = &$this->_actions;
		
		if(isset($this->vest["DATA_ELEMS"])){
			
			$this->dataElems = $this->vest["DATA_ELEMS"];
			
			foreach($this->dataElems as $panel => $dataElem){
				$dataElem->updated = false;
				
				if(!$this->onAjax and isset($dataElem->onAjax) and $dataElem->onAjax and $dataElem->panelType == 2){
					
					
					unset($this->dataElems[$panel]);
				}
			}// next
			
		}else{
			
			//$this->dataElems = array();
			
			foreach($this->elemsInit as $panel => $elem){
				
				$panel = $elem["panel"];
				
				if(isset($elem["typePanel"])){
					$typePanel = $elem["typePanel"];
				}else{
					$typePanel = 1;
				}
				
				if($panel === 0){

					$this->setMethod($elem);
					
				}else{
					$this->setPanel($elem, $typePanel);
				/*
					$this->dataElems[$panel] = new stdClass;
					
					foreach($elem as $k => $v){
						$this->dataElems[$panel]->$k = $v;
					}// next
					$this->dataElems[$panel]->updated = false;
					*/
					//$this->dataElems[$panel]->eparams = $elem;
					
				}// end if
				
			}// next

		}// end if

		$this->vest["DATA_ELEMS"] = &$this->dataElems;
		

	}// end function init

	public function evalRequest(){
		return;
		if($this->form_method != $this->server["REQUEST_METHOD"] and $this->server["argc"] > 0){
			header("Location: ".$this->server["PHP_SELF"]);
			exit;
		}// end if
	}// end function

	public function setSession(){
		
		if(isset($this->vreq["cfg_tgt_aux"])){
			$this->tgt = $this->vreq["cfg_tgt_aux"];
		}else{
			$this->tgt = 1;
		}// end if
		$this->vses = &$this->session["VSES"][$this->ins];
		$this->vest = &$this->session["VEST"][$this->ins][$this->tgt];
		
		//pr($this->session["VEST"]);
		
		if(!isset($this->vses["SS_INS"])){
			
			//$this->vses["SS_PATH"] = SS_PATH_LIB;
			$this->vses["SS_SERVER_ADDR"] = $this->server["SERVER_ADDR"];
			$this->vses["SS_REMOTE_ADDR"] = $this->server["REMOTE_ADDR"];
			$this->vses["SS_INS"] = $this->ins;
			
			$this->vses["SS_PATH_IMAGES"] = SS_PATH_IMAGES;
			//$this->vses["SS_PATH_DOWNLOADS"] = SS_PATH_DOWNLOADS;
			//$this->vses["SS_DEFAULT_CLASS"] = SS_DEFAULT_CLASS;
			//$this->vses["SS_THEME"] = SS_THEME;
			//$this->vses["SS_MODULE"] = $this->module;
			
			//$this->vses = array_merge($this->vses, $this->vses_ini);
 
			foreach($this->vses_ini as $k => $v){
				$this->vses[$k] = $v;
			
			}// end if
		}//end if
		

		
		
		$this->vses["SS_DATE_LOCAL"] = date("d/m/Y");
		$this->vses["SS_DATE"] = date("Y-m-d");
		//********************
		$hoy = getdate();
		$this->vses["SS_DATE_MONTH"] = $hoy["mon"];
		$this->vses["SS_DATE_YEAR"] = $hoy["year"];
		$this->vses["SS_DATE_DAY"] = $hoy["mday"];
		
		
		$c_mes=array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
		
		$this->vses["SS_DATE_NAME_MONTH"] = $c_mes[$hoy["mon"]-1];

		if(isset($this->vest["user"])){
			$this->user = $this->vest["user"];
		}// end if


		
		if(isset($this->vest["module"])){
			$this->module = $this->vest["module"];
		}// end if

		if(isset($this->vest["structure"])){

			$this->structure = $this->vest["structure"];
			$this->strPanels = $this->vest["str_panels"];
					
			
		}// end if


		if(isset($this->vest["template"])){
			$this->template = $this->vest["template"];
		}// end if
		
		//print_r($this->elems[100]);
		
		if(isset($this->vest["LISTEN"])){
			$this->listen = $this->vest["LISTEN"];
		}
		if(!isset($this->vest["SW"])){
			$this->vest["SW"] = "1";
			
		}
	
		
	}// end function
	
	public function setParam(){



		if(isset($this->cmd->v->req["__sg_panel"])){
			$this->lastPanel = $this->cmd->v->req["__sg_panel"];
		}else{
			$this->lastPanel = $this->panel_default;
		}
		
		if($this->onAjax){
			sleep(0);
		}
		
		$this->onrefresh = false;
		
		if(!$this->onAjax){
			
			if(isset($this->vest["SW"]) and isset($this->cmd->v->req["__sg_sw"])){
	
				if($this->vest["SW"] != $this->cmd->v->req["__sg_sw"]){
					$this->vest["SW"] = $this->cmd->v->req["__sg_sw"];
				}else{
					$this->onrefresh = true;
				}//end if
	
			}//end if
		}// end if
		
		$params = array();
		
		if(isset($this->cmd->v->req["__sg_params"])){

			//$params = $this->cmd->get_param($this->cmd->v->req["__sg_params"]);
			
			
			
			$params = $this->getCmd($this->cmd->v->req["__sg_params"]);
			//$params = $this->evalAction($this->cmd->v->req["__sg_params"]);

			
		}// end if
		
	}// end fucntion

	public function evalParams($param){

		foreach($param as $k => $v){
			
			if(isset($this->dataSubElems[$k])){


				if(isset($this->dataSubElems[$k]->defaultParam)){
					
					$this->dataSubElems[$k]->{$this->dataSubElems[$k]->defaultParam} = $v;
					
				}else{
					$this->dataSubElems[$k]->name = $v;
					
				}
				
				if(isset($this->dataSubElems[$k]->default_method)){
					
					$this->dataSubElems[$k]->method = $this->dataSubElems[$k]->default_method;
					
				}				
				
				
				$this->evalSubElem2($this->dataSubElems[$k], $param);
			}
			
		}// next
		
		
		
		if($this->panelSubmit){
			$panel = $this->panelSubmit;
		}else{
			$panel = $this->panel_default;
		}// end if
		
		
		
		if(isset($param["panel"])){
			
			if(/*$param["panel"] != "0" and */$param["panel"] != "" and $param["panel"] != "-1"){
				$panel = $param["panel"];
				
			}elseif($param["panel"] === ""){
				
				$panel = $this->panel_default;
				
			}// end if
			
		}// end if	
		
		$this->iMethod($panel, $param);
		
		if($panel == -2){
			
			$elem = $this->newElement($param["element"]);
			$elem->name = $param["name"];	
			$elem->eparams = $param;
			$elem->evalMethod($param["method"]);	
			
		}elseif($panel > 0){
			
			$this->setPanel($panel, $param);
			
		}else{

			$elem = $this->newElement($param["element"]);
			if(isset($param["name"])){
				$elem->name = $param["name"];
			}
			$method = "";
			if(isset($param["method"])){
				$method = $param["method"];
			}

				
			$elem->eparams = $param;
			$elem->evalMethod($method);			
			
		}
				
	}// end function
	
	public function setEsigns($panel, $esigns){

		foreach($esigns as $sign => $method){
			
			if(!isset($this->listen[$sign])){
				$this->listen[$sign] = array();
				
			}
			$this->listen[$sign][$panel] = $method;

		}// next
		
	}// end function

	public function addSigns($signs){
		
		$this->signs = array_merge($this->signs, $signs);
		
	}// end function

	public function evalCommand($cmd, $value){
		
		if(isset($this->a_cmd[$cmd])){

			$aux = $this->a_cmd[$cmd];
			$aux["element"] = $this->a_cmd[$cmd]["element"];
			$aux["name"] = $this->a_cmd[$cmd]["name"];
			$aux["method"] = $this->a_cmd[$cmd]["method"];
			$aux["eparam"] = $this->a_cmd[$cmd]["eparam"];
			
			$aux[$this->a_cmd[$cmd]["property"]] = $value;
			
			$elem = $this->newElement($aux["element"]);
			$elem->name = $aux["name"];
			$elem->eparams = $aux["eparam"];
			$elem->evalMethod($aux["method"]);
			
		}// end if

	}// end function

	public function evalSubElem2($dataElem, $param, $method = false){
		
		$elem = $this->newElement($dataElem->element);
			
		foreach($dataElem as $k => $v){
			$elem->$k = $v;
		}// next
		$elem->eparams = $param;
		if($method === false){
			$method = $elem->method;
		}
		
		$elem->evalMethod($method);
		
	}// end function

	public function evalSubElem($dataElem, $method = false){
		
		$elem = $this->newElement($dataElem->element);
			
		foreach($dataElem as $k => $v){
			$elem->$k = $v;
		}// next
		
		if($method === false){
			$method = $elem->method;
		}
		
		$elem->evalMethod($method);
		
	}// end function
	

	public function evalSigns($signs){
		
		//print_r($this->listen);
		
		
		foreach($signs as $sign){ 
		
			if(isset($this->listen[$sign])){
				//echo $sign;
				foreach($this->listen[$sign] as $panel => $method){
					
					if(isset($this->dataElems[$panel])){
		
						$this->evalPanel($this->dataElems[$panel], $method);
		
					}// end if					
					
				}// next

			}// end if	
		}// next
		
	}// end function

		
	public function HTMLDoc(){
		$d = $this->diagram;
		$d->onDesing = $this->onDesing;
		$panels = $d->setTemplate($this->template);

		$script1 = "\nvar SGC_PANEL=4;\nvar SGC_FORM_METHOD='GET';\nvar SGC_VERSION='1.0';\nvar SGC_PATH_IMAGES='".SS_PATH_IMAGES."';\nvar SGC_INS='".$this->ins."';";
		$script1 .= "\nvar SGC_SW='".$this->getSW()."';";

		foreach($panels as $panel){
			if(!isset($this->dataElems[$panel])){
				$this->setPanel(array(
					"panel"=>$panel,
					"element"=>false,
					"name"=>"",
					"method"=>false
				
				), 1);
				
			}// end if
			
		}// next

		if($this->onDesing){
			
			//$listPanels = implode(",",$panels);
			//$script1 .= "\nvar SGC_LIST_PANELS='".$listPanels."';";
			$panels = array();
			foreach($this->dataElems as $panel => $dataElem){
				$panels[$panel]=$panel;
				
				
			}// next
			$listPanels = implode(",",$panels);
			$script1 .= "\nvar SGC_LIST_PANELS='".$listPanels."';";
		}// end if





		$this->appendScript($script1);
		if(isset($this->themes[$this->theme])){
			
			foreach($this->themes[$this->theme]["css"] as $v){
				$this->appendCssSheet($this->themes[$this->theme]["path_css"].$v);
			}// next
		}
		foreach($this->jsFiles as  $k => $jsFile){
			$this->appendScriptDoc($jsFile["file"], $jsFile["begin"]);
		}// next

		$this->title->text = $this->defaultTitle;
	
		$structure = $this->getStructure();
	
	
		
	
	
		foreach($this->dataElems as $panel => $dataElem){
			
			$structure->load($this->evalPanel($dataElem));
			
		}// next
	
		$this->body->appendChild($structure);

		//$script2 = $structure->getScript();


$div = new sgHTML();

$div->script = '

//st.addPage("pg3", {caption:"9999"});
//st.addPage("pg3", {caption:"10.001"});
//st.addTab({});
//sevian.addTabPage({title:"Alpha"});
//sevian.addTabPage({title:"Beta"});

';
$this->body->appendChild($div);
//$this->appendScript($script2, true);
		if($d->css != ""){
			$this->appendCssStyle($d->css);
		}
		

		
		
		
		$m = array_merge($this->getMessage(), $d->getMessage());
		
		$text = "";
		foreach($m as $k =>$v){
			//$text .= "\nmessage.show('$v[message]')";
			
		}
		
		//print_r($this->cmd->v->req);
		
		/*   log*/
		
		
		//$this->log->setVars("vreq", $this->cmd->v->req);
		//$this->log->setVars("vses", $this->cmd->v->ses);
		//$this->log->setVars("vexp", $this->cmd->v->exp);




		
		//$this->appendScript($d->script.$text.$this->log->getJSON(), true);

		//$this->appendScript($d->script.$text, true);

		
	}// end function
	
	public function XMLDoc(){
	
		header('Content-type: text/xml');
		
		$d = $this->diagram;// = new sgXMLDiagram;
		$d->debug = &$GLOBALS["debug"];
		
		$this->evalSigns($this->signs);
	
		foreach($this->dataElems as $panel => $dataElem){
			
			if(isset($dataElem->updated)){
				
				if(!$dataElem->updated){
					continue;
				}
				
				$this->evalPanel($dataElem);

			}// end if
			
		}// next
		$this->xml = new sgHTML("");
				
		//$deb = new sgHTML("debug");
		//$deb->innerHTML = $GLOBALS["debug"];
		
		
				
		$xml = new sgHTML("sigefor");
		//$xml->appendChild($deb);
		
		$xml->appendChild($d);
		
		
		
		
		foreach($this->_fragments as $fragment){
			$xml->appendChild($fragment->getXML());
		}
		
		
		$tagScript = new sgHTML("script");
		
		$tagScript->innerHTML = $this->log->getJSON2();
		
		$xml->appendChild($tagScript);
		
		$this->xml->appendChild($xml);
		return;

	}// end function	

	public function evalDoc(){
		
		if($this->onAjax){
			$this->type	= "xml";
			return $this->XMLDoc();
		}else{
			return $this->HTMLDoc();	
		}// end if
		
	}// end fucntion

	public function render(){
		
		$this->setParam();		
		$this->evalDoc();
		
		//$this->vest["user"] = $this->user;
		//$this->vest["module"] = $this->module;
		//$this->vest["structure"] = $this->structure;
		//$this->vest["str_panels"] = $this->strPanels;
		
		$this->vest["template"] = $this->template;
		//$this->vest["DATA_ELEMS"] = &$this->dataElems;
		//$this->vest["DATA_SUB_ELEMS"] = &$this->dataSubElems;
		$this->vest["LISTEN"] = $this->listen;	
		
		
		
		

		return sgHTMLDoc::render();
		
	}// end fucntion

	public function &getVSes(){
		return $this->cmd->v->ses;
	}// end function

	public function setVar($k, $v){
		$this->cmd->v->ses[$k]=$v;
	}// end function

	public function getVar($k=false){
		if($k === false){
			return $this->cmd->v->ses;
		}
		
		if(isset($this->cmd->v->ses[$k])){
			return $this->cmd->v->ses[$k];
		}// end if
		return false;
	}// end function

	public function setReq($k, $v){
		$this->cmd->v->req[$k]=$v;
	}// end function

	public function getReq($k){
		if(isset($this->cmd->v->req[$k])){
			return $this->cmd->v->req[$k];
		}// end if
		return false;
	}// end function

	public function setExp($k, $v){
		$this->cmd->v->exp[$k]=$v;
	}// end function

	public function getExp($k){
		if(isset($this->cmd->v->exp[$k])){
			return $this->cmd->v->exp[$k];
		}// end if
		return false;
	}// end function

	public function setVarRec($v){
		$this->cmd->v->rec = $v;
	}// end function

	public function &getVarReq(){
		return $this->cmd->v->req;
	}// end function

	public function &getVarExp(){
		return $this->cmd->v->exp;
	}// end function

	public function evalExp($q, $default = false){
		return $this->cmd->evalVar($q, $default);
	}// end function

	public function get_param($q){
		return $this->cmd->get_param($q);
	}// end function

	public function getSequence($q){
		return $this->cmd->extract_query($q);
	}// end function

	public function procedure($procedure){

		$this->proc->execute($procedure);	
		
	}// end function

	public function secuenceX($secuence){
		
		$this->evalSequence($this->seq->execute($secuence));	
		
	}// end function

	public function sgInput($opt){
		
		$input = $opt["input"]; 
		
		
		if(isset($this->_clsInput[$input][0])){
			
			$opt["type"] = $this->_clsInput[$input][1];
			
			return new $this->_clsInput[$input][0]($opt);
		}
		
		//return new sgInput($name, "text");		
		
	}// end function

	public function input($input, $name){
		
		if(isset($this->_clsInput[$input][0])){
			
			return new $this->_clsInput[$input][0]($this->_clsInput[$input][1], $name);
		}
		
		return new sgInput($name, "text");		
		
	}// end function

	public function newElement($element){
		
		if(isset($this->_clsElement[$element])){
			$obj = new $this->_clsElement[$element];
			$obj->element = $element;
		}else{
			$obj = new sg_panel;
			$obj->element = "default";
		}
		return $obj;

	}// en function
	
	public function debugVars($id, $var){
		$t1 = new sgTable(2);
		$t1->border = "0";
		$t1->id = "sg_container_".$id;
		$f=0;
		foreach($var as $k => $v){
			

			$t1->insertRow();
			$t1->cells[$f][0]->text = $k;			
			$t1->cells[$f][1]->text = $v;			
			$f++;
		}// next
		return $t1->render();
				
	}// en function

	public function regAction($name, $sec = false, $type = "do"){
		
		$this->_actions[$type][$name] = $sec;
		
	}// en function

	public function evalAction($q){
		$params = $this->cmd->get_param($q);
		
		
		
		
		foreach($params as $type => $name){
			$cmd = $this->_actions[$type][$name];
			
			
			$this->getCmd($cmd);
		}
		
		
		
	}

	public function getCmd($q){
		
		global $seq;
		
		if(trim($q) == ""){
			return "";	
		}// end if
		$c = $seq->cmd->extract_query($q);
		foreach($c[0] as $k => $v){
			if($c[2][$k]!=""){
				$c[2][$k] = $seq->cmd->eval_var($c[2][$k]);
				eval("\$eval=".$c[2][$k].";");
				if($eval){
					$aux = $c[4][$k];
				}else{
					$aux = $c[5][$k];
				}// end if
				$this->getCmd($aux);
			}elseif($c[6][$k] != ""){
				$c[6][$k] = $seq->cmd->eval_var($c[6][$k]);
				eval("\$eval=".$c[6][$k].";");
				if($eval){
					$aux = $c[7][$k];
				}else{
					$aux = $c[8][$k];
				}// end if
				$this->getCmd($aux);
			}elseif($c[9][$k] != ""){
				$c[10][$k] = $seq->cmd->eval_var($c[10][$k]);
				eval("\$eval=".$c[10][$k].";");
				if($eval){
					$aux = $c[11][$k];
				}elseif($c[12][$k] != ""){
					$this->getCmd("case;".$c[12][$k]."default:".$c[13][$k]."endcase;");
				}elseif($c[13][$k]!=""){
					$aux = $c[13][$k];
				}else{
					$aux="";
				}// end if					
				$this->getCmd($aux);
			}elseif($c[16][$k] != ""){
				$this->command($c[15][$k], $c[16][$k]);
			}elseif($c[17][$k] != ""){
				$this->command($c[15][$k], $c[17][$k]);
			}else{
				$this->command($c[15][$k], $c[18][$k]);
			}//end if
		}// next
	}// end function	

	public function sequence($sequence){
		foreach($sequence as $cmd => $value){
			
			$this->command($cmd, $value);
			
		}
		
		
	}// en function
	
	public function command($cmd, $value){
		
		switch($cmd){
			case "vses":
				$this->setVariables($this->cmd->v->ses, $value);
				break;			
			case "vexp":
				$this->setVariables($this->cmd->v->exp, $value);
				break;	
			case "vreq":
				$this->setVariables($this->cmd->v->req, $value);
				break;
			case "set_params":
				$this->params = array_merge($this->params, $this->cmd->get_param($value));
				break;
			case "set_panel":
				if($value != ""){
					
					$this->setPanel($this->cmd->get_param($value));
				}
				break;
			case "set_method":
				if($value != ""){
					$this->setMethod($this->cmd->get_param($value));
				}
				break;
			case "imethod":
				$this->iMethod($value);
				break;
			default:
			
			
				if(isset($this->a_cmd[$cmd])){
					$this->evalCommand($cmd, $value);
				}else{
					//$this->db->error("cmd not exist");	
				}
				break;
		}// end switch
		
	}// end function
	
	public function setVariables(&$var, $q){

		$aux = explode(",", $q);
		foreach($aux as $k2 => $v2){
			$aux2 = explode("=",$v2);
			$var[$aux2[0]]=$aux2[1];
		}// next		
		
	}// end function
	
	public function setMethod($param){
		
		$elem = $this->newElement($param["element"]);

		$elem->element = $param["element"];
		$elem->panel = $param["panel"];
		$elem->name = $param["name"];
		$elem->method = $param["method"];
		$elem->eparams = $param;
		
		
		
		
		$elem->evalMethod($elem->method);
		
	
		
	}// end function
	
	public function iMethod($method){
		
		$panel = $this->lastPanel;
		
		if(isset($this->dataElems[$panel])){
			$dataElems = $this->dataElems[$panel];
		}else{
			return false;
		}// end if
		
		$elem = $this->newElement($dataElems->element);
			
		foreach($dataElems as $k => $v){
			$elem->$k = $v;
		}// next

		$elem->eparams = $this->params;
		$elem->evalMethod($method);

		//$this->addMessage($elem->getMessage());
		//$this->addSigns($elem->getSigns());
		
	}// end function	
	
	public function setPanel($param, $typePanel = 2){
		
		
		
		if(isset($param["panel"]) and $param["panel"] != "" and $param["panel"] != "-1"){
			$panel = $param["panel"];
		}else{
			$panel = $this->lastPanel;
		}// end if

		if(!isset($this->dataElems[$panel])){

			$this->dataElems[$panel] = new stdClass;
			$this->dataElems[$panel]->element = false;
			$this->dataElems[$panel]->panelType = $typePanel;
			
			$this->dataElems[$panel]->design_mode = $this->onDesing;
			$this->dataElems[$panel]->onAjax = $this->onAjax;
			//hr("\n".$panel."....".$this->dataElems[$panel]->panelType."\n\n");
				
			
		}


		$info = &$this->dataElems[$panel];
		
		
		foreach($param as $key => $value){
			$info->$key = $value;
		}// next
		
		$info->eparams = $param;
		$info->updated = true;
		$info->panel = $panel;
		//$info->onAjax = $this->onAjax;
		//hr($this->dataElems,"yellow");
		
	}
	
	public function evalPanel($param){
		
		
		
		$elem = $this->newElement($param->element);

		$elem->element = $param->element;
		$elem->panel = $param->panel;
		$elem->name = $param->name;
		$elem->method = $param->method;
		$elem->eparams = $param->eparams;
		$elem->design_mode = $param->design_mode;

		return $elem;
			
		//$this->diagram->appendChild($elem);
		
	}// end function	

	public function resetPanels(){
		
		foreach($this->dataElems as $panel => $dataElems){
				
			if(!isset($dataElems->fixed) or !$dataElems->fixed){
				
				unset($this->dataElems[$panel]);
			}
		}// next
		
	}// end function

	public function setFragment($target, $html, $script = false, $css = false, $typeAppend = "1"){
	
		$fragment = new sgFragment($target, $html, $script, $css, $typeAppend);
	
		$this->_fragments[] = $fragment;
		
		return $fragment;
	
	}// end function	 
	
	public function setFragmentP($opt){
	
		$fragment = new sgFragmentP($opt);
	
		$this->_fragments[] = $fragment;
		
		return $fragment;
	
	}// end function	 

	public function loadElements($elems){
		if(is_array($elems)){
			$this->elemsInit = $elems;
		}
		
	}
	
	public function setStrConfig($config){
		
		$this->_strConfig = $config;
		
	}// end function

	public function getStructure(){
		if(!$this->_str){
			return new _sgStructure($this->_strConfig);	
		}else{
			return $this->_str;
		}
		
		
	}// end function

	public function setStructure($str){
		
		$this->_str = $str;
		
	}// end function

	
}// end class

$seq = new sevian;

$seq->setStrConfig($strConfig);

$seq->loadElements($elemsInit);
$seq->clsInput = $clsInput;
$seq->clsElement = $clsElement;



$seq->a_cmd = $cmd;


$seq->themes = $themes;
$seq->jsFiles = $jsFiles;

$seq->init($cn, $vses);
echo $seq->render();


?>