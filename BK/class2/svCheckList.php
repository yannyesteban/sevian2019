<?php


class svCheckList extends sgInput{
	
	public $dinamic = true;

	public function render(){
		
		$cont = new sgHTML("");
			
		$ele = $cont->add("textarea");
		$ele->type = "hidden";
		$ele->innerHTML = $this->value;		
		$ele->name = $this->name;

		$ele2 = $cont->add("textarea");
		$ele2->type = "hidden";
		$ele2->innerHTML = $this->value;		
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
			$chk->onclick = "$ref.e.$this->name.check(this)";
			
			foreach($this->events as $kk => $vv){
				$chk->$kk .= $vv;
				
			}			
			
			$chk->name = $this->name."_chk_aux";
			$chk->value = $v[0];
			
			
			if(in_array($v[0], $values)){
				$chk->checked = true;
				
			}
			
			$r->cells[0]->appendChild($chk);
			$r->cells[0]->appendChild($v[1]);
		}
		
		
		
		
		return $cont->render();
	}// end function	
	
	
	
	public function getScript(){
		
		if(!$ref = $this->getRef()){
			$ref = $this->name;	
		}


		$script = "";


		$p = array();
		$p["input"] = "_sgCheckInput";		
		$p["type"] = $this->type;
		$p["name"] = $this->name;
		$p["id"] = $this->id;		

		$p["title"] = $this->title;
		$p["value"] = $this->value;
		$p["valueInit"] = $this->value;


		if(count($this->data) > 0){
			$p["data"] = $this->getDataJson();
		}// end if		
		$p["parent"] = $this->parent;		
		if($this->childs){
			$p["childs"] = true;
		}// end if		
		



		
		if($this->rules){		
			//$p["rules"] = json_decode(json_encode($this->rules ));
			$p["rules"] = $this->rules;
		}// end if

		$p["events"] = $this->events;

		//$json = json_encode($p);
		
		$json = json_encode(array_map(function($v){return is_string($v)? utf8_encode($v): $v;}, $p));		
		
		$script .= "\n\t".$ref.".set($json);";
		
		return $script;
		


	
		$opt["name"] = $this->name;
		$opt["formName"] = $this->formName;

		$json = json_encode($opt);
	
		$script .= "\n\t".$ref." = new sgCheckGrid($json);";
		return $script;

		
		
	}// end function		
	
	
}// end class




?>