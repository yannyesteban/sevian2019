<?php
/*****************************************************************
creado: 20/02/2016
creado: 02/06/2016
por: Yanny Nuñez
Versión: 1.0
*****************************************************************/

class cfg_sequence extends sg_panel{
	public $element = "sequence";
	public function __construct() {
		
		$this->cnn = conection($this->icnn);
		if($this->icns!=""){
			$this->cns = conection($this->icns);
		}else{
			$this->cns = &$this->cnn;
		}// end if
		
		$this->t_sequences = TABLE_PREFIX."sequences";		
		
	}// end function	
	
	
	public function execute($sequence = ""){
		
		if($sequence != ""){
			$this->sequence = $sequence;
		}else{
			return false;
		}// end if

		$cn = &$this->cns;
		
		$query = "
			SELECT * 
			FROM $this->t_sequences 
			WHERE sequence = '$this->sequence'";
		$result = $cn->execute($query);
		
		if($rs = $cn->getDataAssoc($result)){

			foreach($rs as $k => $v){
				$this->$k = $v;
			}// next
			
			$this->log(array("instruction"=>$this->instructions));
			
			return $this->instructions;
		}else{
			$this->log(array("ERROR"=>$cn->query));			
		}// end if
		return false;		
	}// end fucntion	

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
			case "load":
				if($q = $this->execute($this->name)){
					$seq->getCmd($q);	
				}
				
				break;
			
		}// end switch

		
		


		
		if($this->type!=0 and $this->render != 0){
			$this->html = $this->getDinamicPanel($this->html);
		}// end if		
	}// end function	

	
}// end class

?>