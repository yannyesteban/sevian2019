<?php
/*****************************************************************
creado: 18/12/2015
por: Yanny Nu�ez
Versi�n: 1.0
*****************************************************************/


class textNode{
	
	private $_text = "";
	private $_charset = "ISO8859-1";
	public function __construct($text = ""){
		$this->_text = $text;
	}// end function
	
	public function render(){
		return $this->_text;
	}// end function
	
}// end class

class sgHTML{
	
	public $__type = "sgHTML";

	public $tagName = "";
	public $hide = false;

	public $html = "";
	public $script = "";
	public $css = "";

	//public $class = "";

	private $_ele = array();
	//private $_charset = "ISO8859-1";
	private $_charset = "UTF-8";
	private $_propertys = array();
	
	public $__init = array();

	public function __construct($opt = ""){
		
		if(is_array($opt)){
			foreach($opt as $k => $v){
				$this->$k = $v;	
			}// next			
			
		}else{
			$this->tagName = $opt;
			
		}// end if

		
		
		//$this->_propertys["class"] = &$this->class;
		
	}// end function
	
	
	
	static function create($opt){
		return new sgHTML($opt)	;
		
	}
	/* se agrega un objeto hijo al elemento, pudiendo ser un texto o un objeto*/	
	



	public function appendChild($ele){

		if(is_object($ele)){
			/* el elemento que se agrega deber�a tener el metodo: ->render()*/
			return $this->_ele[] = $ele;
		}else{
			return $this->_ele[] = new textNode($ele);
		}// end if

	}// end function

	public function add($opt){

		if(is_array($opt)){
			$ele = new sgHTML($opt);
		}elseif(is_object($opt)){
			$ele = $opt;
		}else{
			$ele = new sgHTML($opt);
		}// end if

		return $this->appendChild($ele);

	}// end function
	
	public function insertFirst($ele){
		
		if(is_object($ele)){
			/* el elemento que se agrega deber�a tener el metodo: ->render()*/
			$ele_x = $ele;
		}else{
			$ele_x = new textNode($ele);
		}// end if

		array_unshift($this->_ele, $ele_x);

	}// end function

	/*se optiene el arreglo de todos hijos*/
	public function getChilds(){

		return $this->_ele;
		
	}// end function

	/*se optiene el primer elemento hijo*/
	public function firstChild(){

		if(isset($this->_ele[0])){
			return $this->_ele[0];
		}// end if
		return false;
		
	}// end function
	
	/*se optiene el �ltimo elemento hijo*/
	public function lastChild(){

		$n = count($this->_ele)-1;
		if(isset($this->_ele[$n])){
			return $this->_ele[$n];
		}// end if
		return false;
		
	}// end function

	/*se optiene el n elemento hijo*/
	public function getChild($index){

		if(isset($this->_ele[index])){
			return $this->_ele[index];
		}else{
			return false;	
		}// end if
		
	}// end function

	public function setAttribute($attribute, $value){

		$this->$attribute = $value;

	}// end function	
	
	private function renderAttribute(){

		$str = "";
		
		foreach($this->_propertys as $k => $v) {

			if($v === false){
				continue;	
			}// end if

			$v = htmlspecialchars($v, ENT_QUOTES, $this->_charset);
			$str .= " ".strtolower(trim($k)).'="'.$v.'"';

		}// next

		return $str;

	}// end function	

	public function render(){

		if($this->hide){
			return "";	
		}

		switch($this->tagName){
		case "br":
		case "hr":
		case "iframe":
		case "img":
		case "input":
		case "meta":
		case "link":
			return "<$this->tagName".$this->renderAttribute()."/>";
			break;	
		default:
			$str = "";
			foreach($this->_ele as $v){
				$str .= $v->render();
			}// next
			if($this->tagName != ""){
				return $this->html = "<$this->tagName".$this->renderAttribute().">".$str."</$this->tagName>";	
			}else{
				return $this->html = $str;
			}// end if
			break;
		}// end switch
		
	}// end function

