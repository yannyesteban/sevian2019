<?php
namespace Sevian;

class InfoRecord{
	
	public $fields = array();
	public $tables = array();
	public $data = array();
	public $details = array();
	public $connectionId = false;
	
	public function __construct($opt = array()){
		foreach($opt as $k => $v){
			if(property_exists($this, $k)){
				$this->$k = $v;
			}
		}
	}
}

class InfoRecordField{
	
	public $name = false;
	public $field = false;
	public $table = false;
	
	public $aux = false;
	public $update = true;
	
	public $serialize = false;//["serialize_filters"=>"codpais"];
	
	public $propertys = ["uppercase", "md5", "pass"];
	public $rules =[];
	
	public $mtype = "S";
	public $serial = "";
	public $key = "";
	public $notNull =false;
	
		
	public $refValue = false;
	public $masterValue = false;
	public $expValue = "Numero {=value} &name @cedula";
	
	public $default = "";
	
	public $details = array();
	
	
	public $sqlValue = false;
	public $md5 = false;
	public $upper = false;
	public $lower = false;
	public $capitalice = false;
	public $capitaliceWords = false;
	//public $value = "";
	public $setSes = false;
	public $setReq = false;
	public $setExp = false;
	
	public function __construct($opt = array()){
		foreach($opt as $k => $v){
			if(property_exists($this, $k)){
				$this->$k = $v;
			}
		}
	}
}

class Record{
	
	public $tables = array();
	public $fields = array();
	
	public $keys = array();
	
	public $connection = false;
	public $connectionId = false;
	
	public $master = array();
	public $dataMaster = array();
	
	public $multi = false;
	
	public $dataPrefix = "";
	public $dataSuffix = "";
	
	
	
	public $dataRecords = array();
	
	public $error = false;
	
	
	
	public $transaction = true;
	
	public $onDebug = true;
	
	private $ses = array();
	private $req = array();
	private $exp = array();
	
	private $_data = array();
	private $_details = array();
	
	private $_dataRecord = array();
	private $_log = array();
	
	private $_message = "";
	
	public function __construct($opt = array()){
		
		global $sevian;
		
		$this->ses = &$sevian->getVSes();
		
		foreach($opt as $k => $v){
			if(property_exists($this, $k)){
				$this->$k = $v;
			}
		}
		
		if(!$this->connection and $this->connectionId){
			$this->setConnection($this->connectionId);
		}
		
		
	}
	
	public function setConnection($id = ""){
		
		if($id != ""){
			$this->connectionId = $id;
		}
		
		$this->connection = Connection::get($this->connectionId);
		
	}
	
	public function setTable($table){
		
		$info = $this->connection->infoTable($table);
		
		$iFields = array();
		
		foreach($info->fields as $field){
			$iFields[$field->name] = new InfoRecordField($field);
		}
		
		$this->fields = $iFields;
		$this->tables = $info->tables;
		$this->keys = $info->keys;
		
	}
	
	public function infoQuery($query){

		$info = $this->connection->infoQuery($query);
		
		$iFields = array();
		
		foreach($info->fields as $field){
			$iFields[$field->name] = new InfoRecordField($field);
		}
		
		$this->fields = $iFields;
		$this->tables = $info->tables;
		$this->keys = $info->keys;
		
	}
	
	public function getField($name){
		return $this->fields[$name];
		
	}
	
	public function addDetail($record){
		$this->_details[] = $record;
		
	}
	
	public function setData(&$data){
		
		$this->_data = $data;
	}
	
	public function beginTransaction(){

		if($this->transaction){
			$this->connection->begin();
		}
		
	}

	public function endTransaction($error){

		if($this->transaction){
			if(!$error){
				$this->connection->commit();
				return "commit";
			}else{
				$this->connection->rollback();
				return "rollback";
			}// end if
		}
		
	}
	
	private function value($field){
		
		if($this->_index === false){
			$name = $this->dataPrefix.$field.$this->dataSuffix;
		}else{
			$name = $this->dataPrefix.$field.$this->dataSuffix.$this->_index;
		}
		
		if(isset($this->_dataRecord[$name])){
			return $this->_dataRecord[$name];
		}else{
			return NULL;	
		}
		
	}
	
