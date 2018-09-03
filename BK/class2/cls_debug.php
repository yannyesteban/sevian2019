<?php
/*****************************************************************
creado: 13/06/2015
modificado: 13/06/2015
por: Yanny Nuñez
*****************************************************************/
class debugObj{
	public $panel, $element, $name, $method, $param;
	
	
}

class cls_debug extends sg_panel{
	
	public $element = "debug";
	public $title = "SIGEFOR 1.0";
	public $text = "";
	public $css = "";
	public $message = "";
	public $method = "";
	public $render = 0;
	 
	public $objPanelName = "";
	
	public $html = "";
	public $script = "";
	public $debug = "";
	
	private $obj = array();
	private $q = array();
	private $e = array();
	
	public $login = false;
	public $admin_user = "admin";
	public $admin_pass = "123";

	private $t_modules = "";
	private $t_structures = "";
	private $t_templates = "";	
	//public $design_mode=false;
	
	
	private $vses = array();
	
	public function __construct() {
		$this->cnn = conection($this->icnn);
		if($this->icns!=""){
			$this->cns = conection($this->icns);
		}else{
			$this->cns = &$this->cnn;
		}// end if
		
		$this->cnd = conection("_designer");

		$this->t_modules = TABLE_PREFIX."modules";
		$this->t_structures = TABLE_PREFIX."structures";
		$this->t_templates = TABLE_PREFIX."templates";


	}// end function	

	public function evalMethod($method){
		
		global $seq;

		$this->vses = array();

		if(isset($seq->cmd->v->ses["_SS_VDEBUG_".$this->panel])){
			
			$this->vses = &$seq->cmd->v->ses["_SS_VDEBUG_".$this->panel];
			
		}else{

			$seq->cmd->v->ses["_SS_VDEBUG_".$this->panel] = &$this->vses; 
			
		}
		
		
		
		if(isset($this->vses["login"]) != ""){

			$this->login = $this->vses["login"];

		}		
		$this->vses["login"] = &$this->login;
		
		$this->divStrutureId = "__desiner_structure_".$this->panel;
		$this->divModuleId = "__desiner_module_".$this->panel;
		$this->divTemplateId = "__desiner_template_".$this->panel;
		
		
		$this->esigns["cfg_modules_save"]="escuchar";
		


		if($seq->getReq("user") != "" and !$this->login){
			if(	$this->admin_user == $seq->getReq("user") and $this->admin_pass == $seq->getReq("pass")){

				$this->login = true;	
				
			}else{

				$this->formLogin();
				return;	

			}// end if	
		}// end if	

		if(!$this->login){
			$this->formLogin();
			return;	
		}// end if	

		switch($method){
		case "toolbar":
			$this->render = 1;
			$this->toolBar();

			break;	
		case "save_module":
			break;	
		case "save_structure":
			break;	
		case "save_template":
			break;	
		case "show_vars":
			$this->render = 1;
			$this->showVars();
			break;	
		case "show_panels":
			break;	
		case "save_menu":
			break;	
		case "save_form":
			break;	
		case "save_fields":
			break;	
		case "new_module":
			$this->newModule();
			break;
			
		case "escuchar":
			$this->setFragment($this->getListModules($seq->getVar("MODULE_NAME"))->render(), $this->divModuleId);
		
			//$this->script = "alert(1024);";
			return "";
			break;
		}// end switch


		$this->log();
		$this->log(array(
			"element"=>$this->element,
			"title"=>$this->title,
			"name"=>$this->name,
			"method"=>$method,
			"panel"=>$this->panel
		));
	
		if($this->type!=0 and $this->render != 0){

			//$this->html = $this->getDinamicPanel($this->html);
		}// end if	
	}// end function
	
