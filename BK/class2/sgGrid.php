<?php

class zGrid extends svPage{
	public $cols = 0;
	

	private $table =  false;

	private $_dx =  false;
	private $_dy =  false;
	
	private $typeEnum = 0;	
	private $typeSelect = 0;

	public function __construct($opt){

		svPage::__construct($opt);

		
		
		$this->header = $this->body->add("div");
		$this->main = $this->body->add("div");
		$this->main->{"data-section"} = "grid";
		
		$this->hiddenFields = $this->body->add("div");
		
		
		
		$this->_dx = 0;
		if($this->typeEnum){
			$this->_dx++;
		}// end if
		
		if($this->typeSelect > 0){
			$this->_dx++;
		}// end if

		$this->table = new sgTable($this->cols + $this->_dx);
		$this->table->border = 1;
		
		$this->table->id = $this->id."_grid_table";	
		
		$this->main->appendChild($this->table);		
		
		
	}// end function	
	
}// end class 


class listGrid extends svPage{
	public $cols = 0;
	
	
	public $panel = false;
	public $form = false;
	public $formName = "";
	
	public $type = "basic";
	
	public $page = 1;
	public $num_pages = 5;
	public $record_page = 4;
	
	public $q = "";
	
	
	public $table = false;
	public $header = false;
	public $main = false;
	
	private $n = 0;
	
	public $data = false;
	public $fields = false;
	
	private $_enum = false;
	private $_modeSelection = 1;
	public $numFields = 0;
	
	private $_delta_r = 0;
	
	public $name = "";
	
	private $_col = array();
	
	public $typeSelect = 0;
	public $typeEnum = 0;
	public $paginator = true;
	public $typeSearch = 1;

	public $fieldsPrefix = "";
	private $_dx = 0;
	private $hiddenFields = false;

	private $_ref = false;
	


			
	public function __construct($opt){

		svPage::__construct($opt);

		$this->{"data-page-type"} = "list_grid";
		$this->{"data-grid-type"} = "main";

		$this->class = "grid";
		
		$this->header = $this->body->add("div");
		$this->main = $this->body->add("div");
		$this->main->{"data-grid-type"} = "grid_table";
		$this->hiddenFields = $this->body->add("div");
		
	

		$this->table = new sgTable($this->cols);
		$this->table->border = 1;
		$this->table->{"data-grid-type"} = "table";
		$this->table->id = $this->id."_g_table";	
		
		$this->main->appendChild($this->table);		

		
		
		
		
		
		
		
		
	}// end function	
	public function setRef($name){
		$this->_ref = $name;	
	}// end function
	
	public function getRef(){
		return $this->_ref;	
	}// end function	
	public function addRow($data, $check = false, $checkTitle = false){
		$m = $this->n++;
		//$this->n++;
		$row = $this->table->insertRow();

		
		$select = new sgHTML("input");
		
		$select->type = "checkbox";
		
		
		$select->name = $this->name."_sel_opt";
		
		$row->cells[0]->appendChild($select);
		if($checkTitle !== false){
			$row->cells[0]->appendChild($checkTitle);
			
		}	
		
		if($check){
			$select->checked = "checked";	
			
		}
		
		$ref = $this->getRef();	
		$select->onclick = "$ref.editCheckList($m);";
			
						
		
		
		$c = 0;
		
		foreach($this->_col as $i => $col){	
			$value = "";
			if(isset($data[$col["name"]])){
				$value = $data[$col["name"]];
			}
			$_col = $this->_col[$i];
			$_col["name"] = $this->fieldsPrefix.$_col["name"]."_i$m";
			if(isset($_col["parent"]) and $_col["parent"] !=""){
				
				$_col["parent"] =  $this->fieldsPrefix.$_col["parent"]."_i$m";
			}
			
			$input = $this->addInput($_col);
			$input->input->value = $value;	
			
			if($col["hide"]){
				$this->hiddenFields->appendChild($input->input);
				continue;	
			}
			$row->cells[$c+$this->_dx]->appendChild($input->input);
			
			$c++;
		}// next
		
	}//e nd function

