<?php
include("cfg_form.php");
include("cls_paginator.php");


class cls_form extends cfg_form{

	public $class_main = "";
	public $class_caption = "";
	public $class_body = "";
	public $class_group_caption = "";
	public $class_group = "";
	public $class_row = "";
	public $class_cell_title = "";
	public $class_cell_input = "";
	public $class_required = "";
	public $class_input = "";
	public $class_search = "";
	public $class_grid = "";

	public $form_type = "grid";

	public $type_caption = 1;
	public $type_search = 1;
	public $type_group = 0;
	public $type_filter = 0;
	public $type_enum = 2;
	public $type_selection = 1;
	public $type_pagination = 1;
	public $type_foot = 1;
	public $grid_rows = 10;
	public $grid_pag_max = 6;
	

	public $type_form = 1;
	public $type_grid = 1;

	
	public $template = "";
	public $template_panel = "";
	
	//public $page = 1;
	public $q_search = "";
	public $q_search_exact  = false;
	
	public $record_from = false;
	public $mode = false;
	public $menu = "";
	public $type = 1;
	
	public $html ="";
	public $script ="";

	public $grid_border = "2";
	public $grid_cellspacing = "2";
	public $grid_cellpadding = "2";

	public $request_element = "";
	public $request_panel = "";

	public $typeRecord = "normal";
	
	public $func = false;
	
	private $_function ="";

	public $with_data = false;

