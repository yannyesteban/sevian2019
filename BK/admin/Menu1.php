<?php



class Menu1 extends Sevian\Panel{
	
	
	
	public function render(){
		
		
		$action = Sevian\Action::Send([
			"async"=>true,
			"panel"=>4,
			"valid"=>false,
			"params"=>[
				[
					"setPanel"=>[
						"panel"=>4,
						"element"=>"menu",
						"name"=>"uno",
						"method"=>"load",
					],
				]
				
			]
			
		]);
		
		$action2 = Sevian\Action::Send([
			"async"=>true,
			"panel"=>4,
			"valid"=>false,
			"params"=>[
				[
					"setPanel"=>[
						"panel"=>4,
						"element"=>"Fragment",
						"name"=>"uno",
						"method"=>"load",
					],
				]
				
			]
			
		]);
		
		$action3 = Sevian\Action::Send([
			"async"=>true,
			"panel"=>4,
			"valid"=>false,
			"params"=>[
				[
					"setPanel"=>[
						"panel"=>4,
						"element"=>"Design",
						"name"=>"uno",
						"method"=>"load",
					],
				]
				
			]
			
		]);
		
	//	echo $action;
		
		$menu = new Sevian\Menu([
			"id"=>"menu22",
			"caption"=>"Menú Secundario",
			"type"=>"default",
			"dinamic"=>false,
			"wIcon"=>true,
			
			//"pullDeltaX"=>15
			
			
		]);
		
		$menu->add([
			"id"=>"item1001",
			"index"=>0,
			"parent"=>false,
			"caption"=>"Option 1",
			"action"=>$action,
			"classImage"=>"sx",
			"events"=>[
				//"click"=>"alert(2);"

			]
			
		]);
		$menu->add([
			"index"=>1,
			"parent"=>false,
			"caption"=>"Option 2",
			"action"=>$action2,
		]);
		$menu->add([
			"index"=>2,
			"parent"=>false,
			"caption"=>"Design Menu",
			"action"=>$action3,
			"icon"=>"../../Iconos/edit.png",
		]);
		$menu->add([
			"index"=>3,
			"parent"=>false,
			"caption"=>"Option 4"
		]);
		
		$menu->add([
			"index"=>4,
			"parent"=>3,
			"caption"=>"Option 41"
		]);
		$menu->add([
			"index"=>5,
			"parent"=>3,
			"caption"=>"Option 42"
		]);

		$menu->add([
			"index"=>6,
			"parent"=>3,
			"caption"=>"Option 43"
		]);
		$this->html = $menu->render();
		
		$this->script = $menu->getScript();
		
		$btn = new Sevian\HTML("input");
		$btn->type = "button";
		$btn->value = "OK";
		$btn->onclick = "menu.setCaption('Menu X1000')";
		
		
		
		return $this->html.$btn->render();
		
	}
	
	
}


?>