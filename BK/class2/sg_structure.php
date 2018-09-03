<?php
class sg_structure extends sg_panel{
	public $element = "structure";
	public $structure = "";
	public $title = "";
	public $template = "";
	public $class = "";
	public $main_panel = "";
	public $params = "";
	public $expressions = "";
	public $events = "";

	public $code = "";


	public $user = "";
public $class_default = "";



	public $elems = array();
	public $panels = array();
	
	public $strPanels = array();
	
	public $html = "";
	public $script = "";

	protected $t_usr_ele = "";
	
	protected $t_str_men = "";
	protected $t_str_ele = "";
	protected $t_structures = "";


	protected $t_gpr_usr = "";
	protected $t_gpr_ele = "";
	protected $t_gpr_str = "";
	protected $t_menus = "";
	protected $t_users = "";
	protected $t_templates = "";
	
	
	
	public $template_default = "--1----4--";
	
	public $icnn = SS_CNN;
	public $icns = SS_CNS;

	public function __construct($structure="") {
		if($structure!=""){
			$this->structure = $structure;
		}// end if
		
		$this->cnn = conection($this->icnn);
		if($this->icns!=""){
			$this->cns = conection($this->icns);
		}else{
			$this->cns = &$this->cnn;
		}// end if
		
		$this->t_structures = TABLE_PREFIX."structures";
		$this->t_grp_str = TABLE_PREFIX."grp_str";
		$this->t_grp_usr = TABLE_PREFIX."grp_usr";
		$this->t_users = TABLE_PREFIX."users";
		$this->t_str_ele = TABLE_PREFIX."str_ele";
		$this->t_grp_ele = TABLE_PREFIX."grp_ele";
		$this->t_usr_ele = TABLE_PREFIX."usr_ele";
		
		$this->t_templates = TABLE_PREFIX."templates";
		
	$this->t_panels = TABLE_PREFIX."panels";
		
	}// end function

	public function evalMethod($method){
		global $seq;
		
		switch($method){
		case "load":
			$this->initElements($this->name);
			//$this->render = 0;
			break;	
		case "init":
			$this->init($method);
			
			
			
			//$this->render = 0;
			break;	
		case "init_panel":
			break;	
		case "login":
		
			$result = $this->login($seq->getReq("user"), $seq->getReq("password"), $seq->getReq("structure"));
			if(!$result){
				
				$seq->addMessage('No esta autorizado');
			}
		
			break;	
		case "new":
			break;	
		case "load_template":
			break;	
		case "new_element":
		
			$this->newElement();
		
			break;		
		case "menu":
			$this->render = 1;
			$this->menu();
			break;	
		}// end switch
	
	}// end function
	
	public function init($method){
		global $seq;
		
		$this->execute($this->name, $method);
		
		$seq->setTemplate($this->code);
		if($this->update){
			$this->initElements($this->name);
			$this->setLevel();				
		}
		
	
	}// ebd function
	
	public function setLevel(){
		global $seq;
		$seq->setVar("STRUCTURE_NAME", $this->name);
		
		if(!isset($seq->cmd->v->ses["STRUCTURE_LEVEL"])){
			$seq->cmd->v->ses["STRUCTURE_LEVEL"] = array();
		}

		$st_new = array();
		$st = $seq->cmd->v->ses["STRUCTURE_LEVEL"];
		foreach($st as $k => $v){
			if($v["value"] != $this->name){
				$st_new[] = $v;
			}else{
				break;
			}
		}// next
		$st_new[] = array("value"=>$this->name, "text"=>$this->title);
		$seq->cmd->v->ses["STRUCTURE_LEVEL"] = $st_new;		
		
	}// end function
	
	public function getLevel(){
		global $seq;
		return $seq->cmd->v->ses["STRUCTURE_LEVEL"];
		
	}// end function
		
	public function menu(){
		
		$ref = $this->getRef();	
		
		$menu = new svMenu("__menu_"."$this->name".$this->panel);
		
		$menu->setRef($ref);
		$menu->typeMenu = "horizontal";
		$menu->setCaption($this->title);		
		$items = $this->getLevel();

		foreach($items as $k => $v){
			$menu->addItem(array(
				"id" => (integer)($k+1), 
				"parentId" => (integer)0,
				"title" => $v["text"],
				"_action" => svAction::send(array(
					"async"=>false,
					"panel"=>$this->panel,
					"params"=>"set_method:'panel:-2;element:$this->element;name:$v[value];method:init;';")),
				"action" => svAction::send(array(
					"async"=>false,
					"panel"=>$this->panel,
					"params"=>"structure:$v[value];"))					
				
			));
		}// next
		$this->html = $menu->render();
		$this->script .= $menu->getScript();
	}// end function
	
