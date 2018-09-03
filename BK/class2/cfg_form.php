<?php

class FormElem{

	public $panel = 0;
	
	public $table = "";
	
	public $type = "text";
	public $id = "";
	public $name = "";
	public $caption = "";
	public $params = "";

	public $input = "";
	public $default_input = "";

	public $control = "";
	public $listen = "";
	
	public $property  = "";
	public $data = array();
	public $data_values = "";
	public $dinamic_data = false;
	public $cfg_input = array();
	public $event = array();
	public $rules = array();
	public $value = false;
	public $value_default = false;
	public $parent = "";
	public $childs = "";

	public $params_cal="";

	public $html = "";
	public $script = "";
	
	public $class = "";
	public $class_invalid = "";
	public $style = "";
	public $propertys = "";
	public $events = "";
	public $valid = "";
	public $help = "";

	public $button_add = "";

	public $readonly = false;
	public $disabled = false;
	public $hidden = false;
	public $hide = false;
	
		
	public $container = "";
	public $autocomplete = true;
	
	public $mtype = false;
	public $value_ini_type = false;
	
	public $position = false;
	public $length = false;
	public $title = false;
	
	public $subform = false;
	
	
	public $p = "p";
	public function control(){
		
		
	}// end function
		
	public function get_data(){
	}// end function
	
	public function set_data(){
	}// end function

	
}// end class

class cfg_form extends sg_panel{
	
	public $tableFields = false;
	public $fields = false;
	
	
	public $element = "form";
	
	public $icnn = SS_CNN;
	public $icns = SS_CNS;
	public $icnd = SS_CND;

	public $keys = array();
	
	public $form = "";
	public $mode = false;
	public $method = "";
	public $mode_record = 1;
	
	public $pagination = true;
	public $page = 1;
	public $record = "";
	public $record_page=10;
	

	public $recordCount = 0;
    public $fieldCount = 0;
	public $pageCount = 0;
	
	
	
	public $objPanelName = "sgForm";

	public $query="";
	public $query_data = "";
	
	public $q_data = "";
	
	public $data = array();
	
	
		
	public $type = "normal";//design,
	public $layer="";
	public $filters="";
	public $fields_search="";
	public $q_search_exact = "";
	public $filters_sql = "";
	
	public $wTabs = false;
	
	public $use_ref = false;
	public $target_tables = "";
	
	protected $t_forms = "";
	protected $t_ele_met = "";
	protected $t_grp_usr = "";
	protected $t_grp_ele = "";
	protected $t_usr_ele = "";
	
	protected $elem_type = "form";
	public $elem_params = "";
	public $elem_eparams = "";
	//public $field = array();
	
	public $title="";
	public $class_default="";
	public $class="";
	
	
	
	
	
	public $params = ""; 
	
	public $tabs = ""; 
	public $groups = "";
	public $expressions = "";
	public $signs  = "";
	public $eval_signs = "";
	
	
	
	public $script="";
	public $text="";
	public $css="";
	public $debug="";
	public $message="";
	public $panel_width="";
	public $ppanel = array();
	public $iTabs = array();
	public $igroups = array();
	public $pgroups = array();
	public $user="";
	public $isigns = array();
	public $functions = false;
	
	
	public $field_detail = "";
	public $field_order = "";
	public $field_order_step = 10;
	
	
	
	public $fieldValue = false;
	protected $_targetTable = array();
	
	protected $_subForm = array();
	//===========================================================
	public function __construct() {
		
		$this->cnn = conection($this->icnn);
		$this->cnd = conection($this->icnd);
		if($this->icns!=""){
			$this->cns = conection($this->icns);
		}else{
			$this->cns = &$this->cnn;
		}// end if
		
		$this->t_forms = TABLE_PREFIX."forms";
		$this->t_form_fields = TABLE_PREFIX."form_fields";		
		$this->t_ele_met = TABLE_PREFIX."ele_met";
		$this->t_grp_usr = TABLE_PREFIX."grp_usr";
		$this->t_grp_ele = TABLE_PREFIX."grp_ele";
		$this->t_usr_ele = TABLE_PREFIX."usr_ele";		
		
	}// end function
		
