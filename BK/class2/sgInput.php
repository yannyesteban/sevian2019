<?php

/*****************************************************************
creado:       08/01/2016
modificado:   08/01/2016
por:          Yanny Nuñez
*****************************************************************/

class sgInput{
	
	public $input = "";
	public $type = "";
	public $name = "";
	public $id = "";
	public $value = false;
	public $parentValue = false;
	public $formName = "";
	
	public $parent = false;
	public $childs = false;
	
	public $params = array();

	public $data = array();
	public $dinamic_data = false;
	public $masterData = array();
	public $propertys = array();
	public $rules = array();
	public $events = array();
	
	private $_html = "";
	private $_script = "";
	private $_ref = "";
	
	
	public function __construct($type="", $name="") {
		if($type !=""){
			$this->type = $type;
		}// end if
		
		if($name !=""){
			$this->name = $name;
		}// end if

	}// end function

	public function setRef($name){
		$this->_ref = $name;	
	}// end function

	public function getRef(){
		return $this->_ref;	
	}// end function

	public function setParams($params){
		foreach($params as $k => $v){
			$this->$k = $v;	
		}
	}
			
	public function render(){
		
		$div = new sgHTML("div");
		$div->id = $this->id."_inpct";
		
		$input = $div->add(new sgHTML("input"));
		
		$input->type = "hidden";
		$input->name = $this->name;
		$input->value = $this->value;
		$input->id = $this->id;
		
		return $div->render();

		
	}	
	public function getScript(){
		
		if(!$ref = $this->getRef()){
			$ref = $this->name;	
		}
		
		$p = array();

		$p["input"] = $this->input;		
		$p["type"] = $this->type;
		$p["name"] = $this->name;
		$p["id"] = $this->id;		
		$p["title"] = $this->title;
		$p["value"] = $this->value;
		$p["valueInit"] = $this->value;
		$p["parent"] = $this->parent;	
		$p["dinamic_data"] = $this->dinamic_data;		
		
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


		//$json = json_encode(array_map(function($v){return is_string($v)? utf8_encode($v): $v;}, $p));		
		$json = json_encode($p);	
		
		$script = "\n\t".$ref.".set($json);";
		
		return $script;
		
	}	
	public function getDataJson(){
		$a = array();
		if(count($this->data) > 0){
			foreach($this->data as $k => $data){
				//$a[] = array_map(function($v){return is_string($v)? utf8_encode($v): $v;}, $data);
				$a[] = $data;
			}// next
		}// end if		
		return $a;
	}
	
	public function getDataScript($var = "_DD"){
		$str = "\n\t"."var $var = new Array();";
		foreach($this->data as $k => $v){
			$v["text"]= str_replace(chr(10),"",$v["text"]);
			$v["text"]= str_replace(chr(13)," ",$v["text"]);
			$str.= "\n\t".$var."[$k] = ['".$v["value"]."','".$v["text"]."','".$v["parent"]."'];";
		}// next
		return $str; 
	}// end function
	
	public function updateData($value=false){
		$str = $this->getDataScript("_DD");
		
		$str .= "\n\t".$this->objScriptName.".data = _DD;";
		if($value === false){
			$str .= "\n\t".$this->objScriptName.".updateData($this->objScriptName.getValue());";
		}else{
			$str .= "\n\t".$this->objScriptName.".updateData('$value');";
		}// end if
		return $str; 
	}// end function
	
	public function getParentValue(){
		if(isset($this->masterData[$this->parent])){
			return $this->masterData[$this->parent];
		}
		return false;	
		
	}
	
}

class stdInput extends sgInput{
	public $input = "_sgInput";
	public $type = "";
	public $name = "";
	public $id = "";
	public $value = false;
	public $parentValue = false;
	public $formName = "";
	
	public $parent = false;
	public $childs = false;
	
	public $params = array();

	public $data = array();
	public $propertys = array();
	public $rules = array();
	public $events = array();
	
	private $_html = "";
	private $_script = "";
	private $_ref = "";
	
	public function __construct($type="", $name=""){

		if($name !=""){
			$this->name = $name;
		}// end if

		if($type !=""){
			$this->type = $type;
		}// end if

	}// end function
	public function setParams($params){
		foreach($params as $k => $v){
			$this->$k = $v;	
		}
	}
	
