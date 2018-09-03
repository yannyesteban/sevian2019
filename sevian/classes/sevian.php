<?php
namespace Sevian;
include 'Tool.php';
include 'Connection.php';
include 'HTML.php';
include 'Document.php';
include 'Structure.php';
include 'Panel.php';

include 'Input.php';
include 'Page.php';
include 'Form.php';



function hr($msg_x, $color_x='black',$back_x=''){
	
	//echo '**('.$GLOBALS['debug'].')**__';
	
	if(is_array($msg_x) or is_object($msg_x)){
		
		$msg_x = print_r($msg_x, true);
	}
	
	
	if(isset($_GET['ajax']) or isset($_POST['ajax'])){
		$GLOBALS['debugN']++;
		$GLOBALS['debug'] .= $GLOBALS['debugN'].': '.$msg_x.'\n';
		
		//echo $GLOBALS['debug'];
		return;	
		
	}
	
	if ($color_x==''){
		echo "<hr>$msg_x<hr>";
	}else{
		echo "<hr><span style=\"background-color:$back_x;color:$color_x;font-family:tahoma;font-size:9pt;font-weight:bold;\">$msg_x</span><hr>";
	}// end if
	
}// end function

class InfoWindow{
	public $caption = false;
	public $mode = 'custom';
	public $width = '300px';
	public $height = '300px';
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
	public $element = '';
	public $name = '';
	public $method = '';
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
class InfoThemes{
	
	public $css = [];
	public $js = [];
	public $templates = [];
	public function __construct($opt = array()){
		foreach($opt as $k => $v){
			if(property_exists($this, $k)){
				$this->$k = $v;
			}
		}
	}
}
class S{
	public static $title = 'SEVIAN 2017.10';
	public static $theme = [];
	public static $templateName = '';
	
	public static $elements = [];
	
	public static $cfg = [];
	
	public static $ses = [];
	public static $req = [];
	public static $exp = [];
	
	private static $ins = false;
	private static $onAjax = false;
	
	public static $_js = [];
	private static $_css = [];
	
	private static $_templates = false;
	private static $_themes = [];
	private static $_template = false;
	private static $_strPanels = false;
	private static $_templateChanged = false;
	
	private static $_elements = [];
	private static $_inputs = [];
	
	
	private static $_info = [];// se guarda la informacion de cada panel ;
	
	private static $_infoClasses = [];
	private static $_infoInputs = [];
	private static $_pSigns = [];
	private static $_signs = false;
	private static $_commands = false;
	private static $_actions = false;
	private static $_fragments = false;
	private static $script = '';
	
	private static $_clsElement = [];
	private static $_mainPanels = [];
	
	private static $lastAction = false;
	