	public function execute($name="", $method=""){
		global $seq;


		unset($this->eparams["design_mode"]);

		if(isset($this->eparams["design_mode"])){
			
			$cn = &$this->cnd;
			$this->cnn = conection($this->icns);
			$this->cns = $this->cnd;
		}else{
			$cn = &$this->cns;
		}

		if($name!==""){
			$this->form = $name;
		}// end if

if($this->form==""){
	return;	
}		
		
		$cn->query = "
			SELECT DISTINCT
				f.form, f.title, f.query, f.class, f.template, f.template_panel, f.tabs, f.groups, f.menu,
				@CONCAT(@IFNULL(f.params,''), @IFNULL(em.params,'')) as params,
				@CONCAT(@IFNULL(f.expressions,''), @IFNULL(em.expressions,'')) as expressions,
				
				@CONCAT(@IFNULL(f.signs,''), @IFNULL(em.signs,'')) as signs,
				@CONCAT(@IFNULL(f.eval_signs,''), @IFNULL(em.eval_signs,'')) as eval_signs,

				@CONCAT(@IFNULL(f.functions,''), @IFNULL(em.functions,'')) as functions,
				@CONCAT(@IFNULL(f.events_signs,''), @IFNULL(em.events_signs,'')) as events_signs,


				f.style, f.propertys, f.title_style, f.title_propertys,
				
				
				f.elem_style,f.elem_propertys, f.elem_title_style, f.elem_title_propertys

			FROM $this->t_forms as f 

			LEFT JOIN $this->t_ele_met as em 
				ON em.element = '$this->elem_type' AND em.name = f.form AND em.method = '$method'  
			LEFT JOIN $this->t_grp_usr as gu 
				ON gu.user = '$this->user'
			LEFT JOIN $this->t_grp_ele as g 
				ON g.element = '$this->elem_type' AND g.name = f.form AND g.group = gu.group 
			LEFT JOIN $this->t_usr_ele as u 
				ON u.element = '$this->elem_type' AND u.name = f.form AND u.user = gu.user
			WHERE f.form = '$this->form'
				AND (g.allow=1 OR g.name IS NULL)
				AND (u.allow=1 OR u.name IS NULL)";
		
		
		$result = $cn->execute($cn->query, true);
		
		$log = false;
		if($rs = $cn->getDataAssoc($result)){
			
			foreach($rs as $k => $v){
				$this->$k = $v;
			}// next
			
			
			if($prop = $seq->cmd->get_param($this->elem_params, $log["elem_params"])){
				//$this->vpara = array_merge($this->vpara,$prop);
				foreach($prop as $k => $v){
					$this->$k = $v;
				}// next
			}// end if
			
			if($prop = $seq->cmd->get_param($this->params, $log["params"])){
				//$this->vpara = array_merge($this->vpara,$prop);
				foreach($prop as $k => $v){
					$this->$k = $v;
				}// next
			}// end if
			
			$proc_before="proc_".$this->method."_before";

			if(isset($this->$proc_before)){
				$seq->procedure($this->$proc_before);
			}// end if
			
			$seq_before="seq_".$this->method."_before";
			if(isset($this->$seq_before)){
				$seq->procedure($this->$seq_before);
			}// end if
			
		
		
			if($prop = $seq->cmd->get_param($this->tabs, $log["tabs"])){
				//$this->vpara = array_merge($this->vpara,$prop);
				foreach($prop as $k => $v){
					$this->iTabs[$k] = $v;
				}// next
				$this->wTabs = true;
			}// end if

		

			if($prop = $seq->cmd->get_param($this->groups, $log["groups"])){
				//$this->vpara = array_merge($this->vpara,$prop);
				foreach($prop as $k => $v){
					$this->igroups[$k] = $v;
				}// next
			}// end if

			if($prop = $seq->cmd->get_param($this->signs, $log["signs"])){
				//$this->vpara = array_merge($this->vpara,$prop);
				foreach($prop as $k => $v){
					
					$this->isigns[$k] = $v;
					
				}// next
				
			}// end if



			if($prop = $seq->cmd->get_param($this->eval_signs, $log["eval_signs"])){
				//$this->vpara = array_merge($this->vpara,$prop);
				foreach($prop as $k => $v){
					
					$this->esigns[$k] = $v;
				}// next
				
				
			}// end if
			if($prop = $seq->cmd->get_param($this->functions, $log["functions"])){
				//$this->vpara = array_merge($this->vpara,$prop);
				foreach($prop as $k => $v){
					
					$this->f[$k] = $v;
				}// next
				
				
			}// end if

			
			$this->value_ref = $this->get_idrecord($this->use_ref);
			if($this->target_tables){
				
				
				$this->_targetTable = array_flip(explode(",", $this->target_tables));
				
				
			}
			
			
			
		}else{

			//$this->logE("FORM", "", "", "NO FOUND");
			$this->query = "SELECT * FROM $this->form";
			
		}// end if	
		
		
		$this->cfgFields($seq->cmd->eval_var($this->query), $method);
		
	}// end function