	public function evalMethod($method=""){
			
		global $seq;
	
		$this->log();
		$this->log(array(
			"element"=>$this->element,
			"title"=>&$this->title,
			"name"=>$this->name,
			"method"=>$method,
			"panel"=>$this->panel
		));		
		
		
		$seq->setVarRec(array());
		
		$this->evalParam();	
		
		switch($method){
		case "save":
		
			$this->execute($this->name, $method);
			
			foreach($this->fields as $field){
				
				if($field->subform){
					
					//hr($field->config);
					//$field->subForm = $this->getSubForm2($field->cfg_input, "f".$field->position."_", "_i");
					$field->subForm = $this->getSubForm2($field->cfg_input, $field->name."_", "_i");
					
					
				}
					
			}
			
			if($this->typeRecord == "multi"){
				
				$multi = true; 	
			}else{
				
				$multi = false; 	
			}

			if($this->mode){
				
				$seq->cmd->v->req["__form_mode"] = $this->mode;
			}
			$s = new sgFormSave(array(
				"fields"=>$this->fields,
				"tables"=>$this->tables,
				"keys"=>$this->tKeys,
				"cn"=>$this->cns,
				"data"=>&$seq->cmd->v->req,
				"data_suffix"=>($multi)?"_i":"",
				"multi"=>$multi
			
			));
			
			$result = $s->log;
			if($s->error == 0){
				if(isset($result->records)){
					foreach($result->records as $k => $v){
						
						//$seq->setVar($k, $v);
						$seq->setExp($k, $v);
					}
					

					
				}
				
			}
			//hr($result);
			break;			
			
		case "request":
			$this->render = 1;
			$this->mode = 1;
			$this->func = 1;
			$this->with_data = false;
			
			$this->execute($this->name, $method);

			if($this->with_data){
				
				$this->data = $this->getData2($this->record);
				
			}else{
				//$this->data = $this->getData2("");	
			}
			
			if($this->record_from){
				$this->data = $this->seq->v->req[$this->record_from];
			}else if($this->mode == 2){
				//$this->get_data("cedula4=12474737,codric2=5");
			}// end if
		
		
			$seq->setVarRec($this->data);

			$this->showForm();
			//$this->create_menu();
			//$this->html .= $this->emenu->html;
			//$this->script .= $this->emenu->script;
			
			
				
			break;
		case "requestfrom":
			break;
		case "load":
			$this->render = 1;
			$this->mode = 2;

			$this->execute($this->name, $method);
			$this->with_data = true;

			if($this->with_data){
				$this->data = $this->getData2($this->record);
			}else{
				//$this->data = $this->getData2("");	
			}
			
			if($this->record_from){
				$this->data = $this->seq->v->req[$this->record_from];
			}else if($this->mode == 2){
				//$this->get_data("cedula4=12474737,codric2=5");
			}// end if
		
		
			$seq->setVarRec($this->data);

			$this->showForm();
			//$this->create_menu();
			//$this->html .= $this->emenu->html;
			//$this->script .= $this->emenu->script;
			
			
			
			break;
		case "list":
			$this->execute($this->name, $method);
			$this->showGrid();
			break;
			
		case "delete":
			$this->execute($this->name, $method);
			$seq->cmd->v->req["__form_mode"] = "3";
			$s = new sgFormSave(array(
				"fields"=>$this->fields,
				"tables"=>$this->tables,
				"keys"=>$this->tKeys,
				"cn"=>$this->cns,
				"data"=>$seq->cmd->v->req
			
			));		
			break;
		case "multi":
			$this->execute($this->name, $method);
			
			if($this->subElement){
				$seq->setVarRec($this->masterData);
			}else{
				$seq->setVarRec($this->data);
				
			}
			
			$this->multiRecord();
			
			break;
		case "list_set":
			$this->execute($this->name, $method);
			
			$this->listSet();
			
			break;
		case "load_record":
			$seq->setVarRec($seq->cmd->v->req);
			
			$this->execute($this->name, $method);
			$field = $this->cfgField($this->name, $method, $this->eparams["field_name"]);
			
			
			
			//$data = $this->getSimpleData($field->q_record);
			$data = $this->getSimpleRecord($field->q_record);
			
			/*
			
			$a = array();
			if(count($data) > 0){
				foreach($data as $k => $idata){
					$a[] = array_map(function($v){return is_string($v)? utf8_encode($v): $v;}, $idata);
				}// next
			}// end if	*/	
			//$json = json_encode(array_map(function($v){return is_string($v)? utf8_encode($v): $v;}, $data));
			$json = json_encode($data);
			$script = $this->getRef().".setValue($json);";
	
			$this->setFragment("","",$script);
			
		
			break;	
		case "get_data":
		
			//$this->masterData = &$seq->cmd->v->req;
			$seq->setVarRec($seq->cmd->v->req);
			$this->execute($this->name, $method);
			
			
			

			$data = $this->getDataField($this->eparams["name"],$this->eparams["method_ref"], $this->eparams["field_name"]);
					
			
			/*
			$a = array();
			if(count($data) > 0){
				foreach($data as $k => $idata){
					$a[] = array_map(function($v){return is_string($v)? utf8_encode($v): $v;}, $idata);
					//$a[] = array_map(function($v){return is_string($v)? utf8_encode($v): $v;}, $idata);
				}// next
			}// end if		
			*/
			$json = json_encode($data);
			$script = $this->getRef().".get('".$this->eparams["field_name"]."').setData($json);";
	
			$this->setFragment("","",$script);
			
			break;
		case "save_old":
		
			$this->execute($this->name, $method);
			
			foreach($this->fields as $field){
				
				if($field->subform){
					//hr("....->".$field->name);
					//hr($field->config);
					//$field->subForm = $this->getSubForm2($field->cfg_input, "f".$field->position."_", "_i");
					$field->subForm = $this->getSubForm2($field->cfg_input, $field->name."_", "_i");
					
					
				}
					
			}
			
			if($this->typeRecord == "multi"){
				
				$multi = true; 	
			}else{
				
				$multi = false; 	
			}

			if($this->mode){
				
				$seq->cmd->v->req["__form_mode"] = $this->mode;
			}
			$s = new sgFormSave(array(
				"fields"=>$this->fields,
				"tables"=>$this->tables,
				"keys"=>$this->tKeys,
				"cn"=>$this->cns,
				"data"=>&$seq->cmd->v->req,
				"data_suffix"=>"_i",
				"multi"=>$multi
			
			));
			
			$result = $s->log;
			if($s->error == 0){
				if(isset($result->records)){
					foreach($result->records as $k => $v){
						
						//$seq->setVar($k, $v);
						$seq->setExp($k, $v);
					}
					

					
				}
				
			}
			hr($result);
			break;
			
			
			$mm=array();
			
			$mm[] = array(
				"__form_mode"=>"1",
				"__form_record"=>"",
				"etnia"=>"yanomami"
			
			);


			$mm2[] = array(
				"__form_mode"=>"1",
				"__form_record"=>"",
				"etnia"=>"Polo 3",
				"codetnia"=>"",
				"codetnia2"=>"",
				"pais"=>"venezuela",
				"x"=>"yanny"
				
			
			);
			
			
			$n[] = array(
				"__form_mode"=>"1",
				"__form_record"=>"",
				"codetnia"=>"",
				"pais"=>"A");

			$n[] = array(
				"__form_mode"=>"1",
				"__form_record"=>"",
				"codetnia"=>"",
				"pais"=>"B");
			$n[] = array(
				"__form_mode"=>"1",
				"__form_record"=>"",
				"codetnia"=>"",
				"pais"=>"C");
			$n[] = array(
				"__form_mode"=>"1",
				"__form_record"=>"",
				"codetnia"=>"",
				"pais"=>"D");


			
			$mm3[] = array(
				"__form_mode"=>"1",
				"__form_record"=>"",
				"etnia"=>"Diez",
				"codetnia"=>"",
				'x'=>$n
				
			
			);			
			
			
				$k = new sgFormSave(array(
				"query"=>"select etnias.*, '' as x 
					from etnias",
				"cn"=>$this->cns,
				//"data"=>&$mm
				
			
			));		
			
			//$k->fields["codetnia2"]->ref_value="codetnia";
			$k->fields["x"]->subform=array(
				"table"=>"etnia_pais",
				"_fields"=>array(
					"codetnia"=>array(
						"master_value"=>"codetnia"
					)
				
				)
				
				
			
			);
			
			print_r($k->save($mm3));
			break;
			
			
			$s = new sgFormSave(array(
				"fields"=>$this->fields,
				"tables"=>$this->tables,
				"keys"=>$this->tKeys,
				"cn"=>$this->cns,
				"data"=>$seq->cmd->v->req
			
			));
			
			//print_r($s->log);
			break;
			
			
			$s = new sgFormSave();
			$s->cn = &$this->cns;
			
			
			
			foreach($this->_subForm as $form){
				
				$f = new cfg_form();
				$f->execute($form, "");

				$s->setFields($f->fields);
				$s->setTables($f->tables);
				$s->setKeys($f->tKeys);

				
				$s->addForm($s);	
				
			}		
			
			//print_r($this->fields);
			
			$s->setFields($this->fields);
			$s->setTables($this->tables);
			$s->setKeys($this->tKeys);
			
			
			$s->save($seq->cmd->v->req);
			break;
					
			if($n = $seq->getReq("__form_multi")){
				
				$data = array();
				for($i=0;$i<$n;$i++){
					
					for($x=0; $x < $this->fields_count; $x++){
						//foreach($this->fields as $k => $v)
						
						$data[$this->fields[$x]->name] = $seq->getReq($this->fields[$x]->name."_".$i);
						
					}// next
					$data["__form_mode"] = $seq->getReq("__form_mode_".$i);
					$data["__form_record"] = $seq->getReq("__form_record_".$i);
					
					
					
					$this->save($data);

					
				}// next
			}else{
				
				
				
				
				break;
				$this->eparams["record"] = $this->save($seq->cmd->v->req);
				
				$seq->setExp("__form_record", $this->eparams["record"]);
				
				
				foreach($this->_subForm as $k => $v){
					
					$seq->setVarRec($seq->cmd->v->req);
					
					$f = $this->subForm($this->fields[$k]->sf, $this->fields[$k]->sf_params);
					

					if($f->method == "list_set"){
						$f->eparams["list_init"] = $seq->cmd->v->req[$k."_list_init"];
						$f->eparams["list_new"] = $seq->cmd->v->req[$k];
						$f->eparams["list_filters"] = $seq->cmd->v->req[$k."_list_filt"];
						
						
						$f->evalMethod("save_list");	
						
					}
					
					
				}				
				
			}// end if
			break;	
		default:

			if(!$this->extraMethod($method)){
				
				$this->render = 1;				
				
			}


			break;			
		}// end switch	

		if($this->type!=0 and $this->render != 0){

			//$this->html = $this->getDinamicPanel($this->html);
		}// end if
		

		if(isset($this->isigns[$this->method])){
			$this->_signs[] = $this->isigns[$method];
		}else{
			$this->_signs[] = $this->name."_".$method;
		}// end if	
		$this->_signs[] = "save";
	}// end function
	
