<?php

require_once("cls_form.php");
//include("cls_form_elem.php");
//include("cls_grid.php");
//include("cls_paginator.php");

class cls_form_design extends cls_form{
	//public $class_d_menu = "des_menu_tab";
	private $class_field_cot = "_fds_fields_main";
	private $class_main_tab = "des_menu_tab";


	private $class_d_caption = "__menud_caption";
	private $class_d_menu = "__menud_menu";
	private $class_d_title = "__menud_title";
	private $class_d_header = "__menud_header";
	private $class_d_page = "__menud_page";
	private $class_d_input = "__menud_input";
	private $class_d_table = "fds_fields_table";

	private $class_f_menu = "__menuf_menu";

	private $selectedField = "";
	private $listField = false;
	
	public function extraMethod($method){
	
		global $seq;
		
		$ref = $this->getRef();
		
		$this->t_templates = TABLE_PREFIX."templates";	
		$this->t_menus = TABLE_PREFIX."menus";	
		$this->t_menus = TABLE_PREFIX."menus";	
		$this->t_procedures = TABLE_PREFIX."procedures";	
		$this->t_sequences = TABLE_PREFIX."sequences";	
		$this->t_menus = TABLE_PREFIX."menus";
		//$seq->setVarRec(array());
		
		//$this->evalParam();
		//$this->initMethod($method);
		$this->fieldsId = "__fds_fields_".$this->panel;
		$this->fieldDivId = "__fds_field_div_".$this->panel;
		$this->fieldParamsId = "__fds_field_params_".$this->panel;

		$this->fieldTabId1 = "__fds_field_tab1_".$this->panel;
		$this->fieldTabId2 = "__fds_field_tab2_".$this->panel;
		$this->fieldTabId3 = "__fds_field_tab3_".$this->panel;
		$this->fieldTabId4 = "__fds_field_tab4_".$this->panel;


		$this->elemMethodId = "__fds_elem_method_".$this->panel;


		$this->fields_method = "";
		if(isset($this->eparams["fields_method"])){
			$this->fields_method = $this->eparams["fields_method"];
		}// end if
		
		$this->ele_method = "";
		if(isset($this->eparams["ele_method"])){
			$this->ele_method = $this->eparams["ele_method"];
		}// end if

		
		if(isset($this->eparams["field"])){
			
			$this->selectedField = $this->eparams["field"];
		}
		
		switch($method){
		case "_new":
			if(isset($this->eparams["method_ref"])){
				$method_ref = $this->eparams["method_ref"];
			}else{
				$method_ref = "request";
			}// end if			
			
			if(isset($this->eparams["element_name"]) and $this->eparams["element_name"]){
				$name = $this->eparams["element_name"];
				
			}else{
				$name = "";
			}// end if	
			
			$this->render = 1;
			$this->mode = 1;
			$this->func = 22;
			$this->with_data = false;
			$name = $this->newElement($name);
			
			$this->execute($name, $method);
			
			$this->design($method_ref);			
	
			break;
		case "design":
			if(isset($this->eparams["method_ref"])){
				$method_ref = $this->eparams["method_ref"];
			}else{
				$method_ref="request";
			}// end if
		
	
		
		
			$this->render = 1;
			$this->mode = 1;
			$this->func = 22;
			$this->with_data = false;
			
			$this->execute($this->name, $this->fields_method);
			
			$this->design($method_ref, $this->fields_method);
			break;	
			
		case "config_panel":
			$this->execute($this->name, $this->fields_method);
			if(isset($this->eparams["method_ref"])){
				$method_ref = $this->eparams["method_ref"];
			}else{
				$method_ref = "request";
			}// end if
		
			$this->render = 0;
			$this->configPanel($method_ref);
			break;	
			
		case "new_element":

			$this->newElement();
			$this->render = 0;
			break;
			
			
		case "load_fields":
		
				
			$this->execute($this->name, $this->fields_method);
			$this->eparams["last_f_method"] = $this->fields_method;
			$this->loadFields($this->fields_method);
			
			$this->setFragment($this->fieldTabId1, "");
			$this->setFragment($this->fieldTabId2, "");
			$this->setFragment($this->fieldTabId3, "");
			$this->setFragment($this->fieldTabId4, "");	
			$this->script .= "\n\t$ref.e._list_field.setValue('');";
			$this->script .= "\n\t$ref.e.__fields_method.setValue('$this->fields_method');";
			
			$this->setFragment("","",$this->script);
			$this->render = 0;
			break;

		case "load_ele_method":
			$this->loadEleMethod($this->ele_method);
			$this->render = 0;
			break;
			
		case "field_params":	
			$field = $this->eparams["field"];
			//$method = $this->fields_method;

			//print_r($data);
			$this->loadFieldParams($field, $this->fields_method);
/*
			if($data = $this->field_params($this->eparams["field"], $this->fields_method)){
				
				$this->loadFieldParams($data, $this->eparams["field"], $this->fields_method);
				
			}else{
				$this->setFragment("", $this->fieldTabId1);
				$this->setFragment("", $this->fieldTabId2);
				$this->setFragment("", $this->fieldTabId3);
				$this->setFragment("", $this->fieldTabId4);				
							
	
			}
			*/
			
			$this->script .= "\n\t$ref.e._list_field.setValue('$field');";
			$this->setFragment("","",$this->script);
			$this->render = 0;
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

		case "set_field_property":
			$field = $this->eparams["field_name"];
			
			if(isset($this->eparams["property"])){
				$property = $this->eparams["property"];
				if(isset($this->eparams["input_value"])){
					$value = $seq->getReq($this->eparams["input_value"]);
				}else{
					$value = $this->eparams["value"];
				}
				
				$this->fieldProperty($field, $this->fields_method, $property, $value);
				$this->execute($this->name, $this->fields_method);
				$this->loadFields($this->fields_method);
				$q = "SELECT method, method, '' FROM $this->t_form_fields WHERE form = '$this->name' AND method != '' GROUP BY method";
				$this->script .= $this->dataScript($q);
				$this->script .= "\n\t_DD.unshift(['','','']);";
				$this->script .= "\n\t$ref.e._list_field_method.setData(_DD, '".$this->fields_method."');"; 			
				$this->setFragment("","",$this->script);			
			}// end if
			$this->render = 0;
			break;
		case "save_form":

			if($this->name==""){
				
				$this->message = "ERROR IN SAVE";
				
			}else{

				$data = &$seq->cmd->v->req;
				$data["d___form_mode"] = 4;
				$data["d___form_record"] = "form=$this->name";
			
				$this->saveRequest($this->t_forms, $data, "d_");
			}
			$this->render = 0;
			break;
		case "delete_form":
		
		
			if(isset($this->eparams["form_name"])){
				
				$form_name = $this->eparams["form_name"];
				$data = &$seq->cmd->v->req;
				
				$data["d___form_mode"] = 3;
				$data["d___form_record"] = "form=$form_name";
						
				$this->saveRequest($this->t_forms, $data, "d_");


			}// end if					
			$this->render = 0;
			break;
		case "save_ele_method":
		
			$data = &$seq->cmd->v->req;
			$data["__form_mode"] = $data["m___form_mode"];
			$data["__form_record"] = $data["m___form_record"];
					

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


		case "save_field":

			if($this->name == "" or !isset($seq->cmd->v->req["f___form_mode"])){
				
				$this->message = "ERROR IN SAVE";
				
			}else{

				$data = &$seq->cmd->v->req;
				$data["__form_mode"] = $data["f___form_mode"];
				$data["__form_record"] = $data["f___form_record"];
			
				$this->saveRequest($this->t_form_fields, $data, "f_");

				$this->execute($this->name, $data["f_method"]/*$this->fields_method*/);
				$this->loadFields($data["f_method"]/*$this->fields_method*/);
				
				
				$q = "SELECT method, method, '' FROM $this->t_form_fields WHERE form = '$this->name' AND method != '' GROUP BY method";
				$this->script .= $this->dataScript($q);
				$this->script .= "\n\t_DD.unshift(['','','']);";
				$this->script .= "\n\t$ref.e._list_field_method.setData(_DD, '".$data["f_method"]."');"; 			
				$this->setFragment("","",$this->script);

			}
			
			//$this->saveField($this->name, $this->eparams["field"], $this->fields_method);
			$this->render = 0;
			break;

		case "delete_field_method":
		
			$this->ele_method = "";
			$field_name = "";
			if(isset($this->eparams["ele_method"])){
				$this->ele_method = $this->eparams["ele_method"];
			}// end if	
			
			if(isset($this->eparams["field_name"])){
				$field_name = $this->eparams["field_name"];	
			}
			
			$data = &$seq->cmd->v->req;
			$data["f_form"]="";
			$data["f___form_mode"] = 3;
			$data["f___form_record"] = "method=$this->ele_method,form=$this->name,name=$field_name";
					
			$this->saveRequest($this->t_form_fields, $data, "f_");
			//$this->loadEleMethod($this->ele_method);
			$this->execute($this->name, "");
			$this->loadFields("");
			
			$this->setFragment($this->fieldTabId1, "");
			$this->setFragment($this->fieldTabId2, "");
			$this->setFragment($this->fieldTabId3, "");
			$this->setFragment($this->fieldTabId4, "");	
			
						
			$this->script .= $this->scriptFieldMethods();
			$this->script .= "\n\t_DD.unshift(['','','']);";
			$this->script .= "\n\t$ref.e._list_field_method.setData(_DD, '');"; 
			$this->script .= "\n\t$ref.e._list_field.setValue('');"; 
			$this->setFragment("","",$this->script);
			$this->render = 0;
			break;

		case "update_query":

			$this->setQuery($seq->getReq("__form_query"));
			$this->render = 0;
			break;
		case "title_form":
			$this->titleForm($seq->getReq("__form_title"));
			$this->render = 0;
			break;		
			
		case "title_field":
			$this->titleField($this->eparams["field_name"], $seq->getReq("__title_".$this->eparams["field_name"]));
			$this->render = 0;
			break;	
			
		case "required_field":
		
		
		
		
			$this->execute($this->name, $this->fields_method);
		
		
		
			$field = $this->eparams["field_name"];
			$this->message = $field;
		
			
			
			if($this->eparams["field_required"]){
				$this->fields[$field]->rules["required"]="{}";
			}else{
				$this->fields[$field]->rules["required"]="";
			}
			$rules = "";


			
			//print_r($this->fields[$this->eparams["field_pos"]]->rules);
			foreach($this->fields[$field]->rules as $k => $v){
				if($v){
					$rules .= "$k:\"$v\";";
				}
			}// next

			$this->fieldProperty($field, $this->fields_method, "valid", $rules);
			//$this->loadFields($this->fields_method);
			$this->loadFieldParams($field, $this->fields_method);
			$q = "SELECT method, method, '' FROM $this->t_form_fields WHERE form = '$this->name' AND method != '' GROUP BY method";
			$this->script .= $this->dataScript($q);
			$this->script .= "\n\t_DD.unshift(['','','']);";
			$this->script .= "\n\t$ref.e._list_field_method.setData(_DD, '".$this->fields_method."');"; 			
			$this->setFragment("","",$this->script);
			$this->render = 0;
			break;			
			
		}
		

		return true;
		
	}// end function 
	
