<?php


class sgJSON{
	
	static function encode($value, $options = 0, $depth = 512){
			
			//$json = json_encode(array_map(function($v){return is_string($v)? utf8_encode($v): $v;}, $a));
		return json_encode($value, $options, $depth);		
		
	}


	static function decode($json, $assoc = false, $depth = 512, $options = 0){
			
			//$json = json_encode(array_map(function($v){return is_string($v)? utf8_encode($v): $v;}, $a));
		return json_decode($json,  $assoc, $depth, $options);		
		
	}
	
	
	private $_propertys = array();
	
	public function set($name, $value=false){
		
		if($value===false){
			$this->_propertys[$name] = new stdClass;
		}else{
			
			$this->_propertys[$name] = $value;
		}
		
	}
	
	
	public function __set($name, $value){
        hr($name, "aqua","blue");
		
		
		
		if(!isset($this->_propertys[$name])){
			
			$this->_propertys[$name] = array();
		}
		$this->_propertys[$name] = $value;
		
        $this->_propertys[$name] = $value;
		
    }// end function

    public function __get($name){

        hr($name, "yellow");
        if(array_key_exists($name, $this->_propertys)) {
            return $this->_propertys[$name];
        }
    }// end function
	
	public function render(){
		
		hr($this->_propertys);
	}	
	
}//

?>