	public function cfgFields($query, $method){
		global $seq;

		$log = array();

		$cn = &$this->cnn;
		
		
		$log["query_ini"] = $this->query;
		$log["query_end"] = &$this->query_data;
		
		$this->keys = array();

		$this->data["__form_mode"] = 1;
		$this->data["__form_record"] = "";

		//$this->info_fields = $cn->get_info_query($query);
		
		$this->info_fields = $cn->infoQuery($query, true);
		//hr($this->info_fields );
		if($cn->error){
			$this->fields[0] = new FormElem;	
			$this->fields[0]->name = "error";
			$this->fields[0]->title = "[: ERROR :]";
			$this->fields[0]->input = $this->defaultInput("C");

			$log["query_error"] = $cn->error;

			return;
			
				
		}
		
		$this->log($log);
		
		$this->tables = $this->info_fields->tables;
		$this->tKeys = $this->info_fields->keys;
		
		$this->fields_count = $this->info_fields->fieldCount;
		
		

		$fields = $this->info_fields->fields;
		
		
		foreach($fields as $i => $v){	
		
			$t = $fields[$i]->table;
			$f = $fields[$i]->name;
			
			$this->tableFields[$t][$f] = $f;
						
			$this->fields[$i] = new FormElem;
			$this->fields[$i]->table = $t;
			
			$this->fields[$i]->field = $fields[$i]->field;
			$this->fields[$i]->name = $fields[$i]->name;//($fields[$i]->alias)? $fields[$i]->alias: $f;
			//$this->fields[$i]->alias = $fields[$i]->alias;
			$this->fields[$i]->title = $fields[$i]->title;
			$this->fields[$i]->type = $fields[$i]->type;
			$this->fields[$i]->config = "";

			$this->fields[$i]->mtype = $fields[$i]->mtype;
			$this->fields[$i]->length = $fields[$i]->length;
			$this->fields[$i]->decimals = $fields[$i]->decimals;
			$this->fields[$i]->default = $fields[$i]->default;
			$this->fields[$i]->not_null = $fields[$i]->not_null;
			$this->fields[$i]->key = $fields[$i]->key;
			$this->fields[$i]->serial = $fields[$i]->serial;
			$this->fields[$i]->unsigned = $fields[$i]->unsigned;
			$this->fields[$i]->serial = $fields[$i]->serial;
			$this->fields[$i]->position = $fields[$i]->position;
			
			$this->fields[$i]->value_ini_type = 0;
			$this->fields[$i]->value = $this->fields[$i]->default;
			if($this->fields[$i]->key){
				$this->keys[$this->fields[$i]->name]=$i;
			}// end if
		

			if(isset($this->igroups[$this->fields[$i]->name])){
				
				$this->pgroups[]=$i;	
				
			}// end if
			
			//$this->fields[$t][$f] = &$this->fields[$i];
			
			
			$this->data[$f] = $this->fields[$i]->default;
			$this->fields[$i]->input = $this->defaultInput($fields[$i]->mtype);
			
			
		}// next
		
		$__form_mode = $this->fields["__form_mode"] = new FormElem;	
		$__form_mode->name = "__form_mode";
		$__form_mode->title = "__form_mode";
		$__form_mode->input = "hidden";
		$__form_mode->hide = true;
			
		$__form_record = $this->fields["__form_record"] = new FormElem;	
		$__form_record->name = "__form_record";
		$__form_record->title = "__form_record";
		$__form_record->input = "hidden";
		$__form_record->hide = true;
		

		$cn = &$this->cns;
		
		if(isset($this->cfg_from)){
			
			$cfg_from = $this->cfg_from;
			
		}else{
			
			$cfg_from = "";	
		}

		//$cfg_from = "aa,bb,cc,dd";
		//$cfg_from = "datos_paciente";
		
		
		$aux = explode(",", $cfg_from);
		$aux=array_merge($aux, array_values($this->tables));
		
		$list_form = "'".implode("','",$aux)."'";
		
		
		array_unshift($aux, $this->form);

		$str = "";
		foreach($aux as $k => $v){
			$str .= "WHEN '$v' THEN $k ";
		}
		$order_by = "CASE form ".$str." END";

					
		$cn->query = "
				SELECT a.* 
				FROM $this->t_form_fields as a 
				WHERE
					(form='$this->form' AND (method='' OR method='$method'))
					
					OR (form in ($list_form))
				 	ORDER BY $order_by DESC, method
					
					";					
			
				
		$result = $cn->execute($cn->query);
		
		while($rs = $cn->getDataAssoc($result)){
			
			
			//$t = $rs["table"];
			$f = $rs["name"];
			$params = $rs["params"];
			$events = $rs["events"];
			

			if(!isset($fields[$f])){
				continue;
			}// end if

			$rs["value_ini"] = $seq->evalExp($rs["value_ini"]);

			$field = &$this->fields[$f];

			foreach($rs as $k => $v){

				$field->$k = $v;
			}// next


			$field->cfg_input = $seq->get_param($field->config);


			$field->sForm = $seq->get_param($field->subform);


			if($prop = $seq->get_param($params)){
				foreach($prop as $k => $v){
					$field->$k = $v;
				}// next
			}// end if

			if($prop = $seq->get_param($events)){
				foreach($prop as $k => $v){
					$field->event[$k] = $v;
				}// next
			}// end if

			if($prop = $seq->get_param($field->valid)){
				
				foreach($prop as $k => $v){
					//$field->rules[$k] = $seq->get_param($v);
					$field->rules[$k] = $v;
					
				}// next
			}// end if

	/*

			if($field->data_values != ""){
				$field->data = $this->evalSequence($field->data_values);
			}// end if



*/
			if($field->value_ini_type != 0){
				$field->default = $field->value_ini;
			}// end if

			if(isset($field->sf)){
				$this->_subForm[$f] = $field->sf;	
			}// end if

			$this->data[$f] = $field->default;
			
			if(!$field->input){
				
				$field->input = $this->defaultInput($field->mtype);	
			}// end if

		}// end if
		
	}// end fucntion
	
	public function get_template($template){
		$cn = &$this->cns;
		$cn->query = "
			SELECT code 
			FROM cfg_templates 
			WHERE template = '$template'
			";
		$result = $cn->execute();
		
		if($rs = $cn->get_data()){
			$this->code = $rs["code"];
			
		}// end if
		
	}// end funtion 
		

	public function get_idrecord($rec){

		$record = array();
		if($rec!=""){
			$aux = explode(",", $rec);
			foreach($aux as $k => $v){
				
				$aux2=explode("=", $v);
				
				$record[$aux2[0]] = $aux2[1];
			}// next
		}else{
			//hr("error");
		}// end if
		return $record;
		
	}// end function