	public function getScript(){

		$script = "";

		foreach($this->_ele as $obj){
			if(method_exists($obj, "getScript")){
				$script .= $obj->getScript();
			}
		}// next
		
		return $script.$this->script;
		
	}// end function	

	public function getCss(){

		$css = "";
		
		foreach($this->_ele as $obj){
			if(method_exists($obj, "getCss")){
				$css .= $obj->getCss();
			}
		}// next

		return $css.$this->css;
	
	}// end function	
	
	public function __set($name, $value){
        
		switch(strtolower(trim($name))){
		case "innerhtml":
		case "text":
			$this->_ele = array();
			$this->appendChild($value);

			return;
			break;
		}// end switch
		
        $this->_propertys[$name] = $value;
		
    }// end function

    public function __get($name){

		switch(strtolower(trim($name))){
		case "innerhtml":
		case "text":
			$str = "";
			foreach($this->_ele as $k => $v){
				$str .= $v->render();
			}// next		
			return $str;
			break;
		}// end switch
        
        if(array_key_exists($name, $this->_propertys)) {
            return $this->_propertys[$name];
        }
    }// end function
    
	public function __isset($name){
		
        return isset($this->_propertys[$name]);
		
    }// end function
		
}// end class

class sgHTMLx{
	
	public $tagName = "";
	public $innerHTML = false;
	public $text = false;
	public $hide = false;
	private $_ele = false;
	
	private $noRender = array();
	private $_charset = "ISO8859-1";
	//private $_charset = "UTF-8";
	

	public function __construct($tagName = ""){
		$this->tagName = $tagName;
		$this->text = &$this->innerHTML;
	}// end function
	
	
	/* se agrega un objeto hijo al elemento, pudiendo ser un texto o un objeto*/	
	public function appendChild($ele){
		
		if($this->_ele === false and $this->innerHTML !== false){
			$this->_ele[] = new textNode($this->innerHTML);
			$this->innerHTML = false;
		}
		if(is_object($ele)){
			/* el elemento que se agrega deber�a tener el metodo: ->render()*/
			$this->_ele[] = $ele;
		}else{
			$this->_ele[] = new textNode($ele);
			
		}
	}// end function

	public function insertFirst($ele){
		
		if($this->_ele === false and $this->innerHTML !== false){
			
			$ele_x = new textNode($this->innerHTML);
			$this->innerHTML = false;
		}
		if(is_object($ele)){
			/* el elemento que se agrega deber�a tener el metodo: ->render()*/
			$ele_x = $ele;
		}else{
			$ele_x = new textNode($ele);
			
		}
		if(!$this->_ele){
			$this->_ele = array();
		}
		array_unshift($this->_ele, $ele_x);
	}// end function


	/*se optiene el arreglo de todos hijos*/
	public function getChilds(){

		if($this->_ele){
			return $this->_ele;
			
		}elseif($this->innerHTML !== false){
			$this->_ele[] = new textNode($this->innerHTML);
			return $this->_ele;
		}// end if
		return false;
		
	}// end function


	/*se optiene el primer elemento hijo*/
	public function firstChild(){

		if(isset($this->_ele[0])){
			return $this->_ele[0];
			
		}elseif($this->innerHTML !== false){
			return $this->_ele[] = new textNode($this->innerHTML);
		}// end if
		return false;
		
	}// end function
	/*se optiene el �ltimo elemento hijo*/
	public function lastChild(){

		$n = count($this->_ele)-1;
		if(isset($this->_ele[$n])){
			return $this->_ele[$n];
		}elseif($this->innerHTML !== false){
			return $this->_ele[] = new textNode($this->innerHTML);			
		}// end if
		return false;
		
	}// end function

	/*se optiene el n elemento hijo*/
	public function getChild($index){
		
		if($this->_ele){
			if(isset($this->_ele[index])){
				return $this->_ele[index];
			}else{
				return false;	
			}
			
		}elseif($this->innerHTML !== false and $index == 0){
			return $this->_ele[] = new textNode($this->innerHTML);			
		}// end if
		return false;
		
	}// end function

