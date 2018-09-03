<?php


namespace Sevian;

class InfoTabPage{
	
	
	public $title = "";
	public $child = "";
	public $class = "";
	
	public function __construct($opt = []) {
		
		if(is_array($opt)){
			foreach($opt as $k => $v){
				$this->$k = $v;
			}
		}
	}
}

class Tab implements \JsonSerializable {
	
	
	public $name ="";
	public $class = "";
	
	private $_main = false;
	private $_menu = false;
	private $_body = false;
	
	private $pages = [];
	
	
	public $nTab = 0;
	
	
	public $value = 0;
	
	public $css = "";
	public $_tab = [];
	public $body = [];
	private $_ref = "tab";
	private $tabs = [];
	
	public function jsonSerialize() {
        return [
			"id"	=> $this->id,
			"class"	=> "summer",
			"value"	=> 0
		];
    }
	
	public function __construct($opt = []) {
		
		if(is_array($opt)){
			foreach($opt as $k => $v){
				$this->$k = $v;
			}
		}
		
		//$this->name = $name;
		$this->_main = new HTML("div");
		$this->_menu = new HTML("div");
		$this->_body = new HTML("div");
		
		$this->_main->id = $this->name;
		$this->_menu->id = $this->name."_menu";
		$this->_body->id = $this->name."_body";
		$this->_main->appendChild("\n");
		$this->_main->appendChild($this->_menu);
		$this->_main->appendChild("\n");
		$this->_main->appendChild($this->_body);
		$this->_main->appendChild("\n");
		
		$this->_main->{"data-tab-type"} = "main";
		
		$this->_main->class = "sg-tab-main";
		$this->_menu->class = "sg-tab-menu";
		$this->_body->class = "sg-tab-body";
		
		$this->_main->id = $this->id;
		
		foreach($this->pages as $page){
			$this->add($page);
		}
		
		
		
	
	}// end function
	
	public function setClass($class = ""){
		$this->_main->class = $class;
		
	}	
	
	public function setRef($name){
		$this->_ref = $name;	
	}// end function
	public function getRef(){
		return $this->_ref;	
	}// end function
	public function add($opt){
		
		
		$opt = new InfoTabPage($opt);
		
		$title = $opt->title;
		$body = $opt->child;
		$class = $opt->class;
		
		
		$this->_tab[$this->nTab] = new HTML("a");
		$this->_tab[$this->nTab]->appendChild($title);
		$this->_tab[$this->nTab]->href = "javascript:void(0);";
		$this->_tab[$this->nTab]->class = "sg-tab-imenu";
		
		
		$this->body[$this->nTab] = new HTML("div");
		$this->body[$this->nTab]->id = $this->name."_body_".$this->nTab;
		$this->body[$this->nTab]->class = "sg-tab-ibody";
		if($body){
			$this->body[$this->nTab]->appendChild($body);
		}// end if

		$this->_menu->appendChild("\n\t");
		$this->_body->appendChild("\n");

		$this->_menu->appendChild($this->_tab[$this->nTab]);
		$this->_body->appendChild($this->body[$this->nTab]);

		return $this->body[$this->nTab++];
		
	}// end function
	
	
	public function getIMenu($index){
		if(isset($this->_tab[$index])){
			return $this->_tab[$index];
		}else{
			return false;
		}
		
		
	}
	public function getIBody($index){
		if(isset($this->body[$index])){
			return $this->body[$index];
		}else{
			return false;
		}
		
	}
	
	public function reqJSON(){
		
		$a = [];
		
		$a["sgTab"] = [
			"id"=>"tab_4_01",
			"class"=>"summer",
			"value"=>0,
			
				
			
			
			
		];
		
		
		$json = "{id='tab_01'}";
		
	}
	
	public function getRequest(){
		
		$info = new \stdClass;
		$info->classObject = "sgTab";
		
		
		$info->menuId = "{$this->name}_menu";
		$info->bodyId = "{$this->name}_body";
		$info->value = $this->value;
		$info->classObject = "sgTab";
		
		return $info;
		
	}
	
	
	public function getScript(){
		return "";
		$ref = $this->getRef();
		if(!$ref = $this->getRef()){
			//$ref = $this->name;	
		}
		
		
		
		$j = array(
			
			"main" => "#s",
			//"bodyId" => "{$this->name}_body",
			"value"	 => 1,
		);
		$json = json_encode($j, JSON_PRETTY_PRINT);
		
		$script = "var ttab = new Tab($json);";
		//$script = "\n\t$ref = _sgTab.create({menu:'".$this->_menu->id."', body:'".$this->_body->id."'});";		
		return $script;
		
	}
	
	public function funcShow($index){
		$ref = $this->getRef();
		return "$ref.show($index);";
	}
	
	public function render(){
		

		return $this->_main->render();
		
		
	}// end function
	
	
}// end class

?>