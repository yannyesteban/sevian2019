<?php
class query extends sg_panel{
	
	public $render = 1;
	
	public $title = "QUERY V1.0";
	
	public $table = "";
	public $_from = "";
	public $_join = "";
	public $_fields = "";
	
	
	public $objPanelName = "dQuery";

	
	public $css = "
		.__query_tables{
			
			border:1px red solid;
			
			margin-right:2px;
			padding:3px;
			display:inline-block;
			width:auto;
			min-width:220px;
			height:100px;
			overflow:auto;
			vertical-align:top;
			
		}	
		
		.__qjoins{
			border:2px solid green;
			padding:4px;
			margin:4px;	
			display:inline-block;
			min-width:300px;
			
		}
		
		.xd{
			border:1px solid blue;	
			
		}	
	/*
	
		.__query_item_active{
			background-color: blue;	
			
		}
		.__query_item_normal{
			background-color: blue;	
			
		}
		
		.xd{
			border: 1px solid blue;
			padding:3px;	
			
		}
		
		div.qdiv1{
			
			
			border: 0px blue solid;
			margin-bottom: 4px;
			
			
		}
		.div1{
			box-sizing:border-box;
			border:1px red solid;
			
			margin-right:2px;
			padding:3px;
			display:inline-block;
			width:auto;
			min-width:200px;
			height:200px;
			overflow:auto;
			vertical-align:top;
			
		}
		.div1 div{
			height:20px;
		}

		.div1 div:HOVER{
			background-color:blue;
			color:white;
			cursor:pointer;
			height:20px;
			line-height:20px;
			vertical-align:midle;
		}
		.div11{
			
			box-sizing:border-box;
			padding:4px;
			border:1px green solid;

			/*display:inline-block;*/
			width:auto;
			min-width:220px;
			height:200px;
			overflow:none;
			vertical-align:top;
			position:relative;
			
		}


		.items{
			postion:relative;
			width:100%;
				
		}
		.alias{
			postion:abslute;
			right:0px;	
		}
		
		.dx{
			position:relative;
			height:100%;
				
		}


		.dix{

			position:absolute;
			postion:relative;
			box-sizing:border-box;
			padding:4px;
			border:1px solid gray;			
			
			overflow:auto;
			
			margin-bottom:30px;
			top:0px;
			bottom:0px;
			min-width:210px;
			
		}
		

		.dmx{
			position:absolute;
			box-sizing:border-box;
			bottom:0px;						
			height:30px;
			line-height:30px;
			text-align:right;
			vartical-align:midle;
		}*/
	
	";
	//===========================================================
	public function __construct() {
		$this->cnn = conection($this->icnn);
		if($this->icns!=""){
			$this->cns = conection($this->icns);
		}else{
			$this->cns = &$this->cnn;
		}// end if
		

	


		$this->containerClass = "__query_container";
		$this->tablesClass = "__query_tables";
		$this->fieldsClass = "__query_tables";		
		$this->joinsClass = "__query_joins";		
		$this->resultClass = "__query_result";		
		


		
	}// end function
	
	public function evalMethod($method){
		
		global $seq;

		$this->containerId = "__query_container_".$this->panel;
		$this->tablesId = "__query_tables_".$this->panel;
		$this->fieldsId = "__query_fields_".$this->panel;		
		$this->fromId = "__query_from_".$this->panel;		
		$this->joinsId = "__query_joins_".$this->panel;		
		$this->whereId = "__query_where_".$this->panel;		
		$this->orderId = "__query_order_".$this->panel;		
		$this->resultId = "__query_result_".$this->panel;
		$this->rId = "__query_r_".$this->panel;	
		
		//$this->sJoinId = "__query_sjoin_".$this->panel;	
		//$this->sJoinFieldAId = "__query_sfielda_".$this->panel;	
		//$this->sJoinFieldBId = "__query_sfieldb_".$this->panel;	


		if(isset($seq->cmd->v->req["table"]) and$seq->cmd->v->req["table"]!=""){
			$this->table = $seq->cmd->v->req["table"];
		}elseif(isset($this->vparams["table"]) and $this->vparams["table"]!=""){
			$this->table = $this->vparams["table"];
		}//end if

		if(isset($seq->cmd->v->req["_from"])){
			$this->_from = $seq->cmd->v->req["_from"];
			
			$this->_tables = $seq->cmd->v->req["_from"];
		}
		/*if(isset($seq->cmd->v->req["_join"])){
			$this->_join = $seq->cmd->v->req["_join"];
		}
		if(isset($seq->cmd->v->req["_fields"])){
			$this->_fields = $seq->cmd->v->req["_fields"];
		}*/

		
		switch($method){
		case "query":
			$this->init();
			//$this->querySQL();
			break;
		case "design":
			$this->queryDesign();
			break;
		case "search_fields":
			$this->setSelectedTable($this->_tables);
		
			$this->setFragment($this->searchFields(), $this->fieldsId);//$this->searchFields();
			$this->setFragment($this->divFrom(), $this->fromId);//$this->searchFields();
			$this->setFragment($this->divJoin(), $this->joinsId);//$this->searchFields();
			$this->setFragment($this->divWhere(), $this->whereId);//$this->searchFields();
			$this->setFragment($this->divOrder(), $this->orderId);//$this->searchFields();
			$this->render=0;
			break;
		case "set_query":
			$this->setFragment($this->setQuery(), $this->div4_id);//$this->searchFields();

			
		
			$this->render=0;
			break;
		case "execute":
			$this->setFragment($this->executeQuery($seq->cmd->v->req["input_query"]), $this->rId);
			
			$this->render=0;
			break;
		}// end switch

		
		if($this->render != 0){
			
			$this->html = $this->getDinamicPanel($this->html);
		}// end if		
		
	}// end public
	
	public function init(){
		$ref = $this->getRef();
		
		
		
		
		$tab = new sgTab($this->name."_tab_".$this->panel);
		
		
		
		$tab->objScriptName = $ref.".tab";
		
		$page1 = $tab->add("Tables");
		$page2 = $tab->add("1. Select");
		$page3 = $tab->add("2. From");
		$page4 = $tab->add("3. Where");
		$page5 = $tab->add("4. Result");
		
		$r = new sgHTML("div");
		$r->style = "max-width:650px;max-height:300px;overflow:auto;";
		$r->id = $this->rId;
		$page5->appendChild($r);
	


		$divContainer = new sgHTML("div");
		$divContainer->id = $this->containerId;
		$divContainer->class = "xd";

		$divTables = new sgHTML("div");
		$divTables->id = $this->tablesId;
		//$divTables->class = "qdiv1";
		$divTables->innerHTML = $this->listTables();
		//$divContainer->appendChild($divTables);
		$page1->appendChild($divTables);

		$divOptions = new sgHTML("div");
		$divOptions->id = "";
		$divOptions->class = "";		
		
		$page1->appendChild($divOptions);
		
		$divFields = new sgHTML("div");
		$divFields->id = $this->fieldsId;


		$a = new stdClass;
		$a->panel = $this->panel;
		$a->w_panel = "";
		$a->eparams = "panel:$this->panel;element:query;method:search_fields;";
		$a->action = "";
		$a->valid = "";
		$a->confirm = "";
		
		$btn1 = new sgHTML("input");
		$btn1->value = "Sig";
		$btn1->type = "button";
		
		$btn1->onclick = "$ref.getTables();";
		//$btn1->onclick .= cfg_action::setEvent2("set_panel_x", $a);	

		$btn1->onclick .= svAction::setPanel(
				array(
					"async"=>true,
					"panel"=>$this->panel,
					"params"=>"panel:$this->panel;element:query;method:search_fields;"
				));

		$divOptions->appendChild($btn1);
		
		
		//$divContainer->appendChild($divFields);
		$page2->appendChild($divFields);



		
		$divFrom = new sgHTML("div");
		$divFrom->id = $this->fromId;
		$divFrom->style = "";
		//$divContainer->appendChild($divFrom);
		$page3->appendChild($divFrom);

		$divJoins = new sgHTML("div");
		$divJoins->id = $this->joinsId;
		$divJoins->style = "";
		//$divContainer->appendChild($divJoins);
		$page3->appendChild($divJoins);

		$divWhere = new sgHTML("div");
		$divWhere->id = $this->whereId;
		$divWhere->style = "";
		//$divContainer->appendChild($divWhere);
		$page4->appendChild($divWhere);


		$divOrder = new sgHTML("div");
		$divOrder->id = $this->orderId;
		$divOrder->style = "";
		//$divContainer->appendChild($divOrder);
		$page4->appendChild($divOrder);


		$divQuery = new sgHTML("div");
		$divQuery->id = $this->resultId;
		
		$inputQuery = new sgHTML("textarea");
		$inputQuery->name = "input_query";
		$divQuery->appendChild($inputQuery);
		
		
		$divContainer->appendChild($divQuery);
		

		$this->script .= "\n$ref = new dQuery($this->panel);";
		



		$btnQ = new sgHTML("input");
		$btnQ->value = "Query";
		$btnQ->type = "button";
		$btnQ->onclick = "$ref.queryResult();";



		$this->script .= "\n$ref.init({input_query: 'input_query'})";
		$this->script .= "\n$ref.newListTables({name: 'tables', container: '$this->tablesId'})";


		$a = new stdClass;
		$a->panel = $this->panel;
		$a->w_panel = "";
		$a->eparams = "panel:$this->panel;element:query;method:execute;";
		$a->action = "";
		$a->valid = "";
		$a->confirm = "";

		$btn2 = new sgHTML("input");
		$btn2->value = "Run";
		$btn2->type = "button";
		
		
		//$btn2->onclick = cfg_action::setEvent2("set_panel_x", $a);	

		$btn2->onclick = svAction::setPanel(
				array(
					"async"=>true,
					"panel"=>$this->panel,
					"params"=>"panel:$this->panel;element:query;method:execute;"
				));
		


		$this->html = $divContainer->render().$btnQ->render().$btn2->render().$tab->render().$this->eleAux();
		
		$this->script .= $tab->getScript();
	}// end public
	
	public function querySQL(){
		$this->html = $this->getListTables($this->table);
		
		
	}// end function
	public function queryDesign(){
		
		
		
	}// end function
	
	
	public function setSelectedTable($tables){
		$cn = &$this->cns;
		$aux = explode(";", $tables);

		$this->tables = array();
		$this->fields = array();
		foreach($aux as $k => $v){
			$aux2 = explode(":", $v);
			
			
			$this->tables[$k]["table"] = $aux2[0];
			$this->tables[$k]["alias"] = $aux2[1];
			
			$fields = $cn->getFields($aux2[0]);
			
			foreach($fields as $k2 => $field){
				$this->fields[$aux2[0]][] = $field;	
				
			}
			
			
		}		
		
	}// end function

	public function listTables(){
		global $seq;
		$ref = $this->getRef();
		$cn = &$this->cns;
		
		$tables = $cn->getTables();		

		
		$div1 = new sgHTML("div");
		$div1->class = $this->tablesClass;
		

		$str = "";
		foreach($tables as $table){
			
			$div = new sgHTML("div");
			$div->innerHTML = $table;
			
			$div->onclick = "$ref.selectTable('$table')";
			$str .= $div->render();	
			
		}

		$div1->innerHTML = $str;
		
		
		$div2 = new sgHTML("div");
		$div2->id = "a1";
		$div2->class = "__query_table_container";
		return $div1->render();//.$div2->render()
		
	}// end function
	
	public function divFrom(){
		
		if($this->tables[0]["alias"]){
			return "FROM ".$this->tables[0]["table"]." as ".$this->tables[0]["alias"];	
			
		}else{
		
			return "FROM ".$this->tables[0]["table"];
		}
		
	}// end function

	
	public function searchFields(){

		global $seq;
		$cn = &$this->cns;
		
		$div1 = new sgHTML("div");
		$div1->class = $this->fieldsClass;
		$str = "";
		
		
		
		$ref = $this->getRef();		
		foreach($this->tables as $k => $v) {
			$table = $v["table"];
			$alias = $v["alias"];

			//$fields = $cn->getFields($table);	

			$div = new sgHTML("div");
			$div->innerHTML = "<b>".$table.(($alias!="")?" as $alias":"")."</b>";
			 $aux = implode(",",$this->fields[$table]);
			
			$div->onclick = "$ref.selectFieldsTable('$table', '$alias', '$aux');";
			$str .= $div->render();	
		
			foreach(array_merge(array($table=>"*"), $this->fields[$table]) as $field){
				$div = new sgHTML("div");
				$div->innerHTML = "- ".$field;
				$div->onclick = "$ref.selectField('$table', '$field', '$alias')";
				$str .= $div->render();					
			}// next

			
			
		}// next
		$div1->innerHTML = $str;
		
		

		
		$this->script .= "\n$ref.newListFields({name: 'fields', container: '$this->fieldsId'});";
		return $div1->render();
		
		
	}// end function

	public function setQuery(){
		global $seq;
			
		$ref = $this->getRef();
		
		if($this->_fields){
			$sql = "SELECT ".implode(", ", explode(",",$this->_fields));
			$sql .= "<br>FROM $this->_from";
			
			if($this->_join){
				$tables = explode(",", $this->_join);
				foreach($tables as $table){
					$sql .= "<br>INNER JOIN $table ON ";
					
				}
				
			}			
			return $sql;
			
			
		}// end if
		return "error";
	}// end function





	
	
	public function divJoin(){
		
		$str = "";
		foreach($this->tables as $k => $t){
			if($k==0){
				continue;	
			}// end if
			
			$str .= $this->fieldsJoin($t, $k);
			
		}


		
		return $str;		
		
		
		global $seq;
		$cn = &$this->cns;

		
		
		$ref = $this->getRef();		
		$aux = explode(";", $this->_from);
		$tables = array();

		foreach($aux as $k => $v){
			$aux2 = explode(":", $v);
			$tables[]=$aux2[0];
		}

		$str = "";
		foreach($tables as $k => $tjoin){
			$str .= $this->fieldsJoin($tjoin, $tables, $k);
			
		}


		
		return $str;
		
	}// end function

	public function fieldsJoin($t, $index){
		
		$tjoin = $t["table"];
		$talias = $t["alias"];

		global $seq;
		$cn = &$this->cns;
		$ref = $this->getRef();

		$str = "";
		
		$tableRef = (($talias!="")?$talias:$tjoin);
		
		$joinRef = (($talias!="")?"$tjoin as $talias":$tjoin);
		
		$sJoin = new sgHTML("select");
		$sJoin->name = "sjoin_".$index;
		$sJoin->innerHTML = "
				<option value='INNER JOIN $joinRef'>INNER JOIN</option>
				<option value='LEFT JOIN $joinRef'>LEFT JOIN</option>
				<option value='RIGHT JOIN $joinRef'>RIGHT JOIN</option>";
		
		$div = new sgHTML("span");
		$div->innerHTML = "$tjoin";
		
		$list = new sgHTML("div");
		$list->id = "__query_ljoin_$index"."_".$this->panel;
		
		$this->script .= "\n$ref.newListJoins({name: '$index', container: '$list->id', class:'__qjoins'});";
		
		
		

		//$fields = $cn->getFields($tjoin);
		
		$select = new sgHTML("select");
		$select->name = "sfield_a_$index";
		$optgroup = new sgHTML("optgroup");
		$optgroup->label = $joinRef;//$tjoin.(($talias!="")?"($talias)":"");		
		
		foreach($this->fields[$tjoin] as $field){
			
			$opt = new sgHTML("option");
			$opt->value = (($talias!="")?$talias:$tjoin).".".$field;

			$opt->text = $field;
			$optgroup->innerHTML .= $opt->render();
			
		}// next
		
		$select->innerHTML .= $optgroup->render();
		
		$select2 = new sgHTML("select");		
		$select2->name = "sfield_b_$index";
		foreach($this->tables as $k => $t){
			

			
			if($k>=$index ){
				
				continue;	
			}
			$table = $t["table"];
			$alias = $t["alias"];
			
			$optgroup = new sgHTML("optgroup");
			if($alias != ""){
				$optgroup->label = "$table as $alias";
			}else{
				$optgroup->label = $table;
			}// end if
		
			foreach($this->fields[$table] as $field){
				
				$opt = new sgHTML("option");
				$opt->value = (($alias!="")?$alias:$table).".".$field;
				$opt->text = $field;
				$optgroup->innerHTML .= $opt->render();
				
			}// next
			$select2->innerHTML .= $optgroup->render();
						
		}// next
		
		
		$btnAdd = new sgHTML("input");
		$btnAdd->type = "button";
		$btnAdd->value = "+";
		
		$btnAdd->onclick = "$ref.selectJoin('$index', '$select->name', '$select2->name');";
		

		
		$str .= $sJoin->render().$div->render();
		return $str.$select->render()." = ".$select2->render().$btnAdd->render().$list->render();
	}// end function


	public function divWhere(){
		global $seq;
		$cn = &$this->cns;
		$ref = $this->getRef();




		$select2 = new sgHTML("select");		
		$select2->name = "__select_w";
		foreach($this->tables as $k=> $t){
			
			
			$table = $t["table"];
			$alias = $t["alias"];

			
			$optgroup = new sgHTML("optgroup");
			if($alias != ""){
				$optgroup->label = "$table as $alias";
			}else{
				$optgroup->label = $table;
			}// end if
			$fields = $cn->getFields($table);
		
			foreach($this->fields[$table] as $field){
				
				$opt = new sgHTML("option");
				$opt->value = (($alias!="")?$alias:$table).".".$field;
				$opt->text = $field;
				$optgroup->innerHTML .= $opt->render();
				
			}
			$select2->innerHTML .= $optgroup->render();
						
		}


		$op = new sgHTML("select");
		$op->id = "__op";
		$op->innerHTML = "
				<option value='AND'>AND</option>
				<option value='OR'>OR</option>
				<option value='XOR'>XOR</option>
				";



		$cond = new sgHTML("select");
		$cond->id = "__cond";
		$cond->innerHTML = "
				<option value='='> = </option>
				<option value='!='> != </option>
				<option value='>'> > </option>
				<option value='>='> >= </option>
				<option value='<'> < </option>
				<option value='<='> <= </option>
				<option value='IS NULL'> IS NULL </option>
				<option value='IS NOT NULL'> IS NOT NULL </option>
				<option value='IN'> IN </option>
				<option value='NOT IN'> NOT IN </option>
				<option value='LIKE'> LIKE </option>
				<option value='NOT LIKE'> NOT LIKE </option>
				";

		
		$list = new sgHTML("div");
		$list->id = "__listwhere";
		
		$value = new sgHTML("input");
		$value->id = "__q_value";
		$value->type = "text";


		$btnAdd = new sgHTML("input");
		$btnAdd->type = "button";
		$btnAdd->value = "+";
		
		$btnAdd->onclick = "$ref.selectWhere('where', '__op', '__select_w', '__cond', '__q_value');";

		
		$this->script .= "\n$ref.newListWhere({name: 'where', container: '$list->id', class:'__qjoins'});";



		return "WHERE ".$op->render().$select2->render().$cond->render().$value->render().$btnAdd->render().$list->render();		
		
		
	}


	public function divOrder(){
		global $seq;
		$cn = &$this->cns;
		$ref = $this->getRef();




		$select2 = new sgHTML("select");		
		$select2->name = "__select_w1";
		foreach($this->tables as $k=> $t){
			
			
			$table = $t["table"];
			$alias = $t["alias"];

			
			$optgroup = new sgHTML("optgroup");
			if($alias != ""){
				$optgroup->label = "$table as $alias";
			}else{
				$optgroup->label = $table;
			}// end if
			$fields = $cn->getFields($table);
		
			foreach($this->fields[$table] as $field){
				
				$opt = new sgHTML("option");
				$opt->value = (($alias!="")?$alias:$table).".".$field;
				$opt->text = $field;
				$optgroup->innerHTML .= $opt->render();
				
			}
			$select2->innerHTML .= $optgroup->render();
						
		}


		$op = new sgHTML("select");
		$op->id = "__ordertype";
		$op->innerHTML = "
				<option value=''></option>
				<option value='ASC'>ASC</option>
				<option value='DESC'>DESC</option>
				";



		

		
		$list = new sgHTML("div");
		$list->id = "__listorder";
		
		


		$btnAdd = new sgHTML("input");
		$btnAdd->type = "button";
		$btnAdd->value = "+";
		
		$btnAdd->onclick = "$ref.selectOrderBy('orderby', '__select_w1', '__ordertype');";

		
		$this->script .= "\n$ref.newListOrderBy({name: 'orderby', container: '$list->id', class:'__qjoins'});";



		return "ORDER BY ".$select2->render().$op->render().$btnAdd->render().$list->render();		
		
		
	}// end function
	
	public function executeQuery($query, $page=1){
		
		
		global $seq;
		$cn = &$this->cns;
		$cn->query = $seq->cmd->eval_var($query);
		
		
		$result = $cn->execute($cn->query);
		$info = $cn->infoQuery($cn->query);
		
		
		
		$t = new sgTable($info->fieldCount);
	
		$t->border="1";		
		$t->insertRow();
		
		$i = 0;
		foreach($info->fields as $k => $v){
			
			$t->cells[0][$i]->innerHTML = $k;
			$i++;
		}// next

		$f = 1;
		while($rs = $cn->getDataAssoc($result)){
			
			$i = 0;
			$t->insertRow();
			foreach($rs as $k => $v){
				$t->cells[$f][$i]->text = $v;
				$i++;
			}// next
				
			$f++;
		}
		
		return $t->render();		
		
	}// end function

	
	public function eleAux(){ 
		
		$div = new sgHTML("div");
		$hidden = new sgHTML("input");
		$hidden->type = "hidden";

		$hidden->name = "_from";
		$hidden->value = $this->_from;
		$div->innerHTML .= "\n\t".$hidden->render();
		


		return $div->render();

	}// end function
	
	
}// end class
?>