	public static function setSes($key, $value){
		self::$ses[$key] = $value;
	}
	public static function setReq($key, $value){
		self::$req[$key] = $value;
	}
	public static function setExp($key, $value){
		self::$exp[$key] = $value;
	}
	public static function &getSes($key){
		return self::$ses[$key];
	}
	public static function &getReq($key){
		return self::$req[$key];
	}
	public static function &getExp($key){
		return self::$exp[$key];
	}
	public static function &getVSes(){
		return self::$ses;
	}
	public static function &getVReq(){
		return self::$req;
	}
	public static function &getVExp(){
		return self::$exp;
	}
	public static function jsInit($js = []){
		self::$_js = $js;
	}
	public static function cssInit($css = []){
		self::$_css = $css;
	}
	public static function configInit($opt){
		foreach($opt as $k => $v){
			if(property_exists(__CLASS__, $k)){
				self::$$k = $v;
			}
		}
	}
	public static function sessionInit(){
		
		
		
		
		if(isset($_REQUEST['__sg_ins'])){
			self::$ins = $_REQUEST['__sg_ins'];
		}else{
			self::$ins = uniqid('p');
		}

		session_name(self::$ins);
		session_start();
		
		self::$cfg = &$_SESSION;
		self::$req = &$_REQUEST;
		
		self::$ses = &self::$cfg['VSES'];
		self::$onAjax = self::getReq('__sg_async');
		
		if(!isset(self::$cfg['INIT'])){
			
			self::$cfg['INIT'] = true;
			self::$cfg['SW'] = 1;
			self::$cfg['INFO'] = [];
			self::$cfg['AUTH'] = false;
			
			self::$cfg['VSES'] = [];
			self::$cfg['TEMPLATE'] = &self::$_template;
			self::$cfg['STR_PANELS'] = &self::$_strPanels;
			
			self::$_infoClasses = self::$_elements;
			
			if(isset($opt['clsInput'])){
				self::$_infoInputs = $opt['clsInput'];
			}
			
			
			
			foreach(self::$elements as $k => $e){
				self::setPanel(new InfoParam($e));
			}

			
			if(isset($opt['commands'])){
				self::$_commands = $opt['commands'];
			}
			if(isset($opt['actions'])){
				self::$_actions = $opt['actions'];
			}
			
			
			
			if(isset($opt['signs'])){
				self::$_signs = $opt['signs'];
			}
			self::$cfg['INFO_CLASSES'] = &self::$_infoClasses;
			self::$cfg['INFO_INPUTS'] = &self::$_infoInputs;
			self::$cfg['LISTEN_PANEL'] = &self::$_pSigns;
			self::$cfg['LISTEN'] = &self::$_signs;
			self::$cfg['COMMANDS'] = &self::$_commands;
			self::$cfg['ACTIONS'] = &self::$_actions;
		}else{
			self::$cfg['INIT'] = false;
			
			self::$cfg['SW'] = (self::$cfg['SW'] == '1')? '0': '1';
			
			self::$_infoClasses = &self::$cfg['INFO_CLASSES'];
			self::$_infoInputs = &self::$cfg['INFO_INPUTS'];
			
			self::$_info = &self::$cfg['INFO'];
			self::$template = &self::$cfg['TEMPLATE'];
			self::$_strPanels = &self::$cfg['STR_PANELS'];
			
			self::$_signs = &self::$cfg['LISTEN'];
			
			self::$_pSigns = &self::$cfg['LISTEN_PANEL'];
			self::$_commands = &self::$cfg['COMMANDS'];
			self::$_actions = &self::$cfg['ACTIONS'];
		}
		
		foreach(self::$_info as $info){
			$info->update = false;
		}
		
		foreach(self::$_infoClasses as $name => $info){
			self::setClassElement($name, $info);
		}
		
		foreach(self::$_infoInputs as $name => $info){
			self::setClassInput($name, $info);
		}
		
		if(self::$cfg['INIT'] and isset($opt['sequenceInit'])){
			self::sequence($opt['sequenceInit']);
		}
		
		if(isset($opt['sequence'])){
			self::sequence($opt['sequence']);
		}
		
		self::evalParams();
	}
	public static function init($opt = []){
		
	}

	public static function addClassInput($name, $info){

		if(isset($info["file"]) and $info["file"] != ""){
			require_once($info["file"]);
		}

		self::$_inputs[$name] = $info;


	}
	public static function inputsLoad($inputs){

		foreach($inputs as $k => $v){

			self::addClassInput($k, $v);
		}


		
	}
	public static function elementsLoad($elements){
		self::$_elements = $elements;
	}
	public static function themesLoad($themes){
		self::$_themes = $themes;
	}
	public static function commandsLoad($inputs){
		
	}
	public static function vars($q){
		return Tool::vars($q, array(
			array(
				'token' 	=> '@',
				'data' 		=> self::$ses,
				'default' 	=> false
			),
			array(
				'token'		=> '\#',
				'data' 		=> self::$req,
				'default' 	=> false
			),
			array(
				'token' 	=> '&EX_',
				'data' 		=> self::$exp,
				'default' 	=> false
			),
		));
	}
	
