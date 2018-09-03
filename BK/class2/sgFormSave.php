<?php
class sgFormSave{

	public $icn = SS_CNN;

	public $cn = false;
	
	public $tables = false;
	public $query = false;
	public $table = false;	
	public $master = array();	

	public $multi = false;

	public $data = false;
	private $_data = array();
	private $_index = false;
	
		
	public $transaction = true;
	
	public $field_mode = "__form_mode";
	public $field_record = "__form_record";

	public $fields = false;
	public $_fields = false;
	
	public $data_suffix = "";
	public $data_prefix = "";

	public $error = false;
	public $errno = 0;
	
	public function __construct($opt = false){

		global $seq;
		
		$this->vses = &$seq->getVSes();
		$this->vreq = &$seq->getVarReq();
		$this->vexp = &$seq->getVarExp();
		
		if($opt){
			$this->set($opt);	
			
		}
		
	}// end function
	
	public function set($opt){
		
		foreach($opt as $k => $v){
			if($k == "data"){
				$this->data = &$opt["data"];
			}else{
				$this->$k = $v;
			}
			
		}// next		
		
		if(!$this->cn){
			$this->cn = conection($this->icn);
		}
		
		if($this->query){
			$this->infoQuery($this->query);	
		}elseif($this->table){
			$this->infoTable($this->table);	
		}
		
		
		
		if($this->_fields){
			foreach($this->_fields as $name => $_field){
				foreach($_field as $kk => $vv){
					$this->fields[$name]->$kk = $vv;
				}// next
			}// next
		}
		
		if($this->data){
			$this->save($this->data);
		}
	}// end function	
	
	
	public function beginTransaction(){

		if($this->transaction){
			$this->cn->begin();
		}
		
	}// end function

	public function endTransaction($error){

		if($this->transaction){
			if(!$error){
				$this->cn->commit();
				return "commit";
			}else{
				$this->cn->rollback();
				return "rollback";
			}// end if
		}
		
	}// end function

	public function setPrefix($prefix){
		$this->data_prefix = $prefix;
		
	}// end function
	public function setSuffix($suffix){
		$this->data_suffix = $suffix;
	}// end function

	public function setTables($tables){
		$this->tables = $tables;
	}// end function

	public function setFields($fields){
		$this->fields = $fields;
	}// end function

	public function setKeys($keys){
		$this->keys = $keys;
	}// end function
	
	public function infoTable($table){
		
		$info = $this->cn->describeTable($table);
		$this->tables = $info->tables;
		$this->fields = $info->fields;
		$this->keys[$table] = $info->keys;
		return $info; 
	}// end function
	
	public function infoQuery($query){
		
		$info = $this->cn->infoQuery($query);
		$this->tables = $info->tables;
		$this->fields = $info->fields;
		$this->keys = $info->keys;
		
		return $info;
	}// end function
	
	public function recordId($str){

		$record = array();
		if($str != ""){
			$aux = explode(",", $str);
			foreach($aux as $k => $v){
				$aux2 = explode("=", $v);
				$record[$aux2[0]] = $aux2[1];
			}// next
		}// end if
		return $record;
		
	}// end function
	
	public function setFieldParam($field, $param, $value){
		if(isset($this->fields[$field])){
			$this->fields[$field]->$param = $value;
		}
		
	}// end function
	
	
	public function value($field){
		
		if($this->_index === false){
		
			$name = $this->data_prefix.$field.$this->data_suffix;
		
		}else{
		
			$name = $this->data_prefix.$field.$this->data_suffix.$this->_index;
		
		}
		
		if(isset($this->_data[$name])){
			
			return $this->_data[$name];
		}else{
			
			return NULL;	
		}
		
		
	}// end function
	