	public function setRef($name){
		$this->_ref = $name;	
	}// end function
	public function getRef(){
		return $this->_ref;	
	}// end function
	
	public function render(){
		
		switch ($this->type){
		case "textarea":	
			$ele = new sgHTML("textarea");
			break;
		case "select":
		case "multiple":
			$ele = new sgHTML("select");
			break;	
		default:
			$ele = new sgHTML("input");	
			break;
		}// end switch
		
		$ele->name = $this->name;
		$ele->id = $this->id;

		switch($this->type){
		
		case "textarea":
			$ele->innerHTML = $this->value;
			break;	
		case "select":
			$ele->innerHTML = $this->getSelectOptions();
			break;	
		case "multiple":
			if($this->parent == ""){
				$ele->innerHTML = $this->getSelectOptions();
			}// end if
			break;	
		default:
			$ele->type = $this->type;
			$ele->value = $this->value;
			break;
		}// end switch



		foreach($this->propertys as $k => $v){
			$ele->$k = $v;	
		}// next
		
		return $ele->render();
		
	}// end function

	public function getScript1(){
		
		if(!$ref = $this->getRef()){
			$ref = $this->name;	
		}
		
		$script = "";

		$p = array();
		$p["input"] = "_sgInput";		
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
		
		
		
		
	}// end function


	public function getScriptXX(){
		
		if(!$ref = $this->getRef()){
			$ref = $this->name;	
		}
		
		$script = "";

		$script .= "\n\t".$ref." = new stdInput('$this->type', '$this->name', document.forms['$this->formName']);";
		$script .= "\n\t".$ref.".name = '$this->name';";
		$script .= "\n\t".$ref.".id = '$this->id';";
		$script .= "\n\t".$ref.".formName = '$this->formName';";

		if(count($this->data) > 0){
			$script .= $this->getDataScript("_DD");
			$script .= "\n\t".$ref.".data = _DD;";
		}// end if

		if($this->parent){
			$script .= "\n\t".$ref.".parent = '$this->parent';";
		}// end if
		
		if($this->childs){
			$script .= "\n\t".$ref.".childs = true;";
		}// end if

		$rules = "";
		foreach($this->rules as $k => $v){
			$rules .= (($rules!="")?",":"")."$k:$v";
		}// next
		
		if($rules){		
			$script .= "\n\t".$ref.".rules = { $rules };";
		}// end if
		
		foreach($this->events as $k => $v){
			if($k!="onparentchange"){
				$script .= "\n\t".$ref.".events['$k'] = function(event){ $v };";
			}
			
		}// next		
		
		if(isset($this->events["onparentchange"])){
			$script .= "\n\t".$ref.".onparentchange = function(event){ ".$this->events["onparentchange"]." };";
		}
		
		return $script;
		
		
	}// end function


	public function getSelectOptions(){
		$opt = new sgHTML("option");

		$sel = explode(",", strtoupper($this->value));
		$str = "";
		$parentValue = $this->getParentValue();
		foreach ($this->data as $k => $v){
			
			if($this->parent and isset($v[2]) and $parentValue != $v[2] and $v[2] != "*"){
				continue;	
				
			}
			//echo($this->name);
			$opt->value = $v[0];
			$opt->text = $v[1];
			if(in_array(trim(strtoupper($opt->value)), $sel)){
				$opt->selected = "selected";
			}else{
				$opt->selected = false;
			}// end if
			$str .= "\n\t\t".$opt->render();
		}//next
		
		//hr($str);
		return $str;

	}// end function		

	public function getSelectOptionsNO(){
		$opt = new sgHTML("option");

		$sel = explode(",", strtoupper($this->value));
		
		$str = "";
		$parentValue = $this->getParentValue();
		foreach ($this->data as $k => $v){
			
			if($this->parent and $parentValue != $v["parent"]){
				continue;	
				
			}
			
			$opt->value = $v["value"];
			$opt->text = $v["text"];
			if(in_array(trim(strtoupper($opt->value)), $sel)){
				$opt->selected = true;
			}else{
				$opt->selected = false;
			}// end if
			$str .= "\n\t\t".$opt->render();
		}//next
		return $str;

	}// end function		

	
	public function getDataScript($var = "_DD"){
		$str = "\n\t"."var $var = new Array();";
		foreach($this->data as $k => $v){
			$v["text"]= str_replace(chr(10), "", $v["text"]);
			$v["text"]= str_replace(chr(13), " ", $v["text"]);
			$str.= "\n\t".$var."[$k] = ['".$v["value"]."','".$v["text"]."','".$v["parent"]."'];";
		}// next
		return $str; 
	}// end function	
	
}// end class

