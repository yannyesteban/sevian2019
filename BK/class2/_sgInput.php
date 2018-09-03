<?php

class _sgInput{
	
	
	
	private $_ref = false;
	private $_ini = false;

	
	public function __construct($opt){
	
		
		
		foreach($opt as $k => $v){
			
			$this->$k = $v;
		}
		
		//$this->_ini = $opt;
		
	}
	public function setRef($name){
		$this->_ref = $name;	
	}// end function

	public function getRef(){
		return $this->_ref;	
	}// end function		
	public function render(){
		$e = new sgHTML("input");
		$e->type = "text";
		$e->name = $this->name;
		$e->id = $this->id;
		
		
		
		
		$e->placeholder = $this->placeholder;
		return $e->render();
		
	}

	public function getScript(){
		if($ref = $this->getRef()){
			
			$json = json_encode($this);
			return "\n$ref = new _sgInput($json)";
			
			
		}
		
	}
	

	public function getJson(){
		return json_encode($this, JSON_PRETTY_PRINT);
		
	}
	
	
	
}// class



?>