	public function formLogin(){


		$f = new svForm(array("name"=>"sgpanel_".$this->panel));
		$f->setRef($this->getRef());
		
		$f->class = "__debug_login";
		//$f->setClasses();
		//$f->setCaption($this->title);
		
		//$f->addPage("1", "");
		//$f->addTab("ss");
		//$f->addTabPage("1+");
		$f->addPage("1", false);
		//$f->addTable("1");
		

		

		$f->addField("text", "user", "Usuario", "admin");
		$f->addField("text", "pass", "Clave", "123");
		
		$f->addMenuPage("1");
		
				
		$item = $f->addField("button", "", "", "Login");		

		$item->input->propertys["onclick"] = svAction::setPanel(
			array(
				"async"=>true,
				"panel"=>$this->panel,
				"params"=>"panel:$this->panel;element:$this->element;method:$this->method;"
			));
			
			$f->style = "width:250px;";
		
		$this->html .= $f->render();//.$this->formVars()
		$this->script .= $f->getScript();		
		$this->render = 1;

		$this->_win_prop = array(
				"name" => "tool",
				"title" => $this->title, 
				"icon" =>"http://localhost/_sevian/themes/_common/images/".'REAL OSX CHESS.png',
				"autoClose" =>false,
				"withCaption" =>true,
				"draggable" =>true,
				"typePopup" =>false,
				"resize"=> true,
				"visible"=> true,
				"width"=>"300px",
				"height"=>"auto",
				"_mode" =>"custom",
				);	

		return;
		
	}// end function

	public function showVars(){
		global $seq;
		$t = new sg_table(0,3);
		$t->border=1;
				
		$t->insert_row();
		$t->cells[0][0]->text .= "<div>Request</div>"; 
		$t->cells[0][1]->text .= "<div>Session</div>"; 
		$t->cells[0][2]->text .= "<div>Exp</div>"; 

		$t->insert_row();
		$t->cells[1][0]->text = $this->getTableVar($seq->cmd->v->req); 
		$t->cells[1][1]->text = $this->getTableVar($seq->cmd->v->ses); 
		$t->cells[1][2]->text = $this->getTableVar($seq->cmd->v->exp); 

		$this->html = $t->render();
		$this->script = "";
		
		
		
	}// end function
	
	public function getTableVar($var){
		$t = new sg_table(0,2);
		$t->border=0;
		$i=0;
		foreach($var as $k => $v){
			$t->insert_row();			
			$t->cells[$i][0]->text = $k; 
			$t->cells[$i][1]->text = $v; 
			$i++;
		}// next
		
		return $t->render();
		
		
		
	}// end function
	
	
	public function regObjeto($panel, $element, $name, $method, $param){
		$this->obj[$panel] = new debugObj(); 
		$this->obj[$panel]->panel = $panel; 
		$this->obj[$panel]->element = $element; 
		$this->obj[$panel]->method = $method; 
		$this->obj[$panel]->param = $param; 
		
		
		
	}
	
	public function regQuery($panel, $query){
		
		if(isset($this->q[$panel])){
			$this->q[$panel] .= $query;
		}else{
			$this->q[$panel] = $query;
		}// end if
		
		
	}
	
	public function regError($panel, $query){
		$this->e[$panel] = $query;
		
		
	}
	
	private function getListModules($value = false){
		global $seq;		
		$cn = &$this->cns;
		
		$cn->query = "SELECT module, title FROM $this->t_modules";
		$result = $cn->execute($cn->query);
		
		
		$list = new sgHTML("select", false);
		$list->name = "module";
		$html = "";

		$opt = new sgHTML("option");
		$opt->value = ""; 
		$opt->text = ""; 
		$list->appendChild($opt->render());

		
		$i=0;
		while($rs = $cn->getDataAssoc($result)){
			$opt = new sgHTML("option", false);
			$opt->value = $rs["module"]; 
			$opt->text = $rs["title"]; 			
				

			
			if($rs["module"] == $value){
				$opt->selected = true;
				
			}// end if
			
			$list->appendChild($opt->render());
			
		}
		$opt = new sgHTML("option", false);
		$opt->value = "";
		$opt->text = "+ Nueva"; 

 
		$list->appendChild($opt->render());





	
	
	
		$list->onchange = svAction::send(array(
			"async" => false,
			"panel"=>$this->panel,
			"params_"=>"set_method:'element:module;method:init;name:{=module};';",
			"params"=>"module:{=module};"
		
		));
		

		//$list->onchange = "sgAction.setPanel(200, 'element:module;name:'+this.value+';method:load;thread:;', 'module', false)";
		//$list->onchange = "S.setPanel(200, 'element:structure;name:'+this.value+';method:load;thread:;', '', false)";

		return $list;
		
	}