	public function save(&$a_data, $dataMaster = array()){
		$this->beginTransaction();	
		$this->error = false;	

		//reset($data);
		$aux = current($a_data);

		

		if(is_array($aux)){
		
			$log = new stdClass;
			$log->records = array();
			foreach($a_data as $k => $data){
				$log->records[] = $this->saveRecord($a_data[$k], $dataMaster);
			
			}// next
			
			
		}elseif($this->multi){
			
			$n=0;
			$pre = $this->data_prefix;
			$suf = $this->data_suffix;
			
			$log = new stdClass;
			$log->records = array();
		
			while(isset($a_data[$pre.$this->field_mode.$suf.$n])){
				$log->records[] = $this->saveRecord($a_data, $dataMaster, $n);
				$n++;
			};
			
			
			
		}else{
			
			$log = $this->saveRecord($a_data, $dataMaster);
			
		}

		$log->transaction = $this->endTransaction($this->error); 
		$this->log = $log;
		return $log;

	}// end function

	public function saveRecord(&$data, $dataMaster = array(), $index = false){
		
		
		$this->_data = &$data;
		$this->_index = $index;
		
		$qError = false; 
		
		$log = new stdClass;
		$log->t = array();
		$cn = $this->cn;

		
		
		$record = $recordId = $this->recordId($this->value($this->field_record));
		



	

		$subform = array();

		foreach($this->tables as $table){

			if($table == ""){
				continue;	
			}
			
			//$mode = $data[$this->field_mode];
			$mode = $this->value($this->field_mode);
			
			
			$serial = false;

			$q_fields = array();
			$q_values = array();
			$q_set = array();


			$q_where = "";
			
			$lastId = false;
			
			$log_t = array();
			
			if($mode != 1){
				
				foreach($this->keys[$table] as $fieldName => $name){
					
					if(!isset($recordId[$name])){
						$qError = true;
						break;
					}

					$q_where .= (($q_where!="")? " AND ": "").$cn->addQuotes($fieldName)."='".$cn->addSlashes($recordId[$name])."'";
					
				}// next
				
				if($mode == 4){
					$mode = 1;
					if($q_where !=""){
						$q_exist = "SELECT 1 as a FROM $table WHERE $q_where ";
						
						$result = $cn->execute($q_exist);
						if($rs = $cn->getData($result)){
							$mode = 2;
						}
						
						
					
						
					}
					
				}				
							
			}// end if	

			if($mode <= 0){
				continue;
			}

			foreach($this->fields as $name => $field){

				
				if(isset($field->subform) and $field->subform){
					
					$subform[$name] = $field;
					
				}
				
				if($field->table != $table or $field->table == ""){
					continue;	
				}

			
				
				if(isset($field->aux) and $field->aux){
					continue;	
				}
				
				if(isset($field->no_update) and $field->no_update){
					continue;	
				}				
				
								
				
				if(isset($field->sql_value)){
					$fieldValue = $field->sql_value;
				}else{

					if(isset($this->master[$name])){
						$field->master_value = $this->master[$name];
					}
					
					if(isset($field->ref_value) and $this->value($field->ref_value) !== NULL){
						
						$value = $this->value($field->ref_value);
						
					}elseif(isset($field->master_value) and isset($dataMaster[$field->master_value])){
						
						$value = $dataMaster[$field->master_value];	
					
					}elseif(isset($field->serialize)){
						
						if(isset($field->serialize_filters)){
							$aux = explode(",", $field->serialize_filters);
							$filters = array();
							foreach($aux as $f){
								if(!is_null($this->value($f))){
									$filter[$f] = $this->value($f);
								}
							}// next
							$value = $cn->serialId($table, $name, $filters);
						}else{
							$value = $cn->serialId($table, $name);	
						}// end if
					}elseif(!is_null($this->value($name))){
						$value = $this->value($name);	
					}else{
						$value = "";	
					}// end if
					
					if(isset($field->upper)){
						$value = strtoupper($value);	
					}
					if(isset($field->lower)){
						$value = strtolower($value);	
					}
					if(isset($field->capitalice)){
						$value = ucfirst($value);	
					}
					if(isset($field->capitalice_words)){
						$value = ucwords($value);	
					}					

					$this->fields[$name]->value = $value;
					if(isset($field->set_ses)){
						$this->vses[$field->set_ses] = &$this->fields[$name]->value;
					}
					if(isset($field->set_req)){
						$this->vreq[$field->set_req] = &$this->fields[$name]->value;
					}
					if(isset($field->set_exp)){
						$this->vexp[$field->set_exp] = &$this->fields[$name]->value;
					}

	

					if(($field->serial or $field->mtype == "S") and $field->not_null and $value == ""){
						
						$serial = $name;
						continue;
					}

					if(($field->mtype != "C" and $field->mtype != "CH" and $field->mtype != "B" or !$field->not_null) and $value == ""){
						$fieldValue = "null";
					}else{
						$fieldValue = "'".$cn->addSlashes($value)."'";
					}
										
				}// end if
	
				if($field->key){
					$record[$name] = $value;
				}	

				$fieldName = $cn->addQuotes($field->field);
				
				switch($mode){
				case 1:
					$q_values[] = $fieldValue;
					$q_fields[] = $fieldName;
					break;
				case 2:
					$q_set[] = $fieldName."=".$fieldValue;
					break;	
				}// end switch			
				
			}// next


			if($qError){
				$q_where = "";	
			}

			$q = "";
			$q_table = $cn->addQuotes($table);

			switch($mode){
			case 1:
				$q = "INSERT INTO $q_table (".implode(", ",$q_fields).") VALUES (".implode(", ",$q_values).");";
				break;
			case 2:
				$q = "UPDATE $q_table SET ". implode(", ", $q_set). " WHERE ". $q_where;
				break;
			case 3:
			case 6:
				$q = "DELETE FROM $q_table WHERE ". $q_where;
				break;
			}// end switch

			if($mode > 0 and $q != ""){
				$result = $cn->execute($q);
			}
			if($serial and $mode == 1){
				$lastId = $cn->getLastId();
				$record[$serial] = $lastId;
				$data[$serial] = $lastId;
				$this->fields[$serial]->value = $lastId;
			}// end if
			
			$log_t["table"] = $table;
			$log_t["q"] = $q;
			$log_t["mode"] = $mode;
			$log_t["keys"] = $this->keys[$table];
			
			$log_t["lastId"] = $lastId;
			$log_t["error"] = $cn->error;
			$log_t["errno"] = $cn->errno;
			$log->t[] = $log_t;
			
			if($cn->error){
				$this->error = true;
				$this->errno = $cn->errno;		
			}
			//hr($this->error."...".$q);			
		}// next

		$log->error = $this->errno;
		
		$log->record = $record;
		$log->recordId = $recordId;
		
		if($subform){
			
			$s = new sgFormSave();
			foreach($subform as $name => $field){
				
				//hr($field->subForm);
				
				$s = new sgFormSave($field->subForm);
				$s->cn = $this->cn;
				
				//$records = json_decode($data[$name], true);
				$records = $data;
				
				$r = $log->sub[] = $s->save($records, $data);
				
				//hr($r);
			}// next
			
			
		}
		
		
		return $log;
	}// end function

		
	public function save1(&$data){
		
		
		$qError = false; 
		$this->error = false;
		$log = new stdClass;
		$log->t = array();
		$cn = $this->cn;
		
		$this->beginTransaction();

		$recordId = $this->recordId($data[$this->field_record]);
		$record = $recordId;

		$pre = $this->data_prefix;
		$suf = $this->data_suffix;

		foreach($this->tables as $table){

			if($table == ""){
				continue;	
			}
			
			$mode = $data[$this->field_mode];
			$serial = false;

			$q_fields = array();
			$q_values = array();
			$q_set = array();


			$q_where = "";
			
			$lastId = false;
			
			$log_t = array();
			
			if($mode != 1){
				foreach($this->keys[$table] as $fieldName => $name){
					
					if(!isset($recordId[$name])){
						$qError = true;
						break;
					}

					$q_where .= (($q_where!="")? " AND ": "").$cn->addQuotes($fieldName)."='".$cn->addSlashes($recordId[$name])."'";
					
				}// next
				
				if($mode == 4){
					$q_exist = "SELECT 1 as a FROM $table WHERE $q_where ";
					$mode = 1;
					$result = $cn->execute($q_exist);
					if($rs = $cn->getData($result)){
						$mode = 2;
					}
				}				
							
			}// end if	

			foreach($this->fields as $name => $field){
				
				if($field->table != $table or $field->table == ""){
					continue;	
				}
				
				if(isset($field->sql_value)){
					$fieldValue = $field->sql_value;
				}else{
					
					if(isset($field->ref_value) and isset($data[$pre.$field->ref_value])){
						$value = $data[$pre.$field->ref_value];
					}elseif(isset($field->serialize)){
						if(isset($field->serialize_filters)){
							$aux = explode(",", $field->serialize_filters);
							$filters = array();
							foreach($aux as $f){
								if(isset($data[$pre.$f])){
									$filter[$f] = $data[$pre.$f];
								}
							}// next
							$value = $cn->serialId($table, $name, $filters);
						}else{
							$value = $cn->serialId($table, $name);	
						}// end if
					}elseif(isset($data[$pre.$name])){
						$value = $data[$pre.$name];	
					}else{
						$value = "";	
					}// end if
					
					if(isset($field->upper)){
						$value = strtoupper($value);	
					}
					if(isset($field->lower)){
						$value = strtolower($value);	
					}
					if(isset($field->capitalice)){
						$value = ucfirst($value);	
					}
					if(isset($field->capitalice_words)){
						$value = ucwords($value);	
					}					
					if($field->serial and $field->not_null and $value == ""){
						$serial = $name;
						continue;
					}
					if(($field->mtype != "C" and $field->mtype != "CH" and $field->mtype != "B" or !$field->not_null) and $value == ""){
						$fieldValue = "null";
					}else{
						$fieldValue = "'".$cn->addSlashes($value)."'";
					}
										
				}// end if
	
				if($field->key){
					$record[$name] = $value;
				}	

				$fieldName = $cn->addQuotes($field->field);
				
				switch($mode){
				case 1:
					$q_values[] = $fieldValue;
					$q_fields[] = $fieldName;
					break;
				case 2:
					$q_set[] = $fieldName."=".$fieldValue;
					break;	
				}// end switch			
				
			}// next
			

			if($qError){
				$q_where = "";	
			}

			$q = "";
			$q_table = $cn->addQuotes($table);

			switch($mode){
			case 1:
				$q = "INSERT INTO $q_table (".implode(", ",$q_fields).") VALUES (".implode(", ",$q_values).");";
				break;
			case 2:
				$q = "UPDATE $q_table SET ". implode(", ", $q_set). " WHERE ". $q_where;
				break;
			case 3:
			case 6:
				$q = "DELETE FROM $q_table WHERE ". $q_where;
				break;
			}// end switch
			
			$result = $cn->execute($q);
			
			//hr($q, "#DA6299");
			//hr($q, "#334455");
			if($serial and $mode == 1){
				$lastId = $cn->getLastId();
				$record[$serial] = $lastId;
				$data[$serial] = $lastId;
			}// end if
			
			$log_t["table"] = $table;
			$log_t["q"] = $q;
			$log_t["mode"] = $mode;
			$log_t["keys"] = $this->keys[$table];
			
			$log_t["lastId"] = $lastId;
			$log_t["error"] = $cn->error;
			$log_t["errno"] = $cn->errno;
			$log->t[] = $log_t;

			if($cn->error){
				$this->error = true;
				$this->errno = $cn->errno;	
			}
						
		}// next
		$log->error = $this->errno;
		$log->record = $record;
		$log->recordId = $recordId;
		$log->transaction = $this->endTransaction($this->error);
		
		return $log;
	}// end function

