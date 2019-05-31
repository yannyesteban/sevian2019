<?php

namespace Sevian;
class InfoMenuItem{

    public $caption = false;
    public $className = false;
    public $classImage = false;
    public $icon = false;
    public $mode = 'close';
    public $checked = false;
    public $disabled = false;

    public $url = false;
    public $urlTarget = false;
    
    public $pull = false;
    
    public $pullX = "front";
    public $pullY = "top";

    public $pullDeltaX = 20;
    public $pullDeltaY = 20;
    
    
    public $type = "default";
    
    public $onOpen = false;
    public $onClose = false;
    
    
    public $wCheck = false;
    public $wIcon = false;
    public $useButton = false;

	
	public function __construct($opt = []){
		foreach($opt as $k => $v){

            if($k == 'class'){
                $k = 'className';
            }
            
			$this->$k = $v;
		}
	}
}
class InfoMenu{

    public $id = false;
    public $target = false;
	public $type = 'normal';
	public $mode = 'default';
	public $caption = false;
	public $wCheck = false;
	public $wIcon = false;
	public $useButton = false;
	public $value = false;
    public $class = false;
    public $oncheck = false;

    public $items = [];
	
	public function __construct($opt = []){
		foreach($opt as $k => $v){

            if($k == 'class'){
                $k = 'classname';
            }

            if($k== 'items'){
                foreach($v as $item){
                    $this->items[] = new InfoMenuItem($item);
                }
                
                
            }else{
                $this->$k = $v;
            }


			
		}
	}
}

class Menu extends HTML{
    public $infoMenu = false;
    public $tagName = "div";
    public $caption = "";
    public $items = [];
    public $target = false;

    public function __construct($opt = ""){

        $this->infoMenu = new InfoMenu($opt);

        if($this->infoMenu->target){
            $this->id = $this->infoMenu->target;
        }
    }

    public function setCaption($caption){
        $this->caption = $caption;
    }

    public function add($item){
        if($item instanceof \Sevian\InfoMenuItem){
            $this->infoMenu->items[] = $item;
        }else{
            $this->infoMenu->items[] = \Sevian\InfoMenuItem($item);
        }
    }

    public function getScript(){
        $json = json_encode($this->infoMenu);
        $this->script ="var mmenu = new Sevian.Menu($json)";
       
        $script = parent::getScript();
        return $script;
    }
}
?>