	public static function evalParams(){
		if(isset(self::$req["__sg_params"]) and self::$req["__sg_params"] != ""){
			self::$sequence(json_decode(self::$req["__sg_params"]));
			
		}
	}
	public static function setTemplate($template = ''){
		self::$_template = $template;
		self::$_templateChanged = true;
	}
	public static function getTemplate(){
		return self::$_template;
	}
	public static function evalTemplate(){
		/*$div = new HTML('div');
		$div->text = self::getTemplate();
		return $div;
		*/
		
		$request = false;
		
		$str = new Structure();
		
		$str->setTemplate(self::vars(self::getTemplate()));
		
		if(self::$_templateChanged){
			self::$_strPanels = $str->getStrPanels();
			foreach(self::$_strPanels as $panel){
				if(!isset(self::$_info[$panel])){
					self::setPanel(new InfoParam(['panel' => $panel]));
				}
			}
		}
		
		foreach(self::$_info as $panel => $e){
			
			self::resetPanelSigns($panel);
			
			$elem = self::getElement($e); 
			
			$aux = self::configInputs(array(
				'__sg_panel'	=>$panel,
				'__sg_sw'		=>self::$cfg['SW'],
				'__sg_sw2'		=>self::$cfg['SW'],
				'__sg_ins'		=>self::$ins,
				'__sg_params'	=>'',
				'__sg_async'	=>'',
				'__sg_action'	=>self::$lastAction,
				'__sg_thread'	=>''
			
			));
			
			$form = new HTML(array('tagName'=>'form', 'action'=>'', 'name'=>'form_p$panel', 'id'=>'form_p$panel', 'method'=> 'POST', 'enctype'=>'multipart/form-data'));
			$form->add($elem);
			$form->add($aux);
			
			//self::setMainPanel($panel, "ImgDir", $elem->getMain());
			
			if(isset(self::$_strPanels[$panel])){
				$div = new HTML(array('tagName'=>'div', 'id'=>'panel_p$panel'));
				$div->add($form);
				$str->addPanel($panel, $div);
			}else{
				
				$win = new InfoWindow(array(
					'caption'=>'hola $panel'	
				));

				$elem->setWinParams($win);

				$request[] = new InfoRequest(array(
					'panel'		=> $panel,
					'targetId'	=> 'panel_p$panel',
					'html'		=> $form->render(),
					'script'	=> $form->getScript(),
					'css'		=> $form->getCss(),
					'typeAppend'=> 1,
					'hidden'	=> false,
					'title'		=> $elem->title,
					'window'	=> $elem->getWinParams(),
				));
			}
			
		}
		
		$opt = new \stdClass;
		$opt->INS = self::$ins;
		$opt->SW = self::$cfg['SW'];
		$opt->mainPanel = 1;
		
		if($request){
			$opt->request = $request;
		}
		
		$opt->fragments = self::getFragment();
		
		$json = json_encode($opt, JSON_PRETTY_PRINT);
		
		self::$script = "\nsevian.init($json);";
		
		self::$cfg['INFO'] = self::$_info;
		
		return $str;
		
	}
	
	public static function sgInput($info){
		
		if(is_array($info)){
			$info = new InfoInput($info);
		}
		
		if(isset(self::$_inputs[$info->input])){
			$info->type = self::$_inputs[$info->input]["type"];
			$obj = new self::$_inputs[$info->input]["class"]($info);
		}else{
			$obj = new Input($info);
		}

		return $obj;

	}
	
	public static function setMainPanel($panel, $type, $main){
		
		self::$_mainPanels[$panel] = [
			"panel"=>$panel,
			"type"=>$type,
			"opt"=>$main
		] ;
	}
	
