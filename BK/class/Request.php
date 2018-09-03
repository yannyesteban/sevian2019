<?php

namespace Sevian;
class Request{
	
	public $template = "sevian 2.0";
	public $elements = array();
	public $actions	= array();
	public $listen = array();
	
	public $themes = array();
	public $theme = "sevian";

	private $cssSheets = array();
	private $cssSheetsDefault = array();
	private $jsFiles = array();
	private $jsFilesDefault = array();
	private $templateName = false;
	
	public function __construct($opt = false){
		if($opt != false){
			foreach($opt as $k => $v){
				$this->$k = $v;
			}
		}
		global $sevian;
		/*
		foreach($this->elements as $k => $e){
			$e["updated"] = false;
			$sevian->setPanel(new InfoPanel($e));
		}
		*/
		$a='[
		{
			"setPanel":{
				"panel":4,
				"element":"Fragment",
				"name":"uno",
				"method":"load",
				
				"eparams":{
					"record":{
						"cedula":12474737,
						"tipo":"v"
					},
					"page":1

				}
			}
				
		},
		{
			"vses":{
				"xx":"Yanny Esteban"
			},
			"vexp":{
				"aux":"Núñez"
			}
		},
		{
			"vreq":
				{"abc":"Jiménez"}
		
		}
		
	]';
		
		
		
		
		$seq =json_decode($a);
		
		
		
		//echo $seq[0]->setPanel->name;
		
		//$sevian->sequence($seq);
		
		
		
		return;
		
		
		if(isset($sevian->req["__sg_params"]) and $sevian->req["__sg_params"] != ""){
			
			
			$aux = new InfoPanel();
			
			$aux->panel = $sevian->req["__sg_params"];
			$aux->element = "menu";
			$aux->name = "form2";
			//$sevian->setPanel($aux);
			//echo $sevian->req["__sg_params"];
			
		}
		
		
	}
	
	private function htmlDoc(){
		global $sevian;
		
		
		$doc = new Document();
		/*
		$meta1 = new HTML("meta");
		$meta1->{"http-equiv"} = "Content-Type";
		$meta1->content = "text/html; charset=utf-8";

		$meta2 = new HTML("meta");
		$meta2->name = "viewport";
		$meta2->content = "width=device-width, initial-scale=1";
		
		$doc->addMeta($meta1);
		$doc->addMeta($meta2);
		*/

		$doc->setTitle("Sevian 2017");
		
		foreach($this->cssSheetsDefault as $v){
			$doc->appendCssSheet($v);
		}
		
		if(isset($this->themes[$this->theme])){
			foreach($this->themes[$this->theme]["css"] as $v){
				//$doc->appendCssSheet($this->themes[$this->theme]["path_css"].$v);
				$doc->appendCssSheet($v);
			}
			foreach($this->themes[$this->theme]["templates"] as $k => $v){
				//$this->_templates[$k] = $this->themes[$this->theme]["path_html"].$v;
				$this->_templates[$k] = $v;
			}
		}
		
		foreach($this->cssSheets as $v){
			$doc->appendCssSheet($v);
		}
		
		foreach($this->jsFilesDefault as $v){
			$doc->appendScriptDoc($v["file"], $v["begin"]);//
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
	
	public function render(){
		global $sevian;
		
		if($sevian->onAjax){
			header('Content-type: application/json; charset=utf-8');
			return $sevian->getRequest();
			/*
			$request = $sevian->evalPanels();
			return json_encode($request);
			
			if($sevian->templateChanged){
				$request[0] = new jsonRequest();
				$request[0]->targetId = false;
				$request[0]->html = $sevian->evalTemplate()->render();
				$request[0]->script = $sevian->script;
				$request[0]->title = $sevian->title;

			}else{
				$request = $sevian->evalPanels();
				
			}
			
			return json_encode($request);
			*/
		}else{
			$html = $this->htmlDoc();
			return $html;
		}
		
	}
	
}// end class
?>