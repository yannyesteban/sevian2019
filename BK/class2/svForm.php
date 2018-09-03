<?php


//include ("connection.php");

//include ("sg_panel.php");
//include ("functions.php");

//include ("sg_html.php");
//include ("sg_input.php");
//include ("cfg_tab.php");


//include ("sgInput.php");
//include ("svMenu.php");

//include ("sgFormSave.php");
//include ("sgGrid.php");







class sgTabNO{
	
	
	public $name ="";

	public $_main = false;
	public $_menu = false;
	public $_body = false;

	public $nTab = 0;
	public $class = "";
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
	
	
	
	public $css = "";
	public $tab = array();
	public $body = array();
	private $_ref = "tab";
	public function __construct($name, $class = false) {
		

		$this->name = $name;
		$this->_main = new sgHTML("div");
		$this->_menu = new sgHTML("div");
		$this->_body = new sgHTML("div");
		
		$this->_menu->id = $this->name."_menu";
		$this->_body->id = $this->name."_body";
		$this->_main->appendChild("\n");
		$this->_main->appendChild($this->_menu);
		$this->_main->appendChild("\n");
		$this->_main->appendChild($this->_body);
		$this->_main->appendChild("\n");
		if($class !== false){
			$this->setClass($class);
		}else{
			$this->_main->class = $this->class_main;
			$this->_menu->class = $this->class_menu;
			$this->_body->class = $this->class_body;
		}// end if
		
	
	}// end function
	
	public function setClass($class = ""){
		
		if($class!=""){
			$this->class = $class;	
		}

		$class = $this->class."_tab_";

		if($this->class_main == ""){
			$this->class_main = $class."main";
		}// end if
		if($this->class_menu == ""){
			$this->class_menu = $class."menu";
		}// end if
		if($this->class_body == ""){
			$this->class_body = $class."body";
		}// end if
		if($this->class_open == ""){
			$this->class_open = $class."open";
		}// end if
		if($this->class_close == ""){
			$this->class_close = $class."close";
		}// end if
		if($this->class_page_open == ""){
			$this->class_page_open = $class."page_open";
		}// end if
		if($this->class_page_close == ""){
			$this->class_page_close = $class."page_close";
		}// end if

		$this->_main->class = $this->class_main;
		$this->_menu->class = $this->class_menu;
		$this->_body->class = $this->class_body;
		
	}	
	
	public function setRef($name){
		$this->_ref = $name;	
	}// end function
	public function getRef(){
		return $this->_ref;	
	}// end function
	public function add($title = "", $body = false){
		$this->nTab++;
		
		$this->tab[$this->nTab] = new sgHTML("a");
		$this->tab[$this->nTab]->appendChild($title);
		$this->tab[$this->nTab]->href = "javascript:void(0);";
		
		$this->body[$this->nTab] = new sgHTML("div");
		$this->body[$this->nTab]->id = $this->name."_body_".$this->nTab;
		if($body){
			$this->body[$this->nTab]->appendChild($body);
		}// end if

		$this->_menu->appendChild("\n\t");
		$this->_body->appendChild("\n");

		$this->_menu->appendChild($this->tab[$this->nTab]);
		$this->_body->appendChild($this->body[$this->nTab]);

		return $this->body[$this->nTab];
		
	}// end function
	