class inputParams extends sgInput{
	
	public $css = "";
	public $mode = 1;
	public $ele_params = "";	
	public function render(){
		
		
		/*var ip = new inputParams("x");
		
		var f = new Array();
		
		ip.value_ini = "if:\"aaa\";do:perla;name:Ums;class:sigefor;then:hijo:Yanny;ix:4654;else:5465;when:ll;endif";
		f.push({name:"name",title:"Name",type:"t", value:"yanny"});
		f.push({name:"title",title:"Titulo",type:"t", value:"Menu Principal"});
		f.push({name:"class",title:"Clase",type:"t", value:"sigefor"});
		f.push({name:"type",title:"Tipo",type:"s", data:{y:"y", n:'no', '':9}, value:""});
		f.push({name:"sign",title:"Senales",type:"b", value:"yanny"});
		ip.fields = f; 
		ip.init();*/		
		
	
		
		switch ($this->type){
		default:
			break;	
		}// end switch

		$ele = new sgHTML("input");
		$ele->type = "hidden";
		$ele->value = $this->value;		
		$ele->name = $this->name."_ele_aux";
		
		$div = new sgHTML("div");
		$div->id = $this->id."_cont";

				
	
		
		return $this->html =  $ele->render().$div->render();
		
	}// end function
	public function getScript(){

		if(!$ref = $this->getRef()){
			$ref = $this->name;	
		}
		
		$script = "";

		$p = array();

		
		$p["input"] = "inputParams";		
		$p["type"] = $this->type;
		$p["name"] = $this->name;
		$p["id"] = $this->id;		
		$p["title"] = $this->title;
		$p["value"] = $this->value;
		
		$p["valueInit"] = $this->value;
		$p["parent"] = $this->parent;	
		$p["dinamic_data"] = $this->dinamic_data;			
		$p["cont"] = $this->id."_cont";
		$p["ele_aux"] = $this->name."_ele_aux";

		$p["fields"] = $this->ele_params;
		



		if(count($this->data) > 0){
			$p["data"] = $this->getDataJson();
		}// end if		
		$p["parent"] = $this->parent;		
		if($this->childs){
			$p["childs"] = true;
		}// end if		


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

		$json = json_encode($p);
		$script .= "\n\t".$ref.".set($json);";
		
		return $script;
		
		if(!$ref = $this->getRef()){
			$ref = $this->name;	
		}
		
		$script = "";

		
		
		$opt = "";
		foreach($this->params as $k => $v){
			$opt .= (($opt != "")?",":"")."$k: $v";
		}// next


		//$script .= "\n\t".$ref." = new inputParams({ name: '$this->name', id: '$id', cont: '$div->id', classCode: '$this->class', form: document.forms['$this->formName'], ele_aux: '$ele->name', mode: '$this->mode', $opt});";
		$script .= "\n\t".$ref." = new inputParams('$this->type', '$this->name');";
		$script .= "\n\t".$ref.".name = '$this->name';";


		$script .= "\n\t".$ref.".cont = '$this->id"."_cont';";
		$script .= "\n\t".$ref.".form = document.forms['$this->formName'];";
		
		
		$script .= "\n\t".$ref.".formName = '$this->formName';";
		$script .= "\n\t".$ref.".ele_aux = '".$this->name."_ele_aux"."';";


		if($this->ele_params){

			$script .= "\n\t".$ref.".fields = [ $this->ele_params ];";
		}
		//$this->script .= "\n\t".$this->objScriptName.".value = \"$this->value\"";
		//$this->script .= "\n\t".$this->objScriptName.".value_ini = \"$this->value\";";





		$rules = "";
		foreach($this->rules as $k => $v){
			$rules .= (($rules!="")?",":"")."$k:$v";
		}// next
		if($rules){		
			$script .= "\n\t".$ref.".rules = { $rules };";
		}// end if
		
			
		foreach($this->events as $k => $v){
			$script .= "\n\t".$ref.".addEvent('$k',function(){ $v } );";
		}// next
		
		return $script;
		
	}


