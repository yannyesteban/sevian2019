<?php

/*paginador version 1.0, 06-may-2014*/
/*paginador version 1.1, 13-dic-2015*/
//require_once ("sgHTML.php");
class sgPaginator extends sgHTML{
	//public $id = "";
	//public $class = "";
	public $class_default = "";
	public $page = 1;
	public $num_pages = false;
	public $max_pages = 4;
	
	public $goto_type = 1;
	
	public $action = "";
	
	public function __construct($page="", $num_pages="", $max_pages="", $action=""){
		if($page!==""){
			$this->page = $page;
		}// end if
		if($num_pages!==""){
			$this->num_pages = $num_pages;
		}// end if
		if($page!==""){
			$this->max_pages = $max_pages;
		}// end if


		if($action!==""){
			$this->action = $action;
		}// end if

		
		$this->render1();
	}// end function
	
	public function render1(){

		if($this->num_pages<=0){
			return;	
			
		}

		if($this->class != ""){
			$class = $this->class."_pag_";
			
		}else{
			$class = $this->class_default."_pag_";
		}// end if
		
		if($this->page <= 0){
			$this->page = 1;
		}else if($this->page > $this->num_pages){
			$this->page = $this->num_pages;
		}// end if
		
		$page_ini = $this->max_pages * floor((($this->page - 1) / $this->max_pages)) + 1;
		$page_end = $page_ini + $this->max_pages - 1;
		
		if ($page_end > $this->num_pages){
			$page_end = $this->num_pages;
		}// end if			
		
		$page_prev = ($this->page == 1)? 1: $this->page - 1;
		$page_next = ($this->page == $this->num_pages)? $this->num_pages: $this->page + 1;

		//$main = new sgHTML("div");
		$this->class = $class."main";
		
		$body = new sgHTML("div");
		$body->class =  $class."body";
		
		$pgFirst = new sgHTML("span");
		$pgPrev = new sgHTML("span");
		$pgNext = new sgHTML("span");
		$pgLast = new sgHTML("span");


		$pgFirst->class = $class."first";
		$pgPrev->class = $class."prev";
		$pgNext->class = $class."next";
		$pgLast->class = $class."last";
		
		$pgFirst->onclick = $this->eval_action(1);
		$pgPrev->onclick = $this->eval_action($page_prev);
		$pgNext->onclick = $this->eval_action($page_next);
		$pgLast->onclick = $this->eval_action($this->num_pages);
		
		$body->appendChild($pgFirst);
		$body->appendChild($pgPrev);


		
		for($i = $page_ini; $i <= $page_end; $i++){
			$pg = new sgHTML("span");
			
			if($this->page == $i){
				$pg->class = $class."i_act";
			}else{
				$pg->class = $class."i";
				$pg->onclick = $this->eval_action($i);
			}// end if
			$pg->innerHTML = $i;
			
			$body->appendChild($pg);

		}// next	

		$body->appendChild($pgNext);
		$body->appendChild($pgLast);
		$this->appendChild($body);

		
		if($this->num_pages > $this->max_pages){
		
			$select = new sgHTML("select");
			$select->onchange = str_replace("{=page}", "this.value", $this->action);
			$select->class =  $class."select";
			
			for($i = 1; $i<$this->num_pages; $i = $i + $this->max_pages){
				$opt = new sgHTML("option");
				$opt->value = "$i";	
				$opt->text = "$i";
				if ($i >= $page_ini and $i <= $page_end){
					$opt->selected = true;
				}else{
					$opt->selected = false;
				}// end if			
				
				$select->appendChild($opt);
			}// next
			
			$span = new sgHTML("div");
			$span->class =  $class."select_div";
			$span->appendChild($select);
			$this->appendChild($span);
		}// end if
		
		//return $main->render();
 	
	}// end function
	
	private function eval_action($page){
		 
		 
		 
		return str_replace("{=page}", $page, $this->action);
		
	}// end function	
	
}// end class
?>