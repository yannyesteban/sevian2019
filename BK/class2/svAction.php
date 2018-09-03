<?php

class sgAction{
	private $_propertys = array();
	
	public $params = "";
		
	public function addParam($param, $value){
		
		if(!isset($this->_propertys["params"])){
			$this->_propertys["params"] = "";
		}
		
		$this->_propertys["params"] .= "$param:'$value';";
		
	}
	
	public function render(){
			
		$json = json_encode(array_map(function($v){return is_string($v)? utf8_encode($v): $v;}, $this->_propertys));
		$ref = "sv";
		return "$ref.send($json, this)";		
	}

	public function __set($name, $value){
        
        $this->_propertys[$name] = $value;
		
    }// end function

    public function __get($name){

        if(array_key_exists($name, $this->_propertys)) {
            return $this->_propertys[$name];
        }
		return "";
    }// end function
    
	public function __isset($name){
		
        return isset($this->_propertys[$name]);
		
    }// end function	
	
}

class svAction{
	
	static function send($opt){
		$json = json_encode(array_map(function($v){return is_string($v)? utf8_encode($v): $v;}, $opt));
		$ref = "sv";
		return "$ref.send($json, this)";
	}// end fucntion

	static function sendJSON($opt){
		$ref = "sv";
		$json = "";
		foreach($opt as $k => $v){
			$json .= (($json != "")?",":"")."\"$k\":$v";
		}
		return "$ref.send({{$json}}, this)";
	}// end fucntion	

	static function setPanel($opt){
		$opt["params"] = "set_panel:'".$opt["params"]."';";
		$json = json_encode(array_map(function($v){return is_string($v)? utf8_encode($v): $v;}, $opt));
		$ref = "sv";
		return "$ref.send($json, this)";
	}// end fucntion
	
}// end class
?>