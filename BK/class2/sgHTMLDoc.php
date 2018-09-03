<?php
/*****************************************************************
creado: 19/12/2015
por: Yanny Nuñez
Version: 1.0
*****************************************************************/

class sgHTMLDoc{
	public $type = "html";
	public $charset = SS_CHARSET;
	public $doctype = "<!DOCTYPE HTML>";
	public $content_type = "text/html; charset=";
	
	public function __construct($type = "html"){
		$this->type = $type;
		switch ($this->type){
		case "html":
			$this->html = new sgHTML("html");

			$this->head = new sgHTML("head");
			$this->head->appendChild("\n");
			$this->head->appendChild($this->contentType = new sgHTML("meta"));
			$this->head->appendChild("\n");
			$this->head->appendChild($this->title = new sgHTML("title"));
			$this->head->appendChild("\n");

			$this->body = new sgHTML("body");

			$this->html->appendChild("\n");
			$this->html->appendChild($this->head);
			$this->html->appendChild("\n");
			$this->html->appendChild($this->body);
			$this->html->appendChild("\n");
			break;
		case "xml":
			$this->xml = new sgHTML("");
			break;
		case "json":
			break;
		case "script":
			break;
		}// end switch
	}// end function
	
	public function setTitle($title){
		
		$this->title->innerHTML = $title;
		
	}// end function
	
	public function appendCssSheet($sheet){
		$link = new sgHTML("link");
		$link->href = $sheet;
		$link->rel = "stylesheet";
		$link->type = "text/css";
		$this->head->appendChild($link);
		$this->head->appendChild("\n");
	}// end function
	
	public function appendCssStyle($css=""){
		if(trim($css) != ""){

			$style = new sgHTML("style");
			$style->appendChild("\n".$css."\n");
			$this->head->appendChild($style);
			$this->head->appendChild("\n");			
			
		}
		

	}// end function	
	
	public function appendScriptDoc($src, $toEnd = false){
		$doc = new sgHTML("script");
		$doc->src = $src;		
		if(!$toEnd){
			$this->head->appendChild($doc);
			$this->head->appendChild("\n");			
		}else{
		//	$this->html->appendChild("\n");	
			$this->html->appendChild($doc);
			$this->html->appendChild("\n");		
		}
	}// end function

	public function appendScript($code, $toEnd = false){
		$script = new sgHTML("script");
		$script->innerHTML = $code."\n";
		if(!$toEnd){
			$this->head->appendChild($script);
			$this->head->appendChild("\n");			
		}else{
			$this->html->appendChild("\n");	
			$this->html->appendChild($script);
			$this->html->appendChild("\n");		
		}
	}// end function					
	
	public function render(){
		switch($this->type){
		case "html":
			if($this->content_type!=""){
				$this->contentType->setAttribute("http-equiv","Content-Type");
				$this->contentType->content = $this->content_type.$this->charset;
			}
			$html = $this->html->render();
			
			$this->appendCssStyle($this->html->getCss());
				
			return $this->doctype."\n".$html."\n<script>".$this->html->getScript()."</script>";
			break;
		case "xml":
			$this->doctype = "<?xml version=\"1.0\" encoding=\"$this->charset\"?>";
			$doc = $this->doctype;
			$doc .= "\n".$this->xml->render();
			return $doc;		
			break;	
		}// end switch
	}// end function	
	
}// end class
?>