	public function execute($structure = "", $method = ""){

		global $seq;
		
		if($structure!=""){
			$this->structure = $structure;
		}// end if

		//$this->user = $this->seq->user;
		//===========================================================

		$cn = &$this->cns;

		$cn->query = "	
			SELECT 
				c.structure, c.title, c.template, c.class, c.main_panel, 
				c.flow, c.params, c.expressions, c.events, c.status, 
				f.user
			FROM $this->t_structures as c 
			LEFT JOIN $this->t_grp_str as d ON d.structure = c.structure
			LEFT JOIN $this->t_grp_usr as e ON e.group = d.group
			LEFT JOIN $this->t_users as f ON f.user = e.user
			WHERE c.structure = '$this->structure' /*AND f.user ='$this->user'*/
				
			";
		
		$result = $cn->execute();
		
		if($rs = $cn->getDataAssoc($result)){
			

			foreach($rs as $k => $v){
				$this->$k = $v;
			}// next
			
			if($prop = $seq->cmd->get_param($this->params)){
				$seq->v->par = array_merge($seq->v->par ,$prop);
				foreach($prop as $k => $v){
					$this->$k = $v;
				}// next
			}// end if

			if($this->class == ""){
				$this->class = $this->class_default;
			}// end if

			if($this->template == ""){
				$this->template = $this->template_default;
			}// end if
			
			if($prop = $seq->cmd->get_param($this->expressions)){
				$seq->v->exp = array_merge($seq->v->exp, $prop);
			}// end if
			//===========================================================
			//$this->events = $this->eval_all($this->events);
			
			
			$this->code = $seq->cmd->eval_var($this->get_template($this->template));
			//$this->code = preg_replace("/(?:--([0-9]+)--)/","~~$1~~",$this->code);
		
			//$this->html = $this->code;
			//$this->strPanels = $this->getPanels($this->code);


			
			
			$this->update = true;
		}else{
			
			$this->code = "--4--";
			
			$this->update = false;
		}// end if
	
	}// end function	
	

	public function initElements($structure){

		global $seq;
		
		if($structure!=""){
			$this->structure = $structure;
		}// end if

		$cn = &$this->cns;		
		
		$cn->query = "	
			SELECT se.structure, se.panel, se.element, se.name, method, 
					elem_params, pagina, para_obj, type, class, params,
					panel_template, panel_class , design_mode
			FROM $this->t_str_ele as se
			
			LEFT JOIN $this->t_grp_str as gs ON gs.structure = se.structure 
			LEFT JOIN $this->t_grp_usr as gu ON gu.group = gs.group 

			LEFT JOIN $this->t_grp_ele as ge 
				ON ge.group = gs.structure AND ge.element = ge.element AND ge.name = ge.name 
			
			LEFT JOIN $this->t_usr_ele as ue 
				ON ue.user = gu.user AND ue.element = ge.element AND ue.name = ge.name 

			WHERE se.structure = '$this->structure' /*AND gu.user = '$this->user'*/
			AND (ge.allow=1 or ge.allow IS NULL)
			AND (ue.allow=1 or ue.allow IS NULL)
			
			ORDER BY se.panel";

		$result = $cn->execute();

		$seq->resetPanels();
		
		$elem = new stdClass;		
		while($rs = $cn->getDataAssoc($result)){

			foreach($rs as $k => $v){
				$elem->$k = $v;
			}// next

			$seq->v->par = &$rs;
			
			if($prop = $seq->cmd->get_param($elem->params)){
				
				$seq->v->par = array_merge($seq->v->par ,$prop);
				
				foreach($prop as $k => $v){
					$elem->$k = $v;
				}// next
				
			}// end if	
			
			$eparams["panel"] = $elem->panel;
			$eparams["element"] = $elem->element;
			$eparams["name"] = $elem->name;
			$eparams["method"] = $elem->method;
			$eparams["eparams"] = $seq->cmd->getParam($elem->elem_params);
			$eparams["design_mode"] = $elem->design_mode;

			$seq->setPanel($eparams, 1);

		}// end if
		
	}// end function

	public function _initElements($structure){

		global $seq;
		
		if($structure!=""){
			$this->structure = $structure;
		}// end if

		$cn = &$this->cns;		
		
		$cn->query = "	
			SELECT 

				se.structure, se.panel, se.eparams,
				params, panel_class, type, design_mode

			FROM $this->t_panels as se
			WHERE se.structure = '$this->structure'
			ORDER BY se.panel";

		$result = $cn->execute();

		$seq->resetPanels();
				
		while($rs = $cn->getDataAssoc($result)){
			

			
			$panel = $rs["panel"];
			
			
			$seq->v->par = &$rs;

			if($eparams = $seq->cmd->getParam($rs["eparams"])){
				
				$eparams["panel"] = $panel;
				$eparams["eparams"] = $rs["eparams"];
				$eparams["design_mode"] = $rs["design_mode"];
				
				$seq->setPanel($eparams, 1);
				
			}// end if			

			

		}// end if
		
	}// end function