	private function design($method_ref="load", $fields_method=""){
		
		$f = $this->f = new svForm(array("name"=>"sgpanel_".$this->panel));
		$f->setRef($this->getRef());
		$f->setClasses("fds");
		$f->setCaption("== Design Form: $this->name ==");	
		

	

		
		
		$f->addMenuPage("1");
		
		$data = $this->inputData("SELECT a.form, a.form FROM $this->t_forms as a WHERE a.form !='' ORDER BY form");
		
		$input = $f->addField("select", "_list", "Forms", $this->name, $data);
		$input->input->propertys["onchange"] = svAction::setPanel(array(
			"panel"=>$this->panel,
			"async"=>true,
			"params"=>"panel:$this->panel;element:$this->element;method:design;name:{=_list};"
		));
		
		
		
		$item = $f->addField("button", "","", "");
		$item->input->propertys["data-fds_menu_type"] = "delete";
		$item->input->propertys["onclick"] = svAction::send(
			array(
				"async"=>true,
				"panel"=>$this->panel,
				"params"=>"set_params:'form_name:{=_list};';imethod:delete_form;set_panel:'panel:$this->panel;element:$this->element;method:design;name:;';"
			));
	
		
		
		$inpNewEle = $f->addField("text", "_element_name");
		$inpNewEle->input->propertys["placeholder"] = "...new element";
		
		$item = $f->addField("button", "","", "");
		$item->input->propertys["data-fds_menu_type"] = "new";
		$item->input->propertys["onclick"] = svAction::setPanel(
			array(
				"async"=>true,
				"panel"=>$this->panel,
				"params"=>"panel:$this->panel;element:$this->element;method:_new;name:{=_element_name};element_name:{=_element_name};"
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
				"params"=>"imethod:save_form;set_panel:'panel:$this->panel;element:$this->element;method:design;name:$this->name;';"
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
			//$field->input->propertys["onchange"] = cfg_action::event("set_panel_x", $this->panel, "panel:$this->panel;element:$this->element;method:design;name:{=_d_name};iname:$this->name;imethod:set_property;property:form;input_value:_d_name;");
			$field->input->propertys["onchange"] = svAction::send(array(
				"panel"=>$this->panel,
				"async"=>true,
				"params"=>"set_params:'property:form;input_value:_d_name;';
				imethod:set_property;
				set_panel:'$this->panel;element:$this->element;method:design;name:{=_d_name};';"
				));
	
			$field = $f->addField("text", "_d_title", "Title", $this->title);
			$field->input->id = "_d_title_p".$this->panel;
			
			//$field->input->propertys["onchange"] = cfg_action::event("set_panel_x", $this->panel, "panel:$this->panel;element:$this->element;imethod:set_property;property:title;input_value:_d_title;");
			
			$field->input->propertys["onchange"] = svAction::send(array(
				"panel"=>$this->panel,
				"async"=>true,
				"params"=>"set_params:'input_value:_d_title;property:title;';imethod:set_property;"
				));
			
			
	
			$tabMain = $f->addTab("tab_mnu_d_".$this->panel);
			
			$this->_tabFields($fields_method, $this->selectedField);
			
			$this->_tabQuery();
			$this->_tabBasic();
			$this->_tabParams();
			$this->_tabMethod();
			
			//$this->_tabItem();
			//$this->_tabAction();
			

		}// end if
		
		
		
		$this->css = $f->css;
		$this->html = $f->render();
		$this->script = $f->getScript()/*.$menu_script*/;		
	}// end funtion
	
	private function _tabFields($method = "", $field = ""){
		
		$fields = $this->fieldsMethod($this->query, $method);
		
		$f = $this->f;
		$f->setTab("tab_mnu_d_".$this->panel);
		
		$f->addTabPage("Fields");
		
		$f->addMenuPage("fields");

		

		$data = $this->inputData("SELECT method, method FROM $this->t_form_fields WHERE form = '$this->name' AND method != '' GROUP BY method");
	
		$input = $f->addField("select", "_list_field_method", "Action", $method, $data);
		$input->input->propertys["onchange"] = svAction::send(
			array(
				"async"=>true,
				"panel"=>$this->panel,
				"params"=>"set_method:'panel:$this->panel;element:$this->element;name:$this->name;method:load_fields;fields_method:{=_list_field_method};';"
			));


		$item = $f->addField("button", "","", "");
		$item->input->propertys["data-fds_menu_type"] = "delete";
		$item->input->propertys["onclick"] = svAction::send(
			array(
				"async"=>true,
				"panel"=>$this->panel,
				"params"=>"set_params:'ele_method:{=_list_field_method};field_name:{=_list_field};';imethod:delete_field_method;"
			));


		$item = $f->addField("button", "","", "");
		$item->input->propertys["data-fds_menu_type"] = "reload";
		$item->input->propertys["onclick"] = $input->input->propertys["onchange"];

		
		$input = $f->addField("text", "__fields_method", $method);
		$input->input->propertys["placeholder"] = "...method";



		$item = $f->addField("button", "","", "");
		$item->input->propertys["data-fds_menu_type"] = "go";
		$item->input->propertys["onclick"] = svAction::send(
			array(
				"async"=>true,
				"panel"=>$this->panel,
				"params"=>"set_method:'panel:$this->panel;element:form;name:$this->name;method:load_fields;fields_method:{=__fields_method};';"
			));		


		$item = $f->addField("button", "","", "");
		$item->input->propertys["data-fds_menu_type"] = "save";
		$item->input->propertys["onclick"] = svAction::send(
			array(
				"async"=>true,
				"panel"=>$this->panel,
				"params"=>"set_params:'fields_method:{=__fields_method};';imethod:save_field;"
			));		
		
		$data = array();
		$data[] = array("", "", "");
		foreach($fields as $k){
			$data[] = array($k->name, $k->name, "");
		}// next
	
		$input = $f->addField("select", "_list_field", "Field", "", $data);
		$input->input->propertys["onchange"] = svAction::send(
			array(
				"panel"=>$this->panel,
				"async"=>true,
				"params"=>"set_method:'panel:$this->panel;element:$this->element;name:$this->name;method:field_params;field:{=_list_field};fields_method:{=_list_field_method};';"
			));		

		$item = $f->addField("button", "","", "");
		$item->input->propertys["data-fds_menu_type"] = "delete";
		$item->input->propertys["onclick"] = svAction::send(
			array(
				"async"=>true,
				"panel"=>$this->panel,
				"params"=>"set_params:'ele_method:{=_list_field_method};field_name:{=_list_field};';imethod:delete_field_method;"
			));


		$f->setTabPage($f->getTab(), 1);
		
		$t = new sgTable(2);
		$t->setTags(array("table" => "div", "caption"=>"div", "tr" => "div", "td" => "span", "th" => "span", "empty" => ""));

		$row = $t->insertRow();
		
		$f->appendChild($t);
		
		$row->class = "form_design_field_row";
		
		$div = new sgHTML("span");
		$div->id = $this->fieldsId;
		$div->appendChild($this->_configField($method));
			
		$row->cells[0]->appendChild($div);
		$row->cells[0]->class = "form_design_field_list";
		$row->cells[1]->class = "form_design_field_det";
		
		$f->setPage($row->cells[1]);

		$f->addTab("tab_mnu_f1_".$this->panel);
		$f->addTabPage("Input")->id = $this->fieldTabId1;
		$f->addTabPage("Basic")->id = $this->fieldTabId2;
		$f->addTabPage("Params")->id = $this->fieldTabId3;
		$f->addTabPage("Valid")->id = $this->fieldTabId4;
	
	}// end funtion 

	private function _tabQuery(){

		$f = $this->f;
		
		
		
		$f->setTab("tab_mnu_d_".$this->panel);
		
		
		$f->addTabPage("Query");
		
		$f->addPage("9", false);
		$btnQuery = new sgHTML("input");
		$btnQuery->type = "button";
		//$btnQuery->value = "Update Query";
		$btnQuery->{"data-fds_menu_type"} = "save";
		
		$btnQuery->onclick = svAction::send(
			array(
				"async"=>true,
				"panel"=>$this->panel,
				"params"=>"set_params:'property:query;fields_method:{=__fields_method};input_value:d_query;';imethod:set_property;set_panel:'panel:$this->panel;element:$this->element;method:design;';"
			));
		

		$field = $f->addField("textarea", "d_query", $btnQuery, $this->query);
		$field->input->propertys["style"] = "width:100%;min-height:6em;";
		
		


	}// end funtion 

	private function _tabBasic(){

		$f = $this->f;
		$f->setTab("tab_mnu_d_".$this->panel);
		$f->addTabPage("Basic");
		$f->addPage("4", false);

		$listTemplate = $this->inputData("SELECT template, template FROM $this->t_templates ORDER BY template");
		$listMenu = $this->inputData("SELECT menu, menu FROM $this->t_menus ORDER BY menu");
		
		$f->addField("text", "d_form", "form", $this->name);
		$f->addField("text", "d_title", "title", $this->title);

		$f->addField("text", "d_class", "class", $this->class);
		$f->addField("select", "d_template", "template", $this->template, $listTemplate);
		$f->addField("select", "d_template_panel", "template_panel", $this->template_panel, $listTemplate);

		$f->addField("select", "d_menu", "menu", $this->menu, $listMenu);
		$f->addField("textarea", "d_tabs", "tabs", $this->tabs);
		$f->addField("textarea", "d_groups", "groups", $this->groups);

		$f->addField("textarea", "d_expressions", "expressions", $this->expressions);
		$f->addField("textarea", "d_signs", "signs", $this->signs);
		$f->addField("textarea", "d_eval_signs", "eval_signs", $this->eval_signs);

	}// end funtion 
	
	private function _tabParams(){
		
		$f = $this->f;
		$f->setTab("tab_mnu_d_".$this->panel);
		$f->addTabPage("Params");
		$f->addPage("5", "");
		//$f->addTable("6", "");		
		
		$field = $f->addField("params", "d_params", "");

		$listMenus = $this->inputDataJSON("SELECT menu, menu FROM $this->t_menus ORDER BY menu");		
		$listTemplates = $this->inputDataJSON("SELECT template, template FROM $this->t_templates ORDER BY template");			
		
		
		$field->config["classC3"] = "'$this->class_d_page'"; 
		$field->panel = $this->panel;
		$a = $params["ele_params"] = json_decode('[

		{"name":"title","title":"title","type":"t", "value":""},
			{"name":"class","title":"class","type":"t", "value":""},
			{"name":"menu_list","title":"menu_list","type":"s", "value":"", "data": '.$listMenus.'},
			{"name":"menu_request","title":"menu_request","type":"s", "value":"", "data": '.$listMenus.'},
			{"name":"menu_load","title":"menu_load","type":"s", "value":"", "data": '.$listMenus.'},
			{"name":"template","title":"template","type":"s", "value":"", "data": '.$listTemplates.'},
			{"name":"template_panel","title":"template_panel","type":"s", "value":"", "data": '.$listTemplates.'},
			{"name":"fields_search","title":"fields_search","type":"b", "value":""}]');

		//$a = $params["ele_params"] = json_decode('[{"name":"title","title":"title","type":"t", "value":""}]');
			
		
			
		$field->input->setParams($params);
		
			
		$field->input->id = $field->input->name."_".$this->panel;
		$field->input->value = $this->params;
		$field->input->class = "mitext";
		$field->input->mode = 1;

	}// end funtion 

	private function _tabMethod(){
		
		$f = $this->f;
		//$f->setTab("tab_mnu_d_".$this->panel);
		$f->addTabPage("Method");

		$f->addMenuPage("method");

		//$divMenu = new sgHTML("div");
		//$divMenu->class = $this->class_d_menu;			
		//$f->appendChild($divMenu);			
		

		$data = $this->inputData("SELECT method, method FROM $this->t_ele_met WHERE name = '$this->name' AND element = '$this->element'");
	
		$field = $f->addField("select", "_list_method", "Action", "", $data);
		$field->input->propertys["onchange"] = svAction::send(
			array(
				"async"=>true,
				"panel"=>$this->panel,
				"params"=>"set_method:'panel:$this->panel;name:$this->name;element:$this->element;method:load_ele_method;ele_method:{=_list_method};';"
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
		$input->input->propertys["placeholder"] = "...method";


		$item = $f->addField("button", "","", "");
		$item->input->propertys["data-fds_menu_type"] = "go";
		$item->input->propertys["onclick"] = svAction::send(
			array(
				"async"=>true,
				"panel"=>$this->panel,
				"params"=>"set_method:'panel:$this->panel;name:$this->name;element:$this->element;method:load_ele_method;ele_method:{=__ele_method};';"
			));
			
		$item = $f->addField("button", "","", "");
		$item->input->propertys["data-fds_menu_type"] = "save";
		$item->input->propertys["onclick"] = svAction::send(
			array(
				"async"=>true,
				"panel"=>$this->panel,
				"params"=>"set_params:'ele_method:{=__ele_method};';imethod:save_ele_method;"
			));			
		
		
		$f->setTabPage($f->getTab(), 5);
		
		$div = new sgHTML("div");
		$div->id = $this->elemMethodId;
		
		$f->appendChild($div);		
	
	}// end funtion 
	
	private function _configField($method = ""){
		
		global $seq;
		
		
		
		$this->listField = $fields = $this->fieldsMethod($this->query, $method);
		
		
		$t = new sgTable(3);
		$t->class = $this->class_d_table;
		$t->border="0";
		$f=0;

		$t->insertRow();
		$t->cells[$f][0]->text = "Field";
		$t->cells[$f][1]->text = "*";
		//$t->cells[$f][2]->text = "H";
		//$t->cells[$f][3]->text = "R";
		$f++;

		foreach($fields as $i => $field){	
			$name = $field->name;
			$t->insertRow();
			$t->rows[$f]->title = $name;
/*
			$set = new sgHTML("input");
			$set->type = "button";
			$set->disabled = "disabled";
			if(isset($field->check)){
				$set->checked = "true";	
			}
*/
			/*
			$readonly = new sgHTML("input");
			$readonly->type = "checkbox";
			*/
			$title = new sgHTML("input");
			$title->type = "text";
			$title->name = "__title_".$field->name;
			$title->value = $field->title;
			$title->placeholder ="...".$field->name;


			$a = new stdClass;
			$a->panel = $this->panel;
			$a->w_panel = "";
			$a->eparams = "panel:$this->panel;element:$this->element;name:$this->name;field_name:$name;method:set_field_property;property:title;input_value:$title->name;";
			$a->action = "";
			$a->valid = "";
			$a->confirm = "";
			$title->onchange = svAction::setPanel(
			array(
				"async"=>true,
				"panel"=>$this->panel,
				"params"=>"panel:$this->panel;element:$this->element;name:$this->name;field_name:$name;method:set_field_property;property:title;input_value:$title->name;fields_method:$method;"
			));		

			$hiddenReq = new sgHTML("input");
			$hiddenReq->type = "hidden";
			$hiddenReq->name = "__required_".$field->name;
			
			$required = new sgHTML("input");
			$required->type="checkbox";
			
			
			//$required->value="1";
			
			$a = new stdClass;
			$a->panel = $this->panel;
			$a->w_panel = "";
			$a->eparams = "panel:$this->panel;element:$this->element;name:$this->name;field_name:$name;field_required:'+((this.checked)?1:0)+';method:required_field;fields_method:$method;";
			$a->action = "";
			$a->valid = "";
			$a->confirm = "";
			//$required->onchange = cfg_action::setEvent2("set_panel_x", $a);				



			$required->onclick = "this.form.$hiddenReq->name.value=(this.checked)?1:0;".svAction::setPanel(array(
				"panel"=>$this->panel,
				"async"=>true,
				"params"=>"panel:$this->panel;element:$this->element;name:$this->name;field_name:$name;field_required:{=$hiddenReq->name};method:required_field;fields_method:$method;",
				"waitTarget"=>"tab_mnu_f1_".$this->panel."_body"
				));
			
			
			//$required->onclick = cfg_action::setEvent("ajax", $this->panel, "yanny:;panel:$this->panel;element:form;method:design;field_table:$table;field_name:$name;field_pos:$i;field_required:'+((this.checked)?1:0)+';name:$this->name;ielement:form;imethod:required_field;","x1");
			/*
			$hidden = new sgHTML("input");
			$hidden->type="checkbox";
			*/
			$ind = "";
			

			
			if(isset($field->rules["required"])){
				$required->checked = "checked";
				$hiddenReq->value = "1";
				
			}

			$options = new sgHTML("input");
			$options->type = "button";
			
			if(isset($field->check)){
				$options->{"data-fds_menu_type"} = "go_on";
			}else{
				$options->{"data-fds_menu_type"} = "go_off";	
			}
			/*$valid = new sgHTML("input");
			$valid->type = "button";
			$valid->value = "V";*/
			
			/*
			$ac = new sgAction();
			$ac->async = true;
			$ac->panel = $this->panel;
			$ac->waitTarget ="tab_mnu_f1_".$this->panel."_body";

			$ac->addParam("set_method", "panel:$this->panel;element:$this->element;name:$this->name;method:field_params;field:$name;fields_method:$method;");
			$ac->addParam("xxx", "panel:$this->panel;element:$this->element;name:$this->name;method:field_params;field:$name;fields_method:$method;");

*/
			

			
			$options->onclick = svAction::send(array(
				"panel"=>$this->panel,
				"async"=>true,
				"params"=>"set_method:'panel:$this->panel;element:form;name:$this->name;method:field_params;field:$name;fields_method:$method;';",
				"waitTarget"=>"tab_mnu_f1_".$this->panel."_body"
				));
			
		
			
			
			
			$t->cells[$f][0]->text = $title->render();
			$t->cells[$f][1]->text = $required->render().$hiddenReq->render();
			//$t->cells[$f][2]->text = $hidden->render();
			//$t->cells[$f][3]->text = $readonly->render();
			$t->cells[$f][2]->text = $options->render();
			//$t->cells[$f][5]->text = $valid->render();
			
			$f++;
			
		}// next

		return $t->render();
		
	}// end function

	private function getFormNextId(){
		$cn = &$this->cns;
		return "form_".$cn->serialName($this->t_forms, "form_", "form");
	}// end funtion 

	private function newElement($name=""){

		global $seq;
		if($name == ""){
			$name = $this->getFormNextId();
		}
		
		$title = ucwords(implode(" ",explode("_", $name)));
		
		$cn = &$this->cns;
		$cn->query = "
			INSERT 
				INTO $this->t_forms (form, title, query) 
				VALUES ('$name','$title', 'SELECT \'\' as field');";
		
		//echo($cn->query);
		//$this->debug = $cn->query;
		$result = $cn->execute();

		$this->vparams["new_form"] = $name;
		$seq->setReq("new_form", $name);
		
		$this->name = $name;
		
		return $name;
	}// end funtion 

	private function setProperty($prop, $value){
		
		global $seq;
		
		$data[$prop] = urldecode($value);
		$data["__form_mode"] = "2";
		$data["__form_record"] = "form=$this->name";

		$s = new sgFormSave;
		$s->cn = &$this->cns;
		$prop = $s->cn->addQuotes($prop);
		$s->infoQuery("SELECT $prop FROM $this->t_forms");
		$r = $s->save($data);
		//print_r($r);

	}// end funtion 

	private function setProperty2($prop, $value){
		
		global $seq;
		$cn = &$this->cns;
		$value = $cn->addSlashes(urldecode($value));
		$prop = $cn->addQuotes($prop);
		
		$cn->query = "UPDATE $this->t_forms SET $prop = '$value' WHERE form = '$this->name';";
		hr($cn->query);
		$result = $cn->execute();
	}// end funtion 

	private function setQuery($query){
		
		global $seq;
		$cn = &$this->cns;
		$query = $cn->addSlashes(urldecode($query));
		
		$cn->query = "UPDATE $this->t_forms SET `query`='$query' WHERE `form`='$this->name';";

		$result = $cn->execute();
	}// end funtion 

	private function titleForm($title){
		global $seq;
		$cn = &$this->cns;
		$title = $cn->addSlashes(urldecode($title));
		$cn->query = "UPDATE $this->t_forms SET `title`='$title' WHERE `form`='$this->name';";
		//echo($cn->query);
		$result = $cn->execute();
	}// end funtion 

	private function titleField($field, $title){
		global $seq;
		$cn = &$this->cns;
		$title = $cn->addSlashes(urldecode($title));
		
		$cn->query = "SELECT 1 FROM $this->t_form_fields WHERE `form`='$this->name' AND `name`='$field'";
		
		$result = $cn->execute();
		if($rs = $cn->getData($result)){
			$cn->query = "UPDATE $this->t_form_fields SET `title`='$title' WHERE `form`='$this->name' AND `name`='$field';";
			//echo $cn->query;
			$result = $cn->execute();
		}else{
			$cn->query = "INSERT INTO $this->t_form_fields (`form`, `name`, `title`) VALUES ('$this->name', '$field', '$title');";
			//echo $cn->query;
			$result = $cn->execute();
		}
		
	}// end funtion 

	private function configPanelX($method_ref=""){
		global $seq;
		$record = "";
		foreach($this->keys as $k => $v){
			$record = (($record!="")?",":"")."$k=@$k"."_x";
		
		}// next
		$this->script = "__CONFIG_PANEL = {panel: '$this->panel', element: 'form', name:'$this->name', method:'$method_ref', params:'record:$record'};";
		$this->script .= "__CONFIG_PANEL_STR = 'panel:$this->panel;element:form;name:$this->name;method:$method_ref;params:record:$record;';";
		
	}// end funtion 

	private function fieldProperty($field, $method, $prop, $value){
		
		global $seq;
		$cn = &$this->cns;

		$value = urldecode($value);
		
		$query = "
			SELECT 1 as NN
			FROM $this->t_form_fields 
			WHERE form = '$this->name' AND name = '$field' AND method = '$method'";
			
		$result = $cn->execute($query);

		if($rs = $cn->getData($result)){

			$r = $this->setFieldProperty($field, $method, $prop, $value);
			
		}else{
			
			$data["form"] = $this->name;
			$data["name"] = $field;
			$data["method"] = $method;
			$data["title"] = $field;
			$data[$prop] = $value;

			$data["__form_mode"] = 1;
			$data["__form_record"] = "";
			
			$r = $this->saveRequest($this->t_form_fields, $data);			

		}// end if
		return $r;
	}// end funtion 

	private function inputData($q){

		return array_merge(array(array('','','')), $this->getDataQuery($q));
		
		$cn = &$this->cns;
		$cn->query = $q;
		
		$data[] = array("", "", "");
		$result = $cn->execute();
		while($rs = $cn->getData($result)){
			$data[] = array($rs[0], $rs[1], "");
		}// end while
		return $data;
		
	}// end funtion 
	
	private function listForm(){
		$cn = &$this->cns;
		$cn->query = "
			SELECT a.form, a.form, a.title 
			FROM $this->t_forms as a
			WHERE a.form !=''
			ORDER BY form
			";
		
		$data = array();
		$result = $cn->execute();
		while($rs = $cn->getData($result)){
			$data[] = array('value' => $rs[0], 'text' => $rs[1], 'parent' => '');
		}// end while
		return $data;
		
	}// end funtion 	

	private function fields_title($method = ""){
		$cn = &$this->cns;
		$cn->query = "
			SELECT name, title
			FROM $this->t_form_fields
			WHERE form = '$this->name' AND method = '$method'
			ORDER BY title
			";

		$result = $cn->execute();
		$titles = array();
		while($rs = $cn->getData($result)){
			$titles[$rs["name"]] = $rs["title"];
			
		}// end while
		return $titles;
		
	}// end funtion 	

	private function getDataFromQuery($q){
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

	private function method_data($name, $method){
		$cn = &$this->cns;
		$cn->query = "
			SELECT *
			FROM $this->t_ele_met
			WHERE method = '$method' AND name = '$name' AND element = '$this->element' 

			";
		
		$result = $cn->execute();
		if($data = $cn->getData($result)){
			$data["_mode"] = 2;
			return $data;
		}else{
			$fields = $cn->fieldsName($result);
			
			foreach($fields as $f){
				$data[$f->name] = "";
			}// next

			$data["_mode"] = 1;
			$data["form"] = $this->name;
			return $data;
		}// end while
		
		
	}// end funtion 	

	private function field_params($field, $method){
		$cn = &$this->cns;
		$cn->query = "
			SELECT *
			FROM $this->t_form_fields
			WHERE form = '$this->name' AND name = '$field' AND method = '$method'
			ORDER BY form
			";
		
		$result = $cn->execute();
		if($data = $cn->getData($result)){
			$data["_mode"] = 2;
			return $data;
		}else{
			$fields = $cn->fieldsName($result);
			
			foreach($fields as $f){
				$data[$f] = "";
			}// next

			$data["_mode"] = 1;
			$data["form"] = $this->name;
			$data["field"] = $field;

			return $data;
		}// end while
		
		
	}// end funtion 	


	private function loadEleMethod($method){


		$query = "
			SELECT *
			FROM $this->t_ele_met
			WHERE method = '$method' AND name = '$this->name' AND element = '$this->element'";

		$data = $this->getDataFromQuery($query);


		$f = new svForm(array("name"=>"sgpanel_".$this->panel));
		$f->setMain(new sgHTML(""));
		$f->subForm = true;
		$f->setRef($this->getRef());
		$f->setClasses("fds");
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
				"params"=>"panel:$this->panel;element:$this->element;method:load_ele_method;ele_method:{=__ele_method};"
			));

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
				"params"=>"panel:$this->panel;element:$this->element;method:save_ele_method;ele_method:{=__ele_method};"
			));


		$div = new sgHTML("div");
		$div->id = $this->elemMethodId;
		$div->class = $this->class_d_page;
		
		
		$menu->innerHTML = $text->render().$btn->render().$btnSave->render();
		return $menu->render().$div->render();		
		

	}// end function 