	private static function configInputs($_vconfig){
		$div = new HTML('');
		
		foreach($_vconfig as $k => $v){
			$input = $div->add(array(
				'tagName'	=>	'input',
				'type'		=>	'hidden',
				'name'		=>	$k,
				'value'		=>	$v
			));
		}
	
		return $div;
		
	}
	public static function getFragment(){
		return self::$_fragments;
		
	}
	public static function setClassElement($name, $info){
		//require_once($info["file"]);
		
		if(isset($info['file']) and $info['file'] != ''){
			require_once($info['file']);
		}
		self::$_clsElement[$name] = $info['class'];
	}
	public static function sgElement($info){
		
		if(isset($this->_clsElement[$info->element])){
			$obj = new $this->_clsElement[$info->element]($info);
			
		}else{
			$obj = new SgPanel($info);
			
		}
		return $obj;

	}
	public static function getElement($info){
		
		if(isset(self::$_clsElement[$info->element])){
		
			$obj = new self::$_clsElement[$info->element]($info);
			
		}else{
		
			$obj = new Panel($info);
			
		}
		return $obj;
		//return $this->sgElement($info);
		
	}
	public static function setPanel($info, $update = false){
		
		if($info->panel){
			$info->update = $update;
			self::$_info[$info->panel] = $info; 
		}
		
		
	}
	public static function resetPanelSigns($panel){
		
		if(isset(self::$_pSigns[$panel])){
			unset(self::$_pSigns[$panel]);
		}
	}
	public static function htmlDoc(){
		global $sevian;
		
		
		$doc = new Document();
		/*
		$meta1 = new HTML('meta');
		$meta1->{'http-equiv'} = 'Content-Type';
		$meta1->content = 'text/html; charset=utf-8';

		$meta2 = new HTML('meta');
		$meta2->name = 'viewport';
		$meta2->content = 'width=device-width, initial-scale=1';
		
		$doc->addMeta($meta1);
		$doc->addMeta($meta2);
		*/

		$doc->setTitle(self::$title);
		
		foreach(self::$_css as $v){
			$doc->appendCssSheet($v);
		}
		foreach(self::$_js as $k=> $v){
			$doc->appendScriptDoc($v['file'], false);
		}

		$templates = [];
		
		if(isset(self::$_themes[self::$theme])){
			$theme = new InfoThemes(self::$_themes[self::$theme]);
			foreach($theme->css as $css){
				$doc->appendCssSheet($css);
			}
			foreach($theme->templates as $k => $v){
				self::$_templates[$k] = $v;
			}
			$templates = $theme->templates;
		}
		
		if(!self::getTemplate()){
			if(self::$templateName and isset($templates[self::$templateName])){
				self::setTemplate(file_get_contents($templates[self::$templateName]));
			}else{
				self::setTemplate(self::$template);
			}
		}
		
		
		$_body = self::evalTemplate()->render();
		$_script = self::evalTemplate()->getScript();
		$_css = self::evalTemplate()->getCss();
		
		$doc->appendCssStyle($_css);
		$doc->appendScript($_script, true);
		
		$doc->body->text = $_body;
		
		
		//$doc->body->add(self::evalTemplate());
				
		$doc->appendScript(self::$script, true);
		//hr(self::$_mainPanels, "green");
		$json = json_encode(self::$_mainPanels, JSON_PRETTY_PRINT);
		$script = "sevian.loadPanels($json)";
		
		$doc->appendScript($script, true);
		
		return $doc->render();
		
		foreach($this->cssSheetsDefault as $v){
			$doc->appendCssSheet($v);
		}
		
		if(isset($this->themes[$this->theme])){
			foreach($this->themes[$this->theme]['css'] as $v){
				//$doc->appendCssSheet($this->themes[$this->theme]['path_css'].$v);
				$doc->appendCssSheet($v);
			}
			foreach($this->themes[$this->theme]['templates'] as $k => $v){
				//$this->_templates[$k] = $this->themes[$this->theme]['path_html'].$v;
				$this->_templates[$k] = $v;
			}
		}
		
		foreach($this->cssSheets as $v){
			$doc->appendCssSheet($v);
		}
		
		foreach($this->jsFilesDefault as $v){
			$doc->appendScriptDoc($v['file'], $v['begin']);//
		}
		
		foreach($this->jsFiles as $v){
			$doc->appendScriptDoc($v, true);
		}
		if(!$sevian->getTemplate()){
			if($this->templateName and isset($this->_templates[$this->templateName])){
				$this->template = file_get_contents($this->_templates[$this->templateName]);
			}
			$sevian->setTemplate($this->template);
		}
		
		
		$doc->body->add($sevian->evalTemplate());
		
		$doc->appendScript($sevian->script, true);
		
		$doc->setTitle($sevian->title);
		
		return $doc->render();
	}
	public static function render(){
		self::sessionInit();
		self::init();
		
		
		return self::htmlDoc();
	}
}