	public function getDataRecord($filter="", $index = false){

		global $seq;
		
		$cn = $this->cnn;
		

		if($filter==""){
			
			$filter = $this->filters;
			
		}
				
		
		$query = $this->query;
		//$this->filters_sql = "1=1";
		if($this->filters_sql!=""){
			
			
			
			$str = "(".$this->filters_sql.")";
			if (preg_match("/(WHERE|HAVING)/i", $query, $c)){
				$query = preg_replace ( "/(WHERE|HAVING)/i", "\\0 $str AND ", $query, 1);
			}else{
				$query = preg_replace ( "/(GROUP\s+BY|ORDER|LIMIT|$)/i", " WHERE $str "."\\0", $query, 1);
			}// end if

			
			
		}else if($filter!=""){
			$idrecord = $this->get_idrecord($this->cns->addSlashes($filter));
			
			$q_where="";
			foreach($idrecord as $k => $v){
				
				if(isset($this->fields[$k])){
					
					$table = $this->fields[$k]->table;
					$field = $this->fields[$k]->field;
					$q_where .= (($q_where!="")?" AND ":""). "$table.".$field."='". $v. "'";
					
				}// end if
				
				
			}// next
			//$query = $this->query." WHERE ".$q_where;
			
			$str = "(".$q_where.")";
			if (preg_match("/(WHERE|HAVING)/i", $query, $c)){
				$query = preg_replace ( "/(WHERE|HAVING)/i", "\\0 $str AND ", $query, 1);
			}else{
				$query = preg_replace ( "/(GROUP\s+BY|ORDER|LIMIT|$)/i", " WHERE $str "."\\0", $query, 1);
			}// end if
			
			
		}// end if
		

		if($this->fields_search and $this->q_search!=""){
			
			$fields_search = $cn->setQuotes($this->fields_search);
			$fields = $seq->cmd->getList($fields_search);
			
			
			
			$query = $cn->evalFilters($query, $this->q_search, $fields, ($this->q_search_exact==1)? "": "%");
			
		}// end if		

		
		
		$cn->pagination = false;
		$cn->pageLimit = $this->record_page;
		$cn->page = $this->page;
		
		
		$result = $cn->execute($query);
		
		$data = array();
		while($rs = $cn->getDataAssoc()){
			
			
			$record="";
			foreach($this->keys as $k => $v){
				
				//$record .= (($record!="")?",":"").$k."=".$rs[$this->fields[$k]->position];
				$record .= (($record!="")?",":"").$k."=".$rs[$v];
			}// next
			$rs["__form_mode"] = "2";
			$rs["__form_record"] = $record;
			if($index){
				
				$data[$rs[$index]] = $rs;
				
			}else{
				$data[] = $rs;
				
			}
			
			
		}// end if



		
		$this->num_records = $cn->recordCount;
        $this->num_fields = $cn->fieldCount;
		$this->num_pages = $cn->pageCount;
		return $data;
	}// end function

	public function multiData($q){
		global $seq;
		
		$cn = $this->cnn;
		
		$cn->pagination = $this->pagination;
		$cn->pageLimit = $this->record_page;
		$cn->page = $this->page;
		//$seq->cmd->v->rec = array_merge($seq->cmd->v->rec ,$this->masterData);
		
		//
		
		
		$q = $seq->cmd->evalVar($q);
		
		
		$result = $cn->execute($q, true);
		//hr($cn->query);
		$data = array();
		
		while($rs = $cn->getDataAssoc()){
			
			//$rs = array_map(function($v){return is_string($v)? utf8_encode($v): $v;}, $rs);
			
			$record="";
			if($rs['__check']){
				foreach($this->keys as $k => $v){
					$record .= (($record!="")?",":"").$k."=".$rs[$v];
				}// next
				$rs["__form_record"] = $record;
				$rs["__form_mode"] = "2";
			}else{
				$rs["__form_record"] = $record;
				$rs["__form_mode"] = "0";
			}

			$data[] = $rs;
			
		}// end if

		$this->recordCount = $cn->recordCount;
        $this->fieldCount = $cn->fieldCount;
		$this->pageCount = $cn->pageCount;
		return $data;
		
	}

