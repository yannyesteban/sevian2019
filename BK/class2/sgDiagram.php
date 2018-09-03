<?php

class x_sgStructure{
	
	
	public function __construct(){
		
	}// end funciton

	public function setTemplate($template){
		$this->_template = $template;
		
	}// end funciton


	public function setTab(){

	}// end funciton


	public function render(){
	}// end funciton
	public function getScript(){
	}// end funciton
	public function getCss(){
	}// end funciton
	
	
	
	
}// end class

$strInit = array(
	"name"=>"sv",
	"template"=>"<div id='tab'></div><div>--4----8--</div>",
	"title"=>"SEVIAN: 1.0",
	"class"=>"sigefor",
	"mainPanel"=>4,
	"childs"=>array(
		"tabs"=>array(
			array(
				"id"=>"tab",
				"class"=>"sigefor_tab",
				"pages"=>array(
					array(
						"title"=>"Página Principal",
						"class"=>"sigefor",
						"active"=>false,
						"set_panel"=>5,
					),
					array(
						"title"=>"Opciones",
						"class"=>"sigefor",
						"active"=>false,
						"set_panel"=>6,
					),
					array(
						"title"=>"Resultados",
						"class"=>"sigefor",
						"active"=>false,
						"set_panel"=>7,
					),
				),
			),
		),
		"childs"=>array(
			array(
				"type"=>"page",
			),
			array(
				"type"=>"htmlElement",
				"tagName"=>"div",
				"id"=>"perla",
				"class"=>"la_perla",
				"child"=>false,
				"set_panel"=>8,
				
			
			
			),
			array(),
		
		
		),
	),


);





class sgFragmentP{

	public $type = "";	

	private $_propertys = array();

	public function __construct($opt = array()){
		
		foreach($opt as $k => $v){
			$this->$k = $v;	
			
		}
		
	}// end function 

	public function __set($name, $value){
        
        $this->_propertys[$name] = $value;
		
    }// end function

    public function __get($name){

        if(array_key_exists($name, $this->_propertys)) {
            return $this->_propertys[$name];
        }
		return "";
    }// end function
    
	public function __isset($name){
		
        return isset($this->_propertys[$name]);
		
    }// end function
	

	
	public function getXML(){
		$xml = "";
		$xml .= "<fragment type='$this->type'>\n";
		
		foreach($this->_propertys as $k => $v){
			$xml .= "	<$k><![CDATA[$v]]></$k>\n";
		}
		$xml .= "</fragment>\n";		
		
		
		return $xml;
	}// end function
	

}// end class


class sgFragment{

	public $target = "";
	public $html = "";
	public $script ="";	
	public $css ="";	
	public $type_append ="";	
	
	public function __construct($target, $html, $script = false, $css = false, $typeAppend = "1"){
		
		$this->target = $target;
		$this->html = $html;
		$this->script = $script;
		$this->css = $css;
		$this->typeAppend = $typeAppend;
			
		
	}// end function 
	
	public function getXML(){
		$xml = "";
		$xml .= "<fragment type='g'>\n";
		$xml .= "	<target><![CDATA[$this->target]]></target>\n";
		$xml .= "	<html><![CDATA[$this->html]]></html>\n";	
		$xml .= "	<script><![CDATA[$this->script]]></script>\n";	
		$xml .= "	<css><![CDATA[$this->css]]></css>\n";	
		$xml .= "	<type_append><![CDATA[$this->typeAppend]]></type_append>\n";
		$xml .= "</fragment>\n";		
		
		
		return $xml;
	}// end function
	

}// end class


class sgHTMLDiagram{
	
	public $html = "";
	public $css = "";
	public $script = "";
	public $title = "";
	public $onDesing = false;
	
	private $_ele = array();
	
	protected $template = "--4--";
	protected $_panels = false;
	protected $_messages = array();
	
	public function setTemplate($html){
		
		$this->_template = 	$html;
		
		
		
		return $this->getPanels($html);
		
	}// end function
	
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
	
	
	public function appendChild($e){
		return $this->_ele[] = $e;
			
	}
	
	public function render(){

		$template = $this->_template;
		$this->css = "";
		$this->script = "";
		
		foreach($this->_ele as $elem){
			$panel = $elem->panel;
			
			$container = new sgHTML("");
			$container->id = "sg_panel_".$panel;
			
			
			
			
			
			
			
			
			$this->onDesing = true;
			
			$div = new sgHTML("div");
			$div->id = "sg_panel_".$panel;

			if($this->onDesing){
				$div->{"data-design-mode"} =  $elem->design_mode? "1": "0";
			}			
			
			$div->appendChild($elem);



			$container->appendChild($div);
			
			
			if(isset($this->_panels[$panel])){
				
				$template = str_replace("--$panel--", $container->render(), $template);
				
			}else{
				
				$template .= $container->render();
			}
			
			
			
			
			$this->script .= "\n".$elem->script;
			
			if($opt = $elem->getWinProp()){
				
				$this->script .= $this->newPopup($panel, $container->id, $opt);
				
			}
			
			$this->css .= "\n".$elem->css;
			
			


			
		}// next
		
		return $template;
		
	}// end function	
	
	public function setPanel($elem){
		
		$panel = $elem->panel;
		if(!isset($this->_panels[$panel])){
			$this->_template .= "--$panel--";
			
		}
		$this->_panels[$panel] = $elem;
		
	}// end function
	
