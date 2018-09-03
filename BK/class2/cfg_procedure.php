<?php 
/*****************************************************************
creado: 01/04/2014
modificado: 20/04/2015, 19/02/2016
por: Yanny Nuñez
*****************************************************************/

class cfg_procedure extends sg_panel{

	var $procedure = "";
	var $title = "";
	var $query = "";
	var $vars = "";
	var $expressions = "";
	var $params = "";
	var $modo_transaccion = "";
	var $on_refresh = false;
	var $refresh = 1;
	var $module = "";
	var $user = "";
	var $update = "";
	var $status = "";
	
	var $q_master = "";
	var $q_range = "";
	var $q_repeat = "";
	var $q_list = "";
	var $q_record = "";

	var $use_default = false;

	var $error = false;

	var $icnn = SS_CNN;
	var $icns = SS_CNS;
	private $t_procedures = "";
	//===========================================================
	public function __construct() {
		$this->cnn = conection($this->icnn);
		if($this->icns!=""){
			$this->cns = conection($this->icns);
		}else{
			$this->cns = &$this->cnn;
		}// end if
		
		
		$this->t_procedures = TABLE_PREFIX."procedures";
					
	}// end function
	
	//===========================================================
	public function execute($procedure=""){

		global $seq;

		if($procedure!=""){
			$this->procedure = $procedure;
		}// end if

		if($this->procedure == ""){
			return false;
		}// end if
		
		$cn = &$this->cns;
		
		$cn->query = "	SELECT * 
						FROM $this->t_procedures as p 
						WHERE p.procedure = '$this->procedure'";
		$result = $cn->execute($cn->query);
		if($rs = $cn->getDataAssoc($result)){
			
			$this->v->par = &$rs;
			foreach($rs as $k => $v){
				$this->$k = $v;
			}// next
			if($prop = $seq->cmd->get_param($this->params)){
				//$this->vpara = array_merge($this->vpara,$prop);
				foreach($prop as $k => $v){
					$this->$k = $v;
				}// next
			}// end if

			if($this->on_refresh and $this->refresh == 0){
				return false;
			}// end if

			if($prop = $seq->cmd->get_param($this->vars)){
				 $seq->cmd->addSession($prop);
			}// end if
			if($prop = $seq->cmd->get_param($this->expressions)){
				 $seq->cmd->addExpression($prop);
			}// end if
			
			$this->cnn->begin();
			$this->execQMaster();
			if($this->error){
				$this->cnn->rollback();
			}else{
				$this->cnn->commit();
			}// end if
		}else{
			$this->log(array("ERROR"=>$cn->query));
		}// end if		
	}// end function
	
	public function execQMaster(){
		global $seq;
		
		if($this->q_master != ""){// q_master:SQL;
			$seq->cmd->v->exp["PRC_QMASTER"] = $this->q_master;
			$cn = &$this->cnn;
			$result = $this->cnn->execute($this->q_master);
			if($cn->errno == 0 and $cn->withResult){
				while($rs = $cn->get_fetch_assoc($result)){
					$seq->cmd->addExpression($rs);
					$seq->cmd->execQuery();
				}// end while
			}else{
				$seq->cmd->v->exp["PRC_ERROR_QMASTER"] = $this->q_master;
				$this->error = true;
			}// end if			
		}elseif($this->q_range != ""){//q_range:1,4,2;
			$seq->cmd->v->exp["PRC_QMASTER"] = $this->q_range;
			$aux = explode(",", $this->q_range);
			$begin = $aux[0];
			$end = $aux[1];

			if(isset($aux[2])){
				$step = $aux[2];
			}else{
				$step = 1;
			}// end if
			
			if($begin>$end or $step<0){
				for($i=$begin;$i>=$end;$i-=abs($step)){
					$seq->cmd->v->exp["PRC_I"] = $i;
					$this->execQuery();
				}// next
			}else{
				for($i=$begin;$i<=$end;$i+=$step){
					$seq->cmd->v->exp["PRC_I"] = $i;
					$this->execQuery();
				}// next				
			}// next

		}elseif($this->q_repeat != ""){//q_repeat:5;
			for($i=0;$i<$this->q_repeat;$i++){
				$seq->cmd->v->exp["PRC_I"] = $i;
				$this->execQuery();
			}// next				
		
		}elseif($this->q_list !=""){//q_repeat:2,4,5,6,aa,cc,7;
			$aux = explode(",", $this->q_list);
			foreach($aux as $k => $v){
				$seq->cmd->v->exp["PRC_I"] = $v;
				$this->execQuery();
			}// next

		}elseif($this->q_record != ""){//q_record:field_a=1,field_b=2;field_a=4,field_b=5;
			$aux = explode(";", $this->q_record);
			foreach($aux as $k => $v){
				$aux2 = explode(",", $v);
				foreach($aux2 as $kk => $vv){
					$aux3 = explode("=", $vv);
					$seq->cmd->v->exp[$aux3[0]] = $aux3[1];
				}// next
				$seq->cmd->v->exp["PRC_I"] = $k;
				$this->execQuery();
			}// next
		
			for($i=0;$i<$this->q_repeat;$i++){
				$seq->cmd->v->exp["PRC_I"] = $i;
				$this->execQuery();
			}// next				
			
		}else{
			
			$this->execQuery();
		}// end if
	}// end function
	