	public function saveRecord_007(&$data, $dataMaster = array()){
		
		$qError = false; 
		
		$log = new stdClass;
		$log->t = array();
		$cn = $this->cn;

		
		$recordId = $this->recordId($data[$this->field_record]);
		$record = $recordId;



		$pre = $this->data_prefix;
		$suf = $this->data_suffix;

		$subform = array();

		foreach($this->tables as $table){

			if($table == ""){
				continue;	
			}
			
			$mode = $data[$this->field_mode];
			$serial = false;

			$q_fields = array();
			$q_values = array();
			$q_set = array();


			$q_where = "";
			
			$lastId = false;
			
			$log_t = array();
			
			if($mode != 1){
				
				foreach($this->keys[$table] as $fieldName => $name){
					
					if(!isset($recordId[$name])){
						$qError = true;
						break;
					}

					$q_where .= (($q_where!="")? " AND ": "").$cn->addQuotes($fieldName)."='".$cn->addSlashes($recordId[$name])."'";
					
				}// next
				
				if($mode == 4){
					$mode = 1;
					if($q_where !=""){
						$q_exist = "SELECT 1 as a FROM $table WHERE $q_where ";
						
						$result = $cn->execute($q_exist);
						if($rs = $cn->getData($result)){
							$mode = 2;
						}
						
						
					
						
					}
					
				}				
							
			}// end if	

			foreach($this->fields as $name => $field){
				
				
				if(isset($field->subform) and $field->subform){
					
					$subform[$name] = $field;
					
				}
				
				if($field->table != $table or $field->table == ""){
					continue;	
				}
				
				if(isset($field->aux) and $field->aux){
					continue;	
				}
				
				if(isset($field->no_update) and $field->no_update){
					continue;	
				}				
				
				if(isset($field->sql_value)){
					$fieldValue = $field->sql_value;
				}else{

					if(isset($this->master[$name])){
						$field->master_value = $this->master[$name];
					}
					
					if(isset($field->ref_value) and isset($data[$pre.$field->ref_value])){
						$value = $data[$pre.$field->ref_value];
					}elseif(isset($field->master_value) and isset($dataMaster[$field->master_value])){
						$value = $dataMaster[$field->master_value];	
					}elseif(isset($field->serialize)){
						if(isset($field->serialize_filters)){
							$aux = explode(",", $field->serialize_filters);
							$filters = array();
							foreach($aux as $f){
								if(isset($data[$pre.$f])){
									$filter[$f] = $data[$pre.$f];
								}
							}// next
							$value = $cn->serialId($table, $name, $filters);
						}else{
							$value = $cn->serialId($table, $name);	
						}// end if
					}elseif(isset($data[$pre.$name])){
						$value = $data[$pre.$name];	
					}else{
						$value = "";	
					}// end if
					
					if(isset($field->upper)){
						$value = strtoupper($value);	
					}
					if(isset($field->lower)){
						$value = strtolower($value);	
					}
					if(isset($field->capitalice)){
						$value = ucfirst($value);	
					}
					if(isset($field->capitalice_words)){
						$value = ucwords($value);	
					}					

					$this->fields[$name]->value = $value;
					if(isset($field->set_ses)){
						$this->vses[$field->set_ses] = &$this->fields[$name]->value;
					}
					if(isset($field->set_req)){
						$this->vreq[$field->set_req] = &$this->fields[$name]->value;
					}
					if(isset($field->set_exp)){
						$this->vexp[$field->set_exp] = &$this->fields[$name]->value;
					}



					if(($field->serial or $field->mtype == "S") and $field->not_null and $value == ""){
						$serial = $name;
						continue;
					}

					if(($field->mtype != "C" and $field->mtype != "CH" and $field->mtype != "B" or !$field->not_null) and $value == ""){
						$fieldValue = "null";
					}else{
						$fieldValue = "'".$cn->addSlashes($value)."'";
					}
										
				}// end if
	
				if($field->key){
					$record[$name] = $value;
				}	

				$fieldName = $cn->addQuotes($field->field);
				
				switch($mode){
				case 1:
					$q_values[] = $fieldValue;
					$q_fields[] = $fieldName;
					break;
				case 2:
					$q_set[] = $fieldName."=".$fieldValue;
					break;	
				}// end switch			
				
			}// next
			

			if($qError){
				$q_where = "";	
			}

			$q = "";
			$q_table = $cn->addQuotes($table);

			switch($mode){
			case 1:
				$q = "INSERT INTO $q_table (".implode(", ",$q_fields).") VALUES (".implode(", ",$q_values).");";
				break;
			case 2:
				$q = "UPDATE $q_table SET ". implode(", ", $q_set). " WHERE ". $q_where;
				break;
			case 3:
			case 6:
				$q = "DELETE FROM $q_table WHERE ". $q_where;
				break;
			}// end switch

			if($mode > 0 and $q != ""){
				$result = $cn->execute($q);
			}
			if($serial and $mode == 1){
				$lastId = $cn->getLastId();
				$record[$serial] = $lastId;
				$data[$serial] = $lastId;
				$this->fields[$serial]->value = $lastId;
			}// end if
			
			$log_t["table"] = $table;
			$log_t["q"] = $q;
			$log_t["mode"] = $mode;
			$log_t["keys"] = $this->keys[$table];
			
			$log_t["lastId"] = $lastId;
			$log_t["error"] = $cn->error;
			$log_t["errno"] = $cn->errno;
			$log->t[] = $log_t;
			
			if($cn->error){
				$this->error = true;
				$this->errno = $cn->errno;		
			}
			//hr($this->error."...".$q);			
		}// next

		$log->error = $this->errno;
		
		$log->record = $record;
		$log->recordId = $recordId;
		
		if($subform){
			
			$s = new sgFormSave();
			foreach($subform as $name => $field){
				
				//hr($field->subForm);
				
				$s = new sgFormSave($field->subForm);
				$s->cn = $this->cn;
				
				//$records = json_decode($data[$name], true);
				$records = $data;
				
				$r = $log->sub[] = $s->save($records, $data);
				
				//hr($r);
			}// next
			
			
		}
		
		
		return $log;
	}// end function
	
