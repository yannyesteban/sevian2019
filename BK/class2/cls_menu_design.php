<?php
require_once("cls_menu.php");
class cls_menu_design extends cls_menu{
	//public $objPanelName = "sgForm";
	
	
	public $class_d_caption = "__menud_caption";
	public $class_d_menu = "__menud_menu";
	public $class_d_title = "__menud_title";
	public $class_d_header = "__menud_header";
	public $class_d_page = "__menud_page";
	public $class_d_input = "__menud_input";
	
	public function extraMethod($method){
		
		global $seq;
		
		
		$this->t_procedures = TABLE_PREFIX."procedures";
		$this->t_sequences = TABLE_PREFIX."sequences";	
		

		$ref = $this->getRef();

		$this->elemMethodId = "__mds_ele_method_".$this->panel;
		$this->divItemId = "__mds_div_item_".$this->panel;
		$this->divActionId = "__mds_div_action_".$this->panel;

		if($this->name != ""){
			$menu = $this->name;
			
		}elseif(isset($this->vparams["new_menu"])){
			$menu = $this->vparams["new_menu"];
			
		}elseif($seq->getReq("new_menu")){
			$menu = $seq->getReq("new_menu");
			
		}else{
			$menu = "";	
		}
		
		if(isset($this->eparams["method_ref"])){
			$method_ref = $this->eparams["method_ref"];
		}else{
			$method_ref = "create";
		}// end if			
		//$this->name = $menu;


		$this->ele_method = "";
		if(isset($this->eparams["ele_method"])){
			$this->ele_method = $this->eparams["ele_method"];
		}// end if
		

		switch ($method){
		case "_new":

			if(isset($this->eparams["element_name"]) and $this->eparams["element_name"]){
				$name = $this->eparams["element_name"];
			}else{
				$name = "";
			}// end if	
			
			

			$this->name = $name = $this->newElement($name);
			
			$this->setVarDesign();
			
			$this->execute($name, "");
			$this->design();			
			$this->render = 1;
			//$this->name = $name;
			break;			

		case "design":
		
			$this->setVarDesign();
		
			$this->execute($this->name, "");
			$this->design();
			$this->render = 1;
			break;
		case "menu_save":

			$this->menuSave($seq->getReq("__menu_title"), $seq->getReq("__menu_code"));
			$this->render = 0;
			break;
		case "action_eparams":
			$this->actionEparams($this->eparams["config_panel"]);
			$this->render = 0;				
			break;
		case "new_element":

			$this->newElement();
			$this->render = 0;
			break;
		case "new_item":
			$this->newItem();
			$this->render = 0;
			break;
		case "new_action":
			$this->newAction();
			$this->render = 0;
			break;
		case "add_action":

			$this->render = 0;
			$action_name = $this->addAction();
			$this->script .= $this->scriptActions();
			$this->script .= "\n\t$ref.m.e.__actions.data = _DD;"; 
			$this->script .= "\n\t$ref.m.e.__actions.setValue('$action_name');_DD.unshift(['','',''])"; 
			$this->script .= "\n\t$ref.e._list_action.setData(_DD, $ref.e._list_action.getValue());"; 
			$this->script .= "\n\t$ref.m.updateAction();"; 
			$this->script .= "\n\tif($ref.e.i_action){"; 
			$this->script .= "\n\t\t$ref.e.i_action.setData(_DD, $ref.e.i_action.getValue());"; 
			$this->script .= "\n\t}"; 

			break;
		case "del_action":
			if(isset($this->eparams["action"])){
				$action = $this->eparams["action"]; 
				$this->delAction($action);
				$this->render = 0;
			}// end if			
			break;
		case "title_action":
			if(isset($this->eparams["action"])){
				$action = $this->eparams["action"]; 
				$title = $this->eparams["title"]; 
				$this->titleAction($action, $title);
				
			}// end if
			$this->render = 0;	
			break;	
		case "lnk_action":
			if(isset($this->eparams["action_name"])){
				$action = $this->eparams["action_name"]; 
				$lnk = $this->eparams["__menu_link"]; 
				$this->lnkAction($action, $lnk);
				
			}// end if
			$this->render = 0;	
			break;	
		case "title_menu":
			$this->titleMenu($seq->getReq("__menu_title")); 
			$this->render = 0;
			break;		
		case "order_menu":
			if($menu_actions = $seq->getReq("__menu_actions")){
				$this->orderMenu($menu_actions);
				$this->render = 0;
			}// end if		
			break;

		case "set_property":
		
			if(isset($this->eparams["property"])){
				$property = $this->eparams["property"];
				if(isset($this->eparams["input_value"])){
					
					$value = $seq->getReq($this->eparams["input_value"]);
				}else{
					
					$value = $this->eparams["value"];
				}
				
				$this->setProperty($property, $value);

				if($property == "title"){
					
					
					$this->setFragmentP(array(
						"type"=> "f",
						"panel"=> $this->panel,
						"target"=> "d_title",
						"property"=> "value",
						"value"=> $value)
					);	
				}
				
			}// end if
			$this->render = 0;
			break;


		case "save_menu":
				
			if($this->name==""){
				
				$this->message = "ERROR IN SAVE";
				
			}else{
		
				$data = &$seq->cmd->v->req;
				$data["d___form_mode"] = 4;
				$data["d___form_record"] = "menu=$this->name";
			
				$this->saveRequest($this->t_menus, $data, "d_");
			}// end if
			$this->render = 0;
			break;
		case "delete_menu":
			if(isset($this->eparams["menu_name"])){
				$menu_name = $this->eparams["menu_name"];
				$data = &$seq->cmd->v->req;
				
				$data["d___form_mode"] = 3;
				$data["d___form_record"] = "menu=$menu_name";
						
				$this->saveRequest($this->t_menus, $data, "d_");


			}// end if					
			$this->render = 0;
			break;			
			
		case "load_items":
			$this->execute($menu, "");
			$this->loadItems();
			$this->render = 0;
			break;	
			
			
		case "load_ele_method":
			$this->loadEleMethod($this->ele_method);
			$this->render = 0;
			break;
		case "load_item":
			if(isset($this->eparams["item_id"])){
				$this->item_id = $this->eparams["item_id"];
				$this->loadItem($this->item_id);
			}// end if
		
			
			$this->render = 0;
			break;


		case "load_action":
		
		
			if(isset($this->eparams["action_name"])){

				$this->action_name = $this->eparams["action_name"];
				$this->loadAction($this->action_name);
			}// end if	
		
		
			
			$this->render = 0;
			break;

		case "save_ele_method":
		
			$data = &$seq->cmd->v->req;
			//$data["__form_mode"] = $data["m___form_mode"];
			//$data["__form_record"] = $data["m___form_record"];
					

			$this->saveRequest($this->t_ele_met, $data, "m_");
			$ele_method = $data["m_method"];
			$this->loadEleMethod($ele_method);
			
			$this->script .= $this->scriptMethods();
			$this->script .= "\n\t_DD.unshift(['','','']);";
			$this->script .= "\n\t$ref.e._list_method.setData(_DD, '$ele_method');"; 
			$this->setFragment("","",$this->script);
			$this->render = 0;
			break;
		case "delete_ele_method":
			$this->ele_method = "";
			if(isset($this->eparams["ele_method"])){
				$this->ele_method = $this->eparams["ele_method"];
			}// end if		
			$data = &$seq->cmd->v->req;
			
			$data["m___form_mode"] = 3;
			$data["m___form_record"] = "method=$this->ele_method,name=$this->name,element=$this->element";
					
			$this->saveRequest($this->t_ele_met, $data, "m_");
			$this->loadEleMethod($this->ele_method);
			
			$this->script .= $this->scriptMethods();
			$this->script .= "\n\t_DD.unshift(['','','']);";
			$this->script .= "\n\t$ref.e._list_method.setData(_DD, '');"; 
			$this->setFragment("","",$this->script);
			$this->render = 0;
			break;
		case "save_item":

			if(isset($this->eparams["item_id"])){
				$item_id = $this->eparams["item_id"];
				$data = &$seq->cmd->v->req;
				$data["i___form_mode"] = 4;
				$data["i___form_record"] = $data["i___form_record"];
			
				$r = $this->saveRequest($this->t_men_itm, $data, "i_");
				
				
				$this->script .= $this->scriptItems();
				
				$this->script .= "\n\t_DD.unshift(['','','']);"; 
				$this->script .= "\n\t\t$ref.e._list_item.setData(_DD, '$item_id');"; 
			 	$this->setFragment("","",$this->script);			
				$this->loadItem($item_id);
				
				//$this->execute($menu, "");
				//$this->loadItems();
				$this->render = 0;
			}// end if		
		
			break;

		case "save_action":

			if(isset($this->eparams["action_name"])){
				
				$data = &$seq->cmd->v->req;
				
				$action_name = $data["a_action"];
				
				$data["a___form_mode"] = 4;
				$data["a___form_record"] = $data["a___form_record"];
			
				$this->saveRequest($this->t_actions, $data, "a_");
				
				
				
				//$this->script .= "\n\t$ref.m.e.__actions.data = _DD;"; 
				//$this->script .= "\n\t$ref.m.e.__actions.setValue('$action_name');"; 
				
				$this->execute($menu, "");
				$this->loadItems();
				
				$this->script .= $this->scriptActions();
				$this->script .= "\n\t_DD.unshift(['','','']);";
				$this->script .= "\n\t$ref.e._list_action.setData(_DD, '$action_name');"; 
				$this->script .= "\n\tif($ref.e.i_action){"; 
				$this->script .= "\n\t\t$ref.e.i_action.setData(_DD, $ref.e.i_action.getValue());"; 
				$this->script .= "\n\t}"; 				
				$this->loadAction($action_name);
				$this->render = 0;

			}// end if		
		
			break;
		case "delete_action":

			if(isset($this->eparams["action_name"])){
				
				$data = &$seq->cmd->v->req;
				
				$action_name = $this->eparams["action_name"];
				
				$data["a___form_mode"] = 3;
				$data["a___form_record"] = "action=$action_name";
			
				$this->saveRequest($this->t_actions, $data, "a_");
				
				
				
				//$this->script .= "\n\t$ref.m.e.__actions.data = _DD;"; 
				//$this->script .= "\n\t$ref.m.e.__actions.setValue('$action_name');"; 
				
				$this->execute($menu, "");
				$this->loadItems();
				
				$this->script .= $this->scriptActions();
				$this->script .= "\n\t_DD.unshift(['','','']);";
				$this->script .= "\n\t$ref.e._list_action.setData(_DD, '$action_name');"; 
				$this->script .= "\n\tif($ref.e.i_action){"; 
				$this->script .= "\n\t\t$ref.e.i_action.setData(_DD, $ref.e.i_action.getValue());"; 
				$this->script .= "\n\t}"; 	
				
				$this->setFragment("","",$this->script);
							
				$this->loadAction($action_name);
				$this->render = 0;

			}// end if		
		
			break;			
			
		}// end switch
	}// end function	