	public function getScript(){
		
		$ref = $this->getRef();
		if(!$ref = $this->getRef()){
			//$ref = $this->name;	
		}
		
		$script =  "\n\t$ref = new sgTab();";
		$script .= "\n\t$ref.classTab = '$this->class_menu';";
		$script .= "\n\t$ref.classPage = '$this->class_body';";
		$script .= "\n\t$ref.classTabOpen = '$this->class_open';";
		$script .= "\n\t$ref.classTabClose = '$this->class_close';";
		$script .= "\n\t$ref.classPageOpen = '$this->class_page_open';";
		$script .= "\n\t$ref.classPageClose = '$this->class_page_close';";
		$script .= "\n\t$ref.init('".$this->_menu->id."','".$this->_body->id."');";		
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


class sgLayout extends sgHTML{
	
	
	private $_last = false;
	private $_last_save = false;
	private $_obj = array();


	private $_css = "";
	private $_script = "";



	public function __construct	($tag = ""){

		$this->tagName = $tag;
		
		$this->_last = $this;
		$this->_last_save = $this;
		
		
	}// end function
	
	
	public function add($opt = "", $newContext = false){

		if(is_array($opt)){
			$ele = new sgHTML(($opt["tagName"])?$opt["tagName"]:"");
			foreach($opt as $k => $v){
				$ele->$k = $v;	
			}// next
		}elseif(is_object($opt)){
			$ele = $opt;
		}else{
			$ele = new sgHTML($opt);
		}// end if
		
		$this->_last->appendChild($ele);
		
		if($newContext){
			$this->_last = $ele;
		}
		
		$this->_obj[] = $ele;
		return $ele;	
	}// end function
	
	public function set($ele){
		$this->_last = $ele;
		
	}// end function


	public function savePage(){
		$this->_last_save = $this->_last;
	}// end function
	
	public function restorePage(){

		$this->_last = $this->_last_save;
		
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
	

	public function getScriptp(){

		$script = "";

		foreach($this->_obj as $obj){
			if(method_exists($obj, "getScript")){
				$script .= $obj->getScript();
			}
		}// next
		
		return $script.$this->_script;
	}// end function
	
}// end class

class svPage extends sgHTML{
	public $__type = "svPage";
	
	public $tagName = "section";
	public $caption = false;
	public $body = false;
	
	private $_caption = false;
	//public $__init = array();
	
	public function __construct	($opt = false){
		
		
		
		if(is_array($opt)){
			$this->__init = $opt;
			foreach($opt as $k => $v){
				$this->$k = $v;	
			}// next			
		}else{
			$this->__init["id"] = $opt;
		}// end if			
		
		if($this->caption){
			$this->setCaption($this->caption);
		}

		$this->body = new sgHTML("div");
		$this->body->{"data-page-type"} = "body";
		sgHTML::appendChild($this->body);
		
	}// end fucntion
	
	public function setCaption($opt){
		
		if(!$this->_caption){
			$this->_caption = new sgHTML("header");
			sgHTML::insertFirst($this->_caption);
		}

		if(is_array($opt)){
			foreach($opt as $k => $v){
				$this->_caption->$k = $v;	
			}// next			
		}else{
			$caption = $opt;
			$this->_caption->innerHTML = $caption;
		}// end if	
		
		
		$this->_caption->{"data-page-type"} = "caption";
		return $this->_caption;
		
	}// end fucntion
	
	public function getCaption(){
		return $this->_caption;
	}// end fucntion
	
	public function appendChild($e){
		return $this->body->appendChild($e);
	}// end fucntion

}// end class

class svTab extends sgHTML{

	public $tagName = "div";

	public $_main = false;
	public $_menu = false;
	public $_body = false;

	public $nTab = 0;
	public $className = "";
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
	
	public function __construct($opt) {

		if(is_array($opt)){
			foreach($opt as $k => $v){
				$this->$k = $v;	
			}// next			
		}else{
			$this->id = $opt;
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
		if($class != ""){
			$this->class = $class;	
		}

		$class = $this->class."_tab_";

		if($this->class_main == ""){
			$this->class_main = $class."main";
		}// end if
		if($this->class_menu == ""){
			$this->class_menu = $class."menu";
		}// end if
		if($this->class_body == ""){
			$this->class_body = $class."body";
		}// end if
		if($this->class_open == ""){
			$this->class_open = $class."open";
		}// end if
		if($this->class_close == ""){
			$this->class_close = $class."close";
		}// end if
		if($this->class_page_open == ""){
			$this->class_page_open = $class."page_open";
		}// end if
		if($this->class_page_close == ""){
			$this->class_page_close = $class."page_close";
		}// end if

		//$this->_main->class = $this->class_main;
		$this->_menu->class = $this->class_menu;
		$this->_body->class = $this->class_body;
		
	}	
	
	public function setRef($name){
		
		$this->_ref = $name;	
	
	}// end function
	
	public function getRef(){
		
		return $this->_ref;	
	
	}// end function

	public function addPage($title = false, $body = false){
		
		
		$page = new stdClass;
		
		$page->menu = new sgHTML("a");
		$page->menu->appendChild($title);
		$page->menu->href = "javascript:void(0);";
		


		$page->body = new sgHTML("div");
		$page->body->id = $this->id."_body_".$this->nTab;

		if($body !== false){
			
			$this->_obj[] = $page->body->appendChild($body);
			
		}// end if

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

	public function renderX(){
		
		sgHTML::render();
	}
	public function getScript(){

		$script = "";

		foreach($this->_obj as $obj){
			if(method_exists($obj, "getScript")){
				//$script .= $obj->getScript();
			}
		}// next
		
		
		$script .= sgHTML::getScript();

		$ref = $this->getRef();
		
		$script .= "\n\t$ref = _sgTab.create({menu:'".$this->_menu->id."', body:'".$this->_body->id."'});";		
	
		return $script;
		
	}// end function
	
}// end class

class svForm extends svPage{

	public $hiddenFields = false;

	//public $class = "";
	public $class_main = "";
	public $class_caption = "";
	public $class_body = "";
	public $class_tab = "";
	public $class_page = "";
	public $class_page_caption = "";
	public $class_page_body = "";
	public $class_menu = "";
	public $class_table = "";
	public $class_row = "";
	public $class_cell_title = "";
	public $class_cell_input = "";
	public $class_required = "";
	public $class_input = "";

	public $fields = array();
	public $subForm = false;
	public $page = false;

	public $grid = false;

	private $_grids = array();
	private $_grid = false;
	
	public $requiredText = "*";
	//public $name = "";
	
	private $tab = false;
	
	public $html = "";
	public $script = "";
	public $css = "";
	
	private $_page = false;
	private $_tab = false;
	private $_menu = false;
	private $_table = false;


	private $_tabs = array();
	private $_inputs = array();
	
	private $_lastInput = false;
	private $_lastField = false;
	

	private $_last = array();
	private $_lastIndex = 0;

	private $_pages = array();
	private $_menus = array();
	private $_tables = array();

	
	
	//public $_caption = false;
	
	private $_ref = "F";
	
	private $_script = "";
	private $_css = "";
	
	
	private $lastPage = "";
	
	public function __construct($opt) {
		
		svPage::__construct($opt);
		
		$this->{"data-page-type"} = "form";
		$this->{"data-form-type"} = "main";

		$this->page = $this->body;
		$this->class = $this->class_main;
		$this->hiddenFields = new sgHTML("");
		
	}// end function		

	public function setRef($name){
		$this->_ref = $name;	
	}// end function

	public function getRef(){
		return $this->_ref;	
	}// end function
	
	public function savePage(){
		
		$this->_last[$this->_lastIndex] = $this->page;
		$this->_lastIndex++;
	}// end function
	
	public function restorePage(){
		$this->page = $this->_last[$this->_lastIndex-1];
		$this->_lastIndex--;
		
	}// end function

	public function setClasses($class = ""){
		
		if($class!=""){
			$this->class = $class;	
			
		}
		
		return;
		$class = $this->class."_form_";
		

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

		if($this->class_menu == ""){
			$this->class_menu = $class."menu";
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

		$this->class = $this->class_main;
		
		$caption = $this->getCaption();
		if($caption){
			$caption->class = $this->class_caption;		
		}// end i
			
		
	}// end function

	public function setMain($obj = false){

		if(is_object($obj)){
			//$this->main = $obj;
		}	
		
		
		$this->page = $this->body;

	}// end function

	public function appendChild($ele){
		return $this->page->appendChild($ele);
		
		
		return;
		if($this->page!==false){
			
			$this->page->appendChild($ele);
		}else{
		
			svPage::appendChild($ele);
			
		}
		/*
		if($this->page == $this){
			svPage::appendChild($ele);
		}else{
			$this->page->appendChild($ele);		
		}
		*/
	}// end function

	public function addPage($name, $caption = false, $wTable = true, $checked = false, $mode = "visible"){
		
		
		if($checked){
			
			$inp = new sgHTML("input");
			$inp->type = "checkbox";
			$inp->checked = ($mode == "visible")?"checked":"";
			
			$aux = new sgHTML("");
			$aux->appendChild($inp);
			
			$aux->appendChild($caption);
			
			$ref = $this->getRef();
			
			$inp->onclick = "$ref.showPage('{$this->name}_p{$name}', this.checked);";
			
			
		}else{
			$aux = $caption;
		}
		
		
		
		
		$this->_pages[$name] = $this->_page = new svPage(array("caption"=>$aux));
		
		
		$this->_page->body->id = $this->name."_p".$name;
		
		$this->_page->body->{"data-page-mode"} = $mode;
		
		//$this->_pages[$name]->class = $this->class_page;
		if($caption = $this->_pages[$name]->getCaption()){
			$caption->class = $this->class_page_caption;
		}
		
		//$this->_pages[$name]->body->class = $this->class_page_body;

		//$this->appendChild($this->_pages[$name]);
		$this->page->appendChild($this->_pages[$name]);
		//$this->body->appendChild($this->_pages[$name]);
		$this->page = $this->_page;
		$this->lastPage = $name;
		
		if($wTable){
			$this->addTable($name);
			
		}
		return $this->page;
		
	}// end fucntion
	
	public function addTab($name){
		
		$tab = $this->_tabs[$name] = $this->_tab = new svTab($name);
		//$tab = $this->_tabs[$name] = $this->_tab = new sgTab($this->name."_tab_".$name);
		$tab->setRef($this->getRef().".tab.$name");
		
		$tab->class = "tab";//$this->class_tab;
		
		$this->page->appendChild($tab);
		return $tab;
		
		
	}// end fucntion
	
	public function addTabPage($name){
		$this->_table = false;
		return $this->page = $this->_tab->addPage($name);

	}// end fucntion	
	
	public function addTable($name){

		$this->_tables[$name] = $this->_table = new sgTable(2);
		$this->_tables[$name]->typeRender("div");
		$this->_tables[$name]->{"data-form-type"} = "table";
		
		$this->page->appendChild($this->_tables[$name]);	
		return $this->_tables[$name];
	
	
	}// end function	

	public function addMenuPage($name){

		$this->_menus[$name] = $this->_menu = new sgHTML("div");
		$this->_menu->{"data-form-type"} = "menu_page";

		$this->page->appendChild($this->_menu);
		$this->_table = false;

		return $this->page = $this->_menu;

	}// end function	

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
	
		$this->_lastInput = $field->input;	
	
		return $field;
		
	}// end fucntion

	public function addField($opt, $name = "", $title = "", $value = false, $data = false){
		

		
		$field = $this->addInput($opt, $name, $title, $value, $data);

		if($field->type != "hidden"){
			if($this->_table){
				$row  = $this->_table->insertRow();
				
				if(isset($field->input->required)){
					$title = $field->input->title.$this->requiredText;	
				}else{
					$title = $field->input->title;	
				}
				
				$row->cells[0]->appendChild($title);
				$row->cells[1]->appendChild($field->input);
			
				$row->{"data-form-type"} = "table_tr";
				$row->cells[0]->{"data-form-type"} = "table_th";
				$row->cells[1]->{"data-form-type"} = "table_td";
				
				$field->row = $row;
			}else{
				$this->page->appendChild($field->input);
				
			}
		}else{
			
			$this->hiddenFields->appendChild($field->input);

		}// end if	
		
		$this->fields[$field->name] = $field = $this->_lastField = $field;
		
		//print_r($this->fields);
		
		return $field;
		
	}// end fucntion
	
	public function getTab($name = false){
	
		if($name === false){
			return $this->_tab;
		}else{
			return $this->_tabs[$name];
		}// end if
		
	}// end function	

	public function getTabPage($name, $index){
	
		if($name === false){
			return $this->_tab->body[$index];
		}else{
			return $this->_tabs[$name]->body[$index];
		}// end if
		
	}// end function		
	
	public function getTable($name = false){
		
		if($name === false){
			return $this->_table;
		}else{
			return $this->_tables[$name];
		}// end if
		
	}// end function

	public function setMenuPage($name = false){
		
		if($name !== false){
			$this->page = $this->_menus[$name];
		}else{
			$this->page = $this->_menu;
		}// end if
		$this->_table = false;


	}// end function	


	public function getMenuPage($name = false){
		
		if($name === false){
			return $this->_menu;
		}else{
			return $this->_menus[$name];
		}// end if
		
	}// end function	

	public function setPage($name){
		
		if(is_object($name)){
			
			$this->page = $name;	
		}else{
			$this->page = $this->_pages[$name];
			if(isset($this->_tables[$name])){
				$this->_table = $this->_tables[$name];
			}
		}
		
	}// end function

	public function setTab($name){

		$this->_tab = $this->_tabs[$name];
		
	}// end function
	
	public function setTabPage($obj, $index){
		
		if(is_object($obj)){
			
			$this->page = $obj->getPage($index);	
		}else{
			
			$this->page = $this->_tabs[$obj]->getPage($index);
			
		}// end if
		
		$this->_table = false;
	}// end function	
	
	public function getPage($name = ""){
		
		if($name==""){
			$name = $this->lastPage;	
		}
		return $this->_pages[$name];
		
	}// end function

	public function setGrid($opt){
		
		$this->grid = $this->page->appendChild(new sgGrid($opt));	
		$this->grid->setClasses($this->class);
		$this->grid->setRef($this->getRef().".g");
		return $this->grid; 
		
	}

	public function addFieldRow(){
		
		$row = $this->_table->insertRow();
		
		$row->class = $this->class_row;
		$row->cells[0]->class = $this->class_cell_title;
		$row->cells[1]->class = $this->class_cell_input;
	
		return $row ;
		
	}

	public function input($input, $name){
		
		global $seq;
		return $seq->input($input, $name);
	}
	
	public function getField($name = false){
		
		if(isset($this->fields[$name])){
			return $this->fields[$name];
			
		}else{
			return $this->_lastField;	
			
		}

	}// end function

	public function getInput($name = false){
		
		if(isset($this->fields[$name])){
			return $this->fields[$name]->input;
			
		}else{
			return $this->_lastInput;	
			
		}

	}// end function

	public function getScript(){
		
		$script = "";
		$ref = $this->getRef();
		if(!$this->subForm){
			
			
			$script .= "\n\t$ref = _sgForm.create({name:'$this->name'});";
			//$script .= "\n\t$ref = new sgForm('$this->name');";
		}
		
				
		
		$script.= svPage::getScript();
		
		
		if(!$this->subForm or true){
			$script .= "\n\t$ref.init();";
			
		}
		
		return $script.$this->_script;
	}// end function

	public function setScript($script){
		$this->_script .= $script;
		
	}// end function

	public function getCssX(){
		$css = "";
		
		if($this->tab){

			$css .= $this->tab->css; 
		}
		foreach($this->_tabs as $tab){
			
			$css .= $tab->css;
		}// next
		
		
		foreach($this->fields as $field){
			
			$css .= $field->input->css;

		}// next

		
		return $css.$this->_css;
	}// end function

	public function setCss($css){
		$this->_css .= $css;
		
	}// end function
	
	public function render(){
		
		
		
		$this->appendChild($this->hiddenFields);
		
		return svPage::render();
		//return $this->main->render();

	}// end function
	
	public function addGrid($opt){
		
	
		$name = $opt["name"];

		$this->_grids[$name] = $this->_grid = new xyGrid($opt);
		$this->_grid->setRef($this->getRef());
		$this->page->appendChild($this->_grids[$name]);	
		return $this->_grids[$name];
				
		
	}

	public function grid($opt){
		
		$g = new stdClass;
		$g->cols = 0;
		$g->dx = 0;
		$g->dy = 0;
		$g->class = "";
		$g->typeEnum = "";
		$g->typeSelect = "";
		$g->paginator = true;	
		$g->page = 1;
		$g->num_pages = 5;
		$g->record_page = 4;

		foreach($opt as $k => $v){
			$g->$k = $v;	
			
		}


		$g->table = new sgTable($this->cols + $this->_dx);
		$g->table->border = 1;
		
		$g->table->id = $this->id."_grid_table";	
		
			

	
		$name = $opt["name"];

		$this->_grids[$name] = new stdClass;


		$this->_grids[$name] = $this->_grid = new xyGrid($opt);
		$this->_grid->setRef($this->getRef());
		$this->page->appendChild($this->_grids[$name]);	
		
		$this->_grids[$name] = $this->_grid = $g;
		
		return $this->_grids[$name];
				
		
	}

	public function addRow($data, $check = false){
		
		
		$g = $this->_grid;
		
		$m = $g->n++;
		//$this->n++;
		$row = $g->table->insertRow();
		if($g->typeEnum > 0){
			$row->cells[0]->appendChild($g->n);
			
		}// end if		

		if($g->typeSelect > 0){
			$select = new sgHTML("input");
			if($g->typeSelect == 2){
				$select->type = "checkbox";
			}else{
				$select->type = "radio";
				
			}// end if
			
			$select->name = $g->name."_sel_opt";
			
			$row->cells[$this->_dx-1]->appendChild($select);	
			
			if($check){
				$select->checked = "checked";	
				
			}
			
			$ref = $this->getRef();	
			
			if($g->type == "check_list"){
				$select->onclick = "$ref.grid.editCheckList($m);";	
				
			}else{
				$select->onclick = "$ref.grid.getRecord($m);";	
				
			}
			
						
		}// end if	
		
		$c = 0;
		
		foreach($g->_col as $i => $col){	
			$value = "";
			if(isset($data[$col["name"]])){
				$value = $data[$col["name"]];
			}
			$_col = $g->_col[$i];
			$_col["name"] = $_col["name"]."_i$m";
			if(isset($_col["parent"]) and $_col["parent"] !=""){
				
				$_col["parent"] = $_col["parent"]."_i$m";
			}
			
			switch($g->type){
				case "basic":
				case "simple":
					$_col["type"] = "hidden";
					$input = $this->addInput($_col);
					$input->input->value = $value;	
					$g->hiddenFields->appendChild($input->input);
					
					if($col["hide"]){
						
						continue;	
					}					
					$row->cells[$i+$g->dx]->appendChild($value);

					break;				
				case "multi":
				case "check_list":
					$input = $this->addInput($_col);
					$input->input->value = $value;	
					
					if($col["hide"]){
						$g->hiddenFields->appendChild($input->input);
						continue;	
					}
					$row->cells[$c+$g->dx]->appendChild($input->input);
					break;				
				case "dinamic":
					$input = $this->addInput($_col);
					$input->input->value = $value;	
					
					if($col["hide"]){
						$g->hiddenFields->appendChild($input->input);
						continue;	
					}
					$row->cells[$c+$g->dx]->appendChild($input->input);
					break;				
			}// end switch
			$c++;
		}// next
		
	}// end function
	
	
}// end class

?>