	public function get_adata($q = ""){
		global $seq;


		
		$cn = $this->cnn;
		

		if($q != ""){
			$query = $q;
		}else{
			$query = $this->query;
		}
		
		
		
		//$this->filters_sql = "1=1";
		if($this->filters_sql != ""){
			
			
			
			$str = "(".$this->filters_sql.")";
			if (preg_match("/(WHERE|HAVING)/i", $query, $c)){
				$query = preg_replace ( "/(WHERE|HAVING)/i", "\\0 $str AND ", $query, 1);
			}else{
				$query = preg_replace ( "/(GROUP\s+BY|ORDER|LIMIT|$)/i", " WHERE $str "."\\0", $query, 1);
			}// end if

			
			
		}else if($this->filters != ""){
			$idrecord = $this->get_idrecord($this->cns->addSlashes($this->filters));
			
			$q_where="";
			foreach($idrecord as $k => $v){
				
				if(isset($this->fields[$k])){
					
					$table = $this->fields[$k]->table;
					$field = $this->fields[$k]->field;
					$q_where .= (($q_where!="")?" AND ":""). "$table.".$field."='". $v. "'";
					
				}// end if
				
				
			}// next
			//$query = $this->query." WHERE ".$q_where;
			
			$str = "(".$q_where.")";
			if (preg_match("/(WHERE|HAVING)/i", $query, $c)){
				$query = preg_replace ( "/(WHERE|HAVING)/i", "\\0 $str AND ", $query, 1);
			}else{
				$query = preg_replace ( "/(GROUP\s+BY|ORDER|LIMIT|$)/i", " WHERE $str "."\\0", $query, 1);
			}// end if
			
			
		}// end if
		

		if($this->fields_search and $this->q_search!=""){
			
			$fields_search = $cn->setQuotes($this->fields_search);
			$fields = $seq->cmd->getList($fields_search);
			
			
			
			$query = $cn->evalFilters($query, $this->q_search, $fields, ($this->q_search_exact==1)? "": "%");
			
		}// end if		

		
		
		$cn->pagination = $this->pagination;
		$cn->pageLimit = $this->record_page;
		$cn->page=$this->page;
		
		
		$result = $cn->execute($query);
		
		$data = array();
		while($rs = $cn->getDataAssoc()){
			
			
			$record="";
			foreach($this->keys as $k => $v){
				
				//$record .= (($record!="")?",":"").$k."=".$rs[$this->fields[$k]->position];
				$record .= (($record!="")?",":"").$k."=".$rs[$v];
			}// next
			$rs["__form_mode"] = "2";
			$rs["__form_record"] = $record;
			$data[] = $rs;
			
		}// end if



		
		$this->num_records = $cn->recordCount;
        $this->num_fields = $cn->fieldCount;
		$this->num_pages = $cn->pageCount;
		return $data;
	}// end function

	public function getData2($record){
		
		if($record==""){
			return array("__form_mode" => 1, "__form_record" => "");
			
		}

		$idrecord = $this->get_idrecord($this->cns->addSlashes($record));
		$q_where = "";
		
		
		$query = $this->query;
		
		
		foreach($idrecord as $k => $v){
			
			
			if(isset($this->fields[$k])){
				
				$table = $this->fields[$k]->table;
				$field = $this->fields[$k]->field;
				$q_where .= (($q_where!="")?" AND ":""). "$table.".$field."='". $v. "'";
				
			}
			
			$str = "(".$q_where.")";
			if (preg_match("/(WHERE|HAVING)/i", $query, $c)){
				$query = preg_replace ( "/(WHERE|HAVING)/i", "\\0 $str AND ", $query, 1);
			}else{
				$query = preg_replace ( "/(GROUP\s+BY|ORDER|LIMIT|$)/i", " WHERE $str "."\\0", $query, 1);
			}// end if
			
		}
	
global $seq;	
		$this->with_data = false;
		if($q_where != ""){

			$cn = &$this->cnn;
			
			$this->query_data = $query;
			
			$query = $seq->cmd->eval_var($query);
			$cn->execute($query, true);
			$data = array();
			//hr($cn->query);
			if($rs = $cn->getDataRow()){
				$this->num_records = $cn->recordCount;
				$this->with_data = true;
				$i=0;
				foreach($this->fields as $k => $v){
					if(isset($rs[$i])){
						$data[$k] = $rs[$i];
					}
					
					$i++;
				}
				
				$data["__form_record"] = $record;
				$data["__form_mode"] = 2;
				
				return $data;
			}else{
				foreach($this->fields as $k => $v){
					if(isset($v->default)){
						
						$data[$k] = $v->default;
					}
					
					
				}	
				$data["__form_record"] = "";
				$data["__form_mode"] = 1;							
				
				return $data;
			}// end if
		}// end if
		
		return array("__form_mode" => 1, "__form_record" => "");
	}// end function

	public function getData($record){
		
		if($record==""){
			return array("__form_mode" => 1, "__form_record" => "");
			
		}

		$idrecord = $this->get_idrecord($this->cns->add_slashes($record));
	
		$q_where = "";	
		$table_aux = "";	
		foreach($this->table_pk as $table => $vv){	
			foreach($this->table_pk[$table] as $k => $v){
				
				if($this->info_fields[$table][$v]->alias){
					$name = $this->info_fields[$table][$v]->alias;
				}else{
					$name = $v;
				}// end if
				
				if($table_aux != $table){
					$table_aux = $table;
					$OP = "OR";
				}else{
					$OP = "AND";
				}
				
				if(isset($idrecord[$name]))
					$q_where .= (($q_where!="")?" $OP ":""). "$table.".$v."='". $idrecord[$name]. "'";
				
			}// next 
		}// next
		$this->with_data = false;
		if($q_where != ""){

			$cn = &$this->cnn;
			$cn->query =$this->query ." WHERE ". $q_where;
			//hr($cn->query,"red","white");
			$cn->execute();
			$this->data = array();
			
			if($rs = $cn->get_data()){
				$this->num_records = $cn->num_records;
				$this->with_data = true;
				
				$rs["__form_record"] = $record;
				$rs["__form_mode"] = 2;
				
				return $rs;
				
			}// end if
		}// end if
		
		return array("__form_mode" => 1, "__form_record" => "");
	}// end function
	