	public function execQuery(){
		
		global $seq;
		$q = "";
		
		$querys = $seq->cmd->extractSQL($this->query);
		$cn = &$this->cnn;

		$log_main = array();	

		foreach($querys as $n => $q){
			
			$log["ini"] = $q;
			$q = $seq->cmd->eval_var($q);
			$log["end"] = $q;
			$result = $this->cnn->execute($q);
			
			$log["Record Count"] = null;
			$log["Affected Rows"] = null;
			$log["Last Id"] = null;
			$log["Status"] = "Ok !!!";
			$log["msg"] = null;
			
			if(!$cn->errno){
				if($cn->fieldCount){
					if($rs = $cn->getDataAssoc($result)){
						
						$seq->cmd->addSession($rs);
					}
					
					
					$seq->cmd->v->exp["PRC_NUM_RECORDS_Q$n"] = $cn->recordCount;
					$log["Record Count"] = $cn->recordCount;
				}else{
					$seq->cmd->v->exp["PRC_AFFECTED_ROWS_Q$n"] = $cn->affectedRows;
					$lastId = $cn->getLastId();
					$log["Affected Rows"] = $cn->affectedRows;
					if($lastId){

						$seq->cmd->v->exp["PRC_LAST_ID_Q$n"] = $lastId;
						$seq->cmd->v->exp["PRC_LAST_ID"] = $lastId;
						if(isset($this->set_id)){
							$seq->cmd->v->ses[$this->set_id] = $lastId;
						}// end if
	
						$log["Last Id"] = $lastId;
							
					}// end if	
				}// end if
				
			}else{
				$log["Status"] = "Error !!!";
				$log["msg"] = $cn->error;
				$seq->cmd->v->exp["PRC_ERROR_ROWS_Q$n"] = $q;
				$this->error = true;
			}//endif
			
			$log_main["q. ".($n+1)] = $log; 
		}// next

		$this->log(array("query:"=>$log_main));
		
		return true;
		
	}// end function 


	public function evalMethod($method){
		global $seq;
		
		
		$seq->setVarRec(array());
		

		$this->log();

		$this->log(array(
			"element"=>$this->element,
			"title"=>$this->title,
			"name"=>$this->name,
			"method"=>$method,
			"panel"=>$this->panel
		));	

	
		$this->render = 0;
		switch($method){
			case "init":
			
			
				//$seq->procedure = new cfg_procedure;
				
				break;
			
			case "load":
				$this->execute($this->name);
				
				
				break;
			
		}// end switch
		
		if($this->type!=0 and $this->render != 0){

			$this->html = $this->getDinamicPanel($this->html);
		}// end if		
	}// end function
	
}// end class
?>