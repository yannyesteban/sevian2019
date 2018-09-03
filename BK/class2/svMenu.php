<?php


class _controlHTML extends sgHTML{
	
	public $name = "";	
	public $_ref = "bbbb";
	
	
	private $_last = false;
	private $_lastIndex = false;
	
	private $_obj = array();	
	private $_script = "";
	private $_css = "";

	
	public function setRef($name){
		
		$this->_ref = $name;	
	
	}// end function
	
	public function getRef(){
	
		return $this->_ref;	
	
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

		foreach($this->_obj as $obj){
			if(method_exists($obj, "getScript")){
				$script .= $obj->getScript();
			}
		}// next
		
		return $script.$this->_script;
	}// end function	
	
}// end class

class svMenu extends _controlHTML{
	
	public $menu = "";
	public $_ref = "MEN1";
	public $caption = false;
	public $tagName = "div";
	
	public $classMain = '_menu_a_main';
	public $classPage = '_menu_a_page';
	public $classItem = '_menu_a_item';
	public $classPgOpen = '_menu_a_show';
	public $classPgClose = '_menu_a_hide';

	public $menuInd = "../higiene_2016/images/min2.png";

	private $_parentId = 0;
	private $_index = 1;
	private $_lastIndex = 0;

	private $_last = false;
	private $mainMenu = false;
	
	private $_item =array();
	
	public $typeMenu = "submit";//1:Normal with Icon, 2:Normal without Icon, 3:
	public $withIcon = true;
	public $dinamic = true;
	
	public function __construct($opt){
		if(is_array($opt)){
			foreach($opt as $k => $v){
				$this->$k = $v;	
			}// next			
		}else{
			
			$this->id = $opt;
		}// end if		
		
		//$this->menu = $this->mainMenu = new sgHTML("ul");
		//$this->menu->class = $this->classMain;
		//$this->appendChild($this->menu);
		//$this->style = "border:3px solid pink;margin:3px;padding:3px;";
		
	}// end function	
	
	public function setCaption($caption){

		$this->caption = $caption;
		if(!$this->dinamic){
			
			$c = new sgHTML("div");
			$c->innerHTML = $caption;
			
			$this->appendChild($c);				
			return $c;
			
		}
		
	}// end function
	
	public function render1(){
		
		if($this->dinamic){
			return _controlHTML::render();	
		}else{
		
			$menu = $this->menu;
			$parentId = 0;
			foreach($this->_item as $item){
				
				if($item->parentId > 0){
					
					$menu = $this->_item[$item->parentId]->menuEle;
				}
				
				$this->_item[$item->index]->ele = $li = new sgHTML("li");
				
				$li->class = $this->classItem;
				$li->appendChild($a = $this->createItem($item->title, $item->image));
				
				$a->onclick = $item->action;
				
				$menu->appendChild($li);
				
				if($item->menu){
					
					$img = $li->add("img");
					$img->src = $this->menuInd;
					//$li->appendChild($img)	;
					$item->menuEle = new sgHTML("ul"); 
					$item->ele->appendChild($item->menuEle);
					$item->menuEle->class = $this->classPage;
					
				}
				
			}// next
			
			return _controlHTML::render();
		}// end if
	
	}// end function


	public function getScript(){
		$ref = $this->getRef();

		$script = "\n\t$ref = new myMenu('$this->id');";		
		$script .= "\n\t$ref.menuId = '$this->id';";	
		$script .= "\n\t$ref.type = '$this->typeMenu';";
		$script .= "\n\t$ref.icon = '$this->icon';";
		if(is_string($this->caption)){
			$script .= "\n\t$ref.caption = '".addslashes($this->caption)."';";
		}	

		$script .= "\n\t$ref.init();";	

		foreach($this->_item as $item){
			
			$a = array(
				"index" => $item->index,
				"parentId" => $item->parentId,
				"title" => $item->title,
				"image" => $item->image,
				"onclick" => $item->action,
				"separator" => $item->separator,
				"dataset" => $item->dataset,
			
			);
			
			//$json = json_encode(array_map(function($v){return is_string($v)? utf8_encode($v): $v;}, $a));
			$json = sgJSON::encode($a);
			$script .= "\n\t$ref.add($json);";	
			
		}
		
		return $script;
		
	}//end function	
	
	public function getScript1(){

		$ele->{"data-men-type"} = "a";
		$ele->{"data-men-level"} = "0";
		
	}//end function