	private function tabInput($data){
		global $seq;

		$f = new svForm(array("name"=>"sgpanel_".$this->panel));
		$f->subForm = true;
		$f->setRef($this->getRef());
		$f->setClasses("fds");
		$f->setMain(new sgHTML(""));
		$f->addPage("1","");
		
		$list[] = array("", "", "");
		foreach($seq->clsInput as $k => $v){
			$list[] = array($k, $k, "");
		}// next
		$list2 = array();
		$list2[] = array("0", "Default", "");
		$list2[] = array("1", "Expresions", "");
		$list2[] = array("2", "Fixed", "");
		$list2[] = array("3", "Necessary", "");

		$f->addField("select", "f_input", "input", $data["input"], $list);
		$f->addField("textarea", "f_data_values", "data_values:", $data["data_values"]);
		$f->addField("textarea", "f_config", "config:", $data["config"]);

		$f->addField("select", "f_value_ini_type", "value_ini_type:", $data["value_ini_type"], $list2);
		$f->addField("textarea", "f_value_ini", "value_ini:", $data["value_ini"]);
		$f->addField("text", "f_class", "class:", $data["class"]);
		
		//$html = $f->render();
		
		
		$this->setFragment($this->fieldTabId1, $f->render(), $f->getScript());
		
		
	}// end function 