	public function showForm(){

		global $seq;
		
		$f = new svForm(array("name"=>"sgpanel_".$this->panel));
		$f->setRef($this->getRef());
		
		
		$f->setClasses($this->class);
		$f->setCaption($this->title);
		
		//$f->addPage("1", "");
		//$f->addTab("ss");
		//$f->addTabPage("1+");
		$f->addPage("1", false);
		//$f->addTable("1");
		//print_r($this->fields);
		
		
		
		$mode = $this->data["__form_mode"];
		$ini_tab = false;
		foreach($this->fields as $i => $field){

			if(isset($this->iTabs[$i])){
				
				if(!$ini_tab){
					
					$f->addTab("_tab_$this->panel");
					$ini_tab = true;
				}
				
				
				$f->addTabPage($this->iTabs[$i]);
				$f->addPage("1", false);
			}
			
			if(isset($this->igroups[$i])){
				
				$f->addPage($i, $this->igroups[$i]);
			}			
			


			if($field->data_values != ""){
				$field->data = $this->evalSequence($field->data_values);
			}// end if
			
			$opt = $field->cfg_input;

		
			$opt["type"] = $field->input;
			$opt["name"] = $field->name;
			$opt["id"] = $field->name."_p".$this->panel;
			$opt["title"] = $field->title;
			
			if(isset($field->rules["required"])){
				$opt["required"] = true;
				
			}
			
			
			
			
			if(isset($field->mode)){
				
				$event = svAction::send(
					array(
						"async"=>true,
						"panel"=>$this->panel,
						"params"=>"set_method:'panel:$this->panel;name:$this->name;element:$this->element;method:load_record;field_name:$field->name;';"
					));
				
				if(isset($field->event["onchange"])){
					
					$field->event["onchange"] .= ";".$event;
				}else{
					//hr($field->name);
					$field->event["onchange"] = $event;
				}
				
				
			}
			//hr("$field->name, $field->value_ini_type, ".$this->data[$i]);
			switch($field->value_ini_type){
				case 0:
					break;
				case 1:
					break;
				case 2:
					
					$this->data[$i] = $field->default;
					
					
				
					break;
				case 3:
					if(!isset($this->data[$i]) or $this->data[$i] == ""){
						$this->data[$i] = $field->default;
						
					}
				
				
					break;
				
				
			}
			
			
			$opt["value"] = &$this->data[$i];
			$opt["data"] = $field->data;
			$opt["childs"] = $field->childs;
			$opt["parent"] = $field->parent;
			$opt["panel"] = $this->panel;
			$opt["rules"] = $field->rules;
			$opt["events"] = $field->event;
			
			$opt["propertys"] = $seq->get_param($field->propertys);
			
			$opt["position"] = $field->position;

		
			
			$opt["masterData"] = &$this->data;
			
				//hr($field->name,"pink");
			//hr($opt);		
			
			$inp = $f->addField($opt);

			
		}// next
		
		//print_r($f->fields);

		//$f->addField("hidden", "__form_record", false, $this->data["__form_record"]);
		//$f->addField("hidden", "__form_mode", false, $this->data["__form_mode"]);
		
		$f->setMain();
		
		$f->appendChild($this->newMenu($this->menu));
		
		$this->html = $f->render().$this->formVars();
		$this->script = $f->getScript();

	
		
		//$this->script .= "\n\t$this->ref.init();";		
		
		
	}
	
	public function multiRecord(){
		
		
		
//$this->fieldsPrefix = "";
	
/*		
		$q = "SELECT p.coddato as __check, dato as __check_title, p.*,d.coddato

				FROM datos as d
				LEFT JOIN prueba_datos as p ON p.coddato = d.coddato AND p.codpersona='&codpersona'";


		$q = "
			SELECT p.coddato as __check, dato as __check_title, p.*,d.coddato

			FROM datos as d
			LEFT JOIN prueba_datos as p ON p.coddato = d.coddato AND @EQ_NUM(p.codpersona, '&codpersona')";
*/
		$this->pagination = false;
		$data = $this->multiData($this->q_data);		

		$g = new xyGrid(array(
			"panel"=>$this->panel,
			"name"=>$this->name,
			"id"=>$this->name."_".$this->panel,
			"caption"=>$this->title,
			"typeEnum"=>0,
			"typeSelect"=>2,
			"cols"=>0+0,
			"type"=>"check_list",
			"formName"=>"sgpanel_".$this->panel,
			"typeSearch"=>0,
			"page"=>$this->page,
			"record_page"=>$this->recordCount,
			"num_pages"=>$this->pageCount,
			"fieldsPrefix"=>(isset($this->fieldsPrefix))?$this->fieldsPrefix:""
			
		));		
		
		$g->setRef($this->getRef());
		
		foreach($this->fields as $field){
			
			if($field->data_values != ""){
				$field->data = $this->evalSequence($field->data_values);
			}// end if			
			
			
			$g->setColumn(
				array(
					"type"=>$field->input,
					"name"=>$field->name,
					"title"=>$field->title,
					"default_value"=>$field->value_default,
					"rules"=>$field->rules,
					"parent"=>$field->parent,
					"childs"=>$field->childs,
					"data"=>array(),
					"params"=>array(),
					"hide"=>$field->hide,
					"value"=>$field->value
				)
			);
			
		}
		
		$g->setTitle2();

		
		foreach($data as $rs){
			if(isset($rs['__check']) and $rs['__check']){
				$checked = true;
			}else{
				$checked = false;
			}

			foreach($this->fields as $field){
				
				switch($field->value_ini_type){
					case 0:
						break;
					case 1:
						if(!$checked){
							$rs[$field->name] = $field->default;
						}					
						break;
					case 2:
						
							$rs[$field->name] = $field->default;
						
					
					
						break;
					case 3:
						if(!isset($rs[$field->name]) or $rs[$field->name] == ""){
							$rs[$field->name] = $field->default;
							
						}
					
					
						break;
					
					
				}			
				
				
			}
			

			if(isset($rs['__check_title'])){
				$chkTitle = $rs['__check_title'];
			}else{
				$chkTitle = false;
			}

			$g->addRow($rs, $checked, $chkTitle);
		}// next



		
		
		$g->appendChild($this->newMenu($this->menu));
		
	
		$this->html = $g->render();
		$this->script = $g->getScript();		
			
	}

	


	public function listSet(){

	
/*		
		$q = "SELECT p.coddato as __check, dato as __check_title, p.*,d.coddato

				FROM datos as d
				LEFT JOIN prueba_datos as p ON p.coddato = d.coddato AND p.codpersona='&codpersona'";


		$q = "
			SELECT p.coddato as __check, dato as __check_title, p.*,d.coddato

			FROM datos as d
			LEFT JOIN prueba_datos as p ON p.coddato = d.coddato AND @EQ_NUM(p.codpersona, '&codpersona')";
*/
		
		$data = $this->multiData($this->q_data);		

		$g = new listGrid(array(
			"panel"=>$this->panel,
			"name"=>$this->name,
			"id"=>$this->name."_".$this->panel,
			"caption"=>$this->title,
			"typeEnum"=>0,
			"typeSelect"=>2,
			"cols"=>1,
			"type"=>"list_set",
			"formName"=>"sgpanel_".$this->panel,
			"typeSearch"=>0,
			
			"fieldsPrefix"=>$this->fieldsPrefix
			
		));		
		
		$g->setRef($this->getRef());
		
		

		
		foreach($data as $rs){
			if(isset($rs['__check']) and $rs['__check']){
				$checked = true;
			}else{
				$checked = false;
			}

			if(isset($rs['__check_title'])){
				$chkTitle = $rs['__check_title'];
			}else{
				$chkTitle = false;
			}

			$g->addRow($rs, $checked, $chkTitle);
		}// next



		
		
		$g->appendChild($this->newMenu($this->menu));
		
	
		$this->html = $g->render();
		$this->script = $g->getScript();		
			
	}
	
