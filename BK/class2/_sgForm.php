<?php


class _sgForm extends sgHTML{
	
	public $__type = "_sgPage";
	
	
	
	
	private $_last = false;
	
	private $_pages = array();
	private $_tabs = array();
	private $_tables = array();
	
	private $_page = false;
	private $_tab = false;
	private $_table = false;
	
	private $_index = 0;
	
	private $_ref = false;
	public function __construct($opt){
		//sgHTML::__construct($opt);

		if(is_array($opt)){
			$this->__init = $opt;
			foreach($opt as $k => $v){
				$this->$k = $v;	
			}// next			
		}else{
			$this->__init["id"] = $opt;
		}// end if
		
		$this->_last = $this;
		
	}// end fucntion
	
	public function setRef($name){
		$this->_ref = $name;	
	}// end function
	
	public function getRef(){
		return $this->_ref;
	}// end function		
	
	public function addPage($opt){


		$opt['id'] = $this->id."_p".$this->_index;
		
		$opt['_ref'] = $this->getRef().".pages[$this->_index]";


		$this->_page = $this->_pages[$this->_index++] = new _sgPage($opt);
		
		$this->_last->appendChild($this->_page);
		
		$this->_last = 	$this->_page;
		
		
		
		return $this->_last;
		
		
	}// end fucntion
	public function addTab($opt){
		
		
		$opt['id'] = $this->id."_t".$this->_index;
		
		$opt['_ref'] = $this->getRef().".tabs[$this->_index]";
		$this->_tab = $this->_tabs[$this->_index++] = new _sgTab($opt);
		
		

		$this->_tab->class = "tab";//$this->class_tab;
		
		$this->_last->appendChild($this->_tab);		
		
		return $this->_tab;		
		
	}// end fucntion
	
	public function addTabPage($opt){
		
		return $this->_last = $this->_tab->addPage($opt);

	}// end fucntion	
	
	public function addTable(){
		
		$this->_table = $this->_tables[$this->_index++] = new sgTable(2);
		$this->_table->typeRender("div");
		$this->_table->{"data-form-type"} = "table";
		
		$this->_last->appendChild($this->_table);	

		return $this->_table;		
		
	}// end fucntion


	public function fieldRow(){
		$row = new sgHTML("div");
		$cell1 = new sgHTML("div");
		$cell2 = new sgHTML("div");
		$row->appendChild($cell1);
		$row->appendChild($cell2);
		
		$_row = new stdClass;
		$_row->row = $row;
		$_row->cells[0] = $cell1;
		$_row->cells[1] = $cell2;
		
		return $_row;
	}// end fucntion
	
	public function addField($opt){
		
		
		$r = $this->fieldRow();
		$opt['_ref'] = $this->getRef().".e.$opt[name]";
		$input = $this->input($opt);
		
		
		
		//$input->setRef($this->getRef().".e");
		
		$r->cells[0]->innerHTML = $opt["title"];
		$r->cells[1]->appendChild($input);
		$this->_last->appendChild($r->row);
		$this->__init = $opt;
		
	}// end fucntion
	
	public function getScript(){
		$ref = $this->getRef();
		
		$script = "";
		
		$script .= "\n$ref = new _sgForm();";
		
		$script .= sgHTML::getScript();
		
		return $script;
		
		
	}
	
	public function input($opt){
		
		global $seq;
		return $seq->sgInput($opt);
	}// end fucntion
	
}// end class




?>