	public function getScript(){
		
		
		
		$script = "";
		$ref = $this->getRef();
		$tableId = $this->table->id;
		$script .= "\n\t$ref = new sgGrid({tableId:'$tableId', formName:'$this->formName', prefix:'$this->fieldsPrefix'});";
		$script .= "\n\t$ref.check_name ='{$this->name}_sel_opt';";
		
		$fields = array();
		foreach($this->_col as $col){
			$fields[] = $col["name"];
		}
		
		$json = json_encode($fields);


		//$json = json_encode(array_map(function($v){return is_string($v)? utf8_encode($v): $v;}, $fields), JSON_PRETTY_PRINT);
		
		$script .= "\n\t$ref.fields = $json;";

		
		
		$script.= svPage::getScript();
		$script .= "\n\t$ref.init();";
		return $script.$this->_script;
		
	}
		
}// end class

class xyGrid extends svPage{
	public $cols = 0;
	
	
	public $panel = false;
	public $form = false;
	public $formName = "";
	
	public $type = "basic";
	
	public $page = 1;
	public $num_pages = 5;
	public $record_page = 4;
	
	public $q = "";
	
	
	public $table = false;
	public $header = false;
	public $main = false;
	
	private $n = 0;
	
	public $data = false;
	public $fields = false;
	
	private $_enum = false;
	private $_modeSelection = 1;
	public $numFields = 0;
	
	private $_delta_r = 0;
	
	public $name = "";
	
	public $_col = array();
	
	public $typeSelect = 0;
	public $typeEnum = 0;
	public $paginator = true;
	public $typeSearch = 1;

	public $fieldsPrefix = "";
	private $_dx = 0;
	private $hiddenFields = false;

	private $_ref = false;

	public function __construct($opt){

		svPage::__construct($opt);

		$this->{"data-page-type"} = "grid";
		$this->{"data-grid-type"} = "main";

		$this->class = "grid";
		
		$this->header = $this->body->add("div");
		$this->main = $this->body->add("div");
		$this->main->{"data-grid-type"} = "grid_table";
		$this->hiddenFields = $this->body->add("div");
		
		$this->_dx = 0;
		if($this->typeEnum){
			$this->_dx++;
		}// end if
		
		if($this->typeSelect > 0){
			$this->_dx++;
		}// end if

		$this->table = new sgTable($this->cols + $this->_dx);
		$this->table->border = 1;
		$this->table->{"data-grid-type"} = "table";
		$this->table->id = $this->id."_g_table";	
		
		$this->main->appendChild($this->table);		

		if($this->paginator){
			$p = new sgPaginator($this->page, $this->num_pages, $this->record_page, 
				svAction::setPanel(array(
					"async"=>true,
					"panel"=>$this->panel,
					"params"=>"page:{=page};",
					)));
			
		}
		
		
		
		
		
		$this->appendChild($p);
		
		
	}// end function

	public function setRef($name){
		$this->_ref = $name;	
	}// end function
	
	public function getRef(){
		return $this->_ref;	
	}// end function

	public function input($input, $name){
		
		global $seq;
		return $seq->input($input, $name);
	}

	public function addInput($opt, $name = "", $title = "", $value = false, $data = false){
		
		$field = new stdClass;
		//$opt["type"] = "text";
		if(is_array($opt)){
			
			$type = (isset($opt["type"]))?$opt["type"]:"text";
			$name = (isset($opt["name"]))?$opt["name"]:"";
			
	
			$field->input = $this->input($type, $name);
			$field->input->class = $this->class_input;
			foreach($opt as $k => $v){
				$field->input->$k = $v;	
			}// next			
		
		}else{
			
			$type = $opt;
	
			$field->input = $this->input($type, $name);
			$field->input->title = $title;
			$field->input->value = $value;
			if($data !== false){
				$field->input->data = $data;
			}
			$field->input->class = $this->class_input;			
		}// end if	
		
		$field->name = $name;
		
		$field->type = $type;
		$field->input->setRef($this->getRef());
		//$field->input->setRef($this->getRef().".e.$name");
		$field->input->formName = $this->name;	
	
			
	
		return $field;
		
	}// end fucntion

