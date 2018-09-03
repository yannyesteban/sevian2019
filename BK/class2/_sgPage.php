<?php
class _sgPage extends sgHTML{
	public $__type = "_sgPage";
	
	public $tagName = "section";
	public $caption = false;
	public $body = false;
	
	private $_caption = false;
	private $_ref = "page";
	//public $__init = array();
	
	public function __construct	($opt = false){
		
		
		
		if(is_array($opt)){
			$this->__init = $opt;
			foreach($opt as $k => $v){
				$this->$k = $v;	
			}// next			
		}else{
			$this->__init["id"] = $opt;
		}// end if			
		
		if($this->caption){
			$this->setCaption($this->caption);
		}

		$this->{"data-page-type"} = "main";

		$this->body = new sgHTML("div");
		$this->body->{"data-page-type"} = "body";
		sgHTML::appendChild($this->body);
		
	}// end fucntion


	
	public function setCaption($opt){
		
		if(!$this->_caption){
			$this->_caption = new sgHTML("header");
			sgHTML::insertFirst($this->_caption);
		}

		if(is_array($opt)){
			foreach($opt as $k => $v){
				$this->_caption->$k = $v;	
			}// next			
		}else{
			$caption = $opt;
			$this->_caption->innerHTML = $caption;
		}// end if	
		
		
		$this->_caption->{"data-page-type"} = "caption";
		return $this->_caption;
		
	}// end fucntion
	
	public function getCaption(){
		return $this->_caption;
	}// end fucntion
	
	public function appendChild($e){
		return $this->body->appendChild($e);
	}// end fucntion

	public function getScript(){
		$ref = $this->getRef();
		
		$script = "";
		
		$script .= "\n$ref = new _sgPage('$this->id');";
		
		$script .= sgHTML::getScript();
		
		return $script;
		
		
	}// end fucntion

	public function setRef($name){
		
		$this->_ref = $name;	
	
	}// end function
	
	public function getRef(){
		
		return $this->_ref;	
	
	}// end function

}// end class


?>