	public function _initElements2($structure){

		global $seq;
		
		if($structure!=""){
			$this->structure = $structure;
		}// end if

		//$this->user = $this->seq->user;





		$cn = &$this->cns;		
		
		
		$cn->query = "	
			SELECT se.structure, se.panel, se.element, se.name, method, 
					elem_params, pagina, para_obj, type, class, params,
					panel_template, panel_class  
			FROM $this->t_str_ele as se
			
			LEFT JOIN $this->t_grp_str as gs ON gs.structure = se.structure 
			LEFT JOIN $this->t_grp_usr as gu ON gu.group = gs.group 

			LEFT JOIN $this->t_grp_ele as ge 
				ON ge.group = gs.structure AND ge.element = ge.element AND ge.name = ge.name 
			
			LEFT JOIN $this->t_usr_ele as ue 
				ON ue.user = gu.user AND ue.element = ge.element AND ue.name = ge.name 

			WHERE se.structure = '$this->structure' /*AND gu.user = '$this->user'*/
			AND (ge.allow=1 or ge.allow IS NULL)
			AND (ue.allow=1 or ue.allow IS NULL)
			
			ORDER BY se.panel
						
						";
						
		//hr($cn->query,"white");				

		$result = $cn->execute();

		$init = false;
		
		while($rs = $cn->getDataAssoc($result)){
			
			if(!$init){

				foreach($seq->dataElems as $panel => $dataElems){
					
					if(!isset($dataElems->fixed) or !$dataElems->fixed){
						unset($seq->dataElems[$panel]);
					}
				}// next
				
				$init = true;
			}// end if
			
			$panel = $rs["panel"];
			
			$seq->dataElems[$panel] = new stdClass;

			$seq->v->par = &$rs;
			foreach($rs as $k => $v){
				$seq->dataElems[$panel]->$k = $v;
			}// next
			

			if($prop = $seq->cmd->get_param($seq->dataElems[$panel]->params)){
				$seq->v->par = array_merge($seq->v->par ,$prop);
				foreach($prop as $k => $v){
					
					
					$seq->dataElems[$panel]->$k = $v;
				}// next
			}// end if			
			

		}// end if
		
		/*
		$seq->strPanels = $this->strPanels;
		
		foreach($this->strPanels as $panel){
			if(!isset($seq->dataElems[$panel])){
				hr(8);
				$seq->dataElems[$panel] = new stdClass;
				$seq->dataElems[$panel]->element = false;
				$seq->dataElems[$panel]->name = "";
				$seq->dataElems[$panel]->method = false;
				$seq->dataElems[$panel]->panel = $panel;
				
			}// end if
			
		}// next
		*/
	}// end function





	public function login($user="", $password="", $structure=""){
		global $seq;
		if($user!=""){
			$this->user = $user;
		}// end if
		if($password!=""){
			$this->password = $password;
		}// end if
		if($structure!=""){
			$this->structure = $structure;
		}// end if
		

		//$this->chance++;
		$cn = &$this->cns;
		$cn->query = "	
			SELECT 
				u.user, u.password, u.expire, u.status,
				gs.group, gs.structure
			FROM $this->t_users as u
			LEFT JOIN $this->t_grp_usr as gu ON gu.user = u.user
			LEFT JOIN $this->t_grp_str as gs ON gs.group = gu.group 
			WHERE 
				u.user = '$user' 
				and (gs.structure = '$structure' /*or gs.structure IS NULL*/)
			ORDER BY gs.structure DESC	
		"; 
			
		$result = $cn->execute();
		if($rs = $cn->getDataAssoc($result)){
			
			
			if($rs["password"] == $password){
				
				$seq->setVar("STRUCTURE_USER", $rs["user"]);
				
				$this->name = $structure;
				
				$this->init("init");
				return true;
				
			}else{
				
				return false;
				//hr($cn->query);	
				//hr("error");
				
			}
			
			/*
			$this->group = $rs["group"];
			$password = ($this->md5) ? md5($this->password) : $this->password;
			$today = date("Y-m-d");
			if($rs["password"] != $password){
				if(!$this->msg_generic){
					$this->message = S_ACC_ERROR_PASSWORD;
					$this->error = 2;
				}else{
					$this->message = S_ACC_ERROR;
					$this->error = 1;
				}// end if
			}else if($rs["expire"] != "" and $rs["expire"] != "0000-00-00" and $rs["expire"] < $today){
				$this->message = S_ACC_ERROR_EXPIRED;
				$this->error = 4;
			}else if($rs["group"] == ""){	
				$this->message = S_ACC_ERROR_GROUP;
				$this->error = 5;
			}else if($rs["structure"] == ""){	
				$this->message = S_ACC_ERROR_ACCESS;	
				$this->error = 6;
			}else if($rs["status"] == "0"){	
				$this->message = S_ACC_ERROR_INACTIVE;	
				$this->error = 7;
			}else{
				$this->auth = true;
			}// end if
		}else{
			if(!$this->msg_generic){
				$this->message = S_ACC_ERROR_USER;
				$this->error = 3;
			}else{
				$this->message = S_ACC_ERROR;
				$this->error = 1;
			}// end if
			*/
		}// end if	
		
		return false;
		//return $this->auth;
	}// end function
	