	public function multiRecord1(){

		
		$f = new svForm(array("name"=>"sgpanel_".$this->panel));
		$f->setRef($this->getRef());
		$f->setClasses($this->class);
		$f->setCaption($this->title);

		$f->addPage("1", false);


		$g = $f->addGrid(array(
			"panel"=>$this->panel,
			"name"=>"g",
			"typeEnum"=>0,
			"typeSelect"=>2,
			"cols"=>$this->fields_count+2,
			"type"=>"check_list"
		));		
		
		

		
		$g->setType("multi");
		$g->setRef($this->getRef());
		
		
		foreach($this->fields as $field){
			$g->setColumn(
				array(
					"type"=>$field->input,
					"name"=>$field->name,
					"title"=>$field->title,
					"default_value"=>$field->value_default,
					"rules"=>$field->rules,
					"parent"=>$field->parent,
					"childs"=>$field->childs,
					"data"=>array(),
					"params"=>array(),
					"hide"=>$field->hide,
					"value"=>$field->value
				)
			);
			
		}
		
		$g->setTitle2();

		

		$q = "
			SELECT p.cod_tipo as __check, cat_dato_clinico as __check_title, 1 as id, 45 as cod_paciente, c.cod_categoria as cod_tipo, p.valor, inicio, localizacion
			
			FROM cat_datos_clinicos as c
			LEFT JOIN pac_datos_clinicos as p ON p.cod_tipo = c.cod_categoria AND id=1
			 
			
		";
		

		$cn = &$this->cns;
		$cn->query = $q;
		$result = $cn->execute($q);
		
		
		while($rs = $cn->getData($result)){
			$record="";
			if($rs['__check']){
				foreach($this->keys as $k => $v){
					$record .= (($record!="")?",":"").$k."=".$rs[$v];
				}// next
				$rs["__form_record"] = $record;
				$rs["__form_mode"] = "2";
				$checked = true;
			}else{
				$rs["__form_record"] = $record;
				$rs["__form_mode"] = "0";
				$checked = false;
				
				
			}
			
			$g->addRow($rs, $checked);
			
		}// end while

		$this->html = $f->render();
		$this->script = $f->getScript();

		$this->create_menu();
		$this->html .= $this->emenu->html;
		$this->script .= $this->emenu->script;	
		
		return;
		$f = new svForm(array("name"=>"sgpanel_".$this->panel));
		$f->setRef($this->getRef());
		
		
		$f->setClasses($this->class);
		
		$f->setCaption($this->title);
		
		//$f->addPage("1", "");
		//$f->addTab("ss");
		//$f->addTabPage("1+");
		$f->addPage("1", false);
		
		
		
		$opt["name"]="hola";
		
		$g = $f->addGrid($opt);
		
		$f->setFields = array("cedula", "nombre", "apellidos", "edad");

		$f->newRecord(array("12474737","Yanny","Nuñez","40"));

		$f->newRecord(array("12474737","Yanny","Nuñez","40"));



			$opt["type"] = $field->input;
			$opt["name"] = $field->name;
			$opt["title"] = $field->title;
			$opt["value"] = &$this->data[$i];
			$opt["data"] = $field->data;
			$opt["childs"] = $field->childs;
			$opt["parent"] = $field->parent;
			$opt["panel"] = $this->panel;
			$opt["rules"] = $field->rules;
			$opt["masterData"] = &$this->data;
		
		$f->setGridField($opt);
		
		
		$g = new mmGrid();
		
		
		$g->cols=4;
		
		$g->setHeaderRow($data);


		foreach($data as $d){
			$g->addDataRow($data);
			
			
		}// end while
		
		
		$this->html = $g->render();
		return;
		
		
		
		$f = new svForm(array(
			"name"=>"sgpanel_".$this->panel,
			"class"=>$this->class,
		
		));
		$f->setCaption($this->title);
		
		
		$g = $f->addGrid();
		
		$g->type = "setList";
		$g->typeSelect = "radio";
		$g->typeEnum = "1";
		
		



		$q = "
			SELECT p.cod_tipo as __check, cat_dato_clinico as __check_title, 1 as id, 45 as cod_paciente, c.cod_categoria as cod_tipo, p.valor, inicio, localizacion
			
			FROM cat_datos_clinicos as c
			LEFT JOIN pac_datos_clinicos as p ON p.cod_tipo = c.cod_categoria 
		";
		

		$cn = &$this->cns;
		$cn->query = $q;
		$result = $cn->execute($q);
		
		
		while($rs = $cn->getData($result)){
			
			
			
		}// end while
				
		$this->html = $f->render();
		$this->script = $f->getScript();
		return;	
		
		$f = new svForm(array("name"=>"sgpanel_".$this->panel));
		$f->setRef($this->getRef());
		
		$this->class = "form";
		$f->setClasses($this->class);
		
		$f->setCaption($this->title);
		
		//$f->addPage("1", "");
		//$f->addTab("ss");
		//$f->addTabPage("1+");
			
		
		$q = "SELECT p.cod_tipo as __check, cat_dato_clinico as __check_title, 1 as id, 45 as cod_paciente, c.cod_categoria as cod_tipo, p.valor, inicio, localizacion
		
		FROM cat_datos_clinicos as c
		LEFT JOIN pac_datos_clinicos as p ON p.cod_tipo = c.cod_categoria 
		
		
		";
		

		$cn = &$this->cns;
		$cn->query = $q;
		$result = $cn->execute($q);
		
		$n=0;
		//$f->savePage();
		

		$g = $f->appendChild(new sgGrid(array(
			"typeEnum"=>1,
			"typeSelect"=>1,
			"name"=>$this->name."_".$this->panel,
			"panel"=>$this->panel

		
		)));

		foreach($this->fields as $field){
			$g->setColumn(
				array(
					"type"=>$field->input,
					"name"=>$field->name,
					"title"=>$field->title,
					"default_value"=>$field->value_default,
					"rules"=>$field->rules,
					"parent"=>$field->parent,
					"childs"=>$field->childs,
					"data"=>array(),
					"params"=>array(),
					"hide"=>$field->hide,
					"value"=>$field->value
				)
			);
			
		}
		$g->form=$f;
		$g->setType("basic");
		
		$g->page = $this->page;
		$g->record_page = $this->record_page;
		//$g->num_pages=$this->num_pages;
		
		$g->q = $this->q_search;
		$g->panel = $this->panel;				
		$g->setTitle2();

		while($rs = $cn->getData($result)){
			$f->setMain();
			$f->addPage($n, $rs["__check_title"], true, true,$rs['__check']?"visible":"hidden");
			$suf = "_i{$n}";
			

			$record="";
			if($rs['__check']){
				foreach($this->keys as $k => $v){
					$record .= (($record!="")?",":"").$k."=".$rs[$v];
				}// next
				$rs["__form_record"] = $record;
				$rs["__form_mode"] = "2";
			}else{
				$rs["__form_record"] = $record;
				$rs["__form_mode"] = "1";
				
				
			}

			$g->addRow($rs);
			/*
			foreach($this->fields as $i => $field){
					
	
				if(isset($this->iTabs[$i])){
					//$f->addTabPage($this->iTabs[$i]);
					//$f->addPage("1", false);
				}
				
				
				$opt = $field->cfg_input;
				
				$opt["type"] = $field->input;
				$opt["name"] = $field->name.$suf;
				$opt["title"] = $field->title;
				$opt["value"] = &$rs[$i];
				
				$inp = $f->addField($opt);
			}
			$n++;
			*/
		}// end while

		
		
		
		
		$this->html = $f->render().$this->formVars();
		$this->script = $f->getScript();
		
	

		$this->create_menu();
		$this->html .= $this->emenu->html;
		$this->script .= $this->emenu->script;	
		
	}
	