	public function setType($type="basic"){
		
		
		
	}

	public function setClasses($class = ""){
		return;
		if($class!=""){
			$this->class = $class;	
			
		}
		$class = $this->class."_grid_";
		

		if($this->class_main == ""){
			$this->class_main = $class."main";
		}// end if
		if($this->class_caption == ""){
			$this->class_caption = $class."caption";
		}// end if
		if($this->class_body == ""){
			$this->class_body = $class."body";
		}// end if


		if($this->class_tab == ""){
			$this->class_tab = $class."tab";
		}// end if

		

		if($this->class_page == ""){
			$this->class_page = $class."page";
		}// end if
		if($this->class_page_caption == ""){
			$this->class_page_caption = $class."page_caption";
		}// end if
		if($this->class_page_body == ""){
			$this->class_page_body = $class."page_body";
		}// end if

		
		
		
		if($this->class_table == ""){
			$this->class_table = $class."table";
		}// end if
	
		if($this->class_row == ""){
			$this->class_row = $class."row";
		}// end if
		if($this->class_cell_title == ""){
			$this->class_cell_title = $class."cell_title";
		}// end if
		if($this->class_cell_input == ""){
			$this->class_cell_input = $class."cell_input";
		}// end if
		if($this->class_required == ""){
			$this->class_required = $class."required";
		}// end if
		if($this->class_input == ""){
			$this->class_input = $class."input";
		}// end if




		
		
	}
	
	public function setCaption($opt){
		
		svPage::setCaption($opt);
			
		//$this->main->caption->appendChild($caption);
		//$this->main->caption->class = &$this->class_caption;
	}	
	
	public function setEnum($value){
		$this->_enum =  $value;
	}
	
	public function setModeSelection($value){
		$this->_modeSelection =  $value;
	}

	public function setTable($col){
		$delta = 0;
		if($this->typeEnum){
			$delta++;
		}


		if($this->typeSelect > 0){

			$delta++;
		}
		$this->_delta_r = $delta;
		
		$this->table = new sgTable($col+$delta);
		$this->table->border = 1;	
		
		$this->main->appendChild($this->table);
		
	}
	
	public function setColumn($c){
		$this->_col[] = $c;
		
		if(isset($c["hide"])and  !$c["hide"]){
			$this->table->insertColumn();	
		}
			
		
	}

	public function setTitle2(){
		
		$row = $this->table->insertRow();

		if($this->typeEnum > 0){
			$row->cells[0]->appendChild("&nbsp;");
			
		}// end if
		
		
		if($this->typeSelect > 0 and $this->type != "check_list"){
			$select = new sgHTML("input");
			if($this->typeSelect == 2){
				$select->type = "checkbox";
			}else{
				$ref = $this->getRef();	
				$select->type = "radio";
				$select->checked = true;
				$select->onclick = "$ref.getRecord();";	
				
			}// end if
			$select->name = $this->name."_sel_opt";
			$row->cells[$this->_dx-1]->appendChild($select);			
		}// end if		
		
		$i=$this->_delta_r + 0;
		$c = 0;
		foreach($this->_col as $i => $col){
			
			if($col["hide"]){
				
				continue;	
				
			}
			
			$row->cells[$c+$this->_dx]->appendChild($col["title"]);
			$c++;
		}// next
		
	}

	public function setTitle(){
		
		$row = $this->table->insertRow();

		if($this->_enum > 0){
			$row->cells[0]->appendChild(".");
			
		}// end if
		
		if($this->_modeSelection > 0){
			$select = new sgHTML("input");
			if($this->_modeSelection == 2){
				$select->type = "checkbox";
			}else{
				$select->type = "radio";
				
			}// end if
			
			$select->name = $this->name."_sel_opt";
			
			$row->cells[$this->_delta_r-1]->appendChild($select);			
		}// end if		
		
		$i=$this->_delta_r + 0;
		foreach($this->fields as $field){
			$row->cells[$i]->appendChild($field->title);
			$i++;
		}// next
		
	}
	