	public function getScriptXX(){
		
		if(!$ref = $this->getRef()){
			$ref = $this->name;	
		}
		
		$script = "";

		
		
		$opt = "";
		foreach($this->params as $k => $v){
			$opt .= (($opt != "")?",":"")."$k: $v";
		}// next


		//$script .= "\n\t".$ref." = new inputParams({ name: '$this->name', id: '$id', cont: '$div->id', classCode: '$this->class', form: document.forms['$this->formName'], ele_aux: '$ele->name', mode: '$this->mode', $opt});";
		$script .= "\n\t".$ref." = new inputParams('$this->type', '$this->name');";
		$script .= "\n\t".$ref.".name = '$this->name';";


		$script .= "\n\t".$ref.".cont = '$this->id"."_cont';";
		$script .= "\n\t".$ref.".form = document.forms['$this->formName'];";
		
		
		$script .= "\n\t".$ref.".formName = '$this->formName';";
		$script .= "\n\t".$ref.".ele_aux = '".$this->name."_ele_aux"."';";


		if($this->ele_params){

			$script .= "\n\t".$ref.".fields = [ $this->ele_params ];";
		}
		//$this->script .= "\n\t".$this->objScriptName.".value = \"$this->value\"";
		//$this->script .= "\n\t".$this->objScriptName.".value_ini = \"$this->value\";";





		$rules = "";
		foreach($this->rules as $k => $v){
			$rules .= (($rules!="")?",":"")."$k:$v";
		}// next
		if($rules){		
			$script .= "\n\t".$ref.".rules = { $rules };";
		}// end if
		
			
		foreach($this->events as $k => $v){
			$script .= "\n\t".$ref.".addEvent('$k',function(){ $v } );";
		}// next
		
		return $script;
		
	}

}// end class

class inputValid extends sgInput{
	public $input = "inputValid";
	
}

class inputValid1 extends sgInput{
	
	public $css = "
		.open, .close{
			padding-left:10px;	
			
		}
		.open{
			overflow:hidden;
			
			
			max-height:70px;
			transition: all 0.5s ease-in-out;
			-moz-transition: all 0.5s ease-in-out;
			-webkit-transition: all 0.5s ease-in-out;
			-o-transition: all 0.5s ease-in-out;		
			
		}
		
		.close{
			overflow:hidden;
			
			max-height:0px;
			transition: all 0.5s ease-in-out;
			-moz-transition: all 0.5s ease-in-out;
			-webkit-transition: all 0.5s ease-in-out;
			-o-transition: all 0.5s ease-in-out;		
			
		}
		
		.derecha {
		
		float:right;
		
			
		}
		.izquierda {
		
		
		float:left;	
			
		}
		
		.valid_container{
			width:100%;
			color:#000;
			background-color:#FFF;
			max-height:200px;
			overflow:auto;
			padding:4px;
			
		}
		.input_valid_rule{
			cursor:pointer;
			
		}
		.input_valid_rule:HOVER{
			
			color:#900;
			cursor:pointer;
			
		}
		
				
		";
	
	public function render(){
		
		switch ($this->type){
		default:
			break;	
		}// end switch
		
		$ele = new sgHTML("input");
		$ele->type = "hidden";
		$ele->value = $this->value;		
		$ele->name = $this->name."_ele_aux";
		
		$div = new sgHTML("div");
		$div->id = $this->id."_cont";
		
		return $this->html =  $ele->render().$div->render();

		$this->script .= "\n\t".$this->objScriptName." = new inputValid({name: '$this->name', cont: '$div->id', form: document.forms['$this->formScriptName'], ele_aux: '$ele->name'});";
		
		//$this->script .= "\n\t".$this->objScriptName.".value = \"$this->value\"";
		//$this->script .= "\n\t".$this->objScriptName.".value_ini = \"$this->value\";";





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
			
		
		return $this->html = $ele->render().$div->render();
		
		
		
		switch ($this->type){
		default:
			$ele = new sgHTML("textarea");
			$ele->innerHTML = $this->value;
			break;	
		}// end switch
		
		$ele->id = "f$this->panel"."_".$this->name;
		$ele->name = $this->name;

		
		$div = new sgHTML("div");
		$div->id = "f$this->panel"."_".$this->name."_cont";
		$div->class = "valid_container";

		$this->script .= "\n\t".$this->objScriptName." = new inputValid({name: '$this->name', cont: '$div->id', form: document.forms['$this->formScriptName']});";
		if($this->conteiner){
			$this->script .= "\n\t".$this->objScriptName.".setConteiner('$this->conteiner');";
		}// end if

		$this->script .= "\n\t".$this->objScriptName.".id = '$ele->id';";
		//$this->script .= "\n\t".$this->objScriptName.".value_ini = '$this->value';";



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
			
		
		return $this->html = $ele->render().$div->render();
		
	}// end function