	public function addMessages($messages = array()){
		
		if(!$messages){
			return;	
		}// end if
		
		$this->_messages[] = $messages ;//array_merge($this->_messages, $messages);
		
	}// end function	

	public function getMessage(){
		
		return $this->_messages;
		
	}// end function	

	
	public function render_(){

		$template = $this->_template;
		$this->css = "";
		$this->script = "";
		
		foreach($this->_ele as $elem){

			if(!isset($elem->html)){
				continue;
			}
			
			
			$panel = $elem->panel;
			
			$this->onDesing = false;
			if($this->onDesing and $elem->design_mode){
				
				$container = new sgHTML("div");
				$container->id = "sg_panel_c$panel";
				$menu = new sgHTML("div");
				$menu->id = "sg_panel_x$panel";
				$menu->innerHTML = "";
				$container->appendChild($menu);
				
			}else{
				
				$container = new sgHTML("");
				$container->id = "sg_panel_".$panel;
				
				
			}
			
			$this->onDesing = true;
			
			$div = new sgHTML("div");
			$div->id = "sg_panel_".$panel;

			if($this->onDesing){
				$div->{"data-design-mode"} =  $elem->design_mode? "1": "0";
			}			
			
			$div->appendChild($elem);

			$container->appendChild($div);
			
			$template = str_replace("--$panel--", $container->render(), $template);
			$this->script .= "\n".$elem->script;
			
			if($opt = $elem->getWinProp()){
				
				$this->script .= $this->newPopup($panel, $container->id, $opt);
				
			}
			
			$this->css .= "\n".$elem->css;
			
			


			$this->addMessages($elem->getMessage());
		}// next
		
		return $template;
		
	}// end function
	
	public function newPopup($panel, $main, $opt){
		
		$json = json_encode($opt);

		return "\n\tsv.setWindow('$panel', '$main', $json);";
		
		
	}// end f
	
	
}//end class



class sgXMLDiagram{
	
	public $xml = "";
	
	public $debug = false;
	
	protected $template = "";
	private $_ele = array();
	
	
	public function setTemplate($html){
		
		$this->_template = 	$html;
		
	}// end function

	public function appendChild($e){
		return $this->_ele[] = $e;
			
	}
	
	public function setPanel($elem){
		
		$panel = $elem->panel;
		$this->_panels[$panel] = $elem;
		
	}// end function
	
	public function render(){
		
		$xml = "";
		
		
		foreach($this->_ele as $elem){
			$panel = $elem->panel;

			$type_append = $elem->render;
			
			
			
			
			$messagex = $elem->getMessage();
			$message="";
			if($messagex){
				$message = $messagex["message"];
			}
			
			$xml_f = "";
			$html = $elem->render();
			$script = $elem->getScript();
			$css = $elem->getCss();
			
			
			$xml .= "	<panels>\n";
			$xml .= "		<panel>$panel</panel>\n";
			$xml .= "		<title><![CDATA[$elem->title]]></title>\n";
			$xml .= "		<target><![CDATA[sg_panel_$panel]]></target>\n";
			$xml .= "		<element><![CDATA[$elem->type]]></element>\n";
			$xml .= "		<name><![CDATA[$elem->name]]></name>\n";
			$xml .= "		<html><![CDATA[$html]]></html>\n";
			$xml .= "		<css><![CDATA[$css]]></css>\n";
			$xml .= "		<script><![CDATA[$script]]></script>\n";
			$xml .= "		<message>$message</message>\n";
			$xml .= "		<debug></debug>\n";
			$xml .= "		$xml_f";
			$xml .= "	</panels>\n";

		}// next
		
		$xml = "<debug><![CDATA[\n$this->debug<]]></debug>\n".$xml;

		return $xml;
		
	}// end function
	
	public function _render(){
		
		$xml = "";
		
		
		foreach($this->_ele as $elem){
			$panel = $elem->panel;

			$type_append = $elem->render;
			
			if($type_append == 0){
				
				$elem->html = "";	
				
			}
			
			
			$messagex = $elem->getMessage();
			$message="";
			if($messagex){
				$message = $messagex["message"];
			}
			
			$xml_f = "";
			$html = $elem->render();
			foreach($elem->fragments as $k => $f){
				$xml_f .= "<fragment>\n";
				$xml_f .= "			<html><![CDATA[$f->html]]></html>\n";	
				$xml_f .= "			<target><![CDATA[$f->target]]></target>\n";
				$xml_f .= "			<type_append><![CDATA[$f->type_append]]></type_append>\n";
				$xml_f .= "		</fragment>";
			}// next				
			
			$xml .= "	<action>\n";
			$xml .= "		<panel>$panel</panel>\n";
			$xml .= "		<title><![CDATA[$elem->title]]></title>\n";
			$xml .= "		<target><![CDATA[sg_panel_$panel]]></target>\n";
			$xml .= "		<element><![CDATA[$elem->type]]></element>\n";
			$xml .= "		<name><![CDATA[$elem->name]]></name>\n";
			$xml .= "		<html><![CDATA[$html]]></html>\n";
			$xml .= "		<css><![CDATA[$elem->css]]></css>\n";
			$xml .= "		<script><![CDATA[$elem->script]]></script>\n";
			$xml .= "		<message>$message</message>\n";
			$xml .= "		<debug></debug>\n";
			$xml .= "		$xml_f";
			$xml .= "	</action>\n";

		}// next
		
		$xml .= "\n	<debug>\n";
		$xml .= "	$this->debug\n";
		$xml .= "	</debug>\n";


		return $xml;
		
	}// end function
}//end class

?>