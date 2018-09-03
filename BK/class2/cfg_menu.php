<?php




class cfg_men_act {
	public $action = "";
	public $title = "";
	public $type = "";
	public $function = "";
	public $panel = "";
	public $params = "";
	public $eparams = "";
	public $iparams = "";
	public $vars = "";
	public $proc_before = "";
	public $proc_after = "";
	public $seq_before = "";
	public $seq_after = "";
	public $signs = "";
	public $eval_signs = "";
	public $validate = "";
	public $valid = "";
	public $events = "";
	public $confirm = "";
	public $class = "";
	public $style = "";
	public $status = "";
	public $request_element = "";
	public $request_panel = "";
	public $hidden = false;	
	
}// end class
class cfg_menu extends sg_panel{
	public $element = "menu";
	
	
	
	protected $elem_type = 1;
	// from table:
	
	public $action = array();
	
	public $method = "";
	public $menu = "";
	public $title = ""; 
	public $class = ""; 
	public $template = ""; 
	public $params = ""; 
	public $expressions = ""; 
	public $signs = ""; 
	public $eval_signs = ""; 
	public $style = "";
	public $propertys = ""; 
	public $title_style = ""; 
	public $title_propertys = ""; 
	public $action_style = ""; 
	public $action_propertys = ""; 
	public $module = ""; 
	public $user = ""; 
	public $update = ""; 
	public $status = ""; 
	public $heading = ""; 
	public $groups = ""; 
	public $type = 1;
	public $menu_type = "vertical";//1:vertical,2:horizontal,3:accordion,4:submit 
	
	public $image = "";


	public $group_propertys = "";
	public $group_style = "";
	public $html = "";
	public $script = "";

	public $text = "";
	public $css = "";
	public $debug = "";
	public $message = "";
	public $panel_width = "";	
	public $elem_params = "";

	public $actionsList = "";
	public $objPanelName = "sgMenu";
	// to class:
	
	//public $class = "";

	public $class_default = "";


	public $class_main = "";
	public $class_title = "";
	public $class_page = "";
	public $class_item = "";

	public $class_item_active = "";
	public $class_item_disabled = "";
	public $class_item_menu = "";
	public $class_item_image = "";	

	public $class_show = "";
	public $class_hide = "";

	public $style_main = "";
	public $style_title = "";
	public $style_page = "";
	public $style_item = "";


	
	
	public $icnn = SS_CNN;
	public $icns = SS_CNS;
	public $icnd = SS_CND;





	//===========================================================
	public function __construct($panel="") {
		
		$this->cnn = conection($this->icnn);
		$this->cnd = conection($this->icnd);
		if($this->icns!=""){
			$this->cns = conection($this->icns);
		}else{
			$this->cns = &$this->cnn;
		}// end if

		$this->t_menus = TABLE_PREFIX."menus";
		$this->t_men_itm = TABLE_PREFIX."men_itm";		
		$this->t_ele_met = TABLE_PREFIX."ele_met";
		$this->t_grp_usr = TABLE_PREFIX."grp_usr";
		$this->t_grp_ele = TABLE_PREFIX."grp_ele";
		$this->t_usr_ele = TABLE_PREFIX."usr_ele";
		
		$this->t_actions = TABLE_PREFIX."actions";
		$this->t_templates = TABLE_PREFIX."templates";	

	}// end function
		
