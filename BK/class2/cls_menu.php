<?php
require_once("cfg_menu.php");
class cls_menu extends cfg_menu{

	public $untitled = "n";
	public $width = "100%";	
	public $dinamic = true;
	public $transition = true;
	public $icon = "";

	public function evalMethod($method = "load"){
		
		global $seq;

		if($this->name == ""){
			//return;	
		}
		
		
		

		
		
		$proc_before="proc_before_".$method;
		$seq_before="seq_before_".$method;
		$proc_after="proc_before_".$method;
		$seq_after="seq_before_".$method;
		//$this->seq->proc->execute($proc_before);
		//$this->seq->execute($seq_before);
		switch ($method){
			
		case "load":
		case "create":
			$this->execute($this->name, $method);
		
			if($this->menu_type == 0 and isset($this->eparams["menu_type"])){
				$this->menu_type = $this->eparams["menu_type"];
			}// end if
			
			$this->load();	
			$this->render = 1;
			break;	

		default:

			if(!$this->extraMethod($method)){
				
				//$this->render = 1;				
				
			}


			break;
			
		}// end switch
		if(isset($this->isigns[$method])){
			$this->sign[$this->isigns[$method]]=$this->isigns[$method];
			
		}else{
			$this->sign[$this->name."_".$method]=$this->name."_".$method;
		}// end if	
		//$this->seq->proc->execute($proc_after);
		//$this->seq->execute($seq_after);
		//hr($this->html);
		
		if($this->render != 0){

			//$this->html = $this->getDinamicPanel($this->html);
		}// end if	
		
		$this->log();
		
		$this->log(array(
			"element"=>$this->element,
			"title"=>$this->title,
			"name"=>$this->name,
			"method"=>$method,
			"panel"=>$this->panel
		));	
		
			
		return $this->html;
		
	}// end function	
	
	public function load($type=""){
		
		//$this->seq->execute($this->seq_before_create);
		if(!$this->name){
			return false;	
		}// end if
		
		if($this->class != ""){
			$class = $this->class."_menu_";
			
		}else{
			$class = $this->class_default."_menu_";
		}// end if
		//1:vertical,2:horizontal,3:accordion,4:submit 
		
		switch($this->menu_type){
		case "accordion":
		case 1:
			$class .= "a_";
			break;
		case "horizontal":
		case 2:
			$class .= "h_";
			break;
		case "vertical":
		case 3:
		default;
			$class .= "v_";
			break;
		case "submit":
		case 4:
			$class .= "s_";
			break;
		}// end switch
		
		if($this->class_main == ""){
			$this->class_main = $class."main";
		}// end if

		if($this->class_title == ""){
			$this->class_title = $class."title";
		}// end if

		if($this->class_page == ""){
			$this->class_page = $class."page";
		}// end if

		if($this->class_item == ""){
			$this->class_item = $class."item";
		}// end if
		if($this->class_item_active == ""){
			$this->class_item_active = $class."item_active";
		}// end if
		if($this->class_item_disabled == ""){
			$this->class_item_disabled = $class."item_disabled";
		}// end if
		if($this->class_item_menu == ""){
			$this->class_item_menu = $class."item_menu";
		}// end if
		if($this->class_item_image == ""){
			$this->class_item_image = $class."item_image";
		}// end if

		if($this->class_show == ""){
			$this->class_show = $class."show";
		}// end if

		if($this->class_hide == ""){
			$this->class_hide = $class."hide";
		}// end if

		$this->menuDinamic();
		
		
		
		
		
	}// end fucntion




