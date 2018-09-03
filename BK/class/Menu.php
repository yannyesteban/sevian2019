<?php

namespace Sevian;

class MenuItem extends HTML implements \JsonSerializable{
	public $tagName = "li";
	public $caption = false;
	private $_menu = false;
	private $_submenu = false;
	public $action = false;
	public $events = array();
	public $classImage = false;
	public $id = false;
	public $index = false;
	public $parent = false;
	public $icon = false;
	
	public function jsonSerialize(){
		return array_filter((array)$this, function($value){
			return ($value===false)? false: true;
			
		});
    }
	
	
	public function __construct($opt){
		foreach($opt as $k => $v){
			
			$this->$k = $v;
			
		}
		$this->class = "item";
		
		$option = $this->add("a");
		$option->class = "option";
		$option->id = $this->id;
		if($this->wIcon){
			$icon = $option->add("div");
			$icon->class = "icon";
			if($this->classImage){

				$icon->class .= " ".$this->classImage;
			}
			
			if($this->icon){
				$img = $icon->add("img");
				$img->src = $this->icon;
			}
			
		}
			
		$caption = $option->add("span");
		$caption->class = "caption";
		
		foreach($this->events as $k => $v){
			$option->{"on$k"} = $v;
		}

		if($this->action){
			if(!isset($option->onclick)){
				$option->onclick = $this->action;
			}else{
				$option->onclick .= $this->action;
			}
		}

		$caption->innerHTML = $this->caption;
		
	}
	
	public function getMenu(){
		return $this->_menu;
	}
	public function createMenu(){
		
		$this->_menu = $this->add("ul");
		$this->_menu->class = "submenu";
		
/*			
			if(!this._item.query(".ind")){
				this._item.create("div").addClass("ind").ds("sgMenuType", "ind");
			}
			
			this._menu.addClass("submenu");
			
			if(classMenu){
				this._menu.addClass(classMenu);
			}
		
*/	}
	
	public function _appendChild($child){
		
		$this->_menu->appendChild($child);	
	}
	
	public function renderX(){

	
		
		return HTML::render();
	
	}
	
	
	
}

class Menu implements \JsonSerializable{
	public $main = "";
	public $id = false;
	public $type = "normal";
	public $mode = "default";
	public $caption = false;
	private $dinamic = true;
	public $value = false;
	public $className = false;
	public $items = false;
	public $pullX = "front";
	public $pullY = "top";
	
	public $pullDeltaX = -3;
	public $pullDeltaY = 5;
	
	public $wCheck = true;	
	
	
	
	public $item = false;
	private $_item = array();
	
	
	
	private $_menu = [];
	
	
	public function jsonSerialize(){
		
		return $this;
		return array_filter((array)$this, function($value){
			return ($value===false)? false: true;
			
		});
		
		/*
		$a = (array)$this;
		array_walk_recursive($a, function($v,$k) use (&$a) {
			if($v === false) unset($a[$k]);
		});
		
		return $a;
		*/
		
    }
	public function __construct($opt = array()){
		
		
		foreach($opt as $k => $v){
			
			$this->$k = $v;
			
		}
		
	}
	
	public function add($opt){
		
		$this->_item[$opt["index"]] = $opt;
		
		
		
		//$this->items[] = $opt;
		
	}
	
	
	public function render(){
		
		$main = new HTML("div");
		$main->id = $this->id;
		
		
		if($this->dinamic){
			
			$this->target = $main->id;
			$this->items = &$this->_item;
			return $main->render();
		}
		
		$this->main = $main->id;
		$main->class = "sg-menu";
		
		
		if($this->caption !== false){
			
			$caption = $main->add("header");
			$caption->innerHTML = $this->caption;
			$caption->class = "caption";
		}
		
		
		
		
		$menu =  $main->add("ul");
		$menu->class = "menu";
		$items = array();
		foreach($this->_item as $k => $item){
			
			$_menu = $menu;
			
			
			if($this->wIcon){
				$item["wIcon"] = true; 
			}
			if($this->wCheck){
				$item["wCheck"] = true; 
			}
			
			
			$items[$k] = $_item = new MenuItem($item);
			
			if($_item->parent !== false){
				
				$parent = $items[$_item->parent];
				
				
				
				
				if(!isset($this->_menu[$_item->parent])){
					
					$parent->createMenu();
					
					$this->_menu[$_item->parent] = true;
					
				}
				$_menu = $parent->getMenu();
				
				
			}
			//hr($k);
			$_menu->appendChild($_item);
			//hr($k, "red");
			
		}
		
		return $main->render();
		
		
	}
	
	public function getScript(){

		$opt = json_encode($this, JSON_PRETTY_PRINT);
		
		return "\nvar menu = new Sevian.Menu($opt);";

		
	}
	
}


?>