	public function setAttribute($attribute, $value){
		$this->$attribute = $value;
	}// end function	
	
	private function renderAttribute(){
		$str = "";
		foreach($this as $k => $v) {

			if(is_object($k) or is_array($k) or is_object($v) or is_array($v) or $v == ""){
				continue;	
			}// end if

			if(isset($this->noRender[$v])){
				continue;
				
			}// end if

			switch(strtolower(trim($k))){
				
			case "tagname":
			case "innerhtml":
			case "text":
			case "_ele":
			case "_charset":
			case "parennode":
			case "hide":
				continue;
				break;
			default:

				$v = htmlspecialchars($v, ENT_QUOTES, $this->_charset);
				$str .= " ".strtolower(trim($k)).'="'.$v.'"';
				break;
			}// end switch
		}// next
		return $str;
	}// end function	

	public function render(){

		if($this->hide){
			return "";	
		}

		switch($this->tagName){
		case "br":
		case "hr":
		case "iframe":
		case "img":
		case "input":
		case "meta":
		case "link":
			return "<$this->tagName".$this->renderAttribute()."/>";
			break;	
		default:
			if($this->innerHTML !== false or !$this->_ele){
				return "<$this->tagName".$this->renderAttribute().">".$this->innerHTML."</$this->tagName>";
			}else{
			
				$str = "";
				foreach($this->_ele as $k => $v){
					$str .= $v->render();
				}// next
				if($this->tagName != ""){
					return "<$this->tagName".$this->renderAttribute().">".$str."</$this->tagName>";	
				}else{
					return $str;
				}// end if
			
			}// end if
			break;
		}// end switch
	}// end function
}// end class


class sgTableRow extends sgHTML{
	public $cells = array();	
	
	
}

class sgTable extends sgHTML{
	
	
	public $rows = array();
	public $cols = array();
	public $cells = array();
	
	private $_prop = array();
	private $_rows = 0;
	private $_cols = 0;
	
	public $thead = false;
	public $tbody = false;
	public $tfoot = false;

	private $_ty = array("table" => "table", "caption"=>"caption", "tr" => "tr", "td" => "td", "th" => "th", "empty" => "", "thead"=>"thead", "tbody"=>"tbody", "tfoot"=>"tfoot"); 
	//array("table" => "div", "caption"=>"div", "tr" => "ul", "td" => "li", "th" => "th", "empty" => "&nbsp;")
	
	private $_lastGroup = false;
	public function __construct($cols=false){

		$this->_cols = $cols;
		

	}// end function
	
	public function getNumRows(){
		return $this->_rows;	
	}// end function
	
	public function setTags($tags){
		$this->_ty = $tags;
		
	}// end function


	public function setTag($tag, $newTag){
		$this->_ty[$tag] = $newTag;
		
	}// end function
	
	public function typeRender($type){
		switch($type){
		case "table":
			$this->setTags(array("table" => "table", "caption"=>"caption", "tr" => "tr", "td" => "td", "th" => "th", "empty" => "", "thead"=>"thead", "tbody"=>"tbody", "tfoot"=>"tfoot"));
			break;
		case "div":
			$this->setTags(array("table" => "div", "caption"=>"div", "tr" => "div", "td" => "div", "th" => "div", "empty" => "", "thead"=>"div"));
			break;
		case "span":
			$this->setTags(array("table" => "span", "caption"=>"span", "tr" => "span", "td" => "span", "th" => "span", "empty" => "", "thead"=>"span"));
			break;
		case "ol":
			$this->setTags(array("table" => "div", "caption"=>"div", "tr" => "ol", "td" => "li", "th" => "th", "empty" => "", "thead"=>"div"));
			break;
		case "ul":
			$this->setTags(array("table" => "div", "caption"=>"div", "tr" => "ul", "td" => "li", "th" => "th", "empty" => "", "thead"=>"div"));
			break;
			
		}
		
		
	}
	public function createCaption($caption){

		$this->_prop["caption"] = new sgHTML($this->_ty["caption"]);
		$this->_prop["caption"]->innerHTML = $caption;
		$this->insertFirst($this->_prop["caption"]);
		$this->insertFirst("\n");
		return $this->_prop["caption"];

	}// end function