	public function getScript(){

		if(!$ref = $this->getRef()){
			$ref = $this->name;	
		}
		
		$script = "";

		$p = array();
		$p["input"] = "inputValid";		
		$p["type"] = $this->type;
		$p["name"] = $this->name;
		$p["id"] = $this->id;	
		$p["cont"] = $this->id."_cont";
		$p["ele_aux"] = $this->name."_ele_aux";


		if(count($this->data) > 0){
			$p["data"] = $this->getDataJson();
		}// end if		
		$p["parent"] = $this->parent;		
		if($this->childs){
			$p["childs"] = true;
		}// end if		


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

		$json = json_encode($p);
		$script .= "\n\t".$ref.".set($json);";
		
		return $script;

		
		if(!$ref = $this->getRef()){
			$ref = $this->name;	
		}
		
		$script = "";

		
		
		$opt = "";
		foreach($this->params as $k => $v){
			$opt .= (($opt != "")?",":"")."$k: $v";
		}// next


		//$this->script .= "\n\t".$this->objScriptName." = new inputValid({name: '$this->name', cont: '$div->id', form: document.forms['$this->formScriptName'], ele_aux: '$ele->name'});";
		
		$script .= "\n\t".$ref." = new inputValid('$this->type', '$this->name');";
		$script .= "\n\t".$ref.".name = '$this->name';";


		$script .= "\n\t".$ref.".cont = '$this->id"."_cont';";
		$script .= "\n\t".$ref.".form = document.forms['$this->formName'];";
		
		
		$script .= "\n\t".$ref.".formName = '$this->formName';";
		$script .= "\n\t".$ref.".ele_aux = '".$this->name."_ele_aux"."';";


		$rules = "";
		foreach($this->rules as $k => $v){
			$rules .= (($rules!="")?",":"")."$k:$v";
		}// next
		if($rules){		
			$script .= "\n\t".$ref.".rules = { $rules };";
		}// end if
		
			
		foreach($this->events as $k => $v){
			$script .= "\n\t".$ref.".addEvent('$k',function(){ $v } );";
		}// next
		
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


		//$this->script .= "\n\t".$this->objScriptName." = new inputValid({name: '$this->name', cont: '$div->id', form: document.forms['$this->formScriptName'], ele_aux: '$ele->name'});";
		
		$script .= "\n\t".$ref." = new inputValid('$this->type', '$this->name');";
		$script .= "\n\t".$ref.".name = '$this->name';";


		$script .= "\n\t".$ref.".cont = '$this->id"."_cont';";
		$script .= "\n\t".$ref.".form = document.forms['$this->formName'];";
		
		
		$script .= "\n\t".$ref.".formName = '$this->formName';";
		$script .= "\n\t".$ref.".ele_aux = '".$this->name."_ele_aux"."';";


		$rules = "";
		foreach($this->rules as $k => $v){
			$rules .= (($rules!="")?",":"")."$k:$v";
		}// next
		if($rules){		
			$script .= "\n\t".$ref.".rules = { $rules };";
		}// end if
		
			
		foreach($this->events as $k => $v){
			$script .= "\n\t".$ref.".addEvent('$k',function(){ $v } );";
		}// next
		
		return $script;

		
	}// end function

}// end class

class svInput extends sgHTML{
	public $tagName = "";

	public function __construct($type="", $name="") {
		if($type !=""){
			$this->type = $type;
			
		}// end if
		
		if($name !=""){
			$this->name = $name;
			
		}// end if

	}// end function

	public function setRef($name){
		$this->_ref = $name;	
	}// end function
	public function getRef(){
		return $this->_ref;	
	}// end function	
	