	public function multiRecord2(){
		
		
		$f = new svForm(array("name"=>"sgpanel_".$this->panel));
		$f->setRef($this->getRef());
		
		$this->class = "form";
		$f->setClasses($this->class);
		
		$f->setCaption($this->title);
		
		//$f->addPage("1", "");
		//$f->addTab("ss");
		//$f->addTabPage("1+");
			
		
		$q = "SELECT p.cod_tipo as __check, cat_dato_clinico as __check_title, 1 as id, 45 as cod_paciente, c.cod_categoria as cod_tipo, p.valor, inicio, localizacion
		
		FROM cat_datos_clinicos as c
		LEFT JOIN pac_datos_clinicos as p ON p.cod_tipo = c.cod_categoria 
		
		
		";
		

		$cn = &$this->cns;
		$cn->query = $q;
		$result = $cn->execute($q);
		
		$n=0;
		//$f->savePage();
		while($rs = $cn->getData($result)){
			$f->setMain();
			$f->addPage($n, $rs["__check_title"], true, true,$rs['__check']?"visible":"hidden");
			$suf = "_i{$n}";
			

			$record="";
			if($rs['__check']){
				foreach($this->keys as $k => $v){
					$record .= (($record!="")?",":"").$k."=".$rs[$v];
				}// next
				$rs["__form_record"] = $record;
				$rs["__form_mode"] = "2";
			}else{
				$rs["__form_record"] = $record;
				$rs["__form_mode"] = "1";
				
				
			}

		
			
			foreach($this->fields as $i => $field){
					
	
				if(isset($this->iTabs[$i])){
					//$f->addTabPage($this->iTabs[$i]);
					//$f->addPage("1", false);
				}
				
				
				$opt = $field->cfg_input;
				
				$opt["type"] = $field->input;
				$opt["name"] = $field->name.$suf;
				$opt["title"] = $field->title;
				$opt["value"] = &$rs[$i];
				
				$inp = $f->addField($opt);
			}
			$n++;
			
		}// end while

		
		
		
		
		$this->html = $f->render().$this->formVars();
		$this->script = $f->getScript();
		
		

			$this->create_menu();
			$this->html .= $this->emenu->html;
			$this->script .= $this->emenu->script;	
		
	}
	
	public function showGrid(){
		
		global $seq;
		//$this->fields_search = "action,title";
		$data = $this->get_adata();
		
		$g = new xyGrid(array(
			"panel"=>$this->panel,
			"name"=>"g",
			"typeEnum"=>0,
			"typeSelect"=>1,
			"cols"=>0,
			"type"=>"basic",
			"formName"=>"sgpanel_".$this->panel,
			"page"=>$this->page,
			"record_page"=>$this->record_page,
			"num_pages"=>$this->num_pages,
		));		
		
		$g->setCaption($this->title);
		
		$g->setClasses($this->class);
		$g->setRef($this->getRef());

		
		$g->q = $this->q_search;
		$g->panel = $this->panel;
		
		foreach($this->fields as $field){

			
			
			$g->setColumn(
				array(
					"type"=>$field->input,
					"name"=>$field->name,
					"title"=>$field->title,
					"default_value"=>$field->value_default,
					"rules"=>$field->rules,
					"parent"=>$field->parent,
					"childs"=>$field->childs,
					"data"=>$field->data,
					"params"=>array(),
					"hide"=>$field->hide,
					"value"=>$field->value
				)
			);
			
		}// next

		$g->setTitle2();

		foreach($data as $d){
			$c =0;
			foreach($d as $k => $v){
				
				if($this->fields[$k]->data_values != ""){
					$seq->setVarRec($d);				
					$g->_col[$c]["data"] = $this->evalSequence($this->fields[$k]->data_values);
				}// end if				
				
				$c++;
			}
			

			
			
			$g->addRow($d);
		}// next

		$g->appendChild($this->newMenu($this->menu));
		$this->html = $g->render();
		$this->script = $g->getScript();		
		
	}// end function
	