	public function addRow($data, $check = false, $checkTitle = false){
		
		$m = $this->n++;
		//$this->n++;
		$row = $this->table->insertRow();
		if($this->typeEnum > 0){
			$row->cells[0]->appendChild($this->n);
			
		}// end if		

		if($this->typeSelect > 0){
			$select = new sgHTML("input");
			if($this->typeSelect == 2){
				$select->type = "checkbox";
			}else{
				$select->type = "radio";
				
			}// end if
			
			$select->name = $this->name."_sel_opt";
			
			$row->cells[$this->_dx-1]->appendChild($select);
			if($checkTitle !== false){
				$row->cells[$this->_dx-1]->appendChild($checkTitle);
				
			}	
			
			if($check){
				$select->checked = "checked";	
				
			}
			
			$ref = $this->getRef();	
			
			if($this->type == "check_list"){
				$select->onclick = "$ref.editCheckList($m);";	
				
			}else{
				$select->onclick = "$ref.getRecord($m);";	
				
			}
			
						
		}// end if	
		
		$c = 0;
		
		foreach($this->_col as $i => $col){	
		
			$value = "";
			if(isset($data[$col["name"]])){
				$value = $data[$col["name"]];
			}
			$_col = $this->_col[$i];
			$_col["name"] = $this->fieldsPrefix.$_col["name"]."_i$m";
			if(isset($_col["parent"]) and $_col["parent"] !=""){
				
				$_col["parent"] =  $this->fieldsPrefix.$_col["parent"]."_i$m";
			}
			
			switch($this->type){
				case "basic":
				case "simple":
					$_col["type"] = "hidden";
					$input = $this->addInput($_col);
					$input->input->value = $value;	
					$this->hiddenFields->appendChild($input->input);
					
					if($col["hide"]){
						
						continue 2;		
					}
					if($value !== ""){
						foreach($col["data"] as $k => $d){
	
							if($d[0] == $value){
								$value = $d[1];
								break;
							}
						}	
					}
						
					$row->cells[$c+$this->_dx]->appendChild($value);

					break;				
				case "multi":
				case "check_list":
					$input = $this->addInput($_col);
					$input->input->value = $value;	
					
					if($col["hide"]){
						
						$this->hiddenFields->appendChild($input->input);
						
						continue 2;
					}
					
					$row->cells[$c+$this->_dx]->appendChild($input->input);
					break;				
				case "dinamic":
					$input = $this->addInput($_col);
					$input->input->value = $value;	
					
					if($col["hide"]){
						$this->hiddenFields->appendChild($input->input);
						
						continue 2;	
					}
					$row->cells[$c+$this->_dx]->appendChild($input->input);
					break;				
			}// end switch
			$c++;
		}// next
		
	}// end function

	public function addEditRow(){
		$row = $this->table->insertRow();
		
		
		$c = 0;
		foreach($this->_col as $i => $col){	
			$input = $this->addInput($this->_col[$i]);
			if($col["hide"]){
				$this->hiddenFields->appendChild($input->input);
				continue;	
				
			}
			
			$row->cells[$c+$this->_dx]->appendChild($input->input);
			$c++;
		}
		
		
	}// end function
	
	public function addHiddenRow(){
		
		foreach($this->_col as $i => $_col){
			$col = $_col;
			$col["type"] = "hidden";
			$input = $this->addInput($col);
			$this->hiddenFields->appendChild($input->input);
		}// next

	}// end function	
	