	public function execute($name="", $method=""){
		
		global $seq;
/*
		if(isset($this->eparams["design_mode"])){
			
			$cn = &$this->cnd;
			$this->cnn = conection($this->icns);
			$this->cns = $this->cnd;
		}else{
			
		}
		*/
		$cn = &$this->cns;
		
		if($name!==""){
			$this->menu = $name;
		}// end if
		if($method!==""){
			$this->method = $method;
		}// end if
		$cn = &$this->cns;


		
		$cn->query = "
			SELECT DISTINCT
				m.menu, m.title, m.menu_type, m.image, m.class, m.template, m.params, m.expressions, 
				m.style, m.propertys, m.title_style, m.title_propertys, 
				m.action_style, m.action_propertys, m.module, 
				m.user, m.update, m.status, m.heading, m.groups			
			FROM $this->t_menus as m
			/*
			LEFT JOIN $this->t_ele_met as em ON em.element = '$this->element' AND em.name = m.menu AND em.method = '$method'  
			LEFT JOIN $this->t_grp_usr as gu ON gu.user = '$this->user'
			
			LEFT JOIN $this->t_grp_ele as g ON g.element = '$this->element' AND g.name = m.menu AND g.group = gu.group 
			LEFT JOIN $this->t_usr_ele as u ON u.element = '$this->element' AND u.name = m.menu AND u.user = gu.user
			*/
			WHERE m.menu = '$this->menu' /*AND m.status = '1'*/
				/*AND (g.allow=1 or g.name IS NULL)
				AND (u.allow=1 or u.name IS NULL)*/
		";
		
		
		$result = $cn->execute($cn->query);
		
		$this->parentItems = array();
		
		if($rs = $cn->getDataAssoc($result)){

			foreach($rs as $k => $v){
				$this->$k = $v;
			}// next
			if($prop = $seq->cmd->get_param($this->params)){
				
				foreach($prop as $k => $v){
					$this->$k = $v;
				}// next
			}// end if

			if($prop = $seq->cmd->get_param($this->elem_params)){
				foreach($prop as $k => $v){
					$this->$k = $v;
				}// next
			}// end if

		
			$cn->query = "
				SELECT
				
					a.action, a.title as a_title, a.async, 
					a.mode, a.panel, a.params, a.sequence,
					a.valid, a.confirm, a.alert, a.events,
					a.class, a.style,
					
					
					id, i.parent_id, i.title as i_title, i.class as i_class, i.type_item, 
					i.image, i.order, 
					i.params as i_params, i.style as i_style, i.propertys as i_propertys,
					
					CASE WHEN i.title IS NULL OR i.title ='' THEN a.title ELSE i.title END as title
				
				
				FROM $this->t_men_itm as i
				LEFT JOIN $this->t_actions as a ON a.action = i.action
				WHERE i.menu = '$this->name'
				ORDER BY i.order";	
			//hr($cn->query,"purple","pink");
			$result = $cn->execute($cn->query);
			$i=0;
			while($rs = $cn->getDataAssoc($result)){
				
				$i = $rs["id"];
				
				$this->action[$i] = new cfg_men_act; 
				foreach($rs as $k => $v){
					$this->action[$i]->$k = $v;
				}// next
				
				if($prop = $seq->cmd->get_param($this->action[$i]->params)){
					foreach($prop as $k => $v){
						$this->action[$i]->$k = $v;
					}// next
				}// end if
				if($prop = $seq->cmd->get_param($this->action[$i]->i_params)){
					foreach($prop as $k => $v){
						$this->action[$i]->$k = $v;
					}// next
				}// end if

				if($this->action[$i]->panel=="-1"){
					$this->action[$i]->panel = $this->panel;
					
				}// end if


				if($this->action[$i]->vars){
					//$this->action[$i]->eparams .= "vars:\'".$action[$i]->vars."\';";
				}// enf if
				
				if($this->action[$i]->parent_id!="0"){
					$this->parentItems[$this->action[$i]->parent_id] = 1;
				}// enf if


				//$this->action[$i]->set_panel = $this->action[$i]->eparams.$this->eval_set_panel($this->action[$i]);
				//$this->action[$i]->set_interaction = $this->eval_interaction($this->action[$i]);
				
				
				
				$i++;
			}// end while
			
			$this->action_style = $this->style.$this->action_style;
			$this->title_style = $this->style.$this->title_style;
			$this->group_style = $this->style.$this->group_style;
	
			$this->action_propertys = $this->propertys.$this->action_propertys;
			$this->title_propertys = $this->propertys.$this->title_propertys;
			$this->group_propertys = $this->propertys.$this->group_propertys;
		}else{
			//$this->name = "";
			
		}// end if	
		



		
	}// end function
	
	
	public function get_template($template){
		$cn = &$this->cns;
		$cn->query = "
			SELECT code 
			FROM $this->t_templates 
			WHERE template = '$this->template'
			";
		$result = $cn->ejecutar();
		
		if($rs = $cn->getDataAssoc()){
			$this->code = $rs["code"];
			
		}// end if
		
	}// end funtion 
	



	public function eval_set_panel($action){
		$cad = "";
		if($action->vars){
			$cad .= "vars:\'".$action->vars."\';";
		}// enf if
		return $cad;
		if($action->proc_before){
			$cad .= "proc_before:".$action->proc_before.";";
		}// enf if
		if($action->proc_after){
			$cad .= "proc_after:".$action->proc_after.";";
		}// enf if
		if($action->seq_before){
			$cad .= "seq_before:".$action->seq_before.";";
		}// enf if
		if($action->seq_after){
			$cad .= "seq_after:".$action->seq_after.";";
		}// enf if

		
		if($action->panel!==""){
			$cad .= "panel:".$action->panel.";";
		}// enf if
		if($action->element!=""){
			$cad .= "element:".$action->element.";";
		}// enf if
		if($action->name!=""){
			$cad .= "name:".$action->name.";";
		}// enf if
		if($action->method!=""){
			$cad .= "method:".$action->method.";";
		}// enf if

		if($action->structure){
			$cad .= "structure:".$action->structure.";";
		}// enf if
		if($action->interaction){
			$cad .= "imethod:".$action->interaction.";";
		}// enf if

		if(isset($action->login)){
			$cad .= "login:".$action->login.";";
		}// enf if

		if($action->elem_params!=""){
			$cad .= $action->elem_params;
		}// enf if


		return $cad;
	}// end function
	public function eval_interaction($action){
		$cad = "";
		
		if($action->proc_before){
			$cad .= "proc_before:".$action->proc_before.";";
		}// enf if
		if($action->proc_after){
			$cad .= "proc_after:".$action->proc_after.";";
		}// enf if
		if($action->seq_before){
			$cad .= "seq_before:".$action->seq_before.";";
		}// enf if
		if($action->seq_after){
			$cad .= "seq_after:".$action->seq_after.";";
		}// enf if
		if($action->structure){
			$cad .= "structure:".$action->structure.";";
		}// enf if
		if($action->interaction){
			$cad .= "method:".$action->interaction.";";
		}// enf if
		if($action->vars){
			$cad .= "vars:\'".$action->vars."\';";
		}// enf if
		if(isset($action->login)){
			$cad .= "login:".$action->login.";";
		}// enf if

		return $cad;
	}// end function

}// end class

?>