<?php
class inputDate extends sgInput{
	public $params_cal = false;
	public $onselectday = false;
	public $conteiner  = "";
	
	public function render(){
		
		if(!$ref = $this->getRef()){
			$ref = $this->name;	
		}		
		
		switch ($this->type){
			default:
			case "calendar":
				$ele = new sgHTML("input");	
				break;
			
		}// end switch
		
		$id = $this->id;
		$ele->name = $this->name;


		switch($this->type){
		default:
		case "calendar":
		
			$ele->type = "hidden";
			$ele->value = $this->value;

			$this->html = $ele->render();
			
			
			
			$ele_mask = new sgHTML("input");	
			$ele_mask->id = $this->name."_cal_mask";
			$ele_mask->name = $this->name."_cal_mask";
			$ele_mask->type = "text";
			if($this->value!=""){
				$date = $this->get_date_from($this->value, "y-m-d");
				$ele_mask->value = $this->eval_date_format($date["year"], $date["month"], $date["day"], "dd/mm/yy");
			}else{
				$ele_mask->value = "";
			}// end if
			if(is_array($this->events)){
				foreach($this->events as $k => $v){
					$ele_mask->$k = $v;
					
				}// next
			}// end if
			$this->html .= $ele_mask->render();

			$ele_dpick = new sgHTML("input");	
			$ele_dpick->id = $this->name."_dpick_aux";
			$ele_dpick->name = $this->name."_dpick_aux";
			$ele_dpick->type = "button";
			$ele_dpick->value = "";
			$ele_dpick->onclick = "\n\t".$ref.".get('$this->name').show(this, event)";
			
			$this->html .= $ele_dpick->render();
			

			
			break;	
		
		}// end switch

	
			
		
		return $this->html;
		
	}// end function
	
	public function getScript(){
		if(!$ref = $this->getRef()){
			$ref = $this->name;	
		}
		
		$script = "";

		$p = array();
		$p["input"] = "dateElement";		
		$p["type"] = $this->type;
		$p["name"] = $this->name;
		$p["id"] = $this->id;			
		$p["class_cal"] = $this->class;
		$p["conteiner"] = $this->conteiner;


		$p["params_cal"] = $this->params_cal;
		
	
		foreach($this->params as $k => $v){
			
			$p[$k] = $v;
		}// next		


		$rules = "";
		foreach($this->rules as $k => $v){
			$rules .= (($rules!="")?",":"")."$k:$v";
		}// next
		
		if($rules){		
			$p["childs"] = "\n\t".$ref.".rules = { $rules };";
		}// end if
		$p["events"] = $this->events;

	



			

/*
			if($this->params_cal!=""){
				
				$script .= "\n\t$ref.params_cal={ $this->params_cal }";
			}// end if
			if($this->onselectday!=""){
				
				$script .= "\n\t$ref.onselectday=function(yy, mm, dd){ $this->onselectday}";
			}// end if
			if(!isset($this->rules["date"])){
				$this->rules["date"]="{}";
			}// end if

*/


		




		$json = json_encode($p);
		$script .= "\n\t".$ref.".set($json);";
		
		
		if($this->onselectday){
			$script .= "\n\t".$ref.".get('$this->name').onselectday=function(yy, mm, dd){ $this->onselectday };";
			
		}
		
		return $script;
		
		
	}// end function
	
	public function getScriptXX(){
		if(!$ref = $this->getRef()){
			$ref = $this->name;	
		}
		
		$script = "";

		
		
		$opt = "";
		foreach($this->params as $k => $v){
			$opt .= (($opt != "")?",":"")."$k: $v";
		}// next		
		
		
		$script .= "\n\t$ref = new dateElement('$this->name', document.forms['$this->formName']);";
		if(isset($this->conteiner)){
			$script .= "\n\t$ref.setConteiner('$this->conteiner');";
		}// end if		



$script .= "\n\t$ref.class_cal = '$this->class';";


			if($this->params_cal!=""){
				
				$script .= "\n\t$ref.params_cal={ $this->params_cal }";
			}// end if
			if($this->onselectday!=""){
				
				$script .= "\n\t$ref.onselectday=function(yy, mm, dd){ $this->onselectday}";
			}// end if
			if(!isset($this->rules["date"])){
				$this->rules["date"]="{}";
			}// end if

		if($this->parent){
			$script .= "\n\t$ref.parent = '$this->parent';";
		}// end if;
		if($this->childs){
			$script .= "\n\t$ref.childs = true;";
		}// end if;

		$rules = "";
		foreach($this->rules as $k => $v){
			$rules .= (($rules!="")?",":"")."$k:$v";
		}// next
		if($rules){		
			$script .= "\n\t$ref.rules = { $rules };";
		}// end if
		
			
		foreach($this->events as $k => $v){
			$script .= "\n\t$ref.addEvent('$k',function(){ $v } );";
		}// next	
		
		return $script;
	}// end function
	