	public function setTHead(){
		$this->thead = new sgHTML($this->_ty["thead"]); 
		$this->_lastGroup = $this->thead;
		$this->appendChild($this->thead);
	}
	
	public function setTBody(){
		$this->tbody = new sgHTML($this->_ty["tbody"]); 
		$this->_lastGroup = $this->tbody;
		$this->appendChild($this->tbody);
	}
	
	public function setTFoot(){
		$this->tfoot = new sgHTML($this->_ty["tfoot"]); 
		$this->_lastGroup = $this->tfoot;
		$this->appendChild($this->tfoot);
	}
	
	public function insertRow($pos=-1){
		$tr = $this->rows[$this->_rows] = new sgTableRow($this->_ty["tr"]);

		for($c=0; $c < $this->_cols; $c++){
			$cell = $this->cells[$this->_rows][$c] = new sgHTML($this->_ty["td"]);
			$cell->appendChild($this->_ty["empty"]);
			$tr->cells[$c] = $cell;
			$tr->appendChild("\n\t");
			$tr->appendChild($cell);
			
		}// next
		$tr->appendChild("\n");
		$this->appendChild("\n");
		
		if($this->_lastGroup){
			$this->_lastGroup->appendChild($tr);
		}else{
			$this->appendChild($tr);
		}
		//$this->appendChild("\n");
		$this->_rows++;
		return $tr;
	}// end function
	
	
	public function insertColumn($pos = -1){
		$this->_cols++;
		foreach($this->rows as $tr){
			$cell = $tr->cells[$this->_cols] = new sgHTML($this->_ty["td"]);
			$cell->appendChild($this->_ty["empty"]);
			
			
			$cell = $this->cells[$this->_rows][$c] = new sgHTML($this->_ty["td"]);
			
			
			$tr->appendChild("\n\t");
			$tr->appendChild($cell);			
			
			
		}
		
	}


	public function mergeCells($r1, $c1, $r2, $c2){
		
		$rows = $r2 - $r1 + 1;
		$cols = $c2 - $c1 + 1;
		
		for($r = $r1; $r <= $r2; $r++){
			for($c= $c1; $c <= $c2; $c++){
				if ($r != $r1 or $c != $c1){
					$this->cells[$r][$c]->hide = true;
				}// end if
			}// next $j
		}// next $i
		
		if($this->_ty["table"] == "table"){
			$this->cells[$r1][$c1]->rowspan = ($rows > 1)? $rows: "";
			$this->cells[$r1][$c1]->colspan = ($cols > 1)? $cols: "";
		}
		
	}// end function


	public function mergeRow($r1, $c1 = -1, $num_cells = -1){
   		if($num_cells == -1){
       		$c2 = $this->_cols - 1;
		}else{
       		$c2 = $c1 + $num_cells - 1;
		}
        if($c1 == -1){
       		$c1 = 0;
		}
		$this->mergeCells($r1, $c1, $r1, $c2);
    }// end function

	public function mergeCol($r1, $c1 = -1, $num_cells = -1){
		
   		if ($num_cells == -1){
       		$r2 = $this->_rows - 1;
		}else{
       		$r2 = $r1 + $num_cells - 1;
		}
        if($c1 == -1){
       		$c1 = $r1;
       		$r1 = 0;
        }// end if
		$this->mergeCells($r1, $c1, $r2, $c1);
    }// end function
	
	public function render(){
		$this->tagName = $this->_ty["table"];
		return sgHTML::render();
		
	}
	
}// end class







?>