	private function design($method_ref = "load"){
		
		$f = $this->f = new svForm(array("name"=>"sgpanel_".$this->panel));
		$f->setRef($this->getRef());
		$f->setClasses("mds");
		$f->setCaption("== Design Menu: $this->name ==");
		
		
		$f->addMenuPage("1");


		$data = $this->inputData("SELECT menu, menu FROM $this->t_menus ORDER BY menu");
	
		$input = $f->addField("select", "_list", "Menus", $this->name, $data);
		$input->input->propertys["onchange"] = svAction::setPanel(
			array(
				"async"=>true,
				"panel"=>$this->panel,
				"params"=>"panel:$this->panel;element:$this->element;method:design;name:{=_list};"
			));

		
		$item = $f->addField("button", "","", "");
		$item->input->propertys["data-fds_menu_type"] = "delete";
		$item->input->propertys["onclick"] = svAction::send(
			array(
				"async"=>true,
				"panel"=>$this->panel,
				"params"=>"set_params:'menu_name:{=_list};';imethod:delete_menu;set_panel:'panel:$this->panel;element:$this->element;method:design;name:;';"
			));
		
		$field = $f->addField("text", "_element_name");
		$field->input->propertys["placeholder"] = "...new element";

		$item = $f->addField("button", "","", "");
		$item->input->propertys["data-fds_menu_type"] = "new";
		$item->input->propertys["onclick"] = svAction::send(
			array(
				"async"=>true,
				"panel"=>$this->panel,
				"params"=>"set_panel:'panel:$this->panel;element:$this->element;method:_new;name:{=_element_name};element_name:{=_element_name};';"
			));


		if($this->name){
			
			$item = $f->addField("button", "","", "");
			$item->input->propertys["data-fds_menu_type"] = "render";
			$item->input->propertys["onclick"] = svAction::setPanel(
				array(
					"async"=>true,
					"panel"=>$this->panel,
					"params"=>"panel:$this->panel;element:$this->element;method:$method_ref;name:$this->name;"
				));

			$item = $f->addField("button", "","", "");
			$item->input->propertys["data-fds_menu_type"] = "save";
			$item->input->propertys["onclick"] = svAction::send(
				array(
					"async"=>true,
					"panel"=>$this->panel,
					"params"=>"imethod:save_menu;set_panel:'panel:$this->panel;element:$this->element;method:design;name:$this->name;';"
				));

			$item = $f->addField("button", "","", "");
			$item->input->propertys["data-fds_menu_type"] = "reload";
			$item->input->propertys["onclick"] = svAction::setPanel(
				array(
					"async"=>true,
					"panel"=>$this->panel,
					"params"=>"panel:$this->panel;element:$this->element;method:design;name:$this->name;"
				));
			
			
			$f->setMain();
			$f->addPage("1", false);
	
	
			$field = $f->addField("text", "_d_name", "Name", $this->name);
			$field->input->propertys["onchange"] = svAction::send(
				array(
					"async"=>true,
					"panel"=>$this->panel,
					"params"=>"set_params:'property:menu;input_value:_d_name;';
					imethod:set_property;
					set_panel:'panel:$this->panel;element:$this->element;method:design;name:{=_d_name};';"
				));
	
			$field = $f->addField("text", "_d_title", "Title", $this->title);
			$field->input->propertys["onchange"] = svAction::send(
				array(
					"async"=>true,
					"panel"=>$this->panel,
					"params"=>"set_params:'input_value:_d_title;property:title;';imethod:set_property;"
				));


		
			$tabMain = $f->addTab("tab_mnu_d_".$this->panel);
			
			$this->_tabItems();
			$this->_tabBasic();
			$this->_tabParams();
			$this->_tabMethod();
			$this->_tabItem();
			$this->_tabAction();
		}// end if
		$this->css = $f->css;
		$this->html = $f->render();
		$this->script .= $f->getScript()/*.$menu_script*/;

	}// end function