	public function evalMethod_($method){
	
		global $seq;
		
		$seq->setVarRec(array());
		
		$this->evalParam();
		$this->initMethod($method);
		
		$this->execute($this->name, $method);
		
		switch($this->_function){
		case "show_form":
			if($this->with_data){
				
				$this->data = $this->getData2($this->record);
			}else{
				//$this->data = $this->getData2("");	
			}
			
			if($this->record_from){
				$this->data = $this->seq->v->req[$this->record_from];
			}else if($this->mode == 2){
				//$this->get_data("cedula4=12474737,codric2=5");
			}// end if
		
		
			$seq->setVarRec($this->data);
		
			
			if($this->type_form==1){

				$this->showForm();
				$this->create_menu();
				$this->html .= $this->emenu->html;
				$this->script .= $this->emenu->script;
				
			}else{
				$this->show_form_pattern();
				$this->create_menu();
				$this->html .= $this->emenu->html;
				$this->script .= $this->emenu->script;
			}// end if		
			break;	
		case "show_grid":

			
			$this->showGrid();
		
		
			break;	
		case "list_set":

			
			
			$this->fieldValue = $this->listSet();
			$this->type_form = 10;
			//$input = $this->inputAux($name, $this->fieldValue);
		
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
			$this->design($method_ref);
			break;	
			
		case "config_panel":
			if(isset($this->eparams["method_ref"])){
				$method_ref = $this->eparams["method_ref"];
			}else{
				$method_ref = "request";
			}// end if
		
			$this->render = 0;
			$this->configPanel($method_ref);
			break;	
			
		case "save":
					
			if($n = $seq->getReq("__form_multi")){
				
				$data = array();
				for($i=0;$i<$n;$i++){
					
					for($x=0; $x < $this->fields_count; $x++){
						//foreach($this->fields as $k => $v)
						
						$data[$this->fields[$x]->name] = $seq->getReq($this->fields[$x]->name."_".$i);
						
					}// next
					$data["__form_mode"] = $seq->getReq("__form_mode_".$i);
					$data["__form_record"] = $seq->getReq("__form_record_".$i);
					
					
					
					$this->save($data);

					
				}// next
			}else{
				
				$this->eparams["record"] = $this->save($seq->cmd->v->req);
				
				$seq->setExp("__form_record", $this->eparams["record"]);
				
				
				foreach($this->_subForm as $k => $v){
					
					$seq->setVarRec($seq->cmd->v->req);
					
					$f = $this->subForm($this->fields[$k]->sf, $this->fields[$k]->sf_params);
					

					if($f->method == "list_set"){
						$f->eparams["list_init"] = $seq->cmd->v->req[$k."_list_init"];
						$f->eparams["list_new"] = $seq->cmd->v->req[$k];
						$f->eparams["list_filters"] = $seq->cmd->v->req[$k."_list_filt"];
						
						
						$f->evalMethod("save_list");	
						
					}
					
					
				}				
				
			}// end if
			break;	
		case "save_list":			

			$this->execute($this->name, $method);
			$this->saveList($this->eparams["list_init"], $this->eparams["list_new"], $this->eparams["list_filters"]);
			break;
			
		case "getdata":
			$this->get_data_script($this->record);
			break;
		case "update_list":
		
			$this->execute($this->name, $method);
			$this->render = 0;
			
			$this->setFuntion("update_list");
			
			return;
			break;	

			
			
		case "refresh"://TEMP	
		case "getfielddata":
			$this->render = 0;
			$this->func = 22;
			break;	
		case "saveat":
			$this->func = 23;
			break;	
		case "savefrom":
			$this->func = 24;
			break;	
		case "valid":
			$this->func = 25;
			break;	
		default:
			$this->func = 0;
			break;	
			
		case "new_element":

			$this->newElement();
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
			
		case "fields_data":
			$this->render = 0;
			
			$this->fieldsData();
			
			break;		
		case "required_field":
			$field = $this->eparams["field_name"];
			//print_r($this->eparams);
		
			$this->execute($this->name, $method);
			
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
			
			$this->validField($field, $rules);
			$this->render = 0;
			break;			
			
		}
		


		if($this->type!=0 and $this->render != 0){

			$this->html = $this->getDinamicPanel($this->html);
		}// end if
		

		if(isset($this->isigns[$this->method])){
			$this->sign[$this->isigns[$method]]=$this->isigns[$method];
			
		}else{
			$this->sign[$this->form."_".$method]=$this->form."_".$method;
		}

		
		return;		
		
		
			
		switch($this->func){
		case 1:
			$this->execute($this->name, $method);
			
			$this->data = $this->getData2("cedula=12474737");
			
			if($this->type==1){
				$this->showForm();
				$this->create_menu();
				$this->html .= $this->emenu->html;
				$this->script .= $this->emenu->script;
				
			}else{
				$this->show_form_pattern();
				$this->create_menu();
				$this->html .= $this->emenu->html;
				$this->script .= $this->emenu->script;
			}// end if
			break;
		case 2:
			$this->execute($this->name, $method);
			$this->show_grid();
				$this->create_menu();
				$this->html .= $this->emenu->html;
				$this->script .= $this->emenu->script;
			break;	
		case 3:
			$this->execute($this->name, $method);
			
			$data = $this->get_adata();
			$this->html= $this->inputGrid($data);
			$this->script .= "\n\t$this->ref.init();";			
			
			
				$this->create_menu();
				$this->html .= $this->emenu->html;
				$this->script .= $this->emenu->script;
			break;	
		case 11:
		case 12:
		case 13:
		case 14:
			
			if($n = $seq->getReq("__form_multi")){
				
				$data = array();
				for($i=0;$i<$n;$i++){
					foreach($this->fields as $k => $v){
						$data[$v->name] = $seq->getReq($v->name.$i);
						
					}// next
					$data["__form_mode"] = $seq->getReq("__form_mode".$i);
					$data["__form_record"] = $seq->getReq("__form_record".$i);
					
					//hr(8,"red");
					$this->save($data);
				}// next
			}else{
				
				$seq->setExp("__form_record", $this->save($seq->cmd->v->req));
			}// end if
			
			
			break;	
		case 10:
			$this->get_data();
			break;	
		case 11:
			$this->get_field_data();
			break;	
		case 12:
			$this->save_at();
			break;	
		case 13:
			$this->save_from();
			break;	
		case 25:
			$this->valid();
			break;	
		case 21:
			$this->get_data_script($this->record);
			break;	
		case 22:
			$this->execute($this->name, $method);
			$this->design();
		
		case 30:
			//$this->valid();
			break;	

		
		
			//$this->get_field_data("codestado");
			break;	
			
		}// end switch
		
		if(isset($this->isigns[$this->method])){
			$this->sign[$this->isigns[$this->method]]=$this->isigns[$this->method];
			
		}else{
			$this->sign[$this->form."_".$this->method]=$this->form."_".$this->method;
		}


				
		if($this->type!=0 and $this->render != 0){

			$this->html = $this->getDinamicPanel($this->html);
		}// end if
	}// end function 
	