	public function addMenu($opt = false){
		
		if($opt !== false){
			
			$this->addItem($opt);
		}
		
		
		$this->_last->menu = true;
		
		$this->_parentId = $this->_last->index;
		
		return $this->_last;
		
	}//end function
	
	public function addMenu2($opt = false){
		
		
		if($opt !== false){
			
			$this->addItem($opt);
		}
		
		
		if(!$this->_last->menu){

			$this->menu = new sgHTML("ul");
			
			$this->menu->style = "border:1px solid red;padding:2px; margin:2px;";
			
			$this->_last->ele->appendChild($this->menu);
			$this->_last->menu = $this->menu;
			$this->_parentId = $this->_last->index;
			
			$img = $this->_last->link->add("img");
			$img->src ="../higiene_2016/images/min2.png";
			
		}
		

		
		return $this->_last;
		
	}//end function
	
	public function setMenu($ele = false){
		
		if($ele){
			
			$ele->menu = true;
			$this->_parentId = $ele->index;

		}else{
			
			$this->_parentId = 0;

		}
		
		
	}// end function
	
	public function setMenu2($ele = false){
		
		if($ele){
			
			$this->_last = $ele;
			$this->addMenu();
			//$this->menu = $ele->menu;
			//$this->_parentId = $ele->index;
		}else{
			
			$this->menu = $this->mainMenu;
			$this->_parentId = 0;
			//hr($this->_parentId,"blue");
		}
		
		
		
		
		
		return $this->menu;
		
	}


	private function createItem($title = "", $image = "", $class = "", $ind = ""){


		$a = new sgHTML(array(
			"tagName" => "a",
			"href" => "javascript:void(0);"
			));
		
		
		switch($this->type){
		case "1":
		default:
			
			$divText = new sgHTML("");
			//$divInd = new sgHTML("div");
			
			if($this->withIcon and $image != ""){
				$divImage = new sgHTML("div");
				$img = $divImage->add("img");
				$img->width="20px";
				$img->src = $image;
				
				$a->appendChild($divImage);	
			}
			
			//$divIcon->src = $image;
			$divText->innerHTML = $title;
			
			
			$a->appendChild($divText);
			//$a->appendChild();
			break;
		
			
		}
		
		return $a;
		
	}


	public function addItem($opt){
		$item = new stdClass;

		
		
		if(is_array($opt)){
			$title = (isset($opt["title"]))? $opt["title"]: "";
			$image = (isset($opt["image"]))? $opt["image"]: "";
			$class = (isset($opt["class"]))? $opt["class"]: "";
			$index = (isset($opt["id"]))? $opt["id"]: $this->_index;
			$parentId = (isset($opt["parentId"]))? $opt["parentId"]: $this->_parentId;
			$action = (isset($opt["action"]))? $opt["action"]: "";
			$separator = (isset($opt["separator"]))? $opt["separator"]: "";
			$dataset = (isset($opt["dataset"]))? $opt["dataset"]: "";

			
		}else{
			
			$title = $opt;
			$index = $this->_index;
			$parentId = $this->_parentId;
			$image = false;
			$action = false;
			$separator = $separator;
			$dataset = false;
		}// end if		
		
				

		$item->title = $title;
		$item->parentId = $parentId;
		$item->index = $this->_lastIndex = $index;
		$item->image = $image;
		$item->menu = false;
		
		$item->action = $action;
		$item->separator = $separator;
		$item->dataset = $dataset;
		if($parentId > 0){
			
			$this->_item[$parentId]->menu = true;
		}
		
		$this->_index++;
		
		return $this->_last = $this->_item[$index] = $item;
	}// end function

	public function getIndex($ele){
		return $ele->index;
		
	}// end function

	public function setLinkProperty($item, $property){
		
		$item->linkProperty = $property;
		
	}// end function

	public function setItemProperty($item, $property){
		
		$item->linkProperty = $property;
		
	}// end function

	public function setAction($action){
		
		$this->_last->action = $action;
		
	}// end function