	private function getMenuNextId(){
		$cn = &$this->cns;
		return "menu_".$cn->serialName($this->t_menus, "menu_", "menu");
	}// end funtion 
	
	private function getActionNextId(){
		$cn = &$this->cns;
		return "action_".$cn->serialName("$this->t_actions", "action_", "action");
	}// end funtion 
	
	private function menuSave($caption, $code){
		global $seq;
		
		
		$this->titleMenu($caption);
		$cn = &$this->cns;
		$aux = explode(";", $code);
		$iOrder = array();
		$items = array();
		foreach($aux as $k => $v){
		
			$aux2 = explode(":", $v);
			$iOrder[]=$aux2[0];
			
			$items[]=array("id"=>$aux2[0], "parent_id"=>$aux2[1], "title"=>$cn->addSlashes(urldecode($aux2[2])), "mode"=>$aux2[3], "action"=>$aux2[4]);

		}
		$order = 10;
		$cn->query = "DELETE FROM $this->t_men_itm WHERE menu='$this->name' AND id NOT IN (".implode(",", $iOrder).")";
		$result = $cn->execute();
		//echo $cn->query."<br>";
		foreach($items as $k => $v){
			
			$action = ($v["action"]!="")?"'$v[action]'":"null";
			switch($v["mode"]){
			case 1:
				$cn->query = "
					INSERT INTO $this->t_men_itm (menu, id, parent_id, title, $this->t_men_itm.order, action)
					VALUES ('$this->name', $v[id], $v[parent_id], '$v[title]', $order, $action)
				
				";
				$result = $cn->execute();
				//echo $cn->query."<br>";
				$order += 10;
			
				break;	
			case 2:
				$cn->query = "
					UPDATE $this->t_men_itm SET parent_id=$v[parent_id], title='$v[title]', $this->t_men_itm.order=$order,
					action=$action
					WHERE menu = '$this->name' AND id=$v[id]
				";
				$result = $cn->execute();
				//echo $cn->query."<br>";
				$order += 10;
			
				break;
			default:
				//echo"XXXX<br>";
						
				
			}// end switch

			$order += 10;
			
		}
		
		
		
		//print_r($iOrder);
		//echo implode(",", $iOrder);
		//exit;
		return;
		

	}// end funtion 

	private function newElement($name=""){
		global $seq;
		if($name == ""){
			$name = $this->getMenuNextId();
		}
		
		$title = ucwords(implode(" ",explode("_", $name)));
		
		$cn = &$this->cns;
		$cn->query = "INSERT INTO $this->t_menus (menu, title) VALUES ('$name','$title');";
		$this->debug = $cn->query;
		$result = $cn->execute();

		$this->vparams["new_menu"] = $name;
		$seq->setReq("new_menu", $name);
		
		return $this->name = $name;
	}// end funtion 

	private function newItem(){
		global $seq;
		
		$cn = &$this->cns;
		$cn->query = "INSERT INTO $this->t_men_itm (`menu`, `title`, `order`) VALUES ('$this->name','nada',10);";
		$this->debug = $cn->query;
		$result = $cn->execute();


		//$this->vparams["new_action"] = $name;
		//$seq->setReq("new_action", $name);
	}// end funtion 
	
	private function newAction($name=""){
		global $seq;
		if($name == ""){
			$name = $this->getActionNextId();
		}
		
		$title = ucwords(implode(" ",explode("_", $name)));
		
		$cn = &$this->cns;
		$cn->query = "INSERT INTO $this->t_actions (`action`, `title`) VALUES ('$name','$title');";
		$this->debug = $cn->query;
		$result = $cn->execute();

		$cn->query = "INSERT INTO $this->t_men_act (`menu`, `action`, `order`) VALUES ('$this->name','$name',10);";
		$this->debug = $cn->query;
		$result = $cn->execute();


		$this->vparams["new_action"] = $name;
		$seq->setReq("new_action", $name);
	}// end funtion 
	
	private function addAction($name = ""){
		global $seq;
		if($name == ""){
			$name = $this->getActionNextId();
		}
		
		$title = ucwords(implode(" ",explode("_", $name)));
		
		$cn = &$this->cns;
		$cn->query = "INSERT INTO $this->t_actions (`action`, `title`) VALUES ('$name','$title');";
		$this->debug = $cn->query;
		$result = $cn->execute();
		return $name;

	}// end funtion 
	
	private function delAction($name){
		global $seq;
		$cn = &$this->cns;
		$cn->query = "DELETE FROM $this->t_men_act WHERE `menu`='$this->name' AND `action`='$name';";
		$result = $cn->execute();
	}// end funtion 
	