	private function menu_submit(){
		
		global $seq;
		$ref = $this->getRef();
		//$this->script .= "\tS.newPanel($this->panel)";
		//$this->script .= "\n\t$ref = new sgMenu('$this->menu', $this->panel);";
		$this->cols = 1;
		$cols = $this->cols;
		$nro_items = count($this->action);

		$t = new cls_table(1,1);
		$t->id = "p_".$this->name;
		//$t->border = 1;
		$t->class = $this->class_menu;

		$r=0;
		if($this->untitled!="y"){
			
			
			$t->cell[$r][0]->text = $this->title;
			$t->cell[$r][0]->class = $this->class_title;
			$t->cell[$r][0]->style = $this->title_style;

			if($prop = $seq->cmd->get_param(stripslashes($this->title_propertys))){
				//hr($this->action[$i]->action_propertys);
				foreach($prop as $kk => $vv){
					//eval("\$t->cell[\$r][0]->$kk=\"$vv\";");
					$t->cell[$r][0]->$kk=$vv;
				}// next
			}// end if
			
			
			
			$r++;
		}// end if
		$t->width = $this->width;
		
		
		$group = $seq->cmd->get_param($this->groups);

		$d=0;
		$h=0;// #elements hidden
		$tb = array();
		$t->create_row();
		foreach($this->action as $k => $v){
			/*
			if(isset($group[$v->action])){
				
				$t->merge_row($r);
				$t->header_row($r);
				$t->cell[$r][0]->text=$group[$v->action];
				if($v->class_group!=""){
					$t->cell[$r][0]->class=$v->class_group;
				}else{
					$t->cell[$r][0]->class=$this->class_group;
				}// end if
				if($v->group_style!=""){
					$t->cell[$r][0]->style=$v->group_style;
				}else{
					$t->cell[$r][0]->style=$this->group_style;
				}// end if
				


				
				$t->cell[$r][0]->onclick="document.getElementById('p_$v->action').style.display=(document.getElementById('p_$v->action').style.display=='none')?'':'none'";
				//$t->set_tbody($r);
				$r++;
				$d+=$cols-(($k-$h+$d) % $cols);
				
				
				$tb[]= array("r" => $r, "a" => $v->action);
			}// end if
			*/
			$this->script .= "\n\t$ref.setElement(\"$v->action\",\"".addslashes($v->title)."\");";

			if($v->hidden=="y"){
				$h++;
				continue;
			}// end if
			
			/*if(($k-$h+$d) % $cols==0){
				
				
				$t->create_row();
				$t->row[$r-1]->id="P".$this->panel."_R_".$v->action;
				$r++;
			}// end if*/
			
			
			
			$button = new sgHTML("input");
			$button->type = "button";
			$button->id = "P".$this->panel."_".$v->action;
			$button->name = $button->id;
			$button->value = $v->title;
			/*
			if($v->ajax){
				$function = "setPanelA";
				
			}else{
				$function = "setPanel";	
			}//end if
			
			if(isset($v->open)){
				$function = "setOpenPanel";	
					
				
			}
			*/
			$button->onclick = $this->getActionEvent($v);
			$t->cell[0][0]->text .= $button->render();
			
			/*
			$t->cell[$r-1][($k-$h+$d) % $cols]->id="P".$this->panel."_".$v->action;
			
			$t->cell[$r-1][($k-$h+$d) % $cols]->text = "<a href='#' style='color:redhover'>$v->title</a>";
			$t->cell[$r-1][($k-$h+$d) % $cols]->class = $this->class_item;
			
			if($v->action_style!=""){
				$t->cell[$r-1][($k-$h+$d) % $cols]->style=$this->action_style.$v->action_style;
			}else{
				$t->cell[$r-1][($k-$h+$d) % $cols]->style=$this->action_style;
			}// end if
//hr($v->action_propertys);
			if($v->action_propertys!=""){
				$action_propertys=$v->action_propertys;
			}else{
				$action_propertys=$this->action_propertys;
			}// end if
			if($prop = $this->seq->get_param(stripslashes($action_propertys))){
				//hr($this->action[$i]->action_propertys);
				foreach($prop as $kk => $vv){
					//eval("\$t->cell[\$r-1][(\$k-\$h+\$d) % \$cols]->$kk=\"$vv\";");
					$t->cell[$r-1][($k-$h+$d) % $cols]->$kk=$vv;
				}// next
			}// end if
			
*/
			
		}// next
		
		
		/*
		foreach($tb as $k => $v){
			if($k<count($tb)){
				$t->set_tbody($tb[$k]["r"],$tb[$k+1]["r"]-2);
				$t->tbody[$tb[$k]["r"]]->id="p_".$tb[$k]["a"];
			}// end if

		}// next
		*/
		
		
		$this->html= $t->control();
		$this->script .= "\n\t$ref.init();"; 
		
		//hr($this->html);
		//return $this->html;
	}// end function




	public function getActionEvent($a){

		/*
		if(isset($this->eparams["request_elemement"])){
			$a->request_element = $this->eparams["request_elemement"];
		}// end if


		if(isset($this->eparams["request_panel"])){
			$a->request_panel = $this->eparams["request_panel"];
		}// end if
		*/
		
		if($a->type == 1){
			
			return cfg_action::send(array(
				"async" => ($a->async)?true:false,
				"panel" => ($a->panel)?$a->panel: $this->panel,
				"params" => "action:$a->action;",
				"valid" => $a->valid
				)
			);
			
			
		}

		return cfg_action::send(array(
			"async" => ($a->async)?true:false,
			"panel" => ($a->panel)?$a->panel: $this->panel,
			"params" => $a->sequence,
			"valid" => $a->valid
			)
		);
		
	}// end function


	public function elem_aux(){ 
		
		$div = new sg_html("div");
		$hidden = new sg_html("input");
		$hidden->type = "text";
		
		$hidden->name = "__menu_actions";
		$hidden->value = $this->actionsList;
		$div->inner_html .= "\n\t".$hidden->render();

		$hidden->name = "__menu_link";
		$hidden->value = "222";
		$div->inner_html .= "\n\t".$hidden->render();

		
		return $div->render();
	}// end function











	private function menuType(){
		
		
		switch($this->menu_type){
		case "accordion":
		case 1:
			return "accordion";
			break;
		case "horizontal":
		case 2:
			return "horizontal";
			break;
		case "vertical":
		case 3:
			return "vertical";
			break;
		case 4:
		case "submit":
			return "submit";
			break;
		default;
			return "vertical";
		}// end switch		
		
	}// end function



	private function menuDinamic(){
		global $seq;
		$ref = $this->getRef();	
		
		
		
		$menu = new svMenu("__menu_"."$this->name".$this->panel);
		
		$menu->setRef($ref);
		$menu->icon = $this->icon;
		$menu->typeMenu = $this->menuType();
		if($this->untitled != "y"){
			$menu->setCaption($this->title);	
			
		}
		
		
		
		$this->html = $menu->render();
		
		foreach($this->action as $k => $v){
			if($v->hidden == "y"){
				$h++;
				continue;
			}// end if			
			
			//hr($this->getActionEvent($v));
			$menu->addItem(array(
				"id" => (integer)$v->id, 
				"parentId" => (integer)$v->parent_id,
				"title" => $v->title,
				"img" => $v->image,
				"action" => $this->getActionEvent($v)
			
			
			));
			
			




			

		}// next		
		
		$this->script .= $menu->getScript();
		
	}// end function




}// end class

?>