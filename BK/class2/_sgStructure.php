<?php


class _sgStructure extends sgHTML{
	public $__type = "_sgStructure";
	
	public $tagName = "div";
	private $template = "";
	private $config = array();
	private $_init = array();
	private $e = array();
	
	private $_def = false;
	private $_last = false;
	
	private $_tab = false;
	
	private $_p = array();
	private $__p = array();
	private $_template = false;
	
	
	private $ref = "sevian";
	
	public function __construct($opt){
		
		$this->__init = $opt;
		
		
		$this->id = "xxx";
		$this->style = "border:2px solid red;padding:4px;margin:0px;";
		//$this->tagName = "textarea";
		//$this->tagName = "div";
		foreach($opt as $k => $v){
			$this->$k = $v; 
		}
		$this->_def = $opt;
		$this->_last = $this;
		$this->_config($this->config);
		$this->_init = $opt;
		
		

	}
	
	
	public function getRef(){
		
		return $this->ref;	
		
	}
	
	public function _config($opt){
		foreach($opt as $k => $v){
			
			//$this->{$v["param"]}($v["value"]);
			
			
			if(method_exists($this, $v["param"])){

				$this->{$v["param"]}($v["value"]);
			}
			
		}
	}
	
	public function addPanel($opt){
		
		$panel = $opt['panel'];
		
		$div = new sgHTML("div");
		$div->id = "sg_panel_".$panel;
		$div->innerHTML = "<b>sg_panel_</b>".$panel;
		$this->_last->appendChild($div);
		
		$this->_p[$panel] = $div;
	}
	
	public function setTemplate($html){
		
		$this->_template = $html;
		
		//$p = $this->getPanels($html);
		
		
	}
	
	public function getPanels($html){
		
		$exp = "|--([0-9]+)--|";
		$this->_panels = array();
		if(preg_match_all($exp, $html, $c)){
			foreach($c[1] as $a => $b){
				$this->_panels[trim($b)] = trim($b);
			}// next
		}// next
		return $this->_panels;

	}// end function	
	
	public function load($obj){
		
		
		//$obj->setRef($this->getRef());

		
		
		$panel = $obj->panel;
		
		$this->__p[] = $obj;
		$this->_p[$panel]->appendChild($obj);
		
		
	}
	
	public function addPage($opt){
		
		$p = new _sgPage($opt);
		$this->_last->appendChild($p);
		$this->e[] = $p;
		$this->_last = $p;
	}	


	public function addTab($opt){
		
		$p = $this->_tab = new _sgTab($opt);
		$this->_last->appendChild($p);
		$this->e[] = $p;
		
	}
	
	public function addTabPage($opt){
		
		
		
		$p = $this->_tab->addPage($opt);
		//$this->e[] = $this->_last = $e;
		$this->_last = $p;
		
	}	
	
	public function _render(){
		
		
		
	}

	public function getScript(){
		
		$script = "";
		
		
				
		$ref = $this->getRef();
		$script .= "\n$ref = _sevian.init('$ref', ".$this->getJson().");";
		
		
		foreach($this->__p as $obj){
			
			//$script .= "\n$ref.load();";
			
		}
		
		$script.= sgHTML::getScript();
		return $script;
		
		
	}

	public function __getInit(){


		$this->__init["__type"] = $this->__type;
		$this->__init["__childs"] = array(); 
		
		foreach($this->e as $obj){
			if(method_exists($obj, "__getInit")){
				$this->__init["__childs"][] = $obj->__getInit();
			}
		}// next


		foreach($this->__p as $obj){
			
			
			
			
			if(method_exists($obj, "__getInit")){
				
				
				//$this->__init["__childs"][] = json_encode($obj, JSON_PRETTY_PRINT);
				
			}
		}// next


		
		return $this->__init;
		
	}// end function		
	
	public function getJson(){
		
		
		
		return json_encode($this->__getInit(), JSON_PRETTY_PRINT);	
		
	}
	
}



?>