	private function orderMenu($actions){
		global $seq;
		$cn = &$this->cns;
		
		$a = explode(",", $actions);
		$step = 10;
		foreach($a as $k => $name){
			
			$cn->query = "UPDATE $this->t_men_act SET order=$step WHERE menu='$this->name' AND action='$name';";
			$result = $cn->execute();
			$step += 10;
			
		}// next
	}// end funtion 
	
	private function titleMenu($caption){
		
		global $seq;
		$cn = &$this->cns;
		$caption = $cn->addSlashes(urldecode($caption));
		$cn->query = "UPDATE $this->t_menus SET `title`='$caption' WHERE `menu`='$this->name';";
		//echo($cn->query);
		$result = $cn->execute();
	}// end funtion 
	
	private function titleAction($name, $caption){
		
		global $seq;
		$cn = &$this->cns;
		$caption = $cn->addSlashes(urldecode($caption));
		$cn->query = "UPDATE $this->t_actions SET `title`='$caption' WHERE `action`='$name';";
		//echo($cn->query);
		$result = $cn->execute();
	}// end funtion 

	private function lnkAction($name, $eparams){
		
		global $seq;
		
		//echo $eparams; 
		$p = $seq->cmd->get_param(urldecode($eparams));
		
		
		
		$panel = $p["panel"];
		
		$cn = &$this->cns;
		$eparams = $cn->addSlashes(urldecode($eparams));
		$cn->query = "UPDATE $this->t_actions SET `eparams`='$eparams', `panel`='$panel' WHERE `action`='$name';";
		
		$result = $cn->execute();
	}// end funtion 

