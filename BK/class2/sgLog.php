<?php
class sgLogLine{

	public $id = "";
	public $line = array();

	public function __construct($info){
		$this->id = $info;
	}	
	
	public function add($info){
		//$this->e = array_merge($this->e, $info);
		
		$info = array_map(function($v){return is_string($v)? utf8_encode($v): $v;}, $info);
		$this->line = array_merge($this->line, $info);
		//$this->line[] = $info;
	}

}

class sgLog{

	private $index = 0;
	private $_vars =  array();
	private $_line =  array();
	private $e = false;
	private $lines =  array();

	public function set($info){
		$info = array_map(function($v){return is_string($v)? utf8_encode($v): $v;}, $info);
		$this->e = $this->lines[] = new sgLogLine($info);
		return $this->e;
	}// end function
	
	public function get(){
		return $this->e;	
	}// end function

	public function setVars($name, $vars){
		$this->_vars[$name] = $vars;	
	}// end function

	public function getJSON(){
		
		$str = "";
		
		foreach($this->_vars as $name => $var){
			$var = @array_map('htmlentities', $var);
			$json = @json_encode($var);
			//print_r($var);
			$str .= "\n\tsgLog.vars['$name'] = $json;";
				
		}// next

		
		foreach($this->lines as $k => $v){
			
			$json = json_encode($v);
			
			$str .= "\n\tsgLog.lines.push($json);";			
			
		}// next
		$str .= "\n\tsgLog.init();";			
		
		return $str;
		
	}// end function

	public function getJSON2(){
		
		$str = "";
		foreach($this->lines as $k => $v){
		
			$json = json_encode($v);
			$str .= "\n\tsgLog.addLine($json);";			
			
		}// next
		//$str .= "\n\tsgLog.init();";			
		
		return $str;
		
	}// end function

	public function logScript($line){
		
		$str = "";
		
		$json = json_encode($line);
		$str .= "\n\tsgLog.addLine($json);";			
		
		return $str;
		
	}// end function

	
}// end class
?>