	public function getList($field){
		$list = array();
		foreach($this->data as $k => $v){
			$list[] = $v[$field];
			
			
			
		}
		return implode(",", $list);
	
	}
	
	public function getDataFromParam($param){
		global $seq;
		 
		$params = $seq->cmd->get_param($param);
		$data = array();
		for($i=0; $i < $this->fields_count; $i++){
			
			if(isset($params[$this->info_fields[$i]->name])){
				$data[$i] = $params[$this->info_fields[$i]->name];
				
				
			}else{
				$data[$i] = false;
			}
			

		}
		$this->with_data = true;

		return $data;
	}

	public function evalSequence($q){
		global $seq;
		
		$data = array();
		if(trim($q) == ""){
			return $data;	
		}// end if
		
		$c = $seq->cmd->extract_query($q);
		
		foreach($c[0] as $k => $v){
			if($c[2][$k]!=""){
				
				$c[2][$k] = $seq->cmd->evalVar($c[2][$k]);
				eval("\$eval=".$c[2][$k].";");
				if($eval){
					$aux = $c[4][$k];
				}else{
					$aux = $c[5][$k];
				}// end if
				$this->evalSequence($aux);
			}elseif($c[6][$k] != ""){
				$c[6][$k] = $seq->cmd->evalVar($c[6][$k]);
				eval("\$eval=".$c[6][$k].";");
				if($eval){
					$aux = $c[7][$k];
				}else{
					$aux = $c[8][$k];
				}// end if
				$this->evalSequence($aux);
			}elseif($c[9][$k] != ""){
				$c[10][$k] = $seq->cmd->evalVar($c[10][$k]);
				eval("\$eval=".$c[10][$k].";");
				if($eval){
					$aux = $c[11][$k];
				}elseif($c[12][$k] != ""){
					$this->evalSequence("case;".$c[12][$k]."default:".$c[13][$k]."endcase;");
				}elseif($c[13][$k]!=""){
					$aux = $c[13][$k];
				}else{
					$aux="";
				}// end if					
				$this->evalSequence($aux);
			}elseif($c[16][$k] != ""){
				$data = array_merge($data, $this->fieldData($c[15][$k], $c[16][$k]));
			}elseif($c[17][$k] != ""){
				$data = array_merge($data, $this->fieldData($c[15][$k], $c[17][$k]));
			}else{
				$data = array_merge($data, $this->fieldData($c[15][$k], $c[18][$k]));
			}//end if
		}// next
		
		return $data;
	}// end function

	public function fieldData($key, $value){
		
		global $seq;
		
		
		$value = $seq->cmd->evalVar($value);
			
		switch($key){
		case "q":
			
			return $this->getDataQuery($value);
			break;
		case "list":
			return $this->dataList($value);
			break;
		case "range":
			return $this->get_data_range($value);
			break;
		case "dbase":
			return $this->get_data_dbase($value);
			break;
		case "tables":
			return $this->get_data_tables($value);
			break;
		case "fields":
			return $this->get_data_fields($value);
			break;
		default:
			return array();
		}// end switch

			
		
	}
	
	public function eval_field_data($q){
		global $seq;
		$this->evalSequence($q);
		
		print_r($seq->getSequence($q));
		$param = $seq->get_param($q);
		$data = array();
		foreach($param as $k => $v){
			
			switch($k){
			case "q":
				//hr($v);
				$data = array_merge($data, $this->get_data_query($v));
				break;
			case "list":
				$data = array_merge($data, $this->get_data_list($v));
				break;
			case "range":
				$data = array_merge($data, $this->get_data_range($v));
				break;
			case "dbase":
				$data = array_merge($data, $this->get_data_dbase($v));
				break;
			case "tables":
				$data = array_merge($data, $this->get_data_tables($v));
				break;
			case "fields":
				$data = array_merge($data, $this->get_data_fields($v));
				break;
			default:
				$data = array();
			}// end switch
		}// next
		return $data;
	}// end function	

	public function dataList($q){

		$data = array();
		$aux = explode(",", $q);
		
		foreach($aux as $k => $v){
			
			$rs = explode("=", $v);

			if(isset($rs[2])){
				$data[] = array($rs[0], $rs[1], $rs[2]);
			}else{
				$data[] = array($rs[0], $rs[1]);
			}// end if
			
		}// next

		return $data;
	}// end function

	public function dataListXX($q){
		$data = array();
		$aux = explode(",", $q);
		
		foreach($aux as $k => $v){
			
			$rs = explode("=", $v);
			if(isset($rs[2])){
				$parent = $rs[2];
			}else{
				$parent = "0";
			}// end if
			$data[]=array('value' => $rs[0], 'text' => $rs[1], 'parent' => $parent);			
			
		}// next

		return $data;
	}// end function

	public function getDataQuery($q){

		$cn = &$this->cns;
		$cn->query = $q;
		$result = $cn->execute($q, true);
		
		if($data = $cn->getDataAll($result, 2)){
		
			return $data;
		}
		return array();

	}// end function

	public function getSimpleData($q){

		$cn = &$this->cns;
		$cn->query = $q;
		$result = $cn->execute($q, true);
		
		if($data = $cn->getDataAssoc($result)){
			return $data;
		}
		return array();

	}// end function