	private function getListStructure($value = false){
		global $seq;		
		$cn = &$this->cns;
		
		$html = "";
		
		$cn->query = "SELECT structure, title FROM $this->t_structures";
		$result = $cn->execute($cn->query);
		
		
		$list = new sgHTML("select", false);
		$list->name = "structure";
		
		


		$opt = new sgHTML("option");

		$opt->value = ""; 
		$opt->text = ""; 			
		
				
		$list->appendChild($opt->render());



		$i=0;
		while($rs = $cn->getDataAssoc($result)){
			$opt = new sgHTML("option", false);

			$opt->value = $rs["structure"]; 
			$opt->text = $rs["title"]; 				
			if($rs["structure"] == $value){
				$opt->selected = true;
				
			}// end if
			
			$list->appendChild($opt->render());
			
		}
		$opt = new sgHTML("option", false);
		$opt->value = "";
		$opt->text = "+ Nueva"; 
		
		$list->appendChild($opt->render());

		$a = new stdClass;
		$a->panel = $this->panel;
		$a->w_panel = "";
		$a->eparams = "panel:-2;element:structure;method:init;name:{=structure};";
		$a->action = "";
		$a->valid = "";
		$a->confirm = "";
	
	
	
		$list->onchange = svAction::send(array(
			"async" => false,
			"panel"=>$this->panel,
			"params_"=>"set_method:'panel:-2;element:structure;method:init;name:{=structure};';",
			"params"=>"structure:{=structure};"
		
		));


		return $list;
		
	}

	private function getListTemplates($value = false){
		global $seq;		
		$cn = &$this->cns;
		$cn->query = "
			SELECT template, IFNULL(title, CONCAT('(',template,')')) as title 
			FROM $this->t_templates
			ORDER BY 2";
		$result = $cn->execute($cn->query);
		
		
		$list = new sgHTML("select", false);
		$list->name = "template";
		$html = "";
		
		$i=0;
		while($rs = $cn->getDataAssoc($result)){
			$opt = new sgHTML("option", false);
			$opt->value = $rs["template"];
			$opt->text = $rs["title"]; 			
			

			
			if($rs["template"] == $value){
				$opt->selected = true;
				
			}// end if
			
			$list->appendChild($opt->render());
			
		}
		$opt = new sgHTML("option", false);
		$opt->value = "";
		$opt->text = "+ Nueva"; 		
		

		$list->appendChild($opt->render());

		$list->onchange = "sgAction.setPanel(200, '', '', false)";


		return $list;
		
	}

	private function getStructures(){
		
		
		
	}
	
	public function toolBar(){
		
		
		
		global $seq;
		
					
					
		$this->_win_prop = array(
				"name" => 'tool',
				"title" => $this->title, 
				"icon" =>"http://localhost/_sevian/themes/_common/images/".'icon_new.png',
				"autoClose" =>false,
				"timerDelay" =>0,
				"withCaption" =>true,
				"draggable" =>true,
				"typePopup" =>true,
				"x"=>"center",
				"y"=>"top",
				"visible" => true,
				"resize" => false,
				"mode" =>"auto",
				"btnClose" => false,
				"btnMax" => false,
				);		
		
		$f = $this->f = new svForm(array("name"=>"sgpanel_".$this->panel));
		$f->setRef($this->getRef());
		$f->setClasses("fds_");
		$f->body->class = "tool_bar";
		//$f->setCaption("== Design Sigefor ==");
		

		
		$f->appendChild($this->getListModules($seq->getVar("MODULE_NAME")));		


		$item = $f->addField("button", "","", "");
		$item->input->propertys["data-fds_menu_type"] = "go";
		$item->input->propertys["onclick"] = svAction::send(
			array(
				"async"=>false,
				"panel"=>$this->panel,
				"params"=>"module:{=module};"
			));
		
		$btnM = $f->addField("button", "btn_m","M+", "M+");		
		$btnM->input->propertys["onclick"] = svAction::send(array(
			"async" => false,
			"panel"=>$this->panel,
			"params"=>"set_method:'element:module;method:new_element;';"
		
		));		

		
		$f->appendChild($this->getListStructure($seq->getVar("STRUCTURE_NAME")));		

		$item = $f->addField("button", "","", "");
		$item->input->propertys["data-fds_menu_type"] = "go";
		$item->input->propertys["onclick"] = svAction::send(
			array(
				"async"=>false,
				"panel"=>$this->panel,
				"params"=>"structure:{=structure};"
			));

		$btnS = $f->addField("button", "btn_s","S+", "S+");		
		$btnS->input->propertys["onclick"] = svAction::send(array(
			"async" => false,
			"panel"=>$this->panel,
			"params"=>"set_method:'element:structure;method:new_element;';"
		
		));
		
		$btnV = $f->addField("button", "btn_v","", "@v");		
		$btnV->input->propertys["onclick"] = "sgLog.showVars();";		

		$btnL = $f->addField("button", "btn_l","", "Lg");		
		$btnL->input->propertys["onclick"] = "sgLog.showLog();";		

		$btnD = $f->addField("button", "btn_d","", "D");		
		$btnD->input->propertys["onclick"] = "debug.showMenu(!debug.active);";		

		$btnT = $f->addField("button", "btn_t","", "T");		
		$btnT->input->propertys["onclick"] = svAction::setPanel(array(
			"async" => true,
			"panel"=>$this->panel,
			"params"=>"panel:204;element:sigefor;name:login;method:design;",
			"window1"=>204
		
		));

		$btnR = $f->addField("button", "btn_r","", "R");		
		$btnR->input->propertys["onclick"] = svAction::setPanel(array(
			"async" => true,
			"panel"=>$this->panel,
			"params"=>"$this->panel;element:$this->element;name:x;method:toolbar;"
		));	
		
		$this->css = $f->getCss();
		$this->html = $f->render();
		$this->script .= $f->getScript();
		
		$auxElement = array();

		foreach($seq->clsElement as $k => $v){
			$auxElement[] = $k;	
		}// next

		$elements = implode(",", $auxElement);
		$this->script .= "\ndebug.typesElement ='$elements';";
		$this->script .= "\ndebug.init();";


		
		
		return;	
		
	}// end function	


