<?php

class _sgPanel{
	
	
	
	static $_element = "_sgPanel";

	public $panel = false;
	public $element = "";
	public $name = "";
	public $method = "";
	public $elem_params = "";
	public $fixed = true;
	
	public $eparams = array();

	public $html = "";
	public $script ="";
	public $css = "";

	protected $onDesing = true;
	protected $onDebug = true;
	
	private $icnn = SS_CNN;
	private $icns = SS_CNS;
	
	private $__init = array();
	
	private $_ref = false;
	
	static function setElementType($value){
		self::$_element = $value;
	}

	public function setRef($name){
		$this->_ref = $name;	
	}// end function

	public function getRef(){
		if($this->_ref != ""){
			return $this->_ref;
		}else{
			return "sevian.p[$this->panel]";	
		}// end if
	}// end function

	static function listActions(){
		
		
	}

	public function evalMethod(){
		
		
		
	}// end function	
	
		
	public function render(){
		
		$this->evalMethod($this->method);

		return $this->html;
		
	}// end function


	public function getScript(){
		return $this->script;
		
	}// end function

	public function getCss(){
		
		
	}// end function

	public function configPanel(){

		return "panel:$this->panel;element:$this->element;name:$this->name;method:$this->method;";
		
	}// end function



	public function __getInit(){
		
		return $this->__init;
		
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