	public function getSimpleRecord($q){

		$cn = &$this->cnn;
		
		
		
		$cn->execute($q);
		
		$info = $cn->infoQuery($q);
		
				
		$data = array("__form_mode" => 1, "__form_record" => "");
		$record = "";
		
		$result = $cn->execute($q, true);
		if($rs = $cn->getDataAssoc($result)){
		
			$data = $rs;
			foreach($info->fields as $field){
				if($field->key){
					$record .= (($record!="")?",":"").$field->name."=".$rs[$field->name]; 	
					
				}
				
			}
			

			
			$data["__form_record"] = $record;
			$data["__form_mode"] = 2;
			
			
			
		}// end if
		
		
		return $data;
		
		
		
	}// end function

	public function get_data_query($q){
		
		$data = array();
		$cn = &$this->cns;
		$cn->query = $q;
		$result = $cn->execute($q);
		while($rs = $cn->getData($result)){
			if(isset($rs[2])){
				$parent = $rs[2];
			}else{
				$parent = "0";
			}// end if
			$data[]=array('value' => $rs[0], 'text' => $rs[1], 'parent' => $parent);
		}// end while
		return $data;
	}// end function
	
	public function defaultInput($meta){
		
		
		
		switch($meta){
		case "I":
		case "R":
		case "C":
		case "CH":
		
			return "text";
			break;
		case "D":
			return "calendar";
			break;
		case "S":
			return "date_time";
			break;
		case "T":
			return "time";
			break;
		case "B":
		
			return "textarea";
			break;	
			
		}// end switch
		
	
	
		
	}// end function

	public function saveList($list1, $list2, $filters){
		$a1 = array_flip(explode(",", $list2));

		foreach($a1 as $k => $v){
			$a1[$k] = 1;
		}// next
		
		$a2 = array_flip(explode(",", $list1));
		
		if($filters != $this->filters or $this->field_order != ""){
			$modo_ini = 2;
		}else{
			$modo_ini = 0;
		}// end if

		foreach($a2 as $k => $v){
			if(isset($a1[$k])){
				$a1[$k] = $modo_ini;
			}else{
				$a1[$k] = 3;	
			}// end if
			
		}// next
		
		$idrecord = $this->get_idrecord($this->cns->add_slashes($this->filters));
		
		$j=0;
		
		foreach($a1 as $k => $v){
			if($k==""){
				continue;	
			}// end if
			
			$data["__form_record"] = "$this->field_detail=$k,$filters";
			$data["__form_mode"] = $v;
			$data[$this->field_detail] = $k;
			if($this->field_order){
				$data[$this->field_order] = ($j+1) * $this->field_order_step;	
				
			}// end if
			
			foreach($idrecord as $kk => $vv){
				$data[$kk] = $vv;
			}// next
			
			if($v != 0){
				$this->save($data);	
			}// end if
			
			$j++;
		}// next
		
		
	}// end function

	public function getListForm($form, $detail, $filters){
		global $seq;
		$cn = &$this->cnn;
		$cn->query = "SELECT query FROM $this->t_forms WHERE form='$form'";
		
		
		$result = $cn->execute($cn->query);
		
		if($rs = $cn->getDataAssoc($result)){
			$query = $rs["query"];
		}else{
			$query = "SELECT $detail FROM $form";
		}

		$filters = $seq->cmd->evalVar($filters);
		
		$query .= " WHERE $filters";
		
		$result2 = $cn->execute($query);
		$list = array();
		while($rs = $cn->getDataAssoc($result2)){
			$list[] = $rs[$detail];
		}// end while		
		
		return implode(",", $list);
	}// end function


	public function getSubForm2($opt, $prefix="", $suffix=""){
		
		
		$form = $opt["form"];
		
		$f = new cfg_form();

		if(isset($opt["master"])){
			$f->master = $opt["master"];
		}	

		$f->cnn = $this->cnn;	
		$f->execute($form, "");

		$masterFields = $f->get_idrecord($this->cnn->addSlashes($f->master));
		
		return array(
			"query"=>$f->query,
			"fields"=>$f->fields,
			"tables"=>$f->tables,
			"keys"=>$f->tKeys,
			"cn"=>$f->cns,
			"master"=>$masterFields,
			"multi"=>true,
			"data_suffix"=>$suffix,
			"data_prefix"=>$prefix
		
		);

	}// end function

	public function _getSubForm($opt){
		
		$f = new cfg_form();
		$f->cnn = $this->cnn;	
		$f->detail = $opt["detail"]; 		
		$f->filter = $opt["filter"]; 
		$f->master = $opt["master"]; 

		$f->execute($opt["name"], "");

		$masterFields = array();

		if($f->master){
			$masterFields = $f->get_idrecord($this->cnn->addSlashes($f->master));			
		}
		
		
		
		return array(
			"query"=>$f->query,
			"fields"=>$f->fields,
			"tables"=>$f->tables,
			"keys"=>$f->tKeys,
			"cn"=>$f->cns,
			"master"=>$masterFields
		
		);

	}// end function
	
