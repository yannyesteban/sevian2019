<?php


//include ("connection.php");

//include ("sg_panel.php");
//include ("functions.php");

//include ("sg_html.php");
//include ("sg_input.php");
//include ("cfg_tab.php");


include ("sgInput.php");
include ("svMenu.php");

include ("sgFormSave.php");
include ("sgGrid.php");



class sgTab{
	
	
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
	
	public $class_main = "tab";
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
		
		$this->_main->{"data-tab-type"} = "main";
		
		if($class !== false){
			$this->setClass($class);
		}else{
			$this->_main->class = $this->class_main;
			//$this->_menu->class = $this->class_menu;
			//$this->_body->class = $this->class_body;
		}// end if
		
	
	}// end function
	
	public function setClass($class = ""){
		return;
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
		

		$script = "\n\t$ref = _sgTab.create({menu:'".$this->_menu->id."', body:'".$this->_body->id."'});";		
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



class page extends sgHTML{
	public $name = "";
	public $tagName = "section";
	public $caption = false;
	public $body = false;
	
	public function __construct	($name = "", $caption = false){
		$this->id = $name;
		
		if($caption !== false){
			$this->caption = new sgHTML("header");
			$this->caption->appendChild($caption);
			sgHTML::appendChild($this->caption);
			sgHTML::appendChild("\n");
		}
		$this->body = new sgHTML("div");
		
		
		sgHTML::appendChild($this->body);
		
	}
	public function setCaption($caption){
		if(!$this->caption){
			$this->caption = new sgHTML("header");
			sgHTML::insertFirst($this->caption);
		}
		$this->caption->appendChild($caption);
		
	}
	public function getCaption(){
	
		return $this->caption;
		
	}
	public function appendChild($child){
		$this->body->appendChild("\n");
		$this->body->appendChild($child);
		$this->body->appendChild("\n");
		
	}
	
	public function render(){
		
		return sgHTML::render();
			
	}
	
}

class sgForm_OFFLINE{

	public $hiddenFields = false;

	public $class = "";
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

	public $css = "";
	public $css2 = 
	
	"


	._tab_main{
	border:2px solid red;
	padding:6px;	
		
	}
	
	
		._tab_menu{
			/*margin-top:5px;*/
			display: flex;
			flex-direction: row;
			flex-wrap: nowrap;
			justify-content: flex-start;
			align-content: stretch;
			align-items: flex-start;
			
			
		}
		._tab_open, ._tab_close{
			
			
			padding:4px;
			padding-left:10px;
			min-width:70px;
			height:1.5em;	
			
			color:white;
			
			font-family:tahoma;
			font-size:9pt;
			text-decoration:none;
			/*font-weight:;
			border-width:3px;
			border-color: red;
			border-style:solid;
			#066;*/
			border-top-width:4px;
			/*
			border-top-left-radius:4px;
			border-top-right-radius: 4px;
			border-bottom-right-radius: 0px;
			border-bottom-left-radius: 0px;
			*/
			border-radius: 4px 10px 0px 0px;
			cursor:pointer;
			-webkit-touch-callout: none;
			-webkit-user-select: none;
			-khtml-user-select: none;
			-moz-user-select: none;
			-ms-user-select: none;
			user-select: none;
			margin-right:2px;
		
		}
		._tab_open:HOVER, ._tab_close:HOVER{
			color:yellow;
			
			transition: all 1s ease-in-out;
			-moz-transition: all 1s ease-in-out;
			-webkit-transition: all 1s ease-in-out;
			-o-transition: all 1s ease-in-out;	
			
		}
		
		
		._tab_open{
			font-weight:bold;
			color:#3FF;
			background-color:#999999;
			border: 1 px red solid;	
		}
		._tab_close{
			font-weight:bold;
			background-color:#666;	
		}
		._tab_page_open, ._tab_page_close{
			background-color:#FFFFFF;
			position:relative;	
			padding:4px;
			border-top: 8px solid #999999;
			
			height:100%;
			width:100%;	
			
			box-sizing: border-box;	
		}
		._tab_page_open{
			display:inherit;
		
			
		}
		._tab_page_close{
			display:none;
			
			
		}


tr.fila {
	background-color:#333;
	border:12px solid red;
	color:white;

}
";
	public $name = "";
	
	private $tab = false;
	
	public $html = "";
	public $script = "";
	//public $css = "";
	
	private $_page = false;
	private $_tab = false;
	private $_menu = false;
	private $_table = false;


	private $_tabs = array();
	private $_inputs = array();

	private $_last = array();
	private $_lastIndex = 0;

	private $_pages = array();
	private $_menus = array();
	private $_tables = array();

	
	
	private $_caption = false;
	
	private $_ref = "F";
	
	private $_script = "";
	private $_css = "";
	

	
	
	public function __construct($name="") {
		
		$this->name = $name;
		$this->main = new page;
		$this->body = $this->main->body;
		$this->page = $this->main;
		
		$this->main->class = $this->class_main;
		
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

		$this->main->class = $this->class_main;
		if($this->main->caption){
			$this->main->caption->class = $this->class_caption;		
		}// end i
			
		
	}// end function
	
	public function setCaption($caption){
			
		$this->main->setCaption($caption);
		$this->main->caption->class = $this->class_caption;
		
	}// end function

	

	public function setMain($obj = false){

		if(is_object($obj)){
			$this->main = $obj;
		}	
		
		
		$this->page = $this->main;

	}// end function

	public function appendChild($ele){
		
		$this->page->appendChild($ele);		
		
	}// end function

	public function addPage($name, $caption = false, $wTable = true){
		
		$this->_pages[$name] = $this->_page = new page(false, $caption);
		$this->_pages[$name]->class = $this->class_page;
		if($this->_pages[$name]->caption){
			$this->_pages[$name]->caption->class = $this->class_page_caption;
		}
		
		//$this->_pages[$name]->body->class = $this->class_page_body;


		$this->page->appendChild($this->_pages[$name]);
		//$this->body->appendChild($this->_pages[$name]);
		$this->page = $this->_pages[$name];
		$this->lastPage = $name;
		
		if($wTable){
			$this->addTable($name);
			
		}
		return $this->page;
		
	}

	
	public function addTab($name){
		
		$tab = $this->_tabs[$name] = $this->_tab = new sgTab($name);
		//$tab = $this->_tabs[$name] = $this->_tab = new sgTab($this->name."_tab_".$name);
		$tab->setRef($this->getRef().".tab.$name");
		
		$tab->class = "tab";//$this->class_tab;
		
		$this->page->appendChild($tab);
		return $tab;
		
		
	}
	public function addTabPage($name){

		return $this->page = $this->_tab->add($name);

	}	
	
	public function addTable($name){

		$this->_tables[$name] = $this->_table = new sgTable(2);
		$this->_tables[$name]->typeRender("div");
		$this->_tables[$name]->class = $this->class_page_body;
		$this->page->appendChild($this->_tables[$name]);	
		return $this->_tables[$name];
	
	
	}// end function	
	

	public function addMenuPage($name){

		$this->_menus[$name] = $this->_menu = new sgHTML("div");
		$this->_menu->class = $this->class_menu;
		$this->page->appendChild($this->_menu);
		return $this->page = $this->_menu;

	}// end function	




	public function addField($type, $name = "", $title = "", $value = false, $data = false){
		
		
		
		$this->fields[$name] = new stdClass;
		$this->fields[$name]->input = $this->input($type, $name);
		$this->fields[$name]->input->title = $title;
		$this->fields[$name]->input->setRef($this->getRef().".e.$name");
		$this->fields[$name]->input->formName = $this->name;
		if($type != "hidden"){
			$row = $this->_table->insertRow();
			$row->cells[0]->appendChild($title);
			$row->cells[1]->appendChild($this->fields[$name]->input);
		
			$row->class = $this->class_row;
			$row->cells[0]->class = $this->class_cell_title;
			$row->cells[1]->class = $this->class_cell_input;
			$this->fields[$name]->row = $row;

		}else{
			
			
			$this->hiddenFields->appendChild($this->fields[$name]->input);
			//$this->body->appendChild($this->fields[$name]->input);	
		}// end if	
		
		$this->fields[$name]->input->value = $value;
		if($data !== false){
			$this->fields[$name]->input->data = $data;
		}
		
		
		$this->fields[$name]->input->class = $this->class_input;
	
		return $this->fields[$name];
		
	}
	
	
	

	
	
	

	
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
			
		}
		
		
	}// end function

	public function setTab($name){

		$this->_tab = $this->_tabs[$name];
		
	}// end function
	
	public function setTabPage($obj, $index){
		
		if(is_object($obj)){
			
			$this->page = $obj->body[$index];	
		}else{
			$this->page = $this->_tabs[$obj]->body[$index];
			
		}// end if
		
		
	}// end function	

	
	public function getPage($name = ""){
		
		if($name==""){
			$name = $this->lastPage;	
		}
		return $this->_pages[$name];
		
	}// end function



	public function addInput($type, $name = "", $title = "", $value = false, $data = false){
		
		$this->fields[$name] = new stdClass;
		$this->fields[$name]->input = $this->input($type, $name);
		$this->fields[$name]->input->title = $title;
		$this->fields[$name]->input->setRef($this->getRef().".e.$name");
		$this->fields[$name]->input->formName = $this->name;
		$this->fields[$name]->input->value = $value;

		if($data !== false){
			$this->fields[$name]->input->data = $data;
		}
		
		$this->fields[$name]->input->class = $this->class_input;
	
		return $this->fields[$name]->input;
		
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
	
	public function getField($name){
		return $this->fields[$name]; 	
	}
	
	public function getScript(){
		$script = "";
		$ref = $this->getRef();
		if(!$this->subForm){
			$script .= "\n\t$ref = _sgForm.create({name:'$this->name'});";
			//$script .= "\n\t$ref = new sgForm('$this->name');";
		}
		
		
		if($this->tab){

			$script .= $this->tab->getScript(); 
		}
		foreach($this->_tabs as $tab){
			
			$script .= $tab->getScript();
		}// next
		
		
		foreach($this->fields as $field){
			
			$script .= $field->input->getScript();
			if($this->subForm){
				$script .= "\n\t".$field->input->getRef().".init();";
			
			}
		}// next
		if(!$this->subForm){
			$script .= "\n\t$ref.init();";
		}
		
		return $script.$this->_script;
	}// end function

	public function setScript($script){
		$this->_script .= $script;
		
	}// end function

	public function getCss(){
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
		$this->main->appendChild($this->hiddenFields);
		return $this->main->render();

	}// end function
	
	
	
}// end class
/*
exit;


//$cn = new_conection("mysqli","127.0.0.1","root" ,"123456", "higiene_2016");
$cn = new_conection("postgres","127.0.0.1","root" ,"123456", "higiene_2016");






$s = new sgFormSave;
$s->cn = &$cn;


$data = array();

$data["id"] = "230";   

$data["cedula"] = "12474737";   
$data["nombre"] = "Yanny";   
$data["apellido"] = "RODRIGUEZ";   
$data["nacimiento"] = "1975-10-24";   
$data["codestado"] = "1"; 
//$data["codmunicipio"] = "1"; 
$data["direccion"] = "CARACAS";  
$data["profesion"] = "abogados 2";  
$data["tipo"] = "W";  

//$data["id2"] = "9"; 
$data["__form_record"] = "id=230,id2=230,tipo1=H";  
$data["__form_mode"] = "2";  



$s->data = $data;
//$s->infoTable("personas");


$s->infoQuery("SELECT personas.*, estudios.* FROM personas INNER JOIN estudios ON personas.id=estudios.id");

//$s->infoQuery("SELECT personas.* FROM personas");


//print_r($s->tables["datos"]);exit;

$s->setFieldParam("id2", "ref_value", "id");
$s->setFieldParam("apellido", "upper", true);
//$s->setFieldParam($field, $param, $value);
//$s->setFieldParam($field, $param, $value);

//$s->fields["apellido"]->valueRules["upper"] = true;
//$s->fields["id2"]->valueRules["ref_value"] = "id";
//$s->setTables(array("personas"));

print_r($s->save($data));
//$cn->close();
//print_r($cn);
exit;



$doc = new sgHTMLDoc("html");
$doc->content_type = "text/html; charset=iso-8859-1";
$doc->title->appendChild("Prueba");

$doc->appendCssSheet("../css/form.css");
$doc->appendScript("SGC_PATH_IMAGES = '../images/';");
$doc->appendScriptDoc("../js/dragDrop.js", false);
$doc->appendScriptDoc("../js/popup2.js", false);
$doc->appendScriptDoc("../js/debug.js", false);
$doc->appendScriptDoc("../js/sgTab.js", false);
$doc->appendScriptDoc("../js/sgForm.js", false);
//$doc->appendScriptDoc("../js/form.js", false);
$doc->appendScriptDoc("../js/sgInput.js", false);

//$tab = new sgTab("x");

//$tab->add("uno", "pagina uno");
//$tab->add("dox", "pagina dos");
//$doc->body->appendChild($tab->render());
//$doc->appendScript($tab->script, true);


$f = new sgForm("f4");
$f->setClasses();
$f->setCaption("HOLA");
$f->setTab();

$f->addTab("***");
$f->body->appendChild("ssssssssssss");

$f->addTab("Uno");
$doc->appendCssStyle($f->css);
$f->addPage("1", "Datos Básicos");
$f->getPage()->body->class = "xx";
$f->getPage()->caption->class = "yy";
$f->addField("text", "cedula", "Cédula");
$f->addField("text", "nombres", "Nombres");
$f->addField("text", "apellidos", "Apellidos");
$f->body->appendChild("<input type='button' value='Ok'>");
$f->addTab("Dos");
$f->addPage("2", "Datos Profesionales");
$f->getPage()->body->class = "xx1";
$f->getPage()->caption->class = "yy1";

$field = $f->addField("text", "edad", "Edad");
$field->input->propertys["style"]="color:red;";

$field->input->propertys["onmouseover"]="this.style.cssText='color:blue;font-weight:normal';";
$field->input->propertys["onmouseout"]="this.style.cssText='color:red;font-weight:bold';";

$f->addField("checkbox", "sexo", "Sexo");
$f->addField("select", "ciudad", "Ciudad");

$f->addPage("3", "Datos Académicos");
$f->addField("text", "edad1", "Edad 1");
$f->addField("checkbox", "sexo1", "Sexo 1");
$f->addField("select", "ciudad1", "Ciudad 1");
//$f->getPage()->body->class = "xx";
//$f->getPage()->caption->class = "yy";
$f->addTab("Tres");
$f->addPage("4", "");


$data1[] = array("value"=>1, "text"=>"Dto Capital", "parent" => "");
$data1[] = array("value"=>2, "text"=>"Aragua", "parent" => "");
$data1[] = array("value"=>3, "text"=>"Carabobo", "parent" => "");
$data1[] = array("value"=>4, "text"=>"Cojedes", "parent" => "");

$field = $f->addField("select", "codestado", "Estado");
$field->input->data = $data1;

$field->input->childs = true;


$data2[] = array("value"=>1, "text"=>"Valencia", "parent" => "3");
$data2[] = array("value"=>2, "text"=>"Bejuma", "parent" => "3");
$data2[] = array("value"=>3, "text"=>"San Carlos", "parent" => "4");
$data2[] = array("value"=>4, "text"=>"Tinaco", "parent" => "4");

$field = $f->addField("select", "codmunicipio", "Municipio");
$field->input->data = $data2;
$field->input->parent = "codestado";

$f->main->appendChild("<input type='button' value='Cancel'>");

$form = new sgHTML("form");
$form->appendChild($f);
$form->name = $f->name;
$doc->body->appendChild($form);
$doc->appendScript($f->getScript(), true);



//$f->getPage("1")->body->class = "xx";
//$f->getPage("1")->caption->class = "yy";


echo $doc->render();
exit;

$doc = new sgHTMLDoc("html");
$doc->content_type = "text/html; charset=iso-8859-1";
$doc->title->appendChild("Prueba");
$doc->appendCssSheet("../css/sigefor.css");
$doc->appendScript("SGC_PATH_IMAGES = '../images/';");
$doc->appendScriptDoc("../js/dragDrop.js", false);
$doc->appendScriptDoc("../js/popup2.js", false);
$doc->appendScriptDoc("../js/debug.js", false);

$doc->appendScriptDoc("../js/sgForm.js", false);
$doc->appendScriptDoc("../js/sgInput.js", false);

$data1[] = array("value"=>1, "text"=>"Dto Capital", "parent" => "");
$data1[] = array("value"=>2, "text"=>"Aragua", "parent" => "");
$data1[] = array("value"=>3, "text"=>"Carabobo", "parent" => "");
$data1[] = array("value"=>4, "text"=>"Cojedes", "parent" => "");


$data2[] = array("value"=>1, "text"=>"Valencia", "parent" => "3");
$data2[] = array("value"=>2, "text"=>"Bejuma", "parent" => "3");
$data2[] = array("value"=>3, "text"=>"San Carlos", "parent" => "4");
$data2[] = array("value"=>4, "text"=>"Tinaco", "parent" => "4");


$i = new input("select", "estado");
$i->value = 4;
$i->data = $data1;
$i->childs = true;


$i2 = new input("select", "municipio");
$i2->data = $data2;
$i2->parent = "estado";
$i2->value = 4;
$i2->parentValue = $i->value;

$i2->events["onparentchange"]="{db(this.getValue())}";

$i3 = new input("select", "parroquia");
$form = new sgHTML("form");
$form->name = "xx";
$form->appendChild($i);
$form->appendChild($i2);

$doc->body->appendChild($form);
$script = "

var f = new sgForm('xx');


";

$i->formName = "xx";
$i2->formName = "xx";

$i->setRef("f.e.estado");
$i2->setRef("f.e.municipio");

$script .= $i->getScript();
$script .= $i2->getScript();


$script .= "\nf.init();";

$doc->appendScript($script, true);

echo $doc->render();
exit;


$a["titulo"]= "esteban";
$i = new input("text", "nombre");
$i->id = "nombre_p4";
$i->formName = "sg_panel_4";

$i->setParams($a);
$i->propertys["style"] = "border:2px solid red;";
$i->propertys["onclick"] = "alert(this.value)";
$i->rules["mandatory"] = "y";

$i->events["onmouseover"] = "this.style.cssText='background-color:yellow;'";


//echo $i->getScript();
echo $i->render().$i->titulo;


$i2 = new input("radio", "opcion");
$i3 = new input("radio", "opcion");
$i4 = new input("checkbox", "opcion");
echo $i2->render().$i3->render().$i4->render();

exit;


$f = new sgForm("x");
$f->classMain = "fila";
$f->createCaption("Hola");
$f->newForm(1);

$field = $f->addField(1, "text", "nombre");
$field->title = "Nombre:";

$field = $f->addField(1, "text", "apellido");
$field->title = "Apellido:";
//$field->rowClass = "fila";

$field = $f->addField(1, "text", "nacimiento");
$field->title = "Nacimiento:";

$tab = new sgTab("x");

$tab->add("uno", "pagina uno");
$tab->add("dox", "pagina dos");
//echo $f->render().$tab->render().$tab->script;

$doc = new sgHTMLDoc("html");
$doc->content_type = "text/html; charset=iso-8859-1";
$doc->content_type = "text/html; charset=UTF-8";

$doc->appendCssSheet("sigefor.css");
$doc->appendCssStyle($f->css);

$doc->appendScriptDoc("../js/form.js", false);



$doc->title->appendChild("Prueba");
$doc->body->appendChild($f);
$doc->body->appendChild($tab->render());
$doc->appendScript($tab->script, true);


echo $doc->render();
exit;


$f = new sgForm("name");
$f->typeRender("table");//div,ul,ol,span
$f->setCaption("Usuarios");

$input = $f->add("text", "cedula");
$input->setProperty("size","4");
$input->data = array();



$f->render();




*/
?>