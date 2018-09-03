<?php

class sg_fragment extends sg_panel{
	public $t_fragments = "";
	public $icnn = SS_CNN;
	public $icns = SS_CNS;
	public $icnd = SS_CND;	
	public function __construct() {
		
		$this->cnn = conection($this->icnn);
		$this->cnd = conection($this->icnd);
		if($this->icns!=""){
			$this->cns = conection($this->icns);
		}else{
			$this->cns = &$this->cnn;
		}// end if
		
		$this->t_fragments = TABLE_PREFIX."fragments";
	}// end fucntion

	public function execute($name="", $method=""){
			global $seq;
	
	
			
	
		if(isset($this->eparams["NO design_mode"])){
			
			$cn = &$this->cnd;
			$this->cnn = conection($this->icns);
			$this->cns = $this->cnd;
		}else{
			$cn = &$this->cns;
		}


		
		$cn->query = "SELECT * FROM $this->t_fragments WHERE fragment = '$name'";
		$result = $cn->execute($cn->query);
		if($rs = $cn->getDataAssoc($result)){
			
			foreach($rs as $k => $v){
				$this->$k = $v;
				
			}// next
			
			$this->html = $seq->cmd->evalVar($this->html);
		}else{


			
		}// end if

			
			
	}
	
	
	public function evalMethod($method){
		switch($method){
		case "load":
		
			$this->execute($this->name, $method);	
			$this->render = 1;
			$this->mode = 1;
			
			
			
			break;
		}// end switch
		
	}// end fucntion		
	
}


?>