	public function getSubForm($opt){
		
		
		/*
		
		
		name:persona_estados;
		type:list;
		master:codpersona=codpersona;
		detail:codestado;
		filter:codperiodo:&codperiodo;
		cnn:,
		
		
		*/
		

		$f = new cfg_form();
		$f->cnn = $this->cnn;	
		$f->detail = $opt["detail"]; 		
		$f->filter = $opt["filter"]; 
		$f->master = $opt["master"]; 
		
		$masterFields = $f->get_idrecord($this->cnn->addSlashes($f->master));
		
		$filter = $f->filter;
		
		foreach($masterFields as $k => $v){
			$filter .= (($filter!="")? ",": "").$k."=".$this->data[$v];
		}// next
		
		$f->execute($opt["name"], "");

		$records = $f->getDataRecord($filter, $f->detail);
		
		$fields = array();

		foreach($f->fields as $field2){
			$fields[] = $field2->name;
		}// next
		
		return array(
			"fields"=>$fields,
			"records"=>$records,
			"detail"=>$f->detail,
			"output"=>"value",
		);
		
	}// end function
			

	public function get_data_script($record){
		
		$script = "\n\t\t_data = new Array();";
		$data = $this->getData2($record);
		
		for($i=0;$i<$this->fields_count;$i++){
			
			$name = $this->fields[$i]->name;
			$script .= "\n\t\t_data['$name']='".$data[$i]."';";
			
			
			
		}
		
		$ref = $this->getRef();
		$script .= "\n\t\t$ref.setValue(_data);";
		//$script .= "\n\t\t$ref.__form_mode.value = 2";
		//$script .= "\n\t\t$ref.__form_record.value = '$this->record'";
		//$script .= "\n\t\t$ref.__form_record_".$this->panel.".value = '$this->record'";
		$this->script = $script;
		
		
	}// end function
	
	public function get_field_data($field){
		foreach($this->fields as $k => $v){
			hr($v->name."....".$field);
			if($v->name==$field){
				print_r($v->data);		
			}
		}
	}
	
	
	
	public function configPanel(){
		global $seq;
		$record = "";
		
		foreach($this->keys as $k => $v){
			$record = (($record!="")?",":"")."$k=@$k"."_x";
		
		}// next
		
		return "panel:$this->panel;element:form;name:$this->name;method:$this->method;record:$record;";
		
	}// end funtion 

	
	
	public function getDataField($form, $method, $field_name){
		global $seq;
		$cn = &$this->cnn;
		


		if(isset($this->cfg_from)){
			
			$cfg_from = $this->cfg_from;
			
		}else{
			
			$cfg_from = "";	
		}

		//$cfg_from = "aa,bb,cc,dd";
		//$cfg_from = "datos_paciente";
		
		
		$aux = explode(",", $cfg_from);
		$aux = array_merge($aux, array_values($this->tables));
		
		$list_form = "'".implode("','",$aux)."'";
		
		array_unshift($aux, $this->form);

		$str = "";
		foreach($aux as $k => $v){
			$str .= "WHEN '$v' THEN $k ";
		}
		$order_by = "CASE form ".$str." END";
					
		$cn->query = "
				SELECT a.* 
				FROM $this->t_form_fields as a 
				WHERE a.name='$field_name' AND (
					(form='$this->form' AND (method='' OR method='$method'))
					OR (form in ($list_form)))
				ORDER BY $order_by DESC, method";
				
		$result = $cn->execute($cn->query);
		$data = array();
		
		while($rs = $cn->getDataAssoc($result)){

			$f = $rs["name"];
			$params = $rs["params"];
			$events = $rs["events"];
			

			$rs["value_ini"] = $seq->evalExp($rs["value_ini"]);

			$field = new stdClass;
			foreach($rs as $k => $v){

				$field->$k = $v;
			}// next


			if($field->data_values != ""){
				$data = $this->evalSequence($field->data_values);
			}// end if


			$field->cfg_input = $seq->get_param($field->config);


			$field->sForm = $seq->get_param($field->subform);


			if($prop = $seq->get_param($params)){
				foreach($prop as $k => $v){
					$field->$k = $v;
				}// next
			}// end if	
		}// end while
		
		return $data;
	}// end function
	
	public function cfgField($form, $method, $field_name){
		global $seq;
		$cn = &$this->cnn;
		
		
		$tables = implode("','", $this->tables);
		
		$cn = &$this->cns;
		$cn->query = "
				SELECT a.* 
				FROM $this->t_form_fields as a 
				WHERE 
					a.name='$field_name' AND 
					(method='' OR method='$method')
					AND (
					(form='$this->form') 
					OR 
					(form in ('$tables')))";



					
					
				
		$result = $cn->execute($cn->query);
		$data = array();
		$field = new stdClass;
		while($rs = $cn->getDataAssoc($result)){
			
		
			//$t = $rs["table"];
			$f = $rs["name"];
			$params = $rs["params"];
			$events = $rs["events"];
			



			$rs["value_ini"] = $seq->evalExp($rs["value_ini"]);

			//$field = &$this->fields[$f];
			
			foreach($rs as $k => $v){

				$field->$k = $v;
			}// next






			if($field->data_values != ""){
				$data = $this->evalSequence($field->data_values);
			}// end if


			$field->cfg_input = $seq->get_param($field->config);


			$field->sForm = $seq->get_param($field->subform);


			if($prop = $seq->get_param($params)){
				foreach($prop as $k => $v){
					$field->$k = $v;
				}// next
			}// end if	
		}// end while
		
		return $field;
	}// end function	
	
}// end class





?>