	private function tabBasic($data, $field, $method){
		
		$f = new svForm(array("name"=>"sgpanel_".$this->panel));
		$f->subForm = true;
		$f->setRef($this->getRef());
		$f->setClasses("fds");
		$f->setMain(new sgHTML(""));
		$f->addPage("1","");		
		
		$list[] = array("", "", "");
		$list[] = array("0", "NO", "");
		$list[] = array("1", "Yes", "");		
		
		$f->addField("text", "f_form", "form:", $this->name);
		$f->addField("text", "f_name", "name:", $field);
		$f->addField("text", "f_method", "method:", $method);
		$f->addField("text", "f_title", "title:", $data["title"]);

		$f->addField("textarea", "f_subform", "subform:", $data["subform"]);

		
		$f->addField("textarea", "f_events", "events:", $data["events"]);
		$f->addField("select", "f_aux", "aux:", $data["aux"], $list);
		$f->addField("select", "f_html", "html:", $data["html"], $list);
		
		$f->addField("textarea", "f_style", "style:", $data["style"]);
		$f->addField("textarea", "f_propertys", "propertys:", $data["propertys"]);
		$f->addField("textarea", "f_title_style", "title_style:", $data["title_style"]);
		$f->addField("textarea", "f_title_propertys", "title_propertys:", $data["title_propertys"]);
		$f->addField("textarea", "f_info", "info:", $data["info"]);


		$record = "form=$this->name,name=$field,method=$method";
		$mode = 4;

		$f->addField("hidden", "f___form_mode", "mode", $mode);
		$f->addField("hidden", "f___form_record", "record", $record);


		$this->setFragment($this->fieldTabId2, $f->render(), $f->getScript());
		//$this->script .= ;

		
		
	}// end function 