	public function insertRow($data){
		
		
		$this->n++;
		if($this->typeEnum > 0){
			$row->cells[0]->appendChild($this->n);
			
		}// end if
		
		if($this->typeSelect > 0){
			$select = new sgHTML("input");
			if($this->typeSelect == 2){
				$select->type = "checkbox";
			}else{
				$select->type = "radio";
				
			}// end if
			$select->name = $this->name."_sel_opt";
			$row->cells[$this->_delta_r-1]->appendChild($select);			
		}// end if		
		
		$i=$this->_delta_r + 0;
		
		foreach($this->fields as $field){
			$row->cells[$i]->appendChild($data[$field->name]);
			$i++;
		}// next		
		
	}
	
	public function searchMenu(){
		$div = $this->header->add("div");
		$div->{"data-grid-type"} = "search";
		
		$input = $div->add("input");
		$input->type = "text";
		$input->name = "q";
		//$input->placeholder = "search...";
		$input->value = $this->q;
		
		$button = $div->add("input");
		$button->type = "button";
		//$button->value = "Search";
		$button->onclick = svAction::send(
			array(
				"async"=>true,
				"panel"=>$this->panel,
				"params"=>"set_panel:'panel:$this->panel;';"
			));
		$input->onkeypress = "if(!event){event = window.event;};if(((document.all)? event.keyCode: event.which) == 13){ $button->onclick; return false;}";

		/*
		$check = $div->add("input");
		$check->type="checkbox";
		$check->name = "q_search_exact";
		$check->value = "1";
		$check->checked = ($this->q_search_exact == "1")?"checked":false;
		
		$div->appendChild("Exact");

*/

		
		
		
	}// end function
	
	public function render(){
		
		switch($this->type){
		case "basic":
			$this->addHiddenRow();	
			break;			
		case "simple":
			$this->addEditRow();	
			break;			
		case "multi":	
			$this->addHiddenRow();	
			break;			
		case "dinamic":	
			break;			
			
		}
		
		
		
		$this->main->style = "overflow:auto;";
		//$this->style = "border:2px purple solid;margin:2px;";

		//$this->body->style = "border:2px blue solid;margin:2px;";
		//$this->header->style = "border:2px green solid;margin:2px;";

		if($this->typeSearch == 1){
			$this->searchMenu();
		}
		
		

		return svPage::render();
		
	}

	public function getScript(){
		
		
		
		$script = "";
		$ref = $this->getRef();
		$tableId = $this->table->id;
		$script .= "\n\t$ref = new sgGrid({type:'$this->type', tableId:'$tableId', formName:'$this->formName', prefix:'$this->fieldsPrefix'});";
		$script .= "\n\t$ref.check_name ='{$this->name}_sel_opt';";
		
		$fields = array();
		foreach($this->_col as $col){
			$fields[] = $col["name"];
		}
		
		$json = json_encode($fields);


		//$json = json_encode(array_map(function($v){return is_string($v)? utf8_encode($v): $v;}, $fields), JSON_PRETTY_PRINT);
		
		$script .= "\n\t$ref.fields = $json;";

		
		
		$script.= svPage::getScript();
		$script .= "\n\t$ref.init();";
		return $script.$this->_script;
		
	}
	
}

class sgGrid extends svPage{
	public $panel = false;
	public $form = false;
	
	
	public $type = "basic";
	
	public $page = 1;
	public $num_pages = 5;
	public $record_page = 4;
	
	public $q = "";
	
	
	public $table = false;
	public $header = false;
	public $main = false;
	
	private $n = 0;
	
	public $data = false;
	public $fields = false;
	
	private $_enum = false;
	private $_modeSelection = 1;
	public $numFields = 0;
	
	private $_delta_r = 0;
	
	public $name = "";
	
	private $_col = array();
	
	public $typeSelect = 0;
	public $typeEnum = 0;
	public $paginator = true;
	
	private $_dx = 0;
	private $hiddenFields = false;

