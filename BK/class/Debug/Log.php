<?php
namespace Sevian\Debug;

class InfoPanel{
	
	public $panel = "";
	public $element = "";
	public $name = "";
	public $method = "";
	public $eparams = false;
	public $info = array();
	public $result = false;

	public function __construct($opt = array()){
		foreach($opt as $k => $v){
			if(property_exists($this, $k)){
				$this->$k = $v;
			}
		}
	}
	
	public function add($info){
		$this->info[] = $info;
	}

}

class Log{
	
	private static $_panels = array();
	private static $_last = false;
	
	public static function addPanel($info){
		return self::$_last = self::$_panels[] = new InfoPanel($info);
	}
	
	public static function getPanel(){
		return self::$_last;
	}
	
	public static function getPanels(){
		return self::$_panels;
	}
	
	public static function request(){
		return self::$_panels;
	}
	
}
?>