	public function saveRecord(&$data, $dataMaster = false, $index = false){
		
		
		$this->_dataRecord = &$data;
		$this->_index = $index;
		
		if($dataMaster){
			$this->dataMaster = $dataMaster;
		}
		
		$qError = false; 
		
		$cn = Connection::get($this->connectionId);
		$recordId = array();
		
		if($this->value("__record_id")){
			$recordId = $this->dataRecords[$this->value("__record_id")];
		}else if($this->value("__record")){
			$recordId = $this->value("__record");
			
		}
		
		
		$details = false;
		
		foreach($this->tables as $table){

			if($table == ""){
				continue;	
			}
			
			//$mode = $data[$this->field_mode];
			
			$mode = $this->value("__record_mode");
			
			
			
			$serial = false;

			$q_fields = array();
			$q_values = array();
			$q_set = array();


			$q_where = "";
			
			$lastId = false;
			
			$log_t = array();
			
			if($mode != 1){
				
				foreach($this->keys as $key){
					if(!isset($recordId[$key])){
						$qError = true;
						break;
					}
					$q_where .= (($q_where!="")? " AND ": "").$cn->addQuotes($key)."='".$cn->addSlashes($recordId[$key])."'";
				}
				
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
			}

			if($mode <= 0){
				continue;
			}

			foreach($this->fields as $name => $field){
				
				
				if($field->details){
					
					
					
					$details[$name] = $field;
					
				
				}
				
				if($field->table != $table or $field->table == ""){
					continue;	
				}

				if($field->aux){
					continue;	
				}
				
				if($mode > 1 and !$field->update){
					continue;	
				}
				
				$value = "";
				$fieldValue = "";
								
				
				if($field->sqlValue){
					$fieldValue = $field->sqlValue;
				}else{
					
					if(isset($this->master[$name])){
						$field->masterValue = $this->master[$name];
					}
					
					if($field->refValue and $this->value($field->refValue) !== NULL){
						
						$value = $this->value($field->refValue);
						
					}elseif($field->masterValue and isset($this->dataMaster[$field->masterValue])){
						
						$value = $this->dataMaster[$field->masterValue];	
					
					}elseif($field->serialize){
						
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
					
					if($field->upper){
						$value = strtoupper($value);	
					}
					if($field->lower){
						$value = strtolower($value);	
					}
					if($field->capitalice){
						$value = ucfirst($value);	
					}
					if($field->capitaliceWords){
						$value = ucwords($value);	
					}					

					$this->fields[$name]->value = $value;
					
					if($field->setSes){
						$this->vses[$field->setSes] = &$this->fields[$name]->value;
					}
					if($field->setReq){
						$this->vreq[$field->setReq] = &$this->fields[$name]->value;
					}
					if($field->setExp){
						$this->vexp[$field->setExp] = &$this->fields[$name]->value;
					}

	

					if(($field->serial or $field->mtype == "S") and $field->notNull and $value == ""){
						
						$serial = $name;
						continue;
					}

					if(($field->mtype != "C" and $field->mtype != "CH" and $field->mtype != "B" or !$field->notNull) and $value == ""){
						$fieldValue = "null";
					}else{
						$fieldValue = "'".$cn->addSlashes($value)."'";
					}
										
				}// end if
	
				
				if($field->rules){
					
					if($error = Valid::send($field->rules, $value, $field->name, array())){
						$this->error = true;
						$this->_message = $error;
						
						return false;
						
					}
					
										
				}
				
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
//hr($q);
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
			$log_t["keys"] = $this->keys;
			
			$log_t["lastId"] = $lastId;
			$log_t["error"] = $cn->error;
			$log_t["errno"] = $cn->errno;
			$log->t[] = $log_t;
			
			if($cn->error){
				$this->error = true;
				$this->errno = $cn->errno;		
			}
			
			
			
			if($this->onDebug){
				$this->_log->add(array(
					
					"table"=>$table,
					"q" => $q,
					"mode" => $mode,
					"keys" => $this->keys,
					"lastId" => $lastId,
					"error" => $cn->error,
					"errno" => $cn->errno,
					
				
				
				));
				
				
			}			
			
			
			
		}// next
		
		
		
		foreach($this->_details as $detail){
			
			$detail->save(false, $data);
			
		}
		
		if($details){
			
			foreach($details as $name => $field){
				$s = $field->details;
				$s->save($data[$name], $data);
			}
			
		}
		
		
		
		
	}
	
	public function save($data = false, $dataMaster = array()){
		
		
		if($data !== false){
			$this->_data = $data;
		}
		
		if(!$this->connection){
			
			$this->setConnection();
		}
		
		$this->beginTransaction();	
		$this->error = false;	

		//reset($data);
		$aux = current($this->_data);

		if($this->onDebug){
			$this->_log = Debug\Log::addPanel(array(
			"element"=>"Sevian\Record"
			
			));	
		}

		$result = true;
		
		if(is_array($aux)){
		
			
			
			
			foreach($this->_data as $k => $data){
				$result = $this->saveRecord($this->_data[$k], $dataMaster);
			}// next
			
			
		}elseif($this->multi){
			
			$n=0;
			$pre = $this->dataPrefix;
			$suf = $this->dataSuffix;
			
			$log = new stdClass;
			$log->records = array();
		
			while(isset($this->_data[$pre.$this->field_mode.$suf.$n])){
				$result = $this->saveRecord($this->_data, $dataMaster, $n);
				$n++;
			};
			
			
			
		}else{
			
			$result = $this->saveRecord($this->_data, $dataMaster);
			
		}

		
		
		$result = $this->endTransaction($this->error); 
		
		if($this->onDebug){
			$this->_log->result["error"] = $this->error;
			$this->_log->result["transaction"] = $result;
			$this->_log->result["message"] = $this->_message;
			
		}
		
		$request = array(
			"error"			=> $this->error,
			"transaction"	=> $result,
			"message"		=> $this->_message,
			
		
		);
		
		return $request;

	}// end function
	
	
	private function vars($q){
		return sgTool::vars($q, array(
			array(
				"token" 	=> "@",
				"data" 		=> $this->ses,
				"default" 	=> false
			),
			array(
				"token"		=> "\#",
				"data" 		=> $this->req,
				"default" 	=> false
			),
			array(
				"token" 	=> "&EX_",
				"data" 		=> $this->exp,
				"default" 	=> false
			),
		));
	}
}// end class




?>