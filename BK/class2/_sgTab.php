<?php



class _sgTab extends sgHTML{
	public $__type = "_sgTab";


	public $tagName = "div";

	public $_main = false;
	public $_menu = false;
	public $_body = false;

	public $main = false;
	public $menu = false;
	public $body = false;


	public $nTab = 0;
	public $className = "tab";
	public $classTab = 'tab';
	public $classPage = 'page';
	public $classTabOpen = 'tab_open';
	public $classTabClose = 'tab_close';
	public $classPageOpen = 'page_open';
	public $classPageClose = 'page_close';		
	
	public $class_main = "_tab_main";
	public $class_menu = "_tab_menu";
	public $class_body = "_tab_body";
	public $class_open = "_tab_open";
	public $class_close = "_tab_close";
	public $class_page_open = "_tab_page_open";
	public $class_page_close = "_tab_page_close";


	private $_last = false;
	private $_lastIndex = false;
	private $_ref = "tab";
	private $_page = array();
	private $_obj = array();
	
	private $_script = "";
	private $_css = "";
	
	private $_def = array();
	
	public function __construct($opt) {

		if(is_array($opt)){
			$this->_def = $opt;	
			foreach($opt as $k => $v){
				$this->$k = $v;	
			}// next
					
		}else{
			$this->id = $opt;
			$this->_def["id"] = $opt; 
		}// end if
	
		
	
		$this->{"data-tab-type"} = "main";
	
		if(!$this->_menu){
			$this->_menu = new sgHTML("div");
		}

		if(!$this->_body){
			$this->_body = new sgHTML("div");
		}
			
		$this->_menu->id = $this->id."_menu";
		$this->_body->id = $this->id."_body";
		
		sgHTML::appendChild("\n");
		sgHTML::appendChild($this->_menu);
		sgHTML::appendChild("\n");
		sgHTML::appendChild($this->_body);
		sgHTML::appendChild("\n");

		if($this->class != ""){
			$this->setClass($this->class);
		
		}else{
			//$this->_main->class = $this->class_main;
			//$this->_menu->class = $this->class_menu;
			//$this->_body->class = $this->class_body;
		}// end if
		
		//$this->class = "ff";
	}// end function
	
	public function setClass($class = ""){
		return;
		
		
	}	
	
	public function setRef($name){
		
		$this->_ref = $name;	
	
	}// end function
	
	public function getRef(){
		
		return $this->_ref;	
	
	}// end function

	public function addPage($title = false){
		
		
		$page = new stdClass;
		
		$page->menu = new sgHTML("a");
		$page->menu->appendChild($title);
		$page->menu->href = "javascript:void(0);";
		


		$page->body = new sgHTML("div");
		$page->body->id = $this->id."_body_".$this->nTab;

		$this->_menu->appendChild("\n\t");
		$this->_body->appendChild("\n");

		$this->_menu->appendChild($page->menu);
		$this->_body->appendChild($page->body);

		$this->_page[$this->nTab] = $page;
		$this->_lastIndex = $this->nTab;
		$this->nTab++;



		return $this->_last = $page->body;
		
	}// end function
	
	public function appendChildX($ele){
		
		return $this->_obj[] = $this->_last->appendChild($ele);
		
	}// end function

	public function addX($ele){

		return $this->_obj[] = $this->_last->add($ele);

	}// end function

	public function getPage($index){

		return $this->_last;		
	}// end function
	
	public function setPage($index){

		return $this->_last = $this->_page[$index]->body;		

	}// end function
	
	public function funcShow($index){

		$ref = $this->getRef();
		return "$ref.show($index);";

	}// end function
                      
	public function setCss($css){
		
		$this->_css .= $css;

	}// end function

	public function getCss(){

		$css = "";
		
		foreach($this->_obj as $obj){
			if(method_exists($obj, "getCss")){
				$css .= $obj->getCss();
			}
		}// next

		return $css.$this->_css;
	
	}// end function	

	public function setScript($script){
		
		$this->_script .= $script;

	}// end function	

	
	public function getScript(){

		$script = "";

		
		
		
		

		$ref = $this->getRef();
		
		$script .= "\n$ref = new _sgTab({menu:'".$this->_menu->id."', body:'".$this->_body->id."'});";		
	$script .= sgHTML::getScript();
		return $script;
		
	}// end function

	public function getJson(){

		$this->_def["menu"] = $this->_menu->id;
		$this->_def["body"] = $this->_body->id;
		
		
		return json_encode($this->_def);

		$script = "";

		
		
		
		$script .= sgHTML::getScript();

		$ref = $this->getRef();
		
		$script .= "\n$ref = _sgTab.create({menu:'".$this->_menu->id."', body:'".$this->_body->id."'});";		
	
		return $script;
		
	}// end function

	
}// end class

?>