	private function tabParams($value){
		
		global $seq;

		$f = new svForm(array("name"=>"sgpanel_".$this->panel));
		$f->subForm = true;
		$f->setRef($this->getRef());
		$f->setClasses("fds");
		$f->setMain(new sgHTML(""));
		$f->addPage("1","");


		
		$data1 = '"":""';
		
		
		foreach($seq->clsInput as $k => $v){
			$data1 .= (($data1!="")?", ":"")."\"$k\":\"$k\"";
		}// next

		$field = $f->addField("params", "f_params", "");
		
		$field->config["classC3"] = "'$this->class_d_page'"; 
		$field->panel = $this->panel;
		$params["ele_params"] = json_decode('[

			{"name":"name","title":"name","type":"t", "value":""},
			{"name":"title","title":"title","type":"t", "value":""},
			{"name":"input","title":"input","type":"s", "value":"", "data":{'.$data1.'}},
			{"name":"data_values","title":"data_values","type":"b", "value":""},
			{"name":"config","title":"config","type":"b", "value":""},
			{"name":"value_ini_type","title":"value_ini_type","type":"s", "value":"", 
				"data":{"0":"Default", "1":"Expresions", "2":"Fixed"}},
			{"name":"value_ini","title":"value_ini","type":"b", "value":""},
			{"name":"events","title":"events","type":"b", "value":""},
			{"name":"aux","title":"aux","type":"s", "value":"", "data":{"":"", "0":"N", "1":"Y"}},
			{"name":"html","title":"html","type":"s", "value":"", "data":{"":"", "0":"N", "1":"Y"}},
			{"name":"style","title":"style","type":"b", "value":""},
			{"name":"propertys","title":"propertys","type":"b", "value":""},
			
			{"name":"title_style","title":"title_style","type":"b", "value":""},
			{"name":"title_propertys","title":"title_propertys","type":"b", "value":""},
			{"name":"rows_style","title":"rows_style","type":"b", "value":""},
			{"name":"rows_propertys","title":"rows_propertys","type":"b", "value":""},
			{"name":"info","title":"info","type":"b", "value":""}]
		
			');

		$field->input->setParams($params);
		
			
		$field->input->id = $field->input->name."_".$this->panel;
		$field->input->value = $value;
		$field->input->class = "mitext";
		$field->input->mode = 1;

		$this->setFragment($this->fieldTabId3, $f->render(), $f->getScript());
		//$this->script .= ;		
		
		
	}// end function 

