<?php

class sigefor extends sg_panel{
	
	public $class_d_page = "__menud_page";
	
	public function __construct() {
		$this->cnn = conection($this->icnn);
		if($this->icns!=""){
			$this->cns = conection($this->icns);
		}else{
			$this->cns = &$this->cnn;
		}// end if
		
		$this->cnd = conection("_designer");

		$this->t_structures = TABLE_PREFIX."structures";
		$this->t_str_ele = TABLE_PREFIX."str_ele";
		$this->t_modules = TABLE_PREFIX."modules";
		$this->t_templates = TABLE_PREFIX."templates";
		$this->t_groups = TABLE_PREFIX."groups";
		$this->t_users = TABLE_PREFIX."users";
		$this->t_procedures = TABLE_PREFIX."procedures";
		$this->t_sequences = TABLE_PREFIX."sequences";	

	}// end function
	
	public function evalMethod($method){
		global $seq;

		$this->divElementId = "__mds_div_element_".$this->panel;
		$this->divModuleId = "__mds_div_module_".$this->panel;
		$this->divTemplateId = "__mds_div_template_".$this->panel;
		$this->divSequenceId = "__mds_div_sequence_".$this->panel;
		$this->divProcedureId = "__mds_div_procedure_".$this->panel;
		$this->divGroupId = "__mds_div_group_".$this->panel;
		$this->divUserId = "__mds_div_user_".$this->panel;

		switch($method){
			
		case "design":
		default:
			$this->render = 1;
			$this->design();

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
				
			}// end if
			
			if($property == "structure"){
				$this->name = $value;
			}
			//$this->design();
			$this->render = 0;
			break;			
		case "save_structure":
			$data = &$seq->cmd->v->req;
			
			//$module = $data["m_module"];
			
			$data["__form_mode"] = 4;
		
			$r= $this->saveRequest($this->t_structures, $data, "");	
		
			
			$this->name = $data["structure"];
			$this->design();
			$this->render = 1;
			break;
			
		case "delete_structure":
			$data = &$seq->cmd->v->req;
			
			$data["__form_mode"] = 3;
			
			
			$this->saveRequest($this->t_structures, $data, "");	
			$this->name = "";
			$this->design();
			$this->render = 1;		
		
			break;
		case "load_form":
			if(isset($this->eparams["ref_name"])){
				$ref_name = $this->eparams["ref_name"];
				$ref_value = $this->eparams["ref_value"];
				
				switch($ref_name){
					case "module":
						$table = $this->t_modules;
						$divId = $this->divModuleId;
						$pre = "m_";
						break;
					case "template":
						$table = $this->t_templates;
						$divId = $this->divTemplateId;
						$pre = "t_";
						break;
					case "sequence":
						$table = $this->t_sequences;
						$divId = $this->divSequenceId;
						$pre = "s_";
						break;
					case "procedure":
						$table = $this->t_procedures;
						$divId = $this->divProcedureId;
						$pre = "p_";
						break;
					case "group":
						$table = $this->t_groups;
						$divId = $this->divGroupId;
						$pre = "g_";
						break;
					case "user":
						$table = $this->t_users;
						$divId = $this->divUserId;
						$pre = "u_";
						break;
					case "element":
						$table = $this->t_str_ele;
						$divId = $this->divElementId;
						$pre = "e_";
						$ref_name = "panel";
						break;
					
				}// end switch
				
				$this->loadForm($table, $ref_name, $ref_value, $divId, $pre);
			}// end if	
			
			$this->render = 0;
			break;
		case "save_form":
			
			if(isset($this->eparams["ref_name"])){
				$ref_name = $this->eparams["ref_name"];
				$ref_value = $this->eparams["ref_value"];
				
				switch($ref_name){
					case "module":
						$table = $this->t_modules;
						$divId = $this->divModuleId;
						$pre = "m_";
						break;
					case "template":
						$table = $this->t_templates;
						$divId = $this->divTemplateId;
						$pre = "t_";
						break;
					case "sequence":
						$table = $this->t_sequences;
						$divId = $this->divSequenceId;
						$pre = "s_";
						break;
					case "procedure":
						$table = $this->t_procedures;
						$divId = $this->divProcedureId;
						$pre = "p_";
						break;
					case "group":
						$table = $this->t_groups;
						$divId = $this->divGroupId;
						$pre = "g_";
						break;
					case "user":
						$table = $this->t_users;
						$divId = $this->divUserId;
						$pre = "u_";
						break;
					case "element":
						$table = $this->t_str_ele;
						$divId = $this->divElementId;
						$pre = "e_";
						$ref_name = "panel";
						
						break;
					
				}// end switch

				
				$data = &$seq->cmd->v->req;
				
				//$module = $data["m_module"];
				
				$data[$pre."__form_mode"] = 4;
				//$data["__form_record"] = $data[$pre."__form_record"];
			
				$this->saveRequest($table, $data, $pre);
				
				$ref_value = $data[$pre.$ref_name];
				
				
				$this->loadForm($table, $ref_name, $ref_value, $divId, $pre);

				$this->script .= $this->dataListScript($table, $ref_name, $ref_value);
				$this->setFragment("","",$this->script);
				
				/**/
				$this->render = 0;

			}// end if	
			break;

		case "delete_form":

			if(isset($this->eparams["ref_name"])){
				
				$ref_name = $this->eparams["ref_name"];
				$ref_value = $this->eparams["ref_value"];
				
				switch($ref_name){
					case "module":
						$table = $this->t_modules;
						$divId = $this->divModuleId;
						$pre = "m_";
						break;
					case "template":
						$table = $this->t_templates;
						$divId = $this->divTemplateId;
						$pre = "t_";
						break;
					case "sequence":
						$table = $this->t_sequences;
						$divId = $this->divSequenceId;
						$pre = "s_";
						break;
					case "procedure":
						$table = $this->t_procedures;
						$divId = $this->divProcedureId;
						$pre = "p_";
						break;
					case "group":
						$table = $this->t_groups;
						$divId = $this->divGroupId;
						$pre = "g_";
						break;
					case "user":
						$table = $this->t_users;
						$divId = $this->divUserId;
						$pre = "u_";
						break;
					case "element":
						$table = $this->t_str_ele;
						$divId = $this->divElementId;
						$pre = "e_";
						$ref_name = "panel";
						break;
					
				}// end switch
				
				$data = &$seq->cmd->v->req;
				
				$data[$pre."__form_mode"] = 3;
				//$data["__form_record"] = $data[$pre."__form_record"];
				
				$this->saveRequest($table, $data, $pre);
				$this->script .= $this->dataListScript($table, $ref_name, $ref_value);
				$this->loadForm($table, $ref_name, $ref_value, $divId, $pre);
				$this->setFragment("","",$this->script);
				$this->render = 0;

			}// end if	
			break;
		
		case "_new":
			
			if(isset($this->eparams["element_name"]) and $this->eparams["element_name"]){
				$name = $this->eparams["element_name"];
			}else{
				$name = "";
			}// end if	
			
			$this->render = 1;

			$name = $this->newElement($name);
			
			$this->design();			
	
			break;			
									
		}// end switch
		