	public function getPanels1($code_x){
		$exp = "|--([0-9]+)--|";
		$panels = array();
		if(preg_match_all($exp, $code_x, $c)){
			foreach($c[1] as $a => $b){
				$panels[trim($b)] = trim($b);
			}// next
		}// next
		return $panels;
	}// end function
	
	public function get_template($template = ""){
		
		if($template == ""){
			return "";
		}// end if
		$cn = &$this->cns;
		$cn->query = "SELECT code, type, file FROM $this->t_templates WHERE template = '$template'";
		
		if($rs = $cn->getData($cn->execute())){
			$file = $rs["file"];
			switch ($rs["type"]){
			case 1:
				if($file != "" and $form = @file_get_contents($file)){
					return $form; 
				}else{
					return $rs["code"];
				}// end if
				break;
			case "C_template_code":
				return $rs["code"];
				break;
			case "C_template_file":
				if($form = @file_get_contents($file)){
					return $form; 
				}// end if
				break;
			}// end switch
			return  $rs["code"];
		}// end if
		return false;
	}// end function

	public function newElement($name=""){
		global $seq;
		$cn = &$this->cnn;
		
		if($name == ""){
			$name = $this->element."_".$cn->serialName($this->t_structures, $this->element."_", "structure");
			
		}
		
		$title = ucwords(implode(" ",explode("_", $name)));
		
		
		$cn->query = "INSERT INTO $this->t_structures (structure, title) VALUES ('$name', '$title');";
		
		$this->debug = $cn->query;
		$result = $cn->execute();

		$this->vparams["new_menu"] = $name;
		$seq->setReq("new_menu", $name);
		
		$this->name = $name;
	}// end funtion 		

	public function initElementsORG($structure){

		global $seq;
		
		if($structure!=""){
			$this->structure = $structure;
		}// end if

		//$this->user = $this->seq->user;
		//===========================================================
		

		$cn = &$this->cns;		
		
		
		$cn->query = "	
			SELECT se.structure, se.panel, se.element, se.name, method, 
					elem_params, pagina, para_obj, type, class, params,
					panel_template, panel_class  
			FROM $this->t_str_ele as se
			
			LEFT JOIN $this->t_grp_str as gs ON gs.structure = se.structure 
			LEFT JOIN $this->t_grp_usr as gu ON gu.group = gs.group 

			LEFT JOIN $this->t_grp_ele as ge 
				ON ge.group = gs.structure AND ge.element = ge.element AND ge.name = ge.name 
			
			LEFT JOIN $this->t_usr_ele as ue 
				ON ue.user = gu.user AND ue.element = ge.element AND ue.name = ge.name 

			WHERE se.structure = '$this->structure' /*AND gu.user = '$this->user'*/
			AND (ge.allow=1 or ge.allow IS NULL)
			AND (ue.allow=1 or ue.allow IS NULL)
			
			ORDER BY se.panel
						
						";

		$result = $cn->execute();
		hr($cn->query."..."); 
		while($rs = $cn->getDataAssoc($result)){

			$panel = $rs["panel"];
			$this->elems[$panel] = $seq->newElement($rs["element"]);
			
			
			$seq->v->par = &$rs;
			foreach($rs as $k => $v){
				$this->elems[$panel]->$k = $v;
			}// next
			

			if($prop = $seq->cmd->get_param($this->elems[$panel]->params)){
				$seq->v->par = array_merge($seq->v->par ,$prop);
				foreach($prop as $k => $v){
					
					
					$this->elems[$panel]->$k = $v;
				}// next
			}// end if
			
		}// end if
		
		foreach($this->strPanels as $panel){
			if(!isset($this->elems[$panel])){
				$this->elems[$panel] = $seq->newElement(101);
				$this->elems[$panel]->panel = $panel;
				$this->elems[$panel]->type = 1;
			}// end if
			
			
			
		}// next
		
		return $this->elems;
		
	}// end function
	
}// end class



?>