	public function __construct($opt){

		svPage::__construct($opt);

		//$this->page = $this;
		$this->body->class = $this->class_main;
		//$this->hiddenFields = new sgHTML("");
		
		//$this->name = $name;
		//$this->main = new page;
		//$this->body = $this->main->body;
		
		
		$this->header = $this->body->add("div");
		$this->main = $this->body->add("div");
		$this->main->{"data-section"} = "grid";
		$this->hiddenFields = $this->body->add("div");
		
		
		
		$this->_dx = 0;
		if($this->typeEnum){
			$this->_dx++;
		}// end if
		
		if($this->typeSelect > 0){
			$this->_dx++;
		}// end if

		$this->table = new sgTable($this->_dx);
		$this->table->border = 1;
		
		$this->table->id = $this->id."_grid_table";	
		
		$this->main->appendChild($this->table);		
		
		
	}

	public function setRef($name){
		$this->_ref = $name;	
	}// end function
	public function getRef(){
		return $this->_ref;	
	}// end function

	public function setType($type="basic"){
		
		
		
	}


	public function setClasses($class = ""){
		return;
		if($class!=""){
			$this->class = $class;	
			
		}
		$class = $this->class."_grid_";
		

		if($this->class_main == ""){
			$this->class_main = $class."main";
		}// end if
		if($this->class_caption == ""){
			$this->class_caption = $class."caption";
		}// end if
		if($this->class_body == ""){
			$this->class_body = $class."body";
		}// end if


		if($this->class_tab == ""){
			$this->class_tab = $class."tab";
		}// end if

		

		if($this->class_page == ""){
			$this->class_page = $class."page";
		}// end if
		if($this->class_page_caption == ""){
			$this->class_page_caption = $class."page_caption";
		}// end if
		if($this->class_page_body == ""){
			$this->class_page_body = $class."page_body";
		}// end if

		
		
		
		if($this->class_table == ""){
			$this->class_table = $class."table";
		}// end if
	
		if($this->class_row == ""){
			$this->class_row = $class."row";
		}// end if
		if($this->class_cell_title == ""){
			$this->class_cell_title = $class."cell_title";
		}// end if
		if($this->class_cell_input == ""){
			$this->class_cell_input = $class."cell_input";
		}// end if
		if($this->class_required == ""){
			$this->class_required = $class."required";
		}// end if
		if($this->class_input == ""){
			$this->class_input = $class."input";
		}// end if




		
		
	}
	
	public function setCaptionS($caption){
			
		//$this->main->caption->appendChild($caption);
		//$this->main->caption->class = &$this->class_caption;
	}	
	
	public function setEnum($value){
		$this->_enum =  $value;
	}
	
	public function setModeSelection($value){
		$this->_modeSelection =  $value;
	}


	
	public function setTable($col){
		$delta = 0;
		if($this->typeEnum){
			$delta++;
		}


		if($this->typeSelect > 0){

			$delta++;
		}
		$this->_delta_r = $delta;
		
		$this->table = new sgTable($col+$delta);
		$this->table->border = 1;	
		
		$this->main->appendChild($this->table);
		
	}
	
	public function setColumn($c){
		$this->_col[] = $c;
		if(isset($c["hide"])and  !$c["hide"]){
			$this->table->insertColumn();	
		}
			
		
	}

	public function setTitle2(){
		
		$row = $this->table->insertRow();

		if($this->typeEnum > 0){
			$row->cells[0]->appendChild("&nbsp;");
			
		}// end if
		
		if($this->typeSelect > 0){
			$select = new sgHTML("input");
			if($this->typeSelect == 2){
				$select->type = "checkbox";
			}else{
				$select->type = "radio";
				$select->checked = true;
				
			}// end if
			$select->name = $this->name."_sel_opt";
			$row->cells[$this->_dx-1]->appendChild($select);			
		}// end if		
		
		$i=$this->_delta_r + 0;
		$c = 0;
		foreach($this->_col as $i => $col){
			
			if($col["hide"]){
				
				continue;	
				
			}
			
			$row->cells[$c+$this->_dx]->appendChild($col["title"]);
			$c++;
		}// next
		
	}