	private function tabValid($value){

		$f = new svForm(array("name"=>"sgpanel_".$this->panel));
		$f->subForm = true;
		$f->setRef($this->getRef());
		$f->setClasses("fds");
		$f->setMain(new sgHTML(""));
		$f->addPage("1","");


		
		$data1 = '"":""';



		$field = $f->addField("valid", "f_valid", "Valid", $value);
		
		

		$this->setFragment($this->fieldTabId4, $f->render(), $f->getScript());
		//$this->script .= ;
		

	}// end function 

	private function loadFieldParams($field, $method){
		$query = "
			SELECT *
			FROM $this->t_form_fields
			WHERE form = '$this->name' AND name = '$field' AND method = '$method'
			ORDER BY form";

		$data = $this->getDataFromQuery($query);		
		
		$this->tabInput($data);
		$this->tabBasic($data, $field, $method);
		$this->tabParams($data["params"]);
		$this->tabValid($data["valid"]);
		//$this->setFragment($field, $this->fieldDivId);
		
		//$this->setFragment($this->tabInput($data), $this->fieldTabId1);
		//$this->setFragment($this->tabBasic($data), $this->fieldTabId2);
		//$this->setFragment($this->tabParams($data["params"]), $this->fieldTabId3);
		//$this->setFragment($this->tabValid($data["valid"]), $this->fieldTabId4);
		
	}// end function