		if($this->type!=0 and $this->render != 0){

			$this->html = $this->getDinamicPanel($this->html);
		}// end if			
	}// end class		
	
	public function design(){
		
		$f = $this->f = new svForm(array("name"=>"sgpanel_".$this->panel));
		$f->setRef($this->getRef());
		$f->setClasses("fds");
		$f->setCaption("== Design Sigefor ==");


		$f->addMenuPage("1");
		$f->savePage();

		$fieldName = $this->cns->addQuotes("structure");
		$data = $this->inputData("SELECT $fieldName, $fieldName FROM $this->t_structures ORDER BY $fieldName");
	
		$input = $f->addField("select", "_list", "Structures", $this->name, $data);
		$input->input->propertys["onchange"] = svAction::setPanel(
			array(
				"async"=>true,
				"panel"=>$this->panel,
				"params"=>"panel:$this->panel;element:$this->element;method:design;name:{=_list};"
			));

		
		$item = $f->addField("button", "","", "");
		$item->input->propertys["data-fds_menu_type"] = "delete";
		$item->input->propertys["onclick"] = svAction::setPanel(
			array(
				"async"=>true,
				"panel"=>$this->panel,
				"params"=>"panel:$this->panel;element:$this->element;method:delete_structure;name:;menu_name:{=_list};"
			));
		


	
		$inpNewEle = $f->addField("text", "_new_element");
		$inpNewEle->input->propertys["placeholder"] = "...new structure";

		$item = $f->addField("button", "","", "");
		$item->input->propertys["data-fds_menu_type"] = "new";
		$item->input->propertys["onclick"] = svAction::setPanel(
			array(
				"async"=>true,
				"panel"=>$this->panel,
				"params"=>"panel:$this->panel;element:$this->element;method:_new;name:$this->name;element_name:{=_new_element};"
			));

		
		

		
		
		$f->setMain();
		$f->addPage("1", false);
		
		
		$tabMain = $f->addTab("tab_mnu_d_".$this->panel);


	
		if($this->name){
			$f->setMenuPage("1");
	
			$item = $f->addField("button", "","", "");
			$item->input->propertys["data-fds_menu_type"] = "render";
			$item->input->propertys["onclick"] = svAction::setPanel(
				array(
					"async"=>true,
					"panel"=>$this->panel,
					"params"=>"panel:$this->panel;element:$this->element;method:design;name:$this->name;"
				));
				
			$item = $f->addField("button", "","", "");
			$item->input->propertys["data-fds_menu_type"] = "save";
			$item->input->propertys["onclick"] = svAction::setPanel(
				array(
					"async"=>true,
					"panel"=>$this->panel,
					"params"=>"panel:$this->panel;element:$this->element;method:save_structure;name:$this->name;"
				));
				
			
			
			
			$f->setPage("1");		
	
			$field = $f->addField("text", "_d_name", "Name", $this->name);
			$field->input->propertys["onchange"] = svAction::send(
			array(
				"async"=>true,
				"panel"=>$this->panel,
				"params"=>"set_params:'property:structure;input_value:_d_name;';imethod:set_property;
					
					set_panel:'panel:$this->panel;element:$this->element;method:design;name:{=_d_name};';"));
	
			$field = $f->addField("text", "_d_title", "Title", $this->title);
			$field->input->propertys["onchange"] = svAction::setPanel(
			array(
				"async"=>true,
				"panel"=>$this->panel,
				"params"=>"panel:$this->panel;element:$this->element;method:set_property;name:$this->name;property:title;input_value:_d_title;"));
		/*
			$tabMain = $f->addTab("tab_mnu_d_".$this->panel);
			
			$this->_tabItems();
			$this->_tabBasic();
			$this->_tabParams();
			$this->_tabMethod();
			$this->_tabItem();
			$this->_tabAction();
			
			*/
			
			$this->_tabStrBasic();
			$this->_tabStrElement();
		}// end if

		$f->setMenuPage("1");

		$item = $f->addField("button", "","", "");
		$item->input->propertys["data-fds_menu_type"] = "reload";
		$item->input->propertys["onclick"] = svAction::setPanel(
			array(
				"async"=>true,
				"panel"=>$this->panel,
				"params"=>"panel:$this->panel;element:$this->element;method:design;name:$this->name;"
			));

		

		
		


		$this->_tabModule();
		$this->_tabTemplate();
		$this->_tabSequence();
		$this->_tabProcedure();
		$this->_tabGroup();
		$this->_tabUser();
		
		$this->css = $f->css;
		$this->html = $f->render();
		$this->script .= $f->getScript();					
	}// end function

	public function menuBar($name, $sQuery, $divBodyId){
		$f = $this->f;
		
		$f->addMenuPage($name);
		
		$data = $this->inputData($sQuery);
		
		$field = $f->addField("select", "_list_".$name, $name, "", $data);
		$field->input->propertys["onchange"] = svAction::send(
			array(
				"async"=>true,
				"panel"=>$this->panel,
				"params"=>"set_method:'panel:$this->panel;element:$this->element;method:load_form;name:$this->name;ref_name:$name;ref_value:{=_list_$name};';"));

		$item = $f->addField("button", "","", "");
		$item->input->propertys["data-fds_menu_type"] = "delete";
		$item->input->propertys["onclick"] = svAction::setPanel(
			array(
				"async"=>true,
				"panel"=>$this->panel,
				"params"=>"set_params:'ref_name:$name;ref_value:{=_list_$name};';imethod:delete_form;"
			));
	
	
		$item = $f->addField("button", "","", "");
		$item->input->propertys["data-fds_menu_type"] = "reload";
		$item->input->propertys["onclick"] = $field->input->propertys["onchange"];
	



		
		$inpNewAct = $f->addField("text", "_$name"."_name");
		$inpNewAct->input->propertys["placeholder"] = "...$name";
		


		$item = $f->addField("button", "","", "");
		$item->input->propertys["data-fds_menu_type"] = "go";
		$item->input->propertys["onclick"] = svAction::send(
			array(
				"async"=>true,
				"panel"=>$this->panel,
				"params"=>"set_method:'panel:$this->panel;element:$this->element;name:$this->name;method:load_form;ref_name:$name;ref_value:{=_".$name."_name};';"
			));
			
	
		$item = $f->addField("button", "","", "");
		$item->input->propertys["data-fds_menu_type"] = "save";
		$item->input->propertys["title"] = "save";
		$item->input->propertys["onclick"] = svAction::send(
			array(
				"async"=>true,
				"panel"=>$this->panel,
				"params"=>"set_params:'ref_name:$name;ref_value:{=_list_$name};';imethod:save_form;"
			));
	
			
	
		
		
	}// end funtion 
	
	private function _tabModule(){
		$f = $this->f;
		$f->addTabPage("Module");
		$f->savePage();
		$this->menuBar("module", "SELECT module, module, false FROM $this->t_modules", $this->divModuleId);
		//$f->setTabPage("tab_mnu_d_".$this->panel, 3);
		$f->restorePage();
		$div = new sgHTML("div");
		$div->id = $this->divModuleId;
		
		
		$f->appendChild($div);	
		//$f->addMenuPage("action");
	}// end function
	
	private function _tabTemplate(){

		$f = $this->f;
		$f->addTabPage("Template");
		$f->savePage();
		$this->menuBar("template", "SELECT template, template, false FROM $this->t_templates", $this->divTemplateId);
		//$f->setTabPage("tab_mnu_d_".$this->panel, 3);
		$f->restorePage();
		$div = new sgHTML("div");
		$div->id = $this->divTemplateId;
		$f->appendChild($div);	

	}// end function
	
	private function _tabSequence(){
		$f = $this->f;
		$f->addTabPage("Sequence");
		$f->savePage();
		$this->menuBar("sequence", "SELECT sequence, sequence, false FROM $this->t_sequences", $this->divSequenceId);
		//$f->setTabPage("tab_mnu_d_".$this->panel, 3);
		$f->restorePage();
		$div = new sgHTML("div");
		$div->id = $this->divSequenceId;
		$f->appendChild($div);	

	}// end function
	
	private function _tabProcedure(){
		$f = $this->f;
		$f->addTabPage("Procedure");
		$f->savePage();
		
		$fieldName =$this->cns->addQuotes("procedure");
		$this->menuBar("procedure", "SELECT $fieldName, $fieldName, false FROM $this->t_procedures", $this->divProcedureId);
		//$f->setTabPage("tab_mnu_d_".$this->panel, 3);
		$f->restorePage();
		$div = new sgHTML("div");
		$div->id = $this->divProcedureId;
		$f->appendChild($div);	
		
	}// end function
	
	private function _tabGroup(){
		$f = $this->f;
		$f->addTabPage("Group");
		$f->savePage();
		$fieldName =$this->cns->addQuotes("group");
		$this->menuBar("group", "SELECT $fieldName, $fieldName, false FROM $this->t_groups", $this->divGroupId);
		//$f->setTabPage("tab_mnu_d_".$this->panel, 3);
		$f->restorePage();
		$div = new sgHTML("div");
		$div->id = $this->divGroupId;
		$f->appendChild($div);
		
	}// end function
	
	private function _tabUser(){
		
		$f = $this->f;
		$f->addTabPage("User");
		$f->savePage();
		$this->menuBar("user", "SELECT user, user, false FROM $this->t_users", $this->divUserId);
		//$f->setTabPage("tab_mnu_d_".$this->panel, 3);
		$f->restorePage();
		$div = new sgHTML("div");
		$div->id = $this->divUserId;
		$f->appendChild($div);
		
	}// end function

	private function _tabStrBasic(){
		$f = $this->f;
		$f->addTabPage("Basic");

		$f->addPage("4", "");
		$table = $this->t_structures;
		$fieldName = "structure";
		$query = "SELECT * FROM $table WHERE ".$this->cns->addQuotes($fieldName)." = '$this->name'";



		$data = $this->getDataQuery($query);		

		$list = $this->inputData("SELECT template, template FROM $this->t_templates ORDER BY template");

		
		$f->addField("text", "structure", "structure", $this->name);
		$f->addField("text", "title", "title", $data["title"]);
		$f->addField("select", "template", "template", $data["template"], $list);
		$f->addField("text", "class", "class", $data["class"]);

		$f->addField("text", "main_panel", "main_panel", $data["main_panel"]);
		$f->addField("textarea", "params", "params", $data["params"]);
		$f->addField("textarea", "expressions", "expressions", $data["expressions"]);
		
		$f->addField("textarea", "events", "events", $data["events"]);

		$record = "structure=$this->name";
		$mode = 4;

		$f->addField("hidden", "__form_mode", "mode", $mode);
		$f->addField("hidden", "__form_record", "record", $record);
		
		$f->fields["_d_title"]->input->value = $data["title"];
		//$f->addMenuPage("action");
	}// end function

	private function _tabStrElement(){
		$f = $this->f;
		$f->addTabPage("Element");

		$f->savePage();
		
		
		$this->menuBar("element", "SELECT panel, panel, false FROM $this->t_str_ele WHERE structure = '$this->name'", $this->divElementId);
		//$f->setTabPage("tab_mnu_d_".$this->panel, 3);
		$f->restorePage();
		$div = new sgHTML("div");
		$div->id = $this->divElementId;
		$f->appendChild($div);	

	}// end function

	private function loadModule($module){
		$query = "SELECT * FROM $this->t_modules WHERE module = '$module'";

		$data = $this->getDataQuery($query);


		$f = new sgForm("sgpanel_".$this->panel);
		$f->setMain(new sgHTML(""));
		
		$f->subForm = true;
		$f->setRef($this->getRef());
		$f->setClasses("mds");
		
		$f->addPage("1","");

		$input = $f->addField("text", "m_module", "module", $module)->input;
		if($data["module"] != ""){
			$input->propertys["style"] = "border:1px green solid;color:green;";
		}else{
			$input->propertys["style"] = "border:1px blue solid;color:blue;";
		}// end if		
		
		
		$list = $this->inputData("SELECT ".$this->cns->addQuotes("structure").", title FROM $this->t_structures");

		$f->addField("text", "m_title", "title", $data["title"]);
		$f->addField("select", "m_structure", "structure", $data["structure"], $list);
		$f->addField("textarea", "m_configuration", "configuration", $data["configuration"]);


		$list = $this->inputData("SELECT ".$this->cns->addQuotes("procedure").", title FROM $this->t_procedures");
		$f->addField("select", "m_procedure", "procedure", $data["procedure"], $list);		

		$list = $this->inputData("SELECT sequence, title FROM $this->t_sequences");
		$f->addField("select", "m_sequence", "sequence", $data["sequence"], $list);
		
		$f->addField("text", "m_theme", "theme", $data["theme"]);
		$f->addField("text", "m_debug", "debug", $data["debug"]);



		
		$record = "module=$module";
		$mode = 4;

		$f->addField("text", "m___form_mode", "mode", $mode);
		$f->addField("text", "m___form_record", "record", $record);

		
		$this->setFragment($this->divModuleId, $f->render(), $f->getScript());
		//$this->script .= $f->getScript();
		
		
	}// end function

	private function loadForm($table, $name, $value, $divId, $pre = ""){
		
		if($name != "panel"){
			$query = "SELECT * FROM $table WHERE ".$this->cns->addQuotes($name)." = '$value'";
			$record = "$name=$value";
			
		}else{
			$query = "SELECT * FROM $table WHERE panel = '$value' AND structure = '$this->name'";
			$record = "panel=$value,structure=$this->name";
		}// end if

		
		$data = $this->getDataQuery($query);
		$f = new svForm("sgpanel_".$this->panel);
		$f->setMain(new sgHTML(""));
		
		$f->subForm = true;
		$f->setRef($this->getRef());
		$f->setClasses("mds");
		$f->addPage("1","");
		
		$this->{"loadForm".ucfirst($name)}($f, $value, $data);
	
		$f->addField("hidden", $pre."__form_mode", "mode", 4);
		$f->addField("hidden", $pre."__form_record", "record", $record);
		
		$this->setFragment($divId, $f->render(), $f->getScript());
		//$this->script .= $f->getScript();
		
	}// end if
	
	private function loadFormModule($f, $value, $data){

		$list1 = $this->inputData("SELECT structure, structure FROM $this->t_structures ORDER BY structure");
		$list2 = $this->inputData($this->cns->repQuotes("SELECT \"procedure\", \"procedure\" FROM $this->t_procedures ORDER BY \"procedure\""));
		$list3 = $this->inputData("SELECT sequence, sequence, false FROM $this->t_sequences");

		$list4 = array();
		$list4[] = array("0", "No", "");
		$list4[] = array("1", "Si", "");
		
		$f->addField("text", "m_module", "module", $value);
		$f->addField("text", "m_title", "title", $data["title"]);
		$f->addField("select", "m_structure", "structure", $data["structure"], $list1);
		$f->addField("textarea", "m_configuration", "configuration", $data["configuration"]);
		$f->addField("select", "m_procedure", "procedure", $data["procedure"], $list2);
		$f->addField("select", "m_sequence", "sequence", $data["sequence"], $list3);
		$f->addField("text", "m_theme", "theme", $data["theme"]);
		$f->addField("select", "m_on_design", "on_design", $data["on_design"], $list4);
		
	}// end fucntion

	private function loadFormTemplate($f, $value, $data){
		
		$f->addField("text", "t_template", "template", $value);
		$f->addField("text", "t_title", "title", $data["title"]);
		$f->addField("textarea", "t_code", "code", $data["code"]);
		
	}// end fucntion

	private function loadFormSequence($f, $value, $data){
		
		$f->addField("text", "s_sequence", "sequence", $value);
		$f->addField("text", "s_title", "title", $data["title"]);
		//$f->addField("textarea", "s_instructions", "instructions", $data["instructions"]);
		$f->addField("textarea", "s_params", "params", $data["params"]);
		

		$field = $f->addField("params", "s_instructions", "");

		$btn = new sgHTML("input");
		$btn->type = "button";
		$btn->value = "EP.";
		$btn->onclick = "this.form.s_instructions.value+=__CONFIG_PANEL_STR;";

		$field->row->cells[1]->appendChild($btn);

		
		$list1 = $this->inputDataJSON("SELECT ".$this->cns->addQuotes("procedure").", ".$this->cns->addQuotes("procedure")." FROM $this->t_procedures ORDER BY 1");
		$list2 = $this->inputDataJSON("SELECT sequence, title FROM $this->t_sequences ORDER BY sequence");
		
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
		$field->input->value = $data["instructions"];
		$field->input->class = "mitext";		

		
		
	}// end fucntion

	private function loadFormProcedure($f, $value, $data){
		
		$f->addField("text", "p_procedure", "procedure", $value);
		$f->addField("text", "p_title", "title", $data["title"]);
		$f->addField("textarea", "p_query", "query", $data["query"]);
		$f->addField("textarea", "p_vars", "vars", $data["vars"]);
		$f->addField("textarea", "p_expressions", "expressions", $data["expressions"]);
		$f->addField("textarea", "p_params", "params", $data["params"]);

	}// end fucntion
	
	private function loadFormGroup($f, $value, $data){
	
		$f->addField("text", "g_group", "group", $value);
		$f->addField("text", "g_title", "title", $data["title"]);

	}// end fucntion	

	private function loadFormUser($f, $value, $data){
		
		$list = array();
		$list[] = array("1", "Acive", "");
		$list[] = array("0", "Inactive", "");
				
		$f->addField("text", "u_user", "user", $value);
		$f->addField("password", "u_password", "password", $data["password"]);
		$f->addField("calendar", "u_expire", "expire", $data["expire"]);
		$f->addField("select", "u_status", "status", $data["status"], $list);
	
	}// end fucntion	
	
	private function loadFormPanel($f, $value, $data){
		
		$list = array();
		$list[] = array("1", "Dinamic", "");
		$list[] = array("0", "Static", "");

		$list2 = array();
		$list2[] = array("1", "Yes", "");
		$list2[] = array("0", "No", "");

				
		$f->addField("hidden", "e_structure", "structure", $this->name);
		$f->addField("text", "e_panel", "panel", $value);
		$f->addField("text", "e_element", "element", $data["element"]);
		$f->addField("text", "e_name", "name", $data["name"]);
		$f->addField("text", "e_method", "method", $data["method"]);
		$f->addField("textarea", "e_elem_params", "elem_params", $data["elem_params"]);
		$f->addField("select", "e_type", "type", $data["type"], $list);
		$f->addField("text", "e_class", "class", $data["class"]);
		$f->addField("textarea", "e_params", "params", $data["params"]);
		$f->addField("select", "e_design_mode", "design_mode", $data["design_mode"], $list2);

	}// end fucntion

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

	private function newElement($name = ""){

		global $seq;
		$cn = &$this->cns;
		if($name == ""){
			$name = "structure_".$cn->serialName($this->t_structures, "structure_", "structure");	
		}
		$title = ucwords(implode(" ",explode("_", $name)));
		
		$cn->query = "
			INSERT 
			INTO $this->t_structures (structure, title) 
			VALUES ('$name','$title');";
		$result = $cn->execute();

		$this->name = $name;
		
		return $name;
	}// end funtion 
	
	private function saveRequest($table, $data, $pre = ""){
		global $seq;
		
		$s = new sgFormSave;
		$s->cn = &$this->cns;
		$s->setPrefix($pre);
		$s->infoTable($table);
		
		return $s->save($data);
	}// end funtion

	private function setProperty($prop, $value){
		
		global $seq;
		
		$data[$prop] = urldecode($value);
		$data["__form_mode"] = "2";
		$data["__form_record"] = "structure=$this->name";

		$s = new sgFormSave;
		$s->cn = &$this->cns;
		$prop = $s->cn->addQuotes($prop);
		$s->infoQuery("SELECT $prop FROM $this->t_structures");
		$s->save($data);

	}// end funtion 
	
	private function dataListScript($table, $name, $value){

		$cn = &$this->cns;
		
		$fieldName = $this->cns->addQuotes($name);
		if($name != "panel"){
			$q = "
				SELECT $fieldName, $fieldName, false
				FROM $table as a
				WHERE $fieldName !=''
				ORDER BY $fieldName
			";
			
		}else{
			$q = "
				SELECT panel, panel, false 
				FROM $this->t_str_ele 
				WHERE structure = '$this->name'";	
			$name = "element";
		}// end if
			
		$ref = $this->getRef();
		$script = "";
		$script .= $cn->aDataScript($q, "_DD");
		$script .= "\n\t_DD.unshift(['','','']);";
		$script .= "\n\t$ref.e._list_$name.setData(_DD, '$value');"; 

		
		 
			
		return $script;		
	}	
	
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
}// end class

?>