	public function evalParam(){
		global $seq;

		
		if(!$this->name){
			if(isset($this->eparams["name"]) and $this->eparams["name"] != ""){
			
				$this->name = $this->eparams["name"];
			
			}else if($v_name = $seq->getReq("__form_name_".$this->panel)){
			
				$this->name = $v_name;
			
			}elseif(isset($this->vparams["name"]) and $this->vparams["name"] != ""){
			
				$this->name = $this->vparams["name"];
				
			}//end if			
			
		}
		
		if(isset($this->eparams["record"]) and $this->eparams["record"] != ""){

			$this->record = $this->eparams["record"];
		
		}else if($v_record = $seq->getReq("__form_record")){
		
			$this->record = $v_record;
		
		}elseif(isset($this->vparams["record"]) and $this->vparams["record"] != ""){
		
			$this->record = $this->vparams["record"];
			
		}//end if
		
		

		if(isset($this->eparams["page"]) and $this->eparams["page"] != ""){
			
			$this->page = $this->eparams["page"];
			
		
		}else if($v_page = $seq->getReq("__form_page_".$this->panel)){
		
			$this->page = $v_page;
		}elseif(isset($this->vparams["page"]) and $this->vparams["page"] != ""){
			$this->page = $this->vparams["page"];
			
		}//end if
		
		if($this->q_search = $seq->getReq("q")){
			
			
		}
		
		
		if($v_q_search = $seq->getReq("q_search_".$this->panel)){

			$this->q_search = $v_q_search;
			if($v_q_search_exact = $seq->getReq("q_search_exact_".$this->panel)){
				
				$this->q_search_exact = $v_q_search_exact;
				
			}// end if

		}// end if




		
		if(isset($this->eparams["request_element"])){
			
			$this->request_element = $this->eparams["request_element"];
			
		}else if($seq->getReq("__form_request_element")){
			$this->request_element = $seq->getReq("__form_request_element");
			
		}// end if

		if(isset($this->eparams["request_panel"])){
			$this->request_panel = $this->eparams["request_panel"];
		}else if($seq->getReq("__form_request_panel")){
			$this->request_panel = $seq->getReq("__form_request_panel");
			
		}// end if

		
		
	}// end function
	
	public function setClasses(){
		

		
		
	}
	
	public function initMethod($method=""){
		if($method!=""){
			//$this->method = $method;
		}// end if
		
		switch($method){
		case "design":
			$this->render = 1;
			$this->mode = 1;
			$this->func = 22;
			$this->with_data = false;
			$this->_function = "design";
			break;	


		case "request":
			$this->render = 1;
			$this->mode = 1;
			$this->func = 1;
			$this->with_data = false;
			
			$this->_function = "show_form";
			break;	
		case "requestfrom":
			$this->render = 1;
			$this->mode = 1;
			$this->func = 1;
			$this->_function = "show_form";
			break;	
		case "load":
			$this->render = 1;
			$this->mode = 2;
			$this->func = 1;
			$this->_function = "show_form";
			
			$this->with_data = true;
			
			break;	
		case "design":
		
			$this->render = 1;
			$this->mode = 1;
			$this->func = 1;
			$this->_function = "design";
			
			$this->with_data = true;
			
			break;	
		case "config_panel":
			$this->render = 0;
			$this->_function = "config_panel";
			break;	
		case "list":
			$this->render = 1;
			$this->mode = 0;
			$this->func = 2;
			$this->_function = "show_grid";
			$this->with_data = true;
			break;	
		case "multi":
			$this->render = 1;
			$this->mode = 0;
			$this->func = 3;
			$this->_function = "show_grid";
			break;	
		case "list_set":
			$this->render = 0;
			$this->_function = "list_set";
			break;
		case "insert":
			$this->render = 0;
			$this->func = 11;
			break;	
		case "update":
			$this->render = 0;
			$this->func = 12;
			break;	
		case "delete":
			$this->render = 0;
			$this->func = 13;
			break;	
		case "save":
		

			$this->execute($this->name, $method);
			$this->render = 0;
			$this->func = 14;
			$this->_function = "save";
			break;	
		case "save_list":
		
			
			$this->render = 0;
			$this->func = 14;
			$this->_function = "save_list";
			break;	
		case "getdata":
			$this->render = 0;
			$this->func = 21;
			$this->_function = "getdata";
			break;
		case "fields_data":
		
			$this->render = 0;
			$this->_function = "fields_data";
			break;


		case "update_list":
		
			$this->execute($this->name, $method);
			$this->render = 0;
			
			$this->setFuntion("update_list");
			
			return;
			break;	

			
			
		case "refresh"://TEMP	
		case "getfielddata":
			$this->render = 0;
			$this->func = 22;
			break;	
		case "saveat":
			$this->func = 23;
			break;	
		case "savefrom":
			$this->func = 24;
			break;	
		case "valid":
			$this->func = 25;
			break;	
		default:
			$this->func = 0;
			break;	
			
		case "new_element":

			$this->newElement();
			$this->render = 0;
			break;
		case "update_query":
			$this->render = 0;
			$this->_function = $method;
			break;
		case "title_form":
			$this->render = 0;
			$this->_function = $method;
			break;		
			
		case "title_field":

			$this->render = 0;
			$this->_function = $method;
			break;		
		case "required_field":
			$this->render = 0;
			$this->_function = $method;
			break;		
			
		}// end switch

		
	}			
	
	public function setFuntion($name){
		switch($name){
		case "render_form":
			if($this->type==1){
				$this->showForm();
				$this->create_menu();
				$this->html .= $this->emenu->html;
				$this->script .= $this->emenu->script;
				
			}else{
				$this->show_form_pattern();
				$this->create_menu();
				$this->html .= $this->emenu->html;
				$this->script .= $this->emenu->script;
			}// end if
			break;			
		case "render_grid":
			$this->show_grid();
			$this->create_menu();
			$this->html .= $this->emenu->html;
			$this->script .= $this->emenu->script;
			break;	
		case "save":
			$this->seq->v->exp["__form_record"] = $this->save($this->seq->v->req);
			break;	
		case "update_list":
			$this->updateList();
			break;	
			
			
			
			
		}// end case
		
		
		
	}// end function
	
	public function getDataScript($data, $name = "_DD"){
		$str = "\n\t"."$name = new Array();";
		foreach($data as $k => $v){
			$v["text"]= str_replace(chr(10),"",$v["text"]);
			$v["text"]= str_replace(chr(13)," ",$v["text"]);
			$str.= "\n\t".$name."[$k] = ['".$v["value"]."','".$v["text"]."','".$v["parent"]."'];";
		}// next
		return $str; 
	}// end function
	
	public function fieldsData(){
		global $seq;
		
		$value = false;
		
		foreach($this->fields as $i => $e){
		
			if($e->data_values!=""){
				
				if(!$this->fields[$i]->input){
					$this->fields[$i]->input = $this->get_control($this->fields[$i]->mtype);
				}// end if			
				$ele = $this->input($this->fields[$i]->input, $this->fields[$i]->name);
				$ele->panel = $this->panel;
				$ele->data = $this->fields[$i]->data;
				
				
				
				$ele->objScriptName = $this->getRef().".e.".$this->fields[$i]->name;
				
				if($this->request_element == $this->fields[$i]->name){
				
					$value = $seq->getExp("LAST_KEY");
				}else{
					$value = false;
				}// end if
				
				$this->script .= $ele->updateData($value);
				
		
			}// end if
			
			
		}// next
		
		//echo $this->script."<hr>";
		return;
		
		foreach($this->fieldsUpdateList[$this->listen] as $p => $v){
		
			$ele = new cls_form_elem;
			$ele->panel = $this->panel;
			
			
			
			$this->script = $ele->get_data($this->fields[$p], true, $this->seq->v->exp["LAST_ID"]);			
			
		}
	}// end function
	