	public function addItem1($opt){
		$item = new stdClass;

		
		$ele = new sgHTML("li");
		
		$ele->{"data-men-type"} = "a";
		$ele->{"data-men-level"} = "0";
		
		if(is_array($opt)){
			$title = (isset($opt["title"]))? $opt["title"]: "";
			$icon = (isset($opt["icon"]))? $opt["icon"]: "";
			$class = (isset($opt["class"]))? $opt["class"]: "";
			$a = $this->createItem($title, $icon, $class, false);
			

			$index = (isset($opt["id"]))? $opt["id"]: $this->_index;
			$parentId = (isset($opt["parentId"]))? $opt["parentId"]: $this->_parentId;

			//$item->title = $title;
			//$item->parentId = $parentId;
			//$item->index = $index;
			//$item->menu = false;			
			
		}else{
			$title = $opt;
			$a = $this->createItem($title, false, false, false);
			
			//$item->title = $title;
			//$item->parentId = $this->_parentId;
			//$item->index = $this->_index;
			//$item->menu = false;			
			
			$index = $this->_index;
			$parentId = $this->_parentId;
			
		}// end if		
		
		
		
		$ele->appendChild($a);
		$ele->style = "color:blue;border:2px solid green;margin:2px;padding:2px;";


		
		/*
		$item->ele = $ele;
		$item->title = $title;
		$item->parentId = $this->_parentId;
		$item->index = $this->_index;
		$item->menu = false;
*/
		$item->ele = $ele;
		$item->link = $a;
		$item->title = $title;
		$item->parentId = $parentId;
		$item->index = $index;
		$item->menu = false;

		
		
		
		//$link = $item->add("a");
		

		
		//hr($index, "red");
		if($parentId != $this->_parentId){
			//print_r($this->_item[$parentId]);
			
			if($parentId == 0){
				$this->menu = $this->mainMenu;	
			}else{
				$this->_last = $this->_item[$parentId];
				
				$this->addMenu();
				$this->menu =  $this->_item[$parentId]->menu;
			}
			
			
			
		}else{
			
			
		}

		$this->menu->appendChild($ele);				
		
		$this->_last = $this->_item[$index] = $item;

		$this->_lastIndex = $index;		
		
		$this->_index++;
		
		return $item;
	}// end function
	
	public function test(){
		
		foreach($this->_item as $item){
			hr($item->title."...".$item->index."...".$item->parentId);			
			
		}
		
		
	}// end function
	
	
}// end class



class sgMenu_OFFLINE{
	public $name = "";
	
	private $_items = array();
	
	private $_type = "";
	
	private $_ref = false;
	
	public function __construct($name = false){
		
		if($name){
			$this->name = $name;
			
		}
		
		
		
	}
	
	
	public function setRef($name){
		$this->_ref = $name;	
	}// end function
	public function getRef(){
		return $this->_ref;	
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
	
	public function setCaption($caption){
			
		$this->main->caption->appendChild($caption);
		$this->main->caption->class = &$this->class_caption;
	}
	
	public function setTab(){
		$this->tab = new sgTab($this->getRef()."_tab");
		$this->tab->setRef($this->getRef().".tab");
		
		$this->tab->class = $this->class_tab;
		$this->main->appendChild($this->tab);
		
		
	}
	
	
	public function setMenu($type = "", $typeRender = "span"){
		
		$this->_type = $type;
		$this->t = new sgTable(1);
		$this->t->typeRender($typeRender);
		$this->t->border = 1;
		
		
	}
	
	public function addAction($name, $title, $event = false){
		$this->items[$name] = new stdClass;
		
		$t = $this->t;
		
		$this->items[$name]->row = $row = $t->insertRow();
		
		
		if($this->_type != "submit" and $this->_type != "button"){
			
			$button = new sgHTML("a");
			$button->href ="javascript:void(0);";
			$button->innerHTML = $title;
			
		}else{
			
			$button = new sgHTML("input");
			$button->type = $this->_type;
			$button->value = $title;
			
			
		}
		$row->cells[0]->appendChild($button);
		$this->items[$name]->button = $button;
		
		if($event !== false){

			$button->onclick = $event;
			
		}
		
		return $this->items[$name];
		
		
		
	}
	
	public function render(){
		
		return $this->t->render();
		
	}
	
	
	public function getScript(){
		return "";
		
		$script = "";
		$ref = $this->getRef();
		$script .= "\n\t$ref =  new sgMenu('$this->name');";
		
		if($this->tab){

			$script .= $this->tab->getScript(); 
		}
		
		
		foreach($this->fields as $field){
			
			$script .= $field->input->getScript();
		}
		
		$script .= "\n\t$ref.init()";
		return $script;		
		
		
	}
	
	
	
}// end class




?>