	private function loadFields($method){

		$this->setFragment($this->fieldsId, $this->_configField($method), "");
		
	}// end function

	private function saveRequest($table, $data, $pre = ""){
		global $seq;
		
		$s = new sgFormSave;
		$s->cn = &$this->cns;
		$s->setPrefix($pre);
		$s->infoTable($table);
		$r = $s->save($data);
		//hr(print_r($r, true));
		hr($r);
		return $r;
	}// end function

	private function saveFormYYY($form){
hr(1111);
		global $seq;
		
		$req = $seq->getVarReq();
		
		$cn = &$this->cns;
		$cn->query = "SELECT * FROM $this->t_forms WHERE form='$this->name'";
		$result = $cn->execute();

		if($data = $cn->getDataAssoc($result)){

			foreach($data as $k => $v){
				
				if(!isset($req["d_".$k])  ){
					continue;
				}
				$value = $req["d_".$k];
				$value = $cn->addSlashes($value);
				$k = $cn->addQuotes($k);
				$q_set[] = "$k ='$value'";
				
			
			}
			
			
			$value = $cn->addSlashes($value);
			
			$q = "UPDATE $this->t_forms SET ". implode(", ", $q_set). " WHERE form='$this->name'";
		}else{
			$fields = $cn->fieldsName($result);


			foreach($fields as $f){
				
				if(!isset($req["d_".$f])  ){
					continue;
				}
				$value = $req["d_".$f];
				$value = $cn->addSlashes($value);


				$q_fields[] = $cn->addQuotes($f);;
				$q_values[] = "'$value'";
			}
			$q = "INSERT INTO $this->t_forms (".implode(", ",$q_fields).") VALUES (".implode(", ",$q_values).");";

		}// end while
		
		$cn->query = $q;
		$result = $cn->execute();

		
		
		
	}// end funtion 