	public function saveRecordM(&$data, $dataMaster = array(), $index=""){
		
		$qError = false; 
		
		$log = new stdClass;
		$log->t = array();
		$cn = $this->cn;
		
		$pre = $this->data_prefix;
		$suf = $this->data_suffix;
		
		$field_record = $pre.$this->field_record.$suf.$index;
		$field_mode = $pre.$this->field_mode.$suf.$index;

		$recordId = $this->recordId($data[$field_record]);
		$record = $recordId;



		$subform = array();

		foreach($this->tables as $table){

			if($table == ""){
				continue;	
			}
			
			$mode = $data[$field_mode];
			
			if($mode == 0){
				return false;	
			}
			
			$serial = false;

			$q_fields = array();
			$q_values = array();
			$q_set = array();


			$q_where = "";
			
			$lastId = false;
			
			$log_t = array();
			
			if($mode != 1){
				foreach($this->keys[$table] as $fieldName => $name){
					
					if(!isset($recordId[$name])){
						$qError = true;
						break;
					}

					$q_where .= (($q_where!="")? " AND ": "").$cn->addQuotes($fieldName)."='".$cn->addSlashes($recordId[$name])."'";
					
				}// next
				
				if($mode == 4){
					$q_exist = "SELECT 1 as a FROM $table WHERE $q_where ";
					$mode = 1;
					$result = $cn->execute($q_exist);
					if($rs = $cn->getData($result)){
						$mode = 2;
					}
				}				
							
			}// end if	

			foreach($this->fields as $name => $field){
				
				
				
				if(isset($field->subform) and $field->subform){
					
					$subform[$name] = $field;
					
				}
				
				if($field->table != $table or $field->table == ""){
					continue;	
				}
				
				
				if(isset($field->sql_value)){
					$fieldValue = $field->sql_value;
				}else{

					if(isset($this->master[$name])){
						$field->master_value = $this->master[$name];
					}
					
					if(isset($field->ref_value) and isset($data[$pre.$field->ref_value.$suf.$index])){
						$value = $data[$pre.$field->ref_value.$suf.$index];
					}elseif(isset($field->master_value) and isset($dataMaster[$field->master_value])){
						$value = $dataMaster[$field->master_value];	
					}elseif(isset($field->serialize)){
						if(isset($field->serialize_filters)){
							$aux = explode(",", $field->serialize_filters);
							$filters = array();
							foreach($aux as $f){
								if(isset($data[$pre.$f.$suf.$index])){
									$filter[$f] = $data[$pre.$f.$suf.$index];
								}
							}// next
							$value = $cn->serialId($table, $name, $filters);
						}else{
							$value = $cn->serialId($table, $name);	
						}// end if
					}elseif(isset($data[$pre.$name.$suf.$index])){
						$value = $data[$pre.$name.$suf.$index];	
					}else{
						$value = "";	
					}// end if
					
					if(isset($field->upper)){
						$value = strtoupper($value);	
					}
					if(isset($field->lower)){
						$value = strtolower($value);	
					}
					if(isset($field->capitalice)){
						$value = ucfirst($value);	
					}
					if(isset($field->capitalice_words)){
						$value = ucwords($value);	
					}					
					if($field->serial and $field->not_null and $value == ""){
						$serial = $name;
						continue;
					}
					if(($field->mtype != "C" and $field->mtype != "CH" and $field->mtype != "B" or !$field->not_null) and $value == ""){
						$fieldValue = "null";
					}else{
						$fieldValue = "'".$cn->addSlashes($value)."'";
					}
					
					
					if(isset($field->set_ses)){
						
						$this->vses[$field->set_ses] = $value;
					}

					if(isset($field->set_req)){
						$this->vreq[$field->set_req] = $value;
					}

					if(isset($field->set_exp)){
						$this->vexp[$field->set_exp] = $value;
					}

										
				}// end if
	
				if($field->key){
					$record[$name] = $value;
				}	

				$fieldName = $cn->addQuotes($field->field);
				
				switch($mode){
				case 1:
					$q_values[] = $fieldValue;
					$q_fields[] = $fieldName;
					break;
				case 2:
					$q_set[] = $fieldName."=".$fieldValue;
					break;	
				}// end switch			
				
			}// next
			

			if($qError){
				$q_where = "";	
			}

			$q = "";
			$q_table = $cn->addQuotes($table);

			switch($mode){
			case 1:
				$q = "INSERT INTO $q_table (".implode(", ",$q_fields).") VALUES (".implode(", ",$q_values).");";
				break;
			case 2:
				$q = "UPDATE $q_table SET ". implode(", ", $q_set). " WHERE ". $q_where;
				break;
			case 3:
			case 6:
				$q = "DELETE FROM $q_table WHERE ". $q_where;
				break;
			}// end switch
			
			if($mode > 0 and $q != ""){
				$result = $cn->execute($q);	
			}
			
			
			if($serial and $mode == 1){
				$lastId = $cn->getLastId();
				$record[$serial] = $lastId;
				$data[$serial] = $lastId;
			}// end if
			
			$log_t["table"] = $table;
			$log_t["q"] = $q;
			$log_t["mode"] = $mode;
			$log_t["keys"] = $this->keys[$table];
			
			$log_t["lastId"] = $lastId;
			$log_t["error"] = $cn->error;
			$log_t["errno"] = $cn->errno;
			$log->t[] = $log_t;

			if($cn->error){
				$this->error = true;
				$this->errno = $cn->errno;		
			}
			//hr($this->error."...".$q);			
		}// next

		$log->error = $this->errno;
		$log->record = $record;
		$log->recordId = $recordId;
		
		if($subform){
			
			$s = new sgFormSave();
			foreach($subform as $name => $field){
				
				$s = new sgFormSave($field->subForm);
				$s->cn = $this->cn;
				
				$records = json_decode($data[$name], true);
				
				$log->sub[] = $s->save($records, $data);
				
				
			}// next
			
			
		}
		
		
		return $log;
	}// end function	

	
	
	
	

	
	
}// end class
?>