	public function get_date_from($query, $pattern){
			$aux = array();
			$date = "";
	
			$pattern_ = preg_replace("/\bd\b/","([0-9]{1,2})", $pattern);
			$pattern_ = preg_replace("/\bm\b/","[0-9]{1,2}", $pattern_);
			$pattern_ = preg_replace("/\by\b/","[0-9]{4}", $pattern_);
			if(preg_match("|$pattern_|isx", $query, $c)){
				$aux["day"] = $c[1];
			}//e nd if
			
			$pattern_ = preg_replace("/\bd\b/","[0-9]{1,2}", $pattern);
			$pattern_ = preg_replace("/\bm\b/","([0-9]{1,2})", $pattern_);
			$pattern_ = preg_replace("/\by\b/","[0-9]{4}", $pattern_);
			if(preg_match("|$pattern_|isx", $query, $c)){
				$aux["month"] = $c[1];
			}//e nd if
	
			$pattern_ = preg_replace("/\bd\b/","[0-9]{1,2}", $pattern);
			$pattern_ = preg_replace("/\bm\b/","[0-9]{1,2}", $pattern_);
			$pattern_ = preg_replace("/\by\b/","([0-9]{4})", $pattern_);
			if(preg_match("|$pattern_|isx", $query, $c)){
				$aux["year"] = $c[1];
			}//e nd if
	
			return $aux;
		
			//eval_date_format(" 24/10/1975 ", "d/m/y");
			//eval_date_format(" 1975/14/02 ", "y/d/m");
		
	}// end function
	
	
	public function eval_date_format($yy, $mm, $dd, $query){
		$day = str_pad($dd, 2, "0", STR_PAD_LEFT);
		$month = str_pad($mm, 2, "0", STR_PAD_LEFT);
		$year = $yy;
	

		$query = preg_replace("/\by\b/",$year, $query);
		$query = preg_replace("/\byy\b/",$year, $query);
		$query = preg_replace("/\bm\b/",$mm, $query);
		$query = preg_replace("/\bmm\b/",$month, $query);
		$query = preg_replace("/\bd\b/",$dd, $query);
		$query = preg_replace("/\bdd\b/",$day, $query);
	
	
		return $query;
		//echo eval_date_format(1975, 2, 24, "dd/mm/y");	
	}// end function
	
}// end class