	public function render(){
		
			
		
		
	}
	
	
}


class inputDataCheck extends sgInput{
	
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
			$chk->onclick = "$ref.getChek(this)";
			
			foreach($this->events as $kk => $vv){
				$chk->$kk .= $vv;
				
			}			
			
			$chk->name = $this->name."_chk_aux";
			$chk->value = $v["value"];
			
			
			if(in_array($v["value"], $values)){
				$chk->checked = true;
				
			}
			
			$r->cells[0]->appendChild($chk);
			$r->cells[0]->appendChild($v["text"]);
		}
		
		
		
		
		return $cont->render();
	}// end function
	
	public function getScript(){
		if(!$ref = $this->getRef()){
			$ref = $this->name;	
		}


		$script = "";
	
		$opt["panel"] = "$this->panel";
		$opt["type"] = "check";
		$opt["name"] = "$this->name";
		$opt["formName"] = "sgpanel_$this->panel";
		$opt["value"] = $this->value;
		$opt["index"] = $this->index;
		$opt["ref"] = "$ref";
		$opt["records"] = $this->records;
		
		$opt["tableId"] = $this->name."_t_".$this->panel;
		


		$json = json_encode($opt);
	
		$script .= "\n\t".$ref." = new sgCheckGrid($json);";
		return $script;

		if(count($this->data) > 0){
			$script .= $this->getDataScript("_DD");
			$script .= "\n\t".$ref.".data = _DD;";
		}// end if

		if(isset($this->records)){
			$json = json_encode($this->records);
			$script .= "\n\t".$ref.".records = $json;";
			
		}

		if(isset($this->fields)){
			$json = json_encode($this->fields);
			$script .= "\n\t".$ref.".fields = $json;";
			
		}


		foreach($this->events as $k => $v){
			$script .= "\n\t".$ref.".events['$k'] = function(){ $v }";
			
		}



		

		return $script;
		
	}	
	
}// end class

class inputSelectText extends sgInput{
	public $input = "selectText";
	
}

class inputSelectText1 extends sgInput{
	
	public function render(){
		
		
		
		switch ($this->type){
		default:
			$ele = new sgHTML("input");
			$ele_text = new sgHTML("input");
			
			break;	
		}// end switch
		
		$ele->id = "f$this->panel"."_".$this->name;
		$ele->name = $this->name;

		$ele_text->id = "f$this->panel"."_".$this->name."_txt";
		$ele_text->name = $this->name."_txt";
		

		$this->script .= "\n\t".$this->objScriptName." = new selectText({name: '$this->name', inputValue:'$ele->id', inputText:'$ele_text->id'});";
		if($this->conteiner){
			$this->script .= "\n\t".$this->objScriptName.".setConteiner('$this->conteiner');";
		}// end if
		switch($this->type){
		default:
			$ele->type = "hidden";
			$ele_text->type = "text";
			$ele->value = $this->value;
			$ele_text->value = $this->getText($this->value);
			break;
		}// end switch
		$this->script .= "\n\t".$this->objScriptName.".id = '$ele->id';";
		$this->script .= "\n\t".$this->objScriptName.".value_ini = '$this->value';";
		$this->script .= "\n\t".$this->objScriptName.".value = '$this->value';";

		if(count($this->data) > 0){
			$this->script .= $this->getDataScript("_DD");
			$this->script .= "\n\t".$this->objScriptName.".data = _DD;";
		}// end if


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
			
		
		return $this->html = $ele->render().$ele_text->render();
		
	}// end function
	public function getSelectOptions(){
		
		
		
		$opt = new sgHTML("option");

		$sel = explode(",", strtoupper($this->value));
		
		$str = "";
		
		foreach ($this->data as $k => $v){
			$opt->value = $v["value"];
			$opt->text = $v["text"];
			if(in_array(trim(strtoupper($opt->value)), $sel)){
				$opt->selected = true;
			}else{
				$opt->selected = false;
			}// end if
			$str .= "\n\t\t".$opt->render();
		}//next
		return $str;

	}// end function	
	public function getText($value){
		foreach ($this->data as $k => $v){
			if($v["value"]==$value){
				return $v["text"];
			}// end if
		}//next
		return "";
	}// end function	


}// end class
?>