	private function _tabItems(){
		
		$f = $this->f;
		$tabMain = $f->getTab();
		$tabPage = $f->addTabPage("Items");
		
		
		$f->addMenuPage("items");
		
		$ref = $this->getRef();
		$ref_menu = "$ref.m";	
		
		$item = $f->addField("button", "","", "");
		$item->input->propertys["data-fds_menu_type"] = "save";
		$item->input->propertys["onclick"] = "this.form.__menu_code.value = $ref_menu.getData();".svAction::send(
			array(
				"async"=>true,
				"panel"=>$this->panel,
				"params"=>"imethod:menu_save;set_panel:'panel:$this->panel;element:$this->element;method:design;name:$this->name;ielement:$this->element;imethod:menu_save;';"
			));

		$item = $f->addField("button", "","", "");
		$item->input->propertys["data-fds_menu_type"] = "reload";
		$item->input->propertys["onclick"] = svAction::setPanel(
			array(
				"async"=>true,
				"panel"=>$this->panel,
				"params"=>"panel:$this->panel;element:$this->element;method:load_items;name:$this->name;"
			));

		$field = $f->addField("hidden", "__menu_code", "__menu_code");
		$field = $f->addField("hidden", "__menu_action", "__menu_action");
		$field = $f->addField("hidden", "__menu_link", "__menu_link");
		
		$f->setTabPage($tabMain, 1);
		
		$div = new sgHTML("div");
		$div->id = "__menu_design_".$this->panel;
		$div->class = "__menu_design_cont";
		
		$f->appendChild($div);
		
		$opt = json_encode(array(
			"name"=>$this->name,
			"targetId"=>$div->id,
			"id"=>"md_{$this->name}_{$this->panel}",
			"panel"=>$this->panel
		));
		
		$menu_script = "\n\t$ref_menu = new menuDesign($opt);";		
		$menu_script .= "\n\t$ref_menu.title = '$this->title';"; 
		$menu_script .= "\n\t$ref_menu.panel = '$this->panel';"; 

		foreach($this->action as $k => $v){
			$v->i_title = addslashes($v->i_title);
			$v->a_title = addslashes($v->a_title);
			$menu_script .= "\n\t$ref_menu.data.push({id:'$v->id',parent_id:'$v->parent_id',i_title:'$v->i_title',mode:2,action:'$v->action', a_title:'$v->a_title'})"; 
		}

		$menu_script .= "\n\t$ref_menu.e = new Array();";
		$menu_script .= $this->scriptActions();
		$menu_script .= "\n\t$ref_menu.dataActions = _DD;"; 

		$funcBody = svAction::send(
			array(
				"async"=>true,
				"panel"=>$this->panel,
				"params"=>"set_method:'panel:$this->panel;element:$this->element;method:load_item;name:$this->name;item_id:{=__menu_action};';"
			)).";";
		
		$funcBody .= $tabMain->funcShow(4);
		
		$menu_script .= "\n\t$ref_menu.funcItemEdit  = function(id){return function(){ 
			this.form.__menu_action.value = id;this.form._list_item.value = id;$funcBody }};"; 

		$funcBody = svAction::send(
			array(
				"async"=>true,
				"panel"=>$this->panel,
				"params"=>"set_method:'panel:$this->panel;element:$this->element;method:load_action;name:$this->name;action_name:{=__menu_action};';"
			)).";";		
		
		$funcBody .= $tabMain->funcShow(5);
		$menu_script .= "\n\t$ref_menu.funcActionEdit  = function(action){return function(){ 
			this.form.__menu_action.value = action;this.form._list_action.value = action;$funcBody }};"; 

		$funcBody = svAction::setPanel(
			array(
				"async"=>true,
				"panel"=>$this->panel,
				"params"=>"panel:$this->panel;element:$this->element;method:lnk_action;name:$this->name;action_name:{=__menu_action};__menu_link:{=__menu_link};"
				
				
			)).";";	
		
		$menu_script .= "\n\t$ref_menu.funcEparam  = function(action){return function(){ 
		this.form.__menu_action.value = action;this.form.__menu_link.value = escape(__CONFIG_PANEL_STR);
		$funcBody }};"; 
		
		$funcBody = svAction::setPanel(
			array(
				"async"=>true,
				"panel"=>$this->panel,
				"params"=>"panel:$this->panel;element:$this->element;method:add_action;name:$this->name;"
			)).";";
		
		$menu_script .= "\n\t$ref_menu.funcNewAction  = function(){return function(){ $funcBody }};"; 
		$menu_script .= "\n\t$ref_menu.init();"; 
		
		$f->setScript($menu_script);

	}// end function

	private function _tabBasic(){
		$f = $this->f;
		
		$f->addTabPage("Basic");
		$f->addPage("4", "");

		$data[] = array("accordion", "accordion", false);
		$data[] = array("horizontal", "horizontal", false);
		$data[] = array("vertical", "vertical", false);
		$data[] = array("submit", "submit", false);

		$list = $this->inputData("SELECT template, template FROM $this->t_templates ORDER BY template");

		$field = $f->addField("text", "d_menu", "menu", $this->name);
		$field = $f->addField("text", "d_title", "title", $this->title);
		$field = $f->addField("select", "d_menu_type", "d_menu_type", $this->menu_type, $data);
		$field = $f->addField("text", "d_class", "class", $this->class);
		$field = $f->addField("select", "d_template", "template", $this->template, $list);
		$field = $f->addField("textarea", "d_expressions", "expressions", $this->expressions);
		$field = $f->addField("textarea", "d_signs", "signs", $this->signs);
		$field = $f->addField("textarea", "d_eval_signs", "eval_signs", $this->eval_signs);
		
	}// end function

	private function _tabParams(){
		$f = $this->f;
		$f->addTabPage("Params");
		$f->addPage("5", "");
		//$f->addTable("6", "");		
		
		$field = $f->addField("params", "d_params", "");
		
		
		
		$list1 ='{"":"", "y":"y", "n":"n"}';
		$list2 ='{"":"", "accordion":"accordion", "horizontal":"horizontal", "vertical":"vertical", "submit":"submit"}';
		$list3 = $this->inputDataJSON("SELECT template, template FROM $this->t_templates ORDER BY template");
		
		
		$field->config["classC3"] = "'$this->class_d_page'"; 
		$field->panel = $this->panel;
		$params["ele_params"] = json_decode('[
			{"name":"title","title":"title","type":"t","value":""},
			{"name":"menu_type","title":"menu_type","type":"s","value":"", "data": '.$list2.'},
			{"name":"untitled","title":"untitled","type":"s","value":"", "data": '.$list1.'},
			{"name":"hidden","title":"hidden","type":"s","value":"", "data": '.$list1.'},
			{"name":"class","title":"class","type":"t","value":""},
			{"name":"template","title":"template","type":"s","value":"", "data": '.$list3.'},
			{"name":"class_main","title":"class_main","type":"t","value":""},
			{"name":"class_title","title":"class_title","type":"t","value":""},
			{"name":"class_page","title":"class_page","type":"t","value":""},
			{"name":"class_item","title":"class_item","type":"t","value":""},
			{"name":"class_item_active","title":"class_item_active","type":"t","value":""},
			{"name":"class_item_disabled","title":"class_item_disabled","type":"t","value":""},
			{"name":"class_item_menu","title":"class_item_menu","type":"t","value":""},
			{"name":"class_item_image","title":"class_item_image","type":"t","value":""},
			{"name":"class_show","title":"class_show","type":"t","value":""},
			{"name":"class_hide","title":"class_hide","type":"t","value":""},
			{"name":"class_item_menu","title":"class_item_menu","type":"t","value":""},
			
			{"name":"style_main","title":"style_main","type":"b","value":""},
			{"name":"style_title","title":"style_title","type":"b","value":""},
			{"name":"style_page","title":"style_page","type":"b","value":""},
			{"name":"style_item","title":"style_item","type":"b","value":""}]');
			
		
			
		$field->input->setParams($params);
		
			
		$field->input->id = $field->input->name."_".$this->panel;
		$field->input->value = $this->params;
		$field->input->class = "mitext";
		$field->input->mode = 1;
				
	}// end function

	private function _tabMethod(){
		$f = $this->f;
		
		$f->addTabPage("Method");
		$f->addMenuPage("method");

		$data = $this->inputData("SELECT method, method FROM $this->t_ele_met WHERE name = '$this->name' AND element = '$this->element'");
	
		$field = $f->addField("select", "_list_method", "Action", "", $data);
		$field->input->propertys["onchange"] = svAction::send(
			array(
				"async"=>true,
				"panel"=>$this->panel,
				"params"=>"set_method:'panel:$this->panel;element:$this->element;name:$this->name;method:load_ele_method;ele_method:{=_list_method};';"
				));
		
		$item = $f->addField("button", "","", "");
		$item->input->propertys["data-fds_menu_type"] = "delete";
		$item->input->propertys["onclick"] = svAction::send(
			array(
				"async"=>true,
				"panel"=>$this->panel,
				"params"=>"set_params:'ele_method:{=_list_method};';imethod:delete_ele_method;"
			));

		$item = $f->addField("button", "","", "");
		$item->input->propertys["data-fds_menu_type"] = "reload";
		$item->input->propertys["onclick"] = $field->input->propertys["onchange"];
		
		$input = $f->addField("text", "__ele_method", "");
		$input->value = $this->ele_method;	
		$input->input->propertys["placeholder"] = "...method";
		
		$item = $f->addField("button", "","", "");
		$item->input->propertys["data-fds_menu_type"] = "go";
		$item->input->propertys["onclick"] = svAction::send(
			array(
				"async"=>true,
				"panel"=>$this->panel,
				"params"=>"set_method:'panel:$this->panel;element:$this->element;method:load_ele_method;ele_method:{=__ele_method};name:$this->name;';"
			));
			
		$item = $f->addField("button", "","", "");
		$item->input->propertys["data-fds_menu_type"] = "save";
		$item->input->propertys["onclick"] = svAction::send(
			array(
				"async"=>true,
				"panel"=>$this->panel,
				"params"=>"set_params:'ele_method:{=__ele_method};';imethod:save_ele_method;"
			));			
		
		$f->setTabPage($f->getTab(), 4);
		
		$div = new sgHTML("div");
		$div->id = $this->elemMethodId;
		
		$f->appendChild($div);
		
	}// end function

	private function _tabItem(){
		$f = $this->f;
		$f->addTabPage("Item");
		
		$f->addMenuPage("item");
		
		//$divMenu = new sgHTML("div");
		//$divMenu->class = $this->class_d_menu;			
		//$f->appendChild($divMenu);	
		
		
		//$f->addPage("7", "");
		//$f->addTable("8", "");		

		$concat = $this->cns->concat("id","' : '", "title");

		$order = $this->cns->addQuotes("order");
		
		$data = $this->inputData("SELECT id, $concat as title FROM $this->t_men_itm WHERE menu = '$this->name' ORDER BY $order");
	
		$input = $f->addField("select", "_list_item", "Item", "", $data);
		$input->input->propertys["onchange"] = svAction::send(
			array(
				"async"=>true,
				"panel"=>$this->panel,
				"params"=>"set_method:'panel:$this->panel;element:$this->element;method:load_item;name:$this->name;item_id:{=_list_item};';"
				));

		$item = $f->addField("button", "","", "");
		$item->input->propertys["data-fds_menu_type"] = "reload";
		$item->input->propertys["onclick"] = $input->input->propertys["onchange"];

		$item = $f->addField("button", "","", "");
		$item->input->propertys["data-fds_menu_type"] = "save";
		$item->input->propertys["onclick"] = svAction::send(
			array(
				"async"=>true,
				"panel"=>$this->panel,
				"params"=>"set_params:'item_id:{=_list_item};';imethod:save_item;"
			));

		$f->setTabPage($f->getTab(), 5);
		$div8 = new sgHTML("div");
		$div8->id = $this->divItemId;
		
		
		$f->appendChild($div8);		
	}// end function

	private function _tabAction(){
		$f = $this->f;
		$f->addTabPage("Action");
		
		$f->addMenuPage("action");
		
		//$divMenu = new sgHTML("div");
		//$divMenu->class = $this->class_d_menu;			
		//$f->appendChild($divMenu);
		
		//$f->addPage("9", "");
		//$f->addTable("9", "");		
		
		
		$data = $this->inputData("SELECT action, action FROM $this->t_actions ORDER BY action");
	
		$field = $f->addField("select", "_list_action", "Action", "", $data);
		//$field->propertys["onchange"] = svAction::event("set_panel_x", $this->panel, "panel:$this->panel;element:$this->element;method:load_action;name:$this->name;action_name:{=_list_action};");


		$field->input->propertys["onchange"] = svAction::send(
			array(
				"async"=>true,
				"panel"=>$this->panel,
				"params"=>"set_method:'panel:$this->panel;element:$this->element;method:load_action;name:$this->name;action_name:{=_list_action};';"
			));
		
		


		$item = $f->addField("button", "","", "");
		$item->input->propertys["data-fds_menu_type"] = "delete";
		$item->input->propertys["onclick"] = svAction::send(
			array(
				"async"=>true,
				"panel"=>$this->panel,
				"params"=>"set_params:'action_name:{=_list_action};';imethod:delete_action;"
			));
		
		$item = $f->addField("button", "","", "");
		$item->input->propertys["data-fds_menu_type"] = "reload";
		$item->input->propertys["onclick"] = $field->input->propertys["onchange"];

		$field = $f->addField("text", "_action_name");
		$field->input->propertys["placeholder"] = "...action";
		
		
		$item = $f->addField("button", "","", "");
		$item->input->propertys["data-fds_menu_type"] = "go";
		$item->input->propertys["onclick"] = svAction::send(
			array(
				"async"=>true,
				"panel"=>$this->panel,
				"params"=>"set_method:'panel:$this->panel;element:$this->element;method:load_action;action_name:{=_action_name};name:$this->name;';"
			));

		$item = $f->addField("button", "","", "");
		$item->input->propertys["data-fds_menu_type"] = "save";
		$item->input->propertys["onclick"] = svAction::send(
			array(
				"async"=>true,
				"panel"=>$this->panel,
				"params"=>"set_params:'action_name:{=_list_action};';imethod:save_action;"
			));
		


		
		$f->setTabPage($f->getTab(), 6);
		$div9 = new sgHTML("div");
		$div9->id = $this->divActionId;
		
		
		$f->appendChild($div9);		
		
	}// end function

	private function input($input, $name){
		global $seq;
		return $seq->input($input, $name);
	}// end function

	private function loadItems(){
		
		$ref = $this->getRef();
		$ref_menu = "$ref.m";	
		
		$script = "\n\t$ref_menu.data = [];"; 
		
		foreach($this->action as $k => $v){
			
			$v->i_title = addslashes($v->i_title);
			$v->a_title = addslashes($v->a_title);
			$script .= "\n\t$ref_menu.data.push({id:'$v->id',parent_id:'$v->parent_id',i_title:'$v->i_title',mode:2,action:'$v->action', a_title:'$v->a_title'})"; 
		
		}// next		
		$script .= "\n\t$ref_menu.reLoad($ref_menu.data);"; 
		
		$script .= $this->scriptActions();
		$script .= "\n\t$ref_menu.e.__actions.data = _DD;"; 
		$script .= "\n\t$ref_menu.e.__actions.setValue('');"; 		

		$this->script .= $script;
		$this->setFragment("","",$this->script);
	}// end function

	private function loadEleMethod($method){


		$query = "
			SELECT *
			FROM $this->t_ele_met
			WHERE method = '$method' AND name = '$this->name' AND element = '$this->element'";

		$data = $this->getDataQuery($query);


		$f = new svForm("sgpanel_".$this->panel);
		$f->setMain(new sgHTML(""));
		$f->subForm = true;
		$f->setRef($this->getRef());
		$f->setClasses("mds");
		$f->addPage("1","");
		
		$input = $f->addField("text", "m_method", "method", $method)->input;
		if($data["method"] != ""){
			$input->propertys["style"] = "border:1px green solid;color:green;";
		}else{
			$input->propertys["style"] = "border:1px blue solid;color:blue;";
		}// end if
		
		
		$f->addField("hidden", "m_element", "element", $this->element);
		$f->addField("hidden", "m_name", "name", $this->name);
		$f->addField("textarea", "m_params", "params", $data["params"]);


		$f->addField("textarea", "m_expressions", "expressions", $data["expressions"]);
		$f->addField("text", "m_function", "function", $data["function"]);
		$f->addField("text", "m_signs", "signs", $data["signs"]);
		
		$f->addField("textarea", "m_eval_signs", "eval_signs:", $data["eval_signs"]);
		
		$list = $this->inputData("SELECT $this->t_procedures.procedure, $this->t_procedures.procedure FROM $this->t_procedures ORDER BY $this->t_procedures.procedure");
		$f->addField("select", "m_proc_before", "proc_before:", $data["proc_before"], $list);
		$f->addField("select", "m_proc_after", "proc_after:", $data["proc_after"], $list);
		
		$list = $this->inputData("SELECT sequence, sequence FROM $this->t_sequences ORDER BY sequence");
		$f->addField("select", "m_seq_before", "seq_before:", $data["seq_before"], $list);
		$f->addField("select", "m_seq_after", "seq_after:", $data["seq_after"], $list);

		$f->addField("textarea", "m_functions", "functions:", $data["functions"]);
		$f->addField("textarea", "m_events_signs", "events_signs:", $data["events_signs"]);
		
		$record = "method=$method,name=$this->name,element=$this->element";
		$mode = 4;

		$f->addField("hidden", "m___form_mode", "mode", $mode);
		$f->addField("hidden", "m___form_record", "record", $record);

		
		$this->setFragment($this->elemMethodId, $f->render(), $f->getScript());
		//$this->script = $f->getScript();
		
		
		
	}// end function
	
	private function loadItem($id){
		$query = "
			SELECT *
			FROM $this->t_men_itm
			WHERE id = '$id' AND menu = '$this->name'";

		$data = $this->getDataQuery($query);


		$f = new svForm("sgpanel_".$this->panel);
		$f->setMain(new sgHTML(""));
		$f->subForm = true;
		$f->setRef($this->getRef());
		$f->setClasses("mds");
		$f->addPage("1","");


		$f->addField("hidden", "i_id", "id", $data["id"]);
		$f->addField("hidden", "i_menu", "menu", $data["menu"]);
		$f->addField("hidden", "i_parent_id", "parent_id", $data["parent_id"]);
		$f->addField("hidden", "i_order", "order", $data["order"]);
		$f->addField("text", "i_title", "title", $data["title"]);
		$f->addField("text", "i_class", "class", $data["class"]);
		$f->addField("select", "i_type_item", "type_item", $data["type_item"]);
		$f->addField("text", "i_image", "image", $data["image"]);
		$f->addField("textarea", "i_params", "params", $data["params"]);
		$f->addField("textarea", "i_style", "style", $data["style"]);
		$f->addField("textarea", "i_propertys", "propertys", $data["propertys"]);
		
		$list = $this->inputData("SELECT action, action FROM $this->t_actions");

		$f->addField("select", "i_action", "action", $data["action"], $list);
		
		$record = "id=$id,menu=$this->name";
		$mode = 4;

		$f->addField("hidden", "i___form_mode", "mode", $mode);
		$f->addField("hidden", "i___form_record", "record", $record);
		
		$this->setFragment($this->divItemId, $f->render(), $f->getScript());
		
	}// end function
	
	private function loadAction($action){
		
		
		
		$query = "
			SELECT *
			FROM $this->t_actions
			WHERE action = '$action'";

		$data = $this->getDataQuery($query);


		$f = new svForm("sgpanel_".$this->panel);
		$f->setMain(new sgHTML(""));
		
		$f->subForm = true;
		$f->setRef($this->getRef());
		$f->setClasses("mds");
		
		$f->addTab("action_p".$this->panel);
		$f->addTabPage("Basic");
		$f->addPage("1","");

		$input = $f->addField("text", "a_action", "action", $action)->input;
		if($data["action"] != ""){
			$input->propertys["style"] = "border:1px green solid;color:green;";
		}else{
			$input->propertys["style"] = "border:1px blue solid;color:blue;";
		}// end if		
		
		

		$f->addField("text", "a_title", "title", $data["title"]);

		$list = array();
		$list[] = array("", "", false);
		$list[] = array("0", "Direct", false);
		$list[] = array("1", "Action", false);

		$f->addField("select", "a_mode", "mode", $data["mode"], $list);

		$list = array();
		$list[] = array("0", "Default", false);
		$list[] = array("1", "True", false);
		$list[] = array("2", "False", false);
		$list[] = array("3", "Auto", false);

		$f->addField("select", "a_async", "async", $data["async"], $list);

		$f->addField("text", "a_panel", "panel", $data["panel"]);
		$f->addField("textarea", "a_params", "params", $data["params"]);
		
		$f->addTabPage("Secuence");
		$f->addPage("2","");		

		$field = $f->addField("params", "a_sequence", "");

		$btn = new sgHTML("input");
		$btn->type = "button";
		$btn->value = "EP.";
		$btn->onclick = "this.form.a_sequence.value+=__CONFIG_PANEL_STR;";

		$field->row->cells[1]->appendChild($btn);

		
		$list1 = $this->inputDataJSON("SELECT ".$this->cns->addQuotes("procedure").", ".$this->cns->addQuotes("procedure")." FROM $this->t_procedures ORDER BY 1");
		$list2 = $this->inputDataJSON("SELECT sequence, sequence FROM $this->t_sequences ORDER BY sequence");
		
		$field->config["classC3"] = "'$this->class_d_page'"; 
		$field->panel = $this->panel;
		$params["ele_params"] = json_decode('[
			{"name":"set_panel","title":"set_panel","type":"t", "value":""},
			{"name":"set_method","title":"set_method","type":"t", "value":""},
			{"name":"imethod","title":"imethod","type":"t", "value":""},
			{"name":"_procedure","title":"procedure","type":"s", "value":"", "data": '.$list1.'},
			{"name":"_sequence","title":"sequence","type":"s", "value":"", "data": '.$list2.'},
			{"name":"vses","title":"vses","type":"t", "value":""},
			{"name":"vreq","title":"vreq","type":"t", "value":""},
			{"name":"vexp","title":"vexp","type":"t", "value":""}
			]');
		$params["mode"] = 1;
		$field->input->setParams($params);
		$field->input->id = $field->input->name."_".$this->panel;
		
		hr($data["sequence"]);
		$field->input->value = $data["sequence"];
		$field->input->class = "mitext";
					


		$f->addTabPage("Button");
		$f->addPage("3","");	

		$f->addField("text", "a_valid", "valid", $data["valid"]);
		$f->addField("textarea", "a_confirm", "confirm", $data["confirm"]);
		$f->addField("text", "a_alert", "alert", $data["alert"]);
		$f->addField("textarea", "a_events", "events", $data["events"]);
		$f->addField("text", "a_class", "class", $data["class"]);
		$f->addField("textarea", "a_style", "style", $data["style"]);

		
		$record = "action=$action";
		$mode = 4;

		$f->addField("hidden", "a___form_mode", "mode", $mode);
		$f->addField("hidden", "a___form_record", "record", $record);

		
		
		
		
		$this->setFragment($this->divActionId, $f->render(), $f->getScript());
		//$this->script .= $f->getScript();
		
		
	}// end function

	private function tabMethod($method){
		
		global $seq;
		
		$menu = new sgHTML("div");
		$menu->class = $this->class_d_menu;

		//$label = new sgHTML("span");
		//$label->innerHTML = "Method: ";
		
		$btn = new sgHTML("input");
		$btn->type = "button";
		$btn->value = "GO";

		$a = new stdClass;
		$a->panel = $this->panel;
		$a->w_panel = "";
		$a->eparams = "panel:$this->panel;element:$this->element;method:load_ele_method;ele_method:{=__ele_method};";
		$a->action = "";
		$a->valid = "";
		$a->confirm = "";
		$btn->onclick = "if(this.form.__ele_method.value==''){alert('Method is required');this.form.__ele_method.focus();return false;};".svAction::setPanel(
			array(
				"async"=>true,
				"panel"=>$this->panel,
				"params"=>"panel:$this->panel;element:$this->element;method:load_ele_method;ele_method:{=__ele_method};"));

		$text = $seq->input("text", "__ele_method");
		$text->propertys["placeholder"] = "...method";
		$text->value = $method;


		$btnSave = new sgHTML("input");
		$btnSave->type = "button";
		$btnSave->value = "SAVE";

		$a = new stdClass;
		$a->panel = $this->panel;
		$a->w_panel = "";
		$a->eparams = "panel:$this->panel;element:$this->element;method:save_ele_method;ele_method:{=__ele_method};";
		$a->action = "";
		$a->valid = "";
		$a->confirm = "";
		$btnSave->onclick = svAction::setPanel(
			array(
				"async"=>true,
				"panel"=>$this->panel,
				"params"=>"panel:$this->panel;element:$this->element;method:save_ele_method;ele_method:{=__ele_method};"));


		$div = new sgHTML("div");
		$div->id = $this->elemMethodId;
		$div->class = $this->class_d_page;
		
		
		$menu->innerHTML = $text->render().$btn->render().$btnSave->render();
		return $menu->render().$div->render();

	}// end function 

	private function inputData($q){
		$cn = &$this->cns;
		$cn->query = $q;
		
		$data[] = array("", "", "");
		$result = $cn->execute();
		while($rs = $cn->getData($result)){
			$data[] = array($rs[0], $rs[1], "");
		}// end while
		return $data;
		
	}// end funtion 

	private function inputDataJSON($q){
		$cn = &$this->cns;
		$cn->query = $q;
		
		
		$str ='"":""';		
		
		$result = $cn->execute();
		while($rs = $cn->getData($result)){
			$str .= (($str!="")?", ":"")."\"$rs[0]\":\"$rs[1]\"";//array($rs[0], $rs[1], "");
		}// end while
		return "{ $str }";
		
	}// end funtion 
	
	private function getDataQuery($q){
		$cn = &$this->cns;
		$result = $cn->execute($q);

		if($data = $cn->getData($result)){
			$data["__mode"] = "2";
			return $data;
		}else{
			$fields = $cn->fieldsName($result);
			$data["__mode"] = "1";
			foreach($fields as $f){
				$data[$f] = "";
			}// next
			return $data;
		}// end while
		
	}// end funtion 	

	private function saveRequest($table, $data, $pre = ""){
		global $seq;
		
		$s = new sgFormSave;
		$s->cn = &$this->cns;
		$s->setPrefix($pre);
		$s->infoTable($table);
		//hr(print_r($s->save($data),true));
		return $s->save($data);


		global $seq;
		
		$req = $seq->getVarReq();
		
		$cn = &$this->cns;
		$cn->query = "SELECT * FROM $table WHERE $filter ";

		$result = $cn->execute();
		if($data = $cn->getDataAssoc($result)){
			foreach($data as $k => $v){
				if(!isset($req[$pre.$k])  ){
					continue;
				}// end if
				$value = $req[$pre.$k];
				$value = $cn->addSlashes($value);
				$k = $cn->addQuotes($k);
				$q_set[] = "$k ='$value'";
			}// next
			
			$value = $cn->addSlashes($value);
			
			$q = "UPDATE $table SET ". implode(", ", $q_set). " WHERE $filter";
		}else{
			$fields = $cn->fieldsName($result);

			foreach($fields as $k => $f){
				if(!isset($req[$pre.$f])  ){
					continue;
				}// end if
				$value = $req[$pre.$f];
				$value = $cn->addSlashes($value);

				$q_fields[] = $cn->addQuotes($f);
				$q_values[] = "'$value'";
			}// next
			$q = "INSERT INTO $table (".implode(", ",$q_fields).") VALUES (".implode(", ",$q_values).");";
		}// end while
		
		$cn->query = $q;
		$result = $cn->execute();
		
	}// end funtion 

	private function setProperty($prop, $value){
		
		global $seq;
		
		$data[$prop] = urldecode($value);
		$data["__form_mode"] = "2";
		$data["__form_record"] = "menu=$this->name";

		$s = new sgFormSave;
		$s->cn = &$this->cns;
		$prop = $s->cn->addQuotes($prop);
		$s->infoQuery("SELECT $prop FROM $this->t_menus");
		$r = $s->save($data);

	}// end funtion 

	private function setProperty2($prop, $value){
		
		global $seq;
		$cn = &$this->cns;
		$prop = $cn->addQuotes($prop);
		$value = $cn->addSlashes(urldecode($value));
		
		$cn->query = "UPDATE $this->t_menus SET $prop = '$value' WHERE menu = '$this->name';";
		hr($cn->query);

		$result = $cn->execute();
	}// end funtion 

	private function scriptMethods(){
		$cn = &$this->cns;
		
		$q = "SELECT method, method, '' FROM $this->t_ele_met WHERE name = '$this->name' AND element = '$this->element'";
			
		return $cn->aDataScript($q, "_DD");
		
	}// end function
	
	private function scriptActions(){
		$cn = &$this->cns;
		$q = "
			SELECT a.action, a.action, a.title, concat(a.action,' (',a.title,')') as title1 
			FROM $this->t_actions as a
			WHERE a.action !=''
			ORDER BY action
			";
			
		return $cn->aDataScript($q, "_DD");
		
	}// end function
	
	private function scriptItems(){
		$cn = &$this->cns;
		
		$concat = $cn->concat("id","' : '", "title");

		$order = $cn->addQuotes("order");		
		
		$q = "
			SELECT id, $concat, ''
			FROM $this->t_men_itm as a
			WHERE a.menu ='$this->name'
			ORDER BY $order
			";
			
		return $cn->aDataScript($q, "_DD");
		
	}// end function

	private function _listActions(){
		$cn = &$this->cns;
		$cn->query = "
			SELECT a.action, a.action, a.title, concat(a.action,' (',a.title,')') as title1 
			FROM $this->t_actions as a
			WHERE a.action !=''
			ORDER BY action
			";
		
		$data = array();
		$result = $cn->execute();
		while($rs = $cn->getData($result)){
			$data[] = array('value' => $rs[0], 'text' => $rs[1], 'parent' => '');
		}// end while
		return $data;
		
	}// end funtion 
	

}// end class

?>