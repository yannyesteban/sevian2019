<?php



class sgDataList extends sgInput{

	public $subForm = false;

	public function __construct(){
		
		
		
	}
	
	
	public function render(){

		
		$cont = new sgHTML("");
				
		$ele = $cont->add("input");
		$ele->type = "hidden";
		$ele->value = $this->value;		
		$ele->name = $this->name;

		$ele2 = $cont->add("input");
		$ele2->type = "hidden";
		$ele2->value = $this->value;		
		$ele2->name = $this->name."_vi_aux";
		
		
		
		$t = $cont->add(new sgTable(1));
		$t->typeRender("div");
		
		$t->id = $this->name."_t_".$this->panel;
		
		if(!$ref = $this->getRef()){
			$ref = $this->name;	
		}		
		
		$values = explode(",", $this->value);
		
		foreach ($this->data as $k => $v){
			$r = $t->insertRow();
			$chk = new sgHTML("input");
			$chk->type = "checkbox";
			$chk->onclick = "$ref.get('$this->name').getChek(this)";
			
			foreach($this->events as $kk => $vv){
				$chk->$kk .= $vv;
				
			}			
			
			$chk->name = $this->name."_chk_aux";
			$chk->value = $v[0];
			
			
			if(isset($this->subForm["records"][$chk->value])){
				$chk->checked = true;
				
			}
			
			$r->cells[0]->appendChild($chk);
			$r->cells[0]->appendChild($v[1]);
		}// next
		
		
		
		
		return $cont->render();
		
	}// end function
	
	public function getScript(){
		
		if(!$ref = $this->getRef()){
			$ref = $this->name;	
		}

		$script = "";


		$p = array();
		$p["input"] = "sgCheckData";		
		$p["type"] = $this->type;
		$p["name"] = $this->name;
		$p["id"] = $this->id;		

		$p["title"] = $this->title;
		$p["value"] = $this->value;
		$p["valueInit"] = $this->value;
		$p["index"] = $this->subForm["detail"];
		$p["ref"] = "$ref";
		$p["records"] = array();//$this->subForm["records"];
		$p["tableId"] = $this->name."_t_".$this->panel;
		
		if(count($this->data) > 0){
			$p["data"] = $this->getDataJson();
		}// end if		
		$p["parent"] = $this->parent;		
		if($this->childs){
			$p["childs"] = true;
		}// end if		


		
		$json = json_encode(array_map(function($v){return is_string($v)? utf8_encode($v): $v;}, $p), JSON_PRETTY_PRINT);		
		
		$script .= "\n\t".$ref.".set($json);";
		
		return $script;
	
		$script .= "\n\t".$ref." = new sgCheckData($json);";
		return $script;		
		
	}// end function

	public function getScriptxx(){
		
		if(!$ref = $this->getRef()){
			$ref = $this->name;	
		}


		$script = "";
	
		$opt["panel"] = "$this->panel";
		$opt["type"] = "check";
		$opt["name"] = "$this->name";
		$opt["formName"] = "sgpanel_$this->panel";
		$opt["value"] = $this->value;
		$opt["index"] = $this->subForm["detail"];
		$opt["ref"] = "$ref";
		$opt["records"] = $this->subForm["records"];
		
		$opt["tableId"] = $this->name."_t_".$this->panel;
		


		$json = json_encode($opt, JSON_PRETTY_PRINT);
	
		$script .= "\n\t".$ref." = new sgCheckData($json);";
		return $script;		
		
	}// end function
	
	
	
}// end class


?>