	public function setTitle(){
		
		$row = $this->table->insertRow();

		if($this->_enum > 0){
			$row->cells[0]->appendChild(".");
			
		}// end if
		
		if($this->_modeSelection > 0){
			$select = new sgHTML("input");
			if($this->_modeSelection == 2){
				$select->type = "checkbox";
			}else{
				$select->type = "radio";
				
			}// end if
			
			$select->name = $this->name."_sel_opt";
			
			$row->cells[$this->_delta_r-1]->appendChild($select);			
		}// end if		
		
		$i=$this->_delta_r + 0;
		foreach($this->fields as $field){
			$row->cells[$i]->appendChild($field->title);
			$i++;
		}// next
		
	}
	
	public function addRow($data){
		$this->n++;
		$row = $this->table->insertRow();
		if($this->typeEnum > 0){
			$row->cells[0]->appendChild($this->n);
			
		}// end if		

		if($this->typeSelect > 0){
			$select = new sgHTML("input");
			if($this->typeSelect == 2){
				$select->type = "checkbox";
			}else{
				$select->type = "radio";
				
			}// end if
			
			$select->name = $this->name."_sel_opt";
			
			$row->cells[$this->_dx-1]->appendChild($select);	
			
			
			
			$ref = $this->getRef();	
			
			$select->onclick = "$ref.getRecord($this->n);";				
		}// end if	
		
		
		
		$c = 0;
		foreach($this->_col as $i => $col){	
			$value = "";
			if(isset($data[$col["name"]])){
				$value = $data[$col["name"]];
			}
			$_col = $this->_col[$i];
			$_col["name"] = $_col["name"]."_$this->n";
			if(isset($_col["parent"]) and $_col["parent"] !=""){
				
				$_col["parent"] = $_col["parent"]."_$this->n";
			}
			
			switch($this->type){
				case "basic":
				case "simple":
					$_col["type"] = "hidden";
					$input = $this->form->addInput($_col);
					$input->input->value = $value;	
					$this->hiddenFields->appendChild($input->input);
					
					if($col["hide"]){
						
						continue;	
					}					
					$row->cells[$i+$this->_dx]->appendChild($value);

					break;				
				case "multi":
					$input = $this->form->addInput($_col);
					$input->input->value = $value;	
					
					if($col["hide"]){
						$this->hiddenFields->appendChild($input->input);
						continue;	
					}
					$row->cells[$c+$this->_dx]->appendChild($input->input);
					break;				
				case "dinamic":
					$input = $this->form->addInput($_col);
					$input->input->value = $value;	
					
					if($col["hide"]){
						$this->hiddenFields->appendChild($input->input);
						continue;	
					}
					$row->cells[$c+$this->_dx]->appendChild($input->input);
					break;				
			}// end switch
			$c++;
				
				
			
				
			
			
		}
		
		
	}

	public function addEditRow(){
		$row = $this->table->insertRow();
		
		
		$c = 0;
		foreach($this->_col as $i => $col){	
			$input = $this->form->addInput($this->_col[$i]);
			if($col["hide"]){
				$this->hiddenFields->appendChild($input->input);
				continue;	
				
			}
			
			$row->cells[$c+$this->_dx]->appendChild($input->input);
			$c++;
		}
		
		
	}// end function
	
	public function addHiddenRow(){
		
		foreach($this->_col as $i => $_col){
			$col = $_col;
			$col["type"] = "hidden";
			$input = $this->form->addInput($col);
			$this->hiddenFields->appendChild($input->input);
		}// next

	}// end function	

	
	public function insertRow($data){
		
		
		$this->n++;
		if($this->typeEnum > 0){
			$row->cells[0]->appendChild($this->n);
			
		}// end if
		
		if($this->typeSelect > 0){
			$select = new sgHTML("input");
			if($this->typeSelect == 2){
				$select->type = "checkbox";
			}else{
				$select->type = "radio";
				
			}// end if
			$select->name = $this->name."_sel_opt";
			$row->cells[$this->_delta_r-1]->appendChild($select);			
		}// end if		
		
		$i=$this->_delta_r + 0;
		
		foreach($this->fields as $field){
			$row->cells[$i]->appendChild($data[$field->name]);
			$i++;
		}// next		
		
	}
	