	private function saveFormMethodXXX($method){

		global $seq;
		
		$req = $seq->getVarReq();
		
		$cn = &$this->cns;
		$cn->query = "SELECT * FROM cfg_ele_met WHERE method = '$method' AND name = '$this->name' AND element = '$this->element' ";
		$result = $cn->execute();

		if($data = $cn->getDataAssoc($result)){

			foreach($data as $k => $v){
				
				if(!isset($req["m_".$k])  ){
					continue;
				}
				$value = $req["m_".$k];
				$value = $cn->addSlashes($value);
				$q_set[] = "`$k` ='$value'";
				
			
			}
			
			
			$value = $cn->addSlashes($value);
			
			$q = "UPDATE cfg_ele_met SET ". implode(", ", $q_set). " WHERE method = '$method' AND name = '$this->name' AND element = '$this->element'";
		}else{
			$fields = $cn->fieldsName($result);


			foreach($fields as $k => $f){
				if(!isset($req["m_".$f->name])  ){
					continue;
				}
				$value = $req["m_".$f->name];
				$value = $cn->addSlashes($value);


				$q_fields[] = "`$f->name`";
				$q_values[] = "'$value'";
			}
			$q = "INSERT INTO cfg_ele_met (".implode(", ",$q_fields).") VALUES (".implode(", ",$q_values).");";

		}// end while
		
		$cn->query = $q;
		$result = $cn->execute();

		
		
		
	}// end funtion 

	private function saveField($form, $field, $method){

		global $seq;
		
		$req = $seq->getVarReq();
		
		$cn = &$this->cns;
		$cn->query = "SELECT * FROM $this->t_form_fields WHERE `form`='$this->name' AND `name`='$field' AND method = '$method'";
		$result = $cn->execute();

		if($data = $cn->getDataAssoc($result)){

			foreach($data as $k => $v){
				
				if(!isset($req["f_".$k])  ){
					continue;
				}
				$value = $req["f_".$k];
				$value = $cn->addSlashes($value);
				$q_set[] = "`$k` ='$value'";
				
			
			}
			
			
			$value = $cn->addSlashes($value);
			
			$q = "UPDATE $this->t_form_fields SET ". implode(", ", $q_set). " WHERE `form`='$this->name' AND `name`='$field' AND method = '$method'";
		}else{
			$fields = $cn->fieldsName($result);


			foreach($fields as $k => $f){
				if(!isset($req["f_".$f->name])  ){
					continue;
				}
				$value = $req["f_".$f->name];
				$value = $cn->addSlashes($value);


				$q_fields[] = "`$f->name`";
				$q_values[] = "'$value'";
			}
			$q = "INSERT INTO $this->t_form_fields (".implode(", ",$q_fields).") VALUES (".implode(", ",$q_values).");";

		}// end while
		echo($q);
		$cn->query = $q;
		$result = $cn->execute();

		
		
		
	}// end funtion 

	private function fieldsMethod($query, $method){
		global $seq;

		$cn = &$this->cnn;
		
		$query = $seq->cmd->evalVar($query);

		$query = $cn->metaFunctions($query);
		
		
		
		if(!($info = $cn->infoQuery($seq->cmd->evalVar($query), true))){
			return new stdClass;
		}
		$fields = $info->fields;
		
		$cn = &$this->cns;
		$cn->query = "
				SELECT name, title, params, valid 
				FROM $this->t_form_fields as a 
				WHERE method = '$method' AND form = '$this->form'";
					
		
		$result = $cn->execute($cn->query);
		
		while($rs = $cn->getDataAssoc($result)){
			
			if(!isset($fields[$rs["name"]])){
				continue;
			}// end if
			
			$field = &$fields[$rs["name"]];

			foreach($rs as $k => $v){
				$field->$k = $v;
			}// next

			if($prop = $seq->get_param($field->params)){
				foreach($prop as $k => $v){
					$field->$k = $v;
				}// next
			}// end if

			if($prop = $seq->get_param($field->valid)){
				foreach($prop as $k => $v){
					$field->rules[$k] = $v;
				}// next
			}// end if
			$field->check = true;
		}// end if
		return $fields;
	}// end fucntion

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

	private function setFieldProperty($field, $method, $prop, $value){

		global $seq;
		
		$data[$prop] = urldecode($value);
		$data["__form_mode"] = "2";
		$data["__form_record"] = "form=$this->name,name=$field,method=$method";

		$s = new sgFormSave;
		$s->cn = &$this->cns;
		$prop = $s->cn->addQuotes($prop);
		$s->infoQuery("SELECT $prop FROM $this->t_form_fields");

		return $s->save($data);

	}// end funtion 

	
	private function scriptMethods(){
		$cn = &$this->cns;
		
		$q = "SELECT method, method, '' FROM $this->t_ele_met WHERE name = '$this->name' AND element = '$this->element'";
			
		return $cn->aDataScript($q, "_DD");
		
	}// end function

	private function scriptFieldMethods(){
		$cn = &$this->cns;
		
		$q = "SELECT method, method, '' FROM $this->t_form_fields WHERE form = '$this->name' AND method != '' GROUP BY method";
			
		return $cn->aDataScript($q, "_DD");
		
	}// end function


	private function dataScript($q, $name = "_DD"){
		$cn = &$this->cns;
		
		$json = $cn->aDataJson($q, $name);
		
		return "\n\t".$name." = $json;";
		
	}// end function



}// end class





?>