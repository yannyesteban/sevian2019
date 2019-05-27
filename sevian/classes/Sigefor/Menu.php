<?php

namespace Sevian\Sigefor;

class Menu extends \Sevian\Panel2 implements \Sevian\DocElement{

	
		public $title = "MENU 5.0";
    
    
    protected $tMenus = "_sg_menus";
    protected $tMenuItems = "_sg_menu_items";
	

		public function getMain(){
			return true;
		}

		public function evalMethod($method = false){
		

			if($method === false){
				$method = $this->method;
			}
			
			//$this->loadForm();
			$this->load();
			
			switch($method){
					
					
				case 'load':
					//$this->main = $this->load();
					break;
				case 'delete':
					break;
				case 'get_field_data':
					break;
					
					
					
			}
			
		}
		public function __construct($opt = array()){
			
			foreach($opt as $k => $v){
				$this->$k = $v;
			}

			$this->main = new \Sevian\HTML('div');

			$this->cn = \Sevian\Connection::get();
		}
    


    private function load(){



			$div = new \Sevian\HTML("div");
			$div->style = "color:white;background:blue;";
			$div->innerHTML = "TEST 27 de MAYO de 2019";

			$this->_main = $div;
		
			return;
		
		$cn = $this->cn;

		$cn->query = "
			SELECT * 
			FROM $this->tMenus 
			WHERE menu = '$this->name'";
        
            
        hr($cn->query);

		$result = $cn->execute();
		
		if($rs = $cn->getDataAssoc($result)){

			foreach($rs as $k => $v){
				$this->$k = $v;
			}
			
		}
		\Sevian\S::setSes("f", 'USA');
		/* leemos el campo params y remplaamos la informacion del objeto */
		$this->_params = \Sevian\S::params($this->params);
		
		if($this->_params){
			
			foreach($this->_params as $k => $v){
				$this->$k = $v;
			}
		}


		$info = $this->getInfoFields($this->query);
		$fields = $info->fields;

		foreach($fields as $k => $v){
			
			$this->fields[$k] = new \Sevian\Sigefor\InfoField($v);
		}

		$q = "
			SELECT * 
			FROM $this->tMenuItemss 
			WHERE form = '$this->name'";

		$result = $cn->execute($q);

		while($rs = $cn->getDataAssoc($result)){
			if(isset($this->fields[$rs['field']])){
				$this->fields[$rs['field']]->update($rs);
			}
			
		
		}

		//print_r($this->fields);
		
	}

	public function renderx(){
		
		global $sevian;
/* 		
		$sevian->setPanelSign($this->panel, "save_cosa", array(
			
			array("vses"=>array("titulo"=>"Principal 2017")),
			array("setPanel"=>array("panel"=>8, "element"=>"menu")),
			array("setPanel"=>array("panel"=>7, "element"=>"procedure"))
		
		));
		 */
		

		$aux = ' {
			async: false,
			panel: 4,
			
			valid: false,
			confirm: \'Seguro\',
			params:{}}
';

		$this->title .= " Panel($this->panel)";
		return "<span style='color:white'>($this->panel) $this->title ... El Menu".'<input type="button" name="submit1" id="submit1" value="Menu" onclick="sevian.send('.$aux.')"></span>';
		
	}
	
}


?>