	public function searchMenu(){
		$div = $this->header->add("div");
		$div->{"data-section"} = "search";
		
		$input = $div->add("input");
		$input->type = "text";
		$input->name = "q";
		$input->placeholder = "search...";
		$input->value = $this->q;
		
		$button = $div->add("input");
		$button->type = "button";
		$button->value = "Search";
		$button->onclick = svAction::send(
			array(
				"async"=>true,
				"panel"=>$this->panel,
				"params"=>"set_panel:'panel:$this->panel;';"
			));
		$input->onkeypress = "if(!event){event = window.event;};if(((document.all)? event.keyCode: event.which) == 13){ $button->onclick; return false;}";

		
		$check = $div->add("input");
		$check->type="checkbox";
		$check->name = "q_search_exact";
		$check->value = "1";
		$check->checked = ($this->q_search_exact=="1")?"checked":"";
		
		$div->appendChild("Exact");


		
		
		
	}// end function
	
	public function render(){
		
		switch($this->type){
		case "basic":
			$this->addHiddenRow();	
			break;			
		case "simple":
			$this->addEditRow();	
			break;			
		case "multi":	
			$this->addHiddenRow();	
			break;			
		case "dinamic":	
			break;			
			
		}
		
		
		
		$this->main->style = "overflow:auto;";
		//$this->style = "border:2px purple solid;margin:2px;";

		//$this->body->style = "border:2px blue solid;margin:2px;";
		//$this->header->style = "border:2px green solid;margin:2px;";

		
		$this->searchMenu();
		
		if($this->paginator){
			$p = new sgPaginator($this->page, $this->num_pages, $this->record_page, 
				svAction::setPanel(array(
					"async"=>true,
					"panel"=>$this->panel,
					"params"=>"page:{=page};",
					)));
			
		}
		
		
		
		
		
		$this->body->appendChild($p);/**/
		return svPage::render();
		
	}
	
	
	public function getScript(){
		
		
		
		$script = "";
		$ref = $this->getRef();
		$refForm = $this->form->getRef();
		$tableId = $this->table->id;
		$script .= "\n\t$ref = new sgGrid('$tableId', $refForm);";
		
		$script .= "\n\t$ref.check_name ='{$this->name}_sel_opt';";
		$script .= "\n\t$ref.panel = $this->panel;";
		
		
		
		$fields = array();
		foreach($this->_col as $col){
			$fields[] = $col["name"];
			
		}
		
		$json = json_encode($fields);
		
		$script .= "\n\t$ref.fields = $json;";

		//$script .= "\n\t$ref.init();";
		
		$script.= svPage::getScript();
		return $script.$this->_script;
		
	}
		
	
}


class mmGrid extends svPage{
	
	private $_dx = 0;
	private $_dy = 0;
	
	public $cols = 0;
	
	private $table = false;
	private $_fields = array();

	public function __construct($opt = array()){
		svPage::__construct($opt);
		$table = $this->table = new sgTable($this->cols + $this->_dx);




		
		$this->table->border = 1;
		
		$this->table->id = $this->id."_grid_table";	
		
		$this->appendChild($this->table);	
		
	}// end function
	
	public function insertRow(){

		return $this->table->insertRow();
		
	}// end function

	public function addRow($data = array()){
		
		$row = $this->table->insertRow();
		
		
		$c = 0;
		foreach($this->_col as $i => $col){	
			$input = $this->form->addInput($this->_col[$i]);
			if($col["hide"]){
				$this->hiddenFields->appendChild($input->input);
				continue;	
				
			}
			
			$row->cells[$c+$this->_dx]->appendChild($input->input);
			$c++;
		}
		
	}// end function

	public function addEditRow(){
		
		
	}// end function
	
	
	
	
}

?>