	public function toolBar_NORMAL(){
		global $seq;
		$this->_win_prop = array(
				"name" => 'tool',
				"title" => $this->title, 
				"icon" =>"http://localhost/_sevian/themes/_common/images/".'icon_new.png',
				"autoClose" =>false,
				"timerDelay" =>0,
				"withCaption" =>true,
				"draggable" =>true,
				"typePopup" =>false,
				"x"=>"center",
				"y"=>"bottom",
				"visible" => true,
				"resize" => false,
				"mode" =>"auto",
				"btnClose" => false,
				"btnMax" => false,
				);		
		
		$sDiv1 = new sgHTML("span");
		$sDiv1->id = $this->divModuleId;
		$sDiv1->appendChild($this->getListModules($seq->getVar("MODULE_NAME")));

		$addM = new sgHTML("input");
		$addM->type="button";
		$addM->value = "M+";

		$a = new stdClass;
		$a->panel = $this->panel;
		$a->w_panel = "";
		$a->eparams = "panel:-2;element:module;method:new_element;";
		$a->action = "";
		$a->valid = "";
		$a->confirm = "";
		$addM->onclick = svAction::send(array(
			"async" => false,
			"panel"=>$this->panel,
			"params"=>"set_method:'element:module;method:new_element;';"
		
		));

		
		$a = new stdClass;
		$a->panel = $this->panel;
		$a->w_panel = "";
		$a->eparams = "panel:-2;element:module;method:init;name:{=module};";
		$a->action = "";
		$a->valid = "";
		$a->confirm = "";

		$refresh = new sgHTML("input");
		$refresh->type = "button";
		$refresh->Value = "L";
		$refresh->onclick = svAction::send(array(
			"async" => false,
			"panel"=>$this->panel,
			"params"=>"panel:-2;element:module;method:init;name:{=module};"
		
		));



		$editM = new sgHTML("input");
		$editM->type="button";
		$editM->value = "M";
		$a = new stdClass;
		$a->panel = $this->panel;
		$a->w_panel = "55";
		$a->eparams = "panel:55;element:form;name:cfg_modules;method:load;record:module={=module};design_mode:1;";
		$a->action = "";
		$a->valid = "";
		$a->confirm = "";
		$editM->onclick = svAction::send(array(
			"async" => false,
			"panel"=>$this->panel,
			"params"=>"panel:55;element:form;name:cfg_modules;method:load;record:module={=module};design_mode:1;"
		
		));	



		$sDiv2 = new sgHTML("span");
		$sDiv2->id = $this->divStrutureId;
		$sDiv2->appendChild($this->getListStructure($seq->getVar("STRUCTURE_NAME")));



		$a = new stdClass;
		$a->panel = $this->panel;
		$a->w_panel = "";
		$a->eparams = "element:structure;method:new_element;";
		$a->action = "";
		$a->valid = "";
		$a->confirm = "";

		$btnSN = new sgHTML("input");
		$btnSN->type="button";
		$btnSN->value = "S+";
		$btnSN->onclick = svAction::send(array(
			"async" => false,
			"panel"=>$this->panel,
			"params"=>"set_method:'element:structure;method:new_element;';"
		
		));	
		
		$a = new stdClass;
		$a->panel = $this->panel;
		$a->w_panel = "";
		$a->eparams = "panel:-2;element:structure;method:init;name:{=module};";
		$a->action = "";
		$a->valid = "";
		$a->confirm = "";

		$btnSL = new sgHTML("input");
		$btnSL->type = "button";
		$btnSL->Value = "L";
		$btnSL->onclick = svAction::send(array(
			"async" => false,
			"panel"=>$this->panel,
			"params"=>"panel:-2;element:structure;method:init;name:{=module};"
		
		));

		$a = new stdClass;
		$a->panel = $this->panel;
		$a->w_panel = "55";
		$a->eparams = "panel:55;element:form;name:cfg_structures;method:load;record:structure={=structure};design_mode:1;";
		$a->action = "";
		$a->valid = "";
		$a->confirm = "";
		$btnSE = new sgHTML("input");
		$btnSE->type="button";
		$btnSE->value = "S";
		$btnSE->onclick = svAction::send(array(
			"async" => false,
			"panel"=>$this->panel,
			"params"=>"panel:55;element:form;name:cfg_structures;method:load;record:structure={=structure};design_mode:1;"
		
		));	


		$sDiv3 = new sgHTML("span");
		$sDiv3->id = $this->divTemplateId;
		$sDiv3->appendChild($this->getListTemplates($seq->getVar("TEMPLATE_NAME")));
		

		$tMenu = new sgHTML("input");
		$tMenu->type="button";
		$tMenu->value = "Menu";
		$a = new stdClass;
		$a->panel = $this->panel;
		$a->w_panel = "244";
		$a->eparams = "panel:244;element:form;name:personas;method:request;";
		$a->action = "";
		$a->valid = "";
		$a->confirm = "";
	
		
	
		$tMenu->onclick = svAction::send(array(
			"async" => false,
			"panel"=>$this->panel,
			"params"=>"panel:244;element:form;name:personas;method:request;"
		
		));	
		




		
		$html = $addM->render().$sDiv1->render().$refresh->render().$editM->render().
				$btnSN->render().$sDiv2->render().$btnSL->render().$btnSE->render().$tMenu->render();	
		
		
		//$b1->onclick = svAction::setEvent("new_window", 200, "panel:200;element:1;name:cfg_modules_a;method:request;thread:;", "new_module", "", "");
		$bb1 = $addM->render();

		$b2 = new sgHTML("input");
		$b2->type="button";
		$b2->value = "E++";
		$a = new stdClass;
		$a->panel = $this->panel;
		$a->w_panel = "";
		$a->eparams = "element:structure;method:new_element;";
		$a->action = "";
		$a->valid = "";
		$a->confirm = "";
	
	
	
		$b2->onclick = svAction::send(array(
			"async" => false,
			"panel"=>$this->panel,
			"params"=>"element:structure;method:new_element;"
		
		));		


		$bb2 = $b2->render();


		$b3 = new sgHTML("input");
		$b3->type="button";
		$b3->value = "Template";
		$a = new stdClass;
		$a->panel = $this->panel;
		$a->w_panel = "201";
		$a->eparams = "panel:201;element:form;name:cfg_templates;method:load;";
		$a->action = "";
		$a->valid = "";
		$a->confirm = "";
	
	
	
		$b3->onclick = svAction::send(array(
			"async" => false,
			"panel"=>$this->panel,
			"params"=>"panel:201;element:form;name:cfg_templates;method:load;"
		
		));		

		$bb3 = $b3->render();




		$tAdd = new sgHTML("input");
		$tAdd->type="button";
		$tAdd->value = "T+";
		$a = new stdClass;
		$a->panel = $this->panel;
		$a->w_panel = "";
		$a->eparams = "panel:205;element:template;method:new_element;";
		$a->action = "";
		$a->valid = "";
		$a->confirm = "";
		$tAdd->onclick = svAction::send(array(
			"async" => false,
			"panel"=>$this->panel,
			"params"=>"panel:205;element:template;method:new_element;"
		
		));		


		$str = $tAdd->render();


		$tEdit = new sgHTML("input");
		$tEdit->type="button";
		$tEdit->value = "T";
		$a = new stdClass;
		$a->panel = $this->panel;
		$a->w_panel = "201";
		$a->eparams = "panel:201;element:form;name:cfg_templates;method:load;design_mode:1;";
		$a->action = "";
		$a->valid = "";
		$a->confirm = "";
	
		
	
		$tEdit->onclick = svAction::send(array(
			"async" => false,
			"panel"=>$this->panel,
			"params"=>"panel:201;element:form;name:cfg_templates;method:load;design_mode:1;"
		
		));	
		$str .= $tEdit->render();
		





		//<form name='sgpanel_200'>
		$html1 = "
		
		<div class='tool_bar'>
		
		<input type=\"button\" onclick=\"sgAction.openWindow(200, 'element:1;name:cfg_modules_a;method:request;thread:;', '', '')\" value=\"M+\">		
		
		$str
		<input type=\"button\" onclick=\"sgAction.openWindow(200, 'element:1;name:cfg_structures_a;method:request;thread:;', '', '')\" value=\"E+\">		
		
		<input type=\"button\" onclick=\"sgAction.openWindow(200, 'element:1;name:cfg_templates;method:request;thread:;', '', '')\" value=\"+\">		
		
		<input type=\"button\" value='SEQ+'>
		<input type=\"button\" value='PRC+'>
		<span>PANEL</span>		
		<select><option>4</option><option>2</option><option>3</option><option>1</option></select>
		<input type=\"button\" value='+'>
		<input type=\"button\" value='LOG'>
		<input id=\"__debug_vars\" type=\"button\" value=\"VARS\">		
		</div>
		
		";


		
		$auxElement = array();
		foreach($seq->clsElement as $k => $v){
			
			$auxElement[] = $k;	
		}
		$elements = implode(",", $auxElement);
		$this->script = "\ndebug.typesElement ='$elements';";
		
		$this->script .= "\ndebug.init();";


		$div = new sgHTML("div");
		$div->class = "tool_bar";
		
		$input1 = new sgHTML("input");
		$input1->type = "button";
		$input1->id = "__debug_vars";
		$input1->value = "VARS";
		$div->innerHTML = $html.$input1->render();


		$input2 = new sgHTML("input");
		$input2->type = "button";
		$input2->id = "__debug_win";
		$input2->value = "db()";
		
		
		$input2->onclick = "sgLog.showVars()";
		
		$div->innerHTML .= $input2->render();
		

		$input3 = new sgHTML("input");
		$input3->type = "button";
		$input3->id = "";
		$input3->value = "onDesign";
		$input3->onclick = "debug.showMenu(!debug.active)";
		$div->innerHTML .= $input3->render();


		$input4 = new sgHTML("input");
		$input4->type = "button";
		$input4->id = "";
		$input4->value = "Tool";
		$input4->onclick = "sgLog.showLog(!debug.active)";
		
		
		$input4->onclick1 = svAction::setPanel(array(
			"async" => true,
			"panel"=>$this->panel,
			"params"=>"panel:204;element:sigefor;name:login;method:design;",
			"window1"=>204
		
		));			
		$div->innerHTML .= $input4->render();


		$input5 = new sgHTML("input");
		$input5->type = "button";
		$input5->id = "";
		$input5->value = "Rf";
		$input5->onclick = "debug.showMenu(!debug.active)";
		
		
		$input5->onclick = svAction::setPanel(array(
			"async" => true,
			"panel"=>$this->panel,
			"params"=>"$this->panel;element:$this->element;name:x;method:toolbar;",
			"window1"=>204
		
		));			
		$div->innerHTML .= $input5->render();

		
		
		$this->html = $div->render();
		
		
	}// end function	
	
	
}// end class


?>