class inputDate2 extends sgInput{
	public $params_cal = false;
	public $onselectday = false;
	public function render(){
		
		
		
		switch ($this->type){
			default:
			case "calendar":
				$ele = new sgHTML("input");	
				break;
			
		}// end switch
		
		$ele->id = "f$this->panel"."_".$this->name;
		$ele->name = $this->name;

		$this->script .= "\n\t".$this->objScriptName." = new dateElement('$this->name', document.forms['$this->formScriptName']);";
		if($this->conteiner){
			$this->script .= "\n\t".$this->objScriptName.".setConteiner('$this->conteiner');";
		}// end if
		switch($this->type){
		default:
		case "calendar":
		
			$ele->type = "hidden";
			$ele->value = $this->value;

			$this->html = $ele->render();
			
			
			
			$ele_mask = new sgHTML("input");	
			$ele_mask->id = $this->name."_cal_mask";
			$ele_mask->name = $this->name."_cal_mask";
			$ele_mask->type = "text";
			if($this->value!=""){
				$date = $this->get_date_from($this->value, "y-m-d");
				$ele_mask->value = $this->eval_date_format($date["year"], $date["month"], $date["day"], "dd/mm/yy");
			}else{
				$ele_mask->value = "";
			}// end if
			if(is_array($this->event)){
				foreach($this->event as $k => $v){
					$ele_mask->$k = $v;
					
				}// next
			}// end if
			$this->html .= $ele_mask->render();

			$ele_dpick = new sgHTML("input");	
			$ele_dpick->id = $this->name."_dpick_aux";
			$ele_dpick->name = $this->name."_dpick_aux";
			$ele_dpick->type = "button";
			$ele_dpick->value = "";
			$ele_dpick->onclick = "\n\t".$this->objScriptName.".show(this, event)";
			$this->script .= "\n\t".$this->objScriptName.".class_cal = '$this->class';";
			$this->html .= $ele_dpick->render();
			
			if($this->params_cal!=""){
				
				$this->script .= "\n\t".$this->objScriptName.".params_cal={ $this->params_cal }";
			}// end if
			if($this->onselectday!=""){
				
				$this->script .= "\n\t".$this->objScriptName.".onselectday=function(yy, mm, dd){ $this->onselectday}";
			}// end if
			if(!isset($this->rules["date"])){
				$this->rules["date"]="{}";
			}// end if
			
			break;	
		
		}// end switch



		if($this->parent){
			$this->script .= "\n\t".$this->objScriptName.".parent = '$this->parent';";
		}// end if;
		if($this->childs){
			$this->script .= "\n\t".$this->objScriptName.".childs = true;";
		}// end if;

		$rules = "";
		foreach($this->rules as $k => $v){
			$rules .= (($rules!="")?",":"")."$k:$v";
		}// next
		if($rules){		
			$this->script .= "\n\t".$this->objScriptName.".rules = { $rules };";
		}// end if
		
			
		foreach($this->event as $k => $v){
			$this->script .= "\n\t".$this->objScriptName.".addEvent('$k',function(){ $v } );";
		}// next		
			
		
		return $this->html;
		
	}// end function
	public function get_date_from($query, $pattern){
			$aux = array();
			$date = "";
	
			$pattern_ = preg_replace("/\bd\b/","([0-9]{1,2})", $pattern);
			$pattern_ = preg_replace("/\bm\b/","[0-9]{1,2}", $pattern_);
			$pattern_ = preg_replace("/\by\b/","[0-9]{4}", $pattern_);
			if(preg_match("|$pattern_|isx", $query, $c)){
				$aux["day"] = $c[1];
			}//e nd if
			
			$pattern_ = preg_replace("/\bd\b/","[0-9]{1,2}", $pattern);
			$pattern_ = preg_replace("/\bm\b/","([0-9]{1,2})", $pattern_);
			$pattern_ = preg_replace("/\by\b/","[0-9]{4}", $pattern_);
			if(preg_match("|$pattern_|isx", $query, $c)){
				$aux["month"] = $c[1];
			}//e nd if
	
			$pattern_ = preg_replace("/\bd\b/","[0-9]{1,2}", $pattern);
			$pattern_ = preg_replace("/\bm\b/","[0-9]{1,2}", $pattern_);
			$pattern_ = preg_replace("/\by\b/","([0-9]{4})", $pattern_);
			if(preg_match("|$pattern_|isx", $query, $c)){
				$aux["year"] = $c[1];
			}//e nd if
	
			return $aux;
		
			//eval_date_format(" 24/10/1975 ", "d/m/y");
			//eval_date_format(" 1975/14/02 ", "y/d/m");
		
	}// end function
	
	
	public function eval_date_format($yy, $mm, $dd, $query){
		$day = str_pad($dd, 2, "0", STR_PAD_LEFT);
		$month = str_pad($mm, 2, "0", STR_PAD_LEFT);
		$year = $yy;
	
		$query = preg_replace("/\by\b/",$year, $query);
		$query = preg_replace("/\byy\b/",$year, $query);
		$query = preg_replace("/\bm\b/",$mm, $query);
		$query = preg_replace("/\bmm\b/",$month, $query);
		$query = preg_replace("/\bd\b/",$dd, $query);
		$query = preg_replace("/\bdd\b/",$day, $query);
	
	
		return $query;
		//echo eval_date_format(1975, 2, 24, "dd/mm/y");	
	}// end function
	
}// end class




?>