	public function listSet1(){		
		$list = array();
		$data = $this->get_adata();
		
		foreach($data as $k => $v){
			$list[] = $v[$this->field_detail];
		}// next
		
		return implode(",", $list);			
	}// end fucntion

	public function buttonAdd($form="", $method="request"){
		return "<input type=\"button\" value=\"+\">";
			
		
	}
	
	public function show_form_template(){
		$this->get_template("form_1");
		//echo $this->code;
		if($this->record_from){
			
			$this->data = $this->seq->v->req[$this->record_from];
		}else if($this->mode == 2){

			$this->get_data("cedula4=12474737,codric2=5");
		}// end if
		
		

		
		$t = new cls_table($this->fields_count,2);
		$t->border=1;
		
		$ele = new cls_form_elem;
		$ele->panel = $this->panel;
		$this->code = str_replace("{f=title}",$this->title,$this->code);
		for($i=0;$i<$this->fields_count;$i++){
			$name=$this->fields[$i]->name;
			if (strpos($this->code, "{=$name:control}") === false) {

				continue;
			}// end if
			
			
			$this->code = str_replace("{=$name:title}", $this->fields[$i]->title, $this->code);
			
			if($this->fields[$i]->control){
				$control = $this->fields[$i]->control;
				
			}else{
				$control = $this->get_control($this->fields[$i]->mtype);
			}// end if
			
			
			switch($control){
			case $control:
				
				$ele->type = $control;
				

				$this->value = $this->data[$i];	

				$ele->control($this->fields[$i]);

				//$t->cell[$i][1]->text = $ele->html;
				$this->code = str_replace("{=$name:control}",$ele->html,$this->code);
				$this->script .= "\n".$ele->script;
				
			}// end switch
			
			
		}// next
		//$this->create_menu();
		$this->html = $this->code;
		$this->script .= "\n\t$this->ref.init();";
		//$this->script .= $nav["script"];
		
	}// end fucntion	
	
	public function show_form_pattern(){
		$this->get_template("form_3");
		
		if($this->record_from){
			
			$this->data = $this->seq->v->req[$this->record_from];
		}else if($this->mode == 2){

			$this->get_data("cedula4=12474737,codric2=5");
		}// end if
		$html = $this->code;
		$group = get_html_pattern($this->code, "group");
		$detail = get_html_pattern($this->code, "detail");
		$this->code= replace_html($this->code, "group", "");
		$rows = "";
		
			
					
		

		$ele = new cls_form_elem;
		$ele->panel = $this->panel;
		$this->code = str_replace("{=title}",$this->title,$this->code);
		$this->code = str_replace("{=class_form}",$this->class_form,$this->code);
		
		$this->code = str_replace("{=class_title}",$this->class_title,$this->code);
		$this->code = str_replace("{=class_elem_title}",$this->class_elem_title,$this->code);
		$group = str_replace("{=class_group}",$this->class_group,$group);
		
		
		for($i=0;$i<$this->fields_count;$i++){
			$name=$this->fields[$i]->name;
			$groupx = "";
			if($this->igroups[$name]){
				
				$groupx = str_replace("{=group}",$this->igroups[$name], $group);
				
			}
			
			$row = $groupx.str_replace("{=title}",$this->fields[$i]->title, $detail);
			
			
			$this->code = str_replace("{=class_elem}",$this->class_elem,$this->code);
			
			if($this->fields[$i]->control){
				$control = $this->fields[$i]->control;
				
			}else{
				$control = $this->get_control($this->fields[$i]->mtype);
			}// end if
			

			switch($control){
			case $control:
				
				$ele->type = $control;
				

				$this->value = $this->data[$i];	

				$ele->control($this->fields[$i]);
				$row = str_replace("{=class_elem}",$this->class_elem,$row);
				$row = str_replace("{=class_elem_cont}",$this->class_elem_cont,$row);
				
				$row = str_replace("{=class_elem_title}",$this->class_elem_title,$row);
				
				//$t->cell[$i][1]->text = $ele->html;
				$rows .= str_replace("{=control}", $ele->html, $row);
				$this->script .= "\n".$ele->script;
				
			}// end switch
			
			
		}// next
		$this->code= replace_html($this->code, "detail", $rows);
		
		
		$this->html = $this->code;
		$this->script .= "\n\t$this->ref.init();";
		
		
	}// end fucntion	
	
			

	public function newMenu($name, $method="load"){
		
		
		
		global $seq;		

		$ref = $this->getRef();
		
		
		$menu = $seq->newElement("menu");
		
		$menu->cns = $this->cns;
		
		$menu->ajax = $this->ajax;
		
		$menu->subElement = true;
		$menu->setRef($this->getRef(1));

		$menu->panel = $this->panel;
		if(isset($this->{"menu_".$this->method})){
			$menu->name = $this->{"menu_".$this->method};			
		}else{
			$menu->name = $name;
		}// end if		
		
		
		
		$menu->eparams = $this->eparams;
		$menu->eparams["menu_type"] = 4;
		$menu->untitled = "y";
		$menu->method = "load";
		
		return $menu;

	}// end function
	

	


	public function formVars($multi=""){ 
		
		$div = new sgHTML("div");
		$hidden = new sgHTML("input");
		$hidden->type = "hidden";
		$hidden->style = "color:white;background-color:#126541;";

		$hidden->name = "__form_multi";
		$hidden->value = $multi;
		$div->innerHTML .= "\n\t".$hidden->render();

		$hidden->name = "__form_record_p".$this->panel;
		$hidden->value = $this->record;
		$div->innerHTML .= "\n\t".$hidden->render();

		$hidden->name = "__form_page";
		$hidden->value = $this->page;
		$div->innerHTML .= "\n\t".$hidden->render();


		$hidden->name = "__form_request_element";
		$hidden->value = $this->request_element;
		
		$div->innerHTML .= "\n\t".$hidden->render();
		
		$hidden->name = "__form_request_panel";
		$hidden->value = $this->request_panel;
		
		$div->innerHTML .= "\n\t".$hidden->render();


		

		return $div->render();

	}// end function



}// end CLASS
?>