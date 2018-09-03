<?php
class setCheck2 extends sgInput{
	public $input = "dataList";
	
	
	public function render(){
		
		$div = new sgHTML("div");
		$div->id = $this->id."_inpct";
		
		$input = $div->add(new sgHTML("input"));
		
		$input->type = "hidden";
		$input->name = $this->name;
		$input->id = $this->id;
		
		return $div->render();
		
	}// end function	


	public function getScript(){
		
		if(!$ref = $this->getRef()){
			$ref = $this->name;	
		}
		
		$script = "";

		$p = array();
		$p["input"] = $this->input;		
		$p["type"] = $this->type;
		$p["name"] = $this->name;
		$p["id"] = $this->id;		
		$p["title"] = $this->title;
		$p["value"] = $this->value;
		$p["valueInit"] = $this->value;
		$p["parent"] = $this->parent;		
		
		if($this->childs){
			$p["childs"] = true;
		}// end if		

		if($this->rules){		
			$p["rules"] = $this->rules;
		}// end if

		
		$p["events"] = $this->events;

		$p["targetId"] = $this->id."_inpct";
		
		if(count($this->data) > 0){
			$p["data"] = $this->getDataJson();
		}// end if



		$f = $this->f = new cls_form;
		$f->name = $this->form;//"xxx";//$this->name;
		$f->method = "data_records";
		$f->panel = $this->panel;
		$f->subElement = true;
		$f->masterData = $this->masterData;
		$f->master = $this->master;
		$f->q_data = $this->q_data;
		$f->fieldsPrefix = "f".$this->position."_";
		


		$f->execute($f->name, $f->method);
		
	


		$f->pagination = false;
		$p["dataRecord"] = $f->multiData($this->q_data);


		

		
		//$json = json_encode(array_map(function($v){return is_string($v)? utf8_encode($v): $v;}, $p));		
		$json = sgJSON::encode($p);	
		$script .= "\n\t".$ref.".set($json);";
		
		
		
		return $script;
		
	}	
	
}

class inputSings extends sgInput{
	public $input = "inputSings";
	
	
	public function render(){
		
		$div = new sgHTML("div");
		$div->id = $this->id."_inpct";
		
		$input = $div->add(new sgHTML("input"));
		
		$input->type = "hidden";
		$input->name = $this->name;
		$input->id = $this->id;
		
		return $div->render();
		
	}// end function	


	public function getScript(){
		
		if(!$ref = $this->getRef()){
			$ref = $this->name;	
		}
		
		$script = "";

		$p = array();
		$p["input"] = $this->input;		
		$p["type"] = $this->type;
		$p["name"] = $this->name;
		$p["id"] = $this->id;		
		$p["title"] = $this->title;
		$p["value"] = $this->value;
		$p["valueInit"] = $this->value;
		$p["parent"] = $this->parent;		
		
		if($this->childs){
			$p["childs"] = true;
		}// end if		

		if($this->rules){		
			$p["rules"] = $this->rules;
		}// end if

		
		$p["events"] = $this->events;

		$p["targetId"] = $this->id."_inpct";
		
		if(count($this->data) > 0){
			$p["data"] = $this->getDataJson();
		}// end if



		$f = $this->f = new cls_form;
		$f->name = $this->form;//"xxx";//$this->name;
		$f->method = "data_records";
		$f->panel = $this->panel;
		$f->subElement = true;
		$f->masterData = $this->masterData;
		$f->master = $this->master;
		$f->q_data = $this->q_data;
		$f->fieldsPrefix = "f".$this->position."_";
		


		$f->execute($f->name, $f->method);
		
	


		$f->pagination = false;
		$p["dataRecord"] = $f->multiData($this->q_data);


		

		
		$json = json_encode(array_map(function($v){return is_string($v)? utf8_encode($v): $v;}, $p));		
		//$json = json_encode($p);		
		
		$script .= "\n\t".$ref.".set($json);";
		
		
		
		return $script;
		
	}	
	
}


class setCheck extends sgInput{
	public $input = "checkList";
	
}

class setCheckM extends sgInput{
	
	
	
	public function render(){
		
		$div = new sgHTML("div");
		$div->id = $this->id."_inpct";
		
		$input = $div->add(new sgHTML("input"));
		
		$input->type = "hidden";
		$input->name = $this->name;
		$input->id = $this->id;
		
		return $div->render();
		
	}	


	public function getScript(){
		
		if(!$ref = $this->getRef()){
			$ref = $this->name;	
		}
		
		$script = "";

		$p = array();
		$p["input"] = "checkList";		
		$p["type"] = $this->type;
		$p["name"] = $this->name;
		$p["id"] = $this->id;		
		$p["title"] = $this->title;
		$p["value"] = $this->value;
		$p["valueInit"] = $this->value;
		$p["parent"] = $this->parent;		
		
		if($this->childs){
			$p["childs"] = true;
		}// end if		

		if($this->rules){		
			$p["rules"] = $this->rules;
		}// end if

		$p["events"] = $this->events;

		$p["targetId"] = $this->id."_inpct";
		
		if(count($this->data) > 0){
			$p["data"] = $this->getDataJson();
		}// end if

/*
		$f = $this->f = new cls_form;
		$f->name = $this->form;//"xxx";//$this->name;
		$f->method = "data_records";
		$f->panel = $this->panel;
		$f->subElement = true;
		$f->masterData = $this->masterData;
		$f->master = $this->master;
		$f->q_data = $this->q_data;
		$f->fieldsPrefix = "f".$this->position."_";
		
		
		if($this->type == "sf_check"){
			
			//$f->method = "list_set";
		}
		
		
		$f->setRef($this->getRef().".e.$this->name");		
		
		*/
		$json = json_encode(array_map(function($v){return is_string($v)? utf8_encode($v): $v;}, $p));		
		
		$script .= "\n\t".$ref.".set($json);";
		
		return $script;
		
	}	
	
}


class formInput extends sgInput{
	private $f;
	public $form = "";
	public $q_data = "";
	public $master = "";
	public $prefix = "";
	
	public function render(){
		
		
		
		$f = $this->f = new cls_form;
		$f->name = $this->form;//"xxx";//$this->name;
		$f->method = "multi";
		$f->panel = $this->panel;
		$f->subElement = true;
		$f->masterData = $this->masterData;
		$f->master = $this->master;
		$f->q_data = $this->q_data;
		$f->fieldsPrefix = $this->name."_";
		
		
		if($this->type == "sf_check"){
			
			$f->method = "list_set";
		}
		
		
		$f->setRef($this->getRef().".e.$this->name");



			
			
		return $f->render();
		
	}
	
	public function getScript(){
		return $this->f->getScript();
		
	}
	
	
	
}// end class


?>